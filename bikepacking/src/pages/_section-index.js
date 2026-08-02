'use strict';

const {
  pageHero, section, newsletterBlock, esc, teaser, magazine, sectionHead,
} = require('../components');

/**
 * Rubrikseite direkt aus dem Navigationsbaum.
 *
 * Aufbau wie eine Ressortseite: erster Artikel gross als Aufmacher,
 * die uebrigen im Raster darunter.
 */
module.exports = function buildSectionIndex(sec) {
  const [first, ...rest] = sec.children;

  const lead = teaser({
    href: first.href,
    title: esc(first.label),
    dek: esc(first.teaser),
    section: sec.label,
    variant: 'lead',
  });

  const grid = magazine(
    rest.map((c) =>
      teaser({ href: c.href, title: esc(c.label), dek: esc(c.teaser), variant: 'standard' })
    ),
    3
  );

  const body = [
    pageHero({
      kicker: 'Rubrik',
      title: esc(sec.label),
      lead: esc(sec.intro),
      meta: [{ icon: 'book', text: `${sec.children.length} Artikel` }],
      href: sec.href,
      image: false,
    }),
    section(lead, { tone: 'plain' }),
    section(`${sectionHead('Weitere Artikel dieser Rubrik')}${grid}`, { tone: 'plain' }),
    section(newsletterBlock({ variant: 'wide' }), { tone: 'soft' }),
  ].join('\n');

  return {
    href: sec.href,
    title: sec.label,
    navLabel: sec.label,
    breadcrumb: sec.label,
    metaTitle: `${sec.label} – Bikepacking für Einsteiger | Sattelfest`,
    description: sec.intro,
    body,
    noPager: true,
    noImage: true,
  };
};
