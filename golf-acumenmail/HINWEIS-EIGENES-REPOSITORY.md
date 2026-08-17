# Dieser Ordner gehört in ein eigenes Repository

Der Auftrag war ein neues Repository namens **Golf Acumenmail**. Die
GitHub-App dieser Sitzung darf keine Repositories anlegen (`403 Resource not
accessible by integration`), deshalb liegt die fertige Seite vorerst hier –
damit nichts verloren geht.

## So kommt sie in ihr eigenes Repository

1. Auf <https://github.com/new> ein leeres Repository **`Golf-Acumenmail`**
   anlegen (ohne README, ohne .gitignore, ohne Lizenz).
2. Dann lokal:

```bash
git clone https://github.com/christophschnurrenberger-sketch/Newsletter-Consulting
cd Newsletter-Consulting
git checkout claude/golf-acumenmail-newsletter-u9szp7

# Inhalt des Ordners als eigenständiges Repository ablegen
cp -r golf-acumenmail /tmp/Golf-Acumenmail
cd /tmp/Golf-Acumenmail
rm HINWEIS-EIGENES-REPOSITORY.md

git init
git add -A
git commit -m "Golf-Landingpage auf Basis von AcumenMail"
git branch -M main
git remote add origin https://github.com/christophschnurrenberger-sketch/Golf-Acumenmail
git push -u origin main
```

Danach kann dieser Ordner aus `Newsletter-Consulting` gelöscht werden.

Alternativ: Sobald das leere Repository existiert, kann eine neue
Claude-Sitzung es direkt bespielen.
