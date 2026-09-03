<?php
/**
 * turniere.php – die Turnier-Kommunikation.
 *
 * Eine Serie hängt an der Rubrik „Turniere" des Redaktionspools und
 * verschickt rund um jeden Termin ihre Touchpoints (14/7/1 Tag vorher, danach).
 * Je Serie ist wählbar, ob vollautomatisch gesendet oder erst ein Entwurf zum
 * Prüfen erstellt wird.
 *
 * Zwei Ansichten: die Liste aller Serien und – mit ?id=… – der Editor einer
 * Serie samt Touchpoints und Terminvorschau.
 */

$pageTitle     = 'Turniere';
$requiredRight = 'kampagnen';
require __DIR__ . '/partials/header.php';

$darf = Auth::can('kampagnen');

/* ------------------------------------------------------------- Aktionen */

if (Util::isPost()) {
    Util::requireCsrf();
    if (!$darf) {
        Util::flash('Dafür fehlt Ihnen die Berechtigung.', 'error');
        Util::redirect('turniere.php');
    }
    $aktion = Util::post('aktion');

    if ($aktion === 'serie-anlegen') {
        $neu = Turniere::createSeries(
            Util::post('name') ?: 'Turnier-Kommunikation',
            Util::postInt('list_id') ?: null,
            Util::post('mode') === Turniere::MODE_AUTO ? Turniere::MODE_AUTO : Turniere::MODE_DRAFT
        );
        Util::flash('Serie angelegt. Die vier üblichen Touchpoints sind schon dabei – passen Sie sie an und aktivieren Sie die Serie.');
        Util::redirect('turniere.php?id=' . $neu);
    }

    if ($aktion === 'serie-speichern' && Util::postInt('id') > 0) {
        $id = Util::postInt('id');
        Turniere::saveSeries($id, [
            'name'        => Util::post('name'),
            'list_id'     => Util::postInt('list_id') ?: null,
            'category'    => Util::post('category'),
            'mode'        => Util::post('mode'),
            'status'      => Util::post('status'),
            'template_id' => Util::postInt('template_id') ?: null,
        ]);
        // Touchpoints in einem Rutsch speichern
        foreach ((array) ($_POST['tp'] ?? []) as $tpId => $werte) {
            $tpId = (int) $tpId;
            if ($tpId <= 0 || !is_array($werte)) {
                continue;
            }
            Turniere::saveTouchpoint($tpId, [
                'offset_days' => (int) ($werte['offset_days'] ?? 0),
                'subject'     => (string) ($werte['subject'] ?? ''),
                'intro'       => (string) ($werte['intro'] ?? ''),
                'active'      => isset($werte['active']) ? 1 : 0,
            ]);
        }
        Util::flash('Serie gespeichert.');
        Util::redirect('turniere.php?id=' . $id);
    }

    if ($aktion === 'touchpoint-hinzu' && Util::postInt('id') > 0) {
        $id = Util::postInt('id');
        $anzahl = count(Turniere::touchpoints($id));
        DB::insert('event_touchpoints', [
            'series_id'   => $id,
            'offset_days' => 0,
            'subject'     => 'Rund um {{turnier}}',
            'intro'       => "hier eine kurze Nachricht zu {{turnier}} am {{datum}}.",
            'active'      => 1,
            'sort'        => $anzahl,
            'created_at'  => Util::now(),
            'updated_at'  => Util::now(),
        ]);
        Util::flash('Touchpoint hinzugefügt.');
        Util::redirect('turniere.php?id=' . $id . '#touchpoints');
    }

    if ($aktion === 'touchpoint-loeschen' && Util::postInt('id') > 0 && Util::postInt('tp_id') > 0) {
        $id = Util::postInt('id');
        // Nur löschen, wenn der Touchpoint auch zu dieser Serie gehört.
        DB::delete('event_touchpoints', 'id = ? AND series_id = ?', [Util::postInt('tp_id'), $id]);
        Util::flash('Touchpoint entfernt.');
        Util::redirect('turniere.php?id=' . $id . '#touchpoints');
    }

    if ($aktion === 'serie-loeschen' && Util::postInt('id') > 0) {
        Turniere::deleteSeries(Util::postInt('id'));
        Util::flash('Serie gelöscht.');
        Util::redirect('turniere.php');
    }

    if ($aktion === 'jetzt-pruefen') {
        $r = Turniere::runDaily();
        if ($r['prepared'] + $r['sent'] === 0) {
            Util::flash('Heute ist kein Touchpoint fällig – es gibt nichts vorzubereiten.');
        } else {
            Util::flash(sprintf('Fertig: %d Entwurf/Entwürfe erstellt, %d sofort versendet.',
                $r['prepared'], $r['sent']));
        }
        Util::redirect('turniere.php' . (Util::postInt('id') > 0 ? '?id=' . Util::postInt('id') : ''));
    }
}

