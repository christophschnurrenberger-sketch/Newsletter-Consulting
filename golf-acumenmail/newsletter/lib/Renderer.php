<?php
/**
 * Renderer – setzt Vorlage + Inhalt zusammen, baut Tracking ein und
 * ersetzt die Platzhalter für den einzelnen Empfänger.
 *
 * Ablauf:
 *   1. wrap()        Inhalt in die Vorlage setzen
 *   2. compile()     Links auf den Klickzähler umschreiben, Zählpixel einsetzen
 *                    (einmal je Kampagne – nicht je Empfänger)
 *   3. personalize() Platzhalter wie {{vorname}} oder {{abmelden_url}} füllen
 *                    (je Empfänger, unmittelbar vor dem Versand)
 */
final class Renderer
{
    /** Steht in der kompilierten Fassung an Stelle des Empfänger-Tokens. */
    public const TOKEN = '%%NLTOKEN%%';

    /* --------------------------------------------------------------- wrap */

    /** Setzt den Inhalt in die Vorlage ein. */
    public static function wrap(?array $template, string $content, string $subject = '', string $preheader = ''): string
    {
        $html = $template['html'] ?? '';
        if (trim((string) $html) === '') {
            // Ohne Vorlage: minimaler, gültiger HTML-Rahmen
            $html = '<!DOCTYPE html><html lang="de"><head><meta charset="utf-8">'
                  . '<meta name="viewport" content="width=device-width,initial-scale=1"><title>{{betreff}}</title></head>'
                  . '<body style="margin:0;padding:24px;background:#ffffff;font-family:Arial,Helvetica,sans-serif;color:#4A5568;">'
                  . '{{inhalt}}<hr style="margin:24px 0;border:none;border-top:1px solid #E0E6ED;">'
                  . '<p style="font-size:12px;color:#8A95A5;">{{impressum}}<br>'
                  . '<a href="{{abmelden_url}}" style="color:#8A95A5;">Abmelden</a></p></body></html>';
        }
        $html = str_replace('{{inhalt}}', $content, $html);
        $html = str_replace(['{{betreff}}', '{{preheader}}'], [Util::e($subject), Util::e($preheader)], $html);
        return $html;
    }

    /**
     * Setzt die Markenangaben einer Vorlage ein – Name, Website, Impressum.
     *
     * Das passiert schon beim Kompilieren und nicht erst beim Versand: So
     * kostet es nichts pro Empfänger, und in der fertigen Mail steht von
     * Anfang an die richtige Marke. Vorlagen ohne eigene Marke bleiben
     * unberührt; für sie gelten wie bisher die Einstellungen.
     *
     * @param bool $html true für HTML (maskiert, Zeilenumbrüche als <br>),
     *                   false für die Textfassung
     */
    public static function applyBrand(string $inhalt, ?array $template, bool $html): string
    {
        if (!Templates::hasOwnBrand($template)) {
            return $inhalt;
        }
        $brand = Templates::brand($template);
        $esc   = static fn(string $v): string => $html ? Util::e($v) : $v;

        return str_replace([
            '{{marke}}',
            '{{website}}',
            '{{website_url}}',
            '{{impressum}}',
            '{{impressum_url}}',
            '{{datenschutz_url}}',
        ], [
            $esc($brand['brand_name']),
            $esc(self::displayHost($brand['website_url'])),
            $esc($brand['website_url']),
            $html ? nl2br(Util::e($brand['imprint'])) : $brand['imprint'],
            $esc($brand['imprint_url']),
            $esc($brand['privacy_url']),
        ], $inhalt);
    }

    /* ------------------------------------------------------------ compile */

    /**
     * Baut Klick-Tracking und Zählpixel ein. Die Empfänger-Kennung bleibt als
     * Platzhalter stehen und wird erst beim Versand ersetzt.
     */
    public static function compile(string $html, ?int $campaignId, ?int $stepId, bool $trackClicks, bool $trackOpens): string
    {
        if ($trackClicks) {
            $html = self::rewriteLinks($html, $campaignId, $stepId);
        }
        if ($trackOpens) {
            $pixel = '<img src="' . Urls::openPixel(self::TOKEN) . '" width="1" height="1" alt="" '
                   . 'style="display:block;width:1px;height:1px;border:0;opacity:0;">';
            $html = str_contains($html, '</body>')
                ? preg_replace('#</body>#i', $pixel . '</body>', $html, 1) ?? $html . $pixel
                : $html . $pixel;
        }
        return $html;
    }

