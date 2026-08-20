<?php
/**
 * Baut die persönliche Demo-Seite eines Golfclubs.
 *
 * Aufruf:  php -f src/club/render.php -- <kennung>
 *          wobei <kennung> der Dateiname unter src/club/daten/ ohne .php ist.
 *
 * Der Sinn dahinter: Statt einer allgemeinen Verkaufsseite bekommt jeder Club
 * eine eigene kleine Seite mit seinem Namen, seiner bisherigen Ausgabe und
 * einem konkreten Vorschlag. Das liest sich nicht wie „ich möchte Ihnen etwas
 * verkaufen“, sondern wie „jemand hat sich mit unserem Club beschäftigt“ –
 * und genau darin liegt der Unterschied.
 *
 * Jede Seite trägt oben und unten deutlich, von wem sie stammt. Sie soll ein
 * erkennbarer Vorschlag sein, keine Nachahmung der Clubwebsite.
 */

require_once __DIR__ . '/../partials/config.php';

$slug = $argv[1] ?? '';
$file = __DIR__ . '/daten/' . basename($slug) . '.php';
if ($slug === '' || !is_file($file)) {
    fwrite(STDERR, "Unbekannter Club: '$slug'\n");
    exit(1);
}

/** @var array $club  Daten des Clubs, siehe src/club/daten/_muster.php */
$club = require $file;

$canonical = rtrim($SITE['domain'], '/') . '/club/' . $slug . '/';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($club['meta']) ?>">
    <!-- Persönliche Seite für einen einzelnen Club: gehört nicht in den Index. -->
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#1B5E32">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <link rel="canonical" href="<?= e($canonical) ?>">

    <title><?= e($club['name']) ?> – Ihr Newsletter, einmal neu gedacht</title>

    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/favicon-32.png" sizes="32x32" type="image/png">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="stylesheet" href="/assets/club.css">
</head>
<body class="club-page">

<!-- Kopfzeile: wer schickt diese Seite ---------------------------------- -->
<header class="club-header">
    <div class="club-wrap club-header-inner">
        <a class="club-sender" href="/index.php" style="text-decoration:none;">
            <span class="club-sender-mark"><?php include __DIR__ . '/../partials/logo.php'; ?></span>
            <span class="club-sender-text">
                <span class="club-sender-name">AcumenMail Golf</span>
                <span class="club-sender-line">Newsletter für Golfclubs</span>
            </span>
        </a>
        <div class="club-header-meta">
            <span>Vorschlag vom <?= e($club['datum']) ?></span>
            <a class="club-btn" href="#gespraech">Beispiel besprechen</a>
        </div>
    </div>
</header>

<main>

<!-- Hero ----------------------------------------------------------------- -->
<section class="club-hero">
    <div class="club-wrap club-hero-inner">
        <div>
            <p class="club-for">
                <span class="club-ball" aria-hidden="true"></span>
                <span class="club-for-text">
                    <small>Erstellt für</small>
                    <strong><?= e($club['name']) ?></strong>
                </span>
            </p>

            <h1>Wir haben Ihren Newsletter<br>einmal neu gedacht.</h1>

            <p class="club-hero-lead"><?= $club['einleitung'] ?></p>

            <div class="club-hero-actions">
                <a class="club-btn" href="#gespraech">Beispiel besprechen</a>
                <a class="club-btn is-ghost" href="#vergleich">Zum Vergleich</a>
            </div>
        </div>

        <div class="club-facts">
<?php foreach ($club['fakten'] as $fakt): ?>
            <div class="club-fact">
                <strong><?= e($fakt[0]) ?></strong>
                <span><?= e($fakt[1]) ?></span>
            </div>
<?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Vorher und Nachher ---------------------------------------------------- -->
<section class="club-section" id="vergleich">
    <div class="club-wrap">
        <div class="club-section-head">
            <span class="club-kicker">Der Vergleich</span>
            <h2>Links Ihre letzte Ausgabe, rechts unser Vorschlag</h2>
            <p>Gleicher Inhalt, gleiche Nachricht – nur anders aufgebaut. Nichts davon
                ist erfunden: Der Vorschlag verwendet ausschließlich das, was in Ihrer
                Ausgabe ohnehin schon stand.</p>
        </div>

        <div class="club-compare">

            <div>
                <p class="club-col-label is-before">
                    <em aria-hidden="true">A</em>
                    <span>Bisher · <?= e($club['vorher']['datum']) ?></span>
                </p>

                <div class="club-mail is-before">
                    <div class="club-mail-bar">
                        Betreff: <b><?= e($club['vorher']['betreff']) ?></b>
                    </div>
                    <div class="club-mail-body">
                        <p class="club-old-head"><?= e($club['vorher']['anrede']) ?></p>
