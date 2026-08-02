'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, pickGrid, affNotice,
} = require('../../components');
const { shops } = require('../../data/shops');

const content = `
<p class="lead-p">
  Es gibt drei Arten, mit dem Rad draußen zu schlafen: unter einem Zelt, unter einem Tarp oder in
  einem Biwaksack. Sie unterscheiden sich um mehr als ein Kilo Gewicht – und vor allem darin, wie
  eine Nacht sich anfühlt, in der es ab zwei Uhr regnet.
</p>

${stats([
  { value: '1,5 kg', label: 'Zelt', note: 'Der Standard für Einsteiger – aus gutem Grund.' },
  { value: '0,9 kg', label: 'Tarp mit Biwaksack', note: 'Leichter, aber ohne Insektenschutz.' },
  { value: '0,3 kg', label: 'Nur Biwaksack', note: 'Am leichtesten, am unbequemsten.' },
])}

${h2('Die drei Systeme im Vergleich', 'vergleich')}
${table({
  head: ['', 'Zelt', 'Tarp', 'Biwaksack'],
  rows: [
    ['Gewicht', '1.100 – 2.200 g', '400 – 800 g', '200 – 500 g'],
    ['Preis', '150 – 600 €', '60 – 250 €', '50 – 200 €'],
    ['Packmaß', 'Groß – Lenkerrolle', 'Klein', 'Sehr klein'],
    ['Insektenschutz', '<strong>Ja</strong>', 'Nein (außer mit Innenzelt)', 'Nur mit Moskitonetz'],
    ['Regen von der Seite', '<strong>Kein Problem</strong>', 'Abhängig vom Aufbau', 'Kein Problem'],
    ['Aufbauzeit', '5 – 10 Min.', '3 – 8 Min. (mit Übung)', '30 Sek.'],
    ['Braucht Bäume/Stöcke', 'Nein', '<strong>Meist ja</strong>', 'Nein'],
    ['Privatsphäre', '<strong>Ja</strong>', 'Kaum', 'Keine'],
    ['Kondenswasser', 'Mäßig, doppelwandig kaum', 'Sehr wenig', '<strong>Viel</strong>'],
    ['Platz für Gepäck', '<strong>Apsis</strong>', 'Unter der Plane', 'Keiner'],
    ['Auf Campingplätzen', '<strong>Immer erlaubt</strong>', 'Meist erlaubt', 'Manchmal problematisch'],
    ['Für die erste Tour', '<strong>Empfohlen</strong>', 'Nur mit Vorerfahrung', 'Nein'],
  ],
})}

${callout(
  'Die Empfehlung für den Anfang',
  '<p>Nimm ein <strong>Zelt</strong>. Nicht weil es das beste System wäre, sondern weil es das <em>fehlerverzeihendste</em> ist. Ein Tarp muss man richtig spannen können, und das lernt man nicht im Dunkeln bei aufziehendem Wind. Ein Zelt steht auch dann, wenn du beim Aufbau alles falsch machst. Auf die 900 Gramm Unterschied kommt es bei deinen ersten Touren nicht an.</p>',
  'tip'
)}

${h2('Das Zelt', 'zelt')}
${h3('Einwandig oder doppelwandig', 'wandig')}
<p>
  Doppelwandige Zelte haben ein Innenzelt aus atmungsaktivem Gewebe und ein separates Außenzelt.
  Kondenswasser schlägt sich am Außenzelt nieder und tropft nicht auf den Schlafsack. Einwandige
  Zelte sind leichter und kleiner, sammeln aber innen Kondenswasser – in feuchten Nächten wachst du
  in einem beschlagenen Zelt auf.
</p>
${checklist([
  '<strong>Doppelwandig für Einsteiger:</strong> Kondensproblem gelöst, robuster, Innenzelt und Außenzelt getrennt packbar (Innenzelt in die Lenkerrolle, nasses Außenzelt an die Gabel)',
  '<strong>Einwandig für Gewichtsbewusste:</strong> 300 bis 500 Gramm leichter, kleiner im Packmaß, aber viel Lüftungsdisziplin nötig',
])}

${h3('Freistehend oder abgespannt', 'freistehend')}
<p>
  Freistehende Zelte stehen ohne Heringe, weil Stangen die Form halten. Das klingt nach einem
  Detail, ist aber auf Tour ein echter Unterschied: Auf hartem, steinigem oder sehr sandigem
  Untergrund – und genau der ist auf Trekkingplätzen häufig – hältst du kaum Heringe.
</p>
${table({
  head: ['Bauart', 'Gewicht', 'Vorteil', 'Nachteil'],
  rows: [
    ['Freistehend (2 Stangen)', '1.400 – 2.200 g', 'Steht überall, auch auf Fels und Schotter', 'Schwerer, größeres Packmaß'],
    ['Halbfreistehend', '1.100 – 1.600 g', 'Guter Kompromiss', 'Fußende muss abgespannt werden'],
    ['Trekkingstock-Zelt', '700 – 1.200 g', 'Sehr leicht, kleines Packmaß', 'Braucht Stöcke – hat man beim Radfahren nicht'],
    ['Tunnelzelt', '1.300 – 2.000 g', 'Viel Platz, sehr windstabil', 'Muss immer abgespannt werden'],
  ],
  note: 'Achtung bei Trekkingstock-Zelten: Sie sind leicht, aber du müsstest die Stöcke extra mitnehmen. Manche Hersteller bieten leichte Zeltstangen als Zubehör an.',
})}

${h3('Ein- oder Zweipersonenzelt?', 'groesse')}
<p>
  Ein Einpersonenzelt ist für eine Person eng. Die Faustregel unter Radreisenden lautet deshalb:
  <strong>Nimm eine Größe mehr, als du bist</strong> – als Einzelperson ein leichtes
  Zweipersonenzelt. Der Gewichtsunterschied beträgt oft nur 200 bis 400 Gramm, aber du hast Platz
  für Taschen im Innenraum und kannst dich bei Regen einen ganzen Vormittag darin aufhalten.
</p>

${callout(
  'Was „2 Personen“ wirklich heißt',
  '<p>Die Personenangabe bei Zelten beschreibt eine Liegefläche, keine Wohnfläche. Ein Zweipersonenzelt hat typischerweise 120 bis 130 Zentimeter Innenbreite – das sind 60 bis 65 Zentimeter pro Person. Eine breite Isomatte ist 63 Zentimeter breit. Rechne also: Zwei Personen mit Isomatten passen hinein, aber nichts sonst.</p>',
  'info'
)}

${h2('Das Tarp', 'tarp')}
<p>
  Ein Tarp ist eine Plane, die als Dach gespannt wird. Es wiegt 400 bis 800 Gramm, packt sich sehr
  klein und bietet – richtig aufgebaut – überraschend guten Schutz. Was es nicht bietet:
  Insektenschutz, geschlossene Seiten und einen Boden.
</p>
${table({
  head: ['Aufbau', 'Schutz', 'Braucht'],
  rows: [
    ['A-Frame (Firstdach)', 'Gut gegen Regen von oben und seitlich', 'Schnur zwischen zwei Bäumen oder 2 Stöcke'],
    ['Lean-to (Schrägdach)', 'Regen von einer Seite, offen zur anderen', 'Eine Abspannlinie plus Heringe'],
    ['Halbpyramide', 'Sehr guter Rundumschutz', 'Einen Stock oder Stützpunkt in der Mitte'],
    ['Über dem Rad gespannt', 'Notlösung, funktioniert erstaunlich gut', 'Das Rad plus Heringe'],
  ],
  note: 'Ein Tarp braucht fast immer einen Aufhängepunkt. Auf einer Wiese ohne Bäume und ohne Trekkingstöcke bist du aufgeschmissen – das ist der praktische Hauptnachteil beim Radfahren.',
})}
${checklist([
  'Tarp plus Biwaksack ergibt zusammen etwa 900 Gramm – dann hast du auch von unten Schutz',
  'Ein separates Moskitonetz (60 – 150 g) löst das Insektenproblem im Sommer',
  'Übe den Aufbau zweimal im Garten, bevor du ihn im Regen brauchst',
  'Auf Campingplätzen ist ein Tarp meist erlaubt, aber frag im Zweifel nach',
])}

${h2('Der Biwaksack', 'biwak')}
<p>
  Die minimalste Form: eine wasserdichte, atmungsaktive Hülle um den Schlafsack. Ab 200 Gramm, in
  30 Sekunden einsatzbereit, überall aufzubauen. Der Preis dafür ist Kondenswasser: Selbst gute
  Membranen kommen mit der Feuchtigkeit, die ein Mensch nachts abgibt, nur begrenzt zurecht.
</p>
${checklist(
  [
    'Kondenswasser macht den Schlafsack im Lauf der Nacht klamm – bei Daune ein echtes Problem',
    'Kein Platz zum Umziehen, Lesen oder Warten bei Regen',
    'Kein Schutz vor Insekten, wenn du das Gesicht frei lässt',
    'Nicht auf jedem Campingplatz gern gesehen',
  ],
  { tone: 'dont' }
)}
<p>
  Als <strong>Ergänzung</strong> ist ein Biwaksack allerdings sehr sinnvoll: unter einem Tarp, als
  Notfallschutz für spontane Nächte oder als zusätzliche Wärmeschicht bei kühlen Nächten. Als
  alleiniges System für die erste Tour ist er nicht zu empfehlen.
</p>

${affNotice()}

${h2('Kaufempfehlungen', 'empfehlungen')}
${pickGrid(
  [
    {
      badge: 'Für die ersten Touren',
      title: 'Doppelwandiges 1–2-Personen-Zelt',
      forWhom: 'Alle, die zum ersten Mal mit dem Rad draußen schlafen.',
      price: 'ca. 150 – 320 €',
      specs: [
        { k: 'Gewicht', v: '1.400 – 1.900 g' },
        { k: 'Aufbau', v: 'Freistehend oder halbfreistehend' },
        { k: 'Wassersäule Boden', v: 'mindestens 3.000 mm' },
        { k: 'Packmaß', v: 'Passt geteilt in Lenkerrolle und Gabeltasche' },
      ],
      pros: [
        'Verzeiht Aufbaufehler und schlechte Standplätze',
        'Kein Kondenswasser auf dem Schlafsack',
        'Apsis nimmt Taschen und Schuhe auf',
        'Innen- und Außenzelt getrennt packbar',
      ],
      cons: ['Schwerer und sperriger als alle Alternativen'],
      partner: 'amazon',
      url: shops.zelt1p,
      ctaLabel: 'Zelte ansehen',
    },
    {
      badge: 'Wenn Gewicht zählt',
      title: 'Tarp plus Moskitonetz',
      forWhom: 'Sommertouren, Erfahrene, Gewichtsbewusste.',
      price: 'ca. 90 – 280 €',
      specs: [
        { k: 'Gewicht', v: '400 – 800 g plus 60 – 150 g Netz' },
        { k: 'Fläche', v: 'mindestens 2,8 × 2 m für eine Person' },
        { k: 'Material', v: 'Silnylon oder DCF' },
        { k: 'Braucht', v: 'Bäume oder Stöcke als Aufhängepunkt' },
      ],
      pros: [
        'Rund 900 Gramm leichter als ein Zelt',
        'Sehr kleines Packmaß, passt in jede Tasche',
        'Kaum Kondenswasser durch die offene Bauweise',
        'Bietet auch tagsüber Schatten und Regenschutz für Pausen',
      ],
      cons: [
        'Braucht einen Aufhängepunkt – auf offener Wiese schwierig',
        'Kein Boden, kein Insektenschutz ohne Zusatz',
        'Aufbau will geübt sein',
      ],
      partner: 'amazon',
      url: shops.tarp,
      ctaLabel: 'Tarps ansehen',
    },
    {
      badge: 'Als Ergänzung stark',
      title: 'Biwaksack',
      forWhom: 'Unter dem Tarp, als Notfallschutz, für spontane Nächte.',
      price: 'ca. 50 – 200 €',
      specs: [
        { k: 'Gewicht', v: '200 – 500 g' },
        { k: 'Material', v: 'Wasserdicht und atmungsaktiv' },
        { k: 'Aufbau', v: 'Keiner – hinlegen und fertig' },
        { k: 'Einsatz', v: 'Ergänzung, nicht Alleinlösung' },
      ],
      pros: [
        'Leichtestes und kleinstes System überhaupt',
        'In 30 Sekunden einsatzbereit, überall',
        'Bringt zusätzlich 3 bis 5 Grad Wärme unter dem Tarp',
      ],
      cons: [
        'Kondenswasser macht den Schlafsack klamm',
        'Kein Platz für irgendetwas außer dir',
        'Bei Regen kein Ort zum Warten',
      ],
      partner: 'amazon',
      url: shops.biwaksack,
      ctaLabel: 'Biwaksäcke ansehen',
      note: 'Ein Biwaksack im Gepäck ist auch dann sinnvoll, wenn du mit Zelt fährst: Er wiegt wenig und rettet dich, falls du unterwegs spontan übernachten musst.',
    },
  ],
  { columns: 3 }
)}

${h2('Worauf du beim Zeltkauf achtest', 'kaufkriterien')}
${table({
  head: ['Kriterium', 'Mindestwert', 'Warum'],
  rows: [
    ['Wassersäule Boden', '3.000 mm', 'Der Boden bekommt Druck durch dein Körpergewicht'],
    ['Wassersäule Außenzelt', '1.500 mm', 'Für Landregen ausreichend'],
    ['Innenhöhe am Kopfende', '90 cm', 'Darunter kannst du nicht aufrecht sitzen'],
    ['Innenlänge', 'Körpergröße + 25 cm', 'Sonst berührt der Schlafsack das feuchte Innenzelt'],
    ['Apsis', 'mindestens 0,6 m²', 'Für Taschen und Schuhe'],
    ['Gestängematerial', 'Aluminium (nicht Fiberglas)', 'Fiberglas splittert bei Kälte und Wind'],
    ['Reißverschlüsse', 'Zwei Wege, YKK oder gleichwertig', 'Der häufigste Defekt an Zelten'],
  ],
  note: 'Zusatztipp: Zähle vor dem Kauf die Heringe. Billige Zelte liefern zu wenige, und Nachkaufen kostet mehr, als es sollte.',
})}

${callout(
  'Der Aufbau-Test vor der Tour',
  '<p>Bau das Zelt <strong>einmal zu Hause auf</strong> – im Garten, im Hof oder notfalls im Wohnzimmer. Du prüfst dabei drei Dinge: ob alle Teile da sind, wie lange du wirklich brauchst und ob du es im Dunkeln könntest. Der erste Aufbau eines unbekannten Zelts bei Regen und Dämmerung ist eine unnötig schlechte Premiere.</p>',
  'warn'
)}
`;

