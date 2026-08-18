<?php
/**
 * Zentrale Angaben der Website.
 *
 * Hier steht alles einmal, was auf vielen Seiten auftaucht: Anschrift,
 * Telefonnummer – und vor allem der Navigationsbaum. Aus diesem Baum entstehen
 * das Hauptmenü, die Mega-Menüs, die Brotkrumen, der Footer und sitemap.xml.
 * Eine neue Unterseite trägt man deshalb genau an einer Stelle ein: hier.
 */

/* --------------------------------------------------------------------------
 * 1. Grunddaten
 * ----------------------------------------------------------------------- */

$SITE = [
    'name'        => 'AcumenMail Golf',
    'claim'       => 'Newsletter für Golfclubs',

    // BITTE ANPASSEN: echte Domain, ohne Schrägstrich am Ende.
    'domain'      => 'https://www.golf-newsletter.de',

    /*
     * Basispfad, unter dem die Seite liegt. '/' heißt: direkt auf der Domain.
     * Liegt die Seite in einem Unterordner, hier z. B. '/golf/' eintragen –
     * alle Verweise richten sich danach.
     */
    'base'        => '/',

    'phone'       => '0175 2778902',
    'phone_link'  => '+491752778902',
    'email'       => 'info@newsletter-consulting.de',
    'owner'       => 'Christoph Schnurrenberger',
    'street'      => 'Birkenstr. 10',
    'city'        => '87734 Benningen',
];

/* --------------------------------------------------------------------------
 * 2. Navigationsbaum
 *
 * Jeder Eintrag: 'label', 'url', optional 'desc' (Zeile im Mega-Menü),
 * 'icon' und 'children'. 'feature' hebt einen Eintrag im Mega-Menü hervor.
 * ----------------------------------------------------------------------- */

