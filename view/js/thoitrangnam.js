document.addEventListener("DOMContentLoaded", function () {
  const sortSelect = document.getElementById("sort-select");
  const productsContainer = document.getElementById("productContainer");
  const productCards = Array.from(
    productsContainer.getElementsByClassName("product-card")
  );
  const navItems = document.querySelectorAll(".nav-item");
  const productsPerPage = 12;
  let currentPage = 1;
  let filteredCards = [...productCards]; // Mảng sản phẩm đã lọc

  // Hàm lọc sản phẩm theo từ khóa từ nav-item
  function filterProducts(keyword) {
    if (!keyword) {
      filteredCards = [...productCards]; // Hiển thị tất cả nếu không có từ khóa
    } else {
      filteredCards = productCards.filter((card) => {
        const title = card
          .querySelector(".product-title")
          .textContent.toLowerCase();
        return title.includes(keyword.toLowerCase());
      });
    }
    currentPage = 1; // Reset về trang 1 khi lọc
    showProductsAndPagination();
  }

  // Hàm hiển thị sản phẩm và phân trang
  function showProductsAndPagination() {
    productsContainer.innerHTML = ""; // Xóa nội dung hiện tại

    // Hiển thị sản phẩm đã lọc
    const startIndex = (currentPage - 1) * productsPerPage;
    const endIndex = startIndex + productsPerPage;
    filteredCards.slice(startIndex, endIndex).forEach((card) => {
      productsContainer.appendChild(card);
    });

    // Hiển thị phân trang
    showPagination();
  }

  // Hàm phân trang
  function showPagination() {
    const existingPagination = document.querySelector(".pagination");
    if (existingPagination) {
      existingPagination.remove();
    }

    const totalPages = Math.ceil(filteredCards.length / productsPerPage);

    // Hiển thị các liên kết phân trang
    const paginationContainer = document.createElement("div");
    paginationContainer.className = "pagination";

    if (currentPage > 1) {
      const prevLink = document.createElement("a");
      prevLink.href = "#";
      prevLink.textContent = "«";
      prevLink.addEventListener("click", (e) => {
        e.preventDefault();
        currentPage--;
        showProductsAndPagination();
      });
      paginationContainer.appendChild(prevLink);
    }

    for (let i = 1; i <= totalPages; i++) {
      const pageLink = document.createElement("a");
      pageLink.href = "#";
      pageLink.textContent = i;

      if (i === currentPage) {
        pageLink.classList.add("active");
      }

      pageLink.addEventListener("click", (e) => {
        e.preventDefault();
        currentPage = i;
        showProductsAndPagination();
      });

      paginationContainer.appendChild(pageLink);
    }

    if (currentPage < totalPages) {
      const nextLink = document.createElement("a");
      nextLink.href = "#";
      nextLink.textContent = "»";
      nextLink.classList.add("next");
      nextLink.addEventListener("click", (e) => {
        e.preventDefault();
        currentPage++;
        showProductsAndPagination();
      });
      paginationContainer.appendChild(nextLink);
    }

    productsContainer.appendChild(paginationContainer);
  }

  // Xử lý sự kiện click trên nav-item
  navItems.forEach((item) => {
    item.addEventListener("click", function () {
      const text = this.textContent.trim(); // Lấy nội dung văn bản của nút (ví dụ: "Áo thể thao Nam")

      // Xác định từ khóa để lọc dựa trên nội dung nút
      let keyword = "";
      if (text.includes("Áo")) keyword = "Áo";
      else if (text.includes("Quần")) keyword = "Quần";
      else if (text.includes("Bộ")) keyword = "Bộ";
      else if (text.includes("Giày")) keyword = "Giày";

      filterProducts(keyword);
    });
  });

  // Xử lý sắp xếp
  sortSelect.addEventListener("change", function () {
    const sortValue = this.value;
    let sortedCards;

    switch (sortValue) {
      case "price-asc":
        sortedCards = filteredCards.sort((a, b) => {
          const priceA = parseFloat(
            a
              .querySelector(".product-price")
              .textContent.replace(/[^0-9.-]+/g, "")
          );
          const priceB = parseFloat(
            b
              .querySelector(".product-price")
              .textContent.replace(/[^0-9.-]+/g, "")
          );
          return priceA - priceB;
        });
        break;
      case "price-desc":
        sortedCards = filteredCards.sort((a, b) => {
          const priceA = parseFloat(
            a
              .querySelector(".product-price")
              .textContent.replace(/[^0-9.-]+/g, "")
          );
          const priceB = parseFloat(
            b
              .querySelector(".product-price")
              .textContent.replace(/[^0-9.-]+/g, "")
          );
          return priceB - priceA;
        });
        break;
      default:
        sortedCards = [...filteredCards];
    }

    filteredCards = sortedCards; // Cập nhật mảng đã lọc
    showProductsAndPagination();
  });

  // Khởi tạo hiển thị tất cả sản phẩm
  showProductsAndPagination();
});
