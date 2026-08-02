'use strict';

const article = require('../_article');
const { h2, callout, esc } = require('../../components');

/**
 * Begriffe alphabetisch. Wird unten automatisch nach Anfangsbuchstaben
 * gruppiert und mit Sprungnavigation versehen.
 */
const TERMS = [
  ['Anything Cage', 'Ein Halter mit drei Schrauben, meist an der Gabel montiert, der beliebige Packsäcke oder große Flaschen aufnimmt. Der günstigste Weg zu zusätzlichem Stauraum – vorausgesetzt, deine Gabel hat die Gewinde.'],
  ['Apex', 'Der Scheitelpunkt einer Kurve. Beim beladenen Fahren fährst du Kurven bewusst weiter außen an, weil das Rad träger einlenkt.'],
  ['Bikepacking-Rennen', 'Selbstversorgte Ultradistanz-Wettbewerbe wie Transcontinental oder Atlas Mountain Race. Eigene Welt mit eigenen Regeln – kein Maßstab für Tourenfahrer.'],
  ['Biwakieren', 'Übernachten ohne Zelt, nur mit Schlafsack und wetterfester Hülle. In Deutschland auf ausgewiesenen Biwakplätzen erlaubt, sonst meist nicht.'],
  ['Biwaksack', 'Wasserdichte Hülle um den Schlafsack. Ab etwa 200 Gramm, die leichteste Form des Wetterschutzes – und die unbequemste.'],
  ['Bottom Bracket', 'Das Tretlager. Relevant, weil viele Rahmentaschen dort mit einem Riemen um das Unterrohr befestigt werden.'],
  ['Bikepacking Roll', 'Andere Bezeichnung für die Lenkerrolle: ein zylindrischer Packsack, der quer am Lenker befestigt wird.'],
  ['Bar Bag', 'Sammelbegriff für Lenkertaschen jeder Bauart – Rolle, Kasten oder Harness-System.'],
  ['Brevet', 'Eine Langstreckenfahrt nach den Regeln der Audax-Bewegung, mit festen Zeitlimits und Kontrollpunkten. Verwandt mit Bikepacking, aber ohne Übernachtungsgepäck.'],
  ['Cargo Cage', 'Siehe Anything Cage. Gabelhalter für Packsäcke und übergroße Flaschen.'],
  ['Chamois', 'Das Sitzpolster in der Radhose, historisch aus Gämsleder. Wird ohne Unterwäsche getragen – ausnahmslos.'],
  ['Chamois-Creme', 'Fettende Creme, die auf Haut oder Polster aufgetragen wird und Reibung reduziert. Ab dem zweiten Tag im Sattel praktisch unverzichtbar.'],
  ['Clipless', 'Klickpedale. Verwirrender englischer Begriff – gemeint sind Pedale mit Einrastmechanismus, nicht Pedale ohne Bindung.'],
  ['Cockpit', 'Der Bereich um Lenker und Vorbau: Griffe, Bremshebel, Halterungen, Taschen. Beim Bikepacking der am meisten umkämpfte Platz am Rad.'],
  ['Credit-Card-Bikepacking', 'Touren ohne Camping-Ausrüstung, mit Übernachtung in Pension oder Hostel. Minimales Gepäck, maximale Übernachtungskosten.'],
  ['Daunenfüllkraft (cuin)', 'Maß für die Bauschkraft von Daune, angegeben in Cubic Inch. Höhere Werte (650, 800, 900) bedeuten mehr Wärme bei weniger Gewicht – und einen höheren Preis.'],
  ['Dry Bag', 'Wasserdichter Packsack mit Rollverschluss. Das Rückgrat jeder wasserdichten Packstrategie.'],
  ['Dropbar', 'Rennlenker mit nach unten gebogenen Enden. Beim Bikepacking beliebt, weil er mehrere Griffpositionen bietet – wichtig gegen taube Hände.'],
  ['EuroVelo', 'Netz von derzeit 17 europäischen Fernradrouten mit einheitlicher Nummerierung. EuroVelo 15 ist der Rhein-Radweg, EuroVelo 6 folgt der Donau.'],
  ['Fahrradstraße', 'Straße, auf der Radfahrende Vorrang haben und nebeneinander fahren dürfen. Kfz-Verkehr nur, wenn ausdrücklich freigegeben.'],
  ['Fastpacking', 'Das Wander-Pendant zum Bikepacking: mehrtägige Touren zu Fuß mit minimalem Gepäck.'],
  ['Feed Bag', 'Siehe Stem Bag. Kleine zylindrische Tasche neben dem Vorbau für Flasche, Snacks oder Handy.'],
  ['Framebag', 'Rahmentasche. Sitzt im Rahmendreieck und ist die fahrdynamisch beste Position für schweres Gepäck.'],
  ['Full Framebag', 'Rahmentasche, die das komplette Rahmendreieck ausfüllt. Maximales Volumen, aber kein Platz mehr für Flaschenhalter im Rahmen.'],
  ['Gabeltasche', 'Packsack oder Tasche an den Gabelholmen, meist über einen Anything Cage befestigt. Bringt Gewicht tief und weit vorn unter.'],
  ['Gravel', 'Ursprünglich Schotter, heute vor allem ein Radtyp: Rennlenker, breitere Reifen, Montagepunkte. Der beliebteste Bikepacking-Radtyp.'],
  ['Gravelbike', 'Rad mit Rennlenker, Scheibenbremsen und Reifenfreiheit ab etwa 40 Millimetern. Der Kompromiss zwischen Rennrad und Mountainbike.'],
  ['GPX', 'Dateiformat für GPS-Tracks. Der Standard zum Austausch von Routen zwischen Planungswerkzeug und Navigationsgerät.'],
  ['Harness', 'Halterungssystem am Lenker, in das ein separater Packsack eingespannt wird. Vorteil gegenüber der festen Rolle: Der Sack lässt sich abends komplett abnehmen.'],
  ['Hardtail', 'Mountainbike mit Federgabel vorn und starrem Hinterbau. Für grobes Gelände die robusteste Bikepacking-Basis.'],
  ['Hike-a-Bike', 'Streckenabschnitte, auf denen das Rad geschoben oder getragen wird. In alpinen Routen eingeplant, in Mittelgebirgsrouten unfreiwillig.'],
  ['Hydration Bladder', 'Trinkblase mit Schlauch, meist im Rucksack. Beim Bikepacking eher unbeliebt, weil ein Rucksack den Rücken belastet.'],
  ['Isomatte', 'Die Matte zwischen dir und dem Boden. Ihre Isolationsleistung (R-Wert) entscheidet über die Nachttemperatur mehr als der Schlafsack.'],
  ['Kettenlehre', 'Messwerkzeug, das den Verschleiß der Kette anzeigt. Kostet unter 15 Euro und verhindert, dass eine verschlissene Kette die Kassette ruiniert.'],
  ['Kettenschloss', 'Wiederverschließbares Verbindungsglied der Kette. Muss zur Anzahl der Gänge passen – ein 11-fach-Schloss gehört ins 11-fach-Werkzeugtäschchen.'],
  ['Komoot', 'Verbreiteter Routenplaner für Rad- und Wandertouren im deutschsprachigen Raum, mit Untergrund- und Wegtypangaben.'],
  ['Komforttemperatur', 'Der Wert, bei dem eine durchschnittliche Frau in einem Schlafsack nicht friert. Der praxisnahe Wert bei der Schlafsackwahl – nicht der Limit- oder Extremwert.'],
  ['Lakeball', 'Kein Bikepacking-Begriff. Falls du hier suchst, hast du dich in ein Golf-Glossar verirrt.'],
  ['Lenkerrolle', 'Zylindrischer Packsack quer am Lenker. Nimmt volumige, leichte Dinge auf: Schlafsack, Zelt-Innenzelt, Isolationsjacke.'],
  ['Loop', 'Rundtour, die am Startpunkt endet. Ideal für Anreise mit dem Auto oder Start vor der Haustür.'],
  ['Microadventure', 'Kurzes Abenteuer im Alltag, oft über eine Nacht. Der Begriff stammt von Alastair Humphreys und beschreibt genau das, was Bikepacking-Einsteigern empfohlen wird.'],
  ['Mudguard', 'Schutzblech. Beim Bikepacking oft weggelassen und dann bei Regen bitter vermisst.'],
  ['Nabendynamo', 'Stromerzeuger in der Vorderradnabe. Erlaubt in Verbindung mit einem USB-Lader autarkes Laden unterwegs, lohnt sich ab etwa einer Woche Tour.'],
  ['Overnighter', 'Tour mit genau einer Übernachtung. Der Standardeinstieg ins Bikepacking.'],
  ['Packsack', 'Siehe Dry Bag. Wasserdichter Sack mit Rollverschluss, in verschiedenen Größen die Grundlage der Innenorganisation.'],
  ['Packtasche', 'Klassische Radtasche, die an einen Gepäckträger gehängt wird. Größer, wasserdichter und günstiger als Bikepacking-Taschen – aber sperriger.'],
  ['Pannenmilch', 'Dichtmilch für Tubeless-Reifen. Verschließt kleine Löcher selbsttätig, muss aber alle drei bis sechs Monate erneuert werden.'],
  ['R-Wert', 'Maß für die Isolationsleistung einer Isomatte. Ab 2,0 sommertauglich, ab 3,0 für Frühjahr und Herbst, ab 4,0 für kalte Nächte.'],
  ['Randonneur', 'Rad für lange Strecken mit Gepäck, traditionell mit Frontgepäckträger und Nabendynamo. Vorläufer des heutigen Bikepacking-Rads.'],
  ['Rahmendreieck', 'Der Bereich zwischen Ober-, Unter- und Sitzrohr. Beherbergt die Rahmentasche – bei kleinen Rahmen entsprechend wenig Volumen.'],
  ['Rahmentasche', 'Siehe Framebag.'],
  ['Reifenfreiheit', 'Der Abstand zwischen Reifen und Rahmen bzw. Gabel. Bestimmt, wie breit du fahren darfst – die wichtigste Zahl beim Radkauf fürs Bikepacking.'],
  ['Rollverschluss', 'Verschlussart, bei der die Öffnung mehrfach eingerollt und mit Schnalle gesichert wird. Ab drei Rollungen wasserdicht.'],
  ['Rückenwind', 'Die einzige Ausrüstung, die man nicht kaufen kann.'],
  ['S24O', 'Sub-24-Hour-Overnight: Losfahren nach Feierabend, eine Nacht draußen, am nächsten Vormittag zurück. Weniger als 24 Stunden, volles Abenteuer.'],
  ['Sattelstützen-Klemmung', 'Der Punkt, an dem die meisten Satteltaschen befestigt werden. Braucht mindestens 10 bis 15 Zentimeter freie Stütze über dem Rahmen.'],
  ['Satteltasche', 'Große Tasche hinter dem Sattel, auch Seatpack genannt. Fasst 5 bis 17 Liter und ist meist die erste Bikepacking-Tasche.'],
  ['Schaltauge', 'Sollbruchstelle zwischen Rahmen und Schaltwerk. Rahmenspezifisch – als Ersatzteil mitnehmen, weil es unterwegs nirgends passend zu bekommen ist.'],
  ['Seatpack', 'Englisch für Satteltasche.'],
  ['Selbstversorgt', 'Prinzip vieler Bikepacking-Rennen: keine externe Unterstützung, kein Begleitfahrzeug, keine vorab organisierten Depots.'],
  ['Setup', 'Die Gesamtheit aus Rad, Taschen und Packweise. Der Begriff, unter dem in Foren am meisten diskutiert und am wenigsten gefahren wird.'],
  ['Singletrail', 'Schmaler, einspuriger Pfad. Mit Gepäck deutlich anspruchsvoller als ohne, weil das Rad träger reagiert.'],
  ['Stem Bag', 'Kleine zylindrische Tasche neben dem Vorbau. Nimmt Flasche, Riegel oder Handy auf und ist die am häufigsten unterschätzte Tasche.'],
  ['Stollenreifen', 'Reifen mit ausgeprägtem Profil. Auf Schotter im Vorteil, auf Asphalt laut und langsam.'],
  ['Systemgewicht', 'Rad plus Gepäck plus Fahrer. Die Zahl, die an jedem Anstieg zählt – und der Wert, den Hersteller für Bauteile begrenzen.'],
  ['Tarp', 'Rechteckige oder geformte Plane als Regendach, gespannt mit Schnüren und Heringen. Leichter als ein Zelt, ohne Insektenschutz und ohne geschlossene Seiten.'],
  ['Top Tube Bag', 'Oberrohrtasche, meist direkt hinter dem Vorbau. Für Dinge, die du im Fahren erreichen willst.'],
  ['Trailhead', 'Startpunkt einer Route, oft mit Parkplatz oder Bahnanschluss.'],
  ['Trekkingplatz', 'Offizieller, meist einfacher Übernachtungsplatz im Wald für Wandernde und Radfahrende. In Deutschland gibt es über 200 davon, Buchung meist online, Preise um 10 bis 15 Euro.'],
  ['Tubeless', 'Reifensystem ohne Schlauch, abgedichtet mit Pannenmilch. Weniger Plattfüße und mehr Komfort bei niedrigem Druck, dafür aufwendiger in der Wartung.'],
  ['Übersetzung', 'Verhältnis von Kettenblatt zu Ritzel. Der kleinste Gang entscheidet, ob du beladen einen steilen Anstieg sitzend fahren kannst.'],
  ['Ultraleicht (UL)', 'Ausrüstungsphilosophie mit dem Ziel minimalen Gewichts. Wirksam, aber teuer – und für die ersten Touren nicht erforderlich.'],
  ['Vorbau', 'Verbindungsstück zwischen Gabelschaft und Lenker. Seine Länge beeinflusst, wie viel Platz eine Lenkerrolle hat.'],
  ['Wildcampen', 'Übernachten außerhalb ausgewiesener Plätze. In Deutschland grundsätzlich verboten oder stark eingeschränkt, in Skandinavien durch das Jedermannsrecht weitgehend erlaubt.'],
  ['Zwiebelprinzip', 'Kleidung in mehreren dünnen Schichten statt einer dicken. Erlaubt Anpassung an Temperatur und Anstrengung, ohne komplett umzuziehen.'],
  ['Zeltunterlage (Footprint)', 'Passgenaue Plane unter dem Zeltboden. Schützt vor Beschädigung, kostet 200 bis 400 Gramm – wird von vielen durch eine dünne Baufolie ersetzt.'],
];

