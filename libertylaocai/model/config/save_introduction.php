<?php
require_once "connect.php";

header('Content-Type: application/json');

try {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $title_vi = isset($_POST['title_vi']) ? mysqli_real_escape_string($conn, trim($_POST['title_vi'])) : '';
    $title_en = isset($_POST['title_en']) ? mysqli_real_escape_string($conn, trim($_POST['title_en'])) : '';
    $content_vi = isset($_POST['description_vi']) ? mysqli_real_escape_string($conn, trim($_POST['description_vi'])) : '';
    $content_en = isset($_POST['description_en']) ? mysqli_real_escape_string($conn, trim($_POST['description_en'])) : '';

    if (empty($title_vi) || empty($title_en)) {
        throw new Exception("Tiêu đề không được để trống.");
    }

    // Bắt đầu giao dịch
    mysqli_begin_transaction($conn);

    if ($post_id > 0) {
        // Cập nhật bản ghi hiện có
        $query = "UPDATE gioithieu SET active = 1 WHERE id = $post_id";
        if (!mysqli_query($conn, $query)) {
            throw new Exception("Lỗi cập nhật gioithieu: " . mysqli_error($conn));
        }

        $query_vi = "UPDATE gioithieu_ngonngu SET title = '$title_vi', content = '$content_vi' WHERE id_gioithieu = $post_id AND id_ngonngu = 1";
        if (!mysqli_query($conn, $query_vi)) {
            throw new Exception("Lỗi cập nhật tiếng Việt: " . mysqli_error($conn));
        }

        $query_en = "UPDATE gioithieu_ngonngu SET title = '$title_en', content = '$content_en' WHERE id_gioithieu = $post_id AND id_ngonngu = 2";
        if (!mysqli_query($conn, $query_en)) {
            throw new Exception("Lỗi cập nhật tiếng Anh: " . mysqli_error($conn));
        }
    } else {
        // Thêm bản ghi mới
        $query = "INSERT INTO gioithieu (active) VALUES (1)";
        if (!mysqli_query($conn, $query)) {
            throw new Exception("Lỗi thêm gioithieu: " . mysqli_error($conn));
        }
        $post_id = mysqli_insert_id($conn);

        $query_vi = "INSERT INTO gioithieu_ngonngu (id_gioithieu, id_ngonngu, title, content) VALUES ($post_id, 1, '$title_vi', '$content_vi')";
        if (!mysqli_query($conn, $query_vi)) {
            throw new Exception("Lỗi thêm tiếng Việt: " . mysqli_error($conn));
        }

        $query_en = "INSERT INTO gioithieu_ngonngu (id_gioithieu, id_ngonngu, title, content) VALUES ($post_id, 2, '$title_en', '$content_en')";
        if (!mysqli_query($conn, $query_en)) {
            throw new Exception("Lỗi thêm tiếng Anh: " . mysqli_error($conn));
        }
    }

    // Cam kết giao dịch
    mysqli_commit($conn);

    $response = ['success' => true];
} catch (Exception $e) {
    // Hoàn tác giao dịch
    mysqli_rollback($conn);
    $response = [
        'success' => false,
        'message' => "Lỗi: " . $e->getMessage()
    ];
}

echo json_encode($response);
mysqli_close($conn);
?>