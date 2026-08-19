<?php
$page = [
    'title'       => 'Saison-Setup',
    'description' => 'Die vollständige Einrichtung für Golfclubs: Newsletter-System auf dem Clubserver, Vorlage im Clubdesign, Listen, Segmente, zwei Automationen und die Einweisung.',
    'section'     => 'leistungen',
    'path'        => 'leistungen/saison-setup.php',
    'crumbs'      => [['Leistungen', 'leistungen/'], ['Saison-Setup', null]],
    'hero'        => [
        'kicker' => 'Leistung · Einrichtung',
        'h1'     => 'Zum Saisonstart <span class="accent">ein fertiges System</span>',
        'lead'   => 'Installation, Zustellbarkeit, Clubdesign, Empfängerlisten, die ersten Automationen und die Einweisung des Sekretariats. Danach verschickt Ihr Club selbst – oder gibt es an uns ab.',
        'facts'  => [['ab 1.490 €', 'einmalig'], ['1–2 Wochen', 'Einrichtung'], ['0 €', 'laufende Lizenz']],
    ],
];
$asideCta = [
    'title' => 'Investition',
    'text'  => 'ab 1.490 € einmalig – danach keine laufenden Lizenzkosten',
    'link'  => ['Unverbindlich anfragen', 'kontakt.php'],
];
include __DIR__ . '/../partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div>
                <h2 class="section-title" style="font-size:1.6rem; margin:0 0 1.4rem;">Was enthalten ist</h2>
                <div class="numbered-steps">
                    <div class="numbered-step">
                        <div>
                            <h3>Installation auf Ihrem Webspace</h3>
                            <p>Systemcheck, Einrichtung, Datenbank, Zugänge. Danach gehört das
                                System dem Club – ohne Vertragslaufzeit und ohne Lizenzgebühr.</p>
                        </div>
                    </div>
                    <div class="numbered-step">
                        <div>
                            <h3>Zustellbarkeit herstellen</h3>
                            <p>SMTP mit einem echten Postfach Ihrer Domain, SPF und DKIM im DNS,
                                Bounce-Verarbeitung und der Cron-Job für den portionsweisen Versand.</p>
                        </div>
                    </div>
                    <div class="numbered-step">
                        <div>
                            <h3>Vorlage im Clubdesign</h3>
                            <p>Farben, Wortmarke, Schriften, Kopfzeile und Footer mit allen
                                Pflichtangaben – abgestimmt auf Ihre Website, damit die Post als
                                Ihre erkannt wird.</p>
                        </div>
                    </div>
                    <div class="numbered-step">
                        <div>
                            <h3>Empfänger und Segmente</h3>
                            <p>Import aus der Clubverwaltung, Listen für Mitglieder, Gastspieler und
                                Kurs­interessenten, dazu das Anmeldeformular auf Ihrer Clubseite.</p>
                        </div>
                    </div>
                    <div class="numbered-step">
                        <div>
                            <h3>Zwei Automationen</h3>
                            <p>In der Regel die Willkommens­strecke für neue Mitglieder und eine
                                zweite nach Ihrem Bedarf – Kurs­interessenten, Gastspieler oder
                                Reaktivierung.</p>
                        </div>
                    </div>
                    <div class="numbered-step">
                        <div>
                            <h3>Redaktionsplan und Einweisung</h3>
                            <p>Ein Plan für die Saison mit Anlässen und Terminen, eine Stunde
                                Einweisung für das Sekretariat und ein Handbuch, das im Club bleibt.</p>
                        </div>
                    </div>
                </div>

                <div class="callout">
                    <i data-icon="zap" class="lucide"></i>
                    <p>
                        <strong>Der beste Zeitpunkt ist der Januar</strong>
                        Dann steht alles, bevor der Platz öffnet – und die erste Ausgabe kann zur
                        Platzöffnung hinausgehen statt mitten in die Saison zu platzen.
                    </p>
                </div>

                <div class="prose">
                    <h2>Was danach an Kosten bleibt</h2>
                    <p>
                        Für das System selbst: nichts. Es läuft auf dem Hosting, das Sie ohnehin
                        bezahlen. Hinzu kommen lediglich Ihr Webspace und – bei sehr großen
                        Anlagen oder engen Versandlimits – gegebenenfalls ein Versanddienst für die
                        Zustellung.
                    </p>
                </div>
            </div>
            <?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>\n