# -*- coding: utf-8 -*-
"""Generator für die Reiseziel-Seiten von 'Zuhause in der Welt'."""
import os, html

# Ausgabeordner = übergeordneter Ordner dieses Skripts (reiseblog/)
OUT = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))

LOGO = ('<svg class="logo-mark" viewBox="0 0 32 32" aria-hidden="true">'
        '<circle cx="16" cy="16" r="15" fill="{c}"/>'
        '<path d="M16 6l3 7 7 .5-5.5 4.5 2 7-6.5-4-6.5 4 2-7L6 13.5 13 13z" fill="#D9744F"/></svg>')

FAVICON = ("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E"
           "%3Ccircle cx='16' cy='16' r='15' fill='%2314524E'/%3E"
           "%3Cpath d='M16 6l3 7 7 .5-5.5 4.5 2 7-6.5-4-6.5 4 2-7L6 13.5 13 13z' fill='%23D9744F'/%3E%3C/svg%3E")

def header(active):
    def cls(name): return ' class="active"' if name == active else ''
    return f'''<header class="site-header">
  <nav class="nav wrap" aria-label="Hauptnavigation">
    <a class="brand" href="index.html">
      {LOGO.format(c="#14524E")}
      <span>Zuhause in der Welt<small>Reiseblog &amp; Reisetipps</small></span>
    </a>
    <button class="nav-toggle" aria-label="Menü öffnen" aria-expanded="false" aria-controls="nav-links">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18" stroke-linecap="round"/></svg>
    </button>
    <ul class="nav-links" id="nav-links">
      <li><a href="index.html"{cls("start")}>Start</a></li>
      <li><a href="reiseziele.html"{cls("reiseziele")}>Reiseziele</a></li>
      <li><a href="reisetipps.html"{cls("reisetipps")}>Reisetipps</a></li>
      <li><a href="ausruestung.html"{cls("ausruestung")}>Ausrüstung</a></li>
      <li><a href="blog.html"{cls("blog")}>Blog</a></li>
      <li><a href="ueber-mich.html"{cls("ueber")}>Über mich</a></li>
      <li class="nav-cta"><a href="kontakt.html" class="btn btn--primary">Kontakt</a></li>
    </ul>
  </nav>
</header>'''

FOOTER = f'''<footer class="site-footer">
  <div class="wrap">
    <div class="footer-grid">
      <div class="footer-brand">
        <span class="brand">
          {LOGO.format(c="#1E6B64")}
          <span>Zuhause in der Welt<small>Reiseblog &amp; Reisetipps</small></span>
        </span>
        <p>Reisegeschichten, ehrliche Tipps und getestete Ausrüstung – für alle, die überall auf der Welt ein Stück Zuhause finden.</p>
        <div class="socials">
          <a href="https://www.instagram.com/zuhause_in_der_welt_2022/" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></a>
          <a href="mailto:hallo@deine-domain.de" aria-label="E-Mail"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg></a>
        </div>
      </div>
      <div>
        <h4>Entdecken</h4>
        <ul>
          <li><a href="reiseziele.html">Reiseziele</a></li>
          <li><a href="reisetipps.html">Reisetipps</a></li>
          <li><a href="ausruestung.html">Ausrüstung</a></li>
          <li><a href="blog.html">Blog</a></li>
        </ul>
      </div>
      <div>
        <h4>Über</h4>
        <ul>
          <li><a href="ueber-mich.html">Über mich</a></li>
          <li><a href="kontakt.html">Kontakt</a></li>
          <li><a href="https://www.instagram.com/zuhause_in_der_welt_2022/" target="_blank" rel="noopener">Instagram</a></li>
        </ul>
      </div>
      <div>
        <h4>Rechtliches</h4>
        <ul>
          <li><a href="impressum.html">Impressum</a></li>
          <li><a href="datenschutz.html">Datenschutz</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© <span data-year>2026</span> Zuhause in der Welt</span>
      <span>Mit ♥ und Fernweh gebaut. * = Affiliate-Link.</span>
    </div>
  </div>
</footer>'''

ICON = {
  "zeit": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
  "dauer": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 9h18M8 3v4M16 3v4"/></svg>',
  "budget": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v10M9.5 9.5a2.5 2 0 015 0c0 2.5-5 1.5-5 4a2.5 2 0 005 0"/></svg>',
  "typ": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 21s-7-6.1-7-11a7 7 0 0114 0c0 4.9-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>',
}

DISCLOSURE = ('<div class="disclosure">'
  '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 8h.01M11 12h1v4h1"/></svg>'
  '<div>Dieser Beitrag enthält Affiliate-Links (mit <strong>*</strong> markiert). Kaufst du darüber, unterstützt du diesen Blog mit einer kleinen Provision – ohne Mehrkosten für dich.</div></div>')

def recbox(tag, title, text):
    return (f'<div class="rec-box"><span class="tag tag--gold">{tag}</span>'
            f'<h4>{title}</h4><p>{text}</p>'
            f'<a class="btn btn--primary" href="#" rel="sponsored nofollow noopener" target="_blank">Auf Amazon ansehen&nbsp;*</a></div>')

# --- Icons für die Buchungs-Boxen ---
BICON = {
 "flug":'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M10 15l-6 2 1-3-4-3 7-1 4-7 2 1-1 6 6 4-1 2-6-3-3 6-2-1z" stroke-linejoin="round"/></svg>',
 "hotel":'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M3 18V8m0 10h18M3 8h13a5 5 0 015 5v5M3 12h18M7 9.5h3"/></svg>',
 "auto":'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M5 16l1.5-5A2 2 0 018.4 9.5h7.2a2 2 0 011.9 1.5L19 16M4 16h16v3h-2v-1H6v1H4zM7 13h10"/><circle cx="7.5" cy="16.5" r="1"/><circle cx="16.5" cy="16.5" r="1"/></svg>',
 "boot":'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 16h16l-2 4H6l-2-4zM6 16V8l6-3 6 3v8M12 5V3"/></svg>',
 "tour":'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 8l16-4-2 16-5-4-3 3-1-4-5-2 3-3z" stroke-linejoin="round"/></svg>',
 "schild":'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3z"/><path d="M9 12l2 2 4-4"/></svg>',
 "sim":'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M5 3h9l5 5v13a1 1 0 01-1 1H5a1 1 0 01-1-1V4a1 1 0 011-1z"/><rect x="8" y="12" width="8" height="6" rx="1"/></svg>',
 "zug":'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="6" y="4" width="12" height="13" rx="2"/><path d="M6 11h12M9 21l-2-2m8 2l2-2"/><circle cx="9" cy="14" r="1"/><circle cx="15" cy="14" r="1"/></svg>',
}

def bcard(icon, title, text, label):
    return (f'<div class="book-card"><div class="book-ico">{BICON[icon]}</div>'
            f'<h4>{title}</h4><p>{text}</p>'
            f'<a class="btn btn--ocean" href="#" rel="sponsored nofollow noopener" target="_blank">{label}&nbsp;*</a></div>')

def booking_section(d):
    b = BOOK[d["slug"]]
    cards = []
    cards.append(bcard("flug","Flüge finden",
        f'Beste Verbindungen nach {b["apt"]}. Preise vergleichen, Preiswecker setzen und flexibel buchen.',
        "Flüge vergleichen"))
    cards.append(bcard("hotel","Hotels &amp; Unterkünfte",
        f'{b["stay"]} Von günstig bis gehoben – hier findest du die passende Bleibe.',
        "Unterkünfte finden"))
    if b["car"]:
        cards.append(bcard("auto","Mietwagen",
            f'{b["move"]} Früh buchen und die Anbieter vergleichen lohnt sich.',
            "Mietwagen vergleichen"))
    else:
        cards.append(bcard("boot","Transfer &amp; Fortbewegung", b["move"], "Transfers ansehen"))
    cards.append(bcard("tour","Touren &amp; Aktivitäten",
        f'{b["tour"]} Vorab buchen spart Wartezeit und sichert die besten Slots.',
        "Touren ansehen"))
    cards.append(bcard("schild","Reiseversicherung",
        "Auslandskranken- und Reiserücktrittsversicherung – gerade auf Fernreisen unverzichtbar und schon ab wenigen Euro pro Reise.",
        "Versicherung vergleichen"))
    if b.get("eu"):
        cards.append(bcard("zug","Bahn, Fähre &amp; Nahverkehr",
            "Fähren, Regionalbusse und Bahn vorab buchen – so kommst du entspannt und günstig von A nach B.",
            "Tickets ansehen"))
    else:
        cards.append(bcard("sim","eSIM &amp; mobiles Internet",
            "Ab der Landung online ohne teures Roaming: eSIM vorab kaufen und sofort startklar sein.",
            "eSIM sichern"))
    grid = "\n    ".join(cards)
    return (f'<div class="book-section">\n'
            f'  <h2>Reise nach {d["name"]} buchen: meine Empfehlungen</h2>\n'
            f'  <p class="book-note">Diese Dienste nutze ich selbst für Planung und Buchung. Die mit <strong>*</strong> markierten Links sind Affiliate-Links – buchst du darüber, unterstützt du den Blog ohne Mehrkosten für dich.</p>\n'
            f'  <div class="book-grid">\n    {grid}\n  </div>\n'
            f'</div>')

