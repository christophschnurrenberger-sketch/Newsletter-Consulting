<?php
/**
 * Waagerechte Kette aus Schritten – „von hier nach dort“.
 *
 * Vorher setzen:
 *   $kette      [[Titel, Untertitel], …]
 *   $ketteLabel Beschriftung für Screenreader (optional)
 *
 * Auf schmalen Bildschirmen bricht die Kette um; die Pfeile stehen dann
 * zwischen den Zeilen, was der Lesbarkeit nicht schadet.
 */
$ketteLabel = $ketteLabel ?? 'Ablauf in Schritten';
$letzter = count($kette ?? []) - 1;
?>
<ol class="chain" aria-label="<?= e($ketteLabel) ?>">
<?php foreach ($kette as $i => $schritt): ?>
    <li>
        <span class="chain-step">
            <strong><?= e($schritt[0]) ?></strong>
            <small><?= e($schritt[1]) ?></small>
        </span>
<?php if ($i < $letzter): ?>
        <span class="chain-arrow" aria-hidden="true">›</span>
<?php endif; ?>
    </li>
<?php endforeach; ?>
</ol>
<?php $kette = null; $ketteLabel = null; ?>
