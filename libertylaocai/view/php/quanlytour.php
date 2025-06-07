<?php
// Kết nối cơ sở dữ liệu
require_once '../../model/config/connect.php';

$id_ngonngu = 1; // Ngôn ngữ tiếng Việt
function uploadImage($file, $uploadDir = '../../view/img/') {
    // Đảm bảo thư mục tồn tại
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            error_log("Failed to create directory: $uploadDir");
            return false;
        }
    }
    
    // Kiểm tra quyền ghi
    if (!is_writable($uploadDir)) {
        error_log("Directory not writable: $uploadDir");
        return false;
    }
    
    // Tạo tên file
    $fileName = time() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;
    
    // Kiểm tra loại file
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        error_log("Invalid file type: {$file['type']}");
        return false;
    }
    
    // Kiểm tra kích thước file
    if ($file['size'] > 5 * 1024 * 1024) {
        error_log("File too large: {$file['size']} bytes");
        return false;
    }
    
    // Kiểm tra file tạm
    if (!is_uploaded_file($file['tmp_name'])) {
        error_log("Invalid temporary file: {$file['tmp_name']}");
        return false;
    }
    
    // Di chuyển file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        error_log("File uploaded successfully: $targetPath");
        return $fileName;
    } else {
        error_log("Failed to move file to: $targetPath. Error: " . error_get_last()['message']);
        return false;
    }
}
// Xử lý POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        // Cập nhật thông tin tour
        if (isset($_POST['action']) && $_POST['action'] === 'update_tour') {
            $id_dichvu = (int)$_POST['id_dichvu'];
            $title_vi = trim($_POST['title_vi']);
            $title_en = trim($_POST['title_en']);
            $price = trim($_POST['price']);

            // Cập nhật giá trong bảng dichvu
            if ($price !== 'Liên hệ') {
                $price_value = (float)str_replace([',', '.'], '', $price);
                $sql = "UPDATE dichvu SET price = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("di", $price_value, $id_dichvu);
            } else {
                $sql = "UPDATE dichvu SET price = 'Liên hệ' WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id_dichvu);
            }
            $stmt->execute();

            // Cập nhật tiêu đề tiếng Việt
            $sql = "SELECT COUNT(*) as count FROM dichvu_ngonngu WHERE id_dichvu = ? AND id_ngonngu = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_dichvu);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                $sql = "UPDATE dichvu_ngonngu SET title = ? WHERE id_dichvu = ? AND id_ngonngu = 1";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $title_vi, $id_dichvu);
            } else {
                $sql = "INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title) VALUES (?, 1, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("is", $id_dichvu, $title_vi);
            }
            $stmt->execute();

            // Cập nhật tiêu đề tiếng Anh
            $sql = "SELECT COUNT(*) as count FROM dichvu_ngonngu WHERE id_dichvu = ? AND id_ngonngu = 2";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_dichvu);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                $sql = "UPDATE dichvu_ngonngu SET title = ? WHERE id_dichvu = ? AND id_ngonngu = 2";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $title_en, $id_dichvu);
            } else {
                $sql = "INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title) VALUES (?, 2, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("is", $id_dichvu, $title_en);
            }
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Cập nhật thông tin tour thành công!']);
            exit;
        }
        
        // Thêm điểm nổi bật
        if (isset($_POST['action']) && $_POST['action'] === 'add_highlight') {
            $icon = trim($_POST['icon']);
            $title_vi = trim($_POST['title_vi']);
            $content_vi = trim($_POST['content_vi']);
            $title_en = trim($_POST['title_en']);
            $content_en = trim($_POST['content_en']);
            $id_dichvu = (int)$_POST['id_dichvu'];

            if (empty($icon)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng chọn hoặc nhập biểu tượng!']);
                exit;
            }

            // Thêm vào bảng tienich
            $stmt = $conn->prepare("INSERT INTO tienich (icon, active) VALUES (?, 1)");
            $stmt->bind_param("s", $icon);
            if ($stmt->execute()) {
                $id_tienich = $conn->insert_id;

                // Thêm vào bảng tienich_ngonngu cho tiếng Việt
                $stmt = $conn->prepare("INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 1, ?, ?)");
                $stmt->bind_param("iss", $id_tienich, $title_vi, $content_vi);
                if (!$stmt->execute()) {
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm nội dung điểm nổi bật tiếng Việt: ' . $conn->error]);
                    exit;
                }

                // Thêm vào bảng tienich_ngonngu cho tiếng Anh
                $stmt = $conn->prepare("INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 2, ?, ?)");
                $stmt->bind_param("iss", $id_tienich, $title_en, $content_en);
                if (!$stmt->execute()) {
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm nội dung điểm nổi bật tiếng Anh: ' . $conn->error]);
                    exit;
                }

                // Thêm vào bảng tienichtour
                $stmt = $conn->prepare("INSERT INTO tienichtour (id_tienich, id_dichvu, page) VALUES (?, ?, 'chitiettour')");
                $stmt->bind_param("ii", $id_tienich, $id_dichvu);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Thêm điểm nổi bật thành công!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm điểm nổi bật vào tour: ' . $conn->error]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi tạo điểm nổi bật: ' . $conn->error]);
            }
            $stmt->close();
            exit;
        }

        // Cập nhật điểm nổi bật
        if (isset($_POST['action']) && $_POST['action'] === 'update_highlight') {
            $id_tienich = (int)$_POST['id_tienich'];
            $icon = trim($_POST['icon']);
            $title_vi = trim($_POST['title_vi']);
            $content_vi = trim($_POST['content_vi']);
            $title_en = trim($_POST['title_en']);
            $content_en = trim($_POST['content_en']);
            $id_dichvu = (int)$_POST['id_dichvu'];

            if (empty($icon)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng chọn hoặc nhập biểu tượng!']);
                exit;
            }

            // Cập nhật biểu tượng
            $stmt = $conn->prepare("UPDATE tienich SET icon = ? WHERE id = ?");
            $stmt->bind_param("si", $icon, $id_tienich);
            if ($stmt->execute()) {
                // Cập nhật hoặc thêm tiếng Việt
                $check_query = "SELECT COUNT(*) as count FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 1";
                $stmt = $conn->prepare($check_query);
                $stmt->bind_param("i", $id_tienich);
                $stmt->execute();
                $check_row = $stmt->get_result()->fetch_assoc();

                if ($check_row['count'] > 0) {
                    $query = "UPDATE tienich_ngonngu SET title = ?, content = ? WHERE id_tienich = ? AND id_ngonngu = 1";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ssi", $title_vi, $content_vi, $id_tienich);
                } else {
                    $query = "INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 1, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("iss", $id_tienich, $title_vi, $content_vi);
                }
                if (!$stmt->execute()) {
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật/thêm nội dung điểm nổi bật tiếng Việt: ' . $conn->error]);
                    exit;
                }

                // Cập nhật hoặc thêm tiếng Anh
                $check_query = "SELECT COUNT(*) as count FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 2";
                $stmt = $conn->prepare($check_query);
                $stmt->bind_param("i", $id_tienich);
                $stmt->execute();
                $check_row = $stmt->get_result()->fetch_assoc();

                if ($check_row['count'] > 0) {
                    $query = "UPDATE tienich_ngonngu SET title = ?, content = ? WHERE id_tienich = ? AND id_ngonngu = 2";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ssi", $title_en, $content_en, $id_tienich);
                } else {
                    $query = "INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 2, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("iss", $id_tienich, $title_en, $content_en);
                }
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Cập nhật điểm nổi bật thành công!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật/thêm nội dung điểm nổi bật tiếng Anh: ' . $conn->error]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật điểm nổi bật: ' . $conn->error]);
            }
            $stmt->close();
            exit;
        }

        // Xóa điểm nổi bật
        if (isset($_POST['action']) && $_POST['action'] === 'delete_highlight') {
            $id_tienich = (int)$_POST['id_tienich'];
            $id_dichvu = (int)$_POST['id_dichvu'];

            $stmt = $conn->prepare("DELETE FROM tienichtour WHERE id_tienich = ? AND id_dichvu = ?");
            $stmt->bind_param("ii", $id_tienich, $id_dichvu);
            if ($stmt->execute()) {
                $stmt = $conn->prepare("DELETE FROM tienich_ngonngu WHERE id_tienich = ?");
                $stmt->bind_param("i", $id_tienich);
                $stmt->execute();
                $stmt = $conn->prepare("DELETE FROM tienich WHERE id = ?");
                $stmt->bind_param("i", $id_tienich);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Xóa điểm nổi bật thành công!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa điểm nổi bật: ' . $conn->error]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa liên kết điểm nổi bật: ' . $conn->error]);
            }
            $stmt->close();
            exit;
        }
        
        // Thêm lịch trình
        if (isset($_POST['action']) && $_POST['action'] === 'add_schedule') {
            $id_dichvu = (int)$_POST['id_dichvu'];
            $time = $_POST['time'];
            $ngay_vi = trim($_POST['ngay_vi']);
            $title_vi = trim($_POST['title_vi']);
            $content_vi = trim($_POST['content_vi']);
            $ngay_en = trim($_POST['ngay_en']);
            $title_en = trim($_POST['title_en']);
            $content_en = trim($_POST['content_en']);

            // Thêm vào bảng lichtrinh (không còn cột ngay)
            $sql = "INSERT INTO lichtrinh (id_dichvu, time) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $id_dichvu, $time);
            $stmt->execute();
            $id_lichtrinh = $conn->insert_id;

            // Thêm vào bảng lichtrinh_ngonngu cho tiếng Việt
            $sql = "INSERT INTO lichtrinh_ngonngu (id_lichtrinh, id_ngonngu, ngay, title, content) VALUES (?, 1, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $id_lichtrinh, $ngay_vi, $title_vi, $content_vi);
            if (!$stmt->execute()) {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm nội dung lịch trình tiếng Việt: ' . $conn->error]);
                exit;
            }

            // Thêm vào bảng lichtrinh_ngonngu cho tiếng Anh
            $sql = "INSERT INTO lichtrinh_ngonngu (id_lichtrinh, id_ngonngu, ngay, title, content) VALUES (?, 2, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $id_lichtrinh, $ngay_en, $title_en, $content_en);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Thêm lịch trình thành công!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm nội dung lịch trình tiếng Anh: ' . $conn->error]);
            }
            exit;
        }

        // Cập nhật lịch trình
        if (isset($_POST['action']) && $_POST['action'] === 'update_schedule') {
            $id_lichtrinh = (int)$_POST['id_lichtrinh'];
            $id_dichvu = (int)$_POST['id_dichvu'];
            $time = $_POST['time'];
            $ngay_vi = trim($_POST['ngay_vi']);
            $title_vi = trim($_POST['title_vi']);
            $content_vi = trim($_POST['content_vi']);
            $ngay_en = trim($_POST['ngay_en']);
            $title_en = trim($_POST['title_en']);
            $content_en = trim($_POST['content_en']);

            // Cập nhật bảng lichtrinh (không còn cột ngay)
            $sql = "UPDATE lichtrinh SET time = ?, id_dichvu = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $time, $id_dichvu, $id_lichtrinh);
            if ($stmt->execute()) {
                // Cập nhật hoặc thêm tiếng Việt
                $check_query = "SELECT COUNT(*) as count FROM lichtrinh_ngonngu WHERE id_lichtrinh = ? AND id_ngonngu = 1";
                $stmt = $conn->prepare($check_query);
                $stmt->bind_param("i", $id_lichtrinh);
                $stmt->execute();
                $check_row = $stmt->get_result()->fetch_assoc();

                if ($check_row['count'] > 0) {
                    $sql = "UPDATE lichtrinh_ngonngu SET ngay = ?, title = ?, content = ? WHERE id_lichtrinh = ? AND id_ngonngu = 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssi", $ngay_vi, $title_vi, $content_vi, $id_lichtrinh);
                } else {
                    $sql = "INSERT INTO lichtrinh_ngonngu (id_lichtrinh, id_ngonngu, ngay, title, content) VALUES (?, 1, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("isss", $id_lichtrinh, $ngay_vi, $title_vi, $content_vi);
                }
                if (!$stmt->execute()) {
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật/thêm nội dung lịch trình tiếng Việt: ' . $conn->error]);
                    exit;
                }

                // Cập nhật hoặc thêm tiếng Anh
                $check_query = "SELECT COUNT(*) as count FROM lichtrinh_ngonngu WHERE id_lichtrinh = ? AND id_ngonngu = 2";
                $stmt = $conn->prepare($check_query);
                $stmt->bind_param("i", $id_lichtrinh);
                $stmt->execute();
                $check_row = $stmt->get_result()->fetch_assoc();

                if ($check_row['count'] > 0) {
                    $sql = "UPDATE lichtrinh_ngonngu SET ngay = ?, title = ?, content = ? WHERE id_lichtrinh = ? AND id_ngonngu = 2";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssi", $ngay_en, $title_en, $content_en, $id_lichtrinh);
                } else {
                    $sql = "INSERT INTO lichtrinh_ngonngu (id_lichtrinh, id_ngonngu, ngay, title, content) VALUES (?, 2, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("isss", $id_lichtrinh, $ngay_en, $title_en, $content_en);
                }
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Cập nhật lịch trình thành công!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật/thêm nội dung lịch trình tiếng Anh: ' . $conn->error]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật thời gian lịch trình: ' . $conn->error]);
            }
            $stmt->close();
            exit;
        }
        
        // Xóa lịch trình
        if (isset($_POST['action']) && $_POST['action'] === 'delete_schedule') {
            $id_lichtrinh = (int)$_POST['id_lichtrinh'];
            
            // Xóa từ bảng lichtrinh_ngonngu
            $sql = "DELETE FROM lichtrinh_ngonngu WHERE id_lichtrinh = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_lichtrinh);
            $stmt->execute();
            
            // Xóa từ bảng lichtrinh
            $sql = "DELETE FROM lichtrinh WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_lichtrinh);
            $stmt->execute();
            
            echo json_encode(['success' => true, 'message' => 'Xóa lịch trình thành công!']);
            exit;
        }
        
        // Cập nhật thông tin bao gồm/không bao gồm
        if (isset($_POST['action']) && $_POST['action'] === 'update_includes') {
            $id_dichvu = (int)$_POST['id_dichvu'];
            $include_vi = trim($_POST['include_vi']);
            $non_include_vi = trim($_POST['non_include_vi']);
            $note_vi = trim($_POST['note_vi']);
            $include_en = trim($_POST['include_en']);
            $non_include_en = trim($_POST['non_include_en']);
            $note_en = trim($_POST['note_en']);

            // Kiểm tra xem đã có bản ghi tour chưa
            $sql = "SELECT id FROM tour WHERE id_dichvu = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_dichvu);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $tour = $result->fetch_assoc();
                $id_tour = $tour['id'];
            } else {
                // Tạo mới tour
                $sql = "INSERT INTO tour (id_dichvu) VALUES (?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id_dichvu);
                $stmt->execute();
                $id_tour = $conn->insert_id;
            }

            // Cập nhật hoặc thêm tiếng Việt
            $sql = "SELECT COUNT(*) as count FROM tour_ngonngu WHERE id_tour = ? AND id_ngonngu = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_tour);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                $sql = "UPDATE tour_ngonngu SET include = ?, non_include = ?, note = ? WHERE id_tour = ? AND id_ngonngu = 1";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $include_vi, $non_include_vi, $note_vi, $id_tour);
            } else {
                $sql = "INSERT INTO tour_ngonngu (id_tour, id_ngonngu, include, non_include, note) VALUES (?, 1, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isss", $id_tour, $include_vi, $non_include_vi, $note_vi);
            }
            if (!$stmt->execute()) {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật/thêm thông tin tiếng Việt: ' . $conn->error]);
                exit;
            }

            // Cập nhật hoặc thêm tiếng Anh
            $sql = "SELECT COUNT(*) as count FROM tour_ngonngu WHERE id_tour = ? AND id_ngonngu = 2";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_tour);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                $sql = "UPDATE tour_ngonngu SET include = ?, non_include = ?, note = ? WHERE id_tour = ? AND id_ngonngu = 2";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $include_en, $non_include_en, $note_en, $id_tour);
            } else {
                $sql = "INSERT INTO tour_ngonngu (id_tour, id_ngonngu, include, non_include, note) VALUES (?, 2, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isss", $id_tour, $include_en, $non_include_en, $note_en);
            }
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật thông tin bao gồm/không bao gồm thành công!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật/thêm thông tin tiếng Anh: ' . $conn->error]);
            }
            exit;
        }
        // Thêm ảnh
        if (isset($_POST['action']) && $_POST['action'] === 'add_image') {
            $id_dichvu = (int)$_POST['id_dichvu'];
            $id_topic = (int)$_POST['id_topic'];
            $is_primary = isset($_POST['is_primary']) ? (int)$_POST['is_primary'] : 0;
            
            // Kiểm tra id_dichvu và id_topic
            $check_dichvu = $conn->prepare("SELECT id FROM dichvu WHERE id = ?");
            $check_dichvu->bind_param("i", $id_dichvu);
            $check_dichvu->execute();
            if ($check_dichvu->get_result()->num_rows === 0) {
                echo json_encode(['success' => false, 'message' => 'ID dịch vụ không hợp lệ']);
                exit;
            }
            
            $check_topic = $conn->prepare("SELECT id FROM thuvien WHERE id = ?");
            $check_topic->bind_param("i", $id_topic);
            $check_topic->execute();
            if ($check_topic->get_result()->num_rows === 0) {
                echo json_encode(['success' => false, 'message' => 'ID topic không hợp lệ']);
                exit;
            }
            
            // Kiểm tra xem đã có ảnh chính cho id_dichvu này chưa
            if ($is_primary) {
                $check_primary = $conn->prepare("SELECT id FROM anhdichvu WHERE id_dichvu = ? AND is_primary = 1");
                $check_primary->bind_param("i", $id_dichvu);
                $check_primary->execute();
                if ($check_primary->get_result()->num_rows > 0) {
                    echo json_encode(['success' => false, 'message' => 'Chỉ được phép có một ảnh chính cho mỗi tour!']);
                    exit;
                }
            }
            
            if (isset($_FILES['image'])) {
                switch ($_FILES['image']['error']) {
                    case UPLOAD_ERR_OK:
                        $imageName = uploadImage($_FILES['image']);
                        if ($imageName) {
                            // Kiểm tra file có thực sự tồn tại
                            if (file_exists('../../view/img/' . $imageName)) {
                                // Nếu ảnh được chọn là ảnh chính, đặt các ảnh khác thành không chính
                                if ($is_primary) {
                                    $reset_primary = $conn->prepare("UPDATE anhdichvu SET is_primary = 0 WHERE id_dichvu = ?");
                                    $reset_primary->bind_param("i", $id_dichvu);
                                    $reset_primary->execute();
                                }
                                $stmt = $conn->prepare("INSERT INTO anhdichvu (image, is_primary, id_dichvu, id_topic) VALUES (?, ?, ?, ?)");
                                $stmt->bind_param("siii", $imageName, $is_primary, $id_dichvu, $id_topic);
                                if ($stmt->execute()) {
                                    echo json_encode(['success' => true, 'message' => 'Thêm ảnh thành công!']);
                                } else {
                                    error_log("SQL Error: " . $stmt->error);
                                    echo json_encode(['success' => false, 'message' => 'Lỗi SQL: ' . $stmt->error]);
                                }
                                $stmt->close();
                            } else {
                                error_log("File not found after upload: ../../view/img/$imageName");
                                echo json_encode(['success' => false, 'message' => 'File ảnh không tồn tại sau khi upload']);
                            }
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Lỗi khi upload hình ảnh. Kiểm tra log để biết chi tiết.']);
                        }
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        echo json_encode(['success' => false, 'message' => 'Vui lòng chọn file ảnh']);
                        break;
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        echo json_encode(['success' => false, 'message' => 'File quá lớn']);
                        break;
                    default:
                        echo json_encode(['success' => false, 'message' => 'Lỗi không xác định khi tải file']);
                        break;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy file ảnh']);
            }
            exit;
        }

        // Xóa ảnh
        if (isset($_POST['action']) && $_POST['action'] === 'delete_image') {
            $id_image = (int)$_POST['id_image'];
            $image_name = trim($_POST['image_name']);
            $upload_dir = '../../view/img';
            $file_path = $upload_dir . $image_name;

            // Xóa file khỏi server
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            // Xóa bản ghi khỏi cơ sở dữ liệu
            $stmt = $conn->prepare("DELETE FROM anhdichvu WHERE id = ?");
            $stmt->bind_param("i", $id_image);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Xóa ảnh thành công!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa ảnh: ' . $conn->error]);
            }
            $stmt->close();
            exit;
        }
        // Cập nhật mô tả tour
        if (isset($_POST['action']) && $_POST['action'] === 'update_description') {
            $id_dichvu = (int)$_POST['id_dichvu'];
            $content_vi = trim($_POST['content_vi']);
            $content_en = trim($_POST['content_en']);

            // Cập nhật hoặc thêm tiếng Việt
            $sql = "SELECT id FROM motatour WHERE id_dichvu = ? AND id_ngonngu = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_dichvu);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $sql = "UPDATE motatour SET content = ? WHERE id_dichvu = ? AND id_ngonngu = 1";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $content_vi, $id_dichvu);
            } else {
                $sql = "INSERT INTO motatour (id_dichvu, id_ngonngu, content) VALUES (?, 1, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("is", $id_dichvu, $content_vi);
            }
            if (!$stmt->execute()) {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật/thêm mô tả tiếng Việt: ' . $conn->error]);
                exit;
            }

            // Cập nhật hoặc thêm tiếng Anh
            $sql = "SELECT id FROM motatour WHERE id_dichvu = ? AND id_ngonngu = 2";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_dichvu);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $sql = "UPDATE motatour SET content = ? WHERE id_dichvu = ? AND id_ngonngu = 2";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $content_en, $id_dichvu);
            } else {
                $sql = "INSERT INTO motatour (id_dichvu, id_ngonngu, content) VALUES (?, 2, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("is", $id_dichvu, $content_en);
            }
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật mô tả tour thành công!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật/thêm mô tả tiếng Anh: ' . $conn->error]);
            }
            exit;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()]);
        exit;
    }
}
// Xử lý POST request để lấy dữ liệu điểm nổi bật hoặc lịch trình tiếng Anh
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    if ($_POST['action'] === 'get_highlight' && isset($_POST['id_tienich']) && isset($_POST['id_ngonngu'])) {
        $id_tienich = (int)$_POST['id_tienich'];
        $id_ngonngu = (int)$_POST['id_ngonngu'];
        $sql = "SELECT title, content FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_tienich, $id_ngonngu);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo json_encode(['success' => true, 'data' => $result->fetch_assoc()]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy dữ liệu']);
        }
        $stmt->close();
        exit;
    }

    // Lấy dữ liệu lịch trình tiếng Anh
    if ($_POST['action'] === 'get_schedule' && isset($_POST['id_lichtrinh']) && isset($_POST['id_ngonngu'])) {
        $id_lichtrinh = (int)$_POST['id_lichtrinh'];
        $id_ngonngu = (int)$_POST['id_ngonngu'];
        $sql = "SELECT ngay, title, content FROM lichtrinh_ngonngu WHERE id_lichtrinh = ? AND id_ngonngu = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_lichtrinh, $id_ngonngu);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy dữ liệu']);
        }
        $stmt->close();
        exit;
    }

}
// Lấy danh sách tour
$id_dichvu = isset($_GET['id_dichvu']) ? (int)$_GET['id_dichvu'] : 0;

