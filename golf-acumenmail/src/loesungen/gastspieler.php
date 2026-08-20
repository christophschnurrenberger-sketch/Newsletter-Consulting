<?php
$page = [
    'title'       => 'Gastspieler & Greenfee',
    'description' => 'Der teuerste Gast ist der, der einmal kommt. Aus einer Greenfee-Runde eine wiederkehrende machen – mit sauberer Einwilligung und zwei gut gesetzten Mails.',
    'section'     => 'loesungen',
    'path'        => 'loesungen/gastspieler.php',
    'crumbs'      => [['Lösungen', 'loesungen/'], ['Gastspieler & Greenfee', null]],
    'hero'        => [
        'kicker' => 'Lösung · Greenfee',
        'h1'     => 'Mehr aus jedem <span class="accent">Gastspieler herausholen</span>',
        'lead'   => 'Die Adresse liegt seit der Buchung im System, angesprochen wird sie nie. Dabei zahlt ein Gast, der einmal kommt, ein Greenfee – einer, der dreimal kommt, drei, und mancher am Ende einen Mitgliedsbeitrag. Wir sprechen Gäste nach der Runde gezielt wieder an: für die nächste Runde, ein Greenfee-Angebot oder eine Mitgliedschaft.',
        'facts'  => [
            ['Tag 2', 'Danke und Rückfrage'],
            ['Tag 30', 'Einladung mit Vorteil'],
            ['Saison­ende', 'Fern- oder Zweitmitgliedschaft'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div>
                <div class="prose">
                    <h2 style="margin-top:0;">Zuerst die Einwilligung, dann alles andere</h2>
                    <p>
                        Bei Gastspielern steht und fällt alles mit dem Moment der Buchung. Wer dort
                        sauber gefragt wird – ein Häkchen, klar beschriftet, nicht vorausgewählt –,
                        darf danach angeschrieben werden. Wer nicht gefragt wurde, darf es nicht,
                        egal wie gut die Idee ist.
                    </p>
                    <p>
                        Deshalb fängt diese Lösung nicht beim Newsletter an, sondern beim
                        Buchungsformular und beim Ablauf am Empfang. Das klären wir im Clubcheck,
                        bevor die erste Mail geschrieben wird.
                    </p>
                </div>

                <div class="callout">
                    <i data-icon="shield-check" class="lucide"></i>
                    <p>
                        <strong>Eigene Liste, eigene Ansprache</strong>
                        Gastspieler kommen nie in die Mitgliederliste. Sie bekommen eine eigene
                        Liste mit eigener Einwilligung, eigenem Abmeldelink und eigenem Ton – und
                        damit auch keine Einladung zur Mitgliederversammlung.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.6rem; margin:2.6rem 0 1.4rem;">Die Strecke danach</h2>
                <div class="numbered-steps">
                    <div class="numbered-step">
                        <div>
                            <h3>Zwei Tage nach der Runde</h3>
                            <p>Danke für den Besuch, eine kurze Rückfrage zum Platzzustand – und
                                nichts zu verkaufen. Diese Mail wird auffallend oft beantwortet, und
                                die Antworten sind für den Greenkeeper wertvoller als jede Umfrage.</p>
                        </div>
                    </div>
                    <div class="numbered-step">
                        <div>
                            <h3>Nach etwa einem Monat</h3>
                            <p>Eine Einladung mit einem echten Grund wiederzukommen: Zweitspieler
                                zum halben Greenfee, ein Gästeturnier, eine ruhige Zeit unter der
                                Woche, in der der Platz ohnehin frei ist.</p>
                        </div>
                    </div>
                    <div class="numbered-step">
                        <div>
                            <h3>Zum Saison­ende</h3>
                            <p>Wer zwei- oder dreimal da war, bekommt das Angebot einer Fern- oder
                                Zweitmitgliedschaft – mit der Rechnung, die zeigt, ab wann sich das
                                gegenüber Greenfee lohnt.</p>
                        </div>
                    </div>
                </div>

                <div class="prose">
                    <h2>Was das für die Anlage bedeutet</h2>
                    <p>
                        Gastspieler füllen genau die Zeiten, die Mitglieder nicht nutzen – Werktage,
                        Randzeiten, Nebensaison. Jede zusätzliche Runde ist deshalb nahezu reiner
                        Deckungsbeitrag, weil der Platz ohnehin gepflegt und besetzt ist. Genau
                        deshalb lohnt sich hier der Aufwand am schnellsten.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.5rem; margin:3rem 0 1.2rem;">Womit das umgesetzt wird</h2>
                <div class="related-grid">
                    <a href="<?= e(url('software/zustellbarkeit-dsgvo.php')) ?>" class="related-card">
                        <span>Software</span><strong>Zustell­barkeit &amp; DSGVO</strong>
                        <p>Double-Opt-in und Protokoll – die Grundlage bei Gastadressen.</p>
                    </a>
                    <a href="<?= e(url('software/automationen.php')) ?>" class="related-card">
                        <span>Software</span><strong>Auto­mationen</strong>
                        <p>Die drei Mails laufen ohne Zutun, sobald jemand gebucht hat.</p>
                    </a>
                </div>
            </div>
            <?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>\n