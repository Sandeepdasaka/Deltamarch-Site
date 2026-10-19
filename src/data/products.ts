// The four Deltamarch products. Card text on Home and /products/ comes from here.

export type Product = {
  slug: string;
  name: string;
  category: string;
  tagline: string;
  summary: string;
  logo: string;
  logoAlt: string;
  /** 'demo' shows the Request a demo form; 'external' links out; 'contact' points to Reach Out. */
  cta: { kind: 'demo' } | { kind: 'external'; label: string; href: string } | { kind: 'contact' };
};

export const products: Product[] = [
  {
    slug: 'credihire',
    name: 'CrediHire',
    category: 'Hiring · B2B SaaS',
    tagline: 'The Hiring Trust Network.',
    summary:
      "Know a candidate's hiring track record before you make the offer. Hiring history, verification and a trust score in one profile, identified by PAN.",
    logo: '/images/products/credihire.png',
    logoAlt: 'CrediHire logo',
    cta: { kind: 'demo' },
  },
  {
    slug: 'localstakes',
    name: 'LocalStakes',
    category: 'Community · Consumer app',
    tagline: 'Back the neighbourhood spots you love.',
    summary:
      'Community-based funding for neighbourhood businesses. Regulars come together to help the cafés, gyms, bakeries and salons they love grow.',
    logo: '/images/products/localstakes.png',
    logoAlt: 'LocalStakes logo',
    cta: { kind: 'demo' },
  },
  {
    slug: 'safe-checkin',
    name: 'Safe CheckIn',
    category: 'Public safety · Hospitality',
    tagline: 'Every guest, accounted for.',
    summary:
      'AI-powered visitor management for rental accommodations. Visitor details reach local law enforcement in a single click.',
    logo: '/images/products/safe-checkin.png',
    logoAlt: 'Safe CheckIn logo',
    cta: { kind: 'external', label: 'Visit safecheckin.in', href: 'https://safecheckin.in' },
  },
  {
    slug: 'safe-ride',
    name: 'Safe Ride',
    category: 'Public safety · Urban transport',
    tagline: 'Scan. Know. Ride safe.',
    summary:
      'A QR-based platform used by the Telangana Police. Passengers scan to see vehicle details, then rate the ride or report a problem.',
    logo: '/images/products/safe-ride.png',
    logoAlt: 'Safe Ride logo',
    cta: { kind: 'contact' },
  },
];

export const productBySlug = (slug: string) => {
  const product = products.find((p) => p.slug === slug);
  if (!product) throw new Error(`Unknown product: ${slug}`);
  return product;
};
