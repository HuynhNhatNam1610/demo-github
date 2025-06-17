<?php
require_once "session.php";
require_once "../../model/UserModel.php";
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;
if (!empty($_SESSION['head_banner'])) {
    $getSelectedBanner = $_SESSION['head_banner'];
}

// Lấy danh sách dịch vụ ẩm thực
$foodServices = getFoodServices($languageId);

// Lấy danh sách món ăn đặc sắc từ cơ sở dữ liệu
$featuredDishes = getFeaturedDishes($languageId);

// Lấy mô tả cho hero-content-nhahang-bar
$descriptionHeroContent = getSelectedDescription($languageId, 'hero-content-nhahang-bar');

// Gọi hàm để lấy danh sách khu vực
$diningAreas = getDiningAreas($languageId);

// Gọi hàm để lấy 3 ảnh ngẫu nhiên
$heroImages = getRandomHeroImages();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhà Hàng & Bar - Liberty Hotel</title>
    <link rel="stylesheet" href="/libertylaocai/view/css/nhahang&bar.css">
</head>

<body>
    <?php include "header.php" ?>
    <div class="service-container">
        <div class="service-banner">
            <img src="/libertylaocai/view/img/<?= $getSelectedBanner['image']; ?>" alt="Banner Image" class="banner-image">
            <h1><?php echo $languageId == 1 ? 'NHÀ HÀNG & BAR' : 'RESTAURANTS & BAR'; ?></h1>
        </div>

        <div class="list-service">
            <h1><?php echo $languageId == 1 ? 'Dịch vụ ẩm thực' : 'Food service'; ?></h1>
            <div class="type-service">
                <?php foreach ($foodServices as $service): ?>
                    <div class="type-of-service">
                        <div class="service-img">
                            <?php if ($service['image']): ?>
                                <img src="/libertylaocai/view/img/<?= htmlspecialchars($service['image']); ?>" alt="<?= htmlspecialchars($service['title']); ?>">
                            <?php else: ?>
                                <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=600&q=80" alt="Default Image">
                            <?php endif; ?>
                        </div>
                        <div class="service-title">
                            <?= htmlspecialchars($service['title']); ?>
                        </div>
                        <div class="service-description">
                            <?= $service['content']; ?>
                        </div>
                        <div class="service-more" data-form-id="form-<?php echo $service['code']; ?>">
                            <?php echo $languageId == 1 ? 'Chi tiết' : 'More'; ?>
                        </div>
                        <form id="form-<?php echo $service['code']; ?>" action="/libertylaocai/user/submit" method="POST" style="display: inline;">
                            <input type="hidden" name="cuisine_code" value="<?php echo htmlspecialchars($service['code']); ?>">
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


        <div class="service-featured">
            <h1><?php echo $languageId == 1 ? 'Thực đơn nổi bật' : 'Featured Menu'; ?></h1>
            <div class="service-featured-list">
                <?php
                foreach ($featuredDishes as $dish) {
                    $mainPrice = $dish['price'];
                ?>
                    <div class="service-featured-detail">
                        <div class="featured-img">
                            <img src="/libertylaocai/view/img/<?= htmlspecialchars($dish['image']); ?>" alt="<?= htmlspecialchars($dish['title']); ?>">
                        </div>
                        <div class="featured-content">
                            <div class="featured-title">
                                <?= htmlspecialchars($dish['title']); ?>
                            </div>
                            <div class="featured-description">
                                <?= $dish['description'] ?? ''; ?>
                            </div>
                            <div class="price-container">
                                <div class="price">
                                    <?= number_format($mainPrice, 0, ',', '.'); ?> VNĐ
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>


    <div class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <?php if (!empty($descriptionHeroContent)): ?>
                    <?php echo $descriptionHeroContent['content']; ?>
                <?php endif; ?>
            </div>

            <div class="hero-images">
                <?php
                // Hiển thị 3 ảnh
                foreach ($heroImages as $index => $image) {
                    $altText = "Liberty Image " . ($index + 1);
                ?>
                    <div class="hero-image">
                        <img src="/libertylaocai/view/img/<?= htmlspecialchars($image); ?>" alt="<?= htmlspecialchars($altText); ?>">
                    </div>
                <?php
                }
                ?>
            </div>
        </div>

    </div>

    <button class="quick-booking-btn" onclick="openModal()">
        🍽️ <?php echo $languageId == 1 ? 'Đặt Bàn Nhanh' : 'Book Now'; ?>
    </button>

    <!-- Modal đặt bàn -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><?php echo $languageId == 1 ? 'Đặt Bàn Nhà Hàng' : 'Restaurant Booking'; ?></h2>
                <span class="close">×</span>
            </div>
            <div class="modal-body">
                <form id="bookingForm">
                    <div class="form-section">
                        <h3><?php echo $languageId == 1 ? 'Thông Tin Liên Hệ' : 'Contact Information'; ?></h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="customerName"><?php echo $languageId == 1 ? 'Họ và tên' : 'Full Name'; ?> <span class="required">*</span></label>
                                <input type="text" id="customerName" name="customerName" placeholder="<?php echo $languageId == 1 ? 'Nhập họ và tên' : 'Enter full name'; ?>" required>
                                <span class="error-message" id="customerName-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="phoneNumber"><?php echo $languageId == 1 ? 'Số điện thoại' : 'Phone Number'; ?> <span class="required">*</span></label>
                                <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="<?php echo $languageId == 1 ? 'Nhập số điện thoại' : 'Enter phone number'; ?>" required>
                                <span class="error-message" id="phoneNumber-error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email"><?php echo $languageId == 1 ? 'Email' : 'Email'; ?> <span class="required">*</span></label>
                            <input type="email" id="email" name="email" placeholder="<?php echo $languageId == 1 ? 'Nhập email' : 'Enter email'; ?>" required>
                            <span class="error-message" id="email-error"></span>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><?php echo $languageId == 1 ? 'Chi Tiết Đặt Bàn' : 'Booking Details'; ?></h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="bookingDate"><?php echo $languageId == 1 ? 'Ngày đặt bàn' : 'Booking Date'; ?> <span class="required">*</span></label>
                                <input type="date" id="bookingDate" name="bookingDate" required>
                                <span class="error-message" id="bookingDate-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="startTime"><?php echo $languageId == 1 ? 'Giờ đặt bàn' : 'Booking Time'; ?> <span class="required">*</span></label>
                                <input type="time" id="startTime" name="startTime" required>
                                <span class="error-message" id="startTime-error"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="guestCount"><?php echo $languageId == 1 ? 'Số lượng khách' : 'Number of Guests'; ?> <span class="required">*</span></label>
                                <input type="number" id="guestCount" name="guestCount" placeholder="<?php echo $languageId == 1 ? 'Nhập số lượng khách dự kiến' : 'Enter estimated number of guests'; ?>" min="1" required>
                                <span class="error-message" id="guestCount-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="diningArea"><?php echo $languageId == 1 ? 'Đặt bàn tại' : 'Book a table at'; ?> <span class="required">*</span></label>
                                <select id="diningArea" name="diningArea">
                                    <option value=""><?php echo $languageId == 1 ? 'Chọn khu vực' : 'Select area'; ?></option>
                                    <?php
                                    if (!empty($diningAreas)) {
                                        foreach ($diningAreas as $area) {
                                            echo '<option value="' . htmlspecialchars($area['label']) . '">' . htmlspecialchars($area['label']) . '</option>';
                                        }
                                    }
                                    ?>
                                    <option value="Sky Bar">Sky Bar</option>
                                </select>
                                <span class="error-message" id="diningArea-error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><?php echo $languageId == 1 ? 'Yêu Cầu Đặc Biệt' : 'Special Requests'; ?></h3>
                        <div class="form-group">
                            <label for="occasion"><?php echo $languageId == 1 ? 'Dịp đặc biệt' : 'Occasion'; ?></label>
                            <select id="occasion" name="occasion">
                                <option value=""><?php echo $languageId == 1 ? 'Chọn dịp (nếu có)' : 'Select occasion (if any)'; ?></option>
                                <option value="birthday"><?php echo $languageId == 1 ? 'Sinh nhật' : 'Birthday'; ?></option>
                                <option value="anniversary"><?php echo $languageId == 1 ? 'Kỷ niệm' : 'Anniversary'; ?></option>
                                <option value="proposal"><?php echo $languageId == 1 ? 'Cầu hôn' : 'Proposal'; ?></option>
                                <option value="business"><?php echo $languageId == 1 ? 'Gặp gỡ công việc' : 'Business Meeting'; ?></option>
                                <option value="celebration"><?php echo $languageId == 1 ? 'Ăn mừng' : 'Celebration'; ?></option>
                                <option value="other"><?php echo $languageId == 1 ? 'Khác' : 'Other'; ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="specialRequests"><?php echo $languageId == 1 ? 'Ghi chú thêm' : 'Additional Notes'; ?></label>
                            <textarea id="specialRequests" name="specialRequests" placeholder="<?php echo $languageId == 1 ? 'Ví dụ: dị ứng thực phẩm, yêu cầu trang trí, menu đặc biệt...' : 'E.g., food allergies, decoration requests, special menu...'; ?>"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeModal()"><?php echo $languageId == 1 ? 'Hủy' : 'Cancel'; ?></button>
                <button type="submit" class="btn btn-submit" form="bookingForm"><?php echo $languageId == 1 ? 'Xác Nhận Đặt Bàn' : 'Confirm Booking'; ?></button>
            </div>
        </div>
    </div>
     <!-- Overlay loading toàn màn hình -->
    <div id="fullScreenLoader" class="full-screen-loader" style="display: none;">
        <div class="loader-content">
            <i class="fas fa-spinner fa-spin fa-3x"></i>
            <p><?php echo $languageId == 1 ? 'Đang xử lý yêu cầu...' : 'Processing request...'; ?></p>
        </div>
    </div>

    <?php include "footer.php" ?>
    <script>
        const languageId = <?php echo json_encode($languageId); ?>;
    </script>
    <script src="/libertylaocai/view/js/nhahang&bar.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>

</html>