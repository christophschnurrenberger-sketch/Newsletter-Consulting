<?php
/**
 * Datenschutzerklärung.
 *
 * >>> VOR DEM ONLINESTELLEN PRÜFEN <<<
 * 1. Hosting: Der Text geht von einem Hosting in Deutschland mit
 *    Auftragsverarbeitungsvertrag aus (z. B. IONOS). Bei anderem Anbieter
 *    Namen, Sitz und Vertragslage anpassen.
 * 2. Die Seite bindet bewusst keine externen Schriften, Karten, Videos oder
 *    Analysedienste ein. Wird das geändert, muss dieser Text ergänzt werden –
 *    und je nach Dienst wird eine Einwilligungslösung nötig.
 * 3. Eine anwaltliche Prüfung dieses Textes ist zu empfehlen; er ersetzt sie
 *    nicht.
 */
$page = [
    'title'       => 'Datenschutzerklärung',
    'description' => 'Informationen zur Verarbeitung personenbezogener Daten auf dieser Website: Hosting, Server-Logfiles, Kontaktformular, Aufbewahrung, Rechtsgrundlagen und Betroffenenrechte.',
    'section'     => '',
    'path'        => 'datenschutz.php',
    'crumbs'      => [['Datenschutz', null]],
    'cta'         => false,
    'hero'        => [
        'kicker' => 'Rechtliches',
        'h1'     => 'Datenschutzerklärung',
        'lead'   => 'Diese Website verzichtet auf Cookies, Tracking, externe Schriften und Werkzeuge Dritter. Verarbeitet wird nur, was für den Betrieb und für Ihre Anfrage nötig ist.',
    ],
];
include __DIR__ . '/partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <div class="callout is-ok">
                    <span class="callout-icon"><i data-icon="shield-check" class="lucide"></i></span>
                    <div class="callout-body">
                        <h3 class="callout-title">Das Wichtigste in Kürze</h3>
                        <p>
                            Keine Cookies, kein Tracking, keine Analysedienste, keine externen
                            Schriften, keine Einbindungen von sozialen Netzwerken, kein
                            Newsletter. Wenn Sie das Kontaktformular nutzen, werden Ihre Angaben
                            per E-Mail an mich übermittelt und dort bearbeitet – mehr passiert
                            nicht.
                        </p>
                    </div>
                </div>

                <h2>1 · Verantwortlicher</h2>
                <p>
                    Verantwortlich für die Datenverarbeitung auf dieser Website im Sinne der
                    Datenschutz-Grundverordnung (DSGVO) ist:
                </p>
                <p>
                    <?= e($SITE['owner']) ?><br>
                    <?= e($SITE['street']) ?><br>
                    <?= e($SITE['city']) ?><br>
                    <?= e($SITE['country']) ?><br>
                    E-Mail: <a href="mailto:<?= e($SITE['email']) ?>"><?= e($SITE['email']) ?></a><br>
                    Telefon: <a href="tel:<?= e($SITE['phone_link']) ?>"><?= e($SITE['phone']) ?></a>
                </p>
                <p>
                    Ein Datenschutzbeauftragter ist gesetzlich nicht zu benennen. Fragen zum
                    Datenschutz richten Sie bitte an die oben genannten Kontaktdaten.
                </p>

                <h2>2 · Hosting und Server-Logfiles</h2>
                <p>
                    Diese Website wird bei einem Anbieter in Deutschland gehostet. Mit dem
                    Anbieter besteht ein Vertrag über Auftragsverarbeitung nach Art. 28 DSGVO.
                </p>
                <p>
                    Beim Aufruf der Website werden vom Webserver automatisch Daten in
                    sogenannten Logfiles gespeichert. Dies sind:
                </p>
                <ul class="checklist is-tight">
                    <li>IP-Adresse des anfragenden Geräts</li>
                    <li>Datum und Uhrzeit des Zugriffs</li>
                    <li>Name und URL der abgerufenen Datei</li>
                    <li>übertragene Datenmenge und Meldung über den Erfolg des Abrufs</li>
                    <li>Browsertyp und Betriebssystem</li>
                    <li>gegebenenfalls die zuvor besuchte Seite (Referrer)</li>
                </ul>
                <p>
                    <strong>Zweck:</strong> Auslieferung der Website, Gewährleistung von
                    Stabilität und Sicherheit sowie Aufklärung im Fall von Angriffen.<br>
                    <strong>Rechtsgrundlage:</strong> Art. 6 Abs. 1 lit. f DSGVO (berechtigtes
                    Interesse am sicheren und störungsfreien Betrieb).<br>
                    <strong>Speicherdauer:</strong> Die Logfiles werden nach spätestens
                    sieben Tagen gelöscht oder anonymisiert, sofern kein sicherheitsrelevanter
                    Vorfall eine längere Aufbewahrung erfordert.
                </p>

                <h2>3 · Verschlüsselung</h2>
                <p>
                    Diese Website nutzt eine Transportverschlüsselung (TLS/HTTPS). Damit sind
                    Daten, die Sie an diese Website übermitteln, auf dem Übertragungsweg für
                    Dritte nicht mitlesbar. Eine verschlüsselte Verbindung erkennen Sie am
                    Schlosssymbol in der Adresszeile Ihres Browsers.
                </p>

                <h2>4 · Kontaktformular</h2>
                <p>
                    Wenn Sie das Kontaktformular nutzen, werden die von Ihnen eingegebenen Daten
                    per E-Mail an mich übermittelt. Verarbeitet werden:
                </p>
                <ul class="checklist is-tight">
                    <li>Name und Unternehmen</li>
                    <li>E-Mail-Adresse, optional Telefonnummer</li>
                    <li>Ihre Angaben zu Funktion, Unternehmensgröße, Anlass und Leistung</li>
                    <li>Ihre Nachricht</li>
                    <li>Zeitpunkt der Absendung sowie eine gekürzte IP-Adresse zur
                        Missbrauchsabwehr</li>
                </ul>
                <p>
                    <strong>Zweck:</strong> Bearbeitung Ihrer Anfrage und, sofern es zu einer
                    Zusammenarbeit kommt, deren Anbahnung.<br>
                    <strong>Rechtsgrundlage:</strong> Art. 6 Abs. 1 lit. b DSGVO (vorvertragliche
                    Maßnahmen) sowie Art. 6 Abs. 1 lit. f DSGVO (berechtigtes Interesse an der
                    Beantwortung von Anfragen).<br>
                    <strong>Speicherdauer:</strong> Ihre Anfrage wird gelöscht, sobald sie
                    abschließend bearbeitet ist und keine gesetzlichen Aufbewahrungspflichten
                    entgegenstehen – spätestens jedoch nach zwölf Monaten, sofern kein
                    Vertragsverhältnis entsteht. Entsteht ein Vertragsverhältnis, gelten die
                    handels- und steuerrechtlichen Aufbewahrungsfristen von bis zu zehn Jahren.
                </p>
                <p>
                    <strong>Eingangsbestätigung:</strong> Nach dem Absenden erhalten Sie
                    automatisch eine E-Mail, die den Eingang bestätigt und den weiteren Ablauf
                    beschreibt. Es handelt sich um eine reine Servicemail; eine
                    Newsletter-Anmeldung erfolgt nicht, und Ihre Adresse wird nicht für Werbung
                    verwendet.
                </p>
                <p>
                    <strong>Spam-Schutz:</strong> Das Formular verwendet ein verstecktes Feld,
                    eine Zeitmessung und eine einfache Rechenaufgabe. Diese Verfahren laufen
                    vollständig auf dem eigenen Server; es wird kein Dienst eines Dritten
                    eingebunden.
                </p>

                <h2>5 · Kontaktaufnahme per E-Mail oder Telefon</h2>
                <p>
                    Wenn Sie mich per E-Mail oder Telefon kontaktieren, werden Ihre Angaben zur
                    Bearbeitung des Anliegens verarbeitet. Rechtsgrundlage und Speicherdauer
                    entsprechen den Angaben unter Ziffer 4.
                </p>

                <h2>6 · Keine Cookies, kein Tracking, keine externen Dienste</h2>
                <p>
                    Diese Website setzt keine Cookies und verwendet keine Verfahren zur Analyse
                    des Nutzungsverhaltens. Es werden keine externen Schriften, keine Karten,
                    keine Videos, keine Schaltflächen sozialer Netzwerke und keine Werkzeuge zur
                    Reichweitenmessung eingebunden. Sämtliche Gestaltungselemente und Skripte
                    werden vom eigenen Server ausgeliefert. Ein Einwilligungsbanner ist deshalb
                    nicht erforderlich.
                </p>

                <h2>7 · Weitergabe von Daten</h2>
                <p>
                    Eine Weitergabe Ihrer Daten an Dritte erfolgt nicht, außer:
                </p>
                <ul class="checklist is-tight">
                    <li>an den Hostinganbieter im Rahmen der Auftragsverarbeitung,</li>
                    <li>an den E-Mail-Dienstleister, über den Nachrichten übermittelt werden,</li>
                    <li>soweit eine gesetzliche Verpflichtung besteht.</li>
                </ul>
                <p>
                    Eine Übermittlung in Länder außerhalb der Europäischen Union findet nicht
                    statt.
                </p>

                <h2>8 · Ihre Rechte</h2>
                <p>Sie haben jederzeit das Recht auf:</p>
                <ul class="checklist is-tight">
                    <li><strong>Auskunft</strong> über die zu Ihrer Person gespeicherten Daten (Art. 15 DSGVO)</li>
                    <li><strong>Berichtigung</strong> unrichtiger Daten (Art. 16 DSGVO)</li>
                    <li><strong>Löschung</strong> (Art. 17 DSGVO)</li>
                    <li><strong>Einschränkung der Verarbeitung</strong> (Art. 18 DSGVO)</li>
                    <li><strong>Datenübertragbarkeit</strong> (Art. 20 DSGVO)</li>
                    <li><strong>Widerspruch</strong> gegen Verarbeitungen auf Grundlage
                        berechtigter Interessen (Art. 21 DSGVO)</li>
                    <li><strong>Widerruf</strong> einer erteilten Einwilligung mit Wirkung für die
                        Zukunft (Art. 7 Abs. 3 DSGVO)</li>
                </ul>
                <p>
                    Zur Ausübung genügt eine formlose Nachricht an die unter Ziffer 1 genannten
                    Kontaktdaten.
                </p>

                <h2>9 · Beschwerderecht</h2>
                <p>
                    Sie haben das Recht, sich bei einer Datenschutz-Aufsichtsbehörde über die
                    Verarbeitung Ihrer personenbezogenen Daten zu beschweren. Zuständig ist
                    grundsätzlich die Behörde Ihres gewöhnlichen Aufenthaltsorts, Ihres
                    Arbeitsplatzes oder des Orts des mutmaßlichen Verstoßes. Für den Sitz des
                    Verantwortlichen ist das Bayerische Landesamt für Datenschutzaufsicht (BayLDA)
                    zuständig.
                </p>

                <h2>10 · Aktualität</h2>
                <p>
                    Diese Datenschutzerklärung wird angepasst, wenn sich die Verarbeitung oder die
                    rechtlichen Rahmenbedingungen ändern. Es gilt jeweils die auf dieser Seite
                    veröffentlichte Fassung.
                </p>

            </div>

            <aside class="page-aside">
                <div class="aside-card">
                    <h2 class="aside-title">Rechtliches</h2>
                    <nav class="aside-nav">
                        <a href="/impressum.php">Impressum<i data-icon="arrow-right" class="lucide"></i></a>
                        <a href="/datenschutz.php" class="is-current" aria-current="page">Datenschutz<i data-icon="arrow-right" class="lucide"></i></a>
                        <a href="/kontakt.php">Kontakt<i data-icon="arrow-right" class="lucide"></i></a>
                    </nav>
                </div>
                <div class="aside-card">
                    <h2 class="aside-title">Grundsatz</h2>
                    <p style="font-size:.93rem;">
                        Eine Beratung, die anderen Governance beibringt, sollte auf der eigenen
                        Website nicht heimlich Daten sammeln. Deshalb: kein Tracking, keine
                        Fremddienste, kein Banner.
                    </p>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
