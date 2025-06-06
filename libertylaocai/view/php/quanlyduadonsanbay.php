<?php
session_start();
require_once '../../model/config/connect.php';

// Xử lý upload ảnh
function uploadImage($file, $uploadDir = '../../view/img/') {
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileName = time() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $fileName;
    }
    return false;
}

// Hàm trả về phản hồi JSON
function sendResponse($success, $message, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'message' => $message]);
    exit();
}

// AJAX lấy dữ liệu tiếng Anh cho FAQ
if (isset($_GET['action']) && $_GET['action'] === 'get_faq_en' && isset($_GET['id_cauhoithuonggap'])) {
    $id_cauhoithuonggap = (int)$_GET['id_cauhoithuonggap'];
    $query = "SELECT question, answer FROM cauhoithuonggap_ngonngu WHERE id_cauhoithuonggap = ? AND id_ngonngu = 2";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_cauhoithuonggap);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($data ?: ['question' => '', 'answer' => '']);
    $stmt->close();
    exit();
}

// AJAX lấy dữ liệu tiếng Anh cho xe đưa đón
if (isset($_GET['action']) && $_GET['action'] === 'get_vehicle_en' && isset($_GET['id_xeduadon'])) {
    $id_xeduadon = (int)$_GET['id_xeduadon'];
    $query = "SELECT name FROM xeduadon_ngonngu WHERE id_xeduadon = ? AND id_ngonngu = 2";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_xeduadon);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($data ?: ['name' => '']);
    $stmt->close();
    exit();
}


// AJAX lấy dữ liệu tiếng Anh cho mô tả
if (isset($_GET['action']) && $_GET['action'] === 'get_description_en' && isset($_GET['id_mota'])) {
    $id_mota = (int)$_GET['id_mota'];
    $query = "SELECT title, content FROM mota_ngonngu WHERE id_mota = ? AND id_ngonngu = 2";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_mota);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($data ?: ['title' => '', 'content' => '']);
    $stmt->close();
    exit();
}

