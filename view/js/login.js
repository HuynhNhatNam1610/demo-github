// Get all form elements
const loginForm = document.getElementById("login-form");
const registerForm = document.getElementById("register-form");
const forgotPasswordForm = document.getElementById("forgot-password-form");

// Get all link elements
const registerLink = document.getElementById("register-link");
const loginLink = document.getElementById("login-link");
const forgotPasswordLink = document.getElementById("forgot-password-link");
const backToLoginLink = document.getElementById("back-to-login-link");

// Show register form
registerLink.addEventListener("click", function() {
  loginForm.classList.remove("active");
  registerForm.classList.add("active");
  forgotPasswordForm.classList.remove("active");
});

// Show login form
loginLink.addEventListener("click", function() {
  loginForm.classList.add("active");
  registerForm.classList.remove("active");
  forgotPasswordForm.classList.remove("active");
});

// Show forgot password form
forgotPasswordLink.addEventListener("click", function() {
  loginForm.classList.remove("active");
  registerForm.classList.remove("active");
  forgotPasswordForm.classList.add("active");
});

// Back to login from forgot password
backToLoginLink.addEventListener("click", function() {
  loginForm.classList.add("active");
  registerForm.classList.remove("active");
  forgotPasswordForm.classList.remove("active");
});