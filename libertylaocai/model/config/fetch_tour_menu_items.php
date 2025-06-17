
<?php
require_once "connect.php";

header('Content-Type: application/json');

$type = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : '';
$active = isset($_GET['active']) ? (int)$_GET['active'] : 1;
$language = isset($_GET['language']) ? (int)$_GET['language'] : 1;

if (!$type) {
    echo json_encode(['success' => false, 'message' => 'Loại thực đơn không hợp lệ']);
    exit;
}

$sql = "SELECT t.id, n.title, n.content, n.active, t.type, n.image
        FROM thucdon_tour t
        JOIN thucdontour_ngonngu n ON t.id = n.id_menu
        WHERE t.type = ? AND n.id_ngonngu = ? AND n.active = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $type, $language, $active);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'content' => $row['content'],
        'active' => $row['active'],
        'image' => $row['image'] ?? null
    ];
}

echo json_encode(['success' => true, 'items' => $items]);

$stmt->close();
$conn->close();
?>