# Buchungs-/Affiliate-Daten pro Ziel
BOOK = {
 "seychellen": {"apt":"Mahé (SEZ), meist via Dubai, Abu Dhabi oder Doha","stay":"Guesthouses („Self-Catering“) auf La Digue und Praslin, Resorts auf Mahé.","car":False,"move":"Auf den Inseln bewegst du dich per Fähre (Cat Cocos), Fahrrad auf La Digue und Taxi – ein Mietwagen lohnt nur auf Mahé.","tour":"Inselhopping per Fähre, Schnorcheltrip nach Curieuse und Bootstour zu den Marine-Parks."},
 "malediven": {"apt":"Malé (MLE)","stay":"Local-Island-Guesthouse (z. B. Maafushi) für kleines Budget oder Resortinsel mit Wasservilla.","car":False,"move":"Der Transfer zur Insel läuft per Speedboot oder Wasserflugzeug – unbedingt vorab mit der Unterkunft buchen.","tour":"Schnorcheltrips zu Mantarochen und Walhaien, Sandbank-Ausflug und Delfin-Tour bei Sonnenuntergang."},
 "namibia": {"apt":"Windhoek (WDH), oft via Johannesburg oder als Direktflug ab Frankfurt","stay":"Lodges und Campsites entlang der Route – von einfach bis luxuriös.","car":True,"move":"Ein 4x4 (gern mit Dachzelt) ist in Namibia praktisch Pflicht – plane zwei Reserveräder ein.","tour":"Geführte Pirschfahrt im Etosha, Sossusvlei bei Sonnenaufgang und optional eine Ballonfahrt über den Dünen."},
 "suedafrika": {"apt":"Kapstadt (CPT) oder Johannesburg (JNB)","stay":"Boutique-Hotels und Guesthouses in Kapstadt, Lodges rund um den Krüger.","car":True,"move":"Ein Mietwagen ist ideal für die Garden Route und die Selbstfahrer-Safari – die Straßen sind gut ausgebaut.","tour":"Kap-Halbinsel-Tour, Weintour durch Stellenbosch und geführte Safari im Krüger-Nationalpark."},
 "dubai": {"apt":"Dubai (DXB)","stay":"Hotels an Marina, Downtown oder Jumeirah Beach – enorme Auswahl in jeder Preisklasse.","car":False,"move":"Metro und Taxi bringen dich günstig überallhin – ein Mietwagen ist für einen Städtetrip meist unnötig.","tour":"Wüstensafari mit Dune Bashing, Burj Khalifa „At the Top“ und Dhow-Dinner-Cruise am Creek."},
 "thailand": {"apt":"Bangkok (BKK)","stay":"Hostels und Boutique-Hotels in den Städten, Bungalows und Resorts auf den Inseln.","car":False,"move":"Zwischen den Regionen fliegst du günstig inländisch oder fährst mit Nachtzug und Fähre; vor Ort Roller oder Grab-Taxi.","tour":"Kochkurs in Chiang Mai, ethisches Elefanten-Sanctuary und Inselhopping-Bootstour im Süden."},
 "usa-westkueste": {"apt":"San Francisco (SFO) oder Los Angeles (LAX)","stay":"Motels und Hotels entlang der Route – für Nationalpark-Lodges früh buchen.","car":True,"move":"Der Mietwagen (mit unbegrenzten Meilen) ist das Herzstück dieses Roadtrips – ohne geht hier nichts.","tour":"Alcatraz-Tour, Yosemite-Ausflug und ein Trip an den Grand Canyon ab Las Vegas."},
 "costa-rica": {"apt":"San José (SJO) oder Liberia (LIR)","stay":"Eco-Lodges im Regenwald und Boutique-Hotels an der Küste.","car":True,"move":"Ein Allrad-Mietwagen bringt dich sicher über die oft holprigen Pisten zu den schönsten Orten.","tour":"Hängebrücken am Arenal, geführte Nachtwanderung und Wildlife-Bootstour in Tortuguero."},
 "mexiko-yucatan": {"apt":"Cancún (CUN)","stay":"Boutique-Hotels in Mérida und Tulum, Strandhotels an der Riviera Maya.","car":True,"move":"Mit dem Mietwagen erreichst du auch abgelegene, ruhige Cenoten und Ruinen ganz flexibel.","tour":"Chichén Itzá früh am Morgen, Cenoten-Tour und Walhai-Schnorcheln ab Isla Holbox (im Sommer)."},
 "kreta": {"apt":"Heraklion (HER) oder Chania (CHQ)","stay":"Studios, Boutique-Hotels und Apartments in Chania oder Rethymno.","car":True,"move":"Ein Mietwagen ist auf Kreta fast Pflicht – nur so erreichst du Traumstrände und Bergdörfer entspannt.","tour":"Wanderung durch die Samaria-Schlucht, Bootstour zur Lagune von Balos und Knossos-Führung.","eu":True},
 "mallorca": {"apt":"Palma (PMI)","stay":"Fincas im Inselinneren, Boutique-Hotels in Palma und Strandhotels an den Buchten.","car":True,"move":"Mit dem Mietwagen erreichst du die versteckten Calas und die Panoramastraßen der Tramuntana.","tour":"Wanderung in der Serra de Tramuntana und Bootstour zu den schönsten Buchten.","eu":True},
 "sardinien": {"apt":"Olbia (OLB), Cagliari (CAG) oder Alghero (AHO)","stay":"Hotels, Agriturismi und B&Bs entlang der Küste.","car":True,"move":"Die schönsten Buchten liegen am Ende kleiner Küstenstraßen – ein Mietwagen ist Gold wert.","tour":"Bootstour zu den Traumbuchten am Golfo di Orosei und geführtes Schnorcheln.","eu":True},
 "portugal": {"apt":"Lissabon (LIS), Porto (OPO) oder Faro (FAO)","stay":"Boutique-Hotels in Lissabon und Porto, Strandhotels und Guesthouses an der Algarve.","car":True,"move":"Für die Algarve und das Douro-Tal ist ein Mietwagen ideal; Lissabon und Porto erkundest du zu Fuß und per Tram.","tour":"Kajaktour zur Benagil-Höhle, Bootsfahrt durchs Douro-Tal, Sintra-Ausflug und ein Fado-Abend.","eu":True},
 "sizilien": {"apt":"Catania (CTA) oder Palermo (PMO)","stay":"B&Bs und Boutique-Hotels in Taormina, Palermo, Cefalù und Ortigia.","car":True,"move":"Für die Inselrundfahrt ist ein Mietwagen ideal – fahre in Palermo aber defensiv.","tour":"Ätna-Wanderung mit Guide, Streetfood-Tour durch Palermo und Führung durchs Tal der Tempel.","eu":True},
}

def page(d, others):
    hl = "\n".join(f'    <li><strong>{h[0]}</strong> – {h[1]}</li>' for h in d["highlights"])
    tips = "\n".join(f'    <li>{t}</li>' for t in d["tips"])
    facts = "".join(
        f'<div class="fact"><div class="k">{ICON[k]}{lbl}</div><div class="v">{v}</div></div>'
        for k, lbl, v in [
            ("zeit","Beste Reisezeit", d["best"]),
            ("dauer","Empf. Dauer", d["dauer"]),
            ("budget","Budget", d["budget"]),
            ("typ","Reisetyp", d["typ"]),
        ])
    # zwei Empfehlungsboxen an sinnvollen Stellen
    rec1 = recbox(*d["rec1"])
    rec2 = recbox(*d["rec2"])
    # Galerie
    gal = ('<div class="gallery">'
           f'<div class="ph {d["g"][0]} wide" data-label="Dein Foto"></div>'
           f'<div class="ph {d["g"][1]}" data-label="Dein Foto"></div>'
           f'<div class="ph {d["g"][2]}" data-label="Dein Foto"></div>'
           f'<div class="ph {d["g"][0]}" data-label="Dein Foto"></div>'
           f'<div class="ph {d["g"][1]} wide" data-label="Dein Foto"></div>'
           '</div>')
    # "Related"-Karten: 3 andere Ziele
    rel_cards = "\n".join(
        f'''      <article class="card">
        <div class="ph {o["ph"]}" data-label="Foto: {o["name"]}"></div>
        <div class="card-body">
          <span class="tag">{o["region"]}</span>
          <h3><a href="reise-{o["slug"]}.html">{o["name"]}</a></h3>
          <p>{o["teaser"]}</p>
        </div>
      </article>''' for o in others)

    kulinarik = ""
    if d.get("food"):
        kulinarik = f'''
  <h2>Kulinarik: Das musst du probieren</h2>
  <p>{d["food"]}</p>'''

    extra = ""
    if d.get("extra"):
        extra = "\n".join(f'  <h2>{sec[0]}</h2>\n  <p>{sec[1]}</p>' for sec in d["extra"])

    return f'''<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{d["name"]}: {d["subtitle"]} | Zuhause in der Welt</title>
<meta name="description" content="{d["meta"]}">
<meta name="theme-color" content="#14524E">
<link rel="canonical" href="https://www.deine-domain.de/reise-{d["slug"]}.html">
<meta property="og:type" content="article">
<meta property="og:title" content="{d["name"]}: {d["subtitle"]}">
<meta property="og:description" content="{d["meta"]}">
<link rel="icon" href="{FAVICON}">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

{header("reiseziele")}

<div class="post-hero" style="background:linear-gradient(135deg,{d["c1"]},{d["c2"]});">
  <div class="wrap">
    <span class="eyebrow" style="color:var(--gold);">Reiseziel · {d["region"]}</span>
    <h1>{d["h1"]}</h1>
    <div class="post-meta"><span>{d["subtitle"]}</span><span>· {d["readmin"]} Min. Lesezeit</span></div>
  </div>
</div>

<div class="wrap">
  <div class="crumbs"><a href="index.html">Start</a> › <a href="reiseziele.html">Reiseziele</a> › {d["name"]}</div>
</div>

<article class="wrap narrow article" style="padding-bottom:60px;">

  {DISCLOSURE}

  <div class="facts">{facts}</div>

  <p class="lead">{d["intro"][0]}</p>
  <p>{d["intro"][1]}</p>

  <div class="ph {d["g"][0]}" data-label="Foto: {d["name"]}"></div>
  <p class="figure-cap">{d["cap"]}</p>

  <h2>Die schönsten Highlights</h2>
  <ul class="hl-list">
{hl}
  </ul>

  {rec1}

  <h2>Meine besten Tipps für {d["name"]}</h2>
  <ul>
{tips}
  </ul>

  <blockquote>{d["quote"]}</blockquote>
{kulinarik}
{extra}

  {gal}

  {rec2}

  <h2>Beste Reisezeit &amp; Anreise</h2>
  <p>{d["reisezeit"]}</p>

  {booking_section(d)}

  <h2>Fazit</h2>
  <p>{d["fazit"]}</p>

  <hr>
  <p><strong>Planst du selbst eine Reise nach {d["name"]}?</strong> Schreib mir deine Fragen über die <a href="kontakt.html">Kontaktseite</a> – ich helfe gern weiter. Meine komplette Packliste und Ausrüstung findest du unter <a href="ausruestung.html">Ausrüstung</a>.</p>

</article>

<section class="bg-cream">
  <div class="wrap">
    <div class="section-head"><h2>Weitere Reiseziele</h2></div>
    <div class="grid grid-3">
{rel_cards}
    </div>
    <p class="center mt-3"><a href="reiseziele.html" class="btn btn--ghost">Alle Reiseziele ansehen</a></p>
  </div>
</section>

{FOOTER}

<script src="assets/main.js"></script>
</body>
</html>
'''

