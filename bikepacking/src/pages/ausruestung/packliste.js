'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, weightList, icon,
} = require('../../components');

const content = `
<p class="lead-p">
  Diese Packliste hat eine Spalte, die in den meisten fehlt: das Gewicht. Denn die Frage ist nie,
  ob etwas nützlich sein <em>könnte</em> – das ist fast alles. Die Frage ist, ob es sein Gewicht wert
  ist. Jede Zeile ist deshalb mit Gramm-Angabe und einer klaren Einordnung versehen.
</p>

${stats([
  { value: '8,5 kg', label: 'Sommer, Zelt, 3 Nächte', note: 'Ohne Wasser und ohne das, was du anhast.' },
  { value: '5,4 kg', label: 'Ohne Camping-Ausrüstung', note: 'Credit-Card-Variante mit Pension.' },
  { value: '3', label: 'Kategorien', note: 'Pflicht, sinnvoll, Ballast – ohne Grauzone.' },
])}

${h2('Wie du die Liste liest', 'lesen')}
${table({
  head: ['Kennzeichnung', 'Bedeutung'],
  rows: [
    ['<strong>Pflicht</strong>', 'Ohne das fährst du nicht los. Sicherheit, Wetterschutz, Reparatur.'],
    ['<strong>Sinnvoll</strong>', 'Macht die Tour deutlich angenehmer, ist aber verzichtbar.'],
    ['<strong>Ballast</strong>', 'Wird typischerweise mitgenommen und nie benutzt. Zählt nicht in die Summe.'],
  ],
  note: 'Die Gewichtsangaben sind Richtwerte für gängige, nicht ultraleichte Ausrüstung. Ultraleicht-Varianten wiegen oft 30 bis 50 Prozent weniger und kosten das Zwei- bis Dreifache.',
})}

${h2('Schlafen', 'schlafen')}
${weightList({
  title: 'Sommer, Zelt, 3 Nächte',
  items: [
    { name: '1-Personen-Zelt', note: 'Doppelwandig, freistehend oder mit Heringen', g: 1500, tag: 'pflicht' },
    { name: 'Schlafsack, Komfort ca. +10 °C', note: 'Daune, im Kompressionssack', g: 600, tag: 'pflicht' },
    { name: 'Isomatte, R-Wert ab 2,5', note: 'Aufblasbar, mit Packsack', g: 450, tag: 'pflicht' },
    { name: 'Aufblasbares Kissen', note: 'Alternativ: Kleidersack als Kissen (0 g)', g: 80, tag: 'sinnvoll' },
    { name: 'Zeltunterlage (Footprint)', note: 'Dünne Baufolie tut es auch – 90 g statt 300 g', g: 300, tag: 'ballast' },
    { name: 'Zusätzliche Heringe', note: 'Die mitgelieferten reichen fast immer', g: 120, tag: 'ballast' },
  ],
})}

${callout(
  'Der größte Hebel liegt hier',
  '<p>Schlafsystem und Zelt machen zusammen etwa <strong>ein Drittel des Gesamtgewichts</strong> aus. Wer Gewicht sparen will, spart hier – nicht bei der Zahnbürste. Ein Tarp statt Zelt spart 900 Gramm, ein Quilt statt Schlafsack 200 Gramm, eine kürzere Isomatte 150 Gramm. Zusammen ist das mehr als der gesamte Rest der Liste an Optimierungspotenzial hergibt.</p>',
  'tip'
)}

${h2('Kleidung', 'kleidung')}
<p>
  Die Grundregel lautet: <strong>eine Garnitur am Körper, eine im Gepäck.</strong> Alles darüber
  hinaus ist Ballast, weil du in einer Bikepacking-Woche ohnehin waschen musst – oder eben nicht,
  und dann macht die dritte Hose es auch nicht besser.
</p>
${weightList({
  title: 'Sommer, 3 Nächte',
  items: [
    { name: 'Radhose mit Sitzpolster', note: 'Angezogen – zählt nicht ins Gepäck', g: 0, tag: 'pflicht' },
    { name: 'Trikot oder Merino-Shirt', note: 'Angezogen', g: 0, tag: 'pflicht' },
    { name: 'Zweites Shirt', note: 'Merino – riecht auch nach drei Tagen erträglich', g: 160, tag: 'pflicht' },
    { name: 'Unterwäsche und Socken, 2 Paar', g: 140, tag: 'pflicht' },
    { name: 'Regenjacke, wasserdicht mit Kapuze', note: 'Griffbereit packen, nicht ganz unten', g: 320, tag: 'pflicht' },
    { name: 'Leichte Isolationsschicht', note: 'Fleece oder dünne Daunenjacke für abends', g: 280, tag: 'pflicht' },
    { name: 'Abendkleidung: leichte Hose und Shirt', note: 'Auch die Schlafkleidung', g: 350, tag: 'sinnvoll' },
    { name: 'Kurze Handschuhe', g: 60, tag: 'sinnvoll' },
    { name: 'Buff / Multifunktionstuch', note: 'Kopf, Hals, Staubschutz, Waschlappen', g: 40, tag: 'sinnvoll' },
    { name: 'Leichte Schuhe für abends', note: 'Sandalen oder Barfußschuhe', g: 300, tag: 'sinnvoll' },
    { name: 'Zweite Radhose', note: 'Erst ab 5 Nächten sinnvoll', g: 220, tag: 'ballast' },
    { name: 'Regenhose im Hochsommer', note: 'Bei über 18 °C wirst du sie nicht anziehen', g: 200, tag: 'ballast' },
    { name: 'Handtuch groß', note: 'Ein Mikrofasertuch in Handgröße reicht', g: 300, tag: 'ballast' },
  ],
})}

${h2('Wasser und Küche', 'kueche')}
${weightList({
  title: 'Mit Kocher',
  items: [
    { name: 'Trinkflaschen 2 × 750 ml', note: 'Leergewicht – gefüllt 1,7 kg', g: 200, tag: 'pflicht' },
    { name: 'Gaskocher (Schraubkocher)', g: 90, tag: 'sinnvoll' },
    { name: 'Gaskartusche 230 g', note: 'Reicht für etwa 8 – 12 Kochvorgänge', g: 380, tag: 'sinnvoll' },
    { name: 'Topf 700 – 900 ml mit Deckel', note: 'Dient gleichzeitig als Schüssel', g: 180, tag: 'sinnvoll' },
    { name: 'Löffel, lang', note: 'Für Beutelmahlzeiten', g: 20, tag: 'sinnvoll' },
    { name: 'Feuerzeug plus Reserve', g: 25, tag: 'pflicht' },
    { name: 'Spülschwamm und Tuch', g: 30, tag: 'sinnvoll' },
    { name: 'Tagesverpflegung und Riegel', note: 'Wird unterwegs nachgekauft', g: 600, tag: 'pflicht' },
    { name: 'Faltflasche 1 l als Reserve', note: 'Wiegt leer fast nichts', g: 45, tag: 'sinnvoll' },
    { name: 'Teller und Tasse extra', note: 'Der Topf ist beides', g: 200, tag: 'ballast' },
    { name: 'Gewürzset', note: 'Salz und Pfeffer in einem Zipbeutel: 15 g', g: 180, tag: 'ballast' },
    { name: 'Wasserfilter in Mitteleuropa', note: 'Nur bei abgelegenen Routen nötig', g: 120, tag: 'ballast' },
  ],
})}

${h2('Licht, Strom und Navigation', 'technik')}
${weightList({
  items: [
    { name: 'Frontlicht', note: 'StVZO-konform oder ab 300 Lumen', g: 130, tag: 'pflicht' },
    { name: 'Rücklicht plus Reserve', note: 'Ein Rücklicht fällt immer aus', g: 80, tag: 'pflicht' },
    { name: 'Stirnlampe', note: 'Zelt aufbauen, kochen, nachts raus', g: 90, tag: 'pflicht' },
    { name: 'Powerbank 10.000 mAh', note: 'Lädt ein Handy 2 – 3 Mal voll', g: 200, tag: 'pflicht' },
    { name: 'Ladegerät und Kabel', note: 'Ein Mehrfach-Kabel spart Gewicht', g: 150, tag: 'pflicht' },
    { name: 'Handy mit Offline-Karten', note: 'Angezogen bzw. in der Oberrohrtasche', g: 0, tag: 'pflicht' },
    { name: 'GPS-Radcomputer', note: 'Für die erste Tour nicht nötig', g: 100, tag: 'sinnvoll' },
    { name: 'Zweite Powerbank', note: 'Erst ab 4 Nächten ohne Steckdose', g: 400, tag: 'ballast' },
    { name: 'Kamera', note: 'Wenn du sie wirklich nutzt: Pflicht. Wenn nicht: Ballast.', g: 450, tag: 'ballast' },
  ],
})}

${h2('Werkzeug und Reparatur', 'werkzeug')}
${weightList({
  items: [
    { name: 'Multitool mit Kettennieter', g: 160, tag: 'pflicht' },
    { name: 'Mini-Handpumpe', note: 'CO₂ allein reicht nicht – nach zwei Patronen ist Schluss', g: 110, tag: 'pflicht' },
    { name: '2 Ersatzschläuche', note: 'In deiner Größe, mit passendem Ventil', g: 300, tag: 'pflicht' },
    { name: 'Flickzeug und 2 Reifenheber', g: 60, tag: 'pflicht' },
    { name: 'Kettenschloss', note: 'Passend zur Gangzahl deiner Kette', g: 15, tag: 'pflicht' },
    { name: 'Ersatz-Schaltauge', note: 'Rahmenspezifisch – unterwegs nirgends zu bekommen', g: 30, tag: 'pflicht' },
    { name: 'Kabelbinder und Gewebeband', note: 'Band um die Pumpe wickeln statt Rolle mitnehmen', g: 40, tag: 'pflicht' },
    { name: 'Kettenöl klein', g: 50, tag: 'sinnvoll' },
    { name: 'Reifen-Flicken für große Schnitte', note: 'Auch ein Stück Geldschein tut es', g: 20, tag: 'sinnvoll' },
    { name: 'Fahrradschloss', note: 'Faltschloss oder schweres Kabel', g: 500, tag: 'sinnvoll' },
    { name: 'Ersatzspeichen', note: 'Nur bei sehr langen oder abgelegenen Touren', g: 60, tag: 'ballast' },
    { name: 'Vollständiger Werkzeugkoffer', note: 'Ein Multitool deckt 95 Prozent ab', g: 700, tag: 'ballast' },
  ],
})}

${h2('Körper und Papiere', 'koerper')}
${weightList({
  items: [
    { name: 'Erste-Hilfe-Set, klein', note: 'Mit Blasenpflaster und Schmerzmittel', g: 180, tag: 'pflicht' },
    { name: 'Sitzcreme (Chamois-Creme)', note: 'Ab Tag 2 praktisch unverzichtbar', g: 60, tag: 'pflicht' },
    { name: 'Sonnencreme LSF 30+ und Lippenpflege', g: 90, tag: 'pflicht' },
    { name: 'Zahnbürste, Zahnpasta klein, Seifenblatt', g: 80, tag: 'pflicht' },
    { name: 'Mikrofaser-Handtuch, klein', g: 90, tag: 'pflicht' },
    { name: 'Ausweis, Versichertenkarte, Bargeld', note: 'In einer Hüfttasche am Körper', g: 60, tag: 'pflicht' },
    { name: 'Müllbeutel', note: 'Für den eigenen Müll – selbstverständlich', g: 15, tag: 'pflicht' },
    { name: 'Ohrstöpsel', note: 'Auf Campingplätzen Gold wert', g: 10, tag: 'sinnvoll' },
    { name: 'Insektenschutz', note: 'Mai bis September an Gewässern', g: 60, tag: 'sinnvoll' },
    { name: 'Große Kulturtasche', note: 'Ein Zipbeutel wiegt 5 g', g: 150, tag: 'ballast' },
    { name: 'Duschgel und Shampoo in Flaschen', note: 'Seifenblätter oder ein kleines Stück Kernseife', g: 250, tag: 'ballast' },
  ],
})}

${h2('Die Gesamtrechnung', 'gesamt')}
${table({
  head: ['Kategorie', 'Sommer, Zelt', 'Übergangszeit', 'Ohne Camping'],
  rows: [
    ['Schlafen', '2.630 g', '2.980 g', '150 g'],
    ['Kleidung', '1.650 g', '2.110 g', '1.650 g'],
    ['Wasser & Küche', '1.570 g', '1.570 g', '925 g'],
    ['Licht, Strom, Navi', '750 g', '750 g', '750 g'],
    ['Werkzeug', '1.285 g', '1.285 g', '1.285 g'],
    ['Körper & Papiere', '645 g', '645 g', '645 g'],
    ['<strong>Summe</strong>', '<strong>8,5 kg</strong>', '<strong>9,3 kg</strong>', '<strong>5,4 kg</strong>'],
    ['Plus Wasser 1,5 l', '10,0 kg', '10,8 kg', '6,9 kg'],
  ],
  note: 'Die Spaltensummen entsprechen den Summen der Listen oben (jeweils ohne Ballast-Zeilen). Nicht enthalten: was du am Körper trägst, und die leeren Taschen (weitere 1,4 bis 2,9 kg). Das Gesamtsystemgewicht liegt damit bei etwa 11 bis 13 Kilo zusätzlich zum Rad.',
})}

${callout(
  'Die Zielmarke',
  '<p><strong>Unter 10 Kilo Gepäck</strong> ist die Marke, ab der ein Bikepacking-Setup sich leicht anfühlt. Unter 7 Kilo bist du im ambitionierten Bereich. Über 15 Kilo solltest du dir ehrlich die Frage stellen, ob ein Gepäckträger nicht die angenehmere Lösung wäre – dort trägt sich das Gewicht besser.</p>',
  'money'
)}

${h2('Die sieben Dinge, die fast alle zu viel mitnehmen', 'zuviel')}
${checklist(
  [
    '<strong>Zu viel Kleidung.</strong> Die dritte Garnitur bleibt im Sack. Zwei reichen, immer.',
    '<strong>Zu viel Essen.</strong> In Deutschland kommst du alle 30 bis 50 Kilometer an einem Supermarkt vorbei.',
    '<strong>Ein zu großes Handtuch.</strong> Ein Mikrofasertuch in Handgröße trocknet dich und trocknet selbst schneller.',
    '<strong>Das Buch.</strong> Nach 80 Kilometern schläfst du um halb zehn ein.',
    '<strong>Kochgeschirr für zwei Gänge.</strong> Ein Topf ist Topf, Teller und Tasse.',
    '<strong>Werkzeug für Reparaturen, die du nicht kannst.</strong> Nimm nur mit, was du auch anwenden kannst.',
    '<strong>Der Ersatz für den Ersatz.</strong> Zwei Schläuche sind genug, drei sind Aberglaube.',
  ],
  { tone: 'dont' }
)}

${h3('Und die vier, die fast alle vergessen', 'vergessen')}
${checklist([
  '<strong>Ersatz-Schaltauge.</strong> Rahmenspezifisch, kostet 20 bis 40 Euro und rettet die Tour.',
  '<strong>Zweites Rücklicht.</strong> Rücklichter fallen aus, und im Dunkeln auf der Landstraße ist das gefährlich.',
  '<strong>Sitzcreme.</strong> Ab dem zweiten Tag entscheidet sie über deine Laune.',
  '<strong>Müllbeutel.</strong> Auf Trekkingplätzen gibt es keine Tonnen. Was du mitbringst, nimmst du mit.',
])}

<p style="margin-top:1.5rem">
  <a class="btn btn--primary" href="/tools/packlisten-generator.html">Persönliche Packliste erstellen ${icon('arrow', 'ico')}</a>
</p>
`;

