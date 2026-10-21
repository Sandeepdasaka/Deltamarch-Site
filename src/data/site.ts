// Company facts used across the site. Edit here, not in individual pages.

const whatsappNumber = '919908843232';
const whatsappGreeting = "Hi Deltamarch, I'd like to know more about your products and services.";

export const company = {
  name: 'Deltamarch',
  legalName: 'Deltamarch Technologies Private Limited',
  cin: 'U72200AP2015PTC097650',
  founded: 2015,
  email: 'info@deltamarch.in',
  webmailUrl: 'https://webmail.deltamarch.in/',
  phoneDisplay: '+91 99088 43232',
  // wa.me opens a new chat in the WhatsApp app (phones) or WhatsApp Web (computers).
  whatsappUrl: `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(whatsappGreeting)}`,
  offices: [
    {
      label: 'India (HQ)',
      lines: ["Suite 302, Archana Arcade, St. John's Road", 'Secunderabad 500025, Telangana, India'],
      // This wording makes Google Maps pin the building itself.
      mapQuery: 'Archana Arcade, Secunderabad, Telangana 500025',
    },
    {
      label: 'USA',
      lines: ['8 The Green, Ste A', 'Dover, DE 19901, USA'],
      mapQuery: '8 The Green, Ste A, Dover, DE 19901',
    },
  ],
};

export const mapsSearchUrl = (query: string) =>
  `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(query)}`;

export const mapsEmbedUrl = (query: string) =>
  `https://maps.google.com/maps?q=${encodeURIComponent(query)}&z=15&output=embed`;

export const nav = [
  { label: 'About', href: '/about/' },
  { label: 'Products', href: '/products/' },
  { label: 'Services', href: '/services/' },
  { label: 'Partners', href: '/partners/' },
];
