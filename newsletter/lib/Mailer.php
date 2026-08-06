<?php
/**
 * Mailer – der eigene Versand. Ohne Bibliotheken, ohne Drittanbieter.
 *
 * Drei Versandwege (Einstellung "transport"):
 *   smtp  – eigener SMTP-Client über Socket (empfohlen: Postfach beim Hoster)
 *   mail  – PHP-Funktion mail() des Servers
 *   file  – Testmodus: legt fertige .eml-Dateien in data/outbox ab
 *
 * Aufbau einer Nachricht (Array):
 *   to, to_name, subject, html, text,
 *   from_email, from_name, reply_to, envelope_from,
 *   headers  => ['List-Unsubscribe' => '<https://…>'],
 *   message_id (optional, wird sonst erzeugt)
 */
final class MailerException extends RuntimeException
{
}

final class Mailer
{
    /** @var resource|null Offene SMTP-Verbindung (Keepalive über mehrere Mails) */
    private static $smtp = null;
    /** @var string[] Vom Server gemeldete SMTP-Erweiterungen */
    private static array $smtpExtensions = [];
    private static string $smtpFingerprint = '';
    private static int $sentOnConnection = 0;

    /* ================================================================ API */

    /**
     * Verschickt eine Nachricht. Wirft bei Misserfolg eine MailerException.
     *
     * @param array<string,mixed> $m
     * @return string Message-ID der versendeten Mail
     */
    public static function send(array $m): string
    {
        $to = Util::normalizeEmail((string) ($m['to'] ?? ''));
        if (!Util::isEmail($to)) {
            throw new MailerException('Ungültige Empfängeradresse: ' . $to);
        }

        $fromEmail = Util::header((string) ($m['from_email'] ?? Settings::get('sender_email')));
        $fromName  = Util::header((string) ($m['from_name'] ?? Settings::get('sender_name')));
        if (!Util::isEmail($fromEmail)) {
            throw new MailerException('Es ist keine gültige Absenderadresse hinterlegt.');
        }
        $envelope = Util::header((string) ($m['envelope_from'] ?? (Settings::get('bounce_email') ?: $fromEmail)));
        if (!Util::isEmail($envelope)) {
            $envelope = $fromEmail;
        }

        $messageId = (string) ($m['message_id'] ?? '') ?: self::newMessageId($fromEmail);
        [$headers, $body] = self::buildMime($m + [
            'to'         => $to,
            'from_email' => $fromEmail,
            'from_name'  => $fromName,
            'message_id' => $messageId,
        ]);

        $transport = Settings::get('transport');
        switch ($transport) {
            case 'smtp':
                self::sendSmtp($envelope, $to, $headers, $body);
                break;
            case 'file':
                self::sendFile($to, $headers, $body, $messageId);
                break;
            case 'mail':
            default:
                self::sendMailFunction($to, $m, $headers, $body, $envelope);
                break;
        }
        return $messageId;
    }

    /** Offene SMTP-Verbindung sauber schließen (am Ende eines Cron-Laufs). */
    public static function close(): void
    {
        if (is_resource(self::$smtp)) {
            try {
                self::command('QUIT', [221, 250]);
            } catch (Throwable $e) {
                // Verbindung wird ohnehin geschlossen
            }
            @fclose(self::$smtp);
        }
        self::$smtp = null;
        self::$smtpExtensions = [];
        self::$sentOnConnection = 0;
    }

    /**
     * Verbindungstest für den Admin-Bereich.
     * @return string Leerer String = erfolgreich, sonst die Fehlermeldung.
     */
    public static function testTransport(): string
    {
        $transport = Settings::get('transport');
        if ($transport === 'file') {
            $dir = NL_ROOT . '/data/outbox';
            if (!is_dir($dir) && !@mkdir($dir, 0750, true)) {
                return 'Testmodus: Verzeichnis data/outbox konnte nicht angelegt werden.';
            }
            return is_writable($dir) ? '' : 'Testmodus: data/outbox ist nicht beschreibbar.';
        }
        if ($transport === 'mail') {
            return function_exists('mail') ? '' : 'Die PHP-Funktion mail() ist auf diesem Server deaktiviert.';
        }
        try {
            self::close();
            self::connectSmtp();
            self::close();
            return '';
        } catch (Throwable $e) {
            self::$smtp = null;
            return $e->getMessage();
        }
    }

