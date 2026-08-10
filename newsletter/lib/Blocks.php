<?php
/**
 * Blocks – der Baukasten.
 *
 * Im Admin-Bereich stellen Sie eine Mail per Drag & Drop aus Bausteinen
 * zusammen. Diese Klasse macht daraus fertiges E-Mail-HTML: tabellenbasiert
 * und mit Inline-Stilen, weil Outlook & Co. nichts anderes zuverlässig
 * darstellen.
 *
 * Zwei Ausgabearten:
 *   renderContent()  – nur der Inhaltsbereich (für eine Newsletter-Ausgabe)
 *   renderDocument() – eine komplette Vorlage mit Kopf, Inhalt und Footer
 *
 * Der Baukasten ist ein Erzeuger: Das Ergebnis landet in denselben Feldern
 * (campaigns.content_html bzw. templates.html), die es vorher auch gab.
 * Der gesamte Versandweg bleibt dadurch unverändert.
 */
final class Blocks
{
    /** Bausteine, die es gibt. */
    public const TYPES = [
        'heading'  => 'Überschrift',
        'text'     => 'Textabsatz',
        'image'    => 'Bild',
        'button'   => 'Knopf (Call-to-Action)',
        'divider'  => 'Trennlinie',
        'spacer'   => 'Abstand',
        'columns'  => 'Zwei Spalten',
        'social'   => 'Links (z. B. Social Media)',
        'html'     => 'Eigenes HTML',
        'content'  => 'Inhalt der Ausgabe',
    ];

    /** Diese Bausteine dürfen in einer Spalte stehen. */
    private const NESTABLE = ['heading', 'text', 'image', 'button', 'divider', 'spacer'];

    /* ------------------------------------------------------------ Defaults */

    /** @return array<string,mixed> Grundeinstellungen einer Mail */
    public static function defaultMeta(): array
    {
        return [
            'bg'         => '#F6F8FA',
            'cardBg'     => '#FFFFFF',
            'width'      => 600,
            'font'       => "Arial, Helvetica, sans-serif",
            'textColor'  => '#4A5568',
            'headColor'  => '#14243A',
            'linkColor'  => '#C8102E',
            'radius'     => 10,
            'padding'    => 32,
            'showHeader' => 1,
            'headerBg'   => '#14243A',
            'headerText' => '#FFFFFF',
            'logoText'   => 'A',
            'showFooter' => 1,
        ];
    }

    /** Startaufbau für eine neue Ausgabe (Baukasten-Modus). */
    public static function starterCampaign(): array
    {
        return [
            'meta'   => self::defaultMeta(),
            'blocks' => [
                self::block('heading', ['text' => 'Ihre Überschrift', 'size' => 24]),
                self::block('text', ['html' => '<p>{{anrede}},</p><p>hier steht der erste Absatz. '
                    . 'Schreiben Sie so, wie Sie mit einer Kundin sprechen: ein Gedanke pro Absatz, '
                    . 'ein konkretes Beispiel, ein klarer nächster Schritt.</p>']),
                self::block('button', ['label' => 'Jetzt ansehen', 'href' => 'https://www.newsletter-consulting.de/']),
                self::block('text', ['html' => '<p>Herzliche Grüße<br><strong>Ihr Team von {{marke}}</strong></p>']),
            ],
        ];
    }

    /** Startaufbau für eine neue Vorlage (mit Platzhalter für den Inhalt). */
    public static function starterTemplate(): array
    {
        return [
            'meta'   => self::defaultMeta(),
            'blocks' => [
                self::block('content'),
            ],
        ];
    }

