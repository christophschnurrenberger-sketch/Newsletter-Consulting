<?php
/**
 * Wochennews – der Redaktionspool und der Generator für den Wochennewsletter.
 *
 * Der Gedanke dahinter ist das eigentliche Verkaufsargument: Ein Club trägt
 * Turniere, Veranstaltungen, Trainings und Angebote EINMAL mit Datum ein –
 * die ganze Saison auf einmal, wenn er mag. Beim Klick auf „Wochennewsletter
 * generieren" sammelt das System von selbst genau die Einträge der jeweiligen
 * Kalenderwoche, ergänzt Dauerinfos (Öffnungszeiten, Platzstatus) und optional
 * das Wetter, und baut daraus einen fertigen Entwurf.
 *
 * Der Mitarbeiter macht nur noch: Generieren → Prüfen → Senden. „Prüfen" und
 * „Senden" sind der bestehende Newsletter-Editor; hier entsteht der Entwurf.
 */
final class Wochennews
{
    /** Die Rubriken des Pools – die Reihenfolge ist zugleich die im Newsletter. */
    public const CATEGORIES = [
        'turniere'        => 'Turniere',
        'veranstaltungen' => 'Veranstaltungen',
        'platz'           => 'Platz & Course',
        'training'        => 'Training',
        'proshop'         => 'Pro-Shop',
        'gastronomie'     => 'Gastronomie',
        'news'            => 'Aus dem Club',
    ];

    /**
     * Für Tests: ersetzt den Wetter-Abruf.
     * Bekommt die URL und liefert ['status' => int, 'body' => string].
     *
     * @var callable|null
     */
    public static $wetterTransport = null;

    private const WETTER_ZEITLIMIT = 6;

    public static function categoryLabel(string $key): string
    {
        return self::CATEGORIES[$key] ?? $key;
    }

    /* ----------------------------------------------------------- Kalender */

    /**
     * Jahr und Kalenderwoche zu einem Zeitpunkt (ISO-8601, Woche beginnt Montag).
     *
     * @return array{0:int,1:int} [Jahr, KW]
     */
    public static function isoWeek(?int $zeit = null): array
    {
        $zeit ??= time();
        // "o" ist das ISO-Jahr – in der Neujahrswoche weicht es von "Y" ab.
        return [(int) date('o', $zeit), (int) date('W', $zeit)];
    }

    /**
     * Montag und Sonntag einer Kalenderwoche.
     *
     * @return array{von:string,bis:string} je Y-m-d
     */
    public static function weekRange(int $jahr, int $kw): array
    {
        $montag = new DateTimeImmutable();
        $montag = $montag->setISODate($jahr, $kw, 1)->setTime(0, 0);
        $sonntag = $montag->modify('+6 days');
        return ['von' => $montag->format('Y-m-d'), 'bis' => $sonntag->format('Y-m-d')];
    }

