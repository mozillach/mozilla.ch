// Responsive menu stuff
window.addEventListener("load", function() {
    // adding js class to doc-root, so we can profit from the menu toggling css
    document.documentElement.classList.add("js");
    var button = document.querySelector("#nav-main>span"),
        popup = document.getElementById("nav-main-menu");
    button.addEventListener("click", function() {
        if(button.getAttribute("aria-expanded") === "true") {
            popup.style.display = "none";
            button.setAttribute("aria-expanded", false);
            button.classList.remove("open");
        }
        else {
            popup.style.display = "block";
            button.setAttribute("aria-expanded", true);
            button.classList.add("open");
            button.focus();
        }
    });

    // remove manual menu visibility when screen is big enough after resize
    var mq = window.matchMedia("(min-width: 753px)");
    mq.addListener(function() {
        if(mq.matches)
            popup.style.display = "";
    });
});

