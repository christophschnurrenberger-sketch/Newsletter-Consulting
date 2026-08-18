<?php
/**
 * einstellungen.php – Selbstverwaltung für Empfänger.
 *
 * Hier kann jede Empfängerin und jeder Empfänger ohne Konto:
 *   – Namen und Unternehmen ändern
 *   – Themen (Listen) an- und abwählen
 *   – die gespeicherten Daten einsehen und herunterladen (DSGVO Art. 15)
 *   – sich abmelden oder alle Daten löschen lassen (DSGVO Art. 17)
 *
 * Der Zugang läuft über den persönlichen Link mit Signatur aus der E-Mail.
 */

require __DIR__ . '/lib/bootstrap.php';
require __DIR__ . '/partials/page.php';

$param = static function (string $key): string {
    $value = $_POST[$key] ?? $_GET[$key] ?? '';
    return is_string($value) ? trim($value) : '';
};

$token     = $param('t');
$signature = $param('s');

$subscriber = null;
if ($token !== '' && Util::checkSign('pref:' . $token, $signature)) {
    $subscriber = Subscribers::byToken($token);
}

if ($subscriber === null) {
    nl_page('Einstellungen', '<div class="nl-card">'
        . nl_notice('error', 'Dieser Link ist ungültig oder abgelaufen',
            'Bitte verwenden Sie den Link „Daten &amp; Einstellungen“ aus der zuletzt erhaltenen E-Mail.')
        . '</div>');
}

$notice = '';

/* ------------------------------------------------------------- Aktionen */

if (Util::isPost()) {
    $action = $param('aktion');

    if ($action === 'profil') {
        DB::update('subscribers', [
            'first_name' => mb_substr($param('first_name'), 0, 120),
            'last_name'  => mb_substr($param('last_name'), 0, 120),
            'company'    => mb_substr($param('company'), 0, 190),
            'salutation' => in_array($param('salutation'), ['Herr', 'Frau'], true) ? $param('salutation') : '',
        ], 'id = ?', [(int) $subscriber['id']]);

        $listIds = [];
        foreach ((array) ($_POST['lists'] ?? []) as $value) {
            if (is_scalar($value)) {
                $listIds[] = (int) $value;
            }
        }
        if (count(Lists::all()) > 1) {
            Subscribers::setLists((int) $subscriber['id'], $listIds);
        }
        Subscribers::logConsent((int) $subscriber['id'], (string) $subscriber['email'], 'update', 'Selbstverwaltung: Angaben geändert');
        $subscriber = Subscribers::byId((int) $subscriber['id']);
        $notice = nl_notice('success', 'Gespeichert', 'Ihre Angaben wurden aktualisiert.');
    }

    if ($action === 'reaktivieren' && $subscriber['status'] !== Subscribers::STATUS_ACTIVE) {
        try {
            $result = Subscribers::signup((string) $subscriber['email'], [], Subscribers::listIds((int) $subscriber['id']), 'selbstverwaltung');
            $notice = nl_notice('success', 'Bitte bestätigen Sie noch kurz', Util::e($result['message']));
        } catch (Throwable $e) {
            $notice = nl_notice('error', 'Das hat nicht geklappt', 'Bitte versuchen Sie es später erneut.');
        }
        $subscriber = Subscribers::byId((int) $subscriber['id']);
    }

    if ($action === 'export') {
        // Datenauskunft als JSON zum Herunterladen
        $data = [
            'stammdaten' => [
                'email'        => $subscriber['email'],
                'vorname'      => $subscriber['first_name'],
                'nachname'     => $subscriber['last_name'],
                'unternehmen'  => $subscriber['company'],
                'anrede'       => $subscriber['salutation'],
                'status'       => Subscribers::statusLabels()[$subscriber['status']] ?? $subscriber['status'],
                'quelle'       => $subscriber['source'],
                'angemeldet'   => $subscriber['created_at'],
                'bestaetigt'   => $subscriber['confirmed_at'],
                'abgemeldet'   => $subscriber['unsubscribed_at'],
                'ip_anmeldung' => $subscriber['signup_ip'],
                'ip_bestaetigung' => $subscriber['confirm_ip'],
            ],
            'listen'      => array_map(static fn($id) => Lists::name($id), Subscribers::listIds((int) $subscriber['id'])),
            'einwilligungen' => array_map(static fn($row) => [
                'zeitpunkt' => $row['created_at'],
                'ereignis'  => $row['event'],
                'details'   => $row['detail'],
                'ip'        => $row['ip'],
            ], Subscribers::consentLog((int) $subscriber['id'])),
            'aktivitaeten' => array_map(static fn($row) => [
                'zeitpunkt' => $row['created_at'],
                'ereignis'  => Events::label((string) $row['type']),
                'newsletter' => $row['campaign_name'] ?? '',
            ], Events::forSubscriber((int) $subscriber['id'], 200)),
            'erstellt_am' => Util::now(),
        ];
        Subscribers::logConsent((int) $subscriber['id'], (string) $subscriber['email'], 'export', 'Datenauskunft heruntergeladen');

        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="meine-newsletter-daten.json"');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    if ($action === 'loeschen' && $param('sicher') === 'ja') {
        $email = (string) $subscriber['email'];
        Subscribers::deleteCompletely((int) $subscriber['id'], true);
        Log::info('dsgvo', 'Empfänger auf eigenen Wunsch gelöscht: ' . $email);
        nl_page('Daten gelöscht', '<div class="nl-card">'
            . nl_notice('success', 'Ihre Daten wurden gelöscht',
                'Wir haben alle zu <strong>' . Util::e($email) . '</strong> gespeicherten Angaben entfernt. '
                . 'Es bleibt lediglich ein Sperrvermerk, damit Sie nicht versehentlich erneut angeschrieben werden.')
            . '<div class="nl-actions"><a class="nl-button nl-button-secondary" href="'
            . Util::e(Settings::get('website_url')) . '">Zur Website</a></div></div>');
    }
}

