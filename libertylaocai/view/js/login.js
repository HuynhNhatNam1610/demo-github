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
  setupOtpInputs("login-otp");
  setupOtpInputs("reset-otp");
});

// Event Listeners
function initializeEventListeners() {
  // Submit form OTP đăng nhập
  document
    .getElementById("loginOtpForm")
    .addEventListener("submit", async (e) => {
      e.preventDefault();
      await handleLoginOtp();
    });

  // Resend OTP đăng nhập
  document
    .getElementById("resendLoginOtp")
    .addEventListener("click", async (e) => {
      e.preventDefault();
      await handleResendLoginOtp();
    });

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

  // // Resend OTP links
  // document.getElementById("resendLoginOtp").addEventListener("click", (e) => {
  //   e.preventDefault();
  //   resendOtp("login");
  // });

  // document.getElementById("resendResetOtp").addEventListener("click", (e) => {
  //   e.preventDefault();
  //   resendOtp("reset");
  // });

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

  // Submit form đăng nhập
  document.getElementById("loginForm").addEventListener("submit", async (e) => {
    e.preventDefault(); // Ngăn gửi form mặc định
    await handleLogin();
  });

  // Submit form quên mk
  document
    .getElementById("forgotPasswordForm")
    .addEventListener("submit", async (e) => {
      e.preventDefault(); // Ngăn gửi form mặc định
      await handleForgotPassword();
    });

  document
    .getElementById("resetOtpForm")
    .addEventListener("submit", async (e) => {
      e.preventDefault();
      await handleResetOtp();
    });
  document
    .getElementById("newPasswordForm")
    .addEventListener("submit", async (e) => {
      e.preventDefault();
      await handleNewPassword();
    });
}

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

  // Tự động focus ô OTP đầu tiên nếu là tab OTP
  if (tabName === "loginOtpTab") {
    const firstOtpInput = document.querySelector(".login-otp");
    if (firstOtpInput) {
      firstOtpInput.focus();
    }
  } else if (tabName === "resetOtpTab") {
    const firstOtpInput = document.querySelector(".reset-otp");
    if (firstOtpInput) {
      firstOtpInput.focus();
    }
  }
}

