<?php
require_once "connect.php";

header('Content-Type: application/json');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
    exit;
}

try {
    // Chuẩn bị truy vấn
    $query = "
        SELECT g.id, gn.title AS title_vi, gn.content AS content_vi,
               (SELECT gn2.title FROM gioithieu_ngonngu gn2 WHERE gn2.id_gioithieu = g.id AND gn2.id_ngonngu = 2) AS title_en,
               (SELECT gn2.content FROM gioithieu_ngonngu gn2 WHERE gn2.id_gioithieu = g.id AND gn2.id_ngonngu = 2) AS content_en
        FROM gioithieu g
        LEFT JOIN gioithieu_ngonngu gn ON g.id = gn.id_gioithieu AND gn.id_ngonngu = 1
        WHERE g.id = $id
    ";

    // Thực thi truy vấn
    $result = mysqli_query($conn, $query);

    if (!$result) {
        throw new Exception("Lỗi truy vấn: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);
        $response = [
            'success' => true,
            'item' => [
                'id' => $item['id'],
                'title_vi' => $item['title_vi'],
                'content_vi' => $item['content_vi'],
                'title_en' => $item['title_en'],
                'content_en' => $item['content_en'],
                'images' => [] // Có thể thêm logic lấy ảnh nếu cần
            ]
        ];
    } else {
        $response = ['success' => false, 'message' => 'Không tìm thấy mục giới thiệu'];
    }

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