# ---------------------------------------------------------------------------
#  DATEN – 14 Reiseziele
# ---------------------------------------------------------------------------
D = [
{
 "slug":"seychellen","name":"Seychellen","region":"Indischer Ozean","ph":"ph--3",
 "c1":"#0f6d6a","c2":"#2C9C9C","g":["ph--3","ph--8","ph--5"],
 "subtitle":"Trauminseln im Indischen Ozean","readmin":8,
 "h1":"Seychellen – wo Postkarten wahr werden",
 "meta":"Seychellen-Reise: Mahé, Praslin und La Digue mit den schönsten Stränden der Welt, Riesenschildkröten und Insider-Tipps.",
 "teaser":"Türkises Wasser, Granitfelsen und die schönsten Strände der Welt.",
 "best":"April–Mai &amp; Okt–Nov","dauer":"10–14 Tage","budget":"€€€ gehoben","typ":"Strand &amp; Natur",
 "intro":[
   "Die Seychellen sind der Inbegriff von Paradies: 115 Inseln, auf denen sich runde Granitfelsen, puderweißer Sand und leuchtend türkises Wasser zu Bildern verbinden, die fast zu perfekt wirken. Wir haben die drei Hauptinseln Mahé, Praslin und La Digue kombiniert – und würden es genau so wieder machen.",
   "Das Schöne: Trotz Luxus-Ruf lassen sich die Seychellen auch entspannt als Inselhopping erleben. Fähren verbinden die Hauptinseln, auf La Digue fährt man mit dem Fahrrad, und der berühmte Strand Anse Source d’Argent ist einfach für alle da."],
 "cap":"Anse Source d’Argent auf La Digue – der wohl meistfotografierte Strand der Welt.",
 "highlights":[
   ["Anse Source d’Argent (La Digue)","Der ikonische Strand mit den runden Granitfelsen – am schönsten bei Ebbe und im weichen Morgenlicht."],
   ["Vallée de Mai (Praslin)","UNESCO-Urwald und Heimat der legendären Coco de Mer, der größten Nuss der Welt."],
   ["Anse Lazio &amp; Anse Georgette","Zwei Traumbuchten auf Praslin, die es regelmäßig in die Weltranglisten schaffen."],
   ["Riesenschildkröten","Auf Curieuse Island und La Digue begegnest du den bis zu 100 Jahre alten Aldabra-Schildkröten."],
   ["Inselhopping per Fähre","Mahé → Praslin → La Digue lässt sich unkompliziert mit dem Cat Cocos / der Fähre kombinieren."],
 ],
 "tips":[
   "Miete auf La Digue ein Fahrrad – Autos gibt es praktisch keine, und so entdeckst du versteckte Buchten.",
   "Schnorchelausrüstung selbst mitbringen: Die Hausriffe sind bunt, aber Verleih ist teuer.",
   "Buche Fähren vorab online, gerade in der Hauptsaison sind sie schnell ausgebucht.",
   "Plane einen Mix aus Resort und Guesthouse (auf den Seychellen „Self-Catering“) – das spart spürbar Budget.",
 ],
 "quote":"Als wir bei Ebbe allein zwischen den warmen Granitfelsen standen, verstanden wir, warum die Seychellen für so viele der schönste Ort der Welt sind.",
 "food":"Kreolische Küche pur: Fisch-Curry mit Kokosmilch, gegrillter Red Snapper, Ladob (Kochbananen in Kokos) und der allgegenwärtige Chili-Dip. Dazu ein kühles SeyBrew-Bier am Strand.",
 "rec1":["Für glasklares Wasser","Schnorchelset mit Tauchmaske","Auf den Seychellen zählt jedes Hausriff – mit einem eigenen, gut sitzenden Schnorchelset verpasst du keinen Rochen und keine Schildkröte und musst dir nichts leihen."],
 "rec2":["Haut &amp; Riff schützen","Reef-safe Sonnencreme","In vielen Meeresschutzgebieten ist herkömmliche Sonnencreme tabu. Eine riff-freundliche Creme mit hohem LSF schützt dich und die Korallen."],
 "reisezeit":"Die Seychellen sind ganzjährig warm (26–30 °C). Am angenehmsten sind die Übergangsmonate April/Mai und Oktober/November, wenn zwischen den Monsunen wenig Wind und ruhige See herrschen. Anreise meist über Abu Dhabi, Dubai oder Doha nach Mahé.",
 "fazit":"Die Seychellen sind teuer – aber jeden Cent wert. Wer Strände liebt und Natur sucht, findet hier den vielleicht schönsten Fleck der Erde. Unser Tipp: die drei Hauptinseln kombinieren, statt nur in einem Resort zu bleiben.",
},
{
 "slug":"malediven","name":"Malediven","region":"Indischer Ozean","ph":"ph--8",
 "c1":"#0e5a7a","c2":"#2C9C9C","g":["ph--8","ph--3","ph--1"],
 "subtitle":"Atolle, Overwater-Villen &amp; Mantas","readmin":8,
 "h1":"Malediven – Leben über und unter Wasser",
 "meta":"Malediven-Reise: Overwater-Villen, das beste Schnorcheln und Tauchen, Mantarochen und Walhaie – plus günstiger Local-Island-Tipp.",
 "teaser":"Overwater-Villen, Hausriffe voller Leben und Sonnenuntergänge zum Verlieben.",
 "best":"November–April","dauer":"7–10 Tage","budget":"€€–€€€€","typ":"Insel &amp; Tauchen",
 "intro":[
   "Kaum ein Ort steht so sehr für Fernweh wie die Malediven: 26 Atolle, Hunderte winziger Inseln und eine Unterwasserwelt, die zu den besten der Erde zählt. Wir sind mit Schnorchel und Kamera abgetaucht – und immer wieder direkt vom Steg unserer Villa ins Hausriff gesprungen.",
   "Ein Mythos hält sich hartnäckig: Malediven müsse man sich nicht leisten können. Dank der „Local Islands“, auf denen man in Gästehäusern wohnt, geht es heute erstaunlich günstig – ganz ohne auf Traumstrände zu verzichten."],
 "cap":"Vom Steg der Wasservilla direkt ins türkise Hausriff – der Malediven-Klassiker.",
 "highlights":[
   ["Overwater-Villa","Einmal über dem Wasser aufwachen, Fische unter dem Glasboden – ein Erlebnis, das man nie vergisst."],
   ["Schnorcheln am Hausriff","Viele Inseln haben ein intaktes Riff direkt vor der Tür – Schildkröten und Riffhaie inklusive."],
   ["Mantarochen &amp; Walhaie","Im Baa-Atoll (Hanifaru Bay) und Süd-Ari-Atoll gleitest du neben den sanften Riesen."],
   ["Sandbank-Picknick","Nur du, eine winzige Sandbank mitten im Ozean und 360° Türkis."],
   ["Local Islands","Auf Maafushi &amp; Co. erlebst du maledivischen Alltag – deutlich günstiger als im Resort."],
 ],
 "tips":[
   "Local Island statt Resort spart bis zu 70 % – Ausflüge zum Schnorcheln buchst du vor Ort.",
   "Bring dein eigenes Schnorchelset und eine wasserdichte Handyhülle mit.",
   "Auf Local Islands gilt lokale Kleiderordnung – Bikini nur an ausgewiesenen „Bikini Beaches“.",
   "Der Transfer zur Insel (Speedboot oder Wasserflugzeug) kostet extra – vorher einplanen.",
 ],
 "quote":"Als der erste Mantarochen lautlos unter uns hinwegglitt, hielten wir beide die Luft an – so groß, so elegant, so nah.",
 "food":"Maledivische Küche dreht sich um Fisch und Kokos: Mas Huni zum Frühstück (Thunfisch, Kokos, Zwiebel mit Roshi-Fladen), würzige Fisch-Currys und frische Kokosnuss direkt vom Baum.",
 "rec1":["Unterwasser-Erinnerungen","Wasserdichte Action-Cam","Ob Mantas, Schildkröten oder das bunte Hausriff – mit einer robusten Action-Cam hältst du die Unterwasserwelt gestochen scharf fest, ganz ohne teures Kamera-Gehäuse."],
 "rec2":["Immer griffbereit","Schwimmende Handyhülle","Vom Steg ins Wasser, Foto machen, weiterschnorcheln: Eine wasserdichte, schwimmfähige Hülle rettet dein Handy und macht trotzdem tolle Bilder."],
 "reisezeit":"Beste Reisezeit ist die Trockenzeit von November bis April mit wenig Regen und guter Sicht unter Wasser. Für Mantas und Walhaie sind Mai bis November im Baa-Atoll top. Anreise über Male, dann per Speedboot oder Wasserflugzeug.",
 "fazit":"Die Malediven sind Wellness für die Seele – und dank Local Islands längst kein reines Luxusziel mehr. Wer das Meer und seine Bewohner liebt, wird hier restlos glücklich.",
},
{
 "slug":"namibia","name":"Namibia","region":"Afrika","ph":"ph--4",
 "c1":"#9a4a1e","c2":"#D9744F","g":["ph--4","ph--2","ph--7"],
 "subtitle":"Wüste, Weite &amp; wilde Tiere","readmin":10,
 "h1":"Namibia – Roadtrip durch endlose Weite",
 "meta":"Namibia Selbstfahrer-Reise: rote Dünen von Sossusvlei, Safari im Etosha, Swakopmund und die schönste Route für deinen Roadtrip.",
 "teaser":"Rote Dünen, Sternenhimmel und Safari – Afrika für Selbstfahrer.",
 "best":"Mai–Oktober","dauer":"14–21 Tage","budget":"€€ mittel","typ":"Roadtrip &amp; Safari",
 "intro":[
   "Namibia ist das perfekte Einsteigerland für Afrika – und gleichzeitig eines der spektakulärsten. Endlose Schotterpisten, an denen stundenlang niemand entgegenkommt, die höchsten Dünen der Welt und Nächte unter einem Sternenhimmel, wie wir ihn nie zuvor gesehen haben.",
   "Wir sind mit dem 4x4 und Dachzelt selbst gefahren – die wohl beste Art, dieses Land zu erleben. Sicher, gut organisiert und trotzdem voller Abenteuer. Von den roten Dünen bis zum tierreichen Etosha-Nationalpark."],
 "cap":"Deadvlei bei Sossusvlei – abgestorbene Bäume vor der höchsten Düne der Welt.",
 "highlights":[
   ["Sossusvlei &amp; Deadvlei","Die berühmten roten Dünen. „Big Daddy“ erklimmen und im Morgenlicht ins skurrile Deadvlei blicken."],
   ["Etosha-Nationalpark","Safari im eigenen Auto: Elefanten, Nashörner, Löwen und Giraffen an den Wasserlöchern."],
   ["Swakopmund","Deutsche Kolonialarchitektur trifft auf Atlantik – wo die Wüste ins Meer stürzt."],
   ["Sesriem Canyon &amp; Sternenhimmel","Namibia hat einige der dunkelsten Nachthimmel der Welt – ein Fest für Astrofotografen."],
   ["Damaraland","Wüstenelefanten, uralte Felsgravuren und dramatische Landschaften abseits der Massen."],
 ],
 "tips":[
   "Nimm ein 4x4 mit zwei Reservereifen – Reifenpannen auf Schotter sind normal.",
   "Tanke, wann immer du kannst: Zwischen den Orten liegen oft hunderte Kilometer.",
   "Starte früh: Bei Sonnenaufgang sind die Dünen kühl, leer und am schönsten.",
   "Campingplätze und Lodges früh buchen – die guten sind in der Hochsaison voll.",
   "Fahre auf Schotter defensiv (max. 80 km/h) – die meisten Unfälle passieren durch Übermut.",
 ],
 "quote":"Oben auf der Düne, als die Sonne aufging und die Welt in Rot und Gold tauchte, war es vollkommen still. Nur der Wind und wir.",
 "food":"Namibia liebt Fleisch: Zartes Oryx-, Kudu- oder Springbock-Steak vom Grill (Braai), dazu deutsche Einflüsse wie frisches Brot und kühles Bier nach deutschem Reinheitsgebot.",
 "rec1":["Tiere ganz nah","Kompaktes Fernglas","Im Etosha entscheidet ein gutes Fernglas darüber, ob du den Löwen im Schatten entdeckst oder nicht. Leicht, robust und der wichtigste Begleiter auf jeder Safari."],
 "rec2":["Staub &amp; Sonne","Faltbarer Wasserkanister &amp; Kopfbedeckung","Auf langen Etappen ohne Versorgung sind Wasservorrat und Sonnenschutz Pflicht. Ein faltbarer Kanister spart Platz, bis du ihn brauchst."],
 "reisezeit":"Ideal ist die Trockenzeit von Mai bis Oktober: angenehme Temperaturen und beste Tierbeobachtung, weil sich die Tiere an den Wasserlöchern sammeln. Anreise über Windhoek (oft via Johannesburg oder Frankfurt-Direktflug).",
 "fazit":"Namibia ist Freiheit auf vier Rädern. Für alle, die Weite, Wüste und Wildtiere lieben und sich einen selbst geplanten Roadtrip zutrauen, gibt es kaum ein lohnenderes Ziel.",
},
{
 "slug":"suedafrika","name":"Südafrika","region":"Afrika","ph":"ph--5",
 "c1":"#1e5a3c","c2":"#3C6E7F","g":["ph--5","ph--1","ph--4"],
 "subtitle":"Kapstadt, Garden Route &amp; Safari","readmin":10,
 "h1":"Südafrika – eine Welt in einem Land",
 "meta":"Südafrika-Reise: Kapstadt &amp; Tafelberg, Garden Route, Weinregion Stellenbosch und Safari im Krüger-Nationalpark. Route, Tipps &amp; Highlights.",
 "teaser":"Tafelberg, Garden Route, Pinguine und die Big Five.",
 "best":"Okt–April (Küste) · Mai–Sep (Safari)","dauer":"14–21 Tage","budget":"€€ mittel","typ":"Roadtrip &amp; Safari",
 "intro":[
   "Südafrika packt gefühlt mehrere Reisen in eine: pulsierende Metropole, dramatische Küstenstraßen, Weinberge wie in Europa und eine der besten Safaris der Welt. Wir haben Kapstadt, die Garden Route und den Krüger zu einer großen Runde verbunden.",
   "Was uns überrascht hat: wie einfach das Land für Selbstfahrer ist. Gute Straßen, gute Infrastruktur – und trotzdem das Gefühl, echtes Abenteuer zu erleben. Von Pinguinen am Strand bis zum Löwenrudel im Busch."],
 "cap":"Der Tafelberg über Kapstadt – am besten früh hinauf, bevor die „Tischdecke“ aus Wolken kommt.",
 "highlights":[
   ["Kapstadt &amp; Tafelberg","Auffahrt mit der Seilbahn, dazu Bo-Kaap, V&amp;A Waterfront und Sonnenuntergänge am Signal Hill."],
   ["Kap der Guten Hoffnung","Wo sich zwei Ozeane treffen – dramatische Klippen und Paviane am Straßenrand."],
   ["Boulders Beach","Eine ganze Kolonie afrikanischer Pinguine watschelt hier über den Strand."],
   ["Garden Route","Traumhafte Küstenstraße von Mossel Bay bis Storms River mit Lagunen und Wäldern."],
   ["Weinland Stellenbosch &amp; Franschhoek","Weltklasse-Weine in kolonialer Kulisse – der Wine Tram ist ein Erlebnis."],
   ["Krüger-Nationalpark","Big Five im eigenen Auto oder auf geführter Pirschfahrt – Safari vom Feinsten."],
 ],
 "tips":[
   "Fahre den Tafelberg früh am Tag an – Wind und Wolken schließen die Seilbahn oft ab Mittag.",
   "Für den Krüger im Selbstfahrer: SANParks-Camps früh buchen und zur Torzeit-Öffnung starten.",
   "In Kapstadt abends auf sichere Viertel achten und Uber statt Fußweg nehmen.",
   "Plane die Weinregion mit dem Wine Tram oder Fahrer – dann kannst du unbeschwert verkosten.",
 ],
 "quote":"Ein Löwenrudel im ersten Morgenlicht, keine 20 Meter entfernt – Gänsehaut, die wir nie vergessen werden.",
 "food":"Vom Braai (Grillen ist hier Kultur) über Boerewors-Würste und Bunny Chow bis zu Weltklasse-Weinen und frischen Meeresfrüchten an der Küste. Probiere unbedingt Biltong als Snack für unterwegs.",
 "rec1":["Safari-Blick","Fernglas mit 10-facher Vergrößerung","Im Krüger macht ein gutes Fernglas den Unterschied zwischen „irgendwas Braunes“ und einem Leoparden im Baum. Für uns das wichtigste Safari-Gepäckstück."],
 "rec2":["Reise-Know-how","Reiseführer Südafrika","Ein guter Reiseführer bündelt Routen, Camp-Infos und Geheimtipps – gerade für die Selbstfahrer-Planung Gold wert und offline immer dabei."],
 "reisezeit":"Für Kapstadt und die Garden Route ist der Südsommer (Oktober–April) ideal. Für die Safari im Krüger ist die Trockenzeit (Mai–September) besser, weil die Tiere leichter zu sehen sind. Anreise über Kapstadt oder Johannesburg.",
 "fazit":"Südafrika bietet unglaublich viel fürs Geld: Stadt, Küste, Wein und Safari in einer Reise. Für uns eines der abwechslungsreichsten Länder überhaupt – und ideal für die erste große Fernreise.",
},
{
 "slug":"dubai","name":"Dubai","region":"Naher Osten","ph":"ph--7",
 "c1":"#8a6f22","c2":"#C9A24B","g":["ph--7","ph--2","ph--8"],
 "subtitle":"Superlative, Wüste &amp; Souks","readmin":7,
 "h1":"Dubai – Zukunft trifft Wüste",
 "meta":"Dubai-Reise: Burj Khalifa, Wüstensafari, Souks und die besten Tipps für einen Stopover oder Städtetrip in die Metropole am Golf.",
 "teaser":"Wolkenkratzer, Wüstensafari und Luxus zwischen den Superlativen.",
 "best":"November–März","dauer":"3–5 Tage","budget":"€€–€€€€","typ":"Städtetrip &amp; Stopover",
 "intro":[
   "Dubai ist ein Ort der Superlative: das höchste Gebäude der Welt, künstliche Inseln in Palmenform, Skihalle in der Wüste. Wir haben die Stadt als Stopover erlebt – und waren überrascht, wie gut sich futuristische Skyline und altes Arabien verbinden lassen.",
   "Zwischen Burj Khalifa und Dünenmeer, zwischen Gold-Souk und Rooftop-Bar zeigt Dubai zwei Gesichter. Ein perfekter Zwischenstopp auf dem Weg nach Asien oder Afrika – oder ein kompakter Städtetrip für sich."],
 "cap":"Die Skyline mit dem Burj Khalifa – am eindrucksvollsten zur blauen Stunde.",
 "highlights":[
   ["Burj Khalifa","828 Meter hoch – die Aussichtsplattform „At the Top“ zum Sonnenuntergang ist ein Muss."],
   ["Wüstensafari","Dune Bashing im Jeep, Sonnenuntergang über den Dünen und Abendessen im Beduinencamp."],
   ["Alt-Dubai &amp; Souks","Mit dem Abra über den Creek, durch Gold- und Gewürz-Souk – das ursprüngliche Dubai."],
   ["Palm Jumeirah &amp; Marina","Die künstliche Palmeninsel und die glitzernde Marina-Promenade."],
   ["Dubai Mall &amp; Fountains","Shopping-Gigant mit Aquarium, dazu die Wasser-Show vor dem Burj Khalifa."],
 ],
 "tips":[
   "Burj-Khalifa-Tickets online vorbuchen – der Sonnenuntergangs-Slot ist schnell weg und günstiger im Voraus.",
   "Kleide dich in Malls und Moscheen respektvoll (Schultern und Knie bedeckt).",
   "Nutze die Metro – günstig, klimatisiert und erstaunlich praktisch für die Hauptachsen.",
   "Wüstensafari am späten Nachmittag buchen: kühler, schönes Licht, Sonnenuntergang inklusive.",
 ],
 "quote":"Oben auf dem Burj Khalifa, als unter uns die Lichter der Wüstenmetropole angingen, fühlte sich die Zukunft plötzlich ganz nah an.",
 "food":"Von arabischen Mezze, Shawarma und frischem Hummus bis zur internationalen Fine-Dining-Szene. Ein Karak-Chai am Straßenstand kostet fast nichts und schmeckt herrlich.",
 "rec1":["Hitze im Griff","Isolierte Trinkflasche","Bei über 30 °C ist Trinken Pflicht. Eine isolierte Edelstahlflasche hält dein Wasser stundenlang kühl – in Dubai unbezahlbar."],
 "rec2":["Wüste &amp; Skyline","Reise-Weitwinkel-Objektiv / Handy-Gimbal","Ob Skyline oder Dünen: Mit einem Gimbal werden deine Videos wackelfrei und die Weitwinkel-Aufnahmen wirken doppelt beeindruckend."],
 "reisezeit":"Ideal von November bis März, wenn die Temperaturen angenehm sind. Im Hochsommer wird es mit über 40 °C sehr heiß. Dubai ist ein perfekter Stopover – Emirates &amp; Co. verbinden die Stadt mit der halben Welt.",
 "fazit":"Dubai polarisiert – aber als Stopover oder kompakter Städtetrip ist die Stadt ein Erlebnis. Unser Tipp: Superlative genießen, aber auch das alte Dubai am Creek nicht verpassen.",
},
{
 "slug":"thailand","name":"Thailand","region":"Asien","ph":"ph--1",
 "c1":"#1e6b64","c2":"#3C9C7A","g":["ph--1","ph--7","ph--5"],
 "subtitle":"Tempel, Inseln &amp; Streetfood","readmin":9,
 "h1":"Thailand – Lächeln, Tempel und Traumstrände",
 "meta":"Thailand-Reise: Bangkok, Chiang Mai und die schönsten Inseln. Route, Streetfood-Tipps, beste Reisezeit und Insider-Empfehlungen.",
 "teaser":"Goldene Tempel, türkise Inseln und das beste Streetfood der Welt.",
 "best":"November–Februar","dauer":"14–21 Tage","budget":"€ günstig","typ":"Backpacking &amp; Strand",
 "intro":[
   "Thailand ist für viele die erste große Asien-Reise – und das aus gutem Grund: einfach zu bereisen, unglaublich gastfreundlich und mit einer Vielfalt, die von Millionenstadt über Bergdschungel bis Traumstrand reicht. Wir haben Norden und Süden zu einer Rundreise verbunden.",
   "Das Beste: Thailand ist günstig, ohne billig zu wirken. Für wenig Geld schläfst du gut, isst großartig und erlebst Momente, die hängenbleiben – vom Sonnenaufgang über Tempeldächern bis zum Longtail-Boot zwischen Kalksteinfelsen."],
 "cap":"Longtail-Boote vor den Kalksteinfelsen der Andamanensee – der Süden Thailands.",
 "highlights":[
   ["Bangkok","Großer Palast, Wat Arun, schwimmende Märkte und Streetfood in der Chinatown – Reizüberflutung im besten Sinn."],
   ["Chiang Mai","Über 300 Tempel, Bergvölker, Kochkurse und ethische Elefanten-Auffangstationen."],
   ["Inseln der Andamanensee","Krabi, Koh Lanta und Koh Phi Phi mit türkisem Wasser und dramatischen Felsen."],
   ["Golf von Thailand","Koh Samui, Koh Phangan und das Taucherparadies Koh Tao."],
   ["Ayutthaya","Die alte Königsstadt mit ihren Tempelruinen – ein perfekter Tagesausflug ab Bangkok."],
 ],
 "tips":[
   "Iss beim Streetfood-Stand mit der längsten Einheimischen-Schlange – dort ist es frisch und lecker.",
   "Wähle für Elefanten nur echte Schutzzentren ohne Reiten (Stichwort „Sanctuary“).",
   "Kleide dich in Tempeln bedeckt – Schultern und Knie verhüllt, Schuhe aus.",
   "Nord und Süd haben unterschiedliche Regenzeiten – plane die Route danach.",
   "Für Inselhopping Fähren und Nachtzüge früh buchen, besonders um Neujahr.",
 ],
 "quote":"Ein Teller Pad Thai am Straßenstand für ein paar Baht, dazu das Lächeln der Köchin – Thailand macht das Reisen einfach leicht.",
 "food":"Das Paradies für Foodies: Pad Thai, grünes und rotes Curry, Mango Sticky Rice, Tom Yum und Som Tam. Ein Kochkurs in Chiang Mai gehört zu den besten Souvenirs überhaupt.",
 "rec1":["Nachts unterwegs","Reise-Moskitoschutz","Im Dschungel und auf den Inseln sind Mücken allgegenwärtig. Ein hautverträgliches Anti-Mücken-Mittel (mit ausreichend Wirkstoff) gehört in jeden Thailand-Rucksack."],
 "rec2":["Alles dabei","Leichter Tagesrucksack","Für Tempeltouren, Inselausflüge und Streetfood-Runden ist ein leichter, faltbarer Daypack ideal – Platz für Wasser, Kamera und den obligatorischen Sonnenschirm."],
 "reisezeit":"Beste Reisezeit ist die Trockenzeit von November bis Februar (angenehm warm, wenig Regen). Die Andamanenküste und der Golf haben leicht versetzte Regenzeiten – das lässt sich clever kombinieren. Anreise über Bangkok.",
 "fazit":"Thailand ist der perfekte Mix aus Kultur, Strand und Kulinarik – und dabei erschwinglich. Für Einsteiger wie erfahrene Reisende ein Ziel, das man immer wieder besuchen kann.",
},
{
 "slug":"usa-westkueste","name":"USA Westküste","region":"Nordamerika","ph":"ph--2",
 "c1":"#b5541f","c2":"#D9744F","g":["ph--2","ph--4","ph--7"],
 "subtitle":"Roadtrip von San Francisco bis Las Vegas","readmin":11,
 "h1":"USA Westküste – der große Roadtrip",
 "meta":"USA Westküste Roadtrip: San Francisco, Highway 1, Los Angeles, Nationalparks, Grand Canyon und Las Vegas. Route, Stopps und Tipps.",
 "teaser":"Highway 1, Nationalparks, Neonlichter – der ultimative Roadtrip.",
 "best":"April–Juni &amp; Sep–Okt","dauer":"14–21 Tage","budget":"€€€ gehoben","typ":"Roadtrip",
 "intro":[
   "Der Westen der USA ist der Roadtrip schlechthin: endlose Highways, gigantische Nationalparks und Städte, die man aus tausend Filmen kennt. Wir haben eine große Schleife von San Francisco über den Highway 1 nach Los Angeles und durch die Wüste bis Las Vegas und zum Grand Canyon gedreht.",
   "Was diese Reise so besonders macht, ist die Vielfalt: An einem Tag stehst du an schroffen Pazifikklippen, am nächsten in einer Mammutbaum-Kathedrale und wenig später in der glühenden Wüste. Ein Auto, unendlich viele Kulissen."],
 "cap":"Der Highway 1 entlang der kalifornischen Küste – eine der schönsten Straßen der Welt.",
 "highlights":[
   ["San Francisco","Golden Gate Bridge, Cable Cars, Alcatraz und die bunten Häuser – die vielleicht europäischste US-Stadt."],
   ["Highway 1 &amp; Big Sur","Die legendäre Küstenstraße mit der Bixby Bridge und Robben an den Stränden."],
   ["Yosemite-Nationalpark","Granitgiganten wie El Capitan und Half Dome, Wasserfälle und Mammutbäume."],
   ["Los Angeles &amp; San Diego","Hollywood, Santa Monica Pier, Venice Beach und entspanntes Strandleben."],
   ["Death Valley &amp; Route in die Wüste","Der heißeste Ort der Erde – surreale Salzflächen und Farbwüsten."],
   ["Las Vegas &amp; Grand Canyon","Neonlichter, Shows – und dann die überwältigende Weite des Grand Canyon."],
 ],
 "tips":[
   "Miete einen Mietwagen mit unbegrenzten Meilen und plane genug Fahrpausen ein – die Distanzen sind riesig.",
   "Für Nationalparks lohnt sich der „America the Beautiful“-Jahrespass ab dem dritten Park.",
   "Beliebte Parks (Yosemite) brauchen teils Zeitfenster-Reservierungen – vorher prüfen.",
   "Tanken in der Wüste, wann immer möglich – und immer genug Wasser dabei haben.",
   "Hotels in Nationalpark-Nähe früh buchen, sie sind Monate im Voraus ausgebucht.",
 ],
 "quote":"Als wir zum ersten Mal am Rand des Grand Canyon standen, verschlug es uns die Sprache – kein Foto wird dieser Weite je gerecht.",
 "food":"In-N-Out Burger als Roadtrip-Ritual, Clam Chowder in San Francisco, Tacos in L.A. und riesige Frühstücks-Pancakes im Diner. Portionen sind amerikanisch – also groß.",
 "rec1":["Immer navigiert","Handy-Halterung &amp; KFZ-Ladegerät","Auf dem Roadtrip läuft alles übers Handy: Navigation, Musik, Playlists. Eine stabile Halterung und ein starkes Ladegerät sind Pflichtausstattung fürs Mietauto."],
 "rec2":["Große Distanzen","Reise-Kühltasche","Zwischen zwei Nationalparks liegen oft Stunden ohne Supermarkt. Eine faltbare Kühltasche hält Wasser und Snacks frisch – Gold wert im Death Valley."],
 "reisezeit":"Am angenehmsten sind Frühling (April–Juni) und Herbst (September–Oktober): mildes Klima und weniger Andrang. Der Hochsommer bringt in den Wüsten extreme Hitze. Anreise über San Francisco oder Los Angeles.",
 "fazit":"Die Westküste ist der Traum-Roadtrip für alle, die Weite, Natur und Großstadt in einer Reise wollen. Plane genug Zeit ein – die Distanzen sind größer, als die Karte vermuten lässt.",
},
{
 "slug":"costa-rica","name":"Costa Rica","region":"Mittelamerika","ph":"ph--5",
 "c1":"#1e6b4a","c2":"#3C9C7A","g":["ph--5","ph--1","ph--3"],
 "subtitle":"Vulkane, Regenwald &amp; Pura Vida","readmin":9,
 "h1":"Costa Rica – Pura Vida im Dschungel",
 "meta":"Costa Rica Rundreise: Vulkan Arenal, Nebelwald Monteverde, Faultiere und Traumstrände an Pazifik und Karibik. Route &amp; Tipps.",
 "teaser":"Faultiere, Vulkane, Nebelwald und das Lebensgefühl Pura Vida.",
 "best":"Dezember–April","dauer":"14–18 Tage","budget":"€€ mittel","typ":"Natur &amp; Abenteuer",
 "intro":[
   "Costa Rica ist ein Naturwunder auf kleinem Raum: Auf gerade einmal der Fläche Niedersachsens leben rund fünf Prozent aller bekannten Tier- und Pflanzenarten der Erde. Zwischen dampfenden Vulkanen und dichtem Regenwald hängt überall dieses eine Lebensgefühl in der Luft – Pura Vida.",
   "Wir sind von Vulkan zu Nebelwald zu Küste gereist und haben mehr Tiere gesehen als je zuvor: Faultiere, Tukane, Brüllaffen, Kolibris. Ein Land, das Abenteuer und Entschleunigung perfekt verbindet."],
 "cap":"Ein Faultier hoch in den Baumwipfeln – Geduld beim Suchen wird hier belohnt.",
 "highlights":[
   ["Vulkan Arenal &amp; La Fortuna","Perfekter Vulkankegel, heiße Quellen und Hängebrücken durch den Regenwald."],
   ["Monteverde Nebelwald","Auf Baumwipfelpfaden durch die Wolken – Heimat des seltenen Quetzals."],
   ["Manuel Antonio","Nationalpark, in dem Faultiere und Affen bis an den Traumstrand kommen."],
   ["Tortuguero","Das „Amazonas von Costa Rica“ – per Boot durch Kanäle, Schildkröten legen hier ihre Eier."],
   ["Nicoya &amp; Pazifikstrände","Surferorte wie Santa Teresa und Nosara mit Sonnenuntergängen zum Niederknien."],
 ],
 "tips":[
   "Miete einen Allrad-Wagen – viele der schönsten Orte liegen an holprigen Pisten.",
   "Nimm einen lokalen Guide für Wildtiere: Sie entdecken Faultiere, die du nie sehen würdest.",
   "Rechne mit Regen, auch in der Trockenzeit – eine gute Regenjacke ist Pflicht.",
   "Plane Fahrzeiten großzügig: Kurze Distanzen dauern auf kurvigen Straßen lang.",
   "Respektiere die Tierwelt – nicht füttern, Abstand halten, Pura Vida eben.",
 ],
 "quote":"Als ein Tukan direkt über unserem Frühstückstisch landete, wussten wir: Hier lebt der Dschungel wirklich mit dir.",
 "food":"Bodenständig und lecker: Gallo Pinto (Reis mit Bohnen) zum Frühstück, Casado als Mittagsteller, frische Tropenfrüchte und der beste Kaffee der Region direkt von der Finca.",
 "rec1":["Wildtiere entdecken","Fernglas mit Nahfokus","Faultiere, Tukane, Kolibris – ohne Fernglas verpasst du die Hälfte. Ein leichtes Modell mit guter Naheinstellung ist im Regenwald unverzichtbar."],
 "rec2":["Trocken bleiben","Wasserdichte Packsäcke (Dry Bags)","Ob Bootstour in Tortuguero oder Regenguss im Nebelwald: Dry Bags schützen Kamera, Handy und Kleidung zuverlässig vor Nässe."],
 "reisezeit":"Beste Reisezeit ist die Trockenzeit von Dezember bis April. Die grüne (Regen-)Zeit ab Mai ist günstiger und üppig grün, mit meist nur nachmittäglichen Schauern. Anreise über San José (SJO) oder Liberia (LIR).",
 "fazit":"Costa Rica ist ein Paradies für Naturliebhaber und alle, die entschleunigen wollen. Nachhaltiger Tourismus wird hier großgeschrieben – und Pura Vida bleibt lange nach der Reise im Kopf.",
},
{
 "slug":"mexiko-yucatan","name":"Mexiko (Yucatán)","region":"Mittelamerika","ph":"ph--8",
 "c1":"#0e6a7a","c2":"#2C9C9C","g":["ph--8","ph--3","ph--7"],
 "subtitle":"Maya-Ruinen, Cenoten &amp; Karibik","readmin":9,
 "h1":"Yucatán – Maya-Magie und Karibiktraum",
 "meta":"Yucatán-Rundreise: Chichén Itzá, Tulum, Cenoten, Mérida und karibische Traumstrände. Route, Kultur und Tipps für Mexiko.",
 "teaser":"Pyramiden im Dschungel, türkise Cenoten und karibische Strände.",
 "best":"November–April","dauer":"12–16 Tage","budget":"€€ mittel","typ":"Kultur &amp; Strand",
 "intro":[
   "Die Halbinsel Yucatán vereint, wonach sich viele sehnen: uralte Maya-Kultur, geheimnisvolle Cenoten und die türkise Karibik. Wir haben Pyramiden erklommen, sind in glasklare Kalksteinhöhlen gesprungen und haben mit Walhaien geschnorchelt.",
   "Yucatán ist herrlich abwechslungsreich und dabei gut zu bereisen. Von der Kolonialstadt Mérida über die Ruinen im Dschungel bis zu den weißen Stränden der Riviera Maya – hier trifft Geschichte auf Postkarten-Karibik."],
 "cap":"Die Pyramide von Chichén Itzá – ein Weltwunder mitten im Dschungel von Yucatán.",
 "highlights":[
   ["Chichén Itzá","Eines der neuen Weltwunder – die Kukulcán-Pyramide früh morgens, bevor die Busse kommen."],
   ["Cenoten","Türkisblaue Süßwasser-Höhlen zum Schwimmen und Schnorcheln – die Cenote Ik Kil ist legendär."],
   ["Tulum","Maya-Ruinen direkt über dem Karibikstrand – die spektakulärste Lage aller Ruinen."],
   ["Mérida &amp; Valladolid","Bunte Kolonialstädte mit Kathedralen, Märkten und dem echten mexikanischen Leben."],
   ["Isla Holbox","Autofreie Karibikinsel mit Flamingos, Sandstraßen und im Sommer Walhaien."],
 ],
 "tips":[
   "Ruinen und Cenoten zur Öffnung besuchen – kühler, leerer und deutlich stimmungsvoller.",
   "Miete ein Auto für Flexibilität: So erreichst du auch abgelegene, ruhige Cenoten.",
   "Immer Bargeld (Pesos) dabei haben – kleine Orte und Cenoten nehmen oft keine Karte.",
   "Nutze reef-safe Sonnencreme; in vielen Cenoten ist eincremen sogar verboten.",
   "Lerne ein paar Brocken Spanisch – es öffnet Türen und Herzen.",
 ],
 "quote":"In einer stillen Cenote, umgeben von Lianen und Lichtstrahlen, fühlten wir uns wie in einer anderen Welt – heilig und magisch zugleich.",
 "food":"Yucatán hat eine eigene Küche: Cochinita Pibil (langsam gegartes Schwein), Tacos al Pastor, frische Guacamole und Aguas Frescas. Und natürlich Tequila und Mezcal am Abend.",
 "rec1":["In die Cenote","Schnorchelset &amp; wasserfeste Tasche","Die Cenoten und Riffe sind ein Schnorchel-Traum. Mit eigenem Set bist du spontan startklar, und eine wasserfeste Tasche schützt Handy und Wertsachen am Wasser."],
 "rec2":["Kultur verstehen","Reiseführer Mexiko/Yucatán","Die Maya-Stätten werden erst mit Hintergrundwissen richtig faszinierend. Ein guter Reiseführer liefert Geschichte, Öffnungszeiten und die besten Cenoten-Tipps."],
 "reisezeit":"Beste Reisezeit ist die Trockenzeit von November bis April. Der Sommer ist heiß und feucht, von Juni bis November ist Hurrikan-Saison. Für Walhaie ist Juni–September auf Holbox top. Anreise über Cancún.",
 "fazit":"Yucatán ist die perfekte Mischung aus Kultur und Karibik. Wer Geschichte, Natur und Strand verbinden will, findet hier eine der lohnendsten Rundreisen Mittelamerikas.",
},
{
 "slug":"kreta","name":"Kreta","region":"Griechenland","ph":"ph--3",
 "c1":"#1e6b7a","c2":"#2C9C9C","g":["ph--3","ph--7","ph--5"],
 "subtitle":"Mythen, Schluchten &amp; rosa Strände","readmin":8,
 "h1":"Kreta – Griechenlands wilde Schönheit",
 "meta":"Kreta-Reise: Knossos, Chania, die pinken Strände Elafonissi &amp; Balos und die Samaria-Schlucht. Highlights, Route und Tipps.",
 "teaser":"Minoische Paläste, tiefe Schluchten und rosaweißer Sand.",
 "best":"Mai–Juni &amp; Sep–Okt","dauer":"7–10 Tage","budget":"€€ mittel","typ":"Insel &amp; Kultur",
 "intro":[
   "Kreta ist die größte griechische Insel – und fühlt sich fast wie ein eigenes kleines Land an. Auf der einen Seite jahrtausendealte Mythen und minoische Paläste, auf der anderen zerklüftete Berge, tiefe Schluchten und Strände, die aussehen wie aus einem Traum.",
   "Wir haben den Westen mit seinen berühmten Lagunen und die charmanten Altstädte kombiniert. Kreta belohnt alle, die auch mal die Hauptstraße verlassen – hinter jeder Bergkurve wartet ein Bergdorf, eine Taverne oder eine einsame Bucht."],
 "cap":"Die rosa schimmernde Lagune von Elafonissi im Südwesten Kretas.",
 "highlights":[
   ["Palast von Knossos","Das Zentrum der minoischen Kultur und Schauplatz des Minotaurus-Mythos."],
   ["Elafonissi &amp; Balos","Zwei der schönsten Lagunen Europas mit rosa-weißem Sand und türkisem Flachwasser."],
   ["Altstadt von Chania","Venezianischer Hafen, verwinkelte Gassen und die beste Abendstimmung der Insel."],
   ["Samaria-Schlucht","Eine der längsten Schluchten Europas – 16 km Wanderung bis ans Libysche Meer."],
   ["Bergdörfer &amp; Tavernen","Im Landesinneren erlebst du das ursprüngliche Kreta mit herzlicher Gastfreundschaft."],
 ],
 "tips":[
   "Miete ein Auto – die schönsten Strände und Dörfer erreichst du nur so entspannt.",
   "Fahre zu Balos und Elafonissi früh los; ab Mittag wird es voll und heiß.",
   "Für die Samaria-Schlucht feste Wanderschuhe und viel Wasser einpacken.",
   "Probiere in einer Bergtaverne das Tagesgericht – oft das Beste, was die Insel zu bieten hat.",
 ],
 "quote":"Ein Glas Raki, dazu der Blick über den venezianischen Hafen von Chania bei Sonnenuntergang – kretische Gelassenheit in Reinform.",
 "food":"Kretische Küche gilt als besonders gesund: Dakos (Gerstenbrot mit Tomate und Feta), gegrilltes Lamm, wildes Grüngemüse, Olivenöl aus eigener Ernte und zum Abschluss immer ein Raki aufs Haus.",
 "rec1":["Schluchten &amp; Strände","Robuste Wander-Sandalen","Von der Samaria-Schlucht bis zu steinigen Buchten – bequeme, robuste Trekking-Sandalen sind auf Kreta die perfekte Allzweckwaffe für Land und Wasser."],
 "rec2":["Insel-Navigation","Griechenland-Reiseführer &amp; Offline-Karte","Kretas schönste Ecken liegen abseits. Ein guter Reiseführer und Offline-Karten helfen, Bergdörfer und einsame Buchten sicher zu finden."],
 "reisezeit":"Am schönsten sind Mai–Juni und September–Oktober: warm, aber nicht zu heiß, und angenehm leer. Der Hochsommer ist heiß und voll. Anreise per Direktflug nach Heraklion oder Chania.",
 "fazit":"Kreta ist Insel und Abenteuer zugleich: Strand, Kultur, Wandern und großartiges Essen. Für uns eine der abwechslungsreichsten Mittelmeerinseln überhaupt.",
},
{
 "slug":"mallorca","name":"Mallorca","region":"Spanien","ph":"ph--5",
 "c1":"#3c6e54","c2":"#6FA07C","g":["ph--5","ph--3","ph--7"],
 "subtitle":"Mehr als Ballermann: Berge &amp; Buchten","readmin":8,
 "h1":"Mallorca – die Insel hat so viele Seiten",
 "meta":"Mallorca abseits der Klischees: Serra de Tramuntana, Traumbuchten (Calas), Palma und die schönsten Dörfer. Route und Tipps.",
 "teaser":"UNESCO-Bergwelt, versteckte Calas und charmante Dörfer.",
 "best":"April–Juni &amp; Sep–Okt","dauer":"5–8 Tage","budget":"€€ mittel","typ":"Insel &amp; Aktiv",
 "intro":[
   "Mallorca kann so viel mehr, als sein Ruf vermuten lässt. Abseits der bekannten Partymeile erwartet dich eine überraschend vielfältige Insel: das UNESCO-Weltnaturerbe der Tramuntana-Berge, türkise Buchten zwischen Pinienwäldern und honigfarbene Dörfer, in denen die Zeit stillzustehen scheint.",
   "Wir haben die Insel mit dem Mietwagen erkundet und immer wieder gestaunt, wie schnell man vom Trubel in die Ruhe kommt. Ein perfektes Ziel für einen aktiven Kurzurlaub mit Wandern, Baden und Genießen."],
 "cap":"Die Serpentinen von Sa Calobra in der Serra de Tramuntana – ein Fahr- und Radlerklassiker.",
 "highlights":[
   ["Serra de Tramuntana","UNESCO-Bergwelt mit Panoramastraßen, Wanderwegen und Orten wie Valldemossa und Deià."],
   ["Traumbuchten (Calas)","Cala Varques, Cala Mondragó und viele mehr – türkises Wasser in versteckten Buchten."],
   ["Palma de Mallorca","Die unterschätzte Hauptstadt mit gotischer Kathedrale La Seu und lebendiger Altstadt."],
   ["Sa Calobra &amp; Torrent de Pareis","Spektakuläre Serpentinenstraße hinab zu einer Schlucht am Meer."],
   ["Cap de Formentor","Der dramatische Nordzipfel mit Leuchtturm und atemberaubenden Ausblicken."],
 ],
 "tips":[
   "Miete ein Auto und starte früh zu den Calas – Parkplätze sind rar und schnell voll.",
   "Fahre die Tramuntana unter der Woche; am Wochenende ist sie bei Radlern beliebt.",
   "Nimm feste Schuhe für kurze Wanderungen zu den schönsten, autofreien Buchten.",
   "Besuche Palma früh morgens oder abends – tagsüber ist es in der Altstadt voll.",
 ],
 "quote":"Als wir nach der kurvigen Fahrt in einer stillen Cala schwammen, ganz ohne Menschenmassen, dachten wir: Das soll Mallorca sein?",
 "food":"Mallorquinisch und lecker: Pa amb oli (Brot mit Öl, Tomate und Käse), die süße Ensaïmada zum Frühstück, frischer Fisch am Hafen und ein Glas Wein aus dem Inselinneren (Binissalem).",
 "rec1":["Aktiv unterwegs","Leichte Wanderschuhe","Für die Tramuntana und die Wege zu den versteckten Calas sind griffige, leichte Wanderschuhe ideal – bequem genug, um sie den ganzen Tag zu tragen."],
 "rec2":["Cala-Tag","Schnelltrocknendes Strandtuch &amp; Trockensack","Für die kleinen Buchten ohne Infrastruktur ist ein kompaktes Mikrofasertuch perfekt – klein gepackt, schnell trocken, ideal fürs Wandern zum Strand."],
 "reisezeit":"Am angenehmsten sind Frühling (April–Juni) und Herbst (September–Oktober): mild, grün und ruhig – ideal zum Wandern und Baden. Der Hochsommer ist heiß und voll. Anreise per Kurzstreckenflug nach Palma.",
 "fazit":"Mallorca ist eine echte Ganzjahresinsel mit vielen Gesichtern. Wer die Klischees hinter sich lässt, entdeckt eine der schönsten und vielseitigsten Inseln des Mittelmeers – perfekt schon für ein langes Wochenende.",
},
{
 "slug":"sardinien","name":"Sardinien","region":"Italien","ph":"ph--3",
 "c1":"#1e7a9a","c2":"#2C9C9C","g":["ph--3","ph--8","ph--7"],
 "subtitle":"Karibik-Wasser im Mittelmeer","readmin":8,
 "h1":"Sardinien – die Karibik Europas",
 "meta":"Sardinien-Reise: Costa Smeralda, La Pelosa, Cala Gonone und geheimnisvolle Nuraghen. Die schönsten Strände und Tipps.",
 "teaser":"Weißer Sand, unglaublich klares Wasser und uralte Nuraghen.",
 "best":"Mai–Juni &amp; Sep","dauer":"7–10 Tage","budget":"€€–€€€","typ":"Insel &amp; Strand",
 "intro":[
   "Sardinien hat Strände, die man eher in der Karibik vermuten würde: puderweißer Sand, Wasser in allen Türkistönen und dazwischen wild-schöne Felsküsten. Wer einmal in einer der Buchten geschwommen ist, versteht, warum die Insel so einen Ruf hat.",
   "Aber Sardinien ist mehr als Strand: Über 7.000 geheimnisvolle Nuraghen – jahrtausendealte Steintürme – erzählen von einer uralten Kultur. Wir haben Küste und Hinterland verbunden und eine Insel voller Charakter entdeckt."],
 "cap":"Das kristallklare Wasser von La Pelosa im Nordwesten – ein Bild wie aus der Karibik.",
 "highlights":[
   ["La Pelosa (Stintino)","Einer der schönsten Strände Europas mit flachem, türkisem Wasser und Blick auf einen alten Wachturm."],
   ["Costa Smeralda","Die glamouröse „Smaragdküste“ mit Luxusyachten und versteckten Trauchbuchten."],
   ["Cala Gonone &amp; Golfo di Orosei","Nur per Boot oder Wanderung erreichbare Traumbuchten wie Cala Luna."],
   ["Nuraghen","Geheimnisvolle Steinbauten der Bronzezeit – der Su Nuraxi ist UNESCO-Welterbe."],
   ["Bosa &amp; Alghero","Bunte Flussstadt und katalanisch geprägtes Städtchen mit charmanter Altstadt."],
 ],
 "tips":[
   "An Top-Stränden wie La Pelosa gibt es Besucherlimits und Gebühren – früh kommen und informieren.",
   "Miete ein Auto: Die schönsten Buchten liegen oft am Ende kleiner Küstenstraßen.",
   "Für die Buchten am Golfo di Orosei lohnt sich eine Bootstour ab Cala Gonone.",
   "Bring Strandschuhe mit – manche Buchten sind steinig, das Wasser aber traumhaft.",
 ],
 "quote":"Wir ankerten in einer einsamen Bucht am Golfo di Orosei, sprangen ins glasklare Wasser – und die Zeit schien einfach anzuhalten.",
 "food":"Sardische Küche ist rustikal und einzigartig: Culurgiones (gefüllte Teigtaschen), Porceddu (Spanferkel), das dünne Pane Carasau, Pecorino-Käse und der kräftige Rotwein Cannonau.",
 "rec1":["Felsige Buchten","Wasserschuhe (Badeschuhe)","Viele der schönsten Buchten Sardiniens sind steinig. Bequeme Wasserschuhe schützen die Füße und machen jeden Einstieg ins türkise Wasser entspannt."],
 "rec2":["Strandtag komplett","Schwimmender Trockensack &amp; Schnorchelset","Für Bootstouren und einsame Buchten sind ein wasserdichter Trockensack und ein Schnorchelset ideal – so bleibt alles trocken und du entdeckst die Unterwasserwelt."],
 "reisezeit":"Ideal sind Mai/Juni und September: warmes Meer, angenehme Temperaturen und weniger Trubel als im vollen August. Anreise per Flug (Olbia, Cagliari, Alghero) oder mit der Fähre ab Italiens Festland.",
 "fazit":"Sardinien ist ein Traum für Strandliebhaber – mit Wasser, das mit jedem Fernziel mithält. Kombiniere Küste und Hinterland, und du bekommst eine der schönsten Inseln des Mittelmeers.",
},
{
 "slug":"portugal","name":"Portugal","region":"Europa","ph":"ph--2",
 "c1":"#b5541f","c2":"#D9744F","g":["ph--2","ph--7","ph--4"],
 "subtitle":"Lissabon, Porto &amp; die wilde Algarve","readmin":9,
 "h1":"Portugal – Roadtrip von Norden bis zur Algarve",
 "meta":"Portugal-Reise: Lissabon, Porto, Sintra, Douro-Tal und die Algarve. Die beste Route, Highlights, Fado, Pastéis und Tipps.",
 "teaser":"Azulejos, Steilküsten, Surf und die besten Pastéis de Nata.",
 "best":"April–Juni &amp; Sep–Okt","dauer":"10–14 Tage","budget":"€€ mittel","typ":"Roadtrip &amp; Stadt",
 "intro":[
   "Portugal hat uns mit seiner Vielfalt verzaubert: melancholischer Fado in den Gassen Lissabons, das goldene Licht über Porto, terrassierte Weinberge im Douro-Tal und die dramatischen Steilküsten der Algarve. Ein kompaktes Land, das sich perfekt als Roadtrip erleben lässt.",
   "Was Portugal so besonders macht, ist die Mischung aus Herzlichkeit, Geschichte und gutem Essen zu fairen Preisen. Von den Surferstränden im Westen bis zu den bunten Azulejo-Fassaden – hier fühlt man sich schnell zuhause."],
 "cap":"Die bunten Fassaden und gelben Straßenbahnen von Lissabon – Fernweh pur.",
 "highlights":[
   ["Lissabon","Aussichtspunkte (Miradouros), die Tram 28, das Viertel Alfama und Fado am Abend."],
   ["Sintra","Märchenhafte Paläste wie der Palácio da Pena mitten im grünen Bergwald."],
   ["Porto &amp; Douro-Tal","Portwein-Keller am Fluss und eine Bootsfahrt durch die terrassierten Weinberge."],
   ["Algarve","Goldene Steilküsten, die Höhle von Benagil und Traumstrände wie Praia da Marinha."],
   ["Surf-Küste","Von Nazaré (Monsterwellen) bis Sagres – Portugals wilder Atlantik."],
 ],
 "tips":[
   "Kaufe das Original-Pastel de Nata in Belém – und iss es lauwarm mit Zimt.",
   "Für Lissabon und Porto bequeme Schuhe: Die Städte sind hügelig und mit Kopfstein gepflastert.",
   "Miete für die Algarve ein Auto – die schönsten Strände liegen zwischen den Orten.",
   "Die Höhle von Benagil früh morgens per Kajak oder SUP besuchen, bevor die Boote kommen.",
   "Reise im Frühling oder Herbst – dann ist es angenehm und deutlich günstiger.",
 ],
 "quote":"Ein Glas Portwein, dazu der Sonnenuntergang über dem Douro und die Lichter von Porto – ein Abend, den wir immer wieder erleben wollen.",
 "food":"Portugal ist ein Genussland: Pastéis de Nata, Bacalhau (Stockfisch) in unzähligen Varianten, gegrillte Sardinen, frische Meeresfrüchte und natürlich Portwein und Vinho Verde.",
 "rec1":["Steilküsten &amp; Städte","Bequeme City-Sneaker","Portugals hügelige Städte und Küstenpfade verlangen nach bequemem Schuhwerk. Ein paar leichte, gut gedämpfte Sneaker trägst du hier den ganzen Tag beschwerdefrei."],
 "rec2":["Perfekte Fotos","Handy-Weitwinkel &amp; Stativ","Von den Miradouros Lissabons bis zur Benagil-Höhle: Mit einem kleinen Reisestativ und Weitwinkel gelingen dir Aufnahmen, die die Weite wirklich einfangen."],
 "reisezeit":"Am schönsten sind Frühling (April–Juni) und Herbst (September–Oktober): mildes Wetter, wenig Andrang und faire Preise. Der Süden (Algarve) ist auch im Sommer top. Anreise per Direktflug nach Lissabon, Porto oder Faro.",
 "fazit":"Portugal ist eines unserer liebsten Roadtrip-Länder in Europa: vielfältig, gastfreundlich und preislich fair. Ob Städtetrip oder Küsten-Rundreise – es lohnt sich immer.",
},
{
 "slug":"sizilien","name":"Sizilien","region":"Italien","ph":"ph--7",
 "c1":"#8a6f22","c2":"#C9A24B","g":["ph--7","ph--2","ph--4"],
 "subtitle":"Vulkan Ätna, Tempel &amp; Dolce Vita","readmin":9,
 "h1":"Sizilien – Vulkaninsel voller Geschichte",
 "meta":"Sizilien-Rundreise: Ätna, Taormina, Tal der Tempel, Palermo und Cefalù. Highlights, beste Route und kulinarische Tipps.",
 "teaser":"Feuerspeiender Ätna, griechische Tempel und beste Küche Italiens.",
 "best":"April–Juni &amp; Sep–Okt","dauer":"8–12 Tage","budget":"€€ mittel","typ":"Insel &amp; Kultur",
 "intro":[
   "Sizilien ist ein Kontinent im Kleinen: der noch aktive Vulkan Ätna, besser erhaltene griechische Tempel als in Griechenland, barocke Städte, wilde Küsten und eine Küche, die viele für die beste Italiens halten. Kaum eine Insel bündelt so viel Geschichte und Leben.",
   "Wir sind einmal um die Insel gefahren und haben in jeder Region etwas Neues entdeckt – vom rauchenden Ätna über die Mosaike der Antike bis zu den Straßenmärkten Palermos. Sizilien ist laut, herzlich, chaotisch und wunderschön."],
 "cap":"Blick von Taormina auf die Küste und den rauchenden Ätna im Hintergrund.",
 "highlights":[
   ["Ätna","Europas aktivster Vulkan – Wanderung zu den Kratern oder Auffahrt mit der Seilbahn."],
   ["Taormina","Antikes Theater mit Ätna-Blick, elegante Gassen und die Bucht von Isola Bella."],
   ["Tal der Tempel (Agrigento)","Eine der besterhaltenen antiken Tempelanlagen der Welt – UNESCO-Welterbe."],
   ["Palermo &amp; Monreale","Pulsierende Hauptstadt mit Streetfood-Märkten und goldglänzenden Mosaiken."],
   ["Cefalù","Postkarten-Städtchen mit Dom, Sandstrand und mittelalterlichen Gassen."],
 ],
 "tips":[
   "Für den Ätna feste Schuhe, eine Jacke und einen lokalen Guide für die oberen Krater einplanen.",
   "Probiere dich durch Palermos Streetfood-Märkte (Ballarò, Vucciria) – ein Erlebnis für sich.",
   "Fahre defensiv: Der sizilianische Verkehr, besonders in Palermo, ist gewöhnungsbedürftig.",
   "Besuche das Tal der Tempel am späten Nachmittag – schönes Licht und angenehmere Temperaturen.",
   "Nimm dir Zeit für lange Mittagessen – Dolce Vita ist hier Programm.",
 ],
 "quote":"Als der Ätna am Abend leicht glühte, während wir in Taormina Cannoli aßen, war klar: Sizilien verführt alle Sinne gleichzeitig.",
 "food":"Sizilien ist ein Fest für Feinschmecker: Arancini, Pasta alla Norma, frischester Fisch, Cannoli und die beste Granita mit Brioche zum Frühstück. Dazu kräftiger Wein vom Ätna.",
 "rec1":["Auf den Vulkan","Leichte Wander-/Trekkingschuhe","Der Ätna und die Tempelpfade verlangen nach festem Schuhwerk. Bequeme, griffige Trekkingschuhe machen Vulkanwanderung und Ruinenbummel gleichermaßen mit."],
 "rec2":["Kultur pur","Reiseführer Sizilien","Sizilien steckt voller Geschichte – von den Griechen bis zu den Normannen. Ein guter Reiseführer verbindet die Stätten zu einer Rundreise und liefert die besten Restaurant-Tipps."],
 "reisezeit":"Am angenehmsten sind Frühling (April–Juni) und Herbst (September–Oktober): warm, blühend und nicht überlaufen. Der Hochsommer ist heiß. Anreise per Flug nach Catania (Osten) oder Palermo (Westen).",
 "fazit":"Sizilien ist eine der lohnendsten Inseln Europas: Vulkan, Antike, Barock, Strand und Weltklasse-Küche in einer Reise. Für uns ein Ziel, zu dem wir garantiert zurückkehren.",
},
]

