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
  assets/logo.svg    Die Bildmarke – Vorlage für alles Weitere
  assets/og-bild.png Vorschaubild fürs Teilen (1200 x 630)
  assets/demo/       Stylesheets des Newslettersystems (Kopien)
  demo/              Die drei Demo-Seiten, als iframe eingebunden
  favicon.svg        Lesezeichen-Symbol, dazu favicon-32.png
  apple-touch-icon.png
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

## Die Bildmarke

Lange stand im Stylesheet „Kein Logo-Kreis. Der Name reicht." Für die Seite
stimmte das – ein Name ist nur kein Lesezeichen-Symbol, kein Bild in der
Vorschau beim Teilen und kein Absender auf einer Clubseite.

Die Marke ist **eine Fahnenstange, an der ein Brief weht.** Der Wimpel ist ein
Umschlag: rechts die Spitze einer Fahne, oben die ausgesparte Klappe. Golf und
Post in einer Form, ohne dass eines von beiden erklärt werden muss. Verworfen
wurde eine schlichte Fahne – die hat jeder Golfanbieter – und eine Fassung mit
Loch im Rasen, deren Ellipse ab 32 px zu Matsch wurde.

Gebaut ist sie aus vier Rechtecken und zwei Dreiecken, ohne Rundungen, ohne
Verlauf, ohne Schatten: dieselbe rechteckige Fläche in derselben Signalfarbe,
mit der die Seite auch Wörter markiert. Deshalb trägt das Wort „Golf" im
Namenszug keine Markierung mehr – zwei lindgrüne Blöcke nebeneinander streiten
sich. Die Fläche gehört jetzt der Marke, das Wort bekommt das Clubgrün.

**Zwei Fassungen.** Auf hellem Grund die volle: lindgrüne Fläche, Zeichnung in
Tinte. Auf dunklem Grund fällt die Fläche weg und die Zeichnung wird lindgrün –
sonst verschwände der Balken unter der Stange im Untergrund und die Marke sähe
unten abgeschnitten aus. Das steuert das Stylesheet über zwei Klassen
(`.marke-flaeche`, `.marke-tinte`), die Umschaltung passiert automatisch in
`.site-footer` und `.section-dark`.

**Wo sie steht:**

| Ort | Fassung |
|---|---|
| Kopfzeile, neben dem Namenszug | voll, 2,1 rem |
| Fußzeile | negativ, 2,6 rem |
| Persönliche Clubseiten, als Absender | voll, 42 px – in unseren Farben, obwohl die Seite die des Clubs trägt |
| Lesezeichen (`favicon.svg`, `favicon-32.png`) | voll |
| Startbildschirm auf dem Handy (`apple-touch-icon.png`) | voll |
| Vorschau beim Teilen (`assets/og-bild.png`) | voll, mit Namenszug und Hauptzeile |

Die Zeichnung liegt zweimal: als `src/partials/logo.php` (eingebettet, Farben
über Klassen) und als `assets/logo.svg` (feste Farben, Vorlage für die
Rasterbilder). Wer eine ändert, ändert bitte auch die andere.

**Rasterbilder erneuern:** Die PNG-Dateien sind aus derselben Zeichnung
gerendert. Fällt eine Änderung an, mit einem Browser neu aufnehmen –
`favicon-32.png` (32 x 32), `apple-touch-icon.png` (180 x 180) und
`assets/og-bild.png` (1200 x 630).

---

## Seitenstruktur

Die Reihenfolge im Menü folgt den Fragen eines Besuchers, nicht der Technik:

