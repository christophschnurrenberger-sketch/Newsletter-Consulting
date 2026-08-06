<?php
/**
 * statistik.php – Auswertung einer einzelnen Ausgabe.
 */

$pageTitle = 'Auswertung';
require __DIR__ . '/partials/header.php';

$id       = Util::getInt('id');
$campaign = Campaigns::byId($id);
if ($campaign === null) {
    Util::flash('Dieser Newsletter existiert nicht (mehr).', 'error');
    Util::redirect('kampagnen.php');
}

if (Util::isPost()) {
    Util::requireCsrf();
    if (Util::post('aktion') === 'wiederholen') {
        $count = Queue::retryFailed($id);
        Util::flash(Util::num($count) . ' fehlgeschlagene Sendungen wurden erneut eingereiht.');
        Util::redirect('statistik.php?id=' . $id);
    }
}

$stats  = Campaigns::stats($id);
$links  = Campaigns::linkStats($id);
$sent   = (int) $stats['sent'];

/* Verlauf der Öffnungen in den ersten Stunden nach dem Start */
$timeline = DB::all(
    "SELECT substr(created_at, 1, 13) AS stunde, COUNT(*) AS anzahl
     FROM events WHERE campaign_id = ? AND type = 'open'
     GROUP BY substr(created_at, 1, 13) ORDER BY stunde",
    [$id]
);
$maxHour = 1;
foreach ($timeline as $row) {
    $maxHour = max($maxHour, (int) $row['anzahl']);
}

/* Letzte Fehler */
$failures = DB::all(
    "SELECT email, last_error, attempts FROM queue WHERE campaign_id = ? AND status = 'failed' ORDER BY id DESC LIMIT 25",
    [$id]
);
?>

<div class="ad-page-head">
    <div>
        <h1>Auswertung: <?= Util::e((string) $campaign['subject']) ?></h1>
        <p class="ad-sub">
            <?= campaign_status_pill((string) $campaign['status']) ?>
            · Start: <?= Util::e(Util::dt((string) $campaign['started_at'])) ?>
            <?php if ($campaign['finished_at'] !== null): ?>
                · Ende: <?= Util::e(Util::dt((string) $campaign['finished_at'])) ?>
            <?php endif; ?>
        </p>
    </div>
    <div class="ad-actions-inline">
        <a class="ad-btn ad-btn-secondary" href="kampagne.php?id=<?= $id ?>">Zum Newsletter</a>
        <a class="ad-btn ad-btn-secondary" target="_blank" rel="noopener" href="../archiv.php?c=<?= $id ?>">Im Archiv ansehen</a>
    </div>
</div>

<div class="ad-grid">
    <div class="ad-stat">
        <div class="ad-stat-label">Versendet</div>
        <div class="ad-stat-value"><?= Util::num($sent) ?></div>
        <div class="ad-stat-note">von <?= Util::num((int) $stats['total']) ?> eingeplant</div>
    </div>
    <div class="ad-stat">
        <div class="ad-stat-label">Öffnungsrate</div>
        <div class="ad-stat-value"><?= Util::e((string) $stats['open_rate']) ?></div>
        <div class="ad-stat-note"><?= Util::num((int) $stats['opens_unique']) ?> Empfänger,
            <?= Util::num((int) $stats['opens']) ?> Öffnungen gesamt</div>
    </div>
    <div class="ad-stat">
        <div class="ad-stat-label">Klickrate</div>
        <div class="ad-stat-value"><?= Util::e((string) $stats['click_rate']) ?></div>
        <div class="ad-stat-note"><?= Util::num((int) $stats['clicks_unique']) ?> Empfänger,
            <?= Util::num((int) $stats['clicks']) ?> Klicks gesamt</div>
    </div>
    <div class="ad-stat">
        <div class="ad-stat-label">Abmeldungen</div>
        <div class="ad-stat-value"><?= Util::num((int) $stats['unsubscribes']) ?></div>
        <div class="ad-stat-note"><?= $sent > 0 ? Util::e(Util::percent((int) $stats['unsubscribes'], $sent, 2)) : '—' ?>
            der Empfänger</div>
    </div>
    <div class="ad-stat">
        <div class="ad-stat-label">Unzustellbar</div>
        <div class="ad-stat-value"><?= Util::num((int) $stats['bounces']) ?></div>
        <div class="ad-stat-note"><?= Util::num((int) $stats['failed']) ?> Fehler beim Versand</div>
    </div>
    <div class="ad-stat">
        <div class="ad-stat-label">Noch offen</div>
        <div class="ad-stat-value"><?= Util::num((int) $stats['pending']) ?></div>
        <div class="ad-stat-note"><?= Util::num((int) $stats['skipped']) ?> übersprungen</div>
    </div>
</div>

<?php if ($timeline !== []): ?>
    <div class="ad-card">
        <h2>Öffnungen im Zeitverlauf</h2>
        <div style="display:flex;align-items:flex-end;gap:3px;height:120px;margin-top:14px;overflow-x:auto;">
            <?php foreach ($timeline as $row):
                $hour = (string) $row['stunde']; ?>
                <div style="flex:1 0 14px;text-align:center;"
                     title="<?= Util::e(Util::dt($hour . ':00:00', 'd.m. H')) ?> Uhr: <?= (int) $row['anzahl'] ?>">
                    <div style="height:<?= (int) max(3, round((int) $row['anzahl'] / $maxHour * 100)) ?>px;background:var(--ad-navy);border-radius:3px 3px 0 0;"></div>
                    <div style="font-size:10px;color:var(--ad-muted);margin-top:3px;"><?= Util::e(substr($hour, 11, 2)) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <p class="ad-hint">Stunden mit Öffnungen – hilfreich, um den besten Versandzeitpunkt zu finden.</p>
    </div>
<?php endif; ?>

<h2>Geklickte Links</h2>
<?php if ($links === []): ?>
    <div class="ad-empty">Für diese Ausgabe wurden keine Links erfasst.</div>
<?php else: ?>
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead>
            <tr>
                <th>Ziel</th>
                <th class="ad-num">Klicks</th>
                <th class="ad-num">Empfänger</th>
                <th class="ad-num">Klickrate</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($links as $link): ?>
                <tr>
                    <td>
                        <a href="<?= Util::e((string) $link['url']) ?>" target="_blank" rel="noopener nofollow">
                            <span class="ad-truncate ad-mono"><?= Util::e((string) $link['url']) ?></span>
                        </a>
                    </td>
                    <td class="ad-num"><?= Util::num((int) $link['klicks']) ?></td>
                    <td class="ad-num"><?= Util::num((int) $link['empfaenger']) ?></td>
                    <td class="ad-num"><?= $sent > 0 ? Util::e(Util::percent((int) $link['empfaenger'], $sent)) : '—' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php if ($failures !== []): ?>
    <h2>Fehlgeschlagene Sendungen</h2>
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>Adresse</th><th>Versuche</th><th>Meldung</th></tr></thead>
            <tbody>
            <?php foreach ($failures as $row): ?>
                <tr>
                    <td class="ad-mono"><?= Util::e((string) $row['email']) ?></td>
                    <td class="ad-num"><?= (int) $row['attempts'] ?></td>
                    <td><span class="ad-truncate"><?= Util::e(Util::shorten((string) $row['last_error'], 120)) ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <form method="post">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="wiederholen">
        <button type="submit" class="ad-btn ad-btn-secondary">Fehlgeschlagene erneut versuchen</button>
    </form>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php';
