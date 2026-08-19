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
  assets/demo/       Stylesheets des Newslettersystems (Kopien)
  demo/              Die drei Demo-Seiten, als iframe eingebunden
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

## Die Demos: die Oberfläche selbst, nicht ihr Abbild

Diese Stelle hat zwei Anläufe hinter sich.

1. Zuerst war die Oberfläche des Newslettersystems in HTML und CSS **nachgebaut**
   und lief als Animation ab. Das sah gut aus und war trotzdem falsch: eine
   Behauptung darüber, wie das Tool aussieht, keine Auskunft.
2. Dann standen dort **Bildschirmfotos**. Ehrlich, aber unscharf – ein 1600 px
   breites Bild auf einer 600 px breiten Spalte wird nie wieder scharf.

Jetzt steht dort das Original. In `demo/` liegen drei eigenständige Seiten, deren
Markup und Stylesheets **unverändert aus dem laufenden Newslettersystem** stammen:

| Datei | Zeigt |
|---|---|
| `demo/baukasten.html` | Der Baukasten: Bausteinleiste, Ausgabe, Gestaltungsspalte |
| `demo/automation.html` | Der Ablauf-Editor mit Wartezeiten, Mails und Ja-/Nein-Zweig |
| `demo/pruefen.html` | Schritt 3: Vorschau, Testversand und die Ampel vor dem Senden |

Die passenden Stylesheets liegen in `assets/demo/` (`admin.css`, `builder.css`,
`flow.css`) – ebenfalls Kopien, nicht Nachbauten. Es ist echter Text im Browser:
scharf auf jedem Bildschirm, durchsuchbar, und beim Vergrößern bleibt er lesbar.

**Entfernt wurde nur, was etwas tun könnte:** Skripte, `name`-Attribute,
Ereignisbehandler, Formularlogik. Knöpfe tragen `type="button"`, Eingaben sind
`readonly`, Links zeigen ins Leere. Die Demo kann nichts absenden und nichts
speichern. Die beiden Vorschaurahmen (Kopf- und Fußzeile der Vorlage) bekommen
ihren Inhalt über `srcdoc` mitgegeben, damit sie ohne Server funktionieren.

### Ein Schaukasten, keine eingebettete Anwendung

Die erste Einbindung war technisch richtig und fühlte sich trotzdem falsch an.
Sie sah bedienbar aus, war es aber nicht: Man klickte einen Knopf an, und nichts
geschah. Auf dem Handy fing der Rahmen den Fingerwisch ab, statt die Seite
weiterzurollen – man blieb darin hängen. Und der Ausschnitt zeigte ausgerechnet
die Bausteinleiste, nicht den Newsletter.

Jetzt ist der Rahmen als Schaukasten gebaut:

- **Eine beschriftete Leiste darüber** sagt, was zu sehen ist:
  „Newslettersystem · Golfclub Musterhausen – Ansicht, nicht bedienbar".
- **`pointer-events: none` auf dem `<iframe>`.** Kein Klick ins Leere, keine
  Scrollfalle. Der Finger rollt die Seite weiter, wie überall sonst.
- **Auf dem Handy ein Ausschnitt statt der ganzen Oberfläche.** Die Bausteinleiste
  und die Gestaltungsspalte treten zurück (`@media` in der jeweiligen Demo-Datei),
  gezeigt wird die Ausgabe selbst. Das ist ein Bildausschnitt, keine andere
  Oberfläche – aus 2 437 px werden 1 280 px, und alles bleibt in Originalgröße
  lesbar.
- **Volle Breite auf dem Handy.** Bei 350 px Innenbreite schnitt die Werkzeugleiste
  eines Bausteins ihr letztes Zeichen ab; über die ganze Breite passt sie.

Dazu regelt `assets/site.js` zwei Dinge:

- **Höhe.** Die Demo misst sich selbst und meldet sie per `postMessage` herauf;
  die Seite setzt sie als `--demo-h`, aber nur bei einer Abweichung von mehr als
  vier Pixeln – sonst schaukeln sich Höhenanimation und Messung gegenseitig auf.
  Ohne JavaScript greift der Vorgabewert aus `assets/site.css`.
- **Start.** Die Bausteine blenden nacheinander ein, sobald der Rahmen ins Bild
  kommt – nicht vorher, sonst ist die Bewegung vorbei, ehe jemand hinsieht.

Die Vorschau in `demo/pruefen.html` bekommt zusätzlich die Höhe ihres Inhalts:
im System rollt sie in einem Rahmen fester Höhe, hier wäre das ein Rollbalken
mitten in der Seite und eine unten abgeschnittene Mail.

### Eine Demo erneuern

