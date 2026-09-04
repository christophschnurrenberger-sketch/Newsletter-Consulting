<?php
/**
 * Hilfe – der Inhalt für das Handbuch und die „?"-Popups.
 *
 * Alle Hilfetexte stehen an EINER Stelle. Jede Verwaltungsseite bekommt oben
 * automatisch ein „?", das den passenden Text als Popup zeigt (siehe admin.js
 * und admin/hilfe.php). Dieselben Texte bilden zusammen das Handbuch.
 *
 * Screenshots liegen unter admin/assets/hilfe/ und werden relativ eingebunden.
 */
final class Hilfe
{
    /**
     * Welche Verwaltungsseite zeigt welches Thema?
     * Schlüssel = Dateiname der Seite, Wert = Themen-Kennung.
     */
    private const SEITEN = [
        'index.php'        => 'uebersicht',
        'wochennews.php'   => 'wochennews',
        'kampagnen.php'    => 'newsletter',
        'kampagne.php'     => 'editor',
        'neu.php'          => 'newsletter',
        'automationen.php' => 'automationen',
        'turniere.php'     => 'turniere',
        'empfaenger.php'   => 'empfaenger',
        'empfaenger-detail.php' => 'empfaenger',
        'listen.php'       => 'listen',
        'marken.php'       => 'marken',
        'vorlagen.php'     => 'vorlagen',
        'systemmails.php'  => 'systemmails',
        'versand.php'      => 'versand',
        'statistik.php'    => 'versand',
        'protokoll.php'    => 'protokoll',
        'einstellungen.php'=> 'einstellungen',
        'api.php'          => 'schnittstelle',
        'benutzer.php'     => 'benutzer',
        'instanzen.php'    => 'instanzen',
        'import.php'       => 'empfaenger',
    ];

    /** Reihenfolge der Gruppen im Handbuch. */
    public const GRUPPEN = [
        'start'      => 'Erste Schritte',
        'versenden'  => 'Versenden',
        'empfaenger' => 'Empfänger',
        'gestaltung' => 'Gestaltung',
        'system'     => 'System',
    ];

    /** Themen-Kennung zur aktuellen Seite (oder '' wenn keine Hilfe). */
    public static function forPage(string $file): string
    {
        return self::SEITEN[$file] ?? '';
    }

    public static function has(string $id): bool
    {
        return isset(self::topics()[$id]);
    }

    public static function topic(string $id): ?array
    {
        return self::topics()[$id] ?? null;
    }

    /** @return array<string,array<string,mixed>> */
    public static function all(): array
    {
        return self::topics();
    }

    /* ------------------------------------------------------------ Inhalte */

