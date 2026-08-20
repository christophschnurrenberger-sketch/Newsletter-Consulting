<?php
$page = [
    'title'       => 'IT-Notfallmanagement: Wiederanlauf, Handbuch, Test',
    'description' => 'IT-Notfallmanagement im Mittelstand: Wiederanlaufziele aus dem Geschäft ableiten, Notfallhandbuch schlank halten, Alarmierung regeln und den Test durchführen, ohne den der Plan wertlos ist.',
    'section'     => 'themen',
    'path'        => 'themen/it-notfallmanagement.php',
    'crumbs'      => [['Themen', 'themen/'], ['IT-Notfallmanagement', null]],
    'hero'        => [
        'kicker' => 'Thema · Widerstandsfähigkeit',
        'h1'     => 'Ein Notfallplan, den <span class="accent">niemand geübt hat</span>, ist ein Dokument',
        'lead'   => 'Der Unterschied zwischen einem Unternehmen, das nach einem Verschlüsselungsangriff in fünf Tagen wieder arbeitet, und einem, das sechs Wochen braucht, liegt selten in der Technik. Er liegt in der Vorbereitung.',
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Kernbegriffe', 'RTO, RPO, Kritikalität'],
    ['Mindesttest', '1× jährlich Tischübung'],
    ['Handbuch', '10–25 Seiten genügen'],
    ['Nachweis', 'Testprotokoll'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Zwei Zahlen, die alles bestimmen</h2>
                <dl class="deflist">
                        <div>
                            <dt>RTO – Wiederanlaufzeit</dt>
                            <dd>Wie lange darf es dauern, bis der Service wieder läuft? Diese Zahl
                                kommt vom Fachbereich, nicht von der IT. „So schnell wie möglich“
                                ist keine Antwort – dann lautet die Gegenfrage: „Was kostet uns
                                jede Stunde?“</dd>
                        </div>
                        <div>
                            <dt>RPO – tolerierbarer Datenverlust</dt>
                            <dd>Wie viel Arbeit darf verloren gehen? Vier Stunden Auftragserfassung
                                sind meist verkraftbar, vier Stunden Fertigungsdaten selten. Diese
                                Zahl bestimmt die Sicherungsstrategie und damit die Kosten.</dd>
                        </div>
                        <div>
                            <dt>Kritikalität</dt>
                            <dd>Die Einstufung eines Service in eine von drei Klassen. Mehr als
                                drei Klassen führen im Mittelstand nur zu Diskussionen ohne
                                Erkenntnisgewinn.</dd>
                        </div>
                </dl>

                <div class="callout is-warning">
                    <span class="callout-icon"><i data-icon="alert-triangle" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Der teuerste Fehler</h3>
                        <p>
                            Wiederanlaufziele werden von der IT geschätzt statt vom Geschäft
                            vorgegeben – und dann an der vorhandenen Technik ausgerichtet. Das
                            Ergebnis: Ziele, die exakt beschreiben, was heute schon geht. In der
                            Prüfung fällt das auf, im Ernstfall ist es egal, weil niemand die
                            Ziele kannte.
                        </p>
                    </div>
                </div>

                <h2>Was ins Notfallhandbuch gehört – und was nicht</h2>
                <div class="versus">
                    <div class="versus-col is-good">
                        <h3>Gehört hinein</h3>
                        <ul class="checklist is-tight">
                            <li>Wer entscheidet, dass ein Notfall vorliegt – mit Vertretung</li>
                            <li>Alarmierungsliste mit privaten Telefonnummern, aktuell gehalten</li>
                            <li>Reihenfolge des Wiederanlaufs: welcher Service zuerst</li>
                            <li>Konkrete Wiederanlaufanweisungen je kritischem System</li>
                            <li>Zugangsdaten-Notfallverfahren (versiegelt, offline, geprüft)</li>
                            <li>Kommunikationsvorlagen: Mitarbeitende, Kunden, Behörden</li>
                            <li>Was ohne IT weiterläuft – Notbetrieb auf Papier</li>
                        </ul>
                    </div>
                    <div class="versus-col is-bad">
                        <h3>Gehört nicht hinein</h3>
                        <ul class="checklist is-cross is-tight">
                            <li>Theoretische Einführungen in Notfallmanagement</li>
                            <li>Vollständige Systemdokumentation – gehört in die Betriebsdoku</li>
                            <li>Organigramme, die im Notfall niemand braucht</li>
                            <li>Verweise auf Dokumente, die nur im ausgefallenen System liegen</li>
                            <li>Alles, was länger als eine Seite braucht, um eine Frage zu beantworten</li>
                        </ul>
                    </div>
                </div>

                <p>
                    Ein besonders wichtiger Punkt: Das Notfallhandbuch muss <strong>offline
                    verfügbar</strong> sein. Ein PDF im Dateiserver, der gerade verschlüsselt
                    wurde, hilft niemandem. Zwei ausgedruckte Exemplare an definierten Orten sind
                    kein Rückschritt, sondern gelebte Praxis.
                </p>

                <h2>Die drei Teststufen</h2>
                <ol class="steps">
                    <li>
                        <h3>Tischübung (halber Tag, jährlich)</h3>
                        <p>Ein Szenario wird durchgesprochen: „Montag 7 Uhr, alle Windows-Server
                           verschlüsselt, Telefonanlage tot.“ Wer macht was, in welcher
                           Reihenfolge, mit welchen Informationen? Deckt in der Regel innerhalb
                           von zwei Stunden fünf bis zehn Lücken auf – zum Preis eines halben
                           Arbeitstags.</p>
                    </li>
                    <li>
                        <h3>Technischer Wiederanlauftest (1–2 Tage, halbjährlich)</h3>
                        <p>Eine Rücksicherung wird tatsächlich durchgeführt, in einer getrennten
                           Umgebung, mit Zeitmessung. Ergebnis ist eine belastbare Aussage: Der
                           Wiederanlauf dieses Systems dauert X Stunden. Erst dann ist ein
                           Wiederanlaufziel mehr als eine Hoffnung.</p>
                    </li>
                    <li>
                        <h3>Vollübung (mehrere Tage, alle 2–3 Jahre)</h3>
                        <p>Ausfall wird simuliert, Notbetrieb wird tatsächlich gelebt. Aufwendig
                           und für viele mittelständische Unternehmen erst dann sinnvoll, wenn die
                           beiden ersten Stufen sitzen.</p>
                    </li>
                </ol>

                <div class="callout is-ok">
                    <span class="callout-icon"><i data-icon="check-circle" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Der Test ist gleichzeitig der Nachweis</h3>
                        <p>
                            Ein Testprotokoll mit Datum, Teilnehmern, Szenario, Ergebnis und
                            abgeleiteten Maßnahmen ist eines der wirksamsten Dokumente überhaupt:
                            Es beweist Vorbereitung, es beweist Wirksamkeit, und es beweist, dass
                            aus Erkenntnissen Maßnahmen wurden. Prüfer fragen inzwischen
                            regelmäßig danach – und akzeptieren eine dokumentierte Tischübung
                            deutlich eher als einen ungetesteten Plan.
                        </p>
                    </div>
                </div>

                <h2>Der blinde Fleck: Notfall beim Dienstleister</h2>
                <p>
                    Viele Notfallpläne enden an der Unternehmensgrenze. Wenn aber das
                    Rechenzentrum, die Telefonanlage oder das ERP beim Dienstleister liegt, ist
                    dessen Notfallfähigkeit Ihre Notfallfähigkeit. Drei Fragen, die vorher geklärt
                    sein sollten:
                </p>
                <ul class="checklist">
                    <li>Welche Wiederanlaufzeiten hat der Dienstleister vertraglich zugesagt – und
                        wurden sie je getestet?</li>
                    <li>Wie werden Sie im Notfall informiert, und wer ist außerhalb der
                        Geschäftszeiten erreichbar?</li>
                    <li>Nehmen Sie an dessen Notfallübungen teil oder erhalten Sie zumindest das
                        Protokoll?</li>
                </ul>
                <p>
                    Diese Punkte gehören in die <a href="/themen/dienstleistermanagement.php">Dienstleistersteuerung</a>
                    – und sind, wenn sie fehlen, ein regelmäßiger Prüfungsbefund.
                </p>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['themen/it-risikomanagement.php', 'themen/dienstleistermanagement.php', 'leistungen/kontrollframework.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
