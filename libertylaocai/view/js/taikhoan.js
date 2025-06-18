function togglePassword(inputId) {
  const input = document.getElementById(inputId);
  if (!input) return;
  
  const button = input.nextElementSibling;
  const icon = button.querySelector("i");

  if (input.type === "password") {
    input.type = "text";
    icon.classList.replace("fa-eye", "fa-eye-slash");
  } else {
    input.type = "password";
    icon.classList.replace("fa-eye-slash", "fa-eye");
  }
}

function showMessage(type, message) {
  const messageDiv = document.createElement("div");
  messageDiv.className = `message ${type}`;
  messageDiv.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${message}`;
  
  document.body.appendChild(messageDiv);

  const messages = document.querySelectorAll('.message');
  const offset = (messages.length - 1) * 70;
  messageDiv.style.top = `${20 + offset}px`;

  setTimeout(() => {
    messageDiv.classList.add('hidden');
    setTimeout(() => messageDiv.remove(), 300);
  }, 5000);
}

function setupTabs() {
  const tabButtons = document.querySelectorAll(".tab-button");
  if (!tabButtons.length) return;

  tabButtons.forEach((button) => {
    button.addEventListener("click", function () {
      tabButtons.forEach(btn => btn.classList.remove("active"));
      this.classList.add("active");

      document.querySelectorAll(".tab-content").forEach(content => {
        content.style.display = "none";
      });

      const tabId = this.getAttribute("data-tab");
      const tabContent = document.getElementById(tabId);
      if (tabContent) tabContent.style.display = "block";
    });
  });
}

function checkPasswordStrength() {
  const password = document.getElementById("new_password")?.value || '';
  const strengthFill = document.getElementById("strengthFill");
  const strengthText = document.getElementById("strengthText");

  if (!strengthFill || !strengthText) return;

  const strength = getPasswordStrength(password);
  const colors = ["#dc3545", "#fd7e14", "#ffc107", "#28a745", "#20c997"];
  const texts = ["Rất yếu", "Yếu", "Trung bình", "Mạnh", "Rất mạnh"];

  if (!password) {
    strengthFill.style.width = "0%";
    strengthFill.style.background = "#e9ecef";
    strengthText.textContent = "Nhập mật khẩu";
    strengthText.style.color = "#666";
    return;
  }

  const percentage = (strength + 1) * 20;
  strengthFill.style.width = `${percentage}%`;
  strengthFill.style.background = colors[strength];
  strengthText.textContent = texts[strength];
  strengthText.style.color = colors[strength];
}

function getPasswordStrength(password) {
  let score = 0;
  if (!password) return 0;

  if (password.length >= 6) score++;
  if (password.length >= 10) score++;
  if (/[a-z]/.test(password)) score++;
  if (/[A-Z]/.test(password)) score++;
  if (/[0-9]/.test(password)) score++;
  if (/[^A-Za-z0-9]/.test(password)) score++;

  return Math.min(score - 1, 4);
}

function checkPasswordMatch() {
  const newPassword = document.getElementById("new_password")?.value || '';
  const confirmPassword = document.getElementById("confirm_password")?.value || '';
  const confirmInput = document.getElementById("confirm_password");

  if (!confirmInput) return;

  if (!confirmPassword) {
    confirmInput.style.borderColor = "#e9ecef";
    return;
  }

  confirmInput.style.borderColor = newPassword === confirmPassword ? "#28a745" : "#dc3545";
}

function validateProfileForm() {
  const mkEmailInput = document.getElementById("mk_email");
  if (mkEmailInput && mkEmailInput.value && mkEmailInput.value.length < 6) {
    showMessage('error', 'Mật khẩu email phải có ít nhất 6 ký tự!');
    return false;
  }
  return true;
}

function logout() {
  if (confirm("Bạn có chắc chắn muốn đăng xuất?")) {
    window.location.href = "/libertylaocai/login";
  }
}

function handleFormSubmit(formId, action) {
  const form = document.getElementById(formId);
  if (!form) return;

  form.addEventListener("submit", function(e) {
    e.preventDefault();
    
    if (formId === "profileForm" && !validateProfileForm()) {
      return;
    }

    const formData = new FormData(this);
    formData.append(action, 'true');
    formData.append('ajax', 'true');

    fetch('/libertylaocai/user/submit', {
      method: "POST",
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      showMessage(data.success ? 'success' : 'error', data.message);
      
      if (data.success) {
        // Reset password confirmation fields based on formId
        if (formId === "profileForm") {
          const profilePasswordInput = document.getElementById("profile_password");
          if (profilePasswordInput) profilePasswordInput.value = "";
        } else if (formId === "passwordForm") {
          const currentPasswordInput = document.getElementById("current_password");
          const newPasswordInput = document.getElementById("new_password");
          const confirmPasswordInput = document.getElementById("confirm_password");
          if (currentPasswordInput) currentPasswordInput.value = "";
          if (newPasswordInput) newPasswordInput.value = "";
          if (confirmPasswordInput) confirmPasswordInput.value = "";
          // Reset password strength indicator
          const strengthFill = document.getElementById("strengthFill");
          const strengthText = document.getElementById("strengthText");
          if (strengthFill) strengthFill.style.width = "0%";
          if (strengthFill) strengthFill.style.background = "#e9ecef";
          if (strengthText) strengthText.textContent = "Nhập mật khẩu";
          if (strengthText) strengthText.style.color = "#666";
          if (confirmPasswordInput) confirmPasswordInput.style.borderColor = "#e9ecef";
        } else if (formId === "hotelForm") {
          const hotelPasswordInput = document.getElementById("hotel_password");
          if (hotelPasswordInput) hotelPasswordInput.value = "";
        }

        // Update username in header if provided
        if (data.username) {
          const usernameElement = document.querySelector(".admin-info span");
          if (usernameElement) {
            usernameElement.textContent = `Chào mừng, ${data.username}`;
          }
        }
      }
    })
    .catch(error => {
      console.error("Error:", error);
      showMessage('error', 'Lỗi khi xử lý yêu cầu!');
    });
  });
}

function setup2FAToggle() {
  const twofaToggle = document.getElementById("twofaToggle");
  if (!twofaToggle) return;

  twofaToggle.addEventListener("change", function() {
    const isChecked = this.checked;

    fetch('/libertylaocai/user/submit', {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `toggle_2fa=true&active_2fa=${isChecked}&ajax=true`
    })
    .then(response => response.json())
    .then(data => {
      showMessage(data.success ? 'success' : 'error', data.message);
      if (!data.success) twofaToggle.checked = !isChecked;
    })
    .catch(error => {
      console.error("Error:", error);
      showMessage('error', 'Lỗi khi xử lý yêu cầu!');
      twofaToggle.checked = !isChecked;
    });
  });
}

document.addEventListener("DOMContentLoaded", function() {
  setupTabs();
  setup2FAToggle();
  
  handleFormSubmit('profileForm', 'update_profile');
  handleFormSubmit('passwordForm', 'change_password');
  handleFormSubmit('hotelForm', 'update_hotel');

  const newPasswordInput = document.getElementById("new_password");
  const confirmPasswordInput = document.getElementById("confirm_password");
  
  if (newPasswordInput) {
    newPasswordInput.addEventListener("input", checkPasswordStrength);
  }
  
  if (confirmPasswordInput) {
    confirmPasswordInput.addEventListener("input", checkPasswordMatch);
  }

  document.querySelectorAll(".message").forEach(message => {
    setTimeout(() => {
      message.classList.add('hidden');
      setTimeout(() => message.remove(), 300);
    }, 5000);
  });
});

function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const mainContent = document.querySelector(".main-content");
  const overlay = document.querySelector(".sidebar-overlay");
  const body = document.body;

  sidebar.classList.toggle("collapsed");
  sidebar.classList.toggle("active");
  mainContent.classList.toggle("collapsed");

  if (window.innerWidth <= 991) {
      if (sidebar.classList.contains("active")) {
          overlay.classList.add("active");
          body.classList.add("sidebar-open");
      } else {
          overlay.classList.remove("active");
          body.classList.remove("sidebar-open");
      }
  }
}