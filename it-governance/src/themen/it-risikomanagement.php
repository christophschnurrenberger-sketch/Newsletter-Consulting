<?php
$page = [
    'title'       => 'IT-Risikomanagement für den Mittelstand',
    'description' => 'IT-Risiken so erfassen, bewerten und behandeln, dass die Geschäftsführung damit entscheiden kann: Risikoinventar, Bewertungsmaßstab in Euro und Zeit, Behandlungsoptionen, Berichtsweg – ohne Risikomatrix-Theater.',
    'section'     => 'themen',
    'path'        => 'themen/it-risikomanagement.php',
    'crumbs'      => [['Themen', 'themen/'], ['IT-Risikomanagement', null]],
    'hero'        => [
        'kicker' => 'Thema · Steuerung',
        'h1'     => 'Risiken, die im <span class="accent">Vorstand ankommen</span>',
        'lead'   => 'Die meisten IT-Risikolisten haben ein gemeinsames Merkmal: Niemand außerhalb der IT liest sie. Sie enthalten technische Sachverhalte statt geschäftlicher Folgen – und sind deshalb keine Entscheidungsgrundlage.',
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Sinnvoller Umfang', '15–30 Risiken'],
    ['Rhythmus', 'quartalsweise Prüfung'],
    ['Entscheidung', 'Geschäftsführung'],
    ['Nachweis', 'Protokoll mit Beschluss'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Warum die meisten IT-Risikolisten wirkungslos sind</h2>
                <div class="versus">
                    <div class="versus-col is-bad">
                        <h3>So steht es meistens da</h3>
                        <p>„Veraltete Firmware auf Switch-Infrastruktur, Eintrittswahrscheinlichkeit
                            mittel, Auswirkung hoch, Risikowert 12.“</p>
                        <p class="muted" style="font-size:.9rem;">Keine Geschäftsführung kann damit
                            etwas anfangen. Was ist 12? Und im Vergleich wozu?</p>
                    </div>
                    <div class="versus-col is-good">
                        <h3>So wird es entscheidbar</h3>
                        <p>„Fällt der Kernswitch im Werk 2 aus, steht die Fertigung. Ersatz ist
                            nicht auf Lager, Lieferzeit sechs Wochen. Ausfallkosten rund 40.000 €
                            pro Tag. Ein Ersatzgerät kostet 9.000 € einmalig.“</p>
                        <p class="muted" style="font-size:.9rem;">Diese Entscheidung trifft eine
                            Geschäftsführung in zwei Minuten.</p>
                    </div>
                </div>

                <h2>Der Aufbau, der funktioniert</h2>
                <ol class="steps">
                    <li>
                        <h3>Vom Geschäftsprozess her denken</h3>
                        <p>Nicht: „Welche Systeme haben wir?“ Sondern: „Welche Geschäftsprozesse
                           dürfen nicht stehen bleiben, und woran hängen sie?“ Damit entstehen
                           Risiken, die im Unternehmen verstanden werden – und gleichzeitig die
                           Grundlage für das <a href="/themen/it-notfallmanagement.php">Notfallmanagement</a>.</p>
                    </li>
                    <li>
                        <h3>In Euro und Stunden bewerten</h3>
                        <p>Statt Wahrscheinlichkeitsklassen: Was kostet ein Ausfalltag? Wie viele
                           Stunden Stillstand sind verkraftbar? Die Zahlen müssen nicht exakt
                           sein – sie müssen von den Fachbereichen kommen und plausibel sein.</p>
                    </li>
                    <li>
                        <h3>Behandlung entscheiden lassen</h3>
                        <p>Vier Optionen: vermeiden, vermindern, übertragen (Versicherung,
                           Vertrag), akzeptieren. Wichtig ist die vierte – eine bewusst akzeptierte
                           Gefahr ist kein Versäumnis, sondern eine Entscheidung. Sie gehört
                           schriftlich festgehalten, mit Datum und Verantwortlichem.</p>
                    </li>
                    <li>
                        <h3>Quartalsweise nachhalten</h3>
                        <p>Neue Risiken, veränderte Bewertungen, Status der Maßnahmen. Zehn
                           Minuten im IT-Steuerkreis genügen, wenn die Liste kurz ist. Das
                           Protokoll ist gleichzeitig der Nachweis für jeden Prüfer.</p>
                    </li>
                </ol>

                <h2>Die zwölf Risiken, die fast überall auftauchen</h2>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Risiko</th><th scope="col">Typische geschäftliche Folge</th><th scope="col">Häufig übersehen</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Verschlüsselungsangriff auf Server und Sicherungen</td><td>Stillstand mehrerer Tage bis Wochen</td><td>Sicherungen im selben Netz erreichbar</td></tr>
                            <tr><td>Ausfall des ERP-Systems</td><td>Keine Aufträge, keine Lieferungen, keine Rechnungen</td><td>Wiederanlaufzeit nie gemessen</td></tr>
                            <tr><td>Ausfall einer Schlüsselperson</td><td>Änderungen und Notfälle nicht beherrschbar</td><td>Zugänge nur bei einer Person</td></tr>
                            <tr><td>Dienstleister fällt aus oder wird insolvent</td><td>Betrieb ohne Ansprechpartner</td><td>Keine Ausstiegsvorbereitung</td></tr>
                            <tr><td>Veraltete, nicht mehr unterstützte Systeme</td><td>Keine Sicherheitsaktualisierungen, Prüfungsfeststellung</td><td>Keine Übersicht über Supportenden</td></tr>
                            <tr><td>Überhöhte Berechtigungen</td><td>Innentäterrisiko, Prüfungsfeststellung</td><td>Wechsel im Unternehmen ohne Rechteentzug</td></tr>
                            <tr><td>Fehlende Wiederanlauffähigkeit</td><td>Notfallplan existiert, funktioniert aber nicht</td><td>Nie getestet</td></tr>
                            <tr><td>Schatten-IT in Fachbereichen</td><td>Daten außerhalb der Kontrolle, Vertragsrisiken</td><td>Wird nicht erhoben, weil unangenehm</td></tr>
                            <tr><td>Abhängigkeit von einem Cloud-Anbieter</td><td>Preis- und Verfügbarkeitsrisiko, Ausstieg teuer</td><td>Datenexport nie erprobt</td></tr>
                            <tr><td>Unzureichende Netztrennung (Büro/Produktion)</td><td>Ein Vorfall legt beides lahm</td><td>Historisch gewachsen, nie bereinigt</td></tr>
                            <tr><td>Fehlende Protokollierung</td><td>Vorfall nicht aufklärbar, Nachweis unmöglich</td><td>Protokolle vorhanden, aber nach 7 Tagen gelöscht</td></tr>
                            <tr><td>Unklare Zuständigkeit im Notfall</td><td>Erste Stunden gehen verloren</td><td>Kein benannter Notfallkoordinator</td></tr>
                        </tbody>
                    </table>
                </div>

                <h2>Verbindung zu den anderen Themen</h2>
                <p>
                    IT-Risikomanagement ist kein eigenständiges Thema, sondern der Verteiler:
                    Aus Risiken entstehen Maßnahmen, aus Maßnahmen Kontrollen, aus Kontrollen
                    Nachweise. Und umgekehrt fließen Erkenntnisse aus Vorfällen, Prüfungen und
                    Dienstleisterbewertungen wieder in die Risikoliste zurück.
                </p>
<?php
$kette = [
    ['Geschäftsprozess', 'Was darf nicht stehen?'],
    ['Risiko', 'Was kann passieren, was kostet es?'],
    ['Entscheidung', 'Vermindern oder akzeptieren'],
    ['Maßnahme', 'Mit Termin und Verantwortlichem'],
    ['Kontrolle', 'Prüft, ob es wirkt'],
];
$ketteLabel = 'Vom Risiko zur Kontrolle';
include __DIR__ . '/../partials/kette.php';
?>

                <div class="callout">
                    <span class="callout-icon"><i data-icon="briefcase" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Anschluss an das Unternehmensrisikomanagement</h3>
                        <p>
                            Viele mittelständische Unternehmen haben bereits ein Risikomanagement
                            – meist im Controlling, oft getrieben durch Anforderungen an die
                            Geschäftsführung zur Früherkennung bestandsgefährdender Entwicklungen.
                            IT-Risiken gehören dort hinein, in derselben Sprache und mit
                            denselben Maßstäben. Zwei getrennte Risikowelten sind doppelte Arbeit
                            und liefern widersprüchliche Bilder.
                        </p>
                    </div>
                </div>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['themen/it-notfallmanagement.php', 'leistungen/kontrollframework.php', 'themen/it-kennzahlen.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
