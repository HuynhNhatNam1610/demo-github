<?php
require_once "../../view/php/session.php";
require_once "../UserModel.php";

header('Content-Type: application/json');

$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;

try {
    // Lấy thông tin điều khoản
    $terms = getTermsNgonNgu($languageId, $id); // Hàm giả định, cần triển khai trong UserModel.php
    if ($terms) {
        $response = [
            'success' => true,
            'item' => [
                'id' => $terms['id'],
                'title_vi' => $terms['title_vi'],
                'title_en' => $terms['title_en'],
                'description_vi' => $terms['content_vi'], // CKEditor content
                'description_en' => $terms['content_en'], // CKEditor content
            ]
        ];
    } else {
        $response = ['success' => false, 'message' => 'Không tìm thấy điều khoản.'];
    }
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
