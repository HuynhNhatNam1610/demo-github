<?php
// Kết nối cơ sở dữ liệu
 session_start();
require_once '../../model/config/connect.php';

// Hàm upload ảnh
function uploadImage($file) {
    $targetDir = "../../view/img/";
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $uniqueName = uniqid() . '.' . $imageFileType;
    $targetFile = $targetDir . $uniqueName;

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) {
        return false;
    }

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $uniqueName;
    }
    return false;
}
// Lấy danh sách icon từ bảng tienich
$icons_query = "SELECT DISTINCT icon FROM tienich WHERE icon IS NOT NULL AND icon != ''";
$icons_result = mysqli_query($conn, $icons_query);
$icons = [];
while ($row = mysqli_fetch_assoc($icons_result)) {
    $icons[] = $row['icon'];
}

// Thêm các icon Bootstrap mẫu
$bootstrap_icons = [
    'bi bi-car-front',
    'bi bi-geo-alt',
    'bi bi-clock',
    'bi bi-wifi',
    'bi bi-person-check'
];

// Kết hợp danh sách icon từ DB và Bootstrap
$all_icons = array_unique(array_merge($icons, $bootstrap_icons));

// Lấy danh sách tiện ích
$features_query = "SELECT t.id as id_tienich, t.icon, tn.title, tn.content, td.page 
                  FROM tienich t 
                  LEFT JOIN tienich_ngonngu tn ON t.id = tn.id_tienich 
                  LEFT JOIN tienichdichvu td ON t.id = td.id_tienich 
                  WHERE tn.id_ngonngu = 1 AND td.page = 'dichvu'";
