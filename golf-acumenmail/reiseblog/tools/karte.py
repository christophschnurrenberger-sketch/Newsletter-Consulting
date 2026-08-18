# -*- coding: utf-8 -*-
"""Geo-Daten für die interaktive Weltkarte.

Pro Reiseziel (Schlüssel = slug aus ziele.py):
  iso     – ISO-3-Code des Landes (fürs Hervorheben der Landfläche).
            Mehrere Ziele im selben Land: Suffix 2, 3 … anhängen (z. B. "ITA2").
  at      – [Längengrad, Breitengrad] des Länder-Markers in der Weltansicht
  box     – Kartenausschnitt beim Hineinzoomen: [West, Süd, Ost, Nord]
  regions – Liste besuchter Regionen: (Name, Längengrad, Breitengrad, Ziel-Seite)
            Ohne "url" führt der Pin auf die Reisebericht-Seite des Ziels.

Neues Ziel ergänzen: hier einen Eintrag anlegen -> es erscheint automatisch
auf der Karte. Fehlt der Eintrag, wird das Ziel auf der Karte übersprungen.
"""

KARTE = {
 "seychellen": {
   "iso":"SYC", "at":[55.7,-4.6], "box":[55.0,-5.0,56.3,-3.9],
   "regions":[("Mahé",55.48,-4.68,None), ("Praslin",55.74,-4.32,None), ("La Digue",55.84,-4.35,None)] },
 "malediven": {
   "iso":"MDV", "at":[73.2,3.9], "box":[72.2,2.5,74.1,5.9],
   "regions":[("Malé",73.51,4.17,None), ("Baa-Atoll",73.05,5.2,None), ("Ari-Atoll",72.83,3.7,None)] },
 "namibia": {
   "iso":"NAM", "at":[15.9,-22.5], "box":[13.0,-26.0,17.9,-17.6],
   "regions":[("Sossusvlei",15.29,-24.73,None), ("Swakopmund",14.53,-22.68,None), ("Etosha",16.33,-18.85,None)] },
 "suedafrika": {
   "iso":"ZAF", "at":[22.5,-31.0], "box":[17.2,-35.0,33.0,-23.0],
   "regions":[("Kapstadt",18.42,-33.92,None), ("Garden Route",23.05,-34.04,None), ("Krüger-NP",31.5,-24.0,None)] },
 "dubai": {
   "iso":"ARE", "at":[55.27,25.2], "box":[54.6,24.4,56.1,25.6],
   "regions":[("Burj Khalifa",55.274,25.197,"dubai-3-tage.html"),
              ("Wüste",55.7,24.85,"dubai-3-tage.html"),
              ("Marina",55.14,25.08,None)] },
 "thailand": {
   "iso":"THA", "at":[100.5,15.5], "box":[97.2,6.6,101.8,19.9],
   "regions":[("Bangkok",100.50,13.76,None), ("Chiang Mai",98.98,18.79,None), ("Krabi",98.91,8.09,None)] },
 "usa-westkueste": {
   "iso":"USA", "at":[-118.5,36.2], "box":[-124.0,32.0,-110.5,39.0],
   "regions":[("San Francisco",-122.42,37.77,None), ("Los Angeles",-118.24,34.05,None),
              ("Las Vegas",-115.14,36.17,None), ("Grand Canyon",-112.14,36.06,None)] },
 "costa-rica": {
   "iso":"CRI", "at":[-84.2,10.1], "box":[-85.4,9.0,-83.5,11.0],
   "regions":[("Arenal",-84.70,10.46,None), ("Monteverde",-84.82,10.30,None), ("Manuel Antonio",-84.14,9.39,None)] },
 "mexiko-yucatan": {
   "iso":"MEX", "at":[-88.3,20.6], "box":[-90.6,19.8,-86.2,21.8],
   "regions":[("Cancún",-86.85,21.16,None), ("Chichén Itzá",-88.57,20.68,None),
              ("Mérida",-89.62,20.97,None), ("Tulum",-87.46,20.21,None)] },
 "kreta": {
   "iso":"GRC", "at":[24.8,35.3], "box":[23.2,34.7,26.6,35.9],
   "regions":[("Chania",24.02,35.51,None), ("Knossos",25.16,35.30,None), ("Elafonissi",23.54,35.27,None)] },
 "mallorca": {
   "iso":"ESP", "at":[2.9,39.6], "box":[2.1,39.1,3.6,40.1],
   "regions":[("Palma",2.65,39.57,None), ("Tramuntana",2.80,39.75,None), ("Alcúdia",3.12,39.85,None)] },
 "sardinien": {
   "iso":"ITA", "at":[9.0,40.1], "box":[7.9,38.7,10.1,41.4],
   "regions":[("La Pelosa",8.20,40.95,None), ("Olbia",9.50,40.92,None), ("Cala Gonone",9.62,40.28,None)] },
 "portugal": {
   "iso":"PRT", "at":[-8.4,39.6], "box":[-9.8,36.7,-6.7,41.5],
   "regions":[("Lissabon",-9.14,38.72,None), ("Porto",-8.61,41.15,None), ("Algarve",-8.67,37.10,None)] },
 "sizilien": {
   "iso":"ITA2", "at":[14.1,37.6], "box":[12.1,36.4,15.9,38.5],
   "regions":[("Palermo",13.36,38.12,None), ("Ätna",15.00,37.6,None), ("Taormina",15.28,37.85,None)] },
}
