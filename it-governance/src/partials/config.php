<?php
/**
 * Zentrale Angaben der Website.
 *
 * Hier steht alles einmal, was auf vielen Seiten auftaucht: Name, Anschrift,
 * Telefonnummer – und vor allem der Navigationsbaum. Aus diesem Baum entstehen
 * Hauptmenü, Mega-Menüs, Brotkrumen, Randspalte, Footer und sitemap.xml.
 * Eine neue Unterseite trägt man deshalb an genau einer Stelle ein: hier.
 *
 * Der Markenname steht ebenfalls nur hier. Wird aus der persönlichen Beratung
 * später eine Boutique mit eigenem Namen, ist das eine Zeile Arbeit.
 */

/* --------------------------------------------------------------------------
 * 1. Grunddaten
 * ----------------------------------------------------------------------- */

$SITE = [
    'name'        => 'Schnurrenberger IT-Governance',
    'short'       => 'Schnurrenberger',
    'claim'       => 'IT-Governance für den Mittelstand',

    // BITTE ANPASSEN: echte Domain, ohne Schrägstrich am Ende.
    'domain'      => 'https://www.it-governance-mittelstand.de',

    /*
     * Basispfad, unter dem die Seite liegt. '/' heißt: direkt auf der Domain.
     * Liegt sie in einem Unterordner, hier z. B. '/it-governance/' eintragen.
     * Für die gebauten HTML-Dateien spielt das keine Rolle – tools/build.php
     * rechnet alle Verweise ohnehin in relative Pfade um.
     */
    'base'        => '/',

    'phone'       => '0175 2778902',
    'phone_link'  => '+491752778902',
    'email'       => 'kontakt@it-governance-mittelstand.de',
    'owner'       => 'Christoph Schnurrenberger',
    'role'        => 'Wirtschaftsinformatiker (M. Sc.), IT-Governance & IT-Prozesse',
    'street'      => 'Birkenstr. 10',
    'city'        => '87734 Benningen',
    'country'     => 'Deutschland',
];

/* --------------------------------------------------------------------------
 * 2. Navigationsbaum
 *
 * Jeder Eintrag: 'label', 'url', optional 'desc' (Zeile im Mega-Menü),
 * 'icon' und 'children'. 'feature' hebt einen Eintrag im Mega-Menü hervor.
 * ----------------------------------------------------------------------- */

