'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, doDont, affNotice, pickGrid,
} = require('../../components');
const { shops } = require('../../data/shops');

const content = `
<p class="lead-p">
  In Bikepacking-Foren gelten Packtaschen als altmodisch. Das ist Unsinn und kostet Einsteiger viel
  Geld. Für einen erheblichen Teil der Touren – vor allem die typischen deutschen Flussradwege – sind
  Gepäckträger und Packtaschen schlicht die bessere Lösung. Hier steht, wann welches System gewinnt.
</p>

${stats([
  { value: '60–100 l', label: 'Packtaschen-System', note: 'Gegen 20–45 Liter beim Bikepacking-Set.' },
  { value: '−40 %', label: 'Preisunterschied', note: 'Ein Packtaschen-Setup kostet deutlich weniger.' },
  { value: '+1,5 kg', label: 'Mehrgewicht', note: 'Träger und größere Taschen wiegen mehr.' },
])}

${h2('Der direkte Vergleich', 'vergleich')}
${table({
  head: ['Kriterium', 'Bikepacking-Taschen', 'Gepäckträger + Packtaschen'],
  rows: [
    ['Volumen', '20 – 45 l', '<strong>60 – 100 l</strong>'],
    ['Preis Komplettsystem', '355 – 790 €', '<strong>180 – 400 € inkl. Träger</strong>'],
    ['Gewicht des Systems', '<strong>1,4 – 2,9 kg</strong>', '3,5 – 5 kg'],
    ['Wasserdichtigkeit', 'Oft nur wasserabweisend', '<strong>Meist echt wasserdicht</strong>'],
    ['Zugriff unterwegs', 'Umständlich', '<strong>Deckel auf, fertig</strong>'],
    ['Packen und Umpacken', 'Zeitaufwendig, Reihenfolge zählt', '<strong>Einfach, alles reinwerfen</strong>'],
    ['Fahrverhalten schmale Wege', '<strong>Schmal, wendig</strong>', 'Breit, hakt an Pfosten und Ästen'],
    ['Fahrverhalten Asphalt', 'Etwas windschnittiger', 'Kaum Unterschied bei Reisetempo'],
    ['Geländetauglichkeit', '<strong>Deutlich besser</strong>', 'Auf Trails hinderlich'],
    ['Voraussetzung am Rad', 'Praktisch keine', 'Braucht Ösen oder Klemmträger'],
    ['Bahnfahrt und Transport', 'Taschen bleiben am Rad', '<strong>Taschen abnehmen und tragen</strong>'],
    ['Einkaufen unterwegs', 'Wenig Reserve', '<strong>Platz für einen ganzen Einkauf</strong>'],
  ],
  note: 'Die fett markierten Felder zeigen den jeweiligen Sieger. Es gewinnt keins der Systeme durchgehend – und genau das ist der Punkt.',
})}

${h2('Wann Packtaschen die bessere Wahl sind', 'packtaschen-besser')}
${doDont({
  doTitle: 'Packtaschen gewinnen, wenn …',
  doItems: [
    'Du überwiegend auf <strong>Asphalt und breiten Radwegen</strong> unterwegs bist – Rhein, Elbe, Donau, Bodensee',
    'Du <strong>länger als eine Woche</strong> unterwegs bist und viel Verpflegung dabeihast',
    'Du im <strong>Frühjahr, Herbst oder Winter</strong> fährst und dickere Kleidung brauchst',
    'Du <strong>zu zweit mit Kind</strong> oder mit gemeinsamer Ausrüstung reist',
    'Du <strong>viel Wert auf Wasserdichtigkeit</strong> legst und keine Lust auf Packsack-Systeme hast',
    'Dein Rad einen <strong>Gepäckträger ab Werk</strong> hat – dann kostet der Einstieg fast nichts',
    'Du <strong>günstig anfangen</strong> willst: Gebrauchte Packtaschen gibt es ab 30 Euro das Paar',
  ],
  dontTitle: 'Bikepacking-Taschen gewinnen, wenn …',
  dontItems: [
    'Du auf <strong>Schotter, Waldwegen oder Trails</strong> fährst',
    'Dein Rad <strong>keine Gepäckträger-Ösen</strong> hat – Rennrad, Gravel, viele MTBs',
    'Du <strong>schnell</strong> unterwegs sein willst und jedes Kilo zählt',
    'Du <strong>drei bis vier Nächte</strong> im Sommer fährst und leicht packen kannst',
    'Du das Rad <strong>tragen oder schieben</strong> musst (Hike-a-Bike, Treppen, Bahnsteige)',
    'Du das Rad auch im Alltag nutzt und <strong>keinen dauerhaften Träger</strong> montieren willst',
  ],
})}

${callout(
  'Die ehrliche Zahl',
  '<p>Ein großer Teil der Bikepacking-Touren in Deutschland führt über asphaltierte Fernradwege. Auf genau diesen Strecken bringt das Bikepacking-System keinen einzigen seiner Vorteile zur Geltung – dafür kostet es mehr, fasst weniger und ist schwerer zu packen. Wer weiß, dass er hauptsächlich Flussradwege fahren wird, kauft mit Packtaschen besser ein.</p>',
  'money'
)}

${h2('Die Mischform: das beste beider Systeme', 'mischform')}
<p>
  In der Praxis fahren viele erfahrene Tourenfahrer eine Kombination. Das ist keine Notlösung,
  sondern oft das durchdachteste Setup überhaupt:
</p>

${table({
  head: ['Kombination', 'Volumen', 'Passt zu'],
  rows: [
    [
      'Gepäckträger hinten + Lenkerrolle',
      '50 – 60 l',
      '<strong>Die beste Einsteigerlösung.</strong> Viel Platz hinten, Schlafsystem vorn.',
    ],
    [
      'Satteltasche + Gepäckträger vorn (Lowrider)',
      '40 – 55 l',
      'Klassisch für lange Reisen, sehr gutes Fahrverhalten',
    ],
    [
      'Volles Bikepacking-Set + kleiner Gepäckträger',
      '45 – 60 l',
      'Für Wintertouren oder wenn Verpflegung für Tage mitmuss',
    ],
    [
      'Rahmentasche + Packtaschen hinten',
      '50 – 60 l',
      'Werkzeug und Wasser zentral, Rest im Gepäck',
    ],
  ],
  note: 'Der Klassiker unter den Mischformen: hinten zwei Packtaschen, vorn eine Lenkerrolle für das Schlafsystem. Kostet unter 250 Euro und deckt fast jede Tour ab.',
})}

${h3('Warum diese Kombination so gut funktioniert', 'warum-misch')}
${checklist([
  'Der Gepäckträger trägt genau dort, wo eine Satteltasche pendelt – und trägt dabei mehr',
  'Die Lenkerrolle nimmt das Voluminöse auf, für das Packtaschen zu schade wären',
  'Packtaschen sind zuverlässig wasserdicht, ohne dass du Packsäcke brauchst',
  'Du kannst abends beide Taschen abnehmen und mitnehmen – wie einen Koffer',
  'Der Träger kostet 40 bis 90 Euro, gebrauchte Packtaschen 30 bis 80 Euro das Paar',
])}

${h2('Wenn dein Rad keine Ösen hat', 'ohne-oesen')}
<p>
  Das ist der häufigste Grund gegen Packtaschen – und er ist lösbarer, als viele denken:
</p>
${table({
  head: ['Lösung', 'Zuladung', 'Kosten', 'Anmerkung'],
  rows: [
    ['Sattelrohr-Klemmträger', '8 – 10 kg', '60 – 130 €', 'Klemmt am Sattelrohr, braucht keine Ösen'],
    ['Sattelstützen-Träger', '5 – 9 kg', '40 – 90 €', 'Nicht für Carbon-Stützen geeignet'],
    ['Achsmontierter Leichtträger', '9 – 15 kg', '150 – 300 €', 'Sehr stabil, wird an der Steckachse verschraubt'],
    ['Schellenadapter für Ösen', '10 – 25 kg', '15 – 30 €', 'Ersetzt fehlende Ösen an der Sitzstrebe'],
  ],
  note: 'Bei Carbonrahmen und Carbonstützen unbedingt die Herstellerfreigabe prüfen. Viele Hersteller schließen Klemmträger ausdrücklich aus.',
})}

${affNotice()}

${h2('Konkrete Empfehlungen', 'empfehlungen')}
${pickGrid(
  [
    {
      badge: 'Bester Einstieg überhaupt',
      title: 'Gepäckträger + zwei Hinterradtaschen',
      forWhom: 'Alle, die günstig anfangen und auf Radwegen unterwegs sind.',
      price: 'ca. 130 – 280 € komplett',
      specs: [
        { k: 'Volumen', v: '2 × 20 l' },
        { k: 'Gewicht', v: 'Träger 600 g, Taschen 1,6 kg' },
        { k: 'Wasserdicht', v: 'Ja, bei Rollverschluss-Modellen' },
        { k: 'Voraussetzung', v: 'Ösen oder Klemmträger' },
      ],
      pros: [
        'Doppeltes Volumen zum halben Preis eines Bikepacking-Sets',
        'Echt wasserdicht, ohne zusätzliche Packsäcke',
        'Abends abnehmen und wie Taschen tragen',
        'Platz für einen kompletten Supermarkteinkauf',
      ],
      cons: [
        'Breit – auf schmalen Wegen und in der Bahn hinderlich',
        'Rund 1,5 kg schwerer als ein Bikepacking-Set',
      ],
      partner: 'amazon',
      url: shops.packtaschen,
      ctaLabel: 'Packtaschen ansehen',
    },
    {
      badge: 'Die Mischform',
      title: 'Packtaschen hinten + Lenkerrolle vorn',
      forWhom: 'Der pragmatische Kompromiss für fast jede Tour.',
      price: 'ca. 220 – 400 € komplett',
      specs: [
        { k: 'Volumen', v: '50 – 60 l' },
        { k: 'Aufteilung', v: 'Schlafsystem vorn, Rest hinten' },
        { k: 'Gewicht', v: 'ca. 2,5 kg leer' },
        { k: 'Erweiterbar', v: 'Oberrohrtasche, Rahmentasche' },
      ],
      pros: [
        'Nutzt von beiden Systemen genau die Stärke',
        'Sehr viel Volumen bei ordentlichem Fahrverhalten',
        'Wächst mit: Rahmentasche und Stem Bags jederzeit ergänzbar',
      ],
      cons: ['Kein reines System – Puristen werden das kritisieren'],
      partner: 'amazon',
      url: shops.gepaecktraeger,
      ctaLabel: 'Gepäckträger ansehen',
    },
    {
      badge: 'Wenn es Gelände sein soll',
      title: 'Reines Bikepacking-Set',
      forWhom: 'Schotter, Trails, Räder ohne Ösen, schnelle Touren.',
      price: 'ca. 355 – 790 €',
      specs: [
        { k: 'Volumen', v: '26 – 45 l' },
        { k: 'Gewicht', v: '1,4 – 2,9 kg leer' },
        { k: 'Vorteil', v: 'Schmal, wendig, tragbar' },
        { k: 'Nachteil', v: 'Weniger Volumen, mehr Packdisziplin' },
      ],
      pros: [
        'Passt an praktisch jedes Rad, auch ohne Ösen',
        'Deutlich besser auf schmalen Wegen und im Gelände',
        'Rad bleibt schiebbar und tragbar',
      ],
      cons: [
        'Teurer bei weniger Volumen',
        'Zwingt zu leichterem Packen – was auch ein Vorteil sein kann',
      ],
      note: 'Wenn du überwiegend Trails oder Schotter fährst, ist das die einzige System-Entscheidung, die wirklich Sinn ergibt.',
    },
  ],
  { columns: 3 }
)}

${h2('Die Entscheidung in drei Fragen', 'entscheidung')}
${checklist([
  '<strong>Fährst du überwiegend Asphalt und breite Radwege?</strong> → Packtaschen.',
  '<strong>Hat dein Rad Gepäckträger-Ösen und du willst günstig starten?</strong> → Packtaschen.',
  '<strong>Fährst du Schotter, Trails oder hat dein Rad keine Ösen?</strong> → Bikepacking-Taschen.',
])}
<p>
  Wenn zwei der drei Antworten in dieselbe Richtung zeigen, hast du deine Entscheidung. Wenn nicht:
  Nimm die Mischform. Sie ist nicht der Kompromiss, für den viele sie halten, sondern für die
  meisten deutschen Touren das objektiv beste Setup.
</p>
`;

