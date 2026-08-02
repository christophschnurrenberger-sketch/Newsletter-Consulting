'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, affNotice, pickGrid,
} = require('../../components');
const { shops } = require('../../data/shops');

const content = `
<p class="lead-p">
  Die wahrscheinlichste richtige Antwort auf diese Frage lautet: das Rad, das du bereits besitzt.
  Bikepacking ist genau deshalb entstanden, weil das Taschensystem ohne Gepäckträger-Ösen auskommt –
  es passt an fast jedes Rad. Trotzdem gibt es vier Eigenschaften, die eine Tour angenehm oder
  anstrengend machen. Um die geht es hier.
</p>

${stats([
  { value: '4', label: 'Kriterien zählen', note: 'Reifenfreiheit, Übersetzung, Bremsen, Sitzposition.' },
  { value: '40 mm', label: 'Reifenbreite Minimum', note: 'Für gemischte Wege. Auf reinem Asphalt reichen 32 mm.' },
  { value: '0', label: 'Räder nötig', note: 'Kaufe erst nach der zweiten Tour – wenn überhaupt.' },
])}

${h2('Die vier Kriterien, die wirklich zählen', 'kriterien')}
${h3('1. Reifenfreiheit', 'reifenfreiheit')}
<p>
  Der Abstand zwischen Reifen und Rahmen bzw. Gabel entscheidet, wie breit du fahren kannst. Breiter
  heißt: mehr Komfort, mehr Grip, weniger Plattfüße, weniger Durchschläge. Mit Gepäck ist das kein
  Luxus, sondern der wichtigste Einzelfaktor für einen erträglichen dritten Tag.
</p>
${table({
  head: ['Reifenbreite', 'Passt für', 'Typisch bei'],
  rows: [
    ['28 – 32 mm', 'Asphalt und feste Radwege', 'Rennrad, sportliches Trekkingrad'],
    ['35 – 40 mm', 'Asphalt plus Waldautobahn', 'Gravelbike, Trekkingrad, Crossrad'],
    ['42 – 50 mm', 'Gemischt bis grober Schotter', 'Gravelbike modern, Tourenrad'],
    ['2,1 – 2,4 Zoll', 'Trails, Wurzeln, Alpen-Schotter', 'Hardtail-MTB'],
  ],
  note: 'Faustregel: mindestens 40 mm für gemischte Touren. Wer überwiegend auf Asphalt bleibt, ist mit 32 bis 35 mm gut bedient.',
})}

${h3('2. Übersetzung', 'uebersetzung')}
<p>
  Mit acht bis zwölf Kilo Zusatzgewicht am Rad brauchst du einen leichteren Gang, als du glaubst.
  Der übliche Anfängerfehler ist nicht mangelnde Kraft, sondern eine Übersetzung, die im Alltag
  reicht und am dritten Anstieg des Tages nicht mehr.
</p>
<p>
  Die Kennzahl heißt <strong>kleinster Gang</strong>: das kleinste Kettenblatt vorn geteilt durch das
  größte Ritzel hinten. Ein Wert von 1,0 oder darunter (also zum Beispiel 34 Zähne vorn zu 34 hinten)
  bedeutet, dass du steile Rampen auch beladen noch sitzend fahren kannst.
</p>
${table({
  head: ['Kleinster Gang', 'Beispiel', 'Reicht für'],
  rows: [
    ['1,5 und höher', '34 vorn / 23 hinten', 'Flachland, kurze Wellen'],
    ['1,2 – 1,4', '34 / 28', 'Hügelland ohne lange Rampen'],
    ['0,9 – 1,1', '34 / 34 oder 30 / 30', '<strong>Der Zielbereich für beladene Touren</strong>'],
    ['unter 0,8', '30 / 40 oder 1×-Antrieb mit 51er Ritzel', 'Alpen, Schotteranstiege, schweres Gepäck'],
  ],
  note: 'Ein größeres Ritzelpaket kostet je nach Antrieb 60 bis 200 Euro und ist die sinnvollste Einzelinvestition an einem vorhandenen Rad.',
})}

${h3('3. Bremsen', 'bremsen')}
<p>
  Beladen bergab ist keine Kleinigkeit: Zehn Kilo mehr Systemgewicht bedeuten spürbar längeren
  Bremsweg und deutlich mehr Wärme in der Bremse. Scheibenbremsen sind hier klar im Vorteil,
  besonders im Regen. Gut eingestellte Felgenbremsen funktionieren aber ebenfalls – nur brauchen
  sie mehr Handkraft und verlieren bei Nässe deutlich.
</p>
${checklist([
  'Scheibenbremse hydraulisch: die beste Lösung, wartungsarm, dosierbar',
  'Scheibenbremse mechanisch: gut, unterwegs leichter zu reparieren',
  'Felgenbremse: funktioniert – Beläge vorher erneuern und im Nassen früher bremsen',
  'V-Brake mit abgefahrener Felgenflanke: <strong>vor der Tour zum Fachhändler</strong>',
])}

${h3('4. Sitzposition', 'sitzposition')}
<p>
  Auf einer Tagestour verzeiht der Körper eine mittelmäßige Sitzposition. Auf drei Tagen mit je sechs
  Stunden nicht. Wichtiger als jede Rahmengeometrie ist deshalb: Fährst du dieses Rad bereits
  mehrere Stunden am Stück beschwerdefrei? Wenn ja, ist es dein Bikepacking-Rad. Wenn nein, löse
  <em>dieses</em> Problem, bevor du über ein neues Rad nachdenkst.
</p>

${callout(
  'Die einzige ehrliche Kaufberatung',
  '<p>Fahre zwei Touren mit dem Rad, das du hast. Notiere unterwegs, was dich stört – nicht was fehlt, sondern was dich <em>stört</em>. Nach zwei Touren hast du eine Liste mit zwei bis vier Punkten. Sehr oft stehen darauf „Übersetzung zu schwer“ und „Reifen zu schmal“ – und beides lässt sich für 150 bis 350 Euro am vorhandenen Rad lösen. Ein neues Rad kostet das Zehnfache.</p>',
  'money'
)}

${h2('Die Radtypen im Vergleich', 'radtypen')}
${table({
  head: ['Radtyp', 'Stärke', 'Schwäche', 'Geeignet für'],
  rows: [
    [
      'Gravelbike',
      'Vielseitig, effizient, viele Montagepunkte',
      'Kleines Rahmendreieck, begrenzte Reifenfreiheit',
      'Der Allrounder – 80 Prozent aller Touren',
    ],
    [
      'Trekkingrad',
      'Bequem, Gepäckträger ab Werk, Licht und Schutzbleche',
      'Schwer, oft schwache Übersetzung',
      'Asphaltrouten, Flussradwege, Komfort',
    ],
    [
      'Hardtail-MTB',
      'Grip, Federgabel, leichte Übersetzung',
      'Langsam auf Asphalt, Federgabel blockiert Gabeltaschen',
      'Schotter, Alpen, Trails',
    ],
    [
      'Rennrad',
      'Schnell, leicht',
      'Kaum Reifenfreiheit, harte Übersetzung, keine Ösen',
      'Credit-Card-Touren auf Asphalt',
    ],
    [
      'Tourenrad / Randonneur',
      'Gebaut für Gepäck, sehr stabil',
      'Träge, oft schwer zu bekommen',
      'Lange Strecken, viel Gepäck',
    ],
    [
      'E-Bike',
      'Höhenmeter werden nebensächlich',
      'Reichweite, Ladeplanung, hohes Gewicht',
      'Touren entlang bewohnter Strecken',
    ],
  ],
})}

${callout(
  'E-Bike: nicht ausgeschlossen, aber anders geplant',
  '<p>Mit dem E-Bike ist Bikepacking machbar, aber die Routenplanung dreht sich um Steckdosen statt um Höhenmeter. Rechne mit 60 bis 100 Kilometern echter Reichweite bei beladener Fahrt und plane jede Nacht mit Lademöglichkeit – Trekkingplätze im Wald haben keine. Ein zweiter Akku wiegt 2,5 bis 3,5 Kilo und kostet 500 bis 900 Euro, ist aber oft die einzige Lösung für abgelegene Strecken.</p>',
  'info'
)}

${h2('Was ein vorhandenes Rad tourentauglich macht', 'umruesten')}
<p>
  Die folgenden Maßnahmen bringen an einem normalen Rad mehr als jeder Neukauf – in dieser
  Reihenfolge:
</p>
${table({
  head: ['Maßnahme', 'Kosten', 'Wirkung'],
  rows: [
    ['Breitere Reifen, so breit wie der Rahmen zulässt', '60 – 120 €', '<strong>Sehr hoch</strong> – Komfort und Pannensicherheit'],
    ['Größeres Ritzelpaket oder kleineres Kettenblatt', '60 – 200 €', '<strong>Sehr hoch</strong> an jedem Anstieg'],
    ['Neue Bremsbeläge und Züge', '25 – 60 €', 'Hoch – vor allem beladen bergab'],
    ['Sattel, der zu dir passt', '50 – 130 €', 'Hoch – der häufigste Tourabbruchgrund'],
    ['Lenkerband dicker oder Ergo-Griffe', '20 – 50 €', 'Mittel – gegen taube Hände'],
    ['Tubeless-Umbau', '60 – 120 €', 'Mittel – weniger Plattfüße, mehr Aufwand'],
    ['Nabendynamo mit USB-Lader', '200 – 400 €', 'Mittel – erst ab längeren Touren sinnvoll'],
  ],
  note: 'Die ersten beiden Zeilen lösen zusammen etwa 70 Prozent aller Beschwerden über ein „ungeeignetes“ Rad.',
})}

${affNotice()}

${h2('Wenn du doch neu kaufst: Worauf achten', 'neukauf')}
${pickGrid(
  [
    {
      badge: 'Der Allrounder',
      title: 'Gravelbike mit viel Reifenfreiheit',
      forWhom: 'Wer eine Sache für Alltag, Tagestour und Bikepacking sucht.',
      price: 'ca. 1.200 – 2.500 €',
      specs: [
        { k: 'Reifenfreiheit', v: 'mindestens 45 mm, besser 50 mm' },
        { k: 'Übersetzung', v: 'kleinster Gang unter 1,0' },
        { k: 'Montagepunkte', v: 'Gabel mit 3-Loch-Aufnahme, Ösen hinten' },
        { k: 'Bremsen', v: 'hydraulische Scheibe' },
      ],
      pros: [
        'Deckt Asphalt und Schotter gleichermaßen gut ab',
        'Rahmendreieck groß genug für eine echte Rahmentasche',
        'Gabelösen erlauben später Gabeltaschen ohne Adapter',
      ],
      cons: ['In sehr grobem Gelände dem Hardtail unterlegen'],
      partner: 'rosebikes',
      url: 'https://www.rosebikes.de/fahrraeder/gravel-bikes',
      ctaLabel: 'Gravelbikes ansehen',
    },
    {
      badge: 'Für grobes Gelände',
      title: 'Hardtail mit starrer Gabel oder Lockout',
      forWhom: 'Wer in die Alpen will oder überwiegend Schotter fährt.',
      price: 'ca. 900 – 2.000 €',
      specs: [
        { k: 'Reifen', v: '2,1 – 2,4 Zoll' },
        { k: 'Übersetzung', v: '1×12 mit 50er oder 51er Ritzel' },
        { k: 'Gabel', v: 'Starr oder Federgabel mit Lockout' },
        { k: 'Rahmendreieck', v: 'klein – Rahmentasche fällt kompakt aus' },
      ],
      pros: [
        'Fährt Wege, auf denen ein Gravelbike aufgibt',
        'Sehr leichte Übersetzung serienmäßig',
        'Robuste Laufräder vertragen Gepäck problemlos',
      ],
      cons: [
        'Auf Asphalt spürbar langsamer',
        'Federgabel schließt Gabeltaschen praktisch aus',
      ],
      partner: 'bikecomponents',
      url: 'https://www.bike-components.de/de/Fahrraeder/',
      ctaLabel: 'Räder ansehen',
    },
    {
      badge: 'Unterschätzt',
      title: 'Gebrauchtes Trekking- oder Tourenrad',
      forWhom: 'Alle, die günstig anfangen und viel Gepäck fahren wollen.',
      price: 'ca. 250 – 700 €',
      specs: [
        { k: 'Gepäckträger', v: 'meist ab Werk montiert' },
        { k: 'Reifen', v: '37 – 47 mm typisch' },
        { k: 'Ausstattung', v: 'Licht, Schutzbleche, Ständer inklusive' },
        { k: 'Prüfen', v: 'Felgenflanken, Kette, Naben' },
      ],
      pros: [
        'Mit Abstand das beste Verhältnis von Preis zu Tourentauglichkeit',
        'Packtaschen sind günstiger und wasserdichter als Bikepacking-Sets',
        'Wenn das Rad kaputt geht, tut es finanziell nicht weh',
      ],
      cons: [
        'Oft zu schwere Übersetzung – Ritzelpaket einplanen',
        'Schwer: 15 bis 18 Kilo sind normal',
      ],
      note: 'Für die ersten drei Touren ist ein 400-Euro-Trekkingrad plus 150 Euro Zubehör die vernünftigste Lösung, die es gibt.',
    },
  ],
  { columns: 3 }
)}

${h2('Montagepunkte: das unterschätzte Detail', 'montagepunkte')}
<p>
  Wenn du wirklich neu kaufst, schau dir die Gewinde am Rahmen an. Sie kosten nichts, lassen sich
  aber nicht nachrüsten:
</p>
${checklist([
  '<strong>Drei Löcher an jedem Gabelholm</strong> für Gabeltaschen oder Flaschenhalter (Anything Cage)',
  '<strong>Zwei bis drei Flaschenhalter</strong> im Rahmendreieck – auch unter dem Unterrohr',
  '<strong>Ösen an der Sitzstrebe und am Ausfallende</strong> für einen späteren Gepäckträger',
  '<strong>Gewinde auf dem Oberrohr</strong> für eine schraubbare Oberrohrtasche',
  'Innenverlegte Züge sind hübsch, machen aber die Rahmentasche fummeliger',
])}
`;

