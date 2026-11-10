/**
 * Invio del modulo "Richiedi preventivo".
 *
 * Funzione server (l'unica parte non statica del sito): valida i dati,
 * blocca lo spam (campo trappola + Cloudflare Turnstile se configurato) e
 * spedisce la richiesta via Resend (https://resend.com).
 *
 * Variabili d'ambiente (da impostare sull'hosting, MAI nel codice):
 *   RESEND_API_KEY        chiave API di Resend
 *   MAIL_TO               destinatario delle richieste (es. pettenotours@gmail.com)
 *   MAIL_FROM             mittente verificato su Resend
 *                         (es. "Sito Pettenò Tours <preventivi@pettenotours.it>")
 *   TURNSTILE_SECRET_KEY  facoltativa: chiave segreta Turnstile anti-bot
 */
import type { APIRoute } from 'astro';

export const prerender = false;

const env = (name: string): string | undefined =>
  (import.meta.env as Record<string, string | undefined>)[name] ?? process.env[name];

const json = (body: unknown, status = 200) =>
  new Response(JSON.stringify(body), {
    status,
    headers: { 'Content-Type': 'application/json; charset=utf-8' },
  });

const clean = (v: FormDataEntryValue | null, max: number) =>
  String(v ?? '')
    .replace(/[\u0000-\u0008\u000B\u000C\u000E-\u001F]/g, '')
    .trim()
    .slice(0, max);

const isEmail = (s: string) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(s);

async function verifyTurnstile(token: string, ip: string | null) {
  const secret = env('TURNSTILE_SECRET_KEY');
  if (!secret) return true; // anti-bot non configurato: si salta il controllo
  if (!token) return false;
  const body = new FormData();
  body.append('secret', secret);
  body.append('response', token);
  if (ip) body.append('remoteip', ip);
  const res = await fetch('https://challenges.cloudflare.com/turnstile/v0/siteverify', {
    method: 'POST',
    body,
  });
  const data = (await res.json().catch(() => ({}))) as { success?: boolean };
  return data.success === true;
}

export const POST: APIRoute = async ({ request, clientAddress }) => {
  let form: FormData;
  try {
    form = await request.formData();
  } catch {
    return json({ ok: false, msg: 'Richiesta non valida.' }, 400);
  }

  // Campo trappola compilato = bot. Rispondiamo "ok" senza inviare nulla.
  if (clean(form.get('sito_web'), 200)) return json({ ok: true });

  const nome = clean(form.get('nome'), 120);
  const email = clean(form.get('email'), 200);
  const telefono = clean(form.get('telefono'), 40);
  const persone = clean(form.get('persone'), 6);
  const data = clean(form.get('data'), 20);
  const messaggio = clean(form.get('messaggio'), 4000);

  if (!nome || !isEmail(email) || !telefono || !messaggio) {
    return json({ ok: false, msg: 'Campi obbligatori mancanti.' }, 400);
  }

  let ip: string | null = null;
  try {
    ip = clientAddress;
  } catch {}
  const human = await verifyTurnstile(clean(form.get('cf-turnstile-response'), 2048), ip);
  if (!human) return json({ ok: false, msg: 'Verifica anti-spam non superata.' }, 400);

  const apiKey = env('RESEND_API_KEY');
  const to = env('MAIL_TO');
  const from = env('MAIL_FROM');
  if (!apiKey || !to || !from) {
    console.error('[preventivo] RESEND_API_KEY, MAIL_TO o MAIL_FROM non impostate.');
    return json({ ok: false, msg: 'Invio non configurato.' }, 500);
  }

  const text = [
    `Nome:     ${nome}`,
    `Email:    ${email}`,
    `Telefono: ${telefono}`,
    `Persone:  ${persone || '-'}`,
    `Data:     ${data || '-'}`,
    '',
    messaggio,
  ].join('\n');

  const res = await fetch('https://api.resend.com/emails', {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${apiKey}`,
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      from,
      to: to.split(',').map((s) => s.trim()).filter(Boolean),
      reply_to: email,
      subject: `Richiesta preventivo dal sito — ${nome}`,
      text,
    }),
  });

  if (!res.ok) {
    console.error('[preventivo] Errore Resend', res.status, await res.text().catch(() => ''));
    return json({ ok: false }, 502);
  }
  return json({ ok: true });
};
