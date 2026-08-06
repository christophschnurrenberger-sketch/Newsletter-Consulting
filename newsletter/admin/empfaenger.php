<?php
/**
 * empfaenger.php – Empfängerliste mit Suche, Filter und Sammelaktionen.
 */

require_once dirname(__DIR__) . '/lib/bootstrap.php';

/* ------------------------------------------------------- CSV-Ausgabe */

if (Util::get('export') === '1') {
    Auth::require();
    $status = Util::get('status');
    $listId = Util::getInt('liste');

    $sql    = 'SELECT s.* FROM subscribers s WHERE 1=1';
    $params = [];
    if (in_array($status, array_keys(Subscribers::statusLabels()), true)) {
        $sql .= ' AND s.status = ?';
        $params[] = $status;
    }
    if ($listId > 0) {
        $sql .= ' AND EXISTS (SELECT 1 FROM subscriber_lists sl WHERE sl.subscriber_id = s.id AND sl.list_id = ?)';
        $params[] = $listId;
    }
    $rows = DB::all($sql . ' ORDER BY s.id', $params);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="empfaenger-' . date('Y-m-d') . '.csv"');
    echo "\xEF\xBB\xBF"; // BOM, damit Excel Umlaute korrekt zeigt
    echo Util::csvLine(['email', 'first_name', 'last_name', 'company', 'salutation', 'status',
                        'listen', 'angemeldet', 'bestaetigt', 'quelle']);
    foreach ($rows as $row) {
        $listen = array_map(static fn($id) => Lists::name($id), Subscribers::listIds((int) $row['id']));
        echo Util::csvLine([
            $row['email'], $row['first_name'], $row['last_name'], $row['company'], $row['salutation'],
            $row['status'], implode(' | ', $listen), $row['created_at'], $row['confirmed_at'], $row['source'],
        ]);
    }
    exit;
}

$pageTitle = 'Empfänger';
require __DIR__ . '/partials/header.php';

/* ---------------------------------------------------------- Aktionen */

if (Util::isPost()) {
    Util::requireCsrf();
    $action = Util::post('aktion');

    // Einzelnen Empfänger von Hand anlegen
    if ($action === 'anlegen') {
        $email = Util::normalizeEmail(Util::post('email'));
        try {
            if (!Util::isEmail($email)) {
                throw new InvalidArgumentException('Bitte geben Sie eine gültige E-Mail-Adresse an.');
            }
            if (Subscribers::byEmail($email) !== null) {
                throw new InvalidArgumentException('Diese Adresse ist bereits erfasst.');
            }
            $status  = Util::post('status') === 'active' ? 'active' : 'pending';
            $listIds = array_map('intval', (array) ($_POST['lists'] ?? []));
            $result  = Subscribers::import([[
                'email'      => $email,
                'first_name' => Util::post('first_name'),
                'last_name'  => Util::post('last_name'),
                'company'    => Util::post('company'),
                'salutation' => Util::post('salutation'),
            ]], $listIds, $status, 'manuell');

            Util::flash($status === 'active'
                ? 'Empfänger angelegt und als bestätigt markiert. <strong>Wichtig:</strong> Nur zulässig, wenn eine '
                  . 'dokumentierte Einwilligung vorliegt.'
                : 'Empfänger angelegt – die Bestätigungsmail ist unterwegs.');
            Util::redirect('empfaenger.php');
        } catch (Throwable $e) {
            Util::flash(Util::e($e->getMessage()), 'error');
            Util::redirect('empfaenger.php?neu=1');
        }
    }

    // Sammelaktionen
    $ids = array_map('intval', (array) ($_POST['ids'] ?? []));
    if ($ids !== [] && in_array($action, ['abmelden', 'aktivieren', 'loeschen', 'liste_zu', 'liste_weg'], true)) {
        $count = 0;
        foreach ($ids as $subId) {
            $sub = Subscribers::byId($subId);
            if ($sub === null) {
                continue;
            }
            switch ($action) {
                case 'abmelden':
                    Subscribers::unsubscribe($sub, 'Abmeldung durch die Verwaltung', null, false);
                    $count++;
                    break;
                case 'aktivieren':
                    DB::update('subscribers', [
                        'status'       => Subscribers::STATUS_ACTIVE,
                        'confirmed_at' => $sub['confirmed_at'] ?: Util::now(),
                    ], 'id = ?', [$subId]);
                    Subscribers::logConsent($subId, (string) $sub['email'], 'admin_activate',
                        'Durch die Verwaltung aktiviert (Einwilligung muss dokumentiert sein)');
                    $count++;
                    break;
                case 'loeschen':
                    Subscribers::deleteCompletely($subId);
                    $count++;
                    break;
                case 'liste_zu':
                    Subscribers::addToLists($subId, [Util::postInt('ziel_liste')]);
                    $count++;
                    break;
                case 'liste_weg':
                    DB::delete('subscriber_lists', 'subscriber_id = ? AND list_id = ?', [$subId, Util::postInt('ziel_liste')]);
                    $count++;
                    break;
            }
        }
        Util::flash(Util::num($count) . ' Empfänger bearbeitet.');
        Util::redirect('empfaenger.php?' . http_build_query(array_filter([
            'status' => Util::post('f_status'),
            'liste'  => Util::post('f_liste'),
            'q'      => Util::post('f_q'),
        ])));
    }
}

