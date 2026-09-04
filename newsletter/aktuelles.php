<?php
/**
 * aktuelles.php – der öffentliche Website-Kanal der Mehrkanal-Meldungen.
 *
 *   aktuelles.php                 → öffentliche Seite „Aktuelles" (im Rahmen)
 *   aktuelles.php?format=json     → JSON-Feed (für eigene Einbindung)
 *   aktuelles.php?format=embed    → schlanke, einbettbare Seite für ein <iframe>
 *
 * Sichtbar sind nur Meldungen, deren Website-Kanal bereits ausgespielt wurde
 * und die nicht abgelaufen sind.
 */

require __DIR__ . '/lib/bootstrap.php';

$format = Util::get('format');
$items  = Announcements::publicItems(40);
$catMeta = Announcements::categoryMeta();

/* ------------------------------------------------------------ JSON-Feed */

if ($format === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');           // öffentlicher, nur lesender Feed
    header('Cache-Control: public, max-age=120');
    $out = [];
    foreach ($items as $m) {
        $out[] = [
            'id'           => (int) $m['id'],
            'title'        => (string) $m['title'],
            'body'         => (string) $m['body'],
            'category'     => (string) $m['category'],
            'category_label' => Announcements::categoryLabel((string) $m['category']),
            'link_url'     => (string) ($m['link_url'] ?? ''),
            'link_label'   => (string) ($m['link_label'] ?? ''),
            'published_at' => (string) ($m['published_at'] ?? ''),
        ];
    }
    echo json_encode(['brand' => Settings::get('brand_name'), 'items' => $out],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}

/* ------------------------------------------------- Eine Meldung als HTML */

$renderItem = static function (array $m) use ($catMeta): string {
    $farbe = $catMeta[$m['category']]['farbe'] ?? '#22405F';
    $body  = trim((string) $m['body']);
    $html  = '<article class="ak-item">'
        . '<div class="ak-kopf">'
        . '<span class="ak-badge" style="background:' . Util::e($farbe) . '">'
        . Util::e(Announcements::categoryLabel((string) $m['category'])) . '</span>'
        . '<time class="ak-datum">' . Util::e(Util::dt((string) ($m['published_at'] ?? ''), 'd.m.Y, H:i')) . ' Uhr</time>'
        . '</div>'
        . '<h2 class="ak-titel">' . Util::e((string) $m['title']) . '</h2>';
    if ($body !== '') {
        $html .= '<div class="ak-text">' . nl2br(Util::e($body)) . '</div>';
    }
    if (trim((string) ($m['link_url'] ?? '')) !== '') {
        $label = trim((string) ($m['link_label'] ?? '')) ?: 'Mehr erfahren';
        $html .= '<p class="ak-mehr"><a href="' . Util::e((string) $m['link_url']) . '" target="_blank" rel="noopener">'
            . Util::e($label) . ' →</a></p>';
    }
    return $html . '</article>';
};

/* ----------------------------------------------- Einbettbare Fassung (iframe) */

if ($format === 'embed') {
    // Rahmen-Einbettung auf fremden Seiten erlauben (Standard ist SAMEORIGIN).
    if (!headers_sent()) {
        header_remove('X-Frame-Options');
        header('Content-Type: text/html; charset=utf-8');
        header('Cache-Control: public, max-age=120');
    }
    ?><!DOCTYPE html>
<html lang="de"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Aktuelles</title>
<style>
  :root { color-scheme: light dark; }
  body { margin:0; font-family:-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif; color:#1f2a3a; background:transparent; }
  .ak-item { padding:12px 2px; border-bottom:1px solid #e6e9ef; }
  .ak-item:last-child { border-bottom:0; }
  .ak-kopf { display:flex; align-items:center; gap:8px; margin-bottom:4px; }
  .ak-badge { color:#fff; font-size:11px; font-weight:700; padding:1px 8px; border-radius:20px; text-transform:uppercase; letter-spacing:.03em; }
  .ak-datum { color:#8a95a5; font-size:12px; }
  .ak-titel { font-size:16px; margin:2px 0 4px; }
  .ak-text { font-size:14px; line-height:1.5; }
  .ak-mehr a { color:#2C6B45; text-decoration:none; font-weight:600; font-size:14px; }
  .ak-leer { color:#8a95a5; font-size:14px; padding:12px 2px; }
  @media (prefers-color-scheme: dark) { body { color:#e6e9ef; } .ak-item { border-color:#2a3546; } }
</style></head><body>
<?php if ($items === []): ?>
    <p class="ak-leer">Zurzeit keine aktuellen Meldungen.</p>
<?php else: foreach ($items as $m) { echo $renderItem($m); } endif; ?>
</body></html>
    <?php
    exit;
}

/* --------------------------------------------------- Öffentliche Seite */

require __DIR__ . '/partials/page.php';

ob_start();
?>
<div class="nl-card">
    <h1>Aktuelles</h1>
    <p class="nl-lead">Kurzfristige Hinweise und Neuigkeiten aus dem Club.</p>
    <?php if ($items === []): ?>
        <?= nl_notice('info', 'Zurzeit keine aktuellen Meldungen', 'Schauen Sie gern später wieder vorbei.') ?>
    <?php else: ?>
        <div class="ak-liste">
            <?php foreach ($items as $m) { echo $renderItem($m); } ?>
        </div>
    <?php endif; ?>
</div>
<style>
  .ak-liste { margin-top:8px; }
  .ak-item { padding:16px 0; border-bottom:1px solid var(--nl-border,#e6e9ef); }
  .ak-item:last-child { border-bottom:0; }
  .ak-kopf { display:flex; align-items:center; gap:10px; margin-bottom:6px; }
  .ak-badge { color:#fff; font-size:11px; font-weight:700; padding:2px 9px; border-radius:20px; text-transform:uppercase; letter-spacing:.03em; }
  .ak-datum { color:var(--nl-muted,#8a95a5); font-size:13px; }
  .ak-titel { font-size:20px; margin:2px 0 6px; }
  .ak-text { line-height:1.6; }
  .ak-mehr { margin:8px 0 0; }
</style>
<?php
nl_page('Aktuelles', (string) ob_get_clean());
