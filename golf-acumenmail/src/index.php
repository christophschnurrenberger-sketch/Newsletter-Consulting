<?php
$page = [
    'title'       => 'Newsletter für Golfclubs: einrichten und betreuen lassen',
    'description' => 'Wir richten Golfclubs den Newsletter ein – System auf dem eigenen Webspace, Clubdesign, Automationen – und schreiben ihn auf Wunsch monatlich. Clubcheck ab 290 €, Einrichtung ab 1.490 €, Betreuung ab 390 € im Monat.',
    'section'     => '',
    'path'        => '',
    'hero'        => false,
    'body_class'  => 'is-home',
];
include __DIR__ . '/partials/header.php';
?>

<!-- Hero ------------------------------------------------------------------
     Aufbau wie bei den guten Anbietern: ein Versprechen, das die eigene
     Schwachstelle benennt, darunter konkret, wer was macht. Die Nummern
     entsprechen den drei Paketen im Abschnitt darunter. -->
<section class="section home-hero">
    <div class="container">
        <p class="section-kicker">Für Golfclubs, Golfanlagen und Golfschulen</p>

        <h1 class="home-hero-title">
            Der Clubnewsletter, der wirklich rausgeht.
            <span class="mark">Dafür sorgen wir.</span>
        </h1>

        <div class="home-hero-foot">
            <div>
                <p class="home-hero-lead">
                    Im Sekretariat fehlt selten die Idee, meistens die Zeit. Wir übernehmen
                    die Arbeit dazwischen – vom Blick auf den Adressbestand bis zur fertigen
                    Ausgabe im Postfach Ihrer Mitglieder.
                </p>

                <ol class="home-hero-points">
                    <li><span><b>Nachsehen, was da ist.</b>
                        Adressen, Einwilligungen, Hosting – bevor irgendjemand irgendetwas einrichtet.</span></li>
                    <li><span><b>Das System hinstellen.</b>
                        Auf dem Webspace Ihres Clubs, im Clubdesign, mit den ersten Auto­mationen.</span></li>
                    <li><span><b>Schreiben, wenn Sie wollen.</b>
                        Monatliche Ausgaben, Turnierpost, ein Bericht für den Vorstand.</span></li>
                </ol>
            </div>

            <div>
                <p class="home-hero-note">
                    Ein fester Ansprechpartner. Keine Kosten pro Kontakt, keine Preisstufe ab
                    1.200 Mitgliedern – die Software läuft bei Ihnen und bleibt bei Ihnen.
                </p>
                <div class="home-hero-actions">
                    <a href="<?= e(url('kontakt.php')) ?>" class="btn-primary-custom">Kostenlose Club-Analyse</a>
                    <a href="<?= e(url('preise.php')) ?>" class="btn-secondary">Was das kostet</a>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="trust-strip">
    <div class="container trust-strip-inner">
        <span class="trust-strip-item"><i data-icon="server" class="lucide"></i>Läuft auf Ihrem eigenen Webspace</span>
        <span class="trust-strip-item"><i data-icon="euro" class="lucide"></i>Keine Kosten pro Kontakt</span>
        <span class="trust-strip-item"><i data-icon="lock" class="lucide"></i>Mitgliederdaten bleiben im Club</span>
        <span class="trust-strip-item"><i data-icon="user-check" class="lucide"></i>Ein fester Ansprechpartner</span>
    </div>
</div>

