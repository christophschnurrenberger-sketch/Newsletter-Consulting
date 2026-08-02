'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, pickGrid, affNotice,
} = require('../../components');
const { shops } = require('../../data/shops');

const content = `
<p class="lead-p">
  Licht ist der einzige Ausrüstungsposten, bei dem Sparen unmittelbar gefährlich wird. Und Strom ist
  das Thema, das jede Tourplanung ab drei Tagen still im Hintergrund bestimmt. Beides lässt sich mit
  einer einfachen Rechnung lösen.
</p>

${stats([
  { value: '2', label: 'Rücklichter', note: 'Eins fällt aus. Immer. Meist am unpassendsten Abend.' },
  { value: '10.000 mAh', label: 'Powerbank-Standard', note: 'Reicht für 2 bis 3 Tage ohne Steckdose.' },
  { value: '15 km/h', label: 'Nabendynamo lädt ab', note: 'Darunter reicht die Leistung nicht zum Laden.' },
])}

${h2('Licht: was du wirklich brauchst', 'licht')}
${h3('Vorne', 'vorne')}
<p>
  In Deutschland gilt die StVZO: Am verkehrstauglichen Fahrrad muss vorn ein weißer Scheinwerfer
  montiert sein, der andere nicht blendet. Für die Praxis wichtiger ist die Frage, ob du damit auch
  <em>sehen</em> kannst – und da unterscheiden sich die Anforderungen deutlich.
</p>
${table({
  head: ['Einsatz', 'Empfohlene Leistung', 'Anmerkung'],
  rows: [
    ['Beleuchtete Straße, Stadt', '30 – 60 Lux', 'Reicht zum Gesehenwerden'],
    ['Unbeleuchtete Landstraße', '60 – 100 Lux', 'Der Standard für Tourenfahrten'],
    ['Waldweg, Schotter im Dunkeln', '100 Lux oder 600+ Lumen', 'Breiter Lichtkegel wichtiger als Reichweite'],
    ['Trails bei Nacht', '800 – 1.500 Lumen', 'Zusätzlich eine Helmlampe sinnvoll'],
  ],
  note: 'Lux misst die Beleuchtungsstärke in einem Punkt, Lumen den gesamten Lichtstrom. StVZO-Leuchten werden in Lux angegeben, MTB-Lampen in Lumen. Die Werte sind nicht direkt vergleichbar.',
})}

${h3('Hinten', 'hinten')}
<p>
  Das Rücklicht ist sicherheitsrelevanter als der Scheinwerfer – es entscheidet darüber, ob ein Auto
  dich rechtzeitig sieht. Und ausgerechnet Rücklichter sind die Bauteile, die am zuverlässigsten
  ausfallen: Akku leer, Halterung gebrochen, unterwegs verloren.
</p>
${checklist([
  '<strong>Immer zwei Rücklichter mitnehmen.</strong> Eines am Rad, eines als Reserve in der Tasche.',
  '<strong>Achtung Satteltasche:</strong> Sie verdeckt oft das Rücklicht an der Sattelstütze. Viele Taschen haben deshalb eine Schlaufe – nutze sie.',
  '<strong>Ein Rücklicht am Rucksack oder Helm</strong> ist zusätzlich sinnvoll: Es sitzt höher und wird früher gesehen.',
  '<strong>Standlicht</strong> ist an der Ampel und an Kreuzungen ein echter Sicherheitsgewinn.',
  '<strong>Reflektoren nicht abmontieren.</strong> Sie brauchen keinen Strom und wirken auch bei totem Akku.',
])}

${callout(
  'Die Stirnlampe ist keine Option',
  '<p>Eine Stirnlampe gehört zur Pflichtausstattung, nicht zum Komfort. Zelt aufbauen, kochen, nachts austreten, etwas in der Tasche suchen – alles das geht mit einer Hand am Handy nicht. 90 Gramm, 20 bis 50 Euro. Nimm ein Modell mit Rotlicht: Es blendet dich und andere im Zelt nicht und schont die Nachtsicht.</p>',
  'tip'
)}

${h2('Strom: die Rechnung', 'strom')}
<p>
  Die einfache Methode, deinen Bedarf zu ermitteln: Zähle zusammen, was du pro Tag lädst.
</p>
${table({
  head: ['Gerät', 'Akkukapazität', 'Ladungen pro Tour', 'Bedarf'],
  rows: [
    ['Handy (Navigation)', '4.000 – 5.000 mAh', '1 × pro Tag', '<strong>4.500 mAh/Tag</strong>'],
    ['Frontlicht', '2.000 – 3.500 mAh', 'Alle 2 – 3 Tage', 'ca. 1.000 mAh/Tag'],
    ['Rücklicht', '300 – 600 mAh', 'Alle 3 – 5 Tage', 'ca. 150 mAh/Tag'],
    ['Stirnlampe', '600 – 1.000 mAh', 'Alle 4 – 6 Tage', 'ca. 200 mAh/Tag'],
    ['GPS-Radcomputer', '1.500 – 2.500 mAh', 'Alle 1 – 2 Tage', 'ca. 1.200 mAh/Tag'],
    ['Kopfhörer', '400 – 800 mAh', 'Alle 3 – 4 Tage', 'ca. 150 mAh/Tag'],
    ['<strong>Summe typisch</strong>', '', '', '<strong>ca. 6.000 – 7.000 mAh/Tag</strong>'],
  ],
  note: 'Wichtig: Eine Powerbank liefert nie ihre Nennkapazität. Wegen Spannungswandlung und Verlusten kommen etwa 60 bis 70 Prozent tatsächlich an. Aus 10.000 mAh werden real etwa 6.000 bis 7.000.',
})}

${table({
  head: ['Powerbank', 'Reale Leistung', 'Reicht für', 'Gewicht'],
  rows: [
    ['5.000 mAh', 'ca. 3.200 mAh', '1 Tag ohne Steckdose', '110 g'],
    ['<strong>10.000 mAh</strong>', 'ca. 6.500 mAh', '<strong>2 – 3 Tage</strong>', '200 g'],
    ['20.000 mAh', 'ca. 13.000 mAh', '4 – 6 Tage', '400 g'],
    ['26.000 mAh', 'ca. 17.000 mAh', '6 – 8 Tage', '520 g'],
  ],
  note: 'Für Wochenendtouren reicht 10.000 mAh mit Reserve. Wer auf Campingplätzen übernachtet, lädt ohnehin fast jede Nacht nach.',
})}

${callout(
  'Wo du unterwegs lädst',
  '<p>Campingplätze haben fast immer Steckdosen im Sanitärbereich (nachfragen, manche berechnen eine Kleinigkeit). Bäckereien, Cafés und Bahnhöfe sind die klassischen Zwischenstopps. Die Mittagspause in einem Café mit Steckdose ist die effizienteste Ladestrategie überhaupt: 60 Minuten bringen die Powerbank auf gut die Hälfte. Auf Trekkingplätzen gibt es dagegen definitiv keinen Strom.</p>',
  'info'
)}

${h2('Der Nabendynamo', 'dynamo')}
<p>
  Ein Nabendynamo erzeugt Strom aus der Radbewegung. Mit einem USB-Lader kannst du damit unterwegs
  Handy, Powerbank und Lampen laden – theoretisch unbegrenzt.
</p>
${table({
  head: ['Aspekt', 'Wert', 'Bewertung'],
  rows: [
    ['Anschaffung Nabe plus Laufradbau', '150 – 300 €', 'Der große Posten'],
    ['USB-Lader', '60 – 130 €', 'Notwendig für das Laden'],
    ['Zusatzgewicht', '400 – 600 g', 'Am Vorderrad – rotierende Masse'],
    ['Ladeleistung', '0,5 – 1,5 W ab 15 km/h', 'Etwa halb so schnell wie eine Steckdose'],
    ['Ladet ab', 'ca. 15 km/h', 'Am Berg und im Gelände lädt er praktisch nicht'],
    ['Tretwiderstand', '3 – 6 W', 'Spürbar, aber gering – etwa 1 bis 2 Prozent'],
    ['Lohnt sich ab', 'ca. 5 – 7 Tage autark', 'Darunter ist eine Powerbank günstiger'],
  ],
  note: 'Für die meisten Bikepacker in Mitteleuropa ist ein Nabendynamo Luxus, keine Notwendigkeit. Wer aber im Norden oder in dünn besiedelten Regionen unterwegs ist, wird ihn schätzen.',
})}

${affNotice()}

${h2('Kaufempfehlungen', 'empfehlungen')}
${pickGrid(
  [
    {
      badge: 'Pflicht',
      title: 'Frontlicht ab 60 Lux mit Akku',
      forWhom: 'Jede Tour, auf der du auch nur eine Stunde im Dunkeln fahren könntest.',
      price: 'ca. 45 – 130 €',
      specs: [
        { k: 'Leistung', v: '60 – 100 Lux (StVZO)' },
        { k: 'Laufzeit', v: 'mindestens 6 Std. auf voller Stufe' },
        { k: 'Laden', v: 'USB-C, direkt am Rad' },
        { k: 'Montage', v: 'Werkzeuglos, aber sturzfest' },
      ],
      pros: [
        'Sicherheit auf unbeleuchteten Landstraßen',
        'Per USB unterwegs an der Powerbank ladbar',
        'Gute Modelle halten Regen problemlos aus',
      ],
      cons: ['Für Trails bei Nacht braucht es zusätzlich eine Lumen-Lampe'],
      partner: 'amazon',
      url: shops.frontlicht,
      ctaLabel: 'Frontlichter ansehen',
    },
    {
      badge: 'Zweimal kaufen',
      title: 'Rücklicht mit Standlicht',
      forWhom: 'Zwei Stück: eins montiert, eins als Reserve.',
      price: 'ca. 20 – 45 € je Stück',
      specs: [
        { k: 'Laufzeit', v: '8 – 20 Std. je nach Modus' },
        { k: 'Montage', v: 'Sattelstütze und Taschenschlaufe' },
        { k: 'Wichtig', v: 'Darf von der Satteltasche nicht verdeckt werden' },
        { k: 'Extra', v: 'Standlicht für Ampeln' },
      ],
      pros: [
        'Der sicherheitsrelevanteste Ausrüstungsgegenstand überhaupt',
        'Günstig genug, um zwei zu kaufen',
        'Zweites Licht am Helm oder an der Tasche erhöht die Sichtbarkeit deutlich',
      ],
      cons: ['Fällt regelmäßig aus – deshalb immer zwei'],
      partner: 'amazon',
      url: shops.ruecklicht,
      ctaLabel: 'Rücklichter ansehen',
    },
    {
      badge: 'Der Standard',
      title: 'Powerbank 10.000 mAh mit USB-C',
      forWhom: 'Wochenendtouren und Reisen mit Lademöglichkeit alle 2 bis 3 Tage.',
      price: 'ca. 25 – 55 €',
      specs: [
        { k: 'Kapazität', v: '10.000 mAh (real ca. 6.500)' },
        { k: 'Gewicht', v: '190 – 230 g' },
        { k: 'Anschluss', v: 'USB-C mit Power Delivery' },
        { k: 'Wichtig', v: 'Lädt sich selbst schnell wieder auf' },
      ],
      pros: [
        'Deckt Handy, Licht und Lampe über zwei bis drei Tage',
        'Mit Power Delivery in 2 Stunden wieder voll',
        'Bestes Verhältnis von Kapazität zu Gewicht',
      ],
      cons: [
        'Ab vier Tagen ohne Steckdose zu wenig',
        'Bei Kälte sinkt die nutzbare Kapazität',
      ],
      partner: 'amazon',
      url: shops.powerbank10,
      ctaLabel: 'Powerbanks ansehen',
      note: 'Wenn du regelmäßig länger als drei Tage ohne Steckdose bist: 20.000 mAh, 400 g. Zwei kleine Powerbanks sind flexibler als eine große, wiegen aber zusammen mehr.',
    },
  ],
  { columns: 3 }
)}

${h2('Stromspar-Regeln für unterwegs', 'sparen')}
${checklist([
  '<strong>Handy im Flugmodus,</strong> GPS bleibt aktiv – spart 20 bis 30 Prozent',
  '<strong>Display aus statt gedimmt.</strong> Das Display ist der größte Verbraucher, mit Abstand.',
  '<strong>Alles nachts laden,</strong> nicht tagsüber im Fahren – Kabel am Lenker gehen kaputt.',
  '<strong>Ein Ladegerät mit mehreren Ausgängen</strong> spart Gewicht und Steckdosenzeit.',
  '<strong>Kurze Kabel</strong> laden verlustärmer und verheddern sich weniger.',
  '<strong>Powerbank nachts im Schlafsack</strong>, wenn es unter 5 Grad ist – Kälte kostet Kapazität.',
  '<strong>Mittagspause im Café mit Steckdose</strong> – 60 Minuten bringen die Powerbank auf die Hälfte.',
])}
`;

