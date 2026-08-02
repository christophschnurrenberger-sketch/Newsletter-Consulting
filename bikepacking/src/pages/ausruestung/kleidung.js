'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, weightList, pickGrid, affNotice,
} = require('../../components');
const { shops } = require('../../data/shops');

const content = `
<p class="lead-p">
  Zwei Garnituren reichen: eine am Körper, eine im Gepäck. Die eigentliche Frage ist nicht, wie viel
  du mitnimmst, sondern welche Schichten du kombinierst – und ob du dich abends in etwas Trockenes
  umziehen kannst. Das ist der Unterschied zwischen einer guten und einer elenden Tour.
</p>

${stats([
  { value: '2', label: 'Garnituren', note: 'Mehr braucht niemand, auch nicht auf einer Woche.' },
  { value: '3', label: 'Schichten', note: 'Basis, Isolation, Wetterschutz – das Zwiebelprinzip.' },
  { value: '0', label: 'Baumwolle', note: 'Sie speichert Feuchtigkeit und kühlt dich aus.' },
])}

${h2('Das Zwiebelprinzip in der Praxis', 'zwiebel')}
<p>
  Drei dünne Schichten wärmen besser als eine dicke – weil die Luft zwischen ihnen isoliert und weil
  du sie einzeln ausziehen kannst. Beim Radfahren ist das wichtiger als bei jeder anderen
  Outdoor-Aktivität: Im Anstieg schwitzt du, in der Abfahrt fünf Minuten später frierst du.
</p>

${table({
  head: ['Schicht', 'Aufgabe', 'Material', 'Beispiel'],
  rows: [
    [
      '<strong>1. Basisschicht</strong>',
      'Feuchtigkeit vom Körper wegtransportieren',
      'Merino oder Kunstfaser – <strong>nie Baumwolle</strong>',
      'Trikot, Funktionsunterhemd',
    ],
    [
      '<strong>2. Isolationsschicht</strong>',
      'Wärme halten',
      'Fleece, Primaloft, dünne Daune',
      'Fleecepulli, Daunenjacke',
    ],
    [
      '<strong>3. Wetterschutz</strong>',
      'Wind und Regen abhalten',
      'Membran oder beschichtetes Gewebe',
      'Regenjacke, Windweste',
    ],
  ],
  note: 'Beim Radfahren kommt eine vierte Kategorie dazu: die Radhose mit Sitzpolster. Sie gehört zu keiner Schicht, ist aber die wichtigste Einzelentscheidung.',
})}

${callout(
  'Warum keine Baumwolle',
  '<p>Baumwolle nimmt bis zum 25-Fachen ihres Gewichts an Feuchtigkeit auf und gibt sie kaum wieder ab. Ein baumwollenes T-Shirt ist nach dem ersten Anstieg nass, bleibt nass und kühlt dich in der Abfahrt aus. Bei 10 Grad und Wind ist das nicht nur unangenehm, sondern der direkte Weg zur Unterkühlung. Die einzige Ausnahme: das Shirt für abends im Zelt, wenn du sicher trocken bleibst.</p>',
  'warn'
)}

${h2('Die Radhose: die wichtigste Einzelentscheidung', 'radhose')}
<p>
  Nichts entscheidet stärker über deinen Komfort ab Stunde drei. Und nichts wird häufiger falsch
  gemacht.
</p>
${checklist([
  '<strong>Ohne Unterwäsche tragen.</strong> Ausnahmslos. Nähte von Unterhosen scheuern genau dort, wo es weh tut.',
  '<strong>Sitzpolster muss zu deiner Sitzposition passen:</strong> Dickeres Polster für aufrechte Touren, dünneres für sportliche Positionen',
  '<strong>Trägerhose ist auf Tour angenehmer</strong> als eine Hose mit Bund – kein Druck auf den Bauch, rutscht nicht',
  '<strong>Nach jeder Fahrt ausziehen und auslüften</strong>, spätestens abends. Feuchtes Polster ist die Hauptursache für Hautprobleme',
  '<strong>Sitzcreme ab dem zweiten Tag</strong> – dünn auf das Polster, nicht auf die Haut auftragen',
  '<strong>Bei mehr als drei Tagen</strong> lohnt eine zweite Hose zum Wechseln',
])}

${h2('Die Packliste Kleidung', 'packliste')}
${weightList({
  title: 'Sommertour, 3 Nächte',
  items: [
    { name: 'Radhose mit Sitzpolster', note: 'Angezogen', g: 0, tag: 'pflicht' },
    { name: 'Trikot oder Merino-Shirt', note: 'Angezogen', g: 0, tag: 'pflicht' },
    { name: 'Zweites Merino-Shirt', note: 'Riecht auch nach drei Tagen erträglich', g: 160, tag: 'pflicht' },
    { name: 'Socken, 2 Paar', note: 'Merino, dünn', g: 90, tag: 'pflicht' },
    { name: 'Unterwäsche für abends, 2×', g: 50, tag: 'pflicht' },
    { name: 'Regenjacke mit Kapuze', note: 'Mindestens 10.000 mm Wassersäule', g: 320, tag: 'pflicht' },
    { name: 'Leichte Isolationsjacke', note: 'Fleece oder dünne Daune – für abends', g: 280, tag: 'pflicht' },
    { name: 'Abendhose, leicht', note: 'Gleichzeitig Schlafhose', g: 220, tag: 'sinnvoll' },
    { name: 'Buff / Multifunktionstuch', note: 'Hals, Kopf, Staub, Waschlappen', g: 40, tag: 'sinnvoll' },
    { name: 'Kurzfingerhandschuhe', note: 'Schützen die Hände beim Sturz', g: 60, tag: 'sinnvoll' },
    { name: 'Leichte Schuhe für abends', note: 'Sandalen oder Barfußschuhe', g: 300, tag: 'sinnvoll' },
    { name: 'Sonnenbrille', note: 'Auch gegen Insekten und Fahrtwind', g: 30, tag: 'sinnvoll' },
    { name: 'Regenhose im Hochsommer', note: 'Über 18 °C ziehst du sie nicht an', g: 200, tag: 'ballast' },
    { name: 'Dritte Garnitur', note: 'Bleibt im Sack, garantiert', g: 400, tag: 'ballast' },
    { name: 'Jeans oder Baumwollhose für abends', note: 'Schwer, trocknet nie', g: 600, tag: 'ballast' },
  ],
})}

${h3('Was in der Übergangszeit dazukommt', 'uebergang')}
${weightList({
  title: 'Zusätzlich für April, Mai, September, Oktober',
  items: [
    { name: 'Regenhose', note: 'Ab jetzt wirklich nötig', g: 200, tag: 'pflicht' },
    { name: 'Lange Handschuhe', note: 'Kalte Hände sind ein Sicherheitsproblem', g: 90, tag: 'pflicht' },
    { name: 'Beinlinge oder lange Radhose', g: 180, tag: 'pflicht' },
    { name: 'Mütze unter dem Helm', g: 50, tag: 'pflicht' },
    { name: 'Wärmere Isolationsjacke', note: 'Statt der leichten – Differenz', g: 120, tag: 'pflicht' },
    { name: 'Überschuhe', note: 'Nasse, kalte Füße ruinieren jeden Tag', g: 130, tag: 'sinnvoll' },
    { name: 'Zweites Paar Socken', g: 45, tag: 'sinnvoll' },
  ],
})}

${h2('Die Regenjacke', 'regenjacke')}
<p>
  Der Posten, an dem sich Sparen am deutlichsten rächt. Eine Jacke, die nach zwei Stunden durchweicht,
  macht aus einer unangenehmen Tour ein Sicherheitsthema – nasse Kleidung plus Fahrtwind plus 10 Grad
  kühlt den Körper erstaunlich schnell aus.
</p>
${table({
  head: ['Kriterium', 'Wert', 'Warum'],
  rows: [
    ['Wassersäule', 'mindestens 10.000 mm', 'Darunter drückt Regen bei Fahrtwind durch'],
    ['Atmungsaktivität', 'RET unter 13 oder MVTR über 15.000', 'Sonst schwitzt du von innen nass'],
    ['Kapuze', 'Helmtauglich, mit Verstellung', 'Ohne Kapuze läuft Wasser in den Kragen'],
    ['Schnitt', 'Hinten verlängert', 'Im Fahren rutscht eine gerade Jacke hoch'],
    ['Belüftung', 'Unterarmzipper oder Rückenlüftung', 'Der wichtigste Komfortfaktor am Berg'],
    ['Gewicht', '250 – 400 g', 'Leichter heißt meist weniger haltbar'],
    ['Farbe', 'Hell oder Signalfarbe', 'Sichtbarkeit bei Regen und Dämmerung'],
  ],
  note: 'Membranjacken müssen regelmäßig imprägniert werden. Wenn das Wasser nicht mehr abperlt, ist nicht die Membran defekt, sondern die Imprägnierung erschöpft – Waschen und Nachimprägnieren hilft.',
})}

${callout(
  'Der Regenponcho – die unterschätzte Alternative',
  '<p>Ein Radponcho deckt Oberkörper, Arme und Lenkerbereich ab, ist unten offen und deshalb hervorragend belüftet. Er kostet 40 bis 90 Euro, wiegt 300 bis 400 Gramm und funktioniert bei Landregen besser als viele Jacken – weil du darunter nicht schwitzt. Nachteile: bei Wind unangenehm, im Gelände hinderlich und optisch gewöhnungsbedürftig. Für Flussradwege eine ernsthafte Option.</p>',
  'tip'
)}

${affNotice()}

${h2('Kaufempfehlungen', 'empfehlungen')}
${pickGrid(
  [
    {
      badge: 'Die wichtigste Anschaffung',
      title: 'Radhose mit gutem Sitzpolster',
      forWhom: 'Alle. Der Posten, der über Freude oder Qual entscheidet.',
      price: 'ca. 60 – 180 €',
      specs: [
        { k: 'Bauform', v: 'Trägerhose für Touren angenehmer' },
        { k: 'Polster', v: 'Mitteldick, mehrlagig, nahtarm' },
        { k: 'Länge', v: 'Kurz für Sommer, 3/4 für Übergang' },
        { k: 'Tragen', v: 'Ohne Unterwäsche, ausnahmslos' },
      ],
      pros: [
        'Kein anderes Kleidungsstück beeinflusst den Komfort so stark',
        'Gute Hosen halten 3 bis 5 Saisons',
        'Trägerhose drückt nicht auf den Bauch',
      ],
      cons: ['Passform ist sehr individuell – wenn möglich anprobieren'],
      partner: 'amazon',
      url: shops.radhose,
      ctaLabel: 'Radhosen ansehen',
    },
    {
      badge: 'Nicht daran sparen',
      title: 'Regenjacke, ab 10.000 mm Wassersäule',
      forWhom: 'Jede Tour, jede Jahreszeit. Auch im Hochsommer.',
      price: 'ca. 110 – 280 €',
      specs: [
        { k: 'Wassersäule', v: 'ab 10.000 mm' },
        { k: 'Gewicht', v: '250 – 400 g' },
        { k: 'Kapuze', v: 'Helmtauglich' },
        { k: 'Belüftung', v: 'Unterarmzipper' },
      ],
      pros: [
        'Der Unterschied zwischen unangenehm und gefährlich',
        'Funktioniert auch als Windjacke in Abfahrten',
        'Hält bei Pflege viele Jahre',
      ],
      cons: [
        'Braucht regelmäßiges Nachimprägnieren',
        'Bei Wärme und Anstieg schwitzt man auch in guten Jacken',
      ],
      partner: 'amazon',
      url: shops.regenjacke,
      ctaLabel: 'Regenjacken ansehen',
    },
    {
      badge: 'Der Mehrtages-Trick',
      title: 'Merino-Shirt',
      forWhom: 'Wer drei Tage lang dasselbe Shirt tragen will, ohne aufzufallen.',
      price: 'ca. 45 – 100 €',
      specs: [
        { k: 'Materialstärke', v: '150 – 200 g/m²' },
        { k: 'Gewicht', v: '140 – 180 g' },
        { k: 'Eigenschaft', v: 'Geruchshemmend, auch feucht warm' },
        { k: 'Pflege', v: 'Handwäsche oder Wollprogramm' },
      ],
      pros: [
        'Riecht auch nach mehreren Tagen deutlich weniger als Kunstfaser',
        'Wärmt auch feucht und trocknet am Körper',
        'Ein Shirt ersetzt zwei aus Kunstfaser',
      ],
      cons: [
        'Teurer und weniger haltbar als Kunstfaser',
        'Dünne Merino-Shirts bekommen leicht Löcher',
      ],
      note: 'Der Grund, warum Merino auf Tour so beliebt ist, ist nicht die Wärmeleistung, sondern der Geruch. Zwei Merino-Shirts reichen für eine Woche.',
    },
  ],
  { columns: 3 }
)}

${h2('Waschen unterwegs', 'waschen')}
${checklist([
  '<strong>Abends im Waschbecken:</strong> Shirt und Socken mit etwas Seife, gut auswringen, über Nacht ins Zelt hängen',
  '<strong>Der Handtuch-Trick:</strong> Nasses Kleidungsstück ins Handtuch rollen und fest auswringen – das zieht viel Wasser',
  '<strong>Merino und Kunstfaser trocknen über Nacht</strong>, Baumwolle nicht',
  '<strong>Am Rad trocknen:</strong> Socken tagsüber unter die Riemen der Satteltasche – funktioniert erstaunlich gut',
  '<strong>Seifenblätter</strong> wiegen 10 Gramm und reichen für Körper, Wäsche und Geschirr',
  '<strong>Campingplätze haben oft Waschmaschinen</strong> – nach vier bis fünf Tagen ein Segen',
])}
`;

