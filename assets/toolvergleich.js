(function () {
    'use strict';

    /* Lokale Icons (kein externes CDN) */
    var iconPaths = {
        menu: '<line x1="4" x2="20" y1="12" y2="12"></line><line x1="4" x2="20" y1="6" y2="6"></line><line x1="4" x2="20" y1="18" y2="18"></line>',
        check: '<path d="M20 6 9 17l-5-5"></path>',
        x: '<path d="M18 6 6 18"></path><path d="m6 6 12 12"></path>',
        'chevron-down': '<path d="m6 9 6 6 6-6"></path>',
        'arrow-right': '<path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path>',
        'external-link': '<path d="M15 3h6v6"></path><path d="M10 14 21 3"></path><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>',
        'shield-check': '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path><path d="m9 12 2 2 4-4"></path>'
    };

    function renderLocalIcons(root) {
        (root || document).querySelectorAll('[data-lucide]').forEach(function (icon) {
            var name = icon.getAttribute('data-lucide');
            var paths = iconPaths[name];
            if (!paths || (icon.tagName.toLowerCase() === 'svg')) { return; }
            var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('viewBox', '0 0 24 24');
            svg.setAttribute('aria-hidden', icon.getAttribute('aria-hidden') || 'true');
            svg.setAttribute('focusable', 'false');
            svg.setAttribute('stroke', 'currentColor');
            svg.setAttribute('stroke-width', '2');
            svg.setAttribute('stroke-linecap', 'round');
            svg.setAttribute('stroke-linejoin', 'round');
            svg.setAttribute('fill', 'none');
            svg.setAttribute('class', icon.getAttribute('class') || 'lucide');
            svg.innerHTML = paths;
            icon.replaceWith(svg);
        });
    }
    renderLocalIcons();

    /* Mobile-Menü */
    var menuButton = document.getElementById('mobile-menu-button');
    if (menuButton) {
        menuButton.addEventListener('click', function () {
            var isOpen = document.body.classList.toggle('nav-open');
            menuButton.setAttribute('aria-expanded', String(isOpen));
            menuButton.setAttribute('aria-label', isOpen ? 'Menü schließen' : 'Menü öffnen');
        });
    }

    /* Sticky-Header Schatten beim Scrollen */
    var header = document.getElementById('header');
    if (header) {
        window.addEventListener('scroll', function () {
            header.classList.toggle('scrolled', window.scrollY > 50);
        });
    }

    /* FAQ-Akkordeon (gleiche Logik/Styles wie auf der Startseite) */
    document.querySelectorAll('.faq-question').forEach(function (question) {
        question.addEventListener('click', function () {
            var answer = question.nextElementSibling;
            var isOpen = answer.classList.contains('open');
            document.querySelectorAll('.faq-answer.open').forEach(function (openAnswer) {
                if (openAnswer !== answer) {
                    openAnswer.classList.remove('open');
                    openAnswer.setAttribute('aria-hidden', 'true');
                    var oq = openAnswer.previousElementSibling;
                    oq.classList.remove('active');
                    oq.setAttribute('aria-expanded', 'false');
                }
            });
            answer.classList.toggle('open', !isOpen);
            answer.setAttribute('aria-hidden', String(isOpen));
            question.classList.toggle('active', !isOpen);
            question.setAttribute('aria-expanded', String(!isOpen));
        });
    });

    /* Copyright-Jahr */
    var yearEl = document.getElementById('copyright-year');
    if (yearEl) { yearEl.textContent = new Date().getFullYear(); }

    /* -----------------------------------------------------------------
       Consent-Platzhalter (§ 25 TDDDG): Tracking erst NACH Einwilligung.
       Aktuell ist bewusst KEIN Tracking aktiv – loadTracking() ist leer.
       ----------------------------------------------------------------- */
    function loadTracking() {
        /* PLATZHALTER: Hier erst nach Zustimmung Tracking-/Analytics-Skripte
           laden (z. B. per document.createElement('script')).
           Solange leer lassen = kein Tracking. */
    }

    var CONSENT_KEY = 'tv_consent';
    var banner = document.getElementById('consent-banner');
    if (banner) {
        var choice = null;
        try { choice = localStorage.getItem(CONSENT_KEY); } catch (e) {}
        if (!choice) { banner.classList.add('is-visible'); }
        if (choice === 'accepted') { loadTracking(); }

        var accept = banner.querySelector('.consent-accept');
        var decline = banner.querySelector('.consent-decline');
        if (accept) {
            accept.addEventListener('click', function () {
                try { localStorage.setItem(CONSENT_KEY, 'accepted'); } catch (e) {}
                banner.classList.remove('is-visible');
                loadTracking();
            });
        }
        if (decline) {
            decline.addEventListener('click', function () {
                try { localStorage.setItem(CONSENT_KEY, 'declined'); } catch (e) {}
                banner.classList.remove('is-visible');
            });
        }
    }
})();
