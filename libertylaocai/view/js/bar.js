// bar.js
// Floating particles animation
let drinkCurrentPage = 1;
let foodCurrentPage = 1;
let drinkTotalPages = 1;
let foodTotalPages = 1;
const menuLimit = 9; // Số món ăn mỗi trang

function fetchMenuItems(type, page, limit = 9) {
  console.log(
    `Đang lấy món ăn loại ${type} cho trang ${page} với giới hạn ${limit}`
  );
  fetch(
    `/libertylaocai/api/menu_api.php?type=${type}&page=${page}&limit=${limit}&language_id=${languageId}`
  )
    .then((response) => {
      if (!response.ok) {
        throw new Error(`Lỗi HTTP! Trạng thái: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      if (data.status === "success") {
        console.log(data);
        updateMenuItems(data.data, type);
        if (type === "cocktails") {
          drinkCurrentPage = data.data.currentPage;
          drinkTotalPages = data.data.totalPages;
        } else if (type === "main") {
          foodCurrentPage = data.data.currentPage;
          foodTotalPages = data.data.totalPages;
        }
        updateMenuPagination(type);
      } else {
        console.error("Lỗi từ server:", data);
        alert(
          languageId == 1 ? `Lỗi: ${data.message}` : `Error: ${data.message}`
        );
      }
    })
    .catch((error) => {
      console.error(`Lỗi khi lấy món ăn (${type}):`, error);
      alert(
        languageId == 1
          ? "Lỗi khi tải thực đơn. Vui lòng thử lại."
          : "Error loading menu. Please try again."
      );
    });
}

function updateMenuItems(data, type) {
  const menuGrid = document.getElementById("menuItems");
  // Chỉ xóa các món ăn thuộc danh mục hiện tại
  const existingItems = menuGrid.querySelectorAll(
    `.service-featured-detail[data-category="${type}"]`
  );
  existingItems.forEach((item) => item.remove());

  if (data.menuItems && data.menuItems.length > 0) {
    data.menuItems.forEach((item) => {
      const itemDiv = document.createElement("div");
      itemDiv.className = "service-featured-detail";
      itemDiv.dataset.category = type;
      itemDiv.style.display =
        type === document.querySelector(".category-btn.active").dataset.category
          ? "block"
          : "none";
      itemDiv.innerHTML = `
        <div class="featured-img">
          <img src="/libertylaocai/view/img/${
            item.image || "default-image.jpg"
          }" alt="${item.name}">
        </div>
        <div class="featured-content">
          <h3 class="featured-title">${item.name}</h3>
          <p class="featured-description">${item.content || ""}</p>
          <div class="price-container">
            <div class="price">${Number(item.price).toLocaleString(
              "vi-VN"
            )} VNĐ</div>
          </div>
        </div>
      `;
      menuGrid.appendChild(itemDiv);
    });
  } else {
    const noItems = document.createElement("p");
    noItems.textContent =
      languageId == 1
        ? "Không có món ăn nào để hiển thị."
        : "No menu items available.";
    noItems.dataset.category = type;
    menuGrid.appendChild(noItems);
  }
}

// function updateMenuPagination(type) {
//   const paginationContainer = document.querySelector(
//     `.menu-pagination-buttons[data-category="${type}"]`
//   );
//   const totalPages = type === "cocktails" ? drinkTotalPages : foodTotalPages;
//   const currentPage = type === "cocktails" ? drinkCurrentPage : foodCurrentPage;

//   if (totalPages <= 1) {
//     paginationContainer.style.display = "none";
//     return;
//   }

//   paginationContainer.style.display = "flex";
//   paginationContainer.innerHTML = "";

//   const maxButtons = 5;
//   let startPage = Math.max(1, currentPage - Math.floor((maxButtons - 1) / 2));
//   let endPage = Math.min(totalPages, startPage + maxButtons - 1);

//   // Nút Previous
//   if (currentPage > 1) {
//     const prevBtn = document.createElement("button");
//     prevBtn.className = "pagination-btn prev-btn";
//     prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
//     prevBtn.addEventListener("click", () =>
//       fetchMenuItems(type, currentPage - 1, menuLimit)
//     );
//     paginationContainer.appendChild(prevBtn);
//   }

//   // Nút trang đầu
//   if (startPage > 1) {
//     const firstPageBtn = document.createElement("button");
//     firstPageBtn.className = "pagination-btn";
//     firstPageBtn.textContent = "1";
//     firstPageBtn.addEventListener("click", () =>
//       fetchMenuItems(type, 1, menuLimit)
//     );
//     paginationContainer.appendChild(firstPageBtn);

//     if (startPage > 2) {
//       const ellipsis = document.createElement("span");
//       ellipsis.className = "pagination-ellipsis";
//       ellipsis.textContent = "...";
//       paginationContainer.appendChild(ellipsis);
//     }
//   }

//   // Nút trang
//   for (let i = startPage; i <= endPage; i++) {
//     const pageBtn = document.createElement("button");
//     pageBtn.className = `pagination-btn ${i === currentPage ? "active" : ""}`;
//     pageBtn.textContent = i;
//     pageBtn.addEventListener("click", () => fetchMenuItems(type, i, menuLimit));
//     paginationContainer.appendChild(pageBtn);
//   }

//   // Nút trang cuối
//   if (endPage < totalPages) {
//     if (endPage < totalPages - 1) {
//       const ellipsis = document.createElement("span");
//       ellipsis.className = "pagination-ellipsis";
//       ellipsis.textContent = "...";
//       paginationContainer.appendChild(ellipsis);
//     }
//     const lastPageBtn = document.createElement("button");
//     lastPageBtn.className = "pagination-btn";
//     lastPageBtn.textContent = totalPages;
//     lastPageBtn.addEventListener("click", () =>
//       fetchMenuItems(type, totalPages, menuLimit)
//     );
//     paginationContainer.appendChild(lastPageBtn);
//   }

//   // Nút Next
//   if (currentPage < totalPages) {
//     const nextBtn = document.createElement("button");
//     nextBtn.className = "pagination-btn next-btn";
//     nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
//     nextBtn.addEventListener("click", () =>
//       fetchMenuItems(type, currentPage + 1, menuLimit)
//     );
//     paginationContainer.appendChild(nextBtn);
//   }
// }
function updateMenuPagination(type) {
  const paginationContainer = document.querySelector(
    `.menu-pagination-buttons[data-category="${type}"]`
  );
  const totalPages = type === "cocktails" ? drinkTotalPages : foodTotalPages;
  const currentPage = type === "cocktails" ? drinkCurrentPage : foodCurrentPage;

  // Ẩn tất cả các container phân trang trước
  document
    .querySelectorAll(".menu-pagination-buttons")
    .forEach((pagination) => {
      pagination.style.display = "none";
    });

  // Chỉ hiển thị container phân trang của danh mục hiện tại nếu có nhiều hơn 1 trang
  if (totalPages <= 1) {
    paginationContainer.style.display = "none";
    return;
  }

  paginationContainer.style.display = "flex";
  paginationContainer.innerHTML = "";

  const maxButtons = 5;
  let startPage = Math.max(1, currentPage - Math.floor((maxButtons - 1) / 2));
  let endPage = Math.min(totalPages, startPage + maxButtons - 1);

  // Nút Previous
  if (currentPage > 1) {
    const prevBtn = document.createElement("button");
    prevBtn.className = "pagination-btn prev-btn";
    prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
    prevBtn.addEventListener("click", () =>
      fetchMenuItems(type, currentPage - 1, menuLimit)
    );
    paginationContainer.appendChild(prevBtn);
  }

  // Nút trang đầu
  if (startPage > 1) {
    const firstPageBtn = document.createElement("button");
    firstPageBtn.className = "pagination-btn";
    firstPageBtn.textContent = "1";
    firstPageBtn.addEventListener("click", () =>
      fetchMenuItems(type, 1, menuLimit)
    );
    paginationContainer.appendChild(firstPageBtn);

    if (startPage > 2) {
      const ellipsis = document.createElement("span");
      ellipsis.className = "pagination-ellipsis";
      ellipsis.textContent = "...";
      paginationContainer.appendChild(ellipsis);
    }
  }

  // Nút trang
  for (let i = startPage; i <= endPage; i++) {
    const pageBtn = document.createElement("button");
    pageBtn.className = `pagination-btn ${i === currentPage ? "active" : ""}`;
    pageBtn.textContent = i;
    pageBtn.addEventListener("click", () => fetchMenuItems(type, i, menuLimit));
    paginationContainer.appendChild(pageBtn);
  }

  // Nút trang cuối
  if (endPage < totalPages) {
    if (endPage < totalPages - 1) {
      const ellipsis = document.createElement("span");
      ellipsis.className = "pagination-ellipsis";
      ellipsis.textContent = "...";
      paginationContainer.appendChild(ellipsis);
    }
    const lastPageBtn = document.createElement("button");
    lastPageBtn.className = "pagination-btn";
    lastPageBtn.textContent = totalPages;
    lastPageBtn.addEventListener("click", () =>
      fetchMenuItems(type, totalPages, menuLimit)
    );
    paginationContainer.appendChild(lastPageBtn);
  }

  // Nút Next
  if (currentPage < totalPages) {
    const nextBtn = document.createElement("button");
    nextBtn.className = "pagination-btn next-btn";
    nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
    nextBtn.addEventListener("click", () =>
      fetchMenuItems(type, currentPage + 1, menuLimit)
    );
    paginationContainer.appendChild(nextBtn);
  }
}

function createParticles() {
  const particlesContainer = document.getElementById("particles");
  for (let i = 0; i < 50; i++) {
    const particle = document.createElement("div");
    particle.className = "particle";
    particle.style.left = Math.random() * 100 + "%";
    particle.style.animationDelay = Math.random() * 6 + "s";
    particle.style.animationDuration = Math.random() * 3 + 4 + "s";
    particlesContainer.appendChild(particle);
  }
}

// Tab navigation
function initTabs() {
  const navBtns = document.querySelectorAll(".nav-btn");
  const tabContents = document.querySelectorAll(".tab-content");

  navBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const targetTab = btn.dataset.tab;
      navBtns.forEach((b) => b.classList.remove("active"));
      tabContents.forEach((content) => content.classList.remove("active"));
      btn.classList.add("active");
      document.getElementById(targetTab).classList.add("active");
    });
  });
}

// Reservation form handling
function initReservationForm() {
  const form = document.getElementById("reservationForm");
  const dateInput = document.getElementById("date");
  const today = new Date().toISOString().split("T")[0];
  dateInput.min = today;

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    if (!data.name || !data.phone || !data.date || !data.time || !data.guests) {
      alert("Vui lòng điền đầy đủ thông tin bắt buộc!");
      return;
    }

    alert(
      `Cảm ơn ${data.name}! Yêu cầu đặt bàn của bạn đã được ghi nhận. Chúng tôi sẽ liên hệ với bạn qua số ${data.phone} trong thời gian sớm nhất để xác nhận.`
    );
    form.reset();
  });
}

// Parallax effect for hero section
function initParallax() {
  window.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset;
    const heroSection = document.querySelector(".hero-section");
    const parallaxSpeed = 0.5;
    if (heroSection) {
      heroSection.style.transform = `translateY nustY(${
        scrolled * parallaxSpeed
      }px)`;
    }
  });
}

// Review handling
let currentPage = 1;
let currentLimit = 5;
let totalPages = 1;

function fetchReviews(page = 1, limit = 5) {
  console.log(`Đang lấy đánh giá cho trang ${page} với giới hạn ${limit}`);
  fetch(`/libertylaocai/api/bar_review.php?page=${page}&limit=${limit}`)
    .then((response) => {
      console.log("Trạng thái phản hồi:", response.status);
      console.log("Tiêu đề phản hồi:", response.headers);
      if (!response.ok) {
        console.error("Phản hồi lỗi HTTP:", response);
        throw new Error(`Lỗi HTTP! Trạng thái: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      console.log("Dữ liệu nhận được:", data);
      if (data.status === "success") {
        updateReviews(data);
        currentPage = data.currentPage;
        totalPages = data.totalPages;
        currentLimit = limit;
        updatePaginationControls();
      } else {
        console.error("Lỗi từ server:", data);
        alert(
          languageId == 1 ? `Lỗi: ${data.message}` : `Error: ${data.message}`
        );
      }
    })
    .catch((error) => {
      console.error("Lỗi khi lấy đánh giá:", error);
      alert(
        languageId == 1
          ? "Lỗi khi tải đánh giá. Vui lòng thử lại."
          : "Error loading reviews. Please try again."
      );
    });
}

function updateReviews(data) {
  const reviewsList = document.querySelector(".reviews-list");
  const ratingScore = document.querySelector(".rating-score");
  const ratingCount = document.querySelector(".rating-count");
  const ratingStars = document.querySelector(".rating-stars");
  const ratingBreakdown = document.querySelector(".rating-breakdown");

  reviewsList.innerHTML = "";
  if (data.reviews && data.reviews.length > 0) {
    data.reviews.forEach((review) => {
      const reviewDiv = document.createElement("div");
      reviewDiv.className = "review-item";
      let starsHTML = "";
      for (let i = 1; i <= 5; i++) {
        starsHTML +=
          i <= review.rate
            ? '<i class="fas fa-star"></i>'
            : '<i class="far fa-star"></i>';
      }
      const reviewDate = new Date(review.create_at).toLocaleDateString("vi-VN");
      reviewDiv.innerHTML = `
        <div class="review-header">
            <div class="reviewer-info">
                <div class="reviewer-avatar">${review.name
                  .charAt(0)
                  .toUpperCase()}</div>
                <div class="reviewer-details">
                    <div class="reviewer-name">${review.name}</div>
                    <div class="review-date">${reviewDate}</div>
                </div>
            </div>
            <div class="review-rating">${starsHTML}</div>
        </div>
        <div class="review-content"><p>${review.content}</p></div>
      `;
      reviewsList.appendChild(reviewDiv);
    });
  } else {
    reviewsList.innerHTML = `<p>${
      languageId == 1
        ? "Không có đánh giá nào để hiển thị."
        : "No reviews available to display."
    }</p>`;
  }

  ratingScore.textContent = data.totalRating || "0.0";
  ratingCount.textContent = `(${data.totalReviews || 0} ${
    languageId == 1 ? "đánh giá" : "reviews"
  })`;

  ratingStars.innerHTML = "";
  for (let i = 1; i <= 5; i++) {
    if (i <= Math.floor(data.totalRating)) {
      ratingStars.innerHTML += '<i class="fas fa-star"></i>';
    } else if (
      i === Math.ceil(data.totalRating) &&
      data.totalRating % 1 !== 0
    ) {
      ratingStars.innerHTML += '<i class="fas fa-star-half-alt"></i>';
    } else {
      ratingStars.innerHTML += '<i class="far fa-star"></i>';
    }
  }

  ratingBreakdown.innerHTML = "";
  for (let i = 5; i >= 1; i--) {
    const percentage = data.ratingBreakdown?.[i]?.percentage || 0;
    const bar = document.createElement("div");
    bar.className = "rating-bar";
    bar.innerHTML = `
      <span class="rating-label">${i} sao</span>
      <div class="bar-container">
          <div class="bar-fill" style="width: ${percentage}%"></div>
      </div>
      <span class="rating-percent">${percentage}%</span>
    `;
    ratingBreakdown.appendChild(bar);
  }
}

function updatePaginationControls() {
  const paginationControls = document.querySelector(".pagination-controls");
  const showMoreBtn = document.querySelector(".show-more-reviews");
  const paginationButtons = document.querySelector(".pagination-buttons");

  if (totalPages <= 1 && currentLimit >= 10) {
    paginationControls.style.display = "none";
    return;
  }

  paginationControls.style.display = "block";

  if (currentLimit < 10) {
    showMoreBtn.style.display = "block";
    paginationButtons.style.display = "none";
  } else {
    showMoreBtn.style.display = "none";
    paginationButtons.style.display = "flex";

    paginationButtons.innerHTML = "";
    const maxButtons = 5;
    let startPage = Math.max(1, currentPage - Math.floor((maxButtons - 1) / 2));
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);

    if (startPage > 1) {
      const firstPageBtn = document.createElement("button");
      firstPageBtn.className = "pagination-btn";
      firstPageBtn.textContent = "1";
      firstPageBtn.addEventListener("click", () =>
        fetchReviews(1, currentLimit)
      );
      paginationButtons.appendChild(firstPageBtn);

      if (startPage > 2) {
        const ellipsis = document.createElement("span");
        ellipsis.className = "pagination-ellipsis";
        ellipsis.textContent = "...";
        paginationButtons.appendChild(ellipsis);
      }
    }

    for (let i = startPage; i <= endPage; i++) {
      const pageBtn = document.createElement("button");
      pageBtn.className = `pagination-btn ${i === currentPage ? "active" : ""}`;
      pageBtn.textContent = i;
      pageBtn.addEventListener("click", () => fetchReviews(i, currentLimit));
      paginationButtons.appendChild(pageBtn);
    }

    if (endPage < totalPages) {
      if (endPage < totalPages - 1) {
        const ellipsis = document.createElement("span");
        ellipsis.className = "pagination-ellipsis";
        ellipsis.textContent = "...";
        paginationButtons.appendChild(ellipsis);
      }
      const lastPageBtn = document.createElement("button");
      lastPageBtn.className = "pagination-btn";
      lastPageBtn.textContent = totalPages;
      lastPageBtn.addEventListener("click", () =>
        fetchReviews(totalPages, currentLimit)
      );
      paginationButtons.appendChild(lastPageBtn);
    }
  }

  showMoreBtn.removeEventListener("click", handleShowMore);
  showMoreBtn.addEventListener("click", handleShowMore);
}

