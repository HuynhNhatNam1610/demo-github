<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thời Trang Thể Thao Nam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/thoitrangnam.css" />
  </head>
  <body>
    <header> <?php include "header.php";?> </header>
    <div class="container">
      <div class="nav">
        <div class="nav-item">
          <img
            src="https://bizweb.dktcdn.net/100/485/982/collections/co-tron-tay-ngan.png?v=1695029052207"
            alt="Áo thể thao Nam"
          />
          Áo thể thao Nam
        </div>
        <div class="nav-item">
          <img
            src="https://bizweb.dktcdn.net/100/485/982/collections/download-1.png?v=1695030989243"
            alt="Quần thể thao Nam"
          />
          Quần thể thao Nam
        </div>
        <div class="nav-item">
          <img
            src="https://bizweb.dktcdn.net/100/485/982/collections/co-tron-tay-ngan.png?v=1695029052207"
            alt="Bộ thể thao Nam"
          />
          Bộ thể thao Nam
        </div>
        <div class="nav-item">
          <img
            src="https://png.pngtree.com/png-vector/20230407/ourmid/pngtree-sneakers-line-icon-vector-png-image_6693223.png"
            alt="Giày thể thao Nam"
          />
          Giày thể thao Nam
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
            <option value="dong_luc">Đồng Lực</option>
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
            src="https://bizweb.dktcdn.net/100/485/982/products/ao-khoac-1692169090225.png?v=1692169094487"
            alt="Áo Hoodie Nam Nam"
          />
          <div class="product-title">Áo Hoodie Nam Nam Đồng Lực Jogarbola</div>
          <div class="product-price">
            1,350,000đ
            <span class="original-price">1,495,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="https://bizweb.dktcdn.net/100/485/982/products/1-1692171388220.png?v=1692171393273"
            alt="Áo Phông Cầu Lông"
          />
          <div class="product-title">Áo Phông Cầu Lông Nam Đồng Lực Promax</div>
          <div class="product-price">175,000đ</div>
        </div>
        <div class="product-card">
          <img
            src="https://bizweb.dktcdn.net/100/485/982/products/mj-0422-01-01-01-1686820824952.jpg?v=1686825867193"
            alt="Bộ thi đấu"
          />
          <div class="product-title">
            Bộ thi đấu chuyên Sao Vàng Combat "Trắng"
          </div>
          <div class="product-price">165,000đ</div>
        </div>
        <div class="product-card">
          <img
            src="https://bizweb.dktcdn.net/100/485/982/products/11-1719379202421.jpg?v=1719391212420"
            alt="Bộ thi đấu"
          />
          <div class="product-title">
            Bộ thi đấu chuyên Sao Vàng Combat "Xanh"
          </div>
          <div class="product-price">165,000đ</div>
        </div>
        <div class="product-card">
          <img
            src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/8-1704421591414.jpg?v=1704421621810"
            alt="Áo Hoodie Nam Nam"
          />
          <div class="product-title">Áo Hoodie Nam Nam Đồng Lực Jogarbola</div>
          <div class="product-price">
            350,000đ
            <span class="original-price">495,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/ao-3-1719230937125.jpg?v=1736219709680"
            alt="Áo Hoodie Nam Nam"
          />
          <div class="product-title">Áo Hoodie Nam Nam Đồng Lực Jogarbola</div>
          <div class="product-price">
            350,000đ
            <span class="original-price">495,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/3-1692171190545.png?v=1692171195120"
            alt="Áo Hoodie Nam Nam"
          />
          <div class="product-title">Áo Hoodie Nam Nam Đồng Lực Jogarbola</div>
          <div class="product-price">
            350,000đ
            <span class="original-price">495,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/1-1714042369704.jpg?v=1714042373223"
            alt="Áo Hoodie Nam Nam"
          />
          <div class="product-title">Áo Hoodie Nam Nam Đồng Lực Jogarbola</div>
          <div class="product-price">
            350,000đ
            <span class="original-price">495,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/3-1714042390043.jpg?v=1714042394160"
            alt="Áo Hoodie Nam Nam"
          />
          <div class="product-title">Áo Hoodie Nam Nam Đồng Lực Jogarbola</div>
          <div class="product-price">
            350,000đ
            <span class="original-price">495,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/4-1714042261677.jpg?v=1714042264947"
            alt="Áo Hoodie Nam Nam"
          />
          <div class="product-title">Áo Hoodie Nam Nam Đồng Lực Jogarbola</div>
          <div class="product-price">
            350,000đ
            <span class="original-price">495,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/2-1714042233634.jpg?v=1714042252357"
            alt="Áo Hoodie Nam Nam"
          />
          <div class="product-title">Áo Hoodie Nam Nam Đồng Lực Jogarbola</div>
          <div class="product-price">
            350,000đ
            <span class="original-price">495,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/7-1704421501668.jpg?v=1704422462033"
            alt="Áo Hoodie Nam Nam"
          />
          <div class="product-title">Áo Hoodie Nam Nam Đồng Lực Jogarbola</div>
          <div class="product-price">
            350,000đ
            <span class="original-price">495,000đ</span>
          </div>
        </div>
        <div class="product-card">
          <img
            src="https://bizweb.dktcdn.net/thumb/large/100/485/982/products/22-1706526254083.jpg?v=1706527351447"
            alt="Áo Hoodie Nam Nam"
          />
          <div class="product-title">Áo Hoodie Nam Nam Đồng Lực Jogarbola</div>
          <div class="product-price">
            350,000đ
            <span class="original-price">495,000đ</span>
          </div>
        </div>
      </div>
    </div>

    <footer> <?php include "footer.php"; ?></footer>

    <script src="../js/thoitrangnam.js"></script>
  </body>
</html>
