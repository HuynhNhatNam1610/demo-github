<?php
require_once "session.php";
require_once "../../model/UserModel.php";

// // Lấy id_dichvu từ URL hoặc nguồn khác
// $id_dichvu = isset($_GET['id_dichvu']) ? (int)$_GET['id_dichvu'] : 3; // Mặc định id=3 cho Tour Sapa
$languageId = isset($_SESSION['language_id']) ? (int)$_SESSION['language_id'] : 1;
$id_dichvu = isset($_SESSION['id_dichvu']) ? $_SESSION['id_dichvu'] : 1;
if (!empty($id_dichvu)) {
    $service = getServiceById($languageId, $id_dichvu);
    $images = $service['images'];
    $tour = getServiceContentById($id_dichvu, $languageId);
    $mota = $service['info']['content'];
    $dichvu = $service['info']['price'];
    $data = getRandomItems($languageId, $id_dichvu);
    $menus = getTourMenusIfApplicable($id_dichvu, $languageId);
    foreach ($menus as $menu) {
        $dichvu_type = $menu['type'];
    }
}

$thongtinkhachsan = getHotelInfoWithLanguage($languageId);
?>
<!DOCTYPE html>
<html lang="<?php echo $languageId == 1 ? 'vi' : 'en'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tour['title'] ?? ($languageId == 1 ? 'Tour Sapa' : 'Sapa Tour')); ?> - Khách Sạn Liberty Lào Cai</title>
    <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
    <meta name="description" content="<?php echo $languageId == 1 ? 'Khám phá chi tiết tour ' . htmlspecialchars($tour['title'] ?? 'Sapa') . ' tại Liberty Lào Cai với hành trình thú vị và dịch vụ chuyên nghiệp.' : 'Discover details of the ' . htmlspecialchars($tour['title'] ?? 'Sapa') . ' tour at Liberty Hotel Lao Cai with exciting itineraries and professional services.'; ?>">
    <link rel="stylesheet" href="/libertylaocai/view/css/chitiettour.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>


