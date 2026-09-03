<?php
/**
 * wochennews.php – der Redaktionspool und der Ein-Klick-Generator.
 *
 * Oben der Knopf, der aus der gewählten Woche einen fertigen Entwurf baut
 * und direkt in den Prüfen-Schritt führt. Darunter die Themen, die einmal
 * eingetragen werden und aus denen sich der Newsletter dann selbst bedient.
 */

$pageTitle     = 'Wochennews';
$requiredRight = 'kampagnen';
require __DIR__ . '/partials/header.php';

$darf = Auth::can('kampagnen');

/* ------------------------------------------------------------- Aktionen */

if (Util::isPost()) {
    Util::requireCsrf();
    if (!$darf) {
        Util::flash('Dafür fehlt Ihnen die Berechtigung.', 'error');
        Util::redirect('wochennews.php');
    }
    $aktion = Util::post('aktion');

    if ($aktion === 'generieren') {
        [$jahr, $kw] = wn_woche_lesen(Util::post('woche'));
        try {
            $id = Wochennews::generate($jahr, $kw);
            Util::flash('Entwurf erstellt – bitte prüfen und dann senden.');
            Util::redirect('kampagne.php?id=' . $id);
        } catch (Throwable $e) {
            Util::flash(Util::e($e->getMessage()), 'error');
            Util::redirect('wochennews.php?woche=' . $jahr . '-' . $kw);
        }
    }

    if ($aktion === 'anlegen' || $aktion === 'speichern') {
        $daten = [
            'category'   => Util::post('category'),
            'title'      => Util::post('title'),
            'body'       => Util::postRaw('body'),
            'item_date'  => Util::post('item_date'),
            'date_until' => Util::post('date_until'),
            'link_url'   => Util::post('link_url'),
            'link_label' => Util::post('link_label'),
            'image_url'  => Util::post('image_url'),
            'evergreen'  => Util::post('evergreen') !== '' ? 1 : 0,
        ];
        if (trim((string) $daten['title']) === '') {
            Util::flash('Bitte geben Sie dem Thema einen Titel.', 'error');
            Util::redirect('wochennews.php');
        }
        if ($aktion === 'speichern' && Util::postInt('id') > 0) {
            Wochennews::update(Util::postInt('id'), $daten);
            Util::flash('Thema aktualisiert.');
        } else {
            $daten['created_by'] = (string) ($currentUser['name'] ?: $currentUser['email']);
            Wochennews::add($daten);
            Util::flash('Thema hinzugefügt.');
        }
        Util::redirect('wochennews.php');
    }

    if ($aktion === 'loeschen' && Util::postInt('id') > 0) {
        Wochennews::delete(Util::postInt('id'));
        Util::flash('Thema gelöscht.');
        Util::redirect('wochennews.php');
    }

    if ($aktion === 'umschalten' && Util::postInt('id') > 0) {
        $eintrag = Wochennews::byId(Util::postInt('id'));
        if ($eintrag !== null) {
            Wochennews::setActive(Util::postInt('id'), (int) $eintrag['active'] === 0);
        }
        Util::redirect('wochennews.php');
    }

    if ($aktion === 'dauerinfos') {
        Settings::setMany([
            'wochen_oeffnung'   => mb_substr(Util::postRaw('wochen_oeffnung'), 0, 1000),
            'wochen_platz'      => mb_substr(Util::postRaw('wochen_platz'), 0, 1000),
            'wochen_gruss'      => mb_substr(Util::postRaw('wochen_gruss'), 0, 500),
            'wochen_intro'      => mb_substr(Util::postRaw('wochen_intro'), 0, 1000),
            'wochen_wetter_ort' => mb_substr(Util::post('wochen_wetter_ort'), 0, 120),
            'wochen_wetter_lat' => trim(Util::post('wochen_wetter_lat')),
            'wochen_wetter_lon' => trim(Util::post('wochen_wetter_lon')),
            'wochen_ki_intro'   => Util::post('wochen_ki_intro') !== '' ? '1' : '0',
        ]);
        Util::flash('Dauerinfos gespeichert.');
        Util::redirect('wochennews.php#dauerinfos');
    }
}

