'use strict';

const { pageHero, section, h2, callout, checklist, doDont, table } = require('../components');
const { affiliates } = require('../config');

const content = `
<p class="lead-p">
  Sattelfest ist kostenlos und finanziert sich über Partnerlinks. Diese Seite erklärt vollständig, was
  das bedeutet, woran du solche Links erkennst, was sie dich kosten – nämlich nichts – und welche
  Regeln wir uns für Empfehlungen gegeben haben.
</p>

${callout('Die Kurzfassung', `<p>${affiliates.disclosureLong}</p>`, 'info')}

${h2('Woran du einen Partnerlink erkennst', 'erkennen')}
<p>
  Die Kennzeichnung erfolgt an vier Stellen gleichzeitig, damit sie nicht übersehen werden kann:
</p>
${checklist([
  '<strong>Am Link selbst:</strong> ein hochgestelltes Sternchen (*) direkt hinter dem Linktext oder im Button.',
  '<strong>Über jedem Empfehlungsblock:</strong> ein umrandeter Hinweiskasten mit dem Werbehinweis.',
  '<strong>Im Footer jeder Seite:</strong> der Standardhinweis zu Partnerlinks.',
  '<strong>Technisch:</strong> jeder Partnerlink trägt die Attribute <code>rel="sponsored nofollow"</code> und öffnet in einem neuen Tab.',
])}

${h2('Was das für dich kostet', 'kosten')}
${table({
  head: ['Frage', 'Antwort'],
  rows: [
    ['Zahle ich mehr, wenn ich über einen Partnerlink kaufe?', '<strong>Nein.</strong> Der Preis ist identisch mit dem Direktaufruf des Shops.'],
    ['Bekomme ich einen schlechteren Service?', 'Nein. Du bist ganz normaler Kunde des jeweiligen Shops.'],
    ['Gelten Rückgaberecht und Garantie normal?', 'Ja. Vertragspartner ist ausschließlich der Shop, nicht Sattelfest.'],
    ['Muss ich Partnerlinks nutzen?', 'Nein. Du kannst jeden Shop auch direkt aufrufen – die Inhalte hier bleiben identisch.'],
    ['Werden meine Daten weitergegeben?', 'Von uns nicht. Der Zielanbieter kann eigene Cookies setzen – siehe <a href="/datenschutz.html">Datenschutz</a>.'],
  ],
})}

${h2('Unsere Regeln für Empfehlungen', 'regeln')}
<p>
  Eine über Provisionen finanzierte Website hat einen Interessenkonflikt. Den kann man nicht
  wegdiskutieren, aber man kann ihn durch Regeln begrenzen. Das sind unsere:
</p>
${doDont({
  doTitle: 'Was wir tun',
  doItems: [
    '<strong>Kategorien statt Artikelnummern.</strong> Wir empfehlen Spezifikationen – Volumen, R-Wert, Komforttemperatur, Wassersäule, Preisrahmen. Die gelten jahrelang, einzelne Modelle wechseln jede Saison.',
    '<strong>Wir raten auch vom Kauf ab.</strong> Auf dieser Seite steht an mehreren Stellen, dass du etwas nicht brauchst: kein neues Rad, kein Komplettset, kein Wasserfilter in Deutschland, keine Ultraleicht-Ausrüstung für die erste Tour.',
    '<strong>Wir nennen günstige Alternativen zuerst,</strong> wenn sie die bessere Lösung sind – etwa gebrauchte Packtaschen statt eines neuen Bikepacking-Sets.',
    '<strong>Wir kennzeichnen jeden Partnerlink</strong> und erklären das Finanzierungsmodell offen.',
    '<strong>Preise nennen wir als Rahmen,</strong> nicht als Tagespreis. Was heute stimmt, stimmt in drei Monaten nicht mehr.',
  ],
  dontTitle: 'Was wir nicht tun',
  dontItems: [
    '<strong>Keine erfundenen Testberichte.</strong> Wir behaupten nirgends, Zelte oder Taschen getestet zu haben, die wir nicht getestet haben.',
    '<strong>Keine Empfehlung wegen höherer Provision.</strong> Die Reihenfolge der Empfehlungen richtet sich nach dem Einsatzzweck, nicht nach der Vergütung.',
    '<strong>Keine künstliche Dringlichkeit.</strong> Keine Countdown-Timer, keine „nur noch heute"-Hinweise, keine erfundenen Rabatte.',
    '<strong>Keine Deeplinks auf einzelne Artikel.</strong> Artikelnummern verschwinden und erzeugen tote Links – wir verlinken Kategorien und Suchergebnisse.',
    '<strong>Kein Verkauf deiner Daten.</strong> Es gibt kein Tracking, keine Werbenetzwerke und keine Weitergabe von Newsletter-Adressen.',
  ],
})}

${h2('Warum überhaupt Partnerlinks?', 'warum')}
<p>
  Eine Website in diesem Umfang kostet Zeit und Geld: Recherche, Hosting, Domain, Bilder, Pflege.
  Für die Finanzierung gibt es im Wesentlichen vier Wege:
</p>
${table({
  head: ['Modell', 'Was es für dich bedeutet', 'Warum wir es (nicht) nutzen'],
  rows: [
    [
      'Bezahlschranke',
      'Du zahlst für den Zugang',
      'Schließt genau die Einsteiger aus, für die die Seite gedacht ist',
    ],
    [
      'Werbebanner',
      'Tracking, Cookie-Banner, langsame Seiten',
      'Widerspricht dem datensparsamen Aufbau dieser Seite',
    ],
    [
      'Gesponserte Inhalte',
      'Hersteller bezahlt für positive Erwähnung',
      'Zerstört die Unabhängigkeit der Empfehlungen',
    ],
    [
      '<strong>Partnerlinks</strong>',
      '<strong>Kostet dich nichts, Preis bleibt gleich</strong>',
      '<strong>Bestes Verhältnis aus Finanzierung und Unabhängigkeit</strong>',
    ],
  ],
  note: 'Der Nachteil bleibt: Wir verdienen nur, wenn du kaufst. Genau deshalb stehen die Regeln oben – und deshalb findest du hier auffällig oft den Rat, erst einmal gar nichts zu kaufen.',
})}

${callout(
  'Der ehrlichste Rat auf dieser Seite',
  '<p>Kaufe nichts, bevor du <strong>zwei Nächte draußen</strong> warst. Nach der ersten Nacht weißt du, ob du überhaupt draußen schlafen willst. Nach der zweiten weißt du, was dich konkret gestört hat – und genau das ist deine Einkaufsliste. Dieser Rat kostet uns Provision und ist trotzdem der richtige.</p>',
  'money'
)}

${h2('Unsere Partnerprogramme', 'partner')}
<p>
  Wir arbeiten mit den folgenden Partnern zusammen bzw. planen dies:
</p>
<ul>
  ${Object.values(affiliates.partners)
    .map((p) => `<li><strong>${p.label}</strong></li>`)
    .join('\n  ')}
</ul>
<p>
  <span class="ph">Diese Liste vor dem Livegang an die tatsächlich freigeschalteten Programme anpassen
  und die jeweils vom Programm vorgegebenen Pflichthinweise ergänzen – insbesondere den
  Standardhinweis des Amazon-PartnerNets in der aktuellen Fassung.</span>
</p>

${h2('Fragen oder Kritik', 'kontakt')}
<p>
  Wenn dir eine Empfehlung unpassend erscheint, ein Link nicht funktioniert oder eine Angabe veraltet
  ist, schreib uns. Korrekturen sind ausdrücklich willkommen – eine Ratgeberseite lebt davon, dass
  die Angaben stimmen. Die Kontaktadresse steht im <a href="/impressum.html">Impressum</a>.
</p>
`;

const body = `
${pageHero({
  kicker: 'Transparenz',
  title: 'Affiliate-Hinweis',
  lead: 'Wie sich diese Seite finanziert, woran du Partnerlinks erkennst und nach welchen Regeln wir empfehlen.',
})}
${section(`<div class="prose">${content}</div>`, { tone: 'plain' })}
`;

module.exports = {
  href: '/affiliate-hinweis.html',
  title: 'Affiliate-Hinweis',
  navLabel: 'Affiliate-Hinweis',
  breadcrumb: 'Affiliate-Hinweis',
  metaTitle: 'Affiliate-Hinweis: Wie sich Sattelfest finanziert | Sattelfest',
  description:
    'Transparenzhinweis zu Partnerlinks auf Sattelfest: Woran du sie erkennst, was sie dich kosten (nichts) und nach welchen Regeln Empfehlungen ausgesprochen werden.',
  bodyClass: 'legal',
  updated: '2026-08-02',
  noPager: true,
  body,
};