<body>
    <?php include "header.php"; ?>

    <div class="big-container">
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-slider">
                <?php if (!empty($images)): ?>
                    <?php foreach ($images as $index => $image): ?>
                        <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
                            <img src="/libertylaocai/view/img/<?php echo $image['image']; ?>" alt="<?php echo $languageId == 1 ? 'Hình ảnh Tour ' . ($index + 1) : 'Tour Image ' . ($index + 1); ?>">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php if (count($images) > 1): ?>
                <div class="hero-nav">
                    <button class="nav-btn prev" onclick="changeSlide(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="nav-btn next" onclick="changeSlide(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="hero-dots">
                    <?php foreach ($images as $index => $image): ?>
                        <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $index + 1; ?>)"></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Tour Info -->
        <section class="tour-info">
            <div class="container">
                <div class="tour-header">
                    <div class="tour-title">
                        <?php
                        if ($tour) {
                            echo '<h1>' . htmlspecialchars($tour['title']) . '</h1>';
                        } else {
                            echo '<h1>' . ($languageId == 1 ? 'Không tìm thấy thông tin tour' : 'Tour information not found') . '</h1>';
                        }
                        ?>
                        <div class="tour-rating">
                            <div class="stars">
                                <?php
                                $sql = "SELECT AVG(bl.rate) as avg_rating, COUNT(bl.id) as total_reviews
                                        FROM binhluan bl
                                        JOIN binhluan_dichvu bldv ON bl.id = bldv.id_binhluan
                                        WHERE bldv.id_dichvu = ? AND bl.active = 1";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $id_dichvu);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $stats = $result->fetch_assoc();

                                $avg_rating = $stats['avg_rating'] ? round($stats['avg_rating'], 1) : 0;
                                $total_reviews = $stats['total_reviews'] ?? 0;

                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= floor($avg_rating)) {
                                        echo '<i class="fas fa-star"></i>';
                                    } elseif ($i == ceil($avg_rating) && $avg_rating - floor($avg_rating) >= 0.5) {
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    } else {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                }
                                ?>
                            </div>
                            <span class="rating-text"><?php echo $avg_rating; ?>/5 (<?php echo $total_reviews; ?> <?php echo $languageId == 1 ? 'đánh giá' : 'reviews'; ?>)</span>
                        </div>
                    </div>
                    <div class="tour-price">
                        <?php
                        if (is_numeric($dichvu)) {
                            echo '<span class="price-label">' . ($languageId == 1 ? 'Giá từ:' : 'Price from:') . '</span>';
                            echo '<span class="price-value">' . number_format($dichvu, 0, ',', '.') . ' VNĐ</span>';
                        } else {
                            echo '<span class="price-value">' . htmlspecialchars($dichvu) . '</span>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tour Content -->
        <section class="tour-content">
            <div class="container">
                <div class="content-grid">
                    <div class="main-content">
                        <!-- Tabs -->
                        <div class="tabs">
                            <button class="tab-btn active" onclick="openTab(event, 'overview')">
                                <i class="fas fa-eye"></i> <?php echo $languageId == 1 ? 'Tổng quan' : 'Overview'; ?>
                            </button>
                            <button class="tab-btn" onclick="openTab(event, 'gallery')">
                                <i class="fas fa-images"></i> <?php echo $languageId == 1 ? 'Hình ảnh' : 'Gallery'; ?>
                            </button>
                            <?php if (!empty($menus)): ?>
                                <button class="tab-btn" onclick="openTab(event, 'menu')">
                                    <i class="fas fa-utensils"></i> <?php echo $languageId == 1 ? 'Menu' : 'Menu'; ?>
                                </button>
                            <?php endif; ?>
                        </div>

                        <!-- Tab Contents -->
                        <div id="overview" class="tab-content active">

                            <div class="description">
                                <?php
                                if (!empty($mota)) {
                                    echo $mota;
                                } else {
                                    echo '<p>' . ($languageId == 1 ? 'Không có mô tả nào cho tour này.' : 'No description available for this tour.') . '</p>';
                                }
                                ?>
                            </div>
                        </div>

                        <div id="gallery" class="tab-content">
                            <h2><?php echo $languageId == 1 ? 'Thư viện ảnh' : 'Photo Gallery'; ?></h2>
                            <div class="gallery-grid">
                                <?php if (!empty($images)): ?>
                                    <?php foreach ($images as $image): ?>
                                        <div class="gallery-item" onclick="openModal('<?php echo $image['image']; ?>')">
                                            <img src="/libertylaocai/view/img/<?php echo $image['image']; ?>" alt="<?php echo $languageId == 1 ? 'Hình ảnh Tour' : 'Tour Image'; ?>">
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (!empty($menus)): ?>
                            <div id="menu" class="tab-content">
                                <h2><?php echo $languageId == 1 ? 'Thực đơn' : 'Menu'; ?></h2>
                                <div class="menu-container">
                                    <?php foreach ($menus as $menu): ?>
                                        <div class="menu-set">
                                            <h3><?php echo htmlspecialchars($menu['title']); ?></h3>
                                            <?php if (!empty($menu['content'])): ?>
                                                <div class="menu-content">
                                                    <?php echo $menu['content']; ?>
                                                </div>
                                            <?php else: ?>
                                                <p><?php echo $languageId == 1 ? 'Chưa có món ăn trong thực đơn này.' : 'No items in this menu.'; ?></p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="reviews-section">
                            <div class="reviews-header">
                                <h3><?php echo $languageId == 1 ? 'Đánh giá từ khách hàng' : 'Customer Reviews'; ?></h3>
                                <div class="overall-rating">
                                    <span class="rating-score">0.0</span>
                                    <div class="rating-stars"></div>
                                    <span class="rating-count">(0 <?php echo $languageId == 1 ? 'đánh giá' : 'reviews'; ?>)</span>
                                </div>
                                <div class="rating-breakdown"></div>
                            </div>
                            <input type="hidden" name="id_dichvu" value="<?php echo htmlspecialchars($id_dichvu); ?>">
                            <div class="reviews-list"></div>
                            <div class="pagination-controls" style="display: none;">
                                <button class="show-more-reviews"><i class="fas fa-chevron-down"></i> <?php echo $languageId == 1 ? 'Xem thêm đánh giá' : 'Show More Reviews'; ?></button>
                                <div class="pagination-buttons" style="display: flex; gap: 10px; justify-content: center; margin-top: 20px;"></div>
                            </div>
                            <div class="write-review-section">
                                <button class="write-review-btn">
                                    <i class="fas fa-pen"></i> <?php echo $languageId == 1 ? 'Viết đánh giá của bạn' : 'Write Your Review'; ?>
                                </button>
                                <div class="review-form-container" id="reviewForm" style="display: none;">
                                    <h4><?php echo $languageId == 1 ? 'Chia sẻ trải nghiệm của bạn' : 'Share Your Experience'; ?></h4>
                                    <form class="review-form" id="reviewFormSubmit">
                                        <input type="hidden" name="id_dichvu" value="<?php echo htmlspecialchars($id_dichvu); ?>">
                                        <div class="form-group rating-input">
                                            <label><?php echo $languageId == 1 ? 'Đánh giá của bạn *:' : 'Your Rating *:'; ?></label>
                                            <div class="star-rating">
                                                <input type="radio" name="rating" value="5" id="star5">
                                                <label for="star5" class="star"><i class="fas fa-star"></i></label>
                                                <input type="radio" name="rating" value="4" id="star4">
                                                <label for="star4" class="star"><i class="fas fa-star"></i></label>
                                                <input type="radio" name="rating" value="3" id="star3">
                                                <label for="star3" class="star"><i class="fas fa-star"></i></label>
                                                <input type="radio" name="rating" value="2" id="star2">
                                                <label for="star2" class="star"><i class="fas fa-star"></i></label>
                                                <input type="radio" name="rating" value="1" id="star1">
                                                <label for="star1" class="star"><i class="fas fa-star"></i></label>
                                            </div>
                                            <div class="rating-text"><?php echo $languageId == 1 ? 'Chọn số sao' : 'Select rating'; ?></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="reviewer-name"><?php echo $languageId == 1 ? 'Họ và tên *:' : 'Full Name *:'; ?></label>
                                            <input type="text" id="reviewer-name" name="reviewer-name" placeholder="<?php echo $languageId == 1 ? 'Nhập họ và tên' : 'Enter your full name'; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="reviewer-email"><?php echo $languageId == 1 ? 'Email *:' : 'Email *:'; ?></label>
                                            <input type="email" id="reviewer-email" name="reviewer-email" placeholder="<?php echo $languageId == 1 ? 'Nhập email' : 'Enter your email'; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="review-content"><?php echo $languageId == 1 ? 'Nội dung đánh giá *:' : 'Review Content *:'; ?></label>
                                            <textarea id="review-content" name="review-content" placeholder="<?php echo $languageId == 1 ? 'Chia sẻ trải nghiệm của bạn...' : 'Share your experience...'; ?>" required></textarea>
                                        </div>
                                        <div class="form-actions">
                                            <button type="button" class="cancel-btn" onclick="toggleReviewForm()"><?php echo $languageId == 1 ? 'Hủy' : 'Cancel'; ?></button>
                                            <button type="submit" class="submit-review-btn"><i class="fas fa-paper-plane"></i> <?php echo $languageId == 1 ? 'Gửi đánh giá' : 'Submit Review'; ?></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar">
                        <div class="booking-card">
                            <!-- Các tour du lịch -->
                            <div class="tours-card">
                                <h4><?php echo $languageId == 1 ? 'Các Tour Du Lịch' : 'Travel Tours'; ?></h4>
                                <div class="tours-list">
                                    <?php if (!empty($data)): ?>
                                        <?php foreach ($data['tours'] as $tour): ?>
                                            <form action="/libertylaocai/user/submit" method="POST" style="display: inline;">
                                                <div class="tour-item">
                                                    <button>
                                                        <input type="hidden" name="chitietdichvu" value="<?php echo $tour['id_dichvu']; ?>">
                                                        <img src="<?php echo $tour['image'] ? '/libertylaocai/view/img/' . htmlspecialchars($tour['image']) : '/libertylaocai/view/img/default-tour-image.png'; ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>">
                                                        <h5><?php echo htmlspecialchars($tour['title']); ?></h5>
                                                    </button>
                                                </div>
                                            </form>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p><?php echo $languageId == 1 ? 'Chưa có tour nào.' : 'No tours available.'; ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Dịch vụ nổi bật -->
                            <div class="services-card">
                                <h4><?php echo $languageId == 1 ? 'Dịch Vụ Nổi Bật' : 'Featured Services'; ?></h4>
                                <div class="services-list">
                                    <?php if (!empty($data)): ?>
                                        <?php foreach ($data['services'] as $service): ?>
                                            <form action="/libertylaocai/user/submit" method="POST" style="display: inline;">
                                                <div class="service-item">
                                                    <button>
                                                        <input type="hidden" name="chitietdichvu" value="<?php echo $service['id_dichvu']; ?>">
                                                        <img src="<?php echo $service['image'] ? '/libertylaocai/view/img/' . htmlspecialchars($service['image']) : '/libertylaocai/view/img/default-service-image.png'; ?>" alt="<?php echo htmlspecialchars($service['title']); ?>">
                                                        <h5><?php echo htmlspecialchars($service['title']); ?></h5>
                                                    </button>
                                                </div>
                                            </form>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p><?php echo $languageId == 1 ? 'Chưa có dịch vụ nào.' : 'No services available.'; ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="contact-info">
                                <h4><?php echo $languageId == 1 ? 'Cần hỗ trợ?' : 'Need Help?'; ?></h4>
                                <?php if (!empty($informationHotel)): ?>
                                    <?php foreach ($informationHotel as $info): ?>
                                        <div class="contact-item">
                                            <i class="fas fa-phone"></i>
                                            <span><?php echo htmlspecialchars($info['phone']); ?></span>
                                        </div>
                                        <div class="contact-item">
                                            <i class="fas fa-envelope"></i>
                                            <span><?php echo htmlspecialchars($info['email']); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal -->
        <div id="imageModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <button class="modal-nav prev" onclick="changeModalSlide(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <img id="modalImage" src="" alt="">
                <button class="modal-nav next" onclick="changeModalSlide(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <div class="modal-controls">
                    <button class="zoom-btn" onclick="zoomIn()">
                        <i class="fas fa-search-plus"></i>
                    </button>
                    <button class="zoom-btn" onclick="zoomOut()">
                        <i class="fas fa-search-minus"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php include "footer.php"; ?>

    <script src="/libertylaocai/view/js/chitiettour.js"></script>
</body>

</html>