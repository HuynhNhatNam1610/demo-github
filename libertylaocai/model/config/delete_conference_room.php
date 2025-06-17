<?php
require_once "connect.php";

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid request'];

try {
    // Đọc body JSON từ php://input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Kiểm tra id
    $id = isset($data['id']) ? (int)$data['id'] : 0;

    if (!$id || $id <= 0) {
        throw new Exception('ID hội trường không hợp lệ');
    }

    mysqli_begin_transaction($conn);

    // Kiểm tra xem hội trường có tồn tại không
    $sql = "SELECT id FROM hoitruong WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) === 0) {
        throw new Exception('Hội trường không tồn tại');
    }
    mysqli_stmt_close($stmt);

    // Xóa bản ghi trong hoitruong_ngonngu
    $sql = "DELETE FROM hoitruong_ngonngu WHERE id_hoitruong = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Lỗi khi xóa dữ liệu hoitruong_ngonngu');
    }
    mysqli_stmt_close($stmt);

    // Xóa bản ghi trong giathuehoitruong
    $sql = "DELETE FROM giathuehoitruong WHERE id_hoitruong = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Lỗi khi xóa dữ liệu giathuehoitruong');
    }
    mysqli_stmt_close($stmt);

    // Xóa bản ghi trong anhhoitruong
    $sql = "DELETE FROM anhhoitruong WHERE id_hoitruong = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Lỗi khi xóa dữ liệu anhhoitruong');
    }
    mysqli_stmt_close($stmt);

    // Xóa bản ghi trong hoitruong
    $sql = "DELETE FROM hoitruong WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Lỗi khi xóa hội trường');
    }
    mysqli_stmt_close($stmt);

    mysqli_commit($conn);
    $response['success'] = true;
    $response['message'] = 'Hội trường đã được xóa thành công';
} catch (Exception $e) {
    mysqli_rollback($conn);
    $response['message'] = 'Error: ' . $e->getMessage();
    error_log("Error in delete_conference_room.php: " . $e->getMessage());
}

echo json_encode($response);
mysqli_close($conn);
