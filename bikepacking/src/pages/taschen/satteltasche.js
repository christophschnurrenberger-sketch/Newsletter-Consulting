'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, pickGrid, affNotice,
} = require('../../components');
const { shops } = require('../../data/shops');

const content = `
<p class="lead-p">
  Die Satteltasche ist meistens die erste Bikepacking-Tasche – und die, über die am meisten geflucht
  wird. Sie pendelt, sie scheuert, sie schleift am Reifen, und die Ferse schlägt beim Treten dagegen.
  Fast alle diese Probleme haben dieselbe Ursache: falsche Größe oder falsch gepackt.
</p>

${stats([
  { value: '10–14 l', label: 'Der Zielbereich', note: 'Für die allermeisten Touren die richtige Größe.' },
  { value: '15 cm', label: 'freie Sattelstütze', note: 'Weniger, und die Tasche sitzt nicht sicher.' },
  { value: '5 kg', label: 'Sinnvolles Maximum', note: 'Darüber wird das Pendeln unangenehm.' },
])}

${h2('Wie die Satteltasche funktioniert', 'funktion')}
<p>
  Eine Bikepacking-Satteltasche hängt an zwei Punkten: an den Sattelstreben (den beiden Bügeln unter
  dem Sattel) und an der Sattelstütze. Sie ist damit fliegend gelagert – anders als eine
  Gepäckträgertasche hat sie keine Auflagefläche. Deshalb ist alles, was du über sie wissen musst,
  eine Frage der Hebelwirkung.
</p>
<p>
  Je weiter hinten das Gewicht in der Tasche sitzt, desto größer der Hebel und desto stärker
  schaukelt sie beim Wiegetritt. Das ist kein Materialfehler, sondern Physik. Die Gegenmittel:
  schwere Sachen nach vorn packen, die Tasche randvoll füllen und die Riemen fest zurren.
</p>

${callout(
  'Der wichtigste Satz zur Satteltasche',
  '<p><strong>Eine volle Tasche pendelt weniger als eine halbvolle.</strong> Wenn du regelmäßig nur eine Nacht unterwegs bist, ist eine 9-Liter-Tasche, die du vollpackst, deutlich besser als eine 17-Liter-Tasche, die halb leer bleibt. Volumen ist keine Reserve, sondern eine Verpflichtung.</p>',
  'tip'
)}

${h2('Die drei Bauarten', 'bauarten')}
${table({
  head: ['Bauart', 'Prinzip', 'Vorteil', 'Nachteil'],
  rows: [
    [
      'Klassische Rolltasche',
      'Ein Sack mit Rollverschluss, direkt festgeschnallt',
      'Leicht (350 – 550 g), günstig, kein Zusatzteil',
      'Pendelt am stärksten, Packen ist fummelig',
    ],
    [
      'Holster-System',
      'Halterung bleibt am Rad, Packsack wird eingespannt',
      'Sack abends komplett abnehmbar, leichter zu packen',
      'Schwerer (600 – 800 g), teurer',
    ],
    [
      'Tasche mit Stützstrebe',
      'Ein Stab oder Gestell stabilisiert nach unten',
      'Kaum Pendeln, hohe Zuladung, oft mit Schnellverschluss',
      'Am schwersten (700 – 1.100 g), am teuersten',
    ],
  ],
  note: 'Für die erste Tasche ist die klassische Rolltasche in Ordnung. Wer viel im Wiegetritt fährt oder auf Schotter unterwegs ist, wird die Stützstrebe irgendwann wollen.',
})}

${h2('Die richtige Größe finden', 'groesse')}
${table({
  head: ['Volumen', 'Reicht für', 'Passt zu'],
  rows: [
    ['5 – 8 l', 'Eine Sommernacht, Credit-Card-Tour', 'Kleine Rahmen, wenig freie Sattelstütze'],
    ['9 – 11 l', 'Ein bis zwei Nächte mit Zelt', 'Der Einstiegsbereich für die meisten'],
    ['12 – 14 l', 'Zwei bis vier Nächte, Übergangszeit', '<strong>Die häufigste sinnvolle Wahl</strong>'],
    ['15 – 17 l', 'Lange Touren, Winter, viel Kleidung', 'Große Rahmen, erfahrene Packer'],
  ],
  note: 'Bei einer Körpergröße unter 165 cm oder einem Rahmen unter 52 cm reicht die freie Sattelstütze oft nicht für Taschen über 11 Liter.',
})}

${h3('Die Maße, die du vorher prüfst', 'masse')}
${checklist([
  '<strong>Freie Sattelstütze:</strong> Miss vom Rahmen bis zur Sattelunterkante. Unter 15 cm brauchst du eine kompakte Tasche oder ein Modell mit Stützstrebe.',
  '<strong>Abstand Sattelunterkante zum Reifen:</strong> Die Tasche darf im vollen Zustand und beim Einfedern nirgends schleifen. Rechne 5 cm Sicherheitsabstand ein.',
  '<strong>Sattelstreben:</strong> Carbonstreben vertragen die Klemmung meist, aber prüfe die Herstellerfreigabe. Sattelmodelle ohne durchgehende Streben (z. B. manche Kurzsättel) brauchen einen Adapter.',
  '<strong>Fersenfreiheit:</strong> Setz dich aufs Rad und tritt rückwärts. Wenn die Ferse den Bereich hinter dem Sattel berührt, brauchst du eine schmale Tasche.',
])}

${h2('Richtig packen: die Reihenfolge', 'packen')}
<p>
  Eine Satteltasche wird von vorn nach hinten gepackt – „vorn“ heißt zum Sattel hin. Die Regel
  lautet: <strong>schwer nach vorn, leicht nach hinten.</strong>
</p>
${table({
  head: ['Position in der Tasche', 'Was da hingehört', 'Warum'],
  rows: [
    ['Ganz vorn (am Sattel)', 'Wechselkleidung, Isomatte, alles Kompakte und Schwere', 'Kürzester Hebel, kein Pendeln'],
    ['Mitte', 'Schlafsack im Packsack, Handtuch', 'Füllt den Querschnitt aus, stabilisiert'],
    ['Hinten (am Verschluss)', 'Regenjacke, Daunenjacke, alles Leichte', 'Größter Hebel – hier darf nichts Schweres hin'],
    ['Außen an den Riemen', 'Nasses, Flipflops, Müllbeutel', 'Nur, was den Verlust verkraften würde'],
  ],
  note: 'Feste Gegenstände wie eine Powerbank oder ein Multitool haben in der Satteltasche nichts verloren – die gehören in die Rahmentasche.',
})}

${callout(
  'Der Trick gegen das Pendeln',
  '<p>Zurre den Kompressionsriemen erst, wenn die Tasche vollständig gefüllt ist – und zwar so fest, dass sich die Tasche mit der Hand nicht mehr seitlich bewegen lässt. Danach schiebst du sie nach oben gegen die Sattelstreben und ziehst die vorderen Riemen nach. Eine Tasche, die du nach zehn Kilometern noch einmal nachziehst, sitzt für den Rest des Tages.</p>',
  'tip'
)}

${h2('Die häufigsten Probleme und ihre Lösung', 'probleme')}
${table({
  head: ['Problem', 'Ursache', 'Lösung'],
  rows: [
    [
      'Tasche pendelt seitlich',
      'Zu wenig gefüllt oder zu viel Gewicht hinten',
      'Vollpacken, Schweres nach vorn, Riemen straffer',
    ],
    [
      'Tasche schleift am Reifen',
      'Zu tief montiert oder überladen',
      'Höher hängen, Gewicht reduzieren, Modell mit Stützstrebe',
    ],
    [
      'Ferse schlägt an',
      'Tasche zu breit oder zu weit vorn',
      'Schmaleres Modell, Tasche weiter nach hinten schieben',
    ],
    [
      'Sattelstütze verrutscht nach unten',
      'Zusatzgewicht plus zu geringes Klemmmoment',
      'Klemmung mit Drehmomentschlüssel anziehen, Carbonmontagepaste',
    ],
    [
      'Scheuerstellen an der Sattelstütze',
      'Riemen reibt bei jeder Bewegung',
      'Schutzfolie oder alten Schlauch unterlegen',
    ],
    [
      'Nasse Klamotten trotz „wasserdicht“',
      'Rollverschluss zu wenig gerollt',
      'Mindestens dreimal einrollen, Packsäcke innen verwenden',
    ],
  ],
})}

${affNotice()}

${h2('Kaufempfehlungen nach Einsatz', 'empfehlungen')}
${pickGrid(
  [
    {
      badge: 'Für den Einstieg',
      title: 'Klassische Rolltasche, 9 – 11 Liter',
      forWhom: 'Erste Tasche, ein bis zwei Nächte, überwiegend Asphalt.',
      price: 'ca. 80 – 140 €',
      specs: [
        { k: 'Volumen', v: '9 – 11 l' },
        { k: 'Leergewicht', v: '350 – 550 g' },
        { k: 'Verschluss', v: 'Rollverschluss, mindestens 3 Rollungen' },
        { k: 'Befestigung', v: '2 Riemen Sattelstreben, 1 Riemen Stütze' },
      ],
      pros: [
        'Günstigster Einstieg mit vollem Nutzen',
        'Leicht, weil kein Gestell mitfährt',
        'Passt an nahezu jeden Sattel',
      ],
      cons: [
        'Pendelt im Wiegetritt spürbar',
        'Packen und Umpacken kostet Zeit',
      ],
      partner: 'amazon',
      url: shops.satteltascheKlein,
      ctaLabel: 'Satteltaschen ansehen',
    },
    {
      badge: 'Beste Allround-Wahl',
      title: 'Holster-System, 13 – 15 Liter',
      forWhom: 'Zwei bis fünf Nächte, gemischter Untergrund, regelmäßige Touren.',
      price: 'ca. 140 – 230 €',
      specs: [
        { k: 'Volumen', v: '13 – 15 l' },
        { k: 'Leergewicht', v: '600 – 800 g' },
        { k: 'Aufbau', v: 'Halterung am Rad, Packsack separat' },
        { k: 'Extra', v: 'Meist mit Rücklicht-Schlaufe' },
      ],
      pros: [
        'Packsack lässt sich abends komplett mit ins Zelt nehmen',
        'Deutlich einfacher zu packen als eine Rolltasche',
        'Halterung bleibt montiert – morgens in 30 Sekunden startklar',
      ],
      cons: [
        'Rund 200 g schwerer als eine Rolltasche',
        'Systemgebunden: Packsack und Halter müssen zusammenpassen',
      ],
      partner: 'amazon',
      url: shops.satteltasche,
      ctaLabel: 'Systeme ansehen',
    },
    {
      badge: 'Wenn nichts wackeln darf',
      title: 'Tasche mit Stützstrebe',
      forWhom: 'Schotter, Trails, schweres Gepäck, kleine Rahmen.',
      price: 'ca. 190 – 350 €',
      specs: [
        { k: 'Volumen', v: '10 – 18 l, oft variabel' },
        { k: 'Leergewicht', v: '700 – 1.100 g' },
        { k: 'Zuladung', v: 'oft bis 9 kg freigegeben' },
        { k: 'Montage', v: 'Klemmung an der Sattelstütze' },
      ],
      pros: [
        'Praktisch kein Pendeln, auch im Wiegetritt',
        'Höchste zulässige Zuladung im Bikepacking-Bereich',
        'Funktioniert auch bei wenig freier Sattelstütze',
      ],
      cons: [
        'Deutlich schwerer und teurer',
        'Zusätzliche Klemmung an der Sattelstütze – bei Carbon prüfen',
      ],
      partner: 'bikecomponents',
      url: 'https://www.bike-components.de/de/Taschen-Koerbe/Satteltaschen/',
      ctaLabel: 'Modelle ansehen',
      note: 'Bei sehr kleinen Rahmen ist ein leichter Gepäckträger mit Klemmung am Sattelrohr oft die bessere und günstigere Lösung.',
    },
  ],
  { columns: 3 }
)}

${h2('Wann eine Satteltasche die falsche Wahl ist', 'falsch')}
${checklist(
  [
    '<strong>Weniger als 12 cm freie Sattelstütze.</strong> Dann sitzt keine Tasche sicher. Nimm einen kompakten Gepäckträger.',
    '<strong>Gefederte Sattelstütze.</strong> Die ständige Bewegung reibt die Befestigung durch und die Tasche schlägt am Reifen an.',
    '<strong>Vollgefedertes Mountainbike.</strong> Der Federweg frisst den Abstand zum Reifen. Es gibt spezielle Kurzmodelle, aber das Volumen ist gering.',
    '<strong>Du brauchst über 20 Liter allein hinten.</strong> Dann bist du im Packtaschen-Bereich – und der ist dafür schlicht besser.',
  ],
  { tone: 'dont' }
)}
`;

