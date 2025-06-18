<?php
require_once "session.php";
require_once "../../model/UserModel.php";
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;
if (!empty($_SESSION['head_banner'])) {
    $getSelectedBanner = $_SESSION['head_banner'];
}

// Lấy ưu đãi
$news = getNews($languageId);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $languageId == 1 ? 'Tin Tức - Khách Sạn Liberty Lào Cai' : 'News - Liberty Hotel Lao Cai'; ?></title>
    <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
    <meta name="description" content="<?php echo $languageId == 1 ? 'Cập nhật tin tức mới nhất về sự kiện và hoạt động tại khách sạn Liberty Lào Cai.' : 'Stay updated with the latest news and events at Liberty Hotel Lao Cai.'; ?>">
    <link rel="stylesheet" href="/libertylaocai/view/css/tintuc.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>


<body>
    <?php include "header.php" ?>
    <div class="sale-container">
        <div class="sale-banner">
            <img src="/libertylaocai/view/img/<?= $getSelectedBanner['image']; ?>" alt="Banner Image" class="banner-image">
            <h1><?php echo $languageId == 1 ? 'TIN TỨC' : 'NEWS'; ?></h1>
        </div>
        <div class="promotions-section">
            <div class="section-title">
                <h2><?php echo $languageId == 1 ? 'Sự kiện gần đây' : 'Recent events'; ?></h2>
            </div>
            <div class="promotions-grid">
                <?php
                // Hiển thị các ưu đãi
                foreach ($news as $new) {
                    echo '<div class="promotion-card">';
                    echo '<div class="promotion-image">';
                    echo '<img src="' . htmlspecialchars($new['image']) . '" alt="' . htmlspecialchars($new['title']) . '">';
                    echo '<span class="corner-text">' . date('Y-m-d', strtotime($new['date'])) . '</span>';
                    echo '</div>';
                    echo '<div class="promotion-content">';
                    echo '<h3 class="promotion-title">' . htmlspecialchars($new['title']) . '</h3>';
                    echo '<p class="promotion-description">' . truncateContent($new['content'], 100) . '</p>';
                ?>
                    <form action="/libertylaocai/user/submit" method="POST" style="display: inline; ">
                        <input type="hidden" name="id_tintuc" value="<?php echo htmlspecialchars($new['id']); ?>">
                        <?php echo '<button type="submit" class="promotion-button">CHI TIẾT</button>'; ?>
                    </form>
                <?php
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>

            <!-- Thêm phần tử phân trang -->
            <div class="pagination-container">
                <span class="pagination-info"></span>
                <div class="pagination-nav"></div>
            </div>
        </div>
    </div>
    <?php include "footer.php" ?>
    <script src="/libertylaocai/view/js/tintuc.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>