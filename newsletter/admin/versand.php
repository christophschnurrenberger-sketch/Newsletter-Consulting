<?php
/**
 * versand.php – Warteschlange beobachten und steuern.
 *
 * Der reguläre Versand läuft über den Cron-Job. Hier lässt sich ein
 * Durchlauf zusätzlich von Hand anstoßen – praktisch zum Testen.
 */

$pageTitle = 'Versand';
require __DIR__ . '/partials/header.php';

$runResult = null;

if (Util::isPost()) {
    Util::requireCsrf();
    $action = Util::post('aktion');

    if ($action === 'jetzt_senden') {
        // Bewusst kleine Portion, damit der Browser nicht in eine Zeitüberschreitung läuft
        @set_time_limit(120);
        $runResult = Queue::process(['limit' => min(50, Settings::int('batch_size', 50)), 'seconds' => 45]);
        Util::flash(sprintf('Durchlauf beendet: %d versendet, %d fehlgeschlagen, %d übersprungen – %d noch offen.',
            $runResult['sent'], $runResult['failed'], $runResult['skipped'], $runResult['remaining']));
        Util::redirect('versand.php');
    }
    if ($action === 'wiederholen') {
        $count = Queue::retryFailed();
        Util::flash(Util::num($count) . ' fehlgeschlagene Sendungen wurden erneut eingereiht.');
        Util::redirect('versand.php');
    }
    if ($action === 'sperren_loesen') {
        $count = Queue::releaseStaleLocks();
        Util::flash(Util::num($count) . ' hängende Sendungen wurden freigegeben.');
        Util::redirect('versand.php');
    }
    if ($action === 'aufraeumen') {
        $count = Queue::prune(180);
        Util::flash(Util::num($count) . ' alte Einträge entfernt.');
        Util::redirect('versand.php');
    }
    if ($action === 'transport_test') {
        $error = Mailer::testTransport();
        if ($error === '') {
            Util::flash('Verbindung zum Versandweg erfolgreich geprüft.');
        } else {
            Util::flash('Verbindung fehlgeschlagen: ' . Util::e($error), 'error');
        }
        Util::redirect('versand.php');
    }
}

$overview = Queue::overview();
$running  = DB::all("SELECT * FROM campaigns WHERE status IN ('sending', 'scheduled', 'paused') ORDER BY id DESC");
$upcoming = DB::all(
    "SELECT q.email, q.due_at, q.attempts, q.campaign_id, q.step_id, c.subject
     FROM queue q LEFT JOIN campaigns c ON c.id = q.campaign_id
     WHERE q.status = 'pending' ORDER BY q.due_at, q.id LIMIT 20"
);
$failed = DB::all(
    "SELECT q.email, q.last_error, q.attempts, c.subject
     FROM queue q LEFT JOIN campaigns c ON c.id = q.campaign_id
     WHERE q.status = 'failed' ORDER BY q.id DESC LIMIT 20"
);
$cronStale = Settings::get('last_cron_at') === '' || strtotime(Settings::get('last_cron_at')) < time() - 3600;
?>

<div class="ad-page-head">
    <div>
        <h1>Versand</h1>
        <p class="ad-sub">Warteschlange, Tempo und Fehler im Blick</p>
    </div>
    <form method="post" class="ad-actions-inline">
        <?= Util::csrfField() ?>
        <button type="submit" name="aktion" value="jetzt_senden" class="ad-btn"
                data-confirm="Jetzt eine Portion aus der Warteschlange verschicken?">Portion jetzt senden</button>
        <button type="submit" name="aktion" value="transport_test" class="ad-btn ad-btn-secondary">Versandweg testen</button>
    </form>
</div>

<?php if ($cronStale): ?>
    <div class="ad-flash ad-flash-warning">
        <strong>Der Cron-Job meldet sich nicht.</strong> Ohne ihn bleibt die Warteschlange stehen.
        Richten Sie im Hosting-Menü einen Aufruf alle 5 Minuten ein – die genaue Zeile steht in der
        README im Ordner <code>newsletter/</code>.
    </div>
<?php endif; ?>

<div class="ad-grid">
    <div class="ad-stat">
        <div class="ad-stat-label">Offen</div>
        <div class="ad-stat-value"><?= Util::num((int) $overview['pending']) ?></div>
        <div class="ad-stat-note">nächste: <?= Util::e(Util::dt((string) $overview['next_due'], 'd.m. H:i')) ?></div>
    </div>
    <div class="ad-stat">
        <div class="ad-stat-label">Letzte Stunde</div>
        <div class="ad-stat-value"><?= Util::num((int) $overview['sent_hour']) ?></div>
        <div class="ad-stat-note">noch möglich: <?= Util::num((int) $overview['hourly_left']) ?></div>
    </div>
    <div class="ad-stat">
        <div class="ad-stat-label">Heute</div>
        <div class="ad-stat-value"><?= Util::num((int) $overview['sent_today']) ?></div>
        <div class="ad-stat-note">insgesamt <?= Util::num((int) $overview['sent_total']) ?></div>
    </div>
    <div class="ad-stat">
        <div class="ad-stat-label">Fehlgeschlagen</div>
        <div class="ad-stat-value"><?= Util::num((int) $overview['failed']) ?></div>
        <div class="ad-stat-note">Versandweg: <?= Util::e(Settings::get('transport')) ?></div>
    </div>
