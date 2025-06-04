<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sky Bar - Tầng 7 PCA Hotel</title>
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
                <p class="hero-subtitle">Tầng 7 - PCA Hotel</p>
                <p class="hero-location">View toàn cảnh thành phố Lào Cai</p>
                <div class="cta-buttons">
                    <a href="#reservation" class="cta-btn cta-primary">
                        <i class="fas fa-calendar-plus"></i>
                        Đặt bàn ngay
                    </a>
                    <a href="#menu" class="cta-btn cta-secondary">
                        <i class="fas fa-utensils"></i>
                        Xem thực đơn
                    </a>
                </div>
            </div>
        </section>

        <!-- Navigation Tabs -->
        <nav class="nav-tabs">
            <div class="nav-container">
                <button class="nav-btn active" data-tab="about">Giới thiệu</button>
                <button class="nav-btn" data-tab="menu">Thực đơn</button>
                <button class="nav-btn" data-tab="events">Sự kiện & Band nhạc</button>
                <button class="nav-btn" data-tab="reservation">Đặt bàn</button>
            </div>
        </nav>

        <!-- About Tab -->
        <div id="about" class="tab-content active">
            <div class="container">
                <div class="about-grid">
                    <div class="about-text">
                        <h2>Trải nghiệm độc đáo trên tầng 7</h2>
                        <p>Sky Bar tại tầng 7 của PCA Hotel mang đến cho bạn không gian thư giãn đẳng cấp với tầm nhìn panorama tuyệt đẹp ra toàn bộ thành phố Lào Cai.</p>
                        <p>Với thiết kế hiện đại, không gian mở thoáng đãng và dịch vụ chuyên nghiệp, chúng tôi chuyên phục vụ đồ uống giải khát cao cấp và các món ăn Âu - Á tinh tế.</p>
                        <p>Đặc biệt, Sky Bar tổ chức biểu diễn band nhạc sống động vào các tối thứ 3 và thứ 7 hàng tuần, tạo nên những buổi tối đầy cảm xúc và lãng mạn.</p>
                    </div>
                    <div class="about-image">
                        <img src="https://images.unsplash.com/photo-1514933651103-005eec06c04b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1974&q=80" alt="Sky Bar Interior">
                    </div>
                </div>

                <div class="about-features">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mountain"></i>
                        </div>
                        <h3 class="feature-title">View Tuyệt Đẹp</h3>
                        <p>Tầm nhìn 360° ra toàn cảnh thành phố Lào Cai và dãy núi Hoàng Liên Sơn hùng vĩ</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-cocktail"></i>
                        </div>
                        <h3 class="feature-title">Đồ uống cao cấp</h3>
                        <p>Menu cocktail độc đáo và đa dạng các loại đồ uống giải khát từ khắp nơi trên thế giới</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-music"></i>
                        </div>
                        <h3 class="feature-title">Band nhạc sống</h3>
                        <p>Biểu diễn band nhạc chuyên nghiệp vào tối thứ 3 và thứ 7 hàng tuần</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3 class="feature-title">Ẩm thực Âu - Á</h3>
                        <p>Thực đơn phong phú với các món ăn tinh tế từ châu Âu và châu Á</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Tab -->
        <div id="menu" class="tab-content">
            <div class="container">
                <div class="menu-categories">
                    <button class="category-btn active" data-category="cocktails">Cocktails</button>
                    <button class="category-btn" data-category="beverages">Đồ uống</button>
                    <button class="category-btn" data-category="appetizers">Khai vị</button>
                    <button class="category-btn" data-category="main">Món chính</button>
                    <button class="category-btn" data-category="desserts">Tráng miệng</button>
                </div>

                <div class="menu-grid" id="menuItems">
                    <!-- Cocktails -->
                    <div class="menu-item" data-category="cocktails">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">Sky Sunset</h3>
                            <span class="menu-item-price">180.000 VNĐ</span>
                        </div>
                        <p class="menu-item-desc">Cocktail đặc trưng của Sky Bar với whisky, liqueur cam và một chút bí mật của bartender</p>
                    </div>

                    <div class="menu-item" data-category="cocktails">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">Lào Cai Mist</h3>
                            <span class="menu-item-price">160.000 VNĐ</span>
                        </div>
                        <p class="menu-item-desc">Cocktail mang hương vị địa phương với vodka, chanh dây và lá bạc hà tươi</p>
                    </div>

                    <div class="menu-item" data-category="cocktails">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">Golden Mountain</h3>
                            <span class="menu-item-price">200.000 VNĐ</span>
                        </div>
                        <p class="menu-item-desc">Rum cao cấp kết hợp với mật ong rừng và nước cốt chanh tươi</p>
                    </div>

                    <!-- Beverages -->
                    <div class="menu-item" data-category="beverages" style="display: none;">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">Cà phê Arabica đặc biệt</h3>
                            <span class="menu-item-price">80.000 VNĐ</span>
                        </div>
                        <p class="menu-item-desc">Cà phê rang xay từ hạt Arabica Cầu Đất - Lào Cai</p>
                    </div>

                    <div class="menu-item" data-category="beverages" style="display: none;">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">Trà Shan Tuyết cổ thù</h3>
                            <span class="menu-item-price">120.000 VNĐ</span>
                        </div>
                        <p class="menu-item-desc">Trà quý hiếm từ những cây chè cổ thụ trên vùng cao Tây Bắc</p>
                    </div>

                    <!-- Appetizers -->
                    <div class="menu-item" data-category="appetizers" style="display: none;">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">Pate gan ngỗng</h3>
                            <span class="menu-item-price">280.000 VNĐ</span>
                        </div>
                        <p class="menu-item-desc">Pate gan ngỗng Pháp phục vụ cùng bánh mì nướng giòn</p>
                    </div>

                    <div class="menu-item" data-category="appetizers" style="display: none;">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">Sashimi cá hồi Na Uy</h3>
                            <span class="menu-item-price">320.000 VNĐ</span>
                        </div>
                        <p class="menu-item-desc">Cá hồi tươi ngon cắt lát mỏng, phục vụ cùng wasabi và gừng ngâm</p>
                    </div>

                    <!-- Main dishes -->
                    <div class="menu-item" data-category="main" style="display: none;">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">Bò Wagyu nướng</h3>
                            <span class="menu-item-price">1.200.000 VNĐ</span>
                        </div>
                        <p class="menu-item-desc">Thịt bò Wagyu A5 nướng tại bàn, phục vụ cùng rau củ nướng</p>
                    </div>

                    <div class="menu-item" data-category="main" style="display: none;">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">Tôm hùm Alaska nướng bơ</h3>
                            <span class="menu-item-price">980.000 VNĐ</span>
                        </div>
                        <p class="menu-item-desc">Tôm hùm tươi sống nướng bơ tỏi, phục vụ cùng khoai tây nghiền</p>
                    </div>

                    <!-- Desserts -->
                    <div class="menu-item" data-category="desserts" style="display: none;">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">Tiramisu truyền thống</h3>
                            <span class="menu-item-price">120.000 VNĐ</span>
                        </div>
                        <p class="menu-item-desc">Bánh Tiramisu Ý đích thực với mascarpone và cà phê espresso</p>
                    </div>

                    <div class="menu-item" data-category="desserts" style="display: none;">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">Chocolate lava cake</h3>
                            <span class="menu-item-price">140.000 VNĐ</span>
                        </div>
                        <p class="menu-item-desc">Bánh chocolate nóng với nhân chocolate chảy, kèm kem vanilla</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Tab -->
        <div id="events" class="tab-content">
            <div class="container">
                <div class="events-header">
                    <h2>Sự kiện & Band nhạc</h2>
                </div>

                <div class="band-schedule">
                    <h3 class="band-title">🎵 Live Music Performance</h3>
                    <div class="band-days">Thứ 3 & Thứ 7 hàng tuần</div>
                    <div class="band-time">20:00 - 23:00</div>
                </div>

                <div class="upcoming-events">
                    <div class="event-card">
                        <div class="event-date">
                            <i class="fas fa-calendar-alt"></i>
                            <span>15/06/2024</span>
                        </div>
                        <h3 class="event-title">Đêm nhạc Jazz cùng The Blue Notes</h3>
                        <p class="event-desc">Thưởng thức những giai điệu jazz mềm mại cùng ban nhạc chuyên nghiệp The Blue Notes trong không gian lãng mạn trên tầng cao.</p>
                    </div>

                    <div class="event-card">
                        <div class="event-date">
                            <i class="fas fa-calendar-alt"></i>
                            <span>22/06/2024</span>
                        </div>
                        <h3 class="event-title">Acoustic Night - Những bản tình ca bất hủ</h3>
                        <p class="event-desc">Cảm nhận âm nhạc acoustic trong trẻo với những ca khúc tình ca kinh điển được trình bày lại một cách sâu lắng.</p>
                    </div>

                    <div class="event-card">
                        <div class="event-date">
                            <i class="fas fa-calendar-alt"></i>
                            <span>29/06/2024</span>
                        </div>
                        <h3 class="event-title">Rock Night - Bùng nổ cảm xúc</h3>
                        <p class="event-desc">Đêm nhạc rock sôi động với những ca khúc kinh điển và hiện đại, mang đến năng lượng tích cực cho tất cả mọi người.</p>
                    </div>

                    <div class="event-card">
                        <div class="event-date">
                            <i class="fas fa-wine-glass"></i>
                            <span>Hàng tuần</span>
                        </div>
                        <h3 class="event-title">Happy Hour</h3>
                        <p class="event-desc">Thứ 2 - Thứ 6: 17:00 - 19:00. Giảm 30% tất cả đồ uống cocktail và 20% món khai vị. Không áp dụng cùng chương trình khác.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reservation Tab -->
        <div id="reservation" class="tab-content">
            <div class="container">
                <div class="reservation-container">
                    <h2 class="reservation-title">Đặt bàn tại Sky Bar</h2>
                    <form class="reservation-form" id="reservationForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Họ và tên *</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Số điện thoại *</label>
                                <input type="tel" id="phone" name="phone" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="date">Ngày đến *</label>
                                <input type="date" id="date" name="date" required>
                            </div>
                            <div class="form-group">
                                <label for="time">Giờ đến *</label>
                                <select id="time" name="time" required>
                                    <option value="">Chọn giờ</option>
                                    <option value="17:00">17:00</option>
                                    <option value="17:30">17:30</option>
                                    <option value="18:00">18:00</option>
                                    <option value="18:30">18:30</option>
                                    <option value="19:00">19:00</option>
                                    <option value="19:30">19:30</option>
                                    <option value="20:00">20:00</option>
                                    <option value="20:30">20:30</option>
                                    <option value="21:00">21:00</option>
                                    <option value="21:30">21:30</option>
                                    <option value="22:00">22:00</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="guests">Số lượng khách *</label>
                                <select id="guests" name="guests" required>
                                    <option value="">Chọn số lượng</option>
                                    <option value="1">1 người</option>
                                    <option value="2">2 người</option>
                                    <option value="3">3 người</option>
                                    <option value="4">4 người</option>
                                    <option value="5">5 người</option>
                                    <option value="6">6 người</option>
                                    <option value="7">7 người</option>
                                    <option value="8">8 người</option>
                                    <option value="9+">9+ người</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="occasion">Dịp đặc biệt</label>
                                <select id="occasion" name="occasion">
                                    <option value="">Chọn dịp (không bắt buộc)</option>
                                    <option value="birthday">Sinh nhật</option>
                                    <option value="anniversary">Kỷ niệm</option>
                                    <option value="business">Công tác</option>
                                    <option value="date">Hẹn hò</option>
                                    <option value="celebration">Celebration</option>
                                    <option value="other">Khác</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Để nhận thông tin xác nhận">
                        </div>

                        <div class="form-group">
                            <label for="requests">Yêu cầu đặc biệt</label>
                            <textarea id="requests" name="requests" rows="4" placeholder="Ví dụ: Bàn gần cửa sổ, dị ứng thực phẩm, trang trí sinh nhật..."></textarea>
                        </div>

                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i>
                            Gửi yêu cầu đặt bàn
                        </button>
                    </form>

                    <div class="contact-info1" style="margin-top: 40px; text-align: center; padding-top: 30px; border-top: 1px solid rgba(255, 215, 0, 0.2);">
                        <h3 style="color:rgb(209, 189, 78); margin-bottom: 20px;">Liên hệ trực tiếp</h3>
                        <div style="display: flex; justify-content: center; gap: 40px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-phone" style="color: #FFD700;"></i>
                                <span>+84 214 123 4567</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-clock" style="color:rgb(175, 165, 110);"></i>
                                <span>17:00 - 23:30 hàng ngày</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-map-marker-alt" style="color:rgb(199, 186, 116);"></i>
                                <span>Tầng 7, PCA Hotel, Lào Cai</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="reviews-section" id="reviews">
            <div class="container"> 
                <div class="reviews-header">
                    <h3>Đánh giá từ khách hàng</h3>
                </div>
                <div class="overall-rating">
                    <div class="rating-score">4.7</div>
                    <div class="rating-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="rating-count">(126 đánh giá)</div>
                </div>
                <div class="rating-breakdown">
                    <div class="rating-bar">
                        <div class="rating-label">5 sao</div>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: 70%"></div>
                        </div>
                        <div class="rating-percent">70%</div>
                    </div>
                    <div class="rating-bar">
                        <div class="rating-label">4 sao</div>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: 20%"></div>
                        </div>
                        <div class="rating-percent">20%</div>
                    </div>
                    <div class="rating-bar">
                        <div class="rating-label">3 sao</div>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: 8%"></div>
                        </div>
                        <div class="rating-percent">8%</div>
                    </div>
                    <div class="rating-bar">
                        <div class="rating-label">2 sao</div>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: 2%"></div>
                        </div>
                        <div class="rating-percent">2%</div>
                    </div>
                    <div class="rating-bar">
                        <div class="rating-label">1 sao</div>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: 0%"></div>
                        </div>
                        <div class="rating-percent">0%</div>
                    </div>
                </div>
                <div class="reviews-list">
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">N</div>
                                <div>
                                    <div class="reviewer-name">Nguyễn Minh Tâm</div>
                                    <div class="review-date">15/11/2024</div>
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
                            Sky Bar có không gian sang trọng với tầm nhìn tuyệt đẹp. Đồ uống ngon, đặc biệt là cocktail Sky Sunset. Dịch vụ chu đáo, rất phù hợp cho các buổi hẹn hò hoặc sự kiện. Tôi rất hài lòng!
                        </div>
                    </div>
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">L</div>
                                <div>
                                    <div class="reviewer-name">Lê Thị Hoa</div>
                                    <div class="review-date">08/11/2024</div>
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
                            Tổ chức sinh nhật tại Sky Bar, mọi thứ đều hoàn hảo. Không gian đẹp, band nhạc sống động, nhân viên nhiệt tình. Giá cả hợp lý với chất lượng dịch vụ.
                        </div>
                    </div>
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">T</div>
                                <div>
                                    <div class="reviewer-name">Trần Văn Đức</div>
                                    <div class="review-date">02/11/2024</div>
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
                            Vị trí Sky Bar thuận tiện, view thành phố rất đẹp. Thực đơn đồ uống đa dạng, đặc biệt cocktail Lào Cai Mist rất ngon. Sẽ quay lại vào tối nhạc Jazz!
                        </div>
                    </div>
                </div>
                <button class="show-more-reviews">
                    Xem thêm đánh giá
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="write-review-section">
                    <button class="write-review-btn">
                        Viết đánh giá của bạn
                        <i class="fas fa-pen"></i>
                    </button>
                    <div class="review-form-container" style="display: none;">
                        <h4>Chia sẻ trải nghiệm của bạn</h4>
                        <form class="review-form">
                            <div class="rating-input">
                                <label>Đánh giá của bạn:</label>
                                <div class="star-rating">
                                    <input type="radio" name="rating" id="star5" value="5" />
                                    <label for="star5" class="star fas fa-star"></label>
                                    <input type="radio" name="rating" id="star4" value="4" />
                                    <label for="star4" class="star fas fa-star"></label>
                                    <input type="radio" name="rating" id="star3" value="3" />
                                    <label for="star3" class="star fas fa-star"></label>
                                    <input type="radio" name="rating" id="star2" value="2" />
                                    <label for="star2" class="star fas fa-star"></label>
                                    <input type="radio" name="rating" id="star1" value="1" />
                                    <label for="star1" class="star fas fa-star"></label>
                                    <div class="rating-text">Chọn số sao</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="reviewer-name">Họ và tên:</label>
                                <input type="text" id="reviewer-name" name="name" required />
                            </div>
                            <div class="form-group">
                                <label for="review-content">Nội dung đánh giá:</label>
                                <textarea id="review-content" name="content" required></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="cancel-btn">Hủy</button>
                                <button type="submit" class="submit-review-btn">
                                    Gửi đánh giá
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "footer.php"; ?>
    <script src="/libertylaocai/view/js/bar.js"></script>
</body>
</html>