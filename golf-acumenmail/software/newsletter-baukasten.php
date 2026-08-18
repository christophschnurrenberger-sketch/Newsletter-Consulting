<?php
$page = [
    'title'       => 'Newsletter-Baukasten',
    'description' => 'Clubnewsletter aus Bausteinen zusammenziehen: Überschrift, Text, Bild, Knopf, zwei Spalten. Ohne HTML-Kenntnisse, mit Vorschau für Rechner und Handy.',
    'section'     => 'software',
    'path'        => 'software/newsletter-baukasten.php',
    'css'         => ['demo'],
    'js'          => ['demo'],
    'crumbs'      => [['Software', 'software/'], ['Newsletter-Baukasten', null]],
    'hero'        => [
        'kicker' => 'Software · Redaktion',
        'h1'     => 'Bausteine ziehen statt <span class="accent">HTML schreiben</span>',
        'lead'   => 'Kopfzeile und Footer gehören zur Clubvorlage und stehen fest. Alles dazwischen setzt das Sekretariat mit der Maus zusammen – und schreibt den Text direkt im Baustein.',
        'facts'  => [
            ['9', 'Bausteine ab Werk'],
            ['20–40', 'Minuten je Ausgabe'],
            ['Strg+Z', 'nimmt jeden Schritt zurück'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div>

                <!-- Bewegte Demo -->
                <div class="demo-shell is-static" data-demo="builder">
                    <div class="demo-chrome">
                        <span class="demo-dots" aria-hidden="true"><i></i><i></i><i></i></span>
                        <span class="demo-url">golfclub-musterhausen.de/newsletter/admin/kampagne.php</span>
                        <button type="button" class="demo-control demo-toggle" aria-pressed="true">
                            <i data-icon="play" class="lucide"></i><span class="demo-toggle-label">Abspielen</span>
                        </button>
                        <button type="button" class="demo-control demo-replay" aria-label="Demo von vorn abspielen">
                            <i data-icon="rotate-ccw" class="lucide"></i>
                        </button>
                    </div>

                    <div class="demo-body demo-builder">
                        <aside class="demo-palette">
                            <p class="demo-panel-title">Bausteine</p>
                            <div class="demo-palette-list">
                                <span class="demo-pal pal-1"><i data-icon="type" class="lucide"></i>Überschrift</span>
                                <span class="demo-pal pal-2"><i data-icon="align-left" class="lucide"></i>Textabsatz</span>
                                <span class="demo-pal pal-3"><i data-icon="image" class="lucide"></i>Bild</span>
                                <span class="demo-pal pal-4"><i data-icon="button" class="lucide"></i>Knopf</span>
                                <span class="demo-pal pal-5"><i data-icon="columns" class="lucide"></i>Zwei Spalten</span>
                            </div>
                        </aside>

                        <div class="demo-canvas">
                            <div class="demo-mail">
                                <div class="demo-mail-head">
                                    <span class="demo-wordmark">Golfclub <em>Musterhausen</em></span>
                                    <span class="demo-locked">Kopfzeile · fest</span>
                                </div>

                                <div class="demo-drop">
                                    <p class="demo-drop-hint">Hierhin kommen die Bausteine der Ausgabe</p>

                                    <div class="demo-blk blk-1">
                                        <h4>Clubmeisterschaft 2026 – Anmeldung ist offen</h4>
                                    </div>
                                    <div class="demo-blk blk-2">
                                        <p>Liebe(r) <span class="ph">{{vorname}}</span>, am Wochenende vom 12. bis
                                            14. Juli spielen wir die Clubmeisterschaft. 60 Startplätze, alle Klassen.</p>
                                    </div>
                                    <div class="demo-blk blk-3">
                                        <?php include __DIR__ . '/../partials/golf-scene.php'; ?>
                                    </div>
                                    <div class="demo-blk blk-4">
                                        <span class="demo-cta">Jetzt Startzeit sichern</span>
                                    </div>
                                </div>

                                <div class="demo-mail-foot">
                                    <span>Golfclub Musterhausen e.V. · Impressum</span>
                                    <span>Abmelden</span>
                                </div>
                            </div>
                        </div>

                        <aside class="demo-settings">
                            <p class="demo-panel-title">Gestaltung</p>
                            <div class="demo-set-row"><span>Breite</span><b>620 px</b></div>
                            <div class="demo-set-row"><span>Überschrift</span><b>Georgia</b></div>
                            <div class="demo-set-row">
                                <span>Clubfarbe</span>
                                <span class="demo-swatches">
                                    <span class="demo-swatch swatch-1" style="background:#14243A"></span>
                                    <span class="demo-swatch swatch-2" style="background:#1E6B45"></span>
                                    <span class="demo-swatch swatch-3" style="background:#B08A3E"></span>
                                </span>
                            </div>
                            <div class="demo-set-row"><span>Footer</span><b>Pflichtangaben</b></div>
                            <p class="demo-save"><i data-icon="check-circle" class="lucide"></i>Gespeichert um 10:42</p>
                        </aside>

                        <span class="demo-ghost ghost-1" style="top:52px; --dx:206px; --dy:76px;"><i data-icon="type" class="lucide"></i>Überschrift</span>
                        <span class="demo-ghost ghost-2" style="top:94px; --dx:206px; --dy:86px;"><i data-icon="align-left" class="lucide"></i>Textabsatz</span>
                        <span class="demo-ghost ghost-3" style="top:136px; --dx:206px; --dy:96px;"><i data-icon="image" class="lucide"></i>Bild</span>
                        <span class="demo-ghost ghost-4" style="top:178px; --dx:206px; --dy:112px;"><i data-icon="button" class="lucide"></i>Knopf</span>

                        <svg class="demo-cursor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M4.04 4.69a.5.5 0 0 1 .65-.65l16 6.5a.5.5 0 0 1-.06.95l-6.13 1.58a2 2 0 0 0-1.43 1.43l-1.58 6.13a.5.5 0 0 1-.95.06z"
                                  fill="#16261C" stroke="#fff" stroke-width="1.2"></path>
                        </svg>
                    </div>

                    <p class="demo-caption">
                        <i data-icon="mouse-pointer" class="lucide" aria-hidden="true"></i>
                        <span class="demo-caption-text">Die Vorlage des Clubs steht bereits.</span>
                        <span class="demo-progress" aria-hidden="true"></span>
                    </p>
                </div>

                <div class="prose" style="margin-top:3rem;">
                    <h2>Neun Bausteine reichen für jede Ausgabe</h2>
                    <p>
                        Aus den Bausteinen erzeugt das System anschließend tabellenbasiertes E-Mail-HTML
                        mit Inline-Stilen – so, wie Outlook und die übrigen Programme es brauchen. Damit
                        ist die Ausgabe auch dann sauber, wenn sie niemand im Club je angesehen hat.
                    </p>
                    <ul>
                        <li><strong>Überschrift</strong> und <strong>Textabsatz</strong> mit Fettung, Kursiv, Links und Aufzählungen</li>
                        <li><strong>Bild</strong> mit Upload, Galerie und Zuschneiden direkt im Browser</li>
                        <li><strong>Knopf</strong> als Call-to-Action, etwa zur Turnieranmeldung</li>
                        <li><strong>Trennlinie</strong> und <strong>Abstand</strong> zum Gliedern</li>
                        <li><strong>Zwei Spalten</strong>, die auf dem Handy automatisch untereinander umbrechen</li>
                        <li><strong>Linkleiste</strong> und <strong>eigenes HTML</strong> für Sonderfälle</li>
                    </ul>

                    <h2>Was das im Sekretariat spart</h2>
                    <p>
                        Der Baukasten sichert von selbst: Kurz nach jeder Änderung geht der Stand zum
                        Server, oben steht dann „Gespeichert um …“, und die Vorschau lädt neu. Wer sich
                        vertut, nimmt mit <strong>Strg+Z</strong> zurück – und zwar satzweise, nicht
                        buchstabenweise.
                    </p>
                </div>

                <div class="callout">
                    <i data-icon="sparkles" class="lucide"></i>
                    <p>
                        <strong>Platzhalter statt Serienbrief</strong>
                        <code>{{vorname}}</code> setzt beim Versand den Vornamen des Mitglieds ein. Genauso
                        gibt es Platzhalter für Nachname, Anrede und weitere Felder aus dem Import – auch
                        in der Betreffzeile.
                    </p>
                </div>

                <div class="prose">
                    <h2>Eigene Bausteine für Wiederkehrendes</h2>
                    <p>
                        Der Stern am Baustein sichert ihn unter einem Namen. Danach steht er links unter
                        „Eigene Bausteine“ und lässt sich mit einem Klick in jede Ausgabe setzen –
                        praktisch für die Grußformel des Präsidenten, den Kasten mit den Platzregeln oder
                        den festen Hinweis auf die Startzeitenbuchung. Gesichert wird auf dem Server, also
                        für das ganze Team.
                    </p>

                    <h2>Rechner und Handy im Blick</h2>
                    <p>
                        Über der Vorschau schalten Sie zwischen beiden um. Am Handy stellt sich die
                        Vorschau auf 375 Pixel – die Mail greift dann auf dieselben Regeln zurück wie
                        später im Postfach, Spalten brechen also wirklich um. Rund zwei Drittel Ihrer
                        Mitglieder lesen die Clubpost auf dem Telefon; das lohnt den Blick.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.5rem; margin:3rem 0 1.2rem;">Weiter im Software-Bereich</h2>
                <div class="related-grid">
                    <a href="<?= e(url('software/automationen.php')) ?>" class="related-card">
                        <span>Software</span>
                        <strong>Automationen</strong>
                        <p>Derselbe Baukasten schreibt auch die Mails, die von allein hinausgehen.</p>
                    </a>
                    <a href="<?= e(url('software/empfaenger-segmente.php')) ?>" class="related-card">
                        <span>Software</span>
                        <strong>Empfänger &amp; Segmente</strong>
                        <p>Wer die Ausgabe bekommt – und wie Sie die Gruppen einmal sauber trennen.</p>
                    </a>
                </div>

            </div>

            <?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
