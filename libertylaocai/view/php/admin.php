<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liberty Lào Cai - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/libertylaocai/view/css/admin.css">
</head>
<body>
    <!-- Fixed Toggle Button - Always visible -->
    <button class="fixed-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>

    <div class="admin-container">
       <?php include "sidebar.php"; ?>

        <main class="main-content" id="mainContent">
            <header class="header">
                <h1 id="page-title">Dashboard</h1>
                <div class="user-info">
                    <div class="user-avatar">A</div>
                    <div>
                        <div>Admin</div>
                        <div style="font-size: 12px; opacity: 0.8;">Quản trị viên</div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Section -->
            <section id="dashboard" class="content-section active">
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Tổng đặt phòng</div>
                            <div class="card-icon"><i class="bi bi-calendar-check"></i></div>
                        </div>
                        <div class="card-value">142</div>
                        <div class="card-trend"><i class="bi bi-arrow-up"></i> +12% so với tháng trước</div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Phòng trống</div>
                            <div class="card-icon"><i class="bi bi-building"></i></div>
                        </div>
                        <div class="card-value">28</div>
                        <div class="card-trend"><i class="bi bi-check-circle"></i> Sẵn sàng phục vụ</div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Doanh thu tháng</div>
                            <div class="card-icon"><i class="bi bi-currency-dollar"></i></div>
                        </div>
                        <div class="card-value">2.4B</div>
                        <div class="card-trend"><i class="bi bi-arrow-up"></i> +8% so với tháng trước</div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Khách hàng mới</div>
                            <div class="card-icon"><i class="bi bi-people"></i></div>
                        </div>
                        <div class="card-value">89</div>
                        <div class="card-trend"><i class="bi bi-arrow-up"></i> +23% so với tháng trước</div>
                    </div>
                </div>

                <div class="chart-container">
                    Biểu đồ doanh thu sẽ được hiển thị tại đây
                </div>
            </section>

            <!-- Bookings Section -->
            <section id="bookings" class="content-section">
                <h2 class="section-title">Quản lý đặt phòng</h2>
                
                <div style="margin-bottom: 20px;">
                    <button class="btn btn-primary"><i class="bi bi-plus"></i> Thêm đặt phòng mới</button>
                </div>

                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Mã đặt phòng</th>
                                <th>Khách hàng</th>
                                <th>Loại phòng</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>BK001</td>
                                <td>Nguyễn Văn A</td>
                                <td>Deluxe</td>
                                <td>2025-05-28</td>
                                <td>2025-05-30</td>
                                <td><span class="status-badge status-confirmed">Đã xác nhận</span></td>
                                <td>
                                    <button class="btn btn-primary"><i class="bi bi-pencil"></i> Sửa</button>
                                    <button class="btn btn-danger"><i class="bi bi-x-circle"></i> Hủy</button>
                                </td>
                            </tr>
                            <tr>
                                <td>BK002</td>
                                <td>Trần Thị B</td>
                                <td>Căn hộ gia đình</td>
                                <td>2025-05-29</td>
                                <td>2025-06-01</td>
                                <td><span class="status-badge status-pending">Chờ xác nhận</span></td>
                                <td>
                                    <button class="btn btn-primary"><i class="bi bi-pencil"></i> Sửa</button>
                                    <button class="btn btn-danger"><i class="bi bi-x-circle"></i> Hủy</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Rooms Section -->
            <section id="rooms" class="content-section">
                <h2 class="section-title">Quản lý phòng</h2>
                
                <div style="margin-bottom: 20px;">
                    <button class="btn btn-primary"><i class="bi bi-plus"></i> Thêm phòng mới</button>
                </div>

                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Số phòng</th>
                                <th>Loại phòng</th>
                                <th>Giá/đêm</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>101</td>
                                <td>Deluxe</td>
                                <td>1,200,000 VND</td>
                                <td><span class="status-badge status-confirmed">Trống</span></td>
                                <td>
                                    <button class="btn btn-primary"><i class="bi bi-pencil"></i> Sửa</button>
                                    <button class="btn btn-danger"><i class="bi bi-trash"></i> Xóa</button>
                                </td>
                            </tr>
                            <tr>
                                <td>201</td>
                                <td>Căn hộ gia đình</td>
                                <td>2,500,000 VND</td>
                                <td><span class="status-badge status-pending">Đã đặt</span></td>
                                <td>
                                    <button class="btn btn-primary"><i class="bi bi-pencil"></i> Sửa</button>
                                    <button class="btn btn-danger"><i class="bi bi-trash"></i> Xóa</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Events Section -->
            <section id="events" class="content-section">
                <h2 class="section-title">Quản lý sự kiện</h2>
                
                <div style="margin-bottom: 20px;">
                    <button class="btn btn-primary"><i class="bi bi-plus"></i> Thêm sự kiện mới</button>
                </div>

                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tên sự kiện</th>
                                <th>Loại sự kiện</th>
                                <th>Ngày tổ chức</th>
                                <th>Số khách</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Hội nghị ABC Corp</td>
                                <td>Hội nghị</td>
                                <td>2025-06-05</td>
                                <td>150</td>
                                <td><span class="status-badge status-confirmed">Đã xác nhận</span></td>
                                <td>
                                    <button class="btn btn-primary"><i class="bi bi-pencil"></i> Sửa</button>
                                    <button class="btn btn-danger"><i class="bi bi-x-circle"></i> Hủy</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Tiệc cưới Minh & Hoa</td>
                                <td>Tiệc cưới</td>
                                <td>2025-06-10</td>
                                <td>300</td>
                                <td><span class="status-badge status-pending">Chờ xác nhận</span></td>
                                <td>
                                    <button class="btn btn-primary"><i class="bi bi-pencil"></i> Sửa</button>
                                    <button class="btn btn-danger"><i class="bi bi-x-circle"></i> Hủy</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="services" class="content-section">
                <h2 class="section-title">Dịch vụ khách sạn</h2>
                
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Giấy thông hành</div>
                            <div class="card-icon"><i class="bi bi-file-text"></i></div>
                        </div>
                        <div class="card-value">23</div>
                        <div class="card-trend">Đơn đang xử lý</div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Tour Sapa</div>
                            <div class="card-icon"><i class="bi bi-mountain"></i></div>
                        </div>
                        <div class="card-value">45</div>
                        <div class="card-trend">Khách đăng ký</div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Đưa đón sân bay</div>
                            <div class="card-icon"><i class="bi bi-car-front"></i></div>
                        </div>
                        <div class="card-value">18</div>
                        <div class="card-trend">Lịch hôm nay</div>
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Dịch vụ</th>
                            <th>Khách hàng</th>
                            <th>Ngày sử dụng</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tour Sapa 2N1Đ</td>
                            <td>Lê Văn C</td>
                            <td>2025-06-01</td>
                            <td>1,500,000 VND</td>
                            <td><span class="status-badge status-confirmed">Đã xác nhận</span></td>
                        </tr>
                        <tr>
                            <td>Đưa đón Nội Bài</td>
                            <td>Phạm Thị D</td>
                            <td>2025-05-28</td>
                            <td>800,000 VND</td>
                            <td><span class="status-badge status-pending">Chờ xác nhận</span></td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- Reports Section -->
            <section id="reports" class="content-section">
                <h2 class="section-title">Báo cáo thống kê</h2>
                
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Doanh thu năm</div>
                            <div class="card-icon"><i class="bi bi-bar-chart"></i></div>
                        </div>
                        <div class="card-value">28.5B</div>
                        <div class="card-trend"><i class="bi bi-arrow-up"></i> +15% so với năm trước</div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Tỷ lệ lấp đầy</div>
                            <div class="card-icon"><i class="bi bi-graph-up"></i></div>
                        </div>
                        <div class="card-value">78%</div>
                        <div class="card-trend"><i class="bi bi-arrow-up"></i> Tăng 5% so với tháng trước</div>
                    </div>
                </div>

                <div class="chart-container">
                    <canvas id="occupancyChart" width="400" height="200"></canvas>
                </div>
            </section>
        </main>
    </div>
    <script src="/libertylaocai/view/js/admin.js"></script>
    </body>
</html>
