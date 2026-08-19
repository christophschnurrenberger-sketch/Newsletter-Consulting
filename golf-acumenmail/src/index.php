<?php
$page = [
    'title'       => 'Newsletter für Golfclubs: einrichten und betreuen lassen',
    'description' => 'Wir richten Golfclubs den Newsletter ein – System auf dem eigenen Webspace, Clubdesign, Automationen – und schreiben ihn auf Wunsch monatlich. Clubcheck ab 290 €, Einrichtung ab 1.490 €, Betreuung ab 390 € im Monat.',
    'section'     => '',
    'path'        => '',
    'hero'        => false,
    'body_class'  => 'is-home',
];
include __DIR__ . '/partials/header.php';
?>

<!-- Hero: was wir tun, in zwei Sätzen -------------------------------------- -->
<section class="section home-hero">
    <div class="container">
        <p class="section-kicker">Für Golfclubs, Golfanlagen und Golfschulen</p>

        <h1 class="home-hero-title">
            Wir richten Golfclubs den
            <span class="mark">Newsletter ein.</span>
        </h1>

        <div class="home-hero-foot">
            <p class="home-hero-lead">
                Und schreiben ihn monatlich, wenn Sie wollen. Vom Blick auf den Adressbestand über
                das fertige System im Clubdesign bis zur Ausgabe, die freitags rausgeht. Die
                Software läuft danach auf dem Webspace Ihres Clubs und kostet im Monat nichts –
                kein Preis pro Kontakt, keine Stufe ab 1.200 Mitgliedern.
            </p>
            <div class="home-hero-actions">
                <a href="<?= e(url('kontakt.php')) ?>" class="btn-primary-custom">Kostenlose Club-Analyse</a>
                <a href="<?= e(url('preise.php')) ?>" class="btn-secondary">Was das kostet</a>
            </div>
        </div>
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

<!-- Das Angebot: drei Pakete, mit Preisen ---------------------------------- -->
<section class="section">
    <div class="container">
        <div class="section-head animate-on-scroll">
            <p class="section-kicker">Das Angebot</p>
            <h2 class="section-title">Drei Schritte, und Sie können nach jedem aufhören</h2>
            <p class="section-lead">
                Die meisten Clubs fangen mit dem Clubcheck an. Ob danach überhaupt etwas folgt,
                entscheiden Sie – und manchmal raten wir selbst ab.
            </p>
        </div>

        <div class="package-grid">
            <article class="package-card animate-on-scroll">
                <p class="package-label">Schritt 1 · Klarheit</p>
                <h3>Clubcheck</h3>
                <p>Was Ihr Adressbestand hergibt, was rechtlich trägt und wo der erste Hebel liegt.</p>
                <ul class="package-list">
                    <li><i data-icon="check" class="lucide"></i><span>Adressen, Einwilligungen, Datenqualität</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Technikcheck Ihres Hostings</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Chancenkarte und Gespräch mit dem Vorstand</span></li>
                </ul>
                <div class="package-meta">
                    <span>Investition</span>
                    <strong>ab 290 € einmalig</strong>
                    <small>Wird beim Saison-Setup verrechnet.</small>
                </div>
                <p style="margin-top:1.2rem;"><a href="<?= e(url('leistungen/clubcheck.php')) ?>" class="btn-secondary" style="width:100%;">Details ansehen</a></p>
            </article>

            <article class="package-card is-featured animate-on-scroll">
                <span class="package-flag">Häufig gewählt</span>
                <p class="package-label">Schritt 2 · Einrichtung</p>
                <h3>Saison-Setup</h3>
                <p>Ein versandbereites System im Design Ihres Clubs – Software inklusive, sie gehört danach dem Club.</p>
                <ul class="package-list">
                    <li><i data-icon="check" class="lucide"></i><span>Newsletter-System auf dem Clubserver eingerichtet</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Zustell­barkeit: SMTP, SPF, DKIM, Bounces</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Clubdesign, Anmeldeformular, Listen und Segmente</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Zwei Auto­mationen, Redaktionsplan, Einweisung</span></li>
                </ul>
                <div class="package-meta">
                    <span>Investition</span>
                    <strong>ab 1.490 € einmalig</strong>
                    <small>Danach keine laufenden Lizenzkosten.</small>
                </div>
                <p style="margin-top:1.2rem;"><a href="<?= e(url('leistungen/saison-setup.php')) ?>" class="btn-primary-custom" style="width:100%;">Details ansehen</a></p>
            </article>

            <article class="package-card animate-on-scroll">
                <p class="package-label">Schritt 3 · Rhythmus</p>
                <h3>Clubbetreuung</h3>
                <p>Für Clubs, bei denen der Newsletter sonst wieder liegen bleibt: Wir schreiben und versenden.</p>
                <ul class="package-list">
                    <li><i data-icon="check" class="lucide"></i><span>Monatliche Ausgaben nach Redaktionsplan</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Turnier- und Eventkommunikation</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Auswertung und Bericht für den Vorstand</span></li>
                </ul>
                <div class="package-meta">
                    <span>Investition</span>
                    <strong>ab 390 € / Monat</strong>
                    <small>Monatlich kündbar, kein Jahresvertrag.</small>
                </div>
                <p style="margin-top:1.2rem;"><a href="<?= e(url('leistungen/clubbetreuung.php')) ?>" class="btn-secondary" style="width:100%;">Details ansehen</a></p>
            </article>
        </div>

        <p style="margin-top:2.5rem;">
            <a href="<?= e(url('preise.php')) ?>" class="btn-secondary">Alle Preise und was enthalten ist</a>
        </p>
    </div>
