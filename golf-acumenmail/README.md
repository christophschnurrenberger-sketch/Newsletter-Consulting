# AcumenMail Golf

Website für Newsletter-Marketing in **Golfclubs** – auf Basis der AcumenMail-Seite,
inhaltlich und strukturell neu aufgebaut. Keine Onepager-Landingpage, sondern eine
Website-Architektur mit 28 Seiten in fünf Bereichen, wie sie B2B-Anbieter in diesem
Markt (PC CADDIE, Inxmail, rapidmail, mail2many) verwenden.

Herzstück des Software-Bereichs sind bewegte Demos, die zeigen, wie ein Clubnewsletter
im bereits vorhandenen Newsletter-Tool entsteht.

---

## Ansehen

**`index.html` im Browser öffnen.** Mehr ist nicht nötig – die Seite ist fertiges
HTML mit relativen Verweisen und läuft lokal per Doppelklick genauso wie auf
jedem Webspace.

Einzige Ausnahme: Das Kontaktformular schickt an `kontakt-senden.php` und braucht
dafür einen Server mit PHP. Lokal öffnet sich die Seite trotzdem, nur das
Absenden geht ins Leere.

---

## Technik

Die Seite hat keinen dynamischen Inhalt – sie ist für alle gleich. Ausgeliefert
wird deshalb reines HTML. PHP dient nur als Bau-Werkzeug, damit Kopfzeile,
Navigation, Randspalte und Footer nicht 28-mal kopiert werden müssen.

```
/                    Die fertige Website – hier liegt index.html
  index.html
  software/*.html    software/, loesungen/, leistungen/, wissen/
  assets/            CSS, JavaScript
  kontakt-senden.php Einziges PHP: nimmt das Kontaktformular entgegen
  robots.txt, sitemap.xml

src/                 Die Quellen, aus denen gebaut wird
  index.php          eine Datei je Seite, enthält nur den Inhalt
  software/…
  partials/config.php    Grunddaten und der Navigationsbaum
  partials/header.php    <head>, Servicezeile, Mega-Menü, Brotkrumen, Seitenkopf
  partials/footer.php    Handlungsband, Footer, Skripte
  partials/aside.php     Randspalte der Unterseiten
  partials/golf-scene.php  Platzansicht als Inline-SVG

tools/build.php      Baut aus src/ die HTML-Seiten und sitemap.xml
```

### Ändern und neu bauen

Bearbeitet wird immer `src/`. Die `.html`-Dateien im Hauptverzeichnis werden
erzeugt und beim nächsten Lauf überschrieben – in ihrem Kopf steht ein
entsprechender Hinweis.

```bash
php tools/build.php     # baut alle 28 Seiten und sitemap.xml neu
```

Wer beim Schreiben lieber direkt im Browser nachsieht, statt jedes Mal zu bauen:

```bash
php -S 127.0.0.1:8000 -t src
```

**Eine neue Unterseite anlegen:** Datei unter `src/` erstellen, `$page` setzen,
Header und Footer einbinden – und den Eintrag in `src/partials/config.php`
ergänzen. Menü, Mega-Menü, Brotkrumen, Randspalte, Footer und Sitemap ziehen
automatisch nach.

```php
<?php
$page = [
    'title'       => 'Newsletter-Baukasten',
    'description' => '…',
    'section'     => 'software',            // Schlüssel aus $NAV
    'path'        => 'software/baukasten.php',
    'crumbs'      => [['Software', 'software/'], ['Baukasten', null]],
    'hero'        => ['kicker' => …, 'h1' => …, 'lead' => …, 'facts' => …],
];
include __DIR__ . '/../partials/header.php';
```

Danach `php tools/build.php` – fertig.

---

## Zum Design

Die erste Fassung sah nach Baukasten aus: Jeder Abschnitt war ein Raster aus
drei gleich großen Kästen mit 1px-Rahmen, jeder Kasten hatte ein Icon, alle
Überschriften waren ungefähr gleich groß. Sauber – und völlig austauschbar.

Diese Fassung macht das Gegenteil, und `assets/site.css` erklärt das im Kopf
noch einmal ausführlich:

* **Kein Rahmen als Standard.** Getrennt wird über Weißraum und Schriftgröße.
* **Brutaler Größenkontrast.** Überschriften sehr groß, Hilfstexte sehr klein,
  dazwischen fast nichts.
