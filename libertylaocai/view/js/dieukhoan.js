// script.js

document.addEventListener("DOMContentLoaded", function () {
  // Smooth scrolling animation for sections
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
      }
    });
  }, observerOptions);

  // Apply animation to all sections
  const sections = document.querySelectorAll(
    ".policy-section, .contact-section, .legal-section"
  );
  sections.forEach((section) => {
    section.classList.add("fade-in");
    observer.observe(section);
  });

  // Add click effect to policy items
  const policyItems = document.querySelectorAll(".policy-item");
  policyItems.forEach((item) => {
    item.addEventListener("click", function () {
      this.classList.add("clicked");
      setTimeout(() => {
        this.classList.remove("clicked");
      }, 150);
    });
  });

  // Add hover effect to room cards
  const roomCards = document.querySelectorAll(".room-card");
  roomCards.forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.classList.add("hovered");
    });

    card.addEventListener("mouseleave", function () {
      this.classList.remove("hovered");
    });
  });

  const contactItems = document.querySelectorAll(".contact-item");
  contactItems.forEach((item) => {
    item.addEventListener("click", function () {
      const textToCopy = this.textContent.split(":")[1]?.trim();
      if (textToCopy) {
        navigator.clipboard.writeText(textToCopy).then(() => {
          showNotification("Đã sao chép: " + textToCopy);
        });
      }
    });

    // Add hover effect
    item.classList.add("clickable");
  });

  function showNotification(message) {
    const notification = document.createElement("div");
    notification.textContent = message;
    notification.className = "notification";

    document.body.appendChild(notification);

    setTimeout(() => {
      notification.classList.add("show");
    }, 100);

    setTimeout(() => {
      notification.classList.add("hide");
      setTimeout(() => {
        notification.remove();
      }, 300);
    }, 3000);
  }
});
