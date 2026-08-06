<?php
/**
 * Gemeinsames Layout aller öffentlichen Newsletter-Seiten
 * (Anmeldung, Bestätigung, Abmeldung, Einstellungen, Archiv).
 */

/**
 * Gibt eine vollständige HTML-Seite aus und beendet das Skript.
 *
 * @param array{status?:string,noindex?:bool,wide?:bool} $options
 */
function nl_page(string $title, string $bodyHtml, array $options = []): void
{
    $brand   = Settings::get('brand_name');
    $website = Settings::get('website_url');
    $privacy = Settings::get('privacy_url');
    $imprint = Settings::get('imprint_url');
    $wide    = !empty($options['wide']);

    if (!headers_sent()) {
        header('Content-Type: text/html; charset=utf-8');
    }
    ?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= Util::e($title) ?> | <?= Util::e($brand) ?></title>
<link rel="stylesheet" href="assets/newsletter.css">
</head>
<body>
<header class="nl-header">
    <a class="nl-brand" href="<?= Util::e($website) ?>">
        <span class="nl-brand-mark" aria-hidden="true">A</span>
        <span class="nl-brand-text">
            <span class="nl-brand-name"><?= Util::e($brand) ?></span>
            <span class="nl-brand-line">Newsletter</span>
        </span>
    </a>
</header>

<main class="nl-main<?= $wide ? ' nl-main-wide' : '' ?>">
    <?= $bodyHtml ?>
</main>

<footer class="nl-footer">
    <p>
        <a href="<?= Util::e($website) ?>">Zur Website</a>
        <?php if ($privacy !== ''): ?> · <a href="<?= Util::e($privacy) ?>">Datenschutz</a><?php endif; ?>
        <?php if ($imprint !== ''): ?> · <a href="<?= Util::e($imprint) ?>">Impressum</a><?php endif; ?>
        <?php if (Settings::bool('archive_enabled')): ?> · <a href="archiv.php">Newsletter-Archiv</a><?php endif; ?>
    </p>
    <p class="nl-footer-legal"><?= nl2br(Util::e(Settings::get('imprint'))) ?></p>
</footer>
</body>
</html>
    <?php
    exit;
}

/** Erfolgs-, Hinweis- oder Fehlerkasten. */
function nl_notice(string $type, string $title, string $text = ''): string
{
    $icon = match ($type) {
        'success' => '✓',
        'error'   => '!',
        default   => 'i',
    };
    return '<div class="nl-notice nl-notice-' . Util::e($type) . '">'
        . '<span class="nl-notice-icon" aria-hidden="true">' . $icon . '</span>'
        . '<div><strong>' . Util::e($title) . '</strong>'
        . ($text !== '' ? '<p>' . $text . '</p>' : '')
        . '</div></div>';
}

/**
 * Das Anmeldeformular (auch auf anmelden.php eingebunden).
 *
 * @param array<string,mixed> $values vorbelegte Werte
 * @param array<int,array<string,mixed>> $lists auswählbare Listen (leer = Standardliste)
 */
function nl_signup_form(array $values = [], array $lists = [], string $error = ''): string
{
    $formTime = time();
    $token    = Util::sign('signup:' . $formTime);
    ob_start();
    ?>
    <form method="post" action="subscribe.php" class="nl-form" novalidate>
        <?php if ($error !== ''): ?>
            <p class="nl-field-error" role="alert"><?= Util::e($error) ?></p>
        <?php endif; ?>

        <!-- Spam-Schutz: für Menschen unsichtbar -->
        <div class="nl-hp" aria-hidden="true">
            <label for="nl_website">Website (bitte frei lassen)</label>
            <input type="text" id="nl_website" name="website" tabindex="-1" autocomplete="off">
        </div>
        <input type="hidden" name="ts" value="<?= (int) $formTime ?>">
        <input type="hidden" name="tsig" value="<?= Util::e($token) ?>">

        <div class="nl-row">
            <div class="nl-field">
                <label for="nl_first">Vorname <span class="nl-optional">(optional)</span></label>
                <input type="text" id="nl_first" name="first_name" autocomplete="given-name"
                       value="<?= Util::e((string) ($values['first_name'] ?? '')) ?>" maxlength="120">
            </div>
            <div class="nl-field">
                <label for="nl_last">Nachname <span class="nl-optional">(optional)</span></label>
                <input type="text" id="nl_last" name="last_name" autocomplete="family-name"
                       value="<?= Util::e((string) ($values['last_name'] ?? '')) ?>" maxlength="120">
            </div>
        </div>

        <div class="nl-field">
            <label for="nl_email">E-Mail-Adresse <span class="nl-required">*</span></label>
            <input type="email" id="nl_email" name="email" required autocomplete="email"
                   value="<?= Util::e((string) ($values['email'] ?? '')) ?>" maxlength="190"
                   placeholder="name@unternehmen.de">
        </div>

        <div class="nl-field">
            <label for="nl_company">Unternehmen <span class="nl-optional">(optional)</span></label>
            <input type="text" id="nl_company" name="company" autocomplete="organization"
                   value="<?= Util::e((string) ($values['company'] ?? '')) ?>" maxlength="190">
        </div>

        <?php if (count($lists) > 1): ?>
            <fieldset class="nl-field nl-fieldset">
                <legend>Welche Themen interessieren Sie?</legend>
                <?php foreach ($lists as $list): ?>
                    <label class="nl-check">
                        <input type="checkbox" name="lists[]" value="<?= (int) $list['id'] ?>"
                            <?= (int) $list['is_default'] === 1 ? 'checked' : '' ?>>
                        <span><?= Util::e((string) $list['name']) ?>
                            <?php if (($list['description'] ?? '') !== ''): ?>
                                <em><?= Util::e((string) $list['description']) ?></em>
                            <?php endif; ?>
                        </span>
                    </label>
                <?php endforeach; ?>
            </fieldset>
        <?php endif; ?>

        <label class="nl-check nl-consent">
            <input type="checkbox" name="consent" value="1" required>
            <span>Ja, ich möchte den Newsletter per E-Mail erhalten. Die Einwilligung kann ich jederzeit
                widerrufen – ein Abmeldelink steht in jeder E-Mail. Hinweise zur Verarbeitung meiner Daten
                finde ich in der <a href="<?= Util::e(Settings::get('privacy_url')) ?>" target="_blank" rel="noopener">Datenschutzerklärung</a>.</span>
        </label>

        <button type="submit" class="nl-button">Newsletter abonnieren</button>
        <p class="nl-form-note">Wir verschicken die E-Mails über unseren eigenen Server. Ihre Adresse wird
            nicht an Dritte weitergegeben und ausschließlich für den Newsletter verwendet.</p>
    </form>
    <?php
    return (string) ob_get_clean();
}
