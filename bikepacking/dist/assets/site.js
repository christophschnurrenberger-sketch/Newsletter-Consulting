/* =========================================================================
   Sattelfest - Interaktion
   Ohne Framework, ohne externe Abhaengigkeiten.
   Konfiguration kommt aus assets/config.js (window.SF).
   ========================================================================= */
(function () {
  'use strict';

  var CFG = window.SF || {};

  var CHECK_SVG =
    '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" ' +
    'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>';

  /** 1250 → "1.250" */
  function de(value) {
    return String(value).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  /* ------------------------------------------------------------------ *
   * Kopfzeile: Schatten beim Scrollen
   * ------------------------------------------------------------------ */
  var header = document.getElementById('site-header');
  if (header) {
    // Tatsaechliche Kopfhoehe bereitstellen: Das Mobilmenue setzt sich
    // darunter, und die Kopfzeile ist je nach Breite ein- oder zweizeilig.
    var syncHeaderHeight = function () {
      document.documentElement.style.setProperty('--header-total', header.offsetHeight + 'px');
    };
    var onScroll = function () {
      header.classList.toggle('is-stuck', window.scrollY > 8);
      syncHeaderHeight();
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', syncHeaderHeight);
    onScroll();
  }

  /* ------------------------------------------------------------------ *
   * Navigation: Mega-Dropdowns + Mobilmenue
   * ------------------------------------------------------------------ */
  var triggers = Array.prototype.slice.call(document.querySelectorAll('.nav-trigger'));
  var isDesktop = function () {
    return window.matchMedia('(min-width: 1081px)').matches;
  };

  function closeAll(except) {
    triggers.forEach(function (trigger) {
      if (trigger === except) return;
      var panel = document.getElementById(trigger.getAttribute('aria-controls'));
      trigger.setAttribute('aria-expanded', 'false');
      if (panel) panel.hidden = true;
    });
  }

  triggers.forEach(function (trigger) {
    var panel = document.getElementById(trigger.getAttribute('aria-controls'));
    if (!panel) return;

    trigger.addEventListener('click', function () {
      var open = trigger.getAttribute('aria-expanded') === 'true';
      closeAll(trigger);
      trigger.setAttribute('aria-expanded', String(!open));
      panel.hidden = open;
    });

    // Auf dem Desktop zusaetzlich per Hover oeffnen.
    var item = trigger.closest('.nav-item');
    if (!item) return;
    var timer;
    item.addEventListener('mouseenter', function () {
      if (!isDesktop()) return;
      clearTimeout(timer);
      closeAll(trigger);
      trigger.setAttribute('aria-expanded', 'true');
      panel.hidden = false;
    });
    item.addEventListener('mouseleave', function () {
      if (!isDesktop()) return;
      timer = setTimeout(function () {
        trigger.setAttribute('aria-expanded', 'false');
        panel.hidden = true;
      }, 140);
    });
  });

  document.addEventListener('click', function (event) {
    if (!event.target.closest('.nav-item')) closeAll(null);
  });

  document.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape') return;
    closeAll(null);
    if (document.body.classList.contains('nav-open')) toggleNav(false);
  });

  var navToggle = document.getElementById('nav-toggle');
  function toggleNav(force) {
    var open = typeof force === 'boolean' ? force : !document.body.classList.contains('nav-open');
    document.body.classList.toggle('nav-open', open);
    if (navToggle) {
      navToggle.setAttribute('aria-expanded', String(open));
      navToggle.setAttribute('aria-label', open ? 'Menü schließen' : 'Menü öffnen');
    }
    if (!open) closeAll(null);
  }
  if (navToggle) navToggle.addEventListener('click', function () { toggleNav(); });

  window.addEventListener('resize', function () {
    if (isDesktop() && document.body.classList.contains('nav-open')) toggleNav(false);
  });

  /* ------------------------------------------------------------------ *
   * Copyright-Jahr
   * ------------------------------------------------------------------ */
  Array.prototype.forEach.call(document.querySelectorAll('[data-year]'), function (el) {
    el.textContent = String(new Date().getFullYear());
  });

  /* ------------------------------------------------------------------ *
   * Newsletter-Formular
   * ------------------------------------------------------------------ */
  var MAIL_RE = /^[^\s@]+@[^\s@]+\.[a-z]{2,}$/i;

  Array.prototype.forEach.call(document.querySelectorAll('[data-newsletter]'), function (form) {
    var status = form.querySelector('[data-status]');
    var mail = form.querySelector('input[type="email"]');
    var mailError = form.querySelector('[data-error]');

    function say(message, kind) {
      if (!status) return;
      status.textContent = message;
      status.className = 'nl-status' + (kind ? ' is-' + kind : '');
    }

    form.addEventListener('submit', function (event) {
      event.preventDefault();

      // Honeypot: von Menschen nie ausgefuellt.
      var trap = form.querySelector('.hp-field');
      if (trap && trap.value) return;

      var valid = mail && MAIL_RE.test(mail.value.trim());
      if (mailError) mailError.hidden = valid;
      if (mail) mail.setAttribute('aria-invalid', String(!valid));
      if (!valid) {
        say('Bitte prüfe deine E-Mail-Adresse.', 'err');
        if (mail) mail.focus();
        return;
      }

      var consent = form.querySelector('input[name="consent"]');
      if (consent && !consent.checked) {
        say('Bitte bestätige die Einwilligung, damit wir dir schreiben dürfen.', 'err');
        return;
      }

      var endpoint = (CFG.newsletter && CFG.newsletter.action) || '';
      if (!endpoint || endpoint.indexOf('BITTE_') === 0) {
        say(
          'Fast geschafft: Der Newsletter-Dienst ist noch nicht verbunden. Trage in src/config.js den Endpunkt deines Anbieters ein.',
          'err'
        );
        return;
      }

      var button = form.querySelector('button[type="submit"]');
      if (button) { button.disabled = true; button.style.opacity = '.7'; }
      say('Wird gesendet …');

      fetch(endpoint, {
        method: 'POST',
        headers: { Accept: 'application/json' },
        body: new FormData(form),
      })
        .then(function (response) {
          if (!response.ok) throw new Error('HTTP ' + response.status);
          form.reset();
          say('Geschafft! Bestätige jetzt die E-Mail in deinem Postfach – danach kommt die Packliste.', 'ok');
        })
        .catch(function () {
          say('Das hat gerade nicht geklappt. Bitte versuche es später noch einmal.', 'err');
        })
        .finally(function () {
          if (button) { button.disabled = false; button.style.opacity = ''; }
        });
    });
  });

  /* ================================================================== *
   * Werkzeug 1: Packlisten-Generator
   * ================================================================== */
  var packer = document.getElementById('packer');
  if (packer) initPacker(packer);

  function initPacker(root) {
    var steps = Array.prototype.slice.call(root.querySelectorAll('.quiz-step'));
    var bars = Array.prototype.slice.call(root.querySelectorAll('.quiz-progress span'));
    var result = root.querySelector('[data-result]');
    var progress = root.querySelector('.quiz-progress');
    var answers = {};
    var current = 0;

    function show(index) {
      steps.forEach(function (step, i) { step.classList.toggle('is-active', i === index); });
      bars.forEach(function (bar, i) { bar.classList.toggle('is-done', i <= index); });
      if (progress) progress.hidden = false;
      current = index;
    }

    /** Nach dem letzten Schritt die Fragen ausblenden – nur das Ergebnis bleibt. */
    function collapseQuestions() {
      steps.forEach(function (step) { step.classList.remove('is-active'); });
      if (progress) progress.hidden = true;
    }

    root.addEventListener('click', function (event) {
      var option = event.target.closest('.quiz-option');
      if (option) {
        var step = option.closest('.quiz-step');
        answers[step.dataset.key] = option.dataset.value;
        if (current < steps.length - 1) {
          show(current + 1);
        } else {
          collapseQuestions();
          render(answers);
        }
        return;
      }
      if (event.target.closest('[data-restart]')) {
        answers = {};
        if (result) result.hidden = true;
        show(0);
        root.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
      if (event.target.closest('[data-back]') && current > 0) show(current - 1);
    });

    /**
     * Baut die Packliste aus den Antworten.
     *
     * Alle Gramm-Angaben sind Richtwerte fuer gaengige, nicht ultraleichte
     * Ausruestung. Sie dienen der Groessenordnung, nicht der Grammjagd.
     */
    function render(a) {
      if (!result) return;

      var nights = a.nights || 'kurz';
      var sleep = a.sleep || 'zelt';
      var season = a.season || 'sommer';
      var cook = a.cook || 'nein';
      var bike = a.bike || 'gravel';
      var terrain = a.terrain || 'gemischt';
      var level = a.level || 'erste';
      var crew = a.crew || 'allein';

      var groups = [];
      var grams = 0;

      function group(name, items) {
        var clean = items.filter(Boolean);
        clean.forEach(function (i) { grams += i[1]; });
        groups.push({ name: name, items: clean });
      }

      /* --- Schlafen ---------------------------------------------------- */
      var sleepItems = [];
      if (sleep === 'unterkunft') {
        sleepItems.push(['Seidenschlafsack / Hüttenschlafsack', 150]);
        sleepItems.push(['Ohrstöpsel und Schlafmaske', 30]);
      } else {
        if (sleep === 'zelt') {
          sleepItems.push(['1-Personen-Zelt (freistehend oder mit Heringen)', 1500]);
        } else if (sleep === 'tarp') {
          sleepItems.push(['Tarp inkl. Schnüre und Heringe', 600]);
          sleepItems.push(['Biwaksack als Nässeschutz von unten', 300]);
        }
        sleepItems.push([
          season === 'kalt'
            ? 'Schlafsack, Komfort ca. −2 °C'
            : season === 'uebergang'
            ? 'Schlafsack, Komfort ca. +5 °C'
            : 'Schlafsack, Komfort ca. +10 °C',
          season === 'kalt' ? 1100 : season === 'uebergang' ? 850 : 600,
        ]);
        sleepItems.push([
          season === 'kalt' ? 'Isomatte, R-Wert ab 4,0' : 'Isomatte, R-Wert ab 2,5',
          season === 'kalt' ? 600 : 450,
        ]);
        sleepItems.push(['Aufblasbares Kissen oder Kleidersack als Kissen', 80]);
      }
      group('Schlafen', sleepItems);

      /* --- Kleidung ---------------------------------------------------- */
      var many = nights === 'lang' || nights === 'woche';
      var clothes = [
        ['Fahrradhose mit Sitzpolster (angezogen)', 0],
        [many ? 'Zweite Fahrradhose' : null, 220],
        ['Trikot oder Merino-Shirt (angezogen)', 0],
        ['Zweites Shirt', 160],
        ['Unterwäsche und Socken, ' + (many ? '3 Paar' : '2 Paar'), many ? 210 : 140],
        ['Regenjacke, wasserdicht mit Kapuze', 320],
        [season === 'sommer' ? null : 'Regenhose', 200],
        [
          season === 'kalt' ? 'Isolationsjacke (Daune oder Primaloft)' : 'Leichte Isolationsschicht / Fleece',
          season === 'kalt' ? 400 : 280,
        ],
        [season === 'sommer' ? null : 'Lange Handschuhe', 90],
        ['Kurze Handschuhe', 60],
        ['Abendkleidung: leichte Hose und Shirt', 350],
        ['Buff / Multifunktionstuch', 40],
        [season === 'kalt' ? 'Mütze unter dem Helm' : null, 50],
        ['Leichte Schuhe für abends (Sandalen o. Ä.)', 300],
      ].filter(function (i) { return i[0]; });
      group('Kleidung', clothes);

      /* --- Kueche ------------------------------------------------------ */
      var kitchen = [];
      if (cook === 'ja') {
        kitchen.push(['Gaskocher (Schraubkocher auf Kartusche)', 90]);
        kitchen.push(['Gaskartusche 230 g (voll)', 380]);
        kitchen.push(['Topf 700–900 ml mit Deckel', 180]);
        kitchen.push(['Löffel (lang, für Beutelmahlzeiten)', 20]);
        kitchen.push(['Feuerzeug plus Reserve', 25]);
        kitchen.push(['Spülschwamm und kleines Tuch', 30]);
      } else {
        kitchen.push(['Faltbecher oder leichte Tasse', 60]);
        kitchen.push(['Löffel', 20]);
      }
      kitchen.push(['Trinkflaschen 2 × 750 ml (leer)', 200]);
      kitchen.push([nights === 'kurz' ? 'Tagesverpflegung und Riegel' : 'Verpflegung für 1,5 Tage', nights === 'kurz' ? 600 : 1200]);
      if (terrain === 'offroad' || nights === 'woche') {
        kitchen.push(['Wasserfilter oder Entkeimungstabletten', 120]);
      }
      group('Wasser & Küche', kitchen);

      /* --- Technik ----------------------------------------------------- */
      var tech = [
        ['Frontlicht (StVZO oder ab 300 Lumen)', 130],
        ['Rücklicht plus Reserve-Rücklicht', 80],
        ['Stirnlampe', 90],
        [
          nights === 'kurz' ? 'Powerbank 10.000 mAh' : nights === 'woche' ? 'Powerbank 20.000 mAh' : 'Powerbank 10.000–20.000 mAh',
          nights === 'kurz' ? 200 : 400,
        ],
        ['Ladegerät und Kabel', 150],
        ['Handy mit Offline-Karten (angezogen)', 0],
        [level === 'erste' ? null : 'GPS-Radcomputer mit Halterung', 100],
      ].filter(function (i) { return i[0]; });
      group('Licht, Strom & Navigation', tech);

      /* --- Werkzeug ---------------------------------------------------- */
      var tools = [
        ['Multitool mit Kettennieter', 160],
        ['Mini-Handpumpe (oder CO₂ plus Pumpe)', 110],
        ['2 Ersatzschläuche in passender Größe', 300],
        ['Flickzeug (selbstklebend) und Reifenheber', 60],
        [terrain === 'asphalt' ? null : 'Reifen-Flicken für große Schnitte (Boot)', 20],
        ['Kettenschloss passend zur Kette', 15],
        ['Kettenöl in kleiner Flasche', 50],
        ['Kabelbinder und Gewebeband (um die Pumpe gewickelt)', 40],
        [bike === 'trekking' ? 'Speichenschlüssel' : 'Ersatzschaltauge für deinen Rahmen', 30],
        ['Fahrradschloss (Faltschloss oder schweres Kabel)', crew === 'allein' ? 500 : 700],
      ].filter(function (i) { return i[0]; });
      group('Werkzeug & Reparatur', tools);

      /* --- Koerper & Papiere ------------------------------------------- */
      var body = [
        ['Erste-Hilfe-Set (klein, mit Blasenpflaster)', 180],
        ['Sitzcreme / Chamois-Creme', 60],
        ['Sonnencreme LSF 30+ und Lippenpflege', 90],
        ['Zahnbürste, Zahnpasta (klein), Seifenblatt', 80],
        ['Mikrofaser-Handtuch', 90],
        ['Ausweis, Versichertenkarte, etwas Bargeld', 60],
        ['Müllbeutel (für deinen eigenen Müll)', 15],
      ];
      group('Körper & Papiere', body);

      /* --- Taschenempfehlung ------------------------------------------- */
      var setup;
      if (bike === 'trekking') {
        setup = {
          headline: 'Gepäckträger hinten plus Lenkerrolle',
          text:
            'Dein Rad hat einen Gepäckträger – nutz ihn. Zwei Hinterradtaschen fassen mehr als jede Satteltasche, sind wasserdicht und ' +
            'kosten weniger. Die Bikepacking-Ergänzung, die sich lohnt, ist eine Lenkerrolle für das Schlafsystem und eine Oberrohrtasche für Snacks.',
          bags: ['2 × Hinterradtasche à 20 l', 'Lenkerrolle 10–14 l', 'Oberrohrtasche 0,7–1 l', 'Optional: Rahmentasche für Werkzeug'],
          volume: '50–60 l',
        };
      } else if (nights === 'kurz') {
        setup = {
          headline: 'Minimal-Setup: Satteltasche plus Lenkerrolle',
          text:
            'Für eine Nacht draußen brauchst du kein volles Taschenset. Satteltasche und Lenkerrolle reichen aus, ' +
            'und eine Rahmentasche kannst du nachkaufen, sobald du weißt, was dir wirklich fehlt.',
          bags: ['Satteltasche 8–11 l', 'Lenkerrolle 8–12 l', 'Oberrohrtasche 0,7 l', 'Optional: Trikottaschen'],
          volume: '18–25 l',
        };
      } else if (nights === 'woche') {
        setup = {
          headline: 'Volles Set inklusive Gabeltaschen',
          text:
            'Ab einer Woche brauchst du Reserve für Verpflegung, Wasser und nasse Kleidung. Die Gabelhalter nehmen genau das ' +
            'auf, was du sonst oben auf den Lenker packen würdest – und halten damit die Lenkung ruhig.',
          bags: ['Satteltasche 13–17 l', 'Lenkerrolle 12–15 l', 'Rahmentasche 4–7 l', '2 × Gabeltasche à 3–4 l', 'Oberrohrtasche 0,7–1 l'],
          volume: '35–45 l',
        };
      } else {
        setup = {
          headline: 'Klassisches Dreiteiler-Set',
          text:
            'Satteltasche, Lenkerrolle und Rahmentasche decken zwei bis vier Nächte komplett ab. Das ist das Setup, ' +
            'mit dem die allermeisten Bikepacker unterwegs sind – und es hat einen guten Grund.',
          bags: ['Satteltasche 11–14 l', 'Lenkerrolle 10–14 l', 'Rahmentasche 4–6 l', 'Oberrohrtasche 0,7–1 l'],
          volume: '26–35 l',
        };
      }

      /* --- Hinweise ---------------------------------------------------- */
      var notes = [];
      if (level === 'erste') {
        notes.push(
          'Weil das deine erste Tour ist: Fahre die vollständig gepackte Runde einmal <strong>zu Hause um den Block</strong>. ' +
            'Alles, was klappert, scheuert oder wackelt, wirst du auf Kilometer 40 hassen.'
        );
      }
      if (sleep === 'zelt' && level === 'erste') {
        notes.push(
          'Baue das Zelt <strong>einmal im Garten oder Wohnzimmer</strong> auf, bevor du losfährst. Der erste Aufbau im Dunkeln bei Regen ist keine gute Premiere.'
        );
      }
      if (bike === 'rennrad') {
        notes.push(
          'Mit dem Rennrad gilt: Rahmendreieck und Reifenfreiheit sind klein. Halte dich an leichte, kompakte Taschen, ' +
            'bleib auf Asphalt und plane die Etappen etwas kürzer – schmale Reifen mit Gepäck sind auf Schotter unangenehm.'
        );
      }
      if (terrain === 'offroad') {
        notes.push(
          'Auf Schotter und Trails wandert eine schwere Satteltasche seitlich aus. Zurre den Kompressionsriemen ' +
            'straff, packe das Schwere möglichst weit vorn in der Tasche und rechne mit deutlich kürzeren Tagesdistanzen.'
        );
      }
      if (crew !== 'allein') {
        notes.push(
          'Ihr seid zu mehreren: Teilt <strong>Zelt, Kocher, Werkzeug und Erste-Hilfe-Set</strong> auf. Das spart pro Person ' +
            'schnell ein bis zwei Kilo – der einfachste Gewichtsgewinn überhaupt.'
        );
      }
      if (cook === 'nein') {
        notes.push(
          'Ohne Kocher planst du die Route um Einkaufsmöglichkeiten herum. Prüfe vorher die Öffnungszeiten – ' +
            'sonntags und in kleinen Orten ist ab 18 Uhr oft nichts mehr offen.'
        );
      }

      /* --- Ausgabe ----------------------------------------------------- */
      var kg = grams / 1000;
      var verdict;
      if (kg < 7) verdict = 'Sehr schlank. Achte darauf, dass Regenschutz und Reparatur-Kit wirklich vollständig sind.';
      else if (kg < 11) verdict = 'Guter Bereich. So fährt sich das Rad fast wie ohne Gepäck.';
      else if (kg < 15) verdict = 'Alltagstauglich, aber spürbar. An langen Anstiegen wirst du jedes Kilo merken.';
      else verdict = 'Das ist viel. Geh die Liste noch einmal durch und streiche alles, was du „für alle Fälle“ mitnimmst.';

      var listHtml = groups
        .map(function (g) {
          return (
            '<h4>' + g.name + '</h4><ul class="tick-list">' +
            g.items
              .map(function (i) {
                return '<li>' + CHECK_SVG + '<span>' + i[0] +
                  (i[1] ? ' <span style="color:var(--c-muted);font-size:.85em">· ca. ' + de(i[1]) + ' g</span>' : '') +
                  '</span></li>';
              })
              .join('') +
            '</ul>'
          );
        })
        .join('');

      result.innerHTML =
        '<h3>Deine Packliste: ' + setup.headline + '</h3>' +
        '<div class="result-figure">' +
          '<div><span>Taschenvolumen</span><strong>' + setup.volume + '</strong></div>' +
          '<div><span>Gepäck ohne Wasser</span><strong>' + kg.toFixed(1).replace('.', ',') + ' kg</strong></div>' +
          '<div><span>Positionen</span><strong>' + groups.reduce(function (n, g) { return n + g.items.length; }, 0) + '</strong></div>' +
        '</div>' +
        '<p>' + setup.text + '</p>' +
        '<h4>Diese Taschen brauchst du</h4>' +
        '<ul class="tick-list">' + setup.bags.map(function (b) { return '<li>' + CHECK_SVG + '<span>' + b + '</span></li>'; }).join('') + '</ul>' +
        '<p><strong>Einordnung:</strong> ' + verdict + '</p>' +
        listHtml +
        (notes.length
          ? '<h4>Für dich besonders wichtig</h4><ul class="tick-list">' +
            notes.map(function (n) { return '<li>' + CHECK_SVG + '<span>' + n + '</span></li>'; }).join('') +
            '</ul>'
          : '') +
        '<p style="font-size:.86rem;color:var(--c-muted)">Die Gewichte sind Richtwerte für gängige, nicht ultraleichte Ausrüstung. ' +
        'Wasser ist nicht eingerechnet: 1,5 Liter wiegen zusätzlich 1,5 kg.</p>' +
        '<p style="margin-top:1.1rem"><a class="btn btn--primary" href="/ausruestung/packliste.html">Zur vollständigen Packliste</a> ' +
        '<button type="button" class="btn btn--secondary" data-restart>Neu starten</button></p>';

      result.hidden = false;
      result.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    show(0);
  }

  /* ================================================================== *
   * Werkzeug 2: Etappen- und Gewichts-Rechner
   * ================================================================== */
  var planner = document.getElementById('planner');
  if (planner) initPlanner(planner);

  function initPlanner(root) {
    var out = root.querySelector('[data-planner-result]');
    if (!out) return;

    var val = function (name, fallback) {
      var el = root.querySelector('[data-' + name + ']');
      if (!el) return fallback;
      var n = parseFloat(String(el.value).replace(',', '.'));
      return isNaN(n) ? fallback : n;
    };
    var pick = function (name) {
      var el = root.querySelector('[data-' + name + ']');
      return el ? el.value : '';
    };

    /**
     * Realistische Tagesdistanz – ueber ein Zeitbudget, nicht ueber Kilometer.
     *
     * Begrenzend ist auf Tour die Zeit, nicht die Strecke. Deshalb wird zuerst
     * ein Zeitbudget im Sattel bestimmt und daraus ueber die Reisegeschwindig-
     * keit die Distanz errechnet. Hoehenmeter kosten in diesem Modell Zeit
     * (rund eine Stunde je 500 Hm) statt pauschal Kilometer – das ist der
     * Grund, warum bergige Etappen kurz werden, ohne dass die Rechnung
     * zusammenbricht.
     */
    function calculate() {
      var refKm = val('ref', 80);
      var hm = val('hm', 500);
      var days = val('days', 3);
      var load = val('load', 10);
      var surface = pick('surface') || 'gemischt';
      var level = pick('level') || 'einige';

      var levelFactor = { erste: 0.85, einige: 1.0, viele: 1.1 }[level] || 1.0;

      // Gepaeck bremst: bis 6 kg kaum spuerbar, danach linear bis maximal −20 %.
      var loadFactor = load <= 6 ? 1 : Math.max(0.8, 1 - (load - 6) * 0.018);

      // Mehrtagesmuedigkeit
      var dayFactor = days <= 2 ? 1 : days <= 4 ? 0.95 : days <= 7 ? 0.9 : 0.85;

      // Zeitbudget im Sattel. Annahme: Die Referenztour wurde mit rund
      // 20 km/h gefahren. Auf Tour sitzt man laenger im Sattel (Faktor 1,2),
      // faehrt aber ruhiger – das steckt in der Geschwindigkeit unten.
      var budget = (refKm / 20) * 1.2 * levelFactor * dayFactor;

      var speed = ({ asphalt: 19, gemischt: 16, offroad: 12.5 }[surface] || 16) * loadFactor;

      // Anstiege kosten zusaetzliche Zeit: rund 500 Hm pro Stunde beladen.
      var climbTime = hm / 500;

      var km = Math.max(10, (budget - climbTime) * speed);
      var ride = km / speed;
      var breaks = (ride + climbTime) * 0.3 + 0.75; // Pausen plus Mittag/Einkauf
      var total = ride + climbTime + breaks;

      var hoursText = function (h) {
        var full = Math.floor(h);
        var min = Math.round((h - full) * 60 / 5) * 5;
        if (min === 60) { full += 1; min = 0; }
        return full + ' h ' + (min < 10 ? '0' : '') + min + ' min';
      };

      var verdict;
      if (level === 'erste' && km > 90) {
        verdict = 'Das ist rechnerisch möglich – für die erste Tour würden wir trotzdem 20 Prozent abziehen. Lieber früh am Ziel sein als im Dunkeln ankommen.';
      } else if (total > 10) {
        verdict = 'Über zehn Stunden unterwegs: Das ist ein langer Tag. Teile die Etappe, oder plane den Start vor 8 Uhr.';
      } else if (total < 5) {
        verdict = 'Entspannt. Da bleibt Zeit für einen See, ein Café und einen Umweg – genau darum geht es beim Bikepacking.';
      } else {
        verdict = 'Solide Etappe. Du bist am frühen Abend am Ziel und hast Puffer für Pannen und Umwege.';
      }

      var totalWeight = load + val('bike', 12) + 2; // 2 kg fuer Wasser und Tagesverpflegung
      var bodyWeight = val('body', 0);
      var share = bodyWeight > 0 ? Math.round((load / bodyWeight) * 1000) / 10 : null;

      var weightNote;
      if (load < 7) weightNote = 'Leicht. Das Rad fährt sich noch fast wie ohne Gepäck.';
      else if (load < 11) weightNote = 'Der Bereich, in dem die meisten Bikepacker landen. Gut so.';
      else if (load < 16) weightNote = 'Spürbar. Prüfe, ob Zelt, Kocher und Kleidung wirklich alle mitmüssen.';
      else weightNote = 'Deutlich zu viel für ein Bikepacking-Setup. Auf einem Gepäckträger wäre das angenehmer zu fahren.';

      out.innerHTML =
        '<h3>Deine realistische Tagesetappe</h3>' +
        '<div class="result-figure">' +
          '<div><span>Tagesdistanz</span><strong>' + Math.round(km) + ' km</strong></div>' +
          '<div><span>Reine Fahrzeit</span><strong>' + hoursText(ride + climbTime) + '</strong></div>' +
          '<div><span>Tag inkl. Pausen</span><strong>' + hoursText(total) + '</strong></div>' +
          '<div><span>Gesamtstrecke</span><strong>' + de(Math.round(km * days)) + ' km</strong></div>' +
        '</div>' +
        '<p><strong>' + verdict + '</strong></p>' +
        '<p>Auf ' +
          ({ asphalt: 'Asphalt', gemischt: 'gemischtem Untergrund', offroad: 'Schotter und Trails' }[surface]) +
          ' rechnen wir mit einem Schnitt von rund ' + speed.toFixed(1).replace('.', ',') + ' km/h. ' +
          'Von deinem Zeitbudget von ' + hoursText(budget) + ' im Sattel gehen ' + hoursText(climbTime) +
          ' allein für die ' + de(Math.round(hm)) + ' Höhenmeter drauf. ' +
          'Für ' + days + (days === 1 ? ' Tag' : ' Tage') + ' ergibt das etwa ' + de(Math.round(km * days)) +
          ' Kilometer Gesamtstrecke.</p>' +
        (budget - climbTime < 0.6
          ? '<p><strong>Achtung:</strong> Die Höhenmeter fressen dein gesamtes Zeitbudget auf. ' +
            'Bei dieser Kombination aus Referenzstrecke und Anstiegen ist die Etappe nicht mehr sinnvoll planbar – ' +
            'teile sie auf zwei Tage, oder such eine flachere Route.</p>'
          : '') +
        '<h4>Gewichts-Check</h4>' +
        '<div class="result-figure">' +
          '<div><span>Gepäck</span><strong>' + load.toFixed(1).replace('.', ',') + ' kg</strong></div>' +
          '<div><span>Systemgewicht</span><strong>' + totalWeight.toFixed(1).replace('.', ',') + ' kg</strong></div>' +
          (share !== null ? '<div><span>Anteil Körpergewicht</span><strong>' + String(share).replace('.', ',') + ' %</strong></div>' : '') +
        '</div>' +
        '<p>' + weightNote + ' Systemgewicht heißt Rad plus Gepäck plus zwei Kilo für Wasser und Tagesverpflegung – ' +
        'das ist die Masse, die du jeden Höhenmeter mit hochziehst.</p>' +
        '<p style="font-size:.86rem;color:var(--c-muted)">Die Rechnung ist eine Planungshilfe, keine Garantie. ' +
        'Gegenwind, Hitze, schlechter Schlaf und eine gesperrte Brücke stehen in keiner Formel. ' +
        'Plane deshalb immer eine Ausstiegsmöglichkeit pro Tag ein.</p>';

      out.hidden = false;
    }

    root.addEventListener('input', function (event) {
      if (event.target.matches('input, select')) calculate();
    });
    root.addEventListener('change', function (event) {
      if (event.target.matches('select')) calculate();
    });
    var reset = root.querySelector('[data-planner-reset]');
    if (reset) {
      reset.addEventListener('click', function () {
        root.querySelectorAll('input[data-default]').forEach(function (el) {
          el.value = el.dataset.default;
        });
        calculate();
      });
    }

    calculate();
  }
})();
