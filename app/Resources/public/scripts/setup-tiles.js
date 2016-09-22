// @license magnet:?xt=urn:btih:3877d6d54b3accd4bc32f8a48bf32ebc0901502a&dn=mpl-2.0.txt MPL-2.0
(function() {
    document.documentElement.classList.add("js");

    window.addEventListener("load", function() {
        var masonry = new Masonry(document.querySelector('.promo-grid'), {
            itemSelector : '.item',
            columnWidth : 160,
            isInitLayout: false
        });

        masonry.on("layoutComplete", function() {
            var grid = document.querySelector(".promo-grid");
            grid.classList.add("stagger");
            grid.classList.add("reveal");
        });

        masonry.layout();

        // watch the wide tiles for size changes, so the grid doesn't break.
        var wideTiles = document.querySelectorAll(".promo-large-landscape"),
            layout = function() { masonry.layout(); };
        for(var i = 0; i < wideTiles.length; ++i) {
            new ResizeSensor(wideTiles[i], layout);
        }
    });
}) ();
// @license-end
