'use strict';

const { pageHero, section, cards, icon, esc } = require('../components');
const { nav } = require('../nav');

const body = `
${pageHero({
  kicker: 'Fehler 404',
  title: 'Diese Seite gibt es nicht',
  lead:
    'Der Link ist entweder veraltet, oder in der Adresse hat sich ein Tippfehler eingeschlichen. Kein Grund zur Sorge – hier geht es weiter.',
})}

${section(
  `<div class="section-head">
    <h2 class="section-title">Wonach hast du gesucht?</h2>
  </div>
  ${cards(
    nav
      .filter((s) => s.id !== 'service')
      .map((s) => ({
        title: esc(s.label),
        text: esc(s.intro),
        href: s.href,
        linkLabel: 'Rubrik öffnen',
      })),
    { columns: 3 }
  )}
  <p style="margin-top:2rem">
    <a class="btn btn--primary" href="/">Zur Startseite ${icon('arrow', 'ico')}</a>
    <a class="btn btn--secondary" href="/faq.html" style="margin-left:.5rem">Häufige Fragen</a>
  </p>`,
  { tone: 'plain' }
)}
`;

module.exports = {
  href: '/404.html',
  title: 'Seite nicht gefunden',
  navLabel: 'Seite nicht gefunden',
  breadcrumb: '404',
  metaTitle: 'Seite nicht gefunden (404) | Sattelfest',
  description: 'Die aufgerufene Seite existiert nicht. Hier geht es zurück zu den Rubriken und zur Startseite.',
  noCrumbs: true,
  noPager: true,
  // Nicht in sitemap.xml aufnehmen und nicht indexieren lassen.
  noIndex: true,
  body,
};
