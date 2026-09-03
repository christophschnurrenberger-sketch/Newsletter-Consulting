<?php
/**
 * automationen.php – Mailstrecken als Ablauf zusammenziehen.
 *
 * Links die Schritte (warten, senden, prüfen, handeln), in der Mitte der
 * Ablauf per Drag & Drop, rechts die Einstellungen des gewählten Schrittes.
 * Der Inhalt einer Mail wird darunter im gewohnten Baukasten geschrieben.
 */

require_once dirname(__DIR__) . '/lib/bootstrap.php';

/* Vorschau einer Automations-Mail */
if (Util::get('vorschau') === '1') {
    Auth::require('kampagnen');
    $stepId = Util::getInt('schritt');
    Automations::compileStep($stepId);
    $source = Automations::stepAsCampaign($stepId);
    if ($source === null) {
        http_response_code(404);
        exit('Schritt nicht gefunden.');
    }
    $mail = Campaigns::renderFor($source, Renderer::sampleSubscriber(), 'vorschau');
    Util::previewHeaders();
    echo preg_replace('#<img[^>]+track\.php\?o=[^>]*>#i', '', $mail['html']);
    exit;
}

/*
 * Rahmen einer Automations-Mail: dieselbe Mail, nur mit einer Marke an der
 * Stelle des Inhalts. Der Baukasten zeigt damit Kopfzeile und Footer fest an
 * (siehe kampagne.php, dort steht die ausführliche Begründung).
 */
if (Util::get('rahmen') === '1') {
    Auth::require('kampagnen');
    $quelle = Automations::stepAsCampaign(Util::getInt('schritt'));
    if ($quelle === null) {
        http_response_code(404);
        exit('Schritt nicht gefunden.');
    }
    $template = Templates::byId((int) $quelle['template_id']);
    if ($template !== null && Templates::usesBuilder($template)) {
        $template['html'] = Blocks::renderDocument(Templates::blocks($template));
    }
    $html = Renderer::wrap($template, '<div id="nl-inhalt" style="height:1px;line-height:1px;">&nbsp;</div>',
        (string) $quelle['subject'], (string) ($quelle['preheader'] ?? ''));
    $html = Renderer::applyBrand($html, $template, true);
    $html = Renderer::personalize($html, Renderer::sampleSubscriber(), [
        'abmelden_url'     => '#',
        'praeferenzen_url' => '#',
        'webansicht_url'   => '#',
    ], true);
    Util::previewHeaders();
    echo $html;
    exit;
}

/*
 * Aus dem Assistenten: Marke steht fest, Strecke anlegen und hinein.
 * Die Schritte der Strecke erben die Marke, ihre Mails erscheinen also
 * gleich in der richtigen Aufmachung.
 */
if (Util::get('neu') === '1' && Util::get('marke') !== '') {
    Auth::require('kampagnen');
    $vorlageId = Templates::brandTemplateId(Util::get('marke'));
    $neuId     = Automations::create('Neue Strecke', Lists::defaultId() ?: null, $vorlageId);
    Automations::saveFlow($neuId, (string) json_encode(Flow::starter()));
    Util::flash('Strecke angelegt. Ziehen Sie jetzt die Schritte in den Ablauf.');
    Util::redirect('automationen.php?id=' . $neuId);
}

$pageTitle = 'Automationen';
$extraCss  = ['assets/flow.css', 'assets/builder.css'];
$extraJs   = ['assets/flow.js', 'assets/builder.js'];
require __DIR__ . '/partials/header.php';
require __DIR__ . '/partials/builder.php';


/* ------------------------------------------------------------- Aktionen */

