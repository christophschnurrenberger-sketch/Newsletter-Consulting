<?php
/**
 * upload.php – Bilder für den Baukasten hochladen und verwalten.
 *
 * Bilder in E-Mails müssen öffentlich erreichbar sein; sie liegen deshalb
 * in newsletter/uploads/ (nicht im gesperrten data-Ordner). Dort ist die
 * Ausführung von PHP per .htaccess abgeschaltet.
 *
 *   GET  ?liste=1   → JSON mit allen vorhandenen Bildern
 *   POST datei=…    → Bild hochladen, JSON mit der URL zurück
 *   POST loeschen=… → Bild entfernen
 */

require_once dirname(__DIR__) . '/lib/bootstrap.php';

Auth::require('kampagnen');

const NL_UPLOAD_MAX  = 3145728; // 3 MB
const NL_UPLOAD_DIR  = 'uploads';

$verzeichnis = NL_ROOT . '/' . NL_UPLOAD_DIR;

/** Legt den Ordner an und schützt ihn gegen die Ausführung von Skripten. */
function upload_verzeichnis_vorbereiten(string $verzeichnis): string
{
    if (!is_dir($verzeichnis) && !@mkdir($verzeichnis, 0755, true)) {
        return 'Der Ordner uploads/ konnte nicht angelegt werden. Bitte per FTP anlegen (Rechte 755).';
    }
    if (!is_writable($verzeichnis)) {
        return 'Der Ordner uploads/ ist nicht beschreibbar. Bitte per FTP die Rechte auf 755 setzen.';
    }
    $schutz = $verzeichnis . '/.htaccess';
    if (!is_file($schutz)) {
        @file_put_contents($schutz,
            "# Hier liegen nur Bilder – niemals Programmcode ausführen.\n"
            . "php_flag engine off\n"
            . "<FilesMatch \"\\.(php|phtml|php3|php4|php5|php7|phps|cgi|pl|py|sh)$\">\n"
            . "    Require all denied\n"
            . "</FilesMatch>\n"
            . "AddType text/plain .php .phtml .cgi .pl\n");
    }
    return '';
}

$fehler = upload_verzeichnis_vorbereiten($verzeichnis);

/* ------------------------------------------------------------- Auflisten */

if (Util::get('liste') === '1' && !Util::isPost()) {
    $bilder = [];
    foreach (glob($verzeichnis . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE) ?: [] as $pfad) {
        $bilder[] = [
            'url'   => Config::url(NL_UPLOAD_DIR . '/' . basename($pfad)),
            'name'  => basename($pfad),
            'groesse' => filesize($pfad),
            'zeit'  => filemtime($pfad),
        ];
    }
    usort($bilder, static fn($a, $b) => $b['zeit'] <=> $a['zeit']);
    Util::json(['ok' => $fehler === '', 'fehler' => $fehler, 'bilder' => $bilder]);
}

/* --------------------------------------------------------------- Löschen */

if (Util::isPost() && Util::post('loeschen') !== '') {
    Util::requireCsrf();
    $name = basename(Util::post('loeschen'));
    $pfad = $verzeichnis . '/' . $name;
    if (preg_match('/^[A-Za-z0-9._-]+\.(jpg|jpeg|png|gif|webp)$/i', $name) && is_file($pfad)) {
        @unlink($pfad);
        Util::json(['ok' => true]);
    }
    Util::json(['ok' => false, 'fehler' => 'Datei nicht gefunden.'], 404);
}

/* ------------------------------------------------------------ Hochladen */

if (!Util::isPost()) {
    Util::json(['ok' => false, 'fehler' => 'Nur POST erlaubt.'], 405);
}
Util::requireCsrf();

if ($fehler !== '') {
    Util::json(['ok' => false, 'fehler' => $fehler], 500);
}

$datei = $_FILES['datei'] ?? null;
if (!is_array($datei) || (int) ($datei['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    $meldung = match ((int) ($datei['error'] ?? UPLOAD_ERR_NO_FILE)) {
        UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Die Datei ist zu groß.',
        UPLOAD_ERR_NO_FILE => 'Es wurde keine Datei ausgewählt.',
        default => 'Der Upload ist fehlgeschlagen.',
    };
    Util::json(['ok' => false, 'fehler' => $meldung], 422);
}

if ((int) $datei['size'] > NL_UPLOAD_MAX) {
    Util::json(['ok' => false, 'fehler' => 'Das Bild ist größer als 3 MB. Bitte vorher verkleinern.'], 422);
}
if (!is_uploaded_file((string) $datei['tmp_name'])) {
    Util::json(['ok' => false, 'fehler' => 'Ungültiger Upload.'], 400);
}

// Nur echte Bilder annehmen – die Dateiendung allein ist kein Beweis.
$info = @getimagesize((string) $datei['tmp_name']);
$typen = [
    IMAGETYPE_JPEG => 'jpg',
    IMAGETYPE_PNG  => 'png',
    IMAGETYPE_GIF  => 'gif',
    IMAGETYPE_WEBP => 'webp',
];
if ($info === false || !isset($typen[$info[2]])) {
    Util::json(['ok' => false, 'fehler' => 'Nur JPG, PNG, GIF oder WebP sind möglich.'], 422);
}

$endung = $typen[$info[2]];
$basis  = preg_replace('/[^a-z0-9]+/i', '-', pathinfo((string) $datei['name'], PATHINFO_FILENAME)) ?: 'bild';
$basis  = trim(mb_strtolower(mb_substr($basis, 0, 40)), '-') ?: 'bild';
$name   = $basis . '-' . bin2hex(random_bytes(4)) . '.' . $endung;
$ziel   = $verzeichnis . '/' . $name;

if (!@move_uploaded_file((string) $datei['tmp_name'], $ziel)) {
    Util::json(['ok' => false, 'fehler' => 'Das Bild konnte nicht gespeichert werden.'], 500);
}
@chmod($ziel, 0644);

Log::info('upload', 'Bild hochgeladen: ' . $name . ' (' . (int) $datei['size'] . ' Bytes)');

Util::json([
    'ok'     => true,
    'url'    => Config::url(NL_UPLOAD_DIR . '/' . $name),
    'name'   => $name,
    'breite' => (int) $info[0],
    'hoehe'  => (int) $info[1],
]);