    /**
     * Erzeugt einen Baustein mit sinnvollen Vorgaben.
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public static function block(string $type, array $data = []): array
    {
        $defaults = [
            'heading' => ['text' => 'Überschrift', 'size' => 22, 'align' => 'left', 'color' => '#14243A', 'space' => 12],
            'text'    => ['html' => '<p>Ihr Text …</p>', 'size' => 15, 'align' => 'left', 'color' => '', 'space' => 12],
            'image'   => ['src' => '', 'alt' => '', 'href' => '', 'width' => 100, 'align' => 'center', 'space' => 12],
            'button'  => ['label' => 'Mehr erfahren', 'href' => '', 'bg' => '#C8102E', 'color' => '#FFFFFF',
                          'align' => 'left', 'radius' => 6, 'space' => 12, 'full' => 0],
            'divider' => ['color' => '#E0E6ED', 'thickness' => 1, 'space' => 12],
            'spacer'  => ['height' => 24],
            'columns' => ['gap' => 20, 'space' => 12, 'left' => [], 'right' => []],
            'social'  => ['links' => [], 'align' => 'center', 'space' => 12, 'color' => '#8A95A5'],
            'html'    => ['html' => '<p>Eigenes HTML …</p>', 'space' => 12],
            'content' => [],
        ];
        $block = ['id' => 'b' . bin2hex(random_bytes(4)), 'type' => $type];
        return $block + $data + ($defaults[$type] ?? []);
    }

    /* -------------------------------------------------------------- Prüfen */

    /**
     * Liest die JSON-Fassung aus dem Editor ein und säubert sie.
     * Alles, was nicht erwartet wird, fliegt raus.
     *
     * @return array{meta:array<string,mixed>,blocks:array<int,array<string,mixed>>}
     */
    public static function parse(string $json): array
    {
        $data = json_decode($json, true);
        if (!is_array($data)) {
            return ['meta' => self::defaultMeta(), 'blocks' => []];
        }
        $meta = self::cleanMeta(is_array($data['meta'] ?? null) ? $data['meta'] : []);

        $blocks = [];
        foreach ((array) ($data['blocks'] ?? []) as $raw) {
            $block = self::cleanBlock(is_array($raw) ? $raw : [], true);
            if ($block !== null) {
                $blocks[] = $block;
            }
        }
        return ['meta' => $meta, 'blocks' => $blocks];
    }

    /** @param array<string,mixed> $meta */
    private static function cleanMeta(array $meta): array
    {
        $default = self::defaultMeta();
        $out     = [];
        foreach ($default as $key => $fallback) {
            $value = $meta[$key] ?? $fallback;
            $out[$key] = match ($key) {
                'width'   => max(320, min(900, (int) $value)),
                'radius'  => max(0, min(30, (int) $value)),
                'padding' => max(0, min(60, (int) $value)),
                'showHeader', 'showFooter' => (int) ((bool) $value),
                'font'    => self::cleanFont((string) $value),
                'logoText' => mb_substr(trim(strip_tags((string) $value)), 0, 3),
                default   => self::color((string) $value, (string) $fallback),
            };
        }
        return $out;
    }

