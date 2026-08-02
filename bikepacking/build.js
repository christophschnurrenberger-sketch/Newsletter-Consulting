#!/usr/bin/env node
'use strict';

/**
 * Statischer Seitengenerator fuer Sattelfest.
 *
 * Ohne npm-Abhaengigkeiten. Aufruf:  node build.js
 *
 * Ergebnis liegt im Ordner, der in src/config.js unter deploy.outDir steht
 * (Vorgabe: ./dist). Dessen INHALT wird per FTP/SFTP auf den Webspace geladen.
 */

const fs = require('fs');
const path = require('path');

const { site, deploy, newsletter, affiliates, absoluteUrl } = require('./src/config');
const { nav, allContentPages, siblings } = require('./src/nav');
const { renderPage } = require('./src/layout');
const { pager } = require('./src/components');
const buildSectionIndex = require('./src/pages/_section-index');

const OUT = path.join(__dirname, deploy.outDir);
const ASSETS_SRC = path.join(__dirname, 'src', 'assets');
const BASE = deploy.basePath.replace(/\/+$/, '');

/* ------------------------------------------------------------------ *
 * Hilfsfunktionen
 * ------------------------------------------------------------------ */

function write(relativePath, contents) {
  const target = path.join(OUT, relativePath.replace(/^\//, ''));
  fs.mkdirSync(path.dirname(target), { recursive: true });
  fs.writeFileSync(target, contents, 'utf8');
  return target;
}

/**
 * Rechnet die internen Links in ihre endgueltige Form um.
 *
 * Betrifft nur href und src, die mit einem einzelnen "/" beginnen. Externe
 * Links (https://…) und protokollrelative (//…) bleiben unberuehrt. Laeuft
 * erst nach der Link-Pruefung, damit dort weiter mit den reinen Pfaden
 * verglichen werden kann.
 *
 * @param {string} html
 * @param {string} href      Pfad der Seite, fuer die gerendert wird
 * @param {boolean} forceAbs Immer absolut ausgeben (fuer die 404-Seite noetig,
 *                           weil Apache sie unter beliebigen URLs ausliefert)
 */
function rewriteLinks(html, href, forceAbs = false) {
  const useRelative = deploy.paths === 'relative' && !forceAbs;

  if (!useRelative) {
    return BASE ? html.replace(/(href|src)="\/(?!\/)/g, `$1="${BASE}/`) : html;
  }

  // Wie tief liegt diese Seite? /taschen/satteltasche.html → 1 Ebene.
  const depth = fileFor(href).replace(/^\//, '').split('/').length - 1;
  const prefix = '../'.repeat(depth);

  return html.replace(/(href|src)="\/(?!\/)([^"]*)"/g, (match, attr, target) => {
    // Verzeichnis-URLs brauchen fuer file:// die Datei explizit.
    const hasSuffix = target.includes('#') || target.includes('?');
    const resolved = !hasSuffix && (target === '' || target.endsWith('/')) ? `${target}index.html` : target;
    return `${attr}="${prefix}${resolved}"`;
  });
}

/** Wandelt /pfad/ in /pfad/index.html um, sonst unveraendert. */
function fileFor(href) {
  return href.endsWith('/') ? `${href}index.html` : href;
}

/* ------------------------------------------------------------------ *
 * Seiten einsammeln
 * ------------------------------------------------------------------ */

const PAGE_MODULES = [
  // Startseite
  './src/pages/index',

  // Einstieg
  './src/pages/einstieg/was-ist-bikepacking',
  './src/pages/einstieg/erste-tour-fahrplan',
  './src/pages/einstieg/welches-fahrrad',
  './src/pages/einstieg/was-kostet-bikepacking',
  './src/pages/einstieg/tagesetappen-planen',
  './src/pages/einstieg/bikepacking-glossar',

  // Taschen
  './src/pages/taschen/taschensystem',
  './src/pages/taschen/satteltasche',
  './src/pages/taschen/lenkerrolle',
  './src/pages/taschen/rahmentasche',
  './src/pages/taschen/kleine-taschen',
  './src/pages/taschen/taschen-oder-packtaschen',
  './src/pages/taschen/richtig-packen',
  './src/pages/taschen/wasserdicht-packen',

  // Ausruestung
  './src/pages/ausruestung/packliste',
  './src/pages/ausruestung/schlafsystem',
  './src/pages/ausruestung/schlafsack-isomatte',
  './src/pages/ausruestung/kochen-unterwegs',
  './src/pages/ausruestung/kleidung',
  './src/pages/ausruestung/navigation',
  './src/pages/ausruestung/licht-strom',
  './src/pages/ausruestung/werkzeug-reparatur',

  // Routen & Planung
  './src/pages/routen/einsteiger-routen-deutschland',
  './src/pages/routen/erstes-mikroabenteuer',
  './src/pages/routen/uebernachten',
  './src/pages/routen/route-selbst-planen',
  './src/pages/routen/routen-europa',
  './src/pages/routen/saison-wetter',
  './src/pages/routen/wasser-verpflegung',

  // Unterwegs
  './src/pages/unterwegs/tagesablauf',
  './src/pages/unterwegs/panne-beheben',
  './src/pages/unterwegs/koerper-beschwerden',
  './src/pages/unterwegs/training-vorbereitung',
  './src/pages/unterwegs/allein-oder-gruppe',
  './src/pages/unterwegs/sicherheit-notfall',

  // Fehlerseite
  './src/pages/404',

  // Werkzeuge & Service
  './src/pages/tools/packlisten-generator',
  './src/pages/tools/etappen-rechner',
  './src/pages/newsletter',
  './src/pages/faq',
  './src/pages/ueber-uns',

  // Recht
  './src/pages/impressum',
  './src/pages/datenschutz',
  './src/pages/affiliate-hinweis',
];

function collectPages() {
  const pages = PAGE_MODULES.map((modulePath) => require(modulePath));

  // Rubrik-Uebersichten werden aus dem Navigationsbaum erzeugt.
  nav.forEach((section) => pages.push(buildSectionIndex(section)));

  return pages;
}

/* ------------------------------------------------------------------ *
 * Ausgabe
 * ------------------------------------------------------------------ */

function buildSitemap(pages) {
  const today = new Date().toISOString().slice(0, 10);
  const priority = (href) => {
    if (href === '/') return '1.0';
    if (href.endsWith('/')) return '0.8';
    if (href.startsWith('/impressum') || href.startsWith('/datenschutz') || href.startsWith('/affiliate')) return '0.2';
    return '0.7';
  };
  const urls = pages
    .filter((p) => !p.noIndex)
    .map(
      (p) => `  <url>
    <loc>${absoluteUrl(p.href)}</loc>
    <lastmod>${p.updated || today}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>${priority(p.href)}</priority>
  </url>`
    )
    .join('\n');
  return `<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
${urls}
</urlset>
`;
}

function buildRobots() {
  return `# robots.txt fuer ${site.name}
User-agent: *
Allow: /

Sitemap: ${site.origin}${BASE}/sitemap.xml
`;
}

/**
 * Apache-Konfiguration fuer klassischen Webspace.
 *
 * Jede Direktive steht in einem <IfModule>-Block: Fehlt das Modul beim
 * Anbieter, wird der Block ignoriert statt einen 500er auszuloesen.
 */
function buildHtaccess() {
  return `# ============================================================
# ${site.name} – Apache-Konfiguration
# Automatisch erzeugt von build.js. Aenderungen bitte dort vornehmen.
# ============================================================

# Zeichensatz und Startseite
AddDefaultCharset UTF-8
DirectoryIndex index.html

# Verzeichnisinhalte nicht auflisten
Options -Indexes

# Eigene Fehlerseite
ErrorDocument 404 ${BASE}/404.html

# ------------------------------------------------------------
# HTTPS erzwingen
# Erst aktivieren, wenn das SSL-Zertifikat eingerichtet ist und
# https://deine-domain.de im Browser funktioniert – sonst ist die
# Seite nicht mehr erreichbar.
# ------------------------------------------------------------
# <IfModule mod_rewrite.c>
#   RewriteEngine On
#   RewriteCond %{HTTPS} !=on
#   RewriteCond %{HTTP:X-Forwarded-Proto} !https
#   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L]
# </IfModule>

# ------------------------------------------------------------
# Komprimierung
# ------------------------------------------------------------
<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/css text/plain text/xml
  AddOutputFilterByType DEFLATE application/javascript application/json
  AddOutputFilterByType DEFLATE application/xml image/svg+xml
</IfModule>

# ------------------------------------------------------------
# Browser-Caching
# HTML bewusst kurz, damit Aenderungen schnell sichtbar werden.
# ------------------------------------------------------------
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType text/html                 "access plus 1 hour"
  ExpiresByType text/css                  "access plus 1 month"
  ExpiresByType application/javascript    "access plus 1 month"
  ExpiresByType image/svg+xml             "access plus 1 month"
  ExpiresByType image/jpeg                "access plus 1 month"
  ExpiresByType image/png                 "access plus 1 month"
  ExpiresByType image/webp                "access plus 1 month"
  ExpiresByType font/woff2                "access plus 1 year"
</IfModule>

# ------------------------------------------------------------
# Sicherheits-Header
# ------------------------------------------------------------
<IfModule mod_headers.c>
  Header set X-Content-Type-Options "nosniff"
  Header set Referrer-Policy "strict-origin-when-cross-origin"
  Header set X-Frame-Options "SAMEORIGIN"
  Header set Permissions-Policy "geolocation=(), microphone=(), camera=(), interest-cohort=()"
  # HSTS erst zusammen mit der HTTPS-Weiterleitung oben aktivieren:
  # Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
</IfModule>

# ------------------------------------------------------------
# MIME-Typen, die nicht bei jedem Anbieter gesetzt sind
# ------------------------------------------------------------
<IfModule mod_mime.c>
  AddType image/svg+xml .svg
  AddType application/manifest+json .webmanifest
</IfModule>

# Zugriff auf Punktdateien unterbinden
<FilesMatch "^\\.">
  Require all denied
</FilesMatch>
`;
}

function buildClientConfig() {
  return `/* Automatisch erzeugt von build.js - nicht direkt bearbeiten. */
window.SF = ${JSON.stringify(
    {
      site: { name: site.name, origin: site.origin },
      newsletter: { action: newsletter.action, leadMagnet: newsletter.leadMagnet },
      affiliates: { enabled: affiliates.enabled },
    },
    null,
    2
  )};
`;
}

function copyAssets() {
  const targetDir = path.join(OUT, 'assets');
  fs.mkdirSync(targetDir, { recursive: true });

  // Stylesheet, Skript, Favicon
  fs.readdirSync(ASSETS_SRC, { withFileTypes: true })
    .filter((e) => e.isFile())
    .forEach((e) => fs.copyFileSync(path.join(ASSETS_SRC, e.name), path.join(targetDir, e.name)));

  // Bilder rekursiv, ohne die Dokumentation
  const imgSrc = path.join(ASSETS_SRC, 'img');
  if (fs.existsSync(imgSrc)) {
    fs.cpSync(imgSrc, path.join(targetDir, 'img'), {
      recursive: true,
      filter: (src) => !src.endsWith('README.md'),
    });
  }

  fs.writeFileSync(path.join(targetDir, 'config.js'), buildClientConfig(), 'utf8');
}

/** Zaehlt, wie viele Bildslots noch leer sind – erscheint in der Bauausgabe. */
function countMissingImages(pages) {
  const { imageFor, imageExists } = require('./src/components');
  const withImage = pages.filter((p) => !p.noImage);
  const missing = withImage.filter((p) => !imageExists(imageFor(p.href)));
  return {
    total: withImage.length,
    missing: missing.map((p) => imageFor(p.href).replace('/assets/img/', '')),
  };
}

/* ------------------------------------------------------------------ *
 * Pruefungen: verhindern, dass tote Links live gehen
 * ------------------------------------------------------------------ */

function verify(pages) {
  const problems = [];
  const known = new Set(pages.map((p) => p.href));

  // 1. Jede in nav.js verlinkte Seite muss existieren.
  allContentPages().forEach((entry) => {
    if (!known.has(entry.href)) problems.push(`Navigation verweist auf fehlende Seite: ${entry.href}`);
  });
  nav.forEach((section) => {
    if (!known.has(section.href)) problems.push(`Rubrik-Übersicht fehlt: ${section.href}`);
  });

  // 2. Jeder interne Link im erzeugten HTML muss ein Ziel haben.
  pages.forEach((page) => {
    const hrefs = page.html.match(/href="\/[^"#]*"/g) || [];
    hrefs.forEach((raw) => {
      const href = raw.slice(6, -1);
      if (href.startsWith('/assets/')) return;
      if (!known.has(href)) problems.push(`${page.href} verlinkt auf unbekanntes Ziel: ${href}`);
    });
  });

  // 3. Doppelte Seitenpfade
  const seen = new Set();
  pages.forEach((p) => {
    if (seen.has(p.href)) problems.push(`Doppelter Pfad: ${p.href}`);
    seen.add(p.href);
  });

  return [...new Set(problems)];
}

/* ------------------------------------------------------------------ *
 * Hauptlauf
 * ------------------------------------------------------------------ */

function main() {
  fs.rmSync(OUT, { recursive: true, force: true });
  fs.mkdirSync(OUT, { recursive: true });

  const pages = collectPages();

  // Weiterlesen-Navigation automatisch anhaengen.
  pages.forEach((page) => {
    if (page.noPager) return;
    const rel = siblings(page.href);
    if (rel.prev || rel.next) page.body += `<section class="band band--soft"><div class="wrap">${pager(rel)}</div></section>`;
  });

  pages.forEach((page) => {
    page.html = renderPage(page);
  });

  const problems = verify(pages);
  if (problems.length) {
    console.error('\nBuild abgebrochen – bitte zuerst beheben:\n');
    problems.forEach((p) => console.error('  ✗ ' + p));
    process.exit(1);
  }

  // Die Fehlerseite braucht absolute Pfade: Apache liefert sie unter der
  // ursprünglich angefragten URL aus, deren Tiefe nicht vorhersehbar ist.
  pages.forEach((page) =>
    write(fileFor(page.href), rewriteLinks(page.html, page.href, page.href === '/404.html'))
  );

  copyAssets();
  write('/sitemap.xml', buildSitemap(pages));
  write('/robots.txt', buildRobots());
  if (deploy.writeHtaccess) write('/.htaccess', buildHtaccess());

  const bytes = pages.reduce((sum, p) => sum + Buffer.byteLength(p.html, 'utf8'), 0);
  console.log(`✓ ${pages.length} Seiten erzeugt (${(bytes / 1024).toFixed(0)} kB HTML) → ${deploy.outDir}/`);
  console.log(`  Rubriken: ${nav.length} · Inhaltsseiten: ${allContentPages().length}`);
  console.log(`  Ziel-URL: ${site.origin}${BASE}/`);
  console.log(`  Verlinkung: ${deploy.paths}${deploy.paths === 'relative' ? ' (läuft auch per Doppelklick)' : ''}`);
  const img = countMissingImages(pages);
  if (img.missing.length) {
    console.log(`\n  Bilder: ${img.total - img.missing.length} von ${img.total} Slots belegt.`);
    console.log(`  Noch offen (als .jpg nach src/assets/img/ legen):`);
    img.missing.slice(0, 6).forEach((m) => console.log(`    · ${m}`));
    if (img.missing.length > 6) console.log(`    … und ${img.missing.length - 6} weitere`);
  } else {
    console.log(`\n  Bilder: alle ${img.total} Slots belegt.`);
  }

  console.log(`\n  Vorschau: ${deploy.outDir}/index.html im Browser öffnen`);
  console.log(`  Hochladen: den INHALT von ${deploy.outDir}/ auf den Webspace kopieren`);
  console.log(`  (auch die versteckte .htaccess – im FTP-Programm „versteckte Dateien anzeigen“).`);
}

main();
