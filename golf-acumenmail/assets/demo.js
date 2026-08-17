/**
 * Taktgeber für die bewegten Demos auf der Startseite.
 *
 * Jede Demo ist eine nachgebaute Oberfläche im HTML. Dieses Skript schaltet
 * sie Schritt für Schritt weiter: Zu jedem Schritt gehört eine Liste von
 * Elementen, die eine Zustandsklasse bekommen (`is-in`, `is-active` …), sowie
 * ein Untertitel. Das Aussehen der Zustände steht vollständig in demo.css.
 *
 * Drei Eigenschaften waren dabei wichtig:
 *
 * - Jeder Schritt beschreibt den **vollständigen** Zustand, nicht die Änderung.
 *   Vor dem Setzen werden alle Zustandsklassen entfernt. So kann sich nichts
 *   aufaddieren, und das Zurückspringen an den Anfang ist derselbe Vorgang wie
 *   jeder andere Schritt.
 * - Läuft kein JavaScript, bleibt `is-static` stehen und die Demo zeigt das
 *   fertige Ergebnis statt einer leeren Fläche.
 * - Wer Bewegung abbestellt hat (prefers-reduced-motion) oder gerade woanders
 *   auf der Seite ist, bekommt keine Schleife im Hintergrund.
 */
(function () {
    'use strict';

    /* Alle Klassen, die dieses Skript verwaltet. Sie werden vor jedem Schritt
       entfernt – Entfernen und Neusetzen im selben Durchlauf löst keinen
       Übergang aus, sichtbar bleibt also nur, was sich wirklich ändert. */
    var MANAGED = [
        'is-active', 'is-held', 'is-moving', 'is-dropped', 'is-in', 'is-new',
        'is-visible', 'is-ok', 'is-ready', 'is-armed', 'is-pressed',
        'is-filling', 'is-done', 'is-focus', 'is-picked', 'is-gone'
    ];

    var STEP_MS = 1250;

    /* ------------------------------------------------------------------ *
     * Drehbücher
     * ------------------------------------------------------------------ */

    /**
     * Demo 1: Ein Newsletter entsteht aus Bausteinen.
     * Je Baustein zwei Schritte – aufnehmen, ablegen.
     */
    var BUILDER = [
        {
            caption: 'Die Vorlage des Clubs steht bereits: Kopfzeile, Farben und Footer sind gesetzt und lassen sich nicht verschieben.',
            set: [['.demo-drop-hint', 'is-visible']]
        },
        {
            caption: 'Baustein <b>Überschrift</b> in der Leiste anklicken – oder direkt hinüberziehen.',
            set: [['.pal-1', 'is-active'], ['.ghost-1', 'is-held'], ['.demo-cursor', 'is-visible']],
            cursor: ['66px', '62px']
        },
        {
            caption: 'Loslassen. Die Überschrift sitzt zwischen Kopfzeile und Footer.',
            set: [['.pal-1', 'is-active'], ['.ghost-1', 'is-moving'], ['.blk-1', 'is-in is-new'], ['.demo-cursor', 'is-visible']],
            cursor: ['300px', '128px']
        },
        {
            caption: 'Der nächste Baustein: <b>Textabsatz</b>.',
            set: [['.pal-2', 'is-active'], ['.ghost-2', 'is-held'], ['.blk-1', 'is-in'], ['.demo-cursor', 'is-visible']],
            cursor: ['66px', '104px']
        },
        {
            caption: 'Text direkt im Baustein schreiben. <b>{{vorname}}</b> setzt später den Vornamen des Mitglieds ein.',
            set: [['.pal-2', 'is-active'], ['.ghost-2', 'is-moving'], ['.blk-1', 'is-in'], ['.blk-2', 'is-in is-new'], ['.demo-cursor', 'is-visible']],
            cursor: ['300px', '178px']
        },
        {
            caption: 'Ein <b>Bild</b> vom letzten Turnier – hochladen, zuschneiden, fertig.',
            set: [['.pal-3', 'is-active'], ['.ghost-3', 'is-held'], ['.blk-1', 'is-in'], ['.blk-2', 'is-in'], ['.demo-cursor', 'is-visible']],
            cursor: ['66px', '146px']
        },
        {
            caption: 'Zu große Handyfotos werden beim Hochladen automatisch verkleinert.',
            set: [['.pal-3', 'is-active'], ['.ghost-3', 'is-moving'], ['.blk-1', 'is-in'], ['.blk-2', 'is-in'], ['.blk-3', 'is-in is-new'], ['.demo-cursor', 'is-visible']],
            cursor: ['300px', '232px']
        },
        {
            caption: 'Zum Schluss der <b>Knopf</b> – der Weg zur Turnieranmeldung.',
            set: [['.pal-4', 'is-active'], ['.ghost-4', 'is-held'], ['.blk-1', 'is-in'], ['.blk-2', 'is-in'], ['.blk-3', 'is-in'], ['.demo-cursor', 'is-visible']],
            cursor: ['66px', '188px']
        },
        {
            caption: 'Vier Bausteine, keine Zeile HTML.',
            set: [['.pal-4', 'is-active'], ['.ghost-4', 'is-moving'], ['.blk-1', 'is-in'], ['.blk-2', 'is-in'], ['.blk-3', 'is-in'], ['.blk-4', 'is-in is-new'], ['.demo-cursor', 'is-visible']],
            cursor: ['300px', '296px']
        },
        {
            caption: 'Rechts stehen die Clubfarben. Wer sie ändert, ändert sie überall.',
            set: [['.blk-1', 'is-in'], ['.blk-2', 'is-in'], ['.blk-3', 'is-in'], ['.blk-4', 'is-in'], ['.swatch-2', 'is-picked'], ['.demo-cursor', 'is-visible']],
            cursor: ['calc(100% - 92px)', '150px']
        },
        {
            caption: 'Gespeichert wird von selbst – ohne Knopfdruck, nach jeder Änderung.',
            set: [['.blk-1', 'is-in'], ['.blk-2', 'is-in'], ['.blk-3', 'is-in'], ['.blk-4', 'is-in'], ['.swatch-2', 'is-picked'], ['.demo-save', 'is-visible']]
        },
        {
            caption: 'Das war der ganze Weg zum fertigen Clubnewsletter: rund zwei Minuten.',
            set: [['.blk-1', 'is-in'], ['.blk-2', 'is-in'], ['.blk-3', 'is-in'], ['.blk-4', 'is-in'], ['.swatch-2', 'is-picked'], ['.demo-save', 'is-visible']]
        }
    ];

    /** Demo 2: Inhalt, Angaben, Prüfen & Senden. */
    var STEPS = [
        {
            caption: 'Schritt 1 – <b>Inhalt</b>: die Bausteine von eben.',
            set: [['.tab-1', 'is-active'], ['.pane-1', 'is-active']]
        },
        {
            caption: 'Schritt 2 – <b>Angaben</b>: Betreff, Absender, Empfänger.',
            set: [['.tab-1', 'is-done'], ['.tab-2', 'is-active'], ['.pane-2', 'is-active']]
        },
        {
            caption: 'Betreff schreiben – oder vom Textassistenten Vorschläge holen.',
            set: [['.tab-1', 'is-done'], ['.tab-2', 'is-active'], ['.pane-2', 'is-active'], ['.field-subject', 'is-filling']]
        },
        {
            caption: 'Empfängerliste wählen. Segmente wie „Mitglieder aktiv“ trennen Sie einmal – danach wählen Sie nur noch aus.',
            set: [['.tab-1', 'is-done'], ['.tab-2', 'is-active'], ['.pane-2', 'is-active'], ['.field-subject', 'is-filling'], ['.field-list', 'is-filling']]
        },
        {
            caption: 'Schritt 3 – <b>Prüfen & Senden</b>. Die Ampel zeigt, was noch fehlt.',
            set: [['.tab-1', 'is-done'], ['.tab-2', 'is-done'], ['.tab-3', 'is-active'], ['.pane-3', 'is-active'], ['.chk-1', 'is-ok'], ['.chk-2', 'is-ok']]
        },
        {
            caption: 'Vorschau für Rechner und Handy, dazu eine Testmail an den Vorstand.',
            set: [['.tab-1', 'is-done'], ['.tab-2', 'is-done'], ['.tab-3', 'is-active'], ['.pane-3', 'is-active'], ['.chk-1', 'is-ok'], ['.chk-2', 'is-ok'], ['.chk-3', 'is-ok'], ['.chk-4', 'is-ok'], ['.demo-pill', 'is-ready'], ['.demo-send', 'is-armed']]
        },
        {
            caption: 'Senden – oder auf Freitag, 9 Uhr planen.',
            set: [['.tab-1', 'is-done'], ['.tab-2', 'is-done'], ['.tab-3', 'is-active'], ['.pane-3', 'is-active'], ['.chk-1', 'is-ok'], ['.chk-2', 'is-ok'], ['.chk-3', 'is-ok'], ['.chk-4', 'is-ok'], ['.demo-pill', 'is-ready'], ['.demo-send', 'is-armed is-pressed']]
        },
        {
            caption: 'Der Versand läuft portionsweise über den eigenen Server – ohne Tempolimit eines fremden Anbieters.',
            set: [['.tab-1', 'is-done'], ['.tab-2', 'is-done'], ['.tab-3', 'is-active'], ['.pane-3', 'is-active'], ['.chk-1', 'is-ok'], ['.chk-2', 'is-ok'], ['.chk-3', 'is-ok'], ['.chk-4', 'is-ok'], ['.demo-pill', 'is-ready'], ['.demo-send', 'is-armed'], ['.demo-toast', 'is-visible']]
        }
    ];

    /** Demo 3: Willkommensstrecke für neue Mitglieder. */
    var FLOW = [
        {
            caption: 'Auslöser: Ein neues Mitglied bestätigt seine Anmeldung.',
            set: [['.node-1', 'is-in is-focus']]
        },
        {
            caption: 'Einen Tag Pause – niemand mag zwei Mails in fünf Minuten.',
            set: [['.node-1', 'is-in'], ['.link-1', 'is-in'], ['.node-2', 'is-in is-focus']]
        },
        {
            caption: 'Die Willkommensmail: Platzregeln, Ansprechpartner, Startzeiten buchen.',
            set: [['.node-1', 'is-in'], ['.link-1', 'is-in'], ['.node-2', 'is-in'], ['.link-2', 'is-in'], ['.node-3', 'is-in is-focus']]
        },
        {
            caption: 'Eine Bedingung teilt den Ablauf: Wurde die Mail geöffnet?',
            set: [['.node-1', 'is-in'], ['.link-1', 'is-in'], ['.node-2', 'is-in'], ['.link-2', 'is-in'], ['.node-3', 'is-in'], ['.link-3', 'is-in'], ['.node-4', 'is-in is-focus']]
        },
        {
            caption: 'Ja-Zweig und Nein-Zweig bekommen unterschiedliche Post.',
            set: [['.node-1', 'is-in'], ['.link-1', 'is-in'], ['.node-2', 'is-in'], ['.link-2', 'is-in'], ['.node-3', 'is-in'], ['.link-3', 'is-in'], ['.node-4', 'is-in'], ['.link-4', 'is-in'], ['.node-5', 'is-in is-focus'], ['.node-6', 'is-in is-focus']]
        },
        {
            caption: 'Auf <b>Aktiv</b> stellen – ab jetzt läuft die Strecke für jedes neue Mitglied von allein.',
            set: [['.node-1', 'is-in'], ['.link-1', 'is-in'], ['.node-2', 'is-in'], ['.link-2', 'is-in'], ['.node-3', 'is-in'], ['.link-3', 'is-in'], ['.node-4', 'is-in'], ['.link-4', 'is-in'], ['.node-5', 'is-in'], ['.node-6', 'is-in'], ['.demo-flow-status', 'is-visible']]
        },
        {
            caption: 'Einmal gebaut, arbeitet die Strecke das ganze Jahr – auch im Winter.',
            set: [['.node-1', 'is-in'], ['.link-1', 'is-in'], ['.node-2', 'is-in'], ['.link-2', 'is-in'], ['.node-3', 'is-in'], ['.link-3', 'is-in'], ['.node-4', 'is-in'], ['.link-4', 'is-in'], ['.node-5', 'is-in'], ['.node-6', 'is-in'], ['.demo-flow-status', 'is-visible']]
        }
    ];

    var SCRIPTS = { builder: BUILDER, steps: STEPS, flow: FLOW };

    /* ------------------------------------------------------------------ *
     * Abspielwerk
     * ------------------------------------------------------------------ */

    function Demo(shell, script) {
        this.shell = shell;
        this.script = script;
        this.index = 0;
        this.timer = null;
        this.playing = false;
        this.inView = false;

        this.caption = shell.querySelector('.demo-caption-text');
        this.cursor = shell.querySelector('.demo-cursor');
        this.toggle = shell.querySelector('.demo-toggle');
        this.replay = shell.querySelector('.demo-replay');
        this.progress = shell.querySelector('.demo-progress');

        this.buildProgress();
        this.render(0);
    }

    Demo.prototype.buildProgress = function () {
        if (!this.progress) { return; }
        var html = '';
        for (var i = 0; i < this.script.length; i++) { html += '<i></i>'; }
        this.progress.innerHTML = html;
        this.ticks = this.progress.querySelectorAll('i');
    };

    /** Setzt den Zustand eines Schrittes – vollständig, nicht als Differenz. */
    Demo.prototype.render = function (index) {
        var step = this.script[index];
        if (!step) { return; }

        var all = this.shell.querySelectorAll('.demo-body [class]');
        for (var i = 0; i < all.length; i++) {
            all[i].classList.remove.apply(all[i].classList, MANAGED);
        }

        for (var s = 0; s < step.set.length; s++) {
            var target = this.shell.querySelector(step.set[s][0]);
            if (!target) { continue; }
            var classes = step.set[s][1].split(' ');
            for (var c = 0; c < classes.length; c++) { target.classList.add(classes[c]); }
        }

        if (this.cursor && step.cursor) {
            this.cursor.style.left = step.cursor[0];
            this.cursor.style.top = step.cursor[1];
        }

        if (this.caption) { this.caption.innerHTML = step.caption; }

        if (this.ticks) {
            for (var t = 0; t < this.ticks.length; t++) {
                this.ticks[t].classList.toggle('is-done', t <= index);
            }
        }

        this.shell.setAttribute('data-step', String(index));
        this.index = index;
    };

    Demo.prototype.next = function () {
        this.render((this.index + 1) % this.script.length);
    };

    Demo.prototype.play = function () {
        if (this.playing) { return; }
        this.playing = true;
        this.setToggle(true);
        var self = this;
        this.timer = window.setInterval(function () { self.next(); }, STEP_MS);
    };

    Demo.prototype.pause = function () {
        this.playing = false;
        this.setToggle(false);
        window.clearInterval(this.timer);
        this.timer = null;
    };

    Demo.prototype.setToggle = function (playing) {
        if (!this.toggle) { return; }
        this.toggle.setAttribute('aria-pressed', String(!playing));
        var label = this.toggle.querySelector('.demo-toggle-label');
        if (label) { label.textContent = playing ? 'Pause' : 'Abspielen'; }
        this.toggle.setAttribute('aria-label', playing ? 'Demo anhalten' : 'Demo abspielen');
    };

    /* Wiedergabe nur, solange die Demo auch zu sehen ist. */
    Demo.prototype.setInView = function (visible) {
        this.inView = visible;
        if (visible && this.wanted) { this.play(); } else { this.pause(); }
    };

    /* ------------------------------------------------------------------ */

    function init() {
        var shells = document.querySelectorAll('.demo-shell[data-demo]');
        if (!shells.length) { return; }

        var reduced = window.matchMedia &&
            window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        var observer = null;
        if ('IntersectionObserver' in window) {
            observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    var demo = entry.target.demoInstance;
                    if (demo) { demo.setInView(entry.isIntersecting); }
                });
            }, { threshold: 0.25 });
        }

        for (var i = 0; i < shells.length; i++) {
            (function (shell) {
                var script = SCRIPTS[shell.getAttribute('data-demo')];
                if (!script) { return; }

                /* Ab hier übernimmt das Skript: der statische Endzustand,
                   den Besucher ohne JavaScript sehen, wird abgelegt. */
                shell.classList.remove('is-static');

                var demo = new Demo(shell, script);
                shell.demoInstance = demo;

                /* Bei abbestellter Bewegung steht die Demo still und zeigt den
                   letzten Schritt. Abspielen bleibt als Knopf verfügbar. */
                demo.wanted = !reduced;
                if (reduced) { demo.render(script.length - 1); }

                if (demo.toggle) {
                    demo.setToggle(false);
                    demo.toggle.addEventListener('click', function () {
                        demo.wanted = !demo.playing;
                        if (demo.playing) { demo.pause(); } else { demo.play(); }
                    });
                }

                if (demo.replay) {
                    demo.replay.addEventListener('click', function () {
                        demo.render(0);
                        demo.wanted = true;
                        demo.pause();
                        demo.play();
                    });
                }

                if (observer) {
                    observer.observe(shell);
                } else if (!reduced) {
                    demo.play();
                }
            }(shells[i]));
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
}());
