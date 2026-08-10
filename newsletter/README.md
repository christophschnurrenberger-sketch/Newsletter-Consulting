# Eigenes Newslettersystem

Ein vollständiges Newsletter-System in PHP – Anmeldung, Double-Opt-in, Redaktion,
eigener Versand, Tracking, Automationen, Bounce-Verarbeitung und Auswertung.
Ohne Composer, ohne externe Dienste, ohne monatliche Kosten: Es läuft auf jedem
Webhosting mit PHP 8 und einer Datenbank (SQLite genügt).

---

## 1. In fünf Minuten startklar

1. **Hochladen:** den Ordner `newsletter/` auf den Webspace kopieren
   (z. B. nach `/newsletter`).
2. **Prüfen:** `https://ihre-domain.de/newsletter/systemcheck.php` öffnen.
   Diese Seite läuft auf jedem Server und sagt Ihnen sofort, ob PHP-Version,
   Erweiterungen, Schreibrechte und alle hochgeladenen Dateien in Ordnung sind.
3. **Einrichten:** `https://ihre-domain.de/newsletter/install.php` im Browser öffnen
   und das Formular ausfüllen (Datenbank, Zugang, Absender).
4. **install.php löschen** – der Installer weist am Ende darauf hin.
5. **Anmelden:** `https://ihre-domain.de/newsletter/admin/login.php`
6. **Versandweg hinterlegen:** Einstellungen → Versandweg → SMTP mit einem echten
   Postfach Ihrer Domain (siehe Abschnitt 5).
7. **Cron-Job einrichten:** alle 5 Minuten (siehe Abschnitt 4).

**Voraussetzung:** PHP 8.0 oder neuer. Bei IONOS stellen Sie die Version unter
„Websites & Shops → Ihre Website → PHP verwalten“ ein.

Danach: Empfänger importieren oder das Anmeldeformular auf der Website nutzen,
Newsletter schreiben, Testmail verschicken, senden.

---

## 2. Was das System kann

| Bereich | Funktionen |
|---|---|
| Anmeldung | Formular auf der Startseite und eigene Landingpage, Double-Opt-in, Honeypot, Zeit- und Rate-Limits, MX-Prüfung der Domain |
| Empfänger | Suche, Filter, Sammelaktionen, Listen, CSV-Import/-Export, Einwilligungs-Protokoll, Sperrliste |
| Redaktion | **Baukasten mit Drag & Drop**, Platzhalter (`{{vorname}}` …), Live-Vorschau, Testversand, Textfassung automatisch – wahlweise auch direkt in HTML |
| Gestaltung | Eigene Vorlagen per Drag & Drop: Kopfzeile, Farben, Schrift, Breite, Footer; Bild-Upload mit Galerie |
| Versand | Eigener SMTP-Client, portionsweiser Versand über Cron, Tempolimits, Wiederholungen, Pause/Fortsetzen/Abbrechen, Planung |
| Messung | Öffnungen, Klicks je Link, Abmeldungen, Bounces, Verlaufsgrafik |
| Automation | Willkommensstrecken mit mehreren Schritten und Wartezeiten |
| Zustellbarkeit | List-Unsubscribe (auch One-Click nach RFC 8058), Bounce-Auswertung per POP3, Sperrliste |
| Recht | Double-Opt-in mit Protokoll, Abmeldelink in jeder Mail, Impressum im Footer, Selbstauskunft und Löschung für Empfänger |

---

## 3. Der Baukasten: Newsletter und Vorlagen selbst gestalten

Newsletter und Vorlagen stellen Sie per Drag & Drop aus Bausteinen zusammen –
ohne HTML-Kenntnisse. Aus den Bausteinen erzeugt das System anschließend
tabellenbasiertes E-Mail-HTML mit Inline-Stilen, wie es Outlook & Co. brauchen.

**Bausteine:** Überschrift · Textabsatz (fett, kursiv, Links, Aufzählungen) ·
Bild (mit Upload) · Knopf/Call-to-Action · Trennlinie · Abstand · Zwei Spalten ·
Linkleiste · eigenes HTML · Inhaltsplatzhalter (nur in Vorlagen).

