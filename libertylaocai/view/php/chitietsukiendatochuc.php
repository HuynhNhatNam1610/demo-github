<?php
require_once "session.php";
require_once "../../model/UserModel.php";
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;
if (!empty($_SESSION['head_banner'])) {
    $getSelectedBanner = $_SESSION['head_banner'];
}

if (!empty($_SESSION['id_sukiendatochuc'])) {
    $id_sukiendatochuc = $_SESSION['id_sukiendatochuc'];
}
$getEventOrganizedById = getEventOrganizedById($languageId, $id_sukiendatochuc);
$getRelatedEventOrganized = getRelatedEventOrganized($languageId, $id_sukiendatochuc, 6);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($getEventOrganizedById['title'] ?? ($languageId == 1 ? 'Sự Kiện Đã Tổ Chức' : 'Organized Event')); ?> - <?php echo $languageId == 1 ? 'Khách Sạn Liberty Lào Cai' : 'Liberty Hotel Lao Cai'; ?></title>
    <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
    <meta name="description" content="<?php echo $languageId == 1 ? 'Khám phá chi tiết sự kiện ' . htmlspecialchars($getEventOrganizedById['title'] ?? 'sự kiện đã tổ chức') . ' tại khách sạn Liberty Lào Cai với hình ảnh và thông tin nổi bật.' : 'Explore details of the event ' . htmlspecialchars($getEventOrganizedById['title'] ?? 'organized event') . ' at Liberty Hotel Lao Cai with highlights and images.'; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/libertylaocai/view/css/chitietsukiendatochuc.css">
</head>


<body>
    <?php include "header.php" ?>

    <div class="saledetail-container">
        <div class="organized-detail-banner">
            <img src="/libertylaocai/view/img/<?= $getSelectedBanner['image']; ?>" alt="Banner Image" class="banner-image">
            <h1><?php echo $languageId == 1 ? 'SỰ KIỆN ĐÃ TỔ CHỨC' : 'EVENTS ORGANIZED'; ?></h1>
        </div>

        <div class="saledetail-content">
            <div class="content-wrapper">
                <div class="promotion-content">
                    <?php if (!empty($getEventOrganizedById)): ?>
                        <h2><?= $getEventOrganizedById['title']; ?></h2>
                        <p class="promotion-meta"><?php echo $languageId == 1 ? 'Ngày đăng' : 'Date posted'; ?>: <?= $getEventOrganizedById['create_at']; ?></p>
                        <div class="content-text">
                            <?= $getEventOrganizedById['content']; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="related-promotions">
            <div class="content-wrapper">
                <h2><?php echo $languageId == 1 ? 'Liên quan' : 'Relate to'; ?></h2>
                <div class="promotion-slider-container">
                    <button class="promotion-nav-btn promotion-nav-prev">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="promotions-grid-wrapper">
                        <div class="promotions-grid" id="promotionsGrid">
                            <?php
                            foreach ($getRelatedEventOrganized as $promo) {
                                echo '<div class="promotion-card" data-promotion-id="' . htmlspecialchars($promo['id']) . '">';
                                echo '<div class="promotion-image">';
                                echo '<img src="' . htmlspecialchars($promo['image']) . '" alt="' . htmlspecialchars($promo['title']) . '">';
                                echo '<span class="corner-text">' . date('Y-m-d', strtotime($promo['create_at'])) . '</span>';
                                echo '</div>';
                                echo '<div class="promotion-item-content">';
                                echo '<h3>' . htmlspecialchars($promo['title']) . '</h3>';
                                echo '<p>' . htmlspecialchars(mb_substr(strip_tags($promo['content']), 0, 100, 'UTF-8'));
                                if (mb_strlen($promo['content'], 'UTF-8') > 100) {
                                    echo '...';
                                }
                                echo '</p>';
                                echo '<form action="/libertylaocai/user/submit" method="POST" style="display: inline;">';
                                echo '<input type="hidden" name="id_sukiendatochuc" value="' . htmlspecialchars($promo['id']) . '">';
                                echo '<button type="submit" class="promotion-button">CHI TIẾT</button>';
                                echo '</form>';
                                echo '</div>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                    <button class="promotion-nav-btn promotion-nav-next">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Hidden form for submission -->
    <form id="promotionForm" action="/libertylaocai/user/submit" method="POST" style="display: none;">
        <input type="hidden" name="other_organized_id" id="promotionIdInput">
    </form>
    <?php include "footer.php" ?>
    <script src="/libertylaocai/view/js/chitietsukiendatochuc.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>