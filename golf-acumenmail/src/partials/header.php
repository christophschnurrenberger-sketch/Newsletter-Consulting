<?php
/**
 * Kopf jeder Seite: <head>, Servicezeile, Hauptnavigation mit Mega-Menü und –
 * sofern die Seite einen Seitenkopf setzt – Brotkrumen und Seiten-Hero.
 *
 * Erwartet ein Array $page, das die aufrufende Seite vorher gesetzt hat:
 *
 *   title       Titel für <title> und Suchmaschinen (Pflicht)
 *   description Beschreibung für Suchmaschinen (Pflicht)
 *   section     Schlüssel aus $NAV – markiert den aktiven Menüpunkt
 *   path        Pfad der Seite relativ zur Basis, für die kanonische URL
 *   css         Zusätzliche Stylesheets, etwa ['club']
 *   crumbs      [[Beschriftung, Pfad|null], …] – ohne „Startseite“
 *   hero        ['kicker' =>, 'h1' =>, 'lead' =>, 'actions' => [[Text, Pfad, 'primary'|'ghost'], …]]
 *               false unterdrückt den Standardkopf (für die Startseite)
 */

require_once __DIR__ . '/config.php';

$page = array_merge([
    'title'       => $SITE['name'],
    'description' => '',
    'section'     => '',
    'path'        => '',
    'css'         => [],
    'crumbs'      => [],
    'hero'        => false,
    'body_class'  => '',
], $page ?? []);

$canonical = rtrim($SITE['domain'], '/') . url($page['path']);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($page['description']) ?>">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
    <meta name="author" content="<?= e($SITE['name']) ?>">
    <meta name="theme-color" content="#143D28">
    <meta name="referrer" content="strict-origin-when-cross-origin">

    <link rel="canonical" href="<?= e($canonical) ?>">

    <meta property="og:locale" content="de_DE">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= e($SITE['name']) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:title" content="<?= e($page['title']) ?>">
    <meta property="og:description" content="<?= e($page['description']) ?>">
    <meta name="twitter:card" content="summary_large_image">

    <title><?= e($page['title']) ?> | <?= e($SITE['name']) ?></title>

    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><circle cx='16' cy='16' r='15' fill='%231E6B45'/><text x='16' y='22' font-family='Georgia,serif' font-size='17' font-weight='700' fill='white' text-anchor='middle'>A</text></svg>">

    <link rel="stylesheet" href="<?= e(url('assets/site.css')) ?>">
<?php foreach ($page['css'] as $sheet): ?>
    <link rel="stylesheet" href="<?= e(url('assets/' . $sheet . '.css')) ?>">
<?php endforeach; ?>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "ProfessionalService",
      "name": <?= json_encode($SITE['name']) ?>,
      "url": <?= json_encode(rtrim($SITE['domain'], '/') . url('')) ?>,
      "telephone": <?= json_encode($SITE['phone']) ?>,
      "areaServed": { "@type": "Country", "name": "Deutschland" },
      "serviceType": "Newsletter-Marketing für Golfclubs"
    }
    </script>
</head>
<body class="<?= e($page['body_class']) ?>">

<a href="#main-content" class="skip-link">Zum Inhalt springen</a>

<!-- Servicezeile ------------------------------------------------------- -->
<div class="utility-bar">
    <div class="container utility-inner">
        <p class="utility-claim">Newsletter-Marketing für Golfclubs, Golfanlagen und Golfschulen</p>
        <div class="utility-links">
            <a href="tel:<?= e($SITE['phone_link']) ?>">
                <i data-icon="phone" class="lucide"></i><?= e($SITE['phone']) ?>
            </a>
            <a href="<?= e(url('faq.php')) ?>">Häufige Fragen</a>
            <a href="<?= e(url('newsletter/admin/login.php')) ?>" class="utility-login">
                <i data-icon="lock" class="lucide"></i>Zum Newsletter-Tool
            </a>
        </div>
    </div>
</div>

<!-- Hauptnavigation ---------------------------------------------------- -->
<header id="header" class="site-header">
    <div class="container header-inner">

        <a href="<?= e(url('')) ?>" class="site-brand" aria-label="<?= e($SITE['name']) ?> – Startseite">
            <span class="site-brand-mark" aria-hidden="true">A</span>
            <span class="site-brand-text">
                <span class="site-brand-name">AcumenMail <span class="brand-accent">Golf</span></span>
                <span class="site-brand-line"><?= e($SITE['claim']) ?></span>
            </span>
        </a>

        <nav id="main-nav" class="main-nav" aria-label="Hauptnavigation">
            <ul class="nav-list">
<?php foreach ($NAV as $key => $item):
    $active   = $page['section'] === $key;
    $hasMega  = !empty($item['children']);
    $megaId   = 'mega-' . $key;
