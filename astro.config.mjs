// @ts-check
import { defineConfig } from 'astro/config';
import sitemap from '@astrojs/sitemap';

// Static build: every page becomes <page>/index.html, so Apache serves deep
// links (e.g. /products/) directly without any rewrite rules.
export default defineConfig({
  site: 'https://deltamarch.in',
  trailingSlash: 'always',
  build: { format: 'directory' },
  integrations: [
    sitemap({ filter: (page) => !page.includes('/thank-you/') }),
  ],
});
