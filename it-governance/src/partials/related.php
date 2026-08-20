<?php
/**
 * Verweiskacheln am Fuß einer Unterseite („Passt dazu“).
 *
 * Vorher setzen:
 *   $related      ['leistungen/gap-analyse.php', 'themen/nis2.php', …]
 *   $relatedTitle abweichende Überschrift (optional)
 *
 * Beschriftung und Beschreibungszeile kommen aus dem Navigationsbaum – sie
 * stehen also nur an einer Stelle und bleiben überall gleich.
 */

$relatedTitle = $relatedTitle ?? 'Passt dazu';
$entries = [];
foreach ($related ?? [] as $path) {
    $entry = nav_find($path);
    if ($entry) { $entries[] = $entry; }
}
if ($entries):
?>
<section class="section section-tight related">
    <div class="container">
        <h2 class="related-title"><?= e($relatedTitle) ?></h2>
        <div class="related-grid">
<?php foreach ($entries as $entry): ?>
            <a href="<?= e(url($entry['url'])) ?>" class="related-card">
                <span class="related-icon"><i data-icon="<?= e($entry['icon'] ?? 'arrow-right') ?>" class="lucide"></i></span>
                <span class="related-text">
                    <strong><?= e($entry['label']) ?></strong>
                    <small><?= e($entry['desc'] ?? '') ?></small>
                </span>
                <i data-icon="arrow-right" class="lucide related-arrow"></i>
            </a>
<?php endforeach; ?>
        </div>
    </div>
</section>
<?php
endif;
$related = null;
