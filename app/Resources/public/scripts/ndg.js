window.addEventListener("load", function() {
    var overlay = new Overlay(document.getElementById("ndg-overlay"));
    document.querySelector(".ndg a").addEventListener("click", function(e) {
        e.preventDefault();
        overlay.show();
    }, true);
    document.getElementById("close_ndg-overlay").addEventListener("mouseup", function(e) {
        e.preventDefault();
        overlay.hide();
    }, true);
});
