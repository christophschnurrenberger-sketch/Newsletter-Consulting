<?php
/**
 * Templates – HTML-Rahmen für Newsletter.
 *
 * Eine Vorlage enthält den Platzhalter {{inhalt}}; dort wird der Text der
 * Kampagne eingesetzt. Der Aufbau ist bewusst tabellenbasiert mit Inline-CSS,
 * weil Outlook & Co. moderne CSS-Layouts nicht zuverlässig darstellen.
 */
final class Templates
{
    /** @return array<int,array<string,mixed>> */
    public static function all(): array
    {
        return DB::all('SELECT * FROM templates ORDER BY is_default DESC, name');
    }

    public static function byId(?int $id): ?array
    {
        if ($id === null || $id <= 0) {
            return null;
        }
        return DB::row('SELECT * FROM templates WHERE id = ?', [$id]);
    }

    public static function defaultTemplate(): ?array
    {
        $row = DB::row('SELECT * FROM templates WHERE is_default = 1 ORDER BY id LIMIT 1');
        return $row ?? DB::row('SELECT * FROM templates ORDER BY id LIMIT 1');
    }

    public static function defaultId(): int
    {
        $tpl = self::defaultTemplate();
        return $tpl === null ? 0 : (int) $tpl['id'];
    }

    /**
     * @param string|null $blocksJson Bausteine aus dem Baukasten; ist der Wert
     *        gesetzt, entsteht das HTML daraus (statt aus $html).
     */
    public static function create(string $name, string $html, string $description = '',
                                  bool $isDefault = false, ?string $blocksJson = null): int
    {
        $mode = 'html';
        if ($blocksJson !== null) {
            $blocks     = Blocks::parse($blocksJson);
            $blocksJson = (string) json_encode($blocks, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $html       = Blocks::renderDocument($blocks);
            $mode       = 'blocks';
        }
        $id = DB::insert('templates', [
            'name'        => mb_substr(trim($name), 0, 190),
            'description' => mb_substr(trim($description), 0, 255),
            'html'        => $html,
            'blocks_json' => $blocksJson,
            'editor_mode' => $mode,
            'is_default'  => $isDefault ? 1 : 0,
            'created_at'  => Util::now(),
            'updated_at'  => Util::now(),
        ]);
        if ($isDefault) {
            self::makeDefault($id);
        }
        return $id;
    }

    public static function update(int $id, string $name, string $html, string $description = '',
                                  ?string $blocksJson = null): void
    {
        $data = [
            'name'        => mb_substr(trim($name), 0, 190),
            'description' => mb_substr(trim($description), 0, 255),
            'html'        => $html,
            'updated_at'  => Util::now(),
        ];
        if ($blocksJson !== null) {
            $blocks              = Blocks::parse($blocksJson);
            $data['blocks_json'] = (string) json_encode($blocks, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $data['html']        = Blocks::renderDocument($blocks);
            $data['editor_mode'] = 'blocks';
        }
        DB::update('templates', $data, 'id = ?', [$id]);
        Automations::recompileForTemplate($id);
    }

    /* --------------------------------------------------------------- Marke */

    /**
     * Felder, mit denen eine Vorlage eine eigene Marke bekommt.
     * Leer bedeutet immer: Angabe aus den Einstellungen verwenden.
     *
     * So lassen sich mehrere Websites aus einer Installation bedienen –
     * jede mit eigenem Namen, eigener Website und eigenem Impressum.
     */
    public const BRAND_FIELDS = [
        'brand_name'  => 'brand_name',
        'website_url' => 'website_url',
        'imprint'     => 'imprint',
        'imprint_url' => 'imprint_url',
        'privacy_url' => 'privacy_url',
        'sender_name' => 'sender_name',
        'sender_email' => 'sender_email',
    ];

    /**
     * Die gültigen Markenangaben für eine Vorlage: eigener Wert, sonst
     * der Wert aus den Einstellungen.
     *
     * @return array<string,string>
     */
    public static function brand(?array $template): array
    {
        $brand = [];
        foreach (self::BRAND_FIELDS as $spalte => $einstellung) {
            $eigen = trim((string) ($template[$spalte] ?? ''));
            $brand[$spalte] = $eigen !== '' ? $eigen : Settings::get($einstellung);
        }
        return $brand;
    }

    /** Hat diese Vorlage eine eigene Marke hinterlegt? */
    public static function hasOwnBrand(?array $template): bool
    {
        foreach (array_keys(self::BRAND_FIELDS) as $spalte) {
            if (trim((string) ($template[$spalte] ?? '')) !== '') {
                return true;
            }
        }
        return false;
    }

    /** Speichert die Markenangaben einer Vorlage (leere Felder = Einstellungen). */
    public static function saveBrand(int $id, array $werte): void
    {
        $data = ['updated_at' => Util::now()];
        foreach (array_keys(self::BRAND_FIELDS) as $spalte) {
            $wert = trim((string) ($werte[$spalte] ?? ''));
            $data[$spalte] = $spalte === 'imprint' ? mb_substr($wert, 0, 2000) : mb_substr($wert, 0, 190);
        }
        DB::update('templates', $data, 'id = ?', [$id]);
        // Automations-Mails halten eine fertige Fassung – die muss mit.
        Automations::recompileForTemplate($id);
    }

    /** Bausteine einer Vorlage (leer = noch nie im Baukasten bearbeitet). */
    public static function blocks(?array $template): array
    {
        $json = (string) ($template['blocks_json'] ?? '');
        if (trim($json) === '') {
            return Blocks::starterTemplate();
        }
        return Blocks::parse($json);
    }

    public static function usesBuilder(?array $template): bool
    {
        return ($template['editor_mode'] ?? 'html') === 'blocks';
    }

    public static function makeDefault(int $id): void
    {
        DB::run('UPDATE templates SET is_default = 0');
        DB::update('templates', ['is_default' => 1], 'id = ?', [$id]);
    }

    public static function delete(int $id): void
    {
        DB::run('UPDATE campaigns SET template_id = NULL WHERE template_id = ?', [$id]);
        DB::run('UPDATE automation_steps SET template_id = NULL WHERE template_id = ?', [$id]);
        DB::delete('templates', 'id = ?', [$id]);
    }

    /* -------------------------------------------------- Fertige Vorlagen */

    /**
     * Fertige Vorlagen aus dem Ordner newsletter/vorlagen/.
     *
     * Jede Datei beginnt mit einem Kommentarkopf:
     *   Vorlage:      Name in der Auswahl
     *   Beschreibung: kurze Erläuterung
     *   Marke:        Vorschlag für den Markennamen
     *   Website:      Vorschlag für die Website
     *
     * @return array<string,array{name:string,description:string,brand:string,website:string}>
     */
    public static function files(): array
    {
        $out = [];

        // HTML-Vorlagen: Angaben stehen im Kommentarkopf
        foreach (glob(NL_ROOT . '/vorlagen/*.html') ?: [] as $pfad) {
            $kopf = (string) file_get_contents($pfad, false, null, 0, 800);
            $lies = static function (string $feld) use ($kopf): string {
                return preg_match('/^\s*' . $feld . ':\s*(.+)$/mu', $kopf, $t) ? trim($t[1]) : '';
            };
            $schluessel = basename($pfad, '.html');
            $out[$schluessel] = [
                'name'        => $lies('Vorlage') ?: $schluessel,
                'description' => $lies('Beschreibung'),
                'brand'       => $lies('Marke'),
                'website'     => $lies('Website'),
                'baukasten'   => false,
            ];
        }

        // Baukasten-Vorlagen: Angaben stehen in der JSON-Datei selbst
        foreach (glob(NL_ROOT . '/vorlagen/*.json') ?: [] as $pfad) {
            $daten = json_decode((string) file_get_contents($pfad), true);
            if (!is_array($daten) || !is_array($daten['blocks'] ?? null)) {
                continue;
            }
            $schluessel = basename($pfad, '.json');
            $out[$schluessel] = [
                'name'        => (string) ($daten['vorlage'] ?? $schluessel),
                'description' => (string) ($daten['beschreibung'] ?? ''),
                'brand'       => (string) ($daten['marke'] ?? ''),
                'website'     => (string) ($daten['website'] ?? ''),
                'baukasten'   => true,
            ];
        }

        ksort($out);
        return $out;
    }

    /**
     * Eine mitgelieferte Datei als Vorlage – ohne sie anzulegen.
     *
     * Damit lässt sich eine Marke ansehen, bevor man sie zum ersten Mal
     * benutzt: Das Ergebnis hat dieselbe Gestalt wie eine Zeile aus der
     * Datenbank und passt deshalb in jede Vorschau.
     *
     * @return array<string,mixed>|null null, wenn es die Datei nicht gibt
     */
    public static function fromFile(string $schluessel): ?array
    {
        $schluessel = preg_replace('/[^a-z0-9_-]/i', '', $schluessel) ?? '';
        $angaben    = self::files()[$schluessel] ?? null;
        if ($angaben === null) {
            return null;
        }
        $pfad = NL_ROOT . '/vorlagen/' . $schluessel . ($angaben['baukasten'] ? '.json' : '.html');
        if (!is_file($pfad)) {
            return null;
        }

        $json = null;
        if ($angaben['baukasten']) {
            $daten  = json_decode((string) file_get_contents($pfad), true);
            $blocks = Blocks::parse((string) json_encode($daten['blocks'] ?? [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $json   = (string) json_encode($blocks, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $html   = Blocks::renderDocument($blocks);
        } else {
            $html = (string) file_get_contents($pfad);
        }

        return [
            'id'          => 0,
            'name'        => $angaben['name'],
            'description' => $angaben['description'],
            'html'        => $html,
            'blocks_json' => $json,
            'editor_mode' => $angaben['baukasten'] ? 'blocks' : 'html',
            'is_default'  => 0,
            'brand_name'  => $angaben['brand'],
            'website_url' => $angaben['website'],
        ];
    }

    /**
     * Legt eine Vorlage aus einer mitgelieferten Datei an – samt Marke,
     * damit Kopfzeile und Footer sofort stimmen.
     *
     * @return int Kennung der neuen Vorlage, 0 wenn die Datei fehlt
     */
    public static function createFromFile(string $schluessel): int
    {
        $roh = self::fromFile($schluessel);
        if ($roh === null) {
            return 0;
        }

        $id = $roh['blocks_json'] !== null
            ? self::create($roh['name'], '', $roh['description'], false, (string) $roh['blocks_json'])
            : self::create($roh['name'], (string) $roh['html'], $roh['description']);

        if ($roh['brand_name'] !== '' || $roh['website_url'] !== '') {
            self::saveBrand($id, [
                'brand_name'  => $roh['brand_name'],
                'website_url' => $roh['website_url'],
            ]);
        }
        return $id;
    }

    /* --------------------------------------------------------- Markenwahl */

    /**
     * Die Marken, unter denen ein Newsletter erscheinen kann.
     *
     * Für die Empfänger ist die Marke das Sichtbare: Kopfzeile, Footer,
     * Name, Website, Impressum. Technisch steckt sie in einer Vorlage –
     * beim Anlegen soll aber die Marke die Wahl sein, nicht die Vorlage.
     * Deshalb werden die Vorlagen hier nach ihrer Marke gruppiert.
     *
     * Mitgelieferte Marken, die es hier noch nicht gibt, stehen ebenfalls
     * in der Liste; ihre Vorlage entsteht beim ersten Benutzen.
     *
     * @return array<int,array{schluessel:string,name:string,template:?array,
     *                         datei:string,neu:bool,vorlagen:array<int,array<string,mixed>>}>
     */
    public static function brands(): array
    {
        $standard = trim(Settings::get('brand_name'));
        $gruppen  = [];

        foreach (self::all() as $vorlage) {
            $name = trim((string) self::brand($vorlage)['brand_name']);
            if ($name === '') {
                $name = $standard !== '' ? $standard : 'Ohne Markenname';
            }
            $key = mb_strtolower($name);

            if (!isset($gruppen[$key])) {
                $gruppen[$key] = ['schluessel' => 'vorlage:' . (int) $vorlage['id'], 'name' => $name,
                                  'template' => $vorlage, 'datei' => '', 'neu' => false, 'vorlagen' => []];
            }
            // Die Standardvorlage vertritt ihre Marke – sie bestimmt Kopf und Fuß.
            if ((int) $vorlage['is_default'] === 1) {
                $gruppen[$key]['schluessel'] = 'vorlage:' . (int) $vorlage['id'];
                $gruppen[$key]['template']   = $vorlage;
            }
            $gruppen[$key]['vorlagen'][] = $vorlage;
        }

        // Die Marke aus den Einstellungen gibt es immer – auch ohne Vorlage
        if ($standard !== '' && !isset($gruppen[mb_strtolower($standard)])) {
            $gruppen[mb_strtolower($standard)] = ['schluessel' => 'standard', 'name' => $standard,
                'template' => null, 'datei' => '', 'neu' => false, 'vorlagen' => []];
        }

        // Mitgelieferte Marken, die hier noch nicht benutzt werden.
        // Gibt es zu einer Marke mehrere Dateien, gewinnt die Baukasten-
        // Fassung: Sie lässt sich hinterher im Baukasten weiterbearbeiten,
        // die HTML-Fassung nur im Quelltext. Deshalb stehen sie hier vorn.
        $dateien = self::files();
        uasort($dateien, static fn(array $a, array $b): int => $b['baukasten'] <=> $a['baukasten']);

        foreach ($dateien as $schluessel => $angaben) {
            $name = trim((string) $angaben['brand']);
            if ($name === '' || isset($gruppen[mb_strtolower($name)])) {
                continue;
            }
            $gruppen[mb_strtolower($name)] = ['schluessel' => 'datei:' . $schluessel, 'name' => $name,
                'template' => null, 'datei' => $schluessel, 'neu' => true, 'vorlagen' => []];
        }

        // Die eigene Marke zuerst, der Rest alphabetisch
        uasort($gruppen, static function (array $a, array $b) use ($standard): int {
            $eigen = static fn(array $m): int => strcasecmp($m['name'], $standard) === 0 ? 0 : 1;
            return ($eigen($a) <=> $eigen($b)) ?: strcasecmp($a['name'], $b['name']);
        });

        return array_values($gruppen);
    }

    /**
     * Legt eine neue Marke an.
     *
     * Eine Marke ist technisch eine Vorlage mit eigenen Markenangaben.
     * Damit man dafür nicht erst eine Vorlage anlegen und dann Felder
     * ausfüllen muss, macht diese Methode beides in einem Zug.
     *
     * @param string $start 'datei:<name>' aus dem Ordner vorlagen/,
     *                      'kopie:<id>' einer vorhandenen Marke,
     *                      sonst ein leeres Design zum Selbstbauen
     * @param array<string,string> $angaben Markenfelder (siehe BRAND_FIELDS)
     * @return int Kennung der neuen Vorlage
     */
    public static function createBrand(string $name, array $angaben = [], string $start = 'leer'): int
    {
        $name = mb_substr(trim($name), 0, 190);
        if ($name === '') {
            throw new InvalidArgumentException('Bitte geben Sie der Marke einen Namen.');
        }
        foreach (self::brands() as $marke) {
            if (strcasecmp((string) $marke['name'], $name) === 0 && !$marke['neu']) {
                throw new InvalidArgumentException('Eine Marke mit diesem Namen gibt es schon.');
            }
        }

        if (str_starts_with($start, 'datei:')) {
            $id = self::createFromFile(substr($start, 6));
        } elseif (str_starts_with($start, 'kopie:')) {
            $quelle = self::byId((int) substr($start, 6));
            $id = $quelle === null ? 0 : self::create($name, (string) $quelle['html'], '', false,
                trim((string) $quelle['blocks_json']) !== '' ? (string) $quelle['blocks_json'] : null);
        } else {
            $id = self::create($name, '', '', false,
                (string) json_encode(Blocks::starterTemplate(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
        if ($id === 0) {
            // Die gewünschte Grundlage gibt es nicht – dann eben leer
            $id = self::create($name, '', '', false,
                (string) json_encode(Blocks::starterTemplate(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        DB::update('templates', ['name' => $name, 'updated_at' => Util::now()], 'id = ?', [$id]);
        self::saveBrand($id, ['brand_name' => $name] + $angaben);
        return $id;
    }

    /**
     * Speichert die Markenangaben für alle Designs einer Marke.
     *
     * Zwei Designs derselben Marke sollen nicht verschiedene Impressen
     * haben – wer die Angaben ändert, meint die Marke, nicht ein Design.
     *
     * @param array<int,array<string,mixed>> $vorlagen Designs dieser Marke
     * @param array<string,string> $werte
     */
    public static function saveBrandGroup(array $vorlagen, array $werte): void
    {
        foreach ($vorlagen as $vorlage) {
            self::saveBrand((int) $vorlage['id'], $werte);
        }
    }

    /**
     * Wo eine Marke überall benutzt wird – damit vor dem Löschen klar ist,
     * was daran hängt.
     *
     * @param array<int,array<string,mixed>> $vorlagen Designs dieser Marke
     * @return array{listen:int,kampagnen:int,automationen:int,schritte:int}
     */
    public static function brandUsage(array $vorlagen): array
    {
        $ids = array_map(static fn(array $v): int => (int) $v['id'], $vorlagen);
        if ($ids === []) {
            return ['listen' => 0, 'kampagnen' => 0, 'automationen' => 0, 'schritte' => 0];
        }
        $platz = implode(',', array_fill(0, count($ids), '?'));

        return [
            'listen'       => (int) DB::value("SELECT COUNT(*) FROM lists WHERE template_id IN ($platz)", $ids),
            'kampagnen'    => (int) DB::value("SELECT COUNT(*) FROM campaigns WHERE template_id IN ($platz)", $ids),
            'automationen' => (int) DB::value("SELECT COUNT(*) FROM automations WHERE template_id IN ($platz)", $ids),
            'schritte'     => (int) DB::value("SELECT COUNT(*) FROM automation_steps WHERE template_id IN ($platz)", $ids),
        ];
    }

    /**
     * Löst die Auswahl beim Anlegen in eine Vorlage auf.
     *
     * "standard"  – keine Vorlage, es gelten die Einstellungen
     * "vorlage:7" – diese Vorlage
     * "datei:xyz" – aus der mitgelieferten Datei; sie wird beim ersten Mal
     *               angelegt, danach die vorhandene Vorlage genommen.
     */
    public static function brandTemplateId(string $wahl): ?int
    {
        if (str_starts_with($wahl, 'vorlage:')) {
            $id = (int) substr($wahl, 8);
            return self::byId($id) !== null ? $id : null;
        }

        if (str_starts_with($wahl, 'datei:')) {
            $angaben = self::files()[substr($wahl, 6)] ?? null;
            if ($angaben === null || trim((string) $angaben['brand']) === '') {
                return null;
            }
            // Schon einmal angelegt? Dann diese nehmen – keine zweite Vorlage.
            foreach (self::all() as $vorlage) {
                if (strcasecmp((string) $vorlage['brand_name'], (string) $angaben['brand']) === 0) {
                    return (int) $vorlage['id'];
                }
            }
            $id = self::createFromFile(substr($wahl, 6));
            return $id > 0 ? $id : null;
        }

        return null;   // "standard" und alles Unbekannte
    }

    /** Legt beim ersten Start die mitgelieferten Vorlagen an. */
    public static function ensureDefaults(): void
    {
        if ((int) DB::value('SELECT COUNT(*) FROM templates') > 0) {
            return;
        }
        self::create('Standard (AcumenMail)', self::standardHtml(),
            'Klares Layout mit Kopfzeile, Inhaltsbereich und rechtssicherem Footer.', true);
        self::create('Schlicht (nur Text)', self::plainHtml(),
            'Reduzierte Vorlage ohne Farbflächen – wirkt wie eine persönliche E-Mail.');
        // Eine Vorlage im Baukasten-Modus – als Startpunkt zum Umbauen
        self::create('Baukasten-Vorlage', '',
            'Frei gestaltbar per Drag & Drop – Kopfzeile, Inhalt und Footer anpassen.',
            false, (string) json_encode(Blocks::starterTemplate()));
    }

    /* ------------------------------------------------------------ Vorlagen */

    /** Die mitgelieferte Hauptvorlage im Design der Website. */
    public static function standardHtml(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{betreff}}</title>
</head>
<body style="margin:0;padding:0;background-color:#F6F8FA;-webkit-font-smoothing:antialiased;">
<span style="display:none!important;opacity:0;color:#F6F8FA;font-size:1px;line-height:1px;max-height:0;max-width:0;overflow:hidden;">{{preheader}}</span>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F6F8FA;padding:24px 12px;">
<tr><td align="center">

  <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;background-color:#ffffff;border:1px solid #E0E6ED;border-radius:10px;overflow:hidden;">

    <!-- Kopf -->
    <tr><td style="background-color:#14243A;padding:22px 32px;">
      <table role="presentation" cellpadding="0" cellspacing="0" border="0"><tr>
        <td style="background-color:#ffffff;color:#14243A;width:36px;height:36px;border-radius:7px;font-family:Arial,Helvetica,sans-serif;font-weight:bold;font-size:18px;text-align:center;line-height:36px;">A</td>
        <td style="padding-left:12px;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;font-size:18px;letter-spacing:0.2px;">{{marke}}</td>
      </tr></table>
    </td></tr>

    <!-- Inhalt -->
    <tr><td style="padding:32px;font-family:Arial,Helvetica,sans-serif;color:#4A5568;font-size:15px;line-height:1.65;">
      {{inhalt}}
    </td></tr>

    <!-- Footer -->
    <tr><td style="background-color:#F6F8FA;padding:20px 32px;border-top:1px solid #E0E6ED;font-family:Arial,Helvetica,sans-serif;color:#8A95A5;font-size:12px;line-height:1.6;">
      Sie erhalten diese E-Mail, weil Sie sich unter {{website}} für unseren Newsletter angemeldet und die Anmeldung bestätigt haben.<br><br>
      {{impressum}}<br><br>
      <a href="{{abmelden_url}}" style="color:#8A95A5;text-decoration:underline;">Newsletter abbestellen</a> &middot;
      <a href="{{praeferenzen_url}}" style="color:#8A95A5;text-decoration:underline;">Daten &amp; Einstellungen</a> &middot;
      <a href="{{datenschutz_url}}" style="color:#8A95A5;text-decoration:underline;">Datenschutz</a> &middot;
      <a href="{{impressum_url}}" style="color:#8A95A5;text-decoration:underline;">Impressum</a> &middot;
      <a href="{{webansicht_url}}" style="color:#8A95A5;text-decoration:underline;">Im Browser ansehen</a>
    </td></tr>

  </table>

</td></tr>
</table>
</body>
</html>
HTML;
    }

    /** Schlichte Vorlage ohne Farbflächen. */
    public static function plainHtml(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{betreff}}</title>
</head>
<body style="margin:0;padding:0;background-color:#ffffff;">
<span style="display:none!important;opacity:0;color:#ffffff;font-size:1px;line-height:1px;max-height:0;max-width:0;overflow:hidden;">{{preheader}}</span>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:24px 12px;">
<tr><td align="center">
  <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;">
    <tr><td style="font-family:Georgia,'Times New Roman',serif;color:#14243A;font-size:16px;line-height:1.7;padding-bottom:24px;">
      {{inhalt}}
    </td></tr>
    <tr><td style="border-top:1px solid #E0E6ED;padding-top:16px;font-family:Arial,Helvetica,sans-serif;color:#8A95A5;font-size:12px;line-height:1.6;">
      {{impressum}}<br>
      <a href="{{abmelden_url}}" style="color:#8A95A5;">Abmelden</a> &middot;
      <a href="{{praeferenzen_url}}" style="color:#8A95A5;">Einstellungen</a> &middot;
      <a href="{{datenschutz_url}}" style="color:#8A95A5;">Datenschutz</a> &middot;
      <a href="{{impressum_url}}" style="color:#8A95A5;">Impressum</a>
    </td></tr>
  </table>
</td></tr>
</table>
</body>
</html>
HTML;
    }

    /** Startinhalt für eine neue Kampagne. */
    /**
     * Was in der Vorschau an der Stelle von {{inhalt}} steht.
     *
     * Voreingestellt ist ein deutlich markierter Platzhalter: Eine Vorlage
     * ist nur der Rahmen, sie enthält keinen Text. Ein Beispielinhalt würde
     * den Eindruck erwecken, in der Vorlage stecke bereits ein Newsletter.
     *
     * @param bool $mitBeispiel true zeigt einen Beispieltext zur Beurteilung
     *                          von Schrift und Farben
     */
    public static function starterContent(?array $template = null, bool $mitBeispiel = false): string
    {
        $meta   = Blocks::metaFromTemplate($template);
        $akzent = (string) $meta['linkColor'];
        $kopf   = (string) $meta['headColor'];

        if (!$mitBeispiel) {
            return '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">'
                . '<tr><td style="border:2px dashed ' . $meta['borderColor'] . ';border-radius:6px;'
                . 'padding:34px 20px;text-align:center;font-family:' . $meta['font']
                . ';font-size:14px;line-height:1.6;color:' . $meta['textColor'] . ';">'
                . '<strong style="color:' . $kopf . ';">Hier steht der Inhalt der jeweiligen Ausgabe</strong><br>'
                . 'Die Vorlage ist nur der Rahmen. Den Text schreiben Sie unter „Newsletter“.'
                . '</td></tr></table>';
        }

        return str_replace(
            ['#C8102E', '#14243A'],
            [$akzent, $kopf],
            self::starterContentHtml()
        );
    }

    /**
     * Ein schlanker HTML-Rahmen für eine neue Vorlage: Kopfzeile, Platz für
     * den Inhalt, Footer mit den Pflichtangaben – sonst nichts. Alles
     * Weitere baut man sich selbst zusammen.
     */
    public static function minimalHtml(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{betreff}}</title>
</head>
<body style="margin:0;padding:0;background-color:#F6F8FA;">
<span style="display:none!important;opacity:0;color:#F6F8FA;font-size:1px;line-height:1px;max-height:0;max-width:0;overflow:hidden;">{{preheader}}</span>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F6F8FA;padding:24px 12px;">
<tr><td align="center">

  <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;background-color:#ffffff;border:1px solid #E0E6ED;border-radius:8px;">

    <!-- Kopfzeile -->
    <tr><td style="padding:22px 32px;border-bottom:1px solid #E0E6ED;font-family:Arial,Helvetica,sans-serif;font-size:18px;font-weight:bold;color:#14243A;">
      {{marke}}
    </td></tr>

    <!-- Hier wird der Text der jeweiligen Ausgabe eingesetzt -->
    <tr><td style="padding:32px;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.65;color:#4A5568;">
      {{inhalt}}
    </td></tr>

    <!-- Footer: Impressum und Abmeldelink sind Pflicht -->
    <tr><td style="padding:20px 32px;border-top:1px solid #E0E6ED;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:1.6;color:#8A95A5;">
      Sie erhalten diese E-Mail, weil Sie sich unter {{website}} für unseren Newsletter angemeldet und die Anmeldung bestätigt haben.<br><br>
      {{impressum}}<br><br>
      <a href="{{abmelden_url}}" style="color:#8A95A5;text-decoration:underline;">Newsletter abbestellen</a> &middot;
      <a href="{{praeferenzen_url}}" style="color:#8A95A5;text-decoration:underline;">Daten &amp; Einstellungen</a> &middot;
      <a href="{{datenschutz_url}}" style="color:#8A95A5;text-decoration:underline;">Datenschutz</a> &middot;
      <a href="{{impressum_url}}" style="color:#8A95A5;text-decoration:underline;">Impressum</a> &middot;
      <a href="{{webansicht_url}}" style="color:#8A95A5;text-decoration:underline;">Im Browser ansehen</a>
    </td></tr>

  </table>

</td></tr>
</table>
</body>
</html>
HTML;
    }

    private static function starterContentHtml(): string
    {
        return <<<'HTML'
<p style="margin:0 0 16px;font-size:22px;font-weight:bold;color:#14243A;">Überschrift des Newsletters</p>
<p style="margin:0 0 16px;">{{anrede}},</p>
<p style="margin:0 0 16px;">hier steht der erste Absatz. Schreiben Sie so, wie Sie mit einem Kunden sprechen: ein Gedanke pro Absatz, konkrete Beispiele, ein klarer nächster Schritt.</p>
<p style="margin:0 0 24px;">Ein zweiter Absatz mit dem eigentlichen Nutzen für die Leserin oder den Leser.</p>
<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 24px;">
  <tr><td style="background-color:#C8102E;border-radius:6px;">
    <a href="https://www.newsletter-consulting.de/" style="display:inline-block;padding:13px 26px;font-family:Arial,Helvetica,sans-serif;font-size:15px;font-weight:bold;color:#ffffff;text-decoration:none;">Jetzt ansehen</a>
  </td></tr>
</table>
<p style="margin:0;">Herzliche Grüße<br><strong style="color:#14243A;">Ihr Team von {{marke}}</strong></p>
HTML;
    }
}
