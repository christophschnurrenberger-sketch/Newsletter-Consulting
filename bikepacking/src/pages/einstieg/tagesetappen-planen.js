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
  { value: '60 %', label: 'deiner besten Tagestour', note: 'Der Ausgangswert für eine entspannte Bikepacking-Etappe.' },
  { value: '9 km', label: 'je 100 Höhenmeter', note: 'So viel flache Strecke kostet dich ein Anstieg an Kraft.' },
  { value: '2 Std.', label: 'gehen jeden Tag weg', note: 'Für Pausen, Einkauf, Zeltaufbau und Orientierung.' },
])}

${h2('Die Formel', 'formel')}
<p>
  Nimm die Strecke, die du an einem <strong>guten Tag ohne Gepäck</strong> schaffst, und rechne von
  dort aus:
</p>
${table({
  head: ['Faktor', 'Multiplikator', 'Warum'],
  rows: [
    ['Grundwert Bikepacking', '× 0,6', 'Gepäck, Organisation, Auf- und Abbau, weniger Tempo'],
    ['Untergrund Asphalt', '× 1,0', 'Referenz'],
    ['Untergrund gemischt', '× 0,85', 'Waldwege, Schotterpisten, kurze unbefestigte Stücke'],
    ['Untergrund Schotter/Trail', '× 0,7', 'Loser Untergrund kostet Tempo und Konzentration'],
    ['Erste Tour', '× 0,85', 'Alles dauert länger, weil noch nichts Routine ist'],
    ['Ab Tag 5', '× 0,9', 'Ermüdung – der berühmte dritte bis fünfte Tag'],
    ['Gepäck über 6 kg', '− 1,8 % je kg', 'Bis maximal minus 20 Prozent'],
    ['Höhenmeter', '− 9 km je 100 Hm', 'Ein 800-Hm-Tag kostet dich rund 72 flache Kilometer'],
  ],
  note: 'Der Etappen-Rechner setzt genau diese Formel um – inklusive Fahrzeit und Tageslänge mit Pausen.',
})}

${callout(
  'Ein Rechenbeispiel',
  '<p>Du fährst an einem guten Sonntag ohne Gepäck 100 Kilometer. Deine erste Bikepacking-Tour geht über gemischten Untergrund mit 600 Höhenmetern am Tag, du hast 10 Kilo Gepäck dabei.</p><p>100 × 0,6 = 60 · gemischt × 0,85 = 51 · erste Tour × 0,85 = 43,4 · Gepäck (10 kg) × 0,93 = 40,4 · minus Höhenmeter (600 ÷ 100 × 9 = 54) … <strong>rechnerisch bleiben rund 15 Kilometer.</strong></p><p>Das zeigt: Bei 600 Höhenmetern am Tag ist die Etappe nicht durch Distanz begrenzt, sondern durch die Anstiege. Realistisch planst du dann 35 bis 45 Kilometer mit diesen Höhenmetern – und das ist ein voller Tag.</p>',
  'info'
)}

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
  Ein verbreiteter Umrechnungsfaktor im Radsport lautet: 100 Höhenmeter entsprechen etwa 8 bis 10
  flachen Kilometern. Beladen liegt der Wert eher am oberen Rand, weil das Zusatzgewicht bergauf
  vollständig durchschlägt – bergab bringt es dir dagegen nichts, weil du ohnehin bremst.
</p>
${table({
  head: ['Höhenmeter pro Tag', 'Entspricht flach', 'Charakter'],
  rows: [
    ['0 – 300 Hm', '0 – 27 km', 'Flussradweg, Küste, Norddeutschland'],
    ['300 – 600 Hm', '27 – 54 km', 'Welliges Hügelland, Heide, Börde'],
    ['600 – 1.000 Hm', '54 – 90 km', 'Mittelgebirge – Harz, Eifel, Schwarzwald'],
    ['1.000 – 1.500 Hm', '90 – 135 km', 'Anspruchsvolles Mittelgebirge, Voralpen'],
    ['über 1.500 Hm', 'über 135 km', 'Alpenüberquerung – eigener Planungsmaßstab'],
  ],
})}

${callout(
  'Der Fehler, der Touren kippt',
  '<p>Fast alle Routenplaner zeigen Distanz groß und Höhenmeter klein. Deshalb planen Einsteiger nach Kilometern und werden von den Höhenmetern überrascht. Dreh es um: <strong>Plane zuerst die Höhenmeter, dann die Distanz.</strong> Ein 1.000-Höhenmeter-Tag ist auch mit nur 45 Kilometern ein anstrengender Tag.</p>',
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
    'Realistische Tagesetappen beim Bikepacking berechnen: die 60-Prozent-Formel, warum 100 Höhenmeter 9 flachen Kilometern entsprechen, Richtwerte nach Erfahrung und wie ein Tourtag zeitlich wirklich aussieht.',
  lead:
    'Die meisten planen mit ihrer besten Tagestour ohne Gepäck. Übrig bleiben rund 60 Prozent – und dann kommen die Höhenmeter.',
  meta: [
    { icon: 'route', text: '9 Minuten Lesezeit' },
    { icon: 'mountain', text: 'Mit Höhenmeter-Umrechnung' },
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
      a: '<p>Als Faustregel entsprechen 100 Höhenmeter etwa 8 bis 10 flachen Kilometern an Kraftaufwand. Mit Gepäck rechnest du besser mit 9 bis 10, weil das Zusatzgewicht bergauf voll durchschlägt und dir bergab nichts bringt. Ein Tag mit 800 Höhenmetern kostet dich also rund 72 flache Kilometer.</p>',
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
