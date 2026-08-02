'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, pickGrid, affNotice,
} = require('../../components');
const { shops } = require('../../data/shops');

const content = `
<p class="lead-p">
  Für die erste Tour reicht dein Handy mit einer Offline-Karte. Das eigentliche Problem beim
  Navigieren ist nicht die Genauigkeit, sondern der Akku: Dauerhafte Navigation mit hellem Display
  leert ein Handy in vier bis sechs Stunden. Wer das löst, hat das Thema gelöst.
</p>

${stats([
  { value: '4–6 Std.', label: 'Handy mit Navi-Display', note: 'Deshalb ist Akkuplanung wichtiger als Kartenwahl.' },
  { value: '15–20 Std.', label: 'GPS-Radcomputer', note: 'Der eigentliche Grund, warum sich Geräte lohnen.' },
  { value: '0 €', label: 'Für die erste Tour', note: 'Offline-Karte aufs Handy und los.' },
])}

${h2('Die drei Wege zu navigieren', 'wege')}
${table({
  head: ['', 'Handy', 'GPS-Radcomputer', 'Papierkarte'],
  rows: [
    ['Anschaffung', '<strong>0 €</strong> (hast du)', '150 – 600 €', '10 – 20 € je Blatt'],
    ['Akkulaufzeit', '4 – 6 Std. mit Display an', '<strong>15 – 25 Std.</strong>', '<strong>Unbegrenzt</strong>'],
    ['Display bei Sonne', 'Oft schwer lesbar', '<strong>Transflektiv, gut lesbar</strong>', '<strong>Perfekt</strong>'],
    ['Regen und Nässe', 'Touchscreen wird unbedienbar', '<strong>Tasten funktionieren</strong>', 'Braucht Hülle'],
    ['Umplanen unterwegs', '<strong>Sehr komfortabel</strong>', 'Umständlich', 'Sehr gut, aber ungenau'],
    ['Kartendetail', '<strong>Sehr hoch</strong>', 'Hoch', 'Mittel'],
    ['Sturzrisiko', 'Teuer, wenn es fällt', 'Robust gebaut', 'Egal'],
    ['Als Backup', 'Ja', 'Ja', '<strong>Das eigentliche Backup</strong>'],
  ],
  note: 'Die verbreitetste Kombination auf Tour: Radcomputer für die laufende Navigation, Handy für Umplanung und Suche, ein Screenshot oder Kartenausschnitt als Notfallreserve.',
})}

${callout(
  'Der einfachste funktionierende Aufbau',
  '<p>Handy in der Oberrohrtasche mit <strong>ausgeschaltetem Display</strong>, Offline-Karte geladen, Sprachansage im Ohr oder Handy nur an Kreuzungen kurz anschauen. So hält der Akku statt sechs Stunden gut zwölf. Eine 10.000-mAh-Powerbank bringt dich damit über drei Tage.</p>',
  'tip'
)}

${h2('Offline-Karten: der wichtigste Schritt', 'offline')}
<p>
  Im Wald, im Mittelgebirge und in Grenzregionen ist Mobilfunkempfang keine Selbstverständlichkeit.
  Eine Route, die nur online funktioniert, ist genau dann weg, wenn du sie brauchst.
</p>
${checklist([
  '<strong>Lade die Karten der gesamten Region herunter</strong>, nicht nur den Korridor entlang der Route – Umleitungen führen dich schnell aus dem geladenen Bereich',
  '<strong>Speichere den GPX-Track zusätzlich lokal</strong>, nicht nur in der Cloud',
  '<strong>Mach Screenshots</strong> der Übersichtskarte und der kritischen Abzweigungen',
  '<strong>Notiere die Adresse deines Schlafplatzes auf Papier</strong> – das rettet dich, wenn das Handy stirbt',
  '<strong>Teste die Offline-Funktion vor der Tour</strong>: Flugmodus an, Route öffnen. Wenn sie dann nicht lädt, hast du sie nicht offline',
])}

${h2('Die Apps im Vergleich', 'apps')}
${table({
  head: ['App', 'Stärke', 'Schwäche', 'Kosten'],
  rows: [
    [
      'Komoot',
      'Untergrundangaben, sehr gute Radrouting-Qualität, große Community',
      'Routing führt manchmal auf sehr grobe Wege',
      'Einzelregion kostenlos, Weltpaket einmalig ca. 30 €',
    ],
    [
      'OsmAnd',
      'Vollständige OpenStreetMap offline, sehr detailliert, keine Abo-Pflicht',
      'Bedienung gewöhnungsbedürftig',
      'Kostenlos, Vollversion ca. 30 €',
    ],
    [
      'Organic Maps',
      'Schlank, schnell, komplett kostenlos, sehr genügsam mit dem Akku',
      'Kein leistungsfähiges Routing für Radrouten',
      'Kostenlos',
    ],
    [
      'Bikemap / Ride with GPS',
      'Sehr gute Routenplanung am Rechner',
      'Offline meist nur mit Abo',
      'Abo ca. 5 – 10 € im Monat',
    ],
    [
      'Google Maps',
      'Kennt jeder, gut für Supermärkte und Öffnungszeiten',
      'Radrouting mittelmäßig, Offline-Karten ohne Radrouten',
      'Kostenlos',
    ],
  ],
  note: 'Bewährte Kombination: Komoot für die Planung und Navigation, dazu Organic Maps oder OsmAnd als unabhängige Offline-Reserve. Zwei Karten-Apps kosten nichts und geben Sicherheit.',
})}

${h3('Worauf du beim Routing achtest', 'routing')}
${checklist(
  [
    '<strong>Prüfe den Untergrund</strong> in der Streckenübersicht: „Wanderweg“ und „Pfad“ heißen mit Gepäck oft Schieben.',
    '<strong>Traue keinem Höhenprofil ohne Blick auf die Steigungsprozente.</strong> 200 Höhenmeter auf 800 Metern Strecke sind eine Wand.',
    '<strong>Kontrolliere Fährverbindungen und Brücken</strong> – manche fahren nur saisonal oder nach Fahrplan.',
    '<strong>Rad-Routing führt manchmal über Privatwege</strong>, die in der Realität gesperrt sind.',
    '<strong>Automatisch generierte Routen bevorzugen kurze Wege</strong>, nicht schöne. Die Handarbeit lohnt sich.',
  ],
  { tone: 'dont' }
)}

${h2('Das Akkuproblem lösen', 'akku')}
${table({
  head: ['Maßnahme', 'Gewinn', 'Kosten', 'Aufwand'],
  rows: [
    ['Display aus, nur Sprachansage', '<strong>+6 bis 8 Std.</strong>', '0 €', 'Keiner'],
    ['Flugmodus mit GPS an', '+2 bis 3 Std.', '0 €', 'Keiner'],
    ['Helligkeit auf 30 Prozent', '+1 bis 2 Std.', '0 €', 'Keiner'],
    ['Powerbank 10.000 mAh', '2 – 3 volle Ladungen', '20 – 40 €', 'Nachts laden'],
    ['Powerbank 20.000 mAh', '4 – 6 volle Ladungen', '35 – 60 €', '400 g Mehrgewicht'],
    ['GPS-Radcomputer statt Handy', '<strong>15 – 25 Std. Laufzeit</strong>', '150 – 600 €', 'Route übertragen'],
    ['Nabendynamo mit USB-Lader', 'Unbegrenzt ab ca. 15 km/h', '200 – 400 €', 'Einbau, Laufradwechsel'],
    ['Solarpanel', '+20 bis 40 % an sonnigen Tagen', '60 – 150 €', 'Unzuverlässig in Mitteleuropa'],
  ],
  note: 'Die ersten drei Zeilen kosten nichts und lösen das Problem für Wochenendtouren vollständig. Der Nabendynamo lohnt sich erst ab etwa einer Woche ohne verlässliche Steckdose.',
})}

${affNotice()}

${h2('Kaufempfehlungen', 'empfehlungen')}
${pickGrid(
  [
    {
      badge: 'Für die erste Tour',
      title: 'Handyhalterung plus Powerbank',
      forWhom: 'Alle, die noch kein Navigationsgerät haben.',
      price: 'ca. 45 – 90 €',
      specs: [
        { k: 'Halterung', v: 'Klemm- oder Magnetsystem, stabil' },
        { k: 'Powerbank', v: '10.000 mAh, ca. 200 g' },
        { k: 'Kabel', v: 'Kurz, passend, plus Reserve' },
        { k: 'Wichtig', v: 'Wasserdichte Hülle oder Zipbeutel' },
      ],
      pros: [
        'Kostet einen Bruchteil eines Radcomputers',
        'Handy kannst du auch zum Umplanen und Suchen nutzen',
        'Powerbank lädt auch Licht und Kopfhörer',
      ],
      cons: [
        'Display bei Sonne oft schlecht lesbar',
        'Touchscreen bei Regen kaum bedienbar',
        'Ein Sturz kann teuer werden',
      ],
      partner: 'amazon',
      url: shops.handyhalter,
      ctaLabel: 'Halterungen ansehen',
    },
    {
      badge: 'Wenn du dabeibleibst',
      title: 'GPS-Radcomputer mit Kartendarstellung',
      forWhom: 'Ab der dritten oder vierten Tour, längere Strecken.',
      price: 'ca. 200 – 450 €',
      specs: [
        { k: 'Akkulaufzeit', v: '15 – 25 Std. mit Navigation' },
        { k: 'Display', v: 'Transflektiv, bei Sonne gut lesbar' },
        { k: 'Bedienung', v: 'Tasten – funktionieren mit nassen Fingern' },
        { k: 'Import', v: 'GPX per App oder Kabel' },
      ],
      pros: [
        'Löst das Akkuproblem auf einen Schlag',
        'Bei Sonne und Regen zuverlässig ablesbar',
        'Robust gebaut – ein Sturz ist kein Totalschaden',
        'Handy bleibt als Reserve unangetastet',
      ],
      cons: [
        'Teuer für den Einstieg',
        'Umplanen unterwegs ist umständlich',
      ],
      partner: 'amazon',
      url: shops.gpsComputer,
      ctaLabel: 'Radcomputer ansehen',
    },
    {
      badge: 'Das echte Backup',
      title: 'Papierkarte oder Ausdruck',
      forWhom: 'Jede Tour, die länger als einen Tag dauert.',
      price: 'ca. 0 – 20 €',
      specs: [
        { k: 'Gewicht', v: '30 – 90 g' },
        { k: 'Akku', v: 'Keiner nötig' },
        { k: 'Alternative', v: 'Ausdruck der Übersichtskarte' },
        { k: 'Ergänzung', v: 'Adresse des Schlafplatzes notieren' },
      ],
      pros: [
        'Funktioniert immer – auch wenn beide Geräte tot sind',
        'Gibt Überblick, den ein 3-Zoll-Display nie bietet',
        'Kostet fast nichts und wiegt fast nichts',
      ],
      cons: ['Ungenau im Detail, nicht aktuell bei Sperrungen'],
      note: 'Minimalvariante, die nichts kostet: die Übersichtskarte als Screenshot plus Adresse und Telefonnummer des Schlafplatzes auf einem Zettel in der Hüfttasche.',
    },
  ],
  { columns: 3 }
)}

${h2('Wenn du dich verfahren hast', 'verfahren')}
${checklist([
  '<strong>Anhalten, bevor du improvisierst.</strong> Weiterfahren in der Hoffnung, dass es schon passt, verschlimmert es fast immer.',
  '<strong>Zurück zum letzten sicheren Punkt.</strong> Meist billiger, als querfeldein den Anschluss zu suchen.',
  '<strong>Such die nächste Straße mit Namen.</strong> Ein Straßenschild plus Offline-Karte ordnet dich in 30 Sekunden ein.',
  '<strong>Frag jemanden.</strong> Auf dem Land wissen Leute erstaunlich genau, welcher Weg fahrbar ist und welcher nicht.',
  '<strong>Notfalls die Straße nehmen.</strong> Eine Landstraße ist selten schön, bringt dich aber zuverlässig ans Ziel.',
  '<strong>Bahnhöfe sind die besten Fixpunkte.</strong> Sie stehen auf jeder Karte, sind ausgeschildert und meist erreichbar.',
])}
`;

