<?php
require_once 'connect.php';
ini_set('display_errors', 0); // Tắt hiển thị lỗi trên màn hình
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$type = isset($_GET['type']) ? trim($_GET['type']) : '';

if ($id <= 0 || empty($type)) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

// Xác định bảng
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

try {
    // Lấy nội dung đa ngôn ngữ
    $sql = "SELECT `id_ngonngu`, `title`, `content` FROM `$content_table` WHERE `id_$main_table` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $post = ['title_vi' => '', 'content_vi' => '', 'title_en' => '', 'content_en' => ''];
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['id_ngonngu'] == 1) {
            $post['title_vi'] = $row['title'];
            $post['content_vi'] = $row['content'];
        } elseif ($row['id_ngonngu'] == 2) {
            $post['title_en'] = $row['title'];
            $post['content_en'] = $row['content'];
        }
    }
    mysqli_stmt_close($stmt);

    // Lấy ảnh đại diện
    $sql = "SELECT `image` FROM `$image_table` WHERE `id_$main_table` = ? AND `is_primary` = 1 LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $image_row = mysqli_fetch_assoc($result);
    $post['image'] = $image_row ? $image_row['image'] : '';
    mysqli_stmt_close($stmt);

    echo json_encode(['success' => true, 'post' => $post]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}

mysqli_close($conn);
?>