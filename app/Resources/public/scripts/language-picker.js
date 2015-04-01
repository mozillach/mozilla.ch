// Language picker thing
window.addEventListener("load", function() {
    document.getElementById("language").addEventListener("change", function() {
        window.location = this.value;
    });
});

// _gaq shim to make tabzilla language prompt work
window._gaq = {
    push: function(callback) {
        if(typeof(callback) == "function")
            callback();
    }
};

