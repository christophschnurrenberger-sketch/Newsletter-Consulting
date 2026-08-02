'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, steps,
} = require('../../components');

const content = `
<p class="lead-p">
  Eine gute Route entsteht nicht dadurch, dass man zwei Punkte in einen Planer eingibt und auf
  „Route berechnen“ klickt. Sie entsteht rückwärts: erst die Schlafplätze, dann die Etappen, dann
  die Linie dazwischen. Wer in dieser Reihenfolge arbeitet, plant Touren, die auch fahrbar sind.
</p>

${stats([
  { value: 'Rückwärts', label: 'Die Planungsrichtung', note: 'Schlafplätze zuerst, Strecke zuletzt.' },
  { value: '2 Std.', label: 'Aufwand', note: 'Für eine Wochenendtour, inklusive Prüfung.' },
  { value: '1', label: 'Ausstieg pro Tag', note: 'Ein Bahnhof je Etappe ist Pflicht.' },
])}

${h2('Die Reihenfolge, die funktioniert', 'reihenfolge')}
${steps([
  {
    title: 'Grobe Richtung und Dauer festlegen',
    meta: 'Schritt 1 · 10 Minuten',
    text:
      'Wie viele Tage hast du, und in welche Richtung willst du? Rechne mit einer realistischen Tagesdistanz – für Einsteiger 50 bis 70 Kilometer bei moderaten Höhenmetern. Multipliziere: drei Tage à 60 Kilometer sind 180 Kilometer, nicht 300.',
    list: [
      'Rundtour oder Streckentour? Rundtouren sparen die Rückfahrt, Streckentouren sind abwechslungsreicher',
      'Bei Streckentouren: Start und Ziel müssen per Bahn erreichbar sein',
      'Plane einen Tag Puffer für Wetter, Panne oder einen schönen See',
    ],
  },
  {
    title: 'Schlafplätze suchen – vor der Strecke',
    meta: 'Schritt 2 · 30 Minuten',
    text:
      'Das ist der eigentliche Kern der Planung. Trage Campingplätze, Trekkingplätze und Unterkünfte in einer Karte ein, die etwa im richtigen Abstand liegen. Diese Punkte bestimmen deine Etappen – nicht umgekehrt.',
    list: [
      'Trekkingplätze über die Länderportale suchen, Campingplätze über die üblichen Verzeichnisse',
      'Prüfe Öffnungssaison und ob eine Buchung nötig ist',
      'Notiere zu jedem Platz eine Alternative in 10 bis 15 Kilometern Entfernung',
    ],
  },
  {
    title: 'Etappen zwischen den Schlafplätzen ziehen',
    meta: 'Schritt 3 · 40 Minuten',
    text:
      'Jetzt erst kommt der Routenplaner. Verbinde die Übernachtungspunkte und schau dir das Ergebnis kritisch an – automatisch berechnete Routen bevorzugen kurze Wege, nicht schöne oder fahrbare.',
    list: [
      'Wegpunkte setzen, um die Route über gewünschte Orte zu führen',
      'Höhenprofil und Steigungsprozente prüfen, nicht nur die Gesamt-Höhenmeter',
      'Untergrundangaben durchsehen: „Pfad“ und „Wanderweg“ heißen mit Gepäck oft Schieben',
    ],
  },
  {
    title: 'Versorgung und Ausstiege eintragen',
    meta: 'Schritt 4 · 20 Minuten',
    text:
      'Markiere pro Tag mindestens zwei Einkaufsmöglichkeiten und einen Bahnhof. Der Bahnhof ist deine Versicherung: Er macht aus einer Tour, die schiefgehen könnte, eine Tour, die notfalls einfach kürzer wird.',
    list: [
      'Supermärkte mit Öffnungszeiten – sonntags ist in Deutschland fast alles zu',
      'Wasserstellen: Friedhöfe, Sportplätze, Campingplätze, Gaststätten',
      'Bahnhöfe entlang der Route, mit Notiz, ob dort auch abends noch Züge fahren',
    ],
  },
  {
    title: 'Offline verfügbar machen und prüfen',
    meta: 'Schritt 5 · 15 Minuten',
    text:
      'Karte der gesamten Region herunterladen, GPX lokal speichern, Screenshots der Übersicht anlegen. Dann Flugmodus einschalten und prüfen, ob wirklich alles funktioniert.',
    list: [
      'Nicht nur den Streckenkorridor laden – Umleitungen führen schnell aus dem Bereich',
      'Adressen der Schlafplätze auf Papier notieren',
      'Route auch an eine zweite Person schicken',
    ],
  },
])}

${callout(
  'Warum die Reihenfolge zählt',
  '<p>Wer zuerst die Strecke plant und danach nach Schlafplätzen sucht, landet regelmäßig in der Situation, dass am Etappenende nichts ist – und dann noch 25 Kilometer im Dunkeln fahren muss. Wer umgekehrt vorgeht, hat eine Route, die vielleicht ein paar Kilometer länger ist, aber abends immer irgendwo endet.</p>',
  'tip'
)}

${h2('Höhenprofile richtig lesen', 'hoehenprofil')}
<p>
  Die Gesamt-Höhenmeter sagen wenig darüber aus, wie hart ein Tag wird. Entscheidend ist, wie sie
  verteilt sind.
</p>
${table({
  head: ['Profil', 'Beispiel', 'Wie es sich anfühlt'],
  rows: [
    ['Gleichmäßig wellig', '600 hm auf 70 km in vielen kleinen Wellen', 'Angenehm – der Körper kommt in einen Rhythmus'],
    ['Ein langer Anstieg', '600 hm am Stück über 12 km', 'Anstrengend, aber planbar – Tempo einteilen'],
    ['Mehrere steile Rampen', '600 hm in vier Rampen mit 12 % Steigung', '<strong>Härtester Fall</strong> – jede Rampe kostet komplett'],
    ['Alles am Ende des Tages', '600 hm auf den letzten 15 km', 'Ungünstig – plane den Anstieg an den Anfang'],
  ],
  note: 'Achte auf die maximale Steigung, nicht nur auf die Summe. Über 10 Prozent wird es mit Gepäck auch für Trainierte zäh, über 15 Prozent wird geschoben.',
})}

${checklist([
  '<strong>Steigungsprozente prüfen,</strong> nicht nur Höhenmeter. 200 hm auf 800 m Strecke sind 25 Prozent – das ist eine Wand.',
  '<strong>Anstiege möglichst an den Vormittag legen.</strong> Der Kopf ist frisch, und es ist kühler.',
  '<strong>Nach Möglichkeit einen langen Anstieg statt vieler kurzer.</strong> Rampen kosten mehr Kraft als ihre Höhenmeter vermuten lassen.',
  '<strong>Abfahrten kosten auch Zeit.</strong> Mit Gepäck fährst du vorsichtiger – rechne nicht mit 50 km/h.',
])}

${h2('Untergrund richtig einschätzen', 'untergrund')}
${table({
  head: ['Angabe im Planer', 'Realität mit Gepäck', 'Tempoverlust'],
  rows: [
    ['Straße / Asphalt', 'Problemlos', '0 %'],
    ['Radweg befestigt', 'Problemlos, oft schöner als Straße', '0 – 5 %'],
    ['Wirtschaftsweg / Feldweg', 'Meist gut fahrbar, nach Regen matschig', '10 – 20 %'],
    ['Schotterweg / Forststraße', 'Gut fahrbar ab 40 mm Reifenbreite', '15 – 25 %'],
    ['Pfad / Wanderweg', '<strong>Oft schieben, mit schmalen Reifen unfahrbar</strong>', '40 – 70 %'],
    ['Singletrail', '<strong>Mit Gepäck anspruchsvoll bis unfahrbar</strong>', '50 – 80 %'],
    ['Sand', '<strong>Schieben</strong>', '70 – 90 %'],
  ],
  note: 'Wenn ein Routenplaner mehr als 10 Prozent „Pfad“ oder „Wanderweg“ ausweist, plane die Route um – oder rechne mit deutlich längerer Fahrzeit.',
})}

${callout(
  'Die Trittsteine der Routenplanung',
  '<p>Radfernwege sind für die ersten Touren aus einem Grund ideal: Sie sind <strong>durchgehend ausgeschildert</strong>. Das spart Akku, Konzentration und Zeit an Kreuzungen. Auch wenn du eigene Wege planst, lohnt es sich, Radfernwege als Rückgrat zu nutzen und nur an interessanten Stellen abzuweichen. Die Schilder sind ein unterschätzter Komfortfaktor.</p>',
  'info'
)}

${h2('Die Prüfliste vor dem Losfahren', 'pruefliste')}
${checklist([
  '<strong>Sind alle Schlafplätze gebucht oder zumindest verfügbar?</strong> Öffnungssaison geprüft?',
  '<strong>Fährt die Fähre, die du eingeplant hast?</strong> Viele haben saisonale oder eingeschränkte Fahrpläne.',
  '<strong>Gibt es Baustellen oder Sperrungen?</strong> Die Portale der Radfernwege melden das meist.',
  '<strong>Liegt pro Tag ein Bahnhof an der Strecke?</strong> Fährt dort abends noch etwas?',
  '<strong>Sind zwei Einkaufsmöglichkeiten pro Tag markiert?</strong> Mit Öffnungszeiten – Sonntag beachten.',
  '<strong>Ist der Wasserbedarf abgedeckt?</strong> Vor allem für den Weg zu Trekkingplätzen.',
  '<strong>Funktioniert die Route im Flugmodus?</strong> Wirklich testen, nicht annehmen.',
  '<strong>Weiß jemand, wo du bist?</strong> Route und Schlafplätze an eine Vertrauensperson schicken.',
])}

${h2('Typische Planungsfehler', 'fehler')}
${checklist(
  [
    '<strong>Nach Kilometern planen statt nach Höhenmetern.</strong> Der häufigste Fehler überhaupt.',
    '<strong>Der automatisch berechneten Route vertrauen.</strong> Sie kennt keine gesperrten Privatwege und keine Treppen.',
    '<strong>Den letzten Tag zu lang planen.</strong> Ausgerechnet dann bist du müde und willst pünktlich am Bahnhof sein.',
    '<strong>Keine Alternative zum Schlafplatz haben.</strong> Wenn der geschlossen ist, wird der Abend lang.',
    '<strong>Sonntag als Einkaufstag einplanen.</strong> In Deutschland ist sonntags fast alles zu.',
    '<strong>Die Anreise vergessen.</strong> Drei Stunden Bahnfahrt am Freitagmorgen kürzen den ersten Tag erheblich.',
    '<strong>Fähren und Brücken nicht prüfen.</strong> Ein fehlender Flussübergang bedeutet schnell 25 Kilometer Umweg.',
  ],
  { tone: 'dont' }
)}

${h2('Fertige Routen als Abkürzung', 'fertig')}
<p>
  Für die ersten Touren spricht viel dafür, eine bestehende Route zu übernehmen statt selbst zu
  planen. Sie ist erprobt, ausgeschildert und dokumentiert.
</p>
${table({
  head: ['Quelle', 'Was du bekommst', 'Bewertung'],
  rows: [
    ['Deutsche Radfernwege', 'Beschilderte Routen mit Infrastruktur', '<strong>Ideal für den Einstieg</strong>'],
    ['Routenplaner-Communitys', 'Von Nutzern geteilte GPX-Tracks mit Fotos', 'Sehr nützlich, aber Qualität schwankt'],
    ['Bikepacking-Portale', 'Kuratierte Offroad-Routen mit Beschreibung', 'Oft anspruchsvoll – Schwierigkeit prüfen'],
    ['Tourismusverbände', 'Regionale Routen mit Unterkunftslisten', 'Gut recherchiert, teils sehr gemütlich'],
    ['EuroVelo', '17 europäische Fernrouten mit Nummerierung', 'Für längere Touren die beste Basis'],
  ],
  note: 'Auch bei fertigen Routen gilt: Prüfe die Etappenlänge und passe sie an dich an. Die vorgeschlagenen Etappen sind oft für Fortgeschrittene ausgelegt.',
})}
`;