**Bedienung**

* Baustein aus der linken Leiste in die Mail **ziehen** – oder anklicken, dann
  wird er unten angehängt.
* Vorhandene Bausteine am Griff `⠿` verschieben; auf dem Handy über die
  Pfeiltasten `↑ ↓` am Baustein.
* Texte schreiben Sie direkt in der Vorschau. Rechts stellen Sie Größe, Farbe,
  Ausrichtung und Abstände ein.
* **Platzhalter** wie `{{vorname}}` setzt die Auswahl links an der Schreibmarke
  ein – in Texten ebenso wie in Einstellungsfeldern.
* Zwei Spalten brechen auf dem Handy automatisch untereinander um.

**Eigene Vorlage bauen:** Vorlagen → „Neu im Baukasten“. Eine Vorlage ist der
Rahmen um jede Ausgabe. Der Baustein **„Inhalt der Ausgabe“** markiert die
Stelle, an der später der Text des jeweiligen Newsletters steht – er gehört in
jede Vorlage (fehlt er, wird er automatisch ergänzt). Kopfzeile, Farben,
Schriftart, Breite und Footer stellen Sie rechts unter „Gestaltung“ ein.
Abmeldelink und Impressum stehen immer im Footer; beide sind gesetzlich
vorgeschrieben und lassen sich nicht entfernen.

**Bilder:** Der Upload legt sie in `newsletter/uploads/` ab (max. 3 MB,
JPG/PNG/GIF/WebP). Der Ordner wird beim ersten Upload automatisch angelegt und
gegen die Ausführung von Programmcode gesperrt. Bilder in E-Mails müssen
öffentlich erreichbar sein – deshalb liegen sie dort und nicht im Datenordner.

**Umschalten:** Über die Knöpfe „Baukasten“ / „HTML“ wechseln Sie jederzeit.
Beim Wechsel zum Baukasten wandert vorhandenes HTML in einen Baustein
„Eigenes HTML“; beim Wechsel zu HTML bearbeiten Sie die erzeugte Fassung weiter.
Es geht nichts verloren.

---

## 4. Cron-Job einrichten (wichtig!)

Der Versand läuft **nicht** beim Klick auf „Senden“, sondern im Hintergrund.
Ohne Cron-Job bleibt die Warteschlange stehen.

**Per Kommandozeile (bevorzugt, z. B. IONOS „Cron-Jobs“):**

```
*/5 * * * * /usr/bin/php /pfad/zum/webspace/newsletter/cron/send.php >/dev/null 2>&1
0   * * * * /usr/bin/php /pfad/zum/webspace/newsletter/cron/bounces.php >/dev/null 2>&1
30  3 * * * /usr/bin/php /pfad/zum/webspace/newsletter/cron/wartung.php >/dev/null 2>&1
```

**Per URL (wenn nur Web-Cronjobs möglich sind):**

```
https://ihre-domain.de/newsletter/cron/send.php?token=<cron_token>
```

Den `cron_token` finden Sie in `config.php` und im Admin-Bereich unter
Einstellungen → Cron-Job.

Mehrere gleichzeitige Läufe sind unkritisch: `cron/send.php` sperrt sich selbst,
und jede einzelne Mail wird vor dem Versand gesperrt.

---

## 5. Zustellbarkeit: SPF, DKIM, DMARC

Eigener Versand heißt: Sie sind selbst für die Reputation verantwortlich. Drei
DNS-Einträge entscheiden darüber, ob Ihre Mails im Posteingang landen.

1. **Absenderadresse = echtes Postfach Ihrer Domain.**
   Also `newsletter@ihre-domain.de`, niemals `@gmail.com` oder `@web.de`.
2. **SPF** – erlaubt dem Mailserver Ihres Hosters, für Ihre Domain zu senden.
   Beispiel für IONOS (die genaue Zeile nennt Ihr Hoster):
   `v=spf1 include:_spf.perfora.net include:_spf.kundenserver.de ~all`
