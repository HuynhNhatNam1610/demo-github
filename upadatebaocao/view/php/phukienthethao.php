<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <title>NHL SPORTS</title>
    <link rel="stylesheet" href="../css/trangchude.css" />
  </head>
  <body>
  <header> <?php include"header.php";?> </header>
    <div class="container">
      <div class="nav">
        <div class="nav-item">
          <img
            src="https://bizweb.dktcdn.net/100/485/982/collections/thiet-ke-logo-the-thao.png?v=1695027603200"
            alt="Phụ kiện bóng đá"
          />
          Phụ kiện bóng đá
        </div>
        <div class="nav-item">
          <img
            src="https://bizweb.dktcdn.net/100/485/982/collections/png-clipart-shuttlecock-badmintonracket-birdie-s-angle-sport.png?v=1695029634263"
            alt="Phụ kiện cầu lông"
          />
          Phụ kiện cầu lông
        </div>
        <div class="nav-item">
          <img
            src="https://bizweb.dktcdn.net/100/485/982/collections/qua-bong-ro.jpg?v=1695031462573"
            alt="Phụ kiện bóng rổ"
          />
          Phụ kiện bóng rổ
        </div>
        <div class="nav-item">
          <img
            src="https://bizweb.dktcdn.net/100/485/982/collections/lovepik-athletes-performing-volleyball-matches-png-image-401341385-wh1200.png?v=1695026569863"
            alt="Phụ kiện bóng chuyền"
          />
          Phụ kiện bóng chuyền
        </div>
      </div>

      <div class="filter-bar">
        <div class="filter-item">
          <select>
            <option value="">Chọn mức giá</option>
            <option value="500000">Dưới 500,000đ</option>
            <option value="1000000">Từ 500,000đ - 1 triệu</option>
            <option value="1500000">Từ 1 triệu - 1,500,000đ</option>
            <option value="2500000">Từ 1,500,000đ - 2,500,000đ</option>
            <option value="2500000+">Trên 2,500,000đ</option>
          </select>
        </div>
        <div class="filter-item">
          <select>
            <option value="">Loại sản phẩm</option>
            <option value="quan_ao_the_thao">Phụ kiện thể thao</option>
          </select>
        </div>
        <div class="filter-item">
          <select>
            <option value="">Thương hiệu</option>
            <option value="bubadu">Bubadu</option>
            <option value="dong_luc">Động Lực</option>
            <option value="grand_sport">Grand Sport</option>
            <option value="spalding">Spading</option>
            <option value="sao_vang">Sao Vàng</option>
          </select>
        </div>
        <div class="filter-item">
          <select>
            <option value="">Size giày</option>
            <option value="35">35</option>
            <option value="36">36</option>
            <option value="37">37</option>
            <option value="38">38</option>
            <option value="39">39</option>
            <option value="40">40</option>
            <option value="41">41</option>
          </select>
        </div>
        <div class="filter-item">
          <select>
            <option value="">Size quần áo</option>
            <option value="xs">XS</option>
            <option value="s">S</option>
            <option value="m">M</option>
            <option value="l">L</option>
            <option value="xl">XL</option>
            <option value="2xl">2XL</option>
            <option value="3xl">3XL</option>
          </select>
        </div>
      </div>

      <div class="sort-container">
        <div class="sort-dropdown">
          <span class="sort-label">Sắp xếp: </span>
          <select id="sort-select">
            <option value="default" selected>Mặc định</option>
            <option value="price-asc">Giá tăng dần</option>
            <option value="price-desc">Giá giảm dần</option>
          </select>
        </div>
      </div>

      <div class="products" id="productContainer">
        <div class="product-card">
          <img
            src="../img/phukienthethao01.webp"
            alt="Balo đội tuyển quốc gia"
          />
          <div class="product-title">Balo đội tuyển quốc gia Việt Nam 2024 AJ-HP1123 - Hàng Chính Hãng</div>
          <div class="product-price"> 455,000đ <span class="original-price"> 500,000đ </span></div>
        </div>
        <div class="product-card">
          <img
            src="../img/phukienthethao02.webp"
            alt="Mũ đội tuyển quốc gia"
          />
          <div class="product-title">Mũ đội tuyển quốc gia Việt Nam 2024 "Trắng" JG-DTQG-M-02 - Hàng Chính Hãng</div>
          <div class="product-price">145,000đ</div>
        </div>
        <div class="product-card">
          <img
            src="../img/phukienthethao03.webp"
            alt="Áo Lót Body"
          />
          <div class="product-title">
          Áo Lót Body Thể Thao Sao Vàng SV-AB - Hàng Chính Hãng
          </div>
          <div class="product-price">140,000đ</div>
        </div>
        <div class="product-card">
          <img
            src="../img/phukienthethao04.webp"
            alt="Tất ngắn thi đấu"
          />
          <div class="product-title">
          Tất ngắn thi đấu đội tuyển quốc gia Việt Nam 2024 "Trắng" JG-DTQG-TN-01 - Hàng Chính Hãng
          </div>
          <div class="product-price">75,000đ <span class="original-price">100,000đ</span></div>
        </div>
        <div class="product-card">
          <img
            src="../img/phukienthethao05.webp"
            alt="Lưới bóng chuyền "
          />
          <div class="product-title">Lưới bóng chuyền hơi có cáp Huy Hoàng - 1 viền trắng - Hàng Chính Hãng</div>
          <div class="product-price">
            320,000đ
            <span class="original-price">350,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="../img/phukienthethao06.webp"
            alt="Bảng điểm thể thao Winstar"
          />
          <div class="product-title">Bảng điểm thể thao Winstar "Lớn" - Hàng Chính Hãng</div>
          <div class="product-price">
            400,000đ
            <span class="original-price">500,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="../img/phukienthethao07.webp"
            alt="Balo Thể Thao"
          />
          <div class="product-title">Balo Thể Thao Jogarbola "Xanh" AJ-HP0123-03 - Hàng Chính Hãng</div>
          <div class="product-price">
            419,000đ
            <span class="original-price">495,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="../img/phukienthethao08.webp"
            alt="Mũ đội tuyển"
          />
          <div class="product-title">Mũ đội tuyển quốc gia Việt Nam 2024 "Đen" JG-DTQG-M-01 - Hàng Chính Hãng</div>
          <div class="product-price">
            145,000đ
            <span class="original-price">160,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="../img/phukienthethao09.webp"
            alt="Túi đựng giày"
          />
          <div class="product-title">Túi đựng giày đội tuyển quốc gia Việt Nam 2024 JG-DTQG-TDG- Hàng Chính Hãng</div>
          <div class="product-price">
            145,000đ
            <span class="original-price">160,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="../img/phukienthethao10.webp"
            alt="Áo Hoodie Nữ"
          />
          <div class="product-title">Bó gót thể thao Winstar - Hàng Chính Hãng</div>
          <div class="product-price">
            110,000đ
            <span class="original-price">130,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="../img/phukienthethao11.webp"
            alt="Bóng rổ Spalding"
          />
          <div class="product-title">Bóng rổ Spalding VBA Official Game Ball – Indoor - Size 7 77-781z - Hàng Chính Hãng</div>
          <div class="product-price">
            2,350,000đ
            <span class="original-price">2,495,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="../img/phukienthethao12.webp"
            alt="Bóng rổ Spalding"
          />
          <div class="product-title">Bóng rổ Spalding VBA Rainbow Graffiti – Outdoor – Size 7 85-034z - Hàng Chính Hãng</div>
          <div class="product-price">
            550,000đ
            <span class="original-price">695,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="../img/phukienthethao13.webp"
            alt="Bóng Chuyền Da "
          />
          <div class="product-title">Bóng chuyền Da Động Lực Ebet DT S001 DL-DTS001 - Hàng Chính Hãng</div>
          <div class="product-price">
            105,000đ
            <span class="original-price">120,000đ</span>
          </div>
        </div>
      </div>
    </div>
    <footer> <?php include "footer.php"; ?></footer>
    <script src="../js/phukienthethao.js"></script>
  </body>
</html>
