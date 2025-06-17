<?php
require_once "connect.php";

header('Content-Type: application/json');

$response = ['success' => false, 'max_room_number' => 0];

try {
    $sql = "SELECT MAX(room_number) as max_room_number FROM hoitruong";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $response['success'] = true;
        $response['max_room_number'] = $row['max_room_number'] ? (int)$row['max_room_number'] : 0;
    } else {
        throw new Exception('Lỗi khi truy vấn cơ sở dữ liệu');
    }
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
    error_log("Error in get_max_room_number.php: " . $e->getMessage());
}

echo json_encode($response);
mysqli_close($conn);
?>