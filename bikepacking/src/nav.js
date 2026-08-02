'use strict';

/**
 * Navigationsbaum.
 *
 * Einzige Quelle der Wahrheit für: Header-Dropdowns, Footer-Spalten,
 * Breadcrumbs, sitemap.xml und die Sektions-Übersichten.
 *
 * `href` ist immer root-relativ (beginnt mit /), damit Unterseiten in
 * beliebiger Tiefe denselben Markup-Block nutzen können.
 */

const nav = [
  {
    id: 'einstieg',
    label: 'Einstieg',
    href: '/einstieg/',
    intro:
      'Was Bikepacking überhaupt ist, welches Rad du dafür brauchst (Antwort: meistens deins) und wie weit du am ersten Tag realistisch kommst.',
    children: [
      {
        href: '/einstieg/was-ist-bikepacking.html',
        label: 'Was Bikepacking wirklich ist',
        teaser: 'Der Unterschied zur klassischen Radreise – und warum er über deine Taschen entscheidet.',
      },
      {
        href: '/einstieg/erste-tour-fahrplan.html',
        label: 'Fahrplan: In 7 Schritten zur ersten Tour',
        teaser: 'Die komplette Reihenfolge vom Entschluss bis zur ersten Nacht draußen.',
      },
      {
        href: '/einstieg/welches-fahrrad.html',
        label: 'Welches Rad passt zum Bikepacking?',
        teaser: 'Gravel, MTB, Trekking oder das Rad im Keller – die ehrliche Einordnung.',
      },
      {
        href: '/einstieg/was-kostet-bikepacking.html',
        label: 'Was Bikepacking wirklich kostet',
        teaser: 'Drei Budgets komplett durchgerechnet, inklusive der Posten, die niemand einplant.',
      },
      {
        href: '/einstieg/tagesetappen-planen.html',
        label: 'Wie weit kommst du am Tag?',
        teaser: 'Die Formel gegen die Selbstüberschätzung – mit Höhenmetern und Untergrund.',
      },
      {
        href: '/einstieg/bikepacking-glossar.html',
        label: 'Bikepacking-Glossar A bis Z',
        teaser: 'Über 90 Begriffe von Anything Cage bis Zwiebelprinzip, verständlich erklärt.',
      },
    ],
  },
  {
    id: 'taschen',
    label: 'Taschen',
    href: '/taschen/',
    intro:
      'Das Herzstück des Bikepackings. Welche Tasche welche Aufgabe hat, in welcher Reihenfolge du kaufst und wie du packst, damit das Rad noch fährt.',
    children: [
      {
        href: '/taschen/taschensystem.html',
        label: 'Das Taschensystem verstehen',
        teaser: 'Fünf Positionen am Rad, vier Aufgaben, eine sinnvolle Kaufreihenfolge.',
      },
      {
        href: '/taschen/satteltasche.html',
        label: 'Satteltasche (Seatpack)',
        teaser: 'Die erste Tasche, die du kaufst – und der häufigste Frustpunkt für Einsteiger.',
      },
      {
        href: '/taschen/lenkerrolle.html',
        label: 'Lenkerrolle & Lenkertasche',
        teaser: 'Viel Volumen, wenig Gewicht: Warum hier maximal 4 Kilo hineindürfen.',
      },
      {
        href: '/taschen/rahmentasche.html',
        label: 'Rahmentasche',
        teaser: 'Der beste Platz am ganzen Rad – tief, zentral, fahrstabil.',
      },
      {
        href: '/taschen/kleine-taschen.html',
        label: 'Oberrohr-, Gabel- & Stemtaschen',
        teaser: 'Die Kleinteile, die den Tag angenehm machen: Snacks, Handy, Wasser.',
      },
      {
        href: '/taschen/taschen-oder-packtaschen.html',
        label: 'Bikepacking-Taschen oder Packtaschen?',
        teaser: 'Gepäckträger ist nicht altmodisch – er ist für viele Touren schlicht besser.',
      },
      {
        href: '/taschen/richtig-packen.html',
        label: 'Richtig packen: Gewichtsverteilung',
        teaser: 'Schwer nach unten und mittig – warum falsch packen wie ein Fahrfehler wirkt.',
      },
      {
        href: '/taschen/wasserdicht-packen.html',
        label: 'Wasserdicht packen',
        teaser: '„Wasserabweisend“ heißt nass. Das Zwei-Zonen-System hält deinen Schlafsack trocken.',
      },
    ],
  },
  {
    id: 'ausruestung',
    label: 'Ausrüstung',
    href: '/ausruestung/',
    intro:
      'Alles, was in die Taschen kommt – nach Gewicht sortiert und mit klarer Ansage, was Pflicht ist, was mitdarf und was zu Hause bleibt.',
    children: [
      {
        href: '/ausruestung/packliste.html',
        label: 'Die Packliste mit Gewichten',
        teaser: 'Jede Zeile mit Gramm-Angabe und der Einordnung Pflicht, sinnvoll oder Ballast.',
      },
      {
        href: '/ausruestung/schlafsystem.html',
        label: 'Zelt, Tarp oder Biwaksack?',
        teaser: 'Drei Wege, draußen zu schlafen – und welcher zu deiner ersten Tour passt.',
      },
      {
        href: '/ausruestung/schlafsack-isomatte.html',
        label: 'Schlafsack & Isomatte',
        teaser: 'Warum die Matte über deine kalte Nacht entscheidet, nicht der Schlafsack.',
      },
      {
        href: '/ausruestung/kochen-unterwegs.html',
        label: 'Kochen unterwegs',
        teaser: 'Gaskocher, Spiritus oder gar nicht – mit ehrlicher Gewichtsrechnung.',
      },
      {
        href: '/ausruestung/kleidung.html',
        label: 'Kleidung: Das Zwiebelprinzip',
        teaser: 'Zwei Garnituren reichen. Welche zwei, entscheidet über deinen Komfort.',
      },
      {
        href: '/ausruestung/navigation.html',
        label: 'Navigation: Apps, GPS & Karten',
        teaser: 'Komoot, Fahrradcomputer oder Handy am Lenker – Akku ist das eigentliche Thema.',
      },
      {
        href: '/ausruestung/licht-strom.html',
        label: 'Licht, Strom & Powerbank',
        teaser: 'Wie viel Milliamperestunden du brauchst – und wann sich ein Nabendynamo lohnt.',
      },
      {
        href: '/ausruestung/werkzeug-reparatur.html',
        label: 'Werkzeug & Reparatur-Kit',
        teaser: 'Die zwölf Teile, mit denen du 95 Prozent aller Pannen selbst löst.',
      },
    ],
  },
  {
    id: 'routen',
    label: 'Routen & Planung',
    href: '/routen/',
    intro:
      'Wohin die erste Tour geht, wo du legal übernachtest und wie aus einer Idee eine Route wird, die auch bei Regen noch Spaß macht.',
    children: [
      {
        href: '/routen/einsteiger-routen-deutschland.html',
        label: 'Einsteiger-Routen in Deutschland',
        teaser: 'Zehn Strecken mit Distanz, Höhenmetern, Untergrund und Anreise per Bahn.',
      },
      {
        href: '/routen/erstes-mikroabenteuer.html',
        label: 'Das erste Mikroabenteuer (S24O)',
        teaser: 'Freitag nach der Arbeit los, Samstagmittag zurück. Der beste Einstieg überhaupt.',
      },
      {
        href: '/routen/uebernachten.html',
        label: 'Übernachten: Wo du legal schläfst',
        teaser: 'Trekkingplatz, Campingplatz, Biwak – und was in Deutschland wirklich erlaubt ist.',
      },
      {
        href: '/routen/route-selbst-planen.html',
        label: 'Route selbst planen',
        teaser: 'Von der groben Linie zum GPX-Track, das ohne böse Überraschung fahrbar ist.',
      },
      {
        href: '/routen/routen-europa.html',
        label: 'Bikepacking-Routen in Europa',
        teaser: 'Acht Klassiker für die zweite Saison – mit ehrlicher Schwierigkeitseinordnung.',
      },
      {
        href: '/routen/saison-wetter.html',
        label: 'Saison, Wetter & Jahreszeit',
        teaser: 'Wann die beste Zeit ist und wie du drei Tage Regen einplanst, statt zu hoffen.',
      },
      {
        href: '/routen/wasser-verpflegung.html',
        label: 'Wasser & Verpflegung unterwegs',
        teaser: 'Wo du in Deutschland zuverlässig Wasser bekommst – und wie viel du brauchst.',
      },
    ],
  },
  {
    id: 'unterwegs',
    label: 'Unterwegs',
    href: '/unterwegs/',
    intro:
      'Der Teil, über den kaum jemand schreibt: der Tagesrhythmus auf Tour, Pannen im Nirgendwo und was dein Körper nach 80 Kilometern sagt.',
    children: [
      {
        href: '/unterwegs/tagesablauf.html',
        label: 'Der Tagesablauf auf Tour',
        teaser: 'Von 6:30 Uhr bis zur Dunkelheit – der Rhythmus, der Kilometer leicht macht.',
      },
      {
        href: '/unterwegs/panne-beheben.html',
        label: 'Panne unterwegs beheben',
        teaser: 'Platten, Kettenriss, gerissene Speiche – die Notfall-Anleitungen im Detail.',
      },
      {
        href: '/unterwegs/koerper-beschwerden.html',
        label: 'Sitzbeschwerden, Hände & Knie',
        teaser: 'Die drei Baustellen, die Touren abbrechen – und wie du sie vorher löst.',
      },
      {
        href: '/unterwegs/training-vorbereitung.html',
        label: 'Training & Vorbereitung',
        teaser: 'Acht Wochen, drei Einheiten pro Woche – mehr braucht es nicht.',
      },
      {
        href: '/unterwegs/allein-oder-gruppe.html',
        label: 'Allein, zu zweit oder in der Gruppe',
        teaser: 'Wie die Gruppengröße Tempo, Planung und Sicherheit verändert.',
      },
      {
        href: '/unterwegs/sicherheit-notfall.html',
        label: 'Sicherheit & Notfall',
        teaser: 'Erste Hilfe, Notruf ohne Empfang, Diebstahlschutz und die Nacht allein im Wald.',
      },
    ],
  },
  {
    id: 'service',
    label: 'Service',
    href: '/service/',
    intro: 'Zwei Rechner, die dir Arbeit abnehmen, der Newsletter und die Fragen, die immer wieder kommen.',
    children: [
      {
        href: '/tools/packlisten-generator.html',
        label: 'Packlisten-Generator',
        teaser: 'Acht Fragen, eine persönliche Packliste inklusive geschätztem Gesamtgewicht.',
      },
      {
        href: '/tools/etappen-rechner.html',
        label: 'Etappen- & Gewichts-Rechner',
        teaser: 'Realistische Tagesdistanz aus Höhenmetern, Untergrund und Gepäck berechnen.',
      },
      {
        href: '/newsletter.html',
        label: 'Newsletter',
        teaser: 'Alle 14 Tage: eine Route, ein Ausrüstungsteil, ein Fehler, den du nicht machen musst.',
      },
      {
        href: '/faq.html',
        label: 'Häufige Fragen',
        teaser: 'Von „Brauche ich ein Gravelbike?“ bis „Wo gehe ich nachts aufs Klo?“.',
      },
      {
        href: '/ueber-uns.html',
        label: 'Über Sattelfest',
        teaser: 'Wer hinter der Seite steckt und nach welchen Regeln wir empfehlen.',
      },
    ],
  },
];

const legalNav = [
  { href: '/impressum.html', label: 'Impressum' },
  { href: '/datenschutz.html', label: 'Datenschutz' },
  { href: '/affiliate-hinweis.html', label: 'Affiliate-Hinweis' },
];

/** Flache Liste aller Inhaltsseiten (ohne Sektions-Übersichten). */
function allContentPages() {
  return nav.flatMap((section) =>
    section.children.map((child) => ({ ...child, section: section.id, sectionLabel: section.label }))
  );
}

/** Findet die Sektion, zu der ein href gehört. */
function sectionFor(href) {
  return nav.find(
    (section) => section.href === href || section.children.some((child) => child.href === href)
  );
}

/** Vorheriger / nächster Artikel innerhalb einer Sektion. */
function siblings(href) {
  const section = sectionFor(href);
  if (!section) return { section: null, prev: null, next: null };
  const index = section.children.findIndex((child) => child.href === href);
  if (index === -1) return { section, prev: null, next: null };
  return {
    section,
    prev: index > 0 ? section.children[index - 1] : null,
    next: index < section.children.length - 1 ? section.children[index + 1] : null,
  };
}

module.exports = { nav, legalNav, allContentPages, sectionFor, siblings };
