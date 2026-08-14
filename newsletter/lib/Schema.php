<?php
/**
 * Schema – legt die Tabellen an und hält sie aktuell.
 *
 * Die DDL ist einmal generisch formuliert; Platzhalter werden je nach
 * Datenbank übersetzt:
 *   %PK%        Auto-Increment-Primärschlüssel
 *   %INT%       Ganzzahl
 *   %STR(n)%    kurzer Text, indizierbar (VARCHAR bei MySQL)
 *   %TEXT%      Langtext
 *   %DT%        Zeitstempel (als Text 'Y-m-d H:i:s')
 *   %ENGINE%    Tabellen-Suffix (nur MySQL)
 *
 * migrate() ist idempotent: mehrfaches Aufrufen ist unschädlich und
 * ergänzt fehlende Tabellen/Indizes/Spalten.
 */
final class Schema
{
    /** Version des Schemas – wird in settings gespeichert. */
    public const VERSION = 7;

    public static function migrate(): void
    {
        foreach (self::tables() as $sql) {
            DB::pdo()->exec(self::translate($sql));
        }
        foreach (self::indexes() as $sql) {
            // MySQL kennt kein "CREATE INDEX IF NOT EXISTS" – dort wird der
            // Zusatz entfernt und ein bereits vorhandener Index ignoriert.
            if (!DB::isSqlite()) {
                $sql = str_replace('CREATE INDEX IF NOT EXISTS', 'CREATE INDEX', $sql);
            }
            try {
                DB::pdo()->exec($sql);
            } catch (Throwable $e) {
                $msg = strtolower($e->getMessage());
                if (!str_contains($msg, 'duplicate') && !str_contains($msg, 'exist')) {
                    throw $e;
                }
            }
        }
        // Nachrüstung für Installationen aus einer früheren Fassung
        self::ensureColumn('campaigns', 'blocks_json', '%TEXT%');
        self::ensureColumn('campaigns', 'editor_mode', "%STR(10)% NOT NULL DEFAULT 'html'");
        self::ensureColumn('templates', 'blocks_json', '%TEXT%');
        self::ensureColumn('templates', 'editor_mode', "%STR(10)% NOT NULL DEFAULT 'html'");
        self::ensureColumn('users', 'status', "%STR(20)% NOT NULL DEFAULT 'active'");
        self::ensureColumn('automations', 'flow_json', '%TEXT%');
        self::ensureColumn('automation_runs', 'node_id', "%STR(24)% NOT NULL DEFAULT ''");
        self::ensureColumn('automation_runs', 'context_json', '%TEXT%');
        self::ensureColumn('automation_steps', 'blocks_json', '%TEXT%');
        self::ensureColumn('automation_steps', 'editor_mode', "%STR(10)% NOT NULL DEFAULT 'blocks'");
        // Eigene Marke je Vorlage – für mehrere Websites in einer Installation
        self::ensureColumn('templates', 'brand_name', "%STR(190)% NOT NULL DEFAULT ''");
        self::ensureColumn('templates', 'website_url', "%STR(190)% NOT NULL DEFAULT ''");
        self::ensureColumn('templates', 'imprint', '%TEXT%');
        self::ensureColumn('templates', 'imprint_url', "%STR(190)% NOT NULL DEFAULT ''");
        self::ensureColumn('templates', 'privacy_url', "%STR(190)% NOT NULL DEFAULT ''");
        self::ensureColumn('templates', 'sender_name', "%STR(190)% NOT NULL DEFAULT ''");
        self::ensureColumn('templates', 'sender_email', "%STR(190)% NOT NULL DEFAULT ''");
        // Sicherungskopie beim Wechsel von HTML in den Baukasten
        self::ensureColumn('templates', 'html_backup', '%TEXT%');
        /*
         * Marke einer Liste und einer Automation.
         *
         * Damit erscheinen auch Bestätigungs-, Willkommens- und
         * Abmeldemail in der Marke, für die sich jemand angemeldet hat –
         * vorher kam dort immer die Standardvorlage, also bei zwei Marken
         * die falsche Anschrift im Footer.
         */
        self::ensureColumn('lists', 'template_id', '%INT% NULL');
        self::ensureColumn('automations', 'template_id', '%INT% NULL');

        Settings::set('schema_version', (string) self::VERSION);
    }

