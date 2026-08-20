<?php
$page = [
    'title'       => 'ISO/IEC 27001 im Mittelstand: Aufwand, Kosten, Alternativen',
    'description' => 'Was eine ISO/IEC-27001-Zertifizierung wirklich bedeutet: Managementsystem statt Dokumentensammlung, realistischer Aufwand und Kosten, Ablauf der Zertifizierung – und wann die Struktur ohne Zertifikat die bessere Entscheidung ist.',
    'section'     => 'themen',
    'path'        => 'themen/iso-27001.php',
    'crumbs'      => [['Themen', 'themen/'], ['ISO/IEC 27001', null]],
    'hero'        => [
        'kicker' => 'Thema · Normen',
        'h1'     => 'ISO 27001: <span class="accent">wertvoll</span> – wenn Sie es aus dem richtigen Grund tun',
        'lead'   => 'Ein Zertifikat öffnet Türen bei Ausschreibungen und beendet Fragebogenschlachten. Es kostet aber auch dauerhaft Geld und Aufmerksamkeit. Die Frage ist deshalb nicht, ob ISO 27001 gut ist, sondern ob es Ihr Problem löst.',
    ],
];
include __DIR__ . '/../partials/header.php';

$asideFacts = [
    ['Norm', 'ISO/IEC 27001:2022'],
    ['Maßnahmen (Anhang A)', '93 in 4 Themen'],
    ['Zyklus', '3 Jahre + Überwachung'],
    ['Erstaufwand', 'meist 9–18 Monate'],
];
$asideCta = [
    'title' => 'Erst prüfen, dann entscheiden',
    'text'  => 'Eine Gap-Analyse gegen Anhang A zeigt vor der Entscheidung, wie weit der Weg wirklich ist.',
    'link'  => ['Gap-Analyse ansehen', 'leistungen/gap-analyse.php'],
];
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Was ISO 27001 tatsächlich verlangt</h2>
                <p>
                    Der häufigste Irrtum: ISO 27001 sei eine Liste technischer Sicherheits&shy;maßnahmen.
                    Tatsächlich ist der verbindliche Teil der Norm – die Kapitel 4 bis 10 – ein
                    <strong>Managementsystem</strong>. Verlangt wird ein Kreislauf: Kontext und
                    Anforderungen verstehen, Verantwortung festlegen, Risiken bewerten, Maßnahmen
                    planen, umsetzen, messen, prüfen, verbessern.
                </p>
                <p>
                    Der bekanntere Anhang A mit seinen 93 Maßnahmen ist demgegenüber eine
                    Referenzliste. Man muss nicht alle umsetzen – man muss begründen, welche
                    anwendbar sind und welche nicht. Diese Begründung landet in der
                    Anwendbarkeitserklärung, dem Dokument, das ein Auditor als Erstes aufschlägt.
                </p>

                <div class="table-wrap">
                    <table class="data-table">
                        <caption>Die vier Themenbereiche des Anhangs A in der Fassung von 2022.</caption>
                        <thead>
                            <tr><th scope="col">Bereich</th><th scope="col">Anzahl</th><th scope="col">Schwerpunkt</th></tr>
                        </thead>
                        <tbody>
                            <tr><td><strong>Organisatorisch</strong></td><td class="num">37</td><td>Richtlinien, Rollen, Lieferanten, Vorfälle, Kontinuität, Compliance</td></tr>
                            <tr><td><strong>Personenbezogen</strong></td><td class="num">8</td><td>Eignung, Schulung, Verhalten, Rückgabe von Werten, Fernarbeit</td></tr>
                            <tr><td><strong>Physisch</strong></td><td class="num">14</td><td>Zutritt, Räume, Verkabelung, Entsorgung, Arbeitsplatz</td></tr>
                            <tr><td><strong>Technologisch</strong></td><td class="num">34</td><td>Zugriffe, Kryptografie, Protokollierung, Entwicklung, Netzwerksicherheit</td></tr>
                        </tbody>
                    </table>
                </div>

                <p>
                    Bemerkenswert: Der größte Block ist organisatorisch. Wer ein
                    <a href="/leistungen/governance-framework.php">Governance-Framework</a>,
                    ein <a href="/leistungen/rollen-verantwortlichkeiten.php">Rollenmodell</a> und
                    ein <a href="/leistungen/kontrollframework.php">Kontrollframework</a> aufgebaut
                    hat, hat einen erheblichen Teil des Wegs bereits zurückgelegt – auch ohne
                    Zertifizierungsabsicht.
                </p>

                <h2>Der Ablauf einer Zertifizierung</h2>
                <ol class="phase-list">
                    <li>
                        <span class="phase-week">Monat 1–3</span>
                        <h3>Geltungsbereich und Standortbestimmung</h3>
                        <p>Welche Teile des Unternehmens werden zertifiziert? Ein enger
                           Geltungsbereich senkt Aufwand und Risiko erheblich – ein zu enger
                           entwertet das Zertifikat gegenüber Kunden.</p>
                    </li>
                    <li>
                        <span class="phase-week">Monat 3–9</span>
                        <h3>Managementsystem aufbauen</h3>
                        <p>Risikomethodik, Risikobewertung, Anwendbarkeitserklärung, Richtlinien,
                           Rollen, Kennzahlen, Schulung, Kontrollen. Der Hauptteil der Arbeit.</p>
                    </li>
                    <li>
                        <span class="phase-week">Monat 9–12</span>
                        <h3>Betriebsnachweis erzeugen</h3>
                        <p>Der kritische Punkt: Auditoren wollen sehen, dass das System läuft –
                           interne Audits, Managementbewertung, dokumentierte Vorfälle, laufende
                           Kontrollen. Ein System ohne Historie besteht nicht.</p>
                    </li>
                    <li>
                        <span class="phase-week">Monat 12–15</span>
                        <h3>Zertifizierungsaudit Stufe 1 und 2</h3>
                        <p>Stufe 1 prüft die Dokumentation, Stufe 2 die Wirksamkeit vor Ort.
                           Zwischen beiden liegen typischerweise vier bis acht Wochen für
                           Nachbesserungen.</p>
                    </li>
                    <li>
                        <span class="phase-week">danach</span>
                        <h3>Überwachung und Rezertifizierung</h3>
                        <p>Jährliche Überwachungsaudits, nach drei Jahren die Rezertifizierung.
                           Der laufende Aufwand ist real und dauerhaft – das gehört in die
                           Entscheidung.</p>
                    </li>
                </ol>

                <h2>Was es kostet – ehrlich gerechnet</h2>
                <div class="table-wrap">
                    <table class="data-table">
                        <caption>Größenordnungen für ein Unternehmen mit 150 bis 500 Mitarbeitenden.
                            Die Zertifizierungsstelle rechnet nach Auditzeit, die sich aus
                            Mitarbeiterzahl und Komplexität ergibt.</caption>
                        <thead>
                            <tr><th scope="col">Posten</th><th scope="col">Einmalig</th><th scope="col">Jährlich</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Interner Aufwand (Aufbau, 0,3–0,7 Stellen über 12 Monate)</td><td class="num">25.000 – 60.000 €</td><td class="num">15.000 – 35.000 €</td></tr>
                            <tr><td>Externe Beratung beim Aufbau</td><td class="num">25.000 – 70.000 €</td><td class="num">–</td></tr>
                            <tr><td>Zertifizierungsstelle (Audit Stufe 1 + 2)</td><td class="num">8.000 – 20.000 €</td><td class="num">4.000 – 9.000 € Überwachung</td></tr>
                            <tr><td>Technische Nachrüstung (stark abhängig vom Ausgangszustand)</td><td class="num">offen</td><td class="num">offen</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="callout is-warning">
                    <span class="callout-icon"><i data-icon="alert-triangle" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Der unterschätzte Posten</h3>
                        <p>
                            Der teuerste Teil ist nicht das Audit, sondern der interne Aufwand –
                            und er endet nicht mit dem Zertifikat. Ein Managementsystem braucht
                            dauerhaft jemanden, der es betreibt: interne Audits, Managementbewertung,
                            Risikoaktualisierung, Nachweispflege. Wer diese Stelle nicht hat, hat
                            nach zwei Jahren ein Zertifikat und ein schlechtes Gewissen.
                        </p>
                    </div>
                </div>

                <h2>Wann sich das Zertifikat lohnt – und wann nicht</h2>
                <div class="versus">
                    <div class="versus-col is-good">
                        <h3>Lohnt sich</h3>
                        <ul class="checklist is-tight">
                            <li>Kunden fordern es in Ausschreibungen ausdrücklich.</li>
                            <li>Sie beantworten mehr als fünf Sicherheitsfragebögen pro Jahr.</li>
                            <li>Sie liefern an regulierte Branchen (Finanz, Gesundheit, Energie, öffentliche Hand).</li>
                            <li>Sie wollen ein Managementsystem ohnehin – dann ist die Zertifizierung nur der Beweis.</li>
                            <li>Es gibt intern eine Person mit Kapazität für den Betrieb.</li>
                        </ul>
                    </div>
                    <div class="versus-col is-bad">
                        <h3>Lohnt sich nicht</h3>
                        <ul class="checklist is-cross is-tight">
                            <li>Ein einzelner Kunde fragt danach – dann verhandeln Sie über den Nachweisweg.</li>
                            <li>Das Ziel ist Marketing, nicht Sicherheit.</li>
                            <li>Es gibt niemanden, der das System danach betreibt.</li>
                            <li>Die Grundlagen fehlen völlig – dann zuerst Governance aufbauen, Zertifizierung später.</li>
                            <li>Der Geltungsbereich müsste so eng geschnitten werden, dass er nichts aussagt.</li>
                        </ul>
                    </div>
                </div>

                <h2>Die Alternative: Substanz ohne Zertifikat</h2>
                <p>
                    Für einen erheblichen Teil der mittelständischen Unternehmen ist der bessere
                    Weg: die Struktur aufbauen, die ISO verlangt, und den Nachweis anders führen –
                    über ein sauberes Nachweispaket, eine dokumentierte Selbstbewertung und
                    gegebenenfalls eine externe Bestätigung einzelner Bereiche. Das kostet einen
                    Bruchteil und deckt einen Großteil der Kundenanforderungen ab.
                </p>
                <p>
                    Wenn später doch zertifiziert werden soll, ist diese Arbeit nicht verloren –
                    sie ist genau die Vorbereitung. Ich sage das offen, obwohl es kleinere Projekte
                    bedeutet: Ein Zertifikat zu verkaufen, das ein Unternehmen nicht braucht, ist
                    der schnellste Weg zu einem unzufriedenen Kunden.
                </p>

                <div class="callout is-legal">
                    <span class="callout-icon"><i data-icon="scale" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Rollen im Zertifizierungsprozess</h3>
                        <p>
                            Ich berate beim Aufbau des Managementsystems. Zertifizieren darf und
                            will ich nicht – das machen akkreditierte Zertifizierungsstellen, und
                            sie dürfen dieselbe Organisation nicht beraten und prüfen. Diese
                            Trennung ist gut so; Beratung und Prüfung in einer Hand entwerten das
                            Ergebnis.
                        </p>
                    </div>
                </div>

            </div>
<?php include __DIR__ . '/../partials/aside.php'; ?>
        </div>
    </div>
</section>

<?php
$related = ['leistungen/gap-analyse.php', 'leistungen/kontrollframework.php', 'themen/it-risikomanagement.php'];
include __DIR__ . '/../partials/related.php';
include __DIR__ . '/../partials/footer.php';
?>
