// AOS Animation Implementation
function initAOS() {
  // Intersection Observer for animations
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const element = entry.target;
        const delay = element.getAttribute("data-aos-delay") || 0;

        setTimeout(() => {
          element.classList.add("aos-animate");
        }, delay);
      }
    });
  }, observerOptions);

  // Observe all elements with data-aos attribute
  document.querySelectorAll("[data-aos]").forEach((el) => {
    observer.observe(el);
  });
}

// Form Handling
function initContactForm() {
  const form = document.getElementById("contactForm");
  const submitBtn = form.querySelector(".submit-btn");

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Get form data
    const formData = {
      name: document.getElementById("name").value,
      phone: document.getElementById("phone").value,
      service: document.getElementById("service").value,
      message: document.getElementById("message").value,
    };

    // Validate form
    if (!validateForm(formData)) {
      return;
    }

    // Show loading state
    showLoadingState(submitBtn);

    // Simulate form submission
    try {
      await simulateFormSubmission(formData);
      showSuccessMessage();
      form.reset();
    } catch (error) {
      showErrorMessage();
    } finally {
      hideLoadingState(submitBtn);
    }
  });
}

function validateForm(data) {
  const errors = [];

  if (!data.name.trim()) {
    errors.push("Vui lòng nhập họ tên");
  }

  if (!data.phone.trim()) {
    errors.push("Vui lòng nhập số điện thoại");
  } else if (!/^[0-9]{10,11}$/.test(data.phone.replace(/\s/g, ""))) {
    errors.push("Số điện thoại không hợp lệ");
  }

  if (!data.service) {
    errors.push("Vui lòng chọn dịch vụ");
  }

  if (errors.length > 0) {
    showValidationErrors(errors);
    return false;
  }

  return true;
}

function showValidationErrors(errors) {
  const errorHtml = errors.map((error) => `<p>• ${error}</p>`).join("");
  showNotification("Lỗi nhập liệu", errorHtml, "error");
}

function showLoadingState(btn) {
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
}

function hideLoadingState(btn) {
  btn.disabled = false;
  btn.innerHTML = '<span>Gửi Yêu Cầu</span><i class="fas fa-paper-plane"></i>';
}

function simulateFormSubmission(data) {
  return new Promise((resolve) => {
    setTimeout(() => {
      console.log("Form submitted:", data);
      resolve();
    }, 2000);
  });
}

function showSuccessMessage() {
  showNotification(
    "Gửi thành công!",
    "Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi trong thời gian sớm nhất.",
    "success"
  );
}

function showErrorMessage() {
  showNotification(
    "Có lỗi xảy ra",
    "Vui lòng thử lại sau hoặc liên hệ trực tiếp qua hotline.",
    "error"
  );
}

// Notification System
function showNotification(title, message, type = "info") {
  const notification = document.createElement("div");
  notification.className = `notification notification-${type}`;
  notification.innerHTML = `
        <div class="notification-content">
            <h4>${title}</h4>
            <div>${message}</div>
        </div>
        <button class="notification-close">&times;</button>
    `;

  document.body.appendChild(notification);

  // Show notification
  setTimeout(() => notification.classList.add("show"), 100);

  // Auto hide after 5 seconds
  setTimeout(() => hideNotification(notification), 5000);

  // Manual close
  notification
    .querySelector(".notification-close")
    .addEventListener("click", () => {
      hideNotification(notification);
    });
}

function hideNotification(notification) {
  notification.classList.remove("show");
  setTimeout(() => notification.remove(), 300);
}

// Service Card Interactions
function initServiceCards() {
  const serviceCards = document.querySelectorAll(".service-card");

  serviceCards.forEach((card) => {
    card.addEventListener("mouseenter", () => {
      card.style.transform = "translateY(-10px) scale(1.02)";
    });

    card.addEventListener("mouseleave", () => {
      card.style.transform = "translateY(0) scale(1)";
    });
  });
}

// Tour Card Interactions
function initTourCards() {
  const tourCards = document.querySelectorAll(".tour-card");

  tourCards.forEach((card) => {
    card.addEventListener("click", () => {
      const tourTitle = card.querySelector("h3").textContent;
      showTourDetails(tourTitle);
    });

    // Add hover effect for tour images
    const img = card.querySelector(".tour-image img");
    card.addEventListener("mouseenter", () => {
      img.style.transform = "scale(1.1)";
    });

    card.addEventListener("mouseleave", () => {
      img.style.transform = "scale(1)";
    });
  });
}
// Smooth scrolling for anchor links
function initSmoothScrolling() {
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  });
}

