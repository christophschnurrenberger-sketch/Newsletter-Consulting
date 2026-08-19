<?php
$page = [
    'title'       => 'Über uns',
    'description' => 'Wer hinter AcumenMail Golf steht: ein Ansprechpartner, kein Callcenter. Newsletter-Marketing für Golfclubs mit eigener Software statt Mietlösung.',
    'section'     => 'ueber-uns',
    'path'        => 'ueber-uns.php',
    'crumbs'      => [['Über uns', null]],
    'hero'        => [
        'kicker' => 'Über uns',
        'h1'     => 'Ein Ansprechpartner, <span class="accent">kein Callcenter</span>',
        'lead'   => 'AcumenMail Golf ist die Golf-Spezialisierung von AcumenMail. Wir richten Newsletter-Systeme in Golfclubs ein und betreiben sie – mit Software, die dem Club gehört statt einem Anbieter.',
        'facts'  => [
            ['1', 'fester Ansprechpartner'],
            ['24 h', 'Antwortzeit werktags'],
            ['0', 'Weitergabe von Clubdaten'],
        ],
    ],
];
include __DIR__ . '/partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="split-grid is-wide-left is-top">
            <div class="prose">
                <h2 style="margin-top:0;">Warum ausgerechnet Golfclubs</h2>
                <p>
                    Weil hier fast alles zusammenkommt, was E-Mail gut kann – und weil es fast
                    nirgends genutzt wird. Ein Golfclub hat eine klar abgegrenzte Zielgruppe, feste
                    Anlässe über das Jahr, Segmente, die tatsächlich etwas bedeuten, und ein
                    wirtschaftliches Interesse an Bindung. Trotzdem läuft die Kommunikation in
                    vielen Clubs über Aushang, Facebook und einen Rundbrief, den zweimal im Jahr
                    jemand zwischen Startzeiten und Turnierleitung schreibt.
                </p>
                <p>
                    Der zweite Grund ist die Kostenseite. Mietlösungen rechnen nach Kontakten ab.
                    Ein Club mit 900 Mitgliedern, 400 Gastspielern und einem Kursverteiler zahlt
                    dort dauerhaft – für etwas, das auf dem eigenen Webspace ebenso gut läuft.
                </p>

                <h2>Wie wir arbeiten</h2>
                <p>
                    Sie sprechen mit einer Person, nicht mit wechselnden Ansprechpartnern. Diese
                    Person hat den Clubcheck gemacht, die Einrichtung begleitet und schreibt später
                    die Ausgaben – oder weist das Sekretariat ein, wenn der Club sie selbst
                    schreiben will.
                </p>
                <p>
                    Wenn wir der Meinung sind, dass sich eine Zusammenarbeit für Ihren Club gerade
                    nicht lohnt, sagen wir das. Das ist kein Verkaufstrick, sondern die einzige
                    Grundlage, auf der ein Clubvorstand einer Empfehlung glauben kann.
                </p>
            </div>

            <div>
                <div class="aside-card">
                    <h2 class="aside-title">Grundsätze</h2>
                    <ul class="checklist is-tight" style="margin-top:0;">
                        <li><i data-icon="check" class="lucide"></i><span>Die Software gehört dem Club, nicht uns</span></li>
                        <li><i data-icon="check" class="lucide"></i><span>Keine Vertragslaufzeit, monatlich kündbar</span></li>
                        <li><i data-icon="check" class="lucide"></i><span>Mitgliederdaten verlassen den Clubserver nicht</span></li>
                        <li><i data-icon="check" class="lucide"></i><span>Keine Tracker und keine externen Dienste auf dieser Website</span></li>
                        <li><i data-icon="check" class="lucide"></i><span>Ehrliche Absage statt unpassendem Angebot</span></li>
                    </ul>
                </div>

                <div class="aside-card" style="margin-top:1.4rem;">
                    <h2 class="aside-title">Kontakt</h2>
                    <address style="font-style:normal; font-size:0.95rem; line-height:1.8;">
                        <?= e($SITE['owner']) ?><br>
                        <?= e($SITE['street']) ?><br>
                        <?= e($SITE['city']) ?><br>
                        <a href="tel:<?= e($SITE['phone_link']) ?>" style="color:var(--green);"><?= e($SITE['phone']) ?></a><br>
                        <a href="mailto:<?= e($SITE['email']) ?>" style="color:var(--green);"><?= e($SITE['email']) ?></a>
                    </address>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <span class="section-kicker">Herkunft</span>
            <h2 class="section-title">Die Software gab es vor dem Angebot</h2>
            <p class="section-lead">
                Das Newsletter-System ist nicht für dieses Angebot erfunden worden. Es ist gewachsen,
                weil wir für eigene Projekte ein Werkzeug brauchten, das ohne monatliche Kosten
                auskommt und dessen Daten nicht bei Dritten liegen.
            </p>
        </div>

        <div class="stat-band">
            <div><strong>Baukasten</strong><span>Drag &amp; Drop statt HTML, auch am Handy</span></div>
            <div><strong>Auto­mationen</strong><span>Warten, senden, verzweigen als Ablauf</span></div>
            <div><strong>Eigener Versand</strong><span>SMTP, Cron, Bounces, Sperrliste</span></div>
            <div><strong>Mehrere Marken</strong><span>Club, Golfschule und Gastronomie getrennt</span></div>
        </div>

        <p class="form-hint" style="margin-top:1.6rem; max-width:52rem;">
            Was daraus für einen Golfclub folgt, steht ausführlich im
            <a href="<?= e(url('software/')) ?>">Software-Bereich</a>.
        </p>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
