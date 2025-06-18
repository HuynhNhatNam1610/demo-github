<?php
error_reporting(E_ALL);
require_once "session.php";
require_once "../../model/UserModel.php";
// Lấy dữ liệu ban đầu cho View
$result = getBookings($conn);
$bookings = $result['bookings'];
$stats = getBookingStats($conn);
// Lấy dữ liệu đặt hội trường
$event_result = getEventBookings($conn);
$events = $event_result['events'];
$event_stats = getEventBookingStats($conn);
// Lấy dữ liệu đặt nhà hàng
$restaurant_result = getRestaurantBookings($conn);
$restaurants = $restaurant_result['restaurants'];
$restaurant_stats = getRestaurantBookingStats($conn);
// Lấy dữ liệu đặt bàn bar
$bar_result = getBarBookings($conn);
$bars = $bar_result['bars'];
$bar_stats = getBarBookingStats($conn);
// Lấy dữ liệu yêu cầu liên hệ
$contact_result = getContactRequests($conn, '', '', 1, 'lienhe');
$contacts = $contact_result['contacts'];
$contact_stats = getContactRequestStats($conn, 'lienhe');
// Lấy dữ liệu yêu cầu dịch vụ
$service_result = getContactRequests($conn, '', '', 1, 'dichvu');
$services = $service_result['services'];
$service_stats = getContactRequestStats($conn, 'dichvu');

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đặt Phòng & Hội Trường - The Liberty Lào Cai</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="/libertylaocai/view/css/quanlydatphong.css">
</head>
<body>
<?php include "sidebar.php"; ?>
<div class="main-content">
    <div class="container">
        <!-- Header -->
        <div class="tabs">
            <button class="tab-btn active" onclick="openTab('booking-management')">Quản lý Đặt Phòng</button>
            <button class="tab-btn" onclick="openTab('event-management')">Quản lý Đặt Hội Trường</button>
            <button class="tab-btn" onclick="openTab('restaurant-management')">Quản lý Đặt Nhà Hàng</button>
            <button class="tab-btn" onclick="openTab('bar-management')">Quản lý Đặt Bàn Bar</button>
            <button class="tab-btn" onclick="openTab('contact-management')">Quản lý Liên hệ</button>
            <button class="tab-btn" onclick="openTab('service-management')">Quản lý Dịch vụ</button>
        </div>
        <!-- Tab quản lý đặt phòng -->
        <div id="booking-management" class="tab-content" style="display: block;">
            <!-- Form quản lý nhanh -->
            <div class="add-room-section">
                <div class="section-header">
                    <h2><i class="fas fa-cog"></i> Quản Lý Nhanh</h2>
                </div>
                <div style="padding: 1.5rem;">
                    <div class="form-group">
                        <label>Tìm kiếm theo tên khách hàng hoặc email:</label>
                        <input type="text" class="form-control" id="searchBooking" placeholder="Nhập tên hoặc email..." oninput="searchBookings(1)">
                    </div>
                </div>
            </div>

            <!-- Danh sách đặt phòng -->
            <div class="rooms-section">
                <div class="section-header">
                    <h2><i class="fas fa-list"></i> Danh Sách Đặt Phòng</h2>
                </div>
                <div style="overflow-x: auto;">
                    <table class="rooms-table">
                        <thead>
                            <tr>
                            <th>Mã Đặt</th>
                            <th>Tên Khách Hàng</th>
                            <th>Thời Gian Đặt</th>
                            <th>Trạng thái</th> <!-- Thêm cột mới -->
                            <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="bookingTableBody">
                            <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['id']); ?></td>
                                <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($booking['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-info btn-small" onclick="openDetailModal(<?php echo htmlspecialchars(json_encode($booking)); ?>)">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    <button class="btn btn-danger btn-small" onclick="confirmDelete(<?php echo $booking['id']; ?>, '<?php echo htmlspecialchars($booking['id']); ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagination" id="pagination"></div>
            </div>
        </div>

        <!-- Tab quản lý đặt hội trường -->
        <div id="event-management" class="tab-content" style="display: none;">
            <!-- Form quản lý nhanh -->
            <div class="add-room-section">
                <div class="section-header">
                    <h2><i class="fas fa-cog"></i> Quản Lý Nhanh</h2>
                </div>
                <div style="padding: 1.5rem;">
                    <div class="form-group">
                        <label>Tìm kiếm theo tên khách hàng hoặc email:</label>
                        <input type="text" class="form-control" id="searchEvent" placeholder="Nhập tên hoặc email..." oninput="searchEvents(1)">
                    </div>
                </div>
            </div>

            <!-- Danh sách đặt hội trường -->
            <div class="rooms-section">
                <div class="section-header">
                    <h2><i class="fas fa-list"></i> Danh Sách Đặt Hội Trường</h2>
                </div>
                <div style="overflow-x: auto;">
                <table class="rooms-table">
                        <thead>
                            <tr>
                                <th>Mã Đặt</th>
                                <th>Tên Khách Hàng</th>
                                <th>Loại Sự Kiện</th> <!-- Cột này hiển thị loại sự kiện -->
                                <th>Thời Gian Bắt Đầu</th>
                                <th>Trạng thái</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="eventTableBody">
                            <?php foreach ($events as $event): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($event['id']); ?></td>
                                <td><?php echo htmlspecialchars($event['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($event['type_event']); ?></td> <!-- Hiển thị loại sự kiện -->
                                <td><?php echo date('d/m/Y H:i', strtotime($event['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-info btn-small" onclick="openDetailEventModal(<?php echo htmlspecialchars(json_encode($event)); ?>)">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    <button class="btn btn-danger btn-small" onclick="confirmDeleteEvent(<?php echo $event['id']; ?>, '<?php echo htmlspecialchars($event['id']); ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagination" id="eventPagination"></div>
            </div>
        </div>
            <!-- Tab quản lý đặt nhà hàng -->
            <div id="restaurant-management" class="tab-content" style="display: none;">
                <!-- Form quản lý nhanh -->
                <div class="add-room-section">
                    <div class="section-header">
                        <h2><i class="fas fa-cog"></i> Quản Lý Nhanh</h2>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div class="form-group">
                            <label>Tìm kiếm theo tên khách hàng hoặc email:</label>
                            <input type="text" class="form-control" id="searchRestaurant" placeholder="Nhập tên hoặc email..." oninput="searchRestaurants(1)">
                        </div>
                    </div>
                </div>

                <!-- Danh sách đặt nhà hàng -->
                <div class="rooms-section">
                    <div class="section-header">
                        <h2><i class="fas fa-list"></i> Danh Sách Đặt Nhà Hàng</h2>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="rooms-table">
                            <thead>
                                <tr>
                                    <th>Mã Đặt</th>
                                    <th>Tên Khách Hàng</th>
                                    <th>Thời Gian</th>
                                    <th>Dịp</th> <!-- Thêm cột Dịp -->
                                    <th>Trạng thái</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody id="restaurantTableBody">
                                <?php foreach ($restaurants as $restaurant): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($restaurant['id']); ?></td>
                                    <td><?php echo htmlspecialchars($restaurant['customer_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($restaurant['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($restaurant['occasion'] ?? 'N/A'); ?></td> <!-- Hiển thị Dịp -->
                                    <td><?php echo ($restaurant['is_read'] == 0) ? 'Chưa đọc' : 'Đã đọc'; ?></td>
                                    <td>
                                        <button class="btn btn-info btn-small" onclick="openDetailRestaurantModal(<?php echo htmlspecialchars(json_encode($restaurant)); ?>)">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                        <button class="btn btn-danger btn-small" onclick="confirmDeleteRestaurant(<?php echo $restaurant['id']; ?>, '<?php echo htmlspecialchars($restaurant['id']); ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination" id="restaurantPagination"></div>
                </div>
            </div>

        <!-- Tab quản lý đặt bàn bar -->
        <div id="bar-management" class="tab-content" style="display: none;">
            <!-- Form quản lý nhanh -->
            <div class="add-room-section">
                <div class="section-header">
                    <h2><i class="fas fa-cog"></i> Quản Lý Nhanh</h2>
                </div>
                <div style="padding: 1.5rem;">
                    <div class="form-group">
                        <label>Tìm kiếm theo tên khách hàng hoặc email:</label>
                        <input type="text" class="form-control" id="searchBar" placeholder="Nhập tên hoặc email..." oninput="searchBars(1)">
                    </div>
                </div>
            </div>

            <!-- Danh sách đặt bàn bar -->
            <div class="rooms-section">
                <div class="section-header">
                    <h2><i class="fas fa-list"></i> Danh Sách Đặt Bàn Bar</h2>
                </div>
                <div style="overflow-x: auto;">
                    <table class="rooms-table">
                        <thead>
                            <tr>
                            <th>Mã Đặt</th>
                            <th>Tên Khách Hàng</th>
                            <th>Thời Gian</th>
                            <th>Trạng thái</th> <!-- Thêm cột mới -->
                            <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="barTableBody">
                            <?php foreach ($bars as $bar): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($bar['id']); ?></td>
                                <td><?php echo htmlspecialchars($bar['customer_name']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($bar['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-info btn-small" onclick="openDetailBarModal(<?php echo htmlspecialchars(json_encode($bar)); ?>)">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    <button class="btn btn-danger btn-small" onclick="confirmDeleteBar(<?php echo $bar['id']; ?>, '<?php echo htmlspecialchars($bar['id']); ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagination" id="barPagination"></div>
            </div>
        </div>

        <!-- Tab quản lý liên hệ -->
        <div id="contact-management" class="tab-content" style="display: none;">
            <!-- Form quản lý nhanh -->
            <div class="add-room-section">
                <div class="section-header">
                    <h2><i class="fas fa-cog"></i> Quản Lý Nhanh</h2>
                </div>
                <div style="padding: 1.5rem;">
                    <div class="form-group">
                        <label>Tìm kiếm theo tên khách hàng hoặc email:</label>
                        <input type="text" class="form-control" id="searchContact" placeholder="Nhập tên hoặc email..." oninput="searchContacts(1)">
                    </div>
                </div>
            </div>

            <!-- Danh sách liên hệ -->
            <div class="rooms-section">
                <div class="section-header">
                    <h2><i class="fas fa-list"></i> Danh Sách Liên hệ</h2>
                </div>
                <div style="overflow-x: auto;">
                    <table class="rooms-table">
                        <thead>
                            <tr>
                            <th>Mã Yêu Cầu</th>
                            <th>Tên Khách Hàng</th>
                            <th>Thời Gian Tạo</th>
                            <th>Trạng thái</th> <!-- Thêm cột mới -->
                            <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="contactTableBody">
                            <?php foreach ($contacts as $contact): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($contact['id']); ?></td>
                                <td><?php echo htmlspecialchars($contact['customer_name']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($contact['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-info btn-small" onclick="openDetailContactModal(<?php echo htmlspecialchars(json_encode($contact)); ?>)">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    <button class="btn btn-danger btn-small" onclick="confirmDeleteContact(<?php echo $contact['id']; ?>, '<?php echo htmlspecialchars($contact['id']); ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagination" id="contactPagination"></div>
            </div>
        </div>

        <!-- Tab quản lý dịch vụ -->
        <div id="service-management" class="tab-content" style="display: none;">
            <!-- Form quản lý nhanh -->
            <div class="add-room-section">
                <div class="section-header">
                    <h2><i class="fas fa-cog"></i> Quản Lý Nhanh</h2>
                </div>
                <div style="padding: 1.5rem;">
                    <div class="form-group">
                        <label>Tìm kiếm theo tên khách hàng hoặc email:</label>
                        <input type="text" class="form-control" id="searchService" placeholder="Nhập tên hoặc email..." oninput="searchServices(1)">
                    </div>
                </div>
            </div>

            <!-- Danh sách dịch vụ -->
            <div class="rooms-section">
                <div class="section-header">
                    <h2><i class="fas fa-list"></i> Danh Sách Dịch vụ</h2>
                </div>
                <div style="overflow-x: auto;">
                    <table class="rooms-table">
                        <thead>
                            <tr>
                            <th>Mã Yêu Cầu</th>
                            <th>Tên Khách Hàng</th>
                            <th>Thời Gian Tạo</th>
                            <th>Trạng thái</th> <!-- Thêm cột mới -->
                            <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="serviceTableBody">
                            <?php foreach ($services as $service): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($service['id']); ?></td>
                                <td><?php echo htmlspecialchars($service['customer_name']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($service['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-info btn-small" onclick="openDetailServiceModal(<?php echo htmlspecialchars(json_encode($service)); ?>)">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    <button class="btn btn-danger btn-small" onclick="confirmDeleteService(<?php echo $service['id']; ?>, '<?php echo htmlspecialchars($service['id']); ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagination" id="servicePagination"></div>
            </div>
        </div>

        <!-- Modal chi tiết đặt phòng -->
        <div id="detailModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><i class="fas fa-info-circle"></i> Chi Tiết Đặt Phòng</h2>
                </div>
                <div class="modal-body">
                    <div class="modal-scrollable">
                        <div class="detail-content">
                            <div class="detail-row"><strong>Mã Đặt Phòng:</strong> <span id="detail_id"></span></div>
                            <div class="detail-row"><strong>Loại Phòng:</strong> <span id="detail_room_type"></span></div>
                            <div class="detail-row"><strong>Tên Khách Hàng:</strong> <span id="detail_customer_name"></span></div>
                            <div class="detail-row"><strong>Số Điện Thoại:</strong> <span id="detail_customer_phone"></span></div>
                            <div class="detail-row"><strong>Email:</strong> <span id="detail_customer_email"></span></div>
                            <div class="detail-row"><strong>Thời Gian Đến:</strong> <span id="detail_time_come"></span></div>
                            <div class="detail-row"><strong>Thời Gian Đi:</strong> <span id="detail_time_leave"></span></div>
                            <div class="detail-row"><strong>Số Người:</strong> <span id="detail_number_people"></span></div>
                            <div class="detail-row"><strong>Ghi Chú:</strong> <span id="detail_note"></span></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="closeModal('detailModal')">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal chi tiết đặt hội trường -->
        <div id="detailEventModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><i class="fas fa-info-circle"></i> Chi Tiết Đặt Hội Trường</h2>
                </div>
                <div class="modal-body">
                    <div class="modal-scrollable">
                        <div class="detail-content">
                            <div class="detail-row"><strong>Mã Đặt:</strong> <span id="detail_event_id"></span></div>
                            <div class="detail-row"><strong>Loại Sự Kiện:</strong> <span id="detail_event_type"></span></div>
                            <div class="detail-row"><strong>Tên Khách Hàng:</strong> <span id="detail_event_customer_name"></span></div>
                            <div class="detail-row"><strong>Email:</strong> <span id="detail_event_customer_email"></span></div>
                            <div class="detail-row"><strong>Số Điện Thoại:</strong> <span id="detail_event_customer_phone"></span></div>
                            <div class="detail-row"><strong>Hội Trường:</strong> <span id="detail_event_hall_name"></span></div>
                            <div class="detail-row"><strong>Thời Gian Bắt Đầu:</strong> <span id="detail_event_start_at"></span></div>
                            <div class="detail-row"><strong>Thời Gian Kết Thúc:</strong> <span id="detail_event_end_at"></span></div>
                            <div class="detail-row"><strong>Số Người:</strong> <span id="detail_event_number_people"></span></div>
                            <div class="detail-row"><strong>Ghi Chú:</strong> <span id="detail_event_note"></span></div>
                            <div class="detail-row"><strong>Ngân Sách:</strong> <span id="detail_event_budget"></span></div>
                            <div class="detail-row"><strong>Ảnh:</strong></div>
                            <div class="swiper-container" id="detail_event_images">
                                <div class="swiper-wrapper"></div>
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="closeModal('detailEventModal')">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal chi tiết đặt nhà hàng -->
        <div id="detailRestaurantModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><i class="fas fa-info-circle"></i> Chi Tiết Đặt Nhà Hàng</h2>
                </div>
                <div class="modal-body">
                    <div class="modal-scrollable">
                        <div class="detail-content">
                            <div class="detail-row"><strong>Mã Đặt:</strong> <span id="detail_restaurant_id"></span></div>
                            <div class="detail-row"><strong>Địa Điểm:</strong> <span id="detail_restaurant_location"></span></div>
                            <div class="detail-row"><strong>Tên Khách Hàng:</strong> <span id="detail_restaurant_customer_name"></span></div>
                            <div class="detail-row"><strong>Số Điện Thoại:</strong> <span id="detail_restaurant_customer_phone"></span></div>
                            <div class="detail-row"><strong>Email:</strong> <span id="detail_restaurant_customer_email"></span></div>
                            <div class="detail-row"><strong>Thời Gian:</strong> <span id="detail_restaurant_start_at"></span></div>
                            <div class="detail-row"><strong>Số Người:</strong> <span id="detail_restaurant_number_people"></span></div>
                            <div class="detail-row"><strong>Dịp:</strong> <span id="detail_restaurant_occasion"></span></div>
                            <div class="detail-row"><strong>Ghi Chú:</strong> <span id="detail_restaurant_note"></span></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="closeModal('detailRestaurantModal')">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal chi tiết đặt bàn bar -->
        <div id="detailBarModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><i class="fas fa-info-circle"></i> Chi Tiết Đặt Bàn Bar</h2>
                </div>
                <div class="modal-body">
                    <div class="modal-scrollable">
                        <div class="detail-content">
                            <div class="detail-row"><strong>Mã Đặt:</strong> <span id="detail_bar_id"></span></div>
                            <div class="detail-row"><strong>Tên Khách Hàng:</strong> <span id="detail_bar_customer_name"></span></div>
                            <div class="detail-row"><strong>Số Điện Thoại:</strong> <span id="detail_bar_customer_phone"></span></div>
                            <div class="detail-row"><strong>Email:</strong> <span id="detail_bar_customer_email"></span></div>
                            <div class="detail-row"><strong>Thời Gian:</strong> <span id="detail_bar_start_at"></span></div>
                            <div class="detail-row"><strong>Số Người:</strong> <span id="detail_bar_number_people"></span></div>
                            <div class="detail-row"><strong>Ghi Chú:</strong> <span id="detail_bar_note"></span></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="closeModal('detailBarModal')">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal chi tiết liên hệ -->
        <div id="detailContactModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><i class="fas fa-info-circle"></i> Chi Tiết Liên hệ</h2>
                </div>
                <div class="modal-body">
                    <div class="modal-scrollable">
                        <div class="detail-content">
                            <div class="detail-row"><strong>Mã Yêu Cầu:</strong> <span id="detail_contact_id"></span></div>
                            <div class="detail-row"><strong>Tên Khách Hàng:</strong> <span id="detail_contact_customer_name"></span></div>
                            <div class="detail-row"><strong>Số Điện Thoại:</strong> <span id="detail_contact_customer_phone"></span></div>
                            <div class="detail-row"><strong>Email:</strong> <span id="detail_contact_customer_email"></span></div>
                            <div class="detail-row"><strong>Thời Gian Tạo:</strong> <span id="detail_contact_created_at"></span></div>
                            <div class="detail-row"><strong>Tin Nhắn:</strong> <span id="detail_contact_message"></span></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="closeModal('detailContactModal')">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal chi tiết dịch vụ -->
        <div id="detailServiceModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><i class="fas fa-info-circle"></i> Chi Tiết Dịch vụ</h2>
                </div>
                <div class="modal-body">
                    <div class="modal-scrollable">
                        <div class="detail-content">
                            <div class="detail-row"><strong>Mã Yêu Cầu:</strong> <span id="detail_service_id"></span></div>
                            <div class="detail-row"><strong>Dịch Vụ:</strong> <span id="detail_service_service"></span></div>
                            <div class="detail-row"><strong>Tên Khách Hàng:</strong> <span id="detail_service_customer_name"></span></div>
                            <div class="detail-row"><strong>Số Điện Thoại:</strong> <span id="detail_service_customer_phone"></span></div>
                            <div class="detail-row"><strong>Email:</strong> <span id="detail_service_customer_email"></span></div>
                            <div class="detail-row"><strong>Thời Gian Tạo:</strong> <span id="detail_service_created_at"></span></div>
                            <div class="detail-row"><strong>Tin Nhắn:</strong> <span id="detail_service_message"></span></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="closeModal('detailServiceModal')">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                </div>
            </div>
        </div>

        <!-- Form ẩn để xóa đặt phòng -->
        <form id="deleteForm" method="POST" style="display: none;">
            <input type="hidden" name="action" value="delete_booking">
            <input type="hidden" name="booking_id" id="delete_booking_id">
        </form>

        <!-- Form ẩn để xóa đặt hội trường -->
        <form id="deleteEventForm" method="POST" style="display: none;">
            <input type="hidden" name="action" value="delete_event">
            <input type="hidden" name="event_id" id="delete_event_id">
        </form>

        <!-- Form ẩn để xóa đặt nhà hàng -->
        <form id="deleteRestaurantForm" method="POST" style="display: none;">
            <input type="hidden" name="action" value="delete_restaurant">
            <input type="hidden" name="restaurant_id" id="delete_restaurant_id">
        </form>

        <!-- Form ẩn để xóa đặt bàn bar -->
        <form id="deleteBarForm" method="POST" style="display: none;">
            <input type="hidden" name="action" value="delete_bar">
            <input type="hidden" name="bar_id" id="delete_bar_id">
        </form>

        <!-- Form ẩn để xóa liên hệ -->
        <form id="deleteContactForm" method="POST" style="display: none;">
            <input type="hidden" name="action" value="delete_contact">
            <input type="hidden" name="contact_id" id="delete_contact_id">
        </form>

        <!-- Form ẩn để xóa dịch vụ -->
        <form id="deleteServiceForm" method="POST" style="display: none;">
            <input type="hidden" name="action" value="delete_service1">
            <input type="hidden" name="service_id" id="delete_service_id">
        </form>
        
        <!-- Loading indicator -->
        <div id="loading" class="loading">Đang xử lý...</div>

        <script src="/libertylaocai/view/js/quanlydatphong.js"></script>
    </div>
</body>
</html>
