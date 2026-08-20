<?php
$page = [
    'title'       => 'IT-Governance im Mittelstand: was der Begriff konkret bedeutet',
    'description' => 'Ein Grundlagenartikel: Was IT-Governance im Mittelstand tatsächlich heißt, welche zehn Bausteine dazugehören, was der Unterschied zu IT-Management und Informationssicherheit ist – und in welcher Reihenfolge man vorgeht.',
    'section'     => 'wissen',
    'path'        => 'wissen/it-governance-mittelstand.php',
    'crumbs'      => [['Wissen', 'wissen/'], ['IT-Governance im Mittelstand', null]],
    'hero'        => [
        'kicker' => 'Leitfaden · Grundlagen',
        'h1'     => 'IT-Governance im Mittelstand – <span class="accent">ohne Konzernsprache</span>',
        'lead'   => 'Der Begriff schreckt ab, weil er nach Vorstandsetage klingt. Gemeint ist etwas sehr Praktisches: dass jemand weiß, wer über IT entscheidet, wie das abläuft und woran man erkennt, dass es eingehalten wurde.',
    ],
];
include __DIR__ . '/../partials/header.php';

$asideCta = [
    'title' => 'Selbst einschätzen',
    'text'  => 'Zwölf Fragen, fünf Stufen, ein ehrliches Ergebnis – ohne Datenabgabe.',
    'link'  => ['Zur Selbsteinschätzung', 'wissen/reifegradmodell.php'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <div class="toc">
                    <h2>Inhalt</h2>
                    <ol>
                        <li><a href="#definition">Was IT-Governance eigentlich ist</a></li>
                        <li><a href="#abgrenzung">Abgrenzung zu IT-Management und Sicherheit</a></li>
                        <li><a href="#bausteine">Die zehn Bausteine</a></li>
                        <li><a href="#mittelstand">Was im Mittelstand anders ist</a></li>
                        <li><a href="#reihenfolge">Die richtige Reihenfolge</a></li>
                        <li><a href="#fehler">Fünf typische Fehler</a></li>
                    </ol>
                </div>

                <h2 id="definition">Was IT-Governance eigentlich ist</h2>
                <p>
                    IT-Governance beantwortet drei Fragen, und zwar auf eine Weise, die sich
                    belegen lässt:
                </p>
                <ol class="steps">
                    <li>
                        <h3>Wer entscheidet worüber?</h3>
                        <p>Über Budget, Architektur, Anwendungen, Auslagerungen, Risiken,
                           Ausnahmen. Nicht in einem Organigramm, sondern konkret: ab welchem
                           Betrag, in welchem Gremium, mit wessen Zustimmung.</p>
                    </li>
                    <li>
                        <h3>Nach welchen Regeln?</h3>
                        <p>Welche Verfahren gelten, wer muss beteiligt werden, was ist verboten,
                           was ist genehmigungspflichtig, was passiert bei Abweichung.</p>
                    </li>
                    <li>
                        <h3>Woran erkennt man, dass es eingehalten wird?</h3>
                        <p>Kontrollen, Kennzahlen, Berichte, Nachweise. Ohne diesen dritten Punkt
                           ist Governance eine Absichtserklärung – und in jeder Prüfung wertlos.</p>
                    </li>
                </ol>

                <div class="pullquote">
                    Governance ist nicht die Frage, ob gut gearbeitet wird. Sie ist die Frage, ob
                    das auch dann noch funktioniert, wenn die Person wechselt, die es bisher gut
                    gemacht hat.
                </div>

                <h2 id="abgrenzung">Abgrenzung: Governance, Management, Sicherheit</h2>
                <p>
                    Diese drei Begriffe werden oft synonym verwendet und meinen Unterschiedliches.
                    Die Unterscheidung hilft, weil sie die Zuständigkeiten klärt:
                </p>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Begriff</th><th scope="col">Beantwortet</th><th scope="col">Typische Rolle</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>IT-Governance</strong></td>
                                <td>Was soll erreicht werden, wer entscheidet, wie wird überwacht?</td>
                                <td>Geschäftsführung, IT-Steuerkreis</td>
                            </tr>
                            <tr>
                                <td><strong>IT-Management</strong></td>
                                <td>Wie wird das Beschlossene umgesetzt und betrieben?</td>
                                <td>IT-Leitung, Teams</td>
                            </tr>
                            <tr>
                                <td><strong>Informationssicherheit</strong></td>
                                <td>Wie werden Vertraulichkeit, Integrität und Verfügbarkeit geschützt?</td>
                                <td>Sicherheitsbeauftragter</td>
                            </tr>
                            <tr>
                                <td><strong>Datenschutz</strong></td>
                                <td>Wie werden personenbezogene Daten rechtskonform verarbeitet?</td>
                                <td>Datenschutzbeauftragter, Kanzlei</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p>
                    Wichtig ist die Grenze nach unten und nach außen: Governance sagt nicht, welche
                    Firewall gekauft wird. Und sie ersetzt keine rechtliche Bewertung – ob eine
                    Verarbeitung zulässig ist, entscheidet nicht der IT-Steuerkreis.
                </p>

                <h2 id="bausteine">Die zehn Bausteine</h2>
                <p>
                    Wenn man alle gängigen Rahmenwerke – COBIT, ISO/IEC 27001, ITIL, aber auch die
                    Anforderungslisten von Prüfern und Kunden – auf das reduziert, was ein
                    mittelständisches Unternehmen tatsächlich braucht, bleiben zehn Bausteine:
                </p>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Baustein</th><th scope="col">Mindestumfang im Mittelstand</th></tr>
                        </thead>
                        <tbody>
                            <tr><td><strong>1 · Steuerungsstruktur</strong></td><td>Ein Gremium, das quartalsweise tagt und protokolliert wird</td></tr>
                            <tr><td><strong>2 · Rollen und Verantwortlichkeiten</strong></td><td>10–15 benannte Rollen mit Vertretung</td></tr>
                            <tr><td><strong>3 · Regelungen</strong></td><td>6–10 Richtlinien, freigegeben und bekannt</td></tr>
                            <tr><td><strong>4 · Anforderungssteuerung (Demand)</strong></td><td>Ein Eingang, ein Bewertungsschema, eine Entscheidungsrunde</td></tr>
                            <tr><td><strong>5 · Servicebetrieb</strong></td><td>Servicekatalog, Incident- und Change-Prozess</td></tr>
                            <tr><td><strong>6 · Risikomanagement</strong></td><td>15–30 Risiken, quartalsweise geprüft, in Euro bewertet</td></tr>
                            <tr><td><strong>7 · Kontrollen</strong></td><td>15–40 Kontrollen mit Nachweis im Arbeitsablauf</td></tr>
                            <tr><td><strong>8 · Dokumentation und Inventar</strong></td><td>Kerndokumente aktuell, Anwendungsverzeichnis vollständig</td></tr>
                            <tr><td><strong>9 · Dienstleistersteuerung</strong></td><td>Übersicht, Kritikalität, jährliche Bewertung der wichtigsten</td></tr>
                            <tr><td><strong>10 · Kennzahlen und Bericht</strong></td><td>8–12 Kennzahlen, ein Bericht monatlich auf einer Seite</td></tr>
                        </tbody>
                    </table>
                </div>
                <p>
                    Das ist der vollständige Umfang. Ein Unternehmen mit 300 Mitarbeitenden, das
                    diese zehn Punkte erfüllt, besteht die allermeisten Prüfungen und kann seine IT
                    steuern. Alles, was darüber hinausgeht, sollte einen konkreten Anlass haben.
                </p>

                <h2 id="mittelstand">Was im Mittelstand anders ist</h2>
                <ul class="checklist">
                    <li><strong>Personalunion ist normal.</strong> Eine Person trägt drei bis fünf
                        Rollen. Das ist zulässig, solange unvereinbare Rollen getrennt bleiben –
                        wer beantragt, darf nicht genehmigen.</li>
                    <li><strong>Kurze Wege sind ein Vorteil.</strong> Entscheidungen, die im
                        Konzern drei Gremien durchlaufen, fallen hier in einem Gespräch. Governance
                        soll diesen Vorteil nicht zerstören, sondern absichern.</li>
                    <li><strong>Es gibt keinen Stab.</strong> Niemand hat Zeit, ein
                        Managementsystem zu pflegen. Deshalb muss jeder Nachweis im Arbeitsablauf
                        entstehen, nicht daneben.</li>
                    <li><strong>Der Anlass kommt von außen.</strong> Selten beginnt ein
                        Governance-Projekt aus eigenem Antrieb. Meist ist es ein Prüfbericht, ein
                        Kunde, ein Vorfall oder eine Konzernmutter.</li>
                    <li><strong>Historisch gewachsen ist der Normalzustand.</strong> Wer das als
                        Versäumnis behandelt, verliert die Beteiligung der Menschen, die es
                        aufgebaut haben.</li>
                </ul>

                <h2 id="reihenfolge">Die richtige Reihenfolge</h2>
                <p>
                    Die Reihenfolge ist wichtiger als die Vollständigkeit. Wer Kontrollen einführt,
                    bevor Rollen geklärt sind, kontrolliert ins Leere. Wer Prozesse beschreibt,
                    ohne Verantwortliche zu benennen, beschreibt Wünsche.
                </p>
<?php
$kette = [
    ['1 · Standort', 'Wo stehen wir wirklich?'],
    ['2 · Rollen', 'Wer ist wofür zuständig?'],
    ['3 · Regeln', 'Was soll gelten?'],
    ['4 · Prozesse', 'Wie läuft es ab?'],
    ['5 · Kontrollen', 'Wird es eingehalten?'],
    ['6 · Bericht', 'Wie steuern wir?'],
];
$ketteLabel = 'Reihenfolge beim Aufbau';
include __DIR__ . '/../partials/kette.php';
?>
                <p>
                    Für ein Unternehmen ohne Vorarbeit sind das realistisch 12 bis 24 Monate –
                    nebenbei, im Tagesgeschäft, mit Hilfe. Wer schneller fertig sein will, bekommt
                    Dokumente statt Wirkung.
                </p>

                <h2 id="fehler">Fünf typische Fehler</h2>
                <ol class="steps">
                    <li>
                        <h3>Das Konzernregelwerk kopieren</h3>
                        <p>Vierzig Dokumente aus einer anderen Organisation passen nicht und werden
                           nicht gelebt. Sie erzeugen zusätzlich das Risiko, an Regeln gemessen zu
                           werden, die man sich selbst gegeben hat.</p>
                    </li>
                    <li>
                        <h3>Governance als IT-Projekt behandeln</h3>
                        <p>Ohne Beteiligung der Geschäftsführung und der Fachbereiche entsteht ein
                           IT-internes Regelwerk, das an der ersten Ausnahme von oben zerbricht.</p>
                    </li>
                    <li>
                        <h3>Werkzeug vor Struktur</h3>
                        <p>Ein GRC-Werkzeug löst kein Governance-Problem. Es verwaltet eine
                           Struktur, die es vorher geben muss – sonst verwaltet es Leere,
                           allerdings mit Lizenzkosten.</p>
                    </li>
                    <li>
                        <h3>Vollständigkeit vor Wirksamkeit</h3>
                        <p>Lieber fünf Kontrollen, die laufen, als vierzig, die dokumentiert sind.
                           Ein Prüfer bewertet eine funktionierende Teilmenge deutlich besser als
                           ein vollständiges System ohne Nachweise.</p>
                    </li>
                    <li>
                        <h3>Nach dem Projekt aufhören</h3>
                        <p>Governance ist ein Betriebsmodus, kein Projekt. Ohne festen Rhythmus –
                           Gremium, Kontrollen, Bericht – ist nach zwölf Monaten der alte Zustand
                           erreicht, nur mit Dokumenten im Laufwerk.</p>
                    </li>
                </ol>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['wissen/reifegradmodell.php', 'leistungen/quick-assessment.php', 'themen/it-kennzahlen.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
