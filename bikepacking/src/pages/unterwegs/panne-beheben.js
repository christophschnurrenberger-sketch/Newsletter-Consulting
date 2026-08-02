'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, steps,
} = require('../../components');

const content = `
<p class="lead-p">
  Vier von fünf Pannen sind Plattfüße. Wer den Schlauchwechsel sicher beherrscht, ist auf fast alles
  vorbereitet, was tatsächlich passiert. Die übrigen Fälle stehen hier ebenfalls – mit dem Fokus
  darauf, was du am Straßenrand wirklich tun kannst.
</p>

${stats([
  { value: '80 %', label: 'Aller Pannen', note: 'Sind Plattfüße. Der Rest verteilt sich.' },
  { value: '10 Min.', label: 'Schlauchwechsel', note: 'Mit Übung. Beim ersten Mal eher 30.' },
  { value: '1', label: 'Regel', note: 'Erst die Ursache finden, dann den Schlauch wechseln.' },
])}

${h2('Plattfuß', 'platt')}
${steps([
  {
    title: 'Sichern und vorbereiten',
    text:
      'Weg von der Fahrbahn, aufs Gras oder auf einen Feldweg. Rad umdrehen oder anlehnen. Bei Scheibenbremse: Sobald das Laufrad draußen ist, nicht in den Bremshebel greifen – die Kolben fahren dann zusammen und lassen sich nur mühsam zurückdrücken.',
  },
  {
    title: 'Laufrad ausbauen',
    meta: 'Hinten: vorher auf das kleinste Ritzel schalten',
    text:
      'Schnellspanner öffnen oder Steckachse herausschrauben. Beim Hinterrad das Schaltwerk mit der Hand nach hinten ziehen, dann fällt das Rad heraus. Bei Felgenbremse zusätzlich die Bremse öffnen.',
  },
  {
    title: 'Reifen abziehen',
    text:
      'Restliche Luft ablassen. Den Reifen rundherum von der Felgenflanke ins Felgenbett drücken – das schafft Platz und macht das Abziehen erst möglich. Dann mit zwei Reifenhebern eine Seite über die Felgenkante hebeln. Die zweite Seite muss nicht ab.',
  },
  {
    title: 'Ursache finden – der wichtigste Schritt',
    meta: 'Wird oft übersprungen und rächt sich sofort',
    text:
      'Fahre mit dem Finger vorsichtig das Innere des Reifens ab. Suche nach Glassplittern, Dornen und Drähten. Wenn du die Ursache nicht entfernst, ist der neue Schlauch nach zwei Kilometern ebenfalls platt. Prüfe auch das Felgenband auf Verrutschen.',
  },
  {
    title: 'Neuen Schlauch einlegen',
    text:
      'Schlauch leicht aufpumpen, bis er Form hat – dann faltet er sich nicht und wird nicht eingeklemmt. Ventil zuerst durch das Loch stecken, dann den Schlauch rundherum in den Reifen legen.',
  },
  {
    title: 'Reifen aufziehen – mit den Händen',
    meta: 'Niemals mit Hebeln',
    text:
      'Beginne gegenüber vom Ventil und arbeite dich zu beiden Seiten vor. Das letzte Stück ist am schwersten: Drücke den bereits montierten Teil ins Felgenbett, dann entsteht genug Spiel. Reifenheber quetschen fast immer den Schlauch – der Klassiker, warum es nach fünf Minuten wieder zischt.',
  },
  {
    title: 'Kontrollieren und aufpumpen',
    text:
      'Rundherum prüfen, ob der Schlauch irgendwo unter der Reifenwulst hervorschaut. Dann auf halben Druck aufpumpen, Sitz kontrollieren, voll aufpumpen. Laufrad einbauen, Bremse prüfen, Schnellspanner fest.',
  },
])}

${callout(
  'Die drei häufigsten Fehler beim Schlauchwechsel',
  '<p><strong>1.</strong> Ursache nicht gesucht – der Dorn steckt noch im Reifen. <strong>2.</strong> Reifen mit Hebeln aufgezogen – der Schlauch wird eingeklemmt. <strong>3.</strong> Schlauch komplett leer eingelegt – er verdreht sich und wird zwischen Reifen und Felge gequetscht. Alle drei führen dazu, dass du in zehn Minuten wieder anhältst.</p>',
  'warn'
)}

${h3('Wenn der Schnitt zu groß ist', 'schnitt')}
<p>
  Bei einem Schnitt über etwa 5 Millimeter drückt sich der Schlauch heraus und platzt sofort wieder.
  Die Lösung ist ein „Boot“ – eine stabile Einlage zwischen Reifen und Schlauch:
</p>
${checklist([
  '<strong>Fertiges Reifen-Boot</strong> aus dem Reparaturset, selbstklebend',
  '<strong>Geldschein</strong> – der Klassiker, das Papier ist erstaunlich reißfest',
  '<strong>Stück einer alten Reifenflanke</strong> aus dem Werkzeugtäschchen',
  '<strong>Gewebeband</strong>, mehrfach übereinander geklebt',
  '<strong>Danach mit reduziertem Druck fahren</strong> und den Reifen bei nächster Gelegenheit ersetzen',
])}

${h3('Tubeless-Reifen', 'tubeless')}
${checklist([
  '<strong>Kleines Loch:</strong> Rad so drehen, dass das Loch unten ist – die Milch dichtet meist innerhalb einer Minute ab',
  '<strong>Mittleres Loch:</strong> Mit einem Reparaturstöpsel (Plug) von außen verschließen, danach nachpumpen',
  '<strong>Großes Loch:</strong> Ventil ausbauen und einen normalen Schlauch einziehen – deshalb gehört auch bei Tubeless immer ein Schlauch ins Gepäck',
  '<strong>Milch ist eingetrocknet:</strong> Kommt nach 3 bis 6 Monaten vor. Vor der Tour prüfen und nachfüllen',
])}

${h2('Kettenriss', 'kette')}
${steps([
  {
    title: 'Beschädigtes Glied finden',
    text:
      'Meist ist ein Glied aufgebogen oder ein Niet herausgedrückt. Prüfe auch die benachbarten Glieder – oft sind zwei betroffen.',
  },
  {
    title: 'Kette öffnen',
    text:
      'Wenn ein Kettenschloss verbaut ist: mit den Händen oder einer Zange öffnen. Wenn nicht: mit dem Kettennieter am Multitool den Niet des beschädigten Glieds herausdrücken – nicht ganz heraus, sonst bekommst du ihn nicht zurück.',
  },
  {
    title: 'Beschädigte Glieder entfernen',
    text:
      'Immer ein Innen- und ein Außenglied zusammen entfernen, damit die Enden wieder zusammenpassen. Dann das mitgeführte Kettenschloss einsetzen und durch kräftiges Treten einrasten lassen.',
  },
  {
    title: 'Achtung: die Kette ist jetzt kürzer',
    meta: 'Der Punkt, an dem Menschen ihr Schaltwerk zerstören',
    text:
      'Eine gekürzte Kette darf nicht mehr auf die Kombination größtes Kettenblatt plus größtes Ritzel. Sonst reißt das Schaltwerk ab und beschädigt Rahmen und Laufrad. Meide diese Kombination bis zur Reparatur konsequent.',
  },
])}

${h2('Schaltprobleme', 'schaltung')}
${table({
  head: ['Symptom', 'Wahrscheinliche Ursache', 'Sofortmaßnahme'],
  rows: [
    [
      'Kette springt beim Schalten nach oben nicht',
      'Zug zu locker',
      'Rändelschraube am Schaltwerk gegen den Uhrzeigersinn drehen, je 1/2 Umdrehung',
    ],
    [
      'Kette springt nicht nach unten',
      'Zug zu stramm',
      'Rändelschraube im Uhrzeigersinn drehen',
    ],
    [
      'Schaltung springt sprunghaft, rasselt dauerhaft',
      'Schaltauge verbogen',
      'Von hinten prüfen, ob das Schaltwerk parallel zu den Ritzeln steht. Vorsichtig richten oder ersetzen',
    ],
    [
      'Kein Schalten mehr möglich',
      'Zug gerissen',
      'Kette auf mittleres Ritzel legen, Begrenzungsschrauben eindrehen – als Eingang weiterfahren',
    ],
    [
      'Kette fällt vorn herunter',
      'Umwerfer verstellt oder Kette verschlissen',
      'Kette wieder auflegen, Begrenzungsschrauben nachstellen',
    ],
    [
      'Lautes Knacken beim Treten',
      'Kette trocken oder verschlissen',
      'Ölen. Wenn es bleibt: Kette und Kassette prüfen lassen',
    ],
  ],
  note: 'Faustregel bei der Feineinstellung: Immer nur eine halbe Umdrehung, dann testen. Wer eine ganze Umdrehung dreht, schießt über das Ziel hinaus.',
})}

${h2('Gebrochene Speiche', 'speiche')}
${checklist([
  '<strong>Sofort anhalten.</strong> Eine lose Speiche kann sich ins Schaltwerk oder in die Bremse verheddern.',
  '<strong>Die gebrochene Speiche umwickeln</strong> und an einer Nachbarspeiche fixieren, damit sie nichts beschädigt.',
  '<strong>Die beiden Nachbarspeichen etwas lösen</strong>, damit sich das Laufrad wieder annähernd rund dreht.',
  '<strong>Bremse öffnen</strong>, wenn das Laufrad einen Schlag hat und schleift.',
  '<strong>Langsam und ohne Wiegetritt weiterfahren</strong>, bis zur nächsten Werkstatt.',
  '<strong>Bei zwei oder mehr gebrochenen Speichen: nicht weiterfahren.</strong> Das Laufrad kann kollabieren.',
])}
<p>
  Speichenbrüche treten fast immer hinten auf der Antriebsseite auf. Bei Bikepacking-Gewicht sind sie
  selten – ein regelmäßiger Speichencheck vor der Tour reduziert das Risiko deutlich.
</p>

${h2('Bremsen', 'bremsen')}
${table({
  head: ['Problem', 'Ursache', 'Was du tun kannst'],
  rows: [
    ['Bremshebel zieht bis zum Lenker', 'Beläge abgefahren oder Luft im System', 'Beläge tauschen. Bei Luft: Bremse ist unterwegs kaum zu retten'],
    ['Schleifgeräusch', 'Scheibe verzogen oder Sattel verschoben', 'Sattel lösen, Bremse ziehen, Schrauben wieder festziehen'],
    ['Quietschen bei Nässe', 'Normal bei Scheibenbremsen', 'Nichts – verschwindet nach ein paar Bremsungen'],
    ['Dauerhaftes Quietschen trocken', 'Beläge verölt', 'Beläge tauschen, Scheibe mit Bremsenreiniger säubern'],
    ['Bremse blockiert nach Radausbau', 'In den Hebel gegriffen bei ausgebautem Rad', 'Kolben mit einem Reifenheber vorsichtig zurückdrücken'],
    ['Felgenbremse greift nicht mehr', 'Beläge oder Felgenflanke verschlissen', 'Beläge tauschen. Bei verschlissener Flanke: nicht weiterfahren'],
  ],
  note: 'Auf langen Touren mit vielen Abfahrten verschleißen Bremsbeläge deutlich schneller als im Alltag. Ein Satz Ersatzbeläge wiegt 30 Gramm und kostet 15 bis 30 Euro.',
})}

${callout(
  'Was du wirklich reparieren können musst',
  '<p>Drei Dinge: <strong>Schlauch wechseln, Kette öffnen und schließen, Schaltung grob nachstellen.</strong> Alles andere ist entweder selten oder unterwegs ohnehin nicht zu lösen. Übe diese drei Handgriffe einmal an einem Nachmittag zu Hause – mit sauberen Händen, gutem Licht und ohne Zeitdruck. Das ist die beste Stunde, die du in deine Tourvorbereitung investieren kannst.</p>',
  'tip'
)}

${h2('Wenn nichts mehr geht', 'aufgeben')}
${checklist([
  '<strong>Die Bahn ist der zuverlässigste Notausstieg in Deutschland.</strong> Im Nahverkehr meist ohne Reservierung.',
  '<strong>Fahrradläden über die Offline-Karte suchen.</strong> In kleinen Orten hilft oft auch eine Landmaschinenwerkstatt weiter.',
  '<strong>Montags haben viele Fahrradläden geschlossen.</strong> Plane das ein, wenn du auf eine Reparatur angewiesen bist.',
  '<strong>ADFC-Mitglieder haben Pannenhilfe.</strong> Manche Versicherungen und Kreditkarten ebenfalls – vorher prüfen.',
  '<strong>Ein Taxi mit Rad</strong> ist teuer, aber sonntagabends manchmal die einzige Möglichkeit.',
  '<strong>Nicht in der Dämmerung mit halb repariertem Rad weiterfahren.</strong> Lieber eine ungeplante Übernachtung als ein Unfall.',
])}
`;