// AJAX lấy dữ liệu tiếng Anh cho tiện ích
if (isset($_GET['action']) && $_GET['action'] === 'get_feature_en' && isset($_GET['id_tienich'])) {
    $id_tienich = (int)$_GET['id_tienich'];
    $query = "SELECT title, content FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 2";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_tienich);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($data ?: ['title' => '', 'content' => '']);
    $stmt->close();
    exit();
}
// AJAX lấy danh sách tiện ích
if (isset($_GET['action']) && $_GET['action'] === 'get_features') {
    $features_query = "SELECT t.id as id_tienich, t.icon, 
                      MAX(CASE WHEN tn.id_ngonngu = 1 THEN tn.title END) as title,
                      MAX(CASE WHEN tn.id_ngonngu = 1 THEN tn.content END) as content
                      FROM tienich t 
                      LEFT JOIN tienich_ngonngu tn ON t.id = tn.id_tienich 
                      LEFT JOIN tienichdichvu td ON t.id = td.id_tienich 
                      WHERE tn.id_ngonngu = 1 AND td.page = 'duadonsanbay'
                      GROUP BY t.id";
    $features_result = mysqli_query($conn, $features_query);
    $features = [];
    while ($row = mysqli_fetch_assoc($features_result)) {
        $features[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($features);
    exit();
}
// AJAX lấy thông tin tiện ích
if (isset($_GET['action']) && $_GET['action'] === 'get_feature' && isset($_GET['id_tienich'])) {
    $id_tienich = (int)$_GET['id_tienich'];
    $query = "SELECT t.id as id_tienich, t.icon, tn.title, tn.content 
              FROM tienich t 
              LEFT JOIN tienich_ngonngu tn ON t.id = tn.id_tienich 
              WHERE t.id = ? AND tn.id_ngonngu = 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_tienich);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($data ?: ['id_tienich' => '', 'icon' => '', 'title' => '', 'content' => '']);
    $stmt->close();
    exit();
}
// AJAX lấy danh sách mô tả
if (isset($_GET['action']) && $_GET['action'] === 'get_descriptions') {
    $descriptions_query = "SELECT m.id as id_mota, 
                          MAX(CASE WHEN mn.id_ngonngu = 1 THEN mn.title END) as title_vi,
                          MAX(CASE WHEN mn.id_ngonngu = 1 THEN mn.content END) as content_vi,
                          MAX(CASE WHEN mn.id_ngonngu = 2 THEN mn.title END) as title_en,
                          MAX(CASE WHEN mn.id_ngonngu = 2 THEN mn.content END) as content_en
                          FROM mota m 
                          LEFT JOIN mota_ngonngu mn ON m.id = mn.id_mota 
                          GROUP BY m.id 
                          ORDER BY m.id";
    $descriptions_result = mysqli_query($conn, $descriptions_query);
    $descriptions = [];
    while ($row = mysqli_fetch_assoc($descriptions_result)) {
        $descriptions[] = $row;
    }

    $active_description_query = "SELECT m.id as id_mota, 
                               MAX(CASE WHEN mn.id_ngonngu = 1 THEN mn.title END) as title_vi,
                               MAX(CASE WHEN mn.id_ngonngu = 1 THEN mn.content END) as content_vi,
                               MAX(CASE WHEN mn.id_ngonngu = 2 THEN mn.title END) as title_en,
                               MAX(CASE WHEN mn.id_ngonngu = 2 THEN mn.content END) as content_en
                               FROM chon_mo_ta cm 
                               JOIN mota_ngonngu mn ON cm.id_mota_ngonngu = mn.id 
                               JOIN mota m ON mn.id_mota = m.id
                               WHERE cm.area = 'airport-shuttle-description'
                               GROUP BY m.id";
    $active_description_result = mysqli_query($conn, $active_description_query);
    $active_description = mysqli_fetch_assoc($active_description_result);

    header('Content-Type: application/json');
    echo json_encode([
        'descriptions' => $descriptions,
        'active_description' => $active_description
    ]);
    exit();
}
// AJAX lấy danh sách FAQ
if (isset($_GET['action']) && $_GET['action'] === 'get_faqs') {
    $faqs_query = "SELECT c.id as id_cauhoithuonggap, cn.question, cn.answer 
                   FROM cauhoithuonggap c 
                   LEFT JOIN cauhoithuonggap_ngonngu cn ON c.id = cn.id_cauhoithuonggap 
                   WHERE cn.id_ngonngu = 1";
    $faqs_result = mysqli_query($conn, $faqs_query);
    $faqs = [];
    while ($row = mysqli_fetch_assoc($faqs_result)) {
        $faqs[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($faqs);
    exit();
}

// AJAX lấy danh sách xe
if (isset($_GET['action']) && $_GET['action'] === 'get_vehicles') {
    $vehicles_query = "SELECT x.* FROM xeduadon x WHERE x.id_dichvu = 1";
    $vehicles_result = mysqli_query($conn, $vehicles_query);
    $vehicles = [];
    while ($row = mysqli_fetch_assoc($vehicles_result)) {
        $vehicles[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($vehicles);
    exit();
}
// AJAX lấy danh sách lời chào
if (isset($_GET['action']) && $_GET['action'] === 'get_greetings') {
    $greetings_query = "SELECT n.id as id_nhungcauchaohoi, 
                          MAX(CASE WHEN nn.id_ngonngu = 1 THEN nn.content END) as content_vi,
                          MAX(CASE WHEN nn.id_ngonngu = 2 THEN nn.content END) as content_en
                          FROM nhungcauchaohoi n 
                          LEFT JOIN nhungcauchaohoi_ngonngu nn ON n.id = nn.id_nhungcauchaohoi 
                          GROUP BY n.id 
                          ORDER BY n.id";
    $greetings_result = mysqli_query($conn, $greetings_query);
    $greetings = [];
    while ($row = mysqli_fetch_assoc($greetings_result)) {
        $greetings[] = $row;
    }

    $active_greeting_query = "SELECT n.id as id_nhungcauchaohoi, 
                            MAX(CASE WHEN nn.id_ngonngu = 1 THEN nn.content END) as content_vi,
                            MAX(CASE WHEN nn.id_ngonngu = 2 THEN nn.content END) as content_en
                            FROM loichaoduocchon lc 
                            JOIN nhungcauchaohoi_ngonngu nn ON lc.id_nhungcauchaohoi_ngonngu = nn.id 
                            JOIN nhungcauchaohoi n ON nn.id_nhungcauchaohoi = n.id
                            WHERE lc.page = 'duadonsanbay' AND lc.area = 'airport-shuttle-greeting'
                            GROUP BY n.id";
    $active_greeting_result = mysqli_query($conn, $active_greeting_query);
    $active_greeting = mysqli_fetch_assoc($active_greeting_result);

    header('Content-Type: application/json');
    echo json_encode([
        'greetings' => $greetings,
        'active_greeting' => $active_greeting
    ]);
    exit();
}

// AJAX lấy dữ liệu tiếng Anh cho lời chào
if (isset($_GET['action']) && $_GET['action'] === 'get_greeting_en' && isset($_GET['id_nhungcauchaohoi'])) {
    $id_nhungcauchaohoi = (int)$_GET['id_nhungcauchaohoi'];
    $query = "SELECT content FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 2";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_nhungcauchaohoi);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($data ?: ['content' => '']);
    $stmt->close();
    exit();
}
// Xử lý các hành động POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add_greeting':
            $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
            $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');

            $stmt = $conn->prepare("INSERT INTO nhungcauchaohoi () VALUES ()");
            if ($stmt->execute()) {
                $id_nhungcauchaohoi = $conn->insert_id;
                $stmt = $conn->prepare("INSERT INTO nhungcauchaohoi_ngonngu (id_nhungcauchaohoi, id_ngonngu, content) VALUES (?, 1, ?)");
                $stmt->bind_param("is", $id_nhungcauchaohoi, $content_vi);
                if ($stmt->execute()) {
                    if (!empty($content_en)) {
                        $stmt = $conn->prepare("INSERT INTO nhungcauchaohoi_ngonngu (id_nhungcauchaohoi, id_ngonngu, content) VALUES (?, 2, ?)");
                        $stmt->bind_param("is", $id_nhungcauchaohoi, $content_en);
                        $stmt->execute();
                    }
                    sendResponse(true, "Thêm lời chào mới thành công!");
                } else {
                    sendResponse(false, "Lỗi khi thêm lời chào tiếng Việt: " . $conn->error, 500);
                }
            } else {
                sendResponse(false, "Lỗi khi tạo bản ghi lời chào: " . $conn->error, 500);
            }
            $stmt->close();
            break;

        case 'update_greeting':
            $id_nhungcauchaohoi = (int)$_POST['id_nhungcauchaohoi'];
            $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
            $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');

            $check_query = "SELECT COUNT(*) as count FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 1";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("i", $id_nhungcauchaohoi);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                $query = "UPDATE nhungcauchaohoi_ngonngu SET content = ? WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 1";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("si", $content_vi, $id_nhungcauchaohoi);
                $stmt->execute();
            } else {
                $query = "INSERT INTO nhungcauchaohoi_ngonngu (id_nhungcauchaohoi, id_ngonngu, content) VALUES (?, 1, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("is", $id_nhungcauchaohoi, $content_vi);
                $stmt->execute();
            }

            $check_query = "SELECT COUNT(*) as count FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 2";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("i", $id_nhungcauchaohoi);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                if (empty($content_en)) {
                    $query = "DELETE FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 2";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $id_nhungcauchaohoi);
                    $stmt->execute();
                } else {
                    $query = "UPDATE nhungcauchaohoi_ngonngu SET content = ? WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 2";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("si", $content_en, $id_nhungcauchaohoi);
                    $stmt->execute();
                }
            } else if (!empty($content_en)) {
                $query = "INSERT INTO nhungcauchaohoi_ngonngu (id_nhungcauchaohoi, id_ngonngu, content) VALUES (?, 2, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("is", $id_nhungcauchaohoi, $content_en);
                $stmt->execute();
            }

            sendResponse(true, "Cập nhật lời chào thành công!");
            $stmt->close();
            break;
        case 'delete_greeting':
            $id_nhungcauchaohoi = (int)$_POST['id_nhungcauchaohoi'];

            // Check if the greeting is used by pages other than 'duadonsanbay', ensuring unique pages
            $check_usage_query = "SELECT DISTINCT page FROM loichaoduocchon lc 
                                JOIN nhungcauchaohoi_ngonngu nn ON lc.id_nhungcauchaohoi_ngonngu = nn.id 
                                WHERE nn.id_nhungcauchaohoi = ? AND lc.page != 'duadonsanbay'";
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
                sendResponse(false, "Lời chào đang được sử dụng bởi trang: " . implode(", ", $used_pages) . ". Không thể xóa!", 400);
            }

            // If no other pages use it, proceed with deletion, including entries in loichaoduocchon for duadonsanbay
            $stmt = $conn->prepare("DELETE FROM loichaoduocchon WHERE id_nhungcauchaohoi_ngonngu IN (SELECT id FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ?)");
            $stmt->bind_param("i", $id_nhungcauchaohoi);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ?");
            $stmt->bind_param("i", $id_nhungcauchaohoi);
            if ($stmt->execute()) {
                $stmt = $conn->prepare("DELETE FROM nhungcauchaohoi WHERE id = ?");
                $stmt->bind_param("i", $id_nhungcauchaohoi);
                if ($stmt->execute()) {
                    sendResponse(true, "Xóa lời chào thành công!");
                } else {
                    sendResponse(false, "Lỗi khi xóa lời chào: " . $conn->error, 500);
                }
            } else {
                sendResponse(false, "Lỗi khi xóa nội dung lời chào: " . $conn->error, 500);
            }
            $stmt->close();
            break;

        case 'update_active_greeting':
            $id_nhungcauchaohoi = (int)$_POST['id_nhungcauchaohoi'];
            $area = 'airport-shuttle-greeting';
            $page = 'duadonsanbay';

            $check_query = "SELECT COUNT(*) as count FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 1";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("i", $id_nhungcauchaohoi);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                $stmt = $conn->prepare("SELECT id FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 1");
                $stmt->bind_param("i", $id_nhungcauchaohoi);
                $stmt->execute();
                $result = $stmt->get_result();
                $vi_row = $result->fetch_assoc();
                $id_nhungcauchaohoi_ngonngu_vi = $vi_row['id'];

                $stmt = $conn->prepare("DELETE FROM loichaoduocchon WHERE area = ? AND page = ?");
                $stmt->bind_param("ss", $area, $page);
                $stmt->execute();

                $stmt = $conn->prepare("SELECT id FROM ngonngu WHERE id = 1");
                $stmt->execute();
                $lang_vi_exists = $stmt->get_result()->num_rows > 0;

                if ($lang_vi_exists) {
                    $stmt = $conn->prepare("INSERT INTO loichaoduocchon (id_nhungcauchaohoi_ngonngu, id_ngonngu, page, area) VALUES (?, 1, ?, ?)");
                    $stmt->bind_param("iss", $id_nhungcauchaohoi_ngonngu_vi, $page, $area);
                    $stmt->execute();
                } else {
                    sendResponse(false, "Ngôn ngữ tiếng Việt (id_ngonngu = 1) không tồn tại trong bảng ngonngu!");
                }

                $check_en_query = "SELECT id FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 2";
                $stmt = $conn->prepare($check_en_query);
                $stmt->bind_param("i", $id_nhungcauchaohoi);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($en_row = $result->fetch_assoc()) {
                    $id_nhungcauchaohoi_ngonngu_en = $en_row['id'];

                    $stmt = $conn->prepare("SELECT id FROM ngonngu WHERE id = 2");
                    $stmt->execute();
                    $lang_en_exists = $stmt->get_result()->num_rows > 0;

                    if ($lang_en_exists) {
                        $stmt = $conn->prepare("INSERT INTO loichaoduocchon (id_nhungcauchaohoi_ngonngu, id_ngonngu, page, area) VALUES (?, 2, ?, ?)");
                        $stmt->bind_param("iss", $id_nhungcauchaohoi_ngonngu_en, $page, $area);
                        $stmt->execute();
                    } else {
                        sendResponse(false, "Ngôn ngữ tiếng Anh (id_ngonngu = 2) không tồn tại trong bảng ngonngu!");
                    }
                }

                sendResponse(true, "Cập nhật lời chào hoạt động thành công!");
            } else {
                sendResponse(false, "Lời chào không tồn tại!");
            }
            $stmt->close();
            break;

        case 'add_description':
            $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi']);
            $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
            $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
            $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');

            $stmt = $conn->prepare("INSERT INTO mota () VALUES ()");
            if ($stmt->execute()) {
                $id_mota = $conn->insert_id;
                $stmt = $conn->prepare("INSERT INTO mota_ngonngu (id_mota, id_ngonngu, title, content) VALUES (?, 1, ?, ?)");
                $stmt->bind_param("iss", $id_mota, $title_vi, $content_vi);
                if ($stmt->execute()) {
                    if (!empty($title_en) || !empty($content_en)) {
                        $stmt = $conn->prepare("INSERT INTO mota_ngonngu (id_mota, id_ngonngu, title, content) VALUES (?, 2, ?, ?)");
                        $stmt->bind_param("iss", $id_mota, $title_en, $content_en);
                        $stmt->execute();
                    }
                    sendResponse(true, "Thêm mô tả mới thành công!");
                } else {
                    sendResponse(false, "Lỗi khi thêm mô tả tiếng Việt: " . $conn->error, 500);
                }
            } else {
                sendResponse(false, "Lỗi khi tạo bản ghi mô tả: " . $conn->error, 500);
            }
            $stmt->close();
            break;

        case 'update_description':
            $id_mota = (int)$_POST['id_mota'];
            $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi']);
            $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
            $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
            $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');

            $check_query = "SELECT COUNT(*) as count FROM mota_ngonngu WHERE id_mota = ? AND id_ngonngu = 1";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("i", $id_mota);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                $query = "UPDATE mota_ngonngu SET title = ?, content = ? WHERE id_mota = ? AND id_ngonngu = 1";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssi", $title_vi, $content_vi, $id_mota);
                $stmt->execute();
            } else {
                $query = "INSERT INTO mota_ngonngu (id_mota, id_ngonngu, title, content) VALUES (?, 1, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("iss", $id_mota, $title_vi, $content_vi);
                $stmt->execute();
            }

            $check_query = "SELECT COUNT(*) as count FROM mota_ngonngu WHERE id_mota = ? AND id_ngonngu = 2";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("i", $id_mota);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                if (empty($title_en) && empty($content_en)) {
                    $query = "DELETE FROM mota_ngonngu WHERE id_mota = ? AND id_ngonngu = 2";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $id_mota);
                    $stmt->execute();
                } else {
                    $query = "UPDATE mota_ngonngu SET title = ?, content = ? WHERE id_mota = ? AND id_ngonngu = 2";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ssi", $title_en, $content_en, $id_mota);
                    $stmt->execute();
                }
            } else if (!empty($title_en) || !empty($content_en)) {
                $query = "INSERT INTO mota_ngonngu (id_mota, id_ngonngu, title, content) VALUES (?, 2, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("iss", $id_mota, $title_en, $content_en);
                $stmt->execute();
            }

            sendResponse(true, "Cập nhật mô tả thành công!");
            $stmt->close();
            break;

        case 'delete_description':
            $id_mota = (int)$_POST['id_mota'];

            $stmt = $conn->prepare("DELETE FROM mota_ngonngu WHERE id_mota = ?");
            $stmt->bind_param("i", $id_mota);
            if ($stmt->execute()) {
                $stmt = $conn->prepare("DELETE FROM mota WHERE id = ?");
                $stmt->bind_param("i", $id_mota);
                if ($stmt->execute()) {
                    $stmt = $conn->prepare("DELETE FROM chon_mo_ta WHERE id_mota_ngonngu IN (SELECT id FROM mota_ngonngu WHERE id_mota = ?)");
                    $stmt->bind_param("i", $id_mota);
                    $stmt->execute();
                    sendResponse(true, "Xóa mô tả thành công!");
                } else {
                    sendResponse(false, "Lỗi khi xóa mô tả: " . $conn->error, 500);
                }
            } else {
                sendResponse(false, "Lỗi khi xóa nội dung mô tả: " . $conn->error, 500);
            }
            $stmt->close();
            break;

