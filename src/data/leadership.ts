// Leadership team shown on /about/.
// TODO before going live: replace the placeholders with real names, titles, bios and photos.
// Put photos in /public/images/leadership/ (square, at least 600x600) and set `photo`,
// e.g. photo: '/images/leadership/firstname-lastname.jpg'. Without a photo, initials are shown.

export type Leader = { name: string; title: string; bio: string; photo?: string; linkedin?: string };

export const leadership: Leader[] = [
  { name: 'Name Surname', title: 'Founder & Managing Director', bio: 'One or two lines about this leader.' },
  { name: 'Name Surname', title: 'Director', bio: 'One or two lines about this leader.' },
  { name: 'Name Surname', title: 'Head of Engineering', bio: 'One or two lines about this leader.' },
];
