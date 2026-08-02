'use strict';

const article = require('./_article');
const { h2, callout, checklist } = require('../components');

const content = `
<p class="lead-p">
  Die Fragen, die uns immer wieder erreichen – von „Brauche ich ein Gravelbike?" bis „Wo gehe ich
  nachts eigentlich aufs Klo?". Sortiert nach Themen, mit kurzen Antworten und Verweisen auf die
  ausführlichen Artikel.
</p>

${callout(
  'Die vier häufigsten Fragen in einem Absatz',
  '<p><strong>Nein</strong>, du brauchst kein neues Rad. <strong>Nein</strong>, du musst nicht 100 Kilometer am Tag fahren. <strong>Nein</strong>, Wildcampen ist in Deutschland nicht erlaubt – aber es gibt über 200 legale Trekkingplätze. Und <strong>ja</strong>, eine einzige Nacht draußen ist eine vollwertige Bikepacking-Tour.</p>',
  'tip'
)}

${h2('Und wenn deine Frage nicht dabei ist', 'kontakt')}
${checklist([
  'Das <a href="/einstieg/bikepacking-glossar.html">Glossar</a> erklärt jeden Fachbegriff, der auf dieser Seite vorkommt.',
  'Der <a href="/tools/packlisten-generator.html">Packlisten-Generator</a> beantwortet die Frage „Was nehme ich mit?" für deine konkrete Tour.',
  'Der <a href="/tools/etappen-rechner.html">Etappen-Rechner</a> beantwortet „Wie weit komme ich?" mit deinen eigenen Zahlen.',
  'Für alles andere: Schreib uns. Die Adresse steht im <a href="/impressum.html">Impressum</a>, und häufige Fragen landen anschließend hier.',
])}
`;

