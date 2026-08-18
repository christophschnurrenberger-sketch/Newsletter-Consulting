<?php
/**
 * bestaetigen.php – Klick auf den Bestätigungslink aus der Double-Opt-in-Mail.
 * Erst hier wird aus einer Anmeldung eine gültige Einwilligung.
 */

require __DIR__ . '/lib/bootstrap.php';
require __DIR__ . '/partials/page.php';

$token  = Util::get('t');
$result = ['ok' => false, 'subscriber' => null, 'message' => 'Dieser Bestätigungslink ist ungültig.'];

if ($token !== '') {
    // Schutz gegen das Durchprobieren von Token
    if (!Util::rateLimit('confirm', Util::ip(), 30, 3600)) {
        nl_page('Bestätigung', '<div class="nl-card">'
            . nl_notice('error', 'Zu viele Versuche', 'Bitte versuchen Sie es in einer Stunde erneut.')
            . '</div>');
    }
    $result = Subscribers::confirm($token);
}

ob_start();
if ($result['ok']) {
    $sub = $result['subscriber'] ?? [];
    ?>
    <div class="nl-card">
        <?= nl_notice('success', 'Anmeldung bestätigt', Util::e($result['message'])) ?>
        <h1>Willkommen!</h1>
        <p class="nl-lead">Ihre Anmeldung für <strong><?= Util::e((string) ($sub['email'] ?? '')) ?></strong>
            ist ab sofort aktiv. Die nächste Ausgabe erhalten Sie automatisch.</p>
        <p>Sie können Ihre Angaben jederzeit ändern oder den Newsletter mit einem Klick abbestellen –
            die Links dazu stehen am Ende jeder E-Mail.</p>
        <div class="nl-actions">
            <a class="nl-button" href="<?= Util::e(Settings::get('website_url')) ?>">Zur Website</a>
            <?php if (!empty($sub['token'])): ?>
                <a class="nl-button nl-button-secondary" href="<?= Util::e(Urls::preferences((string) $sub['token'])) ?>">Daten &amp; Einstellungen</a>
            <?php endif; ?>
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="nl-card">
        <?= nl_notice('error', 'Bestätigung nicht möglich', Util::e($result['message'])) ?>
        <h1>Link abgelaufen oder bereits ersetzt</h1>
        <p>Das passiert, wenn der Link älter als 14 Tage ist oder wenn Sie sich zwischenzeitlich erneut
            angemeldet haben – dann gilt nur noch der Link aus der neuesten E-Mail.</p>
        <p>Am schnellsten geht es, wenn Sie sich einfach noch einmal anmelden:</p>
        <div class="nl-actions">
            <a class="nl-button" href="anmelden.php">Erneut anmelden</a>
        </div>
    </div>
    <?php
}
nl_page($result['ok'] ? 'Anmeldung bestätigt' : 'Bestätigung fehlgeschlagen', (string) ob_get_clean());
