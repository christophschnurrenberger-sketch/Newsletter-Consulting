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
   Postfach Ihrer Domain (siehe Abschnitt 8).
7. **Cron-Job einrichten:** alle 5 Minuten (siehe Abschnitt 7).

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
| Redaktion | **Baukasten mit Drag & Drop**, Rückgängig (Strg+Z), Vorschau für Rechner und Handy, Platzhalter (`{{vorname}}` …), Testversand, Textfassung automatisch – wahlweise auch direkt in HTML |
| Gestaltung | Eigene Vorlagen per Drag & Drop: Kopfzeile, Farben, Schrift, Breite, Footer; Bild-Upload mit Galerie, Zuschneiden im Browser, eigene Bausteine zum Wiederverwenden |
| Versand | Eigener SMTP-Client, portionsweiser Versand über Cron, Tempolimits, Wiederholungen, Pause/Fortsetzen/Abbrechen, Planung |
| Messung | Öffnungen, Klicks je Link, Abmeldungen, Bounces, Verlaufsgrafik |
| Automation | **Ablauf-Baukasten mit Drag & Drop**: warten, senden, Bedingungen („hat geöffnet?“) mit Ja-/Nein-Zweigen, Aktionen |
| Textassistent | Optional: Vorschläge per Klick (umformulieren, kürzen, korrigieren, Betreffzeilen) – nur mit eigenem Schlüssel, ab Werk aus |
| Zugänge | Mehrere Benutzer mit drei Rollen (Administrator, Redakteur, Betrachter), Sperren statt Löschen |
| Mehrere Marken | Eine Installation für mehrere Websites: Vorlage mit eigenem Namen, Impressum und Absender |
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

**Eine neue Vorlage enthält nur Kopfzeile und Footer** – dazwischen steht der
Baustein „Inhalt der Ausgabe“, der die Stelle für den späteren Newsletter-Text
markiert. Kein Beispieltext, keine vorgefertigte Gestaltung: Alles dazwischen
bauen Sie selbst. Das gilt für beide Wege, „Neu im Baukasten“ und „Neu als HTML“.

Die Vorschau zeigt deshalb an der Stelle des Inhalts eine gestrichelte Fläche.
Über „Mit Beispieltext ansehen“ lässt sich einmalig ein Musternewsletter
einblenden, wenn Sie Schrift und Farben beurteilen wollen.

**Die drei Bausteine einer Vorlage:** Kopfzeile, „Inhalt der Ausgabe“
und Footer. Sie lassen sich einstellen und durch eigene Bausteine ergänzen –
etwa eine feste Grußformel zwischen Inhalt und Footer.

**Kopfzeile bleibt oben, Footer bleibt unten.** Alles, was Sie hinzufügen,
liegt dazwischen – egal ob angeklickt oder hineingezogen. Ein Baustein unter
den Pflichtangaben wäre auch in der fertigen Mail unter dem Abmeldelink
gelandet; das ist nie gewollt.

* **Kopfzeile:** Logo-Quadrat mit Kürzel oder **Wortmarke als Text**, bei der ein
  Teil farblich hervorgehoben wird (etwa die „54“ in „Fairway54“), dazu ein Claim.
* **Footer:** eigene Farben und ein freier Hinweis über dem Impressum – dort
  gehört bei Affiliate-Newslettern die Pflichtangabe zu Partnerlinks hin.
  Impressum, Abmeldelink, Datenschutz und „Im Browser ansehen“ stecken fest darin.
* Fehlt der Footer-Baustein, hängt das System Impressum und Abmeldelink trotzdem
  an – beides ist gesetzlich vorgeschrieben.

Für die ganze Mail gilt weiterhin rechts unter „Gestaltung“:

* **Schrift für Überschriften** getrennt vom Fließtext (z. B. Serifen für
  Überschriften, Grotesk für den Text).
* **Farben** für Seitenhintergrund, Inhaltsfläche und Rahmen sowie die Maße.

Damit lässt sich auch ein fremdes Website-Design im Baukasten nachbauen; HTML
brauchen Sie nur noch für Sonderfälle.

**Bedienung**

* Baustein aus der linken Leiste in die Mail **ziehen** – oder anklicken, dann
  wird er unten angehängt. Das Ziehen läuft über Zeigerereignisse und
  funktioniert deshalb in jedem Browser und auch auf dem Handy.
* Vorhandene Bausteine ziehen Sie am **Kopf des Bausteins**, nicht nur am
  Griffsymbol.
* **Der Baukasten speichert von selbst.** Kurz nach jeder Änderung geht der
  Stand zum Server, oben steht dann „Gespeichert um …“, und die Vorschau lädt
  sich neu. Der Knopf „Speichern“ bleibt trotzdem – er ist der sichere Weg,
  wenn die Verbindung mal hakt.
* **Texte schreiben Sie direkt im Baustein:** Textabsatz, Überschrift und
  Knopfbeschriftung lassen sich anklicken und überschreiben.
* Rechts stehen oben die Einstellungen des angeklickten Bausteins, darunter
  die Gestaltung der ganzen Mail (aufklappbar nach Seite, Kopfzeile, Footer).
* Vorhandene Bausteine am Griff `⠿` verschieben; auf dem Handy über die
  Pfeiltasten `↑ ↓` am Baustein.
* Texte schreiben Sie direkt in der Vorschau. Rechts stellen Sie Größe, Farbe,
  Ausrichtung und Abstände ein.
* **Platzhalter** wie `{{vorname}}` setzt die Auswahl links an der Schreibmarke
  ein – in Texten ebenso wie in Einstellungsfeldern.
