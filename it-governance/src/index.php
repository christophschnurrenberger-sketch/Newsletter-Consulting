<?php
$page = [
    'title'       => 'IT-Governance-Beratung für den Mittelstand',
    'description' => 'Beratung für IT-Governance, IT-Prozesse und Auditfähigkeit im Mittelstand: Strukturen aufbauen, regulatorische Anforderungen umsetzen, Audits bestehen, IT steuerbar machen. Klar abgegrenzte Beratungsleistungen mit festem Ergebnis.',
    'section'     => '',
    'path'        => '',
    'hero'        => false,
];
include __DIR__ . '/partials/header.php';
?>

<!-- Bühne --------------------------------------------------------------- -->
<section class="hero">
    <div class="container">
        <div class="hero-grid">
            <div class="hero-copy">
                <span class="hero-badge">
                    <i data-icon="shield-check" class="lucide"></i>
                    Spezialisiert auf IT-Governance im Mittelstand
                </span>

                <h1 class="hero-title">
                    Ihre IT ist gewachsen.<br>
                    Ihre <span class="accent">Steuerung</span> nicht.
                </h1>

                <p class="hero-lead">
                    Ich helfe mittelständischen Unternehmen, ihre IT-Governance so aufzubauen,
                    dass regulatorische Anforderungen erfüllt, Audits bestanden und IT-Prozesse
                    transparent und steuerbar werden.
                </p>

                <p class="hero-sub">
                    Kein Werkzeugverkauf, keine Zertifikatsfabrik, keine 200-seitigen Konzepte,
                    die niemand liest. Sondern Strukturen, die im Alltag halten – und Nachweise,
                    die in der Prüfung anerkannt werden.
                </p>

                <div class="hero-actions">
                    <a href="/kontakt.php" class="btn-primary-custom">Erstgespräch vereinbaren</a>
                    <a href="/leistungen/" class="btn-secondary">Leistungen ansehen</a>
                </div>

                <div class="hero-proof">
                    <span class="hero-proof-item">
                        <i data-icon="check" class="lucide"></i>
                        Festpreis und festes Ergebnis je Leistung
                    </span>
                    <span class="hero-proof-item">
                        <i data-icon="check" class="lucide"></i>
                        Erfahrung aus internationalem Konzernumfeld
                    </span>
                    <span class="hero-proof-item">
                        <i data-icon="check" class="lucide"></i>
                        Klare Abgrenzung zur Rechtsberatung
                    </span>
                </div>
            </div>

            <div class="hero-panel">
                <div class="hero-panel-head">
                    <p class="hero-panel-title">Das Governance-Haus</p>
                    <span class="badge">Modell</span>
                </div>
                <?php include __DIR__ . '/partials/governance-haus.php'; ?>
            </div>
        </div>
    </div>
</section>

<!-- Einordnung ---------------------------------------------------------- -->
<section class="section-tight">
    <div class="container">
        <div class="trust-row">
            <span><i data-icon="building" class="lucide"></i>Mittelstand, 150 bis 5.000 Mitarbeitende</span>
            <span><i data-icon="globe" class="lucide"></i>Mehrere Standorte und Gesellschaften</span>
            <span><i data-icon="graduation-cap" class="lucide"></i>BWL &amp; M. Sc. Wirtschaftsinformatik</span>
            <span><i data-icon="briefcase" class="lucide"></i>ERP-, Prozess- und Servicemanagement-Praxis</span>
        </div>
    </div>
</section>