// Form label animations
function initFormLabels() {
  const formInputs = document.querySelectorAll(
    ".form-group input, .form-group select, .form-group textarea"
  );

  formInputs.forEach((input) => {
    // Check if input has value on page load
    if (input.value) {
      input.classList.add("has-value");
    }

    input.addEventListener("focus", () => {
      input.parentElement.classList.add("focused");
    });

    input.addEventListener("blur", () => {
      input.parentElement.classList.remove("focused");
      if (input.value) {
        input.classList.add("has-value");
      } else {
        input.classList.remove("has-value");
      }
    });

    input.addEventListener("input", () => {
      if (input.value) {
        input.classList.add("has-value");
      } else {
        input.classList.remove("has-value");
      }
    });
  });
}

// Phone number formatting
function initPhoneFormatter() {
  const phoneInput = document.getElementById("phone");

  phoneInput.addEventListener("input", (e) => {
    let value = e.target.value.replace(/\D/g, "");
    if (value.length > 11) {
      value = value.slice(0, 11);
    }

    // Format: 0123 456 789
    if (value.length > 6) {
      value = value.replace(/(\d{4})(\d{3})(\d+)/, "$1 $2 $3");
    } else if (value.length > 3) {
      value = value.replace(/(\d{4})(\d+)/, "$1 $2");
    }

    e.target.value = value;
  });
}

// Contact methods click handlers
function initContactMethods() {
  const phoneMethod = document.querySelector(".contact-method:has(i.fa-phone)");
  const emailMethod = document.querySelector(
    ".contact-method:has(i.fa-envelope)"
  );

  if (phoneMethod) {
    phoneMethod.addEventListener("click", () => {
      window.location.href = "tel:0123456789";
    });
    phoneMethod.style.cursor = "pointer";
  }

  if (emailMethod) {
    emailMethod.addEventListener("click", () => {
      window.location.href = "mailto:info@libertylc.com";
    });
    emailMethod.style.cursor = "pointer";
  }
}

// Parallax effect for hero section
function initParallaxEffect() {
  const hero = document.querySelector(".hero");

  window.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset;
    const rate = scrolled * -0.5;

    if (hero) {
      hero.style.transform = `translateY(${rate}px)`;
    }
  });
}

// Add notification styles dynamically
// function addNotificationStyles() {
//   const style = document.createElement("style");
//   style.textContent = `
//         .notification {
//             position: fixed;
//             top: 20px;
//             right: 20px;
//             background: var(--white);
//             border-radius: 10px;
//             box-shadow: var(--shadow-hover);
//             padding: 1.5rem;
//             max-width: 400px;
//             z-index: 1000;
//             transform: translateX(100%);
//             opacity: 0;
//             transition: all 0.3s ease;
//         }

//         .notification.show {
//             transform: translateX(0);
//             opacity: 1;
//         }

//         .notification-success {
//             border-left: 4px solid #28a745;
//         }

//         .notification-error {
//             border-left: 4px solid #dc3545;
//         }

//         .notification-info {
//             border-left: 4px solid var(--primary-color);
//         }

//         .notification-content h4 {
//             margin: 0 0 0.5rem 0;
//             color: var(--primary-color);
//             font-weight: 600;
//         }

//         .notification-content p {
//             margin: 0.5rem 0;
//             color: #666;
//             font-size: 0.9rem;
//         }

//         .notification-close {
//             position: absolute;
//             top: 10px;
//             right: 15px;
//             background: none;
//             border: none;
//             font-size: 1.5rem;
//             cursor: pointer;
//             color: #999;
//             line-height: 1;
//         }

//         .notification-close:hover {
//             color: #333;
//         }

//         .highlight-tag {
//             display: inline-block;
//             background: rgba(44, 85, 48, 0.1);
//             color: var(--primary-color);
//             padding: 0.2rem 0.5rem;
//             border-radius: 10px;
//             font-size: 0.7rem;
//             margin: 0.2rem;
//             font-weight: 500;
//         }

//         @media (max-width: 480px) {
//             .notification {
//                 right: 10px;
//                 left: 10px;
//                 max-width: none;
//             }
//         }
//     `;
//   document.head.appendChild(style);
// }

// Initialize everything when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  console.log("Liberty Travel Service Website Loaded");

  // Initialize all features
  initAOS();
  initContactForm();
  initServiceCards();
  initTourCards();
  initSmoothScrolling();
  initFormLabels();
  initPhoneFormatter();
  initContactMethods();
  initParallaxEffect();
});

// Handle page visibility changes
document.addEventListener("visibilitychange", () => {
  if (document.visibilityState === "visible") {
    console.log("Page is now visible");
  }
});

// Handle window resize
window.addEventListener("resize", () => {
  // Recalculate any responsive elements if needed
  console.log("Window resized");
});

// Export functions for potential external use
window.LibertyTravel = {
  showNotification,
  showTourDetails,
};
