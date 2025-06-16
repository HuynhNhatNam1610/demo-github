<?php
require_once "config/connect.php";

function getHotelInfoWithLanguage($languageId = null)
{
    global $conn;
    $hotels = [];

    $sql = "SELECT 
                t.id,
                t.name,
                t.short_name,
                t.phone,
                t.email,
                t.facebook,
                t.link_facebook,
                t.logo,
                t.position,
                t.website,
                t.link_website,
                t.iframe,
                t.iframe_ytb,
                tn.id_ngonngu,
                tn.address,
                tn.description
            FROM thongtinkhachsan t
            LEFT JOIN thongtinkhachsan_ngonngu tn ON t.id = tn.id_thongtinkhachsan";

    if ($languageId !== null) {
        $sql .= " WHERE tn.id_ngonngu = ?";
    }

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        if ($languageId !== null) {
            mysqli_stmt_bind_param($stmt, "i", $languageId);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $hotels[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'short_name' => $row['short_name'],
                'phone' => $row['phone'],
                'email' => $row['email'],
                'facebook' => $row['facebook'],
                'link_facebook' => $row['link_facebook'],
                'logo' => $row['logo'],
                'position' => $row['position'],
                'website' => $row['website'],
                'link_website' => $row['link_website'],
                'iframe' => $row['iframe'],
                'iframe_ytb' => $row['iframe_ytb'],
                'id_ngonngu' => $row['id_ngonngu'],
                'address' => $row['address'],
                'description' => $row['description']
            ];
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Lỗi chuẩn bị truy vấn getHotelInfoWithLanguage: " . mysqli_error($conn));
    }

    return $hotels;
}

function getHeaderCategories($languageId = null)
{
    global $conn;
    $categories = [];

    $sql = "SELECT 
                d.id,
                d.code,
                d.position,
                d.active,
                dn.id_ngonngu,
                dn.name
            FROM danhmucheader d
            LEFT JOIN danhmucheader_ngonngu dn ON d.id = dn.id_danhmucheader
            WHERE d.active = 1";

    if ($languageId !== null) {
        $sql .= " AND dn.id_ngonngu = ?";
    }

    $sql .= " ORDER BY d.position ASC";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {

        if ($languageId !== null) {
            mysqli_stmt_bind_param($stmt, "i", $languageId);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = [
                'id' => $row['id'],
                'code' => $row['code'],
                'position' => $row['position'],
                'active' => $row['active'],
                'id_ngonngu' => $row['id_ngonngu'],
                'name' => $row['name']
            ];
        }

        mysqli_stmt_close($stmt);
    } else {
        error_log("Lỗi chuẩn bị truy vấn getHeaderCategories: " . mysqli_error($conn));
    }

    return $categories;
}

function getGreetingByLanguage($languageId = null, $page = null)
{
    global $conn;
    $greetings = null;

    $sql = "SELECT 
                chnn.id,
                -- nccn.id_nhungcauchaohoi,
                -- nccn.id_ngonngu,
                chnn.content
            FROM nhungcauchaohoi_ngonngu chnn
            JOIN loichaoduocchon lcdc ON chnn.id = lcdc.id_nhungcauchaohoi_ngonngu
            WHERE lcdc.page = ? AND lcdc.id_ngonngu = ?
            ORDER BY chnn.id ASC";

    // if ($languageId !== null) {
    //     $sql .= " AND nccn.id_ngonngu = ?";
    // }

    // $sql .= " ORDER BY nccn.id ASC";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {

        if ($languageId !== null) {
            mysqli_stmt_bind_param($stmt, "si", $page, $languageId);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $greetings[] = [
                'id' => $row['id'],
                // 'id_nhungcauchaohoi' => $row['id_nhungcauchaohoi'],
                // 'id_ngonngu' => $row['id_ngonngu'],
                'content' => $row['content']
            ];
        }

        mysqli_stmt_close($stmt);
    } else {
        error_log("Lỗi chuẩn bị truy vấn getGreetingByLanguage: " . mysqli_error($conn));
    }

    return $greetings;
}

function getGreeting($language_id, $page = 'dichvu')
{
    global $conn;
    $sql = "
        SELECT nn.content
        FROM loichaoduocchon l
        JOIN nhungcauchaohoi_ngonngu nn ON l.id_nhungcauchaohoi_ngonngu = nn.id
        WHERE l.id_ngonngu = ? AND l.page = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new RuntimeException('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param('is', $language_id, $page);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fallback khi không có dữ liệu
    if ($result->num_rows === 0) {
        $default = [
            1 => 'Đồng hành cùng bạn khám phá vẻ đẹp Tây Bắc',
            2 => 'Accompanying you to explore the beauty of the Northwest',
        ];
        $stmt->close();
        return $default[$language_id] ?? $default[2];
    }

    $greeting = $result->fetch_assoc()['content'];
    $stmt->close();
    return $greeting;
}

function getSelectedDescription($languageId, $area)
{
    global $conn;
    $description = null;

    $sql = "SELECT mn.id, mn.id_mota, mn.id_ngonngu, mn.title, mn.content
            FROM mota_ngonngu mn
            JOIN chon_mo_ta sd ON mn.id = sd.id_mota_ngonngu
            WHERE sd.area = ? AND sd.language_id = ?
            ORDER BY mn.id ASC";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $area, $languageId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $description = [
                'id' => $row['id'],
                'id_mota' => $row['id_mota'],
                'id_ngonngu' => $row['id_ngonngu'],
                'title' => $row['title'],
                'content' => $row['content']
            ];
        }

        mysqli_stmt_close($stmt);
    } else {
        error_log("Lỗi chuẩn bị truy vấn getSelectedDescription: " . mysqli_error($conn));
    }

    return $description;
}

function getImageGeneral($limit = null)
{
    global $conn;
    $images = [];

    // Bắt đầu xây dựng câu SQL
    $sql = "SELECT 
                atq.id,
                atq.image,
                atq.id_topic,
                atq.id_thongtinhotel
            FROM anhtongquat atq
            WHERE 1=1";

    $params = [];
    $types = '';

    // Sắp xếp trước rồi mới giới hạn
    $sql .= " ORDER BY atq.id ASC";

    // Thêm LIMIT nếu có
    if ($limit !== null) {
        $sql .= " LIMIT ?";
        $params[] = $limit;
        $types .= 'i';
    }

    // Chuẩn bị statement
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Nếu có tham số, bind chúng
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $images[] = [
                    'id' => $row['id'],
                    'image' => $row['image'],
                    'id_topic' => $row['id_topic'],
                    'id_thongtinhotel' => $row['id_thongtinhotel']
                ];
            }
        } else {
            error_log("Lỗi thực thi truy vấn getImageGeneral: " . mysqli_error($conn));
        }

        if ($stmt instanceof mysqli_stmt) {
            mysqli_stmt_close($stmt);
        }
    } else {
        error_log("Lỗi chuẩn bị truy vấn getImageGeneral: " . mysqli_error($conn));
    }

    return $images;
}


function getSubsectionHeaderCategories($id_header = null, $languageId = null)
{
    global $conn;
    $categories = [];

    // Kiểm tra kết nối cơ sở dữ liệu
    if (!$conn) {
        error_log("Lỗi: Kết nối cơ sở dữ liệu không tồn tại.");
        return $categories;
    }

    // Kiểm tra nếu $id_header là null hoặc không hợp lệ
    if ($id_header === null || !is_numeric($id_header)) {
        error_log("Lỗi: id_header không hợp lệ hoặc không được cung cấp.");
        return $categories;
    }

    $sql = "SELECT 
                t.id,
                t.code,
                t.position,
                t.active,
                t.id_danhmucheader,
                tn.id_ngonngu,
                tn.name
            FROM tieumucheader t
            LEFT JOIN tieumucheader_ngonngu tn ON t.id = tn.id_tieumucheader
            WHERE t.active = 1 AND t.id_danhmucheader = ?";

    // Thêm điều kiện ngôn ngữ nếu $languageId được cung cấp
    $paramTypes = "i"; // Loại tham số mặc định cho id_header
    $params = [$id_header]; // Mảng tham số để liên kết

    if ($languageId !== null && is_numeric($languageId)) {
        $sql .= " AND tn.id_ngonngu = ?";
        $paramTypes .= "i"; // Thêm kiểu cho id_ngonngu
        $params[] = $languageId; // Thêm languageId vào mảng tham số
    }

    $sql .= " ORDER BY t.position ASC";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Liên kết tham số động
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);
        }

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $categories[] = [
                    'id' => $row['id'],
                    'code' => $row['code'],
                    'position' => $row['position'],
                    'active' => $row['active'],
                    'id_danhmucheader' => $row['id_danhmucheader'],
                    'id_ngonngu' => $row['id_ngonngu'],
                    'name' => $row['name']
                ];
            }
        } else {
            error_log("Lỗi thực thi truy vấn getSubsectionHeaderCategories: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
    } else {
        error_log("Lỗi chuẩn bị truy vấn getSubsectionHeaderCategories: " . mysqli_error($conn));
    }

    return $categories;
}

function getRoomTypes($languageId = null)
{
    global $conn;
    $rooms = [];

    $sql = "SELECT lpn.id, lpn.quantity, lpn.area, lpn.price, lpnn.name, lpnn.description
            FROM loaiphongnghi lpn
            JOIN loaiphongnghi_ngonngu lpnn ON lpn.id = lpnn.id_loaiphongnghi
            WHERE lpnn.id_ngonngu = ?
            ORDER BY lpn.id ASC";

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $languageId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $rooms[] = [
                'id' => $row['id'],
                'quantity' => $row['quantity'],
                'area' => $row['area'],
                'price' => $row['price'],
                'name' => $row['name'],
                'description' => $row['description']
            ];
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Lỗi chuẩn bị truy vấn getRoomTypes: " . mysqli_error($conn));
    }

    return $rooms;
}

function getRoomDetail($room_id, $languageId)
{
    // Câu truy vấn SQL để lấy chi tiết phòng
    global $conn;
    $sql_room_detail = "
        SELECT 
            lpn.id,
            lpn.quantity,
            lpn.area,
            lpn.price,
            lpnnn.name,
            lpnnn.description
        FROM loaiphongnghi lpn
        LEFT JOIN loaiphongnghi_ngonngu lpnnn ON lpn.id = lpnnn.id_loaiphongnghi
        WHERE lpn.id = ? AND (lpnnn.id_ngonngu = ? OR lpnnn.id_ngonngu IS NULL)
    ";

    // Chuẩn bị và thực thi truy vấn
    $stmt = $conn->prepare($sql_room_detail);
    if (!$stmt) {
        // Xử lý lỗi chuẩn bị truy vấn
        error_log("Prepare failed: " . $conn->error);
        return null;
    }

    $stmt->bind_param("ii", $room_id, $languageId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra kết quả
    if ($result->num_rows == 0) {
        // Không tìm thấy phòng, chuyển hướng về danh sách phòng
        header("Location: danhsachphong.php");
        exit();
    }

    // Lấy dữ liệu phòng
    $room = $result->fetch_assoc();

    // Đóng statement
    $stmt->close();

    return $room;
}

function getBedTypesForRoom($roomTypeId, $languageId = null)
{
    global $conn;
    $bedTypes = [];



    $sql = "SELECT lg.area, lglp.quantity, lgnn.name
            FROM loaigiuong_loaiphong lglp
            JOIN loaigiuong lg ON lglp.id_loaigiuong = lg.id
            JOIN loaigiuong_ngonngu lgnn ON lg.id = lgnn.id_loaigiuong
            WHERE lglp.id_loaiphongnghi = ? AND lgnn.id_ngonngu = ?";

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $roomTypeId, $languageId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $bedTypes[] = [
                'area' => $row['area'],
                'quantity' => $row['quantity'],
                'name' => $row['name']
            ];
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Lỗi chuẩn bị truy vấn getBedTypesForRoom: " . mysqli_error($conn));
    }

    return $bedTypes;
}

// lấy cho các thẻ danh sách phong
function getAmenitiesForRoom($roomTypeId, $languageId)
{
    global $conn;
    $amenities = [];

    $sql = "SELECT tn.content
            FROM tienich_loaiphong tlp
            JOIN tienich t ON tlp.id_tienich = t.id
            JOIN tienich_ngonngu tn ON t.id = tn.id_tienich
            WHERE tlp.id_loaiphong = ? AND tn.id_ngonngu = ? AND t.active = 1 LIMIT 6";

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $roomTypeId, $languageId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $amenities[] = $row['content'];
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Lỗi chuẩn bị truy vấn getAmenitiesForRoom: " . mysqli_error($conn));
    }

    return $amenities;
}

function getImagesForRoom($roomTypeId)
{
    global $conn;
    $images = [];

    $sql = "SELECT image
            FROM anhkhachsan
            WHERE id_loaiphongnghi = ? AND active = 1
            ORDER BY id ASC";

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $roomTypeId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $images[] = $row['image'];
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Lỗi chuẩn bị truy vấn getImagesForRoom: " . mysqli_error($conn));
    }

    return $images;
}

function getSelectedImage($area)
{
    global $conn;
    $image = null;

    $sql = "SELECT atq.id, atq.image
            FROM anhtongquat atq
            JOIN chon_anhtongquat cat ON atq.id = cat.id_anhtongquat
            WHERE cat.area = ? AND atq.active = 1
            ORDER BY atq.id ASC";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $area);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $image = [
                'id' => $row['id'],
                'image' => $row['image'],
            ];
        }

        mysqli_stmt_close($stmt);
    } else {
        error_log("Lỗi chuẩn bị truy vấn getSelectedImage: " . mysqli_error($conn));
    }

    return $image;
}

// Hàm tổng hợp dữ liệu dịch vụ cho carousel
function getServicesForCarousel($languageId)
{
    global $conn;
    $services = [];

    // Truy vấn kết hợp các bảng
    $query = "
        SELECT d.id, dn.title, a.image
        FROM dichvu d
        LEFT JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu AND dn.id_ngonngu = ?
        LEFT JOIN anhdichvu a ON d.id = a.id_dichvu AND a.is_primary = 1
        WHERE d.active = 1
        ORDER BY d.id
    ";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        $conn->close();
        return $services;
    }

    $stmt->bind_param("i", $languageId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $services[] = [
                'id' => $row['id'],
                'title' => $row['title'] ?? ($languageId == 1 ? 'Dịch vụ không xác định' : 'Undefined Service'),
                'image' => $row['image'] ?? 'default-service-image.jpg'
            ];
        }
    }

    $stmt->close();
    return $services;
}

function getServiceById($languageId, $id_dichvu)
{
    global $conn; // Giả sử $conn đã được thiết lập trước đó
    $service = [
        'info' => null,
        'images' => []
    ];

    // Truy vấn thông tin dịch vụ và nội dung ngôn ngữ
    $sql = "
        SELECT 
            d.id, 
            d.icon, 
            d.price, 
            d.type, 
            dn.title, 
            dn.content
        FROM dichvu d
        LEFT JOIN dichvu_ngonngu dn 
            ON d.id = dn.id_dichvu 
            AND dn.id_ngonngu = ?
        WHERE d.id = ? AND d.active = 1
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $languageId, $id_dichvu);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $service['info'] = [
                'id' => $row['id'],
                'icon' => $row['icon'],
                'price' => $row['price'],
                'type' => $row['type'],
                'title' => $row['title'],
                'content' => $row['content']
            ];
        }

        mysqli_stmt_close($stmt);
    } else {
        error_log("Lỗi chuẩn bị truy vấn thông tin dịch vụ: " . mysqli_error($conn));
        return $service;
    }

    // Truy vấn danh sách ảnh
    $sql_images = "
        SELECT image
        FROM anhdichvu 
        WHERE id_dichvu = ? 
        ORDER BY is_primary DESC, id ASC
    ";

    $stmt_images = mysqli_prepare($conn, $sql_images);

    if ($stmt_images) {
        mysqli_stmt_bind_param($stmt_images, "i", $id_dichvu);
        mysqli_stmt_execute($stmt_images);
        $result_images = mysqli_stmt_get_result($stmt_images);

        while ($row = mysqli_fetch_assoc($result_images)) {
            $service['images'][] = [
                'image' => htmlspecialchars($row['image'])
            ];
        }

        mysqli_stmt_close($stmt_images);
    } else {
        error_log("Lỗi chuẩn bị truy vấn ảnh dịch vụ: " . mysqli_error($conn));
    }

    return $service;
}
// Hàm lấy danh sách tin tức với tiêu đề, nội dung, ngày tạo, và ảnh chính (giới hạn 3)
function getNewsList($languageId)
{
    global $conn;
    $newsList = [];

    $query = "
        SELECT t.id, t.create_at, tn.title, tn.content, a.image
        FROM tintuc t
        LEFT JOIN tintuc_ngonngu tn ON t.id = tn.id_tintuc AND tn.id_ngonngu = ?
        LEFT JOIN anhtintuc a ON t.id = a.id_tintuc AND a.is_primary = 1
        WHERE t.active = 1
        ORDER BY t.create_at DESC
        LIMIT 3
    ";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        return $newsList;
    }

    $stmt->bind_param("i", $languageId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Định dạng ngày tháng
            $date = new DateTime($row['create_at']);
            $day = $date->format('d');
            $month = $languageId == 1 ? $date->format('M') : $date->format('M');
            if ($languageId == 1) {
                $month = str_replace(
                    ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
                    $month
                );
            }

            $newsList[] = [
                'id' => $row['id'],
                'title' => $row['title'] ?? ($languageId == 1 ? 'Tin tức không xác định' : 'Undefined News'),
                'content' => $row['content'] ?? ($languageId == 1 ? 'Nội dung không có' : 'No content available'),
                'day' => $day,
                'month' => $month,
                'image' => $row['image'] ?? 'default-news-image.jpg'
            ];
        }
    }

    $stmt->close();
    return $newsList;
}

