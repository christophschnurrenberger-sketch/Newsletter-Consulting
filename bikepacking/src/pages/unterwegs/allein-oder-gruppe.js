'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, doDont, weightList,
} = require('../../components');

const content = `
<p class="lead-p">
  Die Gruppengröße verändert eine Tour stärker als jedes Ausrüstungsteil. Sie bestimmt das Tempo,
  die Planung, das Gepäckgewicht pro Person und die Sicherheit. Und sie entscheidet darüber, ob du
  abends mit jemandem redest oder mit dir selbst.
</p>

${stats([
  { value: '1–2 kg', label: 'Ersparnis pro Person', note: 'Durch geteilte Gemeinschaftsausrüstung.' },
  { value: '+30 %', label: 'Zeitbedarf ab 4 Personen', note: 'Für Pausen, Einkauf, Auf- und Abbau.' },
  { value: 'Langsamster', label: 'Bestimmt das Tempo', note: 'Immer. Ohne Ausnahme.' },
])}

${h2('Die drei Konstellationen im Vergleich', 'vergleich')}
${table({
  head: ['', 'Allein', 'Zu zweit', 'Gruppe ab 3'],
  rows: [
    ['Gepäck pro Person', 'Am höchsten', '<strong>Deutlich weniger</strong>', '<strong>Am wenigsten</strong>'],
    ['Tempo', '<strong>Völlig frei</strong>', 'Kompromiss', 'Der Langsamste bestimmt'],
    ['Planungsaufwand', '<strong>Minimal</strong>', 'Gering', 'Hoch – Abstimmung kostet Zeit'],
    ['Spontane Änderungen', '<strong>Jederzeit</strong>', 'Meist möglich', 'Schwierig'],
    ['Zeitbedarf für Pausen', '<strong>Kurz</strong>', 'Moderat', 'Deutlich länger'],
    ['Sicherheit bei Panne', 'Auf dich gestellt', '<strong>Gut</strong>', '<strong>Sehr gut</strong>'],
    ['Übernachtung', 'Überall möglich', 'Meist unproblematisch', 'Trekkingplätze oft zu klein'],
    ['Kosten pro Person', 'Am höchsten', '<strong>Geteilte Unterkunft</strong>', '<strong>Am günstigsten</strong>'],
    ['Erfahrungsdichte', '<strong>Sehr hoch</strong>', 'Hoch', 'Geteilt, aber gesellig'],
  ],
  note: 'Für die erste Tour ist zu zweit meist ideal: genug Sicherheit, geteiltes Gepäck, aber noch keine echte Gruppenlogistik.',
})}

${h2('Allein fahren', 'allein')}
<p>
  Solo-Bikepacking hat einen Ruf zwischen heldenhaft und leichtsinnig. Beides trifft nicht zu. Es ist
  vor allem eines: einfacher. Du fährst los, wann du willst, hältst an, wo du willst, und musst dich
  mit niemandem darüber einigen, ob heute noch 20 Kilometer drin sind.
</p>

${doDont({
  doTitle: 'Dafür spricht',
  doItems: [
    '<strong>Kein Abstimmungsaufwand.</strong> Keine Terminfindung, keine Kompromissroute, keine Diskussion an jeder Kreuzung',
    '<strong>Dein Tempo.</strong> Du fährst so schnell oder so langsam, wie es dir an diesem Tag entspricht',
    '<strong>Spontaneität.</strong> Ein See, ein Café, ein Umweg – du entscheidest in drei Sekunden',
    '<strong>Intensität.</strong> Ohne Gesprächspartner nimmst du die Umgebung nachweislich anders wahr',
    '<strong>Kontakt zu anderen.</strong> Allein Reisende werden viel häufiger angesprochen als Gruppen',
  ],
  dontTitle: 'Dagegen spricht',
  dontItems: [
    'Das gesamte Gepäck trägst du allein – Zelt, Kocher, Werkzeug, Erste-Hilfe-Set',
    'Bei einer ernsteren Panne oder Verletzung bist du auf dich gestellt',
    'Das Rad kann nie unbeaufsichtigt bleiben, auch nicht kurz im Supermarkt',
    'Abende können lang werden, vor allem bei Regen im Zelt',
    'Übernachtungen sind pro Person teurer',
  ],
})}

${h3('Wenn du allein fährst', 'allein-regeln')}
${checklist([
  '<strong>Route und Schlafplätze an eine Vertrauensperson schicken</strong> – mit den geplanten Etappen und einem groben Zeitplan',
  '<strong>Täglich eine kurze Nachricht.</strong> Abends „bin da" reicht. Wenn sie ausbleibt, weiß jemand Bescheid',
  '<strong>Konservativer planen.</strong> Kürzere Etappen, mehr Puffer, früher am Ziel',
  '<strong>Vollständiges Reparatur-Kit.</strong> Du kannst nichts teilen und niemanden fragen',
  '<strong>Handy immer geladen halten.</strong> Nicht erst laden, wenn es unter 20 Prozent fällt',
  '<strong>Eine kleine Hüfttasche</strong> mit Ausweis, Karte und Bargeld, die du auch im Supermarkt am Körper trägst',
])}

${callout(
  'Solo ist nicht gefährlicher – aber unverzeihlicher',
  '<p>Die Statistik spricht nicht gegen Alleinfahren. Was sich ändert, ist die Fehlertoleranz: Ein gebrochenes Schaltauge ist zu zweit ein Ärgernis und allein eine ernste Planänderung. Deshalb gilt für Solo-Touren nicht „mehr Mut", sondern <strong>mehr Puffer</strong> – bei Etappenlänge, Ausrüstung und Ankunftszeit.</p>',
  'info'
)}

${h2('Zu zweit fahren', 'zweit')}
<p>
  Für die meisten Touren die beste Konstellation. Ihr teilt das Gepäck, ihr helft euch bei Pannen,
  und die Abstimmung bleibt überschaubar – zwei Menschen einigen sich schneller als fünf.
</p>

${h3('Was ihr teilt', 'teilen')}
${weightList({
  title: 'Gemeinschaftsausrüstung, aufgeteilt auf zwei',
  items: [
    { name: '2-Personen-Zelt statt zwei 1-Personen-Zelten', note: 'Einer trägt Innenzelt und Stangen, einer das Außenzelt', g: 900, tag: 'pflicht' },
    { name: 'Ein Kocher statt zwei', note: 'Inklusive Kartusche und Topf', g: 650, tag: 'pflicht' },
    { name: 'Ein Werkzeugset statt zwei', note: 'Multitool, Pumpe, Flickzeug – Schläuche aber doppelt', g: 350, tag: 'pflicht' },
    { name: 'Ein Erste-Hilfe-Set statt zwei', g: 180, tag: 'pflicht' },
    { name: 'Ein Fahrradschloss statt zwei', note: 'Beide Räder an einem Schloss', g: 500, tag: 'sinnvoll' },
    { name: 'Ein Ladegerät für beide', note: 'Mehrfach-USB-Netzteil', g: 150, tag: 'sinnvoll' },
    { name: 'Zahnpasta, Sonnencreme, Seife gemeinsam', g: 120, tag: 'sinnvoll' },
  ],
  totalLabel: 'Ersparnis pro Person',
})}
<p>
  Das sind etwa <strong>1,4 Kilo weniger pro Person</strong> – mehr, als jede Ultraleicht-Optimierung
  an Zelt und Schlafsack hergibt, und ohne einen Cent Mehrkosten.
</p>

${h3('Was ihr nicht teilt', 'nicht-teilen')}
${checklist(
  [
    '<strong>Ersatzschläuche.</strong> Jeder braucht die passende Größe für sein eigenes Rad',
    '<strong>Ersatz-Schaltauge.</strong> Rahmenspezifisch – zwei verschiedene Räder, zwei verschiedene Teile',
    '<strong>Powerbank.</strong> Eine für zwei reicht selten, und getrennt zu laden ist flexibler',
    '<strong>Stirnlampe.</strong> Beide brauchen nachts Licht, oft gleichzeitig',
    '<strong>Regenjacke und Isolationsschicht.</strong> Klingt selbstverständlich, wird trotzdem regelmäßig vergessen',
  ],
  { tone: 'dont' }
)}

${h3('Die Absprachen, die vorher stattfinden sollten', 'absprachen')}
${table({
  head: ['Frage', 'Warum sie vorher geklärt wird'],
  rows: [
    ['Wie viele Kilometer wollen wir pro Tag?', 'Der häufigste Konfliktpunkt überhaupt'],
    ['Zelt oder feste Unterkunft?', 'Unterschiedliche Erwartungen sind teuer und frustrierend'],
    ['Wer navigiert?', 'Zwei Geräte, zwei Meinungen an jeder Kreuzung kosten Nerven'],
    ['Fahren wir zusammen oder jeder sein Tempo?', 'Am Berg getrennt und oben wieder zusammen ist oft die beste Lösung'],
    ['Was passiert, wenn einer nicht mehr kann?', 'Vorher besprochen ist es Planung, unterwegs ist es ein Streit'],
    ['Wie teilen wir Kosten?', 'Gemeinsame Kasse spart tägliche Abrechnung'],
  ],
  note: 'Diese sechs Fragen in einem Abend zu klären, verhindert etwa neunzig Prozent aller Konflikte auf Tour.',
})}

${callout(
  'Die Berg-Regel',
  '<p>Unterschiedlich starke Fahrende sollten am Berg nicht zusammenbleiben. Wer wartet, kühlt aus; wer sich anpasst, überlastet sich. Die bewährte Lösung: <strong>Jeder fährt den Anstieg im eigenen Tempo, oben wird gewartet.</strong> Das ist für beide angenehmer als ein Kompromisstempo, das niemandem passt – und der Schnellere hat oben eine Pause verdient.</p>',
  'tip'
)}

${h2('In der Gruppe fahren', 'gruppe')}
<p>
  Ab drei Personen ändert sich die Logistik grundlegend. Alles dauert länger – nicht wegen der
  Menschen, sondern wegen der Multiplikation: Wenn jeder alle zwei Stunden einmal muss, hält die
  Gruppe alle vierzig Minuten.
</p>

${table({
  head: ['Was', 'Allein', 'Zu dritt', 'Zu fünft'],
  rows: [
    ['Morgens startklar', '45 Min.', '60 Min.', '80 Min.'],
    ['Pause unterwegs', '5 Min.', '12 Min.', '20 Min.'],
    ['Einkaufen', '20 Min.', '30 Min.', '45 Min.'],
    ['Abends aufgebaut', '30 Min.', '45 Min.', '60 Min.'],
    ['<strong>Nebenzeit pro Tag</strong>', '<strong>ca. 2 Std.</strong>', '<strong>ca. 3 Std.</strong>', '<strong>ca. 4 Std.</strong>'],
  ],
  note: 'Die praktische Konsequenz: Plane in einer Fünfergruppe rund 25 bis 30 Prozent weniger Kilometer als allein – bei gleicher Fitness.',
})}

${h3('Was in der Gruppe funktioniert', 'gruppe-regeln')}
${checklist([
  '<strong>Einer plant, alle stimmen vorher zu.</strong> Planung im Konsens unterwegs funktioniert nicht',
  '<strong>Feste Treffpunkte statt Zusammenbleiben.</strong> Nach jedem Anstieg, an jeder größeren Kreuzung, am Einkaufsort',
  '<strong>Der Langsamste fährt vorn.</strong> Dann bestimmt er das Tempo, ohne hinterherzuhecheln',
  '<strong>Gemeinschaftsausrüstung schriftlich aufteilen.</strong> Sonst haben drei Leute einen Kocher und niemand das Erste-Hilfe-Set',
  '<strong>Gemeinsame Kasse.</strong> Einer zahlt, alle legen am Anfang gleich viel ein',
  '<strong>Trekkingplätze vorher prüfen.</strong> Viele haben nur zwei bis fünf Stellplätze – für eine Fünfergruppe oft zu klein',
  '<strong>Ab vier Personen zwei Kocher.</strong> Sonst wartet die Hälfte eine Stunde aufs Essen',
])}

${h2('Mit unterschiedlicher Fitness fahren', 'fitness')}
<p>
  Der häufigste Grund, warum gemeinsame Touren schiefgehen. Die Lösungen sind unspektakulär, aber
  wirksam:
</p>
${checklist([
  '<strong>Gepäck ungleich verteilen.</strong> Die stärkere Person trägt das Zelt und den Kocher. Das gleicht mehr aus als jedes Tempo-Zugeständnis',
  '<strong>Übersetzung angleichen.</strong> Wer schwächer ist, braucht den leichteren Gang – nicht mehr Willenskraft',
  '<strong>Etappen nach dem Schwächsten planen,</strong> nicht nach dem Durchschnitt',
  '<strong>E-Bike ist eine legitime Lösung.</strong> Wenn eine Person sonst nicht mitkäme, ist ein E-Bike besser als keine gemeinsame Tour',
  '<strong>Windschatten nutzen.</strong> Die stärkere Person fährt vorn – das spart der hinteren 20 bis 30 Prozent Kraft',
  '<strong>Ehrlich sein, bevor es zu spät ist.</strong> „Ich kann nicht mehr" nach 20 Kilometern ist planbar, nach 90 nicht',
])}

${callout(
  'Für die erste Tour',
  '<p>Zu zweit mit einer Person, die schon einmal draußen geschlafen hat, ist der einfachste denkbare Einstieg. Wenn niemand zur Verfügung steht: allein. Eine große Gruppe für die erste Tour ist die schwierigste Variante – weil du dich dann neben der ungewohnten Situation auch noch mit Gruppendynamik beschäftigst.</p>',
  'tip'
)}
`;

