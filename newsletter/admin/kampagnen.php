<?php
/**
 * kampagnen.php – Liste aller Newsletter-Ausgaben.
 */

$pageTitle = 'Newsletter';
require __DIR__ . '/partials/header.php';

if (Util::isPost()) {
    Util::requireCsrf();
    $id     = Util::postInt('id');
    $action = Util::post('aktion');

    if ($action === 'kopieren' && $id > 0) {
        $newId = Campaigns::duplicate($id);
        Util::flash('Kopie angelegt. Sie können sie jetzt bearbeiten.');
        Util::redirect('kampagne.php?id=' . $newId);
    }
    if ($action === 'loeschen' && $id > 0) {
        Campaigns::delete($id);
        Util::flash('Der Newsletter wurde gelöscht.');
        Util::redirect('kampagnen.php');
    }
}

$filter    = Util::get('status');
$campaigns = Campaigns::all(in_array($filter, array_keys(Campaigns::statusLabels()), true) ? $filter : '');
?>

<div class="ad-page-head">
    <div>
        <h1>Newsletter</h1>
        <p class="ad-sub">Entwürfe, geplante und versendete Ausgaben</p>
    </div>
    <a class="ad-btn" href="kampagne.php?neu=1">Neuen Newsletter schreiben</a>
</div>

<div class="ad-card ad-card-tight">
    <form method="get" class="ad-row" style="align-items:flex-end;margin:0;">
        <div class="ad-field" style="margin:0;max-width:240px;">
            <label for="status">Status</label>
            <select id="status" name="status" onchange="this.form.submit()">
                <option value="">Alle anzeigen</option>
                <?php foreach (Campaigns::statusLabels() as $key => $label): ?>
                    <option value="<?= Util::e($key) ?>" <?= $filter === $key ? 'selected' : '' ?>>
                        <?= Util::e($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <noscript><button type="submit" class="ad-btn ad-btn-secondary ad-btn-small">Filtern</button></noscript>
    </form>
</div>

<?php if ($campaigns === []): ?>
    <div class="ad-empty">
        Kein Newsletter gefunden. <a href="kampagne.php?neu=1">Jetzt den ersten schreiben</a>
    </div>
<?php else: ?>
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead>
            <tr>
                <th>Betreff / Name</th>
                <th>Status</th>
                <th>Liste</th>
                <th class="ad-num">Versendet</th>
                <th class="ad-num">Öffnungsrate</th>
                <th class="ad-num">Klickrate</th>
                <th>Datum</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($campaigns as $campaign):
                $stats = Campaigns::stats((int) $campaign['id']); ?>
                <tr>
                    <td>
                        <a href="kampagne.php?id=<?= (int) $campaign['id'] ?>">
                            <strong><?= Util::e($campaign['subject'] !== '' ? (string) $campaign['subject'] : (string) $campaign['name']) ?></strong>
                        </a>
                        <?php if ($campaign['subject'] !== '' && $campaign['subject'] !== $campaign['name']): ?>
                            <div class="ad-hint"><?= Util::e((string) $campaign['name']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td><?= campaign_status_pill((string) $campaign['status']) ?></td>
                    <td><?= Util::e(Lists::name($campaign['list_id'] !== null ? (int) $campaign['list_id'] : null)) ?></td>
                    <td class="ad-num"><?= Util::num((int) $stats['sent']) ?></td>
                    <td class="ad-num"><?= Util::e((string) $stats['open_rate']) ?></td>
                    <td class="ad-num"><?= Util::e((string) $stats['click_rate']) ?></td>
                    <td><?= Util::e(Util::dt((string) ($campaign['started_at'] ?: $campaign['created_at']), 'd.m.Y')) ?></td>
                    <td>
                        <div class="ad-actions-inline">
                            <?php if ((int) $stats['sent'] > 0): ?>
                                <a class="ad-btn ad-btn-secondary ad-btn-small" href="statistik.php?id=<?= (int) $campaign['id'] ?>">Auswertung</a>
                            <?php endif; ?>
                            <form method="post" style="display:inline;">
                                <?= Util::csrfField() ?>
                                <input type="hidden" name="id" value="<?= (int) $campaign['id'] ?>">
                                <input type="hidden" name="aktion" value="kopieren">
                                <button type="submit" class="ad-btn ad-btn-secondary ad-btn-small">Kopieren</button>
                            </form>
                            <?php if (in_array($campaign['status'], [Campaigns::DRAFT, Campaigns::CANCELLED], true)): ?>
                                <form method="post" style="display:inline;">
                                    <?= Util::csrfField() ?>
                                    <input type="hidden" name="id" value="<?= (int) $campaign['id'] ?>">
                                    <input type="hidden" name="aktion" value="loeschen">
                                    <button type="submit" class="ad-btn ad-btn-danger ad-btn-small"
                                            data-confirm="Diesen Newsletter wirklich löschen?">Löschen</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php';
