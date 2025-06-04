<?php
require_once "session.php";
require_once "../../model/UserModel.php";

$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;
if (!empty($_SESSION['restaurant_images'])) {
    $getRestaurantImages = $_SESSION['restaurant_images'];
}

// Lấy danh sách món ăn đặc sắc từ cơ sở dữ liệu
$featuredDishes = getFeaturedDishes($languageId);

$getAllMenuImages = getAllMenuImages($languageId, 1);

// Lấy thông tin khách sạn 
$informationHotel = getHotelInfoWithLanguage($languageId);

$getAmThucNgonNgu = getAmThucNgonNgu($languageId, 1);
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
                // Gọi hàm để lấy ảnh, ví dụ: giới hạn 3 ảnh
                $images = getRestaurantImages(3);
                $first = true; // Biến để đánh dấu slide đầu tiên là active

                // Lặp qua danh sách ảnh để tạo các slide
                foreach ($images as $image) {
                    $activeClass = $first ? 'active' : ''; // Thêm class active cho slide đầu tiên
                    echo '<div class="slide ' . $activeClass . '">';
                    echo '<img src="/libertylaocai/view/img/' . htmlspecialchars($image['image']) . '" alt="Nhà hàng Liberty">';
                    echo '</div>';
                    $first = false; // Sau slide đầu tiên, các slide tiếp theo không có class active
                }
                ?>
                <button class="slider-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
                <button class="slider-btn next-btn"><i class="fas fa-chevron-right"></i></button>
                <div class="slider-dots">
                    <?php
                    // Tạo các chấm điều hướng tương ứng với số lượng ảnh
                    $first = true; // Reset để đánh dấu chấm đầu tiên là active
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
                    <button class="book-now-btn">Đặt bàn ngay</button>
                        </div>
                    </div>
                    <div class="reviews-section">
                        <div class="reviews-header">
                            <h3><?php echo $languageId == 1 ? 'Đánh giá từ khách hàng' : 'Customer Reviews'; ?></h3>
                            <?php
                            $reviews = getRestaurantReviews();
                            $totalReviews = count($reviews);
                            $totalRating = array_sum(array_column($reviews, 'rate')) / ($totalReviews ?: 1);
                            $ratingBreakdown = array_count_values(array_column($reviews, 'rate'));
                            ?>
                            <div class="overall-rating">
                                <span class="rating-score"><?php echo number_format($totalRating, 1); ?></span>
                                <div class="rating-stars">
                                    <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= floor($totalRating) ? '<i class="fas fa-star"></i>' : ($i == ceil($totalRating) && $totalRating != floor($totalRating) ? '<i class="fas fa-star-half-alt"></i>' : '<i class="far fa-star"></i>');
                                    }
                                    ?>
                                </div>
                                <span class="rating-count">(<?php echo $totalReviews; ?> <?php echo $languageId == 1 ? 'đánh giá' : 'reviews'; ?>)</span>
                            </div>
                            <div class="rating-breakdown">
                                <?php
                                for ($i = 5; $i >= 1; $i--) {
                                    $count = isset($ratingBreakdown[$i]) ? $ratingBreakdown[$i] : 0;
                                    $percentage = $totalReviews ? ($count / $totalReviews * 100) : 0;
                                ?>
                                    <div class="rating-bar">
                                        <span class="rating-label"><?php echo $i; ?> <?php echo $languageId == 1 ? 'sao' : 'stars'; ?></span>
                                        <div class="bar-container">
                                            <div class="bar-fill" style="width: <?php echo $percentage; ?>%"></div>
                                        </div>
                                        <span class="rating-percent"><?php echo round($percentage); ?>%</span>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="reviews-list">
                            <?php foreach ($reviews as $review): ?>
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="reviewer-info">
                                            <div class="reviewer-avatar"><?php echo htmlspecialchars($review['name'][0]); ?></div>
                                            <div class="reviewer-details">
                                                <div class="reviewer-name"><?php echo htmlspecialchars($review['name']); ?></div>
                                                <div class="review-date"><?php echo date('d/m/Y', strtotime($review['create_at'])); ?></div>
                                            </div>
                                        </div>
                                        <div class="review-rating">
                                            <?php
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $review['rate'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="review-content">
                                        <p><?php echo htmlspecialchars($review['content']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="show-more-reviews"><i class="fas fa-chevron-down"></i> <?php echo $languageId == 1 ? 'Xem thêm đánh giá' : 'Show more reviews'; ?></button>
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

                    <!-- Tab: Menu -->
                    <div class="tab-content" id="menu">
                        <div class="service-featured">
                            <h1><?php echo $languageId == 1 ? 'Thực đơn' : 'Menu'; ?></h1>
                            <div class="service-featured-list">
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
                        </div>
                    </div>

                    <!-- Tab: Booking -->
                    <div class="tab-content" id="booking">
                        <div class="booking-form-container">
                            <h2>Đặt bàn tại nhà hàng</h2>
                            <form class="booking-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Họ và tên *</label>
                                        <input type="text" name="fullname" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Số điện thoại *</label>
                                        <input type="tel" name="phone" id="phone" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email">
                                    </div>
                                    <div class="form-group">
                                        <label>Số lượng khách *</label>
                                        <select name="guests" required>
                                            <option value="" disabled selected>Chọn số lượng khách</option>
                                            <option value="1-10">1-10 người</option>
                                            <option value="11-30">11-30 người</option>
                                            <option value="31-50">31-50 người</option>
                                            <option value="51-100">51-100 người</option>
                                            <option value="101-200">101-200 người</option>
                                            <option value="201-500">201-500 người</option>
                                            <option value="500+">Trên 500 người</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Ngày tổ chức *</label>
                                        <input type="date" name="date" id="event-date" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Thời gian *</label>
                                        <select name="time" required>
                                            <option value="" disabled selected>Chọn thời gian</option>
                                            <option value="06:00">06:00</option>
                                            <option value="07:00">07:00</option>
                                            <option value="08:00">08:00</option>
                                            <option value="09:00">09:00</option>
                                            <option value="10:00">10:00</option>
                                            <option value="11:00">11:00</option>
                                            <option value="12:00">12:00</option>
                                            <option value="13:00">13:00</option>
                                            <option value="14:00">14:00</option>
                                            <option value="15:00">15:00</option>
                                            <option value="16:00">16:00</option>
                                            <option value="17:00">17:00</option>
                                            <option value="18:00">18:00</option>
                                            <option value="19:00">19:00</option>
                                            <option value="20:00">20:00</option>
                                            <option value="21:00">21:00</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Loại sự kiện</label>
                                    <select name="event-type">
                                        <option value="" disabled selected>Chọn loại sự kiện</option>
                                        <option value="wedding">Tiệc cưới</option>
                                        <option value="birthday">Tiệc sinh nhật</option>
                                        <option value="conference">Hội nghị</option>
                                        <option value="company">Tiệc công ty</option>
                                        <option value="family">Tiệc gia đình</option>
                                        <option value="other">Khác</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Yêu cầu đặc biệt</label>
                                    <textarea name="special-requests"></textarea>
                                </div>
                                <div class="booking-summary">
                                    <h3>Tóm tắt đặt bàn</h3>
                                    <div class="summary-item"><span>Số lượng khách:</span><span id="guest-count">Chưa chọn</span></div>
                                    <div class="summary-item"><span>Ngày:</span><span id="event-date-display">Chưa chọn</span></div>
                                    <div class="summary-item"><span>Thời gian:</span><span id="event-time">Chưa chọn</span></div>
                                </div>
                                <div class="terms-checkbox">
                                    <input type="checkbox" id="terms" required>
                                    <label for="terms">Tôi đồng ý với <a href="#" class="terms-link">điều khoản và điều kiện</a> của nhà hàng</label>
                                </div>
                                <button type="submit" class="submit-booking-btn"><i class="fas fa-calendar-check"></i> Gửi yêu cầu đặt bàn</button>
                            </form>
                        </div>
                        <div class="booking-info">
                            <h3>Thông tin quan trọng</h3>
                            <div class="info-list">
                                <div class="info-item">
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <strong>Giờ hoạt động</strong>
                                        <p>6:00 - 22:00 hàng ngày</p>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-ban"></i>
                                    <div>
                                        <strong>Chính sách hủy</strong>
                                        <p>Hủy miễn phí trước 24 giờ. Hủy trong ngày tính phí 50%.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
    </div>
    <script src="/libertylaocai/view/js/nhahang.js"></script>
    <?php include "footer.php"; ?>
</body>

</html>