'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, timeline,
} = require('../../components');

const content = `
<p class="lead-p">
  Nach zwei, drei Tagen stellt sich auf jeder Tour ein Rhythmus ein. Wer ihn kennt, bevor er losfährt,
  spart sich die ersten beiden Tage Chaos – und kommt abends entspannt an, statt in der Dämmerung ein
  Zelt zu suchen.
</p>

${stats([
  { value: '2 Std.', label: 'Nebenzeit pro Tag', note: 'Packen, einkaufen, aufbauen, orientieren.' },
  { value: '90 Min.', label: 'Vor Sonnenuntergang', note: 'Der Zielwert für die Ankunft.' },
  { value: '45 Min.', label: 'Morgenroutine', note: 'Vom Aufwachen bis zum Losfahren, mit Übung.' },
])}

${h2('Der Tag im Detail', 'ablauf')}
${timeline([
  {
    time: '06:30',
    title: 'Aufwachen',
    text:
      'Meist von selbst, weil es hell wird. Bleib zehn Minuten liegen, iss einen Riegel im Schlafsack – das bringt den Kreislauf in Gang, bevor du in die kühle Morgenluft steigst.',
  },
  {
    time: '06:45',
    title: 'Anziehen im Schlafsack',
    text:
      'Der wichtigste Trick des Morgens: Radkleidung im Schlafsack anziehen, solange es warm ist. Bei 6 Grad Außentemperatur macht das den Unterschied zwischen einem guten und einem miserablen Start.',
  },
  {
    time: '07:00',
    title: 'Packen: innen nach außen',
    text:
      'Erst alles im Zelt einpacken, dann das Zelt. Schlafsack in den Packsack, Isomatte aufrollen, Kleidung sortieren. Wer erst das Zelt abbaut, sitzt danach mit seinem Zeug auf der nassen Wiese.',
  },
  {
    time: '07:20',
    title: 'Zelt abbauen',
    text:
      'Innenzelt zuerst herausnehmen, wenn es doppelwandig ist – so bleibt es trocken. Außenzelt kräftig ausschütteln. Wenn es nass ist: außen an die Gabel, es trocknet im Fahrtwind.',
  },
  {
    time: '07:35',
    title: 'Platz kontrollieren',
    text:
      'Einmal im Kreis gehen. Heringe, Müll, vergessene Socken, Powerbank an der Bank. Der Platz sieht danach aus, als wärst du nie da gewesen – das ist die Grundregel, an der das ganze Trekkingplatz-System hängt.',
  },
  {
    time: '07:45',
    title: 'Losfahren',
    text:
      'Ohne Frühstück. Die ersten 20 Kilometer im kühlen Morgenlicht sind fast immer die besten des Tages, und der Bäcker macht sowieso erst um acht auf.',
  },
  {
    time: '09:15',
    title: 'Frühstücken',
    text:
      'Nach 20 bis 25 Kilometern in einer Bäckerei oder einem Café. Zu diesem Zeitpunkt hast du ein Drittel der Etappe geschafft. Hier auch: Powerbank laden, Wasser auffüllen, Route für den Tag prüfen.',
  },
  {
    time: '10:00',
    title: 'Der lange Vormittagsblock',
    text:
      'Die produktivste Zeit des Tages. Kühl, ausgeruht, motiviert. Anstiege gehören hierher, nicht in den Nachmittag. Alle 90 Minuten kurz anhalten, trinken und etwas essen.',
  },
  {
    time: '13:00',
    title: 'Mittagspause',
    text:
      'Mindestens 45 Minuten, im Schatten, mit richtigem Essen. Schuhe aus, Beine hoch. Das ist keine verlorene Zeit – wer mittags 20 Minuten dösen kann, fährt nachmittags spürbar besser.',
  },
  {
    time: '14:00',
    title: 'Einkaufen für den Abend',
    text:
      'Abendessen und Getränk für den Abend, plus Wasser für den Schlafplatz. Nicht später – kleine Orte haben nachmittags oft geschlossen, und ab 18 Uhr wird es dünn.',
  },
  {
    time: '14:30',
    title: 'Der Nachmittagsblock',
    text:
      'Der zähste Teil des Tages. Wärme, Müdigkeit, und der Kopf rechnet aus, wie weit es noch ist. Kürzere Intervalle, mehr Pausen, weniger Ehrgeiz. Der Nachmittag ist nicht die Zeit für Rekorde.',
  },
  {
    time: '17:30',
    title: 'Ankommen',
    text:
      'Rund 90 Minuten vor Sonnenuntergang. Erst Platz suchen, dann Rad abstellen, dann hinsetzen und fünf Minuten nichts tun. Danach aufbauen.',
  },
  {
    time: '18:00',
    title: 'Aufbauen und waschen',
    text:
      'Zelt zuerst, damit es steht, bevor du müde wirst. Dann waschen, umziehen, Radkleidung auslüften. Trockene Kleidung ist der Moment, ab dem ein Tourtag angenehm wird.',
  },
  {
    time: '19:00',
    title: 'Kochen und essen',
    text:
      'In Ruhe. Dabei Geräte laden, Route für morgen anschauen, Wetter prüfen. Wenn du kein Netz hast: Du hast die Route ohnehin offline.',
  },
  {
    time: '20:30',
    title: 'Sitzen',
    text:
      'Der Teil, wegen dem man das alles macht. Kein Programm, kein Bildschirm. Wer diesen Teil wegoptimiert, hat den Sinn der Sache verpasst.',
  },
  {
    time: '21:30',
    title: 'Schlafen',
    text:
      'Früher als zu Hause, weil es dunkel ist und du gefahren bist. Vorher: Wasser für die Nacht bereitstellen, Stirnlampe griffbereit, Elektronik mit in den Schlafsack, wenn es kalt ist.',
  },
])}

${callout(
  'Die zwei wichtigsten Regeln',
  '<p><strong>1. Früh losfahren.</strong> Ein Start um 7:45 statt um 10:30 verändert den ganzen Tag: Du fährst in der kühlen Zeit, hast nachmittags Puffer und kommst entspannt an.</p><p><strong>2. 90 Minuten vor Sonnenuntergang ankommen.</strong> Zelt aufbauen bei Licht dauert ein Drittel der Zeit und macht ein Vielfaches an Unterschied für die Laune.</p>',
  'tip'
)}

${h2('Die Morgenroutine optimieren', 'morgen')}
<p>
  Am ersten Tag brauchst du 90 Minuten vom Aufwachen bis zum Losfahren. Am dritten sind es 45. Was
  hilft:
</p>
${checklist([
  '<strong>Abends vorbereiten:</strong> Am Vorabend alles, was du nicht nachts brauchst, schon in die Taschen packen',
  '<strong>Feste Reihenfolge:</strong> Immer dieselben Handgriffe in derselben Abfolge – dann vergisst du nichts',
  '<strong>Feste Plätze:</strong> Stirnlampe immer links im Zelt, Handy immer in derselben Tasche',
  '<strong>Ohne Frühstück starten:</strong> Spart 30 Minuten und die ersten Kilometer im Kühlen sind die schönsten',
  '<strong>Nasses Zelt nicht trocknen wollen:</strong> Es trocknet unterwegs im Fahrtwind, wenn du es außen befestigst',
  '<strong>Der Kontrollblick:</strong> Einmal um den Platz gehen, bevor du aufsteigst',
])}

${h2('Pausen richtig setzen', 'pausen')}
${table({
  head: ['Art', 'Wann', 'Dauer', 'Was du machst'],
  rows: [
    ['Trinkpause', 'Alle 45 – 60 Min.', '2 Min.', 'Trinken, kurz strecken – nicht absteigen nötig'],
    ['Essenspause', 'Alle 90 Min.', '5 – 10 Min.', 'Riegel, Wasser auffüllen, Hände lockern'],
    ['Mittagspause', 'Nach 3 – 4 Std.', '45 – 90 Min.', 'Richtige Mahlzeit, Schuhe aus, Beine hoch'],
    ['Technikpause', 'Nach den ersten 10 km', '3 Min.', 'Alle Riemen nachziehen – Gepäck setzt sich'],
    ['Notpause', 'Bei Kopfschmerz, Übelkeit, Kraftverlust', 'So lange wie nötig', 'Essen, trinken, Schatten. Nicht durchbeißen'],
  ],
  note: 'Der häufigste Fehler ist, zu selten und dafür zu lange zu pausieren. Kurze Pausen alle 90 Minuten halten die Leistung besser als eine große Pause nach vier Stunden.',
})}

${h2('Der Abend am Schlafplatz', 'abend')}
${checklist([
  '<strong>Zelt zuerst.</strong> Auch wenn du hungrig bist – ein stehendes Zelt beruhigt und du bist bei Regen sofort im Trockenen.',
  '<strong>Radkleidung sofort ausziehen und auslüften.</strong> Feuchtes Sitzpolster über Stunden ist die Hauptursache für Hautprobleme.',
  '<strong>Waschen, auch nur mit einem Waschlappen.</strong> Zehn Minuten, die den Abend verändern.',
  '<strong>Rad abstellen und sichern,</strong> auch auf einem einsamen Trekkingplatz – zumindest angeschlossen.',
  '<strong>Wasser für die Nacht bereitstellen.</strong> Nachts nach draußen und suchen ist unangenehm.',
  '<strong>Elektronik laden</strong>, solange du wach bist. Powerbank an Handy und Licht anschließen, bevor du müde wirst.',
  '<strong>Wetter für morgen prüfen</strong> und die Etappe gegebenenfalls anpassen.',
  '<strong>Bei Kälte alles Empfindliche mit in den Schlafsack:</strong> Powerbank, Handy, Gaskartusche, Wasserflasche.',
])}

${callout(
  'Der Rhythmus stellt sich von selbst ein',
  '<p>Am ersten Tag ist alles ungewohnt und dauert zu lang. Am zweiten geht es besser. Ab dem dritten Tag läuft es von allein – dann packst du morgens, ohne nachzudenken, und weißt abends genau, in welcher Reihenfolge du was machst. Das ist der Moment, ab dem Bikepacking einfach wird. Es ist auch der Grund, warum viele sagen, dass eine Tour erst ab dem dritten Tag richtig anfängt.</p>',
  'info'
)}
`;

