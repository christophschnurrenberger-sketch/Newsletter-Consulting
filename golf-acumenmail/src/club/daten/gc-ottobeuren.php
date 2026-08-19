<?php
/**
 * Allgäuer Golf- & Landclub Ottobeuren – Beispiel für einen echten Club.
 *
 * ACHTUNG, vor dem Versenden zu erledigen:
 *
 * 1. Der Abschnitt 'vorher' ist ein PLATZHALTER. Hier muss die echte letzte
 *    Ausgabe des Clubs stehen – im Wortlaut, nur gekürzt. Eine erfundene
 *    Fassung würde genau das Vertrauen zerstören, auf dem die Idee beruht:
 *    Der Empfänger erkennt sofort, ob das seine Mail ist oder nicht.
 *    Solange der Platzhalter drinsteht, weist die Seite selbst darauf hin.
 *
 * 2. Die genaue Schreibweise des Clubnamens auf deren Website prüfen.
 *
 * Der Abschnitt 'nachher' und die Verbesserungen greifen bewusst Themen auf,
 * die auf der Website des Clubs stehen (6-Loch-Kurzplatz, Golf & Natur,
 * Wohnmobil-Stellplätze). Genau das erzeugt den Eindruck, dass sich jemand
 * tatsächlich mit dem Club beschäftigt hat.
 */

