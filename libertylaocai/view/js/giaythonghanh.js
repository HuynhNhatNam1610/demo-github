document.addEventListener("DOMContentLoaded", function () {
  initializeAnimations();
  initializeForm();
  initializeScrollEffects();
  loadDynamicContent();
});

// Load dynamic content from database
function loadDynamicContent() {
  // Lấy giá dịch vụ từ PHP global variable hoặc data attribute
  const servicePriceElement = document.getElementById("service-price");
  if (servicePriceElement) {
    const servicePrice = parseInt(servicePriceElement.value) || 320000;
    updateTotalCost(1, servicePrice);
  }

  // Cập nhật các phần tử động khác nếu cần
  updateDynamicElements();
}

// Update dynamic elements based on database content
function updateDynamicElements() {
  // Cập nhật greeting text nếu có
  const greetingElements = document.querySelectorAll(".hero-subtitle");
  greetingElements.forEach((element) => {
    if (element.dataset.dbContent) {
      element.textContent = element.dataset.dbContent;
    }
  });

  // Cập nhật description title và content
  const descriptionTitleElements = document.querySelectorAll("[data-db-title]");
  descriptionTitleElements.forEach((element) => {
    if (element.dataset.dbTitle) {
      element.textContent = element.dataset.dbTitle;
    }
  });

  const descriptionContentElements =
    document.querySelectorAll("[data-db-content]");
  descriptionContentElements.forEach((element) => {
    if (element.dataset.dbContent) {
      element.textContent = element.dataset.dbContent;
    }
  });

  // Cập nhật benefit cards nếu có dữ liệu động
  const benefitCards = document.querySelectorAll(".benefit-card");
  benefitCards.forEach((card) => {
    const titleElement = card.querySelector("h3");
    const contentElement = card.querySelector("p");
    const iconElement = card.querySelector(".benefit-icon");

    if (titleElement && titleElement.dataset.dbTitle) {
      titleElement.textContent = titleElement.dataset.dbTitle;
    }
    if (contentElement && contentElement.dataset.dbContent) {
      contentElement.textContent = contentElement.dataset.dbContent;
    }
    if (iconElement && iconElement.dataset.dbIcon) {
      iconElement.className = `benefit-icon ${iconElement.dataset.dbIcon}`;
    }
  });
}

// Initialize scroll animations
function initializeAnimations() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("fade-in-up");
      }
    });
  }, observerOptions);

  // Observe elements for animation
  const animatedElements = document.querySelectorAll(
    ".benefit-card, .service-card, .step, .service-item, .contact-form, .contact-info"
  );

  animatedElements.forEach((el) => observer.observe(el));
}

// Form handling
function initializeForm() {
  const form = document.getElementById("visaForm");
  if (!form) return;

  const submitBtn = form.querySelector(".submit-btn");
  const peopleCountInput = document.getElementById("people-count");

  // Xử lý sự kiện submit
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    handleFormSubmission(form, submitBtn);
  });

  // Xử lý sự kiện input cho trường people-count
  if (peopleCountInput) {
    peopleCountInput.addEventListener("input", function () {
      let value = parseInt(peopleCountInput.value) || 1;
      value = Math.max(1, Math.min(10, value)); // Giới hạn giá trị từ 1 đến 10
      peopleCountInput.value = value;
      updateTotalCost(value);
      updateCountButtonStates(value);
    });
  }

  // Xử lý validation và các sự kiện khác
  const inputs = form.querySelectorAll("input[required]");
  inputs.forEach((input) => {
    input.addEventListener("blur", validateInput);
    input.addEventListener("input", clearErrors);
  });

  const phoneInput = document.getElementById("phone");
  if (phoneInput) {
    phoneInput.addEventListener("input", formatPhoneNumber);
  }

  // Khởi tạo giá trị ban đầu
  if (peopleCountInput) {
    const initialCount = parseInt(peopleCountInput.value) || 1;
    const servicePrice = getServicePrice();
    updateTotalCost(initialCount, servicePrice);
    updateCountButtonStates(initialCount);
  }
}

// Get service price from hidden input or default
function getServicePrice() {
  const servicePriceElement = document.getElementById("service-price");
  return servicePriceElement ? parseInt(servicePriceElement.value) : 320000;
}

