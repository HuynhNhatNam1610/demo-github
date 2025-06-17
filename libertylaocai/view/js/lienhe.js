// document.addEventListener("DOMContentLoaded", function () {
//   const contactForm = document.getElementById("contactForm");
//   const formInputs = contactForm.querySelectorAll("input, textarea");

//   // Detect current language from HTML lang attribute
//   const currentLang = document.documentElement.lang || "vi";

//   // Multi-language messages
//   const messages = {
//     vi: {
//       required: "Vui lòng điền thông tin này",
//       emailInvalid: "Vui lòng nhập email hợp lệ",
//       phoneInvalid: "Vui lòng nhập số điện thoại hợp lệ (10-11 số)",
//       sending: "ĐANG GỬI...",
//       success: "Thành công!",
//       successMessage:
//         "Cảm ơn bạn đã liên hệ với chúng tôi. Chúng tôi sẽ phản hồi trong thời gian sớm nhất.",
//       submitBtn: "GỬI THÔNG TIN",
//       error: "Có lỗi xảy ra, vui lòng thử lại sau.",
//     },
//     en: {
//       required: "Please fill in this information",
//       emailInvalid: "Please enter a valid email",
//       phoneInvalid: "Please enter a valid phone number (10-11 digits)",
//       sending: "SENDING...",
//       success: "Success!",
//       successMessage:
//         "Thank you for contacting us. We will respond as soon as possible.",
//       submitBtn: "SEND MESSAGE",
//       error: "An error occurred, please try again later.",
//     },
//   };

//   const msg = messages[currentLang] || messages.vi;

//   // Form validation
//   function validateForm() {
//     let isValid = true;
//     const requiredFields = contactForm.querySelectorAll("[required]");

//     requiredFields.forEach((field) => {
//       if (!field.value.trim()) {
//         showFieldError(field, msg.required);
//         isValid = false;
//       } else {
//         clearFieldError(field);
//       }
//     });

//     const emailField = document.getElementById("email");
//     if (emailField.value.trim() && !isValidEmail(emailField.value)) {
//       showFieldError(emailField, msg.emailInvalid);
//       isValid = false;
//     }

//     const phoneField = document.getElementById("phone");
//     if (phoneField.value.trim() && !validatePhone(phoneField.value)) {
//       showFieldError(phoneField, msg.phoneInvalid);
//       isValid = false;
//     }

//     return isValid;
//   }

//   function isValidEmail(email) {
//     const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
//     return emailRegex.test(email);
//   }

//   function validatePhone(phone) {
//     const phoneRegex = /^[0-9]{10,11}$/;
//     return phoneRegex.test(phone);
//   }

//   function showFieldError(field, message) {
//     clearFieldError(field);
//     field.style.borderColor = "#e74c3c";
//     const errorDiv = document.createElement("div");
//     errorDiv.className = "field-error";
//     errorDiv.textContent = message;
//     errorDiv.style.cssText = `
//             color: #e74c3c;
//             font-size: 0.85rem;
//             margin-top: 5px;
//             animation: slideDown 0.3s ease;
//         `;
//     field.parentNode.appendChild(errorDiv);
//   }

//   function clearFieldError(field) {
//     field.style.borderColor = "#e0e0e0";
//     const existingError = field.parentNode.querySelector(".field-error");
//     if (existingError) {
//       existingError.remove();
//     }
//   }

//   function showSuccessMessage(message) {
//     const existingMessage = document.querySelector(
//       ".success-message, .error-message"
//     );
//     if (existingMessage) existingMessage.remove();

//     const successDiv = document.createElement("div");
//     successDiv.className = "success-message show";
//     successDiv.innerHTML = `<strong>${msg.success}</strong> ${message}`;
//     successDiv.style.cssText = `
//             background: #d4edda;
//             border: 1px solid #c3e6cb;
//             color: #155724;
//             padding: 12px 20px;
//             border-radius: 5px;
//             margin-bottom: 20px;
//             animation: slideDown 0.3s ease;
//         `;
//     contactForm.insertBefore(successDiv, contactForm.firstChild);
//     setTimeout(() => successDiv.remove(), 8000);
//   }

