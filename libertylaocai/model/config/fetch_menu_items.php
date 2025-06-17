<?php
require_once "../../model/UserModel.php";
require_once "connect.php";

header('Content-Type: application/json');

$languageId = isset($_GET['language']) ? (int)$_GET['language'] : 1;
$active = isset($_GET['active']) ? (int)$_GET['active'] : 1;
$type = isset($_GET['type']) ? $_GET['type'] : '';
$id_amthuc = isset($_GET['id_amthuc']) ? (int)$_GET['id_amthuc'] : 1;

$response = ['success' => false, 'message' => 'Invalid request', 'items' => []];

try {
    if ($type === 'restaurant' && $id_amthuc === 1) {
        $items = getMenu($languageId, $id_amthuc, $active);
    } elseif ($type === 'main' || $type === 'cocktails') {
        $items = getMenuBar($languageId, $type, $active);
    } else {
        $response['message'] = 'Unsupported type';
        echo json_encode($response);
        exit;
    }

    if (empty($items)) {
        $response['message'] = 'No items found';
    } else {
        $response['success'] = true;
        $response['items'] = array_values($items); // Đảm bảo mảng có index liên tục
    }
} catch (Exception $e) {
    $response['message'] = 'Error fetching menu items: ' . $e->getMessage();
}

echo json_encode($response);
