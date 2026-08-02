'use strict';

const article = require('../_article');
const {
  h2, callout, steps, table, checklist, stats,
} = require('../../components');

const content = `
<p class="lead-p">
  Die meisten Menschen scheitern nicht an der ersten Tour, sondern an der Vorbereitung darauf. Sie
  lesen sich sechs Wochen lang in Zeltmodelle ein und fahren nie los. Dieser Fahrplan dreht die
  Reihenfolge um: Die Ausrüstung kommt zum Schluss, das Losfahren steht am Anfang.
</p>

${stats([
  { value: '7', label: 'Schritte', note: 'Vom Entschluss bis zur Rückfahrt – in der richtigen Reihenfolge.' },
  { value: '3–5 Wochen', label: 'Vorlauf reicht', note: 'Mehr Vorbereitung macht die erste Tour nicht besser.' },
  { value: '1', label: 'Übernachtung', note: 'Alles Weitere ist eine Wiederholung dieser einen Nacht.' },
])}

${h2('Der Fahrplan', 'fahrplan')}
${steps([
  {
    title: 'Termin festlegen – vor allem anderen',
    meta: 'Woche 0 · 5 Minuten',
    text:
      'Such dir ein konkretes Wochenende und trage es in den Kalender ein. Nicht „im Sommer mal“, sondern ein Datum. Alles, was danach kommt, richtet sich daran aus. Ohne Termin bleibt Bikepacking ein Browser-Tab.',
    list: [
      'Ein Wochenende mit stabilem Wetter ist ideal – aber warte nicht auf perfekte Aussichten',
      'Freitagnachmittag bis Samstagabend reicht vollkommen',
      'Plane einen Ausweichtermin eine Woche später ein',
    ],
  },
  {
    title: 'Das Rad prüfen, nicht kaufen',
    meta: 'Woche 1 · 1 Stunde',
    text:
      'Nimm das Rad, das du hast. Prüfe Bremsen, Reifen, Kette und Schaltung – oder gib es für 60 bis 90 Euro in den Service. Ein Rad, das im Alltag zuverlässig läuft, läuft auch mit acht Kilo Gepäck.',
    list: [
      'Bremsbeläge: mindestens 2 mm Belag übrig',
      'Reifen: keine Risse in den Flanken, Profil vorhanden',
      'Kette: mit Kettenlehre prüfen oder beim Service messen lassen',
      'Speichen: alle gleich fest, kein Ping beim Anzupfen',
    ],
  },
  {
    title: 'Schlafplatz buchen',
    meta: 'Woche 1 · 20 Minuten',
    text:
      'Der Schlafplatz bestimmt die Route, nicht umgekehrt. Such dir einen Trekkingplatz oder Campingplatz 40 bis 70 Kilometer von zu Hause entfernt und reserviere. Damit hat die Tour ein Ziel und eine Länge.',
    list: [
      'Trekkingplätze sind meist von Mai bis Oktober geöffnet und kosten um 10 bis 15 Euro',
      'Campingplätze funktionieren immer und haben Duschen',
      'Für die allererste Nacht ist ein Platz mit Wasser und Toilette Gold wert',
    ],
  },
  {
    title: 'Gepäck zusammensuchen – erst dann kaufen',
    meta: 'Woche 2 · 2 Stunden',
    text:
      'Leg alles, was du mitnehmen willst, auf den Boden. Erst danach entscheidest du, wie es ans Rad kommt. Sehr oft stellt sich heraus: Für eine Nacht reichen eine Satteltasche und eine Lenkerrolle – oder sogar ein alter Gepäckträger mit zwei Packtaschen.',
    list: [
      'Schlafsack und Isomatte kannst du fast überall leihen – auch von Freunden',
      'Kaufe zuerst die Satteltasche, dann die Lenkerrolle, dann alles andere',
      'Was du dreimal in die Hand nimmst und wieder weglegst, bleibt zu Hause',
    ],
  },
  {
    title: 'Die Testfahrt um den Block',
    meta: 'Woche 3 · 45 Minuten',
    text:
      'Pack das Rad vollständig und fahre 20 bis 30 Kilometer. Das ist der wichtigste Schritt im ganzen Plan. Alles, was klappert, scheuert oder wackelt, findest du hier – und nicht bei Kilometer 40 im Regen.',
    list: [
      'Achte auf Fersenkontakt an der Satteltasche',
      'Prüfe, ob die Lenkerrolle die Züge einklemmt oder am Reifen schleift',
      'Fahre einmal im Wiegetritt und einmal schnell bergab',
      'Baue zu Hause einmal das Zelt auf, bevor du es einpackst',
    ],
  },
  {
    title: 'Route bauen und offline verfügbar machen',
    meta: 'Woche 3 · 1 Stunde',
    text:
      'Plane die Strecke mit einem Routenplaner, prüfe die Höhenmeter und lade die Karte offline aufs Handy. Notiere dir zwei Einkaufsmöglichkeiten und einen Bahnhof als Ausstiegsmöglichkeit.',
    list: [
      'Höhenmeter checken: 100 Hm kosten dich ungefähr so viel Kraft wie 9 flache Kilometer',
      'Offline-Karte herunterladen – Empfang ist im Wald keine Selbstverständlichkeit',
      'Route zusätzlich als Screenshot speichern, falls die App streikt',
    ],
  },
  {
    title: 'Losfahren – und unterwegs nichts beweisen',
    meta: 'Am Wochenende',
    text:
      'Starte früh, fahre langsam, iss regelmäßig. Sei vor Einbruch der Dunkelheit am Schlafplatz. Die erste Tour hat genau eine Aufgabe: dass du am nächsten Morgen aufwachst und Lust auf die zweite hast.',
    list: [
      'Ankunft am Ziel mindestens 90 Minuten vor Sonnenuntergang einplanen',
      'Alle 90 Minuten essen, auch wenn du keinen Hunger hast',
      'Wenn es nicht läuft: Bahnhof suchen. Abbrechen ist kein Scheitern.',
    ],
  },
])}

${callout(
  'Die Reihenfolge ist der Punkt',
  '<p>Fast alle Anleitungen fangen bei der Ausrüstung an. Das ist der Grund, warum so viele Leute ein Zeltmodell auswendig kennen und trotzdem nie draußen geschlafen haben. Termin, Schlafplatz und Testfahrt kosten zusammen weniger als zwei Stunden – und sie entscheiden über deine Tour weit mehr als die Wahl zwischen zwei Satteltaschen.</p>',
  'tip'
)}

${h2('Der Zeitplan im Überblick', 'zeitplan')}
${table({
  head: ['Wann', 'Was', 'Aufwand', 'Kosten'],
  rows: [
    ['Woche 0', 'Termin in den Kalender', '5 Min.', '0 €'],
    ['Woche 1', 'Rad prüfen oder Service', '1 Std.', '0 – 90 €'],
    ['Woche 1', 'Schlafplatz reservieren', '20 Min.', '10 – 25 €'],
    ['Woche 2', 'Ausrüstung sichten, Fehlendes leihen', '2 Std.', '0 – 200 €'],
    ['Woche 3', 'Testfahrt vollgepackt', '45 Min.', '0 €'],
    ['Woche 3', 'Route planen, offline speichern', '1 Std.', '0 €'],
    ['Tourtag', 'Fahren, schlafen, zurück', '1,5 Tage', '20 – 60 € Verpflegung'],
  ],
  note: 'Wer Schlafsack und Isomatte leiht und die vorhandenen Taschen nutzt, kommt für unter 60 Euro auf die erste Tour.',
})}

${h2('Die fünf Fehler, die fast alle machen', 'fehler')}
${checklist(
  [
    '<strong>Zu weit für den ersten Tag.</strong> 100 Kilometer klingen nach nicht viel, bis du Zelt aufbauen, einkaufen und kochen musst. 50 sind besser.',
    '<strong>Zu spät losgefahren.</strong> Im Dunkeln einen Zeltplatz suchen ist die zuverlässigste Methode, eine Tour zu ruinieren.',
    '<strong>Zu viel dabei.</strong> Fast jeder schleppt beim ersten Mal drei bis vier Kilo mit, die er nie anfasst.',
    '<strong>Zelt zum ersten Mal am Zielort aufgebaut.</strong> Übe es einmal im Wohnzimmer. Wirklich.',
    '<strong>Keine Ausstiegsmöglichkeit eingeplant.</strong> Ein Bahnhof auf halber Strecke nimmt enorm Druck raus.',
  ],
  { tone: 'dont' }
)}

${h2('Wenn du gar keine Ausrüstung hast', 'ohne-ausruestung')}
<p>
  Dann startest du mit einer Variante, die viel zu selten empfohlen wird: eine Nacht in einer Pension
  oder Jugendherberge. Du brauchst dafür kein Zelt, keinen Schlafsack, keine Isomatte und keinen
  Kocher – nur Wechselkleidung, Regenjacke und Werkzeug. Das passt in eine einzige Lenkerrolle.
</p>
<p>
  Der Erkenntniswert ist trotzdem hoch: Du weißt danach, wie sich dein Rad mit Gepäck fährt, wie weit
  du an einem Tag kommst und ob dir das Ganze überhaupt liegt. Erst danach lohnt sich die Frage nach
  dem <a href="/ausruestung/schlafsystem.html">Schlafsystem</a>.
</p>
`;

