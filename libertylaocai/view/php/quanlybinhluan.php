<?php
require_once '../../model/UserModel.php';
require_once 'session.php';
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
                <input type="text" id="searchInput" placeholder="Tìm kiếm theo tên, email, nội dung bình luận hoặc dịch vụ...">
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
                <select id="rateFilter">
                    <option value="">Tất cả đánh giá</option>
                    <option value="1">1 sao</option>
                    <option value="2">2 sao</option>
                    <option value="3">3 sao</option>
                    <option value="4">4 sao</option>
                    <option value="5">5 sao</option>
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
            <div class="pagination-info"></div>
            <div class="pagination-container"></div>
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
            <div class="pagination-info"></div>
            <div class="pagination-container"></div>
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
            <div class="pagination-info"></div>
            <div class="pagination-container"></div>
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
            <div class="pagination-info"></div>
            <div class="pagination-container"></div>
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