// Lấy các tour có chứa từ "tour" trong title
$sql = "SELECT d.id, dn.title, d.price 
        FROM dichvu d 
        JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu 
        WHERE dn.id_ngonngu = ? AND LOWER(dn.title) LIKE ? 
        ORDER BY d.id";
$stmt = $conn->prepare($sql);
$search_term = '%tour%';
$stmt->bind_param("is", $id_ngonngu, $search_term);
$stmt->execute();
$tours = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
// Nếu có tour được chọn, lấy thông tin chi tiết
$selected_tour = null;
$highlights = [];
$schedules = [];
$tour_includes = null;
// Lấy danh sách ảnh
$sql = "SELECT id, image, is_primary FROM anhdichvu WHERE id_dichvu = ? AND id_topic = ?";
$stmt = $conn->prepare($sql);
$id_topic = 1; // Giả sử id_topic là 1 dựa trên thuvien.sql
$stmt->bind_param("ii", $id_dichvu, $id_topic);
$stmt->execute();
$images = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
// Lấy mô tả tour
$sql = "SELECT content 
        FROM motatour 
        WHERE id_dichvu = ? AND id_ngonngu = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_dichvu, $id_ngonngu);
$stmt->execute();
$result = $stmt->get_result();
$tour_description = $result->num_rows > 0 ? $result->fetch_assoc() : null;
if ($id_dichvu > 0) {
    // Lấy thông tin tour
    $sql = "SELECT d.id, dn.title, d.price 
            FROM dichvu d 
            JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu 
            WHERE d.id = ? AND dn.id_ngonngu = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_dichvu, $id_ngonngu);
    $stmt->execute();
    $selected_tour = $stmt->get_result()->fetch_assoc();
    
    // Lấy điểm nổi bật
    $sql = "SELECT t.id, tn.title, tn.content, t.icon 
            FROM tienichtour tt 
            JOIN tienich_ngonngu tn ON tt.id_tienich = tn.id_tienich 
            JOIN tienich t ON tt.id_tienich = t.id 
            WHERE tt.id_dichvu = ? AND tn.id_ngonngu = ? AND t.active = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_dichvu, $id_ngonngu);
    $stmt->execute();
    $highlights = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Lấy lịch trình
    $sql = "SELECT lt.id, lt.time, ln.ngay, ln.title, ln.content 
            FROM lichtrinh lt 
            JOIN lichtrinh_ngonngu ln ON lt.id = ln.id_lichtrinh 
            WHERE lt.id_dichvu = ? AND ln.id_ngonngu = ? 
            ORDER BY lt.time";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_dichvu, $id_ngonngu);
    $stmt->execute();
    $schedules = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Lấy thông tin bao gồm/không bao gồm
    $sql = "SELECT tn.include, tn.non_include, tn.note 
            FROM tour t 
            JOIN tour_ngonngu tn ON t.id = tn.id_tour 
            WHERE t.id_dichvu = ? AND tn.id_ngonngu = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_dichvu, $id_ngonngu);
    $stmt->execute();
    $result = $stmt->get_result();
    $tour_includes = $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

