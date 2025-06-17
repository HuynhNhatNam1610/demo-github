<?php
require_once "connect.php";

header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1; // id_amthuc = 1 cho Nhà hàng

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
        LEFT JOIN anhnhahang anh ON atnn.id_amthuc = ? AND anh.active = 1
        WHERE atnn.id_amthuc = ? AND atnn.id_ngonngu = 1
        GROUP BY atnn.id";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $id, $id);
mysqli_stmt_execute($stmt);
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
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin Nhà hàng']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>