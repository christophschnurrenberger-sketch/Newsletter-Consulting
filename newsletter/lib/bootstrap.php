<?php
/**
 * bootstrap.php – gemeinsamer Einstieg für alle Skripte des Newslettersystems.
 *
 * Lädt die Klassen, die Konfiguration und die Datenbankverbindung.
 * Ohne config.php (also vor der Installation) wird auf install.php verwiesen.
 */

declare(strict_types=1);

if (!defined('NL_ROOT')) {
    define('NL_ROOT', dirname(__DIR__));
}

/**
 * Fassung des Programmcodes. Steht im Admin-Bereich unten und im
 * Systemcheck – so lässt sich sofort erkennen, welcher Stand auf dem
 * Server liegt.
 */
define('NL_VERSION', '1.20.0 (Kopfzeile und Footer stehen im Baukasten)');

mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/Berlin');

/*
 * Ausgabe zwischenspeichern.
 *
 * Die Admin-Seiten geben ihren Seitenkopf aus, bevor sie ein abgeschicktes
 * Formular verarbeiten. Ohne Puffer wäre danach kein "Location"-Header mehr
 * möglich – die Weiterleitung nach dem Speichern liefe ins Leere und der
 * Browser zeigte eine halbe, leere Seite. Viele Server haben den Puffer ab
 * Werk aus, deshalb schalten wir ihn hier selbst ein.
 */
if (PHP_SAPI !== 'cli' && !headers_sent()) {
    ob_start();
}

/* Klassen laden – bewusst ohne Composer, damit das System überall läuft. */
foreach ([
    'Config', 'Util', 'DB', 'Schema', 'Settings', 'Log', 'Mailer', 'Urls',
    'Lists', 'Events', 'Subscribers', 'Templates', 'Blocks', 'Renderer', 'Campaigns',
    'Queue', 'Flow', 'Automations', 'SystemMails', 'Auth', 'Tracking', 'Bounces', 'Ai', 'Snippets',
] as $class) {
    require_once NL_ROOT . '/lib/' . $class . '.php';
}

/* Fehler nicht an Besucher ausgeben, aber protokollieren. */
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

set_exception_handler(static function (Throwable $e): void {
    error_log('[Newsletter] ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    try {
        Log::error('app', $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine());
    } catch (Throwable $ignored) {
        // Datenbank evtl. nicht verfügbar
    }
    if (Util::isCli()) {
        fwrite(STDERR, 'Fehler: ' . $e->getMessage() . PHP_EOL);
        exit(1);
    }
    http_response_code(500);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="de"><head><meta charset="utf-8"><title>Fehler</title></head>'
       . '<body style="font-family:Arial,Helvetica,sans-serif;padding:40px;color:#14243A;">'
       . '<h1 style="font-size:20px;">Es ist ein Fehler aufgetreten</h1>'
       . '<p style="color:#4A5568;">Bitte versuchen Sie es später erneut. Der Vorfall wurde protokolliert.</p>'
       . '</body></html>';
    exit(1);
});

/* Konfiguration laden. */
$configLoaded = Config::load();

if (!$configLoaded && !defined('NL_INSTALLER')) {
    if (Util::isCli()) {
        fwrite(STDERR, "Das Newslettersystem ist noch nicht eingerichtet. Bitte install.php im Browser aufrufen.\n");
        exit(1);
    }
    http_response_code(503);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="de"><head><meta charset="utf-8"><title>Einrichtung nötig</title></head>'
       . '<body style="font-family:Arial,Helvetica,sans-serif;padding:40px;color:#14243A;">'
       . '<h1 style="font-size:20px;">Newslettersystem noch nicht eingerichtet</h1>'
       . '<p style="color:#4A5568;">Bitte rufen Sie einmalig <a href="install.php">install.php</a> auf.</p>'
       . '</body></html>';
    exit;
}

if ($configLoaded) {
    DB::init();

    /*
     * Nach dem Hochladen einer neueren Fassung fehlen der Datenbank unter
     * Umständen neue Spalten oder Tabellen. Das holen wir hier automatisch
     * nach – sonst müsste nach jedem Update von Hand nachgearbeitet werden.
     * Schema::migrate() ist idempotent und läuft nur bei geänderter Version.
     */
    try {
        if (Schema::isInstalled() && (int) Settings::get('schema_version') < Schema::VERSION) {
            Schema::migrate();
            Log::info('schema', 'Datenbank auf Stand ' . Schema::VERSION . ' gebracht (' . NL_VERSION . ').');
        }
    } catch (Throwable $e) {
        error_log('[Newsletter] Aktualisierung der Datenbank fehlgeschlagen: ' . $e->getMessage());
    }
}

/* Sicherheitskopfzeilen für alle HTML-Ausgaben im Browser. */
if (!Util::isCli() && !headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('X-Frame-Options: SAMEORIGIN');
}
