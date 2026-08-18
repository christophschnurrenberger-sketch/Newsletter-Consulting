# Dieser Ordner gehört in ein eigenes Repository

Der Auftrag war ein neues Repository namens **Golf Acumenmail**. Die GitHub-App
dieser Sitzung darf keine Repositories anlegen (`403 Resource not accessible by
integration`), deshalb liegt die fertige Website vorerst hier – damit nichts
verloren geht.

## So kommt sie in ihr eigenes Repository

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

## Ansehen

Die Seiten sind PHP mit gemeinsamen Partials – zum Ansehen also einen PHP-Server
starten, nicht die Dateien direkt im Browser öffnen:

```bash
cd golf-acumenmail
php -S 127.0.0.1:8000
```

Alles Weitere steht in `README.md`.
