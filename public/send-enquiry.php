<?php
/**
 * Deltamarch website: sends "Request a demo" and "Reach out" form submissions by email.
 *
 * Works on PHP 7.4 and newer. Uses PHP's built-in mail(), which the web host must allow. Replies go straight to the
 * visitor because their address is set as Reply-To.
 *
 * Returns JSON when the page submits with JavaScript (Accept: application/json);
 * otherwise redirects to /thank-you/ so the form also works without JavaScript.
 */

declare(strict_types=1);

// ---- Settings -------------------------------------------------------------------------

const RECIPIENTS = ['info@deltamarch.in', 'sandeep.dasaka@gmail.com'];
// Must be an address on the deltamarch.in domain so the email passes SPF checks.
const FROM_ADDRESS = 'website@deltamarch.in';
const FROM_NAME = 'Deltamarch Website';
const MIN_SECONDS_ON_PAGE = 3;      // faster submissions are treated as bots
const MAX_PER_IP_PER_HOUR = 5;

// ---- Helpers --------------------------------------------------------------------------

$wantsJson = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;

/** Multibyte-safe substring, falling back to substr() if mbstring is not installed. */
function cut(string $value, int $max): string
{
    return function_exists('mb_substr') ? mb_substr($value, 0, $max, 'UTF-8') : substr($value, 0, $max);
}

function respond(bool $ok, string $error = '', int $status = 200): void
{
    global $wantsJson;
    if ($wantsJson) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode($ok ? ['ok' => true] : ['ok' => false, 'error' => $error]);
    } elseif ($ok) {
        header('Location: /thank-you/', true, 303);
    } else {
        http_response_code($status);
        header('Content-Type: text/html; charset=utf-8');
        $safe = htmlspecialchars($error ?: 'Your message could not be sent.', ENT_QUOTES, 'UTF-8');
        echo "<!doctype html><meta charset=utf-8><meta name=viewport content='width=device-width'>"
            . "<title>Message not sent | Deltamarch</title>"
            . "<body style='font-family:system-ui,sans-serif;max-width:560px;margin:15vh auto;padding:0 20px;line-height:1.6'>"
            . "<h1>Message not sent</h1><p>{$safe}</p>"
            . "<p>Please go back and try again, email <a href='mailto:info@deltamarch.in'>info@deltamarch.in</a> "
            . "or message us on <a href='https://wa.me/919908843232'>WhatsApp</a>.</p></body>";
    }
    exit;
}

/** Single-line field: trimmed, length-limited, with line breaks removed (prevents header injection). */
function field(string $name, int $max): string
{
    $value = trim((string) ($_POST[$name] ?? ''));
    $value = preg_replace('/[\r\n\t]+/', ' ', $value) ?? '';
    return cut($value, $max);
}

function too_many_requests(string $ip): bool
{
    $file = sys_get_temp_dir() . '/deltamarch-enquiry-' . hash('sha256', $ip);
    $now = time();
    $recent = [];
    if (is_readable($file)) {
        $stamps = json_decode((string) file_get_contents($file), true);
        $recent = array_values(array_filter(is_array($stamps) ? $stamps : [], fn($t) => is_int($t) && $t > $now - 3600));
    }
    if (count($recent) >= MAX_PER_IP_PER_HOUR) {
        return true;
    }
    $recent[] = $now;
    @file_put_contents($file, json_encode($recent), LOCK_EX);
    return false;
}

// ---- Request checks -------------------------------------------------------------------

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: /reach/', true, 303);
    exit;
}

// Honeypot: a hidden field that people never see, but bots fill in. Pretend success.
if (trim((string) ($_POST['website'] ?? '')) !== '') {
    respond(true);
}

$started = (int) ($_POST['started'] ?? 0);
if ($started > 0 && time() - $started < MIN_SECONDS_ON_PAGE) {
    respond(true);
}

if (too_many_requests($_SERVER['REMOTE_ADDR'] ?? 'unknown')) {
    respond(false, 'Too many messages were sent from your connection. Please try again in an hour.', 429);
}

// ---- Validate -------------------------------------------------------------------------

$type    = ($_POST['form_type'] ?? '') === 'demo' ? 'demo' : 'contact';
$product = in_array($_POST['product'] ?? '', ['CrediHire', 'LocalStakes'], true) ? $_POST['product'] : '';
$name    = field('name', 120);
$email   = field('email', 160);
$phone   = field('phone', 30);
$company = field('company', 160);
$topic   = in_array($_POST['topic'] ?? '', ['Products', 'Cybersecurity', 'IT services', 'Partnership', 'Other'], true)
    ? $_POST['topic'] : '';
$message = cut(trim((string) ($_POST['message'] ?? '')), 4000);

if ($name === '') {
    respond(false, 'Please enter your name.', 422);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'Please enter a valid email address.', 422);
}
if ($type === 'demo' && ($product === '' || $phone === '' || $company === '')) {
    respond(false, 'Please fill in your phone number and company.', 422);
}
if ($type === 'contact' && $message === '') {
    respond(false, 'Please enter a message.', 422);
}

// ---- Compose & send -------------------------------------------------------------------

$subject = $type === 'demo'
    ? "Demo request: {$product} from {$name}" . ($company !== '' ? " ({$company})" : '')
    : 'Website enquiry' . ($topic !== '' ? " [{$topic}]" : '') . " from {$name}";

$rows = [
    'Form'    => $type === 'demo' ? "Request a demo ({$product})" : 'Reach out',
    'Name'    => $name,
    'Email'   => $email,
    'Phone'   => $phone ?: '-',
    'Company' => $company ?: '-',
];
if ($type === 'contact') {
    $rows['Topic'] = $topic ?: '-';
}

$body = '';
foreach ($rows as $label => $value) {
    $body .= str_pad($label . ':', 10) . $value . "\n";
}
$body .= "\nMessage:\n" . ($message !== '' ? $message : '-') . "\n\n";
$body .= "--\nSent from the form on deltamarch.in at " . gmdate('Y-m-d H:i') . " UTC.\n";
$body .= "Reply to this email to answer {$name} directly.\n";

$encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
$encodedFrom = '=?UTF-8?B?' . base64_encode(FROM_NAME) . '?=';
$encodedName = '=?UTF-8?B?' . base64_encode($name) . '?=';
$headers = [
    'From' => "{$encodedFrom} <" . FROM_ADDRESS . '>',
    'Reply-To' => "{$encodedName} <{$email}>",
    'MIME-Version' => '1.0',
    'Content-Type' => 'text/plain; charset=UTF-8',
    'Content-Transfer-Encoding' => '8bit',
    'X-Mailer' => 'Deltamarch website',
];

$sent = mail(implode(', ', RECIPIENTS), $encodedSubject, $body, $headers, '-f' . FROM_ADDRESS);

if (!$sent) {
    error_log('Deltamarch enquiry form: mail() failed for ' . $email);
    respond(false, '', 500);
}

respond(true);
