<?php
/**
 * AcumenMail – Kontaktformular-Handler (serverseitig, ohne Drittanbieter)
 * -----------------------------------------------------------------------
 * Nimmt die Anfrage vom Kontaktformular entgegen, prüft sie und verschickt
 * sie als E-Mail über den Mailserver Ihres Hosters. Es werden KEINE Daten
 * an Dritte (z. B. in die USA) übermittelt.
 *
 * >>> BITTE ANPASSEN: die beiden Werte in $CONFIG unten. <<<
 *
 * Hinweis zur Zustellbarkeit: Setzen Sie als Absender (from) eine Adresse
 * auf IHRER eigenen Domain, für die SPF/DKIM eingerichtet ist. Sonst landen
 * die Mails evtl. im Spam. Der/die Interessent/in wird als Reply-To gesetzt,
 * sodass Sie direkt antworten können.
 */

$CONFIG = [
    // Wohin sollen die Anfragen gehen?
    'recipient'    => 'info@newsletter-consulting.de',   // Empfänger der Anfragen
    // Absenderadresse auf der eigenen Domain (für SPF/DKIM), NICHT die des Besuchers
    'from_address' => 'info@newsletter-consulting.de',   // muss ein echtes Postfach bei IONOS sein
    'from_name'    => 'Newsletter-Consulting Website',
    'subject'      => 'Neue Potenzialanalyse-Anfrage über newsletter-consulting.de',
    // Mindestsekunden zwischen Seitenaufruf und Absenden (Spam-Schutz)
    'min_seconds'  => 3,

    // --- Automatische Bestätigungs-/Willkommensmail an den Interessenten ---
    'send_confirmation' => true,   // auf false setzen, um die Bestätigungsmail abzuschalten
    'confirm_subject'   => 'Vielen Dank für Ihre Anfrage – so geht es weiter',
    'brand_name'        => 'AcumenMail',
    'owner_name'        => 'Christoph Schnurrenberger',
    'contact_phone'     => '0175 2778902',
    'contact_email'     => 'info@newsletter-consulting.de',
    'website'           => 'www.newsletter-consulting.de',
    // Impressum-Kurzangaben für den E-Mail-Footer (geschäftliche Pflichtangaben)
    'imprint'           => 'Christoph Schnurrenberger · Birkenstr. 10 · 87734 Benningen · Deutschland',
];

header('Content-Type: application/json; charset=utf-8');

/* Nur POST zulassen */
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['errors' => [['message' => 'Methode nicht erlaubt.']]]);
    exit;
}

/* Kleine Hilfsfunktionen */
function post(string $key): string {
    return isset($_POST[$key]) ? trim((string) $_POST[$key]) : '';
}
// Schützt Header-Felder (From/Reply-To/Subject) vor Header-Injection
function clean_header(string $v): string {
    return trim(str_replace(["\r", "\n", "%0a", "%0d"], '', $v));
}
function fail(array $messages): void {
    http_response_code(422);
    echo json_encode(['errors' => array_map(fn($m) => ['message' => $m], $messages)]);
    exit;
}
// „Still“ mit Erfolg antworten (bei Bot-Verdacht), ohne etwas zu versenden
function silent_ok(): void {
    http_response_code(200);
    echo json_encode(['ok' => true]);
    exit;
}

/**
 * Sendet eine gestaltete Bestätigungs-/Willkommensmail (HTML + Text) an den
 * Interessenten. Reine Service-Mail (keine Werbung) – DSGVO/UWG-konform.
 * Best effort: ein Fehler hier beeinflusst die Formular-Antwort nicht.
 */
