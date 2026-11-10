/**
 * Pannello contenuti (CMS) del sito Pettenò Tours — Keystatic.
 *
 * Il pannello è raggiungibile su /keystatic. In locale (npm run dev) salva i
 * file direttamente nel progetto; online salva su GitHub (ogni modifica è un
 * commit, e il sito si ricostruisce da solo). L'accesso online avviene con un
 * account GitHub autorizzato sul repository: attivare la verifica in due
 * passaggi (2FA) sull'account è il "secondo fattore" del pannello.
 *
 * Tutti i contenuti stanno in src/content/ come file JSON leggibili.
 */
import { config, collection, fields, singleton } from '@keystatic/core';

/** Repository GitHub in cui il pannello online salva le modifiche. */
const GITHUB_REPO = 'DetectiveConan2025/Petteno-Tours-WP';

/* -------------------------------------------------------------------------- */
/* Campi riutilizzati                                                          */
/* -------------------------------------------------------------------------- */

const showToggle = () =>
  fields.checkbox({
    label: 'Mostra questa sezione nel sito',
    defaultValue: true,
  });

const text = (label: string, description?: string) =>
  fields.text({ label, description, validation: { isRequired: false } });

const textarea = (label: string, description?: string) =>
  fields.text({ label, description, multiline: true });

const sectionHead = {
  kicker: text('Occhiello', 'La piccola scritta sopra al titolo.'),
  titolo: text('Titolo'),
  lead: textarea('Testo introduttivo'),
};

/* -------------------------------------------------------------------------- */
/* Configurazione                                                              */
/* -------------------------------------------------------------------------- */

