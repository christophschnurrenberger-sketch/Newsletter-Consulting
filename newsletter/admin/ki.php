<?php
/**
 * ki.php – holt einen Textvorschlag vom Sprachmodell.
 *
 * Wird vom Baukasten per fetch aufgerufen und antwortet immer mit JSON.
 * Ohne hinterlegten Anbieter samt Schlüssel geht hier gar nichts hinaus –
 * dann meldet die Seite schlicht, dass der Assistent nicht eingerichtet ist.
 *
 *   POST aktion=…  text=…  hinweis=…   → {ok:true, text:"…"}
 */

require_once dirname(__DIR__) . '/lib/bootstrap.php';

Auth::require('kampagnen');

if (!Util::isPost()) {
    Util::json(['ok' => false, 'fehler' => 'Nur POST erlaubt.'], 405);
}
Util::requireCsrf();

if (!Ai::available()) {
    Util::json(['ok' => false, 'fehler' => 'Der Textassistent ist nicht eingerichtet. '
        . 'Unter Einstellungen lässt sich ein Anbieter samt Schlüssel hinterlegen.'], 409);
}

$aktion = Util::post('aktion');
if (!isset(Ai::ACTIONS[$aktion])) {
    Util::json(['ok' => false, 'fehler' => 'Unbekannte Aufgabe.'], 422);
}

try {
    $vorschlag = Ai::suggest($aktion, Util::postRaw('text'), Util::postRaw('hinweis'));
} catch (Throwable $e) {
    // Der Wortlaut stammt aus Ai und ist für die Redaktion gedacht.
    Log::warn('ki', 'Textvorschlag fehlgeschlagen: ' . $e->getMessage());
    Util::json(['ok' => false, 'fehler' => $e->getMessage()], 502);
}

Log::info('ki', 'Textvorschlag geholt (' . $aktion . ', ' . Ai::provider() . ').');
Util::json(['ok' => true, 'text' => $vorschlag]);
