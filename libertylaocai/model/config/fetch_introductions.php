<?php
require_once "connect.php";


header('Content-Type: application/json');

$active = isset($_GET['active']) ? intval($_GET['active']) : 1;
$language_id = isset($_GET['language']) ? intval($_GET['language']) : 1;

try {
    // Chuẩn bị truy vấn
    $query = "
        SELECT g.id, gn.title AS title_vi, gn.content AS content_vi,
               (SELECT gn2.title FROM gioithieu_ngonngu gn2 WHERE gn2.id_gioithieu = g.id AND gn2.id_ngonngu = 2) AS title_en,
               (SELECT gn2.content FROM gioithieu_ngonngu gn2 WHERE gn2.id_gioithieu = g.id AND gn2.id_ngonngu = 2) AS content_en
        FROM gioithieu g
        LEFT JOIN gioithieu_ngonngu gn ON g.id = gn.id_gioithieu AND gn.id_ngonngu = $language_id
        WHERE g.active = $active
        ORDER BY g.id DESC
    ";

    // Thực thi truy vấn
    $result = mysqli_query($conn, $query);

    if (!$result) {
        throw new Exception("Lỗi truy vấn: " . mysqli_error($conn));
    }

    $items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = [
            'id' => $row['id'],
            'title_vi' => $row['title_vi'],
            'content_vi' => $row['content_vi'],
            'title_en' => $row['title_en'],
            'content_en' => $row['content_en'],
            'images' => [] // Có thể thêm logic lấy ảnh nếu cần
        ];
    }

    $response = [
        'success' => true,
        'items' => $items
    ];

    mysqli_free_result($result);
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => "Lỗi: " . $e->getMessage()
    ];
}

echo json_encode($response);
mysqli_close($conn);
?>