$NAV = [
    'software' => [
        'label' => 'Software',
        'url'   => 'software/',
        'intro' => [
            'title' => 'Das Newsletter-System für Golfclubs',
            'text'  => 'Läuft auf dem Server Ihres Clubs. Keine Kosten pro Kontakt, keine Mitgliederdaten bei Dritten.',
            'link'  => ['Alle Funktionen im Überblick', 'software/'],
        ],
        'children' => [
            [
                'label' => 'Newsletter-Baukasten',
                'url'   => 'software/newsletter-baukasten.php',
                'desc'  => 'Bausteine ziehen statt HTML schreiben',
                'icon'  => 'layers',
            ],
            [
                'label' => 'Automationen',
                'url'   => 'software/automationen.php',
                'desc'  => 'Mailstrecken, die von allein laufen',
                'icon'  => 'git-branch',
            ],
            [
                'label' => 'Empfänger & Segmente',
                'url'   => 'software/empfaenger-segmente.php',
                'desc'  => 'Import, Listen, Handicap-Gruppen',
                'icon'  => 'users',
            ],
            [
                'label' => 'Auswertung',
                'url'   => 'software/auswertung.php',
                'desc'  => 'Öffnungen, Klicks, Bericht für den Vorstand',
                'icon'  => 'line-chart',
            ],
            [
                'label' => 'Zustellbarkeit & DSGVO',
                'url'   => 'software/zustellbarkeit-dsgvo.php',
                'desc'  => 'Double-Opt-in, SPF, DKIM, Bounces',
                'icon'  => 'shield-check',
            ],
            [
                'label' => 'Systemvoraussetzungen',
                'url'   => 'software/systemvoraussetzungen.php',
                'desc'  => 'Was Ihr Webspace mitbringen muss',
                'icon'  => 'server',
            ],
        ],
    ],

    'loesungen' => [
        'label' => 'Lösungen',
        'url'   => 'loesungen/',
        'intro' => [
            'title' => 'Wofür Clubs uns holen',
            'text'  => 'Fünf Aufgaben, die in fast jedem Golfclub auf dem Tisch liegen – und wie E-Mail sie löst.',
            'link'  => ['Alle Lösungen ansehen', 'loesungen/'],
        ],
        'children' => [
            [
                'label' => 'Mitgliederbindung',
                'url'   => 'loesungen/mitgliederbindung.php',
                'desc'  => 'Auch die erreichen, die selten da sind',
                'icon'  => 'users',
            ],
            [
                'label' => 'Turniere & Events',
                'url'   => 'loesungen/turniere-events.php',
                'desc'  => 'Startlisten füllen statt Aushang hoffen',
                'icon'  => 'trophy',
            ],
            [
                'label' => 'Gastspieler & Greenfee',
                'url'   => 'loesungen/gastspieler.php',
                'desc'  => 'Aus einer Runde eine zweite machen',
                'icon'  => 'flag',
            ],
            [
                'label' => 'Neumitglieder gewinnen',
                'url'   => 'loesungen/neumitglieder.php',
                'desc'  => 'Von der Anfrage bis zur Aufnahme',
                'icon'  => 'user-check',
            ],
            [
                'label' => 'Golfschule & Pro',
                'url'   => 'loesungen/golfschule.php',
                'desc'  => 'Kurse und Platzreife auslasten',
                'icon'  => 'target',
            ],
        ],
    ],

    'leistungen' => [
        'label' => 'Leistungen',
        'url'   => 'leistungen/',
        'intro' => [
            'title' => 'Wir richten ein, Sie versenden',
            'text'  => 'Drei klar umrissene Pakete – vom einmaligen Check bis zur laufenden Betreuung.',
            'link'  => ['Leistungen im Überblick', 'leistungen/'],
        ],
        'children' => [
            [
                'label'   => 'Clubcheck',
                'url'     => 'leistungen/clubcheck.php',
                'desc'    => 'Bestandsaufnahme mit klarer Empfehlung',
                'icon'    => 'search',
            ],
            [
                'label'   => 'Saison-Setup',
                'url'     => 'leistungen/saison-setup.php',
                'desc'    => 'Einrichtung bis zur ersten Ausgabe',
                'icon'    => 'zap',
                'feature' => true,
            ],
            [
                'label'   => 'Clubbetreuung',
                'url'     => 'leistungen/clubbetreuung.php',
                'desc'    => 'Wir schreiben und versenden für Sie',
                'icon'    => 'repeat',
            ],
        ],
    ],

    'preise' => [
        'label' => 'Preise',
        'url'   => 'preise.php',
    ],

    'wissen' => [
        'label' => 'Wissen',
        'url'   => 'wissen/',
        'intro' => [
            'title' => 'Aus der Praxis im Club',
            'text'  => 'Was in Golfclubs funktioniert und was nicht – ohne Marketing-Sprech.',
            'link'  => ['Alle Beiträge', 'wissen/'],
        ],
        'children' => [
            [
                'label' => 'Der Newsletter-Jahresplan',
                'url'   => 'wissen/newsletter-jahresplan-golfclub.php',
                'desc'  => '12 Monate Clubkommunikation im Überblick',
                'icon'  => 'calendar',
            ],
            [
                'label' => 'Betreffzeilen, die im Club wirken',
                'url'   => 'wissen/betreffzeilen-golfclub.php',
                'desc'  => 'Was geöffnet wird und was nicht',
                'icon'  => 'mail',
            ],
            [
                'label' => 'Mitgliederdaten rechtssicher nutzen',
                'url'   => 'wissen/dsgvo-mitgliederdaten-golfclub.php',
                'desc'  => 'Vereinsinfo, Werbung und die Grenze dazwischen',
                'icon'  => 'lock',
            ],
            [
                'label' => 'Häufige Fragen',
                'url'   => 'faq.php',
                'desc'  => 'Recht, Aufwand, PC CADDIE, Kosten',
                'icon'  => 'help-circle',
            ],
        ],
    ],

    'ueber-uns' => [
        'label' => 'Über uns',
        'url'   => 'ueber-uns.php',
    ],
];

/* --------------------------------------------------------------------------
 * 3. Hilfsfunktionen
 * ----------------------------------------------------------------------- */

/** Baut einen Verweis aus dem Basispfad und einem seitenrelativen Pfad. */
function url(string $path = ''): string
{
    global $SITE;
    return rtrim($SITE['base'], '/') . '/' . ltrim($path, '/');
}

/** Kürzel für die Ausgabe von Text, der aus Variablen kommt. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/**
 * Liefert alle Seiten als flache Liste – gebraucht für sitemap.xml und den
 * Footer. Jeder Eintrag: ['label' => …, 'url' => …, 'section' => …].
 */
function nav_flat(): array
{
    global $NAV;
    $out = [];
    foreach ($NAV as $key => $item) {
        $out[] = ['label' => $item['label'], 'url' => $item['url'], 'section' => $key];
        foreach ($item['children'] ?? [] as $child) {
            $out[] = ['label' => $child['label'], 'url' => $child['url'], 'section' => $key];
        }
    }
    return $out;
}
