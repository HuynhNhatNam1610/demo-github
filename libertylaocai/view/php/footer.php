<?php
require_once "session.php";
require_once "../../model/UserModel.php";
// Kiểm tra ngôn ngữ từ session, mặc định là 1 (tiếng Việt)
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;

// Lấy thông tin khách sạn và danh mục header theo ngôn ngữ
$informationHotel = getHotelInfoWithLanguage($languageId);

// Lấy dữ liệu footer
$footerData = getFooterData($languageId);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer Thể Thao</title>
    <meta name="description" content="NHL Sports - Cửa hàng thời trang thể thao hàng đầu.">
    <meta name="keywords" content="footer, thể thao, NHL Sports, thời trang thể thao">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/libertylaocai/view/css/footer.css">
</head>

<body>
    <footer class="footer">
        <?php if (!empty($informationHotel)): ?>
            <div class="footer-container">
                <?php foreach ($informationHotel as $info): ?>
                    <div class="footer-column">
                        <h4><?php echo $languageId == 1 ? 'THÔNG TIN LIÊN HỆ' : 'CONTACT INFORMATION'; ?></h4>
                        <p><strong><i class="bi bi-geo-alt-fill"></i></strong><a href="<?php echo htmlspecialchars($info['position']); ?>"> <?php echo htmlspecialchars($info['address']); ?></a></p>
                        <p><strong> <i class="bi bi-telephone-fill"></i></strong><a href="tel:<?php echo htmlspecialchars($info['phone']); ?>"> <?php echo htmlspecialchars($info['phone']); ?></a></p>
                        <p><strong><i class="bi bi-envelope"></i></strong> <?php echo htmlspecialchars($info['email']); ?></p>
                        <p><strong><i class="bi bi-globe"></i></strong><a href="<?php echo htmlspecialchars($info['link_website']); ?>"> <?php echo htmlspecialchars($info['website']); ?></a></p>
                        <p><strong><i class="bi bi-facebook"></i></strong><a href="<?php echo htmlspecialchars($info['link_facebook']); ?>"> <?php echo htmlspecialchars($info['facebook']); ?></a></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <!-- Các cột danh mục và tiểu mục từ cơ sở dữ liệu -->
            <?php foreach ($footerData as $danhmuc): ?>
                <div class="footer-column">
                    <h4 class="list"><?php echo htmlspecialchars($danhmuc['danhmuc_name']); ?>
                        <i class="bi bi-plus toggle-btn"></i>
                    </h4>
                    <ul>
                        <?php foreach ($danhmuc['tieumuc'] as $index => $tieumuc): ?>
                            <form action="/libertylaocai/user/submit" method="POST" style="display: inline;" class="tieumuc-form">
                                <input type="hidden" name="footer_category_code" value="<?php echo htmlspecialchars($tieumuc['code']); ?>">
                                <li class="tieumuc" style="cursor: pointer;"><?php echo htmlspecialchars($tieumuc['name']); ?></li>
                            </form>
                        <?php endforeach; ?>
                    </ul>

                </div>
            <?php endforeach; ?>
            </div>
            <?php if (!empty($informationHotel)): ?>
                <?php foreach ($informationHotel as $info): ?>
                    <div class="end-footer">
                        <div class="footer-left">
                            <div class="logo1">
                                <img src="/libertylaocai/view/img/<?php echo htmlspecialchars($info['logo']); ?>" alt="Liberty Lào Cai">
                                <span class="logo-text1"><?php echo htmlspecialchars($info['name']); ?></span>
                            </div>
                            <div class="text">
                                <p><?php echo htmlspecialchars($info['description']); ?></p>
                            </div>
                        </div>

                        <div class="footer-right">
                            <iframe src="<?php echo $info['iframe']; ?>" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="copyright">
                <p><?php echo $languageId == 1 ? ' © Bản quyền 2025 Liberty Hotel. Tất cả quyền được bảo lưu.' : ' © Copyright 2025 Liberty Hotel. All rights reserved.'; ?></p>
            </div>

    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/libertylaocai/view/js/footer.js"></script>
</body>

</html>