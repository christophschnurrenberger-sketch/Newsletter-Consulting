<?php
/**
 * Bounces – Umgang mit unzustellbaren Adressen.
 *
 * Zwei Wege:
 *  1. Direkt beim Versand: lehnt der Mailserver eine Adresse mit 5xx ab,
 *     meldet Queue::handleFailure() das hier als "hard bounce".
 *  2. Rücklaufpostfach: der Cron-Job cron/bounces.php holt die Fehlermails
 *     per POP3 ab, wertet sie aus und sperrt betroffene Adressen.
 *
 * Regel: harte Bounces sperren sofort, weiche Bounces erst nach
 * mehreren Versuchen (Einstellung bounce_hard_limit).
 */
final class Bounces
{
    /** Endgültig unzustellbar → Adresse sperren. */
    public static function registerHard(string $email, ?int $campaignId, string $message, string $code = ''): void
    {
        $email = Util::normalizeEmail($email);
        DB::insert('bounces', [
            'email'       => $email,
            'campaign_id' => $campaignId,
            'bounce_type' => 'hard',
            'code'        => mb_substr($code, 0, 20),
            'message'     => mb_substr($message, 0, 1000),
            'created_at'  => Util::now(),
        ]);
        Subscribers::suppress($email, 'hard_bounce', $code . ' ' . Util::shorten($message, 200));

        $sub = Subscribers::byEmail($email);
        if ($sub !== null) {
            Events::record(Events::BOUNCE, [
                'campaign_id'   => $campaignId,
                'subscriber_id' => (int) $sub['id'],
                'detail'        => 'hard: ' . Util::shorten($message, 300),
                'ip'            => '',
                'user_agent'    => '',
            ]);
        }
    }

    /** Vorübergehend unzustellbar → mitzählen, ab Grenzwert sperren. */
    public static function registerSoft(string $email, ?int $campaignId, string $message, string $code = ''): void
    {
        $email = Util::normalizeEmail($email);
        DB::insert('bounces', [
            'email'       => $email,
            'campaign_id' => $campaignId,
            'bounce_type' => 'soft',
            'code'        => mb_substr($code, 0, 20),
            'message'     => mb_substr($message, 0, 1000),
            'created_at'  => Util::now(),
        ]);

        $sub = Subscribers::byEmail($email);
        if ($sub === null) {
            return;
        }
        $count = (int) $sub['bounce_count'] + 1;
        DB::update('subscribers', ['bounce_count' => $count], 'id = ?', [(int) $sub['id']]);
        Events::record(Events::BOUNCE, [
            'campaign_id'   => $campaignId,
            'subscriber_id' => (int) $sub['id'],
            'detail'        => 'soft (' . $count . '): ' . Util::shorten($message, 300),
            'ip'            => '',
            'user_agent'    => '',
        ]);

        $limit = max(1, Settings::int('bounce_hard_limit', 3));
        if ($count >= $limit) {
            Subscribers::suppress($email, 'soft_bounce', $count . ' Fehlversuche');
            Log::warn('bounce', $email . ' nach ' . $count . ' weichen Bounces gesperrt.');
        }
    }

    /** Beschwerde (Feedback-Loop) → sofort sperren. */
    public static function registerComplaint(string $email, ?int $campaignId = null, string $detail = ''): void
    {
        $email = Util::normalizeEmail($email);
        Subscribers::suppress($email, 'complaint', $detail);
        $sub = Subscribers::byEmail($email);
        Events::record(Events::COMPLAINT, [
            'campaign_id'   => $campaignId,
            'subscriber_id' => $sub !== null ? (int) $sub['id'] : null,
            'detail'        => $detail,
            'ip'            => '',
            'user_agent'    => '',
        ]);
    }

    /* ------------------------------------------------------- Rücklaufpostfach */

