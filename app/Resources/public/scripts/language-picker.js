// @license magnet:?xt=urn:btih:3877d6d54b3accd4bc32f8a48bf32ebc0901502a&dn=mpl-2.0.txt MPL-2.0
// Language picker thing
window.addEventListener("load", function() {
    document.getElementById("language").addEventListener("change", function() {
        window.location = this.value;
    });
});
// @license-end
