<?php
/**
 * systemcheck.php – Selbsttest des Newslettersystems.
 *
 * Diese Datei ist bewusst so einfach geschrieben, dass sie auf JEDEM Server
 * läuft (auch mit sehr altem PHP) und ohne den Rest des Systems auskommt.
 * Wenn install.php eine leere Seite zeigt, sagt diese Seite warum.
 *
 * Aufruf: https://ihre-domain.de/newsletter/systemcheck.php
 */

// Fehler hier bewusst anzeigen – das ist der Sinn dieser Seite.
@ini_set('display_errors', '1');
@error_reporting(E_ALL);

/* ---------------------------------------------------------------- Zugang
 *
 * Vor der Einrichtung ist die Seite frei: Da gibt es nichts zu schützen,
 * und genau dann braucht man sie. Sobald das System steht, verrät sie
 * einem Fremden zu viel (Fassung, PHP-Version, Erweiterungen, Grenzwerte)
 * – so etwas ist der erste Schritt eines gezielten Angriffs. Deshalb ab
 * dann nur noch für Angemeldete.
 *
 * Zusätzlich geht der Schlüssel aus der config.php als Parameter
 * (?token=…). Denn wenn der Admin-Bereich selbst klemmt, ist diese Seite
 * das einzige Werkzeug, das noch etwas sagen kann – dann darf sie nicht
 * hinter genau der kaputten Anmeldung liegen.
 */
$sc_offen = true;
// Auf zu altem PHP läuft der Programmkern gar nicht erst – dann bleibt die
// Seite offen, denn genau dann muss sie sagen dürfen, woran es liegt.
if (is_file(dirname(__FILE__) . '/config.php') && PHP_VERSION_ID >= 80000) {
    $sc_erlaubt = false;
    try {
        require_once dirname(__FILE__) . '/lib/bootstrap.php';
        $sc_token = (string) Config::get('cron_token', '');
        $sc_frage = isset($_GET['token']) ? (string) $_GET['token'] : '';

        if ($sc_token !== '' && $sc_frage !== '' && hash_equals($sc_token, $sc_frage)) {
            $sc_erlaubt = true;
        } elseif (Auth::check()) {
            $sc_erlaubt = true;
        } elseif (Auth::userCount() === 0) {
            $sc_erlaubt = true;   // eingerichtet, aber noch ohne Zugang
        }
    } catch (Throwable $sc_fehler) {
        // Datenbank kaputt: Dann ist die Seite das letzte Hilfsmittel und
        // bleibt offen – ohne Datenbank steht hier ohnehin nichts Privates.
        $sc_erlaubt = true;
    }
    // bootstrap.php schaltet die Fehleranzeige ab; hier soll sie an bleiben.
    @ini_set('display_errors', '1');
    @error_reporting(E_ALL);
    if (function_exists('ob_get_level')) {
        while (ob_get_level() > 0) { @ob_end_clean(); }
    }

    if (!$sc_erlaubt) {
        header('HTTP/1.1 403 Forbidden');
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html lang="de"><head><meta charset="utf-8"><title>Systemcheck</title></head>'
           . '<body style="font-family:-apple-system,Arial,sans-serif;padding:40px;color:#14243A;max-width:640px;">'
           . '<h1 style="font-size:20px;">Systemcheck</h1>'
           . '<p style="color:#4A5568;line-height:1.6;">Diese Seite zeigt technische Angaben zum Server. '
           . 'Seit der Einrichtung ist sie nur noch nach der Anmeldung sichtbar.</p>'
           . '<p style="color:#4A5568;line-height:1.6;">Klemmt der Admin-Bereich, hängen Sie den '
           . 'Schlüssel aus Ihrer <code>config.php</code> an: '
           . '<code>systemcheck.php?token=IHR_CRON_TOKEN</code></p>'
           . '<p><a href="admin/login.php" style="color:#C8102E;font-weight:700;">Zur Anmeldung</a></p>'
           . '</body></html>';
        exit;
    }
    $sc_offen = false;
}

$MINDEST_PHP = 80000; // PHP 8.0.0
$probleme    = array();
$warnungen   = array();

/* ------------------------------------------------------------ PHP-Version */

$phpOk = (PHP_VERSION_ID >= $MINDEST_PHP);
if (!$phpOk) {
    $probleme[] = 'Ihr Server nutzt PHP ' . PHP_VERSION . '. Das Newslettersystem braucht mindestens PHP 8.0. '
        . 'Stellen Sie die PHP-Version im Hosting-Menü um (bei IONOS: Websites & Shops → PHP verwalten).';
}

