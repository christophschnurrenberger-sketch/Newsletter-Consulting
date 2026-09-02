<?php
/**
 * Auth – Anmeldung für den Admin-Bereich.
 * Passwörter werden mit password_hash() gespeichert, Anmeldeversuche
 * pro IP begrenzt und die Sitzung nach dem Login neu vergeben.
 */
final class Auth
{
    private const MAX_ATTEMPTS = 8;
    private const WINDOW       = 900; // 15 Minuten
    /*
     * Zweite Bremse, diesmal am Konto statt an der Herkunft: Wer ein Passwort
     * von vielen verschiedenen Adressen aus durchprobiert, umgeht die Sperre
     * pro IP mühelos. Die Grenze liegt bewusst hoch genug, dass sich damit
     * niemand mutwillig fremde Zugänge lahmlegen kann – und sie läuft nach
     * einer Viertelstunde von selbst wieder ab.
     */
    private const MAX_PER_ACCOUNT = 25;

    /**
     * Rückgabe von login(), wenn das Passwort stimmte, aber noch die Zahl
     * aus der Authenticator-App fehlt. Kein Fehlertext – ein Zwischenschritt.
     */
    public const ZWEITER_FAKTOR = '@zweiter-faktor';

    /* ------------------------------------------------------------- Rollen */

    /** @return array<string,string> */
    public const ROLES = [
        'admin'      => 'Administrator',
        'redakteur'  => 'Redakteur',
        'betrachter' => 'Betrachter',
    ];

    /** Was die einzelnen Rollen dürfen. */
    private const RIGHTS = [
        'admin'      => ['lesen', 'kampagnen', 'empfaenger', 'einstellungen', 'benutzer'],
        'redakteur'  => ['lesen', 'kampagnen', 'empfaenger'],
        'betrachter' => ['lesen'],
    ];

    /** Klartext für die Oberfläche. */
    public const RIGHT_LABELS = [
        'lesen'         => 'Alles ansehen (Übersicht, Auswertungen, Empfänger)',
        'kampagnen'     => 'Newsletter schreiben, Vorlagen, Automationen und Versand',
        'empfaenger'    => 'Empfänger und Listen pflegen, importieren, löschen',
        'einstellungen' => 'Systemeinstellungen, Versandweg und Protokoll',
        'benutzer'      => 'Zugänge anlegen und ändern',
    ];

    public static function roleLabel(string $role): string
    {
        return self::ROLES[$role] ?? $role;
    }

    /** @return string[] */
    public static function rightsOf(string $role): array
    {
        return self::RIGHTS[$role] ?? self::RIGHTS['betrachter'];
    }

    /** Darf der angemeldete Benutzer das? */
    public static function can(string $right): bool
    {
        $user = self::user();
        if ($user === null) {
            return false;
        }
        return in_array($right, self::rightsOf((string) $user['role']), true);
    }

    public static function isAdmin(): bool
    {
        return self::can('benutzer');
    }

    public static function createUser(string $email, string $password, string $name = '', string $role = 'admin'): int
    {
        $email = Util::normalizeEmail($email);
        if (!Util::isEmail($email)) {
            throw new InvalidArgumentException('Bitte geben Sie eine gültige E-Mail-Adresse an.');
        }
        $problem = self::passwordProblem($password, $email);
        if ($problem !== '') {
            throw new InvalidArgumentException($problem);
        }
        if (DB::value('SELECT COUNT(*) FROM users WHERE email = ?', [$email]) > 0) {
            throw new InvalidArgumentException('Für diese Adresse gibt es bereits ein Konto.');
        }
        if (!isset(self::ROLES[$role])) {
            $role = 'redakteur';
        }
        return DB::insert('users', [
            'email'         => $email,
            'name'          => mb_substr(trim($name), 0, 190),
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => $role,
            'status'        => 'active',
            'created_at'    => Util::now(),
        ]);
    }

