<?php
$page = [
    'title'       => 'Auswertung & Berichte',
    'description' => 'Öffnungen, Klicks je Link, Abmeldungen und Bounces – und daraus ein Bericht, den man in der Mitgliederversammlung zeigen kann.',
    'section'     => 'software',
    'path'        => 'software/auswertung.php',
    'crumbs'      => [['Software', 'software/'], ['Auswertung', null]],
    'hero'        => [
        'kicker' => 'Software · Messung',
        'h1'     => 'Am Ende der Saison fragt jemand: <span class="accent">Was hat es gebracht?</span>',
        'lead'   => 'Darauf gibt es eine Antwort, die aus Zahlen besteht und nicht aus Bauchgefühl – je Ausgabe, je Segment und über die ganze Saison hinweg.',
        'facts'  => [
            ['je Link', 'Klicks einzeln gezählt'],
            ['POP3', 'Bounces automatisch verarbeitet'],
            ['CSV', 'Export für eigene Auswertungen'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div>
                <div class="prose">
                    <h2 style="margin-top:0;">Was gemessen wird</h2>
                    <p>
                        Öffnungen und Klicks lassen sich je Ausgabe abschalten – gemessen wird nur,
                        wenn Sie es wollen. Ist die Messung aktiv, sehen Sie folgendes:
                    </p>
                    <ul>
                        <li><strong>Öffnungen</strong> – wie viele Empfänger die Ausgabe geöffnet haben</li>
                        <li><strong>Klicks je Link</strong> – nicht nur „wurde geklickt“, sondern welcher Link wie oft</li>
                        <li><strong>Abmeldungen</strong> – als Warnsignal, wenn eine Ausgabe daneben lag</li>
                        <li><strong>Bounces</strong> – ungültige Adressen, per POP3 automatisch ausgewertet und gesperrt</li>
                        <li><strong>Verlauf</strong> – die Entwicklung über die Saison als Grafik</li>
                    </ul>
                </div>

                <h2 class="section-title" style="font-size:1.6rem; margin:2.6rem 0 1.2rem;">Die Zahlen, die im Vorstand zählen</h2>
                <div class="stat-band">
                    <div><strong>Öffnungsrate</strong><span>Sagt, ob Betreff und Absender passen</span></div>
                    <div><strong>Klickrate</strong><span>Sagt, ob der Inhalt relevant war</span></div>
                    <div><strong>Anmeldungen</strong><span>Sagt, ob das Turnier voll wird</span></div>
                    <div><strong>Listenqualität</strong><span>Sagt, ob die Basis gesund ist</span></div>
                </div>

                <div class="prose">
                    <h2>Was die Zahlen nicht können</h2>
                    <p>
                        Die Öffnungsrate ist die unzuverlässigste der vier. Sie wird über ein kleines
                        Bild gemessen, und moderne Postfächer laden dieses Bild teils automatisch
                        oder gar nicht. Als Vergleichswert zwischen zwei eigenen Ausgaben taugt sie,
                        als absolute Wahrheit nicht.
                    </p>
                    <p>
                        Belastbarer sind Klicks und das, was danach passiert: Wie viele Startplätze
                        wurden nach der Ausgabe gebucht? Wie viele Anmeldungen zum Kurs kamen in den
                        drei Tagen danach? Diese Verbindung stellen wir in der Betreuung her – die
                        Software liefert die eine Hälfte, die Clubverwaltung die andere.
                    </p>
                </div>

                <div class="callout">
                    <i data-icon="line-chart" class="lucide"></i>
                    <p>
                        <strong>Ein Bericht pro Saison reicht</strong>
                        In der Clubbetreuung fassen wir die Kampagnen zu einem Bericht zusammen, der
                        auf zwei Seiten passt: was verschickt wurde, was ankam, was daraus folgte und
                        was im nächsten Jahr anders laufen sollte. Genau das, was der Vorstand
                        braucht – nicht mehr.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.5rem; margin:3rem 0 1.2rem;">Weiterlesen</h2>
                <div class="related-grid">
                    <a href="<?= e(url('leistungen/clubbetreuung.php')) ?>" class="related-card">
                        <span>Leistung</span><strong>Clubbetreuung</strong>
                        <p>Auswertung je Kampagne und ein Bericht für den Vorstand.</p>
                    </a>
                    <a href="<?= e(url('wissen/betreffzeilen-golfclub.php')) ?>" class="related-card">
                        <span>Wissen</span><strong>Betreffzeilen, die im Club wirken</strong>
                        <p>Woran man vor dem Versand erkennt, ob ein Betreff trägt.</p>
                    </a>
                </div>
            </div>
            <?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