module.exports = article({
  href: '/einstieg/erste-tour-fahrplan.html',
  kicker: 'Einstieg · Fahrplan',
  title: 'In 7 Schritten zur ersten Bikepacking-Tour',
  metaTitle: 'Erste Bikepacking-Tour: Fahrplan in 7 Schritten für Anfänger | Sattelfest',
  description:
    'Der komplette Fahrplan für die erste Bikepacking-Tour: Termin, Radcheck, Schlafplatz, Ausrüstung, Testfahrt, Routenplanung und die fünf Fehler, die fast alle Einsteiger machen.',
  lead:
    'Nicht die Ausrüstung entscheidet über die erste Tour, sondern die Reihenfolge. Sieben Schritte, drei Wochen Vorlauf, ein Wochenende.',
  meta: [
    { icon: 'clock', text: '10 Minuten Lesezeit' },
    { icon: 'map', text: 'Mit Zeitplan und Kostenrahmen' },
    { icon: 'check', text: 'Ohne Kaufzwang' },
  ],
  toc: [
    { label: 'Der Fahrplan', id: 'fahrplan' },
    { label: 'Der Zeitplan im Überblick', id: 'zeitplan' },
    { label: 'Die fünf Fehler, die fast alle machen', id: 'fehler' },
    { label: 'Wenn du gar keine Ausrüstung hast', id: 'ohne-ausruestung' },
  ],
  content,
  faq: [
    {
      q: 'Wie lange sollte die erste Bikepacking-Tour sein?',
      a: '<p>Eine Übernachtung, 40 bis 70 Kilometer pro Tag. Das reicht völlig, um alles zu erleben, worum es beim Bikepacking geht – und es ist kurz genug, dass ein Regentag oder ein Platten die Tour nicht kippt. Wer mehr will, hängt bei der zweiten Tour einfach einen Tag dran.</p>',
    },
    {
      q: 'Muss ich vorher trainieren?',
      a: '<p>Für 50 Kilometer am Tag reicht normale Alltagsfitness. Sinnvoll ist trotzdem eine einzige lange Tagestour mit Gepäck vor der ersten Übernachtung – nicht wegen der Kondition, sondern weil du dabei merkst, ob Sattel, Sitzposition und Taschenbefestigung stimmen. Ab etwa 80 Kilometern pro Tag lohnt sich ein strukturierter Aufbau über sechs bis acht Wochen.</p>',
    },
    {
      q: 'Was mache ich, wenn Regen angesagt ist?',
      a: '<p>Fahren, wenn es Landregen ist und du eine funktionierende Regenjacke hast. Verschieben, wenn Gewitter oder Dauerregen mit unter 10 Grad angesagt sind – nasse Kälte ist der schnellste Weg zu einer Tour, die dir das Thema für immer verleidet. Deshalb steht im Fahrplan ein Ausweichtermin.</p>',
    },
    {
      q: 'Wie viel Geld muss ich für die erste Tour ausgeben?',
      a: '<p>Wenn du Rad, Schlafsack und Isomatte hast oder leihen kannst: 20 bis 60 Euro für Übernachtung und Verpflegung. Wenn du zwei Taschen kaufst, kommen 150 bis 250 Euro dazu. Ein komplettes neues Setup liegt bei 900 bis 2.000 Euro – aber genau das solltest du vor der ersten Tour nicht kaufen.</p>',
    },
  ],
  related: [
    { href: '/routen/erstes-mikroabenteuer.html', label: 'Das erste Mikroabenteuer (S24O)' },
    { href: '/taschen/taschensystem.html', label: 'Das Taschensystem verstehen' },
    { href: '/ausruestung/packliste.html', label: 'Die Packliste mit Gewichten' },
    { href: '/tools/packlisten-generator.html', label: 'Packlisten-Generator: 8 Fragen' },
  ],
});
