<?php
$page = [
    'title'       => 'IT Service Management Setup',
    'description' => 'Servicekatalog, Serviceverantwortliche, Incident- und Change-Prozess sowie belastbare Kennzahlen. ITSM-Einführung mit Mittelstandszuschnitt statt ITIL-Vollausbau. 20.000 bis 42.000 € netto.',
    'section'     => 'leistungen',
    'path'        => 'leistungen/service-management.php',
    'crumbs'      => [['Leistungen', 'leistungen/'], ['IT Service Management', null]],
    'hero'        => [
        'kicker' => 'Leistung · Aufbau',
        'h1'     => 'Erst wissen, <span class="accent">was Sie liefern</span> – dann darüber sprechen',
        'lead'   => 'Die meisten IT-Bereiche können nicht benennen, welche Services sie erbringen. Damit fehlt die Grundlage für Verfügbarkeitszusagen, für Kostenzuordnung, für Kennzahlen und für jedes ernsthafte Gespräch mit dem Fachbereich.',
        'actions' => [
            ['ITSM-Setup anfragen', 'kontakt.php', 'primary'],
            ['Kennzahlen ansehen', 'themen/it-kennzahlen.php', 'ghost'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Dauer', '3–5 Monate'],
    ['Aufwand bei Ihnen', '5–8 Std./Woche'],
    ['Ergebnis', 'Servicekatalog + Prozesse + Kennzahlen'],
    ['Preis', '20.000 – 42.000 € netto'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Der Servicekatalog kommt zuerst</h2>
                <p>
                    Ein IT-Service ist etwas, das ein Fachbereich nutzt und benennen kann –
                    „ERP-Nutzung“, „Arbeitsplatz“, „E-Mail“, „Fertigungsdatenerfassung“,
                    „Fernzugriff“. Ein Server ist kein Service. Eine Lizenz ist kein Service.
                    Diese Unterscheidung klingt akademisch und entscheidet in der Praxis darüber,
                    ob der Katalog benutzbar wird.
                </p>
                <p>
                    Für ein mittelständisches Unternehmen ergeben sich typischerweise
                    <strong>12 bis 25 Services</strong>. Je Service werden vier Dinge festgelegt:
                </p>
                <div class="card-grid cols-2">
                    <div class="card is-paper">
                        <h3 class="card-title">Serviceverantwortlicher</h3>
                        <p class="card-text">Eine benannte Rolle, die für Verfügbarkeit, Änderungen und Kosten dieses Service geradesteht.</p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Kritikalität</h3>
                        <p class="card-text">Wie lange kann das Unternehmen ohne diesen Service arbeiten? Daraus folgen Wiederanlaufziele und Investitionen.</p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Servicezeit &amp; Reaktion</h3>
                        <p class="card-text">Wann wird gearbeitet, wie schnell wird reagiert? Keine Verfügbarkeitsversprechen, die niemand messen kann.</p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Abhängigkeiten</h3>
                        <p class="card-text">Welche Systeme, Dienstleister und Schnittstellen trägt dieser Service? Grundlage für Notfall- und Risikoarbeit.</p>
                    </div>
                </div>

                <div class="callout is-warning">
                    <span class="callout-icon"><i data-icon="alert-triangle" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Verfügbarkeit in Prozent – ein häufiger Fehler</h3>
                        <p>
                            „99,5 % Verfügbarkeit“ klingt professionell und ist im Mittelstand
                            meist eine Zusage ins Blaue: Es fehlt die Messung, es fehlt die
                            Definition des Ausfalls, und die Zahl hat keine Konsequenz. Sinnvoller
                            sind Servicezeiten und Reaktionszeiten, die tatsächlich gemessen und
                            berichtet werden können.
                        </p>
                    </div>
                </div>

                <h2>Die Prozesse, die dazugehören</h2>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Prozess</th><th scope="col">Was eingeführt wird</th><th scope="col">Nachweiswert</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Incident</strong></td>
                                <td>Kategorien, Prioritätsmatrix, Eskalationsstufen, Kommunikationsregel bei Großstörung</td>
                                <td>Ticketverlauf</td>
                            </tr>
                            <tr>
                                <td><strong>Service Request</strong></td>
                                <td>Standardanfragen mit Genehmigungsweg – Zugang, Gerät, Software</td>
                                <td>Genehmigung im Ticket</td>
                            </tr>
                            <tr>
                                <td><strong>Change</strong></td>
                                <td>Drei Kategorien (Standard, Normal, Notfall), Freigeber, Rückfallplan, Dokumentation</td>
                                <td>Freigabevermerk</td>
                            </tr>
                            <tr>
                                <td><strong>Problem</strong></td>
                                <td>Schlanke Ursachenanalyse für wiederkehrende Störungen – kein eigener Apparat</td>
                                <td>Analyseprotokoll</td>
                            </tr>
                            <tr>
                                <td><strong>Konfiguration</strong></td>
                                <td>Welche Systeme tragen welchen Service – so grob wie möglich, so genau wie nötig</td>
                                <td>Inventar mit Pflegenachweis</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h2>Kennzahlen, die man wirklich messen kann</h2>
                <p>
                    Kennzahlen sind der Teil, der am häufigsten misslingt – weil Zahlen definiert
                    werden, die niemand aus dem System bekommt. Meine Regel: Eine Kennzahl kommt
                    nur in den Bericht, wenn sie ohne manuelle Pflege entsteht.
                </p>
                <ul class="checklist">
                    <li><strong>Tickets nach Kategorie und Trend</strong> – zeigt, wo Arbeit entsteht</li>
                    <li><strong>Anteil Erstlösung</strong> – Aussage über Wissensstand im Service Desk</li>
                    <li><strong>Reaktionszeit nach Priorität</strong> – prüfbar, im Gegensatz zur Lösungszeit</li>
                    <li><strong>Changes mit Rückfall</strong> – der ehrlichste Qualitätsindikator</li>
                    <li><strong>Wiederkehrende Störungen je Service</strong> – Grundlage für Investitionsentscheidungen</li>
                    <li><strong>Offene Anforderungen je Fachbereich</strong> – nimmt Druck aus Priorisierungsdebatten</li>
                </ul>
                <p>
                    Für die Auswertung nutze ich vorhandene Datenquellen: Ticketsystem-Datenbank
                    per SQL, Standardberichte oder ein einfaches Dashboard. Erfahrung mit SQL,
                    Oracle, MySQL und Tableau hilft an dieser Stelle mehr als jede
                    Kennzahlensammlung aus dem Lehrbuch.
                </p>

                <h2>Ablauf</h2>
                <ol class="phase-list">
                    <li>
                        <span class="phase-week">Monat 1</span>
                        <h3>Serviceschnitt</h3>
                        <p>Workshops mit IT und Fachbereichen: Was nutzen Sie eigentlich? Ergebnis
                           ist ein Katalogentwurf in der Sprache der Nutzer, nicht der Technik.</p>
                    </li>
                    <li>
                        <span class="phase-week">Monat 2</span>
                        <h3>Verantwortliche und Kritikalität</h3>
                        <p>Je Service ein Verantwortlicher, abgestimmt mit den Betroffenen.
                           Kritikalitätseinstufung gemeinsam mit den Fachbereichen – das ist
                           gleichzeitig die Vorarbeit für das Notfallmanagement.</p>
                    </li>
                    <li>
                        <span class="phase-week">Monat 3–4</span>
                        <h3>Prozesse und Werkzeug</h3>
                        <p>Prozessbeschreibungen, Umsetzung im Ticketsystem, Kategorien und
                           Prioritätsmatrix, Genehmigungswege, Schulung des Service Desks.</p>
                    </li>
                    <li>
                        <span class="phase-week">Monat 4–5</span>
                        <h3>Bericht und Übergabe</h3>
                        <p>Erster Servicebericht für die Geschäftsführung, Feinschliff der
                           Kennzahlen, Übergabe an die IT-Leitung mit Betriebsanleitung.</p>
                    </li>
                </ol>

                <p>
                    <strong>Preis:</strong> 20.000 € netto bei vorhandenem, brauchbarem
                    Ticketsystem und einem Standort; 30.000 € im Regelfall; bis 42.000 € bei
                    mehreren Standorten, Werkzeugwechsel oder Servicekatalog über
                    Gesellschaftsgrenzen hinweg.
                </p>

                <div class="callout">
                    <span class="callout-icon"><i data-icon="settings" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Werkzeugauswahl</h3>
                        <p>
                            Ich verkaufe keine Software und erhalte keine Provisionen. Wenn ein
                            Werkzeugwechsel nötig ist, begleite ich die Auswahl mit einem
                            Anforderungskatalog und einer nachvollziehbaren Bewertung – die
                            Entscheidung und der Vertrag bleiben bei Ihnen.
                        </p>
                    </div>
                </div>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['leistungen/demand-management.php', 'themen/it-kennzahlen.php', 'themen/asset-applikationsmanagement.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