return [
    'name'     => 'Allgäuer Golf- & Landclub Ottobeuren e.V.',
    'kurzname' => 'Allgäuer Golf- & Landclub',
    'datum'    => '18. August 2026',
    'meta'     => 'Ein konkreter Vorschlag für den Newsletter des Allgäuer Golf- & Landclubs Ottobeuren.',

    'einleitung' => 'Wir haben uns Ihre Website angesehen – den 6-Loch-Kurzplatz zum
        Kennenlernen, „Golf &amp; Natur“, die Wohnmobil-Stellplätze. Das sind Themen, um die
        andere Clubs Sie beneiden. In der Mitgliederpost tauchen solche Dinge erfahrungsgemäß
        selten auf. Also haben wir eine Ausgabe gebaut, die genau daraus besteht.',

    'fakten' => [
        ['1', 'Ausgabe umgebaut'],
        ['3', 'konkrete Verbesserungen'],
        ['20 Min', 'für Sie zum Ansehen'],
    ],

    /* PLATZHALTER – siehe Hinweis im Dateikopf. */
    'vorher' => [
        'datum'   => 'letzte Ausgabe',
        'betreff' => 'Newsletter Ausgabe 03/2026',
        'anrede'  => 'Liebe Mitglieder,',
        'absaetze' => [
            'An dieser Stelle steht im fertigen Beispiel Ihre tatsächliche letzte Ausgabe – im Wortlaut, lediglich gekürzt.',
            'Typisch für Clubnewsletter ist ein durchgehender Fließtext, in dem Turniertermine, Platzpflege, Gastronomie und Vereinsformalien gleichberechtigt untereinander stehen, ohne Überschriften und ohne einen Knopf, über den man direkt zur Anmeldung käme.',
            'Genau daran setzt der Vorschlag auf der rechten Seite an.',
            'Mit sportlichen Grüßen, Ihr Vorstand',
        ],
        'hinweis' => 'Platzhalter: Hier gehört die echte letzte Ausgabe des Clubs hinein. '
                   . 'Diese Seite ist erst versandfertig, wenn sie ersetzt wurde.',
    ],

    'nachher' => [
        'betreff'     => 'Kurzplatz, Wohnmobile und ein Wiesenstück, das bleiben darf',
        'rubrik'      => 'Clubnachrichten',
        'kicker'      => 'Ausgabe 04 · August',
        'ueberschrift'=> 'Golfen zum Kennenlernen: Der 6-Loch-Kurzplatz füllt sich',
        'aufhaenger'  => 'wenn Sie jemanden kennen, der schon immer einmal Golf ausprobieren wollte – der Kurzplatz ist genau dafür da, und im September sind noch Termine frei.',
        'absaetze' => [
            'Sechs Löcher, kein Handicap nötig, Leihschläger vor Ort. Für viele unserer heutigen Mitglieder war das der erste Kontakt mit dem Sport.',
            '<strong>Kurz notiert:</strong> Die neuen Wohnmobil-Stellplätze sind ab sofort buchbar. Und im Rahmen von „Golf &amp; Natur“ bleibt das Wiesenstück östlich von Bahn 7 diesen Sommer ungemäht – wer dort einen Ball sucht, weiß jetzt, warum.',
        ],
        'knopf' => 'Kurzplatz-Termin sichern',
    ],

    'verbesserungen' => [
        [
            'titel'   => 'Der Betreff verrät, worum es geht',
            'text'    => '<em>„Newsletter Ausgabe 03/2026“</em> sagt nichts – es könnte alles
                          drinstehen oder nichts. Ein Betreff, der ein konkretes Thema nennt,
                          entscheidet in der Vorschau des Postfachs darüber, ob die Mail geöffnet
                          wird oder nicht.',
            'wirkung' => 'Wirkt bei jeder Ausgabe, kostet keine Minute Mehrarbeit.',
        ],
        [
            'titel'   => 'Ihre Stärken stehen nicht im Newsletter',
            'text'    => 'Kurzplatz, „Golf &amp; Natur“, E-Mobilität, Wohnmobil-Stellplätze:
                          Das steht alles auf Ihrer Website – aber Ihre Mitglieder lesen Ihre
                          Website nicht regelmäßig. Ihre Post schon. Genau diese Themen sind es,
                          die weitererzählt werden.',
            'wirkung' => 'Aus Mitgliedern werden Empfehler – ohne zusätzliches Budget.',
        ],
        [
            'titel'   => 'Ein Knopf statt „Anmeldung im Sekretariat“',
            'text'    => 'Jede Ausgabe hat genau eine Handlung, zu der sie führt. Der Knopf
                          verweist in Ihre bestehende Startzeitenbuchung – an Ihren Abläufen und
                          an Ihrer Clubverwaltung ändert sich dabei nichts.',
            'wirkung' => 'Erstmals nachvollziehbar, welche Ausgabe Buchungen ausgelöst hat.',
        ],
    ],

    'ausblick' => 'Eine Ausgabe besser zu machen, ist der kleinere Teil. Der größere ist alles,
        was danach ohne weiteres Zutun läuft – und die Frage, wer welche Post überhaupt bekommt.',

    'moeglichkeiten' => [
        '<strong>Gäste getrennt ansprechen:</strong> Greenfee-Spieler, Partnerhotel-Gäste und Wohnmobil-Besucher brauchen eine andere Mail als Vollmitglieder – und ihre Adressen liegen bereits im System.',
        '<strong>Turnierausschreibungen nur an die passende Handicap-Klasse</strong>, dazu eine automatische Erinnerung an alle, die sich noch nicht gemeldet haben.',
        '<strong>Eine Willkommens­strecke für neue Mitglieder</strong>, die einmal gebaut wird und danach jahrelang von allein läuft.',
        '<strong>Kurs­interessenten begleiten,</strong> statt auf den Rückruf zu warten – vom Kurzplatz bis zur Platzreife.',
        '<strong>Über den Winter in Kontakt bleiben:</strong> fünf Monate Funkstille kosten mehr, als sie sparen.',
    ],

    'abschluss' => 'Wenn Sie mögen, gehen wir das in einer halben Stunde gemeinsam durch –
        telefonisch oder per Video, mit Vorstand, Sekretariat oder beiden. Sie bekommen eine
        ehrliche Einschätzung, auch wenn die lautet, dass sich der Aufwand für Ihren Club
        gerade nicht lohnt.',
];
