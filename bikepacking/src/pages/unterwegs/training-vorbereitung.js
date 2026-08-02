'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, steps,
} = require('../../components');

const content = `
<p class="lead-p">
  Für eine Wochenendtour mit 60 Kilometern pro Tag brauchst du kein Training – normale Alltagsfitness
  reicht. Ab etwa 80 Kilometern täglich über mehrere Tage wird es anders. Acht Wochen mit drei
  Einheiten pro Woche genügen dann vollkommen.
</p>

${stats([
  { value: '8', label: 'Wochen', note: 'Reichen für eine Tour mit 80 bis 100 km am Tag.' },
  { value: '3', label: 'Einheiten pro Woche', note: 'Mehr bringt für Tourenfahrer wenig.' },
  { value: '1', label: 'Lange Fahrt', note: 'Die wichtigste Einheit der Woche.' },
])}

${h2('Brauchst du überhaupt Training?', 'ob')}
${table({
  head: ['Deine Tour', 'Trainingsbedarf', 'Vorbereitung'],
  rows: [
    ['1 Nacht, 40 – 60 km/Tag', '<strong>Keiner</strong>', 'Eine Testfahrt mit Gepäck genügt'],
    ['2 – 3 Nächte, 60 – 80 km/Tag', 'Gering', '4 Wochen mit 2 Einheiten pro Woche'],
    ['4 – 7 Nächte, 80 – 100 km/Tag', 'Mittel', '<strong>8 Wochen mit 3 Einheiten</strong>'],
    ['Über eine Woche, 100+ km/Tag', 'Deutlich', '12 Wochen mit strukturiertem Aufbau'],
    ['Alpenüberquerung', 'Hoch', '12 Wochen mit Höhenmeter-Fokus'],
  ],
  note: 'Wichtiger als die reine Kondition ist die Gewöhnung an die Sitzposition. Wer sechs Stunden am Stück im Sattel sitzen kann, schafft auch die Kilometer.',
})}

${callout(
  'Was wirklich limitiert',
  '<p>Auf Bikepacking-Touren geht die Kraft selten aus – der Körper stellt sich nach zwei Tagen um. Was tatsächlich Touren abbricht, sind <strong>Sitzbeschwerden, taube Hände und Knieprobleme</strong>. Deshalb ist eine lange Fahrt mit vollem Gepäck vor der Tour wertvoller als zehn kurze Intervall-Einheiten: Sie zeigt dir, was an deiner Sitzposition nicht stimmt.</p>',
  'tip'
)}

${h2('Der Acht-Wochen-Plan', 'plan')}
<p>
  Drei Einheiten pro Woche: eine lange Fahrt, eine mittlere mit Höhenmetern, eine kurze lockere. Der
  Umfang steigt in vier Blöcken von je zwei Wochen, dazwischen jeweils eine leichtere Woche.
</p>

${table({
  head: ['Woche', 'Lange Fahrt', 'Mittlere Fahrt', 'Lockere Fahrt', 'Wochenumfang'],
  rows: [
    ['1', '40 km', '25 km, 300 hm', '20 km', '85 km'],
    ['2', '50 km', '30 km, 400 hm', '20 km', '100 km'],
    ['3', '65 km', '35 km, 500 hm', '25 km', '125 km'],
    ['4', '<strong>50 km (Entlastung)</strong>', '25 km', '20 km', '95 km'],
    ['5', '80 km', '40 km, 600 hm', '25 km', '145 km'],
    ['6', '<strong>95 km mit Gepäck</strong>', '45 km, 700 hm', '25 km', '165 km'],
    ['7', '<strong>2 Tage à 70 km mit Gepäck</strong>', '35 km', '20 km', '195 km'],
    ['8', '40 km locker', '25 km locker', 'Ruhe', '65 km'],
  ],
  note: 'Woche 7 ist die entscheidende: zwei aufeinanderfolgende Tage mit vollem Gepäck. Sie zeigt dir, wie sich der zweite Tag anfühlt – die Erfahrung, die keine Einzelfahrt liefert.',
})}

${h3('Die drei Einheiten im Detail', 'einheiten')}
${steps([
  {
    title: 'Die lange Fahrt',
    meta: 'Wichtigste Einheit · Wochenende',
    text:
      'Gleichmäßiges Tempo, bei dem du dich noch unterhalten könntest. Es geht nicht um Geschwindigkeit, sondern um Zeit im Sattel. Ab Woche 5 mit vollem Gepäck fahren – das ändert Sitzposition, Fahrgefühl und Kraftbedarf spürbar.',
    list: [
      'Tempo: du kannst sprechen, ohne nach Luft zu ringen',
      'Alle 90 Minuten essen, alle 45 Minuten trinken – wie auf Tour',
      'Ab Woche 5 mit Gepäck, auf dem Untergrund, den du auf Tour fahren wirst',
    ],
  },
  {
    title: 'Die mittlere Fahrt mit Höhenmetern',
    meta: 'Unter der Woche · 1,5 – 2,5 Std.',
    text:
      'Hier trainierst du das, was auf Tour am meisten kostet: Anstiege mit Gewicht. Fahre die Anstiege sitzend in einem Gang, den du dauerhaft treten kannst – Wiegetritt kostet unverhältnismäßig viel Kraft.',
    list: [
      'Trittfrequenz am Berg nicht unter 65 Umdrehungen fallen lassen',
      'Wenn das nicht geht: Übersetzung ändern, nicht härter treten',
      'Ab Woche 5 ebenfalls mit Gepäck',
    ],
  },
  {
    title: 'Die lockere Fahrt',
    meta: 'Regeneration · 45 – 75 Min.',
    text:
      'Bewusst langsam, ohne Ehrgeiz. Diese Einheit hat den Zweck, die Muskulatur zu durchbluten und die Sitzgewöhnung aufrechtzuerhalten. Wer sie zu schnell fährt, macht daraus eine dritte harte Einheit – und ermüdet.',
    list: [
      'Flach, ohne Anstiege',
      'Gerne als Alltagsfahrt zur Arbeit',
      'Kein Gepäck nötig',
    ],
  },
])}

${h2('Was neben dem Radfahren hilft', 'ergaenzung')}
${table({
  head: ['Übung', 'Wofür', 'Häufigkeit'],
  rows: [
    ['Planks (Unterarmstütz), 3 × 45 Sek.', 'Rumpfstabilität – entlastet Nacken und Hände', '2 × pro Woche'],
    ['Seitstütz, 2 × 30 Sek. je Seite', 'Seitliche Rumpfmuskulatur', '2 × pro Woche'],
    ['Kniebeugen, 3 × 12', 'Beinkraft für Anstiege mit Gepäck', '2 × pro Woche'],
    ['Ausfallschritte, 3 × 10 je Seite', 'Einbeinige Stabilität', '1 – 2 × pro Woche'],
    ['Hüftbeuger dehnen, 2 × 45 Sek.', 'Gegen Rückenschmerzen im Sattel', 'Täglich'],
    ['Brustmuskulatur dehnen', 'Gegen Nackenverspannung', 'Täglich'],
    ['Nackenmobilisation', 'Für die aufrechte Kopfhaltung', 'Täglich'],
  ],
  note: 'Zwanzig Minuten zweimal pro Woche genügen. Der Nutzen liegt nicht in der Kraft, sondern darin, dass Rumpf und Schultern das Gewicht des Oberkörpers tragen statt der Hände.',
})}

${h2('Die Generalprobe', 'generalprobe')}
<p>
  Zwei bis drei Wochen vor der Tour: eine Übernachtungstour mit der kompletten Ausrüstung, die du
  auch mitnehmen wirst. Nicht als Trainingseinheit, sondern als Test.
</p>
${checklist([
  '<strong>Vollständige Ausrüstung,</strong> genau wie geplant – auch das Zelt, auch den Kocher',
  '<strong>Übernachtung draußen,</strong> nicht zu Hause im Garten (obwohl auch das besser als nichts ist)',
  '<strong>Notiere alles, was stört:</strong> jede Scheuerstelle, jedes Klappern, jedes fehlende Teil',
  '<strong>Miss deine tatsächliche Zeit</strong> für Auf- und Abbau, Packen, Einkaufen',
  '<strong>Teste dein Essen:</strong> Nichts ist ärgerlicher als eine Trekkingmahlzeit, die du nicht magst',
  '<strong>Prüfe die Akkulaufzeit</strong> unter realen Bedingungen',
])}

${callout(
  'Trainingsfehler Nummer eins',
  '<p>Zu spät mit Gepäck zu fahren. Ein Rad mit zehn Kilo Gepäck fährt sich anders: Es lenkt träger, es beschleunigt langsamer, es fordert am Berg mehr, und die Sitzposition ändert sich messbar. Wer erst am ersten Tourtag mit vollem Gepäck fährt, verbringt diesen Tag mit Umgewöhnung statt mit Genuss. <strong>Ab Woche 5 gehört das Gepäck aufs Rad.</strong></p>',
  'warn'
)}

${h2('Wenn du wenig Zeit hast', 'wenig-zeit')}
<p>
  Wer nicht acht Wochen investieren kann oder will, sollte drei Dinge priorisieren – in dieser
  Reihenfolge:
</p>
${checklist([
  '<strong>1. Eine lange Fahrt mit vollem Gepäck.</strong> Mindestens 60 Kilometer, mindestens vier Stunden. Sie deckt alle Ausrüstungs- und Sitzprobleme auf.',
  '<strong>2. Zwei aufeinanderfolgende Tage.</strong> Der zweite Tag fühlt sich anders an als der erste – diese Erfahrung ist durch nichts zu ersetzen.',
  '<strong>3. Ein paar Anstiege mit Gepäck.</strong> Um zu prüfen, ob deine Übersetzung reicht.',
])}
<p>
  Alles darüber hinaus ist Feinschliff. Wer diese drei Dinge gemacht hat, ist für eine
  Wochenendtour vollständig vorbereitet – und weiß, worauf er sich bei einer längeren einstellen muss.
</p>

${h2('Während der Tour', 'waehrend')}
${checklist([
  '<strong>Tag 1 bewusst zu langsam fahren.</strong> Der häufigste Fehler ist, den ersten Tag mit voller Motivation zu übertreiben.',
  '<strong>Tag 3 bis 5 sind der Einbruch.</strong> Muskelkater, schlechter Schlaf, Gewöhnung. Plane hier 10 bis 15 Prozent weniger.',
  '<strong>Ab Tag 6 kommt die Tourform.</strong> Der Körper hat sich umgestellt, die Etappen dürfen wieder länger werden.',
  '<strong>Abends fünf Minuten dehnen</strong> – Hüftbeuger und Oberschenkelrückseite. Das reicht.',
  '<strong>Genug essen.</strong> Ein Kaloriendefizit über drei Tage zeigt sich als plötzliche, unerklärliche Erschöpfung.',
  '<strong>Einen Ruhetag einplanen</strong>, sobald die Tour länger als fünf Tage dauert.',
])}
`;