    /**
     * @param  string|null $email Adresse des Kontos – dann wird auch geprüft,
     *                            ob das Passwort daraus gebaut ist.
     * @return string Leerer String = Passwort in Ordnung
     */
    public static function passwordProblem(string $password, ?string $email = null): string
    {
        if (mb_strlen($password) < 10) {
            return 'Das Passwort muss mindestens 10 Zeichen lang sein.';
        }
        if (!preg_match('/[A-Za-zÄÖÜäöü]/u', $password) || !preg_match('/\d/', $password)) {
            return 'Das Passwort muss Buchstaben und Ziffern enthalten.';
        }

        /*
         * „Passwort2026“ erfüllt alle Regeln oben – und steht trotzdem in
         * jeder Angreiferliste. Deshalb wird zusätzlich der Kern des
         * Passworts geprüft: ohne angehängte Ziffern und Sonderzeichen.
         */
        $klein = mb_strtolower(trim($password));
        $kern  = preg_replace('/[^a-zäöüß]/u', '', $klein) ?? $klein;
        foreach (self::haeufigePasswoerter() as $bekannt) {
            if ($klein === $bekannt || ($kern !== '' && $kern === $bekannt)) {
                return 'Dieses Passwort steht auf den Listen, die Angreifer zuerst durchprobieren. '
                     . 'Bitte wählen Sie ein anderes – am besten drei zufällige Wörter mit einer Zahl.';
            }
        }

        if ($email !== null && $email !== '') {
            $name = mb_strtolower((string) strstr($email, '@', true));
            if ($name !== '' && mb_strlen($name) >= 5 && str_contains($klein, $name)) {
                return 'Das Passwort darf nicht Ihre E-Mail-Adresse enthalten.';
            }
        }

        return '';
    }

