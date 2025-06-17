<?php
require_once "connect.php";
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 2; // id_amthuc = 2 cho Bar

// Ghi log giá trị id để debug
error_log("Fetching bar with id_amthuc: $id");

$sql = "SELECT 
            atnn.id,
            atnn.title AS title_vi,
            atnn.content AS content_vi,
            atnn.description AS description_vi,
            atnn_en.title AS title_en,
            atnn_en.content AS content_en,
            atnn_en.description AS description_en,
            GROUP_CONCAT(DISTINCT anh.image) AS images
        FROM amthuc_ngonngu atnn
        LEFT JOIN amthuc_ngonngu atnn_en ON atnn.id_amthuc = atnn_en.id_amthuc AND atnn_en.id_ngonngu = 2
        LEFT JOIN anhbar anh ON atnn.id_amthuc = ? AND anh.active = 1
        WHERE atnn.id_amthuc = ? AND atnn.id_ngonngu = 1
        GROUP BY atnn.id";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    error_log("Prepare failed: " . mysqli_error($conn));
    echo json_encode(['success' => false, 'message' => 'Lỗi chuẩn bị truy vấn']);
    exit;
}

if (!mysqli_stmt_bind_param($stmt, "ii", $id, $id)) {
    error_log("Binding parameters failed: " . mysqli_stmt_error($stmt));
    echo json_encode(['success' => false, 'message' => 'Lỗi gắn tham số']);
    exit;
}

if (!mysqli_stmt_execute($stmt)) {
    error_log("Execute failed: " . mysqli_stmt_error($stmt));
    echo json_encode(['success' => false, 'message' => 'Lỗi thực thi truy vấn']);
    exit;
}

$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $images = $row['images'] ? explode(',', $row['images']) : [];

    echo json_encode([
        'success' => true,
        'item' => [
            'id' => $row['id'],
            'title_vi' => $row['title_vi'],
            'content_vi' => $row['content_vi'],
            'description_vi' => $row['description_vi'],
            'title_en' => $row['title_en'],
            'content_en' => $row['content_en'],
            'description_en' => $row['description_en'],
            'images' => $images
        ]
    ]);
} else {
    error_log("No results found for id_amthuc: $id");
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin Bar']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>