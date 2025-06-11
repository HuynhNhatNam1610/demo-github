<?php
// Include file kết nối database và session
require_once '../../model/config/connect.php';
require_once 'session.php';

// Lấy ngôn ngữ hiện tại từ session (mặc định là tiếng Việt - id = 1)
$languageId = isset($_SESSION['language_id']) ? (int)$_SESSION['language_id'] : 1;

// Lấy thông tin khách sạn
$sql_hotel = "SELECT ks.*, ksnn.address, ksnn.description 
              FROM thongtinkhachsan ks 
              LEFT JOIN thongtinkhachsan_ngonngu ksnn ON ks.id = ksnn.id_thongtinkhachsan 
              WHERE ksnn.id_ngonngu = ? 
              LIMIT 1";
$stmt_hotel = $conn->prepare($sql_hotel);
$stmt_hotel->bind_param("i", $languageId);
$stmt_hotel->execute();
$hotel_info = $stmt_hotel->get_result()->fetch_assoc();

// Lấy các điều khoản hoạt động
$sql_terms = "SELECT dk.id, dknn.title, dknn.content 
              FROM dieukhoan dk 
              LEFT JOIN dieukhoan_ngonngu dknn ON dk.id = dknn.id_dieukhoan 
              WHERE dk.active = 1 AND dknn.id_ngonngu = ?
              ORDER BY dk.id";
$stmt_terms = $conn->prepare($sql_terms);
$stmt_terms->bind_param("i", $languageId);
$stmt_terms->execute();
$terms_result = $stmt_terms->get_result();
?>

<!DOCTYPE html>
<html lang="<?php echo $languageId == 1 ? 'vi' : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Điều khoản và Chính sách - <?php echo htmlspecialchars($hotel_info['name'] ?? 'Liberty Hotel Lào Cai'); ?></title>
    <link rel="stylesheet" href="/libertylaocai/view/css/dieukhoan.css">
</head>
<body>
    <?php include "header.php"; ?>

    <div class="container">
        <div class="page-header">
            <h1><?php echo $languageId == 1 ? 'Điều khoản và Chính sách' : 'Terms and Policies'; ?></h1>
            <p class="subtitle"><?php echo htmlspecialchars($hotel_info['name'] ?? 'Khách sạn Liberty Lào Cai'); ?></p>
        </div>

        <div class="content-wrapper">
            <!-- Các điều khoản và chính sách -->
            <?php if ($terms_result->num_rows > 0): ?>
                <?php while ($term = $terms_result->fetch_assoc()): ?>
                    <section class="policy-section">
                        <h2 class="section-title">
                            <i class="icon-bed"></i>
                            <?php echo htmlspecialchars($term['title']); ?>
                        </h2>
                        <div class="policy-content">
                            <div class="policy-item">
                                <?php echo nl2br(htmlspecialchars($term['content'])); ?>
                            </div>
                        </div>
                    </section>
                <?php endwhile; ?>
            <?php else: ?>
                <section class="policy-section">
                    <h2 class="section-title">
                        <i class="icon-bed"></i>
                        <?php echo $languageId == 1 ? 'Chưa có điều khoản' : 'No Policy'; ?>
                    </h2>
                    <div class="policy-content">
                        <div class="policy-item">
                            <p><?php echo $languageId == 1 ? 'Hiện tại chưa có điều khoản nào được cập nhật.' : 'No terms have been updated yet.'; ?></p>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Thông tin liên hệ -->
            <section class="contact-section">
                <h2 class="section-title">
                    <i class="icon-contact"></i>
                    <?php echo $languageId == 1 ? 'Thông tin liên hệ' : 'Contact Information'; ?>
                </h2>
                <div class="contact-info">
                    <?php if ($hotel_info): ?>
                        <div class="contact-item">
                            <strong><?php echo $languageId == 1 ? 'Địa chỉ:' : 'Address:'; ?></strong> 
                            <?php echo htmlspecialchars($hotel_info['address']); ?>
                        </div>
                        <div class="contact-item">
                            <strong><?php echo $languageId == 1 ? 'Điện thoại:' : 'Phone:'; ?></strong> 
                            <?php echo htmlspecialchars($hotel_info['phone']); ?>
                        </div>
                        <div class="contact-item">
                            <strong>Email:</strong> 
                            <a href="mailto:<?php echo htmlspecialchars($hotel_info['email']); ?>">
                                <?php echo htmlspecialchars($hotel_info['email']); ?>
                            </a>
                        </div>
                        <div class="contact-item">
                            <strong>Facebook:</strong> 
                            <a href="<?php echo htmlspecialchars($hotel_info['link_facebook']); ?>" target="_blank">
                                <?php echo htmlspecialchars($hotel_info['facebook']); ?>
                            </a>
                        </div>
                        <?php if (!empty($hotel_info['website'])): ?>
                            <div class="contact-item">
                                <strong>Website:</strong> 
                                <a href="<?php echo htmlspecialchars($hotel_info['link_website']); ?>" target="_blank">
                                    <?php echo htmlspecialchars($hotel_info['website']); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="contact-item">
                            <p><?php echo $languageId == 1 ? 'Thông tin liên hệ đang được cập nhật.' : 'Contact information is being updated.'; ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <script src="/libertylaocai/view/js/dieukhoan.js"></script>
    <?php include "footer.php"; ?>
</body>
</html>

<?php
// Đóng kết nối
$stmt_hotel->close();
$stmt_terms->close();
$conn->close();
?>