function handleShowMore() {
  if (currentLimit < 10) {
    currentLimit = 10;
    fetchReviews(1, 10);
  }
}

function toggleReviewForm() {
  const form = document.getElementById("reviewForm");
  const btn = document.querySelector(".write-review-btn");

  if (form.style.display === "none" || form.style.display === "") {
    form.style.display = "block";
    btn.innerHTML = `<i class="fas fa-times"></i> ${
      languageId == 1 ? "Hủy viết đánh giá" : "Cancel writing review"
    }`;
    btn.style.background = "linear-gradient(135deg, #dc3545, #c82333)";
  } else {
    form.style.display = "none";
    btn.innerHTML = `<i class="fas fa-pen"></i> ${
      languageId == 1 ? "Viết đánh giá của bạn" : "Write your review"
    }`;
    btn.style.background = "linear-gradient(135deg, #28a745, #20c997)";
    resetReviewForm();
  }
}

function resetReviewForm() {
  const form = document.querySelector(".review-form");
  form.reset();
  const ratingText = document.querySelector(".rating-text");
  ratingText.textContent = languageId == 1 ? "Chọn số sao" : "Select stars";
  ratingText.style.color = "#666";
  const stars = document.querySelectorAll(".star-rating .star");
  stars.forEach((star) => {
    star.style.color = "#dee2e6";
    star.style.transform = "scale(1)";
  });
}