case 'update_active_description':
    $id_mota = (int)$_POST['id_mota'];
    $area = 'airport-shuttle-description';

    // Kiểm tra sự tồn tại của mô tả tiếng Việt
    $check_query = "SELECT COUNT(*) as count FROM mota_ngonngu WHERE id_mota = ? AND id_ngonngu = 1";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("i", $id_mota);
    $stmt->execute();
    $check_row = $stmt->get_result()->fetch_assoc();

    if ($check_row['count'] > 0) {
        // Lấy id_mota_ngonngu cho tiếng Việt
        $stmt = $conn->prepare("SELECT id FROM mota_ngonngu WHERE id_mota = ? AND id_ngonngu = 1");
        $stmt->bind_param("i", $id_mota);
        $stmt->execute();
        $result = $stmt->get_result();
        $vi_row = $result->fetch_assoc();
        $id_mota_ngonngu_vi = $vi_row['id'];

        // Xóa mô tả hiện tại
        $stmt = $conn->prepare("DELETE FROM chon_mo_ta WHERE area = ?");
        $stmt->bind_param("s", $area);
        $stmt->execute();

        // Kiểm tra language_id = 1 có tồn tại trong ngonngu
        $stmt = $conn->prepare("SELECT id FROM ngonngu WHERE id = 1");
        $stmt->execute();
        $lang_vi_exists = $stmt->get_result()->num_rows > 0;

        if ($lang_vi_exists) {
            // Thêm mô tả tiếng Việt
            $stmt = $conn->prepare("INSERT INTO chon_mo_ta (area, id_mota_ngonngu, language_id) VALUES (?, ?, 1)");
            $stmt->bind_param("si", $area, $id_mota_ngonngu_vi);
            $stmt->execute();
        } else {
            sendResponse(false, "Ngôn ngữ tiếng Việt (language_id = 1) không tồn tại trong bảng ngonngu!");
        }

        // Kiểm tra và thêm mô tả tiếng Anh nếu có
        $check_en_query = "SELECT id FROM mota_ngonngu WHERE id_mota = ? AND id_ngonngu = 2";
        $stmt = $conn->prepare($check_en_query);
        $stmt->bind_param("i", $id_mota);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($en_row = $result->fetch_assoc()) {
            $id_mota_ngonngu_en = $en_row['id'];

            // Kiểm tra language_id = 2 có tồn tại trong ngonngu
            $stmt = $conn->prepare("SELECT id FROM ngonngu WHERE id = 2");
            $stmt->execute();
            $lang_en_exists = $stmt->get_result()->num_rows > 0;

            if ($lang_en_exists) {
                $stmt = $conn->prepare("INSERT INTO chon_mo_ta (area, id_mota_ngonngu, language_id) VALUES (?, ?, 2)");
                $stmt->bind_param("si", $area, $id_mota_ngonngu_en);
                $stmt->execute();
            } else {
                sendResponse(false, "Ngôn ngữ tiếng Anh (language_id = 2) không tồn tại trong bảng ngonngu!");
            }
        }

        sendResponse(true, "Cập nhật mô tả hoạt động thành công!");
    } else {
        sendResponse(false, "Mô tả không tồn tại!");
    }
    $stmt->close();
    break;
        case 'add_feature':
            $icon = mysqli_real_escape_string($conn, $_POST['icon']);
            $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi']);
            $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
            $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
            $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');
            $page = 'duadonsanbay';

            $stmt = $conn->prepare("INSERT INTO tienich (icon) VALUES (?)");
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
                    $stmt = $conn->prepare("INSERT INTO tienichdichvu (id_tienich, page) VALUES (?, ?)");
                    $stmt->bind_param("is", $id_tienich, $page);
                    $stmt->execute();
                    sendResponse(true, "Thêm tiện ích mới thành công!");
                } else {
                    sendResponse(false, "Lỗi khi thêm tiện ích tiếng Việt: " . $conn->error, 500);
                }
            } else {
                sendResponse(false, "Lỗi khi tạo bản ghi tiện ích: " . $conn->error, 500);
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
                    $query = "INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 1, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("iss", $id_tienich, $title_vi, $content_vi);
                    $stmt->execute();
                }

                $check_query = "SELECT COUNT(*) as count FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 2";
                $stmt = $conn->prepare($check_query);
                $stmt->bind_param("i", $id_tienich);
                $stmt->execute();
                $check_row = $stmt->get_result()->fetch_assoc();

                if ($check_row['count'] > 0) {
                    if (empty($title_en) && empty($content_en)) {
                        $query = "DELETE FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 2";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $id_tienich);
                        $stmt->execute();
                    } else {
                        $query = "UPDATE tienich_ngonngu SET title = ?, content = ? WHERE id_tienich = ? AND id_ngonngu = 2";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("ssi", $title_en, $content_en, $id_tienich);
                        $stmt->execute();
                    }
                } else if (!empty($title_en) || !empty($content_en)) {
                    $query = "INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 2, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("iss", $id_tienich, $title_en, $content_en);
                    $stmt->execute();
                }

                sendResponse(true, "Cập nhật tiện ích thành công!");
            } else {
                sendResponse(false, "Lỗi khi cập nhật tiện ích: " . $conn->error, 500);
            }
            $stmt->close();
            break;

        case 'delete_feature':
            $id_tienich = (int)$_POST['id_tienich'];

            $stmt = $conn->prepare("DELETE FROM tienich_ngonngu WHERE id_tienich = ?");
            $stmt->bind_param("i", $id_tienich);
            if ($stmt->execute()) {
                $stmt = $conn->prepare("DELETE FROM tienichdichvu WHERE id_tienich = ?");
                $stmt->bind_param("i", $id_tienich);
                $stmt->execute();
                $stmt = $conn->prepare("DELETE FROM tienich WHERE id = ?");
                $stmt->bind_param("i", $id_tienich);
                if ($stmt->execute()) {
                    sendResponse(true, "Xóa tiện ích thành công!");
                } else {
                    sendResponse(false, "Lỗi khi xóa tiện ích: " . $conn->error, 500);
                }
            } else {
                sendResponse(false, "Lỗi khi xóa nội dung tiện ích: " . $conn->error, 500);
            }
            $stmt->close();
            break;

        case 'add_faq':
            $question_vi = mysqli_real_escape_string($conn, $_POST['question_vi']);
            $answer_vi = mysqli_real_escape_string($conn, $_POST['answer_vi']);
            $question_en = mysqli_real_escape_string($conn, $_POST['question_en'] ?? '');
            $answer_en = mysqli_real_escape_string($conn, $_POST['answer_en'] ?? '');

            $stmt = $conn->prepare("INSERT INTO cauhoithuonggap () VALUES ()");
            if ($stmt->execute()) {
                $id_cauhoithuonggap = $conn->insert_id;
                $stmt = $conn->prepare("INSERT INTO cauhoithuonggap_ngonngu (id_cauhoithuonggap, id_ngonngu, question, answer) VALUES (?, 1, ?, ?)");
                $stmt->bind_param("iss", $id_cauhoithuonggap, $question_vi, $answer_vi);
                if ($stmt->execute()) {
                    if (!empty($question_en) || !empty($answer_en)) {
                        $stmt = $conn->prepare("INSERT INTO cauhoithuonggap_ngonngu (id_cauhoithuonggap, id_ngonngu, question, answer) VALUES (?, 2, ?, ?)");
                        $stmt->bind_param("iss", $id_cauhoithuonggap, $question_en, $answer_en);
                        $stmt->execute();
                    }
                    sendResponse(true, "Thêm câu hỏi thường gặp thành công!");
                } else {
                    sendResponse(false, "Lỗi khi thêm câu hỏi tiếng Việt: " . $conn->error, 500);
                }
            } else {
                sendResponse(false, "Lỗi khi tạo bản ghi câu hỏi: " . $conn->error, 500);
            }
            $stmt->close();
            break;

        case 'update_faq':
            $id_cauhoithuonggap = (int)$_POST['id_cauhoithuonggap'];
            $question_vi = mysqli_real_escape_string($conn, $_POST['question_vi']);
            $answer_vi = mysqli_real_escape_string($conn, $_POST['answer_vi']);
            $question_en = mysqli_real_escape_string($conn, $_POST['question_en'] ?? '');
            $answer_en = mysqli_real_escape_string($conn, $_POST['answer_en'] ?? '');

            $check_query = "SELECT COUNT(*) as count FROM cauhoithuonggap_ngonngu WHERE id_cauhoithuonggap = ? AND id_ngonngu = 1";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("i", $id_cauhoithuonggap);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                $query = "UPDATE cauhoithuonggap_ngonngu SET question = ?, answer = ? WHERE id_cauhoithuonggap = ? AND id_ngonngu = 1";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssi", $question_vi, $answer_vi, $id_cauhoithuonggap);
                $stmt->execute();
            } else {
                $insert_query = "INSERT INTO cauhoithuonggap_ngonngu (id_cauhoithuonggap, id_ngonngu, question, answer) VALUES (?, 1, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("iss", $id_cauhoithuonggap, $question_vi, $answer_vi);
                $stmt->execute();
            }

            $check_query = "SELECT COUNT(*) as count FROM cauhoithuonggap_ngonngu WHERE id_cauhoithuonggap = ? AND id_ngonngu = 2";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("i", $id_cauhoithuonggap);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                if (empty($question_en) && empty($answer_en)) {
                    $query = "DELETE FROM cauhoithuonggap_ngonngu WHERE id_cauhoithuonggap = ? AND id_ngonngu = 2";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $id_cauhoithuonggap);
                    $stmt->execute();
                } else {
                    $query = "UPDATE cauhoithuonggap_ngonngu SET question = ?, answer = ? WHERE id_cauhoithuonggap = ? AND id_ngonngu = 2";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ssi", $question_en, $answer_en, $id_cauhoithuonggap);
                    $stmt->execute();
                }
            } else if (!empty($question_en) || !empty($answer_en)) {
                $insert_query = "INSERT INTO cauhoithuonggap_ngonngu (id_cauhoithuonggap, id_ngonngu, question, answer) VALUES (?, 2, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("iss", $id_cauhoithuonggap, $question_en, $answer_en);
                $stmt->execute();
            }

            sendResponse(true, "Cập nhật câu hỏi thường gặp thành công!");
            $stmt->close();
            break;

        case 'delete_faq':
            $id_cauhoithuonggap = (int)$_POST['id_cauhoithuonggap'];
            $id_ngonngu = (int)$_POST['id_ngonngu'];

            $stmt = $conn->prepare("DELETE FROM cauhoithuonggap_ngonngu WHERE id_cauhoithuonggap = ? AND id_ngonngu = ?");
            $stmt->bind_param("ii", $id_cauhoithuonggap, $id_ngonngu);
            if ($stmt->execute()) {
                $check_query = "SELECT COUNT(*) as count FROM cauhoithuonggap_ngonngu WHERE id_cauhoithuonggap = ?";
                $stmt = $conn->prepare($check_query);
                $stmt->bind_param("i", $id_cauhoithuonggap);
                $stmt->execute();
                $check_row = $stmt->get_result()->fetch_assoc();

                if ($check_row['count'] == 0) {
                    $stmt = $conn->prepare("DELETE FROM cauhoithuonggap WHERE id = ?");
                    $stmt->bind_param("i", $id_cauhoithuonggap);
                    $stmt->execute();
                }
                sendResponse(true, "Xóa câu hỏi thường gặp thành công!");
            } else {
                sendResponse(false, "Lỗi khi xóa câu hỏi thường gặp: " . $conn->error, 500);
            }
            $stmt->close();
            break;

        case 'add_vehicle':
            $name_vi = mysqli_real_escape_string($conn, $_POST['name_vi']);
            $name_en = mysqli_real_escape_string($conn, $_POST['name_en'] ?? '');
            $price = mysqli_real_escape_string($conn, $_POST['price']);
            $number_seat = (int)$_POST['number_seat'];
            $id_dichvu = (int)$_POST['id_dichvu'];

            if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] === UPLOAD_ERR_OK) {
                $imageName = uploadImage($_FILES['vehicle_image']);
                if ($imageName) {
                    $stmt = $conn->prepare("INSERT INTO xeduadon (name, price, number_seat, image_car, id_dichvu) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssisi", $name_vi, $price, $number_seat, $imageName, $id_dichvu);
                    if ($stmt->execute()) {
                        $id_xeduadon = $conn->insert_id;
                        $stmt = $conn->prepare("INSERT INTO xeduadon_ngonngu (id_xeduadon, id_ngonngu, name) VALUES (?, 1, ?)");
                        $stmt->bind_param("is", $id_xeduadon, $name_vi);
                        $stmt->execute();
                        if (!empty($name_en)) {
                            $stmt = $conn->prepare("INSERT INTO xeduadon_ngonngu (id_xeduadon, id_ngonngu, name) VALUES (?, 2, ?)");
                            $stmt->bind_param("is", $id_xeduadon, $name_en);
                            $stmt->execute();
                        }
                        sendResponse(true, "Thêm xe đưa đón thành công!");
                    } else {
                        sendResponse(false, "Lỗi khi thêm xe đưa đón: " . $conn->error, 500);
                    }
                } else {
                    sendResponse(false, "Lỗi khi upload hình ảnh xe.", 400);
                }
            } else {
                sendResponse(false, "Vui lòng chọn hình ảnh xe.", 400);
            }
            $stmt->close();
            break;

        case 'update_vehicle':
            $id = (int)$_POST['id'];
            $name_vi = mysqli_real_escape_string($conn, $_POST['name_vi']);
            $name_en = mysqli_real_escape_string($conn, $_POST['name_en'] ?? '');
            $price = mysqli_real_escape_string($conn, $_POST['price']);
            $number_seat = (int)$_POST['number_seat'];
            $id_dichvu = (int)$_POST['id_dichvu'];

            $query = "UPDATE xeduadon SET name = ?, price = ?, number_seat = ?, id_dichvu = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssiii", $name_vi, $price, $number_seat, $id_dichvu, $id);
            if ($stmt->execute()) {
                $check_query = "SELECT COUNT(*) as count FROM xeduadon_ngonngu WHERE id_xeduadon = ? AND id_ngonngu = 1";
                $stmt = $conn->prepare($check_query);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $check_row = $stmt->get_result()->fetch_assoc();

                if ($check_row['count'] > 0) {
                    $query = "UPDATE xeduadon_ngonngu SET name = ? WHERE id_xeduadon = ? AND id_ngonngu = 1";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("si", $name_vi, $id);
                    $stmt->execute();
                } else {
                    $insert_query = "INSERT INTO xeduadon_ngonngu (id_xeduadon, id_ngonngu, name) VALUES (?, 1, ?)";
                    $stmt = $conn->prepare($insert_query);
                    $stmt->bind_param("is", $id, $name_vi);
                    $stmt->execute();
                }

                $check_query = "SELECT COUNT(*) as count FROM xeduadon_ngonngu WHERE id_xeduadon = ? AND id_ngonngu = 2";
                $stmt = $conn->prepare($check_query);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $check_row = $stmt->get_result()->fetch_assoc();

                if ($check_row['count'] > 0) {
                    if (empty($name_en)) {
                        $query = "DELETE FROM xeduadon_ngonngu WHERE id_xeduadon = ? AND id_ngonngu = 2";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                    } else {
                        $query = "UPDATE xeduadon_ngonngu SET name = ? WHERE id_xeduadon = ? AND id_ngonngu = 2";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("si", $name_en, $id);
                        $stmt->execute();
                    }
                } else if (!empty($name_en)) {
                    $insert_query = "INSERT INTO xeduadon_ngonngu (id_xeduadon, id_ngonngu, name) VALUES (?, 2, ?)";
                    $stmt = $conn->prepare($insert_query);
                    $stmt->bind_param("is", $id, $name_en);
                    $stmt->execute();
                }

                if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] === UPLOAD_ERR_OK) {
                    $imageName = uploadImage($_FILES['vehicle_image']);
                    if ($imageName) {
                        $old_image_query = "SELECT image_car FROM xeduadon WHERE id = ?";
                        $stmt = $conn->prepare($old_image_query);
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $old_image_result = $stmt->get_result();
                        $old_image = $old_image_result->fetch_assoc()['image_car'];
                        if ($old_image && file_exists('../../view/img/' . $old_image)) {
                            unlink('../../view/img/' . $old_image);
                        }
                        $update_image = "UPDATE xeduadon SET image_car = ? WHERE id = ?";
                        $stmt = $conn->prepare($update_image);
                        $stmt->bind_param("si", $imageName, $id);
                        $stmt->execute();
                    }
                }
                sendResponse(true, "Cập nhật xe đưa đón thành công!");
            } else {
                sendResponse(false, "Lỗi khi cập nhật xe đưa đón: " . $conn->error, 500);
            }
            $stmt->close();
            break;

        case 'delete_vehicle':
            $id = (int)$_POST['id'];

            $image_query = "SELECT image_car FROM xeduadon WHERE id = ?";
            $stmt = $conn->prepare($image_query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $image_result = $stmt->get_result();
            $image = $image_result->fetch_assoc()['image_car'];
            if ($image && file_exists('../../view/img/' . $image)) {
                unlink('../../view/img/' . $image);
            }

            $stmt = $conn->prepare("DELETE FROM xeduadon_ngonngu WHERE id_xeduadon = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM xeduadon WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                sendResponse(true, "Xóa xe đưa đón thành công!");
            } else {
                sendResponse(false, "Lỗi khi xóa xe đưa đón: " . $conn->error, 500);
            }
            $stmt->close();
            break;
    }
}

