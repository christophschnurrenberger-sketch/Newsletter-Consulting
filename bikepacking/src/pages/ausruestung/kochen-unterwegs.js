'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, doDont, pickGrid, affNotice,
} = require('../../components');
const { shops } = require('../../data/shops');

const content = `
<p class="lead-p">
  Die ehrlichste Frage zum Kochen unterwegs lautet nicht „welcher Kocher“, sondern „überhaupt einer“.
  Ein Kochset wiegt mit Kartusche rund 700 Gramm. In Deutschland kommst du alle 30 bis 50 Kilometer
  an einem Supermarkt vorbei. Es gibt gute Gründe für beide Antworten.
</p>

${stats([
  { value: '700 g', label: 'Komplettes Kochset', note: 'Kocher, Kartusche, Topf, Löffel, Feuerzeug.' },
  { value: '8–12', label: 'Kochvorgänge', note: 'Aus einer 230-g-Gaskartusche.' },
  { value: '4 €', label: 'Warme Mahlzeit', note: 'Selbst gekocht statt 15 bis 25 Euro im Gasthaus.' },
])}

${h2('Brauchst du überhaupt einen Kocher?', 'ob')}
${doDont({
  doTitle: 'Kocher mitnehmen, wenn …',
  doItems: [
    'Du auf <strong>Trekkingplätzen</strong> übernachtest – dort gibt es nichts zu kaufen',
    'Du <strong>morgens Kaffee</strong> willst, bevor du fährst (der häufigste ehrliche Grund)',
    'Du in <strong>dünn besiedelten Gegenden</strong> fährst oder abends spät ankommst',
    'Du <strong>länger als drei Tage</strong> unterwegs bist – täglich kalt essen zermürbt',
    'Du <strong>Geld sparen</strong> willst: 4 Euro selbst gekocht statt 20 im Gasthaus',
    'Du <strong>im Frühjahr oder Herbst</strong> fährst – etwas Warmes am Abend ist dann kein Luxus',
  ],
  dontTitle: 'Kocher weglassen, wenn …',
  dontItems: [
    'Du eine <strong>Wochenendtour</strong> auf einem gut erschlossenen Radweg fährst',
    'Du auf <strong>Campingplätzen mit Gaststätte</strong> übernachtest',
    'Du im <strong>Hochsommer</strong> unterwegs bist und ohnehin kalt essen willst',
    'Du <strong>Gewicht sparen</strong> musst und die Route durch Ortschaften führt',
    'Es deine <strong>erste Tour</strong> ist – eine Sache weniger, die schiefgehen kann',
  ],
})}

${callout(
  'Der pragmatische Mittelweg',
  '<p>Nimm <strong>Kocher und Topf, aber plane keine Menüs</strong>. Der Kocher deckt Kaffee am Morgen, eine warme Suppe bei Regen und die Notfall-Nudeln ab, wenn der Supermarkt zu hatte. Alles andere kaufst du unterwegs. Das ist deutlich entspannter als eine durchgeplante Selbstversorgung – und du sparst dir Gewürze, Öl, Schneidebrett und Topf Nummer zwei.</p>',
  'tip'
)}

${h2('Die Kochersysteme', 'systeme')}
${table({
  head: ['System', 'Gewicht', 'Preis', 'Kochzeit 0,5 l', 'Bewertung'],
  rows: [
    [
      '<strong>Schraubkocher auf Gaskartusche</strong>',
      '60 – 120 g + 380 g Kartusche',
      '25 – 90 €',
      '3 – 4 Min.',
      '<strong>Der Standard.</strong> Schnell, regelbar, unkompliziert',
    ],
    [
      'Integriertes Kochsystem',
      '350 – 500 g inkl. Topf',
      '90 – 180 €',
      '2 – 3 Min.',
      'Sehr effizient, aber schwer und nur zum Wasserkochen',
    ],
    [
      'Spirituskocher',
      '80 – 200 g',
      '15 – 60 €',
      '6 – 10 Min.',
      'Günstig, leise, überall Brennstoff – aber langsam',
    ],
    [
      'Benzin-/Mehrstoffkocher',
      '350 – 500 g',
      '110 – 220 €',
      '3 – 4 Min.',
      'Für Weltreisen. In Europa unnötig kompliziert',
    ],
    [
      'Holzvergaser',
      '150 – 350 g',
      '30 – 80 €',
      '8 – 15 Min.',
      'Kein Brennstoffgewicht – aber oft Feuerverbot',
    ],
  ],
  note: 'Wichtig: Offenes Feuer und Holzkocher sind im Wald in Deutschland grundsätzlich verboten. Auf vielen Trekkingplätzen gilt zusätzlich ein ausdrückliches Feuerverbot.',
})}

${h3('Gaskartuschen: die praktische Seite', 'gas')}
${checklist([
  '<strong>Schraubkartuschen (EN 417)</strong> bekommst du in fast jedem Outdoorladen, vielen Baumärkten und Tankstellen',
  '<strong>230 g Gas reichen für 8 bis 12 Kochvorgänge</strong> – also gut eine Woche mit Kaffee und einer warmen Mahlzeit am Tag',
  '<strong>Nicht im Flugzeug transportierbar</strong> – bei Anreise per Flug am Zielort kaufen',
  '<strong>Bei Kälte lässt der Druck nach:</strong> Unter 5 Grad die Kartusche nachts in den Schlafsack legen',
  '<strong>Restgas prüfen:</strong> Kartusche in Wasser halten – wie hoch sie schwimmt, zeigt den Füllstand',
  '<strong>Leere Kartuschen gehören in den Wertstoffhof</strong>, nicht in den Restmüll und schon gar nicht in die Natur',
])}

${h2('Der Topf und das Drumherum', 'topf')}
<p>
  Ein Topf ist beim Bikepacking gleichzeitig Kochtopf, Teller, Schüssel und Tasse. Wer das
  akzeptiert, spart 200 Gramm und viel Packvolumen.
</p>
${table({
  head: ['Teil', 'Empfehlung', 'Gewicht', 'Anmerkung'],
  rows: [
    ['Topf', '700 – 900 ml, Aluminium hart eloxiert', '150 – 200 g', 'Titan ist leichter, aber brennt schneller an'],
    ['Deckel', 'Immer verwenden', 'inkl.', 'Halbiert die Kochzeit und spart Gas'],
    ['Löffel', 'Langer Löffel aus Kunststoff oder Titan', '15 – 25 g', 'Lang genug für Beutelmahlzeiten'],
    ['Feuerzeug', 'Zwei Stück, getrennt verstaut', '25 g', 'Sturmfeuerzeuge versagen bei Kälte oft'],
    ['Spülschwamm', 'Halber Schwamm im Zipbeutel', '15 g', 'Ganzer Schwamm ist unnötig'],
    ['Windschutz', 'Alufolie oder gefaltetes Blech', '25 g', 'Spart bis zu 30 Prozent Gas'],
    ['Tasse', '<strong>Nicht nötig</strong>', '0 g', 'Der Topf ist die Tasse'],
    ['Schneidebrett, Messer, Gewürzset', '<strong>Nicht nötig</strong>', '0 g', 'Salz im Zipbeutel reicht'],
  ],
  note: 'Ein Windschutz ist das am meisten unterschätzte Zubehör: Er kostet 3 Euro, wiegt 25 Gramm und verlängert die Reichweite einer Kartusche spürbar.',
})}

${h2('Was du unterwegs wirklich isst', 'essen')}
${h3('Frühstück', 'fruehstueck')}
${table({
  head: ['Variante', 'Aufwand', 'Kalorien', 'Anmerkung'],
  rows: [
    ['Instant-Haferbrei mit Milchpulver', 'Wasser kochen', '350 – 450', 'Der Klassiker, sättigt lange'],
    ['Müsli mit Milchpulver, kalt', 'Kein Kocher nötig', '400 – 500', 'Funktioniert auch ohne Kocher'],
    ['Brötchen vom Vorabend mit Frischkäse', 'Keiner', '350 – 500', 'Wenn ein Bäcker auf der Route liegt'],
    ['Kaffee plus Riegel, dann Bäckerei', 'Minimal', '200 dann 500', 'Früh losfahren, nach 20 km frühstücken'],
  ],
  note: 'Bewährt: 20 bis 30 Kilometer fahren, dann in einer Bäckerei richtig frühstücken. Man kommt früh weg und hat die erste Etappe geschafft, bevor es warm wird.',
})}

${h3('Unterwegs', 'unterwegs')}
${checklist([
  '<strong>Alle 60 bis 90 Minuten etwas essen</strong>, auch ohne Hunger – wer wartet, bis der Hunger kommt, hat schon verloren',
  '<strong>Riegel, Bananen, Salzbrezeln, Studentenfutter</strong> – die Klassiker funktionieren, weil sie funktionieren',
  '<strong>Etwas Salziges</strong> ist wichtiger, als die meisten denken: An heißen Tagen verlierst du erhebliche Mengen Natrium',
  '<strong>Ein Notriegel bleibt immer im Gepäck</strong> und wird nur im Ernstfall angebrochen',
  '<strong>Mittags warm essen</strong> statt abends, wenn ein Gasthaus auf der Route liegt – das spart Gepäck und Zeit',
])}

${h3('Abendessen aus dem Supermarkt', 'abend')}
${table({
  head: ['Gericht', 'Kochzeit', 'Kosten', 'Kalorien'],
  rows: [
    ['Nudeln mit Pesto und Hartkäse', '10 Min.', '3 – 4 €', '800 – 1.000'],
    ['Couscous mit Gemüsebrühe und Salami', '5 Min. (nur ziehen lassen)', '3 €', '700 – 900'],
    ['Kartoffelpüree-Pulver mit Speckwürfeln', '5 Min.', '2,50 €', '700 – 850'],
    ['Instant-Nudelsuppe plus Ei und Gemüse', '8 Min.', '3 €', '600 – 750'],
    ['Trekkingnahrung im Beutel', 'Nur Wasser kochen', '7 – 12 €', '600 – 800'],
    ['Reispfanne aus dem Beutel (2 Min.)', '4 Min.', '2,50 €', '600 – 800'],
  ],
  note: 'Couscous ist unter Radreisenden beliebt, weil er nur heißes Wasser braucht und nach fünf Minuten fertig ist – das spart Gas und Zeit.',
})}

${callout(
  'Die Kalorienrechnung',
  '<p>Ein Bikepacking-Tag mit 70 Kilometern und 600 Höhenmetern verbraucht je nach Körpergewicht und Tempo etwa <strong>2.500 bis 3.500 Kilokalorien zusätzlich</strong> zum Grundumsatz. Das ist der Grund, warum viele nach zwei Tagen ständig hungrig sind: Sie essen wie zu Hause und fahren wie im Wettkampf. Plane bewusst mehr ein, vor allem an den ersten Tagen.</p>',
  'info'
)}

${affNotice()}

${h2('Kaufempfehlungen', 'empfehlungen')}
${pickGrid(
  [
    {
      badge: 'Für fast alle richtig',
      title: 'Schraubkocher plus Alutopf',
      forWhom: 'Der Standard: schnell, regelbar, überall Brennstoff.',
      price: 'ca. 45 – 120 € komplett',
      specs: [
        { k: 'Kocher', v: '60 – 120 g' },
        { k: 'Topf', v: '700 – 900 ml, 150 – 200 g' },
        { k: 'Kartusche', v: '230 g Gas, 380 g gesamt' },
        { k: 'Kochzeit', v: '3 – 4 Min. für 0,5 l' },
      ],
      pros: [
        'Flamme regelbar – nicht nur Wasser kochen, sondern auch köcheln',
        'Kartuschen sind in ganz Europa erhältlich',
        'Sehr einfache Bedienung, kaum etwas kann kaputtgehen',
      ],
      cons: [
        'Kartusche ist sperrig und lässt sich nicht teilen',
        'Bei unter 5 Grad lässt der Druck spürbar nach',
      ],
      partner: 'amazon',
      url: shops.gaskocher,
      ctaLabel: 'Gaskocher ansehen',
    },
    {
      badge: 'Günstig und leise',
      title: 'Spirituskocher-Set',
      forWhom: 'Wer selten kocht, wenig ausgeben will und Zeit hat.',
      price: 'ca. 20 – 60 €',
      specs: [
        { k: 'Gewicht', v: '80 – 200 g mit Topfständer' },
        { k: 'Brennstoff', v: 'Brennspiritus, überall erhältlich' },
        { k: 'Kochzeit', v: '6 – 10 Min. für 0,5 l' },
        { k: 'Verbrauch', v: 'ca. 30 ml je Kochvorgang' },
      ],
      pros: [
        'Sehr günstig in Anschaffung und Betrieb',
        'Brennstoffmenge frei dosierbar – kein Kartuschenrest',
        'Absolut lautlos, keine beweglichen Teile',
      ],
      cons: [
        'Deutlich langsamer als Gas',
        'Flamme kaum regelbar, bei Wind empfindlich',
        'Flüssiger Brennstoff kann auslaufen – gut verpacken',
      ],
      partner: 'amazon',
      url: shops.spirituskocher,
      ctaLabel: 'Spirituskocher ansehen',
    },
    {
      badge: 'Nur Wasser, dafür schnell',
      title: 'Integriertes Kochsystem',
      forWhom: 'Wer ausschließlich Wasser für Beutelmahlzeiten und Kaffee kocht.',
      price: 'ca. 90 – 180 €',
      specs: [
        { k: 'Gewicht', v: '350 – 500 g komplett' },
        { k: 'Kochzeit', v: '2 – 3 Min. für 0,5 l' },
        { k: 'Effizienz', v: 'ca. 30 % weniger Gasverbrauch' },
        { k: 'Einschränkung', v: 'Zum Köcheln ungeeignet' },
      ],
      pros: [
        'Schnellste Variante, sehr windunempfindlich',
        'Kartusche hält deutlich länger',
        'Alles steckt ineinander – kompakt gepackt',
      ],
      cons: [
        'Schwer im Vergleich zum reinen Schraubkocher',
        'Nudeln kochen funktioniert nur mittelmäßig',
      ],
      note: 'Lohnt sich, wenn du auf Trekkingnahrung setzt. Wer echte Mahlzeiten kochen will, ist mit Schraubkocher und Topf besser bedient.',
    },
  ],
  { columns: 3 }
)}

${h2('Regeln, die du kennen solltest', 'regeln')}
${checklist(
  [
    '<strong>Offenes Feuer ist im Wald verboten</strong> – in ganz Deutschland, ganzjährig. Das gilt auch für Holzkocher mit offener Flamme.',
    '<strong>Auf vielen Trekkingplätzen ist jede Art von Feuer untersagt</strong>, teilweise auch Gaskocher. Steht in der Platzordnung.',
    '<strong>Im Zelt kochen ist gefährlich</strong>: Kohlenmonoxid ist geruchlos, und Zeltmaterial brennt schnell. In der Apsis mit weit offener Tür und nur, wenn es nicht anders geht.',
    '<strong>Nie unbeaufsichtigt lassen</strong> – ein umgekippter Kocher auf trockenem Waldboden ist ein Waldbrand.',
    '<strong>Waschwasser nicht in Gewässer</strong>, auch nicht mit biologisch abbaubarer Seife. Mindestens 50 Meter Abstand, in den Boden versickern lassen.',
    '<strong>Essensreste und Verpackungen nimmst du mit.</strong> Auch Bananenschalen – die brauchen zwei Jahre.',
  ],
  { tone: 'dont' }
)}
`;

