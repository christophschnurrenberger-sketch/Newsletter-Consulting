<?php
/**
 * cron/bounces.php – holt Fehlermails aus dem Rücklaufpostfach.
 *
 * Aufruf wie beim Versand:
 *     php /pfad/zu/newsletter/cron/bounces.php
 *     https://…/newsletter/cron/bounces.php?token=<cron_token>
 *
 * Empfohlener Takt: einmal pro Stunde.
 */

require __DIR__ . '/../lib/bootstrap.php';

if (!Util::isCli()) {
    $token = (string) Config::get('cron_token', '');
    if ($token === '' || !hash_equals($token, Util::get('token'))) {
        http_response_code(403);
        header('Content-Type: text/plain; charset=utf-8');
        exit('Zugriff verweigert.');
    }
    header('Content-Type: text/plain; charset=utf-8');
}

@set_time_limit(300);

if (!Settings::bool('bounce_enabled')) {
    echo "Die Auswertung von Rückläufern ist nicht aktiviert (Einstellungen → Rückläufer).\n";
    exit(0);
}

$result = Bounces::processMailbox(200);

if ($result['error'] !== '') {
    echo 'Fehler: ' . $result['error'] . "\n";
    exit(1);
}

printf(
    "Geprüft: %d · dauerhaft unzustellbar: %d · vorübergehend: %d · ohne Bezug: %d\n",
    $result['checked'], $result['hard'], $result['soft'], $result['ignored']
);
exit(0);