/* ------------------------------------------------------------ Ansicht */

$search   = Util::get('q');
$status   = Util::get('status');
$listId   = Util::getInt('liste');
$page     = max(1, Util::getInt('seite', 1));
$perPage  = 50;

$where  = ['1=1'];
$params = [];
if ($search !== '') {
    $where[]  = '(s.email LIKE ? OR s.first_name LIKE ? OR s.last_name LIKE ? OR s.company LIKE ?)';
    $like     = '%' . $search . '%';
    array_push($params, $like, $like, $like, $like);
}
if (in_array($status, array_keys(Subscribers::statusLabels()), true)) {
    $where[]  = 's.status = ?';
    $params[] = $status;
}
if ($listId > 0) {
    $where[]  = 'EXISTS (SELECT 1 FROM subscriber_lists sl WHERE sl.subscriber_id = s.id AND sl.list_id = ?)';
    $params[] = $listId;
}
$whereSql = implode(' AND ', $where);

$total = (int) DB::value('SELECT COUNT(*) FROM subscribers s WHERE ' . $whereSql, $params);
$pages = max(1, (int) ceil($total / $perPage));
$page  = min($page, $pages);

$rows = DB::all(
    'SELECT s.* FROM subscribers s WHERE ' . $whereSql . ' ORDER BY s.id DESC LIMIT ' . $perPage
    . ' OFFSET ' . (($page - 1) * $perPage),
    $params
);

$counts    = Subscribers::statusCounts();
$showNew   = Util::get('neu') === '1';
$queryBase = array_filter(['q' => $search, 'status' => $status, 'liste' => $listId ?: '']);
?>

<div class="ad-page-head">
    <div>
        <h1>Empfänger</h1>
        <p class="ad-sub">
            <?= Util::num($counts[Subscribers::STATUS_ACTIVE]) ?> aktiv ·
            <?= Util::num($counts[Subscribers::STATUS_PENDING]) ?> unbestätigt ·
            <?= Util::num($counts[Subscribers::STATUS_UNSUBSCRIBED]) ?> abgemeldet ·
            <?= Util::num($counts[Subscribers::STATUS_BOUNCED] + $counts[Subscribers::STATUS_COMPLAINED]) ?> gesperrt
        </p>
    </div>
    <div class="ad-actions-inline">
        <a class="ad-btn" href="empfaenger.php?neu=1">Empfänger anlegen</a>
        <a class="ad-btn ad-btn-secondary" href="import.php">Import</a>
        <a class="ad-btn ad-btn-secondary" href="empfaenger.php?export=1&amp;<?= Util::e(http_build_query($queryBase)) ?>">Export (CSV)</a>
    </div>
</div>

