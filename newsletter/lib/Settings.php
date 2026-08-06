<?php
/**
 * Settings – alle im Admin-Bereich pflegbaren Einstellungen.
 * Werden einmal pro Request aus der Tabelle "settings" geladen.
 */
final class Settings
{
    /** @var array<string,string>|null */
    private static ?array $cache = null;

    /** Standardwerte – gelten, solange nichts anderes gespeichert ist. */
    public const DEFAULTS = [
        /* --- Absender & Marke ---------------------------------------- */
        'brand_name'        => 'AcumenMail',
        'sender_name'       => 'AcumenMail',
        'sender_email'      => '',
        'reply_to'          => '',
        'bounce_email'      => '',           // Envelope-Absender / Rücklaufadresse
        'website_url'       => 'https://www.newsletter-consulting.de/',
        'contact_email'     => '',
        'imprint'           => '',           // Pflichtangaben im Mail-Footer
        'privacy_url'       => 'https://www.newsletter-consulting.de/Datenschutz.html',
        'imprint_url'       => 'https://www.newsletter-consulting.de/Impressum.html',

        /* --- Versandweg ---------------------------------------------- */
        'transport'         => 'mail',       // smtp | mail | file (Testmodus)
        'smtp_host'         => '',
        'smtp_port'         => '587',
        'smtp_security'     => 'tls',        // none | tls (STARTTLS) | ssl
        'smtp_user'         => '',
        'smtp_pass'         => '',           // verschlüsselt gespeichert
        'smtp_timeout'      => '20',
        'smtp_keepalive'    => '1',          // Verbindung über mehrere Mails halten

        /* --- Versandtempo (Limits des Hosters beachten!) -------------- */
        'batch_size'        => '50',         // Mails pro Cron-Lauf
        'send_delay_ms'     => '400',        // Pause zwischen zwei Mails
        'hourly_limit'      => '500',        // maximal pro Stunde
        'max_attempts'      => '3',          // Zustellversuche je Mail
        'max_runtime'       => '50',         // Sekunden je Cron-Lauf

        /* --- Tracking & Archiv --------------------------------------- */
        'track_opens'       => '1',
        'track_clicks'      => '1',
        'archive_enabled'   => '1',
        'anonymize_ip'      => '1',

        /* --- Double-Opt-in ------------------------------------------- */
        'doi_subject'       => 'Bitte bestätigen Sie Ihre Newsletter-Anmeldung',
        'doi_intro'         => 'vielen Dank für Ihr Interesse an unserem Newsletter. Bitte bestätigen Sie Ihre Anmeldung mit einem Klick – das ist gesetzlich vorgeschrieben und schützt Sie vor fremden Anmeldungen.',
        'doi_expire_days'   => '14',         // unbestätigte Anmeldungen verfallen

        /* --- Begrüßung & Abmeldung ----------------------------------- */
        'welcome_enabled'   => '1',
        'welcome_subject'   => 'Willkommen beim Newsletter',
        'welcome_intro'     => 'schön, dass Sie dabei sind! Ihre Anmeldung ist bestätigt. Ab sofort erhalten Sie unsere Impulse zu E-Mail-Marketing, Automatisierung und Zustellbarkeit.',
        'goodbye_enabled'   => '1',
        'goodbye_subject'   => 'Sie haben sich abgemeldet',

        /* --- Benachrichtigungen an den Betreiber --------------------- */
        'notify_email'      => '',           // Info bei neuer Anmeldung
        'notify_on_signup'  => '0',

        /* --- Bounce-Verarbeitung (POP3) ------------------------------ */
        'bounce_enabled'    => '0',
        'bounce_host'       => '',
        'bounce_port'       => '995',
        'bounce_ssl'        => '1',
        'bounce_user'       => '',
        'bounce_pass'       => '',           // verschlüsselt gespeichert
        'bounce_delete'     => '1',          // verarbeitete Mails löschen
        'bounce_hard_limit' => '3',          // Soft-Bounces bis zur Sperre

        /* --- Sonstiges ------------------------------------------------ */
        'schema_version'    => '0',
        'installed_at'      => '',
        'last_cron_at'      => '',
    ];