/** Gruppiert die Begriffe nach Anfangsbuchstaben und baut die Sprungnavigation. */
function buildGlossary() {
  const groups = new Map();
  TERMS.forEach(([term, definition]) => {
    const letter = term.charAt(0).toUpperCase().replace('Ü', 'U').replace('Ö', 'O').replace('Ä', 'A');
    if (!groups.has(letter)) groups.set(letter, []);
    groups.get(letter).push([term, definition]);
  });

  const letters = [...groups.keys()].sort((a, b) => a.localeCompare(b, 'de'));

  const jump = `<nav class="glossary-nav" aria-label="Alphabet-Sprungnavigation">${letters
    .map((l) => `<a href="#buchstabe-${l}">${l}</a>`)
    .join('')}</nav>`;

  const body = letters
    .map(
      (letter) => `<section class="glossary-group" id="buchstabe-${letter}">
      <p class="glossary-letter" aria-hidden="true">${letter}</p>
      <h2 style="position:absolute;left:-9999px">Begriffe mit ${letter}</h2>
      <dl class="glossary-list">
        ${groups
          .get(letter)
          .map(([term, def]) => `<div><dt>${esc(term)}</dt><dd>${esc(def)}</dd></div>`)
          .join('')}
      </dl>
    </section>`
    )
    .join('');

  return `${jump}<div class="glossary">${body}</div>`;
}