module.exports = article({
  href: '/unterwegs/allein-oder-gruppe.html',
  kicker: 'Unterwegs · Konstellation',
  title: 'Allein, zu zweit oder in der Gruppe',
  metaTitle: 'Bikepacking allein oder in der Gruppe? Der ehrliche Vergleich | Sattelfest',
  description:
    'Wie die Gruppengröße eine Bikepacking-Tour verändert: Solo-Regeln, was ihr zu zweit teilen könnt (1,4 kg pro Person), Gruppenlogistik ab drei Personen und wie ihr mit unterschiedlicher Fitness umgeht.',
  lead:
    'Die Gruppengröße verändert eine Tour stärker als jedes Ausrüstungsteil – Tempo, Planung, Gepäckgewicht und Sicherheit hängen daran.',
  meta: [
    { icon: 'users', text: '9 Minuten Lesezeit' },
    { icon: 'weight', text: 'Mit Gewichtsersparnis' },
    { icon: 'check', text: 'Absprachen vor der Tour' },
  ],
  toc: [
    { label: 'Die drei Konstellationen im Vergleich', id: 'vergleich' },
    { label: 'Allein fahren', id: 'allein' },
    { label: 'Zu zweit fahren', id: 'zweit' },
    { label: 'In der Gruppe fahren', id: 'gruppe' },
    { label: 'Mit unterschiedlicher Fitness fahren', id: 'fitness' },
  ],
  content,
  faq: [
    {
      q: 'Ist Bikepacking allein gefährlich?',
      a: '<p>Nicht grundsätzlich. Was sich ändert, ist die Fehlertoleranz: Ein gebrochenes Schaltauge ist zu zweit ein Ärgernis und allein eine ernste Planänderung. Für Solo-Touren gilt deshalb nicht „mehr Mut“, sondern mehr Puffer – kürzere Etappen, vollständiges Reparatur-Kit, frühere Ankunft und eine Vertrauensperson, die Route und Zeitplan kennt.</p>',
    },
    {
      q: 'Wie viel Gewicht spart man zu zweit?',
      a: '<p>Rund 1,4 Kilogramm pro Person. Geteilt werden ein 2-Personen-Zelt statt zweier Einzelzelte, ein Kocher, ein Werkzeugset, ein Erste-Hilfe-Set, ein Schloss und ein Ladegerät. Das ist mehr, als jede Ultraleicht-Optimierung an Zelt und Schlafsack hergibt – und es kostet keinen Cent.</p>',
    },
    {
      q: 'Was sollte man vor einer gemeinsamen Tour klären?',
      a: '<p>Sechs Dinge: gewünschte Tageskilometer, Zelt oder feste Unterkunft, wer navigiert, ob man zusammen oder im eigenen Tempo fährt, was passiert wenn jemand nicht mehr kann, und wie die Kosten geteilt werden. Diese Fragen an einem Abend zu klären verhindert etwa neunzig Prozent aller Konflikte unterwegs.</p>',
    },
    {
      q: 'Wie viel langsamer ist man in der Gruppe?',
      a: '<p>Deutlich. Die Nebenzeit pro Tag steigt von rund zwei Stunden allein auf drei zu dritt und vier zu fünft – Pausen, Einkauf, Auf- und Abbau multiplizieren sich. Plane in einer Fünfergruppe rund 25 bis 30 Prozent weniger Kilometer als allein, bei gleicher Fitness.</p>',
    },
    {
      q: 'Wie fährt man mit unterschiedlich starken Leuten zusammen?',
      a: '<p>Gepäck ungleich verteilen (die stärkere Person nimmt Zelt und Kocher), Übersetzung angleichen, Etappen nach dem Schwächsten planen und am Berg getrennt fahren mit Treffpunkt oben. Windschatten spart der hinteren Person 20 bis 30 Prozent Kraft. Ein E-Bike ist eine legitime Lösung, wenn jemand sonst gar nicht mitkäme.</p>',
    },
  ],
  related: [
    { href: '/unterwegs/sicherheit-notfall.html', label: 'Sicherheit & Notfall' },
    { href: '/ausruestung/packliste.html', label: 'Die Packliste mit Gewichten' },
    { href: '/routen/route-selbst-planen.html', label: 'Route selbst planen' },
    { href: '/unterwegs/training-vorbereitung.html', label: 'Training & Vorbereitung' },
  ],
});