/* ----------------------------------------------------------- Erweiterungen */

$erweiterungen = array(
    'pdo'        => 'Datenbankzugriff',
    'pdo_sqlite' => 'Datenbank als Datei (SQLite) – oder alternativ pdo_mysql',
    'pdo_mysql'  => 'Datenbank MySQL/MariaDB – oder alternativ pdo_sqlite',
    'mbstring'   => 'Umlaute und Sonderzeichen',
    'openssl'    => 'verschlüsselter SMTP-Versand (STARTTLS/SSL)',
    'json'       => 'Datenaustausch',
    'filter'     => 'Prüfung von E-Mail-Adressen',
);

$hatDatenbank = extension_loaded('pdo') && (extension_loaded('pdo_sqlite') || extension_loaded('pdo_mysql'));
if (!$hatDatenbank) {
    $probleme[] = 'Es ist keine Datenbank-Erweiterung aktiv (pdo_sqlite oder pdo_mysql). Bitte beim Hoster aktivieren.';
}
foreach (array('mbstring', 'json', 'filter') as $pflicht) {
    if (!extension_loaded($pflicht)) {
        $probleme[] = 'Die PHP-Erweiterung "' . $pflicht . '" fehlt.';
    }
}
if (!extension_loaded('openssl')) {
    $warnungen[] = 'Ohne die Erweiterung "openssl" ist kein verschlüsselter SMTP-Versand möglich – '
        . 'und gespeicherte Passwörter (SMTP, Rücklaufpostfach) liegen dann UNVERSCHLÜSSELT in der '
        . 'Datenbank. Bitte beim Hoster aktivieren lassen.';
}

/* ------------------------------------------------------------ Schreibrechte */

$ordner = array(
    dirname(__FILE__)            => 'Hauptordner (für config.php)',
    dirname(__FILE__) . '/data'  => 'Datenordner (Datenbank, Sperrdatei)',
);
$rechte = array();
foreach ($ordner as $pfad => $zweck) {
    $existiert   = is_dir($pfad);
    $beschreibbar = $existiert && is_writable($pfad);
    $rechte[$pfad] = array('zweck' => $zweck, 'existiert' => $existiert, 'schreibbar' => $beschreibbar);
    if (!$existiert) {
        $probleme[] = 'Der Ordner "' . basename($pfad) . '" fehlt auf dem Server.';
    } elseif (!$beschreibbar) {
        $probleme[] = 'Der Ordner "' . basename($pfad) . '" ist nicht beschreibbar. '
            . 'Bitte per FTP die Rechte auf 755 (Ordner) setzen.';
    }
}

/* ------------------------------------------- Ist der Datenordner dicht?
 *
 * Im Datenordner liegt die Datenbank – also sämtliche Empfängerdaten. Dass
 * niemand sie herunterladen kann, hängt an der Datei data/.htaccess. Ob der
 * Server die überhaupt beachtet, weiß man aber erst, wenn man es versucht:
 * Apache tut es, nginx zum Beispiel nicht. Deshalb fragen wir die eigene
 * Adresse hier tatsächlich einmal ab, statt es zu vermuten.
 */
$datenSchutz = 'unbekannt';   // dicht | offen | unbekannt
$datenPfad   = '';
if (!$sc_offen && class_exists('Config')) {
    $datenDatei = basename((string) Config::get('db.path', ''));
    $basis      = Config::baseUrl();

    if ($basis !== '' && $datenDatei !== '' && Config::get('db.driver') === 'sqlite') {
        $datenPfad = $basis . '/data/' . rawurlencode($datenDatei);
        $anfang    = '';
        $erreicht  = false;

        /*
         * Geholt werden nur die ersten Bytes – und entscheidend ist nicht der
         * Statuscode, sondern ob wirklich eine Datenbank herauskommt: Jede
         * SQLite-Datei beginnt mit "SQLite format 3". Manche Server antworten
         * auf Unbekanntes mit einer freundlichen Seite und Status 200; ohne
         * diese Prüfung gäbe das blinden Alarm.
         */
        if (function_exists('curl_init')) {
            $ch = curl_init($datenPfad);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 8);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($ch, CURLOPT_RANGE, '0-31');
            $roh      = @curl_exec($ch);
            $erreicht = $roh !== false && curl_errno($ch) === 0;
            $anfang   = (string) $roh;
            curl_close($ch);
        } else {
            $kontext = stream_context_create(array('http' => array(
                'method' => 'GET', 'timeout' => 8, 'ignore_errors' => true,
                'header' => "Range: bytes=0-31\r\n",
            )));
            $roh      = @file_get_contents($datenPfad, false, $kontext, 0, 32);
            $erreicht = $roh !== false;
            $anfang   = (string) $roh;
        }

        if ($erreicht && strpos($anfang, 'SQLite format 3') === 0) {
            $datenSchutz = 'offen';
            $probleme[]  = 'SCHWERWIEGEND: Die Datenbank ist aus dem Internet abrufbar – damit kommt '
                . 'jeder an sämtliche Empfängerdaten. Ihr Server beachtet die Datei data/.htaccess '
                . 'offenbar nicht. Sperren Sie den Ordner data/ sofort in der Serverkonfiguration, '
                . 'oder legen Sie die Datenbank außerhalb des Web-Ordners ab.';
        } elseif ($erreicht) {
            // Antwort kam an, war aber keine Datenbank – also abgewiesen.
            $datenSchutz = 'dicht';
        }
    }
}