    /**
     * Holt Fehlermails aus dem Rücklaufpostfach und wertet sie aus.
     *
     * @param int $max maximale Anzahl Nachrichten je Durchlauf
     * @return array{checked:int,hard:int,soft:int,ignored:int,error:string}
     */
    public static function processMailbox(int $max = 100): array
    {
        $result = ['checked' => 0, 'hard' => 0, 'soft' => 0, 'ignored' => 0, 'error' => ''];
        if (!Settings::bool('bounce_enabled')) {
            $result['error'] = 'Die Bounce-Verarbeitung ist nicht aktiviert.';
            return $result;
        }

        $pop = new Pop3Client(
            Settings::get('bounce_host'),
            Settings::int('bounce_port', 995),
            Settings::bool('bounce_ssl'),
            Settings::int('smtp_timeout', 20)
        );

        try {
            $pop->connect(Settings::get('bounce_user'), Settings::get('bounce_pass'));
            $count  = $pop->count();
            $delete = Settings::bool('bounce_delete');

            for ($i = 1; $i <= min($count, $max); $i++) {
                $raw = $pop->retrieve($i);
                $result['checked']++;

                $parsed = self::parseBounce($raw);
                if ($parsed === null) {
                    $result['ignored']++;
                    continue;
                }
                if ($parsed['type'] === 'hard') {
                    self::registerHard($parsed['email'], $parsed['campaign_id'], $parsed['message'], $parsed['code']);
                    $result['hard']++;
                } else {
                    self::registerSoft($parsed['email'], $parsed['campaign_id'], $parsed['message'], $parsed['code']);
                    $result['soft']++;
                }
                if ($delete) {
                    $pop->delete($i);
                }
            }
            $pop->close();
        } catch (Throwable $e) {
            $result['error'] = $e->getMessage();
            Log::error('bounce', 'Postfach konnte nicht verarbeitet werden: ' . $e->getMessage());
            $pop->close();
        }

        if ($result['hard'] > 0 || $result['soft'] > 0) {
            Log::info('bounce', sprintf('%d Nachrichten geprüft: %d hart, %d weich, %d ignoriert.',
                $result['checked'], $result['hard'], $result['soft'], $result['ignored']));
        }
        return $result;
    }

    /**
     * Wertet eine Fehlermail aus.
     *
     * @return array{email:string,type:string,code:string,message:string,campaign_id:?int}|null
     */
    public static function parseBounce(string $raw): ?array
    {
        // Adresse des Betroffenen suchen
        $email = '';
        foreach ([
            '/^Final-Recipient:\s*[^;]*;\s*(.+)$/mi',
            '/^Original-Recipient:\s*[^;]*;\s*(.+)$/mi',
            '/^X-Failed-Recipients:\s*(.+)$/mi',
        ] as $pattern) {
            if (preg_match($pattern, $raw, $m)) {
                $email = trim(str_replace(['<', '>'], '', $m[1]));
                break;
            }
        }
        if ($email === '' && preg_match('/<([^@\s>]+@[^@\s>]+)>[^\n]{0,40}(?:does not exist|unknown|not found|failed)/i', $raw, $m)) {
            $email = $m[1];
        }
        if (!Util::isEmail($email)) {
            return null;
        }

        // Statuscode bestimmen
        $code = '';
        if (preg_match('/^(?:Diagnostic-Code|Status):\s*(?:smtp;\s*)?([245]\.\d+\.\d+|\d{3})/mi', $raw, $m)) {
            $code = trim($m[1]);
        } elseif (preg_match('/\b([45]\.\d+\.\d+)\b/', $raw, $m)) {
            $code = $m[1];
        } elseif (preg_match('/\b([45]\d\d)\s/', $raw, $m)) {
            $code = $m[1];
        }
        $type = str_starts_with($code, '5') ? 'hard' : 'soft';

        // Kampagne über das mitgesendete Token zuordnen, falls vorhanden
        $campaignId = null;
        if (preg_match('/^X-Newsletter-Token:\s*(\S+)$/mi', $raw, $m)) {
            $row = DB::row('SELECT campaign_id FROM queue WHERE token = ?', [trim($m[1])]);
            if ($row !== null && $row['campaign_id'] !== null) {
                $campaignId = (int) $row['campaign_id'];
            }
        }

        // Aussagekräftige Zeile als Meldung übernehmen
        $message = '';
        if (preg_match('/^Diagnostic-Code:\s*(.+)$/mi', $raw, $m)) {
            $message = trim($m[1]);
        } elseif (preg_match('/^Subject:\s*(.+)$/mi', $raw, $m)) {
            $message = trim($m[1]);
        }

        return [
            'email'       => Util::normalizeEmail($email),
            'type'        => $type,
            'code'        => $code,
            'message'     => Util::shorten($message !== '' ? $message : 'Unzustellbar', 300),
            'campaign_id' => $campaignId,
        ];
    }

