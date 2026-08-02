'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, doDont,
} = require('../../components');

const content = `
<p class="lead-p">
  Wildcampen ist in Deutschland grundsätzlich verboten – daran führt kein Weg vorbei. Die gute
  Nachricht: In den vergangenen Jahren ist ein Netz von über 200 offiziellen Trekking- und
  Biwakplätzen entstanden, die genau für Radfahrende und Wandernde eingerichtet wurden. Dazu kommen
  Campingplätze, Naturlagerplätze und eine Reihe legaler Zwischenformen.
</p>

${stats([
  { value: '200+', label: 'Trekkingplätze', note: 'In Deutschland, Tendenz steigend.' },
  { value: '0–15 €', label: 'Pro Nacht', note: 'Je nach Bundesland und Betreiber.' },
  { value: 'Mai–Okt.', label: 'Typische Saison', note: 'Viele Plätze sind im Winter geschlossen.' },
])}

${h2('Die Optionen im Überblick', 'optionen')}
${table({
  head: ['Option', 'Legalität', 'Kosten', 'Komfort', 'Buchung'],
  rows: [
    [
      '<strong>Trekkingplatz</strong>',
      '<strong>Legal, ausdrücklich dafür da</strong>',
      '0 – 15 €',
      'Sehr einfach: Stellfläche, oft Trockenklo',
      'Meist online, teils Pflicht',
    ],
    [
      '<strong>Campingplatz</strong>',
      '<strong>Legal</strong>',
      '10 – 25 €',
      'Dusche, Strom, oft Kiosk',
      'In der Hauptsaison empfehlenswert',
    ],
    [
      'Naturlagerplatz / Biwakplatz',
      '<strong>Legal</strong>',
      '0 – 10 €',
      'Sehr einfach, teils nur Wiese',
      'Regional unterschiedlich',
    ],
    [
      'Jugendherberge / Hostel',
      '<strong>Legal</strong>',
      '25 – 45 €',
      'Bett, Dusche, Frühstück',
      'Empfehlenswert',
    ],
    [
      'Bauernhof / Privatgrundstück mit Erlaubnis',
      '<strong>Legal mit Zustimmung</strong>',
      '0 – 15 €',
      'Sehr unterschiedlich',
      'Direkt fragen',
    ],
    [
      'Schutzhütte am Wanderweg',
      'Grauzone – oft geduldet, selten erlaubt',
      '0 €',
      'Dach, Bank, sonst nichts',
      'Nicht möglich',
    ],
    [
      'Wildcampen im Wald',
      '<strong>Verboten</strong>',
      'Bußgeld 20 – 2.500 €',
      '–',
      '–',
    ],
  ],
  note: 'Die Bußgelder für Wildcampen variieren stark nach Bundesland und Schutzstatus des Gebiets. In Naturschutzgebieten und Nationalparks liegen sie deutlich höher.',
})}

${h2('Trekkingplätze: die eigentliche Lösung', 'trekkingplaetze')}
<p>
  Ein Trekkingplatz ist eine kleine, offiziell ausgewiesene Fläche im Wald – meist mit zwei bis fünf
  Zeltstellplätzen, oft mit einer Feuerstelle, einem Trockenklo und einer Bank. Kein Strom, kein
  Wasser, keine Dusche. Erreichbar nur zu Fuß oder mit dem Rad.
</p>

${h3('So funktioniert es', 'funktion')}
${checklist([
  '<strong>Online buchen</strong> – auf den Portalen der jeweiligen Bundesländer oder Naturparks, meist bis zum Anreisetag möglich',
  '<strong>Gebühr</strong> zwischen 0 und 15 Euro pro Zelt und Nacht, je nach Betreiber',
  '<strong>Anreise nur zu Fuß oder mit dem Rad</strong> – das ist Teil des Konzepts',
  '<strong>Ankunft frühestens am Nachmittag,</strong> Abreise meist bis 10 oder 11 Uhr',
  '<strong>Eine Nacht,</strong> selten zwei – die Plätze sind als Durchgangsstationen gedacht',
  '<strong>Kein Wasser vor Ort.</strong> Du musst deinen Bedarf vorher auffüllen – rechne mit 3 bis 4 Litern',
])}

${h3('Wo du sie findest', 'finden')}
${table({
  head: ['Region', 'Angebot', 'Anmerkung'],
  rows: [
    ['Baden-Württemberg', 'Schwarzwald: dichtes Netz an Trekkingcamps', 'Eines der ältesten und besten Angebote'],
    ['Rheinland-Pfalz', 'Pfälzerwald und Hunsrück gut ausgestattet', 'Teilweise kostenlos'],
    ['Hessen', 'Zunehmend Trekkingplätze in den Naturparks', 'Ausbau in den letzten Jahren'],
    ['Nordrhein-Westfalen', 'Eifel, Sauerland, Teutoburger Wald', 'Wachsendes Angebot'],
    ['Niedersachsen', 'Harz und Heide mit Angeboten', 'Regional sehr unterschiedlich'],
    ['Mecklenburg-Vorpommern', 'Naturlagerplätze an Seen und Flüssen', 'Auch für Kanuten gedacht'],
    ['Brandenburg', 'Biwakplätze, teils an Wasserwanderwegen', 'Gut erreichbar aus Berlin'],
    ['Bayern', '<strong>Sehr wenig</strong>', 'Strengstes Naturschutzrecht Deutschlands'],
  ],
  note: 'Übergreifende Verzeichnisse führen die Plätze bundesweit und lassen sich nach Bundesland filtern. Prüfe vor der Buchung immer Saison, Anreisezeiten und Platzordnung.',
})}

${callout(
  'Vor der Buchung prüfen',
  '<p>Drei Dinge, die auf Trekkingplätzen regelmäßig übersehen werden: <strong>1.</strong> Es gibt kein Trinkwasser – fülle vorher auf. <strong>2.</strong> Auf vielen Plätzen ist Feuer verboten, teilweise auch Gaskocher. <strong>3.</strong> Die Saison endet oft schon Ende Oktober. Alles drei steht in der jeweiligen Platzordnung, und alles drei kann eine Nacht unangenehm machen, wenn man es erst vor Ort merkt.</p>',
  'warn'
)}

${h2('Die Rechtslage beim Wildcampen', 'recht')}
<p>
  Die Regeln unterscheiden sich nach Bundesland, Waldgesetz und Schutzstatus des jeweiligen Gebiets –
  und sie ändern sich. Die Grundlinie ist aber überall dieselbe:
</p>

${table({
  head: ['Handlung', 'Rechtliche Einordnung', 'Praxis'],
  rows: [
    ['Zelt im Wald aufstellen', '<strong>In allen Bundesländern verboten</strong>', 'Bußgeld möglich'],
    ['Zelt auf privater Wiese ohne Erlaubnis', '<strong>Verboten</strong> (Hausfriedensbruch möglich)', 'Bußgeld möglich'],
    ['Zelt auf privater Wiese mit Erlaubnis', '<strong>Erlaubt</strong>', 'Einfach fragen – oft ein Ja'],
    ['Biwakieren ohne Zelt (nur Schlafsack)', 'Regional unterschiedlich, oft geduldet', 'Grauzone, kein Rechtsanspruch'],
    ['Übernachten in Naturschutzgebieten', '<strong>Immer verboten</strong>, hohe Bußgelder', 'Konsequent kontrolliert'],
    ['Nationalparks', '<strong>Verboten</strong> außer auf ausgewiesenen Plätzen', 'Konsequent kontrolliert'],
    ['Feuer machen im Wald', '<strong>Ganzjährig verboten</strong>', 'Auch Holzkocher mit offener Flamme'],
    ['Im Auto oder Wohnmobil schlafen', 'Einmalig zur Wiederherstellung der Fahrtüchtigkeit erlaubt', 'Gilt nicht fürs Zelt'],
  ],
  note: 'Bayern hat das strengste Naturschutzrecht: Dort ist das Zelten in der freien Natur flächendeckend untersagt. In anderen Ländern gibt es Ausnahmen für ausgewiesene Flächen.',
})}

${callout(
  'Warum wir nicht zum Wildcampen raten',
  '<p>Nicht aus Prinzipienreiterei, sondern aus drei praktischen Gründen: Es ist verboten und kann teuer werden. Es schadet dem Ruf von Radreisenden bei Waldbesitzern und Gemeinden, die gerade dabei sind, Trekkingplätze einzurichten. Und es ist schlicht unnötig – für null bis fünfzehn Euro bekommst du einen legalen Platz, auf dem du ruhig schläfst, statt bei jedem Geräusch aufzuwachen.</p>',
  'info'
)}

${h2('Wenn du doch draußen schlafen musst', 'notfall')}
<p>
  Es gibt Situationen, in denen man abends nirgendwo unterkommt: Panne, Wetterumschwung, ein
  Campingplatz, der doch geschlossen hat. Für diesen Fall gilt der Grundsatz „Leave No Trace“:
</p>
${doDont({
  doTitle: 'Wenn es sich nicht vermeiden lässt',
  doItems: [
    '<strong>Frag jemanden.</strong> Ein Bauernhof, ein Sportverein, eine Gemeinde – ein höfliches Fragen bringt erstaunlich oft ein Ja',
    '<strong>Erst in der Dämmerung aufbauen, vor Sonnenaufgang abbauen</strong>',
    '<strong>Abseits von Wegen, außer Sicht, ein einzelnes Zelt</strong>',
    '<strong>Kein Feuer, kein Lärm, kein Licht nach außen</strong>',
    '<strong>Nichts zurücklassen</strong> – auch keine Bananenschale und keine plattgedrückte Wiese',
    '<strong>Bei Ansprache freundlich bleiben und gehen</strong>, wenn man dich bittet',
  ],
  dontTitle: 'Was gar nicht geht',
  dontItems: [
    'Naturschutzgebiete, Nationalparks und Wasserschutzgebiete',
    'Mehrere Nächte am selben Ort',
    'Gruppen mit mehreren Zelten',
    'Feuer – ganzjährig und ausnahmslos',
    'Müll, Toilettenpapier oder Essensreste hinterlassen',
    'Auf frisch bestellten Feldern oder Wiesen kurz vor der Mahd',
  ],
})}

${h2('Campingplätze für Radreisende', 'camping')}
${checklist([
  '<strong>Frag nach dem Radfahrer-Tarif.</strong> Viele Plätze haben günstige Preise für Anreisende ohne Fahrzeug – oft die Hälfte des normalen Zeltpreises.',
  '<strong>Campingplätze weisen selten ab.</strong> Für ein einzelnes kleines Zelt findet sich fast immer eine Ecke, auch wenn „ausgebucht“ steht.',
  '<strong>Anreise vor 20 Uhr</strong> – viele Rezeptionen schließen dann, und die Schranke ist zu.',
  '<strong>Waschmaschinen</strong> gibt es auf vielen Plätzen. Nach vier Tagen ist das mehr wert als die Dusche.',
  '<strong>Steckdosen im Sanitärbereich</strong> zum Laden – manchmal gegen kleine Gebühr.',
  '<strong>Plätze an Fernradwegen</strong> sind auf Radreisende eingestellt und haben oft überdachte Bereiche.',
])}

${h2('Übernachten im europäischen Ausland', 'europa')}
${table({
  head: ['Land', 'Wildcampen', 'Anmerkung'],
  rows: [
    ['Schweden, Norwegen, Finnland', '<strong>Weitgehend erlaubt</strong>', 'Jedermannsrecht: eine Nacht, Abstand zu Häusern'],
    ['Schottland', '<strong>Weitgehend erlaubt</strong>', 'Scottish Outdoor Access Code'],
    ['Estland, Lettland, Litauen', 'Weitgehend erlaubt', 'Vielerorts offizielle Lagerplätze'],
    ['Dänemark', 'Verboten, aber Shelter-Netz', 'Hervorragendes Netz kostenloser Shelter'],
    ['Niederlande, Belgien', 'Verboten', 'Dichtes Netz kleiner Naturcampingplätze'],
    ['Frankreich', 'Verboten, oft geduldet', '„Camping sauvage“ regional unterschiedlich geregelt'],
    ['Österreich, Schweiz', 'Verboten, im Alpinbereich Ausnahmen', 'Über der Baumgrenze teils geduldet'],
    ['Italien, Spanien', 'Verboten, teils hohe Bußgelder', 'Vor allem an Küsten streng kontrolliert'],
    ['Polen, Tschechien', 'Verboten, teils ausgewiesene Plätze', 'In Polen Waldcamping-Programm mit Freiflächen'],
  ],
  note: 'Prüfe die Regeln immer aktuell und regional – sie ändern sich, und in Schutzgebieten gelten überall strengere Vorschriften.',
})}
`;

