<?php
require_once "connect.php";
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
header('Content-Type: application/json');
header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$languageId = 1; // Có thể lấy từ session nếu cần

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
    exit;
}

$sql = "SELECT 
            ht.id,
            ht.room_number,
            ht.opacity AS capacity,
            ht.area,
            ht.floor_number,
            htnn.name AS title_vi,
            htnn.description AS description_vi,
            htnn_en.name AS title_en,
            htnn_en.description AS description_en,
            GROUP_CONCAT(DISTINCT CONCAT(gtht.how_long, ':', gtht.price) SEPARATOR '|') AS prices,
            GROUP_CONCAT(DISTINCT aht.image) AS images
        FROM hoitruong ht
        LEFT JOIN hoitruong_ngonngu htnn ON ht.id = htnn.id_hoitruong AND htnn.id_ngonngu = 1
        LEFT JOIN hoitruong_ngonngu htnn_en ON ht.id = htnn_en.id_hoitruong AND htnn_en.id_ngonngu = 2
        LEFT JOIN giathuehoitruong gtht ON ht.id = gtht.id_hoitruong
        LEFT JOIN anhhoitruong aht ON ht.id = aht.id_hoitruong AND aht.active = 1
        WHERE ht.id = ?
        GROUP BY ht.id";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $prices = [];
    if ($row['prices']) {
        foreach (explode('|', $row['prices']) as $price) {
            list($how_long, $price_value) = explode(':', $price);
            $prices[$how_long] = $price_value;
        }
    }

    $images = $row['images'] ? explode(',', $row['images']) : [];

    echo json_encode([
        'success' => true,
        'item' => [
            'id' => $row['id'],
            'room_number' => $row['room_number'],
            'capacity' => $row['capacity'],
            'area' => $row['area'],
            'floor_number' => $row['floor_number'],
            'title_vi' => $row['title_vi'],
            'description_vi' => $row['description_vi'],
            'title_en' => $row['title_en'],
            'description_en' => $row['description_en'],
            'prices' => $prices,
            'images' => $images
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy hội trường']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>