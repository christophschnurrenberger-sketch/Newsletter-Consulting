'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, pickGrid, affNotice,
} = require('../../components');
const { shops } = require('../../data/shops');

const content = `
<p class="lead-p">
  Die Lenkerrolle löst das Volumenproblem: Ein Schlafsack wiegt kaum etwas, braucht aber acht Liter
  Platz. Genau dafür ist sie da. Gleichzeitig ist der Lenker die empfindlichste Stelle am ganzen Rad –
  hier entscheidet jedes Kilo darüber, ob sich das Rad noch gut fährt.
</p>

${stats([
  { value: '3–4 kg', label: 'Absolute Grenze', note: 'Darüber wird die Lenkung schwammig und träge.' },
  { value: '10–14 l', label: 'Sinnvolles Volumen', note: 'Nimmt ein komplettes Schlafsystem auf.' },
  { value: '38 cm', label: 'Typische Engstelle', note: 'Freier Raum zwischen den Griffen am Rennlenker.' },
])}

${h2('Warum die Gewichtsgrenze so streng ist', 'gewicht')}
<p>
  Alles, was am Lenker hängt, sitzt weit vor der Lenkachse und weit oben. Beides vergrößert das
  Trägheitsmoment: Das Rad lenkt träger ein, es kippt in langsamen Kurven zur Seite, und in schnellen
  Abfahrten kann es zu einem Aufschaukeln des Lenkers kommen – dem berüchtigten „Speed Wobble“, den
  Bikepacker fast immer mit einem überladenen Lenker erkaufen.
</p>
<p>
  Die Grenze von drei bis vier Kilo ist kein Herstellerhinweis aus Vorsicht, sondern eine gut
  begründete Praxisregel. Wer sie einhält, merkt vom Lenkergepäck fahrtechnisch fast nichts.
</p>

${callout(
  'Der Test, der 30 Sekunden dauert',
  '<p>Pack die Rolle, stell dich neben das Rad und heb es an der Lenkermitte hoch. Wenn das Vorderrad spürbar nach unten kippt statt zu balancieren, ist zu viel drin. Fahre danach eine langsame Acht auf dem Parkplatz: Wenn das Rad in die Kurve „hineinfällt“ statt sich führen zu lassen, gehört Gewicht nach hinten.</p>',
  'tip'
)}

${h2('Die drei Bauarten am Lenker', 'bauarten')}
${table({
  head: ['Bauart', 'Prinzip', 'Stärke', 'Schwäche'],
  rows: [
    [
      'Feste Rolle',
      'Ein Packsack wird direkt an den Lenker geschnallt',
      'Leicht (250 – 400 g), günstig, hohes Volumen',
      'Zum Packen musst du sie jedes Mal abnehmen',
    ],
    [
      'Harness mit Packsack',
      'Halterung bleibt am Lenker, Sack wird eingespannt',
      'Sack abends mit ins Zelt, Packsäcke tauschbar',
      'Schwerer (450 – 650 g), etwas teurer',
    ],
    [
      'Lenkerkasten / Bar Bag',
      'Formstabile Tasche mit Deckel oder Reißverschluss',
      'Im Fahren zugänglich, ideal für Kamera und Snacks',
      'Weniger Volumen, oft nur 3 – 8 Liter',
    ],
  ],
  note: 'Viele fahren die Kombination: Rolle unten für das Schlafsystem, kleiner Kasten oben drauf für alles, was tagsüber gebraucht wird.',
})}

${h2('Was in die Lenkerrolle gehört', 'inhalt')}
${checklist([
  '<strong>Schlafsack</strong> – der klassische Inhalt: leicht, voluminös, wird erst abends gebraucht',
  '<strong>Innenzelt oder Tarp</strong> – ebenfalls leicht und sperrig',
  '<strong>Isolationsjacke</strong> – Daune komprimiert stark und füllt Ecken',
  '<strong>Isomatte</strong>, wenn sie nicht in die Satteltasche passt',
  '<strong>Wechselkleidung</strong>, wenn hinten kein Platz mehr ist',
])}
${checklist(
  [
    '<strong>Werkzeug</strong> – zu schwer, gehört in die Rahmentasche',
    '<strong>Wasser</strong> – 1,5 Liter am Lenker sind 1,5 Kilo am falschen Ort',
    '<strong>Gaskartusche und Kocher</strong> – schwer und hart, gehört nach unten',
    '<strong>Powerbank und Elektronik</strong> – hier stärkster Erschütterung ausgesetzt',
    '<strong>Zeltstangen</strong> – besser an die Gabel oder ins Rahmendreieck',
  ],
  { tone: 'dont' }
)}

${h2('Die Maße, die du vorher prüfen musst', 'masse')}
${h3('Freier Raum am Lenker', 'freiraum')}
<p>
  Eine Rolle braucht zwischen den Bremsgriffen bzw. Handgriffen einen durchgehenden freien Bereich.
  Gemessen wird von Innenkante zu Innenkante:
</p>
${table({
  head: ['Lenkertyp', 'Typisch verfügbar', 'Passende Rollenlänge'],
  rows: [
    ['Rennlenker 40 cm', '34 – 38 cm', 'Kompakte Rolle, oft „S“ oder 8 – 10 l'],
    ['Rennlenker 44 cm mit Flare', '38 – 42 cm', 'Standardrolle, 10 – 13 l'],
    ['Flatbar MTB 74 cm', '52 – 60 cm', 'Jede Rolle, auch 15 l und mehr'],
    ['Trekkinglenker gebogen', '40 – 50 cm', 'Standardrolle, auf Griffwinkel achten'],
  ],
  note: 'Eine zu lange Rolle kollidiert mit den Bremshebeln und drückt die Züge ab – das ist nicht nur unbequem, sondern beeinträchtigt die Bremse.',
})}

${h3('Abstand zum Reifen und zu den Zügen', 'abstand')}
${checklist([
  '<strong>Zum Vorderreifen:</strong> mindestens 4 cm, mehr bei Federgabel (Einfederweg beachten)',
  '<strong>Zu den Zügen:</strong> Die Rolle darf Brems- und Schaltzüge nicht abknicken. Hier helfen Spacer.',
  '<strong>Zum Steuerrohr:</strong> Bei kurzem Vorbau kann die Rolle am Rahmen scheuern',
  '<strong>Zu den Händen:</strong> In der Unterlenkerhaltung darf die Rolle die Finger nicht berühren',
])}

${callout(
  'Spacer sind fast immer nötig',
  '<p>Bei Rennlenkern führen die Züge außen am Lenker entlang. Ohne Distanzhalter drückt die Rolle sie ab – die Schaltung wird ungenau, die Bremse fühlt sich stumpf an. <strong>Lenker-Spacer kosten 15 bis 30 Euro</strong> und lösen das Problem vollständig. Plane sie beim Kauf gleich mit ein; viele Hersteller liefern sie nicht mit.</p>',
  'warn'
)}

${h2('Richtig packen', 'packen')}
<p>
  Eine Lenkerrolle wird von der Mitte nach außen gepackt. Die Rolle ist an den Enden schmaler und
  wird dort von den Riemen zusammengedrückt – dort passt nur Weiches hin.
</p>
${table({
  head: ['Bereich', 'Inhalt', 'Warum'],
  rows: [
    ['Mitte', 'Das Sperrigste: Schlafsack oder Innenzelt', 'Meister Platz, geringster Hebel zur Lenkachse'],
    ['Enden', 'Socken, Mütze, Handschuhe, Weiches', 'Die Enden werden von den Riemen komprimiert'],
    ['Außen an den Riemen', 'Nur Leichtes: Jacke, Handtuch zum Trocknen', 'Verlustrisiko und zusätzlicher Hebel'],
  ],
  note: 'Wenn du beide Enden fest packst, behält die Rolle ihre Form und rutscht nicht in den Riemen.',
})}

${affNotice()}

${h2('Kaufempfehlungen', 'empfehlungen')}
${pickGrid(
  [
    {
      badge: 'Der Klassiker',
      title: 'Feste Rolle, 10 – 13 Liter',
      forWhom: 'Einstieg, klare Aufgabe: das Schlafsystem transportieren.',
      price: 'ca. 70 – 130 €',
      specs: [
        { k: 'Volumen', v: '10 – 13 l' },
        { k: 'Leergewicht', v: '250 – 400 g' },
        { k: 'Verschluss', v: 'Beidseitiger Rollverschluss' },
        { k: 'Zubehör', v: 'Spacer meist separat, 15 – 30 €' },
      ],
      pros: [
        'Bestes Verhältnis von Volumen zu Gewicht',
        'Passt an fast jeden Lenker',
        'Beidseitig zu öffnen – Zugriff ohne komplettes Auspacken',
      ],
      cons: [
        'Zum Packen abnehmen nötig',
        'Ohne Spacer drückt sie oft die Züge ab',
      ],
      partner: 'amazon',
      url: shops.lenkerrolle,
      ctaLabel: 'Lenkerrollen ansehen',
    },
    {
      badge: 'Am praktischsten',
      title: 'Harness mit separatem Dry Bag',
      forWhom: 'Wer regelmäßig fährt und morgens schnell startklar sein will.',
      price: 'ca. 110 – 190 €',
      specs: [
        { k: 'Volumen', v: '8 – 15 l, je nach Packsack' },
        { k: 'Leergewicht', v: '450 – 650 g' },
        { k: 'Aufbau', v: 'Halterung plus austauschbarer Packsack' },
        { k: 'Vorteil', v: 'Packsack abends mit ins Zelt' },
      ],
      pros: [
        'Sack lässt sich bequem im Sitzen packen',
        'Packsackgröße nach Tour wechselbar',
        'Halterung schafft von Haus aus Abstand zu den Zügen',
      ],
      cons: [
        'Schwerer als eine feste Rolle',
        'Zwei Teile, die zusammenpassen müssen',
      ],
      partner: 'bikecomponents',
      url: 'https://www.bike-components.de/de/Taschen-Koerbe/Lenkertaschen/',
      ctaLabel: 'Systeme ansehen',
    },
    {
      badge: 'Ergänzung, kein Ersatz',
      title: 'Lenkerkasten, 3 – 6 Liter',
      forWhom: 'Kamera, Snacks, Regenjacke – alles, was du im Fahren brauchst.',
      price: 'ca. 55 – 120 €',
      specs: [
        { k: 'Volumen', v: '3 – 6 l' },
        { k: 'Leergewicht', v: '250 – 450 g' },
        { k: 'Zugriff', v: 'Von oben, oft einhändig' },
        { k: 'Montage', v: 'Riemen oder Schnellverschluss' },
      ],
      pros: [
        'Der einzige große Stauraum, an den du im Fahren kommst',
        'Formstabil – schützt Kamera und Brille',
        'Lässt sich mit einer Rolle darunter kombinieren',
      ],
      cons: [
        'Wenig Volumen im Verhältnis zum Gewicht',
        'Zusammen mit einer Rolle schnell über der Gewichtsgrenze',
      ],
      partner: 'amazon',
      url: shops.lenkertascheKlein,
      ctaLabel: 'Lenkertaschen ansehen',
      note: 'Wenn du dich zwischen Rolle und Kasten entscheiden musst: Die Rolle löst das Volumenproblem, der Kasten löst ein Komfortproblem.',
    },
  ],
  { columns: 3 }
)}

${h2('Häufige Probleme', 'probleme')}
${table({
  head: ['Problem', 'Ursache', 'Lösung'],
  rows: [
    ['Lenkung fühlt sich schwammig an', 'Zu viel Gewicht vorn', 'Auf 3 kg reduzieren, Schweres nach hinten'],
    ['Schaltung schaltet ungenau', 'Rolle drückt die Züge ab', 'Spacer montieren, Rolle etwas tiefer hängen'],
    ['Rolle rutscht nach unten', 'Riemen zu locker oder Enden nicht gefüllt', 'Enden fest packen, Riemen nach 10 km nachziehen'],
    ['Rolle scheuert am Reifen', 'Zu tief oder zu großer Durchmesser', 'Höher hängen, kleineres Volumen wählen'],
    ['Hände werden taub', 'Griffposition durch Rolle eingeschränkt', 'Rolle weiter nach vorn setzen, Lenkeraufsatz prüfen'],
    ['Rolle nass innen', 'Rollverschluss zu wenig gerollt', 'Dreimal einrollen, Schlafsack zusätzlich in Packsack'],
  ],
})}
`;

