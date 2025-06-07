<?php
// Kết nối cơ sở dữ liệu
require_once '../../model/config/connect.php';

// Lấy dữ liệu hiển thị
$languages_query = "SELECT * FROM ngonngu";
$languages_result = mysqli_query($conn, $languages_query);
// Lấy danh sách lời chào
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

// Lấy lời chào hoạt động
$active_greeting_query = "SELECT n.id as id_nhungcauchaohoi, 
                         MAX(CASE WHEN nn.id_ngonngu = 1 THEN nn.content END) as content_vi,
                         MAX(CASE WHEN nn.id_ngonngu = 2 THEN nn.content END) as content_en
                         FROM loichaoduocchon l 
                         JOIN nhungcauchaohoi_ngonngu nn ON l.id_nhungcauchaohoi_ngonngu = nn.id 
                         JOIN nhungcauchaohoi n ON nn.id_nhungcauchaohoi = n.id
                         WHERE l.page = 'giaythonghanh' AND l.area = 'passport-service-greeting'
                         GROUP BY n.id";
$active_greeting_result = mysqli_query($conn, $active_greeting_query);
$active_greeting = mysqli_fetch_assoc($active_greeting_result);
// Lấy danh sách mô tả
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

// Lấy mô tả hoạt động
$active_description_query = "SELECT m.id as id_mota, 
                           MAX(CASE WHEN mn.id_ngonngu = 1 THEN mn.title END) as title_vi,
                           MAX(CASE WHEN mn.id_ngonngu = 1 THEN mn.content END) as content_vi,
                           MAX(CASE WHEN mn.id_ngonngu = 2 THEN mn.title END) as title_en,
                           MAX(CASE WHEN mn.id_ngonngu = 2 THEN mn.content END) as content_en
                           FROM chon_mo_ta cm 
                           JOIN mota_ngonngu mn ON cm.id_mota_ngonngu = mn.id 
                           JOIN mota m ON mn.id_mota = m.id
                           WHERE cm.area = 'passport-service-description'
                           GROUP BY m.id";
$active_description_result = mysqli_query($conn, $active_description_query); // Sửa dòng này
$active_description = mysqli_fetch_assoc($active_description_result);

// Lấy giá và thông tin dịch vụ
$service_query = "SELECT d.id, d.price, dn.title, dn.content 
                 FROM dichvu d 
                 LEFT JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu AND dn.id_ngonngu = 1 
                 WHERE d.id = 1";
$service_result = mysqli_query($conn, $service_query);
$service = mysqli_fetch_assoc($service_result);

// Lấy thông tin dịch vụ tiếng Anh
$service_en_query = "SELECT title, content 
                    FROM dichvu_ngonngu 
                    WHERE id_dichvu = 1 AND id_ngonngu = 2";
$service_en_result = mysqli_query($conn, $service_en_query);
$service_en = mysqli_fetch_assoc($service_en_result);

// Lấy danh sách icon từ bảng `tienich`
$icons_query = "SELECT DISTINCT icon FROM tienich WHERE icon IS NOT NULL AND icon != ''";
$icons_result = mysqli_query($conn, $icons_query);
$icons = [];
while ($row = mysqli_fetch_assoc($icons_result)) {
    $icons[] = $row['icon'];
}

// Thêm các icon Bootstrap mẫu
$bootstrap_icons = [
    'bi bi-passport',
    'bi bi-file-text',
    'bi bi-clock',
    'bi bi-check-circle',
    'bi bi-info-circle'
];

// Kết hợp danh sách icon
$all_icons = array_unique(array_merge($icons, $bootstrap_icons));

// Lấy danh sách tiện ích
$features_query = "SELECT t.id as id_tienich, t.icon, tn.title, tn.content, td.page 
                  FROM tienich t 
                  LEFT JOIN tienich_ngonngu tn ON t.id = tn.id_tienich 
                  LEFT JOIN tienichdichvu td ON t.id = td.id_tienich 
                  WHERE tn.id_ngonngu = 1 AND td.page = 'giaythonghanh'";
$features_result = mysqli_query($conn, $features_query);

// Xử lý các action
// Hàm trả về phản hồi JSON
function sendJsonResponse($success, $message, $data = []) {
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit();
}

