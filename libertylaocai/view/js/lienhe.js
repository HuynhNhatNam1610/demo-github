// contact.js
document.addEventListener("DOMContentLoaded", function () {
  const contactForm = document.getElementById("contactForm");
  const formInputs = contactForm.querySelectorAll("input, textarea");

  // Form validation
  function validateForm() {
    let isValid = true;
    const requiredFields = contactForm.querySelectorAll("[required]");

    requiredFields.forEach((field) => {
      if (!field.value.trim()) {
        showFieldError(field, "Vui lòng điền thông tin này");
        isValid = false;
      } else {
        clearFieldError(field);
      }
    });

    // Validate email format
    const emailField = document.getElementById("email");
    if (emailField.value.trim() && !isValidEmail(emailField.value)) {
      showFieldError(emailField, "Vui lòng nhập email hợp lệ");
      isValid = false;
    }

    return isValid;
  }

  // Email validation
  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  // Show field error
  function showFieldError(field, message) {
    clearFieldError(field);

    field.style.borderColor = "#e74c3c";

    const errorDiv = document.createElement("div");
    errorDiv.className = "field-error";
    errorDiv.textContent = message;
    errorDiv.style.cssText = `
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 5px;
            animation: slideDown 0.3s ease;
        `;

    field.parentNode.appendChild(errorDiv);
  }

  // Clear field error
  function clearFieldError(field) {
    field.style.borderColor = "#e0e0e0";
    const existingError = field.parentNode.querySelector(".field-error");
    if (existingError) {
      existingError.remove();
    }
  }

  // Show success message
  function showSuccessMessage() {
    // Remove existing success message
    const existingMessage = document.querySelector(".success-message");
    if (existingMessage) {
      existingMessage.remove();
    }

    const successDiv = document.createElement("div");
    successDiv.className = "success-message show";
    successDiv.innerHTML = `
            <strong>Thành công!</strong> Cảm ơn bạn đã liên hệ với chúng tôi. 
            Chúng tôi sẽ phản hồi trong thời gian sớm nhất.
        `;

    contactForm.insertBefore(successDiv, contactForm.firstChild);

    // Auto remove after 5 seconds
    setTimeout(() => {
      if (successDiv.parentNode) {
        successDiv.remove();
      }
    }, 5000);
  }

  // Handle form submission
  contactForm.addEventListener("submit", function (e) {
    e.preventDefault();

    if (validateForm()) {
      // Simulate form submission
      const submitBtn = contactForm.querySelector(".submit-btn");
      const originalText = submitBtn.textContent;

      // Show loading state
      submitBtn.textContent = "ĐANG GỬI...";
      submitBtn.disabled = true;
      submitBtn.style.opacity = "0.7";

      // Simulate API call delay
      setTimeout(() => {
        showSuccessMessage();
        contactForm.reset();

        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        submitBtn.style.opacity = "1";

        // Clear any existing errors
        formInputs.forEach((input) => clearFieldError(input));
      }, 1500);
    }
  });

  // Real-time validation on input
  formInputs.forEach((input) => {
    input.addEventListener("blur", function () {
      if (this.hasAttribute("required") && !this.value.trim()) {
        showFieldError(this, "Vui lòng điền thông tin này");
      } else if (
        this.type === "email" &&
        this.value.trim() &&
        !isValidEmail(this.value)
      ) {
        showFieldError(this, "Vui lòng nhập email hợp lệ");
      } else {
        clearFieldError(this);
      }
    });

    input.addEventListener("input", function () {
      if (this.parentNode.querySelector(".field-error")) {
        clearFieldError(this);
      }
    });
  });

  // Smooth scroll animation for form focus
  formInputs.forEach((input) => {
    input.addEventListener("focus", function () {
      this.style.transform = "scale(1.02)";
      setTimeout(() => {
        this.style.transform = "scale(1)";
      }, 200);
    });
  });

  // Add floating label effect
  formInputs.forEach((input) => {
    const placeholder = input.getAttribute("placeholder");
    if (placeholder) {
      input.addEventListener("focus", function () {
        if (!this.value) {
          this.setAttribute("data-placeholder", placeholder);
          this.setAttribute("placeholder", "");
        }
      });

      input.addEventListener("blur", function () {
        if (!this.value && this.getAttribute("data-placeholder")) {
          this.setAttribute(
            "placeholder",
            this.getAttribute("data-placeholder")
          );
          this.removeAttribute("data-placeholder");
        }
      });
    }
  });
});
function validatePhone(phone) {
  const phoneRegex = /^[0-9]{10,11}$/;
  return phoneRegex.test(phone);
}

const phoneField = document.getElementById("phone");
if (phoneField.value.trim() && !validatePhone(phoneField.value)) {
  showFieldError(phoneField, "Vui lòng nhập số điện thoại hợp lệ (10-11 số)");
  isValid = false;
}

if (input.id === "phone" && input.value.trim() && !validatePhone(input.value)) {
  showFieldError(input, "Vui lòng nhập số điện thoại hợp lệ (10-11 số)");
}
