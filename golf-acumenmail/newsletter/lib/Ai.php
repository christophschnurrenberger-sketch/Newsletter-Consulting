<?php
/**
 * Ai – Textvorschläge von einem Sprachmodell.
 *
 * Der Assistent ist eine Zutat, keine Voraussetzung: Ohne hinterlegten
 * Schlüssel bleibt das System wie es ist, es wird nichts nach außen
 * geschickt und in der Oberfläche taucht kein Knopf auf.
 *
 * Unterstützt werden zwei Anbieter über ihre HTTP-Schnittstelle. Beide
 * bekommen dieselbe Anweisung und liefern reinen Text zurück, den die
 * Redaktion prüft und übernimmt oder verwirft.
 */
final class Ai
{
    /** Anbieter zur Auswahl. Leer heißt: Assistent aus. */
    public const PROVIDERS = [
        ''          => 'Aus – keine Textvorschläge',
        'anthropic' => 'Anthropic (Claude)',
        'openai'    => 'OpenAI (ChatGPT)',
    ];

    /** Voreingestelltes Modell je Anbieter – im Feld änderbar. */
    public const DEFAULT_MODELS = [
        'anthropic' => 'claude-sonnet-5',
        'openai'    => 'gpt-4o-mini',
    ];

    /** Was der Assistent tun kann. */
    public const ACTIONS = [
        'schreiben'     => 'Text schreiben',
        'umformulieren' => 'Anders formulieren',
        'kuerzen'       => 'Kürzer fassen',
        'ausbauen'      => 'Ausführlicher',
        'freundlicher'  => 'Persönlicher im Ton',
        'sachlicher'    => 'Sachlicher im Ton',
        'korrigieren'   => 'Rechtschreibung prüfen',
        'betreff'       => 'Betreffzeilen vorschlagen',
    ];

    /**
     * Für Tests: ersetzt den echten Netzzugriff.
     * Bekommt (url, header, daten) und liefert ['status' => int, 'body' => string].
     * Alles Weitere – Auswerten und Fehlermeldungen – läuft wie im Ernstfall.
     *
     * @var callable|null
     */
    public static $transport = null;

    /* --------------------------------------------------------- Einstellungen */

    public static function provider(): string
    {
        $p = Settings::get('ai_provider');
        return isset(self::PROVIDERS[$p]) ? $p : '';
    }

    public static function model(): string
    {
        $eigen = trim(Settings::get('ai_model'));
        return $eigen !== '' ? $eigen : (self::DEFAULT_MODELS[self::provider()] ?? '');
    }

    /** Ist der Assistent nutzbar? */
    public static function available(): bool
    {
        return self::provider() !== '' && trim(Settings::get('ai_key')) !== '';
    }

    /* ------------------------------------------------------------ Anweisung */

    /**
     * Die Anweisung an das Modell – bewusst hier im Code und nicht in der
     * Oberfläche, damit die Vorgaben (Deutsch, kein Marketing-Geschwurbel,
     * Platzhalter unangetastet) immer gelten.
     */
    private static function systemPrompt(): string
    {
        $marke   = Settings::get('brand_name');
        $tonfall = trim(Settings::get('ai_voice'));

        $text = "Du hilfst beim Schreiben von Newslettern für „" . $marke . "\".\n"
              . "Regeln:\n"
              . "- Antworte auf Deutsch und ausschließlich mit dem fertigen Text, ohne Vorrede,\n"
              . "  ohne Anführungszeichen um das Ganze und ohne Erklärung.\n"
              . "- Schreibe schlicht und konkret. Keine Werbefloskeln, keine Ausrufezeichen-Ketten,\n"
              . "  keine leeren Superlative.\n"
              . "- Platzhalter in doppelten geschweiften Klammern wie {{vorname}} oder {{anrede}}\n"
              . "  bleiben unverändert stehen.\n"
              . "- Gib reinen Text oder einfache Absätze zurück, kein Markdown und keine Überschriften\n"
              . "  mit Rautenzeichen.\n";

        if ($tonfall !== '') {
            $text .= "- Tonfall und Besonderheiten: " . $tonfall . "\n";
        }
        return $text;
    }

    /** Baut die Aufgabe aus Aktion und vorhandenem Text. */
    private static function userPrompt(string $aktion, string $text, string $hinweis): string
    {
        $text    = trim($text);
        $hinweis = trim($hinweis);

        $aufgabe = match ($aktion) {
            'umformulieren' => 'Formuliere den folgenden Abschnitt anders, gleiche Länge, gleicher Inhalt.',
            'kuerzen'       => 'Kürze den folgenden Abschnitt deutlich, ohne Wesentliches zu verlieren.',
            'ausbauen'      => 'Führe den folgenden Abschnitt etwas weiter aus, höchstens doppelte Länge.',
            'freundlicher'  => 'Schreibe den folgenden Abschnitt persönlicher und wärmer, ohne anbiedernd zu wirken.',
            'sachlicher'    => 'Schreibe den folgenden Abschnitt nüchterner und sachlicher.',
            'korrigieren'   => 'Korrigiere Rechtschreibung, Zeichensetzung und Grammatik. '
                             . 'Ändere sonst nichts am Inhalt und am Ton.',
            'betreff'       => 'Schlage drei Betreffzeilen für diesen Newsletter vor, je höchstens 60 Zeichen. '
                             . 'Eine Zeile pro Vorschlag, ohne Nummerierung.',
            default         => 'Schreibe einen Abschnitt für einen Newsletter.',
        };

        $teile = [$aufgabe];
        if ($hinweis !== '') {
            $teile[] = "Vorgabe der Redaktion:\n" . $hinweis;
        }
        if ($text !== '') {
            $teile[] = "Vorhandener Text:\n" . $text;
        }
        return implode("\n\n", $teile);
    }

