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
$weiter = Util::isPost() ? Util::post('weiter') : Util::get('weiter');

/* „Andere Anmeldung“: den halben Vorgang wegwerfen und neu beginnen. */
if (Util::get('abbrechen') === '1') {
    Auth::logout();
    Util::redirect('login.php');
}

/* Wartet dieser Browser schon auf die Zahl aus der App? */
$wartet = Auth::wartetAufZweitenFaktor();

/** Nur seiteneigene Ziele zulassen (kein offener Redirect). */
$weiterleiten = static function (string $wunsch): void {
    $ziel = 'index.php';
    if ($wunsch !== '' && preg_match('#^[a-z0-9_\-]+\.php(\?[^\s]*)?$#i', basename($wunsch))) {
        $ziel = basename($wunsch);
    }
    Util::redirect($ziel);
};

if (Util::isPost()) {
    Util::requireCsrf();

    if (Util::post('schritt') === 'zwei') {
        $error = Auth::zweiterFaktor(Util::post('code'));
        if ($error === '') {
            $weiterleiten($weiter);
        }
        $wartet = Auth::wartetAufZweitenFaktor();
    } else {
        $email = Util::post('email');
        $error = Auth::login($email, Util::postRaw('password'));
        if ($error === '') {
            $weiterleiten($weiter);
        }
        if ($error === Auth::ZWEITER_FAKTOR) {
            $error  = '';
            $wartet = Auth::wartetAufZweitenFaktor();
        }
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

        <?php if ($wartet !== null): ?>

            <p>Bitte geben Sie die sechsstellige Zahl aus Ihrer Authenticator-App ein.
                Sie wechselt alle 30 Sekunden.</p>

            <form method="post" autocomplete="off">
                <?= Util::csrfField() ?>
                <input type="hidden" name="schritt" value="zwei">
                <input type="hidden" name="weiter" value="<?= Util::e($weiter) ?>">
                <div class="ad-field">
                    <label for="code">Zahl aus der App</label>
                    <input type="text" id="code" name="code" required autofocus
                           inputmode="numeric" autocomplete="one-time-code"
                           maxlength="13" placeholder="123456"
                           style="font-family:ui-monospace,Menlo,Consolas,monospace;
                                  font-size:22px;letter-spacing:.18em;text-align:center;">
                </div>
                <button type="submit" class="ad-btn">Weiter</button>
            </form>

            <p class="ad-hint" style="margin-top:16px;">Telefon nicht zur Hand? Geben Sie oben
                statt der Zahl einen Ihrer Ersatzcodes ein. Jeder gilt genau einmal.</p>
            <p class="ad-hint"><a href="login.php?abbrechen=1">Andere Anmeldung</a></p>

        <?php else: ?>

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

        <?php endif; ?>
    </div>
</div>
</body>
</html>
