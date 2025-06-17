<?php
require_once "connect.php";
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
header('Content-Type: application/json');

$id = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Kiểm tra xem id_mota có tồn tại trong bảng mota không
$sql_check = "SELECT id FROM mota WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy mô tả với ID này']);
    $stmt_check->close();
    $conn->close();
    exit;
}
$stmt_check->close();

// Truy vấn dữ liệu từ mota_ngonngu
$sql = "SELECT id_ngonngu, title, content 
        FROM mota_ngonngu 
        WHERE id_mota = ? AND id_ngonngu IN (1, 2)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$data = [
    'id' => $id,
    'title_vi' => '',
    'title_en' => '',
    'content_vi' => '',
    'content_en' => ''
];

while ($row = $result->fetch_assoc()) {
    if ($row['id_ngonngu'] == 1) { // Tiếng Việt
        $data['title_vi'] = $row['title'] ?? '';
        $data['content_vi'] = $row['content'] ?? '';
    } elseif ($row['id_ngonngu'] == 2) { // Tiếng Anh
        $data['title_en'] = $row['title'] ?? '';
        $data['content_en'] = $row['content'] ?? '';
    }
}

if ($data['title_vi'] === '' && $data['title_en'] === '' && $data['content_vi'] === '' && $data['content_en'] === '') {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy dữ liệu ngôn ngữ']);
} else {
    echo json_encode([
        'success' => true,
        'item' => $data
    ]);
}

$stmt->close();
$conn->close();
