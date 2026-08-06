<?php
/**
 * automationen.php – Willkommensstrecken und andere Mailserien.
 */

require_once dirname(__DIR__) . '/lib/bootstrap.php';

/* Vorschau eines Schrittes */
if (Util::get('vorschau') === '1') {
    Auth::require();
    $stepId = Util::getInt('schritt');
    Automations::compileStep($stepId);
    $source = Automations::stepAsCampaign($stepId);
    if ($source === null) {
        http_response_code(404);
        exit('Schritt nicht gefunden.');
    }
    $mail = Campaigns::renderFor($source, Renderer::sampleSubscriber(), 'vorschau');
    header('Content-Type: text/html; charset=utf-8');
    echo preg_replace('#<img[^>]+track\.php\?o=[^>]*>#i', '', $mail['html']);
    exit;
}

$pageTitle = 'Automationen';
require __DIR__ . '/partials/header.php';

if (Util::isPost()) {
    Util::requireCsrf();
    $action = Util::post('aktion');
    $id     = Util::postInt('id');
    $stepId = Util::postInt('schritt_id');

    if ($action === 'anlegen') {
        $newId = Automations::create(Util::post('name') ?: 'Willkommensstrecke', Util::postInt('list_id') ?: null);
        Automations::addStep($newId, 0);
        Util::flash('Strecke angelegt. Ergänzen Sie nun Betreff und Inhalt des ersten Schrittes.');
        Util::redirect('automationen.php?id=' . $newId);
    }
    if ($action === 'speichern' && $id > 0) {
        Automations::save($id, [
            'name'    => Util::post('name'),
            'list_id' => Util::postInt('list_id') ?: null,
            'status'  => Util::post('status') === Automations::ACTIVE ? Automations::ACTIVE : Automations::PAUSED,
        ]);
        Util::flash('Gespeichert.');
        Util::redirect('automationen.php?id=' . $id);
    }
    if ($action === 'loeschen' && $id > 0) {
        Automations::delete($id);
        Util::flash('Strecke gelöscht.');
        Util::redirect('automationen.php');
    }
    if ($action === 'schritt_neu' && $id > 0) {
        Automations::addStep($id, 24);
        Util::flash('Schritt hinzugefügt.');
        Util::redirect('automationen.php?id=' . $id);
    }
    if ($action === 'schritt_speichern' && $stepId > 0) {
        Automations::saveStep($stepId, [
            'subject'      => Util::post('subject'),
            'delay_hours'  => max(0, Util::postInt('delay_hours')),
            'template_id'  => Util::postInt('template_id') ?: null,
            'content_html' => Util::postRaw('content_html'),
            'content_text' => Util::postRaw('content_text'),
            'track_opens'  => Util::post('track_opens') === '1' ? 1 : 0,
            'track_clicks' => Util::post('track_clicks') === '1' ? 1 : 0,
        ]);
        Util::flash('Schritt gespeichert.');
        Util::redirect('automationen.php?id=' . $id . '&schritt=' . $stepId);
    }
    if ($action === 'schritt_loeschen' && $stepId > 0) {
        Automations::deleteStep($stepId);
        Util::flash('Schritt gelöscht.');
        Util::redirect('automationen.php?id=' . $id);
    }
}

$automations = Automations::all();
$current     = Automations::byId(Util::getInt('id'));
$steps       = $current !== null ? Automations::steps((int) $current['id']) : [];
$currentStep = null;
if ($steps !== []) {
    $wanted      = Util::getInt('schritt');
    $currentStep = $wanted > 0 ? Automations::step($wanted) : $steps[0];
    if ($currentStep !== null && (int) $currentStep['automation_id'] !== (int) $current['id']) {
        $currentStep = $steps[0];
    }
}
?>

<div class="ad-page-head">
    <div>
        <h1>Automationen</h1>
        <p class="ad-sub">Mails, die zeitversetzt nach der Anmeldung verschickt werden</p>
    </div>
</div>