<!-- Problem -------------------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="split is-wide-left is-top">
            <div>
                <span class="section-kicker">Die Ausgangslage</span>
                <h2 class="section-title">Niemand hat etwas falsch gemacht – und trotzdem ist es ein Problem</h2>
                <p class="lead">
                    In den meisten mittelständischen IT-Bereichen ist die Organisation nicht
                    geplant worden. Sie ist entstanden. Aus zwei Admins wurden zwölf, aus einem
                    Standort fünf, aus einem ERP drei Systeme und vierzig Fachanwendungen.
                </p>
                <p>
                    Das funktioniert erstaunlich lange – bis jemand von außen Fragen stellt.
                    Ein Wirtschaftsprüfer, ein Kunde mit Lieferantenaudit, eine Konzernmutter,
                    eine Cyberversicherung, eine Behörde. Dann zeigt sich: Vieles wird
                    tatsächlich richtig gemacht. Es lässt sich nur nicht belegen.
                </p>
                <p>
                    Genau an dieser Stelle setze ich an. Nicht mit einem neuen Werkzeug, sondern
                    mit Struktur: Wer entscheidet was, nach welchem Verfahren, mit welchem
                    Nachweis – und wie bleibt das im Betrieb erhalten, wenn der nächste Notfall
                    dazwischenkommt.
                </p>
                <a href="/fuer-wen.php" class="text-link">Typische Ausgangslagen ansehen<i data-icon="arrow-right" class="lucide"></i></a>
            </div>

            <div>
                <div class="signal-list">
                    <div class="signal">
                        <span class="signal-mark"><i data-icon="alert-triangle" class="lucide"></i></span>
                        <div>
                            <h3>„Das macht der Kollege seit Jahren so.“</h3>
                            <p>Verantwortung liegt bei Personen statt bei Rollen. Fällt die Person aus, fällt der Prozess aus.</p>
                        </div>
                    </div>
                    <div class="signal">
                        <span class="signal-mark"><i data-icon="alert-triangle" class="lucide"></i></span>
                        <div>
                            <h3>Die Prüfungsfeststellung wiederholt sich</h3>
                            <p>Dieselbe Feststellung wie im Vorjahr. Weil damals das Symptom beseitigt wurde, nicht die Ursache.</p>
                        </div>
                    </div>
                    <div class="signal">
                        <span class="signal-mark"><i data-icon="alert-triangle" class="lucide"></i></span>
                        <div>
                            <h3>Anforderungen kommen per Zuruf</h3>
                            <p>Jede Fachabteilung priorisiert selbst, die IT arbeitet ab, was am lautesten ist. Ein Demand-Prozess fehlt.</p>
                        </div>
                    </div>
                    <div class="signal">
                        <span class="signal-mark"><i data-icon="alert-triangle" class="lucide"></i></span>
                        <div>
                            <h3>Dokumentation existiert – von 2019</h3>
                            <p>Sie liegt im Laufwerk, niemand pflegt sie, und im Ernstfall verlässt sich niemand darauf.</p>
                        </div>
                    </div>
                    <div class="signal">
                        <span class="signal-mark"><i data-icon="alert-triangle" class="lucide"></i></span>
                        <div>
                            <h3>Die Geschäftsführung fragt nach Zahlen</h3>
                            <p>Und bekommt Aufwandsschätzungen statt Kennzahlen. IT bleibt eine Kostenstelle ohne Steuerungsgrößen.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Handlungsfelder ------------------------------------------------------ -->
