<?php
require_once "session.php";
require_once "../../model/UserModel.php";
if (!isset($_SESSION['authenticated'])) {
    header("location: /libertylaocai/dang-nhap");
}
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;
$restaurant_items = getMenu(1, 1);
$bar_food_items = getMenuBar(1, 'main');
$bar_drink_items = getMenuBar(1, 'cocktails');
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Menu - The Liberty Lào Cai</title>
    <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/libertylaocai/view/css/quanlymenu.css">
    <!-- CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script src="/libertylaocai/model/ckfinder/ckfinder.js"></script>
</head>

<body>
    <?php include "sidebar.php"; ?>

    <div class="main-content" id="mainContent">
        <div class="container">
            <h1>Quản Lý Menu</h1>

            <!-- Tab Navigation -->
            <div class="tabs">
                <button class="tab-button active" onclick="openTab('restaurant')">Nhà hàng</button>
                <button class="tab-button" onclick="openTab('bar')">Bar</button>
                <button class="tab-button" onclick="openTab('tour_menu')">Thực đơn</button>
            </div>

            <!-- Nhà hàng Tab -->
            <div id="restaurant" class="tab-content active">
                <div class="tab-header">
                    <div class="search-wrapper">
                        <input type="text" id="search-restaurant" placeholder="Tìm kiếm món ăn..." onkeyup="searchItems('restaurant')">
                        <button class="search-btn" onclick="searchItems('restaurant')"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="header-buttons">
                        <button class="add-post-btn" onclick="openAddForm('restaurant')"><i class="fas fa-plus"></i> Thêm món ăn</button>
                        <button class="toggle-hidden-btn" data-view="visible" onclick="toggleHiddenItems('restaurant')"><i class="fas fa-eye-slash"></i> Xem món đã ẩn</button>
                    </div>
                </div>
                <div id="restaurant-items" class="post-list"></div>
                <div id="pagination-restaurant" class="pagination"></div>
            </div>

            <!-- Bar Tab -->
            <div id="bar" class="tab-content">
                <div class="sub-tabs">
                    <button class="sub-tab-btn active" onclick="openSubTab('bar_food')">Đồ ăn</button>
                    <button class="sub-tab-btn" onclick="openSubTab('bar_drink')">Đồ uống</button>
                </div>

                <div id="bar_food" class="sub-tab-content active">
                    <div class="tab-header">
                        <div class="search-wrapper">
                            <input type="text" id="search-bar_food" placeholder="Tìm kiếm đồ ăn..." onkeyup="searchItems('food')">
                            <button class="search-btn" onclick="searchItems('food')"><i class="fas fa-search"></i></button>
                        </div>
                        <div class="header-buttons">
                            <button class="add-post-btn" onclick="openAddForm('bar_food')"><i class="fas fa-plus"></i> Thêm đồ ăn</button>
                            <button class="toggle-hidden-btn" data-view="visible" onclick="toggleHiddenItems('food')"><i class="fas fa-eye-slash"></i> Xem món đã ẩn</button>
                        </div>
                    </div>
                    <div id="food-items" class="post-list"></div>
                    <div id="pagination-food" class="pagination"></div>
                </div>

                <div id="bar_drink" class="sub-tab-content">
                    <div class="tab-header">
                        <div class="search-wrapper">
                            <input type="text" id="search-bar_drink" placeholder="Tìm kiếm đồ uống..." onkeyup="searchItems('drink')">
                            <button class="search-btn" onclick="searchItems('drink')"><i class="fas fa-search"></i></button>
                        </div>
                        <div class="header-buttons">
                            <button class="add-post-btn" onclick="openAddForm('bar_drink')"><i class="fas fa-plus"></i> Thêm đồ uống</button>
                            <button class="toggle-hidden-btn" data-view="visible" onclick="toggleHiddenItems('drink')"><i class="fas fa-eye-slash"></i> Xem món đã ẩn</button>
                        </div>
                    </div>
                    <div id="drink-items" class="post-list"></div>
                    <div id="pagination-drink" class="pagination"></div>
                </div>
            </div>

            <!-- Thực đơn Tab -->
            <div id="tour_menu" class="tab-content">
                <div class="sub-tabs">
                    <button class="sub-tab-btn active" onclick="openSubTab('tour')">Tour</button>
                    <button class="sub-tab-btn" onclick="openSubTab('hoinghi')">Hội nghị</button>
                    <button class="sub-tab-btn" onclick="openSubTab('sinhnhat')">Sinh nhật</button>
                    <button class="sub-tab-btn" onclick="openSubTab('gala')">Gala</button>
                    <button class="sub-tab-btn" onclick="openSubTab('tieccuoi')">Tiệc cưới</button>
                </div>

                <div id="tour" class="sub-tab-content active">
                    <div class="tab-header">
                        <div class="search-wrapper">
                            <input type="text" id="search-tour" placeholder="Tìm kiếm thực đơn..." onkeyup="searchItems('tour')">
                            <button class="search-btn" onclick="searchItems('tour')"><i class="fas fa-search"></i></button>
                        </div>
                        <div class="header-buttons">
                            <button class="add-post-btn" onclick="openAddForm('tour')"><i class="fas fa-plus"></i> Thêm thực đơn</button>
                            <button class="toggle-hidden-btn" data-view="visible" onclick="toggleHiddenItems('tour')"><i class="fas fa-eye-slash"></i> Xem thực đơn đã ẩn</button>
                        </div>
                    </div>
                    <div id="tour-items" class="post-list"></div>
                    <div id="pagination-tour" class="pagination"></div>
                </div>

                <div id="hoinghi" class="sub-tab-content">
                    <div class="tab-header">
                        <div class="search-wrapper">
                            <input type="text" id="search-hoinghi" placeholder="Tìm kiếm thực đơn..." onkeyup="searchItems('hoinghi')">
                            <button class="search-btn" onclick="searchItems('hoinghi')"><i class="fas fa-search"></i></button>
                        </div>
                        <div class="header-buttons">
                            <button class="add-post-btn" onclick="openAddForm('hoinghi')"><i class="fas fa-plus"></i> Thêm thực đơn</button>
                            <button class="toggle-hidden-btn" data-view="visible" onclick="toggleHiddenItems('hoinghi')"><i class="fas fa-eye-slash"></i> Xem thực đơn đã ẩn</button>
                        </div>
                    </div>
                    <div id="hoinghi-items" class="post-list"></div>
                    <div id="pagination-hoinghi" class="pagination"></div>
                </div>

                <div id="sinhnhat" class="sub-tab-content">
                    <div class="tab-header">
                        <div class="search-wrapper">
                            <input type="text" id="search-sinhnhat" placeholder="Tìm kiếm thực đơn..." onkeyup="searchItems('sinhnhat')">
                            <button class="search-btn" onclick="searchItems('sinhnhat')"><i class="fas fa-search"></i></button>
                        </div>
                        <div class="header-buttons">
                            <button class="add-post-btn" onclick="openAddForm('sinhnhat')"><i class="fas fa-plus"></i> Thêm thực đơn</button>
                            <button class="toggle-hidden-btn" data-view="visible" onclick="toggleHiddenItems('sinhnhat')"><i class="fas fa-eye-slash"></i> Xem thực đơn đã ẩn</button>
                        </div>
                    </div>
                    <div id="sinhnhat-items" class="post-list"></div>
                    <div id="pagination-sinhnhat" class="pagination"></div>
                </div>

                <div id="gala" class="sub-tab-content">
                    <div class="tab-header">
                        <div class="search-wrapper">
                            <input type="text" id="search-gala" placeholder="Tìm kiếm thực đơn..." onkeyup="searchItems('gala')">
                            <button class="search-btn" onclick="searchItems('gala')"><i class="fas fa-search"></i></button>
                        </div>
                        <div class="header-buttons">
                            <button class="add-post-btn" onclick="openAddForm('gala')"><i class="fas fa-plus"></i> Thêm thực đơn</button>
                            <button class="toggle-hidden-btn" data-view="visible" onclick="toggleHiddenItems('gala')"><i class="fas fa-eye-slash"></i> Xem thực đơn đã ẩn</button>
                        </div>
                    </div>
                    <div id="gala-items" class="post-list"></div>
                    <div id="pagination-gala" class="pagination"></div>
                </div>

                <div id="tieccuoi" class="sub-tab-content">
                    <div class="tab-header">
                        <div class="search-wrapper">
                            <input type="text" id="search-tieccuoi" placeholder="Tìm kiếm thực đơn..." onkeyup="searchItems('tieccuoi')">
                            <button class="search-btn" onclick="searchItems('tieccuoi')"><i class="fas fa-search"></i></button>
                        </div>
                        <div class="header-buttons">
                            <button class="add-post-btn" onclick="openAddForm('tieccuoi')"><i class="fas fa-plus"></i> Thêm thực đơn</button>
                            <button class="toggle-hidden-btn" data-view="visible" onclick="toggleHiddenItems('tieccuoi')"><i class="fas fa-eye-slash"></i> Xem thực đơn đã ẩn</button>
                        </div>
                    </div>
                    <div id="tieccuoi-items" class="post-list"></div>
                    <div id="pagination-tieccuoi" class="pagination"></div>
                </div>
            </div>

            <!-- Add/Edit Menu Item Modal -->
            <div id="menu-modal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" onclick="closeModal()">×</span>
                    <h2 id="modal-title">Thêm thực đơn</h2>
                    <form id="menu-item-form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="post-id" name="post_id">
                        <input type="hidden" id="post-type" name="type">

                        <!-- Image Upload Section -->
                        <div class="form-group image-upload-group">
                            <label for="primary-image">Ảnh đại diện</label>
                            <div class="image-upload-wrapper">
                                <input type="file" id="primary-image" name="image" accept="image/*">
                                <img id="image-preview" src="/libertylaocai/view/img/logoliberty.jpg" alt="Image Preview" class="image-preview">
                            </div>
                        </div>

                        <!-- Price and Outstanding Group -->
                        <div class="price-outstanding-group" id="price-outstanding-group">
                            <div class="form-group price-group" id="price-group">
                                <label for="price-input">Giá (VNĐ)</label>
                                <input type="text" id="price-input" name="price" class="price-input">
                            </div>
                            <div class="form-group outstanding-group" id="outstanding-group">
                                <label>
                                    <input type="checkbox" id="outstanding" name="outstanding" value="1">
                                    Đặt làm nổi bật
                                </label>
                            </div>
                        </div>

                        <!-- Vietnamese Content -->
                        <div class="form-group language-section">
                            <h3 class="language-title">Tiếng Việt</h3>
                            <label for="item-title-vi">Tiêu đề (Tiếng Việt)</label>
                            <input type="text" id="item-title-vi" name="title_vi" required>
                            <label for="post-content-vi">Nội dung (Tiếng Việt)</label>
                            <textarea id="post-content-vi" name="content_vi"></textarea>
                        </div>

                        <!-- English Content -->
                        <div class="form-group language-section">
                            <h3 class="language-title">Tiếng Anh</h3>
                            <label for="item-title-en">Tiêu đề (Tiếng Anh)</label>
                            <input type="text" id="item-title-en" name="title_en" required>
                            <label for="post-content-en">Nội dung (Tiếng Anh)</label>
                            <textarea id="post-content-en" name="content_en"></textarea>
                        </div>

                        <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Lưu</button>
                    </form>
                </div>
            </div>
        </div>

        <script src="/libertylaocai/view/js/quanlymenu.js"></script>
    </div>
</body>

</html>