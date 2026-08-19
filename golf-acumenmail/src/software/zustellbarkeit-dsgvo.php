<?php
$page = [
    'title'       => 'Zustell­barkeit & DSGVO',
    'description' => 'Double-Opt-in mit Protokoll, Abmeldelink in jeder Mail, List-Unsubscribe nach RFC 8058, SPF und DKIM, Bounce-Verarbeitung und Sperrliste.',
    'section'     => 'software',
    'path'        => 'software/zustellbarkeit-dsgvo.php',
    'crumbs'      => [['Software', 'software/'], ['Zustell­barkeit & DSGVO', null]],
    'hero'        => [
        'kicker' => 'Software · Recht und Technik',
        'h1'     => 'Ankommen ist <span class="accent">die halbe Miete</span>',
        'lead'   => 'Eine Mail, die im Spam landet, ist so wertlos wie eine, die nie geschrieben wurde. Und eine ohne saubere Einwilligung ist schlimmer als beides. Beides ist ab Werk geregelt.',
        'facts'  => [
            ['RFC 8058', 'One-Click-Abmeldung'],
            ['SPF+DKIM', 'bei der Einrichtung gesetzt'],
            ['Protokoll', 'zu jeder Einwilligung'],
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
                    <h2 style="margin-top:0;">Was rechtlich fest eingebaut ist</h2>
                    <p>
                        Ein Teil der Pflichten lässt sich nicht abschalten – mit Absicht. Wer den
                        Footer-Baustein entfernt, bekommt Impressum und Abmeldelink trotzdem
                        angehängt, weil beides gesetzlich vorgeschrieben ist.
                    </p>
                    <ul>
                        <li><strong>Double-Opt-in</strong> mit Zeitstempel, Quelle und IP im Protokoll</li>
                        <li><strong>Abmeldelink</strong> in jeder Mail, Abmeldung mit einem Klick</li>
                        <li><strong>List-Unsubscribe</strong> im Kopf der Mail, auch als One-Click nach RFC 8058 – dadurch zeigt das Postfach seinen eigenen Abmeldeknopf an</li>
                        <li><strong>Impressum</strong> im Footer, je Marke getrennt hinterlegbar</li>
                        <li><strong>Selbstauskunft und Löschung</strong> für Empfänger ohne Umweg über das Sekretariat</li>
                    </ul>

                    <h2>Und was die Technik beisteuert</h2>
                    <p>
                        Versendet wird über ein echtes Postfach Ihrer Domain, nicht über eine
                        Absenderadresse, die nur so aussieht. Bei der Einrichtung setzen wir SPF und
                        DKIM im DNS – ohne beides sortieren große Anbieter Vereinspost zuverlässig
                        aus.
                    </p>
                    <ul>
                        <li>Portionsweiser Versand über Cron mit Tempolimit, statt 900 Mails in einer Minute</li>
                        <li>Wiederholung bei vorübergehenden Fehlern, Pause und Fortsetzen jederzeit möglich</li>
                        <li>Bounce-Auswertung per POP3: dauerhaft ungültige Adressen wandern automatisch auf die Sperrliste</li>
                        <li>Textfassung zu jeder Mail, weil reine Bild-HTML-Mails schlechter zugestellt werden</li>
                    </ul>
                </div>

                <div class="callout is-warning">
                    <i data-icon="help-circle" class="lucide"></i>
                    <p>
                        <strong>Vereinsinformation ist nicht gleich Werbung</strong>
                        Die Einladung zur Mitgliederversammlung steht rechtlich anders da als das
                        Angebot des Pro-Shops. Wir richten beides in getrennten Listen ein und legen
                        die Einwilligungen nachweisbar ab. Die abschließende Bewertung für Ihren Club
                        trifft Ihr Datenschutzbeauftragter – wir liefern die Nachweise, die er dafür
                        braucht.
                    </p>
                </div>

                <div class="prose">
                    <h2>Wo die Daten liegen</h2>
                    <p>
                        Auf dem Webspace Ihres Clubs. Es gibt keinen Anbieter, der mitliest, keine
                        Übermittlung in Drittländer und deshalb auch keinen Auftragsverarbeitungs­vertrag
                        mit einem amerikanischen Dienst. Wer im Club Zugriff hat, entscheiden Sie über
                        drei Rollen: Administrator, Redakteur, Betrachter. Ausgeschiedene Mitarbeiter
                        werden gesperrt statt gelöscht, damit das Protokoll lückenlos bleibt.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.5rem; margin:3rem 0 1.2rem;">Weiterlesen</h2>
                <div class="related-grid">
                    <a href="<?= e(url('wissen/dsgvo-mitgliederdaten-golfclub.php')) ?>" class="related-card">
                        <span>Wissen</span><strong>Mitgliederdaten rechtssicher nutzen</strong>
                        <p>Der ausführliche Beitrag zur Frage, wen Sie wann anschreiben dürfen.</p>
                    </a>
                    <a href="<?= e(url('software/systemvoraussetzungen.php')) ?>" class="related-card">
                        <span>Software</span><strong>System­voraus­setzungen</strong>
                        <p>Was Ihr Hosting mitbringen muss – und was nicht nötig ist.</p>
                    </a>
                </div>
            </div>
            <?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
