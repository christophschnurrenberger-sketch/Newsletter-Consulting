<?php
/**
 * index.php – Übersicht: Zahlen, offene Aufgaben, letzte Kampagnen.
 */

$pageTitle = 'Übersicht';
require __DIR__ . '/partials/header.php';

$counts    = Subscribers::statusCounts();
$queue     = Queue::overview();
$problems  = Settings::readiness();
$campaigns = array_slice(Campaigns::all(), 0, 5);

// Neuanmeldungen der letzten 14 Tage für den kleinen Verlauf
$growth = [];
for ($i = 13; $i >= 0; $i--) {
    $day = date('Y-m-d', time() - $i * 86400);
    $growth[$day] = (int) DB::value(
        "SELECT COUNT(*) FROM subscribers WHERE confirmed_at >= ? AND confirmed_at < ?",
        [$day . ' 00:00:00', $day . ' 23:59:59']
    );
}
$maxGrowth = max(1, max($growth));
?>

<div class="ad-page-head">
    <div>
        <h1>Übersicht</h1>
        <p class="ad-sub">Stand: <?= Util::e(date('d.m.Y, H:i')) ?> Uhr</p>
    </div>
    <div class="ad-actions-inline">
        <a class="ad-btn" href="neu.php">Newsletter schreiben</a>
        <a class="ad-btn ad-btn-secondary" href="empfaenger.php?neu=1">Empfänger hinzufügen</a>
    </div>
</div>

<?php if ($problems !== []): ?>
    <div class="ad-flash ad-flash-warning">
        <strong>Vor dem ersten Versand noch zu erledigen:</strong>
        <ul style="margin:8px 0 0 18px;padding:0;">
            <?php foreach ($problems as $problem): ?>
                <li><?= Util::e($problem) ?></li>
            <?php endforeach; ?>
        </ul>
        <?php /* Wer die Einstellungen nicht darf, landete hier bisher auf einer
                 Abweisung. Dann lieber sagen, wer weiterhilft. */ ?>
        <?php if (Auth::can('einstellungen')): ?>
            <p style="margin:10px 0 0;"><a href="einstellungen.php">Zu den Einstellungen</a></p>
        <?php else: ?>
            <p style="margin:10px 0 0;">Das erledigt eine Administratorin oder ein Administrator
                in den Einstellungen.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="ad-grid">
    <div class="ad-stat">
        <div class="ad-stat-label">Aktive Empfänger</div>
        <div class="ad-stat-value"><?= Util::num($counts[Subscribers::STATUS_ACTIVE]) ?></div>
        <div class="ad-stat-note"><?= Util::num($counts[Subscribers::STATUS_PENDING]) ?> warten auf Bestätigung</div>
    </div>
    <div class="ad-stat">
        <div class="ad-stat-label">Offene Sendungen</div>
        <div class="ad-stat-value"><?= Util::num((int) $queue['pending']) ?></div>
        <div class="ad-stat-note">
            <?php if ((int) $queue['pending'] > 0): ?>
                nächste fällig: <?= Util::e(Util::dt((string) $queue['next_due'], 'd.m. H:i')) ?>
            <?php else: ?>
                nichts in der Warteschlange
            <?php endif; ?>
        </div>
    </div>
    <div class="ad-stat">
        <div class="ad-stat-label">Heute versendet</div>
        <div class="ad-stat-value"><?= Util::num((int) $queue['sent_today']) ?></div>
        <div class="ad-stat-note"><?= Util::num((int) $queue['sent_hour']) ?> in der letzten Stunde
            (Limit <?= Util::num(Settings::int('hourly_limit', 500)) ?>/h)</div>
    </div>
    <div class="ad-stat">
        <div class="ad-stat-label">Abmeldungen &amp; Bounces</div>
        <div class="ad-stat-value"><?= Util::num($counts[Subscribers::STATUS_UNSUBSCRIBED]) ?></div>
        <div class="ad-stat-note"><?= Util::num($counts[Subscribers::STATUS_BOUNCED] + $counts[Subscribers::STATUS_COMPLAINED]) ?>
            unzustellbar/gesperrt</div>
    </div>