    /* ---------------------------------------------------------------- Anfrage */

    /**
     * Holt einen Vorschlag. Wirft bei Fehlern eine Ausnahme mit einer
     * Meldung, die sich der Redaktion zeigen lässt.
     *
     * @param string $aktion  Schlüssel aus ACTIONS
     * @param string $text    vorhandener Text (darf leer sein)
     * @param string $hinweis freie Vorgabe der Redaktion
     */
    public static function suggest(string $aktion, string $text, string $hinweis = ''): string
    {
        if (!isset(self::ACTIONS[$aktion])) {
            throw new InvalidArgumentException('Unbekannte Aktion.');
        }
        if (!self::available()) {
            throw new RuntimeException('Der Textassistent ist nicht eingerichtet. '
                . 'Unter Einstellungen lässt sich ein Anbieter samt Schlüssel hinterlegen.');
        }
        // Sehr lange Vorlagen kosten unnötig – der Anfang genügt für den Zweck.
        $text    = mb_substr(trim(strip_tags($text)), 0, 6000);
        $hinweis = mb_substr(trim($hinweis), 0, 1000);

        $antwort = self::call(self::systemPrompt(), self::userPrompt($aktion, $text, $hinweis));
        $antwort = trim($antwort);

        if ($antwort === '') {
            throw new RuntimeException('Der Assistent hat nichts zurückgegeben. Bitte erneut versuchen.');
        }
        return $antwort;
    }

    /** Schickt die Anfrage an den eingestellten Anbieter. */
    private static function call(string $system, string $user): string
    {
        $key   = trim(Settings::get('ai_key'));
        $model = self::model();

        if (self::provider() === 'anthropic') {
            $antwort = self::http('https://api.anthropic.com/v1/messages', [
                'x-api-key: ' . $key,
                'anthropic-version: 2023-06-01',
                'content-type: application/json',
            ], [
                'model'      => $model,
                'max_tokens' => 1200,
                'system'     => $system,
                'messages'   => [['role' => 'user', 'content' => $user]],
            ]);
            foreach ((array) ($antwort['content'] ?? []) as $teil) {
                if (($teil['type'] ?? '') === 'text') {
                    return (string) $teil['text'];
                }
            }
            return '';
        }

        $antwort = self::http('https://api.openai.com/v1/chat/completions', [
            'Authorization: Bearer ' . $key,
            'content-type: application/json',
        ], [
            'model'    => $model,
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user',   'content' => $user],
            ],
        ]);
        return (string) ($antwort['choices'][0]['message']['content'] ?? '');
    }

    /**
     * Ein POST mit JSON – über curl, sonst über den Datenstrom.
     *
     * @param string[]            $header
     * @param array<string,mixed> $daten
     * @return array<string,mixed>
     */
    private static function http(string $url, array $header, array $daten): array
    {
        $rumpf = (string) json_encode($daten, JSON_UNESCAPED_UNICODE);
        $roh   = '';
        $code  = 0;

        if (is_callable(self::$transport)) {
            $ersatz = (array) call_user_func(self::$transport, $url, $header, $daten);
            $roh    = (string) ($ersatz['body'] ?? '');
            $code   = (int) ($ersatz['status'] ?? 200);
        } elseif (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $rumpf,
                CURLOPT_HTTPHEADER     => $header,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 45,
                CURLOPT_CONNECTTIMEOUT => 10,
            ]);
            $roh  = (string) curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $netz = curl_error($ch);
            curl_close($ch);
            if ($roh === '' && $netz !== '') {
                throw new RuntimeException('Keine Verbindung zum Anbieter: ' . $netz);
            }
        } else {
            $kontext = stream_context_create(['http' => [
                'method'        => 'POST',
                'header'        => implode("\r\n", $header),
                'content'       => $rumpf,
                'timeout'       => 45,
                'ignore_errors' => true,
            ]]);
            $roh = (string) @file_get_contents($url, false, $kontext);
            foreach ($http_response_header ?? [] as $zeile) {
                if (preg_match('#^HTTP/\S+\s+(\d{3})#', $zeile, $t)) {
                    $code = (int) $t[1];
                }
            }
            if ($roh === '') {
                throw new RuntimeException('Keine Verbindung zum Anbieter. '
                    . 'Erlaubt Ihr Webhosting ausgehende HTTPS-Verbindungen?');
            }
        }

        $antwort = json_decode($roh, true);
        if (!is_array($antwort)) {
            // Bei Fehlern schickt mancher Server (oder ein Proxy davor) eine
            // HTML-Seite statt JSON – dann ist der Status die einzige Auskunft.
            throw new RuntimeException($code >= 400
                ? 'Der Anbieter meldet Fehler ' . $code . '.'
                : 'Unverständliche Antwort des Anbieters.');
        }
        if ($code >= 400) {
            $meldung = (string) ($antwort['error']['message'] ?? ('Fehler ' . $code));
            if ($code === 401 || $code === 403) {
                $meldung = 'Der Schlüssel wurde nicht akzeptiert (' . $meldung . ').';
            } elseif ($code === 429) {
                $meldung = 'Der Anbieter bremst gerade ab. Bitte in einer Minute erneut versuchen.';
            }
            throw new RuntimeException($meldung);
        }
        return $antwort;
    }
}
