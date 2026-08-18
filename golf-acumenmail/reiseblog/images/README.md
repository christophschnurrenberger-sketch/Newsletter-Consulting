# Bilder-Ordner

Lege hier deine Fotos ab (z. B. von deinem Instagram-Account
[@zuhause_in_der_welt_2022](https://www.instagram.com/zuhause_in_der_welt_2022/)).

## So ersetzt du einen Platzhalter durch ein echtes Foto

Im Code stehen aktuell farbige Platzhalter wie:

```html
<div class="ph ph--1" data-label="Foto: Japan"></div>
```

Ersetze so einen Block einfach durch dein Bild:

```html
<img src="images/japan-kirschbluete.jpg" alt="Kirschblüten in Tokio" loading="lazy">
```

## Empfehlungen
- Format: **JPG** (Fotos) oder **WebP** (kleiner, moderner)
- Breite: ca. **1600 px** für große Bilder, **800 px** für Kartenbilder
- Vor dem Hochladen **komprimieren** (z. B. squoosh.app), damit die Seite schnell lädt
- Immer ein aussagekräftiges `alt`-Attribut setzen (gut für SEO & Barrierefreiheit)
