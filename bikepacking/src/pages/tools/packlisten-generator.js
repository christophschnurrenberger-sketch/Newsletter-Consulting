'use strict';

const article = require('../_article');
const { h2, callout, checklist, table, esc } = require('../../components');

/**
 * Die acht Fragen des Generators.
 * `key` landet als data-key am Schritt, `value` am jeweiligen Button –
 * die Auswertung passiert in src/assets/site.js (initPacker).
 */
const QUESTIONS = [
  {
    key: 'nights',
    q: 'Wie viele Nächte bist du unterwegs?',
    hint: 'Die Dauer bestimmt Taschenvolumen, Powerbank-Größe und wie viel Kleidung mitmuss. Für die erste Tour ist eine Nacht völlig ausreichend.',
    options: [
      { value: 'kurz', label: 'Eine Nacht', note: 'Mikroabenteuer, S24O, Wochenende' },
      { value: 'mittel', label: 'Zwei bis vier Nächte', note: 'Der häufigste Fall – verlängertes Wochenende' },
      { value: 'lang', label: 'Fünf bis sieben Nächte', note: 'Eine Woche unterwegs' },
      { value: 'woche', label: 'Länger als eine Woche', note: 'Reserve für Verpflegung und Wäsche nötig' },
    ],
  },
  {
    key: 'sleep',
    q: 'Wo schläfst du?',
    hint: 'Der größte Einzelposten deines Gepäcks. Zwischen Zelt und fester Unterkunft liegen rund drei Kilo Unterschied.',
    options: [
      { value: 'zelt', label: 'Im Zelt', note: 'Camping- oder Trekkingplatz – die Standardlösung' },
      { value: 'tarp', label: 'Unter einem Tarp', note: 'Leichter, aber ohne Insektenschutz und Boden' },
      { value: 'unterkunft', label: 'In Pension oder Hostel', note: 'Credit-Card-Bikepacking, minimales Gepäck' },
    ],
  },
  {
    key: 'season',
    q: 'In welcher Jahreszeit fährst du?',
    hint: 'Entscheidend ist nicht die Tagestemperatur, sondern die Nacht. Die kälteste Stunde liegt kurz vor Sonnenaufgang.',
    options: [
      { value: 'sommer', label: 'Hochsommer', note: 'Juni bis August, Nächte über 10 °C' },
      { value: 'uebergang', label: 'Frühjahr oder Herbst', note: 'April, Mai, September, Oktober' },
      { value: 'kalt', label: 'Kalte Jahreszeit', note: 'März, November oder Höhenlagen' },
    ],
  },
  {
    key: 'cook',
    q: 'Willst du unterwegs kochen?',
    hint: 'Ein Kochset wiegt mit Kartusche rund 700 Gramm. In Deutschland liegt alle 30 bis 50 Kilometer ein Supermarkt – ein Kocher ist also eine Komfort-, keine Versorgungsfrage.',
    options: [
      { value: 'ja', label: 'Ja, mit Kocher', note: 'Kaffee am Morgen, warmes Essen am Trekkingplatz' },
      { value: 'nein', label: 'Nein, ich kaufe unterwegs', note: 'Spart 700 g, verlangt Planung nach Öffnungszeiten' },
    ],
  },
  {
    key: 'bike',
    q: 'Mit welchem Rad fährst du?',
    hint: 'Das Rad entscheidet darüber, welches Taschensystem überhaupt möglich ist – vor allem, ob ein Gepäckträger schon vorhanden ist.',
    options: [
      { value: 'gravel', label: 'Gravelbike', note: 'Viele Montagepunkte, der Allrounder' },
      { value: 'mtb', label: 'Mountainbike', note: 'Kleines Rahmendreieck, oft Federgabel' },
      { value: 'trekking', label: 'Trekking- oder Tourenrad', note: 'Gepäckträger meist schon vorhanden' },
      { value: 'rennrad', label: 'Rennrad', note: 'Wenig Reifenfreiheit, kaum Ösen' },
    ],
  },
  {
    key: 'terrain',
    q: 'Auf welchem Untergrund bist du unterwegs?',
    hint: 'Der Untergrund beeinflusst nicht nur die Tagesdistanz, sondern auch, wie fest du packen musst und welches Reparaturmaterial sinnvoll ist.',
    options: [
      { value: 'asphalt', label: 'Überwiegend Asphalt', note: 'Fernradwege, Flussrouten' },
      { value: 'gemischt', label: 'Gemischt', note: 'Asphalt plus Wald- und Wirtschaftswege' },
      { value: 'offroad', label: 'Schotter und Trails', note: 'Gepäck muss straff sitzen' },
    ],
  },
  {
    key: 'level',
    q: 'Wie viel Bikepacking-Erfahrung hast du?',
    hint: 'Bei der ersten Tour bekommst du zusätzliche Hinweise zu Testfahrt und Zeltaufbau – die zwei Dinge, die am häufigsten schiefgehen.',
    options: [
      { value: 'erste', label: 'Das wird meine erste Tour', note: 'Du hast noch nie mit Gepäck übernachtet' },
      { value: 'einige', label: 'Ein paar Touren', note: 'Du weißt, wie sich beladenes Fahren anfühlt' },
      { value: 'viele', label: 'Viel Erfahrung', note: 'Du optimierst nur noch Details' },
    ],
  },
  {
    key: 'crew',
    q: 'Fährst du allein oder mit anderen?',
    hint: 'In der Gruppe lassen sich Zelt, Kocher, Werkzeug und Erste-Hilfe-Set aufteilen. Das ist der einfachste Gewichtsgewinn überhaupt.',
    options: [
      { value: 'allein', label: 'Allein', note: 'Alles muss doppelt vorhanden sein – bei dir' },
      { value: 'zweit', label: 'Zu zweit', note: 'Zelt und Kocher lassen sich teilen' },
      { value: 'gruppe', label: 'In einer Gruppe', note: 'Gemeinschaftsausrüstung aufteilen' },
    ],
  },
];