<?php if ($showNew): ?>
    <div class="ad-card">
        <h2>Empfänger von Hand anlegen</h2>
        <form method="post">
            <?= Util::csrfField() ?>
            <input type="hidden" name="aktion" value="anlegen">
            <div class="ad-row">
                <div class="ad-field">
                    <label for="email">E-Mail-Adresse</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="ad-field">
                    <label for="first_name">Vorname</label>
                    <input type="text" id="first_name" name="first_name">
                </div>
                <div class="ad-field">
                    <label for="last_name">Nachname</label>
                    <input type="text" id="last_name" name="last_name">
                </div>
            </div>
            <div class="ad-row">
                <div class="ad-field">
                    <label for="company">Unternehmen</label>
                    <input type="text" id="company" name="company">
                </div>
                <div class="ad-field">
                    <label for="salutation">Anrede</label>
                    <select id="salutation" name="salutation">
                        <option value="">— keine —</option>
                        <option value="Frau">Frau</option>
                        <option value="Herr">Herr</option>
                    </select>
                </div>
                <div class="ad-field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="pending">Bestätigungsmail senden (empfohlen)</option>
                        <option value="active">Direkt aktiv – Einwilligung liegt vor</option>
                    </select>
                </div>
            </div>
            <?php if (count(Lists::all()) > 0): ?>
                <div class="ad-field">
                    <span class="ad-label">Listen</span>
                    <?php foreach (Lists::all() as $list): ?>
                        <label class="ad-check">
                            <input type="checkbox" name="lists[]" value="<?= (int) $list['id'] ?>"
                                <?= (int) $list['is_default'] === 1 ? 'checked' : '' ?>>
                            <span><?= Util::e((string) $list['name']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="ad-actions">
                <button type="submit" class="ad-btn">Anlegen</button>
                <a class="ad-btn ad-btn-secondary" href="empfaenger.php">Abbrechen</a>
            </div>
            <p class="ad-hint">Rechtlicher Hinweis: „Direkt aktiv“ ist nur zulässig, wenn Sie die Einwilligung
                nachweisen können (z. B. aus einem früheren System). Im Zweifel immer die Bestätigungsmail wählen.</p>
        </form>
    </div>
<?php endif; ?>

<div class="ad-card ad-card-tight">
    <form method="get" class="ad-row" style="align-items:flex-end;">
        <div class="ad-field" style="margin:0;flex:2 1 260px;">
            <label for="q">Suche</label>
            <input type="text" id="q" name="q" value="<?= Util::e($search) ?>" placeholder="Adresse, Name oder Firma">
        </div>
        <div class="ad-field" style="margin:0;">
            <label for="f_status">Status</label>
            <select id="f_status" name="status">
                <option value="">Alle</option>
                <?php foreach (Subscribers::statusLabels() as $key => $label): ?>
                    <option value="<?= Util::e($key) ?>" <?= $status === $key ? 'selected' : '' ?>>
                        <?= Util::e($label) ?> (<?= Util::num($counts[$key]) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="ad-field" style="margin:0;">
            <label for="f_liste">Liste</label>
            <select id="f_liste" name="liste">
                <option value="">Alle</option>
                <?php foreach (Lists::all() as $list): ?>
                    <option value="<?= (int) $list['id'] ?>" <?= $listId === (int) $list['id'] ? 'selected' : '' ?>>
                        <?= Util::e((string) $list['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="ad-field" style="margin:0;">
            <button type="submit" class="ad-btn ad-btn-secondary">Filtern</button>
        </div>
    </form>
</div>

<?php if ($rows === []): ?>
    <div class="ad-empty">Keine Empfänger gefunden.</div>
<?php else: ?>
    <form method="post">
        <?= Util::csrfField() ?>
        <input type="hidden" name="f_status" value="<?= Util::e($status) ?>">
        <input type="hidden" name="f_liste" value="<?= (int) $listId ?>">
        <input type="hidden" name="f_q" value="<?= Util::e($search) ?>">

        <div class="ad-table-wrap">
            <table class="ad-table">
                <thead>
                <tr>
                    <th style="width:34px;"><input type="checkbox" data-check-all="ids[]" aria-label="Alle auswählen"></th>
                    <th>Adresse</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Listen</th>
                    <th>Angemeldet</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $row):
                    $listen = array_map(static fn($lid) => Lists::name($lid), Subscribers::listIds((int) $row['id'])); ?>
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="<?= (int) $row['id'] ?>"
                                   aria-label="<?= Util::e((string) $row['email']) ?> auswählen"></td>
                        <td class="ad-mono">
                            <a href="empfaenger-detail.php?id=<?= (int) $row['id'] ?>"><?= Util::e((string) $row['email']) ?></a>
                        </td>
                        <td><?= Util::e(trim((string) $row['first_name'] . ' ' . (string) $row['last_name']) ?: '—') ?>
                            <?php if ((string) $row['company'] !== ''): ?>
                                <div class="ad-hint"><?= Util::e((string) $row['company']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td><?= subscriber_status_pill((string) $row['status']) ?></td>
                        <td class="ad-hint"><?= Util::e(implode(', ', $listen) ?: '—') ?></td>
                        <td><?= Util::e(Util::dt((string) $row['created_at'], 'd.m.Y')) ?></td>
                        <td><a class="ad-btn ad-btn-secondary ad-btn-small" href="empfaenger-detail.php?id=<?= (int) $row['id'] ?>">Details</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="ad-card ad-card-tight">
            <div class="ad-row" style="align-items:flex-end;">
                <div class="ad-field" style="margin:0;">
                    <label for="aktion">Mit ausgewählten Empfängern …</label>
                    <select id="aktion" name="aktion">
                        <option value="">— Aktion wählen —</option>
                        <option value="aktivieren">Als aktiv markieren</option>
                        <option value="abmelden">Abmelden</option>
                        <option value="liste_zu">Zu Liste hinzufügen</option>
                        <option value="liste_weg">Aus Liste entfernen</option>
                        <option value="loeschen">Endgültig löschen</option>
                    </select>
                </div>
                <div class="ad-field" style="margin:0;">
                    <label for="ziel_liste">Liste (für Listen-Aktionen)</label>
                    <select id="ziel_liste" name="ziel_liste">
                        <?php foreach (Lists::all() as $list): ?>
                            <option value="<?= (int) $list['id'] ?>"><?= Util::e((string) $list['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="ad-field" style="margin:0;">
                    <button type="submit" class="ad-btn ad-btn-secondary"
                            data-confirm="Aktion für die ausgewählten Empfänger wirklich ausführen?">Ausführen</button>
                </div>
            </div>
        </div>
    </form>

    <?php if ($pages > 1): ?>
        <div class="ad-actions">
            <?php for ($p = 1; $p <= $pages; $p++):
                $query = $queryBase + ['seite' => $p]; ?>
                <a class="ad-btn ad-btn-small <?= $p === $page ? '' : 'ad-btn-secondary' ?>"
                   href="empfaenger.php?<?= Util::e(http_build_query($query)) ?>"><?= $p ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

    <p class="ad-hint"><?= Util::num($total) ?> Empfänger gefunden · Seite <?= $page ?> von <?= $pages ?></p>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php';
