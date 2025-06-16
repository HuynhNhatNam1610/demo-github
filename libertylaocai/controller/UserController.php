<?php
require_once '../model/UserModel.php';
require_once '../view/php/session.php';
require_once '../model/mail/sendmail.php';
error_reporting(E_ALL);
// // ini_set('log_errors', 1);
ini_set('error_log', 'debug.log');
// Kiểm tra ngôn ngữ từ session, mặc định là 1 (tiếng Việt)
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;

// Hàm trả về thông điệp đa ngôn ngữ
function getMessage($key, $languageId)
{
    $messages = [
        'missing_field' => [
            1 => 'Thiếu trường bắt buộc: ',
            2 => 'Missing required field: '
        ],
        'invalid_email' => [
            1 => 'Email không đúng định dạng',
            2 => 'Invalid email format'
        ],
        'invalid_phone' => [
            1 => 'Số điện thoại không hợp lệ',
            2 => 'Invalid phone number'
        ],
        'past_start_date' => [
            1 => 'Ngày bắt đầu không thể là ngày trong quá khứ',
            2 => 'Start date cannot be in the past'
        ],
        'invalid_end_date' => [
            1 => 'Ngày kết thúc không thể trước ngày bắt đầu',
            2 => 'End date cannot be before start date'
        ],
        'booking_success' => [
            1 => 'Gửi yêu cầu thành công! Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.',
            2 => 'Request sent successfully! We will contact you as soon as possible.'
        ],
        'booking_failed' => [
            1 => 'Có lỗi khi gửi yêu cầu. Vui lòng thử lại.',
            2 => 'An error occurred while sending the request. Please try again.'
        ]
    ];

    return $messages[$key][$languageId] ?? $messages[$key][2]; // Mặc định tiếng Anh nếu không tìm thấy
}

function to_slug($str)
{
    // Chuyển tiếng Việt có dấu thành không dấu
    $str = mb_strtolower($str, 'UTF-8');
    $str = preg_replace('/[áàảãạâấầẩẫậăắằẳẵặ]/u', 'a', $str);
    $str = preg_replace('/[éèẻẽẹêếềểễệ]/u', 'e', $str);
    $str = preg_replace('/[iíìỉĩị]/u', 'i', $str);
    $str = preg_replace('/[óòỏõọôốồổỗộơớờởỡợ]/u', 'o', $str);
    $str = preg_replace('/[úùủũụưứừửữự]/u', 'u', $str);
    $str = preg_replace('/[ýỳỷỹỵ]/u', 'y', $str);
    $str = preg_replace('/đ/u', 'd', $str);

    // Loại bỏ ký tự đặc biệt
    $str = preg_replace('/[^a-z0-9\s-]/', '', $str);

    // Thay dấu cách bằng gạch ngang
    $str = preg_replace('/[\s]+/', '-', $str);

    return trim($str, '-');
}

function to_short_slug($productName, $slug)
{
    $words = explode(" ", $productName);
    return to_slug(implode("-", array_slice($words, 0, $slug)));
}

//function tạo mã OTP
function generateOTP($length = 6)
{
    $min = pow(10, $length - 1);
    $max = pow(10, $length) - 1;
    return sprintf("%0{$length}d", mt_rand($min, $max));
}

