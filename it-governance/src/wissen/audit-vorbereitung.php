<?php
$page = [
    'title'       => 'Audit-Vorbereitung: die 12 Wochen vor der Prüfung',
    'description' => 'Wochenplan für die Vorbereitung auf IT-Prüfungen: Prüfungsumfang klären, Nachweise sammeln, Stichproben simulieren, Interviews vorbereiten, Raum und Ablauf organisieren – mit Checkliste für jede Phase.',
    'section'     => 'wissen',
    'path'        => 'wissen/audit-vorbereitung.php',
    'crumbs'      => [['Wissen', 'wissen/'], ['Audit-Vorbereitung', null]],
    'hero'        => [
        'kicker' => 'Leitfaden · Praxis',
        'h1'     => 'Die zwölf Wochen <span class="accent">vor der Prüfung</span>',
        'lead'   => 'Prüfungen laufen nicht deshalb schlecht, weil die IT schlecht arbeitet. Sie laufen schlecht, weil niemand Nachweise findet, weil zwei Personen dieselbe Frage unterschiedlich beantworten und weil in Woche 12 begonnen wird, was in Woche 1 hätte anfangen müssen.',
    ],
];
include __DIR__ . '/../partials/header.php';

$asideCta = [
    'title' => 'Prüfung steht bevor?',
    'text'  => 'Ein Audit Readiness Assessment nimmt die Feststellungen vorweg – solange sie noch behebbar sind.',
    'link'  => ['Audit Readiness ansehen', 'leistungen/audit-readiness.php'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Vorab: Die Rolle, die Sie in der Prüfung haben</h2>
                <p>
                    Ein Prüfer ist kein Gegner, aber auch kein Berater. Seine Aufgabe ist es,
                    Aussagen zu belegen oder zu widerlegen. Daraus folgen drei Grundregeln, die
                    mehr bewirken als jede Nachdokumentation:
                </p>
                <ul class="checklist">
                    <li><strong>Fragen beantworten, nicht Geschichten erzählen.</strong> Jede
                        Zusatzinformation eröffnet einen neuen Prüfungspfad.</li>
                    <li><strong>Nicht raten.</strong> „Das weiß ich nicht, ich kläre es bis morgen“
                        ist eine gute Antwort. Eine falsche Vermutung wird zur Feststellung.</li>
                    <li><strong>Bekannte Lücken selbst benennen</strong> – mit Bewertung und
                        geplanter Maßnahme. Eine erkannte und terminierte Lücke wird deutlich
                        milder bewertet als eine, die der Prüfer findet.</li>
                </ul>

                <h2>Wochen 12 bis 10: Klären, worum es geht</h2>
                <ol class="phase-list">
                    <li>
                        <span class="phase-week">Woche 12</span>
                        <h3>Prüfungsumfang schriftlich klären</h3>
                        <p>
                            Welche Bereiche, welcher Zeitraum, welche Systeme, welcher
                            Prüfungsmaßstab? Wenn die Ankündigung vage ist: nachfragen. Prüfer
                            antworten in der Regel bereitwillig – und ein geklärter Umfang schützt
                            vor Arbeit, die niemand braucht.
                        </p>
                    </li>
                    <li>
                        <span class="phase-week">Woche 11</span>
                        <h3>Vorjahresfeststellungen aufarbeiten</h3>
                        <p>
                            Der erste Blick jedes Prüfers gilt den offenen Punkten des Vorjahres.
                            Eine wiederholte Feststellung wird härter bewertet als eine neue.
                            Was nicht behoben wurde, braucht mindestens eine dokumentierte
                            Entscheidung mit Begründung und Termin.
                        </p>
                    </li>
                    <li>
                        <span class="phase-week">Woche 10</span>
                        <h3>Nachweisliste aufstellen</h3>
                        <p>
                            Je Prüfungsthema: Welcher Nachweis belegt es, wo liegt er, wer
                            beschafft ihn, bis wann? Diese Liste ist das zentrale Werkzeug der
                            gesamten Vorbereitung – und sie zeigt in zwei Stunden, wo es eng wird.
                        </p>
                    </li>
                </ol>

                <h2>Wochen 9 bis 6: Sammeln und prüfen</h2>
                <div class="table-wrap">
                    <table class="data-table">
                        <caption>Die Nachweise, die in IT-Prüfungen fast immer angefordert werden.</caption>
                        <thead>
                            <tr><th scope="col">Thema</th><th scope="col">Typisch angeforderter Nachweis</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Organisation</td><td>Organigramm, Rollenbeschreibungen, Vertretungsregelung</td></tr>
                            <tr><td>Regelungen</td><td>Richtlinien mit Freigabedatum und Freigebendem</td></tr>
                            <tr><td>Berechtigungen</td><td>Benutzerliste mit Rollen, Liste privilegierter Konten, Nachweis der letzten Rezertifizierung</td></tr>
                            <tr><td>Ein- und Austritte</td><td>Stichprobe: Zeitpunkt der Kontoerstellung bzw. -deaktivierung im Verhältnis zum HR-Datum</td></tr>
                            <tr><td>Änderungen</td><td>Stichprobe von 5–20 Changes mit Genehmigung, Test und Umsetzungsnachweis</td></tr>
                            <tr><td>Betrieb</td><td>Sicherungsprotokolle, Ergebnisse von Rücksicherungstests, Monitoring-Auswertungen</td></tr>
                            <tr><td>Notfall</td><td>Notfallhandbuch, Protokoll der letzten Übung</td></tr>
                            <tr><td>Dienstleister</td><td>Übersicht, Verträge, Zertifikate, letzte Bewertung</td></tr>
                            <tr><td>Vorfälle</td><td>Vorfallliste mit Bewertung und Nachbearbeitung</td></tr>
                            <tr><td>Steuerung</td><td>Protokolle der Gremiensitzungen, Risikoübersicht, Maßnahmenstand</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout">
                    <span class="callout-icon"><i data-icon="clock" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Die Beschaffungszeit ist selbst ein Befund</h3>
                        <p>
                            Messen Sie, wie lange die Beschaffung jedes Nachweises dauert. Was
                            länger als einen Tag braucht, ist im Prüfungsalltag ein Problem – und
                            ein Hinweis darauf, dass die zugehörige Kontrolle keinen definierten
                            Nachweisort hat. Diese Erkenntnis ist oft wertvoller als der Nachweis
                            selbst.
                        </p>
                    </div>
                </div>

                <ol class="phase-list">
                    <li>
                        <span class="phase-week">Woche 9–8</span>
                        <h3>Nachweise beschaffen und bewerten</h3>
                        <p>Nicht nur sammeln – ansehen. Ist der Nachweis vollständig, datiert,
                           nachvollziehbar? Ein Screenshot ohne Datum und Systemkennung ist kein
                           Nachweis.</p>
                    </li>
                    <li>
                        <span class="phase-week">Woche 7</span>
                        <h3>Eigene Stichprobe ziehen</h3>
                        <p>Fünf Changes, fünf Ein- und Austritte, fünf Berechtigungsvergaben.
                           Genau so, wie ein Prüfer es täte. Was dabei auffällt, fällt auch dem
                           Prüfer auf – nur haben Sie jetzt noch Zeit.</p>
                    </li>
                    <li>
                        <span class="phase-week">Woche 6</span>
                        <h3>Sofortmaßnahmen entscheiden</h3>
                        <p>Was ist in sechs Wochen behebbar, was nicht? Für den Rest gilt: bewusst
                           entscheiden, dokumentieren, terminieren. Eine Maßnahmenliste mit
                           Terminen ist in der Prüfung ein starkes Argument.</p>
                    </li>
                </ol>

                <h2>Wochen 5 bis 1: Menschen und Organisation</h2>
                <ol class="phase-list">
                    <li>
                        <span class="phase-week">Woche 5</span>
                        <h3>Zuständigkeiten für die Prüfung festlegen</h3>
                        <p>Eine Person koordiniert – sie nimmt Anfragen entgegen, verteilt sie und
                           liefert aus. Ohne diese Bündelung bekommt der Prüfer aus drei Richtungen
                           drei verschiedene Antworten.</p>
                    </li>
                    <li>
                        <span class="phase-week">Woche 4</span>
                        <h3>Interviews vorbereiten</h3>
                        <p>Wer wird voraussichtlich befragt? Diese Personen sollten die relevanten
                           Regelungen kennen und ihre eigene Rolle beschreiben können. Ein
                           Rollenspiel von 90 Minuten wirkt Wunder.</p>
                    </li>
                    <li>
                        <span class="phase-week">Woche 3</span>
                        <h3>Unterlagen ordnen</h3>
                        <p>Ein Ordner, nach Prüfungsthemen strukturiert, mit Übersichtsblatt.
                           Nicht 200 Dateien in einer Freigabe. Der erste Eindruck der
                           Unterlagenqualität prägt die gesamte Prüfung.</p>
                    </li>
                    <li>
                        <span class="phase-week">Woche 2</span>
                        <h3>Ablauf organisieren</h3>
                        <p>Raum, Zugänge, Ansprechpartner, Zeitfenster, Verpflegung. Klingt
                           nebensächlich – ein Prüfer, der zwischen Terminen wartet, sucht sich
                           Beschäftigung, und die besteht aus zusätzlichen Fragen.</p>
                    </li>
                    <li>
                        <span class="phase-week">Woche 1</span>
                        <h3>Letzte Durchsicht</h3>
                        <p>Nachweisliste vollständig? Sofortmaßnahmen erledigt oder terminiert?
                           Kennt jeder seine Rolle? Was jetzt noch offen ist, bleibt offen –
                           hektische Nachdokumentation in der letzten Woche fällt auf und schadet.</p>
                    </li>
                </ol>

                <h2>Während der Prüfung</h2>
                <ul class="checklist">
                    <li>Alle Anfragen und Lieferungen protokollieren – Datum, Anforderung,
                        geliefert am. Das schützt vor der Feststellung „Unterlagen nicht vorgelegt“.</li>
                    <li>Täglich kurz intern abstimmen: Was wurde gefragt, wo wird es eng?</li>
                    <li>Keine Dokumente rückdatieren. Das ist der einzige Fehler, der aus einer
                        Feststellung ein ernstes Problem macht.</li>
                    <li>Bei Missverständnissen sofort klarstellen, nicht erst im Berichtsentwurf.</li>
                </ul>

                <h2>Nach der Prüfung</h2>
                <p>
                    Der Berichtsentwurf wird üblicherweise zur Stellungnahme vorgelegt. Diese
                    Gelegenheit sollte genutzt werden: sachliche Fehler korrigieren, Einordnungen
                    ergänzen, zu jeder Feststellung eine Maßnahme mit Termin und Verantwortlichem
                    benennen. Ein Bericht mit vollständigen Stellungnahmen liest sich für Beirat
                    und Gesellschafter völlig anders als einer ohne.
                </p>
                <p>
                    Und dann der wichtigste Schritt: die Maßnahmen tatsächlich umsetzen. Die
                    häufigste Ursache für Feststellungen ist die Wiederholung aus dem Vorjahr –
                    weil nach der Prüfung alle erleichtert waren und niemand nachgehalten hat.
                </p>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['leistungen/audit-readiness.php', 'wissen/kontrollen-nachweise.php', 'leistungen/kontrollframework.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
