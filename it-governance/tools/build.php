<?php
/**
 * Baut die Website: aus den PHP-Quellen in src/ entstehen fertige HTML-Seiten
 * im Projektverzeichnis, dazu sitemap.xml.
 *
 * Warum dieser Umweg: Die Seite hat keinen dynamischen Inhalt – sie ist für
 * alle Besucher gleich. PHP dient hier nur dazu, Kopfzeile, Navigation,
 * Randspalte und Footer nicht vierzigmal kopieren zu müssen. Ausgeliefert wird
 * reines HTML. Das hat drei Vorteile: index.html lässt sich per Doppelklick
 * öffnen, die Seite läuft auf jedem Webspace auch ohne PHP, und sie ist schnell.
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

/* Seiten außerhalb des Navigationsbaums – alles Übrige holt sich der Ablauf
   aus $NAV, damit eine neue Unterseite nur an einer Stelle eingetragen wird. */
$pages = [
    'index.php',
    'vorgehen.php',
    'fuer-wen.php',
    'preise.php',
    'ueber-mich.php',
    'faq.php',
    'kontakt.php',
    'impressum.php',
    'datenschutz.php',
];

foreach (nav_flat() as $entry) {
    $path = ltrim($entry['url'], '/');
    // Aus "themen/" wird die Quelldatei "themen/index.php"
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
 * @param string $target Ziel wie "/themen/nis2.php" oder "/themen/"
 * @param int    $depth  Ordnertiefe der Seite, auf der der Verweis steht
 */
function to_relative(string $target, int $depth, array $keepDynamic): string
{
    $clean = ltrim($target, '/');

    /* Sprungmarken und Sonderprotokolle bleiben, wie sie sind. */
    if ($clean === '' ) {
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
        fwrite(STDERR, "FEHLER beim Bauen von src/$page:\n" . substr((string) $html, 0, 400) . "\n");
        $failed++;
        continue;
    }

    $depth  = substr_count($page, '/');
    $target = preg_replace('/\.php$/', '.html', $page);

    /* Nur href und src anfassen – Text, der zufällig wie ein Pfad aussieht,
       bleibt unberührt. Verweise, die mit # oder mailto: beginnen, ebenso. */
    $html = preg_replace_callback(
        '/(href|src)="(\/[^"#]*)"/',
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

/* ------------------------------------------------------------- Sitemap */

$base = rtrim($SITE['domain'], '/');
$prio = [
    'index.html'      => '1.0',
    'leistungen/index.html' => '0.9',
    'themen/index.html'     => '0.9',
    'preise.html'     => '0.9',
    'kontakt.html'    => '0.8',
    'vorgehen.html'   => '0.8',
    'ueber-mich.html' => '0.7',
    'fuer-wen.html'   => '0.7',
    'faq.html'        => '0.6',
    'impressum.html'  => '0.2',
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
    $xml .= '        <priority>' . ($prio[$target] ?? '0.8') . "</priority>\n";
    $xml .= "    </url>\n";
}
$xml .= "</urlset>\n";
file_put_contents($root . '/sitemap.xml', $xml);

/* ------------------------------------------------------------- Bericht */

printf("%d Seiten gebaut, sitemap.xml geschrieben.\n", count($built));
if ($failed) { printf("%d FEHLER!\n", $failed); }
printf("Zum Ansehen: index.html im Browser öffnen.\n");
exit($failed ? 1 : 0);
