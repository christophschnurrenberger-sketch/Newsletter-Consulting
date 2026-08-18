<?php
$page = [
    'title'       => 'Systemvoraussetzungen',
    'description' => 'Was der Webspace des Golfclubs mitbringen muss: PHP 8, eine Datenbank (SQLite genügt), ein SMTP-Postfach und ein Cron-Job alle fünf Minuten.',
    'section'     => 'software',
    'path'        => 'software/systemvoraussetzungen.php',
    'crumbs'      => [['Software', 'software/'], ['Systemvoraussetzungen', null]],
    'hero'        => [
        'kicker' => 'Software · Technik',
        'h1'     => 'Läuft auf dem Hosting, <span class="accent">das Sie schon haben</span>',
        'lead'   => 'Kein eigener Server, kein Docker, keine Zusatzsoftware. Wenn Ihre Clubwebsite heute schon läuft, läuft das Newsletter-System mit hoher Wahrscheinlichkeit daneben.',
        'facts'  => [
            ['PHP 8.0+', 'Mindestversion'],
            ['SQLite', 'genügt als Datenbank'],
            ['5 Min', 'Cron-Intervall'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div>
                <div class="table-scroll">
                    <table class="data-table">
                        <caption>Eine Prüfseite im System beantwortet all das automatisch – vor der Installation, in einer Minute.</caption>
                        <thead>
                            <tr>
                                <th scope="col">Was</th>
                                <th scope="col">Benötigt</th>
                                <th scope="col">Anmerkung</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">PHP</th>
                                <td class="yes">8.0 oder neuer</td>
                                <td>Bei IONOS unter „Websites &amp; Shops → PHP verwalten“ einstellbar</td>
                            </tr>
                            <tr>
                                <th scope="row">Datenbank</th>
                                <td class="yes">SQLite oder MySQL</td>
                                <td>SQLite braucht keine eigene Einrichtung und reicht für jeden Club</td>
                            </tr>
                            <tr>
                                <th scope="row">Postfach</th>
                                <td class="yes">SMTP auf Ihrer Domain</td>
                                <td>Ein echtes Postfach, kein Alias – wegen SPF und DKIM</td>
                            </tr>
                            <tr>
                                <th scope="row">Cron-Job</th>
                                <td class="yes">alle 5 Minuten</td>
                                <td>Für portionsweisen Versand, Automationen und Bounces</td>
                            </tr>
                            <tr>
                                <th scope="row">Speicherplatz</th>
                                <td class="yes">ab ca. 200 MB</td>
                                <td>Vor allem für hochgeladene Bilder</td>
                            </tr>
                            <tr>
                                <th scope="row">Composer, Node</th>
                                <td class="no">nicht nötig</td>
                                <td>Das System kommt ohne Paketverwalter und ohne Build aus</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="prose" style="margin-top:2.6rem;">
                    <h2>Und wenn etwas fehlt?</h2>
                    <p>
                        Der häufigste Stolperstein ist eine zu alte PHP-Version – die lässt sich bei
                        praktisch jedem Hoster im Kundenmenü umstellen. Der zweithäufigste ist ein
                        fehlender Cron-Job; einige günstige Tarife bieten ihn nicht an. Dann ist ein
                        Tarifwechsel um wenige Euro im Monat nötig, kein Anbieterwechsel.
                    </p>
                    <p>
                        Beides klären wir im <a href="<?= e(url('leistungen/clubcheck.php')) ?>">Clubcheck</a>,
                        bevor irgendetwas beauftragt wird. Wenn Ihr Hosting nicht mitspielt, sagen wir
                        das dort – und nicht erst, wenn die Rechnung geschrieben ist.
                    </p>

                    <h2>Was beim Versand größerer Mengen gilt</h2>
                    <p>
                        Viele Hoster begrenzen, wie viele Mails pro Stunde über ihr SMTP hinausgehen.
                        Für einen Club mit 600 bis 1.200 Empfängern ist das meist unkritisch, weil
                        das System ohnehin portionsweise versendet. Bei größeren Anlagen oder engen
                        Limits binden wir einen Versanddienst ein – dann bleiben die Daten weiterhin
                        bei Ihnen, nur die Zustellung läuft über eine spezialisierte Strecke.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.5rem; margin:3rem 0 1.2rem;">Weiterlesen</h2>
                <div class="related-grid">
                    <a href="<?= e(url('leistungen/saison-setup.php')) ?>" class="related-card">
                        <span>Leistung</span><strong>Saison-Setup</strong>
                        <p>Installation, Design, Listen und Einweisung als ein Paket.</p>
                    </a>
                    <a href="<?= e(url('preise.php')) ?>" class="related-card">
                        <span>Preise</span><strong>Was es kostet</strong>
                        <p>Einmalige Einrichtung, laufende Betreuung und die Frage nach den Toolkosten.</p>
                    </a>
                </div>
            </div>
            <?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