/* ------------------------------------------------------ Dateien vollständig? */

$erwartet = array(
    'install.php', 'anmelden.php', 'subscribe.php', 'bestaetigen.php', 'abmelden.php',
    'einstellungen.php', 'archiv.php', 'track.php',
    'lib/bootstrap.php', 'lib/Config.php', 'lib/Util.php', 'lib/DB.php', 'lib/Schema.php',
    'lib/Settings.php', 'lib/Log.php', 'lib/Mailer.php', 'lib/Urls.php', 'lib/Lists.php',
    'lib/Events.php', 'lib/Subscribers.php', 'lib/Templates.php', 'lib/Renderer.php',
    'lib/Campaigns.php', 'lib/Queue.php', 'lib/Automations.php', 'lib/SystemMails.php',
    'lib/Auth.php', 'lib/Tracking.php', 'lib/Bounces.php', 'lib/Blocks.php', 'lib/Flow.php',
    'partials/page.php', 'assets/newsletter.css',
    'admin/login.php', 'admin/logout.php', 'admin/index.php', 'admin/kampagnen.php',
    'admin/kampagne.php', 'admin/statistik.php', 'admin/empfaenger.php',
    'admin/empfaenger-detail.php', 'admin/import.php', 'admin/listen.php',
    'admin/automationen.php', 'admin/vorlagen.php', 'admin/versand.php',
    'admin/protokoll.php', 'admin/einstellungen.php', 'admin/benutzer.php',
    'admin/partials/header.php', 'admin/partials/footer.php',
    'admin/assets/admin.css', 'admin/assets/admin.js',
    'admin/assets/builder.css', 'admin/assets/builder.js',
    'admin/assets/flow.css', 'admin/assets/flow.js',
    'admin/partials/builder.php', 'admin/upload.php',
    'cron/send.php', 'cron/bounces.php', 'cron/wartung.php',
);

$dateien  = array();
$fehlend  = 0;
$kaputt   = 0;

foreach ($erwartet as $relativ) {
    $pfad   = dirname(__FILE__) . '/' . $relativ;
    $status = 'ok';
    $hinweis = '';
    $groesse = 0;

    if (!file_exists($pfad)) {
        $status  = 'fehlt';
        $hinweis = 'Datei nicht auf dem Server';
        $fehlend++;
    } else {
        $groesse = filesize($pfad);
        $inhalt  = file_get_contents($pfad);

        if ($inhalt === false || $groesse < 40) {
            $status  = 'kaputt';
            $hinweis = 'Datei ist leer oder unlesbar';
            $kaputt++;
        } else {
            $ende = rtrim($inhalt);
            $letzteZeichen = substr($ende, -9);
            $sauberesEnde = (substr($ende, -1) === ';' || substr($ende, -1) === '}'
                || substr($ende, -2) === '?>' || strpos($letzteZeichen, '</html>') !== false
                || substr($ende, -2) === '*/' || substr($ende, -1) === ')');

            if (!$sauberesEnde) {
                $status  = 'kaputt';
                $hinweis = 'Datei endet mitten im Text – vermutlich unvollständig hochgeladen';
                $kaputt++;
            } elseif (substr($relativ, -4) === '.php' && PHP_VERSION_ID >= 70000 && defined('TOKEN_PARSE')) {
                // Echte Syntaxprüfung, ohne die Datei auszuführen
                try {
                    token_get_all($inhalt, TOKEN_PARSE);
                } catch (Throwable $e) {
                    $status  = 'kaputt';
                    $hinweis = 'PHP kann die Datei nicht lesen: ' . $e->getMessage();
                    $kaputt++;
                } catch (Exception $e) {
                    $status  = 'kaputt';
                    $hinweis = 'PHP kann die Datei nicht lesen: ' . $e->getMessage();
                    $kaputt++;
                }
            }
        }
    }
    $dateien[] = array('datei' => $relativ, 'status' => $status, 'groesse' => $groesse, 'hinweis' => $hinweis);
}