    /**
     * @param array<string,mixed> $raw
     * @return array<string,mixed>|null
     */
    private static function cleanBlock(array $raw, bool $topLevel): ?array
    {
        $type = (string) ($raw['type'] ?? '');
        if (!isset(self::TYPES[$type])) {
            return null;
        }
        if (!$topLevel && !in_array($type, self::NESTABLE, true)) {
            return null;
        }

        $id    = preg_replace('/[^a-z0-9]/i', '', (string) ($raw['id'] ?? '')) ?: ('b' . bin2hex(random_bytes(4)));
        $block = ['id' => mb_substr($id, 0, 24), 'type' => $type];
        $space = static fn($v) => max(0, min(80, (int) $v));
        $align = static fn($v) => in_array($v, ['left', 'center', 'right'], true) ? $v : 'left';

        switch ($type) {
            case 'heading':
                $block += [
                    'text'  => mb_substr(trim(strip_tags((string) ($raw['text'] ?? ''))), 0, 300),
                    'size'  => max(12, min(48, (int) ($raw['size'] ?? 22))),
                    'align' => $align($raw['align'] ?? 'left'),
                    'color' => self::color((string) ($raw['color'] ?? ''), '#14243A'),
                    'space' => $space($raw['space'] ?? 12),
                ];
                break;

            case 'text':
                $block += [
                    'html'  => self::cleanHtml((string) ($raw['html'] ?? '')),
                    'size'  => max(11, min(28, (int) ($raw['size'] ?? 15))),
                    'align' => $align($raw['align'] ?? 'left'),
                    'color' => self::color((string) ($raw['color'] ?? ''), ''),
                    'space' => $space($raw['space'] ?? 12),
                ];
                break;

            case 'image':
                $block += [
                    'src'   => self::cleanUrl((string) ($raw['src'] ?? ''), true),
                    'alt'   => mb_substr(trim(strip_tags((string) ($raw['alt'] ?? ''))), 0, 200),
                    'href'  => self::cleanUrl((string) ($raw['href'] ?? '')),
                    'width' => max(10, min(100, (int) ($raw['width'] ?? 100))),
                    'align' => $align($raw['align'] ?? 'center'),
                    'space' => $space($raw['space'] ?? 12),
                ];
                break;

            case 'button':
                $block += [
                    'label'  => mb_substr(trim(strip_tags((string) ($raw['label'] ?? ''))), 0, 120),
                    'href'   => self::cleanUrl((string) ($raw['href'] ?? '')),
                    'bg'     => self::color((string) ($raw['bg'] ?? ''), '#C8102E'),
                    'color'  => self::color((string) ($raw['color'] ?? ''), '#FFFFFF'),
                    'align'  => $align($raw['align'] ?? 'left'),
                    'radius' => max(0, min(30, (int) ($raw['radius'] ?? 6))),
                    'full'   => (int) ((bool) ($raw['full'] ?? 0)),
                    'space'  => $space($raw['space'] ?? 12),
                ];
                break;

            case 'divider':
                $block += [
                    'color'     => self::color((string) ($raw['color'] ?? ''), '#E0E6ED'),
                    'thickness' => max(1, min(8, (int) ($raw['thickness'] ?? 1))),
                    'space'     => $space($raw['space'] ?? 12),
                ];
                break;

            case 'spacer':
                $block += ['height' => max(4, min(120, (int) ($raw['height'] ?? 24)))];
                break;

            case 'columns':
                $block += [
                    'gap'   => max(0, min(48, (int) ($raw['gap'] ?? 20))),
                    'space' => $space($raw['space'] ?? 12),
                    'left'  => self::cleanNested($raw['left'] ?? []),
                    'right' => self::cleanNested($raw['right'] ?? []),
                ];
                break;

            case 'social':
                $links = [];
                foreach ((array) ($raw['links'] ?? []) as $link) {
                    if (!is_array($link)) {
                        continue;
                    }
                    $label = mb_substr(trim(strip_tags((string) ($link['label'] ?? ''))), 0, 40);
                    $href  = self::cleanUrl((string) ($link['href'] ?? ''));
                    if ($label !== '' && count($links) < 8) {
                        $links[] = ['label' => $label, 'href' => $href];
                    }
                }
                $block += [
                    'links' => $links,
                    'align' => $align($raw['align'] ?? 'center'),
                    'color' => self::color((string) ($raw['color'] ?? ''), '#8A95A5'),
                    'space' => $space($raw['space'] ?? 12),
                ];
                break;

            case 'html':
                $block += [
                    'html'  => self::cleanHtml((string) ($raw['html'] ?? ''), true),
                    'space' => $space($raw['space'] ?? 12),
                ];
                break;

            case 'content':
                // Kein Inhalt – hier wird später die Ausgabe eingesetzt.
                break;
        }
        return $block;
    }

    /** @return array<int,array<string,mixed>> */
    private static function cleanNested($raw): array
    {
        $out = [];
        foreach ((array) $raw as $item) {
            $block = self::cleanBlock(is_array($item) ? $item : [], false);
            if ($block !== null && count($out) < 20) {
                $out[] = $block;
            }
        }
        return $out;
    }

    /* ------------------------------------------------------------ Rendern */

    /**
     * Nur der Inhaltsbereich – für eine Newsletter-Ausgabe.
     * Das Ergebnis landet in campaigns.content_html.
     */
    public static function renderContent(array $data): string
    {
        $meta = $data['meta'] ?? self::defaultMeta();
        $html = '';
        $needsColumnCss = false;

        foreach ($data['blocks'] ?? [] as $block) {
            if ($block['type'] === 'content') {
                continue; // gehört nur in Vorlagen
            }
            if ($block['type'] === 'columns') {
                $needsColumnCss = true;
            }
            $html .= self::renderBlock($block, $meta);
        }

        return ($needsColumnCss ? self::columnCss() : '') . $html;
    }

