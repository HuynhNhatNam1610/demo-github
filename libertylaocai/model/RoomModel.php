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

function getGreetingByLanguage($languageId = null, $page)
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

function getAmenitiesForRoom($roomTypeId, $languageId)
{
    global $conn;
    $amenities = [];

    $sql = "SELECT tn.content
            FROM tienich_loaiphong tlp
            JOIN tienich t ON tlp.id_tienich = t.id
            JOIN tienich_ngonngu tn ON t.id = tn.id_tienich
            WHERE tlp.id_loaiphong = ? AND tn.id_ngonngu = ? AND t.active = 1";

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

function getOrganizedEvents($languageId)
{
    global $conn;
    $events = [];

    $sql = "SELECT 
                skdt.id,
                skdt.type_serviced,
                skdt.create_at,
                skdt_ngonngu.title,
                skdt_ngonngu.content,
                askdt.image
            FROM sukiendatochuc skdt
            LEFT JOIN sukiendatochuc_ngonngu skdt_ngonngu ON skdt.id = skdt_ngonngu.id_sukiendatochuc
            LEFT JOIN anhsukiendatochuc askdt ON skdt.id = askdt.id_sukiendatochuc AND askdt.is_primary = 1
            WHERE skdt.active = 1 AND skdt_ngonngu.id_ngonngu = ?
            GROUP BY skdt.type_serviced
            ORDER BY skdt.create_at DESC";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $languageId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            // Định dạng ngày tháng
            $date = new DateTime($row['create_at']);
            $day = $date->format('d/m/Y');
            $events[] = [
                'id' => $row['id'],
                'type_serviced' => $row['type_serviced'],
                'create_at' => $day,
                'title' => $row['title'] ?? ($languageId == 1 ? 'Sự kiện không xác định' : 'Undefined Event'),
                'content' => $row['content'] ?? ($languageId == 1 ? 'Nội dung không có' : 'No content available'),
                'image' => $row['image'] ?? 'default-event-image.jpg'
            ];
        }

        mysqli_stmt_close($stmt);
    } else {
        error_log("Lỗi chuẩn bị truy vấn getOrganizedEvents: " . mysqli_error($conn));
    }

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
    $sql = "SELECT 
                skdt.type_serviced,
                askdt.image
            FROM sukiendatochuc skdt
            LEFT JOIN anhsukiendatochuc askdt ON skdt.id = askdt.id_sukiendatochuc
            WHERE skdt.active = 1 AND skdt.type_serviced = ?
            GROUP BY skdt.type_serviced, askdt.image
            ORDER BY skdt.id DESC";

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

