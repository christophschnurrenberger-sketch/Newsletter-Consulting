# Zuhause in der Welt – Reiseblog

Ein generischer, mehrseitiger Reiseblog als statisches HTML – gebaut als
Grundgerüst zum Selbst-Befüllen. Zusätzlich als **Tippgeber** angelegt, damit
du später sauber **Affiliate-Marketing** (z. B. Amazon-Partnerprogramm)
betreiben kannst.

## Seiten
| Datei | Inhalt |
|-------|--------|
| `index.html` | Startseite (Hero, Reiseziele, Tipps, Ausrüstung, Newsletter, Instagram) |
| `reiseziele.html` | Reiseziele nach Region (Europa, Asien, Natur …) |
| `reisetipps.html` | Reisetipps nach Kategorie (Planung, Packen, Budget …) |
| `ausruestung.html` | **Affiliate-Hub**: Produkt-Empfehlungen mit Werbelinks |
| `blog.html` | Blog-Übersicht mit Beitragsraster |
| `blog-japan-kirschbluete.html` | Beispiel-Artikel (Vorlage) inkl. Empfehlungs-Boxen |
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

## Affiliate-Hinweis (wichtig)
Affiliate-Links müssen als Werbung gekennzeichnet sein. Umgesetzt ist bereits:
- Sichtbarer Transparenz-Hinweis auf der Ausrüstungs-Seite und in Artikeln
- `*`-Kennzeichnung an jedem Werbelink
- `rel="sponsored nofollow"` an den Links
- Abschnitt zum Amazon-Partnerprogramm in der Datenschutzerklärung
