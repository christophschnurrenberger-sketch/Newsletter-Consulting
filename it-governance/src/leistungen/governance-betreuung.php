<?php
$page = [
    'title'       => 'Laufende IT-Governance-Betreuung',
    'description' => 'Ein fester Tag im Monat für IT-Governance: Maßnahmen nachhalten, Kontrollen prüfen, Berichte für die Geschäftsführung vorbereiten, Prüfungen begleiten. Ab 2.800 € monatlich, Laufzeit ab sechs Monaten.',
    'section'     => 'leistungen',
    'path'        => 'leistungen/governance-betreuung.php',
    'crumbs'      => [['Leistungen', 'leistungen/'], ['Laufende Betreuung', null]],
    'hero'        => [
        'kicker' => 'Leistung · Betrieb',
        'h1'     => 'Damit nach dem Projekt <span class="accent">nicht Stillstand</span> kommt',
        'lead'   => 'Der typische Verlauf ohne Betreuung: drei Monate Aufbruch, drei Monate Alltag, danach liegt das Konzept im Laufwerk. Nicht aus Nachlässigkeit, sondern weil niemand die Aufgabe hat, es nachzuhalten.',
        'actions' => [
            ['Betreuung anfragen', 'kontakt.php', 'primary'],
            ['Alle Leistungen', 'leistungen/', 'ghost'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Rhythmus', 'monatlich, feste Termine'],
    ['Umfang', '1–3 Tage im Monat'],
    ['Laufzeit', 'ab 6 Monaten'],
    ['Preis', 'ab 2.800 € / Monat'],
];
$asideCta = [
    'title' => 'Auch ohne Vorprojekt',
    'text'  => 'Die Betreuung setzt kein früheres Projekt mit mir voraus. Zum Einstieg genügt eine kurze Bestandsaufnahme im ersten Monat.',
    'link'  => ['Gespräch vereinbaren', 'kontakt.php'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Was in einem Betreuungsmonat passiert</h2>
                <p>
                    Der Ablauf ist bewusst gleichförmig. Governance lebt von Wiederholung, nicht
                    von Ereignissen. Ein typischer Monat:
                </p>
                <ol class="steps">
                    <li>
                        <h3>Maßnahmen durchgehen (2 Stunden)</h3>
                        <p>Was war fällig, was ist erledigt, was hängt und warum? Hängende
                           Maßnahmen werden nicht verschoben, sondern entweder neu terminiert
                           oder bewusst gestrichen – mit Begründung im Protokoll.</p>
                    </li>
                    <li>
                        <h3>Kontrollen stichprobenhaft prüfen (2–3 Stunden)</h3>
                        <p>Zwei bis vier Kontrollen aus dem Katalog: Wurde sie durchgeführt,
                           existiert der Nachweis, würde er einer Prüfung standhalten? Über zwölf
                           Monate wird so der gesamte Katalog abgedeckt.</p>
                    </li>
                    <li>
                        <h3>Neues einordnen (1–2 Stunden)</h3>
                        <p>Neuer Dienstleister, neue Anwendung, Vorfall, Kundenanforderung,
                           Regulierungsänderung – was bedeutet das für Richtlinien, Rollen und
                           Kontrollen? Kleine Anpassungen werden sofort erledigt.</p>
                    </li>
                    <li>
                        <h3>Bericht vorbereiten (1–2 Stunden)</h3>
                        <p>Eine Seite für die Geschäftsführung: Status, Risiken, Entscheidungsbedarf.
                           Auf Wunsch als Vorlage für den IT-Steuerkreis oder die Beiratssitzung.</p>
                    </li>
                </ol>

                <h2>Was zusätzlich abgedeckt ist</h2>
                <ul class="checklist">
                    <li><strong>Erreichbarkeit zwischen den Terminen</strong> für Governance-Fragen –
                        per E-Mail und Telefon, Antwort in der Regel am selben oder nächsten Werktag</li>
                    <li><strong>Vorbereitung auf Prüfungen und Kundenaudits</strong>, einschließlich
                        Beantwortung von Sicherheitsfragebögen von Kunden</li>
                    <li><strong>Jährliche Richtlinienprüfung</strong> mit dokumentierter Freigabe –
                        allein dafür fragen Prüfer regelmäßig nach</li>
                    <li><strong>Sparringspartner für die IT-Leitung</strong> bei Entscheidungen mit
                        Governance-Bezug: Auslagerung, Werkzeugauswahl, Rollenschnitt</li>
                    <li><strong>Fortschrittsmessung</strong> gegen das Reifegradprofil, einmal jährlich</li>
                </ul>

                <h2>Preise</h2>
                <div class="price-grid">
                    <div class="price-card">
                        <p class="price-eyebrow">Basis</p>
                        <h3 class="card-title">Ein Tag im Monat</h3>
                        <p class="price-value">2.800 € <span style="font-size:1rem;">/ Monat</span></p>
                        <p class="price-note">zzgl. USt., Laufzeit ab 6 Monaten</p>
                        <ul class="checklist is-tight">
                            <li>Monatstermin (remote oder vor Ort)</li>
                            <li>Maßnahmen- und Kontrollnachverfolgung</li>
                            <li>Monatsbericht, eine Seite</li>
                            <li>Erreichbarkeit für Rückfragen</li>
                        </ul>
                    </div>
                    <div class="price-card is-feature">
                        <p class="price-eyebrow">Regelfall</p>
                        <h3 class="card-title">Zwei Tage im Monat</h3>
                        <p class="price-value">5.200 € <span style="font-size:1rem;">/ Monat</span></p>
                        <p class="price-note">zzgl. USt., Laufzeit ab 6 Monaten</p>
                        <ul class="checklist is-tight">
                            <li>alles aus Basis</li>
                            <li>aktive Umsetzungsbegleitung offener Maßnahmen</li>
                            <li>Teilnahme am IT-Steuerkreis</li>
                            <li>Prüfungs- und Auditbegleitung inklusive</li>
                            <li>Beantwortung von Kundenfragebögen</li>
                        </ul>
                    </div>
                    <div class="price-card">
                        <p class="price-eyebrow">Intensiv</p>
                        <h3 class="card-title">Drei Tage im Monat</h3>
                        <p class="price-value">7.400 € <span style="font-size:1rem;">/ Monat</span></p>
                        <p class="price-note">zzgl. USt., Laufzeit ab 6 Monaten</p>
                        <ul class="checklist is-tight">
                            <li>alles aus Regelfall</li>
                            <li>Aufbauarbeiten parallel zum Betrieb</li>
                            <li>Übergangsphase nach Wechsel der IT-Leitung</li>
                            <li>mehrere Gesellschaften</li>
                        </ul>
                    </div>
                </div>

                <div class="callout">
                    <span class="callout-icon"><i data-icon="clock" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Ehrlich zur Laufzeit</h3>
                        <p>
                            Ich verkaufe keine Dauermandate ohne Ende. Die Betreuung ist als
                            Übergangsphase gedacht: zwölf bis vierundzwanzig Monate, bis die
                            Struktur ohne externe Unterstützung läuft. Wenn wir nach einem Jahr
                            feststellen, dass es intern getragen wird, beenden wir sie – das ist
                            der Erfolgsfall, nicht der Verlust eines Kunden. Kündigungsfrist:
                            drei Monate zum Monatsende.
                        </p>
                    </div>
                </div>

                <h2>Wann sich die Betreuung nicht lohnt</h2>
                <ul class="checklist is-cross">
                    <li>Es gibt keine Governance-Struktur, die nachgehalten werden könnte – dann
                        ist zuerst ein Aufbauprojekt nötig.</li>
                    <li>Im Haus gibt es niemanden, der die Ergebnisse übernimmt. Betreuung ersetzt
                        keine Stelle.</li>
                    <li>Sie brauchen operative Unterstützung im Tagesgeschäft – dafür sind
                        Dienstleister oder Zeitarbeit die günstigere Antwort.</li>
                    <li>Sie haben einen internen ISB oder Governance-Verantwortlichen mit
                        Kapazität. Dann genügen punktuelle Termine, keine monatliche Betreuung.</li>
                </ul>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['leistungen/kontrollframework.php', 'themen/it-kennzahlen.php', 'leistungen/audit-readiness.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
