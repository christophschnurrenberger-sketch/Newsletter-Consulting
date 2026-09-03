<?php
/**
 * Turniere – die Turnier-Kommunikation.
 *
 * Der Gedanke: Ein Turnier steht ohnehin schon mit Datum im Redaktionspool
 * (Rubrik „Turniere"). Eine Turnier-Serie hängt sich an diese Rubrik und
 * verschickt rund um jeden Termin von selbst mehrere Mails – 14 Tage, 7 Tage
 * und 1 Tag vorher sowie eine Nachlese danach. Jeder dieser Touchpoints hat
 * einen Betreff und einen kurzen Text mit Platzhaltern ({{turnier}}, {{datum}}),
 * die beim Erzeugen mit dem konkreten Turnier gefüllt werden.
 *
 * Betriebsart je Serie:
 *   – „auto"  : Die Mail geht am Stichtag von selbst hinaus.
 *   – „draft" : Es entsteht ein Entwurf zum Prüfen; gesendet wird per Klick.
 *
 * Verschickt wird über den ganz normalen Kampagnen-Weg (Warteschlange,
 * Tracking, Vorschau) – eine Turnier-Mail ist also eine gewöhnliche Kampagne,
 * nur eben automatisch vorbereitet.
 */
final class Turniere
{
    public const MODE_AUTO  = 'auto';
    public const MODE_DRAFT = 'draft';

    public const ACTIVE = 'active';
    public const PAUSED = 'paused';

    /** Vorgabe-Rubrik im Redaktionspool. */
    public const CATEGORY = 'turniere';

    public static function modeLabel(string $mode): string
    {
        return $mode === self::MODE_AUTO ? 'Vollautomatisch senden' : 'Entwurf zum Prüfen';
    }

    /* --------------------------------------------------------- Serien führen */

    /** @return array<int,array<string,mixed>> */
    public static function allSeries(): array
    {
        return DB::all('SELECT * FROM event_series ORDER BY name ASC, id ASC');
    }

    public static function series(int $id): ?array
    {
        return DB::row('SELECT * FROM event_series WHERE id = ?', [$id]);
    }

