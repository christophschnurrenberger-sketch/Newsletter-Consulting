<?php
/**
 * Platzansicht als Inline-SVG: Himmel, Baumreihe, Fairway, Bunker, Grün mit
 * Fahne. Statt einer Bilddatei, damit sie in jeder Größe scharf bleibt und
 * nichts nachgeladen werden muss.
 *
 * Die Verlaufs-IDs müssen im Dokument eindeutig sein – kommt die Grafik auf
 * einer Seite mehrfach vor, zählt $GLOBALS['golf_scene_n'] mit.
 */
$GLOBALS['golf_scene_n'] = ($GLOBALS['golf_scene_n'] ?? 0) + 1;
$n = $GLOBALS['golf_scene_n'];
?>
<svg class="golf-scene" viewBox="0 0 320 120" preserveAspectRatio="xMidYMid slice" role="img" aria-label="Golfplatz mit Fahne am Grün">
    <defs>
        <linearGradient id="gs-sky-<?= $n ?>" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#A9CDE4"></stop>
            <stop offset="1" stop-color="#E6F1F7"></stop>
        </linearGradient>
        <linearGradient id="gs-turf-<?= $n ?>" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#63A87B"></stop>
            <stop offset="1" stop-color="#2B6E48"></stop>
        </linearGradient>
    </defs>
    <rect width="320" height="120" fill="url(#gs-sky-<?= $n ?>)"></rect>
    <path d="M0 56c14-9 22 2 34-4s18 5 30 1 20-8 32-3 22 7 34 2 24-6 36 0 26 8 38 3 30-6 42 0v14H0z" fill="#3E7A57" opacity=".5"></path>
    <path d="M0 64c50-12 110 6 168-2s102-14 152-4v62H0z" fill="url(#gs-turf-<?= $n ?>)"></path>
    <path d="M92 120c8-30 28-45 64-51 30-5 52 4 66 14-20 14-42 23-60 37z" fill="#8CC79F" opacity=".45"></path>
    <ellipse cx="66" cy="98" rx="25" ry="8" fill="#E2D6B8"></ellipse>
    <ellipse cx="66" cy="96" rx="25" ry="8" fill="#F3EAD6"></ellipse>
    <ellipse cx="216" cy="82" rx="40" ry="12" fill="#93CFA6" opacity=".7"></ellipse>
    <path d="M216 81V52" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"></path>
    <path d="M216 53l-17 5 17 5z" fill="#C0392B"></path>
    <circle cx="216" cy="82" r="2.6" fill="#16261C" opacity=".45"></circle>
</svg>
