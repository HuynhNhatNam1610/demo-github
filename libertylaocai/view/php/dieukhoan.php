<?php
// Include file kết nối database và session
require_once "session.php";
require_once "../../model/UserModel.php";
// Lấy ngôn ngữ hiện tại từ session (mặc định là tiếng Việt - id = 1)
$languageId = isset($_SESSION['language_id']) ? (int)$_SESSION['language_id'] : 1;

$hotel_info =  getHotelInfoWithLanguage($languageId);;
$terms_result = getActiveTerms($languageId);
?>

<!DOCTYPE html>
<html lang="<?php echo $languageId == 1 ? 'vi' : 'en'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $languageId == 1 ? 'Điều Khoản & Chính Sách - Khách Sạn Liberty Lào Cai' : 'Terms & Policies - Liberty Hotel Lao Cai'; ?></title>
    <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
    <meta name="description" content="<?php echo $languageId == 1 ? 'Tìm hiểu các điều khoản và chính sách lưu trú tại khách sạn Liberty Lào Cai, đảm bảo trải nghiệm tuyệt vời.' : 'Learn about the terms and policies for staying at Liberty Hotel Lao Cai, ensuring a wonderful experience.'; ?>">
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
                                <?php echo $term['content']; ?>
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
                        <?php foreach ($hotel_info as $info): ?>
                            <div class="contact-item">
                                <strong><?php echo $languageId == 1 ? 'Địa chỉ:' : 'Address:'; ?></strong>
                                <?php echo htmlspecialchars($info['address']); ?>
                            </div>
                            <div class="contact-item">
                                <strong><?php echo $languageId == 1 ? 'Điện thoại:' : 'Phone:'; ?></strong>
                                <?php echo htmlspecialchars($info['phone']); ?>
                            </div>
                            <div class="contact-item">
                                <strong>Email:</strong>
                                <a href="mailto:<?php echo htmlspecialchars($info['email']); ?>">
                                    <?php echo htmlspecialchars($info['email']); ?>
                                </a>
                            </div>
                            <div class="contact-item">
                                <strong>Facebook:</strong>
                                <a href="<?php echo htmlspecialchars($info['link_facebook']); ?>" target="_blank">
                                    <?php echo htmlspecialchars($info['facebook']); ?>
                                </a>
                            </div>
                            <?php if (!empty($info['website'])): ?>
                                <div class="contact-item">
                                    <strong>Website:</strong>
                                    <a href="<?php echo htmlspecialchars($info['link_website']); ?>" target="_blank">
                                        <?php echo htmlspecialchars($info['website']); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
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