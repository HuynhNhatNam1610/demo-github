<?php
require_once "session.php";
require_once "../../model/UserModel.php";

// // Lấy thông báo từ session (nếu có)
// $review_success = isset($_SESSION['review_success']) ? $_SESSION['review_success'] : null;
// $booking_success = isset($_SESSION['booking_success']) ? $_SESSION['booking_success'] : null;
// $review_error = isset($_SESSION['review_error']) ? $_SESSION['review_error'] : null;
// $booking_error = isset($_SESSION['booking_error']) ? $_SESSION['booking_error'] : null;

// // Xóa thông báo sau khi lấy
// unset($_SESSION['review_success']);
// unset($_SESSION['booking_success']);
// unset($_SESSION['review_error']);
// unset($_SESSION['booking_error']);

// Kiểm tra ngôn ngữ từ session, mặc định là 1 (tiếng Việt)
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;

// Định nghĩa các text đa ngôn ngữ
$texts = [
    1 => [ // Tiếng Việt
        'page_title' => 'Chi tiết phòng - The Liberty Lào Cai',
        'description_tab' => 'Mô tả phòng',
        'booking_tab' => 'Đặt phòng',
        'price_per_night' => '/ Đêm',
        'area_label' => 'Diện tích:',
        'quantity_label' => 'Số lượng phòng:',
        'amenities_title' => 'Tiện ích phòng',
        'included_services_title' => 'Dịch vụ bao gồm',
        'included_service_1' => 'Bữa sáng buffet miễn phí',
        'included_service_2' => 'Thuế GTGT',
        'included_service_3' => 'WiFi tốc độ cao',
        'included_service_4' => 'Bãi đỗ xe miễn phí',
        'price_summary_title' => 'Giá phòng',
        'taxes_fees' => 'Thuế & phí',
        'included' => 'Đã bao gồm',
        'total_per_night' => 'Tổng cộng/đêm',
        'book_now' => 'Đặt phòng ngay',
        'other_rooms_title' => 'Các phòng khác',
        'view_details' => 'Xem chi tiết',
        'reviews_title' => 'Đánh giá từ khách hàng',
        'no_reviews' => 'Chưa có đánh giá nào cho phòng này. Hãy là người đầu tiên đánh giá!',
        'write_review' => 'Viết đánh giá của bạn',
        'share_experience' => 'Chia sẻ trải nghiệm của bạn',
        'rating_label' => 'Đánh giá của bạn:',
        'select_stars' => 'Chọn số sao',
        'name_label' => 'Họ và tên:',
        'email_label' => 'Email (tùy chọn):',
        'phone_label' => 'Số điện thoại (tùy chọn):',
        'review_content_label' => 'Nội dung đánh giá:',
        'review_content_placeholder' => 'Chia sẻ trải nghiệm của bạn về phòng này...',
        'cancel' => 'Hủy',
        'submit_review' => 'Gửi đánh giá',
        'checkin_label' => 'Ngày nhận phòng',
        'checkout_label' => 'Ngày trả phòng',
        'adults_label' => 'Người lớn',
        'children_label' => 'Trẻ em',
        'fullname_label' => 'Họ và tên',
        'email_booking_label' => 'Email',
        'phone_booking_label' => 'Số điện thoại',
        'special_requests_label' => 'Yêu cầu đặc biệt',
        'special_requests_placeholder' => 'Nhập yêu cầu đặc biệt của bạn...',
        'booking_summary_title' => 'Tóm tắt đặt phòng',
        'room_type_label' => 'Loại phòng:',
        'nights_label' => 'Số đêm:',
        'room_price_label' => 'Giá phòng:',
        'children_fee_label' => 'Phí trẻ em (6-12 tuổi):',
        'total_label' => 'Tổng cộng:',
        'terms_label' => 'Tôi đồng ý với điều khoản và điều kiện của khách sạn',
        'confirm_booking' => 'Xác nhận đặt phòng',
        'important_info_title' => 'Thông tin quan trọng',
        'checkin_checkout_info' => 'Giờ nhận/trả phòng',
        'checkin_checkout_details' => 'Nhận phòng: 14:00 | Trả phòng: 12:00',
        'children_policy' => 'Chính sách trẻ em',
        'children_policy_details' => 'Trẻ em dưới 6 tuổi: miễn phí<br>Từ 6-12 tuổi: 100.000 VNĐ/đêm',
        'cancellation_policy' => 'Chính sách hủy phòng',
        'cancellation_policy_details' => 'Hủy miễn phí trước 1 ngày<br>Hủy trong ngày: tính 50% giá phòng',
        'review_success' => 'Cảm ơn bạn đã gửi đánh giá!',
        'review_error_fill' => 'Vui lòng điền đầy đủ thông tin và chọn số sao.',
        'booking_error_past_date' => 'Ngày nhận phòng không thể là ngày trong quá khứ.',
        'booking_error_invalid_dates' => 'Ngày trả phòng phải sau ngày nhận phòng.',
        'booking_error_missing_info' => 'Vui lòng điền đầy đủ thông tin bắt buộc.',
        'booking_error_invalid_email' => 'Email không hợp lệ.',
        'booking_error_no_room' => 'Rất tiếc, loại phòng này đã hết chỗ trong thời gian bạn chọn. Vui lòng chọn ngày khác.',
        'booking_error_customer' => 'Có lỗi xảy ra khi tạo thông tin khách hàng.',
        'booking_error_create' => 'Có lỗi xảy ra khi tạo đặt phòng. Vui lòng thử lại.',
        'booking_success' => 'Đặt phòng thành công! Mã đặt phòng của bạn là: #',
        'no_name_room' => 'Phòng không tên'
    ],
    2 => [ // Tiếng Anh
        'page_title' => 'Room Details - The Liberty Lao Cai',
        'description_tab' => 'Room Description',
        'booking_tab' => 'Booking',
        'price_per_night' => '/ Night',
        'area_label' => 'Area:',
        'quantity_label' => 'Quantity:',
        'max_guests' => 'Maximum 2 adults + 1 child',
        'amenities_title' => 'Room Amenities',
        'included_services_title' => 'Included Services',
        'included_service_1' => 'Free buffet breakfast',
        'included_service_2' => 'VAT included',
        'included_service_3' => 'High-speed WiFi',
        'included_service_4' => 'Free parking',
        'price_summary_title' => 'Room Price',
        'taxes_fees' => 'Taxes & Fees',
        'included' => 'Included',
        'total_per_night' => 'Total/night',
        'book_now' => 'Book Now',
        'other_rooms_title' => 'Other Rooms',
        'view_details' => 'View Details',
        'reviews_title' => 'Customer Reviews',
        'no_reviews' => 'No reviews yet for this room. Be the first to review!',
        'write_review' => 'Write Your Review',
        'share_experience' => 'Share Your Experience',
        'rating_label' => 'Your Rating:',
        'select_stars' => 'Select stars',
        'name_label' => 'Full Name:',
        'email_label' => 'Email (optional):',
        'phone_label' => 'Phone Number (optional):',
        'review_content_label' => 'Review Content:',
        'review_content_placeholder' => 'Share your experience about this room...',
        'cancel' => 'Cancel',
        'submit_review' => 'Submit Review',
        'checkin_label' => 'Check-in Date',
        'checkout_label' => 'Check-out Date',
        'adults_label' => 'Adults',
        'children_label' => 'Children',
        'fullname_label' => 'Full Name',
        'email_booking_label' => 'Email',
        'phone_booking_label' => 'Phone Number',
        'special_requests_label' => 'Special Requests',
        'special_requests_placeholder' => 'Enter your special requests...',
        'booking_summary_title' => 'Booking Summary',
        'room_type_label' => 'Room Type:',
        'nights_label' => 'Nights:',
        'room_price_label' => 'Room Price:',
        'children_fee_label' => 'Children Fee (6-12 years):',
        'total_label' => 'Total:',
        'terms_label' => 'I agree with the terms and conditions of the hotel',
        'confirm_booking' => 'Confirm Booking',
        'important_info_title' => 'Important Information',
        'checkin_checkout_info' => 'Check-in/Check-out Time',
        'checkin_checkout_details' => 'Check-in: 2:00 PM | Check-out: 12:00 PM',
        'children_policy' => 'Children Policy',
        'children_policy_details' => 'Children under 6 years: free<br>6-12 years: 100,000 VND/night',
        'cancellation_policy' => 'Cancellation Policy',
        'cancellation_policy_details' => 'Free cancellation 1 day prior<br>Same-day cancellation: 50% room charge',
        'review_success' => 'Thank you for submitting your review!',
        'review_error_fill' => 'Please fill in all required information and select a star rating.',
        'booking_error_past_date' => 'Check-in date cannot be in the past.',
        'booking_error_invalid_dates' => 'Check-out date must be after check-in date.',
        'booking_error_missing_info' => 'Please fill in all required information.',
        'booking_error_invalid_email' => 'Invalid email address.',
        'booking_error_no_room' => 'Sorry, this room type is fully booked for the selected dates. Please choose different dates.',
        'booking_error_customer' => 'An error occurred while creating customer information.',
        'booking_error_create' => 'An error occurred while creating the booking. Please try again.',
        'booking_success' => 'Booking successful! Your booking ID is: #',
        'no_name_room' => 'Unnamed Room'
    ]
];