</div>

<div class="ad-card">
    <h2>Einstellungen des Tempos</h2>
    <p class="ad-hint" style="margin-top:-4px;">
        Pro Cron-Lauf <strong><?= Util::num(Settings::int('batch_size', 50)) ?></strong> Mails ·
        Pause dazwischen <strong><?= Util::num(Settings::int('send_delay_ms', 400)) ?> ms</strong> ·
        höchstens <strong><?= Util::num(Settings::int('hourly_limit', 500)) ?></strong> Mails pro Stunde ·
        <strong><?= Util::num(Settings::int('max_attempts', 3)) ?></strong> Zustellversuche.
        <a href="einstellungen.php">Ändern</a>
    </p>
    <form method="post" class="ad-actions" style="margin-top:10px;">
        <?= Util::csrfField() ?>
        <button type="submit" name="aktion" value="wiederholen" class="ad-btn ad-btn-secondary ad-btn-small">Fehlgeschlagene wiederholen</button>
        <button type="submit" name="aktion" value="sperren_loesen" class="ad-btn ad-btn-secondary ad-btn-small">Hängende Sendungen freigeben</button>
        <button type="submit" name="aktion" value="aufraeumen" class="ad-btn ad-btn-secondary ad-btn-small"
                data-confirm="Versandprotokoll älter als 180 Tage löschen?">Alte Einträge aufräumen</button>
    </form>
</div>

<?php if ($running !== []): ?>
    <h2>Laufende und geplante Ausgaben</h2>
    <div class="ad-table-wrap" <?= $overview['pending'] > 0 ? 'data-autorefresh="30"' : '' ?>>
        <table class="ad-table">
            <thead><tr><th>Betreff</th><th>Status</th><th class="ad-num">Fortschritt</th><th>Geplant für</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($running as $campaign):
                $stats = Campaigns::stats((int) $campaign['id']);
                $done  = (int) $stats['total'] > 0 ? round((int) $stats['sent'] / (int) $stats['total'] * 100) : 0; ?>
                <tr>
                    <td><a href="kampagne.php?id=<?= (int) $campaign['id'] ?>"><?= Util::e((string) $campaign['subject']) ?></a></td>
                    <td><?= campaign_status_pill((string) $campaign['status']) ?></td>
                    <td class="ad-num">
                        <?= Util::num((int) $stats['sent']) ?> / <?= Util::num((int) $stats['total']) ?>
                        <div class="ad-progress" style="width:120px;margin-left:auto;"><span style="width:<?= $done ?>%"></span></div>
                    </td>
                    <td><?= Util::e(Util::dt((string) $campaign['scheduled_at'])) ?></td>
                    <td><a class="ad-btn ad-btn-secondary ad-btn-small" href="kampagne.php?id=<?= (int) $campaign['id'] ?>">Steuern</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<h2>Nächste Sendungen</h2>
<?php if ($upcoming === []): ?>
    <div class="ad-empty">Die Warteschlange ist leer.</div>
<?php else: ?>
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>Empfänger</th><th>Newsletter</th><th>Fällig</th><th class="ad-num">Versuche</th></tr></thead>
            <tbody>
            <?php foreach ($upcoming as $row): ?>
                <tr>
                    <td class="ad-mono"><?= Util::e((string) $row['email']) ?></td>
                    <td><?= Util::e((string) ($row['subject'] ?: ($row['step_id'] !== null ? 'Automation' : '—'))) ?></td>
                    <td><?= Util::e(Util::dt((string) $row['due_at'])) ?></td>
                    <td class="ad-num"><?= (int) $row['attempts'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php if ($failed !== []): ?>
    <h2>Fehlgeschlagene Sendungen</h2>
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>Empfänger</th><th>Newsletter</th><th class="ad-num">Versuche</th><th>Meldung</th></tr></thead>
            <tbody>
            <?php foreach ($failed as $row): ?>
                <tr>
                    <td class="ad-mono"><?= Util::e((string) $row['email']) ?></td>
                    <td><?= Util::e((string) ($row['subject'] ?: '—')) ?></td>
                    <td class="ad-num"><?= (int) $row['attempts'] ?></td>
                    <td><span class="ad-truncate"><?= Util::e(Util::shorten((string) $row['last_error'], 120)) ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php';
