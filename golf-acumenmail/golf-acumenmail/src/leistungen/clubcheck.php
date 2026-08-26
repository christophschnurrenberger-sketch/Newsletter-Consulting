<?php
$page = [
    'title'       => 'Clubcheck',
    'description' => 'Die Bestandsaufnahme für Golfclubs: Adressbestand, Einwilligungen, Datenqualität und bisherige Kommunikation – mit einer priorisierten Empfehlung.',
    'section'     => 'leistungen',
    'path'        => 'leistungen/clubcheck.php',
    'crumbs'      => [['Leistungen', 'leistungen/'], ['Clubcheck', null]],
    'hero'        => [
        'kicker' => 'Leistung · Klarheit',
        'h1'     => 'Erst wissen, <span class="accent">was der Bestand hergibt</span>',
        'lead'   => 'Bevor irgendetwas eingerichtet wird, sehen wir uns an, womit Ihr Club überhaupt arbeiten kann. Manchmal ist das Ergebnis, dass zuerst ganz andere Hausaufgaben anstehen.',
        'facts'  => [['ab 290 €', 'einmalig'], ['2 Wochen', 'bis zum Ergebnis'], ['30 Min', 'Gespräch inklusive']],
    ],
];
$asideCta = [
    'title' => 'Investition',
    'text'  => 'ab 290 € einmalig – wird beim Saison-Setup verrechnet',
    'link'  => ['Unverbindlich anfragen', 'kontakt.php'],
];
include __DIR__ . '/../partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div>
                <div class="prose">
                    <h2 style="margin-top:0;">Was wir uns ansehen</h2>
                    <ul>
                        <li><strong>Adressbestand</strong> – wie viele Adressen es gibt, wie aktuell sie sind, wie viele Dubletten und tote Einträge darin stecken</li>
                        <li><strong>Einwilligungen</strong> – woher die Adressen stammen und ob eine belastbare Grundlage für den Versand besteht</li>
                        <li><strong>Bisherige Kommunikation</strong> – was in den letzten zwei Jahren hinausging und was daraus wurde</li>
                        <li><strong>Technik</strong> – ob Ihr Hosting das System tragen kann und ob SPF und DKIM stehen</li>
                        <li><strong>Segmente</strong> – welche Gruppen sich aus Ihren Daten überhaupt bilden lassen</li>
                    </ul>

                    <h2>Was Sie bekommen</h2>
                    <p>
                        Eine Chancenkarte auf wenigen Seiten: die drei Hebel mit der größten Wirkung,
                        jeweils mit Aufwand und erwartetem Nutzen, dazu die Punkte, die vorher
                        geklärt sein müssen. Und ein Gespräch von etwa 30 Minuten, in dem wir das
                        gemeinsam durchgehen – mit Vorstand, Sekretariat oder beiden.
                    </p>
                </div>

                <div class="callout">
                    <i data-icon="check-circle" class="lucide"></i>
                    <p>
                        <strong>Wird verrechnet</strong>
                        Entscheiden Sie sich danach für das Saison-Setup, ziehen wir den Clubcheck
                        vollständig ab. Er ist damit kein Zusatzposten, sondern ein Vorschuss auf
                        eine informierte Entscheidung.
                    </p>
                </div>

                <div class="prose">
                    <h2>Für wen sich das lohnt</h2>
                    <p>
                        Für Clubs, die schon ahnen, dass im Adressbestand mehr steckt, aber nicht
                        wissen, wo sie anfangen sollen. Und für Vorstände, die vor einer
                        Investitionsentscheidung eine Einschätzung von außen brauchen.
                    </p>
                </div>
            </div>
            <?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>\n