<section class="section section-paper section-line">
    <div class="container">
        <div class="section-head">
            <span class="section-kicker">Worum es geht</span>
            <h2 class="section-title">Vier Ebenen, in dieser Reihenfolge</h2>
            <p class="section-lead">
                Governance scheitert selten am guten Willen, sondern an der Reihenfolge.
                Wer Kontrollen einführt, bevor Rollen und Prozesse geklärt sind, produziert
                Papier. Wer Prozesse beschreibt, ohne Verantwortliche zu benennen, produziert
                Absichtserklärungen.
            </p>
        </div>

        <div class="card-grid cols-4">
            <div class="card">
                <span class="card-icon"><i data-icon="building" class="lucide"></i></span>
                <h3 class="card-title">1 · Struktur</h3>
                <p class="card-text">
                    Entscheidungswege, Gremien, Richtlinienlandschaft. Wer entscheidet über
                    Budget, Architektur, Risiken, Ausnahmen – und wo ist das nachlesbar?
                </p>
                <div class="card-foot">
                    <a href="/leistungen/governance-framework.php" class="text-link">Governance-Framework<i data-icon="arrow-right" class="lucide"></i></a>
                </div>
            </div>
            <div class="card">
                <span class="card-icon"><i data-icon="git-branch" class="lucide"></i></span>
                <h3 class="card-title">2 · Prozesse</h3>
                <p class="card-text">
                    Demand, Change, Incident, Zugriffsrechte, Auslagerungen. So beschrieben,
                    dass sie im Alltag funktionieren – und so knapp, dass sie gelesen werden.
                </p>
                <div class="card-foot">
                    <a href="/leistungen/it-prozess-assessment.php" class="text-link">IT-Prozess-Assessment<i data-icon="arrow-right" class="lucide"></i></a>
                </div>
            </div>
            <div class="card">
                <span class="card-icon"><i data-icon="shield-check" class="lucide"></i></span>
                <h3 class="card-title">3 · Kontrollen</h3>
                <p class="card-text">
                    Wenige, wirksame Kontrollen an den richtigen Stellen statt einer Matrix mit
                    200 Zeilen, die im zweiten Quartal niemand mehr ausfüllt.
                </p>
                <div class="card-foot">
                    <a href="/leistungen/kontrollframework.php" class="text-link">IT-Kontrollframework<i data-icon="arrow-right" class="lucide"></i></a>
                </div>
            </div>
            <div class="card">
                <span class="card-icon is-accent"><i data-icon="file-text" class="lucide"></i></span>
                <h3 class="card-title">4 · Nachweise</h3>
                <p class="card-text">
                    Belege, die im laufenden Betrieb entstehen, statt einer Sonderaktion vier
                    Wochen vor der Prüfung. Das ist der Unterschied zwischen bestehen und leiden.
                </p>
                <div class="card-foot">
                    <a href="/leistungen/audit-readiness.php" class="text-link">Audit Readiness<i data-icon="arrow-right" class="lucide"></i></a>
                </div>
            </div>
        </div>

        <div style="margin-top:2.6rem;">
            <h3 style="font-family:var(--sans); font-size:1rem;">So hängt das zusammen</h3>
<?php
$kette = [
    ['Anforderung', 'Gesetz, Norm, Kunde, Konzernvorgabe'],
    ['Regelung', 'Richtlinie, die eine Entscheidung festhält'],
    ['Prozess', 'Ablauf, der die Regelung ausführt'],
    ['Kontrolle', 'Prüfschritt im Prozess'],
    ['Nachweis', 'Beleg, der von allein entsteht'],
];
$ketteLabel = 'Von der Anforderung zum Nachweis';
include __DIR__ . '/partials/kette.php';
?>
            <p class="muted" style="font-size:.92rem;">
                Reißt die Kette an einer Stelle, hilft der Rest nicht: Eine Richtlinie ohne
                Prozess wird nicht gelebt, ein Prozess ohne Kontrolle nicht geprüft, eine
                Kontrolle ohne Nachweis nicht anerkannt.
            </p>
        </div>
    </div>
</section>

