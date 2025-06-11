<?php
require_once 'connect.php'; // Giả sử file này chứa kết nối database ($conn)

header('Content-Type: application/json');

// Nhận dữ liệu từ yêu cầu AJAX
$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? (int)$input['id'] : 0;
$type = isset($input['type']) ? $input['type'] : '';

if ($id <= 0 || empty($type)) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

// Xác định bảng dựa trên type
$table = '';
switch ($type) {
    case 'news':
        $table = 'tintuc';
        break;
    case 'offer':
        $table = 'uudai';
        break;
    case 'event':
        $table = 'sukiendatochuc';
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Loại bài viết không hợp lệ']);
        exit;
}

// Cập nhật trường active
$sql = "UPDATE `$table` SET `active` = 1 WHERE `id` = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật cơ sở dữ liệu']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>