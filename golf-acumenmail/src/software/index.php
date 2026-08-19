<?php
$page = [
    'title'       => 'Newsletter-Software für Golfclubs',
    'description' => 'Das Newsletter-System läuft auf dem Server Ihres Clubs: Baukasten ohne HTML, Auto­mationen, Segmente, Auswertung und Zustell­barkeit – ohne Kosten pro Kontakt.',
    'section'     => 'software',
    'path'        => 'software/',
    'crumbs'      => [['Software', null]],
    'hero'        => [
        'kicker' => 'Die Software',
        'h1'     => 'Ein Newsletter-System, das dem Club gehört – <span class="accent">nicht dem Anbieter</span>',
        'lead'   => 'Es liegt auf dem Webspace Ihres Clubs, rechnet nicht pro Kontakt ab und lässt sich bedienen, ohne dass jemand HTML können muss. Sechs Bereiche, die zusammen ein vollständiges System ergeben.',
        'actions'=> [
            ['Demo anfragen', 'kontakt.php', 'primary'],
            ['Preise ansehen', 'preise.php', 'ghost'],
        ],
        'facts'  => [
            ['0 €', 'Lizenzkosten pro Monat'],
            ['PHP 8', 'mehr braucht der Server nicht'],
            ['1–2', 'Wochen bis zur ersten Ausgabe'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';
?>

<div class="trust-strip">
    <div class="container trust-strip-inner">
        <span class="trust-strip-item"><i data-icon="server" class="lucide"></i>Auf Ihrem eigenen Webspace</span>
        <span class="trust-strip-item"><i data-icon="euro" class="lucide"></i>Keine Kosten pro Kontakt</span>
        <span class="trust-strip-item"><i data-icon="shield-check" class="lucide"></i>Double-Opt-in mit Protokoll</span>
        <span class="trust-strip-item"><i data-icon="lock" class="lucide"></i>Mitgliederdaten bleiben im Club</span>
    </div>
</div>

<!-- Einordnung ---------------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="split-grid is-wide-left is-top">
            <div>
                <span class="section-kicker">Warum eigene Software</span>
                <h2 class="section-title">Der Unterschied zeigt sich beim dritten Turnier</h2>
                <p class="section-lead">
                    Mietlösungen rechnen nach Kontakten oder nach versendeten Mails ab. Ein Club mit
                    900 Mitgliedern, 400 Gastspielern und einem Kursverteiler zahlt dort dauerhaft –
                    und darf die Daten trotzdem nicht dorthin schieben, wo er möchte.
                </p>
                <p style="margin-top:1rem;">
                    Unser System kehrt das um: Es wird einmal auf dem Webspace des Clubs eingerichtet
                    und gehört danach dem Club. Keine Vertragslaufzeit, kein Tarifwechsel bei der
                    nächsten Mitgliederwelle, keine Datenweitergabe.
                </p>
                <ul class="checklist">
                    <li><i data-icon="check" class="lucide"></i><span>Läuft auf jedem gängigen Webhosting mit PHP 8 – SQLite genügt als Datenbank</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Versand über ein echtes Postfach Ihrer Domain, portionsweise per Cron</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Mehrere Zugänge mit Rollen: Administrator, Redakteur, Betrachter</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Mehrere Marken in einer Installation – Club, Golfschule, Gastronomie</span></li>
                </ul>
            </div>

            <div class="table-scroll">
                <table class="data-table">
                    <caption>Vergleich mit den üblichen Mietlösungen. Preise dort je nach Anbieter und Tarif.</caption>
                    <thead>
                        <tr>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">Mietlösung</th>
                            <th scope="col">Eigenes System</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Kosten</th>
                            <td class="no">pro Kontakt oder Mail</td>
                            <td class="yes">einmalige Einrichtung</td>
                        </tr>
                        <tr>
                            <th scope="row">Mitgliederdaten</th>
                            <td class="no">beim Anbieter</td>
                            <td class="yes">auf dem Clubserver</td>
                        </tr>
                        <tr>
                            <th scope="row">Wachsende Liste</th>
                            <td class="no">nächster Tarif</td>
                            <td class="yes">ändert nichts</td>
                        </tr>
                        <tr>
                            <th scope="row">Laufzeit</th>
                            <td class="no">Vertrag</td>
                            <td class="yes">keine</td>
                        </tr>
                        <tr>
                            <th scope="row">Anpassbar</th>
                            <td class="no">was der Tarif hergibt</td>
                            <td class="yes">Quelltext liegt beim Club</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Die sechs Bereiche --------------------------------------------------- -->
<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <span class="section-kicker">Funktionen</span>
            <h2 class="section-title">Sechs Bereiche, ein System</h2>
            <p class="section-lead">
                Jeder Bereich hat eine eigene Seite – mit der Oberfläche selbst und dem, was im
                Cluballtag daraus folgt.
            </p>
        </div>

        <div class="link-card-grid">
<?php foreach ($NAV['software']['children'] as $child): ?>
            <a href="<?= e(url($child['url'])) ?>" class="link-card">
                <span class="link-card-icon"><i data-icon="<?= e($child['icon']) ?>" class="lucide"></i></span>
                <h3><?= e($child['label']) ?></h3>
                <p><?= e($child['desc']) ?></p>
                <span class="link-card-more">Ansehen<i data-icon="arrow-right" class="lucide"></i></span>
            </a>
<?php endforeach; ?>
        </div>
    </div>
</section>


<!-- Drei Schritte bis zum Versand ---------------------------------------- -->
<section class="section section-dark">
    <div class="container">
        <div class="section-head">
            <p class="section-kicker on-dark">Der Weg einer Ausgabe</p>
            <h2 class="section-title">Drei Schritte bis zum Versand</h2>
            <p class="section-lead">
                Inhalt, Angaben, Prüfen &amp; Senden. Der dritte Schritt trägt die Ampel: Solange
                etwas fehlt, bleibt „Jetzt senden“ gesperrt und der Grund steht daneben.
            </p>
        </div>

        <figure class="demo-frame" data-demo-frame>
            <div class="demo-frame-kopf">
                <span>Newslettersystem <span class="ort">Prüfen &amp; Senden</span></span>
                <span class="hinweis">Ansicht – nicht bedienbar</span>
            </div>
            <div class="demo-frame-buehne">
                <iframe src="<?= e(url('demo/pruefen.html')) ?>" loading="lazy"
                        title="Schritt drei im Editor: Vorschau der fertigen Mail, daneben der Testversand, darunter der gesperrte Versandknopf."></iframe>
            </div>
            <figcaption>
                <b>Schritt 3 von 3.</b>
                <span>Die Vorschau der fertigen Mail, darunter das, was noch fehlt. Solange
                dort etwas steht, bleibt „Jetzt senden“ gesperrt.
                <a href="<?= e(url('kontakt.php')) ?>">Einen eigenen Zugang zum
                Ausprobieren anfragen</a></span>
            </figcaption>
        </figure>
    </div>
</section>

<!-- Ablauf der Einrichtung ---------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="split-grid is-wide-right is-top">
            <div>
                <span class="section-kicker">Einrichtung</span>
                <h2 class="section-title">Vom Zugang zum Webspace bis zur ersten Ausgabe</h2>
                <p class="section-lead">
                    Sie brauchen niemanden im Club, der Technik kann. Wir richten ein, Sie bekommen
                    Zugänge und eine Einweisung.
                </p>
                <p style="margin-top:1.4rem;">
                    <a href="<?= e(url('leistungen/saison-setup.php')) ?>" class="btn-primary-custom">Zum Saison-Setup</a>
                </p>
            </div>

            <div class="numbered-steps">
                <div class="numbered-step">
                    <div>
                        <h3>Systemcheck</h3>
                        <p>Eine Prüfseite sagt sofort, ob PHP-Version, Erweiterungen und Schreibrechte
                            Ihres Hostings passen. Das dauert Minuten, nicht Tage.</p>
                    </div>
                </div>
                <div class="numbered-step">
                    <div>
                        <h3>Installation und Versandweg</h3>
                        <p>Datenbank, Zugänge, Absenderadresse. Danach SMTP mit einem echten Postfach
                            Ihrer Domain sowie SPF und DKIM, damit die Post ankommt.</p>
                    </div>
                </div>
                <div class="numbered-step">
                    <div>
                        <h3>Clubdesign und Listen</h3>
                        <p>Die Vorlage bekommt Ihre Farben, Wortmarke und Pflichtangaben. Die
                            Empfänger kommen als CSV aus der Clubverwaltung.</p>
                    </div>
                </div>
                <div class="numbered-step">
                    <div>
                        <h3>Einweisung</h3>
                        <p>Eine Stunde mit dem Sekretariat, dazu ein Handbuch für den Club. Danach
                            entsteht eine Ausgabe in 20 bis 40 Minuten.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