$text = $texts[$languageId];
$room_info = $_SESSION['room_info'] ?? [];
$images = $room_info['images'] ?? '';
$room  = getRoomDetail($room_info['room_id'], $languageId);
$room_id = $room_info['room_id'] ?? '1';
$price_number = (int)str_replace('.', '', $room['price']);
$other_rooms = getOtherRooms($room_id, $languageId, 6);   /// danh sách các phòng khác
$amenities = getRoomAmenities($room_id, $languageId);   /// lấy tiện tích của phòng

?>

<!DOCTYPE html>
<html lang="<?php echo $languageId == 1 ? 'vi' : 'en'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($room['name'] ?? $text['no_name_room']); ?> - The Liberty Lào Cai</title>
    <link rel="stylesheet" href="/libertylaocai/view/css/chitietphong.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php include "header.php"; ?>

    <div class="big-container">
        <!-- Banner Slider -->
        <section class="banner-slider">
            <div class="slider-container">
                <?php foreach ($images as $index => $image): ?>
                    <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo (strpos($image, 'http') === 0) ? $image : '/libertylaocai/view/img/' . htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($room['name'] ?? $text['no_name_room']); ?> <?php echo $index + 1; ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="slider-btn prev-btn" onclick="prevSlide()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="slider-btn next-btn" onclick="nextSlide()">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="slider-dots">
                <?php foreach ($images as $index => $image): ?>
                    <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $index + 1; ?>)"></span>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Tab Navigation -->
        <nav class="tab-navigation">
            <div class="container">
                <button class="tab-btn active" data-tab="description"><?php echo $text['description_tab']; ?></button>
                <button class="tab-btn" data-tab="booking"><?php echo $text['booking_tab']; ?></button>
            </div>
        </nav>

        <!-- Tab Content -->
        <div class="container">
            <!-- Description Tab -->
            <div id="description" class="tab-content active">
                <div class="room-info-grid">
                    <div class="room-details">
                        <div class="room-price">
                            <h1><?php echo htmlspecialchars($room['name'] ?? $text['no_name_room']); ?> -  <span class="current-price"><?php echo  number_format($price_number, 0, '.', '.'); ?> <?php echo $languageId == 1 ? 'VNĐ' : 'VND'; ?>
                        </span><span class="price-unit"><?php echo $text['price_per_night']; ?></span></h1>
                        </div>

                        <?php if (!empty($amenities)): ?>
                            <div class="amenities">
                                <h3><?php echo $text['amenities_title']; ?></h3>
                                <div class="amenities-grid">
                                    <?php foreach ($amenities as $amenity): ?>
                                        <div class="amenity-item">
                                            <i class="fas fa-check"></i>
                                            <span><?php echo htmlspecialchars($amenity); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="room-description">
                            <?php echo $room['description'] ?? $text['no_name_room']; ?>
                        </div>
                    </div>

                    <div class="booking-card">
                        <div class="price-summary">
                            <h3><?php echo $text['price_summary_title']; ?></h3>
                            <div class="price-breakdown">
                                <div class="price-row">
                                    <span><?php echo htmlspecialchars($room['name'] ?? $text['no_name_room']); ?></span>
                                    <span><?php echo  number_format($price_number, 0, '.', '.'); ?> <?php echo $languageId == 1 ? 'VNĐ' : 'VND'; ?></span>
                                </div>
                                <div class="price-row">
                                    <span><?php echo $text['taxes_fees']; ?></span>
                                    <span><?php echo $text['included']; ?></span>
                                </div>
                                <div class="price-total">
                                    <span><?php echo $text['total_per_night']; ?></span>
                                    <span><?php echo  number_format($price_number, 0, '.', '.'); ?> <?php echo $languageId == 1 ? 'VNĐ' : 'VND'; ?></span>
                                </div>
                            </div>
                        </div>
                        <button class="book-now-btn" onclick="switchTab('booking')">
                            <?php echo $text['book_now']; ?>
                        </button>
                    </div>
                </div>

                <!-- Other Rooms Section -->
                <?php if (!empty($other_rooms)): ?>
                    <div class="other-rooms">
                        <h2><?php echo $text['other_rooms_title']; ?></h2>
                        <div class="room-slider-container">
                            <button class="room-nav-btn room-nav-prev" onclick="prevRoomSlide()">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <div class="rooms-grid-wrapper">
                                <div class="rooms-grid">
                                    <?php foreach ($other_rooms as $other_room):
                                        $other_price = (int)str_replace('.', '', $other_room['price']);
                                        $image = !empty($other_room['images']) ? $other_room['images'][0] : 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
                                    ?>
                                        <div class="room-card" data-price="<?php echo $other_price; ?>" data-room-id="<?php echo $other_room['id']; ?>">
                                            <img src="<?php echo (strpos($image, 'http') === 0) ? $image : '/libertylaocai/view/img/' . htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($other_room['name'] ?? $text['no_name_room']); ?>">
                                            <div class="room-card-content">
                                                <h3>
                                                    <?php echo htmlspecialchars($other_room['name'] ?? $text['no_name_room']); ?>
                                                </h3>
                                                <p>
                                                    <?php echo "Diện tích: " . $other_room['area']; ?>m² <br>
                                                    <?php echo !empty($other_room['bed_info']) ? htmlspecialchars($other_room['bed_info']) : ($languageId == 1 ? 'Không có thông tin giường' : 'No bed information'); ?>
                                                    </p>

                                                <div class="room-card-price"><?php echo number_format($other_price, 0, '.', '.'); ?> <?php echo $languageId == 1 ? 'VNĐ' : 'VND'; ?><?php echo $text['price_per_night']; ?></div>
                                                <form action="/libertylaocai/user/submit" method="POST" style="display:inline;">
                                                    <input type="hidden" name="room_other_id" value="<?= $other_room['id']; ?>">
                                                    <button type="submit" name="orther_room" value="<?= $other_room['name']; ?>" class="view-room-btn"><?php echo $text['view_details']; ?></button>
                                                </form>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <button class="room-nav-btn room-nav-next" onclick="nextRoomSlide()">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

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

            <!-- Booking Tab -->
            <div id="booking" class="tab-content">
                <div class="booking-form-container">
                    <form class="booking-form" method="POST" action="">
                        <input type="hidden" name="submit_booking_room" value="1">
                        <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                        <input type="hidden" id="room-price" name="room-price" value="<?php echo htmlspecialchars((int)$price_number); ?>">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="checkin"><?php echo $text['checkin_label']; ?><span class="required">*</span></label>
                                <input type="date" id="checkin" name="checkin" required>
                            </div>
                            <div class="form-group">
                                <label for="checkout"><?php echo $text['checkout_label']; ?><span class="required">*</span></label>
                                <input type="date" id="checkout" name="checkout" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="adults"><?php echo $text['adults_label']; ?><span class="required">*</span></label>
                                <input type="number" id="adults" name="adults" min="1" max="10" placeholder="<?php echo $languageId == 1 ? 'Nhập số người lớn' : 'Enter number of adults'; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="children"><?php echo $text['children_label']; ?><span class="required">*</span></label>
                                <input type="number" id="children" name="children" min="0" max="10" placeholder="<?php echo $languageId == 1 ? 'Nhập số trẻ em' : 'Enter number of children'; ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fullname"><?php echo $text['fullname_label']; ?><span class="required">*</span></label>
                                <input type="text" id="fullname" name="fullname" placeholder="<?php echo $languageId == 1 ? 'Nhập họ và tên' : 'Enter full name'; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone"><?php echo $text['phone_booking_label']; ?><span class="required">*</span></label>
                                <input type="tel" id="phone" name="phone" placeholder="<?php echo $languageId == 1 ? 'Nhập số điện thoại' : 'Enter phone number'; ?>" required>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="email"><?php echo $text['email_booking_label']; ?><span class="required">*</span></label>
                            <input type="email" id="email" name="email" placeholder="<?php echo $languageId == 1 ? 'Nhập email' : 'Enter email'; ?>" required>
                        </div>


                        <div class="form-group">
                            <label for="special-requests"><?php echo $text['special_requests_label']; ?></label>
                            <textarea id="special-requests" name="special-requests" rows="3" placeholder="<?php echo $text['special_requests_placeholder']; ?>"></textarea>
                        </div>



                        <div class="terms-checkbox">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms"><?php echo $text['terms_label']; ?></label>
                        </div>

                        <button type="submit" class="submit-booking-btn">
                            <i class="fas fa-calendar-check"></i>
                            <?php echo $text['confirm_booking']; ?>
                        </button>
                    </form>
                </div>
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

    <?php include "footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="/libertylaocai/view/js/chitietphong.js"></script>
    <script>
        const roomPrice = <?php echo isset($price_number) ? json_encode((int)$price_number) : '0'; ?>;
        const languageId = <?php echo json_encode($languageId); ?>;
        const texts = <?php echo json_encode($texts[$languageId]); ?>;
    </script>
</body>

</html>