* **Zwei Spalten:** Unter jeder Spalte stehen „Text schreiben“ und „Bild
  einsetzen“ sowie eine Auswahl für Überschrift, Knopf, Trennlinie und
  Abstand. Man muss also nichts hineinziehen – ein Klick genügt, danach
  schreibt man direkt in der Spalte. Auf dem Handy brechen die Spalten
  automatisch untereinander um.

**Rückgängig:** `Strg+Z` nimmt den letzten Schritt zurück, `Strg+Umschalt+Z`
stellt ihn wieder her – oder die Pfeile ↶ ↷ oben am Baukasten. Beim Schreiben
wird nicht jeder Buchstabe zu einem Schritt: Erst nach einer kurzen Pause
wandert der Stand in den Verlauf, ein Rückgängig nimmt also einen Satz zurück
und nicht ein Zeichen. Gemerkt werden die letzten 60 Schritte; nach dem
Neuladen der Seite fängt der Verlauf von vorn an.

**Rechner oder Handy:** Über der Vorschau schalten Sie zwischen beiden um. Am
Handy wird die Vorschau auf 375 Pixel gestellt – die Mail greift dann auf
dieselben Regeln zurück wie später im Postfach, Spalten brechen also wirklich
um. Die Wahl bleibt über Seitenwechsel hinweg bestehen.

**Eigene Bausteine:** Der Stern ☆ am Baustein sichert ihn unter einem Namen.
Danach steht er links unter „Eigene Bausteine" und lässt sich mit einem Klick
in jeden Newsletter einsetzen – praktisch für eine Grußformel, einen
Produktkasten oder einen Hinweis, den Sie immer wieder brauchen. Gesichert wird
auf dem Server, also für das ganze Team und über jeden Rechnerwechsel hinweg.
Zwei Spalten samt Inhalt lassen sich als Ganzes sichern.

**Bilder zuschneiden:** „Zuschneiden" öffnet den Ausschnitt direkt im Browser –
Rechteck aufziehen, wahlweise mit festem Seitenverhältnis (1:1, 4:3, 3:2, 16:9),
und auf eine Zielbreite verkleinern. Das Ergebnis wird als **neue** Datei
gespeichert; das Ausgangsbild bleibt, wie es war. Beim Hochladen werden zu
große Bilder automatisch auf 1400 Pixel Breite gebracht – ein Handyfoto mit
4000 Pixeln läuft sonst in die 3-MB-Grenze und macht die Mail unnötig schwer.
Bilder von fremden Servern lassen sich nicht zuschneiden, das verbietet der
Browser; laden Sie sie vorher hoch.

**Der Weg zu einer neuen Ausgabe:** Newsletter → „Neuen Newsletter schreiben“ zeigt
zuerst die vorhandenen Designs als Kacheln mit echter Vorschau. Ein Klick legt die
Ausgabe in diesem Design an. Wer lieber mit leerer Fläche beginnt, wählt „Ohne
Beispieltext“. Gibt es nur ein Design, entfällt die Auswahl.

**Design später wechseln:** Im Editor rechts unter „Vorlage & Liste“. Der Wechsel
betrifft nicht nur den Rahmen, sondern auch den geschriebenen Inhalt – Schriften,
Text-, Link- und Knopffarben ziehen mit. Farben, die Sie an einem Baustein selbst
gesetzt haben, bleiben unangetastet: Umgestellt wird nur, was noch auf dem alten
Design stand.

**Bilder verlinken:** Unter jedem Bild im Baukasten steht, wohin es verlinkt ist –
oder „Bild verlinken“, wenn noch nichts hinterlegt ist. Ein Klick darauf führt
direkt zum Feld. Verlinkte Bilder werden wie alle Links gezählt.

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
Bei einer **Ausgabe** wandert vorhandenes HTML in einen Baustein „Eigenes HTML“.

Bei einer **Vorlage** ist das anders: Ein von Hand geschriebener Rahmen lässt
sich nicht in Bausteine zerlegen, er wird beim Wechsel durch einen
Standardrahmen ersetzt. Die bisherige Fassung wird deshalb gesichert – über
„HTML zurückholen“ ist sie wieder da.

**Neue Ausgaben erben die Gestaltung ihrer Vorlage:** Schriften, Text-, Link-
und Knopffarbe kommen aus der Standardvorlage. Ein Newsletter der zweiten Marke
beginnt damit gleich in deren Farben.

**Textassistent (freiwillig, ab Werk aus).** Unter *Einstellungen →
Textassistent* lässt sich ein Sprachmodell hinterlegen (Anthropic oder OpenAI,
mit eigenem Schlüssel). Danach steht über jedem Textfeld ein ✨-Knopf: anders
formulieren, kürzen, ausbauen, persönlicher oder sachlicher schreiben,
Rechtschreibung prüfen. Am Betreff gibt es zusätzlich „Betreff vorschlagen
lassen“ – Grundlage ist dort der ganze Newsletter. Jeder Vorschlag erscheint
erst im Fenster; Sie ändern ihn nach Belieben und entscheiden dann, ob er den
Text ersetzt oder an der Schreibmarke eingesetzt wird. Ein fester Tonfall
(„Wir duzen unsere Leser“) lässt sich einmal hinterlegen und gilt dann immer.

Drei Dinge sollten Sie dabei wissen:

* **Ohne Schlüssel passiert nichts.** Kein Knopf, keine Verbindung nach außen.
  Das System funktioniert vollständig ohne den Assistenten.
