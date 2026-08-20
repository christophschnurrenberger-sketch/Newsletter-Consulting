<?php
$page = [
    'title'       => 'Wissen: Leitfäden zu IT-Governance und Auditfähigkeit',
    'description' => 'Ausführliche Leitfäden aus der Projektarbeit: IT-Governance im Mittelstand, NIS2-Betroffenheit prüfen, Audit-Vorbereitung in 12 Wochen, Reifegrad-Selbsteinschätzung, Kontrollen und Nachweise, IT-Dokumentenlandkarte. Kostenlos, ohne Anmeldung.',
    'section'     => 'wissen',
    'path'        => 'wissen/',
    'crumbs'      => [['Wissen', null]],
    'hero'        => [
        'kicker' => 'Wissensbereich',
        'h1'     => 'Ausführlich, kostenlos, <span class="accent">ohne Anmeldung</span>',
        'lead'   => 'Sechs Leitfäden und ein Selbsttest. Alles so geschrieben, dass Sie es selbst umsetzen können – ohne E-Mail-Adresse, ohne Download-Formular, ohne dass Sie danach angerufen werden.',
        'facts'  => [
            ['6', 'Leitfäden'],
            ['1', 'Selbsttest'],
            ['0', 'Formulare'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';
?>

<section class="section">
    <div class="container">

        <div class="card-grid cols-2">
            <a href="/wissen/it-governance-mittelstand.php" class="card">
                <span class="card-icon"><i data-icon="book-open" class="lucide"></i></span>
                <h3 class="card-title">IT-Governance im Mittelstand – was der Begriff konkret bedeutet</h3>
                <p class="card-text">
                    Der Grundlagenartikel: Warum „Governance“ nach Konzern klingt, im Mittelstand
                    aber etwas sehr Handfestes meint – und welche zehn Bausteine tatsächlich
                    dazugehören.
                </p>
                <div class="card-meta"><span><i data-icon="clock" class="lucide"></i>14 Minuten</span></div>
                <div class="card-foot"><span class="text-link">Lesen<i data-icon="arrow-right" class="lucide"></i></span></div>
            </a>

            <a href="/wissen/reifegradmodell.php" class="card">
                <span class="card-icon is-accent"><i data-icon="bar-chart" class="lucide"></i></span>
                <h3 class="card-title">Reifegrad-Selbsteinschätzung: 12 Fragen</h3>
                <p class="card-text">
                    Der Selbsttest rechnet im Browser und sendet nichts. Am Ende steht eine Stufe
                    von 1 bis 5 – und eine Empfehlung, was in Ihrer Lage der sinnvolle nächste
                    Schritt wäre.
                </p>
                <div class="card-meta"><span><i data-icon="clock" class="lucide"></i>8 Minuten</span></div>
                <div class="card-foot"><span class="text-link">Zum Selbsttest<i data-icon="arrow-right" class="lucide"></i></span></div>
            </a>

            <a href="/wissen/audit-vorbereitung.php" class="card">
                <span class="card-icon"><i data-icon="calendar-check" class="lucide"></i></span>
                <h3 class="card-title">Die 12 Wochen vor der Prüfung</h3>
                <p class="card-text">
                    Ein Wochenplan für die Audit-Vorbereitung: was wann zu tun ist, welche
                    Unterlagen wann bereitliegen müssen und wie man das Prüfungsgespräch selbst
                    vorbereitet.
                </p>
                <div class="card-meta"><span><i data-icon="clock" class="lucide"></i>12 Minuten</span></div>
                <div class="card-foot"><span class="text-link">Lesen<i data-icon="arrow-right" class="lucide"></i></span></div>
            </a>

            <a href="/wissen/nis2-betroffenheit.php" class="card">
                <span class="card-icon"><i data-icon="help-circle" class="lucide"></i></span>
                <h3 class="card-title">NIS2-Betroffenheit in sechs Schritten prüfen</h3>
                <p class="card-text">
                    Ein strukturierter Weg zu einer belastbaren Einschätzung – inklusive der
                    Stelle, an der die Vorarbeit endet und die Kanzlei übernehmen sollte.
                </p>
                <div class="card-meta"><span><i data-icon="clock" class="lucide"></i>10 Minuten</span></div>
                <div class="card-foot"><span class="text-link">Lesen<i data-icon="arrow-right" class="lucide"></i></span></div>
            </a>

            <a href="/wissen/kontrollen-nachweise.php" class="card">
                <span class="card-icon"><i data-icon="check-circle" class="lucide"></i></span>
                <h3 class="card-title">Von der Kontrolle zum Nachweis</h3>
                <p class="card-text">
                    Warum Prüfer Belege sehen wollen und keine Absichten – und wie man Kontrollen
                    so baut, dass der Nachweis im Arbeitsablauf von allein entsteht.
                </p>
                <div class="card-meta"><span><i data-icon="clock" class="lucide"></i>11 Minuten</span></div>
                <div class="card-foot"><span class="text-link">Lesen<i data-icon="arrow-right" class="lucide"></i></span></div>
            </a>

            <a href="/wissen/dokumentenlandkarte.php" class="card">
                <span class="card-icon"><i data-icon="map" class="lucide"></i></span>
                <h3 class="card-title">Die IT-Dokumentenlandkarte</h3>
                <p class="card-text">
                    Welche Dokumente eine mittelständische IT wirklich braucht, wer sie
                    verantwortet, wie oft sie geprüft werden – als Tabelle zum Abhaken.
                </p>
                <div class="card-meta"><span><i data-icon="clock" class="lucide"></i>9 Minuten</span></div>
                <div class="card-foot"><span class="text-link">Lesen<i data-icon="arrow-right" class="lucide"></i></span></div>
            </a>
        </div>

        <div class="callout" style="margin-top:2.6rem;">
            <span class="callout-icon"><i data-icon="mail" class="lucide"></i></span>
            <div class="callout-body">
                <h3 class="callout-title">Warum es hier kein Download-Formular gibt</h3>
                <p>
                    Weil ich selbst keine E-Mail-Adresse für ein PDF hergeben möchte. Wer die
                    Inhalte braucht, soll sie lesen können – und wer daraufhin allein
                    weiterkommt, hat genau das getan, was der Sinn der Sache ist. Wenn Sie Fragen
                    haben, schreiben Sie mir; wenn nicht, hören Sie nichts von mir.
                </p>
            </div>
        </div>

    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
