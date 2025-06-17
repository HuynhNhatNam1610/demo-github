<?php
require_once "../../model/UserModel.php";
require_once "connect.php";

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid request'];

try {
    // Kiểm tra kết nối
    if (!$conn) {
        throw new Exception('Database connection failed');
    }

    // Lấy tham số từ query string
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $type = isset($_GET['type']) ? trim($_GET['type']) : '';

    if ($id <= 0 || empty($type)) {
        throw new Exception('Invalid ID or type');
    }

    // Xác định id_amthuc và menu type dựa trên type
    $id_amthuc = ($type === 'restaurant') ? 1 : 2;
    $menuType = ($type === 'food') ? 'main' : ($type === 'drink' ? 'cocktails' : '');

    // Truy vấn thông tin menu
    $sql = "
        SELECT 
            t.*,
            tn_vi.name AS title_vi,
            tn_vi.content AS content_vi,
            tn_en.name AS title_en,
            tn_en.content AS content_en,
            a.image
        FROM 
            thucdon t
        LEFT JOIN 
            thucdon_ngonngu tn_vi ON t.id = tn_vi.id_thucdon AND tn_vi.id_ngonngu = 1
        LEFT JOIN 
            thucdon_ngonngu tn_en ON t.id = tn_vi.id_thucdon AND tn_en.id_ngonngu = 2
        LEFT JOIN 
            anhthucdon a ON t.id = a.id_menu
        WHERE 
            t.id = ?
            AND t.id_amthuc = ?
    ";

    // Nếu type là food hoặc drink, thêm điều kiện type
    if ($type === 'food' || $type === 'drink') {
        $sql .= " AND t.type = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iis', $id, $id_amthuc, $menuType);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $id, $id_amthuc);
    }

    if (!$stmt->execute()) {
        throw new Exception('Failed to fetch menu item');
    }

    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $response['success'] = true;
        $response['item'] = [
            'title_vi' => $row['title_vi'] ?? '',
            'title_en' => $row['title_en'] ?? '',
            'content_vi' => $row['content_vi'] ?? '',
            'content_en' => $row['content_en'] ?? '',
            'price' => $row['price'] ?? 0,
            'image' => $row['image'] ? '' . $row['image'] : '',
            'outstanding' => $row['outstanding']
        ];
    } else {
        throw new Exception('Menu item not found');
    }

    $stmt->close();
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
    error_log("Error in fetch_menu_item.php: " . $e->getMessage());
}

echo json_encode($response);
mysqli_close($conn);
