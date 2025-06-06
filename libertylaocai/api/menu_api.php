<?php
require_once "../model/UserModel.php";

header('Content-Type: application/json');

$languageId = isset($_GET['language_id']) ? (int)$_GET['language_id'] : 1;
$type = isset($_GET['type']) ? $_GET['type'] : 'drink';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 9;


$response = getMenuItemsByType($languageId, $type, $page, $limit);
echo json_encode([
    'status' => 'success',
    'data' => $response
]);
?>