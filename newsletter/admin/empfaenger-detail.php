<?php
/**
 * empfaenger-detail.php – Einzelansicht: Stammdaten, Einwilligungen, Aktivität.
 */

$pageTitle = 'Empfänger';
require __DIR__ . '/partials/header.php';

$id  = Util::isPost() ? Util::postInt('id') : Util::getInt('id');
$sub = Subscribers::byId($id);
if ($sub === null) {
    Util::flash('Dieser Empfänger existiert nicht (mehr).', 'error');
    Util::redirect('empfaenger.php');
}

if (Util::isPost()) {
    Util::requireCsrf();
    if (!Auth::can('empfaenger')) {
        Util::flash('Dafür fehlt Ihnen die Berechtigung. Fragen Sie eine Administratorin oder einen Administrator.', 'error');
        Util::redirect('empfaenger.php');
    }
    $action = Util::post('aktion');

    if ($action === 'speichern') {
        DB::update('subscribers', [
            'first_name' => mb_substr(Util::post('first_name'), 0, 120),
            'last_name'  => mb_substr(Util::post('last_name'), 0, 120),
            'company'    => mb_substr(Util::post('company'), 0, 190),
            'salutation' => in_array(Util::post('salutation'), ['Herr', 'Frau'], true) ? Util::post('salutation') : '',
            'note'       => mb_substr(Util::postRaw('note'), 0, 2000),
        ], 'id = ?', [$id]);
        Subscribers::setLists($id, array_map('intval', (array) ($_POST['lists'] ?? [])));
        Util::flash('Gespeichert.');
        Util::redirect('empfaenger-detail.php?id=' . $id);
    }

    if ($action === 'doi_erneut') {
        try {
            SystemMails::sendDoubleOptIn($sub);
            Subscribers::logConsent($id, (string) $sub['email'], 'doi_resent', 'Bestätigungsmail erneut versendet');
            Util::flash('Die Bestätigungsmail wurde erneut verschickt.');
        } catch (Throwable $e) {
            Util::flash('Versand fehlgeschlagen: ' . Util::e($e->getMessage()), 'error');
        }
        Util::redirect('empfaenger-detail.php?id=' . $id);
    }

    if ($action === 'abmelden') {
        Subscribers::unsubscribe($sub, 'Abmeldung durch die Verwaltung', null, false);
        Util::flash('Empfänger abgemeldet.');
        Util::redirect('empfaenger-detail.php?id=' . $id);
    }

    if ($action === 'aktivieren') {
        DB::update('subscribers', [
            'status'       => Subscribers::STATUS_ACTIVE,
            'confirmed_at' => $sub['confirmed_at'] ?: Util::now(),
            'bounce_count' => 0,
        ], 'id = ?', [$id]);
        Subscribers::unsuppress((string) $sub['email']);
        Subscribers::logConsent($id, (string) $sub['email'], 'admin_activate', 'Durch die Verwaltung aktiviert');
        Util::flash('Empfänger ist wieder aktiv und von der Sperrliste genommen.');
        Util::redirect('empfaenger-detail.php?id=' . $id);
    }

    if ($action === 'sperren') {
        Subscribers::suppress((string) $sub['email'], 'manuell', 'Durch die Verwaltung gesperrt');
        Util::flash('Adresse gesperrt.');
        Util::redirect('empfaenger-detail.php?id=' . $id);
    }

    if ($action === 'loeschen') {
        $email = (string) $sub['email'];
        Subscribers::deleteCompletely($id, Util::post('sperren') === '1');
        Log::info('dsgvo', 'Empfänger durch die Verwaltung gelöscht: ' . $email);
        Util::flash('Empfänger vollständig gelöscht.');
        Util::redirect('empfaenger.php');
    }

    $sub = Subscribers::byId($id);
}

$memberOf = Subscribers::listIds($id);
$consent  = Subscribers::consentLog($id);
$events   = Events::forSubscriber($id, 40);
$blocked  = Subscribers::isSuppressed((string) $sub['email']);
?>

