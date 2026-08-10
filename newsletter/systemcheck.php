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
    $warnungen[] = 'Ohne die Erweiterung "openssl" ist kein verschlüsselter SMTP-Versand möglich.';
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

/* ------------------------------------------------------ Dateien vollständig? */

$erwartet = array(
    'install.php', 'anmelden.php', 'subscribe.php', 'bestaetigen.php', 'abmelden.php',
    'einstellungen.php', 'archiv.php', 'track.php',
    'lib/bootstrap.php', 'lib/Config.php', 'lib/Util.php', 'lib/DB.php', 'lib/Schema.php',
    'lib/Settings.php', 'lib/Log.php', 'lib/Mailer.php', 'lib/Urls.php', 'lib/Lists.php',
    'lib/Events.php', 'lib/Subscribers.php', 'lib/Templates.php', 'lib/Renderer.php',
    'lib/Campaigns.php', 'lib/Queue.php', 'lib/Automations.php', 'lib/SystemMails.php',
    'lib/Auth.php', 'lib/Tracking.php', 'lib/Bounces.php', 'lib/Blocks.php',
    'partials/page.php', 'assets/newsletter.css',
    'admin/login.php', 'admin/logout.php', 'admin/index.php', 'admin/kampagnen.php',
    'admin/kampagne.php', 'admin/statistik.php', 'admin/empfaenger.php',
    'admin/empfaenger-detail.php', 'admin/import.php', 'admin/listen.php',
    'admin/automationen.php', 'admin/vorlagen.php', 'admin/versand.php',
    'admin/protokoll.php', 'admin/einstellungen.php',
    'admin/partials/header.php', 'admin/partials/footer.php',
    'admin/assets/admin.css', 'admin/assets/admin.js',
    'admin/assets/builder.css', 'admin/assets/builder.js',
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
    echo "Fassung: " . $version . ($hatBaukasten ? " (Baukasten vorhanden)" : " (ohne Baukasten)") . "\n";
    echo "PHP: " . PHP_VERSION . " (" . PHP_SAPI . ")\n";
    echo "Speichergrenze: " . @ini_get('memory_limit') . ", max. Laufzeit: " . @ini_get('max_execution_time') . "s\n";
    echo "config.php vorhanden: " . ($configVorhanden ? 'ja' : 'nein') . "\n\n";
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
                <th style="width:45%;">Fassung des Programmcodes</th>
                <td><strong><?= sc_e($version) ?></strong>
                    <span class="pille <?= $hatBaukasten ? 'gut' : 'mittel' ?>">
                        <?= $hatBaukasten ? 'mit Baukasten' : 'ohne Baukasten – ältere Fassung hochgeladen' ?>
                    </span>
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
        Diese Seite verrät nichts Vertrauliches, kann aber nach der Einrichtung gelöscht werden.
        Textfassung zum Kopieren: <a href="systemcheck.php?format=text">systemcheck.php?format=text</a>
    </p>
</div>
</body>
</html>
