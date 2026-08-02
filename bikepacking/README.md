# Sattelfest – Bikepacking-Einstiegsguide

Statische Website für Bikepacking-Einsteiger. Aufgebaut wie ein Ratgeber-Portal:
sechs Rubriken mit Dropdown-Navigation, 40 ausführliche Artikel, Newsletter-Anbindung,
zwei interaktive Werkzeuge und Affiliate-Integration.

**Kein Framework, keine npm-Abhängigkeiten, keine CDN-Aufrufe.** Ein Node-Skript rendert
alle Seiten nach `dist/`. Dieser Ordner wird per FTP auf einen ganz normalen Webspace
geladen – es wird kein Node, kein PHP und keine Datenbank auf dem Server benötigt.

Gleiche Architektur wie das Schwesterprojekt Fairway54 (Golf-Einstiegsguide).

---

## Schnellstart

```bash
node build.js     # baut alles nach dist/
```

Danach **`dist/index.html` doppelklicken** – die Seite öffnet sich im Browser und
funktioniert vollständig, inklusive Navigation und beider Werkzeuge. Ein lokaler Server ist
nicht nötig, weil intern relativ verlinkt wird.

Wer trotzdem einen Server möchte (etwa um die `.htaccess` zu testen):

```bash
python3 -m http.server 8080 --directory dist   # dann localhost:8080 aufrufen
```

> **Ausnahme `dist/404.html`:** Diese eine Seite wird bewusst mit **absoluten** Pfaden
> gebaut, weil Apache sie unter beliebigen URLs ausliefert und die Verzeichnistiefe
> deshalb nicht vorhersehbar ist. Auf dem Webspace ist das richtig – nur in der lokalen
> `file://`-Vorschau lädt sie kein CSS. Kein Fehler.

---

## Umfang

| | |
|---|---|
| Seiten gesamt | 51 (40 Inhaltsseiten, 6 Rubrikseiten, 2 Werkzeuge, Recht, 404) |
| Rubriken | Einstieg · Taschen · Ausrüstung · Routen & Planung · Unterwegs · Service |
| Glossar | 76 Begriffe |
| Werkzeuge | Packlisten-Generator (8 Fragen), Etappen- & Gewichts-Rechner |
| Erzeugtes HTML | ca. 2,9 MB |

---

## Projektstruktur

```
build.js                 Generator (führt alles zusammen, prüft Links)
src/
  config.js              ⚙️  Domain, Basispfad, Newsletter-Endpunkt, Affiliate-IDs
  nav.js                 🧭  Navigationsbaum – Quelle für Menü, Footer, Sitemap
  layout.js              HTML-Gerüst: Header mit Mega-Dropdowns, Footer, SEO-Meta
  components.js          Bausteine: Tabellen, Callouts, Packlisten, Routenkarten, FAQ …
  data/
    shops.js             🛒  Ziel-URLs der Produktempfehlungen
    glossar.js           📖  Die 76 Glossarbegriffe (Startseite nennt die Anzahl)
  assets/
    site.css             Vollständiges Stylesheet
    site.js              Navigation, Newsletter, Packlisten-Generator, Etappen-Rechner
    favicon.svg
    img/                 Bilder (Konvention: /taschen/satteltasche.jpg zur gleichnamigen Seite)
  pages/
    _article.js          Standardgerüst einer Inhaltsseite
    _section-index.js    Rubrikseiten, automatisch aus nav.js erzeugt
    einstieg/ taschen/ ausruestung/ routen/ unterwegs/ tools/
```

### Eigene Bausteine dieses Projekts

Zusätzlich zu den Standard-Bausteinen (Tabellen, Callouts, Karten, FAQ …):

- **`weightList()`** – Packliste mit Gramm-Spalte, Einordnung als Pflicht / sinnvoll /
  Ballast und **automatisch berechneter Summe** (Ballast zählt nicht mit). Die Summen in
  den Fließtexten stammen aus derselben Quelle wie die Listen und können nicht auseinanderlaufen.
