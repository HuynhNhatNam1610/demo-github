<?php
require_once "session.php";
require_once "../../model/UserModel.php";
// Kiểm tra ngôn ngữ từ session, mặc định là 1 (tiếng Việt)
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;

// Lấy nội dung chào hỏi và mô tả theo ngôn ngữ
$getGreeding = getGreetingByLanguage($languageId, 'trangchu');
$getImageGeneral = getImageGeneral();

//Lấy ảnh cho feature-image-right
$getSelectedImageRight = getSelectedImage('feature-image-right');

//Lấy ảnh cho feature-image-left
$getSelectedImageLeft = getSelectedImage('feature-image-left');

//Lấy ảnh cho banner-overlay
$getSelectedBannerOverlay = getSelectedImage('banner-overlay');

// Lấy mô tả cho .left-panel
$descriptionLeftPanel = getSelectedDescription($languageId, 'left-panel');

// Lấy mô tả cho .feature-text
$descriptionFeatureText = getSelectedDescription($languageId, 'feature-text');
$descriptionFeatureText1 = getSelectedDescription($languageId, 'feature-text1');

// Lấy mô tả cho highlight-title
$descriptionHightlightTitle = getSelectedDescription($languageId, 'highlight-title');

// Lấy mô tả cho banner-overlay
$descriptionBannerOverlay = getSelectedDescription($languageId, 'banner-overlay');

// Lấy mô tả cho service-text
$descriptionServiceText = getSelectedDescription($languageId, 'service-text');

// Lấy mô tả cho news-subtitle
$descriptionNewsSubtitle = getSelectedDescription($languageId, 'news-subtitle');

// Lấy mô tả cho quality-card1
$descriptionQualityCard1 = getSelectedDescription($languageId, 'quality-card1');

// Lấy mô tả cho quality-card2
$descriptionQualityCard2 = getSelectedDescription($languageId, 'quality-card2');

// Lấy mô tả cho quality-card3
$descriptionQualityCard3 = getSelectedDescription($languageId, 'quality-card3');

// Lấy mô tả cho quality-card4
$descriptionQualityCard4 = getSelectedDescription($languageId, 'quality-card4');

// Lấy mô tả cho quality-card5
$descriptionQualityCard5 = getSelectedDescription($languageId, 'quality-card5');


// Lấy danh sách các loại phòng
$roomTypes = getRoomTypes($languageId);

// Lấy dữ liệu cho service-carousel-wrapper
$carouselServices = getServicesForCarousel($languageId);

// Lấy 4 bình luận mới nhất
$reviews = getBinhLuan(null, 4);

//Lấy tin tức
$newsList = getNewsList($languageId);

// Tạo mảng để truyền thông tin carousel sang JavaScript
$carouselData = [];
foreach ($roomTypes as $room) {
  $images = getImagesForRoom($room['id']);
  $carouselId = 'room-' . $room['id'];
  $carouselData[$carouselId] = [
    'totalSlides' => !empty($images) ? count($images) : 1
  ];
}
?>
<!DOCTYPE html>
<html lang="<?php echo $languageId == 1 ? 'vi' : 'en'; ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $languageId == 1 ? 'Khách sạn Liberty Lào Cai' : 'Liberty Hotel Lao Cai'; ?></title>
  <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
  <meta name="description" content="<?php echo $languageId == 1 ? 'Liberty Hotel & Events Khách sạn Liberty Lào Cai' : 'Liberty Hotel & Events Lao Cai'; ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="/libertylaocai/view/css/trangchu.css">
</head>