// Lấy dữ liệu hiển thị
$languages_query = "SELECT * FROM ngonngu";
$languages_result = mysqli_query($conn, $languages_query);

$greetings_query = "SELECT n.id as id_nhungcauchaohoi, 
                    MAX(CASE WHEN nn.id_ngonngu = 1 THEN nn.content END) as content_vi,
                    MAX(CASE WHEN nn.id_ngonngu = 2 THEN nn.content END) as content_en
                    FROM nhungcauchaohoi n 
                    LEFT JOIN nhungcauchaohoi_ngonngu nn ON n.id = nn.id_nhungcauchaohoi 
                    GROUP BY n.id 
                    ORDER BY n.id";
$greetings_result = mysqli_query($conn, $greetings_query);
$greetings = [];
while ($row = mysqli_fetch_assoc($greetings_result)) {
    $greetings[] = $row;
}

$active_greeting_query = "SELECT n.id as id_nhungcauchaohoi, 
                        MAX(CASE WHEN nn.id_ngonngu = 1 THEN nn.content END) as content_vi,
                        MAX(CASE WHEN nn.id_ngonngu = 2 THEN nn.content END) as content_en
                        FROM loichaoduocchon lc 
                        JOIN nhungcauchaohoi_ngonngu nn ON lc.id_nhungcauchaohoi_ngonngu = nn.id 
                        JOIN nhungcauchaohoi n ON nn.id_nhungcauchaohoi = n.id
                        WHERE lc.page = 'duadonsanbay' AND lc.area = 'airport-shuttle-greeting'
                        GROUP BY n.id";
