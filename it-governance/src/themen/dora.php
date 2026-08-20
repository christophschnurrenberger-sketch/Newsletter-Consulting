<?php
$page = [
    'title'       => 'DORA: digitale operationale Resilienz im Finanzsektor',
    'description' => 'DORA (Verordnung (EU) 2022/2554) im Überblick: fünf Säulen, Informationsregister, Anforderungen an IKT-Drittdienstleister und was Vertragsklauseln für IT-Dienstleister des Finanzsektors organisatorisch bedeuten.',
    'section'     => 'themen',
    'path'        => 'themen/dora.php',
    'crumbs'      => [['Themen', 'themen/'], ['DORA', null]],
    'hero'        => [
        'kicker' => 'Thema · Regulatorik',
        'h1'     => 'DORA trifft nicht nur Banken – <span class="accent">sondern deren IT-Lieferanten</span>',
        'lead'   => 'Für Finanzunternehmen ist DORA unmittelbar verpflichtend. Für mittelständische IT-Dienstleister wird sie über Verträge spürbar: Prüfrechte, Ausstiegsklauseln, Meldeunterstützung, Unterauftragsregeln – plötzlich steht das im Rahmenvertrag.',
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Rechtsgrundlage', 'Verordnung (EU) 2022/2554'],
    ['Charakter', 'unmittelbar geltende Verordnung'],
    ['Anwendbar seit', '17. Januar 2025'],
    ['Säulen', '5'],
];
$asideCta = [
    'title' => 'Betroffen als Lieferant?',
    'text'  => 'Wenn Kunden aus dem Finanzsektor neue Vertragsklauseln vorlegen, geht es meist um Nachweisfähigkeit. Genau dafür ist die Gap-Analyse gemacht.',
    'link'  => ['Gap-Analyse ansehen', 'leistungen/gap-analyse.php'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

<?php
$rechtshinweisText = 'DORA ist eine unmittelbar geltende EU-Verordnung mit umfangreichen
    technischen Regulierungsstandards. Ob Ihr Unternehmen als Finanzunternehmen erfasst ist,
    wie Vertragsklauseln auszulegen sind und welche Pflichten sich daraus ergeben, ist eine
    Rechtsfrage.';
include __DIR__ . '/../partials/rechtshinweis.php';
?>

                <h2>Wen DORA unmittelbar betrifft</h2>
                <p>
                    DORA richtet sich an Finanzunternehmen im weiten Sinne: Kreditinstitute,
                    Zahlungs- und E-Geld-Institute, Wertpapierfirmen, Versicherungs- und
                    Rückversicherungsunternehmen, Versicherungsvermittler,
                    Kapitalverwaltungsgesellschaften, Handelsplätze, Anbieter von
                    Krypto-Dienstleistungen und weitere. Anders als bei einer Richtlinie gilt
                    eine Verordnung unmittelbar – ohne nationales Umsetzungsgesetz.
                </p>
                <p>
                    Hinzu kommt eine zweite Gruppe: <strong>IKT-Drittdienstleister</strong>.
                    Solche, die als <em>kritisch</em> eingestuft werden, unterliegen einer
                    direkten europäischen Überwachung. Alle übrigen werden über die Verträge
                    ihrer Kunden erfasst – und das ist der für den Mittelstand relevante Weg.
                </p>

                <h2>Die fünf Säulen</h2>
                <div class="card-grid cols-2">
                    <div class="card is-paper">
                        <span class="card-icon"><i data-icon="shield-check" class="lucide"></i></span>
                        <h3 class="card-title">1 · IKT-Risikomanagement</h3>
                        <p class="card-text">
                            Rahmenwerk mit Governance, Rollen, Schutzmaßnahmen, Erkennung,
                            Reaktion und Wiederherstellung – verantwortet und überwacht durch
                            das Leitungsorgan.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <span class="card-icon"><i data-icon="alert-triangle" class="lucide"></i></span>
                        <h3 class="card-title">2 · Vorfallmanagement und Meldung</h3>
                        <p class="card-text">
                            Klassifizierung von IKT-Vorfällen nach vorgegebenen Kriterien und
                            Meldung schwerwiegender Vorfälle an die zuständige Behörde in
                            gestuften Fristen.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <span class="card-icon"><i data-icon="target" class="lucide"></i></span>
                        <h3 class="card-title">3 · Resilienztests</h3>
                        <p class="card-text">
                            Regelmäßige Tests der digitalen Widerstandsfähigkeit; für bestimmte
                            Unternehmen zusätzlich bedrohungsgeleitete Penetrationstests in
                            mehrjährigem Turnus.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <span class="card-icon"><i data-icon="link" class="lucide"></i></span>
                        <h3 class="card-title">4 · Drittparteienrisiko</h3>
                        <p class="card-text">
                            Informationsregister über alle vertraglichen Vereinbarungen zu
                            IKT-Dienstleistungen, verpflichtende Vertragsinhalte, Ausstiegs&shy;strategien,
                            Regeln für Unterauftragnehmer.
                        </p>
                    </div>
                    <div class="card is-paper">
                        <span class="card-icon"><i data-icon="message-circle" class="lucide"></i></span>
                        <h3 class="card-title">5 · Informationsaustausch</h3>
                        <p class="card-text">
                            Freiwilliger Austausch über Bedrohungen und Erkenntnisse zwischen
                            Finanzunternehmen – die einzige Säule ohne Pflichtcharakter.
                        </p>
                    </div>
                    <div class="card is-navy">
                        <h3 class="card-title">Für IT-Dienstleister relevant</h3>
                        <p class="card-text">
                            Säule 4 ist der Hebel: Was das Finanzunternehmen erfüllen muss,
                            gibt es vertraglich an seine Lieferanten weiter. Wer liefert, muss
                            liefern können – organisatorisch nachweisbar.
                        </p>
                    </div>
                </div>

                <h2>Was in den Verträgen steht</h2>
                <p>
                    Wenn ein mittelständischer IT-Dienstleister einen überarbeiteten Rahmenvertrag
                    von einem Bank- oder Versicherungskunden bekommt, tauchen typischerweise diese
                    Punkte auf – sie ergeben sich aus den Vorgaben der Verordnung an
                    Vertragsinhalte:
                </p>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th scope="col">Klausel</th><th scope="col">Was Sie organisatorisch dafür brauchen</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Vollständige Leistungsbeschreibung, Ort der Leistungserbringung und Datenverarbeitung</td><td>Servicekatalog, Standortübersicht, Unterauftragnehmerliste</td></tr>
                            <tr><td>Zugangs-, Einsichts- und Prüfrechte des Kunden und der Aufsicht</td><td>Ordner mit Nachweisen, Ansprechpartner, geübter Umgang mit Prüfungen</td></tr>
                            <tr><td>Unterstützung bei IKT-Vorfällen, Meldewege, Reaktionszeiten</td><td>Vorfallprozess mit Erreichbarkeit außerhalb der Geschäftszeiten</td></tr>
                            <tr><td>Vorgaben zu Unterauftragnehmern und deren Wechsel</td><td>Steuerung der eigenen Lieferkette, Genehmigungsverfahren</td></tr>
                            <tr><td>Ausstiegsszenarien und Übergabepflichten</td><td>Dokumentation, die Übergabe überhaupt möglich macht</td></tr>
                            <tr><td>Anforderungen an Verfügbarkeit, Sicherheit, Fortführung</td><td>Wiederanlaufziele, getestete Notfallverfahren, Kennzahlen</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout">
                    <span class="callout-icon"><i data-icon="eye" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Die unangenehme Erkenntnis</h3>
                        <p>
                            Fast alle diese Klauseln sind erfüllbar – aber nur, wenn die eigene
                            Organisation geordnet ist. Ein Dienstleister ohne Servicekatalog kann
                            keine Leistungsbeschreibung liefern. Einer ohne Vorfallprozess kann
                            keine Meldeunterstützung zusagen. Einer ohne Dokumentation kann keinen
                            Ausstieg unterstützen. Die Vertragsverhandlung deckt schonungslos auf,
                            was in der eigenen Governance fehlt.
                        </p>
                    </div>
                </div>

                <h2>Das Informationsregister</h2>
                <p>
                    Finanzunternehmen müssen ein Register aller vertraglichen Vereinbarungen über
                    IKT-Dienstleistungen führen und den Aufsichtsbehörden bereitstellen – mit
                    einem festgelegten Datenmodell. In der Praxis bedeutet das für Lieferanten:
                    Sie werden nach Daten gefragt, die viele Unternehmen nicht strukturiert
                    vorhalten – Rechtsträgerkennung, Leistungsart, Datenstandorte,
                    Unterauftragsketten, Kritikalitätseinstufung.
                </p>
                <p>
                    Wer diese Angaben nicht liefern kann, wird als Lieferant zum Problem. Das ist
                    kein juristisches Risiko, sondern ein wirtschaftliches: Kunden weichen aus.
                </p>

                <h2>Was ich in diesem Umfeld mache</h2>
                <ul class="checklist">
                    <li><strong>Vertragsanforderungen in Organisationsanforderungen übersetzen:</strong>
                        Welche Prozesse, Rollen und Nachweise braucht es, um die Klauseln zu erfüllen?</li>
                    <li><strong>Nachweispaket aufbauen</strong>, das gegenüber mehreren Kunden
                        wiederverwendbar ist – statt jede Anfrage einzeln zu bearbeiten.</li>
                    <li><strong>Lieferkette ordnen:</strong> eigene Unterauftragnehmer erfassen,
                        bewerten, vertraglich einbinden.</li>
                    <li><strong>Vorfall- und Notfallverfahren</strong> so aufsetzen, dass zugesagte
                        Reaktionszeiten realistisch sind.</li>
                    <li><strong>Prüfungsfähigkeit herstellen</strong> – Kundenprüfungen laufen
                        deutlich ruhiger, wenn Unterlagen vorhanden und Zuständige geübt sind.</li>
                </ul>
                <p>
                    Was ich <strong>nicht</strong> mache: Vertragsklauseln verhandeln oder
                    bewerten, ob eine Formulierung zulässig ist. Das ist Aufgabe Ihrer Kanzlei –
                    und ich arbeite ihr gern zu, indem ich beschreibe, was Ihr Unternehmen
                    tatsächlich leisten kann.
                </p>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['themen/dienstleistermanagement.php', 'themen/it-notfallmanagement.php', 'leistungen/audit-readiness.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