<!-- Das Angebot: drei Pakete, mit Preisen ---------------------------------- -->
<section class="section">
    <div class="container">
        <div class="section-head animate-on-scroll">
            <p class="section-kicker">Das Angebot</p>
            <h2 class="section-title">Erst nachsehen. Dann einrichten. Dann laufen lassen.</h2>
            <p class="section-lead">
                Drei Pakete, die aufeinander aufbauen – nach jedem ist Schluss möglich. Die
                meisten Clubs fangen mit dem Clubcheck an: Danach steht schwarz auf weiß, was
                Ihr Adressbestand hergibt und wo der erste Hebel liegt.
            </p>
        </div>

        <div class="package-grid">
            <article class="package-card animate-on-scroll">
                <p class="package-label">Schritt 1 · Klarheit</p>
                <h3>Clubcheck</h3>
                <p>Was Ihr Adressbestand hergibt, was rechtlich trägt und wo der erste Hebel liegt.</p>
                <ul class="package-list">
                    <li><i data-icon="check" class="lucide"></i><span>Adressen, Einwilligungen, Datenqualität</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Technikcheck Ihres Hostings</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Chancenkarte und Gespräch mit dem Vorstand</span></li>
                </ul>
                <div class="package-meta">
                    <span>Investition</span>
                    <strong>ab 290 € einmalig</strong>
                    <small>Wird beim Saison-Setup verrechnet.</small>
                </div>
                <p style="margin-top:1.2rem;"><a href="<?= e(url('leistungen/clubcheck.php')) ?>" class="btn-secondary" style="width:100%;">Details ansehen</a></p>
            </article>

            <article class="package-card is-featured animate-on-scroll">
                <span class="package-flag">Häufig gewählt</span>
                <p class="package-label">Schritt 2 · Einrichtung</p>
                <h3>Saison-Setup</h3>
                <p>Ein versandbereites System im Design Ihres Clubs – Software inklusive, sie gehört danach dem Club.</p>
                <ul class="package-list">
                    <li><i data-icon="check" class="lucide"></i><span>Newsletter-System auf dem Clubserver eingerichtet</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Zustell­barkeit: SMTP, SPF, DKIM, Bounces</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Clubdesign, Anmeldeformular, Listen und Segmente</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Zwei Auto­mationen, Redaktionsplan, Einweisung</span></li>
                </ul>
                <div class="package-meta">
                    <span>Investition</span>
                    <strong>ab 1.490 € einmalig</strong>
                    <small>Danach keine laufenden Lizenzkosten.</small>
                </div>
                <p style="margin-top:1.2rem;"><a href="<?= e(url('leistungen/saison-setup.php')) ?>" class="btn-primary-custom" style="width:100%;">Details ansehen</a></p>
            </article>

            <article class="package-card animate-on-scroll">
                <p class="package-label">Schritt 3 · Rhythmus</p>
                <h3>Clubbetreuung</h3>
                <p>Für Clubs, bei denen der Newsletter sonst wieder liegen bleibt: Wir schreiben und versenden.</p>
                <ul class="package-list">
                    <li><i data-icon="check" class="lucide"></i><span>Monatliche Ausgaben nach Redaktionsplan</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Turnier- und Eventkommunikation</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Auswertung und Bericht für den Vorstand</span></li>
                </ul>
                <div class="package-meta">
                    <span>Investition</span>
                    <strong>ab 390 € / Monat</strong>
                    <small>Monatlich kündbar, kein Jahresvertrag.</small>
                </div>
                <p style="margin-top:1.2rem;"><a href="<?= e(url('leistungen/clubbetreuung.php')) ?>" class="btn-secondary" style="width:100%;">Details ansehen</a></p>
            </article>
        </div>

        <p style="margin-top:2.5rem;">
            <a href="<?= e(url('preise.php')) ?>" class="btn-secondary">Alle Preise und was enthalten ist</a>
        </p>
    </div>
</section>

<!-- Was danach anders ist --------------------------------------------------
     Hier stand einmal eine Rechnung: zwoelf Ausgaben mal dreissig Minuten
     gleich sechs Stunden im Jahr. Sie stimmte – und arbeitete gegen das
     eigene Angebot. Sechs Stunden sind nichts; wer das liest, fragt sich
     zu Recht, wofuer er dann jemanden bezahlen soll. Der Unterschied liegt
     nicht im Aufwand, sondern darin, was danach passiert. -->
<section class="section section-dark">
    <div class="container">
        <div class="section-head animate-on-scroll">
            <p class="section-kicker on-dark">Nach der Einrichtung</p>
            <h2 class="section-title">Was danach anders ist</h2>
            <p class="section-lead">
                Der Unterschied liegt nicht darin, dass mehr geschrieben wird. Er liegt darin,
                dass die Ausgabe ankommt, die Liste sich selbst pflegt und am Jahresende
                nachlesbar ist, wen sie erreicht hat und wen nicht.
            </p>
        </div>

        <div class="table-scroll animate-on-scroll">
            <table class="data-table">
                <caption>Der Vergleich beschreibt den Zustand, den wir in Clubs regelmäßig antreffen.</caption>
                <thead>
                    <tr>
                        <th scope="col">&nbsp;</th>
                        <th scope="col">Heute</th>
                        <th scope="col">Mit System</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Wer es erfährt</th>
                        <td class="no">wer zufällig im Clubhaus vorbeigeht</td>
                        <td class="yes">alle im Verteiler, auch im Winter</td>
                    </tr>
                    <tr>
                        <th scope="row">Ob es ankommt</th>
                        <td class="no">Sammelmail im Blindkopie-Feld, oft im Spam</td>
                        <td class="yes">eigene Domain, mit SPF und DKIM eingerichtet</td>
                    </tr>
                    <tr>
                        <th scope="row">Tote Adressen</th>
                        <td class="no">bleiben jahrelang in der Liste</td>
                        <td class="yes">werden erkannt und stillgelegt</td>
                    </tr>
                    <tr>
                        <th scope="row">Abmeldungen</th>
                        <td class="no">per Zuruf ans Sekretariat</td>
                        <td class="yes">Abmeldelink, rechtssicher, ohne Zutun</td>
                    </tr>
                    <tr>
                        <th scope="row">Was der Vorstand sieht</th>
                        <td class="no">nichts – die Mail ist weg, mehr weiß niemand</td>
                        <td class="yes">Zustellungen, Öffnungen, Klicks je Ausgabe</td>
                    </tr>
                    <tr>
                        <th scope="row">Im Juni</th>
                        <td class="no">bleibt der Newsletter liegen</td>
                        <td class="yes">Redaktionsplan und Auto­mationen laufen weiter</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Der Unterschied zu den bekannten Anbietern ----------------------------- -->
