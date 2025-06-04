<?php
require_once "../view/php/session.php";
require_once "../model/UserModel.php";

header('Content-Type: application/json');

$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = 9; // Số món ăn mỗi trang
$id_amthuc = 1;

$menuData = getAllMenuImages($languageId, $id_amthuc, $page, $limit);

echo json_encode([
    'status' => 'success',
    'data' => $menuData
]);