<!-- Leistungen ----------------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="section-kicker">Leistungen</span>
            <h2 class="section-title">Abgegrenzte Beratungsleistungen statt Tagessatz auf Zuruf</h2>
            <p class="section-lead">
                Jede Leistung hat einen festen Umfang, ein festes Ergebnis und einen Preis, der
                vor der Beauftragung feststeht. Sie wissen vorher, was am Ende auf dem Tisch liegt
                und wer damit weiterarbeitet.
            </p>
        </div>

        <div class="card-grid cols-3">
            <a href="/leistungen/quick-assessment.php" class="card service-card">
                <span class="card-icon"><i data-icon="compass" class="lucide"></i></span>
                <h3 class="card-title">IT-Governance Quick Assessment</h3>
                <p class="card-text">
                    Standortbestimmung in zwei Wochen: Wo steht die IT-Steuerung, was fällt in
                    einer Prüfung zuerst auf, was ist zuerst zu tun?
                </p>
                <div class="card-foot">
                    <p class="service-price">ab 4.900 €<small>2–3 Beratungstage · 2 Wochen</small></p>
                </div>
            </a>

            <a href="/leistungen/gap-analyse.php" class="card service-card is-feature">
                <span class="service-flag">Häufigster Einstieg</span>
                <span class="card-icon is-accent"><i data-icon="search" class="lucide"></i></span>
                <h3 class="card-title">IT-Governance Gap-Analyse</h3>
                <p class="card-text">
                    Soll-Ist-Vergleich gegen einen definierten Rahmen, mit priorisierter
                    Maßnahmenliste, Aufwandsschätzung und Reihenfolge.
                </p>
                <div class="card-foot">
                    <p class="service-price">14.500 – 26.000 €<small>4–8 Wochen</small></p>
                </div>
            </a>

            <a href="/leistungen/audit-readiness.php" class="card service-card">
                <span class="card-icon"><i data-icon="clipboard-check" class="lucide"></i></span>
                <h3 class="card-title">Audit Readiness Assessment</h3>
                <p class="card-text">
                    Die Prüfung vor der Prüfung: Stichproben, Nachweislage, Interviewtraining –
                    bevor es der externe Prüfer feststellt.
                </p>
                <div class="card-foot">
                    <p class="service-price">16.000 – 29.000 €<small>4–6 Wochen</small></p>
                </div>
            </a>

            <a href="/leistungen/it-operating-model.php" class="card service-card">
                <span class="card-icon"><i data-icon="layers" class="lucide"></i></span>
                <h3 class="card-title">IT Operating Model</h3>
                <p class="card-text">
                    Wie die IT künftig aufgestellt, besetzt und gesteuert wird – über Standorte
                    und Gesellschaften hinweg, mit Übergangsplan.
                </p>
                <div class="card-foot">
                    <p class="service-price">ab 32.000 €<small>3–5 Monate</small></p>
                </div>
            </a>

            <a href="/leistungen/demand-management.php" class="card service-card">
                <span class="card-icon"><i data-icon="inbox" class="lucide"></i></span>
                <h3 class="card-title">IT Demand Management</h3>
                <p class="card-text">
                    Ein Weg für alle Anforderungen: erfassen, bewerten, priorisieren,
                    entscheiden – nachvollziehbar bis zur Umsetzung.
                </p>
                <div class="card-foot">
                    <p class="service-price">18.000 – 34.000 €<small>2–4 Monate</small></p>
                </div>
            </a>

            <a href="/leistungen/governance-betreuung.php" class="card service-card">
                <span class="card-icon"><i data-icon="repeat" class="lucide"></i></span>
                <h3 class="card-title">Laufende Governance-Betreuung</h3>
                <p class="card-text">
                    Ein fester Tag im Monat: Maßnahmen nachhalten, Kontrollen prüfen, Berichte
                    vorbereiten, Prüfer begleiten.
                </p>
                <div class="card-foot">
                    <p class="service-price">ab 2.800 € / Monat<small>Laufzeit ab 6 Monaten</small></p>
                </div>
            </a>
        </div>

        <p style="margin-top:2rem;">
            <a href="/leistungen/" class="btn-secondary">Alle elf Leistungen im Überblick</a>
            <a href="/preise.php" class="btn-secondary">Preise und Investitionsrahmen</a>
        </p>
    </div>
</section>

