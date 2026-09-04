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

/**
 * Ein Icon je Navigationspunkt – schlanke Strichzeichnungen als Inline-SVG,
 * ganz ohne Fremdpaket. currentColor lässt sie die Farbe des Eintrags erben.
 */
function admin_nav_icon(string $file): string
{
    $p = [
        'index.php'        => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/>',
        'wochennews.php'   => '<rect x="3" y="4.5" width="18" height="16" rx="2"/><path d="M3 9h18M8 2.5v4M16 2.5v4"/>',
        'kampagnen.php'    => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3.5 6.5 8.5 6 8.5-6"/>',
        'automationen.php' => '<path d="M13 2 4.5 13H11l-1 9 8.5-11H12l1-9Z"/>',
        'turniere.php'     => '<path d="M6 21V3l11 2.5L6 8"/><path d="M6 21h5"/>',
        'meldungen.php'    => '<path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>',
        'versand.php'      => '<path d="M21 3 10.5 13.5M21 3l-6.5 18-4-8-8-4L21 3Z"/>',
        'empfaenger.php'   => '<circle cx="9" cy="8" r="3.2"/><path d="M3.5 20a5.5 5.5 0 0 1 11 0"/><path d="M16 6.2a3 3 0 0 1 0 5.6M20.5 20a5 5 0 0 0-4-4.9"/>',
        'listen.php'       => '<path d="M8 6h13M8 12h13M8 18h13"/><path d="M3.5 6h.01M3.5 12h.01M3.5 18h.01"/>',
        'marken.php'       => '<path d="M12 3a9 9 0 1 0 0 18c1.5 0 2-1 2-2s-.8-1.5-.8-2.5S14 13 15.5 13H18a3 3 0 0 0 3-3 7 7 0 0 0-9-7Z"/><circle cx="7.5" cy="11" r="1"/><circle cx="12" cy="7.5" r="1"/><circle cx="16.5" cy="11" r="1"/>',
        'vorlagen.php'     => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>',
        'systemmails.php'  => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3.5 6.5 8.5 6 8.5-6"/><path d="m15 15 2 2 4-4"/>',
        'protokoll.php'    => '<path d="M3 12h4l2.5 7 5-16L17 12h4"/>',
        'einstellungen.php'=> '<circle cx="12" cy="12" r="3.2"/><path d="M19.4 15a1.6 1.6 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.6 1.6 0 0 0-2.7 1.1V21a2 2 0 0 1-4 0v-.1A1.6 1.6 0 0 0 7 19.4a1.6 1.6 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.6 1.6 0 0 0-1.1-2.7H1a2 2 0 0 1 0-4h.1A1.6 1.6 0 0 0 4.6 7a1.6 1.6 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1A1.6 1.6 0 0 0 9 2.6h.1A2 2 0 0 1 13 2.6a1.6 1.6 0 0 0 2.7-1.1h.1a2 2 0 0 1 2.8 2.8l-.1.1A1.6 1.6 0 0 0 21.4 9v.1a2 2 0 0 1 0 4Z"/>',
        'instanzen.php'    => '<rect x="3" y="3" width="7.5" height="7.5" rx="1.5"/><rect x="13.5" y="3" width="7.5" height="7.5" rx="1.5"/><rect x="3" y="13.5" width="7.5" height="7.5" rx="1.5"/><rect x="13.5" y="13.5" width="7.5" height="7.5" rx="1.5"/>',
        'api.php'          => '<path d="m8 6-6 6 6 6"/><path d="m16 6 6 6-6 6"/>',
        'hilfe.php'        => '<circle cx="12" cy="12" r="9"/><path d="M9.2 9.3a2.8 2.8 0 0 1 5.5.8c0 1.9-2.8 2.4-2.8 2.4"/><path d="M12 17h.01"/>',
        'benutzer.php'     => '<circle cx="12" cy="8" r="3.4"/><path d="M5.5 20a6.5 6.5 0 0 1 13 0"/>',
    ];
    $d = $p[$file] ?? '<circle cx="12" cy="12" r="9"/>';
    return '<svg class="ad-nav-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" '
        . 'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
}

/** Navigationspunkt ausgeben. */
function admin_nav(string $file, string $label, string $badge = ''): string
{
    $current = basename((string) ($_SERVER['SCRIPT_NAME'] ?? '')) === $file;
    // title = Name als Tooltip, damit die Punkte auch in der schmalen
    // (eingeklappten) Leiste ohne Beschriftung erkennbar bleiben.
    return '<a href="' . Util::e($file) . '" class="ad-nav-link' . ($current ? ' is-current' : '') . '"'
        . ' title="' . Util::e($label) . '"'
        . ($current ? ' aria-current="page"' : '') . '>'
        . admin_nav_icon($file)
        . '<span class="ad-nav-text">' . Util::e($label) . '</span>'
        . ($badge !== '' ? '<span class="ad-badge">' . Util::e($badge) . '</span>' : '')
        . '</a>';
}

/**
 * Ein freundlicher Leerzustand: Icon, kurze Überschrift, ein Satz und
 * optional eine Handlung. So steht auf leeren Seiten nicht bloß „nichts da“,
 * sondern was als Nächstes zu tun ist.
 *
 * @param string $icon  Schlüssel aus der kleinen Icon-Auswahl unten
 * @param string $title Überschrift (wird maskiert)
 * @param string $text  ein erklärender Satz (wird maskiert)
 * @param string $tat   optionaler Handlungs-Knopf als fertiges HTML (vertraut)
 */
