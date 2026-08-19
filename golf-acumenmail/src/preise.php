<?php
$page = [
    'title'       => 'Preise',
    'description' => 'Was Newsletter-Marketing im Golfclub kostet: einmalige Einrichtung, optionale Betreuung – und warum für die Software selbst keine laufenden Lizenzkosten anfallen.',
    'section'     => 'preise',
    'path'        => 'preise.php',
    'crumbs'      => [['Preise', null]],
    'hero'        => [
        'kicker' => 'Preise',
        'h1'     => 'Einmal einrichten statt <span class="accent">ewig mieten</span>',
        'lead'   => 'Mietlösungen rechnen nach Kontakten ab – ein wachsender Club zahlt dort dauerhaft mehr. Bei uns steckt der Aufwand in der Einrichtung, danach kostet die Software nichts.',
        'actions'=> [['Club-Analyse anfragen', 'kontakt.php', 'primary']],
    ],
];
include __DIR__ . '/partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="package-grid">
            <article class="package-card">
                <p class="package-label">Klarheit</p>
                <h3>Clubcheck</h3>
                <p>Für Clubs, die wissen wollen, was ihr Adressbestand hergibt und wo der erste Hebel liegt.</p>
                <ul class="package-list">
                    <li><i data-icon="check" class="lucide"></i><span>Prüfung von Adressen, Einwilligungen und Datenqualität</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Technikcheck des Hostings</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Chancenkarte mit priorisierten Maßnahmen</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Gespräch mit Vorstand oder Sekretariat</span></li>
                </ul>
                <div class="package-meta">
                    <span>Investition</span>
                    <strong>ab 290 € einmalig</strong>
                    <small>Wird beim Saison-Setup verrechnet.</small>
                </div>
                <p style="margin-top:1.2rem;"><a href="<?= e(url('leistungen/clubcheck.php')) ?>" class="btn-secondary" style="width:100%;">Details ansehen</a></p>
            </article>

            <article class="package-card is-featured">
                <span class="package-flag">Häufig gewählt</span>
                <p class="package-label">Einrichtung</p>
                <h3>Saison-Setup</h3>
                <p>Für Clubs, die zum Saisonstart ein fertiges System wollen – inklusive Software, Design und Auto­mationen.</p>
                <ul class="package-list">
                    <li><i data-icon="check" class="lucide"></i><span>Newsletter-System auf dem Clubserver eingerichtet</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Zustell­barkeit: SMTP, SPF, DKIM, Bounces</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Vorlage im Clubdesign, Anmeldeformular, Listen und Segmente</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Zwei Auto­mationen und ein Redaktionsplan für die Saison</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Einweisung des Sekretariats, Handbuch für den Club</span></li>
                </ul>
                <div class="package-meta">
                    <span>Investition</span>
                    <strong>ab 1.490 € einmalig</strong>
                    <small>Danach keine laufenden Lizenzkosten.</small>
                </div>
                <p style="margin-top:1.2rem;"><a href="<?= e(url('leistungen/saison-setup.php')) ?>" class="btn-primary-custom" style="width:100%;">Details ansehen</a></p>
            </article>

            <article class="package-card">
                <p class="package-label">Rhythmus</p>
                <h3>Clubbetreuung</h3>
                <p>Für Clubs, bei denen der Newsletter sonst wieder liegen bleibt: Wir schreiben, versenden und werten aus.</p>
                <ul class="package-list">
                    <li><i data-icon="check" class="lucide"></i><span>Monatliche Ausgaben nach Redaktionsplan</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Turnier- und Eventkommunikation</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Ausbau der Auto­mationen und Segmente</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Auswertung je Kampagne, Bericht für den Vorstand</span></li>
                </ul>
                <div class="package-meta">
                    <span>Investition</span>
                    <strong>ab 390 € / Monat</strong>
                    <small>Monatlich kündbar, kein Jahresvertrag.</small>
                </div>
                <p style="margin-top:1.2rem;"><a href="<?= e(url('leistungen/clubbetreuung.php')) ?>" class="btn-secondary" style="width:100%;">Details ansehen</a></p>
            </article>
        </div>

        <p class="form-hint" style="margin-top:1.6rem; max-width:52rem;">
            Alle Beträge netto zzgl. gesetzlicher Mehrwertsteuer. Der genaue Preis hängt von
            Mitgliederzahl, Zustand des Adressbestands und Umfang der Auto­mationen ab – im
            Clubcheck bekommen Sie ein verbindliches Angebot.
        </p>
    </div>
</section>

<!-- Rechenbeispiel -->
<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <span class="section-kicker">Zum Vergleich</span>
            <h2 class="section-title">Was eine Mietlösung über fünf Jahre kostet</h2>
            <p class="section-lead">
                Die Zahlen unten sind eine Beispielrechnung für einen Club mit rund 900 Empfängern.
                Die tatsächlichen Tarife unterscheiden sich je Anbieter – das Muster bleibt gleich.
            </p>
        </div>

        <div class="table-scroll">
            <table class="data-table">
                <caption>Beispielrechnung. Hosting fällt in beiden Fällen an und ist deshalb nicht aufgeführt.</caption>
                <thead>
                    <tr>
                        <th scope="col">&nbsp;</th>
                        <th scope="col">Mietlösung</th>
                        <th scope="col">Eigenes System</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><th scope="row">Einrichtung</th><td>gering bis keine</td><td>einmalig ab 1.490 €</td></tr>
                    <tr><th scope="row">Laufend pro Monat</th><td>je nach Tarif, meist zweistellig bis dreistellig</td><td class="yes">0 €</td></tr>
                    <tr><th scope="row">Bei 1.500 Empfängern</th><td class="no">nächster Tarif</td><td class="yes">unverändert 0 €</td></tr>
                    <tr><th scope="row">Nach fünf Jahren</th><td class="no">läuft weiter</td><td class="yes">längst amortisiert</td></tr>
                    <tr><th scope="row">Wenn Sie aufhören</th><td class="no">Daten und Vorlagen weg</td><td class="yes">alles bleibt im Club</td></tr>
                </tbody>
            </table>
        </div>

        <div class="callout" style="margin-top:2rem;">
            <i data-icon="euro" class="lucide"></i>
            <p>
                <strong>Wann sich eine Mietlösung trotzdem lohnt</strong>
                Wenn Ihr Club zwei Mailings im Jahr verschickt und keine Auto­mationen braucht, ist
                ein günstiger Miettarif die einfachere Wahl. Wir sagen das im Clubcheck auch dann,
                wenn wir damit den Auftrag verlieren.
            </p>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
