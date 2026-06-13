# Pettenò Tours — tema WordPress

Replica in **WordPress** del sito vetrina di Pettenò Tours (noleggio bus
turistici con conducente). È la trasposizione fedele del progetto
[Astro `Petteno-Tours`](https://github.com/DetectiveConan2025/Petteno-Tours):
stessa landing page singola, stessa palette **azzurro / bianco**, stessi
contenuti, sezioni e animazioni — riscritta come tema classico per WordPress.

## Sezioni

La home page è composta dalle stesse sezioni dell'originale, nello stesso ordine:

1. **Header** sticky con navigazione ad ancore e menù mobile
2. **Hero** a tutto schermo con slideshow di sfondo (crossfade + Ken Burns)
3. **Servizi** — griglia "bento" di sei servizi con icone inline
4. **Flotta** — Gran Turismo e Scuolabus con foto e specifiche
5. **Chi siamo** — testo aziendale + valori
6. **Contatti** — recapiti reali + form preventivo
7. **Footer** con colonne di link, recapiti e riga legale

## Struttura del tema

```
.
├── style.css                 # header del tema + design token + tutte le sezioni
├── functions.php             # setup, enqueue, meta SEO/JSON-LD, handler form (AJAX)
├── header.php                # <head> + header sticky con navigazione
├── footer.php                # footer + chiusura <body>
├── front-page.php            # assembla le sezioni della home
├── index.php                 # fallback (stesse sezioni della home)
├── template-parts/
│   ├── hero.php
│   ├── services.php
│   ├── fleet.php
│   ├── about.php
│   └── contact.php
├── assets/
│   ├── js/main.js            # menù mobile, reveal on scroll, anno, invio form
│   └── images/               # logo, foto hero/flotta, favicon, apple-touch-icon
└── screenshot.png            # anteprima per la bacheca di WordPress
```

## Installazione

Questo repository **è** il tema: la cartella va messa fra i temi di WordPress.

1. Copia (o clona) questa cartella in `wp-content/themes/petteno-tours/`:
   ```bash
   git clone <repo> wp-content/themes/petteno-tours
   ```
   In alternativa, comprimi la cartella in uno `.zip` e caricala da
   **Aspetto → Temi → Aggiungi nuovo → Carica tema**.
2. Attiva il tema da **Aspetto → Temi**.
3. (Consigliato) In **Impostazioni → Lettura** imposta come «La tua home page
   visualizza» una **pagina statica** vuota, così WordPress userà
   `front-page.php`. Non è obbligatorio: anche senza, `index.php` mostra le
   stesse sezioni.

Serve WordPress 6.0+ e PHP 7.4+.

## Form preventivo

Nel sito Astro il form inviava a `public/mail.php`. Qui usa il meccanismo
standard di WordPress (`admin-ajax.php` + `wp_mail()`), con verifica del nonce:

- L'email di destinazione è, di default, quella dell'amministratore del sito
  (**Impostazioni → Generali → Indirizzo email**).
- È personalizzabile via filtro:
  ```php
  add_filter( 'petteno_tours_recipient', fn() => 'pettenotours@gmail.com' );
  ```
- Perché `wp_mail()` recapiti in modo affidabile è consigliato un plugin SMTP
  (es. WP Mail SMTP), come su qualsiasi installazione WordPress.

## Personalizzazione

- **Logo** — di default `assets/images/logo.png`; impostando un logo da
  **Aspetto → Personalizza → Identità del sito** viene usato quello.
- **Colori** — design token in `style.css` sotto `:root`.
- **Foto** — hero e flotta in `assets/images/`.
- **Contenuti** — testi e dati nei file in `template-parts/` e nei recapiti di
  `footer.php`.

## Dati dell'azienda (reali)

- **Ragione sociale:** Pettenò Tours S.a.s. di Pettenò Luca & C.
- **Sede:** Via Leonardo da Vinci 39/B, 30030 Salzano (VE)
- **Telefono:** +39 348 928 0768 — Fisso: 041 482231
- **Email:** pettenotours@gmail.com
- **P.IVA:** 02172370278
