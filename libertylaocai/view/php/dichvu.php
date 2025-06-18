<?php
require_once "session.php";
require_once "../../model/UserModel.php";

// Default language: Vietnamese (id = 1)
$current_language = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;
$language_id = ($current_language == 2) ? 2 : 1;
$features = getFeaturesByLanguage($language_id);
$services = getServices($language_id);
$tours = getToursByLanguage($language_id, 'tour');
$s = getToursByLanguage($language_id);


if (!empty($_SESSION['head_banner'])) {
  $getSelectedBanner = $_SESSION['head_banner'];
}

// Lấy thông tin khách sạn và danh mục header theo ngôn ngữ
$informationHotel = getHotelInfoWithLanguage($language_id);
?>

<!DOCTYPE html>
<html lang="<?php echo ($language_id == 1) ? 'vi' : 'en'; ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $language_id == 1 ? 'Dịch Vụ Du Lịch - Khách Sạn Liberty Lào Cai' : 'Travel Services - Liberty Hotel Lao Cai'; ?></title>
  <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
  <meta name="description" content="<?php echo $language_id == 1 ? 'Khám phá các dịch vụ du lịch và tiện ích đẳng cấp tại khách sạn Liberty Lào Cai, từ tour du lịch đến dịch vụ khách sạn.' : 'Explore top-class travel services and amenities at Liberty Hotel Lao Cai, from tours to hotel services.'; ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="/libertylaocai/view/css/dichvu.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>


