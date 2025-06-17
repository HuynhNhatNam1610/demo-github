<?php
require_once "connect.php";
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? intval($input['id']) : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
    exit;
}

try {
    // Bắt đầu giao dịch
    mysqli_begin_transaction($conn);

    // Xóa bản ghi trong gioithieu_ngonngu
    $query = "DELETE FROM gioithieu_ngonngu WHERE id_gioithieu = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Lỗi xóa gioithieu_ngonngu: " . mysqli_error($conn));
    }
    mysqli_stmt_close($stmt);

    // Xóa bản ghi trong gioithieu
    $query = "DELETE FROM gioithieu WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Lỗi xóa gioithieu: " . mysqli_error($conn));
    }
    mysqli_stmt_close($stmt);

    // Cam kết giao dịch
    mysqli_commit($conn);

    // Kiểm tra thành công dựa trên việc giao dịch hoàn tất
    $response = ['success' => true];
} catch (Exception $e) {
    // Hoàn tác giao dịch
    mysqli_rollback($conn);
    $response = [
        'success' => false,
        'message' => "Lỗi: " . $e->getMessage()
    ];
}

echo json_encode($response);
mysqli_close($conn);