# Reihenfolge / „related“-Zuordnung
for i, d in enumerate(D):
    others = [D[(i+1) % len(D)], D[(i+2) % len(D)], D[(i+3) % len(D)]]
    html_out = page(d, others)
    with open(os.path.join(OUT, f"reise-{d['slug']}.html"), "w", encoding="utf-8") as f:
        f.write(html_out)
    print("geschrieben:", f"reise-{d['slug']}.html")

# ---------------------------------------------------------------------------
#  Übersicht: reiseziele.html
# ---------------------------------------------------------------------------
REGIONS = [
  ("Indischer Ozean &amp; Afrika", "Trauminseln, Wüste und die große Safari.", ["seychellen","malediven","namibia","suedafrika"]),
  ("Asien &amp; Orient", "Tempel, Streetfood und Superlative.", ["thailand","dubai"]),
  ("Amerika", "Roadtrips, Regenwald und Maya-Kultur.", ["usa-westkueste","costa-rica","mexiko-yucatan"]),
  ("Europa &amp; Mittelmeer", "Inseln, Küsten und Genuss vor der Haustür.", ["portugal","mallorca","sardinien","sizilien","kreta"]),
]
by_slug = {d["slug"]: d for d in D}

def ziel_card(d):
    return f'''      <article class="card">
        <div class="ph {d["ph"]}" data-label="Foto: {d["name"]}"></div>
        <div class="card-body">
          <span class="tag">{d["region"]}</span>
          <h3><a href="reise-{d["slug"]}.html">{d["name"]}</a></h3>
          <p>{d["teaser"]}</p>
          <div class="card-meta"><span>{d["best"]}</span><span>· {d["dauer"]}</span></div>
        </div>
      </article>'''

