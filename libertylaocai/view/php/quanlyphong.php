<?php
require_once "session.php";
require_once "../../model/UserModel.php";

// Lấy dữ liệu ban đầu
$rooms = getRooms($conn);
$room_types = getRoomTypes1($conn);
$stats = getStats($conn);
$room_type_stats = getRoomTypeStats($conn);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Phòng - The Liberty Lào Cai</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/libertylaocai/view/css/quanlyphong.css">
</head>

<body>
    <?php include "sidebar.php"; ?>
    <div class="main-content">
        <div class="container">
            <!-- Header -->
            <div class="tabs">
                <button class="tab-btn active" onclick="openTab('room-management')">Quản lý Phòng</button>
                <button class="tab-btn" onclick="openTab('room-type-management')">Quản lý Loại Phòng</button>
            </div>

            <!-- Thống kê -->
            <div class="stats-grid">
                <div class="stat-card total">
                    <i class="fas fa-door-open"></i>
                    <div class="stat-number"><?php echo $stats['total_rooms']; ?></div>
                    <div>Tổng số phòng</div>
                </div>
                <div class="stat-card available">
                    <i class="fas fa-check-circle"></i>
                    <div class="stat-number"><?php echo $stats['available_rooms']; ?></div>
                    <div>Phòng trống</div>
                </div>
                <div class="stat-card reserved">
                    <i class="fas fa-calendar-check"></i>
                    <div class="stat-number"><?php echo $stats['reserved_rooms']; ?></div>
                    <div>Phòng đã đặt</div>
                </div>
                <div class="stat-card maintenance">
                    <i class="fas fa-tools"></i>
                    <div class="stat-number"><?php echo $stats['maintenance_rooms']; ?></div>
                    <div>Bảo trì</div>
                </div>
            </div>

            <!-- Thống kê theo loại phòng -->
            <div class="room-type-stats">
                <div class="section-header">
                    <h2><i class="fas fa-chart-pie"></i> Thống Kê Theo Loại Phòng</h2>
                </div>
                <div style="overflow-x: auto;">
                    <table class="rooms-table">
                        <thead>
                            <tr>
                                <th>Loại Phòng</th>
                                <th>Số Lượng Định Mức</th>
                                <th>Số Phòng Thực Tế</th>
                                <th>Phòng Trống</th>
                                <th>Tỷ Lệ Sử Dụng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($room_type_stats as $stat): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($stat['name'] ?? 'Chưa xác định'); ?></strong></td>
                                    <td><?php echo $stat['total_quantity']; ?></td>
                                    <td><?php echo $stat['actual_rooms']; ?></td>
                                    <td><?php echo $stat['available_count']; ?></td>
                                    <td>
                                        <?php
                                        $usage_rate = $stat['total_quantity'] > 0 ?
                                            round(($stat['actual_rooms'] - $stat['available_count']) / $stat['total_quantity'] * 100, 1) : 0;
                                        echo $usage_rate . '%';
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab nội dung -->
            <div id="room-management" class="tab-content" style="display: block;">

                <!-- Form thêm/sửa phòng -->
                <div class="add-room-section">
                    <div class="section-header">
                        <h2><i class="fas fa-cog"></i> Quản Lý Nhanh</h2>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div class="form-group">
                            <label>Thay đổi trạng thái hàng loạt:</label>
                            <select class="form-control" id="bulkStatus">
                                <option value="">Chọn trạng thái...</option>
                                <option value="available">Trống</option>
                                <option value="pending">Đang chờ</option>
                                <option value="reserved">Đã đặt</option>
                                <option value="maintenance">Bảo trì</option>
                            </select>
                        </div>
                        <button class="btn btn-primary" onclick="bulkUpdateStatus()">
                            <i class="fas fa-sync"></i> Cập nhật trạng thái
                        </button>

                        <hr style="margin: 1.5rem 0;">
                        <div class="form-group">
                            <label>Tìm kiếm số phòng hoặc số điện thoại:</label>
                            <input type="text" class="form-control" id="searchRoom" placeholder="Nhập số phòng hoặc số điện thoại..." oninput="searchRooms()">
                        </div>

                        <div class="form-group">
                            <label>Lọc theo trạng thái:</label>
                            <select class="form-control" onchange="filterRooms(this.value)">
                                <option value="">Tất cả</option>
                                <option value="available">Phòng trống</option>
                                <option value="pending">Đang chờ</option>
                                <option value="reserved">Đã đặt</option>
                                <option value="maintenance">Bảo trì</option>
                            </select>
                        </div>

                        <!-- Phần lọc loại phòng -->
                        <div class="form-group">
                            <label>Lọc theo loại phòng:</label>
                            <select class="form-control" onchange="filterRoomsByType(this.value)">
                                <option value="">Tất cả loại</option>
                                <?php foreach ($room_types as $type): ?>
                                    <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['languages'][1]['name'] ?? 'Chưa xác định'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Danh sách phòng -->
                <div class="rooms-section">
                    <div class="section-header">
                        <h2><i class="fas fa-list"></i> Danh Sách Phòng</h2>
                        <button class="btn btn-primary" onclick="openAddModal()">
                            <i class="fas fa-plus"></i> Thêm Phòng
                        </button>
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="rooms-table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll()"></th>
                                    <th>Số Phòng</th>
                                    <th>Loại Phòng</th>
                                    <th>Giá</th>
                                    <th>Diện Tích</th>
                                    <th>Số Điện Thoại</th> <!-- Thêm cột này -->
                                    <th>Trạng Thái</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rooms as $room): ?>
                                    <tr>
                                        <td><input type="checkbox" name="selected_rooms[]" value="<?php echo $room['id']; ?>"></td>
                                        <td><strong><?php echo htmlspecialchars($room['room_number']); ?></strong></td>
                                        <td data-room-type-id="<?php echo $room['id_loaiphong']; ?>">
                                            <?php echo htmlspecialchars($room['room_type_name'] ?? 'Chưa xác định'); ?>
                                        </td>
                                        <td><?php echo number_format($room['price']); ?> VNĐ</td>
                                        <td><?php echo $room['area']; ?> m²</td>
                                        <td><?php echo htmlspecialchars($room['phone'] ?? ''); ?></td> <!-- Hiển thị số điện thoại -->
                                        <td>
                                            <span class="status-badge status-<?php echo $room['status']; ?>">
                                                <?php
                                                switch ($room['status']) {
                                                    case 'available':
                                                        echo 'Trống';
                                                        break;
                                                    case 'pending':
                                                        echo 'Đang chờ';
                                                        break; // Add this case
                                                    case 'reserved':
                                                        echo 'Đã đặt';
                                                        break;
                                                    case 'maintenance':
                                                        echo 'Bảo trì';
                                                        break;
                                                    default:
                                                        echo $room['status'];
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-warning btn-small" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($room)); ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-small" onclick="confirmDelete(<?php echo $room['id']; ?>, '<?php echo htmlspecialchars($room['room_number']); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab quản lý loại phòng -->
            <div id="room-type-management" class="tab-content">
                <div class="section-header">
                    <h2><i class="fas fa-list"></i> Danh Sách Loại Phòng</h2>
                    <button class="btn btn-primary" onclick="openAddRoomTypeModal()">
                        <i class="fas fa-plus"></i> Thêm Loại Phòng
                    </button>
                </div>

                <div style="overflow-x: auto;">
                    <table class="rooms-table">
                        <thead>
                            <tr>
                                <th>Tên Loại Phòng</th>
                                <th>Mô Tả</th>
                                <th>Giá</th>
                                <th>Diện Tích</th>
                                <th>Số Lượng</th>
                                <th>Số Ảnh</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($room_types as $type): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($type['name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars(substr($type['description'], 0, 50) . (strlen($type['description']) > 50 ? '...' : '')); ?></td>
                                    <td><?php echo number_format($type['price']); ?> VNĐ</td>
                                    <td><?php echo $type['area']; ?> m²</td>
                                    <td><?php echo $type['quantity']; ?></td>
                                    <td><?php echo $type['image_count']; ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-small" onclick="openEditRoomTypeModal(<?php echo htmlspecialchars(json_encode($type)); ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-small" onclick="confirmDeleteRoomType(<?php echo $type['id']; ?>, '<?php echo htmlspecialchars($type['name']); ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal thêm phòng -->
        <div id="addModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('addModal')">&times;</span>
                <h2><i class="fas fa-plus"></i> Thêm Phòng Mới</h2>
                <form method="POST" id="addRoomForm">
                    <input type="hidden" name="action" value="add_room">

                    <div class="form-group">
                        <label for="room_number">Số Phòng:</label>
                        <input type="text" id="room_number" name="room_number" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="id_loaiphong">Loại Phòng:</label>
                        <select id="id_loaiphong" name="id_loaiphong" class="form-control" required>
                            <option value="">Chọn loại phòng...</option>
                            <?php foreach ($room_types as $type): ?>
                                <option value="<?php echo $type['id']; ?>">
                                    <?php echo htmlspecialchars($type['languages'][1]['name']); ?> - <?php echo number_format($type['price']); ?> VNĐ
                                    (Số lượng hiện tại: <?php echo $type['quantity']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Trạng Thái:</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="available">Trống</option>
                            <option value="pending">Đang chờ</option>
                            <option value="reserved">Đã đặt</option>
                            <option value="maintenance">Bảo trì</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Thêm Phòng
                    </button>
                </form>
            </div>
        </div>

        <!-- Modal sửa phòng -->

        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('editModal')">&times;</span>
                <h2><i class="fas fa-edit"></i> Sửa Thông Tin Phòng</h2>
                <form method="POST" id="editRoomForm">
                    <input type="hidden" name="action" value="update_room">
                    <input type="hidden" name="room_id" id="edit_room_id">

                    <div class="form-group">
                        <label for="edit_room_number">Số Phòng:</label>
                        <input type="text" id="edit_room_number" name="room_number" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_id_loaiphong">Loại Phòng:</label>
                        <select id="edit_id_loaiphong" name="id_loaiphong" class="form-control" required>
                            <?php foreach ($room_types as $type): ?>
                                <option value="<?php echo $type['id']; ?>">
                                    <?php echo htmlspecialchars($type['languages'][1]['name']); ?> - <?php echo number_format($type['price']); ?> VNĐ
                                    (Số lượng: <?php echo $type['quantity']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_status">Trạng Thái:</label>
                        <select id="edit_status" name="status" class="form-control" required>
                            <option value="available">Trống</option>
                            <option value="pending">Đang chờ</option>
                            <option value="reserved">Đã đặt</option>
                            <option value="maintenance">Bảo trì</option>
                        </select>
                    </div>

                    <!-- Thêm trường số điện thoại -->
                    <div class="form-group">
                        <label for="edit_phone">Số điện thoại:</label>
                        <input type="text" id="edit_phone" name="phone" class="form-control" placeholder="Nhập số điện thoại">
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Cập Nhật
                    </button>
                </form>
            </div>
        </div>

        <!-- Modal thêm loại phòng -->
        <div id="addRoomTypeModal" class="modal">
            <div class="modal-content" style="max-width: 800px;">
                <span class="close" onclick="closeModal('addRoomTypeModal')">&times;</span>
                <h2><i class="fas fa-plus"></i> Thêm Loại Phòng Mới</h2>
                <form method="POST" enctype="multipart/form-data" id="addRoomTypeForm">
                    <input type="hidden" name="action" value="add_room_type">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="room_type_name_vi">Tên Loại Phòng (Tiếng Việt):</label>
                            <input type="text" id="room_type_name_vi" name="name_vi" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="room_type_name_en">Tên Loại Phòng (Tiếng Anh):</label>
                            <input type="text" id="room_type_name_en" name="name_en" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="room_type_description_vi">Mô Tả (Tiếng Việt):</label>
                            <textarea id="room_type_description_vi" name="description_vi" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="room_type_description_en">Mô Tả (Tiếng Anh):</label>
                            <textarea id="room_type_description_en" name="description_en" class="form-control" rows="4"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="room_type_price">Giá (VNĐ):</label>
                            <input type="text" id="room_type_price" name="price" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="room_type_quantity">Số Lượng:</label>
                            <input type="number" id="room_type_quantity" name="quantity" class="form-control" required min="1">
                        </div>

                        <div class="form-group">
                            <label for="room_type_area">Diện Tích (m²):</label>
                            <input type="number" id="room_type_area" name="area" class="form-control" required min="1">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="room_type_images">Ảnh Loại Phòng (Tối đa 4 ảnh):</label>
                        <div class="upload-area">
                            <div class="upload-icon">📷</div>
                            <div class="upload-text">
                                Nhấp để tải lên ảnh loại phòng<br>
                                <small>Có thể tải lên tối đa 4 ảnh</small>
                            </div>
                            <input type="file" id="room_type_images" name="images[]" multiple accept="image/*">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Thêm Loại Phòng
                    </button>
                </form>
            </div>
        </div>

        <!-- Modal sửa loại phòng -->
        <div id="editRoomTypeModal" class="modal">
            <div class="modal-content" style="max-width: 800px;">
                <span class="close" onclick="closeModal('editRoomTypeModal')">&times;</span>
                <h2><i class="fas fa-edit"></i> Sửa Loại Phòng</h2>
                <form method="POST" enctype="multipart/form-data" id="editRoomTypeForm">
                    <input type="hidden" name="action" value="update_room_type">
                    <input type="hidden" name="room_type_id" id="edit_room_type_id">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_room_type_name_vi">Tên Loại Phòng (Tiếng Việt):</label>
                            <input type="text" id="edit_room_type_name_vi" name="name_vi" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_room_type_name_en">Tên Loại Phòng (Tiếng Anh):</label>
                            <input type="text" id="edit_room_type_name_en" name="name_en" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_room_type_description_vi">Mô Tả (Tiếng Việt):</label>
                            <textarea id="edit_room_type_description_vi" name="description_vi" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="edit_room_type_description_en">Mô Tả (Tiếng Anh):</label>
                            <textarea id="edit_room_type_description_en" name="description_en" class="form-control" rows="4"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_room_type_price">Giá (VNĐ):</label>
                            <input type="text" id="edit_room_type_price" name="price" class="form-control" required onblur="formatCurrency(this)" onfocus="handleCurrencyFocus(this)">
                        </div>

                        <div class="form-group">
                            <label for="edit_room_type_quantity">Số Lượng:</label>
                            <input type="number" id="edit_room_type_quantity" name="quantity" class="form-control" required min="1">
                        </div>

                        <div class="form-group">
                            <label for="edit_room_type_area">Diện Tích (m²):</label>
                            <input type="number" id="edit_room_type_area" name="area" class="form-control" required min="1">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Ảnh Hiện Tại:</label>
                        <div id="current-images-container" class="images-preview"></div>
                    </div>

                    <div class="form-group">
                        <label>Thêm Ảnh Mới:</label>
                        <div class="upload-area">
                            <div class="upload-icon">📷</div>
                            <div class="upload-text">
                                Nhấp để tải lên ảnh mới<br>
                                <small>Có thể tải lên tối đa 4 ảnh (JPG, PNG)</small>
                            </div>
                            <input type="file" id="edit_room_type_images" name="new_images[]" multiple accept="image/jpeg,image/png">
                        </div>
                        <div id="edit-new-images-preview" class="images-preview"></div>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Cập Nhật
                    </button>
                </form>
            </div>
        </div>
    </div>
    </div>
    <!-- Form ẩn để xóa loại phòng -->
    <form id="deleteRoomTypeForm" method="POST" style="display: none;">
        <input type="hidden" name="action" value="delete_room_type">
        <input type="hidden" name="room_type_id" id="delete_room_type_id">
    </form>

    <!-- Form ẩn để xóa phòng -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="action" value="delete_room">
        <input type="hidden" name="room_id" id="delete_room_id">
    </form>

    <script src="/libertylaocai/view/js/quanlyphong.js"></script>
</body>

</html>