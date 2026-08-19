<?php
$page = [
    'title'       => 'Häufige Fragen',
    'description' => 'Antworten zu Vereinsrecht und DSGVO, Aufwand im Sekretariat, PC CADDIE, Kosten, Umstieg vom bisherigen Tool und Zeitplan bis zur ersten Ausgabe.',
    'section'     => 'wissen',
    'path'        => 'faq.php',
    'crumbs'      => [['Wissen', 'wissen/'], ['Häufige Fragen', null]],
    'hero'        => [
        'kicker' => 'FAQ',
        'h1'     => 'Was Clubs uns <span class="accent">am häufigsten fragen</span>',
        'lead'   => 'Meistens geht es um drei Dinge: Recht, Aufwand und die bestehende Clubverwaltung. Hier stehen die ehrlichen Antworten – auch dort, wo sie unbequem sind.',
    ],
];
include __DIR__ . '/partials/header.php';

$faq = [
    ['Recht', 'Dürfen wir unsere Mitglieder überhaupt anschreiben?',
     'Reine Vereinsinformationen an Mitglieder stehen rechtlich anders da als Werbung für Pro-Shop, Gastronomie oder Reisen. Wir richten deshalb beides getrennt ein: Anmeldung mit Double-Opt-in, Einwilligungsprotokoll zu jedem Eintrag, eigene Listen für Vereinsinfo und Werbung, Abmeldelink in jeder Mail. Damit ist die Grundlage sauber. Die abschließende rechtliche Bewertung für Ihren Club trifft Ihr Datenschutzbeauftragter – wir liefern die Nachweise, die er dafür braucht.'],
    ['Technik', 'Wir arbeiten mit PC CADDIE. Passt das zusammen?',
     'Ja. Ihre Clubverwaltung bleibt die führende Stelle für Mitgliederdaten, für den Newsletter wird ein CSV-Export importiert. Das funktioniert mit PC CADDIE ebenso wie mit anderen Systemen. Beim Import erkennt das Tool Dubletten; Abmeldungen und die Sperrliste bleiben dabei immer geschützt – ein abgemeldetes Mitglied kommt durch einen Import nicht versehentlich zurück.'],
    ['Aufwand', 'Wie viel Zeit kostet das unser Sekretariat?',
     'Nach der Einrichtung liegt eine Ausgabe erfahrungsgemäß bei 20 bis 40 Minuten: Bausteine anordnen, Text schreiben, Vorschau prüfen, senden. Die Auto­mationen laufen danach ohne weiteres Zutun. Wer auch das nicht leisten kann oder will, gibt die Ausgaben in der Clubbetreuung ganz ab.'],
    ['Kosten', 'Was kostet der Betrieb des Tools?',
     'Für das Tool selbst fallen keine laufenden Lizenzkosten an. Es läuft auf dem Webspace des Clubs mit PHP 8 und einer Datenbank; SQLite genügt. Die Zahl der Mitglieder ist damit für die Kosten unerheblich – anders als bei Anbietern, die pro Kontakt abrechnen. Was bleibt, sind Ihr Hosting und, bei großen Mengen, gegebenenfalls ein Versanddienst.'],
    ['Umstieg', 'Müssen wir unser bisheriges Tool kündigen?',
     'Nicht zwingend. Zuerst prüfen wir, ob Ihr aktuelles Werkzeug die Segmente und Auto­mationen abbilden kann, die Sie brauchen. Wenn ja, arbeiten wir damit weiter – die Strategie ist wichtiger als das Werkzeug. Ein Wechsel lohnt meist dann, wenn die Kosten mit der Mitgliederzahl steigen, Auto­mationen fehlen oder Mitgliederdaten den EU-Raum verlassen.'],
    ['Gastspieler', 'Was ist mit Greenfee-Zahlern und Gästen?',
     'Gastspieler sind für die meisten Anlagen die am stärksten unterschätzte Gruppe: Die Adresse liegt seit der Buchung im System, angesprochen wird sie nie. Wichtig ist die saubere Einwilligung bei der Buchung – dann lässt sich daraus eine eigene Liste mit eigener Ansprache bauen.'],
    ['Zeitplan', 'Wie schnell sind wir startklar?',
     'Die Einrichtung samt Vorlage im Clubdesign, Listen und erster Automation dauert in der Regel ein bis zwei Wochen. Die erste eigene Ausgabe verschicken die meisten Clubs im ersten Monat. Für einen Saisonstart im Frühjahr ist der Januar ein guter Zeitpunkt zum Anfangen.'],
    ['Zustell­barkeit', 'Landen die Mails nicht im Spam?',
     'Das hängt fast vollständig an der Technik, nicht am Inhalt. Versendet wird über ein echtes Postfach Ihrer Domain, dazu setzen wir SPF und DKIM im DNS. Der Versand läuft portionsweise mit Tempolimit statt 900 Mails in einer Minute, jede Mail hat eine Textfassung und einen List-Unsubscribe-Kopf. Damit ist der wesentliche Teil erledigt.'],
    ['Betrieb', 'Was passiert, wenn wir die Zusammenarbeit beenden?',
     'Nichts Dramatisches. Das System läuft auf Ihrem Server, die Daten liegen bei Ihnen, das Handbuch im Sekretariat. Sie versenden weiter wie bisher – ohne uns und ohne zusätzliche Kosten. Genau das ist der Sinn der Konstruktion.'],
    ['Größe', 'Lohnt sich das auch für einen kleinen Club?',
     'Ab etwa 300 Empfängern in der Regel ja, weil der Aufwand pro Ausgabe gleich bleibt, egal ob 300 oder 3.000 Leute sie bekommen. Darunter kommt es darauf an, wie viel Kommunikation ohnehin anfällt. Auch das ist eine Frage für den Clubcheck.'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div>
                <div class="faq-list">
<?php foreach ($faq as $i => $item): ?>
                    <article class="faq-item">
                        <button class="faq-question<?= $i === 0 ? ' active' : '' ?>" type="button" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>">
                            <span class="faq-number"><?= sprintf('%02d', $i + 1) ?></span>
                            <span class="faq-question-text">
                                <small><?= e($item[0]) ?></small>
                                <strong><?= e($item[1]) ?></strong>
                            </span>
                            <i data-icon="chevron-down" class="lucide"></i>
                        </button>
                        <div class="faq-answer<?= $i === 0 ? ' open' : '' ?>" aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>"><div>
                            <p><?= e($item[2]) ?></p>
                        </div></div>
                    </article>
<?php endforeach; ?>
                </div>
            </div>

            <?php $asideCta = [
                'title' => 'Frage nicht dabei?',
                'text'  => 'Schreiben Sie sie einfach ins Nachrichtenfeld. Sie bekommen eine Antwort, auch wenn daraus kein Auftrag wird.',
                'link'  => ['Frage stellen', 'kontakt.php'],
            ]; ?>
            <?php include __DIR__ . '/partials/aside.php'; ?>
        </div>
    </div>
</section>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
<?php foreach ($faq as $i => $item): ?>
    {
      "@type": "Question",
      "name": <?= json_encode($item[1], JSON_UNESCAPED_UNICODE) ?>,
      "acceptedAnswer": { "@type": "Answer", "text": <?= json_encode($item[2], JSON_UNESCAPED_UNICODE) ?> }
    }<?= $i < count($faq) - 1 ? ',' : '' ?>

<?php endforeach; ?>
  ]
}
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