function getBinhLuan($id_khachhang = null, $limit = 4)
{
    global $conn;
    $sql = "SELECT bl.id, bl.content, bl.create_at, bl.rate, bl.active, kh.name, kh.img 
            FROM binhluan bl 
            INNER JOIN khachhang kh ON bl.id_khachhang = kh.id 
            WHERE bl.active = 1";
    if ($id_khachhang !== null) {
        $sql .= " AND bl.id_khachhang = ?";
    }
    $sql .= " ORDER BY bl.id LIMIT ?";
    $stmt = $conn->prepare($sql);
    if ($id_khachhang !== null) {
        $stmt->bind_param("ii", $id_khachhang, $limit);
    } else {
        $stmt->bind_param("i", $limit);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $data ?: false;
}

// Hàm lấy dữ liệu footer từ 4 bảng sử dụng mysqli
function getFooterData($languageId)
{
    global $conn;
    $footerData = [];

    // Lấy tất cả danh mục footer đang active, sắp xếp theo position
    $queryDanhmuc = "
        SELECT dm.id, dm.position, dmn.name
        FROM danhmucfooter dm
        JOIN danhmucfooter_ngonngu dmn ON dm.id = dmn.id_danhmucfooter
        WHERE dm.active = 1 AND dmn.id_ngonngu = ?
        ORDER BY dm.position ASC
    ";
    $stmtDanhmuc = $conn->prepare($queryDanhmuc);
    $stmtDanhmuc->bind_param("i", $languageId);
    $stmtDanhmuc->execute();
    $resultDanhmuc = $stmtDanhmuc->get_result();
    $danhmucList = $resultDanhmuc->fetch_all(MYSQLI_ASSOC);

    // Với mỗi danh mục, lấy danh sách tiểu mục
    foreach ($danhmucList as $danhmuc) {
        $danhmucId = $danhmuc['id'];

        $queryTieumuc = "
            SELECT tm.id, tm.code, tm.position, tmn.name
            FROM tieumucfooter tm
            JOIN tieumucfooter_ngonngu tmn ON tm.id = tmn.id_tieumucfooter
            WHERE tm.active = 1 AND tm.id_danhmucfooter = ? AND tmn.id_ngonngu = ?
            ORDER BY tm.position ASC
        ";
        $stmtTieumuc = $conn->prepare($queryTieumuc);
        $stmtTieumuc->bind_param("ii", $danhmucId, $languageId);
        $stmtTieumuc->execute();
        $resultTieumuc = $stmtTieumuc->get_result();
        $tieumucList = $resultTieumuc->fetch_all(MYSQLI_ASSOC);

        // Thêm danh mục và tiểu mục vào mảng kết quả
        $footerData[] = [
            'danhmuc_name' => $danhmuc['name'],
            'tieumuc' => $tieumucList
        ];
    }

    $conn->close();
    return $footerData;
}

function getSelectedBanner($page, $area)
{
    global $conn; // Giả sử $conn đã được thiết lập trước đó
    $banner = null;

    $sql = "SELECT id, image
            FROM head_banner
            WHERE page = ? AND area = ?
            ORDER BY id ASC";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Ràng buộc tham số: cả page và area đều là string
        mysqli_stmt_bind_param($stmt, "ss", $page, $area);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $banner = [
                'id' => $row['id'],
                'image' => $row['image'],
            ];
        }

        mysqli_stmt_close($stmt);
    } else {
        error_log("Lỗi chuẩn bị truy vấn getSelectedBanner: " . mysqli_error($conn));
    }

    return $banner;
}

// Hàm lấy ảnh is_primary, title, content, và code
function getPrimaryEventData($languageId)
{
    global $conn;
    $results = [];

    $sql = "SELECT a.image, sk.code, sgn.title, sgn.content
            FROM anhsukien a
            JOIN sukien sk ON a.id_sukien = sk.id
            JOIN sukien_ngonngu sgn ON sk.id = sgn.id_sukien
            WHERE a.is_primary = 1 AND sgn.id_ngonngu = ? AND sk.active = 1
            ORDER BY a.id ASC";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $languageId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = [
                'image' => $row['image'],
                'code' => $row['code'],
                'title' => $row['title'],
                'content' => $row['content']
            ];
        }

        mysqli_stmt_close($stmt);
    } else {
        error_log("Lỗi chuẩn bị truy vấn getPrimaryEventData: " . mysqli_error($conn));
    }
    return $results;
}

function getOrganizedEvents($languageId, $limit = null)
{
    global $conn;
    $events = [];

    $sql = "
        SELECT DISTINCT
            skdt.id,
            skdt.type_serviced,
            skdt.create_at,
            skdt_ngonngu.title,
            skdt_ngonngu.content,
            askdt.image
        FROM sukiendatochuc AS skdt
        LEFT JOIN sukiendatochuc_ngonngu AS skdt_ngonngu
               ON  skdt.id = skdt_ngonngu.id_sukiendatochuc
               AND skdt_ngonngu.id_ngonngu = ?
        LEFT JOIN anhsukiendatochuc AS askdt
               ON  skdt.id = askdt.id_sukiendatochuc
               AND askdt.is_primary = 1
        WHERE skdt.active = 1
        ORDER BY skdt.create_at DESC";

    if ($limit !== null) {
        $sql .= " LIMIT ?";
    }

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log('Lỗi chuẩn bị truy vấn getOrganizedEvents: ' . mysqli_error($conn));
        return $events;
    }

    if ($limit !== null) {

        mysqli_stmt_bind_param($stmt, "ii", $languageId, $limit);
    } else {
        mysqli_stmt_bind_param($stmt, "i", $languageId);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $date  = DateTime::createFromFormat('Y-m-d H:i:s', $row['create_at'] ?? '');
        $event = [
            'id'            => $row['id'],
            'type_serviced' => $row['type_serviced'],
            'create_at'     => $date ? $date->format('d/m/Y') : '',
            'title'         => $row['title'],
            'content'       => $row['content'],
            'image'         => $row['image'],
        ];
        $events[] = $event;
    }

    mysqli_stmt_close($stmt);
    return $events;
}


function getImageOrganizedEvents($type_serviced, $limit = null)
{
    global $conn;
    $events = [];

    // Kiểm tra kết nối cơ sở dữ liệu
    if (!$conn) {
        error_log("Lỗi: Kết nối cơ sở dữ liệu không tồn tại.");
        return $events;
    }

    // Xây dựng câu truy vấn SQL
    // $sql = "SELECT 
    //             skdt.type_serviced,
    //             askdt.image
    //         FROM sukiendatochuc skdt
    //         LEFT JOIN anhsukiendatochuc askdt ON skdt.id = askdt.id_sukiendatochuc
    //         WHERE skdt.active = 1 AND skdt.type_serviced = ?
    //         GROUP BY skdt.type_serviced, askdt.image
    //         ORDER BY skdt.id DESC";
    $sql = "SELECT 
                skdt.type_serviced,
                askdt.image
            FROM sukiendatochuc skdt
            LEFT JOIN anhsukiendatochuc askdt ON skdt.id = askdt.id_sukiendatochuc
            WHERE skdt.active = 1 AND skdt.type_serviced = ?
            AND askdt.image IS NOT NULL
            ORDER BY RAND()";

    // Thêm LIMIT nếu được cung cấp
    if ($limit !== null && is_numeric($limit) && $limit >= 0) {
        $sql .= " LIMIT ?";
    }

    // Chuẩn bị truy vấn
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log("Lỗi chuẩn bị truy vấn getImageOrganizedEvents: " . mysqli_error($conn));
        return $events;
    }

    // Gán tham số
    if ($limit !== null && is_numeric($limit) && $limit >= 0) {
        mysqli_stmt_bind_param($stmt, "si", $type_serviced, $limit);
    } else {
        mysqli_stmt_bind_param($stmt, "s", $type_serviced);
    }

    // Thực thi truy vấn
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Lỗi thực thi truy vấn getImageOrganizedEvents: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return $events;
    }

    // Lấy kết quả
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = [
            'type_serviced' => $row['type_serviced'],
            'image' => $row['image'] ?? 'default-event-image.jpg'
        ];
    }

    // Đóng statement
    mysqli_stmt_close($stmt);

    return $events;
}

// function getConferenceRooms($languageId = null)
// {
//     global $conn;
//     $rooms = [];

//     // Kiểm tra kết nối cơ sở dữ liệu
//     if (!$conn) {
//         error_log("Lỗi: Kết nối cơ sở dữ liệu không tồn tại.");
//         return $rooms;
//     }

//     // Xây dựng câu truy vấn SQL
//     $sql = "SELECT 
//                 ht.id,
//                 ht.room_number,
//                 ht.opacity AS capacity,
//                 ht.area,
//                 ht.floor_number,
//                 htnn.name,
//                 htnn.description,
//                 GROUP_CONCAT(DISTINCT CONCAT(gtht.how_long, ':', gtht.price) SEPARATOR '|') AS prices,
//                 GROUP_CONCAT(DISTINCT aht.image) AS images
//             FROM hoitruong ht
//             LEFT JOIN hoitruong_ngonngu htnn ON ht.id = htnn.id_hoitruong
//             LEFT JOIN giathuehoitruong gtht ON ht.id = gtht.id_hoitruong
//             LEFT JOIN anhhoitruong aht ON ht.id = aht.id_hoitruong
//             WHERE htnn.id_ngonngu = ? AND aht.active = 1
//             GROUP BY ht.id, htnn.name, htnn.description
//             ORDER BY ht.floor_number ASC";

//     // Chuẩn bị truy vấn
//     $stmt = mysqli_prepare($conn, $sql);
//     if (!$stmt) {
//         error_log("Lỗi chuẩn bị truy vấn getConferenceRooms: " . mysqli_error($conn));
//         return $rooms;
//     }

//     // Gán tham số
//     mysqli_stmt_bind_param($stmt, "i", $languageId);

//     // Thực thi truy vấn
//     if (!mysqli_stmt_execute($stmt)) {
//         error_log("Lỗi thực thi truy vấn getConferenceRooms: " . mysqli_stmt_error($stmt));
//         mysqli_stmt_close($stmt);
//         return $rooms;
//     }

//     // Lấy kết quả
//     $result = mysqli_stmt_get_result($stmt);
//     while ($row = mysqli_fetch_assoc($result)) {
//         // Xử lý giá thuê
//         $prices = [];
//         if ($row['prices']) {
//             foreach (explode('|', $row['prices']) as $price) {
//                 list($how_long, $price_value) = explode(':', $price);
//                 $prices[$how_long] = $price_value;
//             }
//         }

//         // Xử lý danh sách ảnh
//         $images = $row['images'] ? explode(',', $row['images']) : ['default-room-image.jpg'];

//         $rooms[] = [
//             'id' => $row['id'],
//             'room_number' => $row['room_number'],
//             'capacity' => $row['capacity'],
//             'area' => $row['area'],
//             'floor_number' => $row['floor_number'],
//             'name' => $row['name'],
//             'description' => $row['description'],
//             'prices' => $prices,
//             'images' => $images
//         ];
//     }

//     // Đóng statement
//     mysqli_stmt_close($stmt);

//     return $rooms;
// }
function getConferenceRooms($languageId = null, $active = 1)
{
    global $conn;
    $rooms = [];

    if (!$conn) {
        error_log("Lỗi: Kết nối cơ sở dữ liệu không tồn tại.");
        return $rooms;
    }

    $sql = "SELECT 
                ht.id,
                ht.room_number,
                ht.opacity AS capacity,
                ht.area,
                ht.floor_number,
                htnn.name,
                htnn.description,
                GROUP_CONCAT(DISTINCT CONCAT(gtht.how_long, ':', gtht.price) SEPARATOR '|') AS prices,
                GROUP_CONCAT(DISTINCT aht.image) AS images
            FROM hoitruong ht
            LEFT JOIN hoitruong_ngonngu htnn ON ht.id = htnn.id_hoitruong AND htnn.id_ngonngu = ?
            LEFT JOIN giathuehoitruong gtht ON ht.id = gtht.id_hoitruong
            LEFT JOIN anhhoitruong aht ON ht.id = aht.id_hoitruong AND aht.active = 1
            WHERE ht.active = ?
            GROUP BY ht.id, htnn.name, htnn.description
            ORDER BY ht.floor_number ASC";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log("Lỗi chuẩn bị truy vấn getConferenceRooms: " . mysqli_error($conn));
        return $rooms;
    }

    mysqli_stmt_bind_param($stmt, "ii", $languageId, $active);
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Lỗi thực thi truy vấn getConferenceRooms: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return $rooms;
    }

    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $prices = [];
        if ($row['prices']) {
            foreach (explode('|', $row['prices']) as $price) {
                if (strpos($price, ':') !== false) {
                    list($how_long, $price_value) = explode(':', $price);
                    $prices[$how_long] = $price_value;
                }
            }
        }

        $images = $row['images'] ? explode(',', $row['images']) : ['default-room-image.jpg'];

        $rooms[] = [
            'id' => $row['id'],
            'room_number' => $row['room_number'],
            'capacity' => $row['capacity'],
            'area' => $row['area'],
            'floor_number' => $row['floor_number'],
            'name' => $row['name'],
            'description' => $row['description'],
            'prices' => $prices,
            'images' => $images
        ];
    }

    mysqli_stmt_close($stmt);
    return $rooms;
}

// Lấy các loại sự kiện cho form
function getEventTypes($languageId = null)
{
    global $conn;
    $eventTypes = [];

    // Kiểm tra kết nối cơ sở dữ liệu
    if (!$conn) {
        error_log("Lỗi: Kết nối cơ sở dữ liệu không tồn tại.");
        return $eventTypes;
    }

    // Xây dựng câu truy vấn SQL
    $sql = "SELECT 
                sk.code,
                sknn.title
            FROM sukien sk
            LEFT JOIN sukien_ngonngu sknn ON sk.id = sknn.id_sukien
            WHERE sk.active = 1 AND sknn.id_ngonngu = ?
            ORDER BY sk.id ASC";

    // Chuẩn bị truy vấn
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log("Lỗi chuẩn bị truy vấn getEventTypes: " . mysqli_error($conn));
        return $eventTypes;
    }

    // Gán tham số
    mysqli_stmt_bind_param($stmt, "i", $languageId);

    // Thực thi truy vấn
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Lỗi thực thi truy vấn getEventTypes: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return $eventTypes;
    }

    // Lấy kết quả
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $eventTypes[] = [
            'code' => $row['code'],
            'title' => $row['title']
        ];
    }

    // Đóng statement
    mysqli_stmt_close($stmt);

    // Thêm tùy chọn "Khác" hoặc "Other"
    $eventTypes[] = [
        'code' => 'other',
        'title' => $languageId == 1 ? 'Khác' : 'Other'
    ];

    return $eventTypes;
}

function getConferenceHalls($languageId = null)
{
    global $conn;
    $halls = [];

    // Kiểm tra kết nối cơ sở dữ liệu
    if (!$conn) {
        error_log("Lỗi: Kết nối cơ sở dữ liệu không tồn tại.");
        return $halls;
    }

    // Xây dựng câu truy vấn SQL
    $sql = "SELECT 
                ht.id,
                htnn.name
            FROM hoitruong ht
            LEFT JOIN hoitruong_ngonngu htnn ON ht.id = htnn.id_hoitruong
            WHERE htnn.id_ngonngu = ?
            ORDER BY ht.floor_number ASC";

    // Chuẩn bị truy vấn
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log("Lỗi chuẩn bị truy vấn getConferenceHalls: " . mysqli_error($conn));
        return $halls;
    }

    // Gán tham số
    mysqli_stmt_bind_param($stmt, "i", $languageId);

    // Thực thi truy vấn
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Lỗi thực thi truy vấn getConferenceHalls: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return $halls;
    }

    // Lấy kết quả
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $halls[] = [
            'id' => $row['id'],
            'name' => $row['name']
        ];
    }

    // Đóng statement
    mysqli_stmt_close($stmt);

    return $halls;
}

function getCustomerIdByEmail($email)
{
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM khachhang WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['id'] ?? null;
}

function getCustomers()
{
    global $conn;
    $customers = [];

    $sql = "SELECT DISTINCT id, name, email FROM khachhang GROUP BY name, email ORDER BY name";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $customers[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'email' => $row['email']
            ];
        }
        $result->free(); // Giải phóng bộ nhớ
    } else {
        error_log("Lỗi truy vấn getCustomers: " . $conn->error);
    }

    return $customers;
}

function createCustomer($name, $phone, $email, $img = null)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO khachhang (name, phone, email, img) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $phone, $email, $img);
    $stmt->execute();
    $customerId = $conn->insert_id;
    $stmt->close();
    return $customerId;
}

function createContactRequest($subject, $message, $status, $type, $customerId)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO contact_requests (service, message, status, type, id_khachhang) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $subject, $message, $status, $type, $customerId); // s = string, i = integer
    $stmt->execute();
    $contactRequestId = $conn->insert_id;
    $stmt->close();
    return $contactRequestId;
}

function insertEventBooking($type_event, $start_at, $end_at, $number_people, $note, $images, $budget, $status, $how_long, $id_hoitruong, $id_khachhang)
{
    global $conn;
    $stmt = $conn->prepare("
        INSERT INTO dathoitruong (type_event, start_at, end_at, number_people, note, images, budget, status, how_long, created_at, id_hoitruong, id_khachhang)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)
    ");
    $stmt->bind_param("sssissisiii", $type_event, $start_at, $end_at, $number_people, $note, $images, $budget, $status, $how_long, $id_hoitruong, $id_khachhang);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function getFoodServices($languageId)
{
    global $conn;
    $services = [];

    // Truy vấn lấy danh sách ẩm thực và thông tin ngôn ngữ
    $sql = "SELECT a.id, a.code, an.title, an.content
            FROM amthuc a
            LEFT JOIN amthuc_ngonngu an ON a.id = an.id_amthuc AND an.id_ngonngu = ?
            WHERE a.active = 1
            ORDER BY a.id ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $languageId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $service = [
            'id' => $row['id'],
            'code' => $row['code'],
            'title' => $row['title'],
            'content' => $row['content'],
            'image' => null
        ];

        // Lấy ngẫu nhiên 1 ảnh từ anhnhahang hoặc anhbar dựa trên id_amthuc
        if ($row['id'] == 1) { // Nhà Hàng
            $imageSql = "SELECT image FROM anhnhahang WHERE active = 1  ORDER BY RAND() LIMIT 1";
        } else { // Sky Bar
            $imageSql = "SELECT image FROM anhbar WHERE active = 1  ORDER BY RAND() LIMIT 1";
        }

        $imageResult = $conn->query($imageSql);
        if ($imageResult && $imageResult->num_rows > 0) {
            $imageRow = $imageResult->fetch_assoc();
            $service['image'] = $imageRow['image'];
        }

        $services[] = $service;
    }

    $stmt->close();
    return $services;
}

function getFeaturedDishes($languageId)
{
    global $conn; // Sử dụng kết nối toàn cục
    // Khởi tạo mảng kết quả
    $featuredDishes = [];

    // Truy vấn SQL để lấy các món ăn đặc sắc (outstanding = 1)
    $sql = "
        SELECT 
            t.id,
            t.price,
            tn.name AS title,
            tn.content AS description,
            a.image
        FROM 
            thucdon t
        LEFT JOIN 
            thucdon_ngonngu tn ON t.id = tn.id_thucdon AND tn.id_ngonngu = ?
        LEFT JOIN 
            anhthucdon a ON t.id = a.id_menu
        WHERE 
            t.outstanding = 1
            AND t.active = 1
        ORDER BY 
            t.id ASC
    ";

    // Chuẩn bị câu truy vấn để tránh SQL Injection
    if ($stmt = $conn->prepare($sql)) {
        // Bind tham số
        $stmt->bind_param("i", $languageId);

        // Thực thi truy vấn
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result();

        // Lặp qua kết quả và lưu vào mảng
        while ($row = $result->fetch_assoc()) {
            $featuredDishes[] = [
                'id' => $row['id'],
                'price' => $row['price'],
                'title' => $row['title'],
                'description' => $row['description'],
                'image' => $row['image']
            ];
        }

        // Đóng statement
        $stmt->close();
    } else {
        // Ghi lỗi nếu có
        error_log("Error preparing statement: " . $conn->error);
    }

    // Trả về mảng dữ liệu
    return $featuredDishes;
}

