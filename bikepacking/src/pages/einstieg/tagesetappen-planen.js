'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, icon,
} = require('../../components');

const content = `
<p class="lead-p">
  Die Frage „Wie weit komme ich am Tag?“ wird fast immer falsch beantwortet – nicht aus Angeberei,
  sondern aus einem Denkfehler: Die meisten rechnen mit ihrer besten Tagestour ohne Gepäck. Beim
  Bikepacking kommen Gewicht, Untergrund, Höhenmeter, Zeltaufbau und Einkaufen dazu. Übrig bleiben
  etwa 60 Prozent.
</p>

${stats([
  { value: '≈ 60 %', label: 'deiner besten Tagestour', note: 'So viel bleibt beim Bikepacking typischerweise übrig.' },
  { value: '12 Min.', label: 'je 100 Höhenmeter', note: 'Zusätzliche Fahrzeit – Anstiege kosten Zeit, nicht Kilometer.' },
  { value: '2 Std.', label: 'gehen jeden Tag weg', note: 'Für Pausen, Einkauf, Zeltaufbau und Orientierung.' },
])}

${h2('Die Formel', 'formel')}
<p>
  Begrenzend ist auf Tour nicht die Strecke, sondern die <strong>Zeit</strong>. Deshalb rechnest du
  nicht mit Kilometern, sondern in zwei Schritten: erst dein Zeitbudget im Sattel, dann deine
  Reisegeschwindigkeit.
</p>

<blockquote>Tagesdistanz = (Zeitbudget − Zeit für die Anstiege) × Reisegeschwindigkeit</blockquote>

${h3('Schritt 1: Dein Zeitbudget', 'formel-zeit')}
<p>
  Der Ausgangspunkt ist die Strecke, die du an einem guten Tag <strong>ohne Gepäck</strong> schaffst.
  Bei einem typischen Schnitt von 20 km/h sagt sie dir, wie viele Stunden du im Sattel aushältst.
  Auf Tour sitzt du etwas länger – aber ruhiger.
</p>
${table({
  head: ['Faktor', 'Wert', 'Warum'],
  rows: [
    ['Referenztour ÷ 20 km/h', '= Stunden', '100 km an einem guten Tag entsprechen 5 Stunden im Sattel'],
    ['Tourfaktor', '× 1,2', 'Auf Mehrtagestouren sitzt man länger, aber langsamer'],
    ['Erste Tour', '× 0,85', 'Alles dauert länger, weil noch nichts Routine ist'],
    ['Viel Erfahrung', '× 1,1', 'Eingespielte Abläufe, weniger Leerlauf'],
    ['3 – 4 Tourtage', '× 0,95', 'Beginnende Ermüdung'],
    ['5 – 7 Tourtage', '× 0,90', 'Der klassische Einbruch ab Tag 3'],
    ['Über 7 Tourtage', '× 0,85', 'Dauerbelastung ohne vollständige Erholung'],
  ],
})}

${h3('Schritt 2: Deine Reisegeschwindigkeit', 'formel-tempo')}
${table({
  head: ['Untergrund', 'Schnitt beladen', 'Anmerkung'],
  rows: [
    ['Asphalt', '19 km/h', 'Fernradwege, Straßen'],
    ['Gemischt', '16 km/h', 'Asphalt plus Wald- und Wirtschaftswege'],
    ['Schotter und Trails', '12,5 km/h', 'Loser Untergrund kostet Tempo und Konzentration'],
    ['Gepäck über 6 kg', '−1,8 % je kg', 'Gedeckelt bei maximal −20 Prozent'],
  ],
  note: 'Das sind Reiseschnitte, keine Spitzenwerte: Sie enthalten Ampeln, Steigungen und das Suchen an Kreuzungen, aber keine echten Pausen.',
})}

${h3('Schritt 3: Die Anstiege abziehen', 'formel-hm')}
<p>
  Beladen bewältigst du etwa <strong>500 Höhenmeter pro Stunde</strong>. Diese Zeit geht von deinem
  Budget ab, bevor du die Distanz ausrechnest. 600 Höhenmeter kosten dich also rund 1 Stunde 12
  Minuten – und die fehlen dir für Kilometer.
</p>

${callout(
  'Ein Rechenbeispiel',
  '<p>Du fährst an einem guten Sonntag ohne Gepäck 100 Kilometer. Deine erste Bikepacking-Tour geht über drei Tage, gemischter Untergrund, 600 Höhenmeter am Tag, 10 Kilo Gepäck.</p>' +
    '<p><strong>Zeitbudget:</strong> 100 ÷ 20 = 5 Std. · Tourfaktor × 1,2 = 6,0 · erste Tour × 0,85 = 5,1 · drei Tage × 0,95 = <strong>4,85 Stunden</strong><br>' +
    '<strong>Tempo:</strong> gemischt 16 km/h · Gepäck (10 kg) × 0,93 = <strong>14,9 km/h</strong><br>' +
    '<strong>Anstiege:</strong> 600 ÷ 500 = <strong>1,2 Stunden</strong></p>' +
    '<p>Bleiben 4,85 − 1,2 = 3,65 Stunden zum Fahren. Mal 14,9 km/h ergibt <strong>rund 54 Kilometer</strong> – etwas mehr als die Hälfte deiner Referenzstrecke. Mit Pausen, Einkauf und Zeltaufbau ist das ein voller Tag von etwa acht Stunden.</p>',
  'info'
)}
<p>
  Der <a href="/tools/etappen-rechner.html">Etappen-Rechner</a> setzt genau diese Rechnung um – mit
  deinen Zahlen und inklusive der Tageslänge mit Pausen.
</p>

${h2('Richtwerte für die Praxis', 'richtwerte')}
${table({
  head: ['Erfahrung', 'Flach, Asphalt', 'Hügelig, gemischt', 'Bergig, Schotter'],
  rows: [
    ['Erste Tour, untrainiert', '35 – 55 km', '25 – 40 km', '20 – 30 km'],
    ['Erste Tour, sportlich', '60 – 80 km', '45 – 60 km', '30 – 45 km'],
    ['Einige Touren Erfahrung', '80 – 110 km', '60 – 85 km', '45 – 65 km'],
    ['Viel Erfahrung, leicht gepackt', '110 – 160 km', '85 – 120 km', '60 – 90 km'],
  ],
  note: 'Alle Werte gelten für einen Tag mit sechs bis acht Stunden unterwegs, inklusive Pausen und Einkauf.',
})}

${h2('Warum Höhenmeter so viel kosten', 'hoehenmeter')}
<p>
  Höhenmeter kosten dich zweierlei: <strong>Zeit</strong>, die dir für Kilometer fehlt, und
  <strong>Kraft</strong>, die am nächsten Tag fehlt. Für die Tagesplanung zählt die Zeit – und die
  lässt sich gut abschätzen, weil beladen etwa 500 Höhenmeter pro Stunde zusammenkommen.
</p>
${table({
  head: ['Höhenmeter pro Tag', 'Zusätzliche Fahrzeit', 'Charakter'],
  rows: [
    ['0 – 300 Hm', 'bis 36 Min.', 'Flussradweg, Küste, Norddeutschland'],
    ['300 – 600 Hm', '36 – 72 Min.', 'Welliges Hügelland, Heide, Börde'],
    ['600 – 1.000 Hm', '1:12 – 2:00 Std.', 'Mittelgebirge – Harz, Eifel, Schwarzwald'],
    ['1.000 – 1.500 Hm', '2:00 – 3:00 Std.', 'Anspruchsvolles Mittelgebirge, Voralpen'],
    ['über 1.500 Hm', 'über 3 Std.', 'Alpenüberquerung – eigener Planungsmaßstab'],
  ],
  note: 'Die Kraftkosten liegen darüber: Als Faustregel im Radsport gilt, dass 100 Höhenmeter sich anfühlen wie 8 bis 10 flache Kilometer. Das ist der Grund, warum ein bergiger Tag noch am nächsten Morgen spürbar ist.',
})}

${callout(
  'Der Fehler, der Touren kippt',
  '<p>Fast alle Routenplaner zeigen Distanz groß und Höhenmeter klein. Deshalb planen Einsteiger nach Kilometern und werden von den Höhenmetern überrascht. Dreh es um: <strong>Plane zuerst die Höhenmeter, dann die Distanz.</strong> Ein 1.000-Höhenmeter-Tag ist auch mit nur 45 Kilometern ein anstrengender Tag – die zwei Stunden Kletterzeit fehlen dir für alles andere.</p>',
  'warn'
)}

${h2('Wie ein Tag zeitlich wirklich aussieht', 'zeitrechnung')}
<p>
  Die reine Fahrzeit ist nur ein Teil des Tages. Was tatsächlich passiert:
</p>
${table({
  head: ['Aktivität', 'Zeit', 'Anmerkung'],
  rows: [
    ['Aufstehen, packen, Zelt abbauen', '45 – 75 Min.', 'Beim ersten Mal eher 90 Minuten'],
    ['Fahren', '4 – 7 Std.', 'Der eigentliche Kern'],
    ['Pausen unterwegs', '45 – 90 Min.', 'Alle 90 Minuten kurz, einmal lang'],
    ['Einkaufen', '20 – 40 Min.', 'Inklusive Umweg und Suche'],
    ['Orientierung, Umwege, Pannen', '15 – 45 Min.', 'Wird immer unterschätzt'],
    ['Ankommen, aufbauen, kochen', '60 – 90 Min.', 'Vor Einbruch der Dunkelheit einplanen'],
    ['<strong>Gesamter Tag</strong>', '<strong>8 – 12 Std.</strong>', 'Von Aufstehen bis Abendessen'],
  ],
  note: 'Deshalb ist der Startzeitpunkt wichtiger als die Endgeschwindigkeit: Wer um 8 Uhr losfährt, hat entspannt Zeit. Wer um 11 Uhr startet, fährt in die Dämmerung.',
})}

${h3('Die Sonnenuntergangs-Regel', 'sonnenuntergang')}
<p>
  Plane deine Ankunft am Schlafplatz <strong>90 Minuten vor Sonnenuntergang</strong>. Das klingt
  übervorsichtig, ist aber der wirksamste einzelne Planungsgriff überhaupt: Zelt aufbauen, kochen und
  sich einrichten geht bei Licht dreimal so schnell wie mit Stirnlampe. Und wenn unterwegs etwas
  schiefgeht, hast du Puffer.
</p>
${checklist([
  'Ende Juni geht die Sonne in Deutschland gegen 21:30 Uhr unter – Ankunft also 20:00 Uhr',
  'Mitte September bereits gegen 19:45 Uhr – Ankunft 18:15 Uhr',
  'Anfang Oktober gegen 19:00 Uhr – Ankunft 17:30 Uhr, der Tag wird spürbar kürzer',
  'Im Wald wird es 30 bis 45 Minuten früher dunkel als auf offenem Feld',
])}

${h2('Wie du dich selbst kalibrierst', 'kalibrieren')}
<p>
  Die verlässlichste Methode ist eine einzige Referenzfahrt: eine vollgepackte Tagestour über 50 bis
  70 Kilometer auf dem Untergrund, den du auf Tour fahren willst. Notiere danach drei Zahlen:
</p>
${table({
  head: ['Was du notierst', 'Wofür du es brauchst'],
  rows: [
    ['Reine Fahrzeit laut Radcomputer oder App', 'Deinen realen Schnitt mit Gepäck'],
    ['Gesamtzeit von Abfahrt bis Ankunft', 'Deinen Pausenbedarf – meist 25 bis 35 Prozent'],
    ['Wie du dich nach 4 und nach 6 Stunden gefühlt hast', 'Die Grenze, ab der es unangenehm wird'],
  ],
  note: 'Mit diesen drei Zahlen planst du jede weitere Tour deutlich genauer als mit jeder Tabelle im Internet.',
})}

${callout(
  'Die 60-Prozent-Regel gilt auch für die Motivation',
  '<p>Eine Etappe, die du gerade so schaffst, fühlt sich am Abend nach Erschöpfung an. Eine Etappe, bei der du zwei Stunden Puffer hattest, fühlt sich nach Freiheit an. Der Unterschied sind 20 Kilometer – und genau die entscheiden, ob du eine zweite Tour planst. Plane kurz. Du kannst unterwegs immer noch verlängern.</p>',
  'tip'
)}

${h2('Etappen über mehrere Tage verteilen', 'mehrtags')}
${checklist([
  '<strong>Tag 1 kürzer als die anderen.</strong> Anreise, Aufregung und ungewohntes Gepäck kosten Zeit.',
  '<strong>Tag 2 ist meist der stärkste.</strong> Ausgeruht, eingespielt, motiviert.',
  '<strong>Tag 3 bis 5 sind der Einbruch.</strong> Muskelkater, schlechter Schlaf, Gewöhnungsphase. Plane hier 10 bis 15 Prozent weniger.',
  '<strong>Ab Tag 6 kommt die Tourform.</strong> Der Körper hat sich umgestellt, die Etappen dürfen wieder länger werden.',
  '<strong>Baue einen Ruhetag oder Kurztag ein</strong>, sobald die Tour länger als fünf Tage dauert.',
])}

<p style="margin-top:1.5rem">
  <a class="btn btn--primary" href="/tools/etappen-rechner.html">Etappen-Rechner öffnen ${icon('arrow', 'ico')}</a>
</p>
`;

