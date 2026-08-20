<?php
$page = [
    'title'       => 'Vorgehen: wie ein Projekt bei mir abläuft',
    'description' => 'Vom Erstgespräch bis zur Übergabe: Ablauf eines Governance-Projekts, Rollen und Erwartungen, Umgang mit Vertraulichkeit, Zusammenarbeit mit vorhandenen Dienstleistern und Kanzleien sowie die Grundsätze, nach denen ich arbeite.',
    'section'     => 'vorgehen',
    'path'        => 'vorgehen.php',
    'crumbs'      => [['Vorgehen', null]],
    'hero'        => [
        'kicker' => 'Vorgehen',
        'h1'     => 'Erst verstehen. Dann bewerten. <span class="accent">Dann entscheiden lassen.</span>',
        'lead'   => 'Jedes Projekt folgt derselben Logik – ob es zwei Wochen oder fünf Monate dauert. Was sich unterscheidet, ist die Tiefe, nicht der Ablauf.',
        'actions' => [
            ['Erstgespräch vereinbaren', 'kontakt.php', 'primary'],
            ['Leistungen ansehen', 'leistungen/', 'ghost'],
        ],
    ],
];
include __DIR__ . '/partials/header.php';
?>

<!-- Ablauf --------------------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="section-head is-wide">
            <span class="section-kicker">Der Weg zum Auftrag</span>
            <h2 class="section-title">Vier Schritte, bevor überhaupt Geld fließt</h2>
            <p class="section-lead">
                Ich arbeite ohne Vertriebsdruck. Wenn ein Projekt nicht sinnvoll ist, sage ich
                das im Erstgespräch – das ist billiger für Sie und angenehmer für mich.
            </p>
        </div>

        <ol class="phase-list">
            <li>
                <span class="phase-week">Schritt 1 · kostenlos</span>
                <h3>Erstgespräch, 30 Minuten</h3>
                <p>
                    Telefon oder Video. Sie schildern die Ausgangslage und den Anlass – Audit,
                    Feststellung, Kundendruck, Wachstum, Wechsel in der IT-Leitung. Ich stelle
                    Rückfragen und sage Ihnen, was ich an Ihrer Stelle zuerst angehen würde.
                    Ohne Präsentation, ohne Unterlagen im Vorfeld.
                </p>
            </li>
            <li>
                <span class="phase-week">Schritt 2 · kostenlos</span>
                <h3>Zuschnitt, 60 bis 90 Minuten</h3>
                <p>
                    Wenn beide Seiten weitermachen wollen: ein zweites, tieferes Gespräch,
                    gern mit weiteren Beteiligten. Hier wird der Umfang festgelegt – was gehört
                    dazu, was nicht, wer wird gebraucht, wie viel Zeit kostet es Sie intern.
                    Auf Wunsch unterschreibe ich vorab eine Vertraulichkeitsvereinbarung.
                </p>
            </li>
            <li>
                <span class="phase-week">Schritt 3</span>
                <h3>Angebot mit festem Preis</h3>
                <p>
                    Schriftlich, meist innerhalb von drei Werktagen: Umfang, Ergebnisse,
                    Termine, Mitwirkungspflichten, Preis. Keine Tagessatzkalkulation mit offenem
                    Ende – Sie wissen vorher, was es kostet und was Sie bekommen.
                </p>
            </li>
            <li>
                <span class="phase-week">Schritt 4</span>
                <h3>Auftakt und Terminplan</h3>
                <p>
                    Nach Beauftragung ein Auftakttermin mit allen Beteiligten: Ziel, Ablauf,
                    Interviewtermine, Ansprechpartner, Umgang mit Ergebnissen. Danach beginnt
                    die eigentliche Arbeit.
                </p>
            </li>
        </ol>
    </div>
</section>

