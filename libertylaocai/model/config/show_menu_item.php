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

    // Cập nhật trạng thái active thành 1
    $sql = "UPDATE thucdon SET active = 1 WHERE id = ? AND id_amthuc = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $id_amthuc);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Menu item shown successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No menu item found or already visible']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update menu item']);
    }

    $stmt->close();
} catch (Exception $e) {
    error_log("Error showing menu item: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
}
?>