<?php
/**
 * meldungen.php – Mehrkanal-Meldungen.
 *
 * Ein Ereignis (z. B. „Platz gesperrt") mit einem Text, das über mehrere
 * Kanäle zu verschiedenen Zeiten ausgespielt wird: E-Mail, Website und –
 * anschlussbereit – SMS, WhatsApp, Push.
 *
 * Zwei Ansichten: die Liste aller Meldungen und – mit ?id=… – der Editor
 * einer Meldung samt Kanälen und Zeiten.
 */

$pageTitle     = 'Meldungen';
$requiredRight = 'kampagnen';
require __DIR__ . '/partials/header.php';

$darf = Auth::can('kampagnen');

/* ------------------------------------------------------------- Aktionen */

if (Util::isPost()) {
    Util::requireCsrf();
    if (!$darf) {
        Util::flash('Dafür fehlt Ihnen die Berechtigung.', 'error');
        Util::redirect('meldungen.php');
    }
    $aktion = Util::post('aktion');

    if ($aktion === 'meldung-anlegen') {
        $neu = Announcements::create(Util::post('title') ?: 'Neue Meldung', (string) ($currentUser['email'] ?? ''));
        Util::flash('Meldung angelegt. Text schreiben, Kanäle und Zeiten festlegen – dann scharf schalten.');
        Util::redirect('meldungen.php?id=' . $neu);
    }

    if ($aktion === 'meldung-speichern' && Util::postInt('id') > 0) {
        $id = Util::postInt('id');
        Announcements::save($id, [
            'title'      => Util::post('title'),
            'body'       => Util::postRaw('body'),
            'category'   => Util::post('category'),
            'link_url'   => Util::post('link_url'),
            'link_label' => Util::post('link_label'),
            'list_id'    => Util::postInt('list_id') ?: null,
            'expires_at' => Util::post('expires_at'),
        ]);
        foreach ((array) ($_POST['kanal'] ?? []) as $kid => $werte) {
            $kid = (int) $kid;
            if ($kid <= 0 || !is_array($werte)) {
                continue;
            }
            Announcements::saveChannel($kid, [
                'channel'      => (string) ($werte['channel'] ?? ''),
                'scheduled_at' => (string) ($werte['scheduled_at'] ?? ''),
            ]);
        }
        Util::flash('Meldung gespeichert.');
        Util::redirect('meldungen.php?id=' . $id);
    }

    if ($aktion === 'kanal-hinzu' && Util::postInt('id') > 0) {
        $id = Util::postInt('id');
        Announcements::addChannel($id, Util::post('channel') ?: 'website', Util::now(),
            count(Announcements::channels($id)));
        Util::flash('Kanal hinzugefügt.');
        Util::redirect('meldungen.php?id=' . $id . '#kanaele');
    }

    if ($aktion === 'kanal-loeschen' && Util::postInt('id') > 0 && Util::postInt('kanal_id') > 0) {
        // Nur löschen, wenn der Kanal auch zu dieser Meldung gehört.
        $k = DB::row('SELECT * FROM announcement_channels WHERE id = ? AND announcement_id = ?',
            [Util::postInt('kanal_id'), Util::postInt('id')]);
        if ($k !== null) {
            Announcements::deleteChannel(Util::postInt('kanal_id'));
            Util::flash('Kanal entfernt.');
        }
        Util::redirect('meldungen.php?id=' . Util::postInt('id') . '#kanaele');
    }

    if ($aktion === 'scharf' && Util::postInt('id') > 0) {
        Announcements::activate(Util::postInt('id'));
        Util::flash('Meldung ist scharf. Jeder Kanal wird zu seiner Zeit ausgespielt – der Versand-Cron übernimmt das.');
        Util::redirect('meldungen.php?id=' . Util::postInt('id'));
    }

    if ($aktion === 'zurueckziehen' && Util::postInt('id') > 0) {
        Announcements::pause(Util::postInt('id'));
        Util::flash('Meldung zurückgezogen. Noch nicht ausgespielte Kanäle warten, bis Sie erneut scharf schalten.');
        Util::redirect('meldungen.php?id=' . Util::postInt('id'));
    }

    if ($aktion === 'jetzt-ausspielen') {
        $r = Announcements::runDue();
        Util::flash(sprintf('Ausgespielt: %d gesendet, %d übersprungen, %d fehlgeschlagen.',
            $r['sent'], $r['skipped'], $r['failed']));
        Util::redirect('meldungen.php' . (Util::postInt('id') > 0 ? '?id=' . Util::postInt('id') : ''));
    }

    if ($aktion === 'meldung-loeschen' && Util::postInt('id') > 0) {
        Announcements::delete(Util::postInt('id'));
        Util::flash('Meldung gelöscht.');
        Util::redirect('meldungen.php');
    }

    if ($aktion === 'kanaele-einrichten') {
        Settings::setMany([
            'channel_sms_provider'    => Util::post('channel_sms_provider'),
            'channel_sms_sender'      => Util::post('channel_sms_sender'),
            'channel_sms_key'         => Util::post('channel_sms_key'),
            'channel_whatsapp_from'   => Util::post('channel_whatsapp_from'),
            'channel_whatsapp_token'  => Util::post('channel_whatsapp_token'),
            'channel_push_key'        => Util::post('channel_push_key'),
        ]);
        Util::flash('Kanal-Einstellungen gespeichert.');
        Util::redirect('meldungen.php#kanaele-einrichten');
    }
}

