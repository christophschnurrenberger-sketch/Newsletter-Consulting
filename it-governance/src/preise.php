<?php
$page = [
    'title'       => 'Preise und Investitionsrahmen',
    'description' => 'Alle Preise offen: Quick Assessment ab 4.900 €, Gap-Analyse 14.500 bis 26.000 €, Audit Readiness 16.000 bis 29.000 €, Aufbauprojekte bis 45.000 €, laufende Betreuung ab 2.800 € monatlich. Mit Erklärung, was den Preis bestimmt.',
    'section'     => 'preise',
    'path'        => 'preise.php',
    'crumbs'      => [['Preise', null]],
    'hero'        => [
        'kicker' => 'Preise',
        'h1'     => 'Preise stehen hier. <span class="accent">Nicht erst im dritten Gespräch.</span>',
        'lead'   => 'Beratungsangebote ohne Preisangabe kosten beide Seiten Zeit. Deshalb finden Sie hier alle Rahmen – netto, zzgl. Umsatzsteuer, mit Erklärung, wovon die Spanne abhängt.',
        'actions' => [
            ['Angebot anfragen', 'kontakt.php', 'primary'],
            ['Leistungen ansehen', 'leistungen/', 'ghost'],
        ],
        'facts'  => [
            ['0 €', 'Erstgespräch'],
            ['Fest', 'Preis vor Beauftragung'],
        ],
    ],
];
include __DIR__ . '/partials/header.php';
?>

<!-- Grundsatz ------------------------------------------------------------ -->
<section class="section">
    <div class="container">
        <div class="split is-wide-left is-top">
            <div>
                <span class="section-kicker">Grundsatz</span>
                <h2 class="section-title">Festpreis statt Tagessatz</h2>
                <p class="lead">
                    Ein Tagessatz belohnt Langsamkeit. Ein Festpreis belohnt Ergebnisse. Deshalb
                    hat jede Leistung einen Preis, der vor der Beauftragung feststeht – ermittelt
                    nach einem kostenlosen Zuschnittsgespräch, in dem der Umfang geklärt wird.
                </p>
                <p>
                    Was das für Sie bedeutet: Wenn ich mich beim Aufwand verschätze, ist das mein
                    Risiko, nicht Ihres. Wenn Sie den Umfang erweitern wollen, bekommen Sie
                    vorher eine eigene Zahl – schriftlich, nicht als Nachtrag auf der Rechnung.
                </p>
                <ul class="checklist">
                    <li>Erstgespräch und Zuschnittsgespräch sind kostenlos</li>
                    <li>Angebot mit Umfang, Ergebnissen, Terminen und Mitwirkungspflichten</li>
                    <li>Keine Nachforderung ohne vorherige schriftliche Vereinbarung</li>
                    <li>Reisekosten nach tatsächlichem Aufwand, Videotermine sind der Regelfall</li>
                    <li>Zahlung üblicherweise 40 % bei Beauftragung, 60 % nach Abnahme; bei
                        Projekten über drei Monaten in Monatsraten</li>
                </ul>
            </div>
            <div>
                <div class="card is-navy">
                    <h3 class="card-title">Zusatzleistungen nach Aufwand</h3>
                    <p class="card-text">
                        Es gibt Fälle, in denen ein Festpreis nicht sinnvoll ist – etwa
                        Begleitung während einer laufenden Prüfung oder kurzfristige
                        Unterstützung bei einem Vorfall.
                    </p>
                    <p style="font-family:var(--serif); font-size:2rem; color:#fff; margin:.6rem 0 .2rem;">1.600 €</p>
                    <p style="font-size:.88rem; margin-bottom:1rem;">je Beratungstag, netto</p>
                    <ul class="checklist is-dark is-tight">
                        <li>Auditbegleitung vor Ort</li>
                        <li>Moderation einzelner Workshops</li>
                        <li>Zweite Meinung zu einer Entscheidung</li>
                        <li>Beantwortung umfangreicher Kundenfragebögen</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Preisübersicht ------------------------------------------------------- -->
