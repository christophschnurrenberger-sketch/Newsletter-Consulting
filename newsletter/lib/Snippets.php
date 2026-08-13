<?php
/**
 * Snippets – selbst gesicherte Bausteine.
 *
 * Ein Absender-Gruß, ein Produktkasten, eine Grußformel: Was man einmal
 * gebaut hat, soll man wiederverwenden können, statt es jedes Mal neu
 * zusammenzuklicken. Gespeichert wird der Baustein so, wie ihn der
 * Baukasten kennt – beim Einsetzen bekommt er nur neue Kennungen, damit
 * zwei Kopien nicht dieselbe haben.
 */
final class Snippets
{
    /** Mehr als das wird unübersichtlich – und bremst die Palette. */
    public const MAX = 60;

    /** @return array<int,array<string,mixed>> */
    public static function all(): array
    {
        return DB::all('SELECT id, name, kind, created_by, created_at, used_at
                        FROM snippets ORDER BY name COLLATE NOCASE');
    }

    public static function byId(int $id): ?array
    {
        return DB::row('SELECT * FROM snippets WHERE id = ?', [$id]);
    }

    public static function count(): int
    {
        return (int) DB::value('SELECT COUNT(*) FROM snippets');
    }

    /**
     * Sichert einen Baustein (oder mehrere) unter einem Namen.
     *
     * @param string $json  ein Baustein oder eine Liste von Bausteinen als JSON
     * @return int Kennung des gesicherten Bausteins
     */
    public static function save(string $name, string $json, string $wer = ''): int
    {
        $name = mb_substr(trim($name), 0, 190);
        if ($name === '') {
            throw new InvalidArgumentException('Bitte geben Sie dem Baustein einen Namen.');
        }
        if (self::count() >= self::MAX) {
            throw new RuntimeException('Es sind bereits ' . self::MAX . ' Bausteine gesichert. '
                . 'Bitte löschen Sie zuerst einen davon.');
        }

        $bausteine = self::clean($json);
        if ($bausteine === []) {
            throw new InvalidArgumentException('Dieser Baustein lässt sich nicht sichern.');
        }
        if (DB::value('SELECT COUNT(*) FROM snippets WHERE name = ?', [$name]) > 0) {
            throw new InvalidArgumentException('Ein Baustein mit diesem Namen ist schon gesichert.');
        }

        return DB::insert('snippets', [
            'name'        => $name,
            'kind'        => count($bausteine) === 1 ? (string) $bausteine[0]['type'] : 'gruppe',
            'blocks_json' => (string) json_encode($bausteine, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'created_by'  => mb_substr($wer, 0, 190),
            'created_at'  => Util::now(),
        ]);
    }

    /**
     * Liefert die Bausteine zum Einsetzen – mit frischen Kennungen.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function blocks(int $id): array
    {
        $eintrag = self::byId($id);
        if ($eintrag === null) {
            return [];
        }
        DB::update('snippets', ['used_at' => Util::now()], 'id = ?', [$id]);
        return self::freshIds(self::clean((string) $eintrag['blocks_json']));
    }

    public static function delete(int $id): bool
    {
        return DB::delete('snippets', 'id = ?', [$id]) > 0;
    }

    /**
     * Prüft und säubert die Bausteine – gespeichert wird nur, was der
     * Baukasten auch versteht. Damit kommt über diesen Weg kein fremdes
     * HTML ins System, das die übliche Prüfung umgeht.
     *
     * @return array<int,array<string,mixed>>
     */
    private static function clean(string $json): array
    {
        $roh = json_decode(trim($json), true);
        if (!is_array($roh)) {
            return [];
        }
        // Einzelner Baustein oder Liste – beides ist erlaubt
        if (isset($roh['type'])) {
            $roh = [$roh];
        }
        $stand = Blocks::parse((string) json_encode(['meta' => [], 'blocks' => $roh],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return $stand['blocks'];
    }

    /**
     * Neue Kennungen für den Baustein und alles, was in ihm steckt.
     *
     * @param array<int,array<string,mixed>> $bausteine
     * @return array<int,array<string,mixed>>
     */
    private static function freshIds(array $bausteine): array
    {
        foreach ($bausteine as $i => $block) {
            $bausteine[$i]['id'] = Blocks::newId();
            foreach (['left', 'right'] as $seite) {
                if (isset($block[$seite]) && is_array($block[$seite])) {
                    $bausteine[$i][$seite] = self::freshIds($block[$seite]);
                }
            }
        }
        return $bausteine;
    }
}