function getDiningAreas($languageId)
{
    global $conn; // Sử dụng kết nối toàn cục
    $diningAreas = [];

    // Truy vấn SQL để lấy danh sách khu vực dựa trên ngôn ngữ
    $sql = "
        SELECT 
            n.room_number AS value,
            nn.name AS label
        FROM 
            nhahang n
        LEFT JOIN 
            nhahang_ngonngu nn ON n.id = nn.id_nhahang AND nn.id_ngonngu = ?
        WHERE 
            n.opacity > 0
        ORDER BY 
            n.room_number ASC
    ";

    // Chuẩn bị câu truy vấn để tránh SQL Injection
    if ($stmt = $conn->prepare($sql)) {
        // Bind tham số
        $stmt->bind_param("i", $languageId);

        // Thực thi truy vấn
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result();

        // Lặp qua kết quả và lưu vào mảng
        while ($row = $result->fetch_assoc()) {
            $diningAreas[] = [
                'value' => $row['value'],
                'label' => $row['label']
            ];
        }

        // Đóng statement
        $stmt->close();
    } else {
        // Ghi lỗi nếu có
        error_log("Error preparing statement: " . $conn->error);
    }

    // Trả về mảng dữ liệu
    return $diningAreas;
}

function getRandomHeroImages()
{
    global $conn; // Sử dụng kết nối toàn cục
    $heroImages = [];

    // Truy vấn SQL để lấy ngẫu nhiên 3 ảnh từ bảng anhthucdon
    $sql = "
        SELECT image
        FROM anhthucdon
        ORDER BY RAND()
        LIMIT 3
    ";

    // Thực thi truy vấn
    $result = $conn->query($sql);

    if ($result) {
        // Lặp qua kết quả và lưu vào mảng
        while ($row = $result->fetch_assoc()) {
            $heroImages[] = $row['image'];
        }
        $result->free(); // Giải phóng kết quả
    } else {
        // Ghi lỗi nếu có
        error_log("Error executing query: " . $conn->error);
    }

    // Trả về mảng chứa 3 ảnh (hoặc ít hơn nếu dữ liệu không đủ)
    return $heroImages;
}

function insertRestaurantBooking($location, $startAt, $numberPeople, $note, $occasion, $status, $idKhachhang)
{
    global $conn;
    $sql = "INSERT INTO datbannhahang (location, start_at, number_people, note, occasion, status, created_at, id_khachhang) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssiissi", $location, $startAt, $numberPeople, $note, $occasion, $status, $idKhachhang);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    return false;
}

function insertBarBooking($startAt, $numberPeople, $note, $status, $idKhachhang)
{
    global $conn;
    $sql = "INSERT INTO datbanbar (start_at, number_people, note, status, created_at, id_khachhang) 
            VALUES (?, ?, ?, ?, NOW(), ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sissi", $startAt, $numberPeople, $note, $status, $idKhachhang);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    return false;
}

// Hàm lấy ảnh từ bảng anhnhahang sử dụng MySQLi
function getRestaurantImages($limit = null)
{
    global $conn; // Giả sử $conn là biến kết nối MySQLi tới cơ sở dữ liệu

    // Kiểm tra kết nối
    if (!$conn) {
        error_log("MySQLi connection failed: " . mysqli_connect_error());
        return [];
    }

    // Xây dựng câu truy vấn SQL
    $sql = "SELECT id, image, active, created_at, id_topic 
            FROM anhnhahang 
            WHERE active = 1";

    // Thêm giới hạn số lượng nếu được cung cấp
    if ($limit !== null) {
        $sql .= " LIMIT " . mysqli_real_escape_string($conn, $limit);
    }

    // Thực thi truy vấn
    $result = mysqli_query($conn, $sql);

    // Kiểm tra lỗi truy vấn
    if (!$result) {
        error_log("Error in getRestaurantImages: " . mysqli_error($conn));
        return [];
    }

    // Lấy tất cả kết quả
    $images = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $images[] = $row;
    }

    // Giải phóng kết quả
    mysqli_free_result($result);

    return $images;
}

function getAllMenuImages($languageId, $id_amthuc, $page = 1, $limit = 9)
{
    global $conn;
    $menuImages = [];
    $offset = ($page - 1) * $limit;

    // Đếm tổng số món ăn
    $countSql = "SELECT COUNT(*) as total FROM thucdon t WHERE t.id_amthuc = ? AND t.active = 1";
    $totalItems = 0;
    if ($stmt = $conn->prepare($countSql)) {
        $stmt->bind_param("i", $id_amthuc);
        $stmt->execute();
        $totalItems = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();
    }

    $totalPages = ceil($totalItems / $limit);

    // Lấy danh sách món ăn
    $sql = "
        SELECT 
            t.id,
            t.price,
            tn.name AS title,
            tn.content AS description,
            a.image
        FROM 
            thucdon t
        LEFT JOIN 
            thucdon_ngonngu tn ON t.id = tn.id_thucdon AND tn.id_ngonngu = ?
        LEFT JOIN 
            anhthucdon a ON t.id = a.id_menu
        WHERE 
            t.id_amthuc = ?
            AND t.active = 1
        ORDER BY 
            t.id ASC
        LIMIT ? OFFSET ?
    ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iiii", $languageId, $id_amthuc, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $menuImages[] = [
                'id' => $row['id'],
                'price' => $row['price'],
                'title' => $row['title'],
                'description' => $row['description'],
                'image' => $row['image']
            ];
        }
        $stmt->close();
    } else {
        error_log("Error preparing statement: " . $conn->error);
    }

    return [
        'menuImages' => $menuImages,
        'totalPages' => $totalPages,
        'currentPage' => $page,
        'limit' => $limit
    ];
}

function getAmThucNgonNgu($id_ngonngu, $id_amthuc)
{
    global $conn; // Sử dụng kết nối toàn cục
    $data = [];

    // Truy vấn SQL để lấy dữ liệu từ bảng amthuc_ngonngu
    $sql = "
        SELECT id, title, content, description
        FROM amthuc_ngonngu
        WHERE id_amthuc = $id_amthuc AND id_ngonngu = $id_ngonngu
        LIMIT 1
    ";

    // Thực thi truy vấn
    $result = $conn->query($sql);

    if ($result) {
        // Lấy bản ghi đầu tiên
        if ($row = $result->fetch_assoc()) {
            $data = $row;
        }
        $result->free(); // Giải phóng kết quả
    } else {
        // Ghi lỗi nếu có
        error_log("Error executing query: " . $conn->error);
    }

    // Trả về mảng dữ liệu hoặc mảng rỗng nếu không tìm thấy
    return $data;
}

function insertCommentRestaurant($name, $email, $content, $rating)
{
    global $conn;

    // Chèn vào bảng khachhang
    $sql = "INSERT INTO khachhang (name, email) VALUES (?, ?)
            ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $name, $email);
        $result = $stmt->execute();
        $id_khachhang = $conn->insert_id;
        $stmt->close();
        if (!$result) {
            return false;
        }
    } else {
        return false;
    }

    // Chèn vào bảng binhluan
    $create_at = date('Y-m-d H:i:s');
    $active = 1;
    $sql = "INSERT INTO binhluan (content, create_at, rate, active, id_khachhang) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssiii", $content, $create_at, $rating, $active, $id_khachhang);
        $result = $stmt->execute();
        $id_binhluan = $conn->insert_id;
        $stmt->close();
        if (!$result) {
            return false;
        }
    } else {
        return false;
    }

    // Chèn vào bảng binhluan_nhahang
    $sql = "INSERT INTO binhluan_nhahang (id_binhluan) VALUES (?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_binhluan);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    return false;
}

// Hàm mới để lấy tổng số bình luận
function getTotalRestaurantReviews()
{
    global $conn;
    $sql = "
        SELECT COUNT(*) as total
        FROM binhluan b
        JOIN binhluan_nhahang bn ON b.id = bn.id_binhluan
        WHERE b.active = 1
    ";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $result->free();
        return (int)$row['total'];
    }
    return 0;
}

// Cập nhật hàm getRestaurantReviews để hỗ trợ phân trang
function getRestaurantReviews($limit = 5, $offset = 0)
{
    global $conn;

    $reviews = [];

    $sql = "
        SELECT b.id, b.content, b.create_at, b.rate, k.name
        FROM binhluan b
        JOIN khachhang k ON b.id_khachhang = k.id
        JOIN binhluan_nhahang bn ON b.id = bn.id_binhluan
        WHERE b.active = 1
        ORDER BY b.create_at DESC
        LIMIT ? OFFSET ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        $result->free();
    } else {
        error_log("Error executing query: " . $conn->error);
    }

    $stmt->close();
    return $reviews;
}
function getAllRestaurantReviews()
{
    global $conn; // Giả sử bạn dùng biến $conn để kết nối cơ sở dữ liệu
    $query = "SELECT b.id, b.content, b.create_at, b.rate, k.name
        FROM binhluan b
        JOIN khachhang k ON b.id_khachhang = k.id
        JOIN binhluan_nhahang bn ON b.id = bn.id_binhluan
        WHERE b.active = 1
        ORDER BY b.create_at DESC";
    $result = mysqli_query($conn, $query);
    $reviews = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = $row;
    }
    return $reviews;
}

// Hàm lấy đánh giá của Sky Bar với phân trang
function getBarReviews($page = 1, $limit = 5)
{
    global $conn;
    $reviews = [];
    $offset = ($page - 1) * $limit;

    $sql = "
        SELECT b.id, b.content, b.create_at, b.rate, k.name
        FROM binhluan b
        JOIN khachhang k ON b.id_khachhang = k.id
        JOIN binhluan_bar bn ON b.id = bn.id_binhluan
        WHERE b.active = 1
        ORDER BY b.create_at DESC
        LIMIT ? OFFSET ?
    ";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $reviews[] = [
                'id' => $row['id'],
                'content' => $row['content'],
                'create_at' => $row['create_at'],
                'rate' => $row['rate'],
                'name' => $row['name']
            ];
        }

        $result->free();
        $stmt->close();
    } else {
        error_log("Error preparing statement in getBarReviews: " . $conn->error);
    }

    return $reviews;
}

// Hàm lấy tổng số đánh giá của Sky Bar
function getTotalBarReviews()
{
    global $conn;
    $sql = "
        SELECT COUNT(*) as total
        FROM binhluan b
        JOIN binhluan_bar bn ON b.id = bn.id_binhluan
        WHERE b.active = 1
    ";

    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $total = (int)$row['total'];
        $result->free();
        return $total;
    } else {
        error_log("Error executing query in getTotalBarReviews: " . $conn->error);
    }

    return 0;
}

// Hàm tính điểm đánh giá trung bình của Sky Bar
function calculateBarAverageRating()
{
    global $conn;
    $sql = "
        SELECT AVG(b.rate) as average_rating
        FROM binhluan b
        JOIN binhluan_bar bn ON b.id = bn.id_binhluan
        WHERE b.active = 1
    ";

    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $average = $row['average_rating'] ? number_format($row['average_rating'], 1) : "0.0";
        $result->free();
        return $average;
    } else {
        error_log("Error executing query in calculateBarAverageRating: " . $conn->error);
    }

    return "0.0";
}

// Hàm tính phân bố tỷ lệ phần trăm các mức sao của Sky Bar
function calculateBarRatingBreakdown()
{
    global $conn;
    $ratingBreakdown = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    $totalReviews = getTotalBarReviews();

    if ($totalReviews == 0) {
        return array_map(function () {
            return ['count' => 0, 'percentage' => 0];
        }, $ratingBreakdown);
    }

    $sql = "
        SELECT b.rate, COUNT(*) as count
        FROM binhluan b
        JOIN binhluan_bar bn ON b.id = bn.id_binhluan
        WHERE b.active = 1
        GROUP BY b.rate
    ";

    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rate = (int)$row['rate'];
            if ($rate >= 1 && $rate <= 5) {
                $percentage = ($row['count'] / $totalReviews) * 100;
                $ratingBreakdown[$rate] = [
                    'count' => (int)$row['count'],
                    'percentage' => round($percentage, 1)
                ];
            }
        }
        $result->free();
    } else {
        error_log("Error executing query in calculateBarRatingBreakdown: " . $conn->error);
    }

    // Điền các mức sao không có đánh giá
    foreach ($ratingBreakdown as $rate => &$data) {
        if (!is_array($data)) {
            $data = ['count' => 0, 'percentage' => 0];
        }
    }

    return $ratingBreakdown;
}

// Hàm chèn bình luận mới cho Sky Bar
function insertCommentBar($name, $email, $content, $rating)
{
    global $conn;

    // Chèn hoặc lấy id_khachhang từ bảng khachhang
    $sql = "INSERT INTO khachhang (name, email) VALUES (?, ?)
            ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $name, $email);
        $result = $stmt->execute();
        $id_khachhang = $conn->insert_id;
        $stmt->close();
        if (!$result) {
            error_log("Error inserting customer in insertCommentBar: " . $conn->error);
            return false;
        }
    } else {
        error_log("Error preparing statement in insertCommentBar (khachhang): " . $conn->error);
        return false;
    }

    // Chèn bình luận vào bảng binhluan
    $create_at = date('Y-m-d H:i:s');
    $active = 1;
    $sql = "INSERT INTO binhluan (content, create_at, rate, active, id_khachhang) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssiii", $content, $create_at, $rating, $active, $id_khachhang);
        $result = $stmt->execute();
        $id_binhluan = $conn->insert_id;
        $stmt->close();
        if (!$result) {
            error_log("Error inserting comment in insertCommentBar: " . $conn->error);
            return false;
        }
    } else {
        error_log("Error preparing statement in insertCommentBar (binhluan): " . $conn->error);
        return false;
    }

    // Chèn vào bảng binhluan_bar
    $sql = "INSERT INTO binhluan_bar (id_binhluan) VALUES (?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_binhluan);
        $result = $stmt->execute();
        $stmt->close();
        if (!$result) {
            error_log("Error inserting into binhluan_bar: " . $conn->error);
            return false;
        }
        return true;
    } else {
        error_log("Error preparing statement in insertCommentBar (binhluan_bar): " . $conn->error);
        return false;
    }

    return false;
}


function getMenuItemsByType($languageId, $type, $page = 1, $limit = 9)
{
    global $conn;
    $offset = ($page - 1) * $limit;

    // Truy vấn tổng số món ăn
    $totalQuery = "SELECT COUNT(*) as total 
                   FROM thucdon t 
                   LEFT JOIN thucdon_ngonngu tn ON t.id = tn.id_thucdon 
                   WHERE tn.id_ngonngu = ? AND t.type = ? AND t.id_amthuc = 2";
    $stmt = $conn->prepare($totalQuery);
    $stmt->bind_param("is", $languageId, $type);
    $stmt->execute();
    $totalResult = $stmt->get_result()->fetch_assoc();
    $totalItems = $totalResult['total'];
    $totalPages = ceil($totalItems / $limit);

    // Truy vấn danh sách món ăn
    $query = "SELECT t.id, t.price, a.image, tn.name, tn.content 
              FROM thucdon t 
              LEFT JOIN thucdon_ngonngu tn ON t.id = tn.id_thucdon 
              LEFT JOIN anhthucdon a ON t.id = a.id_menu
              WHERE tn.id_ngonngu = ? AND t.type = ? AND t.id_amthuc = 2
              LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isii", $languageId, $type, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    $menuItems = [];
    while ($row = $result->fetch_assoc()) {
        $menuItems[] = $row;
    }

    return [
        'menuItems' => $menuItems,
        'totalPages' => $totalPages,
        'currentPage' => $page,
        'totalItems' => $totalItems
    ];
}


// Lấy ưu đãi
function getPromotions($language, $active = 1)
{
    global $conn;
    $posts = [];
    $sql = "SELECT u.id, u.created_at AS date, a.image, un.title, un.content
            FROM uudai u
            JOIN anhuudai a ON u.id = a.id_uudai
            JOIN uudai_ngonngu un ON u.id = un.id_uudai
            WHERE a.is_primary = 1 AND un.id_ngonngu = ? AND u.active = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $language, $active);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $posts;
}

// Hàm cắt ngắn nội dung đến độ dài xác định
function truncateContent($content, $maxLength = 100)
{
    // Loại bỏ các thẻ HTML nhưng giữ <p> và <br> để định dạng cơ bản
    $cleanContent = strip_tags($content, '<p><br>');
    // Xóa khoảng trắng thừa
    $cleanContent = preg_replace('/\s+/', ' ', trim($cleanContent));

    if (strlen($cleanContent) <= $maxLength) {
        return $cleanContent;
    }

    // Cắt đến maxLength, đảm bảo không cắt giữa từ
    $truncated = substr($cleanContent, 0, $maxLength);
    $lastSpace = strrpos($truncated, ' ');
    if ($lastSpace !== false) {
        $truncated = substr($truncated, 0, $lastSpace);
    }

    return $truncated . '...';
}


//Lấy ưu đãi
function getPromotionById($language, $id_uudai)
{
    global $conn;

    $sql = "SELECT u.id, u.created_at, a.image, un.title, un.content
            FROM uudai u
            LEFT JOIN anhuudai a ON u.id = a.id_uudai 
            JOIN uudai_ngonngu un ON u.id = un.id_uudai
            WHERE un.id_ngonngu = ? AND u.id = ? AND u.active = 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $language, $id_uudai);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc(); // chỉ trả về 1 bản ghi
}


function getRelatedPromotions($language, $id_uudai, $limit)
{
    global $conn;

    $sql = "SELECT u.id, u.created_at, a.image, un.title, un.content
            FROM uudai u
            LEFT JOIN anhuudai a ON u.id = a.id_uudai 
            JOIN uudai_ngonngu un ON u.id = un.id_uudai
            WHERE un.id_ngonngu = ? AND u.id != ? AND u.active = 1
            GROUP BY u.id
            ORDER BY ABS(u.id - ?) ASC
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $language, $id_uudai, $id_uudai, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC); // Trả về mảng chứa tối đa 6 bản ghi
}

// Lấy tin tức
function getNews($language, $active = 1)
{
    global $conn;
    $posts = [];
    $sql = "SELECT t.id, t.create_at AS date, a.image, tn.title, tn.content
            FROM tintuc t
            JOIN anhtintuc a ON t.id = a.id_tintuc
            JOIN tintuc_ngonngu tn ON t.id = tn.id_tintuc
            WHERE a.is_primary = 1 AND tn.id_ngonngu = ? AND t.active = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $language, $active);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $posts;
}

//Lấy ưu đãi
function getNewById($language, $id_tintuc)
{
    global $conn;

    $sql = "SELECT t.id, t.create_at, a.image, tn.title, tn.content
            FROM tintuc t
            LEFT JOIN anhtintuc a ON t.id = a.id_tintuc 
            JOIN tintuc_ngonngu tn ON t.id = tn.id_tintuc
            WHERE tn.id_ngonngu = ? AND t.id = ? AND t.active = 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $language, $id_tintuc);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc(); // chỉ trả về 1 bản ghi
}


function getRelatedNews($language, $id_tintuc, $limit)
{
    global $conn;

    $sql = "SELECT t.id, t.create_at, a.image, tn.title, tn.content
            FROM tintuc t
            LEFT JOIN anhtintuc a ON t.id = a.id_tintuc 
            JOIN tintuc_ngonngu tn ON t.id = tn.id_tintuc
            WHERE tn.id_ngonngu = ? AND t.id != ? AND t.active = 1
            GROUP BY t.id
            ORDER BY ABS(t.id - ?) ASC
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $language, $id_tintuc, $id_tintuc, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC); // Trả về mảng chứa tối đa 6 bản ghi
}

