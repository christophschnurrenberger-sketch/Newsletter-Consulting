/**
 * builder.js – der Baukasten für Newsletter und Vorlagen.
 *
 * Ohne Framework. Der Zustand steckt in einem einfachen Objekt
 * ({meta, blocks}); nach jeder Änderung wird er als JSON in ein verstecktes
 * Formularfeld geschrieben. Das E-Mail-HTML erzeugt anschließend der Server
 * (lib/Blocks.php) – so sieht die Vorschau immer das echte Ergebnis.
 */
(function () {
    'use strict';

    var root = document.querySelector('[data-builder]');
    if (!root) {
        return;
    }

    var MODE      = root.getAttribute('data-mode') || 'campaign';
    var UPLOAD    = root.getAttribute('data-upload') || 'upload.php';
    var KI        = root.getAttribute('data-ki') || '';   // leer = Assistent aus
    var CSRF      = root.getAttribute('data-csrf') || '';
    var field     = root.querySelector('[data-blocks-field]');
    var canvas    = root.querySelector('[data-canvas]');
    var inspector = root.querySelector('[data-inspector]');
    var counter   = root.querySelector('[data-count]');

    var state    = parseState(field.value);
    var selected = null;      // id des gewählten Bausteins
    var lastFocus = null;     // zuletzt bearbeitetes Textfeld (für Platzhalter)
    var lastRange = null;     // Position der Schreibmarke darin

    /* ------------------------------------------------------------- Zustand */

    function parseState(json) {
        try {
            var data = JSON.parse(json);
            if (data && typeof data === 'object') {
                return { meta: data.meta || {}, blocks: Array.isArray(data.blocks) ? data.blocks : [] };
            }
        } catch (e) { /* kaputtes JSON: neu anfangen */ }
        return { meta: {}, blocks: [] };
    }

    /*
     * Beim ersten Aufbau der Seite ist noch nichts geändert: Das Feld muss
     * nur zum Stand passen, gespeichert werden darf nichts. Sonst schrieb
     * schon das bloße Öffnen eines Newsletters in die Datenbank – und das
     * Formular galt als geändert, sodass der Browser beim Weggehen fragte.
     */
    var ersterAufbau = true;

    function save(nurFeld) {
        merkeVerlauf();
        field.value = JSON.stringify(state);
        if (nurFeld) { return; }
        field.dispatchEvent(new Event('input', { bubbles: true }));
        autoSpeichern();
    }

    /* ------------------------------------------------------------ Rückgängig
     *
     * Der Verlauf merkt sich ganze Stände als JSON – bei der Größe eines
     * Newsletters ist das ein paar Kilobyte und damit billiger als jede
     * Buchführung über einzelne Änderungen.
     *
     * Beim Tippen wird nicht jeder Buchstabe zu einem Schritt: Erst wenn
     * eine Dreiviertelsekunde Ruhe ist, wandert der Stand von vorher in den
     * Verlauf. Ein Strg+Z nimmt damit einen Satz zurück, nicht ein Zeichen.
     * Alles Strukturelle – Baustein einfügen, löschen, verschieben – wird
     * dagegen sofort festgehalten.
     */
    var verlauf     = [];
    var zukunft     = [];
    // Bewusst der geparste Stand und nicht field.value: Sonst wäre schon beim
    // Öffnen ein Unterschied da (andere Reihenfolge, andere Schreibweise) und
    // der erste Strg+Z täte scheinbar nichts.
    var letzterStand = JSON.stringify(state);
    var merkUhr     = null;
    var imRuecklauf = false;
    var VERLAUF_MAX = 60;

    function merkeVerlauf(sofort) {
        if (imRuecklauf) { return; }
        window.clearTimeout(merkUhr);
        var vorher = letzterStand;

        var schreiben = function () {
            var jetzt = JSON.stringify(state);
            if (jetzt === vorher) { return; }
            verlauf.push(vorher);
            if (verlauf.length > VERLAUF_MAX) { verlauf.shift(); }
            zukunft = [];
            letzterStand = jetzt;
            verlaufKnoepfe();
        };

        if (sofort === true) { schreiben(); } else { merkUhr = window.setTimeout(schreiben, 750); }
    }

    /** Übernimmt einen Stand aus dem Verlauf, ohne ihn erneut aufzuzeichnen. */
    function standSetzen(json) {
        imRuecklauf = true;
        state    = parseState(json);
        selected = null;
        lastFocus = null;
        lastRange = null;
        letzterStand = json;
        render();
        imRuecklauf = false;
        field.value = json;
        autoSpeichern();
        verlaufKnoepfe();
    }

    function zurueck() {
        window.clearTimeout(merkUhr);
        // Eine noch nicht festgehaltene Änderung zählt als eigener Schritt.
        var jetzt = JSON.stringify(state);
        if (jetzt !== letzterStand) { verlauf.push(letzterStand); letzterStand = jetzt; }
        if (verlauf.length === 0) { hinweis('Es gibt nichts mehr zurückzunehmen.'); return; }
        zukunft.push(jetzt);
        standSetzen(verlauf.pop());
    }

    function nachVorn() {
        if (zukunft.length === 0) { hinweis('Es gibt nichts wiederherzustellen.'); return; }
        verlauf.push(JSON.stringify(state));
        standSetzen(zukunft.pop());
    }

    function verlaufKnoepfe() {
        var zurueckKnopf = root.querySelector('[data-zurueck]');
        var vorKnopf     = root.querySelector('[data-vor]');
        if (zurueckKnopf) { zurueckKnopf.disabled = verlauf.length === 0; }
        if (vorKnopf)     { vorKnopf.disabled     = zukunft.length === 0; }
    }

    (function () {
        var z = root.querySelector('[data-zurueck]');
        var v = root.querySelector('[data-vor]');
        if (z) { z.addEventListener('click', zurueck); }
        if (v) { v.addEventListener('click', nachVorn); }
    })();

    document.addEventListener('keydown', function (event) {
        if (!(event.ctrlKey || event.metaKey) || event.altKey) { return; }
        var taste = (event.key || '').toLowerCase();
        if (taste !== 'z' && taste !== 'y') { return; }
        // Nur zuständig, solange man im Baukasten arbeitet
        if (!root.contains(document.activeElement) && document.activeElement !== document.body) { return; }

        event.preventDefault();
        if (taste === 'y' || event.shiftKey) { nachVorn(); } else { zurueck(); }
    });

    /* ------------------------------------------------------ Automatisch speichern
     * Jede Änderung geht kurz darauf von selbst zum Server. So stimmt die
     * Vorschau immer mit dem überein, was man gerade gebaut hat, und nichts
     * geht verloren, wenn der Reiter zugeht.
     */
    var speicherUhr  = null;
    var speicherLauf = false;
    var nochmal      = false;

    function autoSpeichern() {
        if (root.getAttribute('data-autosave') !== '1') { return; }
        window.clearTimeout(speicherUhr);
        speicherUhr = window.setTimeout(schicken, 900);
        merkeStand('ungespeichert');
    }

    function schicken() {
        var form = field.form;
        if (!form) { return; }
        if (speicherLauf) { nochmal = true; return; }
        speicherLauf = true;
        merkeStand('speichert');

        var daten = new FormData(form);
        daten.set('autosave', '1');
        // Der Knopf, der sonst mitgeschickt würde, fehlt bei fetch – die
        // Seite braucht die Aktion aber, um den Baukasten zu erkennen.
        if (!daten.get('aktion')) {
            daten.set('aktion', MODE === 'template' ? 'speichern_baukasten' : 'speichern');
        }

        fetch(window.location.href, {
            method: 'POST', body: daten, credentials: 'same-origin',
            headers: { 'X-Requested-With': 'fetch' }
        })
            .then(function (a) { return a.ok ? a.json() : Promise.reject(new Error(a.status)); })
            .then(function (antwort) {
                if (!antwort || !antwort.ok) { throw new Error('abgelehnt'); }
                merkeStand('gespeichert');
                vorschauErneuern();
            })
            .catch(function () { merkeStand('fehler'); })
            .then(function () {
                speicherLauf = false;
                if (nochmal) { nochmal = false; schicken(); }
            });
    }

    /** Zeigt oben am Baukasten, woran man ist. */
    function merkeStand(art) {
        // Auch die Seite selbst soll es wissen: Solange der Baukasten von
        // selbst speichert, braucht der Browser beim Weggehen nicht zu fragen.
        document.dispatchEvent(new CustomEvent('nl:speicherstand', { detail: { stand: art } }));

        var anzeige = document.querySelector('[data-autosave-status]');
        if (!anzeige) { return; }
        var texte = {
            ungespeichert: 'Änderung erkannt …',
            speichert:     'Wird gespeichert …',
            gespeichert:   'Gespeichert um ' + new Date().toLocaleTimeString('de-DE',
                               { hour: '2-digit', minute: '2-digit' }),
            fehler:        'Nicht gespeichert – bitte auf „Speichern“ klicken'
        };
        anzeige.textContent = texte[art] || '';
        anzeige.className = 'bk-autosave is-' + art;
    }

    /** Lädt die Vorschau neu, damit sie zum aktuellen Stand passt. */
    function vorschauErneuern() {
        document.querySelectorAll('iframe.ad-preview-frame').forEach(function (rahmen) {
            var quelle = rahmen.getAttribute('src') || '';
            if (quelle === '') { return; }
            var basis = quelle.split('&_t=')[0].split('?_t=')[0];
            rahmen.src = basis + (basis.indexOf('?') === -1 ? '?' : '&') + '_t=' + Date.now();
        });
    }

    function uid() {
        return 'b' + Math.random().toString(16).slice(2, 10);
    }

    function esc(value) {
        return String(value === undefined || value === null ? '' : value)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    /* -------------------------------------------------------- Bausteine anlegen */

    var DEFAULTS = {
        heading: { text: 'Ihre Überschrift', size: 22, align: 'left', color: '#14243A', space: 12 },
        text:    { html: '<p>Ihr Text …</p>', size: 15, align: 'left', color: '', space: 12 },
        image:   { src: '', alt: '', href: '', width: 100, align: 'center', space: 12 },
        button:  { label: 'Mehr erfahren', href: '', bg: '#C8102E', color: '#FFFFFF', align: 'left', radius: 6, full: 0, space: 12 },
        divider: { color: '#E0E6ED', thickness: 1, space: 12 },
        spacer:  { height: 24 },
        columns: { gap: 20, space: 12, left: [], right: [] },
        social:  { links: [{ label: 'LinkedIn', href: '' }, { label: 'Website', href: '' }], align: 'center', color: '#8A95A5', space: 12 },
        html:    { html: '<p>Eigenes HTML …</p>', space: 12 },
        content: {},
        kopf:    { stil: 'logo', bg: '#14243A', farbe: '#FFFFFF', logoText: 'A',
                   wortmarke: '', akzentTeil: '', akzentFarbe: '#C8102E', claim: '' },
        fuss:    { bg: '', farbe: '#8A95A5', hinweis: '' }
    };

    function makeBlock(type) {
        var block = { id: uid(), type: type };
        var base  = DEFAULTS[type] || {};
        Object.keys(base).forEach(function (key) {
            var value = base[key];
            block[key] = Array.isArray(value) ? JSON.parse(JSON.stringify(value)) : value;
        });
        // Neue Bausteine übernehmen die Farben der Gestaltung, damit ein
        // Newsletter nicht in fremden Akzentfarben beginnt.
        var meta = state.meta || {};
        if (type === 'button' && meta.linkColor) { block.bg = meta.linkColor; }
        if (type === 'heading' && meta.headColor) { block.color = meta.headColor; }
        if (type === 'divider' && meta.borderColor) { block.color = meta.borderColor; }
        return block;
    }

    /* ------------------------------------------------- Bausteine finden/ändern */

    /** Sucht einen Baustein samt seiner Liste (auch in Spalten). */
    function locate(id, list) {
        list = list || state.blocks;
        for (var i = 0; i < list.length; i++) {
            if (list[i].id === id) {
                return { list: list, index: i, block: list[i] };
            }
            if (list[i].type === 'columns') {
                var links = locate(id, list[i].left || []);
                if (links) { return links; }
                var rechts = locate(id, list[i].right || []);
                if (rechts) { return rechts; }
            }
        }
        return null;
    }

    function findBlock(id) {
        var found = locate(id);
        return found ? found.block : null;
    }

    function removeBlock(id) {
        var found = locate(id);
        if (found) {
            found.list.splice(found.index, 1);
            if (selected === id) { selected = null; }
        }
    }

    function moveBlock(id, direction) {
        var found = locate(id);
        if (!found) { return; }
        var ziel = found.index + direction;
        if (ziel < 0 || ziel >= found.list.length) { return; }
        var block = found.list.splice(found.index, 1)[0];
        found.list.splice(ziel, 0, block);
    }

    function duplicateBlock(id) {
        var found = locate(id);
        if (!found) { return; }
        var kopie = JSON.parse(JSON.stringify(found.block));
        kopie.id = uid();
        if (kopie.left)  { kopie.left.forEach(function (b) { b.id = uid(); }); }
        if (kopie.right) { kopie.right.forEach(function (b) { b.id = uid(); }); }
        found.list.splice(found.index + 1, 0, kopie);
        selected = kopie.id;
    }

    /* ------------------------------------------------------------- Zeichnen */

    /**
     * Kopfzeile oben, Footer unten – alles andere dazwischen.
     *
     * In einer Vorlage sind Kopf und Fuß der Rahmen der Mail. Ein Baustein,
     * der unter dem Footer landet, erscheint auch in der fertigen Mail unter
     * den Pflichtangaben; das ist nie gewollt. Deshalb wird die Reihenfolge
     * nach jeder Änderung geradegezogen, egal wie der Baustein hereinkam –
     * gezogen, angeklickt, aus den gesicherten Bausteinen eingesetzt.
     *
     * @return {boolean} true, wenn dabei etwas umsortiert wurde
     */
    function ordnung() {
        if (MODE !== 'template') { return false; }
        var kopf = [], mitte = [], fuss = [];
        state.blocks.forEach(function (block) {
            if (block.type === 'kopf')      { kopf.push(block); }
            else if (block.type === 'fuss') { fuss.push(block); }
            else                            { mitte.push(block); }
        });
        var neu = kopf.concat(mitte, fuss);
        var anders = neu.some(function (block, i) { return state.blocks[i] !== block; });
        if (anders) { state.blocks = neu; }
        return anders;
    }

    function render() {
        var geordnet = ordnung();
        ablagen = [];
        canvas.innerHTML = '';
        if (state.blocks.length === 0) {
            canvas.appendChild(dropZone(state.blocks, 0, true));
            var leer = document.createElement('p');
            leer.className = 'bk-empty';
            leer.textContent = 'Noch nichts drin. Ziehen Sie links einen Baustein hierher.';
            canvas.appendChild(leer);
        } else {
            // In einer Vorlage gibt es über der Kopfzeile und unter dem Footer
            // keine Ablagefläche – dort darf ohnehin nichts liegen.
            var ersteFrei = !(MODE === 'template' && state.blocks[0].type === 'kopf');
            if (ersteFrei) { canvas.appendChild(dropZone(state.blocks, 0)); }
            state.blocks.forEach(function (block, index) {
                canvas.appendChild(blockCard(block, state.blocks, index));
                var naechster = state.blocks[index + 1];
                var letzteFrei = !(MODE === 'template' && block.type === 'fuss')
                    && !(MODE === 'template' && naechster && naechster.type === 'kopf');
                if (letzteFrei) { canvas.appendChild(dropZone(state.blocks, index + 1)); }
            });
        }
        if (counter) {
            counter.textContent = state.blocks.length + (state.blocks.length === 1 ? ' Baustein' : ' Bausteine');
        }

        // Hinweis, solange der Inhaltsbaustein fehlt
        var fehlt = document.querySelector('[data-fehlt-inhalt]');
        if (fehlt) {
            fehlt.hidden = state.blocks.some(function (b) { return b.type === 'content'; });
        }
        renderInspector();
        // Musste die Reihenfolge geradegezogen werden, ist das eine echte
        // Änderung – die gehört gespeichert, auch beim ersten Aufbau.
        save(ersterAufbau && !geordnet);
        // render() läuft bei den strukturellen Änderungen – Baustein einfügen,
        // löschen, verschieben. Die gehören sofort in den Verlauf, nicht erst
        // nach der Tipp-Pause.
        merkeVerlauf(true);
    }

    /** Ablagefläche zwischen zwei Bausteinen. */
    /* ----------------------------------------------------------- Ziehen
     * Bewusst über Zeigerereignisse statt HTML5-Drag-&-Drop: Letzteres
     * funktioniert auf Touchgeräten gar nicht und in Firefox nicht an
     * <button>-Elementen. So lässt sich überall ziehen – auch am Finger.
     */
    var zug = null;   // { art:'neu'|'move', wert, schatten, zone }

    /** Merkt sich alle Ablageflächen samt Ziel-Liste und Position. */
    var ablagen = [];

    /**
     * Die Ablagefläche, die dem Zeiger am nächsten liegt.
     *
     * Bewusst großzügig: Die Flächen sind nur wenige Pixel hoch – man soll
     * sie nicht millimetergenau treffen müssen. Gesucht wird deshalb die
     * senkrecht nächstgelegene Fläche innerhalb der Arbeitsfläche.
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
            if (abstand < naechste) {
                naechste = abstand;
                treffer  = eintrag;
            }
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

        if (abbruch || !fertig.zone) {
            render();
            return;
        }
        if (fertig.art === 'neu') {
            einfuegenNeu(fertig.wert, fertig.zone.list, fertig.zone.index);
        } else {
            verschiebeNach(fertig.wert, fertig.zone.list, fertig.zone.index);
        }
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
            // Bedienknöpfe im Kopf des Bausteins nicht abfangen
            if (event.target.closest && event.target.closest('[data-act]')) { return; }
            if (event.target.isContentEditable) { return; }

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
                    schatten.textContent = daten.name || 'Baustein';
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
                document.removeEventListener('pointermove', bewegen);
                document.removeEventListener('pointerup', loslassen);
                document.removeEventListener('pointercancel', abbrechen);
                if (!gestartet) { return; }
                // Nach dem Loslassen feuert der Browser noch einen Klick.
                // Der würde den Baustein ein zweites Mal anhängen.
                klickSchlucken();
                zugBeenden(false);
            }
            function abbrechen() {
                document.removeEventListener('pointermove', bewegen);
                document.removeEventListener('pointerup', loslassen);
                document.removeEventListener('pointercancel', abbrechen);
                zugBeenden(true);
            }

            document.addEventListener('pointermove', bewegen);
            document.addEventListener('pointerup', loslassen);
            document.addEventListener('pointercancel', abbrechen);
        });
    }

    /** Einen neuen Baustein an einer Stelle einsetzen. */
    function einfuegenNeu(typ, list, index) {
        if (list !== state.blocks && ['columns', 'social', 'html', 'content'].indexOf(typ) !== -1) {
            hinweis('Dieser Baustein passt nicht in eine Spalte.');
            render();
            return;
        }
        var block = makeBlock(typ);
        list.splice(index, 0, block);
        selected = block.id;
        render();
    }

    /** Einen vorhandenen Baustein an eine andere Stelle setzen. */
    function verschiebeNach(id, list, index) {
        var found = locate(id);
        if (!found) { render(); return; }
        if (list !== state.blocks && ['columns', 'social', 'html', 'content'].indexOf(found.block.type) !== -1) {
            hinweis('Dieser Baustein passt nicht in eine Spalte.');
            render();
            return;
        }
        var ziel = index;
        if (found.list === list && found.index < index) { ziel--; }
        found.list.splice(found.index, 1);
        list.splice(ziel, 0, found.block);
        selected = found.block.id;
        render();
    }

    function dropZone(list, index, gross) {
        var zone = document.createElement('div');
        zone.className = 'bk-drop' + (gross ? ' bk-drop-large' : '');
        ablagen.push({ el: zone, list: list, index: index });
        return zone;
    }

    /** Karte eines Bausteins auf der Arbeitsfläche. */
    function blockCard(block, list, index) {
        var card = document.createElement('div');
        card.className = 'bk-block' + (selected === block.id ? ' is-selected' : '');
        card.setAttribute('data-id', block.id);

        var kopf = document.createElement('div');
        kopf.className = 'bk-block-bar';
        kopf.innerHTML =
            '<span class="bk-grip" title="Zum Verschieben ziehen" aria-hidden="true">⠿</span>' +
            '<span class="bk-block-name">' + esc(window.NL_BLOCK_LABELS[block.type] || block.type) + '</span>' +
            '<span class="bk-block-tools">' +
            '<button type="button" class="bk-icon" data-act="up" title="Nach oben">↑</button>' +
            '<button type="button" class="bk-icon" data-act="down" title="Nach unten">↓</button>' +
            '<button type="button" class="bk-icon" data-act="copy" title="Duplizieren">⧉</button>' +
            '<button type="button" class="bk-icon" data-act="merken" ' +
            'title="Als eigenen Baustein sichern und später wiederverwenden">☆</button>' +
            '<button type="button" class="bk-icon bk-icon-danger" data-act="del" title="Löschen">✕</button>' +
            '</span>';

        // Der ganze Kopf des Bausteins ist Anfasser, nicht nur das Griffsymbol
        ziehbar(kopf, function () {
            return { art: 'move', wert: block.id,
                     name: window.NL_BLOCK_LABELS[block.type] || block.type };
        });

        kopf.addEventListener('click', function (event) {
            var button = event.target.closest('[data-act]');
            if (!button) { return; }
            event.stopPropagation();
            var act = button.getAttribute('data-act');
            if (act === 'merken') { bausteinSichern(block); return; }
            if (act === 'up')   { moveBlock(block.id, -1); }
            if (act === 'down') { moveBlock(block.id, 1); }
            if (act === 'copy') { duplicateBlock(block.id); }
            if (act === 'del')  {
                if (!window.confirm('Diesen Baustein löschen?')) { return; }
                removeBlock(block.id);
            }
            render();
        });

        var body = document.createElement('div');
        body.className = 'bk-block-body';
        body.appendChild(preview(block));

        card.appendChild(kopf);
        card.appendChild(body);
        card.addEventListener('click', function () {
            selected = block.id;
            markSelection();
            renderInspector();
        });
        return card;
    }

    function markSelection() {
        canvas.querySelectorAll('.bk-block').forEach(function (el) {
            el.classList.toggle('is-selected', el.getAttribute('data-id') === selected);
        });
    }

    /** Ungefähre Darstellung des Bausteins (die echte zeigt die Vorschau). */
    function preview(block) {
        var box = document.createElement('div');
        var meta = state.meta || {};

        switch (block.type) {
            case 'heading':
                box.appendChild(einzeiler(block, 'text', 'Überschrift', {
                    fontSize: (block.size || 22) + 'px',
                    fontWeight: '700',
                    color: block.color || '#14243A',
                    textAlign: block.align || 'left',
                    lineHeight: '1.3'
                }));
                break;

            case 'text':
                var editor = document.createElement('div');
                editor.className = 'bk-rich';
                editor.contentEditable = 'true';
                editor.innerHTML = block.html || '<p></p>';
                editor.style.fontSize = (block.size || 15) + 'px';
                editor.style.textAlign = block.align || 'left';
                editor.style.color = block.color || (meta.textColor || '#4A5568');
                editor.addEventListener('input', function () {
                    block.html = editor.innerHTML;
                    save();
                });
                editor.addEventListener('focus', function () { lastFocus = editor; selected = block.id; markSelection(); renderInspector(); });
                editor.addEventListener('mousedown', function () { lastFocus = editor; });
                ['keyup', 'mouseup', 'blur'].forEach(function (name) {
                    editor.addEventListener(name, function () { lastFocus = editor; merkeSchreibmarke(editor); });
                });
                box.appendChild(richToolbar(editor));
                box.appendChild(editor);
                break;

            case 'image':
                if (block.src) {
                    box.innerHTML = '<div style="text-align:' + esc(block.align) + '"><img src="' + esc(block.src)
                        + '" alt="" style="width:' + (block.width || 100) + '%;max-width:100%;border-radius:4px;"></div>';
                    // Ein verlinktes Bild sieht aus wie ein unverlinktes – deshalb
                    // steht der Zustand hier, statt nur rechts im Feld zu schlummern.
                    box.appendChild(linkZeile(block));
                } else {
                    box.innerHTML = '<div class="bk-placeholder">Noch kein Bild gewählt – rechts hochladen oder Adresse eintragen.</div>';
                }
                break;

            case 'button':
                var huelle = document.createElement('div');
                huelle.style.textAlign = block.align || 'left';
                huelle.appendChild(einzeiler(block, 'label', 'Knopf', {
                    display: 'inline-block',
                    padding: '12px 24px',
                    borderRadius: (block.radius || 6) + 'px',
                    background: block.bg,
                    color: block.color,
                    fontWeight: '700',
                    fontSize: '14px'
                }));
                box.appendChild(huelle);
                break;

            case 'divider':
                box.innerHTML = '<hr style="border:none;border-top:' + (block.thickness || 1) + 'px solid '
                    + esc(block.color) + ';margin:6px 0;">';
                break;

            case 'spacer':
                box.innerHTML = '<div class="bk-spacer" style="height:' + (block.height || 24) + 'px;">'
                    + (block.height || 24) + ' px Abstand</div>';
                break;

            case 'columns':
                var grid = document.createElement('div');
                grid.className = 'bk-columns';
                ['left', 'right'].forEach(function (seite) {
                    var spalte = document.createElement('div');
                    spalte.className = 'bk-column';
                    var titel = document.createElement('div');
                    titel.className = 'bk-column-title';
                    titel.textContent = seite === 'left' ? 'Linke Spalte' : 'Rechte Spalte';
                    spalte.appendChild(titel);

                    block[seite] = block[seite] || [];
                    spalte.appendChild(dropZone(block[seite], 0, block[seite].length === 0));
                    block[seite].forEach(function (child, i) {
                        spalte.appendChild(blockCard(child, block[seite], i));
                        spalte.appendChild(dropZone(block[seite], i + 1));
                    });
                    // Ohne Klickweg käme man hier nur per Ziehen hinein.
                    spalte.appendChild(spaltenZugabe(block[seite]));
                    grid.appendChild(spalte);
                });
                box.appendChild(grid);
                break;

            case 'social':
                var teile = (block.links || []).map(function (link) { return esc(link.label); });
                box.innerHTML = '<div style="text-align:' + esc(block.align) + ';color:' + esc(block.color)
                    + ';font-size:13px;">' + (teile.join(' · ') || 'Noch keine Links') + '</div>';
                break;

            case 'html':
                box.innerHTML = '<div class="bk-code-preview">' + esc((block.html || '').slice(0, 300)) + '</div>';
                break;

            case 'content':
                box.innerHTML = '<div class="bk-content-slot">Hier erscheint der Inhalt der jeweiligen Ausgabe</div>';
                break;

            case 'kopf':
                var marke = esc(block.wortmarke || '{{marke}}');
                if (block.stil === 'wortmarke' && block.akzentTeil) {
                    marke += '<span style="color:' + esc(block.akzentFarbe) + '">' + esc(block.akzentTeil) + '</span>';
                }
                box.innerHTML = '<div style="background:' + esc(block.bg) + ';color:' + esc(block.farbe)
                    + ';padding:14px 16px;border-radius:4px;display:flex;justify-content:space-between;'
                    + 'align-items:center;gap:12px;">'
                    + (block.stil === 'wortmarke'
                        ? '<span style="font-size:20px;font-weight:700;letter-spacing:-.02em;">' + marke + '</span>'
                        : '<span style="font-weight:700;"><span style="display:inline-block;width:24px;height:24px;'
                          + 'line-height:24px;text-align:center;border-radius:5px;background:#fff;color:'
                          + esc(block.bg) + ';margin-right:8px;">' + esc(block.logoText || 'A') + '</span>{{marke}}</span>')
                    + '<span style="font-size:10px;letter-spacing:.08em;text-transform:uppercase;opacity:.75;">'
                    + esc(block.claim || '') + '</span></div>';
                break;

            case 'fuss':
                box.innerHTML = '<div style="background:' + esc(block.bg || meta.bg || '#F6F8FA') + ';color:'
                    + esc(block.farbe) + ';padding:14px 16px;border-radius:4px;font-size:12px;line-height:1.6;">'
                    + (block.hinweis ? '<div style="border-left:2px solid ' + esc(meta.accentColor || '#C8102E')
                        + ';padding-left:10px;margin-bottom:10px;">' + esc(block.hinweis) + '</div>' : '')
                    + 'Impressum, Abmeldelink, Datenschutz und „Im Browser ansehen“ – '
                    + 'stehen fest drin und sind gesetzlich vorgeschrieben.</div>';
                break;
        }
        return box;
    }

    /**
     * Zeigt unter einem Bild, ob es verlinkt ist – und führt mit einem Klick
     * zum passenden Feld. Ein Bild ohne Link ist in einem Newsletter fast
     * immer eine verschenkte Gelegenheit.
     */
    function linkZeile(block) {
        var zeile = document.createElement('button');
        zeile.type = 'button';
        zeile.className = 'bk-linkzeile' + (block.href ? ' is-verlinkt' : '');

        if (block.href) {
            var ziel = String(block.href);
            var kurz = ziel.replace(/^https?:\/\//i, '').replace(/\/$/, '');
            zeile.textContent = '🔗 verlinkt auf ' + (kurz.length > 34 ? kurz.slice(0, 33) + '…' : kurz);
            zeile.title = 'Klicken, um den Link zu ändern';
        } else {
            zeile.textContent = '🔗 Bild verlinken';
            zeile.title = 'Ein Ziel angeben, das beim Klick auf das Bild geöffnet wird';
        }

        zeile.addEventListener('click', function (event) {
            event.stopPropagation();
            selected = block.id;
            markSelection();
            renderInspector();
            var feld = inspector.querySelector('[data-feld="href"]');
            if (feld) {
                feld.scrollIntoView({ block: 'center' });
                feld.focus();
                feld.select();
            }
        });
        return zeile;
    }

    /**
     * Ein einzeiliges Feld, das sich direkt im Baustein beschriften lässt –
     * für Überschrift und Knopf. Nur Text, keine Formatierung.
     */
    function einzeiler(block, schluessel, platzhalter, stil) {
        var feld = document.createElement('div');
        feld.className = 'bk-inline';
        feld.contentEditable = 'true';
        feld.setAttribute('role', 'textbox');
        feld.setAttribute('data-platzhalter', platzhalter);
        feld.textContent = block[schluessel] || '';
        Object.keys(stil).forEach(function (k) { feld.style[k] = stil[k]; });

        feld.addEventListener('input', function () {
            block[schluessel] = feld.textContent;
            save();
        });
        // Zeilenumbruch beendet die Eingabe, statt eine zweite Zeile zu öffnen
        feld.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') { event.preventDefault(); feld.blur(); }
        });
        // Nur reinen Text einfügen
        feld.addEventListener('paste', function (event) {
            event.preventDefault();
            var text = (event.clipboardData || window.clipboardData).getData('text');
            document.execCommand('insertText', false, text.replace(/\s+/g, ' '));
        });
        feld.addEventListener('focus', function () {
            lastFocus = feld;
            selected = block.id;
            markSelection();
            renderInspector();
        });
        feld.addEventListener('mousedown', function (event) { event.stopPropagation(); lastFocus = feld; });
        ['keyup', 'mouseup', 'blur'].forEach(function (name) {
            feld.addEventListener(name, function () { lastFocus = feld; merkeSchreibmarke(feld); });
        });
        return feld;
    }

    /** Bausteine, die in eine Spalte passen. */
    var SPALTEN_TYPEN = ['text', 'heading', 'image', 'button', 'divider', 'spacer'];

    /**
     * Knopfleiste am Fuß einer Spalte: Text schreiben, Bild einsetzen und
     * weitere Bausteine – ohne dass man etwas hineinziehen muss.
     */
    function spaltenZugabe(liste) {
        var leiste = document.createElement('div');
        leiste.className = 'bk-column-add';

        [['text', 'Text schreiben'], ['image', 'Bild einsetzen']].forEach(function (paar) {
            var knopf = document.createElement('button');
            knopf.type = 'button';
            knopf.className = 'bk-column-btn';
            knopf.textContent = paar[1];
            knopf.addEventListener('click', function (event) {
                event.stopPropagation();
                inSpalteEinsetzen(liste, paar[0]);
            });
            leiste.appendChild(knopf);
        });

        var mehr = document.createElement('select');
        mehr.className = 'bk-column-select';
        var kopf = document.createElement('option');
        kopf.value = '';
        kopf.textContent = 'Mehr …';
        mehr.appendChild(kopf);
        SPALTEN_TYPEN.forEach(function (typ) {
            if (typ === 'text' || typ === 'image') { return; }
            var option = document.createElement('option');
            option.value = typ;
            option.textContent = window.NL_BLOCK_LABELS[typ] || typ;
            mehr.appendChild(option);
        });
        mehr.addEventListener('change', function (event) {
            event.stopPropagation();
            if (mehr.value !== '') { inSpalteEinsetzen(liste, mehr.value); }
        });
        mehr.addEventListener('click', function (event) { event.stopPropagation(); });
        leiste.appendChild(mehr);

        return leiste;
    }

    /** Setzt einen Baustein ans Ende einer Spalte und wählt ihn aus. */
    function inSpalteEinsetzen(liste, typ) {
        var block = makeBlock(typ);
        liste.push(block);
        selected = block.id;
        render();
        // Beim Text gleich die Schreibmarke setzen – das ist der häufigste Fall.
        var karte = canvas.querySelector('.bk-block[data-id="' + block.id + '"]');
        if (!karte) { return; }
        var feld = karte.querySelector('.bk-rich, .bk-inline');
        if (feld) { feld.focus(); }
        karte.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    /** Kleine Leiste über dem Textfeld: fett, kursiv, Link, Liste. */
    function richToolbar(editor) {
        var bar = document.createElement('div');
        bar.className = 'bk-richbar';
        var buttons = [
            ['B', 'bold', 'Fett'],
            ['I', 'italic', 'Kursiv'],
            ['U', 'underline', 'Unterstrichen'],
            ['•', 'insertUnorderedList', 'Aufzählung'],
            ['🔗', 'createLink', 'Link'],
            ['⌫', 'removeFormat', 'Formatierung entfernen']
        ];
        buttons.forEach(function (item) {
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'bk-richbtn';
            b.title = item[2];
            b.textContent = item[0];
            b.addEventListener('mousedown', function (event) { event.preventDefault(); });
            b.addEventListener('click', function () {
                editor.focus();
                if (item[1] === 'createLink') {
                    var url = window.prompt('Adresse des Links (https://…)', 'https://');
                    if (!url) { return; }
                    document.execCommand('createLink', false, url);
                } else {
                    document.execCommand(item[1], false, null);
                }
                editor.dispatchEvent(new Event('input'));
            });
            bar.appendChild(b);
        });
        if (KI) {
            var ki = document.createElement('button');
            ki.type = 'button';
            ki.className = 'bk-richbtn bk-richbtn-ki';
            ki.title = 'Textassistent – Vorschlag holen';
            ki.textContent = '✨';
            ki.addEventListener('mousedown', function (event) { event.preventDefault(); });
            ki.addEventListener('click', function () { kiOeffnen(editor); });
            bar.appendChild(ki);
        }
        return bar;
    }

    /* ------------------------------------------------------- Textassistent
     * Ein Fenster, das für das gewählte Feld einen Vorschlag holt. Der
     * Vorschlag landet nie ungefragt im Newsletter: Man liest ihn, ändert
     * ihn bei Bedarf und entscheidet dann, ob er den Text ersetzt oder an
     * der Schreibmarke eingesetzt wird.
     */

    /** Liest den Klartext eines Feldes – egal ob Eingabefeld oder Textfläche. */
    function feldText(feld) {
        if (!feld) { return ''; }
        if (feld.isContentEditable) {
            return (feld.innerText || feld.textContent || '').trim();
        }
        return ('value' in feld) ? String(feld.value).trim() : '';
    }

    /** Der gesamte Text des Newsletters – Grundlage für Betreffvorschläge. */
    function gesamterText() {
        var teile = [];
        function durch(liste) {
            (liste || []).forEach(function (block) {
                if (block.type === 'heading')      { teile.push(block.text || ''); }
                else if (block.type === 'text')    { teile.push(entHtml(block.html || '')); }
                else if (block.type === 'button')  { teile.push(block.label || ''); }
                else if (block.type === 'columns') { durch(block.left); durch(block.right); }
            });
        }
        durch(state.blocks);
        return teile.filter(function (t) { return t.trim() !== ''; }).join('\n\n');
    }

    /** Macht aus HTML wieder lesbaren Text. */
    function entHtml(html) {
        var hilfe = document.createElement('div');
        hilfe.innerHTML = html;
        return (hilfe.innerText || hilfe.textContent || '').trim();
    }

    /**
     * Öffnet den Assistenten für ein Feld.
     *
     * @param feld     Zielfeld (contentEditable oder input/textarea)
     * @param vorgabe  gewünschte Aufgabe, sonst „umformulieren"/„schreiben"
     */
    function kiOeffnen(feld, vorgabe) {
        if (!KI) { return; }
        if (!feld) {
            hinweis('Bitte klicken Sie zuerst in das Textfeld, um das es gehen soll.');
            return;
        }
        var vorhanden = feldText(feld);

        var hof = document.createElement('div');
        hof.className = 'bk-modal';
        var kasten = document.createElement('div');
        kasten.className = 'bk-modal-box bk-ki-box';
        hof.appendChild(kasten);

        var titel = document.createElement('h3');
        titel.textContent = 'Textassistent';
        kasten.appendChild(titel);

        /* Aufgabe wählen */
        var reihe = document.createElement('div');
        reihe.className = 'bk-ki-row';
        var wahl = document.createElement('select');
        wahl.className = 'bk-select';
        Object.keys(window.NL_KI_ACTIONS || {}).forEach(function (schluessel) {
            if (schluessel === 'betreff' && !vorgabe) { return; }   // nur beim Betreff sinnvoll
            var option = document.createElement('option');
            option.value = schluessel;
            option.textContent = window.NL_KI_ACTIONS[schluessel];
            wahl.appendChild(option);
        });
        wahl.value = vorgabe || (vorhanden === '' ? 'schreiben' : 'umformulieren');
        reihe.appendChild(wahl);

        var holen = document.createElement('button');
        holen.type = 'button';
        holen.className = 'ad-btn';
        holen.textContent = 'Vorschlag holen';
        reihe.appendChild(holen);
        kasten.appendChild(reihe);

        /* Freie Vorgabe */
        var hinweisFeld = document.createElement('textarea');
        hinweisFeld.className = 'bk-ki-hint';
        hinweisFeld.rows = 2;
        hinweisFeld.placeholder = 'Worum soll es gehen? (optional, z. B. „Einladung zum Golfturnier am 3. Mai")';
        kasten.appendChild(hinweisFeld);

        /* Ergebnis */
        var stand = document.createElement('p');
        stand.className = 'bk-ki-status';
        stand.textContent = vorhanden === ''
            ? 'Das Feld ist noch leer – beschreiben Sie oben kurz, worum es geht.'
            : 'Grundlage ist der Text im gewählten Feld.';
        kasten.appendChild(stand);

        var ergebnis = document.createElement('textarea');
        ergebnis.className = 'bk-ki-result';
        ergebnis.rows = 9;
        ergebnis.hidden = true;
        kasten.appendChild(ergebnis);

        /* Knöpfe */
        var leiste = document.createElement('div');
        leiste.className = 'bk-ki-actions';

        var ersetzen = document.createElement('button');
        ersetzen.type = 'button';
        ersetzen.className = 'ad-btn';
        ersetzen.textContent = 'Text ersetzen';
        ersetzen.hidden = true;

        var einfuegen = document.createElement('button');
        einfuegen.type = 'button';
        einfuegen.className = 'ad-btn ad-btn-secondary';
        einfuegen.textContent = 'An der Schreibmarke einfügen';
        einfuegen.hidden = true;

        var zu = document.createElement('button');
        zu.type = 'button';
        zu.className = 'ad-btn ad-btn-secondary';
        zu.textContent = 'Schließen';

        leiste.appendChild(ersetzen);
        leiste.appendChild(einfuegen);
        leiste.appendChild(zu);
        kasten.appendChild(leiste);

        var fussnote = document.createElement('p');
        fussnote.className = 'bk-hint';
        fussnote.textContent = 'Der Text wird zum eingestellten Anbieter übertragen und dort verarbeitet. '
            + 'Bitte prüfen Sie jeden Vorschlag, bevor er in den Versand geht.';
        kasten.appendChild(fussnote);

        function schliessen() {
            if (hof.parentNode) { hof.parentNode.removeChild(hof); }
            document.removeEventListener('keydown', taste, true);
        }
        function taste(event) {
            if (event.key === 'Escape') { schliessen(); }
        }
        document.addEventListener('keydown', taste, true);
        zu.addEventListener('click', schliessen);
        hof.addEventListener('click', function (event) {
            if (event.target === hof) { schliessen(); }
        });

        holen.addEventListener('click', function () {
            var aufgabe = wahl.value;
            // Beim Betreff ist der ganze Newsletter die Grundlage, nicht das Feld.
            var quelle = aufgabe === 'betreff' ? gesamterText() : feldText(feld);

            if (quelle === '' && hinweisFeld.value.trim() === '') {
                stand.textContent = 'Bitte schreiben Sie kurz auf, worum es gehen soll.';
                hinweisFeld.focus();
                return;
            }
            holen.disabled = true;
            stand.textContent = 'Der Assistent überlegt …';

            var daten = new FormData();
            daten.set('_csrf', CSRF);
            daten.set('aktion', aufgabe);
            daten.set('text', quelle);
            daten.set('hinweis', hinweisFeld.value);

            fetch(KI, { method: 'POST', body: daten, credentials: 'same-origin' })
                .then(function (a) { return a.json(); })
                .then(function (antwort) {
                    if (!antwort || !antwort.ok) {
                        throw new Error((antwort && antwort.fehler) || 'Der Vorschlag kam nicht an.');
                    }
                    ergebnis.value  = antwort.text;
                    ergebnis.hidden = false;
                    ersetzen.hidden = false;
                    einfuegen.hidden = (aufgabe === 'betreff');
                    stand.textContent = aufgabe === 'betreff'
                        ? 'Drei Vorschläge – lassen Sie die gewünschte Zeile oben stehen; '
                          + 'übernommen wird die erste.'
                        : 'Vorschlag – gern noch ändern und dann übernehmen:';
                    ergebnis.focus();
                })
                .catch(function (fehler) {
                    stand.textContent = String(fehler.message || fehler);
                })
                .then(function () { holen.disabled = false; });
        });

        ersetzen.addEventListener('click', function () {
            uebernehmen(feld, ergebnis.value, true);
            schliessen();
        });
        einfuegen.addEventListener('click', function () {
            uebernehmen(feld, ergebnis.value, false);
            schliessen();
        });

        document.body.appendChild(hof);
        (vorhanden === '' ? hinweisFeld : holen).focus();
    }

    /** Trägt den Vorschlag in das Feld ein – ersetzend oder an der Marke. */
    function uebernehmen(feld, text, ersetzen) {
        text = String(text || '').trim();
        if (text === '') { return; }

        if (feld.isContentEditable) {
            if (ersetzen) {
                if (feld.classList.contains('bk-rich')) {
                    // Absätze bleiben Absätze, damit die Mail sauber umbricht.
                    feld.innerHTML = text.split(/\n{2,}/).map(function (absatz) {
                        return '<p>' + esc(absatz).replace(/\n/g, '<br>') + '</p>';
                    }).join('');
                } else {
                    feld.textContent = text.replace(/\s+/g, ' ');
                }
                feld.dispatchEvent(new Event('input', { bubbles: true }));
            } else {
                einsetzenInText(feld, text);
            }
            return;
        }
        if (!('value' in feld)) { return; }

        if (ersetzen) {
            // Ein einzeiliges Feld (etwa der Betreff) bekommt die erste Zeile;
            // bei mehreren Vorschlägen steht die gewünschte dort oben.
            feld.value = feld.tagName === 'TEXTAREA'
                ? text
                : (text.split(/\n/).filter(function (z) { return z.trim() !== ''; })[0] || '').trim();
        } else {
            var stelle = (feld.selectionStart === null || feld.selectionStart === undefined)
                ? feld.value.length : feld.selectionStart;
            feld.value = feld.value.slice(0, stelle) + text + feld.value.slice(stelle);
        }
        feld.dispatchEvent(new Event('input', { bubbles: true }));
        feld.dispatchEvent(new Event('change', { bubbles: true }));
        feld.focus();
    }

    /* ----------------------------------------------------------- Einstellungen */

    var FIELDS = {
        heading: [
            { k: 'text', t: 'text', l: 'Text' },
            { k: 'size', t: 'number', l: 'Schriftgröße (px)', min: 12, max: 48 },
            { k: 'align', t: 'align', l: 'Ausrichtung' },
            { k: 'color', t: 'color', l: 'Farbe' },
            { k: 'space', t: 'number', l: 'Abstand unten (px)', min: 0, max: 80 }
        ],
        text: [
            { k: 'size', t: 'number', l: 'Schriftgröße (px)', min: 11, max: 28 },
            { k: 'align', t: 'align', l: 'Ausrichtung' },
            { k: 'color', t: 'color', l: 'Textfarbe (leer = Standard)' },
            { k: 'space', t: 'number', l: 'Abstand unten (px)', min: 0, max: 80 }
        ],
        image: [
            { k: 'src', t: 'image', l: 'Bild' },
            { k: 'alt', t: 'text', l: 'Bildbeschreibung (für Screenreader)' },
            { k: 'href', t: 'text', l: 'Verlinkt auf (optional)' },
            { k: 'width', t: 'number', l: 'Breite in %', min: 10, max: 100 },
            { k: 'align', t: 'align', l: 'Ausrichtung' },
            { k: 'space', t: 'number', l: 'Abstand unten (px)', min: 0, max: 80 }
        ],
        button: [
            { k: 'label', t: 'text', l: 'Beschriftung' },
            { k: 'href', t: 'text', l: 'Ziel-Adresse' },
            { k: 'bg', t: 'color', l: 'Hintergrundfarbe' },
            { k: 'color', t: 'color', l: 'Schriftfarbe' },
            { k: 'radius', t: 'number', l: 'Ecken abrunden (px)', min: 0, max: 30 },
            { k: 'align', t: 'align', l: 'Ausrichtung' },
            { k: 'space', t: 'number', l: 'Abstand unten (px)', min: 0, max: 80 }
        ],
        divider: [
            { k: 'color', t: 'color', l: 'Farbe' },
            { k: 'thickness', t: 'number', l: 'Stärke (px)', min: 1, max: 8 },
            { k: 'space', t: 'number', l: 'Abstand unten (px)', min: 0, max: 80 }
        ],
        spacer: [
            { k: 'height', t: 'number', l: 'Höhe (px)', min: 4, max: 120 }
        ],
        columns: [
            { k: 'gap', t: 'number', l: 'Abstand zwischen den Spalten (px)', min: 0, max: 48 },
            { k: 'space', t: 'number', l: 'Abstand unten (px)', min: 0, max: 80 }
        ],
        social: [
            { k: 'links', t: 'links', l: 'Links' },
            { k: 'align', t: 'align', l: 'Ausrichtung' },
            { k: 'color', t: 'color', l: 'Farbe' },
            { k: 'space', t: 'number', l: 'Abstand unten (px)', min: 0, max: 80 }
        ],
        html: [
            { k: 'html', t: 'textarea', l: 'HTML-Code' },
            { k: 'space', t: 'number', l: 'Abstand unten (px)', min: 0, max: 80 }
        ],
        content: [],
        kopf: [
            { k: 'stil', t: 'select', l: 'Art der Kopfzeile',
              o: { logo: 'Logo-Quadrat mit Kürzel', wortmarke: 'Wortmarke als Text' } },
            { k: 'bg', t: 'color', l: 'Hintergrund' },
            { k: 'farbe', t: 'color', l: 'Schriftfarbe' },
            { k: 'logoText', t: 'text', l: 'Kürzel im Logo (max. 3 Zeichen)' },
            { k: 'wortmarke', t: 'text', l: 'Wortmarke (leer = Name der Marke)' },
            { k: 'akzentTeil', t: 'text', l: 'Hervorgehobener Teil (z. B. „54")' },
            { k: 'akzentFarbe', t: 'color', l: 'Farbe des hervorgehobenen Teils' },
            { k: 'claim', t: 'text', l: 'Claim rechts oben (optional)' }
        ],
        fuss: [
            { k: 'bg', t: 'color', l: 'Hintergrund (leer = Seitenfarbe)' },
            { k: 'farbe', t: 'color', l: 'Schriftfarbe' },
            { k: 'hinweis', t: 'textarea', l: 'Hinweis über dem Impressum (z. B. Partnerlinks)' }
        ]
    };

    function renderInspector() {
        inspector.innerHTML = '';

        var block = selected ? findBlock(selected) : null;

        // Der angeklickte Baustein steht oben – sonst sucht man seine
        // Einstellungen unterhalb der langen Gestaltungsleiste.
        if (block) {
            var karte = document.createElement('div');
            karte.className = 'bk-panel';
            karte.innerHTML = '<h3>' + esc(window.NL_BLOCK_LABELS[block.type] || block.type) + '</h3>';

            (FIELDS[block.type] || []).forEach(function (feld) {
                karte.appendChild(fieldRow(block, feld));
            });
            if ((FIELDS[block.type] || []).length === 0) {
                var p = document.createElement('p');
                p.className = 'bk-hint';
                p.textContent = 'Dieser Baustein hat keine Einstellungen.';
                karte.appendChild(p);
            }
            inspector.appendChild(karte);
        } else {
            var hinweisBox = document.createElement('p');
            hinweisBox.className = 'bk-hint';
            hinweisBox.textContent = 'Klicken Sie auf einen Baustein, um ihn einzustellen. '
                + 'Texte und Überschriften lassen sich auch direkt anklicken und überschreiben.';
            inspector.appendChild(hinweisBox);
        }

        inspector.appendChild(metaPanel());
    }

    function fieldRow(block, feld) {
        var zeile = document.createElement('div');
        zeile.className = 'bk-field';
        zeile.setAttribute('data-zeile', feld.k);
        var label = document.createElement('label');
        label.textContent = feld.l;
        zeile.appendChild(label);

        if (feld.t === 'select') {
            var auswahl = document.createElement('select');
            auswahl.className = 'bk-input';
            Object.keys(feld.o).forEach(function (wert) {
                var option = document.createElement('option');
                option.value = wert;
                option.textContent = feld.o[wert];
                option.selected = String(block[feld.k]) === wert;
                auswahl.appendChild(option);
            });
            auswahl.addEventListener('change', function () {
                block[feld.k] = auswahl.value;
                render();
            });
            zeile.appendChild(auswahl);
            return zeile;
        }

        if (feld.t === 'align') {
            var gruppe = document.createElement('div');
            gruppe.className = 'bk-seg';
            [['left', 'Links'], ['center', 'Mitte'], ['right', 'Rechts']].forEach(function (opt) {
                var b = document.createElement('button');
                b.type = 'button';
                b.textContent = opt[1];
                b.className = block[feld.k] === opt[0] ? 'is-active' : '';
                b.addEventListener('click', function () {
                    block[feld.k] = opt[0];
                    render();
                });
                gruppe.appendChild(b);
            });
            zeile.appendChild(gruppe);
            return zeile;
        }

        if (feld.t === 'color') {
            var wrap = document.createElement('div');
            wrap.className = 'bk-colorrow';
            var farbe = document.createElement('input');
            farbe.type = 'color';
            farbe.value = /^#[0-9a-f]{6}$/i.test(block[feld.k] || '') ? block[feld.k] : '#000000';
            var text = document.createElement('input');
            text.type = 'text';
            text.className = 'bk-input';
            text.setAttribute('data-feld', feld.k);
            text.value = block[feld.k] || '';
            text.placeholder = '#RRGGBB';
            farbe.addEventListener('input', function () {
                block[feld.k] = farbe.value;
                text.value = farbe.value;
                redrawBlock(block);
            });
            text.addEventListener('change', function () {
                block[feld.k] = text.value.trim();
                render();
            });
            wrap.appendChild(farbe);
            wrap.appendChild(text);
            zeile.appendChild(wrap);
            return zeile;
        }

        if (feld.t === 'image') {
            zeile.appendChild(imageField(block, feld));
            return zeile;
        }

        if (feld.t === 'links') {
            zeile.appendChild(linkListField(block, feld));
            return zeile;
        }

        if (feld.t === 'textarea') {
            var area = document.createElement('textarea');
            area.className = 'bk-input bk-code';
            area.rows = 8;
            area.value = block[feld.k] || '';
            area.addEventListener('input', function () { block[feld.k] = area.value; save(); });
            area.addEventListener('change', function () { render(); });
            area.addEventListener('focus', function () { lastFocus = area; });
            zeile.appendChild(area);
            return zeile;
        }

        var input = document.createElement('input');
        input.className = 'bk-input';
        input.type = feld.t === 'number' ? 'number' : 'text';
        if (feld.min !== undefined) { input.min = feld.min; }
        if (feld.max !== undefined) { input.max = feld.max; }
        input.value = block[feld.k] !== undefined ? block[feld.k] : '';
        input.addEventListener('input', function () {
            block[feld.k] = feld.t === 'number' ? parseInt(input.value, 10) || 0 : input.value;
            redrawBlock(block);
        });
        input.addEventListener('focus', function () { lastFocus = input; });
        input.setAttribute('data-feld', feld.k);
        zeile.appendChild(input);
        return zeile;
    }

    /** Nur die Karte eines Bausteins neu zeichnen (Eingabe behält den Fokus). */
    function redrawBlock(block) {
        var karte = canvas.querySelector('.bk-block[data-id="' + block.id + '"]');
        if (!karte) { render(); return; }
        var body = karte.querySelector('.bk-block-body');
        if (body) {
            body.innerHTML = '';
            body.appendChild(preview(block));
        }
        save();
    }

    /* ------------------------------------------------------------ Bildauswahl */

    function imageField(block, feld) {
        var box = document.createElement('div');

        var url = document.createElement('input');
        url.className = 'bk-input';
        url.type = 'text';
        url.placeholder = 'https://… oder Bild hochladen';
        url.setAttribute('data-feld', feld.k);
        url.value = block[feld.k] || '';
        url.addEventListener('input', function () { block[feld.k] = url.value.trim(); redrawBlock(block); });
        box.appendChild(url);

        var reihe = document.createElement('div');
        reihe.className = 'bk-actions';

        var datei = document.createElement('input');
        datei.type = 'file';
        datei.accept = 'image/jpeg,image/png,image/gif,image/webp';
        datei.style.display = 'none';

        var hoch = document.createElement('button');
        hoch.type = 'button';
        hoch.className = 'bk-btn';
        hoch.textContent = 'Bild hochladen';
        hoch.addEventListener('click', function () { datei.click(); });

        var galerie = document.createElement('button');
        galerie.type = 'button';
        galerie.className = 'bk-btn bk-btn-light';
        galerie.textContent = 'Hochgeladene Bilder';
        galerie.addEventListener('click', function () { openGallery(block, feld, url); });

        var status = document.createElement('div');
        status.className = 'bk-hint';

        var bearbeiten = document.createElement('button');
        bearbeiten.type = 'button';
        bearbeiten.className = 'bk-btn bk-btn-light';
        bearbeiten.textContent = 'Zuschneiden';
        bearbeiten.addEventListener('click', function () {
            if (!block[feld.k]) { hinweis('Wählen Sie zuerst ein Bild aus.'); return; }
            bildEditor(block[feld.k], function (neueUrl, breite, hoehe) {
                block[feld.k] = neueUrl;
                url.value = neueUrl;
                status.textContent = 'Zugeschnitten (' + breite + '×' + hoehe + ' Pixel).';
                redrawBlock(block);
            }, status);
        });

        datei.addEventListener('change', function () {
            if (!datei.files || !datei.files[0]) { return; }
            status.textContent = 'Wird vorbereitet …';
            // Fotos aus der Kamera haben oft 4000 Pixel Breite. In einer Mail
            // sind höchstens 1200 sinnvoll – alles andere kostet nur Ladezeit
            // und läuft in die 3-MB-Grenze. Deshalb wird vorher verkleinert.
            verkleinern(datei.files[0], 1400).then(function (fertig) {
                status.textContent = fertig.verkleinert
                    ? 'Wird hochgeladen (auf ' + fertig.breite + ' Pixel verkleinert) …'
                    : 'Wird hochgeladen …';
                return hochladen(fertig.datei);
            }).then(function (antwort) {
                if (antwort && antwort.ok) {
                    block[feld.k] = antwort.url;
                    url.value = antwort.url;
                    status.textContent = 'Hochgeladen (' + antwort.breite + '×' + antwort.hoehe + ' Pixel).';
                    redrawBlock(block);
                } else {
                    status.textContent = (antwort && antwort.fehler) || 'Upload fehlgeschlagen.';
                }
            }).catch(function () { status.textContent = 'Upload fehlgeschlagen.'; });
        });

        reihe.appendChild(hoch);
        reihe.appendChild(galerie);
        reihe.appendChild(bearbeiten);
        box.appendChild(reihe);
        box.appendChild(datei);
        box.appendChild(status);
        return box;
    }

    /* ------------------------------------------------- Bilder zurechtlegen */

    /** Schickt eine Datei oder einen Blob an den Upload und liefert die Antwort. */
    function hochladen(datei, name) {
        var daten = new FormData();
        daten.append('datei', datei, name || datei.name || 'bild.jpg');
        daten.append('_csrf', CSRF);
        return fetch(UPLOAD, { method: 'POST', body: daten, credentials: 'same-origin' })
            .then(function (r) { return r.json(); });
    }

    /**
     * Verkleinert ein Bild vor dem Hochladen, wenn es breiter ist als nötig.
     * Bleibt das Bild klein genug, geht es unverändert durch – umgerechnet
     * wird nur, was es wirklich braucht.
     */
    function verkleinern(datei, maxBreite) {
        return new Promise(function (fertig) {
            if (!window.FileReader || !datei.type || datei.type.indexOf('image/') !== 0
                || datei.type === 'image/gif') {
                // GIFs können bewegt sein – die würden beim Umzeichnen zum Standbild.
                fertig({ datei: datei, verkleinert: false, breite: 0 });
                return;
            }
            var leser = new FileReader();
            leser.onload = function () {
                var bild = new Image();
                bild.onload = function () {
                    if (bild.naturalWidth <= maxBreite) {
                        fertig({ datei: datei, verkleinert: false, breite: bild.naturalWidth });
                        return;
                    }
                    var breite = maxBreite;
                    var hoehe  = Math.round(bild.naturalHeight * (maxBreite / bild.naturalWidth));
                    var typ    = datei.type === 'image/png' ? 'image/png' : 'image/jpeg';
                    zeichneUndMache(bild, 0, 0, bild.naturalWidth, bild.naturalHeight, breite, hoehe, typ)
                        .then(function (blob) {
                            fertig({ datei: blob || datei, verkleinert: !!blob, breite: breite });
                        });
                };
                bild.onerror = function () { fertig({ datei: datei, verkleinert: false, breite: 0 }); };
                bild.src = leser.result;
            };
            leser.onerror = function () { fertig({ datei: datei, verkleinert: false, breite: 0 }); };
            leser.readAsDataURL(datei);
        });
    }

    /** Schneidet einen Ausschnitt heraus und liefert ihn als Blob. */
    function zeichneUndMache(bild, sx, sy, sw, sh, zielB, zielH, typ) {
        return new Promise(function (fertig) {
            try {
                var flaeche = document.createElement('canvas');
                flaeche.width  = Math.max(1, Math.round(zielB));
                flaeche.height = Math.max(1, Math.round(zielH));
                var stift = flaeche.getContext('2d');
                if (typ === 'image/jpeg') {
                    // JPEG kennt keine Durchsichtigkeit – sonst würde sie schwarz.
                    stift.fillStyle = '#FFFFFF';
                    stift.fillRect(0, 0, flaeche.width, flaeche.height);
                }
                stift.imageSmoothingQuality = 'high';
                stift.drawImage(bild, sx, sy, sw, sh, 0, 0, flaeche.width, flaeche.height);
                flaeche.toBlob(function (blob) { fertig(blob); }, typ, 0.86);
            } catch (e) {
                fertig(null);   // z. B. Bild von einem fremden Server
            }
        });
    }

    /**
     * Bild zuschneiden und verkleinern – ohne Bildbearbeitungsprogramm.
     *
     * Der Ausschnitt wird auf dem Bild aufgezogen, das Ergebnis landet als
     * neue Datei im Bildordner. Das Ausgangsbild bleibt unangetastet: So
     * lässt sich ein Schnitt jederzeit verwerfen, und andere Newsletter,
     * die dasselbe Bild verwenden, ändern sich nicht mit.
     *
     * @param string   quelle Adresse des Bildes
     * @param function fertig bekommt (neueUrl, breite, hoehe)
     */
    function bildEditor(quelle, fertig, status) {
        var hof = document.createElement('div');
        hof.className = 'bk-modal';
        var kasten = document.createElement('div');
        kasten.className = 'bk-modal-box bk-schnitt-box';
        hof.appendChild(kasten);

        var titel = document.createElement('h3');
        titel.textContent = 'Bild zuschneiden';
        kasten.appendChild(titel);

        var buehne = document.createElement('div');
        buehne.className = 'bk-schnitt-buehne';
        var bild = new Image();
        var rahmen = document.createElement('div');
        rahmen.className = 'bk-schnitt-rahmen';
        ['no', 'nw', 'so', 'sw'].forEach(function (ecke) {
            var griff = document.createElement('span');
            griff.className = 'bk-schnitt-griff bk-griff-' + ecke;
            griff.setAttribute('data-ecke', ecke);
            rahmen.appendChild(griff);
        });
        buehne.appendChild(bild);
        buehne.appendChild(rahmen);
        kasten.appendChild(buehne);

        var meldung = document.createElement('p');
        meldung.className = 'bk-hint';
        meldung.textContent = 'Wird geladen …';
        kasten.appendChild(meldung);

        /* Seitenverhältnis und Zielbreite */
        var leiste = document.createElement('div');
        leiste.className = 'bk-schnitt-leiste';

        var verhaeltnis = 0;    // 0 = frei
        [['Frei', 0], ['1:1', 1], ['4:3', 4 / 3], ['3:2', 3 / 2], ['16:9', 16 / 9]].forEach(function (paar) {
            var k = document.createElement('button');
            k.type = 'button';
            k.className = 'bk-schnitt-wahl' + (paar[1] === 0 ? ' is-aktiv' : '');
            k.textContent = paar[0];
            k.addEventListener('click', function () {
                verhaeltnis = paar[1];
                leiste.querySelectorAll('.bk-schnitt-wahl').forEach(function (x) {
                    x.classList.toggle('is-aktiv', x === k);
                });
                if (verhaeltnis > 0) { anpassen(); }
            });
            leiste.appendChild(k);
        });

        var breiteWahl = document.createElement('select');
        breiteWahl.className = 'bk-select bk-schnitt-breite';
        [['Volle Größe', 0], ['1200 px', 1200], ['800 px', 800], ['600 px', 600], ['400 px', 400]]
            .forEach(function (paar) {
                var o = document.createElement('option');
                o.value = String(paar[1]);
                o.textContent = paar[0];
                breiteWahl.appendChild(o);
            });
        leiste.appendChild(breiteWahl);
        kasten.appendChild(leiste);

        /* Knöpfe */
        var knoepfe = document.createElement('div');
        knoepfe.className = 'bk-ki-actions';
        var uebernehmen = document.createElement('button');
        uebernehmen.type = 'button';
        uebernehmen.className = 'ad-btn';
        uebernehmen.textContent = 'Zuschneiden und übernehmen';
        uebernehmen.disabled = true;
        var ganz = document.createElement('button');
        ganz.type = 'button';
        ganz.className = 'ad-btn ad-btn-secondary';
        ganz.textContent = 'Ganzes Bild';
        var zu = document.createElement('button');
        zu.type = 'button';
        zu.className = 'ad-btn ad-btn-secondary';
        zu.textContent = 'Abbrechen';
        knoepfe.appendChild(uebernehmen);
        knoepfe.appendChild(ganz);
        knoepfe.appendChild(zu);
        kasten.appendChild(knoepfe);

        function schliessen() { if (hof.parentNode) { hof.parentNode.removeChild(hof); } }
        zu.addEventListener('click', schliessen);
        hof.addEventListener('click', function (e) { if (e.target === hof) { schliessen(); } });

        /* --- Auswahlrechteck, gerechnet in Anzeigepixeln --- */
        var aus = { x: 0, y: 0, b: 0, h: 0 };

        function zeichnen() {
            rahmen.style.left   = aus.x + 'px';
            rahmen.style.top    = aus.y + 'px';
            rahmen.style.width  = aus.b + 'px';
            rahmen.style.height = aus.h + 'px';
            var faktor = bild.naturalWidth / bild.clientWidth;
            var zielB  = Math.round(aus.b * faktor);
            var zielH  = Math.round(aus.h * faktor);
            var gewuenscht = parseInt(breiteWahl.value, 10) || 0;
            if (gewuenscht > 0 && gewuenscht < zielB) {
                zielH = Math.round(zielH * (gewuenscht / zielB));
                zielB = gewuenscht;
            }
            meldung.textContent = 'Ausschnitt: ' + zielB + ' × ' + zielH + ' Pixel'
                + ' (Vorlage: ' + bild.naturalWidth + ' × ' + bild.naturalHeight + ')';
        }

        function begrenzen() {
            var maxB = bild.clientWidth, maxH = bild.clientHeight;
            aus.b = Math.max(20, Math.min(aus.b, maxB));
            aus.h = Math.max(20, Math.min(aus.h, maxH));
            aus.x = Math.max(0, Math.min(aus.x, maxB - aus.b));
            aus.y = Math.max(0, Math.min(aus.y, maxH - aus.h));
        }

        /**
         * Hält das gewählte Seitenverhältnis ein.
         *
         * Passt die errechnete Höhe nicht mehr aufs Bild, wird die Breite
         * zurückgenommen – sonst würde die Höhe beim Begrenzen gekappt und
         * aus einem Quadrat still und leise ein Rechteck.
         */
        function verhaeltnisWahren() {
            if (verhaeltnis <= 0) { return; }
            var maxB = bild.clientWidth, maxH = bild.clientHeight;
            var b = Math.min(Math.max(20, aus.b), maxB);
            var h = b / verhaeltnis;
            if (h > maxH) { h = maxH; b = h * verhaeltnis; }
            aus.b = b;
            aus.h = h;
        }

        function anpassen() {
            verhaeltnisWahren();
            begrenzen();
            zeichnen();
        }

        breiteWahl.addEventListener('change', zeichnen);

        ganz.addEventListener('click', function () {
            aus = { x: 0, y: 0, b: bild.clientWidth, h: bild.clientHeight };
            verhaeltnis = 0;
            leiste.querySelectorAll('.bk-schnitt-wahl').forEach(function (x, i) {
                x.classList.toggle('is-aktiv', i === 0);
            });
            zeichnen();
        });

        /* Ziehen: innen verschieben, an den Ecken aufziehen */
        buehne.addEventListener('pointerdown', function (event) {
            if (!bild.clientWidth) { return; }
            event.preventDefault();
            var kachel = buehne.getBoundingClientRect();
            var startX = event.clientX - kachel.left;
            var startY = event.clientY - kachel.top;
            var ecke   = event.target.getAttribute && event.target.getAttribute('data-ecke');
            var innen  = event.target === rahmen;
            var start  = { x: aus.x, y: aus.y, b: aus.b, h: aus.h };

            if (!ecke && !innen) {
                // Auf freier Fläche: neues Rechteck aufziehen
                aus = { x: startX, y: startY, b: 0, h: 0 };
                start = { x: startX, y: startY, b: 0, h: 0 };
                ecke = 'so';
            }

            function bewegen(e) {
                var dx = (e.clientX - kachel.left) - startX;
                var dy = (e.clientY - kachel.top) - startY;

                if (innen) {
                    aus.x = start.x + dx;
                    aus.y = start.y + dy;
                } else {
                    if (ecke.indexOf('o') !== -1 && ecke !== 'no' && ecke !== 'so') { /* egal */ }
                    // Ecken: n = oben, s = unten, w = links, o = rechts
                    if (ecke === 'so') { aus.b = start.b + dx; aus.h = start.h + dy; }
                    if (ecke === 'no') { aus.b = start.b + dx; aus.h = start.h - dy; aus.y = start.y + dy; }
                    if (ecke === 'sw') { aus.b = start.b - dx; aus.h = start.h + dy; aus.x = start.x + dx; }
                    if (ecke === 'nw') { aus.b = start.b - dx; aus.h = start.h - dy;
                                         aus.x = start.x + dx; aus.y = start.y + dy; }
                    if (aus.b < 20) { aus.b = 20; }
                    if (aus.h < 20) { aus.h = 20; }
                    verhaeltnisWahren();
                }
                begrenzen();
                zeichnen();
            }

            function loslassen() {
                window.removeEventListener('pointermove', bewegen);
                window.removeEventListener('pointerup', loslassen);
            }
            window.addEventListener('pointermove', bewegen);
            window.addEventListener('pointerup', loslassen);
        });

        uebernehmen.addEventListener('click', function () {
            var faktor = bild.naturalWidth / bild.clientWidth;
            var sx = Math.round(aus.x * faktor);
            var sy = Math.round(aus.y * faktor);
            var sw = Math.round(aus.b * faktor);
            var sh = Math.round(aus.h * faktor);

            var zielB = sw, zielH = sh;
            var gewuenscht = parseInt(breiteWahl.value, 10) || 0;
            if (gewuenscht > 0 && gewuenscht < zielB) {
                zielH = Math.round(zielH * (gewuenscht / zielB));
                zielB = gewuenscht;
            }

            uebernehmen.disabled = true;
            meldung.textContent = 'Wird zugeschnitten …';
            var typ = /\.png($|\?)/i.test(quelle) ? 'image/png' : 'image/jpeg';

            zeichneUndMache(bild, sx, sy, sw, sh, zielB, zielH, typ).then(function (blob) {
                if (!blob) {
                    meldung.textContent = 'Dieses Bild lässt sich nicht zuschneiden. '
                        + 'Bei Bildern von fremden Servern verbietet der Browser das – '
                        + 'laden Sie es zuerst hoch.';
                    uebernehmen.disabled = false;
                    return null;
                }
                return hochladen(blob, 'ausschnitt.' + (typ === 'image/png' ? 'png' : 'jpg'));
            }).then(function (antwort) {
                if (!antwort) { return; }
                if (!antwort.ok) {
                    meldung.textContent = antwort.fehler || 'Speichern fehlgeschlagen.';
                    uebernehmen.disabled = false;
                    return;
                }
                fertig(antwort.url, antwort.breite, antwort.hoehe);
                schliessen();
            }).catch(function () {
                meldung.textContent = 'Speichern fehlgeschlagen.';
                uebernehmen.disabled = false;
            });
        });

        bild.onload = function () {
            // Mit etwas Verzug, damit clientWidth schon steht
            window.setTimeout(function () {
                aus = { x: 0, y: 0, b: bild.clientWidth, h: bild.clientHeight };
                uebernehmen.disabled = false;
                zeichnen();
            }, 30);
        };
        bild.onerror = function () {
            meldung.textContent = 'Das Bild ließ sich nicht laden.';
        };
        bild.alt = '';
        bild.className = 'bk-schnitt-bild';
        bild.src = quelle;

        document.body.appendChild(hof);
        if (status) { status.textContent = ''; }
    }

    function openGallery(block, feld, urlInput) {
        var overlay = document.createElement('div');
        overlay.className = 'bk-modal';
        overlay.innerHTML = '<div class="bk-modal-box"><h3>Hochgeladene Bilder</h3>'
            + '<div class="bk-gallery">Wird geladen …</div>'
            + '<div class="bk-actions"><button type="button" class="bk-btn bk-btn-light" data-close>Schließen</button></div></div>';
        document.body.appendChild(overlay);

        overlay.addEventListener('click', function (event) {
            if (event.target === overlay || event.target.hasAttribute('data-close')) {
                document.body.removeChild(overlay);
            }
        });

        fetch(UPLOAD + '?liste=1', { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (antwort) {
                var raster = overlay.querySelector('.bk-gallery');
                raster.innerHTML = '';
                if (!antwort.bilder || antwort.bilder.length === 0) {
                    raster.innerHTML = '<p class="bk-hint">Noch keine Bilder hochgeladen.</p>';
                    return;
                }
                antwort.bilder.forEach(function (bild) {
                    var kachel = document.createElement('button');
                    kachel.type = 'button';
                    kachel.className = 'bk-tile';
                    kachel.innerHTML = '<img src="' + esc(bild.url) + '" alt=""><span>' + esc(bild.name) + '</span>';
                    kachel.addEventListener('click', function () {
                        block[feld.k] = bild.url;
                        urlInput.value = bild.url;
                        redrawBlock(block);
                        document.body.removeChild(overlay);
                    });
                    raster.appendChild(kachel);
                });
            })
            .catch(function () {
                overlay.querySelector('.bk-gallery').textContent = 'Die Liste konnte nicht geladen werden.';
            });
    }

    /* ------------------------------------------------------------ Linkliste */

    function linkListField(block, feld) {
        var box = document.createElement('div');
        block[feld.k] = block[feld.k] || [];

        block[feld.k].forEach(function (link, i) {
            var reihe = document.createElement('div');
            reihe.className = 'bk-linkrow';

            var name = document.createElement('input');
            name.className = 'bk-input';
            name.value = link.label || '';
            name.placeholder = 'Beschriftung';
            name.addEventListener('input', function () { link.label = name.value; redrawBlock(block); });

            var ziel = document.createElement('input');
            ziel.className = 'bk-input';
            ziel.value = link.href || '';
            ziel.placeholder = 'https://…';
            ziel.addEventListener('input', function () { link.href = ziel.value; save(); });

            var weg = document.createElement('button');
            weg.type = 'button';
            weg.className = 'bk-icon bk-icon-danger';
            weg.textContent = '✕';
            weg.addEventListener('click', function () {
                block[feld.k].splice(i, 1);
                render();
            });

            reihe.appendChild(name);
            reihe.appendChild(ziel);
            reihe.appendChild(weg);
            box.appendChild(reihe);
        });

        var plus = document.createElement('button');
        plus.type = 'button';
        plus.className = 'bk-btn bk-btn-light';
        plus.textContent = 'Link hinzufügen';
        plus.addEventListener('click', function () {
            block[feld.k].push({ label: 'Neuer Link', href: '' });
            render();
        });
        box.appendChild(plus);
        return box;
    }

    /* ----------------------------------------------------- Grundeinstellungen */

    var offeneGruppen = {};

    function metaPanel() {
        var karte = document.createElement('div');
        karte.className = 'bk-panel';
        karte.innerHTML = '<h3>Gestaltung</h3>';

        var felder = [
            { k: 'font', t: 'font', l: 'Schriftart' },
            { k: 'textColor', t: 'color', l: 'Textfarbe' },
            { k: 'linkColor', t: 'color', l: 'Linkfarbe' }
        ];
        if (MODE === 'template') {
            felder = felder.concat([
                { gruppe: 'Seite und Maße' },
                { k: 'headFont', t: 'font', l: 'Schrift für Überschriften', leer: '— wie Fließtext —' },
                { k: 'bg', t: 'color', l: 'Seitenhintergrund' },
                { k: 'cardBg', t: 'color', l: 'Inhaltsfläche' },
                { k: 'borderColor', t: 'color', l: 'Rahmenfarbe' },
                { k: 'width', t: 'number', l: 'Breite (px)', min: 320, max: 900 },
                { k: 'padding', t: 'number', l: 'Innenabstand (px)', min: 0, max: 60 },
                { k: 'radius', t: 'number', l: 'Ecken abrunden (px)', min: 0, max: 30 },

                { gruppe: 'Kopfzeile' },
                { k: 'showHeader', t: 'toggle', l: 'Kopfzeile anzeigen' },
                { k: 'headerStyle', t: 'select', l: 'Art der Kopfzeile',
                  o: { logo: 'Logo-Quadrat mit Kürzel', wortmarke: 'Wortmarke als Text' } },
                { k: 'headerBg', t: 'color', l: 'Farbe der Kopfzeile' },
                { k: 'headerText', t: 'color', l: 'Schrift in der Kopfzeile' }
            ]);
            if (state.meta.headerStyle === 'wortmarke') {
                felder = felder.concat([
                    { k: 'wordmark', t: 'text', l: 'Wortmarke (leer = Name der Marke)' },
                    { k: 'wordmarkAccent', t: 'text', l: 'Hervorgehobener Teil (z. B. „54")' },
                    { k: 'accentColor', t: 'color', l: 'Farbe des hervorgehobenen Teils' }
                ]);
            } else {
                felder.push({ k: 'logoText', t: 'text', l: 'Kürzel im Logo (max. 3 Zeichen)' });
            }
            felder = felder.concat([
                { k: 'claim', t: 'text', l: 'Claim rechts oben (optional)' },

                { gruppe: 'Footer' },
                { k: 'showFooter', t: 'toggle', l: 'Footer mit Pflichtangaben anzeigen' },
                { k: 'footerBg', t: 'color', l: 'Farbe des Footers (leer = Seitenhintergrund)' },
                { k: 'footerText', t: 'color', l: 'Schrift im Footer' },
                { k: 'note', t: 'area', l: 'Hinweis über dem Impressum (z. B. Partnerlinks)' }
            ]);
        }

        // Kopfzeile und Footer stecken in aufklappbaren Abschnitten – sonst
        // wird die Leiste so lang, dass die Baustein-Einstellungen untergehen.
        var ziel = karte;
        felder.forEach(function (feld) {
            if (feld.gruppe) {
                var kasten = document.createElement('details');
                kasten.className = 'bk-group-box';
                kasten.open = offeneGruppen[feld.gruppe] === true;
                var titel = document.createElement('summary');
                titel.className = 'bk-group';
                titel.textContent = feld.gruppe;
                kasten.appendChild(titel);
                kasten.addEventListener('toggle', function () {
                    offeneGruppen[feld.gruppe] = kasten.open;
                });
                karte.appendChild(kasten);
                ziel = kasten;
                return;
            }
            ziel.appendChild(metaRow(feld));
        });

        if (MODE === 'template') {
            var hinweisBox = document.createElement('p');
            hinweisBox.className = 'bk-hint';
            hinweisBox.textContent = 'Abmeldelink und Impressum stehen im Footer und sind gesetzlich vorgeschrieben. '
                + 'Ohne gestalteten Footer werden beide trotzdem angehängt.';
            karte.appendChild(hinweisBox);
        }
        return karte;
    }

    function metaRow(feld) {
        var zeile = document.createElement('div');
        zeile.className = 'bk-field';
        var label = document.createElement('label');
        label.textContent = feld.l;

        if (feld.t === 'toggle') {
            var box = document.createElement('label');
            box.className = 'bk-check';
            var haken = document.createElement('input');
            haken.type = 'checkbox';
            haken.checked = !!Number(state.meta[feld.k]);
            haken.addEventListener('change', function () {
                state.meta[feld.k] = haken.checked ? 1 : 0;
                save();
            });
            box.appendChild(haken);
            box.appendChild(document.createTextNode(' ' + feld.l));
            zeile.appendChild(box);
            return zeile;
        }

        zeile.appendChild(label);

        if (feld.t === 'font' || feld.t === 'select') {
            var auswahl = document.createElement('select');
            auswahl.className = 'bk-input';
            var optionen = feld.t === 'font' ? (window.NL_FONTS || {}) : feld.o;
            if (feld.t === 'font' && feld.leer) {
                var leerOption = document.createElement('option');
                leerOption.value = '';
                leerOption.textContent = feld.leer;
                leerOption.selected = !state.meta[feld.k];
                auswahl.appendChild(leerOption);
            }
            Object.keys(optionen).forEach(function (wert) {
                var option = document.createElement('option');
                option.value = wert;
                option.textContent = optionen[wert];
                option.selected = String(state.meta[feld.k]) === wert;
                auswahl.appendChild(option);
            });
            auswahl.addEventListener('change', function () {
                state.meta[feld.k] = auswahl.value;
                render();
            });
            zeile.appendChild(auswahl);
            return zeile;
        }

        if (feld.t === 'area') {
            var flaeche = document.createElement('textarea');
            flaeche.className = 'bk-input';
            flaeche.rows = 3;
            flaeche.value = state.meta[feld.k] || '';
            flaeche.addEventListener('input', function () {
                state.meta[feld.k] = flaeche.value;
                save();
            });
            flaeche.addEventListener('focus', function () { lastFocus = flaeche; });
            zeile.appendChild(flaeche);
            return zeile;
        }

        if (feld.t === 'color') {
            var wrap = document.createElement('div');
            wrap.className = 'bk-colorrow';
            var farbe = document.createElement('input');
            farbe.type = 'color';
            farbe.value = /^#[0-9a-f]{6}$/i.test(state.meta[feld.k] || '') ? state.meta[feld.k] : '#ffffff';
            var text = document.createElement('input');
            text.type = 'text';
            text.className = 'bk-input';
            text.value = state.meta[feld.k] || '';
            farbe.addEventListener('input', function () {
                state.meta[feld.k] = farbe.value;
                text.value = farbe.value;
                save();
            });
            text.addEventListener('change', function () {
                state.meta[feld.k] = text.value.trim();
                save();
            });
            wrap.appendChild(farbe);
            wrap.appendChild(text);
            zeile.appendChild(wrap);
            return zeile;
        }

        var input = document.createElement('input');
        input.className = 'bk-input';
        input.type = feld.t === 'number' ? 'number' : 'text';
        if (feld.min !== undefined) { input.min = feld.min; }
        if (feld.max !== undefined) { input.max = feld.max; }
        input.value = state.meta[feld.k] !== undefined ? state.meta[feld.k] : '';
        input.addEventListener('input', function () {
            state.meta[feld.k] = feld.t === 'number' ? parseInt(input.value, 10) || 0 : input.value;
            save();
        });
        input.addEventListener('focus', function () { lastFocus = input; });
        zeile.appendChild(input);
        return zeile;
    }

    /* ------------------------------------------------ Eigene Bausteine
     *
     * Was einmal gebaut ist – ein Absender-Gruß, ein Produktkasten – lässt
     * sich unter einem Namen sichern und in jedem Newsletter wieder
     * einsetzen. Gespeichert wird auf dem Server, damit es allen im Team
     * zur Verfügung steht und einen Rechnerwechsel übersteht.
     */

    function bausteinSichern(block) {
        var vorschlag = window.NL_BLOCK_LABELS[block.type] || 'Baustein';
        var name = window.prompt('Unter welchem Namen soll der Baustein gesichert werden?', vorschlag);
        if (name === null || name.trim() === '') { return; }

        var daten = new FormData();
        daten.set('_csrf', CSRF);
        daten.set('name', name.trim());
        daten.set('sichern', JSON.stringify(block));

        fetch('bausteine.php', { method: 'POST', body: daten, credentials: 'same-origin' })
            .then(function (a) { return a.json(); })
            .then(function (antwort) {
                if (!antwort || !antwort.ok) {
                    throw new Error((antwort && antwort.fehler) || 'Sichern fehlgeschlagen.');
                }
                zeigeBausteine(antwort.bausteine);
                hinweis('Baustein „' + name.trim() + '" gesichert – links unter „Eigene Bausteine".');
            })
            .catch(function (fehler) { hinweis(String(fehler.message || fehler)); });
    }

    function bausteinEinsetzen(id, name) {
        var daten = new FormData();
        daten.set('_csrf', CSRF);
        daten.set('einsetzen', String(id));

        fetch('bausteine.php', { method: 'POST', body: daten, credentials: 'same-origin' })
            .then(function (a) { return a.json(); })
            .then(function (antwort) {
                if (!antwort || !antwort.ok || !antwort.blocks || !antwort.blocks.length) {
                    throw new Error((antwort && antwort.fehler) || 'Einsetzen fehlgeschlagen.');
                }
                antwort.blocks.forEach(function (b) { state.blocks.push(b); });
                selected = antwort.blocks[antwort.blocks.length - 1].id;
                render();
                var karte = canvas.querySelector('.bk-block[data-id="' + selected + '"]');
                if (karte) { karte.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
                hinweis('„' + name + '" eingesetzt.');
            })
            .catch(function (fehler) { hinweis(String(fehler.message || fehler)); });
    }

    function bausteinLoeschen(id, name, danach) {
        if (!window.confirm('Den gesicherten Baustein „' + name + '" löschen? '
            + 'Bereits eingesetzte Bausteine bleiben, wo sie sind.')) { return; }
        var daten = new FormData();
        daten.set('_csrf', CSRF);
        daten.set('loeschen', String(id));
        fetch('bausteine.php', { method: 'POST', body: daten, credentials: 'same-origin' })
            .then(function (a) { return a.json(); })
            .then(function (antwort) {
                if (antwort && antwort.ok) { danach(antwort.bausteine); }
            });
    }

    /** Zeichnet die Liste der gesicherten Bausteine in der linken Leiste. */
    function zeigeBausteine(liste) {
        var kasten = root.querySelector('[data-eigene]');
        if (!kasten) { return; }
        kasten.innerHTML = '';

        if (!liste || liste.length === 0) {
            var leer = document.createElement('p');
            leer.className = 'bk-hint';
            leer.style.margin = '0';
            leer.textContent = 'Noch keine. Mit dem Stern ☆ am Baustein sichern Sie einen – '
                + 'er steht dann in jedem Newsletter zur Verfügung.';
            kasten.appendChild(leer);
            return;
        }

        liste.forEach(function (eintrag) {
            var zeile = document.createElement('div');
            zeile.className = 'bk-eigen';

            var nehmen = document.createElement('button');
            nehmen.type = 'button';
            nehmen.className = 'bk-eigen-name';
            nehmen.textContent = eintrag.name;
            nehmen.title = 'Einsetzen';
            nehmen.addEventListener('click', function () {
                bausteinEinsetzen(eintrag.id, eintrag.name);
            });

            var weg = document.createElement('button');
            weg.type = 'button';
            weg.className = 'bk-eigen-weg';
            weg.textContent = '✕';
            weg.title = 'Gesicherten Baustein löschen';
            weg.addEventListener('click', function (event) {
                event.stopPropagation();
                bausteinLoeschen(eintrag.id, eintrag.name, zeigeBausteine);
            });

            zeile.appendChild(nehmen);
            zeile.appendChild(weg);
            kasten.appendChild(zeile);
        });
    }

    if (root.querySelector('[data-eigene]')) {
        fetch('bausteine.php', { credentials: 'same-origin' })
            .then(function (a) { return a.json(); })
            .then(function (antwort) { zeigeBausteine(antwort && antwort.bausteine); })
            .catch(function () { /* dann bleibt die Liste eben leer */ });
    }

    /* ------------------------------------- Vorschau: Rechner oder Handy
     *
     * Der Rahmen wird schmal gestellt, statt das Gerät nachzubauen: Die Mail
     * im Rahmen greift dann von selbst auf ihre Handy-Regeln zurück
     * (@media max-width). Genau das passiert später im Postfach auch.
     */
    (function () {
        var wahl = document.querySelector('[data-geraetewahl]');
        var buehne = document.querySelector('[data-vorschau-buehne]');
        if (!wahl || !buehne) { return; }
        var anzeige = wahl.querySelector('[data-geraet-breite]');

        function stellen(art) {
            buehne.classList.toggle('ist-handy', art === 'handy');
            wahl.querySelectorAll('[data-geraet]').forEach(function (k) {
                k.classList.toggle('is-aktiv', k.getAttribute('data-geraet') === art);
            });
            if (anzeige) {
                anzeige.textContent = art === 'handy' ? '375 px breit' : '';
            }
            try { window.localStorage.setItem('nl_vorschau_geraet', art); } catch (e) { /* egal */ }
        }

        wahl.querySelectorAll('[data-geraet]').forEach(function (knopf) {
            knopf.addEventListener('click', function () {
                stellen(knopf.getAttribute('data-geraet'));
            });
        });

        // Die Wahl bleibt über Seitenwechsel hinweg bestehen
        var gemerkt = null;
        try { gemerkt = window.localStorage.getItem('nl_vorschau_geraet'); } catch (e) { /* egal */ }
        if (gemerkt === 'handy') { stellen('handy'); }
    })();

    /* ------------------------------------------------------------- Palette */

    root.querySelectorAll('[data-add]').forEach(function (chip) {
        var typ = chip.getAttribute('data-add');
        chip.removeAttribute('draggable');
        ziehbar(chip, function () {
            return { art: 'neu', wert: typ, name: window.NL_BLOCK_LABELS[typ] || typ };
        });
        chip.addEventListener('click', function () {
            var block = makeBlock(typ);
            state.blocks.push(block);
            selected = block.id;
            render();
            var karte = canvas.querySelector('.bk-block[data-id="' + block.id + '"]');
            if (karte) { karte.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
        });
    });



    /* ------------------------------------------------- Knöpfe für den Assistenten */

    var kiKnopf = root.querySelector('[data-ki-open]');
    if (kiKnopf) {
        kiKnopf.addEventListener('click', function () {
            kiOeffnen(zielFeld());
        });
    }

    /*
     * Der Betreff steht außerhalb des Baukastens, gehört aber zur selben
     * Seite. Sein Knopf bekommt den ganzen Newsletter als Grundlage.
     */
    document.querySelectorAll('[data-ki-betreff]').forEach(function (knopf) {
        if (!KI) { knopf.hidden = true; return; }
        knopf.addEventListener('click', function () {
            var ziel = document.getElementById(knopf.getAttribute('data-ki-betreff'));
            kiOeffnen(ziel, 'betreff');
        });
    });

    /* --------------------------------------------------------- Platzhalter */

    var picker = root.querySelector('[data-placeholder-picker]');
    if (picker) {
        picker.addEventListener('change', function () {
            var wert = picker.value;
            picker.value = '';
            if (!wert) { return; }
            var ziel = zielFeld();
            if (ziel && ziel.isContentEditable) {
                einsetzenInText(ziel, wert);
            } else if (ziel && 'value' in ziel) {
                var start = ziel.selectionStart !== null && ziel.selectionStart !== undefined
                    ? ziel.selectionStart : ziel.value.length;
                ziel.value = ziel.value.slice(0, start) + wert + ziel.value.slice(start);
                ziel.dispatchEvent(new Event('input', { bubbles: true }));
                ziel.focus();
            } else {
                hinweis('Bitte wählen Sie zuerst einen Baustein oder klicken Sie in ein Textfeld.');
            }
        });
    }

    /**
     * Ermittelt das Feld, in das ein Platzhalter soll.
     * Mehrere Wege, damit es auch klappt, wenn der Fokus verloren ging:
     * zuletzt bearbeitetes Feld → gerade fokussiertes Feld → Text des
     * ausgewählten Bausteins.
     */
    function zielFeld() {
        if (lastFocus && document.contains(lastFocus)) {
            return lastFocus;
        }
        var aktiv = document.activeElement;
        if (aktiv && root.contains(aktiv) && (aktiv.isContentEditable || 'value' in aktiv)) {
            return aktiv;
        }
        if (selected) {
            var karte = canvas.querySelector('.bk-block[data-id="' + selected + '"]');
            var reich = karte ? karte.querySelector('.bk-rich') : null;
            if (reich) { return reich; }
        }
        return null;
    }

    /** Merkt sich, wo im Textfeld die Schreibmarke steht. */
    function merkeSchreibmarke(editor) {
        var auswahl = window.getSelection();
        if (auswahl && auswahl.rangeCount > 0 && editor.contains(auswahl.anchorNode)) {
            lastRange = auswahl.getRangeAt(0).cloneRange();
        }
    }

    /** Setzt Text an der zuletzt bekannten Stelle im Textfeld ein. */
    function einsetzenInText(editor, text) {
        editor.focus();
        var auswahl = window.getSelection();
        var bereich = (lastRange && editor.contains(lastRange.commonAncestorContainer)) ? lastRange : null;
        if (!bereich) {
            // Ohne bekannte Position ans Ende hängen
            bereich = document.createRange();
            bereich.selectNodeContents(editor);
            bereich.collapse(false);
        }
        bereich.deleteContents();
        var knoten = document.createTextNode(text);
        bereich.insertNode(knoten);
        bereich.setStartAfter(knoten);
        bereich.collapse(true);
        auswahl.removeAllRanges();
        auswahl.addRange(bereich);
        lastRange = bereich.cloneRange();
        editor.dispatchEvent(new Event('input', { bubbles: true }));
    }

    function hinweis(text) {
        var box = document.createElement('div');
        box.className = 'bk-toast';
        box.textContent = text;
        document.body.appendChild(box);
        window.setTimeout(function () {
            if (box.parentNode) { box.parentNode.removeChild(box); }
        }, 3500);
    }

    /* Vor dem Absenden noch einmal sichern (z. B. nach Textänderungen). */
    var form = root.closest('form');
    if (form) {
        form.addEventListener('submit', function () { save(); });

        /*
         * Auch Betreff, Absender, Liste und die anderen Felder daneben
         * speichern von selbst: Die Hintergrundspeicherung schickt ohnehin
         * das ganze Formular. Vorher war nur der Baukasten selbst gesichert –
         * wer den Betreff tippte und wegging, verlor ihn.
         *
         * Die Felder im Baukasten selbst lösen bereits save() aus und
         * brauchen hier nichts.
         */
        ['input', 'change'].forEach(function (art) {
            form.addEventListener(art, function (ereignis) {
                if (ereignis.target === field || root.contains(ereignis.target)) { return; }
                autoSpeichern();
            });
        });
    }

    render();
    ersterAufbau = false;
})();
