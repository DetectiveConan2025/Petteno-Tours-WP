// @ts-check
import { defineConfig } from 'astro/config';
import netlify from '@astrojs/netlify';
import react from '@astrojs/react';
import markdoc from '@astrojs/markdoc';
import sitemap from '@astrojs/sitemap';
import keystatic from '@keystatic/astro';

// Sito statico (HTML pre-generato). Restano "server" solo il pannello
// /keystatic e l'invio del modulo /api/preventivo, eseguiti come funzioni
// sull'hosting (Netlify).
export default defineConfig({
  site: 'https://www.pettenotours.it',
  trailingSlash: 'ignore',
  // Vecchi indirizzi del sito WordPress → nuove pagine (301, per Google e
  // per i link già condivisi).
  redirects: {
    '/about': { status: 301, destination: '/#chi-siamo' },
    '/contact': { status: 301, destination: '/contatti/' },
  },
  adapter: netlify(),
  integrations: [
    react(),
    markdoc(),
    keystatic(),
    sitemap({
      filter: (page) => !page.includes('/keystatic') && !page.includes('/api/'),
    }),
  ],
});
