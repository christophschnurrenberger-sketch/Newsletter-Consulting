<?php
/**
 * vorlagen.php – Design-Vorlagen anlegen und bearbeiten.
 */

require_once dirname(__DIR__) . '/lib/bootstrap.php';

/*
 * Vorschau einer Vorlage (wird im Rahmen angezeigt).
 *
 * Drei Fälle: eine angelegte Vorlage (id), eine mitgelieferte Datei, die
 * noch niemand angelegt hat (datei) – damit sich eine Marke ansehen lässt,
 * bevor man sie benutzt – und ganz ohne Angabe der schlichte Rahmen aus
 * den Einstellungen.
 */
if (Util::get('vorschau') === '1') {
    Auth::require('lesen');
    $datei    = Util::get('datei');
    $id       = Util::getInt('id');
    $template = $datei !== '' ? Templates::fromFile($datei) : ($id > 0 ? Templates::byId($id) : null);
    if ($template === null && ($datei !== '' || $id > 0)) {
        http_response_code(404);
        exit('Vorlage nicht gefunden.');
    }
    /*
     * Bei einer Baukasten-Vorlage entsteht die Vorschau aus den Bausteinen,
     * nicht aus dem zuletzt gespeicherten HTML. So stimmt sie auch dann,
     * wenn in der Datenbank noch eine ältere Fassung liegt – etwa mit einem
     * Baustein unter dem Footer, wie es früher passieren konnte.
     */
    if (Templates::usesBuilder($template)) {
        $template['html'] = Blocks::renderDocument(Templates::blocks($template));
    }

    $mitBeispiel = Util::get('beispiel') === '1';
    $html = Renderer::wrap($template, Templates::starterContent($template, $mitBeispiel),
        'Beispiel-Betreff', 'Beispiel-Vorschautext');
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
            : Templates::create(Util::post('name') ?: 'Neue Vorlage', Templates::minimalHtml(),
                Util::post('description'));
        Util::flash($imBaukasten
            ? 'Vorlage angelegt: Kopfzeile, Platz für den Inhalt und Footer. Alles dazwischen bauen Sie selbst.'
            : 'Vorlage angelegt – ein schlanker Rahmen mit Kopfzeile, {{inhalt}} und den Pflichtangaben.');
        Util::redirect('vorlagen.php?id=' . $newId);
    }
    // Der Baukasten speichert im Hintergrund – Antwort als JSON, keine Weiterleitung.
    if (Util::post('autosave') === '1' && $id > 0) {
        $template = Templates::byId($id);
        if ($template === null) {
            Util::json(['ok' => false, 'fehler' => 'Vorlage nicht gefunden.'], 404);
        }
        Templates::update($id, Util::post('name') ?: (string) $template['name'],
            (string) $template['html'], Util::post('description'), Util::postRaw('blocks_json'));
        Util::json(['ok' => true, 'zeit' => date('H:i')]);
    }

    if ($action === 'marke_zuordnen' && $id > 0) {
        // Neuer Name schlägt die Auswahl – wer tippt, meint das auch so.
        $wunsch = Util::post('marke_neu') !== '' ? Util::post('marke_neu') : Util::post('marke_wahl');
        if (Templates::assignBrand($id, $wunsch)) {
            Util::flash('Vorlage gehört jetzt zur Marke „' . Util::e(trim($wunsch)) . '".');
        } else {
            Util::flash('Bitte geben Sie einen Markennamen an.', 'error');
        }
        Util::redirect('vorlagen.php?id=' . $id . '#marke');
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
            // Ein von Hand geschriebener Rahmen lässt sich nicht in Bausteine
            // zerlegen. Damit nichts verloren geht, wird er vorher gesichert.
            $html = trim((string) $template['html']);
            if ($template['editor_mode'] !== 'blocks' && $html !== '') {
                DB::update('templates', ['html_backup' => $html], 'id = ?', [$id]);
            }
            $json = trim((string) $template['blocks_json']) !== ''
                ? (string) $template['blocks_json']
                : (string) json_encode(Blocks::starterTemplate());
            Templates::update($id, (string) $template['name'], '', (string) $template['description'], $json);
            Util::flash('Baukasten aktiviert. Der bisherige HTML-Rahmen ist gesichert und lässt sich '
                . 'über „HTML zurückholen" wiederherstellen.');
        } elseif ($template !== null) {
            DB::update('templates', ['editor_mode' => 'html'], 'id = ?', [$id]);
            Util::flash('HTML-Modus aktiviert. Sie bearbeiten jetzt den erzeugten Code direkt.');
        }
        Util::redirect('vorlagen.php?id=' . $id);
    }

    if ($action === 'html_zurueck' && $id > 0) {
        $template = Templates::byId($id);
        $sicherung = trim((string) ($template['html_backup'] ?? ''));
        if ($template === null || $sicherung === '') {
            Util::flash('Es gibt keine gesicherte HTML-Fassung.', 'error');
            Util::redirect('vorlagen.php?id=' . $id);
        }
        Templates::update($id, (string) $template['name'], $sicherung, (string) $template['description']);
        DB::update('templates', ['editor_mode' => 'html', 'html_backup' => null], 'id = ?', [$id]);
        Util::flash('Die gesicherte HTML-Fassung ist wieder da.');
        Util::redirect('vorlagen.php?id=' . $id);
    }

    if ($action === 'design' && $id > 0) {
        if (Templates::applyDesign($id, Util::post('quelle'))) {
            $meldung = 'Aussehen gewechselt. Ihre Markenangaben sind unverändert – '
                . 'die Wortmarke im Kopf steht auf Ihre Marke.';
            if ((string) (Templates::byId($id)['editor_mode'] ?? '') === 'html') {
                // Ein HTML-Rahmen bringt seine Texte mit; die kann niemand
                // automatisch auf die eigene Marke umschreiben.
                $meldung .= ' Achtung: Das ist ein HTML-Rahmen – bitte prüfen Sie den Footer '
                    . 'auf Texte, die zur anderen Marke gehören.';
            }
            Util::flash($meldung);
        } else {
            Util::flash('Dieses Design gibt es nicht.', 'error');
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
$fertige   = Templates::files();

/*
 * Anlegen ist ein eigener Reiter, kein Dauerformular über der Vorlage.
 * Vorher standen drei Wege zum Anlegen über der Vorlage, die man gerade
 * bearbeitete – man scrollte jedes Mal daran vorbei.
 */
$neu = Util::get('anlegen') !== '' || $templates === [];
if ($neu) {
    $current = null;
}
?>

<div class="ad-page-head">
    <div>
        <h1>Vorlagen</h1>
        <p class="ad-sub">Das Aussehen: der Rahmen um Ihre Newsletter-Inhalte.
            Wer der Absender ist – Name, Website, Impressum – steht unter
            <a href="marken.php">Marken</a>.</p>
    </div>
</div>

<nav class="ad-reiter" aria-label="Vorlagen">
    <?php foreach ($templates as $template): ?>
        <a class="ad-reiter-tab <?= $current !== null && (int) $current['id'] === (int) $template['id'] ? 'is-aktiv' : '' ?>"
           href="vorlagen.php?id=<?= (int) $template['id'] ?>">
            <?= Util::e((string) $template['name']) ?>
            <?php if ((int) $template['is_default'] === 1): ?>
                <span class="ad-pill ad-pill-blue">Standard</span>
            <?php endif; ?>
        </a>
    <?php endforeach; ?>
    <a class="ad-reiter-tab <?= $neu ? 'is-aktiv' : '' ?>" href="vorlagen.php?anlegen=1">+ Neue Vorlage</a>
</nav>

<?php if ($neu): ?>
    <div class="ad-card">
        <h2 style="margin-top:0;">Neue Vorlage anlegen</h2>

        <?php if ($fertige !== []): ?>
            <h3 style="margin-top:18px;">Fertiges Design übernehmen</h3>
            <p class="ad-hint">Mitgelieferte Entwürfe aus dem Ordner <code>newsletter/vorlagen/</code> –
                fertig gestaltet, danach beliebig änderbar. Das ist der schnellste Weg.</p>
            <form method="post" class="ad-actions-inline" style="margin-top:10px;">
                <?= Util::csrfField() ?>
                <input type="hidden" name="aktion" value="aus_datei">
                <select name="datei" class="ad-input" style="width:auto;min-width:240px;">
                    <?php foreach ($fertige as $schluessel => $angaben): ?>
                        <option value="<?= Util::e($schluessel) ?>"><?= Util::e($angaben['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="ad-btn">Übernehmen</button>
            </form>
            <ul class="ad-hint" style="margin:12px 0 0 18px;padding:0;">
                <?php foreach ($fertige as $angaben): ?>
                    <li><strong><?= Util::e($angaben['name']) ?></strong><?= $angaben['description'] !== ''
                        ? ' – ' . Util::e($angaben['description']) : '' ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <h3 style="margin-top:26px;">Oder leer beginnen</h3>
        <p class="ad-hint">Sie bekommen Kopfzeile, Inhaltsfläche und Footer – der Rest ist Ihrer.</p>
        <form method="post" class="ad-actions-inline" style="margin-top:10px;">
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="anlegen">
            <input type="text" name="name" class="ad-input" style="width:220px;" placeholder="Name der Vorlage">
            <button type="submit" name="baukasten" value="1" class="ad-btn ad-btn-secondary">Im Baukasten</button>
            <button type="submit" name="baukasten" value="0" class="ad-btn ad-btn-secondary">Als HTML</button>
        </form>
        <p class="ad-hint">Im Baukasten setzen Sie die Vorlage aus Bausteinen zusammen; „Als HTML“ ist für
            fertigen Code aus einer anderen Quelle.</p>

        <?php if ($templates !== []): ?>
            <p style="margin-top:20px;"><a href="vorlagen.php">Abbrechen</a></p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if ($current === null && !$neu): ?>
    <div class="ad-empty">Noch keine Vorlage vorhanden.</div>
<?php elseif ($current !== null): ?>
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

                <?php /* Häufiges sichtbar, Seltenes im Menü – und „Löschen“ nicht
                         als roter Knopf neben „Speichern“. */ ?>
                <div class="ad-actions">
                    <button type="submit" name="aktion" value="speichern_baukasten" class="ad-btn">Vorlage speichern</button>
                    <a class="ad-btn ad-btn-secondary" target="_blank" rel="noopener"
                       href="vorlagen.php?id=<?= (int) $current['id'] ?>&amp;vorschau=1">Vorschau in neuem Tab</a>
                    <details class="ad-menue">
                        <summary class="ad-btn ad-btn-secondary" title="Weitere Aktionen">…</summary>
                        <div class="ad-menue-liste">
                            <?php if ((int) $current['is_default'] !== 1): ?>
                                <button type="submit" name="aktion" value="standard">Als Standard festlegen</button>
                            <?php endif; ?>
                            <?php if (trim((string) ($current['html_backup'] ?? '')) !== ''): ?>
                                <button type="submit" name="aktion" value="html_zurueck"
                                        data-confirm="Die gesicherte HTML-Fassung wiederherstellen? Die Bausteine dieser Vorlage gehen dabei verloren.">HTML zurückholen</button>
                            <?php endif; ?>
                            <?php if (count($templates) > 1): ?>
                                <button type="submit" name="aktion" value="loeschen" class="ist-gefahr"
                                        data-confirm="Vorlage wirklich löschen?">Vorlage löschen</button>
                            <?php endif; ?>
                        </div>
                    </details>
                </div>
            </form>
        </div>

        <div class="ad-card">
            <h2>So sieht die Vorlage aus</h2>
            <iframe class="ad-preview-frame" style="height:560px;"
                    src="vorlagen.php?id=<?= (int) $current['id'] ?>&amp;vorschau=1"
                    title="Vorschau der Vorlage"></iframe>
            <p class="ad-hint">Die Vorschau zeigt den gespeicherten Stand. Die Vorlage ist nur der Rahmen –
                der Text kommt später aus dem jeweiligen Newsletter.
                <a href="vorlagen.php?id=<?= (int) $current['id'] ?>&amp;vorschau=1&amp;beispiel=1"
                   target="_blank" rel="noopener">Mit Beispieltext ansehen</a></p>
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
                            data-confirm="Zum Baukasten wechseln? Ein von Hand geschriebener HTML-Rahmen lässt sich nicht in Bausteine zerlegen – er wird durch einen Standardrahmen ersetzt. Die bisherige Fassung wird gesichert und lässt sich zurückholen.">Im Baukasten gestalten</button>
                    <details class="ad-menue">
                        <summary class="ad-btn ad-btn-secondary" title="Weitere Aktionen">…</summary>
                        <div class="ad-menue-liste">
                            <?php if ((int) $current['is_default'] !== 1): ?>
                                <button type="submit" name="aktion" value="standard">Als Standard festlegen</button>
                            <?php endif; ?>
                            <button type="submit" name="aktion" value="zuruecksetzen"
                                    data-confirm="Vorlage auf die Auslieferungsfassung zurücksetzen?">Auf Auslieferungsfassung zurücksetzen</button>
                            <?php if (count($templates) > 1): ?>
                                <button type="submit" name="aktion" value="loeschen" class="ist-gefahr"
                                        data-confirm="Vorlage wirklich löschen?">Vorlage löschen</button>
                            <?php endif; ?>
                        </div>
                    </details>
                </div>
            </form>
        </div>

        <div class="ad-card">
            <h2>Vorschau</h2>
            <iframe class="ad-preview-frame" style="height:520px;"
                    src="vorlagen.php?id=<?= (int) $current['id'] ?>&amp;vorschau=1"
                    title="Vorschau der Vorlage"></iframe>
            <p class="ad-hint">Die Vorlage ist nur der Rahmen – der Text kommt später aus dem Newsletter.
                <a href="vorlagen.php?id=<?= (int) $current['id'] ?>&amp;vorschau=1&amp;beispiel=1"
                   target="_blank" rel="noopener">Mit Beispieltext ansehen</a></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- -------------------------------------------------- Aussehen wechseln -->
    <?php $designs = Templates::designs((int) $current['id']); ?>
    <?php if ($designs !== []): ?>
        <div class="ad-card" id="design">
            <h2 style="margin-top:0;">Aussehen wechseln</h2>
            <p class="ad-hint">Übernimmt Farben, Schriften, Kopfzeile und Footer eines anderen Designs –
                <strong>Ihre Markenangaben bleiben</strong>, und die Wortmarke im Kopf wird auf
                „<?= Util::e((string) Templates::brand($current)['brand_name']) ?>“ umgeschrieben.
                Geschriebene Newsletter behalten ihren Text.</p>

            <div class="ad-designwahl ad-designwahl-klein">
                <?php foreach ($designs as $d): ?>
                    <form method="post">
                        <?= Util::csrfField() ?>
                        <input type="hidden" name="aktion" value="design">
                        <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
                        <input type="hidden" name="quelle" value="<?= Util::e($d['schluessel']) ?>">
                        <button type="submit" class="ad-design ad-design-knopf"
                                data-confirm="Aussehen dieser Vorlage durch „<?= Util::e($d['name']) ?>“ ersetzen?">
                            <span class="ad-design-bild">
                                <iframe src="vorlagen.php?vorschau=1<?= $d['datei'] !== ''
                                            ? '&amp;datei=' . Util::e(urlencode($d['datei']))
                                            : '&amp;id=' . (int) substr($d['schluessel'], 8) ?>"
                                        title="Vorschau" loading="lazy" scrolling="no" tabindex="-1"></iframe>
                            </span>
                            <span class="ad-design-fuss">
                                <strong><?= Util::e($d['name']) ?></strong>
                                <?php if ($d['marke'] !== ''): ?>
                                    <em>Marke <?= Util::e($d['marke']) ?></em>
                                <?php endif; ?>
                            </span>
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- ---------------------------------------------------------- Marke -->
    <?php
    /*
     * Hier standen bis zur UX-Durchsicht dieselben sieben Felder wie unter
     * „Marken" – die Angaben ließen sich an drei Stellen ändern (Vorlage,
     * Marke, Einstellungen), und niemand wusste, welche gilt. Jetzt sagt
     * die Seite nur noch, zu welcher Marke die Vorlage gehört, und verweist
     * zum Pflegen dorthin. Das Umhängen bleibt möglich, ist aber selten.
     */
    $marke   = Templates::brand($current);
    $eigene  = Templates::hasOwnBrand($current);
    $zurMarke = 'marken.php';
    foreach (Templates::brands() as $eintrag) {
        foreach ($eintrag['vorlagen'] as $v) {
            if ((int) $v['id'] === (int) $current['id']) {
                $zurMarke = 'marken.php?m=' . urlencode((string) $eintrag['schluessel']);
                break 2;
            }
        }
    }
    ?>
    <div class="ad-card" id="marke">
        <h2 style="margin-top:0;">Marke dieser Vorlage</h2>
        <p>Diese Vorlage gehört zur Marke <strong><?= Util::e((string) $marke['brand_name']) ?></strong>.
            Absender, Impressum und die Links im Footer stehen unter <a href="marken.php">Marken</a> –
            dort gelten sie für alle Designs dieser Marke auf einmal.</p>
        <?php if (!$eigene): ?>
            <p class="ad-hint">Für diese Vorlage ist noch nichts Eigenes hinterlegt; es gelten die
                Angaben aus den <a href="einstellungen.php">Einstellungen</a>.</p>
        <?php endif; ?>

        <div class="ad-actions">
            <a class="ad-btn ad-btn-secondary" href="<?= Util::e($zurMarke) ?>">Marke öffnen</a>
        </div>

        <details class="ad-klapp" style="margin-top:18px;border-top:1px solid var(--ad-border);padding-top:14px;">
            <summary style="font-size:14px;font-weight:700;color:var(--ad-navy);">
                Diese Vorlage einer anderen Marke zuordnen
                <span class="ad-klapp-zeichen" aria-hidden="true"></span>
            </summary>
            <p class="ad-hint" style="margin-top:10px;">Nur nötig, wenn dieses Design zu einem anderen
                Projekt gehören soll. Bei einer vorhandenen Marke übernimmt die Vorlage deren Absender
                und Impressum; ein neuer Name legt eine weitere Marke an.</p>
            <form method="post" class="ad-row" style="align-items:flex-end;">
                <?= Util::csrfField() ?>
                <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
                <input type="hidden" name="aktion" value="marke_zuordnen">
                <div class="ad-field" style="margin:0;">
                    <label for="marke_wahl">Vorhandene Marke</label>
                    <select id="marke_wahl" name="marke_wahl">
                        <?php foreach (Templates::brands() as $eintrag): ?>
                            <?php if ($eintrag['template'] === null) { continue; } ?>
                            <option value="<?= Util::e((string) $eintrag['name']) ?>"
                                <?= strcasecmp((string) $eintrag['name'], (string) $marke['brand_name']) === 0 ? 'selected' : '' ?>>
                                <?= Util::e((string) $eintrag['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="ad-field" style="margin:0;">
                    <label for="marke_neu">… oder neuer Markenname</label>
                    <input type="text" id="marke_neu" name="marke_neu" maxlength="190" placeholder="z. B. Fairway54">
                </div>
                <div class="ad-field" style="margin:0;flex:0;">
                    <label>&nbsp;</label>
                    <button type="submit" class="ad-btn ad-btn-secondary">Zuordnen</button>
                </div>
            </form>
        </details>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php';
