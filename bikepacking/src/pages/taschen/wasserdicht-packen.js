'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, affNotice, pickGrid,
} = require('../../components');
const { shops } = require('../../data/shops');

const content = `
<p class="lead-p">
  „Wasserabweisend“ heißt: nach zwei Stunden Landregen ist alles feucht. „Wasserdicht“ heißt bei
  Bikepacking-Taschen: die Nähte und der Rollverschluss halten dicht, solange du sie richtig
  schließt. Der Unterschied entscheidet darüber, ob du abends in einem trockenen Schlafsack liegst.
</p>

${stats([
  { value: '3', label: 'Rollungen minimum', note: 'Weniger, und der Rollverschluss ist nicht dicht.' },
  { value: '2 Zonen', label: 'Das System', note: 'Trocken bleibt trocken, nass bleibt getrennt.' },
  { value: '20–35 €', label: 'Kosten der Lösung', note: 'Drei Packsäcke lösen das Problem dauerhaft.' },
])}

${h2('Warum Taschen allein nie reichen', 'warum')}
<p>
  Selbst hochwertige Bikepacking-Taschen sind an mindestens einer Stelle angreifbar: an den Nähten,
  am Reißverschluss oder da, wo die Riemen durch das Material geführt werden. Dazu kommt das
  eigentliche Problem beim Radfahren – Wasser kommt nicht nur von oben, sondern als Spritzwasser
  von unten, mit Druck vom Vorderrad und aus jeder Pfütze.
</p>
<p>
  Deshalb lautet die Regel: <strong>Die Tasche ist der Wetterschutz, der Packsack ist die
  Versicherung.</strong> Wer beides kombiniert, wird nie ein nasses Schlafsystem haben – egal
  welche Tasche er fährt.
</p>

${table({
  head: ['Bezeichnung', 'Bedeutung', 'Praxis'],
  rows: [
    ['Wasserabweisend / water resistant', 'Beschichtetes Gewebe, offene Nähte', 'Hält 30 – 90 Minuten leichten Regen'],
    ['Wasserdicht / waterproof', 'Verschweißte Nähte, Rollverschluss', 'Hält Landregen, wenn korrekt gerollt'],
    ['IPX-Angabe', 'Genormte Prüfung, bei Taschen selten', 'Meist nur bei Elektronik relevant'],
    ['Dry Bag', 'Wasserdichter Innensack mit Rollverschluss', 'Der eigentliche Schutz für empfindliche Ausrüstung'],
  ],
  note: 'Achtung: Auch bei „wasserdichten“ Taschen läuft Wasser an der Sattelstütze entlang und tritt an der Befestigung ein. Ein Innensack löst auch das.',
})}

${h2('Das Zwei-Zonen-System', 'zwei-zonen')}
<p>
  Die einfachste zuverlässige Methode, die sich auf jeder Tour bewährt:
</p>

${h3('Zone 1: Muss trocken bleiben', 'trocken')}
${checklist([
  '<strong>Schlafsack</strong> – ein nasser Daunenschlafsack isoliert gar nicht mehr und trocknet unterwegs nicht',
  '<strong>Wechselkleidung und Schlafkleidung</strong> – das Einzige, was dich abends aufwärmt',
  '<strong>Isolationsjacke</strong> – bei Daune ebenfalls kritisch',
  '<strong>Elektronik</strong> – Powerbank, Ladegerät, Kabel',
  '<strong>Papiere und Bargeld</strong> – am besten zusätzlich im Gefrierbeutel',
])}
<p>
  Diese Dinge kommen in einen eigenen Dry Bag, unabhängig davon, wie dicht die äußere Tasche
  angeblich ist. Ein 13-Liter-Packsack für Schlafsack und Kleidung kostet 15 bis 30 Euro.
</p>

${h3('Zone 2: Darf nass sein', 'nass')}
${checklist([
  '<strong>Außenzelt</strong> – ist morgens fast immer nass vom Tau',
  '<strong>Regenjacke und Regenhose</strong> – kommen nass wieder in die Tasche',
  '<strong>Handtuch</strong>',
  '<strong>Schuhe für abends</strong>',
  '<strong>Kochgeschirr</strong>',
])}
<p>
  Diese Dinge gehören in einen zweiten Packsack oder in eine Gabeltasche – <strong>niemals in
  dieselbe Kammer wie Zone 1</strong>. Ein nasses Außenzelt macht in acht Stunden Fahrt eine
  komplette Satteltasche feucht.
</p>

${callout(
  'Die Farbregel',
  '<p>Nimm Packsäcke in zwei deutlich unterschiedlichen Farben – zum Beispiel hell für die Trockenzone, dunkel für die Nasszone. Das klingt banal, verhindert aber genau den Fehler, den man abends im Halbdunkel bei Regen macht: den falschen Sack aufmachen und den Schlafsack ins nasse Zeltmaterial legen.</p>',
  'tip'
)}

${h2('Rollverschlüsse richtig schließen', 'rollen')}
<p>
  Der häufigste Grund für nasses Gepäck ist kein Materialfehler, sondern ein zu wenig gerollter
  Verschluss. Die Regel:
</p>
${table({
  head: ['Rollungen', 'Dichtigkeit', 'Wann ausreichend'],
  rows: [
    ['1 Rollung', 'Praktisch keine', 'Nie'],
    ['2 Rollungen', 'Spritzwasser', 'Trockenes Wetter'],
    ['<strong>3 Rollungen</strong>', '<strong>Landregen</strong>', '<strong>Der Standard</strong>'],
    ['4 – 5 Rollungen', 'Kurzes Untertauchen', 'Flussdurchquerung, Dauerregen'],
  ],
  note: 'Wichtig: Die Rollrichtung muss zur Öffnung passen, und die Kanten müssen sauber aufeinanderliegen. Ein schief gerollter Verschluss ist auch bei fünf Rollungen undicht.',
})}

${checklist([
  'Vor dem Rollen die Luft herausdrücken – das ergibt eine sauberere Rolle und spart Platz',
  'Die Kanten des Verschlusses glatt aufeinanderlegen, nicht verkantet',
  'Immer in dieselbe Richtung rollen, dann die Schnalle schließen',
  'Bei sehr vollen Säcken reichen oft nur zwei Rollungen – dann lieber einen größeren Sack nehmen',
])}

${h2('Die Schwachstellen im Detail', 'schwachstellen')}
${table({
  head: ['Schwachstelle', 'Was passiert', 'Gegenmittel'],
  rows: [
    [
      'Reißverschluss der Rahmentasche',
      'Auch „wasserabweisende“ Zipper lassen bei Dauerregen Wasser durch',
      'Elektronik in Gefrierbeutel, Zipper einwachsen',
    ],
    [
      'Befestigungspunkte an der Satteltasche',
      'Wasser läuft an der Sattelstütze entlang in die Tasche',
      'Innensack verwenden – das Einzige, was hilft',
    ],
    [
      'Naht am Boden der Lenkerrolle',
      'Spritzwasser vom Vorderrad trifft mit Druck',
      'Schutzblech montieren oder Innensack nutzen',
    ],
    [
      'Oberrohrtasche',
      'Handy liegt direkt unter dem Reißverschluss',
      'Handy in Gefrierbeutel oder wasserdichte Hülle',
    ],
    [
      'Gabeltaschen',
      'Volle Breitseite vom Vorderrad-Spritzwasser',
      'Dry Bags statt offener Packsäcke',
    ],
    [
      'Kondenswasser innen',
      'Feuchte Kleidung im dichten Sack schimmelt',
      'Nasses nie im dichten Sack lagern, tagsüber lüften',
    ],
  ],
})}

${callout(
  'Schutzbleche sind unterschätzt',
  '<p>Ein hinteres Schutzblech hält deinen Rücken trocken. Ein <strong>vorderes</strong> hält Gabeltaschen, Lenkerrolle und Füße trocken – und es hält den Antrieb sauber. Steckschutzbleche kosten 25 bis 50 Euro, wiegen 200 bis 400 Gramm und sind auf Herbsttouren die beste Investition dieser Preisklasse. Der einzige Grund, sie wegzulassen, ist grobes Gelände, in dem sie verstopfen.</p>',
  'tip'
)}

${affNotice()}

${h2('Was du kaufen solltest', 'kaufen')}
${pickGrid(
  [
    {
      badge: 'Die Basis',
      title: 'Packsack-Set, 3 Größen',
      forWhom: 'Jeder – unabhängig vom Taschensystem.',
      price: 'ca. 20 – 40 €',
      specs: [
        { k: 'Größen', v: '5 l, 10 l, 15 l' },
        { k: 'Gewicht', v: '40 – 120 g je Sack' },
        { k: 'Material', v: 'PU-beschichtetes Nylon oder TPU' },
        { k: 'Verschluss', v: 'Rollverschluss mit Schnalle' },
      ],
      pros: [
        'Löst das Wasserthema unabhängig von der Taschenqualität',
        'Sortiert das Gepäck – du findest abends alles im Dunkeln',
        'Halten jahrelang und passen an jedes künftige Setup',
      ],
      cons: ['Etwa 200 g Zusatzgewicht für das gesamte Set'],
      partner: 'amazon',
      url: shops.packsaecke,
      ctaLabel: 'Packsäcke ansehen',
    },
    {
      badge: 'Der große Hebel',
      title: 'Steckschutzbleche vorn und hinten',
      forWhom: 'Alle, die im Frühjahr, Herbst oder bei wechselhaftem Wetter fahren.',
      price: 'ca. 25 – 60 €',
      specs: [
        { k: 'Gewicht', v: '200 – 400 g je Paar' },
        { k: 'Montage', v: 'Steckbar, in 2 Minuten ab' },
        { k: 'Wirkung', v: 'Trockener Rücken, trockene Füße, saubere Taschen' },
        { k: 'Einschränkung', v: 'Bei Matsch und grobem Gelände verstopfen sie' },
      ],
      pros: [
        'Größter Komfortgewinn pro Euro auf Regentouren',
        'Hält Gabeltaschen und Lenkerrolle trocken',
        'Schont Kette und Antrieb erheblich',
      ],
      cons: [
        'Zusätzliches Gewicht und Klappern bei billigen Modellen',
        'Im groben Gelände oft im Weg',
      ],
      partner: 'bikecomponents',
      url: 'https://www.bike-components.de/de/Schutzbleche/',
      ctaLabel: 'Schutzbleche ansehen',
    },
    {
      badge: 'Für Elektronik',
      title: 'Gefrierbeutel mit Zipverschluss',
      forWhom: 'Handy, Powerbank, Papiere, Ladegerät.',
      price: 'ca. 3 – 6 €',
      specs: [
        { k: 'Gewicht', v: '5 g je Stück' },
        { k: 'Vorteil', v: 'Durchsichtig – Handy bleibt bedienbar' },
        { k: 'Nachteil', v: 'Reißt irgendwann, ersetzen' },
        { k: 'Alternative', v: 'Wasserdichte Handyhülle, 15 – 30 €' },
      ],
      pros: [
        'Lächerlich günstig für den Schutz, den sie bieten',
        'Handy lässt sich durch die Folie bedienen und navigieren',
        'Wiegen praktisch nichts, immer Ersatz dabei',
      ],
      cons: ['Nicht sehr haltbar – nach ein paar Touren tauschen'],
      note: 'Der klassische Trick der Radreisenden: Ausweis, Bargeld und Karte kommen zusätzlich in einen kleinen Zipbeutel. Kostet nichts und rettet dich, wenn wirklich alles nass wird.',
    },
  ],
  { columns: 3 }
)}

${h2('Wenn doch alles nass geworden ist', 'notfall')}
${checklist([
  '<strong>Schlafsack zuerst.</strong> Ein nasser Daunenschlafsack wärmt nicht. Wenn es kritisch wird: Unterkunft suchen, nicht draußen schlafen.',
  '<strong>Zeitung oder Küchenpapier</strong> im Supermarkt kaufen und in nasse Schuhe stopfen – zieht über Nacht viel Feuchtigkeit.',
  '<strong>Nasse Kleidung nachts in den Schlafsack legen</strong> – nur bei Kunstfaser und nur, wenn sie fast trocken ist. Bei Daune nie.',
  '<strong>Kunstfaser statt Daune</strong>, wenn du regelmäßig bei Nässe fährst. Sie wärmt auch feucht noch.',
  '<strong>Trockenraum nutzen.</strong> Viele Campingplätze haben einen. Fragen kostet nichts.',
  '<strong>Am nächsten Sonnentag alles aufhängen</strong> – auch mittags in der Pause 30 Minuten reichen für vieles.',
])}
`;

