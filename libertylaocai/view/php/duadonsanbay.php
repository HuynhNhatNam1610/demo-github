<?php
require_once '../../model/config/connect.php';

// Khởi tạo session để lưu thông báo và ngôn ngữ
session_start();

// Lấy ngôn ngữ hiện tại từ session, mặc định là tiếng Việt (id = 1)
$current_language = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;
$language_id = ($current_language == 2) ? 2 : 1; // 1: Tiếng Việt, 2: Tiếng Anh

// Xử lý form đặt xe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['phone'], $_POST['vehicle'], $_POST['trip-type'], $_POST['pickup-time'], $_POST['passengers'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $vehicle_name = mysqli_real_escape_string($conn, $_POST['vehicle']);
    $trip_type = mysqli_real_escape_string($conn, $_POST['trip-type']);
    $pickup_time = mysqli_real_escape_string($conn, $_POST['pickup-time']);
    $passengers = (int)$_POST['passengers'];
    $note = mysqli_real_escape_string($conn, $_POST['note'] ?? '');

    // Lưu thông tin khách hàng
    $sql_khachhang = "INSERT INTO khachhang (name, phone, email) VALUES ('$name', '$phone', '$email')";
    if (mysqli_query($conn, $sql_khachhang)) {
        $id_khachhang = mysqli_insert_id($conn);
        
        // Lưu thông tin đặt xe
        $sql_datxe = "INSERT INTO datxe (id_khachhang, vehicle_name, trip_type, pickup_time, passengers, note) 
                      VALUES ($id_khachhang, '$vehicle_name', '$trip_type', '$pickup_time', $passengers, '$note')";
        if (mysqli_query($conn, $sql_datxe)) {
            $_SESSION['success_message'] = $language_id == 1 ? 
                'Đặt xe thành công! Chúng tôi sẽ liên hệ với bạn sớm.' : 
                'Booking successful! We will contact you soon.';
        } else {
            $_SESSION['error_message'] = $language_id == 1 ? 
                'Lỗi khi lưu thông tin đặt xe: ' . mysqli_error($conn) : 
                'Error saving booking information: ' . mysqli_error($conn);
        }
    } else {
        $_SESSION['error_message'] = $language_id == 1 ? 
            'Lỗi khi lưu thông tin khách hàng: ' . mysqli_error($conn) : 
            'Error saving customer information: ' . mysqli_error($conn);
    }
    
    // Redirect để tránh POST resubmission
    header('Location: ' . $_SERVER['PHP_SELF'] . '?booking=success#booking');
    exit();
}

// Lấy thông báo từ session và xóa sau khi hiển thị
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Lấy dữ liệu lời chào từ bảng loichaoduocchon
$sql_greeting = "SELECT nn.content 
                 FROM loichaoduocchon l 
                 JOIN nhungcauchaohoi_ngonngu nn ON l.id_nhungcauchaohoi_ngonngu = nn.id 
                 WHERE l.id_ngonngu = ? AND l.page = 'duadonsanbay' AND l.area = 'airport-shuttle-greeting'";
$greeting_stmt = $conn->prepare($sql_greeting);
$greeting_stmt->bind_param("i", $language_id);
$greeting_stmt->execute();
$greeting_result = $greeting_stmt->get_result();
$greeting = $greeting_result->num_rows > 0 ? $greeting_result->fetch_assoc()['content'] : 
    ($language_id == 1 ? 'Chuyên nghiệp - An toàn - Tiện lợi' : 'Professional - Safe - Convenient');
$greeting_stmt->close();

// Lấy dữ liệu mô tả từ bảng chon_mo_ta
$sql_service = "SELECT mn.title, mn.content 
                FROM chon_mo_ta cmt 
                JOIN mota_ngonngu mn ON cmt.id_mota_ngonngu = mn.id 
                WHERE cmt.area = 'airport-shuttle-description' AND cmt.language_id = ?";
