<?php
$page = [
    'title'       => 'IT-Governance Gap-Analyse',
    'description' => 'Vollständiger Soll-Ist-Vergleich der IT-Governance gegen einen definierten Rahmen – ISO/IEC 27001, NIS2-Maßnahmen oder Konzernvorgaben. Ergebnis: priorisierte Maßnahmenliste mit Aufwand, Wirkung und Reihenfolge. 14.500 bis 26.000 € netto.',
    'section'     => 'leistungen',
    'path'        => 'leistungen/gap-analyse.php',
    'crumbs'      => [['Leistungen', 'leistungen/'], ['Gap-Analyse', null]],
    'hero'        => [
        'kicker' => 'Leistung · Bewertung',
        'h1'     => 'Die Lücke zwischen Anspruch und <span class="accent">Wirklichkeit</span> – vermessen',
        'lead'   => 'Ein Soll-Ist-Vergleich, der nicht bei der Feststellung stehenbleibt: Jede Lücke bekommt einen Aufwand, eine Wirkung und einen Platz in der Reihenfolge. Damit lässt sich ein Budget beantragen statt ein Problem beschreiben.',
        'actions' => [
            ['Gap-Analyse anfragen', 'kontakt.php', 'primary'],
            ['Quick Assessment vergleichen', 'leistungen/quick-assessment.php', 'ghost'],
        ],
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Dauer', '4–8 Wochen'],
    ['Aufwand bei Ihnen', '2–4 Std./Woche'],
    ['Ergebnis', 'Bericht + Maßnahmenplan'],
    ['Preis', '14.500 – 26.000 € netto'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Das Problem mit den meisten Gap-Analysen</h2>
                <p>
                    Eine Gap-Analyse ist schnell erstellt: Man nimmt einen Anforderungskatalog,
                    hakt ab, was fehlt, und übergibt eine Liste mit 140 roten Zeilen. Das
                    Ergebnis ist formal korrekt und praktisch wertlos – niemand kann 140 Lücken
                    gleichzeitig schließen, und der Bericht landet im Laufwerk.
                </p>
                <p>
                    Diese Gap-Analyse ist anders geschnitten. Sie beantwortet nicht nur, was
                    fehlt, sondern <strong>was zuerst zu tun ist, was es kostet und was es
                    bringt</strong>. Am Ende steht kein Mängelbericht, sondern eine
                    Entscheidungsvorlage, mit der die Geschäftsführung ein Budget freigeben kann.
                </p>

<?php
$kette = [
    ['Rahmen festlegen', 'Wogegen wird verglichen?'],
    ['Ist erheben', 'Interviews, Dokumente, Stichproben'],
    ['Lücken bewerten', 'Risiko und Aufwand je Lücke'],
    ['Priorisieren', 'Reihenfolge mit Begründung'],
    ['Entscheidungsvorlage', 'Budget, Zeitplan, Verantwortliche'],
];
$ketteLabel = 'Ablauf der Gap-Analyse';
include __DIR__ . '/../partials/kette.php';
?>

                <h2>Schritt 1: Wogegen wird eigentlich verglichen?</h2>
                <p>
                    Die wichtigste Entscheidung fällt in der ersten Woche. Ein Soll-Rahmen, der
                    zu groß ist, erzeugt Lücken, die für Ihr Unternehmen keine Rolle spielen.
                    Einer, der zu klein ist, übersieht das, wofür Sie später geprüft werden.
                    Üblich sind vier Ausrichtungen – meist in Kombination:
                </p>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th scope="col">Rahmen</th>
                                <th scope="col">Wann sinnvoll</th>
                                <th scope="col">Umfang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>ISO/IEC 27001</strong> mit Anhang A</td>
                                <td>Zertifizierung geplant oder von Kunden gefordert</td>
                                <td>93 Maßnahmen, gefiltert auf Relevanz</td>
                            </tr>
                            <tr>
                                <td><strong>NIS2-Risikomanagementmaßnahmen</strong></td>
                                <td>Betroffenheit durch die Kanzlei bestätigt oder wahrscheinlich</td>
                                <td>10 Maßnahmenbereiche, organisatorisch ausgelegt</td>
                            </tr>
                            <tr>
                                <td><strong>Konzern- oder Kundenvorgaben</strong></td>
                                <td>Muttergesellschaft oder Großkunde macht Vorgaben</td>
                                <td>nach vorliegendem Katalog</td>
                            </tr>
                            <tr>
                                <td><strong>Prüferorientierter Grundrahmen</strong></td>
                                <td>Wirtschaftsprüfung, IDW-nahe Themen, IT-gestützte Rechnungslegung</td>
                                <td>Zugriffe, Änderungen, Betrieb, Auslagerung</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout is-legal">
                    <span class="callout-icon"><i data-icon="scale" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Zur Betroffenheit</h3>
                        <p>
                            Ob NIS2, DORA oder eine andere Regulierung für Sie gilt, bewerte ich
                            nicht rechtsverbindlich – das ist Aufgabe einer Kanzlei. Für die
                            Gap-Analyse gehen wir entweder von einer vorliegenden rechtlichen
                            Einschätzung aus oder arbeiten bewusst mit der Annahme „als ob“,
                            was fachlich sauber und für die Vorbereitung völlig ausreichend ist.
                        </p>
                    </div>
                </div>

                <h2>Schritt 2: Wie der Ist-Zustand erhoben wird</h2>
                <p>
                    Der Unterschied zwischen einer belastbaren und einer schönen Gap-Analyse
                    liegt in der Erhebung. Ich verlasse mich nicht auf Selbstauskunft, sondern
                    arbeite mit drei Quellen, die sich gegenseitig prüfen:
                </p>
                <ol class="steps">
                    <li>
                        <h3>Dokumentenprüfung</h3>
                        <p>Richtlinien, Verträge, Prüfberichte, Systemdokumentation, Protokolle
                           von Gremien. Entscheidend ist nicht, ob ein Dokument existiert, sondern
                           ob es aktuell, freigegeben und bekannt ist.</p>
                    </li>
                    <li>
                        <h3>Interviews auf drei Ebenen</h3>
                        <p>Geschäftsführung, IT-Leitung, operative Ebene. Wenn diese drei Ebenen
                           denselben Prozess unterschiedlich beschreiben, ist das eine Lücke –
                           unabhängig davon, was das Dokument sagt.</p>
                    </li>
                    <li>
                        <h3>Stichproben</h3>
                        <p>Fünf Changes, fünf Benutzerkonten, drei Berechtigungsvergaben, zwei
                           Dienstleisterverträge. Stichproben zeigen in einer Stunde, was
                           Interviews in einem Tag nicht zeigen: ob die Regel im Alltag gilt.</p>
                    </li>
                </ol>

                <h2>Schritt 3: Bewertung – zwei Achsen statt einer Ampel</h2>
                <p>
                    Jede Lücke bekommt zwei Werte: <strong>Risiko</strong> (was passiert, wenn es
                    so bleibt – für den Betrieb, für die Prüfung, für die Haftung) und
                    <strong>Aufwand</strong> (Personentage intern und extern, laufende Kosten).
                    Daraus entsteht die Reihenfolge, und zwar begründet:
                </p>
                <div class="card-grid cols-2">
                    <div class="card is-paper">
                        <h3 class="card-title">Sofort (0–3 Monate)</h3>
                        <p class="card-text">
                            Hohes Risiko, geringer Aufwand. Typisch: Notfallkontakte aktualisieren,
                            Adminkonten inventarisieren, Freigabewege für Changes festhalten,
                            Auslagerungsübersicht erstellen.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Geplant (3–12 Monate)</h3>
                        <p class="card-text">
                            Hohes Risiko, hoher Aufwand. Typisch: Berechtigungskonzept neu
                            aufsetzen, Notfallhandbuch mit Test, Kontrollframework einführen,
                            Demand-Prozess etablieren.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Beobachten</h3>
                        <p class="card-text">
                            Geringes Risiko, hoher Aufwand. Wird bewusst nicht angegangen –
                            dokumentiert mit Begründung. Genau das erwartet ein Prüfer:
                            eine bewusste Entscheidung, keine Lücke aus Versehen.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <h3 class="card-title">Nebenbei</h3>
                        <p class="card-text">
                            Geringes Risiko, geringer Aufwand. Wird im laufenden Betrieb erledigt,
                            ohne Projekt und ohne Budgetantrag.
                        </p>
                    </div>
                </div>

                <h2>Was Sie am Ende bekommen</h2>
                <ul class="checklist">
                    <li><strong>Gap-Bericht</strong> (40–70 Seiten) mit Anforderung, Ist-Zustand,
                        Bewertung und Belegstelle je Punkt.</li>
                    <li><strong>Maßnahmenplan</strong> als bearbeitbare Tabelle: Maßnahme,
                        Verantwortlicher, Aufwand intern/extern, Zieltermin, Wirkung.</li>
                    <li><strong>Entscheidungsvorlage</strong> für Geschäftsführung oder Beirat:
                        drei Umsetzungsszenarien mit Budget, Dauer und Restrisiko.</li>
                    <li><strong>Reifegradprofil</strong> zum Vergleich in zwölf Monaten – der
                        Nachweis, dass sich etwas bewegt hat.</li>
                    <li><strong>Ergebnispräsentation</strong> (2 Stunden) im Entscheiderkreis,
                        auf Wunsch getrennt für IT und Geschäftsführung.</li>
                </ul>

                <h2>Preis und was ihn bestimmt</h2>
                <p>
                    Der Rahmen liegt zwischen <strong>14.500 € und 26.000 € netto</strong>. Wo
                    genau, hängt an vier Faktoren:
                </p>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Faktor</th><th scope="col">Günstiger</th><th scope="col">Teurer</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Standorte / Gesellschaften</td><td>ein Standort</td><td>mehrere Länder, mehrere IT-Teams</td></tr>
                            <tr><td>Rahmen</td><td>ein Katalog</td><td>ISO + NIS2 + Konzernvorgaben kombiniert</td></tr>
                            <tr><td>Ausgangslage</td><td>Dokumentation vorhanden</td><td>alles muss erst zusammengetragen werden</td></tr>
                            <tr><td>Tiefe der Stichproben</td><td>Plausibilitätsprüfung</td><td>prüfungsnahe Belegprüfung</td></tr>
                        </tbody>
                    </table>
                </div>
                <p>
                    Der Preis steht vor Beauftragung fest. Nachforderungen gibt es nur, wenn Sie
                    den Umfang erweitern – und dann schriftlich, vorher, mit eigener Zahl.
                </p>

                <h2>Häufige Fragen zu dieser Leistung</h2>
                <div class="faq-list">
                    <div class="faq-item">
                        <button class="faq-question" type="button" aria-expanded="false">
                            <span class="faq-number">01</span>
                            <span class="faq-question-text">Können Sie die Maßnahmen auch umsetzen?</span>
                        </button>
                        <div class="faq-answer" aria-hidden="true">
                            <p>
                                Teilweise – und bewusst nicht alles. Governance-Strukturen,
                                Prozesse, Rollen und Kontrollen setze ich mit Ihnen um. Technische
                                Umsetzung, Betrieb und Werkzeugeinführung gehören zu Ihrem Team oder
                                Ihrem Systemhaus. Wer bewertet und anschließend alles selbst
                                verkauft, hat ein Interessenproblem.
                            </p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" type="button" aria-expanded="false">
                            <span class="faq-number">02</span>
                            <span class="faq-question-text">Wie unterscheidet sich das vom Quick Assessment?</span>
                        </button>
                        <div class="faq-answer" aria-hidden="true">
                            <p>
                                Das Quick Assessment schätzt ein, die Gap-Analyse prüft nach. Beim
                                Quick Assessment gibt es acht Handlungsfelder und keine Stichproben,
                                bei der Gap-Analyse einen vollständigen Katalog, Belegprüfung und
                                eine Maßnahmenplanung mit Aufwänden. Wer bereits weiß, dass
                                investiert wird, kann das Quick Assessment überspringen.
                            </p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" type="button" aria-expanded="false">
                            <span class="faq-number">03</span>
                            <span class="faq-question-text">Was, wenn das Ergebnis unangenehm ist?</span>
                        </button>
                        <div class="faq-answer" aria-hidden="true">
                            <p>
                                Dann steht es genauso im Bericht. Ein geschönter Bericht kostet Sie
                                dasselbe Geld und nützt niemandem – die Feststellungen kommen dann
                                später von jemandem, der weniger freundlich formuliert. Ich
                                formuliere sachlich und ohne Schuldzuweisung, aber ich formuliere es.
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
$related = ['leistungen/audit-readiness.php', 'leistungen/kontrollframework.php', 'themen/nis2.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