    /** Einstellungen, die verschlüsselt in der Datenbank liegen. */
    private const SECRETS = ['smtp_pass', 'bounce_pass'];

    /** @return array<string,string> */
    public static function all(bool $fresh = false): array
    {
        if (self::$cache === null || $fresh) {
            $stored = [];
            try {
                $stored = DB::pairs('SELECT skey, svalue FROM settings');
            } catch (Throwable $e) {
                $stored = [];
            }
            self::$cache = array_merge(self::DEFAULTS, $stored);
        }
        return self::$cache;
    }

    public static function get(string $key, ?string $default = null): string
    {
        $all   = self::all();
        $value = $all[$key] ?? $default ?? (self::DEFAULTS[$key] ?? '');
        if (in_array($key, self::SECRETS, true) && $value !== '') {
            return Util::decrypt((string) $value);
        }
        return (string) $value;
    }

    public static function int(string $key, int $default = 0): int
    {
        $value = self::get($key);
        return $value === '' ? $default : (int) $value;
    }

    public static function bool(string $key): bool
    {
        $value = self::get($key);
        return $value === '1' || $value === 'true' || $value === 'on';
    }

    public static function set(string $key, string $value): void
    {
        if (in_array($key, self::SECRETS, true) && $value !== '') {
            $value = Util::encrypt($value);
        }
        $exists = DB::value('SELECT COUNT(*) FROM settings WHERE skey = ?', [$key]);
        if ((int) $exists > 0) {
            DB::update('settings', ['svalue' => $value], 'skey = ?', [$key]);
        } else {
            DB::insert('settings', ['skey' => $key, 'svalue' => $value]);
        }
        self::$cache = null;
    }

    /** @param array<string,string> $values */
    public static function setMany(array $values): void
    {
        DB::transaction(static function () use ($values) {
            foreach ($values as $key => $value) {
                self::set($key, (string) $value);
            }
        });
    }

    /** Ist ein Geheimnis hinterlegt? (ohne es preiszugeben) */
    public static function hasSecret(string $key): bool
    {
        return self::get($key) !== '';
    }

    /** Absender als "Name <adresse>". */
    public static function fromHeader(): string
    {
        $name  = Util::header(self::get('sender_name'));
        $email = Util::header(self::get('sender_email'));
        return $name === '' ? $email : Mailer::encodeHeaderWord($name) . ' <' . $email . '>';
    }

    /**
     * Prüft, ob das System versandbereit ist.
     *
     * @return string[] Liste der Probleme (leer = alles in Ordnung)
     */
    public static function readiness(): array
    {
        $problems = [];
        if (self::get('sender_email') === '' || !Util::isEmail(self::get('sender_email'))) {
            $problems[] = 'Es ist keine gültige Absenderadresse hinterlegt (Einstellungen → Absender).';
        }
        if (self::get('imprint') === '') {
            $problems[] = 'Die Impressumsangaben für den Mail-Footer fehlen (gesetzliche Pflichtangabe).';
        }
        if (self::get('transport') === 'smtp' && self::get('smtp_host') === '') {
            $problems[] = 'Als Versandweg ist SMTP eingestellt, aber kein SMTP-Server hinterlegt.';
        }
        if (Config::baseUrl() === '') {
            $problems[] = 'In der config.php fehlt die Basis-URL – Bestätigungs- und Abmeldelinks funktionieren nicht.';
        }
        if (self::get('transport') === 'file') {
            $problems[] = 'Der Versandweg steht auf "Testmodus" – Mails werden nur in data/outbox gespeichert, nicht versendet.';
        }
        return $problems;
    }
}
