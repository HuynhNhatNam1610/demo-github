<?php
require_once "session.php";
require_once "../../model/UserModel.php";
// Kiểm tra ngôn ngữ từ session, mặc định là 1 (tiếng Việt)
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;
if (!empty($_SESSION['head_banner'])) {
    $getSelectedBanner = $_SESSION['head_banner'];
}
if (!empty($_SESSION['type_event'])) {
    $typeEvent = $_SESSION['type_event'];
    if ($typeEvent === 'tiec-cuoi') {
        $languageId == 1 ? $titleBanner = 'TỔ CHỨC TIỆC CƯỚI' : $titleBanner = 'WEDDING ORGANIZATION';
        $descriptionPagedetailTitle = getSelectedDescription($languageId, 'pagedetail-title-wedding');
    } elseif ($typeEvent === 'sinh-nhat') {
        $languageId == 1 ? $titleBanner = 'TỔ CHỨC SINH NHẬT' : $titleBanner = 'BIRTHDAY ORGANIZATION';
        $descriptionPagedetailTitle = getSelectedDescription($languageId, 'pagedetail-title-birthday');
    } elseif ($typeEvent === 'hoi-nghi') {
        $languageId == 1 ? $titleBanner = 'TỔ CHỨC HỘI NGHỊ' : $titleBanner = 'CONFERENCE ORGANIZATION';
        $descriptionPagedetailTitle = getSelectedDescription($languageId, 'pagedetail-title-conference');
    } elseif ($typeEvent === 'gala-dinner') {
        $languageId == 1 ? $titleBanner = 'TỔ CHỨC GALA & TIỆC CÔNG TY' : $titleBanner = 'ORGANIZING GALA & COMPANY PARTIES';
        $descriptionPagedetailTitle = getSelectedDescription($languageId, 'pagedetail-title-gala');
    }
}
if (!empty($_SESSION['image_organized_event'])) {
    $getImageOrganizedEvents = $_SESSION['image_organized_event'];
}

$rooms = getConferenceRooms($languageId);

// Mảng dịch ngôn ngữ
$translations = [
    1 => [ // Tiếng Việt
        'title' => 'Đặt Lịch Tổ Chức Sự Kiện',
        'description' => 'Vui lòng điền đầy đủ thông tin bên dưới để chúng tôi có thể xử lý yêu cầu đặt lịch của bạn một cách nhanh chóng và chính xác nhất.',
        'contact_info' => 'Thông Tin Liên Hệ',
        'full_name_label' => 'Họ và tên',
        'full_name_placeholder' => 'Nhập họ và tên',
        'phone_label' => 'Số điện thoại',
        'phone_placeholder' => 'Nhập số điện thoại',
        'email_label' => 'Email',
        'email_placeholder' => 'Nhập email',
        'event_info' => 'Thông Tin Sự Kiện',
        'event_type_label' => 'Loại sự kiện',
        'event_type_placeholder' => 'Chọn loại sự kiện',
        'event_types' => [
            'wedding' => 'Tiệc cưới',
            'conference' => 'Hội nghị',
            'birthday' => 'Tiệc sinh nhật',
            'gala' => 'Gala & Tiệc Công Ty',
            'other' => 'Khác'
        ],
        'guest_count_label' => 'Số lượng khách',
        'guest_count_placeholder' => 'Nhập số lượng khách dự kiến',
        'checkin_label' => 'Ngày bắt đầu',
        'checkout_label' => 'Ngày kết thúc',
        'start_time_label' => 'Giờ bắt đầu',
        'end_time_label' => 'Giờ kết thúc',
        'venue_label' => 'Địa điểm',
        'venue_placeholder' => 'Nhập địa điểm tổ chức',
        'budget_label' => 'Ngân sách dự kiến',
        'budget_placeholder' => 'Nhập ngân sách dự kiến (VND)',
        'note_label' => 'Mô tả chi tiết sự kiện',
        'note_placeholder' => 'Yêu cầu đặc biệt, ghi chú thêm...',
        'image_section' => 'Hình ảnh tham khảo',
        'upload_text' => 'Nhấp để tải lên hình ảnh tham khảo<br><small>Có thể tải lên nhiều hình ảnh</small>',
        'submit_button' => 'Gửi Yêu Cầu',
        'required_note' => 'Thông tin bắt buộc'
    ],
    2 => [ // Tiếng Anh
        'title' => 'Book an Event',
        'description' => 'Please fill in the information below so we can process your booking request quickly and accurately.',
        'contact_info' => 'Contact Information',
        'full_name_label' => 'Full Name',
        'full_name_placeholder' => 'Enter your full name',
        'phone_label' => 'Phone Number',
        'phone_placeholder' => 'Enter your phone number',
        'email_label' => 'Email',
        'email_placeholder' => 'Enter your email',
        'event_info' => 'Event Information',
        'event_type_label' => 'Event Type',
        'event_type_placeholder' => 'Select event type',
        'event_types' => [
            'wedding' => 'Wedding',
            'conference' => 'Conference',
            'birthday' => 'Birthday Party',
            'gala' => 'Gala & Company Event',
            'other' => 'Other'
        ],
        'guest_count_label' => 'Number of Guests',
        'guest_count_placeholder' => 'Enter expected number of guests',
        'checkin_label' => 'Start Date',
        'checkout_label' => 'End Date',
        'start_time_label' => 'Start Time',
        'end_time_label' => 'End Time',
        'venue_label' => 'Venue',
        'venue_placeholder' => 'Enter event venue',
        'budget_label' => 'Estimated Budget',
        'budget_placeholder' => 'Enter estimated budget (VND)',
        'note_label' => 'Event Details',
        'note_placeholder' => 'Special requests, additional notes...',
        'image_section' => 'Reference Images',
        'upload_text' => 'Click to upload reference images<br><small>Multiple images can be uploaded</small>',
        'submit_button' => 'Submit Request',
        'required_note' => 'Required information'
    ]
];

