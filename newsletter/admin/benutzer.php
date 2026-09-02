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

    /*
     * Fremde Zugänge darf nur bearbeiten, wer das Recht dazu hat. Am eigenen
     * Zugang darf jede und jeder arbeiten – Passwort und zweiter Faktor
     * gehören der Person, nicht der Verwaltung.
     */
    $amEigenenZugang = ['eigenes_passwort', 'totp_start', 'totp_bestaetigen',
                        'totp_neue_codes', 'totp_aus'];
    if (!in_array($action, $amEigenenZugang, true) && !$darfVerwalten) {
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
            // Der eigene Browser darf bleiben – alle anderen sind jetzt draußen.
            Auth::eigeneSitzungBehalten();
            Util::flash('Ihr Passwort wurde geändert. Alle anderen Anmeldungen wurden beendet.');
        } catch (Throwable $e) {
            Util::flash(Util::e($e->getMessage()), 'error');
        }
        Util::redirect('benutzer.php');
    }

    /* ------------------------------------------------- Zweiter Faktor */

    if ($action === 'totp_start') {
        // Geheimnis vormerken; scharf wird es erst mit der ersten richtigen Zahl
        Auth::totpVormerken((int) $currentUser['id'], Totp::neuesGeheimnis());
        Util::redirect('benutzer.php?einrichten=1#zweifaktor');
    }

    if ($action === 'totp_bestaetigen') {
        $geheim = Auth::totpGeheimnis((int) $currentUser['id']);
        if ($geheim === '' || !Totp::pruefe($geheim, Util::post('code'))) {
            Util::flash('Die Zahl stimmt nicht. Bitte prüfen Sie die Uhrzeit auf Ihrem Telefon '
                . 'und geben Sie die aktuelle Zahl ein.', 'error');
            Util::redirect('benutzer.php?einrichten=1#zweifaktor');
        }
        $codes = Totp::ersatzcodes();
        Auth::totpBestaetigen((int) $currentUser['id'], $codes);
        // Genau einmal anzeigen – danach sind nur noch die Hashes da
        $_SESSION['ersatzcodes'] = $codes;
        Util::flash('Die Zwei-Faktor-Anmeldung ist eingeschaltet.');
        Util::redirect('benutzer.php#zweifaktor');
    }

    if ($action === 'totp_neue_codes') {
        if (!Auth::verifyPassword((int) $currentUser['id'], Util::postRaw('passwort'))) {
            Util::flash('Das Passwort stimmt nicht.', 'error');
            Util::redirect('benutzer.php#zweifaktor');
        }
        $codes = Totp::ersatzcodes();
        Auth::totpBestaetigen((int) $currentUser['id'], $codes);
        $_SESSION['ersatzcodes'] = $codes;
        Util::flash('Neue Ersatzcodes erzeugt. Die alten gelten nicht mehr.');
        Util::redirect('benutzer.php#zweifaktor');
    }

    if ($action === 'totp_aus') {
        if (!Auth::verifyPassword((int) $currentUser['id'], Util::postRaw('passwort'))) {
            Util::flash('Das Passwort stimmt nicht.', 'error');
            Util::redirect('benutzer.php#zweifaktor');
        }
        Auth::totpAbschalten((int) $currentUser['id']);
        Util::flash('Die Zwei-Faktor-Anmeldung ist ausgeschaltet.', 'warning');
        Util::redirect('benutzer.php#zweifaktor');
    }

    /*
     * Notausgang: Ein Administrator schaltet den zweiten Faktor für einen
     * anderen Zugang ab – etwa wenn das Telefon weg ist und die Ersatzcodes
     * auch. Das steht im Protokoll.
     */
    if ($action === 'totp_fremd_aus' && $userId > 0) {
        $wer = (string) DB::value('SELECT email FROM users WHERE id = ?', [$userId], '');
        Auth::totpAbschalten($userId);
        Log::warn('auth', 'Zwei-Faktor-Anmeldung für ' . $wer . ' abgeschaltet durch '
            . $currentUser['email'] . '.');
        Util::flash('Zwei-Faktor-Anmeldung für <strong>' . Util::e($wer) . '</strong> abgeschaltet. '
            . 'Bitte weisen Sie die Person darauf hin, sie neu einzurichten.', 'warning');
        Util::redirect('benutzer.php');
    }
}