/* ------------------------------------------------------------- Anzeige */

$current = null;
if (Util::getInt('id') > 0) {
    $current = Turniere::series(Util::getInt('id'));
}
$neu     = $current === null && Util::get('neu') === '1';
$serien  = Turniere::allSeries();
$listen  = Lists::all();

/** Marke/Liste-Name kurz. */
function tk_list_name(?int $id): string
{
    return $id !== null && $id > 0 ? Lists::name($id) : 'alle Listen';
}
?>

<div class="ad-page-head">
    <div>
        <h1>Turniere</h1>
        <p class="ad-sub">Rund um jedes Turnier automatisch informieren – vorher anmelden, danach Ergebnisse.</p>
    </div>
    <?php if ($current !== null): ?>
        <button type="submit" form="serie-form" class="ad-btn">Serie speichern</button>
    <?php endif; ?>
</div>

<?php if ($current === null): ?>

    <!-- ------------------------------------------------ Übersicht der Serien -->

    <div class="ad-card" style="border-color:var(--ad-akzent,#2C6B45);">
        <h2 style="margin-top:0;">So funktioniert die Turnier-Kommunikation</h2>
        <p style="margin:0 0 6px;">Ein Turnier steht mit Datum im Redaktionspool (unter
            <a href="wochennews.php">Wochennews → Turniere</a>). Eine Serie greift diese Termine auf und
            verschickt ihre Touchpoints von selbst – z. B. 14 Tage, 7 Tage und 1 Tag vorher sowie eine
            Nachlese danach. Sie legen den Text einmal an, das System setzt Name und Datum jedes Turniers
            automatisch ein.</p>
        <p class="ad-hint" style="margin:0;">Je Serie wählen Sie, ob vollautomatisch gesendet oder erst ein
            Entwurf zum Prüfen erzeugt wird.</p>
    </div>

    <?php if ($serien === []): ?>
        <div class="ad-card">
            <p style="margin:0 0 12px;">Noch keine Serie. Legen Sie Ihre erste an – die vier üblichen
                Touchpoints sind sofort dabei.</p>
        </div>
    <?php else: ?>
        <div class="ad-card ad-card-tight">
            <div class="ad-table-wrap">
                <table class="ad-table">
                    <tbody>
                    <?php foreach ($serien as $s):
                        $aktiv = $s['status'] === Turniere::ACTIVE;
                        $tps   = Turniere::touchpoints((int) $s['id']);
                        $anTp  = count(array_filter($tps, static fn($t) => (int) $t['active'] === 1)); ?>
                        <tr>
                            <td>
                                <strong><?= Util::e((string) $s['name']) ?></strong>
                                <?php if ($aktiv): ?>
                                    <span class="ad-pill ad-pill-green">aktiv</span>
                                <?php else: ?>
                                    <span class="ad-pill ad-pill-grey">pausiert</span>
                                <?php endif; ?>
                                <?php if ($s['mode'] === Turniere::MODE_AUTO): ?>
                                    <span class="ad-pill ad-pill-blue">automatisch</span>
                                <?php else: ?>
                                    <span class="ad-pill ad-pill-amber">Entwurf zum Prüfen</span>
                                <?php endif; ?>
                                <br><span class="ad-hint"><?= (int) $anTp ?> von <?= count($tps) ?> Touchpoints aktiv ·
                                    <?= Util::e(tk_list_name($s['list_id'] !== null ? (int) $s['list_id'] : null)) ?></span>
                            </td>
                            <td style="width:1%;white-space:nowrap;text-align:right;">
                                <a class="ad-btn ad-btn-secondary ad-btn-small" href="turniere.php?id=<?= (int) $s['id'] ?>">Bearbeiten</a>
                                <details class="ad-menue">
                                    <summary aria-label="Weitere Aktionen">…</summary>
                                    <div class="ad-menue-liste">
                                        <form method="post">
                                            <?= Util::csrfField() ?>
                                            <input type="hidden" name="aktion" value="serie-loeschen">
                                            <input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
                                            <button type="submit" class="ist-gefahr"
                                                    data-confirm="Diese Serie mit allen Touchpoints wirklich löschen?">Löschen</button>
                                        </form>
                                    </div>
                                </details>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- ------------------------------------------------ Neue Serie -->

    <details class="ad-card ad-klapp" id="neue-serie" <?= $neu ? 'open' : '' ?>>
        <summary>
            <h2>Neue Serie anlegen</h2>
            <span class="ad-klapp-zeichen" aria-hidden="true"></span>
        </summary>
        <form method="post" style="margin-top:12px;">
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="serie-anlegen">
            <div class="ad-row">
                <div class="ad-field" style="flex:2 1 300px;">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" maxlength="190" autofocus
                           placeholder="z. B. Turnier-Kommunikation">
                </div>
                <div class="ad-field" style="flex:1 1 220px;">
                    <label for="list_id">An welche Liste?</label>
                    <select id="list_id" name="list_id">
                        <option value="0">Alle Listen</option>
                        <?php foreach ($listen as $l): ?>
                            <option value="<?= (int) $l['id'] ?>"><?= Util::e((string) $l['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="ad-field" style="flex:1 1 220px;">
                    <label for="mode">Betriebsart</label>
                    <select id="mode" name="mode">
                        <option value="draft">Entwurf zum Prüfen</option>
                        <option value="auto">Vollautomatisch senden</option>
                    </select>
                </div>
            </div>
            <div class="ad-actions">
                <button type="submit" class="ad-btn">Serie anlegen</button>
            </div>
        </form>
    </details>

<?php else: ?>

    <!-- ------------------------------------------------ Editor einer Serie -->

    <?php
    $tps      = Turniere::touchpoints((int) $current['id']);
    $turniere = Turniere::tournaments((string) $current['category']);
    $plan     = Turniere::schedulePreview($current);
    ?>

    <p style="margin:-6px 0 14px;"><a href="turniere.php">← Alle Serien</a></p>

    <form method="post" id="serie-form">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="serie-speichern">
        <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">

        <div class="ad-card">
            <div class="ad-row">
                <div class="ad-field" style="flex:2 1 300px;">
                    <label for="s_name">Name</label>
                    <input type="text" id="s_name" name="name" maxlength="190"
                           value="<?= Util::e((string) $current['name']) ?>">
                </div>
                <div class="ad-field" style="flex:1 1 200px;">
                    <label for="s_list">An welche Liste?</label>
                    <select id="s_list" name="list_id">
                        <option value="0">Alle Listen</option>
                        <?php foreach ($listen as $l): ?>
                            <option value="<?= (int) $l['id'] ?>"
                                <?= (int) ($current['list_id'] ?? 0) === (int) $l['id'] ? 'selected' : '' ?>>
                                <?= Util::e((string) $l['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="ad-row">
                <div class="ad-field" style="flex:1 1 220px;">
                    <label for="s_mode">Betriebsart</label>
                    <select id="s_mode" name="mode">
                        <option value="draft" <?= $current['mode'] !== Turniere::MODE_AUTO ? 'selected' : '' ?>>Entwurf zum Prüfen</option>
                        <option value="auto" <?= $current['mode'] === Turniere::MODE_AUTO ? 'selected' : '' ?>>Vollautomatisch senden</option>
                    </select>
                </div>
                <div class="ad-field" style="flex:1 1 220px;">
                    <label for="s_status">Zustand</label>
                    <select id="s_status" name="status">
                        <option value="paused" <?= $current['status'] !== Turniere::ACTIVE ? 'selected' : '' ?>>Pausiert</option>
                        <option value="active" <?= $current['status'] === Turniere::ACTIVE ? 'selected' : '' ?>>Aktiv</option>
                    </select>
                </div>
                <div class="ad-field" style="flex:2 1 260px;align-self:flex-end;">
                    <p class="ad-hint" style="margin:0 0 6px;">Platzhalter im Text:
                        <code>{{turnier}}</code> (Name), <code>{{datum}}</code> (Termin),
                        <code>{{vorname}}</code> (Empfänger).</p>
                </div>
            </div>
        </div>

        <!-- Touchpoints -->
        <div class="ad-card" id="touchpoints">
            <h2 style="margin-top:0;">Touchpoints</h2>
            <p class="ad-hint" style="margin:0 0 14px;">Der Abstand ist in Tagen zum Turniertermin:
                <strong>negativ = davor</strong>, <strong>positiv = danach</strong>, 0 = am Turniertag.</p>

            <?php if ($tps === []): ?>
                <p>Diese Serie hat noch keine Touchpoints.</p>
            <?php endif; ?>

            <?php foreach ($tps as $tp): $off = (int) $tp['offset_days']; ?>
                <div class="ad-card ad-card-tight" style="margin-bottom:12px;">
                    <div class="ad-row" style="align-items:flex-end;">
                        <div class="ad-field" style="flex:0 1 140px;">
                            <label>Abstand (Tage)</label>
                            <input type="number" name="tp[<?= (int) $tp['id'] ?>][offset_days]" min="-365" max="365"
                                   value="<?= $off ?>">
                        </div>
                        <div class="ad-field" style="flex:0 1 170px;justify-content:flex-end;">
                            <span class="ad-pill ad-pill-grey"><?= Util::e(Turniere::offsetLabel($off)) ?></span>
                        </div>
                        <div class="ad-field" style="flex:1 1 200px;justify-content:flex-end;">
                            <label class="ad-check" style="margin-bottom:6px;">
                                <input type="checkbox" name="tp[<?= (int) $tp['id'] ?>][active]" value="1"
                                    <?= (int) $tp['active'] === 1 ? 'checked' : '' ?>>
                                aktiv
                            </label>
                        </div>
                        <div class="ad-field" style="flex:0;justify-content:flex-end;">
                            <details class="ad-menue">
                                <summary aria-label="Touchpoint-Aktionen">…</summary>
                                <div class="ad-menue-liste">
                                    <button type="submit" form="tp-del-<?= (int) $tp['id'] ?>" class="ist-gefahr"
                                            data-confirm="Diesen Touchpoint entfernen?">Entfernen</button>
                                </div>
                            </details>
                        </div>
                    </div>
                    <div class="ad-field">
                        <label>Betreff</label>
                        <input type="text" name="tp[<?= (int) $tp['id'] ?>][subject]" maxlength="190"
                               value="<?= Util::e((string) $tp['subject']) ?>">
                    </div>
                    <div class="ad-field">
                        <label>Text</label>
                        <textarea name="tp[<?= (int) $tp['id'] ?>][intro]" rows="3"><?= Util::e((string) $tp['intro']) ?></textarea>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </form>

    <?php /* Die Lösch-/Hinzufügen-Formulare stehen außerhalb des großen Formulars,
             damit ihr Absenden nicht das ganze Serienformular mitschickt. */ ?>
    <?php foreach ($tps as $tp): ?>
        <form method="post" id="tp-del-<?= (int) $tp['id'] ?>" hidden>
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="touchpoint-loeschen">
            <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
            <input type="hidden" name="tp_id" value="<?= (int) $tp['id'] ?>">
        </form>
    <?php endforeach; ?>

    <div class="ad-card ad-card-tight">
        <form method="post" style="margin:0;">
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="touchpoint-hinzu">
            <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
            <button type="submit" class="ad-btn ad-btn-secondary">+ Touchpoint hinzufügen</button>
        </form>
    </div>

    <!-- Terminvorschau -->
    <div class="ad-card">
        <h2 style="margin-top:0;">Was steht an?</h2>
        <?php if ($turniere === []): ?>
            <p>Für die Rubrik „<?= Util::e(Wochennews::categoryLabel((string) $current['category'])) ?>" sind noch
                keine kommenden Turniere mit Datum eingetragen.
                <a href="wochennews.php?neu=1#thema-form">Jetzt ein Turnier anlegen →</a></p>
        <?php elseif ($plan === []): ?>
            <p>Es sind Turniere eingetragen, aber in nächster Zeit wird kein Touchpoint fällig
                (alle Stichtage liegen in der Vergangenheit oder es sind keine Touchpoints aktiv).</p>
        <?php else: ?>
            <p class="ad-hint" style="margin:0 0 10px;">Diese Mails bereitet die Serie als Nächstes vor
                <?= $current['status'] === Turniere::ACTIVE ? '' : '(sobald die Serie aktiv ist)' ?>:</p>
            <div class="ad-table-wrap">
                <table class="ad-table">
                    <thead>
                        <tr><th>Stichtag</th><th>Turnier</th><th>Touchpoint</th><th></th></tr>
                    </thead>
                    <tbody>
                    <?php foreach (array_slice($plan, 0, 30) as $z): ?>
                        <tr<?= $z['erledigt'] ? ' style="opacity:.5;"' : '' ?>>
                            <td class="ad-mono" style="white-space:nowrap;"><?= Util::e(Turniere::datumLang($z['datum'])) ?></td>
                            <td><?= Util::e($z['turnier']) ?>
                                <br><span class="ad-hint">Termin: <?= Util::e(Turniere::datumLang($z['item_date'])) ?></span></td>
                            <td><?= Util::e($z['touchpoint']) ?></td>
                            <td style="white-space:nowrap;">
                                <?php if ($z['erledigt']): ?>
                                    <span class="ad-pill ad-pill-green">vorbereitet</span>
                                <?php else: ?>
                                    <span class="ad-pill ad-pill-grey"><?= $z['mode'] === Turniere::MODE_AUTO ? 'sendet automatisch' : 'wird Entwurf' ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="ad-actions" style="margin-top:14px;">
            <form method="post" style="margin:0;">
                <?= Util::csrfField() ?>
                <input type="hidden" name="aktion" value="jetzt-pruefen">
                <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
                <button type="submit" class="ad-btn ad-btn-secondary"
                        data-confirm="Jetzt prüfen: Fällige Touchpoints werden vorbereitet – bei automatischen Serien auch sofort versendet. Fortfahren?">Jetzt prüfen</button>
            </form>
            <span class="ad-hint" style="margin-left:10px;">Sonst prüft der tägliche Wartungslauf von selbst.</span>
        </div>
    </div>

<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
