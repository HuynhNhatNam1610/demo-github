// Cấu hình cứng
const ADMIN_CREDENTIALS = {
  username: "admin",
  password: "12345678",
  email: "admin@example.com",
};

const OTP_CODES = {
  login: "123456",
  reset: "654321",
};

// Biến toàn cục
let currentTab = "loginTab";
let tempCredentials = {};
let resendTimers = {
  login: null,
  reset: null,
};

// DOM Elements
const tabs = {
  loginTab: document.getElementById("loginTab"),
  loginOtpTab: document.getElementById("loginOtpTab"),
  forgotPasswordTab: document.getElementById("forgotPasswordTab"),
  resetOtpTab: document.getElementById("resetOtpTab"),
  newPasswordTab: document.getElementById("newPasswordTab"),
};

// Khởi tạo
document.addEventListener("DOMContentLoaded", function () {
  initializeEventListeners();
  initializeOtpInputs();
});

// Event Listeners
function initializeEventListeners() {
  // Form submissions
  document.getElementById("loginForm").addEventListener("submit", handleLogin);
  document
    .getElementById("loginOtpForm")
    .addEventListener("submit", handleLoginOtp);
  document
    .getElementById("forgotPasswordForm")
    .addEventListener("submit", handleForgotPassword);
  document
    .getElementById("resetOtpForm")
    .addEventListener("submit", handleResetOtp);
  document
    .getElementById("newPasswordForm")
    .addEventListener("submit", handleNewPassword);

  // Navigation links
  document
    .getElementById("forgotPasswordLink")
    .addEventListener("click", (e) => {
      e.preventDefault();
      switchTab("forgotPasswordTab");
    });

  document.getElementById("backToLogin").addEventListener("click", (e) => {
    e.preventDefault();
    switchTab("loginTab");
  });

  document
    .getElementById("backToLoginFromForgot")
    .addEventListener("click", (e) => {
      e.preventDefault();
      switchTab("loginTab");
    });

  document
    .getElementById("backToForgotPassword")
    .addEventListener("click", (e) => {
      e.preventDefault();
      switchTab("forgotPasswordTab");
    });

  // Resend OTP links
  document.getElementById("resendLoginOtp").addEventListener("click", (e) => {
    e.preventDefault();
    resendOtp("login");
  });

  document.getElementById("resendResetOtp").addEventListener("click", (e) => {
    e.preventDefault();
    resendOtp("reset");
  });

  // Password toggles
  document.getElementById("togglePassword").addEventListener("click", () => {
    togglePasswordVisibility("password", "togglePassword");
  });

  document.getElementById("toggleNewPassword").addEventListener("click", () => {
    togglePasswordVisibility("newPassword", "toggleNewPassword");
  });

  document
    .getElementById("toggleConfirmPassword")
    .addEventListener("click", () => {
      togglePasswordVisibility("confirmPassword", "toggleConfirmPassword");
    });
}

// Chuyển đổi tab
function switchTab(tabName) {
  // Ẩn tất cả tabs
  Object.values(tabs).forEach((tab) => {
    tab.classList.remove("active");
  });

  // Hiển thị tab được chọn
  tabs[tabName].classList.add("active");
  currentTab = tabName;

  // Clear messages
  clearAllMessages();

  // Reset forms
  resetAllForms();

  // Clear timers
  clearResendTimers();
}

// Xử lý đăng nhập
function handleLogin(e) {
  e.preventDefault();

  const username = document.getElementById("username").value.trim();
  const password = document.getElementById("password").value;

  if (!username || !password) {
    showMessage(
      "loginMessage",
      "Vui lòng nhập đầy đủ thông tin đăng nhập",
      "error"
    );
    return;
  }

  showLoading("loginForm", true);

  setTimeout(() => {
    if (
      username === ADMIN_CREDENTIALS.username &&
      password === ADMIN_CREDENTIALS.password
    ) {
      tempCredentials = { username, password };
      document.getElementById("loginOtpEmail").textContent =
        ADMIN_CREDENTIALS.email;
      showMessage(
        "loginMessage",
        "Đăng nhập thành công! Mã OTP đã được gửi đến email của bạn",
        "success"
      );
      setTimeout(() => {
        switchTab("loginOtpTab");
        startResendTimer("login");
        focusFirstOtpInput("#loginOtpTab");
      }, 1000);
    } else {
      showMessage(
        "loginMessage",
        "Tên đăng nhập hoặc mật khẩu không chính xác",
        "error"
      );
      document.getElementById("password").value = "";
      shakeElement("loginForm");
    }
    showLoading("loginForm", false);
  }, 1000);
}