module.exports = article({
  href: '/unterwegs/training-vorbereitung.html',
  kicker: 'Unterwegs · Vorbereitung',
  title: 'Training & Vorbereitung',
  metaTitle: 'Bikepacking Training: 8-Wochen-Plan für die erste lange Tour | Sattelfest',
  description:
    'Bikepacking-Training: Wann du überhaupt trainieren musst, der Acht-Wochen-Plan mit drei Einheiten pro Woche, ergänzende Übungen für Rumpf und Beine, die Generalprobe und was während der Tour zählt.',
  lead:
    'Für 60 Kilometer am Tag reicht Alltagsfitness. Ab 80 Kilometern über mehrere Tage genügen acht Wochen mit drei Einheiten.',
  meta: [
    { icon: 'trend', text: '9 Minuten Lesezeit' },
    { icon: 'check', text: 'Mit 8-Wochen-Plan' },
    { icon: 'bike', text: 'Inklusive Generalprobe' },
  ],
  toc: [
    { label: 'Brauchst du überhaupt Training?', id: 'ob' },
    { label: 'Der Acht-Wochen-Plan', id: 'plan' },
    { label: 'Was neben dem Radfahren hilft', id: 'ergaenzung' },
    { label: 'Die Generalprobe', id: 'generalprobe' },
    { label: 'Wenn du wenig Zeit hast', id: 'wenig-zeit' },
    { label: 'Während der Tour', id: 'waehrend' },
  ],
  content,
  faq: [
    {
      q: 'Muss ich für eine Bikepacking-Tour trainieren?',
      a: '<p>Für eine Wochenendtour mit 40 bis 60 Kilometern pro Tag reicht normale Alltagsfitness – eine Testfahrt mit Gepäck genügt als Vorbereitung. Ab etwa 80 Kilometern täglich über mehrere Tage sind acht Wochen mit drei Einheiten pro Woche sinnvoll. Für eine Alpenüberquerung solltest du zwölf Wochen mit Höhenmeter-Schwerpunkt einplanen.</p>',
    },
    {
      q: 'Wie sieht ein sinnvoller Trainingsplan aus?',
      a: '<p>Drei Einheiten pro Woche: eine lange Fahrt am Wochenende (steigend von 40 auf 95 Kilometer), eine mittlere Fahrt mit Höhenmetern unter der Woche und eine kurze lockere Regenerationsfahrt. Der Umfang steigt über acht Wochen, mit einer Entlastungswoche in Woche 4. In Woche 7 folgen zwei aufeinanderfolgende Tage mit vollem Gepäck.</p>',
    },
    {
      q: 'Ab wann sollte ich mit Gepäck trainieren?',
      a: '<p>Spätestens ab Woche 5, also drei bis vier Wochen vor der Tour. Ein Rad mit zehn Kilo Gepäck lenkt träger, beschleunigt langsamer und fordert am Berg deutlich mehr. Wer erst am ersten Tourtag mit vollem Gepäck fährt, verbringt diesen Tag mit Umgewöhnung statt mit Genuss.</p>',
    },
    {
      q: 'Was limitiert auf einer Bikepacking-Tour wirklich?',
      a: '<p>Nicht die Kondition – der Körper stellt sich nach zwei Tagen um. Touren scheitern an Sitzbeschwerden, tauben Händen und Knieproblemen. Deshalb ist eine lange Fahrt mit vollem Gepäck vor der Tour wertvoller als zehn Intervalleinheiten: Sie deckt auf, was an deiner Sitzposition und deiner Ausrüstung nicht stimmt.</p>',
    },
    {
      q: 'Was mache ich, wenn ich nur wenig Zeit zum Vorbereiten habe?',
      a: '<p>Drei Dinge, in dieser Reihenfolge: eine lange Fahrt mit vollem Gepäck über mindestens 60 Kilometer und vier Stunden, zwei aufeinanderfolgende Fahrtage (der zweite fühlt sich anders an als der erste) und ein paar Anstiege mit Gepäck, um die Übersetzung zu prüfen. Damit bist du für eine Wochenendtour vollständig vorbereitet.</p>',
    },
  ],
  related: [
    { href: '/unterwegs/koerper-beschwerden.html', label: 'Sitzbeschwerden, Hände & Knie' },
    { href: '/einstieg/tagesetappen-planen.html', label: 'Wie weit kommst du am Tag?' },
    { href: '/einstieg/erste-tour-fahrplan.html', label: 'Fahrplan: In 7 Schritten zur ersten Tour' },
    { href: '/routen/wasser-verpflegung.html', label: 'Wasser & Verpflegung unterwegs' },
  ],
});