module.exports = article({
  href: '/routen/route-selbst-planen.html',
  kicker: 'Routen · Planung',
  title: 'Route selbst planen',
  metaTitle: 'Bikepacking-Route planen: In 5 Schritten zum fahrbaren GPX | Sattelfest',
  description:
    'Bikepacking-Route richtig planen: warum du rückwärts planst (Schlafplätze zuerst), Höhenprofile richtig lesen, Untergrund realistisch einschätzen, die Prüfliste vor dem Losfahren und typische Planungsfehler.',
  lead:
    'Eine gute Route entsteht rückwärts: erst die Schlafplätze, dann die Etappen, dann die Linie dazwischen.',
  meta: [
    { icon: 'route', text: '10 Minuten Lesezeit' },
    { icon: 'mountain', text: 'Mit Höhenprofil-Kunde' },
    { icon: 'check', text: 'Inklusive Prüfliste' },
  ],
  toc: [
    { label: 'Die Reihenfolge, die funktioniert', id: 'reihenfolge' },
    { label: 'Höhenprofile richtig lesen', id: 'hoehenprofil' },
    { label: 'Untergrund richtig einschätzen', id: 'untergrund' },
    { label: 'Die Prüfliste vor dem Losfahren', id: 'pruefliste' },
    { label: 'Typische Planungsfehler', id: 'fehler' },
    { label: 'Fertige Routen als Abkürzung', id: 'fertig' },
  ],
  content,
  faq: [
    {
      q: 'Wie plane ich eine Bikepacking-Route richtig?',
      a: '<p>Rückwärts: Erst die Schlafplätze suchen, dann die Etappen dazwischen ziehen, dann Versorgung und Ausstiege eintragen. Wer zuerst die Strecke plant und danach nach Übernachtungen sucht, landet regelmäßig ohne Schlafplatz am Etappenende und muss noch 25 Kilometer im Dunkeln fahren.</p>',
    },
    {
      q: 'Worauf muss ich beim Höhenprofil achten?',
      a: '<p>Auf die Steigungsprozente, nicht nur auf die Summe der Höhenmeter. 200 Höhenmeter auf 800 Metern Strecke sind 25 Prozent Steigung – eine Wand. Über 10 Prozent wird es mit Gepäck auch für Trainierte zäh, über 15 Prozent wird geschoben. Mehrere kurze steile Rampen sind härter als ein einzelner langer Anstieg mit gleicher Höhensumme.</p>',
    },
    {
      q: 'Wie erkenne ich, ob ein Weg mit Gepäck fahrbar ist?',
      a: '<p>An der Untergrundangabe des Routenplaners. Asphalt, Radweg und Schotterweg sind unproblematisch (ab 40 mm Reifenbreite), Wirtschaftswege werden nach Regen matschig. Kritisch sind „Pfad“, „Wanderweg“ und „Singletrail“ – dort verlierst du mit Gepäck 40 bis 80 Prozent Tempo oder musst schieben. Über 10 Prozent solcher Abschnitte solltest du umplanen.</p>',
    },
    {
      q: 'Warum brauche ich pro Tag einen Bahnhof an der Strecke?',
      a: '<p>Weil er deine Versicherung ist. Ein Bahnhof pro Etappe macht aus einer Tour, die schiefgehen könnte, eine Tour, die notfalls einfach kürzer wird – bei einer Panne, einem Wettersturz oder wenn die Kraft nicht reicht. Notiere dir dabei auch, ob dort abends noch Züge fahren.</p>',
    },
    {
      q: 'Sollte ich eine fertige Route nehmen oder selbst planen?',
      a: '<p>Für die ersten Touren spricht viel für fertige Routen: Deutsche Radfernwege sind durchgehend ausgeschildert, was Akku, Konzentration und Zeit an Kreuzungen spart. Prüfe aber die vorgeschlagenen Etappenlängen – die sind oft für Fortgeschrittene ausgelegt und müssen an die eigene Leistung angepasst werden.</p>',
    },
  ],
  related: [
    { href: '/einstieg/tagesetappen-planen.html', label: 'Wie weit kommst du am Tag?' },
    { href: '/ausruestung/navigation.html', label: 'Navigation: Apps, GPS & Karten' },
    { href: '/routen/einsteiger-routen-deutschland.html', label: 'Einsteiger-Routen in Deutschland' },
    { href: '/tools/etappen-rechner.html', label: 'Etappen- & Gewichts-Rechner' },
  ],
});