$active_greeting_result = mysqli_query($conn, $active_greeting_query);
$active_greeting = mysqli_fetch_assoc($active_greeting_result);

$descriptions_query = "SELECT m.id as id_mota, 
                      MAX(CASE WHEN mn.id_ngonngu = 1 THEN mn.title END) as title_vi,
                      MAX(CASE WHEN mn.id_ngonngu = 1 THEN mn.content END) as content_vi,
                      MAX(CASE WHEN mn.id_ngonngu = 2 THEN mn.title END) as title_en,
                      MAX(CASE WHEN mn.id_ngonngu = 2 THEN mn.content END) as content_en
                      FROM mota m 
                      LEFT JOIN mota_ngonngu mn ON m.id = mn.id_mota 
                      GROUP BY m.id 
                      ORDER BY m.id";
$descriptions_result = mysqli_query($conn, $descriptions_query);
$descriptions = [];
while ($row = mysqli_fetch_assoc($descriptions_result)) {
    $descriptions[] = $row;
}

$active_description_query = "SELECT m.id as id_mota, 
                           MAX(CASE WHEN mn.id_ngonngu = 1 THEN mn.title END) as title_vi,
                           MAX(CASE WHEN mn.id_ngonngu = 1 THEN mn.content END) as content_vi,
                           MAX(CASE WHEN mn.id_ngonngu = 2 THEN mn.title END) as title_en,
                           MAX(CASE WHEN mn.id_ngonngu = 2 THEN mn.content END) as content_en
                           FROM chon_mo_ta cm 
                           JOIN mota_ngonngu mn ON cm.id_mota_ngonngu = mn.id 
                           JOIN mota m ON mn.id_mota = m.id
                           WHERE cm.area = 'airport-shuttle-description'
                           GROUP BY m.id";
