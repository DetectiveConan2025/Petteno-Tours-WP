/* Pettenò Tours — interazioni front-end.
 * Replica gli script inline del sito Astro originale:
 *  - marcatura "js" per le reveal animation
 *  - reveal on scroll (IntersectionObserver, con safety net)
 *  - menù mobile (toggle, chiusura su click esterno / su link)
 *  - anno del footer riallineato lato client
 *  - invio del form preventivo via admin-ajax (sostituisce mail.php)
 */
(function () {
  "use strict";

  /* ---- Reveal on scroll ------------------------------------------------ */
  document.documentElement.classList.add("js");
  document.body.classList.add("js");

  var reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var all = document.querySelectorAll(".reveal");
  var revealAll = function () {
    all.forEach(function (el) {
      el.classList.add("is-in");
    });
  };

  if (reduce || !("IntersectionObserver" in window)) {
    revealAll();
  } else {
    var io = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) {
            e.target.classList.add("is-in");
            io.unobserve(e.target);
          }
        });
      },
      { rootMargin: "0px 0px -10% 0px", threshold: 0.1 }
    );
    all.forEach(function (el) {
      io.observe(el);
    });
    // Safety net: non lasciare mai contenuto nascosto.
    window.addEventListener("load", function () {
      setTimeout(revealAll, 2500);
    });
  }

  /* ---- Menù mobile ----------------------------------------------------- */
  (function () {
    var toggle = document.querySelector(".nav-toggle");
    var menu = document.getElementById("mobile-nav");
    if (!toggle || !menu) return;
    var setOpen = function (open) {
      toggle.setAttribute("aria-expanded", String(open));
      menu.hidden = !open;
      toggle.setAttribute("aria-label", open ? "Chiudi menù" : "Apri menù");
    };
    toggle.addEventListener("click", function () {
      setOpen(toggle.getAttribute("aria-expanded") !== "true");
    });
    menu.querySelectorAll("a").forEach(function (a) {
      a.addEventListener("click", function () {
        setOpen(false);
      });
    });
    document.addEventListener("click", function (e) {
      if (
        toggle.getAttribute("aria-expanded") === "true" &&
        !menu.contains(e.target) &&
        e.target !== toggle &&
        !toggle.contains(e.target)
      ) {
        setOpen(false);
      }
    });
  })();

  /* ---- Anno del footer ------------------------------------------------- */
  var yearEl = document.getElementById("footer-year");
  if (yearEl) yearEl.textContent = new Date().getFullYear();

  /* ---- Form preventivo ------------------------------------------------- */
  (function () {
    var form = document.getElementById("preventivo");
    if (!form) return;
    var hint = form.querySelector(".form-hint");
    var btn = form.querySelector(".form-submit");
    var cfg = window.pettenoTours || {};

    form.addEventListener("submit", function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        hint.textContent = "Controlla i campi obbligatori (nome, email, telefono).";
        hint.classList.add("error");
        form.reportValidity();
        return;
      }

      btn.disabled = true;
      btn.textContent = "Invio in corso…";
      hint.classList.remove("error");
      hint.textContent = "";

      var data = new FormData(form);
      data.append("action", cfg.action || "petteno_tours_preventivo");
      data.append("_nonce", cfg.nonce || "");

      fetch(cfg.ajaxUrl || "/wp-admin/admin-ajax.php", {
        method: "POST",
        body: data,
      })
        .then(function (res) {
          return res.json();
        })
        .then(function (json) {
          if (json && json.ok) {
            hint.textContent = "Messaggio inviato! Ti risponderemo al più presto.";
            form.reset();
          } else {
            throw new Error("send failed");
          }
        })
        .catch(function () {
          hint.classList.add("error");
          hint.textContent =
            "Errore nell'invio. Scrivici direttamente a " +
            (cfg.email || "pettenotours@gmail.com");
        })
        .then(function () {
          btn.disabled = false;
          btn.textContent = "Invia richiesta";
        });
    });
  })();
})();
