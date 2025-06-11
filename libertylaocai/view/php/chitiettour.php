<?php
// Kết nối cơ sở dữ liệu
require_once '../../model/config/connect.php';

// Lấy id_dichvu từ URL hoặc nguồn khác
$id_dichvu = isset($_GET['id_dichvu']) ? (int)$_GET['id_dichvu'] : 3; // Mặc định id=3 cho Tour Sapa
$languageId = isset($_SESSION['language_id']) ? (int)$_SESSION['language_id'] : 1; // Lấy từ session, mặc định tiếng Việt

// Kiểm tra id_dichvu có tồn tại trong bảng dichvu
$sql = "SELECT id FROM dichvu WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_dichvu);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo '<div class="container"><h1>' . ($languageId == 1 ? 'Tour không tồn tại' : 'Tour does not exist') . '</h1><p>' . ($languageId == 1 ? 'Vui lòng chọn tour khác.' : 'Please select another tour.') . '</p></div>';
    include "footer.php";
    exit;
}

// Lấy danh sách ảnh từ bảng anhdichvu
$sql = "SELECT image, is_primary 
        FROM anhdichvu 
        WHERE id_dichvu = ? 
        ORDER BY is_primary DESC, id ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_dichvu);
$stmt->execute();
$result = $stmt->get_result();
$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = [
        'image' => '/libertylaocai/view/img/' . htmlspecialchars($row['image']),
        'is_primary' => $row['is_primary']
    ];
}

// Lấy thông tin khách sạn
$thongtinkhachsan_query = "SELECT phone, email FROM thongtinkhachsan WHERE id = 1";
$thongtinkhachsan_result = mysqli_query($conn, $thongtinkhachsan_query);
$thongtinkhachsan = mysqli_fetch_assoc($thongtinkhachsan_result);

// Lấy title và content từ bảng dichvu_ngonngu
$sql = "SELECT title, content 
        FROM dichvu_ngonngu 
        WHERE id_dichvu = ? AND id_ngonngu = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_dichvu, $languageId);
$stmt->execute();
$result = $stmt->get_result();
$tour = $result->fetch_assoc();

// Lấy mô tả tour từ bảng motatour
$sql = "SELECT content 
        FROM motatour 
        WHERE id_dichvu = ? AND id_ngonngu = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_dichvu, $languageId);
$stmt->execute();
$result = $stmt->get_result();
$mota = $result->fetch_assoc();

// Lấy giá từ bảng dichvu
$sql = "SELECT price 
        FROM dichvu 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_dichvu);
$stmt->execute();
$result = $stmt->get_result();
$dichvu = $result->fetch_assoc();


// Lấy danh sách tour ngẫu nhiên (type = 'tour', tối đa 3)
$tours_query = "
    SELECT dn.id_dichvu, dn.title, a.image 
    FROM dichvu d
    LEFT JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu 
    LEFT JOIN anhdichvu a ON d.id = a.id_dichvu AND a.is_primary = 1 
    WHERE dn.id_ngonngu = ? AND d.type = 'tour' AND dn.id_dichvu != ?
    ORDER BY RAND()
    LIMIT 3";
$tours_stmt = $conn->prepare($tours_query);
$tours_stmt->bind_param("ii", $languageId, $id_dichvu);
$tours_stmt->execute();
$tours_result = $tours_stmt->get_result();
$tours = [];
while ($row = $tours_result->fetch_assoc()) {
    $tours[] = $row;
}
$tours_stmt->close();

// Lấy danh sách dịch vụ nổi bật ngẫu nhiên (type = 'dichvu', tối đa 2)
$services_query = "
    SELECT dn.id_dichvu, dn.title, a.image 
    FROM dichvu d
    LEFT JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu 
    LEFT JOIN anhdichvu a ON d.id = a.id_dichvu AND a.is_primary = 1 
    WHERE dn.id_ngonngu = ? AND d.type = 'dichvu' AND dn.id_dichvu != ?
    ORDER BY RAND()
    LIMIT 2";
$services_stmt = $conn->prepare($services_query);
$services_stmt->bind_param("ii", $languageId, $id_dichvu);
$services_stmt->execute();
$services_result = $services_stmt->get_result();
$services = [];
while ($row = $services_result->fetch_assoc()) {
    $services[] = $row;
}
$services_stmt->close();
//lấy menu
// Kiểm tra type của dịch vụ
$sql = "SELECT type FROM dichvu WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_dichvu);
$stmt->execute();
$result = $stmt->get_result();
$dichvu_type = $result->fetch_assoc()['type'];