// Form submission handler
function handleFormSubmission(form, submitBtn) {
  const formData = new FormData(form);
  const data = Object.fromEntries(formData);

  if (!validateForm(form)) {
    return;
  }

  showLoadingState(submitBtn);

  let registerServiceInput = form.querySelector(
    'input[name="register_service"]'
  );
  if (!registerServiceInput) {
    registerServiceInput = document.createElement("input");
    registerServiceInput.type = "hidden";
    registerServiceInput.name = "register_service";
    registerServiceInput.value = "1";
    form.appendChild(registerServiceInput);
  }

  const xhr = new XMLHttpRequest();
  xhr.open("POST", window.location.href, true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      console.log("HTTP Status:", xhr.status);
      console.log("Response:", xhr.responseText);
      hideLoadingState(submitBtn);
      try {
        if (xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          const languageId = document.documentElement.lang === "vi" ? 1 : 2;

          if (response.success) {
            console.log("Success response received");
            showSuccessMessage();
            resetForm(form);
          } else {
            console.log("Error response:", response.message);
            showErrorMessage(
              response.message ||
                (languageId === 1
                  ? "Có lỗi xảy ra khi đăng ký dịch vụ. Vui lòng thử lại."
                  : "An error occurred during service registration. Please try again.")
            );
          }
        } else {
          console.log("HTTP Error:", xhr.status);
          const languageId = document.documentElement.lang === "vi" ? 1 : 2;
          showErrorMessage(
            languageId === 1
              ? "Lỗi máy chủ. Vui lòng thử lại."
              : "Server error. Please try again."
          );
        }
      } catch (e) {
        console.error("JSON Parse Error:", e, "Response:", xhr.responseText);
        const languageId = document.documentElement.lang === "vi" ? 1 : 2;
        showErrorMessage(
          languageId === 1
            ? "Có lỗi xảy ra khi gửi yêu cầu. Vui lòng thử lại."
            : "An error occurred while sending the request. Please try again."
        );
      }
    }
  };
  xhr.send(formData);
}
// Form validation
function validateForm(form) {
  let isValid = true;
  const requiredInputs = form.querySelectorAll("input[required]");

  requiredInputs.forEach((input) => {
    if (!validateInput({ target: input })) {
      isValid = false;
    }
  });

  return isValid;
}

// Individual input validation với validation rules phù hợp cho Việt Nam
function validateInput(e) {
  const input = e.target;
  const value = input.value.trim();
  const inputGroup = input.closest(".form-group");

  // Remove existing error messages
  clearErrors({ target: input });

  // Validation rules
  let isValid = true;
  let errorMessage = "";

  if (input.hasAttribute("required") && !value) {
    isValid = false;
    errorMessage = "Trường này là bắt buộc";
  } else if (input.type === "tel" && value) {
    // Improved Vietnamese phone number validation
    const phoneRegex = /^(\+84|84|0)[3|5|7|8|9][0-9]{8}$/;
    const cleanPhone = value.replace(/[\s\-\(\)]/g, "");
    if (!phoneRegex.test(cleanPhone)) {
      isValid = false;
      errorMessage = "Số điện thoại không hợp lệ (VD: 0912345678)";
    }
  } else if (input.type === "email" && value) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
      isValid = false;
      errorMessage = "Email không hợp lệ";
    }
  } else if (input.type === "number" && value) {
    const numValue = parseInt(value);
    const min = parseInt(input.getAttribute("min")) || 1;
    const max = parseInt(input.getAttribute("max")) || 10;
    if (numValue < min || numValue > max) {
      isValid = false;
      errorMessage = `Giá trị phải từ ${min} đến ${max}`;
    }
  }

  if (!isValid) {
    showInputError(inputGroup, errorMessage);
    input.classList.add("error");
  } else {
    input.classList.remove("error");
    input.classList.add("valid");
  }

  return isValid;
}

// Clear input errors
function clearErrors(e) {
  const input = e.target;
  const inputGroup = input.closest(".form-group");
  const errorElement = inputGroup.querySelector(".error-message");

  if (errorElement) {
    errorElement.remove();
  }
  input.classList.remove("error");
}

