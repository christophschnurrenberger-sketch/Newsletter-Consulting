'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, routeGrid,
} = require('../../components');

const content = `
<p class="lead-p">
  Acht Routen für die zweite Saison – von der komfortablen Flussstrecke bis zur ernsthaften
  Bergtour. Jede mit ehrlicher Schwierigkeitseinordnung, denn genau daran scheitern die meisten
  Empfehlungen: Was für den einen ein Sommerurlaub ist, ist für den anderen die härteste Woche
  seines Lebens.
</p>

${stats([
  { value: '8', label: 'Routen', note: 'Von leicht bis anspruchsvoll, alle mit Bahnanbindung.' },
  { value: '5–21', label: 'Tage', note: 'Vom verlängerten Wochenende bis zur dreiwöchigen Reise.' },
  { value: '3', label: 'Schwierigkeitsstufen', note: 'Ehrlich eingeordnet, nicht schöngeredet.' },
])}

${h2('Leicht: Komfortrouten mit Infrastruktur', 'leicht')}
${routeGrid(
  [
    {
      title: 'Donauradweg, Passau – Wien',
      region: 'Deutschland, Österreich',
      km: '330 km',
      hm: '700 hm',
      days: '4 – 6 Tage',
      surface: 'Asphalt',
      level: 'leicht',
      text:
        'Der wohl bekannteste Radweg Europas, und für Einsteiger nach wie vor eine hervorragende Wahl. Flussabwärts, durchgehend asphaltiert, mit dichter Infrastruktur und der Wachau als landschaftlichem Höhepunkt.',
      highlights: [
        'Praktisch keine Steigungen – flussabwärts sogar leicht abfallend',
        'Alle 10 bis 15 Kilometer ein Ort mit Versorgung',
        'Sehr viele radfreundliche Unterkünfte und Campingplätze',
        'Fähren erlauben den Wechsel zwischen den Ufern',
      ],
      train: 'Passau und Wien haben Fernverkehrsanschluss, dazwischen fährt die Bahn parallel.',
    },
    {
      title: 'Loire à Vélo, Orléans – Atlantik',
      region: 'Frankreich',
      km: '450 km',
      hm: '900 hm',
      days: '6 – 8 Tage',
      surface: 'Asphalt und feiner Schotter',
      level: 'leicht',
      text:
        'Schlösser, Weinberge und ein sehr breiter, ruhiger Fluss. Frankreichs bestorganisierte Radroute, Teil des EuroVelo 6. Der Untergrund ist überwiegend asphaltiert, die Beschilderung vorbildlich.',
      highlights: [
        'Flach und breit ausgebaut, kaum Autoverkehr',
        'Camping municipal in fast jedem Ort, günstig und einfach',
        'Schlösser als Etappenziele – Chambord, Chenonceau, Saumur',
      ],
      train: 'TGV nach Tours oder Orléans, Rückfahrt ab Nantes oder Saint-Nazaire.',
    },
    {
      title: 'Ostseeküstenroute Dänemark',
      region: 'Dänemark',
      km: '400 km',
      hm: '1.200 hm',
      days: '5 – 7 Tage',
      surface: 'Asphalt',
      level: 'leicht',
      text:
        'Dänemark ist ein Radfahrerland mit einem der besten Radwegenetze Europas – und mit einem Netz kostenloser Shelter, das Bikepacking dort besonders einfach macht. Sanfte Hügel, Küste, kleine Fährverbindungen.',
      highlights: [
        'Kostenloses Shelter-Netz: Holzunterstände zum Übernachten',
        'Ausgezeichnete Radinfrastruktur im ganzen Land',
        'Viele Inseln, viele Fähren, viel Abwechslung',
      ],
      train: 'Über Flensburg oder Puttgarden, in Dänemark gute Regionalbahnverbindungen.',
    },
  ],
  { columns: 2 }
)}

${h2('Mittel: mehr Höhenmeter, mehr Eigenständigkeit', 'mittel')}
${routeGrid(
  [
    {
      title: 'Alpe-Adria-Radweg, Salzburg – Grado',
      region: 'Österreich, Italien',
      km: '410 km',
      hm: '2.100 hm',
      days: '6 – 8 Tage',
      surface: 'Asphalt, teils Schotter',
      level: 'mittel',
      text:
        'Von den Alpen ans Mittelmeer, überwiegend abwärts. Die Alpenüberquerung findet über den Bahntunnel bzw. mit Bahnunterstützung statt, danach geht es durch Kärnten und Friaul bis an die Adria.',
      highlights: [
        'Landschaftlich der spektakulärste unter den einfach fahrbaren Alpenrouten',
        'Überwiegend Gefälle – die Höhenmeter täuschen',
        'Bahn parallel: Etappen lassen sich jederzeit abkürzen',
      ],
      train: 'Salzburg per ICE erreichbar, Rückfahrt ab Grado über Triest oder Udine.',
    },
    {
      title: 'EuroVelo 6, Basel – Wien',
      region: 'Schweiz, Deutschland, Österreich',
      km: '900 km',
      hm: '2.400 hm',
      days: '10 – 14 Tage',
      surface: 'Asphalt',
      level: 'mittel',
      text:
        'Von Rhein zu Donau quer durch Süddeutschland. Die Schwierigkeit liegt nicht im Gelände, sondern in der Länge: Zwei Wochen am Stück fahren ist eine andere Erfahrung als ein verlängertes Wochenende.',
      highlights: [
        'Zwei große Flüsse und die Wasserscheide dazwischen',
        'Durchgehend beschildert und sehr gut ausgebaut',
        'Bahnanbindung praktisch überall – beliebig teilbar',
      ],
      train: 'Basel und Wien mit Fernverkehr, dazwischen dichtes Bahnnetz.',
    },
    {
      title: 'Vennbahn, Aachen – Troisvierges',
      region: 'Deutschland, Belgien, Luxemburg',
      km: '125 km',
      hm: '600 hm',
      days: '2 – 3 Tage',
      surface: 'Asphalt',
      level: 'leicht',
      text:
        'Eine der längsten Bahntrassenrouten Europas: drei Länder, maximal 2 Prozent Steigung, durchgehend asphaltiert. Ideal für ein verlängertes Wochenende und für alle, die Höhenmeter meiden wollen.',
      highlights: [
        'Bahntrasse: nie mehr als 2 Prozent Steigung, auch im Mittelgebirge',
        'Drei Länder auf 125 Kilometern',
        'Sehr gut geeignet für die zweite Tour überhaupt',
      ],
      train: 'Aachen mit Fernverkehr, Rückfahrt ab Troisvierges über Luxemburg.',
    },
  ],
  { columns: 2 }
)}

${h2('Anspruchsvoll: für die dritte Saison', 'schwer')}
${routeGrid(
  [
    {
      title: 'Via Claudia Augusta, Donauwörth – Trient',
      region: 'Deutschland, Österreich, Italien',
      km: '500 km',
      hm: '4.500 hm',
      days: '7 – 10 Tage',
      surface: 'Asphalt und Schotter',
      level: 'mittel',
      text:
        'Die klassische Alpenüberquerung mit dem Rad, entlang einer römischen Handelsstraße. Der Fernpass und der Reschenpass sind die beiden großen Prüfungen – beide sind fahrbar, aber sie verlangen eine leichte Übersetzung und Respekt.',
      highlights: [
        'Echte Alpenüberquerung ohne alpine Technikanforderungen',
        'Reschenpass mit dem versunkenen Kirchturm im Reschensee',
        'Ab Meran wird es mediterran – der Kontrast ist der Reiz',
      ],
      train: 'Donauwörth per Bahn, Rückfahrt ab Trient über den Brenner.',
    },
    {
      title: 'North Coast 500, Schottland',
      region: 'Schottland',
      km: '830 km',
      hm: '9.000 hm',
      days: '10 – 14 Tage',
      surface: 'Asphalt, schmale Single-Track-Roads',
      level: 'anspruchsvoll',
      text:
        'Die Highlands-Rundfahrt ab Inverness. Landschaftlich außergewöhnlich, aber ernst zu nehmen: viel Wind, viel Regen, wenig Versorgung im Nordwesten und Steigungen bis 20 Prozent auf den Single-Track-Roads.',
      highlights: [
        'Wildcampen ist in Schottland weitgehend legal',
        'Landschaftlich mit nichts in Mitteleuropa vergleichbar',
        'Sehr dünn besiedelt – Versorgung sorgfältig planen',
      ],
      train: 'Anreise über Inverness, Fahrradmitnahme in britischen Zügen reservierungspflichtig.',
    },
  ],
  { columns: 2 }
)}

${callout(
  'Zur Einordnung der Schwierigkeit',
  '<p><strong>Leicht</strong> heißt: unter 300 Höhenmeter pro Tag, durchgehend asphaltiert, Versorgung alle 15 Kilometer, Bahnanbindung überall. <strong>Mittel</strong> heißt: bis 600 Höhenmeter pro Tag, teilweise Schotter, Versorgung alle 25 bis 30 Kilometer. <strong>Anspruchsvoll</strong> heißt: über 600 Höhenmeter pro Tag, Steigungen über 12 Prozent, längere Abschnitte ohne Versorgung und Wetter, das die Tour bestimmt.</p>',
  'info'
)}

${h2('Was im Ausland anders ist', 'ausland')}
${table({
  head: ['Thema', 'Worauf du achten musst'],
  rows: [
    ['Fahrradmitnahme in Zügen', 'Regelungen unterscheiden sich stark. In Frankreich und Italien oft nur in Regionalzügen, teils mit Reservierungspflicht'],
    ['Wildcampen', 'Nur in Skandinavien, Schottland und dem Baltikum weitgehend erlaubt – sonst überall verboten'],
    ['Versorgung', 'In Südeuropa Siesta beachten: zwischen 13 und 17 Uhr sind viele Läden geschlossen'],
    ['Wasser', 'In Italien und Frankreich gibt es öffentliche Brunnen, in Skandinavien Bachwasser (Filter empfohlen)'],
    ['Verkehrsregeln', 'Helmpflicht in einigen Ländern, Lichtpflicht am Tag in Skandinavien'],
    ['Notruf', '112 funktioniert EU-weit'],
    ['Versicherung', 'Auslandskrankenversicherung prüfen, Bergungskosten sind oft nicht gedeckt'],
    ['Ersatzteile', 'Außerhalb von Städten schwierig – Schaltauge und Speichen mitnehmen'],
  ],
})}

${h2('So wählst du deine erste Auslandsroute', 'auswahl')}
${checklist([
  '<strong>Bleib bei der ersten Auslandstour im deutschsprachigen oder gut erschlossenen Raum.</strong> Österreich, Dänemark und die Niederlande sind ideal.',
  '<strong>Nimm eine Route mit Bahn parallel.</strong> Donau, Loire und Alpe-Adria erfüllen das durchgehend.',
  '<strong>Rechne mit 20 Prozent weniger Tagesdistanz als zu Hause.</strong> Fremde Umgebung, Sprache und Orientierung kosten Zeit.',
  '<strong>Prüfe die Fahrradmitnahme im Zug vor der Buchung</strong> – für Hin- und Rückfahrt.',
  '<strong>Plane einen Puffertag ein.</strong> Ein verpasster Zug am Ende einer Auslandstour ist teuer.',
])}
`;

