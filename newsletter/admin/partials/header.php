<?php
/**
 * Kopf aller Admin-Seiten: Anmeldung erzwingen, Navigation, Meldungen.
 * Vor dem Einbinden $pageTitle setzen.
 */

require_once dirname(__DIR__, 2) . '/lib/bootstrap.php';

// Jede Seite legt vor dem Einbinden fest, welches Recht sie braucht.
$currentUser = Auth::require($requiredRight ?? 'lesen');
$pageTitle   = $pageTitle ?? 'Übersicht';
$currentFile = basename((string) ($_SERVER['SCRIPT_NAME'] ?? ''));
$pendingMail = Queue::pendingCount();

/** Navigationspunkt ausgeben. */
function admin_nav(string $file, string $label, string $badge = ''): string
{
    $current = basename((string) ($_SERVER['SCRIPT_NAME'] ?? '')) === $file;
    return '<a href="' . Util::e($file) . '" class="ad-nav-link' . ($current ? ' is-current' : '') . '">'
        . Util::e($label)
        . ($badge !== '' ? '<span class="ad-badge">' . Util::e($badge) . '</span>' : '')
        . '</a>';
}

/** Status einer Kampagne als farbige Pille. */
function campaign_status_pill(string $status): string
{
    $label = Campaigns::statusLabels()[$status] ?? $status;
    $class = match ($status) {
        Campaigns::SENT      => 'ad-pill-green',
        Campaigns::SENDING   => 'ad-pill-blue',
        Campaigns::SCHEDULED => 'ad-pill-amber',
        Campaigns::PAUSED    => 'ad-pill-amber',
        Campaigns::CANCELLED => 'ad-pill-red',
        default              => 'ad-pill-grey',
    };
    return '<span class="ad-pill ' . $class . '">' . Util::e($label) . '</span>';
}

/** Status eines Empfängers als farbige Pille. */
function subscriber_status_pill(string $status): string
{
    $label = Subscribers::statusLabels()[$status] ?? $status;
    $class = match ($status) {
        Subscribers::STATUS_ACTIVE       => 'ad-pill-green',
        Subscribers::STATUS_PENDING      => 'ad-pill-amber',
        Subscribers::STATUS_UNSUBSCRIBED => 'ad-pill-grey',
        Subscribers::STATUS_BOUNCED,
        Subscribers::STATUS_COMPLAINED   => 'ad-pill-red',
        default                          => 'ad-pill-grey',
    };
    return '<span class="ad-pill ' . $class . '">' . Util::e($label) . '</span>';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= Util::e($pageTitle) ?> · Newsletter-Verwaltung</title>
<link rel="stylesheet" href="<?= Util::e(Util::asset('assets/admin.css', __DIR__ . '/..')) ?>">
<?php foreach (($extraCss ?? []) as $css): ?>
<link rel="stylesheet" href="<?= Util::e(Util::asset($css, __DIR__ . '/..')) ?>">
<?php endforeach; ?>
</head>
<body>
<header class="ad-topbar">
    <a class="ad-brand" href="index.php">
        <span class="ad-brand-mark" aria-hidden="true">A</span>
        <span><?= Util::e(Settings::get('brand_name')) ?> <em>Newsletter-Verwaltung</em></span>
    </a>
    <div class="ad-topbar-right">
        <a class="ad-topbar-link" href="../anmelden.php" target="_blank" rel="noopener">Anmeldeseite ansehen</a>
        <span class="ad-user"><?= Util::e((string) ($currentUser['name'] ?: $currentUser['email'])) ?>
            · <?= Util::e(Auth::roleLabel((string) $currentUser['role'])) ?></span>
        <a class="ad-topbar-link" href="logout.php">Abmelden</a>
    </div>
</header>

<div class="ad-layout">
    <nav class="ad-nav" aria-label="Hauptnavigation">
        <?= admin_nav('index.php', 'Übersicht') ?>
        <?= admin_nav('kampagnen.php', 'Newsletter') ?>
        <?= admin_nav('empfaenger.php', 'Empfänger') ?>
        <?= admin_nav('listen.php', 'Listen') ?>
        <?= admin_nav('automationen.php', 'Automationen') ?>
        <?= admin_nav('marken.php', 'Marken') ?>
        <?= admin_nav('vorlagen.php', 'Vorlagen') ?>
        <?= admin_nav('versand.php', 'Versand', $pendingMail > 0 ? (string) $pendingMail : '') ?>
        <?php if (Auth::can('einstellungen')): ?>
            <?= admin_nav('protokoll.php', 'Protokoll') ?>
            <?= admin_nav('einstellungen.php', 'Einstellungen') ?>
        <?php endif; ?>
        <?= admin_nav('benutzer.php', Auth::can('benutzer') ? 'Benutzer' : 'Mein Zugang') ?>
    </nav>

    <main class="ad-main">
        <?php foreach (Util::takeFlash() as $flash): ?>
            <div class="ad-flash ad-flash-<?= Util::e($flash['type']) ?>"><?= $flash['message'] ?></div>
        <?php endforeach; ?>
