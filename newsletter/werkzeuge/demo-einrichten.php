<?php
/**
 * demo-einrichten.php – eine Installation mit vorzeigbarem Bestand füllen.
 *
 * Gedacht für eine frische Installation, die jemandem gezeigt werden soll:
 * Marke, Listen, Empfänger, ein versendeter Newsletter mit Zahlen, ein
 * Entwurf, eine Willkommensstrecke und ein gesicherter Baustein. Danach ist
 * jede Seite der Verwaltung gefüllt, statt überall „noch nichts da“.
 *
 * Aufruf (nur auf der Kommandozeile):
 *
 *   php werkzeuge/demo-einrichten.php --url=https://kunde.de/newsletter \
 *        --marke="Golfclub Ottobeuren" --vorlage=golfclub-ottobeuren \
 *        --admin=chef@kunde.de:EinLangesPasswort
 *
 * Gibt es noch keine config.php, legt das Werkzeug eine mit SQLite an
 * (--sqlite=data/newsletter.sqlite). Für MySQL richten Sie die Installation
 * wie gewohnt über install.php ein und rufen dieses Werkzeug danach auf.
 *
 * Sicherheitshalber: läuft nur über die Kommandozeile, und in eine
 * Installation, in der schon Empfänger stehen, nur mit --auch-wenn-belegt.
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Dieses Werkzeug läuft nur auf der Kommandozeile.\n");
}

define('NL_ROOT', dirname(__DIR__));
define('NL_INSTALLER', true);

/* ------------------------------------------------------------ Argumente */

$args = [];
foreach (array_slice($argv, 1) as $roh) {
    if (preg_match('/^--([a-z0-9\-]+)(?:=(.*))?$/i', $roh, $t)) {
        $args[strtolower($t[1])] = $t[2] ?? '1';
    }
}

$url      = (string) ($args['url'] ?? 'http://localhost/newsletter');
$markeArg = (string) ($args['marke'] ?? 'Golfclub Ottobeuren');
$datei    = (string) ($args['vorlage'] ?? 'golfclub-ottobeuren');
$sqlite   = (string) ($args['sqlite'] ?? 'data/newsletter.sqlite');
$adminArg = (string) ($args['admin'] ?? '');
$egal     = isset($args['auch-wenn-belegt']);

require NL_ROOT . '/lib/Util.php';
require NL_ROOT . '/lib/Config.php';

/* Ohne config.php: eine mit SQLite anlegen. */
if (!Config::load()) {
    $pfad = str_starts_with($sqlite, '/') ? $sqlite : NL_ROOT . '/' . ltrim($sqlite, '/');
    @mkdir(dirname($pfad), 0775, true);
    Config::write([
        'base_url'   => rtrim($url, '/'),
        'secret'     => bin2hex(random_bytes(32)),
        'cron_token' => bin2hex(random_bytes(12)),
        'db'         => ['driver' => 'sqlite', 'path' => $pfad],
    ]);
    echo "config.php angelegt (SQLite: $pfad)\n";
}

require NL_ROOT . '/lib/bootstrap.php';

Schema::migrate();

if (!$egal && count(Subscribers::activeForList(null)) > 0) {
    fwrite(STDERR, "Hier stehen schon Empfänger. Mit --auch-wenn-belegt trotzdem füllen.\n");
    exit(1);
}

echo "== Grundangaben ==\n";

$website = (string) ($args['website'] ?? 'https://www.golfclub-ottobeuren.de');
$absender = (string) ($args['absender'] ?? 'newsletter@golfclub-ottobeuren.test');

Settings::setMany([
    'brand_name'    => $markeArg,
    'sender_name'   => $markeArg,
    'sender_email'  => $absender,
    'reply_to'      => $absender,
    'bounce_email'  => $absender,
    'contact_email' => $absender,
    'website_url'   => $website,
    'imprint'       => $markeArg . ' · Anschrift bitte ergänzen · Vertreten durch: bitte ergänzen',
    'imprint_url'   => rtrim($website, '/') . '/impressum',
    'privacy_url'   => rtrim($website, '/') . '/datenschutz',
    'transport'     => (string) ($args['versandweg'] ?? 'file'),
    'send_delay_ms' => '0',
    'hourly_limit'  => '500',
]);
echo "  Marke: $markeArg\n";

