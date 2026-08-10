<?php
/**
 * benutzer.php – Zugänge und Rollen verwalten.
 *
 * Drei Rollen:
 *   Administrator – darf alles, auch Einstellungen und Zugänge
 *   Redakteur     – Newsletter, Vorlagen, Automationen, Empfänger
 *   Betrachter    – darf alles ansehen, aber nichts ändern
 *
 * Schutz vor dem Aussperren: Der letzte aktive Administrator lässt sich
 * weder löschen noch sperren noch herabstufen.
 *
 * Die Seite steht allen offen – wer das Recht „benutzer" nicht hat, sieht
 * hier nur die eigene Rolle und kann das eigene Passwort ändern.
 */

require_once dirname(__DIR__) . '/lib/bootstrap.php';

$darfVerwalten = Auth::can('benutzer');
$pageTitle     = $darfVerwalten ? 'Benutzer' : 'Mein Zugang';
$requiredRight = 'lesen';
require __DIR__ . '/partials/header.php';


/** Verhindert, dass sich das System selbst aussperrt. */
function letzter_admin(int $userId): bool
{
    $user = DB::row('SELECT role, status FROM users WHERE id = ?', [$userId]);
    if ($user === null || $user['role'] !== 'admin' || $user['status'] !== 'active') {
        return false;
    }
    return Auth::adminCount() <= 1;
}

if (Util::isPost()) {
    Util::requireCsrf();
    $action = Util::post('aktion');
    $userId = Util::postInt('user_id');

    // Fremde Zugänge darf nur bearbeiten, wer das Recht dazu hat.
    if ($action !== 'eigenes_passwort' && !$darfVerwalten) {
        Util::flash('Dafür fehlt Ihnen die Berechtigung. Fragen Sie eine Administratorin oder einen Administrator.', 'error');
        Util::redirect('benutzer.php');
    }

    if ($action === 'anlegen') {
        try {
            $neu = Auth::createUser(
                Util::post('email'),
                Util::postRaw('passwort'),
                Util::post('name'),
                Util::post('role')
            );
            Log::info('auth', 'Zugang angelegt: ' . Util::post('email') . ' (' . Util::post('role') . ') durch ' . $currentUser['email']);
            Util::flash('Zugang für <strong>' . Util::e(Util::post('email')) . '</strong> angelegt.');
        } catch (Throwable $e) {
            Util::flash(Util::e($e->getMessage()), 'error');
        }
        Util::redirect('benutzer.php');
    }

    if ($action === 'speichern' && $userId > 0) {
        $rolle  = Util::post('role');
        $status = Util::post('status') === 'disabled' ? 'disabled' : 'active';

        if (!isset(Auth::ROLES[$rolle])) {
            $rolle = 'betrachter';
        }
        if (letzter_admin($userId) && ($rolle !== 'admin' || $status !== 'active')) {
            Util::flash('Das ist der letzte aktive Administrator – Rolle und Status bleiben unverändert, '
                . 'sonst käme niemand mehr in die Einstellungen.', 'error');
            Util::redirect('benutzer.php');
        }

        DB::update('users', [
            'name'   => mb_substr(Util::post('name'), 0, 190),
            'role'   => $rolle,
            'status' => $status,
        ], 'id = ?', [$userId]);
        Log::info('auth', 'Zugang geändert: #' . $userId . ' → ' . $rolle . '/' . $status . ' durch ' . $currentUser['email']);
        Util::flash('Zugang aktualisiert.');
        Util::redirect('benutzer.php');
    }

    if ($action === 'passwort_setzen' && $userId > 0) {
        try {
            Auth::setPassword($userId, Util::postRaw('passwort'));
            Log::info('auth', 'Passwort gesetzt für #' . $userId . ' durch ' . $currentUser['email']);
            Util::flash('Neues Passwort gespeichert. Bitte teilen Sie es der Person auf sicherem Weg mit.');
        } catch (Throwable $e) {
            Util::flash(Util::e($e->getMessage()), 'error');
        }
        Util::redirect('benutzer.php');
    }

    if ($action === 'loeschen' && $userId > 0) {
        if ($userId === (int) $currentUser['id']) {
            Util::flash('Den eigenen Zugang können Sie nicht löschen.', 'error');
        } elseif (letzter_admin($userId)) {
            Util::flash('Der letzte aktive Administrator kann nicht gelöscht werden.', 'error');
        } else {
            $email = (string) DB::value('SELECT email FROM users WHERE id = ?', [$userId], '');
            DB::delete('users', 'id = ?', [$userId]);
            Log::info('auth', 'Zugang gelöscht: ' . $email . ' durch ' . $currentUser['email']);
            Util::flash('Zugang gelöscht.');
        }
        Util::redirect('benutzer.php');
    }

    if ($action === 'eigenes_passwort') {
        try {
            if (!Auth::verifyPassword((int) $currentUser['id'], Util::postRaw('passwort_alt'))) {
                throw new InvalidArgumentException('Das bisherige Passwort stimmt nicht.');
            }
            Auth::setPassword((int) $currentUser['id'], Util::postRaw('passwort_neu'));
            Util::flash('Ihr Passwort wurde geändert.');
        } catch (Throwable $e) {
            Util::flash(Util::e($e->getMessage()), 'error');
        }
        Util::redirect('benutzer.php');
    }
}