    /**
     * Komplette Vorlage inklusive Grundgerüst, Kopfzeile und Footer.
     * Das Ergebnis landet in templates.html und enthält {{inhalt}}.
     */
    public static function renderDocument(array $data): string
    {
        $meta  = $data['meta'] ?? self::defaultMeta();
        $inner = '';
        $hasContent = false;

        foreach ($data['blocks'] ?? [] as $block) {
            if ($block['type'] === 'content') {
                $hasContent = true;
            }
            $inner .= self::renderBlock($block, $meta);
        }
        // Ohne Inhaltsbaustein bliebe die Vorlage leer – deshalb anhängen.
        if (!$hasContent) {
            $inner .= self::renderBlock(['type' => 'content'], $meta);
        }

        $font  = $meta['font'];
        $width = (int) $meta['width'];
        $pad   = (int) $meta['padding'];

        $header = '';
        if (!empty($meta['showHeader'])) {
            $header = '<tr><td style="background-color:' . $meta['headerBg'] . ';padding:22px ' . $pad . 'px;">'
                . '<table role="presentation" cellpadding="0" cellspacing="0" border="0"><tr>'
                . '<td style="background-color:' . $meta['cardBg'] . ';color:' . $meta['headerBg']
                . ';width:36px;height:36px;border-radius:7px;font-family:' . $font
                . ';font-weight:bold;font-size:18px;text-align:center;line-height:36px;">'
                . Util::e((string) $meta['logoText']) . '</td>'
                . '<td style="padding-left:12px;color:' . $meta['headerText'] . ';font-family:' . $font
                . ';font-weight:bold;font-size:18px;letter-spacing:0.2px;">{{marke}}</td>'
                . '</tr></table></td></tr>';
        }

        $footer = '';
        if (!empty($meta['showFooter'])) {
            $footer = '<tr><td style="background-color:' . $meta['bg'] . ';padding:20px ' . $pad
                . 'px;border-top:1px solid #E0E6ED;font-family:' . $font
                . ';color:#8A95A5;font-size:12px;line-height:1.6;">'
                . 'Sie erhalten diese E-Mail, weil Sie sich unter {{website}} für unseren Newsletter '
                . 'angemeldet und die Anmeldung bestätigt haben.<br><br>{{impressum}}<br><br>'
                . '<a href="{{abmelden_url}}" style="color:#8A95A5;text-decoration:underline;">Newsletter abbestellen</a> &middot; '
                . '<a href="{{praeferenzen_url}}" style="color:#8A95A5;text-decoration:underline;">Daten &amp; Einstellungen</a> &middot; '
                . '<a href="{{datenschutz_url}}" style="color:#8A95A5;text-decoration:underline;">Datenschutz</a> &middot; '
                . '<a href="{{impressum_url}}" style="color:#8A95A5;text-decoration:underline;">Impressum</a> &middot; '
                . '<a href="{{webansicht_url}}" style="color:#8A95A5;text-decoration:underline;">Im Browser ansehen</a>'
                . '</td></tr>';
        } else {
            // Abmeldelink ist Pflicht – auch ohne gestalteten Footer.
            $footer = '<tr><td style="padding:12px ' . $pad . 'px;font-family:' . $font
                . ';color:#8A95A5;font-size:12px;line-height:1.6;">{{impressum}}<br>'
                . '<a href="{{abmelden_url}}" style="color:#8A95A5;">Abmelden</a></td></tr>';
        }

        return '<!DOCTYPE html>' . "\n"
            . '<html lang="de"><head><meta charset="utf-8">' . "\n"
            . '<meta name="viewport" content="width=device-width,initial-scale=1">' . "\n"
            . '<title>{{betreff}}</title>' . "\n"
            . self::columnCss() . "\n"
            . '</head>' . "\n"
            . '<body style="margin:0;padding:0;background-color:' . $meta['bg'] . ';">' . "\n"
            . '<span style="display:none!important;opacity:0;color:' . $meta['bg']
            . ';font-size:1px;line-height:1px;max-height:0;max-width:0;overflow:hidden;">{{preheader}}</span>' . "\n"
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" '
            . 'style="background-color:' . $meta['bg'] . ';padding:24px 12px;"><tr><td align="center">' . "\n"
            . '<table role="presentation" width="' . $width . '" cellpadding="0" cellspacing="0" border="0" '
            . 'style="max-width:' . $width . 'px;width:100%;background-color:' . $meta['cardBg']
            . ';border:1px solid #E0E6ED;border-radius:' . (int) $meta['radius'] . 'px;overflow:hidden;">' . "\n"
            . $header
            . '<tr><td style="padding:' . $pad . 'px;font-family:' . $font . ';color:' . $meta['textColor']
            . ';font-size:15px;line-height:1.65;">' . "\n" . $inner . "\n" . '</td></tr>' . "\n"
            . $footer
            . '</table></td></tr></table></body></html>';
    }

