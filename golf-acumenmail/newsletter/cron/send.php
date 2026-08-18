<?php
/**
 * cron/send.php – arbeitet die Versandwarteschlange ab.
 *
 * Aufruf per Kommandozeile (bevorzugt):
 *     php /pfad/zu/newsletter/cron/send.php
 *
 * Aufruf per URL (wenn der Hoster nur Web-Cronjobs kann):
 *     https://…/newsletter/cron/send.php?token=<cron_token aus config.php>
 *
 * Empfohlener Takt: alle 5 Minuten. Mehrere gleichzeitige Läufe stören
 * einander nicht – jede Mail wird vor dem Versand gesperrt.
 */

require __DIR__ . '/../lib/bootstrap.php';

/* ------------------------------------------------------------ Zugriff */

if (!Util::isCli()) {
    $token = (string) Config::get('cron_token', '');
    if ($token === '' || !hash_equals($token, Util::get('token'))) {
        http_response_code(403);
        header('Content-Type: text/plain; charset=utf-8');
        exit('Zugriff verweigert.');
    }
    header('Content-Type: text/plain; charset=utf-8');
}

@set_time_limit(0);
ignore_user_abort(true);

/* --------------------------------------------------- Mehrfachlauf-Sperre */

$lockFile = NL_ROOT . '/data/send.lock';
$lock     = @fopen($lockFile, 'c');
if ($lock === false) {
    Log::error('cron', 'Sperrdatei konnte nicht angelegt werden: ' . $lockFile);
    exit(1);
}
if (!flock($lock, LOCK_EX | LOCK_NB)) {
    echo "Ein anderer Durchlauf ist noch aktiv – dieser wird übersprungen.\n";
    fclose($lock);
    exit(0);
}

/* ------------------------------------------------------------- Versand */

try {
    $result = Queue::process([
        'limit'   => Settings::int('batch_size', 50),
        'seconds' => Settings::int('max_runtime', 50),
    ]);

    printf(
        "Versendet: %d · Fehlgeschlagen: %d · Übersprungen: %d · Offen: %d · Dauer: %.1fs%s\n",
        $result['sent'], $result['failed'], $result['skipped'], $result['remaining'], $result['seconds'],
        $result['limited'] !== '' ? ' · Hinweis: ' . $result['limited'] : ''
    );
} catch (Throwable $e) {
    Log::error('cron', 'Versandlauf abgebrochen: ' . $e->getMessage());
    echo 'Fehler: ' . $e->getMessage() . "\n";
    flock($lock, LOCK_UN);
    fclose($lock);
    exit(1);
}

flock($lock, LOCK_UN);
fclose($lock);
exit(0);
