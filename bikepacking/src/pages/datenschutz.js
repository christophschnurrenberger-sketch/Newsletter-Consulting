'use strict';

const { pageHero, section, h2, h3, callout, toc } = require('../components');

const SECTIONS = [
  { id: 'verantwortlicher', label: 'Verantwortlicher' },
  { id: 'ueberblick', label: 'Überblick der Verarbeitungen' },
  { id: 'hosting', label: 'Hosting und Server-Logfiles' },
  { id: 'newsletter', label: 'Newsletter' },
  { id: 'affiliate', label: 'Partner- und Affiliate-Links' },
  { id: 'tools', label: 'Rechner und Werkzeuge' },
  { id: 'cookies', label: 'Cookies und lokale Speicherung' },
  { id: 'kontakt', label: 'Kontaktaufnahme' },
  { id: 'rechte', label: 'Deine Rechte' },
  { id: 'dauer', label: 'Speicherdauer' },
  { id: 'aenderungen', label: 'Änderungen' },
];

const content = `
${callout(
  'Vorlage – bitte anpassen und prüfen lassen',
  '<p>Diese Datenschutzerklärung beschreibt die Verarbeitungen, die diese Website in ihrer ausgelieferten Form tatsächlich vornimmt. Alle <span class="ph">gelb markierten</span> Stellen musst du ergänzen – insbesondere Hosting-Anbieter, Newsletter-Dienstleister und die eingesetzten Partnerprogramme. Der Text ersetzt <strong>keine Rechtsberatung</strong>; lass ihn vor dem Livegang fachkundig prüfen.</p>',
  'warn'
)}

${toc(SECTIONS, 'Inhalt')}

${h2('1. Verantwortlicher', 'verantwortlicher')}
<p>Verantwortlich für die Datenverarbeitung auf dieser Website ist:</p>
<address class="legal-address">
  <span class="ph">Vorname Nachname / Firmenname</span><br>
  <span class="ph">Straße und Hausnummer</span><br>
  <span class="ph">PLZ Ort</span><br>
  E-Mail: <span class="ph">deine-adresse@beispiel.de</span>
</address>
<p>
  <span class="ph">Falls du einen Datenschutzbeauftragten benannt hast, ergänze hier dessen Kontaktdaten.
  Für die meisten kleinen Websites besteht diese Pflicht nicht.</span>
</p>

${h2('2. Überblick der Verarbeitungen', 'ueberblick')}
<p>
  Diese Website ist bewusst datensparsam aufgebaut. Sie ist rein statisch, lädt keine externen
  Schriften, keine externen Skripte und keine Inhalte von fremden Servern nach. Konkret bedeutet das:
</p>
<ul>
  <li>Es werden <strong>keine Analyse- oder Trackingdienste</strong> eingesetzt.</li>
  <li>Es werden <strong>keine Werbenetzwerke</strong> eingebunden.</li>
  <li>Es werden <strong>keine Cookies</strong> gesetzt.</li>
  <li>Es werden <strong>keine Schriftarten, Karten oder Videos</strong> von Drittanbietern geladen.</li>
  <li>Es gibt <strong>keine Nutzerkonten</strong> und keine Anmeldung.</li>
</ul>
<p>
  Personenbezogene Daten fallen daher nur an drei Stellen an: beim technisch notwendigen Seitenaufruf
  (Server-Logfiles), bei einer freiwilligen Newsletter-Anmeldung und wenn du uns per E-Mail schreibst.
</p>

${h2('3. Hosting und Server-Logfiles', 'hosting')}
<p>
  Die Website wird bei einem externen Anbieter gehostet:
  <span class="ph">Name und Anschrift deines Hosting-Anbieters eintragen</span>. Mit dem Anbieter
  besteht ein Vertrag zur Auftragsverarbeitung nach Art. 28 DSGVO.
</p>
<p>
  Beim Aufruf der Website werden vom Webserver automatisch Daten in sogenannten Logfiles gespeichert,
  die dein Browser übermittelt. Das sind in der Regel:
</p>
<ul>
  <li>IP-Adresse des anfragenden Geräts</li>
  <li>Datum und Uhrzeit des Zugriffs</li>
  <li>Name und URL der abgerufenen Datei</li>
  <li>Übertragene Datenmenge und Meldung über den erfolgreichen Abruf</li>
  <li>Verwendeter Browser und Betriebssystem</li>
  <li>Zuvor besuchte Seite (Referrer), sofern übermittelt</li>
</ul>
<p>
  <strong>Zweck und Rechtsgrundlage:</strong> Die Verarbeitung erfolgt zur Auslieferung der Website,
  zur Gewährleistung der Systemsicherheit und zur Fehleranalyse. Rechtsgrundlage ist
  Art. 6 Abs. 1 lit. f DSGVO (berechtigtes Interesse an einem technisch fehlerfreien und sicheren
  Betrieb der Website).
</p>
<p>
  <strong>Speicherdauer:</strong> <span class="ph">Speicherdauer beim Hoster erfragen und eintragen,
  üblich sind 7 bis 30 Tage.</span> Eine Zusammenführung dieser Daten mit anderen Datenquellen findet
  nicht statt.
</p>

${h2('4. Newsletter', 'newsletter')}
<p>
  Du kannst dich freiwillig für unseren Newsletter anmelden. Dafür verarbeiten wir deine
  E-Mail-Adresse und – falls du sie angibst – deinen Vornamen.
</p>
${h3('Double-Opt-in-Verfahren', 'newsletter-doi')}
<p>
  Die Anmeldung erfolgt im Double-Opt-in-Verfahren: Nach der Eintragung erhältst du eine E-Mail mit
  einem Bestätigungslink. Erst wenn du diesen anklickst, wird deine Adresse in den Verteiler
  aufgenommen. So stellen wir sicher, dass niemand fremde Adressen einträgt. Zur Dokumentation der
  Einwilligung protokollieren wir Anmeldezeitpunkt, Bestätigungszeitpunkt und IP-Adresse.
</p>
${h3('Versanddienstleister', 'newsletter-dienst')}
<p>
  Der Versand erfolgt über <span class="ph">Name und Anschrift deines Newsletter-Anbieters, z. B.
  CleverReach, Brevo, MailerLite, Rapidmail</span>. Mit dem Anbieter besteht ein Vertrag zur
  Auftragsverarbeitung nach Art. 28 DSGVO.
  <span class="ph">Falls der Anbieter außerhalb der EU sitzt oder Daten dorthin überträgt, muss hier
  die Rechtsgrundlage für den Drittlandtransfer ergänzt werden.</span>
</p>
<p>
  <span class="ph">Falls dein Anbieter Öffnungs- und Klickraten misst, muss das hier ausdrücklich
  beschrieben werden – einschließlich der Möglichkeit, dem zu widersprechen.</span>
</p>
${h3('Rechtsgrundlage und Widerruf', 'newsletter-recht')}
<p>
  Rechtsgrundlage ist deine Einwilligung nach Art. 6 Abs. 1 lit. a DSGVO. Du kannst diese Einwilligung
  jederzeit mit Wirkung für die Zukunft widerrufen – am einfachsten über den Abmeldelink, der in jeder
  Newsletter-E-Mail enthalten ist. Nach der Abmeldung wird deine Adresse aus dem Verteiler gelöscht.
</p>

${h2('5. Partner- und Affiliate-Links', 'affiliate')}
<p>
  Diese Website enthält Partnerlinks, die mit einem Sternchen (*) gekennzeichnet sind. Solange du
  einen solchen Link <strong>nicht anklickst</strong>, findet keinerlei Datenübertragung an den
  Partnershop statt – die Links sind einfache HTML-Verweise ohne Skripte oder Zählpixel.
</p>
<p>
  Klickst du einen Partnerlink an, wirst du auf die Website des jeweiligen Händlers weitergeleitet.
  Ab diesem Moment gilt dessen Datenschutzerklärung. Der Händler bzw. das Partnernetzwerk kann dann
  Cookies setzen, um einen späteren Kauf der vermittelnden Website zuzuordnen. Wir erhalten dabei
  <strong>keine personenbezogenen Daten</strong>, sondern lediglich statistische Abrechnungsdaten
  über zustande gekommene Käufe.
</p>
<p>
  Eingesetzte Partnerprogramme:
  <span class="ph">Alle tatsächlich genutzten Programme und Netzwerke auflisten, z. B. Amazon
  PartnerNet, AWIN, Tradedoubler – jeweils mit Anbieter und Link zu deren Datenschutzerklärung.</span>
</p>
<p>
  Rechtsgrundlage für das Setzen der Links ist Art. 6 Abs. 1 lit. f DSGVO (berechtigtes Interesse an
  der Finanzierung des kostenlosen Angebots). Weitere Informationen findest du im
  <a href="/affiliate-hinweis.html">Affiliate-Hinweis</a>.
</p>

${h2('6. Rechner und Werkzeuge', 'tools')}
<p>
  Der <a href="/tools/packlisten-generator.html">Packlisten-Generator</a> und der
  <a href="/tools/etappen-rechner.html">Etappen- und Gewichts-Rechner</a> arbeiten vollständig in
  deinem Browser (clientseitig in JavaScript).
</p>
<ul>
  <li>Deine Eingaben werden <strong>nicht an einen Server übertragen</strong>.</li>
  <li>Sie werden <strong>nicht gespeichert</strong> – weder auf dem Server noch in deinem Browser.</li>
  <li>Beim Neuladen der Seite sind alle Eingaben zurückgesetzt.</li>
</ul>
<p>
  Es findet damit keine Verarbeitung personenbezogener Daten im Sinne der DSGVO statt. Insbesondere
  werden auch die optionalen Angaben zu Körpergewicht oder Fitness ausschließlich lokal verrechnet
  und niemals übermittelt.
</p>

${h2('7. Cookies und lokale Speicherung', 'cookies')}
<p>
  Diese Website setzt <strong>keine Cookies</strong> und nutzt weder Local Storage noch Session
  Storage. Es ist deshalb kein Cookie-Banner und keine Einwilligung nach § 25 TDDDG erforderlich.
</p>
<p>
  Ausnahme: Wenn du einen Partnerlink anklickst und die Seite des Händlers aufrufst, kann dieser
  Cookies setzen. Darauf haben wir keinen Einfluss – siehe Abschnitt 5.
</p>

${h2('8. Kontaktaufnahme', 'kontakt')}
<p>
  Wenn du uns per E-Mail schreibst, verarbeiten wir deine Angaben ausschließlich zur Bearbeitung
  deiner Anfrage. Rechtsgrundlage ist Art. 6 Abs. 1 lit. f DSGVO (berechtigtes Interesse an der
  Beantwortung von Anfragen) bzw. Art. 6 Abs. 1 lit. b DSGVO, sofern die Anfrage auf einen Vertrag
  abzielt. Die Daten werden gelöscht, sobald die Anfrage abschließend bearbeitet ist und keine
  gesetzlichen Aufbewahrungspflichten entgegenstehen.
</p>

${h2('9. Deine Rechte', 'rechte')}
<p>Dir stehen als betroffene Person folgende Rechte zu:</p>
<ul>
  <li><strong>Auskunft</strong> über die zu deiner Person gespeicherten Daten (Art. 15 DSGVO)</li>
  <li><strong>Berichtigung</strong> unrichtiger Daten (Art. 16 DSGVO)</li>
  <li><strong>Löschung</strong> (Art. 17 DSGVO)</li>
  <li><strong>Einschränkung der Verarbeitung</strong> (Art. 18 DSGVO)</li>
  <li><strong>Datenübertragbarkeit</strong> (Art. 20 DSGVO)</li>
  <li><strong>Widerspruch</strong> gegen Verarbeitungen auf Grundlage berechtigter Interessen (Art. 21 DSGVO)</li>
  <li><strong>Widerruf einer Einwilligung</strong> mit Wirkung für die Zukunft (Art. 7 Abs. 3 DSGVO)</li>
</ul>
<p>
  Zur Ausübung genügt eine formlose Nachricht an die oben genannte E-Mail-Adresse.
</p>
<p>
  Außerdem hast du das Recht, dich bei einer Datenschutz-Aufsichtsbehörde zu beschweren
  (Art. 77 DSGVO). Zuständig ist in der Regel die Behörde deines Wohnsitzlandes oder die des Sitzes
  des Verantwortlichen: <span class="ph">zuständige Landesdatenschutzbehörde eintragen</span>.
</p>

${h2('10. Speicherdauer', 'dauer')}
<p>
  Wir speichern personenbezogene Daten nur so lange, wie es für den jeweiligen Zweck erforderlich ist
  oder gesetzliche Aufbewahrungsfristen es vorschreiben:
</p>
<ul>
  <li><strong>Server-Logfiles:</strong> <span class="ph">Dauer beim Hoster erfragen</span></li>
  <li><strong>Newsletter-Daten:</strong> bis zum Widerruf der Einwilligung</li>
  <li><strong>Nachweis der Einwilligung:</strong> bis zum Ablauf möglicher Verjährungsfristen</li>
  <li><strong>E-Mail-Anfragen:</strong> bis zur abschließenden Bearbeitung</li>
</ul>

${h2('11. Änderungen dieser Erklärung', 'aenderungen')}
<p>
  Wir passen diese Datenschutzerklärung an, wenn sich die Rechtslage oder die tatsächlichen
  Verarbeitungen auf dieser Website ändern – etwa wenn ein Newsletter-Dienst oder ein Partnerprogramm
  hinzukommt. Es gilt jeweils die hier veröffentlichte Fassung.
</p>
<p><strong>Stand:</strong> <span class="ph">Datum der letzten Änderung eintragen</span></p>
`;

const body = `
${pageHero({
  kicker: 'Rechtliches',
  title: 'Datenschutzerklärung',
  lead: 'Welche Daten diese Website verarbeitet – und welche ausdrücklich nicht.',
})}
${section(`<div class="prose">${content}</div>`, { tone: 'plain' })}
`;

module.exports = {
  href: '/datenschutz.html',
  title: 'Datenschutzerklärung',
  navLabel: 'Datenschutz',
  breadcrumb: 'Datenschutz',
  metaTitle: 'Datenschutzerklärung | Sattelfest',
  description:
    'Datenschutzerklärung von Sattelfest: Verarbeitung beim Seitenaufruf, Newsletter im Double-Opt-in, Umgang mit Partnerlinks, keine Cookies und keine Trackingdienste.',
  bodyClass: 'legal',
  updated: '2026-08-02',
  noPager: true,
  body,
};