<body>
  <?php include "header.php"; ?>
  <div class="container">
    <div class="homepage">
      <div class="left-panel">
        <?php if (!empty($getGreeding)): ?>
          <?php foreach ($getGreeding as $greeding): ?>
            <h1><?php echo $greeding['content']; ?></h1>
          <?php endforeach; ?>
        <?php else: ?>
          <h1><?php echo $languageId == 1 ? 'Chào mừng bạn đến với Liberty Hotel Lào Cai' : 'Welcome to Liberty Hotel Lao Cai'; ?></h1>
        <?php endif; ?>
        <?php if (!empty($descriptionLeftPanel)): ?>
          <p><?php echo htmlspecialchars($descriptionLeftPanel['content']); ?></p>
        <?php else: ?>
          <p><?php echo $languageId == 1 ? 'Tận hưởng không gian sang trọng và khám phá nét ẩm thực độc đáo.' : 'Enjoy luxurious space and discover unique cuisine.'; ?></p>
        <?php endif; ?>
      </div>
      <div class="right-panel">
        <div class="slideshow-container">
          <?php if (!empty($getImageGeneral)): ?>
            <?php foreach ($getImageGeneral as $index => $slide): ?>
              <div class='slideshow-slide <?php echo $index === 0 ? "active" : ""; ?>'>
                <img src='/libertylaocai/view/img/<?php echo htmlspecialchars($slide['image']); ?>' alt='Slide <?php echo $index + 1; ?>'>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class='slideshow-slide active'>
              <img src='/libertylaocai/view/img/492527411_1219549180175551_8482228742596186123_n.jpg' alt='Default Slide'>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <div class="booking-overlay">
        <div class="form-field">
          <label><?php echo $languageId == 1 ? 'Ngày Nhận Phòng' : 'Check-in'; ?></label>
          <input type="date" />
        </div>
        <div class="form-field">
          <label><?php echo $languageId == 1 ? 'Ngày Trả Phòng' : 'Check-out'; ?></label>
          <input type="date" />
        </div>
        <div class="form-field">
          <label><?php echo $languageId == 1 ? 'Số Lượng Khách' : 'Number Of People'; ?></label>
          <input type="number" name="so_luong" min="1" max="6" placeholder="Nhập số người">
        </div>
        <form action="/libertylaocai/user/submit" method="POST" style="display:inline; ">
          <button type="submit" name="find_room" class="btn-find"><?php echo $languageId == 1 ? 'TÌM PHÒNG' : 'Find Room'; ?> →</button>
        </form>
      </div>
    </div>
    <div class="service-icons">
      <div class="service-item">
        <i class="fas fa-car"></i>
        <p><?php echo $languageId == 1 ? 'Dịch Vụ Đưa Đón' : 'Shuttle Service'; ?></p>
      </div>
      <div class="service-item">
        <i class="fas fa-parking"></i>
        <p><?php echo $languageId == 1 ? 'Bãi Đậu Xe' : 'Parking Lot'; ?></p>
      </div>
      <div class="service-item">
        <i class="fas fa-suitcase-rolling"></i>
        <p><?php echo $languageId == 1 ? 'Du Lịch' : 'Tour'; ?></p>
      </div>
      <div class="service-item">
        <i class="fas fa-utensils"></i>
        <p><?php echo $languageId == 1 ? 'Nhà Hàng' : 'Restaurant'; ?></p>
      </div>
      <div class="service-item">
        <i class="fas fa-landmark"></i>
        <p><?php echo $languageId == 1 ? 'Sự Kiện' : 'Event'; ?></p>
      </div>
      <div class="service-item">
        <i class="fas fa-martini-glass"></i>
        <p>Sky Bar</p>
      </div>
      <div class="service-item">
        <i class="fas fa-wifi"></i>
        <p><?php echo $languageId == 1 ? 'Wifi Miễn Phí' : 'Free Wifi'; ?></p>
      </div>
    </div>

    <div class="feature-section">
      <div class="feature-row" data-aos="fade-right">
        <div class="feature-image">
          <?php if (!empty($getSelectedImageRight)): ?>
            <img src="/libertylaocai/view/img/<?= $getSelectedImageRight['image'] ?>" alt="Hình ảnh khách sạn">
          <?php else: ?>
            <img src="https://thewesternhill.com/storage/sliders/sanh-the-western-hill-942x530.jpg" alt="Hình ảnh khách sạn">
          <?php endif; ?>
        </div>
        <div class="feature-text">
          <div class="subheading"><?php echo $languageId == 1 ? 'The Liberty Lào Cai Hotel' : 'The Liberty Lao Cai Hotel'; ?></div>
          <?php if (!empty($descriptionFeatureText)): ?>
            <h2><?php echo htmlspecialchars($descriptionFeatureText['title']); ?></h2>
            <p><?php echo htmlspecialchars($descriptionFeatureText['content']); ?></p>
          <?php else: ?>
            <h2><?php echo $languageId == 1 ? 'Trải nghiệm đẳng cấp' : 'Experience class'; ?></h2>
            <p>Khách sạn The Liberty Lào Cai là một khách sạn được xây dựng theo lối kiến trúc Châu Âu, hiện đại, nằm trên con đường trung tâm Đà Lạt – Phan Đình Phùng gồm 104 phòng, được trang bị đầy đủ các tiện nghi như truyền hình cáp, minibar và phòng tắm riêng với bồn tắm và máy sấy tóc.</p>
          <?php endif; ?>
          <a href="/libertylaocai/gioi-thieu" class="btn-more"><?php echo $languageId == 1 ? 'Tìm hiểu thêm' : 'Learn more'; ?></a>
        </div>
      </div>

      <div class="feature-row reverse" data-aos="fade-left">
        <div class="feature-image">
          <?php if (!empty($getSelectedImageLeft)): ?>
            <img src="/libertylaocai/view/img/<?= $getSelectedImageLeft['image'] ?>" alt="Hình ảnh khách sạn">
          <?php else: ?>
            <img src="https://thewesternhill.com/storage/anh-moi-t6/lavender/lanvender-sanh-tiec-3-942x530.jpg" alt="Dịch vụ đưa đón">
          <?php endif; ?>
        </div>
        <div class="feature-text"> <!--feature-text1 -->
          <div class="subheading"><?php echo $languageId == 1 ? 'The Liberty Lào Cai Hotel' : 'The Liberty Lao Cai Hotel'; ?></div>
          <?php if (!empty($descriptionFeatureText1)): ?>
            <h2><?php echo htmlspecialchars($descriptionFeatureText1['title']); ?></h2>
            <p><?php echo htmlspecialchars($descriptionFeatureText1['content']); ?></p>
          <?php else: ?>
            <h2><?php echo $languageId == 1 ? 'Đưa đón sân bay nội bài - Liberty Hotel' : 'Noi Bai airport transfer - Liberty Hotel'; ?></h2>
            <p>Chuyến đi của quý vị sẽ thuận tiện và thoải mái hơn với dịch vụ đưa đón sân bay chuyên nghiệp. Hãy để đội ngũ tài xế giàu kinh nghiệm cùng phương tiện hiện đại của chúng tôi đồng hành cùng quý vị ngay từ những phút đầu tiên đặt chân đến Lào Cai!</p>
          <?php endif; ?>
          <a href="/libertylaocai/dua-don" class="btn-more"><?php echo $languageId == 1 ? 'Khám phá' : 'Discover'; ?></a>
        </div>
      </div>
    </div>
    <div class="room-highlight-section">
      <?php if (!empty($descriptionHightlightTitle)): ?>
        <h2 class="highlight-title"><?php echo htmlspecialchars($descriptionHightlightTitle['content']); ?></h2>
      <?php else: ?>
        <h2 class="highlight-title"><?php echo $languageId == 1 ? 'THIẾT KẾ ẤN TƯỢNG VÀ ĐẲNG CẤP' : 'IMPRESSIVE AND CLASSY DESIGN'; ?></h2>
      <?php endif; ?>
      <?php foreach ($roomTypes as $index => $room): ?>
        <?php
        // Lấy thông tin bổ sung
        $bedTypes = getBedTypesForRoom($room['id'], $languageId);
        $amenities = getAmenitiesForRoom($room['id'], $languageId);
        $images = getImagesForRoom($room['id']);

        // Định dạng giá
        $formattedPrice = number_format($room['price'], 0, ',', '.') . ' VNĐ';

        // Tạo ID cho carousel
        $carouselId = 'room-' . $room['id'];

        // Xác định lớp reverse cho layout xen kẽ
        $reverseClass = $index % 2 == 1 ? 'reverse' : '';
        ?>
        <div class="room-block <?php echo $reverseClass; ?>" data-price="<?php echo $room['price']; ?>" data-rating="0">
          <div class="room-info-box">
            <h3><?php echo htmlspecialchars($room['name']); ?></h3>
            <div class="room-price"><?php echo $formattedPrice; ?> <span>/ <?php echo $languageId == 1 ? 'Đêm' : 'Night'; ?></span></div>
            <div class="room-specs">
              <p><?php echo $languageId == 1 ? 'Diện tích' : 'Area'; ?>: <?php echo htmlspecialchars($room['area']); ?> m²</p>
              <p><?php echo $languageId == 1 ? 'Loại giường' : 'Bed type'; ?>:
                <?php
                if (!empty($bedTypes)) {
                  $bedList = [];
                  foreach ($bedTypes as $bedType) {
                    $bedList[] = $bedType['quantity'] . " - " . $bedType['name'];;
                  }
                  echo implode('; ', $bedList);
                } else {
                  echo $languageId == 1 ? 'Không xác định' : 'Not specified';
                }
                ?>
              </p>
            </div>
            <h4><?php echo $languageId == 1 ? 'Tiện ích phòng' : 'Room Amenities'; ?></h4>
            <ul class="room-amenities">
              <?php if (!empty($amenities)): ?>
                <?php foreach ($amenities as $amenity): ?>
                  <li><i class="fas fa-check"></i><?php echo htmlspecialchars($amenity); ?></li>
                <?php endforeach; ?>
              <?php else: ?>
                <li><?php echo $languageId == 1 ? 'Không có tiện ích' : 'No amenities'; ?></li>
              <?php endif; ?>
            </ul>
            <!-- Thay đổi nút đặt phòng thành form POST -->
            <form action="/libertylaocai/user/submit" method="POST">
              <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
              <button class="btn-booking"><?php echo $languageId == 1 ? 'ĐẶT PHÒNG' : 'BOOK NOW'; ?></button>
            </form>
          </div>
          <div class="room-image-box">
            <div class="carousel-container">
              <div class="carousel-slides" id="<?php echo $carouselId; ?>-slides">
                <?php if (!empty($images)): ?>
                  <?php foreach ($images as $image): ?>
                    <div class="carousel-slide">
                      <img src="/libertylaocai/view/img/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>" loading="lazy">
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="carousel-slide">
                    <img src="https://via.placeholder.com/1080x720?text=No+Image" alt="No Image">
                  </div>
                <?php endif; ?>
              </div>
              <div class="carousel-controls">
                <button class="carousel-btn" onclick="changeSlide('<?php echo $carouselId; ?>', -1)">❮</button>
                <div class="carousel-dots" id="<?php echo $carouselId; ?>-dots"></div>
                <div class="carousel-counter" id="<?php echo $carouselId; ?>-counter">
                  1 / <?php echo !empty($images) ? count($images) : 1; ?>
                </div>
                <button class="carousel-btn" onclick="changeSlide('<?php echo $carouselId; ?>', 1)">❯</button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      <div class="view-all-rooms-wrapper">
        <form action="/libertylaocai/user/submit" method="POST" style="display: inline;">
          <button name="find_room" class="view-all-rooms-btn">
            <?php echo $languageId == 1 ? 'Xem tất cả phòng' : 'View All Rooms'; ?>
          </button>
        </form>
      </div>
    </div>
    <div class="custom-banner" data-aos="fade-out">
      <div class="banner-overlay">
        <?php if (!empty($descriptionBannerOverlay)): ?>
          <h2><?php echo htmlspecialchars($descriptionBannerOverlay['title']); ?></h2>
          <p><?php echo htmlspecialchars($descriptionBannerOverlay['content']); ?></p>
        <?php else: ?>
          <h2><?php echo $languageId == 1 ? 'Không gian hoàn hảo cho sự kiện của bạn' : 'The perfect space for your event'; ?></h2>
          <p><?php echo $languageId == 1 ? 'Liên hệ với chúng tôi ngay hôm nay để được tư vấn và đặt lịch.' : 'Contact us today for a consultation and appointment.'; ?></p>
        <?php endif; ?>
        <form action="/libertylaocai/user/submit" method="POST" style="display: inline;">
          <button name="datlichngay" class="banner-button"><?php echo $languageId == 1 ? 'Đặt lịch ngay' : 'Schedule now'; ?></button>
        </form>
      </div>
      <?php if (!empty($getSelectedBannerOverlay)): ?>
        <img src="/libertylaocai/view/img/<?= $getSelectedBannerOverlay['image'] ?>" alt="Hình ảnh khách sạn">
      <?php else: ?>
        <img src="https://libertylaocai.vn/_next/image?url=%2Fgallery-hoi_truong%2Fcong_hoi_truong3.jpg&w=1920&q=75" alt="Event Banner">
      <?php endif; ?>
    </div>
    <section class="other-services-section">
      <div class="other-services-container container">
        <!-- Nội dung chính -->
        <div class="services-content">
          <!-- Nội dung văn bản bên trái -->
          <div class="service-text">
            <?php if (!empty($descriptionServiceText)): ?>
              <h3><?php echo htmlspecialchars($descriptionServiceText['title']); ?></h3>
              <p><?php echo htmlspecialchars($descriptionServiceText['content']); ?></p>
            <?php else: ?>
              <h3><?php echo $languageId == 1 ? 'Dịch vụ khác' : 'Other services'; ?></h3>
              <p><?php echo $languageId == 1 ? 'Quý khách sẽ được hưởng thụ nhiều dịch vụ tại Phước Thịnh Home khi đặt phòng.' : 'You will enjoy many services at Phuoc Thinh Home when booking a room.'; ?></p>
            <?php endif; ?>
            <a href="/libertylaocai/dich-vu" class="btn-more"><?php echo $languageId == 1 ? 'XEM THÊM' : 'VIEW MORE'; ?></a>
          </div>

          <!-- Carousel bên phải -->
          <div class="service-carousel-wrapper">
            <button class="service-carousel-btn left" onclick="changeServiceSlide(-1)">&#10094;</button>
            <div id="services-slides" class="service-carousel">
              <?php
              foreach ($carouselServices as $service):
              ?>
                <div class="service-slide">
                  <img src="/libertylaocai/view/img/<?php echo htmlspecialchars($service['image']); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>" loading="lazy">
                  <p><?php echo htmlspecialchars($service['title']); ?></p>
                </div>
              <?php endforeach; ?>
            </div>
            <button class="service-carousel-btn right" onclick="changeServiceSlide(1)">&#10095;</button>
          </div>
        </div>
      </div>
    </section>
    <!-- News Section -->
    <section class="news-section">
      <div class="news-container container">
        <h2 class="news-title"><?php echo $languageId == 1 ? 'Tin tức' : 'News'; ?></h2>
        <?php if (!empty($descriptionNewsSubtitle)): ?>
          <p class="news-subtitle"><?php echo htmlspecialchars($descriptionNewsSubtitle['content']); ?></p>
        <?php else: ?>
          <p class="news-subtitle"><?php echo $languageId == 1 ? 'Cập nhật những thông tin mới nhất từ khách sạn The Liberty Lào Cai' : 'Update the latest information from The Liberty Lao Cai Hotel'; ?></p>
        <?php endif; ?>

        <!-- Desktop Grid -->
        <!-- News Grid -->
        <div class="news-grid">
          <?php
          foreach ($newsList as $index => $news):
          ?>
            <article class="news-item" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
              <div class="news-image">
                <img src="/libertylaocai/view/img/<?php echo htmlspecialchars($news['image']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>" loading="lazy">
                <div class="news-date">
                  <span class="day"><?php echo htmlspecialchars($news['day']); ?></span>
                  <span class="month"><?php echo htmlspecialchars($news['month']); ?></span>
                </div>
              </div>
              <div class="news-content">

                <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                <p><?php echo htmlspecialchars(mb_substr($news['content'], 0, 100, 'UTF-8')) . '...'; ?></p>
                <form action="/libertylaocai/user/submit" method="POST" style="display: inline; ">
                  <button class="promotion-button" type="submit" class="news-link" name="id_tintuc" value="<?php echo htmlspecialchars($news['id']); ?>">
                    <?php echo $languageId == 1 ? 'Đọc thêm' : 'Read More'; ?> <i class="bi bi-arrow-right"></i>
                  </button>
                </form>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
        <!-- Mobile Static List -->
        <div class="news-carousel">
          <?php foreach ($newsList as $index => $news): ?>
            <article class="news-item" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
              <div class="news-image">
                <img src="/libertylaocai/view/img/<?php echo htmlspecialchars($news['image']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>" loading="lazy">
                <div class="news-date">
                  <span class="day"><?php echo htmlspecialchars($news['day']); ?></span>
                  <span class="month"><?php echo htmlspecialchars($news['month']); ?></span>
                </div>
              </div>
              <div class="news-content">
                <span class="news-category"><?php echo $languageId == 1 ? 'Sự kiện' : 'Event'; ?></span>
                <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                <p><?php echo htmlspecialchars(mb_substr($news['content'], 0, 100, 'UTF-8')) . '...'; ?></p>
                <form action="/libertylaocai/user/submit" method="POST" style="display: inline; ">
                  <button class="promotion-button" type="submit" class="news-link" name="id_tintuc" value="<?php echo htmlspecialchars($news['id']); ?>">
                    <?php echo $languageId == 1 ? 'Đọc thêm' : 'Read More'; ?> <i class="bi bi-arrow-right"></i>
                  </button>
                </form>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <div class="news-view-all">
          <form action="/libertylaocai/user/submit" method="POST" style="display: inline; ">
            <button type="submit" name="xem_them_tin" class="btn-view-all" value="tin-tuc"><?php echo $languageId == 1 ? 'Xem tất cả tin tức' : 'View all news'; ?></button>
          </form>
        </div>
      </div>
    </section>
    <!-- Customer Reviews Section -->
    <section class="customer-reviews-section">
      <h2><?php echo $languageId == 1 ? 'Khách hàng phản hồi' : 'Customer feedback'; ?></h2>
      <div class="reviews-carousel-wrapper">
        <button class="reviews-carousel-btn left" onclick="changeReviewSlide(-1)">❮</button>
        <div class="reviews-carousel" id="reviews-slides">
          <?php
          if ($reviews) {
            foreach ($reviews as $review) {
              $stars = str_repeat('★', $review['rate']); // Tạo chuỗi sao dựa trên rate
          ?>
              <div class="review-slide">
                <div class="review-content">
                  <img src="/libertylaocai/view/img/<?php echo htmlspecialchars($review['img']); ?>" alt="Customer <?php echo htmlspecialchars($review['name']); ?>" class="review-image" loading="lazy">
                  <div class="review-stars"><?php echo $stars; ?></div>
                  <p class="review-text"><?php echo htmlspecialchars($review['content']); ?></p>
                </div>
              </div>
          <?php
            }
          } else {
            echo "<p>Không có bình luận nào để hiển thị.</p>";
          }
          ?>
        </div>
        <button class="reviews-carousel-btn right" onclick="changeReviewSlide(1)">❯</button>
      </div>
      <div class="reviews-dots">
        <!-- Dots will be generated dynamically via JS -->
      </div>
    </section>
    <section class="quality-recognition-section">
      <div class="container">
        <h2 class="quality-title"><?php echo $languageId == 1 ? 'CHẤT LƯỢNG ĐƯỢC CÔNG NHẬN' : 'RECOGNIZED QUALITY'; ?></h2>
        <p class="quality-subtitle"><?php echo $languageId == 1 ? 'CAM KẾT MANG ĐẾN DỊCH VỤ VÀ TIỆN NGHI' : 'COMMITMENT TO PROVIDING SERVICE AND CONVENIENCE'; ?></p>
        <!-- Mobile Carousel -->
        <div class="quality-carousel">
          <div class="carousel-track">
            <!-- Original set -->
            <div class="quality-item">
              <div class="quality-card">
                <div class="quality-icon">
                  <img src="/libertylaocai/view/img/186e5556-1d75-467a-b475-3373c7ad4c2d.png" alt="3 Star Certificate">
                </div>
                <div class="quality-badge"><?php echo $languageId == 1 ? 'Chứng nhận' : 'Certified'; ?></div>
                <?php if (!empty($descriptionQualityCard1)): ?>
                  <h3><?php echo htmlspecialchars($descriptionQualityCard1['title']); ?></h3>
                  <p><?php echo htmlspecialchars($descriptionQualityCard1['content']); ?></p>
                <?php endif; ?>
                <div class="quality-footer">
                  <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                </div>
              </div>
            </div>


            <div class="quality-item">
              <div class="quality-card">
                <div class="quality-icon booking-icon">
                  <img src="https://logos-world.net/wp-content/uploads/2021/08/Booking-Logo.png" alt="Booking.com">
                </div>
                <div class="quality-badge"><?php echo $languageId == 1 ? 'Chứng nhận' : 'Certified'; ?></div>
                <?php if (!empty($descriptionQualityCard2)): ?>
                  <h3><?php echo htmlspecialchars($descriptionQualityCard2['title']); ?></h3>
                  <p><?php echo htmlspecialchars($descriptionQualityCard2['content']); ?></p>
                <?php endif; ?>
                <div class="quality-footer">
                  <span class="hotel-name">Liberty Hotel - Lào Cai</span>

                </div>
              </div>
            </div>

            <div class="quality-item">
              <div class="quality-card">
                <div class="quality-icon">
                  <img src="https://cdn-icons-png.flaticon.com/512/1055/1055687.png" alt="Business License">
                </div>
                <div class="quality-badge"><?php echo $languageId == 1 ? 'Chứng nhận' : 'Certified'; ?></div>
                <?php if (!empty($descriptionQualityCard3)): ?>
                  <h3><?php echo htmlspecialchars($descriptionQualityCard3['title']); ?></h3>
                  <p><?php echo htmlspecialchars($descriptionQualityCard3['content']); ?></p>
                <?php endif; ?>
                <div class="quality-footer">
                  <span class="hotel-name">Liberty Hotel - Lào Cai</span>

                </div>
              </div>
            </div>

            <div class="quality-item">
              <div class="quality-card">
                <div class="quality-icon">
                  <img src="/libertylaocai/view/img/istockphoto-1137962021-612x612.jpg" alt="Fire Safety Certificate">
                </div>
                <div class="quality-badge"><?php echo $languageId == 1 ? 'Chứng nhận' : 'Certified'; ?></div>
                <?php if (!empty($descriptionQualityCard4)): ?>
                  <h3><?php echo htmlspecialchars($descriptionQualityCard4['title']); ?></h3>
                  <p><?php echo htmlspecialchars($descriptionQualityCard4['content']); ?></p>
                <?php endif; ?>
                <div class="quality-footer">
                  <span class="hotel-name">Liberty Hotel - Lào Cai</span>

                </div>
              </div>
            </div>

            <div class="quality-item">
              <div class="quality-card">
                <div class="quality-icon">
                  <img src="https://cdn-icons-png.flaticon.com/512/2921/2921222.png" alt="Food Safety Certificate">
                </div>
                <div class="quality-badge"><?php echo $languageId == 1 ? 'Chứng nhận' : 'Certified'; ?></div>
                <?php if (!empty($descriptionQualityCard5)): ?>
                  <h3><?php echo htmlspecialchars($descriptionQualityCard5['title']); ?></h3>
                  <p><?php echo htmlspecialchars($descriptionQualityCard5['content']); ?></p>
                <?php endif; ?>
                <div class="quality-footer">
                  <span class="hotel-name">Liberty Hotel - Lào Cai</span>

                </div>
              </div>
            </div>

            <!-- Duplicate set for seamless loop -->
            <div class="quality-item">
              <div class="quality-card">
                <div class="quality-icon">
                  <img src="/libertylaocai/view/img/186e5556-1d75-467a-b475-3373c7ad4c2d.png" alt="3 Star Certificate">
                </div>
                <div class="quality-badge"><?php echo $languageId == 1 ? 'Chứng nhận' : 'Certified'; ?></div>
                <?php if (!empty($descriptionQualityCard1)): ?>
                  <h3><?php echo htmlspecialchars($descriptionQualityCard1['title']); ?></h3>
                  <p><?php echo htmlspecialchars($descriptionQualityCard1['content']); ?></p>
                <?php endif; ?>
                <div class="quality-footer">
                  <span class="hotel-name">Liberty Hotel - Lào Cai</span>

                </div>
              </div>
            </div>


            <div class="quality-item">
              <div class="quality-card">
                <div class="quality-icon booking-icon">
                  <img src="https://logos-world.net/wp-content/uploads/2021/08/Booking-Logo.png" alt="Booking.com">
                </div>
                <div class="quality-badge"><?php echo $languageId == 1 ? 'Chứng nhận' : 'Certified'; ?></div>
                <?php if (!empty($descriptionQualityCard2)): ?>
                  <h3><?php echo htmlspecialchars($descriptionQualityCard2['title']); ?></h3>
                  <p><?php echo htmlspecialchars($descriptionQualityCard2['content']); ?></p>
                <?php endif; ?>
                <div class="quality-footer">
                  <span class="hotel-name">Liberty Hotel - Lào Cai</span>

                </div>
              </div>
            </div>

            <div class="quality-item">
              <div class="quality-card">
                <div class="quality-icon">
                  <img src="https://cdn-icons-png.flaticon.com/512/1055/1055687.png" alt="Business License">
                </div>
                <div class="quality-badge"><?php echo $languageId == 1 ? 'Chứng nhận' : 'Certified'; ?></div>
                <?php if (!empty($descriptionQualityCard3)): ?>
                  <h3><?php echo htmlspecialchars($descriptionQualityCard3['title']); ?></h3>
                  <p><?php echo htmlspecialchars($descriptionQualityCard3['content']); ?></p>
                <?php endif; ?>
                <div class="quality-footer">
                  <span class="hotel-name">Liberty Hotel - Lào Cai</span>

                </div>
              </div>
            </div>

            <div class="quality-item">
              <div class="quality-card">
                <div class="quality-icon">
                  <img src="/libertylaocai/view/img/istockphoto-1137962021-612x612.jpg" alt="Fire Safety Certificate">
                </div>
                <div class="quality-badge"><?php echo $languageId == 1 ? 'Chứng nhận' : 'Certified'; ?></div>
                <?php if (!empty($descriptionQualityCard4)): ?>
                  <h3><?php echo htmlspecialchars($descriptionQualityCard4['title']); ?></h3>
                  <p><?php echo htmlspecialchars($descriptionQualityCard4['content']); ?></p>
                <?php endif; ?>
                <div class="quality-footer">
                  <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                </div>
              </div>
            </div>

            <div class="quality-item">
              <div class="quality-card">
                <div class="quality-icon">
                  <img src="https://cdn-icons-png.flaticon.com/512/2921/2921222.png" alt="Food Safety Certificate">
                </div>
                <div class="quality-badge"><?php echo $languageId == 1 ? 'Chứng nhận' : 'Certified'; ?></div>
                <?php if (!empty($descriptionQualityCard5)): ?>
                  <h3><?php echo htmlspecialchars($descriptionQualityCard5['title']); ?></h3>
                  <p><?php echo htmlspecialchars($descriptionQualityCard5['content']); ?></p>
                <?php endif; ?>
                <div class="quality-footer">
                  <span class="hotel-name">Liberty Hotel - Lào Cai</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <?php include "footer.php"; ?>

  <script src="/libertylaocai/view/js/trangchu.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({
      offset: 0,
      duration: 1000, // thời gian chạy hiệu ứng (ms)
      once: false, // chỉ chạy 1 lần
      // mirror: true  
    });
  </script>
  <!-- Truyền dữ liệu carousel sang JavaScript -->
  <script>
    carousels = <?php echo json_encode($carouselData); ?>; // Gán giá trị thay vì khai báo const/let
    // Khởi tạo currentSlide cho từng carousel
    Object.keys(carousels).forEach(roomType => {
      carousels[roomType].currentSlide = 0;
    });
  </script>
</body>