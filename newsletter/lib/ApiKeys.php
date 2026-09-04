<?php
/**
 * ApiKeys – Schlüssel für die Schnittstelle (API).
 *
 * Ein Schlüssel wird beim Anlegen einmal im Klartext gezeigt und danach nur
 * noch als SHA-256-Hash gespeichert – wie bei GitHub-Tokens. Geht er verloren,
 * legt man einen neuen an; einsehen kann man ihn nicht mehr.
 *
 * „scope" steuert die Rechte:
 *   read  – nur lesen (GET)
 *   write – lesen und schreiben (GET, POST, DELETE)
 *
 * Jede Instanz hat ihre eigenen Schlüssel (eigene Datenbank) – ein Schlüssel
 * der einen Installation öffnet also keine andere.
 */
final class ApiKeys
{
    public const SCOPE_READ  = 'read';
    public const SCOPE_WRITE = 'write';

    private const PREFIX = 'acm_';

    /** @return array<int,array<string,mixed>> ohne Geheimnisse */
    public static function all(): array
    {
        return DB::all('SELECT id, label, prefix, scope, active, created_by, created_at, last_used_at
                        FROM api_keys ORDER BY id DESC');
    }

    public static function byId(int $id): ?array
    {
        return DB::row('SELECT * FROM api_keys WHERE id = ?', [$id]);
    }

    public static function count(): int
    {
        return (int) DB::value('SELECT COUNT(*) FROM api_keys WHERE active = 1');
    }

    /**
     * Legt einen Schlüssel an und gibt ihn EINMAL im Klartext zurück.
     *
     * @return array{key:string,row:array<string,mixed>}
     */
    public static function create(string $label, string $scope, string $by = ''): array
    {
        $scope = $scope === self::SCOPE_WRITE ? self::SCOPE_WRITE : self::SCOPE_READ;
        $key   = self::PREFIX . bin2hex(random_bytes(24));   // 4 + 48 Zeichen
        $id    = DB::insert('api_keys', [
            'label'      => mb_substr(trim($label), 0, 120) ?: 'Schlüssel',
            'key_hash'   => self::hash($key),
            'prefix'     => substr($key, 0, 12),
            'scope'      => $scope,
            'active'     => 1,
            'created_by' => mb_substr($by, 0, 190),
            'created_at' => Util::now(),
        ]);
        Log::info('api', 'API-Schlüssel angelegt (#' . $id . ', ' . $scope . ').');
        return ['key' => $key, 'row' => (array) self::byId($id)];
    }

    public static function revoke(int $id): void
    {
        DB::update('api_keys', ['active' => 0], 'id = ?', [$id]);
        Log::info('api', 'API-Schlüssel #' . $id . ' widerrufen.');
    }

    public static function delete(int $id): void
    {
        DB::delete('api_keys', 'id = ?', [$id]);
    }

    /**
     * Prüft einen vorgelegten Schlüssel und gibt bei Erfolg die Zeile zurück
     * (samt scope), sonst null. Merkt sich den Zeitpunkt der Nutzung.
     */
    public static function verify(string $presented): ?array
    {
        $presented = trim($presented);
        if ($presented === '' || strncmp($presented, self::PREFIX, strlen(self::PREFIX)) !== 0) {
            return null;
        }
        $row = DB::row('SELECT * FROM api_keys WHERE key_hash = ? AND active = 1', [self::hash($presented)]);
        if ($row === null) {
            return null;
        }
        // Nutzung vermerken – aber nicht bei jedem Aufruf schreiben.
        $letzte = (string) ($row['last_used_at'] ?? '');
        if ($letzte === '' || strtotime($letzte) < time() - 60) {
            DB::update('api_keys', ['last_used_at' => Util::now()], 'id = ?', [(int) $row['id']]);
        }
        return $row;
    }

    public static function canWrite(array $key): bool
    {
        return ($key['scope'] ?? self::SCOPE_READ) === self::SCOPE_WRITE;
    }

    public static function scopeLabel(string $scope): string
    {
        return $scope === self::SCOPE_WRITE ? 'Lesen & Schreiben' : 'Nur Lesen';
    }

    private static function hash(string $key): string
    {
        return hash('sha256', $key);
    }
}
