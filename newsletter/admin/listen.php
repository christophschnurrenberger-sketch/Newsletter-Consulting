<?php
/**
 * listen.php – Verteiler anlegen und pflegen.
 */

$pageTitle = 'Listen';
require __DIR__ . '/partials/header.php';

if (Util::isPost()) {
    Util::requireCsrf();
    if (!Auth::can('empfaenger')) {
        Util::flash('Dafür fehlt Ihnen die Berechtigung. Fragen Sie eine Administratorin oder einen Administrator.', 'error');
        Util::redirect('listen.php');
    }
    $action = Util::post('aktion');
    $id     = Util::postInt('id');

    if ($action === 'anlegen') {
        $name = Util::post('name');
        if ($name === '') {
            Util::flash('Bitte geben Sie einen Namen an.', 'error');
        } else {
            Lists::create($name, Util::post('description'), Lists::defaultId() === 0);
            Util::flash('Liste angelegt.');
        }
        Util::redirect('listen.php');
    }
    if ($action === 'speichern' && $id > 0) {
        Lists::update($id, Util::post('name'), Util::post('description'));
        Lists::saveTemplate($id, Util::postInt('template_id') ?: null);
        Util::flash('Gespeichert.');
        Util::redirect('listen.php');
    }
    if ($action === 'standard' && $id > 0) {
        Lists::makeDefault($id);
        Util::flash('Standardliste geändert.');
        Util::redirect('listen.php');
    }
    if ($action === 'loeschen' && $id > 0) {
        Lists::delete($id);
        Util::flash('Liste gelöscht. Die Empfänger selbst wurden nicht angetastet.');
        Util::redirect('listen.php');
    }
}

$lists  = Lists::all();
$counts = Lists::activeCounts();
?>

<div class="ad-page-head">
    <div>
        <h1>Listen</h1>
        <p class="ad-sub">Verteiler für unterschiedliche Themen oder Zielgruppen.
            Die <a href="marken.php">Marke</a> einer Liste bestimmt, wie Bestätigungs-,
            Begrüßungs- und Abmeldemail ihrer Empfänger aussehen.</p>
    </div>
</div>

<div class="ad-card">
    <h2>Neue Liste</h2>
    <form method="post">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="anlegen">
        <div class="ad-row">
            <div class="ad-field">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required placeholder="z. B. Praxis-Impulse">
            </div>
            <div class="ad-field" style="flex:2 1 320px;">
                <label for="description">Beschreibung <span class="ad-hint">(erscheint im Anmeldeformular)</span></label>
                <input type="text" id="description" name="description" placeholder="Monatliche Tipps rund um E-Mail-Marketing">
            </div>
            <div class="ad-field" style="flex:0;">
                <label>&nbsp;</label>
                <button type="submit" class="ad-btn">Anlegen</button>
            </div>
        </div>
    </form>
</div>

<?php if ($lists === []): ?>
    <div class="ad-empty">Noch keine Liste vorhanden.</div>
<?php else: ?>
    <?php foreach ($lists as $list): ?>
        <div class="ad-card">
            <form method="post">
                <?= Util::csrfField() ?>
                <input type="hidden" name="id" value="<?= (int) $list['id'] ?>">
                <div class="ad-row" style="align-items:flex-end;">
                    <div class="ad-field">
                        <label>Name
                            <?php if ((int) $list['is_default'] === 1): ?>
                                <span class="ad-pill ad-pill-blue">Standard</span>
                            <?php endif; ?>
                        </label>
                        <input type="text" name="name" value="<?= Util::e((string) $list['name']) ?>">
                    </div>
                    <div class="ad-field" style="flex:2 1 320px;">
                        <label>Beschreibung</label>
                        <input type="text" name="description" value="<?= Util::e((string) $list['description']) ?>">
                    </div>
                    <div class="ad-field">
                        <label>Marke <span class="ad-hint">(für die Systemmails)</span></label>
                        <?php /* Danach richten sich Bestätigungs-, Willkommens- und
                                 Abmeldemail dieser Liste – samt Impressum. */ ?>
                        <select name="template_id">
                            <option value="0">Standardvorlage</option>
                            <?php foreach (Templates::brands() as $marke): ?>
                                <?php if ($marke['template'] === null) { continue; } ?>
                                <option value="<?= (int) $marke['template']['id'] ?>"
                                    <?= (int) ($list['template_id'] ?? 0) === (int) $marke['template']['id'] ? 'selected' : '' ?>>
                                    <?= Util::e((string) $marke['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="ad-field" style="flex:0;">
                        <label>Aktive Empfänger</label>
                        <strong style="font-size:20px;color:var(--ad-navy);">
                            <?= Util::num($counts[(int) $list['id']] ?? 0) ?>
                        </strong>
                    </div>
                </div>
                <div class="ad-actions">
                    <button type="submit" name="aktion" value="speichern" class="ad-btn ad-btn-secondary ad-btn-small">Speichern</button>
                    <?php if ((int) $list['is_default'] !== 1): ?>
                        <button type="submit" name="aktion" value="standard" class="ad-btn ad-btn-secondary ad-btn-small">Als Standard festlegen</button>
                        <button type="submit" name="aktion" value="loeschen" class="ad-btn ad-btn-danger ad-btn-small"
                                data-confirm="Liste wirklich löschen? Die Empfänger bleiben erhalten.">Löschen</button>
                    <?php endif; ?>
                    <a class="ad-btn ad-btn-secondary ad-btn-small" href="empfaenger.php?liste=<?= (int) $list['id'] ?>">Empfänger anzeigen</a>
                </div>
            </form>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php';
