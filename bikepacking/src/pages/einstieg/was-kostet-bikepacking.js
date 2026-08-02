'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats,
} = require('../../components');

const content = `
<p class="lead-p">
  Bikepacking gilt als günstiges Hobby, weil das Übernachten wenig kostet. Das stimmt – aber der
  Einstieg kostet trotzdem Geld, und wer die Rechnung nicht kennt, kauft in der falschen Reihenfolge.
  Hier stehen drei vollständig durchgerechnete Varianten und die Posten, die in Videos nie vorkommen.
</p>

${stats([
  { value: '0 €', label: 'Wenn du leihst', note: 'Schlafsack, Isomatte und Taschen leihen ist völlig legitim.' },
  { value: '380 €', label: 'Sparsam ausgestattet', note: 'Gebraucht gekauft, mit vorhandenem Rad.' },
  { value: '1.950 €', label: 'Komplett neu', note: 'Ohne Rad, mit gutem Zelt und vollem Taschenset.' },
])}

${h2('Die drei Varianten im Vergleich', 'varianten')}
${table({
  head: ['Posten', 'Sparsam', 'Solide', 'Komfortabel'],
  rows: [
    ['Satteltasche', '45 € gebraucht', '110 €', '190 €'],
    ['Lenkerrolle', '40 € gebraucht', '95 €', '170 €'],
    ['Rahmentasche', '– (später)', '70 €', '130 €'],
    ['Oberrohrtasche', '15 €', '35 €', '55 €'],
    ['Gabeltaschen (Paar)', '–', '–', '150 €'],
    ['Zelt oder Tarp', '60 € Tarp', '190 € 1P-Zelt', '450 € Ultraleicht-Zelt'],
    ['Schlafsack', '55 € Kunstfaser', '150 € Daune', '340 € Daune 800 cuin'],
    ['Isomatte', '35 € Schaumstoff', '90 € aufblasbar', '190 € R-Wert 4,5'],
    ['Kocher-Set', '25 € Spiritus', '65 € Gas', '130 € Titan'],
    ['Licht vorn/hinten', '35 €', '75 €', '140 €'],
    ['Powerbank', '20 €', '35 €', '60 €'],
    ['Werkzeug & Ersatzteile', '35 €', '60 €', '95 €'],
    ['Regenjacke', '– (vorhanden)', '110 €', '260 €'],
    ['<strong>Summe Ausrüstung</strong>', '<strong>365 €</strong>', '<strong>1.085 €</strong>', '<strong>2.360 €</strong>'],
  ],
  note: 'Ohne Rad gerechnet. Preise sind Rahmenwerte für den deutschen Markt und ändern sich – maßgeblich ist der Shop zum Zeitpunkt des Kaufs.',
})}

${callout(
  'Die Spalte, die niemand ausrechnet',
  '<p>In der Sparsam-Spalte fehlt nichts Wesentliches. Sie enthält keinen Kompromiss bei der Sicherheit: Licht, Werkzeug und Regenschutz sind vollständig. Was fehlt, ist Komfort – ein Tarp statt Zelt, eine Schaumstoffmatte statt Luftmatte, mehr Gewicht. Das sind Dinge, die eine Tour unbequemer machen, nicht unmöglich.</p>',
  'money'
)}

${h2('Die laufenden Kosten pro Tour', 'laufend')}
<p>
  Der eigentliche Unterschied zu anderen Reiseformen liegt hier. Eine Bikepacking-Nacht kostet
  weniger als ein Kinobesuch.
</p>
${table({
  head: ['Posten', 'Pro Nacht', 'Anmerkung'],
  rows: [
    ['Trekkingplatz', '0 – 15 €', 'Je nach Bundesland, meist Onlinebuchung nötig'],
    ['Campingplatz mit Zelt', '10 – 25 €', 'Mit Dusche und Strom, oft Rabatt für Radreisende'],
    ['Jugendherberge / Hostel', '25 – 45 €', 'Ohne Zelt und Schlafsack unterwegs'],
    ['Pension / Gasthof', '50 – 90 €', 'Credit-Card-Bikepacking'],
    ['Verpflegung selbst gekocht', '8 – 14 €', 'Supermarkt, zwei Mahlzeiten plus Snacks'],
    ['Verpflegung Gaststätte', '25 – 40 €', 'Eine warme Mahlzeit plus Frühstück unterwegs'],
    ['Bahnanreise mit Rad', '15 – 40 €', 'Fahrradkarte plus Ticket, Reservierung im Fernverkehr'],
  ],
  note: 'Ein Wochenende mit einer Nacht auf dem Campingplatz und Selbstverpflegung kostet realistisch 30 bis 50 Euro.',
})}

${h2('Die Posten, die niemand einplant', 'versteckt')}
${checklist(
  [
    '<strong>Bahnfahrkarte mit Rad zurück.</strong> Der klassische Rückweg. Im Nahverkehr 6 bis 12 Euro, im Fernverkehr mit Stellplatzreservierung schnell 40 Euro – und die Plätze sind im Sommer Wochen vorher weg.',
    '<strong>Kleinteile.</strong> Packsäcke, Kabelbinder, Ersatzschläuche, Kartuschen, Riegel: 40 bis 80 Euro, die sich in kleinen Beträgen unbemerkt summieren.',
    '<strong>Der zweite Kauf.</strong> Rund die Hälfte aller Einsteiger kauft die Satteltasche innerhalb eines Jahres ein zweites Mal – zu klein, zu wackelig oder nicht wasserdicht. Das ist der teuerste Posten überhaupt.',
    '<strong>Ein Sattel, der passt.</strong> 60 bis 130 Euro, oft erst nach der ersten Tour fällig. Aber der Posten, der über Freude oder Qual entscheidet.',
    '<strong>Radservice vor der Saison.</strong> 60 bis 120 Euro, wenn du nicht selbst schraubst. Einmal im Jahr sinnvoll.',
    '<strong>Ersatzakku oder Powerbank Nummer zwei.</strong> Spätestens ab drei Nächten ohne Steckdose.',
  ],
  { tone: 'dont' }
)}

${h2('Wo Sparen sinnvoll ist – und wo nicht', 'sparen')}
${h3('Hier lohnt sich Sparen', 'sparen-ja')}
${checklist([
  '<strong>Taschen gebraucht kaufen.</strong> Bikepacking-Taschen halten sehr lange und werden häufig verkauft, wenn jemand das Setup wechselt. 40 bis 60 Prozent Ersparnis sind normal.',
  '<strong>Kocher.</strong> Ein Spirituskocher für 25 Euro kocht Wasser genauso heiß wie ein Titan-System für 130 Euro – nur langsamer.',
  '<strong>Isomatte, wenn du im Sommer fährst.</strong> Eine Schaumstoffmatte für 35 Euro ist unbequemer, aber unkaputtbar und schnell.',
  '<strong>Kleidung.</strong> Merino-Shirt statt Marken-Trikot, vorhandene Regenjacke statt neuer Membranjacke.',
  '<strong>Zelt für die ersten Touren.</strong> Ein 2-Kilo-Zelt für 190 Euro tut genau das, was ein 450-Euro-Zelt tut – nur ein Kilo schwerer.',
])}

${h3('Hier kostet Sparen dich die Tour', 'sparen-nein')}
${checklist(
  [
    '<strong>Regenjacke.</strong> Eine Jacke, die nach zwei Stunden durchweicht, macht bei 12 Grad aus einer Tour ein Sicherheitsproblem.',
    '<strong>Isomatte im Frühjahr und Herbst.</strong> Der R-Wert entscheidet über die Nacht, nicht der Schlafsack. Unter R 2,5 wird es unter 10 Grad Bodentemperatur unangenehm.',
    '<strong>Licht.</strong> Ein 8-Euro-Blinklicht ist im Dunkeln auf einer Landstraße gefährlich. 60 bis 80 Euro für ordentliches Licht sind gut investiert.',
    '<strong>Reifen.</strong> Billige Reifen ohne Pannenschutz kosten dich unterwegs mehr Zeit und Nerven, als sie beim Kauf sparen.',
    '<strong>Befestigung der Satteltasche.</strong> Eine Tasche, die pendelt, macht jede Abfahrt unangenehm und scheuert Sattelstütze und Reifen an.',
  ],
  { tone: 'dont' }
)}

${h2('Die günstigste Variante, die trotzdem funktioniert', 'guenstigste')}
<p>
  Wenn du diesen Sommer mit möglichst wenig Geld eine Nacht draußen schlafen willst, sieht die Liste
  so aus:
</p>
${table({
  head: ['Was', 'Woher', 'Kosten'],
  rows: [
    ['Rad', 'Deins', '0 €'],
    ['Gepäck ans Rad', 'Alter Gepäckträger + gebrauchte Packtaschen', '35 – 70 €'],
    ['Schlafsack', 'Geliehen oder gebraucht', '0 – 45 €'],
    ['Isomatte', 'Schaumstoff, Baumarkt oder Outdoorladen', '20 – 35 €'],
    ['Unterkunft', 'Campingplatz', '12 – 20 €'],
    ['Regenjacke', 'Vorhanden', '0 €'],
    ['Licht', 'Vorhanden oder Basisset', '0 – 35 €'],
    ['Verpflegung', 'Supermarkt', '12 – 18 €'],
    ['<strong>Summe erste Tour</strong>', '', '<strong>79 – 223 €</strong>'],
  ],
  note: 'Kein Zelt, kein Kocher, keine Bikepacking-Taschen. Und trotzdem eine vollwertige Übernachtungstour.',
})}

${callout(
  'Die richtige Kaufreihenfolge',
  '<p>1. Nichts. Erste Tour mit geliehenem und vorhandenem Zeug. 2. Satteltasche und Lenkerrolle. 3. Schlafsystem, das zu deiner Jahreszeit passt. 4. Rahmentasche. 5. Alles andere. Wer diese Reihenfolge einhält, gibt insgesamt etwa ein Drittel weniger aus als jemand, der vorab das komplette Set kauft – weil er nur das kauft, was ihm tatsächlich gefehlt hat.</p>',
  'tip'
)}

${h2('Was du im ersten Jahr insgesamt ausgibst', 'jahr-eins')}
<p>
  Realistisch für jemanden, der ein Rad hat, im ersten Jahr vier bis sechs Touren fährt und
  schrittweise ausstattet:
</p>
${table({
  head: ['Variante', 'Ausrüstung', 'Touren', 'Gesamt Jahr 1'],
  rows: [
    ['Vorsichtig', '365 €', '4 × 45 €', '<strong>545 €</strong>'],
    ['Normal', '1.085 €', '6 × 60 €', '<strong>1.445 €</strong>'],
    ['Ausgiebig', '2.360 €', '8 × 90 €', '<strong>3.080 €</strong>'],
  ],
  note: 'Ab dem zweiten Jahr fallen fast nur noch die Tourkosten an. Bikepacking-Ausrüstung hält bei normaler Nutzung fünf bis zehn Jahre.',
})}
`;