region_blocks = ""
for title, sub, slugs in REGIONS:
    cards = "\n".join(ziel_card(by_slug[s]) for s in slugs)
    region_blocks += f'''
    <div class="section-head" style="margin-top:20px;">
      <h2>{title}</h2>
      <p>{sub}</p>
    </div>
    <div class="grid grid-3" style="margin-bottom:40px;">
{cards}
    </div>
'''

nav_pills = '\n'.join(
    f'      <a class="pill" href="#{title_id}">{label}</a>'
    for title_id, label in [
        ("indischer-ozean-afrika","Indischer Ozean &amp; Afrika"),
        ("asien-orient","Asien &amp; Orient"),
        ("amerika","Amerika"),
        ("europa-mittelmeer","Europa &amp; Mittelmeer"),
    ])

# IDs an die Überschriften hängen
anchors = ["indischer-ozean-afrika","asien-orient","amerika","europa-mittelmeer"]
region_blocks = ""
for (title, sub, slugs), anchor in zip(REGIONS, anchors):
    cards = "\n".join(ziel_card(by_slug[s]) for s in slugs)
    region_blocks += f'''
    <div class="section-head" id="{anchor}" style="margin-top:16px;scroll-margin-top:90px;">
      <h2>{title}</h2>
      <p>{sub}</p>
    </div>
    <div class="grid grid-3" style="margin-bottom:44px;">
{cards}
    </div>
'''

