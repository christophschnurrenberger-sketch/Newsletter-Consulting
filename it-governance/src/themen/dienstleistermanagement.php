<?php
$page = [
    'title'       => 'IT-Dienstleistermanagement und Auslagerungssteuerung',
    'description' => 'Dienstleister steuern statt verwalten: Auslagerungsübersicht, Kritikalitätseinstufung, Mindestanforderungen, jährliche Bewertung, Prüfrechte und Ausstiegsvorbereitung – der häufigste Prüfungsbefund im Mittelstand.',
    'section'     => 'themen',
    'path'        => 'themen/dienstleistermanagement.php',
    'crumbs'      => [['Themen', 'themen/'], ['IT-Dienstleistermanagement', null]],
    'hero'        => [
        'kicker' => 'Thema · Steuerung',
        'h1'     => 'Auslagern dürfen Sie. <span class="accent">Verantwortung abgeben nicht.</span>',
        'lead'   => 'Der Satz steht sinngemäß in jeder relevanten Regulierung – und beschreibt zugleich den häufigsten blinden Fleck im Mittelstand: Verträge liegen im Einkauf, die Leistung läuft, aber niemand steuert und niemand prüft.',
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Erster Schritt', 'vollständige Übersicht'],
    ['Bewertung', 'jährlich, dokumentiert'],
    ['Kritische Dienstleister', 'meist 3–8'],
    ['Häufigster Befund', 'keine Steuerung'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Wo es in der Praxis klemmt</h2>
                <ul class="checklist is-cross">
                    <li>Niemand kann auf Anhieb sagen, wie viele IT-Dienstleister im Einsatz sind.</li>
                    <li>Verträge liegen im Einkauf, technische Absprachen in E-Mails, Zusagen in Angeboten – drei Quellen, keine Übersicht.</li>
                    <li>Sicherheitsanforderungen wurden nie schriftlich vereinbart.</li>
                    <li>Der Dienstleister hat weitreichende Fernzugriffe, die niemand überprüft.</li>
                    <li>Unterauftragnehmer sind unbekannt – der Dienstleister setzt Subunternehmer ein, ohne dass es jemand weiß.</li>
                    <li>Was bei einem Ausstieg passieren müsste, hat nie jemand durchdacht.</li>
                </ul>

                <h2>Schritt 1: Die Übersicht – und was hineingehört</h2>
                <p>
                    Das erste Ergebnis jeder Dienstleistersteuerung ist eine einzige Tabelle. Sie
                    ist gleichzeitig das, was ein Prüfer als Erstes anfordert, und lässt sich in
                    zwei bis drei Tagen erstellen:
                </p>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Spalte</th><th scope="col">Warum sie gebraucht wird</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Dienstleister, Vertragsnummer, Laufzeit, Kündigungsfrist</td><td>Grundlage für Steuerung und Ausstieg</td></tr>
                            <tr><td>Erbrachte Leistung, betroffene Services</td><td>Verbindung zum Servicekatalog</td></tr>
                            <tr><td>Kritikalität (hoch / mittel / gering)</td><td>Bestimmt die Steuerungstiefe – nicht alle brauchen dieselbe</td></tr>
                            <tr><td>Zugriff auf Systeme und Daten</td><td>Grundlage für Zugriffskontrolle und Datenschutz</td></tr>
                            <tr><td>Ort der Leistungserbringung und Datenspeicherung</td><td>Regelmäßig gefragt, oft unbekannt</td></tr>
                            <tr><td>Verantwortlicher im eigenen Haus</td><td>Ohne Namen keine Steuerung</td></tr>
                            <tr><td>Nachweise (Zertifikate, Prüfberichte, Vereinbarungen)</td><td>Beleg für die Bewertung</td></tr>
                            <tr><td>Letzte Bewertung, nächste Bewertung</td><td>Der Nachweis, dass Steuerung stattfindet</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout">
                    <span class="callout-icon"><i data-icon="search" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Wie man die Übersicht wirklich vollständig bekommt</h3>
                        <p>
                            Nicht über Umfragen in der IT – dabei fehlt regelmäßig ein Drittel.
                            Zuverlässiger ist der Weg über die Kreditorenliste der Buchhaltung:
                            alle Zahlungen an IT-nahe Empfänger der letzten 24 Monate. Dazu die
                            Liste der Fernzugriffe und der Anwendungen mit externem Betrieb. Diese
                            drei Quellen zusammengeführt ergeben ein realistisches Bild – und
                            regelmäßig einige Überraschungen.
                        </p>
                    </div>
                </div>

                <h2>Schritt 2: Steuerungstiefe nach Kritikalität</h2>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Kritikalität</th><th scope="col">Was vereinbart sein sollte</th><th scope="col">Turnus der Bewertung</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Hoch</strong><br><small>Ausfall stoppt das Geschäft</small></td>
                                <td>Sicherheitsanforderungen schriftlich, Reaktionszeiten, Meldepflicht bei Vorfällen, Prüfrecht, Unterauftragsregelung, Ausstiegsunterstützung, Notfallabstimmung</td>
                                <td>jährlich, mit Gespräch</td>
                            </tr>
                            <tr>
                                <td><strong>Mittel</strong><br><small>Ausfall behindert spürbar</small></td>
                                <td>Sicherheitsanforderungen, Meldepflicht, Nachweis über Zertifikat oder Selbstauskunft</td>
                                <td>alle zwei Jahre</td>
                            </tr>
                            <tr>
                                <td><strong>Gering</strong><br><small>Ausfall ist verkraftbar</small></td>
                                <td>Standardvertrag genügt</td>
                                <td>anlassbezogen</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p>
                    Diese Abstufung ist entscheidend für die Machbarkeit. Wer alle 40 Dienstleister
                    gleich intensiv steuern will, steuert am Ende keinen. Drei bis acht kritische
                    Dienstleister ernsthaft zu führen, ist realistisch – und deckt den größten Teil
                    des Risikos ab.
                </p>

                <h2>Schritt 3: Die jährliche Bewertung</h2>
                <p>
                    Eine Dienstleisterbewertung muss nicht aufwendig sein. Ein Bogen mit acht bis
                    zwölf Punkten, ausgefüllt vom Verantwortlichen, mit Belegen im Anhang:
                </p>
                <ul class="checklist">
                    <li>Wurden vereinbarte Leistungen und Reaktionszeiten eingehalten?</li>
                    <li>Gab es Sicherheitsvorfälle, und wurden sie gemeldet?</li>
                    <li>Liegen aktuelle Nachweise vor (Zertifikat, Prüfbericht, Selbstauskunft)?</li>
                    <li>Haben sich Unterauftragnehmer oder Leistungsorte geändert?</li>
                    <li>Sind die Zugriffe noch angemessen – oder wurden Rechte nie zurückgenommen?</li>
                    <li>Wie ist die Abhängigkeit einzuschätzen, und was wäre der Ausstiegsaufwand?</li>
                </ul>
                <p>
                    Ergebnis: eine Note, eine Empfehlung, ein Termin für die nächste Bewertung.
                    Das Dokument ist gleichzeitig der Nachweis, den jede Prüfung sehen will.
                </p>

                <h2>Der Ausstieg – vorbereitet, nicht improvisiert</h2>
                <p>
                    Ausstiegsvorbereitung ist im Mittelstand fast nie vorhanden und wird
                    schmerzhaft, sobald sie gebraucht wird: bei Insolvenz, bei Preiserhöhungen, bei
                    Qualitätsproblemen, bei Übernahme des Dienstleisters durch einen Wettbewerber.
                    Für kritische Dienstleister genügen drei Seiten:
                </p>
                <ol class="steps">
                    <li>
                        <h3>Was gehört uns?</h3>
                        <p>Daten, Konfigurationen, Lizenzen, Dokumentation – in welchem Format,
                           wie oft exportierbar, wer hat Zugriff?</p>
                    </li>
                    <li>
                        <h3>Wer könnte übernehmen?</h3>
                        <p>Zwei Alternativen namentlich benannt, mit grober Vorstellung von Aufwand
                           und Kosten. Nicht ausgeschrieben – nur gedacht.</p>
                    </li>
                    <li>
                        <h3>Wie lange dauert der Wechsel?</h3>
                        <p>Eine grobe Schätzung genügt. Sie beeinflusst die Kündigungsfrist, die
                           man vereinbaren sollte – und die Frage, ob das Risiko akzeptabel ist.</p>
                    </li>
                </ol>

                <div class="callout is-legal">
                    <span class="callout-icon"><i data-icon="scale" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Verträge sind Anwaltssache</h3>
                        <p>
                            Ich sage Ihnen, welche Anforderungen fachlich in einem Vertrag geregelt
                            sein sollten und wie Sie die Einhaltung nachhalten. Die Formulierung,
                            Verhandlung und rechtliche Bewertung von Verträgen – einschließlich
                            Auftragsverarbeitung nach Datenschutzrecht – gehört zu Ihrer Kanzlei
                            oder Ihrem Datenschutzbeauftragten.
                        </p>
                    </div>
                </div>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['themen/dora.php', 'themen/it-notfallmanagement.php', 'leistungen/kontrollframework.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