    /** Spalten brechen auf dem Handy untereinander um. */
    private static function columnCss(): string
    {
        return '<style type="text/css">@media only screen and (max-width:600px){'
            . '.nl-col{display:block!important;width:100%!important;max-width:100%!important;'
            . 'padding-left:0!important;padding-right:0!important;}'
            . '.nl-col-gap{height:16px!important;}}</style>';
    }

    /**
     * Ein einzelner Baustein.
     * @param array<string,mixed> $block
     * @param array<string,mixed> $meta
     */
    private static function renderBlock(array $block, array $meta): string
    {
        $type  = (string) ($block['type'] ?? '');
        $space = (int) ($block['space'] ?? 12);
        $font  = (string) ($meta['font'] ?? 'Arial, Helvetica, sans-serif');
        $wrap  = static function (string $inner, int $bottom) use ($font): string {
            return '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" '
                . 'style="width:100%;"><tr><td style="padding:0 0 ' . $bottom . 'px;font-family:' . $font . ';">'
                . $inner . '</td></tr></table>' . "\n";
        };

        switch ($type) {
            case 'heading':
                return $wrap(
                    '<h2 style="margin:0;font-family:' . $font . ';font-size:' . (int) $block['size']
                    . 'px;line-height:1.3;font-weight:bold;color:' . $block['color']
                    . ';text-align:' . $block['align'] . ';">' . Util::e((string) $block['text']) . '</h2>',
                    $space
                );

            case 'text':
                $color = $block['color'] !== '' ? $block['color'] : (string) $meta['textColor'];
                return $wrap(
                    '<div style="font-family:' . $font . ';font-size:' . (int) $block['size']
                    . 'px;line-height:1.65;color:' . $color . ';text-align:' . $block['align'] . ';">'
                    . self::styleLinks((string) $block['html'], (string) $meta['linkColor']) . '</div>',
                    $space
                );

            case 'image':
                if ((string) $block['src'] === '') {
                    return '';
                }
                $img = '<img src="' . Util::e((string) $block['src']) . '" alt="' . Util::e((string) $block['alt'])
                    . '" width="' . (int) round(((int) $meta['width'] - 2 * (int) $meta['padding']) * ((int) $block['width'] / 100))
                    . '" style="display:block;width:' . (int) $block['width']
                    . '%;max-width:100%;height:auto;border:0;outline:none;text-decoration:none;">';
                if ((string) $block['href'] !== '') {
                    $img = '<a href="' . Util::e((string) $block['href']) . '" target="_blank" style="text-decoration:none;">'
                        . $img . '</a>';
                }
                return $wrap('<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">'
                    . '<tr><td align="' . $block['align'] . '">' . $img . '</td></tr></table>', $space);

            case 'button':
                if ((string) $block['label'] === '') {
                    return '';
                }
                $href = (string) $block['href'] !== '' ? (string) $block['href'] : '{{website_url}}';
                $btn  = '<table role="presentation" cellpadding="0" cellspacing="0" border="0"'
                    . ((int) $block['full'] === 1 ? ' width="100%"' : '') . '><tr>'
                    . '<td align="center" bgcolor="' . $block['bg'] . '" style="background-color:' . $block['bg']
                    . ';border-radius:' . (int) $block['radius'] . 'px;">'
                    . '<a href="' . Util::e($href) . '" target="_blank" style="display:inline-block;padding:13px 26px;'
                    . 'font-family:' . $font . ';font-size:15px;font-weight:bold;color:' . $block['color']
                    . ';text-decoration:none;border-radius:' . (int) $block['radius'] . 'px;">'
                    . Util::e((string) $block['label']) . '</a></td></tr></table>';
                return $wrap('<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"><tr>'
                    . '<td align="' . $block['align'] . '">' . $btn . '</td></tr></table>', $space);

            case 'divider':
                return $wrap('<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"><tr>'
                    . '<td style="border-top:' . (int) $block['thickness'] . 'px solid ' . $block['color']
                    . ';font-size:0;line-height:0;">&nbsp;</td></tr></table>', $space);

            case 'spacer':
                return '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"><tr>'
                    . '<td style="height:' . (int) $block['height'] . 'px;line-height:'
                    . (int) $block['height'] . 'px;font-size:0;">&nbsp;</td></tr></table>' . "\n";

            case 'columns':
                $gap   = (int) $block['gap'];
                $left  = '';
                $right = '';
                foreach ((array) $block['left'] as $child) {
                    $left .= self::renderBlock($child, $meta);
                }
                foreach ((array) $block['right'] as $child) {
                    $right .= self::renderBlock($child, $meta);
                }
                return $wrap(
                    '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"><tr>'
                    . '<td class="nl-col" width="50%" valign="top" style="width:50%;padding-right:' . (int) ($gap / 2) . 'px;">'
                    . ($left !== '' ? $left : '&nbsp;') . '</td>'
                    . '<td class="nl-col" width="50%" valign="top" style="width:50%;padding-left:' . (int) ($gap / 2) . 'px;">'
                    . ($right !== '' ? $right : '&nbsp;') . '</td>'
                    . '</tr></table>',
                    $space
                );

            case 'social':
                if ($block['links'] === []) {
                    return '';
                }
                $parts = [];
                foreach ((array) $block['links'] as $link) {
                    $href = (string) $link['href'] !== '' ? (string) $link['href'] : '#';
                    $parts[] = '<a href="' . Util::e($href) . '" target="_blank" style="color:' . $block['color']
                        . ';text-decoration:none;font-family:' . $font . ';font-size:13px;">'
                        . Util::e((string) $link['label']) . '</a>';
                }
                return $wrap('<div style="text-align:' . $block['align'] . ';color:' . $block['color']
                    . ';font-family:' . $font . ';font-size:13px;">'
                    . implode(' <span style="color:#C9D2DD;">&middot;</span> ', $parts) . '</div>', $space);

            case 'html':
                return $wrap((string) $block['html'], $space);

            case 'content':
                return '{{inhalt}}' . "\n";
        }
        return '';
    }

