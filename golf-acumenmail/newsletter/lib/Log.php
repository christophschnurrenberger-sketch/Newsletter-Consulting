<?php
/**
 * Log – technisches Protokoll (Versand, Cron, Fehler).
 * Bewusst schlicht: eine Tabelle, automatische Bereinigung nach 90 Tagen.
 */
final class Log
{
    public static function write(string $level, string $channel, string $message): void
    {
        try {
            DB::insert('logs', [
                'level'      => $level,
                'channel'    => $channel,
                'message'    => mb_substr($message, 0, 4000),
                'created_at' => Util::now(),
            ]);
        } catch (Throwable $e) {
            // Protokollieren darf niemals den eigentlichen Vorgang stoppen.
        }
        if (Util::isCli()) {
            fwrite($level === 'error' ? STDERR : STDOUT,
                '[' . date('H:i:s') . '] ' . strtoupper($level) . ' ' . $channel . ': ' . $message . PHP_EOL);
        }
    }

    public static function info(string $channel, string $message): void
    {
        self::write('info', $channel, $message);
    }

    public static function warn(string $channel, string $message): void
    {
        self::write('warning', $channel, $message);
    }

    public static function error(string $channel, string $message): void
    {
        self::write('error', $channel, $message);
    }

    /** Alte Einträge entfernen (wird vom Cron aufgerufen). */
    public static function prune(int $days = 90): int
    {
        return DB::delete('logs', 'created_at < ?', [date('Y-m-d H:i:s', time() - $days * 86400)]);
    }
}