    /** „KW 37 · 8.–14. September 2026" */
    public static function weekLabel(int $jahr, int $kw): string
    {
        $spanne = self::weekRange($jahr, $kw);
        $von = new DateTimeImmutable($spanne['von']);
        $bis = new DateTimeImmutable($spanne['bis']);
        $monate = [1 => 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
                   'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];

        if ($von->format('m') === $bis->format('m')) {
            $spannenText = $von->format('j') . '.–' . $bis->format('j') . '. '
                . $monate[(int) $bis->format('n')] . ' ' . $bis->format('Y');
        } else {
            $spannenText = $von->format('j') . '. ' . $monate[(int) $von->format('n')]
                . ' – ' . $bis->format('j') . '. ' . $monate[(int) $bis->format('n')] . ' ' . $bis->format('Y');
        }
        return 'KW ' . $kw . ' · ' . $spannenText;
    }

    /* --------------------------------------------------------- Pool führen */

    /** @return array<int,array<string,mixed>> alle Einträge, nach Rubrik und Sortierung */
    public static function all(): array
    {
        // Nach der festgelegten Rubrikenreihenfolge, dann Sortierung, dann Datum
        return DB::all(
            "SELECT * FROM content_items
             ORDER BY CASE category " . self::rubrikOrdnung() . " ELSE 99 END,
                      sort ASC, COALESCE(item_date, '9999-99-99') ASC, id ASC"
        );
    }

    /**
     * Ein CASE-Fragment, das jede Rubrik auf ihre Position abbildet.
     *
     * Die Schlüssel stammen ausschließlich aus der Konstante CATEGORIES (nur
     * Kleinbuchstaben, von uns festgelegt) – hier kommt nie eine Eingabe hinein,
     * deshalb ist das feste Einsetzen unbedenklich.
     */
    private static function rubrikOrdnung(): string
    {
        $teile = [];
        $i = 0;
        foreach (array_keys(self::CATEGORIES) as $key) {
            $teile[] = "WHEN '" . $key . "' THEN " . $i++;
        }
        return implode(' ', $teile);
    }

    public static function byId(int $id): ?array
    {
        return DB::row('SELECT * FROM content_items WHERE id = ?', [$id]);
    }

    /**
     * Einen Eintrag anlegen. Ohne Datum gilt er als Dauerläufer (evergreen):
     * dann steht er in jeder Woche, bis man ihn entfernt.
     *
     * @param array<string,mixed> $d
     */
    public static function add(array $d): int
    {
        $rein = self::normalisiere($d);
        $rein['created_at'] = Util::now();
        $rein['updated_at'] = Util::now();
        return DB::insert('content_items', $rein);
    }

    /** @param array<string,mixed> $d */
    public static function update(int $id, array $d): void
    {
        $rein = self::normalisiere($d);
        $rein['updated_at'] = Util::now();
        DB::update('content_items', $rein, 'id = ?', [$id]);
    }

    public static function delete(int $id): void
    {
        DB::delete('content_items', 'id = ?', [$id]);
    }

    public static function setActive(int $id, bool $an): void
    {
        DB::update('content_items', ['active' => $an ? 1 : 0, 'updated_at' => Util::now()], 'id = ?', [$id]);
    }

    /** Rohdaten aus dem Formular in saubere Spalten. */
    private static function normalisiere(array $d): array
    {
        $cat  = (string) ($d['category'] ?? 'news');
        $von  = self::datum((string) ($d['item_date'] ?? ''));
        $bis  = self::datum((string) ($d['date_until'] ?? ''));
        // Kein Datum → Dauerläufer. Mit Datum entscheidet das Feld „evergreen"
        // nur, ob er zusätzlich auch außerhalb seiner Woche stehen soll.
        $ever = $von === null ? 1 : (int) ((bool) ($d['evergreen'] ?? 0));

        return [
            'category'   => isset(self::CATEGORIES[$cat]) ? $cat : 'news',
            'title'      => mb_substr(trim(strip_tags((string) ($d['title'] ?? ''))), 0, 190),
            'body'       => mb_substr(trim((string) ($d['body'] ?? '')), 0, 4000),
            'item_date'  => $von,
            'date_until' => $bis !== null && $von !== null && $bis >= $von ? $bis : null,
            'link_url'   => Blocks::cleanUrl((string) ($d['link_url'] ?? '')),
            'link_label' => mb_substr(trim(strip_tags((string) ($d['link_label'] ?? ''))), 0, 120),
            'image_url'  => Blocks::cleanUrl((string) ($d['image_url'] ?? ''), true),
            'evergreen'  => $ever,
            'sort'       => (int) ($d['sort'] ?? 0),
            'active'     => (int) ((bool) ($d['active'] ?? 1)),
            'created_by' => mb_substr((string) ($d['created_by'] ?? ''), 0, 190),
        ];
    }

    private static function datum(string $roh): ?string
    {
        $roh = trim($roh);
        if ($roh === '') {
            return null;
        }
        $d = DateTimeImmutable::createFromFormat('Y-m-d', $roh);
        return $d !== false && $d->format('Y-m-d') === $roh ? $roh : null;
    }

    /* ---------------------------------------------------- Woche einsammeln */

    /**
     * Die Einträge einer Kalenderwoche, gruppiert nach Rubrik.
     *
     * Dabei ist ein Eintrag Teil der Woche, wenn
     *   – er ein Dauerläufer ist (evergreen), oder
     *   – sein Datum (bzw. sein Zeitraum) in diese Woche fällt.
     *
     * @return array<string,array<int,array<string,mixed>>> nur nicht-leere Rubriken
     */
    public static function forWeek(int $jahr, int $kw): array
    {
        $spanne = self::weekRange($jahr, $kw);
        $rows = DB::all(
            "SELECT * FROM content_items
             WHERE active = 1 AND (
                   evergreen = 1
                OR (item_date IS NOT NULL AND date_until IS NULL AND item_date BETWEEN ? AND ?)
                OR (item_date IS NOT NULL AND date_until IS NOT NULL AND item_date <= ? AND date_until >= ?)
             )
             ORDER BY sort ASC, COALESCE(item_date, ?) ASC, id ASC",
            [$spanne['von'], $spanne['bis'], $spanne['bis'], $spanne['von'], $spanne['von']]
        );

        $gruppen = [];
        foreach (array_keys(self::CATEGORIES) as $key) {
            $gruppen[$key] = [];
        }
        foreach ($rows as $row) {
            $cat = (string) $row['category'];
            if (!isset($gruppen[$cat])) {
                $cat = 'news';
            }
            $gruppen[$cat][] = $row;
        }
        return array_filter($gruppen, static fn(array $v): bool => $v !== []);
    }

    /** Wie viele Einträge fielen in diese Woche? (für die Vorschau am Knopf) */
    public static function countForWeek(int $jahr, int $kw): int
    {
        $summe = 0;
        foreach (self::forWeek($jahr, $kw) as $eintraege) {
            $summe += count($eintraege);
        }
        return $summe;
    }

    /* ------------------------------------------------------- Generieren */

    /**
     * Baut aus der Woche einen Newsletter-Entwurf und gibt dessen Kennung zurück.
     *
     * @throws RuntimeException wenn die Woche vollständig leer ist
     */
    public static function generate(int $jahr, int $kw, ?int $templateId = null): int
    {
        $gruppen = self::forWeek($jahr, $kw);
        $hatWetter = self::wetter() !== null;
        if ($gruppen === [] && trim(Settings::get('wochen_oeffnung')) === ''
            && trim(Settings::get('wochen_platz')) === '' && !$hatWetter) {
            throw new RuntimeException('Für diese Woche gibt es noch keine Einträge. Bitte legen Sie '
                . 'oben ein paar Themen an – dann lässt sich der Newsletter zusammenstellen.');
        }

        $template = $templateId !== null ? Templates::byId($templateId) : Templates::defaultTemplate();
        $marke    = Templates::brand($template);
        $meta     = Blocks::metaFromTemplate($template);
        $blocks   = self::baueBloecke($jahr, $kw, $gruppen, $meta);

        $dok = ['meta' => $meta, 'blocks' => $blocks];

        $kw2   = str_pad((string) $kw, 2, '0', STR_PAD_LEFT);
        $name  = 'Wochennews KW ' . $kw2 . ' / ' . $jahr;
        $titel = trim((string) ($marke['brand_name'] ?? '')) !== ''
            ? $marke['brand_name'] . ' – Wochennews KW ' . $kw2
            : 'Wochennews KW ' . $kw2;

        $id = Campaigns::create($name, $template !== null ? (int) $template['id'] : null, true);
        Campaigns::save($id, [
            'subject'     => mb_substr($titel, 0, 190),
            'preheader'   => self::preheader($gruppen),
            'editor_mode' => 'blocks',
            'blocks_json' => (string) json_encode($dok, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'list_id'     => Lists::defaultId() ?: null,
        ]);
        Log::info('wochennews', 'Wochennewsletter erzeugt: KW ' . $kw2 . '/' . $jahr
            . ' (#' . $id . ', ' . self::countForWeek($jahr, $kw) . ' Einträge).');
        return $id;
    }

    /**
     * Die Bausteine des Newsletters – Einleitung, je Rubrik die Themen,
     * Dauerinfos, Gruß. Der Kopf und der Footer kommen aus der Vorlage.
     *
     * @param array<string,array<int,array<string,mixed>>> $gruppen
     * @param array<string,mixed>                          $meta
     * @return array<int,array<string,mixed>>
     */
    private static function baueBloecke(int $jahr, int $kw, array $gruppen, array $meta): array
    {
        $akzent = (string) ($meta['linkColor'] ?? '#2C6B45');
        $grau   = (string) ($meta['textColor'] ?? '#4A5568');
        $b = [];

        /* Einleitung ---------------------------------------------------- */
        $b[] = Blocks::block('text', [
            'html'  => '<p style="letter-spacing:.04em;text-transform:uppercase;font-size:13px;">'
                     . Util::e(self::weekLabel($jahr, $kw)) . '</p>',
            'color' => $akzent, 'size' => 13, 'space' => 4,
        ]);
        $b[] = Blocks::block('heading', ['text' => 'Das ist diese Woche los', 'size' => 24, 'space' => 8]);
        $b[] = Blocks::block('text', ['html' => self::absatz(self::intro($gruppen)), 'space' => 16]);

        /* Wetter (optional) -------------------------------------------- */
        $wetter = self::wetterBlockHtml();
        if ($wetter !== '') {
            $b[] = self::rubrikTitel('Wetter am Platz', $akzent);
            $b[] = Blocks::block('text', ['html' => $wetter, 'space' => 8, 'size' => 14]);
        }

        /* Die Rubriken ------------------------------------------------- */
        foreach ($gruppen as $key => $eintraege) {
            $b[] = self::rubrikTitel(self::categoryLabel($key), $akzent);
            foreach ($eintraege as $e) {
                $b = array_merge($b, self::eintragBloecke($e, $akzent, $grau));
            }
        }

        /* Gut zu wissen: Öffnungszeiten und Platzstatus ---------------- */
        $oeffnung = trim(Settings::get('wochen_oeffnung'));
        $platz    = trim(Settings::get('wochen_platz'));
        if ($oeffnung !== '' || $platz !== '') {
            $b[] = self::rubrikTitel('Gut zu wissen', $akzent);
            $text = '';
            if ($platz !== '')    { $text .= '<p><strong>Platz:</strong> ' . self::inline($platz) . '</p>'; }
            if ($oeffnung !== '') { $text .= '<p><strong>Öffnungszeiten:</strong><br>' . self::inline($oeffnung) . '</p>'; }
            $b[] = Blocks::block('text', ['html' => $text, 'space' => 12, 'size' => 14]);
        }

        /* Gruß --------------------------------------------------------- */
        $gruss = trim(Settings::get('wochen_gruss'));
        if ($gruss === '') {
            $gruss = 'Sonnige Grüße und bis bald auf der Runde';
        }
        $b[] = Blocks::block('divider', ['space' => 8]);
        $b[] = Blocks::block('text', ['html' => self::absatz($gruss), 'space' => 4, 'size' => 15]);

        return $b;
    }

    /** Eine Rubriküberschrift mit dünner Linie darüber. */
    private static function rubrikTitel(string $titel, string $akzent): array
    {
        return Blocks::block('heading', [
            'text' => $titel, 'size' => 19, 'color' => $akzent, 'space' => 10,
        ]);
    }

    /**
     * Die Bausteine eines einzelnen Themas: Titel (mit Datum), Text,
     * optional Bild und ein Knopf zum Weiterlesen.
     *
     * @param array<string,mixed> $e
     * @return array<int,array<string,mixed>>
     */
    private static function eintragBloecke(array $e, string $akzent, string $grau): array
    {
        $b = [];
        $titel = (string) $e['title'];
        $datum = self::datumsZusatz($e);
        if ($datum !== '') {
            $titel = $datum . ' · ' . $titel;
        }
        $b[] = Blocks::block('heading', ['text' => $titel, 'size' => 16, 'space' => 4]);

        if (trim((string) ($e['image_url'] ?? '')) !== '') {
            $b[] = Blocks::block('image', [
                'src' => (string) $e['image_url'],
                'alt' => (string) $e['title'],
                'href' => (string) ($e['link_url'] ?? ''),
                'width' => 100, 'space' => 8,
            ]);
        }
        if (trim((string) ($e['body'] ?? '')) !== '') {
            $b[] = Blocks::block('text', ['html' => self::absatz((string) $e['body']), 'color' => $grau, 'space' => 8]);
        }
        if (trim((string) ($e['link_url'] ?? '')) !== '') {
            $b[] = Blocks::block('button', [
                'label' => trim((string) ($e['link_label'] ?? '')) !== '' ? (string) $e['link_label'] : 'Mehr erfahren',
                'href'  => (string) $e['link_url'],
                'bg'    => $akzent, 'space' => 14,
            ]);
        } else {
            $b[] = Blocks::block('spacer', ['height' => 12]);
        }
        return $b;
    }

    /** „Sa 13.9." oder „13.–15.9." als kurzer Datumshinweis vor dem Titel. */
    private static function datumsZusatz(array $e): string
    {
        $von = (string) ($e['item_date'] ?? '');
        if ($von === '') {
            return '';
        }
        $tage = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];
        $v = new DateTimeImmutable($von);
        $vText = $tage[(int) $v->format('N') - 1] . ' ' . $v->format('j.n.');

        $bis = (string) ($e['date_until'] ?? '');
        if ($bis !== '' && $bis !== $von) {
            $bd = new DateTimeImmutable($bis);
            return $v->format('j.n.') . '–' . $bd->format('j.n.');
        }
        return $vText;
    }

    /* ------------------------------------------------------- Einleitung */

    /**
     * Der Einleitungstext: eigener Text, sonst optional vom Textassistenten,
     * sonst automatisch aus den Rubriken zusammengesetzt.
     *
     * @param array<string,array<int,array<string,mixed>>> $gruppen
     */
    private static function intro(array $gruppen): string
    {
        $eigen = trim(Settings::get('wochen_intro'));
        if ($eigen !== '') {
            return $eigen;
        }

        if (Settings::get('wochen_ki_intro') === '1' && Ai::available()) {
            $ki = self::kiIntro($gruppen);
            if ($ki !== '') {
                return $ki;
            }
        }

        $rubriken = array_map(fn(string $k): string => self::categoryLabel($k), array_keys($gruppen));
        if ($rubriken === []) {
            return 'Hier die wichtigsten Infos aus dem Club für diese Woche.';
        }
        if (count($rubriken) === 1) {
            return 'Diese Woche dreht sich alles um ' . $rubriken[0] . '. Alle Infos gibt es hier.';
        }
        $letzte = array_pop($rubriken);
        return 'Diese Woche warten ' . implode(', ', $rubriken) . ' und ' . $letzte
             . ' auf Sie. Hier der Überblick.';
    }

    /** Einleitung vom Textassistenten – scheitert leise, dann greift der Ersatz. */
    private static function kiIntro(array $gruppen): string
    {
        $themen = [];
        foreach ($gruppen as $eintraege) {
            foreach ($eintraege as $e) {
                $themen[] = '- ' . $e['title'];
            }
        }
        $brief = "Schreibe eine kurze, freundliche Einleitung (2 Sätze) für den Wochennewsletter "
               . "eines Golfclubs. Diese Themen kommen darin vor:\n" . implode("\n", array_slice($themen, 0, 20));
        try {
            return trim(strip_tags(Ai::suggest('schreiben', $brief)));
        } catch (Throwable $e) {
            Log::warn('wochennews', 'KI-Einleitung nicht möglich: ' . $e->getMessage());
            return '';
        }
    }

    /** Ein knapper Vorschautext (Preheader) aus den ersten Titeln. */
    private static function preheader(array $gruppen): string
    {
        $titel = [];
        foreach ($gruppen as $eintraege) {
            foreach ($eintraege as $e) {
                $titel[] = (string) $e['title'];
                if (count($titel) >= 3) { break 2; }
            }
        }
        return mb_substr(implode(' · ', $titel), 0, 150);
    }

    /* ----------------------------------------------------------- Wetter */

    /**
     * Die Wettervorhersage der Woche von open-meteo (ohne Schlüssel, kostenlos).
     * Ohne hinterlegten Ort oder bei blockiertem Netz gibt es schlicht kein
     * Wetter – der Newsletter entsteht trotzdem.
     *
     * @return array<int,array{tag:string,text:string,min:int,max:int}>|null
     */
    public static function wetter(): ?array
    {
        $lat = trim(Settings::get('wochen_wetter_lat'));
        $lon = trim(Settings::get('wochen_wetter_lon'));
        if ($lat === '' || $lon === '' || !is_numeric($lat) || !is_numeric($lon)) {
            return null;
        }
        $url = 'https://api.open-meteo.com/v1/forecast?latitude=' . rawurlencode($lat)
             . '&longitude=' . rawurlencode($lon)
             . '&daily=weathercode,temperature_2m_max,temperature_2m_min'
             . '&timezone=Europe%2FBerlin&forecast_days=7';

        try {
            [$code, $body] = self::hole($url);
        } catch (Throwable $e) {
            Log::warn('wochennews', 'Wetter nicht erreichbar: ' . $e->getMessage());
            return null;
        }
        if ($code !== 200) {
            return null;
        }
        $daten = json_decode($body, true);
        $tage  = $daten['daily']['time'] ?? null;
        if (!is_array($tage) || $tage === []) {
            return null;
        }

        $wochentage = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];
        $aus = [];
        foreach ($tage as $i => $tag) {
            $d = DateTimeImmutable::createFromFormat('Y-m-d', (string) $tag);
            if ($d === false) {
                continue;
            }
            $aus[] = [
                'tag'  => $wochentage[(int) $d->format('N') - 1],
                'text' => self::wetterText((int) ($daten['daily']['weathercode'][$i] ?? -1)),
                'max'  => (int) round((float) ($daten['daily']['temperature_2m_max'][$i] ?? 0)),
                'min'  => (int) round((float) ($daten['daily']['temperature_2m_min'][$i] ?? 0)),
            ];
        }
        return $aus === [] ? null : $aus;
    }

