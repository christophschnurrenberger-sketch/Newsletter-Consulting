<?php
/**
 * einstellungen.php – Absender, Versandweg, Tempo, Texte, Zugänge.
 */

$pageTitle = 'Einstellungen';
require __DIR__ . '/partials/header.php';

if (Util::isPost()) {
    Util::requireCsrf();
    $action = Util::post('aktion');

    if ($action === 'absender') {
        Settings::setMany([
            'brand_name'    => Util::post('brand_name'),
            'sender_name'   => Util::post('sender_name'),
            'sender_email'  => Util::normalizeEmail(Util::post('sender_email')),
            'reply_to'      => Util::normalizeEmail(Util::post('reply_to')),
            'bounce_email'  => Util::normalizeEmail(Util::post('bounce_email')),
            'contact_email' => Util::normalizeEmail(Util::post('contact_email')),
            'website_url'   => Util::post('website_url'),
            'privacy_url'   => Util::post('privacy_url'),
            'imprint_url'   => Util::post('imprint_url'),
            'imprint'       => mb_substr(Util::postRaw('imprint'), 0, 2000),
            'notify_email'  => Util::normalizeEmail(Util::post('notify_email')),
            'notify_on_signup' => Util::post('notify_on_signup') === '1' ? '1' : '0',
        ]);
        Util::flash('Absenderangaben gespeichert.');
        Util::redirect('einstellungen.php');
    }

    if ($action === 'versandweg') {
        $values = [
            'transport'      => in_array(Util::post('transport'), ['smtp', 'mail', 'file'], true) ? Util::post('transport') : 'mail',
            'smtp_host'      => Util::post('smtp_host'),
            'smtp_port'      => (string) max(1, Util::postInt('smtp_port', 587)),
            'smtp_security'  => in_array(Util::post('smtp_security'), ['none', 'tls', 'ssl'], true) ? Util::post('smtp_security') : 'tls',
            'smtp_user'      => Util::post('smtp_user'),
            'smtp_timeout'   => (string) max(5, Util::postInt('smtp_timeout', 20)),
            'smtp_keepalive' => Util::post('smtp_keepalive') === '1' ? '1' : '0',
        ];
        // Passwort nur überschreiben, wenn ein neues eingegeben wurde
        $newPass = Util::postRaw('smtp_pass');
        if ($newPass !== '') {
            $values['smtp_pass'] = $newPass;
        }
        Settings::setMany($values);

        $error = Mailer::testTransport();
        Util::flash($error === ''
            ? 'Versandweg gespeichert und erfolgreich geprüft.'
            : 'Gespeichert, aber die Prüfung schlug fehl: ' . Util::e($error), $error === '' ? 'success' : 'warning');
        Util::redirect('einstellungen.php');
    }

    if ($action === 'tempo') {
        Settings::setMany([
            'batch_size'    => (string) max(1, Util::postInt('batch_size', 50)),
            'send_delay_ms' => (string) max(0, Util::postInt('send_delay_ms', 400)),
            'hourly_limit'  => (string) max(0, Util::postInt('hourly_limit', 500)),
            'max_attempts'  => (string) max(1, Util::postInt('max_attempts', 3)),
            'max_runtime'   => (string) max(10, Util::postInt('max_runtime', 50)),
            'track_opens'   => Util::post('track_opens') === '1' ? '1' : '0',
            'track_clicks'  => Util::post('track_clicks') === '1' ? '1' : '0',
            'archive_enabled' => Util::post('archive_enabled') === '1' ? '1' : '0',
            'anonymize_ip'  => Util::post('anonymize_ip') === '1' ? '1' : '0',
        ]);
        Util::flash('Versandtempo und Messung gespeichert.');
        Util::redirect('einstellungen.php');
    }

    if ($action === 'texte') {
        Settings::setMany([
            'doi_subject'     => Util::post('doi_subject'),
            'doi_intro'       => mb_substr(Util::postRaw('doi_intro'), 0, 2000),
            'doi_expire_days' => (string) max(1, Util::postInt('doi_expire_days', 14)),
            'welcome_enabled' => Util::post('welcome_enabled') === '1' ? '1' : '0',
            'welcome_subject' => Util::post('welcome_subject'),
            'welcome_intro'   => mb_substr(Util::postRaw('welcome_intro'), 0, 2000),
            'goodbye_enabled' => Util::post('goodbye_enabled') === '1' ? '1' : '0',
            'goodbye_subject' => Util::post('goodbye_subject'),
        ]);
        Util::flash('Texte gespeichert.');
        Util::redirect('einstellungen.php');
    }

    if ($action === 'bounce') {
        $values = [
            'bounce_enabled'    => Util::post('bounce_enabled') === '1' ? '1' : '0',
            'bounce_host'       => Util::post('bounce_host'),
            'bounce_port'       => (string) max(1, Util::postInt('bounce_port', 995)),
            'bounce_ssl'        => Util::post('bounce_ssl') === '1' ? '1' : '0',
            'bounce_user'       => Util::post('bounce_user'),
            'bounce_delete'     => Util::post('bounce_delete') === '1' ? '1' : '0',
            'bounce_hard_limit' => (string) max(1, Util::postInt('bounce_hard_limit', 3)),
        ];
        $newPass = Util::postRaw('bounce_pass');
        if ($newPass !== '') {
            $values['bounce_pass'] = $newPass;
        }
        Settings::setMany($values);
        Util::flash('Einstellungen für Rückläufer gespeichert.');
        Util::redirect('einstellungen.php#bounce');
    }

    if ($action === 'passwort') {
        try {
            $old = Util::postRaw('passwort_alt');
            $new = Util::postRaw('passwort_neu');
            if (!Auth::verifyPassword((int) $currentUser['id'], $old)) {
                throw new InvalidArgumentException('Das bisherige Passwort stimmt nicht.');
            }
            Auth::setPassword((int) $currentUser['id'], $new);
            Util::flash('Passwort geändert.');
        } catch (Throwable $e) {
            Util::flash(Util::e($e->getMessage()), 'error');
        }
        Util::redirect('einstellungen.php#zugaenge');
    }

    if ($action === 'benutzer_neu') {
        try {
            Auth::createUser(Util::post('neu_email'), Util::postRaw('neu_passwort'), Util::post('neu_name'));
            Util::flash('Zugang angelegt.');
        } catch (Throwable $e) {
            Util::flash(Util::e($e->getMessage()), 'error');
        }
        Util::redirect('einstellungen.php#zugaenge');
    }

    if ($action === 'benutzer_loeschen') {
        $userId = Util::postInt('user_id');
        if ($userId === (int) $currentUser['id']) {
            Util::flash('Sie können Ihren eigenen Zugang nicht löschen.', 'error');
        } elseif (Auth::userCount() <= 1) {
            Util::flash('Es muss mindestens ein Zugang bestehen bleiben.', 'error');
        } else {
            DB::delete('users', 'id = ?', [$userId]);
            Util::flash('Zugang gelöscht.');
        }
        Util::redirect('einstellungen.php#zugaenge');
    }
}

