<?php
/**
 * Util – kleine Helfer, die überall gebraucht werden:
 * Escaping, Zufalls-Token, Signaturen, IP-Anonymisierung, Rate-Limit,
 * Flash-Nachrichten und CSRF-Schutz.
 */
final class Util
{
    /* -------------------------------------------------------------- Dateien */

    /**
     * Adresse einer Stil- oder Skriptdatei mit Fassungskennung.
     *
     * Ohne diesen Anhang liefert der Browser nach einer neuen Fassung
     * weiter die Datei aus seinem Zwischenspeicher aus. Eine behobene
     * Sache bliebe für die Anwender also so lange kaputt, bis sie von
     * sich aus mit Strg+F5 neu laden – worauf niemand kommt. Die Kennung
     * ist die Änderungszeit der Datei; nach jedem Hochladen ist sie neu.
     *
     * @param string $href   Adresse, wie sie in der Seite steht
     * @param string $wurzel Verzeichnis, auf das sich die Adresse bezieht
     */
    public static function asset(string $href, string $wurzel): string
    {
        $datei = rtrim($wurzel, '/') . '/' . ltrim($href, '/');
        $stand = is_file($datei) ? (string) filemtime($datei)
                                 : (defined('NL_VERSION') ? NL_VERSION : '1');
        return $href . '?v=' . substr(md5($stand), 0, 8);
    }

    /* ---------------------------------------------------------------- Text */

    /** HTML-sicher ausgeben. */
    public static function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /** Entfernt Zeilenumbrüche – Pflicht für alles, was in E-Mail-Header wandert. */
    public static function header(string $value): string
    {
        return trim(str_replace(["\r", "\n", "%0a", "%0d", "\0"], '', $value));
    }

    public static function now(): string
    {
        return date('Y-m-d H:i:s');
    }

    public static function inHours(float $hours): string
    {
        return date('Y-m-d H:i:s', time() + (int) round($hours * 3600));
    }

    /** Datum aus der DB hübsch formatieren. */
    public static function dt(?string $value, string $format = 'd.m.Y H:i'): string
    {
        if ($value === null || $value === '') {
            return '—';
        }
        $ts = strtotime($value);
        return $ts ? date($format, $ts) : $value;
    }

    /** Kürzt Text auf eine Maximallänge (mit Auslassungszeichen). */
    public static function shorten(string $value, int $max = 60): string
    {
        $value = trim(preg_replace('/\s+/u', ' ', $value) ?? '');
        if (mb_strlen($value) <= $max) {
            return $value;
        }
        return mb_substr($value, 0, $max - 1) . '…';
    }

    /* -------------------------------------------------------------- Token */

    /** Kryptografisch sicheres Token (URL-tauglich). */
    public static function token(int $bytes = 16): string
    {
        return rtrim(strtr(base64_encode(random_bytes($bytes)), '+/', '-_'), '=');
    }

    /** Signatur für öffentliche Links (Abmeldung, Web-Ansicht, Cron). */
    public static function sign(string $payload): string
    {
        return substr(hash_hmac('sha256', $payload, Config::secret()), 0, 16);
    }

    public static function checkSign(string $payload, string $signature): bool
    {
        return hash_equals(self::sign($payload), $signature);
    }

    /* --------------------------------------------------------- Verschlüsselung */

    /** Verschlüsselt Zugangsdaten (z. B. SMTP-Passwort) für die Datenbank. */
    public static function encrypt(string $plain): string
    {
        if ($plain === '') {
            return '';
        }
        if (function_exists('openssl_encrypt')) {
            $iv  = random_bytes(12);
            $key = hash('sha256', Config::secret(), true);
            $tag = '';
            $enc = openssl_encrypt($plain, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
            if ($enc !== false) {
                return 'enc1:' . base64_encode($iv . $tag . $enc);
            }
        }
        /*
         * Notnagel ohne openssl: base64 ist KEINE Verschlüsselung, das Passwort
         * liegt dann praktisch im Klartext in der Datenbank. Damit das niemand
         * unbemerkt bleibt, wandert es ins Protokoll; der Systemcheck weist
         * ebenfalls darauf hin.
         */
        try {
            Log::warn('sicherheit', 'openssl fehlt – ein Passwort wurde UNVERSCHLÜSSELT gespeichert. '
                . 'Bitte die PHP-Erweiterung "openssl" aktivieren und das Passwort neu eingeben.');
        } catch (Throwable $e) {
            error_log('[Newsletter] openssl fehlt – Passwort unverschlüsselt gespeichert.');
        }
        return 'raw1:' . base64_encode($plain);
    }

    public static function decrypt(string $value): string
    {
        if ($value === '') {
            return '';
        }
        if (str_starts_with($value, 'raw1:')) {
            return (string) base64_decode(substr($value, 5), true);
        }
        if (str_starts_with($value, 'enc1:') && function_exists('openssl_decrypt')) {
            $raw = base64_decode(substr($value, 5), true);
            if ($raw === false || strlen($raw) < 29) {
                return '';
            }
            $iv  = substr($raw, 0, 12);
            $tag = substr($raw, 12, 16);
            $key = hash('sha256', Config::secret(), true);
            $dec = openssl_decrypt(substr($raw, 28), 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
            return $dec === false ? '' : $dec;
        }
        return $value; // Altbestand im Klartext
    }

    /* ------------------------------------------------------------- E-Mail */

    public static function isEmail(string $email): bool
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL) && mb_strlen($email) <= 190;
    }

