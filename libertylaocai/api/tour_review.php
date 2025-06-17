<?php
ini_set('display_errors', 0); // Tắt hiển thị lỗi trên màn hình
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
header('Content-Type: application/json');

require_once "../model/UserModel.php";

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$id_type = $_GET['id_service'] ? : '';

$reviews = getServiceReviews($id_type, $page, $limit); // Hàm giả định lấy đánh giá từ database
$totalReviews = getTotalServiceReviews($id_type); // Hàm giả định lấy tổng số đánh giá
$totalRating =  calculateServiceAverageRating($id_type); // Hàm giả định tính điểm trung bình
$ratingBreakdown = calculateServiceRatingBreakdown($id_type); // Hàm giả định tính phân bố sao

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
