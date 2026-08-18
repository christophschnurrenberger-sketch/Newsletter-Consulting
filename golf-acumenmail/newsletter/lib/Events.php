<?php
/**
 * Events – Ereignisse rund um den Versand: gesendet, geöffnet, geklickt,
 * abgemeldet, Bounce, Fehler. Grundlage aller Statistiken.
 */
final class Events
{
    public const SENT        = 'sent';
    public const OPEN        = 'open';
    public const CLICK       = 'click';
    public const UNSUBSCRIBE = 'unsubscribe';
    public const BOUNCE      = 'bounce';
    public const COMPLAINT   = 'complaint';
    public const FAILED      = 'failed';

    /** @param array<string,mixed> $data */
    public static function record(string $type, array $data = []): void
    {
        DB::insert('events', [
            'type'          => $type,
            'campaign_id'   => $data['campaign_id']   ?? null,
            'step_id'       => $data['step_id']       ?? null,
            'subscriber_id' => $data['subscriber_id'] ?? null,
            'link_id'       => $data['link_id']       ?? null,
            'queue_id'      => $data['queue_id']      ?? null,
            'detail'        => isset($data['detail']) ? mb_substr((string) $data['detail'], 0, 1000) : null,
            'ip'            => $data['ip']         ?? Util::storeIp(),
            'user_agent'    => $data['user_agent'] ?? Util::userAgent(),
            'created_at'    => Util::now(),
        ]);
    }

    /**
     * Ereignis nur einmal je Empfänger und Kampagne zählen
     * (z. B. eindeutige Öffnungen). Gibt true zurück, wenn neu.
     */
    public static function recordUnique(string $type, array $data): bool
    {
        $sql    = 'SELECT COUNT(*) FROM events WHERE type = ? AND queue_id = ?';
        $params = [$type, (int) ($data['queue_id'] ?? 0)];
        if (!empty($data['link_id'])) {
            $sql     .= ' AND link_id = ?';
            $params[] = (int) $data['link_id'];
        }
        $exists = (int) DB::value($sql, $params);
        self::record($type, $data);
        return $exists === 0;
    }

    /** Anzahl je Ereignistyp für eine Kampagne. */
    public static function countFor(int $campaignId, string $type, bool $unique = false): int
    {
        if ($unique) {
            return (int) DB::value(
                'SELECT COUNT(DISTINCT COALESCE(subscriber_id, queue_id)) FROM events WHERE campaign_id = ? AND type = ?',
                [$campaignId, $type]
            );
        }
        return (int) DB::value(
            'SELECT COUNT(*) FROM events WHERE campaign_id = ? AND type = ?',
            [$campaignId, $type]
        );
    }

    /** Aktivitäten eines Empfängers (für die Detailansicht). */
    public static function forSubscriber(int $subscriberId, int $limit = 50): array
    {
        return DB::all(
            'SELECT e.*, c.name AS campaign_name
             FROM events e
             LEFT JOIN campaigns c ON c.id = e.campaign_id
             WHERE e.subscriber_id = ?
             ORDER BY e.id DESC
             LIMIT ' . max(1, $limit),
            [$subscriberId]
        );
    }

    /** @return array<string,string> */
    public static function labels(): array
    {
        return [
            self::SENT        => 'Versendet',
            self::OPEN        => 'Geöffnet',
            self::CLICK       => 'Klick',
            self::UNSUBSCRIBE => 'Abmeldung',
            self::BOUNCE      => 'Unzustellbar',
            self::COMPLAINT   => 'Beschwerde',
            self::FAILED      => 'Fehlgeschlagen',
        ];
    }

    public static function label(string $type): string
    {
        return self::labels()[$type] ?? $type;
    }
}
