document.addEventListener("DOMContentLoaded", function () {
  // Use language ID passed from PHP
  const currentLang = window.currentLang || 1; // Fallback to 1 (Vietnamese) if not set
  const langCode = currentLang === 1 ? "vi" : "en";

  // Load content when page loads
  loadDieuKhoan();

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

  // Function to load điều khoản data
  function loadDieuKhoan() {
    const formData = new FormData();
    formData.append("action", "get_dieukhoan");
    formData.append("lang", langCode);

    fetch(window.location.href, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          renderDieuKhoan(data.data);
        } else {
          console.error("Error loading điều khoản:", data.error);
          showErrorMessage(
            currentLang === 1
              ? "Không thể tải nội dung điều khoản"
              : "Unable to load terms content"
          );
        }
      })
      .catch((error) => {
        console.error("AJAX Error:", error);
        showErrorMessage(
          currentLang === 1
            ? "Lỗi kết nối khi tải nội dung"
            : "Connection error while loading content"
        );
      });
  }

  // Function to render điều khoản content
  function renderDieuKhoan(dieukhoanData) {
    const contentWrapper = document.getElementById("content-wrapper");
    const loading = document.getElementById("loading");

    // Remove loading indicator
    if (loading) {
      loading.remove();
    }

    // Group content by title (main sections)
    const groupedData = {};
    dieukhoanData.forEach((item) => {
      if (!groupedData[item.title]) {
        groupedData[item.title] = [];
      }
      groupedData[item.title].push(item);
    });

    // Generate HTML for each section
    Object.keys(groupedData).forEach((sectionTitle) => {
      const sectionItems = groupedData[sectionTitle];

      const sectionHTML = `
        <section class="policy-section">
          <h2 class="section-title">
            <i class="icon-${getSectionIcon(sectionTitle)}"></i>
            ${sectionTitle}
          </h2>
          <div class="policy-content">
            ${sectionItems
              .map(
                (item) => `
              <div class="policy-item">
                <h3>${item.small_title}</h3>
                ${formatContent(item.content)}
              </div>
            `
              )
              .join("")}
          </div>
        </section>
      `;

      contentWrapper.insertAdjacentHTML("beforeend", sectionHTML);
    });

    // Apply animations to newly added sections
    applyAnimations();
    setupInteractions();
  }

  // Helper function to get section icon based on title
  function getSectionIcon(title) {
    const iconMap = {
      "Chính sách phòng nghỉ": "bed",
      "Chính sách thanh toán": "payment",
      "Quy định chung": "rules",
      "Dịch vụ bổ sung": "services",
      "Room Policy": "bed",
      "Payment Policy": "payment",
      "General Rules": "rules",
      "Additional Services": "services",
    };

    return iconMap[title] || "info";
  }

  // Helper function to format content
  function formatContent(content) {
    // If content contains HTML tags, return as is
    if (content.includes("<") && content.includes(">")) {
      return content;
    }

    // Otherwise, convert plain text to list format
    const lines = content.split("\n").filter((line) => line.trim());
    if (lines.length > 1) {
      return `<ul>${lines
        .map((line) => `<li>${line.trim()}</li>`)
        .join("")}</ul>`;
    }

    return `<p>${content}</p>`;
  }

  // Function to apply animations to sections
  function applyAnimations() {
    const sections = document.querySelectorAll(
      ".policy-section, .contact-section"
    );
    sections.forEach((section) => {
      if (!section.classList.contains("fade-in")) {
        section.classList.add("fade-in");
        observer.observe(section);
      }
    });
  }

  // Function to setup interactions
  function setupInteractions() {
    // Add click effect to policy items
    const policyItems = document.querySelectorAll(".policy-item");
    policyItems.forEach((item) => {
      if (!item.hasClickListener) {
        item.addEventListener("click", function () {
          this.classList.add("clicked");
          setTimeout(() => {
            this.classList.remove("clicked");
          }, 150);
        });
        item.hasClickListener = true;
      }
    });
  }

  // Function to show error message
  function showErrorMessage(message) {
    const contentWrapper = document.getElementById("content-wrapper");
    const loading = document.getElementById("loading");

    if (loading) {
      loading.innerHTML = `<p style="color: red;">${message}</p>`;
    } else {
      contentWrapper.insertAdjacentHTML(
        "afterbegin",
        `<div class="error-message" style="color: red; text-align: center; padding: 20px;">
          ${message}
         </div>`
      );
    }
  }

  // Setup contact items interaction
  const contactItems = document.querySelectorAll(".contact-item");
  contactItems.forEach((item) => {
    item.addEventListener("click", function () {
      const textToCopy = this.textContent.split(":")[1]?.trim();
      if (textToCopy) {
        navigator.clipboard.writeText(textToCopy).then(() => {
          showNotification(
            currentLang === 1
              ? `Đã sao chép: ${textToCopy}`
              : `Copied: ${textToCopy}`
          );
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
