<?php
/**
 * cron/wartung.php – tägliche Pflege.
 *
 *   – löscht unbestätigte Anmeldungen nach Ablauf der Frist (Datensparsamkeit)
 *   – räumt das technische Protokoll auf
 *   – gibt hängende Sendungen wieder frei
 *
 * Aufruf einmal täglich:
 *     php /pfad/zu/newsletter/cron/wartung.php
 *     https://…/newsletter/cron/wartung.php?token=<cron_token>
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

$purged   = Subscribers::purgeExpiredPending();
$logs     = Log::prune(90);
$released = Queue::releaseStaleLocks();

// Zeit- und merkmalsbasierte Strecken: Geburtstag und längere Inaktivität.
// Der eigentliche Versand läuft danach wie immer über den Sende-Cron.
$ausloeser = Automations::runDailyTriggers();

// Abgelaufene Rate-Limit-Einträge entfernen
$rates = DB::delete('rate_limits', 'created_at < ?', [date('Y-m-d H:i:s', time() - 86400)]);

Log::info('wartung', sprintf(
    '%d unbestätigte Anmeldungen gelöscht, %d Protokollzeilen entfernt, %d Sendungen freigegeben.',
    $purged, $logs, $released
));

printf(
    "Unbestätigte gelöscht: %d · Protokollzeilen entfernt: %d · Sendungen freigegeben: %d · Rate-Limits: %d\n"
    . "Automations-Auslöser: %d Geburtstag(e), %d inaktive(r)\n",
    $purged, $logs, $released, $rates, $ausloeser['birthday'], $ausloeser['inactive']
);
exit(0);
