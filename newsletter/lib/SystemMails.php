<?php
/**
 * SystemMails – die automatischen Einzelmails des Systems:
 * Bestätigung (Double-Opt-in), Begrüßung und Abmeldebestätigung.
 *
 * Alle drei nutzen dieselbe Vorlage wie die Newsletter, damit das
 * Erscheinungsbild einheitlich bleibt. Welche Vorlage das ist, hängt an
 * der Liste, für die sich jemand angemeldet hat: Wer die Liste einer
 * zweiten Marke abonniert, bekommt auch deren Kopfzeile, deren Footer
 * und vor allem deren Impressum – vorher stand dort immer die
 * Hauptmarke, was bei zwei Websites schlicht falsch war.
 *
 * Die Texte lassen sich je Marke abweichend hinterlegen. Ist für eine
 * Marke nichts eingetragen, gilt der allgemeine Text aus den
 * Einstellungen; eine einzelne Marke muss also gar nichts pflegen.
 */
final class SystemMails
{
    /** Die drei Mails, wie sie im Admin-Bereich heißen. */
    public const KINDS = [
        'bestaetigung' => 'Anmeldung bestätigen (Double-Opt-in)',
        'willkommen'   => 'Begrüßung nach der Bestätigung',
        'abmeldung'    => 'Bestätigung der Abmeldung',
    ];

    /** Welche Texte zu welcher Mail gehören: Feld => Beschriftung. */
    public const FIELDS = [
        'bestaetigung' => ['doi_subject' => 'Betreff', 'doi_intro' => 'Einleitungstext'],
        'willkommen'   => ['welcome_subject' => 'Betreff', 'welcome_intro' => 'Text'],
        'abmeldung'    => ['goodbye_subject' => 'Betreff'],
    ];

    /* --------------------------------------------------------- Versenden */

    /** Bestätigungsmail nach der Anmeldung – ohne sie gibt es keinen Versand. */
    public static function sendDoubleOptIn(?array $sub): void
    {
        self::send('bestaetigung', $sub, true);
    }

    /** Begrüßungsmail nach bestätigter Anmeldung. */
    public static function sendWelcome(?array $sub): void
    {
        self::send('willkommen', $sub, false);
    }

    /** Bestätigung der Abmeldung – schafft Vertrauen und beugt Beschwerden vor. */
    public static function sendGoodbye(?array $sub): void
    {
        self::send('abmeldung', $sub, false);
    }

    /* -------------------------------------------------------------- Marke */

    /**
     * Ein Text dieser Mail – für die Marke, sonst allgemein.
     *
     * @param int|null $templateId Vorlage (Marke) oder null für allgemein
     */
    public static function text(string $feld, ?int $templateId = null): string
    {
        if ($templateId !== null && $templateId > 0) {
            $eigen = trim(Settings::get(self::key($feld, $templateId)));
            if ($eigen !== '') {
                return $eigen;
            }
        }
        return Settings::get($feld);
    }

    /** Hat diese Marke einen eigenen Text hinterlegt? */
    public static function hasOwnText(string $feld, int $templateId): bool
    {
        return trim(Settings::get(self::key($feld, $templateId))) !== '';
    }

    /**
     * Speichert die Texte einer Marke. Ein leeres Feld heißt: wieder den
     * allgemeinen Text verwenden – so kommt man ohne Umweg zurück.
     *
     * @param array<string,string> $werte
     */
    public static function saveTexts(int $templateId, array $werte): void
    {
        $erlaubt = [];
        foreach (self::FIELDS as $felder) {
            $erlaubt += $felder;
        }
        $speichern = [];
        foreach ($werte as $feld => $wert) {
            if (!isset($erlaubt[$feld])) {
                continue;
            }
            $speichern[self::key($feld, $templateId)] = mb_substr(trim($wert), 0, 2000);
        }
        if ($speichern !== []) {
            Settings::setMany($speichern);
        }
    }

    /** Der Einstellungsschlüssel eines markenspezifischen Textes. */
    private static function key(string $feld, int $templateId): string
    {
        return 'marke' . $templateId . '_' . $feld;
    }

