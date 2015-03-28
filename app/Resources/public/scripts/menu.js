// Responsive menu stuff
window.addEventListener("load", function() {
    var button = document.querySelector("#nav-main>span");
    button.addEventListener("click", function() {
        if(button.getAttribute("aria-expanded") === "true") {
            document.querySelector("#nav-main-menu").style.display = "none";
            button.setAttribute("aria-expanded", false);
        }
        else {
            document.querySelector("#nav-main-menu").style.display = "block";
            button.setAttribute("aria-expanded", true);
        }
    });
});

