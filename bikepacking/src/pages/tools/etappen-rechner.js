'use strict';

const article = require('../_article');
const { h2, h3, callout, checklist, table } = require('../../components');

/**
 * Markup fuer den Rechner. Die Auswertung passiert in
 * src/assets/site.js (initPlanner) – die data-Attribute sind die Schnittstelle.
 */
const tool = `<div class="tool" id="planner">
  <h2 style="margin-top:0;color:var(--c-ink);font-size:1.25rem;font-weight:800">1. Deine Ausgangswerte</h2>
  <p style="margin:.4rem 0 1.2rem;color:var(--c-muted);font-size:.92rem">
    Der wichtigste Wert ist der erste: Wie weit kommst du an einem guten Tag <strong>ohne</strong> Gepäck?
    Davon ausgehend rechnet der Planer alles Weitere.
  </p>
  <div class="tool-grid">
    <div class="field">
      <label for="pl-ref">Gute Tagestour ohne Gepäck (km)</label>
      <input type="number" id="pl-ref" data-ref data-default="80" value="80" min="20" max="300" step="5">
    </div>
    <div class="field">
      <label for="pl-hm">Höhenmeter pro Tag</label>
      <input type="number" id="pl-hm" data-hm data-default="500" value="500" min="0" max="3000" step="50">
    </div>
    <div class="field">
      <label for="pl-days">Anzahl Tourtage</label>
      <input type="number" id="pl-days" data-days data-default="3" value="3" min="1" max="30" step="1">
    </div>
    <div class="field">
      <label for="pl-surface">Untergrund</label>
      <select id="pl-surface" data-surface>
        <option value="asphalt">Überwiegend Asphalt</option>
        <option value="gemischt" selected>Gemischt</option>
        <option value="offroad">Schotter und Trails</option>
      </select>
    </div>
    <div class="field">
      <label for="pl-level">Bikepacking-Erfahrung</label>
      <select id="pl-level" data-level>
        <option value="erste">Erste Tour</option>
        <option value="einige" selected>Ein paar Touren</option>
        <option value="viele">Viel Erfahrung</option>
      </select>
    </div>
  </div>

  <h2 style="margin-top:2rem;color:var(--c-ink);font-size:1.25rem;font-weight:800">2. Dein Gewicht</h2>
  <p style="margin:.4rem 0 1.2rem;color:var(--c-muted);font-size:.92rem">
    Gepäck heißt: alles in den Taschen, ohne Wasser und ohne das, was du am Körper trägst.
    Das Körpergewicht ist optional – es dient nur der Einordnung.
  </p>
  <div class="tool-grid">
    <div class="field">
      <label for="pl-load">Gepäckgewicht (kg)</label>
      <input type="number" id="pl-load" data-load data-default="10" value="10" min="0" max="40" step="0.5">
    </div>
    <div class="field">
      <label for="pl-bike">Gewicht des Rads (kg)</label>
      <input type="number" id="pl-bike" data-bike data-default="12" value="12" min="6" max="30" step="0.5">
    </div>
    <div class="field">
      <label for="pl-body">Körpergewicht (kg, optional)</label>
      <input type="number" id="pl-body" data-body data-default="0" value="" min="0" max="200" step="1" placeholder="optional">
    </div>
  </div>

  <div class="tool-actions">
    <button type="button" class="btn btn--secondary" data-planner-reset>Zurücksetzen</button>
  </div>

  <div class="tool-result" data-planner-result hidden></div>
</div>`;

