'use strict';

const {
  section, icon, stats, newsletterBlock, faq, esc, checklist,
  imageFor, imageExists, teaser, magazine, sectionHead,
} = require('../components');
const { nav, allContentPages } = require('../nav');
const { TERMS } = require('../data/glossar');

/** Findet einen Artikel im Navigationsbaum anhand seines Pfads. */
function art(href) {
  const found = allContentPages().find((p) => p.href === href);
  return {
    href,
    title: found ? found.label : href,
    dek: found ? found.teaser : '',
    section: found ? found.sectionLabel : '',
  };
}

const ARTIKEL = allContentPages().length;
const BEGRIFFE = TERMS.length;

const faqBlock = faq(
  [
    {
      q: 'Brauche ich für Bikepacking ein spezielles Rad?',
      a: '<p>Nein – und das ist der wichtigste Satz für den Einstieg. Weil die Taschen ohne Gepäckträger direkt am Rahmen befestigt werden, lässt sich fast jedes Rad bepacken. Entscheidend sind vier Dinge: ausreichend Reifenfreiheit, eine leichte Übersetzung, funktionierende Bremsen und eine Sitzposition, in der du mehrere Stunden beschwerdefrei fährst. Ein Trekkingrad oder Hardtail erfüllt das oft genauso gut wie ein Gravelbike.</p>',
    },
    {
      q: 'Wie weit komme ich am ersten Tag realistisch?',
      a: '<p>Rechne in Zeit, nicht in Kilometern. Nimm die Strecke, die du an einem guten Tag <strong>ohne</strong> Gepäck schaffst, teile sie durch 20 – das sind deine Stunden im Sattel. Davon geht die Kletterzeit ab (rund <strong>500 Höhenmeter pro Stunde</strong>), der Rest mal deiner Reisegeschwindigkeit. Unterm Strich bleiben meist etwa <strong>60 Prozent</strong> deiner Referenzstrecke – für Einsteiger also 35 bis 60 Kilometer.</p>',
    },
    {
      q: 'Wo darf ich in Deutschland übernachten?',
      a: '<p>Wildcampen ist in allen Bundesländern verboten oder stark eingeschränkt. Die legale Lösung sind über <strong>200 ausgewiesene Trekking- und Biwakplätze</strong>, die genau für Radfahrende und Wandernde eingerichtet wurden und meist 0 bis 15 Euro pro Nacht kosten. Dazu kommen Campingplätze, Naturlagerplätze und Unterkünfte. Wichtig: Auf Trekkingplätzen gibt es in der Regel kein Trinkwasser.</p>',
    },
    {
      q: 'Welche Taschen brauche ich zum Anfangen?',
      a: '<p>Zwei genügen für die erste Nacht: eine <strong>Satteltasche</strong> mit 10 bis 14 Litern und eine <strong>Lenkerrolle</strong> mit 10 bis 14 Litern. Danach folgen Oberrohrtasche und Rahmentasche. Und falls dein Rad schon einen Gepäckträger hat: Zwei gebrauchte Packtaschen kosten 30 bis 80 Euro und fassen mehr als jedes Bikepacking-Set.</p>',
    },
    {
      q: 'Was kostet der Einstieg?',
      a: '<p>Wenn du Rad, Schlafsack und Isomatte hast oder leihen kannst: <strong>79 bis 223 Euro</strong> für die erste vollständige Tour. Eine sparsame Neuausstattung liegt bei 365 Euro, eine solide bei 1.085, eine komfortable bei 2.360 – jeweils ohne Rad. Wir haben alle drei Varianten vollständig durchgerechnet.</p>',
    },
  ],
  { title: 'Häufig gefragt' }
);

/* ------------------------------------------------------------------ *
 * Aufmacher
 * ------------------------------------------------------------------ */

const hero = `<section class="hero">
  <div class="hero-img">
    ${
      imageExists(imageFor('/'))
        ? `<img src="${imageFor('/')}" alt="Beladenes Gravelbike bei Sonnenaufgang auf einem Schotterweg" loading="eager" decoding="async">`
        : ''
    }
  </div>
  <div class="wrap">
    <div class="hero-inner">
      <p class="label label--light">Der Einstieg · Erste Tour bis erste Woche</p>
      <h1 class="hero-title">Einmal draußen schlafen. Der Rest ergibt sich.</h1>
      <p class="hero-dek">
        Kein neues Rad, kein 600-Euro-Taschenset, keine Alpenüberquerung. Sondern der komplette Weg
        von der ersten Nacht im Zelt bis zur Wochentour – mit offengelegten Kosten, echten
        Gewichtsangaben und Empfehlungen, die auch mal vom Kauf abraten.
      </p>
      <div class="hero-actions">
        <a class="btn btn--gold" href="/einstieg/erste-tour-fahrplan.html">Beim Fahrplan starten ${icon('arrow', 'ico')}</a>
        <a class="btn btn--ghost" href="/taschen/taschensystem.html">Welche Taschen brauche ich?</a>
      </div>
      <ul class="hero-facts">
        <li><b>${ARTIKEL}</b> Artikel</li>
        <li><b>${BEGRIFFE}</b> Begriffe im Glossar</li>
        <li><b>2</b> kostenlose Rechner</li>
        <li><b>0 €</b> für alles</li>
      </ul>
    </div>
  </div>
</section>`;

