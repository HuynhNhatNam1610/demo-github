document.addEventListener('DOMContentLoaded', function() {
    // Lấy giỏ hàng từ localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let appliedVoucher = null;

    // Hiển thị sản phẩm trong giỏ hàng
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    const appliedVoucherSpan = document.getElementById('applied-voucher');
    const voucherPopup = document.getElementById('voucher-popup');

    function renderCart() {
        cartItems.innerHTML = '';
        let total = 0;

        if (cart.length === 0) {
            cartItems.innerHTML = '<tr><td colspan="5">Giỏ hàng trống!</td></tr>';
            cartTotal.textContent = '0đ';
            appliedVoucherSpan.textContent = '';
            return;
        }

        cart.forEach((item, index) => {
            const row = document.createElement('tr');
            const itemTotal = item.price * item.quantity;
            total += itemTotal;

            row.innerHTML = `
                <td class="product-info">
                    <img src="${item.image}" alt="${item.name}">
                    <span>${item.name}</span>
                </td>
                <td class="price">${item.price.toLocaleString()}đ</td>
                <td><input type="number" min="1" value="${item.quantity}" data-index="${index}"></td>
                <td class="price">${itemTotal.toLocaleString()}đ</td>
                <td><button class="remove-btn" data-index="${index}"><i class="bi bi-trash"></i></button></td>
            `;
            cartItems.appendChild(row);
        });

        // Áp dụng mã giảm giá nếu có
        if (appliedVoucher) {
            const voucherItem = document.querySelector(`.voucher-item[data-code="${appliedVoucher.code}"]`);
            if (voucherItem) {
                const discount = appliedVoucher.discount;
                const freeship = appliedVoucher.freeship;
                if (discount > 0) {
                    total -= discount;
                    appliedVoucherSpan.textContent = `Đã áp dụng: ${appliedVoucher.code} (-${discount.toLocaleString()}đ)`;
                } else if (freeship) {
                    appliedVoucherSpan.textContent = `Đã áp dụng: ${appliedVoucher.code} (Freeship)`;
                }
            }
        }

        cartTotal.textContent = total.toLocaleString() + 'đ';
    }

    // Cập nhật giỏ hàng khi thay đổi số lượng
    cartItems.addEventListener('change', function(e) {
        if (e.target.tagName === 'INPUT') {
            const index = e.target.getAttribute('data-index');
            const newQuantity = parseInt(e.target.value);
            if (newQuantity > 0) {
                cart[index].quantity = newQuantity;
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCart();
            }
        }
    });

    // Xóa sản phẩm khỏi giỏ hàng
    cartItems.addEventListener('click', function(e) {
        if (e.target.closest('.remove-btn')) {
            const index = e.target.closest('.remove-btn').getAttribute('data-index');
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }
    });

    // Mở popup mã giảm giá
    document.querySelector('.voucher-btn').addEventListener('click', function() {
        voucherPopup.style.display = 'block';
    });

    // Đóng popup
    document.querySelector('.close-btn').addEventListener('click', function() {
        voucherPopup.style.display = 'none';
    });

    // Áp dụng mã giảm giá
    document.querySelectorAll('.apply-btn').forEach(button => {
        button.addEventListener('click', function() {
            const voucherItem = this.parentElement;
            appliedVoucher = {
                code: voucherItem.getAttribute('data-code'),
                discount: parseInt(voucherItem.getAttribute('data-discount')) || 0,
                freeship: voucherItem.getAttribute('data-freeship') === 'true'
            };
            voucherPopup.style.display = 'none';
            renderCart();
        });
    });

    // Ban đầu hiển thị giỏ hàng
    renderCart();
});