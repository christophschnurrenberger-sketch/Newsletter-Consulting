<?php
/**
 * Beispiel-Konfiguration.
 *
 * Normalerweise wird config.php automatisch von install.php erzeugt.
 * Diese Datei zeigt nur, welche Werte darin stehen – etwa zum Umziehen
 * auf einen anderen Server oder für ein Backup.
 *
 * Alle weiteren Einstellungen (Absender, SMTP-Zugang, Versandtempo,
 * Texte) liegen in der Datenbank und werden im Admin-Bereich gepflegt.
 */

return [
    // Adresse des Newsletter-Ordners, ohne Schrägstrich am Ende.
    // Darauf bauen alle Bestätigungs-, Abmelde- und Zähl-Links auf.
    'base_url' => 'https://www.newsletter-consulting.de/newsletter',

    // Zufälliger Schlüssel für Signaturen und die Verschlüsselung der
    // Zugangsdaten. NIEMALS ändern, solange Daten im System sind –
    // sonst werden alle Abmeldelinks ungültig und gespeicherte
    // SMTP-Passwörter unlesbar. Erzeugen mit:
    //     php -r "echo bin2hex(random_bytes(32));"
    'secret' => 'HIER_EINEN_ZUFALLSWERT_EINSETZEN',

    // Schlüssel für den Aufruf der Cron-Skripte per URL.
    'cron_token' => 'HIER_EINEN_ZWEITEN_ZUFALLSWERT_EINSETZEN',

    // Datenbank – entweder SQLite (Datei) …
    'db' => [
        'driver' => 'sqlite',
        'path'   => __DIR__ . '/data/newsletter.sqlite',
    ],

    // … oder MySQL/MariaDB:
    // 'db' => [
    //     'driver' => 'mysql',
    //     'host'   => 'localhost',
    //     'port'   => 3306,
    //     'name'   => 'db1234567',
    //     'user'   => 'dbo1234567',
    //     'pass'   => 'geheim',
    // ],
];