/* ------------------------------------------------------------------ *
 * Aufmacher-Block: ein grosser Teaser, drei kleine daneben
 * ------------------------------------------------------------------ */

const leadStory = teaser({
  ...art('/taschen/taschensystem.html'),
  title: 'Fünf Positionen am Rad – und was wirklich wohin gehört',
  dek:
    'Satteltasche, Lenkerrolle, Rahmentasche, Oberrohrtasche, Gabel: Jede Position hat eine Aufgabe, eine Gewichtsgrenze und eine Sorte Gepäck, die dort hingehört. Wer das System einmal verstanden hat, kauft keine Tasche zweimal – und spart sich den häufigsten teuren Anfängerfehler.',
  section: 'Taschen',
  meta: ['11 Min. Lesezeit', 'Mit Kaufreihenfolge'],
  variant: 'lead',
});

const sideStories = [
  teaser({
    ...art('/einstieg/tagesetappen-planen.html'),
    title: 'Warum aus 100 Kilometern schnell 55 werden',
    dek: 'Das Zeitbudget-Modell und der Grund, warum Höhenmeter Etappen kippen, nicht Distanzen.',
    section: 'Einstieg',
    variant: 'row',
  }),
  teaser({
    ...art('/routen/uebernachten.html'),
    title: 'Über 200 Orte, an denen du legal draußen schläfst',
    dek: 'Wildcampen ist verboten – Trekkingplätze sind die Lösung, und die meisten kennen sie nicht.',
    section: 'Routen',
    variant: 'row',
  }),
  teaser({
    ...art('/taschen/taschen-oder-packtaschen.html'),
    title: 'Packtaschen sind nicht altmodisch, sondern oft besser',
    dek: 'Doppeltes Volumen, halber Preis, echt wasserdicht. Auf Flussradwegen gewinnen sie klar.',
    section: 'Taschen',
    variant: 'row',
  }),
];

/* ------------------------------------------------------------------ *
 * Ressort-Strecken
 * ------------------------------------------------------------------ */

function ressort(sectionId, count = 3, tone = 'plain') {
  const sec = nav.find((s) => s.id === sectionId);
  return section(
    `${sectionHead(esc(sec.label), { more: { href: sec.href, label: 'Alle Artikel' } })}
     ${magazine(
       sec.children
         .slice(0, count)
         .map((c) =>
           teaser({
             href: c.href,
             title: esc(c.label),
             dek: esc(c.teaser),
             variant: 'standard',
           })
         ),
       count
     )}`,
    { tone }
  );
}