<!-- Regulatorik ---------------------------------------------------------- -->
<section class="section section-navy">
    <div class="container">
        <div class="section-head is-wide">
            <span class="section-kicker">Regulatorischer Druck</span>
            <h2 class="section-title">Die Anforderungen kommen jetzt von außen – und sie kommen zu mehreren</h2>
            <p class="section-lead">
                NIS2 über die Lieferkette, DORA über Kunden aus dem Finanzsektor, ISO 27001 über
                Ausschreibungen, Konzernvorgaben über die Muttergesellschaft, Fragebögen über
                Cyberversicherer. Die gute Nachricht: Die Anforderungen überschneiden sich
                erheblich. Wer die Grundlagen einmal sauber baut, bedient mehrere Adressaten.
            </p>
        </div>

        <div class="card-grid cols-3">
            <a href="/themen/nis2.php" class="card is-navy">
                <span class="card-icon is-accent"><i data-icon="shield-check" class="lucide"></i></span>
                <h3 class="card-title">NIS2</h3>
                <p class="card-text">
                    Betroffenheit, Risikomanagementmaßnahmen, Lieferkette, Meldewege – und die
                    persönliche Verantwortung der Geschäftsleitung.
                </p>
                <div class="card-foot"><span class="text-link" style="color:#E3B383;">Thema ansehen<i data-icon="arrow-right" class="lucide"></i></span></div>
            </a>
            <a href="/themen/dora.php" class="card is-navy">
                <span class="card-icon is-accent"><i data-icon="euro" class="lucide"></i></span>
                <h3 class="card-title">DORA</h3>
                <p class="card-text">
                    Relevant für Finanzunternehmen – und für alle, die IT-Dienstleistungen an
                    den Finanzsektor liefern und plötzlich vertraglich in der Pflicht stehen.
                </p>
                <div class="card-foot"><span class="text-link" style="color:#E3B383;">Thema ansehen<i data-icon="arrow-right" class="lucide"></i></span></div>
            </a>
            <a href="/themen/iso-27001.php" class="card is-navy">
                <span class="card-icon is-accent"><i data-icon="award" class="lucide"></i></span>
                <h3 class="card-title">ISO/IEC 27001</h3>
                <p class="card-text">
                    Der Weg zum Zertifikat – und die ehrliche Frage, ob Sie es wirklich brauchen
                    oder ob die Struktur dahinter genügt.
                </p>
                <div class="card-foot"><span class="text-link" style="color:#E3B383;">Thema ansehen<i data-icon="arrow-right" class="lucide"></i></span></div>
            </a>
        </div>

        <div class="callout is-legal" style="margin-top:2.4rem;">
            <span class="callout-icon"><i data-icon="scale" class="lucide"></i></span>
            <div class="callout-body">
                <h3 class="callout-title">Keine Rechtsberatung – bewusst</h3>
                <p>
                    Ob eine Regulierung für Ihr Unternehmen gilt, ist eine Rechtsfrage. Diese
                    beantwortet eine Kanzlei, nicht ich. Meine Aufgabe beginnt danach: die
                    Anforderungen organisatorisch umsetzen, Prozesse und Kontrollen aufbauen,
                    Nachweise erzeugen, Prüfungen vorbereiten. Diese Arbeitsteilung ist kein
                    Haftungsausschluss im Kleingedruckten, sondern Teil der Positionierung.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Vorgehen ------------------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="split is-top">
            <div>
                <span class="section-kicker">Vorgehen</span>
                <h2 class="section-title">Vier Schritte, die immer gleich sind</h2>
                <p>
                    Egal ob Quick Assessment oder Operating Model: Das Vorgehen folgt derselben
                    Logik. Erst verstehen, dann bewerten, dann entscheiden lassen, dann umsetzen
                    und nachhalten. Der Unterschied liegt in der Tiefe, nicht im Ablauf.
                </p>
                <a href="/vorgehen.php" class="btn-secondary">Vorgehen im Detail</a>
            </div>
            <div>
                <ol class="steps">
                    <li>
                        <h3>Verstehen</h3>
                        <p>Dokumente sichten, Interviews führen, Systeme ansehen. Nicht, was im
                           Konzept steht, sondern was Montagmorgen tatsächlich passiert.</p>
                    </li>
                    <li>
                        <h3>Bewerten</h3>
                        <p>Gegen einen definierten Rahmen prüfen: Was fehlt, was ist vorhanden
                           aber nicht belegbar, was ist unnötig aufwendig? Mit Reifegrad je Feld.</p>
                    </li>
                    <li>
                        <h3>Entscheiden lassen</h3>
                        <p>Maßnahmen mit Aufwand, Wirkung und Reihenfolge – als Vorlage für die
                           Geschäftsführung, nicht als Wunschliste der IT.</p>
                    </li>
                    <li>
                        <h3>Umsetzen und nachhalten</h3>
                        <p>Begleitung bei der Einführung, bis die Struktur ohne mich weiterläuft.
                           Das ist das Ziel – nicht ein Dauermandat.</p>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Abgrenzung ----------------------------------------------------------- -->