// Lấy danh sách menu chỉ khi type là 'tour'
$menus = [];
if ($dichvu_type === 'tour') {
    $sql = "SELECT td.id, tdn.title, tdn.content 
            FROM thucdon_tour td
            LEFT JOIN thucdontour_ngonngu tdn ON td.id = tdn.id_menu 
            WHERE tdn.id_ngonngu = ? AND td.type = 'tour'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $languageId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $menus[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'content' => $row['content']
        ];
    }
    $stmt->close();
}
// Xử lý POST từ form đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reviewer-name'])) {
    $reviewerName = trim($_POST['reviewer-name']);
    $reviewerPhone = trim($_POST['reviewer-phone']);
    $reviewerEmail = trim($_POST['reviewer-email'] ?? '');
    $rating = (int)$_POST['rating'];
    $content = trim($_POST['review-content']);
    $id_dichvu = isset($_POST['id_dichvu']) ? (int)$_POST['id_dichvu'] : 0;

    $errors = [];
    if (empty($reviewerName)) {
        $errors[] = $languageId == 1 ? "Họ và tên không được để trống." : "Full name cannot be empty.";
    }
    if (!preg_match("/^[0-9]{10,11}$/", $reviewerPhone)) {
        $errors[] = $languageId == 1 ? "Số điện thoại không hợp lệ (10-11 số)." : "Invalid phone number (10-11 digits).";
    }
    if (!empty($reviewerEmail) && !filter_var($reviewerEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = $languageId == 1 ? "Email không hợp lệ." : "Invalid email.";
    }
    if ($rating < 1 || $rating > 5) {
        $errors[] = $languageId == 1 ? "Vui lòng chọn số sao hợp lệ (1-5 sao)." : "Please select a valid rating (1-5 stars).";
    }
    if (empty($content)) {
        $errors[] = $languageId == 1 ? "Nội dung đánh giá không được để trống." : "Review content cannot be empty.";
    }
    if ($id_dichvu <= 0) {
        $errors[] = $languageId == 1 ? "ID dịch vụ không hợp lệ." : "Invalid service ID.";
    }

    // Kiểm tra id_dichvu
    $sql = "SELECT id FROM dichvu WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_dichvu);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $errors[] = $languageId == 1 ? "Dịch vụ không tồn tại (id_dichvu: $id_dichvu)." : "Service does not exist (id_dichvu: $id_dichvu).";
    }

    if (!empty($errors)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
        exit;
    }

    try {
        // Thêm hoặc lấy id_khachhang
        $sql = "SELECT id FROM khachhang WHERE phone = ? AND name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $reviewerPhone, $reviewerName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $khachhang = $result->fetch_assoc();
            $id_khachhang = $khachhang['id'];
            if (!empty($reviewerEmail)) {
                $sql = "UPDATE khachhang SET email = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $reviewerEmail, $id_khachhang);
                $stmt->execute();
            }
        } else {
            $sql = "INSERT INTO khachhang (name, phone, email) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $reviewerName, $reviewerPhone, $reviewerEmail);
            $stmt->execute();
            $id_khachhang = $conn->insert_id;
        }

        // Lưu bình luận
        $sql = "INSERT INTO binhluan (content, create_at, rate, id_khachhang, active) VALUES (?, NOW(), ?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $content, $rating, $id_khachhang);
        $stmt->execute();
        $id_binhluan = $conn->insert_id;

        // Liên kết bình luận với dịch vụ
        $sql = "INSERT INTO binhluan_dichvu (id_dichvu, id_binhluan) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_dichvu, $id_binhluan);
        $success = $stmt->execute();

        header('Content-Type: application/json');
        if ($success) {
            echo json_encode([
                'success' => true,
                'message' => $languageId == 1 ? 'Gửi đánh giá thành công!' : 'Review submitted successfully!',
                'review' => [
                    'name' => $reviewerName,
                    'date' => date('d/m/Y'),
                    'rating' => $rating,
                    'content' => $content
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => $languageId == 1 ? 'Lỗi khi lưu đánh giá vào bảng binhluan_dichvu.' : 'Error saving review to binhluan_dichvu table.']);
        }
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $languageId == 1 ? 'Lỗi server: ' . $e->getMessage() : 'Server error: ' . $e->getMessage()]);
    }
    exit;
}

