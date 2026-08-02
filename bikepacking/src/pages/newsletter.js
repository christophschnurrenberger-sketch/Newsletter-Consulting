'use strict';

const article = require('./_article');
const {
  h2, callout, checklist, table, newsletterBlock, stats,
} = require('../components');

const content = `
<p class="lead-p">
  Alle 14 Tage eine E-Mail: eine Route mit konkreten Eckdaten, ein Ausrüstungsteil ohne Werbesprech
  und ein Fehler, den du dank uns nicht selbst machen musst. Kein Spam, keine Verkaufsserien,
  Abmeldung mit einem Klick.
</p>

${stats([
  { value: '14', label: 'Tage Rhythmus', note: 'Zweimal im Monat, nicht öfter.' },
  { value: '3', label: 'Themen pro Ausgabe', note: 'Route, Ausrüstung, Fehler – immer gleich aufgebaut.' },
  { value: '0 €', label: 'Kosten', note: 'Und keine Weitergabe deiner Adresse.' },
])}

${newsletterBlock({ variant: 'wide' })}

${h2('Was drinsteht', 'inhalt')}
${table({
  head: ['Rubrik', 'Was du bekommst'],
  rows: [
    [
      '<strong>Eine Route</strong>',
      'Mit Distanz, Höhenmetern, Untergrund, Übernachtungsmöglichkeiten und Bahnanreise – so, dass du sie ohne weitere Recherche fahren könntest.',
    ],
    [
      '<strong>Ein Ausrüstungsteil</strong>',
      'Eine Kategorie im Detail: worauf es ankommt, welche Spezifikation zu welchem Einsatz passt und ab wann sich teurer lohnt. Oft mit dem Ergebnis, dass du es nicht brauchst.',
    ],
    [
      '<strong>Ein Fehler</strong>',
      'Etwas, das schiefgegangen ist – bei uns oder bei Leserinnen und Lesern, die uns geschrieben haben. Mit der Lösung, nicht nur mit der Anekdote.',
    ],
    [
      'Saisonales',
      'Im Frühjahr, wann die Trekkingplätze öffnen. Im Herbst, wie du die kürzeren Tage einplanst. Kurz, nicht als Füllmaterial.',
    ],
  ],
  note: 'Immer derselbe Aufbau, damit du in 30 Sekunden erfassen kannst, ob die Ausgabe für dich relevant ist.',
})}

${h2('Das Startgeschenk', 'startgeschenk')}
<p>
  Nach der Bestätigung bekommst du die <strong>Sattelfest-Packliste als PDF</strong> – die vollständige
  Liste aus dem Ausrüstungsteil, aber in einer Form, die auf zwei Seiten passt und die du ausdrucken
  oder aufs Handy legen kannst.
</p>
${checklist([
  '<strong>Alle Positionen mit Gramm-Angabe</strong>, sortiert nach Kategorie',
  '<strong>Drei Spalten zum Abhaken:</strong> gekauft, gepackt, gebraucht – die dritte ist die interessanteste',
  '<strong>Eigene Gewichtsspalte</strong>, damit du deine tatsächlichen Werte eintragen kannst',
  '<strong>Varianten für Sommer, Übergangszeit und Touren ohne Camping</strong>',
  '<strong>Rückseite:</strong> die Prüfliste vor der Abfahrt und die Notfallnummern',
])}

${h2('Wie die Anmeldung abläuft', 'ablauf')}
${table({
  head: ['Schritt', 'Was passiert'],
  rows: [
    ['1. Eintragen', 'Du gibst deine E-Mail-Adresse ein. Der Vorname ist freiwillig.'],
    ['2. Bestätigungsmail', 'Du bekommst sofort eine E-Mail mit einem Bestätigungslink (Double-Opt-in).'],
    ['3. Bestätigen', 'Erst mit dem Klick auf den Link wird deine Adresse aufgenommen. Ohne Klick passiert nichts.'],
    ['4. Startgeschenk', 'Direkt nach der Bestätigung kommt die Packliste als PDF.'],
    ['5. Danach', 'Alle 14 Tage eine Ausgabe. Abmeldung über den Link in jeder E-Mail.'],
  ],
  note: 'Das Double-Opt-in-Verfahren ist gesetzlich vorgeschrieben und verhindert, dass jemand fremde Adressen einträgt.',
})}

${callout(
  'Keine Bestätigungsmail bekommen?',
  '<p>Schau zuerst im Spam-Ordner nach – Bestätigungsmails landen dort überdurchschnittlich oft. Wenn nach zehn Minuten nichts angekommen ist, hat sich vermutlich ein Tippfehler in die Adresse geschlichen. Trag sie dann einfach noch einmal ein.</p>',
  'info'
)}

${h2('Was wir nicht machen', 'nicht')}
${checklist(
  [
    '<strong>Keine Verkaufsserien.</strong> Es gibt kein Produkt, das wir dir über sieben E-Mails hinweg verkaufen wollen.',
    '<strong>Keine künstliche Dringlichkeit.</strong> Keine Countdowns, keine „nur noch heute"-Angebote.',
    '<strong>Keine Weitergabe deiner Adresse.</strong> An niemanden, unter keinen Umständen.',
    '<strong>Keine Mail ohne Inhalt.</strong> Wenn wir nichts Sinnvolles zu sagen haben, schreiben wir nicht.',
    '<strong>Keine Abmelde-Hürden.</strong> Ein Klick, sofort wirksam, keine Rückfrage.',
  ],
  { tone: 'dont' }
)}

${h2('Datenschutz', 'datenschutz')}
<p>
  Wir verarbeiten deine E-Mail-Adresse und – falls angegeben – deinen Vornamen ausschließlich für den
  Newsletter-Versand. Rechtsgrundlage ist deine Einwilligung nach Art. 6 Abs. 1 lit. a DSGVO, die du
  jederzeit mit Wirkung für die Zukunft widerrufen kannst. Zur Dokumentation der Einwilligung
  protokollieren wir Anmelde- und Bestätigungszeitpunkt.
</p>
<p>
  Alle Einzelheiten – einschließlich des eingesetzten Versanddienstleisters und der Speicherdauer –
  stehen in der <a href="/datenschutz.html">Datenschutzerklärung</a>.
</p>
`;

