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

function createContactRequest($subject, $message, $customerId)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO contact_requests (service, message, id_khachhang) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $subject, $message, $customerId); // s = string, i = integer
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


//Lấy ưu đãi
function getPromotions($language)
{
    global $conn;
    $promotions = [];
    $sql = "SELECT u.id, u.created_at, a.image, un.title, un.content
            FROM uudai u
            JOIN anhuudai a ON u.id = a.id_uudai
            JOIN uudai_ngonngu un ON u.id = un.id_uudai
            WHERE a.is_primary = 1 AND un.id_ngonngu = ? AND u.active = 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $language);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $promotions[] = $row;
    }

    $stmt->close();
    return $promotions;
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

//Lấy tin tức
function getNews($language)
{
    global $conn;
    $promotions = [];
    $sql = "SELECT t.id, t.create_at, a.image, tn.title, tn.content
            FROM tintuc t
            JOIN anhtintuc a ON t.id = a.id_tintuc
            JOIN tintuc_ngonngu tn ON t.id = tn.id_tintuc
            WHERE a.is_primary = 1 AND tn.id_ngonngu = ? AND t.active = 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $language);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $promotions[] = $row;
    }

    $stmt->close();
    return $promotions;
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

//Lấy tin tức
function getEventOrganized($language)
{
    global $conn;
    $promotions = [];
    $sql = "SELECT t.id, t.create_at, a.image, tn.title, tn.content
            FROM sukiendatochuc t
            JOIN anhsukiendatochuc a ON t.id = a.id_sukiendatochuc
            JOIN sukiendatochuc_ngonngu tn ON t.id = tn.id_sukiendatochuc
            WHERE a.is_primary = 1 AND tn.id_ngonngu = ? AND t.active = 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $language);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $promotions[] = $row;
    }

    $stmt->close();
    return $promotions;
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
    return $images;   // Trả về mảng chứa tất cả bản ghi
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
    // Câu truy vấn SQL để lấy danh sách các phòng khác
    $sql_other_rooms = "
        SELECT 
            lpn.id,
            lpn.quantity,
            lpn.area,
            lpn.price,
            lpnnn.name,
            lpnnn.description
        FROM loaiphongnghi lpn
        LEFT JOIN loaiphongnghi_ngonngu lpnnn ON lpn.id = lpnnn.id_loaiphongnghi
        WHERE lpn.id != ? AND (lpnnn.id_ngonngu = ? OR lpnnn.id_ngonngu IS NULL)
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
    $stmt->bind_param("iii", $room_id, $languageId, $limit);
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
