/* Zuhause in der Welt – kleine Interaktionen (ohne externe Bibliotheken) */
(function () {
  "use strict";

  // Mobile-Navigation ein-/ausklappen
  var toggle = document.querySelector(".nav-toggle");
  var links = document.querySelector(".nav-links");
  if (toggle && links) {
    toggle.addEventListener("click", function () {
      var open = links.classList.toggle("open");
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
    });
    links.addEventListener("click", function (e) {
      if (e.target.tagName === "A") links.classList.remove("open");
    });
  }

  // Newsletter- & Kontaktformular: Demo-Feedback (noch kein echter Versand)
  document.querySelectorAll("form[data-demo]").forEach(function (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var note = form.querySelector(".form-note");
      if (note) {
        note.textContent =
          "Danke! Dies ist noch ein Demo-Formular – binde hier deinen Newsletter-/Mail-Dienst ein.";
        note.style.display = "block";
      }
      form.reset();
    });
  });

  // aktuelles Jahr im Footer
  document.querySelectorAll("[data-year]").forEach(function (el) {
    el.textContent = new Date().getFullYear();
  });
})();
