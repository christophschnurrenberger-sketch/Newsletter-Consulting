<?php
/**
 * Fuß jeder Seite: Handlungsband, Footer mit Sitemap und die Skripte.
 *
 * Optional in $page:
 *   cta   ['title' =>, 'text' =>, 'primary' => [Text, Pfad], 'secondary' => [Text, Pfad]]
 *         false unterdrückt das Band (etwa auf der Kontaktseite)
 *   js    Zusätzliche Skripte
 */

$cta = $page['cta'] ?? null;
if ($cta === null) {
    // Standardband – gilt überall dort, wo die Seite nichts eigenes setzt.
    $cta = [
        'title'     => 'Sprechen wir 30 Minuten über Ihre Ausgangslage',
        'text'      => 'Kostenlos und unverbindlich. Sie schildern, was ansteht – Audit, Prüfungsfeststellung, NIS2, Wachstum, unklare Zuständigkeiten. Sie bekommen eine ehrliche Einschätzung, was zuerst zu tun ist. Auch dann, wenn die Antwort lautet: dafür brauchen Sie keine Beratung.',
        'primary'   => ['Erstgespräch vereinbaren', 'kontakt.php'],
        'secondary' => ['Leistungen ansehen', 'leistungen/'],
    ];
}
$js = $page['js'] ?? [];
?>
</main>

<?php if ($cta !== false): ?>
<!-- Handlungsband ------------------------------------------------------ -->
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
                    <span class="site-brand-mark" aria-hidden="true">
                        <svg viewBox="0 0 32 32" width="32" height="32" fill="none" aria-hidden="true">
                            <path d="M6 22V11l10-5 10 5v11" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M11 22v-6M16 22v-9M21 22v-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M5 26h22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <span class="site-brand-text">
                        <span class="site-brand-name"><?= e($SITE['name']) ?></span>
                        <span class="site-brand-line"><?= e($SITE['claim']) ?></span>
                    </span>
                </span>
                <p class="footer-brand-copy">
                    Beratung für mittelständische Unternehmen, deren IT schneller gewachsen ist
                    als ihre Steuerung: Governance-Strukturen, Prozesse, Rollen, Kontrollen und
                    Nachweise – so aufgesetzt, dass sie im Alltag gelebt und in der Prüfung
                    anerkannt werden.
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
                    <h2 class="footer-eyebrow">Beratung</h2>
                    <ul class="footer-link-list">
                        <li><a href="<?= e(url('vorgehen.php')) ?>">Vorgehen</a></li>
                        <li><a href="<?= e(url('fuer-wen.php')) ?>">Für wen ich arbeite</a></li>
                        <li><a href="<?= e(url('preise.php')) ?>">Preise &amp; Investition</a></li>
                        <li><a href="<?= e(url('ueber-mich.php')) ?>">Über mich</a></li>
                        <li><a href="<?= e(url('faq.php')) ?>">Häufige Fragen</a></li>
                        <li><a href="<?= e(url('kontakt.php')) ?>">Kontakt</a></li>
                        <li><a href="<?= e(url('impressum.php')) ?>">Impressum</a></li>
                        <li><a href="<?= e(url('datenschutz.php')) ?>">Datenschutz</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer-legal">
            <p>
                <strong>Keine Rechtsberatung.</strong> Diese Website und meine Leistungen
                dienen der fachlichen Organisations- und Prozessberatung. Sie enthalten
                keine Rechtsberatung im Sinne des Rechtsdienstleistungsgesetzes und ersetzen
                keine anwaltliche Prüfung. Rechtliche Bewertungen – etwa zur Betroffenheit
                von NIS2 oder DORA, zu Vertrags- und Haftungsfragen oder zu Meldepflichten –
                treffen ausschließlich zugelassene Rechtsanwältinnen und Rechtsanwälte.
                Auf Wunsch arbeite ich der von Ihnen beauftragten Kanzlei fachlich zu.
            </p>
        </div>

        <div class="footer-bottom">
            <p>&copy; <span id="copyright-year"><?= date('Y') ?></span> <?= e($SITE['owner']) ?>. Alle Rechte vorbehalten.</p>
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