ob_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tour - Liberty Lào Cai</title>
    <link rel="stylesheet" href="/libertylaocai/view/css/quanlytour.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
                <h1><i class="fas fa-map-marked-alt"></i> Quản lý Tour</h1>
        </header>

        <div class="admin-content">
            <!-- Tour Selection -->
            <div class="section-card">
                <div class="section-header">
                    <h2><i class="fas fa-list"></i> Chọn Tour</h2>
                </div>
                <div class="section-content">
                    <div class="tour-grid">
                        <?php foreach ($tours as $tour): ?>
                        <div class="tour-item <?php echo $tour['id'] == $id_dichvu ? 'active' : ''; ?>" 
                             onclick="selectTour(<?php echo $tour['id']; ?>)">
                            <div class="tour-info">
                                <h3><?php echo htmlspecialchars($tour['title']); ?></h3>
                                <div class="tour-price">
                                    <?php 
                                    if ($tour['price'] !== 'Liên hệ') {
                                        echo number_format($tour['price'], 0, ',', '.') . ' VNĐ';
                                    } else {
                                        echo 'Liên hệ';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="tour-actions">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <?php if ($selected_tour): ?>
            <!-- Tour Info Management -->
            <div class="section-card">
                <div class="section-header">
                    <h2><i class="fas fa-info-circle"></i> Thông tin cơ bản</h2>
                </div>
                <div class="section-content">
                    <form class="form-grid" id="tourInfoForm">
                        <input type="hidden" name="action" value="update_tour">
                        <input type="hidden" name="id_dichvu" value="<?php echo $id_dichvu; ?>">
                        
                        <!-- Tiếng Việt -->
                        <input type="hidden" name="id_ngonngu_vi" value="1">
                        <div class="form-group">
                            <label>Tiêu đề tour (Tiếng Việt)</label>
                            <input type="text" name="title_vi" value="<?php echo htmlspecialchars($selected_tour['title']); ?>" required>
                        </div>
                        
                        <!-- Tiếng Anh -->
                        <input type="hidden" name="id_ngonngu_en" value="2">
                        <div class="form-group">
                            <label>Tiêu đề tour (Tiếng Anh)</label>
                            <input type="text" name="title_en" value="<?php 
                                $sql = "SELECT title FROM dichvu_ngonngu WHERE id_dichvu = ? AND id_ngonngu = 2";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $id_dichvu);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                echo $result->num_rows > 0 ? htmlspecialchars($result->fetch_assoc()['title']) : '';
                            ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Giá tour</label>
                            <input type="text" name="price" value="<?php echo $selected_tour['price']; ?>" 
                                placeholder="Ví dụ: 1500000 hoặc Liên hệ">
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Highlights Management -->
            <div class="section-card">
                <div class="section-header">
                    <h2><i class="fas fa-star"></i> Điểm nổi bật</h2>
                    <button class="btn btn-primary" onclick="openModal('highlightModal')">
                        <i class="fas fa-plus"></i> Thêm Điểm Nổi Bật
                    </button>
                </div>
                <div class="section-content">
                    <div class="tours-grid">
                        <?php
                        $highlights_query = "SELECT t.id as id_tienich, t.icon, tn.title, tn.content, tt.id_dichvu 
                                            FROM tienich t 
                                            LEFT JOIN tienich_ngonngu tn ON t.id = tn.id_tienich 
                                            LEFT JOIN tienichtour tt ON t.id = tt.id_tienich 
                                            WHERE tn.id_ngonngu = ? AND tt.id_dichvu = ? AND t.active = 1";
                        $stmt = $conn->prepare($highlights_query);
                        $stmt->bind_param("ii", $id_ngonngu, $id_dichvu);
                        $stmt->execute();
                        $highlights_result = $stmt->get_result();
                        while ($highlight = $highlights_result->fetch_assoc()):
                        ?>
                        <div class="tour-item">
                            <div class="tour-header">
                                <h3><?php echo htmlspecialchars($highlight['title']); ?></h3>
                                <div class="tour-actions">
                                    <button class="btn btn-small btn-secondary" onclick="editHighlight({
                                        id_tienich: <?php echo (int)$highlight['id_tienich']; ?>,
                                        title: '<?php echo addslashes(htmlspecialchars($highlight['title'] ?? '')); ?>',
                                        content: '<?php echo addslashes(htmlspecialchars($highlight['content'] ?? '')); ?>',
                                        icon: '<?php echo addslashes(htmlspecialchars($highlight['icon'] ?? '')); ?>',
                                        id_dichvu: <?php echo (int)$highlight['id_dichvu']; ?>
                                    })">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-small btn-danger" onclick="deleteHighlight(<?php echo (int)$highlight['id_tienich']; ?>, <?php echo (int)$highlight['id_dichvu']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="tour-content">
                                <p>Biểu tượng: <i class="<?php echo htmlspecialchars($highlight['icon']); ?>"></i></p>
                                <p>Nội dung: <?php echo htmlspecialchars($highlight['content']); ?></p>
                            </div>
                        </div>
                        <?php endwhile; ?>
                        <?php if ($highlights_result->num_rows == 0): ?>
                        <div class="empty-state">
                            <i class="fas fa-star"></i>
                            <p>Chưa có điểm nổi bật nào</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Modal Thêm/Sửa Điểm Nổi Bật -->
            <div id="highlightModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="highlightModalTitle">Thêm Điểm Nổi Bật Mới</h3>
                        <span class="close" onclick="closeModal('highlightModal')">×</span>
                    </div>
                    <form id="highlightForm" method="POST">
                        <input type="hidden" name="action" value="add_highlight" id="highlightAction">
                        <input type="hidden" name="id_tienich" id="highlightId">
                        <input type="hidden" name="id_dichvu" value="<?php echo $id_dichvu; ?>">
                        
                        <!-- Tiếng Việt -->
                        <input type="hidden" name="id_ngonngu_vi" value="1">
                        <div class="form-group">
                            <label for="highlight_title_vi">Tiêu đề điểm nổi bật (Tiếng Việt):</label>
                            <input type="text" name="title_vi" id="highlight_title_vi" required>
                        </div>
                        <div class="form-group">
                            <label for="highlight_content_vi">Nội dung điểm nổi bật (Tiếng Việt):</label>
                            <textarea name="content_vi" id="highlight_content_vi" rows="4" required></textarea>
                        </div>
                        
                        <!-- Tiếng Anh -->
                        <input type="hidden" name="id_ngonngu_en" value="2">
                        <div class="form-group">
                            <label for="highlight_title_en">Tiêu đề điểm nổi bật (Tiếng Anh):</label>
                            <input type="text" name="title_en" id="highlight_title_en" required>
                        </div>
                        <div class="form-group">
                            <label for="highlight_content_en">Nội dung điểm nổi bật (Tiếng Anh):</label>
                            <textarea name="content_en" id="highlight_content_en" rows="4" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="highlight_icon">Biểu tượng (Icon):</label>
                            <div class="icon-select-container">
                                <select id="highlightIconSelect" onchange="updateHighlightIcon()">
                                    <option value="">-- Chọn biểu tượng --</option>
                                    <?php
                                    $icons_query = "SELECT DISTINCT icon FROM tienich WHERE icon IS NOT NULL AND icon != ''";
                                    $icons_result = mysqli_query($conn, $icons_query);
                                    while ($row = mysqli_fetch_assoc($icons_result)):
                                    ?>
                                        <option value="<?php echo htmlspecialchars($row['icon']); ?>">
                                            <?php echo htmlspecialchars($row['icon']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                    <option value="custom">Tùy chỉnh Icon</option>
                                </select>
                                <input type="text" id="highlightIconCustom" style="display: none;" placeholder="Nhập lớp CSS của biểu tượng">
                                <input type="hidden" name="icon" id="highlightIcon">
                                <span id="iconPreview" class="icon-preview"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('highlightModal')">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Schedule Management -->
            <div class="section-card">
                <div class="section-header">
                    <h2><i class="fas fa-calendar-alt"></i> Lịch trình</h2>
                    <button class="btn btn-secondary" onclick="toggleForm('scheduleForm')">
                        <i class="fas fa-plus"></i> Thêm mới
                    </button>
                </div>
                <div class="section-content">
                    <!-- Add Schedule Form -->
                    <div class="form-container" id="scheduleForm" style="display: none;">
                        <form class="form-grid" id="addScheduleForm">
                            <input type="hidden" name="action" value="add_schedule">
                            <input type="hidden" name="id_dichvu" value="<?php echo $id_dichvu; ?>">
                            
                            <div class="form-group">
                                <label>Thời gian</label>
                                <input type="datetime-local" name="time" required>
                            </div>
                            
                            <!-- Tiếng Việt -->
                            <input type="hidden" name="id_ngonngu_vi" value="1">
                            <div class="form-group">
                                <label>Tiêu đề ngày (Tiếng Việt)</label>
                                <input type="text" name="ngay_vi" placeholder="Ví dụ: NGÀY 1: LÀNH CÁI - SAPA" required>
                            </div>
                            <div class="form-group">
                                <label>Tiêu đề hoạt động (Tiếng Việt)</label>
                                <input type="text" name="title_vi" required>
                            </div>
                            <div class="form-group full-width">
                                <label>Nội dung hoạt động (Tiếng Việt)</label>
                                <textarea name="content_vi" required></textarea>
                            </div>
                            
                            <!-- Tiếng Anh -->
                            <input type="hidden" name="id_ngonngu_en" value="2">
                            <div class="form-group">
                                <label>Tiêu đề ngày (Tiếng Anh)</label>
                                <input type="text" name="ngay_en" placeholder="Ví dụ: DAY 1: LAO CAI - SAPA" required>
                            </div>
                            <div class="form-group">
                                <label>Tiêu đề hoạt động (Tiếng Anh)</label>
                                <input type="text" name="title_en" required>
                            </div>
                            <div class="form-group full-width">
                                <label>Nội dung hoạt động (Tiếng Anh)</label>
                                <textarea name="content_en" required></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary" onclick="toggleForm('scheduleForm')">Hủy</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Thêm
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Schedule List -->
                    <div class="schedule-list">
                        <?php foreach ($schedules as $schedule): ?>
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="time"><?php echo date('H:i', strtotime($schedule['time'])); ?></div>
                                <div class="date"><?php echo date('d/m/Y', strtotime($schedule['time'])); ?></div>
                            </div>
                            <div class="schedule-content">
                                <h4><?php echo htmlspecialchars($schedule['ngay'] ?? 'NGÀY ' . date('d/m/Y', strtotime($schedule['time']))); ?></h4>
                                <h5><?php echo htmlspecialchars($schedule['title']); ?></h5>
                                <p><?php echo htmlspecialchars($schedule['content']); ?></p>
                            </div>
                            <div class="schedule-actions">
                                <button class="btn btn-small btn-secondary" onclick="editSchedule({
                                    id: <?php echo (int)$schedule['id']; ?>,
                                    time: '<?php echo htmlspecialchars($schedule['time']); ?>',
                                    ngay: '<?php echo addslashes(htmlspecialchars($schedule['ngay'] ?? '')); ?>',
                                    title: '<?php echo addslashes(htmlspecialchars($schedule['title'] ?? '')); ?>',
                                    content: '<?php echo addslashes(htmlspecialchars($schedule['content'] ?? '')); ?>'
                                })">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete" onclick="deleteSchedule(<?php echo $schedule['id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($schedules)): ?>
                        <div class="empty-state">
                            <i class="fas fa-calendar-alt"></i>
                            <p>Chưa có lịch trình nào</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Modal Chỉnh Sửa Lịch Trình -->
            <div id="scheduleModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="scheduleModalTitle">Chỉnh Sửa Lịch Trình</h3>
                        <span class="close" onclick="closeModal('scheduleModal')">×</span>
                    </div>
                    <form id="scheduleEditForm" method="POST">
                        <input type="hidden" name="action" value="update_schedule" id="scheduleAction">
                        <input type="hidden" name="id_lichtrinh" id="scheduleId">
                        <input type="hidden" name="id_dichvu" value="<?php echo $id_dichvu; ?>">
                        
                        <!-- Thời gian (chung cho cả hai ngôn ngữ) -->
                        <div class="form-group">
                            <label for="schedule_time">Thời gian:</label>
                            <input type="datetime-local" name="time" id="scheduleTime" required>
                        </div>
                        
                        <!-- Tiếng Việt -->
                        <input type="hidden" name="id_ngonngu_vi" value="1">
                        <div class="form-group">
                            <label for="schedule_ngay_vi">Tiêu đề ngày (Tiếng Việt):</label>
                            <input type="text" name="ngay_vi" id="schedule_ngay_vi" placeholder="Ví dụ: NGÀY 1: LÀNH CÁI - SAPA" required>
                        </div>
                        <div class="form-group">
                            <label for="schedule_title_vi">Tiêu đề hoạt động (Tiếng Việt):</label>
                            <input type="text" name="title_vi" id="schedule_title_vi" required>
                        </div>
                        <div class="form-group">
                            <label for="schedule_content_vi">Nội dung hoạt động (Tiếng Việt):</label>
                            <textarea name="content_vi" id="schedule_content_vi" rows="4" required></textarea>
                        </div>
                        
                        <!-- Tiếng Anh -->
                        <input type="hidden" name="id_ngonngu_en" value="2">
                        <div class="form-group">
                            <label for="schedule_ngay_en">Tiêu đề ngày (Tiếng Anh):</label>
                            <input type="text" name="ngay_en" id="schedule_ngay_en" placeholder="Ví dụ: DAY 1: LAO CAI - SAPA" required>
                        </div>
                        <div class="form-group">
                            <label for="schedule_title_en">Tiêu đề hoạt động (Tiếng Anh):</label>
                            <input type="text" name="title_en" id="schedule_title_en" required>
                        </div>
                        <div class="form-group">
                            <label for="schedule_content_en">Nội dung hoạt động (Tiếng Anh):</label>
                            <textarea name="content_en" id="schedule_content_en" rows="4" required></textarea>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('scheduleModal')">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Includes Management -->
            <div class="section-card">
                <div class="section-header">
                    <h2><i class="fas fa-list-check"></i> Bao gồm / Không bao gồm</h2>
                </div>
                <div class="section-content">
                    <form class="form-grid" id="includesForm">
                        <input type="hidden" name="action" value="update_includes">
                        <input type="hidden" name="id_dichvu" value="<?php echo $id_dichvu; ?>">
                        
                        <!-- Tiếng Việt -->
                        <input type="hidden" name="id_ngonngu_vi" value="1">
                        <div class="form-group full-width">
                            <label>Bao gồm (Tiếng Việt, mỗi dòng một mục)</label>
                            <textarea name="include_vi" rows="6" placeholder="Mỗi dòng là một mục bao gồm..."><?php echo $tour_includes ? htmlspecialchars($tour_includes['include']) : ''; ?></textarea>
                        </div>
                        <div class="form-group full-width">
                            <label>Không bao gồm (Tiếng Việt, mỗi dòng một mục)</label>
                            <textarea name="non_include_vi" rows="6" placeholder="Mỗi dòng là một mục không bao gồm..."><?php echo $tour_includes ? htmlspecialchars($tour_includes['non_include']) : ''; ?></textarea>
                        </div>
                        <div class="form-group full-width">
                            <label>Lưu ý (Tiếng Việt, mỗi dòng một lưu ý)</label>
                            <textarea name="note_vi" rows="4" placeholder="Mỗi dòng là một lưu ý..."><?php echo $tour_includes ? htmlspecialchars($tour_includes['note']) : ''; ?></textarea>
                        </div>
                        
                        <!-- Tiếng Anh -->
                        <input type="hidden" name="id_ngonngu_en" value="2">
                        <div class="form-group full-width">
                            <label>Bao gồm (Tiếng Anh, mỗi dòng một mục)</label>
                            <textarea name="include_en" rows="6" placeholder="Each line is an included item..."><?php 
                                $sql = "SELECT include FROM tour_ngonngu WHERE id_tour = (SELECT id FROM tour WHERE id_dichvu = ?) AND id_ngonngu = 2";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $id_dichvu);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                echo $result->num_rows > 0 ? htmlspecialchars($result->fetch_assoc()['include']) : '';
                            ?></textarea>
                        </div>
                        <div class="form-group full-width">
                            <label>Không bao gồm (Tiếng Anh, mỗi dòng một mục)</label>
                            <textarea name="non_include_en" rows="6" placeholder="Each line is an excluded item..."><?php 
                                $sql = "SELECT non_include FROM tour_ngonngu WHERE id_tour = (SELECT id FROM tour WHERE id_dichvu = ?) AND id_ngonngu = 2";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $id_dichvu);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                echo $result->num_rows > 0 ? htmlspecialchars($result->fetch_assoc()['non_include']) : '';
                            ?></textarea>
                        </div>
                        <div class="form-group full-width">
                            <label>Lưu ý (Tiếng Anh, mỗi dòng một lưu ý)</label>
                            <textarea name="note_en" rows="4" placeholder="Each line is a note..."><?php 
                                $sql = "SELECT note FROM tour_ngonngu WHERE id_tour = (SELECT id FROM tour WHERE id_dichvu = ?) AND id_ngonngu = 2";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $id_dichvu);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                echo $result->num_rows > 0 ? htmlspecialchars($result->fetch_assoc()['note']) : '';
                            ?></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>
            <!-- Description Management -->
             
            <div class="section-card">
                <div class="section-header">
                    <h2><i class="fas fa-file-alt"></i> Mô tả tour</h2>
                </div>
                <div class="section-content">
                    <form class="form-grid" id="descriptionForm">
                        <input type="hidden" name="action" value="update_description">
                        <input type="hidden" name="id_dichvu" value="<?php echo $id_dichvu; ?>">
                        
                        <!-- Tiếng Việt -->
                        <input type="hidden" name="id_ngonngu_vi" value="1">
                        <div class="form-group full-width">
                            <label>Mô tả tour (Tiếng Việt, mỗi đoạn văn cách nhau bằng dòng trống)</label>
                            <textarea name="content_vi" rows="8" placeholder="Nhập mô tả tour, mỗi đoạn văn cách nhau bằng dòng trống..."><?php echo $tour_description ? htmlspecialchars($tour_description['content']) : ''; ?></textarea>
                        </div>
                        
                        <!-- Tiếng Anh -->
                        <input type="hidden" name="id_ngonngu_en" value="2">
                        <div class="form-group full-width">
                            <label>Mô tả tour (Tiếng Anh, mỗi đoạn văn cách nhau bằng dòng trống)</label>
                            <textarea name="content_en" rows="8" placeholder="Enter tour description, each paragraph separated by a blank line..."><?php 
                                $sql = "SELECT content FROM motatour WHERE id_dichvu = ? AND id_ngonngu = 2";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $id_dichvu);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                echo $result->num_rows > 0 ? htmlspecialchars($result->fetch_assoc()['content']) : '';
                            ?></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Image Management -->
            <div class="section-card">
                <div class="section-header">
                    <h2><i class="fas fa-images"></i> Quản lý ảnh</h2>
                    <button class="btn btn-primary" onclick="toggleForm('imageForm')">
                        <i class="fas fa-plus"></i> Thêm ảnh mới
                    </button>
                </div>
                <div class="section-content">
                    <!-- Add Image Form -->
                    <div class="form-container" id="imageForm" style="display: none;">
                        <form class="form-grid" id="addImageForm" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="add_image">
                            <input type="hidden" name="id_dichvu" value="<?php echo $id_dichvu; ?>">
                            <input type="hidden" name="id_topic" value="1"> <!-- id_topic cố định là 1 -->
                            
                            <div class="form-group">
                                <label>Chọn ảnh</label>
                                <input type="file" name="image" accept="image/*" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Ảnh chính</label>
                                <input type="checkbox" name="is_primary" value="1">
                            </div>
                            
                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary" onclick="toggleForm('imageForm')">Hủy</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Tải lên
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Image List -->
                    <div class="image-grid">
                        <?php foreach ($images as $image): ?>
                        <div class="image-item">
                            <div class="image-preview">
                                <img src="/libertylaocai/view/img/<?php echo htmlspecialchars($image['image']); ?>" alt="Tour Image">
                                <?php if ($image['is_primary']): ?>
                                    <span class="primary-badge">Ảnh chính</span>
                                <?php endif; ?>
                            </div>
                            <div class="image-actions">
                                <button class="btn btn-small btn-danger" onclick="deleteImage(<?php echo $image['id']; ?>, '<?php echo htmlspecialchars($image['image']); ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($images)): ?>
                        <div class="empty-state">
                            <i class="fas fa-images"></i>
                            <p>Chưa có ảnh nào</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Đang xử lý...</p>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <div class="toast-content">
            <i class="fas fa-check-circle"></i>
            <span class="toast-message"></span>
        </div>
    </div>

    <script src="/libertylaocai/view/js/quanlytour.js"></script>
</body>
</html>
<?php
$current_tab = 'tour-management';
$tab_content = ob_get_clean();
include 'tabdichvu.php'; // Điều chỉnh đường dẫn nếu cần
?>