$service_stmt = $conn->prepare($sql_service);
$service_stmt->bind_param("i", $language_id);
$service_stmt->execute();
$service_result = $service_stmt->get_result();
$service = $service_result->num_rows > 0 ? $service_result->fetch_assoc() : [
    'title' => $language_id == 1 ? 'Dịch Vụ Đưa Đón Sân Bay Chuyên Nghiệp' : 'Professional Airport Transfer Service',
    'content' => $language_id == 1 ? 
        'Chuyến đi của quý vị sẽ thuận tiện và thoải mái hơn với dịch vụ đưa đón sân bay chuyên nghiệp. Hãy để đội ngũ tài xế giàu kinh nghiệm cùng phương tiện hiện đại của chúng tôi đồng hành cùng quý vị ngay từ những phút đầu tiên đặt chân đến Lào Cai!' : 
        'Your trip will be more convenient and comfortable with our professional airport transfer service. Let our experienced drivers and modern vehicles accompany you from the moment you arrive in Lào Cai!'
];
$service_stmt->close();

// Lấy dữ liệu tiện ích
$sql_features = "SELECT t.icon, tn.title, tn.content 
                FROM tienich t 
                JOIN tienich_ngonngu tn ON t.id = tn.id_tienich 
                JOIN tienichdichvu td ON t.id = td.id_tienich 
                WHERE tn.id_ngonngu = ? AND td.page = 'duadonsanbay' AND t.active = 1";
$features_stmt = $conn->prepare($sql_features);
$features_stmt->bind_param("i", $language_id);
$features_stmt->execute();
$features_result = $features_stmt->get_result();
$features = [];
while ($row = $features_result->fetch_assoc()) {
    $features[] = $row;
}
$features_stmt->close();

// Lấy dữ liệu loại xe
$sql_vehicles = "SELECT xn.name, x.price, x.number_seat, x.image_car 
                FROM xeduadon x 
                JOIN xeduadon_ngonngu xn ON x.id = xn.id_xeduadon 
                WHERE x.id_dichvu = 1 AND xn.id_ngonngu = ?";
$result_vehicles = $conn->prepare($sql_vehicles);
$result_vehicles->bind_param("i", $language_id);
$result_vehicles->execute();
$vehicles = [];
$result = $result_vehicles->get_result();
while ($row = $result->fetch_assoc()) {
    $vehicles[] = $row;
}
$result_vehicles->close();

// Lấy dữ liệu FAQ
$sql_faqs = "SELECT question, answer FROM cauhoithuonggap_ngonngu WHERE id_ngonngu = ?";
$faqs_stmt = $conn->prepare($sql_faqs);
$faqs_stmt->bind_param("i", $language_id);
$faqs_stmt->execute();
$faqs_result = $faqs_stmt->get_result();
$faqs = [];
while ($row = $faqs_result->fetch_assoc()) {
    $faqs[] = $row;
}
$faqs_stmt->close();
?>

