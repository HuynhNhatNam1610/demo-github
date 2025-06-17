<?php
require_once "connect.php";

header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Invalid request'];

try {
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 2; // id_amthuc = 2 cho Bar
    $title_vi = isset($_POST['title_vi']) ? trim($_POST['title_vi']) : '';
    $content_vi = isset($_POST['content_vi']) ? trim($_POST['content_vi']) : '';
    $description_vi = isset($_POST['description_vi']) ? $_POST['description_vi'] : '';
    $title_en = isset($_POST['title_en']) ? trim($_POST['title_en']) : '';
    $content_en = isset($_POST['content_en']) ? trim($_POST['content_en']) : '';
    $description_en = isset($_POST['description_en']) ? $_POST['description_en'] : '';
    $existing_images = isset($_POST['existing_images']) ? json_decode($_POST['existing_images'], true) : [];

    if (!$title_vi || !$title_en) {
        throw new Exception('Missing required fields');
    }

    mysqli_begin_transaction($conn);
    // Xóa ảnh cũ không có trong existing_images
    if (!empty($existing_images)) {
        $placeholders = implode(',', array_fill(0, count($existing_images), '?'));
        $sql = "DELETE FROM anhbar WHERE image NOT IN ($placeholders) AND id_topic = 13";
        $stmt = mysqli_prepare($conn, $sql);
        $types = str_repeat('s', count($existing_images));
        mysqli_stmt_bind_param($stmt, $types, ...$existing_images);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Lỗi khi xóa ảnh cũ');
        }
        mysqli_stmt_close($stmt);
        error_log("Deleted old images not in existing_images for id_topic=13");
    } else {
        $sql = "DELETE FROM anhbar WHERE id_topic = 13";
        $stmt = mysqli_prepare($conn, $sql);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Lỗi khi xóa tất cả ảnh cũ');
        }
        mysqli_stmt_close($stmt);
        error_log("Deleted all images for id_topic=13");
    }

    // Xử lý ảnh
    $image_paths = [];
    if (isset($_FILES['image']) && count($_FILES['image']['name']) > 0) {
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/libertylaocai/view/img/uploads/bar/';
        $relative_path = 'uploads/bar/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        foreach ($_FILES['image']['name'] as $key => $name) {
            if ($_FILES['image']['error'][$key] === UPLOAD_ERR_OK) {
                $image_name = time() . '_' . basename($name);
                $image_path = $upload_dir . $image_name;
                if (!move_uploaded_file($_FILES['image']['tmp_name'][$key], $image_path)) {
                    throw new Exception('Không thể tải lên hình ảnh');
                }
                $image_paths[] = $relative_path . $image_name;
            }
        }
    }

    // Cập nhật thông tin Bar
    $sql_vi = "UPDATE amthuc_ngonngu SET title = ?, content = ?, description = ? WHERE id_amthuc = ? AND id_ngonngu = 1";
    $stmt_vi = mysqli_prepare($conn, $sql_vi);
    mysqli_stmt_bind_param($stmt_vi, "sssi", $title_vi, $content_vi, $description_vi, $post_id);
    if (!mysqli_stmt_execute($stmt_vi)) {
        throw new Exception('Lỗi khi cập nhật tiếng Việt');
    }
    mysqli_stmt_close($stmt_vi);

    $sql_en = "UPDATE amthuc_ngonngu SET title = ?, content = ?, description = ? WHERE id_amthuc = ? AND id_ngonngu = 2";
    $stmt_en = mysqli_prepare($conn, $sql_en);
    mysqli_stmt_bind_param($stmt_en, "sssi", $title_en, $content_en, $description_en, $post_id);
    if (!mysqli_stmt_execute($stmt_en)) {
        throw new Exception('Lỗi khi cập nhật tiếng Anh');
    }
    mysqli_stmt_close($stmt_en);

    // Thêm ảnh mới
    foreach ($image_paths as $image) {
        $sql = "INSERT INTO anhbar (image, active, created_at, id_topic) VALUES (?, 1, NOW(), 13)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $image);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Lỗi khi thêm ảnh');
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_commit($conn);
    $response['success'] = true;
    $response['message'] = 'Bar info saved successfully';
} catch (Exception $e) {
    mysqli_rollback($conn);
    $response['message'] = 'Error: ' . $e->getMessage();
    error_log("Error in save_bar.php: " . $e->getMessage());
}

echo json_encode($response);
mysqli_close($conn);
