<?php
/**
 * neu.php – der Assistent zum Anlegen.
 *
 * Zwei Fragen, dann steht man im Baukasten:
 *   1. Um welche E-Mail geht es? (Newsletter, Automation, Systemmail)
 *   2. Unter welcher Marke?
 *
 * Beides mit einer echten kleinen Vorschau daneben, damit man sieht,
 * worauf man klickt, statt Begriffe raten zu müssen. Wer den Weg kennt,
 * kann jeden Schritt überspringen: ?art=newsletter springt gleich zur
 * Marke, und wo es nur eine Möglichkeit gibt, wird nicht gefragt.
 */

require_once dirname(__DIR__) . '/lib/bootstrap.php';
Auth::require('kampagnen');

/** Die drei Wege – Reihenfolge ist die Reihenfolge auf der Seite. */
$arten = [
    'newsletter' => [
        'titel'    => 'Newsletter',
        'text'     => 'Eine einzelne Ausgabe an eine Liste – heute geschrieben, heute oder später verschickt.',
        'wann'     => 'Der Normalfall',
        'weiter'   => 'kampagne.php?neu=1&marke=',
    ],
    'automation' => [
        'titel'    => 'Automation',
        'text'     => 'Eine Strecke, die von selbst läuft: erste Mail sofort, zweite nach drei Tagen, dritte nur bei Interesse.',
        'wann'     => 'Läuft dauerhaft',
        'weiter'   => 'automationen.php?neu=1&marke=',
    ],
    'systemmail' => [
        'titel'    => 'Systemmail ändern',
        'text'     => 'Anmeldebestätigung (Double-Opt-in), Begrüßung und Abmeldebestätigung – die verschickt das System selbst.',
        'wann'     => 'Gibt es schon',
        'weiter'   => 'systemmails.php?marke=',
    ],
];

/**
 * Eine kleine Zeichnung zu jeder Art.
 *
 * Bewusst als SVG im Seitenquelltext und nicht als Bilddatei: keine
 * zusätzliche Anfrage, keine Datei, die beim Hochladen vergessen werden
 * kann – und scharf auf jedem Bildschirm.
 */
function art_mockup(string $art): string
{
    $navy = '#14243A'; $rot = '#C8102E'; $grau = '#E0E6ED'; $hell = '#F6F8FA'; $mittel = '#B9C2CE';

    // Ein Blatt: Kopfbalken, Textzeilen, Knopf, Fußbalken
    $blatt = static function (float $x, float $y, float $b, float $h) use ($navy, $rot, $grau, $mittel): string {
        $s  = '<rect x="' . $x . '" y="' . $y . '" width="' . $b . '" height="' . $h . '" rx="3" fill="#fff" stroke="' . $grau . '"/>';
        $s .= '<rect x="' . $x . '" y="' . $y . '" width="' . $b . '" height="' . ($h * .16) . '" rx="3" fill="' . $navy . '"/>';
        for ($i = 0; $i < 3; $i++) {
            $s .= '<rect x="' . ($x + $b * .12) . '" y="' . ($y + $h * .3 + $i * $h * .12) . '" width="'
                . ($b * (0.76 - $i * 0.14)) . '" height="' . max(2, $h * .05) . '" rx="1" fill="' . $mittel . '"/>';
        }
        $s .= '<rect x="' . ($x + $b * .12) . '" y="' . ($y + $h * .7) . '" width="' . ($b * .34)
            . '" height="' . ($h * .1) . '" rx="2" fill="' . $rot . '"/>';
        $s .= '<rect x="' . $x . '" y="' . ($y + $h * .88) . '" width="' . $b . '" height="' . ($h * .12) . '" fill="' . $grau . '"/>';
        return $s;
    };

    $kopf = '<svg viewBox="0 0 240 150" width="100%" height="100%" role="img" aria-hidden="true">'
        . '<rect width="240" height="150" fill="' . $hell . '"/>';

    if ($art === 'automation') {
        // Drei Blätter hintereinander, dazwischen die Wartezeit
        $s = $kopf . $blatt(14, 30, 58, 90) . $blatt(91, 30, 58, 90) . $blatt(168, 30, 58, 90);
        foreach ([[72, 91], [149, 168]] as $i => $p) {
            $mitte = ($p[0] + $p[1]) / 2;
            $s .= '<path d="M' . ($p[0] + 2) . ' 75 H' . ($p[1] - 6) . '" stroke="' . $navy . '" stroke-width="2"/>'
                . '<path d="M' . ($p[1] - 8) . ' 71 l6 4 -6 4 z" fill="' . $navy . '"/>'
                . '<circle cx="' . $mitte . '" cy="58" r="7" fill="#fff" stroke="' . $navy . '" stroke-width="1.5"/>'
                . '<path d="M' . $mitte . ' 54 V58 h3" stroke="' . $navy . '" stroke-width="1.5" fill="none"/>';
        }
        return $s . '</svg>';
    }

    if ($art === 'systemmail') {
        // Ein Umschlag mit Haken – die Mail, die von selbst rausgeht
        return $kopf
            . $blatt(24, 22, 88, 106)
            . '<rect x="124" y="46" width="96" height="62" rx="4" fill="#fff" stroke="' . $grau . '"/>'
            . '<path d="M124 50 L172 84 L220 50" fill="none" stroke="' . $mittel . '" stroke-width="2"/>'
            . '<circle cx="205" cy="96" r="15" fill="#1E6B45"/>'
            . '<path d="M198 96 l5 5 9 -10" stroke="#fff" stroke-width="2.6" fill="none" stroke-linecap="round"/>'
            . '</svg>';
    }

    // Newsletter: ein einzelnes Blatt, groß
    return $kopf . $blatt(60, 14, 120, 122) . '</svg>';
}