    /** @return array<string,array{title:string,gruppe:string,kurz:string,html:string}> */
    private static function topics(): array
    {
        static $t = null;
        if ($t !== null) { return $t; }

        $t = [];

        $t['uebersicht'] = self::thema('Übersicht', 'start',
            'Ihr Startbildschirm mit Zahlen und den nächsten Schritten.',
            self::bild('dashboard.png', 'Die Übersicht mit Kennzahlen und Versandstatus')
            . self::p('Die Übersicht ist Ihr Cockpit. Oben sehen Sie auf einen Blick die wichtigsten Zahlen, '
                . 'darunter den Anmelde-Verlauf und den Versandstatus, ganz unten die letzten Newsletter.')
            . self::schritte([
                'Ein <strong>gelber Hinweis</strong> ganz oben zeigt, was vor dem ersten Versand noch fehlt '
                    . '(z. B. Absender oder Impressum). Er verschwindet, sobald alles gesetzt ist.',
                'Die <strong>Kacheln</strong> zeigen aktive Empfänger, offene Sendungen, heute Versendetes '
                    . 'und Abmeldungen/Bounces.',
                'Mit <strong>„Newsletter schreiben"</strong> oder <strong>„Empfänger hinzufügen"</strong> '
                    . 'oben rechts starten Sie sofort.',
            ])
            . self::tipp('Der „Letzte Cron-Lauf" zeigt, ob der automatische Versand im Hintergrund läuft. '
                . 'Steht dort nichts Aktuelles, ist der Cron-Job noch nicht eingerichtet (siehe Einstellungen → Technik).'));

        $t['newsletter'] = self::thema('Newsletter schreiben & senden', 'versenden',
            'Eine einzelne Ausgabe erstellen, prüfen und verschicken.',
            self::anim(['neu-1.png', 'neu-2.png', 'editor.png'], 'Von der Art über die Marke in den Baukasten')
            . self::p('Ein „Newsletter" ist eine einmalige Ausgabe an eine Liste – heute geschrieben, heute oder '
                . 'später verschickt.')
            . self::schritte([
                'Auf <strong>„Newsletter schreiben"</strong> klicken und als Art <strong>„Newsletter"</strong> wählen.',
                'Die <strong>Marke</strong> auswählen (bei nur einer Marke entfällt der Schritt).',
                'Im <strong>Baukasten</strong> Bausteine (Text, Bild, Knopf …) hineinziehen und füllen. '
                    . 'Der Baukasten sichert automatisch.',
                'Reiter <strong>„Angaben"</strong>: Betreff, Absender und die <strong>Liste</strong> festlegen.',
                'Reiter <strong>„Prüfen &amp; Senden"</strong>: eine <strong>Testmail</strong> an sich selbst schicken, '
                    . 'dann <strong>senden</strong> – sofort oder geplant.',
            ])
            . self::beispiel('Betreff „Der Platz ist offen – Saisonstart am Samstag", Liste „Clubnachrichten", '
                . 'ein Bild vom ersten Abschlag, zwei Sätze Text und ein Knopf „Startzeit buchen".')
            . self::tipp('Platzhalter wie <code>{{vorname}}</code> setzt das System beim Versand je Empfänger ein. '
                . 'Eine versendete Ausgabe lässt sich nicht mehr ändern – zum Weitermachen „Kopieren".'));

        $t['editor'] = self::thema('Der Baukasten', 'versenden',
            'Inhalte per Baustein zusammensetzen – ganz ohne HTML.',
            self::bild('editor.png', 'Der Baukasten: links die Bausteine, in der Mitte der Inhalt')
            . self::p('Im Baukasten setzen Sie die Mail aus Bausteinen zusammen. Links die Auswahl, in der Mitte '
                . 'Ihre Mail, rechts die Einstellungen des gewählten Bausteins.')
            . self::schritte([
                'Einen Baustein aus der linken Spalte in die Mitte <strong>ziehen</strong> (oder anklicken – '
                    . 'er hängt sich unten an).',
                'Baustein anklicken und rechts <strong>Text, Bild, Farbe, Link</strong> einstellen.',
                'Reihenfolge per <strong>Ziehen</strong> ändern; mit dem <strong>Papierkorb</strong> entfernen.',
                'Oben rechts zwischen <strong>Baukasten</strong> und <strong>HTML</strong> umschalten (für Fortgeschrittene).',
            ])
            . self::tipp('Häufig genutzte Bausteine lassen sich als <strong>gesicherter Baustein</strong> ablegen '
                . 'und in jeder Mail wiederverwenden – z. B. Ihre Grußformel.'));

        $t['wochennews'] = self::thema('Wochennews', 'versenden',
            'Themen einmal pflegen – den Wochennewsletter per Klick erzeugen.',
            self::bild('wochennews.png', 'Der Redaktionspool nach Rubriken')
            . self::p('Das ist das Verkaufsargument: Sie tragen Turniere, Veranstaltungen, Angebote usw. einmal mit '
                . 'Datum ein. Beim Klick auf „Wochennewsletter generieren" sammelt das System automatisch die Themen '
                . 'der gewählten Woche und baut daraus einen fertigen Entwurf.')
            . self::schritte([
                'Unter <strong>„Neues Thema"</strong> Einträge anlegen – mit Rubrik, Titel, Datum und optional Bild/Link.',
                'Oben die <strong>Woche</strong> wählen; der Knopf zeigt, wie viele Themen darin liegen.',
                '<strong>„Wochennewsletter generieren"</strong> → es entsteht ein Entwurf.',
                'Im Entwurf nur noch <strong>prüfen</strong> und <strong>senden</strong>.',
            ])
            . self::beispiel('Rubrik „Turniere": „Captains Cup" am Samstag. Rubrik „Gastronomie": Wochenkarte '
                . '(als Dauerläufer ohne Datum – steht dann jede Woche drin).')
            . self::tipp('Unter <strong>„Dauerinfos &amp; Wetter"</strong> hinterlegen Sie Öffnungszeiten und '
                . 'Platzstatus einmalig; mit Koordinaten kommt sogar die Wettervorhersage automatisch dazu.'));

        $t['automationen'] = self::thema('Automationen', 'versenden',
            'Mailstrecken, die von selbst laufen – Willkommen, Geburtstag, Rückholung.',
            self::bild('automationen.png', 'Der Ablauf-Editor einer Mailstrecke')
            . self::p('Eine Automation läuft von selbst, sobald ihr Auslöser eintritt. So begrüßen Sie neue '
                . 'Mitglieder, gratulieren zum Geburtstag oder holen Inaktive zurück – ohne dass jemand daran denken muss.')
            . self::schritte([
                '<strong>„Neue Strecke"</strong> anlegen und den <strong>Auslöser</strong> wählen: '
                    . 'nach der Anmeldung, am Geburtstag oder bei längerer Inaktivität.',
                'Im <strong>Ablauf</strong> Schritte hineinziehen: <em>warten</em>, <em>E-Mail senden</em>, '
                    . '<em>Bedingung</em>.',
                'Für jede Mail den Inhalt im gewohnten Baukasten schreiben.',
                'Status auf <strong>„Aktiv"</strong> stellen – fertig.',
            ])
            . self::beispiel('„Willkommen im Club": Auslöser = nach der Anmeldung, 1 Stunde warten, dann eine '
                . 'Begrüßungsmail mit Platzregeln, App und Ansprechpartnern.')
            . self::tipp('Für den Geburtstagsgruß braucht das Mitglied ein <strong>Geburtsdatum</strong> '
                . '(Empfänger-Detail oder per Import/Schnittstelle). Der tägliche Wartungslauf löst das automatisch aus.'));

        $t['turniere'] = self::thema('Turnier-Kommunikation', 'versenden',
            'Rund um jedes Turnier automatisch informieren.',
            self::bild('turniere.png', 'Eine Turnier-Serie mit ihren Touchpoints')
            . self::p('Eine Turnier-Serie greift die Turniere aus dem Redaktionspool auf und verschickt rund um '
                . 'jeden Termin ihre Touchpoints – z. B. 14 Tage, 7 Tage und 1 Tag vorher sowie eine Nachlese danach.')
            . self::schritte([
                'Eine <strong>Serie anlegen</strong> und die Betriebsart wählen: '
                    . '<strong>Entwurf zum Prüfen</strong> oder <strong>vollautomatisch senden</strong>.',
                'Die <strong>Touchpoints</strong> anpassen (Abstand in Tagen, Betreff, Text). '
                    . 'Platzhalter <code>{{turnier}}</code> und <code>{{datum}}</code> werden automatisch gefüllt.',
                'Serie auf <strong>„Aktiv"</strong> stellen.',
                'Turniere pflegen Sie unter <strong>Wochennews → Turniere</strong> (mit Datum).',
            ])
            . self::tipp('Die <strong>Terminvorschau</strong> auf der Serienseite zeigt, welche Mail als Nächstes '
                . 'zu welchem Turnier fällig ist. „Jetzt prüfen" bereitet fällige Mails sofort vor.'));

        $t['empfaenger'] = self::thema('Empfänger', 'empfaenger',
            'Anlegen, importieren, suchen und pflegen.',
            self::bild('empfaenger.png', 'Die Empfängerliste mit Filter und Sammelauswahl')
            . self::p('Hier verwalten Sie alle Adressen. Jeder Empfänger hat einen Status (aktiv, unbestätigt, '
                . 'abgemeldet …) und gehört zu einer oder mehreren Listen.')
            . self::schritte([
                '<strong>„Empfänger anlegen"</strong> für einzelne Adressen von Hand.',
                '<strong>„Import"</strong> für viele auf einmal per CSV (Spalten wie E-Mail, Vorname, Geburtstag).',
                'Über <strong>Suche</strong> und <strong>Filter</strong> gezielt finden.',
                'Zeilen <strong>ankreuzen</strong>, um mehrere gleichzeitig zu bearbeiten.',
                'Über <strong>„Details"</strong> Name, Firma, Geburtstag, Listen und Notizen pflegen.',
            ])
            . self::tipp('Adressen aus einer Clubverwaltung lassen sich auch automatisch über die '
                . '<strong>Schnittstelle</strong> einspielen – siehe Hilfe zur Schnittstelle.'));

        $t['listen'] = self::thema('Listen', 'empfaenger',
            'Empfänger in Verteiler bündeln.',
            self::bild('listen.png', 'Listen mit Name, Beschreibung und Marke')
            . self::p('Listen sind Ihre Verteiler – z. B. „Clubnachrichten", „Turniere" oder „Gäste". Ein Newsletter '
                . 'geht immer an eine Liste, und jeder Empfänger kann in mehreren Listen sein.')
            . self::schritte([
                'Unten <strong>„Neue Liste"</strong> anlegen (Name und optional Beschreibung).',
                'Einer Liste eine <strong>Marke</strong> zuordnen – danach richten sich Bestätigungs-, '
                    . 'Willkommens- und Abmeldemail dieser Liste.',
                'Eine Liste als <strong>Standard</strong> festlegen; sie ist bei neuen Anmeldungen vorbelegt.',
            ]));

        $t['marken'] = self::thema('Marken', 'gestaltung',
            'Mehrere Vereine/Absender in einer Installation.',
            self::bild('marken.png', 'Die Markenübersicht')
            . self::p('Eine Marke bündelt Absender, Impressum, Website und Aussehen. So können Sie in einer '
                . 'Installation mehrere Vereine oder Bereiche sauber getrennt betreuen.')
            . self::schritte([
                'Eine <strong>Marke anlegen</strong> und Name, Absenderadresse, Impressum und Website eintragen.',
                'Das <strong>Design</strong> (Kopf, Farben, Footer) festlegen.',
                'Listen und Automationen der jeweiligen Marke zuordnen.',
            ])
            . self::tipp('Betreuen Sie mehrere getrennte Kunden, sind ganze <strong>Instanzen</strong> '
                . '(eigene Datenbank je Kunde) meist die bessere Wahl – siehe Hilfe zu Instanzen.'));

        $t['vorlagen'] = self::thema('Vorlagen', 'gestaltung',
            'Das Grundgerüst (Kopf, Rahmen, Footer) Ihrer Mails.',
            self::bild('vorlagen.png', 'Vorlagen bearbeiten')
            . self::p('Eine Vorlage ist der feste Rahmen um Ihren Inhalt – Kopfzeile, Farben, Footer mit '
                . 'Pflichtangaben und Abmeldelink. Der Baukasten füllt nur den Inhaltsbereich.')
            . self::schritte([
                'Eine Vorlage <strong>auswählen</strong> oder neu anlegen.',
                'Kopf, Farben und Footer anpassen; der <strong>Abmeldelink</strong> <code>{{abmelden_url}}</code> '
                    . 'muss enthalten sein (gesetzlich vorgeschrieben).',
                'Als <strong>Standard</strong> setzen, damit neue Newsletter sie verwenden.',
            ]));

        $t['systemmails'] = self::thema('Systemmails', 'gestaltung',
            'Bestätigung, Begrüßung und Abmeldung.',
            self::bild('systemmails.png', 'Die Systemmails je Marke')
            . self::p('Das sind die automatischen Mails rund um die Anmeldung: die Bestätigungsmail (Double-Opt-in), '
                . 'die Begrüßung und die Abmeldebestätigung. Sie lassen sich je Marke gestalten.')
            . self::schritte([
                'Die Marke oben auswählen.',
                'Betreff und Text der drei Mails anpassen (die allgemeinen Vorgaben stehen in den Einstellungen).',
                'Mit einer Testanmeldung prüfen.',
            ]));

        $t['versand'] = self::thema('Versand & Auswertung', 'versenden',
            'Warteschlange, Fortschritt und Zahlen zu Öffnungen/Klicks.',
            self::bild('versand.png', 'Der Versandmonitor')
            . self::p('Hier sehen Sie, was gerade in der Warteschlange steht und wie ein laufender Versand '
                . 'vorankommt. Nach dem Versand liefert die Auswertung Öffnungs- und Klickraten.')
            . self::schritte([
                'Einen Versand starten Sie im Newsletter unter <strong>„Prüfen &amp; Senden"</strong>.',
                'Der <strong>Versandmonitor</strong> zeigt Fortschritt, Freigegebenes und Fehler.',
                'Über <strong>„Auswertung"</strong> je Newsletter sehen Sie Öffnungen, Klicks und die beliebtesten Links.',
            ])
            . self::tipp('Der Versand läuft in Paketen über den Cron-Job im Hintergrund – so bleibt die '
                . 'Zustellbarkeit hoch und der Server ruhig.'));

        $t['einstellungen'] = self::thema('Einstellungen', 'system',
            'Absender, Versandweg, Tempo, Texte und Technik.',
            self::bild('einstellungen.png', 'Die Einstellungen als Reiter')
            . self::p('Die Grundeinstellungen der Installation, in Reiter gegliedert.')
            . self::schritte([
                '<strong>Absender:</strong> Markenname, Absenderadresse und die Pflichtangaben fürs Impressum.',
                '<strong>Versand:</strong> Versandweg (am besten SMTP mit eigenem Postfach) sowie Tempo und Messung.',
                '<strong>Systemmails:</strong> die allgemeinen Texte für Bestätigung/Begrüßung/Abmeldung.',
                '<strong>Technik:</strong> der Cron-Job-Aufruf, den Sie im Hosting einrichten (alle 5 Minuten).',
            ])
            . self::tipp('Solange der Versandweg auf „Testmodus" steht, landen Mails nur in data/outbox – ideal '
                . 'zum Ausprobieren, aber es wird nichts wirklich verschickt.'));

        $t['schnittstelle'] = self::thema('Schnittstelle (API)', 'system',
            'Daten von außen einspielen und abrufen.',
            self::bild('api.png', 'Schlüssel-Verwaltung und Kurz-Anleitung')
            . self::p('Über die Schnittstelle können andere Programme (z. B. eine Clubverwaltung) Mitglieder und '
                . 'Turniere einspielen oder Daten abrufen.')
            . self::schritte([
                'Einen <strong>Schlüssel anlegen</strong> – „Nur Lesen" für Abfragen, „Lesen &amp; Schreiben" '
                    . 'für den Mitglieder-Sync. Den Schlüssel gleich <strong>kopieren</strong> (er wird nur einmal gezeigt).',
                'Das andere Programm schickt den Schlüssel im Kopf: <code>Authorization: Bearer …</code>.',
                'Die <strong>Endpunkte</strong> und ein fertiges Beispiel stehen auf der Seite selbst.',
            ])
            . self::tipp('Für PC CADDIE &amp; Co.: entweder ein kleines Skript überträgt den Mitglieder-Export '
                . 'per <code>/subscribers/bulk</code>, oder – sobald deren Zugang vorliegt – wird ein direkter '
                . 'Abruf ergänzt.'));

        $t['benutzer'] = self::thema('Benutzer & Zugänge', 'system',
            'Zugänge, Rollen und der zweite Faktor.',
            self::p('Hier verwalten Sie, wer Zugang hat und mit welchen Rechten. Es gibt Rollen vom vollen '
                . 'Administrator bis zum reinen Redakteur.')
            . self::schritte([
                'Einen <strong>Zugang anlegen</strong> und die Rolle wählen.',
                'Jede/r kann das eigene <strong>Passwort ändern</strong> und einen '
                    . '<strong>zweiten Faktor</strong> (App-Code) einschalten.',
                'Ein Passwortwechsel beendet automatisch alle anderen offenen Sitzungen.',
            ])
            . self::tipp('Weniger ist mehr: Geben Sie Redakteuren nur die Rechte, die sie brauchen.'));

        $t['instanzen'] = self::thema('Instanzen', 'system',
            'Mehrere getrennte Installationen im Blick.',
            self::p('Betreuen Sie mehrere Kunden, läuft für jeden eine eigene Installation mit eigener Datenbank. '
                . 'Auf der Instanzen-Seite sehen Sie sie nebeneinander – Empfängerzahl, Warteschlange, letzter Cron-Lauf.')
            . self::schritte([
                'Eine weitere Installation mit ihrer Adresse und ihrem <strong>cron_token</strong> eintragen.',
                'Die Übersicht fragt jede Instanz nur ab – geändert wird dort nichts.',
            ]));

        $t['protokoll'] = self::thema('Protokoll', 'system',
            'Was das System im Hintergrund getan hat.',
            self::p('Das technische Protokoll zeigt Versandläufe, Cron-Aufrufe, Fehler und wichtige Ereignisse – '
                . 'hilfreich, wenn etwas nicht wie erwartet läuft.')
            . self::tipp('Fehlerzeilen sind rot markiert. Ältere Einträge räumt der Wartungslauf automatisch weg.'));

        return $t;
    }

