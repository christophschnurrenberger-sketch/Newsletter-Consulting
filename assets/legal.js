(function () {
    'use strict';

    // Nur die auf den Rechtsseiten benötigten Icons (lokal, kein externes CDN)
    var iconPaths = {
        menu: '<line x1="4" x2="20" y1="12" y2="12"></line><line x1="4" x2="20" y1="6" y2="6"></line><line x1="4" x2="20" y1="18" y2="18"></line>'
    };

    function renderLocalIcons(root) {
        (root || document).querySelectorAll('[data-lucide]').forEach(function (icon) {
            var name = icon.getAttribute('data-lucide');
            var paths = iconPaths[name];
            if (!paths) return;
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

    // Mobile-Menü
    var menuButton = document.getElementById('mobile-menu-button');
    if (menuButton) {
        menuButton.addEventListener('click', function () {
            var isOpen = document.body.classList.toggle('nav-open');
            menuButton.setAttribute('aria-expanded', String(isOpen));
            menuButton.setAttribute('aria-label', isOpen ? 'Menü schließen' : 'Menü öffnen');
        });
    }

    // Sticky-Header Schatten beim Scrollen
    var header = document.getElementById('header');
    if (header) {
        window.addEventListener('scroll', function () {
            header.classList.toggle('scrolled', window.scrollY > 50);
        });
    }

    // Copyright-Jahr
    var yearEl = document.getElementById('copyright-year');
    if (yearEl) {
        yearEl.textContent = new Date().getFullYear();
    }

    // Telefon-Spoiler: Nummer erst auf Klick anzeigen (steht nicht im Klartext im HTML)
    document.querySelectorAll('.phone-spoiler').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (btn.classList.contains('is-revealed')) { return; }
            var tel, disp;
            try {
                tel = atob(btn.getAttribute('data-tel') || '');
                disp = atob(btn.getAttribute('data-display') || '');
            } catch (e) { return; }
            if (!tel || !disp) { return; }
            var link = document.createElement('a');
            link.href = 'tel:' + tel;
            link.textContent = disp;
            btn.textContent = '';
            btn.appendChild(link);
            btn.classList.add('is-revealed');
        });
    });

    // Kopierschutz auf Rechtsseiten (nur Deterrent): Kopieren/Ausschneiden/Kontextmenü unterbinden
    if (document.body.classList.contains('no-copy')) {
        ['copy', 'cut', 'contextmenu', 'dragstart'].forEach(function (evt) {
            document.addEventListener(evt, function (e) {
                // Interaktive Elemente (Links, Buttons, Formularfelder) nicht blockieren
                if (e.target.closest('a, button, input, textarea')) { return; }
                e.preventDefault();
            });
        });
    }
})();
