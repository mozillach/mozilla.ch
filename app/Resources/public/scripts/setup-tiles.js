window.addEventListener("load", function() {
  var masonry = new Masonry(document.querySelector('.promo-grid'), {
    itemSelector : '.item',
    columnWidth : 160
  });

  // watch the wide tiles for size changes, so the grid doesn't break.
  var wideTiles = document.querySelectorAll(".promo-large-landscape"),
      layout = function() { masonry.layout(); };
  for(var i = 0; i < wideTiles.length; ++i) {
    new ResizeSensor(wideTiles[i], layout);
  }
});
