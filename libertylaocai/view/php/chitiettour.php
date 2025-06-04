<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Sapa - Liberty Lào Cai</title>
    <link rel="stylesheet" href="/libertylaocai/view/css/chitiettour.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
        <?php include "header.php"; ?>

    <div class= "big-container">
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-slider">
                <div class="slide active">
                    <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=1200&h=800&fit=crop" alt="Fansipan Summit">
                    <div class="slide-content">
                        <h1>Tour Sapa - Nóc Nhà Đông Dương</h1>
                        <p>Chinh phục đỉnh Fansipan và khám phá văn hóa dân tộc độc đáo</p>
                    </div>
                </div>
                <div class="slide">
                    <img src="https://images.unsplash.com/photo-1509233725247-49e657c54213?w=1200&h=800&fit=crop" alt="Sapa Rice Terraces">
                    <div class="slide-content">
                        <h1>Ruộng Bậc Thang Sapa</h1>
                        <p>Khám phá vẻ đẹp hùng vĩ của ruộng bậc thang vàng óng</p>
                    </div>
                </div>
                <div class="slide">
                    <img src="https://images.unsplash.com/photo-1570197788417-0e82375c9371?w=1200&h=800&fit=crop" alt="Cat Cat Village">
                    <div class="slide-content">
                        <h1>Bản Cát Cát</h1>
                        <p>Trải nghiệm văn hóa và đời sống người dân tộc H'Mông</p>
                    </div>
                </div>
            </div>
            <div class="hero-nav">
                <button class="nav-btn prev" onclick="changeSlide(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="nav-btn next" onclick="changeSlide(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="hero-dots">
                <span class="dot active" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
            </div>
        </section>

        <!-- Tour Info -->
        <section class="tour-info">
            <div class="container">
                <div class="tour-header">
                    <div class="tour-title">
                        <h1>Tour Sapa - Chinh Phục Nóc Nhà Đông Dương</h1>
                        <div class="tour-rating">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="rating-text">4.8/5 (126 đánh giá)</span>
                        </div>
                    </div>
                    <div class="tour-price">
                        <div class="price-old">2,500,000 VNĐ</div>
                        <div class="price-current">1,890,000 VNĐ</div>
                        <div class="price-note">/ khách</div>
                    </div>
                </div>

                <div class="tour-quick-info">
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>Thời gian</strong>
                            <span>2 ngày 1 đêm</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-users"></i>
                        <div>
                            <strong>Số người</strong>
                            <span>2-15 khách</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Khởi hành</strong>
                            <span>Lào Cai</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-car"></i>
                        <div>
                            <strong>Phương tiện</strong>
                            <span>Xe du lịch</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tour Content -->
        <section class="tour-content">
            <div class="container">
                <div class="content-grid">
                    <div class="main-content">
                        <!-- Tabs -->
                        <div class="tabs">
                            <button class="tab-btn active" onclick="openTab(event, 'overview')">
                                <i class="fas fa-eye"></i> Tổng quan
                            </button>
                            <button class="tab-btn" onclick="openTab(event, 'itinerary')">
                                <i class="fas fa-route"></i> Lịch trình
                            </button>
                            <button class="tab-btn" onclick="openTab(event, 'included')">
                                <i class="fas fa-check-circle"></i> Bao gồm
                            </button>
                            <button class="tab-btn" onclick="openTab(event, 'gallery')">
                                <i class="fas fa-images"></i> Hình ảnh
                            </button>
                        </div>

                        <!-- Tab Contents -->
                        <div id="overview" class="tab-content active">
                            <h2>Điểm nổi bật của tour</h2>
                            <div class="highlights">
                                <div class="highlight-item">
                                    <i class="fas fa-mountain"></i>
                                    <div>
                                        <h3>Chinh phục đỉnh Fansipan</h3>
                                        <p>Đứng trên đỉnh cao nhất Đông Dương (3,143m), ngắm nhìn toàn cảnh núi rừng Tây Bắc hùng vĩ</p>
                                    </div>
                                </div>
                                <div class="highlight-item">
                                    <i class="fas fa-home"></i>
                                    <div>
                                        <h3>Khám phá bản Cát Cát</h3>
                                        <p>Tìm hiểu văn hóa, đời sống của người dân tộc H'Mông qua các hoạt động thủ công truyền thống</p>
                                    </div>
                                </div>
                                <div class="highlight-item">
                                    <i class="fas fa-seedling"></i>
                                    <div>
                                        <h3>Ruộng bậc thang Sapa</h3>
                                        <p>Chiêm ngưỡng vẻ đẹp ngoạn mục của những thửa ruộng bậc thang vàng óng mùa lúa chín</p>
                                    </div>
                                </div>
                                <div class="highlight-item">
                                    <i class="fas fa-utensils"></i>
                                    <div>
                                        <h3>Ẩm thực đặc sản</h3>
                                        <p>Thưởng thức các món ăn đặc trưng vùng cao như thịt trâu gác bếp, cơm lam, rượu cần...</p>
                                    </div>
                                </div>
                            </div>

                            <div class="description">
                                <h2>Mô tả tour</h2>
                                <p>Tour Sapa 2 ngày 1 đêm là hành trình khám phá vẻ đẹp hùng vĩ của vùng núi Tây Bắc, nơi hội tụ những cảnh quan thiên nhiên ngoạn mục và nền văn hóa dân tộc đa dạng, phong phú.</p>
                                
                                <p>Điểm nhấn của tour là việc chinh phục đỉnh Fansipan - "Nóc nhà Đông Dương" với độ cao 3,143m. Từ đây, du khách có thể ngắm nhìn toàn cảnh dãy Hoàng Liên Sơn hùng vĩ và cảm nhận sự tĩnh lặng, thiêng liêng của núi rừng Tây Bắc.</p>

                                <p>Bên cạnh đó, tour cũng đưa du khách đến với bản Cát Cát - một trong những bản làng đẹp nhất Sapa, nơi bảo tồn văn hóa truyền thống của người dân tộc H'Mông. Tại đây, du khách có cơ hội tìm hiểu về đời sống, phong tục tập quán và các nghề thủ công truyền thống như dệt vải, chế tác bạc...</p>
                            </div>
                        </div>

                        <div id="itinerary" class="tab-content">
                            <h2>Lịch trình chi tiết</h2>
                            <div class="itinerary-timeline">
                                <div class="day-item">
                                    <div class="day-header">
                                        <div class="day-number">1</div>
                                        <h3>NGÀY 1: LÀNH CÁI - SAPA - FANSIPAN</h3>
                                    </div>
                                    <div class="day-schedule">
                                        <div class="schedule-item">
                                            <div class="time">07:00</div>
                                            <div class="activity">
                                                <h4>Khởi hành từ Lào Cai</h4>
                                                <p>Xe đón tại khách sạn Liberty Lào Cai, di chuyển lên Sapa (45 phút)</p>
                                            </div>
                                        </div>
                                        <div class="schedule-item">
                                            <div class="time">08:30</div>
                                            <div class="activity">
                                                <h4>Chinh phục đỉnh Fansipan</h4>
                                                <p>Đi cáp treo Muong Hoa lên ga Trạm Tôn, sau đó đi cáp treo Fansipan lên đỉnh</p>
                                            </div>
                                        </div>
                                        <div class="schedule-item">
                                            <div class="time">12:00</div>
                                            <div class="activity">
                                                <h4>Ăn trưa tại nhà hàng</h4>
                                                <p>Thưởng thức các món ăn đặc sản vùng cao</p>
                                            </div>
                                        </div>
                                        <div class="schedule-item">
                                            <div class="time">14:00</div>
                                            <div class="activity">
                                                <h4>Tham quan bản Cát Cát</h4>
                                                <p>Khám phá văn hóa dân tộc H'Mông, xem các hoạt động thủ công truyền thống</p>
                                            </div>
                                        </div>
                                        <div class="schedule-item">
                                            <div class="time">18:00</div>
                                            <div class="activity">
                                                <h4>Nhận phòng khách sạn</h4>
                                                <p>Check-in khách sạn, ăn tối và nghỉ ngơi</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="day-item">
                                    <div class="day-header">
                                        <div class="day-number">2</div>
                                        <h3>NGÀY 2: SAPA - NÚI HÀM RỒNG - LÀNH CÁI</h3>
                                    </div>
                                    <div class="day-schedule">
                                        <div class="schedule-item">
                                            <div class="time">08:00</div>
                                            <div class="activity">
                                                <h4>Ăn sáng tại khách sạn</h4>
                                                <p>Thưởng thức buffet sáng phong phú</p>
                                            </div>
                                        </div>
                                        <div class="schedule-item">
                                            <div class="time">09:00</div>
                                            <div class="activity">
                                                <h4>Tham quan núi Hàm Rồng</h4>
                                                <p>Ngắm hoa đỗ quyên, lan rừng và toàn cảnh thị trấn Sapa</p>
                                            </div>
                                        </div>
                                        <div class="schedule-item">
                                            <div class="time">11:00</div>
                                            <div class="activity">
                                                <h4>Mua sắm tại chợ Sapa</h4>
                                                <p>Tự do mua sắm đặc sản, lưu niệm</p>
                                            </div>
                                        </div>
                                        <div class="schedule-item">
                                            <div class="time">12:00</div>
                                            <div class="activity">
                                                <h4>Ăn trưa và trả phòng</h4>
                                                <p>Dùng bữa trưa cuối cùng tại Sapa</p>
                                            </div>
                                        </div>
                                        <div class="schedule-item">
                                            <div class="time">14:00</div>
                                            <div class="activity">
                                                <h4>Về Lào Cai</h4>
                                                <p>Di chuyển về Lào Cai, kết thúc tour</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="included" class="tab-content">
                            <div class="included-grid">
                                <div class="included-section">
                                    <h3><i class="fas fa-check-circle text-green"></i> Bao gồm</h3>
                                    <ul class="included-list">
                                        <li><i class="fas fa-check"></i> Xe du lịch đời mới, máy lạnh</li>
                                        <li><i class="fas fa-check"></i> HDV theo suốt tuyến</li>
                                        <li><i class="fas fa-check"></i> Khách sạn 3* (2-3 người/phòng)</li>
                                        <li><i class="fas fa-check"></i> Ăn theo chương trình</li>
                                        <li><i class="fas fa-check"></i> Vé tham quan theo CT</li>
                                        <li><i class="fas fa-check"></i> Vé cáp treo Fansipan</li>
                                        <li><i class="fas fa-check"></i> Bảo hiểm du lịch</li>
                                        <li><i class="fas fa-check"></i> Nước suối (1 chai/người/ngày)</li>
                                    </ul>
                                </div>
                                <div class="excluded-section">
                                    <h3><i class="fas fa-times-circle text-red"></i> Không bao gồm</h3>
                                    <ul class="excluded-list">
                                        <li><i class="fas fa-times"></i> Chi phí cá nhân</li>
                                        <li><i class="fas fa-times"></i> Đồ uống có cồn</li>
                                        <li><i class="fas fa-times"></i> Tip HDV, tài xế</li>
                                        <li><i class="fas fa-times"></i> Phụ thu phòng đơn</li>
                                        <li><i class="fas fa-times"></i> Chi phí ngoài chương trình</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="notes-section">
                                <h3>Lưu ý quan trọng</h3>
                                <div class="note-item">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <p><strong>Thời tiết:</strong> Nên mang theo áo ấm, đặc biệt vào mùa đông. Thời tiết miền núi thay đổi thất thường.</p>
                                </div>
                                <div class="note-item">
                                    <i class="fas fa-hiking"></i>
                                    <p><strong>Trang phục:</strong> Nên mặc giày thoải mái để đi bộ, mang theo áo mưa.</p>
                                </div>
                                <div class="note-item">
                                    <i class="fas fa-camera"></i>
                                    <p><strong>Chụp ảnh:</strong> Tôn trọng phong tục địa phương khi chụp ảnh với người dân tộc.</p>
                                </div>
                            </div>
                        </div>

                        <div id="gallery" class="tab-content">
                            <h2>Thư viện ảnh</h2>
                            <div class="gallery-grid">
                                <div class="gallery-item" onclick="openModal('https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop')">
                                    <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=300&fit=crop" alt="Fansipan Peak">
                                </div>
                                <div class="gallery-item" onclick="openModal('https://images.unsplash.com/photo-1509233725247-49e657c54213?w=800&h=600&fit=crop')">
                                    <img src="https://images.unsplash.com/photo-1509233725247-49e657c54213?w=400&h=300&fit=crop" alt="Rice Terraces">
                                </div>
                                <div class="gallery-item" onclick="openModal('https://images.unsplash.com/photo-1570197788417-0e82375c9371?w=800&h=600&fit=crop')">
                                    <img src="https://images.unsplash.com/photo-1570197788417-0e82375c9371?w=400&h=300&fit=crop" alt="Cat Cat Village">
                                </div>
                                <div class="gallery-item" onclick="openModal('https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=800&h=600&fit=crop')">
                                    <img src="https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=400&h=300&fit=crop" alt="Local Culture">
                                </div>
                                <div class="gallery-item" onclick="openModal('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop')">
                                    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop" alt="Mountain View">
                                </div>
                                <div class="gallery-item" onclick="openModal('https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop')">
                                    <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=400&h=300&fit=crop" alt="Landscape">
                                </div>
                            </div>
                        </div>
                        <div class="reviews-section">
                            <div class="reviews-header">
                                <h3>Đánh giá từ khách hàng</h3>
                                <div class="overall-rating">
                                    <span class="rating-score">4.8</span>
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span class="rating-count">(126 đánh giá)</span>
                                </div>
                                <div class="rating-breakdown">
                                    <div class="rating-bar">
                                        <span class="rating-label">5 sao</span>
                                        <div class="bar-container">
                                            <div class="bar-fill" style="width: 70%"></div>
                                        </div>
                                        <span class="rating-percent">70%</span>
                                    </div>
                                    <div class="rating-bar">
                                        <span class="rating-label">4 sao</span>
                                        <div class="bar-container">
                                            <div class="bar-fill" style="width: 20%"></div>
                                        </div>
                                        <span class="rating-percent">20%</span>
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
                            </div>

                            <div class="reviews-list">
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="reviewer-info">
                                            <div class="reviewer-avatar">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="reviewer-details">
                                                <div class="reviewer-name">Nguyễn Văn A</div>
                                                <div class="review-date">20/05/2024</div>
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
                                        <p>Tour rất tuyệt vời! Hướng dẫn viên nhiệt tình, lịch trình hợp lý, cảnh đẹp không thể chê. Đỉnh Fansipan là điểm nhấn đáng nhớ!</p>
                                    </div>
                                </div>
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="reviewer-info">
                                            <div class="reviewer-avatar">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="reviewer-details">
                                                <div class="reviewer-name">Trần Thị B</div>
                                                <div class="review-date">15/05/2024</div>
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
                                        <p>Bản Cát Cát rất thú vị, nhưng thời gian tham quan hơi ngắn. Đồ ăn ngon, xe di chuyển thoải mái. Nhìn chung là hài lòng!</p>
                                    </div>
                                </div>
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="reviewer-info">
                                            <div class="reviewer-avatar">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="reviewer-details">
                                                <div class="reviewer-name">Lê Minh C</div>
                                                <div class="review-date">10/05/2024</div>
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
                                        <p>Hành trình chinh phục Fansipan đáng nhớ, cáp treo hiện đại và an toàn. Hướng dẫn viên rất thân thiện và am hiểu văn hóa địa phương.</p>
                                    </div>
                                </div>
                            </div>

                            <button class="show-more-reviews"><i class="fas fa-chevron-down"></i> Xem thêm đánh giá</button>

                            <div class="write-review-section">
                                <button class="write-review-btn"><i class="fas fa-pen"></i> Viết đánh giá của bạn</button>
                                <div class="review-form-container" id="reviewForm" style="display: none;">
                                    <h4>Chia sẻ trải nghiệm của bạn</h4>
                                    <form class="review-form">
                                        <div class="form-group rating-input">
                                            <label>Đánh giá của bạn:</label>
                                            <div class="star-rating">
                                                <input type="radio" name="rating" value="5" id="star5">
                                                <label for="star5" class="star"><i class="fas fa-star"></i></label>
                                                <input type="radio" name="rating" value="4" id="star4">
                                                <label for="star4" class="star"><i class="fas fa-star"></i></label>
                                                <input type="radio" name="rating" value="3" id="star3">
                                                <label for="star3" class="star"><i class="fas fa-star"></i></label>
                                                <input type="radio" name="rating" value="2" id="star2">
                                                <label for="star2" class="star"><i class="fas fa-star"></i></label>
                                                <input type="radio" name="rating" value="1" id="star1">
                                                <label for="star1" class="star"><i class="fas fa-star"></i></label>
                                            </div>
                                            <div class="rating-text">Chọn số sao</div>
                                        </div>
                                        <div class="form-group">
                                            <label for="reviewer-name">Họ và tên:</label>
                                            <input type="text" id="reviewer-name" name="reviewer-name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="review-content">Nội dung đánh giá:</label>
                                            <textarea id="review-content" name="review-content" required></textarea>
                                        </div>
                                        <div class="form-actions">
                                            <button type="button" class="cancel-btn">Hủy</button>
                                            <button type="submit" class="submit-review-btn"><i class="fas fa-paper-plane"></i> Gửi đánh giá</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="sidebar">
                        <div class="booking-card">
                            <div class="booking-header">
                                <h3>Đặt Tour Ngay</h3>
                                <div class="price-display">
                                    <span class="price">1,890,000 VNĐ</span>
                                    <span class="per-person">/khách</span>
                                </div>
                            </div>
                            
                            <form class="booking-form" id="bookingForm">
                                <div class="form-group">
                                    <label>Họ và tên *</label>
                                    <input type="text" id="fullName" placeholder="Nhập họ và tên của bạn" required>
                                </div>
                                <div class="form-group">
                                    <label>Số điện thoại *</label>
                                    <input type="tel" id="phoneNumber" placeholder="Nhập số điện thoại" required>
                                </div>
                                <div class="form-group">
                                    <label>Ngày khởi hành *</label>
                                    <input type="date" id="departureDate" required>
                                </div>
                                <div class="form-group">
                                    <label>Số khách *</label>
                                    <select id="guestCount" required>
                                        <option value="">Chọn số khách</option>
                                        <option value="1">1 khách</option>
                                        <option value="2">2 khách</option>
                                        <option value="3">3 khách</option>
                                        <option value="4">4 khách</option>
                                        <option value="5+">5+ khách</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Ghi chú (tùy chọn)</label>
                                    <textarea id="note" rows="3" placeholder="Yêu cầu đặc biệt hoặc ghi chú khác..."></textarea>
                                </div>
                                <div class="total-price">
                                    <span>Tổng cộng: </span>
                                    <span id="totalPrice">1,890,000 VNĐ</span>
                                </div>
                                <button type="submit" class="btn-book">
                                    <i class="fas fa-calendar-check"></i>
                                    Đặt Tour Ngay
                                </button>
                            </form>
                            <div class="contact-info">
                                <h4>Cần hỗ trợ?</h4>
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span>0214 366 1666</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>chamsockhachhang.liberty@gmail.com</span>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="related-tours">
                            <h3>Tour liên quan</h3>
                            <div class="related-tour-item">
                                <img src="https://images.unsplash.com/photo-1564507592333-c60657eea523?w=200&h=150&fit=crop" alt="Bac Ha Tour">
                                <div class="tour-info">
                                    <h4>Tour Bắc Hà</h4>
                                    <p class="tour-price">1,200,000 VNĐ</p>
                                </div>
                            </div>
                            <div class="related-tour-item">
                                <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=200&h=150&fit=crop" alt="Y Ty Tour">
                                <div class="tour-info">
                                    <h4>Tour Y Tý</h4>
                                    <p class="tour-price">2,100,000 VNĐ</p>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Modal -->
        <div id="imageModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <img id="modalImage" src="" alt="">
            </div>
        </div>
    </div>
        <?php include "footer.php"; ?>

    <script src="/libertylaocai/view/js/chitiettour.js"></script>
</body>
</html>