$art    = Util::get('art');
$marke  = Util::get('marke');
$marken = Templates::brands();

/* Beide Fragen beantwortet – weiter zum Ziel */
if (isset($arten[$art]) && $marke !== '') {
    $vorlageId = Templates::brandTemplateId($marke);

    if ($art === 'systemmail') {
        // Die Systemmails hängen an einer wirklichen Vorlage; gibt es für
        // die Marke noch keine, entsteht sie hier – sonst wüsste die Seite
        // nicht, wessen Texte sie bearbeitet.
        if ($vorlageId === null) {
            $standard  = Templates::defaultTemplate();
            $vorlageId = $standard !== null ? (int) $standard['id'] : 0;
        }
        Util::redirect('systemmails.php?marke=' . $vorlageId);
    }

    Util::redirect($arten[$art]['weiter'] . urlencode($marke));
}

$pageTitle = 'Was möchten Sie anlegen?';
require __DIR__ . '/partials/header.php';

/* -------------------------------------------------- Schritt 2: Marke */

if (isset($arten[$art])) {
    $schritt = $arten[$art];
    ?>
    <div class="ad-page-head">
        <div>
            <p class="ad-schritte"><span>1. Art</span> <b>2. Marke</b> <span>3. Inhalt</span></p>
            <h1>Unter welcher Marke?</h1>
            <p class="ad-sub"><?= Util::e($schritt['titel']) ?> – Kopfzeile und Footer der gewählten Marke
                stehen danach schon, der Inhalt bleibt Ihre leere Fläche.</p>
        </div>
        <a class="ad-btn ad-btn-secondary" href="neu.php">Zurück</a>
    </div>

    <div class="ad-designwahl">
        <?php foreach ($marken as $m): ?>
            <a class="ad-design" href="neu.php?art=<?= Util::e(urlencode($art)) ?>&amp;marke=<?= Util::e(urlencode($m['schluessel'])) ?>">
                <span class="ad-design-bild">
                    <iframe src="vorlagen.php?vorschau=1<?= $m['datei'] !== ''
                                ? '&amp;datei=' . Util::e(urlencode($m['datei']))
                                : ($m['template'] !== null ? '&amp;id=' . (int) $m['template']['id'] : '') ?>"
                            title="Vorschau" loading="lazy" scrolling="no" tabindex="-1"></iframe>
                </span>
                <span class="ad-design-fuss">
                    <strong><?= Util::e((string) $m['name']) ?></strong>
                    <em>Kopfzeile, Footer und Impressum von <?= Util::e((string) $m['name']) ?></em>
                    <?php if ($m['neu']): ?>
                        <span class="ad-pill ad-pill-grey">wird beim ersten Mal angelegt</span>
                    <?php endif; ?>
                </span>
            </a>
        <?php endforeach; ?>
    </div>

    <p class="ad-hint">Die Marke lässt sich später wechseln. Neue Marken legen Sie unter
        <a href="vorlagen.php">Vorlagen</a> an.</p>
    <?php
    require __DIR__ . '/partials/footer.php';
    exit;
}

/* --------------------------------------------------- Schritt 1: Art */

$standard = Templates::defaultTemplate();
?>
<div class="ad-page-head">
    <div>
        <p class="ad-schritte"><b>1. Art</b> <span>2. Marke</span> <span>3. Inhalt</span></p>
        <h1>Um welche E-Mail geht es?</h1>
        <p class="ad-sub">Danach fragen wir nur noch nach der Marke – dann sind Sie im Baukasten.</p>
    </div>
    <a class="ad-btn ad-btn-secondary" href="kampagnen.php">Abbrechen</a>
</div>

<div class="ad-designwahl">
    <?php foreach ($arten as $schluessel => $a): ?>
        <a class="ad-design" href="neu.php?art=<?= Util::e(urlencode($schluessel)) ?>">
            <span class="ad-design-bild ad-artbild">
                <?= art_mockup($schluessel) ?>
            </span>
            <span class="ad-design-fuss">
                <strong><?= Util::e($a['titel']) ?></strong>
                <em><?= Util::e($a['text']) ?></em>
                <span class="ad-pill ad-pill-grey"><?= Util::e($a['wann']) ?></span>
            </span>
        </a>
    <?php endforeach; ?>
</div>

<p class="ad-hint">Unsicher? „Newsletter“ ist fast immer richtig. Eine Automation lohnt sich erst,
    wenn dieselbe Mailfolge immer wieder laufen soll.</p>

<?php require __DIR__ . '/partials/footer.php'; ?>
