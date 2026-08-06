# Eigenes Newslettersystem

Ein vollständiges Newsletter-System in PHP – Anmeldung, Double-Opt-in, Redaktion,
eigener Versand, Tracking, Automationen, Bounce-Verarbeitung und Auswertung.
Ohne Composer, ohne externe Dienste, ohne monatliche Kosten: Es läuft auf jedem
Webhosting mit PHP 8 und einer Datenbank (SQLite genügt).

---

## 1. In fünf Minuten startklar

1. **Hochladen:** den Ordner `newsletter/` auf den Webspace kopieren
   (z. B. nach `/newsletter`).
2. **Einrichten:** `https://ihre-domain.de/newsletter/install.php` im Browser öffnen
   und das Formular ausfüllen (Datenbank, Zugang, Absender).
3. **install.php löschen** – der Installer weist am Ende darauf hin.
4. **Anmelden:** `https://ihre-domain.de/newsletter/admin/login.php`
5. **Versandweg hinterlegen:** Einstellungen → Versandweg → SMTP mit einem echten
   Postfach Ihrer Domain (siehe Abschnitt 4).
6. **Cron-Job einrichten:** alle 5 Minuten (siehe Abschnitt 3).

Danach: Empfänger importieren oder das Anmeldeformular auf der Website nutzen,
Newsletter schreiben, Testmail verschicken, senden.

---

## 2. Was das System kann

| Bereich | Funktionen |
|---|---|
| Anmeldung | Formular auf der Startseite und eigene Landingpage, Double-Opt-in, Honeypot, Zeit- und Rate-Limits, MX-Prüfung der Domain |
| Empfänger | Suche, Filter, Sammelaktionen, Listen, CSV-Import/-Export, Einwilligungs-Protokoll, Sperrliste |
| Redaktion | HTML-Vorlagen, Platzhalter (`{{vorname}}` …), Live-Vorschau, Testversand, Textfassung automatisch |
| Versand | Eigener SMTP-Client, portionsweiser Versand über Cron, Tempolimits, Wiederholungen, Pause/Fortsetzen/Abbrechen, Planung |
| Messung | Öffnungen, Klicks je Link, Abmeldungen, Bounces, Verlaufsgrafik |
| Automation | Willkommensstrecken mit mehreren Schritten und Wartezeiten |
| Zustellbarkeit | List-Unsubscribe (auch One-Click nach RFC 8058), Bounce-Auswertung per POP3, Sperrliste |
| Recht | Double-Opt-in mit Protokoll, Abmeldelink in jeder Mail, Impressum im Footer, Selbstauskunft und Löschung für Empfänger |

---

## 3. Cron-Job einrichten (wichtig!)

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

## 4. Zustellbarkeit: SPF, DKIM, DMARC

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

## 5. Versandwege

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

## 6. Rechtliches (Deutschland/EU)

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

## 7. Aufbau

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
│   ├── vorlagen.php         Design-Vorlagen
│   ├── versand.php          Warteschlange steuern
│   ├── protokoll.php        Ereignisse, Rückläufer, Sperrliste
│   └── einstellungen.php    Absender, SMTP, Tempo, Texte, Zugänge
│
├── cron/
│   ├── send.php             Versand-Worker (alle 5 Minuten)
│   ├── bounces.php          Rücklaufpostfach (stündlich)
│   └── wartung.php          Aufräumen (täglich)
│
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
| `Renderer.php` / `Templates.php` | Vorlagen, Platzhalter, Tracking-Einbau |
| `Subscribers.php` / `Lists.php` | Empfänger, Double-Opt-in, Listen, Import |
| `Automations.php` | Mailstrecken |
| `Bounces.php` | Rückläufer inkl. kleinem POP3-Client |
| `Tracking.php` / `Events.php` | Öffnungen, Klicks, Ereignisse |
| `Auth.php` / `Util.php` / `Log.php` | Anmeldung, Helfer, Protokoll |

---

## 8. Anmeldeformular auf eigenen Seiten einbauen

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

## 9. Wartung und Sicherung

* **Sichern:** `config.php` und den Ordner `data/` (bei MySQL: den
  Datenbank-Export). Ohne `secret` aus der `config.php` sind gespeicherte
  SMTP-Passwörter nicht mehr lesbar und alle Abmeldelinks ungültig.
* **Umziehen:** Dateien kopieren, `base_url` in `config.php` anpassen, fertig.
* **Aufräumen:** erledigt `cron/wartung.php`; Versandprotokolle lassen sich
  zusätzlich unter Versand → „Alte Einträge aufräumen“ kürzen.

---

## 10. Wenn etwas nicht klappt

| Symptom | Ursache und Lösung |
|---|---|
| „Cron-Job meldet sich nicht“ | Cron nicht eingerichtet oder falscher Pfad. Testweise `versand.php` → „Portion jetzt senden“. |
| Mails bleiben in der Warteschlange | Stundenlimit erreicht (Versand → „noch möglich“) oder Kampagne pausiert. |
| SMTP-Fehler „Verbindung fehlgeschlagen“ | Host/Port/Verschlüsselung prüfen; viele Hoster erlauben ausgehendes SMTP nur zum eigenen Server. |
| Mails landen im Spam | SPF/DKIM/DMARC prüfen (Abschnitt 4), Absenderadresse muss zur Domain passen, langsam warmlaufen. |
| Bestätigungslinks führen ins Leere | `base_url` in `config.php` stimmt nicht mit der echten Adresse überein. |
| Öffnungsraten wirken zu niedrig | Viele Programme laden Bilder nicht. Klicks sind die verlässlichere Kennzahl. |
| Fehler im Protokoll | Admin → Protokoll → Fehler; dort steht die genaue Meldung. |

---

## 11. Größenordnung

SQLite trägt problemlos einige zehntausend Empfänger. Für sehr große Verteiler
oder mehrere gleichzeitige Redakteure ist MySQL die bessere Wahl – umstellen
lässt sich das später über einen Export/Import.

Der Versand skaliert über `batch_size` (Mails pro Lauf) und den Cron-Takt:
50 Mails alle 5 Minuten sind 600 pro Stunde; bei aktiviertem Keepalive schafft
ein SMTP-Server meist deutlich mehr – die Grenze setzt in der Praxis der Hoster.