module.exports = article({
  href: '/ausruestung/kochen-unterwegs.html',
  kicker: 'Ausrüstung · Küche',
  title: 'Kochen unterwegs',
  metaTitle: 'Bikepacking kochen: Kocher, Ausrüstung, Rezepte & Regeln | Sattelfest',
  description:
    'Kochen beim Bikepacking: Brauchst du überhaupt einen Kocher? Gas, Spiritus oder integriertes System im Vergleich, was du unterwegs wirklich isst, die Kalorienrechnung und die Regeln zu Feuer im Wald.',
  lead:
    'Die ehrliche Frage ist nicht „welcher Kocher“, sondern „überhaupt einer“. Ein Kochset wiegt 700 Gramm – Supermärkte liegen alle 30 bis 50 Kilometer.',
  meta: [
    { icon: 'clock', text: '10 Minuten Lesezeit' },
    { icon: 'weight', text: 'Mit Gewichtsrechnung' },
    { icon: 'alert', text: 'Regeln zu Feuer im Wald' },
  ],
  toc: [
    { label: 'Brauchst du überhaupt einen Kocher?', id: 'ob' },
    { label: 'Die Kochersysteme', id: 'systeme' },
    { label: 'Der Topf und das Drumherum', id: 'topf' },
    { label: 'Was du unterwegs wirklich isst', id: 'essen' },
    { label: 'Kaufempfehlungen', id: 'empfehlungen' },
    { label: 'Regeln, die du kennen solltest', id: 'regeln' },
  ],
  content,
  faq: [
    {
      q: 'Brauche ich beim Bikepacking einen Kocher?',
      a: '<p>Nicht zwingend. In Deutschland liegt alle 30 bis 50 Kilometer ein Supermarkt, und ein Kochset wiegt mit Kartusche rund 700 Gramm. Ein Kocher lohnt sich, wenn du auf Trekkingplätzen übernachtest, morgens Kaffee willst, länger als drei Tage unterwegs bist oder im Frühjahr und Herbst fährst – dann ist etwas Warmes am Abend kein Luxus.</p>',
    },
    {
      q: 'Welcher Kocher eignet sich am besten fürs Bikepacking?',
      a: '<p>Ein Schraubkocher auf Gaskartusche. Er wiegt 60 bis 120 Gramm, kocht einen halben Liter in drei bis vier Minuten, ist regelbar und Kartuschen bekommt man in ganz Europa. Spirituskocher sind günstiger und leiser, aber deutlich langsamer. Integrierte Systeme sind am schnellsten, taugen aber fast nur zum Wasserkochen.</p>',
    },
    {
      q: 'Wie lange reicht eine Gaskartusche?',
      a: '<p>Eine 230-Gramm-Kartusche reicht für etwa 8 bis 12 Kochvorgänge – also gut eine Woche mit Kaffee am Morgen und einer warmen Mahlzeit am Abend. Ein einfacher Windschutz aus Alufolie für drei Euro verlängert das spürbar. Bei unter 5 Grad lässt der Gasdruck nach: Nimm die Kartusche nachts mit in den Schlafsack.</p>',
    },
    {
      q: 'Darf ich beim Bikepacking ein Feuer machen?',
      a: '<p>Nein. Offenes Feuer ist im Wald in ganz Deutschland ganzjährig verboten, das gilt auch für Holzkocher mit offener Flamme. Auf vielen Trekkingplätzen ist zusätzlich jede Art von Feuer untersagt, teilweise sogar Gaskocher – das steht in der jeweiligen Platzordnung, die du vor der Buchung lesen solltest.</p>',
    },
    {
      q: 'Wie viele Kalorien verbrauche ich beim Bikepacking?',
      a: '<p>Ein Tag mit 70 Kilometern und 600 Höhenmetern verbraucht je nach Körpergewicht und Tempo etwa 2.500 bis 3.500 Kilokalorien zusätzlich zum Grundumsatz. Viele essen wie zu Hause und fahren wie im Wettkampf – deshalb der ständige Hunger ab Tag zwei. Iss alle 60 bis 90 Minuten etwas, auch ohne Hungergefühl.</p>',
    },
  ],
  related: [
    { href: '/routen/wasser-verpflegung.html', label: 'Wasser & Verpflegung unterwegs' },
    { href: '/ausruestung/packliste.html', label: 'Die Packliste mit Gewichten' },
    { href: '/unterwegs/tagesablauf.html', label: 'Der Tagesablauf auf Tour' },
    { href: '/routen/uebernachten.html', label: 'Übernachten: Wo du legal schläfst' },
  ],
});
