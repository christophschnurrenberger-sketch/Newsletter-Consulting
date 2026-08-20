<?php
$page = [
    'title'       => 'NIS2 im Mittelstand: Betroffenheit, Maßnahmen, Lieferkette',
    'description' => 'Was NIS2 für mittelständische Unternehmen bedeutet: Betroffenheit über Sektoren und Größe, die zehn Maßnahmenbereiche, Meldepflichten, Verantwortung der Geschäftsleitung und der Druck über die Lieferkette. Fachliche Einordnung, keine Rechtsberatung.',
    'section'     => 'themen',
    'path'        => 'themen/nis2.php',
    'crumbs'      => [['Themen', 'themen/'], ['NIS2', null]],
    'hero'        => [
        'kicker' => 'Thema · Regulatorik',
        'h1'     => 'NIS2: die Anforderung, die auch <span class="accent">Nichtbetroffene</span> erreicht',
        'lead'   => 'Die meisten mittelständischen Unternehmen, die mich wegen NIS2 ansprechen, sind selbst gar nicht unmittelbar verpflichtet. Sie liefern nur an jemanden, der es ist – und bekommen deshalb einen Fragebogen mit 60 Positionen.',
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Rechtsgrundlage', 'Richtlinie (EU) 2022/2555'],
    ['Maßnahmenbereiche', '10 (Art. 21 Abs. 2)'],
    ['Meldefristen', '24 h / 72 h / 1 Monat'],
    ['Leitung', 'Billigung, Überwachung, Schulung'],
];
$asideCta = [
    'title' => 'Vorbereitung statt Panik',
    'text'  => 'Die zehn Maßnahmenbereiche lassen sich strukturiert abarbeiten. Eine Gap-Analyse zeigt in vier bis acht Wochen, wo Sie stehen.',
    'link'  => ['Gap-Analyse ansehen', 'leistungen/gap-analyse.php'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

<?php
$rechtshinweisText = 'NIS2 ist eine EU-Richtlinie, die durch nationales Recht umgesetzt wird –
    in Deutschland durch das NIS2-Umsetzungsgesetz und die daraus folgenden Änderungen des
    BSI-Gesetzes. Zeitplan, Detailregelungen und Auslegungsfragen haben sich mehrfach
    verändert. Ob und ab wann Ihr Unternehmen verpflichtet ist, welche Fristen gelten und
    welche Haftungsfolgen bestehen, ist eine Rechtsfrage.';
include __DIR__ . '/../partials/rechtshinweis.php';
?>

                <h2>Worum es bei NIS2 im Kern geht</h2>
                <p>
                    NIS2 ist der Versuch, ein Mindestniveau an Cybersicherheit über Branchen
                    hinweg verbindlich zu machen. Das Ziel ist nicht technische Perfektion,
                    sondern Nachweisbarkeit von Management: Es soll ein Verfahren geben, mit dem
                    Risiken erkannt, bewertet, behandelt und überwacht werden – und die
                    Geschäftsleitung soll dafür einstehen.
                </p>
                <p>
                    Aus Sicht eines mittelständischen Unternehmens sind vier Punkte wesentlich:
                </p>
                <ul class="checklist">
                    <li><strong>Es geht um Organisation, nicht nur um Technik.</strong> Firewalls
                        ersetzen kein Verfahren.</li>
                    <li><strong>Die Leitungsebene wird ausdrücklich adressiert.</strong> Billigung
                        der Maßnahmen, Überwachung ihrer Umsetzung, Schulungspflicht.</li>
                    <li><strong>Die Lieferkette ist Teil der Anforderung.</strong> Wer verpflichtet
                        ist, muss die Sicherheit seiner Lieferanten berücksichtigen – und reicht
                        die Anforderungen weiter.</li>
                    <li><strong>Vorfälle müssen gemeldet werden</strong>, und zwar in engen Fristen.</li>
                </ul>

                <h2>Betroffenheit: Sektor und Größe</h2>
                <p>
                    Die Richtlinie arbeitet mit zwei Kriterien, die zusammen erfüllt sein müssen:
                    Das Unternehmen ist in einem der aufgeführten Sektoren tätig, und es
                    überschreitet bestimmte Größenschwellen. Unterschieden wird zwischen
                    <strong>wesentlichen</strong> und <strong>wichtigen</strong> Einrichtungen –
                    der Unterschied liegt vor allem in der Aufsichtsintensität und im
                    Sanktionsrahmen, nicht in den Pflichten selbst.
                </p>

                <div class="table-wrap">
                    <table class="data-table">
                        <caption>Vereinfachte Darstellung der Grundsystematik. Es gibt Sonderfälle,
                            in denen die Größenschwelle keine Rolle spielt – etwa bei bestimmten
                            Anbietern kritischer Dienste. Die Einordnung im Einzelfall gehört zur
                            Kanzlei.</caption>
                        <thead>
                            <tr><th scope="col">Kategorie</th><th scope="col">Sektoren (Beispiele)</th><th scope="col">Größe (Richtwert)</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Wesentliche Einrichtungen</strong></td>
                                <td>Energie, Verkehr, Bankwesen, Finanzmarkt, Gesundheit, Trinkwasser, Abwasser, digitale Infrastruktur, IKT-Dienstleistungsmanagement, öffentliche Verwaltung, Weltraum</td>
                                <td>ab 250 Beschäftigten oder mehr als 50 Mio. € Umsatz und mehr als 43 Mio. € Bilanzsumme</td>
                            </tr>
                            <tr>
                                <td><strong>Wichtige Einrichtungen</strong></td>
                                <td>zusätzlich u. a. Post- und Kurierdienste, Abfallwirtschaft, Chemie, Lebensmittel, verarbeitendes Gewerbe (Medizinprodukte, Elektronik, Maschinenbau, Fahrzeugbau), digitale Dienste, Forschung</td>
                                <td>ab 50 Beschäftigten oder mehr als 10 Mio. € Umsatz und Bilanzsumme</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout">
                    <span class="callout-icon"><i data-icon="help-circle" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Der häufigste Irrtum</h3>
                        <p>
                            „Wir sind Maschinenbauer, uns betrifft das nicht.“ Der Maschinenbau ist
                            im Anhang der Richtlinie als Sektor aufgeführt. Ob ein konkretes
                            Unternehmen erfasst wird, hängt von Tätigkeit, Größe und nationaler
                            Umsetzung ab – die Aussage „betrifft uns nicht“ sollte deshalb aus
                            einer geprüften Einschätzung stammen und nicht aus dem Bauchgefühl.
                            Ein strukturierter Weg dorthin steht im
                            <a href="/wissen/nis2-betroffenheit.php">Leitfaden zur Betroffenheitsprüfung</a>.
                        </p>
                    </div>
                </div>

                <h2>Die zehn Maßnahmenbereiche</h2>
                <p>
                    Artikel 21 Absatz 2 der Richtlinie nennt zehn Bereiche, die von
                    Risikomanagementmaßnahmen abgedeckt sein müssen. Sie sind bewusst
                    technikoffen formuliert – die Ausgestaltung richtet sich nach Größe, Risiko
                    und Stand der Technik.
                </p>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Bereich</th><th scope="col">Was das organisatorisch bedeutet</th></tr>
                        </thead>
                        <tbody>
                            <tr><td><strong>1 · Risikoanalyse und Sicherheitskonzepte</strong></td><td>Ein wiederkehrendes Verfahren zur Risikobewertung mit dokumentiertem Ergebnis – nicht eine einmalige Liste</td></tr>
                            <tr><td><strong>2 · Bewältigung von Sicherheitsvorfällen</strong></td><td>Definierter Ablauf: erkennen, bewerten, eskalieren, beheben, nachbereiten, melden</td></tr>
                            <tr><td><strong>3 · Betriebskontinuität</strong></td><td>Sicherung, Wiederanlauf, Krisenmanagement – inklusive Test</td></tr>
                            <tr><td><strong>4 · Sicherheit der Lieferkette</strong></td><td>Anforderungen an Dienstleister, deren Bewertung und vertragliche Verankerung</td></tr>
                            <tr><td><strong>5 · Sicherheit in Beschaffung und Entwicklung</strong></td><td>Sicherheitsanforderungen bei Auswahl, Einführung und Wartung von Systemen</td></tr>
                            <tr><td><strong>6 · Bewertung der Wirksamkeit</strong></td><td>Regelmäßige Überprüfung, ob die Maßnahmen tatsächlich wirken – das sind Kontrollen</td></tr>
                            <tr><td><strong>7 · Cyberhygiene und Schulung</strong></td><td>Grundschutzmaßnahmen und Sensibilisierung, dokumentiert mit Teilnehmernachweis</td></tr>
                            <tr><td><strong>8 · Kryptografie und Verschlüsselung</strong></td><td>Regelung, wo verschlüsselt wird und wie mit Schlüsseln umgegangen wird</td></tr>
                            <tr><td><strong>9 · Personal, Zugriffskontrolle, Asset-Management</strong></td><td>Rollen, Berechtigungen, Inventar – die klassischen Governance-Themen</td></tr>
                            <tr><td><strong>10 · Mehrfaktor-Authentifizierung, gesicherte Kommunikation</strong></td><td>MFA, gesicherte Sprach- und Textkommunikation, Notfallkommunikation</td></tr>
                        </tbody>
                    </table>
                </div>

                <p>
                    Auffällig ist, dass mindestens sechs dieser zehn Bereiche keine
                    Sicherheitsthemen im engeren Sinne sind, sondern Governance-Themen: Verfahren,
                    Rollen, Dokumentation, Kontrollen, Dienstleistersteuerung. Genau deshalb
                    kommen Unternehmen mit einem reinen Technikprojekt nicht weiter.
                </p>

                <h2>Meldepflichten: die 24-Stunden-Uhr</h2>
                <p>
                    Für erhebliche Sicherheitsvorfälle sieht die Richtlinie ein dreistufiges
                    Meldeverfahren vor:
                </p>
<?php
$kette = [
    ['24 Stunden', 'Frühwarnung: erste Meldung, auch unvollständig'],
    ['72 Stunden', 'Meldung mit Bewertung, Schweregrad, Auswirkungen'],
    ['1 Monat', 'Abschlussbericht mit Ursache und Maßnahmen'],
];
$ketteLabel = 'Meldefristen nach NIS2';
include __DIR__ . '/../partials/kette.php';
?>
                <p>
                    Die praktische Konsequenz ist nicht die Meldung selbst, sondern was ihr
                    vorausgehen muss: Jemand muss den Vorfall <em>erkennen</em>, jemand muss
                    entscheiden, ob er erheblich ist, und jemand muss befugt sein, zu melden –
                    auch am Wochenende. Wer das nicht vorab geregelt hat, verliert die ersten
                    zwölf Stunden mit der Frage, wer jetzt zuständig ist.
                </p>

                <h2>Verantwortung der Geschäftsleitung</h2>
                <p>
                    NIS2 adressiert die Leitungsebene direkt: Sie muss die
                    Risikomanagementmaßnahmen billigen, ihre Umsetzung überwachen und sich
                    schulen lassen. Das ist eine deutliche Verschiebung gegenüber der bisherigen
                    Praxis, in der IT-Sicherheit oft vollständig an die IT delegiert wurde.
                </p>
                <p>
                    Für die Praxis heißt das: Es braucht einen dokumentierten Beschluss,
                    regelmäßige Berichterstattung und einen Nachweis der Schulung. Drei
                    Dokumente, die schnell erstellt sind – aber nur, wenn jemand daran denkt.
                    Zu den Haftungsfolgen äußere ich mich nicht; das ist Sache Ihrer Kanzlei.
                </p>

                <h2>Der Weg über die Lieferkette</h2>
                <p>
                    Der wirtschaftlich relevanteste Effekt für den Mittelstand entsteht
                    mittelbar. Verpflichtete Unternehmen müssen die Sicherheit ihrer Lieferanten
                    berücksichtigen – und tun das auf dem naheliegenden Weg: über Verträge und
                    Fragebögen. In der Praxis erreicht NIS2 damit Unternehmen, die selbst nie
                    unter die Richtlinie fallen würden.
                </p>
                <div class="callout is-warning">
                    <span class="callout-icon"><i data-icon="link" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Was das konkret bedeutet</h3>
                        <p>
                            Ein Zulieferer mit 80 Mitarbeitenden bekommt vom Großkunden einen
                            Fragebogen mit 40 bis 80 Positionen und drei Wochen Zeit. Wer dann
                            keine Richtlinien, keine Rollen und keine Nachweise hat, füllt ihn
                            entweder unehrlich aus – mit vertraglichem Risiko – oder verliert
                            Punkte in der Lieferantenbewertung. Beides ist teurer als eine
                            geordnete Vorbereitung.
                        </p>
                    </div>
                </div>

                <h2>Ein realistischer Fahrplan</h2>
                <ol class="phase-list">
                    <li>
                        <span class="phase-week">Schritt 1</span>
                        <h3>Betroffenheit klären lassen</h3>
                        <p>Rechtliche Einschätzung durch eine Kanzlei einholen – oder,
                           wenn das zu früh erscheint, bewusst mit der Annahme „als ob“ arbeiten.
                           Beides ist besser als Nichtstun.</p>
                    </li>
                    <li>
                        <span class="phase-week">Schritt 2</span>
                        <h3>Standort bestimmen</h3>
                        <p>Gap-Analyse gegen die zehn Maßnahmenbereiche. Ergebnis: Wo stehen Sie,
                           was fehlt, was kostet es, was zuerst.</p>
                    </li>
                    <li>
                        <span class="phase-week">Schritt 3</span>
                        <h3>Grundlagen bauen</h3>
                        <p>Rollen, Richtlinien, Vorfallverfahren, Dienstleisterübersicht,
                           Sicherungs- und Wiederanlaufkonzept. Das sind die Bereiche mit der
                           größten Wirkung je investiertem Euro.</p>
                    </li>
                    <li>
                        <span class="phase-week">Schritt 4</span>
                        <h3>Wirksamkeit nachweisen</h3>
                        <p>Kontrollen einführen, Nachweise erzeugen, Bericht an die
                           Geschäftsleitung etablieren. Ohne diesen Schritt bleibt es bei
                           Absichtserklärungen.</p>
                    </li>
                </ol>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['wissen/nis2-betroffenheit.php', 'leistungen/gap-analyse.php', 'themen/dienstleistermanagement.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