</section>

<!-- Der Unterschied zu den bekannten Anbietern ----------------------------- -->
<section class="section section-alt">
    <div class="container">
        <div class="section-head animate-on-scroll">
            <p class="section-kicker">Der Unterschied</p>
            <h2 class="section-title">Mieten oder besitzen</h2>
            <p class="section-lead">
                Die bekannten Newsletter-Anbieter vermieten Software. Wir richten eine ein, die
                danach dem Club gehört.
            </p>
        </div>

        <div class="split-grid is-wide-left is-top">
            <div class="prose animate-on-scroll">
                <p>
                    Mietlösungen rechnen nach Kontakten oder versendeten Mails ab. Für einen Club
                    mit 900 Mitgliedern, 400 Gastspielern und einem Kursverteiler heißt das: Die
                    Rechnung wächst mit dem Verteiler – und die Mitgliederdaten liegen beim
                    Anbieter.
                </p>
                <p>
                    Wir gehen den anderen Weg. Das System wird einmal auf dem Webspace des Clubs
                    eingerichtet und gehört danach dem Club. Wir sind die Agentur davor und
                    daneben: Wir richten ein, weisen ein und schreiben mit – die Software selbst
                    stellt uns keine Rechnung, also Ihnen auch nicht.
                </p>
            </div>

            <div class="callout animate-on-scroll">
                <i data-icon="help-circle" class="lucide"></i>
                <p>
                    <strong>Und wenn Sie uns nicht mehr wollen?</strong>
                    Dann bleibt alles da: Adressen, Vorlagen, Auto­mationen, die gesamte Historie.
                    Das System läuft weiter, auch ohne uns – bei einer Mietlösung geht genau das
                    nicht.
                </p>
            </div>
        </div>

        <div class="table-scroll animate-on-scroll" style="margin-top:3rem;">
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
                    <tr>
                        <th scope="row">Laufend pro Monat</th>
                        <td class="no">je nach Tarif und Verteilergröße</td>
                        <td class="yes">0 € für die Software</td>
                    </tr>
                    <tr>
                        <th scope="row">Verteiler wächst</th>
                        <td class="no">nächste Preisstufe</td>
                        <td class="yes">ändert nichts</td>
                    </tr>
                    <tr>
                        <th scope="row">Mitgliederdaten</th>
                        <td class="no">beim Anbieter</td>
                        <td class="yes">auf dem Clubserver</td>
                    </tr>
                    <tr>
                        <th scope="row">Wenn Sie aufhören</th>
                        <td class="no">Daten und Vorlagen weg</td>
                        <td class="yes">alles bleibt im Club</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p style="margin-top:2.2rem;">
            <a href="<?= e(url('preise.php')) ?>" class="btn-secondary">Die Rechnung über fünf Jahre</a>
        </p>
    </div>
</section>

<!-- Die Software, die im Setup steckt -------------------------------------- -->
<section class="section section-dark">
    <div class="container">

        <div class="tool-intro-grid">
            <div class="animate-on-scroll">
                <p class="section-kicker on-dark">Im Saison-Setup enthalten</p>
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
                <span>Newslettersystem <span class="ort">Golfclub Musterhausen</span></span>
                <span class="hinweis">Ansicht – nicht bedienbar</span>
            </div>
            <div class="demo-frame-buehne">
                <iframe src="<?= e(url('demo/baukasten.html')) ?>" loading="lazy"
                        title="Der Newsletter-Baukasten: links die Bausteine, in der Mitte die Ausgabe mit Überschrift, Text und Knopf, rechts die Gestaltung."></iframe>
            </div>
            <figcaption>
                <b>Kein Bild.</b>
                <span>Die Oberfläche selbst, mit einer Ausgabe mitten im Schreiben. Drei Bausteine,
                kein HTML. <a href="<?= e(url('software/newsletter-baukasten.php')) ?>">Wie der
                Baukasten arbeitet</a></span>
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

<!-- Wofür Clubs uns holen --------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="section-head animate-on-scroll">
            <p class="section-kicker">Wofür Clubs uns holen</p>
            <h2 class="section-title">Fünf Aufgaben, die auf jedem Clubtisch liegen</h2>
            <p class="section-lead">
                Keine davon ist ein Golfproblem. Zu jeder gibt es eine Seite mit dem, was wir
                konkret einrichten.
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

<!-- Wissen ----------------------------------------------------------------- -->
<section class="section">
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
