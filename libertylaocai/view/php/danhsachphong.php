<?php
require_once "../../model/config/connect.php";
require_once "session.php";

// Kiểm tra ngôn ngữ từ session, mặc định là 1 (tiếng Việt)
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;

// Định nghĩa các text đa ngôn ngữ
$texts = [
    1 => [ // Tiếng Việt
        'page_title' => 'Danh Sách Phòng - The Liberty Lào Cai',
        'page_header' => 'DANH SÁCH PHÒNG',
        'breadcrumb' => 'Trang Chủ > Danh Sách Phòng',
        'filter_rooms' => 'Lọc phòng',
        'clear_all' => 'Xóa Tất Cả',
        'price' => 'Giá',
        'filter' => 'LỌC',
        'room_category' => 'Danh Mục Phòng',
        'showing_results' => 'Hiển thị',
        'results' => 'kết quả',
        'sort_by' => 'Sắp xếp theo:',
        'price_low_high' => 'Giá thấp đến cao',
        'price_high_low' => 'Giá cao đến thấp',
        'room_name' => 'Tên phòng',
        'room_size' => 'Diện tích',
        'per_night' => '/ Đêm',
        'area' => 'Diện tích:',
        'quantity' => 'Số lượng:',
        'rooms_available' => 'phòng còn trống',
        'description' => 'Mô tả:',
        'room_amenities' => 'Tiện ích phòng',
        'book_room' => 'ĐẶT PHÒNG',
        'no_name_room' => 'Phòng không tên'
    ],
    2 => [ // Tiếng Anh
        'page_title' => 'Room List - The Liberty Lao Cai',
        'page_header' => 'ROOM LIST',
        'breadcrumb' => 'Home > Room List',
        'filter_rooms' => 'Filter Rooms',
        'clear_all' => 'Clear All',
        'price' => 'Price',
        'filter' => 'FILTER',
        'room_category' => 'Room Category',
        'showing_results' => 'Showing',
        'results' => 'results',
        'sort_by' => 'Sort by:',
        'price_low_high' => 'Price low to high',
        'price_high_low' => 'Price high to low',
        'room_name' => 'Room name',
        'room_size' => 'Room size',
        'per_night' => '/ Night',
        'area' => 'Area:',
        'quantity' => 'Quantity:',
        'rooms_available' => 'rooms available',
        'description' => 'Description:',
        'room_amenities' => 'Room Amenities',
        'book_room' => 'BOOK ROOM',
        'no_name_room' => 'Unnamed Room'
    ]
];

$text = $texts[$languageId];

// Lấy dữ liệu loại phòng với ngôn ngữ
$sql_rooms = "
    SELECT 
        lpn.id,
        lpn.quantity,
        lpn.area,
        lpn.price,
        lpnnn.name,
        lpnnn.description
    FROM loaiphongnghi lpn
    LEFT JOIN loaiphongnghi_ngonngu lpnnn ON lpn.id = lpnnn.id_loaiphongnghi
    WHERE lpnnn.id_ngonngu = ? OR lpnnn.id_ngonngu IS NULL
    ORDER BY lpn.price ASC
";

$stmt_rooms = $conn->prepare($sql_rooms);
$stmt_rooms->bind_param("i", $languageId);
$stmt_rooms->execute();
$result_rooms = $stmt_rooms->get_result();

$rooms = [];
if ($result_rooms->num_rows > 0) {
    while($row = $result_rooms->fetch_assoc()) {
        $rooms[] = $row;
    }
}

// Lấy tiện ích cho từng loại phòng
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

// Lấy số phòng còn trống
function getAvailableRooms($conn, $room_id) {
    $sql = "SELECT COUNT(*) as available FROM phongkhachsan WHERE id_loaiphong = ? AND status = 'available'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['available'];
}

// Lấy hình ảnh cho từng loại phòng
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
    
    // Nếu không có hình ảnh, trả về danh sách hình ảnh mặc định
    if (empty($images)) {
        $images = [
            'https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
        ];
    }
    
    return $images;
}

