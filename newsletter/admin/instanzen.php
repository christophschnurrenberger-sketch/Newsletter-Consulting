<?php
/**
 * instanzen.php – alle Installationen auf einen Blick.
 *
 * Jeder Kunde bekommt seine eigene Installation (eigener Ordner, eigene
 * Datenbank, eigene Zugänge). Diese Seite holt sich von jeder davon ein
 * paar Zahlen und zeigt sie nebeneinander – damit man nicht in jede
 * einzeln hineinschauen muss, nur um zu sehen, ob der Cron-Job läuft.
 *
 * Abgefragt wird über status.php der jeweiligen Installation, mit deren
 * cron_token. Zurück kommen nur Zahlen, keine Adressen (siehe status.php).
 */

$pageTitle     = 'Instanzen';
$requiredRight = 'einstellungen';
require __DIR__ . '/partials/header.php';

if (Util::isPost()) {
    Util::requireCsrf();
    $aktion = Util::post('aktion');

    if ($aktion === 'anlegen') {
        try {
            Instanzen::add(Util::post('name'), Util::post('url'), Util::post('token'));
            Util::flash('Installation eingetragen. Der Bericht wird gleich geholt.');
        } catch (Throwable $e) {
            Util::flash($e->getMessage(), 'error');
        }
        Util::redirect('instanzen.php');
    }

    if ($aktion === 'entfernen') {
        Instanzen::remove(Util::post('url'));
        Util::flash('Installation aus der Liste genommen. Dort selbst ändert sich nichts.');
        Util::redirect('instanzen.php');
    }
}

$fremde = Instanzen::all();

/* Die eigene Installation steht immer vorn – ohne Umweg über das Netz. */
$zeilen = [[
    'name'   => Settings::get('brand_name') ?: 'Diese Installation',
    'url'    => rtrim(Config::get('base_url', ''), '/'),
    'eigen'  => true,
    'status' => Instanzen::eigene(),
]];
foreach ($fremde as $instanz) {
    $zeilen[] = [
        'name'   => $instanz['name'],
        'url'    => $instanz['url'],
        'eigen'  => false,
        'status' => Instanzen::status($instanz),
    ];
}

/** Wie lange ist der letzte Cron-Lauf her? */
function instanz_cron(string $wann): string
{
    if (trim($wann) === '') {
        return '<span class="ad-pill ad-pill-red">nie gelaufen</span>';
    }
    $alter = time() - (int) strtotime($wann);
    if ($alter > 3600) {
        return '<span class="ad-pill ad-pill-red">' . Util::e(Util::dt($wann)) . '</span>';
    }
    if ($alter > 900) {
        return '<span class="ad-pill ad-pill-amber">' . Util::e(Util::dt($wann)) . '</span>';
    }
    return '<span class="ad-pill ad-pill-green">' . Util::e(Util::dt($wann)) . '</span>';
}
?>

<div class="ad-page-head">
    <div>
        <h1>Instanzen</h1>
        <p class="ad-sub">Alle Installationen nebeneinander: Empfänger, Newsletter, Warteschlange und
            Cron-Lauf. Jede Installation bleibt für sich – hier wird nur nachgesehen, nicht geändert.</p>
    </div>
</div>

<div class="ad-instanzen">
    <?php foreach ($zeilen as $zeile):
        $s = $zeile['status'];
        $lebt = ($s['ok'] ?? false) === true; ?>
        <div class="ad-card ad-instanz <?= $zeile['eigen'] ? 'ist-eigen' : '' ?>">
            <div class="ad-instanz-kopf">
                <div>
                    <h2><?= Util::e((string) $zeile['name']) ?>
                        <?php if ($zeile['eigen']): ?>
                            <span class="ad-pill ad-pill-blue">diese Installation</span>
                        <?php endif; ?>
                    </h2>
                    <p class="ad-hint ad-mono"><?= Util::e((string) $zeile['url']) ?></p>
                </div>
                <div class="ad-actions-inline">
                    <?php if ($zeile['eigen']): ?>
                        <a class="ad-btn ad-btn-secondary ad-btn-small" href="index.php">Übersicht</a>
                    <?php else: ?>
                        <a class="ad-btn ad-btn-secondary ad-btn-small" target="_blank" rel="noopener"
                           href="<?= Util::e($zeile['url'] . '/admin/login.php') ?>">Öffnen</a>
                        <details class="ad-menue">
                            <summary class="ad-btn ad-btn-secondary ad-btn-small" title="Weitere Aktionen">…</summary>
                            <div class="ad-menue-liste">
                                <form method="post">
                                    <?= Util::csrfField() ?>
                                    <input type="hidden" name="aktion" value="entfernen">
                                    <input type="hidden" name="url" value="<?= Util::e($zeile['url']) ?>">
                                    <button type="submit" class="ist-gefahr"
                                            data-confirm="Diese Installation nur aus der Liste nehmen? Dort selbst ändert sich nichts.">Aus der Liste nehmen</button>
                                </form>
                            </div>
                        </details>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!$lebt): ?>
                <div class="ad-flash ad-flash-warning" style="margin:12px 0 0;">
                    <?= Util::e((string) ($s['fehler'] ?? 'Kein Bericht erhalten.')) ?>
                </div>
            <?php else: ?>
                <div class="ad-instanz-zahlen">
                    <div>
                        <span class="ad-stat-label">Aktive Empfänger</span>
                        <strong><?= Util::num((int) $s['empfaenger']['aktiv']) ?></strong>
                        <span class="ad-hint"><?= Util::num((int) $s['empfaenger']['unbestaetigt']) ?> unbestätigt</span>
                    </div>
                    <div>
                        <span class="ad-stat-label">Newsletter</span>
                        <strong><?= Util::num((int) $s['newsletter']['gesamt']) ?></strong>
                        <span class="ad-hint"><?= Util::num((int) $s['newsletter']['versendet']) ?> versendet,
                            <?= Util::num((int) $s['newsletter']['entwurf']) ?> Entwürfe</span>
                    </div>
                    <div>
                        <span class="ad-stat-label">Warteschlange</span>
                        <strong><?= Util::num((int) $s['versand']['offen']) ?></strong>
                        <span class="ad-hint"><?= Util::num((int) $s['versand']['heute']) ?> heute versendet<?php
                            if ((int) $s['versand']['fehler'] > 0): ?>,
                            <?= Util::num((int) $s['versand']['fehler']) ?> fehlgeschlagen<?php endif; ?></span>
                    </div>
                    <div>
                        <span class="ad-stat-label">Listen &amp; Automationen</span>
                        <strong><?= Util::num((int) $s['listen']) ?> / <?= Util::num((int) $s['automationen']) ?></strong>
                        <span class="ad-hint">Verteiler / Strecken</span>
                    </div>
                </div>

                <p class="ad-instanz-fuss">
                    Marken: <?= $s['marken'] === [] ? '—' : Util::e(implode(', ', (array) $s['marken'])) ?>
                    · Letzter Cron-Lauf: <?= instanz_cron((string) $s['versand']['letzter_cron']) ?>
                    · Fassung <?= Util::e((string) $s['version']) ?>
                    <?php if (defined('NL_VERSION') && !$zeile['eigen'] && (string) $s['version'] !== NL_VERSION): ?>
                        <span class="ad-pill ad-pill-amber">andere Fassung als hier</span>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<!-- ------------------------------------------------ Weitere eintragen -->

