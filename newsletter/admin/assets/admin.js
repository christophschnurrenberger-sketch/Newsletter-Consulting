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
        });
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
     * Wo der Baukasten von selbst speichert, meldet er seinen Stand. Nach
     * einer geglückten Speicherung ist nichts mehr offen – dann darf der
     * Browser beim Weggehen auch nicht fragen. Vorher gab es die Rückfrage
     * selbst dann, wenn oben schon „Gespeichert um …“ stand.
     */
    var form = document.querySelector('form[data-warn-unsaved]');
    if (form) {
        var dirty = false;
        form.addEventListener('input', function () { dirty = true; });
        form.addEventListener('submit', function () { dirty = false; });
        document.addEventListener('nl:speicherstand', function (event) {
            var stand = (event.detail || {}).stand;
            if (stand === 'gespeichert') { dirty = false; }
            if (stand === 'ungespeichert' || stand === 'fehler') { dirty = true; }
        });
        window.addEventListener('beforeunload', function (event) {
            if (dirty) {
                event.preventDefault();
                event.returnValue = '';
            }
        });
    }

    /* Fortschritt eines laufenden Versands automatisch aktualisieren */
    var progress = document.querySelector('[data-autorefresh]');
    if (progress) {
        var seconds = parseInt(progress.getAttribute('data-autorefresh'), 10) || 20;
        window.setTimeout(function () { window.location.reload(); }, seconds * 1000);
    }
})();
