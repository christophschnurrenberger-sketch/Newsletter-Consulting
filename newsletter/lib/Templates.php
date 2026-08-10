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
    public static function starterContent(): string
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
