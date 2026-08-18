<?php
$page = [
    'title'       => 'Newsletter-Baukasten',
    'description' => 'Clubnewsletter aus Bausteinen zusammenziehen: Überschrift, Text, Bild, Knopf, zwei Spalten. Ohne HTML-Kenntnisse, mit Vorschau für Rechner und Handy.',
    'section'     => 'software',
    'path'        => 'software/newsletter-baukasten.php',
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

<!-- Das Werkzeug zuerst zeigen, in voller Breite ---------------------- -->
<section class="section-sm">
    <div class="container-wide">
            <figure class="demo-frame" data-demo-frame>
                <div class="demo-frame-buehne">
                    <iframe src="<?= e(url('demo/baukasten.html')) ?>" loading="lazy"
                            title="Der Newsletter-Baukasten: links die Bausteine, in der Mitte die Ausgabe mit Überschrift, Text und Knopf, rechts die Gestaltung."></iframe>
                </div>
                <figcaption>
                    <b>Kein Bild.</b>
                    <span>Der Baukasten selbst, mit einer Ausgabe mitten im Schreiben. Anklicken
                    lässt sich hier nichts – dafür gibt es den Zugang zum Probieren.</span>
                </figcaption>
                <p class="demo-frame-mobil">Auf dem Handy sehen Sie den oberen Ausschnitt – der Rahmen lässt sich darin weiterschieben.</p>
            </figure>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div>

                <!-- Bewegte Demo -->

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