$users     = DB::all('SELECT id, email, name, created_at, last_login_at FROM users ORDER BY id');
$cronToken = (string) Config::get('cron_token', '');
$cronUrl   = Config::url('cron/send.php') . '?token=' . $cronToken;
?>

<div class="ad-page-head">
    <div>
        <h1>Einstellungen</h1>
        <p class="ad-sub">Absender, Versandweg, Tempo und Texte</p>
    </div>
</div>

<!-- ------------------------------------------------------------ Absender -->
<div class="ad-card">
    <h2>Absender &amp; Pflichtangaben</h2>
    <form method="post">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="absender">

        <div class="ad-row">
            <div class="ad-field">
                <label for="brand_name">Markenname</label>
                <input type="text" id="brand_name" name="brand_name" value="<?= Util::e(Settings::get('brand_name')) ?>">
            </div>
            <div class="ad-field">
                <label for="sender_name">Absendername</label>
                <input type="text" id="sender_name" name="sender_name" value="<?= Util::e(Settings::get('sender_name')) ?>">
                <p class="ad-hint">Ein Personenname wirkt oft besser als ein Firmenname.</p>
            </div>
        </div>

        <div class="ad-row">
            <div class="ad-field">
                <label for="sender_email">Absenderadresse</label>
                <input type="email" id="sender_email" name="sender_email" value="<?= Util::e(Settings::get('sender_email')) ?>">
                <p class="ad-hint">Muss ein echtes Postfach auf Ihrer Domain sein (SPF/DKIM!).</p>
            </div>
            <div class="ad-field">
                <label for="reply_to">Antwortadresse</label>
                <input type="email" id="reply_to" name="reply_to" value="<?= Util::e(Settings::get('reply_to')) ?>">
            </div>
            <div class="ad-field">
                <label for="bounce_email">Rücklaufadresse (Envelope)</label>
                <input type="email" id="bounce_email" name="bounce_email" value="<?= Util::e(Settings::get('bounce_email')) ?>">
                <p class="ad-hint">Hier landen Unzustellbarkeiten, z. B. bounce@ihre-domain.de</p>
            </div>
        </div>

        <div class="ad-row">
            <div class="ad-field">
                <label for="website_url">Website</label>
                <input type="text" id="website_url" name="website_url" value="<?= Util::e(Settings::get('website_url')) ?>">
            </div>
            <div class="ad-field">
                <label for="privacy_url">Datenschutzerklärung (URL)</label>
                <input type="text" id="privacy_url" name="privacy_url" value="<?= Util::e(Settings::get('privacy_url')) ?>">
            </div>
            <div class="ad-field">
                <label for="imprint_url">Impressum (URL)</label>
                <input type="text" id="imprint_url" name="imprint_url" value="<?= Util::e(Settings::get('imprint_url')) ?>">
            </div>
        </div>

        <div class="ad-field">
            <label for="imprint">Pflichtangaben im Mail-Footer</label>
            <textarea id="imprint" name="imprint" rows="3"><?= Util::e(Settings::get('imprint')) ?></textarea>
            <p class="ad-hint">Name, Anschrift, Kontakt – erscheint am Ende jeder E-Mail (§ 5 DDG).</p>
        </div>

        <div class="ad-row">
            <div class="ad-field">
                <label for="contact_email">Kontaktadresse für Empfänger</label>
                <input type="email" id="contact_email" name="contact_email" value="<?= Util::e(Settings::get('contact_email')) ?>">
            </div>
            <div class="ad-field">
                <label for="notify_email">Benachrichtigungen an</label>
                <input type="email" id="notify_email" name="notify_email" value="<?= Util::e(Settings::get('notify_email')) ?>">
                <label class="ad-check" style="margin-top:8px;">
                    <input type="checkbox" name="notify_on_signup" value="1" <?= Settings::bool('notify_on_signup') ? 'checked' : '' ?>>
                    <span>Bei jeder neuen Anmeldung benachrichtigen</span>
                </label>
            </div>
        </div>

        <button type="submit" class="ad-btn">Speichern</button>
    </form>
