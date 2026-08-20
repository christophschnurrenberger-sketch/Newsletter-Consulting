<?php
$page = [
    'title'       => 'Reifegrad der IT-Governance: Selbsteinschätzung in 12 Fragen',
    'description' => 'Selbsttest zum Reifegrad der IT-Governance: zwölf Fragen zu Steuerung, Rollen, Prozessen, Kontrollen, Dokumentation und Kennzahlen. Rechnet im Browser, sendet keine Daten, liefert eine Stufe von 1 bis 5 mit Empfehlung.',
    'section'     => 'wissen',
    'path'        => 'wissen/reifegradmodell.php',
    'crumbs'      => [['Wissen', 'wissen/'], ['Reifegradmodell', null]],
    'hero'        => [
        'kicker' => 'Selbsttest · 8 Minuten',
        'h1'     => 'Wo steht Ihre IT-Governance – <span class="accent">ehrlich betrachtet?</span>',
        'lead'   => 'Zwölf Fragen, fünf Stufen. Der Test rechnet ausschließlich in Ihrem Browser: Es werden keine Daten übertragen, gespeichert oder ausgewertet. Sie können die Seite anschließend schließen, und niemand weiß, dass Sie hier waren.',
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Dauer', 'rund 8 Minuten'],
    ['Datenübertragung', 'keine'],
    ['Ergebnis', 'Stufe 1–5 mit Empfehlung'],
];
$asideCta = [
    'title' => 'Zweite Meinung?',
    'text'  => 'Selbsteinschätzungen fallen erfahrungsgemäß eine halbe Stufe zu gut aus. Ein Quick Assessment liefert die Außensicht.',
    'link'  => ['Quick Assessment ansehen', 'leistungen/quick-assessment.php'],
];

/* Die Fragen stehen als Daten hier, damit die Ausgabe kurz bleibt. */
$fragen = [
    ['Steuerung', 'Es gibt ein Gremium, das regelmäßig über IT-Themen entscheidet, und die Beschlüsse werden protokolliert.'],
    ['Entscheidungswege', 'Es ist schriftlich festgelegt, wer welche IT-Entscheidungen treffen darf – inklusive Betragsgrenzen.'],
    ['Rollen', 'Zentrale IT-Rollen sind benannt, besetzt und vertreten; die Zuständigkeit hängt nicht an einzelnen Personen.'],
    ['Regelungen', 'Es gibt freigegebene IT-Richtlinien, die aktuell sind und den Betroffenen bekannt.'],
    ['Anforderungen', 'Neue Anforderungen an die IT laufen über einen definierten Weg und werden nach Kriterien priorisiert.'],
    ['Services', 'Wir können benennen, welche IT-Services wir erbringen, wer sie verantwortet und wie kritisch sie sind.'],
    ['Änderungen', 'Änderungen an Produktivsystemen werden genehmigt und dokumentiert – auch die kleinen.'],
    ['Berechtigungen', 'Berechtigungen werden geordnet vergeben, bei Wechsel angepasst, bei Austritt entzogen und regelmäßig überprüft.'],
    ['Risiken', 'Es gibt eine aktuelle IT-Risikoübersicht, die von der Geschäftsführung zur Kenntnis genommen wird.'],
    ['Notfall', 'Wiederanlaufziele sind mit dem Geschäft abgestimmt, und der Wiederanlauf wurde tatsächlich getestet.'],
    ['Dienstleister', 'Wir haben eine vollständige Übersicht der IT-Dienstleister und bewerten die wichtigen regelmäßig.'],
    ['Kennzahlen', 'Die Geschäftsführung erhält regelmäßig einen IT-Bericht mit Kennzahlen und Entscheidungsbedarf.'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Die fünf Stufen</h2>
                <p>
                    Bevor Sie antworten: Stufe 5 ist kein Ziel. Für die meisten mittelständischen
                    Unternehmen ist ein Durchschnitt zwischen 3,0 und 3,5 genau richtig – mit
                    Stufe 4 bei den prüfungsrelevanten Punkten.
                </p>

                <div class="maturity">
                    <div class="maturity-step">
                        <span class="maturity-num">1</span>
                        <div><h3>Zufällig</h3><p>Findet statt, aber jedes Mal anders. Ergebnis hängt an Personen.</p></div>
                    </div>
                    <div class="maturity-step">
                        <span class="maturity-num">2</span>
                        <div><h3>Ansatzweise</h3><p>Eingespielte Praxis, mündlich weitergegeben, nirgends festgehalten.</p></div>
                    </div>
                    <div class="maturity-step is-target">
                        <span class="maturity-num">3</span>
                        <div><h3>Definiert</h3><p>Beschrieben, freigegeben, bekannt – neue Mitarbeitende können danach arbeiten.</p></div>
                    </div>
                    <div class="maturity-step is-target">
                        <span class="maturity-num">4</span>
                        <div><h3>Gesteuert</h3><p>Wird gemessen, Abweichungen fallen auf, Nachweise entstehen im Betrieb.</p></div>
                    </div>
                    <div class="maturity-step">
                        <span class="maturity-num">5</span>
                        <div><h3>Optimierend</h3><p>Wird systematisch verbessert; Kennzahlen lösen Veränderungen aus.</p></div>
                    </div>
                </div>

                <h2>Die zwölf Fragen</h2>
                <p>
                    Antworten Sie so, wie es heute wirklich ist – nicht so, wie es im Konzept
                    steht oder gemeint war. Der Test ist nur so viel wert wie die Ehrlichkeit
                    beim Ausfüllen, und er sieht niemand außer Ihnen.
                </p>

                <form id="reifegrad-check" class="selfcheck" novalidate>
<?php foreach ($fragen as $i => $frage): ?>
                    <div class="check-item">
                        <label for="frage-<?= $i ?>">
                            <span class="check-topic"><?= e($frage[0]) ?></span>
                            <?= e($frage[1]) ?>
                        </label>
                        <select id="frage-<?= $i ?>" class="form-select">
                            <option value="0">Bitte wählen</option>
                            <option value="1">1 – trifft nicht zu</option>
                            <option value="2">2 – ansatzweise, nichts festgehalten</option>
                            <option value="3">3 – beschrieben, freigegeben, bekannt</option>
                            <option value="4">4 – zusätzlich gemessen und belegt</option>
                            <option value="5">5 – wird systematisch verbessert</option>
                        </select>
                    </div>
<?php endforeach; ?>
                </form>

                <div class="card is-navy" style="margin-top:2rem;">
                    <p class="aside-title" style="color:rgba(255,255,255,.6);">Ihr Ergebnis</p>
                    <p style="font-family:var(--serif); font-size:3rem; line-height:1; color:#fff; margin:0 0 .4rem;">
                        <span id="reifegrad-score">–</span>
                    </p>
                    <p style="color:#E3B383; font-weight:700; margin-bottom:.8rem;" id="reifegrad-level">Noch keine Antwort</p>
                    <p id="reifegrad-text" style="margin-bottom:0;">
                        Beantworten Sie die Fragen so, wie es heute wirklich ist – nicht so, wie
                        es im Konzept steht.
                    </p>
                </div>

                <h2>Wie das Ergebnis zu lesen ist</h2>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Durchschnitt</th><th scope="col">Bedeutung</th><th scope="col">Sinnvoller nächster Schritt</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="num">bis 1,4</td>
                                <td>Kaum Struktur, alles personengebunden. Eine Prüfung würde deutliche Feststellungen erzeugen.</td>
                                <td><a href="/leistungen/quick-assessment.php">Quick Assessment</a>, danach Rollen und Dokumentation</td>
                            </tr>
                            <tr>
                                <td class="num">1,5 – 2,4</td>
                                <td>Einzelne Bereiche geordnet, andere gar nicht – der Normalfall bei gewachsener IT.</td>
                                <td><a href="/leistungen/gap-analyse.php">Gap-Analyse</a> zur Priorisierung</td>
                            </tr>
                            <tr>
                                <td class="num">2,5 – 3,4</td>
                                <td>Regelungen vorhanden und bekannt. Was meist fehlt: Nachweise und Kennzahlen.</td>
                                <td><a href="/leistungen/kontrollframework.php">Kontrollframework</a>, dann <a href="/leistungen/audit-readiness.php">Audit Readiness</a></td>
                            </tr>
                            <tr>
                                <td class="num">3,5 – 4,4</td>
                                <td>Gesteuerte IT mit Nachweisen. Gute Ausgangslage für Zertifizierung.</td>
                                <td>Feinschliff über die <a href="/leistungen/governance-betreuung.php">laufende Betreuung</a></td>
                            </tr>
                            <tr>
                                <td class="num">ab 4,5</td>
                                <td>Etabliert und selbst weiterentwickelt.</td>
                                <td>Externe Beratung nur punktuell – etwa als zweite Meinung</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout is-warning">
                    <span class="callout-icon"><i data-icon="eye" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Selbsteinschätzungen fallen zu gut aus</h3>
                        <p>
                            Das ist keine Unterstellung, sondern Erfahrung: In der Gegenüberstellung
                            mit einer externen Bewertung liegt die Selbsteinschätzung typischerweise
                            eine halbe bis eine ganze Stufe höher. Der Grund ist harmlos – man
                            bewertet die Absicht mit, nicht nur den Nachweis. Rechnen Sie also
                            0,5 Stufen ab, wenn Sie das Ergebnis für eine Entscheidung nutzen.
                        </p>
                    </div>
                </div>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['leistungen/quick-assessment.php', 'wissen/it-governance-mittelstand.php', 'leistungen/gap-analyse.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