if ($fehlend > 0) {
    $probleme[] = $fehlend . ' Datei(en) fehlen auf dem Server. Bitte den kompletten Ordner "newsletter" erneut hochladen.';
}
if ($kaputt > 0) {
    $probleme[] = $kaputt . ' Datei(en) sind unvollständig oder für diese PHP-Version nicht lesbar. '
        . 'Bitte erneut hochladen (FTP-Übertragung im Binärmodus) bzw. die PHP-Version prüfen.';
}

/* -------------------------------------------------------------- Einrichtung */

$configVorhanden = file_exists(dirname(__FILE__) . '/config.php');

// Fassung des hochgeladenen Codes ermitteln – so sieht man sofort, ob die
// neuen Dateien wirklich auf dem Server angekommen sind.
$version = 'unbekannt';
$bootstrapDatei = dirname(__FILE__) . '/lib/bootstrap.php';
if (is_readable($bootstrapDatei)) {
    $inhaltBootstrap = (string) file_get_contents($bootstrapDatei);
    if (preg_match("/define\\('NL_VERSION',\\s*'([^']+)'\\)/", $inhaltBootstrap, $treffer)) {
        $version = $treffer[1];
    }
}
$hatBaukasten = file_exists(dirname(__FILE__) . '/lib/Blocks.php')
    && file_exists(dirname(__FILE__) . '/admin/assets/builder.js');
$hatAblauf = file_exists(dirname(__FILE__) . '/lib/Flow.php')
    && file_exists(dirname(__FILE__) . '/admin/assets/flow.js')
    && file_exists(dirname(__FILE__) . '/admin/benutzer.php');

/* ------------------------------------------------- Zwischenspeicher (OPcache) */

// Viele Hoster halten kompilierten PHP-Code im Arbeitsspeicher. Wurde eine
// Datei hochgeladen, der Server liefert aber weiter die alte Fassung, liegt
// es fast immer daran.
$opcacheAn      = function_exists('opcache_get_status') && @ini_get('opcache.enable');
$opcacheStatus  = array();
$opcachePruefen = @ini_get('opcache.validate_timestamps');
if ($opcacheAn && function_exists('opcache_get_status')) {
    $roh = @opcache_get_status(false);
    if (is_array($roh)) {
        $opcacheStatus = $roh;
    }
}

$geleert = '';
if (isset($_POST['leeren']) && $_POST['leeren'] === 'opcache') {
    // Den Zwischenspeicher zu leeren kostet Rechenzeit: Danach muss der
    // Server jede Datei neu übersetzen. Ohne Schranke könnte das jeder
    // beliebig oft auslösen – deshalb nur, wer die Seite auch sehen darf.
    if (!$sc_offen && class_exists('Util')) {
        Util::requireCsrf();
    }
    if (function_exists('opcache_reset') && @opcache_reset()) {
        $geleert = 'Der Zwischenspeicher wurde geleert. Bitte die Seite neu laden.';
    } else {
        $geleert = 'Der Zwischenspeicher ließ sich nicht leeren – oft ist das auf gemieteten '
                 . 'Servern gesperrt. Warten Sie ein bis zwei Minuten oder ändern Sie die '
                 . 'PHP-Version einmal hin und zurück, das leert ihn ebenfalls.';
    }
}

/* ------------------------------------------------------------- Prüfsummen */

