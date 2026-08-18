#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Macht aus einer gespeicherten Seite des Newslettersystems eine Demo-Seite.

Warum es dieses Werkzeug gibt: Die Demos in demo/ sind keine Nachbildungen –
ihr Markup stammt unveraendert aus dem laufenden System. Genau deshalb muss
vor dem Einchecken zuverlaessig alles verschwinden, was etwas tun oder etwas
verraten koennte: Skripte, Formularlogik, der Zeichenschluessel der Sitzung,
die Adresse des Testservers. Von Hand vergisst man davon immer eines.

Ablauf:

    1. Newslettersystem lokal starten und anmelden.
    2. Die gewuenschte Ansicht im Browser oeffnen und das *gerenderte* Markup
       sichern (Entwicklerwerkzeuge, "Copy outerHTML" auf <html>). Baukasten
       und Ablauf-Editor bauen ihren Inhalt per JavaScript auf – die PHP-Datei
       zu lesen genuegt nicht.
    3. Dieses Werkzeug darauf loslassen:

           python3 tools/demo-saeubern.py seite.html '.ad-card' > teil.html

    4. Den Teil in die passende Datei unter demo/ einsetzen und die
       Stylesheets in assets/demo/ auffrischen.

Der zweite Parameter waehlt den Ausschnitt: entweder eine Zeichenkette, mit
der das oeffnende Tag beginnt (z. B. '<nav class="ad-reiter"'), oder eine
Zahl als Zeichenposition.
"""

import re
import sys

# Tags ohne schliessendes Gegenstueck – sonst zaehlt die Tiefe falsch
LEER = {
    'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input',
    'link', 'meta', 'param', 'source', 'track', 'wbr',
}

TAG = re.compile(r'<(/?)([a-zA-Z][a-zA-Z0-9]*)([^>]*?)(/?)>')


def ausschneiden(quelle: str, start: int) -> str:
    """Schneidet das Element heraus, das an Position start beginnt."""
    i, tiefe = start, 0
    while i < len(quelle):
        treffer = TAG.search(quelle, i)
        if not treffer:
            break
        name = treffer.group(2).lower()
        zu = treffer.group(1) == '/'
        allein = treffer.group(4) == '/' or name in LEER
        if not zu and not allein:
            tiefe += 1
        elif zu:
            tiefe -= 1
            if tiefe == 0:
                return quelle[start:treffer.end()]
        i = treffer.end()
    raise SystemExit('Das Element hoert nicht wieder auf – falsche Startstelle?')


def saeubern(teil: str) -> str:
    """Nimmt alles heraus, was handeln koennte."""
    teil = re.sub(r'<script\b[^>]*>.*?</script>', '', teil, flags=re.S | re.I)
    teil = re.sub(r'\son[a-z]+="[^"]*"', '', teil, flags=re.I)
    teil = re.sub(r"\son[a-z]+='[^']*'", '', teil, flags=re.I)

    # Formulare zu einfachen Kaesten – nichts soll absenden koennen
    teil = re.sub(r'<form\b[^>]*>', '<div class="demo-form">', teil, flags=re.I)
    teil = re.sub(r'</form>', '</div>', teil, flags=re.I)

    teil = re.sub(r'\sname="[^"]*"', '', teil)
    teil = re.sub(r'<input[^>]*type="hidden"[^>]*>', '', teil, flags=re.I)

    teil = re.sub(r'<button\b', '<button type="button" tabindex="-1"', teil, flags=re.I)
    teil = re.sub(r'<button([^>]*?)\stype="submit"', r'<button\1', teil, flags=re.I)
    teil = re.sub(r'<input\b', '<input readonly tabindex="-1"', teil, flags=re.I)
    teil = re.sub(r'<textarea\b', '<textarea readonly tabindex="-1"', teil, flags=re.I)
    teil = re.sub(r'<select\b', '<select tabindex="-1"', teil, flags=re.I)
    teil = re.sub(r'contenteditable="true"', 'contenteditable="false"', teil, flags=re.I)
    teil = re.sub(r'href="(?!#)[^"]*"', 'href="#"', teil)
    return teil


def entschaerfen(teil: str) -> str:
    """Nimmt heraus, was ueber das Testsystem Auskunft gaebe."""
    teil = re.sub(r'\sdata-csrf="[^"]*"', '', teil)
    teil = re.sub(r'https?://(127\.0\.0\.1|localhost)(:\d+)?/',
                  'https://www.golfclub-musterhausen.de/', teil)
    return teil


def main() -> None:
    if len(sys.argv) < 3:
        raise SystemExit(__doc__)

    quelle = open(sys.argv[1], encoding='utf-8').read()
    wahl = sys.argv[2]

    if wahl.isdigit():
        start = int(wahl)
    else:
        start = quelle.find(wahl)
        if start < 0:
            raise SystemExit('Nicht gefunden: ' + wahl)

    sys.stdout.write(entschaerfen(saeubern(ausschneiden(quelle, start))))


if __name__ == '__main__':
    main()
