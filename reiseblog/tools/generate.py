# -*- coding: utf-8 -*-
"""Generator für die ausführlichen Reiseziel-Seiten von 'Zuhause in der Welt'.

Struktur pro Artikel: Einleitung, Route, Highlights im Detail, Kosten,
Unterkunft, Fortbewegung, Kulinarik, Reisezeit, Tipps, Packliste,
Buchungs-/Affiliate-Boxen, FAQ und Fazit.
Neue Ziele: einfach einen Eintrag in D.append(...) ergänzen und Skript starten.
"""
import os

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
  '<div>Dieser Beitrag enthält Affiliate-Links (mit <strong>*</strong> markiert). Kaufst oder buchst du darüber, unterstützt du diesen Blog mit einer kleinen Provision – ohne Mehrkosten für dich.</div></div>')

def recbox(tag, title, text):
    return (f'<div class="rec-box"><span class="tag tag--gold">{tag}</span>'
            f'<h4>{title}</h4><p>{text}</p>'
            f'<a class="btn btn--primary" href="#" rel="sponsored nofollow noopener" target="_blank">Auf Amazon ansehen&nbsp;*</a></div>')

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
        f'Beste Verbindungen nach {b["apt"]}. Preise vergleichen, Preiswecker setzen und flexibel buchen.',"Flüge vergleichen"))
    cards.append(bcard("hotel","Hotels &amp; Unterkünfte",
        f'{b["stay"]} Von günstig bis gehoben – hier findest du die passende Bleibe.',"Unterkünfte finden"))
    if b["car"]:
        cards.append(bcard("auto","Mietwagen",f'{b["move"]} Früh buchen und Anbieter vergleichen lohnt sich.',"Mietwagen vergleichen"))
    else:
        cards.append(bcard("boot","Transfer &amp; Fortbewegung", b["move"], "Transfers ansehen"))
    cards.append(bcard("tour","Touren &amp; Aktivitäten",
        f'{b["tour"]} Vorab buchen spart Wartezeit und sichert die besten Slots.',"Touren ansehen"))
    cards.append(bcard("schild","Reiseversicherung",
        "Auslandskranken- und Reiserücktrittsversicherung – gerade auf Fernreisen unverzichtbar und schon ab wenigen Euro pro Reise.","Versicherung vergleichen"))
    if b.get("eu"):
        cards.append(bcard("zug","Bahn, Fähre &amp; Nahverkehr",
            "Fähren, Regionalbusse und Bahn vorab buchen – so kommst du entspannt und günstig von A nach B.","Tickets ansehen"))
    else:
        cards.append(bcard("sim","eSIM &amp; mobiles Internet",
            "Ab der Landung online ohne teures Roaming: eSIM vorab kaufen und sofort startklar sein.","eSIM sichern"))
    grid = "\n    ".join(cards)
    return (f'<div class="book-section">\n'
            f'  <h2>Reise nach {d["name"]} buchen: meine Empfehlungen</h2>\n'
            f'  <p class="book-note">Diese Dienste nutze ich selbst für Planung und Buchung. Die mit <strong>*</strong> markierten Links sind Affiliate-Links – buchst du darüber, unterstützt du den Blog ohne Mehrkosten für dich.</p>\n'
            f'  <div class="book-grid">\n    {grid}\n  </div>\n</div>')

# ------------------------- Render-Helfer -------------------------
def paras(txt_or_list):
    items = txt_or_list if isinstance(txt_or_list, list) else [txt_or_list]
    return "\n  ".join(f"<p>{p}</p>" for p in items)

def hl(items):
    return ("<ul class=\"hl-list\">\n" +
            "\n".join(f"    <li><strong>{t}</strong> – {desc}</li>" for t, desc in items) +
            "\n  </ul>")

def route_list(items):
    return ("<ol class=\"route-list\">\n" +
            "\n".join(f"    <li><strong>{t}</strong> – {desc}</li>" for t, desc in items) +
            "\n  </ol>")

def cost_table(rows, total=None):
    out = "<div class=\"cost-table\">\n"
    for lbl, val in rows:
        out += f'    <div class="row"><span>{lbl}</span><span class="val">{val}</span></div>\n'
    if total:
        out += f'    <div class="row total"><span>{total[0]}</span><span class="val">{total[1]}</span></div>\n'
    out += "  </div>"
    return out

