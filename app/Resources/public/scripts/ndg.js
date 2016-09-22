// @license magnet:?xt=urn:btih:3877d6d54b3accd4bc32f8a48bf32ebc0901502a&dn=mpl-2.0.txt MPL-2.0
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
// @license-end