<section class="section section-alt">
    <div class="container">
        <div class="section-head animate-on-scroll">
            <p class="section-kicker">Der Unterschied</p>
            <h2 class="section-title">Kein SaaS. Keine Abhängigkeit.</h2>
            <p class="section-lead">
                Die Software gehört Ihrem Club – vom Tag der Einrichtung an. Das ist kein
                technisches Detail, das ist der Unterschied zu allem, was Sie sonst angeboten
                bekommen.
            </p>
        </div>

        <div class="split-grid is-wide-left is-top">
            <div class="animate-on-scroll">
                <ul class="checklist">
                    <li><i data-icon="check" class="lucide"></i><span><b>Einmalige Einrichtung statt Monatsgebühr.</b>
                        Für die Software selbst zahlen Sie danach nichts mehr.</span></li>
                    <li><i data-icon="check" class="lucide"></i><span><b>Läuft auf Ihrem Server.</b>
                        Auf dem Webspace, den Ihr Club ohnehin bezahlt – ohne zusätzlichen Vertrag.</span></li>
                    <li><i data-icon="check" class="lucide"></i><span><b>Mitgliederdaten bleiben bei Ihnen.</b>
                        Kein Auftragsverarbeiter, keine Adressen bei Dritten.</span></li>
                    <li><i data-icon="check" class="lucide"></i><span><b>Keine Kosten pro Kontakt.</b>
                        Ob 900 oder 3.000 Adressen – an der Rechnung ändert das nichts.</span></li>
                    <li><i data-icon="check" class="lucide"></i><span><b>Keine Abhängigkeit von einem Anbieter,</b>
                        der Tarife, Bedingungen oder Funktionsumfang ändert.</span></li>
                    <li><i data-icon="check" class="lucide"></i><span><b>Auch wenn unsere Zusammenarbeit endet,</b>
                        bleibt das System im Club – mit Adressen, Vorlagen und Historie.</span></li>
                </ul>
            </div>

            <div class="callout animate-on-scroll">
                <i data-icon="help-circle" class="lucide"></i>
                <p>
                    <strong>Und wenn Sie uns nicht mehr wollen?</strong>
                    Dann bleibt alles da: Adressen, Vorlagen, Auto­mationen, die gesamte Historie.
                    Das System läuft weiter, auch ohne uns – bei einer Mietlösung geht genau das
                    nicht.
                </p>
            </div>
        </div>

        <div class="table-scroll animate-on-scroll" style="margin-top:3rem;">
            <table class="data-table">
                <caption>Beispielrechnung. Hosting fällt in beiden Fällen an und ist deshalb nicht aufgeführt.</caption>
                <thead>
                    <tr>
                        <th scope="col">&nbsp;</th>
                        <th scope="col">Mietlösung</th>
                        <th scope="col">Eigenes System</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Laufend pro Monat</th>
                        <td class="no">je nach Tarif und Verteilergröße</td>
                        <td class="yes">0 € für die Software</td>
                    </tr>
                    <tr>
                        <th scope="row">Verteiler wächst</th>
                        <td class="no">nächste Preisstufe</td>
                        <td class="yes">ändert nichts</td>
                    </tr>
                    <tr>
                        <th scope="row">Mitgliederdaten</th>
                        <td class="no">beim Anbieter</td>
                        <td class="yes">auf dem Clubserver</td>
                    </tr>
                    <tr>
                        <th scope="row">Wenn Sie aufhören</th>
                        <td class="no">Daten und Vorlagen weg</td>
                        <td class="yes">alles bleibt im Club</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p style="margin-top:2.2rem;">
            <a href="<?= e(url('preise.php')) ?>" class="btn-secondary">Die Rechnung über fünf Jahre</a>
        </p>
    </div>