    /** Ersetzt alle externen Links durch Zähl-Links. */
    private static function rewriteLinks(string $html, ?int $campaignId, ?int $stepId): string
    {
        // Nicht mehr verwendete Links entfernen – aber nur solche ohne
        // Klicks, damit bereits erfasste Statistiken erhalten bleiben.
        $unused = 'NOT EXISTS (SELECT 1 FROM events e WHERE e.link_id = links.id)';
        if ($campaignId !== null) {
            DB::delete('links', 'campaign_id = ? AND ' . $unused, [$campaignId]);
        } elseif ($stepId !== null) {
            DB::delete('links', 'step_id = ? AND ' . $unused, [$stepId]);
        }

        return preg_replace_callback(
            '#(<a\b[^>]*?\shref=)(["\'])(.*?)\2#is',
            static function (array $m) use ($campaignId, $stepId): string {
                $url = trim(html_entity_decode($m[3], ENT_QUOTES | ENT_HTML5, 'UTF-8'));

                // Nicht umschreiben: Platzhalter, Anker, mailto/tel, eigene Zähl-Links
                if ($url === ''
                    || str_contains($url, '{{')
                    || str_starts_with($url, '#')
                    || preg_match('#^(mailto:|tel:|sms:)#i', $url)
                    || !preg_match('#^https?://#i', $url)
                    || str_starts_with($url, Config::url('track.php'))
                    || str_starts_with($url, Config::url('abmelden.php'))) {
                    return $m[0];
                }

                $linkId = self::registerLink($url, $campaignId, $stepId);
                $target = Urls::click(self::TOKEN, $linkId);
                return $m[1] . $m[2] . htmlspecialchars($target, ENT_QUOTES, 'UTF-8') . $m[2];
            },
            $html
        ) ?? $html;
    }

    private static function registerLink(string $url, ?int $campaignId, ?int $stepId): int
    {
        $hash = sha1($url);
        $sql  = 'SELECT id FROM links WHERE url_hash = ? AND ';
        $sql .= $campaignId !== null ? 'campaign_id = ?' : 'step_id = ?';
        $id   = (int) DB::value($sql, [$hash, $campaignId ?? $stepId], 0);
        if ($id > 0) {
            return $id;
        }
        return DB::insert('links', [
            'campaign_id' => $campaignId,
            'step_id'     => $stepId,
            'url'         => $url,
            'url_hash'    => $hash,
            'label'       => Util::shorten($url, 120),
            'created_at'  => Util::now(),
        ]);
    }

    /* -------------------------------------------------------- personalize */

    /**
     * Ersetzt alle Platzhalter für einen Empfänger.
     *
     * @param array<string,mixed> $sub Empfängerdatensatz (darf leer sein → Vorschau)
     * @param array<string,string> $extra zusätzliche Platzhalter
     * @param bool $escape true für HTML-Teile, false für Betreff und Textteil
     */
    public static function personalize(string $content, array $sub, array $extra = [], bool $escape = true): string
    {
        $vars = self::variables($sub, $extra, $escape);

        return preg_replace_callback(
            '/\{\{\s*([a-z0-9_:]+)\s*(?:\|\s*([^}]*?)\s*)?\}\}/i',
            static function (array $m) use ($vars): string {
                $key      = strtolower($m[1]);
                $fallback = $m[2] ?? '';
                $value    = $vars[$key] ?? null;
                if ($value === null || $value === '') {
                    return $fallback;
                }
                return (string) $value;
            },
            $content
        ) ?? $content;
    }