</div>

<!-- ---------------------------------------------------------- Versandweg -->
<div class="ad-card">
    <h2>Versandweg</h2>
    <form method="post">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="versandweg">

        <div class="ad-field">
            <label for="transport">Wie sollen die Mails verschickt werden?</label>
            <select id="transport" name="transport">
                <option value="smtp" <?= Settings::get('transport') === 'smtp' ? 'selected' : '' ?>>SMTP – eigenes Postfach (empfohlen)</option>
                <option value="mail" <?= Settings::get('transport') === 'mail' ? 'selected' : '' ?>>PHP mail() – Mailserver des Hosters</option>
                <option value="file" <?= Settings::get('transport') === 'file' ? 'selected' : '' ?>>Testmodus – nur in data/outbox schreiben</option>
            </select>
            <p class="ad-hint">SMTP mit einem echten Postfach Ihrer Domain ist am zuverlässigsten, weil
                Absender, SPF und DKIM zusammenpassen.</p>
        </div>

        <div class="ad-row">
            <div class="ad-field">
                <label for="smtp_host">SMTP-Server</label>
                <input type="text" id="smtp_host" name="smtp_host" value="<?= Util::e(Settings::get('smtp_host')) ?>"
                       placeholder="z. B. smtp.ionos.de">
            </div>
            <div class="ad-field">
                <label for="smtp_port">Port</label>
                <input type="number" id="smtp_port" name="smtp_port" value="<?= (int) Settings::int('smtp_port', 587) ?>">
            </div>
            <div class="ad-field">
                <label for="smtp_security">Verschlüsselung</label>
                <select id="smtp_security" name="smtp_security">
                    <option value="tls" <?= Settings::get('smtp_security') === 'tls' ? 'selected' : '' ?>>STARTTLS (Port 587)</option>
                    <option value="ssl" <?= Settings::get('smtp_security') === 'ssl' ? 'selected' : '' ?>>SSL/TLS (Port 465)</option>
                    <option value="none" <?= Settings::get('smtp_security') === 'none' ? 'selected' : '' ?>>keine</option>
                </select>
            </div>
        </div>

        <div class="ad-row">
            <div class="ad-field">
                <label for="smtp_user">Benutzername</label>
                <input type="text" id="smtp_user" name="smtp_user" value="<?= Util::e(Settings::get('smtp_user')) ?>"
                       autocomplete="off">
            </div>
            <div class="ad-field">
                <label for="smtp_pass">Passwort</label>
                <input type="password" id="smtp_pass" name="smtp_pass" autocomplete="new-password"
                       placeholder="<?= Settings::hasSecret('smtp_pass') ? 'gespeichert – leer lassen, um es zu behalten' : 'Passwort des Postfachs' ?>">
                <p class="ad-hint">Wird verschlüsselt gespeichert.</p>
            </div>
            <div class="ad-field">
                <label for="smtp_timeout">Zeitlimit (Sekunden)</label>
                <input type="number" id="smtp_timeout" name="smtp_timeout" value="<?= Settings::int('smtp_timeout', 20) ?>">
            </div>
        </div>

        <label class="ad-check">
            <input type="checkbox" name="smtp_keepalive" value="1" <?= Settings::bool('smtp_keepalive') ? 'checked' : '' ?>>
            <span>Verbindung über mehrere Mails offen halten <em class="ad-hint">(schneller; bei Problemen abschalten)</em></span>
        </label>

        <button type="submit" class="ad-btn">Speichern &amp; Verbindung prüfen</button>
    </form>