// Show input error
function showInputError(inputGroup, message) {
  const existingError = inputGroup.querySelector(".error-message");
  if (existingError) {
    existingError.textContent = message;
    return;
  }

  const errorElement = document.createElement("div");
  errorElement.className = "error-message";
  errorElement.textContent = message;
  errorElement.style.color = "#dc3545";
  errorElement.style.fontSize = "0.875rem";
  errorElement.style.marginTop = "5px";

  inputGroup.appendChild(errorElement);
}

// Improved Vietnamese phone number formatting
function formatPhoneNumber(e) {
  let value = e.target.value.replace(/\D/g, "");

  if (value.length >= 10) {
    if (value.startsWith("84")) {
      // Format +84 numbers
      value =
        "+84 " +
        value.slice(2, 5) +
        " " +
        value.slice(5, 8) +
        " " +
        value.slice(8, 11);
    } else if (value.startsWith("0")) {
      // Format Vietnamese mobile numbers starting with 0
      value =
        value.slice(0, 4) + " " + value.slice(4, 7) + " " + value.slice(7, 10);
    }
  } else if (value.length >= 7) {
    // Partial formatting for shorter numbers
    if (value.startsWith("0")) {
      value = value.slice(0, 4) + " " + value.slice(4);
    }
  }

  e.target.value = value;
}

// Update people count with dynamic pricing
function updatePeopleCount(change) {
  const peopleInput = document.getElementById("people-count");
  const totalCostElement = document.getElementById("total-cost");
  const totalCostHidden = document.getElementById("total-cost-hidden");
  const servicePrice =
    parseFloat(document.getElementById("service-price").value) || 320000;

  let peopleCount = parseInt(peopleInput.value) || 1;
  peopleCount = Math.max(1, Math.min(10, peopleCount + change));
  peopleInput.value = peopleCount;

  // Cập nhật trạng thái nút
  updateCountButtonStates(peopleCount);

  // Cập nhật tổng chi phí
  if (servicePrice > 0) {
    const totalCost = servicePrice * peopleCount;
    const languageId = document.documentElement.lang === "vi" ? 1 : 2;
    totalCostElement.textContent =
      totalCost.toLocaleString("vi-VN") + (languageId === 1 ? " VNĐ" : "");
    totalCostHidden.value = totalCost;
  } else {
    totalCostElement.textContent =
      document.getElementById("service-price").dataset.originalValue ||
      "Liên hệ";
    totalCostHidden.value = "0";
  }
}

function updateCountButtonStates(count) {
  const minusBtn = document.querySelector(".minus-btn");
  const plusBtn = document.querySelector(".plus-btn");

  if (minusBtn) {
    minusBtn.disabled = count <= 1;
    minusBtn.classList.toggle("disabled", count <= 1);
  }

  if (plusBtn) {
    plusBtn.disabled = count >= 10;
    plusBtn.classList.toggle("disabled", count >= 10);
  }
}

// Update total cost with formatting
function updateTotalCost(peopleCount, pricePerPerson = null) {
  if (!pricePerPerson) {
    pricePerPerson = getServicePrice();
  }

  const totalCost = peopleCount * pricePerPerson;
  const formattedCost = totalCost.toLocaleString("vi-VN") + " VNĐ";

  const totalCostElement = document.getElementById("total-cost");
  const hiddenTotalCostElement = document.getElementById("total-cost-hidden");

  if (totalCostElement) {
    totalCostElement.textContent = formattedCost;
  }

  if (hiddenTotalCostElement) {
    hiddenTotalCostElement.value = totalCost;
  }
}

// Loading state
function showLoadingState(button) {
  const originalText = button.innerHTML;
  button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
  button.disabled = true;
  button.style.opacity = "0.7";
  button.dataset.originalText = originalText;
}

function hideLoadingState(button) {
  button.innerHTML = button.dataset.originalText;
  button.disabled = false;
  button.style.opacity = "1";
  delete button.dataset.originalText;
}