    /**
     * Alle verfügbaren Platzhalter für einen Empfänger.
     *
     * @return array<string,string>
     */
    public static function variables(array $sub, array $extra = [], bool $escape = true): array
    {
        $first = trim((string) ($sub['first_name'] ?? ''));
        $last  = trim((string) ($sub['last_name'] ?? ''));
        $name  = trim($first . ' ' . $last);
        $esc   = static fn(string $v): string => $escape ? Util::e($v) : $v;

        $vars = [
            'vorname'         => $esc($first),
            'nachname'        => $esc($last),
            'name'            => $esc($name),
            'anrede'          => $esc(self::salutation($sub)),
            'email'           => $esc((string) ($sub['email'] ?? '')),
            'firma'           => $esc((string) ($sub['company'] ?? '')),
            'marke'           => $esc(Settings::get('brand_name')),
            'website'         => $esc(self::displayHost(Settings::get('website_url'))),
            'website_url'     => $esc(Settings::get('website_url')),
            'impressum'       => $escape ? nl2br(Util::e(Settings::get('imprint'))) : Settings::get('imprint'),
            'impressum_url'   => $esc(Settings::get('imprint_url')),
            'datenschutz_url' => $esc(Settings::get('privacy_url')),
            'kontakt_email'   => $esc(Settings::get('contact_email')),
            'datum'           => date('d.m.Y'),
            'jahr'            => date('Y'),
        ];

        // Eigene Felder als {{feld:schluessel}}
        foreach (Subscribers::custom($sub) as $key => $value) {
            $vars['feld:' . strtolower($key)] = $esc($value);
        }

        foreach ($extra as $key => $value) {
            $vars[strtolower($key)] = $value;
        }
        return $vars;
    }

    /** Passende Anrede aus Anrede-Feld und Namen. */
    public static function salutation(array $sub): string
    {
        $salutation = trim((string) ($sub['salutation'] ?? ''));
        $first      = trim((string) ($sub['first_name'] ?? ''));
        $last       = trim((string) ($sub['last_name'] ?? ''));

        if ($last !== '' && in_array(mb_strtolower($salutation), ['herr', 'frau'], true)) {
            return mb_strtolower($salutation) === 'herr'
                ? 'Sehr geehrter Herr ' . $last
                : 'Sehr geehrte Frau ' . $last;
        }
        if ($first !== '') {
            return 'Hallo ' . $first;
        }
        return 'Hallo';
    }

    /** "https://www.example.de/" → "www.example.de" */
    public static function displayHost(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);
        return is_string($host) && $host !== '' ? $host : rtrim(preg_replace('#^https?://#', '', $url) ?? $url, '/');
    }

    /* ------------------------------------------------------------ Vorschau */

    /** Beispielempfänger für Vorschau und Testversand. */
    public static function sampleSubscriber(string $email = 'vorschau@example.com'): array
    {
        return [
            'id'          => 0,
            'email'       => $email,
            'first_name'  => 'Maria',
            'last_name'   => 'Muster',
            'company'     => 'Musterfirma GmbH',
            'salutation'  => 'Frau',
            'token'       => 'vorschau',
            'custom_json' => '',
            'status'      => Subscribers::STATUS_ACTIVE,
        ];
    }

    /** Liste aller Platzhalter für die Hilfe im Admin-Bereich. */
    public static function placeholderHelp(): array
    {
        return [
            '{{anrede}}'          => 'Sehr geehrte Frau Muster / Hallo Maria / Hallo',
            '{{vorname}}'         => 'Vorname (mit Ersatz: {{vorname|liebe Leserin}})',
            '{{nachname}}'        => 'Nachname',
            '{{name}}'            => 'Vor- und Nachname',
            '{{email}}'           => 'E-Mail-Adresse des Empfängers',
            '{{firma}}'           => 'Unternehmen',
            '{{feld:xyz}}'        => 'eigenes Feld aus dem Empfängerdatensatz',
            '{{abmelden_url}}'    => 'persönlicher Abmeldelink (Pflicht in jedem Newsletter)',
            '{{praeferenzen_url}}' => 'Link zu Daten & Einstellungen des Empfängers',
            '{{webansicht_url}}'  => 'Link zur Browser-Ansicht dieser Ausgabe',
            '{{impressum}}'       => 'Pflichtangaben aus den Einstellungen',
            '{{marke}}'           => 'Markenname',
            '{{datum}}'           => 'aktuelles Datum',
            '{{jahr}}'            => 'aktuelles Jahr',
        ];
    }
}