// Lấy sự kiện đã tổ chức
function getEventOrganized($language, $active = 1)
{
    global $conn;
    $posts = [];
    $sql = "SELECT t.id, t.create_at AS date, a.image, tn.title, tn.content
            FROM sukiendatochuc t
            JOIN anhsukiendatochuc a ON t.id = a.id_sukiendatochuc
            JOIN sukiendatochuc_ngonngu tn ON t.id = tn.id_sukiendatochuc
            WHERE a.is_primary = 1 AND tn.id_ngonngu = ? AND t.active = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $language, $active);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $posts;
}

//Lấy ưu đãi
function getEventOrganizedById($language, $id_sukiendatochuc)
{
    global $conn;

    $sql = "SELECT t.id, t.create_at, a.image, tn.title, tn.content
            FROM sukiendatochuc t
            LEFT JOIN anhsukiendatochuc a ON t.id = a.id_sukiendatochuc 
            JOIN sukiendatochuc_ngonngu tn ON t.id = tn.id_sukiendatochuc
            WHERE tn.id_ngonngu = ? AND t.id = ? AND t.active = 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $language, $id_sukiendatochuc);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc(); // chỉ trả về 1 bản ghi
}


function getRelatedEventOrganized($language, $id_sukiendatochuc, $limit)
{
    global $conn;

    $sql = "SELECT t.id, t.create_at, a.image, tn.title, tn.content
            FROM sukiendatochuc t
            LEFT JOIN anhsukiendatochuc a ON t.id = a.id_sukiendatochuc 
            JOIN sukiendatochuc_ngonngu tn ON t.id = tn.id_sukiendatochuc
            WHERE tn.id_ngonngu = ? AND t.id != ? AND t.active = 1 
            GROUP BY t.id
            ORDER BY ABS(t.id - ?) ASC
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $language, $id_sukiendatochuc, $id_sukiendatochuc, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC); // Trả về mảng chứa tối đa 6 bản ghi
}

function getImagesMenu()
{
    global $conn;
    $images = [];

    $sql = "SELECT id, image
            FROM menu_tiec_cuoi 
            WHERE active = 1
            ORDER BY id";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        // Thêm (append) từng bản ghi vào mảng
        $images[] = [
            'id'    => $row['id'],
            'image' => $row['image']
        ];
    }

    $stmt->close();
    return $images;
}


// Lấy tiện ích cho từng loại phòng
function getRoomAmenities($room_id, $languageId)
{
    global $conn;
    $sql_amenities = "
        SELECT tn.content
        FROM tienich_loaiphong tlp
        JOIN tienich_ngonngu tn ON tlp.id_tienich = tn.id_tienich
        WHERE tlp.id_loaiphong = ? AND tn.id_ngonngu = ?
    ";

    $stmt = $conn->prepare($sql_amenities);
    $stmt->bind_param("ii", $room_id, $languageId);
    $stmt->execute();
    $result = $stmt->get_result();

    $amenities = [];
    while ($row = $result->fetch_assoc()) {
        $amenities[] = $row['content'];
    }
    return $amenities;
}

// Lấy số phòng còn trống
function getAvailableRooms($conn, $room_id)
{
    $sql = "SELECT COUNT(*) as available FROM phongkhachsan WHERE id_loaiphong = ? AND status = 'available'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['available'];
}

function getPriceRange()
{
    global $conn;
    // Câu lệnh SQL để lấy giá min và max, loại bỏ dấu chấm và chuyển thành số
    $sql = "SELECT 
                MIN(CAST(REPLACE(price, '.', '') AS UNSIGNED)) as min_price, 
                MAX(CAST(REPLACE(price, '.', '') AS UNSIGNED)) as max_price 
            FROM loaiphongnghi";

    // Thực thi truy vấn
    $result = $conn->query($sql);

    // Kiểm tra và lấy dữ liệu
    if ($result && $result->num_rows > 0) {
        $price_data = $result->fetch_assoc();
        return [
            'min_price' => $price_data['min_price'] ?? 500000,
            'max_price' => $price_data['max_price'] ?? 3000000
        ];
    }

    // Trả về giá trị mặc định nếu không có dữ liệu
    return [
        'min_price' => 500000,
        'max_price' => 3000000
    ];
}

function getOtherRooms($room_id, $languageId, $limit = 6)
{
    global $conn;
    // Câu truy vấn SQL để lấy danh sách các phòng khác và thông tin giường
    $sql_other_rooms = "
        SELECT 
            lpn.id,
            lpn.quantity,
            lpn.area,
            lpn.price,
            lpnnn.name,
            lpnnn.description,
            GROUP_CONCAT(CONCAT(lg_nn.name, ': ', lg_lp.quantity) SEPARATOR ', ') as bed_info
        FROM loaiphongnghi lpn
        LEFT JOIN loaiphongnghi_ngonngu lpnnn ON lpn.id = lpnnn.id_loaiphongnghi
        LEFT JOIN loaigiuong_loaiphong lg_lp ON lpn.id = lg_lp.id_loaiphongnghi
        LEFT JOIN loaigiuong_ngonngu lg_nn ON lg_lp.id_loaigiuong = lg_nn.id_loaigiuong AND lg_nn.id_ngonngu = ?
        WHERE lpn.id != ? AND (lpnnn.id_ngonngu = ? OR lpnnn.id_ngonngu IS NULL)
        GROUP BY lpn.id
        ORDER BY lpn.price ASC
        LIMIT ?
    ";

    // Chuẩn bị và thực thi truy vấn
    $stmt = $conn->prepare($sql_other_rooms);
    if (!$stmt) {
        // Xử lý lỗi chuẩn bị truy vấn
        error_log("Prepare failed: " . $conn->error);
        return [];
    }

    // Ép kiểu $limit thành số nguyên để đảm bảo an toàn
    $limit = (int)$limit;
    $stmt->bind_param("iiii", $languageId, $room_id, $languageId, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    // Lấy danh sách phòng
    $other_rooms = [];
    while ($row = $result->fetch_assoc()) {
        // Lấy danh sách ảnh cho phòng
        $row['images'] = getImagesForRoom($row['id']);
        $other_rooms[] = $row;
    }

    // Đóng statement
    $stmt->close();

    return $other_rooms;
}
// Hàm lấy đánh giá của phòng với phân trang
function getRoomReviews($id_loaiphong, $page = 1, $limit = 5)
{
    global $conn;
    $reviews = [];
    $offset = ($page - 1) * $limit;

    $sql = "
        SELECT b.id, b.content, b.create_at, b.rate, k.name
        FROM binhluan b
        JOIN khachhang k ON b.id_khachhang = k.id
        JOIN loaiphong_binhluan lpb ON b.id = lpb.id_binhluan
        WHERE lpb.id_loaiphong = ? AND b.active = 1
        ORDER BY b.create_at DESC
        LIMIT ? OFFSET ?
    ";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("iii", $id_loaiphong, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $reviews[] = [
                'id' => $row['id'],
                'content' => $row['content'],
                'create_at' => $row['create_at'],
                'rate' => $row['rate'],
                'name' => $row['name']
            ];
        }

        $result->free();
        $stmt->close();
    } else {
        error_log("Error preparing statement in getRoomReviews: " . $conn->error);
    }

    return $reviews;
}

// Hàm lấy tổng số đánh giá của phòng
function getTotalRoomReviews($id_loaiphong)
{
    global $conn;
    $sql = "
        SELECT COUNT(*) as total
        FROM binhluan b
        JOIN loaiphong_binhluan lpb ON b.id = lpb.id_binhluan
        WHERE lpb.id_loaiphong = ? AND b.active = 1
    ";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id_loaiphong);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total = (int)$row['total'];
        $result->free();
        $stmt->close();
        return $total;
    } else {
        error_log("Error preparing statement in getTotalRoomReviews: " . $conn->error);
    }

    return 0;
}

// Hàm tính điểm đánh giá trung bình của phòng
function calculateRoomAverageRating($id_loaiphong)
{
    global $conn;
    $sql = "
        SELECT AVG(b.rate) as average_rating
        FROM binhluan b
        JOIN loaiphong_binhluan lpb ON b.id = lpb.id_binhluan
        WHERE lpb.id_loaiphong = ? AND b.active = 1
    ";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id_loaiphong);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $average = $row['average_rating'] ? number_format($row['average_rating'], 1) : "0.0";
        $result->free();
        $stmt->close();
        return $average;
    } else {
        error_log("Error executing query in calculateRoomAverageRating: " . $conn->error);
    }

    return "0.0";
}

// Hàm tính phân bố tỷ lệ phần trăm các mức sao của phòng
function calculateRoomRatingBreakdown($id_loaiphong)
{
    global $conn;
    $ratingBreakdown = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    $totalReviews = getTotalRoomReviews($id_loaiphong);

    if ($totalReviews == 0) {
        return array_map(function () {
            return ['count' => 0, 'percentage' => 0];
        }, $ratingBreakdown);
    }

    $sql = "
        SELECT b.rate, COUNT(*) as count
        FROM binhluan b
        JOIN loaiphong_binhluan lpb ON b.id = lpb.id_binhluan
        WHERE lpb.id_loaiphong = ? AND b.active = 1
        GROUP BY b.rate
    ";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id_loaiphong);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $rate = (int)$row['rate'];
            if ($rate >= 1 && $rate <= 5) {
                $percentage = ($row['count'] / $totalReviews) * 100;
                $ratingBreakdown[$rate] = [
                    'count' => (int)$row['count'],
                    'percentage' => round($percentage, 1)
                ];
            }
        }

        $result->free();
        $stmt->close();
    } else {
        error_log("Error preparing statement in calculateRoomRatingBreakdown: " . $conn->error);
    }

    // Điền các mức sao không có đánh giá
    foreach ($ratingBreakdown as $rate => &$data) {
        if (!is_array($data)) {
            $data = ['count' => 0, 'percentage' => 0];
        }
    }

    return $ratingBreakdown;
}

// Hàm chèn bình luận mới cho phòng
function insertRoomComment($id_loaiphong, $name, $email, $content, $rating)
{
    global $conn;

    // Chèn hoặc lấy id_khachhang từ bảng khachhang
    $sql = "INSERT INTO khachhang (name, email) VALUES (?, ?)
            ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $name, $email);
        $result = $stmt->execute();
        $id_khachhang = $conn->insert_id;
        $stmt->close();
        if (!$result) {
            error_log("Error inserting customer in insertRoomComment: " . $conn->error);
            return false;
        }
    } else {
        error_log("Error preparing statement in insertRoomComment (khachhang): " . $conn->error);
        return false;
    }

    // Chèn bình luận vào bảng binhluan
    $create_at = date('Y-m-d H:i:s');
    $active = 1;
    $sql = "INSERT INTO binhluan (content, create_at, rate, active, id_khachhang) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssiii", $content, $create_at, $rating, $active, $id_khachhang);
        $result = $stmt->execute();
        $id_binhluan = $conn->insert_id;
        $stmt->close();
        if (!$result) {
            error_log("Error inserting comment in insertRoomComment: " . $conn->error);
            return false;
        }
    } else {
        error_log("Error preparing statement in insertRoomComment (binhluan): " . $conn->error);
        return false;
    }

    // Chèn vào bảng loaiphong_binhluan
    $sql = "INSERT INTO loaiphong_binhluan (id_binhluan, id_loaiphong) VALUES (?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $id_binhluan, $id_loaiphong);
        $result = $stmt->execute();
        $stmt->close();
        if (!$result) {
            error_log("Error inserting into loaiphong_binhluan: " . $conn->error);
            return false;
        }
        return true;
    } else {
        error_log("Error preparing statement in insertRoomComment (loaiphong_binhluan): " . $conn->error);
        return false;
    }

    return false;
}

function insertRoomBooking($checkin_datetime, $checkout_datetime, $adults, $children, $special_requests, $status, $room_id, $customer_id)
{
    global $conn;
    $sql = "INSERT INTO datphongkhachsan (time_come, time_leave, number_adult, number_children, note, status, created_at, id_phong, id_khachhang) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssiissii", $checkin_datetime, $checkout_datetime, $adults, $children, $special_requests, $status, $room_id, $customer_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    return false;
}

// function getActiveTopicsWithVideo($languageId) {
//     global $conn;
//     $topics = [];

//     // Lấy các chủ đề active từ bảng thuvien
//     $sql_topics = "SELECT id, IF(? = 1, topic, topic_ngonngu) AS topic_display 
//                    FROM thuvien 
//                    WHERE active = 1 
//                    ORDER BY id";
//     $stmt_topics = $conn->prepare($sql_topics);
//     $stmt_topics->bind_param("i", $languageId);
//     $stmt_topics->execute();
//     $result_topics = $stmt_topics->get_result();

//     if ($result_topics->num_rows > 0) {
//         while ($row = $result_topics->fetch_assoc()) {
//             $topics[] = ['id' => $row['id'], 'topic_display' => $row['topic_display']];
//         }
//     }

//     return $topics;
// }

function getImagesAndVideos($languageId = 1)
{
    global $conn;
    $image_tables = [
        'anhtintuc' => 'image',
        'anhtongquat' => 'image',
        'anhuudai' => 'image',
        'anhhoitruong' => 'image',
        'anhbar' => 'image',
        'anhnhahang' => 'image',
        'anhdichvu' => 'image',
        'anhkhachsan' => 'image',
        'anhsukiendatochuc' => 'image',
        'anhsukien' => 'image',
        'anhthucdon' => 'image'
    ];

    // Mảng lưu tất cả hình ảnh theo id_topic
    $all_images = [];

    // Lấy hình ảnh từ các bảng
    foreach ($image_tables as $table => $image_column) {
        $sql = "SELECT id_topic, $image_column FROM $table";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $all_images[$row['id_topic']][] = $row[$image_column];
            }
        }
    }

    // Lấy video từ bảng video
    $videos = [];
    $sql_videos = "SELECT video FROM video";
    $result_videos = $conn->query($sql_videos);
    if ($result_videos && $result_videos->num_rows > 0) {
        while ($row = $result_videos->fetch_assoc()) {
            $videos[] = $row['video'];
        }
    }

    // Lấy các chủ đề active từ bảng thuvien
    $topics = [];
    $sql_topics = "SELECT id, IF(? = 1, topic, topic_ngonngu) AS topic_display 
                   FROM thuvien 
                   WHERE active = 1 
                   ORDER BY id";
    $stmt_topics = $conn->prepare($sql_topics);
    $stmt_topics->bind_param("i", $languageId);
    $stmt_topics->execute();
    $result_topics = $stmt_topics->get_result();

    if ($result_topics->num_rows > 0) {
        while ($row = $result_topics->fetch_assoc()) {
            $topics[] = ['id' => $row['id'], 'topic_display' => $row['topic_display']];
        }
    }

    // Trả về dữ liệu
    return [
        'images' => $all_images,
        'videos' => $videos,
        'topics' => $topics
    ];
}

function getAllRoomTypesWithRandomImage($languageId = 1)
{
    global $conn;
    $roomTypes = [];

    // Truy vấn lấy tất cả loại phòng và một ảnh ngẫu nhiên
    $sql = "SELECT lpn.id, lpn.area, lpn.price, lpn_nn.name, lpn_nn.description,
                   (SELECT ak.image 
                    FROM anhkhachsan ak 
                    WHERE ak.id_loaiphongnghi = lpn.id AND ak.active = 1 
                    ORDER BY RAND() 
                    LIMIT 1) AS image
            FROM loaiphongnghi lpn
            JOIN loaiphongnghi_ngonngu lpn_nn ON lpn.id = lpn_nn.id_loaiphongnghi
            WHERE lpn_nn.id_ngonngu = ?";

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $languageId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            // Lấy thông tin loại giường cho loại phòng
            $bedTypes = getBedTypesForRoom($row['id'], $languageId);

            $roomTypes[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'price' => $row['price'],
                'area' => $row['area'],
                'image' => $row['image'] ?? 'default_image.jpg', // Ảnh mặc định nếu không có ảnh
                'bedTypes' => $bedTypes
            ];
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Lỗi chuẩn bị truy vấn getAllRoomTypesWithRandomImage: " . mysqli_error($conn));
    }

    return $roomTypes;
}
function getRooms($conn)
{
    $rooms_sql = "
        SELECT 
            p.id,
            p.room_number,
            p.status,
            p.id_loaiphong,
            p.phone,
            lpnnn.name as room_type_name,
            lpn.price,
            lpn.area,
            lpn.quantity
        FROM phongkhachsan p
        LEFT JOIN loaiphongnghi lpn ON p.id_loaiphong = lpn.id
        LEFT JOIN loaiphongnghi_ngonngu lpnnn ON lpn.id = lpnnn.id_loaiphongnghi AND lpnnn.id_ngonngu = 1
        ORDER BY p.room_number
    ";
    $rooms_result = $conn->query($rooms_sql);
    $rooms = [];
    while ($row = $rooms_result->fetch_assoc()) {
        $rooms[] = $row;
    }
    return $rooms;
}

function getRoomTypes1($conn)
{
    $room_types_sql = "
        SELECT 
            lpn.id, 
            lpn.price, 
            lpn.quantity, 
            lpn.area,
            (SELECT COUNT(*) FROM anhkhachsan WHERE id_loaiphongnghi = lpn.id) as image_count
        FROM loaiphongnghi lpn
        ORDER BY lpn.id
    ";
    $room_types_result = $conn->query($room_types_sql);
    $room_types = [];

    while ($row = $room_types_result->fetch_assoc()) {
        $lang_sql = "
            SELECT id_ngonngu, name, description 
            FROM loaiphongnghi_ngonngu 
            WHERE id_loaiphongnghi = ? AND id_ngonngu IN (1, 2)
        ";
        $lang_stmt = $conn->prepare($lang_sql);
        $lang_stmt->bind_param("i", $row['id']);
        $lang_stmt->execute();
        $lang_result = $lang_stmt->get_result();

        $row['languages'] = [
            1 => ['name' => '', 'description' => ''],
            2 => ['name' => '', 'description' => ''],
        ];

        while ($lang_row = $lang_result->fetch_assoc()) {
            $row['languages'][$lang_row['id_ngonngu']] = [
                'name' => $lang_row['name'],
                'description' => $lang_row['description']
            ];
        }

        $row['images'] = [];
        $images_sql = "SELECT id, image FROM anhkhachsan WHERE id_loaiphongnghi = ?";
        $images_stmt = $conn->prepare($images_sql);
        $images_stmt->bind_param("i", $row['id']);
        $images_stmt->execute();
        $images_result = $images_stmt->get_result();
        while ($img_row = $images_result->fetch_assoc()) {
            $row['images'][] = $img_row;
        }

        $room_types[] = $row;
    }

    return $room_types;
}

function getStats($conn)
{
    $stats_sql = "
        SELECT 
            COUNT(*) as total_rooms,
            SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as available_rooms,
            SUM(CASE WHEN status = 'reserved' THEN 1 ELSE 0 END) as reserved_rooms,
            SUM(CASE WHEN status = 'maintenance' THEN 1 ELSE 0 END) as maintenance_rooms,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_rooms
        FROM phongkhachsan
    ";
    $stats_result = $conn->query($stats_sql);
    return $stats_result->fetch_assoc();
}