    /** Normalisiert eine Adresse (klein, getrimmt) – Grundlage für Duplikat-Prüfung. */
    public static function normalizeEmail(string $email): string
    {
        return mb_strtolower(trim($email));
    }

    public static function emailDomain(string $email): string
    {
        $pos = strrpos($email, '@');
        return $pos === false ? '' : substr($email, $pos + 1);
    }

    /* ----------------------------------------------------------- Request */

    public static function ip(): string
    {
        return (string) ($_SERVER['REMOTE_ADDR'] ?? '');
    }

    /** IP DSGVO-freundlich kürzen (letztes Oktett bzw. Interface-ID entfernen). */
    public static function anonymizeIp(string $ip): string
    {
        if ($ip === '') {
            return '';
        }
        if (str_contains($ip, ':')) {
            $parts = explode(':', $ip);
            $parts = array_slice($parts, 0, 4);
            return implode(':', $parts) . '::';
        }
        return preg_replace('/\.\d+$/', '.0', $ip) ?? $ip;
    }

    /** IP entsprechend der Einstellung speichern (roh oder anonymisiert). */
    public static function storeIp(): string
    {
        $ip = self::ip();
        return Settings::bool('anonymize_ip') ? self::anonymizeIp($ip) : $ip;
    }

    public static function userAgent(int $max = 255): string
    {
        return mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, $max);
    }

    public static function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    public static function post(string $key, string $default = ''): string
    {
        $v = $_POST[$key] ?? $default;
        return is_string($v) ? trim($v) : $default;
    }

    public static function postRaw(string $key, string $default = ''): string
    {
        $v = $_POST[$key] ?? $default;
        return is_string($v) ? $v : $default;
    }

    public static function postInt(string $key, int $default = 0): int
    {
        $v = $_POST[$key] ?? null;
        return is_numeric($v) ? (int) $v : $default;
    }

    public static function get(string $key, string $default = ''): string
    {
        $v = $_GET[$key] ?? $default;
        return is_string($v) ? trim($v) : $default;
    }

    public static function getInt(string $key, int $default = 0): int
    {
        $v = $_GET[$key] ?? null;
        return is_numeric($v) ? (int) $v : $default;
    }

    /**
     * Weiterleitung – auch dann, wenn schon etwas ausgegeben wurde.
     *
     * Normalerweise verwirft der Ausgabepuffer die bisherige Ausgabe und der
     * Header greift. Ist der Puffer abgeschaltet und die Seite schon
     * unterwegs, bleibt als Ausweg eine Weiterleitung im HTML – besser als
     * eine abgebrochene, leere Seite.
     */
    public static function redirect(string $url): void
    {
        while (ob_get_level() > 0 && ob_get_length() !== false) {
            if (!@ob_end_clean()) {
                break;
            }
        }
        if (!headers_sent()) {
            header('Location: ' . $url);
            exit;
        }

        $ziel = self::e($url);
        echo '<!DOCTYPE html><html lang="de"><head><meta charset="utf-8">'
           . '<meta http-equiv="refresh" content="0;url=' . $ziel . '">'
           . '<title>Weiter …</title></head><body>'
           . '<p>Gespeichert. <a href="' . $ziel . '">Hier geht es weiter</a>, '
           . 'falls die Seite nicht von selbst lädt.</p>'
           . '<script>location.replace(' . json_encode($url) . ');</script>'
           . '</body></html>';
        exit;
    }

    /**
     * Antwort als JSON.
     *
     * Vorher wird alles verworfen, was die Seite schon ausgegeben hat: Die
     * Admin-Seiten schreiben ihren Seitenkopf, bevor sie ein Formular
     * verarbeiten. Ohne das Aufräumen käme HTML vor dem JSON an und die
     * Gegenstelle könnte die Antwort nicht lesen.
     */
    public static function json($data, int $status = 200): void
    {
        while (ob_get_level() > 0) {
            if (!@ob_end_clean()) {
                break;
            }
        }
        if (!headers_sent()) {
            http_response_code($status);
            header('Content-Type: application/json; charset=utf-8');
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public static function isCli(): bool
    {
        return PHP_SAPI === 'cli';
    }

    /** Erwartet einen AJAX-/JSON-Aufruf? */
    public static function wantsJson(): bool
    {
        $accept = (string) ($_SERVER['HTTP_ACCEPT'] ?? '');
        $xhr    = (string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');
        return str_contains($accept, 'application/json') || strtolower($xhr) === 'xmlhttprequest';
    }

    /* ------------------------------------------------------------ Session */

    public static function session(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        /*
         * Das Sitzungsplätzchen darf nur über HTTPS mitgehen, sonst könnte
         * jemand im selben Netz die Anmeldung mitlesen. Maßgeblich ist auch
         * die eingetragene Basis-URL: Läuft die Seite laut config.php über
         * https, gilt das Plätzchen auch dann als "nur verschlüsselt", wenn
         * gerade jemand versehentlich über http hereinkommt.
         */
        $https = (($_SERVER['HTTPS'] ?? '') !== '' && ($_SERVER['HTTPS'] ?? '') !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
        if (!$https) {
            try {
                $https = str_starts_with(strtolower(Config::baseUrl()), 'https://');
            } catch (Throwable $e) {
                // Vor der Einrichtung gibt es noch keine Basis-URL.
            }
        }
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'secure'   => $https,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_name('acm_nl_session');
        session_start();
    }

    /* --------------------------------------------------------------- CSRF */

    public static function csrfToken(): string
    {
        self::session();
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = self::token(24);
        }
        return $_SESSION['csrf'];
    }

    public static function csrfField(): string
    {
        return '<input type="hidden" name="_csrf" value="' . self::e(self::csrfToken()) . '">';
    }

    /** Prüft das CSRF-Token; bricht bei Fehlschlag mit 403 ab. */
    public static function requireCsrf(): void
    {
        self::session();
        $sent = (string) ($_POST['_csrf'] ?? $_GET['_csrf'] ?? '');
        if ($sent === '' || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $sent)) {
            http_response_code(403);
            exit('Sicherheitsprüfung fehlgeschlagen (CSRF). Bitte laden Sie die Seite neu.');
        }
    }

    /* -------------------------------------------------------------- Flash */

    public static function flash(string $message, string $type = 'success'): void
    {
        self::session();
        $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
    }

    /** @return array<int,array{type:string,message:string}> */
    public static function takeFlash(): array
    {
        self::session();
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return is_array($flash) ? $flash : [];
    }

    /* --------------------------------------------------------- Rate-Limit */

    /**
     * Einfaches Rate-Limit über die Datenbank.
     * Gibt false zurück, wenn das Limit im Zeitfenster überschritten wurde.
     */
    public static function rateLimit(string $action, string $key, int $max, int $windowSeconds): bool
    {
        $since = date('Y-m-d H:i:s', time() - $windowSeconds);
        DB::run('DELETE FROM rate_limits WHERE created_at < ?', [date('Y-m-d H:i:s', time() - 86400)]);
        $count = (int) DB::value(
            'SELECT COUNT(*) FROM rate_limits WHERE action = ? AND ref = ? AND created_at >= ?',
            [$action, $key, $since]
        );
        if ($count >= $max) {
            return false;
        }
        DB::insert('rate_limits', [
            'action'     => $action,
            'ref'        => $key,
            'created_at' => self::now(),
        ]);
        return true;
    }

    public static function clearRateLimit(string $action, string $key): void
    {
        DB::run('DELETE FROM rate_limits WHERE action = ? AND ref = ?', [$action, $key]);
    }

    /* -------------------------------------------------------------- Sonstiges */

    /** Zahl im deutschen Format. */
    public static function num(int|float $value, int $decimals = 0): string
    {
        return number_format((float) $value, $decimals, ',', '.');
    }

    /** Prozentwert, gegen Division durch Null abgesichert. */
    public static function percent(int|float $part, int|float $total, int $decimals = 1): string
    {
        if ($total <= 0) {
            return '0,0 %';
        }
        return self::num($part / $total * 100, $decimals) . ' %';
    }

    /** CSV-Zeile erzeugen (Semikolon – Excel/DE-freundlich). */
    public static function csvLine(array $fields, string $sep = ';'): string
    {
        $out = [];
        foreach ($fields as $field) {
            $value = (string) $field;
            // Schutz vor CSV-Injection in Tabellenkalkulationen
            if ($value !== '' && str_contains('=+-@', $value[0])) {
                $value = "'" . $value;
            }
            $out[] = '"' . str_replace('"', '""', $value) . '"';
        }
        return implode($sep, $out) . "\r\n";
    }
}
