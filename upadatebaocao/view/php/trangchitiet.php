<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="../css/trangchitiet.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    />
  </head>
  <body>
  <header> <?php include "header.php";?> </header>
    <div class="container">
      <div class="product-header">
        <h1 class="product-title">
          Bộ quần áo tập luyện đội tuyển quốc gia Việt Nam 2024 "Đen"
          MJ-0424.b01-02 - Hàng Chính Hãng
        </h1>
      </div>

      <div class="product-content">
        <!-- Đây là phần HTML cần đảm bảo đúng cấu trúc cho phần gallery và zoom -->
        <div class="product-gallery">
          <div class="main-image-container">
            <img
              src="../img/trangchitiet01.webp"
              alt="Bộ quần áo tập luyện đội tuyển Việt Nam"
              class="main-image"
              id="mainImage"
            />
            <div class="zoom-lens"></div>
            <div class="zoom-result"></div>
            <!-- Các phần tử zoom sẽ được tạo tự động bởi JavaScript nếu không tồn tại -->
            <div class="nav-arrow prev">
              <i class="fas fa-chevron-left"></i>
            </div>
            <div class="nav-arrow next">
              <i class="fas fa-chevron-right"></i>
            </div>
          </div>

          <div class="thumbnail-container">
            <img
              src="../img/trangchitiet01.webp"
              alt="Thumbnail 1"
              class="thumbnail active"
              onclick="changeImage(this)"
            />
            <img
              src="../img/trangchitiet02.webp"
              alt="Thumbnail 2"
              class="thumbnail"
              onclick="changeImage(this)"
            />
            <img
              src="../img/trangchitiet03.webp"
              alt="Thumbnail 3"
              class="thumbnail"
              onclick="changeImage(this)"
            />
            <img
              src="../img/trangchitiet04.webp"
              alt="Thumbnail 4"
              class="thumbnail"
              onclick="changeImage(this)"
            />
          </div>
        </div>

        <div class="product-details">
          <div class="brand-section">
            <div class="brand">
              <span class="brand-label">Thương hiệu: </span>
              <span class="brand-name">Động Lực</span>
            </div>
          </div>

          <div class="specs">
            <div class="spec-header">
              <span class="spec-label">Thông số kỹ thuật</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">- Chất liệu:</span>
              <span class="spec-value"> MK23/MK22</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">- Kiểu dáng:</span>
              <span class="spec-value"> Regular fit</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">- Logo:</span>
              <span class="spec-value"> in silicon</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">- Màu sắc:</span>
              <span class="spec-value"> Đen, Xanh lá</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">- Kích thước:</span>
              <span class="spec-value"> S - 2XL</span>
            </div>
          </div>

          <div class="price">279.000đ</div>

          <div class="size-selector">
            <div class="size-label">
              Kích thước: <span id="selectedSize">S</span>
            </div>
            <div class="size-grid">
              <div class="size-btn active" onclick="selectSize(this, 'S')">
                S
              </div>
              <div class="size-btn" onclick="selectSize(this, 'M')">M</div>
              <div class="size-btn" onclick="selectSize(this, 'L')">L</div>
              <div class="size-btn" onclick="selectSize(this, 'XL')">XL</div>
              <div class="size-btn" onclick="selectSize(this, '2XL')">2XL</div>
            </div>
          </div>

          <div class="action-row">
            <div class="quantity-wrapper">
              <div class="quantity-btn minus" onclick="decreaseQuantity()">
                -
              </div>
              <input
                type="number"
                value="1"
                min="1"
                class="quantity-input"
                id="quantity"
              />
              <div class="quantity-btn plus" onclick="increaseQuantity()">
                +
              </div>
            </div>
            <button class="add-to-cart">THÊM VÀO GIỎ HÀNG</button>
          </div>

          <div class="customer-benefits">
            <div class="benefit-item">
              <img
                src="https://bizweb.dktcdn.net/100/485/982/themes/918620/assets/policy_image_1.png?1741014141129"
                alt="Shipping icon"
                class="benefit-icon"
              />
              <span class="benefit-text">Vận chuyển hỏa tốc TOÀN QUỐC</span>
            </div>
            <div class="benefit-item">
              <img
                src="https://bizweb.dktcdn.net/100/485/982/themes/918620/assets/policy_image_2.png?1741014141129"
                alt="Support icon"
                class="benefit-icon"
              />
              <span class="benefit-text">Hỗ trợ đổi trong 5 ngày</span>
            </div>
            <div class="benefit-item">
              <img
                src="https://bizweb.dktcdn.net/100/485/982/themes/918620/assets/policy_image_3.png?1741014141129"
                alt="Gift icon"
                class="benefit-icon"
              />
              <span class="benefit-text">Quà tặng hấp dẫn cho đơn hàng</span>
            </div>
            <div class="benefit-item">
              <img
                src="https://bizweb.dktcdn.net/100/485/982/themes/918620/assets/policy_image_4.png?1741014141129"
                alt="Security icon"
                class="benefit-icon"
              />
              <span class="benefit-text">Bảo mật thông tin khách hàng</span>
            </div>
          </div>

          <div class="action-buttons">
            <button class="wishlist-btn">
              <i class="far fa-heart"></i>
              <span>Yêu thích sản phẩm</span>
            </button>
            <button class="share-btn">
              <i class="fas fa-share-alt"></i>
              <span>Chia sẻ lên facebook</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="banner-section">
      <div class="banner-container">
        <img
          src="https://bizweb.dktcdn.net/100/485/982/themes/918620/assets/banner_pro.jpg?1742531174900"
          alt="Động Lực - Hàng Chính Hãng"
          class="banner-image"
        />
      </div>
    </div>

    <!-- Product description section -->
    <div class="product-description">
      <div class="description-container">
        <h2 class="description-title">Mô tả sản phẩm</h2>

        <div class="description-content">
          <h3>
            Bộ quần áo tập luyện đội tuyển quốc gia Việt Nam 2024: Thể hiện niềm
            tự hào dân tộc trên sân bóng
          </h3>

          <p>
            Đúng với tinh thần và nội lực của đội tuyển quốc gia mạnh mẽ tư
            doanh của Việt Nam! Sự kết hợp hoàn hảo giữa thiết kế đẳng cấp, chất
            liệu cao cấp và hoa văn đặc đáo cho đồ quần áo sáng trong lúng phô
            bóng.
          </p>

          <div class="feature-section">
            <h4>1. Chất liệu</h4>
            <p>
              Áo được may từ vải MK23/MK22 cao cấp, mang đến cảm giác mát mịn,
              thoáng khí tuyệt vời, thấm hút mồ hôi nhanh chóng, khang khuẩn,
              khử mùi hiệu quả giúp bạn luôn khô thoáng, tự tin trong buổi tập
              đấu.
            </p>
            <p>
              Thêm vào đó, chất vải không nhăn, co giãn tốt, bền màu, giúp bạn
              thoải mái vận động, thực hiện mọi động tác kỹ thuật mà không lo áo
              bị giãn, mất form.
            </p>
          </div>

          <div class="feature-section">
            <h4>2. Thiết kế tối ưu chuyển động</h4>
            <p>
              Kiểu dáng Regular fit hiện đại, vừa vặn, tạo cảm giác thoải mái,
              không bó sát nhưng vẫn đảm bảo tính thể thao, năng động. Cổ áo
              được thiết kế đặc biệt, giúp người mặc thoải mái di chuyển, xoay
              người mà không bị cản trở.
            </p>
          </div>

          <div class="feature-section">
            <h4>3. Logo đặc biệt</h4>
            <p>
              Logo được in silicon cao cấp, sắc nét, bền màu, không bong tróc
              theo thời gian, giữ nguyên vẻ đẹp sau nhiều lần giặt, thể hiện tự
              hào khi khoác lên mình màu áo đội tuyển quốc gia.
            </p>
          </div>

          <div class="feature-section">
            <h4>4. Màu sắc và thiết kế</h4>
            <p>
              Tông màu đen chủ đạo kết hợp với các chi tiết xanh lá mang đến vẻ
              mạnh mẽ, hiện đại nhưng không kém phần tinh tế. Thiết kế đặc biệt
              này vừa thể hiện tinh thần thể thao, vừa mang đậm bản sắc văn hóa
              dân tộc Việt Nam.
            </p>
          </div>

          <div class="feature-section">
            <h4>5. Đa dạng kích thước</h4>
            <p>
              Sản phẩm có đầy đủ các kích thước từ S đến 2XL, phù hợp với mọi
              vóc dáng. Bạn có thể thoải mái lựa chọn size phù hợp với thể trạng
              của mình để có trải nghiệm tốt nhất.
            </p>
          </div>

          <div class="feature-section">
            <h4>6. Sử dụng và bảo quản</h4>
            <p>- Giặt máy ở nhiệt độ thấp hoặc giặt tay nhẹ nhàng</p>
            <p>- Không sử dụng chất tẩy mạnh</p>
            <p>- Phơi trong bóng râm để giữ màu sắc bền lâu</p>
            <p>- Ủi ở nhiệt độ thấp hoặc vừa</p>
          </div>

          <div class="conclusion">
            <p>
              Bộ quần áo tập luyện đội tuyển quốc gia Việt Nam 2024 không chỉ là
              trang phục thể thao mà còn là biểu tượng của niềm tự hào dân tộc,
              mang đến cho người mặc cảm giác tự tin, năng động và đầy phong
              cách trên sân bóng.
            </p>
            <p>
              Hãy sở hữu ngay hôm nay để cùng hòa nhịp với tinh thần thể thao
              Việt Nam!
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="related-section">
      <div class="related-container">
        <h2 class="related-title">Có thể bạn quan tâm:</h2>
        <div class="related-items">
          <div class="related-item">
            <img
              src="../img/trangchitiet01.webp"
              alt="Áo tập luyện đội tuyển Việt Nam 2023"
              class="related-img"
            />
            <div class="related-name">
              Áo tập luyện đội tuyển Việt Nam 2023 "Đỏ"
            </div>
            <div class="related-price">615.000đ</div>
          </div>
          <div class="related-item">
            <img
              src="../img/trangchitiet01.webp"
              alt="Bộ tập luyện đội tuyển Việt Nam 2024"
              class="related-img"
            />
            <div class="related-name">
              Bộ tập luyện đội tuyển Việt Nam 2024 "Tím"
            </div>
            <div class="related-price">445.000đ</div>
          </div>
          <div class="related-item">
            <img
              src="../img/trangchitiet01.webp"
              alt="Áo thi đấu đội tuyển Việt Nam 2024"
              class="related-img"
            />
            <div class="related-name">
              Áo thi đấu đội tuyển Việt Nam 2024 "Đỏ"
            </div>
            <div class="related-price">298.000đ</div>
          </div>
          <div class="related-item">
            <img
              src="../img/trangchitiet01.webp"
              alt="Sản phẩm thể thao"
              class="related-img"
            />
            <div class="related-name">Quần tập luyện Việt Nam 2024</div>
            <div class="related-price">345.000đ</div>
          </div>
        </div>
      </div>
    </div>
    <footer> <?php include "footer.php"; ?></footer>
    <script src="../js/trangchitiet.js"></script>
  </body>
</html>
