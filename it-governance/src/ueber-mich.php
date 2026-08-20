<?php
$page = [
    'title'       => 'Über mich: Christoph Schnurrenberger',
    'description' => 'Wirtschaftsinformatiker (M. Sc.) mit BWL-Hintergrund und Praxis aus dem internationalen Konzernumfeld: IT Business Analysis, ERP (Infor LN), IT-Prozessmanagement, Service-Katalog, globales Demand Management, Prozessharmonisierung über mehrere Gesellschaften.',
    'section'     => 'ueber-mich',
    'path'        => 'ueber-mich.php',
    'crumbs'      => [['Über mich', null]],
    'hero'        => [
        'kicker' => 'Über mich',
        'h1'     => 'Ich habe diese Prozesse nicht nur beschrieben – <span class="accent">ich habe sie eingeführt</span>',
        'lead'   => 'Der Unterschied klingt klein und ist erheblich. Ein Prozess zu entwerfen dauert Wochen. Ihn gegen Gewohnheiten, Sonderwege und berechtigte Einwände tatsächlich in Betrieb zu bringen, dauert Monate – und genau daran scheitern die meisten Governance-Projekte.',
        'actions' => [
            ['Erstgespräch vereinbaren', 'kontakt.php', 'primary'],
            ['Vorgehen ansehen', 'vorgehen.php', 'ghost'],
        ],
    ],
];
include __DIR__ . '/partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Hintergrund</h2>
                <p>
                    Ich bin Christoph Schnurrenberger, Wirtschaftsinformatiker mit
                    betriebswirtschaftlichem Grundstudium – erst BWL, dann der Master in
                    Wirtschaftsinformatik. Diese Kombination beschreibt ziemlich genau, wie ich
                    arbeite: Ich komme von der Frage nach dem geschäftlichen Nutzen und nicht von
                    der Technik, kann mich aber in Systemen und Daten bewegen, ohne auf
                    Übersetzung angewiesen zu sein.
                </p>
                <p>
                    Meine Berufserfahrung stammt aus dem IT-Bereich eines international tätigen
                    Konzerns – ein Umfeld mit mehreren Gesellschaften und Vertriebsgesellschaften,
                    unterschiedlichen Ländern, gewachsenen Arbeitsweisen und der ständigen
                    Spannung zwischen zentraler Vorgabe und lokaler Wirklichkeit. Wer in diesem
                    Umfeld Prozesse einführt, lernt zwei Dinge: dass der fachliche Entwurf der
                    einfache Teil ist, und dass Widerstand fast immer einen nachvollziehbaren
                    Grund hat.
                </p>

                <h2>Womit ich gearbeitet habe</h2>
                <div class="card-grid cols-2">
                    <div class="card is-paper">
                        <span class="card-icon"><i data-icon="git-branch" class="lucide"></i></span>
                        <h3 class="card-title">IT-Prozessmanagement</h3>
                        <p class="card-text">
                            Aufbau und Weiterentwicklung von IT-Prozessen: erheben, schneiden,
                            abstimmen, einführen, nachhalten. Einschließlich der Frage, wie viel
                            Prozess ein Bereich verträgt.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <span class="card-icon"><i data-icon="inbox" class="lucide"></i></span>
                        <h3 class="card-title">Globales IT Demand Handling</h3>
                        <p class="card-text">
                            Einführung eines globalen Demand-Prozesses über Gesellschaften hinweg –
                            von der Erfassung über Bewertung und Priorisierung bis zur
                            Entscheidung im Gremium.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <span class="card-icon"><i data-icon="server" class="lucide"></i></span>
                        <h3 class="card-title">IT Service Management</h3>
                        <p class="card-text">
                            Aufbau eines IT-Service-Katalogs, Definition von Services und
                            Verantwortlichkeiten, Verbindung von Servicebeschreibung und
                            tatsächlichem Betrieb.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <span class="card-icon"><i data-icon="database" class="lucide"></i></span>
                        <h3 class="card-title">ERP und Business Analysis</h3>
                        <p class="card-text">
                            IT Business Analysis im ERP-Umfeld (Infor LN): Anforderungen aufnehmen,
                            fachlich schärfen, in Umsetzung überführen – die Schnittstelle
                            zwischen Fachbereich und IT.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <span class="card-icon"><i data-icon="globe" class="lucide"></i></span>
                        <h3 class="card-title">Prozessharmonisierung</h3>
                        <p class="card-text">
                            Angleichung von Arbeitsweisen über mehrere Gesellschaften und
                            Standorte – mit allem, was dazugehört: Beteiligung, Übersetzung,
                            Ausnahmen, Nachhalten.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <span class="card-icon"><i data-icon="line-chart" class="lucide"></i></span>
                        <h3 class="card-title">Daten und Auswertung</h3>
                        <p class="card-text">
                            SQL, Oracle, MySQL, Tableau. Wichtig, weil Kennzahlen aus vorhandenen
                            Systemen kommen müssen – nicht aus Tabellen, die jemand von Hand
                            pflegt.
                        </p>
                    </div>
                </div>

                <h2>Warum ausgerechnet IT-Governance</h2>
                <p>
                    Weil ich in der Praxis immer wieder demselben Muster begegnet bin: Unternehmen
                    arbeiten sorgfältig, entscheiden vernünftig und tun oft mehr, als sie müssten –
                    können es aber nicht zeigen. Es fehlt keine Technik und selten der gute Wille.
                    Es fehlt die Struktur, die aus guter Arbeit belegbare Arbeit macht.
                </p>
                <p>
                    Dieses Muster wird gerade teuer. Nicht wegen einer einzelnen Regulierung,
                    sondern weil sich mehrere Entwicklungen überlagern: Prüfer schauen genauer auf
                    IT, Kunden geben Anforderungen weiter, Versicherer stellen Fragen, Konzerne
                    machen Vorgaben. Mittelständische Unternehmen brauchen dafür jemanden, der die
                    Anforderungen in eine Organisation übersetzt, die auch ohne Stabsabteilung
                    funktioniert.
                </p>

                <div class="pullquote">
                    Governance im Mittelstand heißt nicht, wie ein Konzern zu arbeiten. Es heißt,
                    die Vorteile kurzer Wege zu behalten und trotzdem belegen zu können, dass
                    Regeln eingehalten werden.
                </div>

                <h2>Wie ich arbeite</h2>
                <ul class="checklist">
                    <li><strong>Ich sage, was ich sehe.</strong> Auch wenn es unangenehm ist, und
                        auch dann, wenn es einen kleineren Auftrag bedeutet.</li>
                    <li><strong>Ich baue nichts, was Sie nicht betreiben können.</strong> Ein
                        Framework, das nach meinem Weggang zusammenbricht, ist kein Erfolg.</li>
                    <li><strong>Ich rede mit allen, nicht nur mit der Leitung.</strong> Die
                        Wahrheit über gelebte Prozesse steht selten im Konzept.</li>
                    <li><strong>Ich bleibe in meiner Spur.</strong> Keine Rechtsberatung, keine
                        Technikverkäufe, keine Prüfungstestate.</li>
                    <li><strong>Ich arbeite selbst.</strong> Kein Angebotstermin mit dem Erfahrenen
                        und danach ein Projektteam, das man nicht kannte.</li>
                </ul>

                <h2>Wohin das führen soll</h2>
                <p>
                    Ich baue diese Beratung bewusst als Spezialistenbetrieb auf, nicht als
                    Full-Service-Haus. Das heißt: ein eng umrissenes Themenfeld, wiederverwendbare
                    Methoden und Vorlagen, klar geschnittene Leistungen – und langfristig ein
                    kleines Team, das dieselbe Tiefe hat. Was es nicht heißt: Wachstum um jeden
                    Preis oder Leistungen, die ich fachlich nicht selbst verantworten kann.
                </p>
                <p>
                    Für Sie als Kunde ist daran vor allem eines relevant: Ich habe kein Interesse
                    an Projekten, die nicht passen. Ein unpassendes Projekt kostet mich mehr –
                    an Zeit, an Ruf und an Freude an der Arbeit –, als es einbringt.
                </p>

                <div class="callout">
                    <span class="callout-icon"><i data-icon="message-circle" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Am schnellsten finden Sie es im Gespräch heraus</h3>
                        <p>
                            Dreißig Minuten genügen, um zu klären, ob wir zusammenpassen. Sie
                            schildern die Lage, ich sage Ihnen offen, ob ich der Richtige bin –
                            und wenn nicht, in welche Richtung ich an Ihrer Stelle suchen würde.
                            <a href="/kontakt.php">Zum Kontaktformular</a>.
                        </p>
                    </div>
                </div>

            </div>

            <aside class="page-aside">
                <div class="aside-card">
                    <h2 class="aside-title">Kurzprofil</h2>
                    <dl class="aside-facts">
                        <div><dt>Name</dt><dd>Christoph Schnurrenberger</dd></div>
                        <div><dt>Abschlüsse</dt><dd>BWL, M. Sc. Wirtschaftsinformatik</dd></div>
                        <div><dt>Schwerpunkt</dt><dd>IT-Governance, IT-Prozesse, Auditfähigkeit</dd></div>
                        <div><dt>Erfahrung aus</dt><dd>Internationales Konzernumfeld</dd></div>
                        <div><dt>Sprachen</dt><dd>Deutsch, Englisch</dd></div>
                        <div><dt>Arbeitsgebiet</dt><dd>DACH, remote und vor Ort</dd></div>
                    </dl>
                </div>

                <!--
                    PORTRÄTFOTO EINFÜGEN
                    Ein Bild wirkt auf dieser Seite mehr als jeder Text. Sobald ein Foto
                    vorliegt: als assets/portrait.jpg ablegen (empfohlen 800 × 1000 Pixel,
                    unter 200 kB) und die folgenden drei Zeilen einkommentieren.

                    <div class="aside-card" style="padding:0; overflow:hidden;">
                        <img src="/assets/portrait.jpg" alt="Christoph Schnurrenberger" style="display:block; width:100%;">
                    </div>
                -->

                <div class="aside-card">
                    <h2 class="aside-title">Fachliche Themen</h2>
                    <div class="tag-row" style="margin-bottom:0;">
                        <a class="tag" href="/themen/nis2.php">NIS2</a>
                        <a class="tag" href="/themen/iso-27001.php">ISO 27001</a>
                        <a class="tag" href="/themen/dora.php">DORA</a>
                        <a class="tag" href="/themen/it-risikomanagement.php">Risiko</a>
                        <a class="tag" href="/themen/it-notfallmanagement.php">Notfall</a>
                        <a class="tag" href="/themen/it-kennzahlen.php">Kennzahlen</a>
                        <a class="tag" href="/themen/prozessharmonisierung.php">Harmonisierung</a>
                    </div>
                </div>

                <div class="aside-card is-dark">
                    <h2 class="aside-title">Direkt sprechen</h2>
                    <p>30 Minuten, kostenlos, ohne Vorbereitung.</p>
                    <a href="/kontakt.php" class="btn-primary-custom btn-on-dark">Erstgespräch vereinbaren</a>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
