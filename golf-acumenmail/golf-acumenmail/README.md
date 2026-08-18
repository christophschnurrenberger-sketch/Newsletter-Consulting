# AcumenMail Golf

Website für Newsletter-Marketing in **Golfclubs** – auf Basis der AcumenMail-Seite,
inhaltlich und strukturell neu aufgebaut. Keine Onepager-Landingpage, sondern eine
Website-Architektur mit 28 Seiten in fünf Bereichen, wie sie B2B-Anbieter in diesem
Markt (PC CADDIE, Inxmail, rapidmail, mail2many) verwenden.

Herzstück des Software-Bereichs sind bewegte Demos, die zeigen, wie ein Clubnewsletter
im bereits vorhandenen Newsletter-Tool entsteht.

---

## Technik

PHP-Seiten mit gemeinsamen Partials – kein Build, kein Paketverwalter, keine
Abhängigkeiten. Das passt zum Hosting, auf dem das Newslettersystem ohnehin läuft.

```bash
php -S 127.0.0.1:8000        # lokal ansehen
php tools/build-sitemap.php  # sitemap.xml neu erzeugen
```

### Aufbau

```
partials/config.php    Grunddaten und der Navigationsbaum – die einzige Quelle
partials/header.php    <head>, Servicezeile, Mega-Menü, Brotkrumen, Seitenkopf
partials/footer.php    Handlungsband, Footer mit Sitemap, Skripte
partials/aside.php     Randspalte der Unterseiten (Bereichsnavigation + CTA)
partials/golf-scene.php  Platzansicht als Inline-SVG

assets/site.css        Tokens, Navigation, Seitenbausteine, Formulare, Footer
assets/demo.css        Die bewegten Demos
assets/site.js         Icons, Navigation, FAQ, Kontaktformular
assets/demo.js         Taktgeber der Demos (Drehbücher als Datenstruktur)

kontakt-senden.php     Serverseitige Verarbeitung des Kontaktformulars
tools/build-sitemap.php  Erzeugt sitemap.xml aus dem Navigationsbaum
```

**Eine neue Unterseite anlegen** heißt: Datei erstellen, `$page` setzen, Header und
Footer einbinden – und den Eintrag in `partials/config.php` ergänzen. Menü,
Mega-Menü, Brotkrumen, Randspalte, Footer und Sitemap ziehen automatisch nach.

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

## Die bewegten Demos

Der Wunsch war „eventuell mit GIFs, die den Prozess zeigen“. Umgesetzt ist es **ohne
GIFs**: Die Oberflächen sind aus HTML und CSS nachgebaut und werden von `demo.js`
Schritt für Schritt weitergeschaltet. Drei handfeste Vorteile:

* **Scharf in jeder Größe.** Ein GIF ist eine Bitmap in fester Auflösung und wird auf
  Retina-Displays matschig.
* **Wenige Kilobyte statt mehrerer Megabyte.** Ein Screencast dieser Länge wäre als GIF
  schnell 3–8 MB groß – auf dem Handy am Golfplatz ein echtes Problem.
* **Änderbar ohne Videoschnitt.** Ändert sich eine Beschriftung im Tool, ändert man hier
  eine Textzeile.

| Demo | Zeigt | Wo |
|---|---|---|
| `builder` | Bausteine per Drag & Drop in den Newsletter ziehen | Startseite, Newsletter-Baukasten |
| `steps` | Inhalt → Angaben → Prüfen & Senden mit Ampel | Software-Übersicht |
| `flow` | Automation als Ablauf mit Ja-/Nein-Zweig | Automationen |

**So ändert man eine Demo:** In `assets/demo.js` steht je Demo ein Drehbuch als Liste
von Schritten. Jeder Schritt beschreibt den *vollständigen* Zustand – welche Elemente
eine Zustandsklasse bekommen – plus den Untertitel darunter:

```js
{
    caption: 'Loslassen. Die Überschrift sitzt zwischen Kopfzeile und Footer.',
    set: [['.pal-1', 'is-active'], ['.ghost-1', 'is-moving'], ['.blk-1', 'is-in is-new']],
    cursor: ['300px', '128px']
}
```

Wie die Zustände aussehen, steht ausschließlich in `assets/demo.css`. Das Tempo regelt
`STEP_MS` oben in `demo.js` (derzeit 1250 ms).

Die Demos halten an, sobald sie aus dem Bild scrollen, lassen sich über den Knopf in der
Fensterleiste pausieren und von vorn abspielen, und sie respektieren
`prefers-reduced-motion`. Ohne JavaScript bleibt die Klasse `is-static` stehen und die
Demo zeigt das fertige Ergebnis statt einer leeren Fläche.

**Falls später echte Screencasts gewünscht sind:** als `<video autoplay muted loop
playsinline>` (MP4/WebM) einsetzen – nicht als GIF. Gleiche Wirkung, ein Bruchteil der
Dateigröße.

---

## Vor dem Livegang anpassen

1. **Domain und Basispfad** in `partials/config.php` (`$SITE['domain']`, `$SITE['base']`).
   Danach `php tools/build-sitemap.php` laufen lassen und die Domain in `robots.txt`
   eintragen. Alle Seiten ziehen automatisch nach.
2. **Kontaktdaten** ebenfalls in `partials/config.php` – sie erscheinen in der
   Servicezeile, im Footer und auf der Kontaktseite.
3. **Preise** in `preise.php` und in den drei Seiten unter `leistungen/`
   (290 € / 1.490 € / 390 € im Monat). Die Zahlen sind Platzhalter, keine Kalkulation.
4. **Empfänger und Absender** in `kontakt-senden.php`, Block `$CONFIG` ganz oben. Die
   Absenderadresse muss ein echtes Postfach der eigenen Domain sein (SPF/DKIM), sonst
   landen die Mails im Spam.
5. **Impressum und Datenschutz** sind aus dem bestehenden Bestand übernommen und auf
   die neue Marke umgeschrieben. Inhaltlich noch einmal prüfen, besonders die Angaben
   zur neuen Domain.
6. **Verweis auf das Newsletter-Tool** in der Servicezeile zeigt auf
   `newsletter/admin/login.php`. Liegt das System woanders, in `partials/header.php`
   anpassen.

---

## Geprüft

Mit einem Crawler über alle 28 Seiten: keine kaputten internen Verweise, überall
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
