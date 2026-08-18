<?php
/**
 * Instanzen.php – mehrere Installationen im Blick behalten.
 *
 * Jeder Kunde bekommt seine eigene Installation: eigener Ordner, eigene
 * Datenbank, eigene Zugänge. Das ist die saubere Trennung – aber es heißt
 * auch, dass man sich sonst überall einzeln anmelden müsste, nur um zu
 * sehen, ob dort etwas hängt.
 *
 * Deshalb führt eine Installation eine Liste der anderen. Abgefragt wird
 * über deren status.php mit dem Schlüssel aus ihrer config.php; zurück
 * kommen nur Zahlen (siehe status.php). Wer den Schlüssel nicht hat,
 * bekommt nichts – und wer ihn hat, bekommt trotzdem keine Adressen.
 */

declare(strict_types=1);

final class Instanzen
{
    /** So lange darf eine Abfrage dauern, bevor sie als „nicht erreichbar" gilt. */
    private const ZEITLIMIT = 8;

    /** Für Tests: eigener Abrufweg statt echter HTTP-Verbindung. */
    public static $transport = null;

    /**
     * Alle eingetragenen Instanzen.
     *
     * @return array<int,array{name:string,url:string,token:string}>
     */
    public static function all(): array
    {
        $roh = json_decode(Settings::get('instanzen_json'), true);
        if (!is_array($roh)) {
            return [];
        }

        $out = [];
        foreach ($roh as $eintrag) {
            if (!is_array($eintrag)) {
                continue;
            }
            $url = self::normalisiere((string) ($eintrag['url'] ?? ''));
            if ($url === '') {
                continue;
            }
            $out[] = [
                'name'  => trim((string) ($eintrag['name'] ?? '')) ?: $url,
                'url'   => $url,
                'token' => trim((string) ($eintrag['token'] ?? '')),
            ];
        }
        return $out;
    }