    /** Die Wettervorhersage als kleine HTML-Zeile für den Newsletter. */
    private static function wetterBlockHtml(): string
    {
        $tage = self::wetter();
        if ($tage === null) {
            return '';
        }
        $ort = trim(Settings::get('wochen_wetter_ort'));
        $zeilen = [];
        foreach ($tage as $t) {
            $zeilen[] = '<strong>' . Util::e($t['tag']) . '</strong> ' . Util::e($t['text'])
                . ' ' . $t['max'] . '°/' . $t['min'] . '°';
        }
        $kopf = $ort !== '' ? '<p style="margin:0 0 4px;">Vorhersage für ' . Util::e($ort) . ':</p>' : '';
        return $kopf . '<p style="line-height:1.9;">' . implode(' &nbsp;·&nbsp; ', $zeilen) . '</p>';
    }

    /** WMO-Wettercode → deutsches Wort. */
    private static function wetterText(int $code): string
    {
        return match (true) {
            $code === 0                    => 'klar',
            $code >= 1 && $code <= 2       => 'heiter',
            $code === 3                    => 'bewölkt',
            $code >= 45 && $code <= 48     => 'Nebel',
            $code >= 51 && $code <= 57     => 'Niesel',
            $code >= 61 && $code <= 67     => 'Regen',
            $code >= 71 && $code <= 77     => 'Schnee',
            $code >= 80 && $code <= 82     => 'Schauer',
            $code >= 85 && $code <= 86     => 'Schneeschauer',
            $code >= 95                    => 'Gewitter',
            default                        => '–',
        };
    }