// pruefsummen.txt wird beim Ausliefern erzeugt und enthält je Datei eine
// Zeile "sha256  pfad". Damit lässt sich Datei für Datei feststellen, was
// beim Hochladen nicht angekommen ist.
$pruefDatei   = dirname(__FILE__) . '/pruefsummen.txt';
$pruefFassung = '';
$veraltet     = array();
$pruefGeprueft = 0;
if (is_readable($pruefDatei)) {
    foreach (file($pruefDatei) as $zeile) {
        $zeile = trim($zeile);
        if ($zeile === '' || substr($zeile, 0, 1) === '#') {
            if (strpos($zeile, '# Fassung:') === 0) {
                $pruefFassung = trim(substr($zeile, 10));
            }
            continue;
        }
        $teile = preg_split('/\s+/', $zeile, 2);
        if (count($teile) !== 2) {
            continue;
        }
        list($soll, $relativ) = $teile;
        $pfad = dirname(__FILE__) . '/' . $relativ;
        if (!is_readable($pfad)) {
            continue; // fehlende Dateien meldet bereits die Liste oben
        }
        $pruefGeprueft++;
        if (hash_file('sha256', $pfad) !== $soll) {
            $veraltet[] = $relativ;
        }
    }
}
if ($veraltet) {
    $probleme[] = count($veraltet) . ' Datei(en) stimmen nicht mit der ausgelieferten Fassung überein. '
        . 'Meist wurden sie beim Hochladen übersprungen – bitte gezielt erneut übertragen.';
}

/* ------------------------------------------------- Schutzdatei der Bilder */

// "php_flag" ohne Absicherung lässt Apache mit Fehler 500 antworten, sobald
// PHP als CGI läuft – dann ist kein Bild im Ordner mehr abrufbar.
$uploadHtaccess = dirname(__FILE__) . '/uploads/.htaccess';
$uploadRiskant  = false;
if (is_readable($uploadHtaccess)) {
    $inhaltHt = (string) file_get_contents($uploadHtaccess);
    $uploadRiskant = preg_match('/^\s*php_flag/mi', $inhaltHt) === 1
        && stripos($inhaltHt, '<IfModule') === false;
}
if ($uploadRiskant) {
    $probleme[] = 'Die Datei uploads/.htaccess enthält "php_flag" ohne Absicherung. '
        . 'Läuft PHP als CGI, antwortet der Server für den ganzen Ordner mit Fehler 500 – '
        . 'hochgeladene Bilder erscheinen dann nicht. Die Datei per FTP löschen; '
        . 'sie wird beim nächsten Bild-Upload richtig neu angelegt.';
}

/* ------------------------------------------------------- Letzter PHP-Fehler */

$letzterFehler = '';
$logPfad = @ini_get('error_log');
if ($logPfad && @is_readable($logPfad) && @filesize($logPfad) > 0) {
    $zeilen = @file($logPfad);
    if (is_array($zeilen)) {
        $zeilen = array_slice($zeilen, -12);
        $letzterFehler = implode('', $zeilen);
    }
}

$alsText = isset($_GET['format']) && $_GET['format'] === 'text';
if ($alsText) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "Systemcheck Newslettersystem\n";
    echo "Fassung: " . $version . "\n";
    echo "Baukasten: " . ($hatBaukasten ? 'vorhanden' : 'FEHLT – ältere Fassung hochgeladen') . "\n";
    echo "Automationen und Benutzer: " . ($hatAblauf ? 'vorhanden' : 'FEHLEN – ältere Fassung hochgeladen') . "\n";
    echo "PHP: " . PHP_VERSION . " (" . PHP_SAPI . ")\n";
    echo "Speichergrenze: " . @ini_get('memory_limit') . ", max. Laufzeit: " . @ini_get('max_execution_time') . "s\n";
    echo "config.php vorhanden: " . ($configVorhanden ? 'ja' : 'nein') . "\n";
    echo "Bilder-Schutzdatei: " . ($uploadRiskant ? 'RISKANT – siehe Probleme' : 'in Ordnung') . "\n";
    echo "Zwischenspeicher (OPcache): " . ($opcacheAn ? 'an' : 'aus')
        . ($opcacheAn && $opcachePruefen === '0' ? ' – prüft Dateidatum NICHT' : '') . "\n";
    if ($pruefGeprueft > 0) {
        echo "Prüfsummen: " . $pruefGeprueft . " Dateien verglichen"
            . ($pruefFassung !== '' ? " (Liste gehört zu Fassung " . $pruefFassung . ")" : '') . "\n";
        echo "Abweichend: " . ($veraltet ? implode(', ', $veraltet) : 'keine') . "\n";
    } else {
        echo "Prüfsummen: keine Liste gefunden (pruefsummen.txt fehlt)\n";
    }
    echo "\n";
    foreach ($dateien as $d) {
        if ($d['status'] !== 'ok') {
            echo strtoupper($d['status']) . ': ' . $d['datei'] . ' – ' . $d['hinweis'] . "\n";
        }
    }
    echo "\nProbleme:\n";
    echo $probleme ? '- ' . implode("\n- ", $probleme) . "\n" : "keine\n";
    exit;
}

