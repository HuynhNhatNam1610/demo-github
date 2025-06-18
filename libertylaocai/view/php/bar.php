<?php
require_once "session.php";
require_once "../../model/UserModel.php";

$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;
$getAmThucNgonNgu = getAmThucNgonNgu($languageId, 2);

// Define multilingual texts
$texts = [
    1 => [ // Vietnamese
        'reviews_title' => 'Đánh giá từ khách hàng',
        'no_reviews' => 'Chưa có đánh giá nào cho Sky Bar này. Hãy là người đầu tiên đánh giá!',
        'write_review' => 'Viết đánh giá của bạn',
        'share_experience' => 'Chia sẻ trải nghiệm của bạn',
        'rating_label' => 'Đánh giá của bạn:',
        'select_stars' => 'Chọn số sao',
        'name_label' => 'Họ và tên:',
        'email_label' => 'Email:',
        'review_content_label' => 'Nội dung đánh giá:',
        'review_content_placeholder' => 'Chia sẻ trải nghiệm của bạn về Sky Bar này...',
        'cancel' => 'Hủy',
        'submit_review' => 'Gửi đánh giá'
    ],
    2 => [ // English
        'reviews_title' => 'Customer Reviews',
        'no_reviews' => 'No reviews yet for this Sky Bar. Be the first to review!',
        'write_review' => 'Write Your Review',
        'share_experience' => 'Share Your Experience',
        'rating_label' => 'Your Rating:',
        'select_stars' => 'Select stars',
        'name_label' => 'Full Name:',
        'email_label' => 'Email:',
        'review_content_label' => 'Review Content:',
        'review_content_placeholder' => 'Share your experience about this Sky Bar...',
        'cancel' => 'Cancel',
        'submit_review' => 'Submit Review'
    ]
];

$text = $texts[$languageId];
?>

<!DOCTYPE html>
<html lang="<?php echo $languageId == 1 ? 'vi' : 'en'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $languageId == 1 ? 'Sky Bar Tầng 7 - Khách Sạn Liberty Lào Cai' : 'Sky Bar 7th Floor - Liberty Hotel Lao Cai'; ?></title>
    <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
    <meta name="description" content="<?php echo $languageId == 1 ? 'Thư giãn tại Sky Bar tầng 7 Liberty Lào Cai với tầm nhìn toàn cảnh, đồ uống đa dạng và nhạc sống.' : 'Relax at Sky Bar on the 7th floor of Liberty Hotel Lao Cai with panoramic views, diverse drinks, and live music.'; ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/libertylaocai/view/css/bar.css">
</head>