<div class="ad-card">
    <h2>Neue Strecke</h2>
    <form method="post">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="anlegen">
        <div class="ad-row" style="align-items:flex-end;">
            <div class="ad-field">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Willkommensstrecke">
            </div>
            <div class="ad-field">
                <label for="list_id">Nur für Anmeldungen dieser Liste</label>
                <select id="list_id" name="list_id">
                    <option value="0">Alle Listen</option>
                    <?php foreach (Lists::all() as $list): ?>
                        <option value="<?= (int) $list['id'] ?>"><?= Util::e((string) $list['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="ad-field" style="flex:0;">
                <label>&nbsp;</label>
                <button type="submit" class="ad-btn">Anlegen</button>
            </div>
        </div>
        <p class="ad-hint">Auslöser ist immer die <strong>bestätigte Anmeldung</strong>. Die Wartezeit eines
            Schrittes zählt ab diesem Zeitpunkt – „0 Stunden“ heißt also sofort nach der Bestätigung.</p>
    </form>
</div>

<?php if ($automations === []): ?>
    <div class="ad-empty">Noch keine Strecke angelegt.</div>
<?php else: ?>
    <div class="ad-card ad-card-tight">
        <div class="ad-actions" style="margin-top:0;">
            <?php foreach ($automations as $automation):
                $stats = Automations::stats((int) $automation['id']); ?>
                <a class="ad-btn ad-btn-small <?= $current !== null && (int) $current['id'] === (int) $automation['id'] ? '' : 'ad-btn-secondary' ?>"
                   href="automationen.php?id=<?= (int) $automation['id'] ?>">
                    <?= Util::e((string) $automation['name']) ?>
                    (<?= $automation['status'] === Automations::ACTIVE ? 'aktiv' : 'pausiert' ?>,
                    <?= Util::num($stats['sent']) ?> Mails)
                </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($current !== null):
    $stats = Automations::stats((int) $current['id']); ?>
    <div class="ad-grid">
        <div class="ad-stat">
            <div class="ad-stat-label">Teilnehmer</div>
            <div class="ad-stat-value"><?= Util::num($stats['subscribers']) ?></div>
            <div class="ad-stat-note"><?= Util::num($stats['pending']) ?> warten auf den nächsten Schritt</div>
        </div>
        <div class="ad-stat">
            <div class="ad-stat-label">Versendete Mails</div>
            <div class="ad-stat-value"><?= Util::num($stats['sent']) ?></div>
        </div>
        <div class="ad-stat">
            <div class="ad-stat-label">Öffnungen</div>
            <div class="ad-stat-value"><?= Util::num($stats['opens']) ?></div>
        </div>
        <div class="ad-stat">
            <div class="ad-stat-label">Klicks</div>
            <div class="ad-stat-value"><?= Util::num($stats['clicks']) ?></div>
        </div>
    </div>

    <div class="ad-card">
        <form method="post">
            <?= Util::csrfField() ?>
            <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
            <div class="ad-row" style="align-items:flex-end;">
                <div class="ad-field">
                    <label for="a_name">Name</label>
                    <input type="text" id="a_name" name="name" value="<?= Util::e((string) $current['name']) ?>">
                </div>
                <div class="ad-field">
                    <label for="a_list">Liste</label>
                    <select id="a_list" name="list_id">
                        <option value="0">Alle Listen</option>
                        <?php foreach (Lists::all() as $list): ?>
                            <option value="<?= (int) $list['id'] ?>"
                                <?= (int) $current['list_id'] === (int) $list['id'] ? 'selected' : '' ?>>
                                <?= Util::e((string) $list['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="ad-field">
                    <label for="a_status">Status</label>
                    <select id="a_status" name="status">
                        <option value="paused" <?= $current['status'] === Automations::PAUSED ? 'selected' : '' ?>>Pausiert</option>
                        <option value="active" <?= $current['status'] === Automations::ACTIVE ? 'selected' : '' ?>>Aktiv</option>
                    </select>
                </div>
                <div class="ad-field" style="flex:0;">
                    <label>&nbsp;</label>
                    <div class="ad-actions-inline">
                        <button type="submit" name="aktion" value="speichern" class="ad-btn">Speichern</button>
                        <button type="submit" name="aktion" value="schritt_neu" class="ad-btn ad-btn-secondary">Schritt hinzufügen</button>
                        <button type="submit" name="aktion" value="loeschen" class="ad-btn ad-btn-danger"
                                data-confirm="Strecke mit allen Schritten löschen?">Löschen</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php if ($steps === []): ?>
        <div class="ad-empty">Diese Strecke hat noch keinen Schritt.</div>
    <?php else: ?>
        <div class="ad-card ad-card-tight">
            <div class="ad-actions" style="margin-top:0;">
                <?php foreach ($steps as $step):
                    $stepStats = Automations::stepStats((int) $step['id']); ?>
                    <a class="ad-btn ad-btn-small <?= $currentStep !== null && (int) $currentStep['id'] === (int) $step['id'] ? '' : 'ad-btn-secondary' ?>"
                       href="automationen.php?id=<?= (int) $current['id'] ?>&amp;schritt=<?= (int) $step['id'] ?>">
                        Schritt <?= (int) $step['position'] ?>
                        (<?= (int) $step['delay_hours'] === 0 ? 'sofort' : '+' . (int) $step['delay_hours'] . ' h' ?>,
                        <?= Util::num($stepStats['sent']) ?> Mails)
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($currentStep !== null): ?>
        <div class="ad-editor-grid">
            <div class="ad-card">
                <h2>Schritt <?= (int) $currentStep['position'] ?> bearbeiten</h2>
                <form method="post" data-warn-unsaved>
                    <?= Util::csrfField() ?>
                    <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
                    <input type="hidden" name="schritt_id" value="<?= (int) $currentStep['id'] ?>">

                    <div class="ad-row">
                        <div class="ad-field" style="flex:2 1 300px;">
                            <label for="subject">Betreff</label>
                            <input type="text" id="subject" name="subject" value="<?= Util::e((string) $currentStep['subject']) ?>"
                                   placeholder="Ohne Betreff wird dieser Schritt nicht versendet">
                        </div>
                        <div class="ad-field">
                            <label for="delay_hours">Versand nach … Stunden</label>
                            <input type="number" id="delay_hours" name="delay_hours" min="0" max="8760"
                                   value="<?= (int) $currentStep['delay_hours'] ?>">
                        </div>
                        <div class="ad-field">
                            <label for="template_id">Vorlage</label>
                            <select id="template_id" name="template_id">
                                <?php foreach (Templates::all() as $template): ?>
                                    <option value="<?= (int) $template['id'] ?>"
                                        <?= (int) $currentStep['template_id'] === (int) $template['id'] ? 'selected' : '' ?>>
                                        <?= Util::e((string) $template['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="ad-field">
                        <label for="content_html">Inhalt (HTML)</label>
                        <textarea id="content_html" name="content_html" rows="18" class="ad-code"><?= Util::e((string) $currentStep['content_html']) ?></textarea>
                    </div>

                    <div class="ad-field">
                        <label for="content_text">Textfassung <span class="ad-hint">(leer = automatisch)</span></label>
                        <textarea id="content_text" name="content_text" rows="4" class="ad-code"><?= Util::e((string) $currentStep['content_text']) ?></textarea>
                    </div>

                    <label class="ad-check">
                        <input type="checkbox" name="track_opens" value="1" <?= (int) $currentStep['track_opens'] === 1 ? 'checked' : '' ?>>
                        <span>Öffnungen messen</span>
                    </label>
                    <label class="ad-check">
                        <input type="checkbox" name="track_clicks" value="1" <?= (int) $currentStep['track_clicks'] === 1 ? 'checked' : '' ?>>
                        <span>Klicks messen</span>
                    </label>

                    <div class="ad-actions">
                        <button type="submit" name="aktion" value="schritt_speichern" class="ad-btn">Schritt speichern</button>
                        <button type="submit" name="aktion" value="schritt_loeschen" class="ad-btn ad-btn-danger"
                                data-confirm="Diesen Schritt löschen?">Schritt löschen</button>
                    </div>
                </form>
            </div>

            <div class="ad-card">
                <h2>Vorschau</h2>
                <iframe class="ad-preview-frame" style="height:520px;"
                        src="automationen.php?vorschau=1&amp;schritt=<?= (int) $currentStep['id'] ?>"
                        title="Vorschau des Automationsschrittes"></iframe>
                <p class="ad-hint">Änderungen erscheinen nach dem Speichern.</p>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php';
