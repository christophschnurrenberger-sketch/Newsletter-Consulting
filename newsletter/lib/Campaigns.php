<?php
/**
 * Campaigns – einzelne Newsletter-Ausgaben.
 *
 * Ablauf: draft → (scheduled) → sending → sent
 * Beim Start wird für jeden Empfänger eine Zeile in der Warteschlange
 * angelegt; den eigentlichen Versand erledigt der Cron-Job (lib/Queue.php).
 */
final class Campaigns
{
    public const DRAFT     = 'draft';
    public const SCHEDULED = 'scheduled';
    public const SENDING   = 'sending';
    public const PAUSED    = 'paused';
    public const SENT      = 'sent';
    public const CANCELLED = 'cancelled';

    /** @return array<string,string> */
    public static function statusLabels(): array
    {
        return [
            self::DRAFT     => 'Entwurf',
            self::SCHEDULED => 'Geplant',
            self::SENDING   => 'Versand läuft',
            self::PAUSED    => 'Pausiert',
            self::SENT      => 'Versendet',
            self::CANCELLED => 'Abgebrochen',
        ];
    }

    /* ---------------------------------------------------------------- CRUD */

    public static function byId(int $id): ?array
    {
        return DB::row('SELECT * FROM campaigns WHERE id = ?', [$id]);
    }

    /** @return array<int,array<string,mixed>> */
    public static function all(string $status = ''): array
    {
        if ($status !== '') {
            return DB::all('SELECT * FROM campaigns WHERE status = ? ORDER BY id DESC', [$status]);
        }
        return DB::all('SELECT * FROM campaigns ORDER BY id DESC');
    }

