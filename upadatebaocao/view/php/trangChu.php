<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebTheThaovn Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/trangChu.css" rel="stylesheet">
</head>
<body>
    <header> <?php include "header.php";?> </header>
    <div class="container">
        <div id="header-container"></div>
        <div class="new-arrivals-container">
            <div class="header-container">
                <h1 class="header-text">NEW ARRIVALS !</h1>
            </div>
            <div class="product-container">
                <div class="product-pages">
                   
                </div>
                <div class="pagination"></div>
            </div>
        </div>
     
        <div class="feature-section-container">
            <div class="row row-cols-2 row-cols-lg-4 g-3">
                <div class="col">
                    <div class="feature-box">
                        <i class="bi bi-truck feature-icon"></i>
                        <span>Vận chuyển SIÊU TỐC<br>Khu vực TOÀN QUỐC</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-box">
                        <i class="bi bi-shield-check feature-icon"></i>
                        <span>Cam kết CHÍNH HÃNG<br>Sản phẩm TRỌN ĐỜI</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-box">
                        <i class="bi bi-credit-card feature-icon"></i>
                        <span>THANH TOÁN<br>Với nhiều PHƯƠNG THỨC</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-box">
                        <i class="bi bi-arrow-counterclockwise feature-icon"></i>
                        <span>100% HOÀN TIỀN<br>nếu sản phẩm lỗi</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="banner-container">
            <a href="/" class="banner-link">
                <img src="https://bizweb.dktcdn.net/100/485/982/themes/918620/assets/banner_index.jpg?1741014141129" alt="Banner Quảng Cáo" class="banner-image">
            </a>
        </div>
     
        <div class="product-section">
            <div class="carousel-container">
                <div class="carousel-inner row row-cols-1 row-cols-md-5 g-3">
                
                    <div class="col">
                        <div class="product-card">
                            <a href="/product/cap-den" class="product-link">
                                <img src="../img/giay.webp" alt="Mũ Đen Đội Tuyển Quốc Gia" class="product-img">
                                <div class="product-info">
                                    <h6 class="product-title">Mũ đội tuyển quốc gia Việt Nam "Đen" JG-DTQG-M...</h6>
                                    <p class="product-price">145.000đ</p>
                                </div>
                            </a>
                        </div>
                    </div>
                 
                    <div class="col">
                        <div class="product-card">
                            <a href="/product/cap-trang" class="product-link">
                                <img src="../img/giay1.webp" alt="Mũ Trắng Đội Tuyển Quốc Gia" class="product-img">
                                <div class="product-info">
                                    <h6 class="product-title">Mũ đội tuyển quốc gia Việt Nam "Trắng" JG-DTQG-...</h6>
                                    <p class="product-price">145.000đ</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col">
                        <div class="product-card">
                            <a href="/product/tui-dong-luc" class="product-link">
                                <img src="../img/giay2.webp" alt="Túi Thể Thao Động Lực" class="product-img">
                                <div class="product-info">
                                    <h6 class="product-title">Túi thể thao Động Lực Jogarbola "HP0623" Ha...</h6>
                                    <p class="product-price">249.000đ <span class="original-price">295.000đ</span></p>
                                </div>
                            </a>
                        </div>
                    </div>
                 
                    <div class="col">
                        <div class="product-card">
                            <a href="/product/tui-giay" class="product-link">
                                <img src="../img/giay.webp" alt="Túi Đựng Giày Đội Tuyển" class="product-img">
                                <div class="product-info">
                                    <h6 class="product-title">Túi đựng giày đội tuyển quốc gia JG-DTQG-...</h6>
                                    <p class="product-price">145.000đ</p>
                                </div>
                            </a>
                        </div>
                    </div>
                   
                    <div class="col">
                        <div class="product-card">
                            <a href="/product/balo-xanh" class="product-link">
                                <img src="../img/giay1.webp" alt="Balo Thể Thao Xanh" class="product-img">
                                <div class="product-info">
                                    <h6 class="product-title">Balo Thể Thao Jogarbola "Xanh" AJ-HP0123-03...</h6>
                                    <p class="product-price">419.000đ <span class="original-price">455.000đ</span></p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="/accessories" class="btn btn-danger">Xem Thêm</a>
            </div>
        </div>

        <div class="sports-banner-section">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <a href="/sports/volleyball" class="sports-banner-link">
                        <img src="https://bizweb.dktcdn.net/100/485/982/themes/918620/assets/img_3banner_1.jpg?1741014141129" alt="Volleyball Banner" class="sports-banner-img">
                    </a>
                </div>
                <div class="col-12 col-md-4">
                    <a href="/sports/badminton" class="sports-banner-link">
                        <img src="https://bizweb.dktcdn.net/100/485/982/themes/918620/assets/img_3banner_2.jpg?1741014141129" alt="Badminton Banner" class="sports-banner-img">
                    </a>
                </div>
                <div class="col-12 col-md-4">
                    <a href="/sports/billiard" class="sports-banner-link">
                        <img src="https://bizweb.dktcdn.net/100/485/982/themes/918620/assets/img_3banner_3.jpg?1741014141129" alt="Billiard Banner" class="sports-banner-img">
                    </a>
                </div>
            </div>
        </div>

        <div class="fashion-section">
            <div class="row">
                <div class="col-12">
                    <a href="/men" class="fashion-title-link">
                        <h2 class="fashion-title">MEN FASHION</h2>
                    </a>
                </div>
                <div class="col-12 col-md-3">
                    <div class="fashion-banner">
                        <a href="/men" class="banner-link">
                            <img src="https://bizweb.dktcdn.net/100/485/982/themes/918620/assets/men_product_img.jpg?1741014141129" alt="Men Fashion Banner" class="banner-image">
                        </a>
                    </div>
                </div>
                <div class="col-12 col-md-9">
                    <div class="carousel-container">
                        <div class="carousel-inner row row-cols-1 row-cols-md-4 g-3">
                            
                            <div class="col">
                                <div class="product-card">
                                    <a href="/product/ao-khoac-doi-tuyen-viet-nam" class="product-link">
                                        <img src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/1-1692171388220.png?v=1692171393273" alt="Áo Khoác Đội Tuyển Việt Nam" class="product-img">
                                        <div class="product-info">
                                            <h6 class="product-title">Áo Khoác Đội Tuyển Việt Nam 2023 Grand Sport GS...</h6>
                                            <p class="product-price">1.655.000đ</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="product-card">
                                    <a href="/product/ao-tap-luyen-doi-tuyen-viet-nam" class="product-link">
                                        <img src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/1-1692171388220.png?v=1692171393273" alt="Áo Tập Luyện Đội Tuyển Việt Nam" class="product-img">
                                        <div class="product-info">
                                            <h6 class="product-title">Áo Tập Luyện Đội Tuyển Việt Nam 2023 Grand Sport...</h6>
                                            <p class="product-price">615.000đ</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="product-card">
                                    <a href="/product/ao-thun-dong-luc-jogarbola" class="product-link">
                                        <img src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/1-1692171388220.png?v=1692171393273" alt="Áo Thun Động Lực Jogarbola" class="product-img">
                                        <div class="product-info">
                                            <h6 class="product-title">Áo thun Động Lực Jogarbola Classic Dri-fit "Xanh"...</h6>
                                            <p class="product-price">345.000đ <span class="original-price">405.000đ</span></p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                           
                            <div class="col">
                                <div class="product-card">
                                    <a href="/product/bo-quan-ao-tap-luyen-nam" class="product-link">
                                        <img src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/1-1692171388220.png?v=1692171393273" alt="Bộ Quần Áo Tập Luyện Đội Tuyển Nam" class="product-img">
                                        <div class="product-info">
                                            <h6 class="product-title">Bộ quần áo tập luyện đội tuyển Nam...</h6>
                                            <p class="product-price">279.000đ</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="product-card">
                                    <a href="/product/ao-thun-doi-tuyen-viet-nam" class="product-link">
                                        <img src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/1-1692171388220.png?v=1692171393273" alt="Áo Thun Đội Tuyển Việt Nam" class="product-img">
                                        <div class="product-info">
                                            <h6 class="product-title">Áo Thun Đội Tuyển Việt Nam 2023 Grand Sport...</h6>
                                            <p class="product-price">415.000đ</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                           
                            <div class="col">
                                <div class="product-card">
                                    <a href="/product/ao-khoac-dong-luc-jogarbola" class="product-link">
                                        <img src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/1-1692171388220.png?v=1692171393273" alt="Áo Khoác Động Lực Jogarbola" class="product-img">
                                        <div class="product-info">
                                            <h6 class="product-title">Áo Khoác Động Lực Jogarbola "Đen" JG-...</h6>
                                            <p class="product-price">1.255.000đ</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="product-card">
                                    <a href="/product/bo-quan-ao-the-thao-nam" class="product-link">
                                        <img src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/1-1692171388220.png?v=1692171393273" alt="Bộ Quần Áo Thể Thao Nam" class="product-img">
                                        <div class="product-info">
                                            <h6 class="product-title">Bộ Quần Áo Thể Thao Nam Động Lực "Xám"...</h6>
                                            <p class="product-price">599.000đ</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="product-card">
                                    <a href="/product/ao-polo-nam-dong-luc" class="product-link">
                                        <img src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/1-1692171388220.png?v=1692171393273" alt="Áo Polo Nam Động Lực" class="product-img">
                                        <div class="product-info">
                                            <h6 class="product-title">Áo Polo Nam Động Lực "Xanh Đậm"...</h6>
                                            <p class="product-price">299.000đ</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-controls">
                            <button class="carousel-control-prev" type="button">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       
    

        <div class="fashion-section">
            <div class="row">
                <div class="col-12">
                    <a href="/women" class="fashion-title-link">
                        <h2 class="fashion-title">WOMEN FASHION</h2>
                    </a>
                </div>
                <div class="col-12 col-md-9">
                    <div class="carousel-container">
                        <div class="carousel-inner row row-cols-1 row-cols-md-4 g-3">
                            
                            <div class="col">
                                <div class="product-card">
                                    <a href="/product/ao-hoodie-nu-dong-luc-jogarbola" class="product-link">
                                        <img src="../img/aonu.jpg" alt="Áo Hoodie Nữ Động Lực Jogarbola" class="product-img">
                                        <div class="product-info">
                                            <h6 class="product-title">Áo Hoodie Nữ Động Lực Jogarbola JG 340* "Xanh"...</h6>
                                            <p class="product-price">355.000đ <span class="original-price">425.000đ</span></p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="product-card">
                                    <a href="/product/ao-phong-cau-long-nu-dong-luc-promax" class="product-link">
                                        <img src="../img/aonu.jpg" alt="Áo Phông Cầu Lông Nữ Động Lực Promax" class="product-img">
                                        <div class="product-info">
                                            <h6 class="product-title">Áo Phông Cầu Lông Nữ Động Lực Promax "Đỏ"...</h6>
                                            <p class="product-price">175.000đ</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="product-card">
                                    <a href="/product/bo-thi-dau-bong-chuyen-sao-vang-combat" class="product-link">
                                        <img src="../img/aonu.jpg" alt="Bộ Thi Đấu Bóng Chuyền Sao Vàng Combat" class="product-img">
                                        <div class="product-info">
                                            <h6 class="product-title">Bộ thi đấu bóng chuyền Sao Vàng Combat "Trắng" SV...</h6>
                                            <p class="product-price">165.000đ</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="product-card">
                                    <a href="/product/bo-thi-dau-bong-chuyen-tim-tham" class="product-link">
                                        <img src="../img/aonu.jpg" alt="Bộ Thi Đấu Bóng Chuyền Tim Thắm" class="product-img">
                                        <div class="product-info">
                                            <h6 class="product-title">Bộ thi đấu bóng chuyền Sao Vàng Combat "Tim Thắm"...</h6>
                                            <p class="product-price">165.000đ</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="product-card">
                                    <a href="/product/ao-the-thao-nu-dong-luc" class="product-link">
                                        <img src="../img/aonu.jpg" alt="Áo Thể Thao Nữ Động Lực" class="product-img">
                                        <div class="product-info">
                                            <h6 class="product-title">Áo Thể Thao Nữ Động Lực "Hồng"...</h6>
                                            <p class="product-price">199.000đ</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                           
                            <div class="col">
                                <div class="product-card">
                                    <a href="/product/bo-quan-ao-the-thao-nu" class="product-link">
                                        <img src="../img/aonu.jpg" alt="Bộ Quần Áo Thể Thao Nữ" class="product-img">
                                        <div class="product-info">
                                            <h6 class="product-title">Bộ Quần Áo Thể Thao Nữ Động Lực "Xám"...</h6>
                                            <p class="product-price">499.000đ</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                           
                            <div class="col">
                                <div class="product-card">
                                    <a href="/product/ao-khoac-nu-dong-luc" class="product-link">
                                        <img src="../img/aonu.jpg" alt="Áo Khoác Nữ Động Lực" class="product-img">
                                        <div class="product-info">
                                            <h6 class="product-title">Áo Khoác Nữ Động Lực "Đen"...</h6>
                                            <p class="product-price">899.000đ</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                           
                            <div class="col">
                                <div class="product-card">
                                    <a href="/product/ao-polo-nu-dong-luc" class="product-link">
                                        <img src="../img/aonu.jpg" alt="Áo Polo Nữ Động Lực" class="product-img">
                                        <div class="product-info">
                                            <h6 class="product-title">Áo Polo Nữ Động Lực "Trắng"...</h6>
                                            <p class="product-price">299.000đ</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-controls">
                            <button class="carousel-control-prev" type="button">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="fashion-banner">
                        <a href="/women" class="banner-link">
                            <img src="https://bizweb.dktcdn.net/100/485/982/themes/918620/assets/women_product_img.jpg?1741014141129" alt="Women Fashion Banner" class="banner-image">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="sports-news-section">
            <div class="news-pages">
                
            </div>
            <div class="news-pagination"></div>
        </div>
    </div>
    <footer> 
        <?php include "footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/trangChu.js"></script>
</body>
</html>