    /** Gibt Links im Fließtext die Akzentfarbe, falls keine gesetzt ist. */
    private static function styleLinks(string $html, string $color): string
    {
        return preg_replace_callback(
            '#<a\b([^>]*)>#i',
            static function (array $m) use ($color): string {
                if (stripos($m[1], 'style=') !== false) {
                    return $m[0];
                }
                return '<a' . $m[1] . ' style="color:' . $color . ';">';
            },
            $html
        ) ?? $html;
    }

    /* ------------------------------------------------------------- Textteil */

    /** Reine Textfassung – für den Text-Teil der Mail. */
    public static function toText(array $data): string
    {
        $out = [];
        foreach ($data['blocks'] ?? [] as $block) {
            $out[] = self::blockToText($block);
        }
        $text = implode("\n\n", array_filter($out, static fn($t) => trim($t) !== ''));
        return trim(preg_replace('/\n{3,}/', "\n\n", $text) ?? $text);
    }

    private static function blockToText(array $block): string
    {
        switch ((string) $block['type']) {
            case 'heading':
                return (string) $block['text'];
            case 'text':
            case 'html':
                return Mailer::htmlToText((string) $block['html']);
            case 'image':
                return (string) $block['alt'] !== '' ? '[Bild: ' . $block['alt'] . ']' : '';
            case 'button':
                $href = (string) $block['href'];
                return $block['label'] . ($href !== '' ? ' (' . $href . ')' : '');
            case 'divider':
                return '––––––––––';
            case 'columns':
                $parts = [];
                foreach (array_merge((array) $block['left'], (array) $block['right']) as $child) {
                    $parts[] = self::blockToText($child);
                }
                return implode("\n\n", array_filter($parts));
            case 'social':
                $parts = [];
                foreach ((array) $block['links'] as $link) {
                    $parts[] = $link['label'] . ($link['href'] !== '' ? ': ' . $link['href'] : '');
                }
                return implode("\n", $parts);
            case 'content':
                return '{{inhalt}}';
        }
        return '';
    }

