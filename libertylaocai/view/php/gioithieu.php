<?php
require_once "session.php";
require_once "../../model/UserModel.php";

// Lấy language_id từ session, mặc định là 1 (tiếng Việt)
$languageId = isset($_SESSION['language_id']) ? (int)$_SESSION['language_id'] : 1;

// Lấy thông tin khách sạn theo ngôn ngữ
$informationHotel = getHotelInfoWithLanguage($languageId);

// Lấy danh sách dịch vụ theo ngôn ngữ
$services = getServicesWithLanguage($languageId);

$labels = [
    'section_title' => ['Dịch vụ của chúng tôi', 'Our Services'],
    'section_desc' => ['Trải nghiệm đa dạng các dịch vụ chất lượng cao', 'Experience a wide range of high-quality services'],

    // Dịch vụ lưu trú
    'stay_title' => ['Dịch vụ lưu trú', 'Accommodation Services'],
    'stay_desc' => [
        'Hệ thống phòng nghỉ sang trọng với đầy đủ tiện nghi: máy điều hòa, TV, WiFi tốc độ cao. Từ phòng Deluxe đến căn hộ gia đình, mang đến sự thoải mái tuyệt đối.',
        'Luxurious rooms with full amenities: air conditioning, TV, high-speed WiFi. From Deluxe rooms to family apartments, providing ultimate comfort.'
    ],
    'stay_features' => [
        ['Phòng Deluxe với view thành phố', 'Deluxe rooms with city view'],
        ['Căn hộ gia đình rộng rãi', 'Spacious family apartments'],
        ['Đầy đủ tiện nghi hiện đại', 'Fully equipped with modern amenities']
    ],

    // Tổ chức sự kiện
    'event_title' => ['Tổ chức sự kiện', 'Event Organization'],
    'event_desc' => [
        'Không gian lý tưởng để tổ chức các sự kiện quan trọng với hệ thống phòng hội nghị hiện đại, sức chứa từ 20 đến 500 khách.',
        'Ideal space for hosting important events with modern conference rooms, accommodating 20 to 500 guests.'
    ],
    'event_features' => [
        ['Hội thảo, hội nghị chuyên nghiệp', 'Professional seminars & conferences'],
        ['Tiệc cưới, sinh nhật sang trọng', 'Elegant weddings & birthday parties'],
        ['Sự kiện doanh nghiệp đa dạng', 'Diverse corporate events']
    ],

    // Nhà hàng & Bar
    'restaurant_title' => ['Nhà hàng & Bar', 'Restaurant & Bar'],
    'restaurant_desc' => [
        'Thưởng thức những món ăn đặc sản miền Tây Bắc và thư giãn tại Sky Bar với không gian lãng mạn nhìn ra toàn thành phố.',
        'Enjoy Northwest specialties and relax at Sky Bar with a romantic city view.'
    ],
    'restaurant_features' => [
        ['Đặc sản miền Tây Bắc', 'Northwest specialties'],
        ['Sky Bar view toàn thành phố', 'Sky Bar with panoramic city view'],
        ['Band nhạc hàng tuần', 'Weekly live band']
    ],

    // Giấy thông hành
    'passport_title' => ['Hỗ trợ giấy thông hành', 'Travel Permit Support'],
    'passport_desc' => [
        'Hỗ trợ làm giấy thông hành du lịch Trung Quốc với quy trình đơn giản, nhanh chóng và đúng quy định.',
        'Support for China travel permit with a simple, quick, and legal process.'
    ],
    'passport_features' => [
        ['Quy trình nhanh chóng', 'Fast process'],
        ['Đúng quy định pháp luật', 'Legally compliant'],
        ['Hỗ trợ tư vấn miễn phí', 'Free consultation support']
    ],

    // Tour du lịch
    'tour_title' => ['Cung cấp tour du lịch', 'Tour Packages'],
    'tour_desc' => [
        'Cung cấp đa dạng các tour du lịch trong và ngoài nước.',
        'Providing a variety of domestic and international tours.'
    ],
    'tour_features' => [
        ['Sapa', 'Sapa'],
        ['Bắc Hà', 'Bac Ha'],
        ['Y tý', 'Y Ty'],
        ['Hà khẩu - Trung quốc', 'He Kou - China']
    ],

    // Đưa đón
    'car_title' => ['Đưa đón tận nơi', 'Pick-up & Drop-off'],
    'car_desc' => [
        'Cung cấp dịch vụ đưa đón Sân bay Lào Cai - Liberty Hotel',
        'Provide transfer service from Lao Cai Airport to Liberty Hotel'
    ],
    'car_features' => [
        ['Giá cả hợp lý', 'Reasonable pricing'],
        ['Tài xế chuyên nghiệp', 'Professional drivers'],
        ['Hỗ trợ 24/7', '24/7 support'],
        ['An toàn tuyệt đối', 'Absolute safety']
    ],
];