module.exports = article({
  href: '/unterwegs/panne-beheben.html',
  kicker: 'Unterwegs · Reparatur',
  title: 'Panne unterwegs beheben',
  metaTitle: 'Fahrradpanne unterwegs: Schlauch, Kette, Schaltung, Speiche | Sattelfest',
  description:
    'Pannen beim Bikepacking selbst beheben: Schlauchwechsel Schritt für Schritt, Kettenriss reparieren, Schaltung nachstellen, gebrochene Speiche sichern und Bremsenprobleme – mit den häufigsten Fehlern.',
  lead:
    'Vier von fünf Pannen sind Plattfüße. Wer den Schlauchwechsel sicher beherrscht, ist auf fast alles vorbereitet, was tatsächlich passiert.',
  meta: [
    { icon: 'tool', text: '10 Minuten Lesezeit' },
    { icon: 'check', text: 'Schritt-für-Schritt-Anleitungen' },
    { icon: 'alert', text: 'Mit typischen Fehlern' },
  ],
  toc: [
    { label: 'Plattfuß', id: 'platt' },
    { label: 'Kettenriss', id: 'kette' },
    { label: 'Schaltprobleme', id: 'schaltung' },
    { label: 'Gebrochene Speiche', id: 'speiche' },
    { label: 'Bremsen', id: 'bremsen' },
    { label: 'Wenn nichts mehr geht', id: 'aufgeben' },
  ],
  content,
  faq: [
    {
      q: 'Warum ist mein neuer Schlauch sofort wieder platt?',
      a: '<p>Meist aus einem von drei Gründen: Die Ursache steckt noch im Reifen (Dorn, Glassplitter, Draht) – deshalb muss man das Reifeninnere immer mit dem Finger abtasten. Oder der Reifen wurde mit Hebeln aufgezogen und der Schlauch dabei eingeklemmt. Oder der Schlauch wurde völlig leer eingelegt und hat sich verdreht.</p>',
    },
    {
      q: 'Wie ziehe ich einen Reifen ohne Hebel auf?',
      a: '<p>Beginne gegenüber vom Ventil und arbeite dich zu beiden Seiten vor. Das letzte Stück ist am schwersten – drücke dann den bereits montierten Teil des Reifens ins tiefe Felgenbett, dadurch entsteht genug Spiel, um das letzte Stück mit den Daumen über die Kante zu schieben. Reifenheber quetschen fast immer den Schlauch.</p>',
    },
    {
      q: 'Was mache ich bei einem Kettenriss?',
      a: '<p>Beschädigtes Glied mit dem Kettennieter entfernen (immer ein Innen- und ein Außenglied zusammen) und ein mitgeführtes Kettenschloss einsetzen. Wichtig: Die Kette ist danach kürzer und darf nicht mehr auf die Kombination größtes Kettenblatt plus größtes Ritzel – sonst reißt das Schaltwerk ab.</p>',
    },
    {
      q: 'Was tue ich, wenn eine Speiche bricht?',
      a: '<p>Sofort anhalten, die gebrochene Speiche umwickeln und an einer Nachbarspeiche fixieren, damit sie sich nicht ins Schaltwerk verheddert. Die beiden Nachbarspeichen etwas lösen, damit das Laufrad annähernd rund läuft, notfalls die Bremse öffnen. Dann langsam und ohne Wiegetritt zur nächsten Werkstatt. Bei zwei oder mehr gebrochenen Speichen nicht weiterfahren.</p>',
    },
    {
      q: 'Was passiert, wenn ich bei ausgebautem Rad die Scheibenbremse ziehe?',
      a: '<p>Die Bremskolben fahren zusammen und lassen keinen Platz mehr für die Bremsscheibe. Das ist reparabel: Mit einem breiten Reifenheber oder einem speziellen Kolbenrücksteller die Kolben vorsichtig und gleichmäßig zurückdrücken. Ärgerlich, aber kein Schaden – deshalb legt man bei ausgebautem Rad einen Transportsicherungskeil ein.</p>',
    },
  ],
  related: [
    { href: '/ausruestung/werkzeug-reparatur.html', label: 'Werkzeug & Reparatur-Kit' },
    { href: '/unterwegs/sicherheit-notfall.html', label: 'Sicherheit & Notfall' },
    { href: '/einstieg/welches-fahrrad.html', label: 'Welches Rad passt zum Bikepacking?' },
    { href: '/unterwegs/tagesablauf.html', label: 'Der Tagesablauf auf Tour' },
  ],
});
