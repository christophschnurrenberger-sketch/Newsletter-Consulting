<?php
/**
 * Automations – zeitgesteuerte Mailstrecken (z. B. Willkommensserie).
 *
 * Auslöser ist die bestätigte Anmeldung. Der Ablauf selbst wird im
 * Baukasten zusammengezogen (lib/Flow.php): warten, Mail senden, Bedingung
 * prüfen, Aktion ausführen. Jeder Empfänger bekommt einen Lauf, der Schritt
 * für Schritt durch den Ablauf wandert; der Cron-Job schiebt ihn weiter.
 *
 * Die Inhalte der Mails stecken weiterhin in automation_steps – so nutzt
 * der Versand dieselbe geprüfte Warteschlange wie normale Kampagnen.
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

    /**
     * @param int|null $templateId Marke der Strecke; neue Schritte erben sie
     */
    public static function create(string $name, ?int $listId = null, ?int $templateId = null): int
    {
        $now = Util::now();
        return DB::insert('automations', [
            'name'         => mb_substr(trim($name), 0, 190) ?: 'Neue Strecke',
            'trigger_type' => self::TRIGGER_CONFIRM,
            'list_id'      => $listId,
            'template_id'  => $templateId !== null && $templateId > 0 ? $templateId : null,
            'status'       => self::PAUSED,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
    }

    /** Die Vorlage (Marke) einer Strecke – sonst die Standardvorlage. */
    public static function template(int $automationId): ?array
    {
        $row = DB::row('SELECT template_id FROM automations WHERE id = ?', [$automationId]);
        $eigen = $row !== null && $row['template_id'] !== null
            ? Templates::byId((int) $row['template_id'])
            : null;
        return $eigen ?? Templates::defaultTemplate();
    }

    /** @param array<string,mixed> $data */
    public static function save(int $id, array $data): void
    {
        $update = [];
        foreach (['name', 'list_id', 'status', 'template_id'] as $field) {
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

    /* ----------------------------------------------------------- Ablauf */

    /**
     * Der Ablauf einer Strecke. Ältere Strecken ohne Ablauf werden aus
     * ihren Schritten übersetzt, damit nichts verloren geht.
     */
    public static function flow(array $automation): array
    {
        $json = trim((string) ($automation['flow_json'] ?? ''));
        if ($json !== '') {
            return Flow::parse($json);
        }

        $nodes  = [];
        $bisher = 0;
        foreach (self::steps((int) $automation['id']) as $step) {
            $wartezeit = max(0, (int) $step['delay_hours'] - $bisher);
            $bisher    = (int) $step['delay_hours'];
            if ($wartezeit > 0) {
                $nodes[] = Flow::node('warten', ['value' => $wartezeit, 'einheit' => 'stunden']);
            }
            $nodes[] = Flow::node('mail', ['step_id' => (int) $step['id']]);
        }
        return ['nodes' => $nodes];
    }

    /**
     * Speichert den Ablauf aus dem Baukasten.
     * Jeder Mail-Knoten bekommt dabei einen Datensatz für seinen Inhalt;
     * Schritte ohne Knoten werden entfernt.
     */
    public static function saveFlow(int $automationId, string $json): void
    {
        $flow = Flow::parse($json);
        $verwendet = [];

        $flow['nodes'] = self::mapNodes($flow['nodes'], function (array $node) use ($automationId, &$verwendet): array {
            if ($node['type'] !== 'mail') {
                return $node;
            }
            $stepId = (int) $node['step_id'];
            $step   = $stepId > 0 ? self::step($stepId) : null;
            if ($step === null || (int) $step['automation_id'] !== $automationId) {
                $stepId = self::addStep($automationId, 0);
            }
            $node['step_id'] = $stepId;
            $verwendet[]     = $stepId;
            return $node;
        });

        foreach (self::steps($automationId) as $step) {
            if (!in_array((int) $step['id'], $verwendet, true)) {
                self::deleteStep((int) $step['id']);
            }
        }

        DB::update('automations', [
            'flow_json'  => (string) json_encode($flow, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'updated_at' => Util::now(),
        ], 'id = ?', [$automationId]);
    }

    /**
     * Wendet eine Funktion auf alle Knoten an – auch in den Zweigen.
     * @param array<int,array<string,mixed>> $nodes
     * @return array<int,array<string,mixed>>
     */
    private static function mapNodes(array $nodes, callable $fn): array
    {
        $out = [];
        foreach ($nodes as $node) {
            $node = $fn($node);
            if (($node['type'] ?? '') === 'bedingung') {
                $node['ja']   = self::mapNodes((array) ($node['ja'] ?? []), $fn);
                $node['nein'] = self::mapNodes((array) ($node['nein'] ?? []), $fn);
            }
            $out[] = $node;
        }
        return $out;
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
        // Der Schritt erbt Marke, Schriften und Farben der Strecke
        $template = self::template($automationId);
        $start    = Blocks::starterCampaign($template);
        return DB::insert('automation_steps', [
            'automation_id' => $automationId,
            'position'      => $position,
            'delay_hours'   => max(0, $delayHours),
            'subject'       => '',
            'template_id'   => $template !== null ? (int) $template['id'] : null,
            'content_html'  => Blocks::renderContent($start),
            'content_text'  => Blocks::toText($start),
            'blocks_json'   => (string) json_encode($start, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'editor_mode'   => 'blocks',
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
                    'content_html', 'content_text', 'track_opens', 'track_clicks',
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

        // Im Baukasten entstehen HTML und Textfassung aus den Bausteinen
        if (($update['editor_mode'] ?? '') === 'blocks' && isset($update['blocks_json'])) {
            $blocks = Blocks::parse((string) $update['blocks_json']);
            $update['blocks_json']  = (string) json_encode($blocks, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $update['content_html'] = Blocks::renderContent($blocks);
            $update['content_text'] = Blocks::toText($blocks);
        }

        $update['updated_at'] = Util::now();
        DB::update('automation_steps', $update, 'id = ?', [$stepId]);
        self::compileStep($stepId);
    }

    /**
     * Baut alle Schritte neu, die diese Vorlage benutzen.
     *
     * Nötig, wenn sich die Vorlage oder ihre Marke geändert hat: Schritte
     * halten eine fertig kompilierte Fassung, die sonst veraltet.
     *
     * @return int Zahl der neu gebauten Schritte
     */
    public static function recompileForTemplate(int $templateId): int
    {
        $ids = DB::column('SELECT id FROM automation_steps WHERE template_id = ?', [$templateId]);
        foreach ($ids as $id) {
            self::compileStep((int) $id);
        }
        return count($ids);
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

    /** Bausteine eines Schrittes (leer = noch nie im Baukasten bearbeitet). */
    public static function stepBlocks(array $step): array
    {
        $json = (string) ($step['blocks_json'] ?? '');
        return trim($json) === '' ? Blocks::starterCampaign() : Blocks::parse($json);
    }

    public static function stepUsesBuilder(array $step): bool
    {
        return ($step['editor_mode'] ?? 'html') === 'blocks';
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
        $html = Renderer::applyBrand($html, $template, true);
        $html = Renderer::compile($html, null, $stepId,
            (int) $step['track_clicks'] === 1, (int) $step['track_opens'] === 1);

        $text = trim((string) $step['content_text']);
        if ($text === '') {
            $text = Mailer::htmlToText((string) $step['content_html']);
        }
        $text .= "\n\n-- \n{{impressum}}\n\n"
              . "Newsletter abbestellen: {{abmelden_url}}\n"
              . "Daten & Einstellungen: {{praeferenzen_url}}\n";
        $text = Renderer::applyBrand($text, $template, false);

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
        // Absender aus der Vorlage, damit eine zweite Marke unter ihrem
        // eigenen Namen verschickt; ohne eigene Angabe gelten die Einstellungen.
        $brand = Templates::brand(Templates::byId(
            $step['template_id'] !== null ? (int) $step['template_id'] : null
        ));

        return [
            'id'            => 0,
            'name'          => 'Automation Schritt ' . $step['position'],
            'subject'       => $step['subject'],
            'preheader'     => '',
            'from_name'     => $brand['sender_name'],
            'from_email'    => $brand['sender_email'],
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
        $automation = self::byId($automationId);
        if ($automation === null) {
            return 0;
        }
        $index = Flow::index(self::flow($automation));
        if ($index['first'] === null) {
            return 0; // leerer Ablauf
        }

        DB::insert('automation_runs', [
            'automation_id' => $automationId,
            'subscriber_id' => $subscriberId,
            'node_id'       => $index['first'],
            'status'        => 'pending',
            'due_at'        => Util::now(),
            'created_at'    => Util::now(),
        ]);
        return 1;
    }

    /** Offene Läufe eines Empfängers beenden (Abmeldung, Sperre, Löschung). */
    public static function cancelFor(int $subscriberId): void
    {
        DB::run("UPDATE automation_runs SET status = 'cancelled' WHERE subscriber_id = ? AND status = 'pending'",
            [$subscriberId]);
    }

    /* ------------------------------------------------------------ Cron-Teil */

    /**
     * Schiebt fällige Läufe durch den Ablauf.
     *
     * Ein Lauf wandert so lange von Knoten zu Knoten, bis er auf eine
     * Wartezeit trifft, eine Mail einreiht oder das Ende erreicht.
     * Wird vom Cron-Job über Queue::process() aufgerufen.
     *
     * @return int Anzahl neu eingereihter Mails
     */
    public static function tick(int $max = 200): int
    {
        $faellig = DB::all(
            "SELECT r.* FROM automation_runs r
             JOIN automations a ON a.id = r.automation_id
             JOIN subscribers s ON s.id = r.subscriber_id
             WHERE r.status = 'pending'
               AND r.due_at <= ?
               AND a.status = 'active'
               AND s.status = 'active'
             ORDER BY r.due_at
             LIMIT " . max(1, $max),
            [Util::now()]
        );

        $eingereiht = 0;
        foreach ($faellig as $run) {
            $eingereiht += self::advance($run);
        }
        if ($eingereiht > 0) {
            Log::info('automation', $eingereiht . ' Automations-Mail(s) eingereiht.');
        }
        return $eingereiht;
    }

    /** Alter Name – bleibt erhalten, damit der Cron unverändert läuft. */
    public static function enqueueDue(): int
    {
        return self::tick();
    }

    /**
     * Bringt einen einzelnen Lauf voran.
     * @return int Anzahl eingereihter Mails (0 oder 1)
     */
    private static function advance(array $run): int
    {
        $runId      = (int) $run['id'];
        $automation = self::byId((int) $run['automation_id']);
        $subscriber = Subscribers::byId((int) $run['subscriber_id']);

        if ($automation === null || $subscriber === null
            || $subscriber['status'] !== Subscribers::STATUS_ACTIVE) {
            DB::update('automation_runs', ['status' => 'cancelled'], 'id = ?', [$runId]);
            return 0;
        }

        $index  = Flow::index(self::flow($automation));
        $nodeId = (string) $run['node_id'] !== '' ? (string) $run['node_id'] : $index['first'];
        $eingereiht = 0;

        // Begrenzung gegen Endlosläufe bei ungünstig gebauten Abläufen
        for ($schritt = 0; $schritt < 25; $schritt++) {
            if ($nodeId === null || !isset($index['nodes'][$nodeId])) {
                break; // Ende des Ablaufs oder Knoten wurde gelöscht
            }
            $node = $index['nodes'][$nodeId];

            switch ((string) $node['type']) {
                case 'warten':
                    DB::update('automation_runs', [
                        'node_id' => (string) ($index['next'][$nodeId] ?? ''),
                        'due_at'  => date('Y-m-d H:i:s', time() + Flow::seconds($node)),
                    ], 'id = ?', [$runId]);
                    return $eingereiht;

                case 'mail':
                    $eingereiht += self::queueMail($node, $subscriber, (int) $automation['id']);
                    // Kurze Pause: die Mail muss erst raus, bevor eine
                    // Bedingung sinnvoll prüfen kann, ob sie geöffnet wurde.
                    DB::update('automation_runs', [
                        'node_id' => (string) ($index['next'][$nodeId] ?? ''),
                        'due_at'  => date('Y-m-d H:i:s', time() + 300),
                    ], 'id = ?', [$runId]);
                    return $eingereiht;

                case 'bedingung':
                    $trifftZu = Flow::evaluate($node, $subscriber, (int) $automation['id']);
                    $nodeId   = $trifftZu ? ($index['ja'][$nodeId] ?? null) : ($index['nein'][$nodeId] ?? null);
                    continue 2;

                case 'aktion':
                    self::applyAction($node, $subscriber);
                    $subscriber = Subscribers::byId((int) $subscriber['id']) ?? $subscriber;
                    if ($subscriber['status'] !== Subscribers::STATUS_ACTIVE) {
                        DB::update('automation_runs', ['status' => 'done'], 'id = ?', [$runId]);
                        return $eingereiht;
                    }
                    $nodeId = $index['next'][$nodeId] ?? null;
                    continue 2;

                case 'ende':
                    $nodeId = null;
                    break 2;
            }
        }

        DB::update('automation_runs', [
            'status'  => 'done',
            'node_id' => '',
        ], 'id = ?', [$runId]);
        return $eingereiht;
    }

    /** Reiht die Mail eines Knotens in die Warteschlange ein. */
    private static function queueMail(array $node, array $subscriber, int $automationId): int
    {
        $step = self::step((int) $node['step_id']);
        if ($step === null || trim((string) $step['subject']) === '') {
            return 0; // unfertiger Schritt wird stillschweigend übersprungen
        }
        if (Subscribers::isSuppressed((string) $subscriber['email'])) {
            return 0;
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
        return 1;
    }

    /** Führt eine Aktion aus (Liste ändern, abmelden). */
    private static function applyAction(array $node, array $subscriber): void
    {
        $subscriberId = (int) $subscriber['id'];
        $listId       = (int) ($node['list_id'] ?? 0);

        switch ((string) $node['aktion']) {
            case 'liste_hinzufuegen':
                if ($listId > 0) {
                    Subscribers::addToLists($subscriberId, [$listId]);
                }
                break;
            case 'liste_entfernen':
                if ($listId > 0) {
                    DB::delete('subscriber_lists', 'subscriber_id = ? AND list_id = ?', [$subscriberId, $listId]);
                }
                break;
            case 'abmelden':
                Subscribers::unsubscribe($subscriber, 'Abmeldung durch eine Automation', null, false);
                break;
        }
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