    /** @return array<int,array<string,mixed>> */
    public static function recent(int $limit = 100): array
    {
        return DB::all('SELECT * FROM bounces ORDER BY id DESC LIMIT ' . max(1, $limit));
    }
}

/**
 * Pop3Client – minimaler POP3-Client für das Rücklaufpostfach.
 * Bewusst klein gehalten: verbinden, zählen, abholen, löschen.
 */
final class Pop3Client
{
    /** @var resource|null */
    private $fh = null;

    public function __construct(
        private string $host,
        private int $port = 995,
        private bool $ssl = true,
        private int $timeout = 20
    ) {
    }

    public function connect(string $user, string $password): void
    {
        if ($this->host === '') {
            throw new RuntimeException('Es ist kein Postfach-Server hinterlegt.');
        }
        $target  = ($this->ssl ? 'ssl://' : 'tcp://') . $this->host . ':' . $this->port;
        $context = stream_context_create(['ssl' => [
            'verify_peer'      => true,
            'verify_peer_name' => true,
            'peer_name'        => $this->host,
        ]]);

        $errno = 0;
        $errstr = '';
        $fh = @stream_socket_client($target, $errno, $errstr, $this->timeout, STREAM_CLIENT_CONNECT, $context);
        if (!$fh) {
            throw new RuntimeException('Keine Verbindung zum Postfach (' . $errstr . ')');
        }
        stream_set_timeout($fh, $this->timeout);
        $this->fh = $fh;

        $this->expectOk('Begrüßung');
        $this->command('USER ' . $user);
        $this->command('PASS ' . $password, true);
    }

    public function count(): int
    {
        $response = $this->command('STAT');
        $parts = preg_split('/\s+/', trim($response)) ?: [];
        return (int) ($parts[1] ?? 0);
    }

    /** Holt eine Nachricht (Kopf + Anfang des Textes reichen für Bounces). */
    public function retrieve(int $number, int $lines = 200): string
    {
        // TOP liefert Kopfzeilen plus n Zeilen Text – schont Bandbreite
        try {
            $this->command('TOP ' . $number . ' ' . $lines);
        } catch (RuntimeException $e) {
            $this->command('RETR ' . $number);
        }
        return $this->readMultiline();
    }

    public function delete(int $number): void
    {
        $this->command('DELE ' . $number);
    }

    public function close(): void
    {
        if (is_resource($this->fh)) {
            try {
                $this->command('QUIT');
            } catch (Throwable $e) {
                // egal – Verbindung wird geschlossen
            }
            @fclose($this->fh);
        }
        $this->fh = null;
    }

    private function command(string $command, bool $secret = false): string
    {
        if (!is_resource($this->fh)) {
            throw new RuntimeException('POP3-Verbindung ist nicht offen.');
        }
        fwrite($this->fh, $command . "\r\n");
        return $this->expectOk($secret ? 'Anmeldung' : $command);
    }

    private function expectOk(string $context): string
    {
        if (!is_resource($this->fh)) {
            throw new RuntimeException('POP3-Verbindung ist nicht offen.');
        }
        $line = fgets($this->fh, 1024);
        if ($line === false) {
            throw new RuntimeException('POP3: keine Antwort (' . $context . ')');
        }
        if (!str_starts_with($line, '+OK')) {
            throw new RuntimeException('POP3-Fehler bei ' . $context . ': ' . trim($line));
        }
        return $line;
    }

    private function readMultiline(): string
    {
        if (!is_resource($this->fh)) {
            return '';
        }
        $out = '';
        while (($line = fgets($this->fh, 4096)) !== false) {
            if (rtrim($line, "\r\n") === '.') {
                break;
            }
            // Punkt-Verdopplung rückgängig machen
            if (str_starts_with($line, '..')) {
                $line = substr($line, 1);
            }
            $out .= $line;
            if (strlen($out) > 512000) {
                break; // Sicherheitsgrenze
            }
        }
        return $out;
    }
}
