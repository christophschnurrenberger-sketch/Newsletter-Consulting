'use strict';

const { site, affiliates, absoluteUrl } = require('./config');
const { nav, legalNav, sectionFor } = require('./nav');
const { icon, esc } = require('./components');

/* ------------------------------------------------------------------ *
 * Header mit Mega-Dropdowns
 * ------------------------------------------------------------------ */

function buildNav(currentHref) {
  const activeSection = sectionFor(currentHref);

  const items = nav
    .map((sec) => {
      const isActive = activeSection && activeSection.id === sec.id;
      const panelId = `drop-${sec.id}`;
      const links = sec.children
        .map(
          (child) => `<li>
            <a class="drop-link${child.href === currentHref ? ' is-current' : ''}" href="${esc(child.href)}">
              <span class="drop-link-label">${esc(child.label)}</span>
              <span class="drop-link-teaser">${esc(child.teaser)}</span>
            </a>
          </li>`
        )
        .join('');

      return `<li class="nav-item has-drop${isActive ? ' is-active' : ''}">
        <button type="button" class="nav-trigger" aria-expanded="false" aria-controls="${panelId}">
          ${esc(sec.label)}${icon('chevron', 'ico nav-caret')}
        </button>
        <div class="drop" id="${panelId}" hidden>
          <div class="drop-inner">
            <div class="drop-intro">
              <p class="drop-eyebrow">${esc(sec.label)}</p>
              <p class="drop-text">${esc(sec.intro)}</p>
              <a class="drop-overview" href="${esc(sec.href)}">Rubrik-Übersicht ${icon('arrow', 'ico')}</a>
            </div>
            <ul class="drop-links">${links}</ul>
          </div>
        </div>
      </li>`;
    })
    .join('');

  return `<nav id="site-nav" class="site-nav" aria-label="Hauptnavigation">
  <ul class="nav-list">${items}</ul>
</nav>`;
}

/**
 * Zweizeiliger Kopfbereich: oben die Marke, darunter die Rubrikleiste.
 * Beim Scrollen klappt die Markenzeile weg und die Rubrikleiste bleibt
 * kompakt stehen.
 */
function header(currentHref) {
  return `<a class="skip-link" href="#main">Zum Inhalt springen</a>
<header class="site-header" id="site-header">
  <div class="wrap header-top">
    <a class="brand" href="/" aria-label="${esc(site.name)} Startseite">
      <span class="brand-mark" aria-hidden="true">${icon('bike', 'ico')}</span>
      <span class="brand-name">Sattel<b>fest</b></span>
      <span class="brand-claim">${esc(site.claim)}</span>
    </a>
    <div class="header-utility">
      <a href="/tools/packlisten-generator.html">${icon('bag', 'ico')} Packlisten-Generator</a>
      <a href="/newsletter.html">${icon('mail', 'ico')} Newsletter</a>
    </div>
  </div>
  <div class="wrap header-nav-row">
    <a class="nav-compact-brand" href="/">Sattel<b>fest</b></a>
    ${buildNav(currentHref)}
    <button type="button" id="nav-toggle" class="nav-toggle" aria-controls="site-nav" aria-expanded="false" aria-label="Menü öffnen">
      ${icon('menu', 'ico')}
    </button>
  </div>
</header>`;
}

/* ------------------------------------------------------------------ *
 * Footer
 * ------------------------------------------------------------------ */

function footer() {
  const columns = nav
    .map(
      (sec) => `<div class="foot-col">
      <p class="foot-eyebrow">${esc(sec.label)}</p>
      <ul class="foot-links">
        ${sec.children
          .slice(0, 6)
          .map((c) => `<li><a href="${esc(c.href)}">${esc(c.label)}</a></li>`)
          .join('')}
      </ul>
    </div>`
    )
    .join('');

  return `<footer class="site-footer">
  <div class="wrap">
    <div class="foot-top">
      <div class="foot-brand">
        <span class="brand-name">Sattel<b>fest</b></span>
        <p class="foot-copy">Unabhängiger Einstiegs-Guide fürs Bikepacking. Ohne Ausrüstungsmythen, ohne Heldengeschichten und ohne die Behauptung, dass du für die erste Nacht draußen ein neues Rad brauchst.</p>
        <a class="btn btn--ghost btn--sm" href="/newsletter.html">${icon('mail', 'ico')} Newsletter abonnieren</a>
        <p style="margin-top:1rem"><a href="/impressum.html" style="color:rgba(255,255,255,.55);font-size:.8rem">Impressum</a></p>
      </div>
      <div class="foot-cols">${columns}</div>
    </div>
    <div class="foot-legal">
      <p class="foot-disclosure">${esc(affiliates.disclosureShort)}</p>
      <div class="foot-bottom">
        <p>&copy; <span data-year>2026</span> ${esc(site.name)}. Alle Rechte vorbehalten.</p>
        <ul class="foot-legal-links">
          ${legalNav.map((l) => `<li><a href="${esc(l.href)}">${esc(l.label)}</a></li>`).join('')}
        </ul>
      </div>
    </div>
  </div>
</footer>`;
}

