<?php
/**
 * hilfe.php – das Handbuch und die Quelle für die „?"-Popups.
 *
 * Zwei Betriebsarten:
 *   1. Vollansicht  (ohne ?teil): das ganze Handbuch, nach Gruppen geordnet.
 *   2. Ausschnitt   (?thema=<id>&teil=1): nur ein Thema als HTML-Schnipsel –
 *      das holt admin.js für das „?"-Popup an der jeweiligen Stelle.
 */

require __DIR__ . '/../lib/bootstrap.php';
Auth::require('lesen');

/* --- Ausschnitt für das Popup ------------------------------------------- */
if (isset($_GET['teil']) && ($_GET['thema'] ?? '') !== '') {
    header('Content-Type: text/html; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    $topic = Hilfe::topic((string) $_GET['thema']);
    if ($topic === null) {
        http_response_code(404);
        echo '<div class="hilfe-inhalt"><p>Zu diesem Bereich gibt es noch keine eigene Hilfe.</p>'
            . '<p><a href="hilfe.php" target="_blank" rel="noopener">Zum Handbuch →</a></p></div>';
        exit;
    }
    echo '<div class="hilfe-inhalt">'
        . '<h2>' . Util::e($topic['title']) . '</h2>'
        . '<p class="hilfe-kurz">' . Util::e($topic['kurz']) . '</p>'
        . $topic['html']
        . '<p class="hilfe-mehr"><a href="hilfe.php#' . Util::e((string) $_GET['thema'])
        . '" target="_blank" rel="noopener">Das ganze Handbuch öffnen →</a></p>'
        . '</div>';
    exit;
}

/* --- Vollansicht (Handbuch) --------------------------------------------- */
$pageTitle = 'Hilfe';
$requiredRight = 'lesen';
require __DIR__ . '/partials/header.php';

$alle = Hilfe::all();
$nachGruppe = [];
foreach (Hilfe::GRUPPEN as $key => $label) { $nachGruppe[$key] = []; }
foreach ($alle as $id => $topic) {
    $g = isset($nachGruppe[$topic['gruppe']]) ? $topic['gruppe'] : 'system';
    $nachGruppe[$g][$id] = $topic;
}
?>

<div class="ad-page-head">
    <div>
        <h1>Hilfe &amp; Handbuch</h1>
        <p class="ad-sub">Schritt für Schritt durch alle Bereiche. Das gleiche „?" finden Sie auf jeder Seite oben.</p>
    </div>
</div>

<div class="hilfe-layout">
    <nav class="hilfe-nav" aria-label="Themen">
        <?php foreach (Hilfe::GRUPPEN as $key => $label): if ($nachGruppe[$key] === []) { continue; } ?>
            <span class="hilfe-nav-gruppe"><?= Util::e($label) ?></span>
            <?php foreach ($nachGruppe[$key] as $id => $topic): ?>
                <a href="#<?= Util::e($id) ?>"><?= Util::e($topic['title']) ?></a>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </nav>

    <div class="hilfe-haupt">
        <?php foreach (Hilfe::GRUPPEN as $key => $label): if ($nachGruppe[$key] === []) { continue; } ?>
            <h2 class="hilfe-gruppe-titel"><?= Util::e($label) ?></h2>
            <?php foreach ($nachGruppe[$key] as $id => $topic): ?>
                <section class="ad-card hilfe-thema" id="<?= Util::e($id) ?>">
                    <h3><?= Util::e($topic['title']) ?></h3>
                    <p class="hilfe-kurz"><?= Util::e($topic['kurz']) ?></p>
                    <div class="hilfe-inhalt"><?= $topic['html'] ?></div>
                </section>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