function buildQuiz() {
  const progress = `<div class="quiz-progress" aria-hidden="true">${QUESTIONS.map(() => '<span></span>').join('')}</div>`;

  const stepsHtml = QUESTIONS.map(
    (question, i) => `<div class="quiz-step${i === 0 ? ' is-active' : ''}" data-key="${esc(question.key)}">
      <p class="quiz-q"><span style="color:var(--c-rust);font-family:var(--font-num);font-size:.85rem">${String(i + 1).padStart(2, '0')} / ${String(QUESTIONS.length).padStart(2, '0')}</span><br>${esc(question.q)}</p>
      <p class="quiz-hint">${esc(question.hint)}</p>
      <div class="quiz-options">
        ${question.options
          .map(
            (o) => `<button type="button" class="quiz-option" data-value="${esc(o.value)}">
            <strong>${esc(o.label)}</strong>
            <span>${esc(o.note)}</span>
          </button>`
          )
          .join('')}
      </div>
      ${i > 0 ? '<p style="margin-top:1rem"><button type="button" class="btn btn--secondary btn--sm" data-back>Zurück</button></p>' : ''}
    </div>`
  ).join('');

  return `<div class="tool" id="packer">
    ${progress}
    ${stepsHtml}
    <div class="tool-result" data-result hidden></div>
  </div>`;
}

