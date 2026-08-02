'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, routeGrid,
} = require('../../components');

const content = `
<p class="lead-p">
  Eine gute Einsteigerroute hat vier Eigenschaften: wenig Höhenmeter, guter Untergrund, regelmäßige
  Versorgung und – am wichtigsten – Bahnhöfe entlang der Strecke. Der letzte Punkt nimmt den ganzen
  Druck aus einer ersten Tour. Diese zehn Strecken erfüllen alle vier.
</p>

${stats([
  { value: '10', label: 'Routen', note: 'Alle mit Bahnanschluss und guter Beschilderung.' },
  { value: '2–5', label: 'Tage', note: 'Wochenende bis verlängertes Wochenende.' },
  { value: '4', label: 'Kriterien', note: 'Höhenmeter, Untergrund, Versorgung, Ausstieg.' },
])}

${h2('Was eine Einsteigerroute ausmacht', 'kriterien')}
${checklist([
  '<strong>Unter 500 Höhenmeter pro Tag.</strong> Höhenmeter sind der Faktor, der Einsteiger am zuverlässigsten überrascht.',
  '<strong>Überwiegend asphaltiert oder feiner Schotter.</strong> Grober Schotter kostet mit Gepäck Kraft und Tempo.',
  '<strong>Alle 20 bis 30 Kilometer eine Ortschaft</strong> mit Wasser und Einkaufsmöglichkeit.',
  '<strong>Bahnhöfe entlang der Strecke.</strong> Der wichtigste Punkt: Du kannst jederzeit aussteigen.',
  '<strong>Durchgehend ausgeschildert.</strong> Spart Navigationsstress und Akku.',
  '<strong>Campingplätze oder Trekkingplätze in sinnvollem Abstand.</strong>',
])}

${h2('Zehn Routen für die ersten Touren', 'routen')}

${h3('Sehr leicht: die ersten Male draußen', 'leicht')}
${routeGrid(
  [
    {
      title: 'Bodensee-Radweg',
      region: 'Baden-Württemberg, Bayern, Österreich, Schweiz',
      km: '260 km',
      hm: '700 hm',
      days: '3 – 4 Tage',
      surface: 'Asphalt',
      level: 'leicht',
      text:
        'Die perfekte erste Mehrtagestour: einmal um den See, durch drei Länder, mit Alpenblick und einer Infrastruktur, die kaum zu übertreffen ist. Alle paar Kilometer ein Ort, überall Campingplätze, überall Bahnhöfe und Fähren.',
      highlights: [
        'Praktisch flach – die 700 Höhenmeter verteilen sich auf die gesamte Runde',
        'Fähren erlauben es, die Route jederzeit abzukürzen',
        'Sehr gut ausgeschildert, kaum Navigation nötig',
      ],
      train: 'Konstanz, Friedrichshafen, Lindau und Bregenz haben direkte Fernverbindungen.',
    },
    {
      title: 'Elberadweg, Abschnitt Dresden – Magdeburg',
      region: 'Sachsen, Sachsen-Anhalt',
      km: '350 km',
      hm: '450 hm',
      days: '4 – 5 Tage',
      surface: 'Asphalt',
      level: 'leicht',
      text:
        'Deutschlands beliebtester Fernradweg, und das aus gutem Grund. Fast durchgehend flach am Fluss entlang, hervorragend ausgeschildert, mit Städten wie Meißen, Wittenberg und Dessau als Etappenziele.',
      highlights: [
        'Nahezu keine nennenswerten Anstiege',
        'Dichte Bahnanbindung – Ausstieg an fast jedem Ort möglich',
        'Sehr viele Campingplätze direkt am Weg',
      ],
      train: 'Dresden und Magdeburg sind ICE-Halte, dazwischen fährt der Regionalverkehr entlang der Strecke.',
    },
    {
      title: 'Havelradweg & Spreewald-Runde',
      region: 'Brandenburg',
      km: '180 km',
      hm: '250 hm',
      days: '2 – 3 Tage',
      surface: 'Asphalt, teils feiner Schotter',
      level: 'leicht',
      text:
        'Die flachste Region Deutschlands, dazu Wasser, Wald und die Fließe des Spreewalds. Ideal für ein Wochenende von Berlin aus – du bist in einer Stunde am Start und kannst überall in die Regionalbahn steigen.',
      highlights: [
        'Kaum Höhenmeter – ideal für die allererste Tour mit Gepäck',
        'Aus Berlin ohne Auto erreichbar',
        'Viele Badestellen unterwegs',
      ],
      train: 'Regionalbahnen ab Berlin nach Lübben, Lübbenau und Brandenburg an der Havel.',
    },
    {
      title: 'Ostseeküsten-Radweg, Lübeck – Rügen',
      region: 'Schleswig-Holstein, Mecklenburg-Vorpommern',
      km: '400 km',
      hm: '1.100 hm',
      days: '4 – 6 Tage',
      surface: 'Asphalt, Abschnitte mit Plattenweg',
      level: 'leicht',
      text:
        'Küste, Boddenlandschaft und Ostseebäder. Landschaftlich sehr abwechslungsreich und trotz der Länge einfach zu fahren. Die Höhenmeter stammen aus den Steilküsten, verteilen sich aber gleichmäßig.',
      highlights: [
        'Ständig Wasser in Sichtweite',
        'Sehr viele Campingplätze, in der Hauptsaison aber voll',
        'Bäderbahnen und Fähren als bequeme Abkürzungen',
      ],
      train: 'Lübeck, Wismar, Rostock, Stralsund – alle mit guter Fernverkehrsanbindung.',
    },
  ],
  { columns: 2 }
)}

${h3('Leicht bis mittel: wenn du schon eine Tour hinter dir hast', 'mittel')}
${routeGrid(
  [
    {
      title: 'Rhein-Radweg, Basel – Mainz',
      region: 'Baden-Württemberg, Rheinland-Pfalz, Hessen',
      km: '450 km',
      hm: '900 hm',
      days: '4 – 6 Tage',
      surface: 'Asphalt',
      level: 'leicht',
      text:
        'Teil des EuroVelo 15, meist asphaltiert und durchgehend beschildert. Der Abschnitt durchs Oberrheintal ist flach, das Mittelrheintal ab Bingen landschaftlich der Höhepunkt – Burgen, Weinberge, Fähren.',
      highlights: [
        'Fast durchgehend flach, ideal für hohe Tagesdistanzen',
        'Bahnstrecke verläuft parallel – Ausstieg jederzeit',
        'Sehr dichte Versorgung, viele Campingplätze',
      ],
      train: 'Basel, Freiburg, Karlsruhe, Mannheim, Mainz – alle mit ICE-Anschluss.',
    },
    {
      title: 'Weser-Radweg, Hann. Münden – Bremen',
      region: 'Niedersachsen, Nordrhein-Westfalen',
      km: '400 km',
      hm: '700 hm',
      days: '4 – 5 Tage',
      surface: 'Asphalt',
      level: 'leicht',
      text:
        'Ruhiger als der Rhein, weniger überlaufen als die Elbe, landschaftlich mit dem Weserbergland und der Norddeutschen Tiefebene sehr abwechslungsreich. Einer der am besten organisierten Radwege Deutschlands.',
      highlights: [
        'Sanft abfallend – flussabwärts fährt es sich merklich leichter',
        'Sehr gute Beschilderung, kaum Navigationsaufwand',
        'Fachwerkstädte wie Hameln und Höxter als Etappenziele',
      ],
      train: 'Hann. Münden, Hameln, Minden, Bremen liegen alle an Bahnstrecken.',
    },
    {
      title: 'Altmühltal-Radweg',
      region: 'Bayern',
      km: '250 km',
      hm: '900 hm',
      days: '3 – 4 Tage',
      surface: 'Asphalt und fester Schotter',
      level: 'leicht',
      text:
        'Von Rothenburg ob der Tauber bis Kelheim durch den Naturpark Altmühltal. Kalkfelsen, Wacholderheiden und ein Fluss, der so langsam fließt, dass man ihn kaum bemerkt. Sehr familienfreundlich und entsprechend gut ausgebaut.',
      highlights: [
        'Landschaftlich einer der schönsten deutschen Flussradwege',
        'Gute Mischung aus Asphalt und feinem Schotter',
        'Viele Campingplätze und Naturbadestellen',
      ],
      train: 'Ansbach, Treuchtlingen, Eichstätt und Kelheim (über Saal) sind per Bahn erreichbar.',
    },
    {
      title: 'Ruhrtal-Radweg',
      region: 'Nordrhein-Westfalen',
      km: '240 km',
      hm: '900 hm',
      days: '2 – 3 Tage',
      surface: 'Asphalt',
      level: 'leicht',
      text:
        'Vom Sauerland bis nach Duisburg – erst durch Wälder und Talsperren, dann durch die Industriekultur des Ruhrgebiets. Die dichteste Bahnanbindung aller hier genannten Routen: Du kannst praktisch überall einsteigen.',
      highlights: [
        'Extrem gute Ausstiegsmöglichkeiten – ideal für die erste Tour',
        'Von Bergland zu Industriekultur, sehr abwechslungsreich',
        'Aus dem Ruhrgebiet ohne Auto erreichbar',
      ],
      train: 'Winterberg, Arnsberg, Witten, Essen, Duisburg – S-Bahn und Regionalverkehr durchgehend.',
    },
  ],
  { columns: 2 }
)}

${h3('Mittel: mehr Höhenmeter, mehr Schotter', 'anspruchsvoller')}
${routeGrid(
  [
    {
      title: 'Schwarzwald-Panorama-Radweg',
      region: 'Baden-Württemberg',
      km: '285 km',
      hm: '4.400 hm',
      days: '4 – 6 Tage',
      surface: 'Rund zwei Drittel Asphalt',
      level: 'mittel',
      text:
        'Von Pforzheim über Freudenstadt und Titisee nach Waldshut-Tiengen. Deutlich anspruchsvoller als die Flussrouten, dafür landschaftlich in einer eigenen Liga. Wer Höhenmeter üben will, ohne in die Alpen zu fahren, ist hier richtig.',
      highlights: [
        'Etwa 1.000 Höhenmeter pro Tag – ein echter Schritt nach oben',
        'Zwei Drittel Asphalt, der Rest gut fahrbarer Schotter',
        'Schwarzwaldbahn ermöglicht das Abkürzen von Etappen',
      ],
      train: 'Pforzheim, Freudenstadt, Titisee-Neustadt und Waldshut liegen an Bahnstrecken.',
    },
    {
      title: 'Mainradweg, Bamberg – Frankfurt',
      region: 'Bayern, Hessen',
      km: '330 km',
      hm: '800 hm',
      days: '3 – 5 Tage',
      surface: 'Asphalt',
      level: 'leicht',
      text:
        'Der Main mäandert so ausgiebig, dass man auf 330 Kilometern Luftlinie kaum vorankommt – genau darin liegt der Reiz. Weinberge, Fachwerk, Würzburg, Wertheim, und ein Weg, der fast durchgehend am Wasser bleibt.',
      highlights: [
        'Mehrfach ausgezeichnet als einer der besten deutschen Radwege',
        'Sehr flach, hohe Tagesdistanzen problemlos möglich',
        'Extrem dichte Versorgung und viele Campingplätze',
      ],
      train: 'Bamberg, Würzburg, Aschaffenburg und Frankfurt haben ICE-Anschluss.',
    },
  ],
  { columns: 2 }
)}

${callout(
  'Der Trick mit der Flussrichtung',
  '<p>Fahre Flussradwege <strong>flussabwärts</strong>. Das klingt nach einer Kleinigkeit, macht aber über 300 Kilometer einen spürbaren Unterschied: Du hast ein leichtes, konstantes Gefälle, und in Mitteleuropa weht der Wind überwiegend aus westlicher Richtung, also bei Nord-Süd-Flüssen häufig günstig. Rhein, Elbe, Weser, Main und Donau lassen sich alle bequem flussabwärts fahren, weil die Bahnanbindung an beiden Enden stimmt.</p>',
  'tip'
)}

${h2('Anreise mit der Bahn', 'bahn')}
<p>
  Die Bahn ist beim Bikepacking in Deutschland der Standardweg zum Start – und die wichtigste
  Rückversicherung unterwegs. Was du wissen solltest:
</p>
${table({
  head: ['Zugtyp', 'Fahrrad-Mitnahme', 'Reservierung', 'Kosten'],
  rows: [
    ['Regionalbahn / RE', 'Ja, ohne Reservierung', 'Nicht möglich', '6 – 12 € Fahrradkarte'],
    ['S-Bahn', 'Ja, oft mit Sperrzeiten', 'Nicht nötig', 'Teils kostenlos'],
    ['IC / EC', 'Ja, begrenzte Stellplätze', '<strong>Pflicht</strong>', 'ca. 9 € plus Ticket'],
    ['ICE', 'Nur in neueren Zügen', '<strong>Pflicht, sehr begrenzt</strong>', 'ca. 9 € plus Ticket'],
    ['Fernbus', 'Nur teilweise, selten', 'Pflicht', 'Je nach Anbieter'],
  ],
  note: 'Fahrradstellplätze im Fernverkehr sind im Sommer Wochen im Voraus ausgebucht. Plane die Anreise früh – oder nimm den Regionalverkehr, der immer funktioniert und oft kaum länger dauert.',
})}

${h2('So wählst du deine erste Route aus', 'auswahl')}
${checklist([
  '<strong>Fang in deiner Nähe an.</strong> Anreise kostet Zeit, Geld und Nerven. Eine Route vor der Haustür ist die, die du wirklich fährst.',
  '<strong>Rechne die Höhenmeter durch:</strong> 100 Höhenmeter entsprechen etwa 9 flachen Kilometern an Anstrengung.',
  '<strong>Prüfe, wo die Bahnhöfe liegen.</strong> Ein Bahnhof auf halber Strecke ist deine Versicherung.',
  '<strong>Buche den Schlafplatz vorher</strong> – zumindest für die erste Tour. Suchen bei Dunkelheit ist kein Vergnügen.',
  '<strong>Plane einen Tag weniger als du könntest.</strong> Zeit ist auf Tour das knappste Gut.',
])}
`;

