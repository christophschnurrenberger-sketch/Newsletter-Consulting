<?php
$page = [
    'title'       => 'Lösungen für Golfclubs',
    'description' => 'Mitgliederbindung, Turnierauslastung, Gastspieler, Neumitglieder und Golfschule: fünf Aufgaben, die in fast jedem Golfclub auf dem Tisch liegen – und wie E-Mail sie löst.',
    'section'     => 'loesungen',
    'path'        => 'loesungen/',
    'crumbs'      => [['Lösungen', null]],
    'hero'        => [
        'kicker' => 'Lösungen',
        'h1'     => 'Fünf Aufgaben, die in jedem Club <span class="accent">auf dem Tisch liegen</span>',
        'lead'   => 'Keine davon ist ein Golfproblem. Alle fünf sind Kommunikationsprobleme – die Information ist da, sie erreicht nur nicht die richtigen Leute zur richtigen Zeit.',
        'actions'=> [['Club-Analyse anfragen', 'kontakt.php', 'primary']],
    ],
];
include __DIR__ . '/../partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="link-card-grid">
<?php foreach ($NAV['loesungen']['children'] as $child): ?>
            <a href="<?= e(url($child['url'])) ?>" class="link-card">
                <span class="link-card-icon"><i data-icon="<?= e($child['icon']) ?>" class="lucide"></i></span>
                <h3><?= e($child['label']) ?></h3>
                <p><?= e($child['desc']) ?></p>
                <span class="link-card-more">Ansehen<i data-icon="arrow-right" class="lucide"></i></span>
            </a>
<?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <span class="section-kicker">Der gemeinsame Nenner</span>
            <h2 class="section-title">Warum das schwarze Brett nicht reicht</h2>
            <p class="section-lead">
                Es erreicht zuverlässig die 80 Mitglieder, die ohnehin dreimal pro Woche da sind.
                Die übrigen 500 erfahren zuletzt davon – oder gar nicht.
            </p>
        </div>

        <div class="split-grid is-top">
            <div class="prose">
                <h3 style="margin-top:0;">Was Clubs typischerweise erleben</h3>
                <ul>
                    <li>Das Turnier ist zwei Wochen vorher zu einem Drittel belegt</li>
                    <li>Neue Mitglieder hören nach der Aufnahme monatelang nichts</li>
                    <li>Von Oktober bis März herrscht Funkstille, im Frühjahr fängt man bei null an</li>
                    <li>Gastspieler zahlen einmal Greenfee und kommen nie wieder</li>
                    <li>Der Rundbrief hängt an einer Person – ist sie im Urlaub, erscheint er nicht</li>
                </ul>
            </div>
            <div class="prose">
                <h3 style="margin-top:0;">Was sich daran ändern lässt</h3>
                <ul>
                    <li>Segmente, damit die Ausschreibung an die passende Handicap-Klasse geht</li>
                    <li>Eine Willkommensstrecke, die ohne Zutun für jedes neue Mitglied läuft</li>
                    <li>Ein Redaktionsplan, der die Wintermonate mitdenkt</li>
                    <li>Eine eigene Liste für Gastspieler mit eigener Ansprache</li>
                    <li>Ein Werkzeug, das jede und jeder im Sekretariat bedienen kann</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>\n