const content = `
<p class="lead-p">
  Bikepacking hat eine Sprache, die zur Hälfte aus Englisch und zur Hälfte aus Fachjargon besteht –
  und in Foren wird sie einfach vorausgesetzt. Hier stehen ${TERMS.length} Begriffe in
  verständlichem Deutsch, ohne die Zirkelschlüsse, an denen Ausrüstungslexika gern scheitern.
</p>

${callout(
  'Die zwölf wichtigsten für den Anfang',
  '<p>Wenn du nur zwölf Begriffe lernst, dann diese: <strong>Satteltasche, Lenkerrolle, Rahmentasche, Stem Bag, Anything Cage, Dry Bag, R-Wert, Komforttemperatur, GPX, Trekkingplatz, S24O und Reifenfreiheit.</strong> Damit verstehst du 90 Prozent aller Kaufberatungen und Tourberichte.</p>',
  'tip'
)}

${h2('Alle Begriffe von A bis Z', 'begriffe')}
${buildGlossary()}
`;

module.exports = article({
  href: '/einstieg/bikepacking-glossar.html',
  kicker: 'Einstieg · Nachschlagewerk',
  title: 'Bikepacking-Glossar von A bis Z',
  metaTitle: `Bikepacking-Glossar: ${TERMS.length} Begriffe einfach erklärt | Sattelfest`,
  description: `Bikepacking-Fachbegriffe verständlich erklärt: ${TERMS.length} Begriffe von Anything Cage bis Zwiebelprinzip – Seatpack, Framebag, R-Wert, Komforttemperatur, S24O, Trekkingplatz und mehr.`,
  lead: `${TERMS.length} Fachbegriffe in verständlichem Deutsch – von Anything Cage bis Zwiebelprinzip, mit Sprungnavigation.`,
  meta: [
    { icon: 'book', text: `${TERMS.length} Begriffe` },
    { icon: 'check', text: 'Ohne Fachchinesisch' },
  ],
  content,
  related: [
    { href: '/taschen/taschensystem.html', label: 'Das Taschensystem verstehen' },
    { href: '/ausruestung/packliste.html', label: 'Die Packliste mit Gewichten' },
    { href: '/einstieg/was-ist-bikepacking.html', label: 'Was Bikepacking wirklich ist' },
  ],
});
