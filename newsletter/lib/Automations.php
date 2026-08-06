<?php
/**
 * Automations – zeitgesteuerte Mailstrecken (z. B. Willkommensserie).
 *
 * Auslöser ist derzeit die bestätigte Anmeldung. Beim Eintritt wird für
 * jeden Schritt ein Lauf mit Fälligkeitszeitpunkt angelegt; der Cron-Job
 * stellt fällige Läufe in dieselbe Warteschlange wie normale Kampagnen.
 *
 * Die Verzögerung eines Schrittes zählt ab dem Eintritt in die Strecke
 * ("24 Stunden nach der Anmeldung"), nicht ab dem vorherigen Schritt.
 */
final class Automations
{
    public const ACTIVE = 'active';
    public const PAUSED = 'paused';

    public const TRIGGER_CONFIRM = 'confirm';

    /* ---------------------------------------------------------------- CRUD */

    /** @return array<int,array<string,mixed>> */
    public static function all(): array
    {
        return DB::all('SELECT * FROM automations ORDER BY id');
    }

    public static function byId(int $id): ?array
    {
        return DB::row('SELECT * FROM automations WHERE id = ?', [$id]);
    }

    public static function create(string $name, ?int $listId = null): int
    {
        $now = Util::now();
        return DB::insert('automations', [
            'name'         => mb_substr(trim($name), 0, 190) ?: 'Neue Strecke',
            'trigger_type' => self::TRIGGER_CONFIRM,
            'list_id'      => $listId,
            'status'       => self::PAUSED,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
    }

    /** @param array<string,mixed> $data */
    public static function save(int $id, array $data): void
    {
        $update = [];
        foreach (['name', 'list_id', 'status'] as $field) {
            if (array_key_exists($field, $data)) {
                $update[$field] = $data[$field];
            }
        }
        if ($update === []) {
            return;
        }
        $update['updated_at'] = Util::now();
        DB::update('automations', $update, 'id = ?', [$id]);
    }

    public static function delete(int $id): void
    {
        DB::transaction(static function () use ($id) {
            $stepIds = array_map('intval', DB::column('SELECT id FROM automation_steps WHERE automation_id = ?', [$id]));
            foreach ($stepIds as $stepId) {
                DB::delete('queue', "step_id = ? AND status = 'pending'", [$stepId]);
                DB::delete('links', 'step_id = ?', [$stepId]);
            }
            DB::delete('automation_runs', 'automation_id = ?', [$id]);
            DB::delete('automation_steps', 'automation_id = ?', [$id]);
            DB::delete('automations', 'id = ?', [$id]);
        });
    }

    /* --------------------------------------------------------------- Schritte */

    /** @return array<int,array<string,mixed>> */
    public static function steps(int $automationId): array
    {
        return DB::all(
            'SELECT * FROM automation_steps WHERE automation_id = ? ORDER BY position, id',
            [$automationId]
        );
    }

    public static function step(int $stepId): ?array
    {
        return DB::row('SELECT * FROM automation_steps WHERE id = ?', [$stepId]);
    }

    public static function addStep(int $automationId, int $delayHours = 24): int
    {
        $position = (int) DB::value(
            'SELECT COALESCE(MAX(position), 0) + 1 FROM automation_steps WHERE automation_id = ?',
            [$automationId]
        );
        $now = Util::now();
        return DB::insert('automation_steps', [
            'automation_id' => $automationId,
            'position'      => $position,
            'delay_hours'   => max(0, $delayHours),
            'subject'       => '',
            'template_id'   => Templates::defaultId() ?: null,
            'content_html'  => Templates::starterContent(),
            'content_text'  => '',
            'track_opens'   => Settings::bool('track_opens') ? 1 : 0,
            'track_clicks'  => Settings::bool('track_clicks') ? 1 : 0,
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);
    }

    /** @param array<string,mixed> $data */
    public static function saveStep(int $stepId, array $data): void
    {
        $allowed = ['position', 'delay_hours', 'subject', 'template_id',
                    'content_html', 'content_text', 'track_opens', 'track_clicks'];
        $update = [];
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $update[$field] = $data[$field];
            }
        }
        if ($update === []) {
            return;
        }
        $update['updated_at'] = Util::now();
        DB::update('automation_steps', $update, 'id = ?', [$stepId]);
        self::compileStep($stepId);
    }

    public static function deleteStep(int $stepId): void
    {
        DB::transaction(static function () use ($stepId) {
            DB::delete('queue', "step_id = ? AND status = 'pending'", [$stepId]);
            DB::delete('links', 'step_id = ?', [$stepId]);
            DB::delete('automation_runs', 'step_id = ?', [$stepId]);
            DB::delete('automation_steps', 'id = ?', [$stepId]);
        });
    }

    /** Baut die versandfertige Fassung eines Schrittes. */
    public static function compileStep(int $stepId): void
    {
        $step = self::step($stepId);
        if ($step === null) {
            return;
        }
        $template = Templates::byId($step['template_id'] !== null ? (int) $step['template_id'] : null);
        $html = Renderer::wrap($template, (string) $step['content_html'], (string) $step['subject']);
        $html = Renderer::compile($html, null, $stepId,
            (int) $step['track_clicks'] === 1, (int) $step['track_opens'] === 1);

        $text = trim((string) $step['content_text']);
        if ($text === '') {
            $text = Mailer::htmlToText((string) $step['content_html']);
        }
        $text .= "\n\n-- \n{{impressum}}\n\n"
              . "Newsletter abbestellen: {{abmelden_url}}\n"
              . "Daten & Einstellungen: {{praeferenzen_url}}\n";

        DB::update('automation_steps', [
            'compiled_html' => $html,
            'compiled_text' => $text,
            'updated_at'    => Util::now(),
        ], 'id = ?', [$stepId]);
    }

    /**
     * Stellt einen Schritt so dar, wie ihn der Versand-Worker erwartet.
     * @return array<string,mixed>|null
     */
    public static function stepAsCampaign(int $stepId): ?array
    {
        $step = self::step($stepId);
        if ($step === null) {
            return null;
        }
        if (trim((string) $step['compiled_html']) === '') {
            self::compileStep($stepId);
            $step = self::step($stepId);
        }
        return [
            'id'            => 0,
            'name'          => 'Automation Schritt ' . $step['position'],
            'subject'       => $step['subject'],
            'preheader'     => '',
            'from_name'     => Settings::get('sender_name'),
            'from_email'    => Settings::get('sender_email'),
            'reply_to'      => Settings::get('reply_to'),
            'compiled_html' => $step['compiled_html'],
            'compiled_text' => $step['compiled_text'],
            'status'        => Campaigns::SENDING,
        ];
    }

    /* -------------------------------------------------------------- Auslöser */

    /** Wird nach bestätigter Anmeldung aufgerufen. */
    public static function onConfirm(array $subscriber): void
    {
        $automations = DB::all(
            "SELECT * FROM automations WHERE status = 'active' AND trigger_type = ?",
            [self::TRIGGER_CONFIRM]
        );
        foreach ($automations as $automation) {
            $listId = $automation['list_id'] !== null ? (int) $automation['list_id'] : null;
            if ($listId !== null && !in_array($listId, Subscribers::listIds((int) $subscriber['id']), true)) {
                continue;
            }
            self::enroll((int) $automation['id'], (int) $subscriber['id']);
        }
    }

    /** Nimmt einen Empfänger in eine Strecke auf (keine Doppelaufnahme). */
    public static function enroll(int $automationId, int $subscriberId): int
    {
        $already = (int) DB::value(
            'SELECT COUNT(*) FROM automation_runs WHERE automation_id = ? AND subscriber_id = ?',
            [$automationId, $subscriberId]
        );
        if ($already > 0) {
            return 0;
        }
        $steps = self::steps($automationId);
        $now   = Util::now();
        $count = 0;
        foreach ($steps as $step) {
            if (trim((string) $step['subject']) === '') {
                continue; // unfertige Schritte überspringen
            }
            DB::insert('automation_runs', [
                'automation_id' => $automationId,
                'subscriber_id' => $subscriberId,
                'step_id'       => (int) $step['id'],
                'status'        => 'pending',
                'due_at'        => Util::inHours((float) $step['delay_hours']),
                'created_at'    => $now,
            ]);
            $count++;
        }
        return $count;
    }

    /** Offene Läufe eines Empfängers beenden (Abmeldung, Sperre, Löschung). */
    public static function cancelFor(int $subscriberId): void
    {
        DB::run("UPDATE automation_runs SET status = 'cancelled' WHERE subscriber_id = ? AND status = 'pending'",
            [$subscriberId]);
    }

    /* ------------------------------------------------------------ Cron-Teil */

    /**
     * Fällige Schritte in die Versandwarteschlange stellen.
     * @return int Anzahl neu eingereihter Mails
     */
    public static function enqueueDue(): int
    {
        $due = DB::all(
            "SELECT r.* FROM automation_runs r
             JOIN automations a ON a.id = r.automation_id
             JOIN subscribers s ON s.id = r.subscriber_id
             WHERE r.status = 'pending'
               AND r.due_at <= ?
               AND a.status = 'active'
               AND s.status = 'active'
             ORDER BY r.due_at
             LIMIT 200",
            [Util::now()]
        );

        $queued = 0;
        foreach ($due as $run) {
            $subscriber = Subscribers::byId((int) $run['subscriber_id']);
            if ($subscriber === null || $subscriber['status'] !== Subscribers::STATUS_ACTIVE) {
                DB::update('automation_runs', ['status' => 'cancelled'], 'id = ?', [(int) $run['id']]);
                continue;
            }
            $step = self::step((int) $run['step_id']);
            if ($step === null || trim((string) $step['subject']) === '') {
                DB::update('automation_runs', ['status' => 'cancelled'], 'id = ?', [(int) $run['id']]);
                continue;
            }
            if (trim((string) $step['compiled_html']) === '') {
                self::compileStep((int) $step['id']);
            }

            DB::insert('queue', [
                'campaign_id'   => null,
                'step_id'       => (int) $step['id'],
                'subscriber_id' => (int) $subscriber['id'],
                'email'         => (string) $subscriber['email'],
                'token'         => Queue::freshToken(),
                'status'        => Queue::PENDING,
                'due_at'        => Util::now(),
                'created_at'    => Util::now(),
            ]);
            DB::update('automation_runs', [
                'status'    => 'queued',
                'queued_at' => Util::now(),
            ], 'id = ?', [(int) $run['id']]);
            $queued++;
        }
        if ($queued > 0) {
            Log::info('automation', $queued . ' Automations-Mail(s) eingereiht.');
        }
        return $queued;
    }

    /* ------------------------------------------------------------ Statistik */

    /** @return array<string,int> */
    public static function stats(int $automationId): array
    {
        $stepIds = array_map('intval', DB::column('SELECT id FROM automation_steps WHERE automation_id = ?', [$automationId]));
        if ($stepIds === []) {
            return ['subscribers' => 0, 'pending' => 0, 'sent' => 0, 'opens' => 0, 'clicks' => 0];
        }
        $in = implode(',', array_fill(0, count($stepIds), '?'));
        return [
            'subscribers' => (int) DB::value(
                'SELECT COUNT(DISTINCT subscriber_id) FROM automation_runs WHERE automation_id = ?', [$automationId]),
            'pending' => (int) DB::value(
                "SELECT COUNT(*) FROM automation_runs WHERE automation_id = ? AND status = 'pending'", [$automationId]),
            'sent' => (int) DB::value(
                "SELECT COUNT(*) FROM queue WHERE status = 'sent' AND step_id IN ($in)", $stepIds),
            'opens' => (int) DB::value(
                "SELECT COUNT(*) FROM events WHERE type = 'open' AND step_id IN ($in)", $stepIds),
            'clicks' => (int) DB::value(
                "SELECT COUNT(*) FROM events WHERE type = 'click' AND step_id IN ($in)", $stepIds),
        ];
    }

    /** Kennzahlen je Schritt für die Übersicht. */
    public static function stepStats(int $stepId): array
    {
        return [
            'sent'    => (int) DB::value("SELECT COUNT(*) FROM queue WHERE step_id = ? AND status = 'sent'", [$stepId]),
            'pending' => (int) DB::value("SELECT COUNT(*) FROM automation_runs WHERE step_id = ? AND status = 'pending'", [$stepId]),
            'opens'   => (int) DB::value("SELECT COUNT(DISTINCT subscriber_id) FROM events WHERE step_id = ? AND type = 'open'", [$stepId]),
            'clicks'  => (int) DB::value("SELECT COUNT(DISTINCT subscriber_id) FROM events WHERE step_id = ? AND type = 'click'", [$stepId]),
        ];
    }
}
