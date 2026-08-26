<?php
$page = [
    'title'       => 'Turniere & Events',
    'description' => 'Startlisten füllen statt auf den Aushang hoffen: Ausschreibung an die passende Handicap-Klasse, Erinnerung nur an die Unentschlossenen, Nachbereitung mit Ergebnissen.',
    'section'     => 'loesungen',
    'path'        => 'loesungen/turniere-events.php',
    'crumbs'      => [['Lösungen', 'loesungen/'], ['Turniere & Events', null]],
    'hero'        => [
        'kicker' => 'Lösung · Auslastung',
        'h1'     => 'Die Ausschreibung allein <span class="accent">füllt kein Feld</span>',
        'lead'   => 'Sie hängt seit vier Wochen im Clubhaus, und zwei Wochen vor dem Turnier sind 18 von 60 Plätzen vergeben. Nicht weil das Turnier unattraktiv wäre – sondern weil die meisten nichts davon wissen.',
        'facts'  => [
            ['3', 'Mails je Turnier'],
            ['−5 Tage', 'nur an die Unentschlossenen'],
            ['+2 Tage', 'Ergebnisse und nächster Termin'],
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
                    <h2 style="margin-top:0;">Drei Mails, nicht dreißig</h2>
                    <p>
                        Der häufigste Fehler bei Turnierkommunikation ist nicht zu wenig Post,
                        sondern zu viel an die Falschen. Wenn jede Ausschreibung an alle 900
                        Mitglieder geht, lesen nach dem dritten Mal auch die nicht mehr mit, die
                        eigentlich gespielt hätten.
                    </p>
                </div>

                <div class="table-scroll" style="margin-top:1.6rem;">
                    <table class="data-table">
                        <caption>Der Ablauf je Turnier. Wer sich bereits angemeldet hat, bekommt die Erinnerung nicht.</caption>
                        <thead>
                            <tr>
                                <th scope="col">Zeitpunkt</th>
                                <th scope="col">Empfänger</th>
                                <th scope="col">Inhalt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">14 Tage vorher</th>
                                <td>Passende Handicap-Klasse</td>
                                <td>Ankündigung mit Spielform, Startzeiten und direktem Anmeldelink</td>
                            </tr>
                            <tr>
                                <th scope="row">5 Tage vorher</th>
                                <td>Nur wer noch nicht gemeldet ist</td>
                                <td>Kurze Erinnerung, freie Plätze, Anmeldeschluss</td>
                            </tr>
                            <tr>
                                <th scope="row">2 Tage danach</th>
                                <td>Teilnehmer und Klasse</td>
                                <td>Ergebnisse, Fotos, Dank – und der nächste Termin</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="prose">
                    <h2>Warum die Nachbereitung die wichtigste Mail ist</h2>
                    <p>
                        Sie wird am häufigsten geöffnet und am seltensten verschickt. Wer nach dem
                        Turnier Ergebnisse und ein paar Bilder bekommt, erinnert sich beim nächsten
                        Mal an den guten Tag – und meldet sich früher an. Gleichzeitig sehen die,
                        die nicht dabei waren, was sie verpasst haben.
                    </p>

                    <h2>Was sich sonst noch lohnt</h2>
                    <ul>
                        <li><strong>Firmen- und Gästeturniere</strong> – eigene Liste, eigene Ansprache, klarer Ablauf</li>
                        <li><strong>Clubabend und Gastronomie</strong> – kurze Ankündigung, weil sonst niemand reserviert</li>
                        <li><strong>Jahresterminplan</strong> – einmal im Januar, damit sich Urlaube danach richten</li>
                        <li><strong>Absagen bei Platzsperrung</strong> – die eine Mail, die sofort raus muss und alle erreicht</li>
                    </ul>
                </div>

                <div class="callout is-warning">
                    <i data-icon="help-circle" class="lucide"></i>
                    <p>
                        <strong>Die Anmeldung selbst bleibt, wo sie ist</strong>
                        Der Newsletter ersetzt Ihre Turniersoftware nicht. Der Knopf in der Mail
                        führt direkt in die Anmeldung Ihrer Clubverwaltung – wir sorgen nur dafür,
                        dass genug Leute dort ankommen.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.5rem; margin:3rem 0 1.2rem;">Womit das umgesetzt wird</h2>
                <div class="related-grid">
                    <a href="<?= e(url('software/empfaenger-segmente.php')) ?>" class="related-card">
                        <span>Software</span><strong>Empfänger &amp; Segmente</strong>
                        <p>Handicap-Gruppen, damit die Ausschreibung die Richtigen trifft.</p>
                    </a>
                    <a href="<?= e(url('software/auswertung.php')) ?>" class="related-card">
                        <span>Software</span><strong>Auswertung</strong>
                        <p>Wer geklickt hat – und damit, wer die Erinnerung noch braucht.</p>
                    </a>
                </div>
            </div>
            <?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>\n