module.exports = article({
  href: '/ausruestung/kleidung.html',
  kicker: 'Ausrüstung · Kleidung',
  title: 'Kleidung: Das Zwiebelprinzip',
  metaTitle: 'Bikepacking-Kleidung: Zwiebelprinzip, Packliste & Kaufberatung | Sattelfest',
  description:
    'Bikepacking-Kleidung richtig wählen: das Zwiebelprinzip mit drei Schichten, warum nie Baumwolle, die richtige Radhose, Kriterien für die Regenjacke und die vollständige Kleidungs-Packliste mit Gewichten.',
  lead:
    'Zwei Garnituren reichen: eine am Körper, eine im Gepäck. Entscheidend ist, welche Schichten du kombinierst.',
  meta: [
    { icon: 'sun', text: '9 Minuten Lesezeit' },
    { icon: 'weight', text: 'Mit Gewichts-Packliste' },
    { icon: 'drop', text: 'Regenjacken-Kriterien' },
  ],
  toc: [
    { label: 'Das Zwiebelprinzip in der Praxis', id: 'zwiebel' },
    { label: 'Die Radhose', id: 'radhose' },
    { label: 'Die Packliste Kleidung', id: 'packliste' },
    { label: 'Die Regenjacke', id: 'regenjacke' },
    { label: 'Kaufempfehlungen', id: 'empfehlungen' },
    { label: 'Waschen unterwegs', id: 'waschen' },
  ],
  content,
  faq: [
    {
      q: 'Wie viel Kleidung nehme ich beim Bikepacking mit?',
      a: '<p>Zwei Garnituren: eine am Körper, eine im Gepäck. Konkret sind das Radhose plus Trikot (angezogen), ein zweites Shirt, zwei Paar Socken, Unterwäsche für abends, Regenjacke, eine Isolationsschicht und leichte Abendkleidung. Alles darüber hinaus bleibt erfahrungsgemäß im Packsack – auch auf einer Woche.</p>',
    },
    {
      q: 'Warum soll ich beim Radfahren keine Baumwolle tragen?',
      a: '<p>Baumwolle nimmt bis zum 25-Fachen ihres Gewichts an Feuchtigkeit auf und gibt sie kaum wieder ab. Nach dem ersten Anstieg ist das Shirt nass, bleibt nass und kühlt dich in der Abfahrt aus. Bei 10 Grad plus Fahrtwind ist das ein Sicherheitsthema. Nimm Merino oder Kunstfaser – Baumwolle allenfalls als Abendshirt im Zelt.</p>',
    },
    {
      q: 'Trägt man Radhosen mit oder ohne Unterwäsche?',
      a: '<p>Ohne, ausnahmslos. Die Nähte einer Unterhose scheuern genau dort, wo das Sitzpolster eigentlich schützen soll, und der Stoff hält Feuchtigkeit an der Haut. Das Polster ist so konstruiert, dass es direkten Hautkontakt haben soll. Zieh die Hose nach jeder Etappe aus und lüfte sie – feuchtes Polster ist die Hauptursache für Hautprobleme.</p>',
    },
    {
      q: 'Worauf achte ich beim Kauf einer Regenjacke fürs Rad?',
      a: '<p>Mindestens 10.000 mm Wassersäule, eine helmtaugliche Kapuze, hinten verlängerter Schnitt (im Fahren rutscht eine gerade Jacke hoch) und Belüftungszipper unter den Armen. Letztere sind der wichtigste Komfortfaktor am Berg. Rechne mit 110 bis 280 Euro – das ist der Posten, an dem sich Sparen am deutlichsten rächt.</p>',
    },
    {
      q: 'Wie wasche ich Kleidung unterwegs?',
      a: '<p>Abends im Waschbecken mit etwas Seife, gut auswringen und über Nacht ins Zelt hängen. Der wirksamste Trick: das nasse Kleidungsstück ins Handtuch rollen und fest auswringen, das zieht sehr viel Wasser. Merino und Kunstfaser sind morgens trocken. Socken lassen sich tagsüber unter den Riemen der Satteltasche trocknen.</p>',
    },
  ],
  related: [
    { href: '/ausruestung/packliste.html', label: 'Die Packliste mit Gewichten' },
    { href: '/unterwegs/koerper-beschwerden.html', label: 'Sitzbeschwerden, Hände & Knie' },
    { href: '/routen/saison-wetter.html', label: 'Saison, Wetter & Jahreszeit' },
    { href: '/taschen/wasserdicht-packen.html', label: 'Wasserdicht packen' },
  ],
});
