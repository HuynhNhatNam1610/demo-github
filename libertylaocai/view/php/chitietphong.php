<?php
require_once "../../model/config/connect.php";
require_once "session.php";

// Thêm tiêu đề cache để ngăn ERR_CACHE_MISS
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

// Lấy thông báo từ session (nếu có)
$review_success = isset($_SESSION['review_success']) ? $_SESSION['review_success'] : null;
$booking_success = isset($_SESSION['booking_success']) ? $_SESSION['booking_success'] : null;
$review_error = isset($_SESSION['review_error']) ? $_SESSION['review_error'] : null;
$booking_error = isset($_SESSION['booking_error']) ? $_SESSION['booking_error'] : null;

// Xóa thông báo sau khi lấy
unset($_SESSION['review_success']);
unset($_SESSION['booking_success']);
unset($_SESSION['review_error']);
unset($_SESSION['booking_error']);

// Kiểm tra ngôn ngữ từ session, mặc định là 1 (tiếng Việt)
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;


$text = $texts[$languageId];

// Lấy room_id từ POST hoặc GET
$room_id = isset($_POST['room_id']) ? (int)$_POST['room_id'] : (isset($_GET['room_id']) ? (int)$_GET['room_id'] : 0);

if ($room_id == 0) {
    header("Location: danhsachphong.php");
    exit();
}

// Hàm lấy hình ảnh cho phòng
function getRoomImages($conn, $room_id) {
    $sql_images = "
        SELECT image
        FROM anhkhachsan
        WHERE id_loaiphongnghi = ? AND active = 1
    ";
    $stmt = $conn->prepare($sql_images);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $images = [];
    while($row = $result->fetch_assoc()) {
        $images[] = $row['image'];
    }
    
    if (empty($images)) {
        $images = [
            'https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
        ];
    }
    
    return $images;
}

// Lấy danh sách ảnh cho phòng
$images = getRoomImages($conn, $room_id);

// Lấy thông tin chi tiết phòng
$sql_room_detail = "
    SELECT 
        lpn.id,
        lpn.quantity,
        lpn.area,
        lpn.price,
        lpnnn.name,
        lpnnn.description
    FROM loaiphongnghi lpn
    LEFT JOIN loaiphongnghi_ngonngu lpnnn ON lpn.id = lpnnn.id_loaiphongnghi
    WHERE lpn.id = ? AND (lpnnn.id_ngonngu = ? OR lpnnn.id_ngonngu IS NULL)
";
$stmt = $conn->prepare($sql_room_detail);
$stmt->bind_param("ii", $room_id, $languageId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: danhsachphong.php");
    exit();
}

$room = $result->fetch_assoc();
$price_number = (int)str_replace('.', '', $room['price']);

