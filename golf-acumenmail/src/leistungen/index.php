<?php
$page = [
    'title'       => 'Leistungen',
    'description' => 'Drei klar umrissene Pakete für Golfclubs: Clubcheck als Bestandsaufnahme, Saison-Setup für die Einrichtung, Clubbetreuung für den laufenden Betrieb.',
    'section'     => 'leistungen',
    'path'        => 'leistungen/',
    'crumbs'      => [['Leistungen', null]],
    'hero'        => [
        'kicker' => 'Leistungen',
        'h1'     => 'Drei Wege, <span class="accent">im Club anzufangen</span>',
        'lead'   => 'Nicht jeder Club braucht sofort die volle Betreuung. Entscheidend ist, ob zuerst Klarheit, Einrichtung oder ein verlässlicher Rhythmus fehlt.',
        'actions'=> [['Club-Analyse anfragen', 'kontakt.php', 'primary'], ['Preise ansehen', 'preise.php', 'ghost']],
    ],
];
include __DIR__ . '/../partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="link-card-grid">
<?php foreach ($NAV['leistungen']['children'] as $child): ?>
            <a href="<?= e(url($child['url'])) ?>" class="link-card">
                <span class="link-card-icon"><i data-icon="<?= e($child['icon']) ?>" class="lucide"></i></span>
                <h3><?= e($child['label']) ?></h3>
                <p><?= e($child['desc']) ?></p>
                <span class="link-card-more">Ansehen<i data-icon="arrow-right" class="lucide"></i></span>
            </a>
<?php endforeach; ?>
        </div>

        <div class="callout" style="margin-top:2.6rem;">
            <i data-icon="help-circle" class="lucide"></i>
            <p>
                <strong>Unsicher, was passt?</strong>
                Der Clubcheck klärt das in einem Gespräch – und wird beim Saison-Setup verrechnet.
                Wenn dabei herauskommt, dass sich eine Zusammenarbeit für Ihren Club gerade nicht
                lohnt, sagen wir das dort.
            </p>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <span class="section-kicker">Wie wir arbeiten</span>
            <h2 class="section-title">Drei Phasen mit je einem Ergebnis</h2>
            <p class="section-lead">Kein Projekt, das ein halbes Jahr läuft, bevor die erste Mail hinausgeht.</p>
        </div>

        <div class="process-grid">
            <article class="process-phase">
                <div class="phase-top"><span class="phase-number">01</span>
                    <span class="phase-icon"><i data-icon="search" class="lucide"></i></span></div>
                <p class="phase-eyebrow">Bestandsaufnahme</p>
                <h3 class="phase-title">Was da ist, was fehlt</h3>
                <p class="phase-copy">Adressbestand, Einwilligungen, Datenqualität und die bisherige Kommunikation.</p>
                <div class="phase-result"><span>Ergebnis</span><strong>Chancenkarte und Datencheck</strong></div>
            </article>
            <article class="process-phase">
                <div class="phase-top"><span class="phase-number">02</span>
                    <span class="phase-icon"><i data-icon="layers" class="lucide"></i></span></div>
                <p class="phase-eyebrow">Einrichtung</p>
                <h3 class="phase-title">Technik, Design, erste Strecke</h3>
                <p class="phase-copy">Installation, Zustell­barkeit, Clubdesign, Listen, Auto­mationen, Einweisung.</p>
                <div class="phase-result"><span>Ergebnis</span><strong>Versandbereites System</strong></div>
            </article>
            <article class="process-phase">
                <div class="phase-top"><span class="phase-number">03</span>
                    <span class="phase-icon"><i data-icon="repeat" class="lucide"></i></span></div>
                <p class="phase-eyebrow">Saisonbetrieb</p>
                <h3 class="phase-title">Laufen lassen und schärfen</h3>
                <p class="phase-copy">Redaktionsplan, Ausgaben, Tests und ein Bericht am Ende der Saison.</p>
                <div class="phase-result"><span>Ergebnis</span><strong>Routine statt Einzelaktion</strong></div>
            </article>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>\n