function getRoomTypeStats($conn)
{
    $room_type_stats_sql = "
        SELECT 
            lpn.id,
            lpnnn.name,
            lpn.quantity as total_quantity,
            COUNT(p.id) as actual_rooms,
            SUM(CASE WHEN p.status = 'available' THEN 1 ELSE 0 END) as available_count
        FROM loaiphongnghi lpn
        LEFT JOIN loaiphongnghi_ngonngu lpnnn ON lpn.id = lpnnn.id_loaiphongnghi AND lpnnn.id_ngonngu = 1
        LEFT JOIN phongkhachsan p ON lpn.id = p.id_loaiphong
        GROUP BY lpn.id, lpnnn.name, lpn.quantity
    ";
    $room_type_stats_result = $conn->query($room_type_stats_sql);
    $room_type_stats = [];
    while ($row = $room_type_stats_result->fetch_assoc()) {
        $room_type_stats[] = $row;
    }
    return $room_type_stats;
}

function addRoom($conn, $room_number, $id_loaiphong, $status)
{
    $conn->begin_transaction();
    try {
        $sql = "INSERT INTO phongkhachsan (room_number, status, id_loaiphong) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $room_number, $status, $id_loaiphong);
        $stmt->execute();

        $update_quantity_sql = "UPDATE loaiphongnghi SET quantity = quantity + 1 WHERE id = ?";
        $update_stmt = $conn->prepare($update_quantity_sql);
        $update_stmt->bind_param("i", $id_loaiphong);
        $update_stmt->execute();

        $conn->commit();
        return ['status' => 'success', 'message' => 'Thêm phòng thành công'];
    } catch (Exception $e) {
        $conn->rollback();
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}

function updateRoom($conn, $id, $room_number, $id_loaiphong, $status, $phone)
{
    $conn->begin_transaction();
    try {
        $sql = "UPDATE phongkhachsan SET room_number = ?, status = ?, id_loaiphong = ?, phone = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisi", $room_number, $status, $id_loaiphong, $phone, $id);
        $stmt->execute();

        $conn->commit();
        return ['status' => 'success', 'message' => 'Cập nhật phòng thành công'];
    } catch (Exception $e) {
        $conn->rollback();
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}

function deleteRoom($conn, $id)
{
    $conn->begin_transaction();
    try {
        $sql = "DELETE FROM phongkhachsan WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $conn->commit();
        return ['status' => 'success', 'message' => 'Xóa phòng thành công'];
    } catch (Exception $e) {
        $conn->rollback();
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}

function addRoomType($conn, $name_vi, $name_en, $description_vi, $description_en, $quantity, $area, $price, $images)
{
    $conn->begin_transaction();
    try {
        $sql = "INSERT INTO loaiphongnghi (quantity, area, price) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $quantity, $area, $price);
        $stmt->execute();

        $room_type_id = $conn->insert_id;

        $lang_sql = "INSERT INTO loaiphongnghi_ngonngu (id_loaiphongnghi, id_ngonngu, name, description) VALUES (?, 1, ?, ?)";
        $lang_stmt = $conn->prepare($lang_sql);
        $lang_stmt->bind_param("iss", $room_type_id, $name_vi, $description_vi);
        $lang_stmt->execute();

        $lang_sql = "INSERT INTO loaiphongnghi_ngonngu (id_loaiphongnghi, id_ngonngu, name, description) VALUES (?, 2, ?, ?)";
        $lang_stmt = $conn->prepare($lang_sql);
        $lang_stmt->bind_param("iss", $room_type_id, $name_en, $description_en);
        $lang_stmt->execute();

        $upload_dir = "../view/img/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        for ($i = 0; $i < min(4, count($images['name'])); $i++) {
            if ($images['size'][$i] > 0) {
                $file_name = uniqid() . '_' . basename($images['name'][$i]);
                $target_path = $upload_dir . $file_name;

                if (move_uploaded_file($images['tmp_name'][$i], $target_path)) {
                    $img_sql = "INSERT INTO anhkhachsan (image, active, created_at, id_topic, id_loaiphongnghi) VALUES (?, 1, NOW(), 2, ?)";
                    $img_stmt = $conn->prepare($img_sql);
                    $img_stmt->bind_param("si", $file_name, $room_type_id);
                    $img_stmt->execute();
                }
            }
        }

        $conn->commit();
        return ['status' => 'success', 'message' => 'Thêm loại phòng thành công'];
    } catch (Exception $e) {
        $conn->rollback();
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}

function updateRoomType($conn, $id, $name_vi, $name_en, $description_vi, $description_en, $quantity, $area, $price, $delete_images, $new_images)
{
    $conn->begin_transaction();
    try {
        $sql = "UPDATE loaiphongnghi SET quantity = ?, area = ?, price = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisi", $quantity, $area, $price, $id);
        $stmt->execute();

        $lang_sql = "UPDATE loaiphongnghi_ngonngu SET name = ?, description = ? WHERE id_loaiphongnghi = ? AND id_ngonngu = 1";
        $lang_stmt = $conn->prepare($lang_sql);
        $lang_stmt->bind_param("ssi", $name_vi, $description_vi, $id);
        $lang_stmt->execute();

        $lang_sql = "UPDATE loaiphongnghi_ngonngu SET name = ?, description = ? WHERE id_loaiphongnghi = ? AND id_ngonngu = 2";
        $lang_stmt = $conn->prepare($lang_sql);
        $lang_stmt->bind_param("ssi", $name_en, $description_en, $id);
        $lang_stmt->execute();

        if (!empty($delete_images)) {
            $placeholders = implode(',', array_fill(0, count($delete_images), '?'));
            $types = str_repeat('i', count($delete_images));

            $delete_sql = "DELETE FROM anhkhachsan WHERE id IN ($placeholders)";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param($types, ...$delete_images);
            $delete_stmt->execute();
        }

        if (!empty($new_images['name'][0])) {
            $upload_dir = "../view/img/";
            for ($i = 0; $i < min(4, count($new_images['name'])); $i++) {
                if ($new_images['size'][$i] > 0) {
                    $file_name = uniqid() . '_' . basename($new_images['name'][$i]);
                    $target_path = $upload_dir . $file_name;

                    if (move_uploaded_file($new_images['tmp_name'][$i], $target_path)) {
                        $img_sql = "INSERT INTO anhkhachsan (image, active, created_at, id_topic, id_loaiphongnghi) VALUES (?, 1, NOW(), 2, ?)";
                        $img_stmt = $conn->prepare($img_sql);
                        $img_stmt->bind_param("si", $file_name, $id);
                        $img_stmt->execute();
                    }
                }
            }
        }

        $conn->commit();
        return ['status' => 'success', 'message' => 'Cập nhật loại phòng thành công'];
    } catch (Exception $e) {
        $conn->rollback();
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}

function deleteRoomType($conn, $id)
{
    $conn->begin_transaction();
    try {
        $delete_lang_sql = "DELETE FROM loaiphongnghi_ngonngu WHERE id_loaiphongnghi = ?";
        $delete_lang_stmt = $conn->prepare($delete_lang_sql);
        $delete_lang_stmt->bind_param("i", $id);
        $delete_lang_stmt->execute();

        $delete_images_sql = "DELETE FROM anhkhachsan WHERE id_loaiphongnghi = ?";
        $delete_images_stmt = $conn->prepare($delete_images_sql);
        $delete_images_stmt->bind_param("i", $id);
        $delete_images_stmt->execute();

        $delete_sql = "DELETE FROM loaiphongnghi WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $id);
        $delete_stmt->execute();

        $conn->commit();
        return ['status' => 'success', 'message' => 'Xóa loại phòng thành công'];
    } catch (Exception $e) {
        $conn->rollback();
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}

function checkUserLogin($username)
{
    global $conn;
    $sql = "SELECT * FROM taikhoan WHERE username = ? ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function passBrypt($username)
{
    global $conn;
    $sql = "SELECT password FROM taikhoan WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $stmt->close();
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['password'];
    }
    return null;
}

function checkEmailExist($email)
{
    global $conn;
    $sql = "SELECT * FROM taikhoan WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    return mysqli_fetch_assoc($result);
}

function storeResetToken($email, $token)
{
    global $conn;
    $expires_at = date("Y-m-d H:i:s", strtotime("+5 minutes"));
    $sql = "UPDATE taikhoan SET reset_token = ?, reset_expires = ? WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    $stmt->bind_param("sss", $token, $expires_at, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function getUserByToken($token)
{
    global $conn;
    $sql = "SELECT * FROM taikhoan WHERE reset_token= ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function changePassword($passBrypt, $email)
{
    global $conn;
    $sql = "UPDATE taikhoan SET password = ?, reset_token = NULL, reset_expires = NULL WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $passBrypt, $email);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}


function bulkUpdateRoomStatus($conn, $status, $room_ids)
{
    $conn->begin_transaction();
    try {
        $update_sql = "UPDATE phongkhachsan SET status = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);

        $deleted_bookings = 0;
        foreach ($room_ids as $room_id) {
            $update_stmt->bind_param("si", $status, $room_id);
            if (!$update_stmt->execute()) {
                throw new Exception("Lỗi khi cập nhật trạng thái phòng ID {$room_id}: " . $conn->error);
            }

            if ($status === 'available') {
                $delete_booking_sql = "DELETE FROM datphongkhachsan 
                                     WHERE id_phong = ? 
                                     AND status IN ('pending', 'confirmed', 'checked_in')";
                $delete_booking_stmt = $conn->prepare($delete_booking_sql);
                $delete_booking_stmt->bind_param("i", $room_id);
                if ($delete_booking_stmt->execute()) {
                    $deleted_bookings += $delete_booking_stmt->affected_rows;
                }
            }
        }

        $conn->commit();
        return [
            'status' => 'success',
            'message' => "Cập nhật trạng thái cho " . count($room_ids) . " phòng thành công!" .
                ($deleted_bookings > 0 ? " Đã xóa {$deleted_bookings} bản ghi đặt phòng." : "")
        ];
    } catch (Exception $e) {
        $conn->rollback();
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}
// quanlytour (liem)

// function updateTour($conn, $id_dichvu, $title_vi, $title_en, $price)
// {
//     // Kiểm tra và xử lý giá
//     if (preg_match('/^\d+[.,]?\d*$/', $price)) {
//         // Nếu giá trị là số (có thể chứa dấu phẩy hoặc chấm)
//         $price_value = (float)str_replace([',', '.'], '', $price);
//         $sql = "UPDATE dichvu SET price = ? WHERE id = ?";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("di", $price_value, $id_dichvu);
//     } else {
//         // Nếu giá trị là chuỗi bất kỳ (bao gồm "Liên hệ", "Miễn phí", v.v.)
//         $sql = "UPDATE dichvu SET price = ? WHERE id = ?";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("si", $price, $id_dichvu);
//     }
//     $stmt->execute();

//     // Cập nhật tiêu đề tiếng Việt
//     $sql = "SELECT COUNT(*) as count FROM dichvu_ngonngu WHERE id_dichvu = ? AND id_ngonngu = 1";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("i", $id_dichvu);
//     $stmt->execute();
//     $check_row = $stmt->get_result()->fetch_assoc();

//     if ($check_row['count'] > 0) {
//         $sql = "UPDATE dichvu_ngonngu SET title = ? WHERE id_dichvu = ? AND id_ngonngu = 1";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("si", $title_vi, $id_dichvu);
//     } else {
//         $sql = "INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title) VALUES (?, 1, ?)";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("is", $id_dichvu, $title_vi);
//     }
//     $stmt->execute();

//     // Cập nhật tiêu đề tiếng Anh
//     $sql = "SELECT COUNT(*) as count FROM dichvu_ngonngu WHERE id_dichvu = ? AND id_ngonngu = 2";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("i", $id_dichvu);
//     $stmt->execute();
//     $check_row = $stmt->get_result()->fetch_assoc();

//     if ($check_row['count'] > 0) {
//         $sql = "UPDATE dichvu_ngonngu SET title = ? WHERE id_dichvu = ? AND id_ngonngu = 2";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("si", $title_en, $id_dichvu);
//     } else {
//         $sql = "INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title) VALUES (?, 2, ?)";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("is", $id_dichvu, $title_en);
//     }
//     $stmt->execute();

//     return ['success' => true, 'message' => 'Cập nhật thông tin tour thành công!'];
// }

function addTourImage($conn, $id_dichvu, $id_topic, $is_primary, $images)
{
    // Kiểm tra id_dichvu và id_topic
    $check_dichvu = $conn->prepare("SELECT id FROM dichvu WHERE id = ?");
    $check_dichvu->bind_param("i", $id_dichvu);
    $check_dichvu->execute();
    if ($check_dichvu->get_result()->num_rows === 0) {
        return ['success' => false, 'message' => 'ID dịch vụ không hợp lệ'];
    }

    $check_topic = $conn->prepare("SELECT id FROM thuvien WHERE id = ?");
    $check_topic->bind_param("i", $id_topic);
    $check_topic->execute();
    if ($check_topic->get_result()->num_rows === 0) {
        return ['success' => false, 'message' => 'ID topic không hợp lệ'];
    }

    // Kiểm tra xem đã có ảnh chính cho id_dichvu này chưa
    if ($is_primary) {
        $check_primary = $conn->prepare("SELECT id FROM anhdichvu WHERE id_dichvu = ? AND is_primary = 1");
        $check_primary->bind_param("i", $id_dichvu);
        $check_primary->execute();
        if ($check_primary->get_result()->num_rows > 0) {
            return ['success' => false, 'message' => 'Chỉ được phép có một ảnh chính cho mỗi dịch vụ!'];
        }
    }

    if (!empty($images['name'][0])) {
        $total_files = count($images['name']);
        $success_count = 0;

        for ($i = 0; $i < $total_files; $i++) {
            if ($images['error'][$i] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $images['name'][$i],
                    'type' => $images['type'][$i],
                    'tmp_name' => $images['tmp_name'][$i],
                    'error' => $images['error'][$i],
                    'size' => $images['size'][$i]
                ];

                $imageName = uploadImage($file);
                if ($imageName) {
                    // Nếu ảnh được chọn là ảnh chính, đặt các ảnh khác thành không chính
                    if ($is_primary && $success_count === 0) {
                        $reset_primary = $conn->prepare("UPDATE anhdichvu SET is_primary = 0 WHERE id_dichvu = ?");
                        $reset_primary->bind_param("i", $id_dichvu);
                        $reset_primary->execute();
                    }

                    $stmt = $conn->prepare("INSERT INTO anhdichvu (image, is_primary, id_dichvu, id_topic) VALUES (?, ?, ?, ?)");
                    $current_is_primary = ($is_primary && $success_count === 0) ? 1 : 0;
                    $stmt->bind_param("siii", $imageName, $current_is_primary, $id_dichvu, $id_topic);
                    if ($stmt->execute()) {
                        $success_count++;
                    } else {
                        error_log("SQL Error: " . $stmt->error);
                    }
                    $stmt->close();
                }
            }
        }

        if ($success_count > 0) {
            return ['success' => true, 'message' => "Thêm $success_count ảnh thành công!"];
        } else {
            return ['success' => false, 'message' => 'Không có ảnh nào được thêm thành công'];
        }
    } else {
        return ['success' => false, 'message' => 'Vui lòng chọn ít nhất một file ảnh'];
    }
}


function deleteTourImage($conn, $id_image, $image_name)
{
    $upload_dir = '../../view/img/';
    $file_path = $upload_dir . $image_name;

    // Xóa file khỏi server
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // Xóa bản ghi khỏi cơ sở dữ liệu
    $stmt = $conn->prepare("DELETE FROM anhdichvu WHERE id = ?");
    $stmt->bind_param("i", $id_image);
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Xóa ảnh thành công!'];
    } else {
        return ['success' => false, 'message' => 'Lỗi khi xóa ảnh: ' . $conn->error];
    }
}

function updateTourDescription($conn, $id_dichvu, $content_vi, $content_en)
{
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
        return ['success' => false, 'message' => 'Lỗi khi cập nhật/thêm mô tả tiếng Việt: ' . $conn->error];
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
        return ['success' => true, 'message' => 'Cập nhật mô tả tour thành công!'];
    } else {
        return ['success' => false, 'message' => 'Lỗi khi cập nhật/thêm mô tả tiếng Anh: ' . $conn->error];
    }
}


function getFeaturesByLanguage($language_id, $page = 'dichvu')
{
    global $conn;
    $features = [];

    $sql = "
        SELECT t.id as id_tienich, t.icon, tn.title, tn.content, td.page 
        FROM tienich t 
        LEFT JOIN tienich_ngonngu tn ON t.id = tn.id_tienich 
        LEFT JOIN tienichdichvu td ON t.id = td.id_tienich 
        WHERE tn.id_ngonngu = ? AND td.page = ? AND t.active = 1
        ORDER BY t.id
    ";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("is", $language_id, $page);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $features[] = $row;
    }

    $stmt->close();
    return $features;
}

function getServices($language_id, $type = 'dichvu')
{
    global $conn;
    $services = [];

    $sql = "
        SELECT dn.id_dichvu,
               dn.title,
               dn.content,
               d.price,
               a.image
        FROM dichvu d
        LEFT JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu
        LEFT JOIN anhdichvu a       ON d.id = a.id_dichvu AND a.is_primary = 1
        WHERE dn.id_ngonngu = ? AND d.type = ?
        ORDER BY dn.id_dichvu
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new RuntimeException('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param('is', $language_id, $type);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }

    $stmt->close();
    return $services;
}

function getToursByLanguage($language_id, $type = null)
{
    global $conn;
    $tours = [];

    $sql = "
        SELECT dn.id_dichvu, dn.title, dn.content, a.image 
        FROM dichvu d
        LEFT JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu 
        LEFT JOIN anhdichvu a ON d.id = a.id_dichvu AND a.is_primary = 1 
        WHERE dn.id_ngonngu = ? 
    ";

    if ($type !== null) {
        $sql .= " AND d.type = ?";
    }
    $sql .= " ORDER BY dn.id_dichvu";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new RuntimeException("Prepare failed: " . $conn->error);
    }
    if ($type !== null) {
        $stmt->bind_param("is", $language_id, $type);
    } else {
        $stmt->bind_param("i", $language_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $tours[] = $row;
    }

    $stmt->close();
    return $tours;
}

// Lấy dữ liệu cho view
function getTourData($id_dichvu, $id_ngonngu)
{
    global $conn;
    $data = [
        'tours' => [],
        'selected_tour' => null,
        'images' => [],
        'tour_description' => null
    ];

    // Lấy danh sách dịch vụ
    $sql = "SELECT d.id, dn.title, d.price 
            FROM dichvu d 
            JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu 
            WHERE dn.id_ngonngu = ?
            ORDER BY d.type = 'tour' DESC, d.id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_ngonngu);
    $stmt->execute();
    $data['tours'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if ($id_dichvu > 0) {
        // Lấy thông tin dịch vụ được chọn
        $sql = "SELECT d.id, dn.title, d.price 
                FROM dichvu d 
                JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu 
                WHERE d.id = ? AND dn.id_ngonngu = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_dichvu, $id_ngonngu);
        $stmt->execute();
        $data['selected_tour'] = $stmt->get_result()->fetch_assoc();

        // Lấy danh sách ảnh
        $sql = "SELECT id, image, is_primary FROM anhdichvu WHERE id_dichvu = ? AND id_topic = ?";
        $stmt = $conn->prepare($sql);
        $id_topic = 3;
        $stmt->bind_param("ii", $id_dichvu, $id_topic);
        $stmt->execute();
        $data['images'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Lấy mô tả dịch vụ
        $sql = "SELECT content 
                FROM motatour 
                WHERE id_dichvu = ? AND id_ngonngu = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_dichvu, $id_ngonngu);
        $stmt->execute();
        $result = $stmt->get_result();
        $data['tour_description'] = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    return $data;
}

function getRandomItems($languageId, $excludeId)
{
    global $conn;
    $results = [
        'tours' => [],
        'services' => []
    ];

    // Truy vấn chung (dùng type khác nhau và LIMIT tương ứng)
    $types = [
        'tour' => 3,
        'dichvu' => 2
    ];

    foreach ($types as $type => $limit) {
        $sql = "
            SELECT dn.id_dichvu, dn.title, a.image 
            FROM dichvu d
            LEFT JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu 
            LEFT JOIN anhdichvu a ON d.id = a.id_dichvu AND a.is_primary = 1 
            WHERE dn.id_ngonngu = ? AND d.type = ? AND dn.id_dichvu != ?
            ORDER BY RAND()
            LIMIT ?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ssii", $languageId, $type, $excludeId, $limit);
        $stmt->execute();

        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $results[$type === 'tour' ? 'tours' : 'services'][] = $row;
        }

        $stmt->close();
    }

    return $results;
}

function getServiceContentById($id_dichvu, $languageId)
{
    global $conn;
    $sql = "SELECT title, content 
            FROM dichvu_ngonngu 
            WHERE id_dichvu = ? AND id_ngonngu = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ii", $id_dichvu, $languageId);
    $stmt->execute();

    $result = $stmt->get_result();
    $tour = $result->fetch_assoc();

    $stmt->close();
    return $tour ?: null;  // Trả về null nếu không có dữ liệu
}

function getServices1()
{
    global $conn;
    $services_query = "
        SELECT 
            d.id as id_dichvu, 
            dn.title as title_vi, 
            dn.content as content_vi,
            (SELECT title FROM dichvu_ngonngu WHERE id_dichvu = d.id AND id_ngonngu = 2) as title_en,
            (SELECT content FROM dichvu_ngonngu WHERE id_dichvu = d.id AND id_ngonngu = 2) as content_en,
            a.image, 
            d.price, 
            d.icon
        FROM dichvu d
        LEFT JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu
        LEFT JOIN anhdichvu a ON d.id = a.id_dichvu AND a.is_primary = 1
        WHERE dn.id_ngonngu = 1 AND d.type = 'dichvu'
        ORDER BY dn.id_dichvu
    ";

    $result = mysqli_query($conn, $services_query);

    if (!$result) {
        error_log("Error in getServices(): " . mysqli_error($conn));
        return false;
    }

    $services = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $services[] = $row;
    }

    return $services;
}

function getTours()
{
    global $conn;
    $tours_query = "
        SELECT 
            d.id as id_dichvu, 
            dn.title as title_vi, 
            dn.content as content_vi,
            (SELECT title FROM dichvu_ngonngu WHERE id_dichvu = d.id AND id_ngonngu = 2) as title_en,
            (SELECT content FROM dichvu_ngonngu WHERE id_dichvu = d.id AND id_ngonngu = 2) as content_en,
            a.image, 
            d.price, 
            d.icon
        FROM dichvu d
        LEFT JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu
        LEFT JOIN anhdichvu a ON d.id = a.id_dichvu AND a.is_primary = 1
        WHERE dn.id_ngonngu = 1 AND d.type = 'tour'
        ORDER BY dn.id_dichvu
    ";

    $result = mysqli_query($conn, $tours_query);

    if (!$result) {
        error_log("Error in getTours(): " . mysqli_error($conn));
        return false;
    }

    $tours = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $tours[] = $row;
    }

    return $tours;
}
// function getServices1($languageId = 1) {
//     global $conn;
//     $services = [];

//     $sql = "SELECT d.id, dn.title, d.type 
//             FROM dichvu d 
//             JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu 
//             WHERE dn.id_ngonngu = ? 
//             ORDER BY d.type, dn.title";
//     $stmt = mysqli_prepare($conn, $sql);
//     if ($stmt) {
//         mysqli_stmt_bind_param($stmt, "i", $languageId);
//         mysqli_stmt_execute($stmt);
//         $result = mysqli_stmt_get_result($stmt);

//         while ($row = mysqli_fetch_assoc($result)) {
//             $services[] = [
//                 'id' => $row['id'],
//                 'title' => $row['title'],
//                 'type' => $row['type']
//             ];
//         }
//         mysqli_stmt_close($stmt);
//     } else {
//         error_log("Lỗi chuẩn bị truy vấn getServices: " . mysqli_error($conn));
//     }

//     return $services;
// }

function getTourMenusIfApplicable($id_dichvu, $languageId)
{
    global $conn;
    $menus = [];

    // Bước 1: Lấy type của dịch vụ
    $sql_type = "SELECT type FROM dichvu WHERE id = ?";
    $stmt_type = $conn->prepare($sql_type);
    if (!$stmt_type) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt_type->bind_param("i", $id_dichvu);
    $stmt_type->execute();
    $result_type = $stmt_type->get_result();
    $dichvu_type = $result_type->fetch_assoc()['type'] ?? null;
    $stmt_type->close();

    // Bước 2: Nếu là 'tour' thì lấy menu
    if ($dichvu_type === 'tour') {
        $sql_menu = "SELECT td.id, tdn.title, tdn.content , td.type
                     FROM thucdon_tour td
                     LEFT JOIN thucdontour_ngonngu tdn ON td.id = tdn.id_menu 
                     WHERE tdn.id_ngonngu = ? AND td.type = 'tour'";

        $stmt_menu = $conn->prepare($sql_menu);
        if (!$stmt_menu) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt_menu->bind_param("i", $languageId);
        $stmt_menu->execute();
        $result_menu = $stmt_menu->get_result();

        while ($row = $result_menu->fetch_assoc()) {
            $menus[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'content' => $row['content'],
                'type' => $row['type']
            ];
        }

        $stmt_menu->close();
    }

    return $menus;
}

// Hàm lấy đánh giá của phòng với phân trang
function getServiceReviews($id_type, $page = 1, $limit = 5)
{
    global $conn;
    $reviews = [];
    $offset = ($page - 1) * $limit;

    $sql = "
        SELECT b.id, b.content, b.create_at, b.rate, k.name
        FROM binhluan b
        JOIN khachhang k ON b.id_khachhang = k.id
        JOIN binhluan_dichvu bldv ON b.id = bldv.id_binhluan
        WHERE bldv.id_dichvu = ? AND b.active = 1
        ORDER BY b.create_at DESC
        LIMIT ? OFFSET ?
    ";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("iii", $id_type, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $reviews[] = [
                'id' => $row['id'],
                'content' => $row['content'],
                'create_at' => $row['create_at'],
                'rate' => $row['rate'],
                'name' => $row['name']
            ];
        }

        $result->free();
        $stmt->close();
    } else {
        error_log("Error preparing statement in getRoomReviews: " . $conn->error);
    }

    return $reviews;
}

