'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, timeline, weightList,
} = require('../../components');

const content = `
<p class="lead-p">
  S24O steht für <em>Sub-24-Hour-Overnight</em>: Freitagabend nach der Arbeit losfahren, eine Nacht
  draußen, Samstagvormittag zurück. Weniger als 24 Stunden, kein Urlaubstag, kaum Planung – und
  trotzdem die vollständige Bikepacking-Erfahrung. Es ist der beste Einstieg, den es gibt.
</p>

${stats([
  { value: '< 24 Std.', label: 'Gesamtdauer', note: 'Von der Haustür bis zurück zur Haustür.' },
  { value: '30–50 km', label: 'Je Richtung', note: 'Zwei bis drei Stunden Fahrzeit.' },
  { value: '0', label: 'Urlaubstage', note: 'Passt in jedes normale Wochenende.' },
])}

${h2('Warum S24O der beste Einstieg ist', 'warum')}
${checklist([
  '<strong>Der Aufwand ist klein genug, um es einfach zu tun.</strong> Kein Urlaub, keine wochenlange Planung, keine große Anreise.',
  '<strong>Du lernst alles, was zählt.</strong> Packen, fahren mit Gewicht, Zelt aufbauen, draußen schlafen, morgens weiterfahren – alles komprimiert auf eine Nacht.',
  '<strong>Das Risiko ist überschaubar.</strong> Wenn etwas nicht klappt, bist du in zwei Stunden zu Hause.',
  '<strong>Du brauchst kaum Ausrüstung.</strong> Für eine Nacht reichen Satteltasche und Lenkerrolle – oder ein alter Gepäckträger.',
  '<strong>Es ist wiederholbar.</strong> Wer einmal S24O gefahren ist, macht es oft zehnmal im Jahr. Eine große Tour macht man einmal.',
])}

${callout(
  'Der eigentliche Grund',
  '<p>Die meisten Menschen kommen nicht zum Bikepacking, weil sie auf den „richtigen Moment“ warten: die richtige Ausrüstung, die passende Woche, die perfekte Route. S24O braucht keinen richtigen Moment. Es braucht einen freien Freitagabend und einen Ort, der 40 Kilometer entfernt ist. Beides hast du wahrscheinlich diese Woche.</p>',
  'tip'
)}

${h2('Der Ablauf', 'ablauf')}
${timeline([
  {
    time: '17:30',
    title: 'Feierabend, Rad steht gepackt',
    text:
      'Pack am Vorabend oder morgens vor der Arbeit. Wer nach Feierabend erst anfängt zu suchen, fährt nicht mehr los. Das Rad steht fertig im Flur.',
  },
  {
    time: '18:00',
    title: 'Losfahren',
    text:
      'Direkt von der Haustür. Keine Anreise, kein Auto, kein Bahnhof. Das ist der halbe Zauber an S24O.',
  },
  {
    time: '19:30',
    title: 'Einkaufen',
    text:
      'Abendessen, Frühstück und Getränke für den Abend. Nicht mehr – das Frühstück gibt es morgen unterwegs beim Bäcker. Achte auf die Öffnungszeiten: Freitagabend schließen viele Läden um 20 Uhr.',
  },
  {
    time: '20:30',
    title: 'Ankommen und aufbauen',
    text:
      'Zelt aufbauen, solange es hell ist. Im Sommer hast du bis 21:30 Licht, im September nur bis 19:45. Plane danach, nicht nach der Uhrzeit auf dem Papier.',
  },
  {
    time: '21:00',
    title: 'Essen und sitzen',
    text:
      'Der eigentliche Grund für die ganze Sache. Kochen oder kalt essen, dann einfach dasitzen. Kein Bildschirm, kein Termin. Das ist der Teil, den man nicht erklären kann und den alle meinen, wenn sie von Bikepacking schwärmen.',
  },
  {
    time: '22:30',
    title: 'Schlafen',
    text:
      'Früher als zu Hause, weil es dunkel ist und du gefahren bist. Nach 80 Kilometern schläft man auf einer Isomatte erstaunlich gut.',
  },
  {
    time: '07:00',
    title: 'Aufwachen',
    text:
      'Meist früher als geplant – Vögel, Licht, kühle Luft. Das ist kein Fehler, sondern der schönste Teil des Tages.',
  },
  {
    time: '08:00',
    title: 'Abbauen und losfahren',
    text:
      'Zelt trocken schütteln, packen, Platz sauber hinterlassen. Wenn das Zelt nass ist: außen an die Gabel, es trocknet unterwegs.',
  },
  {
    time: '09:30',
    title: 'Frühstücken unterwegs',
    text:
      'Nach 20 bis 25 Kilometern in einer Bäckerei. Zu diesem Zeitpunkt hast du bereits die halbe Rückstrecke geschafft und den besten Kaffee deines Lebens.',
  },
  {
    time: '11:30',
    title: 'Zu Hause',
    text:
      'Vor dem Mittagessen zurück. Das Wochenende hat noch nicht einmal richtig angefangen – und du warst eine Nacht draußen.',
  },
])}

${h2('Wo du hinfährst', 'wohin')}
${table({
  head: ['Ziel', 'Vorteil', 'Nachteil', 'Kosten'],
  rows: [
    [
      '<strong>Campingplatz</strong>',
      'Wasser, Toilette, Dusche, kein Suchen',
      'Etwas anonym, im Sommer voll',
      '10 – 25 €',
    ],
    [
      '<strong>Trekkingplatz</strong>',
      'Ruhig, im Wald, genau dafür gemacht',
      'Buchung nötig, meist nur Mai bis Oktober',
      '0 – 15 €',
    ],
    [
      'Naturlagerplatz / Biwakplatz',
      'Sehr einfach, oft kostenlos',
      'Regional stark unterschiedlich verfügbar',
      '0 – 10 €',
    ],
    [
      'Garten von Freunden',
      'Kostenlos, Klo vorhanden, gesellig',
      'Kein echtes Draußengefühl',
      '0 €',
    ],
    [
      'Schutzhütte am Wanderweg',
      'Kostenlos, Dach über dem Kopf',
      'Rechtlich oft eine Grauzone, nicht überall geduldet',
      '0 €',
    ],
  ],
  note: 'Für die allererste Nacht ist ein Campingplatz die entspannteste Wahl: Du findest ihn zuverlässig, es gibt Wasser und eine Toilette, und niemand fragt dich, was du da tust.',
})}

${h2('Was du dabeihast', 'packliste')}
${weightList({
  title: 'S24O im Sommer, eine Nacht',
  items: [
    { name: 'Schlafsack, Komfort ca. +10 °C', g: 600, tag: 'pflicht' },
    { name: 'Isomatte, R-Wert ab 2,5', g: 450, tag: 'pflicht' },
    { name: 'Zelt oder Tarp', note: 'Bei stabilem Wetter reicht ein Tarp', g: 900, tag: 'pflicht' },
    { name: 'Regenjacke', g: 320, tag: 'pflicht' },
    { name: 'Leichte Isolationsjacke für abends', g: 280, tag: 'pflicht' },
    { name: 'Wechselshirt und Socken', note: 'Gleichzeitig Schlafkleidung', g: 200, tag: 'pflicht' },
    { name: 'Licht vorn und hinten plus Stirnlampe', g: 300, tag: 'pflicht' },
    { name: 'Powerbank 10.000 mAh mit Kabel', g: 250, tag: 'pflicht' },
    { name: 'Werkzeug, Schlauch, Pumpe', g: 600, tag: 'pflicht' },
    { name: 'Zahnbürste, kleines Handtuch, Sonnencreme', g: 200, tag: 'pflicht' },
    { name: 'Abendessen und Getränk', note: 'Unterwegs gekauft', g: 900, tag: 'pflicht' },
    { name: 'Kocher mit Kartusche und Topf', note: 'Nur wenn du Kaffee willst', g: 650, tag: 'sinnvoll' },
    { name: 'Frühstück', note: 'Gibt es morgen beim Bäcker', g: 400, tag: 'ballast' },
    { name: 'Zweite Garnitur Radkleidung', note: 'Für eine Nacht wirklich nicht', g: 380, tag: 'ballast' },
  ],
})}

${callout(
  'Was in eine Satteltasche und eine Lenkerrolle passt',
  '<p>Genau diese Liste. Schlafsack und Isolationsjacke in die Lenkerrolle, Zelt, Kleidung und Isomatte in die Satteltasche, Werkzeug und Powerbank in eine Rahmentasche oder eine Trikottasche. Wer noch keine Taschen hat: Ein alter Gepäckträger mit einer Packtasche fasst das alles ebenfalls – und kostet gebraucht 40 Euro.</p>',
  'money'
)}

${h2('Die Variationen', 'varianten')}
${h3('Die Faul-Variante', 'faul')}
<p>
  Kein Zelt, kein Kocher, kein Schlafsack: Du fährst 60 Kilometer, schläfst in einer Pension und
  fährst am nächsten Morgen zurück. Gepäck: eine Lenkerrolle. Klingt nach Schummeln, ist aber für
  viele der Einstieg, nach dem das Zelt folgt.
</p>

${h3('Die Bahn-Variante', 'bahn')}
<p>
  Freitagabend mit der Regionalbahn 60 Kilometer raus, dort 20 Kilometer zum Schlafplatz fahren,
  am Samstag die ganze Strecke zurückradeln. Vorteil: Du fährst am Freitag nur ein kurzes Stück im
  Dunkeln und hast den ganzen Samstag zum Fahren.
</p>

${h3('Die Sonnenaufgangs-Variante', 'sonnenaufgang')}
<p>
  Ziel ist ein Ort mit Blick nach Osten – ein Hügel, ein See, eine Anhöhe. Du stellst dir den Wecker
  auf 20 Minuten vor Sonnenaufgang und trinkst Kaffee, während es hell wird. Der Aufwand ist
  derselbe, der Erinnerungswert ein anderer.
</p>

${h3('Die Gruppen-Variante', 'gruppe')}
<p>
  Zu dritt oder viert. Zelt, Kocher und Werkzeug werden aufgeteilt – jeder trägt weniger. Achtung:
  Die Gruppe fährt immer so schnell wie der Langsamste und braucht 30 Prozent länger für alles.
  Plane entsprechend kürzer.
</p>

${h2('Die fünf Regeln für ein gelungenes S24O', 'regeln')}
${checklist([
  '<strong>Pack am Vorabend.</strong> Wer nach Feierabend erst anfängt zu suchen, fährt nicht mehr los.',
  '<strong>Nicht weiter als 50 Kilometer.</strong> Der Weg ist nicht das Ziel – die Nacht ist das Ziel.',
  '<strong>Sei vor der Dämmerung da.</strong> Zelt aufbauen im Hellen ist ein völlig anderes Erlebnis.',
  '<strong>Kauf unterwegs ein, nicht zu Hause.</strong> Weniger Gewicht auf der Hinfahrt und frischere Sachen.',
  '<strong>Plane keinen Rückweg-Zeitdruck.</strong> Wer um 12 Uhr einen Termin hat, genießt den Morgen nicht.',
])}

${callout(
  'Der Wetter-Vorbehalt',
  '<p>Für eine einzige Nacht lohnt es sich nicht, bei Dauerregen und 9 Grad loszufahren – vor allem nicht beim ersten Mal. Verschieb es um eine Woche. Bei S24O ist das leicht, weil du keinen Urlaub gebucht und keine Anreise organisiert hast. Genau das macht das Format so gut.</p>',
  'warn'
)}
`;