$features_result = mysqli_query($conn, $features_query);
// Xử lý yêu cầu AJAX lấy dữ liệu tiếng Anh của tiện ích
if (isset($_GET['action']) && $_GET['action'] === 'get_feature_en' && isset($_GET['id_tienich'])) {
    $id_tienich = (int)$_GET['id_tienich'];
    
    $stmt = $conn->prepare("SELECT title, content FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 2");
    $stmt->bind_param("i", $id_tienich);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc() ?? ['title' => '', 'content' => ''];
    
    header('Content-Type: application/json');
    echo json_encode($data);
    
    $stmt->close();
    $conn->close();
    exit();
}
// Xử lý các action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $response = ['success' => false, 'message' => ''];

    try {
        switch ($action) {
            case 'add_greeting':
                $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
                $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');

                $stmt = $conn->prepare("INSERT INTO nhungcauchaohoi () VALUES ()");
                if ($stmt->execute()) {
                    $id_nhungcauchaohoi = $conn->insert_id;
                    
                    $stmt = $conn->prepare("INSERT INTO nhungcauchaohoi_ngonngu (id_nhungcauchaohoi, id_ngonngu, content) VALUES (?, 1, ?)");
                    $stmt->bind_param("is", $id_nhungcauchaohoi, $content_vi);
                    $stmt->execute();
                    
                    if (!empty($content_en)) {
                        $stmt = $conn->prepare("INSERT INTO nhungcauchaohoi_ngonngu (id_nhungcauchaohoi, id_ngonngu, content) VALUES (?, 2, ?)");
                        $stmt->bind_param("is", $id_nhungcauchaohoi, $content_en);
                        $stmt->execute();
                    }
                    
                    $response['success'] = true;
                    $response['message'] = "Thêm lời chào mới thành công!";
                } else {
                    $response['message'] = "Lỗi khi tạo bản ghi lời chào: " . $conn->error;
                }
                $stmt->close();
                break;

            case 'update_active_greeting':
                $id_nhungcauchaohoi_ngonngu = (int)$_POST['id_nhungcauchaohoi_ngonngu'];
                $page = 'dichvu';

                // Kiểm tra xem lời chào có tồn tại không
                $check_query = "SELECT id_nhungcauchaohoi, id_ngonngu FROM nhungcauchaohoi_ngonngu WHERE id = ?";
                $stmt = $conn->prepare($check_query);
                $stmt->bind_param("i", $id_nhungcauchaohoi_ngonngu);
                $stmt->execute();
                $check_row = $stmt->get_result()->fetch_assoc();

                if ($check_row) {
                    $id_nhungcauchaohoi = $check_row['id_nhungcauchaohoi'];
                    $id_ngonngu = $check_row['id_ngonngu'];

                    // Tìm id_nhungcauchaohoi_ngonngu của bản ghi tiếng Việt (id_ngonngu = 1)
                    $stmt = $conn->prepare("SELECT id FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 1");
                    $stmt->bind_param("i", $id_nhungcauchaohoi);
                    $stmt->execute();
                    $result = $stmt->get_result()->fetch_assoc();
                    $id_nhungcauchaohoi_ngonngu_vi = $result['id'];

                    // Xóa các bản ghi cũ của page
                    $stmt = $conn->prepare("DELETE FROM loichaoduocchon WHERE page = ?");
                    $stmt->bind_param("s", $page);
                    $stmt->execute();

                    // Thêm bản ghi tiếng Việt
                    $stmt = $conn->prepare("INSERT INTO loichaoduocchon (id_nhungcauchaohoi_ngonngu, id_ngonngu, page, area) VALUES (?, 1, ?, '')");
                    $stmt->bind_param("is", $id_nhungcauchaohoi_ngonngu_vi, $page);
                    $stmt->execute();

                    // Kiểm tra và thêm bản ghi tiếng Anh nếu có
                    $check_en_query = "SELECT id FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 2";
                    $stmt = $conn->prepare($check_en_query);
                    $stmt->bind_param("i", $id_nhungcauchaohoi);
                    $stmt->execute();
                    $check_en_row = $stmt->get_result()->fetch_assoc();

                    if ($check_en_row) {
                        $id_nhungcauchaohoi_ngonngu_en = $check_en_row['id'];
                        $stmt = $conn->prepare("INSERT INTO loichaoduocchon (id_nhungcauchaohoi_ngonngu, id_ngonngu, page, area) VALUES (?, 2, ?, '')");
                        $stmt->bind_param("is", $id_nhungcauchaohoi_ngonngu_en, $page);
                        $stmt->execute();
                    }

                    $response['success'] = true;
                    $response['message'] = "Cập nhật lời chào được chọn thành công!";
                } else {
                    $response['message'] = "Lời chào không tồn tại!";
                }
                $stmt->close();
                break;

            case 'delete_greeting':
                $id_nhungcauchaohoi = (int)$_POST['id_nhungcauchaohoi'];

                // Kiểm tra xem id_nhungcauchaohoi có tồn tại
                $check_exists = $conn->prepare("SELECT id FROM nhungcauchaohoi WHERE id = ?");
                $check_exists->bind_param("i", $id_nhungcauchaohoi);
                $check_exists->execute();
                if ($check_exists->get_result()->num_rows === 0) {
                    $response['success'] = false;
                    $response['message'] = "Lời chào không tồn tại!";
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit();
                }
                $check_exists->close();

                // Kiểm tra sử dụng bởi các trang khác
                $check_usage_query = "SELECT DISTINCT page FROM loichaoduocchon lc 
                                    JOIN nhungcauchaohoi_ngonngu nn ON lc.id_nhungcauchaohoi_ngonngu = nn.id 
                                    WHERE nn.id_nhungcauchaohoi = ? AND lc.page != 'dichvu'";
                $stmt = $conn->prepare($check_usage_query);
                $stmt->bind_param("i", $id_nhungcauchaohoi);
                $stmt->execute();
                $usage_result = $stmt->get_result();
                
                if ($usage_result->num_rows > 0) {
                    $used_pages = [];
                    while ($row = $usage_result->fetch_assoc()) {
                        $used_pages[] = $row['page'];
                    }
                    $stmt->close();
                    $response['success'] = false;
                    $response['message'] = "Lời chào đang được sử dụng bởi trang: " . implode(", ", $used_pages) . ". Không thể xóa!";
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit();
                }
                $stmt->close();

                // Xóa dữ liệu
                $stmt = $conn->prepare("DELETE FROM loichaoduocchon WHERE id_nhungcauchaohoi_ngonngu IN (SELECT id FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ?)");
                $stmt->bind_param("i", $id_nhungcauchaohoi);
                $stmt->execute();

                $stmt = $conn->prepare("DELETE FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ?");
                $stmt->bind_param("i", $id_nhungcauchaohoi);
                if ($stmt->execute()) {
                    $stmt = $conn->prepare("DELETE FROM nhungcauchaohoi WHERE id = ?");
                    $stmt->bind_param("i", $id_nhungcauchaohoi);
                    if ($stmt->execute()) {
                        $response['success'] = true;
                        $response['message'] = "Xóa lời chào thành công!";
                    } else {
                        $response['message'] = "Lỗi khi xóa lời chào: " . $conn->error;
                    }
                } else {
                    $response['message'] = "Lỗi khi xóa nội dung lời chào: " . $conn->error;
                }
                $stmt->close();
                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
                break;

            case 'update_greeting':
                $id_nhungcauchaohoi_ngonngu = (int)$_POST['post_id'];
                $content_vi = mysqli_real_escape_string($conn, $_POST['content']);
                $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');

                // Kiểm tra xem id_nhungcauchaohoi_ngonngu có tồn tại
                $check_query = "SELECT id_nhungcauchaohoi FROM nhungcauchaohoi_ngonngu WHERE id = ?";
                $stmt = $conn->prepare($check_query);
                $stmt->bind_param("i", $id_nhungcauchaohoi_ngonngu);
                $stmt->execute();
                $check_row = $stmt->get_result()->fetch_assoc();

                if (!$check_row) {
                    $response['message'] = "Lời chào không tồn tại!";
                    break;
                }

                $id_nhungcauchaohoi = $check_row['id_nhungcauchaohoi'];

                // Cập nhật nội dung tiếng Việt
                $stmt = $conn->prepare("UPDATE nhungcauchaohoi_ngonngu SET content = ? WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 1");
                $stmt->bind_param("si", $content_vi, $id_nhungcauchaohoi);
                if ($stmt->execute()) {
                    // Kiểm tra và cập nhật/xóa nội dung tiếng Anh
                    $check_en_query = "SELECT COUNT(*) as count FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 2";
                    $stmt = $conn->prepare($check_en_query);
                    $stmt->bind_param("i", $id_nhungcauchaohoi);
                    $stmt->execute();
                    $check_en_row = $stmt->get_result()->fetch_assoc();

                    if (!empty($content_en)) {
                        if ($check_en_row['count'] > 0) {
                            // Cập nhật nếu bản ghi tiếng Anh đã tồn tại
                            $stmt = $conn->prepare("UPDATE nhungcauchaohoi_ngonngu SET content = ? WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 2");
                            $stmt->bind_param("si", $content_en, $id_nhungcauchaohoi);
                            $stmt->execute();
                        } else {
                            // Thêm mới bản ghi tiếng Anh nếu chưa tồn tại
                            $stmt = $conn->prepare("INSERT INTO nhungcauchaohoi_ngonngu (id_nhungcauchaohoi, id_ngonngu, content) VALUES (?, 2, ?)");
                            $stmt->bind_param("is", $id_nhungcauchaohoi, $content_en);
                            $stmt->execute();
                        }
                    } elseif ($check_en_row['count'] > 0) {
                        // Xóa bản ghi tiếng Anh nếu nội dung tiếng Anh rỗng
                        $stmt = $conn->prepare("DELETE FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 2");
                        $stmt->bind_param("i", $id_nhungcauchaohoi);
                        $stmt->execute();
                    }

                    $response['success'] = true;
                    $response['message'] = "Cập nhật lời chào thành công!";
                } else {
                    $response['message'] = "Lỗi khi cập nhật lời chào: " . $conn->error;
                }
                $stmt->close();
                break;

            case 'add_feature':
                $icon = mysqli_real_escape_string($conn, $_POST['icon']);
                $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi']);
                $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
                $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
                $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');

                if (empty($icon)) {
                    $response['message'] = "Vui lòng chọn hoặc nhập biểu tượng!";
                    break;
                }

                $stmt = $conn->prepare("INSERT INTO tienich (icon, active) VALUES (?, 1)");
                $stmt->bind_param("s", $icon);
                if ($stmt->execute()) {
                    $id_tienich = $conn->insert_id;
                    
                    $stmt = $conn->prepare("INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 1, ?, ?)");
                    $stmt->bind_param("iss", $id_tienich, $title_vi, $content_vi);
                    if ($stmt->execute()) {
                        if (!empty($title_en) || !empty($content_en)) {
                            $stmt = $conn->prepare("INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 2, ?, ?)");
                            $stmt->bind_param("iss", $id_tienich, $title_en, $content_en);
                            $stmt->execute();
                        }
                        
                        $stmt = $conn->prepare("INSERT INTO tienichdichvu (id_tienich, page) VALUES (?, 'dichvu')");
                        $stmt->bind_param("i", $id_tienich);
                        if ($stmt->execute()) {
                            $response['success'] = true;
                            $response['message'] = "Thêm tiện ích thành công!";
                        } else {
                            $response['message'] = "Lỗi khi thêm tiện ích vào trang: " . $conn->error;
                        }
                    } else {
                        $response['message'] = "Lỗi khi thêm nội dung tiện ích: " . $conn->error;
                    }
                } else {
                    $response['message'] = "Lỗi khi tạo tiện ích: " . $conn->error;
                }
                $stmt->close();
                break;

            case 'update_feature':
                $id_tienich = (int)$_POST['id_tienich'];
                $icon = mysqli_real_escape_string($conn, $_POST['icon']);
                $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi']);
                $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
                $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
                $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');

                if (empty($icon)) {
                    $response['message'] = "Vui lòng chọn hoặc nhập biểu tượng!";
                    break;
                }

                $stmt = $conn->prepare("UPDATE tienich SET icon = ? WHERE id = ?");
                $stmt->bind_param("si", $icon, $id_tienich);
                if ($stmt->execute()) {
                    $check_query = "SELECT COUNT(*) as count FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 1";
                    $stmt = $conn->prepare($check_query);
                    $stmt->bind_param("i", $id_tienich);
                    $stmt->execute();
                    $check_row = $stmt->get_result()->fetch_assoc();

                    if ($check_row['count'] > 0) {
                        $query = "UPDATE tienich_ngonngu SET title = ?, content = ? WHERE id_tienich = ? AND id_ngonngu = 1";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("ssi", $title_vi, $content_vi, $id_tienich);
                        $stmt->execute();
                    } else {
                        $insert_query = "INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 1, ?, ?)";
                        $stmt = $conn->prepare($insert_query);
                        $stmt->bind_param("iss", $id_tienich, $title_vi, $content_vi);
                        $stmt->execute();
                    }

                    $check_query = "SELECT COUNT(*) as count FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 2";
                    $stmt = $conn->prepare($check_query);
                    $stmt->bind_param("i", $id_tienich);
                    $stmt->execute();
                    $check_row = $stmt->get_result()->fetch_assoc();

                    if (!empty($title_en) || !empty($content_en)) {
                        if ($check_row['count'] > 0) {
                            $query = "UPDATE tienich_ngonngu SET title = ?, content = ? WHERE id_tienich = ? AND id_ngonngu = 2";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("ssi", $title_en, $content_en, $id_tienich);
                            $stmt->execute();
                        } else {
                            $insert_query = "INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 2, ?, ?)";
                            $stmt = $conn->prepare($insert_query);
                            $stmt->bind_param("iss", $id_tienich, $title_en, $content_en);
                            $stmt->execute();
                        }
                    } elseif ($check_row['count'] > 0) {
                        $delete_query = "DELETE FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 2";
                        $stmt = $conn->prepare($delete_query);
                        $stmt->bind_param("i", $id_tienich);
                        $stmt->execute();
                    }

                    $response['success'] = true;
                    $response['message'] = "Cập nhật tiện ích thành công!";
                } else {
                    $response['message'] = "Lỗi khi cập nhật tiện ích: " . $conn->error;
                }
                $stmt->close();
                break;

            case 'delete_feature':
                $id_tienich = (int)$_POST['id_tienich'];

                $stmt = $conn->prepare("DELETE FROM tienichdichvu WHERE id_tienich = ?");
                $stmt->bind_param("i", $id_tienich);
                if ($stmt->execute()) {
                    $stmt = $conn->prepare("DELETE FROM tienich_ngonngu WHERE id_tienich = ?");
                    $stmt->bind_param("i", $id_tienich);
                    $stmt->execute();
                    $stmt = $conn->prepare("DELETE FROM tienich WHERE id = ?");
                    $stmt->bind_param("i", $id_tienich);
                    if ($stmt->execute()) {
                        $response['success'] = true;
                        $response['message'] = "Xóa tiện ích thành công!";
                    } else {
                        $response['message'] = "Lỗi khi xóa tiện ích: " . $conn->error;
                    }
                } else {
                    $response['message'] = "Lỗi khi xóa liên kết tiện ích: " . $conn->error;
                }
                $stmt->close();
                break;

            case 'add_tour':
                $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi']);
                $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
                $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
                $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');
                $price_vi = mysqli_real_escape_string($conn, $_POST['price_vi'] ?? 'Liên hệ');

                $stmt = $conn->prepare("INSERT INTO dichvu (type, active, price) VALUES ('tour', 1, ?)");
                $stmt->bind_param("s", $price_vi);
                if ($stmt->execute()) {
                    $id_dichvu = $conn->insert_id;
                    
                    $stmt = $conn->prepare("INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title, content) VALUES (?, 1, ?, ?)");
                    $stmt->bind_param("iss", $id_dichvu, $title_vi, $content_vi);
                    if ($stmt->execute()) {
                        if (!empty($title_en) || !empty($content_en)) {
                            $stmt = $conn->prepare("INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title, content) VALUES (?, 2, ?, ?)");
                            $stmt->bind_param("iss", $id_dichvu, $title_en, $content_en);
                            $stmt->execute();
                        }
                        
                        if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] === 0) {
                            $imageName = uploadImage($_FILES['service_image']);
                            if ($imageName) {
                                $stmt = $conn->prepare("INSERT INTO anhdichvu (image, is_primary, id_dichvu, id_topic) VALUES (?, 1, ?, 3)");
                                $stmt->bind_param("si", $imageName, $id_dichvu);
                                $stmt->execute();
                            }
                        }
                        $response['success'] = true;
                        $response['message'] = "Thêm tour thành công!";
                    } else {
                        $response['message'] = "Lỗi khi thêm tour: " . $conn->error;
                    }
                } else {
                    $response['message'] = "Lỗi khi thêm dịch vụ: " . $conn->error;
                }
                $stmt->close();
                break;

            case 'update_tour':
                $id_dichvu = (int)$_POST['id_dichvu'];
                $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi']);
                $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
                $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
                $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');
                $price_vi = mysqli_real_escape_string($conn, $_POST['price_vi'] ?? 'Liên hệ');

                $stmt = $conn->prepare("UPDATE dichvu_ngonngu SET title=?, content=? WHERE id_dichvu=? AND id_ngonngu=1");
                $stmt->bind_param("ssi", $title_vi, $content_vi, $id_dichvu);
                if ($stmt->execute()) {
                    if (!empty($title_en) || !empty($content_en)) {
                        $check_query = "SELECT COUNT(*) as count FROM dichvu_ngonngu WHERE id_dichvu = ? AND id_ngonngu = 2";
                        $stmt = $conn->prepare($check_query);
                        $stmt->bind_param("i", $id_dichvu);
                        $stmt->execute();
                        $check_row = $stmt->get_result()->fetch_assoc();

                        if ($check_row['count'] > 0) {
                            $query = "UPDATE dichvu_ngonngu SET title=?, content=? WHERE id_dichvu=? AND id_ngonngu=2";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("ssi", $title_en, $content_en, $id_dichvu);
                            $stmt->execute();
                        } else {
                            $insert_query = "INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title, content) VALUES (?, 2, ?, ?)";
                            $stmt = $conn->prepare($insert_query);
                            $stmt->bind_param("iss", $id_dichvu, $title_en, $content_en);
                            $stmt->execute();
                        }
                    }
                    
                    $update_query = "UPDATE dichvu SET price=?, type='tour' WHERE id=?";
                    $stmt = $conn->prepare($update_query);
                    $stmt->bind_param("si", $price_vi, $id_dichvu);
                    $stmt->execute();
                    
                    if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] === 0) {
                        $imageName = uploadImage($_FILES['service_image']);
                        if ($imageName) {
                            $old_image_query = "SELECT image FROM anhdichvu WHERE id_dichvu=? AND is_primary=1";
                            $stmt = $conn->prepare($old_image_query);
                            $stmt->bind_param("i", $id_dichvu);
                            $stmt->execute();
                            $old_image = $stmt->get_result()->fetch_assoc()['image'] ?? null;
                            if ($old_image && file_exists('../../view/img/' . $old_image)) {
                                unlink('../../view/img/' . $old_image);
                            }
                            $stmt = $conn->prepare("DELETE FROM anhdichvu WHERE id_dichvu=? AND is_primary=1");
                            $stmt->bind_param("i", $id_dichvu);
                            $stmt->execute();
                            $stmt = $conn->prepare("INSERT INTO anhdichvu (image, is_primary, id_dichvu, id_topic) VALUES (?, 1, ?, 3)");
                            $stmt->bind_param("si", $imageName, $id_dichvu);
                            $stmt->execute();
                        }
                    }
                    $response['success'] = true;
                    $response['message'] = "Cập nhật tour thành công!";
                } else {
                    $response['message'] = "Lỗi khi cập nhật tour: " . $conn->error;
                }
                $stmt->close();
                break;

            case 'delete_tour':
                $id_dichvu = (int)$_POST['id_dichvu'];
                
                // Xóa bình luận liên quan trong bảng binhluan_dichvu
                $stmt = $conn->prepare("DELETE FROM binhluan_dichvu WHERE id_dichvu = ?");
                $stmt->bind_param("i", $id_dichvu);
                if ($stmt->execute()) {
                    // Xóa hình ảnh liên quan
                    $image_query = "SELECT image FROM anhdichvu WHERE id_dichvu = ?";
                    $stmt = $conn->prepare($image_query);
                    $stmt->bind_param("i", $id_dichvu);
                    $stmt->execute();
                    $image_result = $stmt->get_result();
                    while ($image = $image_result->fetch_assoc()) {
                        if ($image['image'] && file_exists('../../view/img/' . $image['image'])) {
                            unlink('../../view/img/' . $image['image']);
                        }
                    }
                    
                    // Xóa bản ghi hình ảnh
                    $stmt = $conn->prepare("DELETE FROM anhdichvu WHERE id_dichvu = ?");
                    $stmt->bind_param("i", $id_dichvu);
                    $stmt->execute();
                    
                    // Xóa bản ghi ngôn ngữ
                    $stmt = $conn->prepare("DELETE FROM dichvu_ngonngu WHERE id_dichvu = ?");
                    $stmt->bind_param("i", $id_dichvu);
                    $stmt->execute();
                    
                    // Xóa bản ghi tour
                    $stmt = $conn->prepare("DELETE FROM dichvu WHERE id = ?");
                    $stmt->bind_param("i", $id_dichvu);
                    if ($stmt->execute()) {
                        $response['success'] = true;
                        $response['message'] = "Xóa tour thành công!";
                    } else {
                        $response['message'] = "Lỗi khi xóa tour: " . $conn->error;
                    }
                } else {
                    $response['message'] = "Lỗi khi xóa bình luận liên quan: " . $conn->error;
                }
                $stmt->close();
                break;
            
            case 'add_service':
                $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi']);
                $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
                $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
                $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');
                $price_vi = mysqli_real_escape_string($conn, $_POST['price_vi'] ?? 'Liên hệ');

                $stmt = $conn->prepare("INSERT INTO dichvu (type, active, price) VALUES ('dichvu', 1, ?)");
                $stmt->bind_param("s", $price_vi);
                if ($stmt->execute()) {
                    $id_dichvu = $conn->insert_id;
                    
                    $stmt = $conn->prepare("INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title, content) VALUES (?, 1, ?, ?)");
                    $stmt->bind_param("iss", $id_dichvu, $title_vi, $content_vi);
                    if ($stmt->execute()) {
                        if (!empty($title_en) || !empty($content_en)) {
                            $stmt = $conn->prepare("INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title, content) VALUES (?, 2, ?, ?)");
                            $stmt->bind_param("iss", $id_dichvu, $title_en, $content_en);
                            $stmt->execute();
                        }
                        
                        if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] === 0) {
                            $imageName = uploadImage($_FILES['service_image']);
                            if ($imageName) {
                                $stmt = $conn->prepare("INSERT INTO anhdichvu (image, is_primary, id_dichvu, id_topic) VALUES (?, 1, ?, 3)");
                                $stmt->bind_param("si", $imageName, $id_dichvu);
                                $stmt->execute();
                            }
                        }
                        $response['success'] = true;
                        $response['message'] = "Thêm dịch vụ thành công!";
                    } else {
                        $response['message'] = "Lỗi khi thêm dịch vụ: " . $conn->error;
                    }
                } else {
                    $response['message'] = "Lỗi khi thêm dịch vụ: " . $conn->error;
                }
                $stmt->close();
                break;

            case 'update_service':
                $id_dichvu = (int)$_POST['id_dichvu'];
                $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi']);
                $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
                $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
                $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');
                $price_vi = mysqli_real_escape_string($conn, $_POST['price_vi'] ?? 'Liên hệ');

                $check_query = "SELECT COUNT(*) as count FROM dichvu_ngonngu WHERE id_dichvu = ? AND id_ngonngu = 1";
                $stmt = $conn->prepare($check_query);
                $stmt->bind_param("i", $id_dichvu);
                $stmt->execute();
                $check_row = $stmt->get_result()->fetch_assoc();

                if ($check_row['count'] > 0) {
                    $query = "UPDATE dichvu_ngonngu SET title = ?, content = ? WHERE id_dichvu = ? AND id_ngonngu = 1";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ssi", $title_vi, $content_vi, $id_dichvu);
                    $stmt->execute();
                } else {
                    $insert_query = "INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title, content) VALUES (?, 1, ?, ?)";
                    $stmt = $conn->prepare($insert_query);
                    $stmt->bind_param("iss", $id_dichvu, $title_vi, $content_vi);
                    $stmt->execute();
                }

                $check_query = "SELECT COUNT(*) as count FROM dichvu_ngonngu WHERE id_dichvu = ? AND id_ngonngu = 2";
                $stmt = $conn->prepare($check_query);
                $stmt->bind_param("i", $id_dichvu);
                $stmt->execute();
                $check_row = $stmt->get_result()->fetch_assoc();

                if (!empty($title_en) || !empty($content_en)) {
                    if ($check_row['count'] > 0) {
                        $query = "UPDATE dichvu_ngonngu SET title = ?, content = ? WHERE id_dichvu = ? AND id_ngonngu = 2";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("ssi", $title_en, $content_en, $id_dichvu);
                        $stmt->execute();
                    } else {
                        $insert_query = "INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title, content) VALUES (?, 2, ?, ?)";
                        $stmt = $conn->prepare($insert_query);
                        $stmt->bind_param("iss", $id_dichvu, $title_en, $content_en);
                        $stmt->execute();
                    }
                } elseif ($check_row['count'] > 0) {
                    $delete_query = "DELETE FROM dichvu_ngonngu WHERE id_dichvu = ? AND id_ngonngu = 2";
                    $stmt = $conn->prepare($delete_query);
                    $stmt->bind_param("i", $id_dichvu);
                    $stmt->execute();
                }

                $stmt = $conn->prepare("UPDATE dichvu SET price = ?, type = 'dichvu' WHERE id = ?");
                $stmt->bind_param("si", $price_vi, $id_dichvu);
                $stmt->execute();

                if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] === 0) {
                    $imageName = uploadImage($_FILES['service_image']);
                    if ($imageName) {
                        $old_image_query = "SELECT image FROM anhdichvu WHERE id_dichvu = ? AND is_primary = 1";
                        $stmt = $conn->prepare($old_image_query);
                        $stmt->bind_param("i", $id_dichvu);
                        $stmt->execute();
                        $old_image = $stmt->get_result()->fetch_assoc()['image'] ?? null;
                        if ($old_image && file_exists('../../view/img/' . $old_image)) {
                            unlink('../../view/img/' . $old_image);
                        }

                        $stmt = $conn->prepare("DELETE FROM anhdichvu WHERE id_dichvu = ? AND is_primary = 1");
                        $stmt->bind_param("i", $id_dichvu);
                        $stmt->execute();

                        $stmt = $conn->prepare("INSERT INTO anhdichvu (image, is_primary, id_dichvu, id_topic) VALUES (?, 1, ?, 3)");
                        $stmt->bind_param("si", $imageName, $id_dichvu);
                        $stmt->execute();
                    }
                }

                $response['success'] = true;
                $response['message'] = "Cập nhật dịch vụ thành công!";
                $stmt->close();
                break;
                
            case 'delete_service':
                $id_dichvu = (int)$_POST['id_dichvu'];
                
                // Xóa bình luận liên quan trong bảng binhluan_dichvu
                $stmt = $conn->prepare("DELETE FROM binhluan_dichvu WHERE id_dichvu = ?");
                $stmt->bind_param("i", $id_dichvu);
                if ($stmt->execute()) {
                    // Xóa hình ảnh liên quan
                    $image_query = "SELECT image FROM anhdichvu WHERE id_dichvu = ?";
                    $stmt = $conn->prepare($image_query);
                    $stmt->bind_param("i", $id_dichvu);
                    $stmt->execute();
                    $image_result = $stmt->get_result();
                    while ($image = $image_result->fetch_assoc()) {
                        if ($image['image'] && file_exists('../../view/img/' . $image['image'])) {
                            unlink('../../view/img/' . $image['image']);
                        }
                    }
                    
                    // Xóa bản ghi hình ảnh
                    $stmt = $conn->prepare("DELETE FROM anhdichvu WHERE id_dichvu = ?");
                    $stmt->bind_param("i", $id_dichvu);
                    $stmt->execute();
                    
                    // Xóa bản ghi ngôn ngữ
                    $stmt = $conn->prepare("DELETE FROM dichvu_ngonngu WHERE id_dichvu = ?");
                    $stmt->bind_param("i", $id_dichvu);
                    $stmt->execute();
                    
                    // Xóa bản ghi dịch vụ
                    $stmt = $conn->prepare("DELETE FROM dichvu WHERE id = ?");
                    $stmt->bind_param("i", $id_dichvu);
                    if ($stmt->execute()) {
                        $response['success'] = true;
                        $response['message'] = "Xóa dịch vụ thành công!";
                    } else {
                        $response['message'] = "Lỗi khi xóa dịch vụ: " . $conn->error;
                    }
                } else {
                    $response['message'] = "Lỗi khi xóa bình luận liên quan: " . $conn->error;
                }
                $stmt->close();
                break;

            default:
                $response['message'] = "Hành động không hợp lệ!";
        }
    } catch (Exception $e) {
        $response['message'] = "Lỗi server: " . $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Lấy dữ liệu hiển thị
$banners_query = "SELECT * FROM head_banner ORDER BY id DESC";
$banners_result = mysqli_query($conn, $banners_query);

$languages_query = "SELECT * FROM ngonngu";
$languages_result = mysqli_query($conn, $languages_query);

$greetings_query = "SELECT nn.id as id_nhungcauchaohoi_ngonngu, 
                    nn.id_nhungcauchaohoi,
                    nn.content as content_vi,
                    (SELECT content FROM nhungcauchaohoi_ngonngu nn2 WHERE nn2.id_nhungcauchaohoi = nn.id_nhungcauchaohoi AND nn2.id_ngonngu = 2) as content_en
                    FROM nhungcauchaohoi_ngonngu nn 
                    WHERE nn.id_ngonngu = 1 
                    ORDER BY nn.id_nhungcauchaohoi";
$greetings_result = mysqli_query($conn, $greetings_query);
$greetings = [];
while ($row = mysqli_fetch_assoc($greetings_result)) {
    $greetings[] = $row;
}

// Lấy lời chào hiện tại được chọn
$active_greeting_query = "SELECT l.id_nhungcauchaohoi_ngonngu,
                         (SELECT nn1.content 
                          FROM nhungcauchaohoi_ngonngu nn1 
                          WHERE nn1.id_nhungcauchaohoi = nn.id_nhungcauchaohoi 
                          AND nn1.id_ngonngu = 1) as content_vi,
                         (SELECT nn2.content 
                          FROM nhungcauchaohoi_ngonngu nn2 
                          WHERE nn2.id_nhungcauchaohoi = nn.id_nhungcauchaohoi 
                          AND nn2.id_ngonngu = 2) as content_en
                         FROM loichaoduocchon l 
                         JOIN nhungcauchaohoi_ngonngu nn ON l.id_nhungcauchaohoi_ngonngu = nn.id 
                         WHERE l.page = 'dichvu' 
                         ORDER BY nn.id_nhungcauchaohoi 
                         LIMIT 1";
$active_greeting_result = mysqli_query($conn, $active_greeting_query);
$active_greeting = mysqli_fetch_assoc($active_greeting_result);

// Lấy danh sách dịch vụ 
$services_query = "
    SELECT d.id as id_dichvu, dn.title as title_vi, dn.content as content_vi, 
           (SELECT title FROM dichvu_ngonngu WHERE id_dichvu = d.id AND id_ngonngu = 2) as title_en,
           (SELECT content FROM dichvu_ngonngu WHERE id_dichvu = d.id AND id_ngonngu = 2) as content_en,
           a.image, d.price, d.icon 
    FROM dichvu d 
    LEFT JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu 
    LEFT JOIN anhdichvu a ON d.id = a.id_dichvu AND a.is_primary = 1 
    WHERE dn.id_ngonngu = 1 AND d.type = 'dichvu'
    ORDER BY dn.id_dichvu";
$services_result = mysqli_query($conn, $services_query);

// Lấy danh sách tour
$tours_query = "
    SELECT d.id as id_dichvu, dn.title as title_vi, dn.content as content_vi, 
           (SELECT title FROM dichvu_ngonngu WHERE id_dichvu = d.id AND id_ngonngu = 2) as title_en,
           (SELECT content FROM dichvu_ngonngu WHERE id_dichvu = d.id AND id_ngonngu = 2) as content_en,
           a.image, d.price, d.icon 
    FROM dichvu d 
    LEFT JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu 
    LEFT JOIN anhdichvu a ON d.id = a.id_dichvu AND a.is_primary = 1 
    WHERE dn.id_ngonngu = 1 AND d.type = 'tour'
    ORDER BY dn.id_dichvu";
$tours_result = mysqli_query($conn, $tours_query);

ob_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Dịch Vụ - Liberty Lào Cai</title>
    <link rel="stylesheet" href="/libertylaocai/view/css/quanlydichvu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <?php include "sidebar.php"; ?>

    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-cogs"></i> Quản Lý Dịch Vụ Du Lịch</h1>
            <div class="admin-nav">
                <a href="dichvu.php" target="_blank" class="btn btn-preview">
                    <i class="fas fa-eye"></i> Xem Trang
                </a>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="admin-sections">

            <!-- Quản lý Lời Chào -->
            <section class="admin-section">
                <div class="section-header">
                    <h2><i class="fas fa-comment"></i> Quản Lý Lời Chào</h2>
                    <button class="btn btn-primary" onclick="openModal('greetingModal')">
                        <i class="fas fa-plus"></i> Thêm Lời Chào
                    </button>
                </div>

                <div class="form-container">
                    <div class="form-group">
                        <label>Lời chào hiện tại:</label>
                        <p>
                            <?php 
                            if ($active_greeting && !empty($active_greeting['content_vi'])) {
                                echo '<strong>Tiếng Việt:</strong> ' . htmlspecialchars($active_greeting['content_vi']) . '<br>';
                                if (!empty($active_greeting['content_en'])) {
                                    echo '<strong>Tiếng Anh:</strong> ' . htmlspecialchars($active_greeting['content_en']);
                                }
                            } else {
                                echo 'Chưa chọn lời chào hoặc lời chào tiếng Việt không tồn tại';
                            }
                            ?>
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="activeGreetingSelect">Chọn lời chào hoạt động:</label>
                        <form id="activeGreetingForm" method="POST">
                            <input type="hidden" name="action" value="update_active_greeting">
                                <select id="activeGreetingSelect" name="greeting_data" onchange="updateActiveGreetingInputs(this)">
                                    <option value="">-- Chọn lời chào --</option>
                                    <?php foreach ($greetings as $greeting): ?>
                                        <option value="<?php echo htmlspecialchars(json_encode($greeting)); ?>" 
                                            <?php echo ($active_greeting && $greeting['id_nhungcauchaohoi_ngonngu'] == $active_greeting['id_nhungcauchaohoi_ngonngu']) ? 'selected' : ''; ?>>
                                            Lời chào #<?php echo $greeting['id_nhungcauchaohoi']; ?>: 
                                            <?php echo htmlspecialchars(substr($greeting['content_vi'], 0, 60)); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <input type="hidden" name="id_nhungcauchaohoi_ngonngu" id="activeGreetingId">
                            <button type="submit" class="btn btn-primary" id="updateActiveGreetingBtn" disabled>
                                <i class="fas fa-save"></i> Cập Nhật Lời Chào Hoạt Động
                            </button>
                        </form>
                    </div>

                    <div class="form-group">
                        <label for="greetingSelect">Chỉnh sửa lời chào:</label>
                        <div class="greeting-select-container">
                            <select id="greetingSelect" onchange="loadGreeting(this)">
                                <option value="">-- Chọn lời chào --</option>
                                <?php foreach ($greetings as $greeting): ?>
                                    <option value="<?php echo htmlspecialchars(json_encode($greeting)); ?>">
                                        Lời chào #<?php echo $greeting['id_nhungcauchaohoi']; ?>: 
                                        <?php echo htmlspecialchars(substr($greeting['content_vi'], 0, 60)); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <form id="deleteGreetingForm" method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa lời chào này?')">
                                <input type="hidden" name="action" value="delete_greeting">
                                <input type="hidden" name="id_nhungcauchaohoi" id="deleteGreetingId">
                                <button type="submit" class="btn btn-small btn-danger" id="deleteGreetingBtn" disabled>
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </div>

                    <form id="greetingForm" method="POST" class="admin-form">
                        <input type="hidden" name="action" value="update_greeting">
                        <input type="hidden" name="post_id" id="greetingId">
                        <div class="form-group">
                            <label for="content_vi">Nội dung lời chào (Tiếng Việt):</label>
                            <textarea name="content" id="greetingContent_vi" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="content_en">Nội dung lời chào (Tiếng Anh):</label>
                            <textarea name="content_en" id="greetingContent_en" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" id="updateGreetingBtn" disabled>
                            <i class="fas fa-save"></i> Cập Nhật Lời Chào
                        </button>
                    </form>
                </div>
            </section>

            <!-- Modal Thêm Lời Chào -->
            <div id="greetingModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Thêm Lời Chào Mới</h3>
                        <span class="close" onclick="closeModal('greetingModal')">×</span>
                    </div>
                    <form id="addGreetingForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add_greeting">
                        <div class="form-group">
                            <label for="greeting_content_vi">Nội dung lời chào (Tiếng Việt):</label>
                            <textarea name="content_vi" id="greeting_content_vi" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="greeting_content_en">Nội dung lời chào (Tiếng Anh):</label>
                            <textarea name="content_en" id="greeting_content_en" rows="3"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('greetingModal')">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quản lý Dịch Vụ -->
            <section class="admin-section">
                <div class="section-header">
                    <h2><i class="fas fa-concierge-bell"></i> Quản Lý Dịch Vụ</h2>
                    <button class="btn btn-primary" onclick="openServiceTourModal('service')">
                        <i class="fas fa-plus"></i> Thêm Dịch Vụ
                    </button>
                </div>
                
                <div class="services-grid">
                    <?php 
                    mysqli_data_seek($services_result, 0); // Reset con trỏ
                    while ($service = mysqli_fetch_assoc($services_result)): 
                    ?>
                    <div class="service-item">
                        <div class="service-header">
                            <h3><?php echo htmlspecialchars($service['title_vi']); ?></h3>
                            <div class="service-actions">
                                <button class="btn btn-small btn-secondary" onclick="editService(<?php echo htmlspecialchars(json_encode($service)); ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa dịch vụ này?')">
                                    <input type="hidden" name="action" value="delete_service">
                                    <input type="hidden" name="id_dichvu" value="<?php echo $service['id_dichvu']; ?>">
                                    <button type="submit" class="btn btn-small btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="service-content">
                            <p><strong>Giá:</strong> <?php echo htmlspecialchars($service['price']); ?></p>
                            <?php if ($service['image']): ?>
                                <img src="../../view/img/<?php echo htmlspecialchars($service['image']); ?>" alt="<?php echo htmlspecialchars($service['title_vi']); ?>" class="service-image-preview">
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </section>

            <!-- Quản lý Tiện Ích -->
            <section class="admin-section">
                <div class="section-header">
                    <h2><i class="fas fa-star"></i> Quản Lý Tiện Ích</h2>
                    <button class="btn btn-primary" onclick="openModal('featureModal')">
                        <i class="fas fa-plus"></i> Thêm Tiện Ích
                    </button>
                </div>
                <div class="tours-grid">
                    <?php while ($feature = mysqli_fetch_assoc($features_result)): ?>
                    <div class="tour-item">
                        <div class="tour-header">
                            <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
                            <div class="tour-actions">
                                <button class="btn btn-small btn-secondary" onclick="editFeature(<?php echo htmlspecialchars(json_encode($feature)); ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa tiện ích này?')">
                                    <input type="hidden" name="action" value="delete_feature">
                                    <input type="hidden" name="id_tienich" value="<?php echo $feature['id_tienich']; ?>">
                                    <button type="submit" class="btn btn-small btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="tour-content">
                            <p>Biểu tượng: <i class="<?php echo htmlspecialchars($feature['icon']); ?>"></i></p>
                            <p>Nội dung: <?php echo htmlspecialchars($feature['content']); ?></p>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </section>

            <!-- Modal Thêm/Sửa Tiện Ích -->
            <div id="featureModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="featureModalTitle">Thêm Tiện Ích Mới</h3>
                        <span class="close" onclick="closeModal('featureModal')">×</span>
                    </div>
                    <form id="featureForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add_feature" id="featureAction">
                        <input type="hidden" name="id_tienich" id="featureId">
                        <div class="form-group">
                            <label for="feature_title_vi">Tiêu đề tiện ích (Tiếng Việt):</label>
                            <input type="text" name="title_vi" id="featureTitle_vi" required>
                        </div>
                        <div class="form-group">
                            <label for="feature_title_en">Tiêu đề tiện ích (Tiếng Anh):</label>
                            <input type="text" name="title_en" id="featureTitle_en">
                        </div>
                        <div class="form-group">
                            <label for="feature_icon">Biểu tượng (Icon):</label>
                            <div class="icon-select-container">
                                <select id="featureIconSelect" onchange="updateIcon()">
                                    <option value="">-- Chọn biểu tượng --</option>
                                    <?php foreach ($all_icons as $icon): ?>
                                        <option value="<?php echo htmlspecialchars($icon); ?>">
                                            <i class="<?php echo htmlspecialchars($icon); ?>"></i> <?php echo htmlspecialchars($icon); ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="custom">Custom Icon</option>
                                </select>
                                <input type="text" id="featureIconCustom" style="display: none;" placeholder="Nhập lớp CSS của biểu tượng">
                                <input type="hidden" name="icon" id="featureIcon">
                                <span id="iconPreview" class="icon-preview"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="feature_content_vi">Nội dung tiện ích (Tiếng Việt):</label>
                            <textarea name="content_vi" id="featureContent_vi" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="feature_content_en">Nội dung tiện ích (Tiếng Anh):</label>
                            <textarea name="content_en" id="featureContent_en" rows="4"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('featureModal')">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quản lý Tour -->
            <section class="admin-section">
                <div class="section-header">
                    <h2><i class="fas fa-map-marked-alt"></i> Quản Lý Tour Du Lịch</h2>
                    <button class="btn btn-primary" onclick="openServiceTourModal('tour')">
                        <i class="fas fa-plus"></i> Thêm Tour
                    </button>
                </div>
                
                <div class="tours-grid">
                    <?php while ($tour = mysqli_fetch_assoc($tours_result)): ?>
                    <div class="tour-item">
                        <div class="tour-header">
                            <h3><?php echo htmlspecialchars($tour['title_vi']); ?></h3>
                            <div class="tour-actions">
                                <button class="btn btn-small btn-secondary" onclick="editTour(<?php echo htmlspecialchars(json_encode($tour)); ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa tour này?')">
                                    <input type="hidden" name="action" value="delete_tour">
                                    <input type="hidden" name="id_dichvu" value="<?php echo $tour['id_dichvu']; ?>">
                                    <button type="submit" class="btn btn-small btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="tour-content">
                            <p><strong>Tiếng Việt:</strong> <?php echo htmlspecialchars($tour['content_vi']); ?></p>
                            <?php if ($tour['title_en'] || $tour['content_en']): ?>
                                <p><strong>Tiếng Anh:</strong> <?php echo htmlspecialchars($tour['content_en']); ?></p>
                            <?php endif; ?>
                            <?php if ($tour['image']): ?>
                                <img src="../../view/img/<?php echo htmlspecialchars($tour['image']); ?>" alt="<?php echo htmlspecialchars($tour['title_vi']); ?>" class="tour-image-preview">
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </section>
            <!-- Modal Thêm/Sửa Banner -->
            <div id="bannerModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="bannerModalTitle">Thêm Banner Mới</h3>
                        <span class="close" onclick="closeModal('bannerModal')">×</span>
                    </div>
                    <form id="bannerForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add_banner" id="bannerAction">
                        <input type="hidden" name="banner_id" id="bannerId">
                        
                        <div class="form-group">
                            <label for="banner_image">Hình ảnh banner:</label>
                            <input type="file" name="banner_image" id="bannerImage" accept="image/*">
                            <div id="currentBannerImage"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="page">Trang:</label>
                            <input type="text" name="page" id="bannerPage" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_topic">Topic ID:</label>
                            <input type="number" name="id_topic" id="bannerTopic" required>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('bannerModal')">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Thêm Dịch Vụ/Tour -->
            <div id="serviceTourModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="serviceTourModalTitle">Thêm Dịch Vụ/Tour Mới</h3>
                        <span class="close" onclick="closeModal('serviceTourModal')">×</span>
                    </div>
                    <form id="serviceTourForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add_service" id="serviceTourAction">
                        <input type="hidden" name="id_dichvu" id="serviceTourId">
                        <div class="form-group">
                            <label for="service_tour_title_vi">Tiêu đề (Tiếng Việt):</label>
                            <input type="text" name="title_vi" id="serviceTourTitle_vi" required>
                        </div>
                        <div class="form-group">
                            <label for="service_tour_title_en">Tiêu đề (Tiếng Anh):</label>
                            <input type="text" name="title_en" id="serviceTourTitle_en">
                        </div>
                        <div class="form-group">
                            <label for="service_tour_content_vi">Nội dung mô tả (Tiếng Việt):</label>
                            <textarea name="content_vi" id="serviceTourContent_vi" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="service_tour_content_en">Nội dung mô tả (Tiếng Anh):</label>
                            <textarea name="content_en" id="serviceTourContent_en" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="service_tour_price_vi">Giá:</label>
                            <input type="text" name="price_vi" id="serviceTourPrice_vi" value="Liên hệ" required>
                        </div>
                        <div class="form-group">
                            <label for="service_tour_image">Hình ảnh:</label>
                            <input type="file" name="service_image" id="serviceTourImage" accept="image/*">
                            <div id="currentServiceTourImage"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('serviceTourModal')">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal Chỉnh Sửa Dịch Vụ -->
            <div id="editServiceModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="editServiceModalTitle">Chỉnh Sửa Dịch Vụ</h3>
                        <span class="close" onclick="closeModal('editServiceModal')">×</span>
                    </div>
                    <form id="editServiceForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="update_service" id="editServiceAction">
                        <input type="hidden" name="id_dichvu" id="editServiceId">
                        <div class="form-group">
                            <label for="edit_service_title_vi">Tiêu đề dịch vụ (Tiếng Việt):</label>
                            <input type="text" name="title_vi" id="editServiceTitle_vi" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_service_title_en">Tiêu đề dịch vụ (Tiếng Anh):</label>
                            <input type="text" name="title_en" id="editServiceTitle_en">
                        </div>
                        <div class="form-group">
                            <label for="edit_service_content_vi">Nội dung (Tiếng Việt):</label>
                            <textarea name="content_vi" id="editServiceContent_vi" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_service_content_en">Nội dung (Tiếng Anh):</label>
                            <textarea name="content_en" id="editServiceContent_en" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_service_price_vi">Giá:</label>
                            <input type="text" name="price_vi" id="editServicePrice_vi" value="Liên hệ" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_service_image">Hình ảnh dịch vụ:</label>
                            <input type="file" name="service_image" id="editServiceImage" accept="image/*">
                            <div id="currentEditServiceImage"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('editServiceModal')">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="/libertylaocai/view/js/quanlydichvu.js"></script>
</body>
</html>
<?php
$current_tab = 'tour-service';
$tab_content = ob_get_clean();
include 'tabdichvu.php'; // Điều chỉnh đường dẫn nếu cần
?>