module.exports = article({
  href: '/ausruestung/schlafsystem.html',
  kicker: 'Ausrüstung · Schlafen',
  title: 'Zelt, Tarp oder Biwaksack?',
  metaTitle: 'Bikepacking-Zelt, Tarp oder Biwaksack: Der Systemvergleich | Sattelfest',
  description:
    'Zelt, Tarp oder Biwaksack beim Bikepacking: Gewicht, Preis, Schutz und Aufbau im direkten Vergleich – plus Kaufkriterien für Zelte, warum doppelwandig für Einsteiger besser ist und was „2 Personen“ wirklich bedeutet.',
  lead:
    'Drei Wege, draußen zu schlafen. Sie unterscheiden sich um mehr als ein Kilo – und vor allem darin, wie sich eine Nacht anfühlt, in der es ab zwei Uhr regnet.',
  meta: [
    { icon: 'tent', text: '10 Minuten Lesezeit' },
    { icon: 'weight', text: 'Mit Gewichtsvergleich' },
    { icon: 'check', text: 'Kaufkriterien für Zelte' },
  ],
  toc: [
    { label: 'Die drei Systeme im Vergleich', id: 'vergleich' },
    { label: 'Das Zelt', id: 'zelt' },
    { label: 'Das Tarp', id: 'tarp' },
    { label: 'Der Biwaksack', id: 'biwak' },
    { label: 'Kaufempfehlungen', id: 'empfehlungen' },
    { label: 'Worauf du beim Zeltkauf achtest', id: 'kaufkriterien' },
  ],
  content,
  faq: [
    {
      q: 'Zelt oder Tarp für die erste Bikepacking-Tour?',
      a: '<p>Zelt. Nicht weil es besser wäre, sondern weil es Fehler verzeiht: Es steht auch dann, wenn du beim Aufbau alles falsch machst, hat Insektenschutz, geschlossene Seiten und einen Boden. Ein Tarp muss man richtig spannen können, und das lernt man nicht im Dunkeln bei aufziehendem Wind. Die 900 Gramm Unterschied fallen bei den ersten Touren nicht ins Gewicht.</p>',
    },
    {
      q: 'Wie schwer darf ein Bikepacking-Zelt sein?',
      a: '<p>1.400 bis 1.900 Gramm sind für ein gutes doppelwandiges Zelt völlig in Ordnung und der Bereich, in dem sich preislich vernünftige Modelle bewegen. Unter 1.100 Gramm wird es teuer (ab etwa 450 Euro) oder es handelt sich um ein Trekkingstock-Zelt, für das du beim Radfahren keine Stöcke dabeihast.</p>',
    },
    {
      q: 'Sollte ich als Einzelperson ein 1- oder 2-Personen-Zelt nehmen?',
      a: '<p>Ein leichtes Zweipersonenzelt. Der Gewichtsunterschied beträgt oft nur 200 bis 400 Gramm, aber du gewinnst Platz für Taschen im Innenraum und kannst dich bei Regen einen ganzen Vormittag darin aufhalten. Die Personenangabe bei Zelten beschreibt eine Liegefläche, keine Wohnfläche – ein Einpersonenzelt ist für eine Person wirklich eng.</p>',
    },
    {
      q: 'Was ist besser: einwandig oder doppelwandig?',
      a: '<p>Für Einsteiger doppelwandig. Kondenswasser schlägt sich am Außenzelt nieder statt auf dem Schlafsack, und du kannst Innen- und Außenzelt getrennt packen – Innenzelt trocken in die Lenkerrolle, nasses Außenzelt an die Gabel. Einwandige Zelte sind 300 bis 500 Gramm leichter, verlangen aber viel Lüftungsdisziplin.</p>',
    },
    {
      q: 'Brauche ich eine Zeltunterlage (Footprint)?',
      a: '<p>Nicht zwingend. Ein Footprint schützt den Zeltboden, kostet aber 200 bis 400 Gramm und 40 bis 80 Euro. Eine dünne Baufolie oder Tyvek-Bahn erfüllt denselben Zweck für 90 Gramm und wenige Euro. Sinnvoll ist eine Unterlage vor allem auf steinigem oder harzigem Untergrund.</p>',
    },
  ],
  related: [
    { href: '/ausruestung/schlafsack-isomatte.html', label: 'Schlafsack & Isomatte' },
    { href: '/routen/uebernachten.html', label: 'Übernachten: Wo du legal schläfst' },
    { href: '/taschen/lenkerrolle.html', label: 'Lenkerrolle & Lenkertasche' },
    { href: '/ausruestung/packliste.html', label: 'Die Packliste mit Gewichten' },
  ],
});