function send_confirmation(string $toEmail, string $name, array $CONFIG): void {
    $brand   = htmlspecialchars($CONFIG['brand_name'], ENT_QUOTES, 'UTF-8');
    $owner   = htmlspecialchars($CONFIG['owner_name'], ENT_QUOTES, 'UTF-8');
    $phone   = htmlspecialchars($CONFIG['contact_phone'], ENT_QUOTES, 'UTF-8');
    $mail    = htmlspecialchars($CONFIG['contact_email'], ENT_QUOTES, 'UTF-8');
    $web     = htmlspecialchars($CONFIG['website'], ENT_QUOTES, 'UTF-8');
    $imprint = htmlspecialchars($CONFIG['imprint'], ENT_QUOTES, 'UTF-8');
    $vorname = $name !== '' ? preg_split('/\s+/', trim($name))[0] : '';
    $anrede  = $vorname !== '' ? 'Hallo ' . htmlspecialchars($vorname, ENT_QUOTES, 'UTF-8') : 'Hallo';

    $navy = '#14243A';
    $red  = '#C8102E';

    // --- HTML-Teil (tabellenbasiert für gute Client-Kompatibilität) ---
    $html = '<!DOCTYPE html><html lang="de"><head><meta charset="utf-8">'
        . '<meta name="viewport" content="width=device-width,initial-scale=1"></head>'
        . '<body style="margin:0;padding:0;background-color:#F6F8FA;">'
        . '<span style="display:none!important;opacity:0;color:#F6F8FA;font-size:1px;line-height:1px;max-height:0;max-width:0;overflow:hidden;">Ihre Anfrage ist bei uns eingegangen – wir melden uns innerhalb von 24 Stunden (werktags).</span>'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F6F8FA;padding:24px 12px;">'
        . '<tr><td align="center">'
        . '<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:10px;overflow:hidden;border:1px solid #E0E6ED;">'
        // Header
        . '<tr><td style="background-color:' . $navy . ';padding:24px 32px;">'
        . '<table role="presentation" cellpadding="0" cellspacing="0"><tr>'
        . '<td style="background-color:#ffffff;color:' . $navy . ';width:36px;height:36px;border-radius:7px;font-family:Arial,Helvetica,sans-serif;font-weight:bold;font-size:18px;text-align:center;line-height:36px;">A</td>'
        . '<td style="padding-left:12px;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;font-size:18px;">' . $brand . '</td>'
        . '</tr></table></td></tr>'
        // Body
        . '<tr><td style="padding:32px;font-family:Arial,Helvetica,sans-serif;color:#4A5568;font-size:15px;line-height:1.6;">'
        . '<p style="margin:0 0 16px;font-size:20px;font-weight:bold;color:' . $navy . ';">Vielen Dank für Ihre Anfrage!</p>'
        . '<p style="margin:0 0 16px;">' . $anrede . ',</p>'
        . '<p style="margin:0 0 16px;">vielen Dank für Ihre Nachricht an ' . $brand . '. Ihre Anfrage ist bei uns eingegangen – ich melde mich in der Regel <strong style="color:' . $navy . ';">innerhalb von 24 Stunden</strong> (werktags) persönlich bei Ihnen.</p>'
        . '<p style="margin:24px 0 12px;font-weight:bold;color:' . $navy . ';">So geht es weiter:</p>'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0">'
        . confirmation_step('1', 'Prüfung', 'Ich sehe mir Ihre Anfrage und Ihre Ausgangslage in Ruhe an.', $navy, $red)
        . confirmation_step('2', 'Kennenlernen', 'In einem kurzen, unverbindlichen Gespräch klären wir Ziele und Potenziale.', $navy, $red)
        . confirmation_step('3', 'Klare Empfehlung', 'Sie erhalten eine konkrete Einschätzung der sinnvollen nächsten Schritte.', $navy, $red)
        . '</table>'
        . '<p style="margin:24px 0 4px;">Wenn es dringend ist, erreichen Sie mich direkt:</p>'
        . '<p style="margin:0 0 24px;">Telefon: <a href="tel:' . preg_replace('/[^0-9+]/', '', $CONFIG['contact_phone']) . '" style="color:' . $red . ';text-decoration:none;">' . $phone . '</a><br>'
        . 'E-Mail: <a href="mailto:' . $mail . '" style="color:' . $red . ';text-decoration:none;">' . $mail . '</a></p>'
        . '<p style="margin:0;">Herzliche Grüße<br><strong style="color:' . $navy . ';">' . $owner . '</strong><br>' . $brand . '</p>'
        . '</td></tr>'
        // Footer
        . '<tr><td style="background-color:#F6F8FA;padding:20px 32px;border-top:1px solid #E0E6ED;font-family:Arial,Helvetica,sans-serif;color:#8A95A5;font-size:12px;line-height:1.6;">'
        . 'Diese E-Mail bestätigt den Eingang Ihrer Anfrage über ' . $web . '. Sie haben diese Nachricht erhalten, weil Sie uns über das Kontaktformular geschrieben haben.<br><br>'
        . $imprint . '<br>' . $mail . ' · ' . $web
        . '</td></tr>'
        . '</table></td></tr></table></body></html>';

    // --- Text-Teil (Fallback) ---
    $text = "$anrede,\r\n\r\n"
        . "vielen Dank für Ihre Nachricht an {$CONFIG['brand_name']}. Ihre Anfrage ist bei uns eingegangen – "
        . "ich melde mich in der Regel innerhalb von 24 Stunden (werktags) persönlich bei Ihnen.\r\n\r\n"
        . "So geht es weiter:\r\n"
        . "1. Prüfung – Ich sehe mir Ihre Anfrage und Ausgangslage an.\r\n"
        . "2. Kennenlernen – Kurzes, unverbindliches Gespräch zu Zielen und Potenzialen.\r\n"
        . "3. Klare Empfehlung – Konkrete Einschätzung der nächsten Schritte.\r\n\r\n"
        . "Wenn es dringend ist, erreichen Sie mich direkt:\r\n"
        . "Telefon: {$CONFIG['contact_phone']}\r\n"
        . "E-Mail: {$CONFIG['contact_email']}\r\n\r\n"
        . "Herzliche Grüße\r\n{$CONFIG['owner_name']}\r\n{$CONFIG['brand_name']}\r\n\r\n"
        . "-- \r\n"
        . "Diese E-Mail bestätigt den Eingang Ihrer Anfrage über {$CONFIG['website']}.\r\n"
        . "{$CONFIG['imprint']}\r\n{$CONFIG['contact_email']} · {$CONFIG['website']}\r\n";

    $fromAddr = clean_header($CONFIG['from_address']);
    $fromName = clean_header($CONFIG['brand_name']);
    $subject  = clean_header($CONFIG['confirm_subject']);
    $boundary = '=_acm_' . md5(uniqid('', true));

    $headers   = [];
    $headers[] = 'From: ' . $fromName . ' <' . $fromAddr . '>';
    $headers[] = 'Reply-To: ' . $fromName . ' <' . $fromAddr . '>';
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';
    $headers[] = 'X-Mailer: AcumenMail-Form';
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

    $encSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

    @mail($toEmail, $encSubject, $mimeBody, implode("\r\n", $headers), '-f' . $fromAddr);
}

