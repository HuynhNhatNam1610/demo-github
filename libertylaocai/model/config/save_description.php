
<?php
require_once "connect.php";
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// Lấy dữ liệu từ POST
$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
$title_vi = isset($_POST['title_vi']) ? trim($_POST['title_vi']) : '';
$title_en = isset($_POST['title_en']) ? trim($_POST['title_en']) : '';
$content_vi = isset($_POST['description_vi']) ? trim($_POST['description_vi']) : '';
$content_en = isset($_POST['description_en']) ? trim($_POST['description_en']) : '';

if ($post_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID mô tả không hợp lệ']);
    $conn->close();
    exit;
}

// Kiểm tra xem id_mota có tồn tại trong bảng mota không
$sql_check = "SELECT id FROM mota WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $post_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Mô tả không tồn tại']);
    $stmt_check->close();
    $conn->close();
    exit;
}
$stmt_check->close();

// Bắt đầu giao dịch
$conn->begin_transaction();

try {
    // Cập nhật bản ghi cho tiếng Việt (id_ngonngu = 1)
    $sql_vi = "UPDATE mota_ngonngu SET title = ?, content = ? 
               WHERE id_mota = ? AND id_ngonngu = 1";
    $stmt_vi = $conn->prepare($sql_vi);
    $stmt_vi->bind_param("ssi", $title_vi, $content_vi, $post_id);
    $stmt_vi->execute();
    $stmt_vi->close();

    // Cập nhật bản ghi cho tiếng Anh (id_ngonngu = 2)
    $sql_en = "UPDATE mota_ngonngu SET title = ?, content = ? 
               WHERE id_mota = ? AND id_ngonngu = 2";
    $stmt_en = $conn->prepare($sql_en);
    $stmt_en->bind_param("ssi", $title_en, $content_en, $post_id);
    $stmt_en->execute();
    $stmt_en->close();

    // Cam kết giao dịch
    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Hoàn tác nếu có lỗi
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu dữ liệu: ' . $e->getMessage()]);
}

$conn->close();
?>
