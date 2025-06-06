<?php
session_start();
require_once '../../model/config/connect.php';

// Kiểm tra ngôn ngữ từ session, mặc định là 1 (tiếng Việt)
$languageId = isset($_SESSION['language_id']) ? (int)$_SESSION['language_id'] : 1;
$langCode = ($languageId == 1) ? 'vi' : 'en';

// Xử lý AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "libertylaocai";
    
    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $action = $_POST['action'] ?? '';
        $lang = $_POST['lang'] ?? 'vi';
        
        switch ($action) {
            case 'get_dieukhoan':
                $stmt = $pdo->prepare("
                    SELECT dk.id, dkn.title, dkn.small_title, dkn.content 
                    FROM dieukhoan dk 
                    JOIN dieukhoan_ngonngu dkn ON dk.id = dkn.id_dieukhoan 
                    JOIN ngonngu nn ON dkn.id_ngonngu = nn.id 
                    WHERE dk.active = 1 AND nn.code = ? 
                    ORDER BY dk.id
                ");
                $stmt->execute([$lang]);
                $dieukhoan = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'data' => $dieukhoan]);
                break;
                
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    
    exit;
}

// Fetch contact information for the page
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libertylaocai";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("
        SELECT t.phone, t.email, t.facebook, tn.address
        FROM thongtinkhachsan t
        JOIN thongtinkhachsan_ngonngu tn ON t.id = tn.id_thongtinkhachsan
        JOIN ngonngu nn ON tn.id_ngonngu = nn.id
        WHERE nn.id = ?
        LIMIT 1
    ");
    $stmt->execute([$languageId]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Default values if no data is found
    $contact = $contact ?: [
        'address' => ($languageId == 1) ? '120 Đường Soi Tiền, Phường Kim Tân, TP. Lào Cai' : '120 Soi Tien Street, Kim Tan Ward, Lao Cai City',
        'phone' => '0214 366 1666',
        'email' => 'chamsockhachhang.liberty@gmail.com',
        'facebook' => ($languageId == 1) ? 'Liberty Hotel & Events Khách sạn Liberty Lào Cai' : 'Liberty Hotel & Events Lao Cai'
    ];
} catch (PDOException $e) {
    // Fallback to default values in case of error
    $contact = [
        'address' => ($languageId == 1) ? '120 Đường Soi Tiền, Phường Kim Tân, TP. Lào Cai' : '120 Soi Tien Street, Kim Tan Ward, Lao Cai City',
        'phone' => '0214 366 1666',
        'email' => 'chamsockhachhang.liberty@gmail.com',
        'facebook' => ($languageId == 1) ? 'Liberty Hotel & Events Khách sạn Liberty Lào Cai' : 'Liberty Hotel & Events Lao Cai'
    ];
}

// Set page title and contact section title based on language
$pageTitle = ($languageId == 1) ? 'Điều khoản và Chính sách - Liberty Hotel Lào Cai' : 'Terms and Policies - Liberty Hotel Lao Cai';
$contactSectionTitle = ($languageId == 1) ? 'Thông tin liên hệ' : 'Contact Information';
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($langCode); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="/libertylaocai/view/css/dieukhoan.css">
</head>
<body>
    <?php include "header.php"; ?>

    <div class="container">
        <div class="page-header">
            <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
            <p class="subtitle">Khách sạn Liberty Lào Cai</p>
        </div>

        <div class="content-wrapper" id="content-wrapper">
            <!-- Content will be loaded via AJAX -->
            <div class="loading" id="loading">
                <p><?php echo ($languageId == 1) ? 'Đang tải dữ liệu...' : 'Loading data...'; ?></p>
            </div>
        </div>

        <!-- Thông tin liên hệ (dynamic) -->
        <section class="contact-section">
            <h2 class="section-title">
                <i class="icon-contact"></i>
                <?php echo htmlspecialchars($contactSectionTitle); ?>
            </h2>
            <div class="contact-info">
                <div class="contact-item">
                    <strong><?php echo ($languageId == 1) ? 'Địa chỉ:' : 'Address:'; ?></strong> <?php echo htmlspecialchars($contact['address']); ?>
                </div>
                <div class="contact-item">
                    <strong><?php echo ($languageId == 1) ? 'Điện thoại:' : 'Phone:'; ?></strong> <?php echo htmlspecialchars($contact['phone']); ?>
                </div>
                <div class="contact-item">
                    <strong><?php echo ($languageId == 1) ? 'Email:' : 'Email:'; ?></strong> <?php echo htmlspecialchars($contact['email']); ?>
                </div>
                <div class="contact-item">
                    <strong><?php echo ($languageId == 1) ? 'Facebook:' : 'Facebook:'; ?></strong> <?php echo htmlspecialchars($contact['facebook']); ?>
                </div>
            </div>
        </section>
    </div>

    <script>
        // Pass language_id to JavaScript
        window.currentLang = <?php echo json_encode($languageId); ?>;
    </script>
    <script src="/libertylaocai/view/js/dieukhoan.js"></script>
    <?php include "footer.php"; ?>
</body>
</html>