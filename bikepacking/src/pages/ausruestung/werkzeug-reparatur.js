'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, weightList, pickGrid, affNotice,
} = require('../../components');
const { shops } = require('../../data/shops');

const content = `
<p class="lead-p">
  Die Regel für das Reparatur-Kit ist einfach: <strong>Nimm nur mit, was du auch anwenden kannst.</strong>
  Ein Kettennieter nützt nichts, wenn du noch nie eine Kette geöffnet hast. Zwölf Teile decken 95
  Prozent aller Pannen ab – vorausgesetzt, du hast sie einmal zu Hause ausprobiert.
</p>

${stats([
  { value: '12', label: 'Teile genügen', note: 'Für praktisch alles, was unterwegs passiert.' },
  { value: '1,3 kg', label: 'Gesamtgewicht', note: 'Inklusive Schloss und zwei Ersatzschläuchen.' },
  { value: '80 %', label: 'Aller Pannen', note: 'Sind Platten. Alles andere ist selten.' },
])}

${h2('Das Standard-Kit', 'kit')}
${weightList({
  title: 'Für Wochenend- bis Wochentouren',
  items: [
    { name: 'Multitool mit Kettennieter', note: 'Inbus 2–8, Torx 25, Schraubendreher', g: 160, tag: 'pflicht' },
    { name: 'Mini-Handpumpe', note: 'Mit passendem Ventilkopf, bis mindestens 6 bar', g: 110, tag: 'pflicht' },
    { name: '2 Ersatzschläuche', note: 'Richtige Größe UND richtiges Ventil', g: 300, tag: 'pflicht' },
    { name: '2 Reifenheber', note: 'Kunststoff, nicht Metall', g: 25, tag: 'pflicht' },
    { name: 'Selbstklebendes Flickzeug', note: 'Für den dritten Platten', g: 35, tag: 'pflicht' },
    { name: 'Kettenschloss', note: 'Passend zur Gangzahl deiner Kette', g: 15, tag: 'pflicht' },
    { name: 'Ersatz-Schaltauge', note: 'Rahmenspezifisch – unterwegs nirgends erhältlich', g: 30, tag: 'pflicht' },
    { name: 'Kabelbinder, 5 Stück', note: 'Halten mehr, als man glaubt', g: 15, tag: 'pflicht' },
    { name: 'Gewebeband', note: 'Ein Meter um die Pumpe gewickelt statt ganzer Rolle', g: 25, tag: 'pflicht' },
    { name: 'Kettenöl, kleine Flasche', note: 'Nach jedem Regentag nötig', g: 50, tag: 'sinnvoll' },
    { name: 'Reifen-Boot für große Schnitte', note: 'Alternativ: Stück einer alten Reifenflanke', g: 20, tag: 'sinnvoll' },
    { name: 'Einweghandschuhe, 1 Paar', note: 'Kettenfett bekommst du sonst nicht ab', g: 10, tag: 'sinnvoll' },
    { name: 'Fahrradschloss', note: 'Faltschloss oder schweres Kabelschloss', g: 500, tag: 'sinnvoll' },
    { name: 'Kompletter Werkzeugkoffer', note: 'Ein gutes Multitool deckt fast alles ab', g: 700, tag: 'ballast' },
    { name: 'Ersatzspeichen', note: 'Nur bei sehr langen oder abgelegenen Touren', g: 60, tag: 'ballast' },
    { name: 'Ersatzreifen', note: 'Ein Boot plus Flickzeug bringt dich zum nächsten Laden', g: 450, tag: 'ballast' },
  ],
})}

${callout(
  'Das Ersatz-Schaltauge',
  '<p>Das Schaltauge ist die Sollbruchstelle zwischen Rahmen und Schaltwerk. Es bricht bei einem harmlosen Umfaller – und ist <strong>rahmenspezifisch</strong>. Kein Fahrradladen unterwegs wird deins vorrätig haben, und ohne Schaltauge lässt sich das Schaltwerk nicht montieren. Ein Ersatzteil kostet 20 bis 40 Euro, wiegt 30 Gramm und ist der Unterschied zwischen einer 20-Minuten-Reparatur und einer abgebrochenen Tour.</p>',
  'warn'
)}

${h2('Was du üben solltest, bevor du losfährst', 'ueben')}
<p>
  Reparieren im Regen an einer Landstraße ist der falsche Zeitpunkt, etwas zum ersten Mal zu machen.
  Diese drei Handgriffe lernst du an einem Nachmittag zu Hause:
</p>

${h3('1. Schlauch wechseln', 'schlauch')}
${checklist([
  'Hinterrad ausbauen – bei Scheibenbremse: <strong>nicht in den Bremshebel greifen</strong>, solange das Rad draußen ist',
  'Reifen mit zwei Hebeln von der Felge lösen, eine Seite genügt',
  'Ursache suchen: Mit dem Finger vorsichtig innen den Reifen abtasten, sonst ist der neue Schlauch sofort wieder platt',
  'Neuen Schlauch leicht aufpumpen, damit er sich nicht faltet, dann einlegen',
  'Reifen mit den Händen aufziehen – <strong>nie mit Hebeln</strong>, das quetscht den Schlauch',
  'Vor dem vollen Aufpumpen prüfen, ob der Schlauch nirgends unter der Reifenwulst klemmt',
])}

${h3('2. Kette öffnen und schließen', 'kette')}
${checklist([
  'Kettenschloss finden (es sieht anders aus als die übrigen Glieder) und mit den Händen oder einer Zange öffnen',
  'Wenn kein Schloss vorhanden: Mit dem Kettennieter am Multitool einen Niet herausdrücken',
  'Beschädigtes Glied entfernen, neues Kettenschloss einsetzen',
  '<strong>Wichtig:</strong> Eine gekürzte Kette darf nicht mehr auf das größte Ritzel plus größtes Blatt – sonst reißt das Schaltwerk ab',
])}

${h3('3. Schaltung grob einstellen', 'schaltung')}
${checklist([
  'Wenn das Schaltwerk nicht mehr sauber schaltet: erst prüfen, ob das Schaltauge verbogen ist',
  'Zughülse und Zug prüfen – gerissene Züge sind eine häufige Ursache',
  'Feineinstellung an der Rändelschraube am Schaltwerk: Kette springt schwer nach oben, dann Zug spannen (Schraube herausdrehen)',
  '<strong>Notfall bei defekter Schaltung:</strong> Kette auf ein mittleres Ritzel legen und die Begrenzungsschrauben so eindrehen, dass sie dort bleibt – so fährst du als Eingang weiter',
])}

${h2('Die häufigsten Pannen und ihre Wahrscheinlichkeit', 'pannen')}
${table({
  head: ['Panne', 'Häufigkeit', 'Selbst reparierbar?', 'Was du brauchst'],
  rows: [
    ['Platter Reifen', '<strong>Sehr häufig</strong>', 'Ja', 'Schlauch, Hebel, Pumpe'],
    ['Kette springt / schaltet schlecht', 'Häufig', 'Ja', 'Multitool, 5 Minuten'],
    ['Bremsbeläge abgefahren', 'Häufig auf langen Touren', 'Ja, mit Ersatzbelägen', 'Ersatzbeläge, Multitool'],
    ['Schraube gelöst', 'Häufig', 'Ja', 'Multitool'],
    ['Kette gerissen', 'Gelegentlich', 'Ja, mit Übung', 'Kettennieter, Kettenschloss'],
    ['Schaltauge verbogen/gebrochen', 'Gelegentlich', 'Ja, mit Ersatzteil', 'Ersatz-Schaltauge'],
    ['Speiche gebrochen', 'Selten', 'Notdürftig', 'Speichenschlüssel oder Werkstatt'],
    ['Schaltzug gerissen', 'Selten', 'Behelfsmäßig', 'Begrenzungsschrauben eindrehen'],
    ['Reifen aufgeschlitzt', 'Selten', 'Ja, mit Boot', 'Reifen-Boot oder Geldschein'],
    ['Felge oder Rahmen gebrochen', 'Sehr selten', 'Nein', 'Bahn, Taxi, Werkstatt'],
  ],
  note: 'Rund 80 Prozent aller Pannen sind Plattfüße. Wer den Schlauchwechsel sicher beherrscht, ist auf fast alles vorbereitet, was tatsächlich passiert.',
})}

${h2('Vor der Tour: der Radcheck', 'radcheck')}
<p>
  Zwanzig Minuten eine Woche vor der Abfahrt – nicht am Vorabend, damit du Ersatzteile noch besorgen
  kannst.
</p>
${table({
  head: ['Prüfen', 'Wie', 'Handlungsbedarf ab'],
  rows: [
    ['Bremsbeläge', 'Sichtprüfung durch den Bremssattel', 'unter 1,5 mm Restbelag'],
    ['Bremsscheiben', 'Dicke messen oder ertasten', 'unter 1,5 mm'],
    ['Kette', 'Kettenlehre oder in der Werkstatt messen lassen', 'ab 0,75 % Längung'],
    ['Reifen', 'Flanken auf Risse prüfen, Profil ansehen', 'Risse oder Gewebe sichtbar'],
    ['Speichen', 'Alle anzupfen – gleicher Ton?', 'eine deutlich lockerer'],
    ['Züge', 'Auf Fransen an den Enden prüfen', 'sichtbare Bruchstellen'],
    ['Schrauben', 'Sattelstütze, Vorbau, Flaschenhalter nachziehen', 'immer vor der Tour'],
    ['Ventile', 'Über Nacht Druck halten?', 'Druckverlust über 1 bar'],
    ['Licht', 'Beide Lampen laden und testen', 'immer'],
  ],
  note: 'Wer nicht selbst schraubt: Ein Werkstattcheck kostet 60 bis 120 Euro und ist vor der ersten längeren Tour gut angelegtes Geld.',
})}

${affNotice()}

${h2('Kaufempfehlungen', 'empfehlungen')}
${pickGrid(
  [
    {
      badge: 'Das Herzstück',
      title: 'Multitool mit Kettennieter',
      forWhom: 'Jede Tour. Ohne Ausnahme.',
      price: 'ca. 30 – 70 €',
      specs: [
        { k: 'Inbus', v: '2, 2,5, 3, 4, 5, 6, 8 mm' },
        { k: 'Torx', v: 'T25 (Bremsscheiben, viele Schrauben)' },
        { k: 'Extra', v: 'Kettennieter, Schraubendreher' },
        { k: 'Gewicht', v: '140 – 190 g' },
      ],
      pros: [
        'Deckt fast alle Schrauben am Rad ab',
        'Kettennieter ist das entscheidende Extra',
        'Hält jahrelang, wenn es kein Billigmodell ist',
      ],
      cons: ['Sehr günstige Modelle runden Schrauben ab – hier lohnt Qualität'],
      partner: 'amazon',
      url: shops.multitool,
      ctaLabel: 'Multitools ansehen',
    },
    {
      badge: 'Nicht CO₂ allein',
      title: 'Mini-Handpumpe mit Schlauch',
      forWhom: 'Alle. CO₂-Kartuschen sind Ergänzung, kein Ersatz.',
      price: 'ca. 25 – 60 €',
      specs: [
        { k: 'Maximaldruck', v: 'mindestens 6 bar, besser 8' },
        { k: 'Ventil', v: 'Presta und Schrader umschaltbar' },
        { k: 'Schlauch', v: 'Kurzer Schlauch schont das Ventil' },
        { k: 'Gewicht', v: '90 – 140 g' },
      ],
      pros: [
        'Funktioniert unbegrenzt oft – im Gegensatz zu CO₂',
        'Schlauchvariante verhindert abgebrochene Ventile',
        'Passt an den Flaschenhalter oder in die Rahmentasche',
      ],
      cons: [
        'Hohe Drücke erfordern viele Hübe',
        'Sehr kleine Pumpen sind mühsam',
      ],
      partner: 'amazon',
      url: shops.minipumpe,
      ctaLabel: 'Pumpen ansehen',
    },
    {
      badge: 'Kompromiss Sicherheit',
      title: 'Faltschloss oder schweres Kabelschloss',
      forWhom: 'Jede Tour, auf der du das Rad aus den Augen lässt.',
      price: 'ca. 40 – 110 €',
      specs: [
        { k: 'Gewicht', v: '450 – 900 g' },
        { k: 'Sicherheitsstufe', v: 'Mittel – hält Gelegenheitsdiebe ab' },
        { k: 'Länge', v: 'mindestens 75 cm, um an Objekte zu schließen' },
        { k: 'Alternative', v: 'Leichtes Kabel plus nie aus den Augen lassen' },
      ],
      pros: [
        'Reicht für Supermarkt, Café und Campingplatz',
        'Faltschlösser lassen sich am Rahmen befestigen',
        'Deutlich leichter als ein Bügelschloss',
      ],
      cons: [
        'Kein wirklicher Schutz gegen entschlossene Diebe',
        'Das Gewicht schleppst du jeden Kilometer mit',
      ],
      partner: 'amazon',
      url: shops.schloss,
      ctaLabel: 'Schlösser ansehen',
      note: 'Auf Tour gilt: Das beste Schloss ist Sichtkontakt. Nimm im Supermarkt die Hüfttasche mit und stell das Rad ans Fenster.',
    },
  ],
  { columns: 3 }
)}

${h2('Wenn du nicht weiterkommst', 'notfall')}
${checklist([
  '<strong>Fahrradläden</strong> findest du über die Offline-Karte. In kleinen Orten hilft oft auch die Landmaschinenwerkstatt.',
  '<strong>Die Bahn nimmt dein Rad mit</strong> – im Nahverkehr meist ohne Reservierung. Das ist der zuverlässigste Notausstieg in Deutschland.',
  '<strong>ADFC-Pannenhilfe</strong> gibt es für Mitglieder, ähnlich wie ein Autoschutzbrief.',
  '<strong>Manche Versicherungen und Kreditkarten</strong> enthalten Fahrrad-Pannenhilfe – vorher prüfen, kostet nichts.',
  '<strong>Ein Taxi mit Rad</strong> ist teuer, aber am Sonntagabend mitten im Nirgendwo oft die einzige Option.',
  '<strong>Nicht in der Dämmerung mit defektem Rad weiterfahren.</strong> Lieber eine Nacht früher aufhören.',
])}
`;

