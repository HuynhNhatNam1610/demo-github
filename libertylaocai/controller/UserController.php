<?php
require_once '../model/UserModel.php';
require_once '../view/php/session.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    file_put_contents('debug.log', date('Y-m-d H:i:s') . " - POST: " . print_r($_POST, true) . "\n", FILE_APPEND);
    file_put_contents('debug.log', date('Y-m-d H:i:s') . " - FILES: " . print_r($_FILES, true) . "\n", FILE_APPEND);
    //danh mục header
    if (isset($_POST['category_code'])) {
        $categoryCode = $_POST['category_code'];
        if ($categoryCode === 'event') {
            $_SESSION['head_banner'] = getSelectedBanner('event', 'event-banner');
        } elseif ($categoryCode === 'nhahang&bar') {
            $_SESSION['head_banner'] = getSelectedBanner('nhahang&bar', 'service-banner');
        } elseif ($categoryCode === 'khuyen-mai') {
            $_SESSION['head_banner'] = getSelectedBanner('sale', 'sale-banner');
        }
        header("location: /libertylaocai/$categoryCode");
        exit();
    }

    //tiểu mục header
    if (isset($_POST['subcategory_code'])) {
        $subcategory_code = $_POST['subcategory_code'];
        $_SESSION['type_event'] = $subcategory_code;
        if ($subcategory_code === 'phong-don') {
        } elseif ($subcategory_code === 'phong-doi') {
        } elseif ($subcategory_code === 'phong-triple') {
        } elseif ($subcategory_code === 'phong-gia-dinh') {
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

        } elseif ($subcategory_code === 'dua-don') {            ////duadonsanbay

        } elseif ($subcategory_code === 'tour-sapa') {          ///tour

        } elseif ($subcategory_code === 'tour-bac-ha') {
        } elseif ($subcategory_code === 'tour-y-ty') {
        } elseif ($subcategory_code === 'tour-ha-khau') {
        } elseif ($subcategory_code === 'anh') {                 ///gallery

        } elseif ($subcategory_code === 'video') {               ///gallery

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
        $budget = isset($_POST['budget']) ? filter_var($_POST['budget'], FILTER_SANITIZE_STRING) : '';

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

    if (isset($_POST['id_uudai'])) {
        $id_uudai = $_POST['id_uudai'];
        $getPromotionById = getPromotionById(1, $id_uudai);
        $_SESSION['id_uudai'] = $id_uudai;
        $_SESSION['head_banner'] = getSelectedBanner('saledetail', 'saledetail-banner');
        header("location: /libertylaocai/khuyen-mai/" . to_short_slug($getPromotionById['title'], 5));
    }

    if (isset($_POST['id_tintuc'])) {
        $id_tintuc = $_POST['id_tintuc'];
        $getNewById = getNewById(1, $id_tintuc);
        $_SESSION['id_tintuc'] = $id_tintuc;
        $_SESSION['head_banner'] = getSelectedBanner('tintuc-detail', 'tintuc-detail-banner');
        header("location: /libertylaocai/tin-tuc/" . to_short_slug($getNewById['title'], 5));
    }

    if (isset($_POST['footer_category_code'])) {
        $footer_category_code = $_POST['footer_category_code'];
        if ($footer_category_code === 'tin-tuc') {
            $_SESSION['head_banner'] = getSelectedBanner('tintuc', 'tintuc-banner');
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

    if (isset($_POST['id_sukiendatochuc'])) {
        $id_sukiendatochuc = $_POST['id_sukiendatochuc'];
        $getEventOrganizedById = getEventOrganizedById(1, $id_sukiendatochuc);
        $_SESSION['id_sukiendatochuc'] = $id_sukiendatochuc;
        $_SESSION['head_banner'] = getSelectedBanner('chi-tiet-su-kien-da-to-chuc', 'tintuc-detail-banner');
        header("location: /libertylaocai/su-kien-da-to-chuc/" . to_short_slug($getEventOrganizedById['title'], 5));
    }
}
