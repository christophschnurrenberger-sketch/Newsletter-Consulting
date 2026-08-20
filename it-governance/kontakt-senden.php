<?php
/**
 * Kontaktformular-Handler – serverseitig, ohne Drittanbieter.
 * ---------------------------------------------------------------------------
 * Nimmt die Anfrage vom Kontaktformular entgegen, prüft sie und verschickt sie
 * als E-Mail über den Mailserver des Hosters. Es werden keine Daten an Dritte
 * übermittelt, nichts wird in einer Datenbank gespeichert, es gibt kein
 * Tracking. Für eine Beratung, die anderen Governance beibringt, ist das keine
 * Kleinigkeit, sondern das Mindeste.
 *
 * >>> BITTE ANPASSEN: die Werte in $CONFIG unten. <<<
 *
 * Zur Zustellbarkeit: Als Absender (from_address) muss eine Adresse auf der
 * eigenen Domain stehen, für die SPF und DKIM eingerichtet sind. Sonst landen
 * die Benachrichtigungen im Spam. Die anfragende Person wird als Reply-To
 * gesetzt, sodass sich direkt antworten lässt.
 */

$CONFIG = [
    // Wohin sollen die Anfragen gehen?
    'recipient'    => 'kontakt@it-governance-mittelstand.de',
    // Absenderadresse auf der eigenen Domain (für SPF/DKIM), NICHT die des Absenders
    'from_address' => 'kontakt@it-governance-mittelstand.de',
    'from_name'    => 'Website IT-Governance',
    'subject'      => 'Neue Anfrage über die Website (IT-Governance)',
    // Mindestsekunden zwischen Seitenaufruf und Absenden (Spam-Schutz)
    'min_seconds'  => 4,

    // --- Automatische Eingangsbestätigung an die anfragende Person ---
    'send_confirmation' => true,
    'confirm_subject'   => 'Ihre Anfrage ist angekommen – so geht es weiter',
    'brand_name'        => 'Schnurrenberger IT-Governance',
    'owner_name'        => 'Christoph Schnurrenberger',
    'contact_phone'     => '0175 2778902',
    'contact_email'     => 'kontakt@it-governance-mittelstand.de',
    'website'           => 'www.it-governance-mittelstand.de',
    // Pflichtangaben für den E-Mail-Fuß
    'imprint'           => 'Christoph Schnurrenberger · Birkenstr. 10 · 87734 Benningen · Deutschland',
];

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

/* Nur POST zulassen */
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['errors' => [['message' => 'Methode nicht erlaubt.']]]);
    exit;
}

/* ---------------------------------------------------------- Hilfsfunktionen */

function post(string $key): string
{
    return isset($_POST[$key]) ? trim((string) $_POST[$key]) : '';
}

/** Schützt Kopfzeilen (From/Reply-To/Subject) vor Header-Injection. */
function clean_header(string $value): string
{
    return trim(str_replace(["\r", "\n", '%0a', '%0d'], '', $value));
}

function fail(array $messages): void
{
    http_response_code(422);
    echo json_encode(['errors' => array_map(fn($m) => ['message' => $m], $messages)]);
    exit;
}

/** Bei Bot-Verdacht still mit Erfolg antworten, ohne etwas zu versenden. */
function silent_ok(): void
{
    http_response_code(200);
    echo json_encode(['ok' => true]);
    exit;
}

/**
 * Prüft einen Auswahlwert gegen die erlaubte Liste. Alles Unbekannte wird
 * verworfen statt abgelehnt – ein manipuliertes Auswahlfeld soll die Anfrage
 * nicht scheitern lassen, aber auch nicht in die E-Mail gelangen.
 */
function pick(string $value, array $allowed): string
{
    return in_array($value, $allowed, true) ? $value : '';
}

/* ------------------------------------------------------------- Spam-Schutz */

/* 1) Honigtopf: verstecktes Feld, von echten Nutzern nie ausgefüllt */
if (post('_gotcha') !== '') {
    silent_ok();
}

/* 2) Zeitprüfung: zu schnell abgeschickt = vermutlich Bot */
$formTime = (int) post('form_time');   // Millisekunden aus dem Browser
if ($formTime > 0) {
    $elapsed = (microtime(true) * 1000) - $formTime;
    if ($elapsed < ($CONFIG['min_seconds'] * 1000)) {
        silent_ok();
    }
}

/* ---------------------------------------------------------- Felder einlesen */

$name        = post('name');
$email       = post('email');
$phone       = post('phone');
$company     = post('company');
$rolle       = post('rolle');
$mitarbeiter = post('mitarbeiter');
$anlass      = post('anlass');
$leistung    = post('leistung');
$message     = post('message');
$datenschutz = post('datenschutz');

