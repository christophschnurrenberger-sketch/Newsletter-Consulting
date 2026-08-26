    </main>
</div>

<footer class="ad-footer">
    <span>Newslettersystem von <?= Util::e(Settings::get('brand_name')) ?> · eigener Versand, keine Drittanbieter</span>
    <span>Fassung <?= Util::e(defined('NL_VERSION') ? NL_VERSION : '—') ?>
        · Datenbank <?= Util::e(Settings::get('schema_version')) ?>
        · Letzter Cron-Lauf: <?= Util::e(Util::dt(Settings::get('last_cron_at'))) ?></span>
</footer>

<script src="<?= Util::e(Util::asset('assets/admin.js', __DIR__ . '/..')) ?>"></script>
<?php foreach (($extraJs ?? []) as $js): ?>
<script src="<?= Util::e(Util::asset($js, __DIR__ . '/..')) ?>"></script>
<?php endforeach; ?>
</body>
</html>