// Lấy bản dịch theo ngôn ngữ
$trans = $translations[$languageId];

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
    <link rel="stylesheet" href="/libertylaocai/view/css/pagedetail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php include "header.php" ?>
    <div class="pagedetail-container">
        <div class="pagedetail-banner">
            <img src="/libertylaocai/view/img/<?= $getSelectedBanner['image']; ?>" alt="Banner Image" class="banner-image">
            <h1><?= $titleBanner ?></h1>
            <!-- <div class="pagedetail-breadcumb">
                Trang Chủ > Hội Nghị & Sự Kiện > Tiệc cưới
            </div> -->
        </div>
        <div class="pagedetail-title">
            <?php if (!empty($descriptionPagedetailTitle)): ?>
                <h1><?php echo htmlspecialchars($descriptionPagedetailTitle['content']); ?></h1>
            <?php endif; ?>
        </div>
        <div class="pagedetail-gallery">
            <div class="gallery-container">
                <div class="gallery-wrapper">
                    <div class="gallery-slider">
                        <?php
                        foreach ($getImageOrganizedEvents as $index => $event) {
                            echo '<div class="gallery-item" data-index="' . $index . '">';
                            echo '<img src="/libertylaocai/view/img/' . htmlspecialchars($event['image']) . '" alt="Wedding Image ' . ($index + 1) . '">';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
                <div class="gallery-btn prev-btn"><i class="fas fa-chevron-left"></i></div>
                <div class="gallery-btn next-btn"><i class="fas fa-chevron-right"></i></div>
                <div class="gallery-dots">
                    <?php
                    // Tạo chấm chỉ báo tương ứng với số ảnh
                    foreach ($getImageOrganizedEvents as $index => $image) {
                        echo '<span class="dot" data-index="' . $index . '"' . ($index === 0 ? ' class="active"' : '') . '></span>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- Tab Menu -->
        <div class="tab-menu">
            <div class="tab-nav">
                <button class="tab-btn active" onclick="showTab('description')">
                    <i class="fas fa-info-circle"></i> <?php echo $languageId == 1 ? ' Mô Tả' : 'Describe'; ?>
                </button>
                <button class="tab-btn" onclick="showTab('booking')">
                    <i class="fas fa-calendar-alt"></i> <?php echo $languageId == 1 ? ' Đặt Ngay' : 'Book Now'; ?>
                </button>
            </div>

            <div class="tab-content">
                <div id="description" class="tab-pane active">
                    <div class="room-description">
                        <div class="desc-section">
                            <h3><?php echo $languageId == 1 ? 'CÁC HỘI TRƯỜNG HIỆN CÓ' : 'EXISTING HALLS'; ?></h3>
                            <div class="room-specs">
                                <?php foreach ($rooms as $room): ?>
                                    <div class="spec-item">
                                        <div class="spec-description">
                                            <?= $room['description'] ?>
                                            <div class="price-container">
                                                <div class="price">
                                                    <?= number_format($room['prices'][24] ?? 0, 0, ',', '.') ?> VNĐ <span>/ <?= $languageId == 1 ? 'Ngày' : 'Day' ?></span>
                                                </div>
                                                <div class="half-day-price">
                                                    <?= number_format($room['prices'][12] ?? 0, 0, ',', '.') ?> VNĐ <span>/ 12h</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="spec-img">
                                            <div class="spec-img-container">
                                                <div class="spec-img-wrapper">
                                                    <div class="spec-img-slider">
                                                        <?php foreach ($room['images'] as $index => $image): ?>
                                                            <div class="spec-img-item" data-index="<?= $index ?>">
                                                                <img src="/libertylaocai/view/img/<?= htmlspecialchars($image) ?>" alt="Hall Image <?= $index + 1 ?>">
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="spec-img-btn spec-prev-btn"><i class="fas fa-chevron-left"></i></div>
                                                    <div class="spec-img-btn spec-next-btn"><i class="fas fa-chevron-right"></i></div>
                                                    <div class="spec-img-dots">
                                                        <?php foreach ($room['images'] as $index => $image): ?>
                                                            <span class="spec-dot" data-index="<?= $index ?>" <?= $index === 0 ? ' class="active"' : '' ?>></span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="booking" class="tab-pane">
                    <div class="room-description">
                        <div class="desc-section">
                            <h3><?= htmlspecialchars($trans['title']) ?></h3>
                            <p><?= htmlspecialchars($trans['description']) ?></p>
                            <form id="quickBookingForm" action="/libertylaocai/user/submit" method="POST" enctype="multipart/form-data" style="margin-top: 30px;">
                                <input type="hidden" name="submit_booking" value="true">
                                <div class="form-section">
                                    <h3><?= htmlspecialchars($trans['contact_info']) ?></h3>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="quickFullName"><?= htmlspecialchars($trans['full_name_label']) ?> <span class="required">*</span></label>
                                            <input type="text" id="quickFullName" name="fullName" placeholder="<?= htmlspecialchars($trans['full_name_placeholder']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="quickPhone"><?= htmlspecialchars($trans['phone_label']) ?> <span class="required">*</span></label>
                                            <input type="tel" id="quickPhone" name="phone" placeholder="<?= htmlspecialchars($trans['phone_placeholder']) ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="quickEmail"><?= htmlspecialchars($trans['email_label']) ?> <span class="required">*</span></label>
                                        <input type="email" id="quickEmail" name="email" placeholder="<?= htmlspecialchars($trans['email_placeholder']) ?>" required>
                                    </div>
                                </div>
                                <div class="form-section">
                                    <h3><?= htmlspecialchars($trans['event_info']) ?></h3>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="quickEventType"><?= htmlspecialchars($trans['event_type_label']) ?> <span class="required">*</span></label>
                                            <select id="quickEventType" name="eventType" required>
                                                <option value=""><?= htmlspecialchars($trans['event_type_placeholder']) ?></option>
                                                <?php foreach ($eventTypes as $event): ?>
                                                    <option value="<?php echo htmlspecialchars($event['code']); ?>" <?php echo $event['code'] == $typeEvent ? 'selected' : ''; ?>><?php echo htmlspecialchars($event['title']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="quickGuestCount"><?= htmlspecialchars($trans['guest_count_label']) ?> <span class="required">*</span></label>
                                            <input type="number" id="quickGuestCount" name="guestCount" placeholder="<?= htmlspecialchars($trans['guest_count_placeholder']) ?>" min="1" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="quickVenue"><?= htmlspecialchars($trans['venue_label']) ?> <span class="required">*</span></label>
                                            <select id="quickVenue" name="venue" required>
                                                <option value=""><?= htmlspecialchars($trans['venue_placeholder']) ?></option>
                                                <?php foreach ($halls as $hall): ?>
                                                    <option value="<?php echo htmlspecialchars($hall['id']); ?>"><?php echo htmlspecialchars($hall['name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="quickBudget"><?= htmlspecialchars($trans['budget_label']) ?></label>
                                            <input type="text" id="quickBudget" name="budget" placeholder="<?= htmlspecialchars($trans['budget_placeholder']) ?>">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="quickEventDate"><?= htmlspecialchars($trans['checkin_label']) ?> <span class="required">*</span></label>
                                            <input type="date" id="quickEventDate" name="eventDate" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="quickEndDate"><?= htmlspecialchars($trans['checkout_label']) ?> <span class="required">*</span></label>
                                            <input type="date" id="quickEndDate" name="endDate" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="quickStartTime"><?= htmlspecialchars($trans['start_time_label']) ?> <span class="required">*</span></label>
                                            <input type="time" id="quickStartTime" name="startTime" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="quickEndTime"><?= htmlspecialchars($trans['end_time_label']) ?> <span class="required">*</span></label>
                                            <input type="time" id="quickEndTime" name="endTime" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="quickDescription"><?= htmlspecialchars($trans['note_label']) ?> <span class="required">*</span></label>
                                        <textarea id="quickDescription" name="description" placeholder="<?= htmlspecialchars($trans['note_placeholder']) ?>" required></textarea>
                                    </div>
                                </div>
                                <div class="form-section">
                                    <h3><?= htmlspecialchars($trans['image_section']) ?></h3>
                                    <div class="form-group">
                                        <div class="upload-area">
                                            <div class="upload-icon">📷</div>
                                            <div class="upload-text">
                                                <?= $trans['upload_text'] ?>
                                            </div>
                                            <input type="file" id="imageUpload" name="images[]" multiple accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <small style="color: #666;">* <?= htmlspecialchars($trans['required_note']) ?></small>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-submit"><?= htmlspecialchars($trans['submit_button']) ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "footer.php" ?>
    <script>
        const languageId = <?php echo json_encode($languageId); ?>;
    </script>
    <script src="/libertylaocai/view/js/pagedetail.js"></script>
</body>

</html>