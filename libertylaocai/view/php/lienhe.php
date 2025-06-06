<?php
session_start();
require_once '../../model/config/connect.php';

// Lấy language_id từ session, mặc định là 1 (tiếng Việt)
$languageId = isset($_SESSION['language_id']) ? (int)$_SESSION['language_id'] : 1;

// Xử lý form khi được submit
$response = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_contact') {
    try {
        // Lấy và làm sạch dữ liệu từ form
        $fullName = trim($_POST['fullName'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        // Validate dữ liệu
        $errors = [];
        
        if (empty($fullName)) {
            $errors[] = $languageId == 1 ? 'Vui lòng nhập họ tên' : 'Please enter full name';
        }
        
        if (empty($email)) {
            $errors[] = $languageId == 1 ? 'Vui lòng nhập email' : 'Please enter email';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = $languageId == 1 ? 'Vui lòng nhập email hợp lệ' : 'Please enter valid email';
        }
        
        if (empty($phone)) {
            $errors[] = $languageId == 1 ? 'Vui lòng nhập số điện thoại' : 'Please enter phone number';
        } elseif (!preg_match('/^[0-9]{10,11}$/', $phone)) {
            $errors[] = $languageId == 1 ? 'Vui lòng nhập số điện thoại hợp lệ (10-11 số)' : 'Please enter valid phone number (10-11 digits)';
        }
        
        if (!empty($errors)) {
            $response = [
                'success' => false,
                'message' => implode(', ', $errors)
            ];
        } else {
            // Bắt đầu transaction
            mysqli_autocommit($conn, false);
            
            try {
                // Kiểm tra xem khách hàng đã tồn tại chưa (theo email)
                $check_customer_sql = "SELECT id FROM khachhang WHERE email = ?";
                $check_stmt = mysqli_prepare($conn, $check_customer_sql);
                mysqli_stmt_bind_param($check_stmt, "s", $email);
                mysqli_stmt_execute($check_stmt);
                $check_result = mysqli_stmt_get_result($check_stmt);
                
                if ($existing_customer = mysqli_fetch_assoc($check_result)) {
                    // Khách hàng đã tồn tại, cập nhật thông tin
                    $customer_id = $existing_customer['id'];
                    $update_customer_sql = "UPDATE khachhang SET name = ?, phone = ? WHERE id = ?";
                    $update_stmt = mysqli_prepare($conn, $update_customer_sql);
                    mysqli_stmt_bind_param($update_stmt, "ssi", $fullName, $phone, $customer_id);
                    mysqli_stmt_execute($update_stmt);
                } else {
                    // Tạo mới khách hàng
                    $insert_customer_sql = "INSERT INTO khachhang (name, phone, email) VALUES (?, ?, ?)";
                    $insert_stmt = mysqli_prepare($conn, $insert_customer_sql);
                    mysqli_stmt_bind_param($insert_stmt, "sss", $fullName, $phone, $email);
                    mysqli_stmt_execute($insert_stmt);
                    $customer_id = mysqli_insert_id($conn);
                }
                
                // Tạo service từ subject hoặc sử dụng message làm service
                $service = !empty($subject) ? $subject : ($languageId == 1 ? 'Liên hệ tổng quát' : 'General Contact');
                
                // Thêm contact request
                $insert_contact_sql = "INSERT INTO contact_requests (service, message, id_khachhang) VALUES (?, ?, ?)";
                $contact_stmt = mysqli_prepare($conn, $insert_contact_sql);
                mysqli_stmt_bind_param($contact_stmt, "ssi", $service, $message, $customer_id);
                mysqli_stmt_execute($contact_stmt);
                
                // Commit transaction
                mysqli_commit($conn);
                
                $response = [
                    'success' => true,
                    'message' => $languageId == 1 ? 
                        'Cảm ơn bạn đã liên hệ với chúng tôi. Chúng tôi sẽ phản hồi trong thời gian sớm nhất.' : 
                        'Thank you for contacting us. We will respond as soon as possible.'
                ];
                
            } catch (Exception $e) {
                // Rollback nếu có lỗi
                mysqli_rollback($conn);
                throw $e;
            }
        }
        
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => $languageId == 1 ? 'Có lỗi xảy ra, vui lòng thử lại sau.' : 'An error occurred, please try again later.'
        ];
        error_log("Contact form error: " . $e->getMessage());
    }
    
    // Trả về JSON response cho AJAX
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Hàm để lấy dữ liệu an toàn
function getContentByArea($conn, $area, $language_id) {
    $sql = "SELECT mn.title, mn.content 
            FROM chon_mo_ta cmt 
            JOIN mota_ngonngu mn ON cmt.id_mota_ngonngu = mn.id 
            WHERE cmt.area = ? AND cmt.language_id = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $area, $language_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    return mysqli_fetch_assoc($result);
}

// Lấy thông tin khách sạn
$sql_hotel = "SELECT tk.*, tkn.address, tkn.description 
              FROM thongtinkhachsan tk 
              JOIN thongtinkhachsan_ngonngu tkn ON tk.id = tkn.id_thongtinkhachsan 
              WHERE tkn.id_ngonngu = ? LIMIT 1";
$stmt_hotel = mysqli_prepare($conn, $sql_hotel);
mysqli_stmt_bind_param($stmt_hotel, "i", $languageId);
mysqli_stmt_execute($stmt_hotel);
$result_hotel = mysqli_stmt_get_result($stmt_hotel);
$hotel_info = mysqli_fetch_assoc($result_hotel);

// Lấy thông tin nội dung
$intro_info = getContentByArea($conn, 'contact-intro', $languageId);
$form_info = getContentByArea($conn, 'contact-form-title', $languageId);

// Xử lý trường hợp không có dữ liệu
$hotel_name = $hotel_info['name'] ?? 'Liberty Lào Cai Hotel';
$hotel_short_name = $hotel_info['short_name'] ?? 'Liberty';
$hotel_address = $hotel_info['address'] ?? '120 Đường Soi Tiền, Phường Kim Tân, TP. Lào Cai';
$hotel_phone = $hotel_info['phone'] ?? '0214 366 1666';
$hotel_email = $hotel_info['email'] ?? 'chamsockhachhang.liberty@gmail.com';
$hotel_facebook = $hotel_info['facebook'] ?? 'Liberty Hotel & Events Khách sạn Liberty Lào Cai';
$hotel_description = $hotel_info['description'] ?? 'Khách sạn Liberty Lào Cai - Điểm đến lý tưởng cho du khách';

$intro_title = $intro_info['title'] ?? ($languageId == 1 ? 'KHÁCH SẠN LIBERTY LÀO CAI' : 'LIBERTY LAO CAI HOTEL');
$intro_content = $intro_info['content'] ?? $hotel_description;
$form_title = $form_info['title'] ?? ($languageId == 1 ? 'GỬI THÔNG TIN LIÊN HỆ' : 'SEND CONTACT INFORMATION');
$form_content = $form_info['content'] ?? ($languageId == 1 ? 'Vui lòng nhập đầy đủ thông tin bên dưới ' . $hotel_short_name . ' sẽ liên hệ ngay khi nhận được yêu cầu!' : 'Please fill in the information below, ' . $hotel_short_name . ' will contact you as soon as possible!');
?>

<!DOCTYPE html>
<html lang="<?php echo $languageId == 1 ? 'vi' : 'en'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($languageId == 1 ? 'Liên Hệ' : 'Contact'); ?> - <?php echo htmlspecialchars($hotel_short_name); ?></title>
    <link rel="stylesheet" href="/libertylaocai/view/css/lienhe.css">
</head>

<body>
    <?php include "header.php"; ?>
    <div class="contact-container">
        <div class="contact-header">
            <h1><?php echo $languageId == 1 ? 'Liên Hệ' : 'Contact'; ?></h1>
            <div class="header-line"></div>
        </div>

        <div class="contact-content">
            <!-- Thông tin liên hệ bên trái -->
            <div class="contact-info1">
                <div class="info-section">
                    <h2><?php echo htmlspecialchars($intro_title); ?></h2>
                    <p class="description">
                        <?php echo htmlspecialchars($intro_content); ?>
                    </p>
                </div>

                <div class="contact-details">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="contact-text">
                            <span><?php echo htmlspecialchars($hotel_address); ?></span>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="contact-text">
                            <span><?php echo $languageId == 1 ? 'Hotline' : 'Hotline'; ?>: <?php echo htmlspecialchars($hotel_phone); ?></span>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="contact-text">
                            <span><?php echo $languageId == 1 ? 'Email' : 'Email'; ?>: <?php echo htmlspecialchars($hotel_email); ?></span>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="contact-text">
                            <span>Facebook: <?php echo htmlspecialchars($hotel_facebook); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form liên hệ bên phải -->
            <div class="contact-form-section">
                <h3><?php echo htmlspecialchars($form_title); ?></h3>
                <p class="form-description">
                    <?php echo htmlspecialchars($form_content); ?>
                </p>

                <form class="contact-form" id="contactForm">
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" id="fullName" name="fullName" placeholder="<?php echo $languageId == 1 ? 'Họ & Tên *' : 'Full Name *'; ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="<?php echo $languageId == 1 ? 'Địa chỉ Email *' : 'Email Address *'; ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" id="phone" name="phone" placeholder="<?php echo $languageId == 1 ? 'Số điện thoại' : 'Phone Number'; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="text" id="subject" name="subject" placeholder="<?php echo $languageId == 1 ? 'Tiêu đề' : 'Subject'; ?>">
                    </div>

                    <div class="form-group">
                        <textarea id="message" name="message" rows="6" placeholder="<?php echo $languageId == 1 ? 'Nội dung liên hệ' : 'Contact Message'; ?>"></textarea>
                    </div>

                    <button type="submit" class="submit-btn"><?php echo $languageId == 1 ? 'GỬI THÔNG TIN' : 'SEND MESSAGE'; ?></button>
                </form>
            </div>
        </div>
    </div>
    <?php include "footer.php"; ?>

    <script src="/libertylaocai/view/js/lienhe.js"></script>
</body>

</html>

<?php
// Đóng kết nối database
mysqli_close($conn);
?>