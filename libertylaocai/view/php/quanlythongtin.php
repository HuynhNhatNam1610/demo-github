<?php
require_once "session.php";
require_once "../../model/UserModel.php";

$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;

// Lấy dữ liệu từ các hàm
$restaurant_info = getAmThucNgonNgu($languageId, 1); // Nhà hàng (id_amthuc = 1)
$bar_info = getAmThucNgonNgu($languageId, 2); // Bar (id_amthuc = 2)
$conference_rooms = getConferenceRooms($languageId);
$restaurant_images = getRestaurantImages();

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Thông Tin - The Liberty Lào Cai</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/libertylaocai/view/css/quanlymenu.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script src="/libertylaocai/model/ckfinder/ckfinder.js"></script>
</head>

<body>
    <?php include "sidebar.php"; ?>

    <div class="main-content" id="mainContent">
        <div class="container">
            <h1>Quản Lý Thông Tin</h1>

            <!-- Tab Navigation -->
            <div class="tabs">
                <button class="tab-button active" onclick="openTab('conference')">Hội trường</button>
                <button class="tab-button" onclick="openTab('restaurant')">Nhà hàng</button>
                <button class="tab-button" onclick="openTab('bar')">Bar</button>
                <button class="tab-button" onclick="openTab('terms')">Điều khoản</button>
                <button class="tab-button" onclick="openTab('introduction')">Giới thiệu</button>
                <button class="tab-button" onclick="openTab('description')">Mô tả</button>
            </div>

            <!-- Hội trường Tab -->
            <div id="conference" class="tab-content active">
                <div class="tab-header">
                    <div class="search-wrapper">
                        <input type="text" id="search-conference" placeholder="Tìm kiếm hội trường..." onkeyup="searchItems('conference')">
                        <button class="search-btn" onclick="searchItems('conference')"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="header-buttons">
                        <button class="add-post-btn" onclick="openAddForm('conference')"><i class="fas fa-plus"></i> Thêm hội trường</button>
                        <button class="toggle-hidden-btn" data-view="visible" onclick="toggleHiddenItems('conference')"><i class="fas fa-eye-slash"></i> Xem hội trường đã ẩn</button>
                    </div>
                </div>
                <div id="conference-items" class="post-list"></div>
                <div id="pagination-conference" class="pagination"></div>
            </div>

            <!-- Nhà hàng Tab -->
            <div id="restaurant" class="tab-content">
                <div class="tab-header">
                    <div class="search-wrapper">
                        <input type="text" id="search-restaurant" placeholder="Tìm kiếm nhà hàng..." onkeyup="searchItems('restaurant')">
                        <button class="search-btn" onclick="searchItems('restaurant')"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="header-buttons">
                        <button class="add-post-btn" onclick="editItem(1, 'restaurant')"><i class="fas fa-edit"></i> Chỉnh sửa thông tin</button>
                    </div>
                </div>
                <div id="restaurant-items" class="post-list"></div>
                <div id="pagination-restaurant" class="pagination"></div>
            </div>

            <!-- Bar Tab -->
            <div id="bar" class="tab-content">
                <div class="tab-header">
                    <div class="search-wrapper">
                        <input type="text" id="search-bar" placeholder="Tìm kiếm bar..." onkeyup="searchItems('bar')">
                        <button class="search-btn" onclick="searchItems('bar')"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="header-buttons">
                        <button class="add-post-btn" onclick="editItem(2, 'bar')"><i class="fas fa-edit"></i> Chỉnh sửa thông tin</button>
                    </div>
                </div>
                <div id="bar-items" class="post-list"></div>
                <div id="pagination-bar" class="pagination"></div>
            </div>

            <!-- Điều khoản Tab -->
            <div id="terms" class="tab-content">
                <div class="tab-header">
                    <div class="search-wrapper">
                        <input type="text" id="search-terms" placeholder="Tìm kiếm điều khoản..." onkeyup="searchItems('terms')">
                        <button class="search-btn" onclick="searchItems('terms')"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="header-buttons">
                        <button class="add-post-btn" onclick="editItem(1, 'terms')"><i class="fas fa-edit"></i> Chỉnh sửa điều khoản</button>
                    </div>
                </div>
                <div id="terms-items" class="post-list"></div>
                <div id="pagination-terms" class="pagination"></div>
            </div>

            <!-- Giới thiệu Tab -->
            <div id="introduction" class="tab-content">
                <div class="tab-header">
                    <div class="search-wrapper">
                        <input type="text" id="search-introduction" placeholder="Tìm kiếm giới thiệu..." onkeyup="searchItems('introduction')">
                        <button class="search-btn" onclick="searchItems('introduction')"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="header-buttons">
                        <button class="add-post-btn" onclick="openAddForm('introduction')"><i class="fas fa-plus"></i> Thêm giới thiệu</button>
                        <button class="toggle-hidden-btn" data-view="visible" onclick="toggleHiddenItems('introduction')"><i class="fas fa-eye-slash"></i> Xem giới thiệu đã ẩn</button>
                    </div>
                </div>
                <div id="introduction-items" class="post-list"></div>
                <div id="pagination-introduction" class="pagination"></div>
            </div>

            <!-- Mô tả Tab -->
            <div id="description" class="tab-content">
                <div class="tab-header">
                    <div class="search-wrapper">
                        <input type="text" id="search-description" placeholder="Tìm kiếm mô tả..." onkeyup="searchItems('description')">
                        <button class="search-btn" onclick="searchItems('description')"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="header-buttons">
                        <!-- Không có nút Thêm hoặc Chỉnh sửa cố định -->
                    </div>
                </div>
                <div id="description-items" class="post-list"></div>
                <div id="pagination-description" class="pagination"></div>
            </div>

            <!-- Add/Edit Modal -->
            <div id="info-modal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" onclick="closeModal()">×</span>
                    <h2 id="modal-title">Chỉnh sửa thông tin</h2>
                    <form id="info-form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="post-id" name="post_id">
                        <input type="hidden" id="post-type" name="type">

                        <!-- Image Upload Section -->
                        <div id="image-upload-section" class="form-group image-upload-group">
                            <label for="primary-image">Ảnh</label>
                            <div class="upload-area">
                                <div class="upload-icon">📷</div>
                                <div class="upload-text">
                                    Nhấp để tải lên hình ảnh<br><small>Có thể tải lên nhiều hình ảnh (tối đa 5)</small>
                                </div>
                                <input type="file" id="primary-image" name="image[]" multiple accept="image/*">
                            </div>
                            <div id="image-preview-container" class="images-grid"></div>
                        </div>

                        <!-- Conference Room Specific Fields -->
                        <div id="conference-fields" style="display: none;">
                            <div class="form-group">
                                <label for="room-number">Số phòng</label>
                                <input type="text" id="room-number" name="room_number" readonly>
                            </div>
                            <div class="form-group">
                                <label for="prices">Giá thuê</label>
                                <div id="price-list">
                                    <div class="price-item">
                                        <input type="text" name="how_long[]" placeholder="Thời gian (VD: 4h, 8h)">
                                        <input type="number" name="price_value[]" placeholder="Giá (VNĐ)">
                                        <button type="button" class="remove-price-btn"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                                <button type="button" class="add-price-btn"><i class="fas fa-plus"></i> Thêm giá</button>
                            </div>
                        </div>

                        <!-- Vietnamese Content -->
                        <div class="form-group language-section">
                            <h3 class="language-title">Tiếng Việt</h3>
                            <label for="item-title-vi">Tiêu đề</label>
                            <input type="text" id="item-title-vi" name="title_vi">
                            <div id="content-vi-section" style="display: none;">
                                <label for="item-content-vi">Nội dung ngắn</label>
                                <input type="text" id="item-content-vi" name="content_vi">
                            </div>
                            <label for="post-description-vi">Nội dung (CKEditor)</label>
                            <textarea id="post-description-vi" name="description_vi"></textarea>
                        </div>

                        <!-- English Content -->
                        <div class="form-group language-section">
                            <h3 class="language-title">Tiếng Anh</h3>
                            <label for="item-title-en">Tiêu đề</label>
                            <input type="text" id="item-title-en" name="title_en">
                            <div id="content-en-section" style="display: none;">
                                <label for="item-content-en">Nội dung ngắn</label>
                                <input type="text" id="item-content-en" name="content_en">
                            </div>
                            <label for="post-description-en">Nội dung (CKEditor)</label>
                            <textarea id="post-description-en" name="description_en"></textarea>
                        </div>

                        <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Lưu</button>
                    </form>
                </div>
            </div>
        </div>

        <script src="/libertylaocai/view/js/quanlythongtin.js"></script>
    </div>
</body>

</html>