// Ein Schritt-Block für die Bestätigungsmail (Nummer + Titel + Text)
function confirmation_step(string $num, string $title, string $text, string $navy, string $red): string {
    return '<tr><td style="padding:0 0 14px;">'
        . '<table role="presentation" cellpadding="0" cellspacing="0" width="100%"><tr>'
        . '<td width="36" valign="top"><table role="presentation" cellpadding="0" cellspacing="0"><tr>'
        . '<td style="background-color:' . $red . ';color:#ffffff;width:28px;height:28px;border-radius:6px;font-family:Arial,Helvetica,sans-serif;font-weight:bold;font-size:13px;text-align:center;line-height:28px;">' . $num . '</td>'
        . '</tr></table></td>'
        . '<td valign="top" style="padding-left:12px;font-family:Arial,Helvetica,sans-serif;">'
        . '<span style="font-weight:bold;color:' . $navy . ';font-size:15px;">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</span><br>'
        . '<span style="color:#4A5568;font-size:14px;line-height:1.5;">' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</span>'
        . '</td></tr></table></td></tr>';
}

/* 1) Honeypot: verstecktes Feld – von echten Nutzern nie ausgefüllt */
if (post('_gotcha') !== '') {
    silent_ok();
}

/* 2) Zeit-Check: zu schnell abgeschickt = vermutlich Bot */
$formTime = (int) post('form_time'); // Millisekunden-Timestamp aus dem Browser
if ($formTime > 0) {
    $elapsed = (microtime(true) * 1000) - $formTime;
    if ($elapsed < ($CONFIG['min_seconds'] * 1000)) {
        silent_ok();
    }
}

