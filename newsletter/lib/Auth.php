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
        return DB::insert('users', [
            'email'         => $email,
            'name'          => mb_substr(trim($name), 0, 190),
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => $role,
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

        $user = DB::row('SELECT * FROM users WHERE email = ?', [Util::normalizeEmail($email)]);
        if ($user === null || !password_verify($password, (string) $user['password_hash'])) {
            // Gleiche Meldung für falsche Adresse und falsches Passwort
            return 'E-Mail-Adresse oder Passwort ist falsch.';
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
        return DB::row('SELECT id, email, name, role FROM users WHERE id = ?', [$id]);
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    /** Schützt eine Admin-Seite; leitet sonst zur Anmeldung. */
    public static function require(): array
    {
        $user = self::user();
        if ($user === null) {
            $target = (string) ($_SERVER['REQUEST_URI'] ?? '');
            Util::redirect('login.php?weiter=' . rawurlencode($target));
        }
        return $user;
    }

    public static function userCount(): int
    {
        return (int) DB::value('SELECT COUNT(*) FROM users');
    }
}
