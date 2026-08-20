<?php
$page = [
    'title'       => 'IT-Kennzahlen und Management-Reporting',
    'description' => 'Zwölf IT-Kennzahlen, die sich ohne manuelle Pflege messen lassen, ein Managementbericht auf einer Seite und die Frage, welche Zahlen eine Geschäftsführung tatsächlich zum Entscheiden braucht.',
    'section'     => 'themen',
    'path'        => 'themen/it-kennzahlen.php',
    'crumbs'      => [['Themen', 'themen/'], ['IT-Kennzahlen & Reporting', null]],
    'hero'        => [
        'kicker' => 'Thema · Steuerung',
        'h1'     => 'Eine Seite im Monat schlägt <span class="accent">jedes Dashboard</span>',
        'lead'   => 'IT-Reporting scheitert an zwei Extremen: gar keine Zahlen – oder ein Dashboard mit vierzig Kacheln, das niemand öffnet. Dazwischen liegt das, was Steuerung ermöglicht: wenige Kennzahlen mit Konsequenz.',
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Empfehlung', '8–12 Kennzahlen'],
    ['Bericht', '1 Seite, monatlich'],
    ['Regel', 'keine manuelle Pflege'],
    ['Adressat', 'Geschäftsführung'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Die drei Fragen, die eine Geschäftsführung wirklich hat</h2>
                <p>
                    Bevor man Kennzahlen auswählt, lohnt der Blick auf den Adressaten. Eine
                    Geschäftsführung möchte drei Dinge wissen – alles Übrige ist Detail:
                </p>
                <ol class="steps">
                    <li>
                        <h3>Läuft der Betrieb?</h3>
                        <p>Gab es Ausfälle, wie lange, mit welcher Folge? Sind wir sicherer oder
                           unsicherer geworden?</p>
                    </li>
                    <li>
                        <h3>Kommen wir voran?</h3>
                        <p>Was ist aus den beschlossenen Vorhaben und Maßnahmen geworden – im
                           Verhältnis zu Zeit und Budget?</p>
                    </li>
                    <li>
                        <h3>Wo brauche ich eine Entscheidung?</h3>
                        <p>Welche Risiken, Engpässe oder Investitionen brauchen jetzt einen
                           Beschluss – und was passiert, wenn er ausbleibt?</p>
                    </li>
                </ol>

                <h2>Zwölf Kennzahlen, die man messen kann</h2>
                <p>
                    Ausgewählt nach einem einzigen Kriterium: Sie entstehen aus vorhandenen
                    Systemen, ohne dass jemand eine Liste pflegt.
                </p>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Kennzahl</th><th scope="col">Quelle</th><th scope="col">Was sie steuert</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Ungeplante Ausfallzeit kritischer Services</td><td>Ticketsystem, Monitoring</td><td>Investitionen in Verfügbarkeit</td></tr>
                            <tr><td>Anzahl Störungen nach Kategorie (Trend)</td><td>Ticketsystem</td><td>Wo Ursachenarbeit lohnt</td></tr>
                            <tr><td>Erstlösungsquote</td><td>Ticketsystem</td><td>Wissen und Besetzung im Service Desk</td></tr>
                            <tr><td>Reaktionszeit nach Priorität</td><td>Ticketsystem</td><td>Erfüllung von Zusagen</td></tr>
                            <tr><td>Changes gesamt / davon Notfall / mit Rückfall</td><td>Ticketsystem</td><td>Planungsqualität und Risiko</td></tr>
                            <tr><td>Offene Anforderungen je Fachbereich</td><td>Demand-Liste</td><td>Priorisierungsdiskussion mit Fakten</td></tr>
                            <tr><td>Systeme ohne Herstellerunterstützung</td><td>Inventar</td><td>Investitionsplanung, Sicherheitsrisiko</td></tr>
                            <tr><td>Patchstand kritischer Systeme</td><td>Managementwerkzeug</td><td>Angriffsfläche</td></tr>
                            <tr><td>Konten mit erweiterten Rechten</td><td>Verzeichnisdienst</td><td>Innentäterrisiko, Prüfungsfeststellungen</td></tr>
                            <tr><td>Erfolgreiche Rücksicherungstests</td><td>Testprotokolle</td><td>Nachweisbare Wiederanlauffähigkeit</td></tr>
                            <tr><td>Umsetzungsgrad beschlossener Maßnahmen</td><td>Maßnahmenliste</td><td>Fortschritt in der Governance</td></tr>
                            <tr><td>IT-Kosten je Mitarbeiter oder je Umsatzmillion</td><td>Buchhaltung</td><td>Einordnung im Zeitverlauf, nicht im Branchenvergleich</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout is-warning">
                    <span class="callout-icon"><i data-icon="alert-triangle" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Vorsicht mit Branchenvergleichen</h3>
                        <p>
                            „IT-Kosten in Prozent vom Umsatz“ wird gern mit Studienwerten
                            verglichen. Das führt fast immer in die Irre: Der Wert hängt an
                            Fertigungstiefe, Auslagerungsgrad, Investitionszyklus und daran, was
                            überhaupt als IT-Kosten gebucht wird. Als Zeitreihe im eigenen Haus
                            ist die Kennzahl nützlich, als Benchmark selten.
                        </p>
                    </div>
                </div>

                <h2>Der Managementbericht auf einer Seite</h2>
                <p>
                    Aufbau, der sich in der Praxis bewährt hat – bewusst textarm, mit Ampeln nur
                    dort, wo sie eine Konsequenz haben:
                </p>
                <div class="card-grid cols-2">
                    <div class="card is-paper">
                        <h3 class="card-title">Block 1: Betrieb (oberes Drittel)</h3>
                        <p class="card-text">
                            Ausfallzeit, Störungstrend, ein Satz zu Auffälligkeiten. Wenn nichts
                            passiert ist, steht dort ein Satz – nicht drei Diagramme.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Block 2: Vorhaben und Maßnahmen</h3>
                        <p class="card-text">
                            Fünf bis acht Zeilen: Vorhaben, Status, Termin, Ampel. Rote Ampeln
                            immer mit Satz zur Ursache und zum nötigen Beschluss.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Block 3: Risiken und Sicherheit</h3>
                        <p class="card-text">
                            Veränderungen in der Risikoliste, Sicherheitsvorfälle, Ergebnisse von
                            Kontrollen. Keine Wiederholung der vollständigen Liste.
                        </p>
                    </div>
                    <div class="card is-navy">
                        <h3 class="card-title">Block 4: Entscheidungsbedarf</h3>
                        <p class="card-text">
                            Der wichtigste Block, ganz unten oder ganz oben: Was ist jetzt zu
                            entscheiden, welche Optionen gibt es, was empfiehlt die IT-Leitung?
                            Ohne diesen Block ist der Bericht Information, keine Steuerung.
                        </p>
                    </div>
                </div>

                <h2>Woher die Zahlen kommen</h2>
                <p>
                    In den meisten mittelständischen Unternehmen sind die Daten vorhanden – im
                    Ticketsystem, im Verzeichnisdienst, im Monitoring, in der Buchhaltung. Was
                    fehlt, ist die Auswertung. Mit SQL-Abfragen gegen die Datenbank des
                    Ticketsystems, Standardberichten und einem einfachen Auswertungswerkzeug
                    (etwa Tableau, Power BI oder auch nur einer gut gebauten Tabelle) lässt sich
                    das in wenigen Tagen aufsetzen.
                </p>
                <p>
                    Entscheidend ist die Automatisierung: Ein Bericht, der jeden Monat drei
                    Stunden Handarbeit kostet, wird im vierten Monat nicht mehr erstellt. Einer,
                    der auf Knopfdruck entsteht, überlebt auch einen vollen Terminkalender.
                </p>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['leistungen/service-management.php', 'leistungen/governance-betreuung.php', 'themen/it-risikomanagement.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
