<?php
$page = [
    'title'       => 'IT-Asset- und Applikationsmanagement',
    'description' => 'Ohne Inventar keine Kontrolle: Wie ein belastbares Verzeichnis von Systemen und Anwendungen entsteht, welche Felder wirklich nötig sind, wie es aktuell bleibt und was Applikationsverantwortliche damit zu tun haben.',
    'section'     => 'themen',
    'path'        => 'themen/asset-applikationsmanagement.php',
    'crumbs'      => [['Themen', 'themen/'], ['Asset- & Applikationsmanagement', null]],
    'hero'        => [
        'kicker' => 'Thema · Grundlagen',
        'h1'     => 'Was Sie nicht kennen, <span class="accent">können Sie nicht schützen</span>',
        'lead'   => 'Das Inventar ist die unspektakulärste Grundlage der IT-Governance – und die, an der am häufigsten alles hängt: Risikobewertung, Notfallplanung, Lizenzkosten, Patchmanagement, Berechtigungen, Prüfungsnachweise.',
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Typische Anzahl', '40–150 Anwendungen'],
    ['Pflichtfelder', '8–10 genügen'],
    ['Aktualität', 'automatisiert erheben'],
    ['Verantwortung', 'je Anwendung eine Person'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Die Überraschung bei der ersten Erhebung</h2>
                <p>
                    Fast jedes mittelständische Unternehmen unterschätzt die Zahl seiner
                    Anwendungen um den Faktor zwei bis drei. Die IT nennt 40, tatsächlich sind es
                    120. Der Unterschied besteht aus Fachanwendungen, die Abteilungen selbst
                    beschafft haben, aus Werkzeugen mit Kreditkartenabo, aus Altsystemen, die
                    „nur noch für die Historie“ laufen, und aus Cloud-Diensten, die niemand
                    freigegeben hat.
                </p>
                <p>
                    Diese Lücke ist kein Randproblem. Jede nicht erfasste Anwendung ist ein
                    System ohne Verantwortlichen, ohne Sicherheitsbewertung, ohne
                    Wiederanlaufplanung und ohne Vertragsübersicht.
                </p>

                <h2>Wie man das Inventar wirklich vollständig bekommt</h2>
                <ol class="steps">
                    <li>
                        <h3>Technische Erhebung</h3>
                        <p>Verzeichnisdienst, Netzwerkerkennung, Softwareverteilung,
                           Firewall-Protokolle für ausgehende Verbindungen zu Cloud-Diensten.
                           Liefert die Systeme, die im Netz sichtbar sind.</p>
                    </li>
                    <li>
                        <h3>Kaufmännische Erhebung</h3>
                        <p>Kreditorenliste und Kreditkartenabrechnungen der letzten 24 Monate,
                           gefiltert auf Softwareanbieter. Findet zuverlässig, was die technische
                           Erhebung nicht sieht – der ergiebigste Weg.</p>
                    </li>
                    <li>
                        <h3>Befragung der Fachbereiche</h3>
                        <p>Eine kurze Abfrage je Abteilung: Welche Programme brauchen Sie für Ihre
                           Arbeit? Ohne Vorwurf gestellt, sonst kommt nichts zurück. Wer
                           Schatten-IT sanktioniert, bekommt nie ein vollständiges Bild.</p>
                    </li>
                    <li>
                        <h3>Abgleich und Bereinigung</h3>
                        <p>Zusammenführen, Dubletten entfernen, Zuständigkeiten klären. Erfahrung
                           mit SQL und Datenauswertung hilft hier mehr, als man vermuten würde –
                           die Quellen sind selten sauber.</p>
                    </li>
                </ol>

                <h2>Welche Felder das Verzeichnis braucht</h2>
                <p>
                    Der häufigste Fehler ist ein Datenmodell mit 30 Feldern. Es wird nie
                    vollständig gepflegt und verliert dadurch seine Glaubwürdigkeit. Diese acht
                    bis zehn Felder genügen für alles, was Governance braucht:
                </p>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Feld</th><th scope="col">Wofür es gebraucht wird</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Name und Zweck der Anwendung</td><td>Verständlichkeit für Nichttechniker</td></tr>
                            <tr><td>Fachlicher Verantwortlicher</td><td>Berechtigungsfreigaben, Anforderungen, Budget</td></tr>
                            <tr><td>Technischer Verantwortlicher</td><td>Betrieb, Änderungen, Wiederanlauf</td></tr>
                            <tr><td>Kritikalität</td><td>Notfallplanung, Investitionsentscheidungen</td></tr>
                            <tr><td>Betriebsart (eigen / Dienstleister / Cloud)</td><td>Dienstleistersteuerung, Datenstandort</td></tr>
                            <tr><td>Verarbeitet personenbezogene Daten?</td><td>Zuarbeit für Datenschutz und Verzeichnis der Verarbeitungstätigkeiten</td></tr>
                            <tr><td>Schnittstellen zu anderen Systemen</td><td>Auswirkungsanalyse bei Ausfall oder Ablösung</td></tr>
                            <tr><td>Unterstützung durch den Hersteller bis</td><td>Investitionsplanung, Sicherheitsrisiko</td></tr>
                            <tr><td>Vertrag, Kosten pro Jahr, Kündigungsfrist</td><td>Kostentransparenz, Ausstieg</td></tr>
                            <tr><td>Letzte Prüfung des Eintrags</td><td>Nachweis der Pflege</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout">
                    <span class="callout-icon"><i data-icon="user-check" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Der Applikationsverantwortliche im Fachbereich</h3>
                        <p>
                            Ein oft übersehener Hebel: Der fachliche Verantwortliche einer
                            Anwendung gehört nicht in die IT, sondern in den Fachbereich, der sie
                            nutzt. Er entscheidet über Berechtigungen, priorisiert Anforderungen
                            und trägt die Kosten. Diese Zuordnung entlastet die IT spürbar und
                            beendet die häufigste Fehlannahme im Unternehmen – dass die IT
                            entscheidet, wer im ERP was darf.
                        </p>
                    </div>
                </div>

                <h2>Wie das Inventar aktuell bleibt</h2>
                <ul class="checklist">
                    <li><strong>Automatisch erheben, manuell ergänzen.</strong> Technische Daten
                        kommen aus Werkzeugen, fachliche Felder pflegt der Verantwortliche.</li>
                    <li><strong>An den Beschaffungsprozess koppeln.</strong> Keine Freigabe einer
                        Rechnung für Software ohne Eintrag im Verzeichnis – eine Regel, die
                        erstaunlich zuverlässig wirkt.</li>
                    <li><strong>Jährliche Bestätigung.</strong> Jeder Verantwortliche bestätigt
                        einmal jährlich seinen Eintrag. Das dauert Minuten und ist gleichzeitig
                        ein Nachweis.</li>
                    <li><strong>Aussortieren erlauben.</strong> Anwendungen, die niemand mehr
                        nutzt, gehören abgeschaltet – mit dokumentierter Entscheidung zur
                        Datenaufbewahrung.</li>
                </ul>

                <h2>Was das Inventar ermöglicht</h2>
                <p>
                    Der Aufwand lohnt sich, weil praktisch jede andere Governance-Aufgabe darauf
                    aufsetzt:
                </p>
<?php
$kette = [
    ['Inventar', 'Was haben wir?'],
    ['Kritikalität', 'Was ist wichtig?'],
    ['Risiko & Notfall', 'Was passiert bei Ausfall?'],
    ['Kontrollen', 'Was prüfen wir regelmäßig?'],
    ['Nachweis', 'Was zeigen wir dem Prüfer?'],
];
$ketteLabel = 'Warum das Inventar am Anfang steht';
include __DIR__ . '/../partials/kette.php';
?>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['themen/it-dokumentation.php', 'themen/it-risikomanagement.php', 'leistungen/service-management.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