<!-- Im Projekt ----------------------------------------------------------- -->
<section class="section section-paper section-line">
    <div class="container">
        <div class="section-head is-wide">
            <span class="section-kicker">Im Projekt</span>
            <h2 class="section-title">Wie gearbeitet wird</h2>
        </div>

        <div class="card-grid cols-3">
            <div class="card">
                <span class="card-icon"><i data-icon="search" class="lucide"></i></span>
                <h3 class="card-title">Verstehen vor Bewerten</h3>
                <p class="card-text">
                    Die erste Phase ist immer Zuhören: Interviews, Dokumente, Systemblicke. Wer
                    zu früh bewertet, bewertet das Falsche – und verliert die Menschen, deren
                    Mitarbeit er später braucht.
                </p>
            </div>
            <div class="card">
                <span class="card-icon"><i data-icon="database" class="lucide"></i></span>
                <h3 class="card-title">Daten statt Meinungen</h3>
                <p class="card-text">
                    Wo Systeme Daten hergeben, werden sie ausgewertet – Tickets, Changes,
                    Konten, Buchungen. Eine Auswertung beendet Diskussionen, die Interviews
                    nur verlängern.
                </p>
            </div>
            <div class="card">
                <span class="card-icon"><i data-icon="users" class="lucide"></i></span>
                <h3 class="card-title">Beteiligen statt vorgeben</h3>
                <p class="card-text">
                    Regeln, die über die Köpfe hinweg entstehen, werden umgangen. Deshalb
                    Workshops mit denen, die es später tun müssen – auch wenn es länger dauert.
                </p>
            </div>
            <div class="card">
                <span class="card-icon"><i data-icon="message-circle" class="lucide"></i></span>
                <h3 class="card-title">Keine Überraschungen</h3>
                <p class="card-text">
                    Unangenehme Befunde spreche ich früh an, nicht in der Abschlusspräsentation.
                    Niemand soll vor seinem Vorgesetzten überrascht werden – das schadet dem
                    Projekt mehr als der Befund selbst.
                </p>
            </div>
            <div class="card">
                <span class="card-icon"><i data-icon="file-text" class="lucide"></i></span>
                <h3 class="card-title">Kurze Dokumente</h3>
                <p class="card-text">
                    Ergebnisse werden so kurz wie möglich geschrieben. Ein Bericht, den niemand
                    liest, hat keine Wirkung – auch wenn er hundert Seiten hat.
                </p>
            </div>
            <div class="card">
                <span class="card-icon"><i data-icon="user-check" class="lucide"></i></span>
                <h3 class="card-title">Übergabe von Anfang an</h3>
                <p class="card-text">
                    Ziel ist, dass Sie ohne mich weiterarbeiten können. Deshalb bekommen Sie
                    bearbeitbare Dateien, keine geschützten PDFs – und Vorlagen, die Sie selbst
                    fortführen.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Erwartungen ---------------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="split is-top">
            <div>
                <span class="section-kicker">Zusammenarbeit</span>
                <h2 class="section-title">Was ich von Ihnen brauche</h2>
                <p>
                    Governance-Projekte scheitern selten am Berater. Sie scheitern an fehlender
                    Mitwirkung – und das ist kein Vorwurf, sondern eine Frage der Planung.
                </p>
                <ul class="checklist">
                    <li><strong>Rückendeckung der Geschäftsführung.</strong> Nicht als Formalie:
                        Wenn oben am Prozess vorbei entschieden wird, ist das Projekt tot.</li>
                    <li><strong>Eine verantwortliche Person im Haus</strong>, die Ergebnisse
                        übernimmt und weiterträgt – meist die IT-Leitung.</li>
                    <li><strong>Zeit in Kalendern.</strong> Interviews und Workshops müssen
                        stattfinden. Zwei bis sechs Stunden pro Woche, je nach Leistung.</li>
                    <li><strong>Zugang zu Unterlagen und, wo nötig, lesenden Systemzugriff.</strong></li>
                    <li><strong>Ehrlichkeit.</strong> Ein geschöntes Bild führt zu einem Ergebnis,
                        das nichts wert ist. Was ich höre, wird nicht namentlich weitergegeben.</li>
                </ul>
            </div>
            <div>
                <div class="card is-navy">
                    <h3 class="card-title">Was Sie von mir bekommen</h3>
                    <ul class="checklist is-dark is-tight">
                        <li>Feste Termine, die eingehalten werden</li>
                        <li>Erreichbarkeit zwischen den Terminen</li>
                        <li>Ergebnisse in bearbeitbarer Form</li>
                        <li>Klare Aussagen, auch unbequeme</li>
                        <li>Keine Weitergabe an Dritte, kein Namedropping mit Ihrem Unternehmen</li>
                        <li>Auf Wunsch: Verzicht auf jede Referenznennung, dauerhaft</li>
                    </ul>
                    <p style="margin-top:1rem; margin-bottom:0; font-size:.92rem;">
                        Und die Zusage, dass Sie mit mir arbeiten – nicht mit einem Team, das ich
                        Ihnen im Angebot vorstelle und danach nie wiedersehen.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Abgrenzungen --------------------------------------------------------- -->