module.exports = article({
  href: '/routen/erstes-mikroabenteuer.html',
  kicker: 'Routen · Mikroabenteuer',
  title: 'Das erste Mikroabenteuer (S24O)',
  metaTitle: 'S24O Bikepacking: Das Mikroabenteuer für einen Abend | Sattelfest',
  description:
    'Sub-24-Hour-Overnight: Freitagabend losfahren, eine Nacht draußen, Samstagvormittag zurück. Der komplette Ablauf mit Zeitplan, Packliste für eine Nacht, Zielvarianten und fünf Regeln.',
  lead:
    'Freitagabend nach der Arbeit los, Samstagvormittag zurück. Kein Urlaubstag, kaum Planung – und trotzdem die vollständige Bikepacking-Erfahrung.',
  meta: [
    { icon: 'clock', text: '8 Minuten Lesezeit' },
    { icon: 'tent', text: 'Mit Stundenplan' },
    { icon: 'bag', text: 'Packliste für eine Nacht' },
  ],
  toc: [
    { label: 'Warum S24O der beste Einstieg ist', id: 'warum' },
    { label: 'Der Ablauf', id: 'ablauf' },
    { label: 'Wo du hinfährst', id: 'wohin' },
    { label: 'Was du dabeihast', id: 'packliste' },
    { label: 'Die Variationen', id: 'varianten' },
    { label: 'Die fünf Regeln', id: 'regeln' },
  ],
  content,
  faq: [
    {
      q: 'Was bedeutet S24O beim Bikepacking?',
      a: '<p>S24O steht für Sub-24-Hour-Overnight: eine Tour, die insgesamt weniger als 24 Stunden dauert. Typischer Ablauf: Freitagabend nach der Arbeit losfahren, 30 bis 50 Kilometer zu einem Schlafplatz, eine Nacht draußen, am Samstagvormittag zurück. Kein Urlaubstag, keine Anreise, kaum Planung.</p>',
    },
    {
      q: 'Wie weit sollte man beim ersten Mikroabenteuer fahren?',
      a: '<p>30 bis 50 Kilometer je Richtung, also zwei bis drei Stunden Fahrzeit. Weiter macht wenig Sinn: Der Weg ist nicht das Ziel, die Nacht draußen ist das Ziel. Wichtiger als die Distanz ist, dass du vor der Dämmerung am Schlafplatz ankommst – Zelt aufbauen im Hellen ist ein völlig anderes Erlebnis.</p>',
    },
    {
      q: 'Welche Ausrüstung brauche ich für eine Nacht?',
      a: '<p>Schlafsack, Isomatte, Zelt oder Tarp, Regenjacke, eine Isolationsschicht für abends, Wechselshirt, Licht, Powerbank, Werkzeug und Abendessen – zusammen etwa 4,8 Kilogramm. Das passt in eine Satteltasche plus Lenkerrolle oder in eine einzige Packtasche auf einem alten Gepäckträger.</p>',
    },
    {
      q: 'Wo übernachte ich beim S24O?',
      a: '<p>Für die erste Nacht ist ein Campingplatz die entspannteste Wahl: Du findest ihn zuverlässig, es gibt Wasser und Toilette, und er kostet 10 bis 25 Euro. Trekkingplätze sind ruhiger und günstiger (0 bis 15 Euro), brauchen aber meist eine Vorabbuchung und haben oft nur von Mai bis Oktober geöffnet.</p>',
    },
    {
      q: 'Was mache ich, wenn Regen angesagt ist?',
      a: '<p>Verschieben. Für eine einzige Nacht lohnt es sich nicht, bei Dauerregen und 9 Grad loszufahren – erst recht nicht beim ersten Mal. Bei S24O ist das Verschieben unproblematisch, weil du weder Urlaub gebucht noch eine Anreise organisiert hast. Genau das macht das Format so tauglich für Einsteiger.</p>',
    },
  ],
  related: [
    { href: '/routen/uebernachten.html', label: 'Übernachten: Wo du legal schläfst' },
    { href: '/einstieg/erste-tour-fahrplan.html', label: 'Fahrplan: In 7 Schritten zur ersten Tour' },
    { href: '/ausruestung/packliste.html', label: 'Die Packliste mit Gewichten' },
    { href: '/unterwegs/tagesablauf.html', label: 'Der Tagesablauf auf Tour' },
  ],
});
