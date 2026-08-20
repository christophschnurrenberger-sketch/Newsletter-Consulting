<?php
$page = [
    'title'       => 'Leistungen: IT-Governance, Audit Readiness, IT-Prozesse',
    'description' => 'Elf klar abgegrenzte Beratungsleistungen rund um IT-Governance: Quick Assessment, Gap-Analyse, Audit Readiness, Prozess-Assessment, Operating Model, Kontrollframework, Demand- und Service-Management-Setup sowie laufende Betreuung.',
    'section'     => 'leistungen',
    'path'        => 'leistungen/',
    'crumbs'      => [['Leistungen', null]],
    'hero'        => [
        'kicker' => 'Leistungen',
        'h1'     => 'Elf Leistungen. Jede mit <span class="accent">festem Ergebnis</span>.',
        'lead'   => 'Beratung wird teuer, wenn niemand weiß, wann sie fertig ist. Deshalb hat jede Leistung hier einen definierten Umfang, ein benanntes Ergebnis, eine Dauer und einen Preisrahmen – vor der Beauftragung, nicht danach.',
        'actions' => [
            ['Erstgespräch vereinbaren', 'kontakt.php', 'primary'],
            ['Preise ansehen', 'preise.php', 'ghost'],
        ],
        'facts'  => [
            ['11', 'Leistungen'],
            ['4', 'Einstiegsformate'],
            ['0 €', 'Erstgespräch'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="section-head is-wide">
            <span class="section-kicker">Einordnung</span>
            <h2 class="section-title">Erst schauen, dann bauen, dann halten</h2>
            <p class="section-lead">
                Die Leistungen bauen aufeinander auf. Fast niemand startet mit einem Operating
                Model – die meisten Projekte beginnen mit einer Bestandsaufnahme, weil erst dann
                seriös entschieden werden kann, was sich lohnt. Wer bereits weiß, wo es klemmt,
                kann direkt in eine Aufbauleistung einsteigen.
            </p>
        </div>

<?php
$kette = [
    ['1 · Schauen', 'Quick Assessment, Gap-Analyse, Audit Readiness'],
    ['2 · Bauen', 'Framework, Rollen, Kontrollen, Prozesse'],
    ['3 · Halten', 'Laufende Betreuung, Auditbegleitung'],
];
$ketteLabel = 'Reihenfolge der Leistungen';
include __DIR__ . '/../partials/kette.php';
?>
    </div>
</section>

<!-- 1 Schauen ----------------------------------------------------------- -->
<section class="section section-paper section-line">
    <div class="container">
        <div class="section-head">
            <span class="section-kicker">1 · Schauen</span>
            <h2 class="section-title">Bestandsaufnahme und Bewertung</h2>
            <p class="section-lead">
                Bevor Geld in Maßnahmen fließt, muss klar sein, welche Maßnahmen überhaupt
                etwas bewirken. Diese vier Leistungen liefern die Entscheidungsgrundlage.
            </p>
        </div>

        <div class="card-grid cols-2">
            <a href="/leistungen/quick-assessment.php" class="card service-card">
                <span class="card-icon"><i data-icon="compass" class="lucide"></i></span>
                <h3 class="card-title">IT-Governance Quick Assessment</h3>
                <p class="card-text">
                    Zwei Wochen, sechs bis acht Interviews, ein Bericht mit Reifegrad je
                    Handlungsfeld und einer Liste der drei bis fünf dringendsten Themen. Das
                    Format für alle, die erst wissen wollen, wie groß das Thema wirklich ist.
                </p>
                <div class="card-meta">
                    <span><i data-icon="clock" class="lucide"></i>2 Wochen</span>
                    <span><i data-icon="users" class="lucide"></i>4–6 Std. intern</span>
                </div>
                <div class="card-foot">
                    <p class="service-price">ab 4.900 €<small>zzgl. USt.</small></p>
                </div>
            </a>

            <a href="/leistungen/gap-analyse.php" class="card service-card is-feature">
                <span class="service-flag">Häufigster Einstieg</span>
                <span class="card-icon is-accent"><i data-icon="search" class="lucide"></i></span>
                <h3 class="card-title">IT-Governance Gap-Analyse</h3>
                <p class="card-text">
                    Vollständiger Soll-Ist-Vergleich gegen einen definierten Rahmen – wahlweise
                    an ISO/IEC 27001, NIS2-Maßnahmen oder Konzernvorgaben ausgerichtet. Ergebnis
                    ist eine priorisierte Maßnahmenliste mit Aufwand, Wirkung und Reihenfolge.
                </p>
                <div class="card-meta">
                    <span><i data-icon="clock" class="lucide"></i>4–8 Wochen</span>
                    <span><i data-icon="users" class="lucide"></i>2–4 Std./Woche intern</span>
                </div>
                <div class="card-foot">
                    <p class="service-price">14.500 – 26.000 €<small>zzgl. USt.</small></p>
                </div>
            </a>

            <a href="/leistungen/audit-readiness.php" class="card service-card">
                <span class="card-icon"><i data-icon="clipboard-check" class="lucide"></i></span>
                <h3 class="card-title">Audit Readiness Assessment</h3>
                <p class="card-text">
                    Die Prüfung vor der Prüfung. Ich gehe mit der Brille des Prüfers durch Ihre
                    Unterlagen, ziehe Stichproben, fordere Nachweise an und trainiere die
                    Interviewsituation. Sie erfahren vorher, was sonst im Bericht stünde.
                </p>
                <div class="card-meta">
                    <span><i data-icon="clock" class="lucide"></i>4–6 Wochen</span>
                    <span><i data-icon="users" class="lucide"></i>1–2 Tage intern</span>
                </div>
                <div class="card-foot">
                    <p class="service-price">16.000 – 29.000 €<small>zzgl. USt.</small></p>
                </div>
            </a>

            <a href="/leistungen/it-prozess-assessment.php" class="card service-card">
                <span class="card-icon"><i data-icon="git-branch" class="lucide"></i></span>
                <h3 class="card-title">IT-Prozess-Assessment</h3>
                <p class="card-text">
                    Reifegradbewertung der zentralen IT-Prozesse – Incident, Change, Demand,
                    Zugriffsverwaltung, Auslagerung. Nicht gegen ein Lehrbuch, sondern gegen
                    das, was ein Unternehmen Ihrer Größe braucht.
                </p>
                <div class="card-meta">
                    <span><i data-icon="clock" class="lucide"></i>4–6 Wochen</span>
                    <span><i data-icon="users" class="lucide"></i>2–3 Std./Woche intern</span>
                </div>
                <div class="card-foot">
                    <p class="service-price">12.000 – 22.000 €<small>zzgl. USt.</small></p>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- 2 Bauen -------------------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="section-kicker">2 · Bauen</span>
            <h2 class="section-title">Strukturen aufbauen und einführen</h2>
            <p class="section-lead">
                Aufbauleistungen enden nicht mit einem Dokument, sondern mit einer Struktur, die
                in Betrieb ist: benannte Rollen, entschiedene Regeln, laufende Prozesse.
            </p>
        </div>

        <div class="card-grid cols-3">
            <a href="/leistungen/governance-framework.php" class="card service-card">
                <span class="card-icon"><i data-icon="building" class="lucide"></i></span>
                <h3 class="card-title">IT-Governance-Framework</h3>
                <p class="card-text">
                    Richtlinienlandschaft, Gremien, Entscheidungswege und Eskalation – auf
                    Mittelstandsmaß zugeschnitten statt aus dem Konzernhandbuch kopiert.
                </p>
                <div class="card-foot"><p class="service-price">22.000 – 45.000 €<small>3–5 Monate</small></p></div>
            </a>

            <a href="/leistungen/rollen-verantwortlichkeiten.php" class="card service-card">
                <span class="card-icon"><i data-icon="users" class="lucide"></i></span>
                <h3 class="card-title">Rollen &amp; Verantwortlichkeiten</h3>
                <p class="card-text">
                    Rollenmodell mit RACI, Vertretungsregelungen und Stellenbeschreibungen –
                    abgestimmt mit HR und Betriebsrat, damit es Bestand hat.
                </p>
                <div class="card-foot"><p class="service-price">9.500 – 19.000 €<small>6–10 Wochen</small></p></div>
            </a>

            <a href="/leistungen/kontrollframework.php" class="card service-card">
                <span class="card-icon"><i data-icon="shield-check" class="lucide"></i></span>
                <h3 class="card-title">IT-Kontrollframework</h3>
                <p class="card-text">
                    15 bis 40 wirksame Kontrollen statt einer 200-Zeilen-Matrix: beschrieben,
                    zugeordnet, terminiert – und mit Nachweis, der von allein entsteht.
                </p>
                <div class="card-foot"><p class="service-price">16.000 – 34.000 €<small>2–4 Monate</small></p></div>
            </a>

            <a href="/leistungen/demand-management.php" class="card service-card">
                <span class="card-icon"><i data-icon="inbox" class="lucide"></i></span>
                <h3 class="card-title">IT Demand Management</h3>
                <p class="card-text">
                    Ein Eingang für alle Anforderungen, ein Bewertungsschema, ein Gremium, das
                    entscheidet. Inklusive Einführung in Ihrem Werkzeug.
                </p>
                <div class="card-foot"><p class="service-price">18.000 – 34.000 €<small>2–4 Monate</small></p></div>
            </a>

            <a href="/leistungen/service-management.php" class="card service-card">
                <span class="card-icon"><i data-icon="server" class="lucide"></i></span>
                <h3 class="card-title">IT Service Management</h3>
                <p class="card-text">
                    Servicekatalog, Serviceverantwortliche, Incident- und Change-Prozess sowie
                    Kennzahlen, die man wirklich messen kann.
                </p>
                <div class="card-foot"><p class="service-price">20.000 – 42.000 €<small>3–5 Monate</small></p></div>
            </a>

            <a href="/leistungen/it-operating-model.php" class="card service-card">
                <span class="card-icon"><i data-icon="layers" class="lucide"></i></span>
                <h3 class="card-title">IT Operating Model</h3>
                <p class="card-text">
                    Die große Frage: Wie soll die IT künftig arbeiten, wer macht was zentral,
                    was lokal, was extern – mit Übergangsplan und Stellenbedarf.
                </p>
                <div class="card-foot"><p class="service-price">ab 32.000 €<small>3–5 Monate</small></p></div>
            </a>
        </div>
    </div>
</section>

<!-- 3 Halten ------------------------------------------------------------- -->
<section class="section section-paper section-line">
    <div class="container">
        <div class="split is-top">
            <div>
                <span class="section-kicker">3 · Halten</span>
                <h2 class="section-title">Damit es nach dem Projekt nicht einschläft</h2>
                <p>
                    Der häufigste Fehler nach einem Governance-Projekt: Alles ist beschrieben,
                    entschieden und eingeführt – und nach sechs Monaten macht es niemand mehr,
                    weil der Alltag dazwischenkommt und niemand nachhält.
                </p>
                <p>
                    Genau dafür gibt es die laufende Betreuung: ein fester Tag im Monat, an dem
                    Maßnahmen nachgehalten, Kontrollen geprüft, Berichte vorbereitet und
                    anstehende Prüfungen begleitet werden. Kein Dauervertrag ohne Ergebnis,
                    sondern ein Rhythmus mit sichtbarem Fortschritt.
                </p>
                <a href="/leistungen/governance-betreuung.php" class="btn-secondary">Laufende Betreuung ansehen</a>
            </div>
            <div>
                <div class="card">
                    <span class="card-icon is-accent"><i data-icon="repeat" class="lucide"></i></span>
                    <h3 class="card-title">Laufende Governance-Betreuung</h3>
                    <p class="card-text">
                        Ab 2.800 € im Monat, Laufzeit ab sechs Monaten, monatlich ein bis drei
                        Beratungstage nach Bedarf.
                    </p>
                    <ul class="checklist is-tight">
                        <li>Maßnahmenverfolgung mit Statusbericht an die Geschäftsführung</li>
                        <li>Kontrollen stichprobenhaft prüfen, Nachweise sichten</li>
                        <li>Richtlinien aktuell halten, Änderungen dokumentieren</li>
                        <li>Vorbereitung und Begleitung von Prüfungen und Kundenaudits</li>
                        <li>Ansprechpartner für IT-Leitung bei Governance-Fragen</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vergleich ------------------------------------------------------------ -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="section-kicker">Auswahlhilfe</span>
            <h2 class="section-title">Welche Leistung passt zu welcher Lage?</h2>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <caption>Preise netto, zzgl. USt. Rahmenwerte für Unternehmen mit 150 bis 5.000 Mitarbeitenden.</caption>
                <thead>
                    <tr>
                        <th scope="col">Ihre Lage</th>
                        <th scope="col">Passende Leistung</th>
                        <th scope="col">Dauer</th>
                        <th scope="col">Rahmen</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>„Wir wissen nicht, wo wir stehen.“</td>
                        <td><a href="/leistungen/quick-assessment.php">Quick Assessment</a></td>
                        <td class="num">2 Wochen</td>
                        <td class="num">ab 4.900 €</td>
                    </tr>
                    <tr>
                        <td>„Wir sollen ISO 27001 oder NIS2-Maßnahmen erfüllen.“</td>
                        <td><a href="/leistungen/gap-analyse.php">Gap-Analyse</a></td>
                        <td class="num">4–8 Wochen</td>
                        <td class="num">14.500 – 26.000 €</td>
                    </tr>
                    <tr>
                        <td>„In drei Monaten kommt der Prüfer.“</td>
                        <td><a href="/leistungen/audit-readiness.php">Audit Readiness</a></td>
                        <td class="num">4–6 Wochen</td>
                        <td class="num">16.000 – 29.000 €</td>
                    </tr>
                    <tr>
                        <td>„Der Prüfbericht hat Feststellungen zu IT-Prozessen.“</td>
                        <td><a href="/leistungen/it-prozess-assessment.php">Prozess-Assessment</a></td>
                        <td class="num">4–6 Wochen</td>
                        <td class="num">12.000 – 22.000 €</td>
                    </tr>
                    <tr>
                        <td>„Niemand weiß, wer entscheidet.“</td>
                        <td><a href="/leistungen/rollen-verantwortlichkeiten.php">Rollenmodell</a></td>
                        <td class="num">6–10 Wochen</td>
                        <td class="num">9.500 – 19.000 €</td>
                    </tr>
                    <tr>
                        <td>„Anforderungen kommen per Zuruf und Flurfunk.“</td>
                        <td><a href="/leistungen/demand-management.php">Demand Management</a></td>
                        <td class="num">2–4 Monate</td>
                        <td class="num">18.000 – 34.000 €</td>
                    </tr>
                    <tr>
                        <td>„Der Helpdesk ertrinkt, niemand kennt unsere Services.“</td>
                        <td><a href="/leistungen/service-management.php">Service Management</a></td>
                        <td class="num">3–5 Monate</td>
                        <td class="num">20.000 – 42.000 €</td>
                    </tr>
                    <tr>
                        <td>„Wir müssen Kontrollen und Nachweise aufbauen.“</td>
                        <td><a href="/leistungen/kontrollframework.php">Kontrollframework</a></td>
                        <td class="num">2–4 Monate</td>
                        <td class="num">16.000 – 34.000 €</td>
                    </tr>
                    <tr>
                        <td>„Fünf Standorte, fünf Arbeitsweisen.“</td>
                        <td><a href="/leistungen/it-operating-model.php">Operating Model</a></td>
                        <td class="num">3–5 Monate</td>
                        <td class="num">ab 32.000 €</td>
                    </tr>
                    <tr>
                        <td>„Wir haben ein Konzept – es passiert nur nichts.“</td>
                        <td><a href="/leistungen/governance-betreuung.php">Laufende Betreuung</a></td>
                        <td class="num">ab 6 Monaten</td>
                        <td class="num">ab 2.800 €/Monat</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="callout">
            <span class="callout-icon"><i data-icon="help-circle" class="lucide"></i></span>
            <div class="callout-body">
                <h3 class="callout-title">Unsicher, was passt?</h3>
                <p>
                    Das ist der Normalfall und kein Problem. Im kostenlosen Erstgespräch
                    klären wir in 30 Minuten, welche Leistung Ihre Lage trifft – und ob es
                    überhaupt eine braucht. Zweimal im Jahr sage ich Interessenten, dass sie
                    das intern lösen können. Das ist kein Verkaufstrick, sondern eine Frage
                    der Ehrlichkeit.
                </p>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
