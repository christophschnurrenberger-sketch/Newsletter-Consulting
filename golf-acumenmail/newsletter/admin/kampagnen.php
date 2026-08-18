<?php
/**
 * kampagnen.php – Liste aller Newsletter-Ausgaben.
 */

$pageTitle = 'Newsletter';
require __DIR__ . '/partials/header.php';

if (Util::isPost()) {
    Util::requireCsrf();
    if (!Auth::can('kampagnen')) {
        Util::flash('Dafür fehlt Ihnen die Berechtigung. Fragen Sie eine Administratorin oder einen Administrator.', 'error');
        Util::redirect('kampagnen.php');
    }
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
$filter    = in_array($filter, array_keys(Campaigns::statusLabels()), true) ? $filter : '';
$campaigns = Campaigns::all($filter);
$anzahl    = Campaigns::statusCounts();
?>

<div class="ad-page-head">
    <div>
        <h1>Newsletter</h1>
        <p class="ad-sub">Entwürfe, geplante und versendete Ausgaben</p>
    </div>
    <a class="ad-btn" href="neu.php">Newsletter schreiben</a>
</div>

<?php /*
 * Filter als Reiter statt Auswahlfeld: Man sieht auf einen Blick, wie viele
 * Entwürfe offen sind, und ist mit einem Klick dort. Leere Stapel werden
 * gar nicht erst angeboten.
 */ ?>
<nav class="ad-reiter" aria-label="Filter">
    <a class="ad-reiter-tab <?= $filter === '' ? 'is-aktiv' : '' ?>" href="kampagnen.php">
        Alle <span class="ad-reiter-zahl"><?= Util::num($anzahl['']) ?></span>
    </a>
    <?php foreach (Campaigns::statusLabels() as $key => $label):
        if ($anzahl[$key] === 0 && $filter !== $key) { continue; } ?>
        <a class="ad-reiter-tab <?= $filter === $key ? 'is-aktiv' : '' ?>"
           href="kampagnen.php?status=<?= Util::e($key) ?>">
            <?= Util::e($label) ?> <span class="ad-reiter-zahl"><?= Util::num($anzahl[$key]) ?></span>
        </a>
    <?php endforeach; ?>
</nav>

<?php if ($campaigns === []): ?>
    <div class="ad-empty">
        <?php if ($filter !== '' && $anzahl[''] > 0): ?>
            Hier liegt gerade nichts. <a href="kampagnen.php">Alle Newsletter anzeigen</a>
        <?php else: ?>
            Noch kein Newsletter angelegt. <a href="neu.php">Jetzt den ersten schreiben</a>
        <?php endif; ?>
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
                        <?php /*
                         * Eine sichtbare Haupt-Aktion, der Rest im Menü. Vorher
                         * stand in jeder Zeile ein roter „Löschen“-Knopf – das
                         * zieht den Blick genau auf die Aktion, die man am
                         * seltensten will.
                         */ ?>
                        <div class="ad-actions-inline">
                            <?php if ((int) $stats['sent'] > 0): ?>
                                <a class="ad-btn ad-btn-secondary ad-btn-small" href="statistik.php?id=<?= (int) $campaign['id'] ?>">Auswertung</a>
                            <?php else: ?>
                                <a class="ad-btn ad-btn-secondary ad-btn-small" href="kampagne.php?id=<?= (int) $campaign['id'] ?>">Bearbeiten</a>
                            <?php endif; ?>
                            <details class="ad-menue">
                                <summary class="ad-btn ad-btn-secondary ad-btn-small" aria-label="Weitere Aktionen"
                                         title="Weitere Aktionen">…</summary>
                                <div class="ad-menue-liste">
                                    <a href="kampagne.php?id=<?= (int) $campaign['id'] ?>">Bearbeiten</a>
                                    <?php if ((int) $stats['sent'] > 0): ?>
                                        <a href="statistik.php?id=<?= (int) $campaign['id'] ?>">Auswertung</a>
                                    <?php endif; ?>
                                    <form method="post">
                                        <?= Util::csrfField() ?>
                                        <input type="hidden" name="id" value="<?= (int) $campaign['id'] ?>">
                                        <input type="hidden" name="aktion" value="kopieren">
                                        <button type="submit">Kopieren</button>
                                    </form>
                                    <?php if (in_array($campaign['status'], [Campaigns::DRAFT, Campaigns::CANCELLED], true)): ?>
                                        <form method="post">
                                            <?= Util::csrfField() ?>
                                            <input type="hidden" name="id" value="<?= (int) $campaign['id'] ?>">
                                            <input type="hidden" name="aktion" value="loeschen">
                                            <button type="submit" class="ist-gefahr"
                                                    data-confirm="Diesen Newsletter wirklich löschen?">Löschen</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </details>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php';