* **Der Text verlässt Ihren Server.** Für jeden Vorschlag geht der betreffende
  Abschnitt zum gewählten Anbieter. Schicken Sie keine personenbezogenen Daten
  Ihrer Empfänger mit und schließen Sie mit dem Anbieter einen
  Auftragsverarbeitungsvertrag (Art. 28 DSGVO).
* **Der Anbieter rechnet ab.** Jede Anfrage kostet dort Geld; das
  Newslettersystem selbst verlangt nichts. Der Schlüssel liegt verschlüsselt in
  der Datenbank und wird in der Oberfläche nie wieder angezeigt.

Vorgaben, die immer gelten (Deutsch, keine Werbefloskeln, Platzhalter wie
`{{vorname}}` bleiben unangetastet), stehen im Code und nicht im Formular.

---

## 4. Automationen: Abläufe per Drag & Drop

Eine Automation ist eine Mailstrecke, die von selbst läuft. Ausgelöst wird sie
durch eine **bestätigte Anmeldung** – wahlweise nur in einer bestimmten Liste.
Den Ablauf ziehen Sie unter **Automationen** aus Schritten zusammen.

**Schritte**

| Schritt | Bedeutung |
|---|---|
| ⏱ **Warten** | Pause vor dem nächsten Schritt – 1 bis 365 Minuten, Stunden oder Tage. Die Zeit zählt jeweils ab dem vorherigen Schritt, nicht ab der Anmeldung. |
| ✉ **E-Mail senden** | Verschickt eine Mail. Betreff und Inhalt schreiben Sie darunter im gewohnten Baukasten. Ohne Betreff wird der Schritt übersprungen. |
| ? **Wenn … dann** | Prüft etwas und teilt den Ablauf in einen **Ja-** und einen **Nein-Zweig**. Danach laufen beide Zweige wieder zusammen. |
| ⚙ **Aktion** | Empfänger zu einer Liste hinzufügen, aus einer Liste entfernen oder vom Newsletter abmelden. |
| ■ **Strecke beenden** | Hier verlässt der Empfänger die Strecke; alles darunter wird nicht mehr ausgeführt. |

**Bedingungen**

* *hat die letzte Mail dieser Strecke geöffnet* – braucht „Öffnungen messen“ in
  der betreffenden Mail.
* *hat in der letzten Mail dieser Strecke geklickt* – braucht „Klicks messen“.
* *steht in einer bestimmten Liste*
* *hat ein Unternehmen hinterlegt*

Geprüft wird immer die **zuletzt zugestellte** Mail dieser Strecke an diese
Person. Wurde noch keine verschickt, gilt die Bedingung als nicht erfüllt – es
geht im Nein-Zweig weiter.

**Bedienung**

* Schritt aus der linken Leiste in den Ablauf **ziehen** – oder anklicken, dann
  wird er unten angehängt. In die Zweige einer Bedingung lässt sich ebenso ziehen.
* Reihenfolge ändern: am Griff `⠿` ziehen oder die Pfeiltasten `↑ ↓` benutzen.
* Rechts stellen Sie den ausgewählten Schritt ein (Wartezeit, Bedingung, Liste).
* **Speichern nicht vergessen** – erst danach lässt sich der Inhalt einer neuen
  Mail schreiben („Inhalt bearbeiten“ am Schritt).
* Über „Hinweise zum Ablauf“ meldet das System Lücken: fehlender Betreff, nicht
  gewählte Liste, Bedingung ohne Zweige.

**Start und Kontrolle:** Eine Strecke läuft erst, wenn der Status auf **Aktiv**
steht und gespeichert ist. Wer gerade unterwegs ist, steht unten in der Tabelle
„Wer gerade in der Strecke ist“ samt nächstem Schritt und Fälligkeit. Meldet
sich jemand ab, wird die Strecke für diese Person sofort beendet. Die Schritte
selbst führt der Cron-Job aus (siehe nächster Abschnitt) – ohne ihn steht auch
die Automation still.

**Bedienung wie im Newsletter-Baukasten:** Schritte lassen sich ziehen – am
ganzen Kopf des Schrittes, nicht nur am Griffsymbol – oder anklicken, dann
hängen sie sich unten an. Das Ziehen läuft über Zeigerereignisse und
funktioniert deshalb in jedem Browser und auch auf dem Handy.

**Vom Schritt direkt zur Mail:** Ein frisch eingesetzter Mailschritt hat noch
keinen Inhalt – die dafür nötige Kennung entsteht erst beim Speichern. Der Knopf
**„Inhalt schreiben"** erledigt beides auf einmal: Er speichert den Ablauf und
öffnet danach den Baukasten genau dieser Mail. Später steht dort der Betreff und
ein Knopf „Inhalt bearbeiten".

**Inhalt aus einem vorhandenen Newsletter übernehmen:** Im Schritt-Editor lässt
sich oben ein bereits geschriebener Newsletter auswählen und sein Inhalt samt
Betreff und Design herüberholen. Es wird **kopiert** – der Newsletter selbst
bleibt unverändert, und spätere Änderungen an der Strecke wirken sich nicht auf
ihn aus. Genauso lässt sich für jeden Schritt eine eigene Design-Vorlage wählen;
ein Wechsel stellt auch Schriften und Farben im Inhalt um.

Ältere Strecken (Schritte mit Verzögerung, ohne Ablauf) werden beim ersten
Öffnen automatisch in einen Ablauf übersetzt; es geht nichts verloren.

---

## 5. Benutzer und Rollen

