'use strict';

const article = require('../_article');
const {
  h2, h3, callout, table, checklist, stats, steps, weightList,
} = require('../../components');

const content = `
<p class="lead-p">
  Die realistischen Risiken beim Bikepacking sind unspektakulär: Stürze, Unterkühlung, Dehydrierung
  und der Straßenverkehr. Nicht Wildtiere, nicht Überfälle, nicht die Nacht allein im Wald. Wer die
  echten Risiken kennt, kann sie klein halten – und den Rest getrost ignorieren.
</p>

${stats([
  { value: '112', label: 'Notruf EU-weit', note: 'Funktioniert auch ohne SIM-Karte und ohne Guthaben.' },
  { value: 'Verkehr', label: 'Das reale Hauptrisiko', note: 'Nicht Wildtiere, nicht fremde Menschen.' },
  { value: '1', label: 'Person weiß Bescheid', note: 'Die einfachste Sicherheitsmaßnahme überhaupt.' },
])}

${h2('Die realistische Risikoeinschätzung', 'risiken')}
${table({
  head: ['Risiko', 'Wahrscheinlichkeit', 'Wirksame Vorbeugung'],
  rows: [
    [
      '<strong>Sturz</strong>',
      'Hoch',
      'Helm, angepasstes Tempo bergab, Gepäck straff sitzend',
    ],
    [
      '<strong>Straßenverkehr</strong>',
      'Hoch',
      'Gute Beleuchtung, helle Kleidung, Routen mit wenig Kfz-Verkehr',
    ],
    [
      '<strong>Unterkühlung</strong>',
      'Mittel',
      'Regenkleidung, trockene Reservegarnitur, rechtzeitig abbrechen',
    ],
    [
      '<strong>Dehydrierung und Hitzeerschöpfung</strong>',
      'Mittel',
      'Regelmäßig trinken, Salz zuführen, Mittagshitze meiden',
    ],
    [
      'Gewitter',
      'Mittel im Sommer',
      'Vormittags fahren, Wetterwarnung prüfen, Schutz suchen',
    ],
    [
      'Diebstahl des Rads',
      'Gering bis mittel',
      'Rad nie unbeaufsichtigt, Schloss, Sichtkontakt im Supermarkt',
    ],
    [
      'Zecken',
      'Regional erhöht',
      'Abends absuchen, FSME-Impfung in Risikogebieten prüfen',
    ],
    [
      'Übergriffe durch Menschen',
      '<strong>Sehr gering</strong>',
      'Legale Übernachtungsplätze, Bauchgefühl ernst nehmen',
    ],
    [
      'Wildtiere',
      '<strong>Praktisch null</strong>',
      'Essen nicht im Zelt lagern – das ist alles',
    ],
  ],
  note: 'Die beiden am häufigsten befürchteten Risiken stehen ganz unten. Die beiden realen stehen ganz oben – und beide lassen sich mit Ausrüstung und Routenwahl deutlich verringern.',
})}

${h2('Vor der Tour: die drei Minuten, die zählen', 'vorher')}
${checklist([
  '<strong>Route und Schlafplätze an eine Vertrauensperson schicken</strong> – mit groben Etappenzeiten. Das ist die wirksamste Einzelmaßnahme überhaupt und kostet drei Minuten',
  '<strong>Notfallkontakt im Handy hinterlegen</strong> – als „ICE" oder auf dem Sperrbildschirm sichtbar',
  '<strong>Wichtige Daten auf Papier:</strong> Notfallkontakt, Blutgruppe, Allergien, Medikamente, Versichertennummer. In die Hüfttasche',
  '<strong>Auslandskrankenversicherung prüfen</strong>, wenn du ins Ausland fährst – Bergungskosten sind oft nicht enthalten',
  '<strong>FSME-Impfstatus prüfen</strong>, wenn deine Route durch Risikogebiete führt',
  '<strong>Radcheck machen</strong> – Bremsen und Reifen sind Sicherheitsteile, keine Komfortfrage',
])}

${callout(
  'Die wichtigste Regel überhaupt',
  '<p>Jemand muss wissen, wo du bist und wann du dich wieder meldest. Ein tägliches „bin da“ am Abend reicht völlig. Der Punkt ist nicht die Nachricht selbst – sondern dass ihr Ausbleiben jemandem auffällt. Das ist die gesamte Sicherheitsarchitektur einer Solo-Tour in einem Satz.</p>',
  'tip'
)}

${h2('Das Erste-Hilfe-Set', 'erste-hilfe')}
${weightList({
  title: 'Für Rad- und Mehrtagestouren',
  items: [
    { name: 'Wundschnellverband und Pflaster in mehreren Größen', g: 30, tag: 'pflicht' },
    { name: 'Sterile Kompressen, 2 Stück', note: 'Für großflächige Schürfwunden nach einem Sturz', g: 20, tag: 'pflicht' },
    { name: 'Elastische Binde', note: 'Verstauchungen, Fixierung von Verbänden', g: 50, tag: 'pflicht' },
    { name: 'Desinfektionsmittel oder Wundspray', g: 30, tag: 'pflicht' },
    { name: 'Einmalhandschuhe, 1 Paar', g: 10, tag: 'pflicht' },
    { name: 'Blasenpflaster', note: 'Auch für wundgescheuerte Stellen an der Sitzfläche', g: 20, tag: 'pflicht' },
    { name: 'Schmerz- und Entzündungshemmer', note: 'Nur für den Notfall, nicht als Dauerlösung', g: 10, tag: 'pflicht' },
    { name: 'Rettungsdecke', note: 'Wiegt nichts und ist bei Unterkühlung entscheidend', g: 60, tag: 'pflicht' },
    { name: 'Pinzette', note: 'Splitter und Zecken', g: 15, tag: 'sinnvoll' },
    { name: 'Zeckenkarte oder Zeckenzange', g: 5, tag: 'sinnvoll' },
    { name: 'Persönliche Medikamente', note: 'Doppelte Menge, getrennt verstaut', g: 40, tag: 'pflicht' },
    { name: 'Elektrolytpulver', note: 'Bei Hitze und starkem Schwitzen', g: 30, tag: 'sinnvoll' },
    { name: 'Große Klinikausstattung', note: 'Du bist in Mitteleuropa nie weit von einem Arzt entfernt', g: 400, tag: 'ballast' },
  ],
})}

${h2('Notruf und Standortbestimmung', 'notruf')}
${steps([
  {
    title: 'Notruf 112 wählen',
    meta: 'EU-weit, auch ohne SIM und ohne Guthaben',
    text:
      'Die 112 funktioniert in ganz Europa und wird auch dann verbunden, wenn dein eigener Anbieter kein Netz hat – das Handy nutzt dann jedes verfügbare Netz. Deshalb funktioniert der Notruf oft, wenn normale Anrufe scheitern.',
  },
  {
    title: 'Standort durchgeben',
    text:
      'Die meisten Handys zeigen die Koordinaten in der Karten-App an. Alternativ: nächste Ortschaft, Straßenname, auffällige Landmarke, Kilometerangabe an Radwegen. Viele Radwege und Forstwege haben Rettungspunkte mit einer eindeutigen Nummer – die ist für die Leitstelle ideal.',
  },
  {
    title: 'Die fünf W beantworten',
    text:
      'Wo ist es passiert? Was ist passiert? Wie viele Verletzte? Welche Verletzungen? Warten auf Rückfragen – nicht auflegen, bevor die Leitstelle das Gespräch beendet.',
  },
  {
    title: 'Unfallstelle sichern',
    text:
      'Auf der Straße: Warnweste oder helle Kleidung, Räder aus der Fahrbahn, wenn möglich hinter eine Kurve gehen. Im Gelände: an einem Punkt bleiben, der von der Straße oder aus der Luft sichtbar ist.',
  },
])}

${checklist([
  '<strong>Rettungspunkte im Wald</strong> tragen Nummernschilder und sind bei den Leitstellen hinterlegt – notiere die Nummer, wenn du eine siehst',
  '<strong>Die Notruf-SMS</strong> funktioniert in manchen Regionen bei schwachem Netz, wo ein Anruf scheitert',
  '<strong>Offline-Karten zeigen Koordinaten</strong> auch ohne Empfang – die Position kennt dein Handy per GPS unabhängig vom Netz',
  '<strong>Bei „Notruf" im Sperrbildschirm</strong> lassen sich hinterlegte Notfallkontakte auch von Fremden erreichen',
])}

${h2('Unterkühlung erkennen und behandeln', 'unterkuehlung')}
<p>
  Das unterschätzteste Risiko beim Radfahren – weil es sich langsam entwickelt und die Betroffenen
  es selbst oft zuletzt merken. Kritisch wird die Kombination aus Nässe, Wind und Temperaturen
  unter etwa 12 Grad.
</p>
${table({
  head: ['Stadium', 'Anzeichen', 'Was zu tun ist'],
  rows: [
    [
      'Frühzeichen',
      'Zittern, kalte Hände, Konzentrationsschwäche',
      'Sofort anhalten, trockene Schicht anziehen, etwas Warmes trinken, Kalorien zuführen',
    ],
    [
      'Deutlich',
      'Starkes Zittern, unbeholfene Bewegungen, undeutliche Sprache',
      'Fahrt beenden. Unterkunft suchen, nasse Kleidung wechseln, Rettungsdecke',
    ],
    [
      'Ernst',
      'Zittern hört auf, Verwirrtheit, Teilnahmslosigkeit',
      '<strong>Notruf 112.</strong> Person nicht mehr bewegen als nötig, isolieren, warm halten',
    ],
  ],
  note: 'Warnzeichen bei anderen: Wenn jemand plötzlich schweigsam wird, Fragen falsch beantwortet oder in Kurven unsicher fährt, ist das ein Grund anzuhalten – nicht ein Grund zur Eile.',
})}

${callout(
  'Der Punkt, an dem du abbrichst',
  '<p>Wenn du zitterst und keine trockene Kleidung mehr hast, ist die Tour für heute vorbei. Nicht „noch 20 Kilometer" – jetzt. Eine Pension für 60 Euro oder eine Bahnfahrt nach Hause sind die richtige Entscheidung, keine Niederlage. <strong>Nasse Kälte ist der einzige Umstand, unter dem Bikepacking in Mitteleuropa wirklich gefährlich wird.</strong></p>',
  'warn'
)}

${h2('Sicherheit im Straßenverkehr', 'verkehr')}
${checklist([
  '<strong>Sichtbarkeit vor allem anderen.</strong> Helle Kleidung, funktionierendes Licht, Reflektoren – auch tagsüber bei schlechtem Wetter',
  '<strong>Zwei Rücklichter.</strong> Eines fällt aus, und die Satteltasche verdeckt oft das an der Sattelstütze',
  '<strong>Beladen bremst du länger.</strong> Rechne mit deutlich mehr Bremsweg, vor allem bergab und bei Nässe',
  '<strong>Routen mit wenig Kfz-Verkehr wählen.</strong> Radfernwege und Wirtschaftswege sind nicht nur schöner, sondern messbar sicherer',
  '<strong>In der Dämmerung besonders vorsichtig.</strong> Die kritischste Zeit – Autofahrende haben ihr Licht oft noch nicht an',
  '<strong>Helm tragen.</strong> Auch wenn es in Deutschland keine Pflicht ist: Der häufigste ernste Radunfall ist der Sturz auf den Kopf',
  '<strong>Bei starkem Seitenwind</strong> auf offenen Strecken Abstand zu Lkw halten – der Sog reißt beladene Räder spürbar',
])}

${h2('Diebstahl und Wertsachen', 'diebstahl')}
${table({
  head: ['Situation', 'Risiko', 'Maßnahme'],
  rows: [
    ['Supermarkt, 10 Minuten', 'Gering bis mittel', 'Rad ans Fenster in Sichtweite, Hüfttasche mitnehmen'],
    ['Café, 1 Stunde', 'Mittel', 'Abschließen, Sichtkontakt, Taschen soweit möglich mitnehmen'],
    ['Campingplatz, über Nacht', 'Gering', 'Abschließen, am besten an ein festes Objekt oder ans Zelt'],
    ['Trekkingplatz, über Nacht', 'Sehr gering', 'Abschließen genügt, Taschen mit ins Zelt'],
    ['Bahnhof, unbeaufsichtigt', '<strong>Hoch</strong>', 'Nie unbeaufsichtigt lassen – auch nicht kurz'],
    ['Innenstadt, über Nacht', '<strong>Hoch</strong>', 'Nur in abschließbaren Räumen, Unterkunft vorher fragen'],
  ],
  note: 'Die wirksamste Maßnahme ist keine Ausrüstung, sondern Gewohnheit: Ausweis, Bankkarte, Bargeld und Handy in einer kleinen Hüfttasche, die immer am Körper bleibt.',
})}

${h3('Wenn doch etwas gestohlen wird', 'gestohlen')}
${checklist([
  '<strong>Anzeige bei der Polizei</strong> – notwendig für jede Versicherungsmeldung',
  '<strong>Rahmennummer notieren, bevor du losfährst.</strong> Ohne sie ist eine Fahndung praktisch aussichtslos',
  '<strong>Foto vom Rad auf dem Handy</strong> – hilft bei Anzeige und Versicherung',
  '<strong>Hausratversicherung prüfen:</strong> Viele decken Fahrraddiebstahl nur mit Zusatzbaustein und nur nachts, wenn das Rad im verschlossenen Raum stand',
  '<strong>Bankkarten sofort über 116 116 sperren</strong> – die Sperrnotruf-Nummer funktioniert bundesweit',
])}

${h2('Die Nacht draußen', 'nacht')}
<p>
  Die meistgestellte Frage von Einsteigern – und die mit der unspektakulärsten Antwort. Auf einem
  legalen Trekking- oder Campingplatz passiert praktisch nie etwas. Was hilft, ist weniger
  Ausrüstung als Ruhe:
</p>
${checklist([
  '<strong>Legale Plätze nutzen.</strong> Wer nicht damit rechnen muss, weggeschickt zu werden, schläft deutlich besser',
  '<strong>Nichts draußen liegen lassen.</strong> Taschen mit ins Zelt oder unter das Außenzelt, Rad angeschlossen',
  '<strong>Essen nicht im Innenzelt lagern.</strong> Nicht wegen Bären – wegen Mäusen, die sich durch Zeltstoff nagen',
  '<strong>Stirnlampe griffbereit</strong> neben dem Kopf, damit du nachts nicht suchen musst',
  '<strong>Ohrstöpsel</strong> – die meisten Geräusche sind Wind, Vögel und Wild. Sie stören mehr, als sie bedeuten',
  '<strong>Bauchgefühl ernst nehmen.</strong> Wenn ein Platz sich falsch anfühlt, fahr weiter. Es kostet 20 Minuten und ist es immer wert',
])}

${callout(
  'Was in Mitteleuropa nachts nicht passiert',
  '<p>Es gibt in Deutschland keine Tiere, die für einen Menschen im Zelt gefährlich sind. Wildschweine meiden Menschen, Wölfe erst recht. Die realen nächtlichen Störungen sind: Mäuse an der Verpflegung, Regen, den man für etwas anderes hält, und ein Reh, das durchs Unterholz bricht und klingt wie ein Elefant. Nach der zweiten Nacht draußen ist das Thema erledigt.</p>',
  'info'
)}
`;