<!DOCTYPE html>
<html lang="<?php echo $language_id == 1 ? 'vi' : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $language_id == 1 ? 'Dịch Vụ Đưa Đón Sân Bay Nội Bài - Liberty Lào Cai' : 'Noi Bai Airport Transfer Service - Liberty Lào Cai'; ?></title>
    <link rel="stylesheet" href="/libertylaocai/view/css/duadonsanbay.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include "header.php"; ?>
    <div class="duadonsanbay-container">
        <!-- Hero Section -->
        <section class="hero">
            <img src="/libertylaocai/view/images/hero-image.jpg" alt="Hero Background" class="hero-background">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1 class="hero-title"><?php echo $language_id == 1 ? 'Dịch Vụ Đưa Đón Sân Bay Nội Bài' : 'Noi Bai Airport Transfer Service'; ?></h1>
                <p class="hero-subtitle"><?php echo htmlspecialchars($greeting); ?></p>
                <div class="hero-cta">
                    <a href="#booking" class="cta-btn primary"><?php echo $language_id == 1 ? 'Đặt Ngay' : 'Book Now'; ?></a>
                    <a href="#contact" class="cta-btn secondary"><?php echo $language_id == 1 ? 'Liên Hệ' : 'Contact'; ?></a>
                </div>
            </div>
        </section>

        <!-- Service Overview -->
        <section class="service-overview">
            <div class="container">
                <div class="content-wrapper">
                    <div class="text-content">
                        <h2><?php echo htmlspecialchars($service['title']); ?></h2>
                        <p class="lead"><?php echo htmlspecialchars($service['content']); ?></p>
                        <div class="service-stats">
                            <div class="stat-item">
                                <span class="stat-number">500+</span>
                                <span class="stat-label"><?php echo $language_id == 1 ? 'Khách hàng hài lòng' : 'Satisfied Customers'; ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">24/7</span>
                                <span class="stat-label"><?php echo $language_id == 1 ? 'Hỗ trợ liên tục' : 'Continuous Support'; ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">100%</span>
                                <span class="stat-label"><?php echo $language_id == 1 ? 'An toàn đảm bảo' : 'Safety Guaranteed'; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="image-content">
                        <img src="/libertylaocai/view/img/service-image.jpg" alt="Service Image" class="service-image main">
                        <img src="/libertylaocai/view/img/overlay-image.jpg" alt="Overlay Image" class="service-image overlay">
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section">
            <div class="container">
                <h2 class="section-title"><?php echo $language_id == 1 ? 'Tại Sao Chọn Chúng Tôi?' : 'Why Choose Us?'; ?></h2>
                <div class="features-grid">
                    <?php foreach ($features as $feature): ?>
                        <div class="feature-card">
                            <div class="feature-icon"><i class="<?php echo htmlspecialchars($feature['icon']); ?>"></i></div>
                            <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
                            <p><?php echo htmlspecialchars($feature['content']); ?></p>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($features)): ?>
                        <p><?php echo $language_id == 1 ? 'Không có tiện ích nào được tìm thấy.' : 'No features found.'; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Vehicle Section -->
        <section class="vehicle-section">
            <div class="container">
                <h2 class="section-title"><?php echo $language_id == 1 ? 'Loại Xe Phục Vụ' : 'Vehicle Types'; ?></h2>
                <div class="vehicle-carousel">
                    <div class="vehicle-wrapper" id="vehicleWrapper">
                        <?php foreach ($vehicles as $index => $vehicle): ?>
                            <div class="vehicle-slide">
                                <div class="vehicle-card">
                                    <div class="vehicle-image">
                                        <img src="<?php echo htmlspecialchars($vehicle['image_car'] ? '/libertylaocai/view/img/' . $vehicle['image_car'] : '/libertylaocai/view/images/default-car-image.jpg'); ?>" 
                                             alt="<?php echo htmlspecialchars($vehicle['name']); ?>">
                                        <span class="vehicle-badge">
                                            <?php
                                            $badges = $language_id == 1 ? ['Phổ biến', 'Gia đình', 'Nhóm lớn', 'VIP', 'Premium'] : ['Popular', 'Family', 'Large Group', 'VIP', 'Premium'];
                                            echo htmlspecialchars($badges[$index % count($badges)]);
                                            ?>
                                        </span>
                                    </div>
                                    <div class="vehicle-info">
                                        <h3><?php echo htmlspecialchars($vehicle['name']); ?></h3>
                                        <div class="vehicle-features">
                                            <span><i class="fas fa-users"></i> 1-<?php echo htmlspecialchars($vehicle['number_seat']); ?> <?php echo $language_id == 1 ? 'người' : 'people'; ?></span>
                                            <span><i class="fas fa-suitcase"></i> <?php echo htmlspecialchars($vehicle['number_seat'] <= 4 ? '2-3 vali' : ($vehicle['number_seat'] <= 7 ? '4-5 vali' : 'Nhiều hành lý')); ?></span>

                                        </div>
                                        <div class="vehicle-price">
                                            <span class="price"><?php echo htmlspecialchars($vehicle['price']); ?></span>
                                            <span class="price-unit"><?php echo $language_id == 1 ? '/ chuyến' : '/ trip'; ?></span>
                                        </div>
                                        <button class="book-btn" onclick="bookVehicle('<?php echo strtolower(str_replace(' ', '', $vehicle['name'])); ?>')"><?php echo $language_id == 1 ? 'Đặt Xe' : 'Book Now'; ?></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="nav-arrow prev" id="prevBtn"><i class="fas fa-chevron-left"></i></button>
                    <button class="nav-arrow next" id="nextBtn"><i class="fas fa-chevron-right"></i></button>
                    <div class="carousel-indicators" id="indicators"></div>
                </div>
            </div>
        </section>

        <!-- Route Section -->
        <section class="route-section">
            <div class="container">
                <h2 class="section-title"><?php echo $language_id == 1 ? 'Thông Tin Tuyến Đường' : 'Route Information'; ?></h2>
                <div class="route-info">
                    <div class="route-details">
                        <div class="route-header">
                            <h3><?php echo $language_id == 1 ? 'Sân Bay Nội Bài ⇄ Liberty Lào Cai' : 'Noi Bai Airport ⇄ Liberty Lào Cai'; ?></h3>
                            <div class="route-badges">
                                <span class="badge distance"><?php echo $language_id == 1 ? '~320 km' : '~320 km'; ?></span>
                                <span class="badge time"><?php echo $language_id == 1 ? '~4.5 giờ' : '~4.5 hours'; ?></span>
                                <span class="badge highway"><?php echo $language_id == 1 ? 'Cao tốc' : 'Highway'; ?></span>
                            </div>
                        </div>
                        <div class="route-timeline">
                            <div class="timeline-item">
                                <div class="timeline-icon"><i class="fas fa-plane-departure"></i></div>
                                <div class="timeline-content">
                                    <h4><?php echo $language_id == 1 ? 'Sân Bay Nội Bài' : 'Noi Bai Airport'; ?></h4>
                                    <p><?php echo $language_id == 1 ? 'Điểm đón tại khu vực quy định' : 'Pickup at designated area'; ?></p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon"><i class="fas fa-road"></i></div>
                                <div class="timeline-content">
                                    <h4><?php echo $language_id == 1 ? 'Cao tốc Nội Bài - Lào Cai' : 'Noi Bai - Lào Cai Highway'; ?></h4>
                                    <p><?php echo $language_id == 1 ? 'Di chuyển trên tuyến cao tốc hiện đại' : 'Travel on a modern highway'; ?></p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="timeline-content">
                                    <h4><?php echo $language_id == 1 ? 'Liberty Lào Cai' : 'Liberty Lào Cai'; ?></h4>
                                    <p><?php echo $language_id == 1 ? 'Điểm đến cuối cùng' : 'Final destination'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="route-map">
                        <div class="map-placeholder">
                            <i class="fas fa-map"></i>
                            <p><?php echo $language_id == 1 ? 'Bản đồ tuyến đường<br>Sân Bay Nội Bài → Lào Cai' : 'Route Map<br>Noi Bai Airport → Lào Cai'; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Booking Section -->
        <section class="booking-section" id="booking">
            <div class="container">
                <div class="booking-wrapper">
                    <div class="booking-info">
                        <h2><?php echo $language_id == 1 ? 'Đặt Xe Ngay' : 'Book Now'; ?></h2>
                        <p><?php echo $language_id == 1 ? 'Điền thông tin để chúng tôi có thể phục vụ bạn tốt nhất' : 'Fill in your details for the best service'; ?></p>
                        <div class="booking-benefits">
                            <div class="benefit-item"><i class="fas fa-check-circle"></i> <?php echo $language_id == 1 ? 'Xác nhận đặt chỗ ngay lập tức' : 'Instant booking confirmation'; ?></div>
                            <div class="benefit-item"><i class="fas fa-check-circle"></i> <?php echo $language_id == 1 ? 'Miễn phí hủy trước 24h' : 'Free cancellation 24h before'; ?></div>
                            <div class="benefit-item"><i class="fas fa-check-circle"></i> <?php echo $language_id == 1 ? 'Hỗ trợ 24/7' : '24/7 Support'; ?></div>
                        </div>
                    </div>
                    <form class="booking-form" method="POST">
                        <?php if ($success_message): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <?php echo htmlspecialchars($success_message); ?>
                            </div>
                        <?php elseif ($error_message): ?>
                            <div class="alert alert-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" name="name" required>
                                <label><?php echo $language_id == 1 ? 'Họ và tên' : 'Full Name'; ?></label>
                            </div>
                            <div class="form-group">
                                <input type="tel" name="phone" required>
                                <label><?php echo $language_id == 1 ? 'Số điện thoại' : 'Phone Number'; ?></label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="email" name="email">
                                <label>Email</label>
                            </div>
                            <div class="form-group">
                                <input type="number" name="passengers" min="1" required>
                                <label><?php echo $language_id == 1 ? 'Số hành khách' : 'Number of Passengers'; ?></label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <select name="vehicle" required>
                                    <option value=""><?php echo $language_id == 1 ? 'Chọn loại xe' : 'Select Vehicle'; ?></option>
                                    <?php foreach ($vehicles as $vehicle): ?>
                                        <option value="<?php echo htmlspecialchars($vehicle['name']); ?>">
                                            <?php echo htmlspecialchars($vehicle['name'] . ' - ' . $vehicle['price']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label><?php echo $language_id == 1 ? 'Chọn loại xe' : 'Select Vehicle'; ?></label>
                            </div>
                            <div class="form-group">
                                <select name="trip-type" required>
                                    <option value=""><?php echo $language_id == 1 ? 'Chọn hình thức' : 'Select Trip Type'; ?></option>
                                    <option value="one-way"><?php echo $language_id == 1 ? 'Một chiều' : 'One-Way'; ?></option>
                                    <option value="round-trip"><?php echo $language_id == 1 ? 'Khứ hồi' : 'Round-Trip'; ?></option>
                                </select>
                                <label><?php echo $language_id == 1 ? 'Chọn hình thức' : 'Select Trip Type'; ?></label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="datetime-local" name="pickup-time" required>
                                <label><?php echo $language_id == 1 ? 'Thời gian đón' : 'Pickup Time'; ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea name="note"></textarea>
                            <label><?php echo $language_id == 1 ? 'Ghi chú' : 'Notes'; ?></label>
                        </div>
                        <button type="submit" class="submit-btn"><?php echo $language_id == 1 ? 'Đặt Xe Ngay' : 'Book Now'; ?></button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="contact-section" id="contact">
            <div class="container">
                <div class="contact-wrapper">
                    <div class="contact-info">
                        <h2><?php echo $language_id == 1 ? 'Liên Hệ Trực Tiếp' : 'Direct Contact'; ?></h2>
                        <p><?php echo $language_id == 1 ? 'Cần hỗ trợ ngay? Hãy liên hệ với chúng tôi qua các kênh sau:' : 'Need immediate assistance? Contact us through the following channels:'; ?></p>
                        <div class="contact-methods">
                            <div class="contact-method hotline">
                                <div class="method-icon"><i class="fas fa-phone"></i></div>
                                <div class="method-info">
                                    <h4><?php echo $language_id == 1 ? 'Hotline 24/7: 0214 366 1666' : '24/7 Hotline: 0214 366 1666'; ?></h4>
                                    <p><?php echo $language_id == 1 ? 'Miễn phí gọi từ di động' : 'Free calls from mobile'; ?></p>
                                </div>
                            </div>
                            <div class="contact-method email">
                                <div class="method-icon"><i class="fas fa-envelope"></i></div>
                                <div class="method-info">
                                    <h4>Email: [email protected]</h4>
                                    <p><?php echo $language_id == 1 ? 'Phản hồi trong 30 phút' : 'Response within 30 minutes'; ?></p>
                                </div>
                            </div>
                            <div class="contact-method zalo">
                                <div class="method-icon"><i class="fas fa-comment-dots"></i></div>
                                <div class="method-info">
                                    <h4><?php echo $language_id == 1 ? 'Zalo/Messenger: 0214 366 1666' : 'Zalo/Messenger: 0214 366 1666'; ?></h4>
                                    <p><?php echo $language_id == 1 ? 'Nhắn tin trực tiếp' : 'Direct messaging'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="faq-section">
                        <h3><?php echo $language_id == 1 ? 'Câu Hỏi Thường Gặp' : 'Frequently Asked Questions'; ?></h3>
                        <div class="faq-list">
                            <?php foreach ($faqs as $faq): ?>
                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php echo htmlspecialchars($faq['question']); ?></h4>
                                        <i class="fas fa-plus"></i>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php echo htmlspecialchars($faq['answer']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include "footer.php"; ?>
    
    <!-- Auto scroll to booking section if success -->
    <?php if (isset($_GET['booking']) && $_GET['booking'] === 'success'): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('booking').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 500);
        });
    </script>
    <?php endif; ?>
    
    <script src="/libertylaocai/view/js/duadonsanbay.js"></script>
</body>
</html>