    /* -------------------------------------------------------- Hilfsfunktionen */

    /** Farbwert prüfen (#rgb, #rrggbb oder leer). */
    public static function color(string $value, string $fallback): string
    {
        $value = trim($value);
        if ($value === '') {
            return $fallback;
        }
        return preg_match('/^#[0-9a-f]{3}([0-9a-f]{3})?$/i', $value) ? $value : $fallback;
    }

    /** Nur bekannte Schriftfamilien zulassen. */
    private static function cleanFont(string $font): string
    {
        $erlaubt = [
            'Arial, Helvetica, sans-serif',
            "'Segoe UI', Tahoma, Arial, sans-serif",
            'Georgia, "Times New Roman", serif',
            '"Trebuchet MS", Arial, sans-serif',
            'Verdana, Geneva, sans-serif',
            '"Courier New", Courier, monospace',
        ];
        return in_array($font, $erlaubt, true) ? $font : $erlaubt[0];
    }

    /** @return string[] Für die Auswahl im Editor */
    public static function fonts(): array
    {
        return [
            'Arial, Helvetica, sans-serif'        => 'Arial (Standard)',
            "'Segoe UI', Tahoma, Arial, sans-serif" => 'Segoe UI',
            'Georgia, "Times New Roman", serif'   => 'Georgia (Serif)',
            '"Trebuchet MS", Arial, sans-serif'   => 'Trebuchet MS',
            'Verdana, Geneva, sans-serif'         => 'Verdana',
            '"Courier New", Courier, monospace'   => 'Courier',
        ];
    }

    /**
     * URL prüfen. Erlaubt sind http(s), mailto, tel und Platzhalter.
     * @param bool $imageOnly true = nur Bildquellen (auch data: verboten)
     */
    public static function cleanUrl(string $url, bool $imageOnly = false): string
    {
        $url = trim($url);
        if ($url === '') {
            return '';
        }
        if (str_contains($url, '{{')) {
            return mb_substr($url, 0, 500); // Platzhalter wie {{webansicht_url}}
        }
        if (!$imageOnly && preg_match('#^(mailto:|tel:)[^\s<>"]+$#i', $url)) {
            return mb_substr($url, 0, 500);
        }
        if (preg_match('#^https?://[^\s<>"]+$#i', $url)) {
            return mb_substr($url, 0, 500);
        }
        // Relative Pfade auf die eigene Seite absolut machen
        if (preg_match('#^/[^\s<>"]*$#', $url)) {
            $base = rtrim(Settings::get('website_url'), '/');
            return $base !== '' ? $base . $url : '';
        }
        return '';
    }

