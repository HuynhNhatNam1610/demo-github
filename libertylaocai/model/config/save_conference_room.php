<?php
require_once "connect.php";
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid request'];

// Ghi log dữ liệu POST và FILES nhận được
error_log("Received POST data: " . print_r($_POST, true));
error_log("Received FILES data: " . print_r($_FILES, true));

try {
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $room_number = isset($_POST['room_number']) ? trim($_POST['room_number']) : '';
    $capacity = isset($_POST['capacity']) ? (int)$_POST['capacity'] : 0;
    $area = isset($_POST['area']) ? (int)$_POST['area'] : 0;
    $floor_number = isset($_POST['floor_number']) ? (int)$_POST['floor_number'] : 0;
    $title_vi = isset($_POST['title_vi']) ? trim($_POST['title_vi']) : '';
    $description_vi = isset($_POST['description_vi']) ? $_POST['description_vi'] : '';
    $title_en = isset($_POST['title_en']) ? trim($_POST['title_en']) : '';
    $description_en = isset($_POST['description_en']) ? $_POST['description_en'] : '';
    $how_long = isset($_POST['how_long']) ? $_POST['how_long'] : [];
    $price_value = isset($_POST['price_value']) ? $_POST['price_value'] : [];
    $existing_images = isset($_POST['existing_images']) ? json_decode($_POST['existing_images'], true) : [];

    // Ghi log existing_images
    error_log("Received existing_images: " . print_r($existing_images, true));

    if (!$title_vi || !$title_en || !$room_number) {
        throw new Exception('Missing required fields');
    }

    mysqli_begin_transaction($conn);

    // Xử lý xóa ảnh cũ khi cập nhật hội trường
    if ($post_id > 0) {
        if (!empty($existing_images)) {
            $placeholders = implode(',', array_fill(0, count($existing_images), '?'));
            $sql = "DELETE FROM anhhoitruong WHERE id_hoitruong = ? AND image NOT IN ($placeholders)";
            $stmt = mysqli_prepare($conn, $sql);
            $params = array_merge([$post_id], $existing_images);
            $types = str_repeat('s', count($existing_images) + 1);
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            if (!mysqli_stmt_execute($stmt)) {
                error_log("SQL error deleting anhhoitruong: " . mysqli_error($conn));
                throw new Exception('Lỗi khi xóa ảnh cũ');
            }
            mysqli_stmt_close($stmt);
            error_log("Deleted anhhoitruong not in existing_images for id_hoitruong: $post_id");
        } else {
            $sql = "DELETE FROM anhhoitruong WHERE id_hoitruong = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $post_id);
            if (!mysqli_stmt_execute($stmt)) {
                error_log("SQL error deleting all anhhoitruong: " . mysqli_error($conn));
                throw new Exception('Lỗi khi xóa ảnh cũ');
            }
            mysqli_stmt_close($stmt);
            error_log("Deleted all anhhoitruong for id_hoitruong: $post_id");
        }
    }

    // Xử lý ảnh mới
    $image_paths = [];
    if (isset($_FILES['image']) && count($_FILES['image']['name']) > 0) {
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/libertylaocai/view/img/uploads/hoitruong/';
        $relative_path = 'Uploads/hoitruong/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
            error_log("Created upload directory: $upload_dir");
        } else {
            error_log("Upload directory exists: $upload_dir");
        }
        foreach ($_FILES['image']['name'] as $key => $name) {
            error_log("Processing file: $name, error code: " . $_FILES['image']['error'][$key]);
            if ($_FILES['image']['error'][$key] === UPLOAD_ERR_OK) {
                $image_name = time() . '_' . basename($name);
                $image_path = 'Uploads/hoitruong/' . $image_name;
                $dest_path = '../../view/img/' . $image_path;
                error_log("Attempting to move file to: $dest_path");
                if (!move_uploaded_file($_FILES['image']['tmp_name'][$key], $dest_path)) {
                    error_log("Failed to move file: $name to $dest_path");
                    throw new Exception('Không thể tải lên hình ảnh');
                }
                $image_paths[] = $relative_path . $image_name;
                error_log("File moved successfully: $image_path");
            } else {
                error_log("File upload error for $name: " . $_FILES['image']['error'][$key]);
            }
        }
    } else {
        error_log("No image files received or empty image array");
    }
    // $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    // $room_number = isset($_POST['room_number']) ? trim($_POST['room_number']) : '';
    // $capacity = isset($_POST['capacity']) ? (int)$_POST['capacity'] : 0;
    // $area = isset($_POST['area']) ? (int)$_POST['area'] : 0;
    // $floor_number = isset($_POST['floor_number']) ? (int)$_POST['floor_number'] : 0;
    // $title_vi = isset($_POST['title_vi']) ? trim($_POST['title_vi']) : '';
    // $description_vi = isset($_POST['description_vi']) ? $_POST['description_vi'] : '';
    // $title_en = isset($_POST['title_en']) ? trim($_POST['title_en']) : '';
    // $description_en = isset($_POST['description_en']) ? $_POST['description_en'] : '';
    // $how_long = isset($_POST['how_long']) ? $_POST['how_long'] : [];
    // $price_value = isset($_POST['price_value']) ? $_POST['price_value'] : [];

    // // Ghi log các biến sau khi xử lý
    // error_log("Processed variables: post_id=$post_id, room_number=$room_number, capacity=$capacity, area=$area, floor_number=$floor_number");
    // error_log("Titles: title_vi=$title_vi, title_en=$title_en");
    // error_log("Descriptions: description_vi=$description_vi, description_en=$description_en");
    // error_log("Price data: how_long=" . print_r($how_long, true) . ", price_value=" . print_r($price_value, true));

    // if (!$title_vi || !$title_en || !$room_number) {
    //     throw new Exception('Missing required fields');
    // }

    // mysqli_begin_transaction($conn);

    // // Xử lý ảnh
    // $image_paths = [];
    // if (isset($_FILES['image']) && count($_FILES['image']['name']) > 0) {
    //     $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/libertylaocai/view/img/uploads/hoitruong/';
    //     $relative_path = 'uploads/hoitruong/';
    //     if (!is_dir($upload_dir)) {
    //         mkdir($upload_dir, 0755, true);
    //         error_log("Created upload directory: $upload_dir");
    //     } else {
    //         error_log("Upload directory exists: $upload_dir");
    //     }
    //     foreach ($_FILES['image']['name'] as $key => $name) {
    //         error_log("Processing file: $name, error code: " . $_FILES['image']['error'][$key]);
    //         if ($_FILES['image']['error'][$key] === UPLOAD_ERR_OK) {
    //             $image_name = time() . '_' . basename($name);
    //             $image_path = 'uploads/hoitruong/' . $image_name;
    //             $dest_path = '../../view/img/' . $image_path;
    //             error_log("Attempting to move file to: $dest_path");
    //             if (!move_uploaded_file($_FILES['image']['tmp_name'][$key], $dest_path)) {
    //                 error_log("Failed to move file: $name to $dest_path");
    //                 throw new Exception('Không thể tải lên hình ảnh');
    //             }
    //             $image_paths[] = $relative_path . $image_name;
    //             error_log("File moved successfully: $image_path");
    //         } else {
    //             error_log("File upload error for $name: " . $_FILES['image']['error'][$key]);
    //         }
    //     }
    // } else {
    //     error_log("No image files received or empty image array");
    // }

    // Ghi log image_paths
    error_log("Image paths to be saved: " . print_r($image_paths, true));

    if ($post_id > 0) {
        // Cập nhật hội trường
        $sql = "UPDATE hoitruong SET room_number = ?, opacity = ?, area = ?, floor_number = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "siiii", $room_number, $capacity, $area, $floor_number, $post_id);
        if (!mysqli_stmt_execute($stmt)) {
            error_log("SQL error updating hoitruong: " . mysqli_error($conn));
            throw new Exception('Lỗi khi cập nhật hội trường');
        }
        mysqli_stmt_close($stmt);
        error_log("Updated hoitruong with id: $post_id");

        // Cập nhật nội dung tiếng Việt
        $sql_vi = "UPDATE hoitruong_ngonngu SET name = ?, description = ? WHERE id_hoitruong = ? AND id_ngonngu = 1";
        $stmt_vi = mysqli_prepare($conn, $sql_vi);
        mysqli_stmt_bind_param($stmt_vi, "ssi", $title_vi, $description_vi, $post_id);
        if (!mysqli_stmt_execute($stmt_vi)) {
            error_log("SQL error updating hoitruong_ngonngu (VI): " . mysqli_error($conn));
            throw new Exception('Lỗi khi cập nhật tiếng Việt');
        }
        mysqli_stmt_close($stmt_vi);
        error_log("Updated hoitruong_ngonngu (VI) for id_hoitruong: $post_id");

        // Cập nhật nội dung tiếng Anh
        $sql_en = "UPDATE hoitruong_ngonngu SET name = ?, description = ? WHERE id_hoitruong = ? AND id_ngonngu = 2";
        $stmt_en = mysqli_prepare($conn, $sql_en);
        mysqli_stmt_bind_param($stmt_en, "ssi", $title_en, $description_en, $post_id);
        if (!mysqli_stmt_execute($stmt_en)) {
            if (mysqli_stmt_affected_rows($stmt_en) === 0) {
                $sql_insert_en = "INSERT INTO hoitruong_ngonngu (id_hoitruong, id_ngonngu, name, description) VALUES (?, 2, ?, ?)";
                $stmt_insert_en = mysqli_prepare($conn, $sql_insert_en);
                mysqli_stmt_bind_param($stmt_insert_en, "iss", $post_id, $title_en, $description_en);
                if (!mysqli_stmt_execute($stmt_insert_en)) {
                    error_log("SQL error inserting hoitruong_ngonngu (EN): " . mysqli_error($conn));
                    throw new Exception('Lỗi khi thêm tiếng Anh');
                }
                mysqli_stmt_close($stmt_insert_en);
                error_log("Inserted hoitruong_ngonngu (EN) for id_hoitruong: $post_id");
            }
        }
        mysqli_stmt_close($stmt_en);
        error_log("Updated hoitruong_ngonngu (EN) for id_hoitruong: $post_id");

        // Xóa giá thuê cũ
        $sql = "DELETE FROM giathuehoitruong WHERE id_hoitruong = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $post_id);
        if (!mysqli_stmt_execute($stmt)) {
            error_log("SQL error deleting giathuehoitruong: " . mysqli_error($conn));
            throw new Exception('Lỗi khi xóa giá thuê cũ');
        }
        mysqli_stmt_close($stmt);
        error_log("Deleted old giathuehoitruong for id_hoitruong: $post_id");

        // Thêm giá thuê mới
        foreach ($how_long as $index => $time) {
            if ($time && $price_value[$index]) {
                $sql = "INSERT INTO giathuehoitruong (id_hoitruong, how_long, price) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "isi", $post_id, $time, $price_value[$index]);
                if (!mysqli_stmt_execute($stmt)) {
                    error_log("SQL error inserting giathuehoitruong: " . mysqli_error($conn));
                    throw new Exception('Lỗi khi thêm giá thuê');
                }
                mysqli_stmt_close($stmt);
                error_log("Inserted giathuehoitruong: how_long=$time, price=" . $price_value[$index]);
            }
        }

        // Thêm ảnh mới
        foreach ($image_paths as $image) {
            $sql = "INSERT INTO anhhoitruong (image, active, created_at, id_topic, id_hoitruong) VALUES (?, 1, NOW(), 11, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $image, $post_id);
            if (!mysqli_stmt_execute($stmt)) {
                error_log("SQL error inserting anhhoitruong: " . mysqli_error($conn));
                throw new Exception('Lỗi khi thêm ảnh');
            }
            mysqli_stmt_close($stmt);
            error_log("Inserted anhhoitruong: image=$image, id_hoitruong=$post_id");
        }
    } else {
        // Thêm hội trường mới
        $sql = "INSERT INTO hoitruong (room_number, opacity, area, floor_number) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "siii", $room_number, $capacity, $area, $floor_number);
        if (!mysqli_stmt_execute($stmt)) {
            error_log("SQL error inserting hoitruong: " . mysqli_error($conn));
            throw new Exception('Lỗi khi thêm hội trường');
        }
        $post_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        error_log("Inserted hoitruong with id: $post_id");

        // Thêm nội dung tiếng Việt
        $sql_vi = "INSERT INTO hoitruong_ngonngu (id_hoitruong, id_ngonngu, name, description) VALUES (?, 1, ?, ?)";
        $stmt_vi = mysqli_prepare($conn, $sql_vi);
        mysqli_stmt_bind_param($stmt_vi, "iss", $post_id, $title_vi, $description_vi);
        if (!mysqli_stmt_execute($stmt_vi)) {
            error_log("SQL error inserting hoitruong_ngonngu (VI): " . mysqli_error($conn));
            throw new Exception('Lỗi khi thêm tiếng Việt');
        }
        mysqli_stmt_close($stmt_vi);
        error_log("Inserted hoitruong_ngonngu (VI) for id_hoitruong: $post_id");

        // Thêm nội dung tiếng Anh
        if ($title_en || $description_en) {
            $sql_en = "INSERT INTO hoitruong_ngonngu (id_hoitruong, id_ngonngu, name, description) VALUES (?, 2, ?, ?)";
            $stmt_en = mysqli_prepare($conn, $sql_en);
            mysqli_stmt_bind_param($stmt_en, "iss", $post_id, $title_en, $description_en);
            if (!mysqli_stmt_execute($stmt_en)) {
                error_log("SQL error inserting hoitruong_ngonngu (EN): " . mysqli_error($conn));
                throw new Exception('Lỗi khi thêm tiếng Anh');
            }
            mysqli_stmt_close($stmt_en);
            error_log("Inserted hoitruong_ngonngu (EN) for id_hoitruong: $post_id");
        }

        // Thêm giá thuê
        foreach ($how_long as $index => $time) {
            if ($time && $price_value[$index]) {
                $sql = "INSERT INTO giathuehoitruong (id_hoitruong, how_long, price) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "isi", $post_id, $time, $price_value[$index]);
                if (!mysqli_stmt_execute($stmt)) {
                    error_log("SQL error inserting giathuehoitruong: " . mysqli_error($conn));
                    throw new Exception('Lỗi khi thêm giá thuê');
                }
                mysqli_stmt_close($stmt);
                error_log("Inserted giathuehoitruong: how_long=$time, price=" . $price_value[$index]);
            }
        }

        // Ghi log trước khi lưu ảnh
        error_log("Saving images: " . print_r($image_paths, true) . ", post_id: $post_id");

        // Thêm ảnh
        foreach ($image_paths as $image) {
            $sql = "INSERT INTO anhhoitruong (image, active, created_at, id_topic, id_hoitruong) VALUES (?, 1, NOW(), 11, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $image, $post_id);
            if (!mysqli_stmt_execute($stmt)) {
                error_log("SQL error inserting anhhoitruong: " . mysqli_error($conn));
                throw new Exception('Lỗi khi thêm ảnh');
            }
            mysqli_stmt_close($stmt);
            error_log("Inserted anhhoitruong: image=$image, id_hoitruong=$post_id");
        }
    }

    mysqli_commit($conn);
    $response['success'] = true;
    $response['message'] = 'Conference room saved successfully';
} catch (Exception $e) {
    mysqli_rollback($conn);
    $response['message'] = 'Error: ' . $e->getMessage();
    error_log("Error in save_conference_room.php: " . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());
}

echo json_encode($response);
mysqli_close($conn);
