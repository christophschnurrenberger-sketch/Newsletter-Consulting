'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, pickGrid, affNotice,
} = require('../../components');
const { shops } = require('../../data/shops');

const content = `
<p class="lead-p">
  Der häufigste Grund für eine kalte Nacht ist nicht ein zu dünner Schlafsack, sondern eine zu
  schlechte Isomatte. Unter deinem Körpergewicht wird die Isolation des Schlafsacks vollständig
  plattgedrückt – nach unten wärmt allein die Matte. Wer das weiß, kauft anders ein.
</p>

${stats([
  { value: 'R 2,5', label: 'Sommer-Minimum', note: 'Darunter wird es unter 12 Grad Bodentemperatur ungemütlich.' },
  { value: 'R 3,5', label: 'Frühjahr und Herbst', note: 'Der Bereich für die deutsche Radsaison.' },
  { value: '0 °C', label: 'Komfort statt Limit', note: 'Auf diesen Wert kommt es beim Schlafsack an.' },
])}

${h2('Die Isomatte entscheidet über die Nacht', 'isomatte')}
<p>
  Der <strong>R-Wert</strong> beschreibt den Wärmedurchgangswiderstand einer Isomatte. Er ist seit
  2020 genormt (ASTM F3340), also zwischen Herstellern vergleichbar – eine der wenigen
  Outdoor-Angaben, auf die man sich wirklich verlassen kann.
</p>

${table({
  head: ['R-Wert', 'Geeignet für', 'Bodentemperatur', 'Typisches Gewicht'],
  rows: [
    ['unter 2,0', 'Hochsommer, warme Nächte', 'ab ca. 15 °C', '250 – 400 g'],
    ['2,0 – 3,0', 'Sommer Mai bis September', 'ab ca. 10 °C', '350 – 500 g'],
    ['3,0 – 4,0', '<strong>Frühjahr, Herbst – die deutsche Radsaison</strong>', 'ab ca. 3 °C', '450 – 700 g'],
    ['4,0 – 5,5', 'Kalte Nächte, Höhenlagen', 'ab ca. −5 °C', '550 – 900 g'],
    ['über 5,5', 'Winter', 'unter −10 °C', '700 – 1.200 g'],
  ],
  note: 'R-Werte lassen sich addieren: Eine dünne Schaumstoffmatte (R 2,0) unter einer Luftmatte (R 2,0) ergibt R 4,0. Das ist die günstigste Art, für kalte Nächte aufzurüsten.',
})}

${callout(
  'Der Fehler, der jedes Frühjahr wiederholt wird',
  '<p>Jemand kauft einen guten Schlafsack für 5 Grad Komforttemperatur und eine Sommer-Isomatte mit R 1,5 – und friert im April trotzdem. Der Boden hat nachts 6 Grad, die Matte lässt die Wärme durch, und der Schlafsack ist unter dem Rücken auf null gedrückt. <strong>Erst die Matte, dann der Schlafsack.</strong></p>',
  'warn'
)}

${h3('Die drei Mattenarten', 'mattenarten')}
${table({
  head: ['Art', 'Gewicht', 'Preis', 'Vorteil', 'Nachteil'],
  rows: [
    [
      'Aufblasbar (Luftmatte)',
      '350 – 700 g',
      '80 – 220 €',
      'Bester Komfort, kleinstes Packmaß, hoher R-Wert möglich',
      'Kann ein Loch bekommen, raschelt oft',
    ],
    [
      'Selbstaufblasend',
      '600 – 1.100 g',
      '50 – 130 €',
      'Robust, bequem, verzeiht kleine Löcher',
      'Schwer und großes Packmaß',
    ],
    [
      'Schaumstoff (Faltmatte)',
      '300 – 500 g',
      '25 – 60 €',
      'Unkaputtbar, sofort einsatzbereit, günstig',
      'Sehr großes Packmaß, wenig Komfort',
    ],
  ],
  note: 'Fürs Bikepacking sind Luftmatten am verbreitetsten, weil das Packmaß am Rad das knappste Gut ist. Ein Reparaturset gehört dann aber zwingend mit.',
})}

${checklist([
  '<strong>Nimm ein Reparaturset mit</strong> – Luftmatten bekommen Löcher, meist am zweiten Abend',
  '<strong>Pumpsack statt Mund:</strong> Atemfeuchtigkeit im Inneren führt langfristig zu Schimmel',
  '<strong>Breite prüfen:</strong> Standard sind 51 cm, „wide“ 63 cm. Wer sich viel dreht, will die breite Version',
  '<strong>Länge:</strong> Kurze Matten (120 cm) sparen 150 g – die Beine liegen dann auf Kleidung',
  '<strong>Geräusch:</strong> Manche Matten knistern erheblich. Im Laden vorher darauf achten',
])}

${h2('Der Schlafsack', 'schlafsack')}
${h3('Die Temperaturangaben verstehen', 'temperatur')}
<p>
  Auf jedem Schlafsack stehen drei bis vier Zahlen. Nur eine davon ist für die Kaufentscheidung
  relevant.
</p>
${table({
  head: ['Angabe', 'Bedeutung', 'Verwendbar?'],
  rows: [
    [
      '<strong>Komforttemperatur</strong>',
      'Eine Frau mit Normalempfinden schläft entspannt',
      '<strong>Ja – das ist der Wert, nach dem du kaufst</strong>',
    ],
    [
      'Limit-Temperatur',
      'Ein Mann mit Normalempfinden schläft gerade so',
      'Nur bedingt – „gerade so“ heißt zusammengerollt',
    ],
    [
      'Extremtemperatur',
      'Überlebensbereich, nicht Schlafbereich',
      'Nein – hat mit Komfort nichts zu tun',
    ],
  ],
  note: 'Als Faustregel: Nimm die Komforttemperatur und rechne 5 Grad Puffer ein. Wer schnell friert, rechnet 8 bis 10 Grad.',
})}

${table({
  head: ['Saison in Deutschland', 'Nachttemperatur', 'Komforttemperatur Schlafsack'],
  rows: [
    ['Hochsommer, Juni – August', '12 – 18 °C', '+10 bis +12 °C'],
    ['Mai und September', '6 – 12 °C', '+5 bis +7 °C'],
    ['April und Oktober', '2 – 8 °C', '0 bis +3 °C'],
    ['März und November', '−2 bis +5 °C', '−5 bis 0 °C'],
    ['Mittelgebirge, über 600 m', '5 Grad kälter als im Tal', 'Eine Stufe wärmer wählen'],
  ],
  note: 'Die kälteste Stunde der Nacht liegt kurz vor Sonnenaufgang – nicht um Mitternacht. Die Prognose für „nachts“ meint oft nicht dieses Minimum.',
})}

${h3('Daune oder Kunstfaser', 'fuellung')}
${table({
  head: ['', 'Daune', 'Kunstfaser'],
  rows: [
    ['Gewicht bei gleicher Wärme', '<strong>30 – 40 % leichter</strong>', 'Schwerer'],
    ['Packmaß', '<strong>Deutlich kleiner</strong>', 'Größer'],
    ['Preis', '150 – 500 €', '<strong>50 – 180 €</strong>'],
    ['Wenn nass', 'Isoliert praktisch nicht mehr', '<strong>Wärmt weiter</strong>'],
    ['Trocknungszeit', 'Sehr lang', '<strong>Kurz</strong>'],
    ['Pflege', 'Aufwendig, Spezialwaschmittel', '<strong>Waschmaschine</strong>'],
    ['Lebensdauer', '<strong>10 – 15 Jahre</strong>', '5 – 8 Jahre'],
  ],
  note: 'Für Bikepacking in Mitteleuropa: Daune, wenn du sorgfältig packst und Wert auf Packmaß legst. Kunstfaser, wenn du oft bei Regen fährst oder günstig einsteigen willst.',
})}

${callout(
  'Der Quilt – die dritte Option',
  '<p>Ein Quilt ist ein Schlafsack ohne Rücken und ohne Reißverschluss: Die Isolation unter dir wird ohnehin plattgedrückt, also lässt man sie weg. Das spart 150 bis 300 Gramm und Packmaß. Der Quilt wird mit Riemen an der Isomatte fixiert. Nachteil: Bei Seitenschläfern zieht es leicht an den Rändern, und unter etwa 5 Grad Komforttemperatur wird es fummelig. Für Sommertouren eine sehr gute Wahl.</p>',
  'tip'
)}

${affNotice()}

${h2('Kaufempfehlungen', 'empfehlungen')}
${pickGrid(
  [
    {
      badge: 'Zuerst kaufen',
      title: 'Isomatte, R-Wert 3,0 – 4,0',
      forWhom: 'Deckt die komplette deutsche Radsaison von April bis Oktober ab.',
      price: 'ca. 90 – 190 €',
      specs: [
        { k: 'R-Wert', v: '3,0 – 4,0' },
        { k: 'Gewicht', v: '450 – 700 g' },
        { k: 'Packmaß', v: 'etwa wie eine 1-Liter-Flasche' },
        { k: 'Zubehör', v: 'Pumpsack und Reparaturset' },
      ],
      pros: [
        'Löst das Kälteproblem an der richtigen Stelle',
        'Ein R-Wert um 3,5 deckt Frühjahr bis Herbst ab',
        'Kleines Packmaß – passt in die Satteltasche',
      ],
      cons: [
        'Luftmatten können ein Loch bekommen – Reparaturset ist Pflicht',
        'Manche Modelle knistern hörbar',
      ],
      partner: 'amazon',
      url: shops.isomatte,
      ctaLabel: 'Isomatten ansehen',
    },
    {
      badge: 'Der Allrounder',
      title: 'Daunenschlafsack, Komfort ca. +5 °C',
      forWhom: 'Mai bis September in Deutschland, mit etwas Puffer.',
      price: 'ca. 150 – 350 €',
      specs: [
        { k: 'Komforttemperatur', v: '+3 bis +7 °C' },
        { k: 'Gewicht', v: '700 – 950 g' },
        { k: 'Füllkraft', v: '650 cuin genügt, 800 ist Luxus' },
        { k: 'Packmaß', v: 'ca. 4 – 6 Liter im Kompressionssack' },
      ],
      pros: [
        'Deckt die Hauptsaison komfortabel ab',
        'Kleines Packmaß – passt in die Lenkerrolle',
        'Hält bei guter Pflege 10 bis 15 Jahre',
      ],
      cons: [
        'Nass praktisch wirkungslos – Packsack zwingend',
        'Teurer als Kunstfaser',
      ],
      partner: 'amazon',
      url: shops.schlafsack3jz,
      ctaLabel: 'Schlafsäcke ansehen',
    },
    {
      badge: 'Günstig und robust',
      title: 'Kunstfaser-Schlafsack, Komfort ca. +8 °C',
      forWhom: 'Sommertouren, regenreiche Regionen, günstiger Einstieg.',
      price: 'ca. 50 – 130 €',
      specs: [
        { k: 'Komforttemperatur', v: '+7 bis +10 °C' },
        { k: 'Gewicht', v: '900 – 1.400 g' },
        { k: 'Packmaß', v: 'ca. 7 – 10 Liter' },
        { k: 'Pflege', v: 'Waschmaschine, 30 °C' },
      ],
      pros: [
        'Wärmt auch feucht noch – das entscheidende Argument bei Regen',
        'Trocknet schnell, unkompliziert in der Pflege',
        'Ein Drittel des Preises eines vergleichbaren Daunensacks',
      ],
      cons: [
        'Deutlich schwerer und größer im Packmaß',
        'Kürzere Lebensdauer, verliert Bauschkraft',
      ],
      partner: 'amazon',
      url: shops.schlafsackSommer,
      ctaLabel: 'Schlafsäcke ansehen',
      note: 'Für die ersten Touren völlig ausreichend. Wer merkt, dass er dabeibleibt, kauft später einen Daunensack – und behält den Kunstfasersack für nasse Touren.',
    },
  ],
  { columns: 3 }
)}

${h2('Sieben Tricks für eine wärmere Nacht', 'tricks')}
${checklist([
  '<strong>Vor dem Schlafen essen.</strong> Verdauung erzeugt Wärme. Ein Riegel im Zelt ist keine Schwäche, sondern Heiztechnik.',
  '<strong>Trockene Kleidung anziehen.</strong> Auch leicht feuchte Radkleidung kühlt die ganze Nacht aus.',
  '<strong>Mütze und Socken.</strong> Über den Kopf verlierst du spürbar Wärme, kalte Füße halten dich wach.',
  '<strong>Vor dem Schlafen pinkeln.</strong> Der Körper hält Urin auf Körpertemperatur – das kostet Energie.',
  '<strong>Warme Flasche in den Schlafsack.</strong> Eine Nalgene mit heißem Wasser hält vier bis fünf Stunden.',
  '<strong>Kleidung unter die Beine legen.</strong> Verlängert die Isolation nach unten, wo die Matte oft zu kurz ist.',
  '<strong>Schlafsack früh ausschütteln.</strong> Daune braucht 20 bis 30 Minuten, um ihre volle Bauschkraft zu erreichen.',
])}

${h2('Pflege und Lagerung', 'pflege')}
${table({
  head: ['Was', 'Wie', 'Warum'],
  rows: [
    ['Lagerung zu Hause', 'Locker im großen Lagersack oder hängend', 'Dauerhafte Kompression zerstört die Bauschkraft'],
    ['Nach jeder Tour', 'Einen Tag auslüften, nicht in der Sonne', 'Feuchtigkeit und Körperfett schaden der Füllung'],
    ['Waschen Daune', '1 – 2 × pro Saison, Spezialwaschmittel, Trockner mit Tennisbällen', 'Fett von der Haut verklebt die Daunen'],
    ['Waschen Kunstfaser', 'Bei Bedarf, 30 °C Feinwäsche', 'Unkompliziert'],
    ['Isomatte lagern', 'Ventil offen, leicht aufgeblasen', 'Verhindert Schimmel und Materialkleben'],
    ['Inlett verwenden', 'Seidenschlafsack, 100 – 150 g', 'Reduziert Waschintervalle, bringt 2 – 3 Grad'],
  ],
})}
`;

