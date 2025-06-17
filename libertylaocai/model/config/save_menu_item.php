<?php
require_once "../../model/UserModel.php";
require_once "connect.php";
ini_set('display_errors', 0); // Tắt hiển thị lỗi trên màn hình
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid request'];

try {
    // Kiểm tra kết nối
    if (!$conn) {
        throw new Exception('Database connection failed');
    }

    // Lấy dữ liệu từ FormData và log để debug
    $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    error_log("Received POST data - post_id: $postId, type: $type"); // Log để kiểm tra

    $price = isset($_POST['price']) ? $_POST['price'] : 0;
    $titleVi = isset($_POST['title_vi']) ? trim($_POST['title_vi']) : '';
    $titleEn = isset($_POST['title_en']) ? trim($_POST['title_en']) : '';
    $contentVi = isset($_POST['content_vi']) ? trim($_POST['content_vi']) : '';
    $contentEn = isset($_POST['content_en']) ? trim($_POST['content_en']) : '';
    $outstanding = isset($_POST['outstanding']) && !in_array($type, ['tour', 'hoinghi', 'sinhnhat', 'gala', 'tieccuoi']) ? 1 : 0; // Chỉ áp dụng outstanding cho Nhà hàng và Bar

    // Xác định id_amthuc và menuType dựa trên type
    $id_amthuc = ($type === 'restaurant') ? 1 : 2;
    $menuType = '';
    if ($type === 'restaurant') {
        $menuType = null; // Không cần type cho restaurant, để trống
    } elseif ($type === 'food' || $type === 'bar_food') {
        $menuType = 'main';
    } elseif ($type === 'drink' || $type === 'bar_drink') {
        $menuType = 'cocktails';
    } else {
        error_log("Warning: Invalid type value - type: $type");
    }

    // Xử lý ảnh
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../view/img/uploads/menu/';
        $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            throw new Exception('Failed to upload image');
        }
        $imagePath = 'uploads/menu/' . $imageName; // Đường dẫn tương đối
    } elseif ($postId > 0) {
        // Nếu chỉnh sửa và không thay đổi ảnh, giữ nguyên ảnh cũ
        $stmt = $conn->prepare("SELECT image FROM anhthucdon WHERE id_menu = ?");
        $stmt->bind_param('i', $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $imagePath = $row['image'];
        }
        $stmt->close();
    }

    // Bắt đầu transaction
    mysqli_begin_transaction($conn);

    if ($postId > 0) {
        // Cập nhật bản ghi hiện có
        $stmt = $conn->prepare("UPDATE thucdon SET price = ?, id_amthuc = ?, type = ?, outstanding = ? WHERE id = ?");
        $stmt->bind_param('dissd', $price, $id_amthuc, $menuType, $outstanding, $postId); // Thêm outstanding
        if (!$stmt->execute()) {
            throw new Exception('Failed to update menu item');
        }
        $stmt->close();

        // Cập nhật ngôn ngữ
        $stmt = $conn->prepare("UPDATE thucdon_ngonngu SET name = ?, content = ? WHERE id_thucdon = ? AND id_ngonngu = 1");
        $stmt->bind_param('ssi', $titleVi, $contentVi, $postId);
        if (!$stmt->execute()) {
            throw new Exception('Failed to update Vietnamese content');
        }
        $stmt->close();

        $stmt = $conn->prepare("UPDATE thucdon_ngonngu SET name = ?, content = ? WHERE id_thucdon = ? AND id_ngonngu = 2");
        $stmt->bind_param('ssi', $titleEn, $contentEn, $postId);
        if (!$stmt->execute()) {
            throw new Exception('Failed to update English content');
        }
        $stmt->close();

        // Cập nhật ảnh nếu có
        if (!empty($imagePath)) {
            $stmt = $conn->prepare("UPDATE anhthucdon SET image = ? WHERE id_menu = ?");
            $stmt->bind_param('si', $imagePath, $postId);
            if (!$stmt->execute()) {
                throw new Exception('Failed to update image');
            }
            $stmt->close();
        }
    } else {
        // Thêm bản ghi mới
        $stmt = $conn->prepare("INSERT INTO thucdon (price, id_amthuc, type, active, outstanding) VALUES (?, ?, ?, 1, ?)");
        $stmt->bind_param('dssd', $price, $id_amthuc, $menuType, $outstanding); // Thêm outstanding
        if (!$stmt->execute()) {
            throw new Exception('Failed to insert menu item');
        }
        $postId = $conn->insert_id;
        $stmt->close();

        // Thêm ngôn ngữ
        $stmt = $conn->prepare("INSERT INTO thucdon_ngonngu (id_thucdon, id_ngonngu, name, content) VALUES (?, 1, ?, ?)");
        $stmt->bind_param('iss', $postId, $titleVi, $contentVi);
        if (!$stmt->execute()) {
            throw new Exception('Failed to insert Vietnamese content');
        }
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO thucdon_ngonngu (id_thucdon, id_ngonngu, name, content) VALUES (?, 2, ?, ?)");
        $stmt->bind_param('iss', $postId, $titleEn, $contentEn);
        if (!$stmt->execute()) {
            throw new Exception('Failed to insert English content');
        }
        $stmt->close();

        // Thêm ảnh nếu có
        if (!empty($imagePath)) {
            $stmt = $conn->prepare("INSERT INTO anhthucdon (id_menu, id_topic, image) VALUES (?, 14, ?)");
            $stmt->bind_param('is', $postId, $imagePath);
            if (!$stmt->execute()) {
                throw new Exception('Failed to insert image');
            }
            $stmt->close();
        }
    }

    // Commit transaction
    mysqli_commit($conn);
    $response['success'] = true;
    $response['message'] = 'Menu item saved successfully';
} catch (Exception $e) {
    // Rollback transaction nếu có lỗi
    mysqli_rollback($conn);
    $response['message'] = 'Error: ' . $e->getMessage();
    error_log("Error in save_menu_item.php: " . $e->getMessage());
}

echo json_encode($response);
mysqli_close($conn);