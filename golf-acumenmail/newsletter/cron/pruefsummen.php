<?php
/**
 * pruefsummen.php – erzeugt newsletter/pruefsummen.txt.
 *
 * Die Liste enthält je Programmdatei eine Prüfsumme. systemcheck.php
 * vergleicht sie mit den Dateien auf dem Server und zeigt damit genau,
 * was beim Hochladen nicht angekommen ist.
 *
 * Aufruf auf dem eigenen Rechner vor dem Ausliefern:
 *   php newsletter/cron/pruefsummen.php
 *
 * Nicht für den Cron-Job gedacht – die Datei liegt hier, weil in diesem
 * Ordner die Kommandozeilen-Werkzeuge stehen.
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Nur über die Kommandozeile.\n");
}

$wurzel = dirname(__DIR__);

/** Dateien, die zum Programm gehören – Daten und Zugangsdaten bleiben außen vor. */
function dateienSammeln(string $wurzel): array
{
    $aus       = [];
    $ignoriert = ['data', 'uploads', 'vorlagen'];
    $iterator  = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($wurzel, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $pfad => $info) {
        $relativ = str_replace('\\', '/', substr($pfad, strlen($wurzel) + 1));
        $erster  = explode('/', $relativ)[0];

        if ($info->isDir() || in_array($erster, $ignoriert, true)) {
            continue;
        }
        // Erzeugte oder persönliche Dateien gehören nicht in die Liste
        if (in_array($relativ, ['config.php', 'pruefsummen.txt'], true)) {
            continue;
        }
        if (!preg_match('/\.(php|js|css|md)$/', $relativ)) {
            continue;
        }
        $aus[] = $relativ;
    }
    sort($aus);
    return $aus;
}

$version = 'unbekannt';
$inhalt  = (string) file_get_contents($wurzel . '/lib/bootstrap.php');
if (preg_match("/define\('NL_VERSION',\s*'([^']+)'\)/", $inhalt, $treffer)) {
    $version = $treffer[1];
}

$zeilen = [
    '# Prüfsummen des Newslettersystems – erzeugt von cron/pruefsummen.php',
    '# Fassung: ' . $version,
    '# systemcheck.php vergleicht damit die Dateien auf dem Server.',
];
$dateien = dateienSammeln($wurzel);
foreach ($dateien as $relativ) {
    $zeilen[] = hash_file('sha256', $wurzel . '/' . $relativ) . '  ' . $relativ;
}

file_put_contents($wurzel . '/pruefsummen.txt', implode("\n", $zeilen) . "\n");
printf("%d Dateien erfasst, Fassung %s\n", count($dateien), $version);
