<?php
$page = [
    'title'       => 'Für wen ich arbeite – und für wen nicht',
    'description' => 'Typische Ausgangslagen mittelständischer Unternehmen mit Governance-Bedarf: gewachsene IT, Prüfungsfeststellungen, regulatorischer Druck, mehrere Standorte, unklare Verantwortlichkeiten – und die Fälle, in denen ich der Falsche bin.',
    'section'     => '',
    'path'        => 'fuer-wen.php',
    'crumbs'      => [['Für wen', null]],
    'hero'        => [
        'kicker' => 'Zielgruppe',
        'h1'     => 'Neun Ausgangslagen, in denen <span class="accent">der Anruf sich lohnt</span>',
        'lead'   => 'Ich arbeite für mittelständische Unternehmen im deutschsprachigen Raum, meist zwischen 150 und 5.000 Mitarbeitenden, häufig mit mehreren Standorten oder Gesellschaften. Entscheidender als die Größe ist aber die Situation.',
        'actions' => [
            ['Erstgespräch vereinbaren', 'kontakt.php', 'primary'],
            ['Selbsttest machen', 'wissen/reifegradmodell.php', 'ghost'],
        ],
    ],
];
include __DIR__ . '/partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="signal-list">
            <div class="signal">
                <span class="signal-mark"><i data-icon="trending-up" class="lucide"></i></span>
                <div>
                    <h3>1 · Die IT ist gewachsen, die Steuerung nicht</h3>
                    <p>
                        Aus drei Administratoren sind fünfzehn geworden, aus einem ERP eine
                        Systemlandschaft. Die Arbeitsweise ist dieselbe geblieben: kurze Wege,
                        Zuruf, Vertrauen. Das funktioniert, bis jemand von außen Fragen stellt –
                        oder eine Schlüsselperson geht.
                        <a href="/leistungen/quick-assessment.php">Quick Assessment</a> als
                        Einstieg.
                    </p>
                </div>
            </div>

            <div class="signal">
                <span class="signal-mark"><i data-icon="clipboard-check" class="lucide"></i></span>
                <div>
                    <h3>2 · Der Prüfbericht enthält IT-Feststellungen</h3>
                    <p>
                        Der Wirtschaftsprüfer hat Berechtigungen, Änderungsverfahren oder
                        Auslagerungen bemängelt – vielleicht sogar dieselben Punkte wie im
                        Vorjahr. Wiederholte Feststellungen werden härter bewertet und erreichen
                        irgendwann den Beirat.
                        <a href="/leistungen/audit-readiness.php">Audit Readiness</a> oder
                        <a href="/leistungen/kontrollframework.php">Kontrollframework</a>.
                    </p>
                </div>
            </div>

            <div class="signal">
                <span class="signal-mark"><i data-icon="shield-check" class="lucide"></i></span>
                <div>
                    <h3>3 · Der regulatorische Druck nimmt zu</h3>
                    <p>
                        NIS2 über die Lieferkette, ein Kunde mit Sicherheitsfragebogen, ein
                        Versicherer mit Fragenkatalog, eine Konzernmutter mit Vorgaben. Die
                        Anforderungen kommen selten einzeln – und überschneiden sich stark.
                        <a href="/leistungen/gap-analyse.php">Gap-Analyse</a> schafft Klarheit.
                    </p>
                </div>
            </div>

            <div class="signal">
                <span class="signal-mark"><i data-icon="users" class="lucide"></i></span>
                <div>
                    <h3>4 · Niemand weiß genau, wer entscheidet</h3>
                    <p>
                        Zuständigkeiten sind historisch verteilt, Vertretungen ungeklärt,
                        Entscheidungen hängen an Personen. Im Alltag geht das gut – im Urlaub,
                        im Notfall oder in der Prüfung nicht.
                        <a href="/leistungen/rollen-verantwortlichkeiten.php">Rollenmodell</a>.
                    </p>
                </div>
            </div>

            <div class="signal">
                <span class="signal-mark"><i data-icon="file-text" class="lucide"></i></span>
                <div>
                    <h3>5 · Die Dokumentation ist veraltet oder fehlt</h3>
                    <p>
                        Was existiert, stammt aus einer anderen Zeit. Im Ernstfall verlässt sich
                        niemand darauf, und für Prüfungen wird jedes Mal neu zusammengesucht.
                        <a href="/wissen/dokumentenlandkarte.php">Dokumentenlandkarte</a> als
                        Einstieg, danach
                        <a href="/leistungen/governance-framework.php">Governance-Framework</a>.
                    </p>
                </div>
            </div>

            <div class="signal">
                <span class="signal-mark"><i data-icon="globe" class="lucide"></i></span>
                <div>
                    <h3>6 · Mehrere Standorte, mehrere Arbeitsweisen</h3>
                    <p>
                        Jede Gesellschaft macht es anders, ohne dass das jemals entschieden
                        worden wäre. Vergleichbare Zahlen gibt es nicht, gegenseitige Vertretung
                        auch nicht.
                        <a href="/themen/prozessharmonisierung.php">Prozessharmonisierung</a>
                        oder <a href="/leistungen/it-operating-model.php">IT Operating Model</a>.
                    </p>
                </div>
            </div>

            <div class="signal">
                <span class="signal-mark"><i data-icon="inbox" class="lucide"></i></span>
                <div>
                    <h3>7 · Anforderungen kommen per Zuruf</h3>
                    <p>
                        Die IT arbeitet am Anschlag, und trotzdem sind alle Fachbereiche
                        unzufrieden. Es fehlt kein Personal, sondern ein Verfahren, das
                        priorisiert und zurückmeldet.
                        <a href="/leistungen/demand-management.php">Demand Management</a>.
                    </p>
                </div>
            </div>

            <div class="signal">
                <span class="signal-mark"><i data-icon="line-chart" class="lucide"></i></span>
                <div>
                    <h3>8 · Die Geschäftsführung will die IT steuern, nicht nur bezahlen</h3>
                    <p>
                        Es gibt Budgetzahlen, aber keine Steuerungsgrößen. Auf die Frage „Läuft
                        es gut?“ folgt eine Erzählung statt einer Kennzahl.
                        <a href="/themen/it-kennzahlen.php">IT-Kennzahlen</a> und
                        <a href="/leistungen/service-management.php">Service Management</a>.
                    </p>
                </div>
            </div>

            <div class="signal">
                <span class="signal-mark"><i data-icon="user-check" class="lucide"></i></span>
                <div>
                    <h3>9 · Es gibt niemanden im Haus, der das Thema besetzt</h3>
                    <p>
                        Eine eigene Stelle für IT-Governance lohnt sich erst ab einer bestimmten
                        Größe. Bis dahin fehlt jemand, der Struktur aufbaut und nachhält – und
                        die IT-Leitung hat dafür schlicht keine Zeit.
                        <a href="/leistungen/governance-betreuung.php">Laufende Betreuung</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Branchen ------------------------------------------------------------- -->
