<?php
require_once '../../model/config/connect.php';
session_start();

// Default language: Vietnamese (id = 1)
$current_language = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;
$language_id = ($current_language == 2) ? 2 : 1;

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  header('Content-Type: application/json');
  
  // Read JSON input
  $input = json_decode(file_get_contents('php://input'), true);
  
  if (!$input) {
    http_response_code(400);
    echo json_encode([
      'success' => false,
      'message' => $language_id == 1 ? 'Dữ liệu không hợp lệ!' : 'Invalid data!'
    ]);
    exit;
  }

  // Extract and sanitize input
  $name = trim($input['name'] ?? '');
  $phone = trim($input['phone'] ?? '');
  $email = trim($input['email'] ?? '');
  $service = trim($input['service'] ?? '');
  $message = trim($input['message'] ?? '');

  // Validate input
  if (empty($name) || empty($phone) || empty($email) || empty($service) || empty($message)) {
    http_response_code(400);
    echo json_encode([
      'success' => false,
      'message' => $language_id == 1 ? 'Vui lòng điền đầy đủ thông tin!' : 'Please fill in all required fields!'
    ]);
    exit;
  }

  $phone_clean = preg_replace('/\s+/', '', $phone);
  if (!preg_match('/^0[0-9]{9,10}$/', $phone_clean)) {
    http_response_code(400);
    echo json_encode([
      'success' => false,
      'message' => $language_id == 1 ? 'Số điện thoại không hợp lệ (phải bắt đầu bằng 0, 10-11 số)!' : 'Invalid phone number (must start with 0, 10-11 digits)!'
    ]);
    exit;
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
      'success' => false,
      'message' => $language_id == 1 ? 'Email không hợp lệ!' : 'Invalid email address!'
    ]);
    exit;
  }

  if (strlen($name) > 255 || strlen($email) > 255 || strlen($phone) > 20 || strlen($service) > 255) {
    http_response_code(400);
    echo json_encode([
      'success' => false,
      'message' => $language_id == 1 ? 'Dữ liệu nhập vào quá dài!' : 'Input data is too long!'
    ]);
    exit;
  }

  // Database transaction
  mysqli_begin_transaction($conn);
  try {
    // Check if customer exists
    $stmt = $conn->prepare("SELECT id FROM khachhang WHERE phone = ? OR email = ?");
    if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
    $stmt->bind_param("ss", $phone_clean, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $id_khachhang = $result->fetch_assoc()['id'];
    } else {
      // Insert new customer
      $stmt = $conn->prepare("INSERT INTO khachhang (name, phone, email) VALUES (?, ?, ?)");
      if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
      $stmt->bind_param("sss", $name, $phone_clean, $email);
      if (!$stmt->execute()) throw new Exception("Insert customer failed: " . $stmt->error);
      $id_khachhang = $conn->insert_id;
    }

    // Insert contact request
    $stmt = $conn->prepare("INSERT INTO contact_requests (id_khachhang, service, message) VALUES (?, ?, ?)");
    if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
    $stmt->bind_param("iss", $id_khachhang, $service, $message);
    if (!$stmt->execute()) throw new Exception("Insert contact request failed: " . $stmt->error);

    // Commit transaction
    mysqli_commit($conn);
    echo json_encode([
      'success' => true,
      'message' => $language_id == 1 ? 'Yêu cầu của bạn đã được gửi thành công!' : 'Your request has been sent successfully!'
    ]);
  } catch (Exception $e) {
    mysqli_rollback($conn);
    error_log("Form submission error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
      'success' => false,
      'message' => $language_id == 1 ? 'Có lỗi xảy ra, vui lòng thử lại!' : 'An error occurred, please try again!'
    ]);
  }
  $stmt->close();
  exit;
}

// Existing database queries for page content
$features_query = "
  SELECT t.id as id_tienich, t.icon, tn.title, tn.content, td.page 
  FROM tienich t 
  LEFT JOIN tienich_ngonngu tn ON t.id = tn.id_tienich 
  LEFT JOIN tienichdichvu td ON t.id = td.id_tienich 
  WHERE tn.id_ngonngu = ? AND td.page = 'dichvu'
  ORDER BY t.id";
$features_stmt = $conn->prepare($features_query);
$features_stmt->bind_param("i", $language_id);
$features_stmt->execute();
$features_result = $features_stmt->get_result();
$features = [];
while ($row = $features_result->fetch_assoc()) {
  $features[] = $row;
}
$features_stmt->close();