module.exports = article({
  href: '/taschen/taschen-oder-packtaschen.html',
  kicker: 'Taschen · Systemfrage',
  title: 'Bikepacking-Taschen oder Packtaschen?',
  metaTitle: 'Bikepacking-Taschen vs. Packtaschen: Der ehrliche Vergleich | Sattelfest',
  description:
    'Bikepacking-Taschen oder Gepäckträger mit Packtaschen? Der direkte Vergleich nach Volumen, Preis, Gewicht, Wasserdichtigkeit und Geländetauglichkeit – plus die Mischform, die für die meisten Touren gewinnt.',
  lead:
    'Packtaschen gelten in Foren als altmodisch. Für viele deutsche Touren sind sie schlicht die bessere und günstigere Lösung.',
  meta: [
    { icon: 'bag', text: '10 Minuten Lesezeit' },
    { icon: 'wallet', text: 'Mit Preisvergleich' },
    { icon: 'check', text: 'Entscheidung in 3 Fragen' },
  ],
  toc: [
    { label: 'Der direkte Vergleich', id: 'vergleich' },
    { label: 'Wann Packtaschen besser sind', id: 'packtaschen-besser' },
    { label: 'Die Mischform', id: 'mischform' },
    { label: 'Wenn dein Rad keine Ösen hat', id: 'ohne-oesen' },
    { label: 'Konkrete Empfehlungen', id: 'empfehlungen' },
    { label: 'Die Entscheidung in drei Fragen', id: 'entscheidung' },
  ],
  content,
  faq: [
    {
      q: 'Sind Packtaschen für Bikepacking altmodisch?',
      a: '<p>Nein. Sie fassen mit 60 bis 100 Litern das Doppelte bis Dreifache, kosten mit Träger 180 bis 400 Euro statt 355 bis 790, sind meist echt wasserdicht und lassen sich in Sekunden packen. Auf asphaltierten Fernradwegen – also einem großen Teil aller Touren in Deutschland – bringt ein Bikepacking-Set keinen einzigen seiner Vorteile zur Geltung.</p>',
    },
    {
      q: 'Was ist die beste Kombination für Einsteiger?',
      a: '<p>Gepäckträger mit zwei Hinterradtaschen plus eine Lenkerrolle für das Schlafsystem. Das ergibt 50 bis 60 Liter, kostet 220 bis 400 Euro, ist zuverlässig wasserdicht und deckt praktisch jede Tour in Deutschland ab. Rahmentasche und Oberrohrtasche lassen sich später jederzeit ergänzen.</p>',
    },
    {
      q: 'Kann ich einen Gepäckträger montieren, wenn mein Rad keine Ösen hat?',
      a: '<p>Ja. Sattelrohr-Klemmträger tragen 8 bis 10 Kilo und kosten 60 bis 130 Euro, Sattelstützen-Träger 5 bis 9 Kilo für 40 bis 90 Euro, achsmontierte Leichtträger sogar 9 bis 15 Kilo. Bei Carbonrahmen und Carbon-Sattelstützen unbedingt die Herstellerfreigabe prüfen – viele schließen Klemmträger ausdrücklich aus.</p>',
    },
    {
      q: 'Wie viel schwerer ist ein Packtaschen-Setup?',
      a: '<p>Rund 1,5 Kilogramm. Ein Bikepacking-Set wiegt leer 1,4 bis 2,9 Kilo, Gepäckträger plus zwei Packtaschen kommen auf 3,5 bis 5 Kilo. Das ist spürbar, aber im Verhältnis zum Gesamtsystemgewicht aus Rad, Gepäck und Fahrer weniger dramatisch, als es in Foren klingt.</p>',
    },
  ],
  related: [
    { href: '/taschen/taschensystem.html', label: 'Das Taschensystem verstehen' },
    { href: '/einstieg/welches-fahrrad.html', label: 'Welches Rad passt zum Bikepacking?' },
    { href: '/taschen/wasserdicht-packen.html', label: 'Wasserdicht packen' },
    { href: '/einstieg/was-kostet-bikepacking.html', label: 'Was Bikepacking wirklich kostet' },
  ],
});
