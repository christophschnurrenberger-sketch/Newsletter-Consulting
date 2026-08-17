# AcumenMail Golf

Landingpage für Newsletter-Marketing in **Golfclubs** – auf Basis der AcumenMail-Seite,
inhaltlich und gestalterisch komplett neu gebaut. Herzstück sind drei bewegte Demos,
die zeigen, wie ein Clubnewsletter im bereits vorhandenen Newsletter-Tool entsteht.

Statische Seite: HTML, CSS und etwas JavaScript. Ein einziges PHP-Skript (`kontakt.php`)
nimmt das Kontaktformular entgegen. Keine Build-Kette, keine Abhängigkeiten, keine CDNs.

---

## Was drin ist

| Datei | Zweck |
|---|---|
| `index.html` | Die Startseite mit allen Abschnitten |
| `assets/site.css` | Design-Tokens, Layout, Komponenten, Rechtsseiten |
| `assets/demo.css` | Die drei bewegten Demos |
| `assets/site.js` | Icons, Navigation, FAQ, Kontaktformular |
| `assets/demo.js` | Taktgeber der Demos (Drehbücher als Datenstruktur) |
| `assets/newsletter-signup.js` | Anmeldung am eigenen Newslettersystem |
| `kontakt.php` | Serverseitige Verarbeitung des Kontaktformulars |
| `Impressum.html`, `Datenschutz.html` | Rechtsseiten im neuen Design |
| `robots.txt`, `sitemap.xml` | Suchmaschinen |

Ansehen ohne Server: `index.html` im Browser öffnen. Das Kontaktformular braucht PHP,
alles andere läuft auch aus dem Dateisystem.

Lokal mit PHP:

```bash
php -S 127.0.0.1:8000
```

---

## Die drei Demos

Der Wunsch war „eventuell mit GIFs, die den Prozess zeigen“. Umgesetzt ist es **ohne
GIFs** – die Oberflächen sind aus HTML und CSS nachgebaut und werden von `demo.js`
Schritt für Schritt weitergeschaltet. Das hat drei handfeste Vorteile:

* **Scharf in jeder Größe und auf jedem Bildschirm.** Ein GIF ist eine Bitmap in fester
  Auflösung und wird auf Retina-Displays matschig.
* **Wenige Kilobyte statt mehrerer Megabyte.** Ein Screencast dieser Länge wäre als GIF
  schnell 3–8 MB groß – auf dem Handy am Golfplatz ein echtes Problem.
* **Änderbar ohne Videoschnitt.** Wird eine Beschriftung im Tool anders, ändert man hier
  eine Textzeile statt eine neue Aufnahme zu machen.

| Demo | Zeigt |
|---|---|
| `data-demo="builder"` | Bausteine per Drag & Drop in den Newsletter ziehen |
| `data-demo="steps"` | Inhalt → Angaben → Prüfen & Senden, mit Ampel und Versand |
| `data-demo="flow"` | Eine Automation als Ablauf mit Ja-/Nein-Zweig |

**So ändert man eine Demo:** In `assets/demo.js` steht je Demo ein Drehbuch als Liste von
Schritten. Jeder Schritt beschreibt den *vollständigen* Zustand – welche Elemente eine
Zustandsklasse bekommen – plus den Untertitel darunter:

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
Demo zeigt schlicht das fertige Ergebnis statt einer leeren Fläche.

**Falls später doch echte Screencasts gewünscht sind:** eine Aufnahme aus dem echten
Tool als `<video autoplay muted loop playsinline>` (MP4/WebM) einsetzen – nicht als GIF.
Gleiche Wirkung, ein Bruchteil der Dateigröße.

---

## Vor dem Livegang anpassen

Diese Stellen sind bewusst als Platzhalter gesetzt:

1. **Domain.** Überall steht `https://www.golf-newsletter.de/`. Zu ersetzen in
   `index.html` (Canonical, Open Graph, JSON-LD), `Impressum.html`, `Datenschutz.html`,
   `robots.txt` und `sitemap.xml`.
2. **Preise** in `index.html`, Abschnitt `#pakete` (290 € / 1.490 € / 390 € im Monat) –
   an die eigene Kalkulation angleichen. Die Stelle ist im HTML kommentiert.
3. **Empfänger und Absender** in `kontakt.php`, Block `$CONFIG` ganz oben. Die
   Absenderadresse muss ein echtes Postfach der eigenen Domain sein (SPF/DKIM), sonst
   landen die Mails im Spam.
4. **Impressum und Datenschutzerklärung** sind aus dem bestehenden Bestand übernommen
   und auf die neue Marke umgeschrieben. Inhaltlich noch einmal prüfen, besonders die
   Angaben zum Newsletter-Versand und zur neuen Domain.
5. **Newsletter-Anmeldung.** Das Formular im Abschnitt `#newsletter` sendet an
   `newsletter/subscribe.php`. Dafür muss das Newslettersystem unter `newsletter/`
   auf derselben Domain liegen – sonst den Abschnitt entfernen oder den Pfad anpassen.

---

## Inhaltliche Ausrichtung

Die Seite spricht durchgehend Golfclubs an, nicht „mittelständische Unternehmen“:

* **Ausgangslage** – sechs Situationen aus dem Cluballtag (Turnier nicht voll, Winterpause,
  Gastspieler, Neumitglieder, Aushang, Newsletter hängt an einer Person).
* **Leistungen** – Segmente (Handicap, Mitgliedsart, Aktivität), Redaktionsplan entlang
  der Saison, Automationen, Kennzahlen für den Vorstand.
* **Das Tool** – der dunkle Abschnitt mit den drei Demos und der Funktionsübersicht.
* **Automationen** – sechs fertige Strecken: Willkommen, Platzreife, Turnier-Erinnerung,
  Reaktivierung, Gastspieler, Wintersaison.
* **Vorgehen, Pakete, FAQ, Kontakt** – FAQ und Formular sind auf Clubs zugeschnitten
  (Funktion im Club, Mitgliederzahl, PC CADDIE, Vereinsrecht).

Farben und Typografie stammen aus der bereits vorhandenen Newsletter-Vorlage
`Fairway54` des Newslettersystems: Fairway-Grün `#1E6B45`, Sand `#FAF9F5`,
Georgia für Überschriften. Damit passen Website und versendete Newsletter zusammen.

---

## Barrierefreiheit und Technik

* Keine externen Schriften, Skripte oder Tracker – nichts verlässt den eigenen Server.
* Icons als Inline-SVG in `site.js`, die Platzansicht ebenfalls als SVG statt als Bilddatei.
* Sprungmarke zum Inhalt, sichtbarer Tastaturfokus, `aria-expanded` an Menü und FAQ.
* Kein horizontales Scrollen ab 320 px Breite; die Demos passen sich bis zum Handy an.
* `prefers-reduced-motion` schaltet Bewegung ab und zeigt Endzustände.
