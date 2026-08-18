<?php
/**
 * abmelden.php – Abmeldung vom Newsletter.
 *
 * Drei Wege führen hierher:
 *   1. Klick auf den Abmeldelink in der Mail  → Seite mit Bestätigungsknopf
 *   2. "Abmelden"-Knopf des E-Mail-Programms  → POST nach RFC 8058 (One-Click)
 *   3. Bestätigung auf dieser Seite           → POST mit Signatur
 *
 * Warum bei Weg 1 ein Zwischenschritt? Virenscanner und Vorschaudienste
 * rufen Links in E-Mails automatisch auf. Ein GET darf deshalb nichts
 * verändern – sonst würden Empfänger ungewollt abgemeldet.
 */

require __DIR__ . '/lib/bootstrap.php';
require __DIR__ . '/partials/page.php';

/**
 * Wert aus POST oder Query lesen. Beim One-Click-Verfahren schickt das
 * E-Mail-Programm einen POST an die unveränderte URL – die Parameter
 * stehen dann weiterhin im Query-String.
 */
$param = static function (string $key): string {
    $value = $_POST[$key] ?? $_GET[$key] ?? '';
    return is_string($value) ? trim($value) : '';
};

$token      = $param('t');
$signature  = $param('s');
$queueToken = $param('q');
$oneClick   = $param('one') === '1';

/* Token und Signatur prüfen */
$subscriber = null;
if ($token !== '' && Util::checkSign('unsub:' . $token, $signature)) {
    $subscriber = Subscribers::byToken($token);
}

if ($subscriber === null) {
    if (Util::isPost() && $oneClick) {
        http_response_code(400);
        header('Content-Type: text/plain; charset=utf-8');
        exit('Ungueltiger Abmeldelink.');
    }
    nl_page('Abmeldung', '<div class="nl-card">'
        . nl_notice('error', 'Dieser Abmeldelink ist ungültig',
            'Bitte verwenden Sie den Link aus der zuletzt erhaltenen E-Mail. '
            . 'Alternativ schreiben Sie uns kurz an <a href="mailto:' . Util::e(Settings::get('contact_email')) . '">'
            . Util::e(Settings::get('contact_email')) . '</a> – wir tragen Sie dann von Hand aus.')
        . '</div>');
}

/* Kampagne ermitteln, aus der abgemeldet wurde (für die Statistik) */
$campaignId = null;
if ($queueToken !== '') {
    $row = DB::row('SELECT campaign_id FROM queue WHERE token = ?', [$queueToken]);
    if ($row !== null && $row['campaign_id'] !== null) {
        $campaignId = (int) $row['campaign_id'];
    }
}

/* ---------------------------------------------------- Abmeldung ausführen */

if (Util::isPost()) {
    $reason = mb_substr($param('grund'), 0, 300);
    $detail = $oneClick ? 'Abmeldung über das E-Mail-Programm (One-Click)' : 'Abmeldung über die Website';
    if ($reason !== '') {
        $detail .= ' – Grund: ' . $reason;
    }

    Subscribers::unsubscribe($subscriber, $detail, $campaignId);

    // Antwort für den One-Click-Knopf des Mailprogramms: schlichter Text
    if ($oneClick && $param('bestaetigt') === '') {
        header('Content-Type: text/plain; charset=utf-8');
        exit('OK - Sie wurden abgemeldet.');
    }

    ob_start();
    ?>
    <div class="nl-card">
        <?= nl_notice('success', 'Sie sind abgemeldet',
            'Ihre Adresse <strong>' . Util::e((string) $subscriber['email']) . '</strong> erhält ab sofort keinen Newsletter mehr.') ?>
        <h1>Schade, dass Sie gehen</h1>
        <p>Wir haben Ihre Abmeldung sofort umgesetzt. Bereits versendete Mails können Sie eventuell noch
            erhalten – das legt sich innerhalb weniger Minuten.</p>
        <p>Falls das ein Versehen war, können Sie sich jederzeit erneut anmelden:</p>
        <div class="nl-actions">
            <a class="nl-button nl-button-secondary" href="anmelden.php">Newsletter wieder abonnieren</a>
            <a class="nl-button nl-button-secondary" href="<?= Util::e(Settings::get('website_url')) ?>">Zur Website</a>
        </div>
    </div>
    <?php
    nl_page('Abgemeldet', (string) ob_get_clean());
}

/* ------------------------------------------------- Bestätigungsseite (GET) */

if ($subscriber['status'] === Subscribers::STATUS_UNSUBSCRIBED) {
    nl_page('Bereits abgemeldet', '<div class="nl-card">'
        . nl_notice('info', 'Sie sind bereits abgemeldet',
            'Für <strong>' . Util::e((string) $subscriber['email']) . '</strong> ist bereits keine Zustellung mehr aktiv.')
        . '<div class="nl-actions"><a class="nl-button nl-button-secondary" href="anmelden.php">Erneut abonnieren</a></div>'
        . '</div>');
}

ob_start();
?>
<div class="nl-card">
    <h1>Newsletter abbestellen</h1>
    <p class="nl-lead">Möchten Sie <strong><?= Util::e((string) $subscriber['email']) ?></strong> wirklich
        vom Newsletter abmelden? Ein Klick genügt.</p>

    <form method="post" action="abmelden.php" class="nl-form">
        <input type="hidden" name="t" value="<?= Util::e($token) ?>">
        <input type="hidden" name="s" value="<?= Util::e($signature) ?>">
        <input type="hidden" name="q" value="<?= Util::e($queueToken) ?>">
        <input type="hidden" name="bestaetigt" value="1">

        <div class="nl-field">
            <label for="grund">Warum gehen Sie? <span class="nl-optional">(optional, hilft uns sehr)</span></label>
            <input type="text" id="grund" name="grund" maxlength="300"
                   placeholder="z. B. zu häufig, Themen passen nicht mehr">
        </div>

        <div class="nl-actions">
            <button type="submit" class="nl-button">Jetzt abmelden</button>
            <a class="nl-button nl-button-secondary" href="<?= Util::e(Urls::preferences($token)) ?>">Lieber Einstellungen ändern</a>
        </div>
    </form>

    <p class="nl-form-note">Statt sich ganz abzumelden, können Sie unter „Einstellungen“ auch nur einzelne
        Themen abwählen oder Ihre Daten einsehen und löschen lassen.</p>
</div>
<?php
nl_page('Newsletter abbestellen', (string) ob_get_clean());
