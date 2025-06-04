// DOM Content Loaded
document.addEventListener("DOMContentLoaded", function () {
  // Initialize all functions
  initSmoothScrolling();
  initScrollAnimations();
  initVideoControls();
  initStatCounters();
  initParallaxEffect();
});

// Smooth scrolling for navigation
function initSmoothScrolling() {
  const heroBtn = document.querySelector(".btn-primary");

  if (heroBtn) {
    heroBtn.addEventListener("click", function (e) {
      e.preventDefault();
      const servicesSection = document.querySelector(".services-section");
      if (servicesSection) {
        servicesSection.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  }
}

// Scroll animations
function initScrollAnimations() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");

        // Trigger counter animation for stats
        if (entry.target.classList.contains("welcome-stats")) {
          animateCounters();
        }
      }
    });
  }, observerOptions);

  // Add fade-in class to elements and observe them
  const animatedElements = [
    ".welcome-content",
    ".service-card",
    ".room-card",
    ".tour-card",
    ".contact-item",
    ".location-info",
  ];

  animatedElements.forEach((selector) => {
    const elements = document.querySelectorAll(selector);
    elements.forEach((el) => {
      el.classList.add("fade-in");
      observer.observe(el);
    });
  });
}

// Video controls
function initVideoControls() {
  const video = document.getElementById("heroVideo");
  const heroSection = document.querySelector(".hero-video");

  if (video && heroSection) {
    // Pause video when not in viewport (performance optimization)
    const videoObserver = new IntersectionObserver(
      function (entries) {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            video.play().catch((e) => console.log("Video play failed:", e));
          } else {
            video.pause();
          }
        });
      },
      { threshold: 0.1 }
    );

    videoObserver.observe(heroSection);

    // Add click to play/pause functionality
    video.addEventListener("click", function () {
      if (video.paused) {
        video.play();
      } else {
        video.pause();
      }
    });

    // Add loading state
    video.addEventListener("loadstart", function () {
      heroSection.style.background =
        "linear-gradient(135deg, #2c3e50 0%, #34495e 100%)";
    });

    video.addEventListener("canplay", function () {
      heroSection.style.background = "transparent";
    });
  }
}

// Animated counters for statistics
function initStatCounters() {
  let countersAnimated = false;

  window.animateCounters = function () {
    if (countersAnimated) return;
    countersAnimated = true;

    const counters = [
      {
        element: document.querySelectorAll(".stat-number")[0],
        target: 45,
        suffix: "",
      },
      {
        element: document.querySelectorAll(".stat-number")[1],
        target: 3,
        suffix: "",
      },
      {
        element: document.querySelectorAll(".stat-number")[2],
        target: 500,
        suffix: "",
      },
      {
        element: document.querySelectorAll(".stat-number")[3],
        target: 700,
        suffix: "",
      },
    ];

    counters.forEach((counter) => {
      if (counter.element) {
        animateCounter(
          counter.element,
          0,
          counter.target,
          2000,
          counter.suffix
        );
      }
    });
  };
}

function animateCounter(element, start, end, duration, suffix = "") {
  const startTime = Date.now();
  const range = end - start;

  function updateCounter() {
    const elapsed = Date.now() - startTime;
    const progress = Math.min(elapsed / duration, 1);

    // Easing function for smooth animation
    const easeOutQuart = 1 - Math.pow(1 - progress, 4);
    const current = Math.floor(start + range * easeOutQuart);

    element.textContent = current + suffix;

    if (progress < 1) {
      requestAnimationFrame(updateCounter);
    } else {
      element.textContent = end + suffix;
    }
  }

  updateCounter();
}

// Parallax effect for hero section
function initParallaxEffect() {
  const heroSection = document.querySelector(".hero-video");
  const heroContent = document.querySelector(".hero-content");

  if (heroSection && heroContent) {
    let ticking = false;

    function updateParallax() {
      const scrolled = window.pageYOffset;
      const parallaxSpeed = 0.5;

      // Move hero content slower than scroll
      heroContent.style.transform = `translateY(${scrolled * parallaxSpeed}px)`;

      // Fade out hero content as user scrolls
      const opacity = Math.max(0, 1 - scrolled / window.innerHeight);
      heroContent.style.opacity = opacity;

      ticking = false;
    }

    function requestParallaxUpdate() {
      if (!ticking) {
        requestAnimationFrame(updateParallax);
        ticking = true;
      }
    }

    window.addEventListener("scroll", requestParallaxUpdate);
  }
}

// Card hover effects
document.addEventListener("DOMContentLoaded", function () {
  // Service cards hover effect
  const serviceCards = document.querySelectorAll(".service-card");
  serviceCards.forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-15px) rotateY(5deg)";
    });

    card.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0) rotateY(0)";
    });
  });

  // Room cards image zoom effect
  const roomCards = document.querySelectorAll(".room-card");
  roomCards.forEach((card) => {
    const img = card.querySelector(".room-image img");

    card.addEventListener("mouseenter", function () {
      if (img) {
        img.style.transform = "scale(1.1)";
      }
    });

    card.addEventListener("mouseleave", function () {
      if (img) {
        img.style.transform = "scale(1)";
      }
    });
  });
});

// Utility functions
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Smooth reveal animation for elements
function revealOnScroll() {
  const reveals = document.querySelectorAll(".fade-in");

  reveals.forEach((element) => {
    const windowHeight = window.innerHeight;
    const elementTop = element.getBoundingClientRect().top;
    const elementVisible = 150;

    if (elementTop < windowHeight - elementVisible) {
      element.classList.add("visible");
    }
  });
}

// Alternative scroll listener for older browsers
if (!window.IntersectionObserver) {
  window.addEventListener("scroll", debounce(revealOnScroll, 10));
}

// Contact form animation (if contact form is added later)
function initContactForm() {
  const form = document.querySelector(".contact-form");
  if (form) {
    const inputs = form.querySelectorAll("input, textarea");

    inputs.forEach((input) => {
      input.addEventListener("focus", function () {
        this.parentElement.classList.add("focused");
      });

      input.addEventListener("blur", function () {
        if (!this.value) {
          this.parentElement.classList.remove("focused");
        }
      });
    });
  }
}

// Error handling for images
document.addEventListener("DOMContentLoaded", function () {
  const images = document.querySelectorAll("img");
  images.forEach((img) => {
    img.addEventListener("error", function () {
      this.style.background =
        "linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%)";
      this.style.display = "flex";
      this.style.alignItems = "center";
      this.style.justifyContent = "center";
      this.innerHTML =
        '<i class="fas fa-image" style="font-size: 3rem; color: #dee2e6;"></i>';
    });
  });
});

// Print styles optimization
if (window.matchMedia) {
  const mediaQueryList = window.matchMedia("print");
  mediaQueryList.addListener(function (mql) {
    if (mql.matches) {
      // Hide video when printing
      const video = document.getElementById("heroVideo");
      if (video) {
        video.style.display = "none";
      }
    }
  });
}
