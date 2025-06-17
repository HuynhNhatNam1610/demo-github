<?php
require_once 'connect.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Cho phép CORS nếu cần

// Đọc dữ liệu thô từ php://input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['id']) || !isset($data['type'])) {
    error_log('Missing parameters - Input: ' . $input); // Debug
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

$id = (int)$data['id'];
$type = $data['type'];

try {
    global $conn;

    // Xác định id_amthuc dựa trên type
    $id_amthuc = ($type === 'restaurant') ? 1 : 2;

    // Bắt đầu transaction
    mysqli_begin_transaction($conn);

    // Xóa bản ghi trong bảng ngôn ngữ (nếu có)
    $sql = "DELETE FROM `thucdon_ngonngu` WHERE `id_thucdon` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Lỗi khi xóa dữ liệu trong bảng ngôn ngữ');
    }
    mysqli_stmt_close($stmt);

    // Xóa bản ghi trong bảng ảnh (nếu có)
    $sql = "DELETE FROM `anhthucdon` WHERE `id_menu` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Lỗi khi xóa dữ liệu trong bảng ảnh');
    }
    mysqli_stmt_close($stmt);

    // Xóa bản ghi trong bảng chính
    $sql = "DELETE FROM `thucdon` WHERE `id` = ? AND `id_amthuc` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $id, $id_amthuc);
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
    error_log("Error deleting menu item: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}

mysqli_close($conn);
?>