</div>

<div class="ad-grid-2">
    <div class="ad-card">
        <h2>Bestätigte Anmeldungen (14 Tage)</h2>
        <div style="display:flex;align-items:flex-end;gap:5px;height:110px;margin-top:14px;">
            <?php foreach ($growth as $day => $value): ?>
                <div style="flex:1;text-align:center;" title="<?= Util::e(date('d.m.Y', strtotime($day))) ?>: <?= (int) $value ?>">
                    <div style="height:<?= (int) max(3, round($value / $maxGrowth * 90)) ?>px;background:<?= $value > 0 ? 'var(--ad-navy)' : '#E2E8F0' ?>;border-radius:3px 3px 0 0;"></div>
                    <div style="font-size:10px;color:var(--ad-muted);margin-top:4px;"><?= Util::e(date('d.', strtotime($day))) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <p class="ad-hint">Summe: <?= Util::num(array_sum($growth)) ?> neue bestätigte Anmeldungen</p>
    </div>

    <div class="ad-card">
        <h2>Versandstatus</h2>
        <table class="ad-table">
            <tr><th>Warteschlange offen</th><td class="ad-num"><?= Util::num((int) $queue['pending']) ?></td></tr>
            <tr><th>Insgesamt versendet</th><td class="ad-num"><?= Util::num((int) $queue['sent_total']) ?></td></tr>
            <tr><th>Fehlgeschlagen</th><td class="ad-num"><?= Util::num((int) $queue['failed']) ?></td></tr>
            <tr><th>Diese Stunde noch möglich</th><td class="ad-num"><?= Util::num((int) $queue['hourly_left']) ?></td></tr>
            <tr><th>Letzter Cron-Lauf</th><td class="ad-num"><?= Util::e(Util::dt((string) $queue['last_cron_at'])) ?></td></tr>
        </table>
        <div class="ad-actions">
            <a class="ad-btn ad-btn-secondary ad-btn-small" href="versand.php">Versand steuern</a>
        </div>
        <?php if ((string) $queue['last_cron_at'] === '' || strtotime((string) $queue['last_cron_at']) < time() - 3600): ?>
            <p class="ad-hint" style="color:var(--ad-amber);">
                Der Cron-Job hat sich seit über einer Stunde nicht gemeldet. Ohne ihn bleiben Mails liegen –
                siehe README, Abschnitt „Cron einrichten“.
            </p>
        <?php endif; ?>
    </div>
</div>

<h2>Letzte Newsletter</h2>
<?php if ($campaigns === []): ?>
    <div class="ad-empty">
        Noch kein Newsletter angelegt. <a href="neu.php">Jetzt den ersten schreiben</a>
    </div>
<?php else: ?>
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead>
            <tr>
                <th>Betreff</th>
                <th>Status</th>
                <th class="ad-num">Empfänger</th>
                <th class="ad-num">Öffnungen</th>
                <th class="ad-num">Klicks</th>
                <th>Versand</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($campaigns as $campaign):
                $stats = Campaigns::stats((int) $campaign['id']); ?>
                <tr>
                    <td>
                        <a href="kampagne.php?id=<?= (int) $campaign['id'] ?>">
                            <?= Util::e($campaign['subject'] !== '' ? (string) $campaign['subject'] : (string) $campaign['name']) ?>
                        </a>
                    </td>
                    <td><?= campaign_status_pill((string) $campaign['status']) ?></td>
                    <td class="ad-num"><?= Util::num((int) $stats['sent']) ?></td>
                    <td class="ad-num"><?= Util::e((string) $stats['open_rate']) ?></td>
                    <td class="ad-num"><?= Util::e((string) $stats['click_rate']) ?></td>
                    <td><?= Util::e(Util::dt((string) ($campaign['started_at'] ?: $campaign['created_at']), 'd.m.Y H:i')) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php';
