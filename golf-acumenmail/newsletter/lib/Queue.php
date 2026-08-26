<?php
/**
 * Queue – die Versandwarteschlange und der Worker dahinter.
 *
 * Für jede Mail existiert genau eine Zeile in der Tabelle "queue". Der
 * Cron-Job ruft Queue::process() auf; dort werden portionsweise Mails
 * verschickt, Fehlversuche wiederholt und Statistiken geschrieben.
 *
 * Warum portionsweise? Shared Hosting begrenzt Laufzeit und Mails pro
 * Stunde. Der Worker hält sich an batch_size, hourly_limit und max_runtime.
 */
final class Queue
{
    public const PENDING = 'pending';
    public const SENDING = 'sending';
    public const SENT    = 'sent';
    public const FAILED  = 'failed';
    public const SKIPPED = 'skipped';

    /** Nach so vielen Minuten gilt eine hängende Sperre als verwaist. */
    private const LOCK_TIMEOUT_MINUTES = 15;

    public static function freshToken(): string
    {
        do {
            $token = Util::token(18);
        } while (DB::value('SELECT COUNT(*) FROM queue WHERE token = ?', [$token]) > 0);
        return $token;
    }

    /* ------------------------------------------------------------- Worker */

    /**
     * Arbeitet die Warteschlange ab.
     *
     * @param array{limit?:int,seconds?:int,quiet?:bool} $options
     * @return array{sent:int,failed:int,skipped:int,remaining:int,seconds:float,limited:string}
     */
    public static function process(array $options = []): array
    {
        $started    = microtime(true);
        $maxMessages = (int) ($options['limit'] ?? Settings::int('batch_size', 50));
        $maxSeconds  = (int) ($options['seconds'] ?? Settings::int('max_runtime', 50));
        $delayMs     = Settings::int('send_delay_ms', 400);
        $result      = ['sent' => 0, 'failed' => 0, 'skipped' => 0, 'remaining' => 0, 'seconds' => 0.0, 'limited' => ''];

        self::releaseStaleLocks();
        self::activateScheduledCampaigns();
        Automations::enqueueDue();

        $hourlyLeft = self::hourlyRemaining();
        if ($hourlyLeft <= 0) {
            $result['limited']   = 'Stundenlimit erreicht (' . Settings::int('hourly_limit', 500) . ' Mails/Stunde).';
            $result['remaining'] = self::pendingCount();
            $result['seconds']   = round(microtime(true) - $started, 2);
            return $result;
        }
        $maxMessages = min($maxMessages, $hourlyLeft);

        for ($i = 0; $i < $maxMessages; $i++) {
            if ((microtime(true) - $started) > $maxSeconds) {
                $result['limited'] = 'Zeitlimit des Durchlaufs erreicht.';
                break;
            }
            $row = self::claimNext();
            if ($row === null) {
                break;
            }
            $outcome = self::deliver($row);
            $result[$outcome] = ($result[$outcome] ?? 0) + 1;

            if ($delayMs > 0 && $i + 1 < $maxMessages) {
                usleep($delayMs * 1000);
            }
        }

        Mailer::close();
        self::finishCampaigns();

        $result['remaining'] = self::pendingCount();
        $result['seconds']   = round(microtime(true) - $started, 2);
        Settings::set('last_cron_at', Util::now());

        if ($result['sent'] > 0 || $result['failed'] > 0) {
            Log::info('queue', sprintf(
                'Durchlauf: %d versendet, %d fehlgeschlagen, %d übersprungen, %d offen (%.1fs)',
                $result['sent'], $result['failed'], $result['skipped'], $result['remaining'], $result['seconds']
            ));
        }
        return $result;
    }

