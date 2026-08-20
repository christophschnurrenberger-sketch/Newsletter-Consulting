/**
 * Grundfunktionen der Website: Icons, Navigation, FAQ, Kontaktformular.
 *
 * Alles läuft ohne Bibliothek und ohne externe Aufrufe. Die Icons stehen als
 * SVG-Pfade in dieser Datei, damit kein CDN nötig ist und die Seite auch dann
 * vollständig aussieht, wenn ein Firmennetz Fremdadressen sperrt – was in
 * genau den Unternehmen, für die diese Seite gedacht ist, der Normalfall ist.
 */
(function () {
    'use strict';

    /* ------------------------------------------------------------------ *
     * Icons (Strichzeichnungen im Stil von Lucide, 24×24)
     * ------------------------------------------------------------------ */

    var ICONS = {
        'alert-triangle': '<path d="m21.7 18-8-14a2 2 0 0 0-3.4 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.7-3Z"></path><path d="M12 9v4"></path><path d="M12 17h.01"></path>',
        'arrow-right': '<path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path>',
        award: '<circle cx="12" cy="8" r="6"></circle><path d="M15.5 13.5 17 22l-5-3-5 3 1.5-8.5"></path>',
        'bar-chart': '<path d="M3 3v18h18"></path><path d="M8 17v-5"></path><path d="M13 17V7"></path><path d="M18 17v-8"></path>',
        'book-open': '<path d="M12 7v14"></path><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"></path>',
        briefcase: '<rect width="20" height="14" x="2" y="7" rx="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>',
        building: '<rect width="16" height="20" x="4" y="2" rx="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01M16 6h.01M12 6h.01M12 10h.01M12 14h.01M16 10h.01M16 14h.01M8 10h.01M8 14h.01"></path>',
        'calendar-check': '<path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path><path d="m9 16 2 2 4-4"></path>',
        check: '<path d="M20 6 9 17l-5-5"></path>',
        'check-circle': '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><path d="m9 11 3 3L22 4"></path>',
        'chevron-down': '<path d="m6 9 6 6 6-6"></path>',
        'clipboard-check': '<rect width="8" height="4" x="8" y="2" rx="1"></rect><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><path d="m9 14 2 2 4-4"></path>',
        clock: '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
        compass: '<circle cx="12" cy="12" r="10"></circle><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>',
        database: '<ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"></path><path d="M3 12c0 1.66 4.03 3 9 3s9-1.34 9-3"></path>',
        euro: '<path d="M4 10h12"></path><path d="M4 14h9"></path><path d="M19 6a7.7 7.7 0 0 0-5.2-2A7.9 7.9 0 0 0 6 12c0 4.4 3.5 8 7.8 8 2 0 3.8-.8 5.2-2"></path>',
        eye: '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle>',
        'file-text': '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v5h5"></path><path d="M9 13h6"></path><path d="M9 17h6"></path>',
        'git-branch': '<line x1="6" x2="6" y1="3" y2="15"></line><circle cx="18" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><path d="M18 9a9 9 0 0 1-9 9"></path>',
        globe: '<circle cx="12" cy="12" r="10"></circle><path d="M12 2a15 15 0 0 1 0 20a15 15 0 0 1 0-20Z"></path><path d="M2 12h20"></path>',
        'graduation-cap': '<path d="M22 10 12 5 2 10l10 5 10-5Z"></path><path d="M6 12v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"></path>',
        'help-circle': '<circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><path d="M12 17h.01"></path>',
        inbox: '<polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>',
        layers: '<path d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"></path><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"></path><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"></path>',
        'life-buoy': '<circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="4"></circle><path d="m4.93 4.93 4.24 4.24"></path><path d="m14.83 14.83 4.24 4.24"></path><path d="m14.83 9.17 4.24-4.24"></path><path d="m4.93 19.07 4.24-4.24"></path>',
        'line-chart': '<path d="M3 3v18h18"></path><path d="m19 9-5 5-4-4-3 3"></path>',
        link: '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>',
        'list-checks': '<path d="m3 5 2 2 4-4"></path><path d="m3 13 2 2 4-4"></path><path d="M13 6h8"></path><path d="M13 14h8"></path><path d="M13 20h8"></path><path d="m3 21 2 2 4-4"></path>',
        lock: '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>',
        mail: '<rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>',
        map: '<path d="M14.1 4.1 20 2v16l-5.9 2.1a2 2 0 0 1-1.2 0L9.1 18.6a2 2 0 0 0-1.2 0L2 20.7V4.6l5.9-2.1a2 2 0 0 1 1.2 0l4.8 1.6a2 2 0 0 0 1.2 0Z"></path><path d="M9 3v16"></path><path d="M15 5v16"></path>',
        menu: '<line x1="4" x2="20" y1="12" y2="12"></line><line x1="4" x2="20" y1="6" y2="6"></line><line x1="4" x2="20" y1="18" y2="18"></line>',
        'message-circle': '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>',
        phone: '<path d="M13.83 19.17a15.9 15.9 0 0 1-6.9-6.9l1.85-1.85a2 2 0 0 0 .5-2L8.4 4.8A2 2 0 0 0 6.55 3.6H4.2A2 2 0 0 0 2.2 5.75 17.8 17.8 0 0 0 18.25 21.8a2 2 0 0 0 2.15-2v-2.35a2 2 0 0 0-1.2-1.85l-3.62-1.55a2 2 0 0 0-2 .5z"></path>',
        repeat: '<path d="m17 2 4 4-4 4"></path><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><path d="m7 22-4-4 4-4"></path><path d="M21 13v2a4 4 0 0 1-4 4H3"></path>',
        scale: '<path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"></path><path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"></path><path d="M7 21h10"></path><path d="M12 3v18"></path><path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2"></path>',
        search: '<circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path>',
        server: '<rect width="20" height="8" x="2" y="2" rx="2"></rect><rect width="20" height="8" x="2" y="14" rx="2"></rect><line x1="6" x2="6.01" y1="6" y2="6"></line><line x1="6" x2="6.01" y1="18" y2="18"></line>',
        settings: '<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2Z"></path><circle cx="12" cy="12" r="3"></circle>',
        'shield-check': '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path><path d="m9 12 2 2 4-4"></path>',
        target: '<circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle>',
        'trending-up': '<polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline>',
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

    var menuButton = document.getElementById('mobile-menu-button');
    var nav = document.getElementById('main-nav');
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
               Übergang – der Wechsel von hidden springt sonst hart. */
            window.requestAnimationFrame(function () { item.classList.add('is-open'); });
        } else {
            item.classList.remove('is-open');
            if (isDesktopNav()) {
                window.setTimeout(function () {
                    if (!item.classList.contains('is-open')) { mega.hidden = true; }
                }, 180);
            } else {
                mega.hidden = true;
            }
        }
        if (backdrop && isDesktopNav()) { backdrop.hidden = !open; }
    }

    function closeAllMega(except) {
        for (var i = 0; i < megaItems.length; i++) {
            if (megaItems[i] !== except) { setMega(megaItems[i], false); }
        }
        if (backdrop && !except) { backdrop.hidden = true; }
    }

    for (var m = 0; m < megaItems.length; m++) {
        (function (item) {
            var button = item.querySelector('.nav-toggle');
            if (!button) { return; }

            button.addEventListener('click', function () {
                var open = item.classList.contains('is-open');
                closeAllMega(open ? null : item);
                setMega(item, !open);
            });

            item.addEventListener('mouseenter', function () {
                if (!isDesktopNav()) { return; }
                window.clearTimeout(hoverTimer);
                closeAllMega(item);
                setMega(item, true);
            });

            item.addEventListener('mouseleave', function () {
                if (!isDesktopNav()) { return; }
                hoverTimer = window.setTimeout(function () { setMega(item, false); }, 160);
            });

            item.addEventListener('focusout', function (event) {
                if (!isDesktopNav()) { return; }
                if (!item.contains(event.relatedTarget)) { setMega(item, false); }
            });
        }(megaItems[m]));
    }

    if (backdrop) {
        backdrop.addEventListener('click', function () { closeAllMega(); });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') { closeAllMega(); closeMenu(); }
    });

    function closeMenu() {
        if (!nav || !menuButton) { return; }
        nav.classList.remove('is-open');
        menuButton.setAttribute('aria-expanded', 'false');
        menuButton.setAttribute('aria-label', 'Menü öffnen');
        document.body.style.overflow = '';
    }

    if (menuButton && nav) {
        menuButton.addEventListener('click', function () {
            var open = nav.classList.toggle('is-open');
            menuButton.setAttribute('aria-expanded', String(open));
            menuButton.setAttribute('aria-label', open ? 'Menü schließen' : 'Menü öffnen');
            document.body.style.overflow = open ? 'hidden' : '';
        });

        var navLinks = nav.querySelectorAll('a');
        for (var l = 0; l < navLinks.length; l++) {
            navLinks[l].addEventListener('click', closeMenu);
        }
    }

    window.addEventListener('resize', function () {
        if (isDesktopNav()) { closeMenu(); }
    });

    /* ------------------------------------------------------------------ *
     * Sprungmarken mit Rücksicht auf die klebende Kopfzeile
     * ------------------------------------------------------------------ */

    var header = document.getElementById('header');
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
            var top = target.getBoundingClientRect().top + window.pageYOffset - offset - 14;
            window.scrollTo({ top: top, behavior: 'smooth' });
            target.setAttribute('tabindex', '-1');
            target.focus({ preventScroll: true });
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
        }, { threshold: 0.1 });
        for (var r = 0; r < reveal.length; r++) { revealObserver.observe(reveal[r]); }
    } else {
        for (var r2 = 0; r2 < reveal.length; r2++) { reveal[r2].classList.add('is-visible'); }
    }

    /* ------------------------------------------------------------------ *
     * FAQ-Akkordeon
     * ------------------------------------------------------------------ */

    var questions = document.querySelectorAll('.faq-question');
    for (var q = 0; q < questions.length; q++) {
        questions[q].addEventListener('click', function () {
            var answer = this.nextElementSibling;
            if (!answer) { return; }
            var isOpen = answer.classList.contains('open');

            answer.classList.toggle('open', !isOpen);
            answer.setAttribute('aria-hidden', String(isOpen));
            this.classList.toggle('active', !isOpen);
            this.setAttribute('aria-expanded', String(!isOpen));
        });
    }

    /* ------------------------------------------------------------------ *
     * Reifegrad-Selbsteinschätzung (nur auf der Wissensseite vorhanden)
     *
     * Rechnet im Browser, sendet nichts. Wer die Auswertung verschicken will,
     * druckt die Seite – bewusst kein Formular, das Daten abgreift.
     * ------------------------------------------------------------------ */

    var selfCheck = document.getElementById('reifegrad-check');
    if (selfCheck) {
        var scoreOut = document.getElementById('reifegrad-score');
        var levelOut = document.getElementById('reifegrad-level');
        var textOut = document.getElementById('reifegrad-text');

        var LEVELS = [
            { max: 1.4, name: 'Stufe 1 – zufällig', text: 'Es gibt Wissen, aber kaum Struktur. Vieles hängt an einzelnen Personen. Eine Prüfung würde heute deutliche Feststellungen erzeugen. Sinnvoller Einstieg: Quick Assessment, danach Rollen und Dokumentation.' },
            { max: 2.4, name: 'Stufe 2 – teilweise geregelt', text: 'Einzelne Prozesse laufen geordnet, andere gar nicht. Typisch für gewachsene IT-Bereiche. Sinnvoller Einstieg: Gap-Analyse, um die Lücken zu priorisieren statt alles gleichzeitig anzufassen.' },
            { max: 3.4, name: 'Stufe 3 – definiert', text: 'Es gibt Regelungen, sie sind dokumentiert und im Alltag bekannt. Was meist fehlt, sind Nachweise und Kennzahlen. Sinnvoller Einstieg: Kontrollframework und Audit Readiness.' },
            { max: 4.4, name: 'Stufe 4 – gesteuert', text: 'Prozesse werden gemessen und nachgehalten, Nachweise entstehen im Betrieb. Sinnvoller Einstieg: Feinschliff über die laufende Betreuung, Vorbereitung auf Zertifizierung.' },
            { max: 5.1, name: 'Stufe 5 – optimierend', text: 'Die IT-Steuerung ist etabliert und wird selbst weiterentwickelt. Externe Beratung lohnt hier nur noch punktuell – etwa als zweite Meinung vor einer Zertifizierung.' }
        ];

        var selects = selfCheck.querySelectorAll('select');

        function evaluate() {
            var sum = 0, count = 0;
            for (var s = 0; s < selects.length; s++) {
                var value = Number(selects[s].value);
                if (value > 0) { sum += value; count++; }
            }
            if (!count) {
                if (scoreOut) { scoreOut.textContent = '–'; }
                if (levelOut) { levelOut.textContent = 'Noch keine Antwort'; }
                if (textOut) { textOut.textContent = 'Beantworten Sie die Fragen so, wie es heute wirklich ist – nicht so, wie es im Konzept steht.'; }
                return;
            }

            var average = sum / count;
            var rounded = Math.round(average * 10) / 10;
            var level = LEVELS[LEVELS.length - 1];
            for (var i = 0; i < LEVELS.length; i++) {
                if (average <= LEVELS[i].max) { level = LEVELS[i]; break; }
            }

            if (scoreOut) { scoreOut.textContent = rounded.toFixed(1).replace('.', ','); }
            if (levelOut) { levelOut.textContent = level.name + ' (' + count + ' von ' + selects.length + ' Fragen beantwortet)'; }
            if (textOut) { textOut.textContent = level.text; }
        }

        for (var sc = 0; sc < selects.length; sc++) {
            selects[sc].addEventListener('change', evaluate);
        }
        evaluate();
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
            company: { required: true },
            message: { required: true, minLength: 30 },
            captcha: { required: true, captcha: true }
        };

        /* Rechenaufgabe gegen Formular-Roboter. Die Zahlen gehen als verstecktes
           Feld mit, damit der Server nachrechnen kann – eine Prüfung allein im
           Browser wäre wertlos. */
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

            var consent = document.getElementById('datenschutz');
            if (consent && !consent.checked) {
                showStatus('Bitte bestätigen Sie, dass Sie die Datenschutzhinweise zur Kenntnis genommen haben.');
                valid = false;
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
                                ? data.errors.map(function (err) { return err.message; }).join(' ')
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