    /**
     * Ergänzt eine Spalte, falls sie noch fehlt.
     * So bekommen bestehende Installationen neue Funktionen, ohne dass
     * jemand von Hand in der Datenbank arbeiten muss.
     */
    public static function ensureColumn(string $table, string $column, string $definition): bool
    {
        if (in_array($column, self::columns($table), true)) {
            return false;
        }
        DB::pdo()->exec('ALTER TABLE ' . $table . ' ADD COLUMN ' . $column . ' ' . self::translate($definition));
        Log::info('schema', 'Spalte ' . $table . '.' . $column . ' ergänzt.');
        return true;
    }

    /** @return string[] Spaltennamen einer Tabelle */
    public static function columns(string $table): array
    {
        try {
            if (DB::isSqlite()) {
                $rows = DB::all('PRAGMA table_info(' . $table . ')');
                return array_map(static fn($r) => (string) $r['name'], $rows);
            }
            $rows = DB::all('SHOW COLUMNS FROM ' . $table);
            return array_map(static fn($r) => (string) ($r['Field'] ?? ''), $rows);
        } catch (Throwable $e) {
            return [];
        }
    }

    public static function isInstalled(): bool
    {
        try {
            DB::value('SELECT COUNT(*) FROM settings');
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    private static function translate(string $sql): string
    {
        if (DB::isSqlite()) {
            $map = [
                '%PK%'     => 'INTEGER PRIMARY KEY AUTOINCREMENT',
                '%INT%'    => 'INTEGER',
                '%TEXT%'   => 'TEXT',
                '%DT%'     => 'TEXT',
                '%ENGINE%' => '',
            ];
            $sql = preg_replace('/%STR\((\d+)\)%/', 'TEXT', $sql) ?? $sql;
        } else {
            $map = [
                '%PK%'     => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                '%INT%'    => 'INT',
                '%TEXT%'   => 'LONGTEXT',
                '%DT%'     => 'VARCHAR(19)',
                '%ENGINE%' => ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
            ];
            $sql = preg_replace('/%STR\((\d+)\)%/', 'VARCHAR($1)', $sql) ?? $sql;
        }
        return strtr($sql, $map);
    }

    /** @return string[] */
    private static function tables(): array
    {
        return [
            // Einstellungen (Absender, SMTP, Versandtempo …)
            'CREATE TABLE IF NOT EXISTS settings (
                skey    %STR(100)% NOT NULL PRIMARY KEY,
                svalue  %TEXT%
            )%ENGINE%',

            // Administratoren
            'CREATE TABLE IF NOT EXISTS users (
                id            %PK%,
                email         %STR(190)% NOT NULL UNIQUE,
                name          %STR(190)% NOT NULL DEFAULT \'\',
                password_hash %STR(255)% NOT NULL,
                role          %STR(20)%  NOT NULL DEFAULT \'admin\',
                status        %STR(20)%  NOT NULL DEFAULT \'active\',
                created_at    %DT%,
                last_login_at %DT%
            )%ENGINE%',

            // Verteiler / Listen
            'CREATE TABLE IF NOT EXISTS lists (
                id          %PK%,
                name        %STR(190)% NOT NULL,
                description %TEXT%,
                is_default  %INT% NOT NULL DEFAULT 0,
                created_at  %DT%
            )%ENGINE%',

            // Empfänger
            'CREATE TABLE IF NOT EXISTS subscribers (
                id              %PK%,
                email           %STR(190)% NOT NULL UNIQUE,
                first_name      %STR(120)% NOT NULL DEFAULT \'\',
                last_name       %STR(120)% NOT NULL DEFAULT \'\',
                company         %STR(190)% NOT NULL DEFAULT \'\',
                salutation      %STR(20)%  NOT NULL DEFAULT \'\',
                status          %STR(20)%  NOT NULL DEFAULT \'pending\',
                token           %STR(64)%  NOT NULL UNIQUE,
                custom_json     %TEXT%,
                source          %STR(100)% NOT NULL DEFAULT \'\',
                signup_ip       %STR(64)%  NOT NULL DEFAULT \'\',
                confirm_ip      %STR(64)%  NOT NULL DEFAULT \'\',
                bounce_count    %INT% NOT NULL DEFAULT 0,
                created_at      %DT%,
                confirmed_at    %DT%,
                unsubscribed_at %DT%,
                last_sent_at    %DT%,
                note            %TEXT%
            )%ENGINE%',

