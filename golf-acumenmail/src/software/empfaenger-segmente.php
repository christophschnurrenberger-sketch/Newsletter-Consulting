<?php
$page = [
    'title'       => 'Empfänger & Segmente',
    'description' => 'Mitglieder aus der Clubverwaltung importieren, in Segmente trennen und sauber pflegen: Handicap-Gruppen, Mitgliedsarten, Gastspieler, Sperrliste und Einwilligungsprotokoll.',
    'section'     => 'software',
    'path'        => 'software/empfaenger-segmente.php',
    'crumbs'      => [['Software', 'software/'], ['Empfänger & Segmente', null]],
    'hero'        => [
        'kicker' => 'Software · Daten',
        'h1'     => 'Ein Club ist keine <span class="accent">homogene Liste</span>',
        'lead'   => 'Die Jugend braucht andere Post als die Seniorenriege, ein Fernmitglied andere als jemand, der dreimal die Woche auf der Anlage steht. Segmente trennen Sie einmal – danach wählen Sie nur noch aus.',
        'facts'  => [
            ['CSV', 'Import aus jeder Clubverwaltung'],
            ['0', 'versehentliche Re­aktivierungen'],
            ['∞', 'Listen und Segmente'],
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
                    <h2 style="margin-top:0;">Die Clubverwaltung bleibt die führende Stelle</h2>
                    <p>
                        Ihre Mitgliederdaten liegen dort, wo sie hingehören: in PC CADDIE oder dem
                        System, mit dem Ihr Club ohnehin arbeitet. Für den Newsletter kommt ein
                        CSV-Export hinzu – regelmäßig oder zu Saisonbeginn, ganz wie es in Ihren
                        Ablauf passt. Ein zweites Datensilo entsteht dabei nicht, weil der Newsletter
                        nur die Felder bekommt, die er braucht.
                    </p>
                    <h3>Was beim Import passiert</h3>
                    <ul>
                        <li>Dubletten werden erkannt und zusammengeführt, nicht doppelt angelegt</li>
                        <li>Abmeldungen bleiben geschützt: Wer sich abgemeldet hat, kommt durch keinen Import zurück</li>
                        <li>Die Sperrliste gilt immer – auch für Adressen, die versehentlich wieder in der Datei stehen</li>
                        <li>Zu jeder Adresse wird protokolliert, woher sie stammt und wann die Einwilligung erteilt wurde</li>
                    </ul>
                </div>

                <div class="callout">
                    <i data-icon="shield-check" class="lucide"></i>
                    <p>
                        <strong>Der wichtigste Schutz ist der langweiligste</strong>
                        Genau diese Regel – Import überschreibt niemals eine Abmeldung – verhindert den
                        Fehler, der Clubs am häufigsten Ärger einbringt: Ein Mitglied meldet sich ab,
                        bekommt vier Wochen später trotzdem Post, und aus einer Kleinigkeit wird eine
                        Beschwerde beim Vorstand.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.6rem; margin:2.6rem 0 1.2rem;">Segmente, die sich im Golfclub bewähren</h2>

                <div class="table-scroll">
                    <table class="data-table">
                        <caption>Ein Empfänger kann in mehreren Segmenten stehen – die Segmente schließen sich nicht aus.</caption>
                        <thead>
                            <tr>
                                <th scope="col">Segment</th>
                                <th scope="col">Wofür es gebraucht wird</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><th scope="row">Handicap-Gruppen</th><td>Turnierausschreibungen an die passende Klasse statt an alle</td></tr>
                            <tr><th scope="row">Mitgliedsart</th><td>Voll-, Fern-, Jugend-, Schnupper- und Zweitmitglieder ansprechen, wie es zu ihrem Beitrag passt</td></tr>
                            <tr><th scope="row">Aktivität</th><td>Wer lange nicht gespielt hat, bekommt eine andere Mail als der Stammspieler</td></tr>
                            <tr><th scope="row">Gastspieler</th><td>Greenfee-Zahler mit eigener Einwilligung und eigener Ansprache</td></tr>
                            <tr><th scope="row">Kurs­interessenten</th><td>Platzreife-Anfragen, die noch keine Mitglieder sind</td></tr>
                            <tr><th scope="row">Riegen und Mannschaften</th><td>Damen, Herren, Senioren, Jugend – interne Termine ohne Streuverlust</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="prose">
                    <h2>Anmeldung über die Website</h2>
                    <p>
                        Das Anmeldeformular liegt auf Ihrer Clubseite und läuft über dasselbe System.
                        Jede Anmeldung durchläuft Double-Opt-in: Die Adresse wird erst aufgenommen,
                        wenn der Link in der Bestätigungsmail geklickt wurde. Honeypot, Zeitmessung,
                        Rate-Limits und eine Prüfung der Domain halten Formular-Roboter draußen.
                    </p>
                    <p>
                        Für Empfänger gibt es zwei Selbstbedienungswege, die dem Sekretariat Arbeit
                        abnehmen: Selbstauskunft über die gespeicherten Daten und Löschung – beides
                        ohne Anruf im Clubhaus.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.5rem; margin:3rem 0 1.2rem;">Weiterlesen</h2>
                <div class="related-grid">
                    <a href="<?= e(url('software/zustellbarkeit-dsgvo.php')) ?>" class="related-card">
                        <span>Software</span><strong>Zustell­barkeit &amp; DSGVO</strong>
                        <p>Double-Opt-in, Protokoll, Abmeldelink und was der Gesetzgeber verlangt.</p>
                    </a>
                    <a href="<?= e(url('wissen/dsgvo-mitgliederdaten-golfclub.php')) ?>" class="related-card">
                        <span>Wissen</span><strong>Mitgliederdaten rechtssicher nutzen</strong>
                        <p>Wo Vereinsinformation aufhört und Werbung anfängt.</p>
                    </a>
                </div>
            </div>
            <?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