def tip_list(items):
    return "<ul>\n" + "\n".join(f"    <li>{t}</li>" for t in items) + "\n  </ul>"

def faq_block(items):
    out = "<div class=\"faq\">\n"
    for q, a in items:
        out += (f'    <details>\n      <summary>{q}</summary>\n'
                f'      <div class="faq-body"><p>{a}</p></div>\n    </details>\n')
    out += "  </div>"
    return out

def gallery(g):
    return ('<div class="gallery">'
            f'<div class="ph {g[0]} wide" data-label="Dein Foto"></div>'
            f'<div class="ph {g[1]}" data-label="Dein Foto"></div>'
            f'<div class="ph {g[2]}" data-label="Dein Foto"></div>'
            f'<div class="ph {g[1]}" data-label="Dein Foto"></div>'
            f'<div class="ph {g[0]} wide" data-label="Dein Foto"></div>'
            '</div>')

def page(d, others):
    facts = "".join(
        f'<div class="fact"><div class="k">{ICON[k]}{lbl}</div><div class="v">{v}</div></div>'
        for k, lbl, v in [("zeit","Beste Reisezeit", d["best"]),("dauer","Empf. Dauer", d["dauer"]),
                          ("budget","Budget", d["budget"]),("typ","Reisetyp", d["typ"])])
    rel_cards = "\n".join(
        f'''      <article class="card">
        <div class="ph {o["ph"]}" data-label="Foto: {o["name"]}"></div>
        <div class="card-body">
          <span class="tag">{o["region"]}</span>
          <h3><a href="reise-{o["slug"]}.html">{o["name"]}</a></h3>
          <p>{o["teaser"]}</p>
        </div>
      </article>''' for o in others)

    kn = f'\n  <p>{d["kosten_note"]}</p>' if d.get("kosten_note") else ""

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

  {paras(d["intro"])}

  <div class="ph {d["g"][0]}" data-label="Foto: {d["name"]}"></div>
  <p class="figure-cap">{d["cap"]}</p>

  <h2>Die perfekte Route für {d["name"]}</h2>
  {paras(d["route_intro"])}
  {route_list(d["route"])}

  <h2>Die schönsten Highlights im Detail</h2>
  {hl(d["highlights"])}

  {recbox(*d["rec1"])}

  <h2>Was kostet eine Reise nach {d["name"]}?</h2>
  {paras(d["kosten_intro"])}
  {cost_table(d["kosten"], d.get("kosten_total"))}{kn}

  <h2>Unterkunft: Wo du am besten wohnst</h2>
  {paras(d["unterkunft"])}

  <h2>Fortbewegung vor Ort</h2>
  {paras(d["fortbewegung"])}

  <blockquote>{d["quote"]}</blockquote>

  <h2>Kulinarik: Das musst du probieren</h2>
  {paras(d["food"])}

  {gallery(d["g"])}

  <h2>Beste Reisezeit für {d["name"]}</h2>
  {paras(d["reisezeit"])}

  <h2>Meine besten Tipps für {d["name"]}</h2>
  {tip_list(d["tips"])}

  {recbox(*d["rec2"])}

  <h2>Packliste für {d["name"]}</h2>
  {paras(d["packliste_intro"])}
  {hl(d["packliste"])}

  {booking_section(d)}

  <h2>Häufige Fragen zu {d["name"]}</h2>
  {faq_block(d["faq"])}

  <h2>Fazit</h2>
  {paras(d["fazit"])}

  <hr>
  <p><strong>Planst du selbst eine Reise nach {d["name"]}?</strong> Schreib mir deine Fragen über die <a href="kontakt.html">Kontaktseite</a> – ich helfe gern weiter. Meine komplette Ausrüstung findest du unter <a href="ausruestung.html">Ausrüstung</a>, weitere Ziele in der <a href="reiseziele.html">Reiseziele-Übersicht</a>.</p>

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