?>
                <li class="nav-item<?= $hasMega ? ' has-mega' : '' ?><?= $active ? ' is-active' : '' ?>">
<?php if ($hasMega): ?>
                    <button type="button" class="nav-link nav-toggle" aria-expanded="false" aria-controls="<?= $megaId ?>">
                        <?= e($item['label']) ?><i data-icon="chevron-down" class="lucide nav-caret"></i>
                    </button>

                    <div class="mega" id="<?= $megaId ?>" hidden>
                        <div class="container mega-inner">
                            <div class="mega-intro">
                                <p class="mega-intro-title"><?= e($item['intro']['title']) ?></p>
                                <p class="mega-intro-text"><?= e($item['intro']['text']) ?></p>
                                <a href="<?= e(url($item['intro']['link'][1])) ?>" class="mega-intro-link">
                                    <?= e($item['intro']['link'][0]) ?><i data-icon="arrow-right" class="lucide"></i>
                                </a>
                            </div>

                            <ul class="mega-list">
<?php foreach ($item['children'] as $child): ?>
                                <li>
                                    <a href="<?= e(url($child['url'])) ?>" class="mega-link<?= !empty($child['feature']) ? ' is-feature' : '' ?>">
                                        <span class="mega-icon"><i data-icon="<?= e($child['icon']) ?>" class="lucide"></i></span>
                                        <span class="mega-text">
                                            <strong><?= e($child['label']) ?></strong>
                                            <small><?= e($child['desc']) ?></small>
                                        </span>
                                    </a>
                                </li>
<?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
<?php else: ?>
                    <a href="<?= e(url($item['url'])) ?>" class="nav-link"><?= e($item['label']) ?></a>
<?php endif; ?>
                </li>
<?php endforeach; ?>

                <!-- Auf dem Handy gehört der Hauptknopf mit ins Menü -->
                <li class="nav-item nav-item-cta">
                    <a href="<?= e(url('kontakt.php')) ?>" class="btn-primary-custom">Demo anfragen</a>
                </li>
            </ul>
        </nav>

        <div class="header-actions">
            <a href="<?= e(url('kontakt.php')) ?>" class="btn-primary-custom header-cta">Demo anfragen</a>
        </div>

        <button id="mobile-menu-button" class="mobile-menu-toggle" type="button"
                aria-label="Menü öffnen" aria-controls="main-nav" aria-expanded="false">
            <i data-icon="menu" class="lucide"></i>
        </button>
    </div>
</header>

<div class="mega-backdrop" hidden></div>

<main id="main-content">
<?php if ($page['hero'] !== false):
    $hero = $page['hero'];
?>
<!-- Seitenkopf --------------------------------------------------------- -->
<section class="page-hero">
    <div class="container">
<?php if ($page['crumbs']): ?>
        <nav class="breadcrumb" aria-label="Sie sind hier">
            <ol>
                <li><a href="<?= e(url('')) ?>">Start</a></li>
<?php foreach ($page['crumbs'] as $crumb): ?>
<?php if ($crumb[1] !== null): ?>
                <li><a href="<?= e(url($crumb[1])) ?>"><?= e($crumb[0]) ?></a></li>
<?php else: ?>
                <li aria-current="page"><?= e($crumb[0]) ?></li>
<?php endif; ?>
<?php endforeach; ?>
            </ol>
        </nav>
<?php endif; ?>

        <div class="page-hero-inner">
            <div class="page-hero-copy">
<?php if (!empty($hero['kicker'])): ?>
                <span class="section-kicker"><?= e($hero['kicker']) ?></span>
<?php endif; ?>
                <h1 class="page-hero-title"><?= $hero['h1'] ?></h1>
<?php if (!empty($hero['lead'])): ?>
                <p class="page-hero-lead"><?= $hero['lead'] ?></p>
<?php endif; ?>
<?php if (!empty($hero['actions'])): ?>
                <div class="page-hero-actions">
<?php foreach ($hero['actions'] as $action): ?>
                    <a href="<?= e(url($action[1])) ?>" class="<?= $action[2] === 'primary' ? 'btn-primary-custom' : 'btn-secondary' ?>"><?= e($action[0]) ?></a>
<?php endforeach; ?>
                </div>
<?php endif; ?>
            </div>
<?php if (!empty($hero['facts'])): ?>
            <aside class="page-hero-facts" aria-label="Auf einen Blick">
<?php foreach ($hero['facts'] as $fact): ?>
                <div class="page-fact"><strong><?= e($fact[0]) ?></strong><span><?= e($fact[1]) ?></span></div>
<?php endforeach; ?>
            </aside>
<?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>
