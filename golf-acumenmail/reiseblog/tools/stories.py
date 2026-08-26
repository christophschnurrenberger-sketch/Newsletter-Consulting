# -*- coding: utf-8 -*-
"""Persönliche Erlebnisberichte (Reise-Tagebuch-Stil).

Diese Seiten werden von Hand gepflegt (nicht generiert), tauchen aber
automatisch in blog.html, reiseziele.html und – über die Regions-Pins in
karte.py – auf der Weltkarte auf.

Felder:
  datei   – Dateiname der fertigen HTML-Seite
  titel   – Überschrift für die Karten in Blog/Übersicht
  ziel    – slug des zugehörigen Reiseziels (aus ziele.py)
  region  – Label für die Kategorie-Pille
  teaser  – Kurztext für die Karte
  ph      – Farbklasse des Foto-Platzhalters
  minuten – Lesezeit
"""

STORIES = [
  {
    "datei": "dubai-3-tage.html",
    "titel": "3 Tage Dubai: Zwischen Wolkenkratzern und Wüste",
    "ziel": "dubai",
    "region": "Naher Osten",
    "teaser": "Unser persönlicher Erlebnisbericht: Burj Khalifa, Wüstensafari, "
              "Alt-Dubai am Creek und die Dubai Fountains.",
    "ph": "ph--7",
    "minuten": 15,
  },
]