module.exports = article({
  href: '/faq.html',
  kicker: 'Service · Häufige Fragen',
  title: 'Häufige Fragen zum Bikepacking',
  metaTitle: 'Bikepacking FAQ: Die 24 häufigsten Fragen beantwortet | Sattelfest',
  description:
    'Die häufigsten Fragen zum Bikepacking: Welches Rad, wie viele Kilometer, welche Taschen, wo übernachten, was kostet es, wie sicher ist es – kurz beantwortet mit Verweis auf die ausführlichen Artikel.',
  lead:
    'Von „Brauche ich ein Gravelbike?" bis „Wo gehe ich nachts aufs Klo?" – die Fragen, die uns am häufigsten erreichen.',
  meta: [
    { icon: 'info', text: '24 Fragen' },
    { icon: 'check', text: 'Kurz beantwortet' },
    { icon: 'book', text: 'Mit Verweisen zum Nachlesen' },
  ],
  image: false,
  content,
  faqTitle: 'Alle Fragen im Überblick',
  faq: [
    /* --- Einstieg ------------------------------------------------------- */
    {
      q: 'Brauche ich ein spezielles Bikepacking-Rad?',
      a: '<p>Nein. Für die ersten Touren reicht praktisch jedes verkehrssichere Rad mit funktionierenden Bremsen und einer Übersetzung, die zu deinem Gelände passt. Genau das ist der Vorteil des Systems: Weil keine Gepäckträger-Ösen nötig sind, lässt sich fast jedes Rad bepacken. Kaufe erst neu, wenn du nach zwei bis drei Touren konkret sagen kannst, was dir fehlt. Mehr dazu: <a href="/einstieg/welches-fahrrad.html">Welches Rad passt zum Bikepacking?</a></p>',
    },
    {
      q: 'Wie viele Kilometer schaffe ich am Tag?',
      a: '<p>Als untrainierter Einsteiger 35 bis 55 Kilometer auf flachem Asphalt, sportlich 60 bis 80. Auf Schotter und im Bergigen sinken alle Werte um 30 bis 45 Prozent. Faustregel: Nimm die Strecke, die du an einem guten Tag ohne Gepäck schaffst, und rechne mit rund 60 Prozent davon – begrenzend ist dabei nicht die Distanz, sondern deine Zeit im Sattel. Der <a href="/tools/etappen-rechner.html">Etappen-Rechner</a> macht das mit deinen Zahlen.</p>',
    },
    {
      q: 'Wie lange sollte meine erste Tour sein?',
      a: '<p>Eine Übernachtung. Das reicht völlig, um alles zu erleben, worum es beim Bikepacking geht, und ist kurz genug, dass ein Regentag oder ein Platten die Tour nicht kippt. Das Format heißt <a href="/routen/erstes-mikroabenteuer.html">S24O</a>: Freitagabend los, Samstagvormittag zurück, kein Urlaubstag nötig.</p>',
    },
    {
      q: 'Was kostet der Einstieg ins Bikepacking?',
      a: '<p>Wenn du Rad, Schlafsack und Isomatte hast oder leihen kannst: 79 bis 223 Euro für die erste komplette Tour. Eine sparsame Neuausstattung liegt bei 365 Euro, eine solide bei 1.085, eine komfortable bei 2.360 – jeweils ohne Rad. Alle Posten einzeln: <a href="/einstieg/was-kostet-bikepacking.html">Was Bikepacking wirklich kostet</a></p>',
    },
    {
      q: 'Bin ich zu alt oder zu untrainiert dafür?',
      a: '<p>Für 40 bis 60 Kilometer am Tag reicht normale Alltagsfitness – das ist der entscheidende Punkt. Bikepacking ist kein Ausdauersport, sondern Reisen mit dem Rad. Du bestimmst die Etappenlänge, und du darfst jederzeit schieben, pausieren oder in den Zug steigen. Ab 80 Kilometern täglich lohnt ein <a href="/unterwegs/training-vorbereitung.html">Acht-Wochen-Aufbau</a>.</p>',
    },

    /* --- Taschen -------------------------------------------------------- */
    {
      q: 'Welche Tasche soll ich zuerst kaufen?',
      a: '<p>Die Satteltasche – sie bietet die größte einzelne Kapazität ohne Gepäckträger. 10 bis 14 Liter sind der Zielbereich. Danach folgen Lenkerrolle, Oberrohrtasche, Rahmentasche und zuletzt Gabeltaschen. Die vollständige Reihenfolge mit Preisen: <a href="/taschen/taschensystem.html">Das Taschensystem verstehen</a></p>',
    },
    {
      q: 'Wie viele Liter Taschenvolumen brauche ich?',
      a: '<p>Für eine Sommernacht im Zelt 18 bis 25 Liter, für zwei bis vier Nächte 26 bis 35 Liter, für längere Touren oder die Übergangszeit 35 bis 45 Liter. Kaufe nicht „für alle Fälle" größer: Eine halb gefüllte Satteltasche pendelt deutlich stärker als eine volle kleinere.</p>',
    },
    {
      q: 'Warum pendelt meine Satteltasche?',
      a: '<p>Meist, weil sie nicht voll gepackt ist oder das schwere Gepäck zu weit hinten liegt. Pack Kompaktes und Schweres direkt an den Sattel, fülle die Tasche vollständig aus und zieh den Kompressionsriemen so fest, dass sie sich von Hand nicht mehr seitlich bewegen lässt. Nach zehn Kilometern noch einmal nachziehen – Gepäck setzt sich.</p>',
    },
    {
      q: 'Sind Packtaschen mit Gepäckträger schlechter?',
      a: '<p>Nein, oft sind sie besser. Sie fassen mit 60 bis 100 Litern das Doppelte bis Dreifache, kosten mit Träger 180 bis 400 Euro statt 355 bis 790, sind meist echt wasserdicht und in Sekunden gepackt. Auf asphaltierten Fernradwegen – also einem großen Teil aller deutschen Touren – bringt ein Bikepacking-Set keinen seiner Vorteile zur Geltung. Der <a href="/taschen/taschen-oder-packtaschen.html">direkte Vergleich</a>.</p>',
    },
    {
      q: 'Sind Bikepacking-Taschen wasserdicht?',
      a: '<p>Viele sind nur wasserabweisend und halten 30 bis 90 Minuten leichten Regen. Selbst bei echten wasserdichten Taschen läuft Wasser an der Sattelstütze entlang hinein. Die zuverlässige Lösung sind <a href="/taschen/wasserdicht-packen.html">Packsäcke im Inneren</a>: Schlafsack und Wechselkleidung in einen eigenen Dry Bag, nasses Zeltmaterial getrennt davon.</p>',
    },

    /* --- Ausrüstung ----------------------------------------------------- */
    {
      q: 'Wie viel Gepäck ist normal?',
      a: '<p>Unter 10 Kilogramm ist die Marke, ab der sich ein Setup leicht anfühlt. Eine vollständige Sommerausrüstung mit Zelt liegt bei etwa 8,5 Kilo ohne Wasser. Über 15 Kilo solltest du prüfen, ob ein Gepäckträger nicht angenehmer wäre – dort trägt sich Gewicht besser als in Bikepacking-Taschen.</p>',
    },
    {
      q: 'Zelt, Tarp oder Biwaksack?',
      a: '<p>Für die erste Tour ein Zelt – nicht weil es besser wäre, sondern weil es Fehler verzeiht: Es steht auch dann, wenn du beim Aufbau alles falsch machst, und hat Insektenschutz, geschlossene Seiten und einen Boden. Ein Tarp spart 900 Gramm, muss aber richtig gespannt werden, und das lernt man nicht im Dunkeln bei aufziehendem Wind.</p>',
    },
    {
      q: 'Warum friere ich trotz gutem Schlafsack?',
      a: '<p>Weil unter deinem Körpergewicht die Isolation des Schlafsacks plattgedrückt wird – nach unten wärmt allein die Isomatte. Deren R-Wert entscheidet über die Nacht: ab 2,5 für den Sommer, ab 3,0 bis 4,0 für Frühjahr und Herbst. Mehr dazu: <a href="/ausruestung/schlafsack-isomatte.html">Schlafsack &amp; Isomatte</a></p>',
    },
    {
      q: 'Brauche ich einen Kocher?',
      a: '<p>Nicht zwingend. In Deutschland liegt alle 30 bis 50 Kilometer ein Supermarkt, und ein Kochset wiegt mit Kartusche rund 700 Gramm. Ein Kocher lohnt sich, wenn du auf Trekkingplätzen übernachtest (dort gibt es nichts zu kaufen), morgens Kaffee willst oder im Frühjahr und Herbst fährst.</p>',
    },
    {
      q: 'Brauche ich einen Wasserfilter in Deutschland?',
      a: '<p>Nein. Friedhöfe, Sportplätze, Campingplätze, Gaststätten und Supermärkte liefern dir alle paar Stunden Trinkwasser – die Friedhofs-Regel funktioniert in nahezu jedem Ort, auch sonntags. Ein Filter lohnt sich in Skandinavien, in Südeuropa im Sommer und auf abgelegenen Routen mit über 50 Kilometern zwischen Ortschaften.</p>',
    },
    {
      q: 'Wie viel Kleidung nehme ich mit?',
      a: '<p>Zwei Garnituren: eine am Körper, eine im Gepäck. Mehr bleibt erfahrungsgemäß im Packsack – auch auf einer Woche. Wichtiger als die Menge ist das Material: Merino oder Kunstfaser, niemals Baumwolle. Die vollständige Liste steht unter <a href="/ausruestung/kleidung.html">Kleidung: Das Zwiebelprinzip</a>.</p>',
    },
    {
      q: 'Reicht mein Handy zum Navigieren?',
      a: '<p>Für die ersten Touren ja. Das Problem ist nicht die Kartenqualität, sondern der Akku: Navigation mit hellem Display leert ein Handy in vier bis sechs Stunden. Mit ausgeschaltetem Display, Sprachansage und einer 10.000-mAh-Powerbank kommst du problemlos über ein verlängertes Wochenende.</p>',
    },
    {
      q: 'Welches Werkzeug muss mit?',
      a: '<p>Zwölf Teile decken 95 Prozent aller Pannen ab: Multitool mit Kettennieter, Handpumpe, zwei Ersatzschläuche, zwei Reifenheber, Flickzeug, Kettenschloss, Ersatz-Schaltauge, Kabelbinder, Gewebeband, Kettenöl und ein Reifen-Boot. Nimm aber nur mit, was du auch anwenden kannst. <a href="/ausruestung/werkzeug-reparatur.html">Das komplette Kit</a></p>',
    },

    /* --- Übernachten & Routen ------------------------------------------- */
    {
      q: 'Darf ich in Deutschland wild campen?',
      a: '<p>Nein. Das Zelten in Wald und freier Natur ist in allen Bundesländern verboten oder stark eingeschränkt, in Bayern besonders streng. In Naturschutzgebieten und Nationalparks gilt es ausnahmslos. Die legale Alternative sind über 200 ausgewiesene <a href="/routen/uebernachten.html">Trekking- und Biwakplätze</a> für 0 bis 15 Euro pro Nacht.</p>',
    },
    {
      q: 'Gibt es auf Trekkingplätzen Wasser und Strom?',
      a: '<p>In der Regel weder noch. Das ist der Punkt, der am häufigsten übersehen wird: Fülle vorher auf und rechne mit 3 bis 4 Litern für Abendessen, Trinken und Frühstück. Strom gibt es definitiv nicht – lade deine Powerbank am Tag zuvor oder unterwegs im Café.</p>',
    },
    {
      q: 'Welche Route eignet sich für die erste Mehrtagestour?',
      a: '<p>Der Bodensee-Radweg: 260 Kilometer, nur 700 Höhenmeter, durchgehend asphaltiert, alle paar Kilometer ein Ort und überall Bahnhöfe und Fähren zum Abkürzen. Neun weitere Vorschläge mit Distanz, Höhenmetern und Bahnanreise: <a href="/routen/einsteiger-routen-deutschland.html">Zehn Einsteiger-Routen</a></p>',
    },
    {
      q: 'Wann ist die beste Zeit für eine Tour?',
      a: '<p>Mai, Juni und September. Der Juni hat mit 16,5 Stunden das meiste Tageslicht, der September das stabilere Wetter, leerere Plätze und ruhigere Straßen – dafür drei Stunden kürzere Tage. Juli und August sind warm, aber die Campingplätze sind voll und das Gewitterrisiko ist höher.</p>',
    },

    /* --- Unterwegs ------------------------------------------------------ */
    {
      q: 'Ist Bikepacking allein gefährlich?',
      a: '<p>Nicht grundsätzlich. Was sich ändert, ist die Fehlertoleranz: Ein gebrochenes Schaltauge ist zu zweit ein Ärgernis und allein eine ernste Planänderung. Für Solo-Touren gilt deshalb nicht „mehr Mut", sondern mehr Puffer – und eine Vertrauensperson, die Route und Zeitplan kennt. Das ist die wirksamste Sicherheitsmaßnahme überhaupt.</p>',
    },
    {
      q: 'Was mache ich nachts, wenn ich aufs Klo muss?',
      a: '<p>Auf Camping- und den meisten Trekkingplätzen gibt es eine Toilette oder zumindest ein Trockenklo. Wenn nicht, gilt die Outdoor-Regel: mindestens 50 Meter Abstand zu Wegen, Gewässern und Zeltplätzen, ein etwa 15 Zentimeter tiefes Loch graben, alles wieder zuschütten. Toilettenpapier gehört in einen Beutel und wird mitgenommen. Eine kleine Schaufel wiegt 20 Gramm – oder du nimmst einen Hering.</p>',
    },
    {
      q: 'Wie wasche ich mich und meine Kleidung unterwegs?',
      a: '<p>Ein Mikrofasertuch und Seifenblätter reichen für den Körper. Kleidung wäschst du abends im Waschbecken, wringst sie im Handtuch fest aus und hängst sie über Nacht ins Zelt – Merino und Kunstfaser sind morgens trocken. Campingplätze haben oft Waschmaschinen; nach vier Tagen ist das mehr wert als die Dusche.</p>',
    },
    {
      q: 'Was tue ich, wenn ich nicht mehr weiterkann?',
      a: '<p>Zum nächsten Bahnhof fahren. Die Bahn ist in Deutschland der zuverlässigste Notausstieg – im Nahverkehr nimmst du dein Rad meist ohne Reservierung mit. Deshalb steht in der Routenplanung: pro Tag mindestens eine Ausstiegsmöglichkeit einplanen. Abbrechen ist kein Scheitern, sondern Teil der Planung.</p>',
    },
  ],
  related: [
    { href: '/einstieg/was-ist-bikepacking.html', label: 'Was Bikepacking wirklich ist' },
    { href: '/einstieg/bikepacking-glossar.html', label: 'Bikepacking-Glossar A bis Z' },
    { href: '/tools/packlisten-generator.html', label: 'Packlisten-Generator' },
    { href: '/tools/etappen-rechner.html', label: 'Etappen- & Gewichts-Rechner' },
  ],
});
