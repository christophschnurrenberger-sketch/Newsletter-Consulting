/* Interaktive Weltkarte – Zuhause in der Welt
   Selbst-gehostet, ohne externe Karten-Dienste (DSGVO-freundlich).
   Pan (ziehen), Zoom (Mausrad / Buttons / Pinch), Klick auf Land -> Regionen.
   Basiskarte: window.WORLD_PATHS (aus map-data.js), equirektangular 1000x500. */
(function () {
  "use strict";
  if (!window.WORLD_PATHS) return;

  var W = 1000, H = 500, KMAX = 130;
  function px(lon, lat) { return [ (lon + 180) * W / 360, (90 - lat) * H / 180 ]; }

  // ---------------- Reisedaten ----------------
  var TRIPS = [
    { iso:"SYC", name:"Seychellen", at:[55.7,-4.6], box:[55.0,-5.0,56.3,-3.9], regions:[
      {n:"Mahé", at:[55.48,-4.68], url:"reise-seychellen.html"},
      {n:"Praslin", at:[55.74,-4.32], url:"reise-seychellen.html"},
      {n:"La Digue", at:[55.84,-4.35], url:"reise-seychellen.html"} ] },
    { iso:"MDV", name:"Malediven", at:[73.2,3.9], box:[72.2,2.5,74.1,5.9], regions:[
      {n:"Malé", at:[73.51,4.17], url:"reise-malediven.html"},
      {n:"Baa-Atoll", at:[73.05,5.2], url:"reise-malediven.html"},
      {n:"Ari-Atoll", at:[72.83,3.7], url:"reise-malediven.html"} ] },
    { iso:"NAM", name:"Namibia", at:[15.9,-22.5], box:[13.0,-26.0,17.9,-17.6], regions:[
      {n:"Sossusvlei", at:[15.29,-24.73], url:"reise-namibia.html"},
      {n:"Swakopmund", at:[14.53,-22.68], url:"reise-namibia.html"},
      {n:"Etosha", at:[16.33,-18.85], url:"reise-namibia.html"} ] },
    { iso:"ZAF", name:"Südafrika", at:[22.5,-31.0], box:[17.2,-35.0,33.0,-23.0], regions:[
      {n:"Kapstadt", at:[18.42,-33.92], url:"reise-suedafrika.html"},
      {n:"Garden Route", at:[23.05,-34.04], url:"reise-suedafrika.html"},
      {n:"Krüger-NP", at:[31.5,-24.0], url:"reise-suedafrika.html"} ] },
    { iso:"ARE", name:"Dubai", at:[55.27,25.2], box:[54.6,24.4,56.1,25.6], regions:[
      {n:"Burj Khalifa", at:[55.274,25.197], url:"dubai-3-tage.html"},
      {n:"Wüste", at:[55.7,24.85], url:"dubai-3-tage.html"},
      {n:"Marina", at:[55.14,25.08], url:"reise-dubai.html"} ] },
    { iso:"THA", name:"Thailand", at:[100.5,15.5], box:[97.2,6.6,101.8,19.9], regions:[
      {n:"Bangkok", at:[100.50,13.76], url:"reise-thailand.html"},
      {n:"Chiang Mai", at:[98.98,18.79], url:"reise-thailand.html"},
      {n:"Krabi", at:[98.91,8.09], url:"reise-thailand.html"} ] },
    { iso:"USA", name:"USA Westküste", at:[-118.5,36.2], box:[-124.0,32.0,-110.5,39.0], regions:[
      {n:"San Francisco", at:[-122.42,37.77], url:"reise-usa-westkueste.html"},
      {n:"Los Angeles", at:[-118.24,34.05], url:"reise-usa-westkueste.html"},
      {n:"Las Vegas", at:[-115.14,36.17], url:"reise-usa-westkueste.html"},
      {n:"Grand Canyon", at:[-112.14,36.06], url:"reise-usa-westkueste.html"} ] },
    { iso:"CRI", name:"Costa Rica", at:[-84.2,10.1], box:[-85.4,9.0,-83.5,11.0], regions:[
      {n:"Arenal", at:[-84.70,10.46], url:"reise-costa-rica.html"},
      {n:"Monteverde", at:[-84.82,10.30], url:"reise-costa-rica.html"},
      {n:"Manuel Antonio", at:[-84.14,9.39], url:"reise-costa-rica.html"} ] },
    { iso:"MEX", name:"Mexiko (Yucatán)", at:[-88.3,20.6], box:[-90.6,19.8,-86.2,21.8], regions:[
      {n:"Cancún", at:[-86.85,21.16], url:"reise-mexiko-yucatan.html"},
      {n:"Chichén Itzá", at:[-88.57,20.68], url:"reise-mexiko-yucatan.html"},
      {n:"Mérida", at:[-89.62,20.97], url:"reise-mexiko-yucatan.html"},
      {n:"Tulum", at:[-87.46,20.21], url:"reise-mexiko-yucatan.html"} ] },
    { iso:"GRC", name:"Kreta", at:[24.8,35.3], box:[23.2,34.7,26.6,35.9], regions:[
      {n:"Chania", at:[24.02,35.51], url:"reise-kreta.html"},
      {n:"Knossos", at:[25.16,35.30], url:"reise-kreta.html"},
      {n:"Elafonissi", at:[23.54,35.27], url:"reise-kreta.html"} ] },
    { iso:"ESP", name:"Mallorca", at:[2.9,39.6], box:[2.1,39.1,3.6,40.1], regions:[
      {n:"Palma", at:[2.65,39.57], url:"reise-mallorca.html"},
      {n:"Tramuntana", at:[2.80,39.75], url:"reise-mallorca.html"},
      {n:"Alcúdia", at:[3.12,39.85], url:"reise-mallorca.html"} ] },
    { iso:"ITA", name:"Sardinien", at:[9.0,40.1], box:[7.9,38.7,10.1,41.4], regions:[
      {n:"La Pelosa", at:[8.20,40.95], url:"reise-sardinien.html"},
      {n:"Olbia", at:[9.50,40.92], url:"reise-sardinien.html"},
      {n:"Cala Gonone", at:[9.62,40.28], url:"reise-sardinien.html"} ] },
    { iso:"PRT", name:"Portugal", at:[-8.4,39.6], box:[-9.8,36.7,-6.7,41.5], regions:[
      {n:"Lissabon", at:[-9.14,38.72], url:"reise-portugal.html"},
      {n:"Porto", at:[-8.61,41.15], url:"reise-portugal.html"},
      {n:"Algarve", at:[-8.67,37.10], url:"reise-portugal.html"} ] },
    { iso:"ITA", name:"Sizilien", at:[14.1,37.6], box:[12.1,36.4,15.9,38.5], regions:[
      {n:"Palermo", at:[13.36,38.12], url:"reise-sizilien.html"},
      {n:"Ätna / Catania", at:[15.00,37.6], url:"reise-sizilien.html"},
      {n:"Taormina", at:[15.28,37.85], url:"reise-sizilien.html"} ] }
  ];

  var stage = document.getElementById("map-stage");
  var pinLayer = document.getElementById("map-pins");
  var wrap = document.getElementById("worldmap");
  if (!stage || !pinLayer || !wrap) return;

  // ---------------- SVG-Basiskarte ----------------
  var NS = "http://www.w3.org/2000/svg";
  var svg = document.createElementNS(NS, "svg");
  svg.setAttribute("viewBox", "0 0 " + W + " " + H);
  svg.setAttribute("preserveAspectRatio", "xMidYMid meet");
  var vp = document.createElementNS(NS, "g");
  vp.setAttribute("class", "map-viewport");

  // Gitternetz
  var grid = "";
  for (var lon = -180; lon <= 180; lon += 20) { var a = px(lon, -85), b = px(lon, 85); grid += "M" + a[0].toFixed(1) + " " + a[1].toFixed(1) + "L" + b[0].toFixed(1) + " " + b[1].toFixed(1); }
  for (var lat = -80; lat <= 80; lat += 20) { var c = px(-180, lat), d = px(180, lat); grid += "M" + c[0].toFixed(1) + " " + c[1].toFixed(1) + "L" + d[0].toFixed(1) + " " + d[1].toFixed(1); }
  var gridEl = document.createElementNS(NS, "path");
  gridEl.setAttribute("class", "map-grid"); gridEl.setAttribute("d", grid);
  vp.appendChild(gridEl);

  // Länder
  var gLand = document.createElementNS(NS, "g");
  gLand.setAttribute("class", "map-land");
  var visited = {}; TRIPS.forEach(function (t) { visited[t.iso] = true; });
  Object.keys(window.WORLD_PATHS).forEach(function (iso) {
    var p = document.createElementNS(NS, "path");
    p.setAttribute("d", window.WORLD_PATHS[iso]);
    if (visited[iso]) { p.setAttribute("class", "visited"); p.setAttribute("data-iso", iso); }
    gLand.appendChild(p);
  });
  vp.appendChild(gLand);

  // Reise-Route (nach Längengrad sortiert für einen ruhigen Verlauf)
  var routeOrder = TRIPS.slice().sort(function (a, b) { return a.at[0] - b.at[0]; });
  var rd = "";
  routeOrder.forEach(function (t, i) { var q = px(t.at[0], t.at[1]); rd += (i ? "L" : "M") + q[0].toFixed(1) + " " + q[1].toFixed(1); });
  var routeEl = document.createElementNS(NS, "path");
  routeEl.setAttribute("class", "map-route"); routeEl.setAttribute("d", rd);
  vp.appendChild(routeEl);

  svg.appendChild(vp);
  stage.appendChild(svg);

  // ---------------- Bedienelemente ----------------
  var chip = document.createElement("div"); chip.className = "map-title-chip"; stage.appendChild(chip);
  var controls = document.createElement("div"); controls.className = "map-controls";
  function ctrl(label, title) { var b = document.createElement("button"); b.type = "button"; b.className = "map-ctrl"; b.innerHTML = label; b.title = title; controls.appendChild(b); return b; }
  var btnIn = ctrl("+", "Hineinzoomen");
  var btnOut = ctrl("&minus;", "Herauszoomen");
  var btnHome = ctrl('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11l9-8 9 8M5 10v10h14V10" stroke-linecap="round" stroke-linejoin="round"/></svg>', "Ganze Welt");
  stage.appendChild(controls);
  var backBtn = document.getElementById("map-back");

  // ---------------- Zustand & Transform ----------------
  var k = 1, tx = 0, ty = 0, activeIndex = -1, animTimer = null;

  function clamp(v, lo, hi) { return v < lo ? lo : v > hi ? hi : v; }
  function clampView() {
    k = clamp(k, 1, KMAX);
    tx = clamp(tx, W - W * k, 0);
    ty = clamp(ty, H - H * k, 0);
  }
  function apply() {
    vp.setAttribute("transform", "translate(" + tx.toFixed(2) + " " + ty.toFixed(2) + ") scale(" + k.toFixed(4) + ")");
    positionPins();
  }
  function toScreen(at) {
    var w = px(at[0], at[1]);
    var ux = w[0] * k + tx, uy = w[1] * k + ty;
    var r = stage.getBoundingClientRect();
    return [ ux / W * r.width, uy / H * r.height ];
  }

  // ---------------- Pins ----------------
  var countryPins = [], regionPins = [];
  TRIPS.forEach(function (t, i) {
    var b = document.createElement("button");
    b.className = "map-pin map-pin--country"; b.type = "button";
    b.setAttribute("aria-label", t.name + " – Reisebericht");
    b.innerHTML = '<span class="dot"></span><span class="label">' + t.name + '</span>';
    b.addEventListener("click", function (e) { e.stopPropagation(); openCountry(i); });
    pinLayer.appendChild(b);
    countryPins.push({ el: b, at: t.at });
  });
  function buildRegionPins(t) {
    regionPins.forEach(function (r) { r.el.remove(); });
    regionPins = [];
    t.regions.forEach(function (r) {
      var a = document.createElement("a");
      a.className = "map-pin map-pin--region"; a.href = r.url;
      a.innerHTML = '<span class="drop"></span><span class="rlabel">' + r.n + '</span>';
      pinLayer.appendChild(a);
      regionPins.push({ el: a, at: r.at });
    });
  }
  function positionPins() {
    var i, s;
    for (i = 0; i < countryPins.length; i++) { s = toScreen(countryPins[i].at); countryPins[i].el.style.left = s[0] + "px"; countryPins[i].el.style.top = s[1] + "px"; }
    for (i = 0; i < regionPins.length; i++) { s = toScreen(regionPins[i].at); regionPins[i].el.style.left = s[0] + "px"; regionPins[i].el.style.top = s[1] + "px"; }
  }

  // ---------------- Animation ----------------
  function animateTo(nk, ntx, nty, cb) {
    if (animTimer) clearTimeout(animTimer);
    var k0 = k, x0 = tx, y0 = ty, t0 = Date.now(), dur = 620;
    (function step() {
      var p = Math.min(1, (Date.now() - t0) / dur);
      var e = p < .5 ? 4 * p * p * p : 1 - Math.pow(-2 * p + 2, 3) / 2; // easeInOutCubic
      k = k0 + (nk - k0) * e; tx = x0 + (ntx - x0) * e; ty = y0 + (nty - y0) * e;
      clampView(); apply();
      if (p < 1) animTimer = setTimeout(step, 16); else { k = nk; tx = ntx; ty = nty; clampView(); apply(); if (cb) cb(); }
    })();
  }
  function viewForBox(box) {
    var p1 = px(box[0], box[3]), p2 = px(box[2], box[1]);
    var bw = p2[0] - p1[0], bh = p2[1] - p1[1];
    var nk = clamp(Math.min(W / bw, H / bh) * 0.70, 1, KMAX);
    var cx = (p1[0] + p2[0]) / 2, cy = (p1[1] + p2[1]) / 2;
    return [nk, W / 2 - cx * nk, H / 2 - cy * nk];
  }

  // ---------------- Interaktion: Öffnen / Zurück ----------------
  function openCountry(i) {
    activeIndex = i; var t = TRIPS[i];
    wrap.classList.add("zoomed");
    chip.textContent = t.name;
    buildRegionPins(t);
    var v = viewForBox(t.box);
    animateTo(v[0], v[1], v[2], function () {
      regionPins.forEach(function (r, j) { setTimeout(function () { r.el.classList.add("show"); }, j * 70); });
    });
  }
  function reset() {
    activeIndex = -1;
    wrap.classList.remove("zoomed");
    regionPins.forEach(function (r) { r.el.classList.remove("show"); });
    var stale = regionPins; setTimeout(function () { stale.forEach(function (r) { r.el.remove(); }); }, 300);
    regionPins = [];
    animateTo(1, 0, 0);
  }
  if (backBtn) backBtn.addEventListener("click", reset);
  btnHome.addEventListener("click", reset);

  gLand.addEventListener("click", function (e) {
    var iso = e.target && e.target.getAttribute("data-iso");
    if (!iso) return;
    for (var i = 0; i < TRIPS.length; i++) if (TRIPS[i].iso === iso) { openCountry(i); return; }
  });

  // ---------------- Zoom-Buttons ----------------
  function zoomAt(cx, cy, factor) {
    var r = stage.getBoundingClientRect();
    var ux = cx / r.width * W, uy = cy / r.height * H;
    var wx = (ux - tx) / k, wy = (uy - ty) / k;
    k = clamp(k * factor, 1, KMAX);
    tx = ux - wx * k; ty = uy - wy * k;
    clampView(); apply();
    if (k < 1.25 && activeIndex >= 0) softClear();
  }
  function softClear() { // aus dem Zoom heraus: UI zurücksetzen, Ansicht lassen
    activeIndex = -1; wrap.classList.remove("zoomed");
    regionPins.forEach(function (r) { r.el.remove(); }); regionPins = [];
  }
  btnIn.addEventListener("click", function () { var r = stage.getBoundingClientRect(); zoomAt(r.width / 2, r.height / 2, 1.6); });
  btnOut.addEventListener("click", function () { var r = stage.getBoundingClientRect(); zoomAt(r.width / 2, r.height / 2, 1 / 1.6); });

  // ---------------- Mausrad ----------------
  stage.addEventListener("wheel", function (e) {
    e.preventDefault();
    if (animTimer) { clearTimeout(animTimer); animTimer = null; }
    var r = stage.getBoundingClientRect();
    var factor = Math.exp(-e.deltaY * 0.0016);
    factor = clamp(factor, 0.6, 1.7);
    zoomAt(e.clientX - r.left, e.clientY - r.top, factor);
  }, { passive: false });

  // ---------------- Ziehen & Pinch (Pointer Events) ----------------
  var pointers = {}, last = null, pinchDist = 0;
  stage.addEventListener("pointerdown", function (e) {
    if (e.target.closest && e.target.closest(".map-pin")) return; // Pins selbst behandeln Klicks
    stage.setPointerCapture(e.pointerId);
    pointers[e.pointerId] = { x: e.clientX, y: e.clientY };
    if (Object.keys(pointers).length === 1) { last = { x: e.clientX, y: e.clientY }; }
    if (animTimer) { clearTimeout(animTimer); animTimer = null; }
  });
  stage.addEventListener("pointermove", function (e) {
    if (!pointers[e.pointerId]) return;
    pointers[e.pointerId] = { x: e.clientX, y: e.clientY };
    var ids = Object.keys(pointers), r = stage.getBoundingClientRect();
    if (ids.length >= 2) {
      var p1 = pointers[ids[0]], p2 = pointers[ids[1]];
      var dist = Math.hypot(p1.x - p2.x, p1.y - p2.y);
      var mx = (p1.x + p2.x) / 2 - r.left, my = (p1.y + p2.y) / 2 - r.top;
      if (pinchDist) zoomAt(mx, my, dist / pinchDist);
      pinchDist = dist; last = null;
    } else if (last) {
      stage.classList.add("dragging");
      tx += (e.clientX - last.x) / r.width * W;
      ty += (e.clientY - last.y) / r.height * H;
      last = { x: e.clientX, y: e.clientY };
      clampView(); apply();
    }
  });
  function endPointer(e) {
    delete pointers[e.pointerId];
    if (Object.keys(pointers).length < 2) pinchDist = 0;
    if (Object.keys(pointers).length === 0) { last = null; stage.classList.remove("dragging"); }
    else { var id = Object.keys(pointers)[0]; last = { x: pointers[id].x, y: pointers[id].y }; }
  }
  stage.addEventListener("pointerup", endPointer);
  stage.addEventListener("pointercancel", endPointer);

  // ---------------- Init ----------------
  window.addEventListener("resize", positionPins);
  apply();
})();