/* ------------------------------------------------------------- Prüfungen */

$errors = [];

if ($name === '' || !preg_match('/^[\p{L}\s\-\.\']{2,100}$/u', $name)) {
    $errors[] = 'Bitte geben Sie einen gültigen Namen an.';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
    $errors[] = 'Bitte geben Sie eine gültige E-Mail-Adresse an.';
}
if ($phone !== '' && !preg_match('/^[0-9+()\/\s\-]{4,40}$/', $phone)) {
    $errors[] = 'Bitte geben Sie eine gültige Telefonnummer an.';
}
if ($company === '' || mb_strlen($company) > 150) {
    $errors[] = 'Bitte geben Sie Ihr Unternehmen an.';
}
if (mb_strlen($message) < 30 || mb_strlen($message) > 5000) {
    $errors[] = 'Die Nachricht muss zwischen 30 und 5000 Zeichen lang sein.';
}
if ($datenschutz !== 'ja') {
    $errors[] = 'Bitte bestätigen Sie die Kenntnisnahme der Datenschutzhinweise.';
}

/* Auswahlfelder: nur die angebotenen Werte übernehmen */
$rolle = pick($rolle, [
    'Geschäftsführung / Vorstand', 'IT-Leitung', 'Finanzen / CFO',
    'Informationssicherheit / Datenschutz', 'Fachbereichsleitung', 'Sonstiges',
]);
$mitarbeiter = pick($mitarbeiter, [
    'bis 100', '100 bis 500', '500 bis 1.500', '1.500 bis 5.000', 'mehr als 5.000',
]);
$anlass = pick($anlass, [
    'Audit oder Prüfung steht an', 'Feststellungen aus einer Prüfung',
    'NIS2 oder regulatorischer Druck', 'Kundenanforderung / Lieferantenaudit',
    'ISO 27001 geplant', 'Gewachsene Strukturen ordnen',
    'Mehrere Standorte harmonisieren', 'Wechsel in der IT-Leitung', 'Noch unklar',
]);
$leistung = pick($leistung, [
    'Quick Assessment', 'Gap-Analyse', 'Audit Readiness', 'IT-Prozess-Assessment',
    'IT Operating Model', 'Governance-Framework', 'Rollen & Verantwortlichkeiten',
    'Kontrollframework', 'Demand Management', 'Service Management', 'Laufende Betreuung',
]);

/* Rechenaufgabe serverseitig nachrechnen (a + b = Antwort) */
$captchaA      = (int) post('captcha_a');
$captchaB      = (int) post('captcha_b');
$captchaAnswer = post('captcha');
if ($captchaAnswer === '' || (int) $captchaAnswer !== ($captchaA + $captchaB)) {
    $errors[] = 'Bitte lösen Sie die Sicherheitsfrage korrekt.';
}

if ($errors) {
    fail($errors);
}

/* ------------------------------------------------------ Benachrichtigung */

$lines = [
    'Neue Anfrage über die Website',
    '--------------------------------------------------',
    'Name:         ' . $name,
    'Unternehmen:  ' . $company,
    'E-Mail:       ' . $email,
    'Telefon:      ' . ($phone !== '' ? $phone : '—'),
    'Funktion:     ' . ($rolle !== '' ? $rolle : '—'),
    'Mitarbeitende:' . ' ' . ($mitarbeiter !== '' ? $mitarbeiter : '—'),
    'Anlass:       ' . ($anlass !== '' ? $anlass : '—'),
    'Leistung:     ' . ($leistung !== '' ? $leistung : 'noch offen'),
    '',
    'Nachricht:',
    $message,
    '',
    '--------------------------------------------------',
    'Gesendet am ' . date('d.m.Y H:i') . ' Uhr',
    'IP (gekürzt): ' . preg_replace('/\.\d+$/', '.x', $_SERVER['REMOTE_ADDR'] ?? ''),
];
$body = implode("\r\n", $lines);

$fromAddr = clean_header($CONFIG['from_address']);
$fromName = clean_header($CONFIG['from_name']);
$replyTo  = clean_header($email);
$replyNm  = clean_header($name);
$subject  = clean_header($CONFIG['subject'] . ' – ' . $company);

$headers   = [];
$headers[] = 'From: ' . $fromName . ' <' . $fromAddr . '>';
$headers[] = 'Reply-To: ' . $replyNm . ' <' . $replyTo . '>';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'Content-Transfer-Encoding: 8bit';
$headers[] = 'X-Mailer: ITG-Form';

$encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

$sent = @mail(
    $CONFIG['recipient'],
    $encodedSubject,
    $body,
    implode("\r\n", $headers),
    '-f' . $fromAddr           // Envelope-Sender, verbessert die Zustellbarkeit
);

