<?php
$page = [
    'title'       => 'Mitglieder­bindung',
    'description' => 'Auch die Mitglieder erreichen, die selten auf der Anlage sind: Willkommens­strecke für Neue, Reaktivierung für Stille, ein Rhythmus, der über den Winter trägt.',
    'section'     => 'loesungen',
    'path'        => 'loesungen/mitgliederbindung.php',
    'crumbs'      => [['Lösungen', 'loesungen/'], ['Mitglieder­bindung', null]],
    'hero'        => [
        'kicker' => 'Lösung · Bindung',
        'h1'     => 'Die ersten Wochen entscheiden, <span class="accent">die stillen Jahre kündigen</span>',
        'lead'   => 'Ein Mitglied, das nach der Aufnahme monatelang nichts hört, wird selten zum Stammgast. Und eines, das ein halbes Jahr nicht gespielt hat, kündigt irgendwann – meistens ohne vorher etwas zu sagen.',
        'facts'  => [
            ['3', 'Mails in der Willkommens­strecke'],
            ['1×', 'einrichten, dann läuft es'],
            ['Winter', 'ist kein Funkloch mehr'],
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
                    <h2 style="margin-top:0;">Das Problem hinter dem Problem</h2>
                    <p>
                        Clubs messen Bindung meist an der Kündigungsquote – und merken damit erst,
                        wenn es zu spät ist. Der Absprung beginnt viel früher: in den ersten Wochen
                        nach der Aufnahme, wenn jemand nicht weiß, an wen er sich wendet, wie man
                        Startzeiten bucht oder ob er bei der Damenriege einfach mitspielen darf.
                    </p>
                    <p>
                        Wer in dieser Zeit Antworten bekommt, ohne fragen zu müssen, kommt an. Wer
                        sie nicht bekommt, spielt zwei Jahre gelegentlich und tritt dann aus.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.6rem; margin:2.6rem 0 1.4rem;">Was wir einrichten</h2>

                <div class="numbered-steps">
                    <div class="numbered-step">
                        <div>
                            <h3>Willkommens­strecke für neue Mitglieder</h3>
                            <p>Drei Mails über drei Wochen: Platzregeln und Ansprechpartner, dann
                                Startzeitenbuchung und Rangekarte, dann die Einladung zur
                                Schnupperrunde mit zwei Mitgliedern der Riege. Läuft automatisch,
                                sobald die Anmeldung bestätigt ist.</p>
                        </div>
                    </div>
                    <div class="numbered-step">
                        <div>
                            <h3>Reaktivierung für stille Mitglieder</h3>
                            <p>Wer eine bestimmte Zeit nicht gespielt hat, bekommt genau eine gute
                                Mail – eine ehrliche Nachfrage statt eines Angebots, danach eine
                                unverbindliche Platzrunde mit dem Pro. Mehr wäre aufdringlich,
                                weniger wirkungslos.</p>
                        </div>
                    </div>
                    <div class="numbered-step">
                        <div>
                            <h3>Ein Rhythmus über die ganze Saison</h3>
                            <p>Monatliche Clubnachrichten nach Redaktionsplan – inklusive der
                                Wintermonate, in denen die meisten Clubs schweigen. Saisonrückblick
                                im November, Kurse und Termine im Januar, Platzöffnung im März.</p>
                        </div>
                    </div>
                    <div class="numbered-step">
                        <div>
                            <h3>Segmente, die den Unterschied machen</h3>
                            <p>Fernmitglieder brauchen andere Post als Vollmitglieder, die Jugend
                                andere als die Senioren. Einmal getrennt, danach nur noch
                                ausgewählt.</p>
                        </div>
                    </div>
                </div>

                <div class="callout">
                    <i data-icon="users" class="lucide"></i>
                    <p>
                        <strong>Die günstigste Mitgliedergewinnung ist die vermiedene Kündigung</strong>
                        Ein Austritt kostet den Club nicht nur den Jahresbeitrag, sondern auch die
                        Aufnahmegebühr des Nachfolgers, die Zeit im Sekretariat und – bei
                        Wartelisten-Clubs – nichts, bei allen anderen sehr viel. Eine
                        Willkommens­strecke kostet einmal Einrichtung.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.5rem; margin:3rem 0 1.2rem;">Womit das umgesetzt wird</h2>
                <div class="related-grid">
                    <a href="<?= e(url('software/automationen.php')) ?>" class="related-card">
                        <span>Software</span><strong>Automationen</strong>
                        <p>Die Willkommens­strecke als Ablauf – warten, senden, verzweigen.</p>
                    </a>
                    <a href="<?= e(url('software/empfaenger-segmente.php')) ?>" class="related-card">
                        <span>Software</span><strong>Empfänger &amp; Segmente</strong>
                        <p>Mitgliedsarten, Aktivität und Riegen sauber trennen.</p>
                    </a>
                    <a href="<?= e(url('wissen/newsletter-jahresplan-golfclub.php')) ?>" class="related-card">
                        <span>Wissen</span><strong>Der Newsletter-Jahresplan</strong>
                        <p>Zwölf Monate Clubkommunikation, Monat für Monat.</p>
                    </a>
                </div>
            </div>
            <?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>\n