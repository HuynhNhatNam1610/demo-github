<?php
require_once "session.php";
require_once "../../model/UserModel.php";
if (!empty($_SESSION['head_banner'])) {
    $getSelectedBanner = $_SESSION['head_banner'];
}

// Kiểm tra ngôn ngữ từ session, mặc định là 1 (tiếng Việt)
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;

//Lấy các loại sự kiện
$eventData = getPrimaryEventData($languageId);

//Lấy sự kiện đã tổ chức
$organizedEvents = getOrganizedEvents($languageId, 4);

// Lấy mô tả cho hero-content
$descriptionHeroContent = getSelectedDescription($languageId, 'hero-content');

// // Lấy mô tả cho hero-features
// $descriptionHeroFeatures1 = getSelectedDescription($languageId, 'hero-features1');
// $descriptionHeroFeatures2 = getSelectedDescription($languageId, 'hero-features2');
// $descriptionHeroFeatures3 = getSelectedDescription($languageId, 'hero-features3');

//Lấy ảnh cho phần hero-images
$getImageGeneral = getImageGeneral(3);

//Lấy ảnh cho banner-image
$getSelectedBannerImage = getSelectedImage('banner-image');

// Gọi hàm để lấy danh sách loại sự kiện
$eventTypes = getEventTypes($languageId);

// Gọi hàm để lấy danh sách hội trường
$halls = getConferenceHalls($languageId);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sự kiện</title>
    <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
    <meta name="description" content="<?php echo $languageId == 1 ? 'Liberty Hotel & Events Khách sạn Liberty Lào Cai' : 'Liberty Hotel & Events Lao Cai'; ?>">
    <link rel="stylesheet" href="/libertylaocai/view/css/event.css">
</head>

