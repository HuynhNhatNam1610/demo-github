<?php
// tabdichvu.php
// File này chứa giao diện và logic cho hệ thống tab với các tab: Dịch Vụ Du Lịch, Đưa Đón Sân Bay, Tour, Giấy Thông Hành
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .admin-tabs {
            margin-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
            display: flex;
            gap: 10px;
        }
        .admin-tabs .tab-link {
            padding: 10px 20px;
            cursor: pointer;
            font-weight: bold;
            color: #333;
            background: #f5f5f5;
            border-radius: 5px 5px 0 0;
            transition: all 0.3s;
        }
        .admin-tabs .tab-link.active {
            background: #007bff;
            color: #fff;
            border-bottom: 2px solid #007bff;
        }
        .admin-tabs .tab-link:hover {
            background: #e0e0e0;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-tabs">
            <div class="tab-link <?php echo $current_tab === 'tour-service' ? 'active' : ''; ?>" data-tab="tour-service">
                <i class="fas fa-concierge-bell"></i> Quản Lý Dịch Vụ Du Lịch
            </div>
            <div class="tab-link <?php echo $current_tab === 'airport-shuttle' ? 'active' : ''; ?>" data-tab="airport-shuttle">
                <i class="fas fa-plane"></i> Quản Lý Đưa Đón Sân Bay
            </div>
            <div class="tab-link <?php echo $current_tab === 'tour-management' ? 'active' : ''; ?>" data-tab="tour-management">
                <i class="fas fa-map-marked-alt"></i> Quản Lý Tour
            </div>
            <div class="tab-link <?php echo $current_tab === 'passport-management' ? 'active' : ''; ?>" data-tab="passport-management">
                <i class="fas fa-passport"></i> Quản Lý Giấy Thông Hành
            </div>
        </div>

        <div id="tour-service" class="tab-content <?php echo $current_tab === 'tour-service' ? 'active' : ''; ?>">
            <?php if ($current_tab === 'tour-service') echo $tab_content; ?>
        </div>
        <div id="airport-shuttle" class="tab-content <?php echo $current_tab === 'airport-shuttle' ? 'active' : ''; ?>">
            <?php if ($current_tab === 'airport-shuttle') echo $tab_content; ?>
        </div>
        <div id="tour-management" class="tab-content <?php echo $current_tab === 'tour-management' ? 'active' : ''; ?>">
            <?php if ($current_tab === 'tour-management') echo $tab_content; ?>
        </div>
        <div id="passport-management" class="tab-content <?php echo $current_tab === 'passport-management' ? 'active' : ''; ?>">
            <?php if ($current_tab === 'passport-management') echo $tab_content; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabLinks = document.querySelectorAll('.tab-link');
            tabLinks.forEach(link => {
                link.addEventListener('click', function () {
                    const tabId = this.getAttribute('data-tab');
                    
                    // Xóa lớp active khỏi tất cả các tab và nội dung
                    document.querySelectorAll('.tab-link').forEach(tab => tab.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

                    // Thêm lớp active cho tab được chọn và nội dung tương ứng
                    this.classList.add('active');
                    document.getElementById(tabId).classList.add('active');

                    // Chuyển hướng đến trang tương ứng
                    if (tabId === 'tour-service') {
                        window.location.href = 'quanlydichvu.php';
                    } else if (tabId === 'airport-shuttle') {
                        window.location.href = 'quanlyduadonsanbay.php';
                    } else if (tabId === 'tour-management') {
                        window.location.href = 'quanlytour.php';
                    } else if (tabId === 'passport-management') {
                        window.location.href = 'quanlygiaythonghanh.php';
                    }
                });
            });
        });
    </script>
</body>
</html>