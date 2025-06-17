<?php
require_once "../../view/php/session.php";
require_once "../UserModel.php";

header('Content-Type: application/json');

try {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 1;
    $title_vi = isset($_POST['title_vi']) ? $_POST['title_vi'] : '';
    $title_en = isset($_POST['title_en']) ? $_POST['title_en'] : '';
    $description_vi = isset($_POST['description_vi']) ? $_POST['description_vi'] : '';
    $description_en = isset($_POST['description_en']) ? $_POST['description_en'] : '';

    // Lưu điều khoản vào bảng dieukhoan_ngonngu
    $result = updateTermsNgonNgu($post_id, [
        'title_vi' => $title_vi,
        'title_en' => $title_en,
        'description_vi' => $description_vi,
        'description_en' => $description_en,
    ]); // Hàm giả định, cần triển khai

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