$active_description_result = mysqli_query($conn, $active_description_query);
$active_description = mysqli_fetch_assoc($active_description_result);

// Lấy danh sách icon từ cơ sở dữ liệu
$icons_query = "SELECT DISTINCT icon FROM tienich WHERE icon IS NOT NULL AND icon != ''";
$icons_result = mysqli_query($conn, $icons_query);
$icons = [];
while ($row = mysqli_fetch_assoc($icons_result)) {
    $icons[] = $row['icon'];
}
$features_query = "SELECT t.id as id_tienich, t.icon, tn.title, tn.content, td.page 
                  FROM tienich t 
                  LEFT JOIN tienich_ngonngu tn ON t.id = tn.id_tienich 
                  LEFT JOIN tienichdichvu td ON t.id = td.id_tienich 
                  WHERE tn.id_ngonngu = 1 AND td.page = 'duadonsanbay'";
$features_result = mysqli_query($conn, $features_query);

$faqs_query = "SELECT c.id as id_cauhoithuonggap, cn.question, cn.answer 
               FROM cauhoithuonggap c 
               LEFT JOIN cauhoithuonggap_ngonngu cn ON c.id = cn.id_cauhoithuonggap 
               WHERE cn.id_ngonngu = 1";
$faqs_result = mysqli_query($conn, $faqs_query);

