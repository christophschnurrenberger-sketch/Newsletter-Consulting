'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats,
} = require('../../components');

const content = `
<p class="lead-p">
  Die beste Bikepacking-Zeit in Deutschland ist nicht der Hochsommer, sondern Mai, Juni und September.
  Und der wichtigste Planungsgriff ist nicht die Wahl des Monats, sondern die Frage: Was mache ich,
  wenn es drei Tage regnet? Wer darauf eine Antwort hat, fährt entspannter.
</p>

${stats([
  { value: 'Mai & Sep.', label: 'Die besten Monate', note: 'Lange Tage, moderate Temperaturen, wenig Betrieb.' },
  { value: '16 Std.', label: 'Tageslicht im Juni', note: 'Gegen 8 Stunden im Dezember.' },
  { value: '3', label: 'Regentage einplanen', note: 'Bei einer Woche Tour statistisch normal.' },
])}

${h2('Die Monate im Überblick', 'monate')}
${table({
  head: ['Monat', 'Tageslicht', 'Typische Nacht', 'Bewertung'],
  rows: [
    ['März', '12 Std.', '−1 bis +5 °C', 'Kalt, aber leer. Nur mit Winterausrüstung'],
    ['April', '14 Std.', '2 bis 8 °C', 'Sehr wechselhaft. Trekkingplätze oft noch zu'],
    ['<strong>Mai</strong>', '15,5 Std.', '6 bis 12 °C', '<strong>Sehr gut.</strong> Alles grün, Saison startet, wenig Betrieb'],
    ['<strong>Juni</strong>', '<strong>16,5 Std.</strong>', '9 bis 14 °C', '<strong>Beste Kombination</strong> aus Licht und Wärme'],
    ['Juli', '16 Std.', '12 bis 17 °C', 'Warm bis heiß, Campingplätze voll, Gewitterrisiko'],
    ['August', '14,5 Std.', '12 bis 17 °C', 'Wie Juli, dazu Ferienbetrieb an den Küsten'],
    ['<strong>September</strong>', '13 Std.', '8 bis 13 °C', '<strong>Sehr gut.</strong> Stabiles Wetter, leere Plätze, warme Tage'],
    ['Oktober', '11 Std.', '4 bis 9 °C', 'Schön, aber kurze Tage. Trekkingplätze schließen'],
    ['November – Februar', '8 – 10 Std.', '−3 bis +5 °C', 'Nur für Erfahrene mit Winterausrüstung'],
  ],
  note: 'Tageslichtangaben für die Mitte Deutschlands. Im Norden sind die Sommertage länger und die Wintertage kürzer als im Süden.',
})}

${callout(
  'Warum September oft besser ist als Juli',
  '<p>Im September sind die Temperaturen angenehmer zum Fahren, die Campingplätze leer, die Straßen ruhiger und das Wetter statistisch stabiler als im Hochsommer. Der Preis: Die Tage sind drei Stunden kürzer, und ab Mitte September wird es nachts spürbar kühl. Wer den Schlafsack eine Stufe wärmer wählt, hat den besten Monat des Jahres.</p>',
  'tip'
)}

${h2('Das Tageslicht ist der Taktgeber', 'tageslicht')}
<p>
  Die Länge des Tages bestimmt deine Etappe stärker als die Temperatur. Im Juni kannst du um 5 Uhr
  losfahren und um 21 Uhr noch aufbauen. Im Oktober hast du effektiv sieben nutzbare Stunden.
</p>
${table({
  head: ['Monat', 'Sonnenaufgang', 'Sonnenuntergang', 'Späteste Ankunft am Ziel'],
  rows: [
    ['April', 'ca. 6:45', 'ca. 20:15', '18:45'],
    ['Mai', 'ca. 5:45', 'ca. 21:00', '19:30'],
    ['Juni', 'ca. 5:15', 'ca. 21:30', '20:00'],
    ['Juli', 'ca. 5:30', 'ca. 21:20', '19:50'],
    ['August', 'ca. 6:15', 'ca. 20:30', '19:00'],
    ['September', 'ca. 7:00', 'ca. 19:40', '18:10'],
    ['Oktober', 'ca. 7:45', 'ca. 18:35', '17:05'],
  ],
  note: 'Späteste Ankunft heißt: 90 Minuten vor Sonnenuntergang – genug Zeit für Zeltaufbau, Kochen und Einrichten bei Tageslicht. Im Wald wird es zusätzlich 30 bis 45 Minuten früher dunkel.',
})}

${h2('Regen richtig einplanen', 'regen')}
<p>
  In Deutschland regnet es im Schnitt an etwa jedem dritten Tag. Bei einer Woche Tour sind zwei bis
  drei Regentage statistisch normal – kein Pech, sondern die Erwartung.
</p>

${h3('Die drei Regenarten', 'regenarten')}
${table({
  head: ['Art', 'Dauer', 'Umgang'],
  rows: [
    [
      'Schauer',
      '15 – 45 Min.',
      'Unterstellen und warten. Meist schneller vorbei, als du die Regenjacke anhast',
    ],
    [
      'Landregen',
      '3 – 12 Std.',
      'Durchfahren. Mit guter Jacke und Regenhose gut machbar – kalte Füße sind das Hauptproblem',
    ],
    [
      'Gewitter',
      '20 – 60 Min.',
      '<strong>Sofort Schutz suchen.</strong> Nicht unter einzelnen Bäumen, nicht auf offenem Feld, nicht auf einer Anhöhe',
    ],
  ],
  note: 'Gewitter sind das einzige Wetterphänomen, bei dem Weiterfahren gefährlich ist. Alles andere ist eine Frage der Ausrüstung und der Einstellung.',
})}

${h3('Die Regentag-Strategie', 'strategie')}
${checklist([
  '<strong>Früher losfahren.</strong> Gewitter entstehen meist nachmittags. Wer um 7 Uhr startet, hat die Etappe oft vor dem Umschwung geschafft.',
  '<strong>Kürzere Etappe fahren.</strong> Bei Regen sind 50 Kilometer anstrengender als 80 bei Sonne.',
  '<strong>Auf feste Unterkunft ausweichen.</strong> Eine Pension für 60 Euro nach zwei Regentagen rettet die ganze Tour.',
  '<strong>Mittags einen langen Stopp einlegen.</strong> Ein Café, in dem du zwei Stunden sitzt und trocknest, ist keine verlorene Zeit.',
  '<strong>Trockene Kleidung heilig halten.</strong> Die Schlafkleidung wird niemals unterwegs angezogen. Niemals.',
  '<strong>Regentag als Ruhetag nutzen.</strong> Wer nicht muss, muss nicht fahren.',
])}

${callout(
  'Der kalte Regen ist das Problem, nicht der warme',
  '<p>Regen bei 22 Grad ist unangenehm, mehr nicht. Regen bei 10 Grad plus Fahrtwind kühlt den Körper erstaunlich schnell aus – vor allem in Abfahrten, in denen du dich nicht bewegst. Die Faustregel: <strong>Unter 12 Grad plus Regen ist ein Sicherheitsthema.</strong> Dann brauchst du Regenhose, lange Handschuhe und eine echte Isolationsschicht, oder du bleibst.</p>',
  'warn'
)}

${h2('Hitze', 'hitze')}
<p>
  Über 30 Grad ist Radfahren mit Gepäck keine gute Idee – jedenfalls nicht zwischen 12 und 16 Uhr.
  Das südeuropäische Modell funktioniert auch in Deutschland:
</p>
${checklist([
  '<strong>Früh fahren:</strong> 6 bis 11 Uhr sind die besten Stunden. Um 11 Uhr hast du die Etappe fast geschafft.',
  '<strong>Lange Mittagspause:</strong> 12 bis 16 Uhr im Schatten, an einem See oder in einem Café.',
  '<strong>Abends noch einmal 20 Kilometer,</strong> wenn nötig – die Abendstunden sind angenehm.',
  '<strong>Deutlich mehr trinken:</strong> 750 bis 1.000 ml pro Stunde statt der üblichen 500.',
  '<strong>Salz zuführen:</strong> Salzige Snacks oder Elektrolytpulver – nur Wasser reicht bei Hitze nicht.',
  '<strong>Kopf und Nacken schützen:</strong> Helm mit Belüftung, Buff im Nacken, gelegentlich nass machen.',
  '<strong>Warnzeichen ernst nehmen:</strong> Kopfschmerzen, Übelkeit, Gänsehaut trotz Hitze – sofort in den Schatten.',
])}

${h2('Wind: der unterschätzte Faktor', 'wind')}
<p>
  Wind ist beim Radfahren wirkungsvoller als jeder Berg. Ein Anstieg endet irgendwann; Gegenwind
  begleitet dich den ganzen Tag.
</p>
${table({
  head: ['Windstärke', 'km/h', 'Wirkung mit Gepäck'],
  rows: [
    ['Leichte Brise (2 Bft)', '6 – 11', 'Kaum spürbar'],
    ['Schwache Brise (3 Bft)', '12 – 19', 'Spürbar, kostet 2 bis 3 km/h Schnitt'],
    ['Mäßige Brise (4 Bft)', '20 – 28', 'Deutlich – der Tag wird 30 Prozent länger'],
    ['Frische Brise (5 Bft)', '29 – 38', '<strong>Sehr anstrengend</strong>, Seitenwind wird gefährlich'],
    ['Starker Wind (6 Bft)', '39 – 49', '<strong>Etappe verkürzen oder Route ändern</strong>'],
    ['Sturm (7+ Bft)', 'ab 50', '<strong>Nicht fahren.</strong> Vor allem im Wald: Astbruch'],
  ],
  note: 'Seitenwind ist mit Bikepacking-Gepäck gefährlicher als Gegenwind: Große Flächen wie Lenkerrolle und Satteltasche wirken wie ein Segel. Bei Böen über 40 km/h auf offenen Strecken die Route ändern.',
})}

${checklist([
  '<strong>Plane die Windrichtung ein.</strong> In Mitteleuropa herrscht Westwind vor – Touren von West nach Ost sind im Schnitt leichter.',
  '<strong>An der Küste morgens fahren.</strong> Der Wind frischt dort meist im Tagesverlauf auf.',
  '<strong>Im Wald ist es windstill.</strong> Bei starkem Gegenwind lohnt der Umweg über eine geschützte Route.',
  '<strong>Bei Sturm nicht in den Wald.</strong> Herabfallende Äste sind die eigentliche Gefahr, nicht der Wind selbst.',
])}

${h2('Wintertouren', 'winter')}
<p>
  Bikepacking im Winter ist möglich, aber es ist eine andere Disziplin – nicht dieselbe Tour bei
  kälteren Temperaturen. Was sich ändert:
</p>
${table({
  head: ['Aspekt', 'Was anders ist'],
  rows: [
    ['Tageslicht', 'Nur 8 bis 9 Stunden – Etappen entsprechend kurz planen'],
    ['Schlafsystem', 'Isomatte ab R 4,5, Schlafsack mit Komfort unter −5 °C'],
    ['Wasser', 'Gefriert. Flaschen kopfüber lagern, nachts mit ins Zelt'],
    ['Elektronik', 'Akkus verlieren bei Kälte 30 bis 50 Prozent Kapazität'],
    ['Kocher', 'Gas funktioniert unter 0 °C schlecht – Kartusche vorwärmen'],
    ['Übernachtung', 'Trekkingplätze meist geschlossen, Campingplätze auch'],
    ['Kleidung', 'Deutlich mehr Volumen – Packtaschen sind hier oft die bessere Wahl'],
    ['Sicherheit', 'Unterkühlung ist die reale Gefahr, nicht Schnee'],
  ],
  note: 'Für den Einstieg in Wintertouren gilt dieselbe Regel wie für den Einstieg überhaupt: Fang mit einer einzigen Nacht an, nah an zu Hause, mit einem Ausstiegsplan.',
})}
`;

