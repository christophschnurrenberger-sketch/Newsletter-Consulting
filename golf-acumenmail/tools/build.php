<?php
/**
 * Baut die Website: aus den PHP-Quellen in src/ entstehen fertige HTML-Seiten
 * im Projektverzeichnis, dazu sitemap.xml.
 *
 * Warum dieser Umweg: Die Seite hat keinen dynamischen Inhalt – sie ist überall
 * für alle gleich. PHP dient hier nur dazu, Kopfzeile, Navigation, Randspalte
 * und Footer nicht 28-mal kopieren zu müssen. Ausgeliefert wird reines HTML.
 * Das hat drei Vorteile: Man kann index.html per Doppelklick öffnen, die Seite
 * läuft auf jedem Webspace auch ohne PHP, und sie ist schneller.
 *
 * Einzige Ausnahme ist kontakt-senden.php – das Kontaktformular braucht einen
 * Server. Der Verweis darauf bleibt deshalb unverändert.
 *
 * Aufruf im Projektverzeichnis:
 *
 *     php tools/build.php
 *
 * Bearbeitet wird immer src/, nie die erzeugten .html-Dateien: Sie werden beim
 * nächsten Lauf überschrieben.
 */

require __DIR__ . '/../src/partials/config.php';

$root = dirname(__DIR__);
$src  = $root . '/src';

/* Verweise, die auch in der fertigen Seite auf PHP zeigen müssen. */
$keepDynamic = ['kontakt-senden.php'];

/* ------------------------------------------------------- Seiten einsammeln */

$pages = ['index.php', 'preise.php', 'kontakt.php', 'ueber-uns.php', 'faq.php',
          'impressum.php', 'datenschutz.php'];

foreach (nav_flat() as $entry) {
    $path = ltrim($entry['url'], '/');
    // Aus "software/" wird die Quelldatei "software/index.php"
    $file = substr($path, -1) === '/' ? $path . 'index.php' : $path;
    if (!in_array($file, $pages, true) && is_file($src . '/' . $file)) {
        $pages[] = $file;
    }
}
$pages = array_values(array_unique($pages));

/* ------------------------------------------------------- Verweise umbauen */

/**
 * Rechnet einen wurzelbezogenen Verweis in einen relativen um und tauscht
 * dabei .php gegen .html. Relativ deshalb, damit die Seite sowohl lokal per
 * Doppelklick als auch in einem beliebigen Unterordner eines Webspace läuft.
 *
 * @param string $target Ziel wie "/software/automationen.php" oder "/software/"
 * @param int    $depth  Ordnertiefe der Seite, auf der der Verweis steht
 */
function to_relative(string $target, int $depth, array $keepDynamic): string
{
    $clean = ltrim($target, '/');

    if ($clean === '') {
        $clean = 'index.html';
    } elseif (substr($clean, -1) === '/') {
        $clean .= 'index.html';
    } elseif (substr($clean, -4) === '.php' && !in_array($clean, $keepDynamic, true)) {
        $clean = substr($clean, 0, -4) . '.html';
    }

    return str_repeat('../', $depth) . $clean;
}

/* ----------------------------------------------------------------- Bauen */

$php    = PHP_BINARY;
$built  = [];
$failed = 0;

foreach ($pages as $page) {
    $source = $src . '/' . $page;
    if (!is_file($source)) {
        fwrite(STDERR, "FEHLT: src/$page\n");
        $failed++;
        continue;
    }

    /* Jede Seite in einem eigenen Prozess rendern. So kann sich kein Zustand
       zwischen zwei Seiten übertragen – etwa ein $asideCta, das eine Seite
       setzt und die nächste nicht. */
    $html = shell_exec(escapeshellarg($php) . ' -f ' . escapeshellarg($source) . ' 2>&1');
    if ($html === null || strncmp($html, '<!DOCTYPE html>', 15) !== 0) {
        fwrite(STDERR, "FEHLER beim Bauen von src/$page:\n" . substr((string) $html, 0, 300) . "\n");
        $failed++;
        continue;
    }

    $depth  = substr_count($page, '/');
    $target = preg_replace('/\.php$/', '.html', $page);

    /* Nur href und src anfassen – Text, der zufällig wie ein Pfad aussieht,
       bleibt unberührt. */
    $html = preg_replace_callback(
        '/(href|src)="(\/[^"]*)"/',
        fn($m) => $m[1] . '="' . to_relative($m[2], $depth, $keepDynamic) . '"',
        $html
    );

    $html = str_replace(
        '<head>',
        "<head>\n    <!-- Erzeugt von tools/build.php aus src/$page – nicht hier ändern. -->",
        $html
    );

    $dest = $root . '/' . $target;
    if (!is_dir(dirname($dest))) { mkdir(dirname($dest), 0775, true); }
    file_put_contents($dest, $html);
    $built[] = $target;
}

