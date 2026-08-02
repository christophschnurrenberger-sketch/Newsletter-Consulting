'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, doDont,
} = require('../../components');

const content = `
<p class="lead-p">
  Drei Baustellen brechen Touren ab: der Hintern, die Hände und die Knie. Alle drei sind lösbar –
  aber fast nie unterwegs. Sie werden zu Hause gelöst, in den Wochen davor, durch Sitzposition,
  Ausrüstung und ein bisschen Vorbereitung.
</p>

${stats([
  { value: '3', label: 'Hauptprobleme', note: 'Sitzfläche, Hände, Knie – in dieser Reihenfolge.' },
  { value: 'Vorher', label: 'Der Lösungszeitpunkt', note: 'Unterwegs lässt sich fast nichts mehr reparieren.' },
  { value: '2 Std.', label: 'Der Testlauf', note: 'So lange muss eine Sitzposition schmerzfrei halten.' },
])}

${h2('Die Sitzfläche', 'sitzflaeche')}
<p>
  Der häufigste Grund für abgebrochene Touren, und der mit den meisten Missverständnissen. Ein
  breiterer, weicherer Sattel ist fast nie die Lösung – oft ist er die Ursache.
</p>

${h3('Warum weiche Sättel das Problem verschlimmern', 'weich')}
<p>
  Beim Radfahren soll dein Gewicht auf den <strong>Sitzbeinhöckern</strong> ruhen, den beiden
  knöchernen Punkten am unteren Becken. Ein weicher, stark gepolsterter Sattel gibt unter diesen
  Punkten nach – dadurch sinkt das Becken ein und das Gewicht verlagert sich auf das Weichgewebe
  dazwischen. Genau dort liegen Nerven und Blutgefäße. Ergebnis: Taubheit und Schmerzen.
</p>

${table({
  head: ['Beschwerde', 'Wahrscheinliche Ursache', 'Lösung'],
  rows: [
    [
      'Druckschmerz an den Sitzknochen',
      'Sattel zu schmal für dein Becken',
      'Sitzknochenabstand messen lassen (viele Händler machen das kostenlos), breiteren Sattel wählen',
    ],
    [
      'Taubheit im Dammbereich',
      'Zu weicher Sattel oder Sattelnase zu hoch',
      'Härterer Sattel mit Aussparung, Sattelnase 1 – 2 Grad nach unten',
    ],
    [
      'Wundscheuern innen an den Oberschenkeln',
      'Reibung durch Nähte oder Feuchtigkeit',
      'Sitzcreme, Radhose ohne Unterwäsche, Sattel etwas tiefer',
    ],
    [
      'Schmerzen erst nach 3 Stunden',
      'Sattel zu hoch – das Becken kippt bei jedem Tritt',
      'Sattelhöhe 5 mm reduzieren und erneut testen',
    ],
    [
      'Entzündete Stellen, Pickel',
      'Feuchtes Polster über Stunden',
      'Nach jeder Etappe umziehen, Hose auslüften, waschen',
    ],
  ],
  note: 'Der Sitzknochenabstand liegt typischerweise zwischen 100 und 145 Millimetern. Der Sattel sollte etwa 20 bis 30 Millimeter breiter sein als dieser Abstand.',
})}

${doDont({
  doTitle: 'Das hilft wirklich',
  doItems: [
    '<strong>Sitzknochenabstand messen lassen</strong> und einen dazu passenden Sattel wählen – kostenlos bei vielen Händlern',
    '<strong>Sitzcreme ab dem zweiten Tag</strong>, dünn aufs Polster, nicht auf die Haut',
    '<strong>Radhose ohne Unterwäsche</strong>, ausnahmslos',
    '<strong>Alle 20 bis 30 Minuten kurz aus dem Sattel</strong> – zehn Sekunden reichen, um die Durchblutung wiederherzustellen',
    '<strong>Nach jeder Etappe sofort umziehen</strong> und die Hose auslüften',
    '<strong>Sattel in kleinen Schritten justieren</strong> – 5 Millimeter Höhe und 1 Grad Neigung sind spürbar',
  ],
  dontTitle: 'Das macht es schlimmer',
  dontItems: [
    'Einen breiten Gelsattel kaufen, weil es weh tut',
    'Sattelbezug aus Gel darüberziehen – verstärkt das Einsinken',
    'Unterwäsche unter der Radhose tragen',
    'In der feuchten Hose sitzen bleiben, bis das Zelt steht',
    'Die Sattelhöhe alle 20 Kilometer neu verstellen',
    'Weiterfahren, wenn eine Stelle bereits offen ist',
  ],
})}

${callout(
  'Die harte Wahrheit über neue Sättel',
  '<p>Ein neuer Sattel braucht drei bis fünf Ausfahrten, bis du beurteilen kannst, ob er passt. Die ersten beiden Male tut fast jeder Sattel weh, weil sich das Gewebe anpassen muss. Teste einen neuen Sattel deshalb <strong>nicht auf der Tour</strong>, sondern vier bis sechs Wochen vorher – und dann mindestens auf einer zweistündigen Fahrt.</p>',
  'warn'
)}

${h2('Die Hände', 'haende')}
<p>
  Taube Finger nach zwei Stunden sind kein Zeichen von Schwäche, sondern von Druck auf den
  Ulnarnerv – er verläuft durch die Handkante und wird beim Aufstützen abgeklemmt. Wenn nichts
  passiert, kann die Taubheit Wochen anhalten.
</p>

${table({
  head: ['Maßnahme', 'Wirkung', 'Aufwand'],
  rows: [
    ['Griffposition alle 15 Minuten wechseln', '<strong>Sehr hoch</strong>', 'Keiner – nur Gewohnheit'],
    ['Handschuhe mit Gelpolster an der Handkante', 'Hoch', '30 – 60 €'],
    ['Dickeres Lenkerband (doppelt wickeln)', 'Hoch', '25 – 45 €'],
    ['Ergonomische Griffe (Flatbar)', 'Hoch', '30 – 70 €'],
    ['Lenkeraufsatz für mehr Positionen', 'Hoch', '30 – 60 €'],
    ['Lenker 1 – 2 cm höher stellen', '<strong>Sehr hoch</strong>', 'Kostenlos, Spacer umstecken'],
    ['Gewicht vom Lenker nehmen', 'Mittel', 'Umpacken'],
    ['Ellbogen leicht beugen statt durchstrecken', '<strong>Sehr hoch</strong>', 'Keiner'],
  ],
  note: 'Die beiden wirksamsten Maßnahmen kosten nichts: Griffposition regelmäßig wechseln und die Ellbogen leicht gebeugt halten. Durchgestreckte Arme leiten jeden Schlag direkt in die Handgelenke.',
})}

${checklist([
  '<strong>Wenn Finger taub werden: sofort Position ändern</strong> und die Hände ausschütteln. Nicht durchhalten.',
  '<strong>Anhaltende Taubheit über Tage ist ein Warnzeichen</strong> – dann brauchst du eine andere Sitzposition, keine besseren Handschuhe.',
  '<strong>Bei Rennlenkern:</strong> Oberlenker, Bremsgriffe und Unterlenker regelmäßig durchwechseln.',
  '<strong>Bei Flatbar:</strong> Ein Lenkeraufsatz („Bar Ends“) schafft die zweite Position, die sonst fehlt.',
])}

${h2('Die Knie', 'knie')}
<p>
  Knieschmerzen entstehen beim Radfahren fast nie durch zu viel Belastung, sondern durch die falsche
  Belastung – meist durch eine falsche Sattelhöhe oder zu hohe Trittkraft bei zu niedriger Trittfrequenz.
</p>

${table({
  head: ['Wo es weh tut', 'Typische Ursache', 'Korrektur'],
  rows: [
    [
      '<strong>Vorne, unter der Kniescheibe</strong>',
      'Sattel zu niedrig oder zu weit vorn',
      'Sattel 5 mm höher, ggf. 5 mm nach hinten',
    ],
    [
      '<strong>Hinten, in der Kniekehle</strong>',
      'Sattel zu hoch',
      'Sattel 5 mm tiefer',
    ],
    [
      'Außen am Knie',
      'Cleat-Position oder Beinachse',
      'Cleats leicht nach außen versetzen, Fußstellung prüfen',
    ],
    [
      'Innen am Knie',
      'Füße zu weit außen, Q-Faktor',
      'Cleats nach innen versetzen',
    ],
    [
      'Diffuse Schmerzen ab Tag 2',
      'Zu schwere Gänge, zu niedrige Trittfrequenz',
      'Leichter fahren, 80 – 90 Umdrehungen anstreben',
    ],
    [
      'Schmerzen nur bergauf',
      'Übersetzung zu schwer',
      'Größeres Ritzelpaket – die wirksamste Einzelmaßnahme',
    ],
  ],
  note: 'Die Faustregel für die Sattelhöhe: Mit der Ferse auf dem Pedal in der untersten Position muss das Bein gerade durchgestreckt sein. Beim normalen Tritt bleibt dann eine leichte Beugung.',
})}

${callout(
  'Trittfrequenz statt Kraft',
  '<p>Der häufigste Knie-Fehler beim Bikepacking ist, mit zu schwerem Gang zu fahren. Mit zehn Kilo Gepäck fühlt sich jeder Gang schwerer an – wer dann nicht runterschaltet, tritt bei 55 Umdrehungen pro Minute den Berg hoch und belastet die Kniegelenke erheblich. Ziel sind <strong>80 bis 90 Umdrehungen</strong> in der Ebene und mindestens 65 bis 70 am Berg. Wenn das nicht geht, ist die Übersetzung zu schwer.</p>',
  'tip'
)}

${h2('Nacken und Rücken', 'nacken')}
${checklist([
  '<strong>Lenker höher stellen</strong> ist die wirksamste Einzelmaßnahme – Spacer unter den Vorbau oder kürzerer Vorbau mit mehr Steigung',
  '<strong>Alle 20 Minuten den Kopf bewegen:</strong> nach links und rechts drehen, Schultern kreisen',
  '<strong>Rucksack vermeiden.</strong> Er belastet genau die Muskulatur, die ohnehin arbeitet',
  '<strong>Rumpfmuskulatur vorbereiten:</strong> Zwei Mal pro Woche Planks in den Wochen vor der Tour',
  '<strong>Abends dehnen:</strong> Hüftbeuger, Brustmuskulatur, Nacken – zehn Minuten reichen',
  '<strong>Bei akuten Nackenschmerzen:</strong> Lenker sofort höher, notfalls mit gedrehtem Vorbau',
])}

${h2('Die Erste-Hilfe-Ausstattung für Beschwerden', 'ausstattung')}
${table({
  head: ['Was', 'Wofür', 'Gewicht'],
  rows: [
    ['Sitzcreme', 'Ab Tag 2 unverzichtbar', '60 g'],
    ['Blasenpflaster', 'Für Füße und wundgescheuerte Stellen', '20 g'],
    ['Wund- und Heilsalbe', 'Kleine offene Stellen an der Sitzfläche', '30 g'],
    ['Ibuprofen oder vergleichbar', 'Akute Entzündung – nicht als Dauerlösung', '10 g'],
    ['Kinesiotape', 'Knie und Achillessehne stabilisieren', '40 g'],
    ['Desinfektionsmittel', 'Schürfwunden nach Sturz', '30 g'],
    ['Elastische Binde', 'Verstauchungen', '50 g'],
  ],
  note: 'Wichtig: Schmerzmittel überdecken Warnsignale. Wer mit Ibuprofen weiterfährt, weil das Knie weh tut, riskiert eine ernsthafte Verletzung. Sie sind für den Notfall da, nicht für den Alltag auf Tour.',
})}

${h2('Die Vorbereitung, die alles löst', 'vorbereitung')}
${checklist([
  '<strong>Eine Testfahrt über zwei Stunden</strong> mit vollem Gepäck, vier Wochen vor der Tour. Alles, was da weh tut, tut auf Tour dreifach weh.',
  '<strong>Sitzposition in Ruhe optimieren</strong> – jeweils eine Änderung, dann testen. Nicht drei auf einmal.',
  '<strong>Bikefitting erwägen</strong>, wenn wiederkehrende Beschwerden bestehen: 120 bis 250 Euro, aber oft die Lösung nach Jahren.',
  '<strong>Übersetzung prüfen.</strong> Ein größeres Ritzelpaket löst Knieprobleme zuverlässiger als jede Salbe.',
  '<strong>Sechs bis acht Wochen mit steigendem Umfang fahren</strong>, statt untrainiert 300 Kilometer zu planen.',
])}
`;

