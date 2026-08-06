<?php
/**
 * SystemMails – die automatischen Einzelmails des Systems:
 * Bestätigung (Double-Opt-in), Begrüßung und Abmeldebestätigung.
 *
 * Alle drei nutzen dieselbe Vorlage wie die Newsletter, damit das
 * Erscheinungsbild einheitlich bleibt.
 */
final class SystemMails
{
    /** Bestätigungsmail nach der Anmeldung – ohne sie gibt es keinen Versand. */
    public static function sendDoubleOptIn(?array $sub): void
    {
        if ($sub === null) {
            return;
        }
        $confirmUrl = Urls::confirm((string) $sub['token']);
        $intro      = Settings::get('doi_intro');

        $content = self::paragraph('{{anrede}},')
            . self::paragraph(Util::e($intro))
            . self::button('Anmeldung jetzt bestätigen', $confirmUrl)
            . self::paragraph('Falls sich der Knopf nicht öffnen lässt, kopieren Sie bitte diese Adresse in Ihren Browser:<br>'
                . '<span style="word-break:break-all;font-size:13px;color:#4A5568;">' . Util::e($confirmUrl) . '</span>')
            . self::hint('Diese Anmeldung wurde am ' . date('d.m.Y') . ' um ' . date('H:i') . ' Uhr für die Adresse '
                . '<strong>' . Util::e((string) $sub['email']) . '</strong> angefordert. '
                . 'Wenn Sie das nicht waren, ignorieren Sie diese E-Mail einfach – ohne Ihre Bestätigung erhalten Sie keinen Newsletter.');

        self::deliver($sub, Settings::get('doi_subject'), $content, [
            'Auto-Submitted' => 'auto-replied',
            'X-Mail-Type'    => 'double-opt-in',
        ]);
    }

    /** Begrüßungsmail nach bestätigter Anmeldung. */
    public static function sendWelcome(?array $sub): void
    {
        if ($sub === null) {
            return;
        }
        $content = self::paragraph('{{anrede}},')
            . self::paragraph(Util::e(Settings::get('welcome_intro')))
            . self::paragraph('Sie können Ihre Angaben jederzeit ändern oder den Newsletter mit einem Klick abbestellen – '
                . 'die Links dazu stehen in jeder E-Mail ganz unten.')
            . self::button('Website besuchen', Settings::get('website_url'))
            . self::paragraph('Herzliche Grüße<br><strong style="color:#14243A;">{{marke}}</strong>');

        self::deliver($sub, Settings::get('welcome_subject'), $content, [
            'Auto-Submitted' => 'auto-replied',
            'X-Mail-Type'    => 'welcome',
        ], false);
    }

    /** Bestätigung der Abmeldung – schafft Vertrauen und beugt Beschwerden vor. */
    public static function sendGoodbye(?array $sub): void
    {
        if ($sub === null) {
            return;
        }
        $content = self::paragraph('{{anrede}},')
            . self::paragraph('Ihre Abmeldung ist erledigt: Ab sofort erhalten Sie keinen Newsletter mehr von uns. '
                . 'Ihre Adresse bleibt lediglich in einer Sperrliste, damit Sie nicht versehentlich erneut angeschrieben werden.')
            . self::paragraph('Sollten Sie sich später anders entscheiden, können Sie sich jederzeit erneut anmelden:')
            . self::button('Newsletter erneut abonnieren', Urls::signupPage())
            . self::paragraph('Danke für die gemeinsame Zeit.<br><strong style="color:#14243A;">{{marke}}</strong>');

        self::deliver($sub, Settings::get('goodbye_subject'), $content, [
            'Auto-Submitted' => 'auto-replied',
            'X-Mail-Type'    => 'goodbye',
        ], false);
    }

    /* -------------------------------------------------------------- Intern */

    /**
     * Baut die Mail aus der Standardvorlage und verschickt sie.
     *
     * @param array<string,string> $headers
     * @param bool $throw true: Fehler weiterreichen (Anmeldung muss scheitern)
     */
    private static function deliver(array $sub, string $subject, string $content, array $headers = [], bool $throw = true): void
    {
        $template = Templates::defaultTemplate();
        $html     = Renderer::wrap($template, $content, $subject, '');

        $links = [
            'abmelden_url'     => Urls::unsubscribe((string) $sub['token']),
            'praeferenzen_url' => Urls::preferences((string) $sub['token']),
            'webansicht_url'   => Settings::get('website_url'),
        ];

        $text = Mailer::htmlToText(Renderer::personalize($content, $sub, $links, true))
            . "\n\n-- \n" . Settings::get('imprint')
            . "\nAbmelden: " . $links['abmelden_url']
            . "\nDaten & Einstellungen: " . $links['praeferenzen_url'] . "\n";

        try {
            Mailer::send([
                'to'      => (string) $sub['email'],
                'to_name' => Subscribers::displayName($sub),
                'subject' => Renderer::personalize($subject, $sub, $links, false),
                'html'    => Renderer::personalize($html, $sub, $links, true),
                'text'    => $text,
                'headers' => $headers + [
                    'List-Unsubscribe' => '<' . $links['abmelden_url'] . '&one=1>',
                    'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('systemmail', 'Versand an ' . $sub['email'] . ' fehlgeschlagen: ' . $e->getMessage());
            if ($throw) {
                throw $e;
            }
        }
    }

    private static function paragraph(string $html): string
    {
        return '<p style="margin:0 0 16px;">' . $html . '</p>';
    }

    private static function button(string $label, string $url): string
    {
        return '<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:8px 0 24px;">'
            . '<tr><td style="background-color:#C8102E;border-radius:6px;">'
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