// Lấy tiện ích của phòng
function getRoomAmenities($conn, $room_id, $languageId) {
    $sql_amenities = "
        SELECT tn.content
        FROM tienich_loaiphong tlp
        JOIN tienich_ngonngu tn ON tlp.id_tienich = tn.id_tienich
        WHERE tlp.id_loaiphong = ? AND tn.id_ngonngu = ?
    ";
    $stmt = $conn->prepare($sql_amenities);
    $stmt->bind_param("ii", $room_id, $languageId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $amenities = [];
    while($row = $result->fetch_assoc()) {
        $amenities[] = $row['content'];
    }
    return $amenities;
}

// Lấy bình luận của phòng
function getRoomReviews($conn, $room_id, $limit = 10) {
    $sql_reviews = "
        SELECT 
            bl.id,
            bl.content,
            bl.create_at,
            bl.rate,
            kh.name as customer_name
        FROM binhluan bl
        JOIN loaiphong_binhluan lpbl ON bl.id = lpbl.id_binhluan
        JOIN khachhang kh ON bl.id_khachhang = kh.id
        WHERE lpbl.id_loaiphong = ? AND bl.active = 1
        ORDER BY bl.create_at DESC
        LIMIT ?
    ";
    $stmt = $conn->prepare($sql_reviews);
    $stmt->bind_param("ii", $room_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $reviews = [];
    while($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    return $reviews;
}

// Lấy thống kê rating
function getRatingStats($conn, $room_id) {
    $sql_stats = "
        SELECT 
            COUNT(*) as total_reviews,
            AVG(bl.rate) as average_rating,
            SUM(CASE WHEN bl.rate = 5 THEN 1 ELSE 0 END) as five_star,
            SUM(CASE WHEN bl.rate = 4 THEN 1 ELSE 0 END) as four_star,
            SUM(CASE WHEN bl.rate = 3 THEN 1 ELSE 0 END) as three_star,
            SUM(CASE WHEN bl.rate = 2 THEN 1 ELSE 0 END) as two_star,
            SUM(CASE WHEN bl.rate = 1 THEN 1 ELSE 0 END) as one_star
        FROM binhluan bl
        JOIN loaiphong_binhluan lpbl ON bl.id = lpbl.id_binhluan
        WHERE lpbl.id_loaiphong = ? AND bl.active = 1
    ";
    $stmt = $conn->prepare($sql_stats);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

// Xử lý submit bình luận
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $reviewer_name = trim($_POST['reviewer-name']);
    $review_content = trim($_POST['review-content']);
    $rating = (int)$_POST['rating'];
    $reviewer_email = trim($_POST['reviewer-email'] ?? '');
    $reviewer_phone = trim($_POST['reviewer-phone'] ?? '');
    
    if (!empty($reviewer_name) && !empty($review_content) && $rating >= 1 && $rating <= 5) {
        $sql_check_customer = "SELECT id FROM khachhang WHERE name = ? LIMIT 1";
        $stmt_check = $conn->prepare($sql_check_customer);
        $stmt_check->bind_param("s", $reviewer_name);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $customer = $result_check->fetch_assoc();
            $customer_id = $customer['id'];
        } else {
            $sql_insert_customer = "INSERT INTO khachhang (name, email, phone) VALUES (?, ?, ?)";
            $stmt_customer = $conn->prepare($sql_insert_customer);
            $stmt_customer->bind_param("sss", $reviewer_name, $reviewer_email, $reviewer_phone);
            
            if ($stmt_customer->execute()) {
                $customer_id = $conn->insert_id;
            } else {
                $_SESSION['review_error'] = $text['booking_error_customer'];
                error_log("Lỗi khi chèn khách hàng: " . $conn->error);
                header("Location: chitietphong.php?room_id=" . $room_id);
                exit();
            }
        }
        
        if (isset($customer_id)) {
            $sql_insert_review = "INSERT INTO binhluan (content, create_at, rate, id_khachhang) VALUES (?, NOW(), ?, ?)";
            $stmt_review = $conn->prepare($sql_insert_review);
            $stmt_review->bind_param("sii", $review_content, $rating, $customer_id);
            
            if ($stmt_review->execute()) {
                $review_id = $conn->insert_id;
                
                $sql_link_review = "INSERT INTO loaiphong_binhluan (id_binhluan, id_loaiphong) VALUES (?, ?)";
                $stmt_link = $conn->prepare($sql_link_review);
                $stmt_link->bind_param("ii", $review_id, $room_id);
                
                if ($stmt_link->execute()) {
                    $_SESSION['review_success'] = $text['review_success'];
                    header("Location: chitietphong.php?room_id=" . $room_id);
                    exit();
                } else {
                    $_SESSION['review_error'] = $text['booking_error_create'];
                    error_log("Lỗi khi liên kết đánh giá: " . $conn->error);
                    header("Location: chitietphong.php?room_id=" . $room_id);
                    exit();
                }
            } else {
                $_SESSION['review_error'] = $text['booking_error_create'];
                error_log("Lỗi khi chèn đánh giá: " . $conn->error);
                header("Location: chitietphong.php?room_id=" . $room_id);
                exit();
            }
        }
    } else {
        $_SESSION['review_error'] = $text['review_error_fill'];
        header("Location: chitietphong.php?room_id=" . $room_id);
        exit();
    }
}

$amenities = getRoomAmenities($conn, $room_id, $languageId);
$reviews = getRoomReviews($conn, $room_id);
$rating_stats = getRatingStats($conn, $room_id);

// Tính phần trăm cho mỗi loại rating
$total_reviews = (int)$rating_stats['total_reviews'];
$rating_percentages = [];
if ($total_reviews > 0) {
    $rating_percentages[5] = round(($rating_stats['five_star'] / $total_reviews) * 100);
    $rating_percentages[4] = round(($rating_stats['four_star'] / $total_reviews) * 100);
    $rating_percentages[3] = round(($rating_stats['three_star'] / $total_reviews) * 100);
    $rating_percentages[2] = round(($rating_stats['two_star'] / $total_reviews) * 100);
    $rating_percentages[1] = round(($rating_stats['one_star'] / $total_reviews) * 100);
} else {
    $rating_percentages = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
}

$average_rating = $total_reviews > 0 ? round($rating_stats['average_rating'], 1) : 0;

// Lấy danh sách phòng khác
$sql_other_rooms = "
    SELECT 
        lpn.id,
        lpn.quantity,
        lpn.area,
        lpn.price,
        lpnnn.name,
        lpnnn.description
    FROM loaiphongnghi lpn
    LEFT JOIN loaiphongnghi_ngonngu lpnnn ON lpn.id = lpnnn.id_loaiphongnghi
    WHERE lpn.id != ? AND (lpnnn.id_ngonngu = ? OR lpnnn.id_ngonngu IS NULL)
    ORDER BY lpn.price ASC
    LIMIT 6
";
$stmt_other = $conn->prepare($sql_other_rooms);
$stmt_other->bind_param("ii", $room_id, $languageId);
$stmt_other->execute();
$result_other = $stmt_other->get_result();
$other_rooms = [];
while($row = $result_other->fetch_assoc()) {
    $row['images'] = getRoomImages($conn, $row['id']);
    $other_rooms[] = $row;
}

// Xử lý submit đặt phòng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_booking'])) {
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $adults = (int)$_POST['adults'];
    $children = (int)$_POST['children'];
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $special_requests = trim($_POST['special-requests'] ?? '');
    
    $checkin_date = new DateTime($checkin);
    $checkout_date = new DateTime($checkout);
    $today = new DateTime();
    $today->setTime(0, 0, 0);
    
    if ($adults < 1) {
        $_SESSION['booking_error'] = $text['booking_error_missing_info'];
        header("Location: chitietphong.php?room_id=" . $room_id);
        exit();
    } elseif ($children < 0) {
        $_SESSION['booking_error'] = $text['booking_error_missing_info'];
        header("Location: chitietphong.php?room_id=" . $room_id);
        exit();
    } elseif ($checkin_date < $today) {
        $_SESSION['booking_error'] = $text['booking_error_past_date'];
        header("Location: chitietphong.php?room_id=" . $room_id);
        exit();
    } elseif ($checkout_date <= $checkin_date) {
        $_SESSION['booking_error'] = $text['booking_error_invalid_dates'];
        header("Location: chitietphong.php?room_id=" . $room_id);
        exit();
    } elseif (empty($fullname) || empty($email) || empty($phone)) {
        $_SESSION['booking_error'] = $text['booking_error_missing_info'];
        header("Location: chitietphong.php?room_id=" . $room_id);
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['booking_error'] = $text['booking_error_invalid_email'];
        header("Location: chitietphong.php?room_id=" . $room_id);
        exit();
    } else {
        $nights = $checkin_date->diff($checkout_date)->days;
        $room_total = $price_number * $nights;
        $total_fee = $room_total; // Bỏ phí trẻ em
        
        $sql_check_availability = "
            SELECT p.id, p.room_number 
            FROM phongkhachsan p 
            WHERE p.id_loaiphong = ? 
            AND p.status = 'available'
            AND p.id NOT IN (
                SELECT DISTINCT dp.id_phong 
                FROM datphongkhachsan dp 
                WHERE dp.status IN ('confirmed', 'checked_in')
                AND (
                    (dp.time_come <= ? AND dp.time_leave > ?) OR
                    (dp.time_come < ? AND dp.time_leave >= ?) OR
                    (dp.time_come >= ? AND dp.time_come < ?)
                )
            )
            LIMIT 1
        ";
        
        $stmt_check_room = $conn->prepare($sql_check_availability);
        $checkin_datetime = $checkin . ' 14:00:00';
        $checkout_datetime = $checkout . ' 12:00:00';
        
        $stmt_check_room->bind_param("issssss", 
            $room_id, 
            $checkin_datetime, $checkin_datetime,
            $checkout_datetime, $checkout_datetime,
            $checkin_datetime, $checkout_datetime
        );
        $stmt_check_room->execute();
        $available_room = $stmt_check_room->get_result()->fetch_assoc();
        
        if (!$available_room) {
            $_SESSION['booking_error'] = $text['booking_error_no_room'];
            header("Location: chitietphong.php?room_id=" . $room_id);
            exit();
        } else {
            $sql_check_customer = "SELECT id FROM khachhang WHERE email = ? LIMIT 1";
            $stmt_check_customer = $conn->prepare($sql_check_customer);
            $stmt_check_customer->bind_param("s", $email);
            $stmt_check_customer->execute();
            $result_customer = $stmt_check_customer->get_result();
            
            if ($result_customer->num_rows > 0) {
                $customer = $result_customer->fetch_assoc();
                $customer_id = $customer['id'];
                
                $sql_update_customer = "UPDATE khachhang SET name = ?, phone = ? WHERE id = ?";
                $stmt_update_customer = $conn->prepare($sql_update_customer);
                $stmt_update_customer->bind_param("ssi", $fullname, $phone, $customer_id);
                $stmt_update_customer->execute();
            } else {
                $sql_insert_customer = "INSERT INTO khachhang (name, phone, email) VALUES (?, ?, ?)";
                $stmt_insert_customer = $conn->prepare($sql_insert_customer);
                $stmt_insert_customer->bind_param("sss", $fullname, $phone, $email);
                
                if ($stmt_insert_customer->execute()) {
                    $customer_id = $conn->insert_id;
                } else {
                    $_SESSION['booking_error'] = $text['booking_error_customer'];
                    error_log("Lỗi khi chèn khách hàng: " . $conn->error);
                    header("Location: chitietphong.php?room_id=" . $room_id);
                    exit();
                }
            }
            
            if (isset($customer_id)) {
                $deposit_fee = $total_fee * 0.3;
                $status = 'pending';
                
                $sql_insert_booking = "
                    INSERT INTO datphongkhachsan 
                    (time_come, time_leave, number_adult, number_children, note, total_fee, status, deposit_fee, id_phong, id_khachhang) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ";
                
                $stmt_insert_booking = $conn->prepare($sql_insert_booking);
                $total_fee_str = number_format($total_fee, 0, '.', '');
                $deposit_fee_str = number_format($deposit_fee, 0, '.', '');
                
                $stmt_insert_booking->bind_param("ssiissssii", 
                    $checkin_datetime, $checkout_datetime, 
                    $adults, $children, 
                    $special_requests, $total_fee_str, 
                    $status, $deposit_fee_str, 
                    $available_room['id'], $customer_id
                );
                
                if ($stmt_insert_booking->execute()) {
                    $booking_id = $conn->insert_id;
                    
                    // $sql_update_room_status = "UPDATE phongkhachsan SET status = 'reserved' WHERE id = ?";
                    // $stmt_update_room = $conn->prepare($sql_update_room_status);
                    // $stmt_update_room->bind_param("i", $available_room['id']);
                    // $stmt_update_room->execute();
                    
                    $_SESSION['booking_success'] = $text['booking_success'] . str_pad($booking_id, 6, '0', STR_PAD_LEFT);
                    header("Location: chitietphong.php?room_id=" . $room_id);
                    exit();
                } else {
                    $_SESSION['booking_error'] = $text['booking_error_create'];
                    error_log("Lỗi khi chèn đặt phòng: " . $conn->error);
                    header("Location: chitietphong.php?room_id=" . $room_id);
                    exit();
                }
            }
        }
    }
}

// Function để hiển thị sao
function displayStars($rating, $class = 'fas') {
    $stars = '';
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5;
    
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $full_stars) {
            $stars .= '<i class="fas fa-star"></i>';
        } elseif ($i == $full_stars + 1 && $half_star) {
            $stars .= '<i class="fas fa-star-half-alt"></i>';
        } else {
            $stars .= '<i class="far fa-star"></i>';
        }
    }
    return $stars;
}

