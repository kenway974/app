let lastScrollTop = 0;
const navbar = document.getElementById("customNavbar");

window.addEventListener("scroll", function () {
    const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

    if (window.matchMedia("(orientation: landscape)").matches) {
        if (currentScroll > lastScrollTop) {
            // scroll down
            navbar.style.top = "-100px";
        } else {
            // scroll up
            navbar.style.top = "0";
        }
    }
    lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
}, false);
