'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, pickGrid, affNotice, doDont,
} = require('../../components');
const { shops } = require('../../data/shops');

const content = `
<p class="lead-p">
  Die Rahmentasche ist fahrdynamisch die beste Position am ganzen Rad: tief, mittig, direkt über dem
  Tretlager. Gewicht, das hier sitzt, spürst du fast nicht. Trotzdem ist sie meist die letzte Tasche,
  die gekauft wird – weil sie am schwersten zu finden ist, die zum eigenen Rahmen passt.
</p>

${stats([
  { value: '1.', label: 'Platz fürs Schwerste', note: 'Werkzeug, Wasser, Kocher – alles, was Gewicht hat.' },
  { value: '2–8 l', label: 'Je nach Rahmengröße', note: 'Ein 52er-Rahmen fasst deutlich weniger als ein 58er.' },
  { value: '0', label: 'spürbarer Einfluss', note: 'Auf das Fahrverhalten, solange das Gewicht hier sitzt.' },
])}

${h2('Warum die Position so gut ist', 'position')}
<p>
  Der Schwerpunkt eines beladenen Rads bestimmt, wie es sich fährt. Gewicht weit oben macht es
  kippelig, Gewicht weit vorn oder hinten macht es träge beim Richtungswechsel. Das Rahmendreieck
  liegt genau dort, wo beides nicht passiert: unmittelbar über der Verbindungslinie der Radachsen und
  fast in der Mitte zwischen ihnen.
</p>
<p>
  Praktisch heißt das: Drei Kilo in der Rahmentasche verändern das Fahrverhalten kaum spürbar.
  Dieselben drei Kilo in der Lenkerrolle machen aus einem handlichen Rad einen Ozeandampfer.
</p>

${callout(
  'Die Regel für die Rahmentasche',
  '<p>Alles, was <strong>schwer und kompakt</strong> ist, gehört hier hinein – und zwar bevorzugt in den unteren, vorderen Bereich, also Richtung Tretlager. Werkzeug, Ersatzschläuche, Powerbank, Kocher, Gaskartusche, Wasser, Riegel. Wenn du unsicher bist, wo etwas hingehört: Wiege es in der Hand. Fühlt es sich schwer an für seine Größe, gehört es in den Rahmen.</p>',
  'tip'
)}

${h2('Die drei Bauformen', 'bauformen')}
${table({
  head: ['Bauform', 'Füllt', 'Volumen', 'Flaschenhalter'],
  rows: [
    [
      '<strong>Halbrahmentasche</strong>',
      'Oberes Dreieck, am Oberrohr entlang',
      '2 – 4 l',
      'Bleibt nutzbar – der große Vorteil',
    ],
    [
      '<strong>Volle Rahmentasche</strong>',
      'Das komplette Rahmendreieck',
      '4 – 8 l',
      'Nicht mehr nutzbar',
    ],
    [
      '<strong>Maßanfertigung</strong>',
      'Exakt dein Rahmendreieck',
      '3 – 9 l',
      'Nach Wunsch mit Aussparung',
    ],
  ],
  note: 'Die Halbrahmentasche ist für die meisten Einsteiger die bessere Wahl: Sie kostet weniger, passt an mehr Räder und lässt die Trinkflasche im Rahmen.',
})}

${h3('Halbrahmentasche oder volle Tasche?', 'halb-oder-voll')}
${doDont({
  doTitle: 'Halbrahmentasche, wenn …',
  doItems: [
    'Du auf Routen mit regelmäßiger Wasserversorgung fährst (Deutschland, Mitteleuropa)',
    'Du eine oder zwei Flaschen im Rahmen behalten willst',
    'Dein Rahmen klein ist und eine volle Tasche kaum Volumen brächte',
    'Du eine Tasche willst, die auch an dein nächstes Rad passt',
  ],
  dontTitle: 'Volle Rahmentasche, wenn …',
  dontItems: [
    'Du in abgelegenen Gegenden fährst und Wasser in Flaschen an der Gabel transportierst',
    'Du das maximale Volumen bei minimalem Fahreinfluss brauchst',
    'Dein Rahmen groß ist und viel ungenutzter Raum bleibt',
    'Du längere Touren mit viel Verpflegung planst',
  ],
})}

${h2('Was hineingehört – und was nicht', 'inhalt')}
${table({
  head: ['Gegenstand', 'Gewicht', 'Warum hier'],
  rows: [
    ['Multitool, Reifenheber, Kettenschloss', '250 g', 'Schwer, kompakt, selten gebraucht'],
    ['2 Ersatzschläuche', '300 g', 'Kompakt und schwer für ihre Größe'],
    ['Minipumpe', '110 g', 'Sperrig, aber flach – passt gut ans Oberrohr'],
    ['Powerbank', '200 – 400 g', 'Schwerstes Elektronikteil, gehört nach unten'],
    ['Gaskartusche', '380 g', 'Kompakt und schwer – der ideale Rahmeninhalt'],
    ['Wasser in Faltflasche', '500 – 1.000 g', 'Sehr schwer, deshalb so tief wie möglich'],
    ['Riegel und Tagesverpflegung', '400 – 800 g', 'Erreichbar im Stehen, nicht im Fahren'],
    ['Kabel, Ladegerät, Kleinkram', '150 g', 'Verschwindet sonst in großen Taschen'],
  ],
  note: 'Nicht hinein gehört alles Weiche und Voluminöse: Schlafsack, Daunenjacke, Kleidung. Das verschwendet den wertvollsten Platz am Rad.',
})}

${h2('Passt eine Rahmentasche an mein Rad?', 'passform')}
${h3('So misst du richtig', 'messen')}
<p>
  Nimm ein Maßband und miss die drei Innenseiten deines Rahmendreiecks – jeweils von Schweißnaht zu
  Schweißnaht, nicht von Rohrmitte zu Rohrmitte:
</p>
${checklist([
  '<strong>Oberrohr:</strong> vom Steuerrohr bis zum Sitzrohr (typisch 48 – 58 cm)',
  '<strong>Unterrohr:</strong> vom Steuerrohr bis zum Tretlager (typisch 55 – 68 cm)',
  '<strong>Sitzrohr:</strong> vom Oberrohr bis zum Tretlager (typisch 38 – 52 cm)',
  '<strong>Höhe an der engsten Stelle:</strong> für Halbrahmentaschen entscheidend',
  '<strong>Breite:</strong> Kurbelfreiheit prüfen – die Fersen dürfen die Tasche nicht streifen',
])}

${callout(
  'Der Fersencheck',
  '<p>Setz dich aufs Rad und tritt langsam. Bei kleinen Rahmen und breiten Rahmentaschen berühren die Fersen oder Waden die Tasche – vor allem beim Fahren im Stehen. Eine 6 cm breite Tasche ist bequem, eine 8 cm breite bei kleinem Rahmen oft ein Problem. Im Zweifel schmaler kaufen: Das fehlende Volumen fällt weniger auf als eine Wade, die 60 Kilometer lang gegen Reißverschlusszipper reibt.</p>',
  'warn'
)}

${h3('Sonderfälle', 'sonderfaelle')}
${table({
  head: ['Rahmen', 'Problem', 'Lösung'],
  rows: [
    ['Rahmen unter 52 cm', 'Sehr kleines Dreieck, 2 – 3 l Volumen', 'Halbrahmentasche, dafür Gabeltaschen ergänzen'],
    ['Vollgefedertes MTB', 'Dämpfer nimmt den Platz', 'Kleine Oberrohr-Hängetasche oder gar keine'],
    ['Damenrahmen / Tiefeinsteiger', 'Kein geschlossenes Dreieck', 'Tasche unter dem Oberrohr oder Gepäckträger nutzen'],
    ['Innenverlegte Züge mit Austritt', 'Züge scheuern an der Tasche', 'Zug mit Schutzfolie umkleben'],
    ['Trinkflaschenhalter im Weg', 'Tasche passt nicht bei montiertem Halter', 'Halter versetzen oder unters Unterrohr montieren'],
  ],
})}

${affNotice()}

${h2('Kaufempfehlungen', 'empfehlungen')}
${pickGrid(
  [
    {
      badge: 'Für die meisten richtig',
      title: 'Halbrahmentasche, 3 – 4 Liter',
      forWhom: 'Erste Rahmentasche, Flasche soll im Rahmen bleiben.',
      price: 'ca. 50 – 100 €',
      specs: [
        { k: 'Volumen', v: '3 – 4 l' },
        { k: 'Leergewicht', v: '180 – 300 g' },
        { k: 'Verschluss', v: 'Reißverschluss, wasserabweisend' },
        { k: 'Befestigung', v: 'Klett am Ober- und Unterrohr' },
      ],
      pros: [
        'Passt an sehr viele Rahmen, auch kleine',
        'Trinkflasche bleibt nutzbar',
        'Fällt bei der Fersenfreiheit kaum ins Gewicht',
      ],
      cons: ['Begrenztes Volumen – für lange Touren zu wenig'],
      partner: 'amazon',
      url: shops.rahmentascheHalb,
      ctaLabel: 'Halbrahmentaschen ansehen',
    },
    {
      badge: 'Maximales Volumen',
      title: 'Volle Rahmentasche, 5 – 7 Liter',
      forWhom: 'Längere Touren, abgelegene Strecken, viel Verpflegung.',
      price: 'ca. 80 – 150 €',
      specs: [
        { k: 'Volumen', v: '5 – 7 l' },
        { k: 'Leergewicht', v: '280 – 450 g' },
        { k: 'Aufteilung', v: 'Meist zwei Kammern' },
        { k: 'Wichtig', v: 'Rahmenmaße vorher prüfen' },
      ],
      pros: [
        'Größtes Volumen an der besten Position',
        'Zwei Kammern trennen Werkzeug von Verpflegung',
        'Nimmt schwere Dinge auf, die sonst nirgends gut liegen',
      ],
      cons: [
        'Keine Flasche mehr im Rahmen – Wasser muss woanders hin',
        'Passt nur, wenn die Maße stimmen',
      ],
      partner: 'amazon',
      url: shops.rahmentasche,
      ctaLabel: 'Rahmentaschen ansehen',
    },
    {
      badge: 'Wenn nichts passt',
      title: 'Maßanfertigung',
      forWhom: 'Ungewöhnliche Rahmen, sehr kleine oder sehr große Größen.',
      price: 'ca. 150 – 300 €',
      specs: [
        { k: 'Volumen', v: 'so viel wie dein Rahmen hergibt' },
        { k: 'Lieferzeit', v: '3 – 10 Wochen, je nach Werkstatt' },
        { k: 'Bestellung', v: 'Nach Schablone oder Foto mit Maßstab' },
        { k: 'Extras', v: 'Kabeldurchlass, Flaschenaussparung, Farbe' },
      ],
      pros: [
        'Nutzt jeden Kubikzentimeter des Rahmendreiecks',
        'Aussparungen für Flaschenhalter und Züge möglich',
        'Kleine Werkstätten reparieren die Tasche auch nach Jahren',
      ],
      cons: [
        'Teuer und mit Wartezeit',
        'Passt nur an dieses eine Rad',
      ],
      note: 'Lohnt sich, wenn du weißt, dass du das Rad länger fährst. Für die erste Saison ist eine Halbrahmentasche von der Stange die vernünftigere Wahl.',
    },
  ],
  { columns: 3 }
)}

${h2('Wo das Wasser hin soll, wenn die Tasche den Rahmen füllt', 'wasser')}
<p>
  Das ist die praktische Folgefrage jeder vollen Rahmentasche. Die Optionen, geordnet nach
  Praxistauglichkeit:
</p>
${table({
  head: ['Lösung', 'Menge', 'Bewertung'],
  rows: [
    ['Flaschenhalter unter dem Unterrohr', '0,75 l', 'Gut – aber die Flasche wird schmutzig'],
    ['Anything Cage an der Gabel', '2 × 1 l', '<strong>Beste Lösung</strong>, tief und ausbalanciert'],
    ['Stem Bags neben dem Vorbau', '2 × 0,75 l', 'Sehr griffbereit, aber Gewicht am Lenker'],
    ['Faltflasche in der Rahmentasche', '1 – 2 l', 'Ideal für die Reserve, umständlich im Fahren'],
    ['Trinkblase im Rucksack', '2 – 3 l', 'Funktioniert – aber Rucksack ist auf Tour unangenehm'],
    ['Flasche in der Trikottasche', '0,5 l', 'Nur als Notlösung, drückt auf den Rücken'],
  ],
  note: 'Rechne mit 500 bis 750 Milliliter pro Stunde bei warmem Wetter. Für eine Etappe zwischen zwei Versorgungspunkten sind 1,5 Liter meist ausreichend.',
})}
`;

