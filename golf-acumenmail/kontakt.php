<?php
$page = [
    'title'       => 'Kontakt & Demo anfragen',
    'description' => 'Kostenlose Club-Analyse anfragen oder eine Demo des Newsletter-Systems vereinbaren. Antwort in der Regel innerhalb von 24 Stunden, werktags.',
    'section'     => '',
    'path'        => 'kontakt.php',
    'crumbs'      => [['Kontakt', null]],
    'cta'         => false,
    'hero'        => [
        'kicker' => 'Kontakt',
        'h1'     => 'Kostenlose Club-Analyse <span class="accent">anfragen</span>',
        'lead'   => 'Erzählen Sie kurz, wie die Kommunikation in Ihrem Club heute läuft. Sie bekommen eine ehrliche Einschätzung – auch dann, wenn die lautet, dass sich eine Zusammenarbeit gerade nicht lohnt.',
    ],
];
include __DIR__ . '/partials/header.php';
?>

<section class="section">
    <div class="container">
        <div class="contact-shell">

            <aside class="contact-panel">
                <h2 class="contact-panel-title" style="font-size:1.5rem;">So läuft es ab</h2>

                <ol class="contact-steps">
                    <li>
                        <span class="contact-step-index">1</span>
                        <span class="contact-step-text"><strong>Formular absenden</strong>
                        <span>Ein paar Angaben zum Club und zur heutigen Situation.</span></span>
                    </li>
                    <li>
                        <span class="contact-step-index">2</span>
                        <span class="contact-step-text"><strong>Gespräch, 30 Minuten</strong>
                        <span>Telefon oder Video – mit Vorstand, Sekretariat oder beiden.</span></span>
                    </li>
                    <li>
                        <span class="contact-step-index">3</span>
                        <span class="contact-step-text"><strong>Klare Empfehlung</strong>
                        <span>Was zuerst zu tun ist, was es kostet, was es bringen kann.</span></span>
                    </li>
                </ol>

                <p class="contact-panel-note">Antwort in der Regel innerhalb von 24 Stunden,
                    werktags. Ihre Angaben werden vertraulich behandelt und nicht weitergegeben.</p>

                <div class="aside-card" style="margin-top:2rem;">
                    <h2 class="aside-title">Lieber direkt sprechen?</h2>
                    <address style="font-style:normal; font-size:0.95rem; line-height:1.9;">
                        <a href="tel:<?= e($SITE['phone_link']) ?>" style="color:var(--green); font-weight:700;">
                            <?= e($SITE['phone']) ?>
                        </a><br>
                        <a href="mailto:<?= e($SITE['email']) ?>" style="color:var(--green);">
                            <?= e($SITE['email']) ?>
                        </a>
                    </address>
                </div>
            </aside>

            <div class="contact-card">
                <form id="contact-form" action="<?= e(url('kontakt-senden.php')) ?>" method="POST"
                      accept-charset="UTF-8" class="form-grid" novalidate>

                    <!-- Spam-Schutz: Honeypot, Zeitmessung und Rechenaufgabe, alle serverseitig geprüft -->
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
                        <input type="email" id="email" name="email" class="form-input" placeholder="name@golfclub.de"
                               autocomplete="email" required data-validate>
                        <p class="form-error" data-error-for="email"></p>
                    </div>

                    <div class="form-field">
                        <label for="club">Club oder Golfanlage</label>
                        <input type="text" id="club" name="company" class="form-input"
                               placeholder="Golfclub Musterhausen e.V." autocomplete="organization"
                               required data-validate>
                        <p class="form-error" data-error-for="club"></p>
                    </div>

                    <div class="form-field">
                        <label for="phone">Telefon <span class="form-hint">(optional)</span></label>
                        <input type="tel" id="phone" name="phone" class="form-input" placeholder="0170 1234567"
                               autocomplete="tel" data-validate>
                        <p class="form-error" data-error-for="phone"></p>
                    </div>

                    <div class="form-field">
                        <label for="rolle">Ihre Funktion im Club</label>
                        <select id="rolle" name="rolle" class="form-select">
                            <option value="">Bitte wählen</option>
                            <option>Vorstand / Präsidium</option>
                            <option>Geschäftsführung / Clubmanagement</option>
                            <option>Sekretariat</option>
                            <option>Marketing / Kommunikation</option>
                            <option>Golfschule / Pro</option>
                            <option>Sonstiges</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="mitglieder">Mitglieder im Club</label>
                        <select id="mitglieder" name="mitglieder" class="form-select">
                            <option value="">Bitte wählen</option>
                            <option>bis 300</option>
                            <option>300 bis 700</option>
                            <option>700 bis 1200</option>
                            <option>mehr als 1200</option>
                        </select>
                    </div>

                    <div class="form-field form-col-2">
                        <label for="message">Wie läuft die Kommunikation heute?</label>
                        <textarea id="message" name="message" rows="6" class="form-textarea" required minlength="20"
                                  placeholder="Zum Beispiel: Wir verschicken zwei-, dreimal im Jahr einen Rundbrief über Outlook. Turnieranmeldungen laufen über Aushang und Telefon."
                                  data-validate></textarea>
                        <p class="form-error" data-error-for="message"></p>
                    </div>

                    <div class="form-field form-col-2">
                        <label for="captcha">Sicherheitsfrage: <span id="captcha-question"></span></label>
                        <input type="text" id="captcha" name="captcha" class="form-input" inputmode="numeric"
                               autocomplete="off" placeholder="Ergebnis eintragen" required data-validate>
                        <p class="form-error" data-error-for="captcha"></p>
                    </div>

                    <p class="form-hint form-col-2">
                        Mit dem Absenden verarbeiten wir Ihre Angaben ausschließlich zur Bearbeitung
                        Ihrer Anfrage. Der Versand läuft über unseren eigenen Server, es werden keine
                        Daten an Dritte weitergegeben. Näheres in der
                        <a href="<?= e(url('datenschutz.php')) ?>">Datenschutzerklärung</a>.
                    </p>

                    <p id="form-status" class="form-status form-col-2" role="alert" aria-live="polite"></p>

                    <div class="form-actions form-col-2">
                        <button type="submit" id="contact-submit-button" class="btn-primary-custom">
                            Club-Analyse anfragen
                        </button>
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
        <p>Ihre Anfrage ist angekommen. Ich melde mich in der Regel innerhalb von 24 Stunden
            (werktags) bei Ihnen – mit einer ersten Einschätzung zu Ihrem Club.</p>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