if (Util::isPost()) {
    Util::requireCsrf();
    if (!Auth::can('kampagnen')) {
        Util::flash('Dafür fehlt Ihnen die Berechtigung. Fragen Sie eine Administratorin oder einen Administrator.', 'error');
        Util::redirect('automationen.php');
    }
    $action = Util::post('aktion');
    $id     = Util::postInt('id');
    $stepId = Util::postInt('schritt_id');

    // Der Baukasten der Automations-Mail speichert im Hintergrund. Das muss
    // vor allen anderen Zweigen stehen: Sein Formular führt dieselbe
    // Kennung wie der Ablauf mit sich und liefe sonst in "speichern" –
    // der Ablauf würde mit einem leeren Wert überschrieben.
    if (Util::post('autosave') === '1') {
        if ($stepId <= 0) {
            Util::json(['ok' => false, 'fehler' => 'Kein Schritt gewählt.'], 400);
        }
        $action = 'schritt_speichern';
    }

    if ($action === 'anlegen') {
        $trigger = Util::post('trigger_type');
        $newId = Automations::create(
            Util::post('name') ?: 'Neue Strecke',
            Util::postInt('list_id') ?: null,
            null,
            $trigger,
            Util::postInt('trigger_days') ?: null
        );
        Automations::saveFlow($newId, (string) json_encode(Flow::starter()));
        Util::flash('Strecke angelegt. Ziehen Sie jetzt die Schritte in den Ablauf.');
        Util::redirect('automationen.php?id=' . $newId);
    }

    if ($action === 'speichern' && $id > 0) {
        $trigger = Util::post('trigger_type');
        Automations::save($id, [
            'name'         => Util::post('name'),
            'list_id'      => Util::postInt('list_id') ?: null,
            'template_id'  => Util::postInt('template_id') ?: null,
            'status'       => Util::post('status') === Automations::ACTIVE ? Automations::ACTIVE : Automations::PAUSED,
            'trigger_type' => $trigger,
            'trigger_days' => $trigger === Automations::TRIGGER_INACTIVE
                ? max(1, Util::postInt('trigger_days') ?: Automations::INACTIVE_DAYS) : null,
        ]);
        Automations::saveFlow($id, Util::postRaw('flow_json'));

        /*
         * „Inhalt schreiben" an einem frisch eingesetzten Mailschritt: Die
         * Kennung des Schrittes entsteht erst hier beim Speichern. Deshalb
         * merkt sich der Baukasten den Knoten, und wir leiten anschließend
         * gleich in dessen Editor – statt den Anwender raten zu lassen.
         */
        $weiter = Util::post('weiter_zu');
        if ($weiter !== '') {
            $frisch = Automations::byId($id);
            $knoten = $frisch === null ? null
                : (Flow::index(Automations::flow($frisch))['nodes'][$weiter] ?? null);
            if ($knoten !== null && (int) ($knoten['step_id'] ?? 0) > 0) {
                Util::flash('Ablauf gespeichert. Jetzt der Inhalt dieser Mail.');
                Util::redirect('automationen.php?id=' . $id . '&schritt=' . (int) $knoten['step_id']);
            }
        }

        Util::flash('Ablauf gespeichert.');
        Util::redirect('automationen.php?id=' . $id);
    }

    if ($action === 'loeschen' && $id > 0) {
        Automations::delete($id);
        Util::flash('Strecke gelöscht.');
        Util::redirect('automationen.php');
    }

    /*
     * Inhalt eines vorhandenen Newsletters in diesen Schritt übernehmen.
     * Kopiert wird – der Newsletter selbst bleibt unangetastet, sonst würde
     * eine Änderung an der Strecke später den Newsletter mitverändern.
     */
    if ($action === 'schritt_uebernehmen' && $stepId > 0) {
        $quelle = Campaigns::byId(Util::postInt('von_kampagne'));
        $step   = Automations::step($stepId);
        if ($quelle === null || $step === null) {
            Util::flash('Diesen Newsletter gibt es nicht (mehr).', 'error');
            Util::redirect('automationen.php?id=' . $id . '&schritt=' . $stepId);
        }

        $felder = [
            'template_id' => (int) $quelle['template_id'] ?: null,
            'editor_mode' => (string) $quelle['editor_mode'],
            'blocks_json' => (string) $quelle['blocks_json'],
            'content_html' => (string) $quelle['content_html'],
            'content_text' => (string) $quelle['content_text'],
        ];
        // Einen schon geschriebenen Betreff nicht überbügeln
        if (trim((string) $step['subject']) === '') {
            $felder['subject'] = (string) $quelle['subject'];
        }
        Automations::saveStep($stepId, $felder);
        Util::flash('Inhalt aus „' . Util::e((string) $quelle['name']) . '" übernommen. '
            . 'Änderungen hier wirken sich nicht auf den Newsletter aus.');
        Util::redirect('automationen.php?id=' . $id . '&schritt=' . $stepId);
    }

    if ($action === 'schritt_speichern' && $stepId > 0) {
        $vorher = Automations::step($stepId);
        $felder = [
            'subject'      => Util::post('subject'),
            'template_id'  => Util::postInt('template_id') ?: null,
            'track_opens'  => Util::post('track_opens') === '1' ? 1 : 0,
            'track_clicks' => Util::post('track_clicks') === '1' ? 1 : 0,
        ];
        if (Util::post('editor_mode') === 'blocks') {
            $felder['editor_mode'] = 'blocks';
            $felder['blocks_json'] = Util::postRaw('blocks_json');

            // Design gewechselt? Dann auch den Inhalt umstellen – wie beim
            // Newsletter. Von Hand gesetzte Farben bleiben dabei erhalten.
            $altId = (int) ($vorher['template_id'] ?? 0);
            $neuId = (int) ($felder['template_id'] ?? 0);
            if ($neuId !== $altId && $felder['blocks_json'] !== '') {
                $stand = Blocks::switchDesign(
                    Blocks::parse($felder['blocks_json']),
                    Templates::byId($altId),
                    Templates::byId($neuId)
                );
                $felder['blocks_json'] = (string) json_encode($stand,
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        } else {
            $felder['editor_mode']  = 'html';
            $felder['content_html'] = Util::postRaw('content_html');
            $felder['content_text'] = Util::postRaw('content_text');
        }
        Automations::saveStep($stepId, $felder);
        if (Util::post('autosave') === '1') {
            Util::json(['ok' => true, 'zeit' => date('H:i')]);
        }
        Util::flash('Inhalt gespeichert.');
        Util::redirect('automationen.php?id=' . $id . '&schritt=' . $stepId);
    }

    if ($action === 'schritt_modus' && $stepId > 0) {
        $step = Automations::step($stepId);
        if ($step !== null && Util::post('ziel') === 'blocks') {
            $vorhanden = trim((string) $step['blocks_json']);
            if ($vorhanden === '') {
                $start = Blocks::starterCampaign(Templates::byId((int) $step['template_id']));
                $inhalt = trim((string) $step['content_html']);
                if ($inhalt !== '') {
                    $start['blocks'] = [Blocks::block('html', ['html' => $inhalt])];
                }
                $vorhanden = (string) json_encode($start, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
            Automations::saveStep($stepId, ['editor_mode' => 'blocks', 'blocks_json' => $vorhanden]);
        } elseif ($step !== null) {
            Automations::saveStep($stepId, ['editor_mode' => 'html']);
        }
        Util::redirect('automationen.php?id=' . $id . '&schritt=' . $stepId);
    }

    if ($action === 'testmail' && $stepId > 0) {
        try {
            Automations::compileStep($stepId);
            $source = Automations::stepAsCampaign($stepId);
            $email  = Util::normalizeEmail(Util::post('test_email'));
            if ($source === null || !Util::isEmail($email)) {
                throw new RuntimeException('Bitte geben Sie eine gültige Testadresse an.');
            }
            $sample = Subscribers::byEmail($email) ?? Renderer::sampleSubscriber($email);
            $sample['email'] = $email;
            $mail = Campaigns::renderFor($source, $sample, 'test-' . Util::token(6));
            Mailer::send([
                'to'      => $email,
                'subject' => '[TEST] ' . $mail['subject'],
                'html'    => $mail['html'],
                'text'    => $mail['text'],
                'headers' => ['Auto-Submitted' => 'auto-generated', 'Precedence' => 'bulk'],
            ]);
            Util::flash('Testmail an <strong>' . Util::e($email) . '</strong> verschickt.');
        } catch (Throwable $e) {
            Util::flash('Testversand fehlgeschlagen: ' . Util::e($e->getMessage()), 'error');
        }
        Util::redirect('automationen.php?id=' . $id . '&schritt=' . $stepId);
    }
}

/* -------------------------------------------------------------- Ansicht */

$automations = Automations::all();
$current     = Automations::byId(Util::getInt('id'));
if ($current === null && $automations !== []) {
    $current = $automations[0];
}

/*
 * „Neue Strecke“ ist ein eigener Reiter, kein Formular über dem Ablauf.
 * Wer eine Strecke ansieht, will sie bearbeiten – und nicht zuerst an
 * einem Anlegen-Kasten vorbeiscrollen.
 */
$neu = Util::get('anlegen') !== '' || $automations === [];
if ($neu) {
    $current = null;
}

$flow        = $current !== null ? Automations::flow($current) : ['nodes' => []];
$currentStep = null;
if ($current !== null && Util::getInt('schritt') > 0) {
    $step = Automations::step(Util::getInt('schritt'));
    if ($step !== null && (int) $step['automation_id'] === (int) $current['id']) {
        $currentStep = $step;
    }
}

$listen = array_map(static fn($l) => ['id' => (int) $l['id'], 'name' => (string) $l['name']], Lists::all());
$betreffe = [];
if ($current !== null) {
    foreach (Automations::steps((int) $current['id']) as $step) {
        $betreffe[(string) $step['id']] = (string) $step['subject'];
    }
}
?>

<div class="ad-page-head">
    <div>
        <h1>Automationen</h1>
        <p class="ad-sub">Mailstrecken, die von selbst laufen – nach der Anmeldung, am Geburtstag oder bei längerer Inaktivität</p>
    </div>
    <?php if ($current !== null): ?>
        <?php /* Speichern gehört dorthin, wo man es sucht: oben, immer sichtbar. */ ?>
        <button type="submit" form="strecke" name="aktion" value="speichern" class="ad-btn">Ablauf speichern</button>
    <?php endif; ?>
</div>

<?php /*
 * Die Strecken stehen als Reiter oben – so sieht man sofort, was es gibt
 * und welche gerade läuft. Vorher war das eine Reihe roter Knöpfe, die
 * aussahen, als würde ein Klick etwas auslösen.
 */ ?>
<nav class="ad-reiter" aria-label="Strecken">
    <?php foreach ($automations as $automation):
        $aktiv = $current !== null && (int) $current['id'] === (int) $automation['id']; ?>
        <a class="ad-reiter-tab <?= $aktiv ? 'is-aktiv' : '' ?>"
           href="automationen.php?id=<?= (int) $automation['id'] ?>">
            <?= Util::e((string) $automation['name']) ?>
            <span class="ad-pill <?= $automation['status'] === Automations::ACTIVE ? 'ad-pill-green' : 'ad-pill-grey' ?>">
                <?= $automation['status'] === Automations::ACTIVE ? 'aktiv' : 'pausiert' ?>
            </span>
        </a>
    <?php endforeach; ?>
    <a class="ad-reiter-tab <?= $neu ? 'is-aktiv' : '' ?>" href="automationen.php?anlegen=1">+ Neue Strecke</a>
</nav>

<?php if ($neu): ?>
    <div class="ad-card" id="neue-strecke">
        <h2>Neue Strecke anlegen</h2>
        <p class="ad-hint" style="margin-bottom:14px;">Wählen Sie zuerst, <strong>wann</strong> die Strecke
            startet. Die einzelnen Schritte (warten, Mail, Bedingung) legen Sie gleich danach fest.</p>
        <form method="post" data-strecke-form>
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="anlegen">
            <div class="ad-row">
                <div class="ad-field">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="z. B. Geburtstagsgruß" autofocus>
                </div>
                <div class="ad-field">
                    <label for="trigger_type">Auslöser</label>
                    <select id="trigger_type" name="trigger_type" data-trigger-wahl>
                        <?php foreach (Automations::TRIGGERS as $wert => $label): ?>
                            <option value="<?= Util::e($wert) ?>"><?= Util::e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="ad-row" style="align-items:flex-end;">
                <div class="ad-field">
                    <label for="list_id">Nur für diese Liste</label>
                    <select id="list_id" name="list_id">
                        <option value="0">Alle Listen</option>
                        <?php foreach (Lists::all() as $list): ?>
                            <option value="<?= (int) $list['id'] ?>"><?= Util::e((string) $list['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="ad-field" data-nur-inaktiv hidden>
                    <label for="trigger_days">Inaktiv seit … Tagen</label>
                    <input type="number" id="trigger_days" name="trigger_days" min="1" max="3650"
                           value="<?= Automations::INACTIVE_DAYS ?>">
                </div>
                <div class="ad-field" style="flex:0;">
                    <label>&nbsp;</label>
                    <div class="ad-actions-inline">
                        <button type="submit" class="ad-btn">Anlegen</button>
                        <?php if ($automations !== []): ?>
                            <a class="ad-btn ad-btn-secondary" href="automationen.php">Abbrechen</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <p class="ad-hint" data-trigger-hinweis style="margin:2px 0 0;"></p>
        </form>
    </div>
<?php endif; ?>

<?php if ($current !== null):
    $stats    = Automations::stats((int) $current['id']);
    $probleme = Flow::problems($flow); ?>

    <div class="ad-grid">
        <div class="ad-stat">
            <div class="ad-stat-label">Teilnehmer</div>
            <div class="ad-stat-value"><?= Util::num($stats['subscribers']) ?></div>
            <div class="ad-stat-note"><?= Util::num($stats['pending']) ?> gerade unterwegs</div>
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

    <?php if ($probleme !== []): ?>
        <div class="ad-flash ad-flash-warning">
            <strong>Hinweise zum Ablauf:</strong>
            <ul style="margin:8px 0 0 18px;padding:0;">
                <?php foreach ($probleme as $hinweis): ?><li><?= Util::e($hinweis) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="ad-card">
        <form method="post" id="strecke" data-warn-unsaved>
            <?= Util::csrfField() ?>
            <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">

            <div class="ad-row" style="align-items:flex-end;">
                <div class="ad-field">
                    <label for="a_name">Name</label>
                    <input type="text" id="a_name" name="name" value="<?= Util::e((string) $current['name']) ?>">
                </div>
                <div class="ad-field">
                    <label for="a_trigger">Auslöser</label>
                    <select id="a_trigger" name="trigger_type" data-trigger-wahl>
                        <?php foreach (Automations::TRIGGERS as $wert => $label): ?>
                            <option value="<?= Util::e($wert) ?>"
                                <?= (string) $current['trigger_type'] === $wert ? 'selected' : '' ?>>
                                <?= Util::e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="ad-field" data-nur-inaktiv <?= $current['trigger_type'] === Automations::TRIGGER_INACTIVE ? '' : 'hidden' ?>>
                    <label for="a_days">Inaktiv seit … Tagen</label>
                    <input type="number" id="a_days" name="trigger_days" min="1" max="3650"
                           value="<?= (int) ($current['trigger_days'] ?: Automations::INACTIVE_DAYS) ?>">
                </div>
                <div class="ad-field">
                    <label for="a_list">Nur für diese Liste</label>
                    <select id="a_list" name="list_id">
                        <option value="0">alle Listen</option>
                        <?php foreach (Lists::all() as $list): ?>
                            <option value="<?= (int) $list['id'] ?>"
                                <?= (int) $current['list_id'] === (int) $list['id'] ? 'selected' : '' ?>>
                                <?= Util::e((string) $list['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="ad-field">
                    <?php /* Neue Schritte erben diese Marke; vorhandene behalten ihre. */ ?>
                    <label for="a_marke">Marke</label>
                    <select id="a_marke" name="template_id">
                        <?php foreach (Templates::brands() as $marke): ?>
                            <?php if ($marke['template'] === null) { continue; } ?>
                            <option value="<?= (int) $marke['template']['id'] ?>"
                                <?= (int) ($current['template_id'] ?? 0) === (int) $marke['template']['id'] ? 'selected' : '' ?>>
                                <?= Util::e((string) $marke['name']) ?>
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
                    <?php /* Gespeichert wird oben im Seitenkopf und unten am Ablauf – hier
                             stehen nur die selteneren Aktionen. */ ?>
                    <details class="ad-menue">
                        <summary class="ad-btn ad-btn-secondary" aria-label="Weitere Aktionen"
                                 title="Weitere Aktionen">…</summary>
                        <div class="ad-menue-liste">
                            <button type="submit" name="aktion" value="loeschen" class="ist-gefahr"
                                    data-confirm="Strecke mit allen Schritten löschen?">Strecke löschen</button>
                        </div>
                    </details>
                </div>
            </div>

            <p class="ad-hint" style="margin-bottom:14px;">Ziehen Sie Schritte in den Ablauf. Wartezeiten zählen
                jeweils ab dem vorherigen Schritt. Nach einer Bedingung laufen beide Zweige wieder zusammen.</p>

            <div class="fl" data-flow
                 data-lists='<?= Util::e((string) json_encode($listen, JSON_UNESCAPED_UNICODE)) ?>'
                 data-steps='<?= Util::e((string) json_encode($betreffe, JSON_UNESCAPED_UNICODE)) ?>'
                 data-edit-url="automationen.php?id=<?= (int) $current['id'] ?>&amp;schritt="
                 data-labels='<?= Util::e((string) json_encode([
                     'types'      => Flow::TYPES,
                     'conditions' => Flow::CONDITIONS,
                     'actions'    => Flow::ACTIONS,
                     'units'      => Flow::UNITS,
                     'units_one'  => Flow::UNITS_ONE,
                     'nodes'      => ['warten' => 'Warten', 'mail' => 'E-Mail senden',
                                      'bedingung' => 'Wenn … dann', 'aktion' => 'Aktion',
                                      'ende' => 'Strecke beenden'],
                 ], JSON_UNESCAPED_UNICODE)) ?>'>

                <aside class="fl-palette">
                    <h3>Schritte</h3>
                    <p class="fl-hint">Ziehen Sie einen Schritt in den Ablauf – oder klicken Sie ihn an,
                        dann wird er unten angehängt.</p>
                    <?php foreach ([
                        'warten'    => ['⏱', 'Warten'],
                        'mail'      => ['✉', 'E-Mail senden'],
                        'bedingung' => ['?', 'Wenn … dann'],
                        'aktion'    => ['⚙', 'Aktion'],
                        'ende'      => ['■', 'Strecke beenden'],
                    ] as $typ => $info): ?>
                        <button type="button" class="fl-chip" data-addnode="<?= Util::e($typ) ?>">
                            <span class="fl-chip-icon" aria-hidden="true"><?= $info[0] ?></span>
                            <?= Util::e($info[1]) ?>
                        </button>
                    <?php endforeach; ?>
                </aside>

                <div class="fl-stage">
                    <div class="fl-trigger">
                        <strong>Auslöser</strong>
                        <?php
                        $tt = (string) $current['trigger_type'];
                        if ($tt === Automations::TRIGGER_BIRTHDAY) {
                            echo 'Am Geburtstag';
                        } elseif ($tt === Automations::TRIGGER_INACTIVE) {
                            echo 'Inaktiv seit ' . (int) ($current['trigger_days'] ?: Automations::INACTIVE_DAYS) . ' Tagen';
                        } else {
                            echo 'Anmeldung bestätigt';
                        }
                        echo (int) $current['list_id'] > 0
                            ? ' – Liste „' . Util::e(Lists::name((int) $current['list_id'])) . '“'
                            : ' – alle Listen';
                        ?>
                    </div>
                    <div class="fl-canvas" data-canvas></div>
                </div>

                <aside class="fl-inspector" data-inspector></aside>

                <textarea name="flow_json" data-flow-field hidden><?php
                    echo Util::e((string) json_encode($flow, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                ?></textarea>
            </div>

            <div class="ad-actions">
                <button type="submit" name="aktion" value="speichern" class="ad-btn">Ablauf speichern</button>
                <?php if ($current['status'] !== Automations::ACTIVE): ?>
                    <span class="ad-hint">Die Strecke ist pausiert – zum Starten oben auf „Aktiv“ stellen und speichern.</span>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- ------------------------------------------------ Inhalt einer Mail -->
    <?php if ($currentStep !== null):
        $stepStats  = Automations::stepStats((int) $currentStep['id']);
        $stepBuilder = Automations::stepUsesBuilder($currentStep); ?>

        <div class="ad-card">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
                <div>
                    <h2 style="margin:0;">Inhalt der E-Mail</h2>
                    <p class="ad-hint" style="margin:4px 0 0;">
                        <?= Util::num($stepStats['sent']) ?> versendet ·
                        <?= Util::num($stepStats['opens']) ?> Öffnungen ·
                        <?= Util::num($stepStats['clicks']) ?> Klicks
                    </p>
                </div>
                <div class="ad-actions-inline">
                    <a class="ad-btn ad-btn-secondary ad-btn-small" target="_blank" rel="noopener"
                       href="automationen.php?vorschau=1&amp;schritt=<?= (int) $currentStep['id'] ?>">Vorschau</a>
                    <a class="ad-btn ad-btn-secondary ad-btn-small" href="automationen.php?id=<?= (int) $current['id'] ?>">Schließen</a>
                </div>
            </div>

            <?php
            /*
             * Eine Strecke wiederholt oft, was schon einmal geschrieben wurde.
             * Statt es abzutippen, lässt sich der Inhalt eines vorhandenen
             * Newsletters herüberholen – als Kopie, damit das eine das andere
             * später nicht verändert.
             */
            $vorhandene = Campaigns::all();
            ?>
            <?php if ($vorhandene !== []): ?>
                <form method="post" class="ad-uebernehmen">
                    <?= Util::csrfField() ?>
                    <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
                    <input type="hidden" name="schritt_id" value="<?= (int) $currentStep['id'] ?>">
                    <label for="von_kampagne">Inhalt aus einem vorhandenen Newsletter übernehmen</label>
                    <div class="ad-uebernehmen-reihe">
                        <select id="von_kampagne" name="von_kampagne" class="ad-select">
                            <?php foreach ($vorhandene as $k): ?>
                                <option value="<?= (int) $k['id'] ?>">
                                    <?= Util::e((string) $k['name']) ?>
                                    <?= trim((string) $k['subject']) !== ''
                                        ? ' – ' . Util::e(Util::shorten((string) $k['subject'], 45)) : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="aktion" value="schritt_uebernehmen"
                                class="ad-btn ad-btn-secondary"
                                data-confirm="Der jetzige Inhalt dieses Schrittes wird dabei ersetzt. Fortfahren?">
                            Übernehmen
                        </button>
                    </div>
                    <p class="ad-hint">Es wird kopiert – der Newsletter selbst bleibt, wie er ist.</p>
                </form>
            <?php endif; ?>

            <form method="post" data-warn-unsaved style="margin-top:14px;">
                <?= Util::csrfField() ?>
                <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
                <input type="hidden" name="schritt_id" value="<?= (int) $currentStep['id'] ?>">
                <input type="hidden" name="ziel" value="<?= $stepBuilder ? 'html' : 'blocks' ?>">

                <div class="ad-row">
                    <div class="ad-field" style="flex:2 1 320px;">
                        <label for="subject">Betreff</label>
                        <input type="text" id="subject" name="subject" value="<?= Util::e((string) $currentStep['subject']) ?>"
                               placeholder="Ohne Betreff wird dieser Schritt übersprungen">
                    </div>
                    <div class="ad-field">
                        <label for="template_id">Design-Vorlage</label>
                        <select id="template_id" name="template_id">
                            <?php foreach (Templates::all() as $template): ?>
                                <option value="<?= (int) $template['id'] ?>"
                                    <?= (int) $currentStep['template_id'] === (int) $template['id'] ? 'selected' : '' ?>>
                                    <?= Util::e((string) $template['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="ad-hint">Umstellen wechselt auch Schriften und Farben im Inhalt.</p>
                    </div>
                    <div class="ad-field" style="flex:0;">
                        <label>&nbsp;</label>
                        <button type="submit" name="aktion" value="schritt_modus" class="ad-btn ad-btn-secondary">
                            <?= $stepBuilder ? 'Zu HTML wechseln' : 'Im Baukasten gestalten' ?>
                        </button>
                    </div>
                </div>

                <?php if ($stepBuilder): ?>
                    <input type="hidden" name="editor_mode" value="blocks">
                    <?php builder_ui(Automations::stepBlocks($currentStep), 'campaign', 'blocks_json',
                        'automationen.php?rahmen=1&schritt=' . (int) $currentStep['id']); ?>
                <?php else: ?>
                    <input type="hidden" name="editor_mode" value="html">
                    <div class="ad-field">
                        <label for="content_html">Inhalt (HTML)</label>
                        <textarea id="content_html" name="content_html" rows="16" class="ad-code"><?= Util::e((string) $currentStep['content_html']) ?></textarea>
                    </div>
                    <div class="ad-field">
                        <label for="content_text">Textfassung <span class="ad-hint">(leer = automatisch)</span></label>
                        <textarea id="content_text" name="content_text" rows="4" class="ad-code"><?= Util::e((string) $currentStep['content_text']) ?></textarea>
                    </div>
                <?php endif; ?>

                <label class="ad-check">
                    <input type="checkbox" name="track_opens" value="1" <?= (int) $currentStep['track_opens'] === 1 ? 'checked' : '' ?>>
                    <span>Öffnungen messen <em class="ad-hint">(nötig für die Bedingung „hat geöffnet“)</em></span>
                </label>
                <label class="ad-check">
                    <input type="checkbox" name="track_clicks" value="1" <?= (int) $currentStep['track_clicks'] === 1 ? 'checked' : '' ?>>
                    <span>Klicks messen <em class="ad-hint">(nötig für die Bedingung „hat geklickt“)</em></span>
                </label>

                <div class="ad-actions">
                    <button type="submit" name="aktion" value="schritt_speichern" class="ad-btn">Inhalt speichern</button>
                    <input type="email" name="test_email" class="ad-input" style="width:220px;"
                           value="<?= Util::e((string) ($currentUser['email'] ?? '')) ?>" placeholder="Testadresse">
                    <button type="submit" name="aktion" value="testmail" class="ad-btn ad-btn-secondary">Testmail senden</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- ------------------------------------------------------ Teilnehmer -->
    <?php
    $laeufe = DB::all(
        "SELECT r.*, s.email FROM automation_runs r
         JOIN subscribers s ON s.id = r.subscriber_id
         WHERE r.automation_id = ? ORDER BY r.id DESC LIMIT 15",
        [(int) $current['id']]
    );
    if ($laeufe !== []):
        $index = Flow::index($flow); ?>
        <div class="ad-card">
            <h2>Wer gerade in der Strecke ist</h2>
            <div class="ad-table-wrap" style="margin-bottom:0;">
                <table class="ad-table">
                    <thead><tr><th>Empfänger</th><th>Status</th><th>Nächster Schritt</th><th>Fällig</th></tr></thead>
                    <tbody>
                    <?php foreach ($laeufe as $lauf):
                        $node = $index['nodes'][(string) $lauf['node_id']] ?? null; ?>
                        <tr>
                            <td class="ad-mono"><?= Util::e((string) $lauf['email']) ?></td>
                            <td>
                                <span class="ad-pill <?= $lauf['status'] === 'pending' ? 'ad-pill-blue'
                                    : ($lauf['status'] === 'done' ? 'ad-pill-green' : 'ad-pill-grey') ?>">
                                    <?= Util::e(match ((string) $lauf['status']) {
                                        'pending'   => 'unterwegs',
                                        'done'      => 'abgeschlossen',
                                        'cancelled' => 'abgebrochen',
                                        default     => (string) $lauf['status'],
                                    }) ?>
                                </span>
                            </td>
                            <td class="ad-hint"><?= Util::e($node !== null ? Flow::describe($node) : '—') ?></td>
                            <td><?= Util::e(Util::dt((string) $lauf['due_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<script>
/* Auslöserwahl: das Feld „Inaktiv seit … Tagen" nur zeigen, wenn es passt –
   und im Anlegen-Formular einen kurzen Hinweis einblenden. */
(function () {
    var texte = {
        confirm:  'Startet, sobald jemand seine Anmeldung bestätigt – ideal für eine Willkommensserie.',
        birthday: 'Prüft täglich, wer Geburtstag hat, und schickt automatisch den Gruß. Dafür braucht das Mitglied ein Geburtsdatum.',
        inactive: 'Prüft täglich, wer lange nichts mehr geöffnet hat, und startet die Rückhol-Strecke.'
    };
    document.querySelectorAll('[data-trigger-wahl]').forEach(function (wahl) {
        var form = wahl.closest('form') || document;
        function auffrischen() {
            var inaktiv = form.querySelector('[data-nur-inaktiv]');
            if (inaktiv) { inaktiv.hidden = (wahl.value !== 'inactive'); }
            var hinweis = form.querySelector('[data-trigger-hinweis]');
            if (hinweis) { hinweis.textContent = texte[wahl.value] || ''; }
        }
        wahl.addEventListener('change', auffrischen);
        auffrischen();
    });
})();
</script>

<?php require __DIR__ . '/partials/footer.php';
