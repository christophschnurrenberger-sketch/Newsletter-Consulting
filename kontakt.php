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
    'recipient'    => 'anfragen@ihre-domain.de',      // <-- IHRE Empfänger-Adresse
    // Absenderadresse auf IHRER Domain (für SPF/DKIM), NICHT die des Besuchers
    'from_address' => 'website@ihre-domain.de',       // <-- Adresse auf Ihrer Domain
    'from_name'    => 'AcumenMail Website',
    'subject'      => 'Neue Potenzialanalyse-Anfrage über AcumenMail',
    // Mindestsekunden zwischen Seitenaufruf und Absenden (Spam-Schutz)
    'min_seconds'  => 3,
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
    http_response_code(200);
    echo json_encode(['ok' => true]);
} else {
    http_response_code(500);
    echo json_encode(['errors' => [[
        'message' => 'Die Nachricht konnte gerade nicht gesendet werden. Bitte versuchen Sie es später erneut oder schreiben Sie uns direkt per E-Mail.'
    ]]]);
}
