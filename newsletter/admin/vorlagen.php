<?php
/**
 * vorlagen.php – Design-Vorlagen anlegen und bearbeiten.
 */

require_once dirname(__DIR__) . '/lib/bootstrap.php';

/* Vorschau einer Vorlage (wird im Rahmen angezeigt) */
if (Util::get('vorschau') === '1') {
    Auth::require();
    $template = Templates::byId(Util::getInt('id'));
    if ($template === null) {
        http_response_code(404);
        exit('Vorlage nicht gefunden.');
    }
    $html = Renderer::wrap($template, Templates::starterContent(), 'Beispiel-Betreff', 'Beispiel-Vorschautext');
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
require __DIR__ . '/partials/header.php';

if (Util::isPost()) {
    Util::requireCsrf();
    $action = Util::post('aktion');
    $id     = Util::postInt('id');

    if ($action === 'anlegen') {
        $newId = Templates::create(Util::post('name') ?: 'Neue Vorlage', Templates::standardHtml(),
            Util::post('description'));
        Util::flash('Vorlage angelegt.');
        Util::redirect('vorlagen.php?id=' . $newId);
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
        <button type="submit" class="ad-btn">Neue Vorlage</button>
    </form>
</div>

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
    <div class="ad-editor-grid">
        <div class="ad-card">
            <form method="post" data-warn-unsaved>
                <?= Util::csrfField() ?>
                <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">

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

<?php require __DIR__ . '/partials/footer.php';