| Bereich | Beantwortet | Seiten |
|---|---|---|
| **Leistungen** | Was tut ihr für mich? | Übersicht · Clubcheck · Saison-Setup · Clubbetreuung |
| **Lösungen** | Für welche Aufgabe? | Übersicht · Mitgliederbindung · Turniere & Events · Gastspieler & Greenfee · Neumitglieder gewinnen · Golfschule & Pro |
| **Software** | Womit? | Übersicht · Newsletter-Baukasten · Automationen · Empfänger & Segmente · Auswertung · Zustellbarkeit & DSGVO · Systemvoraussetzungen |
| **Preise** | Was kostet das? | Pakete und eine Vergleichsrechnung gegen Mietlösungen |
| **Wissen** | Verstehen die etwas davon? | Übersicht · Newsletter-Jahresplan · Betreffzeilen · Mitgliederdaten und DSGVO · Häufige Fragen |
| **Sonstige** | | Startseite · Über uns · Kontakt · Impressum · Datenschutz |

Vorher stand „Software" an erster Stelle. Das war eine Aussage über das
Angebot – und die falsche: Verkauft wird eine Dienstleistung, bei der die
Software mitkommt, nicht ein Mietwerkzeug. Die Reihenfolge steht an einer
Stelle, in `$NAV` in `src/partials/config.php`; Hauptmenü, Mega-Menü,
Brotkrumen, Randspalten, Footer und Sitemap folgen ihr.

Jede Unterseite hat Brotkrumen, eine Randspalte mit der Navigation ihres
Bereichs und weiterführende Verweise am Ende.

---

## Was die Startseite sagen muss

