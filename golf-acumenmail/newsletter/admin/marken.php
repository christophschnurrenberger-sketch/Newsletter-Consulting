<?php
/**
 * marken.php – Marken anlegen und pflegen.
 *
 * Eine Marke ist das, was der Empfänger sieht: von wem die Mail kommt,
 * welcher Name im Kopf steht und was im Footer als Pflichtangabe folgt.
 *
 * Aufbau wie überall sonst: oben die vorhandenen Marken als Reiter, darunter
 * genau eine davon zum Bearbeiten. Vorher standen alle Marken gleichzeitig
 * als offene Formulare untereinander – sieben Felder mal Anzahl der Marken,
 * dazu unten noch ein Anlegen-Formular. Das war nicht zu überblicken.
 *
 * Technisch bleibt eine Marke an einer Vorlage hängen; das Aussehen
 * bearbeitet man deshalb weiterhin im Baukasten unter „Vorlagen".
 */

require_once dirname(__DIR__) . '/lib/bootstrap.php';

$pageTitle = 'Marken';
require __DIR__ . '/partials/header.php';

if (Util::isPost()) {
    Util::requireCsrf();
    if (!Auth::can('kampagnen')) {
        Util::flash('Dafür fehlt Ihnen die Berechtigung. Fragen Sie eine Administratorin oder einen Administrator.', 'error');
        Util::redirect('marken.php');
    }
    $aktion = Util::post('aktion');

    if ($aktion === 'anlegen') {
        try {
            $neuId = Templates::createBrand(Util::post('name'), [
                'website_url' => Util::post('website_url'),
                'imprint'     => Util::postRaw('imprint'),
                'sender_name' => Util::post('sender_name') ?: Util::post('name'),
                'sender_email' => Util::normalizeEmail(Util::post('sender_email')),
            ], Util::post('start'));
            Util::flash('Marke angelegt. Prüfen Sie jetzt Absender und Footer – '
                . 'das Aussehen ändern Sie über „Aussehen bearbeiten".');
            Util::redirect('marken.php?m=' . urlencode('vorlage:' . $neuId));
        } catch (Throwable $e) {
            Util::flash($e->getMessage(), 'error');
            Util::redirect('marken.php?anlegen=1');
        }
    }

    if ($aktion === 'speichern') {
        $vorlagen = [];
        foreach (Templates::brands() as $marke) {
            if ($marke['template'] !== null && (int) $marke['template']['id'] === Util::postInt('id')) {
                $vorlagen = $marke['vorlagen'];
            }
        }
        if ($vorlagen !== []) {
            Templates::saveBrandGroup($vorlagen, [
                'brand_name'   => Util::post('brand_name'),
                'website_url'  => Util::post('website_url'),
                'imprint'      => Util::postRaw('imprint'),
                'imprint_url'  => Util::post('imprint_url'),
                'privacy_url'  => Util::post('privacy_url'),
                'sender_name'  => Util::post('sender_name'),
                'sender_email' => Util::normalizeEmail(Util::post('sender_email')),
            ]);
            Util::flash('Gespeichert. Die Angaben gelten für alle Designs dieser Marke.');
        }
        Util::redirect('marken.php?m=' . urlencode('vorlage:' . Util::postInt('id')));
    }

    if ($aktion === 'standard') {
        Templates::makeDefault(Util::postInt('id'));
        Util::flash('Standardmarke geändert. Sie gilt überall dort, wo nichts anderes gewählt ist.');
        Util::redirect('marken.php?m=' . urlencode('vorlage:' . Util::postInt('id')));
    }
}

$marken   = Templates::brands();
$standard = Templates::defaultId();

/* Welche Marke steht gerade vorn? Ohne Angabe die erste – das ist die eigene. */
$anlegen = Util::get('anlegen') !== '' || $marken === [];
$gewaehlt = null;
if (!$anlegen) {
    $wunsch = Util::get('m');
    foreach ($marken as $m) {
        if ($m['schluessel'] === $wunsch) { $gewaehlt = $m; break; }
    }
    if ($gewaehlt === null) { $gewaehlt = $marken[0] ?? null; }
}

/**
 * Kleine Zeichnung: eine E-Mail mit drei Markierungen. Sie beantwortet die
 * Frage, die sonst niemand beantwortet – wo landen diese Angaben eigentlich?
 */