    /* ============================================================== MIME */

    /**
     * Baut Header und Body einer Nachricht.
     *
     * @param array<string,mixed> $m
     * @return array{0:array<string,string>,1:string}
     */
    private static function buildMime(array $m): array
    {
        $subject  = Util::header((string) ($m['subject'] ?? ''));
        $html     = (string) ($m['html'] ?? '');
        $text     = (string) ($m['text'] ?? '');
        if ($text === '' && $html !== '') {
            $text = self::htmlToText($html);
        }

        $toName   = Util::header((string) ($m['to_name'] ?? ''));
        $toHeader = $toName === ''
            ? (string) $m['to']
            : self::encodeHeaderWord($toName) . ' <' . $m['to'] . '>';

        $fromName = (string) $m['from_name'];
        $from     = $fromName === ''
            ? (string) $m['from_email']
            : self::encodeHeaderWord($fromName) . ' <' . $m['from_email'] . '>';

        $headers = [
            'Date'         => date('r'),
            'From'         => $from,
            'To'           => $toHeader,
            'Subject'      => self::encodeHeaderWord($subject),
            'Message-ID'   => '<' . $m['message_id'] . '>',
            'MIME-Version' => '1.0',
            'X-Mailer'     => 'AcumenMail Newsletter',
        ];

        $replyTo = Util::header((string) ($m['reply_to'] ?? Settings::get('reply_to')));
        if ($replyTo !== '' && Util::isEmail($replyTo)) {
            $headers['Reply-To'] = $replyTo;
        }

        foreach ((array) ($m['headers'] ?? []) as $name => $value) {
            $name  = Util::header((string) $name);
            $value = Util::header((string) $value);
            if ($name !== '' && $value !== '') {
                $headers[$name] = $value;
            }
        }

        if ($html !== '' && $text !== '') {
            $boundary = '=_acm_' . bin2hex(random_bytes(12));
            $headers['Content-Type'] = 'multipart/alternative; boundary="' . $boundary . '"';
            $body = "Diese Nachricht ist im MIME-Format. Bitte mit einem E-Mail-Programm oeffnen.\r\n\r\n"
                . '--' . $boundary . "\r\n"
                . "Content-Type: text/plain; charset=UTF-8\r\n"
                . "Content-Transfer-Encoding: base64\r\n\r\n"
                . self::base64($text) . "\r\n"
                . '--' . $boundary . "\r\n"
                . "Content-Type: text/html; charset=UTF-8\r\n"
                . "Content-Transfer-Encoding: base64\r\n\r\n"
                . self::base64($html) . "\r\n"
                . '--' . $boundary . "--\r\n";
        } elseif ($html !== '') {
            $headers['Content-Type']              = 'text/html; charset=UTF-8';
            $headers['Content-Transfer-Encoding'] = 'base64';
            $body = self::base64($html);
        } else {
            $headers['Content-Type']              = 'text/plain; charset=UTF-8';
            $headers['Content-Transfer-Encoding'] = 'base64';
            $body = self::base64($text);
        }

        return [$headers, $body];
    }

    private static function base64(string $value): string
    {
        return chunk_split(base64_encode($value), 76, "\r\n");
    }

    /** Betreff/Namen RFC-2047-kodieren, wenn Sonderzeichen enthalten sind. */
    public static function encodeHeaderWord(string $value): string
    {
        $value = Util::header($value);
        if ($value === '') {
            return '';
        }
        if (preg_match('/^[\x20-\x7E]*$/', $value)) {
            // reines ASCII – in Anführungszeichen, falls Sonderzeichen enthalten
            return preg_match('/[",:;<>@\[\]\\\\]/', $value)
                ? '"' . addcslashes($value, '"\\') . '"'
                : $value;
        }
        // Auf 63 Zeichen je Wort begrenzen (RFC 2047)
        $parts = [];
        foreach (str_split($value, 30) as $chunk) {
            $parts[] = '=?UTF-8?B?' . base64_encode($chunk) . '?=';
        }
        return implode("\r\n ", $parts);
    }