$NAV = [
    'leistungen' => [
        'label' => 'Leistungen',
        'url'   => 'leistungen/',
        'intro' => [
            'title' => 'Abgegrenzte Beratungsleistungen statt Tagessatz-Bauchladen',
            'text'  => 'Jede Leistung hat einen festen Umfang, ein festes Ergebnis und einen Preis, der vorher feststeht. Sie wissen vor der Beauftragung, was am Ende auf dem Tisch liegt.',
            'link'  => ['Alle Leistungen im Überblick', 'leistungen/'],
        ],
        'children' => [
            [
                'label' => 'IT-Governance Quick Assessment',
                'url'   => 'leistungen/quick-assessment.php',
                'desc'  => 'Standortbestimmung in zwei Wochen',
                'icon'  => 'compass',
            ],
            [
                'label'   => 'IT-Governance Gap-Analyse',
                'url'     => 'leistungen/gap-analyse.php',
                'desc'    => 'Soll-Ist-Vergleich mit priorisierter Maßnahmenliste',
                'icon'    => 'search',
                'feature' => true,
            ],
            [
                'label' => 'Audit Readiness Assessment',
                'url'   => 'leistungen/audit-readiness.php',
                'desc'  => 'Vor der Prüfung wissen, was die Prüfung findet',
                'icon'  => 'clipboard-check',
            ],
            [
                'label' => 'IT-Prozess-Assessment',
                'url'   => 'leistungen/it-prozess-assessment.php',
                'desc'  => 'Reifegrad der IT-Prozesse, belastbar bewertet',
                'icon'  => 'git-branch',
            ],
            [
                'label' => 'IT Operating Model',
                'url'   => 'leistungen/it-operating-model.php',
                'desc'  => 'Wie die IT künftig aufgestellt und gesteuert wird',
                'icon'  => 'layers',
            ],
            [
                'label' => 'IT-Governance-Framework',
                'url'   => 'leistungen/governance-framework.php',
                'desc'  => 'Richtlinien, Gremien, Entscheidungswege',
                'icon'  => 'building',
            ],
            [
                'label' => 'Rollen & Verantwortlichkeiten',
                'url'   => 'leistungen/rollen-verantwortlichkeiten.php',
                'desc'  => 'RACI, das im Alltag hält',
                'icon'  => 'users',
            ],
            [
                'label' => 'IT-Kontrollframework',
                'url'   => 'leistungen/kontrollframework.php',
                'desc'  => 'Kontrollen, die Nachweise erzeugen',
                'icon'  => 'shield-check',
            ],
            [
                'label' => 'IT Demand Management',
                'url'   => 'leistungen/demand-management.php',
                'desc'  => 'Anforderungen bewerten, priorisieren, entscheiden',
                'icon'  => 'inbox',
            ],
            [
                'label' => 'IT Service Management',
                'url'   => 'leistungen/service-management.php',
                'desc'  => 'Servicekatalog, Prozesse, Kennzahlen',
                'icon'  => 'server',
            ],
            [
                'label' => 'Laufende Governance-Betreuung',
                'url'   => 'leistungen/governance-betreuung.php',
                'desc'  => 'Fester Tag im Monat statt Projektstau',
                'icon'  => 'repeat',
            ],
        ],
    ],

    'themen' => [
        'label' => 'Themen',
        'url'   => 'themen/',
        'intro' => [
            'title' => 'Worum es fachlich geht',
            'text'  => 'Regulatorische Anforderungen, Kontrollen, Dokumentation, Kennzahlen – erklärt für Menschen, die IT verantworten, aber keine Auditoren sind.',
            'link'  => ['Alle Themen ansehen', 'themen/'],
        ],
        'children' => [
            [
                'label' => 'NIS2',
                'url'   => 'themen/nis2.php',
                'desc'  => 'Betroffenheit, Pflichten, Leitungsverantwortung',
                'icon'  => 'shield-check',
            ],
            [
                'label' => 'DORA',
                'url'   => 'themen/dora.php',
                'desc'  => 'Finanzsektor und seine IT-Dienstleister',
                'icon'  => 'euro',
            ],
            [
                'label' => 'ISO/IEC 27001',
                'url'   => 'themen/iso-27001.php',
                'desc'  => 'Zertifizierung – und was davor kommt',
                'icon'  => 'award',
            ],
            [
                'label' => 'IT-Risikomanagement',
                'url'   => 'themen/it-risikomanagement.php',
                'desc'  => 'Risiken, die im Vorstand ankommen',
                'icon'  => 'alert-triangle',
            ],
            [
                'label' => 'IT-Notfallmanagement',
                'url'   => 'themen/it-notfallmanagement.php',
                'desc'  => 'Wiederanlauf, Notfallhandbuch, Test',
                'icon'  => 'life-buoy',
            ],
            [
                'label' => 'IT-Dienstleistermanagement',
                'url'   => 'themen/dienstleistermanagement.php',
                'desc'  => 'Auslagerung bleibt Ihre Verantwortung',
                'icon'  => 'link',
            ],
            [
                'label' => 'IT-Dokumentation',
                'url'   => 'themen/it-dokumentation.php',
                'desc'  => 'Was gepflegt werden muss – und was nicht',
                'icon'  => 'file-text',
            ],
            [
                'label' => 'IT-Kennzahlen & Reporting',
                'url'   => 'themen/it-kennzahlen.php',
                'desc'  => 'Steuerung statt Bauchgefühl',
                'icon'  => 'line-chart',
            ],
            [
                'label' => 'Asset- & Applikationsmanagement',
                'url'   => 'themen/asset-applikationsmanagement.php',
                'desc'  => 'Ohne Inventar keine Kontrolle',
                'icon'  => 'database',
            ],
            [
                'label' => 'Prozessharmonisierung',
                'url'   => 'themen/prozessharmonisierung.php',
                'desc'  => 'Mehrere Standorte, ein Prozess',
                'icon'  => 'globe',
            ],
        ],
    ],

    'vorgehen' => [
        'label' => 'Vorgehen',
        'url'   => 'vorgehen.php',
    ],

    'wissen' => [
        'label' => 'Wissen',
        'url'   => 'wissen/',
        'intro' => [
            'title' => 'Ausführlich, kostenlos, ohne Anmeldung',
            'text'  => 'Leitfäden, Checklisten und Modelle aus der Projektarbeit. Wer sie selbst umsetzt, braucht mich nicht – das ist so gemeint.',
            'link'  => ['Alle Beiträge', 'wissen/'],
        ],
        'children' => [
            [
                'label' => 'IT-Governance im Mittelstand',
                'url'   => 'wissen/it-governance-mittelstand.php',
                'desc'  => 'Was der Begriff konkret bedeutet',
                'icon'  => 'book-open',
            ],
            [
                'label' => 'NIS2-Betroffenheit prüfen',
                'url'   => 'wissen/nis2-betroffenheit.php',
                'desc'  => 'In sechs Schritten zur belastbaren Einschätzung',
                'icon'  => 'help-circle',
            ],
            [
                'label' => 'Audit-Vorbereitung',
                'url'   => 'wissen/audit-vorbereitung.php',
                'desc'  => 'Die 12 Wochen vor der Prüfung',
                'icon'  => 'calendar-check',
            ],
            [
                'label' => 'Reifegradmodell zur Selbsteinschätzung',
                'url'   => 'wissen/reifegradmodell.php',
                'desc'  => '12 Fragen, fünf Stufen, ehrliches Ergebnis',
                'icon'  => 'bar-chart',
            ],
            [
                'label' => 'Von der Kontrolle zum Nachweis',
                'url'   => 'wissen/kontrollen-nachweise.php',
                'desc'  => 'Warum Prüfer Belege sehen wollen, nicht Absichten',
                'icon'  => 'check-circle',
            ],
            [
                'label' => 'Die IT-Dokumentenlandkarte',
                'url'   => 'wissen/dokumentenlandkarte.php',
                'desc'  => 'Welche Dokumente eine IT wirklich braucht',
                'icon'  => 'map',
            ],
            [
                'label' => 'Häufige Fragen',
                'url'   => 'faq.php',
                'desc'  => 'Ablauf, Kosten, Abgrenzung, Vertraulichkeit',
                'icon'  => 'message-circle',
            ],
        ],
    ],

    'preise' => [
        'label' => 'Preise',
        'url'   => 'preise.php',
    ],

    'ueber-mich' => [
        'label' => 'Über mich',
        'url'   => 'ueber-mich.php',
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
 * Liefert alle Seiten des Navigationsbaums als flache Liste – gebraucht für
 * tools/build.php und den Footer.
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

/**
 * Findet einen Eintrag im Navigationsbaum anhand seines Pfades. Damit lassen
 * sich Verweiskacheln („Das könnte auch passen“) aus dem Baum füllen, statt
 * Beschriftung und Beschreibung auf jeder Seite erneut zu tippen.
 */
function nav_find(string $path): ?array
{
    global $NAV;
    foreach ($NAV as $key => $item) {
        if ($item['url'] === $path) {
            return $item + ['section' => $key];
        }
        foreach ($item['children'] ?? [] as $child) {
            if ($child['url'] === $path) {
                return $child + ['section' => $key];
            }
        }
    }
    return null;
}
