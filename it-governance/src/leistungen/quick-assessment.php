<?php
$page = [
    'title'       => 'IT-Governance Quick Assessment',
    'description' => 'Standortbestimmung der IT-Governance in zwei Wochen: sechs bis acht Interviews, Reifegradbewertung je Handlungsfeld, Bericht mit den fünf dringendsten Themen und Empfehlung zur Reihenfolge. Ab 4.900 € netto.',
    'section'     => 'leistungen',
    'path'        => 'leistungen/quick-assessment.php',
    'crumbs'      => [['Leistungen', 'leistungen/'], ['Quick Assessment', null]],
    'hero'        => [
        'kicker' => 'Leistung · Bestandsaufnahme',
        'h1'     => 'Zwei Wochen, und Sie wissen, <span class="accent">wo Sie stehen</span>',
        'lead'   => 'Der kleinste sinnvolle Einstieg. Kein Projekt, keine Vorleistung, kein Beratungsabo – eine kompakte Standortbestimmung mit einem Bericht, der auch ohne mich weiterverwendbar ist.',
        'actions' => [
            ['Quick Assessment anfragen', 'kontakt.php', 'primary'],
            ['Andere Leistungen', 'leistungen/', 'ghost'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Dauer', '2 Wochen'],
    ['Aufwand bei Ihnen', '4–6 Stunden'],
    ['Ergebnis', 'Bericht, 20–30 Seiten'],
    ['Preis', 'ab 4.900 € netto'],
];
$asideCta = [
    'title' => 'Direkt starten',
    'text'  => 'Das Quick Assessment lässt sich in der Regel innerhalb von drei bis vier Wochen einplanen.',
    'link'  => ['Termin anfragen', 'kontakt.php'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Wofür das Format gedacht ist</h2>
                <p>
                    Die meisten Anfragen beginnen mit demselben Satz: „Wir wissen, dass da etwas
                    zu tun ist – aber wir wissen nicht, wie groß es ist.“ Genau dafür ist das
                    Quick Assessment gemacht. Es beantwortet drei Fragen, und zwar so, dass die
                    Geschäftsführung damit entscheiden kann:
                </p>
                <ul class="checklist">
                    <li><strong>Wo steht die IT-Steuerung heute?</strong> Reifegrad je Handlungsfeld
                        auf einer fünfstufigen Skala, nachvollziehbar begründet.</li>
                    <li><strong>Was würde in einer Prüfung zuerst auffallen?</strong> Die drei bis
                        fünf Punkte, die ein Prüfer mit hoher Wahrscheinlichkeit als Feststellung
                        formulieren würde.</li>
                    <li><strong>Was ist zuerst zu tun – und was kostet das ungefähr?</strong>
                        Priorisierte Empfehlung mit grober Aufwandsschätzung.</li>
                </ul>

                <div class="callout">
                    <span class="callout-icon"><i data-icon="target" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Was es ausdrücklich nicht ist</h3>
                        <p>
                            Keine Zertifizierungsvorbereitung, kein vollständiger Soll-Ist-Vergleich
                            gegen eine Norm und keine belastbare Grundlage für eine Zertifizierung.
                            Dafür gibt es die <a href="/leistungen/gap-analyse.php">Gap-Analyse</a>.
                            Das Quick Assessment ist eine Standortbestimmung – bewusst schmal
                            geschnitten, damit sie schnell und bezahlbar bleibt.
                        </p>
                    </div>
                </div>

                <h2>Diese acht Handlungsfelder werden bewertet</h2>
                <p>
                    Der Rahmen ist an gängigen Ordnungssystemen ausgerichtet – COBIT-Logik für
                    die Steuerung, ISO/IEC 27001 für die Sicherheitsthemen, ITIL-Begriffe für die
                    Servicethemen –, aber auf Mittelstandsmaß eingekürzt. Bewertet wird immer der
                    tatsächliche Zustand, nicht das, was im Konzept steht.
                </p>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th scope="col">Handlungsfeld</th>
                                <th scope="col">Beispiel für eine Leitfrage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Steuerung &amp; Entscheidungswege</strong></td>
                                <td>Wer entscheidet über IT-Budget, Architektur und Ausnahmen – und wo ist das festgehalten?</td>
                            </tr>
                            <tr>
                                <td><strong>Rollen &amp; Verantwortlichkeiten</strong></td>
                                <td>Sind Rollen benannt, besetzt und vertreten? Was passiert bei Ausfall des Schlüsselträgers?</td>
                            </tr>
                            <tr>
                                <td><strong>IT-Prozesse</strong></td>
                                <td>Existieren Incident-, Change- und Demand-Prozess – und werden sie eingehalten?</td>
                            </tr>
                            <tr>
                                <td><strong>Kontrollen &amp; Nachweise</strong></td>
                                <td>Welche Kontrollen laufen regelmäßig, und wo entsteht dabei ein Beleg?</td>
                            </tr>
                            <tr>
                                <td><strong>Dokumentation</strong></td>
                                <td>Ist die Dokumentation auffindbar, aktuell und im Ernstfall benutzbar?</td>
                            </tr>
                            <tr>
                                <td><strong>Risiko &amp; Notfall</strong></td>
                                <td>Gibt es eine IT-Risikoliste, Wiederanlaufziele und einen getesteten Notfallplan?</td>
                            </tr>
                            <tr>
                                <td><strong>Dienstleister &amp; Auslagerung</strong></td>
                                <td>Wer steuert die Dienstleister, und wie wird deren Leistung nachgehalten?</td>
                            </tr>
                            <tr>
                                <td><strong>Kennzahlen &amp; Reporting</strong></td>
                                <td>Womit steuert die Geschäftsführung die IT – und woher kommen die Zahlen?</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h2>Ablauf in zwei Wochen</h2>
                <ol class="phase-list">
                    <li>
                        <span class="phase-week">Vorlauf · 3 Tage</span>
                        <h3>Unterlagen sichten</h3>
                        <p>
                            Sie stellen bereit, was vorhanden ist: Organigramm, vorhandene
                            Richtlinien, letzter Prüfbericht, Dienstleisterliste, Systemübersicht.
                            Es ist völlig in Ordnung, wenn davon wenig existiert – auch das ist
                            ein Ergebnis.
                        </p>
                    </li>
                    <li>
                        <span class="phase-week">Woche 1</span>
                        <h3>Sechs bis acht Interviews</h3>
                        <p>
                            Je 45 bis 60 Minuten: IT-Leitung, ein bis zwei Administratoren,
                            Geschäftsführung oder CFO, eine Fachbereichsleitung, bei Bedarf
                            Datenschutz, Einkauf oder ein wichtiger Dienstleister. Vertraulich,
                            ohne Protokollnamen im Bericht.
                        </p>
                    </li>
                    <li>
                        <span class="phase-week">Woche 2</span>
                        <h3>Bewertung und Bericht</h3>
                        <p>
                            Reifegrad je Handlungsfeld, Begründung, Priorisierung. Der Bericht
                            umfasst 20 bis 30 Seiten, davon eine Managementzusammenfassung auf
                            zwei Seiten, die auch ein Beirat versteht.
                        </p>
                    </li>
                    <li>
                        <span class="phase-week">Abschluss · 90 Minuten</span>
                        <h3>Ergebnisbesprechung</h3>
                        <p>
                            Vorstellung im Kreis der Entscheider, inklusive Diskussion der
                            Reihenfolge. Danach gehört das Ergebnis Ihnen – auch dann, wenn Sie
                            die Umsetzung mit jemand anderem oder allein angehen.
                        </p>
                    </li>
                </ol>

                <h2>Was Sie am Ende in der Hand haben</h2>
                <div class="card-grid cols-2">
                    <div class="card is-paper">
                        <h3 class="card-title">Bericht mit Reifegradprofil</h3>
                        <p class="card-text">
                            Acht Handlungsfelder, je Feld eine Stufe von 1 bis 5, Begründung und
                            Belegstellen. Als PDF und als bearbeitbare Datei.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Feststellungsliste</h3>
                        <p class="card-text">
                            Die Punkte, die in einer Prüfung mit hoher Wahrscheinlichkeit
                            beanstandet würden – in der Sprache, die Prüfer verwenden.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Maßnahmenempfehlung</h3>
                        <p class="card-text">
                            Fünf bis zehn Maßnahmen mit Wirkung, grobem Aufwand und Reihenfolge.
                            Inklusive der Maßnahmen, die Sie ohne Beratung umsetzen können.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Managementzusammenfassung</h3>
                        <p class="card-text">
                            Zwei Seiten für Geschäftsführung, Beirat oder Gesellschafter –
                            ohne IT-Jargon, mit klarer Aussage zum Handlungsbedarf.
                        </p>
                    </div>
                </div>

                <h2>Preis</h2>
                <p>
                    <strong>4.900 € netto</strong> für Unternehmen bis etwa 500 Mitarbeitende und
                    einen Standort. <strong>6.400 € netto</strong> bei mehreren Standorten oder
                    Gesellschaften, weil dann mehr Interviews und ein Vergleich der Arbeitsweisen
                    dazukommen. Reisekosten nach Aufwand, Videotermine sind möglich und üblich.
                </p>
                <p>
                    Beauftragen Sie innerhalb von drei Monaten nach dem Quick Assessment eine
                    weiterführende Leistung, wird das Honorar vollständig angerechnet. Das ist
                    kein Lockangebot: Ich habe die Interviews dann bereits geführt und der Aufwand
                    fällt nicht zweimal an.
                </p>

                <h2>Häufige Fragen zu dieser Leistung</h2>
                <div class="faq-list">
                    <div class="faq-item">
                        <button class="faq-question" type="button" aria-expanded="false">
                            <span class="faq-number">01</span>
                            <span class="faq-question-text">Reicht das für unseren Wirtschaftsprüfer?</span>
                        </button>
                        <div class="faq-answer" aria-hidden="true">
                            <p>
                                Nein, und das soll es auch nicht. Ein Quick Assessment ist eine
                                interne Standortbestimmung, kein Prüfungsersatz und keine
                                Zertifizierungsgrundlage. Es hilft Ihnen, sich vorzubereiten und zu
                                entscheiden, wo Sie investieren – die Prüfung selbst führt weiterhin
                                Ihr Prüfer durch.
                            </p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" type="button" aria-expanded="false">
                            <span class="faq-number">02</span>
                            <span class="faq-question-text">Müssen wir vorher aufräumen?</span>
                        </button>
                        <div class="faq-answer" aria-hidden="true">
                            <p>
                                Bitte nicht. Ein geschöntes Bild führt zu einem Bericht, der nichts
                                wert ist. Ich sehe Unternehmen ohne einzige Richtlinie und
                                Unternehmen mit 90 Dokumenten, von denen 80 veraltet sind – beides
                                ist normal und beides lässt sich einordnen.
                            </p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" type="button" aria-expanded="false">
                            <span class="faq-number">03</span>
                            <span class="faq-question-text">Wer sollte an den Interviews teilnehmen?</span>
                        </button>
                        <div class="faq-answer" aria-hidden="true">
                            <p>
                                Wichtig sind IT-Leitung und mindestens eine Person aus dem Betrieb,
                                die täglich in den Systemen arbeitet – dort liegt die Wahrheit über
                                gelebte Prozesse. Ebenso wichtig ist ein Termin mit
                                Geschäftsführung oder CFO: Governance ohne Rückhalt oben ist
                                verlorene Zeit.
                            </p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" type="button" aria-expanded="false">
                            <span class="faq-number">04</span>
                            <span class="faq-question-text">Was passiert mit den Interviewinhalten?</span>
                        </button>
                        <div class="faq-answer" aria-hidden="true">
                            <p>
                                Aussagen werden nicht namentlich zugeordnet. Das ist kein
                                Höflichkeitsversprechen, sondern die Voraussetzung dafür, dass mir
                                jemand erzählt, wie es wirklich läuft. Eine
                                Vertraulichkeitsvereinbarung unterschreibe ich selbstverständlich
                                vor dem ersten Termin.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['leistungen/gap-analyse.php', 'leistungen/audit-readiness.php', 'wissen/reifegradmodell.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