function submitReview(event) {
  event.preventDefault();
  const formData = new FormData(event.target);
  formData.append("comment_bar", "true");

  const reviewData = {
    rating: formData.get("rating"),
    name: formData.get("reviewer-name"),
    email: formData.get("reviewer-email"),
    content: formData.get("review-content"),
  };

  if (!reviewData.rating) {
    alert(
      languageId == 1
        ? "Vui lòng chọn số sao đánh giá!"
        : "Please select a star rating!"
    );
    return;
  }

  if (!reviewData.name.trim()) {
    alert(
      languageId == 1 ? "Vui lòng nhập họ và tên!" : "Please enter your name!"
    );
    return;
  }

  if (!reviewData.email.trim()) {
    alert(
      languageId == 1 ? "Vui lòng nhập email!" : "Please enter your email!"
    );
    return;
  }

  if (!reviewData.content.trim()) {
    alert(
      languageId == 1
        ? "Vui lòng nhập nội dung đánh giá!"
        : "Please enter review content!"
    );
    return;
  }

  const submitBtn = document.querySelector(".submit-review-btn");
  const originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${
    languageId == 1 ? "Đang gửi..." : "Submitting..."
  }`;
  submitBtn.disabled = true;

  fetch("/libertylaocai/user/submit", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;

      if (data.status === "success") {
        const newReview = createReviewElement(reviewData);
        const reviewsList = document.querySelector(".reviews-list");
        reviewsList.insertBefore(newReview, reviewsList.firstChild);
        fetchReviews(currentPage, currentLimit);
        alert(
          languageId == 1
            ? "Cảm ơn bạn đã chia sẻ đánh giá! Đánh giá của bạn đã được thêm thành công."
            : "Thank you for your review! Your review has been successfully added."
        );
        toggleReviewForm();
        newReview.scrollIntoView({ behavior: "smooth", block: "center" });
      } else {
        alert(
          languageId == 1 ? `Lỗi: ${data.message}` : `Error: ${data.message}`
        );
      }
    })
    .catch((error) => {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
      alert(
        languageId == 1
          ? `Lỗi gửi đánh giá: ${error.message}`
          : `Error submitting review: ${error.message}`
      );
    });
}

function createReviewElement(reviewData) {
  const reviewDiv = document.createElement("div");
  reviewDiv.className = "review-item new-review";
  let starsHTML = "";
  for (let i = 1; i <= 5; i++) {
    starsHTML +=
      i <= reviewData.rating
        ? '<i class="fas fa-star"></i>'
        : '<i class="far fa-star"></i>';
  }
  const currentDate = new Date().toLocaleDateString("vi-VN");
  reviewDiv.innerHTML = `
    <div class="review-header">
        <div class="reviewer-info">
            <div class="reviewer-avatar">${reviewData.name
              .charAt(0)
              .toUpperCase()}</div>
            <div class="reviewer-details">
                <div class="reviewer-name">${reviewData.name}</div>
                <div class="review-date">${currentDate}</div>
            </div>
        </div>
        <div class="review-rating">${starsHTML}</div>
    </div>
    <div class="review-content"><p>${reviewData.content}</p></div>
  `;
  reviewDiv.style.background = "linear-gradient(135deg, #e3f2fd, #f8f9fa)";
  reviewDiv.style.border = "2px solid #007bff";
  reviewDiv.style.borderRadius = "10px";
  reviewDiv.style.padding = "20px";
  reviewDiv.style.marginBottom = "15px";
  setTimeout(() => {
    reviewDiv.style.background = "";
    reviewDiv.style.border = "";
    reviewDiv.style.borderRadius = "";
    reviewDiv.style.padding = "20px 0";
    reviewDiv.style.marginBottom = "";
    reviewDiv.classList.remove("new-review");
  }, 3000);
  return reviewDiv;
}

// Initialize all functionality when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  createParticles();
  initTabs();
  initReservationForm();
  initParallax();

  // Initialize reviews
  fetchReviews(1, 5);

  fetchMenuItems("cocktails", 1, menuLimit); // Tải danh sách đồ uống mặc định
  fetchMenuItems("main", 1, menuLimit);

  // Add hover effect to menu items
  const menuItems = document.querySelectorAll(".menu-item");
  menuItems.forEach((item) => {
    item.addEventListener("mouseenter", () => {
      item.style.transform = "translateY(-8px) scale(1.02)";
    });
    item.addEventListener("mouseleave", () => {
      item.style.transform = "translateY(0) scale(1)";
    });
  });

  // Add click effect to buttons
  const buttons = document.querySelectorAll("button, .cta-btn");
  buttons.forEach((btn) => {
    btn.addEventListener("click", function () {
      this.style.transform = "scale(0.95)";
      setTimeout(() => {
        this.style.transform = "";
      }, 150);
    });
  });

  // Add star rating interactivity
  const starInputs = document.querySelectorAll(".star-rating input");
  const ratingText = document.querySelector(".rating-text");
  starInputs.forEach((input) => {
    input.addEventListener("change", function () {
      const rating = this.value;
      const ratingTexts = {
        1: languageId == 1 ? "Rất tệ" : "Very bad",
        2: languageId == 1 ? "Tệ" : "Bad",
        3: languageId == 1 ? "Bình thường" : "Average",
        4: languageId == 1 ? "Tốt" : "Good",
        5: languageId == 1 ? "Xuất sắc" : "Excellent",
      };
      ratingText.textContent = `${rating} sao - ${ratingTexts[rating]}`;
      ratingText.style.color = "#007bff";
      ratingText.style.fontWeight = "500";
    });
  });

  const stars = document.querySelectorAll(".star-rating .star");
  stars.forEach((star, index) => {
    star.addEventListener("mouseenter", function () {
      for (let i = stars.length - 1; i >= stars.length - 1 - index; i--) {
        stars[i].style.color = "#ffc107";
        stars[i].style.transform = "scale(1.1)";
      }
    });
    star.addEventListener("mouseleave", function () {
      const checkedInput = document.querySelector(".star-rating input:checked");
      if (!checkedInput) {
        stars.forEach((s) => {
          s.style.color = "#dee2e6";
          s.style.transform = "scale(1)";
        });
      } else {
        const selectedValue = parseInt(checkedInput.value);
        stars.forEach((s, i) => {
          if (stars.length - i <= selectedValue) {
            s.style.color = "#ffc107";
            s.style.transform = "scale(1.1)";
          } else {
            s.style.color = "#dee2e6";
            s.style.transform = "scale(1)";
          }
        });
      }
    });
  });
});

// document.querySelectorAll(".category-btn").forEach((button) => {
//   button.addEventListener("click", function () {
//     document
//       .querySelectorAll(".category-btn")
//       .forEach((btn) => btn.classList.remove("active"));
//     this.classList.add("active");

//     const category = this.getAttribute("data-category");
//     document.querySelectorAll(".service-featured-detail").forEach((item) => {
//       if (item.getAttribute("data-category") === category) {
//         item.style.display = "block";
//       } else {
//         item.style.display = "none";
//       }
//     });

//     // updateMenuPagination(category);

//     // Hiển thị/ẩn phân trang tương ứng
//     document.querySelectorAll(".menu-pagination-buttons").forEach((pagination) => {
//       if (pagination.getAttribute("data-category") === category) {
//         pagination.classList.add("active");
//       } else {
//         pagination.classList.remove("active");
//       }
//     });
//   });
// });
document.querySelectorAll(".category-btn").forEach((button) => {
  button.addEventListener("click", function () {
    // Xóa trạng thái active của các nút danh mục
    document
      .querySelectorAll(".category-btn")
      .forEach((btn) => btn.classList.remove("active"));
    this.classList.add("active");

    const category = this.getAttribute("data-category");

    // Hiển thị các món ăn của danh mục được chọn
    document.querySelectorAll(".service-featured-detail").forEach((item) => {
      if (item.getAttribute("data-category") === category) {
        item.style.display = "block";
      } else {
        item.style.display = "none";
      }
    });

    // Hiển thị container phân trang của danh mục được chọn, ẩn các container khác
    document
      .querySelectorAll(".menu-pagination-buttons")
      .forEach((pagination) => {
        if (pagination.getAttribute("data-category") === category) {
          pagination.style.display = pagination.querySelector("button")
            ? "flex"
            : "none";
        } else {
          pagination.style.display = "none";
        }
      });
  });
});

// bar.js
function initReservationForm() {
  const form = document.getElementById("reservationForm");
  const dateInput = document.getElementById("bookingDate");
  const today = new Date().toISOString().split("T")[0];
  if (dateInput) {
    dateInput.min = today;
  }

  const phoneInput = document.getElementById("phoneNumber");
  if (phoneInput) {
    phoneInput.addEventListener("input", function (e) {
      let value = e.target.value.replace(/\D/g, "");
      if (value.startsWith("84")) {
        value =
          "+84 " +
          value.slice(2, 5) +
          " " +
          value.slice(5, 8) +
          " " +
          value.slice(8);
      } else if (value.startsWith("0")) {
        value =
          value.slice(0, 4) + " " + value.slice(4, 7) + " " + value.slice(7);
      }
      e.target.value = value.trim();
    });
  }

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      // Xóa thông báo lỗi trước đó
      document.querySelectorAll(".error-message").forEach((span) => {
        span.style.display = "none";
        span.textContent = "";
      });
      document
        .querySelectorAll(".form-group input, .form-group select")
        .forEach((input) => {
          input.classList.remove("error");
        });

      const fieldNames = {
        customerName: languageId == 1 ? "Họ và tên" : "Full Name",
        phoneNumber: languageId == 1 ? "Số điện thoại" : "Phone Number",
        email: languageId == 1 ? "Email" : "Email",
        bookingDate: languageId == 1 ? "Ngày đặt bàn" : "Booking Date",
        startTime: languageId == 1 ? "Giờ đặt bàn" : "Booking Time",
        guestCount: languageId == 1 ? "Số lượng khách" : "Number of Guests",
        diningArea: languageId == 1 ? "Khu vực đặt bàn" : "Dining Area",
      };

      const requiredFields = [
        "customerName",
        "phoneNumber",
        "email",
        "bookingDate",
        "startTime",
        "guestCount",
        "diningArea",
      ];
      let isValid = true;

      requiredFields.forEach((field) => {
        const input = document.getElementById(field);
        const errorSpan = document.getElementById(`${field}-error`);
        if (!input || !input.value.trim()) {
          if (input) input.classList.add("error");
          if (errorSpan) {
            errorSpan.textContent =
              languageId == 1
                ? `Vui lòng nhập ${fieldNames[field]}`
                : `Please enter ${fieldNames[field]}`;
            errorSpan.style.display = "block";
            errorSpan.style.color = "red";
            errorSpan.style.fontSize = "14px";
          }
          isValid = false;
        }
      });

      const bookingDate = new Date(
        document.getElementById("bookingDate").value
      );
      const todayDate = new Date();
      todayDate.setHours(0, 0, 0, 0);
      if (bookingDate < todayDate) {
        const errorSpan = document.getElementById("bookingDate-error");
        if (errorSpan) {
          errorSpan.textContent =
            languageId == 1
              ? "Ngày đặt bàn không thể là ngày trong quá khứ"
              : "Booking date cannot be in the past";
          errorSpan.style.display = "block";
          errorSpan.style.color = "red";
          errorSpan.style.fontSize = "14px";
        }
        document.getElementById("bookingDate").classList.add("error");
        isValid = false;
      }

      const email = document.getElementById("email")?.value;
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (email && !emailRegex.test(email)) {
        const errorSpan = document.getElementById("email-error");
        if (errorSpan) {
          errorSpan.textContent =
            languageId == 1
              ? "Email không đúng định dạng"
              : "Invalid email format";
          errorSpan.style.display = "block";
          errorSpan.style.color = "red";
          errorSpan.style.fontSize = "14px";
        }
        document.getElementById("email").classList.add("error");
        isValid = false;
      }

      const phoneNumber = document.getElementById("phoneNumber")?.value;
      const phoneRegex = /^[0-9+\-\s\(\)]+$/;
      if (
        phoneNumber &&
        (!phoneRegex.test(phoneNumber) || phoneNumber.length < 10)
      ) {
        const errorSpan = document.getElementById("phoneNumber-error");
        if (errorSpan) {
          errorSpan.textContent =
            languageId == 1
              ? "Số điện thoại không hợp lệ"
              : "Invalid phone number";
          errorSpan.style.display = "block";
          errorSpan.style.color = "red";
          errorSpan.style.fontSize = "14px";
        }
        document.getElementById("phoneNumber").classList.add("error");
        isValid = false;
      }

      if (isValid) {
        const submitBtn = document.querySelector(".submit-booking-btn");
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML =
          '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        submitBtn.disabled = true;

        const formData = new FormData(form);
        formData.append("submit_booking_restaurant", "true");

        fetch("/libertylaocai/user/submit", {
          method: "POST",
          body: formData,
        })
          .then((response) => {
            if (!response.ok) {
              throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
          })
          .then((data) => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            if (data.status === "success") {
              alert(data.message);
              form.reset();
              document
                .querySelectorAll(".nav-btn")
                .forEach((btn) => btn.classList.remove("active"));
              document
                .querySelectorAll(".tab-content")
                .forEach((content) => content.classList.remove("active"));
              document
                .querySelector('[data-tab="about"]')
                .classList.add("active");
              document.getElementById("about").classList.add("active");
            } else {
              alert(data.message);
            }
          })
          .catch((error) => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            console.error("Error:", error);
            alert(
              languageId == 1
                ? "Có lỗi khi gửi yêu cầu. Vui lòng thử lại."
                : "An error occurred while sending the request. Please try again."
            );
          });
      }
    });
  }
}
