<?php
$page = [
    'title'       => 'Clubbetreuung',
    'description' => 'Wir schreiben, versenden und werten aus: monatliche Ausgaben nach Redaktionsplan, Ausbau der Automationen und ein Bericht für den Vorstand.',
    'section'     => 'leistungen',
    'path'        => 'leistungen/clubbetreuung.php',
    'crumbs'      => [['Leistungen', 'leistungen/'], ['Clubbetreuung', null]],
    'hero'        => [
        'kicker' => 'Leistung · Rhythmus',
        'h1'     => 'Damit der Newsletter <span class="accent">nicht wieder liegen bleibt</span>',
        'lead'   => 'Die häufigste Todesursache eines Clubnewsletters ist kein Fehler, sondern der Alltag: Startzeiten, Telefon, Turnierleitung – und dann ist der Monat vorbei.',
        'facts'  => [['ab 390 €', 'pro Monat'], ['monatlich', 'kündbar'], ['1 Bericht', 'pro Saison']],
    ],
];
$asideCta = [
    'title' => 'Investition',
    'text'  => 'ab 390 € pro Monat – monatlich kündbar, kein Jahresvertrag',
    'link'  => ['Unverbindlich anfragen', 'kontakt.php'],
];
include __DIR__ . '/../partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div>
                <div class="prose">
                    <h2 style="margin-top:0;">Was wir übernehmen</h2>
                    <ul>
                        <li><strong>Monatliche Ausgaben</strong> nach dem gemeinsamen Redaktionsplan – wir schreiben, Sie geben frei</li>
                        <li><strong>Turnier- und Eventkommunikation</strong> mit Ankündigung, Erinnerung und Nachbereitung</li>
                        <li><strong>Ausbau der Automationen</strong> und Pflege der Segmente über die Saison</li>
                        <li><strong>Tests</strong> zu Betreffzeilen und Versandzeitpunkten, damit die Zahlen besser werden statt gleich zu bleiben</li>
                        <li><strong>Auswertung je Kampagne</strong> und ein zusammenfassender Bericht für den Vorstand</li>
                    </ul>

                    <h2>Was im Club bleibt</h2>
                    <p>
                        Die Freigabe und der Inhalt, den nur Sie kennen: was auf dem Platz passiert,
                        welches Turnier ansteht, was der Greenkeeper meldet. Wir brauchen dafür
                        keine langen Zuarbeiten – meist reichen ein kurzer Anruf im Monat und ein
                        Blick in den Terminkalender.
                    </p>
                </div>

                <div class="callout is-warning">
                    <i data-icon="help-circle" class="lucide"></i>
                    <p>
                        <strong>Kein Jahresvertrag</strong>
                        Monatlich kündbar. Wenn der Club nach einer Saison selbst weitermachen will,
                        ist das der Normalfall und nicht das Scheitern – das System gehört Ihnen
                        ohnehin, und das Handbuch liegt im Sekretariat.
                    </p>
                </div>

                <div class="prose">
                    <h2>Für wen sich das lohnt</h2>
                    <p>
                        Für Clubs ohne eigene Marketingstelle, in denen die Kommunikation sonst an
                        einer Person hängt. Und für Anlagen, die den Kanal ernsthaft betreiben
                        wollen, statt ihn zweimal im Jahr zu bedienen.
                    </p>
                </div>
            </div>
            <?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>\n