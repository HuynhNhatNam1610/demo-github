<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Phòng - The Liberty Lào Cai</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/libertylaocai/view/css/danhsachphong.css">
</head>
<body>
<?php include "header.php" ?>
<div class="danhsachphong-container">
        <div class="hero-content">
            <h1>DANH SÁCH PHÒNG</h1>
            <div class="breadcrumb">Trang Chủ > Danh Sách Phòng</div>
        </div>
   
    <div class="main-container">
        <!-- Sidebar Filter -->
        <div class="sidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px;">
                <h3>Lọc phòng</h3>
                <span class="clear-all" onclick="clearAllFilters()">Xóa Tất Cả</span>
            </div>

            <!-- Price Filter -->
            <!-- Price Filter Section -->
<div class="filter-section">
    <div class="filter-title">
        <i class="fas fa-dollar-sign"></i>
        Giá
    </div>
    <div class="price-range">
        <div class="price-inputs">
            <input type="text" class="price-input" id="minPrice" value="500.000 đ" readonly>
            <input type="text" class="price-input" id="maxPrice" value="3.000.000 đ" readonly>
        </div>
        <div class="range-slider">
            <input type="range" min="500000" max="3000000" value="500000" class="slider" id="minRange">
            <input type="range" min="500000" max="3000000" value="3000000" class="slider" id="maxRange">
        </div>
        <div class="price-display" id="priceDisplay">500.000 đ - 3.000.000 đ</div>
    </div>

    <div style="margin-top: 20px; font-size: 14px; color: #cbb69d; font-weight: bold;">
        LỌC
    </div>
