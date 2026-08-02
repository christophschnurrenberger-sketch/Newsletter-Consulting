'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats,
} = require('../../components');

const content = `
<p class="lead-p">
  Wasser ist mit einem Kilo pro Liter der schwerste Einzelposten deiner gesamten Ausrüstung. Deshalb
  lautet die Regel nicht „viel mitnehmen“, sondern „wissen, wo es nachgefüllt wird“. In Mitteleuropa
  ist das erstaunlich einfach – man muss nur wissen, wo man suchen muss.
</p>

${stats([
  { value: '1 kg', label: 'Pro Liter Wasser', note: 'Der schwerste Posten im ganzen Gepäck.' },
  { value: '500–1.000', label: 'Milliliter pro Stunde', note: 'Je nach Temperatur und Anstrengung.' },
  { value: '1,5 l', label: 'Reicht meist', note: 'Zwischen zwei Versorgungspunkten in Deutschland.' },
])}

${h2('Wie viel Wasser du wirklich brauchst', 'bedarf')}
${table({
  head: ['Bedingung', 'Bedarf pro Stunde', 'Für 5 Std. Fahrt'],
  rows: [
    ['Kühl (unter 15 °C), flach', '400 – 500 ml', 'ca. 2,2 l'],
    ['Mild (15 – 22 °C)', '500 – 700 ml', 'ca. 3,0 l'],
    ['Warm (23 – 28 °C)', '700 – 900 ml', 'ca. 4,0 l'],
    ['Heiß (über 28 °C)', '900 – 1.200 ml', 'ca. 5,3 l'],
    ['Zusätzlich am Anstieg', '+20 %', ''],
    ['Abends: Kochen und Waschen', '1,0 – 1,5 l', ''],
  ],
  note: 'Wichtig: Diese Mengen musst du nicht alle transportieren. In Deutschland reichen 1,5 Liter am Rad, weil spätestens alle 20 bis 30 Kilometer eine Nachfüllmöglichkeit kommt. Nur für die Fahrt zu einem Trekkingplatz brauchst du den vollen Tagesbedarf plus Abendration.',
})}

${callout(
  'Die Ausnahme: der Trekkingplatz',
  '<p>Trekkingplätze haben in der Regel <strong>kein Trinkwasser</strong>. Rechne für Abendessen, Trinken, Zähneputzen und Frühstück mit <strong>3 bis 4 Litern</strong>, die du am letzten Ort vor dem Platz auffüllen musst. Das sind 3 bis 4 Kilo zusätzlich auf dem letzten Streckenabschnitt – plane diesen Abschnitt entsprechend kurz und flach.</p>',
  'warn'
)}

${h2('Wo du in Deutschland Wasser bekommst', 'quellen')}
${table({
  head: ['Ort', 'Verfügbarkeit', 'Anmerkung'],
  rows: [
    [
      '<strong>Friedhof</strong>',
      'Sehr hoch',
      'Praktisch jeder Ort hat einen, fast immer mit Wasserhahn. Meist Trinkwasserqualität – bei „Kein Trinkwasser“-Schild nicht verwenden',
    ],
    [
      '<strong>Sportplatz / Vereinsheim</strong>',
      'Hoch',
      'Außenhähne sind üblich, im Winter oft abgestellt',
    ],
    [
      '<strong>Campingplatz</strong>',
      'Sehr hoch',
      'Auch für Durchreisende – einfach an der Rezeption fragen',
    ],
    [
      '<strong>Gaststätte, Café, Bäckerei</strong>',
      'Hoch',
      'Nach Leitungswasser fragen, in Deutschland fast immer kostenlos',
    ],
    [
      '<strong>Supermarkt</strong>',
      'Sehr hoch',
      'Wasser kaufen ist die zuverlässigste Variante',
    ],
    [
      'Öffentliche Toiletten, Bahnhöfe',
      'Mittel',
      'Qualität schwankt, Hinweisschilder beachten',
    ],
    [
      'Dorfbrunnen',
      'Regional',
      'Nur bei ausdrücklicher Trinkwasser-Kennzeichnung',
    ],
    [
      'Wanderhütten, Schutzhütten',
      'Niedrig',
      'Selten Wasser, nicht darauf verlassen',
    ],
    [
      'Bäche und Flüsse',
      'Hoch, aber',
      '<strong>Nur mit Filter oder abgekocht.</strong> In Deutschland fast immer landwirtschaftlich belastet',
    ],
  ],
  note: 'Die Friedhofs-Regel ist der wichtigste Wasser-Tipp für Deutschland: Sie funktioniert in nahezu jedem Ort, auch sonntags und nachts.',
})}

${h2('Brauchst du einen Wasserfilter?', 'filter')}
${checklist([
  '<strong>In Deutschland und Mitteleuropa: nein.</strong> Die Versorgungsdichte ist so hoch, dass ein Filter totes Gewicht ist',
  '<strong>In Skandinavien: sinnvoll.</strong> Oberflächenwasser ist dort meist unbedenklich, ein Filter gibt Sicherheit',
  '<strong>In Südeuropa im Sommer: sinnvoll.</strong> Lange Abschnitte ohne Ortschaften, hohe Temperaturen',
  '<strong>Auf abgelegenen Offroad-Routen: ja.</strong> Wenn zwischen zwei Orten mehr als 50 Kilometer liegen',
  '<strong>Alternative:</strong> Entkeimungstabletten wiegen 20 Gramm statt 120 und kosten wenige Euro – wirken aber nicht gegen chemische Belastung',
])}
<p>
  Wichtig zu verstehen: Ein Filter entfernt Bakterien und Protozoen, aber keine Nitrate, Pestizide
  oder Schwermetalle. In deutschen Agrarlandschaften ist genau das das Problem – Bachwasser ist
  dort auch gefiltert nicht unbedenklich.
</p>

${h2('Verpflegung planen', 'verpflegung')}
${h3('Die Einkaufs-Realität in Deutschland', 'einkaufen')}
${table({
  head: ['Situation', 'Was du wissen musst'],
  rows: [
    ['Sonntag', '<strong>Fast alles geschlossen.</strong> Ausnahmen: Bahnhofs- und Tankstellenshops, Bäckereien vormittags'],
    ['Nach 20 Uhr', 'Supermärkte zu, Tankstellen offen (teuer, aber ausreichend)'],
    ['Kleine Orte', 'Oft kein Supermarkt mehr – Dorfläden haben mittags zu'],
    ['Feiertage', 'Wie Sonntag. Regional unterschiedlich, vorher prüfen'],
    ['Mittagspause auf dem Land', 'Viele Metzger und Bäcker schließen von 12:30 bis 14:30'],
    ['Bäckereien', 'Sonntagvormittag oft geöffnet – die Rettung am Wochenende'],
  ],
  note: 'Die praktische Konsequenz: Kaufe samstags ein, wenn du sonntags fährst. Und plane Abendessen und Frühstück gemeinsam ein, wenn du auf einem Trekkingplatz übernachtest.',
})}

${h3('Was du dabeihast', 'vorrat')}
${checklist([
  '<strong>Tagesverpflegung:</strong> Riegel, Nüsse, Trockenobst, Salzgebäck für 4 bis 6 Stunden',
  '<strong>Ein Notriegel</strong>, der nie angebrochen wird, außer es ist wirklich nötig',
  '<strong>Abendessen,</strong> wenn du auf einem Trekkingplatz übernachtest',
  '<strong>Frühstück nur,</strong> wenn morgens kein Bäcker auf der Route liegt',
  '<strong>Etwas Salziges</strong> – bei Hitze wichtiger als alles Süße',
  '<strong>Elektrolytpulver</strong> bei Touren über 25 Grad, wiegt fast nichts',
])}

${callout(
  'Der Einkaufsfehler, den fast alle machen',
  '<p>Zu viel kaufen. Nach dem ersten langen Tag hat man das Gefühl, für drei Tage einkaufen zu müssen – und schleppt dann Konserven durch die Landschaft. In Deutschland kommt spätestens alle 30 bis 50 Kilometer ein Supermarkt. Kaufe für <strong>eine Mahlzeit plus einen Tag Snacks</strong>, mehr nicht. Die Ausnahme sind Sonntag und Feiertag.</p>',
  'money'
)}

${h2('Der Energiebedarf', 'energie')}
${table({
  head: ['Tour', 'Zusatzverbrauch', 'Entspricht'],
  rows: [
    ['50 km flach, gemächlich', '1.200 – 1.800 kcal', 'Ein zusätzliches Mittagessen'],
    ['70 km, 600 hm', '2.500 – 3.500 kcal', 'Eine zusätzliche Tagesration'],
    ['100 km, 1.000 hm', '3.500 – 5.000 kcal', 'Fast eine doppelte Tagesration'],
    ['120 km, 1.500 hm', '4.500 – 6.500 kcal', 'Kaum durch Essen auszugleichen'],
  ],
  note: 'Zusätzlich zum Grundumsatz von rund 1.600 bis 2.200 kcal. Die Werte schwanken stark nach Körpergewicht, Systemgewicht und Tempo – sie dienen der Größenordnung.',
})}

${checklist([
  '<strong>Alle 60 bis 90 Minuten essen,</strong> auch ohne Hungergefühl. Wer wartet, bis der Hunger kommt, hat schon verloren.',
  '<strong>Der Hunger kommt verzögert.</strong> Viele merken den Bedarf erst am zweiten oder dritten Tag – dann aber massiv.',
  '<strong>Kohlenhydrate während der Fahrt,</strong> Eiweiß und Fett am Abend.',
  '<strong>Frühstücke richtig.</strong> Ein Kaffee und ein Riegel tragen keine 70 Kilometer.',
  '<strong>Bei mehrtägigen Touren gilt: lieber zu viel als zu wenig essen.</strong> Ein Kaloriendefizit über drei Tage zeigt sich als plötzliche Erschöpfung.',
])}

${h2('Was in die Stem Bags gehört', 'stembags')}
<p>
  Die kleinen Taschen neben dem Vorbau sind der Grund, warum manche Menschen unterwegs regelmäßig
  essen und andere nicht. Was du im Fahren erreichst, isst du. Was du für einen Riegel anhalten
  musst, isst du zu selten.
</p>
${checklist([
  'Links die Wasserflasche, rechts die Snacks – oder umgekehrt, aber immer gleich',
  'Zwei bis drei Riegel griffbereit, nicht mehr – der Rest bleibt in der Rahmentasche',
  'Etwas Salziges neben etwas Süßem, damit du eine Wahl hast',
  'Nach jeder Pause nachfüllen, damit nie Leere herrscht',
])}
`;