</section>

<!-- Die Software, die im Setup steckt -------------------------------------- -->
<section class="section">
    <div class="container">

        <div class="tool-intro-grid">
            <div class="animate-on-scroll">
                <p class="section-kicker">Im Saison-Setup enthalten</p>
                <h2 class="section-title">Ein Newslettersystem, gebaut für Golfclubs</h2>
                <p class="section-lead">
                    Kein zurechtgebogenes Allzweckwerkzeug: Listen, Segmente und Auto­mationen
                    sind auf das zugeschnitten, was in einem Club anfällt – Mitglieder,
                    Gastspieler, Kursteilnehmer, Turnierfelder. Bedienen lässt es sich ohne eine
                    Zeile HTML.
                </p>
                <p style="margin-top:2.2rem;">
                    <a href="<?= e(url('software/')) ?>" class="btn-primary-custom">Alle Funktionen</a>
                </p>
            </div>

            <div class="tool-claim-list animate-on-scroll">
                <div class="tool-claim">
                    <strong>0 € im Monat</strong>
                    <span>Für die Software selbst. Ob 300 oder 3.000 Mitglieder ändert daran nichts.</span>
                </div>
                <div class="tool-claim">
                    <strong>Auf Ihrem Webspace</strong>
                    <span>Kein zusätzlicher Vertrag, kein eigener Server. Wir richten es dort ein,
                    wo Ihre Website ohnehin liegt.</span>
                </div>
                <div class="tool-claim">
                    <strong>Rechtssicher ab Werk</strong>
                    <span>Double-Opt-in mit Protokoll, Abmeldelink, Impressum, List-Unsubscribe.
                    Der Footer lässt sich nicht wegklicken.</span>
                </div>
            </div>
        </div>

        <figure class="demo-frame home-demo animate-on-scroll" data-demo-frame>
            <div class="demo-frame-kopf">
                <span>Newslettersystem <span class="ort">Golfclub Musterhausen</span></span>
                <span class="hinweis">Ansicht – nicht bedienbar</span>
            </div>
            <div class="demo-frame-buehne">
                <iframe src="<?= e(url('demo/baukasten.html')) ?>" loading="lazy"
                        title="Der Newsletter-Baukasten: links die Bausteine, in der Mitte die Ausgabe mit Überschrift, Text und Knopf, rechts die Gestaltung."></iframe>
            </div>
            <figcaption>
                <b>Kein Bild.</b>
                <span>Die Oberfläche selbst, mit einer Ausgabe mitten im Schreiben. Drei Bausteine,
                kein HTML. <a href="<?= e(url('software/newsletter-baukasten.php')) ?>">Wie der
                Baukasten arbeitet</a></span>
            </figcaption>
        </figure>

        <div class="tool-feature-grid animate-on-scroll" aria-label="Funktionen des Newsletter-Systems">
<?php foreach ($NAV['software']['children'] as $child): ?>
            <a href="<?= e(url($child['url'])) ?>" class="tool-feature">
                <strong><?= e($child['label']) ?></strong>
                <p><?= e($child['desc']) ?></p>
            </a>
<?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Wofür Clubs uns holen --------------------------------------------------- -->
<section class="section section-alt">
    <div class="container">
        <div class="section-head animate-on-scroll">
            <p class="section-kicker">Wo es sich auszahlt</p>
            <h2 class="section-title">Fünf Stellen, an denen im Club etwas liegen bleibt</h2>
            <p class="section-lead">
                Überall ist der Kontakt längst da – er wird nur nicht genutzt. Zu jeder Stelle
                gibt es eine Seite mit dem, was wir konkret einrichten.
            </p>
        </div>

        <div class="link-card-grid">
<?php foreach ($NAV['loesungen']['children'] as $child): ?>
            <a href="<?= e(url($child['url'])) ?>" class="link-card animate-on-scroll">
                <h3><?= e($child['label']) ?></h3>
                <p><?= e($child['desc']) ?></p>
                <span class="link-card-more">Ansehen<i data-icon="arrow-right" class="lucide"></i></span>
            </a>