    /** @param array<string,string> $headers */
    private static function headerString(array $headers): string
    {
        $lines = [];
        foreach ($headers as $name => $value) {
            $lines[] = $name . ': ' . $value;
        }
        return implode("\r\n", $lines);
    }

    /** Einfache HTML→Text-Umwandlung als Fallback für den Text-Teil. */
    public static function htmlToText(string $html): string
    {
        $text = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', '', $html) ?? $html;
        // Links als "Text (URL)" ausgeben
        $text = preg_replace_callback(
            '#<a\b[^>]*href=["\']([^"\']+)["\'][^>]*>(.*?)</a>#is',
            static function (array $m): string {
                $label = trim(strip_tags($m[2]));
                $url   = html_entity_decode($m[1], ENT_QUOTES, 'UTF-8');
                if ($label === '' || $label === $url || str_starts_with($url, 'mailto:')) {
                    return $url;
                }
                return $label . ' (' . $url . ')';
            },
            $text
        ) ?? $text;
        $text = preg_replace('#<br\s*/?>#i', "\n", $text) ?? $text;
        $text = preg_replace('#</(p|div|tr|h1|h2|h3|h4|li|table)>#i', "\n\n", $text) ?? $text;
        $text = preg_replace('#<li\b[^>]*>#i', '• ', $text) ?? $text;
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/[ \t]+/', ' ', $text) ?? $text;
        $text = preg_replace('/ *\n */', "\n", $text) ?? $text;
        $text = preg_replace('/\n{3,}/', "\n\n", $text) ?? $text;
        return trim($text);
    }

    public static function newMessageId(string $fromEmail): string
    {
        $domain = Util::emailDomain($fromEmail) ?: 'localhost';
        return bin2hex(random_bytes(12)) . '.' . time() . '@' . $domain;
    }

    /* ======================================================== Versandwege */

    /** @param array<string,string> $headers */
    private static function sendMailFunction(string $to, array $m, array $headers, string $body, string $envelope): void
    {
        // mail() setzt To und Subject selbst – daher aus der Header-Liste nehmen
        $subject = $headers['Subject'];
        unset($headers['To'], $headers['Subject']);

        $ok = @mail($to, $subject, $body, self::headerString($headers), '-f' . $envelope);
        if (!$ok) {
            $err = error_get_last();
            throw new MailerException('mail() meldet einen Fehler: ' . ($err['message'] ?? 'unbekannt'));
        }
    }

    /** @param array<string,string> $headers */
    private static function sendFile(string $to, array $headers, string $body, string $messageId): void
    {
        $dir = NL_ROOT . '/data/outbox';
        if (!is_dir($dir) && !@mkdir($dir, 0750, true)) {
            throw new MailerException('Testmodus: data/outbox konnte nicht angelegt werden.');
        }
        $name = date('Ymd-His') . '_' . preg_replace('/[^a-z0-9._@-]/i', '_', $to) . '_'
              . substr(md5($messageId), 0, 8) . '.eml';
        $eml = self::headerString($headers) . "\r\n\r\n" . $body;
        if (file_put_contents($dir . '/' . $name, $eml) === false) {
            throw new MailerException('Testmodus: Datei konnte nicht geschrieben werden.');
        }
    }

    /* =============================================================== SMTP */

    /** @param array<string,string> $headers */
    private static function sendSmtp(string $envelope, string $to, array $headers, string $body): void
    {
        $data = self::headerString($headers) . "\r\n\r\n" . $body;

        $reused = is_resource(self::$smtp) && self::$sentOnConnection > 0;
        self::ensureSmtp();
        $stage = 'start';
        try {
            self::transactionSmtp($envelope, $to, $data, $stage);
            return;
        } catch (MailerException $e) {
            self::close();
            // Nur wiederholen, wenn die Nachricht sicher noch nicht übergeben
            // wurde – sonst droht ein doppelter Versand.
            if (!$reused || in_array($stage, ['data-body', 'sent'], true)) {
                throw $e;
            }
            Log::warn('smtp', 'Verbindung war nicht mehr nutzbar, neuer Versuch: ' . $e->getMessage());
        }
        self::ensureSmtp();
        self::transactionSmtp($envelope, $to, $data, $stage);
    }

