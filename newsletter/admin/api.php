<?php
/**
 * api.php – Schnittstelle: Schlüssel verwalten und die Doku ansehen.
 *
 * Der Schlüssel wird beim Anlegen einmal im Klartext gezeigt (danach nie
 * wieder). Darunter steht die Kurz-Doku mit allen Endpunkten und Beispielen.
 */

$pageTitle     = 'Schnittstelle';
$requiredRight = 'einstellungen';
require __DIR__ . '/partials/header.php';

if (Util::isPost()) {
    Util::requireCsrf();
    $aktion = Util::post('aktion');

    if ($aktion === 'anlegen') {
        $scope = Util::post('scope') === ApiKeys::SCOPE_WRITE ? ApiKeys::SCOPE_WRITE : ApiKeys::SCOPE_READ;
        $neu = ApiKeys::create(Util::post('label'), $scope,
            (string) ($currentUser['name'] ?: $currentUser['email']));
        // Einmalig zeigen – über die Sitzung, nicht über die Adresszeile.
        $_SESSION['api_neuer_key'] = $neu['key'];
        Util::flash('Schlüssel angelegt. Bitte jetzt kopieren – er wird nur dieses eine Mal gezeigt.');
        Util::redirect('api.php');
    }

    if ($aktion === 'widerrufen' && Util::postInt('id') > 0) {
        ApiKeys::revoke(Util::postInt('id'));
        Util::flash('Schlüssel widerrufen.');
        Util::redirect('api.php');
    }
}

$neuerKey = '';
if (!empty($_SESSION['api_neuer_key'])) {
    $neuerKey = (string) $_SESSION['api_neuer_key'];
    unset($_SESSION['api_neuer_key']);
}
$keys    = ApiKeys::all();
$apiBase = Config::url('api/v1.php');

/** Kleiner Helfer für die Doku: eine Endpunkt-Zeile. */
function api_zeile(string $method, string $pfad, string $text, bool $schreibt = false): string
{
    $farbe = $method === 'GET' ? 'ad-pill-blue' : ($method === 'DELETE' ? 'ad-pill-red' : 'ad-pill-green');
    return '<tr><td><span class="ad-pill ' . $farbe . '">' . $method . '</span></td>'
        . '<td class="ad-mono" style="white-space:nowrap;">' . Util::e($pfad) . '</td>'
        . '<td>' . Util::e($text) . ($schreibt ? ' <span class="ad-hint">(write)</span>' : '') . '</td></tr>';
}
?>

<div class="ad-page-head">
    <div>
        <h1>Schnittstelle (API)</h1>
        <p class="ad-sub">Daten von außen einspielen und abrufen – z. B. Mitglieder aus der Clubverwaltung.</p>
    </div>
</div>

<?php if ($neuerKey !== ''): ?>
    <div class="ad-card" style="border-color:var(--ad-red);">
        <h2 style="margin-top:0;">Ihr neuer Schlüssel</h2>
        <p>Bitte jetzt kopieren und sicher hinterlegen – aus Sicherheitsgründen wird er
            <strong>nur dieses eine Mal</strong> angezeigt.</p>
        <div class="ad-copybox">
            <code id="neuer-key"><?= Util::e($neuerKey) ?></code>
            <button type="button" class="ad-btn ad-btn-secondary ad-btn-small" data-kopiere="#neuer-key">Kopieren</button>
        </div>
    </div>
<?php endif; ?>

<!-- ------------------------------------------------ Schlüssel-Liste -->
<div class="ad-card">
    <div class="ad-page-head" style="margin-bottom:12px;">
        <h2 style="margin:0;">Schlüssel</h2>
        <a class="ad-btn" href="#neuer">Neuen Schlüssel anlegen</a>
    </div>

    <?php if ($keys === []): ?>
        <?= admin_empty('list', 'Noch kein Schlüssel',
            'Legen Sie einen Schlüssel an, um die Schnittstelle zu nutzen. Nur-Lesen für Abfragen, Lesen & Schreiben für den Mitglieder-Sync.',
            '<a class="ad-btn" href="#neuer">Ersten Schlüssel anlegen</a>') ?>
    <?php else: ?>
        <div class="ad-table-wrap">
            <table class="ad-table">
                <thead><tr><th>Name</th><th>Kennung</th><th>Rechte</th><th>Zuletzt genutzt</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($keys as $k): $aktiv = (int) $k['active'] === 1; ?>
                    <tr<?= $aktiv ? '' : ' style="opacity:.5;"' ?>>
                        <td><strong><?= Util::e((string) $k['label']) ?></strong>
                            <?php if (!$aktiv): ?><span class="ad-pill ad-pill-grey">widerrufen</span><?php endif; ?></td>
                        <td class="ad-mono"><?= Util::e((string) $k['prefix']) ?>…</td>
                        <td><?php if ((string) $k['scope'] === ApiKeys::SCOPE_WRITE): ?>
                                <span class="ad-pill ad-pill-green">Lesen &amp; Schreiben</span>
                            <?php else: ?><span class="ad-pill ad-pill-blue">Nur Lesen</span><?php endif; ?></td>
                        <td class="ad-mono"><?= Util::e(($k['last_used_at'] ?? '') !== '' ? Util::dt((string) $k['last_used_at']) : 'nie') ?></td>
                        <td style="text-align:right;white-space:nowrap;">
                            <?php if ($aktiv): ?>
                                <form method="post" style="display:inline;">
                                    <?= Util::csrfField() ?>
                                    <input type="hidden" name="aktion" value="widerrufen">
                                    <input type="hidden" name="id" value="<?= (int) $k['id'] ?>">
                                    <button type="submit" class="ad-btn ad-btn-danger ad-btn-small"
                                            data-confirm="Diesen Schlüssel widerrufen? Programme, die ihn nutzen, haben danach keinen Zugriff mehr.">Widerrufen</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <details class="ad-klapp" id="neuer" style="margin-top:16px;" <?= $keys === [] ? 'open' : '' ?>>
        <summary><h3 style="display:inline;">Neuen Schlüssel anlegen</h3>
            <span class="ad-klapp-zeichen" aria-hidden="true"></span></summary>
        <form method="post" style="margin-top:12px;">
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="anlegen">
            <div class="ad-row" style="align-items:flex-end;">
                <div class="ad-field" style="flex:2 1 260px;">
                    <label for="label">Name (wofür ist der Schlüssel?)</label>
                    <input type="text" id="label" name="label" maxlength="120" placeholder="z. B. PC CADDIE Mitglieder-Sync">
                </div>
                <div class="ad-field" style="flex:1 1 200px;">
                    <label for="scope">Rechte</label>
                    <select id="scope" name="scope">
                        <option value="read">Nur Lesen (GET)</option>
                        <option value="write">Lesen &amp; Schreiben</option>
                    </select>
                </div>
                <div class="ad-field" style="flex:0;">
                    <label>&nbsp;</label>
                    <button type="submit" class="ad-btn">Anlegen</button>
                </div>
            </div>
        </form>
    </details>