module.exports = article({
  href: '/taschen/lenkerrolle.html',
  kicker: 'Taschen · Lenker',
  title: 'Lenkerrolle & Lenkertasche',
  metaTitle: 'Bikepacking-Lenkerrolle: Volumen, Gewichtsgrenze & Kaufberatung | Sattelfest',
  description:
    'Die Bikepacking-Lenkerrolle richtig wählen und packen: Warum am Lenker maximal 3 bis 4 Kilo hängen dürfen, welche Bauarten es gibt, welche Maße du prüfen musst und warum Spacer fast immer nötig sind.',
  lead:
    'Sie löst das Volumenproblem – und ist gleichzeitig die empfindlichste Stelle am Rad. Hier entscheidet jedes Kilo über das Fahrverhalten.',
  meta: [
    { icon: 'bag', text: '9 Minuten Lesezeit' },
    { icon: 'weight', text: 'Mit Gewichtsgrenzen' },
    { icon: 'tool', text: 'Maße zum Nachmessen' },
  ],
  toc: [
    { label: 'Warum die Gewichtsgrenze so streng ist', id: 'gewicht' },
    { label: 'Die drei Bauarten am Lenker', id: 'bauarten' },
    { label: 'Was in die Lenkerrolle gehört', id: 'inhalt' },
    { label: 'Die Maße, die du prüfen musst', id: 'masse' },
    { label: 'Richtig packen', id: 'packen' },
    { label: 'Kaufempfehlungen', id: 'empfehlungen' },
    { label: 'Häufige Probleme', id: 'probleme' },
  ],
  content,
  faq: [
    {
      q: 'Wie viel Gewicht darf an den Lenker?',
      a: '<p>3 bis 4 Kilogramm sind die praktische Obergrenze. Alles am Lenker sitzt weit vor der Lenkachse und weit oben, vergrößert damit das Trägheitsmoment und macht die Lenkung träge. Über 4 Kilo steigt zudem das Risiko, dass der Lenker in schnellen Abfahrten zu flattern beginnt.</p>',
    },
    {
      q: 'Welches Volumen sollte eine Lenkerrolle haben?',
      a: '<p>10 bis 14 Liter sind der Zielbereich – das nimmt ein komplettes Schlafsystem auf. Am Rennlenker begrenzt oft der freie Raum zwischen den Bremsgriffen: Bei einem 40-cm-Lenker stehen meist nur 34 bis 38 Zentimeter zur Verfügung, dann passt nur eine kompakte Rolle mit 8 bis 10 Litern.</p>',
    },
    {
      q: 'Brauche ich Spacer für die Lenkerrolle?',
      a: '<p>Am Rennlenker fast immer. Ohne Distanzhalter drückt die Rolle die außen verlaufenden Brems- und Schaltzüge ab – die Schaltung wird ungenau und die Bremse fühlt sich stumpf an. Spacer kosten 15 bis 30 Euro, werden aber von vielen Herstellern nicht mitgeliefert. Plane sie beim Kauf gleich mit ein.</p>',
    },
    {
      q: 'Was gehört in die Lenkerrolle?',
      a: '<p>Leichtes und Voluminöses: Schlafsack, Innenzelt oder Tarp, Isolationsjacke, notfalls die Isomatte. Nicht hinein gehören Werkzeug, Wasser, Kocher, Gaskartusche und Elektronik – das ist entweder zu schwer für die Position oder wird dort zu stark durchgeschüttelt.</p>',
    },
    {
      q: 'Warum rutscht meine Lenkerrolle nach unten?',
      a: '<p>Meist, weil die Enden der Rolle nicht fest gepackt sind. Eine Rolle mit weichen, halb leeren Enden verliert unter den Kompressionsriemen ihre Form und wandert. Pack beide Enden fest aus (Socken, Mütze, Handschuhe eignen sich gut) und zieh die Riemen nach den ersten zehn Kilometern noch einmal nach.</p>',
    },
  ],
  related: [
    { href: '/taschen/satteltasche.html', label: 'Satteltasche (Seatpack)' },
    { href: '/taschen/rahmentasche.html', label: 'Rahmentasche' },
    { href: '/taschen/richtig-packen.html', label: 'Richtig packen: Gewichtsverteilung' },
    { href: '/ausruestung/schlafsystem.html', label: 'Zelt, Tarp oder Biwaksack?' },
  ],
});