// Xử lý OTP đăng nhập
function handleLoginOtp(e) {
  e.preventDefault();

  const otpInputs = document.querySelectorAll("#loginOtpTab .otp-input");
  const otp = Array.from(otpInputs)
    .map((input) => input.value)
    .join("");

  if (otp.length !== 6) {
    showMessage("loginOtpMessage", "Vui lòng nhập đầy đủ mã OTP", "error");
    markOtpInputsError(otpInputs);
    return;
  }

  showLoading("loginOtpForm", true);

  setTimeout(() => {
    if (otp === OTP_CODES.login) {
      showMessage(
        "loginOtpMessage",
        "Xác thực thành công! Đang chuyển hướng...",
        "success"
      );
      setTimeout(() => {
        alert("Chuyển hướng đến trang quản trị!");
        // window.location.href = 'admin-dashboard.html';
      }, 1500);
    } else {
      showMessage("loginOtpMessage", "Mã OTP không chính xác", "error");
      clearOtpInputs("#loginOtpTab .otp-input");
      markOtpInputsError(otpInputs);
      focusFirstOtpInput("#loginOtpTab");
    }
    showLoading("loginOtpForm", false);
  }, 1000);
}

// Xử lý quên mật khẩu
function handleForgotPassword(e) {
  e.preventDefault();

  const email = document.getElementById("resetEmail").value.trim();

  if (!email) {
    showMessage("forgotMessage", "Vui lòng nhập email", "error");
    return;
  }

  showLoading("forgotPasswordForm", true);

  setTimeout(() => {
    if (email === ADMIN_CREDENTIALS.email) {
      document.getElementById("resetOtpEmail").textContent = email;
      showMessage(
        "forgotMessage",
        "Mã OTP đã được gửi đến email của bạn",
        "success"
      );
      setTimeout(() => {
        switchTab("resetOtpTab");
        startResendTimer("reset");
        focusFirstOtpInput("#resetOtpTab");
      }, 1000);
    } else {
      showMessage(
        "forgotMessage",
        "Email không tồn tại trong hệ thống",
        "error"
      );
    }
    showLoading("forgotPasswordForm", false);
  }, 1000);
}

// Xử lý OTP đặt lại mật khẩu
function handleResetOtp(e) {
  e.preventDefault();

  const otpInputs = document.querySelectorAll("#resetOtpTab .otp-input");
  const otp = Array.from(otpInputs)
    .map((input) => input.value)
    .join("");

  if (otp.length !== 6) {
    showMessage("resetOtpMessage", "Vui lòng nhập đầy đủ mã OTP", "error");
    markOtpInputsError(otpInputs);
    return;
  }

  showLoading("resetOtpForm", true);

  setTimeout(() => {
    if (otp === OTP_CODES.reset) {
      showMessage("resetOtpMessage", "Xác thực thành công!", "success");
      setTimeout(() => {
        switchTab("newPasswordTab");
      }, 1000);
    } else {
      showMessage("resetOtpMessage", "Mã OTP không chính xác", "error");
      clearOtpInputs("#resetOtpTab .otp-input");
      markOtpInputsError(otpInputs);
      focusFirstOtpInput("#resetOtpTab");
    }
    showLoading("resetOtpForm", false);
  }, 1000);
}

