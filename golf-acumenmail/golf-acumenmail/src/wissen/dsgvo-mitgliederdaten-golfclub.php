<?php
$page = [
    'title'       => 'Mitgliederdaten rechtssicher für den Newsletter nutzen',
    'description' => 'Wo Vereinsinformation aufhört und Werbung anfängt, warum Double-Opt-in auch bei Mitgliedern sinnvoll ist und welche Nachweise ein Golfclub im Streitfall braucht.',
    'section'     => 'wissen',
    'path'        => 'wissen/dsgvo-mitgliederdaten-golfclub.php',
    'crumbs'      => [['Wissen', 'wissen/'], ['Mitgliederdaten und DSGVO', null]],
    'hero'        => [
        'kicker' => 'Wissen · Recht',
        'h1'     => 'Mitgliederdaten rechtssicher <span class="accent">für den Newsletter nutzen</span>',
        'lead'   => 'Die häufigste Frage aus Golfclubs – und die, bei der am meisten Halbwissen kursiert. Hier steht, worauf es praktisch ankommt und wo die Grenze verläuft.',
    ],
];
include __DIR__ . '/../partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div>
                <p class="article-meta">
                    <span><i data-icon="clock" class="lucide"></i>9 Minuten Lesezeit</span>
                    <span><i data-icon="lock" class="lucide"></i>Recht</span>
                </p>

                <div class="callout is-warning">
                    <i data-icon="help-circle" class="lucide"></i>
                    <p>
                        <strong>Keine Rechtsberatung</strong>
                        Dieser Text fasst zusammen, wie wir Newsletter-Systeme in Clubs einrichten
                        und welche Nachweise wir dabei erzeugen. Die rechtliche Bewertung für Ihren
                        Club trifft Ihr Datenschutzbeauftragter oder Ihr Anwalt – nicht wir und
                        nicht ein Text im Internet.
                    </p>
                </div>

                <div class="article-toc">
                    <h2>Inhalt</h2>
                    <ol>
                        <li><a href="#zwei">Zwei Arten von Post, zwei Rechtsgrundlagen</a></li>
                        <li><a href="#doi">Warum Double-Opt-in trotzdem sinnvoll ist</a></li>
                        <li><a href="#nachweis">Welche Nachweise Sie brauchen</a></li>
                        <li><a href="#gaeste">Gastspieler sind ein eigener Fall</a></li>
                        <li><a href="#praxis">Was das praktisch bedeutet</a></li>
                    </ol>
                </div>

                <div class="prose">
                    <h2 id="zwei" style="margin-top:0;">Zwei Arten von Post, zwei Rechtsgrundlagen</h2>
                    <p>
                        Der wichtigste Satz zuerst: Die Einladung zur Mitgliederversammlung und das
                        Angebot des Pro-Shops sind rechtlich nicht dasselbe – auch wenn beides in
                        derselben Mail stehen könnte.
                    </p>
                    <ul>
                        <li>
                            <strong>Vereinsinformation</strong> ergibt sich aus dem
                            Mitgliedschaftsverhältnis: Termine, Platzsperrungen, Beitragsinfos,
                            Einladungen zur Versammlung, Ergebnisse. Das gehört zur Mitgliedschaft
                            und wird üblicherweise auf dieser Grundlage verschickt.
                        </li>
                        <li>
                            <strong>Werbung</strong> ist alles, was verkauft: Pro-Shop-Aktionen,
                            Gastronomieangebote, Golfreisen, Fitting-Termine, Partnerangebote. Dafür
                            gelten strengere Anforderungen, und hier ist eine Einwilligung der
                            saubere Weg.
                        </li>
                    </ul>
                    <p>
                        Die Praxis scheitert selten an dieser Unterscheidung, sondern daran, dass
                        beides in einer Liste landet. Wer dann eine Reiseaktion an alle schickt,
                        verschickt Werbung an Leute, die nur Vereinsinformationen wollten.
                    </p>

                    <h2 id="doi">Warum Double-Opt-in trotzdem sinnvoll ist</h2>
                    <p>
                        Für reine Vereinsinformationen an Mitglieder ist eine gesonderte Einwilligung
                        oft nicht zwingend. Trotzdem richten wir jede Anmeldung mit Double-Opt-in ein
                        – aus drei praktischen Gründen:
                    </p>
                    <ol>
                        <li>
                            <strong>Die Adresse wird geprüft.</strong> Wer den Bestätigungslink
                            klickt, hat ein funktionierendes Postfach. Das senkt die Bounce-Quote und
                            damit das Risiko, dass große Anbieter den Club aussortieren.
                        </li>
                        <li>
                            <strong>Der Nachweis entsteht automatisch.</strong> Zeitstempel, Quelle
                            und IP werden protokolliert. Bei einer Beschwerde ist das der
                            Unterschied zwischen „wir haben da einen Eintrag“ und einem belastbaren
                            Beleg.
                        </li>
                        <li>
                            <strong>Es verhindert Falscheinträge.</strong> Vertippt sich jemand bei
                            der Anmeldung, bekommt ein Fremder die Bestätigungsmail – und nicht drei
                            Jahre lang Clubpost.
                        </li>
                    </ol>

                    <h2 id="nachweis">Welche Nachweise Sie brauchen</h2>
                    <p>
                        Im Streitfall muss der Club zeigen können, woher eine Adresse stammt. Ein
                        sauber geführtes System liefert das ohne Zusatzaufwand:
                    </p>
                    <ul>
                        <li>Datum und Uhrzeit der Anmeldung sowie der Bestätigung</li>
                        <li>Quelle – welches Formular, welche Seite, welcher Import</li>
                        <li>Der Text, dem zugestimmt wurde, in der damals gültigen Fassung</li>
                        <li>Datum einer eventuellen Abmeldung</li>
                    </ul>
                    <p>
                        Genauso wichtig ist die andere Richtung: Eine Abmeldung muss dauerhaft
                        wirken. Deshalb steht in unseren Installationen die Regel, dass ein Import
                        eine Abmeldung niemals überschreibt. Das ist die langweiligste Schutzregel
                        des Systems – und die, die den häufigsten Ärger verhindert.
                    </p>

                    <h2 id="gaeste">Gastspieler sind ein eigener Fall</h2>
                    <p>
                        Bei Greenfee-Zahlern gibt es kein Mitgliedschaftsverhältnis, auf das man sich
                        stützen könnte. Hier entscheidet allein der Moment der Buchung: Wurde sauber
                        gefragt – ein Häkchen, klar beschriftet, nicht vorausgewählt, mit Hinweis auf
                        das Widerrufsrecht –, darf angeschrieben werden. Wurde nicht gefragt, darf es
                        nicht, egal wie gut die Idee ist.
                    </p>
                    <p>
                        Deshalb beginnt die Arbeit an dieser Zielgruppe nicht beim Newsletter,
                        sondern beim Buchungsformular und am Empfang.
                    </p>

                    <h2 id="praxis">Was das praktisch bedeutet</h2>
                    <p>
                        In fast jedem Club läuft es auf dieselben fünf Schritte hinaus: getrennte
                        Listen für Vereinsinformation, Werbung und Gäste; Double-Opt-in für alles;
                        Abmeldelink und Impressum in jeder Mail; ein Protokoll, das niemand pflegen
                        muss; und die Regel, dass Importe Abmeldungen nie überschreiben.
                    </p>
                    <p>
                        Das ist kein großer Aufwand – aber es muss einmal richtig eingerichtet
                        werden. Nachträglich Ordnung in einen gewachsenen Verteiler zu bringen, ist
                        deutlich mühsamer, als von Anfang an sauber zu starten.
                    </p>
                </div>

                <h2 class="section-title" style="font-size:1.5rem; margin:3rem 0 1.2rem;">Weiterlesen</h2>
                <div class="related-grid">
                    <a href="<?= e(url('software/zustellbarkeit-dsgvo.php')) ?>" class="related-card">
                        <span>Software</span><strong>Zustellbarkeit &amp; DSGVO</strong>
                        <p>Was im System fest eingebaut ist und sich nicht abschalten lässt.</p>
                    </a>
                    <a href="<?= e(url('loesungen/gastspieler.php')) ?>" class="related-card">
                        <span>Lösung</span><strong>Gastspieler &amp; Greenfee</strong>
                        <p>Wie die Einwilligung bei der Buchung praktisch aussieht.</p>
                    </a>
                </div>
            </div>

            <?php $asideCta = [
                'title' => 'Bestand prüfen lassen',
                'text'  => 'Im Clubcheck sehen wir uns an, woher Ihre Adressen stammen und ob eine belastbare Grundlage für den Versand besteht.',
                'link'  => ['Clubcheck ansehen', 'leistungen/clubcheck.php'],
            ]; ?>
            <?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