const content = `
<p class="lead-p">
  Zwei Rechnungen in einem Werkzeug: Wie weit kommst du an einem Tag realistisch – und ist dein
  Gepäck noch im vernünftigen Rahmen? Der Rechner berücksichtigt Höhenmeter, Untergrund, Gewicht,
  Erfahrung und Mehrtagesermüdung und gibt zusätzlich die Tageslänge inklusive Pausen aus.
</p>

${tool}

${h2('Wie gerechnet wird', 'formel')}
${h3('Die Tagesdistanz', 'formel-distanz')}
<blockquote>Tagesdistanz = (Zeitbudget − Zeit für die Anstiege) × Reisegeschwindigkeit</blockquote>
<p>
  Begrenzend ist auf Tour nicht die Strecke, sondern die <strong>Zeit</strong>. Deshalb rechnet der
  Planer nicht mit Kilometer-Abzügen, sondern bestimmt zuerst, wie viele Stunden du im Sattel
  aushältst – und wie viel davon die Anstiege verbrauchen.
</p>

${h3('Schritt 1: Das Zeitbudget', 'formel-budget')}
${table({
  head: ['Faktor', 'Wert', 'Begründung'],
  rows: [
    ['Referenzstrecke ÷ 20 km/h', '= Stunden', 'Typischer Schnitt einer guten Tagestour ohne Gepäck'],
    ['Tourfaktor', '× 1,2', 'Auf Mehrtagestouren sitzt man länger im Sattel'],
    ['Erste Tour', '× 0,85', 'Alles dauert länger, weil noch nichts Routine ist'],
    ['Ein paar Touren', '× 1,0', 'Referenz'],
    ['Viel Erfahrung', '× 1,1', 'Eingespielte Abläufe, weniger Leerlauf'],
    ['3 – 4 Tourtage', '× 0,95', 'Beginnende Ermüdung'],
    ['5 – 7 Tourtage', '× 0,90', 'Der klassische Einbruch ab Tag 3'],
    ['Über 7 Tourtage', '× 0,85', 'Dauerbelastung ohne vollständige Erholung'],
  ],
})}

${h3('Schritt 2: Die Reisegeschwindigkeit', 'formel-tempo')}
${table({
  head: ['Faktor', 'Wert', 'Begründung'],
  rows: [
    ['Asphalt', '19 km/h', 'Fernradwege und Straßen'],
    ['Gemischt', '16 km/h', 'Wald- und Wirtschaftswege kosten Tempo'],
    ['Schotter und Trails', '12,5 km/h', 'Loser Untergrund kostet Tempo und Konzentration'],
    ['Gepäck über 6 kg', '−1,8 % je kg', 'Gedeckelt bei maximal −20 Prozent'],
  ],
  note: 'Das sind Reiseschnitte: Sie enthalten Ampeln, kurze Steigungen und das Suchen an Kreuzungen, aber keine echten Pausen.',
})}

${h3('Schritt 3: Die Anstiege', 'formel-hm')}
<p>
  Beladen bewältigst du etwa <strong>500 Höhenmeter pro Stunde</strong>. Diese Zeit wird vom Budget
  abgezogen, bevor die Distanz errechnet wird. 600 Höhenmeter kosten also rund 1 Stunde 12 Minuten –
  und genau die fehlen dir für Kilometer.
</p>
${checklist([
  '<strong>Reine Fahrzeit</strong> ist die Zeit im Sattel, inklusive des Zeitaufschlags für Anstiege.',
  '<strong>Tag inklusive Pausen</strong> rechnet 30 Prozent der Fahrzeit plus 45 Minuten für Mittagspause und Einkauf hinzu.',
  '<strong>Nicht enthalten</strong> sind Zeltabbau am Morgen (45 bis 75 Minuten) und Aufbau am Abend (60 bis 90 Minuten).',
  '<strong>Untergrenze 10 Kilometer:</strong> Wenn die Anstiege das Budget aufbrauchen, meldet der Rechner das ausdrücklich – dann ist die Etappe zu teilen.',
])}

${h3('Der Gewichts-Check', 'formel-gewicht')}
<p>
  Das <strong>Systemgewicht</strong> ist die Masse, die du an jedem Anstieg mit hochziehst: Rad plus
  Gepäck plus zwei Kilo für Wasser und Tagesverpflegung. Der optionale Anteil am Körpergewicht dient
  der Einordnung – als grober Anhaltspunkt gilt Gepäck bis etwa 15 Prozent des Körpergewichts als
  gut beherrschbar.
</p>
${table({
  head: ['Gepäckgewicht', 'Einordnung'],
  rows: [
    ['unter 7 kg', 'Leicht – das Rad fährt sich fast wie ohne Gepäck'],
    ['7 – 11 kg', '<strong>Der Bereich, in dem die meisten Bikepacker landen</strong>'],
    ['11 – 16 kg', 'Spürbar – prüfe, ob Zelt, Kocher und Kleidung wirklich alle mitmüssen'],
    ['über 16 kg', 'Zu viel für ein Bikepacking-Setup – auf einem Gepäckträger angenehmer'],
  ],
})}

${callout(
  'Warum am Ende rund 60 Prozent herauskommen',
  '<p>Beim ersten Rechnen erschrickt fast jeder: Aus 100 Kilometern werden schnell 55. Das ist kein Pessimismus der Formel, sondern die Folge einer falsch gestellten Ausgangsfrage. Deine beste Tagestour ohne Gepäck ist ein <strong>Maximalwert</strong>, den du einmal abrufst. Eine Bikepacking-Etappe musst du <strong>am nächsten Tag wiederholen</strong> – und am übernächsten auch. Dass am Ende etwa 60 Prozent übrig bleiben, ist deshalb kein Rechentrick, sondern das Ergebnis aus Zeitbudget, Tempo und Anstiegen.</p>',
  'info'
)}

${h2('Was die Rechnung nicht kann', 'grenzen')}
${checklist(
  [
    '<strong>Gegenwind.</strong> Vier Windstärken kosten mehr als 300 Höhenmeter – und stehen in keiner Formel.',
    '<strong>Hitze.</strong> Über 30 Grad sinkt die sinnvolle Tagesdistanz um 20 bis 30 Prozent.',
    '<strong>Schlechter Schlaf.</strong> Die zweite Nacht auf einer Isomatte ist selten die beste.',
    '<strong>Sperrungen und Umwege.</strong> Eine gesperrte Brücke sind schnell 25 zusätzliche Kilometer.',
    '<strong>Deine Tagesform.</strong> Sie schwankt stärker als jeder Faktor in dieser Tabelle.',
  ],
  { tone: 'dont' }
)}
<p>
  Deshalb gilt: Plane pro Tag <strong>eine Ausstiegsmöglichkeit</strong> ein – idealerweise einen
  Bahnhof. Dann wird aus einer Etappe, die nicht aufgeht, einfach eine kürzere Etappe.
</p>

${h2('Und danach?', 'danach')}
${checklist([
  '<a href="/einstieg/tagesetappen-planen.html">Wie weit kommst du am Tag?</a> – die Formel im Detail, mit Richtwerten nach Erfahrung und dem vollständigen Tages-Zeitplan.',
  '<a href="/routen/route-selbst-planen.html">Route selbst planen</a> – wie du aus der Tagesdistanz eine Route mit Schlafplätzen machst.',
  '<a href="/tools/packlisten-generator.html">Packlisten-Generator</a> – falls dir der Gewichts-Check zu viel ausgerechnet hat.',
])}
`;

