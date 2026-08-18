<?php
/**
 * track.php – Zählpixel und Klickzähler.
 *
 *   track.php?o=TOKEN          → 1x1-Pixel, zählt eine Öffnung
 *   track.php?c=TOKEN&l=LINKID → Weiterleitung, zählt einen Klick
 *
 * Es wird nie die E-Mail-Adresse übertragen, sondern nur ein Zufallstoken
 * der einzelnen Sendung. Öffnungen lassen sich in den Einstellungen und
 * je Kampagne abschalten.
 */

require __DIR__ . '/lib/bootstrap.php';

/* ------------------------------------------------------------ Öffnungen */

$openToken = Util::get('o');
if ($openToken !== '') {
    try {
        Tracking::recordOpen($openToken);
    } catch (Throwable $e) {
        Log::warn('tracking', 'Öffnung konnte nicht gezählt werden: ' . $e->getMessage());
    }
    Tracking::outputPixel();
}

/* --------------------------------------------------------------- Klicks */

$clickToken = Util::get('c');
$linkId     = Util::getInt('l');

if ($clickToken !== '' && $linkId > 0) {
    $url = null;
    try {
        $url = Tracking::recordClick($clickToken, $linkId);
    } catch (Throwable $e) {
        Log::warn('tracking', 'Klick konnte nicht gezählt werden: ' . $e->getMessage());
        $row = DB::row('SELECT url FROM links WHERE id = ?', [$linkId]);
        $url = $row !== null ? (string) $row['url'] : null;
    }

    if ($url !== null && preg_match('#^https?://#i', $url)) {
        header('Referrer-Policy: no-referrer');
        header('Cache-Control: no-store');
        http_response_code(302);
        header('Location: ' . $url);
        exit;
    }
}

/* Unbekannter Aufruf: zur Website schicken statt einen Fehler zu zeigen. */
$fallback = Settings::get('website_url') ?: Config::url('anmelden.php');
http_response_code(302);
header('Location: ' . $fallback);
exit;
