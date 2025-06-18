<?php
// Lấy dữ liệu cho view
$admin_id = 1;
require_once '../../controller/usercontroller.php';

$admin_data = getAdminData($conn, $admin_id);
$hotel_data = getHotelData($conn);
$lang_data = getLangData($conn);

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
<?php include "sidebar.php"; ?>
    <div class="container">
        <main class="main-content" id="mainContent">
            <div class="account-section">
                <header class="header">
                    <div class="header-content">
                        <div class="admin-info">
                            <span>Chào mừng, <?php echo htmlspecialchars($admin_data['username']); ?></span>
                            <button class="logout-btn" onclick="logout()">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </button>
                        </div>
                    </div>
                </header>
                
                <div class="account-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2><i class="fas fa-user-shield"></i> Quản Lý Tài Khoản Admin</h2>
                    <div>
                        <label class="twofa-toggle">
                            <input type="checkbox" id="twofaToggle" <?php echo $admin_data['active_2fa'] ? 'checked' : ''; ?>>
                            <span class="slider"></span>
                            <span class="twofa-label">Bật 2FA</span>
                        </label>
                    </div>
                </div>

                <div class="tab-nav">
                    <button class="tab-button active" data-tab="profileTab">Thông Tin Cá Nhân</button>
                    <button class="tab-button" data-tab="passwordTab">Đổi Mật Khẩu</button>
                    <button class="tab-button" data-tab="hotelTab">Thông Tin Khách Sạn</button>
                </div>

                <div id="profileTab" class="tab-content">
                    <div class="form-container">
                        <h3><i class="fas fa-user"></i> Thông Tin Cá Nhân</h3>
                        <form method="POST" class="form" id="profileForm">
                            <div class="form-group">
                                <label for="username"><i class="fas fa-user"></i> Tên đăng nhập</label>
                                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($admin_data['username']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin_data['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="mk_email"><i class="fas fa-lock"></i> Mật khẩu Email</label>
                                <div class="password-input">
                                    <input type="password" id="mk_email" name="mk_email" value="<?php echo htmlspecialchars($admin_data['mk_email']); ?>">
                                    <button type="button" class="toggle-password" onclick="togglePassword('mk_email')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
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
                        <form method="POST" class="form" id="passwordForm">
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

                <div id="hotelTab" class="tab-content" style="display: none;">
                    <div class="form-container">
                        <h3><i class="fas fa-hotel"></i> Thông Tin Khách Sạn</h3>
                        <form method="POST" class="form" id="hotelForm">
                            <div class="form-group">
                                <label for="name"><i class="fas fa-building"></i> Tên khách sạn</label>
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($hotel_data['name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="short_name"><i class="fas fa-building"></i> Tên viết tắt</label>
                                <input type="text" id="short_name" name="short_name" value="<?php echo htmlspecialchars($hotel_data['short_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone"><i class="fas fa-phone"></i> Số điện thoại</label>
                                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($hotel_data['phone']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($hotel_data['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="facebook"><i class="fab fa-facebook"></i> Facebook</label>
                                <input type="text" id="facebook" name="facebook" value="<?php echo htmlspecialchars($hotel_data['facebook']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="link_facebook"><i class="fab fa-facebook"></i> Link Facebook</label>
                                <input type="url" id="link_facebook" name="link_facebook" value="<?php echo htmlspecialchars($hotel_data['link_facebook']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="logo"><i class="fas fa-image"></i> Logo</label>
                                <input type="text" id="logo" name="logo" value="<?php echo htmlspecialchars($hotel_data['logo']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="position"><i class="fas fa-map-marker-alt"></i> Vị trí</label>
                                <input type="url" id="position" name="position" value="<?php echo htmlspecialchars($hotel_data['position']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="website"><i class="fas fa-globe"></i> Website</label>
                                <input type="text" id="website" name="website" value="<?php echo htmlspecialchars($hotel_data['website']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="link_website"><i class="fas fa-globe"></i> Link Website</label>
                                <input type="url" id="link_website" name="link_website" value="<?php echo htmlspecialchars($hotel_data['link_website']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="iframe_ytb"><i class="fab fa-youtube"></i> Iframe YouTube</label>
                                <textarea id="iframe_ytb" name="iframe_ytb" required><?php echo htmlspecialchars($hotel_data['iframe_ytb']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="address_vi"><i class="fas fa-map-marker-alt"></i> Địa chỉ (Tiếng Việt)</label>
                                <input type="text" id="address_vi" name="address_vi" value="<?php echo htmlspecialchars($lang_data[1]['address']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="description_vi"><i class="fas fa-info-circle"></i> Mô tả (Tiếng Việt)</label>
                                <textarea id="description_vi" name="description_vi" required><?php echo htmlspecialchars($lang_data[1]['description']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="address_en"><i class="fas fa-map-marker-alt"></i> Địa chỉ (Tiếng Anh)</label>
                                <input type="text" id="address_en" name="address_en" value="<?php echo htmlspecialchars($lang_data[2]['address']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="description_en"><i class="fas fa-info-circle"></i> Mô tả (Tiếng Anh)</label>
                                <textarea id="description_en" name="description_en" required><?php echo htmlspecialchars($lang_data[2]['description']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="hotel_password"><i class="fas fa-lock"></i> Mật khẩu xác nhận</label>
                                <div class="password-input">
                                    <input type="password" id="hotel_password" name="hotel_password" required>
                                    <button type="button" class="toggle-password" onclick="togglePassword('hotel_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" name="update_hotel" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật thông tin khách sạn
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="/libertylaocai/view/js/taikhoan.js"></script>
</body>
</html>