// Hàm lấy tổng số đánh giá của phòng
function getTotalServiceReviews($id_type)
{
    global $conn;
    $sql = "
        SELECT COUNT(*) as total
        FROM binhluan b
        JOIN binhluan_dichvu bldv ON b.id = bldv.id_binhluan
        WHERE bldv.id_dichvu = ? AND b.active = 1
    ";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id_type);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total = (int)$row['total'];
        $result->free();
        $stmt->close();
        return $total;
    } else {
        error_log("Error preparing statement in getTotalRoomReviews: " . $conn->error);
    }

    return 0;
}

// Hàm tính điểm đánh giá trung bình của phòng
function calculateServiceAverageRating($id_type)
{
    global $conn;
    $sql = "
        SELECT AVG(b.rate) as average_rating
        FROM binhluan b
        JOIN binhluan_dichvu bldv ON b.id = bldv.id_binhluan
        WHERE bldv.id_dichvu = ? AND b.active = 1
    ";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id_type);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $average = $row['average_rating'] ? number_format($row['average_rating'], 1) : "0.0";
        $result->free();
        $stmt->close();
        return $average;
    } else {
        error_log("Error executing query in calculateRoomAverageRating: " . $conn->error);
    }

    return "0.0";
}

// Hàm tính phân bố tỷ lệ phần trăm các mức sao của phòng
function calculateServiceRatingBreakdown($id_type)
{
    global $conn;
    $ratingBreakdown = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    $totalReviews = getTotalServiceReviews($id_type);

    if ($totalReviews == 0) {
        return array_map(function () {
            return ['count' => 0, 'percentage' => 0];
        }, $ratingBreakdown);
    }

    $sql = "
        SELECT b.rate, COUNT(*) as count
        FROM binhluan b
        JOIN binhluan_dichvu bldv ON b.id = bldv.id_binhluan
        WHERE bldv.id_dichvu = ? AND b.active = 1
        GROUP BY b.rate
    ";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id_type);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $rate = (int)$row['rate'];
            if ($rate >= 1 && $rate <= 5) {
                $percentage = ($row['count'] / $totalReviews) * 100;
                $ratingBreakdown[$rate] = [
                    'count' => (int)$row['count'],
                    'percentage' => round($percentage, 1)
                ];
            }
        }

        $result->free();
        $stmt->close();
    } else {
        error_log("Error preparing statement in calculateRoomRatingBreakdown: " . $conn->error);
    }

    // Điền các mức sao không có đánh giá
    foreach ($ratingBreakdown as $rate => &$data) {
        if (!is_array($data)) {
            $data = ['count' => 0, 'percentage' => 0];
        }
    }

    return $ratingBreakdown;
}

// Hàm chèn bình luận mới cho phòng
function insertServiceComment($id_type, $name, $email, $content, $rating)
{
    global $conn;

    // Chèn hoặc lấy id_khachhang từ bảng khachhang
    $sql = "INSERT INTO khachhang (name, email) VALUES (?, ?)
            ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $name, $email);
        $result = $stmt->execute();
        $id_khachhang = $conn->insert_id;
        $stmt->close();
        if (!$result) {
            error_log("Error inserting customer in insertRoomComment: " . $conn->error);
            return false;
        }
    } else {
        error_log("Error preparing statement in insertRoomComment (khachhang): " . $conn->error);
        return false;
    }

    // Chèn bình luận vào bảng binhluan
    $create_at = date('Y-m-d H:i:s');
    $active = 1;
    $sql = "INSERT INTO binhluan (content, create_at, rate, active, id_khachhang) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssiii", $content, $create_at, $rating, $active, $id_khachhang);
        $result = $stmt->execute();
        $id_binhluan = $conn->insert_id;
        $stmt->close();
        if (!$result) {
            error_log("Error inserting comment in insertRoomComment: " . $conn->error);
            return false;
        }
    } else {
        error_log("Error preparing statement in insertRoomComment (binhluan): " . $conn->error);
        return false;
    }

    // Chèn vào bảng loaiphong_binhluan
    $sql = "INSERT INTO binhluan_dichvu (id_dichvu, id_binhluan) VALUES (?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $id_type, $id_binhluan);
        $result = $stmt->execute();
        $stmt->close();
        if (!$result) {
            error_log("Error inserting into loaiphong_binhluan: " . $conn->error);
            return false;
        }
        return true;
    } else {
        error_log("Error preparing statement in insertRoomComment (loaiphong_binhluan): " . $conn->error);
        return false;
    }

    return false;
}

function getMenu($languageId, $id_amthuc, $active = 1)
{
    global $conn;
    $menuImages = [];

    // Lấy danh sách món ăn
    $sql = "
        SELECT 
            t.*,
            tn.name AS title,
            tn.content AS description,
            a.image
        FROM 
            thucdon t
        LEFT JOIN 
            thucdon_ngonngu tn ON t.id = tn.id_thucdon AND tn.id_ngonngu = ?
        LEFT JOIN 
            anhthucdon a ON t.id = a.id_menu
        WHERE 
            t.id_amthuc = ?
            AND t.active = ?
        ORDER BY 
            t.id ASC
    ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iii", $languageId, $id_amthuc, $active);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $menuImages[] = [
                'id' => $row['id'],
                'price' => $row['price'],
                'title' => $row['title'],
                'description' => $row['description'],
                'image' => $row['image'],
                'outstanding' => $row['outstanding']
            ];
        }
        $stmt->close();
    } else {
        error_log("Error preparing statement: " . $conn->error);
    }

    return $menuImages;
}

function getMenuBar($languageId, $type, $active = 1)
{
    global $conn;

    // Truy vấn danh sách món ăn
    $query = "SELECT t.*, a.image, tn.name AS title, tn.content AS description 
              FROM thucdon t 
              LEFT JOIN thucdon_ngonngu tn ON t.id = tn.id_thucdon 
              LEFT JOIN anhthucdon a ON t.id = a.id_menu
              WHERE tn.id_ngonngu = ? AND t.type = ? AND t.id_amthuc = 2 AND t.active = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isi", $languageId, $type, $active);
    $stmt->execute();
    $result = $stmt->get_result();

    $menuItems = [];
    while ($row = $result->fetch_assoc()) {
        $menuItems[] = [
            'id' => $row['id'],
            'price' => $row['price'],
            'title' => $row['title'],
            'description' => $row['description'],
            'image' => $row['image'],
            'outstanding' => $row['outstanding']
        ];
    }

    return $menuItems;
}


//quanlybinhluan
// Thêm bình luận
function addComment($conn, $content, $rate, $type, $id_khachhang, $id_dichvu = null, $id_nhahang = null, $id_loaiphong = null)
{
    try {
        $sql = "INSERT INTO binhluan (content, create_at, rate, active, id_khachhang) 
                VALUES (?, NOW(), ?, 1, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $content, $rate, $id_khachhang);
        $stmt->execute();
        $id_binhluan = $conn->insert_id;

        if ($type == 'bar') {
            $sql = "INSERT INTO binhluan_bar (id_binhluan) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_binhluan);
            $stmt->execute();
        } elseif ($type == 'dichvu' && $id_dichvu) {
            $sql = "INSERT INTO binhluan_dichvu (id_dichvu, id_binhluan) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id_dichvu, $id_binhluan);
            $stmt->execute();
        } elseif ($type == 'nhahang') {
            $sql = "INSERT INTO binhluan_nhahang (id_binhluan) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_binhluan);
            $stmt->execute();
        } elseif ($type == 'phong' && $id_loaiphong) {
            $sql = "INSERT INTO loaiphong_binhluan (id_binhluan, id_loaiphong) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id_binhluan, $id_loaiphong);
            $stmt->execute();
        }

        return ['status' => 'success', 'message' => 'Thêm bình luận thành công!', 'id_binhluan' => $id_binhluan];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Lỗi khi thêm bình luận: ' . $e->getMessage()];
    }
}

// Thêm khách hàng mới
function addCustomer($conn, $name, $email)
{
    try {
        $sql = "INSERT INTO khachhang (name, email) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
        return $conn->insert_id;
    } catch (Exception $e) {
        return false;
    }
}

