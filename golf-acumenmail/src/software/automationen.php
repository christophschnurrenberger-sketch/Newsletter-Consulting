<?php
$page = [
    'title'       => 'Automationen',
    'description' => 'Mailstrecken, die von allein laufen: warten, senden, verzweigen. Willkommensstrecke für neue Mitglieder, Kursbegleitung, Reaktivierung – als Ablauf zusammengezogen statt programmiert.',
    'section'     => 'software',
    'path'        => 'software/automationen.php',
    'crumbs'      => [['Software', 'software/'], ['Automationen', null]],
    'hero'        => [
        'kicker' => 'Software · Abläufe',
        'h1'     => 'Die wirksamsten Mails schreibt man <span class="accent">genau einmal</span>',
        'lead'   => 'Eine Automation startet von selbst, sobald jemand seine Anmeldung bestätigt – und läuft danach für jedes neue Mitglied gleich zuverlässig. Auch im Januar, auch wenn im Sekretariat gerade Hochbetrieb ist.',
        'facts'  => [
            ['5', 'Schritt-Typen'],
            ['365', 'Tage maximale Wartezeit'],
            ['2', 'Zweige je Bedingung'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';
?>

<!-- Das Werkzeug zuerst zeigen, in voller Breite ---------------------- -->
<section class="section-sm">
    <div class="container-wide">
            <figure class="shot">
                <img src="<?= e(url('assets/bilder/tool-automation.png')) ?>" width="1000" height="853"
                     alt="Der Ablauf-Editor: Auslöser, Wartezeiten, E-Mail-Schritte und eine Bedingung mit Ja- und Nein-Zweig."
                     loading="lazy" decoding="async">
                <figcaption>
                    <b>Die Willkommensstrecke.</b>
                    <span>Auslöser oben, darunter Wartezeiten und Mails, unten eine Bedingung mit zwei Zweigen.</span>
                </figcaption>
            </figure>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div>


                <div class="prose" style="margin-top:3rem;">
                    <h2>Fünf Schritte, mehr braucht es nicht</h2>
                </div>

                <div class="table-scroll" style="margin-top:1.2rem;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th scope="col">Schritt</th>
                                <th scope="col">Was er tut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Warten</th>
                                <td>Pause vor dem nächsten Schritt – 1 bis 365 Minuten, Stunden oder Tage.
                                    Die Zeit zählt ab dem vorherigen Schritt, nicht ab der Anmeldung.</td>
                            </tr>
                            <tr>
                                <th scope="row">E-Mail senden</th>
                                <td>Verschickt eine Mail. Betreff und Inhalt entstehen im gewohnten
                                    Baukasten. Ohne Betreff wird der Schritt übersprungen.</td>
                            </tr>
                            <tr>
                                <th scope="row">Wenn … dann</th>
                                <td>Prüft etwas und teilt den Ablauf in einen Ja- und einen Nein-Zweig.
                                    Danach laufen beide wieder zusammen.</td>
                            </tr>
                            <tr>
                                <th scope="row">Aktion</th>
                                <td>Empfänger zu einer Liste hinzufügen, aus einer Liste entfernen oder
                                    vom Newsletter abmelden.</td>
                            </tr>
                            <tr>
                                <th scope="row">Strecke beenden</th>
                                <td>Hier verlässt der Empfänger den Ablauf; alles darunter wird nicht
                                    mehr ausgeführt.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="prose">
                    <h2>Bedingungen, die im Club etwas bedeuten</h2>
                    <p>
                        Geprüft wird immer die zuletzt zugestellte Mail dieser Strecke an diese Person.
                        Wurde noch keine verschickt, gilt die Bedingung als nicht erfüllt – es geht im
                        Nein-Zweig weiter.
                    </p>
                    <ul>
                        <li><strong>Hat die letzte Mail geöffnet</strong> – braucht „Öffnungen messen“ in der betreffenden Mail</li>
                        <li><strong>Hat in der letzten Mail geklickt</strong> – etwa auf „Zur Turnieranmeldung“</li>
                        <li><strong>Steht in einer bestimmten Liste</strong> – etwa „Damenriege“ oder „Jugend“</li>
                        <li><strong>Hat ein Unternehmen hinterlegt</strong> – nützlich bei Firmenturnieren</li>
                    </ul>
                </div>

                <div class="callout is-warning">
                    <i data-icon="help-circle" class="lucide"></i>
                    <p>
                        <strong>Erst aktiv, dann läuft es</strong>
                        Eine Strecke arbeitet erst, wenn der Status auf „Aktiv“ steht. Solange sie im
                        Entwurf liegt, können Sie sie beliebig umbauen, ohne dass jemand Post bekommt.
                        Über „Hinweise zum Ablauf“ meldet das System Lücken: fehlender Betreff, nicht
                        gewählte Liste, Bedingung ohne Zweige.
                    </p>
                </div>

                <div class="prose">
                    <h2>Welche Strecken sich zuerst lohnen</h2>
                    <p>
                        In der Praxis reichen zwei Automationen, um den größten Teil des Nutzens zu
                        heben – die Willkommensstrecke für neue Mitglieder und die Begleitung von
                        Kursinteressenten. Beides läuft ganzjährig und trifft Menschen genau dann,
                        wenn ihr Interesse am größten ist.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.5rem; margin:3rem 0 1.2rem;">Passende Anwendungsfälle</h2>
                <div class="related-grid">
                    <a href="<?= e(url('loesungen/mitgliederbindung.php')) ?>" class="related-card">
                        <span>Lösung</span>
                        <strong>Mitgliederbindung</strong>
                        <p>Die Willkommensstrecke im Zusammenhang – und was danach kommt.</p>
                    </a>
                    <a href="<?= e(url('loesungen/golfschule.php')) ?>" class="related-card">
                        <span>Lösung</span>
                        <strong>Golfschule &amp; Pro</strong>
                        <p>Von der Kursanfrage bis zur Platzreife, ohne Nachtelefonieren.</p>
                    </a>
                    <a href="<?= e(url('software/auswertung.php')) ?>" class="related-card">
                        <span>Software</span>
                        <strong>Auswertung</strong>
                        <p>Woher die Bedingungen wissen, ob jemand geöffnet oder geklickt hat.</p>
                    </a>
                </div>

            </div>

            <?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