function marke_mockup(): string
{
    $navy = '#14243A'; $grau = '#E0E6ED'; $hell = '#F6F8FA'; $mittel = '#B9C2CE'; $rot = '#C8102E';

    $punkt = static function (float $x, float $y, string $nr) use ($rot): string {
        return '<circle cx="' . $x . '" cy="' . $y . '" r="11" fill="' . $rot . '"/>'
             . '<text x="' . $x . '" y="' . ($y + 4.5) . '" text-anchor="middle" fill="#fff"'
             . ' font-family="Arial,Helvetica,sans-serif" font-size="13" font-weight="bold">' . $nr . '</text>';
    };

    $s = '<svg viewBox="0 0 320 250" width="100%" height="100%" role="img"'
       . ' aria-label="Eine E-Mail mit drei Markierungen: Absenderzeile, Kopfzeile, Footer">'
       . '<rect width="320" height="250" fill="' . $hell . '"/>';

    /* Absenderzeile im Postfach */
    $s .= '<rect x="26" y="18" width="268" height="34" rx="4" fill="#fff" stroke="' . $grau . '"/>'
        . '<circle cx="46" cy="35" r="9" fill="' . $navy . '"/>'
        . '<rect x="62" y="26" width="86" height="7" rx="2" fill="' . $navy . '"/>'
        . '<rect x="62" y="38" width="130" height="6" rx="2" fill="' . $mittel . '"/>';
    $s .= $punkt(283, 35, '1');

    /* Die Mail selbst */
    $s .= '<rect x="26" y="62" width="268" height="170" rx="4" fill="#fff" stroke="' . $grau . '"/>'
        . '<rect x="26" y="62" width="268" height="34" fill="' . $navy . '"/>'
        . '<rect x="42" y="74" width="74" height="10" rx="2" fill="#fff" opacity=".9"/>';
    $s .= $punkt(283, 79, '2');

    for ($i = 0; $i < 4; $i++) {
        $s .= '<rect x="42" y="' . (112 + $i * 14) . '" width="' . (216 - $i * 26) . '" height="7" rx="2" fill="' . $mittel . '"/>';
    }

    /* Footer */
    $s .= '<rect x="26" y="180" width="268" height="52" fill="' . $hell . '"/>'
        . '<line x1="26" y1="180" x2="294" y2="180" stroke="' . $grau . '"/>';
    for ($i = 0; $i < 3; $i++) {
        $s .= '<rect x="42" y="' . (192 + $i * 12) . '" width="' . (200 - $i * 44) . '" height="6" rx="2" fill="' . $mittel . '"/>';
    }
    $s .= $punkt(283, 206, '3');

    return $s . '</svg>';
}
?>

<div class="ad-page-head">
    <div>
        <h1>Marken</h1>
        <p class="ad-sub">Wer schickt die E-Mail, wie heißt es im Kopf und was steht im Footer.
            Jeder Newsletter, jede Automation und jede Liste gehört zu genau einer Marke.</p>
    </div>
</div>

<?php /* Der Erklärkasten lässt sich zuklappen und bleibt dann zu (siehe admin.js). */ ?>
<details class="ad-card ad-klapp" data-merken="marken-erklaerung" open>
    <summary>
        <h2>Wozu eine Marke?</h2>
        <span class="ad-klapp-zeichen" aria-hidden="true"></span>
    </summary>

    <div class="ad-erklaer">
        <div class="ad-erklaer-bild"><?= marke_mockup() ?></div>
        <div class="ad-erklaer-text">
            <p>Alles, was den Absender ausmacht, steht an <strong>einer</strong> Stelle –
                nämlich hier. Sie tragen es einmal ein, und es erscheint überall:</p>
            <ol class="ad-erklaer-liste">
                <li><strong>Absender im Postfach.</strong> Absendername und Absenderadresse:
                    daran erkennt der Empfänger, von wem die Mail kommt.</li>
                <li><strong>Kopfzeile der Mail.</strong> Der Name der Marke – als Schriftzug
                    oder Logo, je nach Design.</li>
                <li><strong>Footer.</strong> Impressum, Links zu Impressum- und
                    Datenschutzseite, Website. Gesetzlich vorgeschrieben.</li>
            </ol>
            <p class="ad-hint">Eine zweite Marke brauchen Sie, wenn Sie aus derselben
                Installation Post für ein zweites Projekt verschicken – mit eigener
                Website, eigenem Impressum und eigenem Absender.</p>
        </div>
    </div>
