window.addEventListener("load", function() {
    var overlay = new Overlay(document.getElementById("overlay"));
    document.querySelector(".ch-apps a").addEventListener("click", function(e) {
        e.preventDefault();
        overlay.show();
    });
    document.getElementById("close_overlay").addEventListener("click", function(e) {
        overlay.hide();
    });
});