    /**
     * Die mitgelieferte Liste häufiger Passwörter.
     *
     * @return string[]
     */
    private static function haeufigePasswoerter(): array
    {
        if (self::$haeufige !== null) {
            return self::$haeufige;
        }
        self::$haeufige = [];
        $datei = NL_ROOT . '/lib/haeufige-passwoerter.txt';
        if (!is_file($datei)) {
            return self::$haeufige;
        }
        foreach (file($datei, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $zeile) {
            $zeile = trim($zeile);
            if ($zeile !== '' && $zeile[0] !== '#') {
                self::$haeufige[] = mb_strtolower($zeile);
            }
        }
        return self::$haeufige;
    }

    /** @var string[]|null */
    private static ?array $haeufige = null;

    public static function setPassword(int $userId, string $password): void
    {
        $adresse = (string) DB::value('SELECT email FROM users WHERE id = ?', [$userId], '');
        $problem = self::passwordProblem($password, $adresse);
        if ($problem !== '') {
            throw new InvalidArgumentException($problem);
        }
        /*
         * sessions_valid_from setzt den Stichtag neu: Jede Sitzung, die vorher
         * angefangen hat, gilt ab sofort als beendet. Wer sein Passwort
         * wechselt, weil er einen Diebstahl vermutet, erwartet genau das –
         * und wir müssen dafür keine Sitzungsdateien des Servers durchsuchen.
         */
        DB::update('users', [
            'password_hash'       => password_hash($password, PASSWORD_DEFAULT),
            'sessions_valid_from' => Util::now(),
        ], 'id = ?', [$userId]);
        Log::info('auth', 'Passwort geändert für #' . $userId . ' – andere Sitzungen beendet.');
    }

    /**
     * Die eigene Sitzung nach einem Passwortwechsel weiterlaufen lassen.
     *
     * Ohne das würde sich der Wechselnde mit dem Stichtag oben selbst
     * hinauswerfen – die Sitzung ist ja älter als der neue Stichtag.
     */
    public static function eigeneSitzungBehalten(): void
    {
        Util::session();
        $_SESSION['login_at'] = time();
    }

    /**
     * Meldet einen Benutzer an.
     * @return string Leerer String = erfolgreich, sonst die Fehlermeldung
     */
    public static function login(string $email, string $password): string
    {
        Util::session();
        $ip = Util::ip();
        if (!Util::rateLimit('login', $ip, self::MAX_ATTEMPTS, self::WINDOW)) {
            Log::warn('auth', 'Zu viele Anmeldeversuche von ' . Util::anonymizeIp($ip));
            return 'Zu viele Anmeldeversuche. Bitte warten Sie 15 Minuten.';
        }

        $adresse = Util::normalizeEmail($email);
        if (!Util::rateLimit('login_konto', $adresse, self::MAX_PER_ACCOUNT, self::WINDOW)) {
            Log::warn('auth', 'Zu viele Anmeldeversuche für ' . $adresse . ' (von mehreren Adressen).');
            return 'Zu viele Anmeldeversuche für diesen Zugang. Bitte warten Sie 15 Minuten.';
        }

        $user = DB::row('SELECT * FROM users WHERE email = ?', [$adresse]);
        if ($user === null || !password_verify($password, (string) $user['password_hash'])) {
            // Gleiche Meldung für falsche Adresse und falsches Passwort
            return 'E-Mail-Adresse oder Passwort ist falsch.';
        }
        if (($user['status'] ?? 'active') !== 'active') {
            Log::warn('auth', 'Anmeldung eines gesperrten Zugangs: ' . $user['email']);
            return 'Dieser Zugang ist gesperrt. Bitte wenden Sie sich an einen Administrator.';
        }

        if (password_needs_rehash((string) $user['password_hash'], PASSWORD_DEFAULT)) {
            DB::update('users', ['password_hash' => password_hash($password, PASSWORD_DEFAULT)], 'id = ?', [(int) $user['id']]);
        }

        /*
         * Zweiter Faktor eingerichtet? Dann ist hier noch nicht Schluss.
         * Angemeldet wird erst, wenn auch die Zahl aus der App stimmt –
         * bis dahin steht in der Sitzung nur ein Vermerk, wer wartet.
         */
        if (self::hatZweitenFaktor($user)) {
            session_regenerate_id(true);
            $_SESSION['zwei_faktor_id']   = (int) $user['id'];
            $_SESSION['zwei_faktor_seit'] = time();
            return self::ZWEITER_FAKTOR;
        }

        self::anmeldungAbschliessen($user, $ip, $adresse);
        return '';
    }

    /** Ist für diesen Zugang ein zweiter Faktor eingerichtet und bestätigt? */
    public static function hatZweitenFaktor(array $user): bool
    {
        return trim((string) ($user['totp_secret'] ?? '')) !== ''
            && trim((string) ($user['totp_confirmed_at'] ?? '')) !== '';
    }

    /** Wartet gerade jemand auf die Eingabe der Zahl? */
    public static function wartetAufZweitenFaktor(): ?array
    {
        Util::session();
        $id = (int) ($_SESSION['zwei_faktor_id'] ?? 0);
        if ($id <= 0) {
            return null;
        }
        // Nach fünf Minuten fängt man besser von vorn an
        if (time() - (int) ($_SESSION['zwei_faktor_seit'] ?? 0) > 300) {
            unset($_SESSION['zwei_faktor_id'], $_SESSION['zwei_faktor_seit']);
            return null;
        }
        return DB::row('SELECT * FROM users WHERE id = ?', [$id]);
    }

    /**
     * Zweiter Schritt: die Zahl aus der App oder ein Ersatzcode.
     *
     * @return string Leerer String = angemeldet, sonst die Fehlermeldung
     */
    public static function zweiterFaktor(string $eingabe): string
    {
        $user = self::wartetAufZweitenFaktor();
        if ($user === null) {
            return 'Die Anmeldung ist abgelaufen. Bitte fangen Sie noch einmal von vorn an.';
        }

        $ip = Util::ip();
        if (!Util::rateLimit('zweifaktor', $ip . '|' . $user['id'], 10, self::WINDOW)) {
            Log::warn('auth', 'Zu viele Versuche beim zweiten Faktor für ' . $user['email']);
            return 'Zu viele Versuche. Bitte warten Sie 15 Minuten.';
        }

        $eingabe = trim($eingabe);
        $geheim  = Util::decrypt((string) $user['totp_secret']);

        if (Totp::pruefe($geheim, $eingabe)) {
            Util::clearRateLimit('zweifaktor', $ip . '|' . $user['id']);
            self::anmeldungAbschliessen($user, $ip, (string) $user['email']);
            return '';
        }

        // Kein passender Code? Dann vielleicht ein Ersatzcode.
        $hashes = json_decode((string) ($user['totp_recovery'] ?? ''), true);
        if (is_array($hashes) && $hashes !== []) {
            $rest = Totp::ersatzcodeEinloesen($hashes, $eingabe);
            if ($rest !== null) {
                DB::update('users', ['totp_recovery' => json_encode(array_values($rest))],
                    'id = ?', [(int) $user['id']]);
                Log::warn('auth', 'Anmeldung mit Ersatzcode: ' . $user['email']
                    . ' – noch ' . count($rest) . ' übrig.');
                Util::clearRateLimit('zweifaktor', $ip . '|' . $user['id']);
                self::anmeldungAbschliessen($user, $ip, (string) $user['email']);
                return '';
            }
        }

        Log::warn('auth', 'Falscher zweiter Faktor für ' . $user['email']);
        return 'Die Zahl stimmt nicht. Bitte prüfen Sie die Uhrzeit auf Ihrem Telefon und '
             . 'geben Sie die aktuelle Zahl ein.';
    }

    /** @param array<string,mixed> $user */
    private static function anmeldungAbschliessen(array $user, string $ip, string $adresse): void
    {
        session_regenerate_id(true);
        unset($_SESSION['zwei_faktor_id'], $_SESSION['zwei_faktor_seit']);
        $_SESSION['user_id']    = (int) $user['id'];
        $_SESSION['user_email'] = (string) $user['email'];
        $_SESSION['user_name']  = (string) $user['name'];
        $_SESSION['login_at']   = time();
        $_SESSION['last_seen']  = time();

        DB::update('users', ['last_login_at' => Util::now()], 'id = ?', [(int) $user['id']]);
        Util::clearRateLimit('login', $ip);
        Util::clearRateLimit('login_konto', $adresse);
        Log::info('auth', 'Anmeldung: ' . $user['email']);
    }

    /** Prüft das Passwort eines Kontos – ohne Anmeldung und ohne Nebenwirkungen. */
    public static function verifyPassword(int $userId, string $password): bool
    {
        $hash = (string) DB::value('SELECT password_hash FROM users WHERE id = ?', [$userId], '');
        return $hash !== '' && password_verify($password, $hash);
    }

    public static function logout(): void
    {
        Util::session();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'],
                (bool) $params['secure'], (bool) $params['httponly']);
        }
        session_destroy();
    }

    public static function user(): ?array
    {
        Util::session();
        $id = (int) ($_SESSION['user_id'] ?? 0);
        if ($id <= 0) {
            return null;
        }
        // Nach 8 Stunden Inaktivität abmelden
        if (time() - (int) ($_SESSION['last_seen'] ?? 0) > 8 * 3600) {
            self::logout();
            return null;
        }
        $_SESSION['last_seen'] = time();
        $user = DB::row('SELECT id, email, name, role, status, totp_secret, totp_confirmed_at,
                                sessions_valid_from
                         FROM users WHERE id = ?', [$id]);

        /*
         * Stichtag prüfen: Wurde das Passwort geändert, nachdem diese Sitzung
         * begonnen hat, ist sie beendet – auch auf einem fremden Rechner.
         */
        $stichtag = trim((string) ($user['sessions_valid_from'] ?? ''));
        if ($user !== null && $stichtag !== ''
            && (int) ($_SESSION['login_at'] ?? 0) < (int) strtotime($stichtag)) {
            self::logout();
            return null;
        }
        if ($user === null || ($user['status'] ?? 'active') !== 'active') {
            // Zugang wurde zwischenzeitlich gelöscht oder gesperrt
            self::logout();
            return null;
        }
        return $user;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    /**
     * Schützt eine Admin-Seite: leitet ohne Anmeldung zum Login und
     * blockt, wenn die Rolle das angeforderte Recht nicht hat.
     */
    public static function require(string $right = 'lesen'): array
    {
        $user = self::user();
        if ($user === null) {
            $target = (string) ($_SERVER['REQUEST_URI'] ?? '');
            Util::redirect('login.php?weiter=' . rawurlencode($target));
        }
        if (!in_array($right, self::rightsOf((string) $user['role']), true)) {
            self::denied($right);
        }
        return $user;
    }

    /** Zeigt eine verständliche Meldung statt einer nackten Fehlerseite. */
    public static function denied(string $right): void
    {
        http_response_code(403);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html lang="de"><head><meta charset="utf-8"><title>Kein Zugriff</title>'
            . '<link rel="stylesheet" href="'
            . Util::e(Util::asset('assets/admin.css', NL_ROOT . '/admin')) . '"></head><body>'
            . '<div class="ad-login-wrap"><div class="ad-login">'
            . '<h1>Dafür fehlt Ihnen die Berechtigung</h1>'
            . '<p class="ad-sub">Nötig wäre: ' . Util::e(self::RIGHT_LABELS[$right] ?? $right) . '</p>'
            . '<div class="ad-flash ad-flash-info">Bitten Sie eine Administratorin oder einen Administrator '
            . 'um mehr Rechte – unter „Benutzer“ lässt sich das ändern.</div>'
            . '<a class="ad-btn" href="index.php">Zurück zur Übersicht</a>'
            . '</div></div></body></html>';
        exit;
    }

    /* ------------------------------------------------- Zweiter Faktor */

    /**
     * Ein frisches Geheimnis vormerken – bestätigt ist es damit noch nicht.
     * Erst wenn jemand eine gültige Zahl daraus eintippt, wird es scharf
     * geschaltet. Sonst sperrt sich aus, wer die App falsch einrichtet.
     */
    public static function totpVormerken(int $userId, string $geheimnis): void
    {
        DB::update('users', [
            'totp_secret'       => Util::encrypt($geheimnis),
            'totp_confirmed_at' => null,
        ], 'id = ?', [$userId]);
    }

    /** Das vorgemerkte Geheimnis im Klartext – für QR-Code und Prüfung. */
    public static function totpGeheimnis(int $userId): string
    {
        $roh = (string) DB::value('SELECT totp_secret FROM users WHERE id = ?', [$userId], '');
        return $roh === '' ? '' : Util::decrypt($roh);
    }

    /**
     * Scharf schalten, nachdem die erste Zahl gestimmt hat.
     *
     * @param  string[] $ersatzcodes Klartext – hier werden sie gehasht
     */
    public static function totpBestaetigen(int $userId, array $ersatzcodes): void
    {
        $hashes = array_map(static fn(string $c): string => Totp::codeHash($c), $ersatzcodes);
        DB::update('users', [
            'totp_confirmed_at' => Util::now(),
            'totp_recovery'     => json_encode($hashes),
        ], 'id = ?', [$userId]);
        Log::info('auth', 'Zwei-Faktor-Anmeldung eingeschaltet für #' . $userId . '.');
    }

    public static function totpAbschalten(int $userId): void
    {
        DB::update('users', [
            'totp_secret'       => '',
            'totp_confirmed_at' => null,
            'totp_recovery'     => null,
        ], 'id = ?', [$userId]);
        Log::warn('auth', 'Zwei-Faktor-Anmeldung abgeschaltet für #' . $userId . '.');
    }

    /** Wie viele Ersatzcodes sind noch übrig? */
    public static function ersatzcodesUebrig(int $userId): int
    {
        $roh = (string) DB::value('SELECT totp_recovery FROM users WHERE id = ?', [$userId], '');
        $liste = json_decode($roh, true);
        return is_array($liste) ? count($liste) : 0;
    }

    /** Anzahl aktiver Administratoren – schützt vor dem Aussperren. */
    public static function adminCount(): int
    {
        return (int) DB::value("SELECT COUNT(*) FROM users WHERE role = 'admin' AND status = 'active'");
    }

    public static function userCount(): int
    {
        return (int) DB::value('SELECT COUNT(*) FROM users');
    }
}
