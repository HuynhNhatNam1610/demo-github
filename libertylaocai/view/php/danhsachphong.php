<?php
require_once "session.php";
require_once "../../model/UserModel.php";

// Kiểm tra ngôn ngữ từ session, mặc định là 1 (tiếng Việt)
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;

if (!empty($_SESSION['head_banner'])) {
    $getSelectedBanner = $_SESSION['head_banner'];
}

// Định nghĩa các text đa ngôn ngữ
$texts = [
    1 => [ // Tiếng Việt
        'page_title' => 'Danh Sách Phòng - The Liberty Lào Cai',
        'page_header' => 'DANH SÁCH PHÒNG',
        'breadcrumb' => 'Trang Chủ > Danh Sách Phòng',
        'filter_rooms' => 'Lọc phòng',
        'clear_all' => 'Xóa Tất Cả',
        'price' => 'Giá',
        'filter' => 'Lọc theo loại phòng',
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
        'filter' => 'Filter by room type',
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
$rooms = getRoomTypes($languageId);


$price_range = getPriceRange();
$min_price = $price_range['min_price'];
$max_price = $price_range['max_price'];
?>

<!DOCTYPE html>
<html lang="<?php echo $languageId == 1 ? 'vi' : 'en'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $languageId == 1 ? 'Danh Sách Phòng - Khách Sạn Liberty Lào Cai' : 'Room List - Liberty Hotel Lao Cai'; ?></title>
    <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
    <meta name="description" content="<?php echo $languageId == 1 ? 'Xem danh sách các loại phòng sang trọng tại khách sạn Liberty Lào Cai và đặt phòng với giá ưu đãi.' : 'View the list of luxurious room types at Liberty Hotel Lao Cai and book with exclusive offers.'; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/libertylaocai/view/css/danhsachphong.css">
</head>


<body>
    <?php include "header.php"; ?>
    <div class="danhsachphong-container">
        <div class="hero-content">
            <img src="/libertylaocai/view/img/<?php echo $getSelectedBanner['image']; ?>" alt="Banner Image" class="banner-image">
            <h1><?php echo $text['page_header']; ?></h1>
        </div>

        <div class="main-container">
            <div class="sidebar">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px;">
                    <h3><?php echo $text['filter_rooms']; ?></h3>
                    <span class="clear-all" onclick="clearAllFilters()"><?php echo $text['clear_all']; ?></span>
                </div>

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

                    <div style="margin-top: 20px; font-size: 18px; color: #003c32; font-weight: bold;">
                        <?php echo $text['filter']; ?>
                    </div>
                </div>

                <!-- Room Category Filter -->
                <div class="filter-section">
                    <div class="room-category">
                        <?php foreach ($rooms as $room): ?>
                            <label>
                                <input type="checkbox" name="room_category" value="<?php echo $room['id']; ?>">
                                <?php echo htmlspecialchars($room['name'] ?? $text['no_name_room']); ?>
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
                        </select>
                    </div>
                </div>

                <div class="room-highlight-section">
                    <?php
                    $room_counter = 0;
                    foreach ($rooms as $room):
                        $bedTypes = getBedTypesForRoom($room['id'], $languageId);
                        $amenities = getAmenitiesForRoom($room['id'], $languageId);
                        $images = getImagesForRoom($room['id']);
                        $room_counter++;
                        $room_class = ($room_counter % 2 == 0) ? 'room-block reverse' : 'room-block';
                        $price_number = (int)str_replace('.', '', $room['price']);
                    ?>

                        <div class="<?php echo $room_class; ?>" data-price="<?php echo $price_number; ?>" data-rating="0" data-room-id="<?php echo $room['id']; ?>">
                            <div class="room-info-box">
                                <!-- Thay đổi liên kết thành form POST -->
                                <h3>
                                    <?php echo htmlspecialchars($room['name'] ?? $text['no_name_room']); ?>
                                </h3>
                                <div class="room-price"><?php echo number_format($price_number, 0, '.', '.'); ?> VNĐ <span><?php echo $text['per_night']; ?></span></div>
                                <div class="room-specs">
                                    <p><strong><?php echo $text['area']; ?></strong> <?php echo $room['area']; ?> m²</p>
                                    <p><?php echo $languageId == 1 ? 'Loại giường' : 'Bed type'; ?>:
                                        <?php
                                        if (!empty($bedTypes)) {
                                            $bedList = [];
                                            foreach ($bedTypes as $bedType) {
                                                $bedList[] = $bedType['quantity'] . " - " . $bedType['name'];;
                                            }
                                            echo implode('; ', $bedList);
                                        } else {
                                            echo $languageId == 1 ? 'Không xác định' : 'Not specified';
                                        }
                                        ?>
                                    </p>
                                </div>

                                <?php if (!empty($amenities)): ?>
                                    <h4><?php echo $text['room_amenities']; ?></h4>
                                    <ul class="room-amenities">
                                        <?php foreach ($amenities as $amenity): ?>
                                            <li><i class="fas fa-check"></i> <?php echo htmlspecialchars($amenity); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>

                                <!-- Thay đổi nút đặt phòng thành form POST -->
                                <form action="/libertylaocai/user/submit" method="POST">
                                    <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                                    <input type="hidden" name="room_name" value="<?php echo $room['name']?>">
                                    <button type="submit" class="btn-booking"><?php echo $text['book_room']; ?></button>
                                </form>
                            </div>
                            <div class="room-image-box">
                                <div class="carousel-container">
                                    <div class="carousel-slides" id="room<?php echo $room['id']; ?>-slides">
                                        <?php foreach ($images as $index => $image): ?>
                                            <div class="carousel-slide">
                                                <img loading="lazy" src="<?php echo (strpos($image, 'http') === 0) ? $image : '/libertylaocai/view/img/' . htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($room['name'] ?? $text['no_name_room']); ?> <?php echo $index + 1; ?>">
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
            <?php foreach ($rooms as $room): ?>
                initCarousel('room<?php echo $room['id']; ?>');
            <?php endforeach; ?>
        });
    </script>
    <script>
        // Truyền languageId và các chuỗi văn bản từ PHP sang JavaScript
        const languageId = <?php echo json_encode($languageId); ?>;
        const texts = <?php echo json_encode($texts); ?>;
    </script>
</body>

</html>