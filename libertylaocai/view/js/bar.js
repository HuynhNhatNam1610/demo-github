// Floating particles animation
function createParticles() {
  const particlesContainer = document.getElementById("particles");

  for (let i = 0; i < 50; i++) {
    const particle = document.createElement("div");
    particle.className = "particle";
    particle.style.left = Math.random() * 100 + "%";
    particle.style.animationDelay = Math.random() * 6 + "s";
    particle.style.animationDuration = Math.random() * 3 + 4 + "s";
    particlesContainer.appendChild(particle);
  }
}

// Tab navigation
function initTabs() {
  const navBtns = document.querySelectorAll(".nav-btn");
  const tabContents = document.querySelectorAll(".tab-content");

  navBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const targetTab = btn.dataset.tab;

      // Remove active class from all nav buttons and tab contents
      navBtns.forEach((b) => b.classList.remove("active"));
      tabContents.forEach((content) => content.classList.remove("active"));

      // Add active class to clicked button and corresponding tab
      btn.classList.add("active");
      document.getElementById(targetTab).classList.add("active");
    });
  });
}

// Menu category filtering
function initMenuFilters() {
  const categoryBtns = document.querySelectorAll(".category-btn");
  const menuItems = document.querySelectorAll(".menu-item");

  categoryBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const category = btn.dataset.category;

      // Remove active class from all category buttons
      categoryBtns.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");

      // Show/hide menu items based on category
      menuItems.forEach((item) => {
        if (item.dataset.category === category) {
          item.style.display = "block";
          item.style.animation = "fadeIn 0.5s ease";
        } else {
          item.style.display = "none";
        }
      });
    });
  });
}

// Reservation form handling
function initReservationForm() {
  const form = document.getElementById("reservationForm");

  // Set minimum date to today
  const dateInput = document.getElementById("date");
  const today = new Date().toISOString().split("T")[0];
  dateInput.min = today;

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    // Simple validation
    if (!data.name || !data.phone || !data.date || !data.time || !data.guests) {
      alert("Vui lòng điền đầy đủ thông tin bắt buộc!");
      return;
    }

    // Show success message
    alert(
      `Cảm ơn ${data.name}! Yêu cầu đặt bàn của bạn đã được ghi nhận. Chúng tôi sẽ liên hệ với bạn qua số ${data.phone} trong thời gian sớm nhất để xác nhận.`
    );

    // Reset form
    form.reset();
  });
}

// Parallax effect for hero section
function initParallax() {
  window.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset;
    const heroSection = document.querySelector(".hero-section");
    const parallaxSpeed = 0.5;

    if (heroSection) {
      heroSection.style.transform = `translateY(${scrolled * parallaxSpeed}px)`;
    }
  });
}

// Initialize all functionality when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  createParticles();
  initTabs();
  initMenuFilters();
  initReservationForm();
  initSmoothScroll();
  initParallax();
});

// Add some interactive hover effects
document.addEventListener("DOMContentLoaded", () => {
  // Add hover effect to menu items
  const menuItems = document.querySelectorAll(".menu-item");
  menuItems.forEach((item) => {
    item.addEventListener("mouseenter", () => {
      item.style.transform = "translateY(-8px) scale(1.02)";
    });

    item.addEventListener("mouseleave", () => {
      item.style.transform = "translateY(0) scale(1)";
    });
  });

  // Add click effect to buttons
  const buttons = document.querySelectorAll("button, .cta-btn");
  buttons.forEach((btn) => {
    btn.addEventListener("click", function () {
      this.style.transform = "scale(0.95)";
      setTimeout(() => {
        this.style.transform = "";
      }, 150);
    });
  });
});
document.addEventListener("DOMContentLoaded", () => {
  const writeReviewBtn = document.querySelector(".write-review-btn");
  const reviewFormContainer = document.querySelector(".review-form-container");
  const cancelBtn = document.querySelector(".cancel-btn");
  const reviewForm = document.querySelector(".review-form");

  writeReviewBtn.addEventListener("click", () => {
    reviewFormContainer.style.display =
      reviewFormContainer.style.display === "none" ? "block" : "none";
  });

  cancelBtn.addEventListener("click", () => {
    reviewFormContainer.style.display = "none";
    reviewForm.reset();
  });

  reviewForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = new FormData(reviewForm);
    const data = Object.fromEntries(formData.entries());

    if (!data.rating || !data.name || !data.content) {
      alert("Vui lòng điền đầy đủ thông tin đánh giá!");
      return;
    }

    alert(
      `Cảm ơn ${data.name}! Đánh giá của bạn đã được gửi và đang chờ duyệt.`
    );
    reviewForm.reset();
    reviewFormContainer.style.display = "none";
  });
});