    /**
     * Säubert HTML aus dem Editor: erlaubte Tags und Attribute bleiben,
     * alles andere (Skripte, Ereignis-Attribute, fremde Protokolle) fliegt raus.
     *
     * @param bool $allowStructure true für den Baustein „Eigenes HTML“ –
     *        dort sind zusätzlich Tabellen und Bilder erlaubt.
     */
    public static function cleanHtml(string $html, bool $allowStructure = false): string
    {
        $html = trim($html);
        if ($html === '') {
            return '';
        }
        $html = mb_substr($html, 0, 200000);

        $allowed = ['p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'a', 'ul', 'ol', 'li',
                    'span', 'h1', 'h2', 'h3', 'h4', 'blockquote', 'small', 'sup', 'sub', 'div'];
        if ($allowStructure) {
            $allowed = array_merge($allowed, ['table', 'thead', 'tbody', 'tr', 'td', 'th', 'img', 'hr', 'center', 'font']);
        }

        if (!class_exists('DOMDocument')) {
            // Notlösung ohne DOM-Erweiterung
            $stripped = strip_tags($html, '<' . implode('><', $allowed) . '>');
            return preg_replace('/\son[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $stripped) ?? $stripped;
        }

        $doc = new DOMDocument('1.0', 'UTF-8');
        $prev = libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8"><div id="nl-root">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        $root = $doc->getElementById('nl-root');
        if ($root === null) {
            return '';
        }
        self::sanitizeNode($root, $allowed);

        $out = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $out .= $doc->saveHTML($child);
        }
        return trim($out);
    }

    /** Geht den Baum durch und entfernt alles Unerlaubte. */
    private static function sanitizeNode(DOMNode $node, array $allowed): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof DOMComment) {
                $child->parentNode?->removeChild($child);
                continue;
            }
            if (!($child instanceof DOMElement)) {
                continue; // Text bleibt
            }

            $tag = strtolower($child->nodeName);
            if (!in_array($tag, $allowed, true)) {
                // Verbotenes Element: Inhalt behalten, Hülle entfernen
                if (in_array($tag, ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input'], true)) {
                    $child->parentNode?->removeChild($child);
                    continue;
                }
                self::sanitizeNode($child, $allowed);
                while ($child->firstChild !== null) {
                    $child->parentNode?->insertBefore($child->firstChild, $child);
                }
                $child->parentNode?->removeChild($child);
                continue;
            }

            foreach (iterator_to_array($child->attributes ?? []) as $attr) {
                $name  = strtolower($attr->nodeName);
                $value = (string) $attr->nodeValue;

                $keep = match ($name) {
                    'href'   => $tag === 'a' && self::cleanUrl($value) !== '',
                    'src'    => $tag === 'img' && self::cleanUrl($value, true) !== '',
                    'alt', 'title', 'width', 'height', 'align', 'valign', 'border',
                    'cellpadding', 'cellspacing', 'bgcolor', 'target', 'rel', 'role' => true,
                    'style'  => true,
                    default  => false,
                };
                if (!$keep) {
                    $child->removeAttribute($attr->nodeName);
                    continue;
                }
                if ($name === 'href') {
                    $child->setAttribute('href', self::cleanUrl($value));
                } elseif ($name === 'src') {
                    $child->setAttribute('src', self::cleanUrl($value, true));
                } elseif ($name === 'style') {
                    $clean = self::cleanStyle($value);
                    if ($clean === '') {
                        $child->removeAttribute('style');
                    } else {
                        $child->setAttribute('style', $clean);
                    }
                }
            }
            self::sanitizeNode($child, $allowed);
        }
    }

    /** Nur harmlose CSS-Eigenschaften erlauben. */
    private static function cleanStyle(string $style): string
    {
        $erlaubt = ['color', 'background-color', 'font-size', 'font-weight', 'font-style', 'font-family',
                    'text-align', 'text-decoration', 'line-height', 'padding', 'padding-top', 'padding-bottom',
                    'padding-left', 'padding-right', 'margin', 'margin-top', 'margin-bottom', 'margin-left',
                    'margin-right', 'border', 'border-radius', 'border-top', 'border-bottom', 'width',
                    'max-width', 'height', 'display', 'vertical-align'];
        $out = [];
        foreach (explode(';', $style) as $teil) {
            if (!str_contains($teil, ':')) {
                continue;
            }
            [$name, $wert] = explode(':', $teil, 2);
            $name = strtolower(trim($name));
            $wert = trim($wert);
            if (!in_array($name, $erlaubt, true)) {
                continue;
            }
            if (preg_match('/(expression|javascript:|url\s*\(|@import|behavior)/i', $wert)) {
                continue;
            }
            $out[] = $name . ':' . mb_substr($wert, 0, 120);
        }
        return implode(';', $out);
    }
}