module.exports = article({
  href: '/einstieg/welches-fahrrad.html',
  kicker: 'Einstieg · Rad',
  title: 'Welches Rad passt zum Bikepacking?',
  metaTitle: 'Bikepacking-Rad: Welches Fahrrad brauche ich wirklich? | Sattelfest',
  description:
    'Gravel, MTB, Trekking oder das Rad im Keller: die vier Kriterien, die beim Bikepacking-Rad zählen – Reifenfreiheit, Übersetzung, Bremsen, Sitzposition – plus Umbau-Tipps für vorhandene Räder.',
  lead:
    'Die wahrscheinlichste richtige Antwort ist: dein aktuelles Rad. Vier Kriterien entscheiden, ob eine Tour angenehm oder anstrengend wird.',
  meta: [
    { icon: 'bike', text: '11 Minuten Lesezeit' },
    { icon: 'trend', text: 'Mit Übersetzungs-Tabelle' },
    { icon: 'wallet', text: 'Umbau statt Neukauf' },
  ],
  toc: [
    { label: 'Die vier Kriterien, die wirklich zählen', id: 'kriterien' },
    { label: 'Die Radtypen im Vergleich', id: 'radtypen' },
    { label: 'Was ein vorhandenes Rad tourentauglich macht', id: 'umruesten' },
    { label: 'Wenn du doch neu kaufst', id: 'neukauf' },
    { label: 'Montagepunkte: das unterschätzte Detail', id: 'montagepunkte' },
  ],
  content,
  faq: [
    {
      q: 'Brauche ich ein Gravelbike für Bikepacking?',
      a: '<p>Nein. Ein Gravelbike ist der bequemste Kompromiss, aber kein Muss. Entscheidend sind Reifenfreiheit (mindestens 40 mm für gemischte Wege), eine leichte Übersetzung, funktionierende Bremsen und eine Sitzposition, in der du mehrere Stunden beschwerdefrei fährst. Ein Trekkingrad oder Hardtail erfüllt das oft genauso.</p>',
    },
    {
      q: 'Kann ich mit dem Mountainbike Bikepacking machen?',
      a: '<p>Ja, und in grobem Gelände ist es die bessere Wahl. Zwei Einschränkungen: Auf Asphalt bist du spürbar langsamer, und eine Federgabel schließt Gabeltaschen praktisch aus, weil die Holme keine Gewinde haben und die Federung durch das Gewicht leidet. Dafür bringt ein Hardtail serienmäßig die leichteste Übersetzung mit.</p>',
    },
    {
      q: 'Welche Reifenbreite brauche ich zum Bikepacking?',
      a: '<p>Auf reinem Asphalt reichen 32 bis 35 mm. Für gemischte Touren mit Waldwegen sind 40 bis 45 mm der Zielbereich, für groben Schotter und Trails 50 mm bis 2,4 Zoll. Breiter heißt mit Gepäck fast immer besser: mehr Komfort, mehr Grip, weniger Durchschläge. Fahre so breit, wie dein Rahmen zulässt.</p>',
    },
    {
      q: 'Welche Übersetzung brauche ich mit Gepäck?',
      a: '<p>Ziel ist ein kleinster Gang von 1,0 oder darunter – also zum Beispiel 34 Zähne vorn zu 34 hinten. Damit fährst du steile Rampen auch beladen noch sitzend. Für die Alpen oder schweres Gepäck sind Werte unter 0,8 sinnvoll. Ein größeres Ritzelpaket kostet je nach Antrieb 60 bis 200 Euro und ist die wirksamste Einzelmaßnahme an einem vorhandenen Rad.</p>',
    },
    {
      q: 'Geht Bikepacking auch mit dem E-Bike?',
      a: '<p>Ja, aber die Planung dreht sich um Steckdosen statt um Höhenmeter. Rechne mit 60 bis 100 Kilometern realer Reichweite bei beladener Fahrt, plane jede Nacht mit Lademöglichkeit und bedenke, dass Trekkingplätze im Wald keinen Strom haben. Ein Zweitakku wiegt 2,5 bis 3,5 Kilo und kostet 500 bis 900 Euro.</p>',
    },
    {
      q: 'Halten normale Laufräder das Gepäckgewicht aus?',
      a: '<p>In der Regel ja. Bikepacking-Gepäck von 8 bis 12 Kilo liegt deutlich unter dem, wofür Laufräder ausgelegt sind – ein zweiter Mensch auf dem Gepäckträger wäre das Vielfache. Kritisch wird es nur bei sehr leichten Rennrad-Laufrädern mit wenigen Speichen. Lass die Speichenspannung vor der ersten Tour einmal prüfen, das kostet in der Werkstatt kaum etwas.</p>',
    },
  ],
  related: [
    { href: '/taschen/taschensystem.html', label: 'Das Taschensystem verstehen' },
    { href: '/taschen/taschen-oder-packtaschen.html', label: 'Bikepacking-Taschen oder Packtaschen?' },
    { href: '/unterwegs/koerper-beschwerden.html', label: 'Sitzbeschwerden, Hände & Knie' },
    { href: '/einstieg/was-kostet-bikepacking.html', label: 'Was Bikepacking wirklich kostet' },
  ],
});
