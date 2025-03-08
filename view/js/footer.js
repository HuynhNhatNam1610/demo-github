document.addEventListener("DOMContentLoaded", function () {
    const logosContainer = document.querySelector(".logos");
    const logos = document.querySelectorAll(".logos div");
    
    function getLogoWidth() {
        return logos[0].getBoundingClientRect().width; 
    }

    function slideLogos() {
        const logoWidth = getLogoWidth();
        logosContainer.style.transition = "transform 0.5s ease-in-out";
        logosContainer.style.transform = `translateX(-${logoWidth}px)`;

        setTimeout(() => {
            logosContainer.style.transition = "none";
            let firstLogo = logosContainer.children[0];
            logosContainer.appendChild(firstLogo);
            logosContainer.style.transform = "translateX(0)";
        }, 500);
    }

    setInterval(slideLogos, 4000);
});

document.addEventListener("DOMContentLoaded", function () {
    const toggleBtns = document.querySelectorAll(".toggle-btn");

    toggleBtns.forEach(btn => {
        btn.addEventListener("click", function (event) {
            event.stopPropagation(); 
            let parent = this.closest(".footer-column");
            let ul = parent.querySelector("ul");

            ul.classList.toggle("active");
            if (ul.classList.contains("active")) {
                this.classList.replace("bi-plus", "bi-dash");
            } else {
                this.classList.replace("bi-dash", "bi-plus");
            }
        });
    });
});

