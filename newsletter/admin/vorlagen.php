<?php
/**
 * vorlagen.php – Design-Vorlagen anlegen und bearbeiten.
 */

require_once dirname(__DIR__) . '/lib/bootstrap.php';

/* Vorschau einer Vorlage (wird im Rahmen angezeigt) */
if (Util::get('vorschau') === '1') {
    Auth::require('lesen');
    $template = Templates::byId(Util::getInt('id'));
    if ($template === null) {
        http_response_code(404);
        exit('Vorlage nicht gefunden.');
    }
    $html = Renderer::wrap($template, Templates::starterContent(), 'Beispiel-Betreff', 'Beispiel-Vorschautext');
    $html = Renderer::applyBrand($html, $template, true);
    $html = Renderer::personalize($html, Renderer::sampleSubscriber(), [
        'abmelden_url'     => '#',
        'praeferenzen_url' => '#',
        'webansicht_url'   => '#',
    ], true);
    header('Content-Type: text/html; charset=utf-8');
    echo $html;
    exit;
}

$pageTitle = 'Vorlagen';
$extraCss  = ['assets/builder.css'];
$extraJs   = ['assets/builder.js'];
require __DIR__ . '/partials/header.php';
require __DIR__ . '/partials/builder.php';

if (Util::isPost()) {
    Util::requireCsrf();
    if (!Auth::can('kampagnen')) {
        Util::flash('Dafür fehlt Ihnen die Berechtigung. Fragen Sie eine Administratorin oder einen Administrator.', 'error');
        Util::redirect('vorlagen.php');
    }
    $action = Util::post('aktion');
    $id     = Util::postInt('id');

    if ($action === 'anlegen') {
        $imBaukasten = Util::post('baukasten') === '1';
        $newId = $imBaukasten
            ? Templates::create(Util::post('name') ?: 'Neue Vorlage', '', Util::post('description'), false,
                (string) json_encode(Blocks::starterTemplate()))
            : Templates::create(Util::post('name') ?: 'Neue Vorlage', Templates::standardHtml(),
                Util::post('description'));
        Util::flash($imBaukasten
            ? 'Vorlage angelegt. Ziehen Sie jetzt Bausteine an die gewünschte Stelle.'
            : 'Vorlage angelegt.');
        Util::redirect('vorlagen.php?id=' . $newId);
    }
    if ($action === 'marke' && $id > 0) {
        Templates::saveBrand($id, [
            'brand_name'   => Util::post('brand_name'),
            'website_url'  => Util::post('brand_website_url'),
            'imprint'      => Util::postRaw('brand_imprint'),
            'imprint_url'  => Util::post('brand_imprint_url'),
            'privacy_url'  => Util::post('brand_privacy_url'),
            'sender_name'  => Util::post('brand_sender_name'),
            'sender_email' => Util::normalizeEmail(Util::post('brand_sender_email')),
        ]);
        // Die fertige Fassung wird beim nächsten Kompilieren neu gebaut.
        Util::flash('Marke der Vorlage gespeichert.');
        Util::redirect('vorlagen.php?id=' . $id . '#marke');
    }
    if ($action === 'speichern_baukasten' && $id > 0) {
        $template = Templates::byId($id);
        Templates::update($id, Util::post('name'), (string) ($template['html'] ?? ''),
            Util::post('description'), Util::postRaw('blocks_json'));
        Util::flash('Vorlage gespeichert.');
        Util::redirect('vorlagen.php?id=' . $id);
    }

    if ($action === 'modus' && $id > 0) {
        $template = Templates::byId($id);
        if ($template !== null && Util::post('ziel') === 'blocks') {
            // Bestehendes HTML als Baustein übernehmen, damit nichts verloren geht
            $start = Blocks::starterTemplate();
            $html  = trim((string) $template['html']);
            if (trim((string) $template['blocks_json']) === '' && $html !== '') {
                $start['blocks'] = [
                    Blocks::block('content'),
                ];
            }
            $json = trim((string) $template['blocks_json']) !== ''
                ? (string) $template['blocks_json']
                : (string) json_encode($start);
            Templates::update($id, (string) $template['name'], '', (string) $template['description'], $json);
            Util::flash('Baukasten aktiviert. Die Vorlage wird ab jetzt aus Bausteinen erzeugt.');
        } elseif ($template !== null) {
            DB::update('templates', ['editor_mode' => 'html'], 'id = ?', [$id]);
            Util::flash('HTML-Modus aktiviert. Sie bearbeiten jetzt den erzeugten Code direkt.');
        }
        Util::redirect('vorlagen.php?id=' . $id);
    }

    if ($action === 'speichern' && $id > 0) {
        $html = Util::postRaw('html');
        if (!str_contains($html, '{{inhalt}}')) {
            Util::flash('Die Vorlage muss den Platzhalter {{inhalt}} enthalten – sonst bleibt der Newsletter leer.', 'error');
        } elseif (!str_contains($html, '{{abmelden_url}}')) {
            Util::flash('Die Vorlage muss den Abmeldelink {{abmelden_url}} enthalten (gesetzlich vorgeschrieben).', 'error');
        } else {
            Templates::update($id, Util::post('name'), $html, Util::post('description'));
            Util::flash('Vorlage gespeichert.');
        }
        Util::redirect('vorlagen.php?id=' . $id);
    }
    if ($action === 'aus_datei') {
        $neuId = Templates::createFromFile(Util::post('datei'));
        if ($neuId === 0) {
            Util::flash('Diese mitgelieferte Vorlage gibt es nicht.', 'error');
            Util::redirect('vorlagen.php');
        }
        Util::flash('Vorlage übernommen. Prüfen Sie unten die Angaben unter „Marke dieser Vorlage".');
        Util::redirect('vorlagen.php?id=' . $neuId);
    }
    if ($action === 'standard' && $id > 0) {
        Templates::makeDefault($id);
        Util::flash('Standardvorlage geändert.');
        Util::redirect('vorlagen.php?id=' . $id);
    }
    if ($action === 'loeschen' && $id > 0) {
        Templates::delete($id);
        Util::flash('Vorlage gelöscht.');
        Util::redirect('vorlagen.php');
    }
    if ($action === 'zuruecksetzen' && $id > 0) {
        $template = Templates::byId($id);
        if ($template !== null) {
            Templates::update($id, (string) $template['name'], Templates::standardHtml(), (string) $template['description']);
            Util::flash('Vorlage auf die mitgelieferte Fassung zurückgesetzt.');
        }
        Util::redirect('vorlagen.php?id=' . $id);
    }
}