/* --------------------------------------------------------------- Ansicht */

$lists      = Lists::all();
$memberOf   = Subscribers::listIds((int) $subscriber['id']);
$statusText = Subscribers::statusLabels()[$subscriber['status']] ?? (string) $subscriber['status'];

ob_start();
?>
<div class="nl-card">
    <?= $notice ?>
    <h1>Ihre Newsletter-Einstellungen</h1>
    <p class="nl-lead">Angemeldet mit <strong><?= Util::e((string) $subscriber['email']) ?></strong> ·
        Status: <strong><?= Util::e($statusText) ?></strong></p>

    <?php if ($subscriber['status'] !== Subscribers::STATUS_ACTIVE): ?>
        <?= nl_notice('info', 'Sie erhalten derzeit keinen Newsletter',
            'Möchten Sie wieder dabei sein? Wir schicken Ihnen dann eine kurze Bestätigungsmail.') ?>
        <form method="post" style="margin-bottom:28px;">
            <input type="hidden" name="t" value="<?= Util::e($token) ?>">
            <input type="hidden" name="s" value="<?= Util::e($signature) ?>">
            <input type="hidden" name="aktion" value="reaktivieren">
            <button type="submit" class="nl-button">Newsletter wieder abonnieren</button>
        </form>
    <?php endif; ?>

    <form method="post" class="nl-form">
        <input type="hidden" name="t" value="<?= Util::e($token) ?>">
        <input type="hidden" name="s" value="<?= Util::e($signature) ?>">
        <input type="hidden" name="aktion" value="profil">

        <div class="nl-row">
            <div class="nl-field">
                <label for="first_name">Vorname</label>
                <input type="text" id="first_name" name="first_name" maxlength="120"
                       value="<?= Util::e((string) $subscriber['first_name']) ?>">
            </div>
            <div class="nl-field">
                <label for="last_name">Nachname</label>
                <input type="text" id="last_name" name="last_name" maxlength="120"
                       value="<?= Util::e((string) $subscriber['last_name']) ?>">
            </div>
        </div>

        <div class="nl-field">
            <label for="company">Unternehmen</label>
            <input type="text" id="company" name="company" maxlength="190"
                   value="<?= Util::e((string) $subscriber['company']) ?>">
        </div>

        <?php if (count($lists) > 1): ?>
            <fieldset class="nl-field nl-fieldset">
                <legend>Diese Themen möchte ich erhalten</legend>
                <?php foreach ($lists as $list): ?>
                    <label class="nl-check">
                        <input type="checkbox" name="lists[]" value="<?= (int) $list['id'] ?>"
                            <?= in_array((int) $list['id'], $memberOf, true) ? 'checked' : '' ?>>
                        <span><?= Util::e((string) $list['name']) ?>
                            <?php if (($list['description'] ?? '') !== ''): ?>
                                <em><?= Util::e((string) $list['description']) ?></em>
                            <?php endif; ?>
                        </span>
                    </label>
                <?php endforeach; ?>
            </fieldset>
        <?php endif; ?>

        <button type="submit" class="nl-button">Änderungen speichern</button>
    </form>

    <h2>Ihre gespeicherten Daten</h2>
    <table class="nl-data">
        <tr><th>Angemeldet am</th><td><?= Util::e(Util::dt((string) $subscriber['created_at'])) ?></td></tr>
        <tr><th>Bestätigt am</th><td><?= Util::e(Util::dt((string) $subscriber['confirmed_at'])) ?></td></tr>
        <tr><th>Quelle der Anmeldung</th><td><?= Util::e((string) ($subscriber['source'] ?: '—')) ?></td></tr>
        <tr><th>Listen</th><td><?= Util::e(implode(', ', array_map(static fn($id) => Lists::name($id), $memberOf)) ?: '—') ?></td></tr>
    </table>

    <form method="post" style="display:inline-block;margin-right:12px;">
        <input type="hidden" name="t" value="<?= Util::e($token) ?>">
        <input type="hidden" name="s" value="<?= Util::e($signature) ?>">
        <input type="hidden" name="aktion" value="export">
        <button type="submit" class="nl-button nl-button-secondary">Alle Daten herunterladen</button>
    </form>

    <h2>Abmelden oder löschen</h2>
    <p>Sie können den Newsletter abbestellen – dann bleiben Ihre Daten mit dem Vermerk „abgemeldet“
        gespeichert. Oder Sie lassen alle Daten vollständig löschen.</p>

    <div class="nl-actions">
        <a class="nl-button nl-button-secondary" href="<?= Util::e(Urls::unsubscribe($token)) ?>">Newsletter abbestellen</a>
    </div>

    <form method="post" style="margin-top:20px;padding-top:20px;border-top:1px solid var(--nl-border);">
        <input type="hidden" name="t" value="<?= Util::e($token) ?>">
        <input type="hidden" name="s" value="<?= Util::e($signature) ?>">
        <input type="hidden" name="aktion" value="loeschen">
        <label class="nl-check">
            <input type="checkbox" name="sicher" value="ja" required>
            <span>Ja, bitte löschen Sie alle zu meiner Adresse gespeicherten Daten unwiderruflich.</span>
        </label>
        <button type="submit" class="nl-button nl-button-danger">Daten endgültig löschen</button>
    </form>
</div>
<?php
nl_page('Newsletter-Einstellungen', (string) ob_get_clean());
