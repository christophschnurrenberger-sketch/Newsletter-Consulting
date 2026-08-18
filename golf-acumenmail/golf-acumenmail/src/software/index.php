<?php
$page = [
    'title'       => 'Newsletter-Software für Golfclubs',
    'description' => 'Das Newsletter-System läuft auf dem Server Ihres Clubs: Baukasten ohne HTML, Automationen, Segmente, Auswertung und Zustellbarkeit – ohne Kosten pro Kontakt.',
    'section'     => 'software',
    'path'        => 'software/',
    'css'         => ['demo'],
    'js'          => ['demo'],
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
                            <th scope="col">Ihr eigenes System</th>
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
                Jeder Bereich hat eine eigene Seite mit Bildschirmansichten und dem, was im
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
        <div class="demo-head" style="margin-bottom:1.6rem;">
            <span class="demo-step-label">Live-Demo</span>
            <h2 class="section-title" style="font-size:clamp(1.5rem,2.8vw,2.1rem);">Drei Schritte bis zum Versand</h2>
            <p style="color:var(--on-dark-muted); font-size:1.02rem;">
                Inhalt, Angaben, Prüfen &amp; Senden. Der dritte Schritt trägt die Ampel: Solange
                etwas fehlt, bleibt „Jetzt senden“ gesperrt und der Grund steht daneben.
            </p>
        </div>

        <div class="demo-shell is-static" data-demo="steps">
            <div class="demo-chrome">
                <span class="demo-dots" aria-hidden="true"><i></i><i></i><i></i></span>
                <span class="demo-url">golfclub-musterhausen.de/newsletter/admin/kampagne.php?id=41</span>
                <button type="button" class="demo-control demo-toggle" aria-pressed="true">
                    <i data-icon="play" class="lucide"></i><span class="demo-toggle-label">Abspielen</span>
                </button>
                <button type="button" class="demo-control demo-replay" aria-label="Demo von vorn abspielen">
                    <i data-icon="rotate-ccw" class="lucide"></i>
                </button>
            </div>

            <div class="demo-body demo-steps">
                <div class="demo-tabs" role="presentation">
                    <span class="demo-tab tab-1"><i>1</i>Inhalt</span>
                    <span class="demo-tab tab-2"><i>2</i>Angaben</span>
                    <span class="demo-tab tab-3"><i>3</i>Prüfen &amp; Senden</span>
                </div>

                <div class="demo-pane pane-1">
                    <div class="demo-mini" style="max-width:420px;">
                        <span class="demo-mini-label">Inhalt der Ausgabe</span>
                        <span class="bar head"></span>
                        <span class="bar w-70"></span>
                        <span class="bar w-50"></span>
                        <span class="bar cta"></span>
                    </div>
                    <p style="margin-top:1rem; font-size:0.88rem; color:var(--muted);">
                        Die Bausteine aus dem <a href="<?= e(url('software/newsletter-baukasten.php')) ?>"
                        style="color:var(--green); text-decoration:underline;">Baukasten</a> –
                        Kopfzeile und Footer der Clubvorlage stehen fest darum herum.
                    </p>
                </div>

                <div class="demo-pane pane-2">
                    <div class="demo-form-row">
                        <div class="demo-field">
                            <label>Betreff</label>
                            <div class="demo-input field-subject">
                                <span class="demo-typed">Clubmeisterschaft 2026 – Anmeldung ist offen</span><span class="demo-caret"></span>
                            </div>
                        </div>
                    </div>
                    <div class="demo-form-row is-two">
                        <div class="demo-field">
                            <label>Absender</label>
                            <div class="demo-input">sekretariat@golfclub-musterhausen.de</div>
                        </div>
                        <div class="demo-field">
                            <label>Empfängerliste</label>
                            <div class="demo-input field-list">
                                <i data-icon="users" class="lucide"></i>Mitglieder aktiv · 612
                            </div>
                        </div>
                    </div>
                    <div class="demo-form-row is-two">
                        <div class="demo-field">
                            <label>Marke &amp; Design</label>
                            <div class="demo-input">Golfclub Musterhausen</div>
                        </div>
                        <div class="demo-field">
                            <label>Messung</label>
                            <div class="demo-input">Öffnungen und Klicks</div>
                        </div>
                    </div>
                </div>

                <div class="demo-pane pane-3">
                    <div class="demo-preview-row">
                        <div class="demo-mini">
                            <span class="demo-mini-label">Vorschau Rechner</span>
                            <span class="bar head"></span>
                            <span class="bar w-70"></span>
                            <span class="bar w-50"></span>
                            <span class="bar cta"></span>
                        </div>
                        <div class="demo-mini is-phone">
                            <span class="demo-mini-label">Handy</span>
                            <span class="bar head"></span>
                            <span class="bar w-70"></span>
                            <span class="bar cta"></span>
                        </div>
                    </div>

                    <div class="demo-checklist">
                        <span class="demo-check chk-1"><i></i>Betreff gesetzt</span>
                        <span class="demo-check chk-2"><i></i>Empfängerliste gewählt (612)</span>
                        <span class="demo-check chk-3"><i></i>Testmail an den Vorstand verschickt</span>
                        <span class="demo-check chk-4"><i></i>Abmeldelink und Impressum vorhanden</span>
                    </div>

                    <div class="demo-ready-bar">
                        <span class="demo-pill"><em></em><span class="pill-open">2 offen</span><span class="pill-ready">bereit</span></span>
                        <span class="demo-send"><i data-icon="send" class="lucide"></i>Jetzt senden</span>
                    </div>

                    <div class="demo-toast">
                        <i data-icon="check-circle" class="lucide"></i>
                        Versand gestartet – 612 Empfänger, portionsweise über den Clubserver.
                    </div>
                </div>
            </div>

            <p class="demo-caption">
                <i data-icon="layers" class="lucide" aria-hidden="true"></i>
                <span class="demo-caption-text">Schritt 1 – Inhalt.</span>
                <span class="demo-progress" aria-hidden="true"></span>
            </p>
        </div>
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