<section class="section section-paper section-line">
    <div class="container">
        <div class="section-head is-wide">
            <span class="section-kicker">Übersicht</span>
            <h2 class="section-title">Alle Leistungen mit Preisrahmen</h2>
            <p class="section-lead">
                Angaben netto, zzgl. Umsatzsteuer. Die Spannen gelten für Unternehmen mit 150 bis
                5.000 Mitarbeitenden; der untere Wert steht für einen Standort mit vorhandener
                Grundstruktur, der obere für mehrere Gesellschaften ohne Vorarbeit.
            </p>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">Leistung</th>
                        <th scope="col">Dauer</th>
                        <th scope="col">Aufwand bei Ihnen</th>
                        <th scope="col">Preis</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a href="/leistungen/quick-assessment.php"><strong>Quick Assessment</strong></a><br><small>Standortbestimmung mit Reifegradprofil</small></td>
                        <td class="num">2 Wochen</td>
                        <td class="num">4–6 Std.</td>
                        <td class="num">4.900 – 6.400 €</td>
                    </tr>
                    <tr>
                        <td><a href="/leistungen/rollen-verantwortlichkeiten.php"><strong>Rollen- und Verantwortlichkeitsmodell</strong></a><br><small>Rollenbild, RACI, Vertretung</small></td>
                        <td class="num">6–10 Wochen</td>
                        <td class="num">3–4 Std./Woche</td>
                        <td class="num">9.500 – 19.000 €</td>
                    </tr>
                    <tr>
                        <td><a href="/leistungen/it-prozess-assessment.php"><strong>IT-Prozess-Assessment</strong></a><br><small>Reifegrad der Kernprozesse</small></td>
                        <td class="num">4–6 Wochen</td>
                        <td class="num">2–3 Std./Woche</td>
                        <td class="num">12.000 – 22.000 €</td>
                    </tr>
                    <tr>
                        <td><a href="/leistungen/gap-analyse.php"><strong>IT-Governance Gap-Analyse</strong></a><br><small>Soll-Ist-Vergleich mit Maßnahmenplan</small></td>
                        <td class="num">4–8 Wochen</td>
                        <td class="num">2–4 Std./Woche</td>
                        <td class="num">14.500 – 26.000 €</td>
                    </tr>
                    <tr>
                        <td><a href="/leistungen/audit-readiness.php"><strong>Audit Readiness Assessment</strong></a><br><small>Prüfung vor der Prüfung</small></td>
                        <td class="num">4–6 Wochen</td>
                        <td class="num">1–2 Tage</td>
                        <td class="num">16.000 – 29.000 €</td>
                    </tr>
                    <tr>
                        <td><a href="/leistungen/kontrollframework.php"><strong>IT-Kontrollframework</strong></a><br><small>15–40 Kontrollen in Betrieb</small></td>
                        <td class="num">2–4 Monate</td>
                        <td class="num">3–5 Std./Woche</td>
                        <td class="num">16.000 – 34.000 €</td>
                    </tr>
                    <tr>
                        <td><a href="/leistungen/demand-management.php"><strong>IT Demand Management</strong></a><br><small>Prozess, Gremium, Werkzeug</small></td>
                        <td class="num">2–4 Monate</td>
                        <td class="num">4–6 Std./Woche</td>
                        <td class="num">18.000 – 34.000 €</td>
                    </tr>
                    <tr>
                        <td><a href="/leistungen/service-management.php"><strong>IT Service Management</strong></a><br><small>Servicekatalog, Prozesse, Kennzahlen</small></td>
                        <td class="num">3–5 Monate</td>
                        <td class="num">5–8 Std./Woche</td>
                        <td class="num">20.000 – 42.000 €</td>
                    </tr>
                    <tr>
                        <td><a href="/leistungen/governance-framework.php"><strong>IT-Governance-Framework</strong></a><br><small>Richtlinien, Gremien, Entscheidungswege</small></td>
                        <td class="num">3–5 Monate</td>
                        <td class="num">4–6 Std./Woche</td>
                        <td class="num">22.000 – 45.000 €</td>
                    </tr>
                    <tr>
                        <td><a href="/leistungen/it-operating-model.php"><strong>IT Operating Model</strong></a><br><small>Zielbild, Stellenbedarf, Übergangsplan</small></td>
                        <td class="num">3–5 Monate</td>
                        <td class="num">6–10 Std./Woche</td>
                        <td class="num">ab 32.000 €</td>
                    </tr>
                    <tr>
                        <td><a href="/leistungen/governance-betreuung.php"><strong>Laufende Betreuung</strong></a><br><small>1–3 Tage im Monat</small></td>
                        <td class="num">ab 6 Monaten</td>
                        <td class="num">2–6 Std./Monat</td>
                        <td class="num">2.800 – 7.400 € / Monat</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="callout is-ok">
            <span class="callout-icon"><i data-icon="check-circle" class="lucide"></i></span>
            <div class="callout-body">
                <h3 class="callout-title">Anrechnung des Quick Assessments</h3>
                <p>
                    Beauftragen Sie innerhalb von drei Monaten nach einem Quick Assessment eine
                    weiterführende Leistung, wird das Honorar vollständig angerechnet. Der
                    Grund ist schlicht: Die Interviews sind dann bereits geführt, der Aufwand
                    fällt nicht zweimal an.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Preisfaktoren --------------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="section-head is-wide">
            <span class="section-kicker">Transparenz</span>
            <h2 class="section-title">Wovon die Spanne abhängt</h2>
        </div>

        <div class="card-grid cols-4">
            <div class="card is-paper">
                <span class="card-icon"><i data-icon="globe" class="lucide"></i></span>
                <h3 class="card-title">Standorte</h3>
                <p class="card-text">
                    Jede weitere Gesellschaft bedeutet zusätzliche Interviews, Abstimmung und
                    einen Vergleich der Arbeitsweisen. Das ist der größte Kostentreiber.
                </p>
            </div>
            <div class="card is-paper">
                <span class="card-icon"><i data-icon="file-text" class="lucide"></i></span>
                <h3 class="card-title">Ausgangslage</h3>
                <p class="card-text">
                    Vorhandene Dokumentation senkt den Preis erheblich. Wenn alles erst
                    zusammengetragen werden muss, steigt der Aufwand um 20 bis 40 Prozent.
                </p>
            </div>
            <div class="card is-paper">
                <span class="card-icon"><i data-icon="target" class="lucide"></i></span>
                <h3 class="card-title">Anforderungsrahmen</h3>
                <p class="card-text">
                    Ein Katalog ist günstiger als drei. Wer ISO, NIS2 und Konzernvorgaben
                    gleichzeitig abdecken will, braucht mehr Abgleichsarbeit.
                </p>
            </div>
            <div class="card is-paper">
                <span class="card-icon"><i data-icon="users" class="lucide"></i></span>
                <h3 class="card-title">Beteiligte</h3>
                <p class="card-text">
                    Je mehr Personen einbezogen werden müssen, desto mehr Termine. Beteiligung
                    kostet Geld – und ist trotzdem meist die richtige Entscheidung.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Einordnung ----------------------------------------------------------- -->
