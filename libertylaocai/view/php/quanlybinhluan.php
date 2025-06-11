<?php
require_once '../../model/config/connect.php';

// Xử lý các yêu cầu AJAX
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Thêm bình luận (giữ nguyên)
    if ($action == 'add') {
        $content = $_POST['content'];
        $rate = $_POST['rate'];
        $type = $_POST['type'];
        $id_dichvu = isset($_POST['id_dichvu']) ? $_POST['id_dichvu'] : null;
        $id_nhahang = isset($_POST['id_nhahang']) ? $_POST['id_nhahang'] : null;
        $id_loaiphong = isset($_POST['id_loaiphong']) ? $_POST['id_loaiphong'] : null;

        if (isset($_POST['id_khachhang']) && $_POST['id_khachhang']) {
            $id_khachhang = $_POST['id_khachhang'];
        } else {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $sql = "INSERT INTO khachhang (name, email) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $name, $email);
            $stmt->execute();
            $id_khachhang = $conn->insert_id;
        }

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

        echo json_encode(['status' => 'success', 'message' => 'Thêm bình luận thành công!']);
        exit;
    }

    // Ẩn/hiện nhiều bình luận
    if ($action == 'bulk_toggle_active') {
        $ids = isset($_POST['ids']) ? $_POST['ids'] : [];
        $active = $_POST['active'];
        if (!empty($ids)) {
            $ids_placeholder = implode(',', array_fill(0, count($ids), '?'));
            $sql = "UPDATE binhluan SET active = ? WHERE id IN ($ids_placeholder)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('i', count($ids) + 1), $active, ...$ids);
            $stmt->execute();
            echo json_encode(['status' => 'success', 'message' => 'Cập nhật trạng thái thành công!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng chọn ít nhất một bình luận!']);
        }
        exit;
    }

    // Xóa nhiều bình luận
    if ($action == 'bulk_delete') {
        $ids = isset($_POST['ids']) ? $_POST['ids'] : [];
        if (!empty($ids)) {
            $ids_placeholder = implode(',', array_fill(0, count($ids), '?'));

            // Xóa từ bảng binhluan_bar
            $sql = "DELETE FROM binhluan_bar WHERE id_binhluan IN ($ids_placeholder)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
            $stmt->execute();

            // Xóa từ bảng binhluan_dichvu
            $sql = "DELETE FROM binhluan_dichvu WHERE id_binhluan IN ($ids_placeholder)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
            $stmt->execute();

            // Xóa từ bảng binhluan_nhahang
            $sql = "DELETE FROM binhluan_nhahang WHERE id_binhluan IN ($ids_placeholder)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
            $stmt->execute();

            // Xóa từ bảng loaiphong_binhluan
            $sql = "DELETE FROM loaiphong_binhluan WHERE id_binhluan IN ($ids_placeholder)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
            $stmt->execute();

            // Xóa từ bảng binhluan
            $sql = "DELETE FROM binhluan WHERE id IN ($ids_placeholder)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
            $stmt->execute();

            echo json_encode(['status' => 'success', 'message' => 'Xóa các bình luận thành công!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng chọn ít nhất một bình luận!']);
        }
        exit;
    }

    // Sửa bình luận
    if ($action == 'edit') {
        $id = $_POST['id'];
        $content = $_POST['content'];
        $rate = $_POST['rate'];
        $sql = "UPDATE binhluan SET content = ?, rate = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $content, $rate, $id);
        $stmt->execute();
        echo json_encode(['status' => 'success', 'message' => 'Cập nhật bình luận thành công!']);
        exit;
    }

    // Tải lại dữ liệu cho tab
    if ($action == 'load_data') {
        $tab = $_POST['tab'];
        $subtab = isset($_POST['subtab']) ? $_POST['subtab'] : '';
        $search = isset($_POST['search']) ? $_POST['search'] : '';
        $sort = isset($_POST['sort']) ? $_POST['sort'] : 'newest';
        $date = isset($_POST['date']) ? $_POST['date'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : '';

        $html = '';
        $where_conditions = [];
        $params = [];

        if ($search) {
            $where_conditions[] = "(k.name LIKE ? OR k.email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if ($date) {
            $where_conditions[] = "DATE(b.create_at) = ?";
            $params[] = $date;
        }

        if ($status !== '') {
            $where_conditions[] = "b.active = ?";
            $params[] = $status;
        }

        $where_clause = $where_conditions ? "AND " . implode(" AND ", $where_conditions) : "";
        $order_clause = $sort == 'newest' ? "ORDER BY b.create_at DESC" : "ORDER BY b.create_at ASC";

        if ($tab == 'dichvu') {
            $type_filter = $subtab == 'tour' ? 'tour' : 'dichvu';
            $sql = "SELECT b.id, b.content, b.rate, b.active, b.create_at, k.name, k.email, dn.title 
                    FROM binhluan b 
                    JOIN khachhang k ON b.id_khachhang = k.id 
                    JOIN binhluan_dichvu bd ON b.id = bd.id_binhluan 
                    JOIN dichvu d ON bd.id_dichvu = d.id 
                    JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu 
                    WHERE d.type = '$type_filter' AND dn.id_ngonngu = 1 $where_clause $order_clause";
        } elseif ($tab == 'bar') {
            $sql = "SELECT b.id, b.content, b.rate, b.active, b.create_at, k.name, k.email 
                    FROM binhluan b 
                    JOIN khachhang k ON b.id_khachhang = k.id 
                    JOIN binhluan_bar bb ON b.id = bb.id_binhluan 
                    WHERE 1=1 $where_clause $order_clause";
        } elseif ($tab == 'nhahang') {
            $sql = "SELECT b.id, b.content, b.rate, b.active, b.create_at, k.name, k.email 
                    FROM binhluan b 
                    JOIN khachhang k ON b.id_khachhang = k.id 
                    JOIN binhluan_nhahang bn ON b.id = bn.id_binhluan 
                    WHERE 1=1 $where_clause $order_clause";
        } elseif ($tab == 'phong') {
            $room_id = $subtab == 'phongdon' ? 1 : ($subtab == 'phongdoi' ? 2 : ($subtab == 'phongtriple' ? 3 : 4));
            $sql = "SELECT b.id, b.content, b.rate, b.active, b.create_at, k.name, k.email, lpn.name AS phong_name 
                    FROM binhluan b 
                    JOIN khachhang k ON b.id_khachhang = k.id 
                    JOIN loaiphong_binhluan lpb ON b.id = lpb.id_binhluan 
                    JOIN loaiphongnghi lp ON lpb.id_loaiphong = lp.id 
                    JOIN loaiphongnghi_ngonngu lpn ON lp.id = lpn.id_loaiphongnghi 
                    WHERE lpn.id_ngonngu = 1 AND lp.id = $room_id $where_clause $order_clause";
        }

        $stmt = $conn->prepare($sql);
        if ($params) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $html .= "<tr data-name='{$row['name']}' data-email='{$row['email']}' data-date='" . date('Y-m-d', strtotime($row['create_at'])) . "'>
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
            } elseif ($tab == 'nhahang') {
                $html .= "<td>{$row['nhahang_name']}</td>";
            } elseif ($tab == 'phong') {
                $html .= "<td>{$row['phong_name']}</td>";
            }
            
            $html .= "<td>" . date('d/m/Y H:i', strtotime($row['create_at'])) . "</td>
                <td>" . ($row['active'] ? '<span class="status-active">Hiện</span>' : '<span class="status-inactive">Ẩn</span>') . "</td>
                <td>
                    <button class='btn-edit edit' data-id='{$row['id']}' data-content='" . htmlspecialchars($row['content']) . "' data-rate='{$row['rate']}'>Sửa</button>
                </td>
            </tr>";
        }

        echo json_encode(['status' => 'success', 'html' => $html]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý bình luận</title>
    <link rel="stylesheet" href="/libertylaocai/view/css/quanlybinhluan.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
        <?php include "sidebar.php"; ?>
    <div class="admin-container" id="mainContent">
        <div class="header-container">
            <h1>Quản lý bình luận</h1>
            <div class="add-comment">
                <button id="openAddModal" class="btn-primary">+ Thêm bình luận</button>
            </div>
        </div>

        <!-- Thanh tìm kiếm và bộ lọc -->
        <div class="filter-section">
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Tìm kiếm theo tên hoặc email khách hàng...">
                <button id="searchBtn" class="btn-search">Tìm kiếm</button>
            </div>
            <div class="filters">
                <select id="sortFilter">
                    <option value="newest">Mới nhất</option>
                    <option value="oldest">Cũ nhất</option>
                </select>
                <select id="statusFilter">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
                <input type="date" id="dateFilter">
                <button id="clearFilter" class="btn-clear">Xóa bộ lọc</button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-link active" data-tab="dichvu">Bình luận Dịch vụ</button>
            <button class="tab-link" data-tab="bar">Bình luận Bar</button>
            <button class="tab-link" data-tab="nhahang">Bình luận Nhà hàng</button>
            <button class="tab-link" data-tab="phong">Bình luận Phòng</button>
        </div>

        <!-- Tab Dịch vụ -->
        <div id="dichvu" class="tab-content active">
            <div class="tab-header">
                <h3>Bình luận Dịch vụ</h3>
                <div class="bulk-actions">
                    <button id="bulkToggle" class="btn-toggle">Hiển thị/Ẩn</button>
                    <button id="bulkDelete" class="btn-delete">Xóa</button>
                </div>
            </div>
            <div class="sub-tabs">
                <button class="sub-tab-link active" data-subtab="tour">Tour</button>
                <button class="sub-tab-link" data-subtab="dichvu">Dịch vụ</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>ID</th>
                            <th>Nội dung</th>
                            <th>Đánh giá</th>
                            <th>Khách hàng</th>
                            <th>Dịch vụ</th>
                            <th>Ngày tạo</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="dichvu-table-body">
                        <!-- Dữ liệu sẽ được tải bằng AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Bar -->
        <div id="bar" class="tab-content">
            <div class="tab-header">
                <h3>Bình luận Bar</h3>
                <div class="bulk-actions">
                    <button id="bulkToggle" class="btn-toggle">Hiển thị/Ẩn</button>
                    <button id="bulkDelete" class="btn-delete">Xóa</button>
                </div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>ID</th>
                            <th>Nội dung</th>
                            <th>Đánh giá</th>
                            <th>Khách hàng</th>
                            <th>Ngày tạo</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="bar-table-body">
                        <!-- Dữ liệu sẽ được tải bằng AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Nhà hàng -->
        <div id="nhahang" class="tab-content">
            <div class="tab-header">
                <h3>Bình luận Nhà hàng</h3>
                <div class="bulk-actions">
                    <button id="bulkToggle" class="btn-toggle">Hiển thị/Ẩn</button>
                    <button id="bulkDelete" class="btn-delete">Xóa</button>
                </div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>ID</th>
                            <th>Nội dung</th>
                            <th>Đánh giá</th>
                            <th>Khách hàng</th>
                            <th>Ngày tạo</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="nhahang-table-body">
                        <!-- Dữ liệu sẽ được tải bằng AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Phòng -->
        <div id="phong" class="tab-content">
            <div class="tab-header">
                <h3>Bình luận Phòng</h3>
                <div class="bulk-actions">
                    <button id="bulkToggle" class="btn-toggle">Hiển thị/Ẩn</button>
                    <button id="bulkDelete" class="btn-delete">Xóa</button>
                </div>
            </div>
            <div class="sub-tabs">
                <?php
                $result = $conn->query("SELECT lp.id, lpn.name 
                                        FROM loaiphongnghi lp 
                                        JOIN loaiphongnghi_ngonngu lpn ON lp.id = lpn.id_loaiphongnghi 
                                        WHERE lpn.id_ngonngu = 1 
                                        ORDER BY lp.id");
                $first = true;
                while ($row = $result->fetch_assoc()) {
                    $subtab = 'phong' . strtolower(str_replace(' ', '', $row['name']));
                    $activeClass = $first ? 'active' : '';
                    echo "<button class='sub-tab-link $activeClass' data-subtab='$subtab'>{$row['name']}</button>";
                    $first = false;
                }
                ?>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>ID</th>
                            <th>Nội dung</th>
                            <th>Đánh giá</th>
                            <th>Khách hàng</th>
                            <th>Loại phòng</th>
                            <th>Ngày tạo</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="phong-table-body">
                        <!-- Dữ liệu sẽ được tải bằng AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal thêm bình luận -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Thêm bình luận mới</h3>
            <form id="addCommentForm">
                <div class="form-group">
                    <label>Nội dung:</label>
                    <textarea name="content" required placeholder="Nhập nội dung bình luận..."></textarea>
                </div>
                <div class="form-group">
                    <label>Đánh giá:</label>
                    <div class="star-rating">
                        <input type="radio" id="star5" name="rate" value="5" required><label for="star5">★</label>
                        <input type="radio" id="star4" name="rate" value="4"><label for="star4">★</label>
                        <input type="radio" id="star3" name="rate" value="3"><label for="star3">★</label>
                        <input type="radio" id="star2" name="rate" value="2"><label for="star2">★</label>
                        <input type="radio" id="star1" name="rate" value="1"><label for="star1">★</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Khách hàng:</label>
                    <select name="id_khachhang" id="customerSelect">
                        <option value="">Nhập thủ công</option>
                        <?php
                        $result = $conn->query("SELECT DISTINCT id, name, email FROM khachhang GROUP BY name, email ORDER BY name");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id']}' data-name='{$row['name']}' data-email='{$row['email']}'>{$row['name']} ({$row['email']})</option>";
                        }
                        ?>
                    </select>
                </div>
                <div id="manualCustomer" class="form-group">
                    <label>Tên khách hàng:</label>
                    <input type="text" name="name" placeholder="Nhập tên khách hàng">
                    <label>Email khách hàng:</label>
                    <input type="email" name="email" placeholder="Nhập email khách hàng">
                </div>
                <div class="form-group">
                    <label>Loại bình luận:</label>
                    <select name="type" id="commentType" required>
                        <option value="">Chọn loại bình luận</option>
                        <option value="dichvu">Dịch vụ</option>
                        <option value="bar">Bar</option>
                        <option value="nhahang">Nhà hàng</option>
                        <option value="phong">Phòng</option>
                    </select>
                </div>
                <div id="dichvuSelect" class="form-group" style="display: none;">
                    <label>Dịch vụ/Tour:</label>
                    <select name="id_dichvu">
                        <option value="">Chọn dịch vụ</option>
                        <?php
                        $result = $conn->query("SELECT d.id, dn.title, d.type FROM dichvu d 
                                                JOIN dichvu_ngonngu dn ON d.id = dn.id_dichvu 
                                                WHERE dn.id_ngonngu = 1 ORDER BY d.type, dn.title");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['title']} ({$row['type']})</option>";
                        }
                        ?>
                    </select>
                </div>
                <div id="phongSelect" class="form-group" style="display: none;">
                    <label>Loại phòng:</label>
                    <select name="id_loaiphong">
                        <option value="">Chọn loại phòng</option>
                        <?php
                        $result = $conn->query("SELECT lp.id, lpn.name FROM loaiphongnghi lp 
                                                JOIN loaiphongnghi_ngonngu lpn ON lp.id = lpn.id_loaiphongnghi 
                                                WHERE lpn.id_ngonngu = 1 ORDER BY lpn.name");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Thêm bình luận</button>
                    <button type="button" class="btn-cancel" onclick="$('#addModal').hide()">Hủy</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal chỉnh sửa -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Chỉnh sửa bình luận</h3>
            <form id="editCommentForm">
                <input type="hidden" name="id">
                <div class="form-group">
                    <label>Nội dung:</label>
                    <textarea name="content" required></textarea>
                </div>
                <div class="form-group">
                    <label>Đánh giá:</label>
                    <div class="star-rating">
                        <input type="radio" id="edit_star5" name="rate" value="5" required><label for="edit_star5">★</label>
                        <input type="radio" id="edit_star4" name="rate" value="4"><label for="edit_star4">★</label>
                        <input type="radio" id="edit_star3" name="rate" value="3"><label for="edit_star3">★</label>
                        <input type="radio" id="edit_star2" name="rate" value="2"><label for="edit_star2">★</label>
                        <input type="radio" id="edit_star1" name="rate" value="1"><label for="edit_star1">★</label>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Lưu thay đổi</button>
                    <button type="button" class="btn-cancel" onclick="$('#editModal').hide()">Hủy</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading indicator -->
    <div id="loading" class="loading">
        <div class="spinner"></div>
        <p>Đang tải...</p>
    </div>

    <!-- Thông báo -->
    <div id="notification" class="notification">
        <span id="notificationMessage"></span>
        <button id="closeNotification">&times;</button>
    </div>

    <script src="/libertylaocai/view/js/quanlybinhluan.js"></script>
</body>
</html>