Unter **Benutzer** legen Administratoren weitere Zugänge an. Es gibt drei Rollen:

| Rolle | Darf |
|---|---|
| **Administrator** | alles – zusätzlich Einstellungen, Versandweg, Protokoll und Zugänge |
| **Redakteur** | Newsletter, Vorlagen, Automationen, Versand, Empfänger und Listen |
| **Betrachter** | nur ansehen: Übersicht, Auswertungen, Empfänger – nichts ändern |

* Zugänge lassen sich **sperren** statt löschen; gesperrte Konten kommen nicht
  mehr hinein, bleiben aber im Protokoll erhalten.
* Der **letzte aktive Administrator** kann nicht gelöscht, gesperrt oder
  herabgestuft werden – sonst käme niemand mehr in die Einstellungen.
* Jede Person ändert ihr Passwort selbst unter **Mein Zugang**; Administratoren
  können außerdem ein neues Passwort vergeben (es wird im Klartext angezeigt,
  damit Sie es weitergeben können – bitte auf sicherem Weg).
* Die Rechte greifen nicht nur in der Navigation: Wer ein Recht nicht hat,
  bekommt die Seite auch dann nicht, wenn er die Adresse direkt aufruft, und
  abgeschickte Formulare werden abgewiesen.

---

## 6. Mehrere Websites aus einer Installation

Sie können mit **einer** Installation Newsletter für mehrere Projekte
verschicken – jedes mit eigenem Namen, eigener Website, eigenem Impressum und
eigener Absenderadresse. Das spart eine zweite Datenbank, einen zweiten
Cron-Job und doppelte Updates.

**Der Reiter „Marken" ist die Schaltzentrale.** Dort steht jede Marke mit
ihrem Aussehen, ihren Angaben und der Zeile, wo sie überall benutzt wird.

Pro Projekt brauchen Sie zwei Dinge:

