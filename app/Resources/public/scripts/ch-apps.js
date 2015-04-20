window.addEventListener("load", function() {
    var overlay = new Overlay(document.getElementById("overlay"));
    document.querySelector(".ch-apps a").addEventListener("click", function(e) {
        e.preventDefault();
        overlay.show();
    }, true);
    document.getElementById("close_overlay").addEventListener("mouseup", function(e) {
        e.preventDefault();
        overlay.hide();
    }, true);
});