    /** @param array<int,array<string,string>> $liste */
    public static function save(array $liste): void
    {
        $sauber = [];
        foreach ($liste as $eintrag) {
            $url = self::normalisiere((string) ($eintrag['url'] ?? ''));
            if ($url === '') {
                continue;
            }
            $sauber[] = [
                'name'  => mb_substr(trim((string) ($eintrag['name'] ?? '')), 0, 120),
                'url'   => $url,
                'token' => mb_substr(trim((string) ($eintrag['token'] ?? '')), 0, 190),
            ];
        }
        Settings::set('instanzen_json', (string) json_encode($sauber,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Eine Instanz eintragen. Dieselbe Adresse zweimal gibt es nicht.
     *
     * @throws InvalidArgumentException
     */
    public static function add(string $name, string $url, string $token): void
    {
        $url = self::normalisiere($url);
        if ($url === '') {
            throw new InvalidArgumentException('Bitte geben Sie die Adresse der Installation an, z. B. https://kunde.de/newsletter');
        }
        foreach (self::all() as $vorhanden) {
            if (strcasecmp($vorhanden['url'], $url) === 0) {
                throw new InvalidArgumentException('Diese Installation steht schon in der Liste.');
            }
        }
        $liste   = self::all();
        $liste[] = ['name' => $name, 'url' => $url, 'token' => $token];
        self::save($liste);
    }

    /** Eine Instanz wieder austragen. */
    public static function remove(string $url): void
    {
        $url   = self::normalisiere($url);
        $liste = [];
        foreach (self::all() as $eintrag) {
            if (strcasecmp($eintrag['url'], $url) !== 0) {
                $liste[] = $eintrag;
            }
        }
        self::save($liste);
    }

    /**
     * Der Bericht dieser Installation – ohne Umweg über das Netz.
     *
     * @return array<string,mixed>
     */
    public static function eigene(): array
    {
        $empfaenger = Subscribers::statusCounts();
        $ausgaben   = Campaigns::statusCounts();
        $queue      = Queue::overview();

        $marken = [];
        foreach (Templates::brands() as $marke) {
            if (!$marke['neu']) {
                $marken[] = (string) $marke['name'];
            }
        }

        return [
            'ok'         => true,
            'marke'      => Settings::get('brand_name'),
            'marken'     => $marken,
            'version'    => defined('NL_VERSION') ? NL_VERSION : '',
            'schema'     => (int) Settings::get('schema_version'),
            'empfaenger' => [
                'aktiv'        => (int) ($empfaenger[Subscribers::STATUS_ACTIVE] ?? 0),
                'unbestaetigt' => (int) ($empfaenger[Subscribers::STATUS_PENDING] ?? 0),
                'abgemeldet'   => (int) ($empfaenger[Subscribers::STATUS_UNSUBSCRIBED] ?? 0),
                'gesamt'       => array_sum($empfaenger),
            ],
            'newsletter' => [
                'entwurf'   => (int) ($ausgaben[Campaigns::DRAFT] ?? 0),
                'geplant'   => (int) ($ausgaben[Campaigns::SCHEDULED] ?? 0),
                'versendet' => (int) ($ausgaben[Campaigns::SENT] ?? 0),
                'gesamt'    => (int) ($ausgaben[''] ?? 0),
            ],
            'versand'    => [
                'offen'        => (int) $queue['pending'],
                'heute'        => (int) $queue['sent_today'],
                'fehler'       => (int) $queue['failed'],
                'letzter_cron' => (string) $queue['last_cron_at'],
            ],
            'listen'       => count(Lists::all()),
            'automationen' => count(Automations::all()),
            'zeit'         => Util::now(),
        ];
    }

    /**
     * Bericht einer fremden Instanz holen.
     *
     * Fehler werden nicht geworfen, sondern zurückgegeben: Eine Übersicht,
     * die wegen einer schlafenden Installation ganz ausfällt, hilft niemandem.
     *
     * @param array{name:string,url:string,token:string} $instanz
     * @return array<string,mixed>
     */
    public static function status(array $instanz): array
    {
        $url = $instanz['url'] . '/status.php?token=' . urlencode($instanz['token']);

        try {
            [$code, $roh] = self::hole($url);
        } catch (Throwable $e) {
            return ['ok' => false, 'fehler' => 'Nicht erreichbar: ' . $e->getMessage()];
        }

        if ($code === 403) {
            return ['ok' => false, 'fehler' => 'Der Schlüssel stimmt nicht (cron_token der anderen Installation).'];
        }
        if ($code === 404) {
            return ['ok' => false, 'fehler' => 'Dort liegt keine status.php – ist die Adresse richtig und die Fassung aktuell?'];
        }

        $daten = json_decode($roh, true);
        if (!is_array($daten)) {
            return ['ok' => false, 'fehler' => 'Unverständliche Antwort (Status ' . $code . ').'];
        }
        if (($daten['ok'] ?? false) !== true) {
            return ['ok' => false, 'fehler' => (string) ($daten['fehler'] ?? 'Unbekannter Fehler.')];
        }
        return $daten;
    }

    /**
     * Adresse zurechtrücken: ohne Schrägstrich am Ende, ohne status.php,
     * und mit https://, wenn nichts anderes dasteht.
     */
    public static function normalisiere(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return '';
        }
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . $url;
        }
        $url = preg_replace('#/status\.php.*$#i', '', $url) ?? $url;
        $url = rtrim($url, '/');
        return filter_var($url, FILTER_VALIDATE_URL) === false ? '' : $url;
    }

    /**
     * Eine Adresse abrufen – mit cURL, sonst über den Stream.
     *
     * @return array{0:int,1:string}
     */
    private static function hole(string $url): array
    {
        if (is_callable(self::$transport)) {
            $ersatz = (array) call_user_func(self::$transport, $url);
            return [(int) ($ersatz['status'] ?? 0), (string) ($ersatz['body'] ?? '')];
        }

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => self::ZEITLIMIT,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_FOLLOWLOCATION => false,
            ]);
            $roh  = (string) curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $netz = curl_error($ch);
            curl_close($ch);
            if ($code === 0) {
                throw new RuntimeException($netz !== '' ? $netz : 'keine Antwort');
            }
            return [$code, $roh];
        }

        $kontext = stream_context_create(['http' => [
            'method'        => 'GET',
            'timeout'       => self::ZEITLIMIT,
            'ignore_errors' => true,
        ]]);
        $roh  = (string) @file_get_contents($url, false, $kontext);
        $code = 0;
        foreach ($http_response_header ?? [] as $zeile) {
            if (preg_match('#^HTTP/\S+\s+(\d{3})#', $zeile, $t)) {
                $code = (int) $t[1];
            }
        }
        if ($code === 0) {
            throw new RuntimeException('keine Antwort – erlaubt das Hosting ausgehende Verbindungen?');
        }
        return [$code, $roh];
    }
}