    /**
     * Die Vorlage, in der ein Empfänger seine Systemmails bekommt:
     * die seiner Liste, sonst die Standardvorlage.
     */
    public static function templateFor(?array $sub): ?array
    {
        if ($sub !== null && (int) ($sub['id'] ?? 0) > 0) {
            foreach (Subscribers::listIds((int) $sub['id']) as $listId) {
                $vorlage = Lists::template((int) $listId);
                if ($vorlage !== null) {
                    return $vorlage;
                }
            }
        }
        return Templates::defaultTemplate();
    }

    /* ------------------------------------------------------------- Inhalt */

    /**
     * Betreff und Inhaltsteil einer Systemmail – ohne Rahmen, ohne
     * Empfängerdaten. Versand und Vorschau nehmen denselben Weg, damit in
     * der Vorschau nichts anderes steht als in der Mail.
     *
     * @param array<string,mixed> $sub
     * @return array{subject:string,content:string}
     */
    public static function compose(string $art, array $sub, ?array $template): array
    {
        $id     = $template !== null ? (int) $template['id'] : null;
        $akzent = (string) Blocks::metaFromTemplate($template)['linkColor'];

        if ($art === 'willkommen') {
            return [
                'subject' => self::text('welcome_subject', $id),
                'content' => self::paragraph('{{anrede}},')
                    . self::paragraph(Util::e(self::text('welcome_intro', $id)))
                    . self::paragraph('Sie können Ihre Angaben jederzeit ändern oder den Newsletter mit einem Klick abbestellen – '
                        . 'die Links dazu stehen in jeder E-Mail ganz unten.')
                    . self::button('Website besuchen', Settings::get('website_url'), $akzent)
                    . self::paragraph('Herzliche Grüße<br><strong style="color:#14243A;">{{marke}}</strong>'),
            ];
        }

        if ($art === 'abmeldung') {
            return [
                'subject' => self::text('goodbye_subject', $id),
                'content' => self::paragraph('{{anrede}},')
                    . self::paragraph('Ihre Abmeldung ist erledigt: Ab sofort erhalten Sie keinen Newsletter mehr von uns. '
                        . 'Ihre Adresse bleibt lediglich in einer Sperrliste, damit Sie nicht versehentlich erneut angeschrieben werden.')
                    . self::paragraph('Sollten Sie sich später anders entscheiden, können Sie sich jederzeit erneut anmelden:')
                    . self::button('Newsletter erneut abonnieren', Urls::signupPage(), $akzent)
                    . self::paragraph('Danke für die gemeinsame Zeit.<br><strong style="color:#14243A;">{{marke}}</strong>'),
            ];
        }

        $confirmUrl = Urls::confirm((string) ($sub['token'] ?? ''));
        return [
            'subject' => self::text('doi_subject', $id),
            'content' => self::paragraph('{{anrede}},')
                . self::paragraph(Util::e(self::text('doi_intro', $id)))
                . self::button('Anmeldung jetzt bestätigen', $confirmUrl, $akzent)
                . self::paragraph('Falls sich der Knopf nicht öffnen lässt, kopieren Sie bitte diese Adresse in Ihren Browser:<br>'
                    . '<span style="word-break:break-all;font-size:13px;color:#4A5568;">' . Util::e($confirmUrl) . '</span>')
                . self::hint('Diese Anmeldung wurde am ' . date('d.m.Y') . ' um ' . date('H:i') . ' Uhr für die Adresse '
                    . '<strong>' . Util::e((string) ($sub['email'] ?? '')) . '</strong> angefordert. '
                    . 'Wenn Sie das nicht waren, ignorieren Sie diese E-Mail einfach – ohne Ihre Bestätigung erhalten Sie keinen Newsletter.'),
        ];
    }

