<?php
/**
 * login.php – Anmeldung zur Newsletter-Verwaltung.
 */

require_once dirname(__DIR__) . '/lib/bootstrap.php';

if (Auth::check()) {
    Util::redirect('index.php');
}
if (Auth::userCount() === 0) {
    Util::redirect('../install.php');
}

$error  = '';
$email  = '';
$weiter = Util::get('weiter');

if (Util::isPost()) {
    Util::requireCsrf();
    $email = Util::post('email');
    $error = Auth::login($email, Util::postRaw('password'));
    if ($error === '') {
        // Nur seiteneigene Ziele zulassen (kein offener Redirect)
        $target = 'index.php';
        $wanted = Util::post('weiter');
        if ($wanted !== '' && preg_match('#^[a-z0-9_\-]+\.php(\?[^\s]*)?$#i', basename($wanted))) {
            $target = basename($wanted);
        }
        Util::redirect($target);
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Anmelden · Newsletter-Verwaltung</title>
<link rel="stylesheet" href="<?= Util::e(Util::asset('assets/admin.css', __DIR__)) ?>">
</head>
<body>
<div class="ad-login-wrap">
    <div class="ad-login">
        <h1>Newsletter-Verwaltung</h1>
        <p class="ad-sub"><?= Util::e(Settings::get('brand_name')) ?> · eigener Versand</p>

        <?php if ($error !== ''): ?>
            <div class="ad-flash ad-flash-error"><?= Util::e($error) ?></div>
        <?php endif; ?>

        <form method="post" autocomplete="on">
            <?= Util::csrfField() ?>
            <input type="hidden" name="weiter" value="<?= Util::e($weiter) ?>">
            <div class="ad-field">
                <label for="email">E-Mail-Adresse</label>
                <input type="email" id="email" name="email" required autofocus autocomplete="username"
                       value="<?= Util::e($email) ?>">
            </div>
            <div class="ad-field">
                <label for="password">Passwort</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="ad-btn">Anmelden</button>
        </form>
    </div>
</div>
</body>
</html>