/** „2026-37" aus dem Formular in [Jahr, KW], notfalls die aktuelle Woche. */
function wn_woche_lesen(string $roh): array
{
    if (preg_match('/^(\d{4})-(\d{1,2})$/', trim($roh), $t)) {
        $jahr = (int) $t[1];
        $kw   = max(1, min(53, (int) $t[2]));
        return [$jahr, $kw];
    }
    return Wochennews::isoWeek();
}

/* ------------------------------------------------------------- Anzeige */

[$jahr, $kw] = wn_woche_lesen(Util::get('woche'));
$gewaehlt    = $jahr . '-' . $kw;
$anzahlWoche = Wochennews::countForWeek($jahr, $kw);

// Die nächsten acht Wochen zur Auswahl (ab dieser)
$wochen = [];
$start  = new DateTimeImmutable();
for ($i = 0; $i < 8; $i++) {
    $z = $start->modify('+' . $i . ' weeks');
    [$wj, $wk] = Wochennews::isoWeek($z->getTimestamp());
    $wochen["$wj-$wk"] = Wochennews::weekLabel($wj, $wk);
}
// Falls die per ?woche gewählte nicht in den nächsten acht liegt, ergänzen
if (!isset($wochen[$gewaehlt])) {
    $wochen[$gewaehlt] = Wochennews::weekLabel($jahr, $kw);
}

$alle       = Wochennews::all();
$nachRubrik = [];
foreach (array_keys(Wochennews::CATEGORIES) as $key) {
    $nachRubrik[$key] = [];
}
foreach ($alle as $e) {
    $nachRubrik[$e['category']][] = $e;
}

$bearbeiten = null;
if (Util::getInt('bearbeiten') > 0) {
    $bearbeiten = Wochennews::byId(Util::getInt('bearbeiten'));
}
$formOffen = $bearbeiten !== null || Util::get('neu') === '1';

/** Kleiner Helfer: Datum lesbar. */
function wn_datum(?string $d): string
{
    if ($d === null || $d === '') { return ''; }
    $o = DateTimeImmutable::createFromFormat('Y-m-d', $d);
    return $o !== false ? $o->format('d.m.Y') : '';
}
?>

<div class="ad-page-head">
    <div>
        <h1>Wochennews</h1>
        <p class="ad-sub">Themen einmal eintragen – den Wochennewsletter dann per Klick erzeugen.</p>
    </div>
</div>

<!-- ------------------------------------------------ Der Generator -->

<div class="ad-card" style="border-color:var(--ad-akzent,#2C6B45);">
    <h2 style="margin-top:0;">Wochennewsletter erzeugen</h2>
    <p>Das System sammelt automatisch alle Themen der gewählten Woche, ergänzt Öffnungszeiten,
        Platzstatus<?= trim(Settings::get('wochen_wetter_lat')) !== '' ? ' und Wetter' : '' ?> und baut daraus
        einen fertigen Entwurf. Danach nur noch prüfen und senden.</p>

    <form method="get" style="margin:0 0 8px;">
        <div class="ad-row" style="align-items:flex-end;">
            <div class="ad-field" style="flex:1 1 340px;">
                <label for="woche">Für welche Woche?</label>
                <select id="woche" name="woche" onchange="this.form.submit()">
                    <?php foreach ($wochen as $wert => $label): ?>
                        <option value="<?= Util::e($wert) ?>" <?= $wert === $gewaehlt ? 'selected' : '' ?>>
                            <?= Util::e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>

    <p class="ad-hint" style="margin:0 0 14px;">
        <?php if ($anzahlWoche > 0): ?>
            In dieser Woche liegen <strong><?= Util::num($anzahlWoche) ?></strong>
            Them<?= $anzahlWoche === 1 ? 'a' : 'en' ?> im Pool.
        <?php else: ?>
            Für diese Woche ist noch nichts eingetragen. Legen Sie unten Themen an – oder erzeugen Sie
            den Newsletter mit den Dauerinfos.
        <?php endif; ?>
    </p>

    <form method="post">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="generieren">
        <input type="hidden" name="woche" value="<?= Util::e($gewaehlt) ?>">
        <button type="submit" class="ad-btn" <?= $darf ? '' : 'disabled' ?>>Wochennewsletter generieren</button>
        <span class="ad-hint" style="margin-left:10px;">→ Prüfen → Senden</span>
    </form>
