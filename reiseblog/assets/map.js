/* Interaktive Weltkarte – Zuhause in der Welt
   Selbst-gehostet, ohne externe Karten-Dienste (DSGVO-freundlich).
   Basiskarte: window.WORLD_PATHS / WORLD_BBOX (aus map-data.js).
   Projektion: equirektangular in ein 1000x500-System. */
(function () {
  "use strict";
  if (!window.WORLD_PATHS) return;

  var W = 1000, H = 500;
  function px(lon, lat) { return [ (lon + 180) * W / 360, (90 - lat) * H / 180 ]; }

  // ------- Reisedaten: Länder + besuchte Regionen (mit Blog-Link) -------
  // box = [West, Süd, Ost, Nord] (geograf. Ausschnitt für den Zoom)
  var TRIPS = [
    { iso:"SYC", name:"Seychellen", at:[55.7,-4.6], box:[55.1,-5.0,56.2,-4.0], regions:[
      {n:"Mahé", at:[55.48,-4.68], url:"reise-seychellen.html"},
      {n:"Praslin", at:[55.74,-4.32], url:"reise-seychellen.html"},
      {n:"La Digue", at:[55.84,-4.35], url:"reise-seychellen.html"} ] },
    { iso:"MDV", name:"Malediven", at:[73.2,3.9], box:[72.3,2.6,74.0,5.8], regions:[
      {n:"Malé", at:[73.51,4.17], url:"reise-malediven.html"},
      {n:"Baa-Atoll", at:[73.05,5.2], url:"reise-malediven.html"},
      {n:"Ari-Atoll", at:[72.83,3.7], url:"reise-malediven.html"} ] },
    { iso:"NAM", name:"Namibia", at:[15.9,-22.5], box:[13.2,-25.8,17.8,-17.8], regions:[
      {n:"Sossusvlei", at:[15.29,-24.73], url:"reise-namibia.html"},
      {n:"Swakopmund", at:[14.53,-22.68], url:"reise-namibia.html"},
      {n:"Etosha", at:[16.33,-18.85], url:"reise-namibia.html"} ] },
    { iso:"ZAF", name:"Südafrika", at:[22.0,-31.5], box:[17.3,-34.8,32.8,-23.2], regions:[
      {n:"Kapstadt", at:[18.42,-33.92], url:"reise-suedafrika.html"},
      {n:"Garden Route", at:[23.05,-34.04], url:"reise-suedafrika.html"},
      {n:"Krüger-NP", at:[31.5,-24.0], url:"reise-suedafrika.html"} ] },
    { iso:"ARE", name:"Dubai", at:[55.27,25.2], box:[54.7,24.5,56.0,25.5], regions:[
      {n:"Burj Khalifa", at:[55.274,25.197], url:"dubai-3-tage.html"},
      {n:"Wüste", at:[55.7,24.85], url:"dubai-3-tage.html"},
      {n:"Dubai Marina", at:[55.14,25.08], url:"reise-dubai.html"} ] },
    { iso:"THA", name:"Thailand", at:[100.5,15.5], box:[97.3,6.8,101.7,19.7], regions:[
      {n:"Bangkok", at:[100.50,13.76], url:"reise-thailand.html"},
      {n:"Chiang Mai", at:[98.98,18.79], url:"reise-thailand.html"},
      {n:"Krabi", at:[98.91,8.09], url:"reise-thailand.html"} ] },
    { iso:"USA", name:"USA Westküste", at:[-118.5,36.2], box:[-123.6,32.2,-111.0,38.7], regions:[
      {n:"San Francisco", at:[-122.42,37.77], url:"reise-usa-westkueste.html"},
      {n:"Los Angeles", at:[-118.24,34.05], url:"reise-usa-westkueste.html"},
      {n:"Las Vegas", at:[-115.14,36.17], url:"reise-usa-westkueste.html"},
      {n:"Grand Canyon", at:[-112.14,36.06], url:"reise-usa-westkueste.html"} ] },
    { iso:"CRI", name:"Costa Rica", at:[-84.2,10.1], box:[-85.3,9.1,-83.6,10.9], regions:[
      {n:"Arenal", at:[-84.70,10.46], url:"reise-costa-rica.html"},
      {n:"Monteverde", at:[-84.82,10.30], url:"reise-costa-rica.html"},
      {n:"Manuel Antonio", at:[-84.14,9.39], url:"reise-costa-rica.html"} ] },
    { iso:"MEX", name:"Mexiko (Yucatán)", at:[-88.3,20.6], box:[-90.4,19.9,-86.4,21.7], regions:[
      {n:"Cancún", at:[-86.85,21.16], url:"reise-mexiko-yucatan.html"},
      {n:"Chichén Itzá", at:[-88.57,20.68], url:"reise-mexiko-yucatan.html"},
      {n:"Mérida", at:[-89.62,20.97], url:"reise-mexiko-yucatan.html"},
      {n:"Tulum", at:[-87.46,20.21], url:"reise-mexiko-yucatan.html"} ] },
    { iso:"GRC", name:"Kreta", at:[24.8,35.3], box:[23.3,34.8,26.5,35.8], regions:[
      {n:"Chania", at:[24.02,35.51], url:"reise-kreta.html"},
      {n:"Knossos", at:[25.16,35.30], url:"reise-kreta.html"},
      {n:"Elafonissi", at:[23.54,35.27], url:"reise-kreta.html"} ] },
    { iso:"ESP", name:"Mallorca", at:[2.9,39.6], box:[2.2,39.2,3.5,40.0], regions:[
      {n:"Palma", at:[2.65,39.57], url:"reise-mallorca.html"},
      {n:"Tramuntana", at:[2.80,39.75], url:"reise-mallorca.html"},
      {n:"Alcúdia", at:[3.12,39.85], url:"reise-mallorca.html"} ] },
    { iso:"ITA", name:"Sardinien", at:[9.0,40.1], box:[8.0,38.8,10.0,41.3], regions:[
      {n:"La Pelosa", at:[8.20,40.95], url:"reise-sardinien.html"},
      {n:"Olbia", at:[9.50,40.92], url:"reise-sardinien.html"},
      {n:"Cala Gonone", at:[9.62,40.28], url:"reise-sardinien.html"} ] },
    { iso:"PRT", name:"Portugal", at:[-8.4,39.6], box:[-9.7,36.8,-6.8,41.4], regions:[
      {n:"Lissabon", at:[-9.14,38.72], url:"reise-portugal.html"},
      {n:"Porto", at:[-8.61,41.15], url:"reise-portugal.html"},
      {n:"Algarve", at:[-8.67,37.10], url:"reise-portugal.html"} ] },
    { iso:"ITA", name:"Sizilien", at:[14.1,37.6], box:[12.2,36.5,15.8,38.4], regions:[
      {n:"Palermo", at:[13.36,38.12], url:"reise-sizilien.html"},
      {n:"Ätna / Catania", at:[15.00,37.6], url:"reise-sizilien.html"},
      {n:"Taormina", at:[15.28,37.85], url:"reise-sizilien.html"} ] }
  ];

  var stage = document.getElementById("map-stage");
  var pinLayer = document.getElementById("map-pins");
  var wrap = document.getElementById("worldmap");
  var backBtn = document.getElementById("map-back");
  if (!stage || !pinLayer || !wrap) return;

  // ------- SVG-Basiskarte bauen -------
  var svgNS = "http://www.w3.org/2000/svg";
  var svg = document.createElementNS(svgNS, "svg");
  svg.setAttribute("viewBox", "0 0 " + W + " " + H);
  svg.setAttribute("preserveAspectRatio", "xMidYMid meet");
  var gLand = document.createElementNS(svgNS, "g");
  gLand.setAttribute("class", "map-land");
  var visited = {}; TRIPS.forEach(function (t) { visited[t.iso] = true; });
  Object.keys(window.WORLD_PATHS).forEach(function (iso) {
    var p = document.createElementNS(svgNS, "path");
    p.setAttribute("d", window.WORLD_PATHS[iso]);
    if (visited[iso]) { p.setAttribute("class", "visited"); p.setAttribute("data-iso", iso); }
    gLand.appendChild(p);
  });
  svg.appendChild(gLand);
  stage.appendChild(svg);

  // ------- Zustand -------
  var current = [0, 0, W, H];      // aktuelle viewBox
  var animTimer = null, mode = "world";

  function enforceAspect(b) {       // auf 2:1 bringen (verzerrungsfrei)
    var x = b[0], y = b[1], w = b[2], h = b[3], asp = 2;
    if (w / h < asp) { var nw = h * asp; x -= (nw - w) / 2; w = nw; }
    else { var nh = w / asp; y -= (nh - h) / 2; h = nh; }
    return [x, y, w, h];
  }
  function boxToView(box) {         // [W,S,E,N] -> pixel-viewBox mit Rand
    var tl = px(box[0], box[3]), br = px(box[2], box[1]);
    var x = tl[0], y = tl[1], w = br[0] - tl[0], h = br[1] - tl[1];
    var pad = Math.max(w, h) * 0.18; x -= pad; y -= pad; w += pad * 2; h += pad * 2;
    return enforceAspect([x, y, w, h]);
  }
  function setView(v) {
    svg.setAttribute("viewBox", v[0].toFixed(1) + " " + v[1].toFixed(1) + " " + v[2].toFixed(1) + " " + v[3].toFixed(1));
    positionPins();
  }
  function animateTo(v) {
    if (animTimer) clearTimeout(animTimer);
    var from = current.slice(), t0 = Date.now(), dur = 600;
    function step() {
      var k = Math.min(1, (Date.now() - t0) / dur);
      var e = k < .5 ? 2 * k * k : 1 - Math.pow(-2 * k + 2, 2) / 2; // easeInOut
      for (var i = 0; i < 4; i++) current[i] = from[i] + (v[i] - from[i]) * e;
      setView(current);
      if (k < 1) animTimer = setTimeout(step, 16); else { current = v.slice(); setView(current); }
    }
    step();
  }

  // ------- Pins -------
  var countryPins = [], regionPins = [];
  TRIPS.forEach(function (t, i) {
    var b = document.createElement("button");
    b.className = "map-pin map-pin--country";
    b.type = "button";
    b.setAttribute("aria-label", t.name + " – Reisebericht öffnen");
    b.innerHTML = '<span class="dot"></span><span class="label">' + t.name + '</span>';
    b.dataset.i = i;
    b.addEventListener("click", function () { openCountry(i); });
    pinLayer.appendChild(b);
    countryPins.push({ el: b, at: t.at });
  });
  function buildRegionPins(t) {
    regionPins.forEach(function (r) { r.el.remove(); });
    regionPins = [];
    t.regions.forEach(function (r) {
      var a = document.createElement("a");
      a.className = "map-pin map-pin--region";
      a.href = r.url;
      a.innerHTML = '<span class="drop"></span><span class="rlabel">' + r.n + '</span>';
      a.style.display = "none";
      pinLayer.appendChild(a);
      regionPins.push({ el: a, at: r.at });
    });
  }
  function mapToScreen(at) {
    var p = px(at[0], at[1]);
    var rect = stage.getBoundingClientRect();
    var sx = (p[0] - current[0]) / current[2] * rect.width;
    var sy = (p[1] - current[1]) / current[3] * rect.height;
    return [sx, sy];
  }
  function positionPins() {
    countryPins.forEach(function (c) {
      var s = mapToScreen(c.at); c.el.style.left = s[0] + "px"; c.el.style.top = s[1] + "px";
    });
    regionPins.forEach(function (r) {
      var s = mapToScreen(r.at); r.el.style.left = s[0] + "px"; r.el.style.top = s[1] + "px";
    });
  }

  // ------- Interaktion -------
  function openCountry(i) {
    var t = TRIPS[i];
    mode = "region";
    wrap.classList.add("zoomed");
    countryPins.forEach(function (c) { c.el.style.display = "none"; });
    buildRegionPins(t);
    animateTo(boxToView(t.box));
    // Regions-Pins nach dem Zoom einblenden
    setTimeout(function () { regionPins.forEach(function (r) { r.el.style.display = "flex"; }); positionPins(); }, 350);
  }
  function backToWorld() {
    mode = "world";
    wrap.classList.remove("zoomed");
    regionPins.forEach(function (r) { r.el.style.display = "none"; });
    countryPins.forEach(function (c) { c.el.style.display = "block"; });
    animateTo([0, 0, W, H]);
  }
  if (backBtn) backBtn.addEventListener("click", backToWorld);

  // Klick direkt aufs (hervorgehobene) Land
  gLand.addEventListener("click", function (e) {
    var iso = e.target && e.target.getAttribute("data-iso");
    if (!iso) return;
    for (var i = 0; i < TRIPS.length; i++) { if (TRIPS[i].iso === iso) { openCountry(i); return; } }
  });

  window.addEventListener("resize", positionPins);
  setView(current);
  positionPins();
})();
