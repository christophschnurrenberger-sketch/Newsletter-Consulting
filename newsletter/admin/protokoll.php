<?php
/**
 * protokoll.php – Technisches Protokoll, Rückläufer und Sperrliste.
 */

$pageTitle = 'Protokoll';
$requiredRight = 'einstellungen';
require __DIR__ . '/partials/header.php';

if (Util::isPost()) {
    Util::requireCsrf();
    $action = Util::post('aktion');

    if ($action === 'sperre_aufheben') {
        $email = Util::normalizeEmail(Util::post('email'));
        Subscribers::unsuppress($email);
        Util::flash('Sperre für <strong>' . Util::e($email) . '</strong> aufgehoben.');
        Util::redirect('protokoll.php?tab=sperrliste');
    }
    if ($action === 'sperre_setzen') {
        $email = Util::normalizeEmail(Util::post('email'));
        if (Util::isEmail($email)) {
            Subscribers::suppress($email, 'manuell', 'Von Hand gesperrt');
            Util::flash('<strong>' . Util::e($email) . '</strong> wurde gesperrt.');
        } else {
            Util::flash('Bitte eine gültige Adresse angeben.', 'error');
        }
        Util::redirect('protokoll.php?tab=sperrliste');
    }
    if ($action === 'bounces_abholen') {
        $result = Bounces::processMailbox(100);
        if ($result['error'] !== '') {
            Util::flash('Rücklaufpostfach: ' . Util::e($result['error']), 'error');
        } else {
            Util::flash(sprintf('%d Nachrichten geprüft: %d dauerhaft unzustellbar, %d vorübergehend, %d ohne Bezug.',
                $result['checked'], $result['hard'], $result['soft'], $result['ignored']));
        }
        Util::redirect('protokoll.php?tab=bounces');
    }
    if ($action === 'log_leeren') {
        $count = Log::prune(0);
        Util::flash(Util::num($count) . ' Protokolleinträge gelöscht.');
        Util::redirect('protokoll.php');
    }
}

$tab   = Util::get('tab') ?: 'log';
$level = Util::get('level');
?>

<div class="ad-page-head">
    <div>
        <h1>Protokoll</h1>
        <p class="ad-sub">Was das System im Hintergrund getan hat</p>
    </div>
    <div class="ad-actions-inline">
        <a class="ad-btn ad-btn-small <?= $tab === 'log' ? '' : 'ad-btn-secondary' ?>" href="protokoll.php?tab=log">Ereignisse</a>
        <a class="ad-btn ad-btn-small <?= $tab === 'bounces' ? '' : 'ad-btn-secondary' ?>" href="protokoll.php?tab=bounces">Rückläufer</a>
        <a class="ad-btn ad-btn-small <?= $tab === 'sperrliste' ? '' : 'ad-btn-secondary' ?>" href="protokoll.php?tab=sperrliste">Sperrliste</a>
    </div>
</div>

<?php if ($tab === 'bounces'):
    $bounces = Bounces::recent(100); ?>

    <div class="ad-card">
        <h2>Rücklaufpostfach</h2>
        <?php if (Settings::bool('bounce_enabled')): ?>
            <p class="ad-hint">Postfach: <strong><?= Util::e(Settings::get('bounce_user')) ?></strong> auf
                <?= Util::e(Settings::get('bounce_host')) ?>. Der Cron-Job <code>cron/bounces.php</code> holt
                Fehlermails automatisch ab.</p>
            <form method="post" class="ad-actions" style="margin-top:10px;">
                <?= Util::csrfField() ?>
                <button type="submit" name="aktion" value="bounces_abholen" class="ad-btn ad-btn-secondary">Jetzt abholen</button>
            </form>
        <?php else: ?>
            <p class="ad-hint">Die automatische Auswertung von Fehlermails ist nicht aktiviert.
                <a href="einstellungen.php#bounce">In den Einstellungen einrichten</a> – dazu brauchen Sie ein
                eigenes Postfach (z. B. <code>bounce@ihre-domain.de</code>), das als Rücklaufadresse dient.</p>
        <?php endif; ?>
    </div>

    <?php if ($bounces === []): ?>
        <div class="ad-empty">Noch keine Rückläufer erfasst.</div>
    <?php else: ?>
        <div class="ad-table-wrap">
            <table class="ad-table">
                <thead><tr><th>Zeitpunkt</th><th>Adresse</th><th>Art</th><th>Code</th><th>Meldung</th></tr></thead>
                <tbody>
                <?php foreach ($bounces as $row): ?>
                    <tr>
                        <td><?= Util::e(Util::dt((string) $row['created_at'])) ?></td>
                        <td class="ad-mono"><?= Util::e((string) $row['email']) ?></td>
                        <td>
                            <span class="ad-pill <?= $row['bounce_type'] === 'hard' ? 'ad-pill-red' : 'ad-pill-amber' ?>">
                                <?= $row['bounce_type'] === 'hard' ? 'dauerhaft' : 'vorübergehend' ?>
                            </span>
                        </td>
                        <td class="ad-mono"><?= Util::e((string) $row['code']) ?></td>
                        <td><span class="ad-truncate"><?= Util::e(Util::shorten((string) $row['message'], 120)) ?></span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

