<?php
require_once "../../model/UserModel.php";
require_once "session.php";

// Kiểm tra ngôn ngữ từ session, mặc định là 1 (Tiếng Việt)
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;

if (!empty($_SESSION['head_banner'])) {
    $getSelectedBanner = $_SESSION['head_banner'];
}

// $topics = getActiveTopicsWithVideo($languageId);
function generateSlug($string)
{
    return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', trim($string)));
}

// Lấy dữ liệu từ hàm getImagesAndVideos
$data = getImagesAndVideos($languageId);
$topics = $data['topics'];
$all_images = $data['images'];
$videos = $data['videos'];

// $all_images = getImagesAndVideos($languageId);
?>

<!DOCTYPE html>
<html lang="<?php echo $languageId == 1 ? 'vi' : 'en'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $languageId == 1 ? 'Thư Viện Ảnh & Video - Khách Sạn Liberty Lào Cai' : 'Photo & Video Gallery - Liberty Hotel Lao Cai'; ?></title>
    <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
    <meta name="description" content="<?php echo $languageId == 1 ? 'Khám phá thư viện ảnh và video tuyệt đẹp tại khách sạn Liberty Lào Cai, ghi lại những khoảnh khắc đáng nhớ.' : 'Explore the stunning photo and video gallery at Liberty Hotel Lao Cai, capturing memorable moments.'; ?>">
    <link rel="stylesheet" href="/libertylaocai/view/css/gallery.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>


<body>
    <?php include "header.php" ?>
    <div class="gallery-container">
        <div class="gallery-banner">
            <img src="/libertylaocai/view/img/<?= $getSelectedBanner['image']; ?>" alt="Banner Image" class="banner-image">
            <h1><?php echo $languageId == 1 ? 'Thư Viện' : 'Gallery'; ?></h1>
        </div>
        <div class="gallery-tabs">
            <!-- Nút "Tất cả" cố định -->
            <button class="tab-btn active" data-tab="all">
                <i class="fas fa-th"></i> Tất cả
            </button>

            <!-- Tạo động các nút từ cơ sở dữ liệu -->
            <?php foreach ($topics as $index => $topic): ?>
                <button class="tab-btn<?php echo $index === 0 ? ' active' : ''; ?>" data-tab="<?php echo $topic['id'] === '16' ? 'video' : $topic['id']; ?>">
                    <?php echo htmlspecialchars($topic['topic_display']); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Thay thế phần gallery-content trong gallery.php -->
        <div class="gallery-content">
            <?php

            // Hiển thị nội dung tab
            foreach ($topics as $index => $topic) {
                $tab_id = $topic['id'] === '16' ? 'video' : $topic['id']; // ID 16 tương ứng với Video trong bảng thuvien
                $active_class = $index === 0 ? 'active' : '';
            ?>
                <div class="tab-content <?php echo $active_class; ?>" id="tab-<?php echo htmlspecialchars($tab_id); ?>">
                    <div class="gallery-grid">
                        <?php
                        if ($topic['id'] === 16) { // Nếu là tab Video
                            if (empty($videos)) {
                                echo "<p>" . ($languageId == 1 ? 'Chưa có video nào.' : 'No videos available.') . "</p>";
                            } else {
                                foreach ($videos as $video) {
                        ?>
                                    <div class="gallery-item video lazy-item" data-src="/libertylaocai/view/video/<?php echo htmlspecialchars($video); ?>">
                                        <div class="lazy-placeholder video-placeholder">
                                            <div class="loading-spinner">
                                                <i class="fas fa-spinner fa-spin"></i>
                                            </div>
                                            <div class="video-info">
                                                <i class="fas fa-play-circle"></i>
                                                <span>Video</span>
                                            </div>
                                        </div>
                                        <video muted style="display: none;"></video>
                                        <div class="overlay"><i class="fas fa-play"></i></div>
                                    </div>
                                <?php
                                }
                            }
                        } else { // Nếu là tab hình ảnh
                            $images = isset($all_images[$topic['id']]) ? $all_images[$topic['id']] : [];
                            if (empty($images)) {
                                echo "<p>" . ($languageId == 1 ? 'Chưa có hình ảnh nào trong danh mục này.' : 'No images in this category.') . "</p>";
                            } else {
                                foreach ($images as $i => $image) {
                                    $class = '';
                                    if ($i % 8 === 0) $class = 'large';
                                    elseif ($i % 5 === 0) $class = 'tall';
                                    elseif ($i % 7 === 0) $class = 'wide';
                                ?>
                                    <div class="gallery-item lazy-item <?php echo $class; ?>" data-src="/libertylaocai/view/img/<?php echo htmlspecialchars($image); ?>" data-alt="<?php echo htmlspecialchars($topic['topic_display']); ?>">
                                        <div class="lazy-placeholder">
                                            <div class="loading-spinner">
                                                <i class="fas fa-spinner fa-spin"></i>
                                            </div>
                                        </div>
                                        <img style="display: none;" alt="<?php echo htmlspecialchars($topic['topic_display']); ?>">
                                        <div class="overlay"><i class="fas fa-search"></i></div>
                                    </div>
                        <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>

        <!-- Loading More Button -->
        <div class="load-more-container" style="display: none;">
            <button class="load-more-btn">
                <i class="fas fa-plus"></i>
                <span><?php echo $languageId == 1 ? 'Tải thêm' : 'Load More'; ?></span>
            </button>
        </div>

        <!-- Modal for Image/Video Preview -->
        <div class="modal" id="galleryModal">
            <span class="close">×</span>
            <div class="modal-content">
                <div class="image-container">
                    <img id="modalImage" src="" alt="Modal Image">
                    <video id="modalVideo" controls style="display: none;">
                        <source src="" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <button class="nav-btn prev"><i class="fas fa-chevron-left"></i></button>
                <button class="nav-btn next"><i class="fas fa-chevron-right"></i></button>
                <div class="modal-info" id="modalInfo"></div>
                <div class="zoom-controls">
                    <button class="zoom-btn" id="zoomIn">+</button>
                    <span class="zoom-info" id="zoomInfo">100%</span>
                    <button class="zoom-btn" id="zoomOut">-</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Tab Switching and Modal -->
    <script src="/libertylaocai/view/js/gallery.js"> </script>
    <?php include "footer.php" ?>
</body>

</html>