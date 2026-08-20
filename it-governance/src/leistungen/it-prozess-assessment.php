<?php
$page = [
    'title'       => 'IT-Prozess-Assessment',
    'description' => 'Reifegradbewertung der zentralen IT-Prozesse: Incident, Change, Demand, Zugriffsverwaltung, Auslagerung, Asset- und Konfigurationsmanagement. Bewertet wird der gelebte Prozess, nicht das Prozessbild. 12.000 bis 22.000 € netto.',
    'section'     => 'leistungen',
    'path'        => 'leistungen/it-prozess-assessment.php',
    'crumbs'      => [['Leistungen', 'leistungen/'], ['IT-Prozess-Assessment', null]],
    'hero'        => [
        'kicker' => 'Leistung · Bewertung',
        'h1'     => 'Bewertet wird der gelebte Prozess, <span class="accent">nicht das Prozessbild</span>',
        'lead'   => 'In fast jedem Unternehmen gibt es eine Prozesslandkarte. In den wenigsten beschreibt sie, was tatsächlich passiert. Dieses Assessment misst die Differenz – und sagt, welche Prozesse diese Differenz überhaupt rechtfertigt.',
        'actions' => [
            ['Assessment anfragen', 'kontakt.php', 'primary'],
            ['Reifegradmodell ansehen', 'wissen/reifegradmodell.php', 'ghost'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Dauer', '4–6 Wochen'],
    ['Aufwand bei Ihnen', '2–3 Std./Woche'],
    ['Ergebnis', 'Reifegradprofil + Sollbild'],
    ['Preis', '12.000 – 22.000 € netto'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Welche Prozesse betrachtet werden</h2>
                <p>
                    Nicht alle. Ein mittelständischer IT-Bereich braucht keine vierzig
                    beschriebenen Prozesse – er braucht sechs bis acht, die funktionieren.
                    Betrachtet werden die Prozesse, an denen Prüfer, Betrieb und Fachbereiche
                    gleichermaßen hängen:
                </p>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th scope="col">Prozess</th>
                                <th scope="col">Kernfrage</th>
                                <th scope="col">Typische Schwachstelle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Incident &amp; Service Request</strong></td>
                                <td>Wie kommen Störungen und Anfragen herein, wer priorisiert?</td>
                                <td>Zuruf am Schreibtisch, kein Ticket, keine Historie</td>
                            </tr>
                            <tr>
                                <td><strong>Change</strong></td>
                                <td>Wer genehmigt Änderungen am Produktivsystem?</td>
                                <td>Notfall-Changes als Regelfall, Freigabe im Nachhinein</td>
                            </tr>
                            <tr>
                                <td><strong>Demand</strong></td>
                                <td>Wie werden neue Anforderungen bewertet und priorisiert?</td>
                                <td>Existiert nicht; die lauteste Abteilung gewinnt</td>
                            </tr>
                            <tr>
                                <td><strong>Zugriffsverwaltung</strong></td>
                                <td>Wie entstehen, ändern und enden Berechtigungen?</td>
                                <td>Eintritt geregelt, Austritt und Wechsel nicht</td>
                            </tr>
                            <tr>
                                <td><strong>Asset &amp; Konfiguration</strong></td>
                                <td>Was ist im Einsatz, wer besitzt es, bis wann wird es unterstützt?</td>
                                <td>Drei Listen, drei Wahrheiten, keine gepflegt</td>
                            </tr>
                            <tr>
                                <td><strong>Auslagerung</strong></td>
                                <td>Wer steuert Dienstleister und prüft ihre Leistung?</td>
                                <td>Vertrag im Einkauf, Steuerung nirgends</td>
                            </tr>
                            <tr>
                                <td><strong>Sicherung &amp; Wiederanlauf</strong></td>
                                <td>Was wird gesichert, und wurde das Zurückholen getestet?</td>
                                <td>Sicherung läuft, Rücksicherung nie geprobt</td>
                            </tr>
                            <tr>
                                <td><strong>Projekt &amp; Einführung</strong></td>
                                <td>Wie kommt Neues in den Betrieb?</td>
                                <td>Übergabe an den Betrieb findet nicht statt</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h2>Die fünf Reifegradstufen</h2>
                <p>
                    Bewertet wird je Prozess auf einer fünfstufigen Skala. Wichtig: Stufe 5 ist
                    kein Ziel. Für die meisten mittelständischen Unternehmen ist Stufe 3 bei den
                    meisten Prozessen und Stufe 4 bei zwei oder drei prüfungsrelevanten Prozessen
                    genau richtig. Alles darüber kostet mehr, als es einbringt.
                </p>

                <div class="maturity">
                    <div class="maturity-step">
                        <span class="maturity-num">1</span>
                        <div>
                            <h3>Zufällig</h3>
                            <p>Der Prozess passiert, aber jedes Mal anders. Ergebnis hängt an der Person, die ihn ausführt.</p>
                        </div>
                    </div>
                    <div class="maturity-step">
                        <span class="maturity-num">2</span>
                        <div>
                            <h3>Wiederholbar</h3>
                            <p>Eingespielte Praxis, mündlich weitergegeben. Funktioniert – solange niemand ausfällt oder neu ist.</p>
                        </div>
                    </div>
                    <div class="maturity-step is-target">
                        <span class="maturity-num">3</span>
                        <div>
                            <h3>Definiert</h3>
                            <p>Beschrieben, freigegeben, bekannt. Neue Mitarbeitende können ihn anhand der Beschreibung ausführen. <strong>Regelziel im Mittelstand.</strong></p>
                        </div>
                    </div>
                    <div class="maturity-step is-target">
                        <span class="maturity-num">4</span>
                        <div>
                            <h3>Gesteuert</h3>
                            <p>Wird gemessen, Abweichungen fallen auf, Nachweise entstehen automatisch. <strong>Ziel für prüfungsrelevante Prozesse.</strong></p>
                        </div>
                    </div>
                    <div class="maturity-step">
                        <span class="maturity-num">5</span>
                        <div>
                            <h3>Optimierend</h3>
                            <p>Wird systematisch verbessert, Kennzahlen steuern Veränderungen. Für die meisten Mittelständler unwirtschaftlich.</p>
                        </div>
                    </div>
                </div>

                <h2>Wie erhoben wird</h2>
                <ol class="steps">
                    <li>
                        <h3>Prozessdurchsprache</h3>
                        <p>Je Prozess 60 bis 90 Minuten mit den Ausführenden, entlang eines
                           konkreten Falls: „Zeigen Sie mir den letzten Change vom Dienstag.“
                           Das deckt mehr auf als jede abstrakte Prozessfrage.</p>
                    </li>
                    <li>
                        <h3>Datenauswertung im System</h3>
                        <p>Wo ein Ticket- oder ERP-System vorhanden ist, schaue ich in die Daten:
                           Wie viele Changes ohne Freigabe? Wie viele Tickets ohne Kategorie?
                           Wie lange offen? Auswertbar per SQL oder Standardbericht – Zahlen
                           schlagen Meinungen.</p>
                    </li>
                    <li>
                        <h3>Schnittstellenprüfung</h3>
                        <p>Die meisten Prozesse scheitern nicht in der Mitte, sondern an den
                           Übergängen: Demand zu Projekt, Projekt zu Betrieb, Betrieb zu
                           Dienstleister. Diese Übergänge werden gesondert betrachtet.</p>
                    </li>
                </ol>

                <h2>Ergebnis</h2>
                <ul class="checklist">
                    <li><strong>Reifegradprofil</strong> je Prozess mit Ist-Stufe, Zielstufe und Begründung</li>
                    <li><strong>Prozesssteckbriefe</strong> für die betrachteten Prozesse: Auslöser, Schritte, Rollen, Kennzahl, Nachweis – je Prozess ein bis zwei Seiten, nicht zwanzig</li>
                    <li><strong>Schwachstellenliste</strong> mit Wirkung auf Betrieb, Prüfung und Kosten</li>
                    <li><strong>Umsetzungsreihenfolge</strong>: welcher Prozess zuerst, welcher später, welcher gar nicht</li>
                </ul>

                <div class="callout">
                    <span class="callout-icon"><i data-icon="zap" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Häufigste Erkenntnis</h3>
                        <p>
                            In den meisten Fällen ist nicht die Prozessqualität das Problem,
                            sondern die fehlende Grenze zwischen den Prozessen. Wenn eine
                            Anforderung mal als Ticket, mal als Projekt, mal als Change und mal
                            als E-Mail an den Admin läuft, hilft keine Prozessbeschreibung – es
                            fehlt der Eingang. Das ist der Grund, warum
                            <a href="/leistungen/demand-management.php">Demand Management</a> so
                            oft der wirksamste erste Schritt ist.
                        </p>
                    </div>
                </div>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['leistungen/demand-management.php', 'leistungen/service-management.php', 'themen/prozessharmonisierung.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