    /* ------------------------------------------------------------ Bausteine */

    private static function thema(string $titel, string $gruppe, string $kurz, string $html): array
    {
        return ['title' => $titel, 'gruppe' => $gruppe, 'kurz' => $kurz, 'html' => $html];
    }

    private static function p(string $text): string
    {
        return '<p>' . $text . '</p>';
    }

    /** @param string[] $schritte */
    private static function schritte(array $schritte): string
    {
        $li = '';
        foreach ($schritte as $s) { $li .= '<li>' . $s . '</li>'; }
        return '<ol class="hilfe-schritte">' . $li . '</ol>';
    }

    private static function beispiel(string $text): string
    {
        return '<div class="hilfe-beispiel"><span class="hilfe-etikett">Beispiel</span>' . $text . '</div>';
    }

    private static function tipp(string $text): string
    {
        return '<div class="hilfe-tipp"><span class="hilfe-etikett">Tipp</span>' . $text . '</div>';
    }

    private static function bild(string $datei, string $alt): string
    {
        return '<figure class="hilfe-bild"><img src="assets/hilfe/' . $datei . '" alt="' . Util::e($alt) . '" loading="lazy">'
            . '<figcaption>' . Util::e($alt) . '</figcaption></figure>';
    }

    /**
     * Eine kleine „Animation": mehrere Screenshots, die im Popup automatisch
     * durchgeblendet werden (reines CSS, siehe admin.css → .hilfe-anim).
     *
     * @param string[] $bilder
     */
    private static function anim(array $bilder, string $alt): string
    {
        $imgs = '';
        foreach (array_values($bilder) as $i => $b) {
            $imgs .= '<img src="assets/hilfe/' . $b . '" alt="' . Util::e($alt) . '" style="--i:' . $i . '" loading="lazy">';
        }
        $n = count($bilder);
        return '<figure class="hilfe-bild hilfe-anim" data-frames="' . $n . '" style="--n:' . $n . ';">'
            . $imgs . '<figcaption>' . Util::e($alt) . ' <span class="hilfe-anim-hin">(läuft automatisch durch)</span></figcaption></figure>';
    }
}