module.exports = article({
  href: '/ausruestung/schlafsack-isomatte.html',
  kicker: 'Ausrüstung · Schlafen',
  title: 'Schlafsack & Isomatte',
  metaTitle: 'Bikepacking Schlafsack & Isomatte: R-Wert, Komforttemperatur, Kaufberatung | Sattelfest',
  description:
    'Warum die Isomatte über die kalte Nacht entscheidet, nicht der Schlafsack: R-Werte erklärt, Komforttemperatur richtig lesen, Daune oder Kunstfaser, Quilts – plus sieben Tricks für eine wärmere Nacht.',
  lead:
    'Der häufigste Grund für eine kalte Nacht ist nicht der Schlafsack, sondern die Matte. Unter deinem Gewicht isoliert die Füllung nicht mehr.',
  meta: [
    { icon: 'tent', text: '11 Minuten Lesezeit' },
    { icon: 'sun', text: 'Mit Saison-Tabelle' },
    { icon: 'check', text: 'R-Werte erklärt' },
  ],
  toc: [
    { label: 'Die Isomatte entscheidet über die Nacht', id: 'isomatte' },
    { label: 'Der Schlafsack', id: 'schlafsack' },
    { label: 'Kaufempfehlungen', id: 'empfehlungen' },
    { label: 'Sieben Tricks für eine wärmere Nacht', id: 'tricks' },
    { label: 'Pflege und Lagerung', id: 'pflege' },
  ],
  content,
  faq: [
    {
      q: 'Welchen R-Wert braucht meine Isomatte?',
      a: '<p>Für den Hochsommer reicht R 2,0 bis 3,0, für Frühjahr und Herbst – also die eigentliche deutsche Radsaison – solltest du R 3,0 bis 4,0 wählen. Ab R 4,0 sind auch Nächte um den Gefrierpunkt machbar. R-Werte lassen sich addieren: Eine dünne Schaumstoffmatte unter der Luftmatte ist die günstigste Art aufzurüsten.</p>',
    },
    {
      q: 'Warum friere ich trotz gutem Schlafsack?',
      a: '<p>Weil unter deinem Körpergewicht die Isolation des Schlafsacks vollständig plattgedrückt wird – nach unten wärmt allein die Isomatte. Ein Schlafsack mit 5 Grad Komforttemperatur auf einer Sommermatte mit R 1,5 lässt dich im April frieren, obwohl beide Teile für sich in Ordnung sind.</p>',
    },
    {
      q: 'Welche Temperaturangabe beim Schlafsack ist relevant?',
      a: '<p>Die Komforttemperatur. Sie beschreibt, bei welcher Temperatur eine Frau mit normalem Kälteempfinden entspannt schläft. Die Limit-Temperatur bedeutet „gerade so, zusammengerollt“, die Extremtemperatur ist ein Überlebens- und kein Schlafwert. Rechne auf die Komforttemperatur noch 5 Grad Puffer, wenn du schnell frierst 8 bis 10.</p>',
    },
    {
      q: 'Daune oder Kunstfaser beim Bikepacking?',
      a: '<p>Daune ist 30 bis 40 Prozent leichter, packt kleiner und hält 10 bis 15 Jahre – wird aber nass praktisch wirkungslos und ist teuer. Kunstfaser wärmt auch feucht, trocknet schnell, kostet ein Drittel und ist schwerer. Für Mitteleuropa mit sorgfältiger Packstrategie: Daune. Für regenreiche Regionen oder den günstigen Einstieg: Kunstfaser.</p>',
    },
    {
      q: 'Was ist ein Quilt und lohnt er sich?',
      a: '<p>Ein Quilt ist ein Schlafsack ohne Rücken und Reißverschluss – die Isolation unter dir wird ohnehin plattgedrückt, also lässt man sie weg. Das spart 150 bis 300 Gramm und Packmaß. Für Sommertouren eine sehr gute Wahl. Unter etwa 5 Grad Komforttemperatur wird es fummelig, und Seitenschläfer spüren an den Rändern Zug.</p>',
    },
    {
      q: 'Wie lagere ich Schlafsack und Isomatte richtig?',
      a: '<p>Den Schlafsack niemals dauerhaft im Kompressionssack lassen – das zerstört die Bauschkraft. Locker im großen Lagersack oder hängend aufbewahren. Die Isomatte mit offenem Ventil und leicht aufgeblasen lagern, damit sich kein Schimmel bildet und die Innenflächen nicht verkleben.</p>',
    },
  ],
  related: [
    { href: '/ausruestung/schlafsystem.html', label: 'Zelt, Tarp oder Biwaksack?' },
    { href: '/routen/saison-wetter.html', label: 'Saison, Wetter & Jahreszeit' },
    { href: '/taschen/wasserdicht-packen.html', label: 'Wasserdicht packen' },
    { href: '/ausruestung/kleidung.html', label: 'Kleidung: Das Zwiebelprinzip' },
  ],
});