* **Eine laute Farbe.** Lindgrün `#C7F04A`, an wenigen Stellen, dafür in Fläche.
* **Asymmetrie.** Sieben zu fünf statt eins zu eins.
* **Zahlen statt Icons.**

Die Anzeigeschrift ist Georgia in sehr großen Graden. Bewusst keine externe
Schrift: Google Fonts ist auf einer deutschen Seite auch ein Datenschutzthema,
und selbst gehostete Schriften wären zusätzliche Ladezeit für wenig Gewinn.

---

## Seitenstruktur

| Bereich | Seiten |
|---|---|
| **Software** | Übersicht · Newsletter-Baukasten · Automationen · Empfänger & Segmente · Auswertung · Zustellbarkeit & DSGVO · Systemvoraussetzungen |
| **Lösungen** | Übersicht · Mitgliederbindung · Turniere & Events · Gastspieler & Greenfee · Neumitglieder gewinnen · Golfschule & Pro |
| **Leistungen** | Übersicht · Clubcheck · Saison-Setup · Clubbetreuung |
| **Preise** | Pakete und eine Vergleichsrechnung gegen Mietlösungen |
| **Wissen** | Übersicht · Newsletter-Jahresplan · Betreffzeilen · Mitgliederdaten und DSGVO · Häufige Fragen |
| **Sonstige** | Startseite · Über uns · Kontakt · Impressum · Datenschutz |

Die Startseite verweist in die Bereiche, statt alles selbst zu erzählen. Jede
Unterseite hat Brotkrumen, eine Randspalte mit der Navigation ihres Bereichs und
weiterführende Verweise am Ende.

---

## Persönliche Demo-Seiten je Club

Neben der Website gibt es einen zweiten Baustein für die Ansprache: Statt einer
allgemeinen Verkaufsseite bekommt jeder Club eine eigene kleine Seite unter
`club/<kennung>/` – mit seinem Namen, seiner bisherigen Ausgabe und einem
konkreten Vorschlag daneben.

Der Effekt ist ein anderer als bei einem Angebot: Es liest sich nicht wie
„ich möchte Ihnen etwas verkaufen“, sondern wie „jemand hat sich mit unserem
Club beschäftigt“.

**Aufbau der Seite:** Wir haben Ihren Newsletter einmal neu gedacht → vorherige
Ausgabe und Vorschlag nebeneinander → drei konkrete Verbesserungen → was darüber
hinaus möglich wäre → Knopf „Beispiel besprechen“.

Das Design ist bewusst ein anderes als der Rest dieser Website: Weiß, kräftiges
Clubgrün, Signalgelb für Knöpfe, Versalien mit Sperrung, Golfball als
Aufzählungszeichen – näher am Look deutscher Clubwebsites.

### Einen neuen Club anlegen

```bash
cp src/club/daten/golfclub-musterstadt.php src/club/daten/gc-beispiel.php
# Felder ausfüllen, dann:
php tools/build.php
```

Fertig unter `club/gc-beispiel/index.html`. Die Kennung ist der Dateiname –
wer kürzere Adressen will (`domain.de/gc-beispiel` statt `domain.de/club/gc-beispiel`),
ändert in `tools/build.php` den Zielpfad und in `src/club/render.php` die
Ebenenzahl beim Umschreiben der Verweise von 2 auf 1.

**Zwei Dinge sind dabei wichtig:**

1. In den Abschnitt `vorher` gehört die **echte** letzte Ausgabe des Clubs –
   im Wortlaut, nur gekürzt. Eine erfundene Fassung zerstört genau das
   Vertrauen, auf dem die Idee beruht: Der Empfänger erkennt sofort, ob das
   seine Mail ist. Solange der Platzhalter drinsteht, weist die Seite selbst
   darauf hin.
2. Die Seiten tragen `noindex` und stehen nicht in der Sitemap. Sie sind für
   genau einen Empfänger gedacht, nicht für Suchmaschinen.

Mitgeliefert sind zwei Beispiele: `golfclub-musterstadt` (frei erfunden, als
Vorlage zum Kopieren) und `gc-ottobeuren` (echter Club, Vorschlagsseite mit
Platzhalter im Vorher-Teil).

---

## Echte Bildschirmfotos statt nachgebauter Oberflächen

