/**
 * Kleine Hilfen für die Verwaltung – bewusst ohne Framework.
 */
(function () {
    'use strict';

    /* Rückfrage vor gefährlichen Aktionen (data-confirm="Wirklich löschen?") */
    document.addEventListener('click', function (event) {
        var el = event.target.closest('[data-confirm]');
        if (el && !window.confirm(el.getAttribute('data-confirm'))) {
            event.preventDefault();
            event.stopPropagation();
        }
    });

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
    document.addEventListener('click', function (event) {
        document.querySelectorAll('details.ad-menue[open]').forEach(function (menue) {
            if (!menue.contains(event.target)) { menue.open = false; }
        });
    });
    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') { return; }
        document.querySelectorAll('details.ad-menue[open]').forEach(function (menue) { menue.open = false; });
    });
    document.addEventListener('toggle', function (event) {
        var menue = event.target;
        if (!menue.matches || !menue.matches('details.ad-menue') || !menue.open) { return; }
        document.querySelectorAll('details.ad-menue[open]').forEach(function (andere) {
            if (andere !== menue) { andere.open = false; }
        });
    }, true);

    /* Fortschritt eines laufenden Versands automatisch aktualisieren */
    var progress = document.querySelector('[data-autorefresh]');
    if (progress) {
        var seconds = parseInt(progress.getAttribute('data-autorefresh'), 10) || 20;
        window.setTimeout(function () { window.location.reload(); }, seconds * 1000);
    }
})();
