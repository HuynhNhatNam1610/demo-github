<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Quản Trị Khách Sạn</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/libertylaocai/view/css/login.css">
</head>

<body>
    <?php include "header.php"; ?>
    <div class="main-content">
        <div class="login-container">
            <div class="login-box">
                <!-- Tab Đăng nhập -->
                <div id="loginTab" class="tab-content active">
                    <h2 class="login-title">ĐĂNG NHẬP</h2>
                    <div id="loginMessage" class="message"></div>
                    <form id="loginForm" class="login-form">
                        <div class="form-group">
                            <input
                                type="text"
                                id="username"
                                name="username"
                                placeholder="Tài khoản"
                                class="form-input"
                                required>
                        </div>
                        <div class="form-group">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Mật khẩu"
                                class="form-input"
                                required>
                            <span class="password-toggle" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                        <button type="submit" class="login-btn">ĐĂNG NHẬP</button>
                        <div class="form-footer">
                            <a href="#" class="forgot-password" id="forgotPasswordLink">Quên mật khẩu?</a>
                        </div>
                    </form>
                </div>

                <!-- Tab OTP sau đăng nhập -->
                <div id="loginOtpTab" class="tab-content">
                    <h2 class="login-title">NHẬP MÃ OTP</h2>
                    <div id="loginOtpMessage" class="message"></div>
                    <div class="email-info">
                        <i class="bi bi-envelope-check"></i>
                        Mã OTP đã được gửi đến email: <strong id="loginOtpEmail"></strong>
                    </div>
                    <form id="loginOtpForm" class="login-form">
                        <div class="otp-container">
                            <div class="otp-inputs">
                                <input type="text" class="otp-input" maxlength="1" data-index="0">
                                <input type="text" class="otp-input" maxlength="1" data-index="1">
                                <input type="text" class="otp-input" maxlength="1" data-index="2">
                                <input type="text" class="otp-input" maxlength="1" data-index="3">
                                <input type="text" class="otp-input" maxlength="1" data-index="4">
                                <input type="text" class="otp-input" maxlength="1" data-index="5">
                            </div>
                            <div class="resend-otp">
                                <a href="#" class="resend-link" id="resendLoginOtp">
                                    <i class="bi bi-arrow-clockwise"></i> Gửi lại mã OTP
                                </a>
                                <div class="countdown" id="loginCountdown"></div>
                            </div>
                        </div>
                        <button type="submit" class="submit-btn">XÁC NHẬN OTP</button>
                        <div class="form-footer">
                            <a href="#" class="back-link" id="backToLogin">
                                <i class="bi bi-arrow-left"></i> Quay lại đăng nhập
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Tab Quên mật khẩu - Nhập email -->
                <div id="forgotPasswordTab" class="tab-content">
                    <h2 class="login-title">QUÊN MẬT KHẨU</h2>
                    <div id="forgotMessage" class="message"></div>
                    <!-- <div class="email-info">
                        <i class="bi bi-info-circle"></i>
                        Nhập email để nhận mã OTP đặt lại mật khẩu
                    </div> -->
                    <form id="forgotPasswordForm" class="login-form">
                        <div class="form-group">
                            <input
                                type="email"
                                id="resetEmail"
                                name="email"
                                placeholder="Nhập email để đặt lại mật khẩu"
                                class="form-input"
                                required>
                        </div>
                        <button type="submit" class="submit-btn">
                            <i class="bi bi-send"></i> GỬI MÃ OTP
                        </button>
                        <div class="form-footer">
                            <a href="#" class="back-link" id="backToLoginFromForgot">
                                <i class="bi bi-arrow-left"></i> Quay lại đăng nhập
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Tab OTP cho quên mật khẩu -->
                <div id="resetOtpTab" class="tab-content">
                    <h2 class="login-title">NHẬP MÃ OTP</h2>
                    <div id="resetOtpMessage" class="message"></div>
                    <form id="resetOtpForm" class="login-form">
                        <div class="otp-container">
                            <div class="otp-inputs">
                                <input type="text" id="otp-0" class="otp-input reset-otp" maxlength="1" data-index="0">
                                <input type="text" id="otp-1" class="otp-input reset-otp" maxlength="1" data-index="1">
                                <input type="text" id="otp-2" class="otp-input reset-otp" maxlength="1" data-index="2">
                                <input type="text" id="otp-3" class="otp-input reset-otp" maxlength="1" data-index="3">
                                <input type="text" id="otp-4" class="otp-input reset-otp" maxlength="1" data-index="4">
                                <input type="text" id="otp-5" class="otp-input reset-otp" maxlength="1" data-index="5">
                            </div>
                            <div class="resend-otp">
                                <a href="#" class="resend-link" id="resendResetOtp">
                                    <i class="bi bi-arrow-clockwise"></i> Gửi lại mã OTP
                                </a>
                                <div class="countdown" id="resetCountdown"></div>
                            </div>
                        </div>
                        <button type="submit" class="submit-btn">XÁC NHẬN OTP</button>
                        <div class="form-footer">
                            <a href="#" class="back-link" id="backToForgotPassword">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Tab Đặt lại mật khẩu -->
                <div id="newPasswordTab" class="tab-content">
                    <h2 class="login-title">ĐẶT LẠI MẬT KHẨU</h2>
                    <div id="newPasswordMessage" class="message"></div>
                    <form id="newPasswordForm" class="login-form">
                        <div class="form-group">
                            <input
                                type="password"
                                id="newPassword"
                                name="newPassword"
                                placeholder="Mật khẩu mới"
                                class="form-input"
                                required>
                            <span class="password-toggle" id="toggleNewPassword">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                        <div class="form-group">
                            <input
                                type="password"
                                id="confirmPassword"
                                name="confirmPassword"
                                placeholder="Xác nhận mật khẩu mới"
                                class="form-input"
                                required>
                            <span class="password-toggle" id="toggleConfirmPassword">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                        <button type="submit" class="submit-btn">
                            <i class="bi bi-shield-check"></i> ĐẶT LẠI MẬT KHẨU
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include "footer.php"; ?>
    <script src="/libertylaocai/view/js/login.js"></script>
</body>

</html>