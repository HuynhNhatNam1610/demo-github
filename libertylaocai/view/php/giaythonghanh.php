<?php
// Kết nối database
require_once '../../model/config/connect.php';
session_start(); // Bắt đầu session để lấy language_id

// Lấy ngôn ngữ hiện tại từ session, mặc định là tiếng Việt (id = 1)
$current_language = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;
$language_id = ($current_language == 2) ? 2 : 1; // 1: Tiếng Việt, 2: Tiếng Anh

// Lấy lời chào từ bảng loichaoduocchon
$sql_greeting = "
    SELECT nn.content 
    FROM loichaoduocchon l 
    JOIN nhungcauchaohoi_ngonngu nn ON l.id_nhungcauchaohoi_ngonngu = nn.id
    WHERE l.id_ngonngu = ? AND l.page = 'giaythonghanh' AND l.area = 'passport-service-greeting'
    LIMIT 1";
$stmt_greeting = $conn->prepare($sql_greeting);
$stmt_greeting->bind_param("i", $language_id);
$stmt_greeting->execute();
$result_greeting = $stmt_greeting->get_result();
$greeting_text = ($language_id == 1) ? "Quy trình đơn giản, nhanh chóng và đúng quy định" : "Simple, fast, and compliant process"; // Default
if ($result_greeting->num_rows > 0) {
    $row_greeting = $result_greeting->fetch_assoc();
    $greeting_text = $row_greeting['content'];
}
$stmt_greeting->close();

// Lấy thông tin mô tả "Tại Sao Chọn Dịch Vụ Của Chúng Tôi?" từ bảng chon_mo_ta
$sql_description = "
    SELECT mn.title, mn.content 
    FROM chon_mo_ta cmt 
    JOIN mota_ngonngu mn ON cmt.id_mota_ngonngu = mn.id
    WHERE cmt.area = 'passport-service-description' AND cmt.language_id = ?";
$stmt_description = $conn->prepare($sql_description);
$stmt_description->bind_param("i", $language_id);
$stmt_description->execute();
$result_description = $stmt_description->get_result();
$description_title = ($language_id == 1) ? "Tại Sao Chọn Dịch Vụ Của Chúng Tôi?" : "Why Choose Our Service?";
$description_content = ($language_id == 1) ? 
    "Với vị trí tọa lạc liền kề biên giới, Khách sạn Liberty Lào Cai sẵn lòng hỗ trợ quý khách làm giấy thông hành du lịch Trung Quốc với quy trình đơn giản, nhanh chóng và đúng quy định, cam kết mang đến sự an tâm tuyệt đối cho quý khách trên hành trình của mình." : 
    "Located adjacent to the border, Liberty Lào Cai Hotel is dedicated to assisting customers in obtaining a China travel pass with a simple, fast, and compliant process, ensuring complete peace of mind for your journey.";
if ($result_description->num_rows > 0) {
    $row_description = $result_description->fetch_assoc();
    $description_title = $row_description['title'];
    $description_content = $row_description['content'];
}
$stmt_description->close();

// Lấy giá dịch vụ giấy thông hành (id = 1)
$sql_price = "SELECT price FROM dichvu WHERE id = 1";
$result_price = $conn->query($sql_price);
$service_price = "320000"; // Giá trị mặc định
$is_numeric_price = false; // Biến kiểm tra giá có phải là số không
if ($result_price->num_rows > 0) {
    $row_price = $result_price->fetch_assoc();
    $service_price = $row_price['price'];
    // Kiểm tra xem giá có phải là số không
    $is_numeric_price = is_numeric($service_price);
}

// Lấy danh sách tiện ích từ bảng tienichgiaythonghanh
$sql_features = "
    SELECT t.id as id_tienich, t.icon, tn.title, tn.content 
    FROM tienich t 
    JOIN tienich_ngonngu tn ON t.id = tn.id_tienich 
    JOIN tienichdichvu td ON t.id = td.id_tienich 
    WHERE tn.id_ngonngu = ? AND td.page = 'giaythonghanh'";
$stmt_features = $conn->prepare($sql_features);
$stmt_features->bind_param("i", $language_id);
$stmt_features->execute();
$result_features = $stmt_features->get_result();
$features = [];
while ($row = $result_features->fetch_assoc()) {
    $features[] = $row;
}
$stmt_features->close();