# ===========================================================================
#  BOOK: Buchungs-/Affiliate-Daten pro Ziel
# ===========================================================================
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
 "sardinien": {"apt":"Olbia (OLB), Cagliari (CAG) oder Alghero (AHO)","stay":"Hotels, Agriturismi und B&amp;Bs entlang der Küste.","car":True,"move":"Die schönsten Buchten liegen am Ende kleiner Küstenstraßen – ein Mietwagen ist Gold wert.","tour":"Bootstour zu den Traumbuchten am Golfo di Orosei und geführtes Schnorcheln.","eu":True},
 "portugal": {"apt":"Lissabon (LIS), Porto (OPO) oder Faro (FAO)","stay":"Boutique-Hotels in Lissabon und Porto, Strandhotels und Guesthouses an der Algarve.","car":True,"move":"Für die Algarve und das Douro-Tal ist ein Mietwagen ideal; Lissabon und Porto erkundest du zu Fuß und per Tram.","tour":"Kajaktour zur Benagil-Höhle, Bootsfahrt durchs Douro-Tal, Sintra-Ausflug und ein Fado-Abend.","eu":True},
 "sizilien": {"apt":"Catania (CTA) oder Palermo (PMO)","stay":"B&amp;Bs und Boutique-Hotels in Taormina, Palermo, Cefalù und Ortigia.","car":True,"move":"Für die Inselrundfahrt ist ein Mietwagen ideal – fahre in Palermo aber defensiv.","tour":"Ätna-Wanderung mit Guide, Streetfood-Tour durch Palermo und Führung durchs Tal der Tempel.","eu":True},
}

# Ziel-Daten kommen aus dem separaten Datenmodul ziele.py
from ziele import ZIELE
D = list(ZIELE)

# ===========================================================================
#  Ausgabe erzeugen
# ===========================================================================
def build():
    for i, d in enumerate(D):
        others = [D[(i+1) % len(D)], D[(i+2) % len(D)], D[(i+3) % len(D)]]
        with open(os.path.join(OUT, f"reise-{d['slug']}.html"), "w", encoding="utf-8") as f:
            f.write(page(d, others))
        print("geschrieben:", f"reise-{d['slug']}.html")

    by_slug = {d["slug"]: d for d in D}
    REGIONS = [
      ("Indischer Ozean &amp; Afrika","indischer-ozean-afrika","Trauminseln, Wüste und die große Safari.",["seychellen","malediven","namibia","suedafrika"]),
      ("Asien &amp; Orient","asien-orient","Tempel, Streetfood und Superlative.",["thailand","dubai"]),
      ("Amerika","amerika","Roadtrips, Regenwald und Maya-Kultur.",["usa-westkueste","costa-rica","mexiko-yucatan"]),
      ("Europa &amp; Mittelmeer","europa-mittelmeer","Inseln, Küsten und Genuss vor der Haustür.",["portugal","mallorca","sardinien","sizilien","kreta"]),
    ]
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
    nav_pills = "\n".join(f'      <a class="pill" href="#{a}">{t}</a>' for t,a,_,_ in REGIONS)
    region_blocks = ""
    for title,anchor,sub,slugs in REGIONS:
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
<meta name="description" content="Alle Reiseziele auf einen Blick: von den Seychellen über Namibia und Thailand bis Sizilien – mit ausführlichen Guides, Highlights, Kosten und Tipps.">
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
    <p class="lead">14 Länder und Inseln von der Karibik bis zur afrikanischen Wüste – jedes mit ausführlichem Reiseguide: Route, Highlights, Kosten, beste Reisezeit und ehrlichen Empfehlungen.</p>
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
    with open(os.path.join(OUT,"reiseziele.html"),"w",encoding="utf-8") as f:
        f.write(reiseziele)
    print("geschrieben: reiseziele.html")

    # Blog-Übersicht
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
<meta name="description" content="Alle ausführlichen Reiseberichte: von den Seychellen über Namibia und Thailand bis Sizilien. Route, Kosten, Highlights und ehrliche Tipps.">
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
    <p class="lead">Ausführliche Reiseberichte aus 14 Ländern und Inseln – Route, Kosten, Highlights und die Tipps, die ich mir vorher selbst gewünscht hätte.</p>
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
    with open(os.path.join(OUT,"blog.html"),"w",encoding="utf-8") as f:
        f.write(blog)
    print("geschrieben: blog.html")
    print("FERTIG –", len(D), "Ziele.")


if __name__ == "__main__":
    build()