1. **Eine Marke** (Marken → „Neue Marke"): Name, Website, Impressum,
   Absender – und die Angabe, womit das Design anfangen soll (leer, eine
   mitgelieferte Vorlage oder eine Kopie einer bestehenden Marke). Das
   Design bearbeiten Sie danach über „Aussehen bearbeiten".

| Feld | Wirkung |
|---|---|
| Name der Marke | erscheint in der Kopfzeile und als `{{marke}}` |
| Website | Footer-Zeile „Sie erhalten diese E-Mail, weil Sie sich unter … angemeldet haben" |
| Impressum im Footer | die Pflichtangaben dieses Projekts (`{{impressum}}`) |
| Impressum-Seite / Datenschutz-Seite | die Links im Footer |
| Absendername / Absenderadresse | Absender für Automationen dieser Marke und Vorschlag für neue Newsletter |

2. **Eine Liste** (Listen → „Neue Liste"), damit die Empfänger getrennt
   bleiben. Wer sich für Projekt A angemeldet hat, darf keine Post von
   Projekt B bekommen – die Einwilligung gilt immer nur für das Projekt,
   bei dem sie erteilt wurde. **Stellen Sie bei der Liste die Marke ein**
   (Listen → Spalte „Marke"): Danach richten sich Bestätigungs-,
   Begrüßungs- und Abmeldemail dieser Liste, samt Impressum.

**Leere Felder greifen auf die Einstellungen zurück.** Die Hauptmarke braucht
also nichts einzutragen – für sie bleibt alles wie bisher.

**Wo die Marke jeweils gewählt wird:** beim Newsletter rechts unter „Marke &
Design", bei der Automation in den Angaben der Strecke, bei der Liste in der
Spalte „Marke". Beim Anlegen fragt der Assistent ohnehin danach.

Technisch hängt eine Marke an einer Vorlage – der Reiter **Vorlagen** bleibt
deshalb für das Aussehen zuständig. Hat eine Marke mehrere Designs, gelten die
Angaben unter „Marken" für alle davon.

**Das Aussehen einer Marke lässt sich jederzeit austauschen:** Vorlagen →
Abschnitt „Aussehen wechseln" (oder Marken → „Anderes Design"). Dort stehen die
mitgelieferten Designs und Ihre eigenen Vorlagen als Kacheln mit Vorschau; ein
Klick übernimmt Farben, Schriften, Kopfzeile und Footer.

Dabei bleibt die Marke die eigene: Name, Website, Impressum und Absender werden
nicht angefasst, und die **Wortmarke im Kopf wird umgeschrieben** – aus dem
Namen der Marke wird der hervorgehobene Teil abgeleitet („Fairway54" → Fairway
mit farbiger 54, „AcumenMail" → Acumen mit farbigem Mail). Stammt das Design von
einer anderen Marke, werden außerdem deren Claim und deren Footer-Hinweis
geleert – ein Partnerlink-Hinweis der einen Marke hat im Footer der anderen
nichts zu suchen.

Ein reiner HTML-Rahmen lässt sich ebenfalls übernehmen; dort kann das System die
Texte aber nicht anpassen und sagt das auch. Prüfen Sie danach den Footer.

**Abkürzung:** Unter Vorlagen → „Fertige Vorlage übernehmen" liegen fertig
gestaltete Entwürfe aus dem Ordner `newsletter/vorlagen/`. Sie bringen Name und
Website schon mit; Impressum und Absender tragen Sie danach unter „Marke dieser
Vorlage" nach. Eigene Entwürfe legen Sie einfach als weitere `.html`-Datei in
diesen Ordner – mit einem Kommentarkopf aus `Vorlage:`, `Beschreibung:`,
`Marke:` und `Website:`, dann erscheinen sie automatisch in der Auswahl.
Baukasten-Vorlagen legen Sie als `.json` ab; dort stehen dieselben Angaben
als Felder `vorlage`, `beschreibung`, `marke`, `website` neben `blocks`.

Der **Inhalt** einer Ausgabe entsteht weiterhin im Baukasten. Farben für Links
und Knöpfe stellen Sie dort rechts unter „Gestaltung" ein – bei einer zweiten
Marke also einmal auf deren Akzentfarbe setzen.

**Beim Anlegen führt ein Assistent durch zwei Fragen.** Newsletter → „Neue
E-Mail anlegen" fragt zuerst *Um welche E-Mail geht es?* – Newsletter,
Automation oder eine der Systemmails; jede Möglichkeit mit einer kleinen
Zeichnung daneben. Danach kommt *Unter welcher Marke?* mit einer echten
Vorschau je Marke. Dann sind Sie im Baukasten. Mehr Fragen gibt es nicht.

Ein Klick auf die Marke – und Sie stehen auf einer leeren Fläche, bei der
Kopfzeile und Footer dieser Marke aber schon stehen. Eine Vorlage ist
ausdrücklich kein Muss; wer lieber mit einem Beispielinhalt anfängt, findet die
Vorlagen darunter unter „Oder gleich mit einer fertigen Vorlage anfangen".

Marken, die noch nicht benutzt wurden, aber als Datei im Ordner
`newsletter/vorlagen/` liegen, stehen ebenfalls zur Wahl – markiert mit „wird
beim ersten Mal angelegt". Die zugehörige Vorlage entsteht dann beim ersten
Klick, ein zweiter Newsletter derselben Marke benutzt sie weiter.

Später wechseln Sie die Marke im Editor rechts unter **„Marke & Design"**; die
Auswahl ist nach Marken gruppiert. Kopfzeile, Footer, Schriften und Farben
wechseln mit – selbst gesetzte Farben bleiben.

**Die drei Systemmails gibt es ebenfalls je Marke.** Unter „Systemmails"
(erreichbar über den Assistenten oder über Einstellungen) stehen
Anmeldebestätigung, Begrüßung und Abmeldebestätigung als echte Vorschau –
in der Marke, unter der sie beim Empfänger ankommen. Welche Marke das ist,
entscheidet die Liste, für die sich jemand angemeldet hat. Die Texte lassen
sich je Marke abweichen lassen; ein leeres Feld heißt „nimm den allgemeinen
Text aus den Einstellungen", eine einzelne Marke muss also nichts pflegen.

Ebenso erben die Mails einer **Automation** deren Marke: Legen Sie die Strecke
über den Assistenten an, bekommen alle ihre Schritte die gewählte Marke.

Beim Schreiben eines Newsletters wählen Sie außerdem die passende **Liste**;
Absendername und -adresse stehen im Editor und lassen sich pro Ausgabe
überschreiben. Automationen übernehmen den Absender aus der Vorlage des
jeweiligen Schrittes.

> **Wichtig:** Die Absenderadresse muss zu einer Domain gehören, für die Ihr
> Versandweg senden darf. Bei SMTP über ein Postfach von Domain A können Sie
> nicht ohne Weiteres als Domain B auftreten – sonst landen die Mails im Spam.
> Entweder ein zweites Postfach für Domain B einrichten (dann brauchen Sie doch
> zwei Installationen oder Sie wechseln den Versandweg pro Marke), oder als
> Absender eine Adresse Ihrer Hauptdomain nehmen und im Text auf das Projekt
> hinweisen. SPF und DKIM (Abschnitt 8) müssen zur verwendeten Absenderdomain
> passen.

---

## 7. Cron-Job einrichten (wichtig!)

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

## 8. Zustellbarkeit: SPF, DKIM, DMARC

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

## 9. Versandwege

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

## 10. Rechtliches (Deutschland/EU)

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

## 11. Sicherheit

In der Datenbank stehen Klarnamen, E-Mail-Adressen, Einwilligungsprotokolle und
IP-Adressen – also personenbezogene Daten, für die Sie haften. Was das System
dafür tut und was Sie selbst tun müssen:

**Was eingebaut ist**

| Bereich | Maßnahme |
|---|---|
| Passwörter der Zugänge | `password_hash()` (bcrypt), nie im Klartext, automatische Neuverschlüsselung bei stärkeren Verfahren |
| Anmeldung | gleiche Fehlermeldung für falsche Adresse und falsches Passwort, Bremse pro IP (8 Versuche/15 Min.) **und** pro Konto (25/15 Min.), neue Sitzungskennung nach dem Login, Abmeldung nach 8 Stunden |
| Sitzung | Cookie nur für den Server (HttpOnly), nicht seitenübergreifend (SameSite=Lax), nur über HTTPS, sobald die Basis-URL auf https lautet |
| Formulare | CSRF-Zeichen bei **jedem** POST, zeitkonstanter Vergleich |
| Datenbankzugriffe | ausschließlich vorbereitete Anweisungen (PDO), keine zusammengebauten Abfragen mit Benutzereingaben |
| Ausgaben | alles maskiert; eingefügtes HTML läuft durch einen Filter mit fester Positivliste (script, iframe, Ereignis-Attribute und `javascript:` fliegen raus) |
| Rechte | serverseitig geprüft, auch bei direktem Aufruf der Adresse und bei abgeschickten Formularen |
| SMTP-Passwort, Rücklaufpostfach, KI-Schlüssel | verschlüsselt (AES-256-GCM) mit dem Schlüssel aus der `config.php`, in der Oberfläche nie wieder sichtbar |
| Empfänger-Selbstverwaltung | zufälliges Kennzeichen (192 Bit) **plus** zweckgebundene Signatur – ein Kennzeichen aus einer Abmelde-Mail öffnet keine Datenansicht |
| Bild-Upload | nur echte Bilder (Inhaltsprüfung, nicht die Endung), eigener Dateiname, Ausführung von Programmcode im Ordner gesperrt |
| Cron-Adressen | nur mit dem Schlüssel aus der `config.php`, zeitkonstant verglichen |
| Systemcheck | nach der Einrichtung nur noch angemeldet – oder mit dem Cron-Schlüssel, falls der Admin-Bereich klemmt |
| Protokoll | Anmeldungen, Rechteänderungen, Versand und Fehler landen im Protokoll |

**Was Sie selbst tun müssen**

1. **`install.php` löschen**, sobald die Einrichtung steht. Das System weist
   darauf hin und sperrt die Datei zusätzlich, sobald ein Zugang existiert.
2. **HTTPS erzwingen.** Legen Sie im Hauptverzeichnis Ihrer Domain eine
   `.htaccess` an:
   ```apache
   RewriteEngine On
   RewriteCond %{HTTPS} !=on
   RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]
   ```
3. **Den Systemcheck ansehen.** Er ruft die Datenbankdatei einmal über das
   Internet ab und sagt Ihnen, ob sie wirklich unerreichbar ist. Steht dort
   „öffentlich abrufbar“, handeln Sie sofort: Dann kann jeder Ihre komplette
   Empfängerliste herunterladen.
4. **Starke Passwörter** und für jede Person ein eigener Zugang – keine
   Sammelkonten. Ausgeschiedene Mitarbeiter sperren statt das Passwort zu teilen.
5. **Sichern.** Die Datenbank (`data/`) gehört in Ihre Datensicherung – und die
   Sicherung gehört genauso geschützt wie das Original, es sind dieselben Daten.
6. **Aktuell halten:** PHP-Version im Hosting-Menü nicht veralten lassen.

**Wo die Grenzen liegen – ehrlich**

* Der Schutz des Ordners `data/` hängt an `data/.htaccess`. Apache beachtet
  diese Datei (IONOS, die meisten Massenhoster), **nginx nicht**. Zusätzlich
  trägt die Datenbank einen zufälligen Namen, und ein Angreifer müsste ihn
  erraten – aber die eigentliche Sperre ist die `.htaccess`. Wer volle Kontrolle
  über den Server hat, legt den Ordner besser ganz außerhalb des Web-Ordners ab.
* **Keine Zwei-Faktor-Anmeldung.** Wer Passwort und Zugang hat, kommt hinein.
* **Kein Passwort-vergessen-Weg** – bewusst: Das spart eine Angriffsfläche.
  Ein zweiter Administrator kann jederzeit ein neues Passwort vergeben.
* Ein **Redakteur** kann eigenes HTML in Newsletter einsetzen. Das ist gewollt,
  bedeutet aber: Vergeben Sie diese Rolle nur an Menschen, denen Sie vertrauen.
* Fehlt die PHP-Erweiterung **openssl**, werden hinterlegte Passwörter *nicht*
  verschlüsselt. Der Systemcheck warnt davor, das Protokoll ebenfalls.

---

## 12. Aufbau

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
│   ├── automationen.php     Automationen: Ablauf-Baukasten und Mailinhalte
│   ├── vorlagen.php         Design-Vorlagen samt Marke (Baukasten oder HTML)
│   ├── upload.php           Bild-Upload für den Baukasten
│   ├── ki.php               holt Textvorschläge (nur wenn eingerichtet)
│   ├── bausteine.php        gesicherte Bausteine zum Wiederverwenden
│   ├── versand.php          Warteschlange steuern
│   ├── protokoll.php        Ereignisse, Rückläufer, Sperrliste
│   ├── benutzer.php         Zugänge, Rollen, eigenes Passwort
│   └── einstellungen.php    Absender, SMTP, Tempo, Texte
│
├── pruefsummen.txt          Prüfliste für den Systemcheck (mit hochladen)
├── cron/
│   ├── send.php             Versand-Worker (alle 5 Minuten)
│   ├── bounces.php          Rücklaufpostfach (stündlich)
│   ├── wartung.php          Aufräumen (täglich)
│   └── pruefsummen.php      erzeugt die Prüfliste (nur beim Ausliefern)
│
├── uploads/                 hochgeladene Bilder für den Baukasten
├── vorlagen/                fertige Vorlagen zum Übernehmen (.html oder .json)
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
| `Renderer.php` / `Templates.php` | Vorlagen, Marke je Vorlage, Platzhalter, Tracking-Einbau |
| `Subscribers.php` / `Lists.php` | Empfänger, Double-Opt-in, Listen, Import |
| `Automations.php` | Mailstrecken: Teilnehmer aufnehmen, Ablauf ausführen, Mails einreihen |
| `Flow.php` | Ablauf einer Automation: Struktur prüfen, Wege finden, Bedingungen auswerten |
| `Bounces.php` | Rückläufer inkl. kleinem POP3-Client |
| `Tracking.php` / `Events.php` | Öffnungen, Klicks, Ereignisse |
| `Ai.php` | Textassistent: Anweisung, Anfrage an den Anbieter, lesbare Fehler |
| `Snippets.php` | Gesicherte Bausteine: prüfen, ablegen, mit frischen Kennungen zurückgeben |
| `Auth.php` / `Util.php` / `Log.php` | Anmeldung, Rollen und Rechte, Helfer, Protokoll |

---

## 13. Anmeldeformular auf eigenen Seiten einbauen

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

## 14. Aktualisieren (neue Fassung einspielen)

### Von Hand

1. Den Ordner `newsletter/` per FTP **über** den bestehenden hochladen und dabei
   überschreiben lassen. `config.php` und den Ordner `data/` dabei **nicht**
   löschen – dort stecken Zugangsdaten und Empfänger.
2. Eine beliebige Seite aufrufen. Die Datenbank wird automatisch auf den neuen
   Stand gebracht (neue Spalten und Tabellen werden ergänzt).
3. Prüfen: Im Admin-Bereich steht unten die Fassung, z. B.
   `Fassung 1.11.0 (…) · Datenbank 6`. Dasselbe zeigt `systemcheck.php`.

**Ändert sich nichts?** Dann sagt `systemcheck.php` genau, woran es liegt:

* **„X Datei(en) veraltet"** – diese Dateien sind beim Hochladen nicht
  angekommen. Der Systemcheck nennt sie beim Namen; übertragen Sie genau diese
  erneut. Grundlage ist die mitgelieferte Liste `pruefsummen.txt`.
* **„Zwischenspeicher für PHP: an"** und im Admin-Bereich steht unten eine
  **ältere** Fassung als im Systemcheck – dann liegen die neuen Dateien zwar auf
  dem Server, PHP liefert aber noch den alten, kompilierten Code. Der Knopf
  „Zwischenspeicher jetzt leeren" behebt das; sonst ein bis zwei Minuten warten
  oder bei IONOS die PHP-Version einmal hin und zurück stellen.
* Beide Angaben stimmen und es sieht trotzdem alt aus? Dann hängt nur der
  Browser: Strg+F5 lädt CSS und JavaScript neu.

Die Fassungsangabe im Admin-Bereich kommt aus PHP, nicht aus dem Browser –
Strg+F5 kann sie also nie ändern.

Bestehende Newsletter bleiben im HTML-Modus, damit sich nichts unbemerkt
verändert – über den Knopf „Baukasten“ im Editor stellen Sie einzelne Ausgaben um.
Neu angelegte Ausgaben starten immer im Baukasten.


### Automatisch aus GitHub (empfohlen)

Wer den Code in GitHub liegen hat, muss nichts mehr von Hand hochladen: Im
Repository liegt der Ablauf `.github/workflows/newsletter-zu-ionos.yml`. Er
startet bei jedem Push, der etwas an `newsletter/` ändert, und legt die Dateien
per FTPS auf den Webspace.

**Einmalig einrichten** – auf github.com im Repository unter
*Settings → Secrets and variables → Actions → New repository secret*:

| Name | Inhalt |
|---|---|
| `IONOS_FTP_HOST` | der Server aus dem IONOS-Menü, z. B. `access-5017012345.webspace-data.io` – **ohne** `ftp://`. Gibt Ihr Paket nur SFTP her, schreiben Sie `sftp://access-…` davor. |
| `IONOS_FTP_USER` | FTP-Benutzername |
| `IONOS_FTP_PASS` | zugehöriges Passwort |
| `IONOS_ZIEL` | Zielordner auf dem Server, z. B. `/newsletter` |

Legen Sie dafür bei IONOS am besten einen **eigenen FTP-Zugang an, dessen
Startordner der Newsletter-Ordner ist** (Hosting → FTP-Zugänge). Dann kann ein
verlorenes Passwort nicht die ganze Website betreffen. Die Angaben sind danach
auch für Sie nicht mehr lesbar, nur ersetzbar.

**Was der Ablauf tut**

1. Er prüft **jede** PHP-Datei auf Syntaxfehler – eine kaputte Datei würde sonst
   den ganzen Newsletter-Bereich lahmlegen.
2. Er prüft, ob `pruefsummen.txt` zum Code passt (sonst meldet der Systemcheck
   hinterher lauter „geändert").
3. Er lädt hoch – verschlüsselt. Ohne Präfix über **FTPS**, mit `sftp://`
   davor über **SFTP** (SSH). Beides gibt es bei IONOS je nach Paket, beides
   ist geprüft. Unverschlüsseltes `ftp://` weist der Ablauf ausdrücklich ab –
   sonst gingen Passwort und Dateien im Klartext durchs Netz.

**Was er bewusst NICHT anfasst:**

* `config.php` – Ihre Zugangsdaten und der Schlüssel, mit dem die Passwörter
  verschlüsselt sind. Die Datei gehört dem Server, nicht dem Git.
* `data/` – die Datenbank mit **allen Empfängern**. Ein Überschreiben würde
  sämtliche Anmeldungen vernichten.
* `uploads/` – Ihre hochgeladenen Bilder.
* `install.php` – nach der Einrichtung überflüssig. Für eine **erste**
  Einrichtung die entsprechende Zeile im Ablauf entfernen.

**Erst schauen, dann hochladen:** Unter *Actions → Newsletter zu IONOS →
Run workflow* lässt sich der Haken „Nur anzeigen, nichts hochladen" setzen.
Dann steht im Protokoll, welche Dateien übertragen **würden** – ohne dass etwas
passiert. Für den ersten Lauf sehr zu empfehlen.

**Gelöschtes bleibt stehen:** Wenn Sie eine Datei aus dem Git entfernen, bleibt
sie auf dem Server liegen. Das ist Absicht – ein Ablauf, der löschen darf, ist
ein Ablauf, der bei einem Tippfehler Ihre Empfängerdatenbank löschen kann.
Aufräumen also weiterhin von Hand.

Nach dem Hochladen einmal `systemcheck.php` aufrufen: Dort steht, ob alle
Dateien vollständig angekommen sind und welche Fassung nun läuft.
---

## 15. Wartung und Sicherung

* **Sichern:** `config.php` und den Ordner `data/` (bei MySQL: den
  Datenbank-Export). Ohne `secret` aus der `config.php` sind gespeicherte
  SMTP-Passwörter nicht mehr lesbar und alle Abmeldelinks ungültig.
* **Umziehen:** Dateien kopieren, `base_url` in `config.php` anpassen, fertig.
* **Aufräumen:** erledigt `cron/wartung.php`; Versandprotokolle lassen sich
  zusätzlich unter Versand → „Alte Einträge aufräumen“ kürzen.

---

## 16. Wenn etwas nicht klappt

**Erste Anlaufstelle bei jedem Problem:** `systemcheck.php` im Newsletter-Ordner.
Die Seite hat keine Abhängigkeiten und funktioniert auch dann, wenn der Rest streikt.

| Symptom | Ursache und Lösung |
|---|---|
| `install.php` zeigt nur die Kopfzeile, darunter bleibt alles leer | Ein PHP-Fehler bei abgeschalteter Fehleranzeige. Fast immer eine zu alte PHP-Version (nötig: 8.0+) oder eine unvollständig hochgeladene Datei. `systemcheck.php` nennt den Grund; seit dieser Fassung zeigt auch `install.php` den Fehler im Klartext an. |
| „config.php konnte nicht geschrieben werden“ | Der Ordner `newsletter/` ist schreibgeschützt. Per FTP die Rechte auf 755 setzen. |
| Bild-Upload schlägt fehl | Der Ordner `newsletter/uploads/` fehlt oder ist schreibgeschützt – per FTP anlegen und auf 755 setzen. |
| Hochgeladene Bilder erscheinen nicht (kaputtes Vorschaubild) | Eine ältere Fassung schrieb `php_flag engine off` in `uploads/.htaccess`. Läuft PHP als CGI – bei vielen Hostern der Normalfall – antwortet Apache für den ganzen Ordner mit Fehler 500. Ab Fassung 1.7.0 wird die Datei beim nächsten Upload automatisch ersetzt; der Systemcheck warnt davor. |
| Bausteine oder Schritte lassen sich am Handy nicht ziehen | Touch-Geräte unterstützen kein Drag & Drop. Nutzen Sie die Pfeiltasten `↑ ↓` am Baustein bzw. am Schritt – oder tippen Sie den Baustein in der linken Leiste an, dann wird er unten angehängt. |
| Automation verschickt nichts | Status steht auf „Pausiert“, dem Mail-Schritt fehlt der Betreff, oder der Cron-Job läuft nicht. Die Hinweisbox über dem Ablauf nennt fehlende Angaben. |
| Bedingung „hat geöffnet“ trifft nie zu | In der vorangehenden Mail muss „Öffnungen messen“ angehakt sein, und es muss vorher eine Mail dieser Strecke zugestellt worden sein. |
| „Cron-Job meldet sich nicht“ | Cron nicht eingerichtet oder falscher Pfad. Testweise `versand.php` → „Portion jetzt senden“. |
| Mails bleiben in der Warteschlange | Stundenlimit erreicht (Versand → „noch möglich“) oder Kampagne pausiert. |
| SMTP-Fehler „Verbindung fehlgeschlagen“ | Host/Port/Verschlüsselung prüfen; viele Hoster erlauben ausgehendes SMTP nur zum eigenen Server. |
| Mails landen im Spam | SPF/DKIM/DMARC prüfen (Abschnitt 8), Absenderadresse muss zur Domain passen, langsam warmlaufen. |
| Bestätigungslinks führen ins Leere | `base_url` in `config.php` stimmt nicht mit der echten Adresse überein. |
| Öffnungsraten wirken zu niedrig | Viele Programme laden Bilder nicht. Klicks sind die verlässlichere Kennzahl. |
| „Dafür fehlt Ihnen die Berechtigung“ | Der Zugang hat die falsche Rolle. Ein Administrator ändert das unter Benutzer. |
| Nach dem Speichern kommt eine leere Seite | Behoben ab Fassung 1.6.2. Der Server hatte keinen Ausgabepuffer, dadurch lief die Weiterleitung nach dem Speichern ins Leere. |
| Im Newsletter steht das falsche Impressum | Die Vorlage gehört zu einem anderen Projekt: Vorlagen → „Marke dieser Vorlage“ ausfüllen (Abschnitt 6). Bereits versendete Ausgaben bleiben unverändert. |
| Fehler im Protokoll | Admin → Protokoll → Fehler; dort steht die genaue Meldung. |

---

## 17. Größenordnung

SQLite trägt problemlos einige zehntausend Empfänger. Für sehr große Verteiler
oder mehrere gleichzeitige Redakteure ist MySQL die bessere Wahl – umstellen
lässt sich das später über einen Export/Import.

Der Versand skaliert über `batch_size` (Mails pro Lauf) und den Cron-Takt:
50 Mails alle 5 Minuten sind 600 pro Stunde; bei aktiviertem Keepalive schafft
ein SMTP-Server meist deutlich mehr – die Grenze setzt in der Praxis der Hoster.