module.exports = article({
  href: '/ausruestung/packliste.html',
  kicker: 'Ausrüstung · Packliste',
  title: 'Die Bikepacking-Packliste mit Gewichten',
  metaTitle: 'Bikepacking-Packliste 2026: Mit Gramm-Angaben und Gesamtgewicht | Sattelfest',
  description:
    'Die vollständige Bikepacking-Packliste mit Gewichtsangaben: Schlafen, Kleidung, Küche, Technik, Werkzeug und Körperpflege – jede Zeile eingeordnet als Pflicht, sinnvoll oder Ballast. Summe: 8,4 kg im Sommer.',
  lead:
    'Jede Zeile mit Gramm-Angabe und klarer Einordnung. Denn nützlich ist fast alles – die Frage ist, ob es sein Gewicht wert ist.',
  meta: [
    { icon: 'weight', text: '12 Minuten Lesezeit' },
    { icon: 'bag', text: 'Mit Gesamtgewichten' },
    { icon: 'check', text: 'Pflicht, sinnvoll, Ballast' },
  ],
  toc: [
    { label: 'Wie du die Liste liest', id: 'lesen' },
    { label: 'Schlafen', id: 'schlafen' },
    { label: 'Kleidung', id: 'kleidung' },
    { label: 'Wasser und Küche', id: 'kueche' },
    { label: 'Licht, Strom und Navigation', id: 'technik' },
    { label: 'Werkzeug und Reparatur', id: 'werkzeug' },
    { label: 'Körper und Papiere', id: 'koerper' },
    { label: 'Die Gesamtrechnung', id: 'gesamt' },
    { label: 'Was fast alle zu viel mitnehmen', id: 'zuviel' },
  ],
  content,
  faq: [
    {
      q: 'Wie viel Gepäck sollte man beim Bikepacking mitnehmen?',
      a: '<p>Unter 10 Kilogramm ist die Marke, ab der sich ein Setup leicht anfühlt. Eine vollständige Sommerausrüstung mit Zelt liegt bei etwa 8,4 Kilo ohne Wasser, in der Übergangszeit bei rund 10 Kilo. Über 15 Kilo solltest du prüfen, ob ein Gepäckträger nicht die angenehmere Lösung wäre – dort trägt sich Gewicht besser als in Bikepacking-Taschen.</p>',
    },
    {
      q: 'Wo spare ich beim Bikepacking am meisten Gewicht?',
      a: '<p>Beim Schlafsystem. Zelt, Schlafsack und Isomatte machen zusammen etwa ein Drittel des Gesamtgewichts aus. Ein Tarp statt Zelt spart 900 Gramm, ein Quilt statt Schlafsack 200, eine kürzere Isomatte 150. Das ist mehr Potenzial, als der gesamte Rest der Packliste hergibt – Zahnbürsten abzusägen bringt nichts.</p>',
    },
    {
      q: 'Was nehmen Bikepacking-Anfänger typischerweise zu viel mit?',
      a: '<p>Zu viel Kleidung (zwei Garnituren reichen), zu viel Essen (in Deutschland liegt alle 30 bis 50 Kilometer ein Supermarkt), ein zu großes Handtuch, ein Buch, Kochgeschirr für mehrere Gänge und Werkzeug für Reparaturen, die sie nicht durchführen können. Zusammen sind das schnell drei bis vier Kilo, die nie ausgepackt werden.</p>',
    },
    {
      q: 'Was vergessen Bikepacking-Anfänger am häufigsten?',
      a: '<p>Vier Dinge: ein Ersatz-Schaltauge (rahmenspezifisch, unterwegs nirgends zu bekommen), ein zweites Rücklicht (Rücklichter fallen aus, und im Dunkeln ist das gefährlich), Sitzcreme (entscheidet ab Tag zwei über die Laune) und einen Müllbeutel – auf Trekkingplätzen gibt es keine Mülltonnen.</p>',
    },
    {
      q: 'Brauche ich einen Wasserfilter in Deutschland?',
      a: '<p>In der Regel nicht. Friedhöfe, Sportplätze, Campingplätze, Gaststätten und Supermärkte liefern dir in Mitteleuropa alle paar Stunden Trinkwasser. Ein Filter oder Entkeimungstabletten lohnen sich bei sehr abgelegenen Routen, im Hochsommer bei langen Etappen ohne Ortschaften oder auf Touren in Südeuropa und Skandinavien.</p>',
    },
  ],
  related: [
    { href: '/tools/packlisten-generator.html', label: 'Packlisten-Generator' },
    { href: '/ausruestung/schlafsystem.html', label: 'Zelt, Tarp oder Biwaksack?' },
    { href: '/taschen/richtig-packen.html', label: 'Richtig packen: Gewichtsverteilung' },
    { href: '/ausruestung/werkzeug-reparatur.html', label: 'Werkzeug & Reparatur-Kit' },
  ],
});
