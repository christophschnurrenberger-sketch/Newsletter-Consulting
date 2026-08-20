<?php
$page = [
    'title'       => 'IT-Governance-Framework aufbauen',
    'description' => 'Richtlinienlandschaft, Gremien und Entscheidungswege für mittelständische Unternehmen: schlank geschnitten, freigegeben, bekannt und im Alltag anwendbar. 22.000 bis 45.000 € netto.',
    'section'     => 'leistungen',
    'path'        => 'leistungen/governance-framework.php',
    'crumbs'      => [['Leistungen', 'leistungen/'], ['Governance-Framework', null]],
    'hero'        => [
        'kicker' => 'Leistung · Aufbau',
        'h1'     => 'Zwölf Seiten, die <span class="accent">wirklich gelten</span> – statt 200, die niemand kennt',
        'lead'   => 'Ein IT-Governance-Framework ist keine Dokumentensammlung, sondern die Antwort auf drei Fragen: Wer entscheidet worüber, nach welcher Regel, und was passiert bei Abweichung. Alles Weitere ist Beiwerk.',
        'actions' => [
            ['Framework anfragen', 'kontakt.php', 'primary'],
            ['Dokumentenlandkarte ansehen', 'wissen/dokumentenlandkarte.php', 'ghost'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Dauer', '3–5 Monate'],
    ['Aufwand bei Ihnen', '4–6 Std./Woche'],
    ['Ergebnis', 'Richtlinien, Gremien, Rollen in Betrieb'],
    ['Preis', '22.000 – 45.000 € netto'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Warum Frameworks im Mittelstand scheitern</h2>
                <p>
                    Der übliche Weg: Jemand lädt ein Konzernregelwerk herunter, ersetzt den
                    Firmennamen und legt 40 Dokumente ins Intranet. Sechs Monate später kennt
                    niemand den Inhalt, die Dokumente sind unverändert, und in der nächsten
                    Prüfung wird genau das festgestellt – Regelungen ohne Wirksamkeitsnachweis.
                </p>
                <p>
                    Ein Framework wirkt nur, wenn es drei Bedingungen erfüllt: Es ist
                    <strong>kurz genug, um gelesen zu werden</strong>, es ist
                    <strong>entschieden, nicht vorgeschlagen</strong>, und es hat
                    <strong>einen Besitzer, der es pflegt</strong>. Alles drei sind
                    Organisationsfragen, keine Textfragen.
                </p>

                <h2>Was aufgebaut wird</h2>

                <h3>1 · Die Richtlinienlandschaft – bewusst klein</h3>
                <p>
                    Für ein Unternehmen mit 150 bis 2.000 Mitarbeitenden reichen in aller Regel
                    sechs bis zehn Regelungsdokumente. Mehr entsteht meist nur, wenn niemand
                    Nein sagt.
                </p>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Dokument</th><th scope="col">Inhalt</th><th scope="col">Umfang</th></tr>
                        </thead>
                        <tbody>
                            <tr><td><strong>IT-Leitlinie</strong></td><td>Ziele, Grundsätze, Geltungsbereich, Verantwortung der Geschäftsführung</td><td class="num">2–4 Seiten</td></tr>
                            <tr><td><strong>IT-Organisation &amp; Rollen</strong></td><td>Rollenbild, Gremien, Entscheidungswege, Eskalation</td><td class="num">4–8 Seiten</td></tr>
                            <tr><td><strong>Informationssicherheitsrichtlinie</strong></td><td>Schutzziele, Klassifizierung, Grundregeln für alle Mitarbeitenden</td><td class="num">4–8 Seiten</td></tr>
                            <tr><td><strong>Zugriffs- und Berechtigungsregelung</strong></td><td>Beantragung, Genehmigung, Entzug, Rezertifizierung, privilegierte Konten</td><td class="num">4–6 Seiten</td></tr>
                            <tr><td><strong>Änderungs- und Freigaberegelung</strong></td><td>Change-Kategorien, Freigeber, Notfalländerungen, Dokumentation</td><td class="num">3–5 Seiten</td></tr>
                            <tr><td><strong>Dienstleisterrichtlinie</strong></td><td>Auswahl, Mindestanforderungen, Steuerung, Prüfung, Ausstieg</td><td class="num">3–5 Seiten</td></tr>
                            <tr><td><strong>Notfall- und Wiederanlaufregelung</strong></td><td>Kritikalität, Wiederanlaufziele, Alarmierung, Testpflicht</td><td class="num">4–8 Seiten</td></tr>
                            <tr><td><strong>Nutzungsregelung für Mitarbeitende</strong></td><td>Was erlaubt ist und was nicht – die einzige Regelung, die wirklich alle lesen</td><td class="num">2–3 Seiten</td></tr>
                        </tbody>
                    </table>
                </div>

                <h3>2 · Gremien, die tatsächlich tagen</h3>
                <p>
                    Zwei Gremien genügen fast immer. Wichtiger als die Anzahl ist, dass sie
                    Entscheidungsbefugnis haben und dass ihre Beschlüsse protokolliert werden –
                    das Protokoll ist später der Nachweis.
                </p>
                <div class="card-grid cols-2">
                    <div class="card is-paper">
                        <h3 class="card-title">IT-Steuerkreis (quartalsweise)</h3>
                        <p class="card-text">
                            Geschäftsführung, IT-Leitung, zwei bis drei Fachbereichsleitungen.
                            Entscheidet über Portfolio, Budget, große Anforderungen, Risiken und
                            bewusste Ausnahmen. Dauer: 90 Minuten, feste Tagesordnung.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">IT-Jour-fixe (monatlich)</h3>
                        <p class="card-text">
                            IT-Leitung, Teamverantwortliche, bei Bedarf Dienstleister. Entscheidet
                            über Änderungen, Prioritäten im Betrieb, Maßnahmenstatus und
                            Kontrollergebnisse. Dauer: 60 Minuten.
                        </p>
                    </div>
                </div>

                <h3>3 · Entscheidungswege, die eine Grenze ziehen</h3>
                <p>
                    Der praktische Kern des Frameworks ist eine Tabelle: Welche Entscheidung
                    trifft wer, ab welcher Größenordnung, und wer muss vorher gehört werden.
                    Beispielhafte Struktur:
                </p>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Entscheidung</th><th scope="col">Bis 10.000 €</th><th scope="col">10.000 – 50.000 €</th><th scope="col">Über 50.000 €</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Neue Anwendung einführen</td><td>IT-Leitung</td><td>IT-Steuerkreis</td><td>Geschäftsführung</td></tr>
                            <tr><td>Dienstleister beauftragen</td><td>IT-Leitung</td><td>IT-Leitung + Einkauf</td><td>Geschäftsführung</td></tr>
                            <tr><td>Ausnahme von einer Richtlinie</td><td colspan="2">IT-Leitung, befristet, dokumentiert</td><td>IT-Steuerkreis</td></tr>
                            <tr><td>Risiko bewusst akzeptieren</td><td>IT-Leitung</td><td colspan="2">Geschäftsführung, schriftlich</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout">
                    <span class="callout-icon"><i data-icon="eye" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Die Ausnahme ist der wichtigste Mechanismus</h3>
                        <p>
                            Jede Regel wird irgendwann gebrochen – das ist normal und oft richtig.
                            Entscheidend ist, ob die Abweichung beantragt, befristet, begründet
                            und dokumentiert wird. Organisationen ohne Ausnahmeverfahren haben
                            nicht weniger Ausnahmen, sondern nur keine Übersicht darüber. Prüfer
                            wissen das und fragen gezielt danach.
                        </p>
                    </div>
                </div>

                <h2>Ablauf</h2>
                <ol class="phase-list">
                    <li>
                        <span class="phase-week">Monat 1</span>
                        <h3>Bestand und Zuschnitt</h3>
                        <p>Vorhandene Regelungen sichten, Doppelungen und Widersprüche
                           identifizieren, Zielbild der Dokumentenlandschaft abstimmen. Ergebnis:
                           Liste, was bleibt, was zusammengeführt und was neu geschrieben wird.</p>
                    </li>
                    <li>
                        <span class="phase-week">Monat 2–3</span>
                        <h3>Entwurf und Abstimmung</h3>
                        <p>Je Dokument ein Entwurf, danach ein Abstimmungstermin mit den
                           Betroffenen. Erfahrungsgemäß sind zwei Runden nötig – die erste klärt
                           Missverständnisse, die zweite Inhalte.</p>
                    </li>
                    <li>
                        <span class="phase-week">Monat 3–4</span>
                        <h3>Entscheidung und Inkraftsetzung</h3>
                        <p>Freigabe durch die Geschäftsführung mit Datum und Unterschrift. Ohne
                           diesen Schritt bleibt alles ein Entwurf – auch nach zwei Jahren.</p>
                    </li>
                    <li>
                        <span class="phase-week">Monat 4–5</span>
                        <h3>Bekanntmachung und Betrieb</h3>
                        <p>Kurzschulung für Führungskräfte (60 Minuten), Ablage an einem Ort,
                           Wiedervorlage für die jährliche Prüfung, erste Gremiensitzung
                           moderiert. Danach läuft es ohne mich.</p>
                    </li>
                </ol>

                <h2>Preis</h2>
                <p>
                    <strong>22.000 €</strong> für einen Standort mit sechs Kerndokumenten,
                    <strong>32.000 €</strong> im Regelfall mit acht bis zehn Dokumenten und
                    Gremienaufbau, <strong>bis 45.000 €</strong> bei mehreren Gesellschaften mit
                    unterschiedlichen Ausgangslagen und Abstimmung über Ländergrenzen.
                </p>

                <div class="callout is-legal">
                    <span class="callout-icon"><i data-icon="scale" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Mitbestimmung und Recht</h3>
                        <p>
                            Regelungen zu Nutzung, Protokollierung und Zugriffen berühren
                            regelmäßig Mitbestimmungsrechte und Datenschutz. Ich bereite die
                            fachliche Substanz vor und begleite Gespräche mit dem Betriebsrat.
                            Die arbeitsrechtliche Bewertung, die Betriebsvereinbarung und die
                            datenschutzrechtliche Prüfung gehören zu Ihrer Kanzlei bzw. Ihrem
                            Datenschutzbeauftragten.
                        </p>
                    </div>
                </div>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['leistungen/rollen-verantwortlichkeiten.php', 'themen/it-dokumentation.php', 'leistungen/kontrollframework.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
