<?php
require_once '../../model/UserModel.php';
require_once 'session.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Ảnh</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/libertylaocai/view/css/quanlyanh.css" rel="stylesheet">
</head>
<body>
    <?php include "sidebar.php"; ?>

    <div id="mainContent" class="main-content">
        <div class="container">
            <div class="header">
                <h1><i class="fas fa-images"></i> Quản Lý Ảnh & Video</h1>
            </div>
            
            <div class="topics-container">
                <div class="topics-header">
                    <h2>Chọn Chủ Đề</h2>
                    <button class="arrow-btn" id="arrow-btn">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div id="topics-menu" class="topics-menu">
                    <div id="topics-grid" class="topics-grid"></div>
                </div>
            </div>
            
            <div id="management-section" class="management-section" style="display: none;">
                <div class="section-header">
                    <h2 id="current-topic-name">Quản Lý Ảnh</h2>
                </div>
                
                <div id="items-grid" class="items-grid">
                </div>
            </div>
        </div>
    </div>

    <!-- Image Viewer Modal -->
    <div id="image-viewer-modal" class="upload-modal" style="display: none;">
        <div class="image-viewer-backdrop" onclick="closeImageViewer()"></div>
        <div class="image-viewer-container">
            <div class="image-viewer-header">
                <div class="image-viewer-info">
                    <span id="image-counter">1 / 1</span>
                    <span id="image-name">Image name</span>
                </div>
                <div class="image-viewer-controls">
                    <button class="viewer-btn" onclick="zoomOut()" title="Thu nhỏ">
                        <i class="fas fa-search-minus"></i>
                    </button>
                    <button class="viewer-btn" onclick="resetZoom()" title="Kích thước gốc">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </button>
                    <button class="viewer-btn" onclick="zoomIn()" title="Phóng to">
                        <i class="fas fa-search-plus"></i>
                    </button>
                    <button class="viewer-btn" onclick="closeImageViewer()" title="Đóng">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="image-viewer-content">
                <button class="nav-btn nav-prev" onclick="previousImage()" title="Ảnh trước">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="image-container">
                    <img id="viewer-image" src="" alt="Image" loading="lazy" draggable="false">
                </div>
                <button class="nav-btn nav-next" onclick="nextImage()" title="Ảnh sau">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="image-viewer-footer">
                <div class="zoom-info">
                    <span id="zoom-level">100%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="upload-modal" class="upload-modal" style="display: none;">
        <div class="upload-modal-backdrop" onclick="closeUploadModal()"></div>
        <div class="upload-modal-container">
            <div class="upload-modal-header">
                <h3 id="upload-modal-title"></h3>
                <button class="btn-close" onclick="closeUploadModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="upload-modal-content">
                <div class="upload-section">
                    <label class="upload-label" for="file-input">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span id="upload-label-text"></span>
                        <input type="file" id="file-input" multiple accept="">
                    </label>
                </div>
                
                <div id="upload-options" class="upload-options">
                </div>
                
                <div id="selected-files" class="selected-files">
                </div>
            </div>
            <div class="upload-modal-footer">
                <button class="btn btn-secondary" onclick="closeUploadModal()">Hủy</button>
                <button class="btn btn-primary" onclick="uploadImages()" id="upload-btn" disabled>
                    <i class="fas fa-upload"></i> Tải Lên
                </button>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="/libertylaocai/view/js/quanlyanh.js"></script>
</body>
</html>