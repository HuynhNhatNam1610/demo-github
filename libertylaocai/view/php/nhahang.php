<?php
require_once "session.php";
require_once "../../model/UserModel.php";

$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;
if (!empty($_SESSION['restaurant_images'])) {
    $getRestaurantImages = $_SESSION['restaurant_images'];
}

// Lấy danh sách món ăn ban đầu (trang 1, 9 món)
$menuData = getAllMenuImages($languageId, 1, 1, 9);
$getAllMenuImages = $menuData['menuImages'];
$totalPages = $menuData['totalPages'];
$currentPage = $menuData['currentPage'];

// Lấy thông tin khách sạn 
$informationHotel = getHotelInfoWithLanguage($languageId);

$getAmThucNgonNgu = getAmThucNgonNgu($languageId, 1);

// Gọi hàm để lấy danh sách khu vực
$diningAreas = getDiningAreas($languageId);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhà hàng Liberty Hotel & Events</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/libertylaocai/view/css/nhahang.css">
</head>

<body>
    <?php include "header.php"; ?>
    <div class="nhahang-container">
        <!-- Banner Slider -->
        <section class="banner-slider">
            <div class="slider-container">
                <?php
                $images = getRestaurantImages(3);
                $first = true;
                foreach ($images as $image) {
                    $activeClass = $first ? 'active' : '';
                    echo '<div class="slide ' . $activeClass . '">';
                    echo '<img src="/libertylaocai/view/img/' . htmlspecialchars($image['image']) . '" alt="Nhà hàng Liberty">';
                    echo '</div>';
                    $first = false;
                }
                ?>
                <button class="slider-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
                <button class="slider-btn next-btn"><i class="fas fa-chevron-right"></i></button>
                <div class="slider-dots">
                    <?php
                    $first = true;
                    foreach ($images as $index => $image) {
                        $activeDot = $first ? 'active' : '';
                        echo '<span class="dot ' . $activeDot . '"></span>';
                        $first = false;
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Tab Navigation -->
        <section class="tab-navigation">
            <div class="container">
                <button class="tab-btn active" data-tab="description">Thông tin nhà hàng</button>
                <button class="tab-btn" data-tab="menu">Thực đơn</button>
                <button class="tab-btn" data-tab="booking">Đặt bàn</button>
            </div>
        </section>
        <div class="container">
            <!-- Main Content -->
            <section class="main-content">
                <!-- Tab: Description -->
                <div class="tab-content active" id="description">
                    <div class="room-info-grid">
                        <div class="room-details">
                            <?php if (!empty($informationHotel)): ?>
                                <?php foreach ($informationHotel as $info): ?>
                                    <h1><?php echo $info['name'] ?></h1>
                                    <div class="restaurant-specs">
                                        <div class="spec-item"><i class="fas fa-users"></i> <?php echo $languageId == 1 ? 'Sức chứa: 700 khách' : 'Capacity: 700 guests'; ?></div>
                                        <div class="spec-item"><i class="fas fa-building"></i> <?php echo $languageId == 1 ? 'Vị trí: Tầng 1, Tầng 2' : 'Location: 1st Floor, 2nd Floor'; ?></div>
                                        <div class="spec-item"><i class="fas fa-door-closed"></i> <?php echo $languageId == 1 ? '3 phòng ăn riêng biệt' : '3 separate dining rooms'; ?></div>
                                    </div>
                                    <div class="included-services">
                                        <?php if (!empty($getAmThucNgonNgu)): ?>
                                            <?= $getAmThucNgonNgu['description'] ?>
                                        <?php endif; ?>
                                    </div>
                        </div>
                        <div class="booking-card">
                            <h3>Thông tin liên hệ</h3>
                            <div class="info-list">
                                <div class="contact-item">
                                    <i class="fas fa-phone-alt"></i>
                                    <div>
                                        <p><?= $info['phone'] ?></p>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <div>
                                        <p><?= $info['email'] ?></p>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <p>7:00 - 23:00 <?php echo $languageId == 1 ? 'hàng ngày' : 'daily'; ?></p>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div>
                                        <p><?= $info['address'] ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <button class="book-now-btn" onclick="switchTab('booking')">Đặt bàn ngay</button>
                        </div>
                    </div>
                    <div class="reviews-section">
                        <div class="reviews-header">
                            <h3><?php echo $languageId == 1 ? 'Đánh giá từ khách hàng' : 'Customer Reviews'; ?></h3>
                            <div class="overall-rating">
                                <span class="rating-score">0.0</span>
                                <div class="rating-stars"></div>
                                <span class="rating-count">(0 <?php echo $languageId == 1 ? 'đánh giá' : 'reviews'; ?>)</span>
                            </div>
                            <div class="rating-breakdown"></div>
                        </div>
                        <div class="reviews-list"></div>
                        <div class="pagination-controls" style="display: none;">
                            <button class="show-more-reviews"><i class="fas fa-chevron-down"></i> <?php echo $languageId == 1 ? 'Xem thêm đánh giá' : 'Show more reviews'; ?></button>
                            <div class="pagination-buttons" style="display: flex; gap: 10px; justify-content: center; margin-top: 20px;"></div>
                        </div>
                        <div class="write-review-section">
                            <button class="write-review-btn" onclick="toggleReviewForm()"><i class="fas fa-pen"></i> <?php echo $languageId == 1 ? 'Viết đánh giá của bạn' : 'Write your review'; ?></button>
                            <div class="review-form-container" id="reviewForm" style="display: none;">
                                <h4><?php echo $languageId == 1 ? 'Chia sẻ trải nghiệm của bạn' : 'Share your experience'; ?></h4>
                                <form class="review-form" onsubmit="submitReview(event)">
                                    <div class="rating-input">
                                        <label><?php echo $languageId == 1 ? 'Đánh giá của bạn:' : 'Your rating:'; ?></label>
                                        <div class="star-rating">
                                            <input type="radio" name="rating" value="5" id="star5"><label for="star5" class="star"><i class="fas fa-star"></i></label>
                                            <input type="radio" name="rating" value="4" id="star4"><label for="star4" class="star"><i class="fas fa-star"></i></label>
                                            <input type="radio" name="rating" value="3" id="star3"><label for="star3" class="star"><i class="fas fa-star"></i></label>
                                            <input type="radio" name="rating" value="2" id="star2"><label for="star2" class="star"><i class="fas fa-star"></i></label>
                                            <input type="radio" name="rating" value="1" id="star1"><label for="star1" class="star"><i class="fas fa-star"></i></label>
                                            <span class="rating-text"><?php echo $languageId == 1 ? 'Chọn số sao' : 'Select stars'; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo $languageId == 1 ? 'Họ và tên:' : 'Full name:'; ?></label>
                                        <input type="text" name="reviewer-name" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Email:</label>
                                        <input type="email" name="reviewer-email" required>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo $languageId == 1 ? 'Nội dung đánh giá:' : 'Review content:'; ?></label>
                                        <textarea name="review-content" required></textarea>
                                    </div>
                                    <div class="form-actions">
                                        <button type="button" class="cancel-btn" onclick="toggleReviewForm()"><?php echo $languageId == 1 ? 'Hủy' : 'Cancel'; ?></button>
                                        <button type="submit" class="submit-review-btn"><i class="fas fa-paper-plane"></i> <?php echo $languageId == 1 ? 'Gửi đánh giá' : 'Submit review'; ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Menu -->
                <div class="tab-content" id="menu">
                    <div class="service-featured">
                        <h1><?php echo $languageId == 1 ? 'Thực đơn' : 'Menu'; ?></h1>
                        <div class="service-featured-list" id="menu-list">
                            <?php foreach ($getAllMenuImages as $dish) { ?>
                                <div class="service-featured-detail">
                                    <div class="featured-img">
                                        <img src="/libertylaocai/view/img/<?= htmlspecialchars($dish['image']); ?>" alt="<?= htmlspecialchars($dish['title']); ?>">
                                    </div>
                                    <div class="featured-content">
                                        <div class="featured-title">
                                            <?= htmlspecialchars($dish['title']); ?>
                                        </div>
                                        <div class="featured-description">
                                            <?= htmlspecialchars($dish['description'] ?? ''); ?>
                                        </div>
                                        <div class="price-container">
                                            <div class="price">
                                                <?= number_format($dish['price'], 0, ',', '.'); ?> VNĐ
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="pagination-controls" id="menu-pagination" style="display: <?php echo $totalPages <= 1 ? 'none' : 'block'; ?>;">
                            <div class="pagination-buttons" id="menu-pagination-buttons" style="display: flex; gap: 10px; justify-content: center; margin-top: 20px;">
                                <?php
                                $maxButtons = 5;
                                $startPage = max(1, $currentPage - floor(($maxButtons - 1) / 2));
                                $endPage = min($totalPages, $startPage + $maxButtons - 1);

                                if ($startPage > 1) {
                                    echo '<button class="pagination-btn" data-page="1">1</button>';
                                    if ($startPage > 2) {
                                        echo '<span class="pagination-ellipsis">...</span>';
                                    }
                                }

                                for ($i = $startPage; $i <= $endPage; $i++) {
                                    $activeClass = $i == $currentPage ? 'active' : '';
                                    echo '<button class="pagination-btn ' . $activeClass . '" data-page="' . $i . '">' . $i . '</button>';
                                }

                                if ($endPage < $totalPages) {
                                    if ($endPage < $totalPages - 1) {
                                        echo '<span class="pagination-ellipsis">...</span>';
                                    }
                                    echo '<button class="pagination-btn" data-page="' . $totalPages . '">' . $totalPages . '</button>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Booking -->
                <div class="tab-content" id="booking">
                    <div class="booking-form-container">
                        <h2><?php echo $languageId == 1 ? 'Đặt Bàn Nhà Hàng' : 'Restaurant Booking'; ?></h2>
                        <form class="booking-form" id="bookingForm">
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
                                        <select id="diningArea" name="diningArea" required>
                                            <option value=""><?php echo $languageId == 1 ? 'Chọn khu vực' : 'Select area'; ?></option>
                                            <?php
                                            if (!empty($diningAreas)) {
                                                foreach ($diningAreas as $area) {
                                                    echo '<option value="' . htmlspecialchars($area['label']) . '">' . htmlspecialchars($area['label']) . '</option>';
                                                }
                                            }
                                            ?>
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

                            <button type="submit" class="submit-booking-btn"><i class="fas fa-calendar-check"></i> <?php echo $languageId == 1 ? 'Xác Nhận Đặt Bàn' : 'Confirm Booking'; ?></button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
     <!-- Overlay loading toàn màn hình -->
    <div id="fullScreenLoader" class="full-screen-loader" style="display: none;">
        <div class="loader-content">
            <i class="fas fa-spinner fa-spin fa-3x"></i>
            <p><?php echo $languageId == 1 ? 'Đang xử lý yêu cầu...' : 'Processing request...'; ?></p>
        </div>
    </div>
    <script src="/libertylaocai/view/js/nhahang.js"></script>
    <script>
        const languageId = <?php echo json_encode($languageId); ?>;
        let totalMenuPages = <?php echo json_encode($totalPages); ?>;
    </script>
    <?php include "footer.php"; ?>
</body>

</html>