module.exports = article({
  href: '/ausruestung/navigation.html',
  kicker: 'Ausrüstung · Navigation',
  title: 'Navigation: Apps, GPS & Karten',
  metaTitle: 'Bikepacking-Navigation: Komoot, GPS-Gerät oder Handy? | Sattelfest',
  description:
    'Navigation beim Bikepacking: Handy, GPS-Radcomputer und Papierkarte im Vergleich, die wichtigsten Offline-Apps, worauf du beim Routing achtest – und wie du das Akkuproblem ohne teures Gerät löst.',
  lead:
    'Für die erste Tour reicht dein Handy. Das eigentliche Problem ist nicht die Karte, sondern der Akku.',
  meta: [
    { icon: 'map', text: '9 Minuten Lesezeit' },
    { icon: 'route', text: 'Apps im Vergleich' },
    { icon: 'check', text: 'Mit Akku-Lösungen' },
  ],
  toc: [
    { label: 'Die drei Wege zu navigieren', id: 'wege' },
    { label: 'Offline-Karten: der wichtigste Schritt', id: 'offline' },
    { label: 'Die Apps im Vergleich', id: 'apps' },
    { label: 'Das Akkuproblem lösen', id: 'akku' },
    { label: 'Kaufempfehlungen', id: 'empfehlungen' },
    { label: 'Wenn du dich verfahren hast', id: 'verfahren' },
  ],
  content,
  faq: [
    {
      q: 'Reicht das Handy zum Navigieren beim Bikepacking?',
      a: '<p>Für die ersten Touren ja. Das Problem ist nicht die Kartenqualität, sondern der Akku: Dauerhafte Navigation mit hellem Display leert ein Handy in vier bis sechs Stunden. Mit ausgeschaltetem Display, Sprachansage und einer 10.000-mAh-Powerbank kommst du problemlos über ein verlängertes Wochenende.</p>',
    },
    {
      q: 'Welche App eignet sich am besten fürs Bikepacking?',
      a: '<p>Komoot ist im deutschsprachigen Raum am verbreitetsten und liefert als Einziges verlässliche Untergrundangaben – wichtig, um „Wanderweg“ von „Radweg“ zu unterscheiden. Als unabhängige Offline-Reserve eignen sich Organic Maps (kostenlos, sehr akkuschonend) oder OsmAnd. Zwei Karten-Apps kosten nichts und geben Sicherheit.</p>',
    },
    {
      q: 'Wann lohnt sich ein GPS-Radcomputer?',
      a: '<p>Ab der dritten oder vierten Tour und bei Strecken über mehrere Tage. Der eigentliche Grund ist nicht die Genauigkeit, sondern die Akkulaufzeit von 15 bis 25 Stunden, das bei Sonne lesbare transflektive Display und die Tastenbedienung, die auch mit nassen Fingern funktioniert. Rechne mit 200 bis 450 Euro.</p>',
    },
    {
      q: 'Wie stelle ich sicher, dass die Karte offline funktioniert?',
      a: '<p>Lade die Karte der gesamten Region herunter (nicht nur den Streckenkorridor), speichere den GPX-Track lokal statt in der Cloud und teste dann mit eingeschaltetem Flugmodus, ob die Route lädt. Wenn nicht, hast du sie nicht offline. Mach zusätzlich Screenshots der Übersicht und notiere die Adresse des Schlafplatzes auf Papier.</p>',
    },
    {
      q: 'Lohnt sich ein Nabendynamo fürs Bikepacking?',
      a: '<p>Erst ab etwa einer Woche ohne verlässliche Steckdose. Er kostet mit USB-Lader 200 bis 400 Euro plus Einbau und liefert ab rund 15 km/h dauerhaft Strom. Für Wochenendtouren und Reisen mit Campingplatz-Übernachtungen ist eine Powerbank deutlich günstiger und völlig ausreichend.</p>',
    },
  ],
  related: [
    { href: '/routen/route-selbst-planen.html', label: 'Route selbst planen' },
    { href: '/ausruestung/licht-strom.html', label: 'Licht, Strom & Powerbank' },
    { href: '/taschen/kleine-taschen.html', label: 'Oberrohr-, Gabel- & Stemtaschen' },
    { href: '/unterwegs/sicherheit-notfall.html', label: 'Sicherheit & Notfall' },
  ],
});