module.exports = article({
  href: '/einstieg/tagesetappen-planen.html',
  kicker: 'Einstieg · Planung',
  title: 'Wie weit kommst du am Tag?',
  metaTitle: 'Bikepacking: Wie viele km pro Tag sind realistisch? | Sattelfest',
  description:
    'Realistische Tagesetappen beim Bikepacking berechnen: das Zeitbudget-Modell, warum Höhenmeter Zeit statt Kilometer kosten, Richtwerte nach Erfahrung und wie ein Tourtag zeitlich wirklich aussieht.',
  lead:
    'Die meisten planen mit ihrer besten Tagestour ohne Gepäck. Übrig bleiben rund 60 Prozent – weil nicht die Strecke begrenzt, sondern die Zeit.',
  meta: [
    { icon: 'route', text: '9 Minuten Lesezeit' },
    { icon: 'mountain', text: 'Mit Zeitbudget-Formel' },
    { icon: 'clock', text: 'Inklusive Tages-Zeitplan' },
  ],
  toc: [
    { label: 'Die Formel', id: 'formel' },
    { label: 'Richtwerte für die Praxis', id: 'richtwerte' },
    { label: 'Warum Höhenmeter so viel kosten', id: 'hoehenmeter' },
    { label: 'Wie ein Tag zeitlich wirklich aussieht', id: 'zeitrechnung' },
    { label: 'Wie du dich selbst kalibrierst', id: 'kalibrieren' },
    { label: 'Etappen über mehrere Tage verteilen', id: 'mehrtags' },
  ],
  content,
  faq: [
    {
      q: 'Wie viele Kilometer schafft man beim Bikepacking pro Tag?',
      a: '<p>Untrainierte Einsteiger kommen auf flachem Asphalt auf 35 bis 55 Kilometer, sportliche auf 60 bis 80. Mit einigen Touren Erfahrung sind 80 bis 110 Kilometer realistisch, erfahrene und leicht gepackte Fahrer schaffen 110 bis 160. Auf Schotter und im Bergigen sinken alle Werte um 30 bis 45 Prozent.</p>',
    },
    {
      q: 'Wie rechne ich Höhenmeter in Kilometer um?',
      a: '<p>Für die Tagesplanung rechnest du in Zeit: Beladen schaffst du etwa 500 Höhenmeter pro Stunde, 100 Höhenmeter kosten dich also rund 12 Minuten zusätzliche Fahrzeit. Ein Tag mit 800 Höhenmetern frisst damit gut anderthalb Stunden deines Zeitbudgets. Die Kraftkosten liegen höher – als Faustregel fühlen sich 100 Höhenmeter an wie 8 bis 10 flache Kilometer.</p>',
    },
    {
      q: 'Wie viele Stunden ist man beim Bikepacking pro Tag unterwegs?',
      a: '<p>Rechne mit 8 bis 12 Stunden vom Aufstehen bis zum Abendessen. Davon sind nur 4 bis 7 Stunden reine Fahrzeit. Der Rest geht für Zeltabbau, Pausen, Einkauf, Orientierung und den Aufbau am Ziel drauf – etwa zwei Stunden verschwinden jeden Tag in Nebentätigkeiten.</p>',
    },
    {
      q: 'Wann sollte ich am Schlafplatz ankommen?',
      a: '<p>Rund 90 Minuten vor Sonnenuntergang. Zelt aufbauen, kochen und sich einrichten geht bei Tageslicht ungefähr dreimal so schnell wie mit Stirnlampe – und du hast Puffer, falls unterwegs etwas schiefgeht. Im Wald wird es zusätzlich 30 bis 45 Minuten früher dunkel als auf offenem Feld.</p>',
    },
    {
      q: 'Werden die Etappen mit jedem Tag länger oder kürzer?',
      a: '<p>Typisch ist: Tag 1 kürzer (Anreise, ungewohntes Gepäck), Tag 2 am stärksten, Tag 3 bis 5 ein Einbruch durch Muskelkater und schlechten Schlaf, ab Tag 6 kommt die Tourform. Plane für die Tage 3 bis 5 rund 10 bis 15 Prozent weniger ein und baue ab fünf Tagen Tourlänge einen Kurztag ein.</p>',
    },
  ],
  related: [
    { href: '/tools/etappen-rechner.html', label: 'Etappen- & Gewichts-Rechner' },
    { href: '/routen/route-selbst-planen.html', label: 'Route selbst planen' },
    { href: '/unterwegs/tagesablauf.html', label: 'Der Tagesablauf auf Tour' },
    { href: '/unterwegs/training-vorbereitung.html', label: 'Training & Vorbereitung' },
  ],
});
