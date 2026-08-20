<?php
/**
 * Impressum.
 *
 * >>> VOR DEM ONLINESTELLEN PRÜFEN <<<
 * 1. Umsatzsteuer: Entweder Kleinunternehmerregelung nach § 19 UStG (dann keine
 *    USt ausweisen – und die Preisseite entsprechend anpassen, dort steht
 *    derzeit „zzgl. USt.“) oder Regelbesteuerung mit USt-IdNr. Beides muss
 *    zusammenpassen.
 * 2. Berufshaftpflichtversicherung: Angabe ist für Unternehmensberatung nicht
 *    zwingend, wirkt aber vertrauensbildend, sobald eine besteht.
 * 3. Telefonnummer und E-Mail müssen zu den tatsächlich genutzten Anschlüssen
 *    passen.
 */
$page = [
    'title'       => 'Impressum',
    'description' => 'Impressum und Anbieterkennzeichnung nach § 5 Digitale-Dienste-Gesetz.',
    'section'     => '',
    'path'        => 'impressum.php',
    'crumbs'      => [['Impressum', null]],
    'cta'         => false,
    'hero'        => [
        'kicker' => 'Rechtliches',
        'h1'     => 'Impressum',
        'lead'   => 'Angaben gemäß § 5 Digitale-Dienste-Gesetz (DDG).',
    ],
];
include __DIR__ . '/partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="sidebar-layout">
            <div class="prose">

                <h2>Diensteanbieter</h2>
                <p>
                    <?= e($SITE['owner']) ?><br>
                    <?= e($SITE['street']) ?><br>
                    <?= e($SITE['city']) ?><br>
                    <?= e($SITE['country']) ?>
                </p>

                <h2>Kontakt</h2>
                <p>
                    Telefon: <a href="tel:<?= e($SITE['phone_link']) ?>"><?= e($SITE['phone']) ?></a><br>
                    E-Mail: <a href="mailto:<?= e($SITE['email']) ?>"><?= e($SITE['email']) ?></a><br>
                    Web: <?= e(preg_replace('#^https?://#', '', $SITE['domain'])) ?>
                </p>

                <h2>Umsatzsteuer</h2>
                <p>
                    Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz:
                    <strong>[bitte eintragen]</strong>
                </p>
                <p class="muted" style="font-size:.92rem;">
                    Hinweis für die Einrichtung: Wird die Kleinunternehmerregelung nach § 19 UStG
                    in Anspruch genommen, ist dieser Absatz durch den entsprechenden Hinweis zu
                    ersetzen – und die Preisangaben auf der Website sind anzupassen, da dort
                    Nettopreise zuzüglich Umsatzsteuer ausgewiesen werden.
                </p>

                <h2>Redaktionell verantwortlich</h2>
                <p>
                    Verantwortlich für den Inhalt nach § 18 Abs. 2 Medienstaatsvertrag (MStV):<br>
                    <?= e($SITE['owner']) ?>, <?= e($SITE['street']) ?>, <?= e($SITE['city']) ?>
                </p>

                <h2>Berufsbezeichnung und Tätigkeit</h2>
                <p>
                    Unternehmensberatung im Bereich IT-Governance, IT-Organisation und
                    IT-Prozesse. Es handelt sich nicht um eine Rechts-, Steuer- oder
                    Wirtschaftsprüfungsleistung; entsprechende Tätigkeiten werden nicht erbracht.
                </p>

                <h2>Hinweis zur Rechtsberatung</h2>
                <p>
                    Die auf dieser Website dargestellten Leistungen und Inhalte stellen keine
                    Rechtsdienstleistung im Sinne des Rechtsdienstleistungsgesetzes (RDG) dar.
                    Rechtliche Prüfungen, Auslegungen und Bewertungen – insbesondere zur
                    Anwendbarkeit gesetzlicher Vorgaben, zu Haftungsfragen, zu Meldepflichten und
                    zur Vertragsgestaltung – erfolgen ausschließlich durch zugelassene
                    Rechtsanwältinnen und Rechtsanwälte.
                </p>

                <h2>EU-Streitschlichtung</h2>
                <p>
                    Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung
                    (OS) bereit: <a href="https://ec.europa.eu/consumers/odr/" rel="noopener">https://ec.europa.eu/consumers/odr/</a>.
                    Die E-Mail-Adresse finden Sie oben in diesem Impressum.
                </p>

                <h2>Verbraucherstreitbeilegung</h2>
                <p>
                    Ich bin nicht bereit oder verpflichtet, an Streitbeilegungsverfahren vor einer
                    Verbraucherschlichtungsstelle teilzunehmen.
                </p>

                <h2>Haftung für Inhalte</h2>
                <p>
                    Als Diensteanbieter bin ich gemäß § 7 Abs. 1 DDG für eigene Inhalte auf diesen
                    Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 DDG bin
                    ich als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder
                    gespeicherte fremde Informationen zu überwachen oder nach Umständen zu
                    forschen, die auf eine rechtswidrige Tätigkeit hinweisen. Verpflichtungen zur
                    Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen
                    Gesetzen bleiben hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst
                    ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich. Bei
                    Bekanntwerden von entsprechenden Rechtsverletzungen werde ich diese Inhalte
                    umgehend entfernen.
                </p>

                <h2>Haftung für Links</h2>
                <p>
                    Dieses Angebot enthält gegebenenfalls Links zu externen Websites Dritter, auf
                    deren Inhalte ich keinen Einfluss habe. Deshalb kann ich für diese fremden
                    Inhalte keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist
                    stets der jeweilige Anbieter oder Betreiber verantwortlich. Die verlinkten
                    Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße
                    überprüft; rechtswidrige Inhalte waren zu diesem Zeitpunkt nicht erkennbar.
                    Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist ohne konkrete
                    Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von
                    Rechtsverletzungen werde ich derartige Links umgehend entfernen.
                </p>

                <h2>Urheberrecht</h2>
                <p>
                    Die auf diesen Seiten erstellten Inhalte und Werke unterliegen dem deutschen
                    Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der
                    Verwertung außerhalb der Grenzen des Urheberrechts bedürfen der schriftlichen
                    Zustimmung. Downloads und Kopien dieser Seite sind nur für den privaten, nicht
                    kommerziellen Gebrauch gestattet. Die frei zugänglichen Leitfäden im
                    Wissensbereich dürfen für interne Zwecke des eigenen Unternehmens genutzt und
                    ausgedruckt werden.
                </p>

                <h2>Inhaltliche Hinweise</h2>
                <p>
                    Die auf dieser Website dargestellten fachlichen Inhalte geben den
                    Kenntnisstand zum Zeitpunkt der Erstellung wieder. Regulatorische
                    Anforderungen, Normen und deren Auslegung ändern sich; verbindlich sind allein
                    die jeweiligen Gesetzestexte, Normfassungen und behördlichen Auslegungen.
                    Preisangaben sind unverbindliche Rahmenwerte und stellen kein Angebot im
                    Rechtssinne dar; verbindlich ist ausschließlich ein individuelles schriftliches
                    Angebot.
                </p>

            </div>

            <aside class="page-aside">
                <div class="aside-card">
                    <h2 class="aside-title">Rechtliches</h2>
                    <nav class="aside-nav">
                        <a href="/impressum.php" class="is-current" aria-current="page">Impressum<i data-icon="arrow-right" class="lucide"></i></a>
                        <a href="/datenschutz.php">Datenschutz<i data-icon="arrow-right" class="lucide"></i></a>
                        <a href="/kontakt.php">Kontakt<i data-icon="arrow-right" class="lucide"></i></a>
                    </nav>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
