// Toggle password visibility
function togglePassword(inputId) {
  const input = document.getElementById(inputId);
  const button = input.nextElementSibling;
  const icon = button.querySelector("i");

  if (input.type === "password") {
    input.type = "text";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
  } else {
    input.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
  }
}

// Tab navigation
document.addEventListener("DOMContentLoaded", function () {
  const tabButtons = document.querySelectorAll(".tab-button");
  tabButtons.forEach((button) => {
    button.addEventListener("click", function () {
      // Remove active class from all buttons
      tabButtons.forEach((btn) => btn.classList.remove("active"));
      // Add active class to clicked button
      this.classList.add("active");

      // Hide all tab content
      document.querySelectorAll(".tab-content").forEach((content) => {
        content.style.display = "none";
      });

      // Show the selected tab content
      const tabId = this.getAttribute("data-tab");
      document.getElementById(tabId).style.display = "block";
    });
  });

  // Password strength checker
  const newPasswordInput = document.getElementById("new_password");
  if (newPasswordInput) {
    newPasswordInput.addEventListener("input", checkPasswordStrength);
  }

  // Password confirmation checker
  const confirmPasswordInput = document.getElementById("confirm_password");
  if (confirmPasswordInput) {
    confirmPasswordInput.addEventListener("input", checkPasswordMatch);
  }
});

// Check password strength
function checkPasswordStrength() {
  const password = document.getElementById("new_password").value;
  const strengthFill = document.getElementById("strengthFill");
  const strengthText = document.getElementById("strengthText");

  const strength = getPasswordStrength(password);
  const colors = ["#dc3545", "#fd7e14", "#ffc107", "#28a745", "#20c997"];
  const texts = ["Rất yếu", "Yếu", "Trung bình", "Mạnh", "Rất mạnh"];

  if (password.length === 0) {
    strengthFill.style.width = "0%";
    strengthFill.style.background = "#e9ecef";
    strengthText.textContent = "Nhập mật khẩu";
    strengthText.style.color = "#666";
    return;
  }

  const percentage = (strength + 1) * 20;
  strengthFill.style.width = percentage + "%";
  strengthFill.style.background = colors[strength];
  strengthText.textContent = texts[strength];
  strengthText.style.color = colors[strength];
}

// Get password strength score
function getPasswordStrength(password) {
  let score = 0;

  // Length check
  if (password.length >= 6) score++;
  if (password.length >= 10) score++;

  // Character variety checks
  if (/[a-z]/.test(password)) score++;
  if (/[A-Z]/.test(password)) score++;
  if (/[0-9]/.test(password)) score++;
  if (/[^A-Za-z0-9]/.test(password)) score++;

  return Math.min(score - 1, 4);
}

// Check password confirmation match
function checkPasswordMatch() {
  const newPassword = document.getElementById("new_password").value;
  const confirmPassword = document.getElementById("confirm_password").value;
  const confirmInput = document.getElementById("confirm_password");

  if (confirmPassword.length === 0) {
    confirmInput.style.borderColor = "#e9ecef";
    return;
  }

  if (newPassword === confirmPassword) {
    confirmInput.style.borderColor = "#28a745";
  } else {
    confirmInput.style.borderColor = "#dc3545";
  }
}

// Logout function
function logout() {
  if (confirm("Bạn có chắc chắn muốn đăng xuất?")) {
    // Redirect to login page or clear session
    window.location.href = "login.php"; // Điều chỉnh đường dẫn theo project của bạn
  }
}

// Auto hide messages after 5 seconds
document.addEventListener("DOMContentLoaded", function () {
  const messages = document.querySelectorAll(".message");
  messages.forEach((message) => {
    setTimeout(() => {
      message.style.display = "none";
    }, 5000);
  });
});