Die erste Fassung dieser Seite hatte die Oberfläche des Newsletter-Tools in
HTML und CSS **nachgebaut** und als Animation ablaufen lassen. Das sah gut aus
und war trotzdem falsch: Es war eine Behauptung darüber, wie das Tool aussieht,
keine Auskunft.

Jetzt stehen dort echte Aufnahmen. Das Newslettersystem wurde lokal installiert
(SQLite, `install.php`), mit der Golfvorlage `fairway54` eine echte Ausgabe
geschrieben und eine Automation zusammengezogen – die Bilder in
`assets/bilder/` sind Bildschirmfotos davon.

| Datei | Zeigt |
|---|---|
| `tool-baukasten.png` | Der Baukasten mit Bausteinleiste, Ausgabe und Gestaltungsspalte |
| `tool-vorschau.png` | Schritt 3: Vorschau der fertigen Mail für Rechner und Handy |
| `tool-angaben.png` | Schritt 2: Betreff, Absender, Empfängerliste |
| `tool-automation.png` | Der Ablauf-Editor mit Ja-/Nein-Zweig |

Zum Erneuern: das Newslettersystem lokal starten, die gewünschte Ansicht
aufrufen, aufnehmen, zuschneiden und in `assets/bilder/` ablegen. Die Bilder
sind bewusst eng zugeschnitten – ein ganzer Bildschirm mit Seitennavigation
ist auf einer halben Spaltenbreite nicht mehr lesbar.

`assets/demo.css` und `assets/demo.js` sind damit entfallen (rund 38 KB
Attrappe weniger).

---

## Vor dem Livegang anpassen

1. **Domain** in `src/partials/config.php` (`$SITE['domain']`), danach
   `php tools/build.php` laufen lassen und die Domain in `robots.txt` eintragen.
2. **Kontaktdaten** ebenfalls in `src/partials/config.php` – sie erscheinen in der
   Servicezeile, im Footer und auf der Kontaktseite. Nach jeder Änderung neu bauen.
3. **Preise** in `src/preise.php` und in den drei Seiten unter `src/leistungen/`
   (290 € / 1.490 € / 390 € im Monat). Die Zahlen sind Platzhalter, keine Kalkulation.
4. **Empfänger und Absender** in `kontakt-senden.php`, Block `$CONFIG` ganz oben. Die
   Absenderadresse muss ein echtes Postfach der eigenen Domain sein (SPF/DKIM), sonst
   landen die Mails im Spam.
5. **Impressum und Datenschutz** sind aus dem bestehenden Bestand übernommen und auf
   die neue Marke umgeschrieben. Inhaltlich noch einmal prüfen, besonders die Angaben
   zur neuen Domain.
6. **Verweis auf das Newsletter-Tool** in der Servicezeile zeigt auf
   `newsletter/admin/login.php`. Liegt das System woanders, in `src/partials/header.php`
   anpassen.

---

## Geprüft

Jede der 28 erzeugten Seiten einzeln über `file://` geöffnet – so, wie sie
lokal per Doppelklick aufgeht: Stylesheet greift, alle Icons gerendert, kein
übrig gebliebener `.php`-Verweis, keine fehlende Datei, keine JavaScript-Fehler.
Navigation über Kacheln, Mega-Menü, Randspalte und Footer nachgeklickt.

Zusätzlich mit einem Crawler über die PHP-Quellen: keine kaputten internen Verweise, überall
genau eine `h1`, kein horizontales Scrollen ab 320 px, keine JavaScript-Fehler,
keine PHP-Meldungen. Mega-Menü, mobiles Akkordeon, FAQ und Formularvalidierung
zusätzlich von Hand getestet.

## Barrierefreiheit

* Keine externen Schriften, Skripte oder Tracker – nichts verlässt den eigenen Server.
* Menüpunkte mit Untermenü sind `<button aria-expanded>`, nicht Links – bedienbar mit
  Tastatur und Screenreader; Escape schließt, Fokusverlust schließt.
* Sprungmarke zum Inhalt, sichtbarer Tastaturfokus, Brotkrumen mit `aria-current`.
* Icons als Inline-SVG, die Platzansicht ebenfalls als SVG statt als Bilddatei.
* `prefers-reduced-motion` schaltet Bewegung ab und zeigt Endzustände.
