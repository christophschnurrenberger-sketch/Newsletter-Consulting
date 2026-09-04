<?php
/**
 * api/v1.php – die Schnittstelle (REST-API, Fassung 1).
 *
 * Aufruf:  {basis}/api/v1.php/<ressource>[/<id>]
 *   z. B.  https://verein.de/newsletter/api/v1.php/subscribers
 *
 * Anmeldung über einen API-Schlüssel (siehe Verwaltung → Schnittstelle):
 *   Authorization: Bearer acm_xxxxxxxx…      (empfohlen)
 *   oder Header  X-Api-Key: acm_xxxx…
 *   oder Abfrage ?api_key=acm_xxxx…          (nur wenn Header nicht gehen)
 *
 * Antwort immer JSON:
 *   Erfolg:  {"ok":true,"data":…[,"meta":…]}
 *   Fehler:  {"ok":false,"error":{"code":"…","message":"…"}}
 *
 * Schlüssel-Rechte: „read" darf nur GET, „write" auch POST und DELETE.
 * Jede Instanz hat ihre eigenen Schlüssel und ihre eigenen Daten.
 */
declare(strict_types=1);

require __DIR__ . '/../lib/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');
header('Referrer-Policy: no-referrer');

/* ------------------------------------------------------------- Antworten */

