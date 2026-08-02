'use strict';

const {
  pageHero, section, toc, faq, newsletterBlock, esc,
} = require('../components');

/**
 * Standardgeruest einer Inhaltsseite.
 *
 * Aufbau: Rubrik, Titel, Vorspann, Meta-Zeile, Aufmacherbild, darunter
 * zweispaltig Fliesstext und klebrige Seitenspalte mit Inhaltsverzeichnis,
 * Newsletter und Querverweisen.
 */
module.exports = function article(page) {
  const faqBlock = page.faq && page.faq.length ? faq(page.faq, { title: page.faqTitle || 'Häufige Fragen' }) : null;

  const aside = [
    page.toc && page.toc.length
      ? `<div class="aside-block"><p class="aside-title">Inhalt</p>${toc(page.toc, false)}</div>`
      : '',
    newsletterBlock({ variant: 'aside' }),
    page.related && page.related.length
      ? `<div class="aside-block"><p class="aside-title">Passt dazu</p>
         <ul class="related-list">${page.related
           .map((r) => `<li><a href="${esc(r.href)}">${esc(r.label)}</a></li>`)
           .join('')}</ul></div>`
      : '',
  ]
    .filter(Boolean)
    .join('\n');

  const bodyParts = [
    pageHero({
      kicker: page.kicker,
      title: page.title,
      lead: page.lead,
      meta: page.meta || [],
      href: page.href,
      image: page.image !== false,
      caption: page.imageCaption,
      credit: page.imageCredit,
    }),
    section(
      `<div class="article-body">
        <article class="prose">${page.content}</article>
        <aside class="article-aside">${aside}</aside>
      </div>`,
      { tone: 'plain' }
    ),
  ];

  if (faqBlock) {
    bodyParts.push(section(`<div class="prose" style="max-width:none">${faqBlock.html}</div>`, { tone: 'soft' }));
  }

  return {
    href: page.href,
    title: page.title,
    navLabel: page.navLabel,
    breadcrumb: page.breadcrumb,
    metaTitle: page.metaTitle,
    description: page.description,
    updated: page.updated || '2026-08-02',
    article: true,
    jsonLd: faqBlock ? [faqBlock.jsonLd] : [],
    body: bodyParts.join('\n'),
  };
};
