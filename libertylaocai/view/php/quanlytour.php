<?php
require_once "session.php";
require_once "../../model/UserModel.php";
error_reporting(E_ALL);
ini_set('error_log', 'debug.log');
$id_ngonngu = 1; // Ngôn ngữ tiếng Việt
$id_dichvu = isset($_SESSION['id_quanlytour']) ? (int)$_SESSION['id_quanlytour'] : 0;

// Lấy dữ liệu từ controller
$data = getTourData($id_dichvu, $id_ngonngu);
$tours = $data['tours'];
$selected_tour = $data['selected_tour'];
$images = $data['images'];
$tour_description = $data['tour_description'];

ob_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Dịch vụ - Liberty Lào Cai</title>
    <link rel="stylesheet" href="/libertylaocai/view/css/quanlytour.css">
    <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script src="/libertylaocai/model/ckfinder/ckfinder.js"></script>
</head>

<body>
    <?php include "sidebar.php"; ?>

    <div class="admin-container">
        <header class="admin-header">
            <h1><i class="fas fa-map-marked-alt"></i> Quản lý Dịch vụ</h1>
        </header>

        <div class="admin-content">
            <!-- Tour Selection -->
            <div class="section-card">
                <div class="section-header">
                    <h2><i class="fas fa-list"></i> Chọn Dịch vụ</h2>
                </div>
                <div class="section-content">
                    <div class="tour-grid">
                        <?php foreach ($tours as $tour): ?>
                            <form action="/libertylaocai/user/submit" method="POST" style="display: inline">
                                <input type="hidden" name="id_quanlytour" value="<?= $tour['id'] ?>">
                                <div class="tour-item <?php echo $tour['id'] == $id_dichvu ? 'active' : ''; ?>">
                                    <div class="tour-info">
                                        <h3><?php echo htmlspecialchars($tour['title']); ?></h3>
                                    </div>
                                    <div class="tour-footer">
                                        <div class="tour-price">
                                            <?php
                                            if (preg_match('/^\d+[.,]?\d*$/', $tour['price'])) {
                                                echo number_format($tour['price'], 0, ',', '.') . ' VNĐ';
                                            } else {
                                                echo htmlspecialchars($tour['price']);
                                            }
                                            ?>
                                        </div>
                                        <div class="tour-actions">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <?php if ($id_dichvu > 0 && $selected_tour): ?>
                <!-- Tour Info Management -->
                <div class="section-card">
                    <div class="section-header">
                        <h2><i class="fas fa-info-circle"></i> Thông tin cơ bản</h2>
                    </div>
                    <div class="section-content">
                        <form class="form-grid" id="tourInfoForm">
                            <input type="hidden" name="action" value="update_tour_detail">
                            <input type="hidden" name="id_dichvu" value="<?php echo $id_dichvu; ?>">
                            <!-- Tiếng Việt -->
                            <input type="hidden" name="id_ngonngu_vi" value="1">
                            <div class="form-group">
                                <label>Tiêu đề dịch vụ (Tiếng Việt)</label>
                                <input type="text" name="title_vi"  value="<?php echo htmlspecialchars($selected_tour['title']); ?>" required>
                            </div>
                            <!-- Tiếng Anh -->
                            <input type="hidden" name="id_ngonngu_en" value="2">
                            <div class="form-group">
                                <label for="edit_service_title_en">Tiêu đề dịch vụ (Tiếng Anh):</label>
                                <input type="text" name="title_en"  value="<?php
                                                                            if (isset($id_dichvu)) {
                                                                                $serviceContent = getServiceContentById($id_dichvu, 2);
                                                                                echo $serviceContent ? htmlspecialchars($serviceContent['title']) : '';
                                                                            }
                                                                            ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Giá dịch vụ</label>
                                <input type="text" name="price" value="<?php echo htmlspecialchars($selected_tour['price']); ?>"
                                    placeholder="Ví dụ: 1500000 hoặc Liên hệ">
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Cập nhật
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Description Management -->
                <div class="section-card">
                    <div class="section-header">
                        <h2><i class="fas fa-file-alt"></i> Mô tả dịch vụ</h2>
                    </div>
                    <div class="section-content">
                        <form class="form-grid" id="descriptionForm">
                            <input type="hidden" name="action" value="update_description">
                            <input type="hidden" name="id_dichvu" value="<?php echo $id_dichvu; ?>">
                            <!-- Tiếng Việt -->
                            <input type="hidden" name="id_ngonngu_vi" value="1">
                            <div class="form-group full-width">
                                <label>Mô tả dịch vụ (Tiếng Việt, mỗi đoạn văn cách nhau bằng dòng trống)</label>
                                <textarea name="content_vi" id="content_vi" rows="8" placeholder="Nhập mô tả dịch vụ, mỗi đoạn văn cách nhau bằng dòng trống..."><?php echo $tour_description ? htmlspecialchars($tour_description['content']) : ''; ?></textarea>
                            </div>
                            <!-- Tiếng Anh -->
                            <input type="hidden" name="id_ngonngu_en" value="2">
                            <div class="form-group full-width">
                                <label>Mô tả dịch vụ (Tiếng Anh, mỗi đoạn văn cách nhau bằng dòng trống)</label>
                                <textarea name="content_en" id="content_en" rows="8" placeholder="Enter service description, each paragraph separated by a blank line..."><?php
                                                                                                                                                            if (isset($id_dichvu)) {
                                                                                                                                                                // $serviceContent = getServiceContentById($id_dichvu, 2);
                                                                                                                                                                echo $serviceContent ? htmlspecialchars($serviceContent['content']) : '';
                                                                                                                                                            }
                                                                                                                                                            ?></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Cập nhật
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Image Management -->
                <div class="section-card">
                    <div class="section-header">
                        <h2><i class="fas fa-images"></i> Quản lý ảnh</h2>
                        <button class="btn btn-primary" onclick="toggleForm('imageForm')">
                            <i class="fas fa-plus"></i> Thêm ảnh mới
                        </button>
                    </div>
                    <div class="section-content">
                        <!-- Add Image Form -->
                        <div class="form-container" id="imageForm" style="display: none;">
                            <form class="form-grid" id="addImageForm" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="add_image">
                                <input type="hidden" name="id_dichvu" value="<?php echo $id_dichvu; ?>">
                                <input type="hidden" name="id_topic" value="3">
                                <div class="form-group full-width">
                                    <label>Chọn ảnh <span class="required">*</span></label>
                                    <div class="upload-area">
                                        <div class="upload-icon">📷</div>
                                        <div class="upload-text">
                                            Nhấp để tải lên hình ảnh<br>
                                            <small>Có thể tải lên nhiều hình ảnh (tối đa 5)</small>
                                        </div>
                                        <input type="file" id="imageUpload" name="images[]" multiple accept="image/*">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Ảnh chính</label>
                                    <input type="checkbox" name="is_primary" value="1">
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="btn btn-secondary" onclick="toggleForm('imageForm')">Hủy</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> Tải lên
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- Image List -->
                        <div class="image-grid">
                            <?php foreach ($images as $image): ?>
                                <div class="image-item image-preview-item">
                                    <img src="<?php echo htmlspecialchars($image['image']); ?>" alt="Service Image">
                                    <div class="image-overlay">
                                        <span class="image-name"><?php echo htmlspecialchars($image['image']); ?></span>
                                        <button class="remove-btn" onclick="deleteImage(<?php echo $image['id']; ?>, '<?php echo htmlspecialchars($image['image']); ?>')">×</button>
                                    </div>
                                    <?php if ($image['is_primary']): ?>
                                        <span class="primary-badge">Ảnh chính</span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                            <?php if (empty($images)): ?>
                                <div class="empty-state">
                                    <i class="fas fa-images"></i>
                                    <p>Chưa có ảnh nào</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Đang xử lý...</p>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <div class="toast-content">
            <i class="fas fa-check-circle"></i>
            <span class="toast-message"></span>
        </div>
    </div>

    <script src="/libertylaocai/view/js/quanlytour.js"></script>
</body>

</html>
<?php
$current_tab = 'tour-management';
$tab_content = ob_get_clean();
include 'tabdichvu.php';
?>