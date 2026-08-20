<?php
$page = [
    'title'       => 'IT-Dokumentation: was wirklich gebraucht wird',
    'description' => 'IT-Dokumentation im Mittelstand: welche Dokumente nötig sind, wie sie aktuell bleiben, warum Vollständigkeit das falsche Ziel ist und wie Dokumentation entsteht, ohne dass jemand sie schreibt.',
    'section'     => 'themen',
    'path'        => 'themen/it-dokumentation.php',
    'crumbs'      => [['Themen', 'themen/'], ['IT-Dokumentation', null]],
    'hero'        => [
        'kicker' => 'Thema · Grundlagen',
        'h1'     => 'Dokumentation scheitert nicht am Schreiben, <span class="accent">sondern am Pflegen</span>',
        'lead'   => 'Fast jedes Unternehmen hat schon einmal dokumentiert. Meist liegt das Ergebnis in einem Ordner mit Stand 2019. Das Problem ist nicht Faulheit – es ist ein Konstruktionsfehler: Es wurde mehr dokumentiert, als sich pflegen lässt.',
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Regel', 'Weniger, dafür aktuell'],
    ['Kerndokumente', '8–12 Stück'],
    ['Prüfung', 'jährlich mit Freigabevermerk'],
    ['Test', 'Kann ein Neuer damit arbeiten?'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Die drei Arten von IT-Dokumentation</h2>
                <p>
                    Sie werden regelmäßig vermischt, obwohl sie unterschiedliche Zwecke,
                    Adressaten und Pflegerhythmen haben. Diese Trennung ist der wichtigste
                    Ordnungsschritt überhaupt:
                </p>

                <div class="card-grid cols-3">
                    <div class="card is-paper">
                        <span class="card-icon"><i data-icon="building" class="lucide"></i></span>
                        <h3 class="card-title">Regelungsdokumente</h3>
                        <p class="card-text">
                            Richtlinien, Prozesse, Rollen. Sagen, <em>was gelten soll</em>.
                            Adressat: alle Betroffenen. Pflege: jährliche Prüfung, Freigabe durch
                            die Leitung.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <span class="card-icon"><i data-icon="server" class="lucide"></i></span>
                        <h3 class="card-title">Betriebsdokumentation</h3>
                        <p class="card-text">
                            Systeme, Konfigurationen, Anleitungen, Netzpläne. Sagen,
                            <em>wie es funktioniert</em>. Adressat: IT-Betrieb und Vertretung.
                            Pflege: bei Änderung, als Teil des Change-Prozesses.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <span class="card-icon"><i data-icon="clipboard-check" class="lucide"></i></span>
                        <h3 class="card-title">Nachweise</h3>
                        <p class="card-text">
                            Protokolle, Freigaben, Testberichte, Bewertungen. Belegen,
                            <em>dass etwas getan wurde</em>. Adressat: Prüfer. Pflege: entstehen
                            laufend, werden nur aufbewahrt.
                        </p>
                    </div>
                </div>

                <p>
                    Der häufigste Fehler: Man versucht, Betriebsdokumentation vollständig zu
                    halten, und vernachlässigt darüber Regelungen und Nachweise. Dabei sind es
                    genau die letzten beiden, nach denen Prüfer fragen – und die mit weit weniger
                    Aufwand aktuell zu halten sind.
                </p>

                <h2>Die Kerndokumente eines mittelständischen IT-Bereichs</h2>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Dokument</th><th scope="col">Art</th><th scope="col">Pflege</th><th scope="col">Wird gebraucht bei</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>IT-Leitlinie</td><td>Regelung</td><td class="num">jährlich</td><td>jeder Prüfung, NIS2, ISO</td></tr>
                            <tr><td>IT-Organisation &amp; Rollen</td><td>Regelung</td><td class="num">bei Änderung</td><td>Zuständigkeitsfragen, Prüfung</td></tr>
                            <tr><td>Berechtigungskonzept</td><td>Regelung</td><td class="num">jährlich</td><td>Jahresabschlussprüfung</td></tr>
                            <tr><td>Änderungs- und Freigaberegelung</td><td>Regelung</td><td class="num">jährlich</td><td>Jahresabschlussprüfung</td></tr>
                            <tr><td>Notfallhandbuch</td><td>Beides</td><td class="num">halbjährlich</td><td>Notfall, NIS2, Kundenaudit</td></tr>
                            <tr><td>Systemübersicht / Inventar</td><td>Betrieb</td><td class="num">laufend</td><td>allem</td></tr>
                            <tr><td>Netzplan (grob)</td><td>Betrieb</td><td class="num">bei Änderung</td><td>Vorfall, Prüfung, Versicherung</td></tr>
                            <tr><td>Dienstleisterübersicht</td><td>Regelung</td><td class="num">halbjährlich</td><td>Prüfung, DORA, NIS2</td></tr>
                            <tr><td>Wiederanlaufanweisungen</td><td>Betrieb</td><td class="num">nach jedem Test</td><td>Notfall</td></tr>
                            <tr><td>Kontrollübersicht mit Nachweisorten</td><td>Nachweis</td><td class="num">quartalsweise</td><td>jeder Prüfung</td></tr>
                        </tbody>
                    </table>
                </div>

                <h2>Der Trick: Dokumentation als Nebenprodukt</h2>
                <p>
                    Dokumentation, die jemand zusätzlich schreiben muss, veraltet. Dokumentation,
                    die im Arbeitsablauf entsteht, bleibt aktuell. Vier bewährte Wege:
                </p>
                <ol class="steps">
                    <li>
                        <h3>Aus dem System auslesen statt abtippen</h3>
                        <p>Inventar, Benutzerlisten, Serverübersichten, Softwarestände lassen sich
                           automatisiert erzeugen – per Skript, Managementwerkzeug oder Abfrage.
                           Ein monatlich erzeugter Bericht ist aktueller als jede gepflegte Liste.</p>
                    </li>
                    <li>
                        <h3>Dokumentation an den Change binden</h3>
                        <p>Ein Change gilt erst als abgeschlossen, wenn die betroffene Dokumentation
                           angepasst ist. Das ist eine Prozessregel, keine Fleißaufgabe – und sie
                           lässt sich im Ticketsystem als Pflichtfeld abbilden.</p>
                    </li>
                    <li>
                        <h3>Kurz halten und Verantwortliche benennen</h3>
                        <p>Je Dokument ein Name und ein Prüfdatum auf der ersten Seite. Dokumente
                           ohne Verantwortlichen werden nicht gepflegt – das ist so verlässlich
                           wie ein Naturgesetz.</p>
                    </li>
                    <li>
                        <h3>Alles an einen Ort</h3>
                        <p>Ein Ablageort mit klarer Struktur, nicht fünf. Ob SharePoint, Wiki oder
                           Dateiserver, ist zweitrangig – entscheidend ist, dass es genau einen
                           gibt und dass er auch dann erreichbar ist, wenn die IT ausgefallen ist.</p>
                    </li>
                </ol>

                <div class="callout is-ok">
                    <span class="callout-icon"><i data-icon="user-check" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Der beste Test</h3>
                        <p>
                            Geben Sie einer neuen Kollegin oder einem externen Dienstleister die
                            Dokumentation und die Aufgabe, ein System wiederherzustellen oder eine
                            Berechtigung korrekt zu vergeben. Was dabei nicht funktioniert, fehlt.
                            Dieser Test dauert zwei Stunden und ersetzt jede Diskussion über
                            Vollständigkeit.
                        </p>
                    </div>
                </div>

                <h2>Was man getrost weglassen kann</h2>
                <ul class="checklist is-cross">
                    <li>Vollständige Beschreibungen von Standardprodukten – dafür gibt es Herstellerdokumentation.</li>
                    <li>Prozessdiagramme mit dreißig Verzweigungen. Eine Seite Text schlägt jedes Diagramm, das niemand liest.</li>
                    <li>Dokumente, die nur existieren, weil eine Vorlage sie vorsieht.</li>
                    <li>Dreifache Ablage derselben Information – jede Kopie ist eine künftige Abweichung.</li>
                    <li>Detailtiefe, die bei der nächsten Änderung sofort veraltet.</li>
                </ul>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['wissen/dokumentenlandkarte.php', 'leistungen/governance-framework.php', 'themen/asset-applikationsmanagement.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
