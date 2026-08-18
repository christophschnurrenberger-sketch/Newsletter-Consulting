/**
 * Grundfunktionen der Startseite: Icons, Navigation, FAQ, Kontaktformular.
 *
 * Alles läuft ohne Bibliothek und ohne externe Aufrufe. Die Icons stehen als
 * SVG-Pfade in dieser Datei, damit kein CDN nötig ist und die Seite auch dann
 * vollständig aussieht, wenn ein Netz gerade zickt.
 */
(function () {
    'use strict';

    /* ------------------------------------------------------------------ *
     * Icons
     * ------------------------------------------------------------------ */

    var ICONS = {
        'align-left': '<line x1="21" x2="3" y1="6" y2="6"></line><line x1="15" x2="3" y1="12" y2="12"></line><line x1="17" x2="3" y1="18" y2="18"></line>',
        'arrow-right': '<path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path>',
        'bar-chart': '<path d="M3 3v18h18"></path><path d="M18 17V9"></path><path d="M13 17V5"></path><path d="M8 17v-3"></path>',
        'button': '<rect width="18" height="10" x="3" y="7" rx="2"></rect><path d="M8 12h8"></path>',
        'calendar-check': '<path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path><path d="m9 16 2 2 4-4"></path>',
        'calendar': '<path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path>',
        check: '<path d="M20 6 9 17l-5-5"></path>',
        'check-circle': '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><path d="m9 11 3 3L22 4"></path>',
        'chevron-down': '<path d="m6 9 6 6 6-6"></path>',
        clock: '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
        columns: '<rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M12 3v18"></path>',
        database: '<ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"></path><path d="M3 12c0 1.66 4.03 3 9 3s9-1.34 9-3"></path>',
        euro: '<path d="M4 10h12"></path><path d="M4 14h9"></path><path d="M19 6a7.7 7.7 0 0 0-5.2-2A7.9 7.9 0 0 0 6 12c0 4.4 3.5 8 7.8 8 2 0 3.8-.8 5.2-2"></path>',
        eye: '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle>',
        flag: '<path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" x2="4" y1="22" y2="15"></line>',
        'git-branch': '<line x1="6" x2="6" y1="3" y2="15"></line><circle cx="18" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><path d="M18 9a9 9 0 0 1-9 9"></path>',
        'help-circle': '<circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><path d="M12 17h.01"></path>',
        image: '<rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect><circle cx="9" cy="9" r="2"></circle><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>',
        layers: '<path d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"></path><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"></path><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"></path>',
        'line-chart': '<path d="M3 3v18h18"></path><path d="m19 9-5 5-4-4-3 3"></path>',
        lock: '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>',
        mail: '<rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>',
        'mouse-pointer': '<path d="M4.04 4.69a.5.5 0 0 1 .65-.65l16 6.5a.5.5 0 0 1-.06.95l-6.13 1.58a2 2 0 0 0-1.43 1.43l-1.58 6.13a.5.5 0 0 1-.95.06z"></path>',
        menu: '<line x1="4" x2="20" y1="12" y2="12"></line><line x1="4" x2="20" y1="6" y2="6"></line><line x1="4" x2="20" y1="18" y2="18"></line>',
        phone: '<path d="M13.83 19.17a15.9 15.9 0 0 1-6.9-6.9l1.85-1.85a2 2 0 0 0 .5-2L8.4 4.8A2 2 0 0 0 6.55 3.6H4.2A2 2 0 0 0 2.2 5.75 17.8 17.8 0 0 0 18.25 21.8a2 2 0 0 0 2.15-2v-2.35a2 2 0 0 0-1.2-1.85l-3.62-1.55a2 2 0 0 0-2 .5z"></path>',
        pause: '<rect x="14" y="4" width="4" height="16" rx="1"></rect><rect x="6" y="4" width="4" height="16" rx="1"></rect>',
        pen: '<path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>',
        play: '<polygon points="6 3 20 12 6 21 6 3"></polygon>',
        repeat: '<path d="m17 2 4 4-4 4"></path><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><path d="m7 22-4-4 4-4"></path><path d="M21 13v2a4 4 0 0 1-4 4H3"></path>',
        'rotate-ccw': '<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path>',
        search: '<circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path>',
        send: '<path d="m22 2-7 20-4-9-9-4Z"></path><path d="M22 2 11 13"></path>',
        server: '<rect width="20" height="8" x="2" y="2" rx="2"></rect><rect width="20" height="8" x="2" y="14" rx="2"></rect><line x1="6" x2="6.01" y1="6" y2="6"></line><line x1="6" x2="6.01" y1="18" y2="18"></line>',
        'shield-check': '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path><path d="m9 12 2 2 4-4"></path>',
        smartphone: '<rect width="14" height="20" x="5" y="2" rx="2"></rect><path d="M12 18h.01"></path>',
        snowflake: '<line x1="2" x2="22" y1="12" y2="12"></line><line x1="12" x2="12" y1="2" y2="22"></line><path d="m20 16-4-4 4-4"></path><path d="m4 8 4 4-4 4"></path><path d="m16 4-4 4-4-4"></path><path d="m8 20 4-4 4 4"></path>',
        sparkles: '<path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3Z"></path>',
        target: '<circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle>',
        trophy: '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>',
        type: '<polyline points="4 7 4 4 20 4 20 7"></polyline><line x1="9" x2="15" y1="20" y2="20"></line><line x1="12" x2="12" y1="4" y2="20"></line>',
        'user-check': '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><polyline points="16 11 18 13 22 9"></polyline>',
        users: '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
        x: '<path d="M18 6 6 18"></path><path d="m6 6 12 12"></path>',
        zap: '<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>'
    };

    function renderIcons(root) {
        var nodes = (root || document).querySelectorAll('[data-icon]');
        for (var i = 0; i < nodes.length; i++) {
            var node = nodes[i];
            var name = node.getAttribute('data-icon');
            var paths = ICONS[name];
            if (!paths) { continue; }

            var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('viewBox', '0 0 24 24');
            svg.setAttribute('fill', 'none');
            svg.setAttribute('stroke', 'currentColor');
            svg.setAttribute('stroke-width', '2');
            svg.setAttribute('stroke-linecap', 'round');
            svg.setAttribute('stroke-linejoin', 'round');
            svg.setAttribute('aria-hidden', 'true');
            svg.setAttribute('focusable', 'false');
            svg.setAttribute('class', node.getAttribute('class') || 'lucide');
            svg.innerHTML = paths;
            node.replaceWith(svg);
        }
    }
    renderIcons();
    /* ------------------------------------------------------------------ *
     * Kopfzeile, Hauptmenü und Mega-Menü
     *
     * Ein Menüpunkt mit Unterseiten ist ein <button aria-expanded>, kein Link –
     * so lässt er sich mit Tastatur und Screenreader bedienen. Auf breiten
     * Bildschirmen öffnet zusätzlich das Überfahren mit der Maus, auf schmalen
     * klappt derselbe Knopf die Liste als Akkordeon auf.
     * ------------------------------------------------------------------ */

    var header = document.getElementById('header');
    var menuButton = document.getElementById('mobile-menu-button');
    var backdrop = document.querySelector('.mega-backdrop');
    var megaItems = document.querySelectorAll('.nav-item.has-mega');
    var hoverTimer = null;

    function isDesktopNav() {
        return window.matchMedia('(min-width: 981px)').matches;
    }

    function setMega(item, open) {
        var mega = item.querySelector('.mega');
        var button = item.querySelector('.nav-toggle');
        if (!mega || !button) { return; }

        button.setAttribute('aria-expanded', String(open));

        if (open) {
            mega.hidden = false;
            /* Erst im nächsten Bild die Klasse setzen, sonst gibt es keinen
               Übergang – der Wechsel von display:none springt sonst hart. */
            window.requestAnimationFrame(function () { item.classList.add('is-open'); });
        } else {
            item.classList.remove('is-open');
            if (isDesktopNav()) {
                window.setTimeout(function () {
                    if (!item.classList.contains('is-open')) { mega.hidden = true; }
                }, 200);
            } else {
                mega.hidden = true;
            }
        }
    }

    function closeAllMega(except) {
        for (var i = 0; i < megaItems.length; i++) {
            if (megaItems[i] !== except && megaItems[i].classList.contains('is-open')) {
                setMega(megaItems[i], false);
            }
        }
        if (backdrop) {
            backdrop.hidden = !(except && isDesktopNav());
        }
    }

    for (var mi = 0; mi < megaItems.length; mi++) {
        (function (item) {
            var button = item.querySelector('.nav-toggle');
            if (!button) { return; }

            button.addEventListener('click', function () {
                var open = item.classList.contains('is-open');
                if (open) {
                    setMega(item, false);
                    if (backdrop) { backdrop.hidden = true; }
                } else {
                    closeAllMega(item);
                    setMega(item, true);
                }
            });

            /* Maus: öffnen beim Überfahren, mit kurzer Nachlaufzeit beim
               Verlassen – sonst klappt das Menü schon zu, wenn der Zeiger
               einmal kurz die Lücke streift. */
            item.addEventListener('mouseenter', function () {
                if (!isDesktopNav()) { return; }
                window.clearTimeout(hoverTimer);
                closeAllMega(item);
                setMega(item, true);
            });
            item.addEventListener('mouseleave', function () {
                if (!isDesktopNav()) { return; }
                hoverTimer = window.setTimeout(function () {
                    setMega(item, false);
                    if (backdrop) { backdrop.hidden = true; }
                }, 180);
            });

            /* Tastatur: verlässt der Fokus den Menüpunkt, schließt er. */
            item.addEventListener('focusout', function (event) {
                if (!isDesktopNav()) { return; }
                if (item.contains(event.relatedTarget)) { return; }
                setMega(item, false);
                if (backdrop) { backdrop.hidden = true; }
            });
        }(megaItems[mi]));
    }

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') { return; }
        closeAllMega(null);
        if (document.body.classList.contains('nav-open')) { closeMenu(); }
    });

    document.addEventListener('click', function (event) {
        if (header && header.contains(event.target)) { return; }
        closeAllMega(null);
    });

    if (backdrop) {
        backdrop.addEventListener('click', function () { closeAllMega(null); });
    }

    function closeMenu() {
        document.body.classList.remove('nav-open');
        if (menuButton) {
            menuButton.setAttribute('aria-expanded', 'false');
            menuButton.setAttribute('aria-label', 'Menü öffnen');
        }
        closeAllMega(null);
    }

    if (menuButton) {
        menuButton.addEventListener('click', function () {
            var open = document.body.classList.toggle('nav-open');
            menuButton.setAttribute('aria-expanded', String(open));
            menuButton.setAttribute('aria-label', open ? 'Menü schließen' : 'Menü öffnen');
            if (!open) { closeAllMega(null); }
        });
    }

    /* Wechselt die Breite über den Umbruchpunkt, passt der Zustand nicht mehr. */
    if (window.matchMedia) {
        window.matchMedia('(min-width: 981px)').addEventListener('change', function () {
            closeMenu();
        });
    }

    window.addEventListener('scroll', function () {
        if (!header) { return; }
        var current = window.pageYOffset || document.documentElement.scrollTop;
        header.classList.toggle('scrolled', current > 10);
    }, { passive: true });

    /* Anker auf derselben Seite: Menü schließen und weich scrollen. */
    var anchors = document.querySelectorAll('a[href^="#"]');
    for (var a = 0; a < anchors.length; a++) {
        anchors[a].addEventListener('click', function (event) {
            var id = this.getAttribute('href');
            if (!id || id === '#') { return; }
            var target = document.querySelector(id);
            if (!target) { return; }

            event.preventDefault();
            closeMenu();

            var offset = header ? header.offsetHeight : 0;
            var top = target.getBoundingClientRect().top + window.pageYOffset - offset - 12;
            window.scrollTo({ top: top, behavior: 'smooth' });
        });
    }


    /* ------------------------------------------------------------------ *
     * Einblenden beim Scrollen
     * ------------------------------------------------------------------ */

    var reveal = document.querySelectorAll('.animate-on-scroll');
    if ('IntersectionObserver' in window) {
        var revealObserver = new IntersectionObserver(function (entries, obs) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) { return; }
                entry.target.classList.add('is-visible');
                obs.unobserve(entry.target);
            });
        }, { threshold: 0.12 });
        for (var r = 0; r < reveal.length; r++) { revealObserver.observe(reveal[r]); }
    } else {
        for (var r2 = 0; r2 < reveal.length; r2++) { reveal[r2].classList.add('is-visible'); }
    }

    /* ------------------------------------------------------------------ *
     * FAQ
     * ------------------------------------------------------------------ */

    var questions = document.querySelectorAll('.faq-question');
    for (var q = 0; q < questions.length; q++) {
        questions[q].addEventListener('click', function () {
            var answer = this.nextElementSibling;
            var isOpen = answer.classList.contains('open');

            var open = document.querySelectorAll('.faq-answer.open');
            for (var o = 0; o < open.length; o++) {
                if (open[o] === answer) { continue; }
                open[o].classList.remove('open');
                open[o].setAttribute('aria-hidden', 'true');
                open[o].previousElementSibling.classList.remove('active');
                open[o].previousElementSibling.setAttribute('aria-expanded', 'false');
            }

            answer.classList.toggle('open', !isOpen);
            answer.setAttribute('aria-hidden', String(isOpen));
            this.classList.toggle('active', !isOpen);
            this.setAttribute('aria-expanded', String(!isOpen));
        });
    }

    /* ------------------------------------------------------------------ *
     * Kontaktformular
     * ------------------------------------------------------------------ */

    var form = document.getElementById('contact-form');
    if (form) {
        var modal = document.getElementById('success-modal');
        var closeModal = document.getElementById('close-modal');
        var status = document.getElementById('form-status');
        var submit = document.getElementById('contact-submit-button');
        var captchaQuestion = document.getElementById('captcha-question');
        var captchaInput = document.getElementById('captcha');
        var captchaAnswer = null;

        var RULES = {
            name: { required: true, pattern: /^[\wäöüÄÖÜß .'-]{2,}$/ },
            email: { required: true, pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/ },
            phone: { required: false, pattern: /^[0-9+()\/ -]+$/ },
            company: { required: true },   // Feld „Club oder Golfanlage“
            message: { required: true, minLength: 20 },
            captcha: { required: true, captcha: true }
        };

        /* Rechenaufgabe gegen Formular-Roboter. Die Zahlen gehen als
           verstecktes Feld mit, damit kontakt.php serverseitig nachrechnen
           kann – die Prüfung im Browser allein wäre wertlos. */
        function newCaptcha() {
            var first = Math.floor(Math.random() * 8) + 2;
            var second = Math.floor(Math.random() * 8) + 2;
            captchaAnswer = first + second;

            if (captchaQuestion) { captchaQuestion.textContent = first + ' + ' + second; }
            var fieldA = document.getElementById('captcha_a');
            var fieldB = document.getElementById('captcha_b');
            if (fieldA) { fieldA.value = first; }
            if (fieldB) { fieldB.value = second; }
            if (captchaInput) { captchaInput.value = ''; }
        }

        function validate(field) {
            var rules = RULES[field.name];
            if (!rules) { return true; }

            var value = field.value.trim();
            var error = document.querySelector('[data-error-for="' + field.id + '"]');
            var message = '';

            if (value === '') {
                if (rules.required) { message = 'Bitte füllen Sie dieses Feld aus.'; }
            } else if (rules.captcha && Number(value) !== captchaAnswer) {
                message = 'Das Ergebnis stimmt noch nicht.';
            } else if (rules.pattern && !rules.pattern.test(value)) {
                message = 'Bitte prüfen Sie diese Eingabe.';
            } else if (rules.minLength && value.length < rules.minLength) {
                message = 'Bitte schreiben Sie mindestens ' + rules.minLength + ' Zeichen.';
            }

            if (error) { error.textContent = message; error.classList.toggle('is-visible', !!message); }
            field.classList.toggle('has-error', !!message);
            return !message;
        }

        function showStatus(message) {
            if (!status) { window.alert(message); return; }
            status.textContent = message;
            status.classList.add('is-visible');
        }

        newCaptcha();

        var timeField = document.getElementById('form_time');
        if (timeField) { timeField.value = Date.now(); }

        var fields = form.querySelectorAll('[data-validate]');
        for (var f = 0; f < fields.length; f++) {
            fields[f].addEventListener('blur', function () { validate(this); });
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            if (status) { status.classList.remove('is-visible'); }

            var valid = true;
            var list = form.querySelectorAll('[data-validate]');
            for (var i = 0; i < list.length; i++) {
                if (!validate(list[i])) { valid = false; }
            }
            if (!valid) { return; }

            var original = submit ? submit.textContent : '';
            if (submit) { submit.disabled = true; submit.textContent = 'Wird gesendet …'; }

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'Accept': 'application/json' }
            })
                .then(function (response) {
                    if (response.ok) {
                        if (modal) { modal.classList.remove('hidden'); }
                        form.reset();
                        newCaptcha();
                        return;
                    }
                    return response.json()
                        .then(function (data) {
                            var text = data && data.errors && data.errors.length
                                ? data.errors.map(function (e) { return e.message; }).join(' ')
                                : 'Die Nachricht konnte gerade nicht gesendet werden.';
                            showStatus(text);
                        })
                        .catch(function () {
                            showStatus('Die Nachricht konnte gerade nicht gesendet werden. Bitte versuchen Sie es erneut oder schreiben Sie direkt per E-Mail.');
                        });
                })
                .catch(function () {
                    showStatus('Keine Verbindung zum Server. Bitte prüfen Sie Ihre Internetverbindung und versuchen Sie es erneut.');
                })
                .then(function () {
                    if (submit) { submit.disabled = false; submit.textContent = original; }
                });
        });

        if (closeModal && modal) {
            closeModal.addEventListener('click', function () { modal.classList.add('hidden'); });
            modal.addEventListener('click', function (event) {
                if (event.target === modal) { modal.classList.add('hidden'); }
            });
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') { modal.classList.add('hidden'); }
            });
        }
    }

    /* Jahreszahl im Footer */
    var year = document.getElementById('copyright-year');
    if (year) { year.textContent = new Date().getFullYear(); }
}());