module.exports = article({
  href: '/tools/etappen-rechner.html',
  kicker: 'Werkzeug · Kostenlos',
  title: 'Etappen- & Gewichts-Rechner',
  metaTitle: 'Bikepacking-Etappenrechner: Realistische Tagesdistanz berechnen | Sattelfest',
  description:
    'Kostenloser Etappenrechner fürs Bikepacking: realistische Tagesdistanz aus Zeitbudget, Reisegeschwindigkeit und Höhenmetern – plus Fahrzeit, Tageslänge mit Pausen und Gewichts-Check.',
  lead:
    'Wie weit kommst du an einem Tag wirklich – und ist dein Gepäck noch im Rahmen? Mit Höhenmetern, Untergrund, Mehrtagesermüdung und Tageslänge inklusive Pausen.',
  meta: [
    { icon: 'clock', text: '2 Minuten' },
    { icon: 'mountain', text: 'Zeitbudget statt Kilometer' },
    { icon: 'shield', text: 'Keine Datenübertragung' },
  ],
  content,
  faq: [
    {
      q: 'Wie realistisch ist die berechnete Tagesdistanz?',
      a: '<p>Sie bildet die Erfahrungswerte gut ab, die sich beim Bikepacking eingespielt haben: rund 500 Höhenmeter pro Stunde beladen, Reiseschnitte zwischen 12,5 und 19 km/h und ein Ergebnis, das typischerweise bei etwa 60 Prozent deiner Referenzstrecke landet. Was sie nicht kennt: Gegenwind, Hitze, schlechten Schlaf und Umleitungen. Plane deshalb pro Tag eine Ausstiegsmöglichkeit ein.</p>',
    },
    {
      q: 'Was gebe ich bei „gute Tagestour ohne Gepäck“ ein?',
      a: '<p>Die Strecke, die du an einem guten Tag ohne Gepäck komfortabel schaffst – nicht deine Bestleistung und nicht deine übliche Feierabendrunde. Wenn du unsicher bist: Nimm die längste Tour der letzten Saison und zieh 10 Prozent ab. Dieser eine Wert bestimmt das gesamte Ergebnis.</p>',
    },
    {
      q: 'Warum kommt bei vielen Höhenmetern eine so kurze Distanz heraus?',
      a: '<p>Weil Anstiege Zeit kosten, und Zeit ist das, was deinen Tag begrenzt. Beladen schaffst du rund 500 Höhenmeter pro Stunde: Ein Tag mit 1.500 Höhenmetern verbraucht damit drei Stunden deines Zeitbudgets, bevor du einen einzigen Kilometer weit gekommen bist. Bei bergigen Routen ist die Etappe deshalb nicht durch die Distanz begrenzt, sondern durch die Anstiege.</p>',
    },
    {
      q: 'Wie viel Gepäck ist zu viel?',
      a: '<p>Unter 7 Kilo ist leicht, 7 bis 11 Kilo ist der Bereich, in dem die meisten Bikepacker landen. Ab 11 Kilo wird es spürbar, über 16 Kilo ist es für ein Bikepacking-Setup zu viel – dann trägt sich das Gewicht auf einem Gepäckträger deutlich angenehmer als in Satteltasche und Lenkerrolle.</p>',
    },
    {
      q: 'Werden meine Eingaben gespeichert?',
      a: '<p>Nein. Der Rechner arbeitet vollständig in deinem Browser. Es werden keine Daten an einen Server übertragen, nichts gespeichert und keine Cookies gesetzt. Beim Neuladen der Seite stehen wieder die Ausgangswerte.</p>',
    },
  ],
  related: [
    { href: '/einstieg/tagesetappen-planen.html', label: 'Wie weit kommst du am Tag?' },
    { href: '/routen/route-selbst-planen.html', label: 'Route selbst planen' },
    { href: '/tools/packlisten-generator.html', label: 'Packlisten-Generator' },
  ],
});
