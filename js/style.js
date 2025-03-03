// 1. Thay đổi tất cả các phần tử HTML
document.querySelectorAll('h5').forEach(el => {
    el.innerHTML = "Sản phẩm HOT 2024!";
});

// 2. Thay đổi tất cả thuộc tính HTML
document.querySelectorAll('img').forEach(img => {
    img.setAttribute('src', '../img/logo.webp');
    img.setAttribute('alt', 'Hình ảnh mới');
});

// 3. Thay đổi tất cả các style CSS
document.querySelectorAll('button').forEach(btn => {
    btn.style.backgroundColor = "red";
    btn.style.color = "white";
});

// 4. Xóa phần tử và thuộc tính HTML
document.querySelectorAll('.price').forEach(price => {
    price.remove();
});

// 5. Thêm phần tử và thuộc tính HTML mới
let banner = document.createElement('div');
banner.innerHTML = "<h2 class='text-center text-white bg-danger p-3'>🔥 Giảm giá 50% toàn bộ sản phẩm! 🔥</h2>";
document.body.prepend(banner);