// Thêm hàm clearOtpInputs
function clearOtpInputs(selector) {
  const inputs = document.querySelectorAll(selector);
  inputs.forEach((input) => {
    input.value = "";
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

// Shake animation
function shakeElement(elementId) {
  const element = document.getElementById(elementId);
  element.style.animation = "shake 0.5s";
  setTimeout(() => {
    element.style.animation = "";
  }, 500);
}

async function handleLogin() {
  const form = document.getElementById("loginForm");
  const email = form.querySelector("#username").value.trim();
  const password = form.querySelector("#password").value.trim();
  const loginBtn = form.querySelector(".login-btn");

  if (!email || !password) {
    showMessage("loginMessage", "Vui lòng nhập email và mật khẩu.", "error");
    if (!email) shakeElement("username");
    if (!password) shakeElement("password");
    return;
  }

  loginBtn.disabled = true;
  loginBtn.innerHTML = '<span class="spinner"></span> Đang xử lý...';

  try {
    const formData = new FormData();
    formData.append("login", "1");
    formData.append("email", email);
    formData.append("password", password);

    const response = await fetch("/libertylaocai/user/submit", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      body: formData,
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const text = await response.text();
    console.log("Raw response:", text);

    let data;
    const cleanedText = text.trim().replace(/^\uFEFF/, "");

    if (!cleanedText.startsWith("{") && !cleanedText.startsWith("[")) {
      throw new Error("Server returned non-JSON response");
    }

    try {
      data = JSON.parse(cleanedText);
    } catch (parseError) {
      showMessage("loginMessage", "Lỗi định dạng phản hồi từ server.", "error");
      return;
    }

    // Xử lý response
    if (data.success) {
      if (data.require_2fa) {
        // Lưu email tạm thời để sử dụng trong tab OTP
        sessionStorage.setItem("login_email", email);
        // Chuyển sang tab OTP
        switchTab("loginOtpTab");
        showMessage(
          "loginOtpMessage",
          data.message || "Vui lòng nhập mã OTP.",
          "success"
        );
        // Cập nhật email hiển thị trên form OTP
        const otpEmailDisplay = document.getElementById("otpEmailDisplay");
        if (otpEmailDisplay) {
          otpEmailDisplay.textContent = email;
        }
        form.reset();
      } else {
        showMessage(
          "loginMessage",
          data.message || "Đăng nhập thành công!",
          "success"
        );
        setTimeout(() => {
          window.location.href = "/libertylaocai/admin";
        }, 1000);
      }
    } else {
      showMessage(
        "loginMessage",
        data.message || "Email hoặc mật khẩu không đúng.",
        "error"
      );
      shakeElement("username");
      shakeElement("password");
    }
  } catch (error) {
    showMessage(
      "loginMessage",
      "Không thể kết nối tới server. Vui lòng thử lại.",
      "error"
    );
    console.error("Login error:", error);
  } finally {
    loginBtn.disabled = false;
    loginBtn.innerHTML = "ĐĂNG NHẬP";
  }
}

async function handleLoginOtp() {
  const form = document.getElementById("loginOtpForm");
  const otpInputs = form.querySelectorAll(".login-otp");
  const submitBtn = form.querySelector(".submit-btn");

  // Lấy OTP từ các input
  let otp = "";
  otpInputs.forEach((input) => {
    otp += input.value.trim();
  });

  if (otp.length !== 6) {
    showMessage(
      "loginOtpMessage",
      "Vui lòng nhập đầy đủ mã OTP 6 số.",
      "error"
    );
    otpInputs.forEach((input, index) => {
      if (!input.value.trim()) {
        shakeElement(`login-otp-${index}`);
      }
    });
    return;
  }

  // Kiểm tra OTP chỉ chứa số
  if (!/^\d{6}$/.test(otp)) {
    showMessage("loginOtpMessage", "Mã OTP phải là 6 chữ số.", "error");
    return;
  }

  submitBtn.disabled = true;
  submitBtn.innerHTML = '<span class="spinner"></span> Đang xác thực...';

  try {
    const formData = new FormData();
    formData.append("verify_login_otp", "1");
    formData.append("otp", otp);

    // Lấy email từ sessionStorage
    const email = sessionStorage.getItem("login_email");
    if (!email) {
      showMessage(
        "loginOtpMessage",
        "Không tìm thấy email. Vui lòng thử lại từ đầu.",
        "error"
      );
      return;
    }
    formData.append("email", email);

    const response = await fetch("/libertylaocai/user/submit", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      body: formData,
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const text = await response.text();
    console.log("Raw response:", text);

    let data;
    const cleanedText = text.trim().replace(/^\uFEFF/, "");

    if (!cleanedText.startsWith("{") && !cleanedText.startsWith("[")) {
      throw new Error("Server returned non-JSON response");
    }

    try {
      data = JSON.parse(cleanedText);
    } catch (parseError) {
      showMessage(
        "loginOtpMessage",
        "Lỗi định dạng phản hồi từ server.",
        "error"
      );
      return;
    }

    if (data.success) {
      showMessage(
        "loginOtpMessage",
        data.message || "Xác thực OTP thành công!",
        "success"
      );
      // Xóa email khỏi sessionStorage
      sessionStorage.removeItem("login_email");
      // Chuyển hướng đến trang admin
      setTimeout(() => {
        window.location.href = "/libertylaocai/admin";
      }, 1000);
      clearOtpInputs(".login-otp");
    } else {
      showMessage(
        "loginOtpMessage",
        data.message || "Mã OTP không đúng hoặc đã hết hạn.",
        "error"
      );
      clearOtpInputs(".login-otp");
      if (otpInputs[0]) {
        otpInputs[0].focus();
      }
    }
  } catch (error) {
    showMessage(
      "loginOtpMessage",
      "Không thể kết nối tới server. Vui lòng thử lại.",
      "error"
    );
    console.error("Login OTP error:", error);
  } finally {
    submitBtn.disabled = false;
    submitBtn.innerHTML = "XÁC NHẬN OTP";
  }
}

async function handleResendLoginOtp() {
  const form = document.getElementById("loginOtpForm");
  const resendLink = document.getElementById("resendLoginOtp");
  const countdown = document.getElementById("loginCountdown");

  resendLink.style.display = "none";
  const submitBtn = form.querySelector(".submit-btn");
  submitBtn.disabled = true;
  submitBtn.innerHTML = '<span class="spinner"></span> Đang xử lý...';

  try {
    const formData = new FormData();
    formData.append("resend_login_otp", "1");

    const email = sessionStorage.getItem("login_email");
    if (!email) {
      showMessage(
        "loginOtpMessage",
        "Không tìm thấy email. Vui lòng thử lại từ đầu.",
        "error"
      );
      resendLink.style.display = "inline-block";
      return;
    }
    formData.append("email", email);

    const response = await fetch("/libertylaocai/user/submit", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      body: formData,
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const text = await response.text();
    const cleanedText = text.trim().replace(/^\uFEFF/, "");

    let data;
    try {
      data = JSON.parse(cleanedText);
    } catch (parseError) {
      showMessage(
        "loginOtpMessage",
        "Lỗi định dạng phản hồi từ server.",
        "error"
      );
      return;
    }

    if (data.success) {
      showMessage(
        "loginOtpMessage",
        data.message || "Đã gửi lại mã OTP.",
        "success"
      );
      startCountdown("loginCountdown", 60, () => {
        resendLink.style.display = "inline-block";
      });
      clearOtpInputs(".login-otp");
    } else {
      showMessage(
        "loginOtpMessage",
        data.message || "Không thể gửi lại OTP.",
        "error"
      );
      resendLink.style.display = "inline-block";
    }
  } catch (error) {
    showMessage("loginOtpMessage", "Lỗi kết nối server.", "error");
    resendLink.style.display = "inline-block";
    console.error("Resend login OTP error:", error);
  } finally {
    submitBtn.disabled = false;
    submitBtn.innerHTML = "XÁC NHẬN OTP";
  }
}

async function handleForgotPassword() {
  const form = document.getElementById("forgotPasswordForm");
  const email = form.querySelector("#resetEmail").value.trim();
  const submitBtn = form.querySelector(".submit-btn");

  if (!email) {
    showMessage("forgotMessage", "Vui lòng nhập email.", "error");
    shakeElement("forgotEmail");
    return;
  }

  // Validate email format
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    showMessage("forgotMessage", "Email không đúng định dạng.", "error");
    shakeElement("forgotEmail");
    return;
  }

  submitBtn.disabled = true;
  submitBtn.innerHTML = '<span class="spinner"></span> Đang xử lý...';

  try {
    const formData = new FormData();
    formData.append("forgot_password", "1");
    formData.append("email", email);

    const response = await fetch("/libertylaocai/user/submit", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      body: formData,
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const text = await response.text();
    console.log("Raw response:", text);

    let data;
    const cleanedText = text.trim().replace(/^\uFEFF/, "");

    if (!cleanedText.startsWith("{") && !cleanedText.startsWith("[")) {
      throw new Error("Server returned non-JSON response");
    }

    try {
      data = JSON.parse(cleanedText);
    } catch (parseError) {
      showMessage(
        "forgotMessage",
        "Lỗi định dạng phản hồi từ server.",
        "error"
      );
      return;
    }

    // Xử lý response
    if (data.success) {
      showMessage(
        "forgotMessage",
        data.message || "Đã gửi email khôi phục mật khẩu thành công!",
        "success"
      );
      // Chuyển sang tab resetOtpTab
      switchTab("resetOtpTab");
      // Lưu email tạm thời để sử dụng trong tab OTP
      sessionStorage.setItem("reset_email", email);
      // Reset form sau khi thành công
      form.reset();
    } else {
      showMessage(
        "forgotMessage",
        data.message || "Email không tồn tại trong hệ thống.",
        "error"
      );
      shakeElement("resetEmail");
    }
  } catch (error) {
    showMessage(
      "forgotMessage",
      "Không thể kết nối tới server. Vui lòng thử lại.",
      "error"
    );
    console.error("Forgot password error:", error);
  } finally {
    submitBtn.disabled = false;
    submitBtn.innerHTML = "GỬI OTP KHÔI PHỤC";
  }
}

async function handleResetOtp() {
  const form = document.getElementById("resetOtpForm");
  const otpInputs = form.querySelectorAll(".reset-otp");
  const submitBtn = form.querySelector(".submit-btn");

  // Lấy OTP từ các input
  let otp = "";
  otpInputs.forEach((input) => {
    otp += input.value.trim();
  });

  if (otp.length !== 6) {
    showMessage(
      "resetOtpMessage",
      "Vui lòng nhập đầy đủ mã OTP 6 số.",
      "error"
    );
    otpInputs.forEach((input, index) => {
      if (!input.value.trim()) {
        shakeElement(`otp-${index}`);
      }
    });
    return;
  }

  // Kiểm tra OTP chỉ chứa số
  if (!/^\d{6}$/.test(otp)) {
    showMessage("resetOtpMessage", "Mã OTP phải là 6 chữ số.", "error");
    return;
  }

  submitBtn.disabled = true;
  submitBtn.innerHTML = '<span class="spinner"></span> Đang xác thực...';

  try {
    const formData = new FormData();
    formData.append("verify_reset_otp", "1");
    formData.append("otp", otp);

    // Lấy email từ sessionStorage
    const email = sessionStorage.getItem("reset_email");
    if (!email) {
      showMessage(
        "resetOtpMessage",
        "Không tìm thấy email. Vui lòng thử lại từ đầu.",
        "error"
      );
      return;
    }
    formData.append("email", email);

    const response = await fetch("/libertylaocai/user/submit", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      body: formData,
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const text = await response.text();
    console.log("Raw response:", text);

    let data;
    const cleanedText = text.trim().replace(/^\uFEFF/, "");

    if (!cleanedText.startsWith("{") && !cleanedText.startsWith("[")) {
      throw new Error("Server returned non-JSON response");
    }

    try {
      data = JSON.parse(cleanedText);
    } catch (parseError) {
      showMessage(
        "resetOtpMessage",
        "Lỗi định dạng phản hồi từ server.",
        "error"
      );
      return;
    }

    // Xử lý response
    if (data.success) {
      showMessage(
        "resetOtpMessage",
        data.message || "Xác thực OTP thành công!",
        "success"
      );

      // Lưu token reset password nếu có
      if (data.reset_token) {
        sessionStorage.setItem("reset_token", data.reset_token);
      }

      // Chuyển đến tab đặt lại mật khẩu sau 1 giây
      setTimeout(() => {
        switchTab("newPasswordTab"); // Sửa từ showTab thành switchTab
      }, 1000);

      clearOtpInputs(".reset-otp");
    } else {
      showMessage(
        "resetOtpMessage",
        data.message || "Mã OTP không đúng hoặc đã hết hạn.",
        "error"
      );

      // Clear OTP inputs nếu sai
      clearOtpInputs(".reset-otp");

      // Focus vào input đầu tiên
      if (otpInputs[0]) {
        otpInputs[0].focus();
      }
    }
  } catch (error) {
    showMessage(
      "resetOtpMessage",
      "Không thể kết nối tới server. Vui lòng thử lại.",
      "error"
    );
    console.error("Reset OTP error:", error);
  } finally {
    submitBtn.disabled = false;
    submitBtn.innerHTML = "XÁC NHẬN OTP";
  }
}

// Hàm xử lý gửi lại OTP
async function handleResendResetOtp() {
  const form = document.getElementById("resetOtpForm");
  const resendLink = document.getElementById("resendResetOtp");
  const countdown = document.getElementById("resetCountdown");

  resendLink.style.display = "none";
  const submitBtn = form.querySelector(".submit-btn");
  submitBtn.disabled = true;
  submitBtn.innerHTML = '<span class="spinner"></span> Đang xử lý...';

  try {
    const formData = new FormData();
    formData.append("resend_reset_otp", "1");

    const email = sessionStorage.getItem("reset_email");
    if (!email) {
      showMessage(
        "resetOtpMessage",
        "Không tìm thấy email. Vui lòng thử lại từ đầu.",
        "error"
      );
      resendLink.style.display = "inline-block";
      return;
    }
    formData.append("email", email);

    const response = await fetch("/libertylaocai/user/submit", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      body: formData,
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const text = await response.text();
    const cleanedText = text.trim().replace(/^\uFEFF/, "");

    let data;
    try {
      data = JSON.parse(cleanedText);
    } catch (parseError) {
      showMessage(
        "resetOtpMessage",
        "Lỗi định dạng phản hồi từ server.",
        "error"
      );
      return;
    }

    if (data.success) {
      showMessage(
        "resetOtpMessage",
        data.message || "Đã gửi lại mã OTP.",
        "success"
      );

      // Bắt đầu đếm ngược 60 giây
      startCountdown("resetCountdown", 60, () => {
        resendLink.style.display = "inline-block";
      });

      // Clear OTP inputs
      clearOtpInputs("reset-otp");
    } else {
      showMessage(
        "resetOtpMessage",
        data.message || "Không thể gửi lại OTP.",
        "error"
      );
      resendLink.style.display = "inline-block";
    }
  } catch (error) {
    showMessage("resetOtpMessage", "Lỗi kết nối server.", "error");
    resendLink.style.display = "inline-block";
    console.error("Resend reset OTP error:", error);
  } finally {
    submitBtn.disabled = false;
    submitBtn.innerHTML = "XÁC NHẬN OTP";
  }
}

// Hàm clear OTP inputs
function clearOtpInputs(className) {
  const inputs = document.querySelectorAll(`${className}`);
  inputs.forEach((input) => {
    input.value = "";
  });
}

// // Hàm xử lý OTP input navigation
// function setupResetOtpInputs() {
//   const otpInputs = document.querySelectorAll(".reset-otp");

//   otpInputs.forEach((input, index) => {
//     // Chỉ cho phép nhập số
//     input.addEventListener("input", (e) => {
//       const value = e.target.value;

//       // Chỉ giữ lại số
//       e.target.value = value.replace(/[^0-9]/g, "");

//       // Auto focus đến ô tiếp theo
//       if (e.target.value && index < otpInputs.length - 1) {
//         otpInputs[index + 1].focus();
//       }
//     });

//     // Xử lý phím Backspace
//     input.addEventListener("keydown", (e) => {
//       if (e.key === "Backspace" && !e.target.value && index > 0) {
//         otpInputs[index - 1].focus();
//       }
//     });

//     // Xử lý paste
//     input.addEventListener("paste", (e) => {
//       e.preventDefault();
//       const paste = (e.clipboardData || window.clipboardData).getData("text");
//       const numbers = paste.replace(/[^0-9]/g, "").slice(0, 6);

//       // Điền numbers vào các ô
//       for (let i = 0; i < numbers.length && i < otpInputs.length; i++) {
//         otpInputs[i].value = numbers[i];
//       }

//       // Focus vào ô cuối cùng được điền
//       const lastFilledIndex = Math.min(numbers.length, otpInputs.length) - 1;
//       if (lastFilledIndex >= 0) {
//         otpInputs[lastFilledIndex].focus();
//       }
//     });
//   });
// }
function setupOtpInputs(className) {
  const otpInputs = document.querySelectorAll(`.${className}`);

  otpInputs.forEach((input, index) => {
    input.addEventListener("input", (e) => {
      const value = e.target.value;
      e.target.value = value.replace(/[^0-9]/g, "");
      if (e.target.value && index < otpInputs.length - 1) {
        otpInputs[index + 1].focus();
      }
    });

    input.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && !e.target.value && index > 0) {
        otpInputs[index - 1].focus();
      }
    });

    input.addEventListener("paste", (e) => {
      e.preventDefault();
      const paste = (e.clipboardData || window.clipboardData).getData("text");
      const numbers = paste.replace(/[^0-9]/g, "").slice(0, 6);
      for (let i = 0; i < numbers.length && i < otpInputs.length; i++) {
        otpInputs[i].value = numbers[i];
      }
      const lastFilledIndex = Math.min(numbers.length, otpInputs.length) - 1;
      if (lastFilledIndex >= 0) {
        otpInputs[lastFilledIndex].focus();
      }
    });
  });
}

// Hàm đếm ngược
function startCountdown(elementId, seconds, callback) {
  const element = document.getElementById(elementId);
  let timeLeft = seconds;

  const updateCountdown = () => {
    const minutes = Math.floor(timeLeft / 60);
    const secs = timeLeft % 60;
    element.textContent = `Gửi lại sau ${minutes}:${secs
      .toString()
      .padStart(2, "0")}`;

    if (timeLeft <= 0) {
      element.textContent = "";
      if (callback) callback();
      return;
    }

    timeLeft--;
    setTimeout(updateCountdown, 1000);
  };

  updateCountdown();
}

// Event listeners
document.addEventListener("DOMContentLoaded", function () {
  // // Setup OTP inputs
  // setupResetOtpInputs();

  // Submit form OTP
  document
    .getElementById("resetOtpForm")
    .addEventListener("submit", async (e) => {
      e.preventDefault();
      await handleResetOtp();
    });

  // Resend OTP
  document
    .getElementById("resendResetOtp")
    .addEventListener("click", async (e) => {
      e.preventDefault();
      await handleResendResetOtp();
    });

  // Back to forgot password
  document
    .getElementById("backToForgotPassword")
    .addEventListener("click", (e) => {
      e.preventDefault();
      switchTab("forgotPasswordTab");
    });
});

async function handleNewPassword() {
  const form = document.getElementById("newPasswordForm");
  const newPassword = form.querySelector("#newPassword").value.trim();
  const confirmPassword = form.querySelector("#confirmPassword").value.trim();
  const submitBtn = form.querySelector(".submit-btn");

  // Kiểm tra mật khẩu
  if (!newPassword || !confirmPassword) {
    showMessage(
      "newPasswordMessage",
      "Vui lòng nhập đầy đủ mật khẩu và xác nhận mật khẩu.",
      "error"
    );
    if (!newPassword) shakeElement("newPassword");
    if (!confirmPassword) shakeElement("confirmPassword");
    return;
  }

  // Kiểm tra độ dài mật khẩu (ví dụ: tối thiểu 8 ký tự)
  if (newPassword.length < 8) {
    showMessage(
      "newPasswordMessage",
      "Mật khẩu phải có ít nhất 8 ký tự.",
      "error"
    );
    shakeElement("newPassword");
    return;
  }

  if (newPassword.includes(" ")) {
    showMessage(
      "newPasswordMessage",
      "Mật khẩu không được chứa khoảng trắng.",
      "error"
    );
    shakeElement("newPassword");
    return;
  }

  // Kiểm tra mật khẩu khớp nhau
  if (newPassword !== confirmPassword) {
    showMessage("newPasswordMessage", "Mật khẩu xác nhận không khớp.", "error");
    shakeElement("confirmPassword");
    return;
  }

  submitBtn.disabled = true;
  submitBtn.innerHTML = '<span class="spinner"></span> Đang xử lý...';

  try {
    const formData = new FormData();
    formData.append("reset_password", "1");
    formData.append("new_password", newPassword);

    // Lấy email và reset_token từ sessionStorage
    const email = sessionStorage.getItem("reset_email");
    const reset_token = sessionStorage.getItem("reset_token");
    if (!email || !reset_token) {
      showMessage(
        "newPasswordMessage",
        "Không tìm thấy thông tin email hoặc token. Vui lòng thử lại từ đầu.",
        "error"
      );
      return;
    }
    formData.append("email", email);
    formData.append("reset_token", reset_token);

    const response = await fetch("/libertylaocai/user/submit", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      body: formData,
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const text = await response.text();
    console.log("Raw response:", text);

    let data;
    const cleanedText = text.trim().replace(/^\uFEFF/, "");

    if (!cleanedText.startsWith("{") && !cleanedText.startsWith("[")) {
      throw new Error("Server returned non-JSON response");
    }

    try {
      data = JSON.parse(cleanedText);
    } catch (parseError) {
      showMessage(
        "newPasswordMessage",
        "Lỗi định dạng phản hồi từ server.",
        "error"
      );
      return;
    }

    if (data.success) {
      showMessage(
        "newPasswordMessage",
        data.message || "Đặt lại mật khẩu thành công!",
        "success"
      );
      // Xóa sessionStorage
      sessionStorage.removeItem("reset_email");
      sessionStorage.removeItem("reset_token");
      // Chuyển về tab đăng nhập sau 1 giây
      setTimeout(() => {
        switchTab("loginTab");
        showMessage("loginMessage", "Đăng nhập để tiếp tục", "success");
      }, 1000);
    } else {
      showMessage(
        "newPasswordMessage",
        data.message || "Đặt lại mật khẩu thất bại.",
        "error"
      );
      shakeElement("newPassword");
      shakeElement("confirmPassword");
    }
  } catch (error) {
    showMessage(
      "newPasswordMessage",
      "Không thể kết nối tới server. Vui lòng thử lại.",
      "error"
    );
    console.error("Reset password error:", error);
  } finally {
    submitBtn.disabled = false;
    submitBtn.innerHTML = "ĐẶT LẠI MẬT KHẨU";
  }
}
