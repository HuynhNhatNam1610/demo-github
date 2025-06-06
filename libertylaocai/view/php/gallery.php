<?php
// Include database connection
require_once '../../model/config/connect.php';
require_once "session.php";

// Kiểm tra ngôn ngữ từ session, mặc định là 1 (Tiếng Việt)
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;

// Lấy lời chào cho trang gallery
$sql_greeting = "SELECT ncn.content 
                 FROM loichaoduocchon lcd 
                 JOIN nhungcauchaohoi_ngonngu ncn ON lcd.id_nhungcauchaohoi_ngonngu = ncn.id 
                 WHERE lcd.page = 'gallery' AND lcd.area = 'loichaothuvien' AND ncn.id_ngonngu = ?";
$stmt_greeting = $conn->prepare($sql_greeting);
$stmt_greeting->bind_param("i", $languageId);
$stmt_greeting->execute();
$result_greeting = $stmt_greeting->get_result();
$greeting = $result_greeting->fetch_assoc()['content'] ?? ($languageId == 1 ? 'Trang Chủ > Gallery' : 'Home > Gallery');

// Lấy ảnh banner cho trang gallery
$sql_banner = "SELECT image 
               FROM head_banner 
               WHERE page = 'gallery' AND area = 'gallery-banner'";
$result_banner = $conn->query($sql_banner);
$banner_image = $result_banner->num_rows > 0 
                ? '/libertylaocai/view/img/' . $result_banner->fetch_assoc()['image'] 
                : '/libertylaocai/view/img/breadcrumb-2.jpg';
?>

