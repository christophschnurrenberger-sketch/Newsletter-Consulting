<?php
$page = [
    'title'       => 'Themen: NIS2, DORA, ISO 27001, IT-Risiko, Notfall, Kennzahlen',
    'description' => 'Fachliche Grundlagen der IT-Governance: NIS2, DORA, ISO/IEC 27001, IT-Risikomanagement, Notfallmanagement, Dienstleistersteuerung, Dokumentation, Kennzahlen, Asset-Management und Prozessharmonisierung – erklärt für den Mittelstand.',
    'section'     => 'themen',
    'path'        => 'themen/',
    'crumbs'      => [['Themen', null]],
    'hero'        => [
        'kicker' => 'Themen',
        'h1'     => 'Die Sachthemen – <span class="accent">ohne Beratersprache</span>',
        'lead'   => 'Zehn Themen, die in Governance-Projekten immer wieder auftauchen. Jede Seite erklärt, worum es geht, was ein Prüfer erwartet, was im Mittelstand tatsächlich nötig ist – und wo Aufwand entsteht, der sich nicht lohnt.',
        'facts'  => [
            ['10', 'Themen'],
            ['0', 'Anmeldung nötig'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';
?>

<section class="section">
    <div class="container">

        <div class="section-head is-wide">
            <span class="section-kicker">Regulatorik</span>
            <h2 class="section-title">Anforderungen von außen</h2>
            <p class="section-lead">
                Diese drei Themen bringen mittelständische Unternehmen derzeit am häufigsten
                dazu, sich mit IT-Governance zu befassen. Wichtig: Die fachliche Umsetzung ist
                meine Arbeit, die rechtliche Bewertung nicht.
            </p>
        </div>

        <div class="card-grid cols-3">
            <a href="/themen/nis2.php" class="card">
                <span class="card-icon"><i data-icon="shield-check" class="lucide"></i></span>
                <h3 class="card-title">NIS2</h3>
                <p class="card-text">
                    Wen es betrifft, welche Maßnahmen verlangt werden, warum die Lieferkette
                    auch nicht betroffene Unternehmen erreicht – und was die persönliche
                    Verantwortung der Leitung bedeutet.
                </p>
                <div class="card-foot"><span class="text-link">Ansehen<i data-icon="arrow-right" class="lucide"></i></span></div>
            </a>
            <a href="/themen/dora.php" class="card">
                <span class="card-icon"><i data-icon="euro" class="lucide"></i></span>
                <h3 class="card-title">DORA</h3>
                <p class="card-text">
                    Für Finanzunternehmen verpflichtend – und für IT-Dienstleister des
                    Finanzsektors vertraglich spürbar. Was in Verträgen künftig steht und was
                    daraus organisatorisch folgt.
                </p>
                <div class="card-foot"><span class="text-link">Ansehen<i data-icon="arrow-right" class="lucide"></i></span></div>
            </a>
            <a href="/themen/iso-27001.php" class="card">
                <span class="card-icon"><i data-icon="award" class="lucide"></i></span>
                <h3 class="card-title">ISO/IEC 27001</h3>
                <p class="card-text">
                    Der Weg zum Zertifikat, der realistische Aufwand, die Kosten – und die
                    Frage, wann die Struktur ohne Zertifikat die bessere Entscheidung ist.
                </p>
                <div class="card-foot"><span class="text-link">Ansehen<i data-icon="arrow-right" class="lucide"></i></span></div>
            </a>
        </div>

        <div class="section-head is-wide" style="margin-top:3.5rem;">
            <span class="section-kicker">Fachliche Bausteine</span>
            <h2 class="section-title">Woraus Governance im Alltag besteht</h2>
            <p class="section-lead">
                Diese sieben Themen sind unabhängig von jeder Regulierung sinnvoll. Sie sind das,
                was eine IT steuerbar macht – und sie sind gleichzeitig die Bausteine, die fast
                jede Norm und jeder Prüfer in irgendeiner Form verlangt.
            </p>
        </div>

        <div class="card-grid cols-3">
            <a href="/themen/it-risikomanagement.php" class="card">
                <span class="card-icon is-soft"><i data-icon="alert-triangle" class="lucide"></i></span>
                <h3 class="card-title">IT-Risikomanagement</h3>
                <p class="card-text">Risiken so erfassen und bewerten, dass die Geschäftsführung damit entscheiden kann – ohne Risikomatrix-Theater.</p>
            </a>
            <a href="/themen/it-notfallmanagement.php" class="card">
                <span class="card-icon is-soft"><i data-icon="life-buoy" class="lucide"></i></span>
                <h3 class="card-title">IT-Notfallmanagement</h3>
                <p class="card-text">Wiederanlaufziele, Notfallhandbuch, Alarmierung und der Test, ohne den das alles wertlos ist.</p>
            </a>
            <a href="/themen/dienstleistermanagement.php" class="card">
                <span class="card-icon is-soft"><i data-icon="link" class="lucide"></i></span>
                <h3 class="card-title">IT-Dienstleistermanagement</h3>
                <p class="card-text">Auslagern ist erlaubt, Verantwortung abgeben nicht. Steuerung, Mindestanforderungen, Prüfrechte, Ausstieg.</p>
            </a>
            <a href="/themen/it-dokumentation.php" class="card">
                <span class="card-icon is-soft"><i data-icon="file-text" class="lucide"></i></span>
                <h3 class="card-title">IT-Dokumentation</h3>
                <p class="card-text">Welche Dokumente wirklich gebraucht werden, wie sie aktuell bleiben – und welche man getrost weglässt.</p>
            </a>
            <a href="/themen/it-kennzahlen.php" class="card">
                <span class="card-icon is-soft"><i data-icon="line-chart" class="lucide"></i></span>
                <h3 class="card-title">IT-Kennzahlen &amp; Reporting</h3>
                <p class="card-text">Zwölf Kennzahlen, die man messen kann, und ein Managementbericht, der auf eine Seite passt.</p>
            </a>
            <a href="/themen/asset-applikationsmanagement.php" class="card">
                <span class="card-icon is-soft"><i data-icon="database" class="lucide"></i></span>
                <h3 class="card-title">Asset- &amp; Applikationsmanagement</h3>
                <p class="card-text">Ohne Inventar keine Kontrolle: Was ist im Einsatz, wer verantwortet es, wie lange wird es unterstützt?</p>
            </a>
            <a href="/themen/prozessharmonisierung.php" class="card">
                <span class="card-icon is-soft"><i data-icon="globe" class="lucide"></i></span>
                <h3 class="card-title">Prozessharmonisierung</h3>
                <p class="card-text">Mehrere Standorte, ein Prozess – und die Frage, wo Einheitlichkeit nützt und wo sie schadet.</p>
            </a>
        </div>

        <div class="callout is-legal" style="margin-top:3rem;">
            <span class="callout-icon"><i data-icon="scale" class="lucide"></i></span>
            <div class="callout-body">
                <h3 class="callout-title">Zum Charakter dieser Seiten</h3>
                <p>
                    Die Themenseiten geben den fachlichen Stand wieder, mit dem ich in Projekten
                    arbeite. Sie sind keine Rechtsberatung, keine verbindliche Auslegung von
                    Gesetzen oder Normen und kein Ersatz für die Prüfung im Einzelfall.
                    Regulatorische Anforderungen ändern sich; die verbindliche Fassung steht
                    immer im jeweiligen Gesetzestext, in der Norm oder in der Auslegung durch
                    die zuständige Behörde – und die rechtliche Bewertung nimmt eine Kanzlei vor.
                </p>
            </div>
        </div>

    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
