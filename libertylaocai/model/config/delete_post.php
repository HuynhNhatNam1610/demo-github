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

// Xác định các bảng dựa trên type
$main_table = '';
$image_table = '';
$content_table = '';
switch ($type) {
    case 'news':
        $main_table = 'tintuc';
        $image_table = 'anhtintuc';
        $content_table = 'tintuc_ngonngu';
        break;
    case 'offer':
        $main_table = 'uudai';
        $image_table = 'anhuudai';
        $content_table = 'uudai_ngonngu';
        break;
    case 'event':
        $main_table = 'sukiendatochuc';
        $image_table = 'anhsukiendatochuc';
        $content_table = 'sukiendatochuc_ngonngu';
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Loại bài viết không hợp lệ']);
        exit;
}

// Bắt đầu transaction
mysqli_begin_transaction($conn);

try {
    // Xóa bản ghi trong bảng ngôn ngữ
    $sql = "DELETE FROM `$content_table` WHERE `id_{$main_table}` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Lỗi khi xóa dữ liệu trong bảng ngôn ngữ');
    }
    mysqli_stmt_close($stmt);

    // Xóa bản ghi trong bảng ảnh
    $sql = "DELETE FROM `$image_table` WHERE `id_{$main_table}` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Lỗi khi xóa dữ liệu trong bảng ảnh');
    }
    mysqli_stmt_close($stmt);

    // Xóa bản ghi trong bảng chính
    $sql = "DELETE FROM `$main_table` WHERE `id` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Lỗi khi xóa bài viết trong bảng chính');
    }
    mysqli_stmt_close($stmt);

    // Commit transaction
    mysqli_commit($conn);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback transaction nếu có lỗi
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}

mysqli_close($conn);
