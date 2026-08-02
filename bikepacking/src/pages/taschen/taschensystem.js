'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, steps, icon,
} = require('../../components');

const content = `
<p class="lead-p">
  Ein Bikepacking-Setup besteht aus fünf möglichen Positionen am Rad. Jede hat eine klare Aufgabe,
  eine Gewichtsgrenze und eine Sorte Gepäck, die dort hingehört. Wer das System einmal verstanden
  hat, kauft nie wieder eine Tasche, die er nach der ersten Tour wieder verkauft.
</p>

${stats([
  { value: '5', label: 'Positionen am Rad', note: 'Sattel, Lenker, Rahmen, Oberrohr, Gabel.' },
  { value: '2', label: 'reichen zum Start', note: 'Satteltasche und Lenkerrolle decken eine Nacht ab.' },
  { value: '4 kg', label: 'Grenze am Lenker', note: 'Darüber wird die Lenkung unangenehm schwammig.' },
])}

${h2('Die fünf Positionen im Überblick', 'positionen')}
${table({
  head: ['Position', 'Volumen', 'Gewichtsgrenze', 'Was da hingehört'],
  rows: [
    [
      '<strong>Satteltasche</strong>',
      '5 – 17 l',
      '4 – 6 kg',
      'Kleidung, Schlafsack, alles, was du erst abends brauchst',
    ],
    [
      '<strong>Lenkerrolle</strong>',
      '8 – 15 l',
      '3 – 4 kg',
      'Voluminös und leicht: Zelt-Innenzelt, Schlafsack, Isolationsjacke',
    ],
    [
      '<strong>Rahmentasche</strong>',
      '2 – 8 l',
      'kein echtes Limit',
      'Das Schwerste: Werkzeug, Wasser, Kocher, Riegel',
    ],
    [
      '<strong>Oberrohrtasche</strong>',
      '0,5 – 1,5 l',
      '0,5 kg',
      'Handy, Riegel, Geldbeutel – alles im Fahren erreichbar',
    ],
    [
      '<strong>Gabeltaschen</strong>',
      '2 × 3 – 5 l',
      '2 kg je Seite',
      'Zeltstangen, Wasser, Schuhe, Nasses',
    ],
  ],
  note: 'Die Volumenangaben sind marktüblich. Das Gesamtvolumen eines vollständigen Sets liegt damit bei 25 bis 45 Litern.',
})}

${h2('Die vier Aufgaben', 'aufgaben')}
<p>
  Statt nach Tasche zu denken, denk nach Aufgabe. Jedes Gepäckstück gehört in genau eine dieser vier
  Kategorien – und daraus folgt automatisch, wo es hinkommt.
</p>

${h3('Aufgabe 1: Schwer und kompakt', 'schwer')}
<p>
  Werkzeug, Ersatzschläuche, Kocher, Gaskartusche, Wasser, Riegel. Das gehört so <strong>tief und
  mittig</strong> wie möglich – also in die Rahmentasche. Der Grund ist der Schwerpunkt: Gewicht
  hoch oben oder weit außen macht das Rad kippelig, Gewicht tief in der Mitte spürst du kaum.
</p>

${h3('Aufgabe 2: Leicht und voluminös', 'voluminoes')}
<p>
  Schlafsack, Daunenjacke, Innenzelt. Das darf nach oben und nach vorn – in die Lenkerrolle. Ein
  Schlafsack wiegt 600 bis 1.100 Gramm, braucht aber acht Liter Platz. Genau dafür ist die Rolle da.
</p>

${h3('Aufgabe 3: Brauchst du erst abends', 'abends')}
<p>
  Wechselkleidung, Handtuch, Waschzeug, Isomatte. Ab in die Satteltasche. Die ist unterwegs
  umständlich zu öffnen – was kein Nachteil ist, solange nur Dinge drin sind, an die du tagsüber
  ohnehin nicht willst.
</p>

${h3('Aufgabe 4: Brauchst du im Fahren', 'im-fahren')}
<p>
  Handy, Riegel, Sonnencreme, Geldbeutel, Wasserflasche. Oberrohrtasche und Stem Bags. Diese
  Kategorie wird von Einsteigern regelmäßig unterschätzt – und ist der Grund, warum viele nach der
  ersten Tour genau diese kleinen Taschen nachkaufen.
</p>

${callout(
  'Die Faustregel für die Verteilung',
  '<p>Etwa <strong>50 Prozent des Gewichts</strong> gehören hinten (Satteltasche), <strong>30 Prozent</strong> in die Mitte (Rahmen), <strong>20 Prozent</strong> nach vorn (Lenker und Gabel). Wenn du merkst, dass das Vorderrad beim Wiegetritt eigenartig träge einlenkt oder in der Abfahrt zu flattern beginnt, ist zu viel Gewicht am Lenker.</p>',
  'tip'
)}

${h2('Die richtige Kaufreihenfolge', 'reihenfolge')}
${steps([
  {
    title: 'Satteltasche',
    meta: 'Erste Anschaffung · 90 – 190 €',
    text:
      'Die größte einzelne Kapazität ohne Gepäckträger. Nimm 10 bis 14 Liter – groß genug für zwei bis vier Nächte, klein genug, dass sie nicht pendelt. Achte auf mindestens 15 Zentimeter freie Sattelstütze und genug Abstand zum Reifen.',
  },
  {
    title: 'Lenkerrolle',
    meta: 'Zweite Anschaffung · 70 – 170 €',
    text:
      'Damit hast du das Volumen für ein komplettes Schlafsystem. Miss vorher deine Lenkerbreite und den freien Raum zwischen den Griffen – bei Rennlenkern bleiben oft nur 38 bis 42 Zentimeter.',
  },
  {
    title: 'Oberrohrtasche',
    meta: 'Dritte Anschaffung · 25 – 55 €',
    text:
      'Die günstigste Tasche mit dem größten Alltagsgewinn. Handy und Riegel im Fahren erreichbar zu haben, verändert den Tourtag mehr, als es der Preis vermuten lässt.',
  },
  {
    title: 'Rahmentasche',
    meta: 'Vierte Anschaffung · 60 – 130 €',
    text:
      'Erst jetzt – weil sie oft maßgefertigt sein muss und weil du bis dahin weißt, ob du noch Flaschenhalter im Rahmen brauchst. Halbrahmentaschen lassen eine Flasche stehen, volle Rahmentaschen nicht.',
  },
  {
    title: 'Gabeltaschen',
    meta: 'Nur wenn nötig · 60 – 150 € je Paar',
    text:
      'Brauchst du ab etwa fünf Nächten oder wenn du in abgelegenen Gegenden viel Wasser transportieren musst. Setzt Gewinde an der Gabel voraus – Federgabeln haben die praktisch nie.',
  },
])}

${callout(
  'Kaufe nicht das Set',
  '<p>Komplettsets sind rechnerisch günstiger und praktisch oft eine Fehlinvestition: Die Rahmentasche passt selten wirklich in dein Rahmendreieck, und die enthaltene Satteltasche ist meist zu klein oder zu groß für deinen Einsatz. Wer einzeln kauft, gibt beim ersten Mal mehr aus und beim zweiten Mal nichts.</p>',
  'money'
)}

${h2('Wie viel Volumen brauchst du?', 'volumen')}
${table({
  head: ['Tour', 'Volumen gesamt', 'Typisches Setup'],
  rows: [
    ['1 Nacht, Sommer, Pension', '8 – 15 l', 'Nur Lenkerrolle plus Oberrohrtasche'],
    ['1 Nacht, Sommer, Zelt', '18 – 25 l', 'Satteltasche plus Lenkerrolle'],
    ['2 – 4 Nächte, Sommer', '26 – 35 l', 'Satteltasche, Lenkerrolle, Rahmentasche'],
    ['5 – 10 Nächte oder Übergangszeit', '35 – 45 l', 'Volles Set plus Gabeltaschen'],
    ['Winter oder abgelegene Gegenden', '45 – 60 l', 'Volles Set plus Gepäckträger hinten'],
  ],
  note: 'Der häufigste Fehler ist, für alle Fälle zu groß zu kaufen. Eine halb gefüllte 17-Liter-Satteltasche pendelt stärker als eine volle 11-Liter-Tasche.',
})}

${h2('Was bei welchem Rahmen möglich ist', 'rahmen')}
${checklist([
  '<strong>Kleiner Rahmen (unter 52 cm):</strong> Rahmentasche fasst oft nur 2 bis 3 Liter, und die Sattelstütze ragt kaum heraus. Prüfe vor dem Kauf der Satteltasche, ob 15 cm freie Stütze da sind – sonst brauchst du eine Tasche mit Stützstrebe oder einen kleinen Gepäckträger.',
  '<strong>Fully / MTB mit Dämpfer:</strong> Rahmentasche meist unmöglich, Satteltasche nur kurz. Hier lohnt ein Gepäckträger, der am Sattelrohr klemmt.',
  '<strong>Federgabel:</strong> Keine Gabeltaschen, weil die Gewinde fehlen. Klemmbare Adapter gibt es, sie belasten aber die Standrohre.',
  '<strong>Rennlenker:</strong> Lenkerrolle braucht Spacer, damit die Züge nicht abgeklemmt werden. Kosten 15 bis 30 Euro und sind fast immer nötig.',
  '<strong>Gefederte Sattelstütze:</strong> Satteltasche geht nur eingeschränkt – die Bewegung reibt die Befestigung durch.',
])}

${h2('Das komplette System auf einen Blick', 'uebersicht')}
${table({
  head: ['Tasche', 'Preis neu', 'Preis gebraucht', 'Leergewicht'],
  rows: [
    ['Satteltasche 10 – 14 l', '90 – 190 €', '45 – 95 €', '350 – 700 g'],
    ['Lenkerrolle 10 – 14 l', '70 – 170 €', '35 – 85 €', '250 – 600 g'],
    ['Rahmentasche 4 – 6 l', '60 – 130 €', '30 – 70 €', '180 – 400 g'],
    ['Oberrohrtasche 0,8 l', '25 – 55 €', '15 – 30 €', '80 – 160 g'],
    ['Stem Bag (Stück)', '20 – 45 €', '10 – 25 €', '60 – 120 g'],
    ['Gabeltasche mit Halter (Paar)', '90 – 200 €', '50 – 110 €', '500 – 900 g'],
    ['<strong>Volles Set</strong>', '<strong>355 – 790 €</strong>', '<strong>185 – 415 €</strong>', '<strong>1,4 – 2,9 kg</strong>'],
  ],
  note: 'Leergewicht heißt: das wiegen die leeren Taschen. Es geht vom Nutzgewicht ab und wird beim Vergleich fast immer vergessen.',
})}

<p style="margin-top:1.5rem">
  <a class="btn btn--primary" href="/tools/packlisten-generator.html">Passendes Setup in 8 Fragen finden ${icon('arrow', 'ico')}</a>
</p>
`;