$vehicles_query = "SELECT x.* FROM xeduadon x WHERE x.id_dichvu = 1";
$vehicles_result = mysqli_query($conn, $vehicles_query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đưa Đón Sân Bay - Liberty Lào Cai</title>
    <link rel="stylesheet" href="/libertylaocai/view/css/quanlydichvu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-plane"></i> Quản Lý Đưa Đón Sân Bay</h1>
            <div class="admin-nav">
                <a href="duadonsanbay.php" target="_blank" class="btn btn-preview">
                    <i class="fas fa-eye"></i> Xem Trang
                </a>
            </div>
        </div>

        <div id="alertContainer"></div>

        <div class="admin-sections">
    
        <section class="admin-section">
            <div class="section-header">
                <h2><i class="fas fa-comment"></i> Quản Lý Lời Chào</h2>
                <button class="btn btn-primary" onclick="openAddModal('greetingModal')">
                    <i class="fas fa-plus"></i> Thêm Lời Chào
                </button>
            </div>

            <div class="form-container">
                <div class="form-group">
                    <label>Lời chào hiện tại:</label>
                    <p id="activeGreetingText">
                        <?php 
                        if ($active_greeting) {
                            echo '<strong>Tiếng Việt:</strong> ' . htmlspecialchars($active_greeting['content_vi']) . '<br>';
                            if (!empty($active_greeting['content_en'])) {
                                echo '<strong>Tiếng Anh:</strong> ' . htmlspecialchars($active_greeting['content_en']);
                            }
                        } else {
                            echo 'Chưa chọn lời chào';
                        }
                        ?>
                    </p>
                </div>
                <div class="form-group">
                    <label for="activeGreetingSelect">Chọn lời chào hoạt động:</label>
                    <form id="activeGreetingForm" method="POST">
                        <input type="hidden" name="action" value="update_active_greeting">
                        <select id="activeGreetingSelect" name="greeting_data" onchange="updateGreetingInputs(this)">
                            <option value="">-- Chọn lời chào --</option>
                            <?php foreach ($greetings as $greeting): ?>
                                <option value="<?php echo htmlspecialchars(json_encode($greeting)); ?>" 
                                    <?php echo ($active_greeting && $greeting['id_nhungcauchaohoi'] == $active_greeting['id_nhungcauchaohoi']) ? 'selected' : ''; ?>>
                                    Lời chào #<?php echo $greeting['id_nhungcauchaohoi']; ?>: 
                                    <?php echo htmlspecialchars(substr($greeting['content_vi'], 0, 60)); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="id_nhungcauchaohoi" id="activeGreetingId">
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
                        <form id="deleteGreetingForm" method="POST" style="display: inline;">
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
                    <input type="hidden" name="id_nhungcauchaohoi" id="greetingId">
                    <div class="form-group">
                        <label for="greetingContent_vi">Nội dung lời chào (Tiếng Việt):</label>
                        <textarea name="content_vi" id="greetingContent_vi" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="greetingContent_en">Nội dung lời chào (Tiếng Anh):</label>
                        <textarea name="content_en" id="greetingContent_en" rows="4"></textarea>
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
                <form id="addGreetingForm" method="POST">
                    <input type="hidden" name="action" value="add_greeting">
                    <div class="form-group">
                        <label for="greeting_content_vi">Nội dung lời chào (Tiếng Việt):</label>
                        <textarea name="content_vi" id="greeting_content_vi" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="greeting_content_en">Nội dung lời chào (Tiếng Anh):</label>
                        <textarea name="content_en" id="greeting_content_en" rows="4"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('greetingModal')">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>

            <!-- Quản lý Mô tả -->
            <section class="admin-section">
                <div class="section-header">
                    <h2><i class="fas fa-file-alt"></i> Quản Lý Mô Tả</h2>
                    <button class="btn btn-primary" onclick="openAddModal('descriptionModal')">
                        <i class="fas fa-plus"></i> Thêm Mô Tả
                    </button>
                </div>

                <div class="form-container">
                    <div class="form-group">
                        <label>Mô tả hiện tại:</label>
                        <p id="activeDescriptionText">
                            <?php 
                            if ($active_description) {
                                echo '<strong>Tiếng Việt:</strong> ' . htmlspecialchars($active_description['title_vi']) . '<br>' . htmlspecialchars($active_description['content_vi']) . '<br>';
                                if (!empty($active_description['title_en']) || !empty($active_description['content_en'])) {
                                    echo '<strong>Tiếng Anh:</strong> ' . htmlspecialchars($active_description['title_en']) . '<br>' . htmlspecialchars($active_description['content_en']);
                                }
                            } else {
                                echo 'Chưa chọn mô tả';
                            }
                            ?>
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="activeDescriptionSelect">Chọn mô tả hoạt động:</label>
                        <form id="activeDescriptionForm" method="POST">
                            <input type="hidden" name="action" value="update_active_description">
                            <select id="activeDescriptionSelect" name="description_data" onchange="updateActiveDescriptionInputs(this)">
                                <option value="">-- Chọn mô tả --</option>
                                <?php foreach ($descriptions as $description): ?>
                                    <option value="<?php echo htmlspecialchars(json_encode($description)); ?>" 
                                        <?php echo ($active_description && $description['id_mota'] == $active_description['id_mota']) ? 'selected' : ''; ?>">
                                        Mô tả #<?php echo $description['id_mota']; ?>: 
                                        <?php echo htmlspecialchars(substr($description['title_vi'], 0, 60)); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="id_mota" id="activeDescriptionId">
                            <button type="submit" class="btn btn-primary" id="updateActiveDescriptionBtn" disabled>
                                <i class="fas fa-save"></i> Cập Nhật Mô Tả Hoạt Động
                            </button>
                        </form>
                    </div>

                    <div class="form-group">
                        <label for="descriptionSelect">Chỉnh sửa mô tả:</label>
                        <div class="description-select-container">
                            <select id="descriptionSelect" onchange="loadDescription(this)">
                                <option value="">-- Chọn mô tả --</option>
                                <?php foreach ($descriptions as $description): ?>
                                    <option value="<?php echo htmlspecialchars(json_encode($description)); ?>">
                                        Mô tả #<?php echo $description['id_mota']; ?>: 
                                        <?php echo htmlspecialchars(substr($description['title_vi'], 0, 60)); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <form id="deleteDescriptionForm" method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete_description">
                                <input type="hidden" name="id_mota" id="deleteDescriptionId">
                                <button type="submit" class="btn btn-small btn-danger" id="deleteDescriptionBtn" disabled>
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </div>

                    <form id="descriptionForm" method="POST" class="admin-form">
                        <input type="hidden" name="action" value="update_description">
                        <input type="hidden" name="id_mota" id="descriptionId">
                        <div class="form-group">
                            <label for="descriptionTitle_vi">Tiêu đề mô tả (Tiếng Việt):</label>
                            <input type="text" name="title_vi" id="descriptionTitle_vi" required>
                        </div>
                        <div class="form-group">
                            <label for="descriptionContent_vi">Nội dung mô tả (Tiếng Việt):</label>
                            <textarea name="content_vi" id="descriptionContent_vi" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="descriptionTitle_en">Tiêu đề mô tả (Tiếng Anh):</label>
                            <input type="text" name="title_en" id="descriptionTitle_en">
                        </div>
                        <div class="form-group">
                            <label for="descriptionContent_en">Nội dung mô tả (Tiếng Anh):</label>
                            <textarea name="content_en" id="descriptionContent_en" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" id="updateDescriptionBtn" disabled>
                            <i class="fas fa-save"></i> Cập Nhật Mô Tả
                        </button>
                    </form>
                </div>
            </section>

            <!-- Modal Thêm Mô Tả -->
            <div id="descriptionModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Thêm Mô Tả Mới</h3>
                        <span class="close" onclick="closeModal('descriptionModal')">×</span>
                    </div>
                    <form id="addDescriptionForm" method="POST">
                        <input type="hidden" name="action" value="add_description">
                        <div class="form-group">
                            <label for="description_title_vi">Tiêu đề mô tả (Tiếng Việt):</label>
                            <input type="text" name="title_vi" id="description_title_vi" required>
                        </div>
                        <div class="form-group">
                            <label for="description_content_vi">Nội dung mô tả (Tiếng Việt):</label>
                            <textarea name="content_vi" id="description_content_vi" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="description_title_en">Tiêu đề mô tả (Tiếng Anh):</label>
                            <input type="text" name="title_en" id="description_title_en">
                        </div>
                        <div class="form-group">
                            <label for="description_content_en">Nội dung mô tả (Tiếng Anh):</label>
                            <textarea name="content_en" id="description_content_en" rows="4"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('descriptionModal')">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quản lý Tiện ích -->
            <section class="admin-section">
                <div class="section-header">
                    <h2><i class="fas fa-star"></i> Quản Lý Tiện Ích</h2>
                    <button class="btn btn-primary" onclick="openAddModal('featureModal')">
                        <i class="fas fa-plus"></i> Thêm Tiện Ích
                    </button>
                </div>
                <div class="tours-grid" id="featureGrid">
                    <?php while ($feature = mysqli_fetch_assoc($features_result)): ?>
                    <div class="tour-item" id="feature_<?php echo $feature['id_tienich']; ?>">
                        <div class="tour-header">
                            <h3><i class="<?php echo htmlspecialchars($feature['icon']); ?>"></i> <?php echo htmlspecialchars($feature['title']); ?></h3>
                            <div class="tour-actions">
                                <button class="btn btn-small btn-secondary" onclick="editFeature(<?php echo $feature['id_tienich']; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" style="display: inline;" class="deleteFeatureForm">
                                    <input type="hidden" name="action" value="delete_feature">
                                    <input type="hidden" name="id_tienich" value="<?php echo $feature['id_tienich']; ?>">
                                    <button type="submit" class="btn btn-small btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="tour-content">
                            <p><?php echo htmlspecialchars($feature['content']); ?></p>
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
                    <form id="featureForm" method="POST">
                        <input type="hidden" name="action" value="add_feature" id="featureAction">
                        <input type="hidden" name="id_tienich" id="featureId">
                        <div class="form-group">
                            <label for="featureIcon">Biểu tượng:</label>
                            <div class="icon-select-container">
                                <select id="featureIcon" name="icon" onchange="updateIcon(this)">
                                    <option value="">-- Chọn biểu tượng --</option>
                                    <?php foreach ($icons as $icon): ?>
                                        <option value="<?php echo htmlspecialchars($icon); ?>">
                                            <?php echo htmlspecialchars($icon); ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="custom">Tùy chỉnh...</option>
                                </select>
                                <input type="text" id="customIconInput" name="custom_icon" placeholder="Nhập lớp biểu tượng tùy chỉnh (VD: bi bi-car-front)" style="display: none;">
                                <i id="iconPreview" class=""></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="featureTitle_vi">Tiêu đề tiện ích (Tiếng Việt):</label>
                            <input type="text" name="title_vi" id="featureTitle_vi" required>
                        </div>
                        <div class="form-group">
                            <label for="featureContent_vi">Nội dung tiện ích (Tiếng Việt):</label>
                            <textarea name="content_vi" id="featureContent_vi" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="featureTitle_en">Tiêu đề tiện ích (Tiếng Anh):</label>
                            <input type="text" name="title_en" id="featureTitle_en">
                        </div>
                        <div class="form-group">
                            <label for="featureContent_en">Nội dung tiện ích (Tiếng Anh):</label>
                            <textarea name="content_en" id="featureContent_en" rows="4"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('featureModal')">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quản lý Câu Hỏi Thường Gặp -->
            <section class="admin-section">
                <div class="section-header">
                    <h2><i class="fas fa-question-circle"></i> Quản Lý Câu Hỏi Thường Gặp</h2>
                    <button class="btn btn-primary" onclick="openAddModal('faqModal')">
                        <i class="fas fa-plus"></i> Thêm Câu Hỏi
                    </button>
                </div>
                <div class="tours-grid" id="faqGrid">
                    <?php while ($faq = mysqli_fetch_assoc($faqs_result)): ?>
                    <div class="tour-item">
                        <div class="tour-header">
                            <h3><?php echo htmlspecialchars($faq['question']); ?></h3>
                            <div class="tour-actions">
                                <button class="btn btn-small btn-secondary" onclick="editFaq(<?php echo htmlspecialchars(json_encode($faq)); ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" style="display: inline;" class="deleteFaqForm">
                                    <input type="hidden" name="action" value="delete_faq">
                                    <input type="hidden" name="id_cauhoithuonggap" value="<?php echo $faq['id_cauhoithuonggap']; ?>">
                                    <input type="hidden" name="id_ngonngu" value="1">
                                    <button type="submit" class="btn btn-small btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="tour-content">
                            <p><?php echo htmlspecialchars($faq['answer']); ?></p>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </section>

            <!-- Modal Thêm/Sửa FAQ -->
            <div id="faqModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="faqModalTitle">Thêm Câu Hỏi Mới</h3>
                        <span class="close" onclick="closeModal('faqModal')">×</span>
                    </div>
                    <form id="faqForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add_faq" id="faqAction">
                        <input type="hidden" name="id_cauhoithuonggap" id="faqId">
                        <div class="form-group">
                            <label for="faq_question_vi">Câu hỏi (Tiếng Việt):</label>
                            <input type="text" name="question_vi" id="faqQuestion_vi" required>
                        </div>
                        <div class="form-group">
                            <label for="faq_answer_vi">Câu trả lời (Tiếng Việt):</label>
                            <textarea name="answer_vi" id="faqAnswer_vi" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="faq_question_en">Câu hỏi (Tiếng Anh):</label>
                            <input type="text" name="question_en" id="faqQuestion_en">
                        </div>
                        <div class="form-group">
                            <label for="faq_answer_en">Câu trả lời (Tiếng Anh):</label>
                            <textarea name="answer_en" id="faqAnswer_en" rows="4"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('faqModal')">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quản lý Xe Đưa Đón -->
            <section class="admin-section">
                <div class="section-header">
                    <h2><i class="fas fa-car"></i> Quản Lý Xe Đưa Đón</h2>
                    <button class="btn btn-primary" onclick="openAddModal('vehicleModal')">
                        <i class="fas fa-plus"></i> Thêm Xe
                    </button>
                </div>
                <div class="tours-grid" id="vehicleGrid">
                    <?php while ($vehicle = mysqli_fetch_assoc($vehicles_result)): ?>
                    <div class="tour-item">
                        <div class="tour-header">
                            <h3><?php echo htmlspecialchars($vehicle['name']); ?></h3>
                            <div class="tour-actions">
                                <button class="btn btn-small btn-secondary" onclick="editVehicle(<?php echo htmlspecialchars(json_encode($vehicle)); ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" style="display: inline;" class="deleteVehicleForm">
                                    <input type="hidden" name="action" value="delete_vehicle">
                                    <input type="hidden" name="id" value="<?php echo $vehicle['id']; ?>">
                                    <button type="submit" class="btn btn-small btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="tour-content">
                            <p>Giá: <?php echo htmlspecialchars($vehicle['price']); ?></p>
                            <p>Số ghế: <?php echo $vehicle['number_seat']; ?></p>
                            <?php if ($vehicle['image_car']): ?>
                                <img src="/libertylaocai/view/img/<?php echo htmlspecialchars($vehicle['image_car']); ?>" alt="<?php echo htmlspecialchars($vehicle['name']); ?>" class="tour-image-preview">
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </section>

            <!-- Modal Thêm/Sửa Xe -->
            <div id="vehicleModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="vehicleModalTitle">Thêm Xe Mới</h3>
                        <span class="close" onclick="closeModal('vehicleModal')">×</span>
                    </div>
                    <form id="vehicleForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add_vehicle" id="vehicleAction">
                        <input type="hidden" name="id" id="vehicleId">
                        <input type="hidden" name="id_dichvu" value="1">
                        <div class="form-group">
                            <label for="vehicle_name_vi">Tên xe (Tiếng Việt):</label>
                            <input type="text" name="name_vi" id="vehicleName_vi" required>
                        </div>
                        <div class="form-group">
                            <label for="vehicle_name_en">Tên xe (Tiếng Anh):</label>
                            <input type="text" name="name_en" id="vehicleName_en">
                        </div>
                        <div class="form-group">
                            <label for="vehicle_price">Giá:</label>
                            <input type="text" name="price" id="vehiclePrice" required>
                        </div>
                        <div class="form-group">
                            <label for="vehicle_seats">Số ghế:</label>
                            <input type="number" name="number_seat" id="vehicleSeats" required>
                        </div>
                        <div class="form-group">
                            <label for="vehicle_image">Hình ảnh xe:</label>
                            <input type="file" name="vehicle_image" id="vehicleImage" accept="image/*">
                            <div id="currentVehicleImage"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('vehicleModal')">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="/libertylaocai/view/js/quanlyduadonsanbay.js"></script>
</body>
</html>