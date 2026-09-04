<?php
/**
 * Announcements – Mehrkanal-Meldungen.
 *
 * Der Gedanke: Ein Ereignis („Platz gesperrt", „Turnier verschoben", …) hat
 * einen Text und wird über mehrere Kanäle zu verschiedenen Zeiten ausgespielt:
 *
 *     Platz gesperrt
 *       08:00  → SMS
 *       09:00  → Website
 *       11:00  → Newsletter (E-Mail)
 *
 * Jede Meldung besteht aus einem gemeinsamen Text und beliebig vielen
 * Kanal-Einträgen mit eigenem Zeitpunkt. Ist die Meldung „scharf" (active),
 * spielt der Cron jeden Kanal aus, sobald seine Zeit erreicht ist.
 *
 * Kanäle in diesem Ausbau:
 *   – E-Mail   : voll funktionsfähig – erzeugt und versendet eine Kampagne.
 *   – Website  : voll funktionsfähig – erscheint auf der öffentlichen Seite
 *                „Aktuelles" (aktuelles.php) und im einbettbaren Feed.
 *   – SMS/WhatsApp/Push : anschlussbereit. Anbieter/Zugangsdaten lassen sich
 *                hinterlegen; der eigentliche Live-Versand wird angebunden,
 *                sobald ein Anbieter feststeht. Bis dahin melden sie sauber,
 *                dass der Kanal noch nicht eingerichtet ist.
 */
final class Announcements
{
    public const DRAFT  = 'draft';
    public const ACTIVE = 'active';
    public const DONE   = 'done';

    /* Kanal-Status */
    public const PENDING = 'pending';
    public const SENT    = 'sent';
    public const FAILED  = 'failed';
    public const SKIPPED = 'skipped';

    public const CHANNELS = ['email', 'website', 'sms', 'whatsapp', 'push'];

    /** @return array<string,array{label:string,live:bool}> */
    public static function channelMeta(): array
    {
        return [
            'email'    => ['label' => 'E-Mail',   'live' => true],
            'website'  => ['label' => 'Website',  'live' => true],
            'sms'      => ['label' => 'SMS',      'live' => false],
            'whatsapp' => ['label' => 'WhatsApp', 'live' => false],
            'push'     => ['label' => 'Push',     'live' => false],
        ];
    }

    public static function channelLabel(string $channel): string
    {
        return self::channelMeta()[$channel]['label'] ?? $channel;
    }

    /** Rubriken einer Meldung – steuern die Optik auf der öffentlichen Seite. */
    public static function categoryMeta(): array
    {
        return [
            'info'     => ['label' => 'Info',       'farbe' => '#22405F'],
            'warnung'  => ['label' => 'Warnung',    'farbe' => '#B7791F'],
            'sperrung' => ['label' => 'Sperrung',   'farbe' => '#C8102E'],
            'event'    => ['label' => 'Termin',     'farbe' => '#2E7D53'],
            'news'     => ['label' => 'Neuigkeit',  'farbe' => '#22405F'],
        ];
    }

    public static function categoryLabel(string $category): string
    {
        return self::categoryMeta()[$category]['label'] ?? 'Info';
    }

    /* ------------------------------------------------------------ Meldungen */

    /** @return array<int,array<string,mixed>> */
    public static function all(): array
    {
        return DB::all('SELECT * FROM announcements ORDER BY id DESC');
    }

    public static function byId(int $id): ?array
    {
        return DB::row('SELECT * FROM announcements WHERE id = ?', [$id]);
    }