    /**
     * Die fertige Mail für einen Empfänger.
     *
     * @param array<string,mixed> $sub
     * @return array{subject:string,html:string,text:string,links:array<string,string>}
     */
    public static function render(string $art, array $sub, ?array $template): array
    {
        $teil = self::compose($art, $sub, $template);
        $html = Renderer::wrap($template, $teil['content'], $teil['subject'], '');
        // Die Marke der Vorlage einsetzen – sonst stünde im Footer die
        // Anschrift der Hauptmarke, auch wenn die Liste einer anderen gehört.
        $html = Renderer::applyBrand($html, $template, true);

        $links = [
            'abmelden_url'     => Urls::unsubscribe((string) ($sub['token'] ?? '')),
            'praeferenzen_url' => Urls::preferences((string) ($sub['token'] ?? '')),
            'webansicht_url'   => Templates::brand($template)['website_url'],
        ];

        $text = Mailer::htmlToText(Renderer::personalize(
                Renderer::applyBrand($teil['content'], $template, false), $sub, $links, true))
            . "\n\n-- \n" . Templates::brand($template)['imprint']
            . "\nAbmelden: " . $links['abmelden_url']
            . "\nDaten & Einstellungen: " . $links['praeferenzen_url'] . "\n";

        return [
            'subject' => Renderer::personalize($teil['subject'], $sub, $links, false),
            'html'    => Renderer::personalize($html, $sub, $links, true),
            'text'    => $text,
            'links'   => $links,
        ];
    }

    /**
     * Wie die Mail bei einem Beispielempfänger aussieht – für den
     * Admin-Bereich. Ohne echte Links, damit nichts versehentlich
     * bestätigt oder abgemeldet wird.
     */
    public static function preview(string $art, ?array $template): string
    {
        $sub  = Renderer::sampleSubscriber();
        $mail = self::render($art, $sub, $template);
        return str_replace(
            [Urls::unsubscribe((string) $sub['token']), Urls::preferences((string) $sub['token'])],
            ['#', '#'],
            $mail['html']
        );
    }

    /* -------------------------------------------------------------- Intern */

    /**
     * Baut die Mail in der Vorlage des Empfängers und verschickt sie.
     *
     * @param bool $mussKlappen true: Fehler weiterreichen (die Anmeldung
     *             darf nicht als geglückt gelten, wenn die Mail nicht geht)
     */
    private static function send(string $art, ?array $sub, bool $mussKlappen): void
    {
        if ($sub === null) {
            return;
        }
        $mail = self::render($art, $sub, self::templateFor($sub));

        try {
            Mailer::send([
                'to'      => (string) $sub['email'],
                'to_name' => Subscribers::displayName($sub),
                'subject' => $mail['subject'],
                'html'    => $mail['html'],
                'text'    => $mail['text'],
                'headers' => [
                    'Auto-Submitted'        => 'auto-replied',
                    'X-Mail-Type'           => $art === 'bestaetigung' ? 'double-opt-in'
                                               : ($art === 'willkommen' ? 'welcome' : 'goodbye'),
                    'List-Unsubscribe'      => '<' . $mail['links']['abmelden_url'] . '&one=1>',
                    'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('systemmail', 'Versand an ' . $sub['email'] . ' fehlgeschlagen: ' . $e->getMessage());
            if ($mussKlappen) {
                throw $e;
            }
        }
    }

    private static function paragraph(string $html): string
    {
        return '<p style="margin:0 0 16px;">' . $html . '</p>';
    }

    private static function button(string $label, string $url, string $farbe = '#C8102E'): string
    {
        return '<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:8px 0 24px;">'
            . '<tr><td style="background-color:' . Util::e($farbe) . ';border-radius:6px;">'
            . '<a href="' . Util::e($url) . '" style="display:inline-block;padding:13px 26px;'
            . 'font-family:Arial,Helvetica,sans-serif;font-size:15px;font-weight:bold;color:#ffffff;text-decoration:none;">'
            . Util::e($label) . '</a></td></tr></table>';
    }

    private static function hint(string $html): string
    {
        return '<p style="margin:24px 0 0;padding:14px 16px;background-color:#F6F8FA;border-left:3px solid #C8102E;'
            . 'font-size:13px;line-height:1.6;color:#4A5568;">' . $html . '</p>';
    }
}
