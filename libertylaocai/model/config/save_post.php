<?php
require_once 'connect.php'; // File kết nối database

header('Content-Type: application/json');

$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$type = isset($_POST['post_type']) ? trim($_POST['post_type']) : '';
$title_vi = isset($_POST['title_vi']) ? trim($_POST['title_vi']) : '';
$content_vi = isset($_POST['content_vi']) ? $_POST['content_vi'] : '';
$title_en = isset($_POST['title_en']) ? trim($_POST['title_en']) : '';
$content_en = isset($_POST['content_en']) ? $_POST['content_en'] : '';

// Debug: Log các giá trị để kiểm tra
error_log("POST data - post_id: " . $post_id . ", type: " . $type);

if (empty($type) || empty($title_vi) || empty($title_en)) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

// Xác định bảng dựa trên loại bài viết
$main_table = $image_table = $content_table = '';
switch ($type) {
    case 'news':
        $main_table = 'tintuc';
        $image_table = 'anhtintuc';
        $content_table = 'tintuc_ngonngu';
        break;
    case 'offer':
        $main_table = 'uudai';
        $image_table = 'anhuudai';
        $content_table = 'uudai_ngonngu';
        break;
    case 'event':
        $main_table = 'sukiendatochuc';
        $image_table = 'anhsukiendatochuc';
        $content_table = 'sukiendatochuc_ngonngu';
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Loại bài viết không hợp lệ']);
        exit;
}

// Bắt đầu transaction
mysqli_begin_transaction($conn);

try {
    $is_edit = false;

    // Kiểm tra nếu là chỉnh sửa bài viết
    if ($post_id > 0) {
        // Kiểm tra bài viết có tồn tại không
        $check_sql = "SELECT id FROM `$main_table` WHERE id = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, 'i', $post_id);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($result) > 0) {
            // Bài viết tồn tại, thực hiện cập nhật
            $is_edit = true;
            $sql = "UPDATE `$main_table` SET `author` = 'Admin', `create_at` = NOW(), `active` = 1 WHERE `id` = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $post_id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception('Lỗi khi cập nhật bài viết: ' . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt);
            error_log("Updated existing post with ID: " . $post_id);
        }
        mysqli_stmt_close($check_stmt);
    }

    // Nếu không phải chỉnh sửa, tạo mới
    if (!$is_edit) {
        $sql = "INSERT INTO `$main_table` (`author`, `create_at`, `active`) VALUES ('Admin', NOW(), 1)";
        if (!mysqli_query($conn, $sql)) {
            throw new Exception('Lỗi khi thêm bài viết: ' . mysqli_error($conn));
        }
        $post_id = mysqli_insert_id($conn);
        error_log("Created new post with ID: " . $post_id);
    }

    // Xử lý ảnh đại diện
    if (isset($_FILES['primary_image']) && $_FILES['primary_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/libertylaocai/view/img/uploads/new/';

        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $image_name = time() . '_' . basename($_FILES['primary_image']['name']);
        $image_path = $upload_dir . $image_name;

        if (!move_uploaded_file($_FILES['primary_image']['tmp_name'], $image_path)) {
            throw new Exception('Lỗi khi tải ảnh lên');
        }

        // Lưu đường dẫn ảnh (tương đối)
        $image_relative_path = '/libertylaocai/view/img/uploads/new/' . $image_name;

        // Xóa ảnh đại diện cũ (đặt is_primary = 0)
        $sql = "UPDATE `$image_table` SET `is_primary` = 0 WHERE `id_$main_table` = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $post_id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Lỗi khi cập nhật ảnh đại diện cũ: ' . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);

        // Thêm ảnh mới
        $sql = "INSERT INTO `$image_table` (`image`, `is_primary`, `id_topic`, `id_$main_table`) VALUES (?, 1, 1, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $image_relative_path, $post_id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Lỗi khi thêm ảnh mới: ' . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
    }

    // Xử lý nội dung ngôn ngữ
    if ($is_edit) {
        // Khi edit: Kiểm tra và UPDATE hoặc INSERT nếu chưa có

        // Xử lý tiếng Việt
        $check_sql = "SELECT id FROM `$content_table` WHERE `id_$main_table` = ? AND `id_ngonngu` = 1";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, 'i', $post_id);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($result) > 0) {
            // UPDATE tiếng Việt
            $sql = "UPDATE `$content_table` SET `title` = ?, `content` = ? WHERE `id_$main_table` = ? AND `id_ngonngu` = 1";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ssi', $title_vi, $content_vi, $post_id);
        } else {
            // INSERT tiếng Việt
            $sql = "INSERT INTO `$content_table` (`id_$main_table`, `id_ngonngu`, `title`, `content`) VALUES (?, 1, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'iss', $post_id, $title_vi, $content_vi);
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Lỗi khi lưu nội dung tiếng Việt: ' . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
        mysqli_stmt_close($check_stmt);

        // Xử lý tiếng Anh
        $check_sql = "SELECT id FROM `$content_table` WHERE `id_$main_table` = ? AND `id_ngonngu` = 2";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, 'i', $post_id);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($result) > 0) {
            // UPDATE tiếng Anh
            $sql = "UPDATE `$content_table` SET `title` = ?, `content` = ? WHERE `id_$main_table` = ? AND `id_ngonngu` = 2";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ssi', $title_en, $content_en, $post_id);
        } else {
            // INSERT tiếng Anh
            $sql = "INSERT INTO `$content_table` (`id_$main_table`, `id_ngonngu`, `title`, `content`) VALUES (?, 2, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'iss', $post_id, $title_en, $content_en);
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Lỗi khi lưu nội dung tiếng Anh: ' . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
        mysqli_stmt_close($check_stmt);
    } else {
        // Khi tạo mới: Chỉ INSERT

        // INSERT tiếng Việt
        $sql = "INSERT INTO `$content_table` (`id_$main_table`, `id_ngonngu`, `title`, `content`) VALUES (?, 1, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'iss', $post_id, $title_vi, $content_vi);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Lỗi khi lưu nội dung tiếng Việt: ' . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);

        // INSERT tiếng Anh
        $sql = "INSERT INTO `$content_table` (`id_$main_table`, `id_ngonngu`, `title`, `content`) VALUES (?, 2, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'iss', $post_id, $title_en, $content_en);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Lỗi khi lưu nội dung tiếng Anh: ' . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
    }

    // Commit transaction
    mysqli_commit($conn);

    $action = $is_edit ? 'cập nhật' : 'thêm mới';
    echo json_encode([
        'success' => true,
        'post_id' => $post_id,
        'action' => $action,
        'message' => 'Đã ' . $action . ' bài viết thành công!'
    ]);
} catch (Exception $e) {
    // Rollback transaction
    mysqli_rollback($conn);
    error_log("Error in save_post.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}

mysqli_close($conn);