module.exports = article({
  href: '/unterwegs/tagesablauf.html',
  kicker: 'Unterwegs · Rhythmus',
  title: 'Der Tagesablauf auf Tour',
  metaTitle: 'Bikepacking-Tagesablauf: Der Rhythmus von 6:30 bis 21:30 | Sattelfest',
  description:
    'Wie ein Bikepacking-Tag wirklich abläuft: der komplette Stundenplan von Aufwachen bis Schlafen, die Morgenroutine optimieren, Pausen richtig setzen und die Abendroutine am Schlafplatz.',
  lead:
    'Nach zwei, drei Tagen stellt sich ein Rhythmus ein. Wer ihn vorher kennt, spart sich die ersten beiden Tage Chaos.',
  meta: [
    { icon: 'clock', text: '8 Minuten Lesezeit' },
    { icon: 'check', text: 'Mit vollständigem Stundenplan' },
    { icon: 'tent', text: 'Morgen- und Abendroutine' },
  ],
  toc: [
    { label: 'Der Tag im Detail', id: 'ablauf' },
    { label: 'Die Morgenroutine optimieren', id: 'morgen' },
    { label: 'Pausen richtig setzen', id: 'pausen' },
    { label: 'Der Abend am Schlafplatz', id: 'abend' },
  ],
  content,
  faq: [
    {
      q: 'Wann sollte ich beim Bikepacking losfahren?',
      a: '<p>Zwischen 7:30 und 8:30 Uhr, ohne Frühstück. Die ersten 20 Kilometer im kühlen Morgenlicht sind fast immer die besten des Tages, und du frühstückst nach 20 bis 25 Kilometern in einer Bäckerei. Ein Start um 7:45 statt 10:30 verändert den ganzen Tag: kühlere Fahrzeit, nachmittags Puffer, entspannte Ankunft.</p>',
    },
    {
      q: 'Wie lange dauert die Morgenroutine?',
      a: '<p>Am ersten Tag rund 90 Minuten vom Aufwachen bis zum Losfahren, ab dem dritten Tag etwa 45. Was hilft: abends schon vorpacken, immer dieselbe Reihenfolge einhalten, feste Plätze für Stirnlampe und Handy, ohne Frühstück starten und ein nasses Zelt außen an die Gabel schnallen statt es trocknen zu wollen.</p>',
    },
    {
      q: 'Wie setze ich Pausen richtig?',
      a: '<p>Kurz und häufig statt lang und selten: alle 45 bis 60 Minuten zwei Minuten trinken, alle 90 Minuten fünf bis zehn Minuten essen, nach drei bis vier Stunden eine echte Mittagspause von 45 bis 90 Minuten. Dazu eine Technikpause nach den ersten zehn Kilometern, um alle Riemen nachzuziehen – Gepäck setzt sich.</p>',
    },
    {
      q: 'Was mache ich als Erstes am Schlafplatz?',
      a: '<p>Das Zelt aufbauen, auch wenn du hungrig bist. Ein stehendes Zelt beruhigt und schützt dich sofort, falls es zu regnen anfängt. Danach Radkleidung ausziehen und auslüften, waschen, umziehen. Trockene Kleidung ist der Moment, ab dem ein Tourtag angenehm wird.</p>',
    },
    {
      q: 'Wie ziehe ich mich morgens bei Kälte an?',
      a: '<p>Im Schlafsack. Nimm die Radkleidung mit hinein (bei Kälte schon über Nacht, dann ist sie warm) und zieh dich an, solange du im Warmen liegst. Bei 6 Grad Außentemperatur macht das den Unterschied zwischen einem guten und einem miserablen Start in den Tag.</p>',
    },
  ],
  related: [
    { href: '/routen/erstes-mikroabenteuer.html', label: 'Das erste Mikroabenteuer (S24O)' },
    { href: '/einstieg/tagesetappen-planen.html', label: 'Wie weit kommst du am Tag?' },
    { href: '/unterwegs/koerper-beschwerden.html', label: 'Sitzbeschwerden, Hände & Knie' },
    { href: '/routen/wasser-verpflegung.html', label: 'Wasser & Verpflegung unterwegs' },
  ],
});