reiseziele = f'''<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reiseziele – alle Länder &amp; Inseln | Zuhause in der Welt</title>
<meta name="description" content="Alle Reiseziele auf einen Blick: von den Seychellen über Namibia und Thailand bis Sizilien – mit Highlights, bester Reisezeit und Tipps.">
<meta name="theme-color" content="#14524E">
<link rel="canonical" href="https://www.deine-domain.de/reiseziele.html">
<link rel="icon" href="{FAVICON}">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

{header("reiseziele")}

<section class="hero" style="background:linear-gradient(135deg,#123f3b,#1E6B64 60%,#2C6E7F);">
  <div class="wrap hero-inner">
    <span class="eyebrow">Reiseziele</span>
    <h1>Orte, an denen wir wirklich waren</h1>
    <p class="lead">14 Länder und Inseln von der Karibik bis zur afrikanischen Wüste – jedes mit eigenem Reisebericht, Highlights, bester Reisezeit und ehrlichen Empfehlungen.</p>
  </div>
</section>

<section style="padding-top:34px;">
  <div class="wrap">
    <div class="pill-row" style="margin-bottom:30px;">
      <span class="pill active">Alle Regionen</span>
{nav_pills}
    </div>
{region_blocks}
  </div>
</section>

<section class="bg-cream">
  <div class="wrap narrow newsletter">
    <span class="eyebrow">Kein Ziel verpassen</span>
    <h2>Neue Reiseziele per Newsletter</h2>
    <p>Sobald ein neues Ziel online geht, bekommst du eine kurze Nachricht.</p>
    <form class="newsletter-form" data-demo>
      <input type="email" required placeholder="deine@email.de" aria-label="E-Mail-Adresse">
      <button class="btn btn--primary" type="submit">Abonnieren</button>
    </form>
    <p class="form-note center" style="display:none;color:var(--ocean);font-weight:600;margin-top:14px;"></p>
  </div>
</section>

{FOOTER}

<script src="assets/main.js"></script>
</body>
</html>
'''
with open(os.path.join(OUT, "reiseziele.html"), "w", encoding="utf-8") as f:
    f.write(reiseziele)