module.exports = article({
  href: '/routen/uebernachten.html',
  kicker: 'Routen · Übernachten',
  title: 'Übernachten: Wo du legal schläfst',
  metaTitle: 'Bikepacking übernachten: Trekkingplätze, Camping & Wildcampen-Recht | Sattelfest',
  description:
    'Wo du beim Bikepacking in Deutschland legal übernachtest: über 200 Trekkingplätze, Campingplätze, Naturlagerplätze – plus die Rechtslage zum Wildcampen nach Bundesland und die Regeln im europäischen Ausland.',
  lead:
    'Wildcampen ist in Deutschland verboten. Dafür gibt es über 200 offizielle Trekkingplätze, die genau für Radfahrende eingerichtet wurden.',
  meta: [
    { icon: 'tent', text: '10 Minuten Lesezeit' },
    { icon: 'shield', text: 'Mit Rechtslage' },
    { icon: 'map', text: 'Inklusive Europa-Übersicht' },
  ],
  toc: [
    { label: 'Die Optionen im Überblick', id: 'optionen' },
    { label: 'Trekkingplätze: die eigentliche Lösung', id: 'trekkingplaetze' },
    { label: 'Die Rechtslage beim Wildcampen', id: 'recht' },
    { label: 'Wenn du doch draußen schlafen musst', id: 'notfall' },
    { label: 'Campingplätze für Radreisende', id: 'camping' },
    { label: 'Übernachten im Ausland', id: 'europa' },
  ],
  content,
  faq: [
    {
      q: 'Ist Wildcampen in Deutschland erlaubt?',
      a: '<p>Nein. Das Aufstellen eines Zelts in Wald und freier Natur ist in allen Bundesländern verboten oder stark eingeschränkt, in Bayern besonders streng. In Naturschutzgebieten und Nationalparks gilt es ausnahmslos und wird konsequent kontrolliert. Die legale Alternative sind über 200 ausgewiesene Trekking- und Biwakplätze.</p>',
    },
    {
      q: 'Was ist ein Trekkingplatz und was kostet er?',
      a: '<p>Ein Trekkingplatz ist eine kleine, offiziell ausgewiesene Fläche im Wald mit meist zwei bis fünf Zeltstellplätzen, oft mit Trockenklo und Bank, aber ohne Strom, Wasser und Dusche. Erreichbar nur zu Fuß oder mit dem Rad. Die Gebühr liegt je nach Betreiber zwischen 0 und 15 Euro pro Zelt und Nacht, gebucht wird meist online.</p>',
    },
    {
      q: 'Gibt es auf Trekkingplätzen Trinkwasser?',
      a: '<p>In der Regel nicht. Das ist der Punkt, der am häufigsten übersehen wird. Fülle vor der Anreise auf – rechne mit 3 bis 4 Litern für Abendessen, Trinken und Frühstück. Ebenfalls wichtig: Auf vielen Plätzen ist Feuer verboten, teilweise sogar der Gaskocher. Beides steht in der Platzordnung.</p>',
    },
    {
      q: 'In welchen Ländern darf ich wild campen?',
      a: '<p>In Schweden, Norwegen und Finnland weitgehend durch das Jedermannsrecht (eine Nacht, Abstand zu Häusern), in Schottland über den Outdoor Access Code, in den baltischen Staaten weitgehend. Dänemark verbietet es, hat aber ein hervorragendes Netz kostenloser Shelter. In Mittel- und Südeuropa ist es überall verboten, teils mit hohen Bußgeldern.</p>',
    },
    {
      q: 'Bekomme ich auf einem Campingplatz spontan noch einen Platz?',
      a: '<p>Fast immer. Für ein einzelnes kleines Zelt findet sich in der Regel eine Ecke, auch wenn der Platz „ausgebucht“ ist – Radreisende brauchen wenig Fläche. Wichtig ist die Ankunft vor 20 Uhr, weil viele Rezeptionen dann schließen. Frag nach dem Radfahrer-Tarif: Viele Plätze berechnen ohne Fahrzeug etwa die Hälfte.</p>',
    },
  ],
  related: [
    { href: '/routen/erstes-mikroabenteuer.html', label: 'Das erste Mikroabenteuer (S24O)' },
    { href: '/ausruestung/schlafsystem.html', label: 'Zelt, Tarp oder Biwaksack?' },
    { href: '/routen/wasser-verpflegung.html', label: 'Wasser & Verpflegung unterwegs' },
    { href: '/unterwegs/sicherheit-notfall.html', label: 'Sicherheit & Notfall' },
  ],
});