3. **DKIM** – signiert Ihre Mails. Wird beim Hoster im Mail-Menü aktiviert; der
   Hoster legt den DNS-Eintrag meist selbst an.
4. **DMARC** – sagt Empfängern, was bei Fehlern passieren soll. Sanfter Start:
   `v=DMARC1; p=none; rua=mailto:dmarc@ihre-domain.de`

**Warmlaufen:** Bei einer neuen Absenderadresse nicht sofort 5.000 Mails senden.
Erste Woche ~50/Tag, dann verdoppeln. Über Einstellungen → Tempo lässt sich das
Stundenlimit dafür einstellen.

**Prüfen:** Senden Sie eine Testmail an einen eigenen Gmail- und einen
Outlook-Account. In Gmail unter „Original anzeigen“ müssen SPF, DKIM und DMARC
auf „PASS“ stehen.

---

## 6. Versandwege

| Weg | Wann sinnvoll |
|---|---|
| **SMTP** (empfohlen) | Echtes Postfach beim Hoster. Zuverlässig, sauberer Absender, Fehler sind sichtbar. |
| **mail()** | Wenn kein SMTP-Zugang möglich ist. Funktioniert, aber Fehler bleiben oft unsichtbar. |
| **Testmodus** | Schreibt fertige `.eml`-Dateien nach `data/outbox/`, verschickt nichts. Ideal zum Ausprobieren. |

Typische SMTP-Zugänge (Beispiel IONOS): Server `smtp.ionos.de`, Port `587`,
STARTTLS, Benutzername = vollständige E-Mail-Adresse.

**Tempolimits beachten:** Viele Hoster erlauben nur einige hundert Mails pro
Stunde. Stellen Sie „Höchstens pro Stunde“ entsprechend ein – das System hält
sich automatisch daran und verteilt den Versand über mehrere Läufe.

---

## 7. Rechtliches (Deutschland/EU)

Das System ist so gebaut, dass die Pflichten technisch erfüllt sind – die
inhaltliche Verantwortung bleibt bei Ihnen.

* **Einwilligung (DSGVO Art. 6 Abs. 1 lit. a):** Anmeldungen werden erst nach
  Klick auf den Bestätigungslink aktiv (Double-Opt-in).
* **Nachweis (Art. 7 Abs. 1):** Jede Anmeldung, Bestätigung, Änderung und
  Abmeldung landet mit Zeitstempel, IP und Browserkennung im
  Einwilligungs-Protokoll (sichtbar in der Empfänger-Detailansicht).
* **Widerruf (Art. 7 Abs. 3):** Abmeldelink in jeder Mail, zusätzlich
  `List-Unsubscribe` für den Abmeldeknopf im E-Mail-Programm.
* **Auskunft und Löschung (Art. 15/17):** Empfänger können ihre Daten unter
  „Daten & Einstellungen“ selbst herunterladen und löschen lassen.
* **Impressumspflicht (§ 5 DDG):** Die Pflichtangaben aus den Einstellungen
  stehen im Footer jeder Mail.
* **Datensparsamkeit:** IP-Adressen werden auf Wunsch gekürzt gespeichert;
  unbestätigte Anmeldungen löscht die Wartung nach 14 Tagen automatisch.
* **Werbung an Bestandskunden (§ 7 Abs. 3 UWG)** ist eng begrenzt möglich –
  im Zweifel immer die Bestätigungsmail verschicken.

Ergänzen Sie Ihre Datenschutzerklärung um einen Abschnitt zum Newsletter:
welche Daten erhoben werden (E-Mail, Name, Anmelde-IP, Zeitpunkt), Zweck,
Rechtsgrundlage, Speicherdauer, Widerruf – und den Hinweis auf die Messung von
Öffnungen und Klicks, falls Sie diese aktiviert lassen.

> **Hinweis zur Messung:** Öffnungs- und Klickmessung ist mitwirkungsbedürftig.
> Sauber ist es, die Messung in der Einwilligung zu erwähnen („…um die Inhalte
> zu verbessern, messen wir Öffnungen und Klicks“). Wer das nicht möchte,
> schaltet beides in den Einstellungen ab – das System funktioniert vollständig
> auch ohne Tracking.

