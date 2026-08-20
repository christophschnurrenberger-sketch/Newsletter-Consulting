<?php
$page = [
    'title'       => 'Die IT-Dokumentenlandkarte',
    'description' => 'Welche Dokumente eine mittelständische IT wirklich braucht: 18 Dokumente mit Zweck, Verantwortlichem, Umfang und Prüfrhythmus – als Tabelle zum Abhaken, samt Hinweis, was man sich sparen kann.',
    'section'     => 'wissen',
    'path'        => 'wissen/dokumentenlandkarte.php',
    'crumbs'      => [['Wissen', 'wissen/'], ['Dokumentenlandkarte', null]],
    'hero'        => [
        'kicker' => 'Leitfaden · Checkliste',
        'h1'     => 'Achtzehn Dokumente. <span class="accent">Mehr braucht es nicht.</span>',
        'lead'   => 'Diese Landkarte ist das Ergebnis der immer gleichen Frage in Projekten: „Was müssen wir denn alles schreiben?“ Die Antwort ist kürzer, als die meisten befürchten – und länger, als die meisten haben.',
    ],
];
include __DIR__ . '/../partials/header.php';

$asideCta = [
    'title' => 'Landkarte aufbauen',
    'text'  => 'Im Governance-Framework entstehen diese Dokumente – abgestimmt, freigegeben und mit Pflegerhythmus.',
    'link'  => ['Governance-Framework', 'leistungen/governance-framework.php'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Ebene 1: Regelungen (was gelten soll)</h2>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Dokument</th><th scope="col">Zweck in einem Satz</th><th scope="col">Umfang</th><th scope="col">Prüfung</th></tr>
                        </thead>
                        <tbody>
                            <tr><td><strong>IT-Leitlinie</strong></td><td>Ziele, Grundsätze und Bekenntnis der Geschäftsführung zur IT-Steuerung</td><td class="num">2–4 S.</td><td class="num">jährlich</td></tr>
                            <tr><td><strong>IT-Organisation &amp; Rollen</strong></td><td>Wer entscheidet was, welche Rollen gibt es, wer vertritt wen</td><td class="num">4–8 S.</td><td class="num">jährlich</td></tr>
                            <tr><td><strong>Informationssicherheitsrichtlinie</strong></td><td>Schutzziele, Klassifizierung, Grundregeln</td><td class="num">4–8 S.</td><td class="num">jährlich</td></tr>
                            <tr><td><strong>Nutzungsregelung für Mitarbeitende</strong></td><td>Was am Arbeitsplatz erlaubt ist und was nicht</td><td class="num">2–3 S.</td><td class="num">jährlich</td></tr>
                            <tr><td><strong>Zugriffs- und Berechtigungsregelung</strong></td><td>Beantragung, Genehmigung, Entzug, Rezertifizierung</td><td class="num">4–6 S.</td><td class="num">jährlich</td></tr>
                            <tr><td><strong>Änderungs- und Freigaberegelung</strong></td><td>Wer genehmigt Änderungen, wie werden sie dokumentiert</td><td class="num">3–5 S.</td><td class="num">jährlich</td></tr>
                            <tr><td><strong>Dienstleisterrichtlinie</strong></td><td>Auswahl, Mindestanforderungen, Steuerung, Ausstieg</td><td class="num">3–5 S.</td><td class="num">jährlich</td></tr>
                            <tr><td><strong>Notfall- und Wiederanlaufregelung</strong></td><td>Kritikalität, Ziele, Alarmierung, Testpflicht</td><td class="num">4–8 S.</td><td class="num">halbjährlich</td></tr>
                        </tbody>
                    </table>
                </div>

                <h2>Ebene 2: Verzeichnisse (was wir haben)</h2>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Dokument</th><th scope="col">Zweck in einem Satz</th><th scope="col">Form</th><th scope="col">Pflege</th></tr>
                        </thead>
                        <tbody>
                            <tr><td><strong>Anwendungs- und Systemverzeichnis</strong></td><td>Was ist im Einsatz, wer verantwortet es, wie kritisch ist es</td><td>Tabelle / Werkzeug</td><td class="num">laufend</td></tr>
                            <tr><td><strong>Dienstleisterübersicht</strong></td><td>Wer erbringt was, mit welchem Zugriff, mit welchem Vertrag</td><td>Tabelle</td><td class="num">halbjährlich</td></tr>
                            <tr><td><strong>Servicekatalog</strong></td><td>Welche Leistungen die IT erbringt, für wen, in welcher Zeit</td><td>Tabelle / Intranet</td><td class="num">jährlich</td></tr>
                            <tr><td><strong>Risikoübersicht</strong></td><td>Welche IT-Risiken bestehen, wie bewertet, wie behandelt</td><td>Tabelle</td><td class="num">quartalsweise</td></tr>
                            <tr><td><strong>Kontrollübersicht</strong></td><td>Welche Kontrollen es gibt, wer sie durchführt, wo der Nachweis liegt</td><td>Tabelle</td><td class="num">quartalsweise</td></tr>
                            <tr><td><strong>Maßnahmenliste</strong></td><td>Was ist beschlossen, wer macht es, bis wann</td><td>Tabelle / Ticketsystem</td><td class="num">monatlich</td></tr>
                        </tbody>
                    </table>
                </div>

                <h2>Ebene 3: Betrieb und Nachweise</h2>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Dokument</th><th scope="col">Zweck in einem Satz</th><th scope="col">Besonderheit</th></tr>
                        </thead>
                        <tbody>
                            <tr><td><strong>Notfallhandbuch</strong></td><td>Wer tut was im Ernstfall, in welcher Reihenfolge</td><td>muss offline verfügbar sein</td></tr>
                            <tr><td><strong>Wiederanlaufanweisungen</strong></td><td>Wie ein System konkret wiederhergestellt wird</td><td>nach jedem Test aktualisieren</td></tr>
                            <tr><td><strong>Netz- und Architekturübersicht (grob)</strong></td><td>Wie die Systeme zusammenhängen</td><td>eine Seite genügt oft</td></tr>
                            <tr><td><strong>Protokolle der Gremiensitzungen</strong></td><td>Belegen Entscheidungen und Aufsicht</td><td>der meistunterschätzte Nachweis</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout is-ok">
                    <span class="callout-icon"><i data-icon="check-circle" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Realistische Einschätzung des Aufwands</h3>
                        <p>
                            Achtzehn Dokumente klingen nach viel. Es sind zusammengenommen etwa
                            60 bis 90 Seiten Text plus sechs Tabellen. Für ein Unternehmen ohne
                            Vorarbeit sind das rund 25 bis 40 Personentage über sechs bis zwölf
                            Monate – inklusive Abstimmung, die den größeren Teil ausmacht. Der
                            laufende Pflegeaufwand liegt danach bei ein bis zwei Tagen im Quartal.
                        </p>
                    </div>
                </div>

                <h2>Was jedes Dokument braucht</h2>
                <ul class="checklist">
                    <li><strong>Kopfzeile mit Version, Datum, Verantwortlichem und Freigebendem.</strong>
                        Ein Dokument ohne Freigabevermerk gilt als Entwurf – auch nach drei Jahren.</li>
                    <li><strong>Geltungsbereich.</strong> Für welche Gesellschaften, Standorte und
                        Personengruppen gilt es?</li>
                    <li><strong>Datum der nächsten Prüfung.</strong> Ohne Wiedervorlage veraltet
                        jede Regelung.</li>
                    <li><strong>Verweis statt Wiederholung.</strong> Inhalte, die in einem anderen
                        Dokument stehen, werden verlinkt, nicht kopiert. Jede Kopie ist eine
                        künftige Abweichung.</li>
                </ul>

                <h2>Was Sie sich sparen können</h2>
                <ul class="checklist is-cross">
                    <li>Ein eigenes Dokument je Prozess. Vier bis sechs Prozesssteckbriefe in einem
                        Dokument sind übersichtlicher als sechs Einzeldateien.</li>
                    <li>Eine Konzernrichtlinienstruktur mit vier Ebenen und Nummernsystematik.</li>
                    <li>Umfangreiche Begriffsverzeichnisse – zwei Sätze im jeweiligen Dokument genügen.</li>
                    <li>Ein Dokumentenlenkungsverfahren als eigenes Regelwerk. Zwei Absätze in der
                        IT-Leitlinie reichen völlig.</li>
                    <li>Beschreibungen von Standardsoftware, die der Hersteller besser dokumentiert.</li>
                </ul>

                <h2>Die Reihenfolge, in der man schreibt</h2>
                <p>
                    Nicht alphabetisch und nicht nach Wichtigkeit, sondern nach Abhängigkeit: Erst
                    die Rollen, dann die Regeln, die Rollen voraussetzen, dann die Verzeichnisse,
                    dann die Kontrollen, die auf Verzeichnissen aufsetzen.
                </p>
<?php
$kette = [
    ['1', 'IT-Organisation & Rollen'],
    ['2', 'IT-Leitlinie'],
    ['3', 'Verzeichnisse: Anwendungen, Dienstleister'],
    ['4', 'Fachregelungen: Zugriff, Änderung, Notfall'],
    ['5', 'Kontrollübersicht & Nachweise'],
];
$ketteLabel = 'Reihenfolge der Dokumenterstellung';
include __DIR__ . '/../partials/kette.php';
?>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['themen/it-dokumentation.php', 'leistungen/governance-framework.php', 'wissen/kontrollen-nachweise.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