$users = $darfVerwalten
    ? DB::all('SELECT id, email, name, role, status, totp_secret, totp_confirmed_at,
                     created_at, last_login_at FROM users ORDER BY role, email')
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
            <th>2 Faktoren</th>
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
                <td>
                    <?php if (Auth::hatZweitenFaktor($user)): ?>
                        <span class="ad-pill ad-pill-green">an</span>
                    <?php else: ?>
                        <span class="ad-pill ad-pill-grey">aus</span>
                    <?php endif; ?>
                </td>
                <td><?= Util::e(Util::dt((string) $user['last_login_at'])) ?></td>
                <td>
                    <a class="ad-btn ad-btn-secondary ad-btn-small" href="benutzer.php?bearbeiten=<?= (int) $user['id'] ?>">Bearbeiten</a>
                    <?php if (!$istIch && Auth::hatZweitenFaktor($user)): ?>
                        <details class="ad-menue">
                            <summary aria-label="Weitere Aktionen">…</summary>
                            <div class="ad-menue-liste">
                                <form method="post">
                                    <?= Util::csrfField() ?>
                                    <input type="hidden" name="aktion" value="totp_fremd_aus">
                                    <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                    <button type="submit" class="ist-gefahr"
                                            data-confirm="Zwei-Faktor-Anmeldung für diesen Zugang abschalten? Nur tun, wenn die Person ihr Telefon und ihre Ersatzcodes verloren hat.">
                                        Zwei-Faktor-Anmeldung abschalten</button>
                                </form>
                            </div>
                        </details>
                    <?php endif; ?>
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

<!-- ------------------------------------------------- Zweiter Faktor -->

<?php
$zweiAn      = Auth::hatZweitenFaktor($currentUser);
$einrichten  = Util::get('einrichten') === '1' && !$zweiAn;
$frischeCodes = $_SESSION['ersatzcodes'] ?? null;
unset($_SESSION['ersatzcodes']);
?>

<div class="ad-card" id="zweifaktor">
    <div class="ad-page-head" style="margin:0 0 12px;">
        <div>
            <h2 style="margin:0;">Zwei-Faktor-Anmeldung</h2>
            <p class="ad-sub" style="margin:4px 0 0;">Zusätzlich zum Passwort eine Zahl vom Telefon.
                Ein gestohlenes Passwort allein nützt dann niemandem etwas.</p>
        </div>
        <span class="ad-pill <?= $zweiAn ? 'ad-pill-green' : 'ad-pill-grey' ?>">
            <?= $zweiAn ? 'eingeschaltet' : 'aus' ?></span>
    </div>

    <?php if ($frischeCodes !== null): ?>
        <div class="ad-flash ad-flash-warning">
            <strong>Bitte jetzt notieren – diese Codes sehen Sie kein zweites Mal.</strong>
            <p style="margin:6px 0 0;">Jeder Code ersetzt einmalig die Zahl aus der App. Bewahren Sie
                sie dort auf, wo Sie auch Ihre Ausweise haben – nicht auf demselben Telefon.</p>
            <div class="ad-mono" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));
                        gap:6px 18px;margin-top:12px;font-size:15px;letter-spacing:.06em;">
                <?php foreach ($frischeCodes as $code): ?>
                    <span><?= Util::e($code) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($zweiAn): ?>

        <p>Beim Anmelden fragt das System nach dem Passwort und danach nach der Zahl aus Ihrer App.
            Es sind noch <strong><?= Util::num(Auth::ersatzcodesUebrig((int) $currentUser['id'])) ?>
            Ersatzcodes</strong> übrig.</p>

        <div class="ad-row" style="align-items:flex-end;">
            <form method="post" class="ad-field" style="flex:1 1 300px;">
                <?= Util::csrfField() ?>
                <input type="hidden" name="aktion" value="totp_neue_codes">
                <label for="pw_codes">Neue Ersatzcodes erzeugen — Passwort zur Bestätigung</label>
                <div class="ad-row" style="margin:0;">
                    <input type="password" id="pw_codes" name="passwort" required autocomplete="current-password">
                    <button type="submit" class="ad-btn ad-btn-secondary"
                            data-confirm="Die bisherigen Ersatzcodes gelten danach nicht mehr. Fortfahren?">Erzeugen</button>
                </div>
            </form>
        </div>

        <details class="ad-klapp" style="margin-top:14px;">
            <summary><h3 style="display:inline;font-size:15px;">Zwei-Faktor-Anmeldung abschalten</h3>
                <span class="ad-klapp-zeichen" aria-hidden="true"></span></summary>
            <p class="ad-hint" style="margin-top:10px;">Danach genügt wieder das Passwort allein.</p>
            <form method="post" class="ad-row" style="align-items:flex-end;">
                <?= Util::csrfField() ?>
                <input type="hidden" name="aktion" value="totp_aus">
                <div class="ad-field">
                    <label for="pw_aus">Passwort zur Bestätigung</label>
                    <input type="password" id="pw_aus" name="passwort" required autocomplete="current-password">
                </div>
                <div class="ad-field" style="flex:0;">
                    <label>&nbsp;</label>
                    <button type="submit" class="ad-btn ad-btn-secondary"
                            data-confirm="Wirklich abschalten? Danach schützt nur noch das Passwort.">Abschalten</button>
                </div>
            </form>
        </details>

    <?php elseif ($einrichten):
        $geheim  = Auth::totpGeheimnis((int) $currentUser['id']);
        $adresse = Totp::adresse($geheim, (string) $currentUser['email'], Settings::get('brand_name'));
    ?>

        <div class="ad-block">
            <div class="ad-block-nr">1</div>
            <div>
                <h3 style="margin:0 0 6px;">App scannen lassen</h3>
                <p class="ad-hint" style="margin:0 0 12px;">Öffnen Sie eine Authenticator-App auf dem
                    Telefon — etwa Google Authenticator, Microsoft Authenticator, Aegis oder 1Password —
                    und halten Sie sie an dieses Bild.</p>
                <div style="display:flex;flex-wrap:wrap;gap:22px;align-items:flex-start;">
                    <div style="background:#fff;padding:10px;border:1px solid var(--ad-border);border-radius:6px;">
                        <?= Qr::svg($adresse, 200) ?>
                    </div>
                    <div style="flex:1 1 260px;">
                        <p class="ad-hint" style="margin:0 0 6px;">Geht das Scannen nicht, tippen Sie
                            diesen Schlüssel von Hand ein:</p>
                        <p class="ad-mono" style="font-size:16px;letter-spacing:.08em;word-break:break-all;margin:0;">
                            <?= Util::e(Totp::lesbar($geheim)) ?></p>
                        <p class="ad-hint" style="margin:10px 0 0;">Am Telefon selbst?
                            <a href="<?= Util::e($adresse) ?>">App direkt öffnen</a></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="ad-block">
            <div class="ad-block-nr">2</div>
            <div>
                <h3 style="margin:0 0 6px;">Erste Zahl eingeben</h3>
                <p class="ad-hint" style="margin:0 0 12px;">Damit prüfen wir, dass die App richtig
                    eingerichtet ist. Vorher wird nichts scharf geschaltet.</p>
                <form method="post" class="ad-row" style="align-items:flex-end;margin:0;">
                    <?= Util::csrfField() ?>
                    <input type="hidden" name="aktion" value="totp_bestaetigen">
                    <div class="ad-field" style="flex:0 1 200px;">
                        <label for="code">Zahl aus der App</label>
                        <input type="text" id="code" name="code" required inputmode="numeric"
                               maxlength="6" placeholder="123456" autocomplete="one-time-code"
                               class="ad-mono" style="font-size:20px;letter-spacing:.16em;text-align:center;">
                    </div>
                    <div class="ad-field" style="flex:0;">
                        <label>&nbsp;</label>
                        <button type="submit" class="ad-btn">Einschalten</button>
                    </div>
                </form>
            </div>
        </div>

        <p class="ad-hint"><a href="benutzer.php#zweifaktor">Abbrechen</a></p>

    <?php else: ?>

        <p>Ohne zweiten Faktor genügt Ihr Passwort, um an alle Empfängerdaten zu kommen. Mit ihm
            braucht es zusätzlich Ihr Telefon. Das Einrichten dauert zwei Minuten.</p>
        <form method="post">
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="totp_start">
            <button type="submit" class="ad-btn">Zwei-Faktor-Anmeldung einrichten</button>
        </form>

    <?php endif; ?>
</div>

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