    /**
     * Eine Serie anlegen – samt der vier üblichen Touchpoints, damit sie
     * sofort einsatzbereit ist.
     */
    public static function createSeries(string $name, ?int $listId = null, string $mode = self::MODE_DRAFT,
                                        string $category = self::CATEGORY, ?int $templateId = null): int
    {
        $now = Util::now();
        $id  = DB::insert('event_series', [
            'name'        => mb_substr(trim($name), 0, 190) ?: 'Turnier-Kommunikation',
            'category'    => isset(Wochennews::CATEGORIES[$category]) ? $category : self::CATEGORY,
            'list_id'     => $listId,
            'template_id' => $templateId !== null && $templateId > 0 ? $templateId : null,
            'mode'        => $mode === self::MODE_AUTO ? self::MODE_AUTO : self::MODE_DRAFT,
            'status'      => self::PAUSED,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);
        $sort = 0;
        foreach (self::defaultTouchpoints() as $tp) {
            DB::insert('event_touchpoints', $tp + [
                'series_id'  => $id,
                'active'     => 1,
                'sort'       => $sort++,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        return $id;
    }

    /** @param array<string,mixed> $data */
    public static function saveSeries(int $id, array $data): void
    {
        $update = [];
        foreach (['name', 'category', 'list_id', 'template_id', 'mode', 'status'] as $feld) {
            if (array_key_exists($feld, $data)) {
                $update[$feld] = $data[$feld];
            }
        }
        if (isset($update['category']) && !isset(Wochennews::CATEGORIES[$update['category']])) {
            $update['category'] = self::CATEGORY;
        }
        if (isset($update['mode']) && $update['mode'] !== self::MODE_AUTO) {
            $update['mode'] = self::MODE_DRAFT;
        }
        if (isset($update['status']) && $update['status'] !== self::ACTIVE) {
            $update['status'] = self::PAUSED;
        }
        if ($update === []) {
            return;
        }
        $update['updated_at'] = Util::now();
        DB::update('event_series', $update, 'id = ?', [$id]);
    }

    public static function deleteSeries(int $id): void
    {
        DB::transaction(static function () use ($id): void {
            DB::delete('event_touchpoints', 'series_id = ?', [$id]);
            DB::delete('event_mailings', 'series_id = ?', [$id]);
            DB::delete('event_series', 'id = ?', [$id]);
        });
    }

    /* ----------------------------------------------------- Touchpoints führen */

    /** @return array<int,array<string,mixed>> */
    public static function touchpoints(int $seriesId): array
    {
        return DB::all('SELECT * FROM event_touchpoints WHERE series_id = ? ORDER BY sort ASC, offset_days ASC, id ASC',
            [$seriesId]);
    }

    /** @param array<string,mixed> $data */
    public static function saveTouchpoint(int $id, array $data): void
    {
        $update = [];
        if (array_key_exists('offset_days', $data)) {
            // Sinnvolle Grenzen: ein Jahr vorher bis ein Jahr danach.
            $update['offset_days'] = max(-365, min(365, (int) $data['offset_days']));
        }
        if (array_key_exists('subject', $data)) {
            $update['subject'] = mb_substr(trim((string) $data['subject']), 0, 190);
        }
        if (array_key_exists('intro', $data)) {
            $update['intro'] = mb_substr(trim((string) $data['intro']), 0, 4000);
        }
        if (array_key_exists('active', $data)) {
            $update['active'] = (int) ((bool) $data['active']);
        }
        if ($update === []) {
            return;
        }
        $update['updated_at'] = Util::now();
        DB::update('event_touchpoints', $update, 'id = ?', [$id]);
    }

    /**
     * Die vier üblichen Touchpoints als Vorlage. Der Text ist bewusst
     * allgemein gehalten und lässt sich je Serie überschreiben.
     *
     * @return array<int,array{offset_days:int,subject:string,intro:string}>
     */
    public static function defaultTouchpoints(): array
    {
        return [
            ['offset_days' => -14,
             'subject' => 'Jetzt vormerken: {{turnier}}',
             'intro'   => "in zwei Wochen steht {{turnier}} an – am {{datum}}.\n\n"
                        . "Merken Sie sich den Termin schon einmal vor und melden Sie sich rechtzeitig an. "
                        . "Wir freuen uns auf Sie!"],
            ['offset_days' => -7,
             'subject' => 'Nur noch eine Woche bis {{turnier}}',
             'intro'   => "in genau einer Woche ist es so weit: {{turnier}} am {{datum}}.\n\n"
                        . "Haben Sie schon gemeldet? Sichern Sie sich Ihren Startplatz."],
            ['offset_days' => -1,
             'subject' => 'Morgen geht es los: {{turnier}}',
             'intro'   => "morgen startet {{turnier}}.\n\n"
                        . "Hier finden Sie noch einmal alle wichtigen Infos rund um den Tag. "
                        . "Wir wünschen Ihnen ein gutes Spiel!"],
            ['offset_days' => 1,
             'subject' => 'Danke fürs Mitspielen: {{turnier}}',
             'intro'   => "das war {{turnier}} – vielen Dank an alle, die dabei waren!\n\n"
                        . "Ergebnisse und Fotos finden Sie über den Link. Der nächste Termin lässt "
                        . "nicht lange auf sich warten – bleiben Sie dran."],
        ];
    }

    /** Kurzbeschreibung eines Abstands: „14 Tage vorher", „am Turniertag", „1 Tag danach". */
    public static function offsetLabel(int $offset): string
    {
        if ($offset === 0) {
            return 'am Turniertag';
        }
        $betrag = abs($offset);
        $tage   = $betrag === 1 ? '1 Tag' : $betrag . ' Tage';
        return $offset < 0 ? $tage . ' vorher' : $tage . ' danach';
    }

    /* ------------------------------------------------------------- Turniere */

    /**
     * Die Turniere aus dem Redaktionspool (Einträge der Rubrik mit Datum),
     * ab heute in die Zukunft geordnet.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function tournaments(string $category = self::CATEGORY, ?string $abDatum = null): array
    {
        $ab = $abDatum ?? substr(Util::now(), 0, 10);
        return DB::all(
            "SELECT * FROM content_items
             WHERE category = ? AND active = 1 AND item_date IS NOT NULL AND item_date <> '' AND item_date >= ?
             ORDER BY item_date ASC, id ASC",
            [$category, $ab]
        );
    }

    /* -------------------------------------------------------- Täglicher Lauf */

    /**
     * Prüft für alle aktiven Serien, ob heute ein Touchpoint eines Turniers
     * fällig ist, und bereitet die Mail vor (Entwurf oder Sofortversand).
     * Wird einmal täglich vom Wartungs-Cron gerufen.
     *
     * @return array{prepared:int,sent:int}
     */
    public static function runDaily(?string $heute = null): array
    {
        $heute  = $heute ?? Util::now();
        $tag    = substr($heute, 0, 10);
        $zahlen = ['prepared' => 0, 'sent' => 0];

        foreach (DB::all("SELECT * FROM event_series WHERE status = 'active'") as $serie) {
            $cat = (string) $serie['category'];
            foreach (self::touchpoints((int) $serie['id']) as $tp) {
                if ((int) $tp['active'] !== 1) {
                    continue;
                }
                // Fällig, wenn Turniertermin = heute − Abstand.
                $offset = (int) $tp['offset_days'];
                $ziel   = (new DateTimeImmutable($tag))->modify(sprintf('%+d days', -$offset))->format('Y-m-d');

                foreach (DB::all(
                    "SELECT * FROM content_items
                     WHERE category = ? AND active = 1 AND item_date = ?",
                    [$cat, $ziel]) as $turnier) {

                    if (self::bereitsVorbereitet((int) $serie['id'], (int) $tp['id'], (int) $turnier['id'])) {
                        continue;
                    }
                    self::vorbereiten($serie, $tp, $turnier, $tag, $zahlen);
                }
            }
        }

        if ($zahlen['prepared'] + $zahlen['sent'] > 0) {
            Log::info('turniere', sprintf('Turnier-Kommunikation: %d Entwurf/Entwürfe, %d sofort versendet.',
                $zahlen['prepared'], $zahlen['sent']));
        }
        return $zahlen;
    }

    private static function bereitsVorbereitet(int $serieId, int $tpId, int $itemId): bool
    {
        return (int) DB::value(
            'SELECT COUNT(*) FROM event_mailings WHERE series_id = ? AND touchpoint_id = ? AND item_id = ?',
            [$serieId, $tpId, $itemId]
        ) > 0;
    }

    /**
     * Eine einzelne Turnier-Mail vorbereiten und – bei „auto" – versenden.
     *
     * @param array<string,mixed> $serie
     * @param array<string,mixed> $tp
     * @param array<string,mixed> $turnier
     * @param array{prepared:int,sent:int} $zahlen
     */
    private static function vorbereiten(array $serie, array $tp, array $turnier, string $tag, array &$zahlen): void
    {
        $mode       = (string) $serie['mode'] === self::MODE_AUTO ? self::MODE_AUTO : self::MODE_DRAFT;
        $campaignId = self::buildCampaign($serie, $tp, $turnier);

        // Erst den Versand versuchen (nur bei auto), dann protokollieren – so
        // steht in der Merkliste, was wirklich passiert ist.
        $tatsaechlich = self::MODE_DRAFT;
        if ($mode === self::MODE_AUTO) {
            try {
                Campaigns::start($campaignId);
                $tatsaechlich = self::MODE_AUTO;
                $zahlen['sent']++;
            } catch (Throwable $e) {
                // Der Entwurf bleibt bestehen und kann von Hand gesendet werden.
                Log::warn('turniere', 'Turnier-Mail #' . $campaignId . ' konnte nicht automatisch '
                    . 'versendet werden, bleibt Entwurf: ' . $e->getMessage());
                $zahlen['prepared']++;
            }
        } else {
            $zahlen['prepared']++;
        }

        DB::insert('event_mailings', [
            'series_id'     => (int) $serie['id'],
            'touchpoint_id' => (int) $tp['id'],
            'item_id'       => (int) $turnier['id'],
            'campaign_id'   => $campaignId,
            'mode'          => $tatsaechlich,
            'planned_for'   => $tag,
            'created_at'    => Util::now(),
        ]);
    }

    /**
     * Baut aus Touchpoint und Turnier eine Kampagne (Entwurf) und gibt deren
     * Kennung zurück. Der Versand (oder das Belassen als Entwurf) passiert im
     * Aufrufer.
     *
     * @param array<string,mixed> $serie
     * @param array<string,mixed> $tp
     * @param array<string,mixed> $turnier
     */
    public static function buildCampaign(array $serie, array $tp, array $turnier): int
    {
        $templateId = $serie['template_id'] !== null ? (int) $serie['template_id'] : null;
        $template   = $templateId !== null ? Templates::byId($templateId) : Templates::defaultTemplate();
        $meta       = Blocks::metaFromTemplate($template);

        $betreff = self::fill((string) $tp['subject'], $turnier);
        if (trim($betreff) === '') {
            $betreff = (string) $turnier['title'];
        }
        $blocks = self::baueBloecke($tp, $turnier, $meta);
        $dok    = ['meta' => $meta, 'blocks' => $blocks];

        $name = self::fill((string) $serie['name'], $turnier) . ' – ' . (string) $turnier['title']
              . ' (' . self::offsetLabel((int) $tp['offset_days']) . ')';

        $id = Campaigns::create($name, $template !== null ? (int) $template['id'] : null, true);
        Campaigns::save($id, [
            'subject'     => mb_substr($betreff, 0, 190),
            'preheader'   => mb_substr(self::fill(self::ersterSatz((string) $tp['intro']), $turnier), 0, 150),
            'editor_mode' => 'blocks',
            'blocks_json' => (string) json_encode($dok, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'list_id'     => $serie['list_id'] !== null ? (int) $serie['list_id'] : (Lists::defaultId() ?: null),
        ]);
        return $id;
    }

    /**
     * Die Bausteine einer Turnier-Mail: Datumszeile, Turniertitel, der Text
     * des Touchpoints, optional das Bild und ein Knopf zum Link.
     *
     * @param array<string,mixed> $tp
     * @param array<string,mixed> $turnier
     * @param array<string,mixed> $meta
     * @return array<int,array<string,mixed>>
     */
    private static function baueBloecke(array $tp, array $turnier, array $meta): array
    {
        $akzent = (string) ($meta['linkColor'] ?? '#2C6B45');
        $grau   = (string) ($meta['textColor'] ?? '#4A5568');
        $offset = (int) $tp['offset_days'];
        $b = [];

        /* Kicker mit Datum */
        $b[] = Blocks::block('text', [
            'html'  => '<p style="letter-spacing:.04em;text-transform:uppercase;font-size:13px;">'
                     . Util::e(self::datumLang((string) $turnier['item_date'])) . '</p>',
            'color' => $akzent, 'size' => 13, 'space' => 4,
        ]);

        /* Turniertitel */
        $b[] = Blocks::block('heading', ['text' => (string) $turnier['title'], 'size' => 24, 'space' => 10]);

        /* Text des Touchpoints (mit „Hallo {{vorname}}," davor) */
        $anrede = '<p>Hallo {{vorname}},</p>';
        $text   = self::fill((string) $tp['intro'], $turnier);
        $b[] = Blocks::block('text', ['html' => $anrede . self::absatz($text), 'space' => 14]);

        /* Bild des Turniers, falls vorhanden */
        if (trim((string) ($turnier['image_url'] ?? '')) !== '') {
            $b[] = Blocks::block('image', [
                'src'   => (string) $turnier['image_url'],
                'alt'   => (string) $turnier['title'],
                'href'  => (string) ($turnier['link_url'] ?? ''),
                'width' => 100, 'space' => 14,
            ]);
        }

        /* Knopf zum hinterlegten Link */
        if (trim((string) ($turnier['link_url'] ?? '')) !== '') {
            $label = trim((string) ($turnier['link_label'] ?? ''));
            if ($label === '') {
                $label = $offset > 0 ? 'Ergebnisse & Fotos' : 'Mehr erfahren';
            }
            $b[] = Blocks::block('button', ['label' => $label, 'href' => (string) $turnier['link_url'],
                'bg' => $akzent, 'space' => 16]);
        } else {
            $b[] = Blocks::block('spacer', ['height' => 8]);
        }

        /* Kurzinfos aus dem Pooltext des Turniers (falls gepflegt) */
        if (trim((string) ($turnier['body'] ?? '')) !== '') {
            $b[] = Blocks::block('divider', ['space' => 8]);
            $b[] = Blocks::block('text', ['html' => self::absatz((string) $turnier['body']),
                'color' => $grau, 'size' => 14, 'space' => 8]);
        }

        return $b;
    }

    /* -------------------------------------------------------- Vorschau (UI) */

    /**
     * Was steht in nächster Zeit an? Je Turnier und Touchpoint der Stichtag
     * und ob schon vorbereitet. Nur für die Anzeige gedacht.
     *
     * @return array<int,array{turnier:string,item_date:string,touchpoint:string,
     *                          datum:string,offset:int,erledigt:bool,mode:string}>
     */
    public static function schedulePreview(array $serie, ?string $heute = null): array
    {
        $heute = substr($heute ?? Util::now(), 0, 10);
        $tps   = array_filter(self::touchpoints((int) $serie['id']),
            static fn(array $t): bool => (int) $t['active'] === 1);

        $zeilen = [];
        foreach (self::tournaments((string) $serie['category'], $heute) as $turnier) {
            foreach ($tps as $tp) {
                $stichtag = (new DateTimeImmutable((string) $turnier['item_date']))
                    ->modify(sprintf('%+d days', (int) $tp['offset_days']))->format('Y-m-d');
                if ($stichtag < $heute) {
                    continue; // in der Vergangenheit – nicht mehr anzeigen
                }
                $zeilen[] = [
                    'turnier'    => (string) $turnier['title'],
                    'item_date'  => (string) $turnier['item_date'],
                    'touchpoint' => self::offsetLabel((int) $tp['offset_days']),
                    'datum'      => $stichtag,
                    'offset'     => (int) $tp['offset_days'],
                    'erledigt'   => self::bereitsVorbereitet((int) $serie['id'], (int) $tp['id'], (int) $turnier['id']),
                    'mode'       => (string) $serie['mode'],
                ];
            }
        }
        usort($zeilen, static fn(array $a, array $b): int => strcmp($a['datum'], $b['datum']));
        return $zeilen;
    }

    /* --------------------------------------------------------- Textbausteine */

    /** Platzhalter des Turniers einsetzen. Andere ({{vorname}} …) bleiben stehen. */
    public static function fill(string $text, array $turnier): string
    {
        return strtr($text, [
            '{{turnier}}' => (string) ($turnier['title'] ?? ''),
            '{{datum}}'   => self::datumLang((string) ($turnier['item_date'] ?? '')),
        ]);
    }

    /** „Samstag, 13. September 2026" */
    public static function datumLang(string $ymd): string
    {
        $ymd = trim($ymd);
        if ($ymd === '') {
            return '';
        }
        $d = DateTimeImmutable::createFromFormat('Y-m-d', $ymd);
        if ($d === false) {
            return $ymd;
        }
        $tage   = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'];
        $monate = [1 => 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
                   'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
        return $tage[(int) $d->format('N') - 1] . ', ' . $d->format('j') . '. '
             . $monate[(int) $d->format('n')] . ' ' . $d->format('Y');
    }

    /** Der erste Satz eines Textes – für den Vorschautext (Preheader). */
    private static function ersterSatz(string $text): string
    {
        $text = trim(preg_replace('/\s+/', ' ', $text) ?? '');
        if ($text === '') {
            return '';
        }
        if (preg_match('/^(.*?[.!?])(\s|$)/u', $text, $t)) {
            return $t[1];
        }
        return $text;
    }

    /** Freitext in Absätze (Leerzeile) und Zeilen (Umbruch). */
    private static function absatz(string $text): string
    {
        $absaetze = preg_split('/\n\s*\n/', trim($text)) ?: [];
        $out = '';
        foreach ($absaetze as $abs) {
            $abs = trim($abs);
            if ($abs !== '') {
                $out .= '<p>' . nl2br(Util::e($abs)) . '</p>';
            }
        }
        return $out !== '' ? $out : '<p>' . nl2br(Util::e($text)) . '</p>';
    }
}
