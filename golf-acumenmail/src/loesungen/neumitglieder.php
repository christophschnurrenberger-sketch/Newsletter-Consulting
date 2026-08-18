<?php
$page = [
    'title'       => 'Neumitglieder gewinnen',
    'description' => 'Von der ersten Anfrage bis zur Aufnahme: Interessenten begleiten, statt auf den Rückruf zu warten. Mit Schnupperangebot, Platzreife und einem klaren nächsten Schritt.',
    'section'     => 'loesungen',
    'path'        => 'loesungen/neumitglieder.php',
    'crumbs'      => [['Lösungen', 'loesungen/'], ['Neumitglieder gewinnen', null]],
    'hero'        => [
        'kicker' => 'Lösung · Wachstum',
        'h1'     => 'Wer heute anfragt, entscheidet sich <span class="accent">selten heute</span>',
        'lead'   => 'Zwischen der ersten Anfrage und der Aufnahme liegen bei den meisten Menschen Wochen bis Monate. In dieser Zeit passiert im Club meist nichts – und die Entscheidung fällt woanders.',
        'facts'  => [
            ['Wochen', 'dauert die Entscheidung'],
            ['4', 'Mails bis zum Termin'],
            ['0', 'Nachtelefonieren nötig'],
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
                    <h2 style="margin-top:0;">Die Lücke zwischen Anfrage und Aufnahme</h2>
                    <p>
                        Eine Anfrage über das Kontaktformular wird beantwortet, ein PDF geschickt,
                        vielleicht ein Termin angeboten. Kommt keine Reaktion, passiert nichts
                        weiter – aus Höflichkeit und aus Zeitmangel. Genau in dieser Lücke gehen
                        die meisten Interessenten verloren.
                    </p>
                    <p>
                        Dabei sind ihre Fragen fast immer dieselben: Was kostet das wirklich? Muss
                        ich schon spielen können? Wie lange dauert die Platzreife? Bin ich dort zu
                        alt oder zu jung? Wer diese Fragen beantwortet, bevor sie gestellt werden,
                        braucht kein Verkaufsgespräch.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.6rem; margin:2.6rem 0 1.4rem;">Die Interessentenstrecke</h2>
                <div class="numbered-steps">
                    <div class="numbered-step">
                        <div>
                            <h3>Sofort: Ankommen</h3>
                            <p>Die Antwort auf die Anfrage, ein Ansprechpartner mit Namen und
                                Telefonnummer, und ein ehrlicher Überblick über Beitrag und
                                Aufnahmegebühr. Keine Preisverschleierung – das kostet Vertrauen.</p>
                        </div>
                    </div>
                    <div class="numbered-step">
                        <div>
                            <h3>Nach drei Tagen: Die üblichen Sorgen</h3>
                            <p>„Ich kann noch gar nicht spielen“ und „ich habe keine Ausrüstung“
                                sind die zwei häufigsten Bremsen. Beide lassen sich in einer Mail
                                auflösen.</p>
                        </div>
                    </div>
                    <div class="numbered-step">
                        <div>
                            <h3>Nach einer Woche: Ein konkretes Angebot</h3>
                            <p>Der nächste Schnupperkurs mit Datum, oder eine Runde mit einem
                                Mitglied als Pate. Etwas, das man in den Kalender eintragen kann –
                                keine allgemeine Einladung.</p>
                        </div>
                    </div>
                    <div class="numbered-step">
                        <div>
                            <h3>Nach drei Wochen: Der Club von innen</h3>
                            <p>Wie das Clubleben tatsächlich aussieht: Riegen, Clubabend, wer hier
                                spielt. Menschen treten einem Club bei, nicht einem Platz.</p>
                        </div>
                    </div>
                </div>

                <div class="callout">
                    <i data-icon="target" class="lucide"></i>
                    <p>
                        <strong>Der Übergang muss sitzen</strong>
                        Wird aus dem Interessenten ein Mitglied, endet diese Strecke und die
                        Willkommensstrecke beginnt. Das passiert automatisch über eine Aktion im
                        Ablauf – niemand muss daran denken, und niemand bekommt beides.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.5rem; margin:3rem 0 1.2rem;">Passt dazu</h2>
                <div class="related-grid">
                    <a href="<?= e(url('loesungen/golfschule.php')) ?>" class="related-card">
                        <span>Lösung</span><strong>Golfschule &amp; Pro</strong>
                        <p>Die Platzreife ist für viele Clubs der eigentliche Einstiegskanal.</p>
                    </a>
                    <a href="<?= e(url('loesungen/mitgliederbindung.php')) ?>" class="related-card">
                        <span>Lösung</span><strong>Mitgliederbindung</strong>
                        <p>Was nach der Aufnahme passieren muss, damit es nicht umsonst war.</p>
                    </a>
                </div>
            </div>
            <?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>\n