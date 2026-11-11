/* Pettenò Tours — selettore galleria (album) nell'editor dei Mezzi.
 * Apre il media frame di WordPress in modalità multipla e tiene aggiornato
 * il campo nascosto con la lista di ID, più l'anteprima.
 */
(function ($) {
  "use strict";

  var cfg = window.pettenoAdmin || {};

  function makeItem(id, url) {
    return $(
      '<span class="petteno-gallery-item" data-id="' + id + '">' +
        '<img src="' + url + '" alt="" />' +
        '<button type="button" class="petteno-gallery-remove" aria-label="Rimuovi">&times;</button>' +
        "</span>"
    );
  }

  $(document).on("click", ".petteno-gallery-add", function (e) {
    e.preventDefault();
    var $wrap = $(this).closest(".petteno-gallery");
    var $input = $wrap.find(".petteno-gallery-ids");
    var $items = $wrap.find(".petteno-gallery-items");

    var frame = wp.media({
      title: cfg.frameTitle || "Seleziona le foto",
      button: { text: cfg.addButton || "Aggiungi alle foto" },
      library: { type: "image" },
      multiple: true,
    });

    frame.on("select", function () {
      var ids = $input.val() ? $input.val().split(",") : [];
      frame
        .state()
        .get("selection")
        .each(function (att) {
          var a = att.toJSON();
          var id = String(a.id);
          if (ids.indexOf(id) !== -1) return; // già presente
          ids.push(id);
          var url =
            a.sizes && a.sizes.thumbnail ? a.sizes.thumbnail.url : a.url;
          $items.append(makeItem(id, url));
        });
      $input.val(ids.join(","));
    });

    frame.open();
  });

  $(document).on("click", ".petteno-gallery-remove", function (e) {
    e.preventDefault();
    var $item = $(this).closest(".petteno-gallery-item");
    var $wrap = $(this).closest(".petteno-gallery");
    var $input = $wrap.find(".petteno-gallery-ids");
    var id = String($item.data("id"));
    var ids = ($input.val() ? $input.val().split(",") : []).filter(function (x) {
      return x !== id;
    });
    $input.val(ids.join(","));
    $item.remove();
  });
})(jQuery);