//   function showErrorMessage(message) {
//     const existingMessage = document.querySelector(
//       ".success-message, .error-message"
//     );
//     if (existingMessage) existingMessage.remove();

//     const errorDiv = document.createElement("div");
//     errorDiv.className = "error-message show";
//     errorDiv.innerHTML = `<strong>${
//       currentLang === "vi" ? "Lỗi!" : "Error!"
//     }</strong> ${message}`;
//     errorDiv.style.cssText = `
//             background: #f8d7da;
//             border: 1px solid #f5c6cb;
//             color: #721c24;
//             padding: 12px 20px;
//             border-radius: 5px;
//             margin-bottom: 20px;
//             animation: slideDown 0.3s ease;
//         `;
//     contactForm.insertBefore(errorDiv, contactForm.firstChild);
//     setTimeout(() => errorDiv.remove(), 8000);
//   }

//   function submitForm(formData) {
//     return fetch("/libertylaocai/user/submit", {
//       method: "POST",
//       body: formData,
//       headers: {
//         "X-Requested-With": "XMLHttpRequest",
//       },
//     })
//       .then((response) => {
//         if (!response.ok) {
//           throw new Error(
//             `Network response was not ok: ${response.status} ${response.statusText}`
//           );
//         }
//         return response.json();
//       })
//       .catch((error) => {
//         console.error("Fetch error:", error);
//         throw error;
//       });
//   }

//   contactForm.addEventListener("submit", function (e) {
//     e.preventDefault();

//     if (validateForm()) {
//       const submitBtn = contactForm.querySelector(".submit-btn");
//       const originalText = submitBtn.textContent;

//       submitBtn.textContent = msg.sending;
//       submitBtn.disabled = true;
//       submitBtn.style.opacity = "0.7";

//       const formData = new FormData(contactForm);
//       formData.append("action", "submit_contact");
//       formData.append("lienhe", true);

//       submitForm(formData)
//         .then((response) => {
//           if (response.success) {
//             showSuccessMessage(response.message);
//             contactForm.reset(); // Reset form
//             formInputs.forEach((input) => clearFieldError(input)); // Clear errors
//             // Force reset placeholders
//             formInputs.forEach((input) => {
//               const placeholder = input.getAttribute("data-placeholder");
//               if (placeholder) {
//                 input.setAttribute("placeholder", placeholder);
//                 input.removeAttribute("data-placeholder");
//               }
//             });
//           } else {
//             showErrorMessage(response.message || msg.error);
//           }
//         })
//         .catch((error) => {
//           console.error("Submit error:", error);
//           showErrorMessage(msg.error);
//         })
//         .finally(() => {
//           submitBtn.textContent = originalText;
//           submitBtn.disabled = false;
//           submitBtn.style.opacity = "1";
//         });
//     }
//   });

//   formInputs.forEach((input) => {
//     input.addEventListener("blur", function () {
//       if (this.hasAttribute("required") && !this.value.trim()) {
//         showFieldError(this, msg.required);
//       } else if (
//         this.type === "email" &&
//         this.value.trim() &&
//         !isValidEmail(this.value)
//       ) {
//         showFieldError(this, msg.emailInvalid);
//       } else if (
//         this.id === "phone" &&
//         this.value.trim() &&
//         !validatePhone(this.value)
//       ) {
//         showFieldError(this, msg.phoneInvalid);
//       } else {
//         clearFieldError(this);
//       }
//     });

//     input.addEventListener("input", function () {
//       if (this.parentNode.querySelector(".field-error")) {
//         clearFieldError(this);
//       }
//     });

