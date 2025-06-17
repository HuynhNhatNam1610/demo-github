<?php
require_once "session.php";
require_once "../../model/UserModel.php";

// Lấy dữ liệu ban đầu
$room_types = getRoomTypes1($conn);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Loại Phòng - The Liberty Lào Cai</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/libertylaocai/view/css/quanlyphong.css">
    <!-- CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script src="/libertylaocai/model/ckfinder/ckfinder.js"></script>
</head>

<body>
    <?php include "sidebar.php"; ?>
    <div class="main-content">
        <div class="container">
            <!-- Quản lý loại phòng -->
            <div id="room-type-management">
                <div class="section-header">
                    <h2><i class="fas fa-list"></i> Danh Sách Loại Phòng</h2>
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

        <!-- Modal thêm loại phòng -->
        <div id="addRoomTypeModal" class="modal">
            <div class="modal-content" style="max-width: 800px;">
                <span class="close" onclick="closeModal('addRoomTypeModal')">×</span>
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
                <span class="close" onclick="closeModal('editRoomTypeModal')">×</span>
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
                            <input type="text" id="edit_room_type_price" name="price" class="form-control" required>
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

                    <!-- Phần Loại Giường -->
                    <div class="form-group">
                        <label>Loại Giường</label>
                        <div id="bed-types-container" class="price-input-container"></div>
                        <button type="button" class="btn btn-success add-bed-type-btn" onclick="addBedTypeInput()">+ Thêm giường</button>
                    </div>

                    <!-- Phần Tiện Ích -->
                    <div class="form-group">
                        <label>Tiện Ích</label>
                        <div id="amenities-container" class="price-input-container"></div>
                        <button type="button" class="btn btn-success add-amenity-btn" onclick="addAmenityInput()">+ Thêm tiện ích</button>
                    </div>

                    <!-- Phần Ảnh Hiện Tại -->
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
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Cập Nhật
                    </button>
                </form>
            </div>
        </div>

        <!-- Form ẩn để xóa loại phòng -->
        <form id="deleteRoomTypeForm" method="POST" style="display: none;">
            <input type="hidden" name="action" value="delete_room_type">
            <input type="hidden" name="room_type_id" id="delete_room_type_id">
        </form>

        <script src="/libertylaocai/view/js/quanlyphong.js"></script>
    </div>
</body>

</html>