echo "== Zugang ==\n";
if ($adminArg !== '' && str_contains($adminArg, ':')) {
    [$mail, $pw] = explode(':', $adminArg, 2);
    try {
        Auth::createUser(trim($mail), $pw, 'Verwaltung', 'admin');
        echo "  Administrator angelegt: " . trim($mail) . "\n";
    } catch (Throwable $e) {
        // Gibt es den Zugang schon, ist das kein Grund abzubrechen.
        echo "  Zugang übersprungen: " . $e->getMessage() . "\n";
    }
} else {
    echo "  übersprungen (kein --admin=mail:passwort angegeben)\n";
}

echo "== Vorlagen und Marke ==\n";
Templates::ensureDefaults();
$vorlageId = Templates::brandTemplateId('datei:' . $datei);
if ($vorlageId === null) {
    fwrite(STDERR, "Die Vorlage '$datei' liegt nicht im Ordner vorlagen/.\n");
    exit(1);
}
Templates::saveBrand($vorlageId, [
    'brand_name'   => $markeArg,
    'website_url'  => $website,
    'imprint'      => $markeArg . ' · Anschrift bitte ergänzen · Vertreten durch: bitte ergänzen',
    'imprint_url'  => rtrim($website, '/') . '/impressum',
    'privacy_url'  => rtrim($website, '/') . '/datenschutz',
    'sender_name'  => $markeArg,
    'sender_email' => $absender,
]);
Templates::makeDefault($vorlageId);
echo "  Vorlage übernommen und als Standard gesetzt: " . Templates::byId($vorlageId)['name'] . "\n";

echo "== Listen ==\n";
$clubListe = Lists::ensureDefault('Clubnachrichten');
Lists::saveTemplate($clubListe, $vorlageId);
$turniere = Lists::create('Turniere & Wettspiele', 'Ausschreibungen, Startzeiten und Ergebnisse.');
Lists::saveTemplate($turniere, $vorlageId);
$gaeste   = Lists::create('Gäste & Schnupperkurse', 'Angebote für alle, die noch kein Mitglied sind.');
Lists::saveTemplate($gaeste, $vorlageId);
echo "  Clubnachrichten, Turniere & Wettspiele, Gäste & Schnupperkurse\n";

echo "== Empfänger ==\n";
$mitglieder = [
    ['anna.berger@example.test',      'Anna',      'Berger',      'Clubvorstand'],
    ['bernd.cordes@example.test',     'Bernd',     'Cordes',      ''],
    ['clara.dietz@example.test',      'Clara',     'Dietz',       ''],
    ['dirk.engel@example.test',       'Dirk',      'Engel',       'Platzpflege'],
    ['eva.forster@example.test',      'Eva',       'Forster',     ''],
    ['florian.gruber@example.test',   'Florian',   'Gruber',      ''],
    ['greta.hofmann@example.test',    'Greta',     'Hofmann',     'Jugendwart'],
    ['hannes.imhof@example.test',     'Hannes',    'Imhof',       ''],
    ['ines.jung@example.test',        'Ines',      'Jung',        ''],
    ['jonas.kellner@example.test',    'Jonas',     'Kellner',     ''],
    ['lena.mayr@example.test',        'Lena',      'Mayr',        'Sekretariat'],
    ['martin.nolte@example.test',     'Martin',    'Nolte',       ''],
];
// Geburtstage streuen – einer hat HEUTE, damit der Geburtsgruß beim Vorführen
// sofort greift; die übrigen über das Jahr verteilt.
$heuteMMDD = date('m-d');
$gebtage = [$heuteMMDD, '03-14', '06-22', '09-08', '11-30', '01-19', '07-04', '12-24',
            '02-11', '05-27', '08-16', '10-03'];
