<?php
$page = [
    'title'       => 'Wissen',
    'description' => 'Aus der Praxis im Golfclub: Newsletter-Jahresplan, Betreffzeilen, die gelesen werden, und was beim Umgang mit Mitgliederdaten rechtlich gilt.',
    'section'     => 'wissen',
    'path'        => 'wissen/',
    'crumbs'      => [['Wissen', null]],
    'hero'        => [
        'kicker' => 'Wissen',
        'h1'     => 'Aus der Praxis im Club – <span class="accent">ohne Marketing-Sprech</span>',
        'lead'   => 'Was in Golfclubs tatsächlich funktioniert, was nicht, und woran es meistens liegt. Zum Nachlesen, auch wenn daraus keine Zusammenarbeit wird.',
    ],
];
include __DIR__ . '/../partials/header.php';

$beitraege = [
    [
        'url'   => 'wissen/newsletter-jahresplan-golfclub.php',
        'icon'  => 'calendar',
        'label' => 'Redaktionsplan',
        'title' => 'Der Newsletter-Jahresplan für Golfclubs',
        'text'  => 'Zwölf Monate Clubkommunikation im Überblick – mit den Anlässen, die sich in jeder Saison wiederholen, und den Monaten, in denen die meisten Clubs schweigen.',
        'zeit'  => '8 Minuten',
    ],
    [
        'url'   => 'wissen/betreffzeilen-golfclub.php',
        'icon'  => 'mail',
        'label' => 'Redaktion',
        'title' => 'Betreffzeilen, die im Club wirklich geöffnet werden',
        'text'  => 'Warum „Newsletter Ausgabe 04/2026“ die schlechteste aller Betreffzeilen ist – und welche fünf Muster in Golfclubs zuverlässig funktionieren.',
        'zeit'  => '6 Minuten',
    ],
    [
        'url'   => 'wissen/dsgvo-mitgliederdaten-golfclub.php',
        'icon'  => 'lock',
        'label' => 'Recht',
        'title' => 'Mitgliederdaten rechtssicher für den Newsletter nutzen',
        'text'  => 'Wo Vereinsinformation aufhört und Werbung anfängt, warum Double-Opt-in auch bei Mitgliedern sinnvoll ist und welche Nachweise Sie im Streitfall brauchen.',
        'zeit'  => '9 Minuten',
    ],
];
?>

<section class="section">
    <div class="container">
        <div class="link-card-grid">
<?php foreach ($beitraege as $b): ?>
            <a href="<?= e(url($b['url'])) ?>" class="link-card">
                <span class="link-card-icon"><i data-icon="<?= e($b['icon']) ?>" class="lucide"></i></span>
                <p class="capability-index"><?= e($b['label']) ?> · <?= e($b['zeit']) ?> Lesezeit</p>
                <h3><?= e($b['title']) ?></h3>
                <p><?= e($b['text']) ?></p>
                <span class="link-card-more">Lesen<i data-icon="arrow-right" class="lucide"></i></span>
            </a>
<?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="split-grid is-wide-left">
            <div>
                <span class="section-kicker">Häufige Fragen</span>
                <h2 class="section-title">Recht, Aufwand, PC CADDIE, Kosten</h2>
                <p class="section-lead">
                    Die zehn Fragen, die uns Clubs am häufigsten stellen – kompakt beantwortet,
                    auch dort, wo die Antwort unbequem ist.
                </p>
                <p style="margin-top:1.6rem;">
                    <a href="<?= e(url('faq.php')) ?>" class="btn-primary-custom">Zu den häufigen Fragen</a>
                </p>
            </div>
            <div class="quote-card">
                <blockquote>
                    „Wir hatten den Newsletter zweimal angefangen und zweimal wieder eingestellt.
                    Nicht weil er schlecht war, sondern weil im Juni niemand Zeit hatte, ihn zu
                    schreiben.“
                </blockquote>
                <figcaption>Sinngemäß aus mehreren Gesprächen mit Clubsekretariaten – und genau
                    der Grund, warum Automationen und ein Redaktionsplan mehr bringen als die
                    schönste Vorlage.</figcaption>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