function insertService($image, $id_dichvu)
{
    global $conn;
    try {
        $sql = "INSERT INTO anhdichvu (image, is_primary, id_dichvu, id_topic) VALUES (?, 1, ?, 3)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $image, $id_dichvu);
        $stmt->execute();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Sửa bình luận
function updateComment($conn, $id, $content, $rate)
{
    try {
        $sql = "UPDATE binhluan SET content = ?, rate = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $content, $rate, $id);
        $stmt->execute();
        return ['status' => 'success', 'message' => 'Cập nhật bình luận thành công!'];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Lỗi khi cập nhật bình luận: ' . $e->getMessage()];
    }
}

// Ẩn/hiện nhiều bình luận
function bulkToggleComments($conn, $ids)
{
    try {
        if (empty($ids)) {
            return ['status' => 'error', 'message' => 'Vui lòng chọn ít nhất một bình luận!'];
        }
        $ids_placeholder = implode(',', array_fill(0, count($ids), '?'));

        // Lấy trạng thái hiện tại
        $sql = "SELECT id, active FROM binhluan WHERE id IN ($ids_placeholder)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
        $stmt->execute();
        $result = $stmt->get_result();

        // Cập nhật trạng thái ngược lại
        $sql_update = "UPDATE binhluan SET active = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);

        while ($row = $result->fetch_assoc()) {
            $new_active = $row['active'] ? 0 : 1;
            $stmt_update->bind_param('ii', $new_active, $row['id']);
            $stmt_update->execute();
        }

        return ['status' => 'success', 'message' => 'Cập nhật trạng thái thành công!'];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage()];
    }
}

// Xóa nhiều bình luận
function bulkDeleteComments($conn, $ids)
{
    try {
        if (empty($ids)) {
            return ['status' => 'error', 'message' => 'Vui lòng chọn ít nhất một bình luận!'];
        }
        $ids_placeholder = implode(',', array_fill(0, count($ids), '?'));

        // Xóa từ các bảng liên quan
        $tables = ['binhluan_bar', 'binhluan_dichvu', 'binhluan_nhahang', 'loaiphong_binhluan'];
        foreach ($tables as $table) {
            $sql = "DELETE FROM $table WHERE id_binhluan IN ($ids_placeholder)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
            $stmt->execute();
        }

        // Xóa từ bảng binhluan
        $sql = "DELETE FROM binhluan WHERE id IN ($ids_placeholder)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
        $stmt->execute();

        return ['status' => 'success', 'message' => 'Xóa các bình luận thành công!'];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Lỗi khi xóa bình luận: ' . $e->getMessage()];
    }
}

// Tải dữ liệu bình luận
function loadComments($conn, $tab, $subtab, $search, $sort, $status, $date, $rate, $page, $limit = 15)
{
    try {
        $offset = ($page - 1) * $limit;
        $where_conditions = [];
        $params = [];
        $count_params = [];

        if ($search) {
            if ($tab == 'dichvu') {
                $where_conditions[] = "(k.name LIKE ? OR k.email LIKE ? OR dn.title LIKE ? OR b.content LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
                $params[] = "%$search%";
                $params[] = "%$search%";
                $count_params = $params;
            } else {
                $where_conditions[] = "(k.name LIKE ? OR k.email LIKE ? OR b.content LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
                $params[] = "%$search%";
                $count_params = $params;
            }
        }

        if ($date) {
            $where_conditions[] = "DATE(b.create_at) = ?";
            $params[] = $date;
            $count_params[] = $date;
        }

        if ($status !== '') {
            $where_conditions[] = "b.active = ?";
            $params[] = $status;
            $count_params[] = $status;
        }

        if ($rate !== '') {
            $where_conditions[] = "b.rate = ?";
            $params[] = $rate;
            $count_params[] = $rate;
        }

        $where_clause = $where_conditions ? "AND " . implode(" AND ", $where_conditions) : "";
        $order_clause = $sort == 'newest' ? "ORDER BY b.create_at DESC" : "ORDER BY b.create_at ASC";

        if ($tab == 'dichvu') {
            $type_filter = $subtab == 'tour' ? 'tour' : 'dichvu';
            $base_query = "FROM binhluan b 
                           JOIN khachhang k ON b.id_khachhang = k.id 
                           JOIN binhluan_dichvu bd ON b.id = bd.id_binhluan 
                           JOIN dichvu d ON bd.id_dichvu = d.id 
                           JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu 
                           WHERE d.type = '$type_filter' AND dn.id_ngonngu = 1 $where_clause";

            $count_sql = "SELECT COUNT(*) as total $base_query";
            $sql = "SELECT b.id, b.content, b.rate, b.active, b.create_at, k.name, k.email, dn.title 
                    $base_query $order_clause LIMIT $limit OFFSET $offset";
        } elseif ($tab == 'bar') {
            $base_query = "FROM binhluan b 
                           JOIN khachhang k ON b.id_khachhang = k.id 
                           JOIN binhluan_bar bb ON b.id = bb.id_binhluan 
                           WHERE 1=1 $where_clause";

            $count_sql = "SELECT COUNT(*) as total $base_query";
            $sql = "SELECT b.id, b.content, b.rate, b.active, b.create_at, k.name, k.email 
                    $base_query $order_clause LIMIT $limit OFFSET $offset";
        } elseif ($tab == 'nhahang') {
            $base_query = "FROM binhluan b 
                           JOIN khachhang k ON b.id_khachhang = k.id 
                           JOIN binhluan_nhahang bn ON b.id = bn.id_binhluan 
                           WHERE 1=1 $where_clause";

            $count_sql = "SELECT COUNT(*) as total $base_query";
            $sql = "SELECT b.id, b.content, b.rate, b.active, b.create_at, k.name, k.email 
                    $base_query $order_clause LIMIT $limit OFFSET $offset";
        } elseif ($tab == 'phong') {
            $room_ids = [];
            $result = $conn->query("SELECT id FROM loaiphongnghi");
            while ($row = $result->fetch_assoc()) {
                $room_ids[] = $row['id'];
            }

            $subtab_to_room_id = [];
            $result = $conn->query("SELECT lp.id, lpn.name 
                                    FROM loaiphongnghi lp 
                                    JOIN loaiphongnghi_ngonngu lpn ON lp.id = lpn.id_loaiphongnghi 
                                    WHERE lpn.id_ngonngu = 1");
            while ($row = $result->fetch_assoc()) {
                $subtab_name = 'phong' . strtolower(str_replace(' ', '', $row['name']));
                $subtab_to_room_id[$subtab_name] = $row['id'];
            }

            $room_id = isset($subtab_to_room_id[$subtab]) && in_array($subtab_to_room_id[$subtab], $room_ids)
                ? $subtab_to_room_id[$subtab] : 1;

            $base_query = "FROM binhluan b 
                           JOIN khachhang k ON b.id_khachhang = k.id 
                           JOIN loaiphong_binhluan lpb ON b.id = lpb.id_binhluan 
                           JOIN loaiphongnghi lp ON lpb.id_loaiphong = lp.id 
                           JOIN loaiphongnghi_ngonngu lpn ON lp.id = lpn.id_loaiphongnghi 
                           WHERE lpn.id_ngonngu = 1 AND lp.id = ? $where_clause";

            $count_sql = "SELECT COUNT(*) as total $base_query";
            $sql = "SELECT b.id, b.content, b.rate, b.active, b.create_at, k.name, k.email, lpn.name AS phong_name 
                    $base_query $order_clause LIMIT $limit OFFSET $offset";

            $count_params = array_merge([$room_id], $count_params);
            $params = array_merge([$room_id], $params);
        }

        // Đếm tổng số
        $count_stmt = $conn->prepare($count_sql);
        if ($count_params) {
            $count_types = str_repeat('s', count($count_params));
            $count_stmt->bind_param($count_types, ...$count_params);
        }
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();
        $total_records = $count_result->fetch_assoc()['total'];
        $total_pages = ceil($total_records / $limit);

        // Thực hiện query chính
        $stmt = $conn->prepare($sql);
        if ($params) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }

        return [
            'status' => 'success',
            'comments' => $comments,
            'total_records' => $total_records,
            'total_pages' => $total_pages,
            'current_page' => $page
        ];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Lỗi khi tải dữ liệu: ' . $e->getMessage()];
    }
}

function getMenusByTypeAndLanguage($menuType, $languageId)
{
    global $conn;
    $menus = [];

    if (!empty($menuType)) {
        $sql = "SELECT td.id, tdn.title, tdn.content 
                FROM thucdon_tour td
                LEFT JOIN thucdontour_ngonngu tdn ON td.id = tdn.id_menu 
                WHERE tdn.id_ngonngu = ? AND td.type = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("is", $languageId, $menuType);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $menus[] = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'content' => $row['content']
                ];
            }

            $stmt->close();
        } else {
            error_log("Lỗi prepare: " . $conn->error);
        }
    }

    return $menus;
}

function layDanhSachIcon()
{
    global $conn;
    $icons = [];
    $icons_query = "SELECT DISTINCT icon FROM tienich WHERE icon IS NOT NULL AND icon != ''";
    $icons_result = mysqli_query($conn, $icons_query);

    if ($icons_result) {
        while ($row = mysqli_fetch_assoc($icons_result)) {
            $icons[] = $row['icon'];
        }
        mysqli_free_result($icons_result);
    }

    return $icons;
}

function layTienIchNgonNgu($id_tienich)
{
    global $conn;
    $id_tienich = (int)$id_tienich;

    $stmt = $conn->prepare("SELECT title, content FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 2");
    $stmt->bind_param("i", $id_tienich);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc() ?? ['title' => '', 'content' => ''];

    $stmt->close();

    return $data;
}

function addFeature($icon, $title_vi, $content_vi, $title_en, $content_en)
{
    global $conn;
    $result = ['success' => false, 'message' => ''];

    try {
        // Kiểm tra biểu tượng
        if (empty($icon)) {
            $result['message'] = "Vui lòng chọn hoặc nhập biểu tượng!";
            throw new Exception($result['message']);
        }

        // Chèn vào bảng tienich
        $stmt = $conn->prepare("INSERT INTO tienich (icon) VALUES (?)");
        $stmt->bind_param("s", $icon);
        if (!$stmt->execute()) {
            $result['message'] = "Lỗi khi tạo tiện ích: " . $conn->error;
            throw new Exception($result['message']);
        }
        $id_tienich = $conn->insert_id;

        // Chèn vào bảng tienich_ngonngu (Tiếng Việt)
        $stmt = $conn->prepare("INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 1, ?, ?)");
        $stmt->bind_param("iss", $id_tienich, $title_vi, $content_vi);
        if (!$stmt->execute()) {
            $result['message'] = "Lỗi khi thêm nội dung tiện ích: " . $conn->error;
            throw new Exception($result['message']);
        }

        // Chèn vào bảng tienich_ngonngu (Tiếng Anh, nếu có)
        if (!empty($title_en) || !empty($content_en)) {
            $stmt = $conn->prepare("INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 2, ?, ?)");
            $stmt->bind_param("iss", $id_tienich, $title_en, $content_en);
            if (!$stmt->execute()) {
                $result['message'] = "Lỗi khi thêm nội dung tiện ích tiếng Anh: " . $conn->error;
                throw new Exception($result['message']);
            }
        }

        // Chèn vào bảng tienichdichvu
        $stmt = $conn->prepare("INSERT INTO tienichdichvu (id_tienich, page) VALUES (?, 'dichvu')");
        $stmt->bind_param("i", $id_tienich);
        if (!$stmt->execute()) {
            $result['message'] = "Lỗi khi thêm tiện ích vào trang: " . $conn->error;
            throw new Exception($result['message']);
        }

        $result['success'] = true;
        $result['message'] = "Thêm tiện ích thành công!";
        $stmt->close();
    } catch (Exception $e) {
        $result['message'] = $e->getMessage();
        $result['success'] = false;
    }

    return $result;
}

function updateFeature($conn, $id_tienich, $icon, $title_vi, $content_vi, $title_en, $content_en)
{
    $result = ['success' => false, 'message' => ''];

    try {
        // Cập nhật bảng tienich
        $stmt = $conn->prepare("UPDATE tienich SET icon = ? WHERE id = ?");
        $stmt->bind_param("si", $icon, $id_tienich);
        if (!$stmt->execute()) {
            $result['message'] = "Lỗi khi cập nhật tiện ích: " . $conn->error;
            throw new Exception($result['message']);
        }

        // Kiểm tra và cập nhật/chèn tiếng Việt (id_ngonngu = 1)
        $check_query = "SELECT COUNT(*) as count FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 1";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("i", $id_tienich);
        $stmt->execute();
        $check_row = $stmt->get_result()->fetch_assoc();

        if ($check_row['count'] > 0) {
            $query = "UPDATE tienich_ngonngu SET title = ?, content = ? WHERE id_tienich = ? AND id_ngonngu = 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $title_vi, $content_vi, $id_tienich);
            if (!$stmt->execute()) {
                $result['message'] = "Lỗi khi cập nhật nội dung tiếng Việt: " . $conn->error;
                throw new Exception($result['message']);
            }
        } else {
            $insert_query = "INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 1, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("iss", $id_tienich, $title_vi, $content_vi);
            if (!$stmt->execute()) {
                $result['message'] = "Lỗi khi chèn nội dung tiếng Việt: " . $conn->error;
                throw new Exception($result['message']);
            }
        }

        // Kiểm tra và cập nhật/chèn/xóa tiếng Anh (id_ngonngu = 2)
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
                if (!$stmt->execute()) {
                    $result['message'] = "Lỗi khi cập nhật nội dung tiếng Anh: " . $conn->error;
                    throw new Exception($result['message']);
                }
            } else {
                $insert_query = "INSERT INTO tienich_ngonngu (id_tienich, id_ngonngu, title, content) VALUES (?, 2, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("iss", $id_tienich, $title_en, $content_en);
                if (!$stmt->execute()) {
                    $result['message'] = "Lỗi khi chèn nội dung tiếng Anh: " . $conn->error;
                    throw new Exception($result['message']);
                }
            }
        } elseif ($check_row['count'] > 0) {
            $delete_query = "DELETE FROM tienich_ngonngu WHERE id_tienich = ? AND id_ngonngu = 2";
            $stmt = $conn->prepare($delete_query);
            $stmt->bind_param("i", $id_tienich);
            if (!$stmt->execute()) {
                $result['message'] = "Lỗi khi xóa nội dung tiếng Anh: " . $conn->error;
                throw new Exception($result['message']);
            }
        }

        $result['success'] = true;
        $result['message'] = "Cập nhật tiện ích thành công!";
        $stmt->close();
    } catch (Exception $e) {
        $result['message'] = $e->getMessage();
        $result['success'] = false;
    }

    return $result;
}

// Hàm xóa tiện ích
function deleteFeature($id_tienich)
{
    global $conn;
    $result = ['success' => false, 'message' => ''];

    try {
        $stmt = $conn->prepare("DELETE FROM tienichdichvu WHERE id_tienich = ?");
        $stmt->bind_param("i", $id_tienich);
        if (!$stmt->execute()) {
            $result['message'] = "Lỗi khi xóa liên kết tiện ích: " . $conn->error;
            throw new Exception($result['message']);
        }

        $stmt = $conn->prepare("DELETE FROM tienich_ngonngu WHERE id_tienich = ?");
        $stmt->bind_param("i", $id_tienich);
        if (!$stmt->execute()) {
            $result['message'] = "Lỗi khi xóa nội dung tiện ích: " . $conn->error;
            throw new Exception($result['message']);
        }

        $stmt = $conn->prepare("DELETE FROM tienich WHERE id = ?");
        $stmt->bind_param("i", $id_tienich);
        if (!$stmt->execute()) {
            $result['message'] = "Lỗi khi xóa tiện ích: " . $conn->error;
            throw new Exception($result['message']);
        }

        $result['success'] = true;
        $result['message'] = "Xóa tiện ích thành công!";
        $stmt->close();
    } catch (Exception $e) {
        $result['message'] = $e->getMessage();
        $result['success'] = false;
    }

    return $result;
}

function addTourService($price = 'Liên hệ')
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO dichvu (type, active, price) VALUES ('tour', 1, ?)");
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("s", $price);
    $result = $stmt->execute();

    if ($result) {
        $id = $conn->insert_id;
        $stmt->close();
        return $id;
    }

    $stmt->close();
    return false;
}

function addTourLanguage($id_dichvu, $id_ngonngu, $title, $content)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title, content) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("iiss", $id_dichvu, $id_ngonngu, $title, $content);
    $result = $stmt->execute();
    $stmt->close();

    return $result;
}

function updateTour($id_dichvu, $title_vi, $content_vi, $title_en, $content_en, $price_vi, $image_file = null)
{
    global $conn;
    try {
        // Cập nhật thông tin tiếng Việt
        $stmt = $conn->prepare("UPDATE dichvu_ngonngu SET title=?, content=? WHERE id_dichvu=? AND id_ngonngu=1");
        $stmt->bind_param("ssi", $title_vi, $content_vi, $id_dichvu);
        if (!$stmt->execute()) {
            return false;
        }
        $stmt->close();

        // Cập nhật hoặc chèn thông tin tiếng Anh
        if (!empty($title_en) || !empty($content_en)) {
            $check_query = "SELECT COUNT(*) as count FROM dichvu_ngonngu WHERE id_dichvu = ? AND id_ngonngu = 2";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("i", $id_dichvu);
            $stmt->execute();
            $check_row = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($check_row['count'] > 0) {
                $query = "UPDATE dichvu_ngonngu SET title=?, content=? WHERE id_dichvu=? AND id_ngonngu=2";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssi", $title_en, $content_en, $id_dichvu);
                if (!$stmt->execute()) {
                    return false;
                }
            } else {
                $insert_query = "INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title, content) VALUES (?, 2, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("iss", $id_dichvu, $title_en, $content_en);
                if (!$stmt->execute()) {
                    return false;
                }
            }
            $stmt->close();
        }

        // Cập nhật giá và loại tour
        $stmt = $conn->prepare("UPDATE dichvu SET price=?, type='tour' WHERE id=?");
        $stmt->bind_param("si", $price_vi, $id_dichvu);
        if (!$stmt->execute()) {
            return false;
        }
        $stmt->close();

        // Xử lý ảnh mới (nếu có)
        if ($image_file && isset($image_file['error']) && $image_file['error'] === 0) {
            $imageName = uploadImage($image_file);
            if ($imageName) {
                // Xóa ảnh cũ
                $old_image_query = "SELECT image FROM anhdichvu WHERE id_dichvu=? AND is_primary=1";
                $stmt = $conn->prepare($old_image_query);
                $stmt->bind_param("i", $id_dichvu);
                $stmt->execute();
                $old_image = $stmt->get_result()->fetch_assoc()['image'] ?? null;
                if ($old_image && file_exists('../view/img/uploads/dichvu/' . $old_image)) {
                    unlink('../view/img/uploads/dichvu/' . $old_image);
                }
                $stmt->close();

                // Xóa bản ghi ảnh cũ
                $stmt = $conn->prepare("DELETE FROM anhdichvu WHERE id_dichvu=? AND is_primary=1");
                $stmt->bind_param("i", $id_dichvu);
                if (!$stmt->execute()) {
                    return false;
                }
                $stmt->close();

                // Chèn ảnh mới
                $stmt = $conn->prepare("INSERT INTO anhdichvu (image, is_primary, id_dichvu, id_topic) VALUES (?, 1, ?, 3)");
                $stmt->bind_param("si", $imageName, $id_dichvu);
                if (!$stmt->execute()) {
                    return false;
                }
                $stmt->close();
            }
        }

        return true;
    } catch (Exception $e) {
        error_log("Lỗi trong updateTour: " . $e->getMessage());
        return false;
    }
}

function deleteTour($id_dichvu)
{
    global $conn;
    try {
        // Xóa bình luận liên quan trong bảng binhluan_dichvu
        $stmt = $conn->prepare("DELETE FROM binhluan_dichvu WHERE id_dichvu = ?");
        $stmt->bind_param("i", $id_dichvu);
        if (!$stmt->execute()) {
            return false;
        }
        $stmt->close();

        // Xóa hình ảnh liên quan
        $image_query = "SELECT image FROM anhdichvu WHERE id_dichvu = ?";
        $stmt = $conn->prepare($image_query);
        $stmt->bind_param("i", $id_dichvu);
        if (!$stmt->execute()) {
            return false;
        }
        $image_result = $stmt->get_result();
        while ($image = $image_result->fetch_assoc()) {
            if ($image['image'] && file_exists('../img/' . $image['image'])) {
                unlink('../view/img/uploads/dichvu/' . $image['image']);
            }
        }
        $stmt->close();

        // Xóa bản ghi hình ảnh
        $stmt = $conn->prepare("DELETE FROM anhdichvu WHERE id_dichvu = ?");
        $stmt->bind_param("i", $id_dichvu);
        if (!$stmt->execute()) {
            return false;
        }
        $stmt->close();

        // Xóa bản ghi ngôn ngữ
        $stmt = $conn->prepare("DELETE FROM dichvu_ngonngu WHERE id_dichvu = ?");
        $stmt->bind_param("i", $id_dichvu);
        if (!$stmt->execute()) {
            return false;
        }
        $stmt->close();

        // Xóa bản ghi tour
        $stmt = $conn->prepare("DELETE FROM dichvu WHERE id = ?");
        $stmt->bind_param("i", $id_dichvu);
        if (!$stmt->execute()) {
            return false;
        }
        $stmt->close();

        return true;
    } catch (Exception $e) {
        error_log("Lỗi trong deleteTour: " . $e->getMessage());
        return false;
    }
}

function addService($title_vi, $content_vi, $title_en, $content_en, $price_vi, $image_file = null)
{
    global $conn;
    try {
        // Chèn dịch vụ mới
        $stmt = $conn->prepare("INSERT INTO dichvu (type, active, price) VALUES ('dichvu', 1, ?)");
        $stmt->bind_param("s", $price_vi);
        if (!$stmt->execute()) {
            return false;
        }
        $id_dichvu = $conn->insert_id;
        $stmt->close();

        // Chèn thông tin tiếng Việt
        $stmt = $conn->prepare("INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title, content) VALUES (?, 1, ?, ?)");
        $stmt->bind_param("iss", $id_dichvu, $title_vi, $content_vi);
        if (!$stmt->execute()) {
            return false;
        }
        $stmt->close();

        // Chèn thông tin tiếng Anh (nếu có)
        if (!empty($title_en) || !empty($content_en)) {
            $stmt = $conn->prepare("INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title, content) VALUES (?, 2, ?, ?)");
            $stmt->bind_param("iss", $id_dichvu, $title_en, $content_en);
            if (!$stmt->execute()) {
                return false;
            }
            $stmt->close();
        }

        // Chèn ảnh (nếu có)
        if ($image_file && isset($image_file['error']) && $image_file['error'] === 0) {
            $imageName = uploadImage($image_file);
            if ($imageName) {
                $stmt = $conn->prepare("INSERT INTO anhdichvu (image, is_primary, id_dichvu, id_topic) VALUES (?, 1, ?, 3)");
                $stmt->bind_param("si", $imageName, $id_dichvu);
                if (!$stmt->execute()) {
                    return false;
                }
                $stmt->close();
            }
        }

        return true;
    } catch (Exception $e) {
        error_log("Lỗi trong addService: " . $e->getMessage());
        return false;
    }
}