/* ------------------------------------------------- Persoenliche Clubseiten

   Je Datei unter src/club/daten/ entsteht eine eigene kleine Seite unter
   club/<kennung>/index.html. Sie taucht bewusst nicht in der Sitemap auf:
   Diese Seiten sind fuer genau einen Empfaenger gedacht, nicht fuer Google.
   ------------------------------------------------------------------------ */

$clubDir = $src . '/club/daten';
$clubs   = [];

foreach (glob($clubDir . '/*.php') as $datei) {
    $kennung = basename($datei, '.php');
    if ($kennung[0] === '_') { continue; }   // Dateien mit _ sind Vorlagen

    $html = shell_exec(
        escapeshellarg($php) . ' -f ' . escapeshellarg($src . '/club/render.php')
        . ' -- ' . escapeshellarg($kennung) . ' 2>&1'
    );
    if ($html === null || strncmp($html, '<!DOCTYPE html>', 15) !== 0) {
        fwrite(STDERR, "FEHLER bei Clubseite $kennung:\n" . substr((string) $html, 0, 300) . "\n");
        $failed++;
        continue;
    }

    /* Diese Seiten liegen zwei Ebenen tief: club/<kennung>/index.html */
    $html = preg_replace_callback(
        '/(href|src)="(\/[^"]*)"/',
        fn($m) => $m[1] . '="' . to_relative($m[2], 2, $keepDynamic) . '"',
        $html
    );

    $dest = $root . '/club/' . $kennung . '/index.html';
    if (!is_dir(dirname($dest))) { mkdir(dirname($dest), 0775, true); }
    file_put_contents($dest, $html);
    $clubs[] = $kennung;
}

/* ------------------------------------------------------------- Sitemap */

$base = rtrim($SITE['domain'], '/');
$prio = [
    'index.html'  => '1.0',
    'preise.html' => '0.9',
    'faq.html'    => '0.7',
    'kontakt.html' => '0.8',
    'ueber-uns.html' => '0.6',
    'impressum.html' => '0.2',
    'datenschutz.html' => '0.2',
];

$xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($built as $target) {
    // Die Startseite einer Ebene wird als Ordner verlinkt, nicht als index.html
    $loc = preg_replace('/(^|\/)index\.html$/', '$1', $target);
    $xml .= "    <url>\n";
    $xml .= '        <loc>' . htmlspecialchars($base . '/' . $loc, ENT_XML1) . "</loc>\n";
    $xml .= "        <changefreq>monthly</changefreq>\n";
    $xml .= '        <priority>' . ($prio[$target] ?? (str_ends_with($target, 'index.html') ? '0.9' : '0.8')) . "</priority>\n";
    $xml .= "    </url>\n";
}
$xml .= "</urlset>\n";
file_put_contents($root . '/sitemap.xml', $xml);

printf("%d Seiten gebaut, sitemap.xml geschrieben.\n", count($built));
if ($clubs) {
    printf("%d persönliche Clubseiten: %s\n", count($clubs),
           implode(', ', array_map(fn($k) => "club/$k/", $clubs)));
}
if ($failed) { printf("%d FEHLER!\n", $failed); }
printf("Zum Ansehen: index.html im Browser öffnen.\n");
exit($failed ? 1 : 0);