<section class="section section-paper section-line">
    <div class="container">
        <div class="section-head is-wide">
            <span class="section-kicker">Rollen und Grenzen</span>
            <h2 class="section-title">Mit wem ich zusammenarbeite – und wo meine Arbeit endet</h2>
        </div>

        <div class="card-grid cols-2">
            <div class="card">
                <h3 class="card-title">Ihre Kanzlei</h3>
                <p class="card-text">
                    Rechtliche Bewertungen – Betroffenheit von Regulierung, Haftung,
                    Vertragsgestaltung, Arbeitsrecht, Meldepflichten – gehören zu zugelassenen
                    Rechtsanwältinnen und Rechtsanwälten. Ich liefere die fachliche Grundlage
                    und setze um, was rechtlich geklärt ist. Wenn Sie keine passende Kanzlei
                    haben, kann ich Ihnen die Suche erleichtern, empfehle aber niemanden gegen
                    Provision.
                </p>
            </div>
            <div class="card">
                <h3 class="card-title">Ihr Datenschutzbeauftragter</h3>
                <p class="card-text">
                    Datenschutz und IT-Governance überschneiden sich – bei Verzeichnissen,
                    Löschkonzepten, Auftragsverarbeitung, Protokollierung. Ich arbeite dem DSB
                    zu und nutze vorhandene Unterlagen, statt sie doppelt zu erstellen. Die
                    datenschutzrechtliche Bewertung bleibt beim DSB.
                </p>
            </div>
            <div class="card">
                <h3 class="card-title">Ihr Systemhaus oder IT-Dienstleister</h3>
                <p class="card-text">
                    Ich konkurriere nicht mit Ihrem Dienstleister und verkaufe keine Technik.
                    Im Gegenteil: In vielen Projekten wird die Zusammenarbeit mit dem
                    Dienstleister klarer, weil zum ersten Mal aufgeschrieben ist, wer wofür
                    zuständig ist. Sein Betrieb bleibt sein Geschäft.
                </p>
            </div>
            <div class="card">
                <h3 class="card-title">Ihr Wirtschaftsprüfer</h3>
                <p class="card-text">
                    Ich bereite Prüfungen vor und begleite sie auf Ihrer Seite. Ich prüfe nicht
                    selbst und stelle keine Testate aus – das wäre unvereinbar. Wo Ihr Prüfer
                    Feststellungen getroffen hat, arbeite ich mit dem Bericht, nicht gegen ihn.
                </p>
            </div>
        </div>

        <div class="callout is-legal" style="margin-top:2rem;">
            <span class="callout-icon"><i data-icon="scale" class="lucide"></i></span>
            <div class="callout-body">
                <h3 class="callout-title">Keine Rechtsberatung</h3>
                <p>
                    Meine Leistungen sind Organisations-, Prozess- und Managementberatung. Sie
                    enthalten keine Rechtsdienstleistung im Sinne des Rechtsdienstleistungsgesetzes.
                    Ich lege keine Gesetze verbindlich aus, prüfe keine Verträge auf Wirksamkeit
                    und bewerte keine Haftungsfragen. Diese Grenze ziehe ich nicht aus
                    Vorsicht, sondern weil eine saubere Arbeitsteilung für Sie das bessere
                    Ergebnis liefert.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Vertraulichkeit ------------------------------------------------------ -->
<section class="section">
    <div class="container">
        <div class="split is-wide-left is-top">
            <div>
                <span class="section-kicker">Vertraulichkeit</span>
                <h2 class="section-title">Was in Ihrem Haus bleibt</h2>
                <p>
                    Ich sehe in Projekten Dinge, die niemanden etwas angehen: Schwachstellen,
                    Konflikte zwischen Abteilungen, Fehler, die jemandem unangenehm sind.
                    Deshalb gelten drei feste Regeln.
                </p>
                <ol class="steps">
                    <li>
                        <h3>Aussagen werden nicht zugeordnet</h3>
                        <p>Interviewinhalte fließen anonymisiert in Ergebnisse ein. Wer mir etwas
                           erzählt, muss nicht befürchten, es im Bericht mit Namen zu lesen.</p>
                    </li>
                    <li>
                        <h3>Vertraulichkeitsvereinbarung auf Wunsch vorab</h3>
                        <p>Ihre Vorlage oder meine – vor dem ersten inhaltlichen Termin, ohne
                           Diskussion.</p>
                    </li>
                    <li>
                        <h3>Keine Referenz ohne Freigabe</h3>
                        <p>Ihr Name taucht nirgends auf, solange Sie das nicht ausdrücklich
                           schriftlich freigeben. Auch nicht als „ein Maschinenbauer aus
                           Süddeutschland“, wenn das erkennbar wäre.</p>
                    </li>
                </ol>
            </div>
            <div>
                <div class="card is-paper">
                    <h3 class="card-title">Technisch und organisatorisch</h3>
                    <ul class="checklist is-tight">
                        <li>Unterlagen bleiben nach Möglichkeit in Ihren Systemen; wo das nicht
                            geht, verschlüsselte Ablage auf meiner Seite</li>
                        <li>Zugriffe nur lesend und nur so weit wie nötig</li>
                        <li>Rückgabe oder Löschung von Unterlagen nach Projektende, auf Wunsch
                            mit Bestätigung</li>
                        <li>Keine Nutzung von Kundendaten für andere Zwecke – auch nicht
                            anonymisiert für Benchmarks</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
