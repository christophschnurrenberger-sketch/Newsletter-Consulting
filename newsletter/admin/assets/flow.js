/**
 * flow.js – Baukasten für Automationen.
 *
 * Knoten (Warten, E-Mail, Bedingung, Aktion, Ende) werden per Drag & Drop
 * untereinander gezogen. Bedingungen haben zwei Zweige (Ja / Nein), in die
 * ebenfalls Knoten passen. Gespeichert wird ein JSON-Ablauf; ausgeführt wird
 * er auf dem Server (lib/Flow.php und lib/Automations.php).
 */
(function () {
    'use strict';

    var root = document.querySelector('[data-flow]');
    if (!root) {
        return;
    }

    var field     = root.querySelector('[data-flow-field]');
    var canvas    = root.querySelector('[data-canvas]');
    var inspector = root.querySelector('[data-inspector]');
    var LISTS     = JSON.parse(root.getAttribute('data-lists') || '[]');
    var STEPS     = JSON.parse(root.getAttribute('data-steps') || '{}');
    var LABELS    = JSON.parse(root.getAttribute('data-labels') || '{}');
    var EDIT_URL  = root.getAttribute('data-edit-url') || '';

    var state    = parseState(field.value);
    var selected = null;

    function parseState(json) {
        try {
            var data = JSON.parse(json);
            if (data && Array.isArray(data.nodes)) {
                return { nodes: data.nodes };
            }
        } catch (e) { /* neu anfangen */ }
        return { nodes: [] };
    }

    function save() {
        field.value = JSON.stringify(state);
        field.dispatchEvent(new Event('input', { bubbles: true }));
    }

    function uid() {
        return 'n' + Math.random().toString(16).slice(2, 10);
    }

    function esc(value) {
        return String(value === undefined || value === null ? '' : value)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function listName(id) {
        for (var i = 0; i < LISTS.length; i++) {
            if (String(LISTS[i].id) === String(id)) { return LISTS[i].name; }
        }
        return 'keine Liste gewählt';
    }

    /* --------------------------------------------------------- Knoten anlegen */

    var DEFAULTS = {
        warten:    { value: 1, einheit: 'tage' },
        mail:      { step_id: 0 },
        bedingung: { pruefung: 'geoeffnet', list_id: 0, ja: [], nein: [] },
        aktion:    { aktion: 'liste_hinzufuegen', list_id: 0 },
        ende:      {}
    };

    function makeNode(type) {
        var node = { id: uid(), type: type };
        var base = DEFAULTS[type] || {};
        Object.keys(base).forEach(function (key) {
            var value = base[key];
            node[key] = Array.isArray(value) ? [] : value;
        });
        return node;
    }

    /* ------------------------------------------------------- Suchen/Verschieben */

    function locate(id, list) {
        list = list || state.nodes;
        for (var i = 0; i < list.length; i++) {
            if (list[i].id === id) { return { list: list, index: i, node: list[i] }; }
            if (list[i].type === 'bedingung') {
                var ja = locate(id, list[i].ja || []);
                if (ja) { return ja; }
                var nein = locate(id, list[i].nein || []);
                if (nein) { return nein; }
            }
        }
        return null;
    }

    function removeNode(id) {
        var found = locate(id);
        if (found) {
            found.list.splice(found.index, 1);
            if (selected === id) { selected = null; }
        }
    }

    function moveNode(id, richtung) {
        var found = locate(id);
        if (!found) { return; }
        var ziel = found.index + richtung;
        if (ziel < 0 || ziel >= found.list.length) { return; }
        var node = found.list.splice(found.index, 1)[0];
        found.list.splice(ziel, 0, node);
    }

    /* ------------------------------------------------------------- Zeichnen */

    function render() {
        canvas.innerHTML = '';
        canvas.appendChild(nodeList(state.nodes, true));
        renderInspector();
        save();
    }

    /** Eine Kette von Knoten samt Ablageflächen dazwischen. */
    function nodeList(list, istHaupt) {
        var box = document.createElement('div');
        box.className = 'fl-list';

        box.appendChild(dropZone(list, 0, list.length === 0));
        list.forEach(function (node, index) {
            box.appendChild(nodeCard(node));
            box.appendChild(dropZone(list, index + 1));
        });

        if (list.length === 0 && istHaupt) {
            var leer = document.createElement('p');
            leer.className = 'fl-empty';
            leer.textContent = 'Ziehen Sie links einen Schritt hierher – zum Beispiel „Warten“, dann „E-Mail senden“.';
            box.appendChild(leer);
        }
        return box;
    }

    function dropZone(list, index, gross) {
        var zone = document.createElement('div');
        zone.className = 'fl-drop' + (gross ? ' fl-drop-large' : '');
        zone.innerHTML = '<span class="fl-drop-line"></span>';
        zone.addEventListener('dragover', function (event) {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
            zone.classList.add('is-over');
        });
        zone.addEventListener('dragleave', function () { zone.classList.remove('is-over'); });
        zone.addEventListener('drop', function (event) {
            event.preventDefault();
            event.stopPropagation();
            zone.classList.remove('is-over');
            handleDrop(event, list, index);
        });
        return zone;
    }

    function handleDrop(event, list, index) {
        var neuerTyp = event.dataTransfer.getData('nl/newnode');
        var bewegen  = event.dataTransfer.getData('nl/movenode');

        if (neuerTyp) {
            var node = makeNode(neuerTyp);
            list.splice(index, 0, node);
            selected = node.id;
            render();
            return;
        }
        if (bewegen) {
            var found = locate(bewegen);
            if (!found) { return; }
            // Eine Bedingung darf nicht in den eigenen Zweig wandern
            if (found.node.type === 'bedingung' && locate(bewegen, list) === null
                && enthaelt(found.node, list)) {
                toast('Eine Bedingung kann nicht in den eigenen Zweig verschoben werden.');
                return;
            }
            var ziel = index;
            if (found.list === list && found.index < index) { ziel--; }
            found.list.splice(found.index, 1);
            list.splice(ziel, 0, found.node);
            selected = found.node.id;
            render();
        }
    }

    /** Steckt die Liste irgendwo unterhalb dieses Knotens? */
    function enthaelt(node, list) {
        if (node.type !== 'bedingung') { return false; }
        if (node.ja === list || node.nein === list) { return true; }
        var treffer = false;
        (node.ja || []).concat(node.nein || []).forEach(function (kind) {
            if (enthaelt(kind, list)) { treffer = true; }
        });
        return treffer;
    }

    var ICONS = { warten: '⏱', mail: '✉', bedingung: '?', aktion: '⚙', ende: '■' };

    function nodeCard(node) {
        var card = document.createElement('div');
        card.className = 'fl-node fl-node-' + node.type + (selected === node.id ? ' is-selected' : '');
        card.setAttribute('data-id', node.id);

        var kopf = document.createElement('div');
        kopf.className = 'fl-node-bar';
        kopf.innerHTML =
            '<span class="fl-grip" draggable="true" title="Zum Verschieben ziehen" aria-hidden="true">⠿</span>' +
            '<span class="fl-node-icon" aria-hidden="true">' + ICONS[node.type] + '</span>' +
            '<span class="fl-node-title">' + esc(beschreibung(node)) + '</span>' +
            '<span class="fl-node-tools">' +
            '<button type="button" class="fl-icon" data-act="up" title="Nach oben">↑</button>' +
            '<button type="button" class="fl-icon" data-act="down" title="Nach unten">↓</button>' +
            '<button type="button" class="fl-icon fl-icon-danger" data-act="del" title="Entfernen">✕</button>' +
            '</span>';

        var griff = kopf.querySelector('.fl-grip');
        griff.addEventListener('dragstart', function (event) {
            event.dataTransfer.setData('nl/movenode', node.id);
            event.dataTransfer.effectAllowed = 'move';
            card.classList.add('is-dragging');
        });
        griff.addEventListener('dragend', function () { card.classList.remove('is-dragging'); });

        kopf.addEventListener('click', function (event) {
            var knopf = event.target.closest('[data-act]');
            if (!knopf) { return; }
            event.stopPropagation();
            var act = knopf.getAttribute('data-act');
            if (act === 'up')   { moveNode(node.id, -1); }
            if (act === 'down') { moveNode(node.id, 1); }
            if (act === 'del')  {
                if (!window.confirm('Diesen Schritt entfernen?')) { return; }
                removeNode(node.id);
            }
            render();
        });

        card.appendChild(kopf);

        // E-Mail: Hinweis und Verweis auf den Inhalt
        if (node.type === 'mail') {
            var info = document.createElement('div');
            info.className = 'fl-node-body';
            var betreff = STEPS[node.step_id] || '';
            if (!node.step_id) {
                info.innerHTML = '<span class="fl-hint">Erst speichern – danach lässt sich der Inhalt schreiben.</span>';
            } else {
                info.innerHTML = '<span class="fl-mailsubject">' + (betreff ? esc(betreff) : '<em>noch ohne Betreff</em>')
                    + '</span> <a class="fl-link" href="' + esc(EDIT_URL + node.step_id) + '">Inhalt bearbeiten</a>';
            }
            card.appendChild(info);
        }

        // Bedingung: zwei Zweige
        if (node.type === 'bedingung') {
            node.ja   = node.ja   || [];
            node.nein = node.nein || [];
            var zweige = document.createElement('div');
            zweige.className = 'fl-branches';

            [['ja', 'Ja', 'fl-branch-yes'], ['nein', 'Nein', 'fl-branch-no']].forEach(function (art) {
                var zweig = document.createElement('div');
                zweig.className = 'fl-branch ' + art[2];
                var titel = document.createElement('div');
                titel.className = 'fl-branch-title';
                titel.textContent = art[1];
                zweig.appendChild(titel);
                zweig.appendChild(nodeList(node[art[0]], false));
                zweige.appendChild(zweig);
            });
            card.appendChild(zweige);
        }

        card.addEventListener('click', function () {
            selected = node.id;
            render();
        });
        return card;
    }

    function beschreibung(node) {
        switch (node.type) {
            case 'warten':
                var einheiten = Number(node.value) === 1 ? (LABELS.units_one || LABELS.units) : LABELS.units;
                return node.value + ' ' + (einheiten[node.einheit] || einheiten.tage) + ' warten';
            case 'mail':
                return 'E-Mail senden';
            case 'bedingung':
                if (node.pruefung === 'in_liste') {
                    return 'Wenn Empfänger in der Liste „' + listName(node.list_id) + '“ steht';
                }
                return 'Wenn Empfänger ' + (LABELS.conditions[node.pruefung] || '');
            case 'aktion':
                var text = LABELS.actions[node.aktion] || '';
                if (node.aktion.indexOf('liste_') === 0) { text += ': ' + listName(node.list_id); }
                return text.charAt(0).toUpperCase() + text.slice(1);
            case 'ende':
                return 'Strecke endet hier';
        }
        return node.type;
    }

    /* --------------------------------------------------------- Einstellungen */

    function renderInspector() {
        inspector.innerHTML = '';
        var node = selected ? (locate(selected) || {}).node : null;

        if (!node) {
            inspector.innerHTML = '<h3>Einstellungen</h3>'
                + '<p class="fl-hint">Klicken Sie auf einen Schritt, um ihn einzustellen.</p>';
            return;
        }

        var karte = document.createElement('div');
        karte.innerHTML = '<h3>' + esc(LABELS.types[node.type] || node.type) + '</h3>';

        if (node.type === 'warten') {
            karte.appendChild(zahlFeld('Wartezeit', node, 'value', 1, 365));
            karte.appendChild(auswahlFeld('Einheit', node, 'einheit', LABELS.units));
            var hinweis = document.createElement('p');
            hinweis.className = 'fl-hint';
            hinweis.textContent = 'Die Wartezeit zählt ab dem vorherigen Schritt.';
            karte.appendChild(hinweis);
        }

        if (node.type === 'mail') {
            var p = document.createElement('p');
            p.className = 'fl-hint';
            p.innerHTML = node.step_id
                ? 'Betreff und Inhalt schreiben Sie im Baukasten: '
                  + '<a class="fl-link" href="' + esc(EDIT_URL + node.step_id) + '">Inhalt bearbeiten</a>'
                : 'Speichern Sie den Ablauf – danach können Sie den Inhalt schreiben.';
            karte.appendChild(p);
        }

        if (node.type === 'bedingung') {
            karte.appendChild(auswahlFeld('Prüfen ob der Empfänger …', node, 'pruefung', LABELS.conditions));
            if (node.pruefung === 'in_liste') {
                karte.appendChild(listenFeld('Liste', node, 'list_id'));
            }
            var erklaerung = document.createElement('p');
            erklaerung.className = 'fl-hint';
            erklaerung.textContent = 'Trifft die Bedingung zu, geht es im Ja-Zweig weiter, sonst im Nein-Zweig. '
                + 'Danach laufen beide Zweige wieder zusammen.';
            karte.appendChild(erklaerung);
        }

        if (node.type === 'aktion') {
            karte.appendChild(auswahlFeld('Was soll passieren?', node, 'aktion', LABELS.actions));
            if (node.aktion.indexOf('liste_') === 0) {
                karte.appendChild(listenFeld('Liste', node, 'list_id'));
            }
        }

        if (node.type === 'ende') {
            var e = document.createElement('p');
            e.className = 'fl-hint';
            e.textContent = 'Hier verlässt der Empfänger die Strecke. Weitere Schritte darunter werden ignoriert.';
            karte.appendChild(e);
        }

        inspector.appendChild(karte);
    }

    function feldRahmen(label) {
        var zeile = document.createElement('div');
        zeile.className = 'fl-field';
        var l = document.createElement('label');
        l.textContent = label;
        zeile.appendChild(l);
        return zeile;
    }

    function zahlFeld(label, node, key, min, max) {
        var zeile = feldRahmen(label);
        var input = document.createElement('input');
        input.type = 'number';
        input.className = 'fl-input';
        input.min = min;
        input.max = max;
        input.value = node[key];
        input.addEventListener('input', function () {
            node[key] = parseInt(input.value, 10) || min;
            aktualisiereTitel(node);
            save();
        });
        zeile.appendChild(input);
        return zeile;
    }

    function auswahlFeld(label, node, key, optionen) {
        var zeile = feldRahmen(label);
        var select = document.createElement('select');
        select.className = 'fl-input';
        Object.keys(optionen).forEach(function (wert) {
            var option = document.createElement('option');
            option.value = wert;
            option.textContent = optionen[wert];
            option.selected = node[key] === wert;
            select.appendChild(option);
        });
        select.addEventListener('change', function () {
            node[key] = select.value;
            render();
        });
        zeile.appendChild(select);
        return zeile;
    }

    function listenFeld(label, node, key) {
        var zeile = feldRahmen(label);
        var select = document.createElement('select');
        select.className = 'fl-input';
        var leer = document.createElement('option');
        leer.value = '0';
        leer.textContent = '— bitte wählen —';
        select.appendChild(leer);
        LISTS.forEach(function (liste) {
            var option = document.createElement('option');
            option.value = liste.id;
            option.textContent = liste.name;
            option.selected = String(node[key]) === String(liste.id);
            select.appendChild(option);
        });
        select.addEventListener('change', function () {
            node[key] = parseInt(select.value, 10) || 0;
            render();
        });
        zeile.appendChild(select);
        return zeile;
    }

    /** Nur die Beschriftung erneuern – die Eingabe behält den Fokus. */
    function aktualisiereTitel(node) {
        var karte = canvas.querySelector('.fl-node[data-id="' + node.id + '"] .fl-node-title');
        if (karte) { karte.textContent = beschreibung(node); }
    }

    /* --------------------------------------------------------------- Palette */

    root.querySelectorAll('[data-addnode]').forEach(function (chip) {
        var typ = chip.getAttribute('data-addnode');
        chip.addEventListener('dragstart', function (event) {
            event.dataTransfer.setData('nl/newnode', typ);
            event.dataTransfer.effectAllowed = 'copy';
        });
        chip.addEventListener('click', function () {
            var node = makeNode(typ);
            state.nodes.push(node);
            selected = node.id;
            render();
        });
    });

    canvas.addEventListener('dragover', function (event) { event.preventDefault(); });
    canvas.addEventListener('drop', function (event) {
        event.preventDefault();
        handleDrop(event, state.nodes, state.nodes.length);
    });

    function toast(text) {
        var box = document.createElement('div');
        box.className = 'bk-toast';
        box.textContent = text;
        document.body.appendChild(box);
        window.setTimeout(function () {
            if (box.parentNode) { box.parentNode.removeChild(box); }
        }, 3500);
    }

    var form = root.closest('form');
    if (form) { form.addEventListener('submit', save); }

    render();
})();
