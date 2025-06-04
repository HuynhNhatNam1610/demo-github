<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới thiệu - Khách sạn Liberty Lào Cai</title>
    <link rel="stylesheet" href="/libertylaocai/view/css/gioithieu.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include "header.php"; ?>
    <div class ="big-container">
    <!-- Hero Video Section -->
    <section class="hero-video">
        <div class="video-container">
            <video autoplay muted loop id="heroVideo">
                <source src="/libertylaocai/view/video/video.mp4" type="video/mp4">
            </video>
            <div class="video-overlay"></div>
        </div>
        <div class="hero-content">
            <h1 class="hero-title">Khách sạn Liberty Lào Cai</h1>
            <p class="hero-subtitle">Không gian giao thoa giữa sự tiện nghi, hiện đại và những trải nghiệm đậm chất bản địa</p>
            <div class="hero-cta">
                <button class="btn-primary">Khám phá ngay</button>
            </div>
        </div>
    </section>

    <!-- Welcome Section -->
    <section class="welcome-section">
        <div class="container">
            <div class="welcome-content">
                <div class="section-header">
                    <h2>Chào mừng đến với Liberty Lào Cai</h2>
                    <div class="divider"></div>
                </div>
                <div class="welcome-grid">
                    <div class="welcome-text">
                        <p class="lead">Tọa lạc tại vị trí đắc địa bên dòng sông Hồng thơ mộng, Khách sạn Liberty Lào Cai không chỉ mang đến không gian lưu trú ấm cúng mà còn cung cấp đa dạng các dịch vụ đẳng cấp.</p>
                        <p>Từ hệ thống phòng nghỉ sang trọng, nhà hàng ẩm thực tinh tế, quầy bar phong cách đến trung tâm hội nghị - tiệc cưới ấn tượng, dịch vụ hỗ trợ cấp giấy thông hành xuất, nhập cảnh nhanh chóng và nhiều chương trình du lịch khám phá những danh thắng tuyệt đẹp của hai quốc gia Việt - Trung.</p>
                        <div class="motto">
                            <i class="fas fa-quote-left"></i>
                            <span>Tận tâm, Chuyên nghiệp và Đẳng cấp</span>
                            <i class="fas fa-quote-right"></i>
                        </div>
                    </div>
                    <div class="welcome-stats">
                        <div class="stat-item">
                            <div class="stat-number">45</div>
                            <div class="stat-label">Phòng nghỉ</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">3</div>
                            <div class="stat-label">Sao quốc tế</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">500</div>
                            <div class="stat-label">Sức chứa hội nghị</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">700</div>
                            <div class="stat-label">Chỗ ngồi nhà hàng</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section">
        <div class="container">
            <div class="section-header">
                <h2>Dịch vụ của chúng tôi</h2>
                <div class="divider"></div>
                <p>Trải nghiệm đẳng cấp với đa dạng các dịch vụ chất lượng cao</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-bed"></i>
                    </div>
                    <h3>Dịch vụ lưu trú</h3>
                    <p>Hệ thống phòng nghỉ sang trọng với đầy đủ tiện nghi: máy điều hòa, TV, WiFi tốc độ cao. Từ phòng Deluxe đến căn hộ gia đình, mang đến sự thoải mái tuyệt đối.</p>
                    <ul class="service-features">
                        <li>Phòng Deluxe với view thành phố</li>
                        <li>Căn hộ gia đình rộng rãi</li>
                        <li>Đầy đủ tiện nghi hiện đại</li>
                    </ul>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3>Tổ chức sự kiện</h3>
                    <p>Không gian lý tưởng để tổ chức các sự kiện quan trọng với hệ thống phòng hội nghị hiện đại, sức chứa từ 20 đến 500 khách.</p>
                    <ul class="service-features">
                        <li>Hội thảo, hội nghị chuyên nghiệp</li>
                        <li>Tiệc cưới, sinh nhật sang trọng</li>
                        <li>Sự kiện doanh nghiệp đa dạng</li>
                    </ul>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h3>Nhà hàng & Bar</h3>
                    <p>Thưởng thức những món ăn đặc sản miền Tây Bắc và thư giãn tại Sky Bar với không gian lãng mạn nhìn ra toàn thành phố.</p>
                    <ul class="service-features">
                        <li>Đặc sản miền Tây Bắc</li>
                        <li>Sky Bar view toàn thành phố</li>
                        <li>Band nhạc hàng tuần</li>
                    </ul>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-passport"></i>
                    </div>
                    <h3>Hỗ trợ giấy thông hành</h3>
                    <p>Hỗ trợ làm giấy thông hành du lịch Trung Quốc với quy trình đơn giản, nhanh chóng và đúng quy định.</p>
                    <ul class="service-features">
                        <li>Quy trình nhanh chóng</li>
                        <li>Đúng quy định pháp luật</li>
                        <li>Hỗ trợ tư vấn miễn phí</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Room Types Section -->
    <section class="rooms-section">
        <div class="container">
            <div class="section-header">
                <h2>Loại phòng</h2>
                <div class="divider"></div>
                <p>Lựa chọn hoàn hảo cho mọi nhu cầu lưu trú</p>
            </div>
            <div class="rooms-grid">
                <div class="room-card">
                    <div class="room-image">
                        <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=500&h=300&fit=crop" alt="Deluxe Double">
                        <div class="room-price">700.000đ</div>
                    </div>
                    <div class="room-content">
                        <h3>Phòng Deluxe Double</h3>
                        <p>Thiết kế tinh tế với không gian rộng rãi 34m², cửa sổ lớn đón ánh sáng tự nhiên.</p>
                        <div class="room-features">
                            <span><i class="fas fa-user-friends"></i> 2 khách</span>
                            <span><i class="fas fa-bed"></i> 1 giường đôi</span>
                            <span><i class="fas fa-expand"></i> 34m²</span>
                        </div>
                    </div>
                </div>
                
                <div class="room-card">
                    <div class="room-image">
                        <img src="https://images.unsplash.com/photo-1586985289688-ca3cf47d3e6e?w=500&h=300&fit=crop" alt="Deluxe Twin">
                        <div class="room-price">800.000đ</div>
                    </div>
                    <div class="room-content">
                        <h3>Phòng Deluxe Twin</h3>
                        <p>Không gian thoải mái với 2 giường đơn, phù hợp cho bạn bè hoặc đồng nghiệp.</p>
                        <div class="room-features">
                            <span><i class="fas fa-user-friends"></i> 2 khách</span>
                            <span><i class="fas fa-bed"></i> 2 giường đơn</span>
                            <span><i class="fas fa-expand"></i> 34m²</span>
                        </div>
                    </div>
                </div>
                
                <div class="room-card">
                    <div class="room-image">
                        <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=500&h=300&fit=crop" alt="Triple Room">
                        <div class="room-price">1.100.000đ</div>
                    </div>
                    <div class="room-content">
                        <h3>Phòng Triple</h3>
                        <p>Lựa chọn lý tưởng cho gia đình nhỏ hoặc nhóm bạn với 3 giường ngủ thoải mái.</p>
                        <div class="room-features">
                            <span><i class="fas fa-user-friends"></i> 3 khách</span>
                            <span><i class="fas fa-bed"></i> 3 giường</span>
                            <span><i class="fas fa-expand"></i> 36m²</span>
                        </div>
                    </div>
                </div>
                
                <div class="room-card">
                    <div class="room-image">
                        <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?w=500&h=300&fit=crop" alt="Family Suite">
                        <div class="room-price">1.300.000đ</div>
                    </div>
                    <div class="room-content">
                        <h3>Căn hộ gia đình</h3>
                        <p>Không gian rộng rãi 69m² với 2 phòng ngủ và 1 phòng khách riêng biệt.</p>
                        <div class="room-features">
                            <span><i class="fas fa-user-friends"></i> 4-6 khách</span>
                            <span><i class="fas fa-home"></i> 2 phòng ngủ</span>
                            <span><i class="fas fa-expand"></i> 69m²</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tours Section -->
    <section class="tours-section">
        <div class="container">
            <div class="section-header">
                <h2>Tour du lịch</h2>
                <div class="divider"></div>
                <p>Khám phá vẻ đẹp hùng vĩ của Tây Bắc cùng Liberty Lào Cai</p>
            </div>
            <div class="tours-grid">
                <div class="tour-card">
                    <div class="tour-image">
                        <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=250&fit=crop" alt="Tour Sapa">
                    </div>
                    <div class="tour-content">
                        <h3>Tour Sapa</h3>
                        <p>Chinh phục đỉnh Fansipan - nóc nhà Đông Dương, tham quan bản Cát Cát, núi Hàm Rồng và trải nghiệm văn hóa dân tộc.</p>
                        <div class="tour-highlights">
                            <span>Đỉnh Fansipan</span>
                            <span>Bản Cát Cát</span>
                            <span>Núi Hàm Rồng</span>
                        </div>
                    </div>
                </div>
                
                <div class="tour-card">
                    <div class="tour-image">
                        <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=400&h=250&fit=crop" alt="Tour Bắc Hà">
                    </div>
                    <div class="tour-content">
                        <h3>Tour Bắc Hà</h3>
                        <p>Ghé thăm chợ phiên Bắc Hà nổi tiếng, dinh Hoàng A Tưởng và thưởng thức đặc sản vùng cao.</p>
                        <div class="tour-highlights">
                            <span>Chợ phiên Bắc Hà</span>
                            <span>Dinh Hoàng A Tưởng</span>
                            <span>Đặc sản vùng cao</span>
                        </div>
                    </div>
                </div>
                
                <div class="tour-card">
                    <div class="tour-image">
                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=250&fit=crop" alt="Tour Y Tý">
                    </div>
                    <div class="tour-content">
                        <h3>Tour Y Tý</h3>
                        <p>Săn mây trên đỉnh trời Tây Bắc, vùng đất ở độ cao hơn 2.000m với khung cảnh thiên nhiên hùng vĩ như chốn bồng lai.</p>
                        <div class="tour-highlights">
                            <span>Săn mây</span>
                            <span>Độ cao 2000m</span>
                            <span>Thiên nhiên hùng vĩ</span>
                        </div>
                    </div>
                </div>
                
                <div class="tour-card">
                    <div class="tour-image">
                        <img src="https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=400&h=250&fit=crop" alt="Tour Hà Khẩu">
                    </div>
                    <div class="tour-content">
                        <h3>Tour Hà Khẩu - Trung Quốc</h3>
                        <p>Dạo quanh thị trấn Hà Khẩu sầm uất, thưởng thức ẩm thực Trung Hoa và mua sắm thỏa thích.</p>
                        <div class="tour-highlights">
                            <span>Thị trấn Hà Khẩu</span>
                            <span>Ẩm thực Trung Hoa</span>
                            <span>Mua sắm</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-info">
                    <h2>Thông tin liên hệ</h2>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Địa chỉ</h4>
                            <p>120 Đường Soi Tiền, Phường Kim Tân, TP. Lào Cai, Tỉnh Lào Cai</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Điện thoại</h4>
                            <p>0214 366 1666</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Email</h4>
                            <p>chamsockhachhang.liberty@gmail.com</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fab fa-facebook"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Facebook</h4>
                            <p>Liberty Hotel & Events Khách sạn Liberty Lào Cai</p>
                        </div>
                    </div>
                </div>
                
                <div class="location-info">
                    <h3>Vị trí thuận lợi</h3>
                    <ul class="location-list">
                        <li><i class="fas fa-shopping-cart"></i> Cách chợ Cốc Lếu: 800m</li>
                        <li><i class="fas fa-border-style"></i> Cách cửa khẩu: 1km</li>
                        <li><i class="fas fa-car"></i> Bãi đỗ xe rộng: 300m²</li>
                        <li><i class="fas fa-plane"></i> Dịch vụ đưa đón sân bay Nội Bài</li>
                    </ul>
                    
                    <div class="certificates">
                        <h4>Chứng nhận chất lượng</h4>
                        <div class="cert-badge">
                            <i class="fas fa-star"></i>
                            <span>Khách sạn 3 sao quốc tế</span>
                        </div>
                        <p>Chứng nhận số 31/QĐ-SDL</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="quality-recognition-section">
        <div class="container">
            <h2 class="quality-title">CHẤT LƯỢNG ĐƯỢC CÔNG NHẬN</h2>
            <p class="quality-subtitle">CAM KẾT MANG ĐẾN DỊCH VỤ VÀ TIỆN NGHI ĐẠT CHUẨN</p>
            
            <!-- Desktop Grid -->
            <div class="quality-grid">
                <!-- Certificate 1 -->
                <div class="quality-item">
                    <div class="quality-card">
                        <div class="quality-icon">
                            <img src="https://cdn-icons-png.flaticon.com/512/2534/2534204.png" alt="3 Star Certificate">
                        </div>
                        <div class="quality-footer">
                            <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                            <div class="certified-badge">
                                <span class="year">2021</span>
                                <span class="certified-text">CERTIFIED</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificate 2 -->
                <div class="quality-item">
                    <div class="quality-card">
                        <div class="quality-icon booking-icon">
                            <img src="https://logos-world.net/wp-content/uploads/2021/08/Booking-Logo.png" alt="Booking.com">
                        </div>
                        <div class="quality-footer">
                            <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                            <div class="certified-badge">
                                <span class="year">2021</span>
                                <span class="certified-text">CERTIFIED</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificate 3 -->
                <div class="quality-item">
                    <div class="quality-card">
                        <div class="quality-icon">
                            <img src="https://cdn-icons-png.flaticon.com/512/1055/1055687.png" alt="Business License">
                        </div>
                        <div class="quality-footer">
                            <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                            <div class="certified-badge">
                                <span class="year">2021</span>
                                <span class="certified-text">CERTIFIED</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificate 4 -->
                <div class="quality-item">
                    <div class="quality-card">
                        <div class="quality-icon">
                            <img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png" alt="Fire Safety Certificate">
                        </div>
                        <div class="quality-footer">
                            <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                            <div class="certified-badge">
                                <span class="year">2021</span>
                                <span class="certified-text">CERTIFIED</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificate 5 -->
                <div class="quality-item">
                    <div class="quality-card">
                        <div class="quality-icon">
                            <img src="https://cdn-icons-png.flaticon.com/512/2921/2921222.png" alt="Food Safety Certificate">
                        </div>
                        <div class="quality-footer">
                            <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                            <div class="certified-badge">
                                <span class="year">2021</span>
                                <span class="certified-text">CERTIFIED</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Carousel -->
            <div class="quality-carousel">
                <div class="carousel-track">
                    <!-- Original set -->
                    <div class="quality-item">
                        <div class="quality-card">
                            <div class="quality-icon">
                                <img src="https://cdn-icons-png.flaticon.com/512/2534/2534204.png" alt="3 Star Certificate">
                            </div>
                            <div class="quality-footer">
                                <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                                <div class="certified-badge">
                                    <span class="year">2021</span>
                                    <span class="certified-text">CERTIFIED</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="quality-item">
                        <div class="quality-card">
                            <div class="quality-icon booking-icon">
                                <img src="https://logos-world.net/wp-content/uploads/2021/08/Booking-Logo.png" alt="Booking.com">
                            </div>
                            <div class="quality-footer">
                                <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                                <div class="certified-badge">
                                    <span class="year">2021</span>
                                    <span class="certified-text">CERTIFIED</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="quality-item">
                        <div class="quality-card">
                            <div class="quality-icon">
                                <img src="https://cdn-icons-png.flaticon.com/512/1055/1055687.png" alt="Business License">
                            </div>
                            <div class="quality-footer">
                                <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                                <div class="certified-badge">
                                    <span class="year">2021</span>
                                    <span class="certified-text">CERTIFIED</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="quality-item">
                        <div class="quality-card">
                            <div class="quality-icon">
                                <img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png" alt="Fire Safety Certificate">
                            </div>
                            <div class="quality-footer">
                                <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                                <div class="certified-badge">
                                    <span class="year">2021</span>
                                    <span class="certified-text">CERTIFIED</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="quality-item">
                        <div class="quality-card">
                            <div class="quality-icon">
                                <img src="https://cdn-icons-png.flaticon.com/512/2921/2921222.png" alt="Food Safety Certificate">
                            </div>
                            <div class="quality-footer">
                                <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                                <div class="certified-badge">
                                    <span class="year">2021</span>
                                    <span class="certified-text">CERTIFIED</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Duplicate set for seamless loop -->
                    <div class="quality-item">
                        <div class="quality-card">
                            <div class="quality-icon">
                                <img src="https://cdn-icons-png.flaticon.com/512/2534/2534204.png" alt="3 Star Certificate">
                            </div>
                            <div class="quality-footer">
                                <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                                <div class="certified-badge">
                                    <span class="year">2021</span>
                                    <span class="certified-text">CERTIFIED</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="quality-item">
                        <div class="quality-card">
                            <div class="quality-icon booking-icon">
                                <img src="https://logos-world.net/wp-content/uploads/2021/08/Booking-Logo.png" alt="Booking.com">
                            </div>
                            <div class="quality-footer">
                                <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                                <div class="certified-badge">
                                    <span class="year">2021</span>
                                    <span class="certified-text">CERTIFIED</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="quality-item">
                        <div class="quality-card">
                            <div class="quality-icon">
                                <img src="https://cdn-icons-png.flaticon.com/512/1055/1055687.png" alt="Business License">
                            </div>
                            <div class="quality-footer">
                                <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                                <div class="certified-badge">
                                    <span class="year">2021</span>
                                    <span class="certified-text">CERTIFIED</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="quality-item">
                        <div class="quality-card">
                            <div class="quality-icon">
                                <img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png" alt="Fire Safety Certificate">
                            </div>
                            <div class="quality-footer">
                                <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                                <div class="certified-badge">
                                    <span class="year">2021</span>
                                    <span class="certified-text">CERTIFIED</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="quality-item">
                        <div class="quality-card">
                            <div class="quality-icon">
                                <img src="https://cdn-icons-png.flaticon.com/512/2921/2921222.png" alt="Food Safety Certificate">
                            </div>
                            <div class="quality-footer">
                                <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                                <div class="certified-badge">
                                    <span class="year">2021</span>
                                    <span class="certified-text">CERTIFIED</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
    <?php include "footer.php"; ?>
    <script src="/libertylaocai/view/js/gioithieu.js"></script>
</body>
</html>