    /**
     * Eine Adresse abrufen – wie in Instanzen, mit cURL, sonst über den Stream.
     *
     * @return array{0:int,1:string}
     */
    private static function hole(string $url): array
    {
        if (is_callable(self::$wetterTransport)) {
            $ersatz = (array) call_user_func(self::$wetterTransport, $url);
            return [(int) ($ersatz['status'] ?? 0), (string) ($ersatz['body'] ?? '')];
        }
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => self::WETTER_ZEITLIMIT,
                CURLOPT_CONNECTTIMEOUT => 4,
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
            'method' => 'GET', 'timeout' => self::WETTER_ZEITLIMIT, 'ignore_errors' => true,
        ]]);
        $roh  = (string) @file_get_contents($url, false, $kontext);
        $code = 0;
        foreach ($http_response_header ?? [] as $zeile) {
            if (preg_match('#^HTTP/\S+\s+(\d{3})#', $zeile, $t)) {
                $code = (int) $t[1];
            }
        }
        if ($code === 0) {
            throw new RuntimeException('keine Antwort');
        }
        return [$code, $roh];
    }

    /* --------------------------------------------------------- Textsatz */

    /** Freitext aus einem Textfeld in Absätze (Leerzeile) und Zeilen (Umbruch). */
    private static function absatz(string $text): string
    {
        $absaetze = preg_split('/\n\s*\n/', trim($text)) ?: [];
        $out = '';
        foreach ($absaetze as $abs) {
            $abs = trim($abs);
            if ($abs !== '') {
                $out .= '<p>' . self::inline($abs) . '</p>';
            }
        }
        return $out !== '' ? $out : '<p>' . self::inline($text) . '</p>';
    }

    /** Einzelne Zeilen sicher machen und Umbrüche erhalten. */
    private static function inline(string $text): string
    {
        return nl2br(Util::e(trim($text)));
    }
}