/* ------------------------------------------------------------------ *
 * Breadcrumbs
 * ------------------------------------------------------------------ */

function breadcrumbs(page) {
  const section = sectionFor(page.href);
  const trail = [{ href: '/', label: 'Start' }];
  if (section && section.href !== page.href) trail.push({ href: section.href, label: section.label });
  trail.push({ href: page.href, label: page.breadcrumb || page.navLabel || page.title });

  const html = `<nav class="crumbs" aria-label="Brotkrümelnavigation">
  <ol>${trail
    .map((t, i) =>
      i === trail.length - 1
        ? `<li aria-current="page">${esc(t.label)}</li>`
        : `<li><a href="${esc(t.href)}">${esc(t.label)}</a></li>`
    )
    .join('')}</ol>
</nav>`;

  const jsonLd = {
    '@context': 'https://schema.org',
    '@type': 'BreadcrumbList',
    itemListElement: trail.map((t, i) => ({
      '@type': 'ListItem',
      position: i + 1,
      name: t.label,
      item: absoluteUrl(t.href),
    })),
  };
  return { html, jsonLd };
}

/* ------------------------------------------------------------------ *
 * Vollständiges Dokument
 * ------------------------------------------------------------------ */

/**
 * @param {object} page  { href, title, metaTitle, description, body, kicker,
 *                         jsonLd[], noCrumbs, bodyClass, updated }
 */
function renderPage(page) {
  const url = absoluteUrl(page.href);
  const metaTitle = page.metaTitle || `${page.title} | ${site.name}`;
  const description = page.description || site.defaultDescription;

  const crumbs = page.noCrumbs ? { html: '', jsonLd: null } : breadcrumbs(page);

  const structured = [];
  if (crumbs.jsonLd) structured.push(crumbs.jsonLd);
  if (page.href === '/') {
    structured.push({
      '@context': 'https://schema.org',
      '@type': 'WebSite',
      name: site.name,
      url: absoluteUrl('/'),
      description: site.defaultDescription,
      inLanguage: 'de-DE',
    });
  }
  if (page.article) {
    structured.push({
      '@context': 'https://schema.org',
      '@type': 'Article',
      headline: page.title,
      description,
      inLanguage: 'de-DE',
      mainEntityOfPage: url,
      author: { '@type': 'Organization', name: site.name },
      publisher: { '@type': 'Organization', name: site.name },
      dateModified: page.updated || '2026-08-02',
    });
  }
  (page.jsonLd || []).forEach((j) => structured.push(j));

  const structuredHtml = structured
    .map((j) => `<script type="application/ld+json">${JSON.stringify(j)}</script>`)
    .join('\n    ');

  return `<!DOCTYPE html>
<html lang="${site.lang}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>${esc(metaTitle)}</title>
  <meta name="description" content="${esc(description)}">
  <meta name="robots" content="${page.noIndex ? 'noindex, follow' : 'index, follow, max-image-preview:large, max-snippet:-1'}">
  <meta name="author" content="${esc(site.name)}">
  <meta name="theme-color" content="${site.themeColor}">
  <meta name="referrer" content="strict-origin-when-cross-origin">
  <link rel="canonical" href="${esc(url)}">
  <meta property="og:locale" content="${site.locale}">
  <meta property="og:type" content="${page.href === '/' ? 'website' : 'article'}">
  <meta property="og:site_name" content="${esc(site.name)}">
  <meta property="og:url" content="${esc(url)}">
  <meta property="og:title" content="${esc(metaTitle)}">
  <meta property="og:description" content="${esc(description)}">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="${esc(metaTitle)}">
  <meta name="twitter:description" content="${esc(description)}">
  <link rel="icon" href="/assets/favicon.svg" type="image/svg+xml">
  <link rel="stylesheet" href="/assets/site.css">
  ${structuredHtml}
</head>
<body class="${page.bodyClass || ''}">
${header(page.href)}
<main id="main" class="site-main">
${crumbs.html ? `<div class="wrap">${crumbs.html}</div>` : ''}
${page.body}
</main>
${footer()}
<script src="/assets/config.js"></script>
<script src="/assets/site.js" defer></script>
</body>
</html>
`;
}

module.exports = { renderPage, header, footer, breadcrumbs };