module.exports = article({
  href: '/routen/routen-europa.html',
  kicker: 'Routen · Europa',
  title: 'Acht Bikepacking-Routen in Europa',
  metaTitle: 'Bikepacking-Routen Europa: 8 Touren mit ehrlicher Schwierigkeit | Sattelfest',
  description:
    'Acht europäische Bikepacking-Routen für die zweite Saison: Donauradweg, Loire à Vélo, Dänemark, Alpe-Adria, EuroVelo 6, Vennbahn, Via Claudia Augusta und North Coast 500 – mit Distanz, Höhenmetern und Bahnanreise.',
  lead:
    'Acht Routen für die zweite Saison – von der komfortablen Flussstrecke bis zur ernsthaften Bergtour, jede mit ehrlicher Schwierigkeitseinordnung.',
  meta: [
    { icon: 'map', text: '10 Minuten Lesezeit' },
    { icon: 'mountain', text: 'Drei Schwierigkeitsstufen' },
    { icon: 'route', text: 'Mit Bahnanreise' },
  ],
  toc: [
    { label: 'Leicht: Komfortrouten', id: 'leicht' },
    { label: 'Mittel: mehr Höhenmeter', id: 'mittel' },
    { label: 'Anspruchsvoll: für die dritte Saison', id: 'schwer' },
    { label: 'Was im Ausland anders ist', id: 'ausland' },
    { label: 'So wählst du deine erste Auslandsroute', id: 'auswahl' },
  ],
  content,
  faq: [
    {
      q: 'Welche europäische Bikepacking-Route eignet sich für die erste Auslandstour?',
      a: '<p>Der Donauradweg von Passau nach Wien: 330 Kilometer, nur 700 Höhenmeter, durchgehend asphaltiert, alle 10 bis 15 Kilometer ein Ort und die Bahn verläuft parallel. Ebenfalls sehr geeignet sind die Loire à Vélo in Frankreich und die dänische Ostseeküstenroute mit ihrem kostenlosen Shelter-Netz.</p>',
    },
    {
      q: 'Kann ich mit dem Rad die Alpen überqueren?',
      a: '<p>Ja, die Via Claudia Augusta von Donauwörth nach Trient ist die klassische Route: 500 Kilometer, 4.500 Höhenmeter, Fernpass und Reschenpass als große Prüfungen. Beide sind ohne alpine Technikanforderungen fahrbar, verlangen aber eine leichte Übersetzung (kleinster Gang unter 1,0) und Erfahrung mit Mehrtagestouren.</p>',
    },
    {
      q: 'Wie unterscheiden sich die Schwierigkeitsstufen?',
      a: '<p>Leicht heißt: unter 300 Höhenmeter pro Tag, durchgehend asphaltiert, Versorgung alle 15 Kilometer, Bahnanbindung überall. Mittel: bis 600 Höhenmeter, teilweise Schotter, Versorgung alle 25 bis 30 Kilometer. Anspruchsvoll: über 600 Höhenmeter täglich, Steigungen über 12 Prozent, längere versorgungsfreie Abschnitte und wetterabhängige Planung.</p>',
    },
    {
      q: 'Wo darf ich in Europa wild campen?',
      a: '<p>In Schweden, Norwegen und Finnland durch das Jedermannsrecht, in Schottland über den Outdoor Access Code und in den baltischen Staaten weitgehend. Dänemark verbietet es, bietet dafür ein Netz kostenloser Shelter. In Mittel- und Südeuropa ist Wildcampen überall verboten, an Küsten teils mit hohen Bußgeldern belegt.</p>',
    },
    {
      q: 'Worauf muss ich bei der Fahrradmitnahme im Ausland achten?',
      a: '<p>Die Regelungen unterscheiden sich stark. In Frankreich und Italien ist die Mitnahme oft nur in Regionalzügen möglich, teilweise mit Reservierungspflicht. In Großbritannien ist sie reservierungspflichtig und die Plätze sind knapp. Prüfe die Bedingungen vor der Buchung – und zwar für Hin- und Rückfahrt.</p>',
    },
  ],
  related: [
    { href: '/routen/einsteiger-routen-deutschland.html', label: 'Einsteiger-Routen in Deutschland' },
    { href: '/routen/uebernachten.html', label: 'Übernachten: Wo du legal schläfst' },
    { href: '/routen/route-selbst-planen.html', label: 'Route selbst planen' },
    { href: '/unterwegs/sicherheit-notfall.html', label: 'Sicherheit & Notfall' },
  ],
});
