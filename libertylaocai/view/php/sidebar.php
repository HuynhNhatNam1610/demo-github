<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liberty Lào Cai - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/libertylaocai/view/css/sidebar.css">
</head>

<body>
    <!-- Fixed Toggle Button -->
    <button class="fixed-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>

    <!-- Sidebar Component -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <img src="/libertylaocai/view/img/Logoliberty.jpg" alt="Liberty Logo"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div style="display: none; width: 40px; height: 40px; background: #66bb6a; border-radius: 50%; align-items: center; justify-content: center; color: white; font-weight: bold;">L</div>
            </div>
            <div class="sidebar-title">Liberty Lào Cai</div>
        </div>

        <div class="sidebar-nav">
            <a href="/libertylaocai/quan-ly-anh" class="nav-item" data-page="dashboard">
                <span class="nav-icon"><i class="bi bi-camera"></i></span>
                <span>Quản lý ảnh</span>
            </a>
            <a href="/libertylaocai/quan-ly-lich-dat" class="nav-item" data-page="bookings">
                <span class="nav-icon"><i class="bi bi-calendar-check"></i></span>
                <span>Quản lý lịch đặt</span>
            </a>
            <a href="/libertylaocai/quan-ly-phong" class="nav-item" data-page="rooms">
                <span class="nav-icon"><i class="bi bi-building"></i></span>
                <span>Quản lý phòng</span>
            </a>
            <a href="/libertylaocai/quan-ly-thong-tin" class="nav-item" data-page="events">
                <span class="nav-icon"><i class="bi bi-calendar-event"></i></span>
                <span>Quản lý thông tin</span>
            </a>
            <a href="/libertylaocai/quan-ly-binh-luan" class="nav-item" data-page="comment">
                <span class="nav-icon"><i class="bi bi-chat"></i></span>
                <span>Quản lý bình luận</span>
            </a>
            <a href="/libertylaocai/quan-ly-bai-viet" class="nav-item" data-page="tus">
                <span class="nav-icon"><i class="bi bi-file-text"></i></span>
                <span>Quản lý bài viết</span>
            </a>
            <a href="/libertylaocai/quan-ly-menu" class="nav-item" data-page="menu">
                <span class="nav-icon"><i class="bi bi-journal-text"></i></span>
                <span>Quản lý menu</span>
            </a>
            <a href="/libertylaocai/quan-ly-dich-vu" class="nav-item" data-page="reports">
                <span class="nav-icon"><i class="bi bi-graph-up"></i></span>
                <span>Quản lý dịch vụ</span>
            </a>
            <a href="/libertylaocai/tai-khoan" class="nav-item" data-page="settings">
                <span class="nav-icon"><i class="bi bi-gear"></i></span>
                <span>Cài đặt</span>
            </a>
            <a href="/libertylaocai/trangchu" class="nav-item">
                <span class="nav-icon"><i class="bi bi-box-arrow-right"></i></span>
                <span>Đăng xuất</span>
            </a>
        </div>
    </nav>
    <script src="/libertylaocai/view/js/sidebar.js"></script>
</body>

</html>