// Function để format thời gian
function formatDateTime($datetime) {
    $date = new DateTime($datetime);
    return $date->format('d/m/Y');
}
?>

<!DOCTYPE html>
<html lang="<?php echo $languageId == 1 ? 'vi' : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($room['name'] ?? $text['no_name_room']); ?> - The Liberty Lào Cai</title>
    <link rel="stylesheet" href="/libertylaocai/view/css/chitietphong.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include "header.php"; ?>

    <!-- Hiển thị thông báo -->
    <?php if ($review_success): ?>
    <div class="alert alert-success" id="review-success-<?php echo time(); ?>" style="position: fixed; top: 20px; right: 20px; z-index: 1000; background: #4CAF50; color: white; padding: 15px; border-radius: 5px; display: flex; align-items: center; gap: 10px;">
        <?php echo htmlspecialchars($review_success); ?>
        <button onclick="closeAlert('review-success-<?php echo time(); ?>')" style="background: none; border: none; color: white; font-size: 16px; cursor: pointer;">×</button>
    </div>
    <?php endif; ?>

    <?php if ($review_error): ?>
    <div class="alert alert-error" id="review-error-<?php echo time(); ?>" style="position: fixed; top: 20px; right: 20px; z-index: 1000; background: #f44336; color: white; padding: 15px; border-radius: 5px; display: flex; align-items: center; gap: 10px;">
        <?php echo htmlspecialchars($review_error); ?>
        <button onclick="closeAlert('review-error-<?php echo time(); ?>')" style="background: none; border: none; color: white; font-size: 16px; cursor: pointer;">×</button>
    </div>
    <?php endif; ?>

    <?php if ($booking_success): ?>
    <div class="alert alert-success" id="booking-success-<?php echo time(); ?>" style="position: fixed; top: 20px; right: 20px; z-index: 1000; background: #4CAF50; color: white; padding: 15px; border-radius: 5px; display: flex; align-items: center; gap: 10px;">
        <?php echo htmlspecialchars($booking_success); ?>
        <button onclick="closeAlert('booking-success-<?php echo time(); ?>')" style="background: none; border: none; color: white; font-size: 16px; cursor: pointer;">×</button>
    </div>
    <?php endif; ?>

    <?php if ($booking_error): ?>
    <div class="alert alert-error" id="booking-error-<?php echo time(); ?>" style="position: fixed; top: 20px; right: 20px; z-index: 1000; background: #f44336; color: white; padding: 15px; border-radius: 5px; display: flex; align-items: center; gap: 10px;">
        <?php echo htmlspecialchars($booking_error); ?>
        <button onclick="closeAlert('booking-error-<?php echo time(); ?>')" style="background: none; border: none; color: white; font-size: 16px; cursor: pointer;">×</button>
    </div>
    <?php endif; ?>

    <div class="big-container">
        <!-- Banner Slider -->
        <section class="banner-slider">
            <div class="slider-container">
                <?php foreach($images as $index => $image): ?>
                <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
                    <img src="<?php echo (strpos($image, 'http') === 0) ? $image : '/libertylaocai/view/img/' . htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($room['name'] ?? $text['no_name_room']); ?> <?php echo $index + 1; ?>">
                </div>
                <?php endforeach; ?>
            </div>
            <button class="slider-btn prev-btn" onclick="prevSlide()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="slider-btn next-btn" onclick="nextSlide()">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="slider-dots">
                <?php foreach($images as $index => $image): ?>
                <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $index + 1; ?>)"></span>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Tab Navigation -->
        <nav class="tab-navigation">
            <div class="container">
                <button class="tab-btn active" data-tab="description"><?php echo $text['description_tab']; ?></button>
                <button class="tab-btn" data-tab="booking"><?php echo $text['booking_tab']; ?></button>
            </div>
        </nav>

        <!-- Tab Content -->
        <div class="container">
            <!-- Description Tab -->
            <div id="description" class="tab-content active">
                <div class="room-info-grid">
                    <div class="room-details">
                        <h1><?php echo htmlspecialchars($room['name'] ?? $text['no_name_room']); ?></h1>
                        <div class="room-price">
                            <span class="current-price"><?php echo number_format($price_number); ?> <?php echo $languageId == 1 ? 'VNĐ' : 'VND'; ?></span>
                            <span class="price-unit"><?php echo $text['price_per_night']; ?></span>
                        </div>
                        
                        <div class="room-specs">
                            <div class="spec-item">
                                <i class="fas fa-expand-arrows-alt"></i>
                                <span><?php echo $text['area_label']; ?> <?php echo $room['area']; ?>m²</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-bed"></i>
                                <span><?php echo $text['quantity_label']; ?> <?php echo $room['quantity']; ?></span>
                            </div>
                        </div>

                        <div class="room-description">
                            <p><?php echo htmlspecialchars($room['description'] ?? $text['no_name_room']); ?></p>
                        </div>

                        <?php if(!empty($amenities)): ?>
                        <div class="amenities">
                            <h3><?php echo $text['amenities_title']; ?></h3>
                            <div class="amenities-grid">
                                <?php foreach($amenities as $amenity): ?>
                                <div class="amenity-item">
                                    <i class="fas fa-check"></i>
                                    <span><?php echo htmlspecialchars($amenity); ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="included-services">
                            <h3><?php echo $text['included_services_title']; ?></h3>
                            <ul>
                                <li><i class="fas fa-check"></i> <?php echo $text['included_service_1']; ?></li>
                                <li><i class="fas fa-check"></i> <?php echo $text['included_service_2']; ?></li>
                                <li><i class="fas fa-check"></i> <?php echo $text['included_service_3']; ?></li>
                                <li><i class="fas fa-check"></i> <?php echo $text['included_service_4']; ?></li>
                            </ul>
                        </div>
                    </div>

                    <div class="booking-card">
                        <div class="price-summary">
                            <h3><?php echo $text['price_summary_title']; ?></h3>
                            <div class="price-breakdown">
                                <div class="price-row">
                                    <span><?php echo htmlspecialchars($room['name'] ?? $text['no_name_room']); ?></span>
                                    <span><?php echo number_format($price_number); ?> <?php echo $languageId == 1 ? 'VNĐ' : 'VND'; ?></span>
                                </div>
                                <div class="price-row">
                                    <span><?php echo $text['taxes_fees']; ?></span>
                                    <span><?php echo $text['included']; ?></span>
                                </div>
                                <div class="price-total">
                                    <span><?php echo $text['total_per_night']; ?></span>
                                    <span><?php echo number_format($price_number); ?> <?php echo $languageId == 1 ? 'VNĐ' : 'VND'; ?></span>
                                </div>
                            </div>
                        </div>
                        <button class="book-now-btn" onclick="switchTab('booking')">
                            <?php echo $text['book_now']; ?>
                        </button>
                    </div>
                </div>

                <!-- Other Rooms Section -->
                <?php if(!empty($other_rooms)): ?>
                <div class="other-rooms">
                    <h2><?php echo $text['other_rooms_title']; ?></h2>
                    <div class="room-slider-container">
                        <button class="room-nav-btn room-nav-prev" onclick="prevRoomSlide()">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div class="rooms-grid-wrapper">
                            <div class="rooms-grid">
                                <?php foreach($other_rooms as $other_room): 
                                    $other_price = (int)str_replace('.', '', $other_room['price']);
                                    $image = !empty($other_room['images']) ? $other_room['images'][0] : 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
                                ?>
                                <div class="room-card" data-price="<?php echo $other_price; ?>" data-room-id="<?php echo $other_room['id']; ?>">
                                    <img src="<?php echo (strpos($image, 'http') === 0) ? $image : '/libertylaocai/view/img/' . htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($other_room['name'] ?? $text['no_name_room']); ?>">
                                    <div class="room-card-content">
                                        <h3>
                                            <a href="/libertylaocai/view/php/chitietphong.php?room_id=<?php echo $other_room['id']; ?>">
                                                <?php echo htmlspecialchars($other_room['name'] ?? $text['no_name_room']); ?>
                                            </a>
                                        </h3>
                                        <p><?php echo $other_room['area']; ?>m² • <?php echo $other_room['quantity']; ?> <?php echo $text['quantity_label']; ?></p>
                                        <div class="room-card-price"><?php echo number_format($other_price); ?> <?php echo $languageId == 1 ? 'VNĐ' : 'VND'; ?><?php echo $text['price_per_night']; ?></div>
                                        <a href="/libertylaocai/view/php/chitietphong.php?room_id=<?php echo $other_room['id']; ?>" class="view-room-btn"><?php echo $text['view_details']; ?></a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button class="room-nav-btn room-nav-next" onclick="nextRoomSlide()">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Reviews Section -->
                <div class="reviews-section">
                    <div class="reviews-header">
                        <h3><?php echo $text['reviews_title']; ?></h3>
                        <div class="overall-rating">
                            <div class="rating-score"><?php echo $average_rating; ?></div>
                            <div class="rating-stars">
                                <?php echo displayStars($average_rating); ?>
                            </div>
                            <div class="rating-count">(<?php echo $total_reviews; ?> <?php echo $languageId == 1 ? 'đánh giá' : 'reviews'; ?>)</div>
                        </div>
                    </div>

                    <?php if ($total_reviews > 0): ?>
                    <div class="rating-breakdown">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                        <div class="rating-bar">
                            <span class="rating-label"><?php echo $i; ?> <?php echo $languageId == 1 ? 'sao' : 'stars'; ?></span>
                            <div class="bar-container">
                                <div class="bar-fill" style="width: <?php echo $rating_percentages[$i]; ?>%"></div>
                            </div>
                            <span class="rating-percent"><?php echo $rating_percentages[$i]; ?>%</span>
                        </div>
                        <?php endfor; ?>
                    </div>

                    <div class="reviews-list">
                        <?php foreach($reviews as $review): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="reviewer-details">
                                        <div class="reviewer-name"><?php echo htmlspecialchars($review['customer_name']); ?></div>
                                        <div class="review-date"><?php echo formatDateTime($review['create_at']); ?></div>
                                    </div>
                                </div>
                                <div class="review-rating">
                                    <?php echo displayStars($review['rate']); ?>
                                </div>
                            </div>
                            <div class="review-content">
                                <p><?php echo htmlspecialchars($review['content']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="no-reviews">
                        <p><?php echo $text['no_reviews']; ?></p>
                    </div>
                    <?php endif; ?>

                    <div class="write-review-section">
                        <button class="write-review-btn" onclick="toggleReviewForm()">
                            <i class="fas fa-pen"></i>
                            <?php echo $text['write_review']; ?>
                        </button>
                        
                        <div class="review-form-container" id="reviewForm" style="display: none;">
                            <h4><?php echo $text['share_experience']; ?></h4>
                            <form class="review-form" method="POST" action="">
                                <input type="hidden" name="submit_review" value="1">
                                <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                                
                                <div class="rating-input">
                                    <label><?php echo $text['rating_label']; ?></label>
                                    <div class="star-rating">
                                        <input type="radio" id="star5" name="rating" value="5">
                                        <label for="star5" class="star"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star4" name="rating" value="4">
                                        <label for="star4" class="star"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star3" name="rating" value="3">
                                        <label for="star3" class="star"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star2" name="rating" value="2">
                                        <label for="star2" class="star"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star1" name="rating" value="1">
                                        <label for="star1" class="star"><i class="fas fa-star"></i></label>
                                    </div>
                                    <span class="rating-text"><?php echo $text['select_stars']; ?></span>
                                </div>
                                
                                <div class="form-group">
                                    <label for="reviewer-name"><?php echo $text['name_label']; ?></label>
                                    <input type="text" id="reviewer-name" name="reviewer-name" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="reviewer-email"><?php echo $text['email_label']; ?></label>
                                    <input type="email" id="reviewer-email" name="reviewer-email">
                                </div>
                                
                                <div class="form-group">
                                    <label for="reviewer-phone"><?php echo $text['phone_label']; ?></label>
                                    <input type="tel" id="reviewer-phone" name="reviewer-phone">
                                </div>
                                
                                <div class="form-group">
                                    <label for="review-content"><?php echo $text['review_content_label']; ?></label>
                                    <textarea id="review-content" name="review-content" rows="4" 
                                            placeholder="<?php echo $text['review_content_placeholder']; ?>" required></textarea>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="button" class="cancel-btn" onclick="toggleReviewForm()">
                                        <?php echo $text['cancel']; ?>
                                    </button>
                                    <button type="submit" class="submit-review-btn">
                                        <i class="fas fa-paper-plane"></i>
                                        <?php echo $text['submit_review']; ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Tab -->
            <div id="booking" class="tab-content">
                <div class="booking-form-container">
                    <form class="booking-form" method="POST" action="">
                        <input type="hidden" name="submit_booking" value="1">
                        <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                        <input type="hidden" id="room-price" name="room-price" value="<?php echo htmlspecialchars((int)$price_number); ?>">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="checkin"><?php echo $text['checkin_label']; ?></label>
                                <input type="date" id="checkin" name="checkin" required value="<?php echo isset($_POST['checkin']) ? htmlspecialchars($_POST['checkin']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="checkout"><?php echo $text['checkout_label']; ?></label>
                                <input type="date" id="checkout" name="checkout" required value="<?php echo isset($_POST['checkout']) ? htmlspecialchars($_POST['checkout']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="adults"><?php echo $text['adults_label']; ?></label>
                                <input type="number" id="adults" name="adults" min="1" max="10" 
                                    value="<?php echo isset($_POST['adults']) ? htmlspecialchars($_POST['adults']) : '2'; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="children"><?php echo $text['children_label']; ?></label>
                                <input type="number" id="children" name="children" min="0" max="10" 
                                    value="<?php echo isset($_POST['children']) ? htmlspecialchars($_POST['children']) : '0'; ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fullname"><?php echo $text['fullname_label']; ?></label>
                            <input type="text" id="fullname" name="fullname" required value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="email"><?php echo $text['email_booking_label']; ?></label>
                                <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="phone"><?php echo $text['phone_booking_label']; ?></label>
                                <input type="tel" id="phone" name="phone" required value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="special-requests"><?php echo $text['special_requests_label']; ?></label>
                            <textarea id="special-requests" name="special-requests" rows="3" placeholder="<?php echo $text['special_requests_placeholder']; ?>"><?php echo isset($_POST['special-requests']) ? htmlspecialchars($_POST['special-requests']) : ''; ?></textarea>
                        </div>

                        <div class="booking-summary">
                            <h3><?php echo $text['booking_summary_title']; ?></h3>
                            <div class="summary-item">
                                <span><?php echo $text['room_type_label']; ?></span>
                                <span><?php echo htmlspecialchars($room['name'] ?? $text['no_name_room']); ?></span>
                            </div>
                            <div class="summary-item">
                                <span><?php echo $text['nights_label']; ?></span>
                                <span id="nights-count">0 <?php echo $languageId == 1 ? 'đêm' : 'nights'; ?></span>
                            </div>
                            <div class="summary-item">
                                <span><?php echo $text['room_price_label']; ?></span>
                                <span id="room-price"><?php echo number_format($price_number); ?> <?php echo $languageId == 1 ? 'VNĐ' : 'VND'; ?><?php echo $text['price_per_night']; ?></span>
                            </div>
                            <div class="summary-item">
                                <span><?php echo $text['children_fee_label']; ?></span>
                                <span id="children-fee">0 <?php echo $languageId == 1 ? 'VNĐ' : 'VND'; ?></span>
                            </div>
                            <div class="summary-total">
                                <span><?php echo $text['total_label']; ?></span>
                                <span id="total-price">0 <?php echo $languageId == 1 ? 'VNĐ' : 'VND'; ?></span>
                            </div>
                        </div>

                        <div class="terms-checkbox">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms"><?php echo $text['terms_label']; ?></label>
                        </div>

                        <button type="submit" class="submit-booking-btn">
                            <i class="fas fa-calendar-check"></i>
                            <?php echo $text['confirm_booking']; ?>
                        </button>
                    </form>
                </div>

                <div class="booking-info">
                    <h3><?php echo $text['important_info_title']; ?></h3>
                    <div class="info-list">
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong><?php echo $text['checkin_checkout_info']; ?></strong>
                                <p><?php echo $text['checkin_checkout_details']; ?></p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-baby"></i>
                            <div>
                                <strong><?php echo $text['children_policy']; ?></strong>
                                <p><?php echo $text['children_policy_details']; ?></p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-ban"></i>
                            <div>
                                <strong><?php echo $text['cancellation_policy']; ?></strong>
                                <p><?php echo $text['cancellation_policy_details']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="/libertylaocai/view/js/chitietphong.js"></script>
    <script>
        const roomPrice = <?php echo isset($price_number) ? json_encode((int)$price_number) : '0'; ?>;
    </script>
</body>
</html>