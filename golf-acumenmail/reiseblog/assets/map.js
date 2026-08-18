/* Interaktive Weltkarte – Zuhause in der Welt
   Selbst-gehostet, ohne externe Karten-Dienste (DSGVO-freundlich).
   Alle Marker liegen IM SVG -> sie bewegen sich fest mit der Karte mit.
   Bedienung: Land klicken (Zoom), Mausrad, Ziehen, Pinch, +/-/Home.
   Basiskarte: window.WORLD_PATHS (map-data.js), equirektangular 1000x500. */
(function () {
  "use strict";
  if (!window.WORLD_PATHS) return;

  var W = 1000, H = 500, KMAX = 160;
  function px(lon, lat) { return [ (lon + 180) * W / 360, (90 - lat) * H / 180 ]; }
  function clamp(v, lo, hi) { return v < lo ? lo : v > hi ? hi : v; }

  // ---------------- Reisedaten ----------------
  // Kommen aus assets/map-trips.js (erzeugt von tools/generate.py).
  var TRIPS = window.MAP_TRIPS || [];
  if (!TRIPS.length) return;

  var stage = document.getElementById("map-stage");
  var wrap = document.getElementById("worldmap");
  if (!stage || !wrap) return;
  var oldPins = document.getElementById("map-pins");
  if (oldPins) oldPins.remove();               // alte HTML-Pin-Ebene entfernen

  var NS = "http://www.w3.org/2000/svg";
  function el(name, attrs) {
    var e = document.createElementNS(NS, name);
    if (attrs) for (var k in attrs) e.setAttribute(k, attrs[k]);
    return e;
  }

  // ---------------- SVG-Grundgerüst ----------------
  var svg = el("svg", { viewBox: "0 0 " + W + " " + H, preserveAspectRatio: "xMidYMid slice" });

  // Farbverläufe
  var defs = el("defs");
  var gv = el("linearGradient", { id: "gvisit", x1: "0", y1: "0", x2: "0", y2: "1" });
  gv.appendChild(el("stop", { offset: "0", "stop-color": "#7fc9bd" }));
  gv.appendChild(el("stop", { offset: "1", "stop-color": "#5cb3a7" }));
  defs.appendChild(gv);
  var gd = el("radialGradient", { id: "gdot", cx: "35%", cy: "30%", r: "70%" });
  gd.appendChild(el("stop", { offset: "0", "stop-color": "#f09b78" }));
  gd.appendChild(el("stop", { offset: "1", "stop-color": "#c95f37" }));
  defs.appendChild(gd);
  svg.appendChild(defs);

  var vp = el("g");            // Viewport: wird transformiert

  // Gitternetz
  var grid = "", i;
  for (i = -180; i <= 180; i += 20) { var a = px(i, -85), b = px(i, 85); grid += "M" + a[0].toFixed(1) + " " + a[1].toFixed(1) + "L" + b[0].toFixed(1) + " " + b[1].toFixed(1); }
  for (i = -80; i <= 80; i += 20) { var c = px(-180, i), d = px(180, i); grid += "M" + c[0].toFixed(1) + " " + c[1].toFixed(1) + "L" + d[0].toFixed(1) + " " + d[1].toFixed(1); }
  vp.appendChild(el("path", { "class": "map-grid", d: grid }));

  // Länder
  var gLand = el("g", { "class": "map-lands" });
  var visited = {};
  TRIPS.forEach(function (t) { visited[t.iso.replace(/2$/, "")] = true; });
  Object.keys(window.WORLD_PATHS).forEach(function (iso) {
    var p = el("path", { d: window.WORLD_PATHS[iso] });
    if (visited[iso]) { p.setAttribute("class", "visited"); p.setAttribute("data-iso", iso); }
    gLand.appendChild(p);
  });
  vp.appendChild(gLand);

  // Reise-Route
  var order = TRIPS.slice().sort(function (a, b) { return a.at[0] - b.at[0]; });
  var rd = "";
  order.forEach(function (t, j) { var q = px(t.at[0], t.at[1]); rd += (j ? "L" : "M") + q[0].toFixed(1) + " " + q[1].toFixed(1); });
  vp.appendChild(el("path", { "class": "map-route", d: rd }));

  var gRegions = el("g");      // Regions-Pins (Detailansicht)
  var gCountry = el("g");      // Länder-Marker (Weltansicht)
  vp.appendChild(gRegions);
  vp.appendChild(gCountry);
  svg.appendChild(vp);
  stage.appendChild(svg);

  // ---------------- Zustand ----------------
  var k = 1, tx = 0, ty = 0, active = -1, timer = null;

  function clampView() {
    k = clamp(k, 1, KMAX);
    tx = clamp(tx, W - W * k, 0);
    ty = clamp(ty, H - H * k, 0);
  }
  function apply() {
    vp.setAttribute("transform", "translate(" + tx.toFixed(2) + " " + ty.toFixed(2) + ") scale(" + k.toFixed(4) + ")");
    // Marker gegenskalieren, damit sie immer gleich groß erscheinen
    var s = 1 / k;
    [].forEach.call(vp.querySelectorAll("[data-anchor]"), function (g) {
      var p = g.getAttribute("data-anchor").split(",");
      g.setAttribute("transform", "translate(" + p[0] + " " + p[1] + ") scale(" + s + ")");
    });
  }

  // ---------------- Marker bauen ----------------
  function textWidth(str) { return str.length * 6.2 + 18; }

  TRIPS.forEach(function (t, idx) {
    var p = px(t.at[0], t.at[1]);
    var g = el("g", { "class": "mpin-c", "data-anchor": p[0].toFixed(1) + "," + p[1].toFixed(1),
                      tabindex: "0", role: "button" });
    var pulse = el("circle", { "class": "pulse", r: "7" });
    var anim = el("animate", { attributeName: "r", values: "7;20", dur: "2.4s", repeatCount: "indefinite" });
    var animO = el("animate", { attributeName: "opacity", values: ".5;0", dur: "2.4s", repeatCount: "indefinite" });
    pulse.appendChild(anim); pulse.appendChild(animO);
    g.appendChild(pulse);
    g.appendChild(el("circle", { "class": "cdot", r: "7" }));

    var lw = textWidth(t.name);
    var lab = el("g", { "class": "mlabel", transform: "translate(0 -14)" });
    lab.appendChild(el("rect", { x: (-lw / 2).toFixed(1), y: "-17", width: lw.toFixed(1), height: "19", rx: "5" }));
    var tt = el("text", { x: "0", y: "-3.5", "text-anchor": "middle" });
    tt.textContent = t.name;
    lab.appendChild(tt);
    g.appendChild(lab);

    var title = el("title"); title.textContent = t.name + " – Reisebericht öffnen"; g.appendChild(title);

    g.addEventListener("click", function (e) { e.stopPropagation(); openCountry(idx); });
    g.addEventListener("keydown", function (e) { if (e.key === "Enter" || e.key === " ") { e.preventDefault(); openCountry(idx); } });
    gCountry.appendChild(g);
  });

  function buildRegions(t) {
    while (gRegions.firstChild) gRegions.removeChild(gRegions.firstChild);
    t.regions.forEach(function (r, j) {
      var p = px(r.at[0], r.at[1]);
      var a = el("a", { "class": "mpin-r", "data-anchor": p[0].toFixed(1) + "," + p[1].toFixed(1) });
      a.setAttributeNS("http://www.w3.org/1999/xlink", "href", r.url);
      a.setAttribute("href", r.url);
      // Tropfen-Pin
      a.appendChild(el("path", { "class": "rdrop",
        d: "M0 0 C-7 -9 -9 -13 -9 -17 A9 9 0 0 1 9 -17 C9 -13 7 -9 0 0 Z" }));
      a.appendChild(el("circle", { cx: "0", cy: "-17", r: "3.4", fill: "#fff" }));
      var lw = textWidth(r.n);
      a.appendChild(el("rect", { "class": "rbg", x: (-lw / 2).toFixed(1), y: "4", width: lw.toFixed(1), height: "18", rx: "9" }));
      var tt = el("text", { x: "0", y: "16.5", "text-anchor": "middle" });
      tt.textContent = r.n;
      a.appendChild(tt);
      var title = el("title"); title.textContent = r.n + " – zum Reisebericht"; a.appendChild(title);
      gRegions.appendChild(a);
      setTimeout(function () { a.classList.add("show"); }, 220 + j * 90);
    });
  }

  // ---------------- Bedienelemente ----------------
  var chip = document.createElement("div"); chip.className = "map-title-chip"; stage.appendChild(chip);
  var controls = document.createElement("div"); controls.className = "map-controls";
  function ctrl(html, title) {
    var b = document.createElement("button"); b.type = "button"; b.className = "map-ctrl";
    b.innerHTML = html; b.title = title; controls.appendChild(b); return b;
  }
  var btnIn = ctrl("+", "Hineinzoomen");
  var btnOut = ctrl("&minus;", "Herauszoomen");
  var btnHome = ctrl('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11l9-8 9 8M5 10v10h14V10" stroke-linecap="round" stroke-linejoin="round"/></svg>', "Ganze Welt");
  stage.appendChild(controls);
  var backBtn = document.getElementById("map-back");

  // ---------------- Animation ----------------
  function animateTo(nk, nx, ny, cb) {
    if (timer) { clearTimeout(timer); timer = null; }
    var k0 = k, x0 = tx, y0 = ty, t0 = Date.now(), dur = 620;
    (function step() {
      var q = Math.min(1, (Date.now() - t0) / dur);
      var e = q < .5 ? 4 * q * q * q : 1 - Math.pow(-2 * q + 2, 3) / 2;
      k = k0 + (nk - k0) * e; tx = x0 + (nx - x0) * e; ty = y0 + (ny - y0) * e;
      clampView(); apply();
      if (q < 1) timer = setTimeout(step, 16);
      else { timer = null; k = nk; tx = nx; ty = ny; clampView(); apply(); if (cb) cb(); }
    })();
  }
  function viewForBox(box) {
    var p1 = px(box[0], box[3]), p2 = px(box[2], box[1]);
    var bw = Math.max(p2[0] - p1[0], 0.5), bh = Math.max(p2[1] - p1[1], 0.5);
    var nk = clamp(Math.min(W / bw, H / bh) * 0.62, 1, KMAX);
    var cx = (p1[0] + p2[0]) / 2, cy = (p1[1] + p2[1]) / 2;
    return [nk, W / 2 - cx * nk, H / 2 - cy * nk];
  }

  // ---------------- Öffnen / Zurück ----------------
  function openCountry(idx) {
    active = idx;
    var t = TRIPS[idx];
    wrap.classList.add("zoomed");
    chip.textContent = t.name;
    buildRegions(t);
    var v = viewForBox(t.box);
    animateTo(v[0], v[1], v[2]);
  }
  function reset() {
    active = -1;
    wrap.classList.remove("zoomed");
    while (gRegions.firstChild) gRegions.removeChild(gRegions.firstChild);
    animateTo(1, 0, 0);
  }
  if (backBtn) backBtn.addEventListener("click", reset);
  btnHome.addEventListener("click", reset);

  gLand.addEventListener("click", function (e) {
    var iso = e.target && e.target.getAttribute && e.target.getAttribute("data-iso");
    if (!iso) return;
    for (var j = 0; j < TRIPS.length; j++) {
      if (TRIPS[j].iso.replace(/2$/, "") === iso) { openCountry(j); return; }
    }
  });

  // ---------------- Zoom ----------------
  function zoomAt(cx, cy, factor) {
    var r = stage.getBoundingClientRect();
    var ux = cx / r.width * W, uy = cy / r.height * H;
    var wx = (ux - tx) / k, wy = (uy - ty) / k;
    k = clamp(k * factor, 1, KMAX);
    tx = ux - wx * k; ty = uy - wy * k;
    clampView(); apply();
    if (k <= 1.02 && active >= 0) { active = -1; wrap.classList.remove("zoomed"); while (gRegions.firstChild) gRegions.removeChild(gRegions.firstChild); }
  }
  btnIn.addEventListener("click", function () { var r = stage.getBoundingClientRect(); zoomAt(r.width / 2, r.height / 2, 1.6); });
  btnOut.addEventListener("click", function () { var r = stage.getBoundingClientRect(); zoomAt(r.width / 2, r.height / 2, 1 / 1.6); });

  stage.addEventListener("wheel", function (e) {
    e.preventDefault();
    if (timer) { clearTimeout(timer); timer = null; }
    var r = stage.getBoundingClientRect();
    zoomAt(e.clientX - r.left, e.clientY - r.top, clamp(Math.exp(-e.deltaY * 0.0016), 0.6, 1.7));
  }, { passive: false });

  // ---------------- Ziehen & Pinch ----------------
  var pts = {}, last = null, pinch = 0, moved = 0;
  stage.addEventListener("pointerdown", function (e) {
    pts[e.pointerId] = { x: e.clientX, y: e.clientY };
    if (Object.keys(pts).length === 1) { last = { x: e.clientX, y: e.clientY }; moved = 0; }
    if (timer) { clearTimeout(timer); timer = null; }
  });
  stage.addEventListener("pointermove", function (e) {
    if (!pts[e.pointerId]) return;
    pts[e.pointerId] = { x: e.clientX, y: e.clientY };
    var ids = Object.keys(pts), r = stage.getBoundingClientRect();
    if (ids.length >= 2) {
      var p1 = pts[ids[0]], p2 = pts[ids[1]];
      var dist = Math.hypot(p1.x - p2.x, p1.y - p2.y);
      if (pinch) zoomAt((p1.x + p2.x) / 2 - r.left, (p1.y + p2.y) / 2 - r.top, dist / pinch);
      pinch = dist; last = null;
    } else if (last) {
      var dx = e.clientX - last.x, dy = e.clientY - last.y;
      moved += Math.abs(dx) + Math.abs(dy);
      if (moved > 4) stage.classList.add("dragging");
      tx += dx / r.width * W; ty += dy / r.height * H;
      last = { x: e.clientX, y: e.clientY };
      clampView(); apply();
    }
  });
  function endPointer(e) {
    delete pts[e.pointerId];
    var n = Object.keys(pts).length;
    if (n < 2) pinch = 0;
    if (n === 0) { last = null; stage.classList.remove("dragging"); }
    else { last = { x: pts[Object.keys(pts)[0]].x, y: pts[Object.keys(pts)[0]].y }; }
  }
  stage.addEventListener("pointerup", endPointer);
  stage.addEventListener("pointercancel", endPointer);
  stage.addEventListener("pointerleave", endPointer);

  apply();
})();
