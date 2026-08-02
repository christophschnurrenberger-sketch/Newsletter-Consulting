'use strict';

const article = require('./_article');
const {
  h2, h3, callout, checklist, doDont, table, stats,
} = require('../components');
const { allContentPages } = require('../nav');

const ARTIKEL = allContentPages().length;

const content = `
<p class="lead-p">
  Sattelfest ist ein Ratgeber für Menschen, die zum ersten Mal mit dem Rad draußen übernachten wollen.
  Kein Reisetagebuch, keine Heldengeschichten, keine Ausrüstungsschau – sondern der Versuch, die
  Fragen zu beantworten, die man sich vor der ersten Tour tatsächlich stellt.
</p>

${stats([
  { value: `${ARTIKEL}`, label: 'Artikel', note: 'In sechs Rubriken, alle kostenlos zugänglich.' },
  { value: '2', label: 'Werkzeuge', note: 'Packlisten-Generator und Etappen-Rechner.' },
  { value: '0 €', label: 'Für alles', note: 'Finanziert über gekennzeichnete Partnerlinks.' },
])}

${h2('Warum es diese Seite gibt', 'warum')}
<p>
  Wer sich zum ersten Mal mit Bikepacking beschäftigt, stößt auf zwei Sorten von Inhalten. Auf der
  einen Seite Reiseberichte von Menschen, die in 14 Tagen die Alpen überquert haben – schön zu lesen,
  aber unbrauchbar für die Frage, welche Satteltasche an ein 52er-Rahmen passt. Auf der anderen Seite
  Produkttests, die nach dem Kauf einer Ausrüstung fragen, bevor überhaupt klar ist, ob man draußen
  schlafen möchte.
</p>
<p>
  Was fehlt, ist das Dazwischen: die konkrete, unaufgeregte Auskunft. Wie weit komme ich am Tag
  wirklich? Wo darf ich schlafen? Was passiert, wenn es regnet? Und vor allem: <strong>Was brauche
  ich davon nicht?</strong>
</p>

${callout(
  'Die Grundhaltung dieser Seite',
  '<p>Die meisten Menschen scheitern nicht an fehlender Ausrüstung, sondern daran, dass sie nie losfahren – weil sie auf das richtige Rad, die richtige Woche oder die perfekte Route warten. Deshalb steht auf dieser Seite an vielen Stellen: Nimm das Rad, das du hast. Fahre 40 Kilometer. Schlaf einmal draußen. Alles Weitere ergibt sich daraus.</p>',
  'tip'
)}

${h2('Wie wir arbeiten', 'arbeitsweise')}
${doDont({
  doTitle: 'Das machen wir',
  doItems: [
    '<strong>Wir empfehlen Spezifikationen, keine Artikelnummern.</strong> R-Wert, Komforttemperatur, Volumen, Wassersäule, Preisrahmen – das gilt jahrelang. Modelle wechseln jede Saison.',
    '<strong>Wir raten oft vom Kauf ab.</strong> Kein neues Rad, kein Komplettset, kein Wasserfilter in Deutschland, keine Ultraleicht-Ausrüstung für die erste Tour.',
    '<strong>Wir nennen Zahlen.</strong> Gramm, Euro, Kilometer, Höhenmeter, Grad. Auch dann, wenn eine ungefähre Formulierung bequemer wäre.',
    '<strong>Wir schreiben dazu, was etwas nicht kann.</strong> Jedes Werkzeug auf dieser Seite hat einen Abschnitt über seine Grenzen.',
    '<strong>Wir korrigieren.</strong> Wenn eine Angabe falsch oder veraltet ist, wird sie geändert – Hinweise sind ausdrücklich willkommen.',
  ],
  dontTitle: 'Das machen wir nicht',
  dontItems: [
    '<strong>Keine erfundenen Tests.</strong> Wir behaupten nirgends, Ausrüstung getestet zu haben, die wir nicht getestet haben.',
    '<strong>Keine Tagespreise als Fakten.</strong> Preise ändern sich – wir nennen Rahmen und schreiben dazu, dass sie Rahmen sind.',
    '<strong>Keine Empfehlung wegen höherer Provision.</strong> Die Reihenfolge richtet sich nach dem Einsatzzweck.',
    '<strong>Kein Tracking, keine Cookies, keine Werbenetzwerke.</strong> Die Seite lädt nichts von fremden Servern nach.',
    '<strong>Keine künstliche Dringlichkeit.</strong> Weder Countdowns noch „nur noch heute"-Hinweise.',
  ],
})}

${h2('Woher die Angaben stammen', 'quellen')}
${table({
  head: ['Art der Angabe', 'Grundlage', 'Verlässlichkeit'],
  rows: [
    [
      'Gewichte',
      'Herstellerangaben und Erfahrungswerte für gängige, nicht ultraleichte Ausrüstung',
      'Richtwert, Abweichung 20 – 30 %',
    ],
    [
      'Preise',
      'Marktbeobachtung im deutschsprachigen Handel',
      'Rahmenwert, ändert sich laufend',
    ],
    [
      'Distanzen und Höhenmeter',
      'Offizielle Angaben der Routenträger und Radwegportale',
      'Gut, je nach Streckenvariante ± 10 %',
    ],
    [
      'Technische Faustregeln',
      'Etablierte Werte aus der Radsportpraxis (z. B. rund 500 Hm pro Stunde beladen)',
      'Bewährt, aber individuell verschieden',
    ],
    [
      'Rechtliche Angaben',
      'Landesrecht zu Wald, Naturschutz und Camping',
      '<strong>Ohne Gewähr – regional und laufend im Wandel</strong>',
    ],
    [
      'Empfehlungen',
      'Abwägung nach Einsatzzweck, nicht nach Vergütung',
      'Meinung, offengelegt begründet',
    ],
  ],
  note: 'Besonders bei den rechtlichen Angaben gilt: Sie unterscheiden sich nach Bundesland, Schutzgebiet und Grundstückseigentümer. Prüfe die Regelungen deiner Route eigenverantwortlich.',
})}

${h2('Wie sich die Seite finanziert', 'finanzierung')}
<p>
  Über Partnerlinks. Wenn du über einen mit Sternchen gekennzeichneten Link etwas kaufst, erhalten wir
  eine Provision vom Händler – für dich bleibt der Preis identisch. Das ist der einzige Weg, auf dem
  diese Seite Geld verdient.
</p>
<p>
  Dieses Modell hat einen offensichtlichen Interessenkonflikt: Wir verdienen nur, wenn du kaufst.
  Deshalb stehen die Regeln oben – und deshalb findest du hier auffällig oft den Rat, erst einmal gar
  nichts zu kaufen. Vollständig offengelegt ist das Modell im
  <a href="/affiliate-hinweis.html">Affiliate-Hinweis</a>.
</p>

${h2('Was diese Seite nicht ist', 'grenzen')}
${checklist(
  [
    '<strong>Keine Rechtsberatung.</strong> Die Angaben zu Übernachten, Wegerecht und Naturschutz sind Orientierung, keine verbindliche Auskunft.',
    '<strong>Keine medizinische Beratung.</strong> Bei anhaltenden Beschwerden gehört ein Arzt gefragt, nicht eine Website.',
    '<strong>Kein Ersatz für ein Bikefitting.</strong> Bei wiederkehrenden Schmerzen ist eine professionelle Sitzpositionsanalyse durch nichts zu ersetzen.',
    '<strong>Kein Testmagazin.</strong> Wir vergleichen Kategorien und Spezifikationen, keine Einzelmodelle im Labor.',
    '<strong>Kein Reiseveranstalter.</strong> Die Routen sind Vorschläge, keine gebuchten Angebote. Die Nutzung erfolgt auf eigene Gefahr.',
  ],
  { tone: 'dont' }
)}

${h2('Aufbau der Seite', 'aufbau')}
${table({
  head: ['Rubrik', 'Wofür sie da ist'],
  rows: [
    ['<a href="/einstieg/">Einstieg</a>', 'Die Grundlagen: Was ist das, welches Rad, was kostet es, wie weit komme ich?'],
    ['<a href="/taschen/">Taschen</a>', 'Das Herzstück: welche Tasche welche Aufgabe hat, wie du packst, wie du trocken bleibst'],
    ['<a href="/ausruestung/">Ausrüstung</a>', 'Alles, was in die Taschen kommt – nach Gewicht sortiert und ehrlich eingeordnet'],
    ['<a href="/routen/">Routen &amp; Planung</a>', 'Wohin, wo schlafen, wie planen – für Deutschland und Europa'],
    ['<a href="/unterwegs/">Unterwegs</a>', 'Der Teil, über den kaum jemand schreibt: Tagesrhythmus, Pannen, Körper, Sicherheit'],
    ['<a href="/service/">Service</a>', 'Zwei Rechner, der Newsletter und die häufigsten Fragen'],
  ],
})}

${h3('Zwei Werkzeuge, die tatsächlich rechnen', 'werkzeuge')}
${checklist([
  '<strong><a href="/tools/packlisten-generator.html">Packlisten-Generator</a>:</strong> Acht Fragen, eine vollständige Packliste mit Gramm-Angaben, Taschenempfehlung und Gesamtgewicht.',
  '<strong><a href="/tools/etappen-rechner.html">Etappen- &amp; Gewichts-Rechner</a>:</strong> Realistische Tagesdistanz aus Referenzstrecke, Höhenmetern, Untergrund, Gepäck und Erfahrung – plus Tageslänge mit Pausen.',
])}
<p>
  Beide rechnen vollständig in deinem Browser. Es werden keine Daten übertragen, nichts gespeichert
  und keine Cookies gesetzt.
</p>

${h2('Kontakt und Korrekturen', 'kontakt')}
<p>
  Wenn eine Angabe falsch ist, ein Link nicht funktioniert, sich eine Rechtslage geändert hat oder du
  eine Frage vermisst: Schreib uns. Korrekturen sind der wertvollste Beitrag, den man einer
  Ratgeberseite leisten kann, und häufig gestellte Fragen landen anschließend in den
  <a href="/faq.html">häufigen Fragen</a>. Die Kontaktadresse steht im
  <a href="/impressum.html">Impressum</a>.
</p>
`;

