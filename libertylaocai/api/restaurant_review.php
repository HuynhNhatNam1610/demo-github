<?php
header('Content-Type: application/json');

require_once "../model/UserModel.php";

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$offset = ($page - 1) * $limit;

// Lấy danh sách bình luận cho trang hiện tại
$reviews = getRestaurantReviews($limit, $offset);
$totalReviews = getTotalRestaurantReviews();
$totalPages = ceil($totalReviews / $limit);

// Lấy toàn bộ dữ liệu để tính tổng điểm và phân bố sao
$allReviews = getAllRestaurantReviews(); // Hàm mới để lấy tất cả bình luận
$totalRating = $totalReviews ? array_sum(array_column($allReviews, 'rate')) / $totalReviews : 0;
$ratingBreakdown = array_count_values(array_column($allReviews, 'rate'));

// Chuẩn bị dữ liệu trả về
$response = [
    'reviews' => $reviews, // Chỉ hiển thị bình luận của trang hiện tại
    'totalReviews' => $totalReviews,
    'totalRating' => number_format($totalRating, 1),
    'ratingBreakdown' => [],
    'currentPage' => $page,
    'totalPages' => $totalPages
];

// Tính tỷ lệ phần trăm cho từng mức sao
for ($i = 5; $i >= 1; $i--) {
    $count = isset($ratingBreakdown[$i]) ? $ratingBreakdown[$i] : 0;
    $percentage = $totalReviews ? ($count / $totalReviews * 100) : 0;
    $response['ratingBreakdown'][$i] = [
        'count' => $count,
        'percentage' => round($percentage)
    ];
}

echo json_encode($response);
