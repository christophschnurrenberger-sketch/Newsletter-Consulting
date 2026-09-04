/**
 * Kleine Hilfen für die Verwaltung – bewusst ohne Framework.
 */
(function () {
    'use strict';

    /*
     * Rückfrage vor gefährlichen Aktionen (data-confirm="Wirklich löschen?").
     *
     * Statt des nüchternen Browser-Dialogs ein eigenes Fenster im Stil der
     * Verwaltung. Der Browser-Dialog fragt sofort (synchron); unser Fenster
     * antwortet erst später. Deshalb halten wir den Klick zuerst an, zeigen
     * das Fenster – und lösen bei „Ja" genau denselben Klick noch einmal aus,
     * diesmal ohne Rückfrage. So bleibt das Absenden von Formularen und das
     * Folgen von Links unverändert, samt Name und Wert eines Knopfes.
     */
    document.addEventListener('click', function (event) {
        var el = event.target.closest('[data-confirm]');
        if (!el) { return; }
        if (el.nlBestaetigt) { el.nlBestaetigt = false; return; }  // durchlassen

        event.preventDefault();
        event.stopPropagation();

        var gefahr = el.classList.contains('ist-gefahr') || el.classList.contains('ad-btn-danger');
        nlFrage(el.getAttribute('data-confirm'), {
            ok: el.getAttribute('data-confirm-ok') || (gefahr ? 'Löschen' : 'Fortfahren'),
            gefahr: gefahr
        }).then(function (ja) {
            if (ja) {
                el.nlBestaetigt = true;
                el.click();
            }
        });
    });

    /**
     * Ein Bestätigungsfenster. Gibt ein Versprechen zurück, das mit true
     * (bestätigt) oder false (abgebrochen) endet. Bewusst ohne Framework.
     */
    function nlFrage(text, opt) {
        opt = opt || {};
        return new Promise(function (fertig) {
            var vorherAktiv = document.activeElement;

            var hinter = document.createElement('div');
            hinter.className = 'ad-frage-hinter';

            var box = document.createElement('div');
            box.className = 'ad-frage';
            box.setAttribute('role', 'dialog');
            box.setAttribute('aria-modal', 'true');

            var p = document.createElement('p');
            p.className = 'ad-frage-text';
            p.textContent = text || 'Sind Sie sicher?';
            box.appendChild(p);

            var reihe = document.createElement('div');
            reihe.className = 'ad-frage-knoepfe';

            var nein = document.createElement('button');
            nein.type = 'button';
            nein.className = 'ad-btn ad-btn-secondary';
            nein.textContent = 'Abbrechen';

            var ja = document.createElement('button');
            ja.type = 'button';
            ja.className = 'ad-btn' + (opt.gefahr ? ' ad-btn-gefahr-voll' : '');
            ja.textContent = opt.ok || 'Fortfahren';

            reihe.appendChild(nein);
            reihe.appendChild(ja);
            box.appendChild(reihe);
            hinter.appendChild(box);
            document.body.appendChild(hinter);

            ja.focus();

            function schliessen(antwort) {
                document.removeEventListener('keydown', beiTaste, true);
                hinter.remove();
                if (vorherAktiv && typeof vorherAktiv.focus === 'function') { vorherAktiv.focus(); }
                fertig(antwort);
            }
            function beiTaste(e) {
                if (e.key === 'Escape') { e.preventDefault(); schliessen(false); }
                if (e.key === 'Enter')  { e.preventDefault(); schliessen(true); }
            }

            nein.addEventListener('click', function () { schliessen(false); });
            ja.addEventListener('click', function () { schliessen(true); });
            hinter.addEventListener('mousedown', function (e) {
                if (e.target === hinter) { schliessen(false); }  // Klick daneben
            });
            document.addEventListener('keydown', beiTaste, true);
        });
    }
    // Auch für andere Skripte auf der Seite nutzbar
    window.nlFrage = nlFrage;

    /* Alle Zeilen einer Tabelle auswählen */
    var toggleAll = document.querySelector('[data-check-all]');
    if (toggleAll) {
        toggleAll.addEventListener('change', function () {
            var name = toggleAll.getAttribute('data-check-all');
            document.querySelectorAll('input[name="' + name + '"]').forEach(function (box) {
                box.checked = toggleAll.checked;
            });
            document.dispatchEvent(new CustomEvent('nl:auswahl'));
        });
    }

    /*
     * Sammelaktionen einer Tabelle.
     *
     * Die Leiste bleibt weg, solange nichts angekreuzt ist – sonst steht
     * dort ein Knopf, der nichts tut. Sobald etwas ausgewählt ist, zeigt
     * sie die Anzahl und nur die Felder, die zur gewählten Aktion gehören.
     */
    var sammelform = document.querySelector('form[data-sammelform]');
    if (sammelform) {
        var leiste   = sammelform.querySelector('[data-sammelleiste]');
        var zahl     = sammelform.querySelector('[data-sammelzahl]');
        var wahl     = sammelform.querySelector('[data-sammelaktion]');
        var zielListe= sammelform.querySelector('[data-sammelliste]');
        var ausfuehr = leiste ? leiste.querySelector('button[type="submit"]') : null;
        var aufheben = sammelform.querySelector('[data-sammelweg]');
        var hinweis  = sammelform.querySelector('[data-sammelhinweis]');
        var kaesten  = function () { return sammelform.querySelectorAll('tbody input[type="checkbox"]'); };

        var pruefen = function () {
            var gewaehlt = 0;
            kaesten().forEach(function (box) { if (box.checked) { gewaehlt++; } });

            if (leiste)  { leiste.hidden = gewaehlt === 0; }
            if (hinweis) { hinweis.hidden = gewaehlt > 0; }
            if (zahl) {
                zahl.textContent = gewaehlt === 1 ? '1 Empfänger ausgewählt'
                                                  : gewaehlt + ' Empfänger ausgewählt';
            }
            if (zielListe) {
                var braucht = wahl && (wahl.value === 'liste_zu' || wahl.value === 'liste_weg');
                zielListe.hidden = !braucht;
            }
            if (ausfuehr) {
                ausfuehr.disabled = gewaehlt === 0 || !wahl || wahl.value === '';
            }
            if (toggleAll) {
                var alle = kaesten().length;
                toggleAll.checked = alle > 0 && gewaehlt === alle;
                toggleAll.indeterminate = gewaehlt > 0 && gewaehlt < alle;
            }
        };

        sammelform.addEventListener('change', function (event) {
            if (event.target.matches('input[type="checkbox"], [data-sammelaktion]')) { pruefen(); }
        });
        document.addEventListener('nl:auswahl', pruefen);
        if (aufheben) {
            aufheben.addEventListener('click', function () {
                kaesten().forEach(function (box) { box.checked = false; });
                if (toggleAll) { toggleAll.checked = false; }
                pruefen();
            });
        }
        pruefen();
    }

    /* Vorschau im Editor aktualisieren, ohne die Seite zu speichern */
    var previewButton = document.querySelector('[data-preview-refresh]');
    if (previewButton) {
        previewButton.addEventListener('click', function () {
            var frame = document.getElementById('preview-frame');
            if (frame) {
                frame.src = frame.src.split('#')[0] + '&t=' + Date.now();
            }
        });
    }

    /*
     * Warnung bei ungespeicherten Änderungen.
     *
     * Wo von selbst gespeichert wird, hat die Rückfrage nichts zu suchen:
     * Zwischen dem Tippen und dem Sichern liegt rund eine Sekunde, und wer
     * in dieser Sekunde weiterklickt, bekam bisher „Website verlassen?“ –
     * obwohl gleich darauf gespeichert worden wäre. Deshalb fragt der
     * Browser bei diesen Formularen nur noch, wenn eine Speicherung
     * tatsächlich fehlgeschlagen ist. Formulare ohne Selbstspeicherung
     * (etwa der Ablauf einer Strecke) fragen weiterhin bei jeder Änderung.
     */
    var form = document.querySelector('form[data-warn-unsaved]');
    if (form) {
        var selbstSichernd = !!form.querySelector('[data-builder][data-autosave]');
        var dirty = false;
        form.addEventListener('input', function () {
            if (!selbstSichernd) { dirty = true; }
        });
        form.addEventListener('submit', function () { dirty = false; });
        document.addEventListener('nl:speicherstand', function (event) {
            var stand = (event.detail || {}).stand;
            if (stand === 'gespeichert') { dirty = false; }
            if (stand === 'fehler') { dirty = true; }
            if (stand === 'ungespeichert' && !selbstSichernd) { dirty = true; }
        });
        window.addEventListener('beforeunload', function (event) {
            if (dirty) {
                event.preventDefault();
                event.returnValue = '';
            }
        });
    }

    /*
     * Aufklappbare Karten merken sich ihren Zustand.
     * Wer die Vorschau offen haben will, soll sie nicht nach jedem
     * Speichern erneut aufklappen müssen.
     */
    document.querySelectorAll('details[data-merken]').forEach(function (karte) {
        var schluessel = 'nl-klapp-' + karte.getAttribute('data-merken');
        var gemerkt;
        try { gemerkt = window.localStorage.getItem(schluessel); } catch (e) { gemerkt = null; }
        if (gemerkt === 'auf')  { karte.open = true; }
        if (gemerkt === 'zu')   { karte.open = false; }
        karte.addEventListener('toggle', function () {
            try { window.localStorage.setItem(schluessel, karte.open ? 'auf' : 'zu'); } catch (e) { /* egal */ }
        });
    });

    /*
     * Zeilenmenüs („…“): immer nur eines offen, und ein Klick daneben
     * oder Escape schließt wieder. Ohne das bleiben aufgeklappte Menüs
     * über der Tabelle stehen.
     */
    /*
     * Solange ein Zeilenmenü offen ist, darf sein Tabellenrahmen nicht
     * clippen – sonst klemmt das Aufklappmenü im scrollenden Rahmen ein und
     * ein Scrollbalken erscheint. Die Klasse hebt das Clipping nur für die
     * kurze Zeit auf, in der das Menü offen ist.
     */
    function tabellenRahmenAnpassen() {
        document.querySelectorAll('.ad-table-wrap').forEach(function (wrap) {
            wrap.classList.toggle('hat-menue-offen', !!wrap.querySelector('details.ad-menue[open]'));
        });
    }

    document.addEventListener('click', function (event) {
        document.querySelectorAll('details.ad-menue[open]').forEach(function (menue) {
            if (!menue.contains(event.target)) { menue.open = false; }
        });
        tabellenRahmenAnpassen();
    });
    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') { return; }
        document.querySelectorAll('details.ad-menue[open]').forEach(function (menue) { menue.open = false; });
        tabellenRahmenAnpassen();
    });
    document.addEventListener('toggle', function (event) {
        var menue = event.target;
        if (!menue.matches || !menue.matches('details.ad-menue')) { return; }
        if (menue.open) {
            document.querySelectorAll('details.ad-menue[open]').forEach(function (andere) {
                if (andere !== menue) { andere.open = false; }
            });
        }
        tabellenRahmenAnpassen();
    }, true);

    /*
     * Mobile Navigation: der Menü-Knopf öffnet die Schublade; Overlay,
     * Escape und ein Klick auf einen Navigationspunkt schließen sie wieder.
     */
    (function () {
        var knopf = document.querySelector('.ad-nav-toggle');
        if (!knopf) { return; }
        var overlay = document.querySelector('.ad-nav-overlay');
        var nav = document.getElementById('ad-hauptnav');
        function setzen(auf) {
            document.body.classList.toggle('nav-auf', auf);
            knopf.setAttribute('aria-expanded', auf ? 'true' : 'false');
            knopf.setAttribute('aria-label', auf ? 'Menü schließen' : 'Menü öffnen');
        }
        knopf.addEventListener('click', function () {
            setzen(!document.body.classList.contains('nav-auf'));
        });
        if (overlay) { overlay.addEventListener('click', function () { setzen(false); }); }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && document.body.classList.contains('nav-auf')) { setzen(false); }
        });
        if (nav) {
            nav.addEventListener('click', function (e) { if (e.target.closest('a')) { setzen(false); } });
        }
        window.addEventListener('resize', function () {
            if (window.innerWidth > 860) { setzen(false); }
        });
    })();

    /*
     * Seitenmenü anheften: am Desktop steht die Navigation normal als schmale
     * Leiste (nur Symbole) und fährt beim Überfahren auf. Mit der Nadel bleibt
     * sie dauerhaft offen; der Zustand wird im Browser gemerkt, damit er nach
     * dem Neuladen erhalten bleibt (siehe kleines Skript im Seitenkopf).
     */
    (function () {
        var pin = document.querySelector('.ad-nav-pin');
        if (!pin) { return; }
        var text = pin.querySelector('.ad-nav-text');
        function anzeigen(fest) {
            pin.setAttribute('aria-pressed', fest ? 'true' : 'false');
            pin.title = fest ? 'Menü wieder einklappen' : 'Menü angeheftet halten';
            if (text) { text.textContent = fest ? 'Angeheftet' : 'Anheften'; }
        }
        function setzen(fest) {
            document.body.classList.toggle('nav-fest', fest);
            document.body.classList.toggle('nav-schmal', !fest);
            try { localStorage.setItem('ad-nav', fest ? 'fest' : 'schmal'); } catch (e) {}
            anzeigen(fest);
        }
        pin.addEventListener('click', function () {
            setzen(!document.body.classList.contains('nav-fest'));
        });
        anzeigen(document.body.classList.contains('nav-fest'));
    })();

    /*
     * „Optimalen Versand übernehmen": trägt den empfohlenen Zeitpunkt in das
     * Datums-/Zeitfeld ein und hebt es kurz hervor.
     */
    document.addEventListener('click', function (event) {
        var el = event.target.closest('[data-setze-zeit]');
        if (!el) { return; }
        var ziel = document.getElementById(el.getAttribute('data-setze-zeit'));
        var wert = el.getAttribute('data-zeit') || '';
        if (!ziel || wert === '') { return; }
        ziel.value = wert;
        ziel.dispatchEvent(new Event('change', { bubbles: true }));
        ziel.classList.add('ad-eben-gesetzt');
        setTimeout(function () { ziel.classList.remove('ad-eben-gesetzt'); }, 1200);
        try { ziel.focus({ preventScroll: true }); } catch (e) { ziel.focus(); }
    });

    /*
     * Einstellungen: aus den Bereichen Reiter machen – aber nur, wenn
     * JavaScript läuft. Ohne bleibt die Seite als eine lange Liste bedienbar.
     */
    (function () {
        var box = document.querySelector('[data-einstellungen]');
        if (!box) { return; }
        var tabs = Array.prototype.slice.call(box.querySelectorAll('.ad-tab'));
        var panels = Array.prototype.slice.call(box.querySelectorAll('.ad-tab-panel'));
        if (tabs.length === 0 || panels.length === 0) { return; }
        box.classList.add('hat-reiter');

        function zeige(ziel) {
            var gefunden = false;
            tabs.forEach(function (t) {
                var an = t.getAttribute('data-ziel') === ziel;
                t.classList.toggle('aktiv', an);
                t.setAttribute('aria-selected', an ? 'true' : 'false');
                if (an) { gefunden = true; }
            });
            panels.forEach(function (p) { p.classList.toggle('aktiv', p.id === ziel); });
            return gefunden;
        }
        function ausHash() {
            var h = (location.hash || '').replace('#', '');
            if (!h) { return ''; }
            var el = document.getElementById(h);
            var panel = el ? el.closest('.ad-tab-panel') : null;
            return panel ? panel.id : '';
        }
        tabs.forEach(function (t) {
            t.setAttribute('role', 'tab');
            t.addEventListener('click', function () {
                var ziel = t.getAttribute('data-ziel');
                if (zeige(ziel) && window.history && history.replaceState) {
                    history.replaceState(null, '', '#' + ziel);
                }
                window.scrollTo(0, 0);
            });
        });
        // Ändert sich der Anker (etwa ein Link auf #rueck), gleich den passenden
        // Reiter öffnen – nicht nur beim ersten Laden.
        window.addEventListener('hashchange', function () {
            var z = ausHash();
            if (z) { zeige(z); }
        });
        zeige(ausHash() || (panels[0] && panels[0].id));
    })();

    /*
     * Kontexthilfe: ein „?" neben der Seitenüberschrift und ein Popup, das den
     * passenden Hilfetext von hilfe.php nachlädt. Die Inhalte stehen zentral in
     * lib/Hilfe.php – hier ist nur die Anzeige.
     */
    var hilfeTimer = [];
    function hilfeAnimationen(wurzel) {
        (wurzel || document).querySelectorAll('.hilfe-anim').forEach(function (fig) {
            var bilder = fig.querySelectorAll('img');
            if (bilder.length < 2) { if (bilder[0]) { bilder[0].classList.add('aktiv'); } return; }
            var i = 0;
            bilder.forEach(function (b, k) { b.classList.toggle('aktiv', k === 0); });
            hilfeTimer.push(window.setInterval(function () {
                bilder[i].classList.remove('aktiv');
                i = (i + 1) % bilder.length;
                bilder[i].classList.add('aktiv');
            }, 2200));
        });
    }
    (function () {
        var thema = (document.body.getAttribute('data-hilfe-seite') || '').trim();
        if (thema) {
            var kopf = document.querySelector('.ad-page-head h1');
            if (kopf) {
                var b = document.createElement('button');
                b.type = 'button'; b.className = 'ad-hilfe-knopf'; b.textContent = '?';
                b.setAttribute('data-hilfe', thema);
                b.setAttribute('aria-label', 'Hilfe zu dieser Seite');
                b.title = 'Hilfe zu dieser Seite';
                kopf.appendChild(b);
            }
        }
        // Auf der Handbuch-Seite die Animationen sofort starten.
        hilfeAnimationen(document);

        var hinter = null, vorher = null;
        function schliessen() {
            if (!hinter) { return; }
            hilfeTimer.forEach(function (t) { window.clearInterval(t); }); hilfeTimer = [];
            hinter.remove(); hinter = null;
            document.body.classList.remove('hilfe-offen');
            if (vorher && vorher.focus) { vorher.focus(); }
        }
        function oeffnen(id, quelle) {
            vorher = quelle || document.activeElement;
            hinter = document.createElement('div'); hinter.className = 'ad-hilfe-hinter';
            hinter.innerHTML = '<div class="ad-hilfe" role="dialog" aria-modal="true" aria-label="Hilfe">'
                + '<button type="button" class="ad-hilfe-zu" aria-label="Schließen">×</button>'
                + '<div class="ad-hilfe-koerper"><p class="ad-hilfe-laedt">Hilfe wird geladen …</p></div></div>';
            document.body.appendChild(hinter);
            document.body.classList.add('hilfe-offen');
            var koerper = hinter.querySelector('.ad-hilfe-koerper');
            hinter.querySelector('.ad-hilfe-zu').addEventListener('click', schliessen);
            hinter.addEventListener('click', function (e) { if (e.target === hinter) { schliessen(); } });
            hinter.querySelector('.ad-hilfe-zu').focus();
            fetch('hilfe.php?teil=1&thema=' + encodeURIComponent(id), { credentials: 'same-origin' })
                .then(function (r) { return r.text(); })
                .then(function (html) { koerper.innerHTML = html; koerper.scrollTop = 0; hilfeAnimationen(koerper); })
                .catch(function () {
                    koerper.innerHTML = '<div class="hilfe-inhalt"><p>Die Hilfe konnte nicht geladen werden. '
                        + '<a href="hilfe.php" target="_blank" rel="noopener">Zum Handbuch →</a></p></div>';
                });
        }
        document.addEventListener('click', function (e) {
            var el = e.target.closest('[data-hilfe]');
            if (!el || !(el.getAttribute('data-hilfe') || '').trim()) { return; }
            if (el.tagName === 'A') { return; }   // der Navigationslink bleibt ein Link
            e.preventDefault();
            oeffnen(el.getAttribute('data-hilfe').trim(), el);
        });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { schliessen(); } });
    })();

    /* „Kopieren"-Knöpfe (data-kopiere="#ziel") – etwa für API-Schlüssel. */
    document.addEventListener('click', function (event) {
        var knopf = event.target.closest('[data-kopiere]');
        if (!knopf) { return; }
        var ziel = document.querySelector(knopf.getAttribute('data-kopiere'));
        if (!ziel) { return; }
        var text = ziel.textContent || '';
        var fertig = function () {
            var alt = knopf.textContent;
            knopf.textContent = 'Kopiert ✓';
            window.setTimeout(function () { knopf.textContent = alt; }, 1500);
        };
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(fertig, function () {});
        } else {
            try {
                var bereich = document.createRange(); bereich.selectNodeContents(ziel);
                var auswahl = window.getSelection(); auswahl.removeAllRanges(); auswahl.addRange(bereich);
                document.execCommand('copy'); auswahl.removeAllRanges(); fertig();
            } catch (e) {}
        }
    });

    /* Fortschritt eines laufenden Versands automatisch aktualisieren */
    var progress = document.querySelector('[data-autorefresh]');
    if (progress) {
        var seconds = parseInt(progress.getAttribute('data-autorefresh'), 10) || 20;
        window.setTimeout(function () { window.location.reload(); }, seconds * 1000);
    }
})();