function uploadImage($file)
{
    $targetDir = "../view/img/uploads/dichvu/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $uniqueName = uniqid() . '.' . $imageFileType;
    $targetFile = $targetDir . $uniqueName;

    // $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    // if (!in_array($imageFileType, $allowedTypes)) {
    //     return false;
    // }

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $uniqueName;
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'add_feature') {
        $response = ['success' => false, 'message' => ''];

        try {
            // Lấy và thoát dữ liệu từ POST
            $icon = mysqli_real_escape_string($conn, $_POST['icon']);
            $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi']);
            $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
            $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
            $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');

            // Gọi hàm addFeature
            $response = addFeature($icon, $title_vi, $content_vi, $title_en, $content_en);
        } catch (Exception $e) {
            $response['message'] = "Lỗi server: " . $e->getMessage();
            $response['success'] = false;
        }

        // Xóa bất kỳ đầu ra nào trước khi gửi JSON
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    if ($action === 'update_feature') {
        $response = ['success' => false, 'message' => ''];

        try {
            // Lấy và thoát dữ liệu từ POST
            $id_tienich = (int)$_POST['id_tienich'];
            $icon = mysqli_real_escape_string($conn, $_POST['icon']);
            $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi']);
            $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
            $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
            $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');

            // Kiểm tra icon
            if (empty($icon)) {
                $response['message'] = "Vui lòng chọn hoặc nhập biểu tượng!";
                $response['success'] = false;
            }

            // Gọi hàm updateFeature
            $response = updateFeature($conn, $id_tienich, $icon, $title_vi, $content_vi, $title_en, $content_en);
        } catch (Exception $e) {
            $response['message'] = "Lỗi server: " . $e->getMessage();
            $response['success'] = false;
        }

        // Xóa bất kỳ đầu ra nào trước khi gửi JSON
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    if ($action === 'delete_feature') {
        $response = ['success' => false, 'message' => ''];

        try {
            // Lấy id_tienich
            $id_tienich = (int)$_POST['id_tienich'];

            // Gọi hàm deleteFeature
            $response = deleteFeature($id_tienich);
        } catch (Exception $e) {
            $response['message'] = "Lỗi server: " . $e->getMessage();
            $response['success'] = false;
        }

        // Xóa bất kỳ đầu ra nào trước khi gửi JSON
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    if ($action === 'add_tour') {
        $response = ['success' => false, 'message' => ''];

        // Bắt đầu transaction
        $conn->autocommit(false);

        try {
            // Sanitize input data từ $_POST
            $title_vi = isset($_POST['title_vi']) ? mysqli_real_escape_string($conn, $_POST['title_vi']) : '';
            $content_vi = isset($_POST['content_vi']) ? mysqli_real_escape_string($conn, $_POST['content_vi']) : '';
            $title_en = isset($_POST['title_en']) ? mysqli_real_escape_string($conn, $_POST['title_en']) : '';
            $content_en = isset($_POST['content_en']) ? mysqli_real_escape_string($conn, $_POST['content_en']) : '';
            $price_vi = isset($_POST['price_vi']) ? mysqli_real_escape_string($conn, $_POST['price_vi']) : 'Liên hệ';

            // Kiểm tra dữ liệu bắt buộc
            if (empty($title_vi) || empty($content_vi)) {
                throw new Exception("Tiêu đề và nội dung tiếng Việt là bắt buộc!");
            }

            // Thêm dịch vụ tour
            $id_dichvu = addTourService($price_vi);
            if (!$id_dichvu) {
                throw new Exception("Lỗi khi thêm dịch vụ: " . $conn->error);
            }

            // Thêm thông tin tiếng Việt
            if (!addTourLanguage($id_dichvu, 1, $title_vi, $content_vi)) {
                throw new Exception("Lỗi khi thêm thông tin tiếng Việt: " . $conn->error);
            }

            // Thêm thông tin tiếng Anh (nếu có)
            if (!empty($title_en) || !empty($content_en)) {
                if (!addTourLanguage($id_dichvu, 2, $title_en, $content_en)) {
                    throw new Exception("Lỗi khi thêm thông tin tiếng Anh: " . $conn->error);
                }
            }
            // Xử lý upload ảnh (nếu có)
            if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] === 0) {
                $imageName = uploadImage($_FILES['service_image']);
                if ($imageName) {
                    // $stmt = $conn->prepare("INSERT INTO anhdichvu (image, is_primary, id_dichvu, id_topic) VALUES (?, 1, ?, 3)");
                    // $stmt->bind_param("si", $imageName, $id_dichvu);
                    if (!insertService($imageName, $id_dichvu)) {
                        throw new Exception("Lỗi khi thêm ảnh: " . $conn->error);
                    }
                }
            }

            // Commit transaction
            $conn->commit();
            $response['success'] = true;
            $response['message'] = "Thêm tour thành công!";
            $response['id_dichvu'] = $id_dichvu;
        } catch (Exception $e) {
            // Rollback transaction
            $conn->rollback();
            $response['message'] = $e->getMessage();
        }

        // Khôi phục autocommit
        $conn->autocommit(true);
        // $stmt->close();

        // Gửi phản hồi JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    if ($action === 'update_tour') {
        $response = ['success' => false, 'message' => ''];

        try {
            // Lấy và thoát dữ liệu từ POST
            $id_dichvu = (int)$_POST['id_dichvu'];
            $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi']);
            $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
            $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
            $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');
            $price_vi = mysqli_real_escape_string($conn, $_POST['price_vi'] ?? 'Liên hệ');
            $image_file = isset($_FILES['service_image']) ? $_FILES['service_image'] : null;

            // Kiểm tra dữ liệu bắt buộc
            if (empty($title_vi) || empty($content_vi)) {
                $response['message'] = "Tiêu đề và nội dung tiếng Việt là bắt buộc!";
                $response['success'] = false;
            }

            // Gọi hàm updateTour
            if (updateTour($id_dichvu, $title_vi, $content_vi, $title_en, $content_en, $price_vi, $image_file)) {
                $response['success'] = true;
                $response['message'] = "Cập nhật tour thành công!";
            } else {
                $response['message'] = "Lỗi khi cập nhật tour!";
            }
        } catch (Exception $e) {
            $response['message'] = "Lỗi server: " . $e->getMessage();
        }

        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($action === 'delete_tour') {
        $response = ['success' => false, 'message' => ''];

        try {
            // Lấy id_dichvu
            $id_dichvu = (int)$_POST['id_dichvu'];

            // Gọi hàm deleteTour
            if (deleteTour($id_dichvu)) {
                $response['success'] = true;
                $response['message'] = "Xóa tour thành công!";
            } else {
                $response['message'] = "Lỗi khi xóa tour!";
            }
        } catch (Exception $e) {
            $response['message'] = "Lỗi server: " . $e->getMessage();
        }
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($action === 'add_service') {
        $response = ['success' => false, 'message' => ''];

        try {
            // Lấy và thoát dữ liệu từ POST
            $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi']);
            $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
            $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
            $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');
            $price_vi = mysqli_real_escape_string($conn, $_POST['price_vi'] ?? 'Liên hệ');
            $image_file = isset($_FILES['service_image']) ? $_FILES['service_image'] : null;

            // Kiểm tra dữ liệu bắt buộc
            if (empty($title_vi) || empty($content_vi)) {
                $response['message'] = "Tiêu đề và nội dung tiếng Việt là bắt buộc!";
            }

            // Gọi hàm addService
            if (addService($title_vi, $content_vi, $title_en, $content_en, $price_vi, $image_file)) {
                $response['success'] = true;
                $response['message'] = "Thêm dịch vụ thành công!";
            } else {
                $response['message'] = "Lỗi khi thêm dịch vụ!";
            }
        } catch (Exception $e) {
            $response['message'] = "Lỗi server: " . $e->getMessage();
        }

        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($action === 'update_service') {
        $response = ['success' => false, 'message' => ''];

        try {
            // Lấy và thoát dữ liệu từ POST
            $id_dichvu = (int)$_POST['id_dichvu'];
            $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi']);
            $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
            $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
            $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');
            $price_vi = mysqli_real_escape_string($conn, $_POST['price_vi'] ?? 'Liên hệ');
            $image_file = isset($_FILES['service_image']) ? $_FILES['service_image'] : null;

            // Kiểm tra dữ liệu bắt buộc
            if (empty($title_vi) || empty($content_vi)) {
                $response['message'] = "Tiêu đề và nội dung tiếng Việt là bắt buộc!";
                ob_clean();
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Gọi hàm updateService
            if (updateService($id_dichvu, $title_vi, $content_vi, $title_en, $content_en, $price_vi, $image_file)) {
                $response['success'] = true;
                $response['message'] = "Cập nhật dịch vụ thành công!";
            } else {
                $response['message'] = "Lỗi khi cập nhật dịch vụ!";
            }
        } catch (Exception $e) {
            $response['message'] = "Lỗi server: " . $e->getMessage();
        }

        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($action === 'delete_service') {
        $response = ['success' => false, 'message' => ''];

        try {
            // Lấy id_dichvu
            $id_dichvu = (int)$_POST['id_dichvu'];

            // Gọi hàm deleteService
            if (deleteService($id_dichvu)) {
                $response['success'] = true;
                $response['message'] = "Xóa dịch vụ thành công!";
            } else {
                $response['message'] = "Lỗi khi xóa dịch vụ!";
            }
        } catch (Exception $e) {
            $response['message'] = "Lỗi server: " . $e->getMessage();
        }

        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }


    // Xử lý yêu cầu AJAX lấy dữ liệu tiếng Anh của tiện ích
    if ($action === 'get_feature_en') {

        $id_tienich = (int)$_POST['id_tienich'];

        // Gọi hàm để lấy dữ liệu
        $data = layTienIchNgonNgu($id_tienich);

        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    // Xử lý hành động add_room
    if ($action === 'add_room') {
        $room_number = $_POST['room_number'] ?? '';
        $id_loaiphong = $_POST['id_loaiphong'] ?? '';
        $status = $_POST['status'] ?? '';

        $result = addRoom($conn, $room_number, $id_loaiphong, $status);
        echo json_encode(array_merge($result, [
            'rooms' => getRooms($conn),
            'stats' => getStats($conn),
            'room_type_stats' => getRoomTypeStats($conn),
            'room_types' => getRoomTypes1($conn)
        ]));
        exit;
    }

    if ($action === 'bulk_update_status') {
        $status = $_POST['status'] ?? '';
        $room_ids = $_POST['room_ids'] ?? [];

        if (empty($status) || empty($room_ids)) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Vui lòng chọn trạng thái và ít nhất một phòng!'
            ]);
            exit;
        }

        if (!in_array($status, ['available', 'reserved', 'maintenance', 'pending'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Trạng thái không hợp lệ!'
            ]);
            exit;
        }

        $result = bulkUpdateRoomStatus($conn, $status, $room_ids);
        echo json_encode(array_merge($result, [
            'rooms' => getRooms($conn),
            'stats' => getStats($conn),
            'room_type_stats' => getRoomTypeStats($conn)
        ]));
        exit;
    }

    // Xử lý hành động update_room
    if ($action === 'update_room') {
        $id = $_POST['room_id'] ?? '';
        $room_number = $_POST['room_number'] ?? '';
        $id_loaiphong = $_POST['id_loaiphong'] ?? '';
        $status = $_POST['status'] ?? '';
        $phone = ($status === 'reserved') ? ($_POST['phone'] ?? '') : '';

        $result = updateRoom($conn, $id, $room_number, $id_loaiphong, $status, $phone);
        echo json_encode(array_merge($result, [
            'rooms' => getRooms($conn),
            'stats' => getStats($conn),
            'room_type_stats' => getRoomTypeStats($conn),
            'room_types' => getRoomTypes1($conn)
        ]));
        exit;
    }

    // Xử lý hành động delete_room
    if ($action === 'delete_room') {
        $id = $_POST['room_id'] ?? '';
        $result = deleteRoom($conn, $id);
        echo json_encode(array_merge($result, [
            'rooms' => getRooms($conn),
            'stats' => getStats($conn),
            'room_type_stats' => getRoomTypeStats($conn),
            'room_types' => getRoomTypes1($conn)
        ]));
        exit;
    }

    // Xử lý hành động add_room_type
    if ($action === 'add_room_type') {
        $name_vi = $_POST['name_vi'] ?? '';
        $name_en = $_POST['name_en'] ?? '';
        $description_vi = $_POST['description_vi'] ?? '';
        $description_en = $_POST['description_en'] ?? '';
        $quantity = $_POST['quantity'] ?? '';
        $area = $_POST['area'] ?? '';
        $price = $_POST['price'] ?? '';
        $images = $_FILES['images'] ?? ['name' => [], 'size' => []];

        $result = addRoomType($conn, $name_vi, $name_en, $description_vi, $description_en, $quantity, $area, $price, $images);
        echo json_encode(array_merge($result, [
            'room_types' => getRoomTypes1($conn),
            'room_type_stats' => getRoomTypeStats($conn)
        ]));
        exit;
    }

    // Xử lý hành động update_room_type
    if ($action === 'update_room_type') {
        $id = $_POST['room_type_id'] ?? '';
        $name_vi = $_POST['name_vi'] ?? '';
        $name_en = $_POST['name_en'] ?? '';
        $description_vi = $_POST['description_vi'] ?? '';
        $description_en = $_POST['description_en'] ?? '';
        $quantity = $_POST['quantity'] ?? '';
        $area = $_POST['area'] ?? '';
        $price = $_POST['price'] ?? '';
        $delete_images = $_POST['delete_images'] ?? [];
        $new_images = $_FILES['new_images'] ?? ['name' => [], 'size' => []];

        $result = updateRoomType($conn, $id, $name_vi, $name_en, $description_vi, $description_en, $quantity, $area, $price, $delete_images, $new_images);
        echo json_encode(array_merge($result, [
            'room_types' => getRoomTypes1($conn),
            'room_type_stats' => getRoomTypeStats($conn)
        ]));
        exit;
    }

    // Xử lý hành động delete_room_type
    if ($action === 'delete_room_type') {
        $id = $_POST['room_type_id'] ?? '';
        $result = deleteRoomType($conn, $id);
        echo json_encode(array_merge($result, [
            'room_types' => getRoomTypes1($conn),
            'room_type_stats' => getRoomTypeStats($conn),
            'rooms' => getRooms($conn)
        ]));
        exit;
    }

    // Xử lý hành động fetch_room_types
    if ($action === 'fetch_room_types') {
        echo json_encode([
            'status' => 'success',
            'room_types' => getRoomTypes1($conn)
        ]);
        exit;
    }

    //danh mục header
    if (isset($_POST['category_code'])) {
        $categoryCode = $_POST['category_code'];
        if ($categoryCode === 'event') {
            $_SESSION['head_banner'] = getSelectedBanner('event', 'event-banner');
        } elseif ($categoryCode === 'nhahang&bar') {
            $_SESSION['head_banner'] = getSelectedBanner('nhahang&bar', 'service-banner');
        } elseif ($categoryCode === 'khuyen-mai') {
            $_SESSION['head_banner'] = getSelectedBanner('sale', 'sale-banner');
        } elseif ($categoryCode === 'thu-vien') {
            $_SESSION['head_banner'] = getSelectedBanner('thu-vien', 'gallery-banner');
        } elseif ($categoryCode === 'dat-phong') {
            $_SESSION['head_banner'] = getSelectedBanner('dat-phong', 'hero-content');
        } elseif ($categoryCode === 'dich-vu') {
            $_SESSION['head_banner'] = getSelectedBanner('dichvu', 'hero-background');
        }
        header("location: /libertylaocai/$categoryCode");
        exit();
    }

    if (isset($_POST['datlichngay'])) {
        $_SESSION['head_banner'] = getSelectedBanner('event', 'event-banner');
        header("location: /libertylaocai/event");
        exit();
    }

    if (isset($_POST['find_room'])) {
        $_SESSION['head_banner'] = getSelectedBanner('dat-phong', 'hero-content');
        header("location: /libertylaocai/dat-phong");
        exit();
    }

    //tiểu mục header
    if (isset($_POST['subcategory_code'])) {
        $subcategory_code = $_POST['subcategory_code'];
        $_SESSION['type_event'] = $subcategory_code;
        if ($subcategory_code === 'phong-don' || $subcategory_code === 'phong-doi' || $subcategory_code === 'phong-triple' || $subcategory_code === 'phong-gia-dinh') {
            if ($subcategory_code === 'phong-don') {
                $room_id = 1;
            } elseif ($subcategory_code === 'phong-doi') {
                $room_id = 2;
            } elseif ($subcategory_code === 'phong-triple') {
                $room_id = 3;
            } elseif ($subcategory_code === 'phong-gia-dinh') {
                $room_id = 4;
            }
            $room_name = getRoomDetail($room_id, 1)['name'];
            $images = getImagesForRoom($room_id);
            $_SESSION['images'] = $images;
            $_SESSION['room_info'] = [
                'room_id' => $room_id,
                'images' => $images
            ];
            header("location: /libertylaocai/" . to_slug($room_name));
            exit();
        } elseif ($subcategory_code === 'hoi-nghi') {            ///////////////// Pagedetail
            $_SESSION['head_banner'] = getSelectedBanner('pagedetail', 'pagedetail-banner-conference');
            $_SESSION['image_organized_event'] = getImageOrganizedEvents('hoi-nghi',  10);
        } elseif ($subcategory_code === 'tiec-cuoi') {
            $_SESSION['head_banner'] = getSelectedBanner('pagedetail', 'pagedetail-banner-wedding');
            $_SESSION['image_organized_event'] = getImageOrganizedEvents('tiec-cuoi', 10);
        } elseif ($subcategory_code === 'sinh-nhat') {
            $_SESSION['head_banner'] = getSelectedBanner('pagedetail', 'pagedetail-banner-birthday');
            $_SESSION['image_organized_event'] = getImageOrganizedEvents('sinh-nhat', 10);
        } elseif ($subcategory_code === 'gala-dinner') {
            $_SESSION['head_banner'] = getSelectedBanner('pagedetail', 'pagedetail-banner-gala');
            $_SESSION['image_organized_event'] = getImageOrganizedEvents('gala-dinner', 10);
        } elseif ($subcategory_code === 'nha-hang') {            /////// nhahang
            $_SESSION['restaurant_images'] = getRestaurantImages();
        } elseif ($subcategory_code === 'sky-bar') {             /////// bar

        } elseif ($subcategory_code === 'dua-don' || $subcategory_code === 'tour-sapa' || $subcategory_code === 'tour-bac-ha' || $subcategory_code === 'tour-y-ty' || $subcategory_code === 'tour-ha-khau') {            ////duadonsanbay
            if ($subcategory_code === 'dua-don') {
                $id_dichvu = 2;
            } elseif ($subcategory_code === 'tour-sapa') {
                $id_dichvu = 10;
            } elseif ($subcategory_code === 'tour-bac-ha') {
                $id_dichvu = 3;
            } elseif ($subcategory_code === 'tour-y-ty') {
                $id_dichvu = 4;
            } elseif ($subcategory_code === 'tour-ha-khau') {
                $id_dichvu = 5;
            }
            $service = getServiceById($languageId, $id_dichvu);
            $_SESSION['id_dichvu'] = $id_dichvu;
            header("location: /libertylaocai/dich-vu/" . to_short_slug($service['info']['title'], 5));
            exit;
        }

        header("location: /libertylaocai/$subcategory_code");
        exit();
    }

    if (isset($_POST['event_code'])) {
        $event_code = $_POST['event_code'];
        $_SESSION['type_event'] = $event_code;
        if ($event_code === 'hoi-nghi') {
            $_SESSION['head_banner'] = getSelectedBanner('pagedetail', 'pagedetail-banner-conference');
            $_SESSION['image_organized_event'] = getImageOrganizedEvents('hoi-nghi',  10);
        } elseif ($event_code === 'tiec-cuoi') {
            $_SESSION['head_banner'] = getSelectedBanner('pagedetail', 'pagedetail-banner-wedding');
            $_SESSION['image_organized_event'] = getImageOrganizedEvents('tiec-cuoi', 10);
        } elseif ($event_code === 'sinh-nhat') {
            $_SESSION['head_banner'] = getSelectedBanner('pagedetail', 'pagedetail-banner-birthday');
            $_SESSION['image_organized_event'] = getImageOrganizedEvents('sinh-nhat', 10);
        } elseif ($event_code === 'gala-dinner') {
            $_SESSION['head_banner'] = getSelectedBanner('pagedetail', 'pagedetail-banner-gala');
            $_SESSION['image_organized_event'] = getImageOrganizedEvents('gala-dinner', 10);
        }

        header("location: /libertylaocai/$event_code");
        exit();
    }

    if (isset($_POST['submit_booking'])) {
        header('Content-Type: application/json');

        // Validate required fields
        $requiredFields = ['fullName', 'phone', 'email', 'eventType', 'guestCount', 'eventDate', 'endDate', 'startTime', 'endTime', 'venue', 'description'];
        $missingFields = [];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $missingFields[] = $field;
            }
        }
        if (!empty($missingFields)) {
            echo json_encode([
                'status' => 'error',
                'message' => getMessage('missing_field', $languageId) . implode(', ', $missingFields)
            ]);
            exit();
        }

        // Sanitize input
        $fullName = filter_var($_POST['fullName'], FILTER_SANITIZE_STRING);
        $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $eventType = filter_var($_POST['eventType'], FILTER_SANITIZE_STRING);
        $guestCount = filter_var($_POST['guestCount'], FILTER_SANITIZE_NUMBER_INT);
        $eventDate = $_POST['eventDate'];
        $endDate = $_POST['endDate'];
        $startTime = $_POST['startTime'];
        $endTime = $_POST['endTime'];
        $venue = filter_var($_POST['venue'], FILTER_SANITIZE_NUMBER_INT);
        $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $budget = isset($_POST['budget']) ? preg_replace('/[^0-9]/', '', $_POST['budget']) : 0;

        // Validate email and phone
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => getMessage('invalid_email', $languageId)]);
            exit();
        }
        if (!preg_match('/^[0-9+\-\s\(\)]+$/', $phone) || strlen($phone) < 10) {
            echo json_encode(['status' => 'error', 'message' => getMessage('invalid_phone', $languageId)]);
            exit();
        }

        // Combine date and time
        $startAt = date('Y-m-d H:i:s', strtotime("$eventDate $startTime"));
        $endAt = date('Y-m-d H:i:s', strtotime("$endDate $endTime"));

        // Validate dates
        if (strtotime($startAt) < time()) {
            echo json_encode(['status' => 'error', 'message' => getMessage('past_start_date', $languageId)]);
            exit();
        }
        if (strtotime($endAt) < strtotime($startAt)) {
            echo json_encode(['status' => 'error', 'message' => getMessage('invalid_end_date', $languageId)]);
            exit();
        }

        // Calculate duration in hours
        $howLong = (strtotime($endAt) - strtotime($startAt)) / 3600;

        // Handle image uploads
        $images = [];
        if (!empty($_FILES['images']['name'])) {
            $uploadDir = '../view/img/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            foreach ($_FILES['images']['name'] as $key => $name) {
                if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['images']['tmp_name'][$key];
                    $fileName = uniqid() . '_' . basename($name);
                    $targetPath = $uploadDir . $fileName;

                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $images[] = $fileName;
                    }
                }
            }
        }
        $imagesString = !empty($images) ? implode(',', $images) : '';

        // Check if customer exists or create new
        $customerId = getCustomerIdByEmail($email);
        if (!$customerId) {
            $customerId = createCustomer($fullName, $phone, $email);
        }

        // Insert into dathoitruong
        $result = insertEventBooking(
            $eventType,
            $startAt,
            $endAt,
            $guestCount,
            $description,
            $imagesString,
            $budget,
            'pending', // Default status
            $howLong,
            $venue,
            $customerId
        );

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => getMessage('booking_success', $languageId)]);
        } else {
            echo json_encode(['status' => 'error', 'message' => getMessage('booking_failed', $languageId)]);
        }
        exit();
    }

    if (isset($_POST['cuisine_code'])) {
        $event_code = $_POST['cuisine_code'];
        // $_SESSION['type_event'] = $event_code;
        if ($event_code === 'nha-hang') {
            $_SESSION['restaurant_images'] = getRestaurantImages();
        }
        // } elseif ($event_code === 'tiec-cuoi') {
        //     $_SESSION['head_banner'] = getSelectedBanner('pagedetail', 'pagedetail-banner-wedding');
        //     $_SESSION['image_organized_event'] = getImageOrganizedEvents('tiec-cuoi', 10);
        // } elseif ($event_code === 'sinh-nhat') {
        //     $_SESSION['head_banner'] = getSelectedBanner('pagedetail', 'pagedetail-banner-birthday');
        //     $_SESSION['image_organized_event'] = getImageOrganizedEvents('sinh-nhat', 10);
        // } elseif ($event_code === 'gala-dinner') {
        //     $_SESSION['head_banner'] = getSelectedBanner('pagedetail', 'pagedetail-banner-gala');
        //     $_SESSION['image_organized_event'] = getImageOrganizedEvents('gala-dinner', 10);
        // }

        header("location: /libertylaocai/$event_code");
        exit();
    }

    if (isset($_POST['submit_booking_restaurant'])) {
        header('Content-Type: application/json');

        // Validate required fields
        $requiredFields = ['customerName', 'phoneNumber', 'email', 'bookingDate', 'startTime', 'guestCount'];
        $missingFields = [];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $missingFields[] = $field;
            }
        }
        if (!empty($missingFields)) {
            echo json_encode([
                'status' => 'error',
                'message' => getMessage('missing_field', $languageId) . implode(', ', $missingFields)
            ]);
            exit();
        }

        // Sanitize input
        $customerName = filter_var($_POST['customerName'], FILTER_SANITIZE_STRING);
        $phoneNumber = filter_var($_POST['phoneNumber'], FILTER_SANITIZE_STRING);
        $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
        $bookingDate = $_POST['bookingDate'];
        $startTime = $_POST['startTime'];
        $guestCount = filter_var($_POST['guestCount'], FILTER_SANITIZE_NUMBER_INT);
        $diningArea = isset($_POST['diningArea']) ? filter_var($_POST['diningArea'], FILTER_SANITIZE_STRING) : '';
        $occasion = isset($_POST['occasion']) && !empty($_POST['occasion']) ? filter_var($_POST['occasion'], FILTER_SANITIZE_STRING) : null;
        $specialRequests = isset($_POST['specialRequests']) && !empty($_POST['specialRequests']) ? filter_var($_POST['specialRequests'], FILTER_SANITIZE_STRING) : null;

        // Validate email and phone
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => getMessage('invalid_email', $languageId)]);
            exit();
        }
        if (!preg_match('/^[0-9+\-\s\(\)]+$/', $phoneNumber) || strlen($phoneNumber) < 10) {
            echo json_encode(['status' => 'error', 'message' => getMessage('invalid_phone', $languageId)]);
            exit();
        }

        // Combine date and time
        $startAt = date('Y-m-d H:i:s', strtotime("$bookingDate $startTime"));

        // Validate date
        if (strtotime($startAt) < time()) {
            echo json_encode(['status' => 'error', 'message' => getMessage('past_start_date', $languageId)]);
            exit();
        }

        // Check if customer exists or create new
        $customerId = getCustomerIdByEmail($email ?: $phoneNumber); // Sử dụng email hoặc phone làm key
        if (!$customerId) {
            $customerId = createCustomer($customerName, $phoneNumber, $email);
        }

        if ($diningArea === 'Sky Bar') {
            // Insert into datbanbar (no location, no occasion)
            $result = insertBarBooking(
                $startAt,
                $guestCount,
                $specialRequests,
                'pending', // Default status
                $customerId
            );

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => getMessage('booking_success', $languageId)]);
            } else {
                echo json_encode(['status' => 'error', 'message' => getMessage('booking_failed', $languageId)]);
            }
        } else {
            // Insert into datbannhahang
            $result = insertRestaurantBooking(
                $diningArea,
                $startAt,
                $guestCount,
                $specialRequests,
                $occasion,
                'pending', // Default status
                $customerId
            );

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => getMessage('booking_success', $languageId)]);
            } else {
                echo json_encode(['status' => 'error', 'message' => getMessage('booking_failed', $languageId)]);
            }
        }
        exit();
    }

    if (isset($_POST['comment_restaurant']) && $_POST['comment_restaurant'] === 'true') {
        $name = $_POST['reviewer-name'] ?? '';
        $email = $_POST['reviewer-email'] ?? '';
        $content = $_POST['review-content'] ?? '';
        $rating = $_POST['rating'] ?? 0;

        if (!$name || !$email || !$content || !$rating) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin bắt buộc']);
            exit;
        }

        if (insertCommentRestaurant($name, $email, $content, $rating)) {
            echo json_encode(['status' => 'success', 'message' => 'Đánh giá đã được gửi thành công']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi lưu đánh giá']);
        }
    }

    if (isset($_POST['comment_bar']) && $_POST['comment_bar'] === 'true') {
        $name = $_POST['reviewer-name'] ?? '';
        $email = $_POST['reviewer-email'] ?? '';
        $content = $_POST['review-content'] ?? '';
        $rating = $_POST['rating'] ?? 0;

        if (!$name || !$email || !$content || !$rating) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin bắt buộc']);
            exit;
        }

        if (insertCommentBar($name, $email, $content, $rating)) {
            echo json_encode(['status' => 'success', 'message' => 'Đánh giá đã được gửi thành công']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi lưu đánh giá']);
        }
    }

    if (isset($_POST['comment_room']) && $_POST['comment_room'] === 'true') {
        $name = $_POST['reviewer-name'] ?? '';
        $email = $_POST['reviewer-email'] ?? '';
        $content = $_POST['review-content'] ?? '';
        $rating = $_POST['rating'] ?? 0;
        $id_loaiphong = $_POST['id_loaiphong'] ?? '';

        if (!$name || !$email || !$content || !$rating || !$id_loaiphong) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin bắt buộc']);
            exit;
        }

        if (insertRoomComment($id_loaiphong, $name, $email, $content, $rating)) {
            echo json_encode(['status' => 'success', 'message' => 'Đánh giá đã được gửi thành công']);
            // exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi lưu đánh giá']);
            // exit;
        }
    }

    if (isset($_POST['comment_service']) && $_POST['comment_service'] === 'true') {
        $name = $_POST['reviewer-name'] ?? '';
        $email = $_POST['reviewer-email'] ?? '';
        $content = $_POST['review-content'] ?? '';
        $rating = $_POST['rating'] ?? 0;
        $id_service = $_POST['id_service'] ?? '';

        // if (!$name || !$email || !$content || !$rating || !$id_service) {
        //     echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin bắt buộc']);
        //     exit;
        // }

        if (insertServiceComment($id_service, $name, $email, $content, $rating)) {
            echo json_encode(['status' => 'success', 'message' => 'Đánh giá đã được gửi thành công']);
            // exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi lưu đánh giá']);
            // exit;
        }
    }

    // Nhận id ưu đãi (có thể đến từ id_uudai hoặc other_promotion_id)
    if (isset($_POST['id_uudai']) || isset($_POST['other_promotion_id'])) {
        // Ưu tiên id_uudai, còn không thì lấy other_promotion_id
        $id_uudai = $_POST['id_uudai'] ?? $_POST['other_promotion_id'];

        // Lấy thông tin ưu đãi
        $getPromotionById = getPromotionById(1, $id_uudai);

        // Lưu vào session
        $_SESSION['id_uudai']   = $id_uudai;
        $_SESSION['head_banner'] = getSelectedBanner('saledetail', 'saledetail-banner');

        // Điều hướng về trang chi tiết khuyến mãi
        header(
            "Location: /libertylaocai/khuyen-mai/" .
                to_short_slug($getPromotionById['title'], 5)
        );
        exit; // Đảm bảo dừng script sau khi redirect
    }


    // Nhận id tin tức (có thể đến từ id_tintuc hoặc other_news_id)
    if (isset($_POST['id_tintuc']) || isset($_POST['other_news_id'])) {
        // Ưu tiên id_tintuc, nếu không có thì dùng other_news_id
        $id_tintuc = $_POST['id_tintuc'] ?? $_POST['other_news_id'];

        // Lấy dữ liệu tin tức theo ID
        $getNewById = getNewById(1, $id_tintuc);

        // Lưu vào session
        $_SESSION['id_tintuc'] = $id_tintuc;
        $_SESSION['head_banner'] = getSelectedBanner('tintuc-detail', 'tintuc-detail-banner');

        // Chuyển hướng về trang chi tiết tin tức
        header("Location: /libertylaocai/tin-tuc/" . to_short_slug($getNewById['title'], 5));
        exit; // Đảm bảo dừng script sau khi chuyển hướng
    }

    if (isset($_POST['footer_category_code'])) {
        $footer_category_code = $_POST['footer_category_code'];
        if ($footer_category_code === 'tin-tuc') {
            $_SESSION['head_banner'] = getSelectedBanner('tintuc', 'tintuc-banner');
        } elseif ($footer_category_code === 'event') {
            $_SESSION['head_banner'] = getSelectedBanner('event', 'event-banner');
        } elseif ($footer_category_code === 'nhahang&bar') {
            $_SESSION['head_banner'] = getSelectedBanner('nhahang&bar', 'service-banner');
        } elseif ($footer_category_code === 'dat-phong') {
            $_SESSION['head_banner'] = getSelectedBanner('dat-phong', 'hero-content');
        }
        header("location: /libertylaocai/$footer_category_code");
    }

    if (isset($_POST['xem_them_tin'])) {
        $footer_category_code = $_POST['xem_them_tin'];
        $_SESSION['head_banner'] = getSelectedBanner('tintuc', 'tintuc-banner');
        header("location: /libertylaocai/$footer_category_code");
    }

    if (isset($_POST['sukiendatochuc'])) {
        $_SESSION['head_banner'] = getSelectedBanner('su-kien-da-to-chuc', 'event-organized-banner');
        header("location: /libertylaocai/su-kien-da-to-chuc");
    }

    // if (isset($_POST['id_sukiendatochuc'])) {
    //     $id_sukiendatochuc = $_POST['id_sukiendatochuc'];
    //     $getEventOrganizedById = getEventOrganizedById(1, $id_sukiendatochuc);
    //     $_SESSION['id_sukiendatochuc'] = $id_sukiendatochuc;
    //     $_SESSION['head_banner'] = getSelectedBanner('chi-tiet-su-kien-da-to-chuc', 'tintuc-detail-banner');
    //     header("location: /libertylaocai/su-kien-da-to-chuc/" . to_short_slug($getEventOrganizedById['title'], 5));
    // }

    // //Ưu đãi liên quan
    // if (isset($_POST['other_organized_id'])) {
    //     $id_sukiendatochuc = $_POST['other_organized_id'];
    //     $getEventOrganizedById = getEventOrganizedById(1, $id_sukiendatochuc);
    //     $_SESSION['id_sukiendatochuc'] = $id_sukiendatochuc;
    //     $_SESSION['head_banner'] = getSelectedBanner('chi-tiet-su-kien-da-to-chuc', 'tintuc-detail-banner');
    //     header("location: /libertylaocai/su-kien-da-to-chuc/" . to_short_slug($getEventOrganizedById['title'], 5));
    // }
    // Nhận ID sự kiện đã tổ chức (từ id_sukiendatochuc hoặc other_organized_id)
    if (isset($_POST['id_sukiendatochuc']) || isset($_POST['other_organized_id'])) {
        // Ưu tiên id_sukiendatochuc, nếu không có thì dùng other_organized_id
        $id_sukiendatochuc = $_POST['id_sukiendatochuc'] ?? $_POST['other_organized_id'];

        // Lấy thông tin sự kiện
        $getEventOrganizedById = getEventOrganizedById(1, $id_sukiendatochuc);

        // Lưu thông tin vào session
        $_SESSION['id_sukiendatochuc'] = $id_sukiendatochuc;
        $_SESSION['head_banner'] = getSelectedBanner('chi-tiet-su-kien-da-to-chuc', 'tintuc-detail-banner');

        // Chuyển hướng đến trang chi tiết sự kiện
        header("Location: /libertylaocai/su-kien-da-to-chuc/" . to_short_slug($getEventOrganizedById['title'], 5));
        exit;
    }

    if (isset($_POST['room_id']) && !isset($_POST['submit_booking_room'])) {
        $room_id = $_POST['room_id'];
        $room_name = getRoomDetail($room_id, 1)['name'];
        $images = getImagesForRoom($room_id);
        $_SESSION['images'] = $images;
        $_SESSION['room_info'] = [
            'room_id' => $room_id,
            'images' => $images
        ];
        header("location: /libertylaocai/" . to_slug($room_name));
        exit();
    }

    if (isset($_POST['orther_room'])) {
        $room_id = $_POST['room_other_id'] ?? '';
        $orther_room_name = getRoomDetail($room_id, 1)['name'];
        $images = getImagesForRoom($room_id);
        $_SESSION['images'] = $images;
        $_SESSION['room_info'] = [
            'room_id' => $room_id,
            'images' => $images
        ];
        header("location: /libertylaocai/" . to_slug($orther_room_name));
        exit();
    }
    if (isset($_POST['submit_booking_room'])) {
        header('Content-Type: application/json');
        // Lấy dữ liệu từ biểu mẫu
        $checkin = trim($_POST['checkin'] ?? '');
        $checkout = trim($_POST['checkout'] ?? '');
        $adults = (int)($_POST['adults'] ?? 0);
        $children = (int)($_POST['children'] ?? 0);
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $special_requests = trim($_POST['special-requests'] ?? NULL);
        $room_id = $_POST['id_loaiphong'] ?? 0;

        // Xác thực dữ liệu đầu vào
        if ($adults < 1 || $children < 0 || empty($fullname) || empty($email) || empty($phone) || empty($room_id)) {
            echo json_encode([
                'status' => 'error',
                'message' => getMessage('missing_field', $languageId) . implode(', ', $missingFields)
            ]);
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => getMessage('invalid_email', $languageId)]);
            exit();
        }

        $checkin_date = date('Y-m-d H:i:s', strtotime("$checkin 00:00:00"));
        $checkout_date = date('Y-m-d H:i:s', strtotime("$checkout 00:00:00"));

        // Validation cải thiện
        $today_start = date('Y-m-d 00:00:00'); // Ngày hôm nay lúc 00:00:00
        $today_timestamp = strtotime($today_start);

        // 1. Kiểm tra ngày check-in không được là quá khứ (so với hôm nay)
        if (strtotime($checkin_date) < $today_timestamp) {
            echo json_encode(['status' => 'error', 'message' => getMessage('past_start_date', $languageId)]);
            exit();
        }

        // 2. Kiểm tra ngày check-out phải sau ngày check-in
        if (strtotime($checkout_date) <= strtotime($checkin_date)) {
            echo json_encode(['status' => 'error', 'message' => getMessage('checkout_before_checkin', $languageId)]);
            exit();
        }


        // Check if customer exists or create new
        $customerId = getCustomerIdByEmail($email ?: $phone); // Sử dụng email hoặc phone làm key
        if (!$customerId) {
            $customerId = createCustomer($fullname, $phone, $email);
        }
        $result = insertRoomBooking($checkin_date, $checkout_date, $adults, $children, $special_requests, 'pending', $room_id, $customerId);
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => getMessage('booking_success', $languageId)]);
        } else {
            echo json_encode(['status' => 'error', 'message' => getMessage('booking_failed', $languageId)]);
        }
        exit();
    }

    if (isset($_POST['lienhe']) && $_POST['lienhe'] === 'true') {
        // Lấy dữ liệu từ form
        $fullName = isset($_POST['fullName']) ? $conn->real_escape_string(trim($_POST['fullName'])) : '';
        $email = isset($_POST['email']) ? $conn->real_escape_string(trim($_POST['email'])) : '';
        $phone = isset($_POST['phone']) ? $conn->real_escape_string(trim($_POST['phone'])) : '';
        $subject = isset($_POST['subject']) ? $conn->real_escape_string(trim($_POST['subject'])) : '';
        $message = isset($_POST['message']) ? $conn->real_escape_string(trim($_POST['message'])) : '';

        // Kiểm tra dữ liệu
        if (empty($fullName) || empty($email) || empty($phone)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin bắt buộc']);
            exit;
        }

        // Kiểm tra định dạng email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Email không hợp lệ']);
            exit;
        }

        // Kiểm tra định dạng số điện thoại
        if (!preg_match('/^[0-9]{10,11}$/', $phone)) {
            echo json_encode(['success' => false, 'message' => 'Số điện thoại không hợp lệ']);
            exit;
        }

        // Check if customer exists or create new
        $customerId = getCustomerIdByEmail($email);
        if (!$customerId) {
            $customerId = createCustomer($fullName, $phone, $email);
        }

        $result = createContactRequest($subject, $message, 'pending', 'lienhe', $customerId);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Thông tin liên hệ đã được gửi thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu dữ liệu: ' . $conn->error]);
        }
        exit();
    }

    if (isset($_POST['login'])) {
        header('Content-Type: application/json; charset=utf-8');
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            echo json_encode([
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $user = checkUserLogin($email);
        if (empty($user)) {
            echo json_encode([
                'success' => false,
                'message' => 'Tài khoản không tồn tại.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $passBrypt = passBrypt($email);
        if (empty($passBrypt)) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi hệ thống. Vui lòng thử lại sau.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (!password_verify($password, $passBrypt)) {
            echo json_encode([
                'success' => false,
                'message' => 'Sai mật khẩu.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Đăng nhập thành công!'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (isset($_POST['forgot_password'])) {
        header('Content-Type: application/json; charset=utf-8');
        $email = $_POST['email'] ?? '';

        if (empty($email)) {
            echo json_encode([
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $emailExist = checkEmailExist($email);
        if (!$emailExist) {
            echo json_encode([
                'success' => false,
                'message' => 'Email không tồn tại.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $token = generateOTP();
        storeResetToken($email, $token);
        $subject = "Xác thực để đăng nhập";
        $message = "Mã OTP của bạn là: $token\nVui lòng nhập mã này để xác thực đăng nhập.\nMã có hiệu lực trong 5 phút.";
        if (sendMail($email, $subject, $message)) {
            echo json_encode([
                'success' => true,
                'message' => 'Đã gửi OTP xác minh.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi khi gửi mail để xác thực.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // Xử lý xác thực OTP reset password
    if (isset($_POST['verify_reset_otp'])) {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        $otp = $_POST['otp'] ?? '';
        $email = $_POST['email'] ?? '';

        if (empty($otp) || empty($email)) {
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu thông tin OTP hoặc email.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Validate OTP format (6 digits)
        if (!preg_match('/^\d{6}$/', $otp)) {
            echo json_encode([
                'success' => false,
                'message' => 'Mã OTP phải là 6 chữ số.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        try {

            $user = getUserByToken($otp);

            if ($user && $user['email'] === $email && strtotime($user['reset_expires']) > time()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Xác thực OTP thành công!',
                    'reset_token' => $otp
                ], JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'OTP không hợp lệ hoặc đã hết hạn.'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
        } catch (Exception $e) {
            error_log("Verify reset OTP error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi hệ thống. Vui lòng thử lại sau.'
            ], JSON_UNESCAPED_UNICODE);
        }

        exit;
    }

    if (isset($_POST['reset_password'])) {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        $email = $_POST['email'] ?? '';
        $reset_token = $_POST['reset_token'] ?? '';
        $new_password = $_POST['new_password'] ?? '';

        if (empty($email) || empty($reset_token) || empty($new_password)) {
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu thông tin email, token hoặc mật khẩu.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Kiểm tra độ dài mật khẩu
        if (strlen($new_password) < 8) {
            echo json_encode([
                'success' => false,
                'message' => 'Mật khẩu phải có ít nhất 8 ký tự.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        try {
            $user = getUserByToken($reset_token);

            if ($user && $user['email'] === $email && strtotime($user['reset_expires']) > time()) {
                // Mã hóa mật khẩu mới
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                $result = changePassword($hashed_password, $email);
                if ($result) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Đặt lại mật khẩu thành công!'
                    ], JSON_UNESCAPED_UNICODE);
                    exit;
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Token không hợp lệ hoặc đã hết hạn.'
                    ], JSON_UNESCAPED_UNICODE);
                    exit;
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Token không hợp lệ hoặc đã hết hạn.'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
        } catch (Exception $e) {
            error_log("Reset password error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi hệ thống. Vui lòng thử lại sau.'
            ], JSON_UNESCAPED_UNICODE);
        }

        exit;
    }

    if (isset($_POST['resend_reset_otp'])) {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        $email = $_POST['email'] ?? '';
        if (empty($email)) {
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu email.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => 'Email không đúng định dạng.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        try {
            // Giả định: Tạo và gửi OTP mới
            $emailExist = checkEmailExist($email);
            if (!$emailExist) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Email không tồn tại.'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $otp = generateOtp();
            storeResetToken($email, $otp);
            $subject = "Xác thực để đăng nhập";
            $message = "Mã OTP của bạn là: $otp\nVui lòng nhập mã này để xác thực đăng nhập.\nMã có hiệu lực trong 5 phút.";
            if (sendMail($email, $subject, $message)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Đã gửi OTP xác minh.'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Lỗi khi gửi mail để xác thực.'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
        } catch (Exception $e) {
            error_log("Resend OTP error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi hệ thống. Vui lòng thử lại sau.'
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }

    if (isset($_POST['lienhetour'])) {
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $service = isset($_POST['service']) ? trim($_POST['service']) : '';
        $message = isset($_POST['message']) ? trim($_POST['message']) : '';
        $lienhe = isset($_POST['lienhetour']) && $_POST['lienhetour'] === 'true';

        // Validate input
        if (empty($name) || empty($phone) || empty($email) || empty($service) || empty($message)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $languageId == 1 ? 'Vui lòng điền đầy đủ thông tin!' : 'Please fill in all required fields!'
            ]);
            exit;
        }

        if (!$lienhe) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $languageId == 1 ? 'Yêu cầu không hợp lệ!' : 'Invalid request!'
            ]);
            exit;
        }

        $phone_clean = preg_replace('/\s+/', '', $phone);
        if (!preg_match('/^0[0-9]{9,10}$/', $phone_clean)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $languageId == 1 ? 'Số điện thoại không hợp lệ (phải bắt đầu bằng 0, 10-11 số)!' : 'Invalid phone number (must start with 0, 10-11 digits)!'
            ]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $languageId == 1 ? 'Email không hợp lệ!' : 'Invalid email address!'
            ]);
            exit;
        }

        if (strlen($name) > 255 || strlen($email) > 255 || strlen($phone) > 20 || strlen($service) > 255) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $languageId == 1 ? 'Dữ liệu nhập vào quá dài!' : 'Input data is too long!'
            ]);
            exit;
        }

        // Check if customer exists or create new
        $customerId = getCustomerIdByEmail($email);
        if (!$customerId) {
            $customerId = createCustomer($name, $phone, $email);
        }

        if (createContactRequest($service, $message, 'pending', 'dichvu', $customerId)) {
            echo json_encode([
                'success' => true,
                'message' => $languageId == 1 ? 'Yêu cầu của bạn đã được gửi thành công!' : 'Your request has been sent successfully!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => $languageId == 1 ? 'Có lỗi xảy ra, vui lòng thử lại!' : 'An error occurred, please try again!'
            ]);
        }

        exit;
    }

    // Thêm bình luận
    if ($action === 'add') {
        $content = $_POST['content'] ?? '';
        $rate = (int)($_POST['rate'] ?? 0);
        $type = $_POST['type'] ?? '';
        $id_dichvu = isset($_POST['id_dichvu']) ? (int)$_POST['id_dichvu'] : null;
        $id_nhahang = isset($_POST['id_nhahang']) ? (int)$_POST['id_nhahang'] : null;
        $id_loaiphong = isset($_POST['id_loaiphong']) ? (int)$_POST['id_loaiphong'] : null;

        if (isset($_POST['id_khachhang']) && $_POST['id_khachhang']) {
            $id_khachhang = (int)$_POST['id_khachhang'];
        } else {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            if (empty($name) || empty($email)) {
                echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin khách hàng']);
                exit;
            }
            $id_khachhang = addCustomer($conn, $name, $email);
            if (!$id_khachhang) {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi khi thêm khách hàng']);
                exit;
            }
        }

        $result = addComment($conn, $content, $rate, $type, $id_khachhang, $id_dichvu, $id_nhahang, $id_loaiphong);
        echo json_encode($result);
        exit;
    }

    // Sửa bình luận
    if ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        $content = $_POST['content'] ?? '';
        $rate = (int)($_POST['rate'] ?? 0);

        if (empty($id) || empty($content) || empty($rate)) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin bắt buộc']);
            exit;
        }

        $result = updateComment($conn, $id, $content, $rate);
        echo json_encode($result);
        exit;
    }

    // Ẩn/hiện nhiều bình luận
    if ($action === 'bulk_toggle_active') {
        $ids = isset($_POST['ids']) ? array_map('intval', $_POST['ids']) : [];
        $result = bulkToggleComments($conn, $ids);
        echo json_encode($result);
        exit;
    }

    // Xóa nhiều bình luận
    if ($action === 'bulk_delete') {
        $ids = isset($_POST['ids']) ? array_map('intval', $_POST['ids']) : [];
        $result = bulkDeleteComments($conn, $ids);
        echo json_encode($result);
        exit;
    }

    // Tải dữ liệu bình luận
    if ($action === 'load_data') {
        $tab = $_POST['tab'] ?? '';
        $subtab = $_POST['subtab'] ?? '';
        $search = $_POST['search'] ?? '';
        $sort = $_POST['sort'] ?? 'newest';
        $status = $_POST['status'] ?? '';
        $date = $_POST['date'] ?? '';
        $rate = $_POST['rate'] ?? '';
        $page = (int)($_POST['page'] ?? 1);

        $result = loadComments($conn, $tab, $subtab, $search, $sort, $status, $date, $rate, $page);

        if ($result['status'] === 'success') {
            $html = '';
            foreach ($result['comments'] as $row) {
                $html .= "<tr data-name='{$row['name']}' data-email='{$row['email']}' data-date='" . date('Y-m-d', strtotime($row['create_at'])) . "' data-active='{$row['active']}'>
                    <td><input type='checkbox' class='select-comment' value='{$row['id']}'></td>
                    <td>{$row['id']}</td>
                    <td>{$row['content']}</td>
                    <td>";
                for ($i = 1; $i <= 5; $i++) {
                    $html .= $i <= $row['rate'] ? '★' : '☆';
                }
                $html .= "</td>
                    <td>{$row['name']} ({$row['email']})</td>";

                if ($tab == 'dichvu') {
                    $html .= "<td>{$row['title']}</td>";
                } elseif ($tab == 'phong') {
                    $html .= "<td>{$row['phong_name']}</td>";
                }

                $html .= "<td>" . date('d/m/Y H:i', strtotime($row['create_at'])) . "</td>
                    <td>" . ($row['active'] ?
                    '<span class="status-active" style="background-color: #d4edda; color: #155724; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem;">Hiện</span>' :
                    '<span class="status-inactive" style="background-color: #f8d7da; color: #721c24; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem;">Ẩn</span>') . "</td>
                    <td>
                        <button class='btn-edit edit' data-id='{$row['id']}' data-content='" . htmlspecialchars($row['content']) . "' data-rate='{$row['rate']}'>Sửa</button>
                    </td>
                </tr>";
            }

            if (empty($result['comments'])) {
                $html = "<tr><td colspan='9' style='text-align: center;'>Không có bình luận nào để hiển thị.</td></tr>";
            }

            $pagination_html = '';
            if ($result['total_pages'] > 1) {
                $pagination_html = '<div class="pagination">';

                if ($page > 1) {
                    $pagination_html .= '<button class="pagination-btn" data-page="' . ($page - 1) . '">‹ Trước</button>';
                }

                $start_page = max(1, $page - 2);
                $end_page = min($result['total_pages'], $page + 2);

                if ($start_page > 1) {
                    $pagination_html .= '<button class="pagination-btn" data-page="1">1</button>';
                    if ($start_page > 2) {
                        $pagination_html .= '<span class="pagination-dots">...</span>';
                    }
                }

                for ($i = $start_page; $i <= $end_page; $i++) {
                    $active_class = ($i == $page) ? 'active' : '';
                    $pagination_html .= '<button class="pagination-btn ' . $active_class . '" data-page="' . $i . '">' . $i . '</button>';
                }

                if ($end_page < $result['total_pages']) {
                    if ($end_page < $result['total_pages'] - 1) {
                        $pagination_html .= '<span class="pagination-dots">...</span>';
                    }
                    $pagination_html .= '<button class="pagination-btn" data-page="' . $result['total_pages'] . '">' . $result['total_pages'] . '</button>';
                }

                if ($page < $result['total_pages']) {
                    $pagination_html .= '<button class="pagination-btn" data-page="' . ($page + 1) . '">Tiếp ›</button>';
                }

                $pagination_html .= '</div>';
            }

            echo json_encode([
                'status' => 'success',
                'html' => $html,
                'pagination' => $pagination_html,
                'total_records' => $result['total_records'],
                'current_page' => $result['current_page'],
                'total_pages' => $result['total_pages']
            ]);
        } else {
            echo json_encode($result);
        }
        exit;
    }

    if (isset($_POST['chitietdichvu'])) {
        $id_dichvu = $_POST['chitietdichvu'];
        $service = getServiceById($languageId, $id_dichvu);
        $_SESSION['id_dichvu'] = $id_dichvu;
        header("location: /libertylaocai/dich-vu/" . to_short_slug($service['info']['title'], 5));
    }
}



// //quanlytour (liem)
// // Xử lý POST requests cho quản lý tour
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     header('Content-Type: application/json');

//     try {
//         // Cập nhật thông tin tour
//         if (isset($_POST['action']) && $_POST['action'] === 'update_tour') {
//             $id_dichvu = (int)$_POST['id_dichvu'];
//             $title_vi = trim($_POST['title_vi']);
//             $title_en = trim($_POST['title_en']);
//             $price = trim($_POST['price']);

//             $result = updateTour($conn, $id_dichvu, $title_vi, $title_en, $price);
//             echo json_encode($result);
//             exit;
//         }

//         // Thêm ảnh
//         if (isset($_POST['action']) && $_POST['action'] === 'add_image') {
//             $id_dichvu = (int)$_POST['id_dichvu'];
//             $id_topic = (int)$_POST['id_topic'];
//             $is_primary = isset($_POST['is_primary']) ? (int)$_POST['is_primary'] : 0;
//             $images = $_FILES['images'] ?? ['name' => [], 'error' => []];

//             $result = addTourImage($conn, $id_dichvu, $id_topic, $is_primary, $images);
//             echo json_encode($result);
//             exit;
//         }

//         // Xóa ảnh
//         if (isset($_POST['action']) && $_POST['action'] === 'delete_image') {
//             $id_image = (int)$_POST['id_image'];
//             $image_name = trim($_POST['image_name']);

//             $result = deleteTourImage($conn, $id_image, $image_name);
//             echo json_encode($result);
//             exit;
//         }

//         // Cập nhật mô tả tour
//         if (isset($_POST['action']) && $_POST['action'] === 'update_description') {
//             $id_dichvu = (int)$_POST['id_dichvu'];
//             $content_vi = trim($_POST['content_vi']);
//             $content_en = trim($_POST['content_en']);

//             $result = updateTourDescription($conn, $id_dichvu, $content_vi, $content_en);
//             echo json_encode($result);
//             exit;
//         }
//     } catch (Exception $e) {
//         echo json_encode(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()]);
//         exit;
//     }
// }
//quanlyanh (liem)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // Lấy danh sách chủ đề
    if ($action === 'get_topics') {
        $result = getTopics($conn);
        echo json_encode($result['topics']);
        exit;
    }

    // Lấy danh sách trang
    if ($action === 'get_pages') {
        $result = getPages($conn);
        echo json_encode($result['pages']);
        exit;
    }

    // Lấy danh sách sự kiện
    if ($action === 'get_sukien') {
        $result = getSukien($conn);
        echo json_encode($result['sukien']);
        exit;
    }

    // Lấy danh sách ảnh/video
    if ($action === 'get_images') {
        $topic_id = $_POST['topic_id'] ?? '';
        $page = $_POST['page'] ?? '';
        $id_sukien = $_POST['id_sukien'] ?? '';

        if (empty($topic_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu topic_id']);
            exit;
        }

        $result = getImages($conn, $topic_id, $page, $id_sukien);
        echo json_encode($result);
        exit;
    }

    // Tải lên ảnh/video
    if ($action === 'upload_images') {
        $topic_id = $_POST['topic_id'] ?? '';
        $event_id = $_POST['event_id'] ?? null;
        $service = $_POST['service'] ?? null;

        if (empty($topic_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu topic_id']);
            exit;
        }

        if (!isset($_FILES['images']) || empty($_FILES['images']['name'][0])) {
            echo json_encode(['status' => 'error', 'message' => 'Không có file nào được chọn!']);
            exit;
        }

        $result = uploadImages($conn, $topic_id, $_FILES['images'], $event_id, $service);
        echo json_encode([
            'success' => $result['status'] === 'success',
            'uploaded_count' => $result['uploaded_count'] ?? 0,
            'message' => $result['message']
        ]);
        exit;
    }

    // Chỉnh sửa ảnh
    if ($action === 'edit_image') {
        $topic_id = $_POST['topic_id'] ?? '';
        $id = $_POST['id'] ?? '';

        if (empty($topic_id) || empty($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu topic_id hoặc id']);
            exit;
        }

        if (!isset($_FILES['image']) || empty($_FILES['image']['name'])) {
            echo json_encode(['status' => 'error', 'message' => 'Không có file ảnh được chọn!']);
            exit;
        }

        $result = editImage($conn, $topic_id, $id, $_FILES['image']);
        echo json_encode([
            'success' => $result['status'] === 'success',
            'message' => $result['message']
        ]);
        exit;
    }

    // Xóa ảnh/video
    if ($action === 'delete_item') {
        $id = $_POST['id'] ?? '';
        $table = $_POST['table'] ?? '';
        $image_name = $_POST['image_name'] ?? '';

        if (empty($id) || empty($table) || empty($image_name)) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin bắt buộc']);
            exit;
        }

        $result = deleteItem($conn, $id, $table, $image_name);
        echo json_encode([
            'success' => $result['status'] === 'success',
            'message' => $result['message']
        ]);
        exit;
    }

    // Chuyển đổi trạng thái
    if ($action === 'toggle_status') {
        $id = $_POST['id'] ?? '';
        $table = $_POST['table'] ?? '';
        $field = $_POST['field'] ?? '';
        $current_status = $_POST['current_status'] ?? '';

        if (empty($id) || empty($table) || empty($field) || $current_status === '') {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin bắt buộc']);
            exit;
        }

        $result = toggleStatus($conn, $id, $table, $field, $current_status);
        echo json_encode([
            'success' => $result['status'] === 'success',
            'new_status' => $result['new_status'] ?? null,
            'message' => $result['message'] ?? 'Cập nhật thành công!'
        ]);
        exit;
    }
}