</div>

            <!-- Rating Filter -->
            <div class="filter-section">
                <div class="filter-title">
                    <i class="fas fa-star"></i>
                    Đánh Giá Sao
                </div>
                <div class="rating-filter">
                    <div class="rating-option" onclick="toggleRating(1)">
                        <div class="rating-checkbox">
                            <input type="checkbox" id="rating1">
                            <div class="stars">
                                <i class="fas fa-star star"></i>
                            </div>
                            <span>1 đánh giá</span>
                        </div>
                        <span class="rating-count">0</span>
                    </div>
                    <div class="rating-option" onclick="toggleRating(2)">
                        <div class="rating-checkbox">
                            <input type="checkbox" id="rating2">
                            <div class="stars">
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                            </div>
                            <span>2 đánh giá</span>
                        </div>
                        <span class="rating-count">0</span>
                    </div>
                    <div class="rating-option" onclick="toggleRating(3)">
                        <div class="rating-checkbox">
                            <input type="checkbox" id="rating3">
                            <div class="stars">
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                            </div>
                            <span>3 đánh giá</span>
                        </div>
                        <span class="rating-count">0</span>
                    </div>
                    <div class="rating-option" onclick="toggleRating(4)">
                        <div class="rating-checkbox">
                            <input type="checkbox" id="rating4">
                            <div class="stars">
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                            </div>
                            <span>4 đánh giá</span>
                        </div>
                        <span class="rating-count">0</span>
                    </div>
                    <div class="rating-option" onclick="toggleRating(5)">
                        <div class="rating-checkbox">
                            <input type="checkbox" id="rating5">
                            <div class="stars">
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                            </div>
                            <span>5 đánh giá</span>
                        </div>
                        <span class="rating-count">0</span>
                    </div>
                    <div class="rating-option" onclick="toggleRating(0)">
                        <div class="rating-checkbox">
                            <input type="checkbox" id="rating0">
                            <span>Không có sao</span>
                        </div>
                        <span class="rating-count">6</span>
                    </div>
                </div>
            </div>
            <div class="filter-section">
                <div class="filter-title">
                    <span>Danh Mục Phòng</span>
                </div>
                <div class="room-category">
                    <label><input type="checkbox" name="room_category" value="deluxe_quad"> Deluxe Quad Room (3)</label><br>
                    <label><input type="checkbox" name="room_category" value="deluxe"> Deluxe Room (1)</label><br>
                    <label><input type="checkbox" name="room_category" value="superior_quad"> Superior Quad Room (1)</label><br>
                    <label><input type="checkbox" name="room_category" value="superior"> Superior Room (1)</label><br>
                </div>
            </div>
        </div>

        <!-- Room Content -->
        <div class="room-content">
            <div class="results-header">
                <div class="results-count">Hiển thị 3 kết quả</div>
                <div class="sort-options">
                    <label for="sortSelect">Sắp xếp theo:</label>
                    <select id="sortSelect" class="sort-select" onchange="sortRooms()">
                        <option value="price-low">Giá thấp đến cao</option>
                        <option value="price-high">Giá cao đến thấp</option>
                        <option value="name">Tên phòng</option>
                        <option value="size">Diện tích</option>
                    </select>
                </div>
            </div>

            <div class="room-highlight-section">
                <!-- VIP Luxury Room -->
                <div class="room-block" data-price="2250000" data-rating="0">
                    <div class="room-info-box">
                        <h3>VIP LUXURY</h3>
                        <div class="room-price">2,250,000 VNĐ <span>/ Đêm</span></div>
                        <div class="room-specs">
                            <p><strong>Diện tích:</strong> 55 m²</p>
                            <p><strong>Hướng:</strong> Trung tâm thành phố</p>
                            <p><strong>Loại giường:</strong> King bed</p>
                        </div>
                        <h4>Tiện ích phòng</h4>
                        <ul class="room-amenities">
                            <li><i class="fas fa-bed"></i> Giường cao cấp</li>
                            <li><i class="fas fa-glass-whiskey"></i> Ly thủy tinh</li>
                            <li><i class="fas fa-book"></i> Bàn đọc sách</li>
                            <li><i class="fas fa-hanger"></i> Móc treo</li>
                            <li><i class="fas fa-lightbulb"></i> Đèn ngủ</li>
                            <li><i class="fas fa-book-open"></i> Đèn đọc sách</li>
                        </ul>
                        <button class="btn-booking">ĐẶT PHÒNG</button>
                    </div>
                    <div class="room-image-box">
                        <div class="carousel-container">
                            <div class="carousel-slides" id="vip-slides">
                                <div class="carousel-slide">
                                    <img src="https://libertylaocai.vn/_next/image?url=%2Fphong%2Fsofa.jpg&w=1080&q=75" alt="VIP Luxury Room 1">
                                </div>
                                <div class="carousel-slide">
                                    <img src="https://thewesternhill.com/storage/anh-moi-t6/phong-nghi/vip-luxury/vip-luxury-942x530.jpg" alt="VIP Luxury Room 2">
                                </div>
                                <div class="carousel-slide">
                                    <img src="https://thewesternhill.com/storage/anh-moi-t6/phong-nghi/deluxe-family/deluxe-family-5.jpg" alt="VIP Luxury Room 3">
                                </div>
                                <div class="carousel-slide">
                                    <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="VIP Luxury Room 4">
                                </div>
                            </div>
                            <div class="carousel-controls">
                                <button class="carousel-btn" onclick="changeSlide('vip', -1)">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <div class="carousel-dots" id="vip-dots"></div>
                                <span class="carousel-counter" id="vip-counter"></span>
                                <button class="carousel-btn" onclick="changeSlide('vip', 1)">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suite Family Room -->
                <div class="room-block reverse" data-price="2200000" data-rating="0">
                    <div class="room-info-box">
                        <h3>SUITE FAMILY</h3>
                        <div class="room-price">2,200,000 VNĐ <span>/ Đêm</span></div>
                        <div class="room-specs">
                            <p><strong>Diện tích:</strong> 55 m²</p>
                            <p><strong>Hướng:</strong> Thành phố</p>
                            <p><strong>Loại giường:</strong> Double bed</p>
                        </div>
                        <h4>Tiện ích phòng</h4>
                        <ul class="room-amenities">
                            <li><i class="fas fa-bed"></i> Giường đôi</li>
                            <li><i class="fas fa-glass-whiskey"></i> Ly thủy tinh</li>
                            <li><i class="fas fa-book"></i> Bàn đọc sách</li>
                            <li><i class="fas fa-hanger"></i> Móc treo</li>
                            <li><i class="fas fa-lightbulb"></i> Đèn ngủ</li>
                            <li><i class="fas fa-book-open"></i> Đèn đọc sách</li>
                        </ul>
                        <button class="btn-booking">ĐẶT PHÒNG</button>
                    </div>
                    <div class="room-image-box">
                        <div class="carousel-container">
                            <div class="carousel-slides" id="suite-slides">
                                <div class="carousel-slide">
                                    <img src="https://thewesternhill.com/storage/anh-moi-t6/phong-nghi/vip-luxury/vip-luxury-942x530.jpg" alt="Suite Family Room 1">
                                </div>
                                <div class="carousel-slide">
                                    <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Suite Family Room 2">
                                </div>
                                <div class="carousel-slide">
                                    <img src="https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80" alt="Suite Family Room 3">
                                </div>
                                <div class="carousel-slide">
                                    <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Suite Family Room 4">
                                </div>
                                <div class="carousel-slide">
                                    <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2080&q=80" alt="Suite Family Room 5">
                                </div>
                            </div>
                            <div class="carousel-controls">
                                <button class="carousel-btn" onclick="changeSlide('suite', -1)">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <div class="carousel-dots" id="suite-dots"></div>
                                <span class="carousel-counter" id="suite-counter"></span>
                                <button class="carousel-btn" onclick="changeSlide('suite', 1)">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- President Suite Room -->
                <div class="room-block" data-price="2900000" data-rating="0">
                    <div class="room-info-box">
                        <h3>PRESIDENT SUITE</h3>
                        <div class="room-price">2,900,000 VNĐ <span>/ Đêm</span></div>
                        <div class="room-specs">
                            <p><strong>Diện tích:</strong> 80 m²</p>
                            <p><strong>Hướng:</strong> Núi và thành phố</p>
                            <p><strong>Loại giường:</strong> King bed</p>
                        </div>
                        <h4>Tiện ích phòng</h4>
                        <ul class="room-amenities">
                            <li><i class="fas fa-couch"></i> Khu vực tiếp khách</li>
                            <li><i class="fas fa-tv"></i> TV màn hình lớn</li>
                            <li><i class="fas fa-bath"></i> Bồn tắm cao cấp</li>
                            <li><i class="fas fa-wine-glass"></i> Minibar</li>
                            <li><i class="fas fa-mountain"></i> View núi</li>
                            <li><i class="fas fa-spa"></i> Khu vực thư giãn</li>
                        </ul>
                        <button class="btn-booking  ">ĐẶT PHÒNG</button>
                    </div>
                    <div class="room-image-box">
                        <div class="carousel-container">
                            <div class="carousel-slides" id="president-slides">
                                <div class="carousel-slide">
                                    <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80" alt="President Suite 1">
                                </div>
                                <div class="carousel-slide">
                                    <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="President Suite 2">
                                </div>
                                <div class="carousel-slide">
                                    <img src="https://images.unsplash.com/photo-1560472355-536de3962603?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2126&q=80" alt="President Suite 3">
                                </div>
                            </div>
                            <div class="carousel-controls">
                                <button class="carousel-btn" onclick="changeSlide('president', -1)">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <div class="carousel-dots" id="president-dots"></div>
                                <span class="carousel-counter" id="president-counter"></span>
                                <button class="carousel-btn" onclick="changeSlide('president', 1)">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php" ?>
    <script src="/libertylaocai/view/js/danhsachphong.js"></script>
</body>
</html>