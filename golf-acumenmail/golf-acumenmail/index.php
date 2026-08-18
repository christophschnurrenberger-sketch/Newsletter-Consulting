<?php
$page = [
    'title'       => 'Newsletter-Marketing für Golfclubs',
    'description' => 'Volle Turniere, gebundene Mitglieder, wiederkehrende Gastspieler – mit einer Mitgliederkommunikation, die nicht am schwarzen Brett endet. Inklusive Newsletter-System auf dem Server Ihres Clubs.',
    'section'     => '',
    'path'        => '',
    'css'         => ['demo'],
    'js'          => ['demo'],
    'hero'        => false,
    'body_class'  => 'is-home',
];
include __DIR__ . '/partials/header.php';
?>

<!-- Hero ---------------------------------------------------------------- -->
<section id="hero" class="hero">
    <div class="container">
        <div class="hero-grid">

            <div class="hero-copy">
                <span class="hero-badge">
                    <span class="dot" aria-hidden="true"><i data-icon="flag" class="lucide"></i></span>
                    Für Golfclubs, Golfanlagen und Golfschulen
                </span>

                <h1 class="hero-title">
                    Volle Turniere und treue Mitglieder – mit Newslettern, die
                    <span class="hero-accent">wirklich gelesen werden.</span>
                </h1>

                <p class="hero-lead">
                    Das schwarze Brett erreicht nur, wer ohnehin da ist. Wir bauen Golfclubs eine
                    Mitgliederkommunikation, die auch die anderen erreicht – und liefern das
                    Newsletter-System gleich mit. Es läuft auf dem Server Ihres Clubs, ohne
                    monatliche Gebühr.
                </p>

                <div class="hero-actions">
                    <a href="<?= e(url('kontakt.php')) ?>" class="btn-primary-custom">Kostenlose Club-Analyse</a>
                    <a href="<?= e(url('software/')) ?>" class="btn-secondary">
                        Software ansehen<i data-icon="arrow-right" class="lucide"></i>
                    </a>
                </div>

                <div class="hero-trust-bar" aria-label="Vertrauenssignale">
                    <span class="hero-trust-item"><i data-icon="shield-check" class="lucide"></i>DSGVO-konform, Daten bleiben im Club</span>
                    <span class="hero-trust-item"><i data-icon="euro" class="lucide"></i>Keine Kosten pro Kontakt</span>
                    <span class="hero-trust-item"><i data-icon="clock" class="lucide"></i>Antwort binnen 24 Stunden</span>
                </div>
            </div>

            <div class="hero-visual" aria-hidden="true">
              <div class="hero-mail-wrap">
                <div class="hero-mail">
                    <div class="hero-mail-bar">
                        <span class="hero-mail-dots"><i></i><i></i><i></i></span>
                        <span class="hero-mail-subject">Clubmeisterschaft 2026 – Anmeldung ist offen</span>
                    </div>
                    <div class="hero-mail-head">
                        <span class="hero-mail-wordmark">Golfclub <em>Musterhausen</em></span>
                        <span class="hero-mail-claim">Clubnachrichten</span>
                    </div>
                    <div class="hero-mail-body">
                        <span class="hero-mail-kicker">Ausgabe 04 · Juli</span>
                        <h2 style="font-size:1.28rem; line-height:1.25;">Clubmeisterschaft 2026 – Anmeldung ist offen</h2>
                        <p>Liebe(r) <span class="ph">{{vorname}}</span>, am Wochenende vom 12. bis 14. Juli
                            spielen wir die Clubmeisterschaft. 60 Startplätze, alle Klassen, Siegerehrung
                            im Clubhaus.</p>
                        <?php include __DIR__ . '/partials/golf-scene.php'; ?>
                        <span class="hero-mail-cta">Jetzt Startzeit sichern</span>
                    </div>
                    <div class="hero-mail-foot">Golfclub Musterhausen e.V. · Impressum · Abmelden</div>
                </div>

                <span class="hero-chip hero-chip-1">
                    <i data-icon="users" class="lucide"></i> 612 Empfänger
                </span>
                <span class="hero-chip hero-chip-2">
                    <i data-icon="calendar-check" class="lucide"></i> Freitag, 9:00 geplant
                </span>
              </div>
                <span class="hero-visual-note">Beispielansicht</span>
            </div>

        </div>
    </div>
