<?php
require_once "session.php";
require_once "../../model/UserModel.php";

$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Bài Viết - The Liberty Lào Cai</title>
    <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/libertylaocai/view/css/quanlybaiviet.css">
    <!-- CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script src="/libertylaocai/model/ckfinder/ckfinder.js"></script>
</head>

<body>
    <?php include "sidebar.php"; ?>

    <div class="main-content" id="mainContent">
        <div class="container">
            <h1>Quản Lý Bài Viết</h1>

            <!-- Tab Navigation -->
            <div class="tabs">
                <button class="tab-button active" onclick="openTab('news')">Tin tức</button>
                <button class="tab-button" onclick="openTab('offer')">Ưu đãi</button>
                <button class="tab-button" onclick="openTab('event')">Sự kiện đã tổ chức</button>
            </div>

            <!-- Tab Content: Quản lý tin tức -->
            <div id="news" class="tab-content active">
                <div class="tab-header">
                    <div class="search-wrapper">
                        <input type="text" id="search-news" placeholder="Tìm kiếm tin tức..." onkeyup="searchPosts('news')">
                        <button class="search-btn" onclick="searchPosts('news')"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="header-buttons">
                        <button class="add-post-btn" onclick="openAddForm('news')"><i class="fas fa-plus"></i> Thêm bài viết</button>
                        <button class="toggle-hidden-btn" data-view="visible" onclick="toggleHiddenPosts('news')"><i class="fas fa-eye-slash"></i> Xem bài viết đã ẩn</button>
                    </div>
                </div>
                <div id="news-posts" class="post-list"></div>
                <div id="pagination-news" class="pagination"></div>
            </div>

            <!-- Tab Content: Quản lý ưu đãi -->
            <div id="offer" class="tab-content">
                <div class="tab-header">
                    <div class="search-wrapper">
                        <input type="text" id="search-offer" placeholder="Tìm kiếm ưu đãi..." onkeyup="searchPosts('offer')">
                        <button class="search-btn" onclick="searchPosts('offer')"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="header-buttons">
                        <button class="add-post-btn" onclick="openAddForm('offer')"><i class="fas fa-plus"></i> Thêm bài viết</button>
                        <button class="toggle-hidden-btn" data-view="visible" onclick="toggleHiddenPosts('offer')"><i class="fas fa-eye-slash"></i> Xem bài viết đã ẩn</button>
                    </div>
                </div>
                <div id="offer-posts" class="post-list"></div>
                <div id="pagination-offer" class="pagination"></div>
            </div>

            <!-- Tab Content: Quản lý sự kiện -->
            <div id="event" class="tab-content">
                <div class="tab-header">
                    <div class="search-wrapper">
                        <input type="text" id="search-event" placeholder="Tìm kiếm sự kiện..." onkeyup="searchPosts('event')">
                        <button class="search-btn" onclick="searchPosts('event')"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="header-buttons">
                        <button class="add-post-btn" onclick="openAddForm('event')"><i class="fas fa-plus"></i> Thêm sự kiện</button>
                        <button class="toggle-hidden-btn" data-view="visible" onclick="toggleHiddenPosts('event')"><i class="fas fa-eye-slash"></i> Xem bài viết đã ẩn</button>
                    </div>
                </div>
                <div id="event-posts" class="post-list"></div>
                <div id="pagination-event" class="pagination"></div>
            </div>

            <!-- Add/Edit Post Modal -->
            <div id="post-modal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" onclick="closeModal()">×</span>
                    <h2 id="modal-title">Thêm bài viết</h2>
                    <form id="post-form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="post-id" name="post_id">
                        <input type="hidden" id="post-type" name="post_type">

                        <!-- Image Upload Section -->
                        <div class="form-group image-upload-group">
                            <label for="primary-image">Ảnh đại diện</label>
                            <div class="image-upload-wrapper">
                                <input type="file" id="primary-image" name="primary_image" accept="image/*">
                                <img id="image-preview" src="/libertylaocai/view/img/uploads/new/place_holder.jpg" alt="Image Preview" class="image-preview">
                            </div>
                        </div>

                        <!-- Vietnamese Content -->
                        <div class="form-group language-section">
                            <h3 class="language-title">Tiếng Việt</h3>
                            <label for="post-title-vi">Tiêu đề (Tiếng Việt)</label>
                            <input type="text" id="post-title-vi" name="title_vi" required>
                            <label for="post-content-vi">Nội dung (Tiếng Việt)</label>
                            <textarea id="post-content-vi" name="content_vi"></textarea>
                        </div>

                        <!-- English Content -->
                        <div class="form-group language-section">
                            <h3 class="language-title">Tiếng Anh</h3>
                            <label for="post-title-en">Tiêu đề (Tiếng Anh)</label>
                            <input type="text" id="post-title-en" name="title_en" required>
                            <label for="post-content-en">Nội dung (Tiếng Anh)</label>
                            <textarea id="post-content-en" name="content_en"></textarea>
                        </div>

                        <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Lưu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="/libertylaocai/view/js/quanlybaiviet.js"></script>
</body>

</html>