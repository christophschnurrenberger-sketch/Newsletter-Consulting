<?php
/**
 * archiv.php – öffentliches Newsletter-Archiv und Browser-Ansicht.
 *
 *   archiv.php              → Liste der veröffentlichten Ausgaben
 *   archiv.php?c=ID         → eine Ausgabe im Rahmen der Website
 *   archiv.php?c=ID&raw=1   → die Mail selbst (wird im Rahmen eingebettet)
 *   archiv.php?c=ID&q=TOKEN → Browser-Ansicht aus der Mail heraus
 */

require __DIR__ . '/lib/bootstrap.php';
require __DIR__ . '/partials/page.php';

if (!Settings::bool('archive_enabled')) {
    nl_page('Archiv', '<div class="nl-card">'
        . nl_notice('info', 'Kein Archiv verfügbar', 'Frühere Ausgaben werden derzeit nicht veröffentlicht.')
        . '</div>');
}

$campaignId = Util::getInt('c');
$queueToken = Util::get('q');
$raw        = Util::get('raw') === '1';

/* ------------------------------------------------------ Einzelne Ausgabe */

if ($campaignId > 0) {
    $campaign = Campaigns::byId($campaignId);

    // Sichtbar sind nur veröffentlichte, bereits versendete Ausgaben.
    // Wer den persönlichen Link aus der Mail nutzt, sieht sie immer.
    $viaMail = false;
    if ($queueToken !== '') {
        $row = DB::row('SELECT campaign_id FROM queue WHERE token = ?', [$queueToken]);
        $viaMail = $row !== null && (int) $row['campaign_id'] === $campaignId;
    }

    $visible = $campaign !== null
        && ($viaMail || ((int) $campaign['archive_public'] === 1
            && in_array($campaign['status'], [Campaigns::SENT, Campaigns::SENDING], true)));

    if (!$visible) {
        http_response_code(404);
        nl_page('Ausgabe nicht gefunden', '<div class="nl-card">'
            . nl_notice('error', 'Diese Ausgabe gibt es nicht (mehr)',
                'Vielleicht wurde sie zurückgezogen. <a href="archiv.php">Zur Übersicht aller Ausgaben</a>')
            . '</div>');
    }

    if (trim((string) $campaign['compiled_html']) === '') {
        Campaigns::compile($campaignId);
        $campaign = Campaigns::byId($campaignId);
    }

    if ($raw) {
        // Die Mail selbst – ohne Zählpixel, mit neutralen Platzhaltern.
        $sample = Renderer::sampleSubscriber('archiv@example.com');
        $sample['first_name'] = '';
        $sample['last_name']  = '';
        $sample['company']    = '';
        $sample['salutation'] = '';

        $html = (string) $campaign['compiled_html'];
        $html = str_replace(Renderer::TOKEN, 'archiv', $html);
        // Zählpixel in der Archivansicht entfernen
        $html = preg_replace('#<img[^>]+track\.php\?o=[^>]*>#i', '', $html) ?? $html;

        $html = Renderer::personalize($html, $sample, [
            'abmelden_url'     => Config::url('anmelden.php'),
            'praeferenzen_url' => Config::url('anmelden.php'),
            'webansicht_url'   => Urls::webview($campaignId),
            'vorname'          => '',
            'name'             => '',
            'anrede'           => 'Hallo',
            'email'            => '',
        ], true);

        Util::previewHeaders();
        echo $html;
        exit;
    }

    ob_start();
    ?>
    <div class="nl-card">
        <p style="font-size:13px;color:var(--nl-muted);margin-bottom:6px;">
            <a href="archiv.php">← Alle Ausgaben</a>
        </p>
        <h1><?= Util::e((string) $campaign['subject']) ?></h1>
        <p class="nl-lead"><?= Util::e(Util::dt((string) ($campaign['started_at'] ?: $campaign['created_at']), 'd.m.Y')) ?>
            <?php if (trim((string) $campaign['preheader']) !== ''): ?>
                · <?= Util::e((string) $campaign['preheader']) ?>
            <?php endif; ?>
        </p>
    </div>

    <iframe class="nl-mail-frame" style="margin-top:24px;"
            src="archiv.php?c=<?= $campaignId ?>&amp;raw=1<?= $viaMail ? '&amp;q=' . rawurlencode($queueToken) : '' ?>"
            title="Newsletter-Ausgabe: <?= Util::e((string) $campaign['subject']) ?>"></iframe>

    <div class="nl-card" style="margin-top:24px;text-align:center;">
        <p><strong>Diese Ausgabe hat Ihnen gefallen?</strong></p>
        <p>Melden Sie sich an und erhalten Sie die nächste automatisch.</p>
        <a class="nl-button" href="anmelden.php">Newsletter abonnieren</a>
    </div>
    <?php
    nl_page((string) $campaign['subject'], (string) ob_get_clean(), ['wide' => true]);
}

/* ------------------------------------------------------------- Übersicht */

$campaigns = Campaigns::archived();

ob_start();
?>
<div class="nl-card">
    <h1>Newsletter-Archiv</h1>
    <p class="nl-lead">Alle bisher versendeten Ausgaben zum Nachlesen – ohne Anmeldung.</p>

    <?php if ($campaigns === []): ?>
        <?= nl_notice('info', 'Noch keine Ausgabe veröffentlicht', 'Schauen Sie in Kürze wieder vorbei.') ?>
    <?php else: ?>
        <ul class="nl-archive-list">
            <?php foreach ($campaigns as $campaign): ?>
                <li>
                    <a href="archiv.php?c=<?= (int) $campaign['id'] ?>"><?= Util::e((string) $campaign['subject']) ?></a>
                    <span class="nl-archive-date">
                        <?= Util::e(Util::dt((string) $campaign['started_at'], 'd.m.Y')) ?>
                    </span>
                    <?php if (trim((string) $campaign['preheader']) !== ''): ?>
                        <p class="nl-archive-preheader"><?= Util::e((string) $campaign['preheader']) ?></p>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <div class="nl-actions" style="margin-top:28px;">
        <a class="nl-button" href="anmelden.php">Newsletter abonnieren</a>
    </div>
</div>
<?php
nl_page('Newsletter-Archiv', (string) ob_get_clean());
