/**
 * Lettura dei contenuti gestiti dal pannello (Keystatic) + piccoli helper.
 *
 * I contenuti vengono letti al momento della build: il sito pubblicato è
 * statico (HTML puro), quindi veloce e senza database da attaccare.
 */
import { createReader } from '@keystatic/core/reader';
import keystaticConfig from '../../keystatic.config';

export const reader = createReader(process.cwd(), keystaticConfig);

/** Legge un singleton e fallisce in modo chiaro se manca il file. */
async function must<T>(p: Promise<T | null>, name: string): Promise<T> {
  const v = await p;
  if (!v) throw new Error(`Contenuto mancante: src/content/${name}.json`);
  return v;
}

export const getAzienda = () => must(reader.singletons.azienda.read(), 'azienda');
export const getHero = () => must(reader.singletons.hero.read(), 'hero');
export const getServizi = () => must(reader.singletons.servizi.read(), 'servizi');
export const getFlotta = () => must(reader.singletons.flotta.read(), 'flotta');
export const getRotte = () => must(reader.singletons.rotte.read(), 'rotte');
export const getChiSiamo = () => must(reader.singletons.chiSiamo.read(), 'chi-siamo');
export const getContatti = () => must(reader.singletons.contatti.read(), 'contatti');
export const getCtaBand = () => must(reader.singletons.ctaBand.read(), 'cta-band');

/** Mezzi della flotta, ordinati per il campo "ordine" (poi per nome). */
export async function getMezzi() {
  const all = await reader.collections.mezzi.all();
  return all
    .map(({ slug, entry }) => ({ slug, ...entry }))
    .sort((a, b) => (a.ordine ?? 0) - (b.ordine ?? 0) || a.nome.localeCompare(b.nome, 'it'));
}

export type Azienda = Awaited<ReturnType<typeof getAzienda>>;

/** "tel:+393489280768" a partire dal numero come viene mostrato. */
export const telHref = (num: string) => 'tel:' + num.replace(/[^0-9+]/g, '');

/** "https://wa.me/39…" oppure null se il numero WhatsApp non è impostato. */
export const waHref = (num: string | undefined) => {
  const digits = (num ?? '').replace(/\D/g, '');
  return digits ? `https://wa.me/${digits}` : null;
};

export const addressFull = (a: Azienda) => `${a.via}, ${a.cap} ${a.citta} (${a.provincia})`;
export const addressShort = (a: Azienda) => `${a.via}, ${a.citta} (${a.provincia})`;

/** URL delle pagine principali (unico punto da cambiare). */
export const URLS = {
  home: '/',
  contatti: '/contatti/',
  rotte: '/rotte-scolastiche/',
  anchor: (hash: string) => `/${hash}`,
};

/** Voci del menu, in base alle sezioni attive. */
export async function getNavLinks() {
  const [servizi, flotta, rotte, chiSiamo] = await Promise.all([
    getServizi(),
    getFlotta(),
    getRotte(),
    getChiSiamo(),
  ]);
  const links: { href: string; label: string }[] = [];
  if (servizi.mostra) links.push({ href: URLS.anchor('#servizi'), label: 'Servizi' });
  if (flotta.mostra) links.push({ href: URLS.anchor('#flotta'), label: 'Flotta' });
  if (rotte.mostra) links.push({ href: URLS.rotte, label: 'Rotte scolastiche' });
  if (chiSiamo.mostra) links.push({ href: URLS.anchor('#chi-siamo'), label: 'Chi siamo' });
  links.push({ href: URLS.contatti, label: 'Contatti' });
  return { links, servizi, flotta, rotte, chiSiamo };
}
