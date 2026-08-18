<?php
/**
 * anmelden.php – Landingpage mit dem Anmeldeformular.
 * Funktioniert vollständig ohne JavaScript.
 */

require __DIR__ . '/lib/bootstrap.php';
require __DIR__ . '/partials/page.php';

$status  = Util::get('status');
$meldung = mb_substr(Util::get('meldung'), 0, 200);
$notice  = '';

switch ($status) {
    case 'created':
    case 'resent':
    case 'suppressed':
        $notice = nl_notice('success', 'Fast geschafft – bitte bestätigen Sie Ihre Anmeldung',
            'Wir haben Ihnen eine E-Mail geschickt. Klicken Sie darin auf den Bestätigungslink, '
            . 'dann sind Sie dabei. Die Mail nicht gefunden? Schauen Sie bitte auch im Spam-Ordner nach.');
        break;
    case 'already_active':
        $notice = nl_notice('info', 'Diese Adresse ist bereits angemeldet',
            'Sie erhalten unseren Newsletter bereits. Über den Link „Daten &amp; Einstellungen“ in jeder '
            . 'Ausgabe können Sie Ihre Angaben ändern.');
        break;
    case 'error':
        $notice = nl_notice('error', 'Die Anmeldung hat nicht geklappt',
            $meldung !== '' ? Util::e($meldung) : 'Bitte prüfen Sie Ihre Eingaben und versuchen Sie es erneut.');
        break;
}

// Die Themenauswahl erscheint nur, wenn es mehr als eine Liste gibt.
$lists = Lists::all();
$brand = Settings::get('brand_name');

ob_start();
?>
<div class="nl-card">
    <?= $notice ?>

    <?php if (in_array($status, ['created', 'resent', 'suppressed'], true)): ?>
        <h1>Bitte bestätigen Sie Ihre Anmeldung</h1>
        <p class="nl-lead">Aus rechtlichen Gründen verschicken wir den Newsletter erst, wenn Sie Ihre
            Anmeldung per Klick bestätigt haben (Double-Opt-in). So kann sich niemand mit Ihrer Adresse anmelden.</p>
        <ol class="nl-steps">
            <li><strong>Postfach öffnen</strong> – wir haben Ihnen soeben eine E-Mail geschickt.</li>
            <li><strong>Bestätigungslink klicken</strong> – der Link ist 14 Tage gültig.</li>
            <li><strong>Fertig</strong> – Sie erhalten die nächste Ausgabe automatisch.</li>
        </ol>
    <?php else: ?>
        <h1>Newsletter von <?= Util::e($brand) ?></h1>
        <p class="nl-lead">Alle paar Wochen ein kompakter Impuls zu E-Mail-Marketing, Automatisierung und
            Zustellbarkeit – aus der Praxis, ohne Marketing-Floskeln, jederzeit abbestellbar.</p>

        <?= nl_signup_form(['email' => Util::get('email')], $lists) ?>
    <?php endif; ?>
</div>

<?php if (Settings::bool('archive_enabled')): ?>
    <p style="text-align:center;margin-top:24px;font-size:14px;">
        <a href="archiv.php">Frühere Ausgaben im Archiv ansehen</a>
    </p>
<?php endif; ?>
<?php
nl_page('Newsletter abonnieren', (string) ob_get_clean());
