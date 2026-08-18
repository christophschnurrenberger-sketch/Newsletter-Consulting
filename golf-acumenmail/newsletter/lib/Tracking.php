<?php
/**
 * Tracking – Öffnungen und Klicks.
 *
 * Jede versendete Mail hat ein eigenes Token (Spalte queue.token). Damit
 * lassen sich Zählpixel und Klick-Links eindeutig zuordnen, ohne dass die
 * E-Mail-Adresse in der URL steht.
 */
final class Tracking
{
    /** 1x1-Pixel (transparentes GIF) */
    public const PIXEL = "\x47\x49\x46\x38\x39\x61\x01\x00\x01\x00\x80\x00\x00\x00\x00\x00\xff\xff\xff\x21\xf9\x04\x01\x00\x00\x00\x00\x2c\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02\x44\x01\x00\x3b";

    /** @return array<string,mixed>|null */
    public static function queueByToken(string $token): ?array
    {
        if ($token === '' || strlen($token) > 64) {
            return null;
        }
        return DB::row('SELECT * FROM queue WHERE token = ?', [$token]);
    }

    /** Öffnung zählen (nur bei tatsächlich versendeten Mails). */
    public static function recordOpen(string $token): void
    {
        $row = self::queueByToken($token);
        if ($row === null || $row['status'] !== Queue::SENT) {
            return;
        }
        if (self::looksLikeBot()) {
            return;
        }
        Events::record(Events::OPEN, [
            'campaign_id'   => $row['campaign_id'] !== null ? (int) $row['campaign_id'] : null,
            'step_id'       => $row['step_id'] !== null ? (int) $row['step_id'] : null,
            'subscriber_id' => (int) $row['subscriber_id'],
            'queue_id'      => (int) $row['id'],
        ]);
    }

    /**
     * Klick zählen und Ziel-URL zurückgeben.
     * @return string|null null = Link unbekannt
     */
    public static function recordClick(string $token, int $linkId): ?string
    {
        $link = DB::row('SELECT * FROM links WHERE id = ?', [$linkId]);
        if ($link === null) {
            return null;
        }
        $url = (string) $link['url'];
        if (!preg_match('#^https?://#i', $url)) {
            return null; // niemals auf andere Schemata weiterleiten
        }

        $row = self::queueByToken($token);
        if ($row !== null && $row['status'] === Queue::SENT && !self::looksLikeBot()) {
            // Der Link muss zur versendeten Mail gehören
            $matchesCampaign = $link['campaign_id'] !== null
                && (int) $link['campaign_id'] === (int) ($row['campaign_id'] ?? 0);
            $matchesStep = $link['step_id'] !== null
                && (int) $link['step_id'] === (int) ($row['step_id'] ?? 0);

            if ($matchesCampaign || $matchesStep) {
                Events::record(Events::CLICK, [
                    'campaign_id'   => $row['campaign_id'] !== null ? (int) $row['campaign_id'] : null,
                    'step_id'       => $row['step_id'] !== null ? (int) $row['step_id'] : null,
                    'subscriber_id' => (int) $row['subscriber_id'],
                    'queue_id'      => (int) $row['id'],
                    'link_id'       => $linkId,
                ]);
                // Ein Klick beweist, dass die Mail geöffnet wurde
                $hasOpen = (int) DB::value(
                    "SELECT COUNT(*) FROM events WHERE type = 'open' AND queue_id = ?",
                    [(int) $row['id']]
                );
                if ($hasOpen === 0) {
                    Events::record(Events::OPEN, [
                        'campaign_id'   => $row['campaign_id'] !== null ? (int) $row['campaign_id'] : null,
                        'step_id'       => $row['step_id'] !== null ? (int) $row['step_id'] : null,
                        'subscriber_id' => (int) $row['subscriber_id'],
                        'queue_id'      => (int) $row['id'],
                        'detail'        => 'aus Klick abgeleitet',
                    ]);
                }
            }
        }
        return $url;
    }

    /** Grobe Bot-Erkennung, damit Sicherheitsscanner die Zahlen nicht verfälschen. */
    private static function looksLikeBot(): bool
    {
        $agent = mb_strtolower(Util::userAgent());
        if ($agent === '') {
            return false;
        }
        foreach (['bot', 'crawler', 'spider', 'preview', 'monitoring', 'pingdom', 'uptime',
                  'python-requests', 'curl/', 'wget', 'headlesschrome'] as $needle) {
            if (str_contains($agent, $needle)) {
                return true;
            }
        }
        return false;
    }

    /** Zählpixel ausliefern (immer, auch bei unbekanntem Token). */
    public static function outputPixel(): void
    {
        header('Content-Type: image/gif');
        header('Content-Length: ' . strlen(self::PIXEL));
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        echo self::PIXEL;
        exit;
    }
}