const content = `
<p class="lead-p">
  Acht Fragen zu Dauer, Übernachtung, Jahreszeit, Rad und Erfahrung – heraus kommt eine vollständige
  Packliste mit Gramm-Angaben, einer konkreten Taschenempfehlung und dem geschätzten Gesamtgewicht.
  Der Generator rechnet vollständig in deinem Browser; es werden keine Daten übertragen und nichts
  gespeichert.
</p>

${buildQuiz()}

${h2('Worauf die Empfehlung beruht', 'grundlage')}
<p>
  Der Generator wendet fünf Regeln an, die sich beim Bikepacking durchgängig bewährt haben:
</p>
${checklist([
  '<strong>Volumen folgt der Dauer, nicht der Vorsicht.</strong> Eine halb gefüllte Satteltasche pendelt stärker als eine volle kleinere. Deshalb steigt die empfohlene Taschengröße mit den Nächten – und nicht darüber hinaus.',
  '<strong>Das Schlafsystem richtet sich nach der Nachttemperatur.</strong> Nicht nach der Tagestemperatur. Schlafsack und Isomatte werden gemeinsam an die Jahreszeit angepasst, weil beide zusammen über die Nacht entscheiden.',
  '<strong>Wer einen Gepäckträger hat, nutzt ihn.</strong> Beim Trekkingrad empfiehlt der Generator Packtaschen statt eines Bikepacking-Sets – doppeltes Volumen zum halben Preis, und auf Asphalt fahren sie sich genauso gut.',
  '<strong>Gemeinschaftsausrüstung wird geteilt.</strong> Ab zwei Personen weist der Generator darauf hin, Zelt, Kocher, Werkzeug und Erste-Hilfe-Set aufzuteilen. Das spart pro Person schnell ein bis zwei Kilo.',
  '<strong>Reparaturmaterial folgt dem Untergrund.</strong> Auf Schotter kommen andere Teile mit als auf dem Flussradweg – und die Hinweise zum Packen unterscheiden sich ebenfalls.',
])}

${h2('Was die Gewichtsangaben bedeuten', 'gewichte')}
${table({
  head: ['Angabe', 'Wofür sie steht'],
  rows: [
    ['Gramm je Position', 'Richtwert für gängige, <strong>nicht ultraleichte</strong> Ausrüstung'],
    ['Gepäck ohne Wasser', 'Summe aller Positionen, ohne getragene Kleidung und ohne leere Taschen'],
    ['Taschenvolumen', 'Empfohlenes Gesamtvolumen für diese Tour, in Litern'],
  ],
  note: 'Nicht enthalten sind das Leergewicht der Taschen (1,4 bis 2,9 kg für ein volles Set) und Wasser (1 kg je Liter). Ultraleicht-Ausrüstung wiegt oft 30 bis 50 Prozent weniger und kostet das Zwei- bis Dreifache.',
})}

${callout(
  'Die Zielmarke',
  '<p><strong>Unter 10 Kilo Gepäck</strong> ist die Marke, ab der ein Bikepacking-Setup sich leicht anfühlt. Unter 7 Kilo bist du im ambitionierten Bereich. Wenn der Generator dir über 15 Kilo ausrechnet, liegt das fast immer an der Jahreszeit oder an der Tourlänge – und dann ist ein Gepäckträger die angenehmere Lösung als noch mehr Bikepacking-Taschen.</p>',
  'money'
)}

${callout(
  'Was der Generator nicht kann',
  '<p>Er kennt deine Route nicht. Wenn du zu einem Trekkingplatz ohne Wasser fährst, brauchst du drei bis vier Liter zusätzlich – das sind drei bis vier Kilo, die in keiner Liste stehen. Und er kennt dein Wetter nicht: Bei angekündigtem Dauerregen gehören Regenhose und eine zweite trockene Garnitur dazu, auch im Hochsommer.</p>',
  'info'
)}

${h2('Und danach?', 'danach')}
<p>
  Wenn du dein Ergebnis hast, führen diese drei Artikel weiter:
</p>
${checklist([
  '<a href="/ausruestung/packliste.html">Die Packliste mit Gewichten</a> – alle Positionen im Detail, eingeordnet als Pflicht, sinnvoll oder Ballast.',
  '<a href="/taschen/taschensystem.html">Das Taschensystem verstehen</a> – welche Tasche welche Aufgabe hat und in welcher Reihenfolge du kaufst.',
  '<a href="/taschen/richtig-packen.html">Richtig packen: Gewichtsverteilung</a> – wohin das Gewicht gehört, damit das Rad noch gut fährt.',
])}
`;

