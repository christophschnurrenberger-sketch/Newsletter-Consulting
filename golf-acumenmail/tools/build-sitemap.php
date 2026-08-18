<?php
/**
 * Erzeugt sitemap.xml aus dem Navigationsbaum in partials/config.php.
 *
 * Aufruf im Projektverzeichnis:  php tools/build-sitemap.php
 *
 * So kann keine Seite in der Sitemap fehlen, die im Menü steht – und keine
 * darin stehen, die es nicht mehr gibt.
 */

require __DIR__ . '/../partials/config.php';

$base = rtrim($SITE['domain'], '/');

/* Startseite und die Seiten, die nicht im Navigationsbaum hängen. */
$extra = [
    ['', '1.0', 'monthly'],
    ['preise.php', '0.9', 'monthly'],
    ['kontakt.php', '0.8', 'yearly'],
    ['ueber-uns.php', '0.6', 'yearly'],
    ['faq.php', '0.7', 'monthly'],
    ['impressum.php', '0.2', 'yearly'],
    ['datenschutz.php', '0.2', 'yearly'],
];

$seen = [];
$urls = [];

foreach ($extra as [$path, $prio, $freq]) {
    $seen[$path] = true;
    $urls[] = [$path, $prio, $freq];
}

foreach (nav_flat() as $entry) {
    $path = ltrim($entry['url'], '/');
    if (isset($seen[$path])) { continue; }
    $seen[$path] = true;
    // Übersichtsseiten wiegen etwas schwerer als einzelne Unterseiten.
    $prio = substr($path, -1) === '/' ? '0.9' : '0.8';
    $urls[] = [$path, $prio, 'monthly'];
}

$out  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$out .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as [$path, $prio, $freq]) {
    $out .= "    <url>\n";
    $out .= '        <loc>' . htmlspecialchars($base . url($path), ENT_XML1) . "</loc>\n";
    $out .= "        <changefreq>$freq</changefreq>\n";
    $out .= "        <priority>$prio</priority>\n";
    $out .= "    </url>\n";
}
$out .= "</urlset>\n";

file_put_contents(__DIR__ . '/../sitemap.xml', $out);
printf("sitemap.xml geschrieben: %d Seiten\n", count($urls));
