<?php
$page = [
    'title'       => 'Audit Readiness Assessment',
    'description' => 'Die Prüfung vor der Prüfung: Nachweislage prüfen, Stichproben ziehen, Interviewsituation trainieren, Feststellungen vorwegnehmen. Vorbereitung auf Wirtschaftsprüfung, Kundenaudit, Konzernrevision oder Zertifizierungsaudit. 16.000 bis 29.000 € netto.',
    'section'     => 'leistungen',
    'path'        => 'leistungen/audit-readiness.php',
    'crumbs'      => [['Leistungen', 'leistungen/'], ['Audit Readiness', null]],
    'hero'        => [
        'kicker' => 'Leistung · Prüfungsvorbereitung',
        'h1'     => 'Erfahren Sie vorher, was <span class="accent">im Bericht stehen würde</span>',
        'lead'   => 'Eine Prüfung verläuft selten schlecht, weil die IT schlecht arbeitet. Sie verläuft schlecht, weil niemand die Nachweise findet, weil zwei Personen dieselbe Frage verschieden beantworten und weil eine Stichprobe eine Lücke trifft, die man kannte.',
        'actions' => [
            ['Audit Readiness anfragen', 'kontakt.php', 'primary'],
            ['12-Wochen-Leitfaden lesen', 'wissen/audit-vorbereitung.php', 'ghost'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Dauer', '4–6 Wochen'],
    ['Aufwand bei Ihnen', '1–2 Tage gesamt'],
    ['Ergebnis', 'Befundbericht + Sofortliste'],
    ['Preis', '16.000 – 29.000 € netto'],
];
$asideCta = [
    'title' => 'Termin steht schon fest?',
    'text'  => 'Sinnvoll sind acht bis zwölf Wochen Vorlauf. Auch vier Wochen sind noch machbar – dann mit engerem Zuschnitt.',
    'link'  => ['Verfügbarkeit anfragen', 'kontakt.php'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Für welche Prüfungen dieses Format gedacht ist</h2>
                <div class="card-grid cols-2">
                    <div class="card is-paper">
                        <h3 class="card-title">Jahresabschlussprüfung</h3>
                        <p class="card-text">
                            Der Wirtschaftsprüfer sieht sich die IT-gestützte Rechnungslegung an:
                            Zugriffsrechte auf das ERP, Änderungsverfahren, Betriebssicherheit,
                            Auslagerungen. Feststellungen landen im Management Letter.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Kunden- und Lieferantenaudit</h3>
                        <p class="card-text">
                            Ein Großkunde prüft, ob Sie als Lieferant tragbar sind – zunehmend
                            mit Fragenkatalogen, die aus NIS2- und ISO-Anforderungen abgeleitet
                            sind. Das Ergebnis entscheidet über Aufträge.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Konzernrevision</h3>
                        <p class="card-text">
                            Die Muttergesellschaft prüft die Tochter gegen Konzernvorgaben.
                            Typisch: enge Fristen, wenig Verhandlungsspielraum, hohe Sichtbarkeit
                            bis in die Geschäftsführung.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Zertifizierungsaudit</h3>
                        <p class="card-text">
                            Stufe 1 und Stufe 2 einer ISO/IEC-27001-Zertifizierung. Hier zählt,
                            ob das Managementsystem gelebt wird – Dokumente allein reichen der
                            Zertifizierungsstelle nicht.
                        </p>
                    </div>
                </div>

                <h2>Was ich mache – und warum es unangenehm ist</h2>
                <p>
                    Ich nehme die Rolle des Prüfers ein. Das heißt: Ich glaube nicht, was mir
                    gesagt wird, sondern lasse es mir zeigen. Für die Beteiligten fühlt sich das
                    zunächst unangenehm an. Genau darin liegt der Wert – die Alternative ist,
                    dass sich derselbe Moment in der echten Prüfung ereignet, dann aber mit
                    Protokoll.
                </p>

                <ol class="phase-list">
                    <li>
                        <span class="phase-week">Woche 1</span>
                        <h3>Prüfungsrahmen rekonstruieren</h3>
                        <p>
                            Was genau wird geprüft? Prüfungsankündigung, Fragenkatalog,
                            Vorjahresbericht, offene Feststellungen. Daraus entsteht die Liste der
                            Nachweise, die tatsächlich angefordert werden – nicht die, die man
                            vermutet.
                        </p>
                    </li>
                    <li>
                        <span class="phase-week">Woche 2</span>
                        <h3>Nachweisprobe</h3>
                        <p>
                            Ich fordere 20 bis 40 Nachweise an, so wie ein Prüfer es täte, mit
                            Frist. Gemessen wird zweierlei: ob der Nachweis existiert – und wie
                            lange es dauert, ihn zu beschaffen. Beides fließt in den Befund ein.
                        </p>
                    </li>
                    <li>
                        <span class="phase-week">Woche 3</span>
                        <h3>Stichproben und Kontrolltests</h3>
                        <p>
                            Änderungen, Berechtigungsvergaben, Notfalltests, Dienstleisterprüfungen,
                            Sicherungsprotokolle. Stichprobenumfang je nach Grundgesamtheit, mit
                            Dokumentation des Vorgehens – so, wie ein Prüfer es nachvollziehen muss.
                        </p>
                    </li>
                    <li>
                        <span class="phase-week">Woche 4</span>
                        <h3>Interviewtraining</h3>
                        <p>
                            Zwei bis drei Stunden Rollenspiel mit den Personen, die in der Prüfung
                            sprechen. Die häufigsten Fehler: zu viel erzählen, Vermutungen als
                            Tatsachen darstellen, Zuständigkeiten falsch zuordnen. Alle drei lassen
                            sich in einer Sitzung abstellen.
                        </p>
                    </li>
                    <li>
                        <span class="phase-week">Woche 5–6</span>
                        <h3>Befundbericht und Sofortmaßnahmen</h3>
                        <p>
                            Jeder Befund im Format einer echten Feststellung: Sachverhalt,
                            Anforderung, Risiko, Empfehlung. Dazu die Sofortliste – was in der
                            verbleibenden Zeit noch tatsächlich zu schaffen ist.
                        </p>
                    </li>
                </ol>

                <h2>Die fünf häufigsten Befunde</h2>
                <p>
                    Aus der Erfahrung mit Prüfungsvorbereitungen wiederholen sich dieselben
                    Muster – unabhängig von Branche und Größe:
                </p>
                <ol class="steps">
                    <li>
                        <h3>Der Nachweis existiert, aber niemand findet ihn</h3>
                        <p>Sicherungen laufen, Tests wurden gemacht, Freigaben erteilt – aber die
                           Belege liegen in drei Postfächern, einem Ticketsystem und einem
                           Laufwerk. Aus Prüfersicht ist das ein fehlender Nachweis.</p>
                    </li>
                    <li>
                        <h3>Berechtigungen wachsen mit der Karriere</h3>
                        <p>Wer die Abteilung wechselt, behält die alten Rechte. Nach fünf Jahren
                           haben einzelne Mitarbeitende Zugriff auf alles. Fast immer ein Befund,
                           und einer mit schneller Lösung.</p>
                    </li>
                    <li>
                        <h3>Änderungen am Produktivsystem ohne Spur</h3>
                        <p>„Das war ein Kleinstchange“ ist keine Kategorie, die ein Prüfer kennt.
                           Wenn kein Freigabevermerk existiert, gilt die Änderung als nicht
                           kontrolliert.</p>
                    </li>
                    <li>
                        <h3>Dienstleister ohne Steuerung</h3>
                        <p>Verträge vorhanden, Leistung wird erbracht – aber niemand prüft, ob
                           vereinbarte Sicherheitsanforderungen eingehalten werden. Auslagerung
                           entbindet nicht von der Verantwortung.</p>
                    </li>
                    <li>
                        <h3>Der Notfallplan wurde nie getestet</h3>
                        <p>Ein ungetesteter Wiederanlaufplan ist ein Dokument, keine Fähigkeit.
                           Prüfer fragen inzwischen regelmäßig nach dem Testprotokoll, nicht nach
                           dem Plan.</p>
                    </li>
                </ol>

                <div class="callout is-ok">
                    <span class="callout-icon"><i data-icon="check-circle" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Ehrliche Erwartung</h3>
                        <p>
                            Audit Readiness verhindert keine Feststellungen. Es verhindert
                            <em>überraschende</em> Feststellungen und sorgt dafür, dass Sie zu
                            jedem bekannten Punkt eine Position haben: erkannt, bewertet,
                            Maßnahme terminiert. Prüfer bewerten das deutlich milder als eine
                            Lücke, die im Gespräch zum ersten Mal auftaucht.
                        </p>
                    </div>
                </div>

                <h2>Umfang und Preis</h2>
                <ul class="checklist">
                    <li><strong>16.000 €</strong> – ein Standort, eine Prüfung, klarer Fragenkatalog</li>
                    <li><strong>21.000 €</strong> – Regelfall: mehrere Systeme, Vorjahresfeststellungen, Interviewtraining für vier bis sechs Personen</li>
                    <li><strong>29.000 €</strong> – mehrere Gesellschaften, Zertifizierungsaudit Stufe 1 und 2, Begleitung während der Prüfungstage</li>
                </ul>
                <p>
                    Begleitung während der eigentlichen Prüfung ist zubuchbar
                    (1.600 € netto je Tag). Sinnvoll ist sie vor allem beim ersten Mal – als
                    Übersetzer zwischen Prüfersprache und Ihrer Organisation.
                </p>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['wissen/audit-vorbereitung.php', 'leistungen/kontrollframework.php', 'wissen/kontrollen-nachweise.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
