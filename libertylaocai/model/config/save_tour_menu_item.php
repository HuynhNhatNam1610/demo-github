
<?php
require_once "connect.php";

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid request'];

try {
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $title_vi = isset($_POST['title_vi']) ? trim($_POST['title_vi']) : '';
    $content_vi = isset($_POST['content_vi']) ? trim($_POST['content_vi']) : '';
    $title_en = isset($_POST['title_en']) ? trim($_POST['title_en']) : '';
    $content_en = isset($_POST['content_en']) ? trim($_POST['content_en']) : '';

    if (!$type || !$title_vi || !$title_en) {
        throw new Exception('Missing required fields');
    }

    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '/libertylaocai/view/img/uploads/menu/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $image_path = 'uploads/menu/' . $image_name;
        // Di chuyển tệp
        if (!move_uploaded_file($_FILES['image']['tmp_name'], '../../view/img/' . $image_path)) {
            throw new Exception('Không thể tải lên hình ảnh');
        }
    }

    if ($post_id > 0) {
        // Lấy id_menu từ thucdontour_ngonngu dựa trên post_id
        $sql_get_id_menu = "SELECT id_menu FROM thucdontour_ngonngu WHERE id_menu = ?";
        $stmt_get_id_menu = $conn->prepare($sql_get_id_menu);
        $stmt_get_id_menu->bind_param("i", $post_id);
        $stmt_get_id_menu->execute();
        $result = $stmt_get_id_menu->get_result();
        if ($row = $result->fetch_assoc()) {
            $id_menu = $row['id_menu'];
        } else {
            throw new Exception('Menu item not found');
        }
        $stmt_get_id_menu->close();

        // Cập nhật thucdon_tour
        $sql = "UPDATE thucdon_tour SET type = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $type, $id_menu);
        $stmt->execute();
        $stmt->close();

        // Cập nhật bản ghi tiếng Việt
        $sql_vi = "UPDATE thucdontour_ngonngu SET title = ?, content = ?, image = COALESCE(?, image) WHERE id_menu = ? AND id_ngonngu = 1";
        $stmt_vi = $conn->prepare($sql_vi);
        $stmt_vi->bind_param("sssi", $title_vi, $content_vi, $image_path, $post_id);
        $stmt_vi->execute();
        $stmt_vi->close();

        // Cập nhật bản ghi tiếng Việt
        $sql_vi = "UPDATE thucdontour_ngonngu SET title = ?, content = ? WHERE id_menu = ? AND id_ngonngu = 2";
        $stmt_vi = $conn->prepare($sql_vi);
        $stmt_vi->bind_param("ssi", $title_en, $content_en, $post_id);
        $stmt_vi->execute();
        $stmt_vi->close();

        // // Cập nhật hoặc thêm bản ghi tiếng Anh
        // $sql_en = "INSERT INTO thucdontour_ngonngu (id_menu, id_ngonngu, title, content) VALUES (?, 2, ?, ?) ON DUPLICATE KEY UPDATE title = ?, content = ?";
        // $stmt_en = $conn->prepare($sql_en);
        // $stmt_en->bind_param("issss", $id_menu, $title_en, $content_en, $title_en, $content_en);
        // $stmt_en->execute();
        // $stmt_en->close();
    } else {
        // Thêm mục thực đơn mới
        $sql = "INSERT INTO thucdon_tour (type) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $type);
        $stmt->execute();
        $id_menu = $conn->insert_id;
        $stmt->close();

        // Thêm bản ghi tiếng Việt
        $sql_vi = "INSERT INTO thucdontour_ngonngu (id_menu, id_ngonngu, title, content, image) VALUES (?, 1, ?, ?, ?)";
        $stmt_vi = $conn->prepare($sql_vi);
        $stmt_vi->bind_param("isss", $id_menu, $title_vi, $content_vi, $image_path);
        $stmt_vi->execute();
        $stmt_vi->close();

        // Thêm bản ghi tiếng Anh nếu có
        if ($title_en || $content_en) {
            $sql_en = "INSERT INTO thucdontour_ngonngu (id_menu, id_ngonngu, title, content) VALUES (?, 2, ?, ?)";
            $stmt_en = $conn->prepare($sql_en);
            $stmt_en->bind_param("iss", $id_menu, $title_en, $content_en);
            $stmt_en->execute();
            $stmt_en->close();
        }
    }

    $response['success'] = true;
    $response['message'] = 'Menu item saved successfully';
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
    error_log("Error in save_tour_menu_item.php: " . $e->getMessage());
}

echo json_encode($response);
$conn->close();
?>