<?php elseif ($tab === 'sperrliste'):
    $blocked = DB::all('SELECT * FROM suppression ORDER BY created_at DESC LIMIT 300'); ?>

    <div class="ad-card">
        <h2>Adresse sperren</h2>
        <p class="ad-hint" style="margin-top:-4px;">Gesperrte Adressen werden nie angeschrieben – auch nicht
            nach einem Import. Das schützt vor Beschwerden und schlechter Zustellbarkeit.</p>
        <form method="post" class="ad-row" style="align-items:flex-end;">
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="sperre_setzen">
            <div class="ad-field" style="margin:0;">
                <label for="email">E-Mail-Adresse</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="ad-field" style="margin:0;flex:0;">
                <button type="submit" class="ad-btn ad-btn-secondary">Sperren</button>
            </div>
        </form>
    </div>

    <?php if ($blocked === []): ?>
        <div class="ad-empty">Die Sperrliste ist leer.</div>
    <?php else: ?>
        <div class="ad-table-wrap">
            <table class="ad-table">
                <thead><tr><th>Adresse</th><th>Grund</th><th>Details</th><th>Seit</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($blocked as $row): ?>
                    <tr>
                        <td class="ad-mono"><?= Util::e((string) $row['email']) ?></td>
                        <td><span class="ad-pill ad-pill-grey"><?= Util::e((string) $row['reason']) ?></span></td>
                        <td class="ad-hint"><?= Util::e(Util::shorten((string) $row['detail'], 80)) ?></td>
                        <td><?= Util::e(Util::dt((string) $row['created_at'], 'd.m.Y')) ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <?= Util::csrfField() ?>
                                <input type="hidden" name="aktion" value="sperre_aufheben">
                                <input type="hidden" name="email" value="<?= Util::e((string) $row['email']) ?>">
                                <button type="submit" class="ad-btn ad-btn-secondary ad-btn-small"
                                        data-confirm="Sperre wirklich aufheben?">Aufheben</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

<?php else:
    $where  = $level !== '' ? 'WHERE level = ?' : '';
    $params = $level !== '' ? [$level] : [];
    $logs   = DB::all('SELECT * FROM logs ' . $where . ' ORDER BY id DESC LIMIT 300', $params); ?>

    <div class="ad-card ad-card-tight">
        <div class="ad-actions" style="margin-top:0;">
            <a class="ad-btn ad-btn-small <?= $level === '' ? '' : 'ad-btn-secondary' ?>" href="protokoll.php">Alle</a>
            <a class="ad-btn ad-btn-small <?= $level === 'error' ? '' : 'ad-btn-secondary' ?>" href="protokoll.php?level=error">Fehler</a>
            <a class="ad-btn ad-btn-small <?= $level === 'warning' ? '' : 'ad-btn-secondary' ?>" href="protokoll.php?level=warning">Warnungen</a>
            <form method="post" style="display:inline;margin-left:auto;">
                <?= Util::csrfField() ?>
                <input type="hidden" name="aktion" value="log_leeren">
                <button type="submit" class="ad-btn ad-btn-danger ad-btn-small"
                        data-confirm="Protokoll vollständig leeren?">Protokoll leeren</button>
            </form>
        </div>
    </div>

    <?php if ($logs === []): ?>
        <div class="ad-empty">Keine Einträge.</div>
    <?php else: ?>
        <div class="ad-table-wrap">
            <table class="ad-table">
                <thead><tr><th>Zeitpunkt</th><th>Ebene</th><th>Bereich</th><th>Meldung</th></tr></thead>
                <tbody>
                <?php foreach ($logs as $row): ?>
                    <tr>
                        <td style="white-space:nowrap;"><?= Util::e(Util::dt((string) $row['created_at'], 'd.m.Y H:i:s')) ?></td>
                        <td>
                            <span class="ad-pill <?= match ((string) $row['level']) {
                                'error'   => 'ad-pill-red',
                                'warning' => 'ad-pill-amber',
                                default   => 'ad-pill-grey',
                            } ?>"><?= Util::e((string) $row['level']) ?></span>
                        </td>
                        <td class="ad-mono"><?= Util::e((string) $row['channel']) ?></td>
                        <td><?= Util::e((string) $row['message']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php';