module.exports = article({
  href: '/ausruestung/werkzeug-reparatur.html',
  kicker: 'Ausrüstung · Werkzeug',
  title: 'Werkzeug & Reparatur-Kit',
  metaTitle: 'Bikepacking Werkzeug: Das 12-teilige Reparatur-Kit | Sattelfest',
  description:
    'Das Bikepacking-Reparatur-Kit: zwölf Teile für 95 Prozent aller Pannen, mit Gewichten. Plus die drei Handgriffe, die du vorher üben solltest, die Pannen-Wahrscheinlichkeiten und der Radcheck vor der Tour.',
  lead:
    'Die Regel lautet: Nimm nur mit, was du auch anwenden kannst. Zwölf Teile decken fast alles ab, was tatsächlich passiert.',
  meta: [
    { icon: 'tool', text: '10 Minuten Lesezeit' },
    { icon: 'weight', text: 'Mit Gewichtsliste' },
    { icon: 'check', text: 'Inklusive Radcheck' },
  ],
  toc: [
    { label: 'Das Standard-Kit', id: 'kit' },
    { label: 'Was du üben solltest', id: 'ueben' },
    { label: 'Die häufigsten Pannen', id: 'pannen' },
    { label: 'Vor der Tour: der Radcheck', id: 'radcheck' },
    { label: 'Kaufempfehlungen', id: 'empfehlungen' },
    { label: 'Wenn du nicht weiterkommst', id: 'notfall' },
  ],
  content,
  faq: [
    {
      q: 'Welches Werkzeug brauche ich beim Bikepacking?',
      a: '<p>Zwölf Teile decken 95 Prozent aller Pannen ab: Multitool mit Kettennieter, Handpumpe, zwei Ersatzschläuche, zwei Reifenheber, Flickzeug, Kettenschloss, Ersatz-Schaltauge, Kabelbinder, Gewebeband, Kettenöl und ein Reifen-Boot. Zusammen etwa 800 Gramm ohne Schloss. Nimm nur mit, was du auch anwenden kannst.</p>',
    },
    {
      q: 'Warum brauche ich ein Ersatz-Schaltauge?',
      a: '<p>Das Schaltauge ist die Sollbruchstelle zwischen Rahmen und Schaltwerk und bricht schon bei einem harmlosen Umfaller. Es ist rahmenspezifisch – kein Fahrradladen unterwegs hat deins vorrätig, und ohne Schaltauge lässt sich das Schaltwerk nicht montieren. 20 bis 40 Euro und 30 Gramm für den Unterschied zwischen 20 Minuten Reparatur und abgebrochener Tour.</p>',
    },
    {
      q: 'Reichen CO₂-Kartuschen statt einer Pumpe?',
      a: '<p>Nein. Nach zwei oder drei Kartuschen ist Schluss, und du brauchst genau dann Luft, wenn du keine mehr hast. Eine Handpumpe funktioniert unbegrenzt oft. CO₂ ist eine sinnvolle Ergänzung, wenn es schnell gehen soll – als alleinige Lösung auf einer Mehrtagestour ist es fahrlässig.</p>',
    },
    {
      q: 'Was ist die häufigste Panne beim Bikepacking?',
      a: '<p>Der Plattfuß – rund 80 Prozent aller Pannen. Wer den Schlauchwechsel sicher beherrscht, ist auf fast alles vorbereitet, was tatsächlich passiert. Danach folgen Schaltprobleme, abgefahrene Bremsbeläge und gelöste Schrauben. Kettenriss, gebrochene Speiche und gerissener Zug sind deutlich seltener.</p>',
    },
    {
      q: 'Was mache ich, wenn ich die Panne nicht selbst beheben kann?',
      a: '<p>Der zuverlässigste Notausstieg in Deutschland ist die Bahn: Im Nahverkehr nimmst du das Rad meist ohne Reservierung mit. Fahrradläden findest du über die Offline-Karte, in kleinen Orten hilft oft auch eine Landmaschinenwerkstatt. ADFC-Mitglieder haben Pannenhilfe, manche Versicherungen und Kreditkarten ebenfalls.</p>',
    },
  ],
  related: [
    { href: '/unterwegs/panne-beheben.html', label: 'Panne unterwegs beheben' },
    { href: '/ausruestung/packliste.html', label: 'Die Packliste mit Gewichten' },
    { href: '/einstieg/welches-fahrrad.html', label: 'Welches Rad passt zum Bikepacking?' },
    { href: '/unterwegs/sicherheit-notfall.html', label: 'Sicherheit & Notfall' },
  ],
});