    public static function create(string $name): int
    {
        $now      = Util::now();
        $template = Templates::defaultTemplate();
        // Absender aus der Standardvorlage vorbelegen – bei mehreren Marken
        // stimmt so schon der Vorschlag. Änderbar bleibt er im Editor.
        $brand = Templates::brand($template);

        return DB::insert('campaigns', [
            'name'           => mb_substr(trim($name), 0, 190) ?: 'Neuer Newsletter',
            'subject'        => '',
            'preheader'      => '',
            'from_name'      => $brand['sender_name'],
            'from_email'     => $brand['sender_email'],
            'reply_to'       => Settings::get('reply_to'),
            'template_id'    => $template !== null ? (int) $template['id'] : null,
            'list_id'        => Lists::defaultId() ?: null,
            'content_html'   => Blocks::renderContent(Blocks::starterCampaign()),
            'content_text'   => Blocks::toText(Blocks::starterCampaign()),
            'blocks_json'    => (string) json_encode(Blocks::starterCampaign(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'editor_mode'    => 'blocks',
            'status'         => self::DRAFT,
            'track_opens'    => Settings::bool('track_opens') ? 1 : 0,
            'track_clicks'   => Settings::bool('track_clicks') ? 1 : 0,
            'archive_public' => Settings::bool('archive_enabled') ? 1 : 0,
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);
    }

    /** @param array<string,mixed> $data */
    public static function save(int $id, array $data): void
    {
        $allowed = ['name', 'subject', 'preheader', 'from_name', 'from_email', 'reply_to',
                    'template_id', 'list_id', 'content_html', 'content_text',
                    'track_opens', 'track_clicks', 'archive_public', 'scheduled_at',
                    'blocks_json', 'editor_mode'];
        $update = [];
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $update[$field] = $data[$field];
            }
        }
        if ($update === []) {
            return;
        }

        // Im Baukasten-Modus entstehen HTML und Textfassung aus den Bausteinen.
        if (($update['editor_mode'] ?? '') === 'blocks' && isset($update['blocks_json'])) {
            $blocks = Blocks::parse((string) $update['blocks_json']);
            $update['blocks_json']  = (string) json_encode($blocks, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $update['content_html'] = Blocks::renderContent($blocks);
            $update['content_text'] = Blocks::toText($blocks);
        }

        $update['updated_at'] = Util::now();
        DB::update('campaigns', $update, 'id = ?', [$id]);
    }

    /** Bausteine einer Ausgabe (leer = noch nie im Baukasten bearbeitet). */
    public static function blocks(array $campaign): array
    {
        $json = (string) ($campaign['blocks_json'] ?? '');
        if (trim($json) === '') {
            return Blocks::starterCampaign();
        }
        return Blocks::parse($json);
    }

    public static function usesBuilder(array $campaign): bool
    {
        return ($campaign['editor_mode'] ?? 'html') === 'blocks';
    }

    public static function duplicate(int $id): int
    {
        $campaign = self::byId($id);
        if ($campaign === null) {
            throw new RuntimeException('Kampagne nicht gefunden.');
        }
        $now = Util::now();
        return DB::insert('campaigns', [
            'name'           => Util::shorten('Kopie von ' . $campaign['name'], 185),
            'subject'        => $campaign['subject'],
            'preheader'      => $campaign['preheader'],
            'from_name'      => $campaign['from_name'],
            'from_email'     => $campaign['from_email'],
            'reply_to'       => $campaign['reply_to'],
            'template_id'    => $campaign['template_id'],
            'list_id'        => $campaign['list_id'],
            'content_html'   => $campaign['content_html'],
            'content_text'   => $campaign['content_text'],
            'blocks_json'    => $campaign['blocks_json'] ?? null,
            'editor_mode'    => $campaign['editor_mode'] ?? 'html',
            'status'         => self::DRAFT,
            'track_opens'    => $campaign['track_opens'],
            'track_clicks'   => $campaign['track_clicks'],
            'archive_public' => $campaign['archive_public'],
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);
    }

    public static function delete(int $id): void
    {
        DB::transaction(static function () use ($id) {
            DB::delete('queue', 'campaign_id = ?', [$id]);
            DB::delete('links', 'campaign_id = ?', [$id]);
            DB::delete('events', 'campaign_id = ?', [$id]);
            DB::delete('campaigns', 'id = ?', [$id]);
        });
    }

    /* ------------------------------------------------------------- Rendern */

    /**
     * Erzeugt die versandfertige Fassung (Vorlage + Inhalt + Tracking) und
     * legt sie in der Kampagne ab. Muss vor jedem Versand laufen.
     *
     * Bereits laufende oder abgeschlossene Ausgaben werden nicht erneut
     * kompiliert: Sonst bekämen die Zähl-Links neue Kennungen und die
     * bisherige Klickstatistik ginge verloren. Vorschau und Archiv rufen
     * diese Methode auf – deshalb ist die Sperre hier und nicht im Aufrufer.
     */
    public static function compile(int $id, bool $force = false): array
    {
        $campaign = self::byId($id);
        if ($campaign === null) {
            throw new RuntimeException('Kampagne nicht gefunden.');
        }

        $locked = in_array($campaign['status'], [self::SENDING, self::SENT, self::PAUSED, self::CANCELLED], true);
        if (!$force && $locked && trim((string) $campaign['compiled_html']) !== '') {
            return [
                'html' => (string) $campaign['compiled_html'],
                'text' => (string) $campaign['compiled_text'],
            ];
        }

        $template = Templates::byId($campaign['template_id'] !== null ? (int) $campaign['template_id'] : null);

        $html = Renderer::wrap($template, (string) $campaign['content_html'],
            (string) $campaign['subject'], (string) $campaign['preheader']);
        $html = Renderer::applyBrand($html, $template, true);
        $html = Renderer::compile($html, $id, null,
            (int) $campaign['track_clicks'] === 1, (int) $campaign['track_opens'] === 1);

        $text = trim((string) $campaign['content_text']);
        if ($text === '') {
            $text = Mailer::htmlToText((string) $campaign['content_html']);
        }
        $text .= "\n\n-- \n{{impressum}}\n\n"
              . "Newsletter abbestellen: {{abmelden_url}}\n"
              . "Daten & Einstellungen: {{praeferenzen_url}}\n"
              . "Im Browser ansehen: {{webansicht_url}}\n";
        $text = Renderer::applyBrand($text, $template, false);

        DB::update('campaigns', [
            'compiled_html' => $html,
            'compiled_text' => $text,
            'updated_at'    => Util::now(),
        ], 'id = ?', [$id]);

        return ['html' => $html, 'text' => $text];
    }

    /**
     * Baut die fertige Mail für einen konkreten Empfänger.
     *
     * @return array{subject:string,html:string,text:string}
     */
    public static function renderFor(array $campaign, array $subscriber, string $queueToken): array
    {
        $subToken = (string) ($subscriber['token'] ?? 'vorschau');
        $links = [
            'abmelden_url'     => Urls::unsubscribe($subToken, $queueToken),
            'praeferenzen_url' => Urls::preferences($subToken),
            'webansicht_url'   => Urls::webview((int) $campaign['id'], $queueToken),
        ];

        $html = (string) ($campaign['compiled_html'] ?? '');
        $text = (string) ($campaign['compiled_text'] ?? '');
        $html = str_replace(Renderer::TOKEN, rawurlencode($queueToken), $html);
        $text = str_replace(Renderer::TOKEN, rawurlencode($queueToken), $text);

        return [
            'subject' => Renderer::personalize((string) $campaign['subject'], $subscriber, $links, false),
            'html'    => Renderer::personalize($html, $subscriber, $links, true),
            'text'    => Renderer::personalize($text, $subscriber, $links, false),
        ];
    }

    /* -------------------------------------------------------------- Prüfen */

    /**
     * Prüft, ob die Kampagne versandfertig ist.
     * @return string[] Liste der Probleme
     */
    public static function validate(array $campaign): array
    {
        $problems = [];
        if (trim((string) $campaign['subject']) === '') {
            $problems[] = 'Es fehlt ein Betreff.';
        }
        if (trim(strip_tags((string) $campaign['content_html'])) === '') {
            $problems[] = 'Der Inhalt ist leer.';
        }
        if (!Util::isEmail((string) $campaign['from_email'])) {
            $problems[] = 'Die Absenderadresse ist ungültig.';
        }
        $template = Templates::byId($campaign['template_id'] !== null ? (int) $campaign['template_id'] : null);
        $frame    = ($template['html'] ?? '') . $campaign['content_html'];
        if (!str_contains($frame, '{{abmelden_url}}')) {
            $problems[] = 'Der Abmeldelink {{abmelden_url}} fehlt – er ist gesetzlich vorgeschrieben.';
        }
        if (self::recipientCount($campaign) === 0) {
            $problems[] = 'In der gewählten Liste gibt es keine aktiven Empfänger.';
        }
        // Der Hinweis auf den Testmodus ist eine Information, kein Hindernis
        $readiness = array_filter(Settings::readiness(), static fn($p) => !str_contains($p, 'Testmodus'));
        return array_merge($problems, array_values($readiness));
    }

    public static function recipientCount(array $campaign): int
    {
        $listId = $campaign['list_id'] !== null ? (int) $campaign['list_id'] : null;
        return Subscribers::countActiveForList($listId);
    }

    /* -------------------------------------------------------------- Versand */

    /**
     * Stellt die Kampagne in die Warteschlange.
     *
     * @param string $scheduledAt leer = sofort, sonst 'Y-m-d H:i:s'
     * @return int Anzahl eingeplanter Empfänger
     */
    public static function start(int $id, string $scheduledAt = ''): int
    {
        $campaign = self::byId($id);
        if ($campaign === null) {
            throw new RuntimeException('Kampagne nicht gefunden.');
        }
        if (in_array($campaign['status'], [self::SENDING, self::SENT], true)) {
            throw new RuntimeException('Diese Kampagne wurde bereits versendet.');
        }
        $problems = self::validate($campaign);
        if ($problems !== []) {
            throw new RuntimeException(implode(' ', $problems));
        }

        // Vor dem Start immer neu aufbauen – auch nach einem Abbruch.
        self::compile($id, true);
        $campaign = self::byId($id);

        $listId     = $campaign['list_id'] !== null ? (int) $campaign['list_id'] : null;
        $recipients = Subscribers::activeForList($listId);
        $dueAt      = $scheduledAt !== '' ? $scheduledAt : Util::now();
        $now        = Util::now();

        $count = DB::transaction(static function () use ($recipients, $id, $dueAt, $now): int {
            // Reste eines früheren Anlaufs entfernen
            DB::delete('queue', "campaign_id = ? AND status = 'pending'", [$id]);
            $inserted = 0;
            foreach ($recipients as $sub) {
                $already = (int) DB::value(
                    "SELECT COUNT(*) FROM queue WHERE campaign_id = ? AND subscriber_id = ? AND status IN ('sent','sending')",
                    [$id, (int) $sub['id']]
                );
                if ($already > 0) {
                    continue; // niemals doppelt zustellen
                }
                DB::insert('queue', [
                    'campaign_id'   => $id,
                    'subscriber_id' => (int) $sub['id'],
                    'email'         => (string) $sub['email'],
                    'token'         => Queue::freshToken(),
                    'status'        => 'pending',
                    'due_at'        => $dueAt,
                    'created_at'    => $now,
                ]);
                $inserted++;
            }
            return $inserted;
        });

        DB::update('campaigns', [
            'status'           => $scheduledAt !== '' ? self::SCHEDULED : self::SENDING,
            'scheduled_at'     => $scheduledAt !== '' ? $scheduledAt : null,
            'started_at'       => $scheduledAt !== '' ? null : $now,
            'total_recipients' => $count,
            'updated_at'       => $now,
        ], 'id = ?', [$id]);

        Log::info('campaign', 'Kampagne #' . $id . ' eingeplant: ' . $count . ' Empfänger'
            . ($scheduledAt !== '' ? ' für ' . $scheduledAt : ''));

        return $count;
    }

    public static function pause(int $id): void
    {
        DB::update('campaigns', ['status' => self::PAUSED, 'updated_at' => Util::now()], 'id = ?', [$id]);
        Log::info('campaign', 'Kampagne #' . $id . ' pausiert.');
    }

    public static function resume(int $id): void
    {
        $campaign = self::byId($id);
        if ($campaign === null) {
            return;
        }
        $pending = (int) DB::value("SELECT COUNT(*) FROM queue WHERE campaign_id = ? AND status = 'pending'", [$id]);
        DB::update('campaigns', [
            'status'     => $pending > 0 ? self::SENDING : self::SENT,
            'started_at' => $campaign['started_at'] ?: Util::now(),
            'updated_at' => Util::now(),
        ], 'id = ?', [$id]);
        Log::info('campaign', 'Kampagne #' . $id . ' fortgesetzt (' . $pending . ' offen).');
    }

    public static function cancel(int $id): void
    {
        DB::run("UPDATE queue SET status = 'skipped', last_error = 'Versand abgebrochen'
                 WHERE campaign_id = ? AND status = 'pending'", [$id]);
        DB::update('campaigns', [
            'status'      => self::CANCELLED,
            'finished_at' => Util::now(),
            'updated_at'  => Util::now(),
        ], 'id = ?', [$id]);
        Log::info('campaign', 'Kampagne #' . $id . ' abgebrochen.');
    }

    /** Testversand an eine beliebige Adresse (zählt nicht in der Statistik). */
    public static function sendTest(int $id, string $email): void
    {
        $campaign = self::byId($id);
        if ($campaign === null) {
            throw new RuntimeException('Kampagne nicht gefunden.');
        }
        $email = Util::normalizeEmail($email);
        if (!Util::isEmail($email)) {
            throw new RuntimeException('Bitte geben Sie eine gültige Testadresse an.');
        }
        self::compile($id);
        $campaign = self::byId($id);

        $sample = Subscribers::byEmail($email) ?? Renderer::sampleSubscriber($email);
        $sample['email'] = $email;
        $mail = self::renderFor($campaign, $sample, 'test-' . Util::token(6));

        Mailer::send([
            'to'         => $email,
            'to_name'    => Subscribers::displayName($sample),
            'subject'    => '[TEST] ' . $mail['subject'],
            'html'       => $mail['html'],
            'text'       => $mail['text'],
            'from_email' => (string) $campaign['from_email'],
            'from_name'  => (string) $campaign['from_name'],
            'reply_to'   => (string) $campaign['reply_to'],
            'headers'    => [
                'X-Newsletter-Test' => 'yes',
                'Auto-Submitted'    => 'auto-generated',
                'Precedence'        => 'bulk',
            ],
        ]);
        Log::info('campaign', 'Testversand von Kampagne #' . $id . ' an ' . $email);
    }

    /* ------------------------------------------------------------ Statistik */

    /** @return array<string,int|string> */
    public static function stats(int $id): array
    {
        $queue = DB::pairs(
            'SELECT status, COUNT(*) FROM queue WHERE campaign_id = ? GROUP BY status',
            [$id]
        );
        $sent    = (int) ($queue['sent'] ?? 0);
        $pending = (int) ($queue['pending'] ?? 0) + (int) ($queue['sending'] ?? 0);
        $failed  = (int) ($queue['failed'] ?? 0);
        $skipped = (int) ($queue['skipped'] ?? 0);

        $opensUnique  = Events::countFor($id, Events::OPEN, true);
        $clicksUnique = Events::countFor($id, Events::CLICK, true);

        return [
            'sent'          => $sent,
            'pending'       => $pending,
            'failed'        => $failed,
            'skipped'       => $skipped,
            'total'         => $sent + $pending + $failed + $skipped,
            'opens'         => Events::countFor($id, Events::OPEN),
            'opens_unique'  => $opensUnique,
            'clicks'        => Events::countFor($id, Events::CLICK),
            'clicks_unique' => $clicksUnique,
            'unsubscribes'  => Events::countFor($id, Events::UNSUBSCRIBE),
            'bounces'       => Events::countFor($id, Events::BOUNCE),
            'open_rate'     => $sent > 0 ? Util::percent($opensUnique, $sent) : '—',
            'click_rate'    => $sent > 0 ? Util::percent($clicksUnique, $sent) : '—',
        ];
    }

    /** Klicks je Link. */
    public static function linkStats(int $id): array
    {
        return DB::all(
            'SELECT l.id, l.url, l.label,
                    COUNT(e.id) AS klicks,
                    COUNT(DISTINCT e.subscriber_id) AS empfaenger
             FROM links l
             LEFT JOIN events e ON e.link_id = l.id AND e.type = \'click\'
             WHERE l.campaign_id = ?
             GROUP BY l.id, l.url, l.label
             ORDER BY klicks DESC, l.id',
            [$id]
        );
    }

    /** Öffentliche Archivliste. */
    public static function archived(): array
    {
        return DB::all(
            "SELECT id, name, subject, preheader, started_at, finished_at
             FROM campaigns
             WHERE archive_public = 1 AND status IN ('sent', 'sending')
             ORDER BY COALESCE(started_at, created_at) DESC"
        );
    }
}
