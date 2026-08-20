<?php
/**
 * Randspalte der Unterseiten: Navigation innerhalb des Bereichs und ein Kasten
 * mit dem nächsten Schritt.
 *
 * Der aktive Eintrag ergibt sich aus $page['path'], die Geschwister aus dem
 * Navigationsbaum. Eine neue Unterseite taucht damit von selbst hier auf.
 *
 * Optional vorher setzen:
 *   $asideCta   ['title' =>, 'text' =>, 'link' => [Text, Pfad]]
 *   $asideFacts [[Beschriftung, Wert], …]  – kleine Faktentabelle über der Navigation
 */

$section = $NAV[$page['section']] ?? null;

$asideCta = $asideCta ?? [
    'title' => 'Nächster Schritt',
    'text'  => '30 Minuten, kostenlos: Sie schildern Ihre Ausgangslage, ich sage Ihnen, was ich an Ihrer Stelle zuerst angehen würde.',
    'link'  => ['Erstgespräch vereinbaren', 'kontakt.php'],
];
?>
<aside class="page-aside">
<?php if (!empty($asideFacts)): ?>
    <div class="aside-card">
        <h2 class="aside-title">Auf einen Blick</h2>
        <dl class="aside-facts">
<?php foreach ($asideFacts as $fact): ?>
            <div>
                <dt><?= e($fact[0]) ?></dt>
                <dd><?= e($fact[1]) ?></dd>
            </div>
<?php endforeach; ?>
        </dl>
    </div>
<?php endif; ?>

<?php if ($section && !empty($section['children'])): ?>
    <div class="aside-card">
        <h2 class="aside-title"><?= e($section['label']) ?></h2>
        <nav class="aside-nav" aria-label="Weitere Seiten in diesem Bereich">
            <a href="<?= e(url($section['url'])) ?>"<?= $page['path'] === $section['url'] ? ' class="is-current" aria-current="page"' : '' ?>>
                Übersicht
                <i data-icon="arrow-right" class="lucide"></i>
            </a>
<?php foreach ($section['children'] as $child):
    $current = $page['path'] === $child['url'];
?>
            <a href="<?= e(url($child['url'])) ?>"<?= $current ? ' class="is-current" aria-current="page"' : '' ?>>
                <?= e($child['label']) ?>
                <i data-icon="arrow-right" class="lucide"></i>
            </a>
<?php endforeach; ?>
        </nav>
    </div>
<?php endif; ?>

    <div class="aside-card is-dark">
        <h2 class="aside-title"><?= e($asideCta['title']) ?></h2>
        <p><?= e($asideCta['text']) ?></p>
        <a href="<?= e(url($asideCta['link'][1])) ?>" class="btn-primary-custom btn-on-dark"><?= e($asideCta['link'][0]) ?></a>
    </div>
</aside>
