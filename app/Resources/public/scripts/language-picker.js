// Language picker thing
window.addEventListener("load", function() {
    document.getElementById("language").addEventListener("change", function() {
        window.location = this.value;
    });
});