</div>

<!-- ------------------------------------------------ Thema anlegen/bearbeiten -->

<details class="ad-card ad-klapp" id="thema-form" <?= $formOffen ? 'open' : '' ?>>
    <summary>
        <h2><?= $bearbeiten !== null ? 'Thema bearbeiten' : 'Neues Thema' ?></h2>
        <span class="ad-klapp-zeichen" aria-hidden="true"></span>
    </summary>

    <form method="post" style="margin-top:12px;">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="<?= $bearbeiten !== null ? 'speichern' : 'anlegen' ?>">
        <?php if ($bearbeiten !== null): ?>
            <input type="hidden" name="id" value="<?= (int) $bearbeiten['id'] ?>">
        <?php endif; ?>

        <div class="ad-row">
            <div class="ad-field" style="flex:0 1 220px;">
                <label for="category">Rubrik</label>
                <select id="category" name="category">
                    <?php foreach (Wochennews::CATEGORIES as $key => $label): ?>
                        <option value="<?= Util::e($key) ?>"
                            <?= ($bearbeiten['category'] ?? '') === $key ? 'selected' : '' ?>><?= Util::e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="ad-field" style="flex:2 1 340px;">
                <label for="title">Titel</label>
                <input type="text" id="title" name="title" maxlength="190" required
                       value="<?= Util::e((string) ($bearbeiten['title'] ?? '')) ?>"
                       placeholder="z. B. Captains Cup – 18-Loch-Zählspiel">
            </div>
        </div>

        <div class="ad-field">
            <label for="body">Text</label>
            <textarea id="body" name="body" rows="4"
                      placeholder="Worum geht es? Zwei, drei Sätze genügen."><?= Util::e((string) ($bearbeiten['body'] ?? '')) ?></textarea>
        </div>

        <div class="ad-row">
            <div class="ad-field" style="flex:0 1 200px;">
                <label for="item_date">Datum (von)</label>
                <input type="date" id="item_date" name="item_date"
                       value="<?= Util::e((string) ($bearbeiten['item_date'] ?? '')) ?>">
            </div>
            <div class="ad-field" style="flex:0 1 200px;">
                <label for="date_until">bis (optional)</label>
                <input type="date" id="date_until" name="date_until"
                       value="<?= Util::e((string) ($bearbeiten['date_until'] ?? '')) ?>">
            </div>
            <div class="ad-field" style="flex:1 1 240px;justify-content:flex-end;">
                <label class="ad-check" style="margin-bottom:6px;">
                    <input type="checkbox" name="evergreen" value="1"
                        <?= (int) ($bearbeiten['evergreen'] ?? 0) === 1 ? 'checked' : '' ?>>
                    Dauerläufer – in jeder Woche zeigen
                </label>
            </div>
        </div>
        <p class="ad-hint" style="margin:-6px 0 14px;">Ohne Datum ist ein Thema automatisch ein Dauerläufer.
            Mit Datum erscheint es nur in der passenden Woche.</p>

        <div class="ad-row">
            <div class="ad-field" style="flex:2 1 320px;">
                <label for="link_url">Link (optional)</label>
                <input type="text" id="link_url" name="link_url" maxlength="500"
                       value="<?= Util::e((string) ($bearbeiten['link_url'] ?? '')) ?>"
                       placeholder="https://…">
            </div>
            <div class="ad-field" style="flex:1 1 200px;">
                <label for="link_label">Beschriftung</label>
                <input type="text" id="link_label" name="link_label" maxlength="120"
                       value="<?= Util::e((string) ($bearbeiten['link_label'] ?? '')) ?>"
                       placeholder="Anmelden">
            </div>
        </div>
        <div class="ad-field">
            <label for="image_url">Bild-Adresse (optional)</label>
            <input type="text" id="image_url" name="image_url" maxlength="500"
                   value="<?= Util::e((string) ($bearbeiten['image_url'] ?? '')) ?>"
                   placeholder="https://… – etwa aus dem Baukasten hochgeladen">
        </div>

        <div class="ad-actions">
            <button type="submit" class="ad-btn"><?= $bearbeiten !== null ? 'Speichern' : 'Thema hinzufügen' ?></button>
            <?php if ($bearbeiten !== null): ?>
                <a class="ad-btn ad-btn-secondary" href="wochennews.php">Abbrechen</a>
            <?php endif; ?>
        </div>
    </form>
