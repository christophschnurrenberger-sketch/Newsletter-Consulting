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

    /*
     * Beim ersten Zeichnen ist nichts geändert – das Feld muss nur zum
     * Stand passen. Ohne diese Unterscheidung galt der Ablauf schon beim
     * Öffnen als geändert, und der Browser fragte beim Weggehen nach.
     */
    var ersterAufbau = true;

    function save(nurFeld) {
        field.value = JSON.stringify(state);
        if (nurFeld) { return; }
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
        // Die Ablageflächen werden bei jedem Zeichnen neu erzeugt – die alten
        // Einträge zeigen sonst auf Elemente, die es nicht mehr gibt.
        ablagen = [];
        canvas.innerHTML = '';
        canvas.appendChild(nodeList(state.nodes, true));
        renderInspector();
        save(ersterAufbau);
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

    /* ------------------------------------------------------------- Ziehen
     *
     * Wie im Newsletter-Baukasten über Zeigerereignisse statt über
     * HTML5-Drag-&-Drop: Letzteres läuft auf Touchgeräten gar nicht und in
     * Firefox nicht an <button>-Elementen – man konnte einen Schritt also
     * nur an dem winzigen Griff und nur mit der Maus bewegen.
     */
    var zug     = null;   // { art:'neu'|'move', wert, schatten, zone }
    var ablagen = [];     // alle Ablageflächen samt Ziel-Liste und Position

    function dropZone(list, index, gross) {
        var zone = document.createElement('div');
        zone.className = 'fl-drop' + (gross ? ' fl-drop-large' : '');
        zone.innerHTML = '<span class="fl-drop-line"></span>';
        ablagen.push({ el: zone, list: list, index: index });
        return zone;
    }

    /**
     * Die Ablagefläche, die dem Zeiger am nächsten liegt – großzügig
     * gesucht, denn die Flächen sind nur wenige Pixel hoch.
     */
    function zoneUnterZeiger(x, y) {
        var treffer  = null;
        var naechste = 1e9;
        ablagen.forEach(function (eintrag) {
            if (!eintrag.el.isConnected) { return; }
            var r = eintrag.el.getBoundingClientRect();
            if (r.width === 0) { return; }
            if (x < r.left - 40 || x > r.right + 40) { return; }
            var abstand = Math.abs(y - (r.top + r.height / 2));
            if (abstand < naechste) { naechste = abstand; treffer = eintrag; }
        });
        return naechste > 400 ? null : treffer;
    }

    function zugBeenden(abbruch) {
        if (!zug) { return; }
        if (zug.schatten && zug.schatten.parentNode) { zug.schatten.parentNode.removeChild(zug.schatten); }
        if (zug.zone) { zug.zone.el.classList.remove('is-over'); }
        document.body.classList.remove('bk-zieht');
        var fertig = zug;
        zug = null;

        if (abbruch || !fertig.zone) { render(); return; }

        if (fertig.art === 'neu') {
            var node = makeNode(fertig.wert);
            fertig.zone.list.splice(fertig.zone.index, 0, node);
            selected = node.id;
            render();
            return;
        }

        var found = locate(fertig.wert);
        if (!found) { render(); return; }
        // Eine Bedingung darf nicht in den eigenen Zweig wandern
        if (found.node.type === 'bedingung' && enthaelt(found.node, fertig.zone.list)) {
            toast('Eine Bedingung kann nicht in den eigenen Zweig verschoben werden.');
            render();
            return;
        }
        var ziel = fertig.zone.index;
        if (found.list === fertig.zone.list && found.index < ziel) { ziel--; }
        found.list.splice(found.index, 1);
        fertig.zone.list.splice(ziel, 0, found.node);
        selected = found.node.id;
        render();
    }

    /** Schluckt genau einen Klick – den, der auf ein Ziehen folgt. */
    function klickSchlucken() {
        function weg(event) {
            event.stopPropagation();
            event.preventDefault();
            document.removeEventListener('click', weg, true);
        }
        document.addEventListener('click', weg, true);
        window.setTimeout(function () { document.removeEventListener('click', weg, true); }, 400);
    }

    /** Macht ein Element ziehbar. `holen` liefert { art, wert, name }. */
    function ziehbar(element, holen) {
        element.addEventListener('pointerdown', function (event) {
            if (event.button !== 0) { return; }
            if (event.target.closest && event.target.closest('[data-act], a, input, select, textarea')) { return; }

            var startX = event.clientX;
            var startY = event.clientY;
            var gestartet = false;
            var zeiger = event.pointerId;

            function bewegen(e) {
                if (!gestartet) {
                    if (Math.abs(e.clientX - startX) + Math.abs(e.clientY - startY) < 6) { return; }
                    var daten = holen();
                    if (!daten) { return; }
                    gestartet = true;
                    try { element.setPointerCapture(zeiger); } catch (err) { /* egal */ }

                    var schatten = document.createElement('div');
                    schatten.className = 'bk-ghost';
                    schatten.textContent = daten.name || 'Schritt';
                    document.body.appendChild(schatten);
                    document.body.classList.add('bk-zieht');
                    zug = { art: daten.art, wert: daten.wert, schatten: schatten, zone: null };
                }
                e.preventDefault();
                zug.schatten.style.left = e.clientX + 'px';
                zug.schatten.style.top  = e.clientY + 'px';

                var ziel = zoneUnterZeiger(e.clientX, e.clientY);
                if (zug.zone && zug.zone !== ziel) { zug.zone.el.classList.remove('is-over'); }
                zug.zone = ziel;
                if (ziel) { ziel.el.classList.add('is-over'); }
            }

            function loslassen() {
                abmelden();
                if (!gestartet) { return; }
                // Nach dem Loslassen feuert der Browser noch einen Klick –
                // der würde den Schritt ein zweites Mal anhängen.
                klickSchlucken();
                zugBeenden(false);
            }
            function abbrechen() { abmelden(); zugBeenden(true); }
            function abmelden() {
                document.removeEventListener('pointermove', bewegen);
                document.removeEventListener('pointerup', loslassen);
                document.removeEventListener('pointercancel', abbrechen);
            }

            document.addEventListener('pointermove', bewegen);
            document.addEventListener('pointerup', loslassen);
            document.addEventListener('pointercancel', abbrechen);
        });
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

        // Der ganze Kopf ist Anfasser, nicht nur das Griffsymbol
        ziehbar(kopf, function () {
            return { art: 'move', wert: node.id, name: beschreibung(node) };
        });

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

        // E-Mail: Betreff zeigen und direkt zum Inhalt führen
        if (node.type === 'mail') {
            var info = document.createElement('div');
            info.className = 'fl-node-body';
            var betreff = STEPS[node.step_id] || '';

            if (!node.step_id) {
                /*
                 * Ein frisch eingesetzter Schritt hat noch keine Kennung – die
                 * vergibt der Server beim Speichern. Vorher stand hier nur der
                 * Hinweis „erst speichern", und wer den übersah, kam nie zum
                 * Inhalt. Jetzt erledigt ein Knopf beides auf einmal: speichern
                 * und danach den Editor dieses Schrittes öffnen.
                 */
                var neu = document.createElement('button');
                neu.type = 'button';
                neu.className = 'fl-mailbtn';
                neu.textContent = '✎ Inhalt schreiben';
                neu.title = 'Ablauf speichern und die Mail dieses Schrittes bearbeiten';
                neu.addEventListener('click', function (event) {
                    event.stopPropagation();
                    zumInhalt(node.id);
                });
                info.appendChild(neu);
                var wink = document.createElement('span');
                wink.className = 'fl-hint';
                wink.textContent = ' – wird dabei gespeichert';
                info.appendChild(wink);
            } else {
                var text = document.createElement('span');
                text.className = 'fl-mailsubject';
                if (betreff) {
                    text.textContent = betreff;
                } else {
                    text.innerHTML = '<em>noch ohne Betreff</em>';
                }
                var hin = document.createElement('a');
                hin.className = 'fl-mailbtn';
                hin.href = EDIT_URL + node.step_id;
                hin.textContent = '✎ Inhalt bearbeiten';
                info.appendChild(text);
                info.appendChild(hin);
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
            p.textContent = 'Betreff und Inhalt dieser Mail schreiben Sie im Baukasten – '
                + 'wahlweise von Grund auf oder aus einem vorhandenen Newsletter übernommen.';
            karte.appendChild(p);

            var los = document.createElement('button');
            los.type = 'button';
            los.className = 'fl-mailbtn';
            los.style.marginLeft = '0';
            if (node.step_id) {
                los.textContent = '✎ Inhalt bearbeiten';
                los.addEventListener('click', function () {
                    window.location.href = EDIT_URL + node.step_id;
                });
            } else {
                los.textContent = '✎ Inhalt schreiben';
                los.title = 'Ablauf speichern und die Mail dieses Schrittes bearbeiten';
                los.addEventListener('click', function () { zumInhalt(node.id); });
            }
            karte.appendChild(los);
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
        chip.removeAttribute('draggable');
        ziehbar(chip, function () {
            return { art: 'neu', wert: typ, name: (LABELS.nodes && LABELS.nodes[typ]) || typ };
        });
        chip.addEventListener('click', function () {
            var node = makeNode(typ);
            state.nodes.push(node);
            selected = node.id;
            render();
            var karte = canvas.querySelector('.fl-node[data-id="' + node.id + '"]');
            if (karte) { karte.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
        });
    });

    /**
     * Speichert den Ablauf und springt anschließend in den Inhalt dieses
     * Schrittes. Die Kennung des Knotens geht mit; der Server sucht danach
     * den zugehörigen Mailschritt heraus und leitet dorthin weiter.
     */
    function zumInhalt(nodeId) {
        var form = root.closest('form');
        if (!form) { return; }
        save();
        var merker = form.querySelector('[name="weiter_zu"]');
        if (!merker) {
            merker = document.createElement('input');
            merker.type = 'hidden';
            merker.name = 'weiter_zu';
            form.appendChild(merker);
        }
        merker.value = nodeId;
        var knopf = form.querySelector('button[value="speichern"]');
        if (knopf) { knopf.click(); } else { form.submit(); }
    }

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
    if (form) { form.addEventListener('submit', function () { save(); }); }

    render();
    ersterAufbau = false;
})();
