<?php
require_once "connect.php";
require_once "../UserModel.php";
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
header('Content-Type: application/json');
header('Content-Type: application/json');
header('Content-Type: application/json');

$active = isset($_GET['active']) ? (int)$_GET['active'] : 1;
$languageId = isset($_GET['language']) ? (int)$_GET['language'] : 1;

$rooms = getConferenceRooms($languageId, $active);

echo json_encode(['success' => true, 'items' => $rooms]);
mysqli_close($conn);
?>