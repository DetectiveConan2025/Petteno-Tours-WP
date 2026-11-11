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
  var pointerFine = window.matchMedia("(hover: hover) and (pointer: fine)").matches;
  var all = document.querySelectorAll(".reveal");
  var revealAll = function () {
    all.forEach(function (el) {
      el.classList.add("is-in");
    });
  };

  // Cascata: ritardo crescente in base alla posizione fra i ".reveal"
  // fratelli nello stesso contenitore (es. le schede di una griglia).
  var staggerDelay = function (el) {
    var parent = el.parentElement;
    if (!parent) return 0;
    var siblings = Array.prototype.filter.call(parent.children, function (c) {
      return c.classList && c.classList.contains("reveal");
    });
    var idx = siblings.indexOf(el);
    return Math.min(idx < 0 ? 0 : idx, 5) * 70;
  };

  if (reduce || !("IntersectionObserver" in window)) {
    revealAll();
  } else {
    var io = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) {
            e.target.style.transitionDelay = staggerDelay(e.target) + "ms";
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

  /* ---- Navbar compatta allo scroll -------------------------------------- */
  (function () {
    var header = document.querySelector(".site-header");
    if (!header) return;
    var ticking = false;
    var update = function () {
      header.classList.toggle("is-scrolled", window.scrollY > 40);
      ticking = false;
    };
    window.addEventListener(
      "scroll",
      function () {
        if (!ticking) {
          window.requestAnimationFrame(update);
          ticking = true;
        }
      },
      { passive: true }
    );
    update();
  })();

  /* ---- Magnetic hover sui CTA principali -------------------------------- */
  if (pointerFine && !reduce) {
    (function () {
      var buttons = document.querySelectorAll(".btn-primary, .btn-white");
      var strength = 0.25;
      var maxOffset = 6;
      buttons.forEach(function (btn) {
        btn.addEventListener("mousemove", function (e) {
          var rect = btn.getBoundingClientRect();
          var dx = e.clientX - (rect.left + rect.width / 2);
          var dy = e.clientY - (rect.top + rect.height / 2);
          var ox = Math.max(-maxOffset, Math.min(maxOffset, dx * strength));
          var oy = Math.max(-maxOffset, Math.min(maxOffset, dy * strength));
          btn.style.transform = "translate(" + ox + "px, " + oy + "px)";
        });
        btn.addEventListener("mouseleave", function () {
          btn.style.transform = "";
        });
      });
    })();
  }

  /* ---- Parallax leggero sullo sfondo dell'hero -------------------------- */
  if (!reduce && window.innerWidth > 760) {
    (function () {
      var slides = document.querySelector(".slides");
      var hero = document.querySelector(".hero");
      if (!slides || !hero) return;
      var ticking = false;
      var update = function () {
        var rect = hero.getBoundingClientRect();
        // Attivo solo mentre l'hero è (almeno in parte) in vista.
        if (rect.bottom > 0 && rect.top < window.innerHeight) {
          var offset = Math.max(0, -rect.top) * 0.15;
          slides.style.transform = "translateY(" + Math.min(offset, 80) + "px)";
        }
        ticking = false;
      };
      window.addEventListener(
        "scroll",
        function () {
          if (!ticking) {
            window.requestAnimationFrame(update);
            ticking = true;
          }
        },
        { passive: true }
      );
    })();
  }

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
            // Invio riuscito: messaggio dinamico, poi si torna alla home
            // (il modulo resta disabilitato, non serve più riattivarlo).
            hint.textContent =
              "Messaggio inviato! Ti risponderemo al più presto. Torno alla home…";
            form.reset();
            setTimeout(function () {
              window.location.href = cfg.homeUrl || "/";
            }, 2200);
          } else {
            throw new Error("send failed");
          }
        })
        .catch(function () {
          // Invio fallito: avviso dinamico sulla pagina stessa, il modulo
          // resta compilabile per riprovare (nessun redirect).
          hint.classList.add("error");
          hint.textContent =
            "Errore nell'invio. Scrivici direttamente a " +
            (cfg.email || "pettenotours@gmail.com");
          btn.disabled = false;
          btn.textContent = "Invia richiesta";
        });
    });
  })();
})();