- **`routeCard()` / `routeGrid()`** – Routenkarte mit Distanz, Höhenmetern, Dauer,
  Untergrund, Schwierigkeitsbadge und Bahnanreise.
- **`timeline()`** – Zeitschiene für Tagesabläufe.

---

## Die beiden Werkzeuge

Beide rechnen vollständig im Browser. Keine Datenübertragung, keine Speicherung, keine Cookies.

### Packlisten-Generator (`#packer`, `initPacker` in site.js)

Acht Fragen → vollständige Packliste mit Gramm-Angaben, Taschenempfehlung und Gesamtgewicht.
Die `data-key`-Werte der Schritte sind die Schnittstelle zur Logik:

| `data-key` | Werte |
|---|---|
| `nights` | `kurz`, `mittel`, `lang`, `woche` |
| `sleep` | `zelt`, `tarp`, `unterkunft` |
| `season` | `sommer`, `uebergang`, `kalt` |
| `cook` | `ja`, `nein` |
| `bike` | `gravel`, `mtb`, `trekking`, `rennrad` |
| `terrain` | `asphalt`, `gemischt`, `offroad` |
| `level` | `erste`, `einige`, `viele` |
| `crew` | `allein`, `zweit`, `gruppe` |

### Etappen- & Gewichts-Rechner (`#planner`, `initPlanner` in site.js)

Rechnet über ein **Zeitbudget**, nicht über Kilometer-Abzüge:

```
Tagesdistanz = (Zeitbudget − Zeit für die Anstiege) × Reisegeschwindigkeit

Zeitbudget   = Referenzstrecke ÷ 20 km/h × 1,2 × Erfahrung × Mehrtagesfaktor
Tempo        = 19 / 16 / 12,5 km/h je Untergrund, −1,8 % je kg über 6 kg (max. −20 %)
Anstiege     = Höhenmeter ÷ 500 (Stunden)
```

Das ist bewusst so gebaut: Ein früherer Ansatz zog Höhenmeter pauschal als Kilometer ab
(„9 km je 100 hm“) und landete bei jeder realistischen Eingabe auf dem Minimalwert. Begrenzend
ist auf Tour die Zeit, nicht die Strecke.

Eingabefelder: `data-ref`, `data-hm`, `data-days`, `data-load`, `data-bike`, `data-body`,
Selects `data-surface` und `data-level`, Ausgabe in `[data-planner-result]`.

---

## Vor dem Livegang anpassen

Alles, was mit `BITTE_` oder `DEIN_` beginnt, ist ein Platzhalter:

- `src/config.js` → `site.origin`, `site.publisher`, `site.contactEmail`
- `src/config.js` → `newsletter.action` (Double-Opt-in-fähiger Dienst)
- `src/config.js` → `affiliates.partners[*].tag` bzw. `.template`
- `src/pages/impressum.js` und `datenschutz.js` → alle `<span class="ph">`-Stellen
- `src/pages/affiliate-hinweis.js` → tatsächlich freigeschaltete Partnerprogramme

Die Rechtstexte sind Vorlagen und **ersetzen keine Rechtsberatung**. Vor dem Livegang
fachkundig prüfen lassen.

---

## Bilder

Zu jeder Seite gehört ein Bild unter demselben Pfad:

```
/taschen/satteltasche.html  →  src/assets/img/taschen/satteltasche.jpg
```

Fehlt die Datei, erscheint ein beschrifteter Platzhalter, der den erwarteten Dateinamen
nennt. `node build.js` listet am Ende alle noch offenen Bildslots auf – aktuell sind es
alle 45.

---

## Qualitätssicherung

`build.js` bricht ab, wenn ein interner Link ins Leere zeigt, eine in `nav.js` verlinkte
Seite fehlt oder ein Pfad doppelt vergeben ist. Zusätzlich wurde die gebaute Seite mit
Chromium geprüft: Navigation (Maus und Tastatur), beide Werkzeuge inklusive Plausibilität
der Rechenergebnisse, Newsletter-Validierung, Darstellung bei 390 px Breite und
JavaScript-Fehlerfreiheit.
