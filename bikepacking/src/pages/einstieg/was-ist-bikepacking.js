'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, doDont,
} = require('../../components');

const content = `
<p class="lead-p">
  Bikepacking ist Radreisen ohne Gepäckträger. Das klingt nach einer Nebensächlichkeit, ist aber
  die Entscheidung, aus der alles andere folgt: dein Taschenset, dein Gepäckvolumen, deine
  Etappenlänge und am Ende sogar, welche Wege du fahren kannst.
</p>

${stats([
  { value: '0', label: 'Gepäckträger', note: 'Taschen werden direkt an Rahmen, Sattel und Lenker geschnallt.' },
  { value: '20–45 l', label: 'Typisches Volumen', note: 'Klassische Radreise mit Packtaschen: 60–100 Liter.' },
  { value: '1 Nacht', label: 'Reicht für den Anfang', note: 'Die erste Tour muss keine Weltreise sein.' },
])}

${h2('Der Unterschied zur klassischen Radreise', 'unterschied')}
<p>
  Bei der klassischen Radreise trägt ein Gepäckträger zwei Packtaschen, oft noch zwei vorn und eine
  Rolle obendrauf. Beim Bikepacking hängen die Taschen ohne Träger direkt am Rad – in der Satteltasche
  hinter dem Sattel, in der Rolle am Lenker, in der Tasche im Rahmendreieck.
</p>
<p>
  Der Grund für diese Bauweise ist nicht Mode, sondern Physik: Ohne seitlich ausladende Packtaschen
  bleibt das Rad schmal, das Gewicht sitzt näher an der Mittellinie, und du kommst durch enge Trails,
  über Wurzeln und Serpentinen. Genau dafür wurde Bikepacking erfunden – von Leuten, die mit dem
  Mountainbike mehrtägig ins Gelände wollten.
</p>

${table({
  head: ['', 'Bikepacking', 'Klassische Radreise'],
  rows: [
    ['Gepäckträger', 'Keiner nötig', 'Pflicht'],
    ['Volumen', '20–45 Liter', '60–100 Liter'],
    ['Rad', 'Fast jedes – auch ohne Ösen', 'Braucht Montagepunkte'],
    ['Gelände', 'Trails, Schotter, enge Wege', 'Asphalt und breite Wege'],
    ['Packen', 'Rollverschlüsse, alles muss rein', 'Deckel auf, Zeug rein'],
    ['Zugriff unterwegs', 'Umständlich – Satteltasche bleibt zu', 'Jederzeit, auch im Fahren'],
    ['Preis Taschenset', '250 – 700 €', '120 – 350 € plus Träger'],
    ['Gewicht Taschen leer', '900 g – 1,8 kg', '2,5 – 4 kg inkl. Träger'],
  ],
  note: 'Beide Systeme lassen sich mischen. Genau das ist für viele Einsteiger die beste Lösung – dazu unten mehr.',
})}

${callout(
  'Bikepacking ist kein Sportabzeichen',
  '<p>Es gibt keine Mindestdistanz, ab der es „richtiges“ Bikepacking ist. Eine Nacht draußen, 45 Kilometer von zu Hause entfernt, mit einem Schlafsack in einer Lenkerrolle – das ist eine vollwertige Bikepacking-Tour. Wer dir etwas anderes erzählt, verkauft dir vermutlich gerade ein Rad.</p>',
  'tip'
)}

${h2('Woher der Reiz kommt', 'reiz')}
<p>
  Der Unterschied zwischen einer Tagestour und einer Übernachtungstour ist größer als der zwischen
  50 und 500 Kilometern. Sobald du nicht mehr abends zu Hause bist, ändert sich, wie du auf die
  Landschaft schaust. Der Hügel ist nicht mehr Trainingsreiz, sondern Weg. Der See ist nicht mehr
  Fotomotiv, sondern Waschgelegenheit. Und der Wetterbericht ist kein Hintergrundrauschen mehr.
</p>
<p>
  Dazu kommt ein ganz praktischer Punkt: Mit dem Rad legst du an einem Wochenende 150 bis 250
  Kilometer zurück. Das ist weit genug, um in eine völlig andere Gegend zu kommen, und nah genug,
  um Sonntagabend wieder am Schreibtischstuhl vorbeizulaufen.
</p>

${h2('Die drei Spielarten', 'spielarten')}
${h3('Credit-Card-Bikepacking', 'creditcard')}
<p>
  Du fährst mit minimalem Gepäck und schläfst in Pensionen oder Hostels. Kein Zelt, kein Schlafsack,
  kein Kocher: 4 bis 6 Kilo reichen. Der leichteste Einstieg überhaupt, weil du dafür fast keine
  Ausrüstung brauchst – und der teuerste pro Nacht.
</p>
${h3('Klassisches Bikepacking', 'klassisch')}
<p>
  Zelt oder Tarp, Schlafsack, Isomatte, vielleicht ein Kocher. 8 bis 12 Kilo Gepäck. Du übernachtest
  auf Campingplätzen, Trekkingplätzen oder in Schutzhütten. Das ist der Bereich, in dem sich die
  meisten bewegen, und die Bauform, für die das ganze Taschensystem entwickelt wurde.
</p>
${h3('Bikepacking-Rennen', 'racing')}
<p>
  Selbstversorgt, ohne fremde Hilfe, oft ohne geplante Übernachtung. Transcontinental, Atlas Mountain
  Race, Silk Road Mountain Race. Das ist ein eigener Sport mit eigenen Regeln – und für die ersten
  Jahre ausdrücklich nicht dein Maßstab. Wenn dir jemand erzählt, ein 400-Gramm-Schlafsack sei
  Pflicht, redet er über diese Welt, nicht über deine.
</p>

${h2('Was du zum Start wirklich brauchst', 'start')}
<p>
  Die ehrliche Antwort lautet: erstaunlich wenig, und fast alles davon liegt bei den meisten Menschen
  schon im Keller oder lässt sich leihen. Der teuerste Fehler beim Einstieg ist, das komplette Setup
  zu kaufen, bevor man eine einzige Nacht draußen war.
</p>

${doDont({
  doTitle: 'Das brauchst du für die erste Nacht',
  doItems: [
    'Ein fahrtüchtiges Rad – <strong>deins reicht fast sicher</strong>',
    'Irgendeine Möglichkeit, Gepäck zu befestigen: Satteltasche, Lenkerrolle oder ein Gepäckträger',
    'Schlafsack und Isomatte (leihen ist völlig in Ordnung)',
    'Regenjacke, Licht vorn und hinten, Werkzeug für einen Platten',
    'Einen Ort, an dem du <strong>legal</strong> schlafen darfst',
  ],
  dontTitle: 'Das brauchst du jetzt noch nicht',
  dontItems: [
    'Ein neues Gravelbike – der mit Abstand teuerste Anfängerfehler',
    'Ein komplettes Taschenset für 600 Euro',
    'Einen Titan-Kocher, ein Ultraleicht-Zelt, einen 800er-Daunenschlafsack',
    'GPS-Radcomputer – dein Handy mit Offline-Karte reicht für die erste Tour',
    'Tubeless-Umbau, neue Übersetzung, andere Reifen',
  ],
})}

${callout(
  'Die Zwei-Nächte-Regel',
  '<p>Kaufe nichts Teures, bevor du <strong>zwei Nächte draußen</strong> warst. Nach der ersten Nacht weißt du, ob du überhaupt draußen schlafen willst. Nach der zweiten weißt du, was dich konkret gestört hat – und genau das ist die Einkaufsliste. Vorher kaufst du das, was in Videos gut aussah.</p>',
  'money'
)}

${h2('Die häufigsten Missverständnisse', 'missverstaendnisse')}
${checklist(
  [
    '<strong>„Ich brauche ein Gravelbike.“</strong> Nein. Ein Trekkingrad, ein Hardtail oder ein altes Tourenrad funktionieren genauso. Reifenfreiheit und funktionierende Bremsen zählen mehr als der Radtyp.',
    '<strong>„Bikepacking heißt Wildcampen.“</strong> Nein. In Deutschland ist Wildcampen fast überall verboten. Es gibt über 200 legale Trekkingplätze, dazu Campingplätze und Schutzhütten.',
    '<strong>„Ich muss 100 Kilometer am Tag schaffen.“</strong> Nein. 40 bis 60 Kilometer sind für die erste Tour ein guter Wert – vor allem, wenn du Zelt aufbauen und einkaufen willst.',
    '<strong>„Ohne Ultraleicht-Ausrüstung geht es nicht.“</strong> Doch. Ein 2-Kilo-Zelt kostet dich am Berg ein paar Minuten. Es kostet dich aber nicht die Tour.',
    '<strong>„Packtaschen sind altmodisch.“</strong> Nein. Auf Asphaltrouten sind sie praktischer, günstiger und wasserdichter als jedes Bikepacking-Set.',
  ],
  { tone: 'dont' }
)}

${h2('Wie du von hier aus weitermachst', 'weiter')}
<p>
  Wenn du die Seite von vorn nach hinten durchgehen willst, ist der
  <a href="/einstieg/erste-tour-fahrplan.html">Fahrplan in sieben Schritten</a> der richtige Einstieg.
  Wenn du wissen willst, ob dein Rad taugt, geht es bei
  <a href="/einstieg/welches-fahrrad.html">Welches Rad passt zum Bikepacking?</a> weiter. Und wenn
  du schlicht diesen Sommer eine Nacht draußen schlafen willst, ohne vorher 30 Artikel zu lesen:
  <a href="/routen/erstes-mikroabenteuer.html">Das erste Mikroabenteuer</a> ist die kürzeste Route
  zum Ziel.
</p>
`;