const body = `
${hero}

${section(
  `<div class="mag mag--lead">
    ${leadStory}
    <div class="mag-side">${sideStories.join('')}</div>
  </div>`,
  { tone: 'plain' }
)}

${section(
  `${sectionHead('Der Weg zur ersten Nacht draußen', {
    sub: 'So sieht der Einstieg realistisch aus – mit Zeit- und Kostenrahmen, die niemand schönrechnet.',
  })}
  ${stats([
    { value: '3 Wochen', label: 'Vorlauf genügen', note: 'Termin, Radcheck, Schlafplatz, Testfahrt – mehr braucht es nicht.' },
    { value: '40–60 km', label: 'Erste Etappe', note: 'Weit genug für ein Abenteuer, kurz genug für einen entspannten Abend.' },
    { value: '8,5 kg', label: 'Gepäck im Sommer', note: 'Vollständige Ausrüstung mit Zelt, ohne Wasser.' },
    { value: '0–15 €', label: 'Trekkingplatz', note: 'Legal übernachten im Wald, meist online buchbar.' },
    { value: '1', label: 'Nacht reicht', note: 'Alles Weitere ist eine Wiederholung dieser einen Nacht.' },
  ])}
  <p style="margin-top:1.5rem">
    <a class="btn btn--secondary" href="/einstieg/erste-tour-fahrplan.html">Der komplette Fahrplan ${icon('arrow', 'ico')}</a>
  </p>`,
  { tone: 'soft' }
)}

${ressort('taschen', 3, 'plain')}
${ressort('ausruestung', 3, 'soft')}

${section(
  `${sectionHead('Routen &amp; Planung', { more: { href: '/routen/', label: 'Alle Artikel' } })}
  ${magazine(
    nav
      .find((s) => s.id === 'routen')
      .children.slice(0, 4)
      .map((c, i) =>
        teaser({ href: c.href, title: esc(c.label), dek: esc(c.teaser), variant: 'numbered', index: i + 1 })
      ),
    2
  )}`,
  { tone: 'plain' }
)}

${ressort('unterwegs', 3, 'soft')}

${section(
  `${sectionHead('Zwei Rechner, die dir Arbeit abnehmen')}
  <div class="mag mag--2">
    <div>
      <p class="label label--light">Werkzeug</p>
      <h3 class="teaser-title" style="color:#fff;font-size:1.4rem">Packlisten-Generator</h3>
      <p style="margin-top:.5rem;line-height:1.6">
        Acht Fragen zu Dauer, Übernachtung, Jahreszeit, Rad und Erfahrung – heraus kommt eine
        vollständige Packliste mit Gramm-Angaben, einer konkreten Taschenempfehlung und dem
        geschätzten Gesamtgewicht.
      </p>
      <p style="margin-top:1rem"><a class="btn btn--gold btn--sm" href="/tools/packlisten-generator.html">Generator starten</a></p>
    </div>
    <div>
      <p class="label label--light">Werkzeug</p>
      <h3 class="teaser-title" style="color:#fff;font-size:1.4rem">Etappen- &amp; Gewichts-Rechner</h3>
      <p style="margin-top:.5rem;line-height:1.6">
        Realistische Tagesdistanz aus Referenzstrecke, Höhenmetern, Untergrund, Gepäck und Erfahrung –
        inklusive Fahrzeit, Tageslänge mit Pausen und einer Einordnung deines Gepäckgewichts.
      </p>
      <p style="margin-top:1rem"><a class="btn btn--gold btn--sm" href="/tools/etappen-rechner.html">Rechner öffnen</a></p>
    </div>
  </div>`,
  { tone: 'dark' }
)}

${section(
  `<div class="article-body">
    <div class="prose" style="max-width:none">
      ${sectionHead('Wie wir Empfehlungen aussprechen')}
      <p class="lead-p">
        Diese Seite finanziert sich über Partnerlinks. Das schafft einen Interessenkonflikt, den wir
        nicht wegdiskutieren, sondern durch feste Regeln begrenzen.
      </p>
      <div class="do-dont" style="margin-top:1.5rem">
        <div class="do-dont-col do-dont-col--do">
          <h3>${icon('check', 'ico')} Das machen wir</h3>
          ${checklist([
            'Wir empfehlen <strong>Spezifikationen statt Artikelnummern</strong> – R-Wert, Komforttemperatur, Volumen, Wassersäule. Die gelten jahrelang.',
            'Wir schreiben dazu, <strong>wann du etwas nicht kaufen solltest</strong>. Bei Einsteigern ist das überraschend oft der Fall.',
            'Wir nennen <strong>Zahlen</strong>: Gramm, Euro, Kilometer, Höhenmeter – auch dann, wenn eine vage Formulierung bequemer wäre.',
          ])}
        </div>
        <div class="do-dont-col do-dont-col--dont">
          <h3>${icon('x', 'ico')} Das machen wir nicht</h3>
          ${checklist(
            [
              'Keine erfundenen Testberichte. Wir behaupten nicht, Ausrüstung getestet zu haben.',
              'Keine Tagespreise als Fakten. Preise ändern sich – wir nennen Rahmen.',
              'Kein Tracking, keine Cookies, keine Werbenetzwerke.',
            ],
            { tone: 'dont' }
          )}
        </div>
      </div>
      <p style="margin-top:1.5rem">
        <a class="btn btn--secondary btn--sm" href="/affiliate-hinweis.html">Finanzierung im Detail ${icon('arrow', 'ico')}</a>
      </p>
    </div>
    <aside class="article-aside">
      ${newsletterBlock({ variant: 'aside' })}
    </aside>
  </div>`,
  { tone: 'plain' }
)}

${section(`<div class="prose" style="max-width:none">${faqBlock.html}</div>`, { tone: 'soft' })}
`;

module.exports = {
  href: '/',
  title: 'Bikepacking für Anfänger',
  metaTitle: 'Bikepacking für Anfänger: Taschen, Ausrüstung, Routen | Sattelfest',
  description:
    'Der Einstiegs-Guide fürs Bikepacking: welche Taschen du wirklich brauchst, welche Ausrüstung mitkommt, wie weit du am Tag kommst, wo du legal übernachtest und welche Routen sich für die erste Tour eignen. Kostenlos, mit zwei Rechnern.',
  noCrumbs: true,
  noPager: true,
  jsonLd: [faqBlock.jsonLd],
  body,
};
