'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, pickGrid, affNotice,
} = require('../../components');
const { shops } = require('../../data/shops');

const content = `
<p class="lead-p">
  Die kleinen Taschen kosten zusammen weniger als eine Satteltasche und verändern den Tourtag stärker
  als jede große Tasche. Der Grund ist banal: Sie sind die einzigen, an die du im Fahren kommst.
  Wer sie weglässt, hält alle 40 Minuten für einen Riegel an.
</p>

${stats([
  { value: '25–55 €', label: 'Oberrohrtasche', note: 'Die günstigste Tasche mit dem größten Alltagsgewinn.' },
  { value: '2 × 0,75 l', label: 'Stem Bags', note: 'Nehmen eine Flasche oder eine große Handvoll Snacks auf.' },
  { value: '2 × 3–5 l', label: 'Gabeltaschen', note: 'Für Wasser, Zeltstangen, Schuhe und Nasses.' },
])}

${h2('Die Oberrohrtasche', 'oberrohr')}
<p>
  Sie sitzt direkt hinter dem Vorbau auf dem Oberrohr und ist im Fahren mit einer Hand erreichbar.
  Was hineinkommt: Handy, Riegel, Sonnencreme, Lippenpflege, Geldbeutel, Kopfhörer. Also genau das,
  was du sonst zehnmal am Tag aus einer großen Tasche kramst.
</p>

${table({
  head: ['Bauart', 'Befestigung', 'Vorteil', 'Nachteil'],
  rows: [
    ['Klettmontage', 'Zwei Klettbänder um Ober- und Steuerrohr', 'Passt an jedes Rad, schnell versetzbar', 'Kann bei starkem Wiegetritt wandern'],
    ['Schraubmontage', 'Zwei Gewinde auf dem Oberrohr', 'Sitzt bombenfest, wackelt nie', 'Nur bei Rahmen mit Bohrungen'],
    ['Hinter dem Sattel', 'Klett am Oberrohr, vor der Sattelstütze', 'Stört nicht beim Wiegetritt', 'Im Fahren schlechter erreichbar'],
  ],
  note: 'Achte auf die Breite: Eine zu breite Tasche scheuert bei Rennlenker-Fahrern an den Innenseiten der Oberschenkel.',
})}

${callout(
  'Der Kniescheuertest',
  '<p>Bevor du eine Oberrohrtasche kaufst, setz dich aufs Rad und tritt eine Minute lang kräftig im Sitzen und im Stehen. Wenn deine Knie oder Oberschenkel den Bereich hinter dem Vorbau berühren, brauchst du ein besonders schmales Modell (unter 6 cm) oder die Variante hinter dem Sattel. Eine Tasche, die bei jedem Tritt an der Innenseite des Knies reibt, ist nach 50 Kilometern unerträglich.</p>',
  'warn'
)}

${h2('Stem Bags (Feed Bags)', 'stem-bags')}
<p>
  Zylindrische Taschen, die neben dem Vorbau am Lenker und am Steuerrohr befestigt werden. Sie sind
  offen nach oben, mit Kordelzug oder Klettdeckel verschließbar, und nehmen eine 0,75-Liter-Flasche,
  eine Kaffeetasse, eine Banane oder eine Handvoll Riegel auf.
</p>
<p>
  Der unterschätzte Nutzen: Sie lösen das Trinkproblem an Rädern, deren Rahmendreieck von einer
  vollen Rahmentasche belegt ist. Und sie sind die einzige Stelle am Rad, an der du im Fahren
  <em>hineingreifen</em> kannst, ohne einen Verschluss zu öffnen.
</p>

${checklist([
  '<strong>Zwei Stück sind besser als eins</strong> – links Wasser, rechts Snacks, und die Gewichtsverteilung bleibt symmetrisch',
  '<strong>Auf den Deckel achten:</strong> Ohne Verschluss fliegt bei einer ruppigen Abfahrt der Inhalt heraus',
  '<strong>Nicht zu schwer beladen:</strong> Zwei volle Flaschen sind 1,5 kg am Lenker – das zählt zur Lenker-Gewichtsgrenze',
  '<strong>Bei Rennlenker prüfen:</strong> Die Tasche darf die Bremshebel nicht blockieren',
])}

${h2('Gabeltaschen und Anything Cages', 'gabel')}
<p>
  An den Gabelholmen lässt sich Gewicht sehr tief und weit vorn unterbringen – fahrdynamisch nach dem
  Rahmendreieck die zweitbeste Position. Voraussetzung sind drei Gewindebohrungen pro Holm. Die haben
  fast alle Gravelbikes mit Starrgabel und viele Tourenräder, aber praktisch keine Federgabel.
</p>

${table({
  head: ['System', 'Aufbau', 'Kosten', 'Kapazität je Seite'],
  rows: [
    ['Anything Cage plus Packsack', 'Halter aus Alu oder Kunststoff, Sack separat', '25 – 45 € + 20 – 40 €', '3 – 5 l'],
    ['Fertige Gabeltasche', 'Tasche mit integrierter Befestigung', '55 – 110 €', '3 – 5 l'],
    ['Klemmadapter für Federgabel', 'Schellen um die Standrohre', '30 – 60 €', '2 – 3 l'],
    ['Flaschenhalter groß', 'Für 1-Liter-Flaschen', '10 – 25 €', '1 l'],
  ],
  note: 'Die Kombination aus Anything Cage und separatem Dry Bag ist meist günstiger und flexibler als eine fertige Gabeltasche.',
})}

${h3('Was an die Gabel gehört', 'gabel-inhalt')}
${checklist([
  '<strong>Wasser</strong> – die beste Nutzung überhaupt: schwer, tief platziert, symmetrisch',
  '<strong>Zeltstangen</strong> – lang und sperrig, an der Gabel stören sie nirgends',
  '<strong>Schuhe für abends</strong> – dreckig und sperrig, gehören nicht zur sauberen Kleidung',
  '<strong>Nasses Zeltaußenzelt</strong> – trocknet unterwegs im Fahrtwind',
  '<strong>Reserveverpflegung</strong> – Konserven, Nudeln, alles Schwere',
])}

${callout(
  'Die Gewichtsgrenze an der Gabel',
  '<p>Die meisten Gabeln sind für <strong>zwei bis drei Kilo je Holm</strong> freigegeben – prüfe das beim Hersteller, es steht oft im Handbuch. Carbongabeln haben teils niedrigere Grenzen. Wichtig ist außerdem die symmetrische Beladung: Ein Kilo Unterschied zwischen links und rechts spürst du in der Lenkung deutlich.</p>',
  'warn'
)}

${affNotice()}

${h2('Kaufempfehlungen', 'empfehlungen')}
${pickGrid(
  [
    {
      badge: 'Größter Alltagsgewinn',
      title: 'Oberrohrtasche, 0,7 – 1 Liter',
      forWhom: 'Jeder. Ausnahmslos. Die erste Ergänzung nach den großen Taschen.',
      price: 'ca. 25 – 55 €',
      specs: [
        { k: 'Volumen', v: '0,7 – 1 l' },
        { k: 'Leergewicht', v: '80 – 160 g' },
        { k: 'Breite', v: 'unter 6,5 cm wegen Kniefreiheit' },
        { k: 'Verschluss', v: 'Reißverschluss, wasserabweisend' },
      ],
      pros: [
        'Handy und Riegel im Fahren erreichbar',
        'Günstigste Tasche mit spürbarem Effekt auf den Tourtag',
        'Passt per Klett an praktisch jedes Rad',
      ],
      cons: ['Bei zu breitem Modell scheuern die Knie'],
      partner: 'amazon',
      url: shops.oberrohrtasche,
      ctaLabel: 'Oberrohrtaschen ansehen',
    },
    {
      badge: 'Löst das Wasserproblem',
      title: 'Stem Bag, Paar',
      forWhom: 'Volle Rahmentasche, warmes Wetter, lange Etappen ohne Versorgung.',
      price: 'ca. 40 – 90 € je Paar',
      specs: [
        { k: 'Volumen', v: '0,7 – 1,2 l je Stück' },
        { k: 'Leergewicht', v: '60 – 120 g je Stück' },
        { k: 'Verschluss', v: 'Kordelzug oder Klettdeckel' },
        { k: 'Befestigung', v: 'Lenker plus Steuerrohr' },
      ],
      pros: [
        'Trinken ohne Anhalten und ohne Verschluss zu öffnen',
        'Ersetzt den Flaschenhalter im belegten Rahmendreieck',
        'Zwei Stück verteilen das Gewicht symmetrisch',
      ],
      cons: [
        'Gewicht sitzt am Lenker – zählt zur 4-Kilo-Grenze',
        'Ohne Deckel fliegt bei Abfahrten der Inhalt heraus',
      ],
      partner: 'amazon',
      url: shops.stemtasche,
      ctaLabel: 'Stem Bags ansehen',
    },
    {
      badge: 'Wenn die Gabel Gewinde hat',
      title: 'Anything Cage plus Dry Bag',
      forWhom: 'Längere Touren, viel Wasser, sperrige Ausrüstung.',
      price: 'ca. 50 – 90 € je Seite',
      specs: [
        { k: 'Kapazität', v: '3 – 5 l je Seite' },
        { k: 'Zuladung', v: '2 – 3 kg je Holm (Herstellerangabe prüfen)' },
        { k: 'Voraussetzung', v: '3 Gewinde je Gabelholm' },
        { k: 'Gewicht Halter', v: '130 – 220 g je Stück' },
      ],
      pros: [
        'Zweitbeste Gewichtsposition am Rad nach dem Rahmendreieck',
        'Packsack frei wählbar und einzeln ersetzbar',
        'Ideal für Wasser, Zeltstangen und alles Dreckige',
      ],
      cons: [
        'Nur mit Gewinden an der Gabel – Federgabeln fallen aus',
        'Unsymmetrische Beladung ist in der Lenkung deutlich spürbar',
      ],
      partner: 'amazon',
      url: shops.anythingCage,
      ctaLabel: 'Gabelhalter ansehen',
    },
  ],
  { columns: 3 }
)}

${h2('Was du sonst noch am Rad unterbringen kannst', 'sonstiges')}
${table({
  head: ['Ort', 'Was', 'Bewertung'],
  rows: [
    ['Unter dem Unterrohr', 'Flasche, Werkzeugkapsel', 'Gut – wird aber schmutzig'],
    ['Unter dem Sattel (klein)', 'Schlauch, Reifenheber, Multitool', 'Sinnvoll, wenn keine Satteltasche montiert ist'],
    ['Am Sattelrohr', 'Minipumpe, Schloss', 'Praktisch, wenn kein Flaschenhalter dort ist'],
    ['Trikottaschen', 'Handy, Riegel, Regenjacke', 'Auf Tour eher unbequem – Rücken schwitzt'],
    ['Hüfttasche', 'Wertsachen, Ausweis, Geld', '<strong>Sehr empfehlenswert:</strong> bleibt bei dir, wenn du das Rad abstellst'],
    ['Rucksack', 'Alles Weitere', 'Möglichst vermeiden – belastet Rücken und Sitzfläche'],
  ],
  note: 'Eine kleine Hüfttasche mit Ausweis, Karte und Bargeld ist eine der besten Gewohnheiten überhaupt: Sie nimmst du mit, wenn du im Supermarkt bist oder aufs Klo gehst.',
})}

${callout(
  'Warum Rucksäcke auf Tour selten funktionieren',
  '<p>Ein Rucksack verlagert Gewicht auf Wirbelsäule und Sitzbeinhöcker – also genau dorthin, wo nach vier Stunden im Sattel ohnehin das Problem liegt. Dazu kommt der schwitzende Rücken. Ausnahme: eine kleine Trinkblasenweste unter zwei Kilo bei sehr technischen Trails. Alles darüber gehört ans Rad.</p>',
  'tip'
)}
`;