function getConferenceRooms($languageId = null)
{
    global $conn;
    $rooms = [];

    // Kiểm tra kết nối cơ sở dữ liệu
    if (!$conn) {
        error_log("Lỗi: Kết nối cơ sở dữ liệu không tồn tại.");
        return $rooms;
    }

    // Xây dựng câu truy vấn SQL
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
            LEFT JOIN hoitruong_ngonngu htnn ON ht.id = htnn.id_hoitruong
            LEFT JOIN giathuehoitruong gtht ON ht.id = gtht.id_hoitruong
            LEFT JOIN anhhoitruong aht ON ht.id = aht.id_hoitruong
            WHERE htnn.id_ngonngu = ? AND aht.active = 1
            GROUP BY ht.id, htnn.name, htnn.description
            ORDER BY ht.floor_number ASC";

    // Chuẩn bị truy vấn
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log("Lỗi chuẩn bị truy vấn getConferenceRooms: " . mysqli_error($conn));
        return $rooms;
    }

    // Gán tham số
    mysqli_stmt_bind_param($stmt, "i", $languageId);

    // Thực thi truy vấn
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Lỗi thực thi truy vấn getConferenceRooms: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return $rooms;
    }

    // Lấy kết quả
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        // Xử lý giá thuê
        $prices = [];
        if ($row['prices']) {
            foreach (explode('|', $row['prices']) as $price) {
                list($how_long, $price_value) = explode(':', $price);
                $prices[$how_long] = $price_value;
            }
        }

        // Xử lý danh sách ảnh
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

    // Đóng statement
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
            ORDER BY a.id";

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
//qwert
// Lấy danh sách phòng
function getRooms1($conn) {
    $rooms_sql = "
        SELECT 
            p.id,
            p.room_number,
            p.status,
            p.id_loaiphong,
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

// Lấy danh sách loại phòng
function getRoomTypes1($conn) {
    $room_types_sql = "
        SELECT lpn.id, lpnnn.name, lpnnn.description, lpn.price, lpn.quantity, lpn.area,
               (SELECT COUNT(*) FROM anhkhachsan WHERE id_loaiphongnghi = lpn.id) as image_count
        FROM loaiphongnghi lpn
        LEFT JOIN loaiphongnghi_ngonngu lpnnn ON lpn.id = lpnnn.id_loaiphongnghi AND lpnnn.id_ngonngu = 1
        ORDER BY lpn.id
    ";
    $room_types_result = $conn->query($room_types_sql);
    $room_types = [];
    while ($row = $room_types_result->fetch_assoc()) {
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

// Lấy thống kê phòng
function getStats1($conn) {
    $stats_sql = "
        SELECT 
            COUNT(*) as total_rooms,
            SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as available_rooms,
            SUM(CASE WHEN status = 'reserved' THEN 1 ELSE 0 END) as reserved_rooms,
            SUM(CASE WHEN status = 'maintenance' THEN 1 ELSE 0 END) as maintenance_rooms
        FROM phongkhachsan
    ";
    $stats_result = $conn->query($stats_sql);
    return $stats_result->fetch_assoc();
}

// Lấy thống kê theo loại phòng
function getRoomTypeStats1($conn) {
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

// Thêm phòng
function addRoom1($conn, $room_number, $id_loaiphong, $status) {
    $conn->begin_transaction();
    try {
        $sql = "INSERT INTO phongkhachsan (room_number, status, id_loaiphong) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $room_number, $status, $id_loaiphong);
        if (!$stmt->execute()) {
            throw new Exception("Lỗi khi thêm phòng: " . $conn->error);
        }

        $update_quantity_sql = "UPDATE loaiphongnghi SET quantity = quantity + 1 WHERE id = ?";
        $update_stmt = $conn->prepare($update_quantity_sql);
        $update_stmt->bind_param("i", $id_loaiphong);
        if (!$update_stmt->execute()) {
            throw new Exception("Lỗi khi cập nhật số lượng loại phòng: " . $conn->error);
        }

        $conn->commit();
        return [
            'status' => 'success',
            'message' => 'Thêm phòng thành công và đã cập nhật số lượng loại phòng!',
            'rooms' => getRooms1($conn),
            'stats' => getStats1($conn),
            'room_type_stats' => getRoomTypeStats1($conn),
            'room_types' => getRoomTypes1($conn)
        ];
    } catch (Exception $e) {
        $conn->rollback();
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

// Cập nhật phòng
function updateRoom1($conn, $id, $room_number, $id_loaiphong, $status) {
    $old_room_sql = "SELECT id_loaiphong, status FROM phongkhachsan WHERE id = ?";
    $old_stmt = $conn->prepare($old_room_sql);
    $old_stmt->bind_param("i", $id);
    $old_stmt->execute();
    $old_result = $old_stmt->get_result();
    $old_room = $old_result->fetch_assoc();
    $old_id_loaiphong = $old_room['id_loaiphong'];
    $old_status = $old_room['status'];

    $conn->begin_transaction();
    try {
        $sql = "UPDATE phongkhachsan SET room_number = ?, status = ?, id_loaiphong = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $room_number, $status, $id_loaiphong, $id);
        if (!$stmt->execute()) {
            throw new Exception("Lỗi khi cập nhật phòng: " . $conn->error);
        }

        if ($old_id_loaiphong != $id_loaiphong) {
            $decrease_sql = "UPDATE loaiphongnghi SET quantity = quantity - 1 WHERE id = ? AND quantity > 0";
            $decrease_stmt = $conn->prepare($decrease_sql);
            $decrease_stmt->bind_param("i", $old_id_loaiphong);
            $decrease_stmt->execute();

            $increase_sql = "UPDATE loaiphongnghi SET quantity = quantity + 1 WHERE id = ?";
            $increase_stmt = $conn->prepare($increase_sql);
            $increase_stmt->bind_param("i", $id_loaiphong);
            $increase_stmt->execute();
        }

        $checkout_message = '';
        if ($old_status != 'available' && $status == 'available') {
            $delete_booking_sql = "DELETE FROM datphongkhachsan 
                                 WHERE id_phong = ? 
                                 AND status IN ('pending', 'confirmed', 'checked_in')";
            $delete_booking_stmt = $conn->prepare($delete_booking_sql);
            $delete_booking_stmt->bind_param("i", $id);
            if ($delete_booking_stmt->execute()) {
                $deleted_bookings = $delete_booking_stmt->affected_rows;
                if ($deleted_bookings > 0) {
                    $checkout_message = " Đã xóa {$deleted_bookings} bản ghi đặt phòng (checkout).";
                }
            }
        }

        $conn->commit();
        return [
            'status' => 'success',
            'message' => 'Cập nhật phòng thành công!' . $checkout_message,
            'rooms' => getRooms1($conn),
            'stats' => getStats1($conn),
            'room_type_stats' => getRoomTypeStats1($conn),
            'room_types' => getRoomTypes1($conn)
        ];
    } catch (Exception $e) {
        $conn->rollback();
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

function bulkUpdateStatus1($conn, $status, $room_ids) {
    if (empty($status) || empty($room_ids)) {
        return [
            'status' => 'error',
            'message' => 'Vui lòng chọn trạng thái và ít nhất một phòng!'
        ];
    }

    $conn->begin_transaction();
    try {
        // Tạo câu lệnh prepare một lần
        $update_sql = "UPDATE phongkhachsan SET status = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        
        $deleted_bookings = 0;
        foreach ($room_ids as $room_id) {
            // Bind và execute cho từng phòng
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
                         ($deleted_bookings > 0 ? " Đã xóa {$deleted_bookings} bản ghi đặt phòng." : ""),
            'rooms' => getRooms1($conn),
            'stats' => getStats1($conn),
            'room_type_stats' => getRoomTypeStats1($conn)
        ];
    } catch (Exception $e) {
        $conn->rollback();
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

// Xóa phòng
function deleteRoom1($conn, $id) {
    $check_sql = "SELECT COUNT(*) as count FROM datphongkhachsan WHERE id_phong = ? AND status IN ('pending', 'confirmed')";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        return [
            'status' => 'error',
            'message' => 'Không thể xóa phòng này vì đang có đặt phòng!'
        ];
    }

    $room_info_sql = "SELECT id_loaiphong FROM phongkhachsan WHERE id = ?";
    $room_info_stmt = $conn->prepare($room_info_sql);
    $room_info_stmt->bind_param("i", $id);
    $room_info_stmt->execute();
    $room_info_result = $room_info_stmt->get_result();
    $room_info = $room_info_result->fetch_assoc();
    $id_loaiphong = $room_info['id_loaiphong'];

    $conn->begin_transaction();
    try {
        $sql = "DELETE FROM phongkhachsan WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Lỗi khi xóa phòng: " . $conn->error);
        }

        $decrease_quantity_sql = "UPDATE loaiphongnghi SET quantity = quantity - 1 WHERE id = ? AND quantity > 0";
        $decrease_stmt = $conn->prepare($decrease_quantity_sql);
        $decrease_stmt->bind_param("i", $id_loaiphong);
        if (!$decrease_stmt->execute()) {
            throw new Exception("Lỗi khi cập nhật số lượng loại phòng: " . $conn->error);
        }

        $conn->commit();
        return [
            'status' => 'success',
            'message' => 'Xóa phòng thành công và đã cập nhật số lượng loại phòng!',
            'rooms' => getRooms1($conn),
            'stats' => getStats1($conn),
            'room_type_stats' => getRoomTypeStats1($conn),
            'room_types' => getRoomTypes1($conn)
        ];
    } catch (Exception $e) {
        $conn->rollback();
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

// Checkout phòng
function checkoutRoom1($conn, $id) {
    $conn->begin_transaction();
    try {
        $update_room_sql = "UPDATE phongkhachsan SET status = 'available' WHERE id = ?";
        $update_stmt = $conn->prepare($update_room_sql);
        $update_stmt->bind_param("i", $id);
        if (!$update_stmt->execute()) {
            throw new Exception("Lỗi khi cập nhật trạng thái phòng: " . $conn->error);
        }

        $delete_sql = "DELETE FROM datphongkhachsan 
                     WHERE id_phong = ? 
                     AND status IN ('pending', 'confirmed', 'checked_in')";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $id);
        $delete_stmt->execute();
        $deleted_count = $delete_stmt->affected_rows;

        $conn->commit();
        return [
            'status' => 'success',
            'message' => "Checkout thành công! Phòng đã được chuyển về trạng thái trống." . 
                         ($deleted_count > 0 ? " Đã xóa {$deleted_count} bản ghi đặt phòng." : ""),
            'rooms' => getRooms1($conn),
            'stats' => getStats1($conn),
            'room_type_stats' => getRoomTypeStats1($conn)
        ];
    } catch (Exception $e) {
        $conn->rollback();
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

// Thêm loại phòng
function addRoomType1($conn, $name_vi, $name_en, $description_vi, $description_en, $quantity, $area, $price, $images) {
    if (empty($images['name'][0])) {
        return [
            'status' => 'error',
            'message' => 'Vui lòng tải lên ít nhất một ảnh cho loại phòng.'
        ];
    }

    $conn->begin_transaction();
    try {
        $sql = "INSERT INTO loaiphongnghi (quantity, area, price) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $quantity, $area, $price);
        if (!$stmt->execute()) {
            throw new Exception("Lỗi khi thêm loại phòng: " . $conn->error);
        }

        $room_type_id = $conn->insert_id;

        $lang_sql = "INSERT INTO loaiphongnghi_ngonngu (id_loaiphongnghi, id_ngonngu, name, description) 
                     VALUES (?, 1, ?, ?)";
        $lang_stmt = $conn->prepare($lang_sql);
        $lang_stmt->bind_param("iss", $room_type_id, $name_vi, $description_vi);
        if (!$lang_stmt->execute()) {
            throw new Exception("Lỗi khi thêm thông tin ngôn ngữ tiếng Việt: " . $conn->error);
        }

        $lang_sql = "INSERT INTO loaiphongnghi_ngonngu (id_loaiphongnghi, id_ngonngu, name, description) 
                     VALUES (?, 2, ?, ?)";
        $lang_stmt = $conn->prepare($lang_sql);
        $lang_stmt->bind_param("iss", $room_type_id, $name_en, $description_en);
        if (!$lang_stmt->execute()) {
            throw new Exception("Lỗi khi thêm thông tin ngôn ngữ tiếng Anh: " . $conn->error);
        }

        $upload_dir = "../../view/img/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $uploaded_count = 0;
        for ($i = 0; $i < min(4, count($images['name'])); $i++) {
            if ($images['size'][$i] > 0) {
                $file_name = uniqid() . '_' . basename($images['name'][$i]);
                $target_path = $upload_dir . $file_name;

                if (move_uploaded_file($images['tmp_name'][$i], $target_path)) {
                    $img_sql = "INSERT INTO anhkhachsan (image, active, created_at, id_topic, id_loaiphongnghi) 
                                VALUES (?, 1, NOW(), 2, ?)";
                    $img_stmt = $conn->prepare($img_sql);
                    $img_stmt->bind_param("si", $file_name, $room_type_id);
                    if (!$img_stmt->execute()) {
                        throw new Exception("Lỗi khi thêm ảnh: " . $conn->error);
                    }
                    $uploaded_count++;
                } else {
                    throw new Exception("Lỗi khi upload ảnh: " . $images['name'][$i]);
                }
            }
        }

        $conn->commit();
        return [
            'status' => 'success',
            'message' => 'Thêm loại phòng và ảnh thành công!',
            'room_types' => getRoomTypes1($conn),
            'room_type_stats' => getRoomTypeStats1($conn)
        ];
    } catch (Exception $e) {
        $conn->rollback();
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

// Cập nhật loại phòng
function updateRoomType1($conn, $id, $name_vi, $name_en, $description_vi, $description_en, $quantity, $area, $price, $new_images, $delete_images) {
    $conn->begin_transaction();
    try {
        $sql = "UPDATE loaiphongnghi SET quantity = ?, area = ?, price = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisi", $quantity, $area, $price, $id);
        if (!$stmt->execute()) {
            throw new Exception("Lỗi khi cập nhật loại phòng: " . $conn->error);
        }

        $lang_sql = "UPDATE loaiphongnghi_ngonngu SET name = ?, description = ? 
                    WHERE id_loaiphongnghi = ? AND id_ngonngu = 1";
        $lang_stmt = $conn->prepare($lang_sql);
        $lang_stmt->bind_param("ssi", $name_vi, $description_vi, $id);
        if (!$lang_stmt->execute()) {
            throw new Exception("Lỗi khi cập nhật thông tin ngôn ngữ tiếng Việt: " . $conn->error);
        }

        $lang_sql = "UPDATE loaiphongnghi_ngonngu SET name = ?, description = ? 
                    WHERE id_loaiphongnghi = ? AND id_ngonngu = 2";
        $lang_stmt = $conn->prepare($lang_sql);
        $lang_stmt->bind_param("ssi", $name_en, $description_en, $id);
        if (!$lang_stmt->execute()) {
            throw new Exception("Lỗi khi cập nhật thông tin ngôn ngữ tiếng Anh: " . $conn->error);
        }

        if (!empty($delete_images)) {
            $placeholders = implode(',', array_fill(0, count($delete_images), '?'));
            $types = str_repeat('i', count($delete_images));

            $select_sql = "SELECT image FROM anhkhachsan WHERE id IN ($placeholders)";
            $select_stmt = $conn->prepare($select_sql);
            $select_stmt->bind_param($types, ...$delete_images);
            $select_stmt->execute();
            $result = $select_stmt->get_result();

            $images_to_delete = [];
            while ($row = $result->fetch_assoc()) {
                $images_to_delete[] = $row['image'];
            }

            $delete_sql = "DELETE FROM anhkhachsan WHERE id IN ($placeholders)";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param($types, ...$delete_images);
            $delete_stmt->execute();

            foreach ($images_to_delete as $image) {
                $file_path = "../../view/img/" . $image;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
        }

        $uploaded_count = 0;
        if (!empty($new_images['name'][0])) {
            $upload_dir = "../../view/img/";

            for ($i = 0; $i < min(4, count($new_images['name'])); $i++) {
                if ($new_images['size'][$i] > 0) {
                    $file_name = uniqid() . '_' . basename($new_images['name'][$i]);
                    $target_path = $upload_dir . $file_name;

                    if (move_uploaded_file($new_images['tmp_name'][$i], $target_path)) {
                        $img_sql = "INSERT INTO anhkhachsan (image, active, created_at, id_topic, id_loaiphongnghi) 
                                    VALUES (?, 1, NOW(), 2, ?)";
                        $img_stmt = $conn->prepare($img_sql);
                        $img_stmt->bind_param("si", $file_name, $id);
                        if (!$img_stmt->execute()) {
                            throw new Exception("Lỗi khi thêm ảnh mới: " . $conn->error);
                        }
                        $uploaded_count++;
                    } else {
                        throw new Exception("Lỗi khi upload ảnh mới: " . $new_images['name'][$i]);
                    }
                }
            }
        }

        $conn->commit();
        return [
            'status' => 'success',
            'message' => 'Cập nhật loại phòng và ảnh mới thành công!',
            'room_types' => getRoomTypes1($conn),
            'room_type_stats' => getRoomTypeStats1($conn)
        ];
    } catch (Exception $e) {
        $conn->rollback();
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

// Xóa loại phòng
function deleteRoomType1($conn, $id) {
    $check_sql = "SELECT COUNT(*) as count FROM phongkhachsan WHERE id_loaiphong = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        return [
            'status' => 'error',
            'message' => 'Không thể xóa loại phòng này vì đang có phòng sử dụng!'
        ];
    }

    $conn->begin_transaction();
    try {
        $images_sql = "SELECT image FROM anhkhachsan WHERE id_loaiphongnghi = ?";
        $images_stmt = $conn->prepare($images_sql);
        $images_stmt->bind_param("i", $id);
        $images_stmt->execute();
        $images_result = $images_stmt->get_result();

        $images_to_delete = [];
        while ($img_row = $images_result->fetch_assoc()) {
            $images_to_delete[] = $img_row['image'];
        }

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

        foreach ($images_to_delete as $image) {
            $file_path = "../../view/img/" . $image;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $conn->commit();
        return [
            'status' => 'success',
            'message' => 'Xóa loại phòng thành công!',
            'room_types' => getRoomTypes1($conn),
            'room_type_stats' => getRoomTypeStats1($conn),
            'rooms' => getRooms1($conn)
        ];
    } catch (Exception $e) {
        $conn->rollback();
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}
?>