            // Zuordnung Empfänger <-> Liste
            'CREATE TABLE IF NOT EXISTS subscriber_lists (
                subscriber_id %INT% NOT NULL,
                list_id       %INT% NOT NULL,
                created_at    %DT%,
                PRIMARY KEY (subscriber_id, list_id)
            )%ENGINE%',

            // Einwilligungs-Protokoll (Nachweispflicht DSGVO Art. 7 Abs. 1)
            'CREATE TABLE IF NOT EXISTS consent_log (
                id            %PK%,
                subscriber_id %INT% NOT NULL,
                email         %STR(190)% NOT NULL DEFAULT \'\',
                event         %STR(40)%  NOT NULL,
                detail        %TEXT%,
                ip            %STR(64)%  NOT NULL DEFAULT \'\',
                user_agent    %STR(255)% NOT NULL DEFAULT \'\',
                created_at    %DT%
            )%ENGINE%',

            // Sperrliste: diese Adressen werden nie wieder angeschrieben
            'CREATE TABLE IF NOT EXISTS suppression (
                email      %STR(190)% NOT NULL PRIMARY KEY,
                reason     %STR(60)% NOT NULL DEFAULT \'\',
                detail     %TEXT%,
                created_at %DT%
            )%ENGINE%',

            // Design-Vorlagen
            'CREATE TABLE IF NOT EXISTS templates (
                id          %PK%,
                name        %STR(190)% NOT NULL,
                description %STR(255)% NOT NULL DEFAULT \'\',
                html        %TEXT%,
                blocks_json %TEXT%,
                editor_mode %STR(10)% NOT NULL DEFAULT \'html\',
                brand_name  %STR(190)% NOT NULL DEFAULT \'\',
                website_url %STR(190)% NOT NULL DEFAULT \'\',
                imprint     %TEXT%,
                imprint_url %STR(190)% NOT NULL DEFAULT \'\',
                privacy_url %STR(190)% NOT NULL DEFAULT \'\',
                sender_name %STR(190)% NOT NULL DEFAULT \'\',
                sender_email %STR(190)% NOT NULL DEFAULT \'\',
                html_backup %TEXT%,
                is_default  %INT% NOT NULL DEFAULT 0,
                created_at  %DT%,
                updated_at  %DT%
            )%ENGINE%',

            // Kampagnen (einzelne Newsletter)
            'CREATE TABLE IF NOT EXISTS campaigns (
                id              %PK%,
                name            %STR(190)% NOT NULL,
                subject         %STR(255)% NOT NULL DEFAULT \'\',
                preheader       %STR(255)% NOT NULL DEFAULT \'\',
                from_name       %STR(190)% NOT NULL DEFAULT \'\',
                from_email      %STR(190)% NOT NULL DEFAULT \'\',
                reply_to        %STR(190)% NOT NULL DEFAULT \'\',
                template_id     %INT%,
                list_id         %INT%,
                content_html    %TEXT%,
                content_text    %TEXT%,
                compiled_html   %TEXT%,
                compiled_text   %TEXT%,
                blocks_json     %TEXT%,
                editor_mode     %STR(10)% NOT NULL DEFAULT \'html\',
                status          %STR(20)% NOT NULL DEFAULT \'draft\',
                track_opens     %INT% NOT NULL DEFAULT 1,
                track_clicks    %INT% NOT NULL DEFAULT 1,
                archive_public  %INT% NOT NULL DEFAULT 1,
                total_recipients %INT% NOT NULL DEFAULT 0,
                scheduled_at    %DT%,
                started_at      %DT%,
                finished_at     %DT%,
                created_at      %DT%,
                updated_at      %DT%
            )%ENGINE%',

            // Automationen (z. B. Willkommensstrecke)
            'CREATE TABLE IF NOT EXISTS automations (
                id           %PK%,
                name         %STR(190)% NOT NULL,
                trigger_type %STR(40)% NOT NULL DEFAULT \'confirm\',
                list_id      %INT%,
                flow_json    %TEXT%,
                status       %STR(20)% NOT NULL DEFAULT \'paused\',
                created_at   %DT%,
                updated_at   %DT%
            )%ENGINE%',

            'CREATE TABLE IF NOT EXISTS automation_steps (
                id            %PK%,
                automation_id %INT% NOT NULL,
                position      %INT% NOT NULL DEFAULT 1,
                delay_hours   %INT% NOT NULL DEFAULT 0,
                subject       %STR(255)% NOT NULL DEFAULT \'\',
                template_id   %INT%,
                content_html  %TEXT%,
                content_text  %TEXT%,
                compiled_html %TEXT%,
                compiled_text %TEXT%,
                blocks_json   %TEXT%,
                editor_mode   %STR(10)% NOT NULL DEFAULT \'blocks\',
                track_opens   %INT% NOT NULL DEFAULT 1,
                track_clicks  %INT% NOT NULL DEFAULT 1,
                created_at    %DT%,
                updated_at    %DT%
            )%ENGINE%',

            // Läuft ein Empfänger gerade durch eine Automation?
            'CREATE TABLE IF NOT EXISTS automation_runs (
                id            %PK%,
                automation_id %INT% NOT NULL,
                subscriber_id %INT% NOT NULL,
                step_id       %INT%,
                node_id       %STR(24)% NOT NULL DEFAULT \'\',
                context_json  %TEXT%,
                status        %STR(20)% NOT NULL DEFAULT \'pending\',
                due_at        %DT%,
                queued_at     %DT%,
                created_at    %DT%
            )%ENGINE%',

            // Versandwarteschlange – eine Zeile je Empfänger und Mail
            'CREATE TABLE IF NOT EXISTS queue (
                id            %PK%,
                campaign_id   %INT%,
                step_id       %INT%,
                subscriber_id %INT% NOT NULL,
                email         %STR(190)% NOT NULL,
                token         %STR(64)% NOT NULL UNIQUE,
                status        %STR(20)% NOT NULL DEFAULT \'pending\',
                attempts      %INT% NOT NULL DEFAULT 0,
                last_error    %TEXT%,
                message_id    %STR(190)% NOT NULL DEFAULT \'\',
                due_at        %DT%,
                locked_at     %DT%,
                sent_at       %DT%,
                created_at    %DT%
            )%ENGINE%',

            // Links einer Kampagne (für Klick-Tracking)
            'CREATE TABLE IF NOT EXISTS links (
                id          %PK%,
                campaign_id %INT%,
                step_id     %INT%,
                url         %TEXT%,
                url_hash    %STR(40)% NOT NULL,
                label       %STR(190)% NOT NULL DEFAULT \'\',
                created_at  %DT%
            )%ENGINE%',

            // Ereignisse: gesendet, geöffnet, geklickt, abgemeldet, Bounce …
            'CREATE TABLE IF NOT EXISTS events (
                id            %PK%,
                type          %STR(20)% NOT NULL,
                campaign_id   %INT%,
                step_id       %INT%,
                subscriber_id %INT%,
                link_id       %INT%,
                queue_id      %INT%,
                detail        %TEXT%,
                ip            %STR(64)% NOT NULL DEFAULT \'\',
                user_agent    %STR(255)% NOT NULL DEFAULT \'\',
                created_at    %DT%
            )%ENGINE%',

            // Unzustellbarkeiten aus dem Bounce-Postfach
            'CREATE TABLE IF NOT EXISTS bounces (
                id          %PK%,
                email       %STR(190)% NOT NULL,
                campaign_id %INT%,
                bounce_type %STR(20)% NOT NULL DEFAULT \'soft\',
                code        %STR(20)% NOT NULL DEFAULT \'\',
                message     %TEXT%,
                created_at  %DT%
            )%ENGINE%',

            // Technisches Protokoll (Versand, Cron, Fehler)
            'CREATE TABLE IF NOT EXISTS logs (
                id         %PK%,
                level      %STR(20)% NOT NULL DEFAULT \'info\',
                channel    %STR(40)% NOT NULL DEFAULT \'app\',
                message    %TEXT%,
                created_at %DT%
            )%ENGINE%',

            // Selbst gesicherte Bausteine, die sich wiederverwenden lassen
            'CREATE TABLE IF NOT EXISTS snippets (
                id          %PK%,
                name        %STR(190)% NOT NULL,
                kind        %STR(20)% NOT NULL DEFAULT \'block\',
                blocks_json %TEXT%,
                created_by  %STR(190)%,
                created_at  %DT%,
                used_at     %DT%
            )%ENGINE%',

            // Rate-Limits (Anmeldeformular, Admin-Login)
            'CREATE TABLE IF NOT EXISTS rate_limits (
                id         %PK%,
                action     %STR(40)% NOT NULL,
                ref        %STR(190)% NOT NULL,
                created_at %DT%
            )%ENGINE%',
        ];
    }

    /** @return string[] */
    private static function indexes(): array
    {
        return [
            'CREATE INDEX IF NOT EXISTS idx_subscribers_status ON subscribers (status)',
            'CREATE INDEX IF NOT EXISTS idx_subscribers_created ON subscribers (created_at)',
            'CREATE INDEX IF NOT EXISTS idx_sublists_list ON subscriber_lists (list_id)',
            'CREATE INDEX IF NOT EXISTS idx_consent_sub ON consent_log (subscriber_id)',
            'CREATE INDEX IF NOT EXISTS idx_queue_status ON queue (status, due_at)',
            'CREATE INDEX IF NOT EXISTS idx_queue_campaign ON queue (campaign_id, status)',
            'CREATE INDEX IF NOT EXISTS idx_queue_step ON queue (step_id, status)',
            'CREATE INDEX IF NOT EXISTS idx_events_campaign ON events (campaign_id, type)',
            'CREATE INDEX IF NOT EXISTS idx_events_sub ON events (subscriber_id, type)',
            'CREATE INDEX IF NOT EXISTS idx_events_created ON events (created_at)',
            'CREATE INDEX IF NOT EXISTS idx_links_campaign ON links (campaign_id)',
            'CREATE INDEX IF NOT EXISTS idx_runs_state ON automation_runs (status, due_at)',
            'CREATE INDEX IF NOT EXISTS idx_runs_sub ON automation_runs (subscriber_id, automation_id)',
            'CREATE INDEX IF NOT EXISTS idx_rate ON rate_limits (action, ref, created_at)',
            'CREATE INDEX IF NOT EXISTS idx_logs_created ON logs (created_at)',
            'CREATE INDEX IF NOT EXISTS idx_bounces_email ON bounces (email)',
        ];
    }
}
