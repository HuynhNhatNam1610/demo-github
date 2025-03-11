document.addEventListener('DOMContentLoaded', function() {
    // Phần xử lý NEW ARRIVALS !
    const productContainer = document.querySelector('.product-pages');
    const pagination = document.querySelector('.pagination');
    const products = [
        { name: "Giày thể thao Nam Nữ Promax Muran...", price: "599.000đ", originalPrice: "666.000đ", img: "../img/giay.webp", alt: "Jogarbola Kaze Grey" },
        { name: "Giày thể thao Nam Nữ Promax Muran...", price: "599.000đ", originalPrice: "666.000đ", img: "../img/giay1.webp", alt: "Jogarbola Cloud Step Blue" },
        { name: "Giày thể thao Nam Nữ Promax Muran...", price: "599.000đ", originalPrice: "666.000đ", img: "../img/giay2.webp", alt: "Jogarbola Cloud Step Dark" },
        { name: "Giày thể thao Nam Nữ Promax Muran...", price: "599.000đ", originalPrice: "666.000đ", img: "../img/giay.webp", alt: "Jogarbola Cloud Step White" },
        { name: "Giày thể thao Nam Nữ Promax Muran...", price: "599.000đ", originalPrice: "666.000đ", img: "../img/giay1.webp", alt: "Jogarbola Kaze Black" },
        { name: "Giày thể thao Nam Nữ Promax Muran...", price: "599.000đ", originalPrice: "666.000đ", img: "../img/giay2.webp", alt: "Jogarbola Speed Red" },
        { name: "Giày thể thao Nam Nữ Promax Muran...", price: "599.000đ", originalPrice: "666.000đ", img: "../img/giay.webp", alt: "Jogarbola Speed Green" },
        { name: "Giày thể thao Nam Nữ Promax Muran...", price: "599.000đ", originalPrice: "666.000đ", img: "../img/giay1.webp", alt: "Jogarbola Lite Yellow" },
        { name: "Giày thể thao Nam Nữ Promax Muran...", price: "599.000đ", originalPrice: "666.000đ", img: "../img/giay2.webp", alt: "Jogarbola Lite Purple" },
        { name: "Giày thể thao Nam Nữ Promax Muran...", price: "599.000đ", originalPrice: "666.000đ", img: "../img/giay.webp", alt: "Jogarbola Kaze Orange" }
    ];

    if (!productContainer || !pagination) {
        console.error('Product container or pagination not found');
        return;
    }

    function updateProductPagination() {
        productContainer.innerHTML = '';
        pagination.innerHTML = '';

        let cols = window.innerWidth < 576 ? 2 : window.innerWidth < 768 ? 2 : window.innerWidth < 992 ? 3 : 5;
        const itemsPerPage = cols;
        const totalPages = Math.ceil(products.length / itemsPerPage);

        for (let i = 0; i < totalPages; i++) {
            const page = document.createElement('div');
            page.classList.add('product-page');
            if (i === 0) page.classList.add('active');
            else page.style.display = 'none';

            const row = document.createElement('div');
            row.classList.add('row', `row-cols-${cols}`, `row-cols-md-${cols}`, `row-cols-lg-5`, 'g-3');

            const start = i * itemsPerPage;
            const end = start + itemsPerPage;
            const pageProducts = products.slice(start, end);

            pageProducts.forEach(product => {
                const col = document.createElement('div');
                col.classList.add('col');
                col.innerHTML = `
                    <div class="card h-100 product-card-custom">
                        <div class="card-img-container">
                            <img src="${product.img}" class="card-img-top" alt="${product.alt}">
                        </div>
                        <div class="card-body text-center">
                            <div class="discount-badge">GIẢM SỐC</div>
                            <h5 class="product-title">${product.name}</h5>
                            <p class="product-price-original text-muted text-decoration-line-through">${product.originalPrice}</p>
                            <p class="product-price-discounted">${product.price}</p>
                            <button class="btn in-stock-btn w-100">Còn hàng</button>
                        </div>
                    </div>
                `;
                row.appendChild(col);
            });

            page.appendChild(row);
            productContainer.appendChild(page);
        }

        for (let i = 0; i < totalPages; i++) {
            const dot = document.createElement('div');
            dot.classList.add('pagination-dot');
            if (i === 0) dot.classList.add('active');
            dot.setAttribute('data-page', i + 1);
            pagination.appendChild(dot);
        }

        const paginationDots = document.querySelectorAll('.pagination-dot');
        const productPages = document.querySelectorAll('.product-page');

        paginationDots.forEach(dot => {
            dot.addEventListener('click', function() {
                const page = this.getAttribute('data-page');
                productPages.forEach(pageElement => {
                    pageElement.style.display = 'none';
                    pageElement.classList.remove('active');
                });
                const activePage = document.querySelector(`.product-page:nth-child(${page})`);
                activePage.style.display = 'block';
                activePage.classList.add('active');
                paginationDots.forEach(d => d.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }

    // Phần xử lý Sports News Section
    const newsContainer = document.querySelector('.news-pages');
    const newsPagination = document.querySelector('.news-pagination');
    const newsItems = [
        { type: "small", href: "/news/so-san-luat-bong-chuyen-co-ban", img: "https://bizweb.dktcdn.net/100/485/982/articles/2-1692171388220.png?v=1692171393273", alt: "News Image", title: "SỔ SÁCH LUẬT BÓNG CHUYỀN CƠ BẢN VÀ LUẬT BÓNG CHUYỀN THI ĐẤU" },
        { type: "small", href: "/news/tat-tan-tat-ve-bong-chuyen", img: "https://bizweb.dktcdn.net/thumb/medium/100/485/982/articles/1-1692171388220.png?v=1692171393273", alt: "News Image 1", title: "Tất tần tật về bóng chuyền: kỹ năng, kỹ thuật và lợi khuyên..." },
        { type: "small", href: "/news/cach-chon-mua-bong-ro", img: "https://bizweb.dktcdn.net/thumb/medium/100/485/982/articles/3-1692171388220.png?v=1692171393273", alt: "News Image 2", title: "Cách chọn mua bóng rổ tốt nhất và 9 điều cần chú ý đế..." },
        { type: "small", href: "/news/danh-bong-chuyen-nen-di-giay", img: "https://bizweb.dktcdn.net/thumb/medium/100/485/982/articles/4-1692171388220.png?v=1692171393273", alt: "News Image 3", title: "Đánh bóng chuyền nên đi giày gì? Lựa chọn tốt nhất..." },
        { type: "small", href: "/news/tu-van-cach-danh-chuyen", img: "https://bizweb.dktcdn.net/thumb/medium/100/485/982/articles/5-1692171388220.png?v=1692171393273", alt: "News Image 4", title: "Tư vấn cách đánh chuyền chuyên châu Á, nguồn mở t..." },
        { type: "small", href: "/news/tieu-chi-lua-chon-qua-bong", img: "https://bizweb.dktcdn.net/thumb/medium/100/485/982/articles/6-1692171388220.png?v=1692171393273", alt: "News Image 5", title: "Tiêu chí lựa chọn quả bóng da phù hợp" },
        { type: "small", href: "/news/cach-chon-mua-bong-ro-tot-nhat", img: "https://bizweb.dktcdn.net/thumb/medium/100/485/982/articles/7-1692171388220.png?v=1692171393273", alt: "News Image 6", title: "Cách chọn mua bóng rổ tốt nhất và 9 điều cần chú ý đế..." }
    ];

    if (!newsContainer || !newsPagination) {
        console.error('News container or pagination not found');
        return;
    }

    function updateNewsPagination() {
        newsContainer.innerHTML = '';
        newsPagination.innerHTML = '';

        let cols = window.innerWidth < 992 ? 3 : 6;
        const itemsPerPage = cols;
        const totalPages = Math.ceil(newsItems.length / itemsPerPage);

        for (let i = 0; i < totalPages; i++) {
            const page = document.createElement('div');
            page.classList.add('news-page');
            if (i === 0) page.classList.add('active');
            else page.style.display = 'none';

            const row = document.createElement('div');
            row.classList.add('row', `row-cols-${cols}`, `row-cols-lg-${cols}`, 'g-3');

            const start = i * itemsPerPage;
            const end = start + itemsPerPage;
            const pageNews = newsItems.slice(start, end);

            pageNews.forEach(news => {
                const col = document.createElement('div');
                col.classList.add('col');
                if (news.type === "main") {
                    col.innerHTML = `
                        <div class="main-news-article">
                            <a href="${news.href}" class="news-link">
                                <img src="${news.img}" alt="${news.alt}" class="news-img">
                                <div class="news-info">
                                    <h3 class="news-title">${news.title}</h3>
                                    <p class="news-excerpt">${news.excerpt || ''}</p>
                                </div>
                            </a>
                        </div>
                    `;
                } else {
                    col.innerHTML = `
                        <div class="small-news-article">
                            <a href="${news.href}" class="news-link">
                                <img src="${news.img}" alt="${news.alt}" class="small-news-img">
                                <div class="small-news-info">
                                    <h6 class="small-news-title">${news.title}</h6>
                                </div>
                            </a>
                        </div>
                    `;
                }
                row.appendChild(col);
            });

            page.appendChild(row);
            newsContainer.appendChild(page);
        }

        for (let i = 0; i < totalPages; i++) {
            const dot = document.createElement('div');
            dot.classList.add('news-pagination-dot');
            if (i === 0) dot.classList.add('active');
            dot.setAttribute('data-page', i + 1);
            newsPagination.appendChild(dot);
        }

        const paginationDots = document.querySelectorAll('.news-pagination-dot');
        const newsPages = document.querySelectorAll('.news-page');

        paginationDots.forEach(dot => {
            dot.addEventListener('click', function() {
                const page = this.getAttribute('data-page');
                newsPages.forEach(pageElement => {
                    pageElement.style.display = 'none';
                    pageElement.classList.remove('active');
                });
                const activePage = document.querySelector(`.news-page:nth-child(${page})`);
                activePage.style.display = 'block';
                activePage.classList.add('active');
                paginationDots.forEach(d => d.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }

    // Hàm debounce để tối ưu resize
    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // Cập nhật lại pagination khi thay đổi kích thước màn hình
    window.addEventListener('resize', debounce(() => {
        updateProductPagination();
        updateNewsPagination();
    }, 200));

    // Khởi chạy lần đầu
    updateProductPagination();
    updateNewsPagination();

    // Xử lý carousel
    document.querySelectorAll('.carousel-container').forEach(container => {
        const inner = container.querySelector('.carousel-inner');
        const prevBtn = container.querySelector('.carousel-control-prev');
        const nextBtn = container.querySelector('.carousel-control-next');

        if (!inner || !prevBtn || !nextBtn) {
            console.error('Carousel elements not found:', { inner, prevBtn, nextBtn });
            return;
        }

        let scrollAmount = 0;
        const colElement = inner.querySelector('.col');
        if (!colElement) {
            console.error('No .col element found inside .carousel-inner');
            return;
        }

        const scrollStep = colElement.offsetWidth + 15;

        nextBtn.addEventListener('click', () => {
            const maxScroll = inner.scrollWidth - inner.clientWidth;
            scrollAmount += scrollStep;
            if (scrollAmount > maxScroll) scrollAmount = maxScroll;
            inner.scrollTo({ left: scrollAmount, behavior: 'smooth' });
        });

        prevBtn.addEventListener('click', () => {
            scrollAmount -= scrollStep;
            if (scrollAmount < 0) scrollAmount = 0;
            inner.scrollTo({ left: scrollAmount, behavior: 'smooth' });
        });

        // Ẩn nút nếu không cần cuộn
        if (inner.scrollWidth <= inner.clientWidth) {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
        }
    });
});