<section class="section section-paper section-line">
    <div class="container">
        <div class="section-head is-center">
            <span class="section-kicker">Ehrliche Abgrenzung</span>
            <h2 class="section-title">Wofür Sie mich holen sollten – und wofür nicht</h2>
        </div>

        <div class="versus">
            <div class="versus-col is-good">
                <h3><i data-icon="check-circle" class="lucide"></i> Passt</h3>
                <ul class="checklist is-tight">
                    <li>Die IT ist gewachsen, die Steuerung nicht mitgewachsen.</li>
                    <li>Ein Audit steht an oder hat Feststellungen hinterlassen.</li>
                    <li>Regulatorischer Druck nimmt zu (NIS2, Kunden, Konzern, Versicherung).</li>
                    <li>Mehrere Standorte oder Gesellschaften sollen harmonisiert werden.</li>
                    <li>Verantwortlichkeiten sind unklar oder hängen an Einzelpersonen.</li>
                    <li>Es gibt keinen eigenen Spezialisten für IT-Governance im Haus.</li>
                    <li>Die Geschäftsführung will die IT steuern statt nur bezahlen.</li>
                </ul>
            </div>
            <div class="versus-col is-bad">
                <h3><i data-icon="x" class="lucide"></i> Passt nicht</h3>
                <ul class="checklist is-cross is-tight">
                    <li>Sie brauchen eine rechtliche Bewertung – das gehört zu einer Kanzlei.</li>
                    <li>Sie suchen ein Zertifikat ohne die Substanz dahinter.</li>
                    <li>Sie brauchen operative Manpower, die Tickets abarbeitet.</li>
                    <li>Sie suchen einen günstigen Tagessatz für unbestimmte Arbeit.</li>
                    <li>Sie wollen ein Gutachten, das eine bereits getroffene Entscheidung stützt.</li>
                    <li>Es gibt im Haus niemanden, der Ergebnisse übernimmt und weiterträgt.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Person --------------------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="split is-top">
            <div>
                <span class="section-kicker">Wer arbeitet</span>
                <h2 class="section-title">Sie bekommen den, mit dem Sie gesprochen haben</h2>
                <p class="lead">
                    Kein Juniorteam, keine Weitergabe an einen Praktikanten mit Vorlage. Die
                    Person, die im Erstgespräch sitzt, ist die Person, die im Projekt arbeitet.
                </p>
                <p>
                    Mein Hintergrund: BWL-Studium, Master Wirtschaftsinformatik, seit Jahren im
                    IT-Umfeld eines international tätigen Konzerns – IT Business Analysis,
                    ERP-Umfeld (Infor LN), IT-Prozessmanagement, Prozessharmonisierung über
                    mehrere Gesellschaften und Vertriebsgesellschaften hinweg, Aufbau eines
                    IT-Service-Katalogs und Einführung eines globalen IT-Demand-Prozesses.
                </p>
                <p>
                    Das heißt konkret: Ich habe diese Prozesse nicht nur beschrieben, sondern
                    eingeführt – gegen Widerstände, mit Betriebsrat, über Ländergrenzen, mit
                    Menschen, die zu Recht fragen, warum sie ihre Arbeitsweise ändern sollen.
                </p>
                <a href="/ueber-mich.php" class="text-link">Vollständiger Hintergrund<i data-icon="arrow-right" class="lucide"></i></a>
            </div>
            <div>
                <div class="card is-paper">
                    <h3 class="card-title">Was das für Ihr Projekt bedeutet</h3>
                    <ul class="checklist">
                        <li><strong>Fachliche Tiefe statt Folienwissen.</strong> Ich kann mit Ihrem
                            ERP-Team über Berechtigungskonzepte sprechen und mit dem Prüfer über
                            Nachweise.</li>
                        <li><strong>Erfahrung mit Konzernvorgaben.</strong> Wenn eine Muttergesellschaft
                            Vorgaben macht, kenne ich beide Seiten des Tisches.</li>
                        <li><strong>Verständnis für Datenlage.</strong> SQL, Oracle, MySQL, Tableau –
                            Kennzahlen müssen aus vorhandenen Systemen kommen, nicht aus Excel-Pflege.</li>
                        <li><strong>Grenzen werden benannt.</strong> Wo ich nicht der Richtige bin,
                            sage ich das im Erstgespräch, nicht im dritten Projektmonat.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Wissen --------------------------------------------------------------- -->