module.exports = article({
  href: '/routen/wasser-verpflegung.html',
  kicker: 'Routen · Versorgung',
  title: 'Wasser & Verpflegung unterwegs',
  metaTitle: 'Bikepacking: Wasser und Verpflegung unterwegs planen | Sattelfest',
  description:
    'Wasser und Verpflegung beim Bikepacking: wie viel du wirklich brauchst, wo du in Deutschland zuverlässig Wasser bekommst (Friedhofs-Regel), ob ein Filter nötig ist und der reale Energiebedarf pro Tag.',
  lead:
    'Wasser ist der schwerste Posten im Gepäck. Die Regel lautet nicht „viel mitnehmen“, sondern „wissen, wo es nachgefüllt wird“.',
  meta: [
    { icon: 'drop', text: '8 Minuten Lesezeit' },
    { icon: 'map', text: 'Mit Wasserquellen-Liste' },
    { icon: 'check', text: 'Energiebedarf-Tabelle' },
  ],
  toc: [
    { label: 'Wie viel Wasser du brauchst', id: 'bedarf' },
    { label: 'Wo du in Deutschland Wasser bekommst', id: 'quellen' },
    { label: 'Brauchst du einen Wasserfilter?', id: 'filter' },
    { label: 'Verpflegung planen', id: 'verpflegung' },
    { label: 'Der Energiebedarf', id: 'energie' },
    { label: 'Was in die Stem Bags gehört', id: 'stembags' },
  ],
  content,
  faq: [
    {
      q: 'Wie viel Wasser muss ich beim Bikepacking mitnehmen?',
      a: '<p>In Deutschland reichen 1,5 Liter am Rad, weil spätestens alle 20 bis 30 Kilometer eine Nachfüllmöglichkeit kommt. Der Stundenbedarf liegt je nach Temperatur bei 400 bis 1.200 Millilitern. Die große Ausnahme ist die Fahrt zu einem Trekkingplatz: Dort gibt es kein Wasser, also brauchst du 3 bis 4 Liter für Abendessen, Trinken und Frühstück.</p>',
    },
    {
      q: 'Wo bekomme ich unterwegs Trinkwasser?',
      a: '<p>Friedhöfe sind die zuverlässigste Quelle – praktisch jeder Ort hat einen, fast immer mit Wasserhahn, und sie sind auch sonntags zugänglich. Ebenfalls gut: Sportplätze, Campingplätze, Gaststätten und Bäckereien (nach Leitungswasser fragen, in Deutschland fast immer kostenlos) sowie Supermärkte. Beachte Hinweisschilder wie „Kein Trinkwasser“.</p>',
    },
    {
      q: 'Brauche ich beim Bikepacking einen Wasserfilter?',
      a: '<p>In Deutschland und Mitteleuropa nicht – die Versorgungsdichte ist so hoch, dass ein Filter totes Gewicht wäre. Sinnvoll wird er in Skandinavien, in Südeuropa im Sommer und auf abgelegenen Offroad-Routen mit über 50 Kilometern zwischen Ortschaften. Beachte: Ein Filter entfernt Bakterien, aber keine Nitrate oder Pestizide – deutsches Bachwasser ist auch gefiltert oft belastet.</p>',
    },
    {
      q: 'Wie viel esse ich beim Bikepacking pro Tag?',
      a: '<p>Eine Tour mit 70 Kilometern und 600 Höhenmetern verbraucht 2.500 bis 3.500 Kilokalorien zusätzlich zum Grundumsatz. Iss alle 60 bis 90 Minuten etwas, auch ohne Hungergefühl. Der Hunger kommt oft verzögert – viele merken den Bedarf erst am zweiten oder dritten Tag, dann aber massiv.</p>',
    },
    {
      q: 'Was muss ich beim Einkaufen in Deutschland beachten?',
      a: '<p>Sonntags ist fast alles geschlossen; Ausnahmen sind Bahnhofs- und Tankstellenshops sowie Bäckereien am Vormittag. Nach 20 Uhr helfen nur noch Tankstellen. In kleinen Orten gibt es oft keinen Supermarkt mehr, und Dorfläden schließen mittags. Kaufe samstags ein, wenn du sonntags fährst – und kaufe nicht zu viel: Alle 30 bis 50 Kilometer kommt der nächste Laden.</p>',
    },
  ],
  related: [
    { href: '/ausruestung/kochen-unterwegs.html', label: 'Kochen unterwegs' },
    { href: '/taschen/kleine-taschen.html', label: 'Oberrohr-, Gabel- & Stemtaschen' },
    { href: '/routen/uebernachten.html', label: 'Übernachten: Wo du legal schläfst' },
    { href: '/unterwegs/tagesablauf.html', label: 'Der Tagesablauf auf Tour' },
  ],
});