print("geschrieben: reiseziele.html")

# ---------------------------------------------------------------------------
#  Blog-Übersicht: blog.html
# ---------------------------------------------------------------------------
def blog_card(d):
    return f'''      <article class="card">
        <div class="ph {d["ph"]}" data-label="Foto: {d["name"]}"></div>
        <div class="card-body">
          <span class="tag">{d["region"]}</span>
          <h3><a href="reise-{d["slug"]}.html">{d["h1"]}</a></h3>
          <p>{d["teaser"]}</p>
          <div class="card-meta"><span>{d["best"]}</span><span>· {d["readmin"]} Min.</span></div>
        </div>
      </article>'''

feat = by_slug["seychellen"]
rest = [d for d in D if d["slug"] != "seychellen"]
blog_cards = "\n".join(blog_card(d) for d in rest)

blog = f'''<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Blog – alle Reiseberichte | Zuhause in der Welt</title>
<meta name="description" content="Alle Reisegeschichten und Berichte: von den Seychellen über Namibia und Thailand bis Sizilien. Ehrliche Erfahrungen, Highlights und Tipps.">
<meta name="theme-color" content="#14524E">
<link rel="canonical" href="https://www.deine-domain.de/blog.html">
<link rel="icon" href="{FAVICON}">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

{header("blog")}

<section class="hero" style="background:linear-gradient(135deg,#123f3b,#1E6B64 60%,#2C6E7F);">
  <div class="wrap hero-inner">
    <span class="eyebrow">Blog</span>
    <h1>Geschichten von unterwegs</h1>
    <p class="lead">Reiseberichte aus 14 Ländern und Inseln – ehrliche Erfahrungen, Highlights und die Tipps, die ich mir vorher selbst gewünscht hätte.</p>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="pill-row" style="margin-bottom:40px;">
      <span class="pill active">Alle</span>
      <a class="pill" href="reiseziele.html#indischer-ozean-afrika">Indischer Ozean &amp; Afrika</a>
      <a class="pill" href="reiseziele.html#asien-orient">Asien &amp; Orient</a>
      <a class="pill" href="reiseziele.html#amerika">Amerika</a>
      <a class="pill" href="reiseziele.html#europa-mittelmeer">Europa &amp; Mittelmeer</a>
    </div>

    <article class="card" style="flex-direction:row;margin-bottom:44px;">
      <div class="ph {feat["ph"]}" data-label="Foto: {feat["name"]}" style="flex:0 0 46%;min-height:320px;"></div>
      <div class="card-body" style="justify-content:center;">
        <span class="tag tag--terra">Titelstory</span>
        <h3 style="font-size:1.7rem;"><a href="reise-{feat["slug"]}.html">{feat["h1"]}</a></h3>
        <p>{feat["intro"][0]}</p>
        <div class="card-meta"><span>{feat["region"]}</span><span>· {feat["best"]}</span><span>· {feat["readmin"]} Min. Lesezeit</span></div>
        <a class="btn btn--primary" href="reise-{feat["slug"]}.html" style="align-self:flex-start;margin-top:16px;">Beitrag lesen</a>
      </div>
    </article>

    <div class="grid grid-3">
{blog_cards}
    </div>
  </div>
</section>

{FOOTER}

<script src="assets/main.js"></script>
</body>
</html>
'''
with open(os.path.join(OUT, "blog.html"), "w", encoding="utf-8") as f:
    f.write(blog)
print("geschrieben: blog.html")
print("FERTIG –", len(D), "Ziele.")