module.exports = article({
  href: '/tools/packlisten-generator.html',
  kicker: 'Werkzeug · Kostenlos',
  title: 'Packlisten-Generator',
  metaTitle: 'Bikepacking-Packliste erstellen: Generator in 8 Fragen | Sattelfest',
  description:
    'Kostenloser Packlisten-Generator fürs Bikepacking: Acht Fragen zu Dauer, Übernachtung, Jahreszeit, Rad und Erfahrung – heraus kommt eine vollständige Packliste mit Gramm-Angaben und Taschenempfehlung.',
  lead:
    'Acht Fragen, eine persönliche Packliste mit Gramm-Angaben, Taschenempfehlung und geschätztem Gesamtgewicht. Läuft komplett im Browser, ohne Datenübertragung.',
  meta: [
    { icon: 'clock', text: '2 Minuten' },
    { icon: 'weight', text: 'Mit Gesamtgewicht' },
    { icon: 'shield', text: 'Keine Datenübertragung' },
  ],
  content,
  faq: [
    {
      q: 'Werden meine Antworten gespeichert?',
      a: '<p>Nein. Der Generator rechnet vollständig in deinem Browser. Es werden keine Daten an einen Server übertragen, nichts gespeichert und keine Cookies gesetzt. Lädst du die Seite neu, ist alles zurückgesetzt.</p>',
    },
    {
      q: 'Wie genau sind die Gewichtsangaben?',
      a: '<p>Es sind Richtwerte für gängige, nicht ultraleichte Ausrüstung – sie dienen der Größenordnung, nicht der Grammjagd. Deine tatsächlichen Gewichte können um 20 bis 30 Prozent abweichen. Ultraleicht-Ausrüstung wiegt oft 30 bis 50 Prozent weniger, kostet dafür aber das Zwei- bis Dreifache.</p>',
    },
    {
      q: 'Warum empfiehlt mir der Generator Packtaschen statt Bikepacking-Taschen?',
      a: '<p>Weil du angegeben hast, dass du ein Trekking- oder Tourenrad fährst. Dort ist meist schon ein Gepäckträger montiert, und zwei Hinterradtaschen bieten doppeltes Volumen zum halben Preis eines Bikepacking-Sets – dazu sind sie zuverlässig wasserdicht. Auf Asphalt und breiten Wegen sind sie schlicht die bessere Lösung.</p>',
    },
    {
      q: 'Ist Wasser im errechneten Gewicht enthalten?',
      a: '<p>Nein. Wasser wiegt ein Kilo pro Liter und wird unterwegs ständig verbraucht und nachgefüllt – deshalb wird es separat gerechnet. Für eine normale Etappe kommen 1,5 Kilo dazu. Wenn du zu einem Trekkingplatz ohne Wasserstelle fährst, sind es drei bis vier Kilo auf dem letzten Abschnitt.</p>',
    },
    {
      q: 'Ersetzt der Generator eine eigene Packliste?',
      a: '<p>Für die ersten Touren weitgehend ja. Er kennt aber deine Route und dein Wetter nicht: Ein Trekkingplatz ohne Wasser, angekündigter Dauerregen oder eine Strecke ohne Einkaufsmöglichkeit verlangen Ergänzungen. Nach zwei, drei Touren hast du ohnehin deine eigene Liste – und die ist dann besser als jede allgemeine.</p>',
    },
  ],
  related: [
    { href: '/ausruestung/packliste.html', label: 'Die Packliste mit Gewichten' },
    { href: '/taschen/taschensystem.html', label: 'Das Taschensystem verstehen' },
    { href: '/tools/etappen-rechner.html', label: 'Etappen- & Gewichts-Rechner' },
  ],
});
