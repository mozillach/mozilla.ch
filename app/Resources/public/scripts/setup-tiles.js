window.addEventListener("load", function() {
  new Masonry(document.querySelector('.promo-grid'), {
    itemSelector : '.item',
    columnWidth : 160
  });
});
