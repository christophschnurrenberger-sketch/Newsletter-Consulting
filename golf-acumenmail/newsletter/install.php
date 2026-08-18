<?php
/**
 * install.php – einmalige Einrichtung.
 *
 * Legt config.php an, erstellt die Datenbanktabellen, die Standardliste,
 * die Vorlagen und den ersten Zugang. Danach sollte diese Datei gelöscht
 * werden – das System weist im Admin-Bereich darauf hin.
 */

/*
 * Schritt 0: PHP-Version prüfen, BEVOR das eigentliche System geladen wird.
 * Bei zu altem PHP könnten die Programmdateien gar nicht erst gelesen werden –
 * das Ergebnis wäre eine weiße Seite ohne jede Erklärung.
 */
if (PHP_VERSION_ID < 80000) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="de"><head><meta charset="utf-8"><title>PHP zu alt</title></head>'
        . '<body style="font-family:Arial,Helvetica,sans-serif;max-width:640px;margin:60px auto;padding:0 20px;color:#14243A;">'
        . '<h1 style="font-size:22px;">Ihr Server nutzt eine zu alte PHP-Version</h1>'
        . '<p style="color:#4A5568;line-height:1.6;">Gefunden: <strong>PHP ' . PHP_VERSION . '</strong>. '
        . 'Das Newslettersystem benötigt mindestens <strong>PHP 8.0</strong>.</p>'
        . '<p style="color:#4A5568;line-height:1.6;">Die PHP-Version stellen Sie im Hosting-Menü um '
        . '(bei IONOS: „Websites &amp; Shops“ → Ihre Website → „PHP verwalten“). '
        . 'Danach diese Seite neu laden.</p>'
        . '<p><a href="systemcheck.php" style="color:#C8102E;">Zum Systemcheck</a></p>'
        . '</body></html>';
    exit;
}

/*
 * Fehler während der Einrichtung sichtbar machen. Ohne das bliebe die Seite
 * bei einem Problem einfach leer – der häufigste Grund für Ratlosigkeit.
 * Nach der Einrichtung wird install.php gelöscht, also bleibt nichts offen.
 */
@ini_set('display_errors', '1');
error_reporting(E_ALL);