$templates = Templates::all();
$current   = Templates::byId(Util::getInt('id')) ?? Templates::defaultTemplate();
?>

<div class="ad-page-head">
    <div>
        <h1>Vorlagen</h1>
        <p class="ad-sub">Der HTML-Rahmen um Ihre Newsletter-Inhalte</p>
    </div>
    <form method="post" class="ad-actions-inline">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="anlegen">
        <input type="text" name="name" class="ad-input" style="width:200px;" placeholder="Name der Vorlage">
        <button type="submit" name="baukasten" value="1" class="ad-btn">Neu im Baukasten</button>
        <button type="submit" name="baukasten" value="0" class="ad-btn ad-btn-secondary">Neu als HTML</button>
    </form>
</div>

<?php $fertige = Templates::files(); ?>
<?php if ($fertige !== []): ?>
    <div class="ad-card">
        <h2 style="margin-top:0;">Fertige Vorlage übernehmen</h2>
        <p class="ad-hint">Mitgelieferte Entwürfe aus dem Ordner <code>newsletter/vorlagen/</code> –
            fertig gestaltet, danach beliebig änderbar.</p>
        <form method="post" class="ad-actions-inline" style="margin-top:10px;">
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="aus_datei">
            <select name="datei" class="ad-input" style="width:auto;min-width:240px;">
                <?php foreach ($fertige as $schluessel => $angaben): ?>
                    <option value="<?= Util::e($schluessel) ?>"><?= Util::e($angaben['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="ad-btn ad-btn-secondary">Übernehmen</button>
        </form>
        <ul class="ad-hint" style="margin:12px 0 0 18px;padding:0;">
            <?php foreach ($fertige as $angaben): ?>
                <li><strong><?= Util::e($angaben['name']) ?></strong><?= $angaben['description'] !== ''
                    ? ' – ' . Util::e($angaben['description']) : '' ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="ad-card ad-card-tight">
    <div class="ad-actions" style="margin-top:0;">
        <?php foreach ($templates as $template): ?>
            <a class="ad-btn ad-btn-small <?= $current !== null && (int) $current['id'] === (int) $template['id'] ? '' : 'ad-btn-secondary' ?>"
               href="vorlagen.php?id=<?= (int) $template['id'] ?>">
                <?= Util::e((string) $template['name']) ?>
                <?= (int) $template['is_default'] === 1 ? ' ★' : '' ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?php if ($current === null): ?>
    <div class="ad-empty">Noch keine Vorlage vorhanden.</div>
<?php else: ?>
    <?php $tplBuilder = Templates::usesBuilder($current); ?>

    <?php if ($tplBuilder): ?>
        <div class="ad-card">
            <form method="post" data-warn-unsaved>
                <?= Util::csrfField() ?>
                <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
                <input type="hidden" name="ziel" value="html">

                <div class="ad-row">
                    <div class="ad-field">
                        <label for="bk_name">Name</label>
                        <input type="text" id="bk_name" name="name" value="<?= Util::e((string) $current['name']) ?>">
                    </div>
                    <div class="ad-field" style="flex:2 1 300px;">
                        <label for="bk_desc">Beschreibung</label>
                        <input type="text" id="bk_desc" name="description" value="<?= Util::e((string) $current['description']) ?>">
                    </div>
                    <div class="ad-field" style="flex:0;">
                        <label>&nbsp;</label>
                        <div class="ad-actions-inline">
                            <button type="submit" name="aktion" value="speichern_baukasten" class="ad-btn">Speichern</button>
                            <button type="submit" name="aktion" value="modus" class="ad-btn ad-btn-secondary">Zu HTML wechseln</button>
                        </div>
                    </div>
                </div>

                <p class="ad-hint" style="margin-bottom:14px;">Ziehen Sie Bausteine in die Vorlage. Der Baustein
                    <strong>„Inhalt der Ausgabe“</strong> markiert die Stelle, an der später der Text des jeweiligen
                    Newsletters erscheint – er gehört in jede Vorlage.</p>

                <?php builder_ui(Templates::blocks($current), 'template'); ?>

                <div class="ad-actions">
                    <button type="submit" name="aktion" value="speichern_baukasten" class="ad-btn">Vorlage speichern</button>
                    <?php if ((int) $current['is_default'] !== 1): ?>
                        <button type="submit" name="aktion" value="standard" class="ad-btn ad-btn-secondary">Als Standard</button>
                    <?php endif; ?>
                    <?php if (count($templates) > 1): ?>
                        <button type="submit" name="aktion" value="loeschen" class="ad-btn ad-btn-danger"
                                data-confirm="Vorlage wirklich löschen?">Löschen</button>
                    <?php endif; ?>
                    <a class="ad-btn ad-btn-secondary" target="_blank" rel="noopener"
                       href="vorlagen.php?id=<?= (int) $current['id'] ?>&amp;vorschau=1">Vorschau in neuem Tab</a>
                </div>
            </form>
        </div>

        <div class="ad-card">
            <h2>So sieht die Vorlage aus</h2>
            <iframe class="ad-preview-frame" style="height:560px;"
                    src="vorlagen.php?id=<?= (int) $current['id'] ?>&amp;vorschau=1"
                    title="Vorschau der Vorlage"></iframe>
            <p class="ad-hint">Die Vorschau zeigt den gespeicherten Stand mit Beispielinhalt.</p>
        </div>
    <?php else: ?>
    <div class="ad-editor-grid">
        <div class="ad-card">
            <form method="post" data-warn-unsaved>
                <?= Util::csrfField() ?>
                <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
                <input type="hidden" name="ziel" value="blocks">

                <div class="ad-row">
                    <div class="ad-field">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="<?= Util::e((string) $current['name']) ?>">
                    </div>
                    <div class="ad-field" style="flex:2 1 300px;">
                        <label for="description">Beschreibung</label>
                        <input type="text" id="description" name="description" value="<?= Util::e((string) $current['description']) ?>">
                    </div>
                </div>

                <div class="ad-field">
                    <label for="html">HTML der Vorlage</label>
                    <textarea id="html" name="html" rows="26" class="ad-code"><?= Util::e((string) $current['html']) ?></textarea>
                    <p class="ad-hint">Pflicht-Platzhalter: <code>{{inhalt}}</code> (Inhalt der Ausgabe) und
                        <code>{{abmelden_url}}</code> (Abmeldelink). Empfohlen: <code>{{preheader}}</code>,
                        <code>{{impressum}}</code>, <code>{{webansicht_url}}</code>.</p>
                </div>

                <div class="ad-actions">
                    <button type="submit" name="aktion" value="speichern" class="ad-btn">Speichern</button>
                    <button type="submit" name="aktion" value="modus" class="ad-btn ad-btn-secondary"
                            data-confirm="Zum Baukasten wechseln? Die Vorlage wird ab dann aus Bausteinen erzeugt.">Im Baukasten gestalten</button>
                    <?php if ((int) $current['is_default'] !== 1): ?>
                        <button type="submit" name="aktion" value="standard" class="ad-btn ad-btn-secondary">Als Standard</button>
                    <?php endif; ?>
                    <button type="submit" name="aktion" value="zuruecksetzen" class="ad-btn ad-btn-secondary"
                            data-confirm="Vorlage auf die Auslieferungsfassung zurücksetzen?">Zurücksetzen</button>
                    <?php if (count($templates) > 1): ?>
                        <button type="submit" name="aktion" value="loeschen" class="ad-btn ad-btn-danger"
                                data-confirm="Vorlage wirklich löschen?">Löschen</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="ad-card">
            <h2>Vorschau</h2>
            <iframe class="ad-preview-frame" style="height:520px;"
                    src="vorlagen.php?id=<?= (int) $current['id'] ?>&amp;vorschau=1"
                    title="Vorschau der Vorlage"></iframe>
            <p class="ad-hint">Die Vorschau zeigt die Vorlage mit Beispielinhalt.</p>
        </div>
    </div>
    <?php endif; ?>

    <!-- ---------------------------------------------------------- Marke -->
    <?php $marke = Templates::brand($current); ?>
    <div class="ad-card" id="marke">
        <h2 style="margin-top:0;">Marke dieser Vorlage</h2>
        <p class="ad-hint">Nur ausfüllen, wenn diese Vorlage zu einer <strong>anderen Website</strong> gehört –
            etwa einem zweiten Projekt mit eigenem Impressum. Leere Felder verwenden automatisch die Angaben
            aus den <a href="einstellungen.php">Einstellungen</a>. Kopfzeile und Footer der Vorlage übernehmen
            die Werte beim nächsten Speichern des Newsletters.</p>

        <form method="post">
            <?= Util::csrfField() ?>
            <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
            <input type="hidden" name="aktion" value="marke">

            <div class="ad-row">
                <div class="ad-field">
                    <label for="brand_name">Name der Marke <span class="ad-hint">({{marke}})</span></label>
                    <input type="text" id="brand_name" name="brand_name" maxlength="190"
                           value="<?= Util::e((string) $current['brand_name']) ?>"
                           placeholder="<?= Util::e(Settings::get('brand_name')) ?>">
                </div>
                <div class="ad-field">
                    <label for="brand_website_url">Website <span class="ad-hint">({{website}})</span></label>
                    <input type="url" id="brand_website_url" name="brand_website_url" maxlength="190"
                           value="<?= Util::e((string) $current['website_url']) ?>"
                           placeholder="<?= Util::e(Settings::get('website_url')) ?>">
                </div>
            </div>

            <div class="ad-field">
                <label for="brand_imprint">Impressum im Footer <span class="ad-hint">({{impressum}} – Pflichtangaben)</span></label>
                <textarea id="brand_imprint" name="brand_imprint" rows="3"
                          placeholder="<?= Util::e(Settings::get('imprint')) ?>"><?= Util::e((string) $current['imprint']) ?></textarea>
            </div>

            <div class="ad-row">
                <div class="ad-field">
                    <label for="brand_imprint_url">Impressum-Seite <span class="ad-hint">({{impressum_url}})</span></label>
                    <input type="url" id="brand_imprint_url" name="brand_imprint_url" maxlength="190"
                           value="<?= Util::e((string) $current['imprint_url']) ?>"
                           placeholder="<?= Util::e(Settings::get('imprint_url')) ?>">
                </div>
                <div class="ad-field">
                    <label for="brand_privacy_url">Datenschutz-Seite <span class="ad-hint">({{datenschutz_url}})</span></label>
                    <input type="url" id="brand_privacy_url" name="brand_privacy_url" maxlength="190"
                           value="<?= Util::e((string) $current['privacy_url']) ?>"
                           placeholder="<?= Util::e(Settings::get('privacy_url')) ?>">
                </div>
            </div>

            <div class="ad-row">
                <div class="ad-field">
                    <label for="brand_sender_name">Absendername</label>
                    <input type="text" id="brand_sender_name" name="brand_sender_name" maxlength="190"
                           value="<?= Util::e((string) $current['sender_name']) ?>"
                           placeholder="<?= Util::e(Settings::get('sender_name')) ?>">
                </div>
                <div class="ad-field">
                    <label for="brand_sender_email">Absenderadresse</label>
                    <input type="email" id="brand_sender_email" name="brand_sender_email" maxlength="190"
                           value="<?= Util::e((string) $current['sender_email']) ?>"
                           placeholder="<?= Util::e(Settings::get('sender_email')) ?>">
                    <p class="ad-hint">Gilt für Automationen mit dieser Vorlage und als Vorschlag für neue
                        Newsletter. Die Adresse muss zu einer Domain gehören, für die Ihr Versandweg senden darf.</p>
                </div>
            </div>

            <div class="ad-actions">
                <button type="submit" class="ad-btn">Marke speichern</button>
                <?php if (Templates::hasOwnBrand($current)): ?>
                    <span class="ad-pill ad-pill-blue">eigene Marke: <?= Util::e($marke['brand_name']) ?></span>
                <?php else: ?>
                    <span class="ad-hint">Zurzeit gelten die Einstellungen (<?= Util::e($marke['brand_name']) ?>).</span>
                <?php endif; ?>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php';