module.exports = article({
  href: '/ausruestung/licht-strom.html',
  kicker: 'Ausrüstung · Technik',
  title: 'Licht, Strom & Powerbank',
  metaTitle: 'Bikepacking Licht & Strom: Powerbank-Größe, Lichtstärke, Nabendynamo | Sattelfest',
  description:
    'Licht und Strom beim Bikepacking: welche Lichtstärke du wo brauchst, warum zwei Rücklichter Pflicht sind, wie groß die Powerbank sein muss – mit vollständiger Verbrauchsrechnung und Nabendynamo-Bewertung.',
  lead:
    'Licht ist der Posten, bei dem Sparen gefährlich wird. Strom ist das Thema, das jede Tourplanung ab drei Tagen still bestimmt.',
  meta: [
    { icon: 'bulb', text: '8 Minuten Lesezeit' },
    { icon: 'shield', text: 'Mit Verbrauchsrechnung' },
    { icon: 'check', text: 'Powerbank-Größen' },
  ],
  toc: [
    { label: 'Licht: was du wirklich brauchst', id: 'licht' },
    { label: 'Strom: die Rechnung', id: 'strom' },
    { label: 'Der Nabendynamo', id: 'dynamo' },
    { label: 'Kaufempfehlungen', id: 'empfehlungen' },
    { label: 'Stromspar-Regeln für unterwegs', id: 'sparen' },
  ],
  content,
  faq: [
    {
      q: 'Wie groß muss die Powerbank fürs Bikepacking sein?',
      a: '<p>10.000 mAh sind der Standard und reichen für zwei bis drei Tage ohne Steckdose. Wichtig: Eine Powerbank liefert wegen Spannungswandlung nur 60 bis 70 Prozent ihrer Nennkapazität – aus 10.000 mAh werden real etwa 6.500. Der typische Tagesbedarf aus Handy, Licht und Lampe liegt bei 6.000 bis 7.000 mAh.</p>',
    },
    {
      q: 'Wie hell muss das Fahrradlicht beim Bikepacking sein?',
      a: '<p>Auf unbeleuchteten Landstraßen 60 bis 100 Lux, in der Stadt reichen 30 bis 60 Lux. Für Waldwege und Schotter im Dunkeln sind 100 Lux oder 600+ Lumen sinnvoll, wobei ein breiter Lichtkegel wichtiger ist als die Reichweite. Beachte: StVZO-Leuchten werden in Lux angegeben, MTB-Lampen in Lumen – die Werte sind nicht direkt vergleichbar.</p>',
    },
    {
      q: 'Warum brauche ich zwei Rücklichter?',
      a: '<p>Weil eines ausfällt – Akku leer, Halterung gebrochen oder unterwegs verloren. Das Rücklicht ist sicherheitsrelevanter als der Scheinwerfer, weil es darüber entscheidet, ob ein Auto dich rechtzeitig sieht. Achte außerdem darauf, dass deine Satteltasche das Licht an der Sattelstütze nicht verdeckt – viele Taschen haben dafür eine eigene Schlaufe.</p>',
    },
    {
      q: 'Lohnt sich ein Nabendynamo?',
      a: '<p>Erst ab etwa fünf bis sieben Tagen ohne verlässliche Steckdose. Nabe plus Laufradbau kosten 150 bis 300 Euro, der USB-Lader weitere 60 bis 130, dazu kommen 400 bis 600 Gramm am Vorderrad. Er lädt erst ab rund 15 km/h – am Berg und im Gelände also praktisch nicht. Für Wochenendtouren ist eine Powerbank deutlich sinnvoller.</p>',
    },
    {
      q: 'Wo kann ich unterwegs laden?',
      a: '<p>Campingplätze haben fast immer Steckdosen im Sanitärbereich, Bäckereien, Cafés und Bahnhöfe sind die klassischen Zwischenstopps. Die effizienteste Strategie ist die Mittagspause in einem Café mit Steckdose: 60 Minuten bringen eine Powerbank auf gut die Hälfte. Auf Trekkingplätzen gibt es definitiv keinen Strom.</p>',
    },
  ],
  related: [
    { href: '/ausruestung/navigation.html', label: 'Navigation: Apps, GPS & Karten' },
    { href: '/unterwegs/sicherheit-notfall.html', label: 'Sicherheit & Notfall' },
    { href: '/ausruestung/packliste.html', label: 'Die Packliste mit Gewichten' },
    { href: '/unterwegs/tagesablauf.html', label: 'Der Tagesablauf auf Tour' },
  ],
});
