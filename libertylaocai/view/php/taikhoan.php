<?php
session_start();
require_once '../../model/config/connect.php';

// Kiểm tra đăng nhập (tạm thời bỏ qua session check)
$admin_id = 1; 

// Xử lý cập nhật thông tin cá nhân
if (isset($_POST['update_profile'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $position = trim($_POST['position']);
    $confirm_password = $_POST['profile_password'];
    
    // Lấy thông tin hiện tại để verify password
    $check_query = "SELECT password FROM taikhoan WHERE id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_admin = $result->fetch_assoc();
    
    if ($current_admin['password'] === $confirm_password) {
        // Cập nhật thông tin
        $update_query = "UPDATE taikhoan SET username = ?, email = ?, phone = ?, position = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssi", $username, $email, $phone, $position, $admin_id);
        
        if ($stmt->execute()) {
            $success_message = "Thông tin đã được cập nhật thành công!";
        } else {
            $error_message = "Lỗi khi cập nhật thông tin!";
        }
    } else {
        $error_message = "Mật khẩu xác nhận không đúng!";
    }
}

// Xử lý đổi mật khẩu
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Lấy mật khẩu hiện tại
    $check_query = "SELECT password FROM taikhoan WHERE id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_admin = $result->fetch_assoc();
    
    if ($current_admin['password'] === $current_password) {
        if ($new_password === $confirm_password) {
            if (strlen($new_password) >= 6) {
                // Cập nhật mật khẩu mới
                $update_query = "UPDATE taikhoan SET password = ? WHERE id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("si", $new_password, $admin_id);
                
                if ($stmt->execute()) {
                    $success_message = "Mật khẩu đã được thay đổi thành công!";
                } else {
                    $error_message = "Lỗi khi thay đổi mật khẩu!";
                }
            } else {
                $error_message = "Mật khẩu mới phải có ít nhất 6 ký tự!";
            }
        } else {
            $error_message = "Xác nhận mật khẩu không khớp!";
        }
    } else {
        $error_message = "Mật khẩu hiện tại không đúng!";
    }
}

// Lấy thông tin admin hiện tại
$query = "SELECT * FROM taikhoan WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin_data = $result->fetch_assoc();

if (!$admin_data) {
    die("Không tìm thấy thông tin admin!");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài Khoản Admin - Liberty Lào Cai Hotel</title>
    <link rel="stylesheet" href="/libertylaocai/view/css/taikhoan.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <header class="header">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-hotel"></i>
                    <h1>Liberty Lào Cai Hotel</h1>
                </div>
                <div class="admin-info">
                    <span>Chào mừng, <?php echo htmlspecialchars($admin_data['username']); ?></span>
                    <button class="logout-btn" onclick="logout()">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </button>
                </div>
            </div>
        </header>

        <main class="main-content">
            <div class="account-section">
                <div class="account-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2><i class="fas fa-user-shield"></i> Quản Lý Tài Khoản Admin</h2>
                    <!-- Tạm thời ẩn 2FA -->
                    <label class="twofa-toggle">
                        <input type="checkbox" id="twofaToggle" <?php echo $admin_data['active_2fa'] ? 'checked' : ''; ?>>
                        <span class="slider"></span>
                        <span class="twofa-label">Bật 2FA</span>
                    </label>
                </div>

                <?php if (isset($success_message)): ?>
                    <div class="message success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div class="message error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <div class="tab-nav">
                    <button class="tab-button active" data-tab="profileTab">Thông Tin Cá Nhân</button>
                    <button class="tab-button" data-tab="passwordTab">Đổi Mật Khẩu</button>
                </div>

                <div id="profileTab" class="tab-content">
                    <div class="form-container">
                        <h3><i class="fas fa-user"></i> Thông Tin Cá Nhân</h3>
                        <form method="POST" class="form">
                            <div class="form-group">
                                <label for="username"><i class="fas fa-user"></i> Tên đăng nhập</label>
                                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($admin_data['username']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin_data['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone"><i class="fas fa-phone"></i> Số điện thoại</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($admin_data['phone']); ?>" pattern="[0-9]{10,11}" title="Vui lòng nhập số điện thoại hợp lệ (10-11 số)">
                            </div>
                            <div class="form-group">
                                <label for="position"><i class="fas fa-id-badge"></i> Chức vụ</label>
                                <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($admin_data['position']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="profile_password"><i class="fas fa-lock"></i> Mật khẩu xác nhận</label>
                                <div class="password-input">
                                    <input type="password" id="profile_password" name="profile_password" required>
                                    <button type="button" class="toggle-password" onclick="togglePassword('profile_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật thông tin
                            </button>
                        </form>
                    </div>
                </div>

                <div id="passwordTab" class="tab-content" style="display: none;">
                    <div class="form-container">
                        <h3><i class="fas fa-key"></i> Đổi Mật Khẩu</h3>
                        <form method="POST" class="form">
                            <div class="form-group">
                                <label for="current_password"><i class="fas fa-lock"></i> Mật khẩu hiện tại</label>
                                <div class="password-input">
                                    <input type="password" id="current_password" name="current_password" required>
                                    <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="new_password"><i class="fas fa-lock"></i> Mật khẩu mới</label>
                                <div class="password-input">
                                    <input type="password" id="new_password" name="new_password" required minlength="6">
                                    <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength">
                                    <div class="strength-bar">
                                        <div id="strengthFill" class="strength-fill"></div>
                                    </div>
                                    <span id="strengthText" class="strength-text">Nhập mật khẩu</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password"><i class="fas fa-lock"></i> Xác nhận mật khẩu mới</label>
                                <div class="password-input">
                                    <input type="password" id="confirm_password" name="confirm_password" required>
                                    <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" name="change_password" class="btn btn-primary">
                                <i class="fas fa-key"></i> Đổi mật khẩu
                            </button>
                        </form>
                    </div>
                </div>

                <div class="stats-container">
                    <h3><i class="fas fa-chart-bar"></i> Thống Kê Tài Khoản</h3>
                    <div class="stats-grid">
                        <!-- <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-user"></i></div>
                            <div class="stat-info">
                                <h4>Tên đăng nhập</h4>
                                <p><?php echo htmlspecialchars($admin_data['username']); ?></p>
                            </div>
                        </div> -->
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-id-badge"></i></div>
                            <div class="stat-info">
                                <h4>Chức vụ</h4>
                                <p><?php echo htmlspecialchars($admin_data['position']); ?></p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-clock"></i></div>
                            <div class="stat-info">
                                <h4>Lần đăng nhập cuối</h4>
                                <p><?php 
                                    if ($admin_data['last_login']) {
                                        echo date('d/m/Y H:i:s', strtotime($admin_data['last_login']));
                                    } else {
                                        echo 'Chưa có dữ liệu';
                                    }
                                ?></p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-shield-alt"></i></div>
                            <div class="stat-info">
                                <h4>Trạng thái bảo mật</h4>
                                <p class="security-status active">Hoạt động</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
  <script src="/libertylaocai/view/js/taikhoan.js"></script>


</body>
</html>