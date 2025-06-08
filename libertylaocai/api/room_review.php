<?php
// Bật ghi log lỗi
ob_clean();
ini_set('display_errors', 0); // Tắt hiển thị lỗi cho người dùng
ini_set('log_errors', 1); // Bật ghi log lỗi
ini_set('error_log', 'php_errors.log'); // Đường dẫn tệp log
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once "../model/UserModel.php";

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$id_loaiphong = $_GET['room_id'] ? : '';

$reviews = getRoomReviews($id_loaiphong, $page, $limit); // Hàm giả định lấy đánh giá từ database
$totalReviews = getTotalRoomReviews($id_loaiphong); // Hàm giả định lấy tổng số đánh giá
$totalRating = calculateRoomAverageRating($id_loaiphong); // Hàm giả định tính điểm trung bình
$ratingBreakdown = calculateRoomRatingBreakdown($id_loaiphong); // Hàm giả định tính phân bố sao

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
