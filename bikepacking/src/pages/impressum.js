'use strict';

const { pageHero, section, h2, callout } = require('../components');

const content = `
${callout(
  'Vorlage – bitte ausfüllen und prüfen lassen',
  '<p>Alle <span class="ph">gelb markierten</span> Stellen müssen durch deine echten Daten ersetzt werden. Diese Vorlage orientiert sich an den üblichen Pflichtangaben nach § 5 DDG und § 18 Abs. 2 MStV, ersetzt aber <strong>keine Rechtsberatung</strong>. Lass den fertigen Text vor dem Livegang von einer fachkundigen Stelle prüfen – ein fehlerhaftes Impressum ist abmahnfähig.</p>',
  'warn'
)}

${h2('Angaben gemäß § 5 DDG', 'anbieter')}
<address class="legal-address">
  <span class="ph">Vorname Nachname / Firmenname</span><br>
  <span class="ph">Straße und Hausnummer</span><br>
  <span class="ph">PLZ Ort</span><br>
  <span class="ph">Deutschland</span>
</address>
<p>
  <strong>Rechtsform:</strong> <span class="ph">z. B. Einzelunternehmen, GbR, UG (haftungsbeschränkt), GmbH</span><br>
  <strong>Vertreten durch:</strong> <span class="ph">nur bei Gesellschaften: Name der vertretungsberechtigten Person</span><br>
  <strong>Registereintrag:</strong> <span class="ph">nur bei eingetragenen Gesellschaften: Registergericht und Registernummer</span>
</p>

${h2('Kontakt', 'kontakt')}
<p>
  <strong>E-Mail:</strong> <span class="ph">deine-adresse@beispiel.de</span><br>
  <strong>Telefon:</strong> <span class="ph">optional – keine Pflichtangabe, wenn eine schnelle elektronische Kontaktaufnahme möglich ist</span>
</p>

${h2('Umsatzsteuer-Identifikationsnummer', 'ustid')}
<p>
  Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz:
  <span class="ph">DE123456789 – oder streichen, falls Kleinunternehmerregelung nach § 19 UStG</span>
</p>

${h2('Verantwortlich für den Inhalt nach § 18 Abs. 2 MStV', 'verantwortlich')}
<address class="legal-address">
  <span class="ph">Vorname Nachname</span><br>
  <span class="ph">Straße und Hausnummer</span><br>
  <span class="ph">PLZ Ort</span>
</address>

${h2('Hinweis zu Werbe- und Partnerlinks', 'affiliate')}
<p>
  Diese Website enthält Provisions- bzw. Partnerlinks, die mit einem Sternchen (*) gekennzeichnet sind.
  Beim Kauf über einen solchen Link erhält der Anbieter dieser Website eine Vergütung vom jeweiligen
  Händler. Für Käuferinnen und Käufer entstehen dadurch keine Mehrkosten. Ausführliche Informationen
  stehen im <a href="/affiliate-hinweis.html">Affiliate-Hinweis</a>.
</p>
<p>
  <span class="ph">Falls du am Amazon-PartnerNet teilnimmst, ergänze hier den vom Programm vorgegebenen
  Standardhinweis in der jeweils aktuellen Fassung.</span>
</p>

${h2('Streitschlichtung', 'streitschlichtung')}
<p>
  Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung bereit. Wir sind nicht
  verpflichtet und nicht bereit, an Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle
  teilzunehmen.
</p>
<p>
  <span class="ph">Diesen Abschnitt an die aktuelle Rechtslage anpassen lassen – die Vorgaben zur
  OS-Plattform haben sich geändert.</span>
</p>

${h2('Haftung für Inhalte', 'haftung-inhalte')}
<p>
  Als Diensteanbieter sind wir gemäß § 7 Abs. 1 DDG für eigene Inhalte auf diesen Seiten nach den
  allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 DDG sind wir als Diensteanbieter jedoch nicht
  verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu
  forschen, die auf eine rechtswidrige Tätigkeit hinweisen.
</p>
<p>
  Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen
  Gesetzen bleiben hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der
  Kenntnis einer konkreten Rechtsverletzung möglich. Bei Bekanntwerden entsprechender Rechtsverletzungen
  werden wir diese Inhalte umgehend entfernen.
</p>

${h2('Haftung für Links', 'haftung-links')}
<p>
  Unser Angebot enthält Links zu externen Websites Dritter, auf deren Inhalte wir keinen Einfluss haben.
  Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der
  verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich. Die
  verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße überprüft;
  rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar.
</p>
<p>
  Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist ohne konkrete Anhaltspunkte einer
  Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links
  umgehend entfernen.
</p>

${h2('Urheberrecht', 'urheberrecht')}
<p>
  Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen
  Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der
  Grenzen des Urheberrechts bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers.
  Downloads und Kopien dieser Seite sind nur für den privaten, nicht kommerziellen Gebrauch gestattet.
</p>

${h2('Hinweis zu den Inhalten dieser Website', 'inhaltshinweis')}
<p>
  Die Informationen auf dieser Website dienen der allgemeinen Orientierung für Bikepacking-Einsteiger.
  Sie stellen keine Rechtsberatung, keine medizinische Beratung und keine verbindliche Auskunft zu
  Naturschutz-, Wald- oder Straßenverkehrsrecht dar. Insbesondere die Angaben zum Übernachten in der
  Natur unterscheiden sich nach Bundesland, Schutzgebiet und Grundstückseigentümer und ändern sich
  laufend – maßgeblich sind stets die vor Ort geltenden Vorschriften. Prüfe die Regelungen deiner
  Route eigenverantwortlich.
</p>
<p>
  Angaben zu Gewichten, Preisen, Distanzen, Höhenmetern und Streckenzuständen sind Orientierungswerte
  und können sich jederzeit ändern. Die Nutzung der hier beschriebenen Routen und Techniken erfolgt auf
  eigene Gefahr.
</p>
`;

const body = `
${pageHero({
  kicker: 'Rechtliches',
  title: 'Impressum',
  lead: 'Anbieterkennzeichnung nach § 5 DDG und § 18 Abs. 2 MStV.',
})}
${section(`<div class="prose">${content}</div>`, { tone: 'plain' })}
`;

module.exports = {
  href: '/impressum.html',
  title: 'Impressum',
  navLabel: 'Impressum',
  breadcrumb: 'Impressum',
  metaTitle: 'Impressum | Sattelfest',
  description: 'Impressum und Anbieterkennzeichnung von Sattelfest.',
  bodyClass: 'legal',
  updated: '2026-08-02',
  noPager: true,
  body,
};