/* ------------------------------------------------- Eingangsbestätigung */

/**
 * Reine Servicemail: bestätigt den Eingang und beschreibt den weiteren Ablauf.
 * Keine Werbung, kein Newsletter, keine Anmeldung. Fehler hier beeinflussen die
 * Antwort an den Browser nicht.
 */
function send_confirmation(string $toEmail, string $name, array $CONFIG): void
{
    $brand   = htmlspecialchars($CONFIG['brand_name'], ENT_QUOTES, 'UTF-8');
    $owner   = htmlspecialchars($CONFIG['owner_name'], ENT_QUOTES, 'UTF-8');
    $phone   = htmlspecialchars($CONFIG['contact_phone'], ENT_QUOTES, 'UTF-8');
    $mail    = htmlspecialchars($CONFIG['contact_email'], ENT_QUOTES, 'UTF-8');
    $web     = htmlspecialchars($CONFIG['website'], ENT_QUOTES, 'UTF-8');
    $imprint = htmlspecialchars($CONFIG['imprint'], ENT_QUOTES, 'UTF-8');

    $vorname = $name !== '' ? preg_split('/\s+/', trim($name))[0] : '';
    $anrede  = $vorname !== '' ? 'Guten Tag ' . htmlspecialchars($vorname, ENT_QUOTES, 'UTF-8') : 'Guten Tag';

    $navy = '#0E2A47';
    $copp = '#B0682C';

    $schritte = [
        ['1', 'Ich lese Ihre Anfrage', 'Und schaue mir an, was aus der geschilderten Lage folgt.'],
        ['2', 'Terminvorschlag', 'In der Regel am selben oder am nächsten Werktag, per E-Mail.'],
        ['3', 'Gespräch, 30 Minuten', 'Telefon oder Video, ohne Vorbereitung Ihrerseits.'],
        ['4', 'Ehrliche Empfehlung', 'Was zuerst zu tun ist – auch dann, wenn dafür keine Beratung nötig ist.'],
    ];

    $schrittHtml = '';
    foreach ($schritte as [$num, $titel, $text]) {
        $schrittHtml .= '<tr><td style="padding:0 0 14px;">'
            . '<table role="presentation" cellpadding="0" cellspacing="0" width="100%"><tr>'
            . '<td width="36" valign="top"><table role="presentation" cellpadding="0" cellspacing="0"><tr>'
            . '<td style="background-color:' . $copp . ';color:#ffffff;width:28px;height:28px;border-radius:6px;'
            . 'font-family:Arial,Helvetica,sans-serif;font-weight:bold;font-size:13px;text-align:center;line-height:28px;">'
            . $num . '</td></tr></table></td>'
            . '<td valign="top" style="padding-left:12px;font-family:Arial,Helvetica,sans-serif;">'
            . '<span style="font-weight:bold;color:' . $navy . ';font-size:15px;">' . htmlspecialchars($titel, ENT_QUOTES, 'UTF-8') . '</span><br>'
            . '<span style="color:#3B4B5B;font-size:14px;line-height:1.5;">' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</span>'
            . '</td></tr></table></td></tr>';
    }

    $html = '<!DOCTYPE html><html lang="de"><head><meta charset="utf-8">'
        . '<meta name="viewport" content="width=device-width,initial-scale=1"></head>'
        . '<body style="margin:0;padding:0;background-color:#F5F7F9;">'
        . '<span style="display:none!important;opacity:0;color:#F5F7F9;font-size:1px;line-height:1px;max-height:0;max-width:0;overflow:hidden;">'
        . 'Ihre Anfrage ist eingegangen – Antwort in der Regel am selben oder nächsten Werktag.</span>'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F5F7F9;padding:24px 12px;">'
        . '<tr><td align="center">'
        . '<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:10px;overflow:hidden;border:1px solid #DDE4EA;">'
        . '<tr><td style="background-color:' . $navy . ';padding:24px 32px;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;font-size:17px;">'
        . $brand . '</td></tr>'
        . '<tr><td style="padding:32px;font-family:Arial,Helvetica,sans-serif;color:#3B4B5B;font-size:15px;line-height:1.6;">'
        . '<p style="margin:0 0 16px;font-size:20px;font-weight:bold;color:' . $navy . ';">Vielen Dank für Ihre Anfrage</p>'
        . '<p style="margin:0 0 16px;">' . $anrede . ',</p>'
        . '<p style="margin:0 0 16px;">Ihre Nachricht ist angekommen. Ich melde mich in der Regel '
        . '<strong style="color:' . $navy . ';">am selben oder am nächsten Werktag</strong> persönlich bei Ihnen.</p>'
        . '<p style="margin:24px 0 12px;font-weight:bold;color:' . $navy . ';">So geht es weiter:</p>'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0">' . $schrittHtml . '</table>'
        . '<p style="margin:24px 0 4px;">Wenn es eilt, erreichen Sie mich direkt:</p>'
        . '<p style="margin:0 0 24px;">Telefon: <a href="tel:' . preg_replace('/[^0-9+]/', '', $CONFIG['contact_phone'])
        . '" style="color:' . $copp . ';text-decoration:none;">' . $phone . '</a><br>'
        . 'E-Mail: <a href="mailto:' . $mail . '" style="color:' . $copp . ';text-decoration:none;">' . $mail . '</a></p>'
        . '<p style="margin:0;">Mit freundlichen Grüßen<br><strong style="color:' . $navy . ';">' . $owner . '</strong><br>' . $brand . '</p>'
        . '</td></tr>'
        . '<tr><td style="background-color:#F5F7F9;padding:20px 32px;border-top:1px solid #DDE4EA;'
        . 'font-family:Arial,Helvetica,sans-serif;color:#667A8D;font-size:12px;line-height:1.6;">'
        . 'Diese E-Mail bestätigt den Eingang Ihrer Anfrage über ' . $web . '. Sie erhalten sie, weil Sie das '
        . 'Kontaktformular ausgefüllt haben – es handelt sich nicht um Werbung und nicht um einen Newsletter.<br><br>'
        . $imprint . '<br>' . $mail . ' · ' . $web
        . '</td></tr></table></td></tr></table></body></html>';

    $text = "$anrede,\r\n\r\n"
        . "Ihre Nachricht an {$CONFIG['brand_name']} ist angekommen. Ich melde mich in der Regel "
        . "am selben oder am nächsten Werktag persönlich bei Ihnen.\r\n\r\n"
        . "So geht es weiter:\r\n"
        . "1. Ich lese Ihre Anfrage und schaue mir an, was daraus folgt.\r\n"
        . "2. Terminvorschlag per E-Mail.\r\n"
        . "3. Gespräch, 30 Minuten, Telefon oder Video.\r\n"
        . "4. Ehrliche Empfehlung, was zuerst zu tun ist.\r\n\r\n"
        . "Wenn es eilt: {$CONFIG['contact_phone']} oder {$CONFIG['contact_email']}\r\n\r\n"
        . "Mit freundlichen Grüßen\r\n{$CONFIG['owner_name']}\r\n{$CONFIG['brand_name']}\r\n\r\n"
        . "-- \r\n"
        . "Diese E-Mail bestätigt den Eingang Ihrer Anfrage über {$CONFIG['website']}.\r\n"
        . "{$CONFIG['imprint']}\r\n{$CONFIG['contact_email']} · {$CONFIG['website']}\r\n";

    $fromAddr = clean_header($CONFIG['from_address']);
    $fromName = clean_header($CONFIG['brand_name']);
    $subject  = clean_header($CONFIG['confirm_subject']);
    $boundary = '=_itg_' . md5(uniqid('', true));

    $headers   = [];
    $headers[] = 'From: ' . $fromName . ' <' . $fromAddr . '>';
    $headers[] = 'Reply-To: ' . $fromName . ' <' . $fromAddr . '>';
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';
    $headers[] = 'X-Mailer: ITG-Form';
    $headers[] = 'Auto-Submitted: auto-replied';

    $mimeBody =
        "--$boundary\r\n"
        . "Content-Type: text/plain; charset=UTF-8\r\n"
        . "Content-Transfer-Encoding: base64\r\n\r\n"
        . chunk_split(base64_encode($text)) . "\r\n"
        . "--$boundary\r\n"
        . "Content-Type: text/html; charset=UTF-8\r\n"
        . "Content-Transfer-Encoding: base64\r\n\r\n"
        . chunk_split(base64_encode($html)) . "\r\n"
        . "--$boundary--";

    @mail($toEmail, '=?UTF-8?B?' . base64_encode($subject) . '?=', $mimeBody, implode("\r\n", $headers), '-f' . $fromAddr);
}

/* ---------------------------------------------------------------- Antwort */

if ($sent) {
    if (!empty($CONFIG['send_confirmation'])) {
        send_confirmation($email, $name, $CONFIG);
    }
    http_response_code(200);
    echo json_encode(['ok' => true]);
} else {
    http_response_code(500);
    echo json_encode(['errors' => [[
        'message' => 'Die Nachricht konnte gerade nicht gesendet werden. Bitte versuchen Sie es '
                   . 'später erneut oder schreiben Sie direkt an ' . $CONFIG['contact_email'] . '.',
    ]]]);
}
