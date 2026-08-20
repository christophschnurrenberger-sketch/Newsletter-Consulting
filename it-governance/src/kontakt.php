<?php
$page = [
    'title'       => 'Kontakt: kostenloses Erstgespräch vereinbaren',
    'description' => 'Erstgespräch zur IT-Governance vereinbaren: 30 Minuten, kostenlos und unverbindlich. Sie schildern Ihre Ausgangslage, Sie bekommen eine ehrliche Einschätzung – auch dann, wenn sie lautet, dass Sie keine Beratung brauchen.',
    'section'     => '',
    'path'        => 'kontakt.php',
    'crumbs'      => [['Kontakt', null]],
    'cta'         => false,
    'hero'        => [
        'kicker' => 'Kontakt',
        'h1'     => 'Dreißig Minuten, <span class="accent">ehrliche Einschätzung</span>',
        'lead'   => 'Schildern Sie kurz, was bei Ihnen ansteht. Sie bekommen eine Einschätzung, was ich an Ihrer Stelle zuerst angehen würde – und wenn dafür keine Beratung nötig ist, sage ich Ihnen auch das.',
    ],
];
include __DIR__ . '/partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="contact-shell">

            <aside class="contact-panel">
                <div class="aside-card">
                    <h2 class="aside-title">So läuft es ab</h2>
                    <ol class="contact-steps">
                        <li>
                            <span class="contact-step-index">1</span>
                            <span class="contact-step-text"><strong>Formular absenden</strong>
                            <span>Ein paar Angaben zum Unternehmen und zum Anlass.</span></span>
                        </li>
                        <li>
                            <span class="contact-step-index">2</span>
                            <span class="contact-step-text"><strong>Terminvorschlag</strong>
                            <span>Antwort in der Regel am selben oder nächsten Werktag.</span></span>
                        </li>
                        <li>
                            <span class="contact-step-index">3</span>
                            <span class="contact-step-text"><strong>Gespräch, 30 Minuten</strong>
                            <span>Telefon oder Video, ohne Vorbereitung Ihrerseits.</span></span>
                        </li>
                        <li>
                            <span class="contact-step-index">4</span>
                            <span class="contact-step-text"><strong>Klare Empfehlung</strong>
                            <span>Was zuerst zu tun ist – mit oder ohne mich.</span></span>
                        </li>
                    </ol>
                    <p class="contact-panel-note">
                        Kein Verkaufsgespräch, keine Präsentation, keine Nachfassschleife. Wenn
                        Sie sich nicht melden, melde ich mich auch nicht wieder.
                    </p>
                </div>

                <div class="aside-card">
                    <h2 class="aside-title">Lieber direkt sprechen?</h2>
                    <address style="font-style:normal; font-size:.95rem; line-height:1.9;">
                        <strong><?= e($SITE['owner']) ?></strong><br>
                        <a href="tel:<?= e($SITE['phone_link']) ?>" style="font-weight:700;"><?= e($SITE['phone']) ?></a><br>
                        <a href="mailto:<?= e($SITE['email']) ?>"><?= e($SITE['email']) ?></a><br>
                        <span class="muted"><?= e($SITE['street']) ?>, <?= e($SITE['city']) ?></span>
                    </address>
                </div>

                <div class="aside-card is-dark">
                    <h2 class="aside-title">Vertraulich</h2>
                    <p style="font-size:.92rem;">
                        Ihre Angaben werden ausschließlich zur Bearbeitung der Anfrage genutzt,
                        nicht weitergegeben und nicht für Werbung verwendet. Eine
                        Vertraulichkeitsvereinbarung unterschreibe ich auf Wunsch vor dem ersten
                        inhaltlichen Termin.
                    </p>
                </div>
            </aside>

            <div class="contact-card">
                <h2 style="font-size:1.5rem; margin-bottom:1.4rem;">Anfrage senden</h2>

                <form id="contact-form" action="<?= e(url('kontakt-senden.php')) ?>" method="POST"
                      accept-charset="UTF-8" class="form-grid" novalidate>

                    <!-- Spam-Schutz: Honigtopf, Zeitmessung und Rechenaufgabe, alle serverseitig geprüft -->
                    <div class="sr-only-field" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text" id="website" name="_gotcha" tabindex="-1" autocomplete="off">
                    </div>
                    <input type="hidden" name="captcha_a" id="captcha_a">
                    <input type="hidden" name="captcha_b" id="captcha_b">
                    <input type="hidden" name="form_time" id="form_time">

                    <div class="form-field">
                        <label for="name">Ihr Name</label>
                        <input type="text" id="name" name="name" class="form-input" placeholder="Vor- und Nachname"
                               autocomplete="name" required data-validate>
                        <p class="form-error" data-error-for="name"></p>
                    </div>

                    <div class="form-field">
                        <label for="email">E-Mail</label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="name@unternehmen.de"
                               autocomplete="email" required data-validate>
                        <p class="form-error" data-error-for="email"></p>
                    </div>

                    <div class="form-field">
                        <label for="company">Unternehmen</label>
                        <input type="text" id="company" name="company" class="form-input"
                               placeholder="Muster GmbH &amp; Co. KG" autocomplete="organization"
                               required data-validate>
                        <p class="form-error" data-error-for="company"></p>
                    </div>

                    <div class="form-field">
                        <label for="phone">Telefon <span class="form-hint">(optional)</span></label>
                        <input type="tel" id="phone" name="phone" class="form-input" placeholder="0170 1234567"
                               autocomplete="tel" data-validate>
                        <p class="form-error" data-error-for="phone"></p>
                    </div>

                    <div class="form-field">
                        <label for="rolle">Ihre Funktion</label>
                        <select id="rolle" name="rolle" class="form-select">
                            <option value="">Bitte wählen</option>
                            <option>Geschäftsführung / Vorstand</option>
                            <option>IT-Leitung</option>
                            <option>Finanzen / CFO</option>
                            <option>Informationssicherheit / Datenschutz</option>
                            <option>Fachbereichsleitung</option>
                            <option>Sonstiges</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="mitarbeiter">Mitarbeitende</label>
                        <select id="mitarbeiter" name="mitarbeiter" class="form-select">
                            <option value="">Bitte wählen</option>
                            <option>bis 100</option>
                            <option>100 bis 500</option>
                            <option>500 bis 1.500</option>
                            <option>1.500 bis 5.000</option>
                            <option>mehr als 5.000</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="anlass">Was ist der Anlass?</label>
                        <select id="anlass" name="anlass" class="form-select">
                            <option value="">Bitte wählen</option>
                            <option>Audit oder Prüfung steht an</option>
                            <option>Feststellungen aus einer Prüfung</option>
                            <option>NIS2 oder regulatorischer Druck</option>
                            <option>Kundenanforderung / Lieferantenaudit</option>
                            <option>ISO 27001 geplant</option>
                            <option>Gewachsene Strukturen ordnen</option>
                            <option>Mehrere Standorte harmonisieren</option>
                            <option>Wechsel in der IT-Leitung</option>
                            <option>Noch unklar</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="leistung">Interessante Leistung <span class="form-hint">(optional)</span></label>
                        <select id="leistung" name="leistung" class="form-select">
                            <option value="">Weiß ich noch nicht</option>
                            <option>Quick Assessment</option>
                            <option>Gap-Analyse</option>
                            <option>Audit Readiness</option>
                            <option>IT-Prozess-Assessment</option>
                            <option>IT Operating Model</option>
                            <option>Governance-Framework</option>
                            <option>Rollen &amp; Verantwortlichkeiten</option>
                            <option>Kontrollframework</option>
                            <option>Demand Management</option>
                            <option>Service Management</option>
                            <option>Laufende Betreuung</option>
                        </select>
                    </div>

                    <div class="form-field form-col-2">
                        <label for="message">Worum geht es?</label>
                        <textarea id="message" name="message" rows="6" class="form-textarea" required minlength="30"
                                  placeholder="Zum Beispiel: Unser Wirtschaftsprüfer hat im letzten Bericht Feststellungen zu Berechtigungen und Änderungsverfahren gemacht. Wir haben keine dokumentierten IT-Prozesse und wissen nicht, wo wir anfangen sollen."
                                  data-validate></textarea>
                        <p class="form-error" data-error-for="message"></p>
                    </div>

                    <div class="form-field form-col-2">
                        <label for="captcha">Sicherheitsfrage: <span id="captcha-question"></span></label>
                        <input type="text" id="captcha" name="captcha" class="form-input" inputmode="numeric"
                               autocomplete="off" placeholder="Ergebnis eintragen" required data-validate>
                        <p class="form-error" data-error-for="captcha"></p>
                    </div>

                    <div class="form-check form-col-2">
                        <input type="checkbox" id="datenschutz" name="datenschutz" value="ja" required>
                        <label for="datenschutz">
                            Ich habe die <a href="<?= e(url('datenschutz.php')) ?>">Datenschutzhinweise</a>
                            zur Kenntnis genommen. Meine Angaben werden zur Bearbeitung meiner
                            Anfrage verarbeitet.
                        </label>
                    </div>

                    <p id="form-status" class="form-status form-col-2" role="alert" aria-live="polite"></p>

                    <div class="form-actions form-col-2">
                        <button type="submit" id="contact-submit-button" class="btn-primary-custom">
                            Erstgespräch anfragen
                        </button>
                        <span class="form-hint">Antwort in der Regel am selben oder nächsten Werktag.</span>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

<div id="success-modal" class="modal-backdrop hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="modal-card">
        <button id="close-modal" class="modal-close" type="button" aria-label="Schließen">
            <i data-icon="x" class="lucide lucide-lg"></i>
        </button>
        <i data-icon="check-circle" class="lucide lucide-2xl"></i>
        <h3 id="modal-title">Vielen Dank!</h3>
        <p>
            Ihre Anfrage ist angekommen. Ich melde mich in der Regel am selben oder am nächsten
            Werktag mit einem Terminvorschlag – und mit einer ersten Einschätzung zu dem, was
            Sie geschildert haben.
        </p>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
