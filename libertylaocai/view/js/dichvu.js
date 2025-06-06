document.addEventListener("DOMContentLoaded", () => {
  console.log("Liberty Travel Service Website Loaded");

  // Form Handling
  function initContactForm() {
    const form = document.getElementById("contactForm");
    if (!form) {
      console.error("Contact form not found!");
      return;
    }

    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      // Get form data
      const formData = {
        name: document.getElementById("name").value.trim(),
        phone: document.getElementById("phone").value.trim(),
        email: document.getElementById("email").value.trim(),
        service: document.getElementById("service").value.trim(),
        message: document.getElementById("message").value.trim(),
      };

      // Validate form
      const errors = [];
      if (!formData.name) errors.push("Please enter your full name");
      if (!formData.phone) {
        errors.push("Please enter your phone number");
      } else if (!/^(0[0-9]{9,10})$/.test(formData.phone.replace(/\s/g, ""))) {
        errors.push("Invalid phone number (must start with 0, 10-11 digits)");
      }
      if (!formData.email) {
        errors.push("Please enter your email");
      } else if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(formData.email)) {
        errors.push("Invalid email address");
      }
      if (!formData.service) errors.push("Please select a service");
      if (!formData.message) errors.push("Please enter your message");

      if (errors.length > 0) {
        showNotification("Validation Error", errors.join("<br>"), "error");
        return;
      }

      // Show loading state
      const submitBtn = form.querySelector(".submit-btn");
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

      try {
        const response = await fetch("/libertylaocai/view/php/dichvu.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(formData),
        });

        const result = await response.json();

        if (result.success) {
          showNotification("Success", result.message, "success");
          form.reset();
          form.querySelectorAll("input, select, textarea").forEach((input) => {
            input.classList.remove("has-value");
          });
        } else {
          showNotification("Error", result.message, "error");
        }
      } catch (error) {
        console.error("Form submission error:", error);
        showNotification(
          "Error",
          "An error occurred. Please try again later.",
          "error"
        );
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML =
          '<span>Send Request</span><i class="fas fa-paper-plane"></i>';
      }
    });
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
    setTimeout(() => notification.classList.add("show"), 100);
    setTimeout(() => hideNotification(notification), 5000);

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

  // Form label animations
  function initFormLabels() {
    const formInputs = document.querySelectorAll(
      ".form-group input, .form-group select, .form-group textarea"
    );

    formInputs.forEach((input) => {
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
    if (phoneInput) {
      phoneInput.addEventListener("input", (e) => {
        let value = e.target.value.replace(/\D/g, "");
        if (value.length > 11) {
          value = value.slice(0, 11);
        }
        if (value.length > 6) {
          value = value.replace(/(\d{4})(\d{3})(\d+)/, "$1 $2 $3");
        } else if (value.length > 3) {
          value = value.replace(/(\d{4})(\d+)/, "$1 $2");
        }
        e.target.value = value;
      });
    }
  }

  // Initialize everything
  initContactForm();
  initFormLabels();
  initPhoneFormatter();

  // Keep existing functions for other features
  function initAOS() {
    const observerOptions = {
      threshold: 0.1,
      rootMargin: "0px 0px -50px 0px",
    };
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const element = entry.target;
          const delay = element.getAttribute("data-aos-delay") || 0;
          setTimeout(() => element.classList.add("aos-animate"), delay);
        }
      });
    }, observerOptions);
    document.querySelectorAll("[data-aos]").forEach((el) => {
      observer.observe(el);
    });
  }

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

  function initTourCards() {
    const tourCards = document.querySelectorAll(".tour-card");
    tourCards.forEach((card) => {
      card.addEventListener("click", () => {
        const tourTitle = card.querySelector("h3").textContent;
        showNotification(
          "Tour Selected",
          `You clicked on ${tourTitle}`,
          "info"
        );
      });
      const img = card.querySelector(".tour-image img");
      card.addEventListener("mouseenter", () => {
        img.style.transform = "scale(1.1)";
      });
      card.addEventListener("mouseleave", () => {
        img.style.transform = "scale(1)";
      });
    });
  }

  function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener("click", function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute("href"));
        if (target) {
          target.scrollIntoView({ behavior: "smooth", block: "start" });
        }
      });
    });
  }

  function initContactMethods() {
    const phoneMethod = document.querySelector(
      ".contact-method:has(i.fa-phone)"
    );
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

  function initServiceLinks() {
    const serviceLinks = document.querySelectorAll(".text-content [data-href]");
    serviceLinks.forEach((link) => {
      link.addEventListener("click", () => {
        window.location.href = link.getAttribute("data-href");
      });
    });
  }

  initAOS();
  initServiceCards();
  initTourCards();
  initSmoothScrolling();
  initContactMethods();
  initParallaxEffect();
  initServiceLinks();
});

window.LibertyTravel = {
  showNotification,
};
