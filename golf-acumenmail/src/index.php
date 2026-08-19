<?php
$page = [
    'title'       => 'Newsletter-Marketing für Golfclubs',
    'description' => 'Wir bauen Golfclubs eine Mitgliederkommunikation, die auch die erreicht, die selten auf der Anlage sind – samt Newsletter-System auf dem eigenen Server, ohne Kosten pro Kontakt.',
    'section'     => '',
    'path'        => '',
    'hero'        => false,
    'body_class'  => 'is-home',
];
include __DIR__ . '/partials/header.php';
?>

<!-- Hero: eine Aussage, sonst nichts ------------------------------------- -->
<section class="section home-hero">
    <div class="container">
        <p class="section-kicker">Für Golfclubs, Golfanlagen und Golfschulen</p>

        <h1 class="home-hero-title">
            Ihr Aushang erreicht nur die,
            <span class="mark">die ohnehin da sind.</span>
        </h1>

        <div class="home-hero-foot">
            <p class="home-hero-lead">
                Die anderen fünfhundert erfahren vom Turnier, wenn es vorbei ist. Wir ändern das –
                mit Clubpost, die ankommt, und einem Newsletter-System, das auf Ihrem eigenen
                Server läuft. Ohne Monatsgebühr, ohne Preisstufe ab 1.200 Mitgliedern.
            </p>
            <div class="home-hero-actions">
                <a href="<?= e(url('kontakt.php')) ?>" class="btn-primary-custom">Kostenlose Club-Analyse</a>
                <a href="<?= e(url('software/')) ?>" class="btn-secondary">Software ansehen</a>
            </div>
        </div>
    </div>
</section>

<!-- Direkt das Werkzeug zeigen, statt es zu beschreiben -------------------- -->
<section class="section-sm">
    <div class="container-wide">
        <figure class="demo-frame" data-demo-frame>
            <div class="demo-frame-kopf">
                <span>Newslettersystem <span class="ort">Golfclub Musterhausen</span></span>
                <span class="hinweis">Ansicht – nicht bedienbar</span>
            </div>
            <div class="demo-frame-buehne">
                <iframe src="<?= e(url('demo/baukasten.html')) ?>" loading="lazy"
                        title="Der Newsletter-Baukasten: links die Bausteine, in der Mitte die Ausgabe mit Überschrift, Text und Knopf, rechts die Gestaltung."></iframe>
            </div>
            <figcaption>
                <b>Kein Bild.</b>
                <span>Die Oberfläche selbst, mit derselben Formatvorlage wie im Sekretariat –
                eine Ausgabe mitten im Schreiben. Drei Bausteine, kein HTML.
                <a href="<?= e(url('software/newsletter-baukasten.php')) ?>">Wie der Baukasten
                arbeitet</a></span>
            </figcaption>
        </figure>
    </div>
</section>

<div class="trust-strip">
    <div class="container trust-strip-inner">
        <span class="trust-strip-item"><i data-icon="server" class="lucide"></i>Läuft auf Ihrem eigenen Webspace</span>
        <span class="trust-strip-item"><i data-icon="euro" class="lucide"></i>Keine Kosten pro Kontakt</span>
        <span class="trust-strip-item"><i data-icon="lock" class="lucide"></i>Mitgliederdaten bleiben im Club</span>
        <span class="trust-strip-item"><i data-icon="user-check" class="lucide"></i>Ein fester Ansprechpartner</span>
    </div>
</div>

<!-- Die Ausgangslage ------------------------------------------------------ -->
<section class="section">
    <div class="container">
        <div class="section-head animate-on-scroll">
            <p class="section-kicker">Wofür Clubs uns holen</p>
            <h2 class="section-title">Fünf Aufgaben, die auf jedem Clubtisch liegen</h2>
            <p class="section-lead">
                Keine davon ist ein Golfproblem.
            </p>
        </div>

        <div class="link-card-grid">
<?php foreach ($NAV['loesungen']['children'] as $child): ?>
            <a href="<?= e(url($child['url'])) ?>" class="link-card animate-on-scroll">
                <h3><?= e($child['label']) ?></h3>
                <p><?= e($child['desc']) ?></p>
                <span class="link-card-more">Ansehen<i data-icon="arrow-right" class="lucide"></i></span>
            </a>