$zeilen = [];
foreach ($mitglieder as $i => $m) {
    $zeilen[] = ['email' => $m[0], 'first_name' => $m[1], 'last_name' => $m[2], 'company' => $m[3],
                 'birthday' => '1978-' . ($gebtage[$i] ?? '06-15')];
}
$import = Subscribers::import($zeilen, [$clubListe, $turniere], Subscribers::STATUS_ACTIVE, 'demo');
echo "  " . (int) $import['imported'] . " Mitglieder in Clubnachrichten und Turniere (mit Geburtstagen)\n";

/* Ein paar Gäste, davon einer noch unbestätigt – so ist auch dieser Fall zu sehen. */
Subscribers::import([
    ['email' => 'gast.probst@example.test', 'first_name' => 'Paula', 'last_name' => 'Probst'],
    ['email' => 'gast.reiter@example.test', 'first_name' => 'Rudi',  'last_name' => 'Reiter'],
], [$gaeste], Subscribers::STATUS_ACTIVE, 'demo');
Subscribers::signup('interessent.schmid@example.test', ['first_name' => 'Sabine', 'last_name' => 'Schmid'],
    [$gaeste], 'anmeldeformular');
echo "  2 Gäste aktiv, 1 Anmeldung noch unbestätigt\n";

echo "== Newsletter ==\n";

