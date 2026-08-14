<?php
/**
 * marken.php – Marken anlegen und pflegen.
 *
 * Bisher war die Marke eine Eigenschaft, die unten in einer Vorlage
 * versteckt lag: Man musste erst eine Vorlage anlegen, um eine Marke zu
 * bekommen – und hat nirgends gesehen, welche es gibt. Hier ist die
 * Marke das, was sie für den Empfänger auch ist: der Absender mit Namen,
 * Website, Impressum und Aussehen.
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
            Util::flash('Marke angelegt. Ergänzen Sie jetzt Impressum und Absender – '
                . 'das Aussehen ändern Sie über „Aussehen bearbeiten".');
            Util::redirect('marken.php#marke' . $neuId);
        } catch (Throwable $e) {
            Util::flash($e->getMessage(), 'error');
            Util::redirect('marken.php');
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
        Util::redirect('marken.php');
    }

    if ($aktion === 'standard') {
        Templates::makeDefault(Util::postInt('id'));
        Util::flash('Standardmarke geändert. Sie gilt überall dort, wo nichts anderes gewählt ist.');
        Util::redirect('marken.php');
    }
}

$marken   = Templates::brands();
$standard = Templates::defaultId();
?>

<div class="ad-page-head">
    <div>
        <h1>Marken</h1>
        <p class="ad-sub">Eine Marke ist das, was Ihre Empfänger sehen: Absender, Kopfzeile, Footer
            und Impressum. Jeder Newsletter, jede Automation und jede Liste gehört zu genau einer.</p>
    </div>
    <a class="ad-btn" href="#neue-marke">Neue Marke</a>
</div>

<?php foreach ($marken as $m): ?>
    <?php
    $vorlage  = $m['template'];
    $angaben  = Templates::brand($vorlage);
    $istNeu   = $m['neu'];
    $id       = $vorlage !== null ? (int) $vorlage['id'] : 0;
    $benutzt  = $istNeu ? ['listen' => 0, 'kampagnen' => 0, 'automationen' => 0, 'schritte' => 0]
                        : Templates::brandUsage($m['vorlagen']);
    ?>
    <div class="ad-card ad-marke" id="marke<?= $id ?>">
        <div class="ad-marke-bild">
            <iframe src="vorlagen.php?vorschau=1<?= $m['datei'] !== ''
                        ? '&amp;datei=' . Util::e(urlencode($m['datei']))
                        : ($id > 0 ? '&amp;id=' . $id : '') ?>"
                    title="Aussehen von <?= Util::e((string) $m['name']) ?>"
                    loading="lazy" scrolling="no" tabindex="-1"></iframe>
        </div>

        <div class="ad-marke-inhalt">
            <h2>
                <?= Util::e((string) $m['name']) ?>
                <?php if ($id === $standard): ?>
                    <span class="ad-pill ad-pill-blue">Standard</span>
                <?php endif; ?>
                <?php if ($istNeu): ?>
                    <span class="ad-pill ad-pill-grey">noch nicht benutzt</span>
                <?php endif; ?>
            </h2>

            <?php if ($istNeu): ?>
                <p class="ad-hint">Dieses Design liegt im Ordner <code>vorlagen/</code> und lässt sich
                    benutzen, sobald Sie damit einen Newsletter anlegen. Erst dann gibt es dafür
                    Angaben zum Pflegen.</p>
                <div class="ad-actions">
                    <a class="ad-btn ad-btn-secondary ad-btn-small"
                       href="neu.php?art=newsletter&amp;marke=<?= Util::e(urlencode($m['schluessel'])) ?>">Damit einen Newsletter anlegen</a>
                </div>
            <?php else: ?>
                <form method="post">
                    <?= Util::csrfField() ?>
                    <input type="hidden" name="aktion" value="speichern">
                    <input type="hidden" name="id" value="<?= $id ?>">

                    <div class="ad-row">
                        <div class="ad-field">
                            <label for="n<?= $id ?>">Name der Marke</label>
                            <input type="text" id="n<?= $id ?>" name="brand_name" maxlength="190"
                                   value="<?= Util::e((string) $angaben['brand_name']) ?>">
                        </div>
                        <div class="ad-field">
                            <label for="w<?= $id ?>">Website</label>
                            <input type="url" id="w<?= $id ?>" name="website_url" maxlength="190"
                                   value="<?= Util::e((string) $angaben['website_url']) ?>">
                        </div>
                    </div>

                    <div class="ad-field">
                        <label for="i<?= $id ?>">Impressum im Footer <span class="ad-hint">(Pflichtangabe)</span></label>
                        <textarea id="i<?= $id ?>" name="imprint" rows="2"><?= Util::e((string) $angaben['imprint']) ?></textarea>
                    </div>

                    <div class="ad-row">
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
                        </div>
                    </div>

                    <div class="ad-actions">
                        <button type="submit" class="ad-btn" <?= Auth::can('kampagnen') ? '' : 'disabled' ?>>Angaben speichern</button>
                        <a class="ad-btn ad-btn-secondary ad-btn-small" href="vorlagen.php?id=<?= $id ?>">Aussehen bearbeiten</a>
                        <a class="ad-btn ad-btn-secondary ad-btn-small" href="vorlagen.php?id=<?= $id ?>#design">Anderes Design</a>
                        <a class="ad-btn ad-btn-secondary ad-btn-small" href="systemmails.php?marke=<?= $id ?>">Systemmails</a>
                    </div>
                </form>

                <p class="ad-marke-nutzung">
                    Wird benutzt von
                    <a href="listen.php"><?= Util::num($benutzt['listen']) ?> Liste<?= $benutzt['listen'] === 1 ? '' : 'n' ?></a>,
                    <a href="kampagnen.php"><?= Util::num($benutzt['kampagnen']) ?> Newsletter</a>,
                    <a href="automationen.php"><?= Util::num($benutzt['automationen']) ?> Automation<?= $benutzt['automationen'] === 1 ? '' : 'en' ?></a>.
                    <?php if (count($m['vorlagen']) > 1): ?>
                        Dazu gehören <?= count($m['vorlagen']) ?> Designs – die Angaben oben gelten für alle.
                    <?php endif; ?>
                    <?php if ($id !== $standard): ?>
                </p>
                <form method="post" class="ad-actions-inline">
                    <?= Util::csrfField() ?>
                    <input type="hidden" name="aktion" value="standard">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <button type="submit" class="ad-btn ad-btn-secondary ad-btn-small">Als Standardmarke festlegen</button>
                </form>
                    <?php else: ?>
                        Das ist die Standardmarke: Sie gilt überall dort, wo nichts anderes gewählt ist.</p>
                    <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

<!-- ------------------------------------------------------ Neue Marke -->

<div class="ad-card" id="neue-marke">
    <h2 style="margin-top:0;">Neue Marke anlegen</h2>
    <p class="ad-hint">Für ein zweites Projekt mit eigener Website und eigenem Impressum.
        Alles Weitere – Kopfzeile, Farben, Schriften – stellen Sie danach unter
        „Aussehen bearbeiten" ein.</p>

    <form method="post">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="anlegen">

        <div class="ad-row">
            <div class="ad-field">
                <label for="neu_name">Name der Marke</label>
                <input type="text" id="neu_name" name="name" maxlength="190" required
                       placeholder="z. B. Fairway54">
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
        </div>
    </form>
</div>

<p class="ad-hint">Welche Marke wo gilt: beim <strong>Newsletter</strong> rechts unter „Marke &amp; Design“,
    bei der <strong>Liste</strong> in der Spalte „Marke“ (davon hängen die Systemmails ab), bei der
    <strong>Automation</strong> in den Angaben der Strecke. Beim Anlegen fragt der
    <a href="neu.php">Assistent</a> ohnehin danach.</p>

<?php require __DIR__ . '/partials/footer.php'; ?>