<?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Die Software ---------------------------------------------------------- -->
<section class="section section-dark">
    <div class="container">

        <div class="tool-intro-grid">
            <div class="animate-on-scroll">
                <p class="section-kicker on-dark">Die Software</p>
                <h2 class="section-title">Das Werkzeug gab es vor dem Angebot.</h2>
                <p class="section-lead">
                    Wir haben es für eigene Projekte gebaut, weil uns die Rechnung der Mietanbieter
                    nicht gefiel. Es liegt auf dem Webspace des Clubs, rechnet nicht nach Kontakten
                    ab und lässt sich bedienen, ohne dass jemand HTML kann.
                </p>
                <p style="margin-top:2.2rem;">
                    <a href="<?= e(url('software/')) ?>" class="btn-primary-custom">Alle Funktionen</a>
                </p>
            </div>

            <div class="tool-claim-list animate-on-scroll">
                <div class="tool-claim">
                    <strong>0 € im Monat</strong>
                    <span>Für die Software selbst. Ob 300 oder 3.000 Mitglieder ändert daran nichts.</span>
                </div>
                <div class="tool-claim">
                    <strong>PHP 8 genügt</strong>
                    <span>SQLite reicht als Datenbank. Kein eigener Server, kein Docker, kein Composer.</span>
                </div>
                <div class="tool-claim">
                    <strong>Rechtssicher ab Werk</strong>
                    <span>Double-Opt-in mit Protokoll, Abmeldelink, Impressum, List-Unsubscribe.
                    Der Footer lässt sich nicht wegklicken.</span>
                </div>
            </div>
        </div>

        <figure class="demo-frame home-demo animate-on-scroll" data-demo-frame>
            <div class="demo-frame-kopf">
                <span>Newslettersystem <span class="ort">Auto­mationen</span></span>
                <span class="hinweis">Ansicht – nicht bedienbar</span>
            </div>
            <div class="demo-frame-buehne">
                <iframe src="<?= e(url('demo/automation.html')) ?>" loading="lazy"
                        title="Der Ablauf-Editor: Auslöser, Wartezeiten, E-Mail-Schritte und eine Bedingung mit Ja- und Nein-Zweig."></iframe>
            </div>
            <figcaption>
                <b>Einmal bauen.</b>
                <span>Die Willkommens­strecke des Golfclubs Musterhausen, wie sie im System
                steht. Danach läuft sie für jedes neue Mitglied von allein.
                <a href="<?= e(url('software/automationen.php')) ?>">Was sich damit bauen
                lässt</a></span>
            </figcaption>
        </figure>

        <div class="tool-feature-grid animate-on-scroll" aria-label="Funktionen des Newsletter-Systems">
<?php foreach ($NAV['software']['children'] as $child): ?>
            <a href="<?= e(url($child['url'])) ?>" class="tool-feature">
                <strong><?= e($child['label']) ?></strong>
                <p><?= e($child['desc']) ?></p>
            </a>
<?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Der ehrliche Teil ------------------------------------------------------ -->
<section class="section section-alt">
    <div class="container">
        <div class="split-grid is-wide-left is-top">
            <div class="animate-on-scroll">
                <p class="section-kicker">Wann sich das nicht lohnt</p>
                <h2 class="section-title">Manchmal ist die Antwort: lassen Sie es.</h2>
                <div class="prose" style="margin-top:1.6rem;">
                    <p>
                        Wenn Ihr Club zweimal im Jahr einen Rundbrief verschickt und keine
                        Auto­mationen braucht, ist ein günstiger Miettarif die einfachere Wahl. Das
                        sagen wir im Clubcheck auch dann, wenn wir damit den Auftrag verlieren.
                    </p>
                    <p>
                        Es lohnt sich ungefähr ab 300 Empfängern, weil der Aufwand pro Ausgabe
                        gleich bleibt – egal ob 300 oder 3.000 Leute sie bekommen. Und es lohnt
                        sich dann besonders, wenn jemand im Club den Newsletter eigentlich machen
                        will, aber im Juni nie dazu kommt.
                    </p>
                </div>
            </div>

            <div class="quote-card animate-on-scroll">
                <blockquote>
                    „Wir hatten den Newsletter zweimal angefangen und zweimal wieder eingestellt.
                    Nicht weil er schlecht war, sondern weil im Juni niemand Zeit hatte.“
                </blockquote>
                <figcaption>
                    Sinngemäß aus mehreren Gesprächen mit Clubsekretariaten. Genau deshalb bringen
                    ein Redaktionsplan und zwei Auto­mationen mehr als die schönste Vorlage.
                </figcaption>
            </div>
        </div>
    </div>
</section>

<!-- Zusammenarbeit --------------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="section-head animate-on-scroll">
            <p class="section-kicker">Zusammenarbeit</p>
            <h2 class="section-title">Drei Wege anzufangen</h2>
            <p class="section-lead">
                Je nachdem, ob zuerst Klarheit, Einrichtung oder ein verlässlicher Rhythmus fehlt.
            </p>
        </div>

        <div class="link-card-grid">
<?php foreach ($NAV['leistungen']['children'] as $child): ?>
            <a href="<?= e(url($child['url'])) ?>" class="link-card animate-on-scroll">
                <h3><?= e($child['label']) ?></h3>
                <p><?= e($child['desc']) ?></p>
                <span class="link-card-more">Ansehen<i data-icon="arrow-right" class="lucide"></i></span>
            </a>
<?php endforeach; ?>
        </div>

        <p style="margin-top:2.5rem;">
            <a href="<?= e(url('preise.php')) ?>" class="btn-secondary">Was das kostet</a>
        </p>
    </div>
</section>

<!-- Wissen ----------------------------------------------------------------- -->
<section class="section section-alt">
    <div class="container">
        <div class="split-grid is-wide-right is-top">
            <div class="animate-on-scroll">
                <p class="section-kicker">Wissen</p>
                <h2 class="section-title">Aus der Praxis im Club</h2>
                <p class="section-lead">
                    Zum Nachlesen, auch wenn daraus nie eine Zusammenarbeit wird.
                </p>
                <p style="margin-top:1.8rem;">
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
                    <p>Warum „Newsletter 04/2026“ die schlechteste aller Betreffzeilen ist.</p>
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