module.exports = article({
  href: '/ueber-uns.html',
  kicker: 'Service · Über uns',
  title: 'Über Sattelfest',
  metaTitle: 'Über Sattelfest: Wer dahintersteckt und wie wir empfehlen | Sattelfest',
  description:
    'Wer hinter Sattelfest steckt, warum es die Seite gibt, nach welchen Regeln Empfehlungen ausgesprochen werden, woher die Angaben stammen und wie sich das kostenlose Angebot finanziert.',
  lead:
    'Ein Ratgeber für Menschen, die zum ersten Mal mit dem Rad draußen übernachten wollen – kein Reisetagebuch, keine Ausrüstungsschau.',
  meta: [
    { icon: 'book', text: `${ARTIKEL} Artikel` },
    { icon: 'shield', text: 'Ohne Tracking und Cookies' },
    { icon: 'wallet', text: 'Finanzierung offengelegt' },
  ],
  image: false,
  toc: [
    { label: 'Warum es diese Seite gibt', id: 'warum' },
    { label: 'Wie wir arbeiten', id: 'arbeitsweise' },
    { label: 'Woher die Angaben stammen', id: 'quellen' },
    { label: 'Wie sich die Seite finanziert', id: 'finanzierung' },
    { label: 'Was diese Seite nicht ist', id: 'grenzen' },
    { label: 'Aufbau der Seite', id: 'aufbau' },
    { label: 'Kontakt und Korrekturen', id: 'kontakt' },
  ],
  content,
  faq: [
    {
      q: 'Wer schreibt die Inhalte auf Sattelfest?',
      a: '<p>Die Redaktion von Sattelfest. Die Betreiberangaben stehen vollständig im <a href="/impressum.html">Impressum</a>. Inhaltlich gilt: Wir geben an, worauf eine Empfehlung beruht – Spezifikation, Erfahrungswert oder Abwägung – und wir behaupten nirgends, Ausrüstung getestet zu haben, die wir nicht getestet haben.</p>',
    },
    {
      q: 'Wie verdient Sattelfest Geld?',
      a: '<p>Ausschließlich über Partnerlinks, die mit einem Sternchen gekennzeichnet sind. Kaufst du darüber etwas, erhalten wir eine Provision vom Händler – für dich bleibt der Preis identisch. Es gibt keine Werbebanner, kein Tracking, keine gesponserten Beiträge und keinen Verkauf von Daten. Alles offengelegt im <a href="/affiliate-hinweis.html">Affiliate-Hinweis</a>.</p>',
    },
    {
      q: 'Warum ratet ihr so oft vom Kauf ab, wenn ihr an Käufen verdient?',
      a: '<p>Weil eine Empfehlung nur dann etwas wert ist, wenn sie auch mal Nein sagt. Die häufigsten teuren Anfängerfehler sind ein neues Rad vor der ersten Tour, ein zu großes Taschenset und Ausrüstung, die nach zwei Touren wieder verkauft wird. Wer stattdessen erst zweimal draußen schläft und dann gezielt kauft, gibt insgesamt rund ein Drittel weniger aus.</p>',
    },
    {
      q: 'Wie aktuell sind die Angaben?',
      a: '<p>Preise und Produktkategorien werden regelmäßig geprüft, Distanzen und Höhenmeter stammen von den offiziellen Routenträgern. Bei rechtlichen Angaben – vor allem zum Übernachten in der Natur – gilt eine ausdrückliche Einschränkung: Sie unterscheiden sich nach Bundesland und Schutzgebiet und ändern sich laufend. Prüfe die Regeln deiner Route eigenverantwortlich.</p>',
    },
    {
      q: 'Werden auf der Seite Daten über mich gesammelt?',
      a: '<p>Nein. Die Seite ist statisch, setzt keine Cookies, bindet keine externen Schriften oder Skripte ein und nutzt keine Analysedienste. Auch die beiden Rechner arbeiten vollständig in deinem Browser – deine Eingaben verlassen dein Gerät nicht. Details in der <a href="/datenschutz.html">Datenschutzerklärung</a>.</p>',
    },
  ],
  related: [
    { href: '/affiliate-hinweis.html', label: 'Affiliate-Hinweis' },
    { href: '/faq.html', label: 'Häufige Fragen' },
    { href: '/newsletter.html', label: 'Newsletter' },
    { href: '/datenschutz.html', label: 'Datenschutzerklärung' },
  ],
});
