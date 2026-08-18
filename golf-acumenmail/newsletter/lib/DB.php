<?php
/**
 * DB – schlanker PDO-Wrapper für SQLite (Standard) und MySQL/MariaDB.
 *
 * Alle Zeitstempel werden als Text 'Y-m-d H:i:s' gespeichert; damit
 * funktionieren Vergleiche und Sortierung in beiden Datenbanken gleich.
 */
final class DB
{
    private static ?PDO $pdo = null;
    private static string $driver = 'sqlite';

    public static function init(?array $cfg = null): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }
        $cfg = $cfg ?? (array) Config::get('db', []);
        $driver = (string) ($cfg['driver'] ?? 'sqlite');
        self::$driver = $driver === 'mysql' ? 'mysql' : 'sqlite';

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        if (self::$driver === 'mysql') {
            $host = (string) ($cfg['host'] ?? 'localhost');
            $port = (int) ($cfg['port'] ?? 3306);
            $name = (string) ($cfg['name'] ?? '');
            $dsn  = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
            self::$pdo = new PDO($dsn, (string) ($cfg['user'] ?? ''), (string) ($cfg['pass'] ?? ''), $options);
            self::$pdo->exec("SET sql_mode = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION'");
        } else {
            $path = (string) ($cfg['path'] ?? (NL_ROOT . '/data/newsletter.sqlite'));
            $dir  = dirname($path);
            if (!is_dir($dir)) {
                @mkdir($dir, 0750, true);
            }
            self::$pdo = new PDO('sqlite:' . $path, null, null, $options);
            self::$pdo->exec('PRAGMA journal_mode = WAL');
            self::$pdo->exec('PRAGMA busy_timeout = 10000');
            self::$pdo->exec('PRAGMA foreign_keys = ON');
        }
        return self::$pdo;
    }

    public static function pdo(): PDO
    {
        return self::$pdo ?? self::init();
    }

    public static function driver(): string
    {
        return self::$driver;
    }

    public static function isSqlite(): bool
    {
        return self::$driver === 'sqlite';
    }

    /* ------------------------------------------------------------ Abfragen */

    public static function run(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /** @return array<int,array<string,mixed>> */
    public static function all(string $sql, array $params = []): array
    {
        return self::run($sql, $params)->fetchAll();
    }

    /** @return array<string,mixed>|null */
    public static function row(string $sql, array $params = []): ?array
    {
        $row = self::run($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    /** Erste Spalte der ersten Zeile. */
    public static function value(string $sql, array $params = [], $default = null)
    {
        $value = self::run($sql, $params)->fetchColumn();
        return $value === false ? $default : $value;
    }

    /** Eine Spalte als flache Liste. */
    public static function column(string $sql, array $params = []): array
    {
        return self::run($sql, $params)->fetchAll(PDO::FETCH_COLUMN);
    }

    /** Zwei Spalten als key => value. */
    public static function pairs(string $sql, array $params = []): array
    {
        $out = [];
        foreach (self::all($sql, $params) as $row) {
            $values = array_values($row);
            $out[(string) $values[0]] = $values[1] ?? null;
        }
        return $out;
    }

    /* ------------------------------------------------------------ Schreiben */

    public static function insert(string $table, array $data): int
    {
        $cols = array_keys($data);
        $sql  = 'INSERT INTO ' . $table . ' (' . implode(', ', $cols) . ') VALUES ('
              . implode(', ', array_fill(0, count($cols), '?')) . ')';
        self::run($sql, array_values($data));
        return (int) self::pdo()->lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $params = []): int
    {
        $set = [];
        foreach (array_keys($data) as $col) {
            $set[] = $col . ' = ?';
        }
        $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $set) . ' WHERE ' . $where;
        return self::run($sql, array_merge(array_values($data), $params))->rowCount();
    }

    public static function delete(string $table, string $where, array $params = []): int
    {
        return self::run('DELETE FROM ' . $table . ' WHERE ' . $where, $params)->rowCount();
    }

    /* ------------------------------------------------------------ Transaktion */

    public static function transaction(callable $fn)
    {
        $pdo = self::pdo();
        $own = !$pdo->inTransaction();
        if ($own) {
            $pdo->beginTransaction();
        }
        try {
            $result = $fn();
            if ($own) {
                $pdo->commit();
            }
            return $result;
        } catch (Throwable $e) {
            if ($own && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    /** Testet die Verbindung mit gegebenen Zugangsdaten (Installer). */
    public static function testConnection(array $cfg): string
    {
        try {
            if (($cfg['driver'] ?? 'sqlite') === 'mysql') {
                $dsn = 'mysql:host=' . ($cfg['host'] ?? 'localhost')
                     . ';port=' . ($cfg['port'] ?? 3306)
                     . ';dbname=' . ($cfg['name'] ?? '') . ';charset=utf8mb4';
                new PDO($dsn, (string) ($cfg['user'] ?? ''), (string) ($cfg['pass'] ?? ''), [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
            } else {
                $path = (string) ($cfg['path'] ?? '');
                $dir  = dirname($path);
                if (!is_dir($dir) && !@mkdir($dir, 0750, true)) {
                    return 'Verzeichnis ' . $dir . ' konnte nicht angelegt werden.';
                }
                if (!is_writable($dir)) {
                    return 'Verzeichnis ' . $dir . ' ist nicht beschreibbar.';
                }
                new PDO('sqlite:' . $path, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            }
            return '';
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }
}