</section>

<div class="trust-strip">
    <div class="container trust-strip-inner">
        <span class="trust-strip-item"><i data-icon="server" class="lucide"></i>Software auf Ihrem eigenen Webspace</span>
        <span class="trust-strip-item"><i data-icon="euro" class="lucide"></i>Keine laufenden Lizenzkosten</span>
        <span class="trust-strip-item"><i data-icon="user-check" class="lucide"></i>Ein fester Ansprechpartner</span>
        <span class="trust-strip-item"><i data-icon="lock" class="lucide"></i>Double-Opt-in mit Protokoll</span>
    </div>
</div>

<!-- Ausgangslage --------------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="section-head animate-on-scroll">
            <span class="section-kicker">Ausgangslage</span>
            <h2 class="section-title">Fünf Situationen, die jeder Club kennt</h2>
            <p class="section-lead">
                Keine davon ist ein Golfproblem. Alle fünf sind Kommunikationsprobleme – die
                Information ist da, sie erreicht nur nicht die Richtigen zur richtigen Zeit.
            </p>
        </div>

        <div class="link-card-grid">
<?php foreach ($NAV['loesungen']['children'] as $child): ?>
            <a href="<?= e(url($child['url'])) ?>" class="link-card animate-on-scroll">
                <span class="link-card-icon"><i data-icon="<?= e($child['icon']) ?>" class="lucide"></i></span>
                <h3><?= e($child['label']) ?></h3>
                <p><?= e($child['desc']) ?></p>
                <span class="link-card-more">Ansehen<i data-icon="arrow-right" class="lucide"></i></span>
            </a>