</details>

<!-- ------------------------------------------------ Der Pool -->

<?php if ($alle === []): ?>
    <div class="ad-card">
        <p style="margin:0;">Noch keine Themen. Legen Sie oben Ihr erstes an – zum Beispiel das nächste
            Turnier oder ein Pro-Shop-Angebot. Danach genügt ein Klick für den ganzen Newsletter.</p>
    </div>
<?php else: ?>
    <?php foreach (Wochennews::CATEGORIES as $key => $label):
        $eintraege = $nachRubrik[$key];
        if ($eintraege === []) { continue; } ?>
        <div class="ad-card ad-card-tight">
            <h2 style="margin:0 0 10px;font-size:18px;"><?= Util::e($label) ?>
                <span class="ad-pill ad-pill-grey"><?= count($eintraege) ?></span></h2>
            <div class="ad-table-wrap">
                <table class="ad-table">
                    <tbody>
                    <?php foreach ($eintraege as $e):
                        $inaktiv = (int) $e['active'] === 0; ?>
                        <tr<?= $inaktiv ? ' style="opacity:.5;"' : '' ?>>
                            <td style="width:130px;white-space:nowrap;" class="ad-mono">
                                <?php if ((int) $e['evergreen'] === 1 && ($e['item_date'] ?? '') === ''): ?>
                                    <span class="ad-pill ad-pill-blue">läuft immer</span>
                                <?php else: ?>
                                    <?= Util::e(wn_datum($e['item_date'])) ?>
                                    <?php if (($e['date_until'] ?? '') !== ''): ?><br>bis <?= Util::e(wn_datum($e['date_until'])) ?><?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= Util::e((string) $e['title']) ?></strong>
                                <?php if ($inaktiv): ?><span class="ad-pill ad-pill-grey">pausiert</span><?php endif; ?>
                                <?php if (trim((string) $e['body']) !== ''): ?>
                                    <br><span class="ad-hint"><?= Util::e(Util::shorten((string) $e['body'], 90)) ?></span>
                                <?php endif; ?>
                            </td>
                            <td style="width:1%;white-space:nowrap;text-align:right;">
                                <a class="ad-btn ad-btn-secondary ad-btn-small" href="wochennews.php?bearbeiten=<?= (int) $e['id'] ?>#thema-form">Bearbeiten</a>
                                <details class="ad-menue">
                                    <summary aria-label="Weitere Aktionen">…</summary>
                                    <div class="ad-menue-liste">
                                        <form method="post">
                                            <?= Util::csrfField() ?>
                                            <input type="hidden" name="aktion" value="umschalten">
                                            <input type="hidden" name="id" value="<?= (int) $e['id'] ?>">
                                            <button type="submit"><?= $inaktiv ? 'Wieder aufnehmen' : 'Pausieren' ?></button>
                                        </form>
                                        <form method="post">
                                            <?= Util::csrfField() ?>
                                            <input type="hidden" name="aktion" value="loeschen">
                                            <input type="hidden" name="id" value="<?= (int) $e['id'] ?>">
                                            <button type="submit" class="ist-gefahr"
                                                    data-confirm="Dieses Thema wirklich löschen?">Löschen</button>
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
    <?php endforeach; ?>
<?php endif; ?>

<!-- ------------------------------------------------ Dauerinfos & Wetter -->