module.exports = article({
  href: '/routen/einsteiger-routen-deutschland.html',
  kicker: 'Routen · Deutschland',
  title: 'Zehn Einsteiger-Routen in Deutschland',
  metaTitle: 'Bikepacking-Routen Deutschland: 10 Touren für Einsteiger | Sattelfest',
  description:
    'Zehn Bikepacking-Routen in Deutschland für Einsteiger: Bodensee, Elbe, Rhein, Weser, Main, Altmühltal, Ostsee, Ruhrtal, Spreewald und Schwarzwald – mit Distanz, Höhenmetern, Untergrund und Bahnanreise.',
  lead:
    'Wenig Höhenmeter, guter Untergrund, regelmäßige Versorgung – und Bahnhöfe entlang der Strecke. Der letzte Punkt nimmt den ganzen Druck aus einer ersten Tour.',
  meta: [
    { icon: 'map', text: '11 Minuten Lesezeit' },
    { icon: 'route', text: 'Mit Höhenmetern und Anreise' },
    { icon: 'mountain', text: 'Nach Schwierigkeit sortiert' },
  ],
  toc: [
    { label: 'Was eine Einsteigerroute ausmacht', id: 'kriterien' },
    { label: 'Zehn Routen für die ersten Touren', id: 'routen' },
    { label: 'Anreise mit der Bahn', id: 'bahn' },
    { label: 'So wählst du deine erste Route', id: 'auswahl' },
  ],
  content,
  faq: [
    {
      q: 'Welche Bikepacking-Route eignet sich am besten für Anfänger?',
      a: '<p>Der Bodensee-Radweg: 260 Kilometer, nur 700 Höhenmeter, durchgehend asphaltiert und hervorragend ausgeschildert. Alle paar Kilometer ein Ort, überall Campingplätze, dazu Fähren und Bahnhöfe zum Abkürzen. Wer in Brandenburg oder Berlin wohnt, fährt stattdessen die Havel- und Spreewald-Runde mit nur 250 Höhenmetern.</p>',
    },
    {
      q: 'Wie viele Höhenmeter pro Tag sind für Einsteiger machbar?',
      a: '<p>Unter 500 Höhenmeter pro Tag sind für die erste Tour ein guter Wert. 100 Höhenmeter entsprechen etwa 9 flachen Kilometern an Anstrengung – ein Tag mit 800 Höhenmetern kostet dich also rund 72 Kilometer zusätzlich. Der Schwarzwald-Panorama-Radweg mit rund 1.000 Höhenmetern täglich ist der Schritt für die zweite oder dritte Saison.</p>',
    },
    {
      q: 'Kann ich mein Fahrrad in der Bahn mitnehmen?',
      a: '<p>Im Regionalverkehr ja, ohne Reservierung, für 6 bis 12 Euro Fahrradkarte. Im IC und EC brauchst du eine Stellplatzreservierung (ca. 9 Euro), im ICE geht es nur in neueren Zügen mit sehr begrenzten Plätzen. Fernverkehrsplätze sind im Sommer Wochen vorher ausgebucht – der Regionalverkehr funktioniert dagegen immer.</p>',
    },
    {
      q: 'Warum sollte ich Flussradwege flussabwärts fahren?',
      a: '<p>Wegen des konstanten leichten Gefälles und der vorherrschenden Windrichtung. In Mitteleuropa weht der Wind überwiegend aus westlicher Richtung, was auf vielen Nord-Süd-Flüssen günstig liegt. Über 300 Kilometer macht das einen spürbaren Unterschied. Rhein, Elbe, Weser, Main und Donau lassen sich alle bequem flussabwärts fahren, weil beide Enden gut per Bahn erreichbar sind.</p>',
    },
    {
      q: 'Wie lange braucht man für einen deutschen Fernradweg?',
      a: '<p>Als Einsteiger rechnest du mit 50 bis 70 Kilometern pro Tag. Der Bodensee-Radweg (260 km) dauert damit 3 bis 4 Tage, der Elberadweg von Dresden nach Magdeburg (350 km) 4 bis 5 Tage, der Rhein-Radweg von Basel nach Mainz (450 km) 4 bis 6 Tage. Plane eher einen Tag mehr als zu wenig ein.</p>',
    },
  ],
  related: [
    { href: '/routen/erstes-mikroabenteuer.html', label: 'Das erste Mikroabenteuer (S24O)' },
    { href: '/routen/uebernachten.html', label: 'Übernachten: Wo du legal schläfst' },
    { href: '/routen/route-selbst-planen.html', label: 'Route selbst planen' },
    { href: '/einstieg/tagesetappen-planen.html', label: 'Wie weit kommst du am Tag?' },
  ],
});