<?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Unser Vorgehen ---------------------------------------------------------- -->
<section class="section">
    <div class="container">
        <div class="section-head animate-on-scroll">
            <p class="section-kicker">Unser Vorgehen</p>
            <h2 class="section-title">Wir sehen erst nach, dann richten wir ein</h2>
            <p class="section-lead">
                Fünf Dinge prüfen wir, bevor irgendetwas installiert wird. Danach wissen Sie,
                was Ihr Bestand hergibt – und wir wissen, wo anzufangen ist.
            </p>
        </div>

        <div class="capability-grid">
            <article class="capability-card animate-on-scroll">
                <h3>Adressbestand</h3>
                <p>Wie viele Adressen es gibt, wie aktuell sie sind und wie viele Dubletten und
                tote Einträge darin stecken.</p>
                <ul class="capability-list">
                    <li><i data-icon="check" class="lucide"></i><span>Abgleich mit der Clubverwaltung</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Dubletten und Tippfehler</span></li>
                </ul>
            </article>

            <article class="capability-card animate-on-scroll">
                <h3>Einwilligungen</h3>
                <p>Woher die Adressen stammen und ob eine belastbare Grundlage für den Versand
                besteht – bevor die erste Mail hinausgeht.</p>
                <ul class="capability-list">
                    <li><i data-icon="check" class="lucide"></i><span>Herkunft je Adressquelle</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Vereinsinformation oder Werbung</span></li>
                </ul>
            </article>

            <article class="capability-card animate-on-scroll">
                <h3>Bisherige Kommunikation</h3>
                <p>Was in den letzten zwei Jahren hinausging und was daraus wurde. Oft liegt hier
                schon die Antwort, woran es hakt.</p>
                <ul class="capability-list">
                    <li><i data-icon="check" class="lucide"></i><span>Anlässe und Rhythmus</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Was liegen geblieben ist</span></li>
                </ul>
            </article>

            <article class="capability-card animate-on-scroll">
                <h3>Technik</h3>
                <p>Ob Ihr Hosting das System trägt und ob der Versand über Ihre Domain sauber
                aufgesetzt ist.</p>
                <ul class="capability-list">
                    <li><i data-icon="check" class="lucide"></i><span>Webspace und Postfach</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>SPF und DKIM</span></li>
                </ul>
            </article>

            <article class="capability-card animate-on-scroll">
                <h3>Segmente</h3>
                <p>Welche Gruppen sich aus Ihren Daten überhaupt bilden lassen – Mitglieder,
                Gäste, Kursteilnehmer, Handicap-Bereiche.</p>
                <ul class="capability-list">
                    <li><i data-icon="check" class="lucide"></i><span>Was heute schon möglich ist</span></li>
                    <li><i data-icon="check" class="lucide"></i><span>Was zuerst gepflegt gehört</span></li>
                </ul>
            </article>
        </div>

        <p style="margin-top:2.5rem;">
            <a href="<?= e(url('leistungen/clubcheck.php')) ?>" class="btn-secondary">Was im Clubcheck steckt</a>
        </p>
    </div>
</section>

<!-- Wissen ----------------------------------------------------------------- -->
<section class="section section-alt">
    <div class="container">
        <div class="split-grid is-wide-right is-top">
            <div class="animate-on-scroll">
                <p class="section-kicker">Wissen</p>
                <h2 class="section-title">Aus der Praxis im Club</h2>
                <p class="section-lead">
                    Zum Nachlesen, auch wenn daraus nie eine Zusammenarbeit wird.
                </p>
                <p style="margin-top:1.8rem;">
                    <a href="<?= e(url('wissen/')) ?>" class="btn-secondary">Alle Beiträge</a>
                </p>
            </div>

            <div class="related-grid animate-on-scroll">
                <a href="<?= e(url('wissen/newsletter-jahresplan-golfclub.php')) ?>" class="related-card">
                    <span>Redaktionsplan</span><strong>Der Newsletter-Jahresplan</strong>
                    <p>Zwölf Monate Clubkommunikation – und warum der Winter der wichtigste Teil ist.</p>
                </a>
                <a href="<?= e(url('wissen/betreffzeilen-golfclub.php')) ?>" class="related-card">
                    <span>Redaktion</span><strong>Betreffzeilen, die wirken</strong>
                    <p>Warum „Newsletter 04/2026“ die schlechteste aller Betreffzeilen ist.</p>
                </a>
                <a href="<?= e(url('wissen/dsgvo-mitgliederdaten-golfclub.php')) ?>" class="related-card">
                    <span>Recht</span><strong>Mitgliederdaten und DSGVO</strong>
                    <p>Wo Vereinsinformation aufhört und Werbung anfängt.</p>
                </a>
                <a href="<?= e(url('faq.php')) ?>" class="related-card">
                    <span>FAQ</span><strong>Häufige Fragen</strong>
                    <p>Recht, Aufwand, PC CADDIE, Kosten – kompakt beantwortet.</p>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