</details>

<nav class="ad-reiter" aria-label="Marken">
    <?php foreach ($marken as $m):
        $id = $m['template'] !== null ? (int) $m['template']['id'] : 0; ?>
        <a class="ad-reiter-tab <?= !$anlegen && $gewaehlt !== null && $gewaehlt['schluessel'] === $m['schluessel'] ? 'is-aktiv' : '' ?>"
           href="marken.php?m=<?= Util::e(urlencode((string) $m['schluessel'])) ?>">
            <?= Util::e((string) $m['name']) ?>
            <?php if ($id > 0 && $id === $standard): ?>
                <span class="ad-pill ad-pill-blue">Standard</span>
            <?php elseif ($m['neu']): ?>
                <span class="ad-pill ad-pill-grey">ungenutzt</span>
            <?php endif; ?>
        </a>
    <?php endforeach; ?>
    <a class="ad-reiter-tab <?= $anlegen ? 'is-aktiv' : '' ?>" href="marken.php?anlegen=1">+ Neue Marke</a>
</nav>

<?php /* ------------------------------------------------------ Eine Marke */ ?>
<?php if (!$anlegen && $gewaehlt !== null):
    $m       = $gewaehlt;
    $vorlage = $m['template'];
    $angaben = Templates::brand($vorlage);
    $id      = $vorlage !== null ? (int) $vorlage['id'] : 0;
    $benutzt = $m['neu'] ? ['listen' => 0, 'kampagnen' => 0, 'automationen' => 0, 'schritte' => 0]
                         : Templates::brandUsage($m['vorlagen']);
    $vorschau = 'vorlagen.php?vorschau=1' . ($m['datei'] !== ''
        ? '&amp;datei=' . Util::e(urlencode($m['datei']))
        : ($id > 0 ? '&amp;id=' . $id : ''));
    ?>

    <?php if ($m['neu']): ?>
        <div class="ad-card ad-marke-detail">
            <div class="ad-marke-spalte">
                <div class="ad-marke-bild">
                    <iframe src="<?= $vorschau ?>" title="Aussehen von <?= Util::e((string) $m['name']) ?>"
                            loading="lazy" scrolling="no" tabindex="-1"></iframe>
                </div>
            </div>
            <div class="ad-marke-felder">
                <h2 style="margin-top:0;"><?= Util::e((string) $m['name']) ?></h2>
                <p>Dieses Design wird mitgeliefert und liegt im Ordner <code>vorlagen/</code>.
                    Es ist noch nicht in Gebrauch – deshalb gibt es dafür auch noch keine
                    Angaben zum Pflegen.</p>
                <p class="ad-hint">Sobald Sie damit den ersten Newsletter anlegen, wird daraus
                    eine richtige Marke mit Absender, Impressum und eigenem Aussehen.</p>
                <div class="ad-actions">
                    <a class="ad-btn"
                       href="neu.php?art=newsletter&amp;marke=<?= Util::e(urlencode((string) $m['schluessel'])) ?>">Damit einen Newsletter anlegen</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="ad-card ad-marke-detail">
            <div class="ad-marke-spalte">
                <div class="ad-marke-bild">
                    <iframe src="<?= $vorschau ?>" title="Aussehen von <?= Util::e((string) $m['name']) ?>"
                            loading="lazy" scrolling="no" tabindex="-1"></iframe>
                </div>
                <div class="ad-actions" style="margin-top:12px;">
                    <a class="ad-btn ad-btn-secondary ad-btn-small" href="vorlagen.php?id=<?= $id ?>">Aussehen bearbeiten</a>
                    <a class="ad-btn ad-btn-secondary ad-btn-small" href="vorlagen.php?id=<?= $id ?>#design">Anderes Design</a>
                </div>
                <?php if (count($m['vorlagen']) > 1): ?>
                    <p class="ad-hint">Zu dieser Marke gehören <?= count($m['vorlagen']) ?> Designs.
                        Die Angaben rechts gelten für alle.</p>
                <?php endif; ?>
            </div>

            <div class="ad-marke-felder">
                <form method="post">
                    <?= Util::csrfField() ?>
                    <input type="hidden" name="aktion" value="speichern">
                    <input type="hidden" name="id" value="<?= $id ?>">

                    <fieldset class="ad-block">
                        <legend><span class="ad-block-nr">1</span> Absender im Postfach</legend>
                        <p class="ad-hint">So steht es in der Empfängerliste, bevor jemand die Mail öffnet.</p>
                        <div class="ad-row">
                            <div class="ad-field">
                                <label for="sn<?= $id ?>">Absendername</label>
                                <input type="text" id="sn<?= $id ?>" name="sender_name" maxlength="190"
                                       value="<?= Util::e((string) $angaben['sender_name']) ?>">
                            </div>
                            <div class="ad-field">
                                <label for="se<?= $id ?>">Absenderadresse</label>
                                <input type="email" id="se<?= $id ?>" name="sender_email" maxlength="190"
                                       value="<?= Util::e((string) $angaben['sender_email']) ?>">
                                <p class="ad-hint">Muss zu einer Domain gehören, für die Ihr
                                    <a href="einstellungen.php">Versandweg</a> senden darf.</p>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="ad-block">
                        <legend><span class="ad-block-nr">2</span> Name in der Kopfzeile</legend>
                        <p class="ad-hint">Erscheint oben in jeder Mail dieser Marke und als Platzhalter
                            <code>{{marke}}</code> im Text.</p>
                        <div class="ad-field">
                            <label for="n<?= $id ?>">Name der Marke</label>
                            <input type="text" id="n<?= $id ?>" name="brand_name" maxlength="190"
                                   value="<?= Util::e((string) $angaben['brand_name']) ?>">
                        </div>
                    </fieldset>

                    <fieldset class="ad-block">
                        <legend><span class="ad-block-nr">3</span> Footer und Pflichtangaben</legend>
                        <p class="ad-hint">Steht am Ende jeder Mail. Impressum und Abmeldelink sind
                            gesetzlich vorgeschrieben (§ 5 DDG).</p>
                        <div class="ad-field">
                            <label for="i<?= $id ?>">Impressum im Footer</label>
                            <textarea id="i<?= $id ?>" name="imprint" rows="2"
                                      placeholder="Firma · Straße 1 · 12345 Ort · Vertreten durch …"><?= Util::e((string) $angaben['imprint']) ?></textarea>
                        </div>
                        <div class="ad-row">
                            <div class="ad-field">
                                <label for="w<?= $id ?>">Website</label>
                                <input type="url" id="w<?= $id ?>" name="website_url" maxlength="190"
                                       value="<?= Util::e((string) $angaben['website_url']) ?>">
                            </div>
                            <div class="ad-field">
                                <label for="iu<?= $id ?>">Impressum-Seite</label>
                                <input type="url" id="iu<?= $id ?>" name="imprint_url" maxlength="190"
                                       value="<?= Util::e((string) $angaben['imprint_url']) ?>">
                            </div>
                            <div class="ad-field">
                                <label for="du<?= $id ?>">Datenschutz-Seite</label>
                                <input type="url" id="du<?= $id ?>" name="privacy_url" maxlength="190"
                                       value="<?= Util::e((string) $angaben['privacy_url']) ?>">
                            </div>
                        </div>
                    </fieldset>

                    <div class="ad-actions">
                        <button type="submit" class="ad-btn" <?= Auth::can('kampagnen') ? '' : 'disabled' ?>>Speichern</button>
                        <a class="ad-btn ad-btn-secondary ad-btn-small" href="systemmails.php?marke=<?= $id ?>">Systemmails dieser Marke</a>
                        <?php if ($id !== $standard): ?>
                            <?php /* Der Knopf gehört zum zweiten Formular weiter unten –
                                     „form" verbindet ihn damit, ohne Formulare zu schachteln. */ ?>
                            <details class="ad-menue">
                                <summary class="ad-btn ad-btn-secondary ad-btn-small" title="Weitere Aktionen">…</summary>
                                <div class="ad-menue-liste">
                                    <button type="submit" form="marke-standard-<?= $id ?>"
                                        <?= Auth::can('kampagnen') ? '' : 'disabled' ?>>Als Standardmarke festlegen</button>
                                </div>
                            </details>
                        <?php endif; ?>
                    </div>
                </form>

                <?php if ($id !== $standard): ?>
                    <form method="post" id="marke-standard-<?= $id ?>" hidden>
                        <?= Util::csrfField() ?>
                        <input type="hidden" name="aktion" value="standard">
                        <input type="hidden" name="id" value="<?= $id ?>">
                    </form>
                <?php endif; ?>

                <p class="ad-marke-nutzung">
                    Wird benutzt von
                    <a href="listen.php"><?= Util::num($benutzt['listen']) ?> Liste<?= $benutzt['listen'] === 1 ? '' : 'n' ?></a>,
                    <a href="kampagnen.php"><?= Util::num($benutzt['kampagnen']) ?> Newsletter</a>,
                    <a href="automationen.php"><?= Util::num($benutzt['automationen']) ?> Automation<?= $benutzt['automationen'] === 1 ? '' : 'en' ?></a>.
                    <?php if ($id === $standard): ?>
                        Das ist die Standardmarke: Sie gilt überall dort, wo nichts anderes gewählt ist.
                    <?php endif; ?>
                </p>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php /* ------------------------------------------------------ Neue Marke */ ?>
