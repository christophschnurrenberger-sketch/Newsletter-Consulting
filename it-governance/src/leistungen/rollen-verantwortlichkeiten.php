<?php
$page = [
    'title'       => 'IT-Rollen- und Verantwortlichkeitsmodell',
    'description' => 'Rollenmodell für die IT mit RACI-Zuordnung, Vertretungsregelungen und Stellenbeschreibungen. Verantwortung wird von Personen auf Rollen übertragen – prüfungsfest und alltagstauglich. 9.500 bis 19.000 € netto.',
    'section'     => 'leistungen',
    'path'        => 'leistungen/rollen-verantwortlichkeiten.php',
    'crumbs'      => [['Leistungen', 'leistungen/'], ['Rollen & Verantwortlichkeiten', null]],
    'hero'        => [
        'kicker' => 'Leistung · Aufbau',
        'h1'     => 'Verantwortung gehört zur Rolle, <span class="accent">nicht zur Person</span>',
        'lead'   => 'Solange „das macht der Thomas“ die Antwort auf eine Zuständigkeitsfrage ist, hat die IT kein Rollenmodell, sondern ein Personenrisiko. Dieses Projekt überträgt Verantwortung auf Rollen – ohne dass jemand entmachtet wird.',
        'actions' => [
            ['Rollenmodell anfragen', 'kontakt.php', 'primary'],
            ['RACI-Leitfaden lesen', 'wissen/it-governance-mittelstand.php', 'ghost'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Dauer', '6–10 Wochen'],
    ['Aufwand bei Ihnen', '3–4 Std./Woche'],
    ['Ergebnis', 'Rollenmodell + RACI + Vertretung'],
    ['Preis', '9.500 – 19.000 € netto'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Das Problem hinter dem Problem</h2>
                <p>
                    Unklare Verantwortlichkeiten sind selten ein Organisationsversäumnis. Sie
                    sind meist das Ergebnis von Wachstum: Menschen sind hineingewachsen, haben
                    sich Themen genommen, weil es sonst niemand tat, und irgendwann kannte jeder
                    die informelle Landkarte. Sie funktioniert – bis jemand kündigt, ausfällt,
                    Urlaub hat oder ein Prüfer fragt, wer eine Berechtigung genehmigt hat.
                </p>
                <p>
                    Deshalb ist dieses Projekt kein Restrukturierungsprojekt. Niemand verliert
                    Aufgaben. Es wird nur aufgeschrieben, was ohnehin gilt – und dort ergänzt,
                    wo eine Lücke sichtbar wird.
                </p>

                <h2>Welche Rollen ein mittelständischer IT-Bereich braucht</h2>
                <p>
                    Rollen sind keine Stellen. Eine Person kann mehrere Rollen tragen, das ist im
                    Mittelstand die Regel. Wichtig ist nur, dass unvereinbare Rollen getrennt
                    bleiben – wer eine Berechtigung beantragt, darf sie nicht selbst genehmigen.
                </p>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Rolle</th><th scope="col">Verantwortet</th><th scope="col">Typisch besetzt durch</th></tr>
                        </thead>
                        <tbody>
                            <tr><td><strong>IT-Leitung</strong></td><td>Gesamtsteuerung, Budget, Eskalation, Bericht an die Geschäftsführung</td><td>eigene Stelle</td></tr>
                            <tr><td><strong>Informationssicherheits&shy;beauftragter</strong></td><td>Sicherheitsanforderungen, Risiken, Sensibilisierung, Vorfallbewertung</td><td>eigene Stelle oder extern</td></tr>
                            <tr><td><strong>Service-Verantwortlicher</strong></td><td>Ein Service über den Lebenszyklus: Verfügbarkeit, Kosten, Änderungen</td><td>Teamleitung, Fachadministrator</td></tr>
                            <tr><td><strong>Anwendungs&shy;verantwortlicher</strong></td><td>Eine Anwendung fachlich: Berechtigungen, Releases, Anforderungen</td><td>Fachbereich, nicht IT</td></tr>
                            <tr><td><strong>Dateneigentümer</strong></td><td>Schutzbedarf, Freigabe von Zugriffen, Aufbewahrung</td><td>Fachbereichsleitung</td></tr>
                            <tr><td><strong>Change-Freigeber</strong></td><td>Genehmigung von Änderungen am Produktivbetrieb</td><td>IT-Leitung, Vertretung geregelt</td></tr>
                            <tr><td><strong>Dienstleister&shy;verantwortlicher</strong></td><td>Steuerung eines Providers: Leistung, Sicherheit, Vertrag</td><td>IT-Leitung, Einkauf beteiligt</td></tr>
                            <tr><td><strong>Notfall&shy;koordinator</strong></td><td>Alarmierung, Wiederanlaufsteuerung, Kommunikation im Notfall</td><td>IT-Leitung oder Stellvertretung</td></tr>
                        </tbody>
                    </table>
                </div>

                <h2>RACI – nützlich, wenn man es kurz hält</h2>
                <p>
                    RACI (verantwortlich, rechenschaftspflichtig, konsultiert, informiert) ist
                    ein bewährtes Werkzeug und wird trotzdem oft ruiniert: Matrizen mit 80 Zeilen
                    liest niemand. Sinnvoll sind 15 bis 25 Aktivitäten – die, bei denen es in der
                    Vergangenheit tatsächlich geklemmt hat oder bei denen ein Prüfer nachfragt.
                </p>
                <div class="table-wrap">
                    <table class="data-table">
                        <caption>Auszug aus einer typischen RACI-Zuordnung. A = rechenschaftspflichtig, R = ausführend, C = konsultiert, I = informiert.</caption>
                        <thead>
                            <tr>
                                <th scope="col">Aktivität</th>
                                <th scope="col">Geschäfts&shy;führung</th>
                                <th scope="col">IT-Leitung</th>
                                <th scope="col">Fachbereich</th>
                                <th scope="col">Dienstleister</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>IT-Budget freigeben</td><td class="yes">A</td><td>R</td><td>C</td><td>–</td></tr>
                            <tr><td>Neue Anwendung genehmigen</td><td class="yes">A</td><td>C</td><td>R</td><td>–</td></tr>
                            <tr><td>Berechtigung genehmigen</td><td>–</td><td>C</td><td class="yes">A</td><td>R</td></tr>
                            <tr><td>Change produktiv setzen</td><td>–</td><td class="yes">A</td><td>C</td><td>R</td></tr>
                            <tr><td>Risiko akzeptieren</td><td class="yes">A</td><td>R</td><td>C</td><td>I</td></tr>
                            <tr><td>Notfall ausrufen</td><td>I</td><td class="yes">A</td><td>I</td><td>R</td></tr>
                            <tr><td>Dienstleisterleistung prüfen</td><td>I</td><td class="yes">A</td><td>C</td><td>–</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout is-warning">
                    <span class="callout-icon"><i data-icon="alert-triangle" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Die eine Regel, an der sich alles entscheidet</h3>
                        <p>
                            Je Aktivität genau ein A. Sobald zwei Personen rechenschaftspflichtig
                            sind, ist es niemand. Diese Diskussion dauert im Workshop oft eine
                            halbe Stunde je Zeile – und genau diese halbe Stunde ist der Wert des
                            Projekts, nicht die fertige Tabelle.
                        </p>
                    </div>
                </div>

                <h2>Vertretung: der Teil, den alle vergessen</h2>
                <p>
                    Ein Rollenmodell ohne Vertretungsregelung hält bis zum ersten längeren
                    Ausfall. Deshalb gehört zu jeder Rolle:
                </p>
                <ul class="checklist">
                    <li>eine benannte Vertretung mit Namen, nicht „das Team“</li>
                    <li>die Angabe, ab wann die Vertretung greift (sofort, ab drei Tagen, ab einer Woche)</li>
                    <li>welche Entscheidungen die Vertretung treffen darf und welche warten</li>
                    <li>die technischen Voraussetzungen: Zugänge, Notfallkonten, Schlüssel, Passwortverwaltung</li>
                    <li>ein Test: einmal jährlich übernimmt die Vertretung bewusst für eine Woche</li>
                </ul>

                <h2>Ablauf und Ergebnis</h2>
                <ol class="steps">
                    <li>
                        <h3>Ist-Aufnahme (2 Wochen)</h3>
                        <p>Wer macht heute was? Erhoben über Interviews und über Systemdaten –
                           wer genehmigt in welchen Systemen tatsächlich, wer hat administrative
                           Rechte, wer steht in welchem Verteiler.</p>
                    </li>
                    <li>
                        <h3>Rollenschnitt (2 Wochen)</h3>
                        <p>Entwurf des Rollenmodells inklusive Trennung unvereinbarer Rollen und
                           Abgleich mit vorhandenen Stellenbeschreibungen.</p>
                    </li>
                    <li>
                        <h3>RACI-Workshops (2–3 Termine)</h3>
                        <p>Moderierte Abstimmung der strittigen Aktivitäten. Hier entstehen die
                           Entscheidungen, hier wird auch Widerstand sichtbar – besser jetzt als
                           im Ernstfall.</p>
                    </li>
                    <li>
                        <h3>Verabschiedung und Übergabe</h3>
                        <p>Freigabe durch die Geschäftsführung, Aufnahme in die
                           IT-Organisationsrichtlinie, Kommunikation an alle Beteiligten,
                           Wiedervorlage nach zwölf Monaten.</p>
                    </li>
                </ol>

                <p>
                    <strong>Preis:</strong> 9.500 € netto für einen Standort mit bis zu
                    15 Rollen, 14.000 € im Regelfall, bis 19.000 € bei mehreren Gesellschaften
                    mit unterschiedlichen Strukturen und Abstimmung mit HR und Betriebsrat.
                </p>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['leistungen/governance-framework.php', 'leistungen/kontrollframework.php', 'themen/it-dokumentation.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
