<?php
$page = [
    'title'       => 'IT-Kontrollframework aufbauen',
    'description' => 'Internes Kontrollsystem für die IT: 15 bis 40 wirksame Kontrollen, beschrieben, zugeordnet, terminiert – mit Nachweisen, die im laufenden Betrieb entstehen. 16.000 bis 34.000 € netto.',
    'section'     => 'leistungen',
    'path'        => 'leistungen/kontrollframework.php',
    'crumbs'      => [['Leistungen', 'leistungen/'], ['IT-Kontrollframework', null]],
    'hero'        => [
        'kicker' => 'Leistung · Aufbau',
        'h1'     => 'Wenige Kontrollen, die <span class="accent">wirklich laufen</span>',
        'lead'   => 'Kontrollframeworks scheitern fast nie am Konzept, sondern am dritten Quartal: Dann füllt niemand mehr die Matrix aus. Deshalb baue ich Kontrollen so, dass ihr Nachweis im Arbeitsablauf entsteht – und nicht durch zusätzliche Pflege.',
        'actions' => [
            ['Kontrollframework anfragen', 'kontakt.php', 'primary'],
            ['Nachweislogik verstehen', 'wissen/kontrollen-nachweise.php', 'ghost'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Dauer', '2–4 Monate'],
    ['Aufwand bei Ihnen', '3–5 Std./Woche'],
    ['Ergebnis', 'Kontrollkatalog in Betrieb'],
    ['Preis', '16.000 – 34.000 € netto'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Was eine Kontrolle ist – und was nicht</h2>
                <p>
                    Eine Kontrolle ist eine wiederkehrende Handlung, die prüft, ob eine Regel
                    eingehalten wurde, und die dabei einen Beleg hinterlässt. Alles drei muss
                    zutreffen. Eine gute Absicht ist keine Kontrolle. Ein Werkzeug ist keine
                    Kontrolle. Eine Richtlinie ist keine Kontrolle.
                </p>

                <div class="versus">
                    <div class="versus-col is-bad">
                        <h3>Keine Kontrolle</h3>
                        <ul class="checklist is-cross is-tight">
                            <li>„Wir achten darauf, dass keine Adminrechte vergeben werden.“</li>
                            <li>„Das Antivirenprogramm läuft auf allen Rechnern.“</li>
                            <li>„Berechtigungen werden bei Bedarf überprüft.“</li>
                            <li>„Der Dienstleister ist zertifiziert.“</li>
                        </ul>
                    </div>
                    <div class="versus-col is-good">
                        <h3>Kontrolle</h3>
                        <ul class="checklist is-tight">
                            <li>„Quartalsweise zieht die IT-Leitung die Liste aller Konten mit Adminrechten, gleicht sie gegen die Sollliste ab und dokumentiert Abweichungen im Ticket XY.“</li>
                            <li>„Monatlich prüft Rolle A den Abdeckungsbericht des Endpunktschutzes; Geräte ohne Schutz werden im Ticket erfasst und binnen 5 Tagen behoben.“</li>
                            <li>„Halbjährlich bestätigen Fachbereichsleitungen die Berechtigungen ihrer Mitarbeitenden schriftlich (Rezertifizierung).“</li>
                            <li>„Jährlich fordert der Dienstleisterverantwortliche Zertifikat und Prüfbericht an und dokumentiert die Bewertung.“</li>
                        </ul>
                    </div>
                </div>

                <h2>Der Kontrollkatalog: 15 bis 40 Zeilen, nicht 200</h2>
                <p>
                    Die Anzahl ergibt sich aus Ihrer Größe und den Anforderungen, denen Sie
                    genügen müssen. Als Orientierung: Ein Unternehmen mit 300 Mitarbeitenden und
                    Prüfungsdruck durch Wirtschaftsprüfung und Kundenaudits kommt mit rund
                    20 Kontrollen aus. Mit ISO-Zertifizierungsabsicht werden es 30 bis 40.
                </p>

                <div class="table-wrap">
                    <table class="data-table">
                        <caption>Ausschnitt aus einem typischen Kontrollkatalog.</caption>
                        <thead>
                            <tr>
                                <th scope="col">Kontrolle</th>
                                <th scope="col">Takt</th>
                                <th scope="col">Wer</th>
                                <th scope="col">Nachweis entsteht als</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Prüfung privilegierter Konten</td><td class="num">quartalsweise</td><td>IT-Leitung</td><td>Ticket mit Systemexport</td></tr>
                            <tr><td>Rezertifizierung Berechtigungen (ERP)</td><td class="num">halbjährlich</td><td>Fachbereichsleitung</td><td>Freigabeliste mit Unterschrift</td></tr>
                            <tr><td>Austrittsprozess: Konten deaktiviert</td><td class="num">monatlich</td><td>IT-Service</td><td>Abgleich HR-Liste ↔ Verzeichnisdienst</td></tr>
                            <tr><td>Change-Freigaben vollständig</td><td class="num">monatlich</td><td>Change-Freigeber</td><td>Auswertung Ticketsystem</td></tr>
                            <tr><td>Rücksicherungstest</td><td class="num">halbjährlich</td><td>Systembetrieb</td><td>Testprotokoll mit Zeitmessung</td></tr>
                            <tr><td>Notfallübung (Tischübung genügt)</td><td class="num">jährlich</td><td>Notfallkoordinator</td><td>Übungsprotokoll mit Teilnehmern</td></tr>
                            <tr><td>Dienstleisterbewertung</td><td class="num">jährlich</td><td>Dienstleisterverantwortlicher</td><td>Bewertungsbogen je Provider</td></tr>
                            <tr><td>Patchstand kritischer Systeme</td><td class="num">monatlich</td><td>Systembetrieb</td><td>Bericht aus dem Managementwerkzeug</td></tr>
                            <tr><td>Prüfung offener Maßnahmen</td><td class="num">quartalsweise</td><td>IT-Leitung</td><td>Protokoll IT-Steuerkreis</td></tr>
                            <tr><td>Aktualität der Richtlinien</td><td class="num">jährlich</td><td>IT-Leitung</td><td>Freigabevermerk mit Datum</td></tr>
                        </tbody>
                    </table>
                </div>

                <h2>Das Prinzip: Nachweis als Nebenprodukt</h2>
                <p>
                    Der entscheidende Konstruktionsgrundsatz: Eine Kontrolle darf keine
                    zusätzliche Dokumentationsarbeit erzeugen. Wenn der Nachweis eine eigene
                    Excel-Datei braucht, wird die Kontrolle nach spätestens sechs Monaten nicht
                    mehr ausgeführt. Deshalb wird jede Kontrolle an einen Ort gebunden, an dem
                    ohnehin gearbeitet wird:
                </p>
                <ul class="checklist">
                    <li><strong>Ticketsystem</strong> – wiederkehrendes Ticket mit Checkliste und Anhang</li>
                    <li><strong>Protokoll eines Gremiums</strong> – Tagesordnungspunkt mit Beschluss</li>
                    <li><strong>Systembericht</strong> – automatisch erzeugt, per Aufbewahrung archiviert</li>
                    <li><strong>Freigabeschritt im Prozess</strong> – der Nachweis ist der Workflow-Eintrag selbst</li>
                </ul>

<?php
$kette = [
    ['Regel', 'Was gilt bei uns?'],
    ['Kontrolle', 'Wer prüft, wie oft?'],
    ['Durchführung', 'Im Arbeitsablauf, nicht daneben'],
    ['Nachweis', 'Entsteht automatisch'],
    ['Bericht', 'Auffälligkeiten ins Gremium'],
];
$ketteLabel = 'Aufbau einer wirksamen Kontrolle';
include __DIR__ . '/../partials/kette.php';
?>

                <h2>Ablauf</h2>
                <ol class="phase-list">
                    <li>
                        <span class="phase-week">Monat 1</span>
                        <h3>Anforderungen und Risiken zuordnen</h3>
                        <p>Woher kommen die Anforderungen – Prüfer, Norm, Kunde, Konzern, eigenes
                           Risiko? Jede geplante Kontrolle bekommt eine Herkunft. Kontrollen ohne
                           Herkunft werden gestrichen.</p>
                    </li>
                    <li>
                        <span class="phase-week">Monat 1–2</span>
                        <h3>Kontrollen entwerfen und zuschneiden</h3>
                        <p>Je Kontrolle: Ziel, Beschreibung, Takt, Verantwortlicher, Nachweisort,
                           Eskalation bei Abweichung. Abstimmung mit denen, die sie ausführen –
                           nicht über ihre Köpfe hinweg.</p>
                    </li>
                    <li>
                        <span class="phase-week">Monat 2–3</span>
                        <h3>Technisch verankern</h3>
                        <p>Wiederkehrende Tickets anlegen, Berichte automatisieren, Aufbewahrung
                           regeln, Zuständige einweisen. Hier entscheidet sich, ob das Framework
                           lebt.</p>
                    </li>
                    <li>
                        <span class="phase-week">Monat 3–4</span>
                        <h3>Erster Durchlauf begleitet</h3>
                        <p>Ein vollständiger Zyklus wird gemeinsam durchlaufen, inklusive
                           Bewertung der entstandenen Nachweise: Würde ein Prüfer das annehmen?
                           Nachschärfen, wo nötig.</p>
                    </li>
                </ol>

                <div class="callout">
                    <span class="callout-icon"><i data-icon="trending-up" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Wirkung, die messbar ist</h3>
                        <p>
                            Der eigentliche Nutzen zeigt sich in der Prüfung: Statt „wir suchen
                            die Unterlagen zusammen“ liegt eine Kontrollübersicht vor, aus der
                            hervorgeht, welche Kontrolle wann durchgeführt wurde und wo der Beleg
                            liegt. Der Zeitaufwand der Prüfung sinkt spürbar – und mit ihm die
                            Zahl der Feststellungen, die aus Beschaffungsproblemen entstehen.
                        </p>
                    </div>
                </div>

                <p>
                    <strong>Preis:</strong> 16.000 € netto für einen kompakten Katalog von rund
                    15 Kontrollen an einem Standort, 24.000 € im Regelfall, bis 34.000 € bei
                    ISO-Ambition, mehreren Gesellschaften oder Kontrollen über
                    Dienstleistergrenzen hinweg.
                </p>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['wissen/kontrollen-nachweise.php', 'leistungen/audit-readiness.php', 'themen/it-risikomanagement.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