function sc_e($wert)
{
    return htmlspecialchars((string) $wert, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Systemcheck – Newslettersystem</title>
<style>
    body { margin:0; background:#F2F5F8; color:#43506A; font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Arial,sans-serif; font-size:15px; line-height:1.6; }
    .kopf { background:#14243A; color:#fff; padding:16px 24px; font-weight:700; }
    .kopf span { display:block; font-weight:400; font-size:13px; color:#B9C4D2; }
    .inhalt { max-width:900px; margin:0 auto; padding:26px 18px 60px; }
    h1 { font-size:23px; color:#14243A; margin:0 0 6px; }
    h2 { font-size:17px; color:#14243A; margin:28px 0 10px; }
    .karte { background:#fff; border:1px solid #E0E6ED; border-radius:10px; padding:20px 22px; margin-bottom:18px; }
    table { width:100%; border-collapse:collapse; font-size:14px; }
    th, td { text-align:left; padding:8px 10px; border-bottom:1px solid #E0E6ED; }
    th { background:#FAFBFD; color:#14243A; font-size:12.5px; text-transform:uppercase; letter-spacing:.4px; }
    .pille { display:inline-block; padding:2px 10px; border-radius:20px; font-size:12px; font-weight:700; }
    .gut { background:#E7F4EC; color:#2E7D53; }
    .schlecht { background:#FDECEF; color:#C8102E; }
    .mittel { background:#FCF3E3; color:#B7791F; }
    .klein { color:#6B7683; font-size:12.5px; line-height:1.5; margin-top:5px; }
    button { background:#14243A; color:#fff; border:0; border-radius:6px; padding:8px 14px;
             font-size:13px; font-weight:700; cursor:pointer; }
    button:hover { background:#22354F; }
    .meldung { padding:14px 18px; border-radius:8px; margin-bottom:16px; }
    .m-fehler { background:#FDECEF; color:#8E0A20; border:1px solid #F3C6CF; }
    .m-warn { background:#FCF3E3; color:#7A5312; border:1px solid #EBD6AE; }
    .m-gut { background:#E7F4EC; color:#1F5C3C; border:1px solid #BFE0CD; }
    .knopf { display:inline-block; background:#C8102E; color:#fff; padding:10px 20px; border-radius:6px; text-decoration:none; font-weight:700; }
    code, pre { font-family:ui-monospace,Menlo,Consolas,monospace; font-size:12.5px; }
    pre { background:#F2F5F8; padding:12px; border-radius:6px; overflow-x:auto; white-space:pre-wrap; }
    ul { margin:8px 0 0 18px; padding:0; }
</style>
</head>
<body>
<div class="kopf">Newslettersystem <span>Systemcheck</span></div>
<div class="inhalt">

    <h1>Systemcheck</h1>
    <p>Diese Seite prüft, ob Ihr Server alles mitbringt und ob alle Dateien vollständig hochgeladen sind.</p>

    <?php if ($probleme): ?>
        <div class="meldung m-fehler">
            <strong>Das muss behoben werden:</strong>
            <ul><?php foreach ($probleme as $p): ?><li><?= sc_e($p) ?></li><?php endforeach; ?></ul>
        </div>
    <?php else: ?>
        <div class="meldung m-gut">
            <strong>Alles in Ordnung.</strong> Ihr Server erfüllt alle Voraussetzungen und alle Dateien sind vollständig.
            <?php if (!$configVorhanden): ?>
                <br>Sie können jetzt die Einrichtung starten.
            <?php endif; ?>
        </div>
        <?php if (!$configVorhanden): ?>
            <p><a class="knopf" href="install.php">Zur Einrichtung</a></p>
        <?php else: ?>
            <p><a class="knopf" href="admin/login.php">Zur Anmeldung</a></p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($warnungen): ?>
        <div class="meldung m-warn">
            <strong>Hinweise:</strong>
            <ul><?php foreach ($warnungen as $w): ?><li><?= sc_e($w) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <div class="karte">
        <h2 style="margin-top:0;">Server</h2>
        <table>
            <tr>
                <th style="width:45%;">Fassung auf der Festplatte</th>
                <td><strong><?= sc_e($version) ?></strong>
                    <span class="pille <?= $hatBaukasten ? 'gut' : 'mittel' ?>">
                        <?= $hatBaukasten ? 'mit Baukasten' : 'ohne Baukasten – ältere Fassung hochgeladen' ?>
                    </span>
                    <span class="pille <?= $hatAblauf ? 'gut' : 'mittel' ?>">
                        <?= $hatAblauf ? 'mit Automationen und Benutzern' : 'ohne Ablauf-Baukasten – ältere Fassung hochgeladen' ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th style="width:45%;">Dateien vollständig aktuell?</th>
                <td>
                    <?php if ($pruefGeprueft === 0): ?>
                        <span class="pille mittel">keine Prüfliste gefunden</span>
                        <div class="klein">Die Datei <code>pruefsummen.txt</code> fehlt – sie gehört mit hochgeladen.</div>
                    <?php elseif ($veraltet): ?>
                        <span class="pille schlecht"><?= count($veraltet) ?> Datei(en) veraltet</span>
                        <div class="klein">Diese Dateien sind auf dem Server anders als in der ausgelieferten
                            Fassung <?= sc_e($pruefFassung) ?> – bitte gezielt erneut hochladen:</div>
                        <pre><?= sc_e(implode("\n", $veraltet)) ?></pre>
                    <?php else: ?>
                        <span class="pille gut">alle <?= (int) $pruefGeprueft ?> Dateien aktuell</span>
                        <div class="klein">Verglichen mit der Prüfliste der Fassung <?= sc_e($pruefFassung) ?>.</div>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th style="width:45%;">Zwischenspeicher für PHP</th>
                <td>
                    <?php if (!$opcacheAn): ?>
                        <span class="pille gut">aus</span>
                        <div class="klein">Hochgeladene Dateien wirken sofort.</div>
                    <?php else: ?>
                        <span class="pille <?= $opcachePruefen === '0' ? 'schlecht' : 'mittel' ?>">an<?php
                            echo $opcachePruefen === '0' ? ' – prüft das Dateidatum nicht' : ''; ?></span>
                        <div class="klein">
                            Der Server hält kompilierten PHP-Code im Arbeitsspeicher.
                            <strong>Steht im Admin-Bereich unten eine ältere Fassung als hier
                            (<?= sc_e($version) ?>), liefert der Server alten Code.</strong>
                            Dann hilft der Knopf unten – oder ein bis zwei Minuten warten.
                        </div>
                        <?php if ($geleert !== ''): ?>
                            <div class="klein" style="margin-top:8px;"><strong><?= sc_e($geleert) ?></strong></div>
                        <?php endif; ?>
                        <?php /* Beim Zugang über ?token=… muss der Schlüssel auch am Formular hängen. */ ?>
                        <form method="post" style="margin-top:8px;" action="systemcheck.php<?=
                            isset($_GET['token']) ? '?token=' . rawurlencode((string) $_GET['token']) : '' ?>">
                            <?php if (!$sc_offen && class_exists('Util')): ?>
                                <input type="hidden" name="_csrf" value="<?= sc_e(Util::csrfToken()) ?>">
                            <?php endif; ?>
                            <input type="hidden" name="leeren" value="opcache">
                            <button type="submit">Zwischenspeicher jetzt leeren</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th style="width:45%;">PHP-Version</th>
                <td><?= sc_e(PHP_VERSION) ?> (<?= sc_e(PHP_SAPI) ?>)
                    <span class="pille <?= $phpOk ? 'gut' : 'schlecht' ?>"><?= $phpOk ? 'in Ordnung' : 'zu alt – mindestens 8.0 nötig' ?></span>
                </td>
            </tr>
            <tr><th>Speichergrenze</th><td><?= sc_e(@ini_get('memory_limit')) ?></td></tr>
            <tr><th>Maximale Laufzeit</th><td><?= sc_e(@ini_get('max_execution_time')) ?> Sekunden</td></tr>
            <tr><th>Fehleranzeige</th><td><?= @ini_get('display_errors') ? 'an' : 'aus (Fehler stehen im Fehlerprotokoll des Hosters)' ?></td></tr>
            <tr><th>Fehlerprotokoll</th><td><code><?= sc_e(@ini_get('error_log') ? @ini_get('error_log') : 'vom Hoster verwaltet') ?></code></td></tr>
            <tr><th>config.php</th><td><?= $configVorhanden ? 'vorhanden – System ist eingerichtet' : 'noch nicht vorhanden – Einrichtung ausstehend' ?></td></tr>
        </table>
    </div>

    <div class="karte">
        <h2 style="margin-top:0;">PHP-Erweiterungen</h2>
        <table>
            <?php foreach ($erweiterungen as $name => $zweck):
                $da = extension_loaded($name);
                $optional = ($name === 'pdo_sqlite' || $name === 'pdo_mysql' || $name === 'openssl'); ?>
                <tr>
                    <th style="width:45%;"><?= sc_e($name) ?><br><span style="font-weight:400;color:#8A95A5;font-size:12.5px;"><?= sc_e($zweck) ?></span></th>
                    <td>
                        <span class="pille <?= $da ? 'gut' : ($optional ? 'mittel' : 'schlecht') ?>">
                            <?= $da ? 'vorhanden' : 'fehlt' ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="karte">
        <h2 style="margin-top:0;">Schreibrechte</h2>
        <table>
            <?php foreach ($rechte as $pfad => $info): ?>
                <tr>
                    <th style="width:45%;"><?= sc_e(basename($pfad)) ?><br><span style="font-weight:400;color:#8A95A5;font-size:12.5px;"><?= sc_e($info['zweck']) ?></span></th>
                    <td>
                        <span class="pille <?= $info['schreibbar'] ? 'gut' : 'schlecht' ?>">
                            <?= $info['existiert'] ? ($info['schreibbar'] ? 'beschreibbar' : 'schreibgeschützt') : 'fehlt' ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if ($datenSchutz !== 'unbekannt' || $datenPfad !== ''): ?>
            <h2>Sind die Empfängerdaten geschützt?</h2>
            <?php if ($datenSchutz === 'offen'): ?>
                <span class="pille schlecht">die Datenbank ist öffentlich abrufbar</span>
                <p class="klein">Wir haben <code><?= sc_e($datenPfad) ?></code> abgerufen und die Datei
                    bekommen. Damit kann jeder sämtliche Namen, Adressen und Einwilligungen
                    herunterladen. Der Ordner <code>data/</code> muss in der Serverkonfiguration
                    gesperrt werden – oder die Datenbank gehört außerhalb des Web-Ordners.</p>
            <?php elseif ($datenSchutz === 'dicht'): ?>
                <span class="pille gut">von außen nicht erreichbar</span>
                <p class="klein">Der Abruf der Datenbankdatei über das Internet wurde abgewiesen.
                    Der Schutz liegt an <code>data/.htaccess</code>; bleibt diese Datei beim
                    Hochladen liegen, bleibt der Schutz bestehen.</p>
            <?php else: ?>
                <span class="pille mittel">nicht prüfbar</span>
                <p class="klein">Der Server durfte sich nicht selbst aufrufen. Prüfen Sie es von Hand:
                    Rufen Sie <code><?= sc_e($datenPfad) ?></code> im Browser auf. Erscheint eine
                    Fehlermeldung, ist alles in Ordnung – lädt eine Datei herunter, nicht.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="karte">
        <h2 style="margin-top:0;">Dateien (<?= count($dateien) ?> geprüft)</h2>
        <?php if ($fehlend === 0 && $kaputt === 0): ?>
            <p style="margin:0;">Alle Dateien sind vorhanden und vollständig.</p>
        <?php else: ?>
            <table>
                <tr><th>Datei</th><th style="width:110px;">Status</th><th>Hinweis</th></tr>
                <?php foreach ($dateien as $d): if ($d['status'] === 'ok') { continue; } ?>
                    <tr>
                        <td><code><?= sc_e($d['datei']) ?></code></td>
                        <td><span class="pille schlecht"><?= sc_e($d['status']) ?></span></td>
                        <td><?= sc_e($d['hinweis']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <p style="margin-top:14px;">Laden Sie den Ordner <code>newsletter</code> bitte komplett neu hoch.
                Achten Sie darauf, dass Ihr FTP-Programm die Übertragung vollständig abschließt
                (im Zweifel „Binär“ statt „Automatisch“ einstellen).</p>
        <?php endif; ?>
    </div>

    <?php if ($letzterFehler !== ''): ?>
        <div class="karte">
            <h2 style="margin-top:0;">Letzte Einträge im Fehlerprotokoll</h2>
            <pre><?= sc_e($letzterFehler) ?></pre>
        </div>
    <?php endif; ?>

    <p style="font-size:13px;color:#8A95A5;">
        Hier stehen keine Zugangsdaten und keine Empfängerangaben – aber Fassung, PHP-Version und
        Erweiterungen. Das ist genau das, was ein Angreifer zuerst wissen will, deshalb ist die Seite
        seit der Einrichtung nur noch angemeldet erreichbar.
        Textfassung zum Kopieren: <a href="systemcheck.php?format=text">systemcheck.php?format=text</a>
    </p>
</div>
</body>
</html>