Die frühere Startseite fing mit einem Problemsatz an („Ihr Aushang erreicht nur
die, die ohnehin da sind") und zeigte direkt darunter ein großes Fenster mit der
Newsletter-Oberfläche. Beides für sich gut – zusammen sagten sie: *Die verkaufen
Software.* Verkauft wird aber eine Dienstleistung. Der Preis stand ausschließlich
auf der Preisseite, das Wort „einrichten" kam über der Falz gar nicht vor.

Der Vergleich mit den bekannten Anbietern im deutschsprachigen Raum ist eindeutig:
Bei rapidmail, CleverReach, Inxmail und mail2many steht in der ersten Zeile, *was*
verkauft wird und *für wen*, direkt darunter der Preis oder ein kostenloser
Einstieg. Keiner von ihnen lässt den Besucher raten.

Die Startseite folgt jetzt derselben Reihenfolge, mit einem eigenen Dreh an der
Stelle, an der wir uns tatsächlich unterscheiden:

1. **Das Versprechen und wer dafür geradesteht** – im Kopf, mit den drei
   Schritten direkt darunter.
2. **Das Angebot mit Preisen** – die drei Pakete stehen mit Betrag direkt unter
   dem Kopf, nicht erst drei Klicks später.
3. **Mieten oder besitzen** – die eine Frage, die jeder Besucher mitbringt, der
   rapidmail und CleverReach kennt. Mit Vergleichstabelle.
4. **Die Software** – jetzt klar eingeordnet als „im Saison-Setup enthalten",
   nicht als das Produkt.
5. **Wofür Clubs uns holen**, **wann sich das nicht lohnt**, **Wissen**.

Punkt 3 gibt es bei den Mietanbietern nicht: Wer Software vermietet, kann
schlecht erklären, warum man sie besser besitzt.

### Der Kopf

Zwischenstand war einmal „Wir richten Golfclubs den Newsletter ein." Sachlich
richtig, vollständig – und tot. Ein Satz aus einem Leistungsverzeichnis: kein
Versprechen, keine Spannung, kein Grund weiterzulesen.

Inxmail macht es vor, ohne dass man es abschreiben müsste: **ein Ergebnis, das
sonst ausbleibt – und daneben, wer dafür sorgt.** Der Bau ist immer derselbe:
Zeile, Vorspann, drei konkrete Punkte, ein beruhigender Satz, zwei Knöpfe (einer
verbindlich, einer unverbindlich).

Hier lautet das Ergebnis:

> **Der Clubnewsletter, der wirklich rausgeht. Dafür sorgen wir.**

„Wirklich rausgeht" benennt die Schwachstelle, ohne jemandem etwas vorzuwerfen –
fast jeder Club hat schon einmal einen Newsletter angefangen und wieder
eingestellt. „Dafür sorgen wir" macht daraus ein Angebot statt einer
Beobachtung. Der Vorspann sagt, was wir übernehmen; die drei nummerierten
Schritte sagen, wie – und sie tragen dieselben Nummern wie die drei Pakete im
Abschnitt darunter.

Im Vorspann stand einen Entwurf lang: *„Die meisten Clubnewsletter scheitern
nicht am Text, sondern am Juni: Startzeiten, Telefon, Turnierleitung – und dann
ist der Monat vorbei."* Klingt nach Pointe, ist aber keine. Man kann nicht *an
einem Monat* scheitern; „Text" und „Juni" sind keine vergleichbaren Größen, die
Antithese läuft also leer. Und wer die Seite zum ersten Mal sieht, muss sich
„Juni" erst übersetzen – Hochsaison, Sekretariat im Dauerbetrieb –, wofür der
Satz dann seine eigene Fußnote nachliefert. Ein Bild, das erklärt werden muss,
hat sich seinen Platz in der ersten Zeile nicht verdient.

Dazu kam: Der Vorspann wiederholte, was die Überschrift schon gesagt hatte,
statt weiterzuführen. Jetzt steht dort eine echte Antithese aus zwei
vergleichbaren Größen und danach unsere Rolle:

> Im Sekretariat fehlt selten die Idee, meistens die Zeit. Wir übernehmen die
> Arbeit dazwischen – vom Blick auf den Adressbestand bis zur fertigen Ausgabe
> im Postfach Ihrer Mitglieder.

Der Juni ist nicht verschwunden, er steht jetzt an der richtigen Stelle: im
Zitat weiter unten, gesagt von jemandem aus einem Clubsekretariat, mit dem
Zusammenhang drumherum, den er braucht.

Der Rest ist Handwerk: Die letzte Zeile des Vorspanns war ein Wort lang, also
wurde der Satz umgestellt („Genau die drei Schritte, an denen es hängt,
übernehmen wir") statt an der Breite zu drehen. Und das Handlungsband am
Seitenende hieß „Sehen Sie das Tool an Ihren eigenen Inhalten" – wieder das
Werkzeug im Vordergrund. Jetzt: „Wir sehen uns Ihren Club an. Kostenlos."

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

## Vier Schritte statt vier Funktionen

Die Texte waren lange nach Funktionen gebaut: Newsletter → Segmente →
Automationen → Software. Das beschreibt, was wir einrichten, nicht was der Club
davon hat. Jetzt folgt jede Seite derselben Kette:

**Problem → wirtschaftliche Folge → Lösung → konkreter Nutzen**

Am deutlichsten wird der Unterschied bei den Lösungsseiten. Die alten
Überschriften waren gute Beobachtungen – und blieben bei Schritt eins stehen:

| vorher | jetzt |
|---|---|
| „Die Ausschreibung allein füllt kein Feld" | **Turniere schneller ausbuchen** |
| „Die Adresse liegt seit der Buchung im System. Angesprochen wird sie nie." | **Mehr aus jedem Gastspieler herausholen** |
| „Die ersten Wochen entscheiden, die stillen Jahre kündigen" | **Weniger stille Mitglieder, weniger Kündigungen** |
| „Wer heute anfragt, entscheidet sich selten heute" | **Aus mehr Anfragen werden Mitglieder** |
| „Der Kurs ist erst voll, wenn er voll ist" | **Kurse voll bekommen, bevor sie starten** |

Die alten Sätze sind nicht verloren: Sie stehen jetzt am Anfang des Vorspanns,
gefolgt von dem, was das Problem kostet. Bei den Turnieren zum Beispiel: „Jeder
leere Startplatz ist ein Startgeld, das nicht kommt, plus der Umsatz, der danach
in der Gastronomie liegen geblieben wäre."

### Zwei Rechnungen, die beide nicht getragen haben

Auf der Startseite standen nacheinander zwei Zahlenblöcke. Beide waren sauber
gerechnet, beide sind wieder raus – und die Gründe lohnen sich zu merken.

**Erster Versuch: der Umsatz.** Zehn zusätzliche Buchungen im Monat à 50 €
Deckungsbeitrag, damit sei das Saison-Setup nach drei Monaten bezahlt. Die
Rechnung stand auf einer falschen Annahme: **Mitglieder eines Golfclubs zahlen
einmal im Jahr ihren Beitrag.** Sie „buchen" nichts, was pro Kopf Umsatz
erzeugt. Greenfee und Kursgebühren gibt es, aber nicht bei den 900 Mitgliedern,
mit denen gerechnet worden war. Eine Rechnung, deren Grundgröße nicht stimmt,
ist schlimmer als gar keine.

**Zweiter Versuch: die Zeit.** Zwölf Ausgaben mal dreißig Minuten gleich sechs
Stunden im Jahr. Diesmal stimmte die Rechnung – und arbeitete trotzdem gegen
das eigene Angebot. Sechs Stunden im Jahr sind nichts. Wer das liest, fragt
sich zu Recht, wofür er dafür jemanden bezahlen soll; das Argument erledigt die
Clubbetreuung, also das laufende Geschäft, gleich mit. **Eine Zahl, die den
eigenen Aufwand kleinredet, redet auch den eigenen Wert klein.**

Die Lehre aus beiden: An dieser Stelle gehört keine Zahl hin, sondern ein
Zustand. Dort steht jetzt eine Gegenüberstellung – links, was heute passiert,
rechts, was nach der Einrichtung passiert:

| | Heute | Mit System |
|---|---|---|
| Wer es erfährt | wer zufällig im Clubhaus vorbeigeht | alle im Verteiler, auch im Winter |
| Ob es ankommt | Sammelmail im Blindkopie-Feld, oft im Spam | eigene Domain, mit SPF und DKIM |
| Tote Adressen | bleiben jahrelang in der Liste | werden erkannt und stillgelegt |
| Abmeldungen | per Zuruf ans Sekretariat | Abmeldelink, rechtssicher, ohne Zutun |
| Was der Vorstand sieht | nichts | Zustellungen, Öffnungen, Klicks je Ausgabe |
| Im Juni | bleibt der Newsletter liegen | Redaktionsplan und Automationen laufen weiter |

Jede Zeile ist nachprüfbar, keine behauptet ein Ergebnis. Und der Aufwand kommt
gar nicht mehr vor – die Frage „wie viel Zeit kostet das?" beantworten die
häufigen Fragen, wo sie hingehört und wo sie auch der Hinweis auf die
Clubbetreuung begleitet.

Technisch ist es dieselbe `.data-table` wie überall: Damit stapelt sie sich auf
schmalen Anzeigen von selbst und bekommt ihre Etiketten aus der Kopfzeile. Neu
dazugekommen sind nur die Farben für den dunklen Grund.

### Zwei Stellen, an denen der Ton nicht stimmte

**„Das Werkzeug gab es vor dem Angebot."** Gemeint war Unabhängigkeit, gelesen
wurde: schnell zusammengebaut, für den Eigenbedarf. Dazu stand als
Verkaufsargument „PHP 8 genügt – SQLite reicht als Datenbank" – für einen
Vorstand ist das keine Information, sondern Rauschen. Jetzt heißt der Abschnitt
„Ein Newslettersystem, gebaut für Golfclubs", und aus der PHP-Zeile ist „Auf
Ihrem Webspace – kein zusätzlicher Vertrag, kein eigener Server" geworden. Die
technischen Voraussetzungen stehen weiterhin dort, wo sie hingehören: auf der
Seite „Systemvoraussetzungen".

**„Manchmal ist die Antwort: lassen Sie es."** Ein ganzer Abschnitt, der erklärte,
wann ein Miettarif die bessere Wahl ist. Als Vertrauenssignal gedacht, als
Absage gelesen – auf einer Seite, die Aufträge bringen soll, ist das der falsche
Platz dafür. An seiner Stelle steht jetzt **„Unser Vorgehen"**: die fünf Dinge,
die im Clubcheck geprüft werden, bevor irgendetwas installiert wird –
Adressbestand, Einwilligungen, bisherige Kommunikation, Technik, Segmente. Das
zeigt Sorgfalt, ohne abzuraten.

### Der USP, als Verkaufsargument statt als Fußnote

„Läuft auf dem Server Ihres Clubs" war technisch erklärt. Für einen Vorstand ist
das eine Frage von Abhängigkeit und Eigentum, nicht von Hosting:

> **Kein SaaS. Keine Abhängigkeit.**
> Die Software gehört Ihrem Club – vom Tag der Einrichtung an.

Darunter sechs Argumente, jeweils Vorteil zuerst, Begründung dahinter: einmalige
Einrichtung, läuft auf Ihrem Server, Mitgliederdaten bleiben bei Ihnen, keine
Kosten pro Kontakt, keine Abhängigkeit von einem Anbieter – und der Satz, der am
meisten wiegt: **auch wenn unsere Zusammenarbeit endet, bleibt das System im
Club.** Kein Mietanbieter kann das schreiben.

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

**Wörter, die über ihre Spalte hinausliefen.** Auf der Auswertungsseite standen
„Öffnungsrate", „Klickrate", „Anmeldungen" und „Listenqualität" in einem Band, das
für Zahlen gedacht war: 3 rem Schrift in Spalten von 180 px. Die Wörter passten
nie und liefen ineinander. Dasselbe im Kopf: Marke, Navigation und Knopf ergaben
zusammen 1272 px in einer Spalte von 1144 px, der Kopf ragte auf jeder Seite
rechts über das Raster hinaus.

Beides war lange unsichtbar, weil `body` ein `overflow-x: hidden` trägt – die
Seite rollt dadurch nie waagerecht, aber jede Prüfung über
`documentElement.scrollWidth` läuft ins Leere. **Ein Prüflauf, der nur die
Seitenbreite misst, findet solche Fehler nicht.** Der Lauf vergleicht deshalb
für *jedes* Element `scrollWidth` mit `clientWidth` – unabhängig davon, ob die
Seite als Ganzes rollt. Damit fielen beide Fälle sofort auf.

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

Für den UX-Durchgang drei eigene Läufe:

1. **Rollende Elemente und zu kurze letzte Zeilen**, bei 1440 und 390 px:
   `overflow` auf `auto`/`scroll` **und** Inhalt größer als der Rahmen; dazu
   Überschriften und Absätze, deren letzte Zeile kürzer als 28 % der längsten
   ist. Vorher 43 Fundstellen, darunter sieben rollende Tabellen. Jetzt keine
   rollenden Elemente und vier Umbrüche, die alle im üblichen Rahmen liegen.
2. **Text, der über sein eigenes Kästchen hinausläuft**, bei 1440, 1100, 900,
   700, 500, 390 und 320 px: für jedes Element `scrollWidth` gegen
   `clientWidth`, unabhängig von der Seitenbreite. Das ist der Lauf, der die
   überlaufenden Kennzahlen und den zu breiten Seitenkopf gefunden hat – beides
   war für jede Prüfung unsichtbar, die nur die Seite als Ganzes misst. Jetzt:
   keine Fundstelle.
3. **Waagerechter Überlauf** bei 320, 360 und 390 px – keiner.

## Barrierefreiheit

* Keine externen Schriften, Skripte oder Tracker – nichts verlässt den eigenen Server.
* Menüpunkte mit Untermenü sind `<button aria-expanded>`, nicht Links – bedienbar mit
  Tastatur und Screenreader; Escape schließt, Fokusverlust schließt.
* Sprungmarke zum Inhalt, sichtbarer Tastaturfokus, Brotkrumen mit `aria-current`.
* Icons als Inline-SVG, die Platzansicht ebenfalls als SVG statt als Bilddatei.
* `prefers-reduced-motion` schaltet Bewegung ab und zeigt Endzustände – auch in den Demos.
* Die Demo-Rahmen tragen einen `title`, der beschreibt, was darin zu sehen ist.
