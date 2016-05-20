window.addEventListener("load", function() {
    var overlay = new Overlay(document.getElementById("surveillance-laws-overlay"));
    document.querySelector(".surveillance-laws a").addEventListener("click", function(e) {
        e.preventDefault();
        overlay.show();
    }, true);
    document.getElementById("close_surveillance-laws-overlay").addEventListener("mouseup", function(e) {
        e.preventDefault();
        overlay.hide();
    }, true);
});
