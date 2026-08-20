<?php
$page = [
    'title'       => 'IT-Prozessharmonisierung über Standorte und Gesellschaften',
    'description' => 'Prozesse über mehrere Standorte und Gesellschaften harmonisieren: Was vereinheitlicht werden sollte, was lokal bleiben darf, wie man Widerstand ernst nimmt und warum das Vorgehen über Erfolg oder Scheitern entscheidet.',
    'section'     => 'themen',
    'path'        => 'themen/prozessharmonisierung.php',
    'crumbs'      => [['Themen', 'themen/'], ['Prozessharmonisierung', null]],
    'hero'        => [
        'kicker' => 'Thema · Organisation',
        'h1'     => 'Einheitlich, wo es nützt. <span class="accent">Unterschiedlich, wo es muss.</span>',
        'lead'   => 'Harmonisierungsprojekte scheitern selten an der Fachlichkeit. Sie scheitern daran, dass jemand aus der Zentrale einen Prozess vorgibt, ohne verstanden zu haben, warum der Standort in Polen ihn anders macht – und manchmal aus gutem Grund.',
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Typische Dauer', '6–18 Monate'],
    ['Erfolgsfaktor', 'lokale Beteiligung'],
    ['Häufigster Fehler', 'Vorgabe ohne Verständnis'],
    ['Messbar an', 'Kennzahlen, nicht Dokumenten'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Warum überhaupt harmonisieren?</h2>
                <p>
                    Nicht aus Ordnungsliebe. Es gibt vier belastbare Gründe – und wenn keiner
                    zutrifft, sollte man es lassen:
                </p>
                <ul class="checklist">
                    <li><strong>Nachweispflicht.</strong> Konzernvorgaben, Prüfungen und
                        Regulierung verlangen einheitliche Verfahren und vergleichbare Nachweise.</li>
                    <li><strong>Vertretbarkeit.</strong> Wenn jeder Standort anders arbeitet, kann
                        niemand einspringen – weder im Urlaub noch im Notfall.</li>
                    <li><strong>Kosten.</strong> Fünf Arbeitsweisen brauchen fünf Werkzeuge, fünf
                        Schulungen, fünf Fehlerquellen.</li>
                    <li><strong>Steuerbarkeit.</strong> Ohne gleiche Definitionen gibt es keine
                        vergleichbaren Zahlen – und ohne Zahlen keine Steuerung.</li>
                </ul>

                <h2>Was harmonisiert werden sollte – und was nicht</h2>
                <div class="versus">
                    <div class="versus-col is-good">
                        <h3>Vereinheitlichen</h3>
                        <ul class="checklist is-tight">
                            <li>Begriffe und Kategorien (was ist ein Incident, was ein Change?)</li>
                            <li>Genehmigungswege und Schwellenwerte</li>
                            <li>Rollenbild und Verantwortlichkeiten</li>
                            <li>Nachweisformate und Aufbewahrung</li>
                            <li>Kennzahldefinitionen und Berichtszeitpunkte</li>
                            <li>Mindestanforderungen an Sicherheit</li>
                        </ul>
                    </div>
                    <div class="versus-col is-bad">
                        <h3>Lokal lassen</h3>
                        <ul class="checklist is-cross is-tight">
                            <li>Arbeitsschritte, die von lokalem Recht abhängen</li>
                            <li>Abläufe, die an lokale Fertigung oder Kundenstruktur gebunden sind</li>
                            <li>Sprache der Kommunikation mit Endanwendern</li>
                            <li>Detailtiefe kleiner Standorte – ein Werk mit 20 Mitarbeitenden braucht kein Gremium</li>
                            <li>Werkzeuge, die tief in lokale Fachprozesse eingebunden sind</li>
                        </ul>
                    </div>
                </div>

                <div class="callout">
                    <span class="callout-icon"><i data-icon="globe" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Die 80/20-Regel der Harmonisierung</h3>
                        <p>
                            Ein Prozess, der zu 80 Prozent überall gleich ist und 20 Prozent
                            lokalen Spielraum lässt, wird angenommen. Einer, der 100 Prozent
                            vorgibt, wird formal bestätigt und informell umgangen. Der Unterschied
                            entscheidet darüber, ob nach zwei Jahren ein gemeinsamer Prozess
                            existiert oder nur ein gemeinsames Dokument.
                        </p>
                    </div>
                </div>

                <h2>Das Vorgehen, das sich bewährt hat</h2>
                <ol class="phase-list">
                    <li>
                        <span class="phase-week">Phase 1</span>
                        <h3>Ist-Aufnahme je Standort – ohne Bewertung</h3>
                        <p>
                            Wie arbeitet jeder Standort heute? Erhoben vor Ort oder per Video, mit
                            den Menschen, die es tun. Wichtig ist die Haltung: Es geht ums
                            Verstehen, nicht ums Beurteilen. Wer als Prüfer auftritt, bekommt
                            geschönte Antworten und verliert die Beteiligung.
                        </p>
                    </li>
                    <li>
                        <span class="phase-week">Phase 2</span>
                        <h3>Gemeinsamkeiten und Unterschiede sichtbar machen</h3>
                        <p>
                            Eine Gegenüberstellung, in der jeder seinen Standort wiederfindet.
                            Oft die produktivste Phase: Standorte entdecken, dass andere ein
                            Problem längst gelöst haben – und übernehmen es freiwillig.
                        </p>
                    </li>
                    <li>
                        <span class="phase-week">Phase 3</span>
                        <h3>Zielprozess gemeinsam schneiden</h3>
                        <p>
                            Mit Vertretern aller Standorte, nicht für sie. Strittige Punkte werden
                            entschieden – von einem benannten Gremium, mit Begründung, nicht durch
                            Aussitzen. Ausnahmen werden zugelassen, aber befristet und begründet.
                        </p>
                    </li>
                    <li>
                        <span class="phase-week">Phase 4</span>
                        <h3>Einführung standortweise</h3>
                        <p>
                            Nicht überall gleichzeitig. Ein Standort startet als Pilot, Erfahrungen
                            fließen zurück, danach folgen die übrigen. Der Pilot sollte nicht der
                            einfachste sein – ein Erfolg beim schwierigsten Standort überzeugt
                            alle anderen.
                        </p>
                    </li>
                    <li>
                        <span class="phase-week">Phase 5</span>
                        <h3>Nachhalten mit Zahlen</h3>
                        <p>
                            Wird der Prozess genutzt? Messbar an Ticketzahlen, Genehmigungen,
                            Umgehungen. Ohne diese Messung weiß niemand, ob die Harmonisierung
                            stattgefunden hat oder nur beschlossen wurde.
                        </p>
                    </li>
                </ol>

                <h2>Widerstand ernst nehmen</h2>
                <p>
                    In jedem Harmonisierungsprojekt gibt es Widerstand, und er ist meistens
                    berechtigt – zumindest teilweise. Drei Muster und wie man ihnen begegnet:
                </p>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Einwand</th><th scope="col">Dahinter steckt oft</th><th scope="col">Was hilft</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>„Bei uns geht das nicht.“</td>
                                <td>Eine echte lokale Besonderheit – oder die Sorge, Einfluss zu verlieren</td>
                                <td>Konkret nachfragen: Welcher Schritt genau, und warum? Meist bleiben ein bis zwei echte Punkte übrig.</td>
                            </tr>
                            <tr>
                                <td>„Wir haben das schon dreimal probiert.“</td>
                                <td>Erfahrung mit abgebrochenen Zentralprojekten</td>
                                <td>Ernst nehmen, Unterschiede zum letzten Versuch benennen, früh sichtbaren Nutzen liefern.</td>
                            </tr>
                            <tr>
                                <td>„Dafür haben wir keine Zeit.“</td>
                                <td>Stimmt meistens</td>
                                <td>Aufwand realistisch beziffern, Entlastung an anderer Stelle schaffen, Termine langfristig planen.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h2>Aus eigener Praxis</h2>
                <p>
                    Prozessharmonisierung über mehrere Gesellschaften und Vertriebsgesellschaften
                    hinweg gehört zu den Themen, mit denen ich im Konzernumfeld unmittelbar
                    gearbeitet habe – einschließlich der Einführung eines globalen
                    IT-Demand-Prozesses. Die wichtigste Erfahrung daraus: Der fachliche Entwurf
                    ist in wenigen Wochen fertig. Die restlichen Monate gehen für Beteiligung,
                    Übersetzung und Vertrauen drauf – und genau diese Monate entscheiden.
                </p>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['leistungen/it-operating-model.php', 'leistungen/demand-management.php', 'leistungen/it-prozess-assessment.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