1. Newslettersystem lokal starten (`php -S 127.0.0.1:8901`), anmelden.
2. Die gewünschte Ansicht aufrufen und das **gerenderte** Markup sichern
   (Entwicklerwerkzeuge, „Copy outerHTML" auf `<html>`). Ablauf-Editor und
   Baukasten bauen ihren Inhalt per JavaScript auf – die PHP-Datei zu lesen
   genügt nicht.
3. Ausschnitt herausschneiden und säubern:

   ```
   python3 tools/demo-saeubern.py seite.html '<div class="ad-card">' > teil.html
   ```

   Das Werkzeug entfernt Skripte, Ereignisbehandler, `name`-Attribute und
   versteckte Felder, macht Knöpfe und Eingaben wirkungslos – und nimmt den
   Zeichenschlüssel der Sitzung sowie die Adresse des Testservers heraus.
   Beides gehört nicht in ein Repository.
4. Den Teil in die passende Datei unter `demo/` einsetzen und die Stylesheets
   in `assets/demo/` auffrischen.

`assets/demo.css`, `assets/demo.js` und `assets/bilder/` sind damit entfallen.

---

## Umbrüche und Rollbalken

Zwei Dinge, die auf einer deutschsprachigen Seite immer wieder auffallen –
und ein Fehler, der lange unbemerkt blieb.

**Der Seitenkopf war 246 px breit.** Für Seiten ohne Eckdaten stand
`max-width: 26ch` an der Spalte statt an der Überschrift. `ch` rechnet mit der
Schriftgröße des Elements – an der Spalte gesetzt, galt das plötzlich für die
Grundschrift statt für die 4,6 rem der Überschrift. Ergebnis: zehn Seiten mit
einer Überschrift in einer Spalte von 246 px, „Kostenlose" gebrochen zu
„Kostenl / ose", bis zu neun Zeilen. Jetzt steht die Begrenzung an der
Überschrift selbst.

**Umbrüche überlässt die Seite dem Browser.** `text-wrap: balance` verteilt die
Zeilen einer Überschrift gleichmäßig, `text-wrap: pretty` verhindert im Fließtext
das einzelne Wort am Absatzende. Feste `<br>` gibt es nicht mehr – sie brachen
auf dem Handy ein zweites Mal.

**Lange Zusammensetzungen tragen weiche Trennzeichen.** `hyphens: auto` braucht
ein Trennwörterbuch, und nicht jeder Browser bringt eines für Deutsch mit; dort
wurde aus „Datenschutzerklärung" ein stumpfes „Datenschutzerklärun / g". Ein
weiches Trennzeichen (U+00AD) versteht jeder Browser und wird nur sichtbar, wo
tatsächlich getrennt wird. Es steht direkt in den Quellen – `tools/build.php`
nimmt es aus `<title>` und den Meta-Angaben wieder heraus, dort wird nicht
umbrochen und in Suchergebnissen hätte es nichts verloren.

**Tabellen rollen nicht mehr.** Eine Vergleichstabelle mit `min-width: 34rem`
bekam in einer 443 px breiten Spalte einen waagerechten Rollbalken – auf dem
Rechner, nicht nur auf dem Handy. Jetzt misst sich der Rahmen selbst
(`container-type: inline-size`) und stellt die Tabelle untereinander, sobald es
eng wird: eine Zeile wird ein Block, die Spaltenüberschrift steht als Etikett
neben dem Wert. Die Etiketten setzt `tools/build.php` aus der Kopfzeile der
Tabelle ein (`data-label`) – von Hand gepflegt stünden sie beim ersten
Umsortieren falsch. Browser ohne Container-Abfragen behalten den Rollrahmen.

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

Die drei Demo-Seiten einzeln bei 1180, 760 und 390 px geprüft: kein Überlauf,
keine fehlende Datei, keine JavaScript-Fehler. Die Einbindung zusätzlich über
`http://` **und** `file://` – die Höhenmeldung per `postMessage` funktioniert in
beiden Fällen, und die Vorlagenrahmen im Baukasten zeichnen auch dort.

Für den UX-Durchgang ein eigener Lauf über alle Seiten bei 1440 und 390 px, der
zwei Dinge sucht: Elemente, die tatsächlich rollen (`overflow` auf `auto`/`scroll`
**und** Inhalt größer als der Rahmen), und Überschriften oder Absätze, deren
letzte Zeile kürzer als 28 % der längsten ist. Ergebnis vorher: 43 Fundstellen,
darunter sieben rollende Tabellen. Danach: keine rollenden Elemente mehr und
sechs Umbrüche, die alle im üblichen Rahmen liegen. Zusätzlich bei 320, 360 und
390 px auf waagerechten Überlauf geprüft – keiner.

## Barrierefreiheit

* Keine externen Schriften, Skripte oder Tracker – nichts verlässt den eigenen Server.
* Menüpunkte mit Untermenü sind `<button aria-expanded>`, nicht Links – bedienbar mit
  Tastatur und Screenreader; Escape schließt, Fokusverlust schließt.
* Sprungmarke zum Inhalt, sichtbarer Tastaturfokus, Brotkrumen mit `aria-current`.
* Icons als Inline-SVG, die Platzansicht ebenfalls als SVG statt als Bilddatei.
* `prefers-reduced-motion` schaltet Bewegung ab und zeigt Endzustände – auch in den Demos.
* Die Demo-Rahmen tragen einen `title`, der beschreibt, was darin zu sehen ist.