/** Legt eine Ausgabe mit fertigem Inhalt an. */
$ausgabe = function (string $name, string $betreff, string $vorschau, array $bausteine, int $liste) use ($vorlageId): int {
    $id = Campaigns::create($name, $vorlageId, true);
    $stand = Blocks::parse((string) (Campaigns::byId($id)['blocks_json'] ?? ''));
    $stand['blocks'] = $bausteine;
    Campaigns::save($id, [
        'subject'     => $betreff,
        'preheader'   => $vorschau,
        'list_id'     => $liste,
        'editor_mode' => 'blocks',
        'blocks_json' => (string) json_encode($stand, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    ]);
    Campaigns::compile($id);
    return $id;
};

$gruen = '#2C6B45';

$saison = $ausgabe(
    'Saisonstart 2026',
    'Der Platz ist offen – Saisonstart am Samstag',
    'Startzeiten, Eröffnungsturnier und die Neuerungen auf Bahn 7',
    [
        Blocks::block('heading', ['text' => 'Die Saison ist eröffnet', 'size' => 26, 'color' => '#123726']),
        Blocks::block('text', ['html' => '<p>{{anrede}},</p><p>ab Samstag ist der Platz wieder auf '
            . 'Sommergrüns gespielt. Die Wintervorgaben entfallen, die Startzeiten buchen Sie wie '
            . 'gewohnt online.</p>']),
        Blocks::block('button', ['label' => 'Startzeit buchen', 'href' => '{{website_url}}', 'bg' => $gruen, 'radius' => 2]),
        Blocks::block('divider', []),
        Blocks::block('text', ['html' => '<p><strong>Neu auf Bahn 7:</strong> Der Bunker vor dem Grün wurde '
            . 'neu angelegt. Bitte harken Sie nach dem Schlag – danke!</p>']),
        Blocks::block('text', ['html' => '<p>Herzliche Grüße<br><strong>Ihr Team von {{marke}}</strong></p>']),
    ],
    $clubListe
);

$turnier = $ausgabe(
    'Ausschreibung Clubmeisterschaft',
    'Clubmeisterschaft: Anmeldung bis Freitag',
    'Zwei Runden, Brutto und Netto, alle Startzeiten auf einen Blick',
    [
        Blocks::block('heading', ['text' => 'Clubmeisterschaft 2026', 'size' => 26, 'color' => '#123726']),
        Blocks::block('text', ['html' => '<p>{{anrede}},</p><p>am Wochenende spielen wir die '
            . 'Clubmeisterschaft über zwei Runden – Brutto und Netto, alle Klassen.</p>'
            . '<ul><li>Samstag: erste Runde ab 8:30 Uhr</li><li>Sonntag: Finalrunde ab 9:00 Uhr</li>'
            . '<li>Meldeschluss: Freitag, 18:00 Uhr</li></ul>']),
        Blocks::block('button', ['label' => 'Zur Ausschreibung', 'href' => '{{website_url}}', 'bg' => $gruen, 'radius' => 2]),
        Blocks::block('text', ['html' => '<p>Sportliche Grüße<br><strong>Ihr Spielausschuss</strong></p>']),
    ],
    $turniere
);

echo "  Entwurf: Ausschreibung Clubmeisterschaft\n";

/* Eine Ausgabe wirklich versenden, damit Auswertung und Protokoll etwas zeigen. */
Campaigns::start($saison);
$lauf = Queue::process(['limit' => 200, 'seconds' => 30]);
echo "  Versendet: Saisonstart 2026 (" . (int) $lauf['sent'] . " Mails über den Versandweg „"
    . Settings::get('transport') . "“)\n";

echo "== Automation ==\n";
$strecke = Automations::create('Willkommen im Club', $clubListe, $vorlageId);
Automations::saveFlow($strecke, (string) json_encode(Flow::starter(), JSON_UNESCAPED_UNICODE));
$schritte = Automations::steps($strecke);
if ($schritte !== []) {
    $ersterSchritt = (int) $schritte[0]['id'];
    Automations::saveStep($ersterSchritt, [
        'subject'     => 'Willkommen im ' . $markeArg,
        'editor_mode' => 'blocks',
        'blocks_json' => (string) json_encode([
            'meta'   => Blocks::parse((string) (Templates::byId($vorlageId)['blocks_json'] ?? ''))['meta'],
            'blocks' => [
                Blocks::block('heading', ['text' => 'Schön, dass Sie dabei sind', 'size' => 24, 'color' => '#123726']),
                Blocks::block('text', ['html' => '<p>{{anrede}},</p><p>ab jetzt bekommen Sie die '
                    . 'Clubnachrichten: Turniere, Platzpflege, Termine. Wenn Sie etwas beitragen '
                    . 'möchten, schreiben Sie uns einfach zurück.</p>']),
                Blocks::block('text', ['html' => '<p>Herzliche Grüße<br><strong>Ihr Team von {{marke}}</strong></p>']),
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    ]);
    Automations::compileStep($ersterSchritt);
}
Automations::save($strecke, ['status' => Automations::ACTIVE]);
echo "  Willkommen im Club (aktiv, 1 Stunde warten → Begrüßungsmail)\n";

// Kleiner Helfer: eine einstufige Strecke mit einer Mail bauen.
$meta = Blocks::parse((string) (Templates::byId($vorlageId)['blocks_json'] ?? ''))['meta'];
$eineMail = static function (int $autoId, string $betreff, array $bloecke) use ($meta): void {
    Automations::saveFlow($autoId, (string) json_encode(['nodes' => [
        Flow::node('mail'),
    ]], JSON_UNESCAPED_UNICODE));
    $s = Automations::steps($autoId);
    if ($s !== []) {
        Automations::saveStep((int) $s[0]['id'], [
            'subject'     => $betreff,
            'editor_mode' => 'blocks',
            'blocks_json' => (string) json_encode(['meta' => $meta, 'blocks' => $bloecke],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);
        Automations::compileStep((int) $s[0]['id']);
    }
    Automations::save($autoId, ['status' => Automations::ACTIVE]);
};

// Geburtstagsgruß – startet am Geburtstag, verschickt sofort.
$geb = Automations::create('Geburtstagsgruß', $clubListe, $vorlageId, Automations::TRIGGER_BIRTHDAY);
$eineMail($geb, 'Alles Gute zum Geburtstag! ⛳', [
    Blocks::block('heading', ['text' => 'Herzlichen Glückwunsch, {{vorname}}!', 'size' => 24, 'color' => '#123726']),
    Blocks::block('text', ['html' => '<p>Der ganze ' . $markeArg . ' wünscht Ihnen alles Gute zum '
        . 'Geburtstag – Gesundheit, schöne Stunden und natürlich viele gute Runden auf dem Platz.</p>'
        . '<p>Als kleines Geschenk: ein Freigetränk im Clubhaus, gegen Vorzeigen dieser Mail.</p>']),
    Blocks::block('text', ['html' => '<p>Herzliche Grüße<br><strong>Ihr Team von {{marke}}</strong></p>']),
]);
echo "  Geburtstagsgruß (aktiv, Auslöser: am Geburtstag)\n";

// Rückholung inaktiver Mitglieder – wirtschaftlich der spannendste Auslöser.
$rueck = Automations::create('Wir vermissen dich', $clubListe, $vorlageId, Automations::TRIGGER_INACTIVE, 180);
$eineMail($rueck, 'Wir vermissen dich auf dem Platz', [
    Blocks::block('heading', ['text' => 'Lange nichts gehört, {{vorname}}', 'size' => 24, 'color' => '#123726']),
    Blocks::block('text', ['html' => '<p>Wir haben Sie eine Weile nicht mehr auf dem Platz gesehen – '
        . 'das wollen wir ändern. Kommen Sie vorbei: Der Platz ist in Topform, und im Clubhaus wartet '
        . 'ein Kaffee auf Sie.</p>']),
    Blocks::block('button', ['label' => 'Startzeit buchen', 'href' => 'https://www.golfclub-ottobeuren.de/startzeiten',
        'bg' => '#123726']),
]);
echo "  Wir vermissen dich (aktiv, Auslöser: inaktiv seit 180 Tagen)\n";

echo "== Bausteine ==\n";
Snippets::save('Grußformel', (string) json_encode([
    Blocks::block('text', ['html' => '<p>Herzliche Grüße<br><strong>Ihr Team von {{marke}}</strong></p>']),
], JSON_UNESCAPED_UNICODE), 'demo');
Snippets::save('Platzregel-Hinweis', (string) json_encode([
    Blocks::block('text', ['html' => '<p><strong>Bitte beachten:</strong> Wintergrüns bis auf Weiteres. '
        . 'Die aktuelle Platzregel hängt am Starterhäuschen aus.</p>']),
], JSON_UNESCAPED_UNICODE), 'demo');
echo "  Grußformel, Platzregel-Hinweis\n";

echo "== Wochennews (Redaktionspool) ==\n";
Settings::setMany([
    'wochen_platz'      => 'Sommergrüns, alle 18 Bahnen offen. Trolley erlaubt.',
    'wochen_oeffnung'   => "Sekretariat: Mo–Fr 9–17 Uhr\nPlatz: täglich ab 7 Uhr\nGastronomie: Mi–So ab 11 Uhr",
    'wochen_gruss'      => 'Sonnige Grüße und bis bald auf der Runde,' . "\n" . 'Ihr Team vom ' . $markeArg,
    // Wetter absichtlich leer: läuft nur, wenn der Kunde eigene Koordinaten
    // einträgt und sein Server ausgehende Verbindungen erlaubt.
]);
// Ein paar Themen über die kommenden Wochen – so zeigt „Generieren" sofort etwas.
$heute = new DateTimeImmutable('monday this week');
$tag   = static fn(int $plus): string => $heute->modify('+' . $plus . ' days')->format('Y-m-d');
Wochennews::add(['category' => 'turniere', 'title' => 'Captains Cup – 18-Loch-Zählspiel',
    'body' => "Das gesellige Highlight des Monats. Kanonenstart um 9 Uhr, danach Siegerehrung "
        . "mit Grillbuffet.\nStartgeld 25 €, für Mitglieder frei.",
    'item_date' => $tag(5), 'link_label' => 'Zur Startliste', 'link_url' => 'https://www.golfclub-ottobeuren.de/turniere',
    'created_by' => 'demo']);
Wochennews::add(['category' => 'veranstaltungen', 'title' => 'Weinabend im Clubhaus',
    'body' => 'Sechs Weine aus der Region, dazu passende Kleinigkeiten aus der Küche. Um Anmeldung wird gebeten.',
    'item_date' => $tag(3), 'created_by' => 'demo']);
Wochennews::add(['category' => 'training', 'title' => 'Kurzplatz-Training mit dem Pro',
    'body' => 'Chippen und Putten mit unserem PGA-Professional. Kleine Gruppe, große Wirkung.',
    'item_date' => $tag(1), 'date_until' => $tag(1), 'link_label' => 'Platz sichern',
    'link_url' => 'https://www.golfclub-ottobeuren.de/training', 'created_by' => 'demo']);
Wochennews::add(['category' => 'proshop', 'title' => '20 % auf alle Handschuhe',
    'body' => 'Nur diese Woche im Pro-Shop – solange der Vorrat reicht.', 'created_by' => 'demo']);
Wochennews::add(['category' => 'gastronomie', 'title' => 'Wochenkarte',
    'body' => "Diese Woche u. a.:\nKaiserschmarrn mit Apfelmus\nAllgäuer Kässpatzen\nWildragout mit Spätzle",
    'evergreen' => 1, 'created_by' => 'demo']);
Wochennews::add(['category' => 'news', 'title' => 'Neue Trolley-Garage eröffnet',
    'body' => 'Ab sofort stehen 40 zusätzliche, abschließbare Trolley-Plätze bereit. '
        . 'Anmeldung im Sekretariat.', 'item_date' => $tag(0), 'created_by' => 'demo']);
echo "  6 Themen im Pool, Dauerinfos gesetzt (Öffnungszeiten, Platzstatus)\n";

echo "== Turnier-Kommunikation ==\n";
// Zwei weitere Turniere weiter in der Zukunft, damit die Terminvorschau der
// Serie gleich mehrere kommende Touchpoints zeigt.
Wochennews::add(['category' => 'turniere', 'title' => 'Clubmeisterschaft 2026',
    'body' => 'Das Saison-Highlight über zwei Runden. Anmeldung im Sekretariat.',
    'item_date' => $tag(18), 'link_label' => 'Zur Ausschreibung',
    'link_url' => 'https://www.golfclub-ottobeuren.de/clubmeisterschaft', 'created_by' => 'demo']);
Wochennews::add(['category' => 'turniere', 'title' => 'Herbst-Scramble',
    'body' => 'Lockeres Vierer-Scramble zum Saisonausklang, danach gemütliches Beisammensein.',
    'item_date' => $tag(32), 'link_label' => 'Anmelden',
    'link_url' => 'https://www.golfclub-ottobeuren.de/turniere', 'created_by' => 'demo']);
// Eine Serie im Entwurf-Modus (in einer Vorführung soll nichts von selbst
// hinausgehen) – aktiv, damit die Vorschau sofort etwas zeigt.
$turnierSerie = Turniere::createSeries('Turnier-Kommunikation', $clubListe, Turniere::MODE_DRAFT);
Turniere::saveSeries($turnierSerie, ['status' => Turniere::ACTIVE, 'template_id' => $vorlageId]);
echo "  Serie „Turnier-Kommunikation“ (aktiv, Entwurf zum Prüfen, 4 Touchpoints)\n";
echo "  3 Turniere mit Datum – die Serie bereitet daraus die Mails vor\n";

echo "== Schnittstelle ==\n";
// Ein Beispiel-Schlüssel, damit die Schnittstellen-Seite nicht leer ist.
// (Der Klartext wird bewusst nicht ausgegeben – für echte Nutzung legt man
//  einen eigenen an.)
ApiKeys::create('Beispiel: Mitglieder-Sync', ApiKeys::SCOPE_WRITE, 'demo');
echo "  Beispiel-API-Schlüssel angelegt (Lesen & Schreiben)\n";

echo "\nFertig. Fassung " . NL_VERSION . "\n";
echo "Anmeldung: " . rtrim(Config::get('base_url', ''), '/') . "/admin/login.php\n";
echo "cron_token für die Instanzen-Übersicht: " . Config::get('cron_token', '') . "\n";
