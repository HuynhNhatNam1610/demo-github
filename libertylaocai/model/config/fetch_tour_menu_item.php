
<?php
require_once "connect.php";

header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$type = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : '';

if (!$id || !$type) {
    echo json_encode(['success' => false, 'message' => 'ID hoặc loại thực đơn không hợp lệ']);
    exit;
}

$sql = "SELECT n.id, n.title AS title_vi, n.content AS content_vi, n.image,
               n2.title AS title_en, n2.content AS content_en
        FROM thucdontour_ngonngu n
        LEFT JOIN thucdontour_ngonngu n2 ON n.id_menu = n2.id_menu AND n2.id_ngonngu = 2
        JOIN thucdon_tour t ON n.id_menu = t.id
        WHERE t.id = ? AND t.type = ? AND n.id_ngonngu = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id, $type);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'success' => true,
        'item' => [
            'id' => $row['id'],
            'title_vi' => $row['title_vi'] ?? '',
            'content_vi' => $row['content_vi'] ?? '',
            'title_en' => $row['title_en'] ?? '',
            'content_en' => $row['content_en'] ?? '',
            'image' => $row['image'] ?? null
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy mục thực đơn']);
}

$stmt->close();
$conn->close();
?>