function updateService($id_dichvu, $title_vi, $content_vi, $title_en, $content_en, $price_vi, $image_file = null)
{
    global $conn;
    try {
        // Kiểm tra và cập nhật/chèn tiếng Việt
        $check_query = "SELECT COUNT(*) as count FROM dichvu_ngonngu WHERE id_dichvu = ? AND id_ngonngu = 1";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("i", $id_dichvu);
        if (!$stmt->execute()) {
            return false;
        }
        $check_row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($check_row['count'] > 0) {
            $query = "UPDATE dichvu_ngonngu SET title = ?, content = ? WHERE id_dichvu = ? AND id_ngonngu = 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $title_vi, $content_vi, $id_dichvu);
            if (!$stmt->execute()) {
                return false;
            }
        } else {
            $insert_query = "INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title, content) VALUES (?, 1, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("iss", $id_dichvu, $title_vi, $content_vi);
            if (!$stmt->execute()) {
                return false;
            }
        }
        $stmt->close();

        // Kiểm tra và cập nhật/chèn/xóa tiếng Anh
        $check_query = "SELECT COUNT(*) as count FROM dichvu_ngonngu WHERE id_dichvu = ? AND id_ngonngu = 2";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("i", $id_dichvu);
        if (!$stmt->execute()) {
            return false;
        }
        $check_row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!empty($title_en) || !empty($content_en)) {
            if ($check_row['count'] > 0) {
                $query = "UPDATE dichvu_ngonngu SET title = ?, content = ? WHERE id_dichvu = ? AND id_ngonngu = 2";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssi", $title_en, $content_en, $id_dichvu);
                if (!$stmt->execute()) {
                    return false;
                }
            } else {
                $insert_query = "INSERT INTO dichvu_ngonngu (id_dichvu, id_ngonngu, title, content) VALUES (?, 2, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("iss", $id_dichvu, $title_en, $content_en);
                if (!$stmt->execute()) {
                    return false;
                }
            }
        } elseif ($check_row['count'] > 0) {
            $delete_query = "DELETE FROM dichvu_ngonngu WHERE id_dichvu = ? AND id_ngonngu = 2";
            $stmt = $conn->prepare($delete_query);
            $stmt->bind_param("i", $id_dichvu);
            if (!$stmt->execute()) {
                return false;
            }
        }
        $stmt->close();

        // Cập nhật giá và loại dịch vụ
        $stmt = $conn->prepare("UPDATE dichvu SET price = ?, type = 'dichvu' WHERE id = ?");
        $stmt->bind_param("si", $price_vi, $id_dichvu);
        if (!$stmt->execute()) {
            return false;
        }
        $stmt->close();

        // Xử lý ảnh mới (nếu có)
        if ($image_file && isset($image_file['error']) && $image_file['error'] === 0) {
            $imageName = uploadImage($image_file);
            if ($imageName) {
                // Xóa ảnh cũ
                $old_image_query = "SELECT image FROM anhdichvu WHERE id_dichvu = ? AND is_primary = 1";
                $stmt = $conn->prepare($old_image_query);
                $stmt->bind_param("i", $id_dichvu);
                if (!$stmt->execute()) {
                    return false;
                }
                $old_image = $stmt->get_result()->fetch_assoc()['image'] ?? null;
                if ($old_image && file_exists('../view/img/uploads/dichvu/' . $old_image)) {
                    unlink('../view/img/uploads/dichvu/' . $old_image);
                }
                $stmt->close();

                // Xóa bản ghi ảnh cũ
                $stmt = $conn->prepare("DELETE FROM anhdichvu WHERE id_dichvu = ? AND is_primary = 1");
                $stmt->bind_param("i", $id_dichvu);
                if (!$stmt->execute()) {
                    return false;
                }
                $stmt->close();

                // Chèn ảnh mới
                $stmt = $conn->prepare("INSERT INTO anhdichvu (image, is_primary, id_dichvu, id_topic) VALUES (?, 1, ?, 3)");
                $stmt->bind_param("si", $imageName, $id_dichvu);
                if (!$stmt->execute()) {
                    return false;
                }
                $stmt->close();
            }
        }

        return true;
    } catch (Exception $e) {
        error_log("Lỗi trong updateService: " . $e->getMessage());
        return false;
    }
}

function deleteService($id_dichvu)
{
    global $conn;
    try {
        // Xóa bình luận liên quan trong bảng binhluan_dichvu
        $stmt = $conn->prepare("DELETE FROM binhluan_dichvu WHERE id_dichvu = ?");
        $stmt->bind_param("i", $id_dichvu);
        if (!$stmt->execute()) {
            return false;
        }
        $stmt->close();

        // Xóa hình ảnh liên quan
        $image_query = "SELECT image FROM anhdichvu WHERE id_dichvu = ?";
        $stmt = $conn->prepare($image_query);
        $stmt->bind_param("i", $id_dichvu);
        if (!$stmt->execute()) {
            return false;
        }
        $image_result = $stmt->get_result();
        while ($image = $image_result->fetch_assoc()) {
            if ($image['image'] && file_exists('../img/' . $image['image'])) {
                unlink('../img/' . $image['image']);
            }
        }
        $stmt->close();

        // Xóa bản ghi hình ảnh
        $stmt = $conn->prepare("DELETE FROM anhdichvu WHERE id_dichvu = ?");
        $stmt->bind_param("i", $id_dichvu);
        if (!$stmt->execute()) {
            return false;
        }
        $stmt->close();

        // Xóa bản ghi ngôn ngữ
        $stmt = $conn->prepare("DELETE FROM dichvu_ngonngu WHERE id_dichvu = ?");
        $stmt->bind_param("i", $id_dichvu);
        if (!$stmt->execute()) {
            return false;
        }
        $stmt->close();

        // Xóa bản ghi dịch vụ
        $stmt = $conn->prepare("DELETE FROM dichvu WHERE id = ?");
        $stmt->bind_param("i", $id_dichvu);
        if (!$stmt->execute()) {
            return false;
        }
        $stmt->close();

        return true;
    } catch (Exception $e) {
        error_log("Lỗi trong deleteService: " . $e->getMessage());
        return false;
    }
}
//quanlyanh (liem)

// Lấy danh sách chủ đề
function getTopics($conn) {
    try {
        $sql = "SELECT * FROM thuvien WHERE id IN (1, 4, 9, 12, 13, 16) ORDER BY id";
        $result = $conn->query($sql);
        $topics = [];
        while ($row = $result->fetch_assoc()) {
            $topics[] = $row;
        }
        return ['status' => 'success', 'topics' => $topics];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Lỗi khi tải chủ đề: ' . $e->getMessage()];
    }
}

// Lấy danh sách các trang
function getPages($conn) {
    try {
        $sql = "SELECT DISTINCT page FROM head_banner WHERE id_topic = 4 AND page != '' ORDER BY page";
        $result = $conn->query($sql);
        $pages = [];
        while ($row = $result->fetch_assoc()) {
            $pages[] = $row['page'];
        }
        return ['status' => 'success', 'pages' => $pages];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Lỗi khi tải danh sách trang: ' . $e->getMessage()];
    }
}

// Lấy danh sách sự kiện
function getSukien($conn) {
    try {
        $sql = "SELECT s.*, sn.title AS title 
                FROM sukien s 
                LEFT JOIN sukien_ngonngu sn ON s.id = sn.id_sukien 
                WHERE sn.id_ngonngu = 1 
                ORDER BY s.id";
        $result = $conn->query($sql);
        $sukien = [];
        while ($row = $result->fetch_assoc()) {
            $sukien[] = [
                'id' => $row['id'],
                'code' => $row['code'],
                'active' => $row['active'],
                'title' => $row['title'] ?: $row['code']
            ];
        }
        return ['status' => 'success', 'sukien' => $sukien];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Lỗi khi tải sự kiện: ' . $e->getMessage()];
    }
}

// Lấy danh sách ảnh/video theo chủ đề
function getImages($conn, $topic_id, $page = '', $id_sukien = '') {
    try {
        $images = [];
        switch ($topic_id) {
            case '1':
                $sql = "SELECT atq.*, t.name AS hotel_name, ca.area AS chon_area 
                        FROM anhtongquat atq 
                        LEFT JOIN thongtinkhachsan t ON atq.id_thongtinhotel = t.id 
                        LEFT JOIN chon_anhtongquat ca ON atq.id = ca.id_anhtongquat 
                        WHERE atq.id_topic = ? 
                        ORDER BY ca.area IS NOT NULL DESC, atq.id DESC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $topic_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $area_display = '';
                    if ($row['chon_area']) {
                        $area_map = [
                            'feature-image-right' => 'Ảnh dịch vụ phải',
                            'feature-image-left' => 'Ảnh dịch vụ trái',
                            'banner-overlay' => 'Ảnh banner phủ'
                        ];
                        $area_display = $area_map[$row['chon_area']] ?? $row['chon_area'];
                    }
                    $images[] = [
                        'id' => $row['id'],
                        'image' => $row['image'],
                        'table' => 'anhtongquat',
                        'active' => $row['active'],
                        'chon_area' => $row['chon_area'],
                        'area_display' => $area_display
                    ];
                }
                break;

            case '4':
                $sql = "SELECT * FROM head_banner WHERE id_topic = ?";
                if (!empty($page)) {
                    $sql .= " AND page = ?";
                }
                $sql .= " ORDER BY id DESC";
                $stmt = $conn->prepare($sql);
                if (!empty($page)) {
                    $stmt->bind_param("is", $topic_id, $page);
                } else {
                    $stmt->bind_param("i", $topic_id);
                }
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $images[] = [
                        'id' => $row['id'],
                        'image' => $row['image'],
                        'table' => 'head_banner',
                        'active' => 1,
                        'created_at' => null,
                        'extra_info' => 'Page: ' . ($row['page'] ?: 'N/A') . ($row['area'] ? ', Area: ' . $row['area'] : '')
                    ];
                }
                break;

            case '9':
                $sql = "SELECT ask.*, s.code AS event_code, sn.title AS event_title 
                        FROM anhsukien ask 
                        LEFT JOIN sukien s ON ask.id_sukien = s.id 
                        LEFT JOIN sukien_ngonngu sn ON s.id = sn.id_sukien AND sn.id_ngonngu = 1 
                        WHERE ask.id_topic = ?";
                if (!empty($id_sukien)) {
                    $sql .= " AND ask.id_sukien = ?";
                }
                $sql .= " ORDER BY ask.id DESC";
                $stmt = $conn->prepare($sql);
                if (!empty($id_sukien)) {
                    $stmt->bind_param("ii", $topic_id, $id_sukien);
                } else {
                    $stmt->bind_param("i", $topic_id);
                }
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $images[] = [
                        'id' => $row['id'],
                        'image' => $row['image'],
                        'table' => 'anhsukien',
                        'is_primary' => $row['is_primary'],
                        'extra_info' => 'Sự kiện: ' . ($row['event_title'] ?: $row['event_code'])
                    ];
                }
                break;

            case '12':
                $sql = "SELECT * FROM anhnhahang WHERE id_topic = ? ORDER BY id DESC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $topic_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $images[] = [
                        'id' => $row['id'],
                        'image' => $row['image'],
                        'table' => 'anhnhahang',
                        'active' => $row['active'],
                        'created_at' => $row['created_at']
                    ];
                }
                break;

            case '13':
                $sql = "SELECT * FROM anhbar WHERE id_topic = ? ORDER BY id DESC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $topic_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $images[] = [
                        'id' => $row['id'],
                        'image' => $row['image'],
                        'table' => 'anhbar',
                        'active' => $row['active'],
                        'created_at' => $row['created_at']
                    ];
                }
                break;

            case '16':
                $sql = "SELECT * FROM video WHERE id_topic = ? ORDER BY id DESC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $topic_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $images[] = [
                        'id' => $row['id'],
                        'video' => $row['video'],
                        'service' => $row['service'],
                        'table' => 'video',
                        'extra_info' => 'Dịch vụ: ' . ($row['service'] ?: 'Không có')
                    ];
                }
                break;

            default:
                return ['status' => 'error', 'message' => 'Chủ đề không hợp lệ'];
        }
        return ['status' => 'success', 'images' => $images, 'topic_id' => $topic_id];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Lỗi khi tải ảnh/video: ' . $e->getMessage()];
    }
}

// Tải lên ảnh/video
function uploadImages($conn, $topic_id, $files, $event_id = null, $service = null) {
    try {
        $uploaded_count = 0;
        $upload_errors = [];
        $upload_dir = ($topic_id == '16') ? '../view/video/' : '../view/img/';
        $allowed_types = ($topic_id == '16') 
            ? ['mp4', 'avi', 'mov', 'wmv', 'flv'] 
            : ['jpg', 'jpeg', 'png', 'gif'];
        $max_size = ($topic_id == '16') ? 50 * 1024 * 1024 : 10 * 1024 * 1024;

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] == 0) {
                $file_name = $files['name'][$i];
                $file_tmp = $files['tmp_name'][$i];
                $file_size = $files['size'][$i];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                if (!in_array($file_ext, $allowed_types)) {
                    $file_type = ($topic_id == '16') ? 'video' : 'ảnh';
                    $upload_errors[] = "File $file_name không đúng định dạng $file_type!";
                    continue;
                }

                if ($file_size > $max_size) {
                    $size_limit = ($topic_id == '16') ? '50MB' : '10MB';
                    $upload_errors[] = "File $file_name quá lớn (>$size_limit)!";
                    continue;
                }

                $new_file_name = time() . '_' . rand(100000, 999999) . '_' . $file_name;
                $upload_path = $upload_dir . $new_file_name;

                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $insert_success = false;

                    switch ($topic_id) {
                        case '1':
                            $sql = "INSERT INTO anhtongquat (image, active, id_topic, id_thongtinhotel) 
                                    VALUES (?, 1, ?, 1)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("si", $new_file_name, $topic_id);
                            $insert_success = $stmt->execute();
                            break;

                        case '9':
                            if (!$event_id) {
                                $upload_errors[] = "Thiếu ID sự kiện cho file $file_name!";
                                unlink($upload_path);
                                continue 2;
                            }
                            $sql = "INSERT INTO anhsukien (image, is_primary, id_topic, id_sukien) 
                                    VALUES (?, 0, ?, ?)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("sii", $new_file_name, $topic_id, $event_id);
                            $insert_success = $stmt->execute();
                            break;

                        case '12':
                            $sql = "INSERT INTO anhnhahang (image, active, created_at, id_topic) 
                                    VALUES (?, 1, NOW(), ?)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("si", $new_file_name, $topic_id);
                            $insert_success = $stmt->execute();
                            break;

                        case '13':
                            $sql = "INSERT INTO anhbar (image, active, created_at, id_topic) 
                                    VALUES (?, 1, NOW(), ?)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("si", $new_file_name, $topic_id);
                            $insert_success = $stmt->execute();
                            break;

                        case '16':
                            $sql = "INSERT INTO video (video, service, id_topic) 
                                    VALUES (?, ?, ?)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ssi", $new_file_name, $service, $topic_id);
                            $insert_success = $stmt->execute();
                            break;
                    }

                    if ($insert_success) {
                        $uploaded_count++;
                    } else {
                        $upload_errors[] = "Lỗi lưu database cho file $file_name: " . $conn->error;
                        unlink($upload_path);
                    }
                } else {
                    $upload_errors[] = "Lỗi upload file $file_name!";
                }
            } else {
                $upload_errors[] = "Lỗi file " . $files['name'][$i] . ": " . $files['error'][$i];
            }
        }

        if ($uploaded_count > 0) {
            $file_type = ($topic_id == '16') ? 'video' : 'ảnh';
            $message = "Đã upload thành công $uploaded_count $file_type!";
            if (!empty($upload_errors)) {
                $message .= " Có " . count($upload_errors) . " lỗi: " . implode(', ', $upload_errors);
            }
            return ['status' => 'success', 'uploaded_count' => $uploaded_count, 'message' => $message];
        } else {
            $file_type = ($topic_id == '16') ? 'video' : 'ảnh';
            return ['status' => 'error', 'message' => "Không upload được $file_type nào! " . implode(', ', $upload_errors)];
        }
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Lỗi khi upload: ' . $e->getMessage()];
    }
}

// Chỉnh sửa ảnh (cho head_banner)
function editImage($conn, $topic_id, $id, $file) {
    try {
        if (!in_array($topic_id, ['1', '4'])) {
            return ['status' => 'error', 'message' => 'Chủ đề không hỗ trợ chỉnh sửa ảnh!'];
        }

        $upload_dir = '../view/img/';
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $max_size = 10 * 1024 * 1024;

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_types)) {
            return ['status' => 'error', 'message' => "File $file_name không đúng định dạng ảnh!"];
        }

        if ($file_size > $max_size) {
            return ['status' => 'error', 'message' => "File $file_name quá lớn (>10MB)!"];
        }

        // Lấy tên ảnh cũ
        $table = ($topic_id == '1') ? 'anhtongquat' : 'head_banner';
        $sql = "SELECT image FROM $table WHERE id = ? AND id_topic = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id, $topic_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $old_image = $row['image'];

        // Tạo tên file mới
        $new_file_name = time() . '_' . rand(100000, 999999) . '_' . $file_name;
        $upload_path = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            // Cập nhật database
            $sql = "UPDATE $table SET image = ? WHERE id = ? AND id_topic = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $new_file_name, $id, $topic_id);
            if ($stmt->execute()) {
                // Xóa ảnh cũ
                if ($old_image && file_exists($upload_dir . $old_image)) {
                    unlink($upload_dir . $old_image);
                }
                return ['status' => 'success', 'message' => 'Cập nhật ảnh thành công!'];
            } else {
                unlink($upload_path);
                return ['status' => 'error', 'message' => 'Lỗi lưu database: ' . $conn->error];
            }
        } else {
            return ['status' => 'error', 'message' => "Lỗi upload file $file_name!"];
        }
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Lỗi khi chỉnh sửa ảnh: ' . $e->getMessage()];
    }
}

// Xóa ảnh/video
function deleteItem($conn, $id, $table, $file_name) {
    try {
        $sql = "DELETE FROM $table WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $file_path = ($table == 'video') ? '../view/video/' . $file_name : '../view/img/' . $file_name;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            return ['status' => 'success', 'message' => 'Xóa thành công!'];
        } else {
            return ['status' => 'error', 'message' => 'Lỗi khi xóa: ' . $conn->error];
        }
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Lỗi khi xóa: ' . $e->getMessage()];
    }
}

// Chuyển đổi trạng thái (active/is_primary)
function toggleStatus($conn, $id, $table, $field, $current_status) {
    try {
        $new_status = $current_status == 1 ? 0 : 1;

        if ($table == 'anhsukien' && $field == 'is_primary' && $new_status == 1) {
            $sql = "SELECT id_sukien FROM anhsukien WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $id_sukien = $row['id_sukien'];

            $sql = "SELECT id FROM anhsukien WHERE id_sukien = ? AND is_primary = 1 AND id != ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id_sukien, $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                return ['status' => 'error', 'message' => 'Sự kiện này đã có ảnh chính! Vui lòng bỏ ảnh chính hiện tại trước.'];
            }
        }

        $sql = "UPDATE $table SET $field = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $new_status, $id);
        if ($stmt->execute()) {
            return ['status' => 'success', 'new_status' => $new_status];
        } else {
            return ['status' => 'error', 'message' => 'Lỗi khi cập nhật: ' . $conn->error];
        }
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage()];
    }
}