/* ------------------------------------------------------------- Anzeige */

$current = Util::getInt('id') > 0 ? Announcements::byId(Util::getInt('id')) : null;
$neu     = $current === null && Util::get('neu') === '1';
$alle    = Announcements::all();
$listen  = Lists::all();
$catMeta = Announcements::categoryMeta();

/** Statuspille eines Kanals. */
function meldung_kanal_pille(string $status): string
{
    [$klasse, $text] = match ($status) {
        Announcements::SENT    => ['ad-pill-green', 'ausgespielt'],
        Announcements::FAILED  => ['ad-pill-red',   'fehlgeschlagen'],
        Announcements::SKIPPED => ['ad-pill-amber', 'übersprungen'],
        default                => ['ad-pill-grey',  'geplant'],
    };
    return '<span class="ad-pill ' . $klasse . '">' . $text . '</span>';
}

/** Statuspille einer Meldung. */
function meldung_status_pille(string $status): string
{
    [$klasse, $text] = match ($status) {
        Announcements::ACTIVE => ['ad-pill-green', 'scharf'],
        Announcements::DONE   => ['ad-pill-blue',  'ausgespielt'],
        default               => ['ad-pill-grey',  'Entwurf'],
    };
    return '<span class="ad-pill ' . $klasse . '">' . $text . '</span>';
}
?>

<div class="ad-page-head">
    <div>
        <h1>Meldungen</h1>
        <p class="ad-sub">Ein Ereignis, mehrere Kanäle – E-Mail, Website und mehr, jeweils zur passenden Zeit.</p>
    </div>
    <?php if ($current !== null): ?>
        <button type="submit" form="meldung-form" class="ad-btn">Meldung speichern</button>
    <?php endif; ?>
</div>

