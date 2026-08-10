    </main>
</div>

<footer class="ad-footer">
    <span>Newslettersystem von <?= Util::e(Settings::get('brand_name')) ?> · eigener Versand, keine Drittanbieter</span>
    <span>Letzter Cron-Lauf: <?= Util::e(Util::dt(Settings::get('last_cron_at'))) ?></span>
</footer>

<script src="assets/admin.js"></script>
<?php foreach (($extraJs ?? []) as $js): ?>
<script src="<?= Util::e($js) ?>"></script>
<?php endforeach; ?>
</body>
</html>
