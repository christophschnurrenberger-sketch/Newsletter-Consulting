'use strict';

const fs = require('fs');
const path = require('path');
const { affiliates } = require('./config');

const IMG_DIR = path.join(__dirname, 'assets', 'img');

/* ------------------------------------------------------------------ *
 * Hilfsfunktionen
 * ------------------------------------------------------------------ */

function esc(value) {
  return String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

function slug(value) {
  return String(value)
    .toLowerCase()
    .replace(/ä/g, 'ae')
    .replace(/ö/g, 'oe')
    .replace(/ü/g, 'ue')
    .replace(/ß/g, 'ss')
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');
}

/** 1250 → "1.250" (deutsche Tausenderpunkte, ohne Intl-Abhaengigkeit). */
function num(value) {
  return String(value).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

/* ------------------------------------------------------------------ *
 * Icons (inline, kein externes CDN)
 * ------------------------------------------------------------------ */

const ICONS = {
  check: '<polyline points="20 6 9 17 4 12"></polyline>',
  arrow: '<line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline>',
  arrowLeft: '<line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>',
  bike: '<circle cx="5.5" cy="17.5" r="3.5"></circle><circle cx="18.5" cy="17.5" r="3.5"></circle><path d="M15 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"></path><path d="M12 17.5V14l-3-3 4-3 2 3h3"></path>',
  bag: '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path>',
  tent: '<path d="M3.5 21 12 4l8.5 17"></path><path d="M12 4v17"></path><path d="M8 21l4-6 4 6"></path>',
  map: '<polygon points="1 6 8 3 16 6 23 3 23 18 16 21 8 18 1 21"></polygon><line x1="8" y1="3" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="21"></line>',
  mountain: '<path d="M8 3 2 21h20L14 3l-3 7-3-7z"></path>',
  route: '<circle cx="6" cy="19" r="3"></circle><circle cx="18" cy="5" r="3"></circle><path d="M9 19h4a4 4 0 0 0 0-8H8a4 4 0 0 1 0-8h4"></path>',
  weight: '<circle cx="12" cy="5" r="3"></circle><path d="M6.5 8h11l2.5 13H4z"></path>',
  drop: '<path d="M12 2.7 6.6 8.8a7.5 7.5 0 1 0 10.8 0z"></path>',
  wallet: '<path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"></path><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>',
  clock: '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
  book: '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>',
  tool: '<path d="M14.7 6.3a4 4 0 0 1 5 5L21 12l-3 3-9.3 5.3a2.1 2.1 0 0 1-2.9-2.9L11 8l3-3z"></path><line x1="6" y1="18" x2="9" y2="15"></line>',
  alert: '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line>',
  info: '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line>',
  bulb: '<path d="M9 18h6"></path><path d="M10 22h4"></path><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"></path>',
  x: '<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>',
  mail: '<rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>',
  menu: '<line x1="4" x2="20" y1="12" y2="12"></line><line x1="4" x2="20" y1="6" y2="6"></line><line x1="4" x2="20" y1="18" y2="18"></line>',
  chevron: '<polyline points="6 9 12 15 18 9"></polyline>',
  users: '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
  trend: '<polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline>',
  shield: '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>',
  sun: '<circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"></path>',
  image: '<rect width="18" height="18" x="3" y="3" rx="2"></rect><circle cx="9" cy="9" r="2"></circle><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>',
};

function icon(name, className = 'ico') {
  const paths = ICONS[name] || ICONS.check;
  return `<svg class="${className}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">${paths}</svg>`;
}

/* ------------------------------------------------------------------ *
 * Bilder
 *
 * Konvention: Zu jeder Seite gehoert ein Bild unter demselben Pfad,
 *   /taschen/satteltasche.html  →  src/assets/img/taschen/satteltasche.jpg
 * Fehlt die Datei, erscheint ein beschrifteter Platzhalter, der den
 * erwarteten Dateinamen nennt. So ist jederzeit sichtbar, was noch fehlt.
 * ------------------------------------------------------------------ */

/** Leitet den erwarteten Bildpfad aus dem Seitenpfad ab. */
function imageFor(href) {
  const clean = href === '/' ? '/start' : href.replace(/\.html$/, '').replace(/\/$/, '/uebersicht');
  return `/assets/img${clean}.jpg`;
}

/** Liegt das Bild tatsaechlich im Projekt? */
function imageExists(src) {
  if (!src) return false;
  const rel = src.replace(/^\/assets\/img\//, '');
  return fs.existsSync(path.join(IMG_DIR, rel));
}

/**
 * Bild mit Rahmen, Bildunterschrift und Platzhalter-Fallback.
 *
 * @param {object} o
 * @param {string} o.src     Pfad ab /assets/img/…
 * @param {string} o.alt     Alternativtext (Pflicht, sobald ein Bild liegt)
 * @param {string} [o.ratio] CSS-Seitenverhaeltnis, z. B. '16 / 9'
 * @param {string} [o.caption]
 * @param {string} [o.credit] Bildnachweis
 * @param {boolean} [o.eager] Erstes Bild der Seite nicht lazy laden
 */
function figure({ src, alt = '', ratio = '16 / 9', caption, credit, eager = false }) {
  const has = imageExists(src);
  const inner = has
    ? `<img src="${esc(src)}" alt="${esc(alt)}" loading="${eager ? 'eager' : 'lazy'}" decoding="async">`
    : `<div class="fig-placeholder">
         ${icon('image', 'ico')}
         <span>Bild ergänzen</span>
         <code>src/assets/img${esc(src.replace('/assets/img', ''))}</code>
       </div>`;

  const captionHtml =
    caption || credit
      ? `<figcaption class="fig-caption">${caption ? `<b>${caption}</b>` : ''}${
          caption && credit ? ' ' : ''
        }${credit ? `<span class="fig-credit">${esc(credit)}</span>` : ''}</figcaption>`
      : '';

  return `<figure class="fig">
  <div class="fig-frame" style="--ratio:${ratio}">${inner}</div>
  ${captionHtml}
</figure>`;
}

/* ------------------------------------------------------------------ *
 * Affiliate-Links
 * ------------------------------------------------------------------ */

/**
 * Baut einen Partnerlink.
 *
 * Solange fuer den Partner kein echtes Deeplink-Template bzw. Tag hinterlegt
 * ist, wird die Ziel-URL unveraendert ausgegeben. Die Seite bleibt dadurch
 * jederzeit funktionsfaehig, auch vor der Programm-Freischaltung.
 */
function affLink(partnerKey, targetUrl) {
  const partner = affiliates.partners[partnerKey];
  if (!partner || !affiliates.enabled) return targetUrl;

  if (partner.template && !partner.template.startsWith('BITTE_')) {
    return partner.template.replace('{{url}}', encodeURIComponent(targetUrl));
  }
  if (partner.suffix && !partner.suffix.includes('DEIN-')) {
    return targetUrl + (targetUrl.includes('?') ? '&' : '?') + partner.suffix.replace(/^\?/, '');
  }
  return targetUrl;
}

/** Textlink mit Partner-Kennzeichnung. */
function affA(partnerKey, targetUrl, label) {
  const href = affLink(partnerKey, targetUrl);
  return `<a class="aff-link" href="${esc(href)}" rel="sponsored nofollow noopener" target="_blank">${esc(label)}<span class="aff-star" aria-label="Partnerlink">*</span></a>`;
}

/* ------------------------------------------------------------------ *
 * Bausteine
 * ------------------------------------------------------------------ */

/** Ressort-Label in Versalien. tone: rust (Standard) | trail | light */
function kicker(text, tone = '') {
  return `<p class="label${tone ? ` label--${tone}` : ''}">${esc(text)}</p>`;
}
const label = kicker;

/**
 * Abschnittskopf: Titel links, optionaler Verweis rechts,
 * darunter eine kraeftige Linie.
 */
function sectionHead(title, { more, sub } = {}) {
  return `<div class="section-head">
    <h2 class="section-title">${title}</h2>
    ${more ? `<a class="section-head-more" href="${esc(more.href)}">${esc(more.label)} ${icon('arrow', 'ico')}</a>` : ''}
  </div>${sub ? `<p class="section-sub">${sub}</p>` : ''}`;
}

/**
 * Redaktioneller Teaser.
 *
 * variant: 'lead'     grosses Bild, grosse Headline (Aufmacher)
 *          'standard' Bild oben, Headline darunter
 *          'row'      kleines Bild links, Text rechts
 *          'numbered' ohne Bild, mit laufender Nummer
 */
function teaser({ href, title, dek, section, meta = [], variant = 'standard', index, ratio, image, alt }) {
  const src = image || imageFor(href);
  const showImage = variant !== 'numbered';
  const imgRatio = ratio || (variant === 'row' ? '1 / 1' : variant === 'lead' ? '16 / 9' : '3 / 2');

  const metaHtml = meta.length
    ? `<p class="teaser-meta">${meta.map((m) => `<span>${esc(m)}</span>`).join('')}</p>`
    : '';

  return `<a class="teaser teaser--${variant}" href="${esc(href)}">
    ${showImage ? figure({ src, alt: alt || stripTags(title), ratio: imgRatio }) : ''}
    ${variant === 'numbered' ? `<span class="teaser-num" aria-hidden="true">${String(index).padStart(2, '0')}</span>` : ''}
    <div class="teaser-body">
      ${section ? `<p class="label">${esc(section)}</p>` : ''}
      <h3 class="teaser-title">${title}</h3>
      ${dek ? `<p class="teaser-dek">${dek}</p>` : ''}
      ${metaHtml}
    </div>
  </a>`;
}

/** Raster fuer Teaser. columns: 2 | 3 | 4 | 'lead' */
function magazine(items, columns = 3) {
  return `<div class="mag mag--${columns}">${items.join('')}</div>`;
}

/**
 * Seitenkopf: Ressort, Titel, Vorspann, Meta-Zeile und – sofern ein Bild
 * vorgesehen ist – das Aufmacherbild.
 */
function pageHero({ kicker: k, title, lead, meta = [], href, image = true, caption, credit }) {
  const metaHtml = meta.length
    ? `<p class="article-meta">${meta
        .map((m) => `<span>${icon(m.icon || 'check', 'ico')}${esc(m.text)}</span>`)
        .join('')}</p>`
    : '';

  const hero =
    image && href
      ? `<div class="article-hero">${figure({
          src: imageFor(href),
          alt: stripTags(title),
          ratio: '21 / 9',
          caption,
          credit,
          eager: true,
        })}</div>`
      : '';

  return `<header class="article-head">
  <div class="wrap">
    ${k ? kicker(k) : ''}
    <h1 class="page-title">${title}</h1>
    ${lead ? `<p class="page-lead">${lead}</p>` : ''}
    ${metaHtml}
    ${hero}
  </div>
</header>`;
}

/** Abschnitt mit optionaler Hintergrundfarbe. */
function section(inner, { id, tone = 'plain', narrow = false, className = '' } = {}) {
  return `<section class="band band--${tone} ${className}"${id ? ` id="${id}"` : ''}>
  <div class="wrap${narrow ? ' wrap--narrow' : ''}">${inner}</div>
</section>`;
}

/** Fliesstext-Container. Innerhalb davon wird normales HTML formatiert. */
function prose(inner) {
  return `<div class="prose">${inner}</div>`;
}

/** Ueberschrift mit automatischem Anker (fuer das Inhaltsverzeichnis). */
function h2(text, id) {
  const anchor = id || slug(text);
  return `<h2 id="${anchor}">${text}</h2>`;
}

function h3(text, id) {
  const anchor = id || slug(text);
  return `<h3 id="${anchor}">${text}</h3>`;
}

/**
 * Inhaltsverzeichnis. Erwartet [{ label, id }].
 * `title: false` unterdrueckt die Ueberschrift – dann liefert der Aufrufer
 * seine eigene, etwa in der Seitenspalte.
 */
function toc(items, title = 'Inhalt dieser Seite') {
  return `<nav class="toc" aria-label="Inhalt dieser Seite">
  ${title === false ? '' : `<p class="toc-title">${esc(title)}</p>`}
  <ol>${items.map((i) => `<li><a href="#${esc(i.id || slug(i.label))}">${esc(i.label)}</a></li>`).join('')}</ol>
</nav>`;
}

/** Farbig abgesetzter Hinweiskasten. tone: info | warn | tip | money */
function callout(title, body, tone = 'info') {
  const iconName = { info: 'info', warn: 'alert', tip: 'bulb', money: 'wallet' }[tone] || 'info';
  return `<aside class="callout callout--${tone}">
  <div class="callout-icon">${icon(iconName, 'ico')}</div>
  <div class="callout-body"><p class="callout-title">${title}</p>${body}</div>
</aside>`;
}

/** Kernaussage als grosse Zahl. Erwartet [{ value, label, note }]. */
function stats(items) {
  return `<ul class="stat-row">${items
    .map(
      (s) => `<li class="stat">
      <span class="stat-value">${s.value}</span>
      <span class="stat-label">${s.label}</span>
      ${s.note ? `<span class="stat-note">${s.note}</span>` : ''}
    </li>`
    )
    .join('')}</ul>`;
}

/** Karten-Raster. Erwartet [{ title, text, icon, href, badge, list }]. */
function cards(items, { columns = 3 } = {}) {
  return `<div class="card-grid card-grid--${columns}">${items
    .map((c) => {
      const inner = `
      ${c.badge ? `<span class="card-badge">${esc(c.badge)}</span>` : ''}
      <h3 class="card-title">${c.title}</h3>
      ${c.text ? `<p class="card-text">${c.text}</p>` : ''}
      ${c.list ? `<ul class="card-list">${c.list.map((l) => `<li>${icon('check', 'ico')}<span>${l}</span></li>`).join('')}</ul>` : ''}
      ${c.href ? `<span class="card-more">${c.linkLabel || 'Mehr erfahren'} ${icon('arrow', 'ico')}</span>` : ''}`;
      return c.href
        ? `<a class="card card--link" href="${esc(c.href)}">${inner}</a>`
        : `<article class="card">${inner}</article>`;
    })
    .join('')}</div>`;
}

/** Nummerierte Schrittfolge. Erwartet [{ title, text, meta, list }]. */
function steps(items) {
  return `<ol class="steps">${items
    .map(
      (s, i) => `<li class="step">
      <span class="step-index">${String(i + 1).padStart(2, '0')}</span>
      <div class="step-body">
        <h3 class="step-title">${s.title}</h3>
        ${s.meta ? `<p class="step-meta">${s.meta}</p>` : ''}
        ${s.text ? `<p class="step-text">${s.text}</p>` : ''}
        ${s.list ? `<ul class="tick-list">${s.list.map((l) => `<li>${icon('check', 'ico')}<span>${l}</span></li>`).join('')}</ul>` : ''}
      </div>
    </li>`
    )
    .join('')}</ol>`;
}

/** Abhak-Liste. */
function checklist(items, { tone = 'do' } = {}) {
  const ic = tone === 'dont' ? 'x' : 'check';
  return `<ul class="tick-list tick-list--${tone}">${items
    .map((i) => `<li>${icon(ic, 'ico')}<span>${i}</span></li>`)
    .join('')}</ul>`;
}

/** Zwei Spalten: Das machst du / Das lässt du. */
function doDont({ doTitle = 'Das nimmst du mit', doItems = [], dontTitle = 'Das bleibt zu Hause', dontItems = [] }) {
  return `<div class="do-dont">
  <div class="do-dont-col do-dont-col--do">
    <h3>${icon('check', 'ico')} ${esc(doTitle)}</h3>
    ${checklist(doItems)}
  </div>
  <div class="do-dont-col do-dont-col--dont">
    <h3>${icon('x', 'ico')} ${esc(dontTitle)}</h3>
    ${checklist(dontItems, { tone: 'dont' })}
  </div>
</div>`;
}

/** Tabelle mit horizontalem Scroll auf kleinen Bildschirmen. */
function table({ head, rows, caption, note }) {
  return `<div class="table-wrap">
  <table class="data-table">
    ${caption ? `<caption>${caption}</caption>` : ''}
    <thead><tr>${head.map((h) => `<th scope="col">${h}</th>`).join('')}</tr></thead>
    <tbody>${rows
      .map((r) => `<tr>${r.map((cell, i) => (i === 0 ? `<th scope="row">${cell}</th>` : `<td>${cell}</td>`)).join('')}</tr>`)
      .join('')}</tbody>
  </table>
</div>${note ? `<p class="table-note">${note}</p>` : ''}`;
}

/* ------------------------------------------------------------------ *
 * Bikepacking-eigene Bausteine
 * ------------------------------------------------------------------ */

/**
 * Packliste mit Gewichtsspalte und automatischer Summe.
 *
 * Gewichte sind Richtwerte in Gramm für gängige, nicht-ultraleichte
 * Ausrüstung. Sie dienen der Einordnung – nicht als Produktversprechen.
 *
 * @param {object} o
 * @param {string} [o.title]
 * @param {Array}  o.items  [{ name, g, note, tag: 'pflicht'|'sinnvoll'|'ballast' }]
 * @param {string} [o.totalLabel]
 */
function weightList({ title, items, totalLabel = 'Summe' }) {
  const sum = items.filter((i) => i.tag !== 'ballast').reduce((acc, i) => acc + (i.g || 0), 0);

  const rows = items
    .map((i) => {
      const tag = i.tag || 'pflicht';
      const tagLabel = { pflicht: 'Pflicht', sinnvoll: 'Sinnvoll', ballast: 'Ballast' }[tag] || tag;
      return `<li class="wl-item wl-item--${tag}">
      <span class="wl-name">${i.name}${i.note ? `<span class="wl-note">${i.note}</span>` : ''}</span>
      <span class="wl-tag">${tagLabel}</span>
      <span class="wl-g">${i.g ? `${num(i.g)}&thinsp;g` : '–'}</span>
    </li>`;
    })
    .join('');

  return `<div class="wl">
  ${title ? `<p class="wl-title">${esc(title)}</p>` : ''}
  <ul class="wl-list">${rows}</ul>
  <p class="wl-total"><span>${esc(totalLabel)} ohne Ballast</span><strong>${(sum / 1000).toFixed(1).replace('.', ',')}&thinsp;kg</strong></p>
</div>`;
}

/**
 * Routenkarte mit den Kennzahlen, die bei der Auswahl wirklich zaehlen.
 *
 * @param {object} o
 * @param {string} o.title
 * @param {string} o.region
 * @param {string} o.km
 * @param {string} o.hm      Hoehenmeter
 * @param {string} o.days
 * @param {string} o.surface Untergrund
 * @param {string} o.level   Schwierigkeit: leicht | mittel | anspruchsvoll
 * @param {string} o.text
 * @param {string} [o.train] Bahnanreise
 * @param {Array}  [o.highlights]
 */
function routeCard({ title, region, km, hm, days, surface, level = 'leicht', text, train, highlights = [] }) {
  return `<article class="route">
  <div class="route-head">
    <span class="route-level route-level--${slug(level)}">${esc(level)}</span>
    <h3 class="route-title">${title}</h3>
    ${region ? `<p class="route-region">${icon('map', 'ico')}${esc(region)}</p>` : ''}
  </div>
  <ul class="route-figures">
    <li><span>Distanz</span><strong>${esc(km)}</strong></li>
    <li><span>Höhenmeter</span><strong>${esc(hm)}</strong></li>
    <li><span>Dauer</span><strong>${esc(days)}</strong></li>
    <li><span>Untergrund</span><strong>${esc(surface)}</strong></li>
  </ul>
  <div class="route-body">
    <p>${text}</p>
    ${highlights.length ? `<ul class="tick-list">${highlights.map((h) => `<li>${icon('check', 'ico')}<span>${h}</span></li>`).join('')}</ul>` : ''}
    ${train ? `<p class="route-train">${icon('route', 'ico')}<span><b>Anreise:</b> ${train}</span></p>` : ''}
  </div>
</article>`;
}

function routeGrid(items, { columns = 2 } = {}) {
  return `<div class="route-grid route-grid--${columns}">${items.map((r) => routeCard(r)).join('')}</div>`;
}

/** Tagesablauf o. Ä. als Zeitschiene. Erwartet [{ time, title, text }]. */
function timeline(items) {
  return `<ol class="timeline">${items
    .map(
      (t) => `<li class="tl-item">
      <span class="tl-time">${esc(t.time)}</span>
      <div class="tl-body">
        <h3 class="tl-title">${t.title}</h3>
        ${t.text ? `<p>${t.text}</p>` : ''}
      </div>
    </li>`
    )
    .join('')}</ol>`;
}

/**
 * Empfehlungs-Box mit Partnerlink.
 *
 * Bewusst kategoriebasiert statt artikelgenau: Spezifikation, Preisrahmen und
 * Auswahlkriterien veralten nicht, Artikelnummern und Tagespreise schon.
 */
function pick({ badge, title, forWhom, price, specs = [], pros = [], cons = [], partner, url, ctaLabel = 'Im Shop ansehen', note }) {
  return `<article class="pick">
  <div class="pick-head">
    ${badge ? `<span class="pick-badge">${esc(badge)}</span>` : ''}
    <h3 class="pick-title">${title}</h3>
    ${forWhom ? `<p class="pick-for">${forWhom}</p>` : ''}
  </div>
  <div class="pick-body">
    ${price ? `<p class="pick-price"><span>Preisrahmen</span><strong>${esc(price)}</strong></p>` : ''}
    ${specs.length ? `<dl class="pick-specs">${specs.map((s) => `<div><dt>${esc(s.k)}</dt><dd>${s.v}</dd></div>`).join('')}</dl>` : ''}
    ${pros.length ? `<div class="pick-list"><p>Dafür spricht</p>${checklist(pros)}</div>` : ''}
    ${cons.length ? `<div class="pick-list"><p>Das solltest du wissen</p>${checklist(cons, { tone: 'dont' })}</div>` : ''}
  </div>
  ${
    partner && url
      ? `<div class="pick-cta">
    <a class="btn btn--primary" href="${esc(affLink(partner, url))}" rel="sponsored nofollow noopener" target="_blank">${esc(ctaLabel)} <span class="aff-star" aria-label="Partnerlink">*</span></a>
    <span class="pick-partner">bei ${esc(affiliates.partners[partner] ? affiliates.partners[partner].label : partner)}</span>
  </div>`
      : ''
  }
  ${note ? `<p class="pick-note">${note}</p>` : ''}
</article>`;
}

function pickGrid(items, { columns = 3 } = {}) {
  return `<div class="pick-grid pick-grid--${columns}">${items.map((p) => pick(p)).join('')}</div>`;
}

/** Kurzer Werbehinweis, direkt vor oder nach Empfehlungsblöcken. */
function affNotice() {
  return `<p class="aff-notice">${esc(affiliates.disclosureShort)} <a href="/affiliate-hinweis.html">Mehr dazu</a></p>`;
}

/** FAQ-Akkordeon. Liefert zusaetzlich JSON-LD zurueck. */
function faq(items, { title = 'Häufige Fragen' } = {}) {
  const html = `<div class="faq">
  ${title ? `<h2 id="${slug(title)}">${esc(title)}</h2>` : ''}
  <div class="faq-list">${items
    .map(
      (f) => `<details class="faq-item">
      <summary>${f.q}${icon('chevron', 'ico faq-chevron')}</summary>
      <div class="faq-answer">${f.a}</div>
    </details>`
    )
    .join('')}</div>
</div>`;

  const jsonLd = {
    '@context': 'https://schema.org',
    '@type': 'FAQPage',
    mainEntity: items.map((f) => ({
      '@type': 'Question',
      name: stripTags(f.q),
      acceptedAnswer: { '@type': 'Answer', text: stripTags(f.a) },
    })),
  };
  return { html, jsonLd };
}

function stripTags(value) {
  return String(value)
    .replace(/<[^>]+>/g, '')
    .replace(/\s+/g, ' ')
    .trim();
}

/** Newsletter-Block. variant: inline | wide | aside */
function newsletterBlock({ variant = 'wide', title, lead } = {}) {
  const heading = title || 'Alle 14 Tage eine Tour weiter';
  const copy =
    lead ||
    'Eine Route mit GPX-Hinweis, ein Ausrüstungsteil ohne Werbesprech und ein Fehler, den du dank uns nicht selbst machen musst. Kostenlos, jederzeit abbestellbar.';
  return `<div class="nl nl--${variant}">
  <div class="nl-copy">
    <p class="label label--light">Newsletter</p>
    <h2 class="nl-title">${esc(heading)}</h2>
    <p class="nl-lead">${esc(copy)}</p>
    <ul class="nl-perks">
      <li>${icon('check', 'ico')}<span>Startgeschenk: die Sattelfest-Packliste als PDF, mit Gewichtsspalte</span></li>
      <li>${icon('check', 'ico')}<span>Kein Spam, kein Weiterverkauf deiner Daten</span></li>
      <li>${icon('check', 'ico')}<span>Abmeldung mit einem Klick in jeder E-Mail</span></li>
    </ul>
  </div>
  <form class="nl-form" data-newsletter novalidate>
    <div class="field">
      <label for="nl-name-${variant}">Vorname <span class="opt">(optional)</span></label>
      <input type="text" id="nl-name-${variant}" name="first_name" autocomplete="given-name" placeholder="Wie sollen wir dich ansprechen?">
    </div>
    <div class="field">
      <label for="nl-mail-${variant}">E-Mail-Adresse</label>
      <input type="email" id="nl-mail-${variant}" name="email" autocomplete="email" required placeholder="dein.name@beispiel.de">
      <p class="field-error" data-error hidden>Bitte gib eine gültige E-Mail-Adresse ein.</p>
    </div>
    <div class="field field--check">
      <input type="checkbox" id="nl-consent-${variant}" name="consent" required>
      <label for="nl-consent-${variant}">Ich möchte den Newsletter erhalten und habe die <a href="/datenschutz.html">Datenschutzerklärung</a> gelesen.</label>
    </div>
    <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">
    <button type="submit" class="btn btn--primary btn--block">Packliste sichern ${icon('arrow', 'ico')}</button>
    <p class="nl-status" data-status role="status"></p>
    <p class="nl-legal">Double-Opt-in: Du bekommst zuerst eine Bestätigungsmail. Erst danach startet der Versand.</p>
  </form>
</div>`;
}

/** Weiterlesen-Navigation am Seitenende. */
function pager({ prev, next }) {
  if (!prev && !next) return '';
  return `<nav class="pager" aria-label="Weitere Artikel dieser Rubrik">
  ${
    prev
      ? `<a class="pager-item pager-item--prev" href="${esc(prev.href)}">
    <span class="pager-dir">${icon('arrowLeft', 'ico')} Vorher</span>
    <span class="pager-label">${esc(prev.label)}</span></a>`
      : '<span></span>'
  }
  ${
    next
      ? `<a class="pager-item pager-item--next" href="${esc(next.href)}">
    <span class="pager-dir">Als Nächstes ${icon('arrow', 'ico')}</span>
    <span class="pager-label">${esc(next.label)}</span></a>`
      : '<span></span>'
  }
</nav>`;
}

/** Verweis-Kasten auf verwandte Artikel. */
function related(items, title = 'Passt dazu') {
  return `<div class="related">
  <p class="related-title">${esc(title)}</p>
  <ul>${items.map((i) => `<li><a href="${esc(i.href)}">${esc(i.label)} ${icon('arrow', 'ico')}</a></li>`).join('')}</ul>
</div>`;
}

module.exports = {
  esc,
  slug,
  num,
  icon,
  figure,
  imageFor,
  imageExists,
  teaser,
  magazine,
  sectionHead,
  label,
  affLink,
  affA,
  affNotice,
  kicker,
  pageHero,
  section,
  prose,
  h2,
  h3,
  toc,
  callout,
  stats,
  cards,
  steps,
  checklist,
  doDont,
  table,
  weightList,
  routeCard,
  routeGrid,
  timeline,
  pick,
  pickGrid,
  faq,
  newsletterBlock,
  pager,
  related,
};
