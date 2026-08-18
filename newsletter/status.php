<?php
/**
 * status.php – Kurzbericht einer Installation, für die Instanzen-Übersicht.
 *
 * Wer mehrere Installationen betreibt (eine je Kunde), will nicht in jede
 * einzeln hineinschauen müssen. Diese Datei liefert deshalb ein paar Zahlen
 * als JSON – und zwar nur gegen den Schlüssel aus der config.php, denselben,
 * mit dem auch der Cron-Job aufgerufen wird.
 *
 *   status.php?token=<cron_token>
 *
 * Bewusst enthalten sind nur Zahlen und die Fassung. Keine Adressen, keine
 * Namen, keine Betreffzeilen: Der Schlüssel wandert in die Datenbank der
 * überwachenden Installation, und was dort landet, muss harmlos sein.
 */

declare(strict_types=1);

require_once __DIR__ . '/lib/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$token = (string) Config::get('cron_token', '');
$frage = isset($_GET['token']) ? (string) $_GET['token'] : '';

if ($token === '' || $frage === '' || !hash_equals($token, $frage)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'fehler' => 'Kein Zugriff.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!Schema::isInstalled()) {
    echo json_encode(['ok' => false, 'fehler' => 'Noch nicht eingerichtet.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$empfaenger = Subscribers::statusCounts();
$ausgaben   = Campaigns::statusCounts();
$queue      = Queue::overview();

/* Wie viele Marken hat diese Installation? */
$marken = [];
foreach (Templates::brands() as $marke) {
    if (!$marke['neu']) {
        $marken[] = (string) $marke['name'];
    }
}

echo json_encode([
    'ok'          => true,
    'marke'       => Settings::get('brand_name'),
    'marken'      => $marken,
    'version'     => NL_VERSION,
    'schema'      => (int) Settings::get('schema_version'),
    'empfaenger'  => [
        'aktiv'        => (int) ($empfaenger[Subscribers::STATUS_ACTIVE] ?? 0),
        'unbestaetigt' => (int) ($empfaenger[Subscribers::STATUS_PENDING] ?? 0),
        'abgemeldet'   => (int) ($empfaenger[Subscribers::STATUS_UNSUBSCRIBED] ?? 0),
        'gesamt'       => array_sum($empfaenger),
    ],
    'newsletter'  => [
        'entwurf'   => (int) ($ausgaben[Campaigns::DRAFT] ?? 0),
        'geplant'   => (int) ($ausgaben[Campaigns::SCHEDULED] ?? 0),
        'versendet' => (int) ($ausgaben[Campaigns::SENT] ?? 0),
        'gesamt'    => (int) ($ausgaben[''] ?? 0),
    ],
    'versand'     => [
        'offen'        => (int) $queue['pending'],
        'heute'        => (int) $queue['sent_today'],
        'fehler'       => (int) $queue['failed'],
        'letzter_cron' => (string) $queue['last_cron_at'],
    ],
    'listen'      => count(Lists::all()),
    'automationen' => count(Automations::all()),
    'zeit'        => Util::now(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