<section class="section section-paper section-line">
    <div class="container">
        <div class="section-head">
            <span class="section-kicker">Wissensbereich</span>
            <h2 class="section-title">Ausführlich, kostenlos, ohne Anmeldung</h2>
            <p class="section-lead">
                Wer die Themen selbst umsetzen kann, soll das tun. Die Leitfäden hier sind so
                geschrieben, dass das möglich ist – ohne Datenabgabe, ohne Newsletter-Zwang.
            </p>
        </div>

        <div class="card-grid cols-3">
            <a href="/wissen/reifegradmodell.php" class="card">
                <span class="card-icon is-soft"><i data-icon="bar-chart" class="lucide"></i></span>
                <h3 class="card-title">Reifegrad-Selbsteinschätzung</h3>
                <p class="card-text">Zwölf Fragen, fünf Stufen, ehrliches Ergebnis – rechnet im Browser, sendet nichts.</p>
                <div class="card-foot"><span class="text-link">Zum Selbsttest<i data-icon="arrow-right" class="lucide"></i></span></div>
            </a>
            <a href="/wissen/audit-vorbereitung.php" class="card">
                <span class="card-icon is-soft"><i data-icon="calendar-check" class="lucide"></i></span>
                <h3 class="card-title">Die 12 Wochen vor der Prüfung</h3>
                <p class="card-text">Ein Wochenplan für die Audit-Vorbereitung: Was wann zu tun ist, damit es nicht hektisch wird.</p>
                <div class="card-foot"><span class="text-link">Leitfaden lesen<i data-icon="arrow-right" class="lucide"></i></span></div>
            </a>
            <a href="/wissen/nis2-betroffenheit.php" class="card">
                <span class="card-icon is-soft"><i data-icon="help-circle" class="lucide"></i></span>
                <h3 class="card-title">NIS2-Betroffenheit prüfen</h3>
                <p class="card-text">In sechs Schritten zu einer belastbaren Einschätzung – und zur Frage, wann die Kanzlei übernimmt.</p>
                <div class="card-foot"><span class="text-link">Leitfaden lesen<i data-icon="arrow-right" class="lucide"></i></span></div>
            </a>
        </div>
    </div>
</section>