register_shutdown_function(static function () {
    $fehler = error_get_last();
    if ($fehler === null || !in_array($fehler['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        return;
    }
    echo '<div style="font-family:Arial,Helvetica,sans-serif;max-width:760px;margin:24px auto;padding:18px 22px;'
        . 'background:#FDECEF;border:1px solid #F3C6CF;border-radius:8px;color:#8E0A20;line-height:1.6;">'
        . '<strong>Die Einrichtung wurde durch einen Fehler abgebrochen:</strong><br>'
        . htmlspecialchars($fehler['message'], ENT_QUOTES, 'UTF-8')
        . '<br><span style="font-size:13px;">in ' . htmlspecialchars(basename((string) $fehler['file']), ENT_QUOTES, 'UTF-8')
        . ', Zeile ' . (int) $fehler['line'] . '</span>'
        . '<p style="margin:12px 0 0;"><a href="systemcheck.php" style="color:#8E0A20;">Systemcheck öffnen</a> – '
        . 'dort steht, was auf diesem Server fehlt.</p></div>';
});

define('NL_INSTALLER', true);
require __DIR__ . '/lib/bootstrap.php';

// bootstrap.php schaltet die Fehleranzeige ab (richtig für den Betrieb).
// Für die einmalige Einrichtung schalten wir sie wieder ein – sonst bliebe
// ein Problem unsichtbar und die Seite einfach leer.
@ini_set('display_errors', '1');
error_reporting(E_ALL);

// Der Installer zeigt Fehler im Klartext statt der allgemeinen Fehlerseite.
set_exception_handler(static function (Throwable $e) {
    echo '<div style="font-family:Arial,Helvetica,sans-serif;max-width:760px;margin:24px auto;padding:18px 22px;'
        . 'background:#FDECEF;border:1px solid #F3C6CF;border-radius:8px;color:#8E0A20;line-height:1.6;">'
        . '<strong>Fehler bei der Einrichtung:</strong><br>'
        . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
        . '<br><span style="font-size:13px;">in ' . htmlspecialchars(basename($e->getFile()), ENT_QUOTES, 'UTF-8')
        . ', Zeile ' . $e->getLine() . '</span>'
        . '<p style="margin:12px 0 0;"><a href="systemcheck.php" style="color:#8E0A20;">Systemcheck öffnen</a></p></div>';
    exit(1);
});

$installed = Config::isInstalled();
if ($installed) {
    try {
        DB::init();
        if (Schema::isInstalled() && Auth::userCount() > 0) {
            http_response_code(403);
            echo '<!DOCTYPE html><html lang="de"><head><meta charset="utf-8"><title>Bereits eingerichtet</title>'
               . '<link rel="stylesheet" href="admin/assets/admin.css"></head><body>'
               . '<div class="ad-login-wrap"><div class="ad-login">'
               . '<h1>Bereits eingerichtet</h1>'
               . '<p class="ad-sub">Das Newslettersystem ist fertig installiert.</p>'
               . '<div class="ad-flash ad-flash-warning">Bitte löschen Sie die Datei <code>install.php</code> '
               . 'vom Server – sie wird nicht mehr gebraucht.</div>'
               . '<a class="ad-btn" href="admin/login.php">Zur Anmeldung</a>'
               . '</div></div></body></html>';
            exit;
        }
    } catch (Throwable $e) {
        // Konfiguration vorhanden, aber Datenbank noch leer → Einrichtung fortsetzen
    }
}

/* ------------------------------------------------------ Voraussetzungen */

$requirements = [
    'PHP 8.0 oder neuer'          => PHP_VERSION_ID >= 80000,
    'PDO'                          => extension_loaded('pdo'),
    'SQLite oder MySQL für PDO'    => extension_loaded('pdo_sqlite') || extension_loaded('pdo_mysql'),
    'mbstring (Umlaute)'           => extension_loaded('mbstring'),
    'openssl (TLS für SMTP)'       => extension_loaded('openssl'),
    'Verzeichnis beschreibbar'     => is_writable(__DIR__),
];
$requirementsOk = !in_array(false, $requirements, true);

$errors  = [];
$values  = [
    'base_url'     => rtrim(dirname((string) ($_SERVER['REQUEST_URI'] ?? '')), '/'),
    'db_driver'    => extension_loaded('pdo_sqlite') ? 'sqlite' : 'mysql',
    'db_host'      => 'localhost',
    'db_name'      => '',
    'db_user'      => '',
    'db_pass'      => '',
    'admin_name'   => '',
    'admin_email'  => '',
    'brand_name'   => 'AcumenMail',
    'sender_name'  => '',
    'sender_email' => '',
    'imprint'      => '',
    'website_url'  => '',
];

// Basis-URL aus dem aktuellen Aufruf vorbelegen
$scheme = (($_SERVER['HTTPS'] ?? '') !== '' && ($_SERVER['HTTPS'] ?? '') !== 'off') ? 'https' : 'http';
$host   = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');
$values['base_url']    = $scheme . '://' . $host . rtrim(dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$values['website_url'] = $scheme . '://' . $host . '/';

if (Util::isPost()) {
    foreach (array_keys($values) as $key) {
        $values[$key] = Util::post($key, $values[$key]);
    }
    $values['db_pass']    = Util::postRaw('db_pass');
    $adminPassword        = Util::postRaw('admin_password');
    $values['base_url']   = rtrim($values['base_url'], '/');

    /* Eingaben prüfen */
    if (!preg_match('#^https?://[^\s]+$#i', $values['base_url'])) {
        $errors[] = 'Die Basis-URL muss mit http:// oder https:// beginnen.';
    }
    if (!Util::isEmail($values['admin_email'])) {
        $errors[] = 'Bitte geben Sie eine gültige Adresse für den Zugang an.';
    }
    if (Auth::passwordProblem($adminPassword) !== '') {
        $errors[] = Auth::passwordProblem($adminPassword);
    }
    if ($values['sender_email'] !== '' && !Util::isEmail($values['sender_email'])) {
        $errors[] = 'Die Absenderadresse ist ungültig.';
    }

    /* Datenbankzugang zusammenstellen und testen */
    // Ein angehängter Port ("server.example.de:3307") wird mit übernommen;
    // ohne Angabe gilt der MySQL-Standardport.
    $dbHost = $values['db_host'];
    $dbPort = 3306;
    if (preg_match('/^(.+):(\d{2,5})$/', $dbHost, $hostTeile)) {
        $dbHost = $hostTeile[1];
        $dbPort = (int) $hostTeile[2];
    }

    $dbConfig = $values['db_driver'] === 'mysql'
        ? [
            'driver' => 'mysql',
            'host'   => $dbHost,
            'port'   => $dbPort,
            'name'   => $values['db_name'],
            'user'   => $values['db_user'],
            'pass'   => $values['db_pass'],
        ]
        : [
            'driver' => 'sqlite',
            // Zufälliger Dateiname – erschwert das Erraten, falls das Verzeichnis
            // wider Erwarten doch einmal öffentlich erreichbar ist.
            'path'   => __DIR__ . '/data/newsletter-' . bin2hex(random_bytes(6)) . '.sqlite',
        ];

    if ($errors === []) {
        $dbError = DB::testConnection($dbConfig);
        if ($dbError !== '') {
            $errors[] = 'Datenbank: ' . $dbError;
        }
    }

    /* Einrichtung durchführen */
    if ($errors === []) {
        try {
            Config::write([
                'base_url'   => $values['base_url'],
                'secret'     => bin2hex(random_bytes(32)),
                'cron_token' => bin2hex(random_bytes(12)),
                'db'         => $dbConfig,
            ]);
            Config::load();
            DB::init($dbConfig);
            Schema::migrate();

            Settings::setMany([
                'brand_name'   => $values['brand_name'],
                'sender_name'  => $values['sender_name'] ?: $values['brand_name'],
                'sender_email' => $values['sender_email'],
                'reply_to'     => $values['sender_email'],
                'bounce_email' => $values['sender_email'],
                'contact_email' => $values['sender_email'],
                'website_url'  => $values['website_url'],
                'imprint'      => $values['imprint'],
                'installed_at' => Util::now(),
            ]);

            Lists::ensureDefault('Newsletter');
            Templates::ensureDefaults();
            Auth::createUser($values['admin_email'], $adminPassword, $values['admin_name']);

            // Schutzdateien anlegen (Apache)
            @file_put_contents(__DIR__ . '/data/.htaccess',
                "# Datenverzeichnis komplett sperren\nRequire all denied\n<IfModule !mod_authz_core.c>\n    Order allow,deny\n    Deny from all\n</IfModule>\n");
            @file_put_contents(__DIR__ . '/data/index.html', '');

            Log::info('install', 'Einrichtung abgeschlossen.');

            header('Content-Type: text/html; charset=utf-8');
            echo '<!DOCTYPE html><html lang="de"><head><meta charset="utf-8"><title>Fertig</title>'
               . '<link rel="stylesheet" href="admin/assets/admin.css"></head><body>'
               . '<div class="ad-login-wrap"><div class="ad-login">'
               . '<h1>Einrichtung abgeschlossen</h1>'
               . '<p class="ad-sub">Ihr eigenes Newslettersystem ist startklar.</p>'
               . '<div class="ad-flash ad-flash-warning"><strong>Bitte jetzt:</strong> Löschen Sie die Datei '
               . '<code>install.php</code> vom Server.</div>'
               . '<div class="ad-flash ad-flash-info">Nächste Schritte: Versandweg (SMTP) in den Einstellungen '
               . 'hinterlegen und den Cron-Job einrichten – beides ist dort beschrieben.</div>'
               . '<a class="ad-btn" href="admin/login.php">Zur Anmeldung</a>'
               . '</div></div></body></html>';
            exit;
        } catch (Throwable $e) {
            $errors[] = 'Einrichtung fehlgeschlagen: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Newslettersystem einrichten</title>
<link rel="stylesheet" href="admin/assets/admin.css">
</head>
<body>
<header class="ad-topbar">
    <span class="ad-brand"><span class="ad-brand-mark">A</span><span>Newslettersystem <em>Einrichtung</em></span></span>
</header>

<div class="ad-main" style="max-width:820px;margin:0 auto;">
    <h1>Einrichtung</h1>
    <p class="ad-sub">Ein paar Angaben – danach können Sie sofort loslegen.</p>

    <?php foreach ($errors as $error): ?>
        <div class="ad-flash ad-flash-error"><?= Util::e($error) ?></div>
    <?php endforeach; ?>

    <div class="ad-card">
        <h2>Voraussetzungen</h2>
        <table class="ad-table">
            <?php foreach ($requirements as $label => $ok): ?>
                <tr>
                    <td><?= Util::e($label) ?></td>
                    <td style="width:120px;">
                        <span class="ad-pill <?= $ok ? 'ad-pill-green' : 'ad-pill-red' ?>"><?= $ok ? 'in Ordnung' : 'fehlt' ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php if (!$requirementsOk): ?>
            <p class="ad-hint">Bitte klären Sie die fehlenden Punkte mit Ihrem Hoster, bevor Sie fortfahren.</p>
        <?php endif; ?>
        <p class="ad-hint">Etwas funktioniert nicht wie erwartet?
            <a href="systemcheck.php">Systemcheck öffnen</a> – der prüft zusätzlich Schreibrechte und ob alle
            Dateien vollständig hochgeladen wurden.</p>
    </div>

    <form method="post">
        <div class="ad-card">
            <h2>Adresse des Systems</h2>
            <div class="ad-field">
                <label for="base_url">Basis-URL des Newsletter-Ordners</label>
                <input type="text" id="base_url" name="base_url" value="<?= Util::e($values['base_url']) ?>" required>
                <p class="ad-hint">Ohne Schrägstrich am Ende. Bestätigungs- und Abmeldelinks bauen darauf auf.</p>
            </div>
            <div class="ad-field">
                <label for="website_url">Adresse Ihrer Website</label>
                <input type="text" id="website_url" name="website_url" value="<?= Util::e($values['website_url']) ?>">
            </div>
        </div>

        <div class="ad-card">
            <h2>Datenbank</h2>
            <div class="ad-field">
                <label for="db_driver">Art der Datenbank</label>
                <select id="db_driver" name="db_driver">
                    <option value="sqlite" <?= $values['db_driver'] === 'sqlite' ? 'selected' : '' ?>>SQLite – Datei auf dem Server (einfachster Weg)</option>
                    <option value="mysql" <?= $values['db_driver'] === 'mysql' ? 'selected' : '' ?>>MySQL / MariaDB</option>
                </select>
                <p class="ad-hint">SQLite genügt für einige zehntausend Empfänger und braucht keine Einrichtung.
                    Bei sehr großen Listen oder parallelem Zugriff ist MySQL die bessere Wahl.</p>
            </div>
            <div class="ad-row">
                <div class="ad-field">
                    <label for="db_host">MySQL-Server</label>
                    <input type="text" id="db_host" name="db_host" value="<?= Util::e($values['db_host']) ?>">
                </div>
                <div class="ad-field">
                    <label for="db_name">Datenbankname</label>
                    <input type="text" id="db_name" name="db_name" value="<?= Util::e($values['db_name']) ?>">
                </div>
                <div class="ad-field">
                    <label for="db_user">Benutzer</label>
                    <input type="text" id="db_user" name="db_user" value="<?= Util::e($values['db_user']) ?>" autocomplete="off">
                </div>
                <div class="ad-field">
                    <label for="db_pass">Passwort</label>
                    <input type="password" id="db_pass" name="db_pass" autocomplete="new-password">
                </div>
            </div>
        </div>

        <div class="ad-card">
            <h2>Ihr Zugang</h2>
            <div class="ad-row">
                <div class="ad-field">
                    <label for="admin_name">Name</label>
                    <input type="text" id="admin_name" name="admin_name" value="<?= Util::e($values['admin_name']) ?>">
                </div>
                <div class="ad-field">
                    <label for="admin_email">E-Mail-Adresse</label>
                    <input type="email" id="admin_email" name="admin_email" value="<?= Util::e($values['admin_email']) ?>" required>
                </div>
                <div class="ad-field">
                    <label for="admin_password">Passwort</label>
                    <input type="password" id="admin_password" name="admin_password" required autocomplete="new-password">
                    <p class="ad-hint">Mindestens 10 Zeichen, Buchstaben und Ziffern.</p>
                </div>
            </div>
        </div>

        <div class="ad-card">
            <h2>Absender</h2>
            <div class="ad-row">
                <div class="ad-field">
                    <label for="brand_name">Markenname</label>
                    <input type="text" id="brand_name" name="brand_name" value="<?= Util::e($values['brand_name']) ?>">
                </div>
                <div class="ad-field">
                    <label for="sender_name">Absendername</label>
                    <input type="text" id="sender_name" name="sender_name" value="<?= Util::e($values['sender_name']) ?>">
                </div>
                <div class="ad-field">
                    <label for="sender_email">Absenderadresse</label>
                    <input type="email" id="sender_email" name="sender_email" value="<?= Util::e($values['sender_email']) ?>"
                           placeholder="newsletter@ihre-domain.de">
                </div>
            </div>
            <div class="ad-field">
                <label for="imprint">Pflichtangaben für den Mail-Footer</label>
                <textarea id="imprint" name="imprint" rows="3"
                          placeholder="Vorname Nachname · Straße 1 · 12345 Ort · info@ihre-domain.de"><?= Util::e($values['imprint']) ?></textarea>
            </div>
        </div>

        <button type="submit" class="ad-btn" <?= $requirementsOk ? '' : 'disabled' ?>>Jetzt einrichten</button>
    </form>
</div>
</body>
</html>