</div>

<!-- --------------------------------------------------------------- Tempo -->
<div class="ad-card">
    <h2>Tempo, Messung &amp; Datenschutz</h2>
    <form method="post">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="tempo">

        <div class="ad-row">
            <div class="ad-field">
                <label for="batch_size">Mails pro Cron-Lauf</label>
                <input type="number" id="batch_size" name="batch_size" min="1" value="<?= Settings::int('batch_size', 50) ?>">
            </div>
            <div class="ad-field">
                <label for="send_delay_ms">Pause zwischen Mails (ms)</label>
                <input type="number" id="send_delay_ms" name="send_delay_ms" min="0" value="<?= Settings::int('send_delay_ms', 400) ?>">
            </div>
            <div class="ad-field">
                <label for="hourly_limit">Höchstens pro Stunde</label>
                <input type="number" id="hourly_limit" name="hourly_limit" min="0" value="<?= Settings::int('hourly_limit', 500) ?>">
                <p class="ad-hint">0 = kein Limit. Bitte am Limit Ihres Hosters ausrichten.</p>
            </div>
        </div>

        <div class="ad-row">
            <div class="ad-field">
                <label for="max_attempts">Zustellversuche je Mail</label>
                <input type="number" id="max_attempts" name="max_attempts" min="1" max="10" value="<?= Settings::int('max_attempts', 3) ?>">
            </div>
            <div class="ad-field">
                <label for="max_runtime">Laufzeit je Durchlauf (Sekunden)</label>
                <input type="number" id="max_runtime" name="max_runtime" min="10" value="<?= Settings::int('max_runtime', 50) ?>">
            </div>
        </div>

        <label class="ad-check">
            <input type="checkbox" name="track_opens" value="1" <?= Settings::bool('track_opens') ? 'checked' : '' ?>>
            <span>Öffnungen messen (Voreinstellung für neue Ausgaben)</span>
        </label>
        <label class="ad-check">
            <input type="checkbox" name="track_clicks" value="1" <?= Settings::bool('track_clicks') ? 'checked' : '' ?>>
            <span>Klicks messen (Voreinstellung für neue Ausgaben)</span>
        </label>
        <label class="ad-check">
            <input type="checkbox" name="archive_enabled" value="1" <?= Settings::bool('archive_enabled') ? 'checked' : '' ?>>
            <span>Öffentliches Archiv anbieten</span>
        </label>
        <label class="ad-check">
            <input type="checkbox" name="anonymize_ip" value="1" <?= Settings::bool('anonymize_ip') ? 'checked' : '' ?>>
            <span>IP-Adressen gekürzt speichern <em class="ad-hint">(datensparsam; als Einwilligungsnachweis
                genügt in der Regel die gekürzte IP mit Zeitstempel)</em></span>
        </label>

        <button type="submit" class="ad-btn">Speichern</button>
    </form>