function admin_empty(string $icon, string $title, string $text, string $tat = ''): string
{
    $icons = [
        'mail'   => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3.5 6.5 8.5 6 8.5-6"/>',
        'users'  => '<circle cx="9" cy="8" r="3.2"/><path d="M3.5 20a5.5 5.5 0 0 1 11 0"/><path d="M16 6.2a3 3 0 0 1 0 5.6M20.5 20a5 5 0 0 0-4-4.9"/>',
        'list'   => '<path d="M8 6h13M8 12h13M8 18h13"/><path d="M3.5 6h.01M3.5 12h.01M3.5 18h.01"/>',
        'layout' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
    ];
    $d = $icons[$icon] ?? $icons['mail'];
    return '<div class="ad-empty">'
        . '<div class="ad-empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" '
        . 'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg></div>'
        . '<h3>' . Util::e($title) . '</h3>'
        . '<p>' . Util::e($text) . '</p>'
        . ($tat !== '' ? '<div class="ad-empty-tat">' . $tat . '</div>' : '')
        . '</div>';
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
<body data-hilfe-seite="<?= Util::e(Hilfe::forPage($currentFile)) ?>">
<?php /* Zustand der Navigation sofort setzen, damit sie nicht kurz aufklappt. */ ?>
<script>try{document.body.classList.add(localStorage.getItem('ad-nav')==='fest'?'nav-fest':'nav-schmal');}catch(e){document.body.classList.add('nav-schmal');}</script>
<header class="ad-topbar">
    <button type="button" class="ad-nav-toggle" aria-label="Menü öffnen" aria-expanded="false" aria-controls="ad-hauptnav">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>
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

<div class="ad-nav-overlay"></div>
<div class="ad-layout">
    <?php /*
     * Die Navigation ist gruppiert: elf Einträge in einer Reihe liest
     * niemand. „Systemmails“ hatte vorher gar keinen Platz und war nur
     * über Umwege erreichbar. Am Handy fährt dieselbe Navigation als
     * Schublade über den Menü-Knopf herein.
     */ ?>
    <nav class="ad-nav" id="ad-hauptnav" aria-label="Hauptnavigation">
        <?php /* Nadel: hält die Navigation dauerhaft offen. Am Handy verborgen –
                 dort öffnet der Menü-Knopf die Schublade. */ ?>
        <button type="button" class="ad-nav-link ad-nav-pin" aria-pressed="false" title="Menü angeheftet halten">
            <svg class="ad-nav-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 17v5"/><path d="M9 10.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24V16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V7a1 1 0 0 1 1-1 2 2 0 0 0 0-4H8a2 2 0 0 0 0 4 1 1 0 0 1 1 1z"/></svg>
            <span class="ad-nav-text">Anheften</span>
        </button>
        <?= admin_nav('index.php', 'Übersicht') ?>
        <?= admin_nav('hilfe.php', 'Hilfe') ?>

        <span class="ad-nav-gruppe">Versenden</span>
        <?= admin_nav('wochennews.php', 'Wochennews') ?>
        <?= admin_nav('kampagnen.php', 'Newsletter') ?>
        <?= admin_nav('automationen.php', 'Automationen') ?>
        <?= admin_nav('turniere.php', 'Turniere') ?>
        <?= admin_nav('meldungen.php', 'Meldungen') ?>
        <?= admin_nav('versand.php', 'Versand', $pendingMail > 0 ? (string) $pendingMail : '') ?>

        <span class="ad-nav-gruppe">Empfänger</span>
        <?= admin_nav('empfaenger.php', 'Empfänger') ?>
        <?= admin_nav('listen.php', 'Listen') ?>

        <span class="ad-nav-gruppe">Gestaltung</span>
        <?= admin_nav('marken.php', 'Marken') ?>
        <?= admin_nav('vorlagen.php', 'Vorlagen') ?>
        <?= admin_nav('systemmails.php', 'Systemmails') ?>

        <span class="ad-nav-gruppe">System</span>
        <?php if (Auth::can('einstellungen')): ?>
            <?= admin_nav('protokoll.php', 'Protokoll') ?>
            <?= admin_nav('einstellungen.php', 'Einstellungen') ?>
            <?= admin_nav('api.php', 'Schnittstelle') ?>
            <?php /* Steht immer da, auch bei einer einzelnen Installation:
                     Wer die Übersicht sucht, sucht sie in der Navigation –
                     nicht in den Einstellungen. Ohne weitere Einträge zeigt
                     die Seite die eigene Installation und das Formular. */ ?>
            <?= admin_nav('instanzen.php', 'Instanzen') ?>
        <?php endif; ?>
        <?= admin_nav('benutzer.php', Auth::can('benutzer') ? 'Benutzer' : 'Mein Zugang') ?>
    </nav>

    <main class="ad-main">
        <?php foreach (Util::takeFlash() as $flash): ?>
            <div class="ad-flash ad-flash-<?= Util::e($flash['type']) ?>"><?= $flash['message'] ?></div>
        <?php endforeach; ?>
