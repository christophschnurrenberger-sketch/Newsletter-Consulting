<?php
/**
 * systemmails.php – die drei automatischen Einzelmails ansehen und ändern.
 *
 * Bestätigung, Begrüßung und Abmeldebestätigung gehen ohne Zutun raus –
 * und wurden deshalb bisher nie jemandem gezeigt. Hier stehen sie als
 * echte Vorschau, in der Marke, unter der sie beim Empfänger ankommen.
 *
 * Die Texte lassen sich je Marke abweichen lassen; ein leeres Feld heißt
 * „nimm den allgemeinen Text". Die allgemeinen Texte und die Schalter
 * „Begrüßung senden ja/nein" bleiben unter Einstellungen, weil sie für
 * die ganze Installation gelten.
 */

require_once dirname(__DIR__) . '/lib/bootstrap.php';

/* Vorschau einer einzelnen Mail (wird im Rahmen angezeigt) */
if (Util::get('vorschau') !== '') {
    Auth::require('lesen');
    $art = Util::get('vorschau');
    if (!isset(SystemMails::KINDS[$art])) {
        http_response_code(404);
        exit('Diese Mail gibt es nicht.');
    }
    $vorlage = Templates::byId(Util::getInt('marke')) ?? Templates::defaultTemplate();
    Util::previewHeaders();
    echo SystemMails::preview($art, $vorlage);
    exit;
}

$pageTitle = 'Systemmails';
require __DIR__ . '/partials/header.php';

/*
 * Schreiben darf, wer Newsletter schreiben darf: Das hier ist Text an
 * dieselben Empfänger, nur automatisch verschickt. Die allgemeinen
 * Vorgaben unter Einstellungen bleiben der Administration vorbehalten.
 */
if (Util::isPost()) {
    Util::requireCsrf();
    if (!Auth::can('kampagnen')) {
        Util::flash('Dafür fehlt Ihnen die Berechtigung. Fragen Sie eine Administratorin oder einen Administrator.', 'error');
        Util::redirect('systemmails.php');
    }
    $markeId = Util::postInt('marke');
    if ($markeId > 0 && Templates::byId($markeId) !== null) {
        $werte = [];
        foreach (SystemMails::FIELDS as $felder) {
            foreach (array_keys($felder) as $feld) {
                $werte[$feld] = Util::postRaw($feld);
            }
        }
        SystemMails::saveTexts($markeId, $werte);
        Util::flash('Gespeichert. Die Vorschau zeigt den neuen Stand.');
    }
    Util::redirect('systemmails.php?marke=' . $markeId);
}

$marken = array_values(array_filter(Templates::brands(), static fn(array $m): bool => $m['template'] !== null));

$vorlage = Templates::byId(Util::getInt('marke'));
if ($vorlage === null) {
    $vorlage = $marken !== [] ? $marken[0]['template'] : Templates::defaultTemplate();
}
$markeId   = $vorlage !== null ? (int) $vorlage['id'] : 0;
$markeName = Templates::brand($vorlage)['brand_name'];
?>

<div class="ad-page-head">
    <div>
        <h1>Systemmails</h1>
        <p class="ad-sub">Diese drei Mails verschickt das System von selbst. Hier sehen Sie,
            wie sie beim Empfänger ankommen – und ändern die Texte.</p>
    </div>
    <a class="ad-btn ad-btn-secondary" href="einstellungen.php">Allgemeine Vorgaben</a>
</div>

<?php if (count($marken) > 1): ?>
    <div class="ad-markenleiste" role="group" aria-label="Marke wählen">
        <span>Marke:</span>
        <?php foreach ($marken as $m): ?>
            <a class="ad-marke-knopf<?= (int) $m['template']['id'] === $markeId ? ' is-current' : '' ?>"
               href="systemmails.php?marke=<?= (int) $m['template']['id'] ?>"><?= Util::e((string) $m['name']) ?></a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post">
    <?= Util::csrfField() ?>
    <input type="hidden" name="marke" value="<?= $markeId ?>">

    <?php foreach (SystemMails::KINDS as $art => $titel): ?>
        <div class="ad-card ad-systemmail">
            <div class="ad-systemmail-spalte">
                <div class="ad-systemmail-vorschau">
                    <iframe src="systemmails.php?vorschau=<?= Util::e($art) ?>&amp;marke=<?= $markeId ?>"
                            title="Vorschau: <?= Util::e($titel) ?>" loading="lazy" scrolling="no" tabindex="-1"></iframe>
                </div>
                <p class="ad-hint" style="text-align:center;">
                    <a href="systemmails.php?vorschau=<?= Util::e($art) ?>&amp;marke=<?= $markeId ?>"
                       target="_blank" rel="noopener">Ganz ansehen</a>
                </p>
            </div>
            <div class="ad-systemmail-felder">
                <h2><?= Util::e($titel) ?></h2>
                <?php if ($art === 'willkommen' && !Settings::bool('welcome_enabled')): ?>
                    <p class="ad-hint">Diese Mail ist zurzeit abgeschaltet – einschalten unter
                        <a href="einstellungen.php">Einstellungen</a>.</p>
                <?php elseif ($art === 'abmeldung' && !Settings::bool('goodbye_enabled')): ?>
                    <p class="ad-hint">Diese Mail ist zurzeit abgeschaltet – einschalten unter
                        <a href="einstellungen.php">Einstellungen</a>.</p>
                <?php endif; ?>

                <?php foreach (SystemMails::FIELDS[$art] as $feld => $label): ?>
                    <?php
                    $eigen     = $markeId > 0 && SystemMails::hasOwnText($feld, $markeId);
                    $allgemein = Settings::get($feld);
                    $wert      = $eigen ? SystemMails::text($feld, $markeId) : '';
                    $mehrzeilig = str_ends_with($feld, '_intro');
                    ?>
                    <div class="ad-field">
                        <label for="<?= Util::e($feld) ?>"><?= Util::e($label) ?></label>
                        <?php if ($mehrzeilig): ?>
                            <textarea id="<?= Util::e($feld) ?>" name="<?= Util::e($feld) ?>" rows="3"
                                placeholder="<?= Util::e($allgemein) ?>"><?= Util::e($wert) ?></textarea>
                        <?php else: ?>
                            <input type="text" id="<?= Util::e($feld) ?>" name="<?= Util::e($feld) ?>"
                                value="<?= Util::e($wert) ?>" placeholder="<?= Util::e($allgemein) ?>">
                        <?php endif; ?>
                        <p class="ad-hint">
                            <?= $eigen
                                ? 'Eigener Text für ' . Util::e($markeName) . '. Feld leeren = wieder der allgemeine Text.'
                                : 'Leer = allgemeiner Text aus den Einstellungen.' ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="ad-actions">
        <button type="submit" class="ad-btn" <?= Auth::can('kampagnen') ? '' : 'disabled' ?>>Texte speichern</button>
    </div>
</form>

<p class="ad-hint">Kopfzeile, Footer und Impressum kommen aus der Vorlage der Marke – zu ändern unter
    <a href="vorlagen.php">Vorlagen</a>. Welche Marke ein Empfänger bekommt, entscheidet die Liste,
    für die er sich angemeldet hat (<a href="listen.php">Listen</a>).</p>

<?php require __DIR__ . '/partials/footer.php'; ?>
