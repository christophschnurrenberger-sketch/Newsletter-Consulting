<?php
/**
 * Subscribers – Empfängerverwaltung mit Double-Opt-in.
 *
 * Status eines Empfängers:
 *   pending      – angemeldet, aber noch nicht bestätigt (erhält keine Newsletter)
 *   active       – bestätigt, erhält Newsletter
 *   unsubscribed – abgemeldet
 *   bounced      – Adresse unzustellbar
 *   complained   – hat sich über Spam beschwert
 */
final class Subscribers
{
    public const STATUS_PENDING      = 'pending';
    public const STATUS_ACTIVE       = 'active';
    public const STATUS_UNSUBSCRIBED = 'unsubscribed';
    public const STATUS_BOUNCED      = 'bounced';
    public const STATUS_COMPLAINED   = 'complained';

    /** @return array<string,string> */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING      => 'Unbestätigt',
            self::STATUS_ACTIVE       => 'Aktiv',
            self::STATUS_UNSUBSCRIBED => 'Abgemeldet',
            self::STATUS_BOUNCED      => 'Unzustellbar',
            self::STATUS_COMPLAINED   => 'Beschwerde',
        ];
    }

    /* --------------------------------------------------------------- Lesen */

    public static function byId(int $id): ?array
    {
        return DB::row('SELECT * FROM subscribers WHERE id = ?', [$id]);
    }

    public static function byEmail(string $email): ?array
    {
        return DB::row('SELECT * FROM subscribers WHERE email = ?', [Util::normalizeEmail($email)]);
    }

    public static function byToken(string $token): ?array
    {
        if ($token === '') {
            return null;
        }
        return DB::row('SELECT * FROM subscribers WHERE token = ?', [$token]);
    }

    /** Anzeigename für Anrede und Listenansicht. */
    public static function displayName(array $sub): string
    {
        $name = trim(($sub['first_name'] ?? '') . ' ' . ($sub['last_name'] ?? ''));
        return $name !== '' ? $name : (string) $sub['email'];
    }

    /** @return int[] IDs der Listen, in denen der Empfänger steht */
    public static function listIds(int $subscriberId): array
    {
        return array_map('intval', DB::column(
            'SELECT list_id FROM subscriber_lists WHERE subscriber_id = ?',
            [$subscriberId]
        ));
    }

    /* ------------------------------------------------------------ Anmelden */

    /**
     * Anmeldung aus dem Formular: legt den Empfänger als "pending" an und
     * verschickt die Bestätigungsmail (Double-Opt-in).
     *
     * @param array<string,mixed> $data  first_name, last_name, company, salutation, custom
     * @param int[]               $listIds
     * @return array{status:string,subscriber:array,message:string}
     */
    public static function signup(string $email, array $data = [], array $listIds = [], string $source = 'website'): array
    {
        $email = Util::normalizeEmail($email);
        if (!Util::isEmail($email)) {
            throw new InvalidArgumentException('Bitte geben Sie eine gültige E-Mail-Adresse an.');
        }
        if (self::isSuppressed($email)) {
            // Gesperrte Adressen bekommen keine Mail mehr, die Antwort bleibt
            // aber neutral (kein Rückschluss auf gespeicherte Adressen).
            return [
                'status'     => 'suppressed',
                'subscriber' => [],
                'message'    => 'Bitte prüfen Sie Ihr Postfach und bestätigen Sie die Anmeldung.',
            ];
        }

        $listIds = self::validListIds($listIds);
        $now     = Util::now();
        $sub     = self::byEmail($email);

        if ($sub === null) {
            $id = DB::insert('subscribers', [
                'email'       => $email,
                'first_name'  => self::clean($data['first_name'] ?? '', 120),
                'last_name'   => self::clean($data['last_name'] ?? '', 120),
                'company'     => self::clean($data['company'] ?? '', 190),
                'salutation'  => self::clean($data['salutation'] ?? '', 20),
                'status'      => self::STATUS_PENDING,
                'token'       => self::freshToken(),
                'custom_json' => self::encodeCustom($data['custom'] ?? []),
                'source'      => self::clean($source, 100),
                'signup_ip'   => Util::storeIp(),
                'created_at'  => $now,
            ]);
            $sub = self::byId($id);
            self::setLists($id, $listIds);
            self::logConsent($id, $email, 'signup', 'Anmeldung über: ' . $source);
            SystemMails::sendDoubleOptIn($sub);
            self::notifyOwner($sub, 'neue Anmeldung (unbestätigt)');

            return [
                'status'     => 'created',
                'subscriber' => $sub,
                'message'    => 'Fast geschafft: Wir haben Ihnen eine E-Mail geschickt. Bitte bestätigen Sie darin Ihre Anmeldung.',
            ];
        }

        // Bereits vorhanden – je nach Status unterschiedlich behandeln
        $update = array_filter([
            'first_name' => self::clean($data['first_name'] ?? '', 120),
            'last_name'  => self::clean($data['last_name'] ?? '', 120),
            'company'    => self::clean($data['company'] ?? '', 190),
            'salutation' => self::clean($data['salutation'] ?? '', 20),
        ], static fn($v) => $v !== '');
        if ($update !== []) {
            DB::update('subscribers', $update, 'id = ?', [(int) $sub['id']]);
        }
        if ($listIds !== []) {
            self::addToLists((int) $sub['id'], $listIds);
        }
        $sub = self::byId((int) $sub['id']);

        if ($sub['status'] === self::STATUS_ACTIVE) {
            self::logConsent((int) $sub['id'], $email, 'signup_duplicate', 'Anmeldung, obwohl bereits aktiv');
            return [
                'status'     => 'already_active',
                'subscriber' => $sub,
                'message'    => 'Diese Adresse ist bereits für den Newsletter angemeldet.',
            ];
        }

        // pending / abgemeldet / bounced → neue Bestätigungsmail
        DB::update('subscribers', [
            'status'          => self::STATUS_PENDING,
            'token'           => self::freshToken(),
            'signup_ip'       => Util::storeIp(),
            'created_at'      => $now,
            'unsubscribed_at' => null,
            'bounce_count'    => 0,
        ], 'id = ?', [(int) $sub['id']]);
        $sub = self::byId((int) $sub['id']);
        self::logConsent((int) $sub['id'], $email, 'signup_again', 'Erneute Anmeldung über: ' . $source);
        SystemMails::sendDoubleOptIn($sub);

        return [
            'status'     => 'resent',
            'subscriber' => $sub,
            'message'    => 'Fast geschafft: Wir haben Ihnen eine E-Mail geschickt. Bitte bestätigen Sie darin Ihre Anmeldung.',
        ];
    }

    /**
     * Bestätigt die Anmeldung (Klick im Double-Opt-in-Link).
     *
     * @return array{ok:bool,subscriber:?array,message:string}
     */
    public static function confirm(string $token): array
    {
        $sub = self::byToken($token);
        if ($sub === null) {
            return ['ok' => false, 'subscriber' => null, 'message' => 'Dieser Bestätigungslink ist ungültig oder wurde bereits ersetzt.'];
        }
        if ($sub['status'] === self::STATUS_ACTIVE) {
            return ['ok' => true, 'subscriber' => $sub, 'message' => 'Ihre Anmeldung war bereits bestätigt – Sie müssen nichts weiter tun.'];
        }

        DB::update('subscribers', [
            'status'          => self::STATUS_ACTIVE,
            'confirmed_at'    => Util::now(),
            'confirm_ip'      => Util::storeIp(),
            'unsubscribed_at' => null,
        ], 'id = ?', [(int) $sub['id']]);

        self::logConsent((int) $sub['id'], (string) $sub['email'], 'confirm', 'Double-Opt-in bestätigt');
        DB::delete('suppression', 'email = ?', [(string) $sub['email']]);

        $sub = self::byId((int) $sub['id']);
        if (Settings::bool('welcome_enabled')) {
            SystemMails::sendWelcome($sub);
        }
        Automations::onConfirm($sub);
        self::notifyOwner($sub, 'Anmeldung bestätigt');

        return ['ok' => true, 'subscriber' => $sub, 'message' => 'Vielen Dank! Ihre Anmeldung ist bestätigt.'];
    }

    /* ------------------------------------------------------------ Abmelden */

    public static function unsubscribe(array $sub, string $reason = '', ?int $campaignId = null, bool $sendGoodbye = true): void
    {
        if ($sub['status'] === self::STATUS_UNSUBSCRIBED) {
            return;
        }
        DB::update('subscribers', [
            'status'          => self::STATUS_UNSUBSCRIBED,
            'unsubscribed_at' => Util::now(),
        ], 'id = ?', [(int) $sub['id']]);

        self::logConsent((int) $sub['id'], (string) $sub['email'], 'unsubscribe', $reason);
        Events::record('unsubscribe', [
            'campaign_id'   => $campaignId,
            'subscriber_id' => (int) $sub['id'],
            'detail'        => $reason,
        ]);
        // Offene Sendungen dieses Empfängers nicht mehr ausliefern
        DB::run("UPDATE queue SET status = 'skipped', last_error = 'Empfänger hat sich abgemeldet'
                 WHERE subscriber_id = ? AND status = 'pending'", [(int) $sub['id']]);
        Automations::cancelFor((int) $sub['id']);

        if ($sendGoodbye && Settings::bool('goodbye_enabled')) {
            SystemMails::sendGoodbye($sub);
        }
    }

    /** Adresse dauerhaft sperren (Bounce, Beschwerde, manuell). */
    public static function suppress(string $email, string $reason, string $detail = ''): void
    {
        $email  = Util::normalizeEmail($email);
        $exists = (int) DB::value('SELECT COUNT(*) FROM suppression WHERE email = ?', [$email]);
        if ($exists === 0) {
            DB::insert('suppression', [
                'email'      => $email,
                'reason'     => mb_substr($reason, 0, 60),
                'detail'     => mb_substr($detail, 0, 1000),
                'created_at' => Util::now(),
            ]);
        }
        $sub = self::byEmail($email);
        if ($sub !== null) {
            $status = $reason === 'complaint' ? self::STATUS_COMPLAINED : self::STATUS_BOUNCED;
            DB::update('subscribers', ['status' => $status], 'id = ?', [(int) $sub['id']]);
            DB::run("UPDATE queue SET status = 'skipped', last_error = 'Adresse gesperrt'
                     WHERE subscriber_id = ? AND status = 'pending'", [(int) $sub['id']]);
            Automations::cancelFor((int) $sub['id']);
            self::logConsent((int) $sub['id'], $email, 'suppressed', $reason . ' ' . $detail);
        }
    }

    public static function unsuppress(string $email): void
    {
        DB::delete('suppression', 'email = ?', [Util::normalizeEmail($email)]);
    }

    public static function isSuppressed(string $email): bool
    {
        return (int) DB::value(
            'SELECT COUNT(*) FROM suppression WHERE email = ?',
            [Util::normalizeEmail($email)]
        ) > 0;
    }

    /**
     * Löscht einen Empfänger vollständig (DSGVO Art. 17).
     * Ereignisse bleiben anonym erhalten, damit Kampagnen-Statistiken stimmen.
     */
    public static function deleteCompletely(int $id, bool $keepSuppression = false): void
    {
        $sub = self::byId($id);
        if ($sub === null) {
            return;
        }
        DB::transaction(static function () use ($sub, $id, $keepSuppression) {
            if ($keepSuppression) {
                self::suppress((string) $sub['email'], 'geloescht', 'Auf Wunsch gelöscht');
            }
            DB::delete('subscriber_lists', 'subscriber_id = ?', [$id]);
            DB::delete('consent_log', 'subscriber_id = ?', [$id]);
            DB::delete('automation_runs', 'subscriber_id = ?', [$id]);
            DB::delete('queue', 'subscriber_id = ? AND status IN (\'pending\', \'failed\')', [$id]);
            DB::run('UPDATE events SET subscriber_id = NULL WHERE subscriber_id = ?', [$id]);
            DB::run("UPDATE queue SET email = '' WHERE subscriber_id = ?", [$id]);
            DB::delete('subscribers', 'id = ?', [$id]);
        });
    }

    /* --------------------------------------------------------------- Listen */

    /** @param int[] $listIds */
    public static function setLists(int $subscriberId, array $listIds): void
    {
        DB::delete('subscriber_lists', 'subscriber_id = ?', [$subscriberId]);
        self::addToLists($subscriberId, $listIds);
    }

    /** @param int[] $listIds */
    public static function addToLists(int $subscriberId, array $listIds): void
    {
        foreach (self::validListIds($listIds) as $listId) {
            $exists = (int) DB::value(
                'SELECT COUNT(*) FROM subscriber_lists WHERE subscriber_id = ? AND list_id = ?',
                [$subscriberId, $listId]
            );
            if ($exists === 0) {
                DB::insert('subscriber_lists', [
                    'subscriber_id' => $subscriberId,
                    'list_id'       => $listId,
                    'created_at'    => Util::now(),
                ]);
            }
        }
    }

    /**
     * Filtert übergebene Listen-IDs gegen die tatsächlich vorhandenen Listen.
     * Ohne Treffer wird die Standardliste verwendet.
     *
     * @param int[] $listIds
     * @return int[]
     */
    public static function validListIds(array $listIds): array
    {
        $existing = array_map('intval', DB::column('SELECT id FROM lists'));
        $valid    = array_values(array_intersect(array_map('intval', $listIds), $existing));
        if ($valid === []) {
            $default = Lists::defaultId();
            if ($default > 0) {
                $valid = [$default];
            }
        }
        return $valid;
    }

    /* ------------------------------------------------------- Einwilligung */

    public static function logConsent(int $subscriberId, string $email, string $event, string $detail = ''): void
    {
        DB::insert('consent_log', [
            'subscriber_id' => $subscriberId,
            'email'         => $email,
            'event'         => $event,
            'detail'        => mb_substr($detail, 0, 1000),
            'ip'            => Util::storeIp(),
            'user_agent'    => Util::userAgent(),
            'created_at'    => Util::now(),
        ]);
    }

    /** @return array<int,array<string,mixed>> */
    public static function consentLog(int $subscriberId): array
    {
        return DB::all(
            'SELECT * FROM consent_log WHERE subscriber_id = ? ORDER BY id DESC',
            [$subscriberId]
        );
    }

    /* ------------------------------------------------------------ Auswahl */

    /**
     * Aktive Empfänger einer Liste (0 = alle Listen), ohne gesperrte Adressen.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function activeForList(?int $listId): array
    {
        $sql = "SELECT s.* FROM subscribers s
                WHERE s.status = 'active'
                  AND NOT EXISTS (SELECT 1 FROM suppression sp WHERE sp.email = s.email)";
        $params = [];
        if ($listId !== null && $listId > 0) {
            $sql .= ' AND EXISTS (SELECT 1 FROM subscriber_lists sl WHERE sl.subscriber_id = s.id AND sl.list_id = ?)';
            $params[] = $listId;
        }
        return DB::all($sql . ' ORDER BY s.id', $params);
    }

    public static function countActiveForList(?int $listId): int
    {
        $sql = "SELECT COUNT(*) FROM subscribers s
                WHERE s.status = 'active'
                  AND NOT EXISTS (SELECT 1 FROM suppression sp WHERE sp.email = s.email)";
        $params = [];
        if ($listId !== null && $listId > 0) {
            $sql .= ' AND EXISTS (SELECT 1 FROM subscriber_lists sl WHERE sl.subscriber_id = s.id AND sl.list_id = ?)';
            $params[] = $listId;
        }
        return (int) DB::value($sql, $params);
    }

    /** @return array<string,int> Anzahl je Status */
    public static function statusCounts(): array
    {
        $counts = array_fill_keys(array_keys(self::statusLabels()), 0);
        foreach (DB::all('SELECT status, COUNT(*) AS anzahl FROM subscribers GROUP BY status') as $row) {
            $counts[(string) $row['status']] = (int) $row['anzahl'];
        }
        return $counts;
    }

    /* -------------------------------------------------------------- Import */

    /**
     * Importiert Empfänger aus CSV-Zeilen.
     *
     * @param array<int,array<string,string>> $rows   Spalten: email, first_name, last_name, company
     * @param int[] $listIds
     * @param string $status 'active' (bestehende Einwilligung) oder 'pending' (Double-Opt-in anstoßen)
     * @return array{imported:int,updated:int,skipped:int,errors:string[]}
     */
    public static function import(array $rows, array $listIds, string $status = 'active', string $source = 'import'): array
    {
        $listIds = self::validListIds($listIds);
        $result  = ['imported' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => []];
        $now     = Util::now();

        foreach ($rows as $index => $row) {
            $email = Util::normalizeEmail((string) ($row['email'] ?? ''));
            if (!Util::isEmail($email)) {
                $result['skipped']++;
                if (count($result['errors']) < 20) {
                    $result['errors'][] = 'Zeile ' . ($index + 1) . ': ungültige Adresse "' . Util::shorten((string) ($row['email'] ?? ''), 40) . '"';
                }
                continue;
            }
            if (self::isSuppressed($email)) {
                $result['skipped']++;
                if (count($result['errors']) < 20) {
                    $result['errors'][] = 'Zeile ' . ($index + 1) . ': ' . $email . ' steht auf der Sperrliste';
                }
                continue;
            }

            $existing = self::byEmail($email);
            $fields   = [
                'first_name' => self::clean($row['first_name'] ?? '', 120),
                'last_name'  => self::clean($row['last_name'] ?? '', 120),
                'company'    => self::clean($row['company'] ?? '', 190),
                'salutation' => self::clean($row['salutation'] ?? '', 20),
                'birthday'   => self::cleanDate((string) ($row['birthday'] ?? '')),
            ];

            if ($existing === null) {
                $data = $fields + [
                    'email'      => $email,
                    'status'     => $status,
                    'token'      => self::freshToken(),
                    'source'     => self::clean($source, 100),
                    'created_at' => $now,
                ];
                if ($status === self::STATUS_ACTIVE) {
                    $data['confirmed_at'] = $now;
                }
                $id = DB::insert('subscribers', $data);
                self::addToLists($id, $listIds);
                self::logConsent($id, $email, 'import', 'Import (' . $source . '), Status: ' . $status);
                if ($status === self::STATUS_PENDING) {
                    SystemMails::sendDoubleOptIn(self::byId($id));
                }
                $result['imported']++;
            } else {
                $update = array_filter($fields, static fn($v) => $v !== '');
                if ($update !== []) {
                    DB::update('subscribers', $update, 'id = ?', [(int) $existing['id']]);
                }
                self::addToLists((int) $existing['id'], $listIds);
                $result['updated']++;
            }
        }
        return $result;
    }

    /**
     * Liest eine CSV-Datei ein und ordnet die Spalten zu.
     *
     * @return array<int,array<string,string>>
     */
    public static function parseCsv(string $content): array
    {
        $content = self::stripBom($content);
        $delimiter = substr_count(explode("\n", $content)[0] ?? '', ';') >= substr_count(explode("\n", $content)[0] ?? '', ',')
            ? ';' : ',';

        $rows   = [];
        $handle = fopen('php://temp', 'r+');
        if ($handle === false) {
            return [];
        }
        fwrite($handle, $content);
        rewind($handle);

        $header = null;
        while (($line = fgetcsv($handle, 0, $delimiter, '"', '')) !== false) {
            if ($line === [null] || $line === false) {
                continue;
            }
            if ($header === null) {
                $header = array_map(static fn($h) => self::normalizeHeader((string) $h), $line);
                // Datei ohne Kopfzeile: erste Zeile ist bereits eine Adresse
                if (!in_array('email', $header, true)) {
                    $rows[] = ['email' => (string) ($line[0] ?? '')];
                    $header = ['email', 'first_name', 'last_name', 'company'];
                }
                continue;
            }
            $row = [];
            foreach ($header as $i => $name) {
                if ($name !== '') {
                    $row[$name] = trim((string) ($line[$i] ?? ''));
                }
            }
            if (array_filter($row) !== []) {
                $rows[] = $row;
            }
        }
        fclose($handle);
        return $rows;
    }

    private static function normalizeHeader(string $header): string
    {
        $h = mb_strtolower(trim($header));
        $map = [
            'e-mail' => 'email', 'email' => 'email', 'mail' => 'email', 'e-mail-adresse' => 'email',
            'vorname' => 'first_name', 'first_name' => 'first_name', 'firstname' => 'first_name',
            'nachname' => 'last_name', 'name' => 'last_name', 'last_name' => 'last_name', 'lastname' => 'last_name',
            'firma' => 'company', 'unternehmen' => 'company', 'company' => 'company',
            'anrede' => 'salutation', 'salutation' => 'salutation',
            'geburtstag' => 'birthday', 'geburtsdatum' => 'birthday', 'birthday' => 'birthday',
            'geb' => 'birthday', 'geburt' => 'birthday',
        ];
        return $map[$h] ?? '';
    }

    private static function stripBom(string $content): string
    {
        return str_starts_with($content, "\xEF\xBB\xBF") ? substr($content, 3) : $content;
    }

    /* ------------------------------------------------------------- Wartung */

    /** Unbestätigte Anmeldungen nach Ablauffrist löschen (Datensparsamkeit). */
    public static function purgeExpiredPending(): int
    {
        $days = Settings::int('doi_expire_days', 14);
        if ($days <= 0) {
            return 0;
        }
        $limit = date('Y-m-d H:i:s', time() - $days * 86400);
        $ids   = array_map('intval', DB::column(
            "SELECT id FROM subscribers WHERE status = 'pending' AND created_at < ?",
            [$limit]
        ));
        foreach ($ids as $id) {
            self::deleteCompletely($id);
        }
        return count($ids);
    }

    /* -------------------------------------------------------------- Intern */

    private static function freshToken(): string
    {
        do {
            $token = Util::token(24);
        } while (DB::value('SELECT COUNT(*) FROM subscribers WHERE token = ?', [$token]) > 0);
        return $token;
    }

    private static function clean($value, int $max): string
    {
        $value = is_string($value) ? trim($value) : '';
        $value = str_replace(["\r", "\n", "\t"], ' ', $value);
        return mb_substr($value, 0, $max);
    }

    /**
     * Ein Geburtsdatum säubern. Akzeptiert die gängigen Schreibweisen
     * (2004-05-13, 13.05.2004, 13.5.) und gibt immer JJJJ-MM-TT zurück –
     * bei einem Datum ohne Jahr mit dem Platzhalterjahr 1900.
     */
    public static function cleanDate(string $roh): string
    {
        $roh = trim($roh);
        if ($roh === '') {
            return '';
        }
        // JJJJ-MM-TT
        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $roh, $t)) {
            [$j, $m, $tag] = [(int) $t[1], (int) $t[2], (int) $t[3]];
        // TT.MM.JJJJ
        } elseif (preg_match('/^(\d{1,2})\.(\d{1,2})\.(\d{4})$/', $roh, $t)) {
            [$j, $m, $tag] = [(int) $t[3], (int) $t[2], (int) $t[1]];
        // TT.MM. (ohne Jahr)
        } elseif (preg_match('/^(\d{1,2})\.(\d{1,2})\.?$/', $roh, $t)) {
            [$j, $m, $tag] = [1900, (int) $t[2], (int) $t[1]];
        } else {
            return '';
        }
        if (!checkdate($m, $tag, $j)) {
            return '';
        }
        return sprintf('%04d-%02d-%02d', $j, $m, $tag);
    }

    private static function encodeCustom($custom): string
    {
        if (!is_array($custom) || $custom === []) {
            return '';
        }
        $clean = [];
        foreach ($custom as $key => $value) {
            $key = preg_replace('/[^a-z0-9_]/i', '', (string) $key) ?? '';
            if ($key !== '' && is_scalar($value)) {
                $clean[$key] = mb_substr((string) $value, 0, 500);
            }
        }
        return $clean === [] ? '' : (string) json_encode($clean, JSON_UNESCAPED_UNICODE);
    }

    /** @return array<string,string> */
    /**
     * Anlegen oder aktualisieren über die Schnittstelle (Schlüssel: E-Mail).
     * Behandelt Sperrliste, Geburtstag, eigene Felder (custom, z. B. die
     * Mitgliedsnummer), Listen, Einwilligungsprotokoll und – bei Status
     * „pending" – die Bestätigungsmail.
     *
     * @param array<string,mixed> $row
     * @param int[]               $listIds
     * @return array{outcome:string,id:?int,reason:string}
     */
    public static function apiUpsert(array $row, array $listIds, string $status = self::STATUS_ACTIVE,
                                     string $source = 'api'): array
    {
        $email = Util::normalizeEmail((string) ($row['email'] ?? ''));
        if (!Util::isEmail($email)) {
            return ['outcome' => 'skipped', 'id' => null, 'reason' => 'ungültige Adresse'];
        }
        if (self::isSuppressed($email)) {
            return ['outcome' => 'skipped', 'id' => null, 'reason' => 'steht auf der Sperrliste'];
        }
        $status  = in_array($status, [self::STATUS_ACTIVE, self::STATUS_PENDING], true) ? $status : self::STATUS_ACTIVE;
        $listIds = self::validListIds($listIds);
        $now     = Util::now();

        $fields = [
            'first_name' => self::clean($row['first_name'] ?? '', 120),
            'last_name'  => self::clean($row['last_name'] ?? '', 120),
            'company'    => self::clean($row['company'] ?? '', 190),
            'salutation' => self::clean($row['salutation'] ?? '', 20),
            'birthday'   => self::cleanDate((string) ($row['birthday'] ?? '')),
        ];
        $custom = (isset($row['custom']) && is_array($row['custom'])) ? $row['custom'] : [];

        $existing = self::byEmail($email);
        if ($existing === null) {
            $data = $fields + [
                'email'       => $email,
                'status'      => $status,
                'token'       => self::freshToken(),
                'source'      => self::clean($source, 100),
                'custom_json' => self::encodeCustom($custom),
                'created_at'  => $now,
            ];
            if ($status === self::STATUS_ACTIVE) {
                $data['confirmed_at'] = $now;
            }
            $id = DB::insert('subscribers', $data);
            self::addToLists($id, $listIds);
            self::logConsent($id, $email, 'api', 'API (' . $source . '), Status: ' . $status);
            if ($status === self::STATUS_PENDING) {
                SystemMails::sendDoubleOptIn(self::byId($id));
            }
            return ['outcome' => 'created', 'id' => $id, 'reason' => ''];
        }

        $id     = (int) $existing['id'];
        $update = array_filter($fields, static fn($v) => $v !== '');
        if ($custom !== []) {
            $merged = self::custom($existing);
            foreach ($custom as $k => $v) {
                if (is_scalar($v)) { $merged[(string) $k] = (string) $v; }
            }
            $update['custom_json'] = self::encodeCustom($merged);
        }
        if ($update !== []) {
            DB::update('subscribers', $update, 'id = ?', [$id]);
        }
        self::addToLists($id, $listIds);
        return ['outcome' => 'updated', 'id' => $id, 'reason' => ''];
    }

    public static function custom(array $sub): array
    {
        $raw = (string) ($sub['custom_json'] ?? '');
        if ($raw === '') {
            return [];
        }
        $data = json_decode($raw, true);
        return is_array($data) ? array_map('strval', $data) : [];
    }

    /** Optionale Info-Mail an den Betreiber. */
    private static function notifyOwner(array $sub, string $anlass): void
    {
        if (!Settings::bool('notify_on_signup')) {
            return;
        }
        $to = Settings::get('notify_email') ?: Settings::get('contact_email');
        if ($to === '' || !Util::isEmail($to)) {
            return;
        }
        try {
            Mailer::send([
                'to'      => $to,
                'subject' => 'Newsletter: ' . $anlass . ' – ' . $sub['email'],
                'text'    => "Anlass: $anlass\r\n"
                    . 'Adresse: ' . $sub['email'] . "\r\n"
                    . 'Name: ' . self::displayName($sub) . "\r\n"
                    . 'Quelle: ' . ($sub['source'] ?? '') . "\r\n"
                    . 'Zeitpunkt: ' . Util::now() . "\r\n",
                'headers' => ['Auto-Submitted' => 'auto-generated'],
            ]);
        } catch (Throwable $e) {
            Log::warn('signup', 'Benachrichtigung an Betreiber fehlgeschlagen: ' . $e->getMessage());
        }
    }
}
