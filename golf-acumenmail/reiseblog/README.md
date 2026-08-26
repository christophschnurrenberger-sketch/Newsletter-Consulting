# Zuhause in der Welt – Reiseblog

Ein generischer, mehrseitiger Reiseblog als statisches HTML – gebaut als
Grundgerüst zum Selbst-Befüllen. Zusätzlich als **Tippgeber** angelegt, damit
du später sauber **Affiliate-Marketing** (z. B. Amazon-Partnerprogramm)
betreiben kannst.

## Seiten
| Datei | Inhalt |
|-------|--------|
| `index.html` | Startseite (Hero, Reiseziele, Tipps, Ausrüstung, Newsletter, Instagram) |
| `reiseziele.html` | Reiseziele nach Region (Indischer Ozean & Afrika, Asien & Orient, Amerika, Europa & Mittelmeer) |
| `reisetipps.html` | Reisetipps nach Kategorie (Planung, Packen, Budget …) |
| `ausruestung.html` | **Affiliate-Hub**: Produkt-Empfehlungen mit Werbelinks |
| `blog.html` | Blog-Übersicht mit allen Reiseberichten |
| `reise-<land>.html` | 14 einzelne Reiseberichte (Seychellen, Malediven, Namibia, Südafrika, Dubai, Thailand, USA Westküste, Costa Rica, Mexiko/Yucatán, Kreta, Mallorca, Sardinien, Portugal, Sizilien) – je mit Steckbrief, Highlights, Tipps, Empfehlungen |
| `ueber-mich.html` | Über-mich-Seite |
| `kontakt.html` | Kontaktformular (Demo) |
| `impressum.html` / `datenschutz.html` | Rechtstexte (Vorlagen mit Platzhaltern) |
| `assets/style.css` | zentrales Stylesheet (Farben, Layout) |
| `assets/main.js` | Mobile-Menü & Formular-Demo |

## Design
- **Farbwelt:** warmes Sand/Creme + tiefes Ozean-Türkis (`#14524E`) + Terrakotta-Akzent (`#D9744F`)
- **Schriften:** System-Serif (Überschriften) + System-Sans (Text) – bewusst **keine Google Fonts / externen CDNs** (DSGVO-freundlich, lädt schnell)
- **Responsive** inkl. mobilem Burger-Menü

## Ansehen
Einfach `index.html` im Browser öffnen – es ist kein Server nötig.

## Nächste Schritte
1. **Fotos einsetzen:** farbige `.ph`-Platzhalter durch `<img>` ersetzen (siehe `images/README.md`).
2. **Texte anpassen:** Reiseziele, Beiträge und „Über mich" mit deinen echten Reisen füllen.
3. **Affiliate-Links eintragen:** In `ausruestung.html` und in den Artikeln stehen Buttons mit `href="#"` und `rel="sponsored nofollow noopener"`. Ersetze `#` durch deine echten Partner-Links (z. B. Amazon-Associates). Das `rel`-Attribut bitte beibehalten.
4. **Rechtstexte füllen:** Alle `[Platzhalter]` in `impressum.html` und `datenschutz.html` ersetzen. Für Deutschland Pflicht. (Keine Rechtsberatung – im Zweifel prüfen lassen.)
5. **Newsletter/Kontakt aktivieren:** Die Formulare sind aktuell Demos. Binde deinen Dienst ein (z. B. Brevo, Mailchimp) oder ein Formular-Backend (z. B. Formspree).
6. **Domain & Canonical:** `https://www.deine-domain.de/` in allen Dateien durch deine echte Domain ersetzen.

## Neue Reiseziele hinzufügen / Texte ändern
Blog, Reiseziele-Übersicht und Weltkarte werden aus **einer** Datenbasis erzeugt –
so bleiben alle Seiten automatisch synchron.

| Datei | Inhalt |
|-------|--------|
| `tools/ziele.py` | **Texte** der Reiseberichte (Route, Highlights, Kosten, FAQ …) |
| `tools/karte.py` | **Geo-Daten** für die Weltkarte (Marker, Zoom-Ausschnitt, Regions-Pins) |
| `tools/stories.py` | **Erlebnisberichte** (persönliche Tagebuch-Artikel wie `dubai-3-tage.html`) |
| `tools/generate.py` | Layout/HTML-Vorlage + Buchungs-Boxen (`BOOK`) |

### Neues Reiseziel anlegen
1. In `tools/ziele.py` einen `ZIELE.append({ ... })`-Block kopieren und anpassen.
2. In `tools/generate.py` im `BOOK`-Block einen Eintrag mit gleichem `slug` ergänzen
   (Flug-/Hotel-/Mietwagen-Texte für die Affiliate-Boxen).
3. In `tools/karte.py` einen Eintrag mit gleichem `slug` ergänzen (Marker + Regionen).
4. `python3 tools/generate.py` ausführen.

Danach erscheint das Ziel automatisch in **`reise-<slug>.html`**, in der Übersicht
**`reiseziele.html`**, im **`blog.html`** und auf der **Weltkarte**. Fehlt der
Karten-Eintrag, meldet das Skript das beim Generieren; Ziele ohne Regionszuordnung
landen in der Übersicht unter „Weitere Reiseziele".

### Neuen Erlebnisbericht anlegen
1. HTML-Seite anlegen (am einfachsten `dubai-3-tage.html` kopieren und umschreiben).
2. In `tools/stories.py` einen Eintrag ergänzen (Datei, Titel, Ziel-Slug, Teaser …).
3. Optional in `tools/karte.py` einzelne Regions-Pins des Ziels auf die neue Seite
   zeigen lassen (4. Wert im `regions`-Tupel).
4. `python3 tools/generate.py` – der Bericht erscheint dann in `blog.html` und als
   Hinweis bei seinem Ziel in `reiseziele.html`.

> `assets/map-trips.js` wird automatisch erzeugt und sollte nicht von Hand
> bearbeitet werden.

## Affiliate-Hinweis (wichtig)
Affiliate-Links müssen als Werbung gekennzeichnet sein. Umgesetzt ist bereits:
- Sichtbarer Transparenz-Hinweis auf der Ausrüstungs-Seite und in Artikeln
- `*`-Kennzeichnung an jedem Werbelink
- `rel="sponsored nofollow"` an den Links
- Abschnitt zum Amazon-Partnerprogramm in der Datenschutzerklärung
