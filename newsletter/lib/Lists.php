<?php
/**
 * Lists – Verteiler. Ein Empfänger kann in mehreren Listen stehen;
 * eine Kampagne geht an genau eine Liste (oder an alle aktiven Empfänger).
 */
final class Lists
{
    /** @return array<int,array<string,mixed>> */
    public static function all(): array
    {
        return DB::all('SELECT * FROM lists ORDER BY is_default DESC, name');
    }

    public static function byId(int $id): ?array
    {
        return DB::row('SELECT * FROM lists WHERE id = ?', [$id]);
    }

    public static function name(?int $id): string
    {
        if ($id === null || $id <= 0) {
            return 'Alle aktiven Empfänger';
        }
        $list = self::byId($id);
        return $list === null ? 'Gelöschte Liste' : (string) $list['name'];
    }

    public static function defaultId(): int
    {
        $id = (int) DB::value('SELECT id FROM lists WHERE is_default = 1 ORDER BY id LIMIT 1', [], 0);
        if ($id === 0) {
            $id = (int) DB::value('SELECT id FROM lists ORDER BY id LIMIT 1', [], 0);
        }
        return $id;
    }

    public static function create(string $name, string $description = '', bool $isDefault = false): int
    {
        $id = DB::insert('lists', [
            'name'        => mb_substr(trim($name), 0, 190),
            'description' => mb_substr(trim($description), 0, 1000),
            'is_default'  => $isDefault ? 1 : 0,
            'created_at'  => Util::now(),
        ]);
        if ($isDefault) {
            self::makeDefault($id);
        }
        return $id;
    }

    public static function update(int $id, string $name, string $description): void
    {
        DB::update('lists', [
            'name'        => mb_substr(trim($name), 0, 190),
            'description' => mb_substr(trim($description), 0, 1000),
        ], 'id = ?', [$id]);
    }

    public static function makeDefault(int $id): void
    {
        DB::run('UPDATE lists SET is_default = 0');
        DB::update('lists', ['is_default' => 1], 'id = ?', [$id]);
    }

    /** Löscht eine Liste; die Empfänger selbst bleiben erhalten. */
    public static function delete(int $id): void
    {
        DB::transaction(static function () use ($id) {
            DB::delete('subscriber_lists', 'list_id = ?', [$id]);
            DB::run('UPDATE campaigns SET list_id = NULL WHERE list_id = ?', [$id]);
            DB::run('UPDATE automations SET list_id = NULL WHERE list_id = ?', [$id]);
            DB::delete('lists', 'id = ?', [$id]);
        });
    }

    /** @return array<int,int> Anzahl aktiver Empfänger je Liste */
    public static function activeCounts(): array
    {
        $rows = DB::all(
            "SELECT sl.list_id, COUNT(*) AS anzahl
             FROM subscriber_lists sl
             JOIN subscribers s ON s.id = sl.subscriber_id
             WHERE s.status = 'active'
             GROUP BY sl.list_id"
        );
        $out = [];
        foreach ($rows as $row) {
            $out[(int) $row['list_id']] = (int) $row['anzahl'];
        }
        return $out;
    }

    /** Legt beim ersten Start eine Standardliste an. */
    public static function ensureDefault(string $name = 'Newsletter'): int
    {
        $id = self::defaultId();
        return $id > 0 ? $id : self::create($name, 'Standard-Verteiler für den Newsletter.', true);
    }
}