module.exports = article({
  href: '/routen/saison-wetter.html',
  kicker: 'Routen · Saison',
  title: 'Saison, Wetter & Jahreszeit',
  metaTitle: 'Bikepacking-Saison: Beste Zeit, Regen, Hitze und Wind planen | Sattelfest',
  description:
    'Wann ist die beste Bikepacking-Zeit? Die Monate im Überblick mit Tageslicht und Nachttemperaturen, wie du Regentage einplanst, die Hitze-Strategie, Windstärken realistisch einschätzen und was im Winter anders ist.',
  lead:
    'Die beste Zeit ist nicht der Hochsommer, sondern Mai, Juni und September. Und der wichtigste Planungsgriff ist die Antwort auf: Was, wenn es drei Tage regnet?',
  meta: [
    { icon: 'sun', text: '9 Minuten Lesezeit' },
    { icon: 'clock', text: 'Mit Tageslicht-Tabelle' },
    { icon: 'drop', text: 'Regen- und Hitzestrategie' },
  ],
  toc: [
    { label: 'Die Monate im Überblick', id: 'monate' },
    { label: 'Das Tageslicht ist der Taktgeber', id: 'tageslicht' },
    { label: 'Regen richtig einplanen', id: 'regen' },
    { label: 'Hitze', id: 'hitze' },
    { label: 'Wind: der unterschätzte Faktor', id: 'wind' },
    { label: 'Wintertouren', id: 'winter' },
  ],
  content,
  faq: [
    {
      q: 'Wann ist die beste Zeit für eine Bikepacking-Tour in Deutschland?',
      a: '<p>Mai, Juni und September. Der Juni hat mit 16,5 Stunden das meiste Tageslicht bei angenehmen Nachttemperaturen. Der September bietet stabileres Wetter, leere Campingplätze und ruhigere Straßen, dafür drei Stunden kürzere Tage. Juli und August sind warm, aber die Plätze sind voll und das Gewitterrisiko ist höher.</p>',
    },
    {
      q: 'Wie viele Regentage muss ich einplanen?',
      a: '<p>In Deutschland regnet es im Schnitt an etwa jedem dritten Tag. Bei einer Wochentour sind zwei bis drei Regentage statistisch normal – das ist kein Pech, sondern die Erwartung. Plane deshalb von vornherein eine Regenstrategie: kürzere Etappen, früherer Start, notfalls eine feste Unterkunft.</p>',
    },
    {
      q: 'Ab wann wird Regen beim Radfahren gefährlich?',
      a: '<p>Unter etwa 12 Grad in Kombination mit Regen. Der Fahrtwind kühlt den Körper dann erstaunlich schnell aus, besonders in Abfahrten, in denen du dich nicht bewegst. In diesem Bereich brauchst du Regenhose, lange Handschuhe und eine echte Isolationsschicht – oder du bleibst. Regen bei 22 Grad ist dagegen nur unangenehm.</p>',
    },
    {
      q: 'Wie gehe ich mit Hitze über 30 Grad um?',
      a: '<p>Nach dem südeuropäischen Modell: von 6 bis 11 Uhr fahren, 12 bis 16 Uhr lange Pause im Schatten, abends bei Bedarf noch einmal 20 Kilometer. Trinke 750 bis 1.000 Milliliter pro Stunde statt der üblichen 500 und führe Salz zu – nur Wasser reicht bei Hitze nicht. Kopfschmerzen und Übelkeit sind Warnzeichen.</p>',
    },
    {
      q: 'Ab welcher Windstärke sollte ich nicht mehr fahren?',
      a: '<p>Ab Sturm (7 Beaufort, über 50 km/h) gar nicht, vor allem nicht im Wald – herabfallende Äste sind die eigentliche Gefahr. Ab starkem Wind (6 Bft, 39 bis 49 km/h) solltest du die Etappe verkürzen oder die Route ändern. Seitenwind ist mit Bikepacking-Gepäck gefährlicher als Gegenwind, weil Lenkerrolle und Satteltasche wie ein Segel wirken.</p>',
    },
  ],
  related: [
    { href: '/ausruestung/kleidung.html', label: 'Kleidung: Das Zwiebelprinzip' },
    { href: '/ausruestung/schlafsack-isomatte.html', label: 'Schlafsack & Isomatte' },
    { href: '/einstieg/tagesetappen-planen.html', label: 'Wie weit kommst du am Tag?' },
    { href: '/taschen/wasserdicht-packen.html', label: 'Wasserdicht packen' },
  ],
});
