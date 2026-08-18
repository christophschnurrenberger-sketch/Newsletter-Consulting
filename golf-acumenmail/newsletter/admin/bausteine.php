<?php
/**
 * bausteine.php – gesicherte Bausteine des Baukastens.
 *
 * Der Baukasten holt und speichert hierüber, was sich wiederverwenden
 * lässt. Antwort ist immer JSON.
 *
 *   GET  ?liste=1        → alle gesicherten Bausteine
 *   POST sichern=…&name= → Baustein sichern
 *   POST einsetzen=<id>  → Bausteine zum Einsetzen holen
 *   POST loeschen=<id>   → gesicherten Baustein entfernen
 */

require_once dirname(__DIR__) . '/lib/bootstrap.php';

Auth::require('kampagnen');

if (!Util::isPost()) {
    Util::json(['ok' => true, 'bausteine' => Snippets::all()]);
}

Util::requireCsrf();
$benutzer = Auth::user();
$wer      = (string) ($benutzer['name'] ?? $benutzer['email'] ?? '');

/* ------------------------------------------------------------ Sichern */

if (Util::postRaw('sichern') !== '') {
    try {
        $id = Snippets::save(Util::post('name'), Util::postRaw('sichern'), $wer);
    } catch (Throwable $e) {
        Util::json(['ok' => false, 'fehler' => $e->getMessage()], 422);
    }
    Log::info('baukasten', 'Baustein gesichert: ' . Util::post('name'));
    Util::json(['ok' => true, 'id' => $id, 'bausteine' => Snippets::all()]);
}

/* ---------------------------------------------------------- Einsetzen */

if (Util::postInt('einsetzen') > 0) {
    $bausteine = Snippets::blocks(Util::postInt('einsetzen'));
    if ($bausteine === []) {
        Util::json(['ok' => false, 'fehler' => 'Diesen Baustein gibt es nicht mehr.'], 404);
    }
    Util::json(['ok' => true, 'blocks' => $bausteine]);
}

/* ------------------------------------------------------------ Löschen */

if (Util::postInt('loeschen') > 0) {
    if (!Snippets::delete(Util::postInt('loeschen'))) {
        Util::json(['ok' => false, 'fehler' => 'Diesen Baustein gibt es nicht mehr.'], 404);
    }
    Util::json(['ok' => true, 'bausteine' => Snippets::all()]);
}

Util::json(['ok' => false, 'fehler' => 'Unbekannte Anfrage.'], 400);