<body>
  <?php include "header.php"; ?>

  <section class="hero">
    <img src="/libertylaocai/view/img/<?= $getSelectedBanner['image']; ?>" alt="Background" class="hero-background">
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <h1 class="hero-title"><?php echo $language_id == 1 ? 'Dịch Vụ Du Lịch Liberty Lào Cai' : 'Liberty Lào Cai Travel Services'; ?></h1>
    </div>
  </section>

  <section class="services-overview">
    <div class="container">
      <h2 class="section-title"><?php echo $language_id == 1 ? 'Dịch Vụ Khách Sạn' : 'Hotel Services'; ?></h2>
      <div class="services-grid">
        <?php if (empty($features)): ?>
          <p><?php echo $language_id == 1 ? 'Chưa có tiện ích nào được thêm.' : 'No features have been added yet.'; ?></p>
        <?php else: ?>
          <?php foreach ($features as $index => $feature): ?>
            <div class="service-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
              <div class="service-icon"><i class="<?php echo htmlspecialchars($feature['icon']); ?>"></i></div>
              <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
              <p><?php echo $feature['content']; ?></p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <?php foreach ($services as $index => $service): ?>
    <section class="<?php echo $service['id_dichvu'] == 1 ? 'document-service' : 'airport-service'; ?>">
      <div class="container">
        <div class="content-wrapper <?php echo $index % 2 == 1 ? 'reverse' : ''; ?>">
          <div class="service-image" data-aos="fade-<?php echo $index % 2 == 0 ? 'right' : 'left'; ?>">
            <img src="<?php echo $service['image'] ? htmlspecialchars($service['image']) : '/libertylaocai/view/img/default-service-image.png'; ?>" alt="<?php echo htmlspecialchars($service['title']); ?>">
          </div>
          <form action="/libertylaocai/user/submit" method="POST" style="display: inline;">
            <input type="hidden" name="chitietdichvu" value="<?php echo $service['id_dichvu']; ?>">
            <div class="text-content" data-aos="fade-<?php echo $index % 2 == 0 ? 'left' : 'right'; ?>">
              <h2 class="service-link"><?php echo htmlspecialchars($service['title']); ?></h2>
              <p class="service-link"><?php echo $service['content']; ?></p>
            </div>
          </form>
        </div>
      </div>
    </section>
  <?php endforeach; ?>

  <section class="tours-section">
    <div class="container">
      <h2 class="section-title"><?php echo $language_id == 1 ? 'Các Tour Du Lịch Hấp Dẫn' : 'Exciting Travel Tours'; ?></h2>
      <div class="tours-grid">
        <?php foreach ($tours as $index => $tour): ?>
          <form action="/libertylaocai/user/submit" method="POST" style="display:inline; ">
            <input type="hidden" name="chitietdichvu" value="<?php echo $tour['id_dichvu']; ?>">
            <div class="tour-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
              <div class="tour-image">
                <img src="<?php echo $tour['image'] ? htmlspecialchars($tour['image']) : '/libertylaocai/view/img/default-tour-image.png'; ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>">
                <div class="tour-overlay">
                  <span class="tour-price"><?php echo $language_id == 1 ? 'Liên hệ' : 'Contact'; ?></span>
                </div>
              </div>
              <div class="tour-content">
                <h3><?php echo htmlspecialchars($tour['title']); ?></h3>
                <p><?php echo $tour['content']; ?></p>
                <div class="tour-highlights">
                  <?php
                  $highlight_tags = [];
                  foreach ($highlight_tags as $tag): ?>
                    <span class="highlight-tag"><?php echo htmlspecialchars($tag); ?></span>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </form>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="contact-section">
    <div class="container">
      <div class="contact-wrapper">
        <?php foreach ($informationHotel as $info): ?>
          <div class="contact-info" data-aos="fade-right">
            <h2><?php echo $language_id == 1 ? 'Liên Hệ Đặt Tour' : 'Contact to Book a Tour'; ?></h2>
            <p><?php echo $language_id == 1 ? 'Hãy liên hệ với chúng tôi để được tư vấn và đặt tour một cách nhanh chóng nhất!' : 'Contact us for consultation and to book your tour as quickly as possible!'; ?></p>
            <div class="contact-methods">
              <div class="contact-method">
                <i class="fas fa-phone"></i>
                <span>Hotline: <?php echo htmlspecialchars($info['phone']); ?></span>
              </div>
              <div class="contact-method">
                <i class="fas fa-envelope"></i>
                <span>Email: <?php echo htmlspecialchars($info['email']); ?></span>
              </div>
              <div class="contact-method">
                <i class="fas fa-map-marker-alt"></i>
                <span><?php echo $language_id == 1 ? 'Địa chỉ: ' : 'Address: '; ?><?php echo htmlspecialchars($info['address']); ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <form id="contactForm" class="contact-form" data-aos="fade-left" method="POST">
          <div class="form-group">
            <input type="text" id="name" name="name" required>
            <label for="name"><?php echo $language_id == 1 ? 'Họ và tên' : 'Full Name'; ?></label>
          </div>
          <div class="form-group">
            <input type="text" id="phone" name="phone" required>
            <label for="phone"><?php echo $language_id == 1 ? 'Số điện thoại' : 'Phone Number'; ?></label>
          </div>
          <div class="form-group">
            <input type="email" id="email" name="email" required>
            <label for="email">Email</label>
          </div>
          <div class="form-group">
            <select id="service" name="service" required>
              <option value="" disabled selected><?php echo $language_id == 1 ? 'Chọn dịch vụ' : 'Select a service'; ?></option>
              <?php foreach ($s as $tour): ?>
                <option value="<?php echo htmlspecialchars($tour['title']); ?>"><?php echo htmlspecialchars($tour['title']); ?></option>
              <?php endforeach; ?>
            </select>
            <label for="service"><?php echo $language_id == 1 ? 'Dịch vụ quan tâm' : 'Service of Interest'; ?></label>
          </div>
          <div class="form-group">
            <textarea id="message" name="message" required></textarea>
            <label for="message"><?php echo $language_id == 1 ? 'Tin nhắn' : 'Message'; ?></label>
          </div>
          <button type="submit" class="submit-btn">
            <span><?php echo $language_id == 1 ? 'Gửi Yêu Cầu' : 'Send Request'; ?></span>
            <i class="fas fa-paper-plane"></i>
          </button>
        </form>
      </div>
    </div>
  </section>
  <!-- Overlay loading toàn màn hình -->
  <div id="fullScreenLoader" class="full-screen-loader" style="display: none;">
    <div class="loader-content">
      <i class="fas fa-spinner fa-spin fa-3x"></i>
      <p><?php echo $languageId == 1 ? 'Đang xử lý yêu cầu...' : 'Processing request...'; ?></p>
    </div>
  </div>

  <?php include "footer.php"; ?>

  <script src="/libertylaocai/view/js/dichvu.js"></script>
</body>

</html>