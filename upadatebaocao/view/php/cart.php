<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - NHL SPORTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/cart.css">
</head>
<body>
    <?php include "header.php"; ?>

    <div class="container">
        <h1>Giỏ Hàng Của Bạn</h1>
        <div class="cart-container">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody id="cart-items">
                    <!-- Các sản phẩm sẽ được thêm bằng JavaScript -->
                </tbody>
            </table>
            <div class="cart-summary">
                <div class="voucher-section">
                    <button class="voucher-btn">Chọn mã giảm giá</button>
                    <span id="applied-voucher" style="color: #ff0000; margin-left: 10px;"></span>
                </div>
                <h3>Tổng cộng: <span id="cart-total">0đ</span></h3>
                <button class="checkout-btn">Thanh toán</button>
            </div>
        </div>

        <!-- Popup mã giảm giá -->
        <div class="voucher-popup" id="voucher-popup">
            <div class="voucher-content">
                <h2>Các mã giảm giá có thể áp dụng:</h2>
                <div class="voucher-list">
                    <div class="voucher-item" data-code="WTT50" data-discount="50000">
                        <span>50K</span>
                        <p>Mã giảm giá WTT50<br>Nhập mã WTT50K giảm ngay 50K</p>
                        <button class="apply-btn">Sao chép</button>
                        <button class="select-btn">Điểu kiện</button>
                    </div>
                    <div class="voucher-item" data-code="WTT100" data-discount="100000">
                        <span>100K</span>
                        <p>Mã giảm giá WTT100<br>Nhập mã WTT100K giảm ngay 100K</p>
                        <button class="apply-btn">Sao chép</button>
                        <button class="select-btn">Điểu kiện</button>
                    </div>
                    <div class="voucher-item" data-code="WTT200" data-discount="200000">
                        <span>200K</span>
                        <p>Mã giảm giá WTT200<br>Nhập mã WTT200K giảm ngay 200K</p>
                        <button class="apply-btn">Sao chép</button>
                        <button class="select-btn">Điểu kiện</button>
                    </div>
                    <div class="voucher-item" data-code="FREESHIP" data-discount="0" data-freeship="true">
                        <span>Freeship</span>
                        <p>Mã giảm giá FREESHIP<br>Nhận mã FREESHIP miễn phí vận chuyển</p>
                        <button class="apply-btn">Sao chép</button>
                        <button class="select-btn">Điểu kiện</button>
                    </div>
                </div>
                <button class="close-btn">Đóng</button>
            </div>
        </div>
    </div>

    <?php include "footer.php"; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/cart.js"></script>
</body>
</html>