<!DOCTYPE html>
<html lang="<?php echo $languageId == 1 ? 'vi' : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <link rel="stylesheet" href="/libertylaocai/view/css/gallery.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include "header.php" ?>
    <div class="gallery-container">
        <div class="gallery-banner">
            <img src="<?php echo htmlspecialchars($banner_image); ?>" alt="Banner Image" class="banner-image">
            <h1><?php echo $languageId == 1 ? 'Thư Viện' : 'Gallery'; ?></h1>
            <div class="gallery-breadcomb">
                <?php echo htmlspecialchars($greeting); ?>
            </div>
        </div>

        <!-- Gallery Tabs -->
        <div class="gallery-tabs">
            <?php
            // Fetch active topics from thuvien table
            $sql_topics = "SELECT id, IF(? = 1, topic, topic_ngonngu) AS topic_display 
                           FROM thuvien 
                           WHERE active = 1 
                           ORDER BY id";
            $stmt_topics = $conn->prepare($sql_topics);
            $stmt_topics->bind_param("i", $languageId);
            $stmt_topics->execute();
            $result_topics = $stmt_topics->get_result();
            $topics = [];
            if ($result_topics->num_rows > 0) {
                while ($row = $result_topics->fetch_assoc()) {
                    $topics[] = ['id' => $row['id'], 'topic_display' => $row['topic_display']];
                }
            }

            // Add static "Video" tab if there are videos
            $sql_videos = "SELECT video FROM video";
            $result_videos = $conn->query($sql_videos);
            if ($result_videos->num_rows > 0) {
                $topics[] = ['id' => 'video', 'topic_display' => $languageId == 1 ? 'Video' : 'Video'];
            }

            // Render tab buttons
            foreach ($topics as $index => $topic) {
                $tab_id = $topic['id'] === 'video' ? 'video' : $topic['id'];
                $active_class = $index === 0 ? 'active' : '';
                echo "<button class='tab-btn $active_class' data-tab='tab-$tab_id'>" . htmlspecialchars($topic['topic_display']) . "</button>";
            }
            ?>
        </div>

        <!-- Gallery Content -->
        <div class="gallery-content">
            <?php
            // Fetch images from all relevant tables
            $image_tables = [
                'anhtintuc' => 'image',
                'anhtongquat' => 'image',
                'anhuudai' => 'image',
                'anhhoitruong' => 'image',
                'anhbar' => 'image',
                'anhnhahang' => 'image',
                'anhdichvu' => 'image',
                'anhkhachsan' => 'image',
                'anhsukiendatochuc' => 'image',
                'anhsukien' => 'image',
                'anhthucdon' => 'image'
            ];

            $all_images = [];
            foreach ($image_tables as $table => $image_column) {
                $sql = "SELECT id_topic, $image_column FROM $table";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $all_images[$row['id_topic']][] = $row[$image_column];
                    }
                }
            }

            // Fetch videos
            $sql_videos = "SELECT video FROM video";
            $result_videos = $conn->query($sql_videos);
            $videos = [];
            if ($result_videos->num_rows > 0) {
                while ($row = $result_videos->fetch_assoc()) {
                    $videos[] = $row['video'];
                }
            }

            // Render tab content
            foreach ($topics as $index => $topic) {
                $tab_id = $topic['id'] === 'video' ? 'video' : $topic['id'];
                $active_class = $index === 0 ? 'active' : '';
                echo "<div class='tab-content $active_class' id='tab-$tab_id'>";
                echo "<div class='gallery-grid'>";

                if ($topic['id'] === 'video') {
                    if (empty($videos)) {
                        echo "<p>" . ($languageId == 1 ? 'Chưa có video nào.' : 'No videos available.') . "</p>";
                    } else {
                        foreach ($videos as $video) {
                            echo "<div class='gallery-item video'>";
                            echo "<video src='/libertylaocai/view/video/$video' muted></video>";
                            echo "<div class='overlay'><i class='fas fa-play'></i></div>";
                            echo "</div>";
                        }
                    }
                } else {
                    $images = isset($all_images[$topic['id']]) ? $all_images[$topic['id']] : [];
                    if (empty($images)) {
                        echo "<p>" . ($languageId == 1 ? 'Chưa có hình ảnh nào trong danh mục này.' : 'No images in this category.') . "</p>";
                    } else {
                        foreach ($images as $i => $image) {
                            $class = '';
                            if ($i % 8 === 0) $class = 'large';
                            elseif ($i % 5 === 0) $class = 'tall';
                            elseif ($i % 7 === 0) $class = 'wide';
                            echo "<div class='gallery-item $class'>";
                            echo "<img src='/libertylaocai/view/img/$image' alt='" . htmlspecialchars($topic['topic_display']) . "'>";
                            echo "<div class='overlay'><i class='fas fa-search'></i></div>";
                            echo "</div>";
                        }
                    }
                }
                echo "</div>";
                echo "</div>";
            }
            ?>
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
    <script>
        // Tab switching
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                button.classList.add('active');
                document.getElementById(button.dataset.tab).classList.add('active');
            });
        });

        // Modal functionality
        const modal = document.getElementById('galleryModal');
        const modalImage = document.getElementById('modalImage');
        const modalVideo = document.getElementById('modalVideo');
        const modalInfo = document.getElementById('modalInfo');
        const closeModal = document.querySelector('.close');
        const prevBtn = document.querySelector('.prev');
        const nextBtn = document.querySelector('.next');
        const zoomInBtn = document.getElementById('zoomIn');
        const zoomOutBtn = document.getElementById('zoomOut');
        const zoomInfo = document.getElementById('zoomInfo');
        const imageContainer = document.querySelector('.image-container');

        let currentItems = [];
        let currentIndex = 0;
        let zoomLevel = 1;

        document.querySelectorAll('.gallery-item').forEach((item, index) => {
            item.addEventListener('click', () => {
                currentItems = Array.from(item.closest('.gallery-grid').querySelectorAll('.gallery-item'));
                currentIndex = currentItems.indexOf(item);
                openModal();
            });
        });

        function openModal() {
            const item = currentItems[currentIndex];
            const isVideo = item.classList.contains('video');
            modalImage.style.display = isVideo ? 'none' : 'block';
            modalVideo.style.display = isVideo ? 'block' : 'none';

            if (isVideo) {
                modalVideo.querySelector('source').src = item.querySelector('video').src;
                modalVideo.load();
                modalInfo.textContent = '<?php echo $languageId == 1 ? "Video" : "Video"; ?>';
            } else {
                modalImage.src = item.querySelector('img').src;
                modalInfo.textContent = item.querySelector('img').alt;
            }

            zoomLevel = 1;
            modalImage.style.transform = `scale(${zoomLevel})`;
            zoomInfo.textContent = '100%';
            modal.style.display = 'block';
        }

        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
            modalVideo.pause();
        });

        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + currentItems.length) % currentItems.length;
            openModal();
        });

        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % currentItems.length;
            openModal();
        });

        zoomInBtn.addEventListener('click', () => {
            if (zoomLevel < 3) {
                zoomLevel += 0.2;
                modalImage.style.transform = `scale(${zoomLevel})`;
                zoomInfo.textContent = `${Math.round(zoomLevel * 100)}%`;
            }
        });

        zoomOutBtn.addEventListener('click', () => {
            if (zoomLevel > 0.5) {
                zoomLevel -= 0.2;
                modalImage.style.transform = `scale(${zoomLevel})`;
                zoomInfo.textContent = `${Math.round(zoomLevel * 100)}%`;
            }
        });

        // Image panning
        let isDragging = false;
        let startX, startY, translateX = 0, translateY = 0;

        imageContainer.addEventListener('mousedown', (e) => {
            if (modalImage.style.display === 'none') return;
            isDragging = true;
            imageContainer.classList.add('grabbing');
            startX = e.clientX - translateX;
            startY = e.clientY - translateY;
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            translateX = e.clientX - startX;
            translateY = e.clientY - startY;
            modalImage.style.transform = `scale(${zoomLevel}) translate(${translateX}px, ${translateY}px)`;
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
            imageContainer.classList.remove('grabbing');
        });

        // Touch support for mobile
        imageContainer.addEventListener('touchstart', (e) => {
            if (modalImage.style.display === 'none') return;
            isDragging = true;
            startX = e.touches[0].clientX - translateX;
            startY = e.touches[0].clientY - translateY;
        });

        imageContainer.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            translateX = e.touches[0].clientX - startX;
            translateY = e.touches[0].clientY - startY;
            modalImage.style.transform = `scale(${zoomLevel}) translate(${translateX}px, ${translateY}px)`;
        });

        imageContainer.addEventListener('touchend', () => {
            isDragging = false;
        });
    </script>
    <?php include "footer.php" ?>
</body>
</html>