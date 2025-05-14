document.addEventListener("DOMContentLoaded", () => {
    // Animation de la barre de progression (XP)
    const progressBar = document.querySelector(".progress");
    if (progressBar) {
        const targetWidth = progressBar.style.width;
        progressBar.style.width = "0";
        setTimeout(() => {
            progressBar.style.width = targetWidth;
        }, 100);
    }

    // Animation simple au scroll
    const items = document.querySelectorAll(".goal, .stat-card, .trophy");
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = 1;
                entry.target.style.transform = "translateY(0)";
            }
        });
    }, {
        threshold: 0.1
    });

    items.forEach(item => {
        item.style.opacity = 0;
        item.style.transform = "translateY(20px)";
        observer.observe(item);
    });
});
