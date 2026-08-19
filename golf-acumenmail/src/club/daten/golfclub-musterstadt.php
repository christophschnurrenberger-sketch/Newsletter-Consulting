<?php
/**
 * Musterclub – die Vorlage zum Kopieren.
 *
 * Für einen neuen Club: diese Datei kopieren, Dateinamen auf die gewünschte
 * Adresse setzen (aus "gc-ottobeuren.php" wird /club/gc-ottobeuren/) und die
 * Felder ausfüllen. Danach  php tools/build.php  ausführen.
 *
 * Wichtig für den Abschnitt "vorher": Dort gehört die echte letzte Ausgabe des
 * Clubs hinein – gekürzt, aber im Wortlaut. Eine erfundene Fassung würde genau
 * das Vertrauen zerstören, auf dem die ganze Idee beruht.
 */

return [
    'name'     => 'Golfclub Musterstadt e.V.',
    'kurzname' => 'GC Musterstadt',
    'datum'    => '12. August 2026',
    'meta'     => 'Ein konkreter Vorschlag, wie der Newsletter des Golfclubs Musterstadt aussehen könnte.',

    'einleitung' => 'Wir haben Ihre Ausgabe vom Juli genommen und daraus einen Newsletter
        gebaut, wie wir ihn für einen Club Ihrer Größe schreiben würden. Gleicher Inhalt,
        gleiche Termine – nur so aufgebaut, dass er auf dem Handy in zwanzig Sekunden
        erfasst ist.',

    'fakten' => [
        ['1', 'Ausgabe umgebaut'],
        ['3', 'konkrete Verbesserungen'],
        ['20 Min', 'für Sie zum Ansehen'],
    ],

    /* Die bisherige Ausgabe – im Wortlaut des Clubs, nur gekürzt. */
    'vorher' => [
        'datum'   => 'Ausgabe Juli',
        'betreff' => 'Newsletter Golfclub Musterstadt 07/2026',
        'anrede'  => 'Liebe Mitglieder,',
        'absaetze' => [
            'anbei erhalten Sie die aktuellen Informationen aus unserem Club. Am Wochenende vom 12. bis 14. Juli findet unsere Clubmeisterschaft statt. An­meldungen sind ab sofort im Sekretariat oder über die bekannten Wege möglich, die Startliste hängt wie gewohnt aus.',
            'Ausserdem möchten wir darauf hinweisen, dass die Driving Range am 18. Juli wegen Pflegearbeiten ab 12 Uhr gesperrt ist. Der Platz selbst ist normal bespielbar. Weiterhin findet am 26. Juli unser Sommerabend im Clubhaus statt, um Anmeldung wird gebeten.',
            'Der Vorstand weist darauf hin, dass die Beitragsordnung zum 1. September aktualisiert wird. Die neue Fassung finden Sie in Kürze auf unserer Homepage im Mitgliederbereich.',
            'Mit sportlichen Grüßen, Ihr Vorstand',
        ],
        'hinweis' => 'Auszug aus Ihrer Ausgabe vom Juli, aus Platzgründen gekürzt.',
    ],

    /* Derselbe Inhalt, neu aufgebaut. */
    'nachher' => [
        'betreff'     => 'Clubmeisterschaft: noch 14 Startplätze frei',
        'rubrik'      => 'Clubnachrichten',
        'kicker'      => 'Ausgabe 04 · Juli',
        'ueberschrift'=> 'Clubmeisterschaft 2026 – noch 14 Startplätze frei',
        'aufhaenger'  => 'am Wochenende vom 12. bis 14. Juli spielen wir die Clubmeisterschaft. Von 60 Plätzen sind 46 vergeben.',
        'absaetze' => [
            'Gespielt wird in allen Klassen, die Siegerehrung findet am Sonntagabend im Clubhaus statt. Anmeldeschluss ist Mittwoch, 9. Juli.',
            '<strong>Kurz notiert:</strong> Die Driving Range ist am 18. Juli ab 12 Uhr gesperrt (Pflegearbeiten, Platz normal bespielbar). Der Sommerabend am 26. Juli ist bereits zur Hälfte ausgebucht.',
        ],
        'knopf' => 'Jetzt Startzeit sichern',
    ],

    'verbesserungen' => [
        [
            'titel'   => 'Der Betreff nennt den Grund zu öffnen',
            'text'    => 'Aus <em>„Newsletter 07/2026“</em> wird <em>„noch 14 Startplätze frei“</em>.
                          Eine Zahl und eine Frist sagen in vier Wörtern, warum diese Mail jetzt
                          wichtig ist – und nicht erst am Wochenende.',
            'wirkung' => 'Betrifft jede Ausgabe, kostet keine Minute Mehrarbeit.',
        ],
        [
            'titel'   => 'Ein Thema führt, der Rest folgt',
            'text'    => 'Bisher stehen Clubmeisterschaft, Range-Sperrung, Sommerabend und
                          Beitragsordnung gleichberechtigt untereinander. Neu: ein Thema oben mit
                          Bild und Knopf, die übrigen als kurze Notizen darunter.',
            'wirkung' => 'Wer nur zehn Sekunden hat, hat trotzdem das Wichtigste gelesen.',
        ],
        [
            'titel'   => 'Ein Knopf statt „im Sekretariat möglich“',
            'text'    => 'Die Anmeldung liegt einen Klick entfernt statt hinter einem Anruf.
                          Der Knopf führt direkt in Ihre bestehende Turnieranmeldung – dort ändert
                          sich nichts.',
            'wirkung' => 'An­meldungen lassen sich damit erstmals der Ausgabe zuordnen.',
        ],
    ],

    'ausblick' => 'Eine einzelne Ausgabe besser zu machen, ist der kleinere Teil. Der größere
        ist alles, was danach ohne weiteres Zutun läuft – und die Frage, wer welche Post
        überhaupt bekommt.',

    'moeglichkeiten' => [
        '<strong>Nur die richtige Handicap-Klasse anschreiben</strong> statt aller 900 Mitglieder – die Ausschreibung trifft, wen sie betrifft.',
        '<strong>Eine Erinnerung fünf Tage vorher</strong>, automatisch und nur an die, die sich noch nicht angemeldet haben.',
        '<strong>Eine Willkommens­strecke für neue Mitglieder</strong>, die einmal gebaut wird und dann jahrelang von allein läuft.',
        '<strong>Gastspieler mit eigener Ansprache</strong> – die Adressen liegen seit der Greenfee-Buchung ohnehin im System.',
        '<strong>Am Saison­ende eine Auswertung</strong>, die zeigt, was die Kommunikation gebracht hat.',
    ],

    'abschluss' => 'Wenn Sie mögen, gehen wir das in einer halben Stunde gemeinsam durch –
        telefonisch oder per Video, mit Vorstand, Sekretariat oder beiden. Sie bekommen eine
        ehrliche Einschätzung, auch wenn die lautet, dass sich der Aufwand für Ihren Club
        gerade nicht lohnt.',
];