module.exports = article({
  href: '/taschen/satteltasche.html',
  kicker: 'Taschen · Satteltasche',
  title: 'Die Satteltasche (Seatpack)',
  metaTitle: 'Bikepacking-Satteltasche: Größe, Bauarten, Packen & Kaufberatung | Sattelfest',
  description:
    'Alles zur Bikepacking-Satteltasche: die drei Bauarten, welche Größe passt, die Maße, die du vorher prüfen musst, richtig packen gegen das Pendeln und Lösungen für die häufigsten Probleme.',
  lead:
    'Meistens die erste Bikepacking-Tasche – und die, über die am meisten geflucht wird. Fast alle Probleme haben zwei Ursachen.',
  meta: [
    { icon: 'bag', text: '10 Minuten Lesezeit' },
    { icon: 'weight', text: 'Mit Pack-Reihenfolge' },
    { icon: 'tool', text: 'Problemlösungen' },
  ],
  toc: [
    { label: 'Wie die Satteltasche funktioniert', id: 'funktion' },
    { label: 'Die drei Bauarten', id: 'bauarten' },
    { label: 'Die richtige Größe finden', id: 'groesse' },
    { label: 'Richtig packen: die Reihenfolge', id: 'packen' },
    { label: 'Die häufigsten Probleme', id: 'probleme' },
    { label: 'Kaufempfehlungen nach Einsatz', id: 'empfehlungen' },
  ],
  content,
  faq: [
    {
      q: 'Wie groß sollte eine Bikepacking-Satteltasche sein?',
      a: '<p>10 bis 14 Liter sind für die meisten Touren richtig. 9 bis 11 Liter reichen für ein bis zwei Nächte im Sommer, 12 bis 14 Liter für zwei bis vier Nächte oder die Übergangszeit. Kaufe nicht größer „für alle Fälle“: Eine halb gefüllte Tasche pendelt deutlich stärker als eine volle.</p>',
    },
    {
      q: 'Warum pendelt meine Satteltasche?',
      a: '<p>Meist aus zwei Gründen: Sie ist nicht voll gepackt, oder das schwere Gepäck liegt zu weit hinten. Pack das Schwerste direkt an den Sattel, fülle die Tasche vollständig aus und zieh den Kompressionsriemen so fest, dass sich die Tasche von Hand nicht mehr seitlich bewegen lässt. Nach den ersten zehn Kilometern noch einmal nachziehen.</p>',
    },
    {
      q: 'Wie viel freie Sattelstütze brauche ich für eine Satteltasche?',
      a: '<p>Mindestens 15 Zentimeter zwischen Rahmen und Sattelunterkante, gemessen an der Stütze. Bei 12 bis 15 Zentimetern geht nur eine kompakte Tasche, unter 12 Zentimetern sitzt keine Bikepacking-Satteltasche sicher – dann ist ein kleiner Gepäckträger die bessere Lösung.</p>',
    },
    {
      q: 'Was darf in die Satteltasche und was nicht?',
      a: '<p>Hinein gehört alles, was du erst abends brauchst: Wechselkleidung, Schlafsack, Isomatte, Handtuch, Waschzeug. Nicht hinein gehören schwere, harte Gegenstände wie Werkzeug, Powerbank oder Gaskartusche – die gehören tief und mittig in die Rahmentasche, weil sie sonst den Hebel vergrößern und das Pendeln verstärken.</p>',
    },
    {
      q: 'Sind Bikepacking-Satteltaschen wirklich wasserdicht?',
      a: '<p>Viele sind wasserabweisend, nicht wasserdicht – das ist ein bedeutender Unterschied. Selbst bei wasserdichten Modellen dringt Wasser bei Dauerregen an Nähten und Verschluss ein. Rolle den Verschluss mindestens dreimal ein und pack Schlafsack und Wechselkleidung zusätzlich in eigene Packsäcke. Das ist die einzige zuverlässige Lösung.</p>',
    },
  ],
  related: [
    { href: '/taschen/lenkerrolle.html', label: 'Lenkerrolle & Lenkertasche' },
    { href: '/taschen/richtig-packen.html', label: 'Richtig packen: Gewichtsverteilung' },
    { href: '/taschen/wasserdicht-packen.html', label: 'Wasserdicht packen' },
    { href: '/taschen/taschensystem.html', label: 'Das Taschensystem verstehen' },
  ],
});
