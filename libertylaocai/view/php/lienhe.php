<?php
require_once "session.php";
require_once "../../model/UserModel.php";

// Lấy language_id từ session, mặc định là 1 (tiếng Việt)
$languageId = isset($_SESSION['language_id']) ? (int)$_SESSION['language_id'] : 1;

// Lấy thông tin khách sạn theo ngôn ngữ
$informationHotel = getHotelInfoWithLanguage($languageId);

$form_title = $form_info['title'] ?? ($languageId == 1 ? 'GỬI THÔNG TIN LIÊN HỆ' : 'SEND CONTACT INFORMATION');

?>

<!DOCTYPE html>
<html lang="<?php echo $languageId == 1 ? 'vi' : 'en'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($languageId == 1 ? 'Liên Hệ' : 'Contact'); ?> - <?php echo htmlspecialchars($hotel_short_name); ?></title>
    <link rel="stylesheet" href="/libertylaocai/view/css/lienhe.css">
</head>

<body>
    <?php include "header.php"; ?>
    <div class="contact-container">
        <div class="contact-header">
            <h1><?php echo $languageId == 1 ? 'Liên Hệ' : 'Contact'; ?></h1>
            <div class="header-line"></div>
        </div>

        <div class="contact-content">
            <!-- Thông tin liên hệ bên trái -->
            <div class="contact-info1">
                <?php foreach ($informationHotel as $info): ?>
                    <div class="info-section">
                        <h2><?php echo $info['name']; ?></h2>
                        <p class="description">
                            <?php echo $info['description']; ?>
                        </p>
                    </div>


                    <div class="contact-details">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor" />
                                </svg>
                            </div>
                            <div class="contact-text">
                                <span><a href="<?php echo htmlspecialchars($info['position']); ?>"> <?php echo htmlspecialchars($info['address']); ?></a></span>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" fill="currentColor" />
                                </svg>
                            </div>
                            <div class="contact-text">
                                <span><?php echo $languageId == 1 ? 'Hotline' : 'Hotline'; ?>: <?php echo $info['phone']; ?></span>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" fill="currentColor" />
                                </svg>
                            </div>
                            <div class="contact-text">
                                <span><?php echo $languageId == 1 ? 'Email' : 'Email'; ?>: <?php echo $info['email']; ?></span>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="currentColor" />
                                </svg>
                            </div>
                            <div class="contact-text">
                                <span>Facebook: <a href="<?php echo htmlspecialchars($info['link_facebook']); ?>"> <?php echo htmlspecialchars($info['facebook']); ?></a></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Form liên hệ bên phải -->
            <div class="contact-form-section">
                <h3><?php echo htmlspecialchars($form_title); ?></h3>
                <p class="form-description">
                    <?php echo $languageId == 1 ? 'Vui lòng nhập đầy đủ thông tin bên dưới chúng tôi sẽ liên hệ bạn ngay khi nhận được yêu cầu!' : 'Please fill in the information below and we will contact you as soon as we receive your request!'; ?>
                </p>

                <form class="contact-form" id="contactForm">
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" id="fullName" name="fullName" placeholder="<?php echo $languageId == 1 ? 'Họ & Tên *' : 'Full Name *'; ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="<?php echo $languageId == 1 ? 'Địa chỉ Email *' : 'Email Address *'; ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" id="phone" name="phone" placeholder="<?php echo $languageId == 1 ? 'Số điện thoại' : 'Phone Number'; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="text" id="subject" name="subject" placeholder="<?php echo $languageId == 1 ? 'Tiêu đề' : 'Subject'; ?>">
                    </div>

                    <div class="form-group">
                        <textarea id="message" name="message" rows="6" placeholder="<?php echo $languageId == 1 ? 'Nội dung liên hệ' : 'Contact Message'; ?>"></textarea>
                    </div>

                    <button type="submit" class="submit-btn"><?php echo $languageId == 1 ? 'GỬI THÔNG TIN' : 'SEND MESSAGE'; ?></button>
                </form>
            </div>
        </div>
    </div>
    <?php include "footer.php"; ?>

    <script src="/libertylaocai/view/js/lienhe.js"></script>
</body>

</html>