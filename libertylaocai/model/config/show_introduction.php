<?php
require_once "connect.php";

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? intval($input['id']) : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
    exit;
}

try {
    $query = "UPDATE gioithieu SET active = 1 WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        throw new Exception("Lỗi truy vấn: " . mysqli_error($conn));
    }

    if (mysqli_affected_rows($conn) > 0) {
        $response = ['success' => true];
    } else {
        $response = ['success' => false, 'message' => 'Không tìm thấy mục giới thiệu'];
    }
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => "Lỗi: " . $e->getMessage()
    ];
}

echo json_encode($response);
mysqli_close($conn);
?>