<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <title>NHL SPORTS</title>
    <link rel="stylesheet" href="../css/trangchitiet.css" />
  </head>
  <body>
  <header> <?php include"header.php";?> </header>
    <div class="container">
      <div class="nav">
        <div class="nav-item">
          <img
            src="https://bizweb.dktcdn.net/100/485/982/collections/co-tron-tay-ngan.png?v=1695029052207"
            alt="Áo thể thao Nữ"
          />
          Áo thể thao Nữ
        </div>
        <div class="nav-item">
          <img
            src="https://bizweb.dktcdn.net/100/485/982/collections/download-1.png?v=1695030989243"
            alt="Quần thể thao Nữ"
          />
          Quần thể thao Nữ
        </div>
        <div class="nav-item">
          <img
            src="https://bizweb.dktcdn.net/100/485/982/collections/co-tron-tay-ngan.png?v=1695029052207"
            alt="Bộ thể thao Nữ"
          />
          Bộ thể thao Nữ
        </div>
        <div class="nav-item">
          <img
            src="https://png.pngtree.com/png-vector/20230407/ourmid/pngtree-sneakers-line-icon-vector-png-image_6693223.png"
            alt="Giày thể thao Nữ"
          />
          Giày thể thao Nữ
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
            <option value="quan_ao_the_thao">Quần áo thể thao</option>
          </select>
        </div>
        <div class="filter-item">
          <select>
            <option value="">Thương hiệu</option>
            <option value="dong_luc">Động Lực</option>
            <option value="grand_sport">Grand Sport</option>
            <option value="kamito">Kamito</option>
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
            src="../img/thoitrangnu01.webp"
            alt="Áo Hoodie Nữ"
          />
          <div class="product-title">Áo Hoodie Nam Nữ Động Lực Jogarbola JG 340 "Xanh Navy" JG340-11 - Hàng Chính Hãng
          </div>
          <div class="product-price"> 350,000đ <span class="original-price"> 495,000đ </span></div>
        </div>
        <div class="product-card">
          <img
            src="../img/thoitrangnu02.webp"
            alt="Áo Phông Cầu Lông"
          />
          <div class="product-title">Áo Phông Cầu Lông Nữ Động Lực Promax "Đỏ - Trắng" DL-AP664-01 - Hàng Chính Hãng</div>
          <div class="product-price">175,000đ</div>
        </div>
        <div class="product-card">
          <img
            src="../img/thoitrangnam13.webp"
            alt="Bộ thi đấu"
          />
          <div class="product-title">
          Bộ thi đấu bóng chuyền Sao Vàng Combat "Trắng" SV-COMBAT-01 - Hàng Chính Hãng
          </div>
          <div class="product-price">165,000đ</div>
        </div>
        <div class="product-card">
          <img
            src="../img/thoitrangnu04.webp"
            alt="Bộ thi đấu"
          />
          <div class="product-title">
          Bộ thi đấu đội tuyển quốc gia Việt Nam 2024 "Trắng" MJ-AJ1277-02 - Hàng Chính Hãng
          </div>
          <div class="product-price">890,000đ <span class="original-price">1,000,000đ</span></div>
        </div>
        <div class="product-card">
          <img
            src="../img/thoitrangnu05.webp"
            alt="Áo Hoodie Nữ"
          />
          <div class="product-title">Bộ thi đấu bóng chuyền Sao Vàng Combat "Trắng" SV-COMBAT-01 - Hàng Chính Hãng
          </div>
          <div class="product-price">
            165,000đ
            <span class="original-price">180,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="../img/thoitrangnu06.webp"
            alt="Áo Hoodie Nữ"
          />
          <div class="product-title">Áo thun Động Lực Jogarbola nữ "Trắng" JG-500-01 - Hàng Chính Hãng
          </div>
          <div class="product-price">
            399,000đ
            <span class="original-price">450,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="../img/thoitrangnu07.webp"
            alt="Quần tập"
          />
          <div class="product-title">Quần tập thể thao Jogarbola nữ "Đen" JG-9030-01 - Hàng Chính Hãng
          </div>
          <div class="product-price">
            339,000đ
            <span class="original-price">395,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="../img/thoitrangnu08.webp"
            alt="Bộ thi đấu"
          />
          <div class="product-title">Bộ thi đấu bóng chuyền Sao Vàng Combat "Đỏ" SV-COMBAT-03 - Hàng Chính Hãng
          </div>
          <div class="product-price">
            165,000đ
            <span class="original-price">180,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="../img/thoitrangnu09.webp"
            alt="Bộ thi đấu"
          />
          <div class="product-title">Bộ thi đấu bóng chuyền Sao Vàng Combat "Vàng" SV-COMBAT-02 - Hàng Chính Hãng
          </div>
          <div class="product-price">
            165,000đ
            <span class="original-price">180,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="../img/thoitrangnu10.webp"
            alt="Quần tập"
          />
          <div class="product-title">Quần Thể Thao Nam Nữ Động Lực Jogarbola Classic BJ392-04 "Đen" BJ392D - Hàng Chính Hãng
          </div>
          <div class="product-price">
            339,000đ
            <span class="original-price">395,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="../img/thoitrangnu11.webp"
            alt="Quần tập"
          />
          <div class="product-title">Quần Thể Thao Nam Nữ Động Lực Jogarbola Basic S2 BJ394-04 "Đen" BJ394D - Hàng Chính Hãng
          </div>
          <div class="product-price">
            339,000đ
            <span class="original-price">395,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="../img/thoitrangnu12.webp"
            alt="Áo Nỉ Nữ"
          />
          <div class="product-title">Áo Nỉ Nữ Động Lực Jogarbola JG 338 "Vàng" JG338V - Hàng Chính Hãng</div>
          <div class="product-price">
            399,000đ
            <span class="original-price">495,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="../img/thoitrangnu13.webp"
            alt="Áo Nỉ Nữ"
          />
          <div class="product-title">Áo Nỉ Nữ Động Lực Jogarbola JG 332 "Trắng" JG332T - Hàng Chính Hãng</div>
          <div class="product-price">
            399,000đ
            <span class="original-price">495,000đ</span>
          </div>
        </div>
      </div>
    </div>
    <footer> <?php include "footer.php"; ?></footer>
    <script src="../js/thoitrangnu.js"></script>
  </body>
</html>
