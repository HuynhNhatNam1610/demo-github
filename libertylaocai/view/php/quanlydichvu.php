
<?php
require_once "session.php";
require_once "../../model/UserModel.php";
$icons = layDanhSachIcon();
$features_result = getFeaturesByLanguage(1, 'dichvu');
$services_result = getServices1();
$tours_result = getTours();
if(!isset($_SESSION['authenticated'])){
    header("location: /libertylaocai/dang-nhap");
}
ob_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Dịch Vụ - Liberty Lào Cai</title>
    <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
    <link rel="stylesheet" href="/libertylaocai/view/css/quanlydichvu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Thêm CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script src="/libertylaocai/model/ckfinder/ckfinder.js"></script>
    <!-- Thêm jQuery và JS Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        /* Tùy chỉnh Select2 để hiển thị chỉ biểu tượng */
        .select2-container .select2-selection--single {
            height: 38px;
            line-height: 38px;
            padding: 0 10px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .select2-results__option i {
            font-size: 16px;
            vertical-align: middle;
        }

        .select2-selection__rendered i {
            font-size: 16px;
            vertical-align: middle;
        }

        .select2-results__option {
            padding: 6px 10px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #5897fb;
            color: white;
        }
    </style>
</head>

<body>
    <?php include "sidebar.php"; ?>

    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-cogs"></i> Quản Lý Dịch Vụ Du Lịch</h1>
            <div class="admin-nav">
                <a href="/libertylaocai/dich-vu" target="_blank" class="btn btn-preview">
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

            <!-- Quản lý Dịch Vụ -->
            <section class="admin-section">
                <div class="section-header">
                    <h2><i class="fas fa-concierge-bell"></i> Quản Lý Dịch Vụ</h2>
                    <button class="btn btn-primary" onclick="openServiceTourModal('service')">
                        <i class="fas fa-plus"></i> Thêm Dịch Vụ
                    </button>
                </div>

                <div class="services-grid">
                    <?php
                    foreach ($services_result as $service):
                    ?>
                        <div class="service-item">
                            <div class="service-header">
                                <h3><?php echo htmlspecialchars($service['title_vi']); ?></h3>
                                <div class="service-actions">
                                    <button class="btn btn-small btn-secondary" onclick="editService(<?php echo htmlspecialchars(json_encode($service)); ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="delete_service">
                                        <input type="hidden" name="id_dichvu" value="<?php echo $service['id_dichvu']; ?>">
                                        <button type="submit" class="btn btn-small btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="service-content">
                                <p><strong>Giá:</strong> <?php echo htmlspecialchars($service['price']); ?></p>
                                <?php if ($service['image']): ?>
                                    <img src="<?php echo htmlspecialchars($service['image']); ?>" alt="<?php echo htmlspecialchars($service['title_vi']); ?>" class="service-image-preview">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Quản lý Tour -->
            <section class="admin-section">
                <div class="section-header">
                    <h2><i class="fas fa-map-marked-alt"></i> Quản Lý Tour Du Lịch</h2>
                    <button class="btn btn-primary" onclick="openServiceTourModal('tour')">
                        <i class="fas fa-plus"></i> Thêm Tour
                    </button>
                </div>

                <div class="tours-grid">
                    <?php foreach ($tours_result as $tour): ?>
                        <div class="tour-item">
                            <div class="tour-header">
                                <h3><?php echo htmlspecialchars($tour['title_vi']); ?></h3>
                                <div class="tour-actions">
                                    <button class="btn btn-small btn-secondary" onclick="editTour(<?php echo htmlspecialchars(json_encode($tour)); ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="delete_tour">
                                        <input type="hidden" name="id_dichvu" value="<?php echo $tour['id_dichvu']; ?>">
                                        <button type="submit" class="btn btn-small btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="tour-content">
                                <p><strong>Tiếng Việt:</strong> <?php echo $tour['content_vi']; ?></p>
                                <?php if ($tour['title_en'] || $tour['content_en']): ?>
                                    <p><strong>Tiếng Anh:</strong> <?php echo $tour['content_en']; ?></p>
                                <?php endif; ?>
                                <?php if ($tour['image']): ?>
                                    <img src="<?php echo htmlspecialchars($tour['image']); ?>" alt="<?php echo htmlspecialchars($tour['title_vi']); ?>" class="tour-image-preview">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Quản lý Tiện Ích -->
            <section class="admin-section">
                <div class="section-header">
                    <h2><i class="fas fa-star"></i> Quản Lý Tiện Ích</h2>
                    <button class="btn btn-primary" onclick="openModal('featureModal')">
                        <i class="fas fa-plus"></i> Thêm Tiện Ích
                    </button>
                </div>
                <div class="tours-grid">
                    <?php foreach ($features_result as $feature): ?>
                        <div class="tour-item">
                            <div class="tour-header">
                                <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
                                <div class="tour-actions">
                                    <button class="btn btn-small btn-secondary" onclick="editFeature(<?php echo htmlspecialchars(json_encode($feature)); ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" style="display: inline;">
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
                                <p>Nội dung: <?php echo $feature['content']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
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
                            <label for="feature_title_vi">Tiêu đề tiện ích (Tiếng Việt):</label>
                            <input type="text" name="title_vi" id="featureTitle_vi" required>
                        </div>
                        <div class="form-group">
                            <label for="feature_title_en">Tiêu đề tiện ích (Tiếng Anh):</label>
                            <input type="text" name="title_en" id="featureTitle_en">
                        </div>
                        <div class="form-group">
                            <label for="feature_icon">Biểu tượng (Icon):</label>
                            <div class="icon-select-container">
                                <select id="featureIconSelect" onchange="updateIcon()" class="icon-select">
                                    <option value="" data-icon="">-- Chọn biểu tượng --</option>
                                    <?php foreach ($icons as $icon): ?>
                                        <option value="<?php echo htmlspecialchars($icon); ?>" data-icon="<?php echo htmlspecialchars($icon); ?>"></option>
                                    <?php endforeach; ?>
                                    <option value="custom" data-icon="fa-pencil-alt">Custom Icon</option>
                                </select>
                                <input type="text" id="featureIconCustom" style="display: none;" placeholder="Nhập lớp CSS của biểu tượng">
                                <input type="hidden" name="icon" id="featureIcon">
                                <span id="iconPreview" class="icon-preview"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="feature_content_vi">Nội dung tiện ích (Tiếng Việt):</label>
                            <textarea name="content_vi" id="featureContent_vi" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="feature_content_en">Nội dung tiện ích (Tiếng Anh):</label>
                            <textarea name="content_en" id="featureContent_en" rows="4"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('featureModal')">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Thêm Dịch Vụ/Tour -->
            <div id="serviceTourModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="serviceTourModalTitle">Thêm Dịch Vụ/Tour Mới</h3>
                        <span class="close" onclick="closeModal('serviceTourModal')">×</span>
                    </div>
                    <form id="serviceTourForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add_service" id="serviceTourAction">
                        <input type="hidden" name="id_dichvu" id="serviceTourId">
                        <div class="form-group">
                            <label for="service_tour_title_vi">Tiêu đề (Tiếng Việt):</label>
                            <input type="text" name="title_vi" id="serviceTourTitle_vi" required>
                        </div>
                        <div class="form-group">
                            <label for="service_tour_title_en">Tiêu đề (Tiếng Anh):</label>
                            <input type="text" name="title_en" id="serviceTourTitle_en">
                        </div>
                        <div class="form-group">
                            <label for="service_tour_content_vi">Nội dung mô tả (Tiếng Việt):</label>
                            <textarea name="content_vi" id="serviceTourContent_vi" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="service_tour_content_en">Nội dung mô tả (Tiếng Anh):</label>
                            <textarea name="content_en" id="serviceTourContent_en" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="service_tour_price_vi">Giá:</label>
                            <input type="text" name="price_vi" id="serviceTourPrice_vi" value="Liên hệ" required>
                        </div>
                        <div class="form-group">
                            <label for="service_tour_image">Hình ảnh:</label>
                            <input type="file" name="service_image" id="serviceTourImage" accept="image/*">
                            <div id="currentServiceTourImage"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('serviceTourModal')">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal Chỉnh Sửa Dịch Vụ -->
            <div id="editServiceModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="editServiceModalTitle">Chỉnh Sửa Dịch Vụ</h3>
                        <span class="close" onclick="closeModal('editServiceModal')">×</span>
                    </div>
                    <form id="editServiceForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="update_service" id="editServiceAction">
                        <input type="hidden" name="id_dichvu" id="editServiceId">
                        <div class="form-group">
                            <label for="edit_service_title_vi">Tiêu đề dịch vụ (Tiếng Việt):</label>
                            <input type="text" name="title_vi" id="editServiceTitle_vi" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_service_title_en">Tiêu đề dịch vụ (Tiếng Anh):</label>
                            <input type="text" name="title_en" id="editServiceTitle_en">
                        </div>
                        <div class="form-group">
                            <label for="edit_service_content_vi">Nội dung (Tiếng Việt):</label>
                            <textarea name="content_vi" id="editServiceContent_vi" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_service_content_en">Nội dung (Tiếng Anh):</label>
                            <textarea name="content_en" id="editServiceContent_en" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_service_price_vi">Giá:</label>
                            <input type="text" name="price_vi" id="editServicePrice_vi" value="Liên hệ" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_service_image">Hình ảnh dịch vụ:</label>
                            <input type="file" name="service_image" id="editServiceImage" accept="image/*">
                            <div id="currentEditServiceImage"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('editServiceModal')">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="/libertylaocai/view/js/quanlydichvu.js"></script>
</body>

</html>
<?php
$current_tab = 'tour-service';
$tab_content = ob_get_clean();
include 'tabdichvu.php'; // Điều chỉnh đường dẫn nếu cần
?>