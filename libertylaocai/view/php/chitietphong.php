<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phòng Deluxe - PCA Hotel</title>
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
                <div class="slide active">
                    <img src="https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2874&q=80" alt="Phòng Deluxe Double">
                </div>
                <div class="slide">
                    <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2870&q=80" alt="Phòng tắm">
                </div>
                <div class="slide">
                    <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="View phòng">
                </div>
            </div>
            <button class="slider-btn prev-btn" onclick="prevSlide()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="slider-btn next-btn" onclick="nextSlide()">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="slider-dots">
                <span class="dot active" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
            </div>
        </section>

        <!-- Tab Navigation -->
        <nav class="tab-navigation">
            <div class="container">
                <button class="tab-btn active" data-tab="description">Mô tả phòng</button>
                <button class="tab-btn" data-tab="booking">Đặt phòng</button>
            </div>
        </nav>

        <!-- Tab Content -->
        <div class="container">
            <!-- Description Tab -->
            <div id="description" class="tab-content active">
                <div class="room-info-grid">
                    <div class="room-details">
                        <h1>Phòng Deluxe Double</h1>
                        <div class="room-price">
                            <span class="current-price">700.000 VNĐ</span>
                            <span class="price-unit">/phòng/đêm</span>
                        </div>
                        
                        <div class="room-specs">
                            <div class="spec-item">
                                <i class="fas fa-expand-arrows-alt"></i>
                                <span>Diện tích: 34m²</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-bed"></i>
                                <span>Giường đôi: 1.8m x 2.0m</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-users"></i>
                                <span>Tối đa 2 người lớn + 1 trẻ em</span>
                            </div>
                        </div>

                        <div class="room-description">
                            <p>Phòng Deluxe Double được thiết kế hiện đại với đầy đủ tiện nghi cao cấp, mang đến trải nghiệm nghỉ dưỡng thoải mái và đẳng cấp tại trung tâm thành phố Lào Cai.</p>
                        </div>

                        <div class="amenities">
                            <h3>Tiện ích phòng</h3>
                            <div class="amenities-grid">
                                <div class="amenity-item">
                                    <i class="fas fa-tv"></i>
                                    <span>TV 40 inch</span>
                                </div>
                                <div class="amenity-item">
                                    <i class="fas fa-snowflake"></i>
                                    <span>Điều hòa</span>
                                </div>
                                <div class="amenity-item">
                                    <i class="fas fa-wifi"></i>
                                    <span>WiFi miễn phí</span>
                                </div>
                                <div class="amenity-item">
                                    <i class="fas fa-shower"></i>
                                    <span>Phòng tắm riêng</span>
                                </div>
                                <div class="amenity-item">
                                    <i class="fas fa-coffee"></i>
                                    <span>Café G7 miễn phí</span>
                                </div>
                                <div class="amenity-item">
                                    <i class="fas fa-tint"></i>
                                    <span>Nước uống miễn phí</span>
                                </div>
                                <div class="amenity-item">
                                    <i class="fas fa-phone"></i>
                                    <span>Điện thoại bàn</span>
                                </div>
                                <div class="amenity-item">
                                    <i class="fas fa-wind"></i>
                                    <span>Máy sấy tóc</span>
                                </div>
                                <div class="amenity-item">
                                    <i class="fas fa-utensils"></i>
                                    <span>Bàn làm việc</span>
                                </div>
                                <div class="amenity-item">
                                    <i class="fas fa-tshirt"></i>
                                    <span>Tủ quần áo</span>
                                </div>
                                <div class="amenity-item">
                                    <i class="fas fa-shoe-prints"></i>
                                    <span>Dép phòng</span>
                                </div>
                                <div class="amenity-item">
                                    <i class="fas fa-spa"></i>
                                    <span>Đồ dùng tắm cao cấp</span>
                                </div>
                            </div>
                        </div>

                        <div class="included-services">
                            <h3>Dịch vụ bao gồm</h3>
                            <ul>
                                <li><i class="fas fa-check"></i> Bữa sáng buffet miễn phí</li>
                                <li><i class="fas fa-check"></i> Thuế GTGT</li>
                                <li><i class="fas fa-check"></i> WiFi tốc độ cao</li>
                                <li><i class="fas fa-check"></i> Bãi đỗ xe miễn phí</li>
                            </ul>
                        </div>
                    </div>

                    <div class="booking-card">
                        <div class="price-summary">
                            <h3>Giá phòng</h3>
                            <div class="price-breakdown">
                                <div class="price-row">
                                    <span>Phòng Deluxe Double</span>
                                    <span>700.000 VNĐ</span>
                                </div>
                                <div class="price-row">
                                    <span>Thuế & phí</span>
                                    <span>Đã bao gồm</span>
                                </div>
                                <div class="price-total">
                                    <span>Tổng cộng/đêm</span>
                                    <span>700.000 VNĐ</span>
                                </div>
                            </div>
                        </div>
                        <button class="book-now-btn" onclick="switchTab('booking')">
                            Đặt phòng ngay
                        </button>
                    </div>
                </div>

                <!-- Other Rooms Section -->
                <div class="other-rooms">
                    <h2>Các phòng khác</h2>
                    <div class="room-slider-container">
                        <button class="room-nav-btn room-nav-prev" onclick="prevRoomSlide()">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div class="rooms-grid-wrapper">
                            <div class="rooms-grid">
                                <div class="room-card">
                                    <img src="https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Phòng Deluxe Twin">
                                    <div class="room-card-content">
                                        <h3>Phòng Deluxe Twin</h3>
                                        <p>2 giường đơn • 34m²</p>
                                        <div class="room-card-price">800.000 VNĐ/đêm</div>
                                        <button class="view-room-btn">Xem chi tiết</button>
                                    </div>
                                </div>
                                <div class="room-card">
                                    <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Phòng Triple">
                                    <div class="room-card-content">
                                        <h3>Phòng Triple</h3>
                                        <p>3 giường • 36m²</p>
                                        <div class="room-card-price">1.100.000 VNĐ/đêm</div>
                                        <button class="view-room-btn">Xem chi tiết</button>
                                    </div>
                                </div>
                                <div class="room-card">
                                    <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Phòng Family">
                                    <div class="room-card-content">
                                        <h3>Phòng Family</h3>
                                        <p>2 phòng ngủ + phòng khách • 69m²</p>
                                        <div class="room-card-price">1.300.000 VNĐ/đêm</div>
                                        <button class="view-room-btn">Xem chi tiết</button>
                                    </div>
                                </div>
                                <div class="room-card">
                                    <img src="https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Phòng Superior">
                                    <div class="room-card-content">
                                        <h3>Phòng Superior</h3>
                                        <p>Giường đôi • 28m²</p>
                                        <div class="room-card-price">600.000 VNĐ/đêm</div>
                                        <button class="view-room-btn">Xem chi tiết</button>
                                    </div>
                                </div>
                                <div class="room-card">
                                    <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Phòng Suite">
                                    <div class="room-card-content">
                                        <h3>Phòng Suite</h3>
                                        <p>Phòng ngủ riêng + phòng khách • 45m²</p>
                                        <div class="room-card-price">1.500.000 VNĐ/đêm</div>
                                        <button class="view-room-btn">Xem chi tiết</button>
                                    </div>
                                </div>
                                <div class="room-card">
                                    <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="Phòng VIP">
                                    <div class="room-card-content">
                                        <h3>Phòng VIP</h3>
                                        <p>Giường king size • View đẹp • 50m²</p>
                                        <div class="room-card-price">2.000.000 VNĐ/đêm</div>
                                        <button class="view-room-btn">Xem chi tiết</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="room-nav-btn room-nav-next" onclick="nextRoomSlide()">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="reviews-section">
                <div class="reviews-header">
                    <h3>Đánh giá từ khách hàng</h3>
                    <div class="overall-rating">
                        <div class="rating-score">4.5</div>
                        <div class="rating-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <div class="rating-count">(89 đánh giá)</div>
                    </div>
                </div>

                <div class="rating-breakdown">
                    <div class="rating-bar">
                        <span class="rating-label">5 sao</span>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: 65%"></div>
                        </div>
                        <span class="rating-percent">65%</span>
                    </div>
                    <div class="rating-bar">
                        <span class="rating-label">4 sao</span>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: 25%"></div>
                        </div>
                        <span class="rating-percent">25%</span>
                    </div>
                    <div class="rating-bar">
                        <span class="rating-label">3 sao</span>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: 8%"></div>
                        </div>
                        <span class="rating-percent">8%</span>
                    </div>
                    <div class="rating-bar">
                        <span class="rating-label">2 sao</span>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: 2%"></div>
                        </div>
                        <span class="rating-percent">2%</span>
                    </div>
                    <div class="rating-bar">
                        <span class="rating-label">1 sao</span>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: 0%"></div>
                        </div>
                        <span class="rating-percent">0%</span>
                    </div>
                </div>

                <div class="reviews-list">
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="reviewer-details">
                                    <div class="reviewer-name">Nguyễn Minh H.</div>
                                    <div class="review-date">15/05/2024</div>
                                </div>
                            </div>
                            <div class="review-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="review-content">
                            <p>Phòng rất sạch sẽ và thoải mái. Nhân viên phục vụ nhiệt tình, vị trí thuận tiện để đi lại. Bữa sáng ngon và đa dạng.</p>
                        </div>
                    </div>

                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="reviewer-details">
                                    <div class="reviewer-name">Trần Thị L.</div>
                                    <div class="review-date">12/05/2024</div>
                                </div>
                            </div>
                            <div class="review-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                        <div class="review-content">
                            <p>Khách sạn đẹp, phòng tắm hiện đại. View từ phòng nhìn ra phố rất đẹp. Giá cả hợp lý so với chất lượng.</p>
                        </div>
                    </div>

                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="reviewer-details">
                                    <div class="reviewer-name">Lê Văn D.</div>
                                    <div class="review-date">08/05/2024</div>
                                </div>
                            </div>
                            <div class="review-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="review-content">
                            <p>Nghỉ tại đây 2 đêm, rất hài lòng. Phòng yên tĩnh, điều hòa mát, WiFi nhanh. Sẽ quay lại lần sau.</p>
                        </div>
                    </div>
                </div>

                <button class="show-more-reviews">
                    Xem thêm đánh giá
                    <i class="fas fa-chevron-down"></i>
                </button>
                <!-- Thêm phần này vào sau .show-more-reviews button trong .reviews-section -->

                <div class="write-review-section">
                    <button class="write-review-btn" onclick="toggleReviewForm()">
                        <i class="fas fa-pen"></i>
                        Viết đánh giá của bạn
                    </button>
                    
                    <div class="review-form-container" id="reviewForm" style="display: none;">
                        <h4>Chia sẻ trải nghiệm của bạn</h4>
                        <form class="review-form" onsubmit="submitReview(event)">
                            <div class="rating-input">
                                <label>Đánh giá của bạn:</label>
                                <div class="star-rating">
                                    <input type="radio" id="star5" name="rating" value="5">
                                    <label for="star5" class="star"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4" class="star"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3" class="star"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2" class="star"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1" class="star"><i class="fas fa-star"></i></label>
                                </div>
                                <span class="rating-text">Chọn số sao</span>
                            </div>
                            
                            <div class="form-group">
                                <label for="reviewer-name">Họ và tên:</label>
                                <input type="text" id="reviewer-name" name="reviewer-name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="review-content">Nội dung đánh giá:</label>
                                <textarea id="review-content" name="review-content" rows="4" 
                                        placeholder="Chia sẻ trải nghiệm của bạn về phòng này..." required></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="button" class="cancel-btn" onclick="toggleReviewForm()">
                                    Hủy
                                </button>
                                <button type="submit" class="submit-review-btn">
                                    <i class="fas fa-paper-plane"></i>
                                    Gửi đánh giá
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>

            <!-- Booking Tab -->
            <div id="booking" class="tab-content">
                <div class="booking-form-container">
                    <h2>Đặt phòng Deluxe Double</h2>
                    <form class="booking-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="checkin">Ngày nhận phòng</label>
                                <input type="date" id="checkin" name="checkin" required>
                            </div>
                            <div class="form-group">
                                <label for="checkout">Ngày trả phòng</label>
                                <input type="date" id="checkout" name="checkout" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="adults">Người lớn</label>
                                <select id="adults" name="adults">
                                    <option value="1">1 người</option>
                                    <option value="2" selected>2 người</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="children">Trẻ em</label>
                                <select id="children" name="children">
                                    <option value="0" selected>0 trẻ em</option>
                                    <option value="1">1 trẻ em</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fullname">Họ và tên</label>
                            <input type="text" id="fullname" name="fullname" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="tel" id="phone" name="phone" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="special-requests">Yêu cầu đặc biệt</label>
                            <textarea id="special-requests" name="special-requests" rows="3" placeholder="Nhập yêu cầu đặc biệt của bạn..."></textarea>
                        </div>

                        <div class="booking-summary">
                            <h3>Tóm tắt đặt phòng</h3>
                            <div class="summary-item">
                                <span>Loại phòng:</span>
                                <span>Deluxe Double</span>
                            </div>
                            <div class="summary-item">
                                <span>Số đêm:</span>
                                <span id="nights-count">1 đêm</span>
                            </div>
                            <div class="summary-item">
                                <span>Giá phòng:</span>
                                <span>700.000 VNĐ/đêm</span>
                            </div>
                            <div class="summary-total">
                                <span>Tổng cộng:</span>
                                <span id="total-price">700.000 VNĐ</span>
                            </div>
                        </div>

                        <div class="terms-checkbox">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">Tôi đồng ý với <a href="#" class="terms-link">điều khoản và điều kiện</a> của khách sạn</label>
                        </div>

                        <button type="submit" class="submit-booking-btn">
                            <i class="fas fa-calendar-check"></i>
                            Xác nhận đặt phòng
                        </button>
                    </form>
                </div>

                <div class="booking-info">
                    <h3>Thông tin quan trọng</h3>
                    <div class="info-list">
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong>Giờ nhận/trả phòng</strong>
                                <p>Nhận phòng: 14:00 | Trả phòng: 12:00</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-baby"></i>
                            <div>
                                <strong>Chính sách trẻ em</strong>
                                <p>Trẻ em dưới 6 tuổi: miễn phí<br>Từ 6-12 tuổi: 100.000 VNĐ/đêm</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-ban"></i>
                            <div>
                                <strong>Chính sách hủy phòng</strong>
                                <p>Hủy miễn phí trước 1 ngày<br>Hủy trong ngày: tính 50% giá phòng</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

  <?php include "footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="/libertylaocai/view/js/chitietphong.js"></script>
</body>
</html>