<body>
    <?php include "header.php" ?>
    <div class="event-container">
        <div class="event-banner">
            <img src="/libertylaocai/view/img/<?= $getSelectedBanner['image']; ?>" alt="Banner Image" class="banner-image">
            <h1><?php echo $languageId == 1 ? 'HỘI NGHỊ & SỰ KIỆN' : 'CONFERENCES & EVENTS'; ?></h1>
            <!-- <div class="event-breadcumb">
                <?php $languageId == 1 ? 'Trang Chủ > Hội Nghị & Sự Kiện' : 'Home > Conferences & Events'; ?>
            </div> -->
        </div>
        <div class="list-event">
            <h1><?php echo $languageId == 1 ? 'Loại hình sự kiện' : 'Event type'; ?></h1>
            <div class="type-event">
                <?php if (!empty($eventData)): ?>
                    <?php foreach ($eventData as $event): ?>
                        <div class="type-of-event">
                            <div class="event-img">
                                <img src="/libertylaocai/view/img/<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                            </div>
                            <div class="event-title">
                                <?php echo htmlspecialchars($event['title']); ?>
                            </div>
                            <div class="event-description">
                                <?php echo htmlspecialchars($event['content']); ?>
                            </div>
                            <form action="/libertylaocai/user/submit" method="POST" style="display: inline;">
                                <input type="hidden" name="event_code" value="<?php echo htmlspecialchars($event['code']); ?>">
                                <div class="event-more"><?php echo $languageId == 1 ? 'Xem thêm ' : 'More'; ?></div>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Không có sự kiện nào.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="event-organized">
            <h1><?php echo $languageId == 1 ? 'Sự kiện đã tổ chức' : 'Events organized'; ?></h1>
            <div class="event-organized-list">
                <?php
                if (!empty($organizedEvents)) {
                    foreach ($organizedEvents as $event) {
                ?>
                        <div class="event-organized-detail" data-date="📅 <?php echo htmlspecialchars($event['create_at']); ?>">
                            <div class="organized-img">
                                <img src="/libertylaocai/view/img/<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                            </div>
                            <div class="organized-content">
                                <div class="organized-title">
                                    <?php echo htmlspecialchars($event['title']); ?>
                                </div>
                                <div class="organized-description">
                                    <?php echo htmlspecialchars($event['content']); ?>
                                </div>
                                <form action="/libertylaocai/user/submit" method="POST" style="display:inline;">
                                    <input type="hidden" name="sukiendatochuc" value="<?php echo htmlspecialchars($event['id']); ?>">
                                    <div class="organized-more" onclick="this.closest('form').submit()" style="cursor:pointer;">
                                        <?php echo $languageId == 1 ? 'Xem Thêm' : 'See More'; ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <p><?php echo $languageId == 1 ? 'Không có sự kiện nào.' : 'No events available.'; ?></p>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <?php if (!empty($descriptionHeroContent)): ?>
                    <?php echo $descriptionHeroContent['content']; ?>
                <?php endif; ?>
            </div>

            <div class="hero-images">
                <?php if (!empty($getImageGeneral)): ?>
                    <?php foreach ($getImageGeneral as $index => $slide): ?>
                        <div class="hero-image">
                            <img src='/libertylaocai/view/img/<?php echo htmlspecialchars($slide['image']); ?>' alt='Slide <?php echo $index + 1; ?>'>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="hero-image">
                        <img src="https://libertylaocai.vn/_next/image?url=%2Fassets%2Fopenart-image_NgQTaQNd_1744184209170_raw.jpg&w=1920&q=75" alt="Liberty Hotel">
                    </div>
                    <div class="hero-image">
                        <img src="https://libertylaocai.vn/_next/image?url=%2Fassets%2Fopenart-image_NgQTaQNd_1744184209170_raw.jpg&w=1920&q=75" alt="Liberty Hotel Event">
                        <!-- <div class="hero-badge">Trở thành khách hàng tiếp theo của chúng tôi<br>Hơn 200+ sự kiện thành công mỗi năm</div> -->
                    </div>
                    <div class="hero-image">
                        <img src="https://libertylaocai.vn/_next/image?url=%2Fassets%2Fopenart-image_NgQTaQNd_1744184209170_raw.jpg&w=1920&q=75" alt="Liberty Hotel Service">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="stats-section">
            <div class="stats-container">
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label"><?php echo $languageId == 1 ? 'Sự kiện mỗi năm' : 'Events every year'; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">98%</span>
                    <span class="stat-label"><?php echo $languageId == 1 ? 'Khách hàng hài lòng' : 'Customers are satisfied'; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">10+</span>
                    <span class="stat-label"><?php echo $languageId == 1 ? 'Năm kinh nghiệm' : 'Years of experience'; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label"><?php echo $languageId == 1 ? 'Hỗ trợ khách hàng' : 'Customer support'; ?></span>
                </div>
            </div>
        </div>
    </div>
    <button class="quick-booking-btn" onclick="openModal()">
        <i class="bi bi-plus"></i><?php echo $languageId == 1 ? 'Đặt Lịch Nhanh' : 'Book Now'; ?>
    </button>
    <?php include "footer.php" ?>
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><?php echo $languageId == 1 ? 'Đặt Lịch Tổ Chức Sự Kiện' : 'Book an Event'; ?></h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="bookingForm" action="/libertylaocai/user/submit" method="POST" enctype="multipart/form-data">
                    <div class="form-section">
                        <h3><?php echo $languageId == 1 ? 'Thông Tin Liên Hệ' : 'Contact Information'; ?></h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fullName"><?php echo $languageId == 1 ? 'Họ và tên' : 'Full Name'; ?> <span class="required">*</span></label>
                                <input type="text" id="fullName" name="fullName" placeholder="<?php echo $languageId == 1 ? 'Nhập họ và tên' : 'Enter full name'; ?>" required>
                                <span class="error-message" id="fullName-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="phone"><?php echo $languageId == 1 ? 'Số điện thoại' : 'Phone Number'; ?> <span class="required">*</span></label>
                                <input type="tel" id="phone" name="phone" placeholder="<?php echo $languageId == 1 ? 'Nhập số điện thoại' : 'Enter phone number'; ?>" required>
                                <span class="error-message" id="phone-error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email"><?php echo $languageId == 1 ? 'Email' : 'Email'; ?> <span class="required">*</span></label>
                            <input type="email" id="email" name="email" placeholder="<?php echo $languageId == 1 ? 'Nhập email' : 'Enter email'; ?>" required>
                            <span class="error-message" id="email-error"></span>
                        </div>
                    </div>
                    <div class="form-section">
                        <h3><?php echo $languageId == 1 ? 'Thông Tin Sự Kiện' : 'Event Information'; ?></h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="eventType"><?php echo $languageId == 1 ? 'Loại sự kiện' : 'Event Type'; ?> <span class="required">*</span></label>
                                <select id="eventType" name="eventType" required>
                                    <option value=""><?php echo $languageId == 1 ? 'Chọn loại sự kiện' : 'Select event type'; ?></option>
                                    <?php foreach ($eventTypes as $event): ?>
                                        <option value="<?php echo htmlspecialchars($event['code']); ?>"><?php echo htmlspecialchars($event['title']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="error-message" id="eventType-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="guestCount"><?php echo $languageId == 1 ? 'Số lượng khách' : 'Number of Guests'; ?> <span class="required">*</span></label>
                                <input type="number" id="guestCount" name="guestCount" placeholder="<?php echo $languageId == 1 ? 'Nhập số lượng khách dự kiến' : 'Enter estimated number of guests'; ?>" min="1" required>
                                <span class="error-message" id="guestCount-error"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="venue"><?php echo $languageId == 1 ? 'Hội trường tổ chức' : 'Organizing hall'; ?> <span class="required">*</span></label>
                                <select id="venue" name="venue" required>
                                    <option value=""><?php echo $languageId == 1 ? 'Chọn hội trường' : 'Select hall'; ?></option>
                                    <?php foreach ($halls as $hall): ?>
                                        <option value="<?php echo htmlspecialchars($hall['id']); ?>"><?php echo htmlspecialchars($hall['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="error-message" id="venue-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="budget"><?php echo $languageId == 1 ? 'Ngân sách dự kiến' : 'Estimated Budget'; ?></label>
                                <input type="text" id="budget" name="budget" placeholder="<?php echo $languageId == 1 ? 'Nhập ngân sách dự kiến (VND)' : 'Enter estimated budget (VND)'; ?>">
                                <span class="error-message" id="budget-error"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="eventDate"><?php echo $languageId == 1 ? 'Ngày bắt đầu' : 'Start Date'; ?> <span class="required">*</span></label>
                                <input type="date" id="eventDate" name="eventDate" required>
                                <span class="error-message" id="eventDate-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="endDate"><?php echo $languageId == 1 ? 'Ngày kết thúc' : 'End Date'; ?> <span class="required">*</span></label>
                                <input type="date" id="endDate" name="endDate" required>
                                <span class="error-message" id="endDate-error"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="startTime"><?php echo $languageId == 1 ? 'Giờ bắt đầu' : 'Start Time'; ?> <span class="required">*</span></label>
                                <input type="time" id="startTime" name="startTime" required>
                                <span class="error-message" id="startTime-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="endTime"><?php echo $languageId == 1 ? 'Giờ kết thúc' : 'End Time'; ?> <span class="required">*</span></label>
                                <input type="time" id="endTime" name="endTime" required>
                                <span class="error-message" id="endTime-error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description"><?php echo $languageId == 1 ? 'Mô tả chi tiết sự kiện' : 'Event Description'; ?> <span class="required">*</span></label>
                            <textarea id="description" name="description" placeholder="<?php echo $languageId == 1 ? 'Mô tả chi tiết về sự kiện bạn muốn tổ chức' : 'Describe the event details'; ?>" required></textarea>
                        </div>
                    </div>
                    <div class="form-section">
                        <h3><?php echo $languageId == 1 ? 'Hình ảnh tham khảo' : 'Reference Images'; ?></h3>
                        <div class="form-group">
                            <div class="upload-area">
                                <div class="upload-icon">📷</div>
                                <div class="upload-text">
                                    <?php echo $languageId == 1 ? 'Nhấp để tải lên hình ảnh tham khảo' : 'Click to upload reference images'; ?><br>
                                    <small><?php echo $languageId == 1 ? 'Có thể tải lên nhiều hình ảnh' : 'Multiple images can be uploaded'; ?></small>
                                </div>
                                <input type="file" id="imageUpload" name="images[]" multiple accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <small style="color: #666;"><?php echo $languageId == 1 ? '* Thông tin bắt buộc' : '* Required fields'; ?></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeModal()"><?php echo $languageId == 1 ? 'Hủy' : 'Cancel'; ?></button>
                <button type="submit" form="bookingForm" class="btn btn-submit"><?php echo $languageId == 1 ? 'Gửi Yêu Cầu' : 'Submit Request'; ?></button>
            </div>
        </div>
    </div>
    <script>
        const languageId = <?php echo json_encode($languageId); ?>;
    </script>
    <script src="/libertylaocai/view/js/event.js"></script>
</body>

</html>