// Xử lý form đăng ký dịch vụ
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register_service'])) {
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $people_count = $_POST['people_count'];
    $travel_date = $_POST['travel_date'];
    $note = $_POST['note'];
    $total_cost = $_POST['total_cost'];
    
    $sql_customer = "INSERT INTO khachhang (name, phone, email) VALUES (?, ?, ?)";
    $stmt_customer = $conn->prepare($sql_customer);
    $stmt_customer->bind_param("sss", $name, $phone, $email);
    
    if ($stmt_customer->execute()) {
        $customer_id = $conn->insert_id;
        
        $travel_date_formatted = !empty($travel_date) ? $travel_date : null;
        $sql_booking = "INSERT INTO datdichvu (id_khachhang, id_dichvu, so_luong_nguoi, ngay_du_kien, ghi_chu, tong_chi_phi) VALUES (?, 1, ?, ?, ?, ?)";
        $stmt_booking = $conn->prepare($sql_booking);
        $stmt_booking->bind_param("iisss", $customer_id, $people_count, $travel_date_formatted, $note, $total_cost);
        
        if ($stmt_booking->execute()) {
            ob_end_clean();
            echo json_encode(['success' => true, 'message' => ($language_id == 1) ? "Đăng ký dịch vụ thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất." : "Service registration successful! We will contact you soon."]);
        } else {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => ($language_id == 1) ? "Lỗi lưu đơn dịch vụ: " . $conn->error : "Error saving booking: " . $conn->error]);
        }
    } else {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => ($language_id == 1) ? "Lỗi lưu thông tin khách hàng: " . $conn->error : "Error saving customer info: " . $conn->error]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="<?php echo ($language_id == 1) ? 'vi' : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($language_id == 1) ? 'Dịch vụ hỗ trợ giấy thông hành du lịch Trung Quốc - Liberty Lào Cai' : 'China Travel Pass Support Service - Liberty Lào Cai'; ?></title>
    <link rel="stylesheet" href="/libertylaocai/view/css/giaythonghanh.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <?php include "header.php"; ?>
    <div class="big-container">
        <div class="hero-section">
            <div class="hero-overlay">
                <div class="container">
                    <div class="hero-content">
                        <h1 class="hero-title"><?php echo ($language_id == 1) ? 'DỊCH VỤ HỖ TRỢ GIẤY THÔNG HÀNH DU LỊCH TRUNG QUỐC' : 'CHINA TRAVEL PASS SUPPORT SERVICE'; ?></h1>
                        <p class="hero-subtitle"><?php echo htmlspecialchars($greeting_text); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <section class="service-overview">
            <div class="container">
                <div class="section-header">
                    <h2><?php echo htmlspecialchars($description_title); ?></h2>
                    <p><?php echo htmlspecialchars($description_content); ?></p>
                </div>
                <div class="benefits-grid">
                    <?php if (empty($features)): ?>
                        <p><?php echo ($language_id == 1) ? 'Chưa có tiện ích nào được cấu hình.' : 'No features configured yet.'; ?></p>
                    <?php else: ?>
                        <?php foreach ($features as $feature): ?>
                            <div class="benefit-card">
                                <i class="<?php echo htmlspecialchars($feature['icon']); ?> benefit-icon" data-db-icon="<?php echo htmlspecialchars($feature['icon']); ?>"></i>
                                <h3 data-db-title="<?php echo htmlspecialchars($feature['title']); ?>"><?php echo htmlspecialchars($feature['title']); ?></h3>
                                <p data-db-content="<?php echo htmlspecialchars($feature['content']); ?>"><?php echo htmlspecialchars($feature['content']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="service-details">
            <div class="container">
                <div class="service-card">
                    <div class="service-header">
                        <h3><?php echo ($language_id == 1) ? 'Dịch Vụ Làm Thủ Tục Đi Thăm Quan Trung Quốc' : 'China Sightseeing Procedure Service'; ?></h3>
                        <span class="price-tag">
                            <?php 
                            if ($is_numeric_price) {
                                echo number_format($service_price); 
                                echo ($language_id == 1) ? ' VNĐ' : ''; // Chỉ thêm VNĐ cho tiếng Việt
                            } else {
                                echo htmlspecialchars($service_price); // Hiển thị trực tiếp nếu không phải số
                            }
                            ?>
                        </span>
                    </div>
                    <div class="service-content">
                        <div class="service-info">
                            <div class="info-item">
                                <span class="label"><?php echo ($language_id == 1) ? 'Thời gian xử lý:' : 'Processing time:'; ?></span>
                                <span class="value"><?php echo ($language_id == 1) ? 'Trong ngày' : 'Within the day'; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label"><?php echo ($language_id == 1) ? 'Giấy tờ cần thiết:' : 'Required documents:'; ?></span>
                                <span class="value"><?php echo ($language_id == 1) ? 'CCCD + Ảnh cá nhân' : 'ID card + Personal photo'; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label"><?php echo ($language_id == 1) ? 'Phương thức thanh toán:' : 'Payment method:'; ?></span>
                                <span class="value"><?php echo ($language_id == 1) ? 'Trực tiếp tại khách sạn' : 'Directly at the hotel'; ?></span>
                            </div>
                        </div>
                        <div class="requirements">
                            <h4><?php echo ($language_id == 1) ? 'Yêu Cầu Hồ Sơ:' : 'Document Requirements:'; ?></h4>
                            <ul class="requirements-list">
                                <li><?php echo ($language_id == 1) ? 'Căn cước công dân (CCCD) còn hiệu lực' : 'Valid Citizen ID Card'; ?></li>
                                <li><?php echo ($language_id == 1) ? 'Ảnh cá nhân 4x6 (chụp không quá 6 tháng)' : '4x6 personal photo (taken within 6 months)'; ?></li>
                                <li><?php echo ($language_id == 1) ? 'Điền đầy đủ thông tin vào form đăng ký' : 'Complete the registration form'; ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="process-section">
            <div class="container">
                <div class="section-header">
                    <h2><?php echo ($language_id == 1) ? 'Quy Trình Thực Hiện' : 'Implementation Process'; ?></h2>
                    <p><?php echo ($language_id == 1) ? '4 bước đơn giản để hoàn tất thủ tục' : '4 simple steps to complete the procedure'; ?></p>
                </div>
                <div class="process-steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4><?php echo ($language_id == 1) ? 'Chuẩn Bị Hồ Sơ' : 'Prepare Documents'; ?></h4>
                            <p><?php echo ($language_id == 1) ? 'Chuẩn bị CCCD và ảnh cá nhân theo yêu cầu' : 'Prepare ID card and personal photo as required'; ?></p>
                        </div>
                    </div>
                    <span class="step-arrow">→</span>
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4><?php echo ($language_id == 1) ? 'Điền Form' : 'Fill Form'; ?></h4>
                            <p><?php echo ($language_id == 1) ? 'Điền thông tin vào form đăng ký tại khách sạn' : 'Fill in the registration form at the hotel'; ?></p>
                        </div>
                    </div>
                    <span class="step-arrow">→</span>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4><?php echo ($language_id == 1) ? 'Thanh Toán' : 'Payment'; ?></h4>
                            <p>
                                <?php 
                                if ($is_numeric_price) {
                                    echo ($language_id == 1) ? 'Thanh toán trực tiếp phí dịch vụ ' . number_format($service_price) . ' VNĐ' : 'Pay the service fee of ' . number_format($service_price) . ' directly';
                                } else {
                                    echo ($language_id == 1) ? 'Thanh toán trực tiếp phí dịch vụ ' . htmlspecialchars($service_price) : 'Pay the service fee of ' . htmlspecialchars($service_price) . ' directly';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                    <span class="step-arrow">→</span>
                    <div class="step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h4><?php echo ($language_id == 1) ? 'Nhận Giấy' : 'Receive Pass'; ?></h4>
                            <p><?php echo ($language_id == 1) ? 'Nhận giấy thông hành trong ngày' : 'Receive the travel pass within the day'; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-section">
            <div class="container">
                <div class="contact-wrapper">
                    <div class="contact-info">
                        <h3><?php echo ($language_id == 1) ? 'Liên Hệ Đăng Ký Dịch Vụ' : 'Contact for Service Registration'; ?></h3>
                        <p><?php echo ($language_id == 1) ? 'Hãy liên hệ với chúng tôi để được tư vấn chi tiết và đăng ký dịch vụ' : 'Contact us for detailed consultation and service registration'; ?></p>
                        <div class="contact-details">
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt contact-icon"></i>
                                <div>
                                    <strong><?php echo ($language_id == 1) ? 'Địa chỉ:' : 'Address:'; ?></strong>
                                    <p><?php echo ($language_id == 1) ? '120 Đường Soi Tiền, Phường Kim Tân, TP. Lào Cai' : '120 Soi Tien Street, Kim Tan Ward, Lao Cai City'; ?></p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone contact-icon"></i>
                                <div>
                                    <strong><?php echo ($language_id == 1) ? 'Điện thoại:' : 'Phone:'; ?></strong>
                                    <p>0214 366 1666</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-envelope contact-icon"></i>
                                <div>
                                    <strong>Email:</strong>
                                    <p>chamsockhachhang.liberty@gmail.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="contact-form">
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-error"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        
                        <form id="visaForm" method="POST" action="">
                            <div class="form-group">
                                <label for="name"><?php echo ($language_id == 1) ? 'Họ và Tên *' : 'Full Name *'; ?></label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="phone"><?php echo ($language_id == 1) ? 'Số Điện Thoại *' : 'Phone Number *'; ?></label>
                                <input type="tel" id="phone" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label for="people-count"><?php echo ($language_id == 1) ? 'Số Lượng Người Đăng Ký *' : 'Number of Registrants *'; ?></label>
                                <div class="people-count-wrapper">
                                    <button type="button" class="count-btn minus-btn" onclick="updatePeopleCount(-1)">-</button>
                                    <input type="number" id="people-count" name="people_count" value="1" min="1" max="10" required>
                                    <button type="button" class="count-btn plus-btn" onclick="updatePeopleCount(1)">+</button>
                                </div>
                                <div class="total-price">
                                    <span>
                                        <?php echo ($language_id == 1) ? 'Tổng chi phí:' : 'Total cost:'; ?> 
                                        <strong id="total-cost">
                                            <?php 
                                            if ($is_numeric_price) {
                                                echo number_format($service_price); 
                                                echo ($language_id == 1) ? ' VNĐ' : '';
                                            } else {
                                                echo htmlspecialchars($service_price);
                                            }
                                            ?>
                                        </strong>
                                    </span>
                                </div>
                                <input type="hidden" id="total-cost-hidden" name="total_cost" 
                                       value="<?php echo $is_numeric_price ? $service_price : '0'; ?>">
                                <input type="hidden" id="service-price" 
                                       value="<?php echo $is_numeric_price ? $service_price : '0'; ?>">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email">
                            </div>
                            <div class="form-group">
                                <label for="travel-date"><?php echo ($language_id == 1) ? 'Ngày Dự Kiến Đi Du Lịch' : 'Expected Travel Date'; ?></label>
                                <input type="date" id="travel-date" name="travel_date">
                            </div>
                            <div class="form-group">
                                <label for="note"><?php echo ($language_id == 1) ? 'Ghi Chú' : 'Note'; ?></label>
                                <textarea id="note" name="note" rows="4"></textarea>
                            </div>
                            <button type="submit" name="register_service" class="submit-btn"><?php echo ($language_id == 1) ? 'Đăng Ký Dịch Vụ' : 'Register Service'; ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="additional-services">
            <div class="container">
                <div class="section-header">
                    <h2><?php echo ($language_id == 1) ? 'Dịch Vụ Khác Tại Liberty Hotel' : 'Other Services at Liberty Hotel'; ?></h2>
                    <p><?php echo ($language_id == 1) ? 'Khám phá thêm các dịch vụ đẳng cấp khác' : 'Explore more premium services'; ?></p>
                </div>
                <div class="services-grid">
                    <div class="service-item">
                        <i class="fas fa-hotel service-icon"></i>
                        <h4><?php echo ($language_id == 1) ? 'Phòng Nghỉ Cao Cấp' : 'Premium Rooms'; ?></h4>
                        <p><?php echo ($language_id == 1) ? 'Từ 700.000 VNĐ/đêm' : 'From 700,000 VNĐ/night'; ?></p>
                    </div>
                    <div class="service-item">
                        <i class="fas fa-calendar-alt service-icon"></i>
                        <h4><?php echo ($language_id == 1) ? 'Tổ Chức Sự Kiện' : 'Event Organization'; ?></h4>
                        <p><?php echo ($language_id == 1) ? 'Hội nghị, tiệc cưới, gala dinner' : 'Conferences, weddings, gala dinners'; ?></p>
                    </div>
                    <div class="service-item">
                        <i class="fas fa-utensils service-icon"></i>
                        <h4><?php echo ($language_id == 1) ? 'Nhà Hàng & Bar' : 'Restaurant & Bar'; ?></h4>
                        <p><?php echo ($language_id == 1) ? 'Ẩm thực đặc sản Tây Bắc' : 'Northwest specialty cuisine'; ?></p>
                    </div>
                    <div class="service-item">
                        <i class="fas fa-map service-icon"></i>
                        <h4><?php echo ($language_id == 1) ? 'Tư Vấn Du Lịch' : 'Travel Consultation'; ?></h4>
                        <p><?php echo ($language_id == 1) ? 'Miễn phí các cảnh điểm tỉnh Lào Cai' : 'Free consultation for Lào Cai attractions'; ?></p>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include "footer.php"; ?>
    <script src="/libertylaocai/view/js/giaythonghanh.js"></script>
</body>
</html>