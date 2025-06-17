<?php

header('Content-Type: application/json');

require_once "../model/UserModel.php";

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

$reviews = getBarReviews($page, $limit); // Hàm giả định lấy đánh giá từ database
$totalReviews = getTotalBarReviews(); // Hàm giả định lấy tổng số đánh giá
$totalRating = calculateBarAverageRating(); // Hàm giả định tính điểm trung bình
$ratingBreakdown = calculateBarRatingBreakdown(); // Hàm giả định tính phân bố sao

$response = [
    'status' => 'success',
    'currentPage' => $page,
    'totalPages' => ceil($totalReviews / $limit),
    'totalReviews' => $totalReviews,
    'totalRating' => number_format($totalRating, 1),
    'ratingBreakdown' => $ratingBreakdown,
    'reviews' => $reviews
];


echo json_encode($response);