    private static function transactionSmtp(string $envelope, string $to, string $data, string &$stage): void
    {
        $from = 'MAIL FROM:<' . $envelope . '>';
        if (isset(self::$smtpExtensions['8BITMIME'])) {
            $from .= ' BODY=8BITMIME';
        }
        $stage = 'mail-from';
        self::command($from, [250]);
        $stage = 'rcpt-to';
        self::command('RCPT TO:<' . $to . '>', [250, 251]);
        $stage = 'data';
        self::command('DATA', [354]);

        // Zeilenenden normalisieren und Punkte am Zeilenanfang verdoppeln
        $data = preg_replace("/\r\n|\r|\n/", "\r\n", $data) ?? $data;
        $data = preg_replace("/^\./m", '..', $data) ?? $data;
        $stage = 'data-body';
        self::write($data . "\r\n.\r\n");
        self::expect([250], 'DATA-Ende');
        $stage = 'sent';
        self::$sentOnConnection++;

        if (!Settings::bool('smtp_keepalive')) {
            self::close();
        }
    }

    private static function ensureSmtp(): void
    {
        $fingerprint = Settings::get('smtp_host') . '|' . Settings::get('smtp_port')
                     . '|' . Settings::get('smtp_security') . '|' . Settings::get('smtp_user');
        if (is_resource(self::$smtp) && self::$smtpFingerprint === $fingerprint && !feof(self::$smtp)) {
            return;
        }
        if (is_resource(self::$smtp)) {
            self::close();
        }
        self::connectSmtp();
        self::$smtpFingerprint = $fingerprint;
    }

    private static function connectSmtp(): void
    {
        $host     = Settings::get('smtp_host');
        $port     = Settings::int('smtp_port', 587);
        $security = Settings::get('smtp_security');
        $timeout  = max(5, Settings::int('smtp_timeout', 20));

        if ($host === '') {
            throw new MailerException('Es ist kein SMTP-Server hinterlegt.');
        }

        $target = ($security === 'ssl' ? 'ssl://' : 'tcp://') . $host . ':' . $port;
        $context = stream_context_create([
            'ssl' => [
                'verify_peer'       => true,
                'verify_peer_name'  => true,
                'allow_self_signed' => false,
                'SNI_enabled'       => true,
                'peer_name'         => $host,
            ],
        ]);

        $errno  = 0;
        $errstr = '';
        $fh = @stream_socket_client($target, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $context);
        if (!$fh) {
            throw new MailerException('Keine Verbindung zu ' . $host . ':' . $port . ' (' . $errstr . ')');
        }
        stream_set_timeout($fh, $timeout);
        self::$smtp = $fh;
        self::$sentOnConnection = 0;

        self::expect([220], 'Begrüßung');
        $ehloHost = self::ehloHostname();
        self::ehlo($ehloHost);

        if ($security === 'tls') {
            if (!isset(self::$smtpExtensions['STARTTLS'])) {
                throw new MailerException('Der Server bietet kein STARTTLS an. Bitte Verschlüsselung auf "keine" oder "SSL" umstellen.');
            }
            self::command('STARTTLS', [220]);
            $crypto = STREAM_CRYPTO_METHOD_TLS_CLIENT;
            if (defined('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT')) {
                $crypto |= STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
            }
            if (defined('STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT')) {
                $crypto |= STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT;
            }
            if (!@stream_socket_enable_crypto($fh, true, $crypto)) {
                throw new MailerException('TLS-Verschlüsselung konnte nicht aktiviert werden.');
            }
            // Nach STARTTLS ist ein erneutes EHLO Pflicht
            self::ehlo($ehloHost);
        }

        $user = Settings::get('smtp_user');
        $pass = Settings::get('smtp_pass');
        if ($user !== '') {
            self::authenticate($user, $pass);
        }
    }