$greeting_query = "
  SELECT nn.content 
  FROM loichaoduocchon l 
  JOIN nhungcauchaohoi_ngonngu nn ON l.id_nhungcauchaohoi_ngonngu = nn.id 
  WHERE l.id_ngonngu = ? AND l.page = 'dichvu'
  LIMIT 1";
$greeting_stmt = $conn->prepare($greeting_query);
$greeting_stmt->bind_param("i", $language_id);
$greeting_stmt->execute();
$greeting_result = $greeting_stmt->get_result();
$greeting = $greeting_result->num_rows > 0 ? $greeting_result->fetch_assoc()['content'] : 
  ($language_id == 1 ? 'Đồng hành cùng bạn khám phá vẻ đẹp Tây Bắc' : 'Accompanying you to explore the beauty of the Northwest');
$greeting_stmt->close();

$services_query = "
  SELECT dn.id_dichvu, dn.title, dn.content, a.image 
  FROM dichvu d
  LEFT JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu 
  LEFT JOIN anhdichvu a ON d.id = a.id_dichvu AND a.is_primary = 1 
  WHERE dn.id_ngonngu = ? AND d.type = 'dichvu'
  ORDER BY dn.id_dichvu";
$services_stmt = $conn->prepare($services_query);
$services_stmt->bind_param("i", $language_id);
$services_stmt->execute();
$services_result = $services_stmt->get_result();
$services = [];
while ($row = $services_result->fetch_assoc()) {
  $services[] = $row;
}
$services_stmt->close();

$tours_query = "
  SELECT dn.id_dichvu, dn.title, dn.content, a.image 
  FROM dichvu d
  LEFT JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu 
  LEFT JOIN anhdichvu a ON d.id = a.id_dichvu AND a.is_primary = 1 
  WHERE dn.id_ngonngu = ? AND d.type = 'tour'
  ORDER BY dn.id_dichvu";
$tours_stmt = $conn->prepare($tours_query);
$tours_stmt->bind_param("i", $language_id);
$tours_stmt->execute();
$tours_result = $tours_stmt->get_result();
$tours = [];
while ($row = $tours_result->fetch_assoc()) {
  $tours[] = $row;
}
$tours_stmt->close();

$banner_query = "SELECT image FROM head_banner WHERE page = 'dichvu' LIMIT 1";
$banner_result = mysqli_query($conn, $banner_query);
if (!$banner_result) {
  error_log("Banner query failed: " . mysqli_error($conn));
  $banner = false;
} else {
  $banner = mysqli_fetch_assoc($banner_result);
}
$banner_image = $banner ? '/libertylaocai/view/img/' . htmlspecialchars($banner['image']) : '/libertylaocai/view/img/background.png';

// Hotel information
$thongtinkhachsan_query = "SELECT phone, email FROM thongtinkhachsan WHERE id = 1";
$thongtinkhachsan_result = mysqli_query($conn, $thongtinkhachsan_query);
$thongtinkhachsan = mysqli_fetch_assoc($thongtinkhachsan_result);

$thongtinkhachsan_ngonngu_query = "SELECT address FROM thongtinkhachsan_ngonngu WHERE id_thongtinkhachsan = 1 AND id_ngonngu = ?";
$thongtinkhachsan_ngonngu_stmt = $conn->prepare($thongtinkhachsan_ngonngu_query);
$thongtinkhachsan_ngonngu_stmt->bind_param("i", $language_id);
$thongtinkhachsan_ngonngu_stmt->execute();
$thongtinkhachsan_ngonngu_result = $thongtinkhachsan_ngonngu_stmt->get_result();
$thongtinkhachsan_ngonngu = $thongtinkhachsan_ngonngu_result->fetch_assoc();
$thongtinkhachsan_ngonngu_stmt->close();
?>