---

## 8. Aufbau

```
newsletter/
├── install.php              Einrichtung (danach löschen)
├── config.php               Zugangsdaten (wird erzeugt, nicht ins Git)
├── config.example.php       Beispiel dazu
│
├── anmelden.php             Landingpage mit Anmeldeformular
├── subscribe.php            nimmt Anmeldungen entgegen (Formular + AJAX)
├── bestaetigen.php          Double-Opt-in-Bestätigung
├── abmelden.php             Abmeldung (Seite, One-Click, Mailprogramm)
├── einstellungen.php        Selbstverwaltung: Daten, Listen, Auskunft, Löschung
├── archiv.php               öffentliches Archiv + Browser-Ansicht
├── track.php                Zählpixel und Klick-Weiterleitung
│
├── admin/                   Verwaltung (Anmeldung erforderlich)
│   ├── index.php            Übersicht
│   ├── kampagnen.php        Liste der Ausgaben
│   ├── kampagne.php         Editor mit Vorschau, Test und Versand
│   ├── statistik.php        Auswertung einer Ausgabe
│   ├── empfaenger.php       Empfängerliste, Sammelaktionen, Export
│   ├── empfaenger-detail.php Einzelansicht mit Einwilligungs-Protokoll
│   ├── import.php           CSV-Import
│   ├── listen.php           Verteiler
│   ├── automationen.php     Willkommensstrecken
│   ├── vorlagen.php         Design-Vorlagen (Baukasten oder HTML)
│   ├── upload.php           Bild-Upload für den Baukasten
│   ├── versand.php          Warteschlange steuern
│   ├── protokoll.php        Ereignisse, Rückläufer, Sperrliste
│   └── einstellungen.php    Absender, SMTP, Tempo, Texte, Zugänge
│
├── cron/
│   ├── send.php             Versand-Worker (alle 5 Minuten)
│   ├── bounces.php          Rücklaufpostfach (stündlich)
│   └── wartung.php          Aufräumen (täglich)
│
├── uploads/                 hochgeladene Bilder für den Baukasten
├── lib/                     Programmkern (siehe unten)
└── data/                    Datenbank, Sperrdatei, Testpostausgang (gesperrt)
```

**Programmkern (`lib/`)**

| Datei | Aufgabe |
|---|---|
| `bootstrap.php` | lädt Klassen, Konfiguration, Datenbank |
| `Config.php` / `Settings.php` | Konfigurationsdatei bzw. Einstellungen aus der Datenbank |
| `DB.php` / `Schema.php` | Datenbankzugriff (SQLite/MySQL) und Tabellen |
| `Mailer.php` | eigener SMTP-Client, MIME-Aufbau, mail()- und Testmodus |
| `Queue.php` | Warteschlange, Worker, Wiederholungen, Limits |
| `Campaigns.php` | Ausgaben: speichern, prüfen, kompilieren, starten, auswerten |
| `Blocks.php` | Baukasten: Bausteine prüfen, absichern und in E-Mail-HTML übersetzen |
| `Renderer.php` / `Templates.php` | Vorlagen, Platzhalter, Tracking-Einbau |
| `Subscribers.php` / `Lists.php` | Empfänger, Double-Opt-in, Listen, Import |
| `Automations.php` | Mailstrecken |
| `Bounces.php` | Rückläufer inkl. kleinem POP3-Client |
| `Tracking.php` / `Events.php` | Öffnungen, Klicks, Ereignisse |
| `Auth.php` / `Util.php` / `Log.php` | Anmeldung, Helfer, Protokoll |

---

## 9. Anmeldeformular auf eigenen Seiten einbauen

Auf der Startseite ist das Formular bereits eingebaut. Für weitere Seiten genügt
dieses Minimalformular (funktioniert auch ohne JavaScript):

