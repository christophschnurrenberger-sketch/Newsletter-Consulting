<?php
$page = [
    'title'       => 'NIS2-Betroffenheit in sechs Schritten prüfen',
    'description' => 'Strukturierter Weg zur Einschätzung der NIS2-Betroffenheit: Tätigkeit und Sektor bestimmen, Größenschwellen prüfen, Konzernstrukturen berücksichtigen, Lieferkettenwirkung einschätzen – und die Stelle erkennen, an der die Kanzlei übernimmt.',
    'section'     => 'wissen',
    'path'        => 'wissen/nis2-betroffenheit.php',
    'crumbs'      => [['Wissen', 'wissen/'], ['NIS2-Betroffenheit prüfen', null]],
    'hero'        => [
        'kicker' => 'Leitfaden · Vorarbeit',
        'h1'     => 'NIS2-Betroffenheit prüfen – <span class="accent">bis zur Grenze der Rechtsberatung</span>',
        'lead'   => 'Dieser Leitfaden bringt Sie zu einer strukturierten Vorarbeit, mit der ein Gespräch mit der Kanzlei eine Stunde statt einen Tag dauert. Die verbindliche Antwort kommt am Ende von dort – aber die Fleißarbeit können Sie selbst erledigen.',
    ],
];
include __DIR__ . '/../partials/header.php';