// Xử lý tải thêm đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'load_more_reviews') {
    header('Content-Type: application/json');
    
    $id_dichvu = isset($_POST['id_dichvu']) ? (int)$_POST['id_dichvu'] : 0;
    $offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;
    $limit = 3;

    if ($id_dichvu <= 0) {
        echo json_encode(['success' => false, 'message' => $languageId == 1 ? 'ID dịch vụ không hợp lệ.' : 'Invalid service ID.']);
        exit;
    }

    try {
        $sql = "SELECT id FROM dichvu WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_dichvu);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => $languageId == 1 ? "Dịch vụ không tồn tại (id_dichvu: $id_dichvu)." : "Service does not exist (id_dichvu: $id_dichvu)."]);
            exit;
        }

        $sql = "SELECT bl.id, bl.content, bl.create_at, bl.rate, kh.name
                FROM binhluan bl
                JOIN binhluan_dichvu bldv ON bl.id = bldv.id_binhluan
                JOIN khachhang kh ON bl.id_khachhang = kh.id
                WHERE bldv.id_dichvu = ? AND bl.active = 1
                ORDER BY bl.create_at DESC
                LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $id_dichvu, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = [
                'name' => $row['name'],
                'date' => date('d/m/Y', strtotime($row['create_at'])),
                'rating' => $row['rate'],
                'content' => $row['content']
            ];
        }

        $sql_total = "SELECT COUNT(*) as total FROM binhluan bl
                      JOIN binhluan_dichvu bldv ON bl.id = bldv.id_binhluan
                      WHERE bldv.id_dichvu = ? AND bl.active = 1";
        $stmt_total = $conn->prepare($sql_total);
        $stmt_total->bind_param("i", $id_dichvu);
        $stmt_total->execute();
        $total_result = $stmt_total->get_result();
        $total_reviews = $total_result->fetch_assoc()['total'];

        $has_more = ($offset + count($reviews)) < $total_reviews;

        echo json_encode([
            'success' => true,
            'reviews' => $reviews,
            'has_more' => $has_more
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $languageId == 1 ? 'Lỗi server: ' . $e->getMessage() : 'Server error: ' . $e->getMessage()]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $languageId == 1 ? 'vi' : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
        $sql = "SELECT title FROM dichvu_ngonngu WHERE id_dichvu = ? AND id_ngonngu = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_dichvu, $languageId);
        $stmt->execute();
        $result = $stmt->get_result();
        $tour = $result->fetch_assoc();
        echo htmlspecialchars($tour['title'] ?? ($languageId == 1 ? 'Tour Sapa - Liberty Lào Cai' : 'Sapa Tour - Liberty Lao Cai'));
        ?>
    </title>    
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
                            <img src="<?php echo $image['image']; ?>" alt="<?php echo $languageId == 1 ? 'Hình ảnh Tour ' . ($index + 1) : 'Tour Image ' . ($index + 1); ?>">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="slide active">
                        <img src="https://via.placeholder.com/1200x800/cccccc/666666?text=No+Image" alt="<?php echo $languageId == 1 ? 'Không có hình ảnh' : 'No Image'; ?>">
                    </div>
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
                        $sql = "SELECT title, content 
                                FROM dichvu_ngonngu 
                                WHERE id_dichvu = ? AND id_ngonngu = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ii", $id_dichvu, $languageId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $tour = $result->fetch_assoc();
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
                        if (is_numeric($dichvu['price'])) {
                            echo '<span class="price-label">' . ($languageId == 1 ? 'Giá từ:' : 'Price from:') . '</span>';
                            echo '<span class="price-value">' . number_format($dichvu['price'], 0, ',', '.') . ' VNĐ</span>';
                        } else {
                            echo '<span class="price-value">' . htmlspecialchars($dichvu['price']) . '</span>';
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
                           <?php if ($dichvu_type === 'tour' && !empty($menus)): ?>
                                <button class="tab-btn" onclick="openTab(event, 'menu')">
                                    <i class="fas fa-utensils"></i> <?php echo $languageId == 1 ? 'Menu' : 'Menu'; ?>
                                </button>
                            <?php endif; ?>
                        </div>

                        <!-- Tab Contents -->
                        <div id="overview" class="tab-content active">
                            
                            <div class="description">
                                <?php
                                $sql = "SELECT content 
                                        FROM motatour 
                                        WHERE id_dichvu = ? AND id_ngonngu = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ii", $id_dichvu, $languageId);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $mota = $result->fetch_assoc();

                                if ($mota && !empty($mota['content'])) {
                                    $paragraphs = explode("\n", trim($mota['content']));
                                    foreach ($paragraphs as $paragraph) {
                                        if (!empty($paragraph)) {
                                            echo '<p>' . htmlspecialchars($paragraph) . '</p>';
                                        }
                                    }
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
                                            <img src="<?php echo $image['image']; ?>" alt="<?php echo $languageId == 1 ? 'Hình ảnh Tour' : 'Tour Image'; ?>">
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="gallery-item">
                                        <img src="https://via.placeholder.com/400x300/cccccc/666666?text=No+Image" alt="<?php echo $languageId == 1 ? 'Không có hình ảnh' : 'No Image'; ?>">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($dichvu_type === 'tour' && !empty($menus)): ?>
                            <div id="menu" class="tab-content">
                                <h2><?php echo $languageId == 1 ? 'Thực đơn' : 'Menu'; ?></h2>
                                <div class="menu-container">
                                    <?php foreach ($menus as $menu): ?>
                                        <div class="menu-set">
                                            <h3><?php echo htmlspecialchars($menu['title']); ?></h3>
                                            <?php
                                            $items = explode("\n", trim($menu['content']));
                                            if (!empty($items)): ?>
                                                <ul>
                                                    <?php foreach ($items as $item): ?>
                                                        <?php if (!empty($item)): ?>
                                                            <li><?php echo htmlspecialchars($item); ?></li>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </ul>
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
                                <?php
                                $sql = "SELECT AVG(bl.rate) as avg_rating, COUNT(bl.id) as total_reviews,
                                            SUM(CASE WHEN bl.rate = 5 THEN 1 ELSE 0 END) as rate_5,
                                            SUM(CASE WHEN bl.rate = 4 THEN 1 ELSE 0 END) as rate_4,
                                            SUM(CASE WHEN bl.rate = 3 THEN 1 ELSE 0 END) as rate_3,
                                            SUM(CASE WHEN bl.rate = 2 THEN 1 ELSE 0 END) as rate_2,
                                            SUM(CASE WHEN bl.rate = 1 THEN 1 ELSE 0 END) as rate_1
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
                                $rate_counts = [
                                    5 => $stats['rate_5'] ?? 0,
                                    4 => $stats['rate_4'] ?? 0,
                                    3 => $stats['rate_3'] ?? 0,
                                    2 => $stats['rate_2'] ?? 0,
                                    1 => $stats['rate_1'] ?? 0
                                ];

                                $percentages = [];
                                foreach ($rate_counts as $star => $count) {
                                    $percentages[$star] = $total_reviews > 0 ? round(($count / $total_reviews) * 100) : 0;
                                }

                                echo '<div class="overall-rating">';
                                echo '<span class="rating-score">' . $avg_rating . '</span>';
                                echo '<div class="rating-stars">';
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= floor($avg_rating)) {
                                        echo '<i class="fas fa-star"></i>';
                                    } elseif ($i == ceil($avg_rating) && $avg_rating - floor($avg_rating) >= 0.5) {
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    } else {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                }
                                echo '</div>';
                                echo '<span class="rating-count">(' . $total_reviews . ' ' . ($languageId == 1 ? 'đánh giá' : 'reviews') . ')</span>';
                                echo '</div>';

                                echo '<div class="rating-breakdown">';
                                for ($star = 5; $star >= 1; $star--) {
                                    echo '<div class="rating-bar">';
                                    echo '<span class="rating-label">' . $star . ' ' . ($languageId == 1 ? 'sao' : 'stars') . '</span>';
                                    echo '<div class="bar-container">';
                                    echo '<div class="bar-fill" style="width: ' . $percentages[$star] . '%"></div>';
                                    echo '</div>';
                                    echo '<span class="rating-percent">' . $percentages[$star] . '%</span>';
                                    echo '</div>';
                                }
                                echo '</div>';
                                ?>
                            </div>

                            <div class="reviews-list">
                                <?php
                                $sql = "SELECT bl.id, bl.content, bl.create_at, bl.rate, kh.name
                                        FROM binhluan bl
                                        JOIN binhluan_dichvu bldv ON bl.id = bldv.id_binhluan
                                        JOIN khachhang kh ON bl.id_khachhang = kh.id
                                        WHERE bldv.id_dichvu = ? AND bl.active = 1
                                        ORDER BY bl.create_at DESC
                                        LIMIT 3";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $id_dichvu);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    while ($review = $result->fetch_assoc()) {
                                        echo '<div class="review-item">';
                                        echo '<div class="review-header">';
                                        echo '<div class="reviewer-info">';
                                        echo '<div class="reviewer-avatar"><i class="fas fa-user"></i></div>';
                                        echo '<div class="reviewer-details">';
                                        echo '<div class="reviewer-name">' . htmlspecialchars($review['name']) . '</div>';
                                        echo '<div class="review-date">' . date('d/m/Y', strtotime($review['create_at'])) . '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '<div class="review-rating">';
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo ($i <= $review['rate']) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                        }
                                        echo '</div>';
                                        echo '</div>';
                                        echo '<div class="review-content">';
                                        echo '<p>' . htmlspecialchars($review['content']) . '</p>';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo '<p>' . ($languageId == 1 ? 'Chưa có đánh giá nào cho tour này.' : 'No reviews for this tour yet.') . '</p>';
                                }
                                ?>
                            </div>

                            <button class="show-more-reviews"><i class="fas fa-chevron-down"></i> <?php echo $languageId == 1 ? 'Xem thêm đánh giá' : 'Show More Reviews'; ?></button>
                            <button class="hide-reviews" style="display: none;"><i class="fas fa-chevron-up"></i> <?php echo $languageId == 1 ? 'Ẩn bình luận' : 'Hide Reviews'; ?></button>
                            <div class="write-review-section">
                                <button class="write-review-btn"><i class="fas fa-pen"></i> <?php echo $languageId == 1 ? 'Viết đánh giá của bạn' : 'Write Your Review'; ?></button>
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
                                            <label for="reviewer-phone"><?php echo $languageId == 1 ? 'Số điện thoại *:' : 'Phone Number *:'; ?></label>
                                            <input type="tel" id="reviewer-phone" name="reviewer-phone" placeholder="<?php echo $languageId == 1 ? 'Nhập số điện thoại' : 'Enter your phone number'; ?>" required pattern="[0-9]{10,11}">
                                        </div>
                                        <div class="form-group">
                                            <label for="reviewer-email"><?php echo $languageId == 1 ? 'Email:' : 'Email:'; ?></label>
                                            <input type="email" id="reviewer-email" name="reviewer-email" placeholder="<?php echo $languageId == 1 ? 'Nhập email (tùy chọn)' : 'Enter your email (optional)'; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="review-content"><?php echo $languageId == 1 ? 'Nội dung đánh giá *:' : 'Review Content *:'; ?></label>
                                            <textarea id="review-content" name="review-content" placeholder="<?php echo $languageId == 1 ? 'Chia sẻ trải nghiệm của bạn...' : 'Share your experience...'; ?>" required></textarea>
                                        </div>
                                        <div class="form-actions">
                                            <button type="button" class="cancel-btn"><?php echo $languageId == 1 ? 'Hủy' : 'Cancel'; ?></button>
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
                                    <?php if (!empty($tours)): ?>
                                        <?php foreach ($tours as $tour): ?>
                                            <div class="tour-item">
                                                <a href="/libertylaocai/view/php/chitiettour.php?id_dichvu=<?php echo htmlspecialchars($tour['id_dichvu']); ?>">
                                                    <img src="<?php echo $tour['image'] ? '/libertylaocai/view/img/' . htmlspecialchars($tour['image']) : '/libertylaocai/view/img/default-tour-image.png'; ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>">
                                                    <h5><?php echo htmlspecialchars($tour['title']); ?></h5>
                                                </a>
                                            </div>
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
                                    <?php if (!empty($services)): ?>
                                        <?php foreach ($services as $service): ?>
                                            <div class="service-item">
                                                <a href="/libertylaocai/view/php/chitiettour.php?id_dichvu=<?php echo htmlspecialchars($service['id_dichvu']); ?>">
                                                    <img src="<?php echo $service['image'] ? '/libertylaocai/view/img/' . htmlspecialchars($service['image']) : '/libertylaocai/view/img/default-service-image.png'; ?>" alt="<?php echo htmlspecialchars($service['title']); ?>">
                                                    <h5><?php echo htmlspecialchars($service['title']); ?></h5>
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p><?php echo $languageId == 1 ? 'Chưa có dịch vụ nào.' : 'No services available.'; ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="contact-info">
                                <h4><?php echo $languageId == 1 ? 'Cần hỗ trợ?' : 'Need Help?'; ?></h4>
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span><?php echo htmlspecialchars($thongtinkhachsan['phone']); ?></span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span><?php echo htmlspecialchars($thongtinkhachsan['email']); ?></span>
                                </div>
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