module.exports = article({
  href: '/einstieg/was-kostet-bikepacking.html',
  kicker: 'Einstieg · Kosten',
  title: 'Was Bikepacking wirklich kostet',
  metaTitle: 'Was kostet Bikepacking? Drei Budgets komplett durchgerechnet | Sattelfest',
  description:
    'Bikepacking-Kosten ehrlich aufgeschlüsselt: drei Ausstattungsvarianten von 365 bis 2.360 Euro, laufende Kosten pro Nacht, versteckte Posten und wo Sparen sinnvoll ist – und wo nicht.',
  lead:
    'Drei Varianten vollständig durchgerechnet, die laufenden Kosten pro Nacht und die Posten, die in keinem Video vorkommen.',
  meta: [
    { icon: 'wallet', text: '10 Minuten Lesezeit' },
    { icon: 'check', text: 'Mit Kaufreihenfolge' },
    { icon: 'trend', text: 'Jahr-1-Rechnung' },
  ],
  toc: [
    { label: 'Die drei Varianten im Vergleich', id: 'varianten' },
    { label: 'Die laufenden Kosten pro Tour', id: 'laufend' },
    { label: 'Die Posten, die niemand einplant', id: 'versteckt' },
    { label: 'Wo Sparen sinnvoll ist – und wo nicht', id: 'sparen' },
    { label: 'Die günstigste Variante, die funktioniert', id: 'guenstigste' },
    { label: 'Was du im ersten Jahr ausgibst', id: 'jahr-eins' },
  ],
  content,
  faq: [
    {
      q: 'Was kostet ein Bikepacking-Taschenset?',
      a: '<p>Ein neues Set aus Satteltasche, Lenkerrolle, Rahmentasche und Oberrohrtasche kostet je nach Marke 215 bis 800 Euro. Günstige Sets von Topeak, Zéfal oder AGU liegen im unteren Bereich, Ortlieb und Restrap in der Mitte, Apidura, Cyclite und Tailfin oben. Gebraucht sparst du 40 bis 60 Prozent – Bikepacking-Taschen halten sehr lange und werden häufig weiterverkauft.</p>',
    },
    {
      q: 'Was kostet eine Bikepacking-Nacht?',
      a: '<p>Ein Trekkingplatz kostet 0 bis 15 Euro, ein Campingplatz mit Zelt 10 bis 25 Euro, eine Jugendherberge 25 bis 45 Euro. Rechne 8 bis 14 Euro für selbst gekochte Verpflegung dazu. Ein Wochenende mit einer Nacht auf dem Campingplatz kommt damit auf 30 bis 50 Euro.</p>',
    },
    {
      q: 'Kann ich Bikepacking ohne teure Ausrüstung machen?',
      a: '<p>Ja. Mit vorhandenem Rad, geliehenem Schlafsack, einer Schaumstoffmatte und einem alten Gepäckträger mit gebrauchten Packtaschen liegst du bei 79 bis 223 Euro für die erste komplette Tour. Kein Zelt, kein Kocher und keine Bikepacking-Taschen nötig – es bleibt trotzdem eine vollwertige Übernachtungstour.</p>',
    },
    {
      q: 'Wo sollte ich beim Bikepacking nicht sparen?',
      a: '<p>Bei der Regenjacke, der Isomatte (außerhalb des Hochsommers), beim Licht, bei den Reifen und bei der Befestigung der Satteltasche. Das sind die Punkte, an denen billige Lösungen nicht nur unbequem sind, sondern eine Tour tatsächlich abbrechen oder gefährlich machen können.</p>',
    },
  ],
  related: [
    { href: '/taschen/taschensystem.html', label: 'Das Taschensystem verstehen' },
    { href: '/ausruestung/packliste.html', label: 'Die Packliste mit Gewichten' },
    { href: '/routen/uebernachten.html', label: 'Übernachten: Wo du legal schläfst' },
    { href: '/einstieg/welches-fahrrad.html', label: 'Welches Rad passt zum Bikepacking?' },
  ],
});
