<?php
require_once "session.php";
require_once "../../model/UserModel.php";
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;
if (!empty($_SESSION['head_banner'])) {
    $getSelectedBanner = $_SESSION['head_banner'];
}

// Lấy ưu đãi
$promotions = getPromotions($languageId);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $languageId == 1 ? 'Khuyến Mãi - Khách Sạn Liberty Lào Cai' : 'Promotions - Liberty Hotel Lao Cai'; ?></title>
    <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
    <meta name="description" content="<?php echo $languageId == 1 ? 'Khám phá các chương trình khuyến mãi đặc biệt tại khách sạn Liberty Lào Cai với ưu đãi hấp dẫn.' : 'Discover special promotion programs at Liberty Hotel Lao Cai with attractive offers.'; ?>">
    <link rel="stylesheet" href="/libertylaocai/view/css/sale.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>


<body>
    <?php include "header.php" ?>
    <div class="sale-container">
        <div class="sale-banner">
            <img src="/libertylaocai/view/img/<?= $getSelectedBanner['image']; ?>" alt="Banner Image" class="banner-image">
            <h1><?php echo $languageId == 1 ? 'KHUYẾN MÃI' : 'PROMOTION'; ?></h1>
        </div>
        <div class="promotions-section">
            <div class="section-title">
                <h2><?php echo $languageId == 1 ? 'Ưu Đãi Đặc Biệt' : 'Special Offers'; ?></h2>
            </div>
            <div class="promotions-grid">
                <?php
                // Hiển thị các ưu đãi
                foreach ($promotions as $promotion) {
                    echo '<div class="promotion-card">';
                    echo '<div class="promotion-image">';
                    echo '<img src="' . htmlspecialchars($promotion['image']) . '" alt="' . htmlspecialchars($promotion['title']) . '">';
                    echo '<span class="corner-text">'.date('Y-m-d', strtotime($promotion['date'])).'</span>';
                    echo '</div>';
                    echo '<div class="promotion-content">';
                    echo '<h3 class="promotion-title">' . htmlspecialchars($promotion['title']) . '</h3>';
                    echo '<p class="promotion-description">' . truncateContent($promotion['content'], 100) . '</p>';
                ?>
                    <form action="/libertylaocai/user/submit" method="POST" style="display: inline; ">
                        <input type="hidden" name="id_uudai" value="<?php echo htmlspecialchars($promotion['id']); ?>">
                        <?php echo '<button type="submit" class="promotion-button">CHI TIẾT</button>'; ?>
                    </form>
                <?php
                    echo '</div>';
                    echo '</div>';
                }
                ?>
                <!-- <div class="promotion-card">
                    <div class="promotion-image">
                        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Khuyến mãi phòng deluxe">
                    </div>
                    <div class="promotion-content">
                        <h3 class="promotion-title">Gói Nghỉ Dưỡng Cuối Tuần</h3>
                        <p class="promotion-description">Tận hưởng kỳ nghỉ cuối tuần thư giãn với gói ưu đãi đặc biệt bao gồm phòng deluxe, ăn sáng buffet và spa miễn phí.</p>
                        <button class="promotion-button">CHI TIẾT</button>
                    </div>
                </div> -->
            </div>

            <!-- Thêm phần tử phân trang -->
            <div class="pagination-container">
                <span class="pagination-info"></span>
                <div class="pagination-nav"></div>
            </div>
        </div>
    </div>
    <?php include "footer.php" ?>
    <script src="/libertylaocai/view/js/sale.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>