# IT-Governance-Beratung – Website

Website für eine spezialisierte B2B-Beratung zu IT-Governance, IT-Prozessen und
Auditfähigkeit im Mittelstand. **39 Seiten** in fünf Bereichen, mit
Kontaktformular, Wissensbereich und einem Selbsttest, der ohne Datenübertragung
im Browser rechnet.

---

## Ansehen

**`index.html` im Browser öffnen.** Mehr ist nicht nötig – ausgeliefert wird
fertiges HTML mit relativen Verweisen. Die Seite läuft lokal per Doppelklick
genauso wie auf jedem Webspace, auch in einem Unterordner.

Einzige Ausnahme: Das Kontaktformular schickt an `kontakt-senden.php` und
braucht dafür einen Server mit PHP und Mailversand. Lokal öffnet sich die Seite
trotzdem, nur das Absenden geht ins Leere.

---

## Aufbau

Die Seite hat keinen dynamischen Inhalt – sie ist für alle Besucher gleich.
PHP dient nur als **Bau-Werkzeug**, damit Kopfzeile, Navigation, Randspalte und
Footer nicht 39-mal kopiert werden müssen.

```
/                       Die fertige Website – hier liegt index.html
  index.html            Startseite
  leistungen/*.html     11 Leistungsseiten + Übersicht
  themen/*.html         10 Themenseiten + Übersicht
  wissen/*.html         6 Leitfäden + Übersicht
  vorgehen.html, fuer-wen.html, preise.html, ueber-mich.html,
  faq.html, kontakt.html, impressum.html, datenschutz.html
  assets/site.css       Ein Stylesheet, keine Bibliothek
  assets/site.js        Icons, Navigation, FAQ, Formular, Selbsttest
  kontakt-senden.php    Einziges PHP im Betrieb: nimmt das Formular entgegen
  robots.txt, sitemap.xml

src/                    Die Quellen, aus denen gebaut wird
  index.php             eine Datei je Seite, enthält nur den Inhalt
  leistungen/, themen/, wissen/
  partials/config.php           Grunddaten und Navigationsbaum
  partials/header.php           <head>, Servicezeile, Mega-Menü, Seitenkopf
  partials/footer.php           Handlungsband, Footer, Skripte
  partials/aside.php            Randspalte der Unterseiten
  partials/related.php          Verweiskacheln „Passt dazu“
  partials/rechtshinweis.php    Abgrenzung zur Rechtsberatung
  partials/kette.php            Waagerechte Schrittfolge
  partials/governance-haus.php  Das Governance-Haus als SVG

tools/build.php         Baut aus src/ alle HTML-Seiten und sitemap.xml
```

### Ändern und neu bauen

Bearbeitet wird immer `src/`. Die `.html`-Dateien werden erzeugt und beim
nächsten Lauf überschrieben – in ihrem Kopf steht ein entsprechender Hinweis.

```bash
php tools/build.php     # baut alle Seiten und sitemap.xml neu
```

### Eine neue Unterseite anlegen

1. Datei unter `src/<bereich>/<name>.php` anlegen (eine vorhandene Seite als
   Vorlage nehmen).
2. Eintrag in `src/partials/config.php` im Navigationsbaum `$NAV` ergänzen –
   damit erscheint sie automatisch im Mega-Menü, in der Randspalte, im Footer
   und in der Sitemap.
3. `php tools/build.php` ausführen.

Seiten außerhalb des Navigationsbaums (z. B. `impressum.php`) stehen in der
Liste `$pages` am Anfang von `tools/build.php`.

---

## Vor dem Onlinestellen zu erledigen

| Punkt | Wo | Was zu tun ist |
|---|---|---|
| **Domain** | `src/partials/config.php` → `$SITE['domain']` | Endgültige Domain eintragen (ohne Schrägstrich am Ende) |
| **Domain** | `robots.txt` | Sitemap-Adresse anpassen |
| **E-Mail** | `src/partials/config.php`, `kontakt-senden.php` | Echte Postfachadresse eintragen; sie muss auf der eigenen Domain liegen und per SPF/DKIM abgesichert sein |
| **Umsatzsteuer** | `src/impressum.php` | USt-IdNr. eintragen **oder** auf Kleinunternehmerregelung umstellen – dann auch die Preisseite anpassen, dort steht „zzgl. USt.“ |
| **Porträtfoto** | `src/ueber-mich.php` | Foto als `assets/portrait.jpg` ablegen und die auskommentierten drei Zeilen aktivieren |
| **Markenname** | `src/partials/config.php` → `$SITE['name']` | Steht nur an dieser einen Stelle. Wird aus der Einzelberatung später eine Boutique mit eigenem Namen, ist das eine Zeile Arbeit |
| **Rechtsprüfung** | `src/impressum.php`, `src/datenschutz.php` | Beide Texte sind sorgfältig erstellt, ersetzen aber keine anwaltliche Prüfung |
| **Preise** | `src/preise.php` und die Leistungsseiten | Die Rahmen sind Marktschätzungen für den deutschen Mittelstand – vor der Veröffentlichung mit der eigenen Kalkulation abgleichen |

---

## Grundsätze, nach denen die Seite gebaut ist

* **Keine externen Aufrufe.** Keine Schriften von Google, kein CDN, kein
  Analysewerkzeug, keine Karten, keine Videos. Das ist bei einer Beratung, die
  anderen Governance beibringt, kein Detail – und außerdem der Grund, warum die
  Seite auch in strengen Firmennetzen vollständig aussieht.
* **Kein Cookie-Banner nötig**, weil keine Cookies gesetzt werden.
* **Keine erfundenen Referenzen.** Es gibt bewusst keine Kundenlogos, keine
  Testimonials und keine Fallstudien – bis echte vorliegen und freigegeben sind.
  Erfundene Referenzen sind in diesem Marktsegment ein K.-o.-Kriterium.
* **Preise stehen offen auf der Seite.** Das filtert Anfragen vor und spart
  beiden Seiten Zeit.
* **Abgrenzung zur Rechtsberatung** auf jeder regulatorischen Seite, im Footer
  und im Impressum – als Positionierung, nicht als Kleingedrucktes.

---

## Barrierefreiheit und Technik

* Semantisches HTML, Sprungmarke zum Inhalt, sichtbarer Fokus
* Navigation mit Tastatur bedienbar (`aria-expanded`, `aria-controls`)
* Bewegung wird bei `prefers-reduced-motion` abgeschaltet
* Eigene Druckformate: Wissensseiten lassen sich sauber ausdrucken
* Keine Abhängigkeit von JavaScript für Inhalte – ohne JS fehlen nur Icons,
  Akkordeons und der Selbsttest