$users = $darfVerwalten
    ? DB::all('SELECT id, email, name, role, status, created_at, last_login_at FROM users ORDER BY role, email')
    : [];
$adminZahl = Auth::adminCount();
?>

<div class="ad-page-head">
    <div>
        <h1><?= Util::e($pageTitle) ?></h1>
        <?php if ($darfVerwalten): ?>
            <p class="ad-sub"><?= Util::num(count($users)) ?> <?= count($users) === 1 ? 'Zugang' : 'Zugänge' ?>
                · <?= $adminZahl === 1 ? '1 aktiver Administrator' : Util::num($adminZahl) . ' aktive Administratoren' ?></p>
        <?php else: ?>
            <p class="ad-sub">Ihre Rolle und Ihr Passwort</p>
        <?php endif; ?>
    </div>
</div>

<?php if (!$darfVerwalten): ?>
    <div class="ad-card">
        <h2 style="margin-top:0;">Ihr Zugang</h2>
        <p><strong><?= Util::e((string) ($currentUser['name'] ?: $currentUser['email'])) ?></strong>
            – <?= Util::e((string) $currentUser['email']) ?></p>
        <p>Rolle: <span class="ad-pill ad-pill-grey"><?= Util::e(Auth::roleLabel((string) $currentUser['role'])) ?></span></p>
        <p class="ad-hint">Damit dürfen Sie:</p>
        <ul class="ad-hint" style="margin:4px 0 0 18px;padding:0;">
            <?php foreach (Auth::rightsOf((string) $currentUser['role']) as $recht): ?>
                <li><?= Util::e(Auth::RIGHT_LABELS[$recht] ?? $recht) ?></li>
            <?php endforeach; ?>
        </ul>
        <p class="ad-hint" style="margin-top:12px;">Zugänge anlegen und Rollen ändern kann nur ein Administrator.</p>
    </div>
<?php endif; ?>

