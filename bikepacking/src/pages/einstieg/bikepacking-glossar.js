'use strict';

const article = require('../_article');
const { h2, callout, esc } = require('../../components');
const { TERMS } = require('../../data/glossar');


/** Gruppiert die Begriffe nach Anfangsbuchstaben und baut die Sprungnavigation. */
function buildGlossary() {
  const groups = new Map();
  TERMS.forEach(([term, definition]) => {
    const letter = term.charAt(0).toUpperCase().replace('Ü', 'U').replace('Ö', 'O').replace('Ä', 'A');
    if (!groups.has(letter)) groups.set(letter, []);
    groups.get(letter).push([term, definition]);
  });

  const letters = [...groups.keys()].sort((a, b) => a.localeCompare(b, 'de'));

  const jump = `<nav class="glossary-nav" aria-label="Alphabet-Sprungnavigation">${letters
    .map((l) => `<a href="#buchstabe-${l}">${l}</a>`)
    .join('')}</nav>`;

  const body = letters
    .map(
      (letter) => `<section class="glossary-group" id="buchstabe-${letter}">
      <p class="glossary-letter" aria-hidden="true">${letter}</p>
      <h2 style="position:absolute;left:-9999px">Begriffe mit ${letter}</h2>
      <dl class="glossary-list">
        ${groups
          .get(letter)
          .map(([term, def]) => `<div><dt>${esc(term)}</dt><dd>${esc(def)}</dd></div>`)
          .join('')}
      </dl>
    </section>`
    )
    .join('');

  return `${jump}<div class="glossary">${body}</div>`;
}

const content = `
<p class="lead-p">
  Bikepacking hat eine Sprache, die zur Hälfte aus Englisch und zur Hälfte aus Fachjargon besteht –
  und in Foren wird sie einfach vorausgesetzt. Hier stehen ${TERMS.length} Begriffe in
  verständlichem Deutsch, ohne die Zirkelschlüsse, an denen Ausrüstungslexika gern scheitern.
</p>

${callout(
  'Die zwölf wichtigsten für den Anfang',
  '<p>Wenn du nur zwölf Begriffe lernst, dann diese: <strong>Satteltasche, Lenkerrolle, Rahmentasche, Stem Bag, Anything Cage, Dry Bag, R-Wert, Komforttemperatur, GPX, Trekkingplatz, S24O und Reifenfreiheit.</strong> Damit verstehst du 90 Prozent aller Kaufberatungen und Tourberichte.</p>',
  'tip'
)}

${h2('Alle Begriffe von A bis Z', 'begriffe')}
${buildGlossary()}
`;

module.exports = article({
  href: '/einstieg/bikepacking-glossar.html',
  kicker: 'Einstieg · Nachschlagewerk',
  title: 'Bikepacking-Glossar von A bis Z',
  metaTitle: `Bikepacking-Glossar: ${TERMS.length} Begriffe einfach erklärt | Sattelfest`,
  description: `Bikepacking-Fachbegriffe verständlich erklärt: ${TERMS.length} Begriffe von Anything Cage bis Zwiebelprinzip – Seatpack, Framebag, R-Wert, Komforttemperatur, S24O, Trekkingplatz und mehr.`,
  lead: `${TERMS.length} Fachbegriffe in verständlichem Deutsch – von Anything Cage bis Zwiebelprinzip, mit Sprungnavigation.`,
  meta: [
    { icon: 'book', text: `${TERMS.length} Begriffe` },
    { icon: 'check', text: 'Ohne Fachchinesisch' },
  ],
  content,
  related: [
    { href: '/taschen/taschensystem.html', label: 'Das Taschensystem verstehen' },
    { href: '/ausruestung/packliste.html', label: 'Die Packliste mit Gewichten' },
    { href: '/einstieg/was-ist-bikepacking.html', label: 'Was Bikepacking wirklich ist' },
  ],
});