module.exports = article({
  href: '/unterwegs/koerper-beschwerden.html',
  kicker: 'Unterwegs · Körper',
  title: 'Sitzbeschwerden, Hände & Knie',
  metaTitle: 'Bikepacking Beschwerden: Sitzschmerzen, taube Hände, Knieprobleme | Sattelfest',
  description:
    'Die drei Baustellen, die Touren abbrechen: Sitzbeschwerden, taube Hände und Knieschmerzen. Warum weiche Sättel das Problem verschlimmern, was bei Taubheit hilft und wie Sattelhöhe und Trittfrequenz das Knie schützen.',
  lead:
    'Der Hintern, die Hände, die Knie. Alle drei sind lösbar – aber fast nie unterwegs. Sie werden zu Hause gelöst, in den Wochen davor.',
  meta: [
    { icon: 'shield', text: '10 Minuten Lesezeit' },
    { icon: 'check', text: 'Mit Ursachen-Tabellen' },
    { icon: 'bike', text: 'Sitzposition korrigieren' },
  ],
  toc: [
    { label: 'Die Sitzfläche', id: 'sitzflaeche' },
    { label: 'Die Hände', id: 'haende' },
    { label: 'Die Knie', id: 'knie' },
    { label: 'Nacken und Rücken', id: 'nacken' },
    { label: 'Erste-Hilfe-Ausstattung', id: 'ausstattung' },
    { label: 'Die Vorbereitung, die alles löst', id: 'vorbereitung' },
  ],
  content,
  faq: [
    {
      q: 'Hilft ein breiter, weicher Sattel gegen Sitzschmerzen?',
      a: '<p>Meist nicht – oft ist er die Ursache. Dein Gewicht soll auf den Sitzbeinhöckern ruhen. Ein stark gepolsterter Sattel gibt unter diesen Punkten nach, das Becken sinkt ein und das Gewicht verlagert sich auf das Weichgewebe dazwischen, wo Nerven und Blutgefäße liegen. Lass stattdessen deinen Sitzknochenabstand messen und wähle einen passend breiten, eher festen Sattel.</p>',
    },
    {
      q: 'Was tun gegen taube Hände beim Radfahren?',
      a: '<p>Die zwei wirksamsten Maßnahmen kosten nichts: Griffposition alle 15 Minuten wechseln und die Ellbogen leicht gebeugt halten statt durchzustrecken. Zusätzlich helfen ein höher gestellter Lenker, gepolsterte Handschuhe, dickeres Lenkerband und ein Lenkeraufsatz für mehr Positionen. Anhaltende Taubheit über Tage ist ein Warnzeichen für eine falsche Sitzposition.</p>',
    },
    {
      q: 'Woher kommen Knieschmerzen beim Bikepacking?',
      a: '<p>Fast nie von zu viel Belastung, sondern von falscher. Schmerzen vorn unter der Kniescheibe deuten auf einen zu niedrigen Sattel hin, Schmerzen in der Kniekehle auf einen zu hohen. Diffuse Schmerzen ab Tag zwei entstehen meist durch zu schwere Gänge: Ziel sind 80 bis 90 Umdrehungen pro Minute in der Ebene und mindestens 65 bis 70 am Berg.</p>',
    },
    {
      q: 'Wie stelle ich die Sattelhöhe richtig ein?',
      a: '<p>Faustregel: Mit der Ferse auf dem Pedal in der untersten Position muss das Bein gerade durchgestreckt sein. Beim normalen Tritt mit dem Fußballen bleibt dann eine leichte Beugung. Ändere die Höhe immer nur in Schritten von 5 Millimetern und teste dazwischen – mehrere Änderungen gleichzeitig machen die Ursachensuche unmöglich.</p>',
    },
    {
      q: 'Kann ich einen neuen Sattel auf der Tour einfahren?',
      a: '<p>Nein. Ein neuer Sattel braucht drei bis fünf Ausfahrten, bis du beurteilen kannst, ob er passt – die ersten beiden Male tut fast jeder Sattel weh. Teste ihn vier bis sechs Wochen vor der Tour und mindestens auf einer zweistündigen Fahrt. Ein Sattel, der zwei Stunden schmerzfrei hält, hält auch acht.</p>',
    },
  ],
  related: [
    { href: '/unterwegs/training-vorbereitung.html', label: 'Training & Vorbereitung' },
    { href: '/ausruestung/kleidung.html', label: 'Kleidung: Das Zwiebelprinzip' },
    { href: '/einstieg/welches-fahrrad.html', label: 'Welches Rad passt zum Bikepacking?' },
    { href: '/unterwegs/tagesablauf.html', label: 'Der Tagesablauf auf Tour' },
  ],
});