<?php if ($anlegen): ?>
    <div class="ad-card" id="neue-marke">
        <h2 style="margin-top:0;">Neue Marke anlegen</h2>
        <p class="ad-hint">Für ein zweites Projekt mit eigener Website und eigenem Impressum.
            Kopfzeile, Farben und Schriften stellen Sie danach unter „Aussehen bearbeiten" ein.</p>

        <form method="post">
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="anlegen">

            <div class="ad-row">
                <div class="ad-field">
                    <label for="neu_name">Name der Marke</label>
                    <input type="text" id="neu_name" name="name" maxlength="190" required
                           placeholder="z. B. Fairway54" autofocus>
                </div>
                <div class="ad-field">
                    <label for="neu_website">Website</label>
                    <input type="url" id="neu_website" name="website_url" maxlength="190"
                           placeholder="https://www.beispiel.de">
                </div>
            </div>

            <div class="ad-field">
                <label for="neu_impressum">Impressum im Footer</label>
                <textarea id="neu_impressum" name="imprint" rows="2"
                          placeholder="Firma · Straße 1 · 12345 Ort · Vertreten durch …"></textarea>
            </div>

            <div class="ad-row">
                <div class="ad-field">
                    <label for="neu_absender">Absendername</label>
                    <input type="text" id="neu_absender" name="sender_name" maxlength="190">
                </div>
                <div class="ad-field">
                    <label for="neu_adresse">Absenderadresse</label>
                    <input type="email" id="neu_adresse" name="sender_email" maxlength="190">
                    <p class="ad-hint">Muss zu einer Domain gehören, für die Ihr Versandweg senden darf.</p>
                </div>
            </div>

            <div class="ad-field">
                <label for="neu_start">Womit anfangen?</label>
                <select id="neu_start" name="start">
                    <option value="leer">Leeres Design (Kopfzeile, Inhalt, Footer)</option>
                    <?php foreach (Templates::files() as $schluessel => $datei): ?>
                        <option value="datei:<?= Util::e($schluessel) ?>">Mitgeliefert: <?= Util::e($datei['name']) ?></option>
                    <?php endforeach; ?>
                    <?php foreach ($marken as $m): ?>
                        <?php if ($m['template'] === null) { continue; } ?>
                        <option value="kopie:<?= (int) $m['template']['id'] ?>">Kopie von <?= Util::e((string) $m['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="ad-actions">
                <button type="submit" class="ad-btn" <?= Auth::can('kampagnen') ? '' : 'disabled' ?>>Marke anlegen</button>
                <?php if ($marken !== []): ?>
                    <a class="ad-btn ad-btn-secondary" href="marken.php">Abbrechen</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
<?php endif; ?>

<p class="ad-hint">Welche Marke wo gilt: beim <strong>Newsletter</strong> rechts unter „Marke &amp; Design“,
    bei der <strong>Liste</strong> in der Spalte „Marke“ (davon hängen die Systemmails ab), bei der
    <strong>Automation</strong> in den Angaben der Strecke. Beim Anlegen fragt der
    <a href="neu.php">Assistent</a> ohnehin danach.</p>

<?php require __DIR__ . '/partials/footer.php'; ?>
