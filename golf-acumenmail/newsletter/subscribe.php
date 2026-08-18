<?php
/**
 * subscribe.php – nimmt Anmeldungen aus dem Formular entgegen.
 *
 * Wird sowohl von der Landingpage (anmelden.php) als auch vom kurzen
 * Formular auf der Website benutzt. Antwortet je nach Aufruf mit einer
 * Weiterleitung (ohne JavaScript) oder mit JSON (mit JavaScript).
 *
 * Schutz vor Missbrauch:
 *   – verstecktes Feld (Honeypot)
 *   – signierter Zeitstempel: nicht zu schnell, nicht zu alt
 *   – Rate-Limit je IP-Adresse und je Adresse
 *   – Pflicht-Einwilligung + Double-Opt-in
 */

require __DIR__ . '/lib/bootstrap.php';
require __DIR__ . '/partials/page.php';

/** Antwort ausliefern – je nach Aufruf als JSON oder Weiterleitung. */
function respond(bool $ok, string $status, string $message): void
{
    if (Util::wantsJson()) {
        Util::json(['ok' => $ok, 'status' => $status, 'message' => $message], $ok ? 200 : 422);
    }
    $target = 'anmelden.php?status=' . rawurlencode($status);
    if (!$ok) {
        $target .= '&meldung=' . rawurlencode(mb_substr($message, 0, 200));
    }
    Util::redirect($target);
}

if (!Util::isPost()) {
    Util::redirect('anmelden.php');
}

/* 1) Honeypot – nur Bots füllen dieses Feld aus. Antwort bleibt freundlich. */
if (Util::post('website') !== '') {
    respond(true, 'created', 'Bitte prüfen Sie Ihr Postfach und bestätigen Sie die Anmeldung.');
}

/* 2) Zeitprüfung: Formular muss aus diesem System stammen und darf weder
      in Sekundenbruchteilen noch nach Tagen abgeschickt worden sein. */
$ts   = Util::postInt('ts');
$tsig = Util::post('tsig');
if ($ts > 0 && $tsig !== '') {
    if (!Util::checkSign('signup:' . $ts, $tsig)) {
        respond(false, 'error', 'Das Formular ist ungültig. Bitte laden Sie die Seite neu.');
    }
    $elapsed = time() - $ts;
    if ($elapsed < 2) {
        respond(true, 'created', 'Bitte prüfen Sie Ihr Postfach und bestätigen Sie die Anmeldung.');
    }
    if ($elapsed > 86400) {
        respond(false, 'error', 'Das Formular ist abgelaufen. Bitte laden Sie die Seite neu.');
    }
}

/* 3) Einwilligung ist Pflicht (DSGVO Art. 6 Abs. 1 lit. a). */
if (Util::post('consent') === '' && Util::post('einwilligung') === '') {
    respond(false, 'error', 'Bitte bestätigen Sie, dass Sie den Newsletter erhalten möchten.');
}

/* 4) Adresse prüfen. */
$email = Util::normalizeEmail(Util::post('email'));
if (!Util::isEmail($email)) {
    respond(false, 'error', 'Bitte geben Sie eine gültige E-Mail-Adresse an.');
}
$domain = Util::emailDomain($email);
if ($domain !== '' && function_exists('checkdnsrr')) {
    $reachable = @checkdnsrr($domain, 'MX') || @checkdnsrr($domain, 'A');
    if (!$reachable) {
        respond(false, 'error', 'Zu dieser E-Mail-Domain gibt es keinen Mailserver. Bitte prüfen Sie die Schreibweise.');
    }
}

/* 5) Rate-Limits gegen Missbrauch fremder Adressen. */
if (!Util::rateLimit('signup_ip', Util::ip(), 8, 3600)) {
    respond(false, 'error', 'Es wurden zu viele Anmeldungen von diesem Anschluss gesendet. Bitte versuchen Sie es später erneut.');
}
if (!Util::rateLimit('signup_mail', $email, 3, 86400)) {
    respond(true, 'created', 'Bitte prüfen Sie Ihr Postfach und bestätigen Sie die Anmeldung.');
}

/* 6) Anmeldung anlegen und Bestätigungsmail verschicken. */
$listIds = [];
foreach ((array) ($_POST['lists'] ?? []) as $value) {
    if (is_scalar($value)) {
        $listIds[] = (int) $value;
    }
}

try {
    $result = Subscribers::signup($email, [
        'first_name' => Util::post('first_name'),
        'last_name'  => Util::post('last_name'),
        'company'    => Util::post('company'),
        'salutation' => Util::post('salutation'),
    ], $listIds, Util::post('quelle') ?: 'website');

    respond(true, $result['status'], $result['message']);
} catch (InvalidArgumentException $e) {
    respond(false, 'error', $e->getMessage());
} catch (Throwable $e) {
    Log::error('signup', 'Anmeldung fehlgeschlagen für ' . $email . ': ' . $e->getMessage());
    respond(false, 'error', 'Die Bestätigungsmail konnte gerade nicht verschickt werden. '
        . 'Bitte versuchen Sie es in einigen Minuten erneut.');
}