//     input.addEventListener("focus", function () {
//       this.style.transform = "scale(1.02)";
//       setTimeout(() => (this.style.transform = "scale(1)"), 200);
//       if (!this.value) {
//         const placeholder = this.getAttribute("placeholder");
//         if (placeholder) {
//           this.setAttribute("data-placeholder", placeholder);
//           this.setAttribute("placeholder", "");
//         }
//       }
//     });

//     input.addEventListener("blur", function () {
//       if (!this.value && this.getAttribute("data-placeholder")) {
//         this.setAttribute("placeholder", this.getAttribute("data-placeholder"));
//         this.removeAttribute("data-placeholder");
//       }
//     });
//   });

//   const style = document.createElement("style");
//   style.textContent = `
//         @keyframes slideDown {
//             from { opacity: 0; transform: translateY(-10px); }
//             to { opacity: 1; transform: translateY(0); }
//         }
//     `;
//   document.head.appendChild(style);
// });
document.addEventListener("DOMContentLoaded", function () {
  const contactForm = document.getElementById("contactForm");
  const formInputs = contactForm.querySelectorAll("input, textarea");

  // Detect current language from HTML lang attribute
  const currentLang = document.documentElement.lang || "vi";

  // Multi-language messages
  const messages = {
    vi: {
      required: "Vui lòng điền thông tin này",
      emailInvalid: "Vui lòng nhập email hợp lệ",
      phoneInvalid: "Vui lòng nhập số điện thoại hợp lệ (10-11 số)",
      sending: "ĐANG GỬI...",
      success: "Thành công!",
      successMessage:
        "Cảm ơn bạn đã liên hệ với chúng tôi. Chúng tôi sẽ phản hồi trong thời gian sớm nhất.",
      submitBtn: "GỬI THÔNG TIN",
      error: "Có lỗi xảy ra, vui lòng thử lại sau.",
    },
    en: {
      required: "Please fill in this information",
      emailInvalid: "Please enter a valid email",
      phoneInvalid: "Please enter a valid phone number (10-11 digits)",
      sending: "SENDING...",
      success: "Success!",
      successMessage:
        "Thank you for contacting us. We will respond as soon as possible.",
      submitBtn: "SEND MESSAGE",
      error: "An error occurred, please try again later.",
    },
  };

  const msg = messages[currentLang] || messages.vi;

  // Form validation
  function validateForm() {
    let isValid = true;
    const requiredFields = contactForm.querySelectorAll("[required]");

    requiredFields.forEach((field) => {
      if (!field.value.trim()) {
        showFieldError(field, msg.required);
        isValid = false;
      } else {
        clearFieldError(field);
      }
    });

    const emailField = document.getElementById("email");
    if (emailField.value.trim() && !isValidEmail(emailField.value)) {
      showFieldError(emailField, msg.emailInvalid);
      isValid = false;
    }

    const phoneField = document.getElementById("phone");
    if (phoneField.value.trim() && !validatePhone(phoneField.value)) {
      showFieldError(phoneField, msg.phoneInvalid);
      isValid = false;
    }

    return isValid;
  }

  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  function validatePhone(phone) {
    const phoneRegex = /^[0-9]{10,11}$/;
    return phoneRegex.test(phone);
  }

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

  function clearFieldError(field) {
    field.style.borderColor = "#e0e0e0";
    const existingError = field.parentNode.querySelector(".field-error");
    if (existingError) {
      existingError.remove();
    }
  }

  function showSuccessMessage(message) {
    const existingMessage = document.querySelector(
      ".success-message, .error-message"
    );
    if (existingMessage) existingMessage.remove();

    const successDiv = document.createElement("div");
    successDiv.className = "success-message show";
    successDiv.innerHTML = `<strong>${msg.success}</strong> ${message}`;
    successDiv.style.cssText = `
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 12px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            animation: slideDown 0.3s ease;
        `;
    contactForm.insertBefore(successDiv, contactForm.firstChild);
    setTimeout(() => successDiv.remove(), 8000);
  }

  function showErrorMessage(message) {
    const existingMessage = document.querySelector(
      ".success-message, .error-message"
    );
    if (existingMessage) existingMessage.remove();

    const errorDiv = document.createElement("div");
    errorDiv.className = "error-message show";
    errorDiv.innerHTML = `<strong>${
      currentLang === "vi" ? "Lỗi!" : "Error!"
    }</strong> ${message}`;
    errorDiv.style.cssText = `
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 12px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            animation: slideDown 0.3s ease;
        `;
    contactForm.insertBefore(errorDiv, contactForm.firstChild);
    setTimeout(() => errorDiv.remove(), 8000);
  }

  function submitForm(formData) {
    return fetch("/libertylaocai/user/submit", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(
            `Network response was not ok: ${response.status} ${response.statusText}`
          );
        }
        return response.json();
      })
      .catch((error) => {
        console.error("Fetch error:", error);
        throw error;
      });
  }

  contactForm.addEventListener("submit", function (e) {
    e.preventDefault();

    // Hiển thị overlay loading toàn màn hình
    const fullScreenLoader = document.getElementById("fullScreenLoader");
    fullScreenLoader.style.display = "flex";

    if (validateForm()) {
      const submitBtn = contactForm.querySelector(".submit-btn");
      const originalText = submitBtn.textContent;

      submitBtn.textContent = msg.sending;
      submitBtn.disabled = true;
      submitBtn.style.opacity = "0.7";

      const formData = new FormData(contactForm);
      formData.append("action", "submit_contact");
      formData.append("lienhe", true);

      submitForm(formData)
        .then((response) => {
          if (response.success) {
            showSuccessMessage(response.message);
            contactForm.reset(); // Reset form
            formInputs.forEach((input) => clearFieldError(input)); // Clear errors
            // Force reset placeholders
            formInputs.forEach((input) => {
              const placeholder = input.getAttribute("data-placeholder");
              if (placeholder) {
                input.setAttribute("placeholder", placeholder);
                input.removeAttribute("data-placeholder");
              }
            });
          } else {
            showErrorMessage(response.message || msg.error);
          }
        })
        .catch((error) => {
          console.error("Submit error:", error);
          showErrorMessage(msg.error);
        })
        .finally(() => {
          // Ẩn overlay loading
          fullScreenLoader.style.display = "none";
          submitBtn.textContent = originalText;
          submitBtn.disabled = false;
          submitBtn.style.opacity = "1";
        });
    } else {
      // Ẩn overlay loading nếu form không hợp lệ
      fullScreenLoader.style.display = "none";
    }
  });

  formInputs.forEach((input) => {
    input.addEventListener("blur", function () {
      if (this.hasAttribute("required") && !this.value.trim()) {
        showFieldError(this, msg.required);
      } else if (
        this.type === "email" &&
        this.value.trim() &&
        !isValidEmail(this.value)
      ) {
        showFieldError(this, msg.emailInvalid);
      } else if (
        this.id === "phone" &&
        this.value.trim() &&
        !validatePhone(this.value)
      ) {
        showFieldError(this, msg.phoneInvalid);
      } else {
        clearFieldError(this);
      }
    });

    input.addEventListener("input", function () {
      if (this.parentNode.querySelector(".field-error")) {
        clearFieldError(this);
      }
    });

    input.addEventListener("focus", function () {
      this.style.transform = "scale(1.02)";
      setTimeout(() => (this.style.transform = "scale(1)"), 200);
      if (!this.value) {
        const placeholder = this.getAttribute("placeholder");
        if (placeholder) {
          this.setAttribute("data-placeholder", placeholder);
          this.setAttribute("placeholder", "");
        }
      }
    });

    input.addEventListener("blur", function () {
      if (!this.value && this.getAttribute("data-placeholder")) {
        this.setAttribute("placeholder", this.getAttribute("data-placeholder"));
        this.removeAttribute("data-placeholder");
      }
    });
  });

  const style = document.createElement("style");
  style.textContent = `
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
  document.head.appendChild(style);
});
