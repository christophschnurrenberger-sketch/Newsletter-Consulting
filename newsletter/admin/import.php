<?php
/**
 * import.php – Empfänger aus einer CSV-Datei übernehmen.
 */

$pageTitle = 'Import';
require __DIR__ . '/partials/header.php';

$result  = null;
$preview = [];

if (Util::isPost()) {
    Util::requireCsrf();
    if (!Auth::can('empfaenger')) {
        Util::flash('Dafür fehlt Ihnen die Berechtigung. Fragen Sie eine Administratorin oder einen Administrator.', 'error');
        Util::redirect('import.php');
    }

    $content = '';
    if (isset($_FILES['datei']) && is_array($_FILES['datei']) && (int) $_FILES['datei']['error'] === UPLOAD_ERR_OK) {
        if ((int) $_FILES['datei']['size'] > 5 * 1024 * 1024) {
            Util::flash('Die Datei ist größer als 5 MB. Bitte teilen Sie sie auf.', 'error');
            Util::redirect('import.php');
        }
        $content = (string) file_get_contents((string) $_FILES['datei']['tmp_name']);
    } elseif (Util::postRaw('einfuegen') !== '') {
        $content = Util::postRaw('einfuegen');
    }

    if (trim($content) === '') {
        Util::flash('Bitte wählen Sie eine CSV-Datei aus oder fügen Sie Adressen ein.', 'error');
        Util::redirect('import.php');
    }

    // Zeichensatz vereinheitlichen (Excel speichert oft ISO-8859-1)
    if (!mb_check_encoding($content, 'UTF-8')) {
        $content = mb_convert_encoding($content, 'UTF-8', 'ISO-8859-1, Windows-1252');
    }

    $rows    = Subscribers::parseCsv($content);
    $listIds = array_map('intval', (array) ($_POST['lists'] ?? []));
    $status  = Util::post('status') === 'active' ? Subscribers::STATUS_ACTIVE : Subscribers::STATUS_PENDING;

    if (Util::post('aktion') === 'pruefen') {
        $preview = array_slice($rows, 0, 15);
        $total   = count($rows);
    } else {
        if ($status === Subscribers::STATUS_ACTIVE && Util::post('bestaetigung') !== '1') {
            Util::flash('Für einen Import als „aktiv“ müssen Sie die Einwilligung bestätigen.', 'error');
            Util::redirect('import.php');
        }
        $result = Subscribers::import($rows, $listIds, $status, 'import ' . date('d.m.Y'));
        Log::info('import', sprintf('Import: %d neu, %d aktualisiert, %d übersprungen',
            $result['imported'], $result['updated'], $result['skipped']));
    }
}
?>

<div class="ad-page-head">
    <div>
        <h1>Empfänger importieren</h1>
        <p class="ad-sub">CSV-Datei hochladen oder Adressen einfügen</p>
    </div>
    <a class="ad-btn ad-btn-secondary" href="empfaenger.php">Zur Empfängerliste</a>
</div>

<?php if ($result !== null): ?>
    <div class="ad-flash ad-flash-success">
        <strong>Import abgeschlossen:</strong>
        <?= Util::num($result['imported']) ?> neu angelegt,
        <?= Util::num($result['updated']) ?> aktualisiert,
        <?= Util::num($result['skipped']) ?> übersprungen.
    </div>
    <?php if ($result['errors'] !== []): ?>
        <div class="ad-card">
            <h2>Übersprungene Zeilen</h2>
            <ul class="ad-hint" style="margin:0;padding-left:18px;">
                <?php foreach ($result['errors'] as $error): ?>
                    <li><?= Util::e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if ($preview !== []): ?>
    <div class="ad-card">
        <h2>Vorschau – erkannte Spalten</h2>
        <p class="ad-hint">Insgesamt <?= Util::num($total ?? 0) ?> Zeilen erkannt. Die ersten 15:</p>
        <div class="ad-table-wrap" style="margin-bottom:0;">
            <table class="ad-table">
                <thead><tr><th>E-Mail</th><th>Vorname</th><th>Nachname</th><th>Unternehmen</th></tr></thead>
                <tbody>
                <?php foreach ($preview as $row): ?>
                    <tr>
                        <td class="ad-mono"><?= Util::e((string) ($row['email'] ?? '')) ?></td>
                        <td><?= Util::e((string) ($row['first_name'] ?? '')) ?></td>
                        <td><?= Util::e((string) ($row['last_name'] ?? '')) ?></td>
                        <td><?= Util::e((string) ($row['company'] ?? '')) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<div class="ad-card">
    <form method="post" enctype="multipart/form-data">
        <?= Util::csrfField() ?>

        <div class="ad-field">
            <label for="datei">CSV-Datei</label>
            <input type="file" id="datei" name="datei" accept=".csv,text/csv,text/plain">
            <p class="ad-hint">Erste Zeile als Kopfzeile mit den Spalten <code>email</code>, <code>vorname</code>,
                <code>nachname</code>, <code>firma</code> (auch englische Bezeichnungen werden erkannt).
                Trennzeichen Semikolon oder Komma.</p>
        </div>

        <div class="ad-field">
            <label for="einfuegen">…oder Adressen einfügen</label>
            <textarea id="einfuegen" name="einfuegen" rows="6" class="ad-code"
                      placeholder="email;vorname;nachname&#10;maria@example.de;Maria;Muster"></textarea>
        </div>

        <div class="ad-field">
            <span class="ad-label">In diese Listen aufnehmen</span>
            <?php foreach (Lists::all() as $list): ?>
                <label class="ad-check">
                    <input type="checkbox" name="lists[]" value="<?= (int) $list['id'] ?>"
                        <?= (int) $list['is_default'] === 1 ? 'checked' : '' ?>>
                    <span><?= Util::e((string) $list['name']) ?></span>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="ad-field">
            <label for="status">Wie sollen die Adressen behandelt werden?</label>
            <select id="status" name="status">
                <option value="pending">Bestätigungsmail verschicken (Double-Opt-in) – empfohlen</option>
                <option value="active">Direkt aktiv setzen – Einwilligung liegt dokumentiert vor</option>
            </select>
        </div>

        <label class="ad-check">
            <input type="checkbox" name="bestaetigung" value="1">
            <span>Ich bestätige: Für alle importierten Adressen liegt eine nachweisbare Einwilligung vor
                (nur nötig beim Import als „aktiv“).</span>
        </label>

        <div class="ad-actions">
            <button type="submit" name="aktion" value="pruefen" class="ad-btn ad-btn-secondary">Erst prüfen</button>
            <button type="submit" name="aktion" value="importieren" class="ad-btn"
                    data-confirm="Import jetzt starten?">Importieren</button>
        </div>
    </form>
</div>

<div class="ad-card">
    <h2>Rechtlicher Hinweis</h2>
    <p>Gekaufte oder recherchierte Adressen dürfen Sie <strong>nicht</strong> anschreiben. Zulässig ist nur, was
        Sie belegen können: eine Anmeldung mit Bestätigung, eine schriftliche Einwilligung oder – eng begrenzt –
        Bestandskunden nach § 7 Abs. 3 UWG.</p>
    <p>Beim Import mit „Bestätigungsmail verschicken“ holen Sie die Einwilligung sauber nach; das ist der
        sichere Weg, wenn Sie sich nicht sicher sind.</p>
</div>

<?php require __DIR__ . '/partials/footer.php';
