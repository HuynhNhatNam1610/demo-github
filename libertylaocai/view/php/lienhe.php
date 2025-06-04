<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ - Liberty Lào Cai</title>
    <link rel="stylesheet" href="/libertylaocai/view/css/lienhe.css">
</head>

<body>
    <?php include "header.php"; ?>
    <div class="contact-container">
        <div class="contact-header">
            <h1>Liên Hệ</h1>
            <div class="header-line"></div>
        </div>

        <div class="contact-content">
            <!-- Thông tin liên hệ bên trái -->
            <div class="contact-info1">
                <div class="info-section">
                    <h2>KHÁCH SẠN LIBERTY LÀO CAI</h2>
                    <p class="description">
                        Khách sạn Liberty Lào Cai được hình thành nhằm mục đích phục vụ
                        nhu cầu Lưu Trú và Nghỉ Dưỡng cho Du Khách đến du lịch tại Thành Phố Hà Tiên.
                        Với vị trí thuận lợi và dịch vụ chuyên nghiệp, chúng tôi cam kết mang đến
                        trải nghiệm thoải mái và đáng nhớ cho mọi du khách. Đội ngũ nhân viên tận tâm
                        luôn sẵn sàng hỗ trợ quý khách 24/7 để đảm bảo kỳ nghỉ của bạn trọn vẹn nhất.
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
                            <span>120 Đường Soi Tiền, Phường Kim Tân, TP. Lào Cai, Tỉnh Lào Cai</span>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="contact-text">
                            <span>Hotline: 0214 366 1666</span>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="contact-text">
                            <span>Email: chamsockhachhang.liberty@gmail.com</span>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="contact-text">
                            <span>Facebook: Liberty Hotel & Events Khách sạn Liberty Lào Cai</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form liên hệ bên phải -->
            <div class="contact-form-section">
                <h3>GỬI THÔNG TIN LIÊN HỆ</h3>
                <p class="form-description">
                    Vui lòng nhập đầy đủ thông tin bên dưới Liberty Lào Cai sẽ liên hệ ngay khi nhận
                    được yêu cầu!
                </p>

                <form class="contact-form" id="contactForm">
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" id="fullName" name="fullName" placeholder="Họ & Tên *" required>
                        </div>
                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="Địa chỉ Email *" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" id="phone" name="phone" placeholder="Số điện thoại" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="text" id="subject" name="subject" placeholder="Tiêu đề">
                    </div>

                    <div class="form-group">
                        <textarea id="message" name="message" rows="6" placeholder="Nội dung liên hệ"></textarea>
                    </div>

                    <button type="submit" class="submit-btn">GỬI THÔNG TIN</button>
                </form>
            </div>
        </div>
    </div>
    <?php include "footer.php"; ?>

    <script src="/libertylaocai/view/js/lienhe.js"></script>
</body>

</html>