    private static function ehlo(string $hostname): void
    {
        try {
            $response = self::command('EHLO ' . $hostname, [250]);
        } catch (MailerException $e) {
            // Sehr alte Server: auf HELO zurückfallen
            $response = self::command('HELO ' . $hostname, [250]);
        }
        self::$smtpExtensions = [];
        foreach (explode("\n", $response) as $line) {
            $line = trim(substr($line, 4)); // Statuscode abschneiden
            if ($line === '') {
                continue;
            }
            $parts = preg_split('/[ \t]+/', $line) ?: [];
            $name  = strtoupper(array_shift($parts) ?? '');
            self::$smtpExtensions[$name] = array_map('strtoupper', $parts);
        }
    }

    private static function authenticate(string $user, string $pass): void
    {
        $mechanisms = self::$smtpExtensions['AUTH'] ?? [];
        if (in_array('LOGIN', $mechanisms, true) || $mechanisms === []) {
            self::command('AUTH LOGIN', [334]);
            self::command(base64_encode($user), [334]);
            self::command(base64_encode($pass), [235]);
            return;
        }
        if (in_array('PLAIN', $mechanisms, true)) {
            self::command('AUTH PLAIN ' . base64_encode("\0" . $user . "\0" . $pass), [235]);
            return;
        }
        if (in_array('CRAM-MD5', $mechanisms, true)) {
            $challenge = self::command('AUTH CRAM-MD5', [334]);
            $decoded   = base64_decode(trim(substr($challenge, 4)), true) ?: '';
            $digest    = hash_hmac('md5', $decoded, $pass);
            self::command(base64_encode($user . ' ' . $digest), [235]);
            return;
        }
        throw new MailerException('Der Server unterstützt kein bekanntes Anmeldeverfahren (' . implode(', ', $mechanisms) . ').');
    }

    private static function ehloHostname(): string
    {
        $host = (string) ($_SERVER['SERVER_NAME'] ?? '');
        if ($host === '' || !preg_match('/^[A-Za-z0-9.\-]+$/', $host)) {
            $host = Util::emailDomain(Settings::get('sender_email'));
        }
        if ($host === '' || !str_contains($host, '.')) {
            $host = 'localhost';
        }
        return $host;
    }

    /** Befehl senden und Antwortcode prüfen. */
    private static function command(string $command, array $expected): string
    {
        self::write($command . "\r\n");
        return self::expect($expected, $command);
    }

    private static function write(string $data): void
    {
        if (!is_resource(self::$smtp)) {
            throw new MailerException('SMTP-Verbindung ist nicht offen.');
        }
        $written = @fwrite(self::$smtp, $data);
        if ($written === false) {
            throw new MailerException('SMTP: Daten konnten nicht gesendet werden.');
        }
    }

    /** Antwort lesen (mehrzeilig) und Code prüfen. */
    private static function expect(array $codes, string $context = ''): string
    {
        if (!is_resource(self::$smtp)) {
            throw new MailerException('SMTP-Verbindung ist nicht offen.');
        }
        $response = '';
        while (true) {
            $line = @fgets(self::$smtp, 1024);
            if ($line === false) {
                $meta = stream_get_meta_data(self::$smtp);
                throw new MailerException(!empty($meta['timed_out'])
                    ? 'SMTP: Zeitüberschreitung beim Warten auf Antwort.'
                    : 'SMTP: Verbindung wurde unterbrochen.');
            }
            $response .= $line;
            // Letzte Zeile einer Antwort: "250 Text" (Leerzeichen statt Bindestrich)
            if (strlen($line) < 4 || $line[3] !== '-') {
                break;
            }
        }
        $code = (int) substr($response, 0, 3);
        if (!in_array($code, $codes, true)) {
            $ctx = $context !== '' ? ' bei "' . self::maskSecrets($context) . '"' : '';
            throw new MailerException('SMTP-Fehler' . $ctx . ': ' . trim($response));
        }
        return $response;
    }

    /** Zugangsdaten nie in Protokolle oder Fehlermeldungen schreiben. */
    private static function maskSecrets(string $command): string
    {
        if (str_starts_with($command, 'AUTH')) {
            return 'AUTH …';
        }
        // Base64-Zeilen sind Benutzername oder Passwort
        if (preg_match('#^[A-Za-z0-9+/]{8,}={0,2}$#', $command)) {
            return '(Zugangsdaten)';
        }
        return $command;
    }
}
