<?php
$page = [
    'title'       => 'Golfschule & Pro',
    'description' => 'Kurse und Platzreife auslasten: Interessenten begleiten, Kurstermine rechtzeitig füllen und aus Kursteilnehmern Mitglieder machen.',
    'section'     => 'loesungen',
    'path'        => 'loesungen/golfschule.php',
    'crumbs'      => [['Lösungen', 'loesungen/'], ['Golfschule & Pro', null]],
    'hero'        => [
        'kicker' => 'Lösung · Golfschule',
        'h1'     => 'Kurse voll bekommen, <span class="accent">bevor sie starten</span>',
        'lead'   => 'Ein Platzreifekurs mit vier statt acht Teilnehmern kostet den Pro dieselbe Zeit – der halbe Kursumsatz fehlt trotzdem, und mit ihm die Mitglieder, die daraus hätten werden können. Die Teilnehmer, die fehlen, haben meist irgendwann einmal angefragt. Wir sprechen sie an, bevor der Kurs beginnt.',
        'facts'  => [
            ['3', 'Mails bis zur Anmeldung'],
            ['1', 'Liste für Kurs­interessenten'],
            ['danach', 'Übergabe an den Club'],
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
                    <h2 style="margin-top:0;">Kurs­interessenten sind keine Mitglieder</h2>
                    <p>
                        Sie stehen ganz am Anfang, kennen niemanden im Club und haben oft eine sehr
                        konkrete Frage im Kopf, die sie sich nicht zu stellen trauen. Deshalb
                        bekommen sie eine eigene Liste und einen anderen Ton als die
                        Clubnachrichten – dort wäre die Rede von Clubmeisterschaft und
                        Mannschaftsaufstellung schlicht abschreckend.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.6rem; margin:2.6rem 0 1.4rem;">Von der Anfrage zum Kurstermin</h2>
                <div class="numbered-steps">
                    <div class="numbered-step">
                        <div>
                            <h3>Was die Platzreife eigentlich ist</h3>
                            <p>In verständlichen Worten, ohne Fachbegriffe: was geprüft wird, wie
                                lange es dauert, wie viele Stunden dahinterstecken.</p>
                        </div>
                    </div>
                    <div class="numbered-step">
                        <div>
                            <h3>Kosten, Ausrüstung, Vorkenntnisse</h3>
                            <p>Die drei Fragen, die jeden umtreiben. Leihschläger, bequeme Schuhe,
                                keine Vorkenntnisse – das räumt mehr Hürden ab als jede
                                Hochglanzbroschüre.</p>
                        </div>
                    </div>
                    <div class="numbered-step">
                        <div>
                            <h3>Die nächsten Termine mit Anmeldelink</h3>
                            <p>Konkrete Daten statt „Kurse finden regelmäßig statt“. Wer sich nicht
                                anmeldet, bekommt vor dem übernächsten Termin noch einmal eine
                                Erinnerung.</p>
                        </div>
                    </div>
                </div>

                <div class="prose">
                    <h2>Und danach der eigentliche Punkt</h2>
                    <p>
                        Nach bestandener Platzreife steht die wichtigste Frage im Raum: Bleibt die
                        Person? Wer den Kurs abgeschlossen hat und dann nichts mehr hört, spielt
                        selten weiter. Eine kurze Strecke im Anschluss – erste Runde mit einem
                        Paten, Schnuppermitgliedschaft, Termine der Anfängerrunde – macht aus
                        Kursteilnehmern Mitglieder.
                    </p>
                    <p>
                        Betreibt Ihre Golfschule eine eigene Marke, lässt sich das in derselben
                        Installation abbilden: eigene Vorlage, eigener Absender, eigenes Impressum –
                        eine Datenbasis, zwei Auftritte.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.5rem; margin:3rem 0 1.2rem;">Passt dazu</h2>
                <div class="related-grid">
                    <a href="<?= e(url('loesungen/neumitglieder.php')) ?>" class="related-card">
                        <span>Lösung</span><strong>Neumitglieder gewinnen</strong>
                        <p>Die Strecke für alle, die sich für eine Mitgliedschaft interessieren.</p>
                    </a>
                    <a href="<?= e(url('software/newsletter-baukasten.php')) ?>" class="related-card">
                        <span>Software</span><strong>Newsletter-Baukasten</strong>
                        <p>Mehrere Marken in einer Installation – Club und Golfschule getrennt.</p>
                    </a>
                </div>
            </div>
            <?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>\n