# Sattelfest – Bikepacking-Einstiegsguide

Statische Website für Bikepacking-Einsteiger. Aufgebaut wie ein Ratgeber-Portal:
sechs Rubriken mit Dropdown-Navigation, ausführliche Artikel, Newsletter-Anbindung,
zwei interaktive Werkzeuge und Affiliate-Integration.

**Kein Framework, keine npm-Abhängigkeiten, keine CDN-Aufrufe.** Ein Node-Skript rendert
alle Seiten nach `dist/`. Dieser Ordner wird per FTP auf einen ganz normalen Webspace
geladen – es wird kein Node, kein PHP und keine Datenbank auf dem Server benötigt.

Gleiche Architektur wie das Schwesterprojekt Fairway54 (Golf-Einstiegsguide).

---

## ⚠️ Aktueller Stand: unfertig

**Der Build läuft noch nicht durch.** 41 von 53 Quelldateien sind fertig,
zwölf Seiten fehlen noch. `node build.js` bricht deshalb mit
`Cannot find module` ab. Das ist erwartet – siehe „Was noch fehlt".

### Fertig (29 Inhaltsseiten + komplette Infrastruktur)

| Bereich | Status |
|---|---|
| `build.js` | ✅ Generator inkl. Linkprüfung, Sitemap, robots.txt, .htaccess |
| `src/config.js` | ✅ Domain, Newsletter-Endpunkt, Affiliate-Partner (Platzhalter) |
| `src/nav.js` | ✅ Navigationsbaum, 6 Rubriken, 40 Inhaltsseiten definiert |
| `src/layout.js` | ✅ Header mit Mega-Dropdowns, Footer, SEO-Meta, JSON-LD |
| `src/components.js` | ✅ Bausteine inkl. `weightList`, `routeCard`, `timeline` |
| `src/assets/site.css` | ✅ Vollständiges Stylesheet (Schiefer-Blau/Teal-Palette) |
| `src/assets/site.js` | ✅ Navigation, Newsletter-Formular, **beide Werkzeuge** |
| `src/data/shops.js` | ✅ Kategorie-URLs für Produktempfehlungen |
| **Einstieg** | ✅ 6 von 6 Seiten |
| **Taschen** | ✅ 8 von 8 Seiten |
| **Ausrüstung** | ✅ 8 von 8 Seiten |
| **Routen & Planung** | ✅ 7 von 7 Seiten |
| **Unterwegs** | 🟡 4 von 6 Seiten |
| **Service / Recht** | ❌ 0 von 8 Seiten |

### Was noch fehlt

Diese zwölf Dateien sind in `build.js` bereits eingetragen, aber noch nicht geschrieben:

```
src/pages/index.js                        Startseite
src/pages/404.js                          Fehlerseite
src/pages/unterwegs/allein-oder-gruppe.js
src/pages/unterwegs/sicherheit-notfall.js
src/pages/tools/packlisten-generator.js   Markup für #packer (Logik in site.js fertig)
src/pages/tools/etappen-rechner.js        Markup für #planner (Logik in site.js fertig)
src/pages/newsletter.js
src/pages/faq.js
src/pages/ueber-uns.js
src/pages/impressum.js
src/pages/datenschutz.js
src/pages/affiliate-hinweis.js
```

**Wichtig für die Werkzeuge:** Die komplette JavaScript-Logik beider Rechner steht
bereits in `src/assets/site.js`. Es fehlt nur das HTML-Gerüst auf den Seiten:

- **Packlisten-Generator** (`initPacker`) braucht ein Element `id="packer"` mit
  `.quiz-progress`, acht `.quiz-step`-Blöcken (`data-key`: `nights`, `sleep`,
  `season`, `cook`, `bike`, `terrain`, `level`, `crew`) mit `.quiz-option`-Buttons
  (`data-value`) und einem `[data-result]`-Container.
- **Etappen-Rechner** (`initPlanner`) braucht ein Element `id="planner"` mit
  Eingabefeldern `data-ref`, `data-hm`, `data-days`, `data-load`, `data-bike`,
  `data-body`, Selects `data-surface` / `data-level` und `[data-planner-result]`.

---

## Schnellstart (sobald die fehlenden Seiten existieren)

```bash
node build.js     # baut alles nach dist/
```

Danach **`dist/index.html` doppelklicken** – die Seite öffnet sich im Browser und
funktioniert vollständig, inklusive Navigation und beider Werkzeuge. Ein lokaler Server
ist nicht nötig, weil intern relativ verlinkt wird.

```bash
python3 -m http.server 8080 --directory dist   # optional, um .htaccess zu testen
```

---

## Projektstruktur

```
build.js                 Generator (führt alles zusammen, prüft Links)
src/
  config.js              ⚙️  Domain, Basispfad, Newsletter-Endpunkt, Affiliate-IDs
  nav.js                 🧭  Navigationsbaum – Quelle für Menü, Footer, Sitemap
  layout.js              HTML-Gerüst: Header mit Mega-Dropdowns, Footer, SEO-Meta
  components.js          Bausteine: Tabellen, Callouts, Packlisten, Routenkarten, FAQ …
  data/shops.js          🛒  Ziel-URLs der Produktempfehlungen
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

---

## Vor dem Livegang anpassen

Alles, was mit `BITTE_` oder `DEIN_` beginnt, ist ein Platzhalter:

- `src/config.js` → `site.origin`, `site.publisher`, `site.contactEmail`
- `src/config.js` → `newsletter.action` (Double-Opt-in-fähiger Dienst)
- `src/config.js` → `affiliates.partners[*].tag` bzw. `.template`
- `src/pages/impressum.js` und `datenschutz.js` → Betreiberdaten (noch zu erstellen)

---

## Bilder

Zu jeder Seite gehört ein Bild unter demselben Pfad:

```
/taschen/satteltasche.html  →  src/assets/img/taschen/satteltasche.jpg
```

Fehlt die Datei, erscheint ein beschrifteter Platzhalter, der den erwarteten Dateinamen
nennt. `node build.js` listet am Ende alle noch offenen Bildslots auf.
