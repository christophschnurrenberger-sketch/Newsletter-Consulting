# Schnittstelle (REST-API)

Über die API lassen sich Daten von außen einspielen (Mitglieder, Turniere) und
abrufen (Empfänger, Listen, Kennzahlen) – zum Beispiel aus einer Clubverwaltung
wie PC CADDIE, direkt oder über ein kleines Zwischenskript, das einen
Mitglieder-Export einliest.

Die Schlüssel-Verwaltung und dieselbe Kurz-Anleitung stehen auch in der
Oberfläche unter **System → Schnittstelle**.

## Basis-Adresse

```
{basis}/api/v1.php/<ressource>
z. B.  https://verein.de/newsletter/api/v1.php/subscribers
```

Jede Instanz (Marke/Installation) hat ihre eigenen Schlüssel und ihre eigenen
Daten. Ein Schlüssel der einen Installation öffnet keine andere.

## Anmeldung

Bei jeder Anfrage einen API-Schlüssel mitschicken (Verwaltung → Schnittstelle →
*Neuen Schlüssel anlegen*). Empfohlen im Header:

```
Authorization: Bearer acm_xxxxxxxxxxxxxxxxxxxxxxxx
```

Alternativ `X-Api-Key: acm_…` oder – nur wenn Header nicht möglich sind –
`?api_key=acm_…` an die Adresse.

Rechte je Schlüssel: **read** darf nur `GET`, **write** auch `POST`/`DELETE`.

## Antwortformat

Immer JSON.

```json
{ "ok": true,  "data": … , "meta": { … } }
{ "ok": false, "error": { "code": "…", "message": "…" } }
```

HTTP-Codes: `200` ok · `201` angelegt · `400/422` Eingabefehler ·
`401` kein/ungültiger Schlüssel · `403` fehlende Rechte · `404` nicht gefunden ·
`405` Methode nicht erlaubt · `429` zu viele Anfragen (max. 240/Minute je
Schlüssel) · `500` interner Fehler.

## Endpunkte

| Methode | Pfad | Zweck | Recht |
|---------|------|-------|-------|
| GET | `/ping` | Verbindung & Schlüssel prüfen | read |
| GET | `/subscribers` | Empfänger auflisten | read |
| GET | `/subscribers/{id\|email}` | einen Empfänger abrufen | read |
| POST | `/subscribers` | Empfänger anlegen/aktualisieren | write |
| POST | `/subscribers/bulk` | viele auf einmal | write |
| DELETE | `/subscribers/{id\|email}` | Empfänger abmelden | write |
| GET | `/lists` | Listen auflisten | read |
| POST | `/lists` | Liste anlegen | write |
| GET | `/content` | Redaktionspool lesen | read |
| POST | `/content` | Eintrag/Turnier schreiben | write |
| GET | `/campaigns` | Newsletter mit Kennzahlen | read |

### GET /subscribers

Filter (Query-Parameter): `list_id`, `status` (`active`/`pending`/…),
`q` (Suche in E-Mail/Name), `created_since` (`JJJJ-MM-TT`),
`limit` (max. 500, Vorgabe 50), `offset`.

```
GET /subscribers?list_id=1&status=active&limit=100
→ { "ok": true, "data": [ … ], "meta": { "total": 812, "limit": 100, "offset": 0 } }
```

### POST /subscribers  (anlegen oder aktualisieren, Schlüssel = E-Mail)

```json
{
  "email": "max@example.de",
  "first_name": "Max",
  "last_name": "Muster",
  "salutation": "Herr",
  "company": "",
  "birthday": "1980-05-13",
  "status": "active",
  "lists": ["Clubnachrichten"],
  "custom": { "mitgliedsnummer": "A-1234", "handicap": "12.4" }
}
```

- `email` ist Pflicht; alle anderen Felder sind optional.
- `birthday` versteht `JJJJ-MM-TT` und `TT.MM.JJJJ`.
- `status`: `active` (Vorgabe, kein Extra-Mailversand) oder `pending`
  (verschickt die Bestätigungsmail / Double-Opt-in).
- `lists`: Namen **oder** IDs. Unbekannte Namen werden übergangen.
- `custom`: eigene Felder (z. B. Mitgliedsnummer). Werden beim Aktualisieren
  zusammengeführt, nicht überschrieben.
- Gibt es die Adresse schon, wird sie **aktualisiert** (`outcome: updated`),
  sonst **angelegt** (`outcome: created`, HTTP 201). Adressen auf der
  Sperrliste werden übergangen (`outcome: skipped`).

### POST /subscribers/bulk  (Massen-Sync)

```json
{
  "status": "active",
  "lists": ["Clubnachrichten"],
  "subscribers": [
    { "email": "a@example.de", "first_name": "Anna" },
    { "email": "b@example.de", "first_name": "Ben", "lists": ["Turniere"] }
  ]
}
```

Bis zu 2000 Einträge je Anfrage. `lists`/`status` gelten für alle, je Eintrag
überschreibbar. Antwort:

```json
{ "ok": true, "data": {
    "summary": { "created": 2, "updated": 0, "skipped": 0 },
    "results": [ { "email": "a@example.de", "outcome": "created", "id": 41 }, … ] } }
```

### POST /content  (Turnier/Eintrag in den Redaktionspool)

```json
{ "category": "turniere", "title": "Captains Cup", "item_date": "2026-09-20",
  "link_url": "https://verein.de/anmelden", "link_label": "Anmelden" }
```

Rubriken (`category`): `turniere`, `veranstaltungen`, `platz`, `training`,
`proshop`, `gastronomie`, `news`. Turniere mit Datum erscheinen automatisch in
Wochennews und in der Turnier-Kommunikation.

## Beispiel (curl)

```bash
curl -X POST https://verein.de/newsletter/api/v1.php/subscribers \
  -H "Authorization: Bearer acm_…" \
  -H "Content-Type: application/json" \
  -d '{"email":"max@example.de","first_name":"Max","lists":["Clubnachrichten"]}'
```

## Anbindung an PC CADDIE & andere Clubverwaltungen

Diese Schnittstelle nimmt Mitgliederdaten aus jeder Quelle entgegen, die JSON
senden kann. In der Praxis:

1. **Export → bulk:** Ein kleines Skript liest den Mitglieder-Export (z. B. CSV
   aus PC CADDIE) und überträgt ihn per `POST /subscribers/bulk`. Als
   `custom.mitgliedsnummer` lässt sich die Mitgliedsnummer mitführen.
2. **Turniere → content:** Turniertermine gehen per `POST /content`
   (`category: turniere`) in den Pool.

Ein aktiver „von der Clubverwaltung abrufen“-Anschluss (die Daten also von dort
ziehen statt entgegenzunehmen) lässt sich ergänzen, sobald die Zugangsdaten und
die Schnittstellen-Doku der jeweiligen Clubverwaltung vorliegen.
