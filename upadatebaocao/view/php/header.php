<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebTheThaovn Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/header.css">
</head>
<body>
    <header>
      <div class="header">
          <div class="logo">
              <img src="../img/logo3.webp" alt="Logo WebTheThaovn">
              <span class="logo-text">NHL SPORTS</span>
          </div>
          
          <div class="search-container">
              <button><i class="bi bi-search"></i></button>
              <input type="text" class="search-input" placeholder="Tìm sản phẩm...">
          </div>
          
          <div class="contact-info">
                  <i class="bi bi-telephone-fill"></i>
                  <div class="contact-text">
                      <div >Tư vấn mua hàng</div>
                      <a href="tel:0823885888" class="tel">
                          <div >0823 885 888</div>
                      </a>
                  </div>
          </div>
          
          <div class="header-icons">
            <a href="#"><i class="bi bi-house-fill"></i></a>
            <!-- <a href="#"><i class="bi bi-heart"></i></a> -->
            <a href="#"><i class="bi bi-cart"></i></a>
            <div class="user-menu">
                <a href="#"><i class="bi bi-person"></i></a>
                <i class="bi bi-caret-down-fill dropdown-toggle"></i>
                <ul class="dropdown-menu">
                    <li><a href="login.php"> <img src="../img/loginicon.png">Đăng nhập</a></li>
                    <li><a href="logout.php"> <img src="../img/dangxuaticon.jpg">Đăng xuất</a></li>
                </ul>
            </div>
          </div>
      </div>
      
      <div class="category-nav">
          <div class="big-menu">
            <button class="list">
              <i class="bi bi-list"></i>
              <span> Danh mục sản phẩm</span>
            </button>
          </div>
          <div class="search-container1">
              <button><i class="bi bi-search"></i></button>
              <input type="text" class="search-input1" placeholder="Tìm sản phẩm...">
          </div>
          <a href="#" class="hidden">Sale off bóng đá</a>
          <a href="#" class="hidden">Đồ bóng chuyền SALE OFF!</a>
          <a href="#" class="hidden">Đồ Bi-a Chính Hãng</a>
          <a href="#" class="hidden">Giầy bóng chuyền Sao Vàng</a>
          <a href="#" class="hidden">SALE OUTLET 40%</a>
      </div>
      <div class="menu-overlay"></div>
      <div class="menu-dropdown">
        <div class="menu-dropdown-left">
          <div class="menu-dropdown-left-top">
            <button data-category="monthethao"><div class="menu-in-dropdown"> <img src="../img/ol.webp">Môn thể thao</div></button>
            <button data-category="aothethaonam"><div class="menu-in-dropdown"> <img src="../img/ol.webp">Thể thao nam</div></button>
            <button data-category="aothethaonu"><div class="menu-in-dropdown"> <img src="../img/ol.webp">Thể thao nữ</div></button>
            <button data-category="phukien"><div class="menu-in-dropdown"> <img src="../img/ol.webp">Phụ kiện</div></button>
            <button data-category="thuonghieu"><div class="menu-in-dropdown"> <img src="../img/ol.webp">Thương hiệu</div></button>
          </div>
          <div class="menu-dropdown-left-bot">
              <div class="menu-item" data-category="monthethao">
                <a href="" class="item">BÓNG RỔ</a>
                <a href="" class="item">Bóng thi đấu</a>
                <a href="" class="item">Giày bóng rổ</a>
                <a href="" class="item">Quần áo</a>
                <a href="" class="item">Phụ kiện bóng rổ</a>
              </div>
              <div class="menu-item" data-category="monthethao">
                <a href="" class="item">BÓNG CHUYỀN</a>
                <a href="" class="item">Bóng thi đấu</a>
                <a href="" class="item">Giày bóng chuyền</a>
                <a href="" class="item">Quần áo</a>
                <a href="" class="item">Phụ kiện bóng chuyền</a>
              </div>
              <div class="menu-item" data-category="monthethao">
                <a href="" class="item">BÓNG ĐÁ & FUTSAL</a>
                <a href="" class="item">Bóng thi đấu</a>
                <a href="" class="item">Giày bóng đá</a>
                <a href="" class="item">Quần áo</a>
                <a href="" class="item">Phụ kiện bóng đá</a>
              </div>
              <div class="menu-item" data-category="monthethao">
                <a href="" class="item">TẬP GYM & WORKOUT</a>
                <a href="" class="item">Giày tập Gym</a>
                <a href="" class="item">Quần áo</a>
                <a href="" class="item">Phụ kiện tập Fitness</a>
              </div>
              <div class="menu-item" data-category="monthethao">
                <a href="" class="item">CHẠY BỘ & ĐI BỘ</a>
                <a href="" class="item">Giày chạy bộ</a>
                <a href="" class="item">Quần áo</a>
                <a href="" class="item">Phụ kiện chạy bộ</a>
              </div>
              <div class="menu-item" data-category="monthethao">
                <a href="" class="item">CẦU LÔNG</a>
                <a href="" class="item">Vợt cầu lông</a>
                <a href="" class="item">Cầu thi đấu</a>
                <a href="" class="item">Giày cầu lông</a>
                <a href="" class="item">Quần áo</a>
                <a href="" class="item">Phụ kiện cầu lông</a>
              </div>
              <div class="menu-item" data-category="monthethao">
                <a href="" class="item">BIA</a>
                <a href="" class="item">Gậy Đánh Bi-a</a>
                <a href="" class="item">Gậy Phá Bi-a</a>
                <a href="" class="item">Gậy Nhảy Bi-a</a>
                <a href="" class="item">Bao đựng cơ</a>
                <a href="" class="item">Áo thi đấu</a>
                <a href="" class="item">Phụ kiện Bi-a</a>
              </div>
              <div class="menu-item" data-category="monthethao">
                <a href="" class="item">PICKLEBALL</a>
                <a href="" class="item">Giày Pickleball</a>
                <a href="" class="item">Vợt Pickleball</a>
                <a href="" class="item">Phụ kiện Pickleball</a>
              </div>
              <div class="menu-item" data-category="aothethaonam">
                <a href="" class="item">ÁO THỂ THAO NAM</a>
                <a href="" class="item">Áo polo Nam</a>
                <a href="" class="item">Áo khoác Nam</a>
                <a href="" class="item">Aó Hoodie Nam</a>
                <a href="" class="item">Áo body Nam</a>
              </div>
              <div class="menu-item" data-category="aothethaonam">
                <a href="" class="item">QUẦN THỂ THAO</a>
                <a href="" class="item">Quần short Nam</a>
                <a href="" class="item">Quần dài Nam</a>
                <a href="" class="item">Quần body Nam</a>
              </div>
              <div class="menu-item" data-category="aothethaonam">
                <a href="" class="item">BỘ THỂ THAO</a>
                <a href="" class="item">Bộ bóng đá Nam</a>
                <a href="" class="item">Bộ bóng chuyền Nam</a>
                <a href="" class="item">Bộ cầu lông Nam</a>
              </div>
              <div class="menu-item" data-category="aothethaonam">
                <a href="" class="item">GIÀY THỂ THAO NAM</a>
                <a href="" class="item">Giày chạy bộ Nam</a>
                <a href="" class="item">Giày thời trang Nam</a>
                <a href="" class="item">Giày bóng rổ Nam</a>
                <a href="" class="item">Giày cầu lông Nam</a>
                <a href="" class="item">Giày bóng chuyền Nam</a>
              </div>
              <div class="menu-item" data-category="aothethaonu">
                <a href="" class="item">ÁO THỂ THAO NỮ</a>
                <a href="" class="item">Áo polo Nữ</a>
                <a href="" class="item">Áo hoodie Nữ</a>
                <a href="" class="item">Áo khoác nữ</a>
              </div>
              <div class="menu-item" data-category="aothethaonu">
                <a href="" class="item">QUẦN THỂ THAO NỮ</a>
                <a href="" class="item">Quần short Nữ</a>
                <a href="" class="item">Quần dài Nữ</a>
                <a href="" class="item">Quần legging Nữ</a>
              </div>
              <div class="menu-item" data-category="aothethaonu">
                <a href="" class="item">BỘ THỂ THAO NỮ</a>
                <a href="" class="item">Bộ bóng chuyền Nữ</a>
                <a href="" class="item">Bộ cầu lông Nữ</a>
              </div>
              <div class="menu-item" data-category="aothethaonu">
                <a href="" class="item">GIÀY THỂ THAO NỮ</a>
                <a href="" class="item">Giày chạy bộ Nữ</a>
                <a href="" class="item">Giày thời trang Nữ</a>
                <a href="" class="item">Giày bóng rổ Nữ</a>
                <a href="" class="item">Giày cầu lông Nữ</a>
                <a href="" class="item">Giày bóng chuyền Nữ</a>
              </div>
              <div class="menu-item" data-category="phukien">
                <a href="" class="item">BALO & TÚI</a>
                <a href="" class="item">Balo</a>
                <a href="" class="item">Túi thể thao</a>
                <a href="" class="item">Túi đựng vợt</a>
                <a href="" class="item">Túi đựng giày</a>
              </div>
              <div class="menu-item" data-category="phukien">
                <a href="" class="item">PHỤ KIỆN KHÁC</a>
                <a href="" class="item">Phụ kiện bóng đá</a>
                <a href="" class="item">Phụ kiện cầu lông</a>
                <a href="" class="item">Phụ kiện bóng rổ</a>
                <a href="" class="item">Phụ kiện bóng chuyền</a>
                <a href="" class="item">Phụ kiện bi-a</a>
              </div>
              <div class="menu-item" data-category="thuonghieu">
                <a href="" class="item">ĐỘNG LỰC</a>
                <a href="" class="item">Quần áo</a>
                <a href="" class="item">Giày dép</a>
                <a href="" class="item">Bóng thi đấu</a>
                <a href="" class="item">Phụ kiện thể thao</a>
              </div>
              <div class="menu-item" data-category="thuonghieu">
                <a href="" class="item">GRAND SPORT</a>
                <a href="" class="item">Quần áo</a>
                <a href="" class="item">Giày dép</a>
                <a href="" class="item">Đồ bảo hộ</a>
              </div>
              <div class="menu-item" data-category="thuonghieu">
                <a href="" class="item">SPALDING</a>
                <a href="" class="item">Bóng thi đấu</a>
                <a href="" class="item">Phụ kiện Bóng rổ</a>
              </div>
              <div class="menu-item" data-category="thuonghieu">
                <a href="" class="item">Giày bóng rổ</a>
                <a href="" class="item">Giày chạy bộ</a>
                <a href="" class="item">Giày thời trang</a>
              </div>
              <div class="menu-item" data-category="thuonghieu">
                <a href="" class="item">BUBADU</a>
                <a href="" class="item">Quần áo</a>
                <a href="" class="item">Vợt cầu lông</a>
                <a href="" class="item">Phụ kiện cầu lông</a>
              </div>
              <div class="menu-item" data-category="thuonghieu">
                <a href="" class="item">SAO VÀNG</a>
                <a href="" class="item">Giày bóng chuyền</a>
                <a href="" class="item">Quần áo</a>
                <a href="" class="item">Phụ kiện thể thao</a>
              </div>
              <div class="menu-item" data-category="thuonghieu">
                <a href="" class="item">PERI</a>
                <a href="" class="item">Gậy / Cơ Bi-a</a>
                <a href="" class="item">Bao đựng cơ</a>
                <a href="" class="item">Phụ kiện khác</a>
              </div>
              <div class="menu-item" data-category="thuonghieu">
                <a href="" class="item">ZOCKER </a>
                <a href="" class="item">Giày chạy bộ Zocker</a>
                <a href="" class="item">Giày bóng đá Zocker</a>
                <a href="" class="item">Phụ kiện Zocker</a>
                <a href="" class="item">Sản phẩm Pickleball Zocker</a>
              </div>

          </div>
        </div>
        <div class="menu-dropdown-right">
            <div class="menu-dropdown-right-top">
              <a class="top" href="">
                <div class="menu-top-1">
                  <img src="../img/cacmonthethao.webp">
                </div>
                <div class="menu-top-2">
                    <h3>Môn thể thao</h3>
                    <p>Xem thêm</p>
                </div>
              </a>
            </div>
            <div class="menu-dropdown-right-bot">
              <div class="menu-option">
                <a class="item" href="">Sale off bóng đá</a>
                <i class="bi bi-arrow-right"></i>
              </div>
              <div class="menu-option">
                <a class="item" href="">Đồ bóng chuyền SALE OFF!</a>
                <i class="bi bi-arrow-right"></i>
              </div>
              <div class="menu-option">
                <a class="item" href="">Đồ Bi-a Chính Hãng</a>
                <i class="bi bi-arrow-right"></i>
              </div>
              <div class="menu-option">
                <a class="item" href="">Giầy bóng chuyền Sao Vàng</a>
                <i class="bi bi-arrow-right"></i>
              </div>
              <div class="menu-option">
                <a class="item" href="">SALE OUTLET 40%</a>
                <i class="bi bi-arrow-right"></i>
              </div>
            </div>
        </div>
      </div>
    </header>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/header.js"></script>
</body>
</html>