```html
<form method="post" action="/newsletter/subscribe.php">
    <div style="position:absolute;left:-9999px;" aria-hidden="true">
        <input type="text" name="website" tabindex="-1" autocomplete="off">
    </div>
    <input type="hidden" name="quelle" value="blog">
    <input type="email" name="email" required placeholder="name@unternehmen.de">
    <label>
        <input type="checkbox" name="consent" value="1" required>
        Ja, ich möchte den Newsletter erhalten.
    </label>
    <button type="submit">Abonnieren</button>
</form>
```

Das Feld `quelle` taucht später in der Empfängerverwaltung auf – so sehen Sie,
welche Seite wie viele Anmeldungen bringt.

---

## 10. Wartung und Sicherung

* **Sichern:** `config.php` und den Ordner `data/` (bei MySQL: den
  Datenbank-Export). Ohne `secret` aus der `config.php` sind gespeicherte
  SMTP-Passwörter nicht mehr lesbar und alle Abmeldelinks ungültig.
* **Umziehen:** Dateien kopieren, `base_url` in `config.php` anpassen, fertig.
* **Aufräumen:** erledigt `cron/wartung.php`; Versandprotokolle lassen sich
  zusätzlich unter Versand → „Alte Einträge aufräumen“ kürzen.

---

## 11. Wenn etwas nicht klappt

**Erste Anlaufstelle bei jedem Problem:** `systemcheck.php` im Newsletter-Ordner.
Die Seite hat keine Abhängigkeiten und funktioniert auch dann, wenn der Rest streikt.

| Symptom | Ursache und Lösung |
|---|---|
| `install.php` zeigt nur die Kopfzeile, darunter bleibt alles leer | Ein PHP-Fehler bei abgeschalteter Fehleranzeige. Fast immer eine zu alte PHP-Version (nötig: 8.0+) oder eine unvollständig hochgeladene Datei. `systemcheck.php` nennt den Grund; seit dieser Fassung zeigt auch `install.php` den Fehler im Klartext an. |
| „config.php konnte nicht geschrieben werden“ | Der Ordner `newsletter/` ist schreibgeschützt. Per FTP die Rechte auf 755 setzen. |
| Bild-Upload schlägt fehl | Der Ordner `newsletter/uploads/` fehlt oder ist schreibgeschützt – per FTP anlegen und auf 755 setzen. |
| Bausteine lassen sich am Handy nicht ziehen | Touch-Geräte unterstützen kein Drag & Drop. Nutzen Sie die Pfeiltasten `↑ ↓` am Baustein. |
| „Cron-Job meldet sich nicht“ | Cron nicht eingerichtet oder falscher Pfad. Testweise `versand.php` → „Portion jetzt senden“. |
| Mails bleiben in der Warteschlange | Stundenlimit erreicht (Versand → „noch möglich“) oder Kampagne pausiert. |
| SMTP-Fehler „Verbindung fehlgeschlagen“ | Host/Port/Verschlüsselung prüfen; viele Hoster erlauben ausgehendes SMTP nur zum eigenen Server. |
| Mails landen im Spam | SPF/DKIM/DMARC prüfen (Abschnitt 5), Absenderadresse muss zur Domain passen, langsam warmlaufen. |
| Bestätigungslinks führen ins Leere | `base_url` in `config.php` stimmt nicht mit der echten Adresse überein. |
| Öffnungsraten wirken zu niedrig | Viele Programme laden Bilder nicht. Klicks sind die verlässlichere Kennzahl. |
| Fehler im Protokoll | Admin → Protokoll → Fehler; dort steht die genaue Meldung. |

---

## 12. Größenordnung

SQLite trägt problemlos einige zehntausend Empfänger. Für sehr große Verteiler
oder mehrere gleichzeitige Redakteure ist MySQL die bessere Wahl – umstellen
lässt sich das später über einen Export/Import.

Der Versand skaliert über `batch_size` (Mails pro Lauf) und den Cron-Takt:
50 Mails alle 5 Minuten sind 600 pro Stunde; bei aktiviertem Keepalive schafft
ein SMTP-Server meist deutlich mehr – die Grenze setzt in der Praxis der Hoster.