module.exports = article({
  href: '/newsletter.html',
  kicker: 'Service · Newsletter',
  title: 'Der Sattelfest-Newsletter',
  metaTitle: 'Bikepacking-Newsletter: Alle 14 Tage Route, Ausrüstung, Fehler | Sattelfest',
  description:
    'Der kostenlose Bikepacking-Newsletter von Sattelfest: alle 14 Tage eine Route mit Eckdaten, ein Ausrüstungsteil ohne Werbesprech und ein vermeidbarer Fehler. Startgeschenk: die Packliste als PDF.',
  lead:
    'Eine Route, ein Ausrüstungsteil, ein Fehler – alle 14 Tage. Kostenlos, ohne Verkaufsserien, Abmeldung mit einem Klick.',
  meta: [
    { icon: 'mail', text: 'Alle 14 Tage' },
    { icon: 'check', text: 'Startgeschenk: Packliste als PDF' },
    { icon: 'shield', text: 'Double-Opt-in, keine Weitergabe' },
  ],
  image: false,
  toc: [
    { label: 'Was drinsteht', id: 'inhalt' },
    { label: 'Das Startgeschenk', id: 'startgeschenk' },
    { label: 'Wie die Anmeldung abläuft', id: 'ablauf' },
    { label: 'Was wir nicht machen', id: 'nicht' },
    { label: 'Datenschutz', id: 'datenschutz' },
  ],
  content,
  faq: [
    {
      q: 'Wie oft kommt der Newsletter?',
      a: '<p>Alle 14 Tage, also zweimal im Monat. Wenn es einmal nichts Sinnvolles zu berichten gibt, kommt keine Ausgabe – wir füllen keine E-Mails, nur um den Rhythmus zu halten.</p>',
    },
    {
      q: 'Was kostet der Newsletter?',
      a: '<p>Nichts. Die Seite finanziert sich über Partnerlinks, die im Newsletter genauso gekennzeichnet sind wie hier auf der Website. Deine Adresse wird nicht verkauft und nicht weitergegeben.</p>',
    },
    {
      q: 'Ich habe keine Bestätigungsmail bekommen. Was nun?',
      a: '<p>Schau zuerst im Spam-Ordner nach, dort landen Bestätigungsmails überdurchschnittlich oft. Wenn nach zehn Minuten nichts da ist, hat sich vermutlich ein Tippfehler in die Adresse geschlichen – trag sie dann einfach noch einmal ein.</p>',
    },
    {
      q: 'Wie melde ich mich wieder ab?',
      a: '<p>Über den Abmeldelink am Ende jeder E-Mail. Ein Klick genügt, die Abmeldung ist sofort wirksam und es gibt keine Rückfrage. Deine Adresse wird anschließend aus dem Verteiler gelöscht.</p>',
    },
    {
      q: 'Bekomme ich das PDF auch ohne Anmeldung?',
      a: '<p>Die Inhalte der Packliste stehen vollständig und kostenlos auf der Seite <a href="/ausruestung/packliste.html">Die Packliste mit Gewichten</a> – dort sogar mit automatisch berechneten Summen. Das PDF ist die ausdruckbare Fassung mit Abhak-Spalten und dafür an die Anmeldung geknüpft.</p>',
    },
  ],
  related: [
    { href: '/ausruestung/packliste.html', label: 'Die Packliste mit Gewichten' },
    { href: '/tools/packlisten-generator.html', label: 'Packlisten-Generator' },
    { href: '/ueber-uns.html', label: 'Über Sattelfest' },
    { href: '/datenschutz.html', label: 'Datenschutzerklärung' },
  ],
});
