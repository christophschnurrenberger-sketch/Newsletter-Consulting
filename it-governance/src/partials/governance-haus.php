<?php
/**
 * Das Governance-Haus als Zeichnung.
 *
 * Ein Bild, das in fast jedem Erstgespräch entsteht: oben die Ziele und die
 * Regulatorik, darunter Steuerung, Rollen, Prozesse und Kontrollen, unten das
 * Fundament aus Dokumentation und Inventar. Wer eine Ebene überspringt, baut
 * eine Etage in die Luft – das ist der ganze Punkt der Zeichnung.
 *
 * Die Farben kommen über CSS-Klassen, damit dieselbe Zeichnung auf hellem und
 * auf dunklem Grund funktioniert (.diagram.is-navy).
 */
?>
<svg class="haus" viewBox="0 0 560 408" role="img" aria-labelledby="haus-titel haus-text">
    <title id="haus-titel">Das Governance-Haus</title>
    <desc id="haus-text">Von oben nach unten: Ziele und Regulatorik als Dach, darunter
        Steuerung und Gremien, Rollen und Verantwortlichkeiten, IT-Prozesse sowie
        Kontrollen und Nachweise. Das Fundament bilden Dokumentation, Inventar,
        Kennzahlen und Dienstleistersteuerung.</desc>

    <polygon class="haus-roof" points="280,18 548,104 12,104"></polygon>
    <text class="haus-text is-roof" x="280" y="80" text-anchor="middle">Ziele · Risikoappetit · Regulatorik</text>

    <rect class="haus-layer" x="62" y="116" width="436" height="52" rx="8"></rect>
    <text class="haus-text" x="86" y="139">Steuerung: Gremien, Entscheidungswege, Richtlinien</text>
    <text class="haus-sub" x="86" y="157">Wer entscheidet worüber – und wo steht das?</text>

    <rect class="haus-layer" x="62" y="176" width="436" height="52" rx="8"></rect>
    <text class="haus-text" x="86" y="199">Rollen &amp; Verantwortlichkeiten</text>
    <text class="haus-sub" x="86" y="217">Benannt, besetzt, vertreten, dokumentiert</text>

    <rect class="haus-layer" x="62" y="236" width="436" height="52" rx="8"></rect>
    <text class="haus-text" x="86" y="259">IT-Prozesse: Demand · Service · Change · Zugriff</text>
    <text class="haus-sub" x="86" y="277">Gelebt, nicht nur gemalt</text>

    <rect class="haus-layer is-accent" x="62" y="296" width="436" height="52" rx="8"></rect>
    <text class="haus-text" x="86" y="319">Kontrollen &amp; Nachweise</text>
    <text class="haus-sub" x="86" y="337">Was der Prüfer sehen will: Belege, keine Absichten</text>

    <rect class="haus-base" x="12" y="356" width="536" height="42" rx="8"></rect>
    <text class="haus-text is-base" x="280" y="382" text-anchor="middle">Fundament: Dokumentation · Inventar · Kennzahlen · Dienstleister</text>

    <g class="haus-marks">
        <circle cx="78" cy="142" r="4"></circle>
        <circle cx="78" cy="202" r="4"></circle>
        <circle cx="78" cy="262" r="4"></circle>
        <circle cx="78" cy="322" r="4"></circle>
    </g>
</svg>