<details class="ad-card ad-klapp" id="dauerinfos" data-merken="wochen-dauerinfos">
    <summary>
        <h2>Dauerinfos &amp; Wetter</h2>
        <span class="ad-klapp-zeichen" aria-hidden="true"></span>
    </summary>

    <p class="ad-hint" style="margin-top:12px;">Diese Angaben stehen in jedem Wochennewsletter, ohne dass
        Sie sie neu eintragen müssen.</p>

    <form method="post" style="margin-top:8px;">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="dauerinfos">

        <div class="ad-row">
            <div class="ad-field" style="flex:1 1 280px;">
                <label for="wochen_platz">Platzstatus</label>
                <input type="text" id="wochen_platz" name="wochen_platz" maxlength="1000"
                       value="<?= Util::e(Settings::get('wochen_platz')) ?>"
                       placeholder="z. B. Sommergrüns, alle 18 Bahnen offen">
            </div>
        </div>
        <div class="ad-field">
            <label for="wochen_oeffnung">Öffnungszeiten</label>
            <textarea id="wochen_oeffnung" name="wochen_oeffnung" rows="3"
                      placeholder="Mo–Fr 8–20 Uhr&#10;Sa/So 7–20 Uhr"><?= Util::e(Settings::get('wochen_oeffnung')) ?></textarea>
        </div>
        <div class="ad-field">
            <label for="wochen_gruss">Grußformel am Ende (optional)</label>
            <input type="text" id="wochen_gruss" name="wochen_gruss" maxlength="500"
                   value="<?= Util::e(Settings::get('wochen_gruss')) ?>"
                   placeholder="Sonnige Grüße und bis bald auf der Runde">
        </div>
        <div class="ad-field">
            <label for="wochen_intro">Feste Einleitung (optional)</label>
            <textarea id="wochen_intro" name="wochen_intro" rows="2"
                      placeholder="Leer lassen: Dann formuliert das System die Einleitung selbst aus den Themen."><?= Util::e(Settings::get('wochen_intro')) ?></textarea>
        </div>

        <?php if (Ai::available()): ?>
            <label class="ad-check">
                <input type="checkbox" name="wochen_ki_intro" value="1"
                    <?= Settings::get('wochen_ki_intro') === '1' ? 'checked' : '' ?>>
                Einleitung vom Textassistenten schreiben lassen (wenn keine feste Einleitung gesetzt ist)
            </label>
        <?php endif; ?>

        <h3 style="margin:18px 0 4px;font-size:15px;">Wetter am Platz (optional)</h3>
        <p class="ad-hint" style="margin:0 0 10px;">Mit Koordinaten holt das System eine 7-Tage-Vorhersage
            (kostenlos, ohne Anmeldung). Ohne Angabe bleibt das Wetter einfach weg.</p>
        <div class="ad-row">
            <div class="ad-field" style="flex:1 1 200px;">
                <label for="wochen_wetter_ort">Ort (Anzeige)</label>
                <input type="text" id="wochen_wetter_ort" name="wochen_wetter_ort" maxlength="120"
                       value="<?= Util::e(Settings::get('wochen_wetter_ort')) ?>" placeholder="Ottobeuren">
            </div>
            <div class="ad-field" style="flex:0 1 150px;">
                <label for="wochen_wetter_lat">Breitengrad</label>
                <input type="text" id="wochen_wetter_lat" name="wochen_wetter_lat" maxlength="20"
                       value="<?= Util::e(Settings::get('wochen_wetter_lat')) ?>" placeholder="47.94">
            </div>
            <div class="ad-field" style="flex:0 1 150px;">
                <label for="wochen_wetter_lon">Längengrad</label>
                <input type="text" id="wochen_wetter_lon" name="wochen_wetter_lon" maxlength="20"
                       value="<?= Util::e(Settings::get('wochen_wetter_lon')) ?>" placeholder="10.30">
            </div>
        </div>
        <p class="ad-hint" style="margin:0 0 12px;">Die Koordinaten Ihres Platzes finden Sie, indem Sie ihn
            bei einer Kartenseite suchen und aus der Adresszeile ablesen.</p>

        <div class="ad-actions">
            <button type="submit" class="ad-btn ad-btn-secondary">Dauerinfos speichern</button>
        </div>
    </form>
</details>

<?php require __DIR__ . '/partials/footer.php'; ?>
