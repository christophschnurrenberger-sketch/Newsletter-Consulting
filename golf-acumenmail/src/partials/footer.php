<?php
/**
 * Fuß jeder Seite: Handlungsaufforderung, Footer mit Sitemap und die Skripte.
 *
 * Optional in $page:
 *   cta   ['title' =>, 'text' =>, 'primary' => [Text, Pfad], 'secondary' => [Text, Pfad]]
 *         false unterdrückt das Band (etwa auf der Kontaktseite)
 *   js    Zusätzliche Skripte, etwa ['club']
 */

$cta = $page['cta'] ?? null;
if ($cta === null) {
    // Standardband – gilt überall dort, wo die Seite nichts eigenes setzt.
    $cta = [
        'title'     => 'Wir sehen uns Ihren Club an. Kostenlos.',
        'text'      => 'Dreißig Minuten, telefonisch oder per Video, mit Vorstand, Sekretariat oder beiden. Danach wissen Sie, was Ihr Adressbestand hergibt und was wir an Ihrer Stelle täten – auch wenn das heißt: vorerst nichts.',
        'primary'   => ['Club-Analyse anfragen', 'kontakt.php'],
        'secondary' => ['Was das kostet', 'preise.php'],
    ];
}
$js = $page['js'] ?? [];
?>
</main>

<?php if ($cta !== false): ?>
<!-- Handlungsaufforderung ---------------------------------------------- -->
<section class="cta-band">
    <div class="container cta-inner">
        <div class="cta-copy">
            <h2 class="cta-title"><?= e($cta['title']) ?></h2>
            <p class="cta-text"><?= e($cta['text']) ?></p>
        </div>
        <div class="cta-actions">
            <a href="<?= e(url($cta['primary'][1])) ?>" class="btn-primary-custom btn-on-dark"><?= e($cta['primary'][0]) ?></a>
<?php if (!empty($cta['secondary'])): ?>
            <a href="<?= e(url($cta['secondary'][1])) ?>" class="btn-secondary btn-ghost-dark"><?= e($cta['secondary'][0]) ?></a>
<?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Footer ------------------------------------------------------------- -->
<footer class="site-footer">
    <div class="container">

        <div class="footer-top">
            <div class="footer-brand">
                <span class="site-brand">
                    <span class="site-brand-mark" aria-hidden="true">A</span>
                    <span class="site-brand-text">
                        <span class="site-brand-name"><?= e($SITE['name']) ?></span>
                        <span class="site-brand-line"><?= e($SITE['claim']) ?></span>
                    </span>
                </span>
                <p class="footer-brand-copy">
                    Mitgliederkommunikation für Golfclubs, Golfanlagen und Golfschulen:
                    Segmente, Redaktionsplan, Auto­mationen – und ein Newsletter-System,
                    das auf dem Server des Clubs läuft.
                </p>
                <address class="footer-address">
                    <?= e($SITE['owner']) ?><br>
                    <?= e($SITE['street']) ?><br>
                    <?= e($SITE['city']) ?><br>
                    <a href="tel:<?= e($SITE['phone_link']) ?>"><?= e($SITE['phone']) ?></a><br>
                    <a href="mailto:<?= e($SITE['email']) ?>"><?= e($SITE['email']) ?></a>
                </address>
            </div>

            <div class="footer-nav">
<?php foreach ($NAV as $item):
    if (empty($item['children'])) { continue; }
?>
                <div class="footer-col">
                    <h2 class="footer-eyebrow"><?= e($item['label']) ?></h2>
                    <ul class="footer-link-list">
                        <li><a href="<?= e(url($item['url'])) ?>">Übersicht</a></li>
<?php foreach ($item['children'] as $child): ?>
                        <li><a href="<?= e(url($child['url'])) ?>"><?= e($child['label']) ?></a></li>
<?php endforeach; ?>
                    </ul>
                </div>
<?php endforeach; ?>

                <div class="footer-col">
                    <h2 class="footer-eyebrow">Unternehmen</h2>
                    <ul class="footer-link-list">
                        <li><a href="<?= e(url('ueber-uns.php')) ?>">Über uns</a></li>
                        <li><a href="<?= e(url('preise.php')) ?>">Preise</a></li>
                        <li><a href="<?= e(url('kontakt.php')) ?>">Kontakt</a></li>
                        <li><a href="<?= e(url('faq.php')) ?>">Häufige Fragen</a></li>
                        <li><a href="<?= e(url('impressum.php')) ?>">Impressum</a></li>
                        <li><a href="<?= e(url('datenschutz.php')) ?>">Datenschutz</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <span id="copyright-year"><?= date('Y') ?></span> <?= e($SITE['name']) ?>. Alle Rechte vorbehalten.</p>
            <p class="footer-note">
                Diese Seite lädt keine externen Schriften, Skripte oder Tracker.
            </p>
        </div>
    </div>
</footer>

<script src="<?= e(url('assets/site.js')) ?>" defer></script>
<?php foreach ($js as $script): ?>
<script src="<?= e(url('assets/' . $script . '.js')) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