function api_out($data, int $code = 200, array $meta = []): void
{
    http_response_code($code);
    $body = ['ok' => true, 'data' => $data];
    if ($meta !== []) { $body['meta'] = $meta; }
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function api_err(string $code, string $message, int $http = 400): void
{
    http_response_code($http);
    echo json_encode(['ok' => false, 'error' => ['code' => $code, 'message' => $message]],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/* --------------------------------------------------------------- Eingang */

/** Den Schlüssel aus Header oder Abfrage lesen. */
function api_key_from_request(): string
{
    $auth = (string) ($_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '');
    if ($auth === '' && function_exists('getallheaders')) {
        foreach (getallheaders() as $name => $wert) {
            if (strcasecmp($name, 'Authorization') === 0) { $auth = (string) $wert; break; }
        }
    }
    if (preg_match('/Bearer\s+(\S+)/i', $auth, $m)) { return trim($m[1]); }
    if (!empty($_SERVER['HTTP_X_API_KEY'])) { return trim((string) $_SERVER['HTTP_X_API_KEY']); }
    if (!empty($_GET['api_key'])) { return trim((string) $_GET['api_key']); }
    return '';
}

/** Einfaches Limit je Schlüssel, damit die Schnittstelle nicht überrannt wird. */
function api_rate_limit(int $keyId): void
{
    $fenster = 60; $limit = 240;
    $seit = date('Y-m-d H:i:s', time() - $fenster);
    $ref  = 'k' . $keyId;
    $anzahl = (int) DB::value(
        "SELECT COUNT(*) FROM rate_limits WHERE action = 'api' AND ref = ? AND created_at >= ?",
        [$ref, $seit]);
    if ($anzahl >= $limit) {
        header('Retry-After: ' . $fenster);
        api_err('rate_limited', 'Zu viele Anfragen. Bitte kurz warten (max. ' . $limit . '/Minute).', 429);
    }
    DB::insert('rate_limits', ['action' => 'api', 'ref' => $ref, 'created_at' => Util::now()]);
}

/** Den JSON-Körper einlesen. */
function api_body(): array
{
    $roh = (string) file_get_contents('php://input');
    if (strlen($roh) > 4000000) {
        api_err('payload_too_large', 'Die Anfrage ist zu groß (max. 4 MB).', 413);
    }
    if (trim($roh) === '') { return []; }
    $daten = json_decode($roh, true);
    if (!is_array($daten)) {
        api_err('bad_json', 'Der Anfrage-Körper ist kein gültiges JSON.', 400);
    }
    return $daten;
}

/** Listen-Angaben (IDs oder Namen) in gültige Listen-IDs übersetzen. */
function api_resolve_lists($wert): array
{
    if (!is_array($wert)) { return []; }
    $ids = [];
    foreach ($wert as $eintrag) {
        if (is_int($eintrag) || (is_string($eintrag) && ctype_digit($eintrag))) {
            $ids[] = (int) $eintrag;
        } elseif (is_string($eintrag) && trim($eintrag) !== '') {
            foreach (Lists::all() as $liste) {
                if (strcasecmp((string) $liste['name'], trim($eintrag)) === 0) { $ids[] = (int) $liste['id']; break; }
            }
        }
    }
    return Subscribers::validListIds($ids);
}

/* ----------------------------------------------------------- Serialisieren */

/** Ein Empfänger als API-Objekt – ohne Token, IP oder Notiz. */
function api_sub(array $row): array
{
    return [
        'id'           => (int) $row['id'],
        'email'        => (string) $row['email'],
        'first_name'   => (string) $row['first_name'],
        'last_name'    => (string) $row['last_name'],
        'salutation'   => (string) $row['salutation'],
        'company'      => (string) $row['company'],
        'birthday'     => (string) ($row['birthday'] ?? ''),
        'status'       => (string) $row['status'],
        'source'       => (string) ($row['source'] ?? ''),
        'lists'        => Subscribers::listIds((int) $row['id']),
        'custom'       => Subscribers::custom($row),
        'created_at'   => (string) ($row['created_at'] ?? ''),
        'confirmed_at' => (string) ($row['confirmed_at'] ?? ''),
    ];
}

function api_list_obj(array $row): array
{
    static $counts = null;
    if ($counts === null) { $counts = Lists::activeCounts(); }
    return [
        'id'          => (int) $row['id'],
        'name'        => (string) $row['name'],
        'description' => (string) ($row['description'] ?? ''),
        'is_default'  => (int) ($row['is_default'] ?? 0) === 1,
        'active'      => (int) ($counts[(int) $row['id']] ?? 0),
    ];
}

function api_content_obj(array $row): array
{
    return [
        'id'         => (int) $row['id'],
        'category'   => (string) $row['category'],
        'title'      => (string) $row['title'],
        'body'       => (string) ($row['body'] ?? ''),
        'item_date'  => (string) ($row['item_date'] ?? ''),
        'date_until' => (string) ($row['date_until'] ?? ''),
        'link_url'   => (string) ($row['link_url'] ?? ''),
        'link_label' => (string) ($row['link_label'] ?? ''),
        'image_url'  => (string) ($row['image_url'] ?? ''),
        'active'     => (int) ($row['active'] ?? 1) === 1,
    ];
}

/* ----------------------------------------------------------- Ressourcen */

function api_ping(array $key): void
{
    api_out([
        'name'     => Settings::get('brand_name') ?: 'AcumenMail',
        'version'  => NL_VERSION,
        'time'     => Util::now(),
        'scope'    => (string) $key['scope'],
        'resources'=> ['subscribers', 'lists', 'content', 'campaigns'],
    ]);
}

function api_subscribers(string $method, string $id, callable $needWrite): void
{
    if ($method === 'GET' && $id === '') {
        $limit  = max(1, min(500, (int) ($_GET['limit'] ?? 50)));
        $offset = max(0, (int) ($_GET['offset'] ?? 0));
        $wo = ['1=1']; $args = [];
        $status = (string) ($_GET['status'] ?? '');
        if ($status !== '') { $wo[] = 's.status = ?'; $args[] = $status; }
        $q = trim((string) ($_GET['q'] ?? ''));
        if ($q !== '') { $wo[] = '(s.email LIKE ? OR s.first_name LIKE ? OR s.last_name LIKE ?)';
            $like = '%' . $q . '%'; array_push($args, $like, $like, $like); }
        $seit = trim((string) ($_GET['created_since'] ?? ''));
        if ($seit !== '') { $wo[] = 's.created_at >= ?'; $args[] = $seit; }
        $listId = (int) ($_GET['list_id'] ?? 0);
        if ($listId > 0) { $wo[] = 'EXISTS (SELECT 1 FROM subscriber_lists sl WHERE sl.subscriber_id = s.id AND sl.list_id = ?)';
            $args[] = $listId; }
        $where = implode(' AND ', $wo);
        $total = (int) DB::value("SELECT COUNT(*) FROM subscribers s WHERE $where", $args);
        $rows  = DB::all("SELECT s.* FROM subscribers s WHERE $where ORDER BY s.id DESC LIMIT $limit OFFSET $offset", $args);
        api_out(array_map('api_sub', $rows), 200, ['total' => $total, 'limit' => $limit, 'offset' => $offset]);
    }

    if ($method === 'GET') {
        $sub = api_find_sub($id);
        if ($sub === null) { api_err('not_found', 'Empfänger nicht gefunden.', 404); }
        api_out(api_sub($sub));
    }

    if ($method === 'POST' && $id === 'bulk') {
        $needWrite();
        $body = api_body();
        $rows = $body['subscribers'] ?? null;
        if (!is_array($rows) || $rows === []) {
            api_err('bad_request', 'Erwartet ein Feld „subscribers" mit mindestens einem Eintrag.', 422);
        }
        if (count($rows) > 2000) {
            api_err('too_many', 'Höchstens 2000 Empfänger je Anfrage.', 422);
        }
        $status = api_status((string) ($body['status'] ?? Subscribers::STATUS_ACTIVE));
        $listen = api_resolve_lists($body['lists'] ?? $body['list_ids'] ?? []);
        $zaehler = ['created' => 0, 'updated' => 0, 'skipped' => 0];
        $ergebnisse = [];
        foreach ($rows as $row) {
            if (!is_array($row)) { $zaehler['skipped']++; continue; }
            $reihe = api_resolve_lists($row['lists'] ?? []);
            $r = Subscribers::apiUpsert($row, $reihe !== [] ? $reihe : $listen, $status, 'api');
            $zaehler[$r['outcome']] = ($zaehler[$r['outcome']] ?? 0) + 1;
            $ergebnisse[] = ['email' => (string) ($row['email'] ?? ''), 'outcome' => $r['outcome'],
                'id' => $r['id'], 'reason' => $r['reason']];
        }
        Log::info('api', sprintf('Bulk-Sync: %d neu, %d aktualisiert, %d übersprungen.',
            $zaehler['created'], $zaehler['updated'], $zaehler['skipped']));
        api_out(['summary' => $zaehler, 'results' => $ergebnisse]);
    }

    if ($method === 'POST' && $id === '') {
        $needWrite();
        $row = api_body();
        if (trim((string) ($row['email'] ?? '')) === '') {
            api_err('bad_request', 'Es fehlt die E-Mail-Adresse.', 422);
        }
        $status = api_status((string) ($row['status'] ?? Subscribers::STATUS_ACTIVE));
        $listen = api_resolve_lists($row['lists'] ?? []);
        $r = Subscribers::apiUpsert($row, $listen, $status, 'api');
        if ($r['outcome'] === 'skipped') {
            api_err('skipped', 'Nicht übernommen: ' . $r['reason'], 422);
        }
        $sub = Subscribers::byId((int) $r['id']);
        api_out(api_sub((array) $sub) + ['outcome' => $r['outcome']], $r['outcome'] === 'created' ? 201 : 200);
    }

    if ($method === 'DELETE') {
        $needWrite();
        $sub = api_find_sub($id);
        if ($sub === null) { api_err('not_found', 'Empfänger nicht gefunden.', 404); }
        Subscribers::unsubscribe($sub, 'api', null, false);
        api_out(['id' => (int) $sub['id'], 'email' => $sub['email'], 'status' => 'unsubscribed']);
    }

    api_err('method_not_allowed', 'Methode für diese Ressource nicht erlaubt.', 405);
}

/** Empfänger anhand ID (Zahl) oder E-Mail finden. */
function api_find_sub(string $id): ?array
{
    $id = urldecode($id);
    if ($id === '') { return null; }
    return ctype_digit($id) ? Subscribers::byId((int) $id) : Subscribers::byEmail($id);
}

function api_status(string $status): string
{
    return in_array($status, [Subscribers::STATUS_ACTIVE, Subscribers::STATUS_PENDING], true)
        ? $status : Subscribers::STATUS_ACTIVE;
}

function api_lists(string $method, string $id, callable $needWrite): void
{
    if ($method === 'GET' && $id === '') {
        api_out(array_map('api_list_obj', Lists::all()));
    }
    if ($method === 'GET') {
        $liste = Lists::byId((int) $id);
        if ($liste === null) { api_err('not_found', 'Liste nicht gefunden.', 404); }
        api_out(api_list_obj($liste));
    }
    if ($method === 'POST' && $id === '') {
        $needWrite();
        $body = api_body();
        $name = trim((string) ($body['name'] ?? ''));
        if ($name === '') { api_err('bad_request', 'Es fehlt der Name der Liste.', 422); }
        $neu = Lists::create($name, (string) ($body['description'] ?? ''));
        api_out(api_list_obj((array) Lists::byId($neu)), 201);
    }
    api_err('method_not_allowed', 'Methode für diese Ressource nicht erlaubt.', 405);
}

function api_content(string $method, string $id, callable $needWrite): void
{
    if ($method === 'GET' && $id === '') {
        $cat = (string) ($_GET['category'] ?? '');
        $alle = Wochennews::all();
        if ($cat !== '') { $alle = array_values(array_filter($alle, static fn($r) => (string) $r['category'] === $cat)); }
        api_out(array_map('api_content_obj', $alle));
    }
    if ($method === 'GET') {
        $e = Wochennews::byId((int) $id);
        if ($e === null) { api_err('not_found', 'Eintrag nicht gefunden.', 404); }
        api_out(api_content_obj($e));
    }
    if ($method === 'POST' && $id === '') {
        $needWrite();
        $body = api_body();
        if (trim((string) ($body['title'] ?? '')) === '') {
            api_err('bad_request', 'Es fehlt der Titel.', 422);
        }
        $cat = (string) ($body['category'] ?? 'news');
        if (!isset(Wochennews::CATEGORIES[$cat])) {
            api_err('bad_request', 'Unbekannte Rubrik. Erlaubt: ' . implode(', ', array_keys(Wochennews::CATEGORIES)), 422);
        }
        $neu = Wochennews::add([
            'category'   => $cat,
            'title'      => (string) $body['title'],
            'body'       => (string) ($body['body'] ?? ''),
            'item_date'  => (string) ($body['item_date'] ?? ''),
            'date_until' => (string) ($body['date_until'] ?? ''),
            'link_url'   => (string) ($body['link_url'] ?? ''),
            'link_label' => (string) ($body['link_label'] ?? ''),
            'image_url'  => (string) ($body['image_url'] ?? ''),
            'created_by' => 'api',
        ]);
        api_out(api_content_obj((array) Wochennews::byId($neu)), 201);
    }
    api_err('method_not_allowed', 'Methode für diese Ressource nicht erlaubt.', 405);
}

function api_campaigns(string $method, string $id): void
{
    if ($method !== 'GET') { api_err('method_not_allowed', 'Kampagnen sind nur lesbar.', 405); }
    if ($id === '') {
        $out = [];
        foreach (Campaigns::all() as $c) {
            $st = Campaigns::stats((int) $c['id']);
            $out[] = ['id' => (int) $c['id'], 'name' => (string) $c['name'], 'subject' => (string) $c['subject'],
                'status' => (string) $c['status'], 'sent' => (int) $st['sent'],
                'open_rate' => (string) $st['open_rate'], 'click_rate' => (string) $st['click_rate']];
        }
        api_out($out);
    }
    $c = Campaigns::byId((int) $id);
    if ($c === null) { api_err('not_found', 'Kampagne nicht gefunden.', 404); }
    $st = Campaigns::stats((int) $c['id']);
    api_out(['id' => (int) $c['id'], 'name' => (string) $c['name'], 'subject' => (string) $c['subject'],
        'status' => (string) $c['status'], 'list_id' => $c['list_id'] !== null ? (int) $c['list_id'] : null,
        'stats' => $st]);
}

/* ------------------------------------------------------------- Ablauf */

try {
    $method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
    if ($method === 'OPTIONS') { http_response_code(204); exit; }

    $schluessel = api_key_from_request();
    $key = $schluessel === '' ? null : ApiKeys::verify($schluessel);
    if ($key === null) {
        header('WWW-Authenticate: Bearer');
        api_err('unauthorized', 'Kein gültiger API-Schlüssel. Erwartet: Header „Authorization: Bearer <Schlüssel>".', 401);
    }
    api_rate_limit((int) $key['id']);

    $needWrite = static function () use ($key): void {
        if (!ApiKeys::canWrite($key)) {
            api_err('forbidden', 'Dieser Schlüssel darf nur lesen (scope=read).', 403);
        }
    };

    $path  = trim((string) ($_SERVER['PATH_INFO'] ?? ''), '/');
    $parts = $path === '' ? [] : explode('/', $path);
    $res   = $parts[0] ?? '';
    $id    = $parts[1] ?? '';

    switch ($res) {
        case '':
        case 'ping':        api_ping($key); break;
        case 'subscribers': api_subscribers($method, $id, $needWrite); break;
        case 'lists':       api_lists($method, $id, $needWrite); break;
        case 'content':     api_content($method, $id, $needWrite); break;
        case 'campaigns':   api_campaigns($method, $id); break;
        default:            api_err('not_found', 'Unbekannte Ressource: „' . $res . '".', 404);
    }
} catch (Throwable $e) {
    try { Log::error('api', $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine()); } catch (Throwable $x) {}
    api_err('server_error', 'Interner Fehler. Der Vorfall wurde protokolliert.', 500);
}