/* 3) Felder einlesen */
$name    = post('name');
$email   = post('email');
$phone   = post('phone');
$company = post('company');
$message = post('message');

/* 4) Serverseitige Validierung */
$errors = [];

if ($name === '' || !preg_match('/^[\p{L}\s\-\.]{2,100}$/u', $name)) {
    $errors[] = 'Bitte geben Sie einen gültigen Namen an.';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
    $errors[] = 'Bitte geben Sie eine gültige E-Mail-Adresse an.';
}
if ($phone !== '' && !preg_match('/^[0-9+()\/\s\-]{4,40}$/', $phone)) {
    $errors[] = 'Bitte geben Sie eine gültige Telefonnummer an.';
}
if (mb_strlen($company) > 150) {
    $errors[] = 'Der Firmenname ist zu lang.';
}
if (mb_strlen($message) < 20 || mb_strlen($message) > 5000) {
    $errors[] = 'Die Nachricht muss zwischen 20 und 5000 Zeichen lang sein.';
}

/* 5) Captcha serverseitig prüfen (a + b = Antwort) */
$captchaA      = (int) post('captcha_a');
$captchaB      = (int) post('captcha_b');
$captchaAnswer = post('captcha');
if ($captchaAnswer === '' || (int) $captchaAnswer !== ($captchaA + $captchaB)) {
    $errors[] = 'Bitte lösen Sie die Sicherheitsfrage korrekt.';
}

if (!empty($errors)) {
    fail($errors);
}

/* 6) E-Mail zusammenbauen */
$lines = [
    'Neue Anfrage über das AcumenMail-Kontaktformular',
    '------------------------------------------------',
    'Name:        ' . $name,
    'E-Mail:      ' . $email,
    'Telefon:     ' . ($phone !== '' ? $phone : '—'),
    'Unternehmen: ' . ($company !== '' ? $company : '—'),
    '',
    'Nachricht:',
    $message,
    '',
    '------------------------------------------------',
    'Gesendet am ' . date('d.m.Y H:i') . ' Uhr',
    'IP (gekürzt): ' . preg_replace('/\.\d+$/', '.x', $_SERVER['REMOTE_ADDR'] ?? ''),
];
$body = implode("\r\n", $lines);

$fromAddr = clean_header($CONFIG['from_address']);
$fromName = clean_header($CONFIG['from_name']);
$replyTo  = clean_header($email);
$replyNm  = clean_header($name);
$subject  = clean_header($CONFIG['subject']);

$headers   = [];
$headers[] = 'From: ' . $fromName . ' <' . $fromAddr . '>';
$headers[] = 'Reply-To: ' . $replyNm . ' <' . $replyTo . '>';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'Content-Transfer-Encoding: 8bit';
$headers[] = 'X-Mailer: AcumenMail-Form';

// Betreff RFC-2047-kodieren (Umlaute korrekt)
$encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

/* 7) Versenden */
$sent = @mail(
    $CONFIG['recipient'],
    $encodedSubject,
    $body,
    implode("\r\n", $headers),
    '-f' . $fromAddr   // Envelope-Sender (verbessert Zustellbarkeit/SPF)
);

if ($sent) {
    // Bestätigungs-/Willkommensmail an den Interessenten (best effort, reine Service-Mail)
    if (!empty($CONFIG['send_confirmation'])) {
        send_confirmation($email, $name, $CONFIG);
    }
    http_response_code(200);
    echo json_encode(['ok' => true]);
} else {
    http_response_code(500);
    echo json_encode(['errors' => [[
        'message' => 'Die Nachricht konnte gerade nicht gesendet werden. Bitte versuchen Sie es später erneut oder schreiben Sie uns direkt per E-Mail.'
    ]]]);
}