</div>

<!-- --------------------------------------------------------------- Texte -->
<div class="ad-card">
    <h2>Texte der Systemmails</h2>
    <form method="post">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="texte">

        <h3>Bestätigungsmail (Double-Opt-in)</h3>
        <div class="ad-row">
            <div class="ad-field" style="flex:2 1 320px;">
                <label for="doi_subject">Betreff</label>
                <input type="text" id="doi_subject" name="doi_subject" value="<?= Util::e(Settings::get('doi_subject')) ?>">
            </div>
            <div class="ad-field">
                <label for="doi_expire_days">Unbestätigte löschen nach (Tagen)</label>
                <input type="number" id="doi_expire_days" name="doi_expire_days" min="1" value="<?= Settings::int('doi_expire_days', 14) ?>">
            </div>
        </div>
        <div class="ad-field">
            <label for="doi_intro">Einleitungstext</label>
            <textarea id="doi_intro" name="doi_intro" rows="3"><?= Util::e(Settings::get('doi_intro')) ?></textarea>
        </div>

        <h3>Begrüßungsmail</h3>
        <label class="ad-check">
            <input type="checkbox" name="welcome_enabled" value="1" <?= Settings::bool('welcome_enabled') ? 'checked' : '' ?>>
            <span>Nach der Bestätigung eine Begrüßungsmail senden</span>
        </label>
        <div class="ad-field">
            <label for="welcome_subject">Betreff</label>
            <input type="text" id="welcome_subject" name="welcome_subject" value="<?= Util::e(Settings::get('welcome_subject')) ?>">
        </div>
        <div class="ad-field">
            <label for="welcome_intro">Text</label>
            <textarea id="welcome_intro" name="welcome_intro" rows="3"><?= Util::e(Settings::get('welcome_intro')) ?></textarea>
        </div>

        <h3>Abmeldebestätigung</h3>
        <label class="ad-check">
            <input type="checkbox" name="goodbye_enabled" value="1" <?= Settings::bool('goodbye_enabled') ? 'checked' : '' ?>>
            <span>Nach der Abmeldung eine kurze Bestätigung senden</span>
        </label>
        <div class="ad-field">
            <label for="goodbye_subject">Betreff</label>
            <input type="text" id="goodbye_subject" name="goodbye_subject" value="<?= Util::e(Settings::get('goodbye_subject')) ?>">
        </div>

        <button type="submit" class="ad-btn">Speichern</button>
    </form>
</div>

<!-- ------------------------------------------------------------- Bounces -->
<div class="ad-card" id="bounce">
    <h2>Rückläufer automatisch auswerten</h2>
    <p class="ad-hint" style="margin-top:-4px;">Optional: Das System holt Fehlermails per POP3 aus einem
        eigenen Postfach ab und sperrt dauerhaft unzustellbare Adressen. Das hält Ihre Liste sauber und
        Ihre Zustellrate hoch.</p>
    <form method="post">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="bounce">

        <label class="ad-check">
            <input type="checkbox" name="bounce_enabled" value="1" <?= Settings::bool('bounce_enabled') ? 'checked' : '' ?>>
            <span>Rücklaufpostfach auswerten</span>
        </label>

        <div class="ad-row">
            <div class="ad-field">
                <label for="bounce_host">POP3-Server</label>
                <input type="text" id="bounce_host" name="bounce_host" value="<?= Util::e(Settings::get('bounce_host')) ?>"
                       placeholder="z. B. pop.ionos.de">
            </div>
            <div class="ad-field">
                <label for="bounce_port">Port</label>
                <input type="number" id="bounce_port" name="bounce_port" value="<?= Settings::int('bounce_port', 995) ?>">
            </div>
            <div class="ad-field">
                <label for="bounce_hard_limit">Sperren nach … weichen Bounces</label>
                <input type="number" id="bounce_hard_limit" name="bounce_hard_limit" min="1" value="<?= Settings::int('bounce_hard_limit', 3) ?>">
            </div>
        </div>

        <div class="ad-row">
            <div class="ad-field">
                <label for="bounce_user">Benutzername</label>
                <input type="text" id="bounce_user" name="bounce_user" value="<?= Util::e(Settings::get('bounce_user')) ?>" autocomplete="off">
            </div>
            <div class="ad-field">
                <label for="bounce_pass">Passwort</label>
                <input type="password" id="bounce_pass" name="bounce_pass" autocomplete="new-password"
                       placeholder="<?= Settings::hasSecret('bounce_pass') ? 'gespeichert – leer lassen, um es zu behalten' : '' ?>">
            </div>
        </div>

        <label class="ad-check">
            <input type="checkbox" name="bounce_ssl" value="1" <?= Settings::bool('bounce_ssl') ? 'checked' : '' ?>>
            <span>SSL verwenden (Port 995)</span>
        </label>
        <label class="ad-check">
            <input type="checkbox" name="bounce_delete" value="1" <?= Settings::bool('bounce_delete') ? 'checked' : '' ?>>
            <span>Verarbeitete Fehlermails im Postfach löschen</span>
        </label>

        <button type="submit" class="ad-btn">Speichern</button>
    </form>