<body>
    <?php include "header.php"; ?>
    <div class="big-container">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="particles" id="particles"></div>
            <div class="hero-content">
                <h1 class="hero-title">SKY BAR</h1>
                <p class="hero-subtitle"><?php echo $languageId == 1 ? 'Tầng 7 - Liberty Hotel' : '7th Floor - Liberty Hotel'; ?></p>
                <p class="hero-location"><?php echo $languageId == 1 ? 'View toàn cảnh thành phố Lào Cai' : 'Panoramic view of Lao Cai city'; ?></p>
            </div>
        </section>

        <!-- Navigation Tabs -->
        <nav class="nav-tabs">
            <div class="nav-container">
                <button class="nav-btn active" data-tab="about"><?php echo $languageId == 1 ? 'Giới thiệu' : 'Introduce'; ?></button>
                <button class="nav-btn" data-tab="menu"><?php echo $languageId == 1 ? 'Thực đơn' : 'Menu'; ?></button>
                <button class="nav-btn" data-tab="reservation"><?php echo $languageId == 1 ? 'Đặt bàn' : 'Book now'; ?></button>
            </div>
        </nav>

        <!-- About Tab -->
        <div id="about" class="tab-content active">
            <div class="container">
                <div class="about-grid">
                    <div class="about-text">
                        <?php if (!empty($getAmThucNgonNgu)): ?>
                            <?= $getAmThucNgonNgu['description'] ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="about-features">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mountain"></i>
                        </div>
                        <h3 class="feature-title"><?php echo $languageId == 1 ? 'View toàn thành phố' : 'View of the entire city'; ?></h3>
                        <p><?php echo $languageId == 1 ? 'Tầm nhìn 360° ra toàn cảnh thành phố Lào Cai và dãy núi Hoàng Liên Sơn hùng vĩ' : '360° panoramic view of Lao Cai city and the majestic Hoang Lien Son mountain range'; ?></p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-cocktail"></i>
                        </div>
                        <h3 class="feature-title"><?php echo $languageId == 1 ? 'Đồ uống đa dạng' : 'Premium drinks'; ?></h3>
                        <p><?php echo $languageId == 1 ? 'Menu cocktail độc đáo và đa dạng các loại đồ uống giải khát' : 'Unique cocktail menu and a variety of refreshing drinks'; ?></p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-music"></i>
                        </div>
                        <h3 class="feature-title"><?php echo $languageId == 1 ? 'Band nhạc sống' : 'Live music band'; ?></h3>
                        <p><?php echo $languageId == 1 ? 'Có tổ chức Band nhạc hàng tuần vào tối thứ 3 & thứ 7' : 'Band performances are held weekly on Tuesday & Saturday evenings'; ?></p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3 class="feature-title"><?php echo $languageId == 1 ? 'Ẩm thực Âu - Á' : 'European - Asian cuisine'; ?></h3>
                        <p><?php echo $languageId == 1 ? 'Thực đơn phong phú với các món ăn từ châu Âu và châu Á' : 'Rich menu with dishes from Europe and Asia'; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Tab -->
        <div id="menu" class="tab-content">
            <div class="container">
                <div class="menu-categories">
                    <button class="category-btn active" data-category="cocktails"><?php echo $languageId == 1 ? 'Đồ uống' : 'Beverage'; ?></button>
                    <button class="category-btn" data-category="main"><?php echo $languageId == 1 ? 'Đồ ăn' : 'Food'; ?></button>
                </div>

                <div class="menu-grid" id="menuItems"></div>
                <div class="menu-pagination" id="menuPagination">
                    <div class="menu-pagination-buttons" data-category="cocktails"></div>
                    <div class="menu-pagination-buttons" data-category="main"></div>
                </div>
            </div>
        </div>

        <!-- Reservation Tab -->
        <div id="reservation" class="tab-content">
            <div class="container">
                <div class="booking-form-container">
                    <h2><?php echo $languageId == 1 ? 'Đặt Bàn Sky Bar' : 'Sky Bar Booking'; ?></h2>
                    <form class="booking-form" id="reservationForm">
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

                        <button type="submit" class="submit-booking-btn"><i class="fas fa-calendar-check"></i> <?php echo $languageId == 1 ? 'Xác Nhận Đặt Bàn' : 'Confirm Booking'; ?></button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="reviews-section" id="reviews">
            <div class="container">
                <div class="reviews-header">
                    <h3><?php echo $text['reviews_title']; ?></h3>
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
                    <button class="write-review-btn" onclick="toggleReviewForm()"><i class="fas fa-pen"></i> <?php echo $text['write_review']; ?></button>
                    <div class="review-form-container" id="reviewForm" style="display: none;">
                        <h4><?php echo $text['share_experience']; ?></h4>
                        <form class="review-form" onsubmit="submitReview(event)">
                            <div class="rating-input">
                                <label><?php echo $text['rating_label']; ?></label>
                                <div class="star-rating">
                                    <input type="radio" name="rating" value="5" id="star5"><label for="star5" class="star"><i class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" value="4" id="star4"><label for="star4" class="star"><i class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" value="3" id="star3"><label for="star3" class="star"><i class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" value="2" id="star2"><label for="star2" class="star"><i class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" value="1" id="star1"><label for="star1" class="star"><i class="fas fa-star"></i></label>
                                    <span class="rating-text"><?php echo $text['select_stars']; ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?php echo $text['name_label']; ?></label>
                                <input type="text" name="reviewer-name" required>
                            </div>
                            <div class="form-group">
                                <label><?php echo $text['email_label']; ?></label>
                                <input type="email" name="reviewer-email" required>
                            </div>
                            <div class="form-group">
                                <label><?php echo $text['review_content_label']; ?></label>
                                <textarea name="review-content" placeholder="<?php echo $text['review_content_placeholder']; ?>" required></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="cancel-btn" onclick="toggleReviewForm()"><?php echo $text['cancel']; ?></button>
                                <button type="submit" class="submit-review-btn"><i class="fas fa-paper-plane"></i> <?php echo $text['submit_review']; ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="quick-booking-btn" onclick="quickBookTable()">
        <i class="fas fa-calendar-plus"></i> <?php echo $languageId == 1 ? 'Đặt Ngay' : 'Book Now'; ?>
    </button>
    <!-- Overlay loading toàn màn hình -->
    <div id="fullScreenLoader" class="full-screen-loader" style="display: none;">
        <div class="loader-content">
            <i class="fas fa-spinner fa-spin fa-3x"></i>
            <p><?php echo $languageId == 1 ? 'Đang xử lý yêu cầu...' : 'Processing request...'; ?></p>
        </div>
    </div>
    <?php include "footer.php"; ?>
    <script>
        const languageId = <?php echo json_encode($languageId); ?>;
        const texts = <?php echo json_encode($texts[$languageId]); ?>;
    </script>
    <script src="/libertylaocai/view/js/bar.js"></script>
</body>

</html>