module.exports = article({
  href: '/taschen/kleine-taschen.html',
  kicker: 'Taschen · Kleinteile',
  title: 'Oberrohr-, Gabel- & Stemtaschen',
  metaTitle: 'Oberrohrtasche, Stem Bag & Gabeltasche: die kleinen Bikepacking-Taschen | Sattelfest',
  description:
    'Die kleinen Bikepacking-Taschen im Überblick: Oberrohrtasche, Stem Bags und Gabeltaschen mit Anything Cage – was hineingehört, welche Maße zählen und warum sie den Tourtag stärker verändern als jede große Tasche.',
  lead:
    'Sie kosten zusammen weniger als eine Satteltasche und sind die einzigen Taschen, an die du im Fahren kommst.',
  meta: [
    { icon: 'bag', text: '8 Minuten Lesezeit' },
    { icon: 'drop', text: 'Mit Wasser-Lösungen' },
    { icon: 'check', text: 'Für jedes Budget' },
  ],
  toc: [
    { label: 'Die Oberrohrtasche', id: 'oberrohr' },
    { label: 'Stem Bags (Feed Bags)', id: 'stem-bags' },
    { label: 'Gabeltaschen und Anything Cages', id: 'gabel' },
    { label: 'Kaufempfehlungen', id: 'empfehlungen' },
    { label: 'Was du sonst am Rad unterbringst', id: 'sonstiges' },
  ],
  content,
  faq: [
    {
      q: 'Brauche ich wirklich eine Oberrohrtasche?',
      a: '<p>Sie ist die günstigste Tasche mit dem größten Effekt auf den Tourtag. Handy, Riegel, Sonnencreme und Geldbeutel im Fahren erreichbar zu haben, erspart dir jeden Tag mehrere Zwangspausen. Achte auf ein schmales Modell unter 6,5 Zentimeter Breite, sonst scheuern bei Rennlenker-Fahrern die Knie.</p>',
    },
    {
      q: 'Was ist ein Stem Bag?',
      a: '<p>Eine kleine zylindrische Tasche, die neben dem Vorbau am Lenker und am Steuerrohr befestigt wird, oben offen mit Kordelzug oder Klettdeckel. Sie nimmt eine 0,75-Liter-Flasche, eine Tasse oder eine Handvoll Snacks auf und ist die einzige Stelle am Rad, in die du im Fahren hineingreifen kannst, ohne einen Verschluss zu öffnen.</p>',
    },
    {
      q: 'Wie viel Gewicht darf an die Gabel?',
      a: '<p>Die meisten Gabeln sind für zwei bis drei Kilo je Holm freigegeben, Carbongabeln teils weniger – prüfe die Herstellerangabe im Handbuch. Wichtiger als die absolute Grenze ist die symmetrische Beladung: Schon ein Kilo Unterschied zwischen links und rechts ist in der Lenkung deutlich spürbar.</p>',
    },
    {
      q: 'Kann ich Gabeltaschen an eine Federgabel montieren?',
      a: '<p>Nur eingeschränkt. Federgabeln haben keine Gewindebohrungen an den Holmen. Es gibt Klemmadapter für 30 bis 60 Euro, die um die Standrohre geschellt werden – die belasten aber die Gleitflächen und tragen weniger. Bei Federgabeln verlagert sich das Gepäck deshalb stärker auf Lenker, Rahmen und Satteltasche.</p>',
    },
    {
      q: 'Sollte ich beim Bikepacking einen Rucksack tragen?',
      a: '<p>Möglichst nicht. Ein Rucksack verlagert Gewicht auf Wirbelsäule und Sitzbeinhöcker – genau dorthin, wo nach vier Stunden im Sattel ohnehin die Probleme entstehen. Die Ausnahme ist eine leichte Trinkweste unter zwei Kilo auf sehr technischen Trails. Eine kleine Hüfttasche für Ausweis, Karte und Bargeld ist dagegen ausdrücklich zu empfehlen.</p>',
    },
  ],
  related: [
    { href: '/taschen/rahmentasche.html', label: 'Rahmentasche' },
    { href: '/routen/wasser-verpflegung.html', label: 'Wasser & Verpflegung unterwegs' },
    { href: '/taschen/richtig-packen.html', label: 'Richtig packen: Gewichtsverteilung' },
    { href: '/ausruestung/navigation.html', label: 'Navigation: Apps, GPS & Karten' },
  ],
});