    /**
     * Holt die nächste fällige Mail und sperrt sie für diesen Durchlauf.
     * @return array<string,mixed>|null
     */
    private static function claimNext(): ?array
    {
        $now = Util::now();
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $row = DB::row(
                "SELECT q.* FROM queue q
                 LEFT JOIN campaigns c ON c.id = q.campaign_id
                 WHERE q.status = 'pending'
                   AND (q.due_at IS NULL OR q.due_at <= ?)
                   AND (q.campaign_id IS NULL OR c.status IN ('sending', 'scheduled'))
                 ORDER BY q.id
                 LIMIT 1",
                [$now]
            );
            if ($row === null) {
                return null;
            }
            $locked = DB::update('queue', [
                'status'    => self::SENDING,
                'locked_at' => $now,
            ], "id = ? AND status = 'pending'", [(int) $row['id']]);
            if ($locked === 1) {
                $row['status'] = self::SENDING;
                return $row;
            }
            // Ein paralleler Durchlauf war schneller – nächste Zeile versuchen.
        }
        return null;
    }

    /**
     * Verschickt eine einzelne Mail.
     * @return string 'sent' | 'failed' | 'skipped'
     */
    private static function deliver(array $row): string
    {
        $queueId  = (int) $row['id'];
        $attempts = (int) $row['attempts'] + 1;

        $subscriber = Subscribers::byId((int) $row['subscriber_id']);
        if ($subscriber === null || $subscriber['status'] !== Subscribers::STATUS_ACTIVE) {
            return self::markSkipped($queueId, 'Empfänger ist nicht (mehr) aktiv.');
        }
        if (Subscribers::isSuppressed((string) $subscriber['email'])) {
            return self::markSkipped($queueId, 'Adresse steht auf der Sperrliste.');
        }

        $source = self::source($row);
        if ($source === null) {
            return self::markSkipped($queueId, 'Zugehörige Kampagne wurde gelöscht.');
        }
        if (($source['status'] ?? '') === Campaigns::CANCELLED) {
            return self::markSkipped($queueId, 'Versand wurde abgebrochen.');
        }

        $token = (string) $row['token'];
        $mail  = Campaigns::renderFor($source, $subscriber, $token);

        try {
            $messageId = Mailer::send([
                'to'         => (string) $subscriber['email'],
                'to_name'    => Subscribers::displayName($subscriber),
                'subject'    => $mail['subject'],
                'html'       => $mail['html'],
                'text'       => $mail['text'],
                'from_email' => (string) ($source['from_email'] ?: Settings::get('sender_email')),
                'from_name'  => (string) ($source['from_name'] ?: Settings::get('sender_name')),
                'reply_to'   => (string) ($source['reply_to'] ?: Settings::get('reply_to')),
                'headers'    => self::listHeaders($subscriber, $token),
            ]);
        } catch (Throwable $e) {
            return self::handleFailure($row, $attempts, $subscriber, $e->getMessage());
        }

        DB::update('queue', [
            'status'     => self::SENT,
            'attempts'   => $attempts,
            'sent_at'    => Util::now(),
            'message_id' => mb_substr($messageId, 0, 190),
            'last_error' => null,
        ], 'id = ?', [$queueId]);

        Events::record(Events::SENT, [
            'campaign_id'   => $row['campaign_id'] !== null ? (int) $row['campaign_id'] : null,
            'step_id'       => $row['step_id'] !== null ? (int) $row['step_id'] : null,
            'subscriber_id' => (int) $subscriber['id'],
            'queue_id'      => $queueId,
            'ip'            => '',
            'user_agent'    => '',
        ]);
        DB::update('subscribers', ['last_sent_at' => Util::now()], 'id = ?', [(int) $subscriber['id']]);

        return 'sent';
    }

    /** Kopfzeilen für Massenmails (Abmeldung mit einem Klick, RFC 8058). */
    private static function listHeaders(array $subscriber, string $queueToken): array
    {
        $unsubUrl = Urls::unsubscribe((string) $subscriber['token'], $queueToken) . '&one=1';
        $mailto   = Settings::get('bounce_email') ?: Settings::get('sender_email');
        $domain   = Util::emailDomain(Settings::get('sender_email')) ?: 'localhost';

        $headers = [
            'List-Unsubscribe'      => '<' . $unsubUrl . '>, <mailto:' . $mailto . '?subject=unsubscribe>',
            'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
            'List-Id'               => Settings::get('brand_name') . ' Newsletter <newsletter.' . $domain . '>',
            'Precedence'            => 'bulk',
            'Auto-Submitted'        => 'auto-generated',
            // Ermöglicht die Zuordnung von Rückläufern zur Kampagne
            'X-Newsletter-Token'    => $queueToken,
        ];
        if (Settings::get('privacy_url') !== '') {
            $headers['List-Help'] = '<' . Settings::get('privacy_url') . '>';
        }
        return $headers;
    }

    /**
     * Kampagne oder Automationsschritt hinter einer Warteschlangenzeile.
     * @return array<string,mixed>|null
     */
    private static function source(array $row): ?array
    {
        if ($row['campaign_id'] !== null) {
            return Campaigns::byId((int) $row['campaign_id']);
        }
        if ($row['step_id'] !== null) {
            return Automations::stepAsCampaign((int) $row['step_id']);
        }
        return null;
    }

    private static function markSkipped(int $queueId, string $reason): string
    {
        DB::update('queue', [
            'status'     => self::SKIPPED,
            'last_error' => $reason,
        ], 'id = ?', [$queueId]);
        return 'skipped';
    }

    /** Fehlversuch behandeln: erneut versuchen oder endgültig scheitern lassen. */
    private static function handleFailure(array $row, int $attempts, array $subscriber, string $error): string
    {
        $queueId  = (int) $row['id'];
        $maxTries = max(1, Settings::int('max_attempts', 3));
        $error    = mb_substr($error, 0, 900);

        if (self::isPermanent($error)) {
            DB::update('queue', [
                'status'     => self::FAILED,
                'attempts'   => $attempts,
                'last_error' => $error,
            ], 'id = ?', [$queueId]);

            Bounces::registerHard(
                (string) $subscriber['email'],
                $row['campaign_id'] !== null ? (int) $row['campaign_id'] : null,
                $error
            );
            Events::record(Events::BOUNCE, [
                'campaign_id'   => $row['campaign_id'] !== null ? (int) $row['campaign_id'] : null,
                'step_id'       => $row['step_id'] !== null ? (int) $row['step_id'] : null,
                'subscriber_id' => (int) $subscriber['id'],
                'queue_id'      => $queueId,
                'detail'        => $error,
                'ip'            => '',
                'user_agent'    => '',
            ]);
            Log::warn('queue', 'Dauerhaft unzustellbar: ' . $subscriber['email'] . ' – ' . $error);
            return 'failed';
        }

        if ($attempts >= $maxTries) {
            DB::update('queue', [
                'status'     => self::FAILED,
                'attempts'   => $attempts,
                'last_error' => $error,
            ], 'id = ?', [$queueId]);
            Events::record(Events::FAILED, [
                'campaign_id'   => $row['campaign_id'] !== null ? (int) $row['campaign_id'] : null,
                'step_id'       => $row['step_id'] !== null ? (int) $row['step_id'] : null,
                'subscriber_id' => (int) $subscriber['id'],
                'queue_id'      => $queueId,
                'detail'        => $error,
                'ip'            => '',
                'user_agent'    => '',
            ]);
            Log::error('queue', 'Versand endgültig fehlgeschlagen an ' . $subscriber['email'] . ': ' . $error);
            return 'failed';
        }

        // Später erneut versuchen (wachsender Abstand)
        DB::update('queue', [
            'status'     => self::PENDING,
            'attempts'   => $attempts,
            'last_error' => $error,
            'locked_at'  => null,
            'due_at'     => date('Y-m-d H:i:s', time() + 300 * $attempts),
        ], 'id = ?', [$queueId]);
        Log::warn('queue', 'Versuch ' . $attempts . ' fehlgeschlagen an ' . $subscriber['email'] . ': ' . $error);
        return 'failed';
    }

    /** Erkennt endgültige Zustellfehler (5xx) an der Fehlermeldung. */
    public static function isPermanent(string $error): bool
    {
        if (preg_match('/\b(5\.[0-7]\.\d+)\b/', $error)) {
            return true;
        }
        if (preg_match('/SMTP-Fehler bei "(MAIL FROM|RCPT TO)[^"]*": 5\d\d/i', $error)) {
            return true;
        }
        $needles = ['user unknown', 'no such user', 'mailbox unavailable', 'does not exist',
                    'recipient rejected', 'invalid recipient', 'address rejected'];
        $lower = mb_strtolower($error);
        foreach ($needles as $needle) {
            if (str_contains($lower, $needle)) {
                return true;
            }
        }
        return false;
    }

    /* ------------------------------------------------------------ Zustände */

    /** Sperren aus abgebrochenen Durchläufen wieder freigeben. */
    public static function releaseStaleLocks(): int
    {
        $limit = date('Y-m-d H:i:s', time() - self::LOCK_TIMEOUT_MINUTES * 60);
        return DB::run(
            "UPDATE queue SET status = 'pending', locked_at = NULL
             WHERE status = 'sending' AND (locked_at IS NULL OR locked_at < ?)",
            [$limit]
        )->rowCount();
    }

    /** Geplante Kampagnen starten, sobald ihr Zeitpunkt erreicht ist. */
    public static function activateScheduledCampaigns(): void
    {
        $due = DB::all(
            "SELECT id FROM campaigns WHERE status = 'scheduled' AND scheduled_at IS NOT NULL AND scheduled_at <= ?",
            [Util::now()]
        );
        foreach ($due as $row) {
            DB::update('campaigns', [
                'status'     => Campaigns::SENDING,
                'started_at' => Util::now(),
                'updated_at' => Util::now(),
            ], 'id = ?', [(int) $row['id']]);
            Log::info('queue', 'Geplante Kampagne #' . $row['id'] . ' gestartet.');
        }
    }

    /** Kampagnen ohne offene Sendungen abschließen. */
    public static function finishCampaigns(): void
    {
        $running = DB::all("SELECT id FROM campaigns WHERE status = 'sending'");
        foreach ($running as $row) {
            $open = (int) DB::value(
                "SELECT COUNT(*) FROM queue WHERE campaign_id = ? AND status IN ('pending', 'sending')",
                [(int) $row['id']]
            );
            if ($open === 0) {
                DB::update('campaigns', [
                    'status'      => Campaigns::SENT,
                    'finished_at' => Util::now(),
                    'updated_at'  => Util::now(),
                ], 'id = ?', [(int) $row['id']]);
                Log::info('campaign', 'Kampagne #' . $row['id'] . ' vollständig versendet.');
            }
        }
    }

    /* -------------------------------------------------------------- Zahlen */

    public static function pendingCount(): int
    {
        return (int) DB::value(
            "SELECT COUNT(*) FROM queue q
             LEFT JOIN campaigns c ON c.id = q.campaign_id
             WHERE q.status = 'pending'
               AND (q.campaign_id IS NULL OR c.status IN ('sending', 'scheduled'))"
        );
    }

    /** Wie viele Mails dürfen in dieser Stunde noch raus? */
    public static function hourlyRemaining(): int
    {
        $limit = Settings::int('hourly_limit', 500);
        if ($limit <= 0) {
            return PHP_INT_MAX;
        }
        $sinceHour = date('Y-m-d H:i:s', time() - 3600);
        $sent = (int) DB::value("SELECT COUNT(*) FROM queue WHERE status = 'sent' AND sent_at >= ?", [$sinceHour]);
        return max(0, $limit - $sent);
    }

    /** @return array<string,int|string> Kennzahlen für das Dashboard */
    public static function overview(): array
    {
        $counts = DB::pairs('SELECT status, COUNT(*) FROM queue GROUP BY status');
        return [
            'pending'      => self::pendingCount(),
            'sending'      => (int) ($counts['sending'] ?? 0),
            'sent_total'   => (int) ($counts['sent'] ?? 0),
            'failed'       => (int) ($counts['failed'] ?? 0),
            'sent_hour'    => (int) DB::value("SELECT COUNT(*) FROM queue WHERE status = 'sent' AND sent_at >= ?",
                                  [date('Y-m-d H:i:s', time() - 3600)]),
            'sent_today'   => (int) DB::value("SELECT COUNT(*) FROM queue WHERE status = 'sent' AND sent_at >= ?",
                                  [date('Y-m-d 00:00:00')]),
            'hourly_left'  => self::hourlyRemaining(),
            'next_due'     => (string) DB::value("SELECT MIN(due_at) FROM queue WHERE status = 'pending'", [], ''),
            'last_cron_at' => Settings::get('last_cron_at'),
        ];
    }

    /** Fehlgeschlagene Sendungen erneut einreihen. */
    public static function retryFailed(?int $campaignId = null): int
    {
        $sql    = "UPDATE queue SET status = 'pending', attempts = 0, due_at = ?, last_error = NULL WHERE status = 'failed'";
        $params = [Util::now()];
        if ($campaignId !== null) {
            $sql     .= ' AND campaign_id = ?';
            $params[] = $campaignId;
        }
        $count = DB::run($sql, $params)->rowCount();
        if ($campaignId !== null && $count > 0) {
            $campaign = Campaigns::byId($campaignId);
            if ($campaign !== null && $campaign['status'] === Campaigns::SENT) {
                DB::update('campaigns', ['status' => Campaigns::SENDING], 'id = ?', [$campaignId]);
            }
        }
        return $count;
    }

    /** Alte, abgeschlossene Warteschlangeneinträge aufräumen. */
    public static function prune(int $days = 180): int
    {
        return DB::delete(
            'queue',
            "status IN ('sent', 'skipped') AND sent_at IS NOT NULL AND sent_at < ?",
            [date('Y-m-d H:i:s', time() - $days * 86400)]
        );
    }
}
