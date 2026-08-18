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
        $problem = self::passwordProblem($password);
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

    /** @return string Leerer String = Passwort in Ordnung */
    public static function passwordProblem(string $password): string
    {
        if (mb_strlen($password) < 10) {
            return 'Das Passwort muss mindestens 10 Zeichen lang sein.';
        }
        if (!preg_match('/[A-Za-zÄÖÜäöü]/u', $password) || !preg_match('/\d/', $password)) {
            return 'Das Passwort muss Buchstaben und Ziffern enthalten.';
        }
        return '';
    }

    public static function setPassword(int $userId, string $password): void
    {
        $problem = self::passwordProblem($password);
        if ($problem !== '') {
            throw new InvalidArgumentException($problem);
        }
        DB::update('users', ['password_hash' => password_hash($password, PASSWORD_DEFAULT)], 'id = ?', [$userId]);
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

        session_regenerate_id(true);
        $_SESSION['user_id']    = (int) $user['id'];
        $_SESSION['user_email'] = (string) $user['email'];
        $_SESSION['user_name']  = (string) $user['name'];
        $_SESSION['login_at']   = time();
        $_SESSION['last_seen']  = time();

        DB::update('users', ['last_login_at' => Util::now()], 'id = ?', [(int) $user['id']]);
        Util::clearRateLimit('login', $ip);
        Util::clearRateLimit('login_konto', $adresse);
        Log::info('auth', 'Anmeldung: ' . $user['email']);
        return '';
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
        $user = DB::row('SELECT id, email, name, role, status FROM users WHERE id = ?', [$id]);
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
