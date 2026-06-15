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

  /* ---- Slideshow hero (N immagini, crossfade) -------------------------- */
  (function () {
    var box = document.querySelector("[data-slideshow]");
    if (!box) return;
    var slides = box.querySelectorAll(".slide");
    if (slides.length < 2) return; // una sola immagine: niente ciclo
    if (reduce) return; // reduced-motion: resta la prima, già attiva

    var i = 0;
    setInterval(function () {
      slides[i].classList.remove("is-active");
      i = (i + 1) % slides.length;
      slides[i].classList.add("is-active");
    }, 5000);
  })();

  /* ---- Lightbox album mezzi -------------------------------------------- */
  (function () {
    var triggers = document.querySelectorAll(".fleet-album");
    if (!triggers.length) return;

    var images = [];
    var index = 0;
    var box = null;
    var imgEl = null;
    var counterEl = null;
    var lastFocus = null;

    function build() {
      box = document.createElement("div");
      box.className = "lightbox";
      box.setAttribute("role", "dialog");
      box.setAttribute("aria-modal", "true");
      box.setAttribute("aria-label", "Galleria foto");
      box.innerHTML =
        '<button type="button" class="lightbox-btn lightbox-close" aria-label="Chiudi">' +
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg></button>' +
        '<button type="button" class="lightbox-btn lightbox-prev" aria-label="Precedente">' +
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg></button>' +
        '<figure class="lightbox-figure"><img class="lightbox-img" alt="" /><figcaption class="lightbox-counter"></figcaption></figure>' +
        '<button type="button" class="lightbox-btn lightbox-next" aria-label="Successiva">' +
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></button>';
      document.body.appendChild(box);
      imgEl = box.querySelector(".lightbox-img");
      counterEl = box.querySelector(".lightbox-counter");

      box.querySelector(".lightbox-close").addEventListener("click", close);
      box.querySelector(".lightbox-prev").addEventListener("click", function () {
        go(-1);
      });
      box.querySelector(".lightbox-next").addEventListener("click", function () {
        go(1);
      });
      box.addEventListener("click", function (e) {
        if (e.target === box) close();
      });
      document.addEventListener("keydown", function (e) {
        if (!box.classList.contains("is-open")) return;
        if (e.key === "Escape") close();
        else if (e.key === "ArrowLeft") go(-1);
        else if (e.key === "ArrowRight") go(1);
      });
    }

    function show() {
      var item = images[index];
      imgEl.src = item.src;
      imgEl.alt = item.alt || "";
      counterEl.textContent = index + 1 + " / " + images.length;
    }

    function go(step) {
      index = (index + step + images.length) % images.length;
      show();
    }

    function open(album, trigger) {
      if (!box) build();
      images = album;
      index = 0;
      lastFocus = trigger || null;
      show();
      box.classList.add("is-open");
      box.querySelector(".lightbox-close").focus();
    }

    function close() {
      if (!box) return;
      box.classList.remove("is-open");
      if (lastFocus && typeof lastFocus.focus === "function") lastFocus.focus();
    }

    triggers.forEach(function (t) {
      t.addEventListener("click", function () {
        var data;
        try {
          data = JSON.parse(t.getAttribute("data-album"));
        } catch (err) {
          return;
        }
        if (data && data.length) open(data, t);
      });
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