// Lấy giá min/max để thiết lập slider
$price_range = $conn->query("SELECT MIN(CAST(REPLACE(price, '.', '') AS UNSIGNED)) as min_price, MAX(CAST(REPLACE(price, '.', '') AS UNSIGNED)) as max_price FROM loaiphongnghi");
$price_data = $price_range->fetch_assoc();
$min_price = $price_data['min_price'] ?? 500000;
$max_price = $price_data['max_price'] ?? 3000000;
?>

<!DOCTYPE html>
<html lang="<?php echo $languageId == 1 ? 'vi' : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $text['page_title']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/libertylaocai/view/css/danhsachphong.css">
</head>
<body>
<?php include "header.php"; ?>
<div class="danhsachphong-container">
    <div class="hero-content" style="background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://thewesternhill.com/storage/anh-moi-t6/phong-nghi/deluxe-family/deluxe-family-5.jpg'); background-size: cover; background-position: center; height: 300px;">
        <h1><?php echo $text['page_header']; ?></h1>
        <div class="breadcrumb"><?php echo $text['breadcrumb']; ?></div>
    </div>
   
    <div class="main-container">
        <!-- Sidebar Filter -->
        <div class="sidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px;">
                <h3><?php echo $text['filter_rooms']; ?></h3>
                <span class="clear-all" onclick="clearAllFilters()"><?php echo $text['clear_all']; ?></span>
            </div>

            <!-- Price Filter Section -->
            <div class="filter-section">
                <div class="filter-title">
                    <i class="fas fa-dollar-sign"></i>
                    <?php echo $text['price']; ?>
                </div>
                <div class="price-range">
                    <div class="price-inputs">
                        <input type="text" class="price-input" id="minPrice" value="<?php echo number_format($min_price); ?> <?php echo $languageId == 1 ? 'đ' : 'VND'; ?>" readonly>
                        <input type="text" class="price-input" id="maxPrice" value="<?php echo number_format($max_price); ?> <?php echo $languageId == 1 ? 'đ' : 'VND'; ?>" readonly>
                    </div>
                    <div class="range-slider">
                        <input type="range" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" value="<?php echo $min_price; ?>" class="slider" id="minRange">
                        <input type="range" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" value="<?php echo $max_price; ?>" class="slider" id="maxRange">
                    </div>
                    <div class="price-display" id="priceDisplay"><?php echo number_format($min_price); ?> <?php echo $languageId == 1 ? 'đ' : 'VND'; ?> - <?php echo number_format($max_price); ?> <?php echo $languageId == 1 ? 'đ' : 'VND'; ?></div>
                </div>

                <div style="margin-top: 20px; font-size: 14px; color: #cbb69d; font-weight: bold;">
                    <?php echo $text['filter']; ?>
                </div>
            </div>

            <!-- Room Category Filter -->
            <div class="filter-section">
                <div class="filter-title">
                    <span><?php echo $text['room_category']; ?></span>
                </div>
                <div class="room-category">
                <?php foreach($rooms as $room): ?>
                    <label>
                        <input type="checkbox" name="room_category" value="<?php echo $room['id']; ?>"> 
                        <?php echo htmlspecialchars($room['name'] ?? $text['no_name_room']); ?> 
                        (<?php echo getAvailableRooms($conn, $room['id']); ?>)
                    </label><br>
                <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Room Content -->
        <div class="room-content">
            <div class="results-header">
                <div class="results-count"><?php echo $text['showing_results']; ?> <?php echo count($rooms); ?> <?php echo $text['results']; ?></div>
                <div class="sort-options">
                    <label for="sortSelect"><?php echo $text['sort_by']; ?></label>
                    <select id="sortSelect" class="sort-select" onchange="sortRooms()">
                        <option value="price-low"><?php echo $text['price_low_high']; ?></option>
                        <option value="price-high"><?php echo $text['price_high_low']; ?></option>
                        <option value="name"><?php echo $text['room_name']; ?></option>
                        <option value="size"><?php echo $text['room_size']; ?></option>
                    </select>
                </div>
            </div>

            <div class="room-highlight-section">
                <?php 
                $room_counter = 0;
                foreach($rooms as $room): 
                    $amenities = getRoomAmenities($conn, $room['id'], $languageId);
                    $images = getRoomImages($conn, $room['id']);
                    $room_counter++;
                    $room_class = ($room_counter % 2 == 0) ? 'room-block reverse' : 'room-block';
                    $price_number = (int)str_replace('.', '', $room['price']);
                ?>
                
                <div class="<?php echo $room_class; ?>" data-price="<?php echo $price_number; ?>" data-rating="0" data-room-id="<?php echo $room['id']; ?>">
                    <div class="room-info-box">
                        <!-- Thay đổi liên kết thành form POST -->
                        <h3>
                            <form action="view/php/chitietphong.php" method="POST" style="display: inline;">
                                <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                                <a href="javascript:void(0)" onclick="this.closest('form').submit();">
                                    <?php echo htmlspecialchars($room['name'] ?? $text['no_name_room']); ?>
                                </a>
                            </form>
                        </h3>
                        <div class="room-price"><?php echo number_format($price_number); ?> VNĐ <span><?php echo $text['per_night']; ?></span></div>
                        <div class="room-specs">
                            <p><strong><?php echo $text['area']; ?></strong> <?php echo $room['area']; ?> m²</p>
                            <p><strong><?php echo $text['quantity']; ?></strong> <?php echo getAvailableRooms($conn, $room['id']); ?> <?php echo $text['rooms_available']; ?></p>
                            <p><strong><?php echo $text['description']; ?></strong> <?php echo htmlspecialchars(substr($room['description'] ?? '', 0, 100)); ?>...</p>
                        </div>
                        
                        <?php if(!empty($amenities)): ?>
                        <h4><?php echo $text['room_amenities']; ?></h4>
                        <ul class="room-amenities">
                            <?php foreach($amenities as $amenity): ?>
                            <li><i class="fas fa-check"></i> <?php echo htmlspecialchars($amenity); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                        
                        <!-- Thay đổi nút đặt phòng thành form POST -->
                        <form action="view/php/chitietphong.php" method="POST">
                            <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                            <button type="submit" class="btn-booking"><?php echo $text['book_room']; ?></button>
                        </form>
                    </div>
                    <div class="room-image-box">
                        <div class="carousel-container">
                            <div class="carousel-slides" id="room<?php echo $room['id']; ?>-slides">
                                <?php foreach($images as $index => $image): ?>
                                <div class="carousel-slide">
                                    <img src="<?php echo (strpos($image, 'http') === 0) ? $image : '/libertylaocai/view/img/' . htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($room['name'] ?? $text['no_name_room']); ?> <?php echo $index + 1; ?>">
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="carousel-controls">
                                <button class="carousel-btn" onclick="changeSlide('room<?php echo $room['id']; ?>', -1)">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <div class="carousel-dots" id="room<?php echo $room['id']; ?>-dots"></div>
                                <span class="carousel-counter" id="room<?php echo $room['id']; ?>-counter"></span>
                                <button class="carousel-btn" onclick="changeSlide('room<?php echo $room['id']; ?>', 1)">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php" ?>

<script src="/libertylaocai/view/js/danhsachphong.js"></script>
<script>
// Cập nhật price range slider với dữ liệu thực
document.addEventListener('DOMContentLoaded', function() {
    const minPrice = <?php echo $min_price; ?>;
    const maxPrice = <?php echo $max_price; ?>;
    
    // Initialize carousel for all rooms
    <?php foreach($rooms as $room): ?>
    initCarousel('room<?php echo $room['id']; ?>');
    <?php endforeach; ?>
});
</script>
</body>
</html>