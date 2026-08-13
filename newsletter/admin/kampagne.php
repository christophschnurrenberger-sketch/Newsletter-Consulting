<?php
/**
 * kampagne.php – Newsletter schreiben, prüfen, testen und versenden.
 *
 * Sonderfall Vorschau: ?id=X&vorschau=1 liefert nur die fertige Mail
 * (wird im Editor in einem Rahmen angezeigt) – ohne Verwaltungslayout.
 */

require_once dirname(__DIR__) . '/lib/bootstrap.php';

/* ----------------------------------------------------------- Vorschau */

if (Util::get('vorschau') === '1') {
    Auth::require('lesen');
    $id       = Util::getInt('id');
    $campaign = Campaigns::byId($id);
    if ($campaign === null) {
        http_response_code(404);
        exit('Newsletter nicht gefunden.');
    }
    Campaigns::compile($id);
    $campaign = Campaigns::byId($id);

    $sample = Renderer::sampleSubscriber();
    $mail   = Campaigns::renderFor($campaign, $sample, 'vorschau');

    header('Content-Type: text/html; charset=utf-8');
    // Zählpixel in der Vorschau entfernen
    echo preg_replace('#<img[^>]+track\.php\?o=[^>]*>#i', '', $mail['html']);
    exit;
}

/* ------------------------------------------------------------ Anlegen */

/*
 * Erst das Design wählen, dann schreiben – wie man es von den großen
 * Anbietern kennt. Wer nur eine Vorlage hat, soll aber nicht jedes Mal
 * eine Auswahl mit einem einzigen Feld wegklicken müssen: Dann geht es
 * direkt weiter.
 */
if (Util::get('neu') === '1') {
    Auth::require('kampagnen');
    $vorlagen = Templates::all();

    $gewaehlt = Util::get('vorlage');
    if ($gewaehlt === '' && count($vorlagen) > 1) {
        $zeigeAuswahl = true;                 // weiter unten, nach dem Seitenkopf
    } else {
        $vorlageId = $gewaehlt === 'leer' ? 0 : (int) ($gewaehlt !== '' ? $gewaehlt : 0);
        $newId = Campaigns::create('Newsletter vom ' . date('d.m.Y'),
            $vorlageId > 0 ? $vorlageId : null, $gewaehlt === 'leer');
        Util::flash('Neuer Newsletter angelegt. Betreff und Inhalt können Sie jetzt schreiben.');
        Util::redirect('kampagne.php?id=' . $newId);
    }
}

$pageTitle = 'Newsletter bearbeiten';
$extraCss  = ['assets/builder.css'];
$extraJs   = ['assets/builder.js'];
require __DIR__ . '/partials/header.php';
require __DIR__ . '/partials/builder.php';

/* --------------------------------------------------- Design aussuchen */

if (!empty($zeigeAuswahl)) {
    $pageTitle = 'Neuer Newsletter';
    ?>
    <div class="ad-page-head">
        <div>
            <h1>Womit soll der Newsletter aussehen?</h1>
            <p class="ad-sub">Das Design lässt sich später jederzeit wechseln – der Inhalt bleibt erhalten.</p>
        </div>
        <a class="ad-btn ad-btn-secondary" href="kampagnen.php">Abbrechen</a>
    </div>

    <div class="ad-designwahl">
        <?php foreach ($vorlagen as $vorlage): ?>
            <a class="ad-design" href="kampagne.php?neu=1&amp;vorlage=<?= (int) $vorlage['id'] ?>">
                <span class="ad-design-bild">
                    <iframe src="vorlagen.php?id=<?= (int) $vorlage['id'] ?>&amp;vorschau=1"
                            title="Vorschau" loading="lazy" scrolling="no" tabindex="-1"></iframe>
                </span>
                <span class="ad-design-fuss">
                    <strong><?= Util::e((string) $vorlage['name']) ?></strong>
                    <?php if ((int) $vorlage['is_default'] === 1): ?>
                        <span class="ad-pill ad-pill-blue">Standard</span>
                    <?php endif; ?>
                    <?php $marke = Templates::brand($vorlage); ?>
                    <?php if (Templates::hasOwnBrand($vorlage)): ?>
                        <em><?= Util::e((string) $marke['brand_name']) ?></em>
                    <?php endif; ?>
                </span>
            </a>
        <?php endforeach; ?>

        <a class="ad-design ad-design-leer" href="kampagne.php?neu=1&amp;vorlage=leer">
            <span class="ad-design-bild"><span class="ad-design-nix">leer</span></span>
            <span class="ad-design-fuss">
                <strong>Ohne Beispieltext</strong>
                <em>Standardvorlage, aber eine leere Fläche</em>
            </span>
        </a>
    </div>

    <p class="ad-hint">Ein Design bestimmt Kopfzeile, Footer, Schriften und Farben.
        Neue Designs legen Sie unter <a href="vorlagen.php">Vorlagen</a> an.</p>
    <?php
    require __DIR__ . '/partials/footer.php';
    exit;
}

