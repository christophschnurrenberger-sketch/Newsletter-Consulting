'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, steps,
} = require('../../components');

const content = `
<p class="lead-p">
  Zwei Räder mit identischem Gepäck können sich völlig unterschiedlich fahren. Der Unterschied liegt
  nicht im Gewicht, sondern darin, wo es sitzt. Falsch verteiltes Gepäck wirkt wie ein Fahrfehler,
  den du den ganzen Tag lang wiederholst.
</p>

${stats([
  { value: '50/30/20', label: 'Die Grundverteilung', note: 'Prozent des Gewichts: hinten / Mitte / vorn.' },
  { value: 'tief', label: 'Die wichtigste Regel', note: 'Schweres so nah wie möglich an die Radachsen.' },
  { value: '10 km', label: 'Nachziehen nicht vergessen', note: 'Nach den ersten Kilometern alle Riemen prüfen.' },
])}

${h2('Die drei Prinzipien', 'prinzipien')}
${h3('1. Schwer nach unten', 'unten')}
<p>
  Der Schwerpunkt bestimmt, wie kippelig ein Rad ist. Ein Kilo auf Höhe des Tretlagers merkst du
  kaum; dasselbe Kilo oben auf der Lenkerrolle spürst du in jeder Kurve. Die Reihenfolge nach
  Höhe: Rahmendreieck unten, Gabeltaschen, Satteltasche vorn, Lenkerrolle, Satteltasche hinten.
</p>

${h3('2. Schwer nach innen', 'innen')}
<p>
  Je näher am Radstand-Mittelpunkt, desto weniger Einfluss aufs Handling. Deshalb sind
  Rahmentasche und Gabeltaschen so gut und die Enden der Satteltasche und der Lenkerrolle so
  schlecht. Ein Kilo ganz hinten in der Satteltasche wirkt mit dem längsten Hebel im ganzen System.
</p>

${h3('3. Links wie rechts', 'symmetrie')}
<p>
  Symmetrie ist bei Gabeltaschen und Stem Bags entscheidend. Schon 500 Gramm Unterschied zwischen
  links und rechts sind in der Lenkung deutlich zu spüren – das Rad zieht dann in eine Richtung und
  du korrigierst den ganzen Tag unbewusst gegen.
</p>

${callout(
  'Die Faustformel',
  '<p><strong>50 Prozent hinten, 30 Prozent Mitte, 20 Prozent vorn.</strong> Bei 10 Kilo Gepäck heißt das: 5 Kilo Satteltasche, 3 Kilo Rahmentasche, 2 Kilo Lenker und Gabel zusammen. Wer mehr als 4 Kilo an den Lenker hängt, verliert Lenkpräzision. Wer mehr als 6 Kilo in die Satteltasche packt, kämpft mit Pendeln.</p>',
  'tip'
)}

${h2('Was wohin gehört – die vollständige Tabelle', 'zuordnung')}
${table({
  head: ['Gegenstand', 'Gewicht', 'Position', 'Warum'],
  rows: [
    ['Werkzeug, Multitool', '160 g', 'Rahmentasche', 'Schwer, kompakt, selten gebraucht'],
    ['Ersatzschläuche', '2 × 150 g', 'Rahmentasche', 'Kompakt und schwer für die Größe'],
    ['Minipumpe', '110 g', 'Rahmen oder Rahmenmontage', 'Sperrig, aber flach'],
    ['Powerbank', '200 – 400 g', 'Rahmentasche', 'Schwerste Elektronik, gehört tief'],
    ['Gaskartusche', '380 g', 'Rahmentasche unten', 'Kompakt und sehr schwer'],
    ['Kocher und Topf', '270 g', 'Rahmentasche oder Gabel', 'Hart und kantig'],
    ['Wasser 1,5 l', '1.500 g', '<strong>Gabel oder Rahmen</strong>', 'Das Schwerste überhaupt – so tief wie möglich'],
    ['Verpflegung Tag', '600 g', 'Rahmentasche, Stem Bag', 'Erreichbar, aber schwer'],
    ['Schlafsack', '600 – 1.100 g', 'Lenkerrolle', 'Leicht, aber sehr voluminös'],
    ['Innenzelt / Tarp', '700 g', 'Lenkerrolle', 'Voluminös, leicht'],
    ['Zeltstangen', '350 g', 'Gabeltasche oder längs am Rahmen', 'Lang und sperrig'],
    ['Außenzelt', '800 g', 'Satteltasche oder Gabel', 'Oft nass – nicht zu Kleidung'],
    ['Isomatte', '450 g', 'Satteltasche vorn oder Lenkerrolle', 'Voluminös, mittleres Gewicht'],
    ['Wechselkleidung', '700 g', 'Satteltasche vorn', 'Braucht man erst abends'],
    ['Isolationsjacke', '300 g', 'Satteltasche hinten oder Lenkerrolle', 'Leicht, komprimiert stark'],
    ['Regenjacke', '320 g', '<strong>Griffbereit ganz oben</strong>', 'Muss in 60 Sekunden erreichbar sein'],
    ['Waschzeug, Handtuch', '170 g', 'Satteltasche', 'Erst abends nötig'],
    ['Handy, Riegel, Geld', '350 g', 'Oberrohrtasche', 'Im Fahren erreichbar'],
    ['Ausweis, Bargeld', '60 g', '<strong>Hüfttasche am Körper</strong>', 'Bleibt bei dir, wenn das Rad steht'],
  ],
  note: 'Die zwei fett markierten Zeilen sind die, die Einsteiger am häufigsten falsch machen: Wasser zu hoch und Regenjacke zu tief vergraben.',
})}

${callout(
  'Die Regenjacken-Regel',
  '<p>Die Regenjacke gehört an eine Stelle, an der du sie <strong>in unter einer Minute im Stehen</strong> erreichst – ganz oben in der Lenkerrolle, außen an einem Riemen oder in der Oberrohrtasche. Wenn du für die Jacke die Satteltasche auspacken musst, wirst du sie zu spät anziehen. Das gilt doppelt bei Sommergewittern, die von hinten kommen und die man nicht sieht.</p>',
  'warn'
)}

${h2('Die Packreihenfolge am Morgen', 'reihenfolge')}
${steps([
  {
    title: 'Zuerst die schweren Sachen in die Rahmentasche',
    text:
      'Werkzeug, Powerbank, Kartusche, Wasser. Diese Tasche wird als Erste voll und ändert sich unterwegs kaum. Wenn du sie zuletzt packst, passt erfahrungsgemäß nicht mehr alles hinein.',
  },
  {
    title: 'Schlafsack und Zelt in die Lenkerrolle',
    text:
      'Das Innenzelt oder Tarp nach unten, den Schlafsack darüber, die Enden mit Socken und Handschuhen ausstopfen. Fest zusammendrücken, bevor du den Verschluss rollst.',
  },
  {
    title: 'Satteltasche von vorn nach hinten',
    text:
      'Wechselkleidung und Isomatte direkt an den Sattel, Handtuch und Waschzeug in die Mitte, Isolationsjacke ganz nach hinten. Vollpacken, damit nichts pendelt.',
  },
  {
    title: 'Kleinteile und Tagesbedarf',
    text:
      'Oberrohrtasche und Stem Bags zuletzt: Handy, Riegel für die nächsten drei Stunden, Sonnencreme. Regenjacke griffbereit obenauf.',
  },
  {
    title: 'Alle Riemen zurren – und nach 10 km nachziehen',
    text:
      'Kompressionsriemen erst zuletzt, wenn alle Taschen voll sind. Nach den ersten Kilometern setzt sich das Gepäck: Halte an, ziehe jeden Riemen einmal nach. Danach hält es den ganzen Tag.',
  },
])}

${h2('Der Fahrtest vor der Tour', 'fahrtest')}
<p>
  Zwanzig Minuten auf einem leeren Parkplatz zeigen dir mehr als jede Anleitung:
</p>
${table({
  head: ['Test', 'Was du prüfst', 'Was es bedeutet'],
  rows: [
    ['Rad am Lenker anheben', 'Kippt das Vorderrad nach unten?', 'Zu viel Gewicht am Lenker'],
    ['Langsame Acht fahren', 'Fällt das Rad in die Kurve?', 'Schwerpunkt zu hoch vorn'],
    ['Wiegetritt 20 Sekunden', 'Schaukelt die Satteltasche?', 'Nicht voll genug oder Gewicht zu weit hinten'],
    ['Kurz freihändig (auf leerem Platz)', 'Zieht das Rad zur Seite?', 'Unsymmetrische Beladung'],
    ['Bordsteinkante hinunter', 'Klappert etwas? Schleift etwas?', 'Riemen nachziehen, Abstände prüfen'],
    ['Vollbremsung', 'Kommt das Heck hoch? Rutscht was?', 'Zu viel Gewicht zu hoch hinten'],
    ['Rückwärts treten im Stand', 'Berührt die Ferse die Tasche?', 'Satteltasche zu breit oder zu weit vorn'],
  ],
  note: 'Diesen Test machst du einmal vor der ersten Tour und danach nie wieder – weil du dann weißt, wie dein Setup sich anfühlt, wenn es stimmt.',
})}

${h2('Häufige Packfehler', 'fehler')}
${checklist(
  [
    '<strong>Wasser in der Lenkerrolle.</strong> 1,5 Liter sind 1,5 Kilo an der schlechtesten Position am Rad.',
    '<strong>Satteltasche halb leer.</strong> Führt zuverlässig zum Pendeln. Lieber eine kleinere Tasche vollpacken.',
    '<strong>Regenjacke ganz unten.</strong> Klassiker – und der Grund, warum viele nass werden, obwohl sie eine Jacke dabeihaben.',
    '<strong>Alles Schwere hinten.</strong> Das Vorderrad wird leicht, in Anstiegen steigt es hoch, in Abfahrten fühlt sich die Lenkung nervös an.',
    '<strong>Harte Gegenstände außen an Riemen.</strong> Sie lösen sich, gehen verloren oder schlagen ins Laufrad.',
    '<strong>Nasses zusammen mit Trockenem.</strong> Ein nasses Außenzelt macht in acht Stunden die ganze Satteltasche feucht.',
    '<strong>Nichts nachgezogen.</strong> Gepäck setzt sich. Nach 10 Kilometern sind alle Riemen lockerer als beim Start.',
  ],
  { tone: 'dont' }
)}

${h2('Packen für Nässe und für Dreck', 'zonen')}
<p>
  Bewährt hat sich ein Drei-Zonen-Denken. Es kostet nichts und verhindert die zwei ärgerlichsten
  Probleme auf Tour: nasse Kleidung und dreckige Ausrüstung.
</p>
${table({
  head: ['Zone', 'Inhalt', 'Wo'],
  rows: [
    ['<strong>Trockenzone</strong>', 'Schlafsack, Wechselkleidung, Elektronik', 'Immer in eigenen Packsäcken, unabhängig von der Tasche'],
    ['<strong>Nasszone</strong>', 'Außenzelt, Regensachen, Handtuch', 'Gabeltasche oder eigener Packsack, nie zur Trockenzone'],
    ['<strong>Schmutzzone</strong>', 'Schuhe, Kochgeschirr, Müllbeutel', 'Gabeltasche oder außen, nie zur Kleidung'],
  ],
  note: 'Drei Packsäcke in verschiedenen Farben kosten zusammen 20 bis 35 Euro und lösen dieses Thema für Jahre.',
})}
`;

