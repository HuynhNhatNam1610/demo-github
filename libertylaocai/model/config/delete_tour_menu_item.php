<?php
require_once "connect.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$id = isset($data['id']) ? (int)$data['id'] : 0;
$type = isset($data['type']) ? mysqli_real_escape_string($conn, $data['type']) : '';

if (!$id || !$type) {
    echo json_encode(['success' => false, 'message' => 'ID hoặc loại thực đơn không hợp lệ']);
    exit;
}

$sql = "DELETE n FROM thucdontour_ngonngu n
        JOIN thucdon_tour t ON n.id_menu = t.id
        WHERE t.id = ? AND t.type = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id, $type);
$success = $stmt->execute();

echo json_encode(['success' => $success, 'message' => $success ? '' : 'Không thể xóa mục thực đơn']);
$stmt->close();
$conn->close();
?>