module.exports = article({
  href: '/einstieg/was-ist-bikepacking.html',
  kicker: 'Einstieg · Grundlagen',
  title: 'Was Bikepacking wirklich ist',
  metaTitle: 'Was ist Bikepacking? Definition, Unterschied zur Radreise & Einstieg | Sattelfest',
  description:
    'Bikepacking erklärt: der Unterschied zur klassischen Radreise mit Packtaschen, typisches Gepäckvolumen, die drei Spielarten und was du für die erste Nacht draußen wirklich brauchst.',
  lead:
    'Radreisen ohne Gepäckträger – warum diese eine Entscheidung über Taschen, Volumen, Etappenlänge und die Wege bestimmt, die du fahren kannst.',
  meta: [
    { icon: 'clock', text: '9 Minuten Lesezeit' },
    { icon: 'bike', text: 'Für alle, die noch nie draußen geschlafen haben' },
    { icon: 'bag', text: 'Mit Systemvergleich' },
  ],
  toc: [
    { label: 'Der Unterschied zur klassischen Radreise', id: 'unterschied' },
    { label: 'Woher der Reiz kommt', id: 'reiz' },
    { label: 'Die drei Spielarten', id: 'spielarten' },
    { label: 'Was du zum Start wirklich brauchst', id: 'start' },
    { label: 'Die häufigsten Missverständnisse', id: 'missverstaendnisse' },
  ],
  content,
  faq: [
    {
      q: 'Was ist der Unterschied zwischen Bikepacking und Radreisen?',
      a: '<p>Bikepacking kommt ohne Gepäckträger aus: Die Taschen werden direkt an Sattel, Lenker und Rahmen geschnallt. Das ergibt ein schmaleres Rad mit weniger Volumen (20 bis 45 Liter statt 60 bis 100) und funktioniert deshalb auch auf Trails und schmalen Wegen. Klassisches Radreisen mit Packtaschen bietet mehr Platz, besseren Zugriff und ist auf Asphalt oft die praktischere Wahl.</p>',
    },
    {
      q: 'Brauche ich ein spezielles Bikepacking-Rad?',
      a: '<p>Nein. Für die ersten Touren reicht praktisch jedes verkehrssichere Rad mit funktionierenden Bremsen und einer Übersetzung, die zu deinem Gelände passt. Genau das ist der Vorteil des Systems: Weil keine Gepäckträger-Ösen nötig sind, lässt sich fast jedes Rad bepacken. Kaufe erst ein neues Rad, wenn du nach zwei bis drei Touren konkret sagen kannst, was dir am alten fehlt.</p>',
    },
    {
      q: 'Wie viele Kilometer fährt man beim Bikepacking pro Tag?',
      a: '<p>Für Einsteigende sind 40 bis 70 Kilometer am Tag ein realistischer Wert, für erfahrene Fahrerinnen und Fahrer 80 bis 120. Entscheidend ist nicht die Fitness allein, sondern die Kombination aus Höhenmetern, Untergrund und Gepäck. Ein 50-Kilometer-Tag mit 1.000 Höhenmetern auf Schotter ist deutlich härter als 100 flache Asphaltkilometer.</p>',
    },
    {
      q: 'Ist Wildcampen beim Bikepacking erlaubt?',
      a: '<p>In Deutschland grundsätzlich nicht. Das freie Zelten in Wald und Flur ist in allen Bundesländern verboten oder stark eingeschränkt, in Bayern besonders streng. Es gibt aber über 200 offizielle Trekking- und Biwakplätze, die genau für diesen Zweck eingerichtet wurden und meist um 10 bis 15 Euro pro Nacht kosten. Dazu kommen Campingplätze und in manchen Regionen Schutzhütten.</p>',
    },
    {
      q: 'Was kostet der Einstieg ins Bikepacking?',
      a: '<p>Wenn du ein Rad, einen Schlafsack und eine Isomatte hast, kommst du mit 150 bis 250 Euro für zwei Taschen los. Ein vollständiges neues Setup inklusive Zelt, Schlafsystem und Taschenset liegt bei 900 bis 2.000 Euro. Wir haben drei Budgets vollständig durchgerechnet.</p>',
    },
  ],
  related: [
    { href: '/einstieg/erste-tour-fahrplan.html', label: 'Fahrplan: In 7 Schritten zur ersten Tour' },
    { href: '/einstieg/welches-fahrrad.html', label: 'Welches Rad passt zum Bikepacking?' },
    { href: '/taschen/taschensystem.html', label: 'Das Taschensystem verstehen' },
    { href: '/routen/erstes-mikroabenteuer.html', label: 'Das erste Mikroabenteuer (S24O)' },
  ],
});