$id       = Util::isPost() ? Util::postInt('id') : Util::getInt('id');
$campaign = Campaigns::byId($id);
if ($campaign === null) {
    Util::flash('Dieser Newsletter existiert nicht (mehr).', 'error');
    Util::redirect('kampagnen.php');
}

$errors = [];

/* ------------------------------------------------------------ Aktionen */

if (Util::isPost()) {
    Util::requireCsrf();
    if (!Auth::can('kampagnen')) {
        Util::flash('Dafür fehlt Ihnen die Berechtigung. Fragen Sie eine Administratorin oder einen Administrator.', 'error');
        Util::redirect('kampagnen.php');
    }
    $action   = Util::post('aktion');
    // Inhalte lassen sich nur ändern, solange nichts unterwegs ist –
    // sonst bekämen die restlichen Empfänger eine andere Fassung.
    $editable = in_array($campaign['status'], [Campaigns::DRAFT, Campaigns::SCHEDULED], true);

    // Inhalte speichern (bei laufendem Versand gesperrt)
    if (in_array($action, ['speichern', 'test', 'senden', 'planen'], true)) {
        if (!$editable) {
            $errors[] = 'Ein laufender oder abgeschlossener Versand kann nicht mehr bearbeitet werden.';
        } else {
            $felder = [
                'name'           => Util::post('name'),
                'subject'        => Util::post('subject'),
                'preheader'      => Util::post('preheader'),
                'from_name'      => Util::post('from_name'),
                'from_email'     => Util::normalizeEmail(Util::post('from_email')),
                'reply_to'       => Util::normalizeEmail(Util::post('reply_to')),
                'template_id'    => Util::postInt('template_id') ?: null,
                'list_id'        => Util::postInt('list_id') ?: null,
                'track_opens'    => Util::post('track_opens') === '1' ? 1 : 0,
                'track_clicks'   => Util::post('track_clicks') === '1' ? 1 : 0,
                'archive_public' => Util::post('archive_public') === '1' ? 1 : 0,
            ];

            // Im Baukasten entstehen HTML und Text aus den Bausteinen,
            // im HTML-Modus schreibt die Redaktion direkt in die Felder.
            if (Util::post('editor_mode') === 'blocks') {
                $felder['editor_mode'] = 'blocks';
                $felder['blocks_json'] = Util::postRaw('blocks_json');

                /*
                 * Design gewechselt? Dann soll auch der Inhalt anders aussehen.
                 * Vorher kam nur ein neuer Rahmen um unveränderte Schriften und
                 * Farben – ein halber Wechsel, der niemandem hilft. Von Hand
                 * gesetzte Farben bleiben erhalten (siehe Blocks::switchDesign).
                 */
                $vorherId = (int) $campaign['template_id'];
                $jetztId  = (int) ($felder['template_id'] ?? 0);
                if ($jetztId !== $vorherId && $felder['blocks_json'] !== '') {
                    $stand = Blocks::switchDesign(
                        Blocks::parse($felder['blocks_json']),
                        Templates::byId($vorherId),
                        Templates::byId($jetztId)
                    );
                    $felder['blocks_json'] = (string) json_encode($stand,
                        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    $designGewechselt = true;
                }
            } else {
                $felder['editor_mode'] = 'html';
                $felder['content_html'] = Util::postRaw('content_html');
                $felder['content_text'] = Util::postRaw('content_text');
            }
            Campaigns::save($id, $felder);
            Campaigns::compile($id);
            $campaign = Campaigns::byId($id);
        }
    }

    // Der Baukasten speichert im Hintergrund – Antwort als JSON.
    if (Util::post('autosave') === '1') {
        Util::json($errors === []
            ? ['ok' => true, 'zeit' => date('H:i')]
            : ['ok' => false, 'fehler' => implode(' ', $errors)], $errors === [] ? 200 : 409);
    }

    if ($action === 'speichern' && $errors === []) {
        Util::flash(!empty($designGewechselt)
            ? 'Gespeichert – der Newsletter steht jetzt im Design der gewählten Vorlage.'
            : 'Gespeichert.');
        Util::redirect('kampagne.php?id=' . $id);
    }

    if ($action === 'test' && $errors === []) {
        try {
            Campaigns::sendTest($id, Util::post('test_email'));
            Util::flash('Testmail an <strong>' . Util::e(Util::post('test_email')) . '</strong> verschickt.');
            Util::redirect('kampagne.php?id=' . $id);
        } catch (Throwable $e) {
            $errors[] = 'Testversand fehlgeschlagen: ' . $e->getMessage();
        }
    }

    if ($action === 'senden' && $errors === []) {
        try {
            $count = Campaigns::start($id);
            Util::flash('Versand gestartet: <strong>' . Util::num($count) . '</strong> Empfänger stehen in der '
                . 'Warteschlange. Der Cron-Job verschickt sie portionsweise.');
            Util::redirect('versand.php');
        } catch (Throwable $e) {
            $errors[] = $e->getMessage();
        }
    }

    if ($action === 'planen' && $errors === []) {
        $when = Util::post('scheduled_at');
        $ts   = $when !== '' ? strtotime(str_replace('T', ' ', $when)) : 0;
        if ($ts <= time()) {
            $errors[] = 'Bitte wählen Sie einen Zeitpunkt in der Zukunft.';
        } else {
            try {
                $count = Campaigns::start($id, date('Y-m-d H:i:s', $ts));
                Util::flash('Versand geplant für <strong>' . Util::e(date('d.m.Y, H:i', $ts)) . '</strong> Uhr ('
                    . Util::num($count) . ' Empfänger).');
                Util::redirect('kampagne.php?id=' . $id);
            } catch (Throwable $e) {
                $errors[] = $e->getMessage();
            }
        }
    }

    // Zwischen Baukasten und HTML wechseln, ohne Inhalte zu verlieren
    if ($action === 'modus' && $editable) {
        $ziel = Util::post('ziel') === 'blocks' ? 'blocks' : 'html';

        // Erst den aktuellen Stand sichern, damit beim Wechsel nichts verloren geht
        if ($campaign['editor_mode'] === 'blocks' && Util::postRaw('blocks_json') !== '') {
            Campaigns::save($id, ['editor_mode' => 'blocks', 'blocks_json' => Util::postRaw('blocks_json')]);
        } elseif ($campaign['editor_mode'] !== 'blocks' && Util::postRaw('content_html') !== '') {
            Campaigns::save($id, ['content_html' => Util::postRaw('content_html')]);
        }
        $campaign = Campaigns::byId($id);

        if ($ziel === 'blocks') {
            $vorhanden = trim((string) $campaign['blocks_json']);
            if ($vorhanden === '') {
                // Bestehendes HTML als eigenen Baustein übernehmen
                $start = Blocks::starterCampaign(Templates::byId((int) $campaign['template_id']));
                $inhalt = trim((string) $campaign['content_html']);
                if ($inhalt !== '') {
                    $start['blocks'] = [Blocks::block('html', ['html' => $inhalt])];
                }
                $vorhanden = (string) json_encode($start, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
            Campaigns::save($id, ['editor_mode' => 'blocks', 'blocks_json' => $vorhanden]);
            Util::flash('Baukasten aktiviert. Ihr bisheriger Inhalt steht als Baustein „Eigenes HTML“ bereit.');
        } else {
            Campaigns::save($id, ['editor_mode' => 'html']);
            Util::flash('HTML-Modus aktiviert. Die im Baukasten erzeugte Fassung können Sie hier weiterbearbeiten.');
        }
        Util::redirect('kampagne.php?id=' . $id);
    }

    if ($action === 'pause') {
        Campaigns::pause($id);
        Util::flash('Versand pausiert.');
        Util::redirect('kampagne.php?id=' . $id);
    }
    if ($action === 'fortsetzen') {
        Campaigns::resume($id);
        Util::flash('Versand fortgesetzt.');
        Util::redirect('kampagne.php?id=' . $id);
    }
    if ($action === 'abbrechen') {
        Campaigns::cancel($id);
        Util::flash('Versand abgebrochen. Bereits versendete Mails lassen sich nicht zurückholen.', 'warning');
        Util::redirect('kampagne.php?id=' . $id);
    }

    $campaign = Campaigns::byId($id);
}

$stats     = Campaigns::stats($id);
$problems  = Campaigns::validate($campaign);
$editable   = in_array($campaign['status'], [Campaigns::DRAFT, Campaigns::SCHEDULED], true);
$recipient  = Campaigns::recipientCount($campaign);
$useBuilder = Campaigns::usesBuilder($campaign);
?>

<div class="ad-page-head">
    <div>
        <h1><?= Util::e((string) $campaign['name']) ?></h1>
        <p class="ad-sub">
            <?= campaign_status_pill((string) $campaign['status']) ?>
            · Liste: <?= Util::e(Lists::name($campaign['list_id'] !== null ? (int) $campaign['list_id'] : null)) ?>
            · <?= Util::num($recipient) ?> aktive Empfänger
        </p>
    </div>
    <div class="ad-actions-inline">
        <a class="ad-btn ad-btn-secondary" href="kampagnen.php">Zurück zur Liste</a>
        <?php if ((int) $stats['sent'] > 0): ?>
            <a class="ad-btn ad-btn-secondary" href="statistik.php?id=<?= $id ?>">Auswertung</a>
        <?php endif; ?>
    </div>
</div>

<?php foreach ($errors as $error): ?>
    <div class="ad-flash ad-flash-error"><?= Util::e($error) ?></div>
<?php endforeach; ?>

<?php if (!$editable): ?>
    <div class="ad-flash ad-flash-info">
        Dieser Newsletter ist <strong><?= Util::e(Campaigns::statusLabels()[$campaign['status']] ?? '') ?></strong>
        und kann nicht mehr verändert werden. Für eine neue Ausgabe können Sie ihn kopieren.
    </div>
<?php endif; ?>

<?php
// Die Inhaltskarte wird einmal aufgebaut und je nach Modus an anderer Stelle
// ausgegeben: der Baukasten braucht die volle Breite, das HTML-Feld nicht.
ob_start();
?>
            <div class="ad-card">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
                    <h2 style="margin:0;">Inhalt</h2>
                    <div class="ad-actions-inline">
                        <button type="submit" name="aktion" value="modus" class="ad-btn ad-btn-small <?= $useBuilder ? '' : 'ad-btn-secondary' ?>"
                                <?= $editable && !$useBuilder ? '' : 'disabled' ?>
                                onclick="this.form.ziel.value='blocks'">Baukasten</button>
                        <button type="submit" name="aktion" value="modus" class="ad-btn ad-btn-small <?= $useBuilder ? 'ad-btn-secondary' : '' ?>"
                                <?= $editable && $useBuilder ? '' : 'disabled' ?>
                                onclick="this.form.ziel.value='html'">HTML</button>
                        <input type="hidden" name="ziel" value="">
                    </div>
                </div>

                <?php if ($useBuilder && !$editable): ?>
                    <p class="ad-hint" style="margin:6px 0 0;">Diese Ausgabe wurde mit dem Baukasten erstellt und ist
                        abgeschlossen. Zum Weiterarbeiten kopieren Sie sie über „Newsletter → Kopieren“.</p>
                <?php elseif ($useBuilder): ?>
                    <p class="ad-hint" style="margin:6px 0 14px;">Bausteine per Drag &amp; Drop anordnen.
                        Texte lassen sich direkt in der Vorschau schreiben; Platzhalter setzen Sie links ein.</p>
                    <?php builder_ui(Campaigns::blocks($campaign), 'campaign'); ?>
                    <input type="hidden" name="editor_mode" value="blocks">
                <?php else: ?>
                    <input type="hidden" name="editor_mode" value="html">
                    <div class="ad-field" style="margin-top:14px;">
                        <label for="content_html">HTML-Inhalt <span class="ad-hint">(wird in die Vorlage eingesetzt)</span></label>
                        <textarea id="content_html" name="content_html" rows="20" class="ad-code"
                            <?= $editable ? '' : 'disabled' ?>><?= Util::e((string) $campaign['content_html']) ?></textarea>
                        <p class="ad-hint">Tipp: Für E-Mails eignen sich einfache Absätze mit Inline-Stilen –
                            moderne CSS-Layouts zeigen viele Programme nicht korrekt an.</p>
                    </div>

                    <div class="ad-field">
                        <label for="content_text">Textfassung <span class="ad-hint">(leer lassen = automatisch aus dem HTML)</span></label>
                        <textarea id="content_text" name="content_text" rows="6" class="ad-code"
                            <?= $editable ? '' : 'disabled' ?>><?= Util::e((string) $campaign['content_text']) ?></textarea>
                        <p class="ad-hint">Jede Mail geht als HTML <em>und</em> als reiner Text raus – das verbessert
                            die Zustellbarkeit deutlich.</p>
                    </div>
                <?php endif; ?>
            </div>

<?php
$inhaltKarte = (string) ob_get_clean();
?>

<form method="post" data-warn-unsaved>
    <?= Util::csrfField() ?>
    <input type="hidden" name="id" value="<?= $id ?>">

    <?php if ($useBuilder): ?>
        <?= $inhaltKarte ?>
    <?php endif; ?>

    <div class="ad-editor-grid">
        <!-- ------------------------------------------------ linke Spalte -->
        <div>
            <div class="ad-card">
                <h2>Betreff und Absender</h2>

                <div class="ad-field">
                    <label for="name">Interner Name <span class="ad-hint">(nur in der Verwaltung sichtbar)</span></label>
                    <input type="text" id="name" name="name" maxlength="190" <?= $editable ? '' : 'disabled' ?>
                           value="<?= Util::e((string) $campaign['name']) ?>">
                </div>

                <div class="ad-field">
                    <label for="subject">Betreff</label>
                    <input type="text" id="subject" name="subject" maxlength="255" <?= $editable ? '' : 'disabled' ?>
                           value="<?= Util::e((string) $campaign['subject']) ?>"
                           placeholder="z. B. 3 Kennzahlen, die Ihren Newsletter besser machen">
                    <p class="ad-hint">40–60 Zeichen wirken am besten. Platzhalter wie {{vorname}} sind erlaubt.</p>
                    <?php if ($editable && Ai::available()): ?>
                        <button type="button" class="ad-btn ad-btn-secondary ad-btn-small" data-ki-betreff="subject">
                            ✨ Betreff vorschlagen lassen
                        </button>
                    <?php endif; ?>
                </div>

                <div class="ad-field">
                    <label for="preheader">Vorschautext (Preheader)</label>
                    <input type="text" id="preheader" name="preheader" maxlength="255" <?= $editable ? '' : 'disabled' ?>
                           value="<?= Util::e((string) $campaign['preheader']) ?>"
                           placeholder="Ergänzt den Betreff in der Vorschau des Postfachs">
                </div>

                <div class="ad-row">
                    <div class="ad-field">
                        <label for="from_name">Absendername</label>
                        <input type="text" id="from_name" name="from_name" maxlength="190" <?= $editable ? '' : 'disabled' ?>
                               value="<?= Util::e((string) $campaign['from_name']) ?>">
                    </div>
                    <div class="ad-field">
                        <label for="from_email">Absenderadresse</label>
                        <input type="email" id="from_email" name="from_email" maxlength="190" <?= $editable ? '' : 'disabled' ?>
                               value="<?= Util::e((string) $campaign['from_email']) ?>">
                    </div>
                    <div class="ad-field">
                        <label for="reply_to">Antwortadresse <span class="ad-hint">(optional)</span></label>
                        <input type="email" id="reply_to" name="reply_to" maxlength="190" <?= $editable ? '' : 'disabled' ?>
                               value="<?= Util::e((string) $campaign['reply_to']) ?>">
                    </div>
                </div>
            </div>

            <?php if (!$useBuilder): ?>
                <?= $inhaltKarte ?>
            <?php endif; ?>

            <div class="ad-card">
                <h2>Vorschau</h2>
                <div class="ad-actions" style="margin-top:0;margin-bottom:12px;">
                    <button type="submit" name="aktion" value="speichern" class="ad-btn ad-btn-secondary ad-btn-small"
                        <?= $editable ? '' : 'disabled' ?>>Speichern &amp; Vorschau aktualisieren</button>
                    <a class="ad-btn ad-btn-secondary ad-btn-small" target="_blank" rel="noopener"
                       href="kampagne.php?id=<?= $id ?>&amp;vorschau=1">In neuem Tab öffnen</a>
                </div>
                <iframe id="preview-frame" class="ad-preview-frame"
                        src="kampagne.php?id=<?= $id ?>&amp;vorschau=1" title="Vorschau des Newsletters"></iframe>
            </div>
        </div>

        <!-- ------------------------------------------------ rechte Spalte -->
        <div>
            <div class="ad-card">
                <h2>Versand</h2>

                <?php if ($problems !== []): ?>
                    <div class="ad-flash ad-flash-warning" style="margin-bottom:14px;">
                        <strong>Noch zu klären:</strong>
                        <ul style="margin:8px 0 0 18px;padding:0;">
                            <?php foreach ($problems as $problem): ?>
                                <li><?= Util::e($problem) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="ad-flash ad-flash-success" style="margin-bottom:14px;">
                        Versandbereit: <strong><?= Util::num($recipient) ?></strong> Empfänger.
                    </div>
                <?php endif; ?>

                <?php if ($campaign['status'] === Campaigns::SENDING): ?>
                    <p>Der Versand läuft. Fortschritt:
                        <strong><?= Util::num((int) $stats['sent']) ?></strong> von
                        <?= Util::num((int) $stats['total']) ?></p>
                    <div class="ad-progress">
                        <span style="width:<?= (int) $stats['total'] > 0 ? round((int) $stats['sent'] / (int) $stats['total'] * 100) : 0 ?>%"></span>
                    </div>
                    <div class="ad-actions">
                        <button type="submit" name="aktion" value="pause" class="ad-btn ad-btn-secondary">Pausieren</button>
                        <button type="submit" name="aktion" value="abbrechen" class="ad-btn ad-btn-danger"
                                data-confirm="Versand wirklich abbrechen? Noch nicht versendete Mails werden verworfen.">Abbrechen</button>
                    </div>
                <?php elseif ($campaign['status'] === Campaigns::PAUSED): ?>
                    <p>Der Versand ist pausiert. Es warten
                        <strong><?= Util::num((int) $stats['pending']) ?></strong> Mails.</p>
                    <div class="ad-actions">
                        <button type="submit" name="aktion" value="fortsetzen" class="ad-btn">Fortsetzen</button>
                        <button type="submit" name="aktion" value="abbrechen" class="ad-btn ad-btn-danger"
                                data-confirm="Versand wirklich abbrechen?">Abbrechen</button>
                    </div>
                <?php elseif ($campaign['status'] === Campaigns::SCHEDULED): ?>
                    <p>Geplant für <strong><?= Util::e(Util::dt((string) $campaign['scheduled_at'])) ?></strong> Uhr.</p>
                    <div class="ad-actions">
                        <button type="submit" name="aktion" value="abbrechen" class="ad-btn ad-btn-danger"
                                data-confirm="Geplanten Versand abbrechen?">Planung aufheben</button>
                    </div>
                <?php elseif ($campaign['status'] === Campaigns::SENT): ?>
                    <p>Versendet an <strong><?= Util::num((int) $stats['sent']) ?></strong> Empfänger.</p>
                    <div class="ad-actions">
                        <a class="ad-btn ad-btn-secondary" href="statistik.php?id=<?= $id ?>">Zur Auswertung</a>
                    </div>
                <?php else: ?>
                    <div class="ad-actions" style="margin-top:0;">
                        <button type="submit" name="aktion" value="senden" class="ad-btn"
                                data-confirm="Newsletter jetzt an <?= Util::num($recipient) ?> Empfänger senden?"
                            <?= $problems === [] ? '' : 'disabled' ?>>Jetzt senden</button>
                    </div>
                    <div class="ad-field" style="margin-top:16px;">
                        <label for="scheduled_at">…oder später senden</label>
                        <input type="datetime-local" id="scheduled_at" name="scheduled_at"
                               value="<?= Util::e($campaign['scheduled_at'] !== null ? date('Y-m-d\TH:i', strtotime((string) $campaign['scheduled_at'])) : '') ?>">
                        <button type="submit" name="aktion" value="planen" class="ad-btn ad-btn-secondary ad-btn-small"
                                style="margin-top:8px;" <?= $problems === [] ? '' : 'disabled' ?>>Versand planen</button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="ad-card">
                <h2>Testversand</h2>
                <div class="ad-field">
                    <label for="test_email">Testadresse</label>
                    <input type="email" id="test_email" name="test_email" value="<?= Util::e((string) ($currentUser['email'] ?? '')) ?>">
                </div>
                <button type="submit" name="aktion" value="test" class="ad-btn ad-btn-secondary"
                    <?= $editable ? '' : 'disabled' ?>>Testmail senden</button>
                <p class="ad-hint">Prüfen Sie die Testmail in mindestens zwei Programmen (z. B. Outlook und Handy).</p>
            </div>

            <div class="ad-card">
                <h2>Vorlage &amp; Liste</h2>
                <div class="ad-field">
                    <label for="template_id">Design-Vorlage</label>
                    <select id="template_id" name="template_id" <?= $editable ? '' : 'disabled' ?>>
                        <?php foreach (Templates::all() as $template): ?>
                            <option value="<?= (int) $template['id'] ?>"
                                <?= (int) $campaign['template_id'] === (int) $template['id'] ? 'selected' : '' ?>>
                                <?= Util::e((string) $template['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="ad-hint">Umstellen wechselt Kopfzeile, Footer, Schriften und Farben – auch im
                        bereits geschriebenen Inhalt. Farben, die Sie selbst gesetzt haben, bleiben.</p>
                </div>
                <div class="ad-field">
                    <label for="list_id">Empfängerliste</label>
                    <select id="list_id" name="list_id" <?= $editable ? '' : 'disabled' ?>>
                        <option value="0">Alle aktiven Empfänger</option>
                        <?php foreach (Lists::all() as $list): ?>
                            <option value="<?= (int) $list['id'] ?>"
                                <?= (int) $campaign['list_id'] === (int) $list['id'] ? 'selected' : '' ?>>
                                <?= Util::e((string) $list['name']) ?>
                                (<?= Util::num(Subscribers::countActiveForList((int) $list['id'])) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <label class="ad-check">
                    <input type="checkbox" name="track_opens" value="1" <?= (int) $campaign['track_opens'] === 1 ? 'checked' : '' ?>
                        <?= $editable ? '' : 'disabled' ?>>
                    <span>Öffnungen messen <em class="ad-hint">(unsichtbares Zählpixel)</em></span>
                </label>
                <label class="ad-check">
                    <input type="checkbox" name="track_clicks" value="1" <?= (int) $campaign['track_clicks'] === 1 ? 'checked' : '' ?>
                        <?= $editable ? '' : 'disabled' ?>>
                    <span>Klicks messen <em class="ad-hint">(Links laufen über einen Zähler)</em></span>
                </label>
                <label class="ad-check">
                    <input type="checkbox" name="archive_public" value="1" <?= (int) $campaign['archive_public'] === 1 ? 'checked' : '' ?>
                        <?= $editable ? '' : 'disabled' ?>>
                    <span>Im öffentlichen Archiv zeigen</span>
                </label>

                <div class="ad-actions">
                    <button type="submit" name="aktion" value="speichern" class="ad-btn ad-btn-secondary"
                        <?= $editable ? '' : 'disabled' ?>>Speichern</button>
                </div>
            </div>

            <div class="ad-card">
                <h2>Platzhalter</h2>
                <ul class="ad-placeholder-list">
                    <?php foreach (Renderer::placeholderHelp() as $code => $meaning): ?>
                        <li><code><?= Util::e($code) ?></code><br><span class="ad-hint"><?= Util::e($meaning) ?></span></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</form>

<?php require __DIR__ . '/partials/footer.php';