<?php if ($darfVerwalten): ?>
<div class="ad-table-wrap">
    <table class="ad-table">
        <thead>
        <tr>
            <th>Adresse</th>
            <th>Name</th>
            <th>Rolle</th>
            <th>Status</th>
            <th>Letzte Anmeldung</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user):
            $istIch = (int) $user['id'] === (int) $currentUser['id']; ?>
            <tr>
                <td class="ad-mono">
                    <?= Util::e((string) $user['email']) ?>
                    <?php if ($istIch): ?><span class="ad-pill ad-pill-blue">Sie</span><?php endif; ?>
                </td>
                <td><?= Util::e((string) $user['name'] ?: '—') ?></td>
                <td>
                    <span class="ad-pill <?= $user['role'] === 'admin' ? 'ad-pill-blue' : 'ad-pill-grey' ?>">
                        <?= Util::e(Auth::roleLabel((string) $user['role'])) ?>
                    </span>
                </td>
                <td>
                    <span class="ad-pill <?= $user['status'] === 'active' ? 'ad-pill-green' : 'ad-pill-red' ?>">
                        <?= $user['status'] === 'active' ? 'aktiv' : 'gesperrt' ?>
                    </span>
                </td>
                <td><?= Util::e(Util::dt((string) $user['last_login_at'])) ?></td>
                <td>
                    <a class="ad-btn ad-btn-secondary ad-btn-small" href="benutzer.php?bearbeiten=<?= (int) $user['id'] ?>">Bearbeiten</a>
                </td>
            </tr>

            <?php if (Util::getInt('bearbeiten') === (int) $user['id']): ?>
                <tr>
                    <td colspan="6" style="background:#FAFBFD;">
                        <div class="ad-grid-2" style="padding:8px 0;">
                            <form method="post">
                                <?= Util::csrfField() ?>
                                <input type="hidden" name="aktion" value="speichern">
                                <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                <h3 style="margin-top:0;">Zugang bearbeiten</h3>

                                <div class="ad-field">
                                    <label for="name<?= (int) $user['id'] ?>">Name</label>
                                    <input type="text" id="name<?= (int) $user['id'] ?>" name="name"
                                           value="<?= Util::e((string) $user['name']) ?>">
                                </div>

                                <div class="ad-field">
                                    <label for="role<?= (int) $user['id'] ?>">Rolle</label>
                                    <select id="role<?= (int) $user['id'] ?>" name="role">
                                        <?php foreach (Auth::ROLES as $key => $label): ?>
                                            <option value="<?= Util::e($key) ?>" <?= $user['role'] === $key ? 'selected' : '' ?>>
                                                <?= Util::e($label) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="ad-field">
                                    <label for="status<?= (int) $user['id'] ?>">Status</label>
                                    <select id="status<?= (int) $user['id'] ?>" name="status">
                                        <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>aktiv</option>
                                        <option value="disabled" <?= $user['status'] !== 'active' ? 'selected' : '' ?>>gesperrt</option>
                                    </select>
                                    <p class="ad-hint">Gesperrte Zugänge können sich nicht anmelden, bleiben aber erhalten.</p>
                                </div>

                                <div class="ad-actions">
                                    <button type="submit" class="ad-btn">Speichern</button>
                                    <a class="ad-btn ad-btn-secondary" href="benutzer.php">Abbrechen</a>
                                </div>
                            </form>

                            <div>
                                <form method="post">
                                    <?= Util::csrfField() ?>
                                    <input type="hidden" name="aktion" value="passwort_setzen">
                                    <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                    <h3 style="margin-top:0;">Neues Passwort vergeben</h3>
                                    <div class="ad-field">
                                        <label for="pw<?= (int) $user['id'] ?>">Passwort</label>
                                        <input type="text" id="pw<?= (int) $user['id'] ?>" name="passwort"
                                               autocomplete="new-password" placeholder="mind. 10 Zeichen, Buchstaben und Ziffern">
                                        <p class="ad-hint">Wird im Klartext angezeigt, damit Sie es weitergeben können.
                                            Bitte auf sicherem Weg übermitteln.</p>
                                    </div>
                                    <button type="submit" class="ad-btn ad-btn-secondary">Passwort setzen</button>
                                </form>

                                <?php if (!$istIch): ?>
                                    <form method="post" style="margin-top:18px;padding-top:14px;border-top:1px solid var(--ad-border);">
                                        <?= Util::csrfField() ?>
                                        <input type="hidden" name="aktion" value="loeschen">
                                        <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                        <button type="submit" class="ad-btn ad-btn-danger"
                                                data-confirm="Zugang wirklich löschen?">Zugang löschen</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="ad-grid-2">
    <div class="ad-card">
        <h2 style="margin-top:0;">Neuen Zugang anlegen</h2>
        <form method="post">
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="anlegen">
            <div class="ad-row">
                <div class="ad-field">
                    <label for="neu_email">E-Mail-Adresse</label>
                    <input type="email" id="neu_email" name="email" required>
                </div>
                <div class="ad-field">
                    <label for="neu_name">Name</label>
                    <input type="text" id="neu_name" name="name">
                </div>
            </div>
            <div class="ad-row">
                <div class="ad-field">
                    <label for="neu_role">Rolle</label>
                    <select id="neu_role" name="role">
                        <?php foreach (Auth::ROLES as $key => $label): ?>
                            <option value="<?= Util::e($key) ?>" <?= $key === 'redakteur' ? 'selected' : '' ?>>
                                <?= Util::e($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="ad-field">
                    <label for="neu_passwort">Passwort</label>
                    <input type="text" id="neu_passwort" name="passwort" required autocomplete="new-password"
                           placeholder="mind. 10 Zeichen, Buchstaben und Ziffern">
                </div>
            </div>
            <button type="submit" class="ad-btn">Zugang anlegen</button>
        </form>
    </div>

    <div class="ad-card">
        <h2 style="margin-top:0;">Was die Rollen dürfen</h2>
        <table class="ad-table">
            <thead><tr><th>Rolle</th><th>Rechte</th></tr></thead>
            <tbody>
            <?php foreach (Auth::ROLES as $key => $label): ?>
                <tr>
                    <td><strong><?= Util::e($label) ?></strong></td>
                    <td class="ad-hint">
                        <?php foreach (Auth::rightsOf($key) as $recht): ?>
                            · <?= Util::e(Auth::RIGHT_LABELS[$recht] ?? $recht) ?><br>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p class="ad-hint">Der letzte aktive Administrator lässt sich nicht löschen, sperren oder herabstufen –
            sonst käme niemand mehr in die Einstellungen.</p>
    </div>
</div>
<?php endif; ?>

<div class="ad-card">
    <h2 style="margin-top:0;">Eigenes Passwort ändern</h2>
    <form method="post">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="eigenes_passwort">
        <div class="ad-row">
            <div class="ad-field">
                <label for="passwort_alt">Bisheriges Passwort</label>
                <input type="password" id="passwort_alt" name="passwort_alt" required autocomplete="current-password">
            </div>
            <div class="ad-field">
                <label for="passwort_neu">Neues Passwort</label>
                <input type="password" id="passwort_neu" name="passwort_neu" required autocomplete="new-password">
            </div>
            <div class="ad-field" style="flex:0;">
                <label>&nbsp;</label>
                <button type="submit" class="ad-btn ad-btn-secondary">Ändern</button>
            </div>
        </div>
    </form>
</div>

<?php require __DIR__ . '/partials/footer.php';
