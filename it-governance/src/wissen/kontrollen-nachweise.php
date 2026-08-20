<?php
$page = [
    'title'       => 'Von der Kontrolle zum Nachweis',
    'description' => 'Warum Prüfer Belege sehen wollen und keine Absichten: Was einen Nachweis brauchbar macht, welche fünf Nachweisarten es gibt, wie Nachweise im Arbeitsablauf entstehen und welche typischen Fehler zu Feststellungen führen.',
    'section'     => 'wissen',
    'path'        => 'wissen/kontrollen-nachweise.php',
    'crumbs'      => [['Wissen', 'wissen/'], ['Von der Kontrolle zum Nachweis', null]],
    'hero'        => [
        'kicker' => 'Leitfaden · Kernthema',
        'h1'     => 'Was nicht belegt ist, <span class="accent">hat nicht stattgefunden</span>',
        'lead'   => 'Dieser Satz klingt hart und ist der Kern jeder Prüfungslogik. Er bedeutet nicht, dass Prüfer misstrauisch sind – sondern dass sie nur bestätigen können, was sie sehen. Wer das verstanden hat, baut Governance anders auf.',
    ],
];
include __DIR__ . '/../partials/header.php';

$asideCta = [
    'title' => 'Kontrollen aufbauen',
    'text'  => 'Ein Kontrollframework mit 15 bis 40 Kontrollen, deren Nachweise im Betrieb entstehen.',
    'link'  => ['Kontrollframework ansehen', 'leistungen/kontrollframework.php'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Die Kette, um die es geht</h2>
<?php
$kette = [
    ['Anforderung', 'Gesetz, Norm, Kunde, eigenes Risiko'],
    ['Regelung', 'Was bei uns gilt'],
    ['Prozess', 'Wie es abläuft'],
    ['Kontrolle', 'Wer prüft, wie oft'],
    ['Nachweis', 'Der Beleg dafür'],
];
$ketteLabel = 'Von der Anforderung zum Nachweis';
include __DIR__ . '/../partials/kette.php';
?>
                <p>
                    Jedes Glied hängt vom vorigen ab. Ein Nachweis ohne Kontrolle ist ein Zufall.
                    Eine Kontrolle ohne Prozess prüft nichts Definiertes. Ein Prozess ohne
                    Regelung ist Gewohnheit. Und eine Regelung ohne Anforderung ist Bürokratie.
                </p>
                <p>
                    Prüfungen setzen am hinteren Ende an und arbeiten sich nach vorn: Zeigen Sie
                    mir den Nachweis – wer hat das durchgeführt – nach welcher Regel – warum gilt
                    diese Regel? Wer die Kette nach vorn geschlossen hat, kommt gut durch.
                </p>

                <h2>Was einen Nachweis brauchbar macht</h2>
                <p>
                    Vier Eigenschaften, die alle vorliegen müssen. Sie sind der Grund, warum ein
                    Screenshot oft nicht genügt:
                </p>
                <div class="card-grid cols-2">
                    <div class="card is-paper">
                        <h3 class="card-title">Datiert</h3>
                        <p class="card-text">Wann wurde die Handlung durchgeführt? Ein Dokument
                            ohne Datum belegt nichts – auch nicht mit dem Änderungsdatum der Datei.</p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Zugeordnet</h3>
                        <p class="card-text">Wer hat gehandelt? Eine Freigabe ohne erkennbaren
                            Freigebenden ist keine Freigabe.</p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Nachvollziehbar</h3>
                        <p class="card-text">Woher stammen die Daten, welcher Zeitraum, welches
                            System? Ein Auszug ohne Quellangabe lässt sich nicht prüfen.</p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Unveränderbar genug</h3>
                        <p class="card-text">Nachweise in einer Tabelle, die jeder überschreiben
                            kann, wiegen weniger als Systemprotokolle oder Ticketverläufe.</p>
                    </div>
                </div>

                <h2>Die fünf Nachweisarten – von schwach bis stark</h2>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Art</th><th scope="col">Beispiel</th><th scope="col">Aussagekraft</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>1 · Aussage</strong></td>
                                <td>„Wir machen das quartalsweise.“</td>
                                <td class="no">keine</td>
                            </tr>
                            <tr>
                                <td><strong>2 · Dokument</strong></td>
                                <td>Richtlinie, die die Kontrolle vorschreibt</td>
                                <td>belegt die Absicht, nicht die Durchführung</td>
                            </tr>
                            <tr>
                                <td><strong>3 · Aufzeichnung</strong></td>
                                <td>Ausgefüllte Checkliste mit Datum und Namen</td>
                                <td>belegt die Durchführung</td>
                            </tr>
                            <tr>
                                <td><strong>4 · Systemnachweis</strong></td>
                                <td>Ticketverlauf, Freigabeschritt im Workflow, Protokolldatei</td>
                                <td class="yes">belegt Durchführung und Zeitpunkt manipulationsärmer</td>
                            </tr>
                            <tr>
                                <td><strong>5 · Wirksamkeitsnachweis</strong></td>
                                <td>Kontrolle hat eine Abweichung gefunden, die behoben und dokumentiert wurde</td>
                                <td class="yes">belegt, dass die Kontrolle wirkt</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout is-ok">
                    <span class="callout-icon"><i data-icon="check-circle" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Der stärkste Nachweis ist der gefundene Fehler</h3>
                        <p>
                            Viele Unternehmen dokumentieren Kontrollen nur, wenn alles in Ordnung
                            war – aus einem verständlichen Reflex heraus. Für die Prüfung ist das
                            Gegenteil überzeugend: Eine Kontrolle, die dreimal im Jahr Abweichungen
                            findet und deren Behebung dokumentiert ist, beweist Wirksamkeit. Eine,
                            die seit vier Jahren nie etwas findet, weckt Zweifel – entweder wird
                            sie nicht ernsthaft durchgeführt, oder sie prüft das Falsche.
                        </p>
                    </div>
                </div>

                <h2>Nachweise, die von allein entstehen</h2>
                <p>
                    Der entscheidende Konstruktionsgrundsatz: Wenn ein Nachweis zusätzliche Arbeit
                    kostet, entsteht er auf Dauer nicht. Vier Orte, an denen Nachweise ohnehin
                    anfallen:
                </p>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Ort</th><th scope="col">Wie der Nachweis entsteht</th><th scope="col">Beispiel</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Ticketsystem</td><td>Wiederkehrendes Ticket mit Checkliste und Anhang</td><td>Quartalsprüfung privilegierter Konten</td></tr>
                            <tr><td>Gremienprotokoll</td><td>Fester Tagesordnungspunkt mit Beschluss</td><td>Risikoübersicht zur Kenntnis genommen</td></tr>
                            <tr><td>Systembericht</td><td>Automatisch erzeugt und archiviert</td><td>Patchstand, Sicherungsergebnisse</td></tr>
                            <tr><td>Workflow-Schritt</td><td>Der Genehmigungsschritt ist selbst der Beleg</td><td>Berechtigungsantrag mit Freigabe</td></tr>
                        </tbody>
                    </table>
                </div>

                <h2>Sechs Fehler, die regelmäßig zu Feststellungen führen</h2>
                <ol class="steps">
                    <li>
                        <h3>Nachweis nur im Postfach</h3>
                        <p>Die Freigabe existiert – als E-Mail beim Abteilungsleiter. Nach seinem
                           Ausscheiden ist sie weg. Nachweise gehören an einen definierten Ort mit
                           Aufbewahrungsregel.</p>
                    </li>
                    <li>
                        <h3>Aufbewahrung zu kurz</h3>
                        <p>Protokolle werden nach sieben oder dreißig Tagen automatisch gelöscht,
                           die Prüfung betrachtet aber das gesamte Geschäftsjahr. Die
                           Aufbewahrungsdauer muss zum Prüfungszeitraum passen.</p>
                    </li>
                    <li>
                        <h3>Screenshot ohne Kontext</h3>
                        <p>Ein Bildschirmfoto ohne Datum, ohne System, ohne Filterangabe. Es zeigt
                           etwas – aber nicht, wann und woher.</p>
                    </li>
                    <li>
                        <h3>Sammelnachweis für Einzelfälle</h3>
                        <p>„Alle Austritte wurden bearbeitet“ als pauschale Aussage. Geprüft wird
                           aber der Einzelfall: Wann wurde welches Konto deaktiviert?</p>
                    </li>
                    <li>
                        <h3>Nachträgliche Erstellung</h3>
                        <p>Vier Wochen vor der Prüfung werden Protokolle „nachgeholt“. Das fällt
                           fast immer auf – an Metadaten, an gleichlautenden Formulierungen, an
                           fehlenden Zwischenständen. Und es verwandelt eine Feststellung in ein
                           ernstes Problem.</p>
                    </li>
                    <li>
                        <h3>Kontrolle ohne benannten Verantwortlichen</h3>
                        <p>„Die IT prüft“ – wer genau? Ohne Namen wird die Kontrolle in
                           arbeitsreichen Wochen nicht durchgeführt, und niemand merkt es.</p>
                    </li>
                </ol>

                <h2>Der Aufbau in der Praxis</h2>
                <p>
                    Eine gute Kontrollbeschreibung passt in fünf Zeilen und beantwortet alles, was
                    ein Prüfer fragt:
                </p>
                <div class="callout">
                    <span class="callout-icon"><i data-icon="list-checks" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Beispiel einer Kontrollbeschreibung</h3>
                        <p>
                            <strong>Ziel:</strong> Nur berechtigte Personen verfügen über
                            administrative Rechte.<br>
                            <strong>Durchführung:</strong> Die IT-Leitung zieht quartalsweise die
                            Liste aller Konten mit administrativen Rechten aus dem Verzeichnisdienst
                            und gleicht sie gegen die Sollliste ab.<br>
                            <strong>Verantwortlich:</strong> IT-Leitung, Vertretung: Systemadministrator.<br>
                            <strong>Nachweis:</strong> Wiederkehrendes Ticket „Adminprüfung Q…“ mit
                            angehängtem Systemexport und Vermerk zu Abweichungen.<br>
                            <strong>Bei Abweichung:</strong> Entzug binnen fünf Arbeitstagen oder
                            dokumentierte Begründung; Meldung im IT-Steuerkreis.
                        </p>
                    </div>
                </div>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['leistungen/kontrollframework.php', 'wissen/audit-vorbereitung.php', 'leistungen/audit-readiness.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
