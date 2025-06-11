<?php
require_once "session.php";
require_once "../../model/UserModel.php";

// Giả định dữ liệu bài viết
$news_posts = getNews(1); // Thay bằng getPostsByType($conn, 'news');
$offer_posts = getPromotions(1); // Thay bằng getPostsByType($conn, 'offer');
$event_posts = getEventOrganized(1); // Thay bằng getPostsByType($conn, 'event');
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Bài Viết - The Liberty Lào Cai</title>
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
                <div id="news-posts" class="post-list">
                    <?php foreach ($news_posts as $post) : ?>
                        <div class="post-card" data-post-id="<?php echo $post['id']; ?>">
                            <div class="post-image-container">
                                <img class="post-image" src="<?php echo htmlspecialchars($post['image'] ?? 'uploads/new/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                                <p class="post-date"><?php echo htmlspecialchars($post['date'] ?? '10/06/2025'); ?></p>
                            </div>
                            <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                            <p><?php $content = $post['content'];
                                echo mb_substr($content, 0, 100, 'UTF-8');
                                if (mb_strlen($content, 'UTF-8') > 100) {
                                    echo '...';
                                } ?></p>
                            <div class="post-actions">
                                <button class="action-btn hide-btn" onclick="hidePost(<?php echo $post['id']; ?>, 'news')"><i class="fas fa-eye-slash"></i> Ẩn</button>
                                <button class="action-btn edit-btn" onclick="editPost(<?php echo $post['id']; ?>, 'news')"><i class="fas fa-edit"></i> Chỉnh sửa</button>
                                <button class="action-btn delete-btn" onclick="deletePost(<?php echo $post['id']; ?>, 'news')"><i class="fas fa-trash"></i> Xóa</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
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
                <div id="offer-posts" class="post-list">
                    <?php foreach ($offer_posts as $post) : ?>
                        <div class="post-card" data-post-id="<?php echo $post['id']; ?>">
                            <div class="post-image-container">
                                <img class="post-image" src="<?php echo htmlspecialchars($post['image'] ?? 'uploads/new/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                                <p class="post-date"><?php echo htmlspecialchars($post['date'] ?? '10/06/2025'); ?></p>
                            </div>
                            <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                            <p><?php $content = $post['content'];
                                echo mb_substr($content, 0, 100, 'UTF-8');
                                if (mb_strlen($content, 'UTF-8') > 100) {
                                    echo '...';
                                } ?></p>
                            <div class="post-actions">
                                <button class="action-btn hide-btn" onclick="hidePost(<?php echo $post['id']; ?>, 'offer')"><i class="fas fa-eye-slash"></i> Ẩn</button>
                                <button class="action-btn edit-btn" onclick="editPost(<?php echo $post['id']; ?>, 'offer')"><i class="fas fa-edit"></i> Chỉnh sửa</button>
                                <button class="action-btn delete-btn" onclick="deletePost(<?php echo $post['id']; ?>, 'offer')"><i class="fas fa-trash"></i> Xóa</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
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
                <div id="event-posts" class="post-list">
                    <?php foreach ($event_posts as $post) : ?>
                        <div class="post-card" data-post-id="<?php echo $post['id']; ?>">
                            <div class="post-image-container">
                                <img class="post-image" src="<?php echo htmlspecialchars($post['image'] ?? 'uploads/new/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                                <p class="post-date"><?php echo htmlspecialchars($post['date'] ?? '10/06/2025'); ?></p>
                            </div>
                            <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                            <p><?php $content = $post['content'];
                                echo mb_substr($content, 0, 100, 'UTF-8');
                                if (mb_strlen($content, 'UTF-8') > 100) {
                                    echo '...';
                                } ?></p>
                            <div class="post-actions">
                                <button class="action-btn hide-btn" onclick="hidePost(<?php echo $post['id']; ?>, 'event')"><i class="fas fa-eye-slash"></i> Ẩn</button>
                                <button class="action-btn edit-btn" onclick="editPost(<?php echo $post['id']; ?>, 'event')"><i class="fas fa-edit"></i> Chỉnh sửa</button>
                                <button class="action-btn delete-btn" onclick="deletePost(<?php echo $post['id']; ?>, 'event')"><i class="fas fa-trash"></i> Xóa</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
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
                                <img id="image-preview" src="/libertylaocai/view/img/uploads/new/placeholder.jpg" alt="Image Preview" class="image-preview">
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
    <script>
        let editorVi, editorEn; // Biến lưu trữ CKEditor instances

        // Khởi tạo CKEditor
        document.addEventListener('DOMContentLoaded', function() {
            ClassicEditor.create(document.querySelector('#post-content-vi'), {
                ckfinder: {
                    uploadUrl: '/libertylaocai/model/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json'
                }
            }).then(editor => {
                editorVi = editor;
            }).catch(error => {
                console.error('Lỗi khởi tạo CKEditor tiếng Việt:', error);
            });

            ClassicEditor.create(document.querySelector('#post-content-en'), {
                ckfinder: {
                    uploadUrl: '/libertylaocai/model/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json'
                }
            }).then(editor => {
                editorEn = editor;
            }).catch(error => {
                console.error('Lỗi khởi tạo CKEditor tiếng Anh:', error);
            });

            // Xử lý form submit bằng AJAX
            document.getElementById('post-form').addEventListener('submit', function(e) {
                e.preventDefault(); // Ngăn hành động mặc định của form

                const formData = new FormData(this);
                const type = document.getElementById('post-type').value;
                const postId = document.getElementById('post-id').value;
                const primaryImage = document.getElementById('primary-image');

                // Kiểm tra ảnh đại diện khi thêm bài viết mới
                if (!postId && !primaryImage.files.length) {
                    alert('Vui lòng chọn ảnh đại diện cho bài viết mới!');
                    return;
                }

                console.log('Post ID being sent:', postId);
                fetch('/libertylaocai/model/config/save_post.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Lưu bài viết thành công!');
                            closeModal();
                            // Reload danh sách bài viết hiển thị
                            loadPosts(type, 1); // Hiển thị bài viết active
                        } else {
                            alert('Lỗi: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi khi lưu bài viết:', error);
                        alert('Đã xảy ra lỗi khi lưu bài viết.');
                    });
            });
        });

        // Mở tab
        function openTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            document.getElementById(tabName).classList.add('active');
            document.querySelector(`button[onclick="openTab('${tabName}')"]`).classList.add('active');
            // Load bài viết hiển thị mặc định
            loadPosts(tabName, 1);
        }

        // Mở form thêm bài viết
        function openAddForm(type) {
            document.getElementById('modal-title').textContent = 'Thêm bài viết';
            document.getElementById('post-id').value = '';
            document.getElementById('post-type').value = type;
            document.getElementById('post-title-vi').value = '';
            document.getElementById('post-title-en').value = '';
            if (editorVi) editorVi.setData('');
            if (editorEn) editorEn.setData('');
            document.getElementById('primary-image').value = '';
            document.getElementById('primary-image').setAttribute('required', 'required'); // Bắt buộc chọn ảnh khi thêm mới
            document.getElementById('image-preview').src = '/libertylaocai/view/img/uploads/new/placeholder.jpg';
            document.getElementById('post-modal').style.display = 'block';
        }

        // Chỉnh sửa bài viết
        function editPost(id, type) {
            document.getElementById('modal-title').textContent = 'Sửa bài viết';
            document.getElementById('post-id').value = id;
            document.getElementById('post-type').value = type;
            document.getElementById('primary-image').removeAttribute('required'); // Không bắt buộc ảnh khi chỉnh sửa
            fetch(`/libertylaocai/model/config/fetch_post.php?id=${id}&type=${type}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('post-title-vi').value = data.post.title_vi || '';
                        document.getElementById('post-title-en').value = data.post.title_en || '';
                        if (editorVi) editorVi.setData(data.post.content_vi || '');
                        if (editorEn) editorEn.setData(data.post.content_en || '');
                        document.getElementById('image-preview').src = data.post.image ? `/libertylaocai/view/img/${data.post.image}` : '/libertylaocai/view/img/uploads/new/placeholder.jpg';
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi lấy dữ liệu bài viết:', error);
                    alert('Đã xảy ra lỗi khi lấy dữ liệu bài viết.');
                });
            document.getElementById('post-modal').style.display = 'block';
        }

        // Hiển thị lại bài viết đã ẩn
        function showPost(id, type) {
            fetch('/libertylaocai/model/config/show_post.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id,
                        type: type
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Bài viết đã được hiển thị lại thành công!');
                        const postCard = document.querySelector(`#${type}-posts .post-card[data-post-id="${id}"]`);
                        if (postCard) postCard.remove();
                        loadPosts(type, 0); // Giữ tab ẩn để xem các bài viết khác
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi hiển thị lại bài viết:', error);
                    alert('Đã xảy ra lỗi khi hiển thị lại bài viết.');
                });
        }

        // Ẩn bài viết
        function hidePost(id, type) {
            fetch('/libertylaocai/model/config/hide_post.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id,
                        type: type
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Bài viết đã được ẩn thành công!');
                        const postCard = document.querySelector(`#${type}-posts .post-card[data-post-id="${id}"]`);
                        if (postCard) postCard.remove();
                        loadPosts(type, 1); // Trở lại tab hiển thị
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi ẩn bài viết:', error);
                    alert('Đã xảy ra lỗi khi ẩn bài viết.');
                });
        }

        // Xóa bài viết
        function deletePost(id, type) {
            if (confirm('Bạn có chắc muốn xóa bài viết này?')) {
                fetch('/libertylaocai/model/config/delete_post.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: id,
                            type: type
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Bài viết đã được xóa thành công!');
                            const postCard = document.querySelector(`#${type}-posts .post-card[data-post-id="${id}"]`);
                            if (postCard) postCard.remove();
                            const btn = document.querySelector(`#${type} .toggle-hidden-btn`);
                            const active = btn.dataset.view === 'visible' ? 1 : 0;
                            loadPosts(type, active);
                        } else {
                            alert('Lỗi: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi khi xóa bài viết:', error);
                        alert('Đã xảy ra lỗi khi xóa bài viết.');
                    });
            }
        }

        // Tìm kiếm bài viết
        function searchPosts(type) {
            const searchValue = document.getElementById(`search-${type}`).value.toLowerCase();
            document.querySelectorAll(`#${type}-posts .post-card`).forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                card.style.display = title.includes(searchValue) ? 'block' : 'none';
            });
        }

        // Load danh sách bài viết
        function loadPosts(type, active) {
            const btn = document.querySelector(`#${type} .toggle-hidden-btn`);
            btn.innerHTML = active ? '<i class="fas fa-eye-slash"></i> Xem bài viết đã ẩn' : '<i class="fas fa-eye"></i> Xem bài viết hiển thị';
            btn.dataset.view = active ? 'visible' : 'hidden';

            fetch(`/libertylaocai/model/config/fetch_posts.php?type=${type}&active=${active}&language=1`)
                .then(response => response.json())
                .then(data => {
                    const postList = document.getElementById(`${type}-posts`);
                    postList.innerHTML = '';
                    if (data.success && data.posts.length > 0) {
                        data.posts.forEach(post => {
                            const postCard = `
                                <div class="post-card" data-post-id="${post.id}">
                                    <div class="post-image-container">
                                        <img class="post-image" src="${post.image || 'uploads/new/placeholder.jpg'}" alt="${post.title}">
                                        <p class="post-date">${post.date || '10/06/2025'}</p>
                                    </div>
                                    <h3>${post.title}</h3>
                                    <p>${post.content.substring(0, 100)}${post.content.length > 100 ? '...' : ''}</p>
                                    <div class="post-actions">
                                        ${active === 0 ? `<button class="action-btn show-btn" onclick="showPost(${post.id}, '${type}')"><i class="fas fa-eye"></i> Hiển thị lại</button>` : `<button class="action-btn hide-btn" onclick="hidePost(${post.id}, '${type}')"><i class="fas fa-eye-slash"></i> Ẩn</button>`}
                                        <button class="action-btn edit-btn" onclick="editPost(${post.id}, '${type}')"><i class="fas fa-edit"></i> Chỉnh sửa</button>
                                        <button class="action-btn delete-btn" onclick="deletePost(${post.id}, '${type}')"><i class="fas fa-trash"></i> Xóa</button>
                                    </div>
                                </div>
                            `;
                            postList.insertAdjacentHTML('beforeend', postCard);
                        });
                    } else {
                        postList.innerHTML = '<p>Không tìm thấy bài viết nào.</p>';
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi lấy danh sách bài viết:', error);
                    document.getElementById(`${type}-posts`).innerHTML = '<p>Đã xảy ra lỗi khi tải danh sách bài viết.</p>';
                });
        }

        // Chuyển đổi xem bài viết ẩn/hiển thị
        function toggleHiddenPosts(type) {
            const btn = document.querySelector(`#${type} .toggle-hidden-btn`);
            const isHiddenView = btn.dataset.view === 'hidden';
            loadPosts(type, isHiddenView ? 1 : 0);
        }

        // Đóng modal
        function closeModal() {
            document.getElementById('post-modal').style.display = 'none';
        }

        // Xem trước ảnh
        document.getElementById('primary-image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>