</div>

<!-- ----------------------------------------------------------- Cron & Co -->
<div class="ad-card">
    <h2>Cron-Job</h2>
    <p>Der Versand läuft im Hintergrund. Richten Sie im Hosting-Menü einen Aufruf <strong>alle 5 Minuten</strong> ein:</p>
    <p class="ad-mono" style="background:#F2F5F8;padding:12px;border-radius:6px;word-break:break-all;">
        <?= Util::e($cronUrl) ?>
    </p>
    <p class="ad-hint">Alternativ per Kommandozeile (schneller und ohne Token):
        <code>php <?= Util::e(NL_ROOT) ?>/cron/send.php</code></p>
    <p class="ad-hint">Für Rückläufer zusätzlich einmal pro Stunde:
        <code><?= Util::e(Config::url('cron/bounces.php') . '?token=' . $cronToken) ?></code></p>
</div>

<!-- ------------------------------------------------------------ Zugänge -->
<div class="ad-card" id="zugaenge">
    <h2>Zugänge</h2>

    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>Adresse</th><th>Name</th><th>Angelegt</th><th>Letzte Anmeldung</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td class="ad-mono"><?= Util::e((string) $user['email']) ?></td>
                    <td><?= Util::e((string) $user['name']) ?></td>
                    <td><?= Util::e(Util::dt((string) $user['created_at'], 'd.m.Y')) ?></td>
                    <td><?= Util::e(Util::dt((string) $user['last_login_at'])) ?></td>
                    <td>
                        <?php if ((int) $user['id'] !== (int) $currentUser['id']): ?>
                            <form method="post" style="display:inline;">
                                <?= Util::csrfField() ?>
                                <input type="hidden" name="aktion" value="benutzer_loeschen">
                                <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                <button type="submit" class="ad-btn ad-btn-danger ad-btn-small"
                                        data-confirm="Zugang wirklich löschen?">Löschen</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="ad-grid-2">
        <form method="post">
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="passwort">
            <h3>Eigenes Passwort ändern</h3>
            <div class="ad-field">
                <label for="passwort_alt">Bisheriges Passwort</label>
                <input type="password" id="passwort_alt" name="passwort_alt" required autocomplete="current-password">
            </div>
            <div class="ad-field">
                <label for="passwort_neu">Neues Passwort</label>
                <input type="password" id="passwort_neu" name="passwort_neu" required autocomplete="new-password">
                <p class="ad-hint">Mindestens 10 Zeichen, Buchstaben und Ziffern.</p>
            </div>
            <button type="submit" class="ad-btn ad-btn-secondary">Passwort ändern</button>
        </form>

        <form method="post">
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="benutzer_neu">
            <h3>Weiteren Zugang anlegen</h3>
            <div class="ad-field">
                <label for="neu_email">E-Mail-Adresse</label>
                <input type="email" id="neu_email" name="neu_email" required>
            </div>
            <div class="ad-field">
                <label for="neu_name">Name</label>
                <input type="text" id="neu_name" name="neu_name">
            </div>
            <div class="ad-field">
                <label for="neu_passwort">Passwort</label>
                <input type="password" id="neu_passwort" name="neu_passwort" required autocomplete="new-password">
            </div>
            <button type="submit" class="ad-btn ad-btn-secondary">Zugang anlegen</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php';