// Xử lý đặt mật khẩu mới
function handleNewPassword(e) {
  e.preventDefault();

  const newPassword = document.getElementById("newPassword").value;
  const confirmPassword = document.getElementById("confirmPassword").value;

  if (!newPassword || !confirmPassword) {
    showMessage(
      "newPasswordMessage",
      "Vui lòng nhập đầy đủ thông tin",
      "error"
    );
    return;
  }

  if (newPassword.length < 6) {
    showMessage(
      "newPasswordMessage",
      "Mật khẩu phải có ít nhất 6 ký tự",
      "error"
    );
    return;
  }

  if (newPassword !== confirmPassword) {
    showMessage("newPasswordMessage", "Mật khẩu xác nhận không khớp", "error");
    return;
  }

  showLoading("newPasswordForm", true);

  setTimeout(() => {
    // Cập nhật mật khẩu cứng (trong thực tế sẽ gửi API)
    ADMIN_CREDENTIALS.password = newPassword;

    showMessage(
      "newPasswordMessage",
      "Đặt lại mật khẩu thành công! Đang chuyển về trang đăng nhập...",
      "success"
    );

    setTimeout(() => {
      switchTab("loginTab");
      showMessage(
        "loginMessage",
        "Vui lòng đăng nhập với mật khẩu mới",
        "info"
      );
    }, 2000);

    showLoading("newPasswordForm", false);
  }, 1000);
}

// Khởi tạo OTP inputs với nhập liên tục
function initializeOtpInputs() {
  const allOtpInputs = document.querySelectorAll(".otp-input");

  allOtpInputs.forEach((input, globalIndex) => {
    const container = input.parentElement;
    const localInputs = Array.from(container.children);
    const localIndex = localInputs.indexOf(input);

    input.addEventListener("input", function (e) {
      const value = e.target.value;

      // Chỉ cho phép số
      if (!/^\d*$/.test(value)) {
        e.target.value = "";
        return;
      }

      // Remove error styling
      input.classList.remove("error");
      input.classList.add("filled");

      // Tự động chuyển sang input tiếp theo
      if (value && localIndex < localInputs.length - 1) {
        localInputs[localIndex + 1].focus();
      }
    });

    input.addEventListener("keydown", function (e) {
      // Xử lý Backspace
      if (e.key === "Backspace") {
        if (!input.value && localIndex > 0) {
          localInputs[localIndex - 1].focus();
          localInputs[localIndex - 1].value = "";
          localInputs[localIndex - 1].classList.remove("filled");
        } else if (input.value) {
          input.value = "";
          input.classList.remove("filled");
        }
      }

      // Xử lý Arrow keys
      if (e.key === "ArrowLeft" && localIndex > 0) {
        e.preventDefault();
        localInputs[localIndex - 1].focus();
      }
      if (e.key === "ArrowRight" && localIndex < localInputs.length - 1) {
        e.preventDefault();
        localInputs[localIndex + 1].focus();
      }
    });

    input.addEventListener("paste", function (e) {
      e.preventDefault();
      const paste = (e.clipboardData || window.clipboardData).getData("text");
      const digits = paste.replace(/\D/g, "").slice(0, 6);

      // Clear all inputs first
      localInputs.forEach((inp) => {
        inp.value = "";
        inp.classList.remove("filled", "error");
      });

      // Fill inputs with pasted digits
      for (let i = 0; i < Math.min(digits.length, localInputs.length); i++) {
        localInputs[i].value = digits[i];
        localInputs[i].classList.add("filled");
      }

      // Focus on next empty input or last input
      const nextEmpty = localInputs.find((inp) => !inp.value);
      if (nextEmpty) {
        nextEmpty.focus();
      } else {
        localInputs[localInputs.length - 1].focus();
      }
    });

    // Remove error styling on focus
    input.addEventListener("focus", function () {
      input.classList.remove("error");
    });
  });
}

// Gửi lại OTP
function resendOtp(type) {
  const resendLink = document.getElementById(
    `resend${type === "login" ? "Login" : "Reset"}Otp`
  );
  const countdownEl = document.getElementById(`${type}Countdown`);
  const messageEl = document.getElementById(
    `${type === "login" ? "loginOtp" : "resetOtp"}Message`
  );

  if (resendLink.classList.contains("disabled")) {
    return;
  }

  // Simulate sending OTP
  showMessage(messageEl.id, "Đang gửi lại mã OTP...", "info");

  setTimeout(() => {
    showMessage(
      messageEl.id,
      "Mã OTP mới đã được gửi đến email của bạn",
      "success"
    );
    startResendTimer(type);

    // Clear and focus first OTP input
    const tabId = type === "login" ? "#loginOtpTab" : "#resetOtpTab";
    clearOtpInputs(`${tabId} .otp-input`);
    focusFirstOtpInput(tabId);
  }, 1000);
}