<?php foreach ($club['vorher']['absaetze'] as $absatz): ?>
                        <p><?= e($absatz) ?></p>
<?php endforeach; ?>
                    </div>
                </div>

<?php if (!empty($club['vorher']['hinweis'])): ?>
                <p class="club-note"><?= e($club['vorher']['hinweis']) ?></p>
<?php endif; ?>
            </div>

            <div>
                <p class="club-col-label is-after">
                    <em aria-hidden="true">B</em>
                    <span>Vorschlag</span>
                </p>

                <div class="club-mail is-after">
                    <div class="club-mail-bar">
                        Betreff: <b><?= e($club['nachher']['betreff']) ?></b>
                    </div>
                    <div class="club-mail-body">
                        <div class="club-mail-head">
                            <span class="club-mail-wordmark"><?= e($club['kurzname']) ?></span>
                            <span class="club-mail-claim"><?= e($club['nachher']['rubrik']) ?></span>
                        </div>

                        <span class="club-new-kicker"><?= e($club['nachher']['kicker']) ?></span>
                        <h3><?= e($club['nachher']['ueberschrift']) ?></h3>

                        <p>Liebe(r) <span class="ph">{{vorname}}</span>, <?= e($club['nachher']['aufhaenger']) ?></p>

                        <?php include __DIR__ . '/../partials/golf-scene.php'; ?>

<?php foreach ($club['nachher']['absaetze'] as $absatz): ?>
                        <p><?= $absatz ?></p>
<?php endforeach; ?>

                        <span class="club-mail-cta"><?= e($club['nachher']['knopf']) ?></span>

                        <p class="club-mail-foot"><?= e($club['name']) ?> · Impressum · Abmelden</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Die drei Verbesserungen ------------------------------------------------ -->
<section class="club-section is-soft">
    <div class="club-wrap">
        <div class="club-section-head">
            <span class="club-kicker">Was sich geändert hat</span>
            <h2>Drei konkrete Verbesserungen</h2>
            <p>Keine Geschmacksfragen, sondern drei Dinge, die messbar etwas bewirken.</p>
        </div>

        <div class="club-points">
<?php foreach ($club['verbesserungen'] as $i => $punkt): ?>
            <article class="club-point">
                <span class="club-point-no"><?= sprintf('%02d', $i + 1) ?></span>
                <h3><?= e($punkt['titel']) ?></h3>
                <p><?= $punkt['text'] ?></p>
                <p class="club-point-effect">
                    <span class="club-ball is-sm" aria-hidden="true"></span>
                    <span><?= e($punkt['wirkung']) ?></span>
                </p>
            </article>
<?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Was danach möglich wäre ------------------------------------------------ -->
<section class="club-section">
    <div class="club-wrap">
        <div class="club-section-head" style="margin-bottom:1.5rem;">
            <span class="club-kicker">Und dann?</span>
            <h2>Das war eine Ausgabe. Interessanter wird es darüber hinaus.</h2>
            <p><?= $club['ausblick'] ?></p>
        </div>

        <ul class="club-list">
<?php foreach ($club['moeglichkeiten'] as $punkt): ?>
            <li>
                <span class="club-ball is-sm" aria-hidden="true"></span>
                <span><?= $punkt ?></span>
            </li>
<?php endforeach; ?>
        </ul>
    </div>
</section>

<!-- Abschluss -------------------------------------------------------------- -->
<section class="club-close" id="gespraech">
    <div class="club-wrap club-close-inner">
        <div>
            <h2>Beispiel besprechen?</h2>
            <p><?= $club['abschluss'] ?></p>

            <div class="club-close-contact">
                <?= e($SITE['owner']) ?><br>
                <a href="tel:<?= e($SITE['phone_link']) ?>"><?= e($SITE['phone']) ?></a><br>
                <a href="mailto:<?= e($SITE['email']) ?>?subject=<?= rawurlencode('Newsletter-Beispiel ' . $club['name']) ?>"><?= e($SITE['email']) ?></a>
            </div>
        </div>

        <div class="club-close-actions">
            <a class="club-btn" href="mailto:<?= e($SITE['email']) ?>?subject=<?= rawurlencode('Newsletter-Beispiel ' . $club['name']) ?>">Beispiel besprechen</a>
            <a class="club-btn is-ghost" href="/index.php">Mehr über uns</a>
        </div>
    </div>
</section>

</main>

<footer class="club-footer">
    <div class="club-wrap club-footer-inner">
        <p>Diese Seite wurde für <?= e($club['name']) ?> erstellt und ist nicht öffentlich verlinkt.</p>
        <p>
            <a href="/impressum.php">Impressum</a> ·
            <a href="/datenschutz.php">Datenschutz</a> ·
            <a href="/index.php">AcumenMail Golf</a>
        </p>
    </div>
</footer>

</body>
</html>