// Xử lý các action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'add_greeting':
    $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
    $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');

    $stmt = $conn->prepare("INSERT INTO nhungcauchaohoi () VALUES ()");
    if ($stmt->execute()) {
        $id_nhungcauchaohoi = $conn->insert_id;

        if ($content_vi) {
            $stmt = $conn->prepare("INSERT INTO nhungcauchaohoi_ngonngu (id_nhungcauchaohoi, id_ngonngu, content) VALUES (?, 1, ?)");
            $stmt->bind_param("is", $id_nhungcauchaohoi, $content_vi);
            if (!$stmt->execute()) {
                sendJsonResponse(false, "Lỗi khi thêm lời chào tiếng Việt: " . $conn->error);
            }
        }

        if ($content_en) {
            $stmt = $conn->prepare("INSERT INTO nhungcauchaohoi_ngonngu (id_nhungcauchaohoi, id_ngonngu, content) VALUES (?, 2, ?)");
            $stmt->bind_param("is", $id_nhungcauchaohoi, $content_en);
            if (!$stmt->execute()) {
                sendJsonResponse(false, "Lỗi khi thêm lời chào tiếng Anh: " . $conn->error);
            }
        }

        if ($content_vi) {
            $new_greeting = [
                'id_nhungcauchaohoi' => $id_nhungcauchaohoi,
                'content_vi' => $content_vi,
                'content_en' => $content_en
            ];
            sendJsonResponse(true, "Thêm lời chào thành công!", $new_greeting);
        } else {
            sendJsonResponse(false, "Vui lòng nhập ít nhất nội dung tiếng Việt!");
        }
    } else {
        sendJsonResponse(false, "Lỗi khi tạo bản ghi lời chào: " . $conn->error);
    }
    $stmt->close();
    break;

        case 'update_greeting':
            $id_nhungcauchaohoi = (int)$_POST['id_nhungcauchaohoi'];
            $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
            $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');

            if ($id_nhungcauchaohoi <= 0) {
                sendJsonResponse(false, "ID lời chào không hợp lệ!");
            }

            $check_query = "SELECT COUNT(*) as count FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 1";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("i", $id_nhungcauchaohoi);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                $stmt = $conn->prepare("UPDATE nhungcauchaohoi_ngonngu SET content = ? WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 1");
                $stmt->bind_param("si", $content_vi, $id_nhungcauchaohoi);
                if (!$stmt->execute()) {
                    sendJsonResponse(false, "Lỗi khi cập nhật lời chào tiếng Việt: " . $conn->error);
                }
            } else if ($content_vi) {
                $stmt = $conn->prepare("INSERT INTO nhungcauchaohoi_ngonngu (id_nhungcauchaohoi, id_ngonngu, content) VALUES (?, 1, ?)");
                $stmt->bind_param("is", $id_nhungcauchaohoi, $content_vi);
                if (!$stmt->execute()) {
                    sendJsonResponse(false, "Lỗi khi thêm lời chào tiếng Việt: " . $conn->error);
                }
            }

            $check_query = "SELECT COUNT(*) as count FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 2";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("i", $id_nhungcauchaohoi);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                if ($content_en) {
                    $stmt = $conn->prepare("UPDATE nhungcauchaohoi_ngonngu SET content = ? WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 2");
                    $stmt->bind_param("si", $content_en, $id_nhungcauchaohoi);
                    if (!$stmt->execute()) {
                        sendJsonResponse(false, "Lỗi khi cập nhật lời chào tiếng Anh: " . $conn->error);
                    }
                } else {
                    $stmt = $conn->prepare("DELETE FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 2");
                    $stmt->bind_param("i", $id_nhungcauchaohoi);
                    $stmt->execute();
                }
            } else if ($content_en) {
                $stmt = $conn->prepare("INSERT INTO nhungcauchaohoi_ngonngu (id_nhungcauchaohoi, id_ngonngu, content) VALUES (?, 2, ?)");
                $stmt->bind_param("is", $id_nhungcauchaohoi, $content_en);
                if (!$stmt->execute()) {
                    sendJsonResponse(false, "Lỗi khi thêm lời chào tiếng Anh: " . $conn->error);
                }
            }

            sendJsonResponse(true, "Cập nhật lời chào thành công!");
            $stmt->close();
            break;

        case 'delete_greeting':
            $id_nhungcauchaohoi = (int)$_POST['id_nhungcauchaohoi'];

            // Check if the greeting is used by pages other than 'giaythonghanh', ensuring unique pages
            $check_usage_query = "SELECT DISTINCT page FROM loichaoduocchon lc 
                                JOIN nhungcauchaohoi_ngonngu nn ON lc.id_nhungcauchaohoi_ngonngu = nn.id 
                                WHERE nn.id_nhungcauchaohoi = ? AND lc.page != 'giaythonghanh'";
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
                sendJsonResponse(false, "Lời chào đang được sử dụng bởi trang: " . implode(", ", $used_pages) . ". Không thể xóa!", 400);
            }

            // If no other pages use it, proceed with deletion, including entries in loichaoduocchon for giaythonghanh
            $stmt = $conn->prepare("DELETE FROM loichaoduocchon WHERE id_nhungcauchaohoi_ngonngu IN (SELECT id FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ?)");
            $stmt->bind_param("i", $id_nhungcauchaohoi);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ?");
            $stmt->bind_param("i", $id_nhungcauchaohoi);
            if ($stmt->execute()) {
                $stmt = $conn->prepare("DELETE FROM nhungcauchaohoi WHERE id = ?");
                $stmt->bind_param("i", $id_nhungcauchaohoi);
                if ($stmt->execute()) {
                    sendJsonResponse(true, "Xóa lời chào thành công!");
                } else {
                    sendJsonResponse(false, "Lỗi khi xóa lời chào: " . $conn->error, 500);
                }
            } else {
                sendJsonResponse(false, "Lỗi khi xóa nội dung lời chào: " . $conn->error, 500);
            }
            $stmt->close();
            break;

        case 'update_active_greeting':
            $id_nhungcauchaohoi = (int)$_POST['id_nhungcauchaohoi'];
            $area = 'passport-service-greeting';

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

                $stmt = $conn->prepare("DELETE FROM loichaoduocchon WHERE area = ? AND page = 'giaythonghanh'");
                $stmt->bind_param("s", $area);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO loichaoduocchon (id_nhungcauchaohoi_ngonngu, id_ngonngu, page, area) VALUES (?, 1, 'giaythonghanh', ?)");
                $stmt->bind_param("is", $id_nhungcauchaohoi_ngonngu_vi, $area);
                $stmt->execute();

                $check_en_query = "SELECT id FROM nhungcauchaohoi_ngonngu WHERE id_nhungcauchaohoi = ? AND id_ngonngu = 2";
                $stmt = $conn->prepare($check_en_query);
                $stmt->bind_param("i", $id_nhungcauchaohoi);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($en_row = $result->fetch_assoc()) {
                    $id_nhungcauchaohoi_ngonngu_en = $en_row['id'];
                    $stmt = $conn->prepare("INSERT INTO loichaoduocchon (id_nhungcauchaohoi_ngonngu, id_ngonngu, page, area) VALUES (?, 2, 'giaythonghanh', ?)");
                    $stmt->bind_param("is", $id_nhungcauchaohoi_ngonngu_en, $area);
                    $stmt->execute();
                }

                sendJsonResponse(true, "Cập nhật lời chào hoạt động thành công!");
            } else {
                sendJsonResponse(false, "Lời chào không tồn tại!");
            }
            $stmt->close();
            break;

        case 'update_service':
            $price = mysqli_real_escape_string($conn, $_POST['price']);
            $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi']);
            $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi'] ?? '');
            $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
            $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');
            $id_dichvu = 1;

            $stmt = $conn->prepare("UPDATE dichvu SET price = ? WHERE id = ?");
            $stmt->bind_param("si", $price, $id_dichvu);
            $stmt->execute();

            $check_query = "SELECT COUNT(*) as count FROM dichvu_ngonngu WHERE id_dichvu = ? AND id_ngonngu = 1";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("i", $id_dichvu);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                $stmt = $conn->prepare("UPDATE dichvu_ngonngu SET title = ?, content = ? WHERE id_dichvu = ? AND id_ngonngu = 1");
                $stmt->bind_param("ssi", $title_vi, $content_vi, $id_dichvu);
            } else {
                $stmt = $conn->prepare("INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title, content) VALUES (?, 1, ?, ?)");
                $stmt->bind_param("iss", $id_dichvu, $title_vi, $content_vi);
            }
            $stmt->execute();

            if ($title_en || $content_en) {
                $check_query = "SELECT COUNT(*) as count FROM dichvu_ngonngu WHERE id_dichvu = ? AND id_ngonngu = 2";
                $stmt = $conn->prepare($check_query);
                $stmt->bind_param("i", $id_dichvu);
                $stmt->execute();
                $check_row = $stmt->get_result()->fetch_assoc();

                if ($check_row['count'] > 0) {
                    $stmt = $conn->prepare("UPDATE dichvu_ngonngu SET title = ?, content = ? WHERE id_dichvu = ? AND id_ngonngu = 2");
                    $stmt->bind_param("ssi", $title_en, $content_en, $id_dichvu);
                } else {
                    $stmt = $conn->prepare("INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title, content) VALUES (?, 2, ?, ?)");
                    $stmt->bind_param("iss", $id_dichvu, $title_en, $content_en);
                }
                $stmt->execute();
            }

            sendJsonResponse(true, "Cập nhật dịch vụ thành công!");
            $stmt->close();
            break;
        case 'add_description':
            $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi'] ?? '');
            $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
            $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
            $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');

            $stmt = $conn->prepare("INSERT INTO mota () VALUES ()");
            if ($stmt->execute()) {
                $id_mota = $conn->insert_id;

                // Thêm tiếng Việt
                if ($content_vi) {
                    $stmt = $conn->prepare("INSERT INTO mota_ngonngu (id_mota, id_ngonngu, title, content) VALUES (?, 1, ?, ?)");
                    $stmt->bind_param("iss", $id_mota, $title_vi, $content_vi);
                    if (!$stmt->execute()) {
                        sendJsonResponse(false, "Lỗi khi thêm mô tả tiếng Việt: " . $conn->error);
                    }
                }

                // Thêm tiếng Anh
                if ($content_en || $title_en) {
                    $stmt = $conn->prepare("INSERT INTO mota_ngonngu (id_mota, id_ngonngu, title, content) VALUES (?, 2, ?, ?)");
                    $stmt->bind_param("iss", $id_mota, $title_en, $content_en);
                    if (!$stmt->execute()) {
                        sendJsonResponse(false, "Lỗi khi thêm mô tả tiếng Anh: " . $conn->error);
                    }
                }

                if ($content_vi) {
                    // Trả về dữ liệu đầy đủ cho mô tả mới
                    $new_description = [
                        'id_mota' => $id_mota,
                        'title_vi' => $title_vi,
                        'content_vi' => $content_vi,
                        'title_en' => $title_en,
                        'content_en' => $content_en
                    ];
                    sendJsonResponse(true, "Thêm mô tả thành công!", $new_description);
                } else {
                    sendJsonResponse(false, "Vui lòng nhập ít nhất nội dung tiếng Việt!");
                }
            } else {
                sendJsonResponse(false, "Lỗi khi tạo bản ghi mô tả: " . $conn->error);
            }
            $stmt->close();
            break;

        case 'update_description':
            $id_mota = (int)$_POST['post_id'];
            $title_vi = mysqli_real_escape_string($conn, $_POST['title_vi'] ?? '');
            $content_vi = mysqli_real_escape_string($conn, $_POST['content_vi']);
            $title_en = mysqli_real_escape_string($conn, $_POST['title_en'] ?? '');
            $content_en = mysqli_real_escape_string($conn, $_POST['content_en'] ?? '');

            if ($id_mota <= 0) {
                sendJsonResponse(false, "ID mô tả không hợp lệ!");
            }

            // Cập nhật tiếng Việt
            $check_query = "SELECT COUNT(*) as count FROM mota_ngonngu WHERE id_mota = ? AND id_ngonngu = 1";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("i", $id_mota);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                $query = "UPDATE mota_ngonngu SET title = ?, content = ? WHERE id_mota = ? AND id_ngonngu = 1";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssi", $title_vi, $content_vi, $id_mota);
                if (!$stmt->execute()) {
                    sendJsonResponse(false, "Lỗi khi cập nhật mô tả tiếng Việt: " . $conn->error);
                }
            } else if ($content_vi || $title_vi) {
                $insert_query = "INSERT INTO mota_ngonngu (id_mota, id_ngonngu, title, content) VALUES (?, 1, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("iss", $id_mota, $title_vi, $content_vi);
                if (!$stmt->execute()) {
                    sendJsonResponse(false, "Lỗi khi thêm mô tả tiếng Việt: " . $conn->error);
                }
            }

            // Cập nhật tiếng Anh
            $check_query = "SELECT COUNT(*) as count FROM mota_ngonngu WHERE id_mota = ? AND id_ngonngu = 2";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("i", $id_mota);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                if ($content_en || $title_en) {
                    $query = "UPDATE mota_ngonngu SET title = ?, content = ? WHERE id_mota = ? AND id_ngonngu = 2";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ssi", $title_en, $content_en, $id_mota);
                    if (!$stmt->execute()) {
                        sendJsonResponse(false, "Lỗi khi cập nhật mô tả tiếng Anh: " . $conn->error);
                    }
                } else {
                    $stmt = $conn->prepare("DELETE FROM mota_ngonngu WHERE id_mota = ? AND id_ngonngu = 2");
                    $stmt->bind_param("i", $id_mota);
                    $stmt->execute();
                }
            } else if ($content_en || $title_en) {
                $insert_query = "INSERT INTO mota_ngonngu (id_mota, id_ngonngu, title, content) VALUES (?, 2, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("iss", $id_mota, $title_en, $content_en);
                if (!$stmt->execute()) {
                    sendJsonResponse(false, "Lỗi khi thêm mô tả tiếng Anh: " . $conn->error);
                }
            }

            sendJsonResponse(true, "Cập nhật mô tả thành công!");
            $stmt->close();
            break;

            // Cập nhật tiếng Anh
            $check_query = "SELECT COUNT(*) as count FROM mota_ngonngu WHERE id_mota = ? AND id_ngonngu = 2";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("i", $id_mota);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();

            if ($check_row['count'] > 0) {
                if ($content_en) {
                    $query = "UPDATE mota_ngonngu SET content = ? WHERE id_mota = ? AND id_ngonngu = 2";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("si", $content_en, $id_mota);
                    if (!$stmt->execute()) {
                        sendJsonResponse(false, "Lỗi khi cập nhật mô tả tiếng Anh: " . $conn->error);
                    }
                } else {
                    $stmt = $conn->prepare("DELETE FROM mota_ngonngu WHERE id_mota = ? AND id_ngonngu = 2");
                    $stmt->bind_param("i", $id_mota);
                    $stmt->execute();
                }
            } else if ($content_en) {
                $insert_query = "INSERT INTO mota_ngonngu (id_mota, id_ngonngu, content) VALUES (?, 2, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("is", $id_mota, $content_en);
                if (!$stmt->execute()) {
                    sendJsonResponse(false, "Lỗi khi thêm mô tả tiếng Anh: " . $conn->error);
                }
            }

            sendJsonResponse(true, "Cập nhật mô tả thành công!");
            $stmt->close();
            break;

        case 'delete_description':
            $id_mota = (int)$_POST['id_mota'];

            $stmt = $conn->prepare("DELETE FROM chon_mo_ta WHERE id_mota_ngonngu IN (SELECT id FROM mota_ngonngu WHERE id_mota = ?)");
            $stmt->bind_param("i", $id_mota);
            if ($stmt->execute()) {
                $stmt = $conn->prepare("DELETE FROM mota_ngonngu WHERE id_mota = ?");
                $stmt->bind_param("i", $id_mota);
                if ($stmt->execute()) {
                    $stmt = $conn->prepare("DELETE FROM mota WHERE id = ?");
                    $stmt->bind_param("i", $id_mota);
                    if ($stmt->execute()) {
                        sendJsonResponse(true, "Xóa mô tả thành công!");
                    } else {
                        sendJsonResponse(false, "Lỗi khi xóa mô tả: " . $conn->error);
                    }
                } else {
                    sendJsonResponse(false, "Lỗi khi xóa nội dung mô tả: " . $conn->error);
                }
            } else {
                sendJsonResponse(false, "Lỗi khi xóa liên kết mô tả: " . $conn->error);
            }
            $stmt->close();
            break;

        case 'update_active_description':
            $id_mota = (int)$_POST['id_mota'];
            $area = 'passport-service-description';

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

                // Thêm mô tả tiếng Việt
                $stmt = $conn->prepare("INSERT INTO chon_mo_ta (area, id_mota_ngonngu, language_id) VALUES (?, ?, 1)");
                $stmt->bind_param("si", $area, $id_mota_ngonngu_vi);
                $stmt->execute();

                // Kiểm tra và thêm mô tả tiếng Anh nếu có
                $check_en_query = "SELECT id FROM mota_ngonngu WHERE id_mota = ? AND id_ngonngu = 2";
                $stmt = $conn->prepare($check_en_query);
                $stmt->bind_param("i", $id_mota);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($en_row = $result->fetch_assoc()) {
                    $id_mota_ngonngu_en = $en_row['id'];
                    $stmt = $conn->prepare("INSERT INTO chon_mo_ta (area, id_mota_ngonngu, language_id) VALUES (?, ?, 2)");
                    $stmt->bind_param("si", $area, $id_mota_ngonngu_en);
                    $stmt->execute();
                }

                sendJsonResponse(true, "Cập nhật mô tả hoạt động thành công!");
            } else {
                sendJsonResponse(false, "Mô tả không tồn tại!");
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
                sendJsonResponse(false, "Vui lòng chọn hoặc nhập biểu tượng!");
            }

            $stmt = $conn->prepare("INSERT INTO tienich (icon, active) VALUES (?, 1)");
            $stmt->bind_param("s", $icon);
            if ($stmt->execute()) {
                $id_tienich = $conn->insert_id;

                if ($content_vi) {
                    $stmt = $conn->prepare("INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 1, ?, ?)");
                    $stmt->bind_param("iss", $id_tienich, $title_vi, $content_vi);
                    $stmt->execute();
                }

                if ($title_en || $content_en) {
                    $stmt = $conn->prepare("INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 2, ?, ?)");
                    $stmt->bind_param("iss", $id_tienich, $title_en, $content_en);
                    $stmt->execute();
                }

                $stmt = $conn->prepare("INSERT INTO tienichdichvu (id_tienich, page) VALUES (?, 'giaythonghanh')");
                $stmt->bind_param("i", $id_tienich);
                if ($stmt->execute() && $content_vi) {
                    sendJsonResponse(true, "Thêm tiện ích thành công!", ['id' => $id_tienich]);
                } else {
                    sendJsonResponse(false, "Lỗi khi thêm tiện ích: " . $conn->error);
                }
            } else {
                sendJsonResponse(false, "Lỗi khi tạo tiện ích: " . $conn->error);
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
                sendJsonResponse(false, "Vui lòng chọn hoặc nhập biểu tượng!");
            }

            $stmt = $conn->prepare("UPDATE tienich SET icon = ? WHERE id = ?");
            $stmt->bind_param("si", $icon, $id_tienich);
            if ($stmt->execute()) {
                $check_query = "SELECT COUNT(*) as count FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 1";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bind_param("i", $id_tienich);
                $check_stmt->execute();
                $check_row = $check_stmt->get_result()->fetch_assoc();

                if ($check_row['count'] > 0) {
                    $query = "UPDATE tienich_ngonngu SET title = ?, content = ? WHERE id_tienich = ? AND id_ngonngu = 1";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ssi", $title_vi, $content_vi, $id_tienich);
                    if (!$stmt->execute()) {
                        sendJsonResponse(false, "Lỗi khi cập nhật nội dung tiếng Việt: " . $conn->error);
                    }
                } else if ($title_vi || $content_vi) {
                    $insert_query = "INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 1, ?, ?)";
                    $stmt = $conn->prepare($insert_query);
                    $stmt->bind_param("iss", $id_tienich, $title_vi, $content_vi);
                    if (!$stmt->execute()) {
                        sendJsonResponse(false, "Lỗi khi thêm nội dung tiếng Việt: " . $conn->error);
                    }
                }

                $check_query = "SELECT COUNT(*) as count FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 2";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bind_param("i", $id_tienich);
                $check_stmt->execute();
                $check_row = $check_stmt->get_result()->fetch_assoc();

                if ($check_row['count'] > 0) {
                    if ($title_en || $content_en) {
                        $query = "UPDATE tienich_ngonngu SET title = ?, content = ? WHERE id_tienich = ? AND id_ngonngu = 2";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("ssi", $title_en, $content_en, $id_tienich);
                        if (!$stmt->execute()) {
                            sendJsonResponse(false, "Lỗi khi cập nhật nội dung tiếng Anh: " . $conn->error);
                        }
                    } else {
                        $stmt = $conn->prepare("DELETE FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 2");
                        $stmt->bind_param("i", $id_tienich);
                        $stmt->execute();
                    }
                } else if ($title_en || $content_en) {
                    $insert_query = "INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 2, ?, ?)";
                    $stmt = $conn->prepare($insert_query);
                    $stmt->bind_param("iss", $id_tienich, $title_en, $content_en);
                    if (!$stmt->execute()) {
                        sendJsonResponse(false, "Lỗi khi thêm nội dung tiếng Anh: " . $conn->error);
                    }
                }

                sendJsonResponse(true, "Cập nhật tiện ích thành công!");
            } else {
                sendJsonResponse(false, "Lỗi khi cập nhật tiện ích: " . $conn->error);
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
                    sendJsonResponse(true, "Xóa tiện ích thành công!");
                } else {
                    sendJsonResponse(false, "Lỗi khi xóa tiện ích: " . $conn->error);
                }
            } else {
                sendJsonResponse(false, "Lỗi khi xóa liên kết tiện ích: " . $conn->error);
            }
            $stmt->close();
            break;

        default:
            sendJsonResponse(false, "Hành động không hợp lệ!");
    }
}