</div>

<!-- ------------------------------------------------ Doku -->
<div class="ad-card">
    <h2 style="margin-top:0;">Kurz-Anleitung</h2>

    <h3>Basis-Adresse</h3>
    <div class="ad-copybox">
        <code id="api-base"><?= Util::e($apiBase) ?></code>
        <button type="button" class="ad-btn ad-btn-secondary ad-btn-small" data-kopiere="#api-base">Kopieren</button>
    </div>
    <p class="ad-hint">Die Endpunkte hängen dahinter, z. B. <code><?= Util::e($apiBase) ?>/subscribers</code>.</p>

    <h3>Anmeldung</h3>
    <p>Bei jeder Anfrage den Schlüssel mitschicken – am besten im Kopf (Header):</p>
    <pre class="ad-code">Authorization: Bearer &lt;Ihr Schlüssel&gt;</pre>
    <p class="ad-hint">Alternativ Header <code>X-Api-Key: &lt;Schlüssel&gt;</code> oder – wenn gar nichts anderes
        geht – <code>?api_key=&lt;Schlüssel&gt;</code> an die Adresse. Antworten kommen immer als JSON.</p>

    <h3>Endpunkte</h3>
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>Methode</th><th>Pfad</th><th>Zweck</th></tr></thead>
            <tbody>
                <?= api_zeile('GET', '/ping', 'Verbindung & Schlüssel prüfen') ?>
                <?= api_zeile('GET', '/subscribers', 'Empfänger auflisten (Filter: list_id, status, q, created_since, limit, offset)') ?>
                <?= api_zeile('GET', '/subscribers/{id|email}', 'Einen Empfänger abrufen') ?>
                <?= api_zeile('POST', '/subscribers', 'Empfänger anlegen/aktualisieren (Schlüssel: E-Mail)', true) ?>
                <?= api_zeile('POST', '/subscribers/bulk', 'Viele auf einmal (Feld „subscribers": […])', true) ?>
                <?= api_zeile('DELETE', '/subscribers/{id|email}', 'Empfänger abmelden', true) ?>
                <?= api_zeile('GET', '/lists', 'Listen auflisten') ?>
                <?= api_zeile('POST', '/lists', 'Liste anlegen', true) ?>
                <?= api_zeile('GET', '/content', 'Redaktionspool lesen (Filter: category)') ?>
                <?= api_zeile('POST', '/content', 'Eintrag/Turnier in den Pool schreiben', true) ?>
                <?= api_zeile('GET', '/campaigns', 'Newsletter mit Kennzahlen abrufen') ?>
            </tbody>
        </table>
    </div>

    <h3>Beispiel: einen Empfänger anlegen</h3>
    <pre class="ad-code">curl -X POST <?= Util::e($apiBase) ?>/subscribers \
  -H "Authorization: Bearer &lt;Schlüssel&gt;" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "max@example.de",
    "first_name": "Max",
    "last_name": "Muster",
    "birthday": "1980-05-13",
    "lists": ["Clubnachrichten"],
    "custom": { "mitgliedsnummer": "A-1234" }
  }'</pre>
    <p class="ad-hint">Felder: <code>email</code> (Pflicht), <code>first_name</code>, <code>last_name</code>,
        <code>salutation</code>, <code>company</code>, <code>birthday</code> (JJJJ-MM-TT oder TT.MM.JJJJ),
        <code>status</code> (<code>active</code> oder <code>pending</code> = Bestätigungsmail),
        <code>lists</code> (Namen oder IDs) und <code>custom</code> (eigene Felder, z. B. Mitgliedsnummer).
        Gibt es die Adresse schon, wird sie aktualisiert.</p>

    <h3>Anbindung an PC CADDIE &amp; andere Clubverwaltungen</h3>
    <p>Diese Schnittstelle nimmt Mitgliederdaten aus jeder Quelle entgegen, die JSON senden kann –
        direkt aus der Verwaltung oder über ein kleines Zwischenskript, das den Mitglieder-Export
        (z. B. CSV aus PC CADDIE) einliest und per <code>/subscribers/bulk</code> hierher überträgt.
        Turniere lassen sich über <code>/content</code> in den Pool schreiben und erscheinen dann in
        Wochennews und der Turnier-Kommunikation.</p>
    <p class="ad-hint">Sobald die Zugangsdaten/Doku Ihrer Clubverwaltung vorliegen, lässt sich zusätzlich
        ein „von dort abrufen"-Anschluss ergänzen.</p>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
