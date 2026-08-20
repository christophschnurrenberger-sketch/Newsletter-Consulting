<?php
$page = [
    'title'       => 'IT Demand Management einführen',
    'description' => 'Ein Weg für alle IT-Anforderungen: Erfassung, Bewertung, Priorisierung, Entscheidung und Rückmeldung. Einführung eines Demand-Prozesses inklusive Bewertungsschema, Gremium und Werkzeugumsetzung. 18.000 bis 34.000 € netto.',
    'section'     => 'leistungen',
    'path'        => 'leistungen/demand-management.php',
    'crumbs'      => [['Leistungen', 'leistungen/'], ['IT Demand Management', null]],
    'hero'        => [
        'kicker' => 'Leistung · Aufbau',
        'h1'     => 'Ein Eingang für alles, was die IT <span class="accent">tun soll</span>',
        'lead'   => 'Ohne Demand-Prozess entscheidet Lautstärke über Prioritäten. Mit ihm entscheidet ein Gremium anhand von Kriterien – und jede Fachabteilung weiß, woran sie ist. Das ist der Prozess, dessen Einführung ich selbst mehrfach global begleitet habe.',
        'actions' => [
            ['Demand-Setup anfragen', 'kontakt.php', 'primary'],
            ['Prozess-Assessment ansehen', 'leistungen/it-prozess-assessment.php', 'ghost'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Dauer', '2–4 Monate'],
    ['Aufwand bei Ihnen', '4–6 Std./Woche'],
    ['Ergebnis', 'Prozess in Betrieb, Gremium tagt'],
    ['Preis', '18.000 – 34.000 € netto'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Woran man erkennt, dass der Prozess fehlt</h2>
                <ul class="checklist is-cross">
                    <li>Anforderungen erreichen die IT über fünf Kanäle: E-Mail, Ticket, Telefon, Flurgespräch, Lenkungskreis.</li>
                    <li>Niemand kann sagen, wie viele offene Anforderungen es gibt – die Schätzungen schwanken um den Faktor drei.</li>
                    <li>Fachbereiche beschweren sich, dass „nichts passiert“; die IT arbeitet gleichzeitig am Anschlag.</li>
                    <li>Projekte starten, ohne dass jemand über Alternativen entschieden hat.</li>
                    <li>Die Antwort auf „Wann kommt das?“ lautet regelmäßig „Wir schauen mal“.</li>
                    <li>Nach einem Führungswechsel im Fachbereich wird alles neu priorisiert.</li>
                </ul>

                <h2>Der Prozess in fünf Schritten</h2>
<?php
$kette = [
    ['Erfassen', 'Ein Formular, ein Eingang'],
    ['Klären', 'Vollständig? Wer ist Auftraggeber?'],
    ['Bewerten', 'Nutzen, Aufwand, Risiko, Pflicht'],
    ['Entscheiden', 'Gremium, dokumentiert'],
    ['Rückmelden', 'Ja, Nein oder Wann – immer'],
];
$ketteLabel = 'Der Demand-Prozess';
include __DIR__ . '/../partials/kette.php';
?>

                <h3>Erfassen: ein Formular mit sechs Feldern</h3>
                <p>
                    Je länger das Anforderungsformular, desto häufiger wird es umgangen. Sechs
                    Felder genügen: Wer fragt an, welches Problem soll gelöst werden, seit wann
                    besteht es, was passiert ohne Lösung, gibt es einen Termin (und woher kommt
                    er), wer ist der fachliche Auftraggeber. Bewusst nicht abgefragt wird die
                    gewünschte Lösung – das ist der häufigste Fehler.
                </p>

                <h3>Bewerten: vier Kriterien, gewichtet</h3>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Kriterium</th><th scope="col">Frage</th><th scope="col">Skala</th></tr>
                        </thead>
                        <tbody>
                            <tr><td><strong>Pflicht</strong></td><td>Gesetz, Vertrag, Konzernvorgabe, Prüfungsfeststellung?</td><td>ja / nein (schlägt alles)</td></tr>
                            <tr><td><strong>Nutzen</strong></td><td>Eingesparte Zeit, vermiedene Kosten, Umsatzwirkung – geschätzt, aber beziffert</td><td>1–5</td></tr>
                            <tr><td><strong>Risiko ohne Umsetzung</strong></td><td>Was passiert, wenn nichts geschieht?</td><td>1–5</td></tr>
                            <tr><td><strong>Aufwand</strong></td><td>Personentage IT, Fachbereich, extern, Folgekosten pro Jahr</td><td>T-Shirt-Größen</td></tr>
                        </tbody>
                    </table>
                </div>
                <p>
                    Die Bewertung ist bewusst grob. Sie muss nicht stimmen, sie muss vergleichbar
                    sein. Der Wert liegt nicht in der Zahl, sondern darin, dass zwei Anforderungen
                    nebeneinander liegen und jemand begründen muss, warum die eine vorgeht.
                </p>

                <h3>Entscheiden: ein Gremium, ein Rhythmus</h3>
                <p>
                    Der IT-Steuerkreis entscheidet quartalsweise über das Portfolio, alles unter
                    einer definierten Schwelle entscheidet die IT-Leitung im Monatsrhythmus. Drei
                    Ergebnisse sind erlaubt: <strong>umsetzen</strong> (mit Termin und
                    Verantwortlichem), <strong>zurückstellen</strong> (mit Wiedervorlage) oder
                    <strong>ablehnen</strong> (mit Begründung). Das dritte Ergebnis ist das
                    wichtigste und wird in der Praxis am häufigsten vermieden.
                </p>

                <div class="callout">
                    <span class="callout-icon"><i data-icon="message-circle" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Warum die Rückmeldung entscheidet</h3>
                        <p>
                            Die Akzeptanz des Prozesses hängt fast vollständig an einem Punkt:
                            Bekommt der Antragsteller eine Antwort? Ein klares „Nein, weil …“ wird
                            deutlich besser aufgenommen als ein monatelanges Schweigen. Wo
                            Rückmeldungen ausbleiben, findet der Flurfunk innerhalb weniger Wochen
                            zurück.
                        </p>
                    </div>
                </div>

                <h2>Was im Projekt entsteht</h2>
                <ul class="checklist">
                    <li>Prozessbeschreibung (3–5 Seiten) mit Rollen, Fristen und Eskalation</li>
                    <li>Anforderungsformular und Bewertungsschema, abgestimmt mit den Fachbereichen</li>
                    <li>Umsetzung im vorhandenen Werkzeug – Ticketsystem, Jira, ServiceNow, SharePoint-Liste; ein neues Werkzeug ist selten nötig</li>
                    <li>Geschäftsordnung des Entscheidungsgremiums inklusive Tagesordnung und Protokollvorlage</li>
                    <li>Portfolioübersicht für die Geschäftsführung: Was ist beauftragt, was läuft, was wurde abgelehnt</li>
                    <li>Begleitung der ersten drei Gremiensitzungen – dort entscheidet sich, ob der Prozess hält</li>
                </ul>

                <h2>Aus der Praxis</h2>
                <p>
                    Ich habe die Einführung eines globalen IT-Demand-Prozesses in einem
                    international tätigen Konzern begleitet – über mehrere Gesellschaften und
                    Vertriebsgesellschaften hinweg, mit unterschiedlichen Arbeitsweisen,
                    unterschiedlichen Sprachen und einem beträchtlichen Bestand an gewachsenen
                    Sonderwegen. Drei Erkenntnisse daraus:
                </p>
                <ol class="steps">
                    <li>
                        <h3>Der Prozess ist einfach, die Einführung nicht</h3>
                        <p>Fünf Schritte erklärt man in zehn Minuten. Der Aufwand liegt darin,
                           dass fünfzig Menschen ihre bisherige Abkürzung aufgeben.</p>
                    </li>
                    <li>
                        <h3>Ohne Rückendeckung von oben chancenlos</h3>
                        <p>Wenn die Geschäftsführung selbst am Prozess vorbei beauftragt, ist er
                           innerhalb eines Quartals tot. Das gehört vorher besprochen, nicht
                           nachher beklagt.</p>
                    </li>
                    <li>
                        <h3>Sichtbarkeit erzeugt Akzeptanz</h3>
                        <p>Sobald Fachbereiche sehen, was sonst noch offen ist und warum ihre
                           Anforderung Platz sieben hat, sinkt der Druck spürbar. Transparenz
                           wirkt stärker als jede Prozessschulung.</p>
                    </li>
                </ol>

                <p>
                    <strong>Preis:</strong> 18.000 € netto für einen Standort mit vorhandenem
                    Ticketsystem, 26.000 € im Regelfall, bis 34.000 € bei mehreren
                    Gesellschaften mit Harmonisierungsbedarf und mehrsprachiger Einführung.
                </p>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['leistungen/service-management.php', 'themen/prozessharmonisierung.php', 'themen/it-kennzahlen.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