module.exports = article({
  href: '/taschen/wasserdicht-packen.html',
  kicker: 'Taschen · Nässe',
  title: 'Wasserdicht packen',
  metaTitle: 'Bikepacking wasserdicht packen: Das Zwei-Zonen-System | Sattelfest',
  description:
    'Bikepacking-Gepäck trocken halten: der Unterschied zwischen wasserabweisend und wasserdicht, das Zwei-Zonen-System mit Packsäcken, Rollverschlüsse richtig schließen und die typischen Schwachstellen.',
  lead:
    '„Wasserabweisend“ heißt: nach zwei Stunden Landregen ist alles feucht. Das Zwei-Zonen-System löst das Problem für 20 bis 35 Euro.',
  meta: [
    { icon: 'drop', text: '8 Minuten Lesezeit' },
    { icon: 'shield', text: 'Mit Schwachstellen-Liste' },
    { icon: 'check', text: 'Notfallplan inklusive' },
  ],
  toc: [
    { label: 'Warum Taschen allein nie reichen', id: 'warum' },
    { label: 'Das Zwei-Zonen-System', id: 'zwei-zonen' },
    { label: 'Rollverschlüsse richtig schließen', id: 'rollen' },
    { label: 'Die Schwachstellen im Detail', id: 'schwachstellen' },
    { label: 'Was du kaufen solltest', id: 'kaufen' },
    { label: 'Wenn doch alles nass geworden ist', id: 'notfall' },
  ],
  content,
  faq: [
    {
      q: 'Sind Bikepacking-Taschen wasserdicht?',
      a: '<p>Viele sind nur wasserabweisend – die halten 30 bis 90 Minuten leichten Regen. Echte wasserdichte Taschen mit verschweißten Nähten und Rollverschluss halten Landregen, wenn du den Verschluss mindestens dreimal einrollst. Aber selbst dann läuft Wasser an der Sattelstütze entlang in die Tasche. Die zuverlässige Lösung sind Packsäcke im Inneren.</p>',
    },
    {
      q: 'Wie oft muss ich einen Rollverschluss einrollen?',
      a: '<p>Mindestens dreimal – das ist der Standard für Landregen. Zwei Rollungen halten nur Spritzwasser, vier bis fünf sind für Dauerregen oder eine Flussdurchquerung sinnvoll. Wichtiger als die Anzahl ist, dass die Kanten sauber und gerade aufeinanderliegen: Ein schief gerollter Verschluss ist auch bei fünf Rollungen undicht.</p>',
    },
    {
      q: 'Was ist das Zwei-Zonen-System?',
      a: '<p>Alles, was trocken bleiben muss (Schlafsack, Wechselkleidung, Elektronik, Papiere), kommt in einen eigenen Dry Bag. Alles, was nass sein darf (Außenzelt, Regensachen, Handtuch, Schuhe), kommt in einen zweiten, getrennten Sack oder in eine Gabeltasche. Beides niemals in derselben Kammer – ein nasses Außenzelt durchfeuchtet in acht Stunden eine ganze Satteltasche.</p>',
    },
    {
      q: 'Lohnen sich Schutzbleche beim Bikepacking?',
      a: '<p>Auf Asphalt und festen Wegen sehr. Ein vorderes Schutzblech hält Gabeltaschen, Lenkerrolle und Füße trocken und schont den Antrieb erheblich, ein hinteres deinen Rücken. Steckschutzbleche kosten 25 bis 60 Euro und wiegen 200 bis 400 Gramm. Nur im groben, matschigen Gelände sind sie hinderlich, weil sie verstopfen.</p>',
    },
    {
      q: 'Was mache ich, wenn mein Schlafsack nass geworden ist?',
      a: '<p>Bei Daune wird es ernst: nasse Daune isoliert praktisch nicht mehr und trocknet unterwegs kaum. Such dir für diese Nacht eine Unterkunft, statt draußen zu schlafen – das ist ein Sicherheitsthema, kein Komfortthema. Kunstfaserschlafsäcke wärmen auch feucht noch und sind deshalb für regenreiche Regionen die robustere Wahl.</p>',
    },
  ],
  related: [
    { href: '/taschen/richtig-packen.html', label: 'Richtig packen: Gewichtsverteilung' },
    { href: '/ausruestung/schlafsack-isomatte.html', label: 'Schlafsack & Isomatte' },
    { href: '/routen/saison-wetter.html', label: 'Saison, Wetter & Jahreszeit' },
    { href: '/ausruestung/kleidung.html', label: 'Kleidung: Das Zwiebelprinzip' },
  ],
});