// Bắt đầu đếm ngược gửi lại OTP
function startResendTimer(type) {
  const resendLink = document.getElementById(
    `resend${type === "login" ? "Login" : "Reset"}Otp`
  );
  const countdownEl = document.getElementById(`${type}Countdown`);

  let timeLeft = 60;
  resendLink.classList.add("disabled");

  const timer = setInterval(() => {
    countdownEl.textContent = `Gửi lại sau ${timeLeft}s`;
    timeLeft--;

    if (timeLeft < 0) {
      clearInterval(timer);
      resendLink.classList.remove("disabled");
      countdownEl.textContent = "";
    }
  }, 1000);

  resendTimers[type] = timer;
}

// Xóa tất cả timer
function clearResendTimers() {
  Object.values(resendTimers).forEach((timer) => {
    if (timer) clearInterval(timer);
  });
  resendTimers = { login: null, reset: null };

  // Reset UI
  document.querySelectorAll(".resend-link").forEach((link) => {
    link.classList.remove("disabled");
  });
  document.querySelectorAll(".countdown").forEach((countdown) => {
    countdown.textContent = "";
  });
}

// Focus vào ô OTP đầu tiên
function focusFirstOtpInput(containerSelector) {
  setTimeout(() => {
    const firstInput = document.querySelector(
      `${containerSelector} .otp-input`
    );
    if (firstInput) {
      firstInput.focus();
    }
  }, 100);
}

// Đánh dấu lỗi cho OTP inputs
function markOtpInputsError(inputs) {
  inputs.forEach((input) => {
    input.classList.add("error");
    input.classList.remove("filled");
  });
}

// Hiển thị/ẩn mật khẩu
function togglePasswordVisibility(inputId, toggleId) {
  const input = document.getElementById(inputId);
  const toggle = document.getElementById(toggleId);
  const icon = toggle.querySelector("i");

  const type = input.getAttribute("type") === "password" ? "text" : "password";
  input.setAttribute("type", type);

  if (type === "password") {
    icon.className = "bi bi-eye";
  } else {
    icon.className = "bi bi-eye-slash";
  }
}

// Hiển thị thông báo
function showMessage(messageId, text, type) {
  const messageEl = document.getElementById(messageId);
  messageEl.textContent = text;
  messageEl.className = `message ${type} show`;

  setTimeout(() => {
    messageEl.classList.remove("show");
  }, 5000);
}

// Xóa tất cả thông báo
function clearAllMessages() {
  const messages = document.querySelectorAll(".message");
  messages.forEach((msg) => {
    msg.classList.remove("show");
    msg.textContent = "";
  });
}

// Reset tất cả forms
function resetAllForms() {
  const forms = document.querySelectorAll("form");
  forms.forEach((form) => form.reset());
  clearOtpInputs(".otp-input");
}

// Xóa OTP inputs
function clearOtpInputs(selector) {
  const inputs = document.querySelectorAll(selector);
  inputs.forEach((input) => {
    input.value = "";
    input.classList.remove("filled", "error");
  });
}

// Hiển thị loading
function showLoading(formId, isLoading) {
  const form = document.getElementById(formId);
  const btn = form.querySelector('button[type="submit"]');
  const originalHtml = btn.innerHTML;

  if (isLoading) {
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Đang xử lý...';
  } else {
    btn.disabled = false;
    // Khôi phục text gốc
    const originalTexts = {
      loginForm: "ĐĂNG NHẬP",
      loginOtpForm: "XÁC NHẬN OTP",
      forgotPasswordForm: '<i class="bi bi-send"></i> GỬI MÃ OTP',
      resetOtpForm: "XÁC NHẬN OTP",
      newPasswordForm: '<i class="bi bi-shield-check"></i> ĐẶT LẠI MẬT KHẨU',
    };
    btn.innerHTML = originalTexts[formId] || "XÁC NHẬN";
  }
}

// Shake animation
function shakeElement(elementId) {
  const element = document.getElementById(elementId);
  element.style.animation = "shake 0.5s";
  setTimeout(() => {
    element.style.animation = "";
  }, 500);
}