$asideCta = [
    'title' => 'Danach der nächste Schritt',
    'text'  => 'Unabhängig vom Ergebnis: Eine Gap-Analyse gegen die zehn Maßnahmenbereiche zeigt, wo Sie stehen.',
    'link'  => ['Gap-Analyse ansehen', 'leistungen/gap-analyse.php'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

<?php include __DIR__ . '/../partials/rechtshinweis.php'; ?>

                <h2>Warum die Vorarbeit sich lohnt</h2>
                <p>
                    Kanzleien rechnen nach Stunden. Wenn Sie mit der Frage „Sind wir betroffen?“
                    ins Gespräch gehen, beginnt die Kanzlei mit genau der Bestandsaufnahme, die
                    Sie selbst deutlich schneller machen können: Tätigkeiten, Kennzahlen,
                    Beteiligungsstruktur, Kundenkreis. Wer diese Unterlagen mitbringt, bekommt
                    eine Einschätzung statt einer Rechnung für Datensammlung.
                </p>

                <h2>Schritt 1: Tätigkeiten sauber beschreiben</h2>
                <p>
                    Nicht die Selbstbeschreibung aus dem Marketing, sondern die tatsächlichen
                    Tätigkeiten – gegliedert nach Umsatzanteilen. Nützliche Quellen: Handelsregister
                    und Gesellschaftsvertrag, Umsatzstatistik nach Geschäftsfeldern,
                    Produktkatalog, Website. Häufig zeigt sich dabei, dass ein Unternehmen mehr
                    Tätigkeiten hat, als in der Selbstwahrnehmung vorkommen.
                </p>
                <p>
                    Beispiel: Ein Maschinenbauer, der zusätzlich Fernwartung und Betrieb von
                    Steuerungssoftware für seine Kunden anbietet, hat einen zweiten
                    Tätigkeitsbereich – der möglicherweise anders zu bewerten ist als das
                    Kerngeschäft.
                </p>

                <h2>Schritt 2: Sektorzuordnung vorbereiten</h2>
                <p>
                    Die Richtlinie führt in ihren Anhängen Sektoren und Teilsektoren auf. Ordnen
                    Sie jede Tätigkeit einem Sektor zu – und schreiben Sie dazu, warum. Bei
                    Unsicherheit beide möglichen Zuordnungen notieren. Das ist genau die
                    Information, die eine Kanzlei benötigt.
                </p>
                <div class="table-wrap">
                    <table class="data-table">
                        <caption>Sektoren, in denen mittelständische Unternehmen häufig unerwartet
                            auftauchen. Die Liste ist eine Gedächtnisstütze, keine abschließende
                            Aufzählung.</caption>
                        <thead>
                            <tr><th scope="col">Sektor</th><th scope="col">Typischer Fall im Mittelstand</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Verarbeitendes Gewerbe (Maschinenbau, Elektronik, Fahrzeugbau, Medizinprodukte)</td><td>klassischer Industriemittelstand</td></tr>
                            <tr><td>IKT-Dienstleistungsmanagement</td><td>Systemhäuser, Managed-Service-Anbieter</td></tr>
                            <tr><td>Digitale Infrastruktur</td><td>Rechenzentren, Netzbetreiber, Anbieter von Cloud-Diensten</td></tr>
                            <tr><td>Post- und Kurierdienste</td><td>Logistikdienstleister mit eigenem Zustellnetz</td></tr>
                            <tr><td>Abfallbewirtschaftung</td><td>Entsorger, Recyclingbetriebe</td></tr>
                            <tr><td>Lebensmittel (Produktion, Verarbeitung, Vertrieb)</td><td>Hersteller, Großhandel</td></tr>
                            <tr><td>Chemie</td><td>Herstellung, Handel, Vertrieb</td></tr>
                            <tr><td>Gesundheit</td><td>Kliniken, Labore, Hersteller bestimmter Erzeugnisse</td></tr>
                            <tr><td>Trinkwasser und Abwasser</td><td>kommunale und private Versorger</td></tr>
                        </tbody>
                    </table>
                </div>

                <h2>Schritt 3: Größenschwellen ermitteln</h2>
                <p>
                    Zusammenstellen für die letzten zwei Geschäftsjahre: Zahl der Beschäftigten
                    (nach der einschlägigen Zählweise, die auch Teilzeit und Leiharbeit
                    berücksichtigt), Jahresumsatz und Bilanzsumme. Wichtig ist die Frage der
                    Betrachtungseinheit: einzelne Gesellschaft oder Unternehmensverbund?
                </p>
                <div class="callout is-warning">
                    <span class="callout-icon"><i data-icon="alert-triangle" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Der Stolperstein Konzernstruktur</h3>
                        <p>
                            Bei verbundenen Unternehmen sind Werte unter Umständen
                            zusammenzurechnen. Eine Gesellschaft mit 45 Mitarbeitenden kann damit
                            über der Schwelle liegen, wenn sie Teil einer größeren Struktur ist.
                            Genau diese Zusammenrechnung ist eine Rechtsfrage – bereiten Sie das
                            Beteiligungsdiagramm vor und lassen Sie die Bewertung machen.
                        </p>
                    </div>
                </div>

                <h2>Schritt 4: Sonderfälle prüfen</h2>
                <p>
                    Es gibt Konstellationen, in denen die Größenschwelle keine Rolle spielt – etwa
                    bei bestimmten Anbietern, deren Ausfall erhebliche Auswirkungen hätte, oder
                    bei Einrichtungen, die von einer Behörde ausdrücklich benannt werden. Notieren
                    Sie deshalb: Sind Sie einziger Anbieter einer Leistung in einer Region?
                    Beliefern Sie kritische Einrichtungen? Gab es behördliche Anschreiben?
                </p>

                <h2>Schritt 5: Lieferkettenwirkung einschätzen</h2>
                <p>
                    Dieser Schritt ist unabhängig vom Ergebnis der Schritte 1 bis 4 relevant –
                    und für viele mittelständische Unternehmen der wirtschaftlich wichtigere:
                </p>
                <ul class="checklist">
                    <li>Welche Ihrer Kunden sind mit hoher Wahrscheinlichkeit selbst betroffen?</li>
                    <li>Wie hoch ist deren Umsatzanteil an Ihrem Geschäft?</li>
                    <li>Haben Sie bereits Sicherheitsfragebögen oder geänderte Vertragsentwürfe erhalten?</li>
                    <li>Wie viel Zeit hätten Sie, wenn morgen ein Fragebogen mit 60 Positionen käme?</li>
                </ul>
                <p>
                    Wenn zwei oder mehr dieser Punkte unangenehm sind, ist der Handlungsbedarf
                    unabhängig von der rechtlichen Betroffenheit gegeben – und zwar aus
                    wirtschaftlichen Gründen.
                </p>

                <h2>Schritt 6: Das Ergebnis dokumentieren</h2>
                <p>
                    Fassen Sie die Schritte 1 bis 5 auf zwei bis drei Seiten zusammen – Tätigkeiten,
                    Sektorzuordnung mit Begründung, Kennzahlen, Struktur, Kundenkreis, offene
                    Fragen. Dieses Dokument hat drei Funktionen:
                </p>
                <ol class="steps">
                    <li>
                        <h3>Grundlage für die Kanzlei</h3>
                        <p>Sie kaufen eine Bewertung statt einer Bestandsaufnahme – das ist der
                           deutlich günstigere Teil.</p>
                    </li>
                    <li>
                        <h3>Nachweis der Auseinandersetzung</h3>
                        <p>Selbst wenn das Ergebnis „nicht betroffen“ lautet: Die dokumentierte
                           Prüfung ist ein Beleg dafür, dass die Geschäftsleitung sich mit der
                           Frage befasst hat.</p>
                    </li>
                    <li>
                        <h3>Ausgangspunkt für die Umsetzung</h3>
                        <p>Bei bestätigter Betroffenheit beginnt die Arbeit an den zehn
                           Maßnahmenbereichen – dann mit klarem Geltungsbereich.</p>
                    </li>
                </ol>

                <div class="callout is-ok">
                    <span class="callout-icon"><i data-icon="check-circle" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Und wenn Sie nicht betroffen sind?</h3>
                        <p>
                            Dann haben Sie eine belastbare Aussage für Kunden, Versicherer und
                            Gesellschafter – und trotzdem einen guten Grund, die Grundlagen zu
                            ordnen. Denn die Anforderungen kommen dann eben über den Vertrag statt
                            über das Gesetz, und der Aufwand ist derselbe. Die Prüfung war also
                            nicht umsonst, sie hat nur den Absender geändert.
                        </p>
                    </div>
                </div>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['themen/nis2.php', 'leistungen/gap-analyse.php', 'themen/dienstleistermanagement.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