module.exports = article({
  href: '/taschen/richtig-packen.html',
  kicker: 'Taschen · Packen',
  title: 'Richtig packen: Gewichtsverteilung',
  metaTitle: 'Bikepacking richtig packen: Gewichtsverteilung & Packreihenfolge | Sattelfest',
  description:
    'Bikepacking-Gepäck richtig verteilen: die 50/30/20-Regel, was in welche Tasche gehört, die Packreihenfolge am Morgen, der Fahrtest vor der Tour und die sieben häufigsten Packfehler.',
  lead:
    'Zwei Räder mit identischem Gepäck fahren sich völlig unterschiedlich. Der Unterschied ist nicht das Gewicht, sondern wo es sitzt.',
  meta: [
    { icon: 'weight', text: '9 Minuten Lesezeit' },
    { icon: 'check', text: 'Mit vollständiger Zuordnungstabelle' },
    { icon: 'bike', text: 'Inklusive Fahrtest' },
  ],
  toc: [
    { label: 'Die drei Prinzipien', id: 'prinzipien' },
    { label: 'Was wohin gehört', id: 'zuordnung' },
    { label: 'Die Packreihenfolge am Morgen', id: 'reihenfolge' },
    { label: 'Der Fahrtest vor der Tour', id: 'fahrtest' },
    { label: 'Häufige Packfehler', id: 'fehler' },
    { label: 'Packen für Nässe und Dreck', id: 'zonen' },
  ],
  content,
  faq: [
    {
      q: 'Wie verteile ich das Gewicht beim Bikepacking richtig?',
      a: '<p>Grob 50 Prozent hinten in die Satteltasche, 30 Prozent in die Rahmentasche, 20 Prozent nach vorn an Lenker und Gabel. Dabei gelten drei Prinzipien: Schweres nach unten (nah an die Radachsen), Schweres nach innen (nah an die Radstandsmitte) und links wie rechts gleich schwer.</p>',
    },
    {
      q: 'Wo transportiere ich Wasser beim Bikepacking?',
      a: '<p>So tief wie möglich: im Rahmendreieck oder in Gabeltaschen. Wasser ist mit 1 Kilo pro Liter der schwerste Einzelposten – in der Lenkerrolle sitzt es an der schlechtesten Stelle am Rad. Zwei Anything Cages an der Gabel mit je einem Liter sind die beste Lösung, weil sie tief und symmetrisch tragen.</p>',
    },
    {
      q: 'Warum pendelt meine Satteltasche trotz richtiger Größe?',
      a: '<p>Meist, weil sie nicht vollständig gefüllt ist oder das Schwere zu weit hinten liegt. Pack Kompaktes und Schweres direkt an den Sattel, Leichtes nach hinten zum Verschluss, fülle die Tasche komplett aus und zieh den Kompressionsriemen fest. Nach den ersten zehn Kilometern noch einmal nachziehen – Gepäck setzt sich.</p>',
    },
    {
      q: 'Wo gehört die Regenjacke hin?',
      a: '<p>Dorthin, wo du sie in unter einer Minute im Stehen erreichst: ganz oben in der Lenkerrolle, in der Oberrohrtasche oder außen an einem Riemen. Wenn du dafür die Satteltasche auspacken musst, ziehst du sie zu spät an. Sommergewitter kommen oft von hinten und sind erst sichtbar, wenn es zu spät ist.</p>',
    },
    {
      q: 'Wie teste ich, ob ich richtig gepackt habe?',
      a: '<p>Fahre auf einem leeren Parkplatz eine langsame Acht (fällt das Rad in die Kurve, ist vorn zu viel Gewicht), 20 Sekunden Wiegetritt (schaukelt die Satteltasche, ist sie zu leer), eine Vollbremsung und einmal rückwärts treten im Stand (Fersenfreiheit). Zwanzig Minuten zeigen mehr als jede Anleitung.</p>',
    },
  ],
  related: [
    { href: '/taschen/wasserdicht-packen.html', label: 'Wasserdicht packen' },
    { href: '/ausruestung/packliste.html', label: 'Die Packliste mit Gewichten' },
    { href: '/taschen/satteltasche.html', label: 'Satteltasche (Seatpack)' },
    { href: '/tools/etappen-rechner.html', label: 'Etappen- & Gewichts-Rechner' },
  ],
});