<!-- Fragen --------------------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="split is-top">
            <div>
                <span class="section-kicker">Häufige Fragen</span>
                <h2 class="section-title">Was Interessenten zuerst fragen</h2>
                <p>
                    Die vollständige Liste steht auf der Seite mit den häufigen Fragen – von
                    Vertraulichkeit über Zusammenarbeit mit vorhandenen Dienstleistern bis zur
                    Frage, was passiert, wenn das Projekt vorbei ist.
                </p>
                <a href="/faq.php" class="btn-secondary">Alle Fragen ansehen</a>
            </div>

            <div>
                <div class="faq-list">
                    <div class="faq-item">
                        <button class="faq-question" type="button" aria-expanded="false">
                            <span class="faq-number">01</span>
                            <span class="faq-question-text">Wir sind 400 Mitarbeitende – ist IT-Governance für uns nicht überdimensioniert?</span>
                        </button>
                        <div class="faq-answer" aria-hidden="true">
                            <p>
                                Der Begriff klingt nach Konzern, die Sache selbst nicht. Es geht um
                                fünf bis fünfzehn Seiten Regelung, ein klares Rollenbild, vier bis
                                sechs beschriebene Prozesse und eine Handvoll Kontrollen. Was
                                überdimensioniert wäre, ist ein Konzern-Framework mit 40 Prozessen.
                                Genau deshalb ist der Zuschnitt Teil der Arbeit.
                            </p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" type="button" aria-expanded="false">
                            <span class="faq-number">02</span>
                            <span class="faq-question-text">Was kostet der Einstieg?</span>
                        </button>
                        <div class="faq-answer" aria-hidden="true">
                            <p>
                                Das Erstgespräch kostet nichts und dauert 30 Minuten. Die kleinste
                                echte Leistung ist das Quick Assessment ab 4.900 € netto. Die
                                häufigsten Projekte – Gap-Analyse oder Audit Readiness – liegen
                                zwischen 14.500 € und 29.000 € netto. Alle Rahmen stehen offen auf
                                der <a href="/preise.php">Preisseite</a>.
                            </p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" type="button" aria-expanded="false">
                            <span class="faq-number">03</span>
                            <span class="faq-question-text">Machen Sie auch die Umsetzung oder nur Konzepte?</span>
                        </button>
                        <div class="faq-answer" aria-hidden="true">
                            <p>
                                Beides, aber mit klarer Rollenteilung: Ich baue die Struktur, moderiere
                                die Entscheidungen und begleite die Einführung. Die operative Arbeit
                                bleibt in Ihrem Haus – sonst entsteht Abhängigkeit statt Fähigkeit.
                                Für die Phase nach der Einführung gibt es die
                                <a href="/leistungen/governance-betreuung.php">laufende Betreuung</a>.
                            </p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" type="button" aria-expanded="false">
                            <span class="faq-number">04</span>
                            <span class="faq-question-text">Ersetzen Sie unseren Datenschutzbeauftragten oder unsere Kanzlei?</span>
                        </button>
                        <div class="faq-answer" aria-hidden="true">
                            <p>
                                Nein. Rechtliche Bewertungen gehören zu zugelassenen Rechtsanwältinnen
                                und Rechtsanwälten, Datenschutz zum DSB. Ich arbeite beiden zu: Wenn
                                die Kanzlei sagt, was gilt, sorge ich dafür, dass die Organisation es
                                umsetzt und belegen kann.
                            </p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" type="button" aria-expanded="false">
                            <span class="faq-number">05</span>
                            <span class="faq-question-text">Wie viel Zeit kostet uns das intern?</span>
                        </button>
                        <div class="faq-answer" aria-hidden="true">
                            <p>
                                Beim Quick Assessment etwa vier bis sechs Stunden auf Ihrer Seite,
                                verteilt auf Interviews. Bei einer Gap-Analyse zwei bis vier Stunden
                                pro Woche, hauptsächlich beim IT-Leiter. Wer weniger investiert,
                                bekommt ein Ergebnis, das an der Realität vorbeigeht – das sage ich
                                lieber vorher.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
