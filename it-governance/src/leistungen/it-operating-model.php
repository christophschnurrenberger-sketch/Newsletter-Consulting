<?php
$page = [
    'title'       => 'IT Operating Model entwickeln',
    'description' => 'Zielbild für die IT-Organisation: Aufgabenverteilung zwischen Zentrale, Standorten und Dienstleistern, Steuerungsmodell, Stellenbedarf und Übergangsplan. Für gewachsene IT-Bereiche mit mehreren Gesellschaften. Ab 32.000 € netto.',
    'section'     => 'leistungen',
    'path'        => 'leistungen/it-operating-model.php',
    'crumbs'      => [['Leistungen', 'leistungen/'], ['IT Operating Model', null]],
    'hero'        => [
        'kicker' => 'Leistung · Aufbau',
        'h1'     => 'Wie die IT künftig <span class="accent">arbeiten soll</span> – und wer was macht',
        'lead'   => 'Die größte der elf Leistungen und die mit dem längsten Nachhall. Ein Operating Model beantwortet, welche Aufgaben zentral, welche lokal und welche extern erbracht werden, wie gesteuert wird und welche Stellen dafür nötig sind.',
        'actions' => [
            ['Operating Model anfragen', 'kontakt.php', 'primary'],
            ['Prozessharmonisierung ansehen', 'themen/prozessharmonisierung.php', 'ghost'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Dauer', '3–5 Monate'],
    ['Aufwand bei Ihnen', '6–10 Std./Woche'],
    ['Ergebnis', 'Zielbild + Übergangsplan'],
    ['Preis', 'ab 32.000 € netto'],
];
$asideCta = [
    'title' => 'Erst kleiner anfangen?',
    'text'  => 'Wenn die Ausgangslage unklar ist, empfehle ich vorab eine Gap-Analyse oder ein Prozess-Assessment – das schützt vor einem Zielbild auf falscher Grundlage.',
    'link'  => ['Beratung anfragen', 'kontakt.php'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Wann ein Operating Model gebraucht wird</h2>
                <ul class="checklist">
                    <li>Mehrere Standorte oder Gesellschaften arbeiten unterschiedlich, ohne dass jemand entschieden hätte, dass das so sein soll.</li>
                    <li>Eine Zukauf- oder Fusionssituation hat zwei IT-Bereiche nebeneinandergestellt.</li>
                    <li>Die IT wächst, aber niemand kann sagen, welche Stellen als nächstes gebraucht werden.</li>
                    <li>Der Auslagerungsgrad ist gewachsen, ohne dass die Steuerungsfähigkeit mitgewachsen wäre.</li>
                    <li>Ein Wechsel in der IT-Leitung steht an oder hat gerade stattgefunden.</li>
                    <li>Die Geschäftsführung stellt die Frage: „Ist unsere IT eigentlich richtig aufgestellt?“</li>
                </ul>

                <h2>Die sechs Bausteine</h2>
                <ol class="steps">
                    <li>
                        <h3>Leistungsbild</h3>
                        <p>Welche Leistungen erbringt die IT überhaupt – für wen, in welcher
                           Qualität, mit welchem Aufwand? Grundlage ist der Servicekatalog; ohne
                           ihn beginnt das Projekt mit seiner Erstellung.</p>
                    </li>
                    <li>
                        <h3>Verteilung: zentral, lokal, extern</h3>
                        <p>Je Leistung wird entschieden, wo sie erbracht wird. Die Entscheidung
                           folgt Kriterien – Nähe zum Geschäft, Skaleneffekt, Risiko,
                           Verfügbarkeit von Fachkräften –, nicht Gewohnheiten.</p>
                    </li>
                    <li>
                        <h3>Steuerungsmodell</h3>
                        <p>Wie steuert die Zentrale die Standorte, wie werden Dienstleister
                           geführt, welche Entscheidungen bleiben lokal? Hier entsteht die
                           Verbindung zum <a href="/leistungen/governance-framework.php">Governance-Framework</a>.</p>
                    </li>
                    <li>
                        <h3>Rollen und Stellenbedarf</h3>
                        <p>Welche Rollen braucht das Zielbild, wie viele Personen, mit welchem
                           Profil? Inklusive ehrlicher Aussage dazu, welche Rollen am Arbeitsmarkt
                           schwer zu besetzen sind und welche Alternativen es gibt.</p>
                    </li>
                    <li>
                        <h3>Kosten und Verrechnung</h3>
                        <p>Was kostet das Modell, wie werden Kosten den Gesellschaften zugeordnet,
                           welche Investitionen fallen einmalig an? Kein Controlling-Ersatz,
                           sondern eine belastbare Größenordnung für die Entscheidung.</p>
                    </li>
                    <li>
                        <h3>Übergangsplan</h3>
                        <p>Der Teil, der über Erfolg entscheidet: Was passiert in welcher
                           Reihenfolge, was ist Voraussetzung wofür, wo entstehen Risiken für den
                           laufenden Betrieb, was wird bewusst nicht angefasst.</p>
                    </li>
                </ol>

                <h2>Drei Modelle, zwischen denen entschieden wird</h2>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th scope="col">Modell</th>
                                <th scope="col">Passt, wenn</th>
                                <th scope="col">Preis dafür</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Zentral</strong><br><small>Alles aus einer Hand, Standorte als Nutzer</small></td>
                                <td>Standardisierte Geschäftsprozesse, ähnliche Anforderungen, Kostendruck</td>
                                <td>Weniger Nähe zum Geschäft, Widerstand in den Standorten</td>
                            </tr>
                            <tr>
                                <td><strong>Föderal</strong><br><small>Zentrale Vorgaben, lokale Umsetzung</small></td>
                                <td>Unterschiedliche Geschäftsmodelle, gewachsene Standorte, internationale Struktur</td>
                                <td>Höherer Abstimmungsaufwand, klare Regeln zwingend erforderlich</td>
                            </tr>
                            <tr>
                                <td><strong>Retained IT</strong><br><small>Kleine steuernde IT, Leistung extern</small></td>
                                <td>Schwierige Personalgewinnung, standardisierbare Leistungen, hoher Kostendruck</td>
                                <td>Steuerungsfähigkeit muss aufgebaut werden – sonst steuert der Dienstleister Sie</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout is-warning">
                    <span class="callout-icon"><i data-icon="alert-triangle" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Die teuerste Variante ist die unentschiedene</h3>
                        <p>
                            Am häufigsten finde ich ein viertes Modell vor: Es wurde nie
                            entschieden. Standorte machen, was sie für richtig halten, die
                            Zentrale macht Vorgaben, die niemand durchsetzt, Dienstleister füllen
                            die Lücken. Dieses Modell hat alle Nachteile der drei anderen und
                            keinen ihrer Vorteile.
                        </p>
                    </div>
                </div>

                <h2>Was das Projekt liefert</h2>
                <ul class="checklist">
                    <li><strong>Zielbild</strong> der IT-Organisation mit Aufgabenverteilung, Rollen und Schnittstellen</li>
                    <li><strong>Entscheidungsvorlage</strong> mit zwei bis drei Varianten, Kosten, Risiken und Empfehlung</li>
                    <li><strong>Stellenplan</strong> für das Zielbild inklusive Priorisierung offener Besetzungen</li>
                    <li><strong>Übergangsplan</strong> über 12 bis 24 Monate mit Meilensteinen und Verantwortlichen</li>
                    <li><strong>Kommunikationspaket</strong> für Führungskräfte und Mitarbeitende – ein unterschätzter Erfolgsfaktor</li>
                    <li><strong>Begleitung</strong> der ersten Umsetzungsphase auf Wunsch über die
                        <a href="/leistungen/governance-betreuung.php">laufende Betreuung</a></li>
                </ul>

                <h2>Preis</h2>
                <p>
                    <strong>Ab 32.000 € netto.</strong> Der tatsächliche Preis hängt an der Zahl
                    der Gesellschaften, an der Verfügbarkeit von Ausgangsdaten und daran, wie
                    viele Beteiligte einbezogen werden müssen. Ein Modell für drei deutsche
                    Standorte liegt typischerweise bei 32.000 bis 45.000 €, ein internationales
                    Modell mit mehreren Ländern und Sprachen deutlich darüber. Der Preis wird nach
                    einem kostenlosen Zuschnittsgespräch verbindlich angeboten – Schätzungen ins
                    Blaue gibt es hier nicht.
                </p>

                <div class="callout is-legal">
                    <span class="callout-icon"><i data-icon="scale" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Personelle Veränderungen</h3>
                        <p>
                            Ein Operating Model kann Auswirkungen auf Stellen und Aufgaben haben.
                            Arbeitsrechtliche Fragen – Versetzung, Betriebsübergang,
                            Mitbestimmung, Interessenausgleich – gehören zu Ihrer Rechtsabteilung
                            oder einer Kanzlei. Ich liefere die fachliche Grundlage und die
                            Argumentation, keine arbeitsrechtliche Bewertung.
                        </p>
                    </div>
                </div>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['themen/prozessharmonisierung.php', 'leistungen/governance-framework.php', 'themen/dienstleistermanagement.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
