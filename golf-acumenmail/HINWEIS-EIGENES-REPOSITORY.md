# Ansehen und Umzug in ein eigenes Repository

## Ansehen

**`index.html` in diesem Ordner doppelklicken.** Mehr ist nicht nötig – die Seite
ist fertiges HTML mit relativen Verweisen. Alle 28 Seiten, Navigation, Mega-Menü
und die bewegten Demos funktionieren ohne Server.

Nur das Kontaktformular braucht PHP (`kontakt-senden.php`); lokal öffnet sich die
Seite trotzdem, das Absenden geht ins Leere.

## Ändern

Bearbeitet wird `src/`, nicht die `.html`-Dateien – die werden erzeugt:

```bash
php tools/build.php
```

Alles Weitere steht in `README.md`.

---

## Dieser Ordner gehört in ein eigenes Repository

Der Auftrag war ein neues Repository namens **Golf Acumenmail**. Die GitHub-App
dieser Sitzung darf keine Repositories anlegen (`403 Resource not accessible by
integration`), deshalb liegt die Website vorerst hier – damit nichts verloren geht.

1. Auf <https://github.com/new> ein leeres Repository **`Golf-Acumenmail`**
   anlegen (ohne README, ohne .gitignore, ohne Lizenz).
2. Dann lokal:

```bash
git clone https://github.com/christophschnurrenberger-sketch/Newsletter-Consulting
cd Newsletter-Consulting
git checkout claude/golf-acumenmail-newsletter-u9szp7

cp -r golf-acumenmail /tmp/Golf-Acumenmail
cd /tmp/Golf-Acumenmail
rm HINWEIS-EIGENES-REPOSITORY.md

git init
git add -A
git commit -m "Website fuer Newsletter-Marketing in Golfclubs"
git branch -M main
git remote add origin https://github.com/christophschnurrenberger-sketch/Golf-Acumenmail
git push -u origin main
```

Danach kann dieser Ordner aus `Newsletter-Consulting` gelöscht werden.