export default config({
  storage: import.meta.env.PROD
    ? { kind: 'github', repo: GITHUB_REPO }
    : { kind: 'local' },

  locale: 'it-IT',

  ui: {
    brand: { name: 'Pettenò Tours' },
    navigation: {
      Azienda: ['azienda'],
      'Home page': ['hero', 'servizi', 'flotta', 'mezzi', 'chiSiamo', 'ctaBand'],
      'Altre pagine': ['rotte', 'contatti'],
    },
  },

  singletons: {
    /* ---- Azienda e recapiti (usati in contatti, footer, SEO) ---------- */
    azienda: singleton({
      label: 'Azienda e recapiti',
      path: 'src/content/azienda',
      format: { data: 'json' },
      schema: {
        ragioneSociale: text('Ragione sociale'),
        piva: text('Partita IVA'),
        telMobile: text('Telefono (cellulare)'),
        telFisso: text('Telefono fisso'),
        email: text('Email'),
        whatsapp: text(
          'Numero WhatsApp',
          'Facoltativo. Formato internazionale senza spazi, es. 393489280768. Se vuoto, il pulsante WhatsApp non compare.'
        ),
        via: text('Via e civico'),
        cap: text('CAP'),
        citta: text('Città'),
        provincia: text('Provincia (sigla)'),
        mapsUrl: fields.url({ label: 'Link Google Maps' }),
        seoDescrizione: textarea(
          'Descrizione per Google',
          'Il testo che appare sotto al titolo nei risultati di ricerca (max ~160 caratteri).'
        ),
      },
    }),

    /* ---- Hero ---------------------------------------------------------- */
    hero: singleton({
      label: 'Hero (sezione iniziale)',
      path: 'src/content/hero',
      format: { data: 'json' },
      schema: {
        badge: text('Badge superiore'),
        titoloRiga1: text('Titolo — prima riga'),
        titoloPrima: text('Titolo — parola prima dell’accento'),
        titoloAccento: text('Titolo — parola evidenziata'),
        titoloDopo: text('Titolo — testo dopo l’accento'),
        lead: textarea('Testo descrittivo'),
        cta1: text('Bottone principale (testo)'),
        cta2: text('Bottone secondario (testo)'),
        immagini: fields.array(
          fields.object({
            immagine: fields.image({
              label: 'Foto',
              directory: 'public/images/hero',
              publicPath: '/images/hero/',
              validation: { isRequired: true },
            }),
            alt: text('Descrizione della foto (per accessibilità e Google)'),
          }),
          {
            label: 'Immagini dello slideshow',
            description: 'Aggiungi quante foto vuoi: scorrono in dissolvenza. La prima è quella che si vede subito.',
            itemLabel: (props) => props.fields.alt.value || 'Foto',
          }
        ),
      },
    }),

    /* ---- Servizi ------------------------------------------------------- */
    servizi: singleton({
      label: 'Servizi',
      path: 'src/content/servizi',
      format: { data: 'json' },
      schema: {
        mostra: showToggle(),
        ...sectionHead,
        elenco: fields.array(
          fields.object({
            titolo: text('Titolo'),
            descrizione: textarea('Descrizione'),
            icona: fields.select({
              label: 'Icona',
              options: [
                { label: 'Mondo (gite e tour)', value: 'mondo' },
                { label: 'Scuola', value: 'scuola' },
                { label: 'Aereo', value: 'aereo' },
                { label: 'Stella (eventi)', value: 'stella' },
                { label: 'Percorso', value: 'percorso' },
                { label: 'Valigetta (aziende)', value: 'valigetta' },
              ],
              defaultValue: 'mondo',
            }),
          }),
          {
            label: 'Schede servizio',
            description: 'La prima scheda è quella in evidenza (più grande).',
            itemLabel: (props) => props.fields.titolo.value || 'Servizio',
          }
        ),
      },
    }),

    /* ---- Flotta (intestazione; i mezzi sono una raccolta a parte) ------ */
    flotta: singleton({
      label: 'Flotta — intestazione',
      path: 'src/content/flotta',
      format: { data: 'json' },
      schema: {
        mostra: showToggle(),
        ...sectionHead,
        nota: textarea('Nota a piè di sezione'),
      },
    }),

    /* ---- Rotte scolastiche -------------------------------------------- */
    rotte: singleton({
      label: 'Rotte scolastiche',
      path: 'src/content/rotte',
      format: { data: 'json' },
      schema: {
        mostra: fields.checkbox({
          label: 'Mostra il richiamo in home e la voce nel menu',
          description: 'La pagina /rotte-scolastiche resta comunque raggiungibile dal suo link (utile per i bandi).',
          defaultValue: true,
        }),
        ...sectionHead,
        cta: text('Bottone del richiamo in home (testo)'),
        linee: fields.array(
          fields.object({
            nome: text('Linea', 'Es. Linea 1 — Salzano · Robegano · Mirano'),
            orari: text('Orari / dettagli', 'Es. Andata 07:10 · Ritorno 13:30'),
          }),
          {
            label: 'Linee',
            itemLabel: (props) => props.fields.nome.value || 'Linea',
          }
        ),
        nota: textarea('Nota sotto la tabella'),
      },
    }),

    /* ---- Chi siamo ----------------------------------------------------- */
    chiSiamo: singleton({
      label: 'Chi siamo',
      path: 'src/content/chi-siamo',
      format: { data: 'json' },
      schema: {
        mostra: showToggle(),
        kicker: text('Occhiello'),
        titolo: text('Titolo'),
        paragrafi: fields.array(textarea('Paragrafo'), {
          label: 'Paragrafi',
          itemLabel: (props) => (props.value || 'Paragrafo').slice(0, 60),
        }),
        cta: text('Bottone (testo)'),
        valori: fields.array(
          fields.object({
            titolo: text('Titolo'),
            descrizione: textarea('Descrizione'),
          }),
          {
            label: 'Valori',
            itemLabel: (props) => props.fields.titolo.value || 'Valore',
          }
        ),
      },
    }),

    /* ---- Pagina Contatti (intestazione del modulo) -------------------- */
    contatti: singleton({
      label: 'Pagina Contatti',
      path: 'src/content/contatti',
      format: { data: 'json' },
      schema: {
        kicker: text('Occhiello'),
        titolo: text('Titolo'),
        intro: textarea('Testo introduttivo'),
      },
    }),

    /* ---- Banner finale prima del footer ------------------------------- */
    ctaBand: singleton({
      label: 'Banner finale (prima del footer)',
      path: 'src/content/cta-band',
      format: { data: 'json' },
      schema: {
        kicker: text('Occhiello'),
        titolo: text('Titolo'),
        bottone: text('Testo del bottone'),
      },
    }),
  },

  collections: {
    /* ---- Mezzi della flotta ------------------------------------------- */
    mezzi: collection({
      label: 'Mezzi della flotta',
      path: 'src/content/mezzi/*',
      slugField: 'nome',
      format: { data: 'json' },
      columns: ['nome', 'posti'],
      schema: {
        nome: fields.slug({ name: { label: 'Nome del mezzo' } }),
        ordine: fields.integer({
          label: 'Ordine',
          description: 'I mezzi sono mostrati dal numero più basso al più alto.',
          defaultValue: 10,
        }),
        posti: text('Posti', 'Es. 48 – 54 posti'),
        descrizione: textarea('Descrizione'),
        dotazioni: fields.array(text('Dotazione'), {
          label: 'Dotazioni',
          itemLabel: (props) => props.value || 'Dotazione',
        }),
        copertina: fields.image({
          label: 'Foto principale',
          directory: 'public/images/flotta',
          publicPath: '/images/flotta/',
        }),
        galleria: fields.array(
          fields.image({
            label: 'Foto',
            directory: 'public/images/flotta',
            publicPath: '/images/flotta/',
            validation: { isRequired: true },
          }),
          {
            label: 'Album foto',
            description: 'Foto aggiuntive: con almeno una, sulla scheda compare l’album sfogliabile.',
          }
        ),
      },
    }),
  },
});
