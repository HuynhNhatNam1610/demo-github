<?php
require_once "../../model/RoomModel.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add_room':
            $room_number = $_POST['room_number'];
            $id_loaiphong = $_POST['id_loaiphong'];
            $status = $_POST['status'];
            $response = addRoom1($conn, $room_number, $id_loaiphong, $status);
            break;

        case 'update_room':
            $id = $_POST['room_id'];
            $room_number = $_POST['room_number'];
            $id_loaiphong = $_POST['id_loaiphong'];
            $status = $_POST['status'];
            $response = updateRoom1($conn, $id, $room_number, $id_loaiphong, $status);
            break;

        case 'bulk_update_status':
            $status = $_POST['status'] ?? '';
            $room_ids = $_POST['room_ids'] ?? [];
            $response = bulkUpdateStatus1($conn, $status, $room_ids);
            break;

        case 'delete_room':
            $id = $_POST['room_id'];
            $response = deleteRoom1($conn, $id);
            break;

        case 'checkout_room':
            $id = $_POST['room_id'];
            $response = checkoutRoom1($conn, $id);
            break;

        case 'add_room_type':
            $name_vi = $_POST['name_vi'];
            $name_en = $_POST['name_en'];
            $description_vi = $_POST['description_vi'];
            $description_en = $_POST['description_en'];
            $quantity = $_POST['quantity'];
            $area = $_POST['area'];
            $price = $_POST['price'];
            $images = $_FILES['images'];
            $response = addRoomType1($conn, $name_vi, $name_en, $description_vi, $description_en, $quantity, $area, $price, $images);
            break;

        case 'update_room_type':
            $id = $_POST['room_type_id'];
            $name_vi = $_POST['name_vi'];
            $name_en = $_POST['name_en'];
            $description_vi = $_POST['description_vi'];
            $description_en = $_POST['description_en'];
            $quantity = $_POST['quantity'];
            $area = $_POST['area'];
            $price = $_POST['price'];
            $new_images = $_FILES['new_images'];
            $delete_images = $_POST['delete_images'] ?? [];
            $response = updateRoomType1($conn, $id, $name_vi, $name_en, $description_vi, $description_en, $quantity, $area, $price, $new_images, $delete_images);
            break;

        case 'delete_room_type':
            $id = $_POST['room_type_id'];
            $response = deleteRoomType1($conn, $id);
            break;

        case 'fetch_room_types':
            $response = [
                'status' => 'success',
                'room_types' => getRoomTypes1($conn)
            ];
            break;

        default:
            $response = [
                'status' => 'error',
                'message' => 'Hành động không hợp lệ!'
            ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Lấy dữ liệu ban đầu cho View
$data = [
    'rooms' => getRooms1($conn),
    'room_types' => getRoomTypes1($conn),
    'stats' => getStats1($conn),
    'room_type_stats' => getRoomTypeStats1($conn)
];
?>