<?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Die Software --------------------------------------------------------- -->
<section class="section section-dark">
    <div class="container">
        <div class="tool-intro-grid">
            <div class="animate-on-scroll">
                <span class="section-kicker on-dark">Die Software</span>
                <h2 class="section-title">Das Werkzeug haben wir schon gebaut.<br>Sie klicken nur noch zusammen.</h2>
                <p class="section-lead">
                    Kein Abo pro Kontakt, kein Datenexport zu einem Anbieter in Übersee, keine
                    Preisstufe, die ab 1.200 Mitgliedern wehtut. Das System läuft auf dem Webspace
                    Ihres Clubs – bedienbar, ohne dass irgendjemand HTML können muss.
                </p>
                <p style="margin-top:1.8rem;">
                    <a href="<?= e(url('software/')) ?>" class="btn-primary-custom btn-on-dark">Alle Funktionen ansehen</a>
                </p>
            </div>

            <div class="tool-claim-list animate-on-scroll">
                <div class="tool-claim">
                    <i data-icon="server" class="lucide"></i>
                    <span><strong>Läuft auf Ihrem Server</strong>
                    PHP 8 und eine Datenbank genügen. Die Mitgliederdaten verlassen den Club nicht.</span>
                </div>
                <div class="tool-claim">
                    <i data-icon="euro" class="lucide"></i>
                    <span><strong>Keine laufenden Lizenzkosten</strong>
                    Ob 300 oder 3.000 Mitglieder – an den Kosten ändert das nichts.</span>
                </div>
                <div class="tool-claim">
                    <i data-icon="lock" class="lucide"></i>
                    <span><strong>Rechtssicher ab Werk</strong>
                    Double-Opt-in mit Protokoll, Abmeldelink, Impressum, List-Unsubscribe.</span>
                </div>
            </div>
        </div>

        <!-- Bewegte Demo: so entsteht eine Ausgabe -->
        <div class="demo-block animate-on-scroll">
            <div class="demo-head">
                <span class="demo-step-label">Live-Demo</span>
                <h3>So entsteht ein Clubnewsletter</h3>
                <p>Bausteine aus der Leiste ziehen – oder antippen, dann hängen sie sich unten an.
                    Kopfzeile und Footer gehören zur Clubvorlage und lassen sich nicht versehentlich
                    verschieben. Keine Zeile HTML.</p>
            </div>

            <div class="demo-shell is-static" data-demo="builder">
                <div class="demo-chrome">
                    <span class="demo-dots" aria-hidden="true"><i></i><i></i><i></i></span>
                    <span class="demo-url">golfclub-musterhausen.de/newsletter/admin/kampagne.php</span>
                    <button type="button" class="demo-control demo-toggle" aria-pressed="true">
                        <i data-icon="play" class="lucide"></i><span class="demo-toggle-label">Abspielen</span>
                    </button>
                    <button type="button" class="demo-control demo-replay" aria-label="Demo von vorn abspielen">
                        <i data-icon="rotate-ccw" class="lucide"></i>
                    </button>
                </div>

                <div class="demo-body demo-builder">
                    <aside class="demo-palette">
                        <p class="demo-panel-title">Bausteine</p>
                        <div class="demo-palette-list">
                            <span class="demo-pal pal-1"><i data-icon="type" class="lucide"></i>Überschrift</span>
                            <span class="demo-pal pal-2"><i data-icon="align-left" class="lucide"></i>Textabsatz</span>
                            <span class="demo-pal pal-3"><i data-icon="image" class="lucide"></i>Bild</span>
                            <span class="demo-pal pal-4"><i data-icon="button" class="lucide"></i>Knopf</span>
                            <span class="demo-pal pal-5"><i data-icon="columns" class="lucide"></i>Zwei Spalten</span>
                        </div>
                    </aside>

                    <div class="demo-canvas">
                        <div class="demo-mail">
                            <div class="demo-mail-head">
                                <span class="demo-wordmark">Golfclub <em>Musterhausen</em></span>
                                <span class="demo-locked">Kopfzeile · fest</span>
                            </div>
                            <div class="demo-drop">
                                <p class="demo-drop-hint">Hierhin kommen die Bausteine der Ausgabe</p>
                                <div class="demo-blk blk-1">
                                    <h4>Clubmeisterschaft 2026 – Anmeldung ist offen</h4>
                                </div>
                                <div class="demo-blk blk-2">
                                    <p>Liebe(r) <span class="ph">{{vorname}}</span>, am Wochenende vom 12. bis
                                        14. Juli spielen wir die Clubmeisterschaft. 60 Startplätze, alle Klassen.</p>
                                </div>
                                <div class="demo-blk blk-3">
                                    <?php include __DIR__ . '/partials/golf-scene.php'; ?>
                                </div>
                                <div class="demo-blk blk-4">
                                    <span class="demo-cta">Jetzt Startzeit sichern</span>
                                </div>
                            </div>
                            <div class="demo-mail-foot">
                                <span>Golfclub Musterhausen e.V. · Impressum</span>
                                <span>Abmelden</span>
                            </div>
                        </div>
                    </div>

                    <aside class="demo-settings">
                        <p class="demo-panel-title">Gestaltung</p>
                        <div class="demo-set-row"><span>Breite</span><b>620 px</b></div>
                        <div class="demo-set-row"><span>Überschrift</span><b>Georgia</b></div>
                        <div class="demo-set-row">
                            <span>Clubfarbe</span>
                            <span class="demo-swatches">
                                <span class="demo-swatch swatch-1" style="background:#14243A"></span>
                                <span class="demo-swatch swatch-2" style="background:#1E6B45"></span>
                                <span class="demo-swatch swatch-3" style="background:#B08A3E"></span>
                            </span>
                        </div>
                        <div class="demo-set-row"><span>Footer</span><b>Pflichtangaben</b></div>
                        <p class="demo-save"><i data-icon="check-circle" class="lucide"></i>Gespeichert um 10:42</p>
                    </aside>

                    <span class="demo-ghost ghost-1" style="top:52px; --dx:206px; --dy:76px;"><i data-icon="type" class="lucide"></i>Überschrift</span>
                    <span class="demo-ghost ghost-2" style="top:94px; --dx:206px; --dy:86px;"><i data-icon="align-left" class="lucide"></i>Textabsatz</span>
                    <span class="demo-ghost ghost-3" style="top:136px; --dx:206px; --dy:96px;"><i data-icon="image" class="lucide"></i>Bild</span>
                    <span class="demo-ghost ghost-4" style="top:178px; --dx:206px; --dy:112px;"><i data-icon="button" class="lucide"></i>Knopf</span>

                    <svg class="demo-cursor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4.04 4.69a.5.5 0 0 1 .65-.65l16 6.5a.5.5 0 0 1-.06.95l-6.13 1.58a2 2 0 0 0-1.43 1.43l-1.58 6.13a.5.5 0 0 1-.95.06z"
                              fill="#16261C" stroke="#fff" stroke-width="1.2"></path>
                    </svg>
                </div>

                <p class="demo-caption">
                    <i data-icon="mouse-pointer" class="lucide" aria-hidden="true"></i>
                    <span class="demo-caption-text">Die Vorlage des Clubs steht bereits.</span>
                    <span class="demo-progress" aria-hidden="true"></span>
                </p>
            </div>
        </div>

        <div class="tool-feature-grid animate-on-scroll" aria-label="Funktionen des Newsletter-Systems">