<div class="ad-page-head">
    <div>
        <h1><?= Util::e((string) $sub['email']) ?></h1>
        <p class="ad-sub">
            <?= subscriber_status_pill((string) $sub['status']) ?>
            <?php if ($blocked): ?><span class="ad-pill ad-pill-red">Sperrliste</span><?php endif; ?>
            · angemeldet <?= Util::e(Util::dt((string) $sub['created_at'])) ?>
        </p>
    </div>
    <a class="ad-btn ad-btn-secondary" href="empfaenger.php">Zurück zur Liste</a>
</div>

<div class="ad-editor-grid">
    <div>
        <div class="ad-card">
            <h2>Stammdaten</h2>
            <form method="post">
                <?= Util::csrfField() ?>
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="aktion" value="speichern">

                <div class="ad-row">
                    <div class="ad-field">
                        <label for="salutation">Anrede</label>
                        <select id="salutation" name="salutation">
                            <option value="">— keine —</option>
                            <option value="Frau" <?= $sub['salutation'] === 'Frau' ? 'selected' : '' ?>>Frau</option>
                            <option value="Herr" <?= $sub['salutation'] === 'Herr' ? 'selected' : '' ?>>Herr</option>
                        </select>
                    </div>
                    <div class="ad-field">
                        <label for="first_name">Vorname</label>
                        <input type="text" id="first_name" name="first_name" value="<?= Util::e((string) $sub['first_name']) ?>">
                    </div>
                    <div class="ad-field">
                        <label for="last_name">Nachname</label>
                        <input type="text" id="last_name" name="last_name" value="<?= Util::e((string) $sub['last_name']) ?>">
                    </div>
                </div>

                <div class="ad-field">
                    <label for="company">Unternehmen</label>
                    <input type="text" id="company" name="company" value="<?= Util::e((string) $sub['company']) ?>">
                </div>

                <div class="ad-field">
                    <span class="ad-label">Listen</span>
                    <?php foreach (Lists::all() as $list): ?>
                        <label class="ad-check">
                            <input type="checkbox" name="lists[]" value="<?= (int) $list['id'] ?>"
                                <?= in_array((int) $list['id'], $memberOf, true) ? 'checked' : '' ?>>
                            <span><?= Util::e((string) $list['name']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="ad-field">
                    <label for="note">Interne Notiz</label>
                    <textarea id="note" name="note" rows="3"><?= Util::e((string) $sub['note']) ?></textarea>
                </div>

                <button type="submit" class="ad-btn">Speichern</button>
            </form>
        </div>

        <div class="ad-card">
            <h2>Einwilligungs-Protokoll</h2>
            <p class="ad-hint" style="margin-top:-6px;">Nachweis nach DSGVO Art. 7 Abs. 1 – wer wann wie eingewilligt hat.</p>
            <?php if ($consent === []): ?>
                <div class="ad-empty">Keine Einträge.</div>
            <?php else: ?>
                <div class="ad-table-wrap" style="margin-bottom:0;">
                    <table class="ad-table">
                        <thead><tr><th>Zeitpunkt</th><th>Ereignis</th><th>Details</th><th>IP</th></tr></thead>
                        <tbody>
                        <?php foreach ($consent as $row): ?>
                            <tr>
                                <td><?= Util::e(Util::dt((string) $row['created_at'])) ?></td>
                                <td><span class="ad-pill ad-pill-grey"><?= Util::e((string) $row['event']) ?></span></td>
                                <td class="ad-hint"><?= Util::e(Util::shorten((string) $row['detail'], 90)) ?></td>
                                <td class="ad-mono"><?= Util::e((string) $row['ip']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="ad-card">
            <h2>Aktivität</h2>
            <?php if ($events === []): ?>
                <div class="ad-empty">Noch keine Mails an diese Adresse.</div>
            <?php else: ?>
                <div class="ad-table-wrap" style="margin-bottom:0;">
                    <table class="ad-table">
                        <thead><tr><th>Zeitpunkt</th><th>Ereignis</th><th>Newsletter</th></tr></thead>
                        <tbody>
                        <?php foreach ($events as $row): ?>
                            <tr>
                                <td><?= Util::e(Util::dt((string) $row['created_at'])) ?></td>
                                <td><?= Util::e(Events::label((string) $row['type'])) ?></td>
                                <td class="ad-hint"><?= Util::e((string) ($row['campaign_name'] ?? '—')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <div class="ad-card">
            <h2>Status</h2>
            <table class="ad-table">
                <tr><th>Status</th><td><?= subscriber_status_pill((string) $sub['status']) ?></td></tr>
                <tr><th>Bestätigt</th><td><?= Util::e(Util::dt((string) $sub['confirmed_at'])) ?></td></tr>
                <tr><th>Abgemeldet</th><td><?= Util::e(Util::dt((string) $sub['unsubscribed_at'])) ?></td></tr>
                <tr><th>Letzte Mail</th><td><?= Util::e(Util::dt((string) $sub['last_sent_at'])) ?></td></tr>
                <tr><th>Quelle</th><td><?= Util::e((string) ($sub['source'] ?: '—')) ?></td></tr>
                <tr><th>Bounces</th><td><?= (int) $sub['bounce_count'] ?></td></tr>
                <tr><th>IP Anmeldung</th><td class="ad-mono"><?= Util::e((string) ($sub['signup_ip'] ?: '—')) ?></td></tr>
                <tr><th>IP Bestätigung</th><td class="ad-mono"><?= Util::e((string) ($sub['confirm_ip'] ?: '—')) ?></td></tr>
            </table>
        </div>

        <div class="ad-card">
            <h2>Aktionen</h2>
            <form method="post" style="margin-bottom:10px;">
                <?= Util::csrfField() ?>
                <input type="hidden" name="id" value="<?= $id ?>">
                <?php if ($sub['status'] === Subscribers::STATUS_PENDING): ?>
                    <button type="submit" name="aktion" value="doi_erneut" class="ad-btn ad-btn-secondary">Bestätigungsmail erneut senden</button>
                <?php endif; ?>
                <?php if ($sub['status'] !== Subscribers::STATUS_ACTIVE): ?>
                    <button type="submit" name="aktion" value="aktivieren" class="ad-btn ad-btn-secondary"
                            data-confirm="Nur aktivieren, wenn eine Einwilligung nachweisbar ist. Fortfahren?">Als aktiv markieren</button>
                <?php else: ?>
                    <button type="submit" name="aktion" value="abmelden" class="ad-btn ad-btn-secondary"
                            data-confirm="Empfänger abmelden?">Abmelden</button>
                <?php endif; ?>
                <?php if (!$blocked): ?>
                    <button type="submit" name="aktion" value="sperren" class="ad-btn ad-btn-secondary"
                            data-confirm="Adresse dauerhaft sperren?">Adresse sperren</button>
                <?php endif; ?>
            </form>

            <form method="post" style="border-top:1px solid var(--ad-border);padding-top:14px;">
                <?= Util::csrfField() ?>
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="aktion" value="loeschen">
                <label class="ad-check">
                    <input type="checkbox" name="sperren" value="1" checked>
                    <span>Adresse anschließend sperren, damit sie nicht erneut importiert wird</span>
                </label>
                <button type="submit" class="ad-btn ad-btn-danger"
                        data-confirm="Alle Daten dieses Empfängers unwiderruflich löschen?">Vollständig löschen (DSGVO)</button>
            </form>
        </div>

        <div class="ad-card">
            <h2>Persönliche Links</h2>
            <p class="ad-hint">Diese Links stehen auch in jeder Mail – hier zum Prüfen.</p>
            <p class="ad-mono" style="word-break:break-all;font-size:12px;">
                <a href="<?= Util::e(Urls::preferences((string) $sub['token'])) ?>" target="_blank" rel="noopener">Einstellungen</a><br>
                <a href="<?= Util::e(Urls::unsubscribe((string) $sub['token'])) ?>" target="_blank" rel="noopener">Abmeldeseite</a>
            </p>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php';