// Enhanced success message
function showSuccessMessage() {
  console.log("Showing success modal");
  const modal = document.createElement("div");
  modal.className = "success-modal";
  modal.innerHTML = `
    <div class="modal-overlay">
      <div class="modal-content">
        <div class="success-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <h3>Đăng Ký Thành Công!</h3>
        <p>Chúng tôi đã nhận được thông tin của bạn và sẽ liên hệ sớm nhất có thể.</p>
        <div class="modal-actions">
          <button class="close-modal-btn">Đóng</button>
          <button class="continue-btn" onclick="window.location.reload()">Đăng ký thêm</button>
        </div>
      </div>
    </div>
  `;
  document.body.appendChild(modal);
  console.log("Modal appended to body");

  const closeBtn = modal.querySelector(".close-modal-btn");
  const overlay = modal.querySelector(".modal-overlay");

  function closeModal() {
    modal.style.animation = "fadeOut 0.3s ease-out forwards";
    setTimeout(() => {
      if (document.body.contains(modal)) {
        document.body.removeChild(modal);
      }
    }, 300);
  }

  closeBtn.addEventListener("click", closeModal);
  overlay.addEventListener("click", (e) => {
    if (e.target === overlay) closeModal();
  });

  setTimeout(closeModal, 8000);
}
// Show error message
function showErrorMessage(message) {
  const errorModal = document.createElement("div");
  errorModal.className = "error-modal";
  errorModal.innerHTML = `
    <div class="modal-overlay">
      <div class="modal-content error">
        <div class="error-icon">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>Có Lỗi Xảy Ra!</h3>
        <p>${message}</p>
        <button class="close-modal-btn">Đóng</button>
      </div>
    </div>
  `;

  document.body.appendChild(errorModal);

  const closeBtn = errorModal.querySelector(".close-modal-btn");
  const overlay = errorModal.querySelector(".modal-overlay");

  function closeErrorModal() {
    errorModal.style.animation = "fadeOut 0.3s ease-out forwards";
    setTimeout(() => {
      if (document.body.contains(errorModal)) {
        document.body.removeChild(errorModal);
      }
    }, 300);
  }

  closeBtn.addEventListener("click", closeErrorModal);
  overlay.addEventListener("click", (e) => {
    if (e.target === overlay) closeErrorModal();
  });

  setTimeout(closeErrorModal, 5000);
}

// Reset form
function resetForm(form) {
  form.reset();
  const inputs = form.querySelectorAll("input, textarea");
  inputs.forEach((input) => {
    input.classList.remove("error", "valid");
  });

  const errorMessages = form.querySelectorAll(".error-message");
  errorMessages.forEach((error) => error.remove());

  // Reset people count và total cost
  const peopleCountInput = document.getElementById("people-count");
  if (peopleCountInput) {
    peopleCountInput.value = 1;
    const servicePrice = getServicePrice();
    updateTotalCost(1, servicePrice);
    updateCountButtonStates(1);
  }
}
// Scroll effects
function initializeScrollEffects() {
  window.addEventListener("scroll", handleScroll);

  // Smooth scrolling for anchor links
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

function handleScroll() {
  const scrolled = window.pageYOffset;
  const parallaxElements = document.querySelectorAll(".hero-section");

  parallaxElements.forEach((element) => {
    const speed = 0.5;
    element.style.transform = `translateY(${scrolled * speed}px)`;
  });

  // Add navbar scroll effect
  const navbar = document.querySelector(".navbar");
  if (navbar) {
    if (scrolled > 100) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  }
}

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

// Language switching functionality
function switchLanguage(lang) {
  const currentUrl = new URL(window.location);
  currentUrl.searchParams.set("lang", lang);
  window.location.href = currentUrl.toString();
}

// Export functions for external use
window.VisaServiceApp = {
  validateForm,
  showSuccessMessage,
  showErrorMessage,
  resetForm,
  updatePeopleCount,
  updateTotalCost,
  switchLanguage,
  getServicePrice,
};

// Initialize when page loads
document.addEventListener("DOMContentLoaded", function () {
  initializeAnimations();
  initializeForm();
  initializeScrollEffects();
  loadDynamicContent();

  const peopleCountInput = document.getElementById("people-count");
  if (peopleCountInput) {
    const initialCount = parseInt(peopleCountInput.value) || 1;
    updateTotalCost(initialCount);
    updateCountButtonStates(initialCount);
  }
});