<?php foreach ($NAV['software']['children'] as $child): ?>
            <a href="<?= e(url($child['url'])) ?>" class="tool-feature">
                <i data-icon="<?= e($child['icon']) ?>" class="lucide"></i>
                <strong><?= e($child['label']) ?></strong>
                <p><?= e($child['desc']) ?></p>
            </a>
<?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Leistungen ----------------------------------------------------------- -->
<section class="section section-alt">
    <div class="container">
        <div class="section-head animate-on-scroll">
            <span class="section-kicker">Zusammenarbeit</span>
            <h2 class="section-title">Drei Wege, im Club anzufangen</h2>
            <p class="section-lead">
                Nicht jeder Club braucht sofort die volle Betreuung. Entscheidend ist, ob zuerst
                Klarheit, Einrichtung oder ein verlässlicher Rhythmus fehlt.
            </p>
        </div>

        <div class="link-card-grid">
<?php foreach ($NAV['leistungen']['children'] as $child): ?>
            <a href="<?= e(url($child['url'])) ?>" class="link-card animate-on-scroll">
                <span class="link-card-icon"><i data-icon="<?= e($child['icon']) ?>" class="lucide"></i></span>
                <h3><?= e($child['label']) ?></h3>
                <p><?= e($child['desc']) ?></p>
                <span class="link-card-more">Ansehen<i data-icon="arrow-right" class="lucide"></i></span>
            </a>
<?php endforeach; ?>
        </div>

        <p style="margin-top:2rem;">
            <a href="<?= e(url('preise.php')) ?>" class="btn-secondary">Preise im Detail</a>
        </p>
    </div>
</section>

<!-- Wissen --------------------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="split-grid is-wide-left is-top">
            <div class="animate-on-scroll">
                <span class="section-kicker">Wissen</span>
                <h2 class="section-title">Aus der Praxis im Club</h2>
                <p class="section-lead">
                    Was in Golfclubs tatsächlich funktioniert und was nicht – zum Nachlesen, auch
                    wenn daraus keine Zusammenarbeit wird.
                </p>
                <p style="margin-top:1.6rem;">
                    <a href="<?= e(url('wissen/')) ?>" class="btn-secondary">Alle Beiträge</a>
                </p>
            </div>

            <div class="related-grid animate-on-scroll">
                <a href="<?= e(url('wissen/newsletter-jahresplan-golfclub.php')) ?>" class="related-card">
                    <span>Redaktionsplan</span><strong>Der Newsletter-Jahresplan</strong>
                    <p>Zwölf Monate Clubkommunikation – und warum der Winter der wichtigste Teil ist.</p>
                </a>
                <a href="<?= e(url('wissen/betreffzeilen-golfclub.php')) ?>" class="related-card">
                    <span>Redaktion</span><strong>Betreffzeilen, die wirken</strong>
                    <p>Fünf Muster, die in Golfclubs zuverlässig geöffnet werden.</p>
                </a>
                <a href="<?= e(url('wissen/dsgvo-mitgliederdaten-golfclub.php')) ?>" class="related-card">
                    <span>Recht</span><strong>Mitgliederdaten und DSGVO</strong>
                    <p>Wo Vereinsinformation aufhört und Werbung anfängt.</p>
                </a>
                <a href="<?= e(url('faq.php')) ?>" class="related-card">
                    <span>FAQ</span><strong>Häufige Fragen</strong>
                    <p>Recht, Aufwand, PC CADDIE, Kosten – kompakt beantwortet.</p>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
