# Pettenò Tours — sito web (Astro + Keystatic)

Sito vetrina di **Pettenò Tours** (noleggio pullman con conducente, Salzano VE).
Il sito è **statico** (HTML pre-generato: veloce, sicuro, quasi gratis da
ospitare) e i contenuti si modificano da un **pannello** su `/keystatic`,
senza toccare il codice e senza database.

> La versione WordPress precedente resta disponibile nella storia git
> (branch `main` / commit precedenti).

## Cosa si può modificare dal pannello

| Voce del pannello | Cosa contiene |
|---|---|
| Azienda e recapiti | ragione sociale, P.IVA, telefoni, email, WhatsApp, indirizzo, descrizione per Google |
| Hero | testi iniziali e **slideshow foto** (quante foto si vuole) |
| Servizi | schede servizio (titolo, testo, icona), sezione attivabile |
| Flotta — intestazione | titolo, testo, nota |
| Mezzi della flotta | un mezzo per scheda: posti, descrizione, dotazioni, **foto e album** |
| Chi siamo | paragrafi e valori |
| Rotte scolastiche | tabella linee/orari (pagina `/rotte-scolastiche/`, linkabile per i bandi) |
| Pagina Contatti | intestazione del modulo preventivo |
| Banner finale | fascia prima del footer |

I contenuti sono file JSON in `src/content/`, le foto in `public/images/`.

## Sviluppo in locale

```bash
npm install
npm run dev
```

- Sito: http://127.0.0.1:4321
- Pannello: http://127.0.0.1:4321/keystatic (in locale salva direttamente i file)

## Struttura

```
keystatic.config.ts        # modello dei contenuti del pannello
astro.config.mjs           # Astro + adapter Netlify + sitemap
src/content/               # contenuti (JSON) modificati dal pannello
src/lib/site.ts            # lettura contenuti + helper
src/layouts/Base.astro     # <head> SEO/OG/JSON-LD, header, footer
src/components/            # sezioni (Hero, Services, Fleet, …)
src/pages/                 # /, /contatti, /rotte-scolastiche, 404
src/pages/api/preventivo.ts# invio email del modulo (Resend)
src/scripts/main.js        # animazioni, menu, slideshow, lightbox, form
src/styles/global.css      # design token + stili
public/images/             # logo, foto hero e flotta
```

## Messa online (Netlify)

1. Netlify → *Add new site → Import from Git* → questo repository.
   Build e cartella sono già in `netlify.toml`.
2. Imposta le variabili d'ambiente (vedi `.env.example`):
   - `RESEND_API_KEY`, `MAIL_TO`, `MAIL_FROM` per le email del modulo;
   - facoltative `PUBLIC_TURNSTILE_SITE_KEY` / `TURNSTILE_SECRET_KEY` (anti-bot Cloudflare).
3. **Pannello online**: apri `https://<sito>/keystatic` e segui la procedura
   guidata, che crea una GitHub App e fornisce
   `KEYSTATIC_GITHUB_CLIENT_ID`, `KEYSTATIC_GITHUB_CLIENT_SECRET`,
   `KEYSTATIC_SECRET`, `PUBLIC_KEYSTATIC_GITHUB_APP_SLUG` da aggiungere su
   Netlify. Il repository di destinazione è indicato in `keystatic.config.ts`.

### Sicurezza del pannello

Online si entra su `/keystatic` **con un account GitHub** che abbia accesso
in scrittura al repository. Non c'è una password "del sito" da gestire:
- attivare la **verifica in due passaggi (2FA)** su GitHub (app OTP o passkey);
- dare accesso al repository solo alle persone che devono modificare il sito;
- ogni modifica è un commit: si vede chi ha cambiato cosa e si può annullare.

### Email del modulo preventivo

Il modulo invia a `/api/preventivo`, che usa [Resend](https://resend.com).
Per inviare da un indirizzo `@pettenotours.it` va verificato il dominio su
Resend aggiungendo i record DNS **SPF, DKIM e DMARC** che Resend indica.
Finché il dominio non è disponibile, il modulo risponde con un errore e
mostra l'email aziendale come alternativa.

### Dominio

Quando il dominio è sotto il controllo dell'azienda: Netlify → *Domain
management* → aggiungi `pettenotours.it` / `www.pettenotours.it` e aggiorna i
DNS. Attenzione alle caselle email esistenti (record **MX**) e a **DNSSEC**
prima di cambiare nameserver.