<section class="section section-paper section-line">
    <div class="container">
        <div class="split is-top">
            <div>
                <span class="section-kicker">Branchen</span>
                <h2 class="section-title">Wo ich mich schnell zurechtfinde</h2>
                <p>
                    Governance funktioniert branchenübergreifend ähnlich – die Unterschiede
                    liegen in den Anforderungen von außen und in der Sprache. Am schnellsten
                    bin ich dort produktiv, wo ich das Umfeld kenne:
                </p>
                <ul class="checklist">
                    <li><strong>Produzierendes Gewerbe und Maschinenbau</strong> – ERP-getrieben,
                        mehrere Werke, Vertriebsgesellschaften im Ausland, häufig NIS2-relevanter
                        Sektor</li>
                    <li><strong>Handel und Großhandel</strong> – hohe Systemabhängigkeit, enge
                        Verfügbarkeitsanforderungen, viele Schnittstellen</li>
                    <li><strong>Technische Dienstleister und Systemhäuser</strong> – bekommen
                        Anforderungen ihrer Kunden weitergereicht, oft aus dem Finanzsektor</li>
                    <li><strong>Unternehmen mit Konzernmutter</strong> – Vorgaben von oben,
                        begrenzte lokale Ressourcen, regelmäßige Konzernrevision</li>
                </ul>
                <p>
                    Was ich nicht bediene: Bankenaufsichtsrecht im engeren Sinne, klinische
                    Prozesse im Krankenhaus und öffentliche Verwaltung mit ihren besonderen
                    Vergabe- und Haushaltsregeln. Dort gibt es Spezialisten, die das besser
                    können.
                </p>
            </div>
            <div>
                <div class="card is-navy">
                    <h3 class="card-title">Größenordnung</h3>
                    <p class="card-text">
                        Der Zuschnitt der Leistungen passt am besten auf Unternehmen zwischen
                        150 und 5.000 Mitarbeitenden.
                    </p>
                    <ul class="checklist is-dark is-tight">
                        <li><strong>Unter 100 Mitarbeitende:</strong> meist überdimensioniert.
                            Hier genügt oft ein Quick Assessment mit Selbstumsetzung.</li>
                        <li><strong>150 bis 1.000:</strong> der Kernbereich. Struktur fehlt,
                            Anforderungen kommen, eigene Spezialisten gibt es nicht.</li>
                        <li><strong>1.000 bis 5.000:</strong> häufig mehrere Gesellschaften,
                            Harmonisierungsbedarf, Konzernvorgaben.</li>
                        <li><strong>Über 5.000:</strong> in der Regel eigene Governance-Funktion
                            vorhanden – dann bin ich punktuell nützlich, nicht als Aufbauhilfe.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nicht passend -------------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="section-head is-center">
            <span class="section-kicker">Ehrlichkeit</span>
            <h2 class="section-title">Wann Sie mich nicht beauftragen sollten</h2>
            <p class="section-lead">
                Diese Liste kostet mich Aufträge und erspart uns beiden schlechte Projekte.
            </p>
        </div>

        <div class="card-grid cols-3">
            <div class="card">
                <span class="card-icon is-soft"><i data-icon="scale" class="lucide"></i></span>
                <h3 class="card-title">Sie brauchen eine rechtliche Bewertung</h3>
                <p class="card-text">
                    Betroffenheit, Haftung, Vertragsfragen: Das gehört zu einer Kanzlei. Ich
                    kann die Vorarbeit leisten, aber nicht die Antwort geben.
                </p>
            </div>
            <div class="card">
                <span class="card-icon is-soft"><i data-icon="award" class="lucide"></i></span>
                <h3 class="card-title">Sie wollen nur das Zertifikat</h3>
                <p class="card-text">
                    Ein Managementsystem, das niemand betreibt, hält keine zwei
                    Überwachungsaudits durch. Wer nur den Aufkleber will, ist bei mir falsch.
                </p>
            </div>
            <div class="card">
                <span class="card-icon is-soft"><i data-icon="server" class="lucide"></i></span>
                <h3 class="card-title">Sie brauchen operative Kapazität</h3>
                <p class="card-text">
                    Tickets abarbeiten, Systeme betreuen, Projekte umsetzen: Dafür sind
                    Systemhäuser und Freiberufler die passende und günstigere Antwort.
                </p>
            </div>
            <div class="card">
                <span class="card-icon is-soft"><i data-icon="eye" class="lucide"></i></span>
                <h3 class="card-title">Das Ergebnis steht schon fest</h3>
                <p class="card-text">
                    Ein Gutachten, das eine getroffene Entscheidung stützen soll, schreibe ich
                    nicht. Auch dann nicht, wenn es gut bezahlt wäre.
                </p>
            </div>
            <div class="card">
                <span class="card-icon is-soft"><i data-icon="users" class="lucide"></i></span>
                <h3 class="card-title">Niemand übernimmt die Ergebnisse</h3>
                <p class="card-text">
                    Ohne verantwortliche Person im Haus verpufft jedes Governance-Projekt.
                    Dann lieber später starten – oder erst die Stelle klären.
                </p>
            </div>
            <div class="card">
                <span class="card-icon is-soft"><i data-icon="euro" class="lucide"></i></span>
                <h3 class="card-title">Der Preis ist das Hauptkriterium</h3>
                <p class="card-text">
                    Es gibt günstigere Anbieter, und für manche Aufgaben genügen sie. Wer den
                    niedrigsten Tagessatz sucht, wird bei mir nicht fündig.
                </p>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