module.exports = article({
  href: '/taschen/rahmentasche.html',
  kicker: 'Taschen · Rahmen',
  title: 'Die Rahmentasche',
  metaTitle: 'Bikepacking-Rahmentasche: Halb oder voll, Maße & Kaufberatung | Sattelfest',
  description:
    'Die Rahmentasche ist die beste Position am Rad: tief, mittig, fahrstabil. Halbrahmentasche oder volle Tasche, richtig ausmessen, was hineingehört – und wohin das Wasser bei voller Tasche kommt.',
  lead:
    'Tief, mittig, direkt über dem Tretlager: Gewicht an dieser Stelle spürst du fast nicht. Deshalb gehört hier das Schwerste hin.',
  meta: [
    { icon: 'bag', text: '9 Minuten Lesezeit' },
    { icon: 'tool', text: 'Mit Mess-Anleitung' },
    { icon: 'drop', text: 'Inklusive Wasser-Lösungen' },
  ],
  toc: [
    { label: 'Warum die Position so gut ist', id: 'position' },
    { label: 'Die drei Bauformen', id: 'bauformen' },
    { label: 'Was hineingehört – und was nicht', id: 'inhalt' },
    { label: 'Passt eine Rahmentasche an mein Rad?', id: 'passform' },
    { label: 'Kaufempfehlungen', id: 'empfehlungen' },
    { label: 'Wohin mit dem Wasser?', id: 'wasser' },
  ],
  content,
  faq: [
    {
      q: 'Halbrahmentasche oder volle Rahmentasche?',
      a: '<p>Für die meisten Einsteiger die Halbrahmentasche: Sie kostet weniger, passt an mehr Räder und lässt die Trinkflasche im Rahmen. Eine volle Rahmentasche lohnt sich, wenn du längere oder abgelegene Touren fährst, viel Verpflegung transportierst und das Wasser ohnehin an der Gabel oder in Stem Bags unterbringst.</p>',
    },
    {
      q: 'Was gehört in die Rahmentasche?',
      a: '<p>Alles, was schwer und kompakt ist: Werkzeug, Ersatzschläuche, Minipumpe, Powerbank, Gaskartusche, Kocher, Wasser und Tagesverpflegung. Nicht hinein gehört Weiches und Voluminöses wie Schlafsack oder Daunenjacke – das verschwendet den wertvollsten Platz am Rad.</p>',
    },
    {
      q: 'Wie messe ich mein Rahmendreieck aus?',
      a: '<p>Miss die drei Innenseiten jeweils von Schweißnaht zu Schweißnaht: Oberrohr (Steuerrohr bis Sitzrohr), Unterrohr (Steuerrohr bis Tretlager) und Sitzrohr (Oberrohr bis Tretlager). Prüfe zusätzlich die Fersenfreiheit, indem du dich aufs Rad setzt und langsam trittst – bei kleinen Rahmen streifen Waden oder Fersen breite Taschen.</p>',
    },
    {
      q: 'Wohin mit der Trinkflasche, wenn die Rahmentasche den Rahmen füllt?',
      a: '<p>Die beste Lösung sind Anything Cages an der Gabel: zwei Liter, tief und ausbalanciert. Alternativen sind ein Flaschenhalter unter dem Unterrohr (Flasche wird schmutzig), Stem Bags neben dem Vorbau (griffbereit, aber Gewicht am Lenker) oder eine Faltflasche als Reserve in der Rahmentasche selbst.</p>',
    },
    {
      q: 'Passt eine Rahmentasche an ein vollgefedertes Mountainbike?',
      a: '<p>Meist nicht. Der Dämpfer nimmt genau den Raum ein, den die Tasche bräuchte. Es gibt kleine Hängetaschen unter dem Oberrohr mit ein bis zwei Litern, mehr ist selten möglich. Bei Fullys verlagert sich das Gepäck deshalb stärker auf Satteltasche, Lenker und einen sattelrohrgeklemmten Gepäckträger.</p>',
    },
  ],
  related: [
    { href: '/taschen/kleine-taschen.html', label: 'Oberrohr-, Gabel- & Stemtaschen' },
    { href: '/taschen/richtig-packen.html', label: 'Richtig packen: Gewichtsverteilung' },
    { href: '/routen/wasser-verpflegung.html', label: 'Wasser & Verpflegung unterwegs' },
    { href: '/ausruestung/werkzeug-reparatur.html', label: 'Werkzeug & Reparatur-Kit' },
  ],
});
