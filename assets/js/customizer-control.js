/* Pettenò Tours — controllo Customizer "lista immagini" (slideshow hero).
 * Aggiunge/rimuove immagini via media frame e tiene aggiornato il campo
 * nascosto (con trigger 'change' perché il Customizer registri il valore).
 */
(function ($) {
  "use strict";

  function thumbFor(id, cb) {
    var att = wp.media.attachment(id);
    if (att.get("url")) {
      cb(thumbUrl(att));
    } else {
      att.fetch().then(function () {
        cb(thumbUrl(att));
      });
    }
  }

  function thumbUrl(att) {
    var sizes = att.get("sizes");
    if (sizes && sizes.thumbnail) return sizes.thumbnail.url;
    return att.get("url");
  }

  function makeItem(id, url) {
    return $(
      '<span class="petteno-imglist-item" data-id="' + id + '">' +
        '<img src="' + url + '" alt="" />' +
        '<button type="button" class="petteno-imglist-remove" aria-label="Rimuovi">&times;</button>' +
        "</span>"
    );
  }

  function render($wrap) {
    var $items = $wrap.find(".petteno-imglist-items").empty();
    var val = $wrap.find(".petteno-imglist-ids").val();
    var ids = val ? val.split(",") : [];
    ids.forEach(function (id) {
      id = $.trim(id);
      if (!id) return;
      thumbFor(id, function (url) {
        $items.append(makeItem(id, url));
      });
    });
  }

  $(document).on("click", ".petteno-imglist-add", function (e) {
    e.preventDefault();
    var $wrap = $(this).closest(".petteno-imglist");
    var $input = $wrap.find(".petteno-imglist-ids");

    var frame = wp.media({
      title: "Seleziona le immagini",
      button: { text: "Aggiungi" },
      library: { type: "image" },
      multiple: true,
    });

    frame.on("select", function () {
      var ids = $input.val() ? $input.val().split(",") : [];
      frame
        .state()
        .get("selection")
        .each(function (att) {
          var id = String(att.id);
          if (ids.indexOf(id) === -1) ids.push(id);
        });
      $input.val(ids.join(",")).trigger("change");
      render($wrap);
    });

    frame.open();
  });

  $(document).on("click", ".petteno-imglist-remove", function (e) {
    e.preventDefault();
    var $item = $(this).closest(".petteno-imglist-item");
    var $wrap = $(this).closest(".petteno-imglist");
    var $input = $wrap.find(".petteno-imglist-ids");
    var id = String($item.data("id"));
    var ids = ($input.val() ? $input.val().split(",") : []).filter(function (x) {
      return $.trim(x) !== id;
    });
    $input.val(ids.join(",")).trigger("change");
    $item.remove();
  });

  $(function () {
    $(".petteno-imglist").each(function () {
      render($(this));
    });
  });
})(jQuery);