module.exports = article({
  href: '/taschen/taschensystem.html',
  kicker: 'Taschen · Grundlagen',
  title: 'Das Bikepacking-Taschensystem verstehen',
  metaTitle: 'Bikepacking-Taschen: Das System aus 5 Positionen erklärt | Sattelfest',
  description:
    'Welche Bikepacking-Tasche welche Aufgabe hat: Satteltasche, Lenkerrolle, Rahmentasche, Oberrohrtasche und Gabeltaschen im Überblick – mit Volumen, Gewichtsgrenzen, Kaufreihenfolge und Preisen.',
  lead:
    'Fünf Positionen am Rad, vier Aufgaben, eine sinnvolle Kaufreihenfolge. Wer das System versteht, kauft keine Tasche zweimal.',
  meta: [
    { icon: 'bag', text: '11 Minuten Lesezeit' },
    { icon: 'weight', text: 'Mit Gewichtsverteilung' },
    { icon: 'wallet', text: 'Preise neu und gebraucht' },
  ],
  toc: [
    { label: 'Die fünf Positionen im Überblick', id: 'positionen' },
    { label: 'Die vier Aufgaben', id: 'aufgaben' },
    { label: 'Die richtige Kaufreihenfolge', id: 'reihenfolge' },
    { label: 'Wie viel Volumen brauchst du?', id: 'volumen' },
    { label: 'Was bei welchem Rahmen möglich ist', id: 'rahmen' },
    { label: 'Das System auf einen Blick', id: 'uebersicht' },
  ],
  content,
  faq: [
    {
      q: 'Welche Bikepacking-Tasche sollte ich zuerst kaufen?',
      a: '<p>Die Satteltasche. Sie bietet die größte einzelne Kapazität ohne Gepäckträger. 10 bis 14 Liter sind der Zielbereich – groß genug für zwei bis vier Nächte, klein genug, dass sie nicht pendelt. Danach folgen Lenkerrolle, Oberrohrtasche, Rahmentasche und zuletzt Gabeltaschen.</p>',
    },
    {
      q: 'Wie viel Liter Bikepacking-Taschen brauche ich?',
      a: '<p>Für eine Sommernacht im Zelt reichen 18 bis 25 Liter. Zwei bis vier Nächte brauchen 26 bis 35 Liter, längere Touren oder die Übergangszeit 35 bis 45 Liter. Kaufe nicht „für alle Fälle“ größer: Eine halb gefüllte 17-Liter-Satteltasche pendelt stärker als eine volle 11-Liter-Tasche.</p>',
    },
    {
      q: 'Wie verteile ich das Gewicht auf die Taschen?',
      a: '<p>Grob 50 Prozent nach hinten in die Satteltasche, 30 Prozent in die Rahmentasche, 20 Prozent nach vorn an Lenker und Gabel. Das Schwerste gehört tief und mittig in die Rahmentasche, Voluminöses und Leichtes in die Lenkerrolle. Am Lenker sollten nicht mehr als 3 bis 4 Kilo hängen, sonst wird die Lenkung schwammig.</p>',
    },
    {
      q: 'Lohnt sich ein Bikepacking-Taschenset im Bundle?',
      a: '<p>Selten. Sets sind rechnerisch günstiger, aber die enthaltene Rahmentasche passt oft nicht wirklich in dein Rahmendreieck, und die Satteltasche ist für deinen Einsatz meist zu klein oder zu groß. Wer einzeln in der richtigen Reihenfolge kauft, gibt beim ersten Mal mehr aus und muss beim zweiten Mal nichts ersetzen.</p>',
    },
    {
      q: 'Was kostet ein komplettes Bikepacking-Taschenset?',
      a: '<p>Neu 355 bis 790 Euro für Satteltasche, Lenkerrolle, Rahmentasche, Oberrohrtasche und Gabeltaschen. Gebraucht liegst du bei 185 bis 415 Euro. Beachte auch das Leergewicht: Ein volles Set wiegt leer 1,4 bis 2,9 Kilo – das geht von deinem Nutzgewicht ab.</p>',
    },
  ],
  related: [
    { href: '/taschen/satteltasche.html', label: 'Satteltasche (Seatpack)' },
    { href: '/taschen/richtig-packen.html', label: 'Richtig packen: Gewichtsverteilung' },
    { href: '/taschen/taschen-oder-packtaschen.html', label: 'Bikepacking-Taschen oder Packtaschen?' },
    { href: '/tools/packlisten-generator.html', label: 'Packlisten-Generator' },
  ],
});