<?php if ($current === null): ?>

    <!-- --------------------------------------------------- Übersicht -->

    <div class="ad-card" style="border-left:3px solid var(--ad-navy);">
        <h2 style="margin-top:0;">So funktionieren Meldungen</h2>
        <p style="margin:0 0 6px;">Eine Meldung ist ein Ereignis mit einem Text – z. B. <em>„Platz gesperrt"</em>.
            Sie legen fest, über welche Kanäle und zu welcher Uhrzeit sie hinausgeht:</p>
        <p style="margin:0 0 8px;">
            <span class="ad-pill ad-pill-grey">08:00 → SMS</span>
            <span class="ad-pill ad-pill-grey">09:00 → Website</span>
            <span class="ad-pill ad-pill-grey">11:00 → Newsletter</span>
        </p>
        <p class="ad-hint" style="margin:0;">E-Mail und Website funktionieren sofort. SMS, WhatsApp und Push sind
            vorbereitet und warten auf einen Anbieter (siehe „Kanäle einrichten" unten). Die öffentliche Seite ist
            <a href="<?= Util::e(Config::url('aktuelles.php')) ?>" target="_blank" rel="noopener">Aktuelles</a>.</p>
    </div>

    <?php if ($alle === []): ?>
        <?= admin_empty('mail', 'Noch keine Meldung',
            'Legen Sie Ihre erste Meldung an – etwa eine Platzsperrung oder eine kurzfristige Terminänderung.',
            '') ?>
    <?php else: ?>
        <div class="ad-card ad-card-tight">
            <div class="ad-table-wrap">
                <table class="ad-table">
                    <tbody>
                    <?php foreach ($alle as $m):
                        $kan = Announcements::channels((int) $m['id']); ?>
                        <tr>
                            <td>
                                <strong><?= Util::e((string) $m['title']) ?></strong>
                                <?= meldung_status_pille((string) $m['status']) ?>
                                <span class="ad-pill" style="background:<?= Util::e($catMeta[$m['category']]['farbe'] ?? '#22405F') ?>;color:#fff;">
                                    <?= Util::e(Announcements::categoryLabel((string) $m['category'])) ?></span>
                                <br><span class="ad-hint">
                                    <?php foreach ($kan as $k): ?>
                                        <?= Util::e(Announcements::channelLabel((string) $k['channel'])) ?><?= $k['scheduled_at'] ? ' ' . Util::e(Util::dt((string) $k['scheduled_at'], 'd.m. H:i')) : '' ?><?= $k['status'] !== Announcements::PENDING ? ' (' . Util::e($k['status']) . ')' : '' ?><?= $k !== end($kan) ? ' · ' : '' ?>
                                    <?php endforeach; ?>
                                </span>
                            </td>
                            <td style="width:1%;white-space:nowrap;text-align:right;">
                                <a class="ad-btn ad-btn-secondary ad-btn-small" href="meldungen.php?id=<?= (int) $m['id'] ?>">Bearbeiten</a>
                                <details class="ad-menue">
                                    <summary aria-label="Weitere Aktionen">…</summary>
                                    <div class="ad-menue-liste">
                                        <form method="post">
                                            <?= Util::csrfField() ?>
                                            <input type="hidden" name="aktion" value="meldung-loeschen">
                                            <input type="hidden" name="id" value="<?= (int) $m['id'] ?>">
                                            <button type="submit" class="ist-gefahr"
                                                    data-confirm="Diese Meldung mit allen Kanälen wirklich löschen?">Löschen</button>
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

    <!-- Neue Meldung -->
    <details class="ad-card ad-klapp" id="neue-meldung" <?= $neu || $alle === [] ? 'open' : '' ?>>
        <summary>
            <h2>Neue Meldung anlegen</h2>
            <span class="ad-klapp-zeichen" aria-hidden="true"></span>
        </summary>
        <form method="post" style="margin-top:12px;">
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="meldung-anlegen">
            <div class="ad-field">
                <label for="title">Worum geht es?</label>
                <input type="text" id="title" name="title" maxlength="190" placeholder="z. B. Platz gesperrt">
            </div>
            <div class="ad-actions">
                <button type="submit" class="ad-btn">Meldung anlegen</button>
            </div>
        </form>
    </details>

    <!-- Kanäle einrichten -->
    <details class="ad-card ad-klapp" id="kanaele-einrichten">
        <summary>
            <h2>Kanäle einrichten</h2>
            <span class="ad-klapp-zeichen" aria-hidden="true"></span>
        </summary>
        <p class="ad-hint" style="margin:8px 0 14px;">E-Mail und Website sind sofort einsatzbereit. Für SMS,
            WhatsApp und Push hinterlegen Sie hier die Zugangsdaten Ihres Anbieters. Sobald ein Anbieter feststeht,
            wird der Live-Versand freigeschaltet.</p>

        <div class="ad-kanal-status">
            <?php foreach (Announcements::channelMeta() as $ch => $meta):
                $ok = Announcements::configured($ch); ?>
                <span class="ad-pill <?= $ok ? 'ad-pill-green' : 'ad-pill-grey' ?>">
                    <?= Util::e($meta['label']) ?>: <?= $ok ? 'bereit' : 'einrichten' ?></span>
            <?php endforeach; ?>
        </div>

        <form method="post" style="margin-top:14px;">
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="kanaele-einrichten">
            <h3 style="font-size:14px;margin:6px 0;">SMS</h3>
            <div class="ad-row">
                <div class="ad-field" style="flex:1 1 180px;">
                    <label for="sms_provider">Anbieter</label>
                    <input type="text" id="sms_provider" name="channel_sms_provider" maxlength="60"
                           value="<?= Util::e(Settings::get('channel_sms_provider')) ?>" placeholder="z. B. Twilio, MessageBird">
                </div>
                <div class="ad-field" style="flex:1 1 160px;">
                    <label for="sms_sender">Absender</label>
                    <input type="text" id="sms_sender" name="channel_sms_sender" maxlength="60"
                           value="<?= Util::e(Settings::get('channel_sms_sender')) ?>" placeholder="z. B. GC Ottobeuren">
                </div>
                <div class="ad-field" style="flex:2 1 220px;">
                    <label for="sms_key">API-Schlüssel</label>
                    <input type="password" id="sms_key" name="channel_sms_key" autocomplete="off"
                           value="<?= Util::e(Settings::get('channel_sms_key')) ?>">
                </div>
            </div>
            <h3 style="font-size:14px;margin:12px 0 6px;">WhatsApp</h3>
            <div class="ad-row">
                <div class="ad-field" style="flex:1 1 180px;">
                    <label for="wa_from">Absendernummer</label>
                    <input type="text" id="wa_from" name="channel_whatsapp_from" maxlength="40"
                           value="<?= Util::e(Settings::get('channel_whatsapp_from')) ?>" placeholder="+49 …">
                </div>
                <div class="ad-field" style="flex:2 1 220px;">
                    <label for="wa_token">Zugriffstoken</label>
                    <input type="password" id="wa_token" name="channel_whatsapp_token" autocomplete="off"
                           value="<?= Util::e(Settings::get('channel_whatsapp_token')) ?>">
                </div>
            </div>
            <h3 style="font-size:14px;margin:12px 0 6px;">Push</h3>
            <div class="ad-row">
                <div class="ad-field" style="flex:2 1 220px;">
                    <label for="push_key">Schlüssel / VAPID</label>
                    <input type="password" id="push_key" name="channel_push_key" autocomplete="off"
                           value="<?= Util::e(Settings::get('channel_push_key')) ?>">
                </div>
            </div>
            <div class="ad-actions">
                <button type="submit" class="ad-btn ad-btn-secondary">Kanal-Einstellungen speichern</button>
            </div>
        </form>
    </details>

<?php else: ?>

    <!-- --------------------------------------------------- Editor -->

    <?php
    $kanaele  = Announcements::channels((int) $current['id']);
    $istScharf = $current['status'] === Announcements::ACTIVE;
    $expiresLocal = $current['expires_at'] ? date('Y-m-d\TH:i', strtotime((string) $current['expires_at'])) : '';
    ?>

    <p style="margin:-6px 0 14px;"><a href="meldungen.php">← Alle Meldungen</a></p>

    <div class="ad-flash ad-flash-<?= $istScharf ? 'success' : 'info' ?>" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
        <span>Zustand: <?= meldung_status_pille((string) $current['status']) ?>
            <?= $istScharf ? 'Kanäle werden zu ihrer Zeit ausgespielt.' : 'Noch nichts wird versendet, bis Sie scharf schalten.' ?></span>
        <span style="display:flex;gap:8px;">
            <?php if ($istScharf): ?>
                <form method="post" style="margin:0;">
                    <?= Util::csrfField() ?>
                    <input type="hidden" name="aktion" value="zurueckziehen">
                    <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
                    <button type="submit" class="ad-btn ad-btn-secondary ad-btn-small">Zurückziehen</button>
                </form>
            <?php else: ?>
                <form method="post" style="margin:0;">
                    <?= Util::csrfField() ?>
                    <input type="hidden" name="aktion" value="scharf">
                    <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
                    <button type="submit" class="ad-btn ad-btn-small"
                            data-confirm="Meldung scharf schalten? Kanäle mit einer Zeit in der Vergangenheit gehen beim nächsten Cron-Lauf sofort hinaus.">Scharf schalten</button>
                </form>
            <?php endif; ?>
        </span>
    </div>

    <form method="post" id="meldung-form">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="meldung-speichern">
        <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">

        <div class="ad-card">
            <div class="ad-row">
                <div class="ad-field" style="flex:2 1 300px;">
                    <label for="m_title">Titel</label>
                    <input type="text" id="m_title" name="title" maxlength="190"
                           value="<?= Util::e((string) $current['title']) ?>">
                </div>
                <div class="ad-field" style="flex:1 1 180px;">
                    <label for="m_cat">Rubrik</label>
                    <select id="m_cat" name="category">
                        <?php foreach ($catMeta as $key => $meta): ?>
                            <option value="<?= Util::e($key) ?>" <?= $current['category'] === $key ? 'selected' : '' ?>>
                                <?= Util::e($meta['label']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="ad-field">
                <label for="m_body">Text</label>
                <textarea id="m_body" name="body" rows="4" placeholder="Was ist passiert? Kurz und klar."><?= Util::e((string) $current['body']) ?></textarea>
            </div>
            <div class="ad-row">
                <div class="ad-field" style="flex:2 1 260px;">
                    <label for="m_link">Link (optional)</label>
                    <input type="text" id="m_link" name="link_url" maxlength="500"
                           value="<?= Util::e((string) ($current['link_url'] ?? '')) ?>" placeholder="z. B. www.club.de/platz">
                </div>
                <div class="ad-field" style="flex:1 1 180px;">
                    <label for="m_linklabel">Link-Beschriftung</label>
                    <input type="text" id="m_linklabel" name="link_label" maxlength="120"
                           value="<?= Util::e((string) ($current['link_label'] ?? '')) ?>" placeholder="z. B. Platzstatus">
                </div>
            </div>
            <div class="ad-row">
                <div class="ad-field" style="flex:1 1 200px;">
                    <label for="m_list">E-Mail an welche Liste?</label>
                    <select id="m_list" name="list_id">
                        <option value="0">Alle aktiven Empfänger</option>
                        <?php foreach ($listen as $l): ?>
                            <option value="<?= (int) $l['id'] ?>"
                                <?= (int) ($current['list_id'] ?? 0) === (int) $l['id'] ? 'selected' : '' ?>>
                                <?= Util::e((string) $l['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="ad-field" style="flex:1 1 200px;">
                    <label for="m_expires">Auf der Website sichtbar bis (optional)</label>
                    <input type="datetime-local" id="m_expires" name="expires_at" value="<?= Util::e($expiresLocal) ?>">
                </div>
            </div>
        </div>

        <!-- Kanäle -->
        <div class="ad-card" id="kanaele">
            <h2 style="margin-top:0;">Kanäle &amp; Zeiten</h2>
            <p class="ad-hint" style="margin:0 0 14px;">Jeder Kanal geht zu seiner Zeit hinaus. Ein Zeitpunkt in der
                Vergangenheit bedeutet: beim nächsten Cron-Lauf sofort.</p>

            <?php if ($kanaele === []): ?>
                <p>Diese Meldung hat noch keinen Kanal. Fügen Sie unten einen hinzu.</p>
            <?php endif; ?>

            <?php foreach ($kanaele as $k):
                $live = Announcements::configured((string) $k['channel']);
                $local = $k['scheduled_at'] ? date('Y-m-d\TH:i', strtotime((string) $k['scheduled_at'])) : ''; ?>
                <div class="ad-card ad-card-tight" style="margin-bottom:12px;">
                    <div class="ad-row" style="align-items:flex-end;">
                        <div class="ad-field" style="flex:1 1 160px;">
                            <label>Kanal</label>
                            <select name="kanal[<?= (int) $k['id'] ?>][channel]">
                                <?php foreach (Announcements::channelMeta() as $ch => $meta): ?>
                                    <option value="<?= Util::e($ch) ?>" <?= $k['channel'] === $ch ? 'selected' : '' ?>>
                                        <?= Util::e($meta['label']) ?><?= $meta['live'] ? '' : ' (anschlussbereit)' ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="ad-field" style="flex:1 1 200px;">
                            <label>Zeitpunkt</label>
                            <input type="datetime-local" name="kanal[<?= (int) $k['id'] ?>][scheduled_at]"
                                   value="<?= Util::e($local) ?>">
                        </div>
                        <div class="ad-field" style="flex:1 1 160px;justify-content:flex-end;">
                            <?= meldung_kanal_pille((string) $k['status']) ?>
                            <?php if (!$live): ?><span class="ad-hint">noch nicht eingerichtet</span><?php endif; ?>
                        </div>
                        <div class="ad-field" style="flex:0;justify-content:flex-end;">
                            <details class="ad-menue">
                                <summary aria-label="Kanal-Aktionen">…</summary>
                                <div class="ad-menue-liste">
                                    <button type="submit" form="k-del-<?= (int) $k['id'] ?>" class="ist-gefahr"
                                            data-confirm="Diesen Kanal entfernen?">Entfernen</button>
                                </div>
                            </details>
                        </div>
                    </div>
                    <?php if (trim((string) ($k['result'] ?? '')) !== ''): ?>
                        <p class="ad-hint" style="margin:8px 0 0;">Zuletzt: <?= Util::e((string) $k['result']) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </form>

    <?php foreach ($kanaele as $k): ?>
        <form method="post" id="k-del-<?= (int) $k['id'] ?>" hidden>
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="kanal-loeschen">
            <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
            <input type="hidden" name="kanal_id" value="<?= (int) $k['id'] ?>">
        </form>
    <?php endforeach; ?>

    <div class="ad-card ad-card-tight">
        <form method="post" style="margin:0;display:flex;gap:8px;align-items:flex-end;flex-wrap:wrap;">
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="kanal-hinzu">
            <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
            <div class="ad-field" style="flex:0 1 200px;margin:0;">
                <label for="neuer_kanal">Kanal hinzufügen</label>
                <select id="neuer_kanal" name="channel">
                    <?php foreach (Announcements::channelMeta() as $ch => $meta): ?>
                        <option value="<?= Util::e($ch) ?>"><?= Util::e($meta['label']) ?><?= $meta['live'] ? '' : ' (anschlussbereit)' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="ad-btn ad-btn-secondary">+ Kanal hinzufügen</button>
        </form>
    </div>

    <div class="ad-card">
        <h2 style="margin-top:0;">Vorschau &amp; Test</h2>
        <p class="ad-hint" style="margin:0 0 10px;">Die öffentliche Seite zeigt die Meldung, sobald der Website-Kanal
            ausgespielt ist: <a href="<?= Util::e(Config::url('aktuelles.php')) ?>" target="_blank" rel="noopener">Aktuelles ansehen →</a></p>
        <form method="post" style="margin:0;">
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="jetzt-ausspielen">
            <input type="hidden" name="id" value="<?= (int) $current['id'] ?>">
            <button type="submit" class="ad-btn ad-btn-secondary"
                    data-confirm="Jetzt fällige Kanäle ausspielen? E-Mail geht dabei tatsächlich hinaus.">Fällige Kanäle jetzt ausspielen</button>
        </form>
        <p class="ad-hint" style="margin-top:8px;">Sonst übernimmt das der Versand-Cron (alle paar Minuten) von selbst.</p>
    </div>

<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