<!DOCTYPE html>
<html lang="<?php echo ($language_id == 1) ? 'vi' : 'en'; ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $language_id == 1 ? 'Dịch Vụ Du Lịch Liberty Lào Cai' : 'Liberty Lào Cai Travel Services'; ?></title>
  <link rel="stylesheet" href="/libertylaocai/view/css/dichvu.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
  <?php include "header.php"; ?>

  <section class="hero">
    <img src="<?php echo htmlspecialchars($banner_image); ?>" alt="Background" class="hero-background">
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <h1 class="hero-title"><?php echo $language_id == 1 ? 'Dịch Vụ Du Lịch Liberty Lào Cai' : 'Liberty Lào Cai Travel Services'; ?></h1>
      <p class="hero-subtitle"><?php echo htmlspecialchars($greeting); ?></p>
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
              <p><?php echo htmlspecialchars($feature['content']); ?></p>
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
          <img src="<?php echo $service['image'] ? '/libertylaocai/view/img/' . htmlspecialchars($service['image']) : '/libertylaocai/view/img/default-service-image.png'; ?>" alt="<?php echo htmlspecialchars($service['title']); ?>">
        </div>
        <div class="text-content" data-aos="fade-<?php echo $index % 2 == 0 ? 'left' : 'right'; ?>">
          <h2 class="service-link" data-href="/libertylaocai/view/php/chitiettour.php?id_dichvu=<?php echo htmlspecialchars($service['id_dichvu']); ?>"><?php echo htmlspecialchars($service['title']); ?></h2>
          <p class="service-link" data-href="/libertylaocai/view/php/chitiettour.php?id_dichvu=<?php echo htmlspecialchars($service['id_dichvu']); ?>"><?php echo htmlspecialchars($service['content']); ?></p>
        </div>
      </div>
    </div>
  </section>
<?php endforeach; ?>

  <section class="tours-section">
    <div class="container">
      <h2 class="section-title"><?php echo $language_id == 1 ? 'Các Tour Du Lịch Hấp Dẫn' : 'Exciting Travel Tours'; ?></h2>
      <div class="tours-grid">
        <?php foreach ($tours as $index => $tour): ?>
          <div class="tour-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
            <a href="/libertylaocai/view/php/chitiettour.php?id_dichvu=<?php echo htmlspecialchars($tour['id_dichvu']); ?>" style="text-decoration: none;">
              <div class="tour-image">
                <img src="<?php echo $tour['image'] ? '/libertylaocai/view/img/' . htmlspecialchars($tour['image']) : '/libertylaocai/view/img/default-tour-image.png'; ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>">
                <div class="tour-overlay">
                  <span class="tour-price"><?php echo $language_id == 1 ? 'Liên hệ' : 'Contact'; ?></span>
                </div>
              </div>
              <div class="tour-content">
                <h3><?php echo htmlspecialchars($tour['title']); ?></h3>
                <p><?php echo htmlspecialchars($tour['content']); ?></p>
                <div class="tour-highlights">
                  <?php
                  $highlight_tags = [];
                  foreach ($highlight_tags as $tag): ?>
                    <span class="highlight-tag"><?php echo htmlspecialchars($tag); ?></span>
                  <?php endforeach; ?>
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="contact-section">
    <div class="container">
        <div class="contact-wrapper">
            <div class="contact-info" data-aos="fade-right">
                <h2><?php echo $language_id == 1 ? 'Liên Hệ Đặt Tour' : 'Contact to Book a Tour'; ?></h2>
                <p><?php echo $language_id == 1 ? 'Hãy liên hệ với chúng tôi để được tư vấn và đặt tour một cách nhanh chóng nhất!' : 'Contact us for consultation and to book your tour as quickly as possible!'; ?></p>
                <div class="contact-methods">
                    <div class="contact-method">
                        <i class="fas fa-phone"></i>
                        <span>Hotline: <?php echo htmlspecialchars($thongtinkhachsan['phone']); ?></span>
                    </div>
                    <div class="contact-method">
                        <i class="fas fa-envelope"></i>
                        <span>Email: <?php echo htmlspecialchars($thongtinkhachsan['email']); ?></span>
                    </div>
                    <div class="contact-method">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo $language_id == 1 ? 'Địa chỉ: ' : 'Address: '; ?><?php echo htmlspecialchars($thongtinkhachsan_ngonngu['address']); ?></span>
                    </div>
                </div>
            </div>
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
                        <?php foreach ($tours as $tour): ?>
                            <option value="<?php echo htmlspecialchars($tour['title']); ?>"><?php echo htmlspecialchars($tour['title']); ?></option>
                        <?php endforeach; ?>
                        <option value="<?php echo $language_id == 1 ? 'Đưa đón sân bay' : 'Airport Transfer'; ?>">
                            <?php echo $language_id == 1 ? 'Đưa đón sân bay' : 'Airport Transfer'; ?>
                        </option>
                        <option value="<?php echo $language_id == 1 ? 'Làm giấy thông hành' : 'Travel Pass Service'; ?>">
                            <?php echo $language_id == 1 ? 'Làm giấy thông hành' : 'Travel Pass Service'; ?>
                        </option>
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

  <?php include "footer.php"; ?>

  <script src="/libertylaocai/view/js/dichvu.js"></script>
</body>
</html>