// Xử lý AJAX lấy dữ liệu tiếng Anh cho tiện ích (giữ nguyên)
if (isset($_GET['action']) && $_GET['action'] === 'get_english_feature' && isset($_GET['id_tienich'])) {
    header('Content-Type: application/json');
    $id_tienich = (int)$_GET['id_tienich'];

    $stmt = $conn->prepare("SELECT title, content FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 2");
    $stmt->bind_param("i", $id_tienich);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        echo json_encode(['success' => true, 'title' => $data['title'], 'content' => $data['content']]);
    } else {
        echo json_encode(['success' => false, 'title' => '', 'content' => '']);
    }
    $stmt->close();
    exit();
}
// Xử lý AJAX lấy dữ liệu tiếng Anh cho tiện ích
if (isset($_GET['action']) && $_GET['action'] === 'get_english_feature' && isset($_GET['id_tienich'])) {
    header('Content-Type: application/json');
    $id_tienich = (int)$_GET['id_tienich'];

    $stmt = $conn->prepare("SELECT title, content FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 2");
    $stmt->bind_param("i", $id_tienich);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        echo json_encode(['success' => true, 'title' => $data['title'], 'content' => $data['content']]);
    } else {
        echo json_encode(['success' => false, 'title' => '', 'content' => '']);
    }
    $stmt->close();
    exit();
}