// Lấy tất cả loại phòng với ảnh ngẫu nhiên (ngôn ngữ mặc định: 1 - Tiếng Việt)
$rooms = getAllRoomTypesWithRandomImage($languageId);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $languageId == 1 ? 'Giới Thiệu - Khách Sạn Liberty Lào Cai' : 'About Us - Liberty Hotel Lao Cai'; ?></title>
    <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
    <meta name="description" content="<?php echo $languageId == 1 ? 'Tìm hiểu về khách sạn Liberty Lào Cai với dịch vụ lưu trú, sự kiện, nhà hàng và tour du lịch đẳng cấp.' : 'Learn about Liberty Hotel Lao Cai with top-class accommodation, events, dining, and tour services.'; ?>">
    <link rel="stylesheet" href="/libertylaocai/view/css/gioithieu.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>


<body>
    <?php include "header.php"; ?>
    <div class="big-container">
        <!-- Hero Video Section -->
        <?php foreach ($informationHotel as $info): ?>
            <section class="hero-video">

                <div class="video-container">
                    <iframe
                        width="100%"
                        height="100%"
                        src="<?php echo $info['iframe_ytb']; ?>"
                        title="YouTube video player"
                        frameborder="0"
                        allow="autoplay; encrypted-media"
                        allowfullscreen
                        id="heroVideo">
                    </iframe>

                    <div class="video-overlay"></div>
                </div>

                <div class="hero-content">
                    <h1 class="hero-title"><?php echo $info['name']; ?></h1>
                    <!-- <p class="hero-subtitle">Không gian giao thoa giữa sự tiện nghi, hiện đại và những trải nghiệm đậm chất bản địa</p> -->
                    <div class="hero-cta">
                        <button class="btn-primary"><?php echo $languageId == 1 ? 'Khám phá ngay' : 'Explore Now'; ?></button>
                    </div>
                </div>
            </section>

            <!-- Welcome Section -->
            <section class="welcome-section">
                <div class="container">
                    <div class="welcome-content">
                        <div class="section-header">
                            <h2><?php echo $info['name']; ?></h2>
                            <div class="divider"></div>
                        </div>
                        <div class="welcome-grid">
                            <div class="welcome-text">
                                <?php echo $info['description']; ?>
                                <div class="motto">
                                    <i class="fas fa-quote-left"></i>
                                    <span><?php echo $languageId == 1 ? 'Tận tâm, chuyên nghiệp và sang trọng' : 'Dedicated, professional and luxurious'; ?></span>
                                    <i class="fas fa-quote-right"></i>
                                </div>
                            </div>
                            <div class="welcome-stats">
                                <div class="stat-item">
                                    <div class="stat-number">45</div>
                                    <div class="stat-label"><?php echo $languageId == 1 ? 'Phòng nghỉ' : 'Rooms'; ?></div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">3</div>
                                    <div class="stat-label"><?php echo $languageId == 1 ? 'Sao quốc tế' : 'International Stars'; ?></div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">500</div>
                                    <div class="stat-label"><?php echo $languageId == 1 ? 'Sức chứa hội nghị' : 'Conference Capacity'; ?></div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">700</div>
                                    <div class="stat-label"><?php echo $languageId == 1 ? 'Chỗ ngồi nhà hàng' : 'Restaurant Seats'; ?></div>
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
                        <h2><?= $labels['section_title'][$languageId == 1 ? 0 : 1]; ?></h2>
                        <div class="divider"></div>
                        <p><?= $labels['section_desc'][$languageId == 1 ? 0 : 1]; ?></p>
                    </div>
                    <div class="services-grid">
                        <!-- Dịch vụ lưu trú -->
                        <?php foreach ($services as $service): ?>
                            <div class="service-card">
                                <div class="service-icon">
                                    <?php if (!empty($info['logo'])): ?>
                                        <div class="service-logo">
                                            <img src="/libertylaocai/view/img/<?= htmlspecialchars($info['logo']); ?>" alt="Logo">
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="service-description service-description-<?php echo $index; ?>">
                                    <?= $service['description']; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>


        <?php endforeach; ?>
    </div>
    <?php include "footer.php"; ?>
    <script src="/libertylaocai/view/js/gioithieu.js"></script>
</body>

</html>