<details class="ad-card ad-klapp" id="neue-instanz" <?= $fremde === [] ? 'open' : '' ?>>
    <summary>
        <h2>Weitere Installation eintragen</h2>
        <span class="ad-klapp-zeichen" aria-hidden="true"></span>
    </summary>

    <p class="ad-hint" style="margin-top:12px;">Sie brauchen zwei Angaben aus der anderen Installation:
        ihre Adresse (der Ordner, in dem <code>admin/</code> liegt) und den Wert <code>cron_token</code>
        aus deren <code>config.php</code>. Denselben Schlüssel benutzt dort schon der Cron-Job.</p>

    <form method="post">
        <?= Util::csrfField() ?>
        <input type="hidden" name="aktion" value="anlegen">
        <div class="ad-row">
            <div class="ad-field">
                <label for="i_name">Name</label>
                <input type="text" id="i_name" name="name" maxlength="120" placeholder="z. B. Golfclub Ottobeuren">
            </div>
            <div class="ad-field" style="flex:2 1 320px;">
                <label for="i_url">Adresse der Installation</label>
                <input type="text" id="i_url" name="url" required placeholder="https://www.kunde.de/newsletter">
            </div>
            <div class="ad-field">
                <label for="i_token">cron_token</label>
                <input type="text" id="i_token" name="token" maxlength="190" class="ad-mono">
            </div>
        </div>
        <div class="ad-actions">
            <button type="submit" class="ad-btn">Eintragen</button>
        </div>
    </form>
</details>

<details class="ad-klapp ad-card" id="neue-anlegen" data-merken="instanzen-anlegen">
    <summary>
        <h2>Wie lege ich eine neue Installation an?</h2>
        <span class="ad-klapp-zeichen" aria-hidden="true"></span>
    </summary>
    <p style="margin-top:12px;">Eine Installation ist ein eigener Ordner auf dem Webspace, mit eigener
        <code>config.php</code>, eigener Datenbank und eigenen Zugängen. Der Programmcode ist überall
        derselbe – kopieren müssen Sie nichts von Hand.</p>
    <ol style="margin:10px 0 0 18px;padding:0;line-height:1.7;">
        <li>Auf GitHub unter <em>Actions → „Newsletter zu IONOS“ → Run workflow</em> bei
            <em>Neue Instanz</em> den Ordner eintragen, etwa <code>/kunden/ottobeuren</code>.
            Dieser Lauf legt den Ordner an und schickt <code>install.php</code> mit.</li>
        <li>Im Browser <code>…/kunden/ottobeuren/install.php</code> aufrufen und ausfüllen:
            Datenbank, Absender, erster Zugang. Danach <code>install.php</code> vom Server löschen.</li>
        <li>Den Ordner im Repository zum Geheimnis <code>IONOS_ZIEL</code> hinzufügen – eine Zeile
            je Ordner. Ab dann bekommt die neue Installation jede Aktualisierung automatisch mit.</li>
        <li>Zurück auf dieser Seite eintragen, mit Adresse und <code>cron_token</code> aus deren
            <code>config.php</code>. Dann steht sie oben mit ihren Zahlen dabei.</li>
    </ol>
    <p class="ad-hint" style="margin:12px 0 0;">Ein Update spielen Sie nicht mehr einzeln ein: Ein
        Push aktualisiert alle Ordner, die in <code>IONOS_ZIEL</code> stehen.</p>
</details>

<?php require __DIR__ . '/partials/footer.php'; ?>