    public static function create(string $title, ?string $createdBy = null): int
    {
        $now = Util::now();
        $id  = DB::insert('announcements', [
            'title'      => mb_substr(trim($title), 0, 190) ?: 'Neue Meldung',
            'body'       => '',
            'category'   => 'info',
            'status'     => self::DRAFT,
            'list_id'    => Lists::defaultId() ?: null,
            'created_by' => $createdBy,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        // Eine sinnvolle Startbelegung: die beiden sofort funktionsfähigen
        // Kanäle, jeweils „jetzt". Weitere fügt man im Editor hinzu.
        $sort = 0;
        foreach (['website', 'email'] as $ch) {
            self::addChannel($id, $ch, Util::now(), $sort++);
        }
        return $id;
    }

    /** @param array<string,mixed> $data */
    public static function save(int $id, array $data): void
    {
        $update = [];
        if (array_key_exists('title', $data))      { $update['title']      = mb_substr(trim((string) $data['title']), 0, 190); }
        if (array_key_exists('body', $data))       { $update['body']       = mb_substr((string) $data['body'], 0, 8000); }
        if (array_key_exists('category', $data)) {
            $cat = (string) $data['category'];
            $update['category'] = isset(self::categoryMeta()[$cat]) ? $cat : 'info';
        }
        if (array_key_exists('link_url', $data))   { $update['link_url']   = self::cleanUrl((string) $data['link_url']); }
        if (array_key_exists('link_label', $data)) { $update['link_label'] = mb_substr(trim((string) $data['link_label']), 0, 120); }
        if (array_key_exists('list_id', $data)) {
            $lid = (int) $data['list_id'];
            $update['list_id'] = $lid > 0 ? $lid : null;
        }
        if (array_key_exists('expires_at', $data)) {
            $update['expires_at'] = self::cleanDateTime((string) $data['expires_at']);
        }
        if (array_key_exists('status', $data)) {
            $st = (string) $data['status'];
            $update['status'] = in_array($st, [self::DRAFT, self::ACTIVE, self::DONE], true) ? $st : self::DRAFT;
        }
        if ($update === []) {
            return;
        }
        $update['updated_at'] = Util::now();
        DB::update('announcements', $update, 'id = ?', [$id]);
    }

    public static function delete(int $id): void
    {
        DB::transaction(static function () use ($id): void {
            DB::delete('announcement_channels', 'announcement_id = ?', [$id]);
            DB::delete('announcements', 'id = ?', [$id]);
        });
    }

    /**
     * Scharf schalten: die Meldung wird aktiv, fällige Kanäle spielt der
     * nächste Cron-Lauf aus. Kanäle ohne Zeitpunkt bekommen „jetzt".
     */
    public static function activate(int $id): void
    {
        foreach (self::channels($id) as $ch) {
            if (trim((string) $ch['scheduled_at']) === '') {
                DB::update('announcement_channels', ['scheduled_at' => Util::now(), 'updated_at' => Util::now()],
                    'id = ?', [(int) $ch['id']]);
            }
        }
        self::save($id, ['status' => self::ACTIVE]);
    }

    public static function pause(int $id): void
    {
        self::save($id, ['status' => self::DRAFT]);
    }

    /* -------------------------------------------------------------- Kanäle */

    /** @return array<int,array<string,mixed>> */
    public static function channels(int $announcementId): array
    {
        return DB::all(
            'SELECT * FROM announcement_channels WHERE announcement_id = ? ORDER BY scheduled_at ASC, sort ASC, id ASC',
            [$announcementId]
        );
    }

    public static function addChannel(int $announcementId, string $channel, string $scheduledAt = '', int $sort = 0): int
    {
        if (!in_array($channel, self::CHANNELS, true)) {
            $channel = 'website';
        }
        $now = Util::now();
        return DB::insert('announcement_channels', [
            'announcement_id' => $announcementId,
            'channel'         => $channel,
            'scheduled_at'    => self::cleanDateTime($scheduledAt) ?: $now,
            'status'          => self::PENDING,
            'sort'            => $sort,
            'created_at'      => $now,
            'updated_at'      => $now,
        ]);
    }

    /** @param array<string,mixed> $data */
    public static function saveChannel(int $id, array $data): void
    {
        $update = [];
        if (array_key_exists('channel', $data)) {
            $ch = (string) $data['channel'];
            $update['channel'] = in_array($ch, self::CHANNELS, true) ? $ch : 'website';
        }
        if (array_key_exists('scheduled_at', $data)) {
            $update['scheduled_at'] = self::cleanDateTime((string) $data['scheduled_at']);
        }
        if ($update === []) {
            return;
        }
        $update['updated_at'] = Util::now();
        DB::update('announcement_channels', $update, 'id = ?', [$id]);
    }

    public static function deleteChannel(int $id): void
    {
        DB::delete('announcement_channels', 'id = ?', [$id]);
    }

    /** Ist ein Kanal einsatzbereit? E-Mail/Website immer; die übrigen je Einstellung. */
    public static function configured(string $channel): bool
    {
        switch ($channel) {
            case 'email':
            case 'website':
                return true;
            case 'sms':
                return Settings::get('channel_sms_key') !== '' && Settings::get('channel_sms_sender') !== '';
            case 'whatsapp':
                return Settings::get('channel_whatsapp_token') !== '' && Settings::get('channel_whatsapp_from') !== '';
            case 'push':
                return Settings::get('channel_push_key') !== '';
            default:
                return false;
        }
    }

    /* --------------------------------------------------- Ausspielen (Cron) */

    /**
     * Fällige Kanäle scharfer Meldungen ausspielen. Wird häufig gerufen
     * (Sende-Cron, alle paar Minuten), damit die Uhrzeiten genau sitzen.
     *
     * @return array{sent:int,skipped:int,failed:int}
     */
    public static function runDue(?string $now = null): array
    {
        $jetzt = $now ?? Util::now();
        $zahlen = ['sent' => 0, 'skipped' => 0, 'failed' => 0];

        $faellig = DB::all(
            "SELECT ac.* FROM announcement_channels ac
             JOIN announcements a ON a.id = ac.announcement_id
             WHERE ac.status = ? AND a.status = ? AND ac.scheduled_at <> '' AND ac.scheduled_at <= ?
             ORDER BY ac.scheduled_at ASC, ac.id ASC",
            [self::PENDING, self::ACTIVE, $jetzt]
        );

        foreach ($faellig as $ch) {
            $ann = self::byId((int) $ch['announcement_id']);
            if ($ann === null) {
                continue;
            }
            [$status, $result, $refId] = self::dispatch($ann, (string) $ch['channel']);
            DB::update('announcement_channels', [
                'status'     => $status,
                'result'     => mb_substr($result, 0, 2000),
                'ref_id'     => $refId,
                'sent_at'    => Util::now(),
                'updated_at' => Util::now(),
            ], 'id = ?', [(int) $ch['id']]);

            if ($status === self::SENT)         { $zahlen['sent']++; }
            elseif ($status === self::FAILED)   { $zahlen['failed']++; }
            else                                { $zahlen['skipped']++; }

            self::maybeFinish($ann['id']);
        }

        if (array_sum($zahlen) > 0) {
            Log::info('meldungen', sprintf('Mehrkanal-Meldungen: %d gesendet, %d übersprungen, %d fehlgeschlagen.',
                $zahlen['sent'], $zahlen['skipped'], $zahlen['failed']));
        }
        return $zahlen;
    }

    /**
     * Einen einzelnen Kanal ausspielen.
     *
     * @param array<string,mixed> $ann
     * @return array{0:string,1:string,2:?int} [status, result, ref_id]
     */
    public static function dispatch(array $ann, string $channel): array
    {
        try {
            switch ($channel) {
                case 'email':
                    return self::dispatchEmail($ann);
                case 'website':
                    // Website: nichts zu senden – ab jetzt erscheint die Meldung
                    // auf der öffentlichen Seite (die Abfrage prüft „sent").
                    return [self::SENT, 'Auf der Website veröffentlicht.', null];
                case 'sms':
                case 'whatsapp':
                case 'push':
                    return self::dispatchProvider($channel, $ann);
                default:
                    return [self::SKIPPED, 'Unbekannter Kanal.', null];
            }
        } catch (Throwable $e) {
            Log::warn('meldungen', 'Kanal ' . $channel . ' fehlgeschlagen: ' . $e->getMessage());
            return [self::FAILED, $e->getMessage(), null];
        }
    }

    /**
     * E-Mail-Kanal: aus der Meldung eine Kampagne bauen und sofort versenden.
     *
     * @param array<string,mixed> $ann
     * @return array{0:string,1:string,2:?int}
     */
    private static function dispatchEmail(array $ann): array
    {
        $campaignId = self::buildCampaign($ann);
        try {
            $count = Campaigns::start($campaignId);
            return [self::SENT, 'Newsletter an ' . $count . ' Empfänger versendet.', $campaignId];
        } catch (Throwable $e) {
            // Der Entwurf bleibt bestehen und kann von Hand gesendet werden.
            return [self::FAILED, 'Entwurf erstellt, Versand nicht möglich: ' . $e->getMessage(), $campaignId];
        }
    }

    /**
     * SMS/WhatsApp/Push: anschlussbereit. Ohne hinterlegten Anbieter wird der
     * Kanal sauber als „noch nicht eingerichtet" vermerkt. Sobald ein Anbieter
     * feststeht, wird hier der Live-Versand angebunden.
     *
     * @param array<string,mixed> $ann
     * @return array{0:string,1:string,2:?int}
     */
    private static function dispatchProvider(string $channel, array $ann): array
    {
        $label = self::channelLabel($channel);
        if (!self::configured($channel)) {
            return [self::SKIPPED,
                'Der ' . $label . '-Kanal ist noch nicht eingerichtet. '
                . 'Anbieter und Zugangsdaten unter „Kanäle einrichten" hinterlegen.', null];
        }
        // Anbieter hinterlegt: die eigentliche Anbindung (Provider-API) folgt,
        // sobald der Anbieter feststeht. Bewusst kein ungetesteter Live-Versand.
        $n = self::recipientCount($ann);
        return [self::SKIPPED,
            $label . '-Anbieter ist hinterlegt. Der Live-Versand an ' . $n . ' Empfänger wird '
            . 'freigeschaltet, sobald die Anbindung bestätigt ist.', null];
    }

    /**
     * Baut aus der Meldung eine Kampagne (Entwurf) und gibt deren Kennung
     * zurück. Der Versand passiert im Aufrufer.
     *
     * @param array<string,mixed> $ann
     */
    public static function buildCampaign(array $ann): int
    {
        $template = Templates::defaultTemplate();
        $meta     = Blocks::metaFromTemplate($template);
        $akzent   = (string) ($meta['linkColor'] ?? '#2C6B45');

        $blocks = [];
        $blocks[] = Blocks::block('heading', ['text' => (string) $ann['title'], 'size' => 24, 'space' => 10]);
        $blocks[] = Blocks::block('text', [
            'html'  => '<p>Hallo {{vorname}},</p>' . self::absatz((string) $ann['body']),
            'space' => 14,
        ]);
        if (trim((string) ($ann['link_url'] ?? '')) !== '') {
            $label = trim((string) ($ann['link_label'] ?? '')) ?: 'Mehr erfahren';
            $blocks[] = Blocks::block('button', ['label' => $label, 'href' => (string) $ann['link_url'],
                'bg' => $akzent, 'space' => 16]);
        }
        $dok = ['meta' => $meta, 'blocks' => $blocks];

        $id = Campaigns::create('Meldung: ' . (string) $ann['title'], $template !== null ? (int) $template['id'] : null, true);
        Campaigns::save($id, [
            'subject'     => mb_substr((string) $ann['title'], 0, 190),
            'preheader'   => mb_substr(self::ersterSatz((string) $ann['body']), 0, 150),
            'editor_mode' => 'blocks',
            'blocks_json' => (string) json_encode($dok, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'list_id'     => $ann['list_id'] !== null ? (int) $ann['list_id'] : (Lists::defaultId() ?: null),
        ]);
        return $id;
    }

    /** Grobe Empfängerzahl (Zielliste oder alle aktiven). */
    public static function recipientCount(array $ann): int
    {
        $listId = $ann['list_id'] !== null ? (int) $ann['list_id'] : 0;
        if ($listId > 0) {
            return (int) DB::value(
                "SELECT COUNT(*) FROM subscriber_lists sl JOIN subscribers s ON s.id = sl.subscriber_id
                 WHERE sl.list_id = ? AND s.status = 'active'", [$listId], 0);
        }
        return (int) DB::value("SELECT COUNT(*) FROM subscribers WHERE status = 'active'", [], 0);
    }

    /** Wenn alle Kanäle abgearbeitet sind, die Meldung als erledigt markieren. */
    private static function maybeFinish(int $id): void
    {
        $offen = (int) DB::value(
            'SELECT COUNT(*) FROM announcement_channels WHERE announcement_id = ? AND status = ?',
            [$id, self::PENDING], 0);
        if ($offen === 0) {
            DB::update('announcements', ['status' => self::DONE, 'updated_at' => Util::now()],
                'id = ? AND status = ?', [$id, self::ACTIVE]);
        }
    }

    /* ------------------------------------------------- Öffentliche Website */

    /**
     * Meldungen, die aktuell auf der Website sichtbar sind: der Website-Kanal
     * ist ausgespielt (sent) und die Meldung nicht abgelaufen.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function publicItems(int $limit = 30): array
    {
        $jetzt = Util::now();
        return DB::all(
            "SELECT a.*, ac.sent_at AS published_at
             FROM announcements a
             JOIN announcement_channels ac ON ac.announcement_id = a.id AND ac.channel = 'website'
             WHERE ac.status = ?
               AND (a.expires_at IS NULL OR a.expires_at = '' OR a.expires_at >= ?)
             ORDER BY ac.sent_at DESC, a.id DESC
             LIMIT " . max(1, min(100, $limit)),
            [self::SENT, $jetzt]
        );
    }

    /* --------------------------------------------------------- Textbausteine */

    private static function cleanUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return '';
        }
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . $url;
        }
        return mb_substr($url, 0, 500);
    }

    /** Datum/Zeit aus dem Formular (auch „2026-09-04T08:00") normalisieren. */
    public static function cleanDateTime(string $value): string
    {
        $value = trim(str_replace('T', ' ', $value));
        if ($value === '') {
            return '';
        }
        $ts = strtotime($value);
        return $ts === false ? '' : date('Y-m-d H:i:s', $ts);
    }

    private static function ersterSatz(string $text): string
    {
        $text = trim(preg_replace('/\s+/', ' ', $text) ?? '');
        if ($text === '') {
            return '';
        }
        if (preg_match('/^(.*?[.!?])(\s|$)/u', $text, $t)) {
            return $t[1];
        }
        return $text;
    }

    private static function absatz(string $text): string
    {
        $absaetze = preg_split('/\n\s*\n/', trim($text)) ?: [];
        $out = '';
        foreach ($absaetze as $abs) {
            $abs = trim($abs);
            if ($abs !== '') {
                $out .= '<p>' . nl2br(Util::e($abs)) . '</p>';
            }
        }
        return $out !== '' ? $out : '<p>' . nl2br(Util::e($text)) . '</p>';
    }
}