ob_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Giấy Thông Hành - Liberty Lào Cai</title>
    <link rel="stylesheet" href="/libertylaocai/view/css/quanlygiaythonghanh.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-passport"></i> Quản Lý Dịch Vụ Giấy Thông Hành</h1>
            <div class="admin-nav">
                <a href="giaythonghanh.php" target="_blank" class="btn btn-preview">
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
                            <select id="activeGreetingSelect" name="greeting_data" onchange="updateActiveGreetingInputs(this)">
                                <option value="">-- Chọn lời chào --</option>
                                <?php foreach ($greetings as $greeting): ?>
                                    <option value="<?php echo htmlspecialchars(json_encode($greeting)); ?>" 
                                        <?php echo ($active_greeting && $greeting['id_nhungcauchaohoi'] == $active_greeting['id_nhungcauchaohoi']) ? 'selected' : ''; ?>">
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
                        <input type="hidden" name="id_nhungcauchaohoi" id="greetingId">
                        <div class="form-group">
                            <label for="greetingContent_vi">Nội dung lời chào (Tiếng Việt):</label>
                            <textarea name="content_vi" id="greetingContent_vi" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="greetingContent_en">Nội dung lời chào (Tiếng Anh):</label>
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
                    <form id="addGreetingForm" method="POST">
                        <input type="hidden" name="action" value="add_greeting">
                        <div class="form-group">
                            <label for="content_vi">Nội dung lời chào (Tiếng Việt):</label>
                            <textarea name="content_vi" id="content_vi" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="content_en">Nội dung lời chào (Tiếng Anh):</label>
                            <textarea name="content_en" id="content_en" rows="3"></textarea>
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
                    <button class="btn btn-primary" onclick="openModal('descriptionModal')">
                        <i class="fas fa-plus"></i> Thêm Mô Tả
                    </button>
                </div>

                <div class="form-container">
                    <div class="form-group">
                        <label>Mô tả hiện tại:</label>
                        <p>
                            <?php 
                            if ($active_description) {
                                if ($active_description['title_vi']) {
                                    echo '<strong>Tiêu đề (Tiếng Việt):</strong> ' . htmlspecialchars($active_description['title_vi']) . '<br>';
                                }
                                echo '<strong>Nội dung (Tiếng Việt):</strong> ' . htmlspecialchars($active_description['content_vi']) . '<br>';
                                if (!empty($active_description['title_en']) || !empty($active_description['content_en'])) {
                                    if ($active_description['title_en']) {
                                        echo '<strong>Tiêu đề (Tiếng Anh):</strong> ' . htmlspecialchars($active_description['title_en']) . '<br>';
                                    }
                                    echo '<strong>Nội dung (Tiếng Anh):</strong> ' . htmlspecialchars($active_description['content_en']);
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
                                        <?php echo htmlspecialchars($description['title_vi'] ? $description['title_vi'] : substr($description['content_vi'], 0, 60)); ?>
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
                                        <?php echo htmlspecialchars($description['title_vi'] ? $description['title_vi'] : substr($description['content_vi'], 0, 60)); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <form id="deleteDescriptionForm" method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa mô tả này?')">
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
                        <input type="hidden" name="post_id" id="descriptionId">
                        <div class="form-group">
                            <label for="descriptionTitle_vi">Tiêu đề mô tả (Tiếng Việt):</label>
                            <input type="text" name="title_vi" id="descriptionTitle_vi">
                        </div>
                        <div class="form-group">
                            <label for="descriptionContent_vi">Nội dung mô tả (Tiếng Việt):</label>
                            <textarea name="content_vi" id="descriptionContent_vi" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="descriptionTitle_en">Tiêu đề mô tả (Tiếng Anh):</label>
                            <input type="text" name="title_en" id="descriptionTitle_en">
                        </div>
                        <div class="form-group">
                            <label for="descriptionContent_en">Nội dung mô tả (Tiếng Anh):</label>
                            <textarea name="content_en" id="descriptionContent_en" rows="3"></textarea>
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
                            <label for="title_vi">Tiêu đề mô tả (Tiếng Việt):</label>
                            <input type="text" name="title_vi" id="title_vi">
                        </div>
                        <div class="form-group">
                            <label for="content_vi">Nội dung mô tả (Tiếng Việt):</label>
                            <textarea name="content_vi" id="content_vi" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="title_en">Tiêu đề mô tả (Tiếng Anh):</label>
                            <input type="text" name="title_en" id="title_en">
                        </div>
                        <div class="form-group">
                            <label for="content_en">Nội dung mô tả (Tiếng Anh):</label>
                            <textarea name="content_en" id="content_en" rows="3"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('descriptionModal')">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        
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
                            <label for="featureTitle">Tiêu đề tiện ích (Tiếng Việt):</label>
                            <input type="text" name="title_vi" id="featureTitle" required>
                        </div>
                        <div class="form-group">
                            <label for="featureContent">Nội dung tiện ích (Tiếng Việt):</label>
                            <textarea name="content_vi" id="featureContent" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="featureTitleEn">Tiêu đề tiện ích (Tiếng Anh):</label>
                            <input type="text" name="title_en" id="featureTitleEn">
                        </div>
                        <div class="form-group">
                            <label for="featureContentEn">Nội dung tiện ích (Tiếng Anh):</label>
                            <textarea name="content_en" id="featureContentEn" rows="4"></textarea>
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('featureModal')">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quản lý Dịch Vụ -->
            <section class="admin-section">
                <div class="section-header">
                    <h2><i class="fas fa-money-bill"></i> Quản Lý Dịch Vụ</h2>
                </div>
                <div class="form-container">
                    <form id="servicePriceForm" method="POST" class="admin-form">
                        <input type="hidden" name="action" value="update_service">
                        <div class="form-group">
                            <label for="servicePrice">Giá dịch vụ (VNĐ):</label>
                            <input type="text" name="price" id="servicePrice" value="<?php echo htmlspecialchars($service['price']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="serviceTitle">Tiêu đề dịch vụ (Tiếng Việt):</label>
                            <input type="text" name="title_vi" id="serviceTitle" value="<?php echo htmlspecialchars($service['title']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="serviceContent">Nội dung dịch vụ (Tiếng Việt):</label>
                            <textarea name="content_vi" id="serviceContent" rows="4"><?php echo htmlspecialchars($service['content']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="serviceTitleEn">Tiêu đề dịch vụ (Tiếng Anh):</label>
                            <input type="text" name="title_en" id="serviceTitleEn" value="<?php echo htmlspecialchars($service_en['title'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="serviceContentEn">Nội dung dịch vụ (Tiếng Anh):</label>
                            <textarea name="content_en" id="serviceContentEn" rows="4"><?php echo htmlspecialchars($service_en['content'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập Nhật Dịch Vụ
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script src="/libertylaocai/view/js/quanlygiaythonghanh.js"></script>
</body>
</html>
<?php
$current_tab = 'passport-management';
$tab_content = ob_get_clean();
include 'tabdichvu.php'; // Điều chỉnh đường dẫn nếu cần
?>