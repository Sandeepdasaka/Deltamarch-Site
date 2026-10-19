# Deltamarch-Site

Source for [deltamarch.in](https://deltamarch.in): Deltamarch's company website.

It is a static site built with [Astro](https://astro.build). The build produces plain HTML, CSS and
images, plus one small PHP script (`send-enquiry.php`) that emails form submissions. It runs on the
existing Apache hosting with no Node.js on the server.

## Pages

| URL | Page |
|---|---|
| `/` | Home |
| `/about/` | About, including Leadership |
| `/products/` | All products |
| `/products/credihire/` | CrediHire, with a Request a demo form |
| `/products/localstakes/` | LocalStakes, with a Request a demo form |
| `/products/safe-checkin/` | Safe CheckIn (links to safecheckin.in) |
| `/products/safe-ride/` | Safe Ride |
| `/services/` | Cybersecurity and IT services |
| `/partners/` | Partners, grouped |
| `/reach/` | Reach out: WhatsApp, email, contact form, both offices with maps |

## Where to change things

| What | File |
|---|---|
| Company details, phone, WhatsApp message, email, addresses | `src/data/site.ts` |
| Product names, taglines and card text | `src/data/products.ts` |
| Partners and their logos | `src/data/partners.ts`, logos in `public/images/partners/` |
| Leadership names, titles, bios and photos | `src/data/leadership.ts`, photos in `public/images/leadership/` |
| Who receives form emails | `RECIPIENTS` at the top of `public/send-enquiry.php` |
| Page text | `src/pages/**.astro` |
| Colours, fonts, spacing | `src/styles/global.css` |

## Forms

The **Request a demo** forms (CrediHire, LocalStakes) and the **Reach out** contact form post to
`/send-enquiry.php`, which emails the details to `info@deltamarch.in` and `sandeep.dasaka@gmail.com`.
The visitor's address is set as Reply-To, so you can reply directly.

- Uses PHP's built-in `mail()` (PHP 7.4 or newer). The hosting must allow outgoing mail.
- Emails are sent from `website@deltamarch.in`. For good delivery, the domain's SPF record should
  allow the web server to send mail for deltamarch.in (most cPanel hosts set this up by default).
- Spam protection: a hidden honeypot field, a minimum time on the page, and at most 5 messages per
  hour from one IP address.
- Without JavaScript, the form still works and shows `/thank-you/`.

## Develop

Requires Node.js 22.

```bash
npm ci
npm run dev        # http://localhost:4321
npm run build      # outputs the site to dist/
```

## Deploy (replace the old site)

1. Run `npm run build` (or use a ready-made upload zip of `dist/`, if you were given one).
2. On the server, back up and then delete the old site's files in the web root:
   `index.html`, `index.php`, `.htaccess`, the `assets/` and `lovable-uploads/` folders and the old
   `favicon.ico`.
3. Upload **everything inside `dist/`**, including the hidden `.htaccess` file, to the web root.
4. Check: `https://deltamarch.in/products/` opens directly, and a test message from
   `https://deltamarch.in/reach/` arrives at both email addresses.

The `.htaccess` makes the old addresses (`/about`, `/products`, `/partners`, `/reach`) redirect to
the new pages, serves a proper 404 page, and sets caching and security headers.
