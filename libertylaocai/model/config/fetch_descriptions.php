<?php
require_once "connect.php";
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
header('Content-Type: application/json');

// Truy vấn tất cả id_mota từ bảng mota
$sql_mota = "SELECT id FROM mota";
$result_mota = $conn->query($sql_mota);

if ($result_mota->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy mô tả nào']);
    $conn->close();
    exit;
}

// Lấy tất cả dữ liệu từ mota_ngonngu
$sql = "SELECT m.id, mn.id_ngonngu, mn.title, mn.content 
        FROM mota m 
        LEFT JOIN mota_ngonngu mn ON m.id = mn.id_mota 
        WHERE mn.id_ngonngu IN (1, 2) OR mn.id_ngonngu IS NULL
        ORDER BY m.id";
$result = $conn->query($sql);

$items = [];
$current_mota = null;
$current_item = null;

while ($row = $result->fetch_assoc()) {
    if ($current_mota !== $row['id']) {
        if ($current_item !== null) {
            $items[] = $current_item;
        }
        $current_mota = $row['id'];
        $current_item = [
            'id' => $row['id'],
            'title_vi' => '',
            'title_en' => '',
            'content_vi' => '',
            'content_en' => ''
        ];
    }
    if ($row['id_ngonngu'] == 1) { // Tiếng Việt
        $current_item['title_vi'] = $row['title'] ?? '';
        $current_item['content_vi'] = $row['content'] ?? '';
    } elseif ($row['id_ngonngu'] == 2) { // Tiếng Anh
        $current_item['title_en'] = $row['title'] ?? '';
        $current_item['content_en'] = $row['content'] ?? '';
    }
}

// Thêm bản ghi cuối cùng nếu có
if ($current_item !== null) {
    $items[] = $current_item;
}

if (empty($items)) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy dữ liệu ngôn ngữ']);
} else {
    echo json_encode([
        'success' => true,
        'items' => $items
    ]);
}

$conn->close();
?>