module.exports = article({
  href: '/unterwegs/sicherheit-notfall.html',
  kicker: 'Unterwegs · Sicherheit',
  title: 'Sicherheit & Notfall',
  metaTitle: 'Bikepacking Sicherheit: Notruf, Erste Hilfe, Unterkühlung, Diebstahl | Sattelfest',
  description:
    'Sicherheit beim Bikepacking: die realistische Risikoeinschätzung, das Erste-Hilfe-Set mit Gewichten, Notruf und Standortbestimmung ohne Empfang, Unterkühlung erkennen, Diebstahlschutz und die Nacht draußen.',
  lead:
    'Die realen Risiken sind unspektakulär: Stürze, Verkehr, Unterkühlung, Dehydrierung. Nicht Wildtiere, nicht die Nacht allein im Wald.',
  meta: [
    { icon: 'shield', text: '10 Minuten Lesezeit' },
    { icon: 'alert', text: 'Mit Notruf-Anleitung' },
    { icon: 'weight', text: 'Erste-Hilfe-Set mit Gewichten' },
  ],
  toc: [
    { label: 'Die realistische Risikoeinschätzung', id: 'risiken' },
    { label: 'Vor der Tour', id: 'vorher' },
    { label: 'Das Erste-Hilfe-Set', id: 'erste-hilfe' },
    { label: 'Notruf und Standortbestimmung', id: 'notruf' },
    { label: 'Unterkühlung erkennen', id: 'unterkuehlung' },
    { label: 'Sicherheit im Straßenverkehr', id: 'verkehr' },
    { label: 'Diebstahl und Wertsachen', id: 'diebstahl' },
    { label: 'Die Nacht draußen', id: 'nacht' },
  ],
  content,
  faq: [
    {
      q: 'Was sind die realen Risiken beim Bikepacking?',
      a: '<p>Stürze und der Straßenverkehr an erster Stelle, danach Unterkühlung und Dehydrierung. Wildtiere und Übergriffe durch Menschen – die beiden am häufigsten befürchteten Szenarien – sind statistisch praktisch bedeutungslos. Die realen Risiken lassen sich durch Helm, gute Beleuchtung, verkehrsarme Routen und passende Kleidung deutlich verringern.</p>',
    },
    {
      q: 'Funktioniert der Notruf ohne Handyempfang?',
      a: '<p>Die 112 wird EU-weit auch dann verbunden, wenn dein eigener Anbieter kein Netz hat – das Handy nutzt dann jedes verfügbare Netz, und sie funktioniert auch ohne SIM-Karte und ohne Guthaben. Deshalb klappt der Notruf oft dort, wo normale Anrufe scheitern. Deine GPS-Position kennt das Handy unabhängig vom Netz.</p>',
    },
    {
      q: 'Wie gebe ich meinen Standort im Notfall durch?',
      a: '<p>Am besten über die Koordinaten aus der Karten-App, die auch offline funktioniert. Alternativ: nächste Ortschaft, Straßenname, auffällige Landmarke oder Kilometerangabe am Radweg. Ideal sind Rettungspunkte im Wald – sie tragen Nummernschilder und sind bei den Leitstellen hinterlegt.</p>',
    },
    {
      q: 'Woran erkenne ich eine Unterkühlung?',
      a: '<p>Frühzeichen sind Zittern, kalte Hände und Konzentrationsschwäche – dann sofort anhalten, trockene Schicht anziehen und Kalorien zuführen. Deutlich wird es bei starkem Zittern, unbeholfenen Bewegungen und undeutlicher Sprache: Fahrt beenden. Wenn das Zittern aufhört und Verwirrtheit einsetzt, ist es ein Notfall – 112 wählen.</p>',
    },
    {
      q: 'Ist es gefährlich, allein im Zelt zu übernachten?',
      a: '<p>Auf einem legalen Trekking- oder Campingplatz praktisch nicht. In Deutschland gibt es keine Tiere, die für einen Menschen im Zelt gefährlich sind. Die realen nächtlichen Störungen sind Mäuse an der Verpflegung und Geräusche, die harmloser sind als sie klingen. Ohrstöpsel helfen mehr als jede Ausrüstung.</p>',
    },
    {
      q: 'Wie schütze ich mein Rad vor Diebstahl?',
      a: '<p>Die wirksamste Maßnahme ist Sichtkontakt: Rad ans Fenster stellen, wenn du einkaufst. Dazu ein Faltschloss und die Gewohnheit, Ausweis, Karte, Bargeld und Handy in einer Hüfttasche am Körper zu tragen. Notiere vor der Tour die Rahmennummer und mach ein Foto – ohne beides ist eine Fahndung nach einem Diebstahl aussichtslos.</p>',
    },
  ],
  related: [
    { href: '/unterwegs/panne-beheben.html', label: 'Panne unterwegs beheben' },
    { href: '/unterwegs/allein-oder-gruppe.html', label: 'Allein, zu zweit oder in der Gruppe' },
    { href: '/routen/saison-wetter.html', label: 'Saison, Wetter & Jahreszeit' },
    { href: '/ausruestung/licht-strom.html', label: 'Licht, Strom & Powerbank' },
  ],
});
