<?php
/**
 * Die Bildmarke als eingebettetes SVG.
 *
 * Absichtlich inline statt als Bilddatei: Sie steht auf jeder Seite im Kopf
 * und im Fuß, ist keine 500 Byte groß und spart so zwei Anfragen.
 *
 * Die Farben stehen nicht als Attribut, sondern als Klasse – damit die Marke
 * auf dunklem Grund umschlagen kann: Dort fällt die lindgrüne Fläche weg und
 * die Zeichnung selbst wird lindgrün. Ohne das verschwände der dunkle Balken
 * unter der Fahnenstange im dunklen Fuß.
 *
 * Dieselbe Zeichnung liegt zusätzlich als assets/logo.svg mit fest
 * eingetragenen Farben – von dort stammen das Lesezeichen-Symbol und das
 * Vorschaubild fürs Teilen. Wer eine ändert, ändert bitte auch die andere.
 */
?>
<svg class="marke" viewBox="0 0 64 64" aria-hidden="true" focusable="false">
    <rect class="marke-flaeche" width="64" height="64"/>
    <path class="marke-tinte" d="M20 10 L45 10 L53 19 L45 28 L20 28 Z"/>
    <path class="marke-flaeche" d="M20 10 L45 10 L32.5 21 Z"/>
    <rect class="marke-tinte" x="15" y="7" width="5" height="45"/>
    <rect class="marke-tinte" y="52" width="64" height="12"/>
</svg>