<section class="section section-navy">
    <div class="container">
        <div class="split is-top">
            <div>
                <span class="section-kicker">Einordnung</span>
                <h2 class="section-title">Ist das teuer?</h2>
                <p>
                    Eine berechtigte Frage, und die Antwort hängt vom Vergleichsmaßstab ab. Drei
                    Vergleiche, die in Gesprächen regelmäßig helfen:
                </p>
                <ul class="checklist is-dark">
                    <li><strong>Gegen eine eigene Stelle:</strong> Eine Fachkraft für
                        IT-Governance kostet mit Nebenkosten 90.000 bis 130.000 € im Jahr – und
                        ist am Arbeitsmarkt schwer zu finden. Für viele Unternehmen lohnt sich
                        die Stelle erst ab einer bestimmten Größe.</li>
                    <li><strong>Gegen große Beratungshäuser:</strong> Dort liegen Tagessätze für
                        vergleichbare Arbeit deutlich höher, und die Arbeit macht häufig ein
                        Team mit weniger Erfahrung als der Partner im Angebotstermin.</li>
                    <li><strong>Gegen die Kosten des Nichtstuns:</strong> Ein Fertigungsstillstand
                        von drei Tagen, ein verlorener Großkunde nach schlechtem Lieferantenaudit
                        oder ein Prüfungsvermerk, der bis zur Bank durchschlägt – solche Ereignisse
                        kosten ein Vielfaches.</li>
                </ul>
            </div>
            <div>
                <div class="card is-navy" style="border:1px solid rgba(255,255,255,.16);">
                    <h3 class="card-title">Wann es sich nicht rechnet</h3>
                    <p class="card-text">
                        Auch das gehört hierher: Wenn Ihr Unternehmen unter 100 Mitarbeitende
                        hat, keinen regulatorischen Druck spürt, an einem Standort arbeitet und
                        eine überschaubare Systemlandschaft betreibt, ist ein Projekt in dieser
                        Größenordnung nicht angemessen.
                    </p>
                    <p class="card-text">
                        Dann ist ein Quick Assessment mit anschließender Selbstumsetzung der
                        vernünftige Weg – oder gar nichts. Diesen Rat gebe ich mehrmals im Jahr,
                        und er ist ernst gemeint.
                    </p>
                    <a href="/wissen/reifegradmodell.php" class="btn-primary-custom btn-on-dark">Erst selbst einschätzen</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Fragen --------------------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="section-kicker">Häufige Fragen zum Preis</span>
            <h2 class="section-title">Was regelmäßig gefragt wird</h2>
        </div>

        <div class="faq-list">
            <div class="faq-item">
                <button class="faq-question" type="button" aria-expanded="false">
                    <span class="faq-number">01</span>
                    <span class="faq-question-text">Gibt es Fördermittel für solche Projekte?</span>
                </button>
                <div class="faq-answer" aria-hidden="true">
                    <p>
                        Für Beratungsleistungen im Mittelstand existieren je nach Bundesland und
                        Programmlage Zuschüsse, teils auch für Digitalisierungs- und
                        Sicherheitsthemen. Programme, Bedingungen und Fristen ändern sich
                        allerdings häufig, und die Antragsberatung ist nicht mein Fachgebiet. Ich
                        stelle Ihnen gern eine Leistungsbeschreibung bereit, die sich für einen
                        Antrag verwenden lässt – die Prüfung der Förderfähigkeit übernimmt Ihre
                        Hausbank, Ihr Steuerberater oder eine Förderberatung.
                    </p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" type="button" aria-expanded="false">
                    <span class="faq-number">02</span>
                    <span class="faq-question-text">Können wir das Projekt aufteilen?</span>
                </button>
                <div class="faq-answer" aria-hidden="true">
                    <p>
                        Ja, und häufig ist das die bessere Lösung. Ein Governance-Framework lässt
                        sich in zwei Stufen bauen: erst die vier wichtigsten Regelungen, ein halbes
                        Jahr später der Rest. Sie sehen früher Wirkung, verteilen die Kosten und
                        können nach der ersten Stufe entscheiden, ob Sie weitermachen.
                    </p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" type="button" aria-expanded="false">
                    <span class="faq-number">03</span>
                    <span class="faq-question-text">Was passiert, wenn wir das Projekt abbrechen?</span>
                </button>
                <div class="faq-answer" aria-hidden="true">
                    <p>
                        Abrechnung nach erbrachter Leistung anhand der vereinbarten Phasen; bereits
                        erstellte Ergebnisse gehören Ihnen. Ich halte niemanden in einem Projekt
                        fest, das keinen Sinn mehr ergibt – und habe lieber einen Kunden, der
                        später wiederkommt.
                    </p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" type="button" aria-expanded="false">
                    <span class="faq-number">04</span>
                    <span class="faq-question-text">Rechnen Sie Reisekosten ab?</span>
                </button>
                <div class="faq-answer" aria-hidden="true">
                    <p>
                        Ja, nach tatsächlichem Aufwand: Fahrtkosten, gegebenenfalls Übernachtung.
                        Reisezeit berechne ich nicht. Der größere Teil der Arbeit läuft ohnehin per
                        Video – Interviews funktionieren so erfahrungsgemäß genauso gut, Workshops
                        etwas schlechter.
                    </p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" type="button" aria-expanded="false">
                    <span class="faq-number">05</span>
                    <span class="faq-question-text">Wie schnell können Sie starten?</span>
                </button>
                <div class="faq-answer" aria-hidden="true">
                    <p>
                        Ein Quick Assessment lässt sich in der Regel innerhalb von drei bis vier
                        Wochen einplanen, größere Projekte je nach Auslastung in vier bis zehn
                        Wochen. Wenn ein Prüfungstermin drängt, sagen Sie das im Erstgespräch –
                        dann prüfe ich, ob sich etwas vorziehen lässt, statt einen Termin
                        zuzusagen, den ich nicht halten kann.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
