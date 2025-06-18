let currentSlideIndex = 0;
let currentModalIndex = 0;
let zoomLevel = 1;
const maxZoom = 2;
const minZoom = 0.5;
const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");
let currentPage = 1;
let currentLimit = 5;
let totalPages = 1;

document.addEventListener("DOMContentLoaded", function () {
  initializeSlider();
  initializeTabSwitching();
  initializeReviewForm();
  fetchReviews(1, 5); // Tải đánh giá ngay khi trang được tải
  // Gắn sự kiện click cho ảnh trong hero slider
  const heroImages = document.querySelectorAll(".hero-slider .slide img");
  heroImages.forEach((img) => {
    img.addEventListener("click", () => openModal(img.src));
  });
});

function initializeSlider() {
  if (slides.length === 0 || dots.length === 0) {
    console.warn("No slides or dots found for slider.");
    return;
  }
  setInterval(() => {
    changeSlide(1);
  }, 5000);
}

function changeSlide(direction) {
  if (!slides[currentSlideIndex] || !dots[currentSlideIndex]) {
    console.error("Invalid slide or dot index:", currentSlideIndex);
    return;
  }
  slides[currentSlideIndex].classList.remove("active");
  dots[currentSlideIndex].classList.remove("active");
  currentSlideIndex += direction;
  if (currentSlideIndex >= slides.length) {
    currentSlideIndex = 0;
  } else if (currentSlideIndex < 0) {
    currentSlideIndex = slides.length - 1;
  }
  slides[currentSlideIndex].classList.add("active");
  dots[currentSlideIndex].classList.add("active");
}

function currentSlide(n) {
  if (!slides[n - 1] || !dots[n - 1]) {
    console.error("Invalid slide index:", n - 1);
    return;
  }
  slides[currentSlideIndex].classList.remove("active");
  dots[currentSlideIndex].classList.remove("active");
  currentSlideIndex = n - 1;
  slides[currentSlideIndex].classList.add("active");
  dots[currentSlideIndex].classList.add("active");
}

function openTab(evt, tabName) {
  const tabContents = document.querySelectorAll(".tab-content");
  tabContents.forEach((content) => {
    content.classList.remove("active");
  });
  const tabButtons = document.querySelectorAll(".tab-btn");
  tabButtons.forEach((btn) => {
    btn.classList.remove("active");
  });
  const tabElement = document.getElementById(tabName);
  if (tabElement) {
    tabElement.classList.add("active");
    evt.currentTarget.classList.add("active");
  } else {
    console.error("Tab not found:", tabName);
  }
}

function initializeTabSwitching() {
  const tabButtons = document.querySelectorAll(".tab-btn");
  tabButtons.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      const tabNameMatch = this.getAttribute("onclick")?.match(/'([^']+)'/);
      if (tabNameMatch) {
        openTab(e, tabNameMatch[1]);
      }
    });
  });
}

function openModal(imageSrc) {
  const modal = document.getElementById("imageModal");
  const modalImage = document.getElementById("modalImage");
  const allImages = document.querySelectorAll(
    ".gallery-item img, .hero-slider .slide img"
  );
  if (!modal || !modalImage) {
    console.error("Modal or modal image not found.");
    return;
  }
  currentModalIndex = Array.from(allImages).findIndex(
    (img) => img.src === imageSrc
  );
  modalImage.src = imageSrc;
  modal.style.display = "block";
  document.body.style.overflow = "hidden";
  zoomLevel = 1;
  modalImage.style.transform = `scale(${zoomLevel})`;
}

function closeModal() {
  const modal = document.getElementById("imageModal");
  const modalImage = document.getElementById("modalImage");
  if (modal && modalImage) {
    modal.style.display = "none";
    document.body.style.overflow = "auto";
    zoomLevel = 1;
    modalImage.style.transform = `scale(${zoomLevel})`;
  }
}

function changeModalSlide(direction) {
  const allImages = document.querySelectorAll(
    ".gallery-item img, .hero-slider .slide img"
  );
  if (allImages.length === 0) return;
  currentModalIndex += direction;
  if (currentModalIndex >= allImages.length) currentModalIndex = 0;
  else if (currentModalIndex < 0) currentModalIndex = allImages.length - 1;
  const modalImage = document.getElementById("modalImage");
  if (modalImage) {
    modalImage.src = allImages[currentModalIndex].src;
    zoomLevel = 1;
    modalImage.style.transform = `scale(${zoomLevel})`;
  }
}

function zoomIn() {
  const modalImage = document.getElementById("modalImage");
  if (modalImage && zoomLevel < maxZoom) {
    zoomLevel += 0.2;
    modalImage.style.transform = `scale(${zoomLevel})`;
  }
}

function zoomOut() {
  const modalImage = document.getElementById("modalImage");
  if (modalImage && zoomLevel > minZoom) {
    zoomLevel -= 0.2;
    modalImage.style.transform = `scale(${zoomLevel})`;
  }
}

document.addEventListener("click", function (e) {
  const modal = document.getElementById("imageModal");
  if (e.target === modal) {
    closeModal();
  }
});

document.addEventListener("keydown", function (e) {
  if (e.key === "Escape") {
    closeModal();
  }
});

function toggleReviewForm() {
  const form = document.getElementById("reviewForm");
  const btn = document.querySelector(".write-review-btn");
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;
  if (!form || !btn) return;
  if (form.style.display === "none" || form.style.display === "") {
    form.style.display = "block";
    btn.innerHTML = `<i class="fas fa-times"></i> ${
      languageId == 1 ? "Hủy viết đánh giá" : "Cancel Review"
    }`;
    btn.style.background = "linear-gradient(135deg, #e74c3c, #c0392b)";
  } else {
    form.style.display = "none";
    btn.innerHTML = `<i class="fas fa-pen"></i> ${
      languageId == 1 ? "Viết đánh giá của bạn" : "Write Your Review"
    }`;
    btn.style.background = "linear-gradient(135deg, #3498db, #2980b9)";
    resetReviewForm();
  }
}

function resetReviewForm() {
  const form = document.getElementById("reviewFormSubmit");
  if (form) {
    form.reset();
    const ratingText = document.querySelector(".rating-text");
    const languageId = document.documentElement.lang === "vi" ? 1 : 2;
    if (ratingText) {
      ratingText.textContent =
        languageId == 1 ? "Chọn số sao" : "Select rating";
      ratingText.style.color = "#7f8c8d";
    }
    const stars = document.querySelectorAll(".star-rating .star");
    stars.forEach((star) => {
      star.style.color = "#e9ecef";
      star.style.transform = "scale(1)";
    });
  }
}

function fetchReviews(page = 1, limit = 5) {
  const id_dichvu = document.querySelector('input[name="id_dichvu"]').value;
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;
  fetch(
    `/libertylaocai/api/tour_review.php?page=${page}&limit=${limit}&id_service=${id_dichvu}`
  )
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      updateReviews(data);
      currentPage = data.currentPage;
      totalPages = data.totalPages;
      currentLimit = limit;
      updatePaginationControls();
    })
    .catch((error) => {
      console.error("Lỗi tải đánh giá:", error);
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
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;

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
      const reviewDate = new Date(review.create_at).toLocaleDateString(
        languageId == 1 ? "vi-VN" : "en-US"
      );
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
        ? "Chưa có đánh giá nào cho tour này."
        : "No reviews available for this tour."
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
    } else if (i == Math.ceil(data.totalRating) && data.totalRating % 1 !== 0) {
      ratingStars.innerHTML += '<i class="fas fa-star-half-alt"></i>';
    } else {
      ratingStars.innerHTML += '<i class="far fa-star"></i>';
    }
  }

  ratingBreakdown.innerHTML = "";
  for (let i = 5; i >= 1; i--) {
    const bar = document.createElement("div");
    bar.className = "rating-bar";
    bar.innerHTML = `
      <span class="rating-label">${i} ${
      languageId == 1 ? "sao" : "stars"
    }</span>
      <div class="bar-container">
          <div class="bar-fill" style="width: ${
            data.ratingBreakdown?.[i]?.percentage || 0
          }%"></div>
      </div>
      <span class="rating-percent">${
        data.ratingBreakdown?.[i]?.percentage || 0
      }%</span>
    `;
    ratingBreakdown.appendChild(bar);
  }
}

function updatePaginationControls() {
  const paginationControls = document.querySelector(".pagination-controls");
  const showMoreBtn = document.querySelector(".show-more-reviews");
  const paginationButtons = document.querySelector(".pagination-buttons");
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;

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

    if (endPage - startPage + 1 > maxButtons) {
      endPage = startPage + maxButtons - 1;
    }

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
      startPage++;
      endPage = Math.min(endPage, startPage + maxButtons - 2);
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

function submitReview(event) {
  event.preventDefault();
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;
  const formData = new FormData(event.target);
  formData.append("comment_service", "true");
  formData.append(
    "id_service",
    document.querySelector('input[name="id_dichvu"]').value
  );

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

  if (!reviewData.email.trim() || !isValidEmail(reviewData.email)) {
    alert(
      languageId == 1
        ? "Vui lòng nhập email hợp lệ!"
        : "Please enter a valid email!"
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
            : "Thank you for sharing your review! Your review has been added successfully."
        );
        toggleReviewForm();
        newReview.scrollIntoView({ behavior: "smooth", block: "center" });
      } else {
        alert(
          languageId == 1 ? "Lỗi: " + data.message : "Error: " + data.message
        );
      }
    })
    .catch((error) => {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
      console.error("Lỗi gửi đánh giá:", error);
      alert(
        languageId == 1
          ? "Lỗi gửi đánh giá: " + error.message
          : "Error submitting review: " + error.message
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
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;
  const currentDate = new Date().toLocaleDateString(
    languageId == 1 ? "vi-VN" : "en-US"
  );
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
  reviewDiv.style.border = "2px solid #3498db";
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

function initializeReviewForm() {
  const writeReviewBtn = document.querySelector(".write-review-btn");
  if (writeReviewBtn) {
    writeReviewBtn.addEventListener("click", toggleReviewForm);
  }
  const reviewForm = document.getElementById("reviewFormSubmit");
  if (reviewForm) {
    reviewForm.addEventListener("submit", submitReview);
  }
  const cancelBtn = document.querySelector(".cancel-btn");
  if (cancelBtn) {
    cancelBtn.addEventListener("click", toggleReviewForm);
  }
  const starInputs = document.querySelectorAll(".star-rating input");
  const ratingText = document.querySelector(".rating-text");
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;
  const ratingTexts =
    languageId == 1
      ? { 1: "Rất tệ", 2: "Tệ", 3: "Bình thường", 4: "Tốt", 5: "Xuất sắc" }
      : { 1: "Very bad", 2: "Bad", 3: "Average", 4: "Good", 5: "Excellent" };

  starInputs.forEach((input) => {
    input.addEventListener("change", function () {
      const rating = this.value;
      ratingText.textContent = `${rating} ${
        languageId == 1 ? "sao" : "stars"
      } - ${ratingTexts[rating]}`;
      ratingText.style.color = "#3498db";
      ratingText.style.fontWeight = "500";
    });
  });

  const stars = document.querySelectorAll(".star-rating .star");
  stars.forEach((star, index) => {
    star.addEventListener("mouseenter", function () {
      for (let i = stars.length - 1; i >= stars.length - 1 - index; i--) {
        stars[i].style.color = "#f39c12";
        stars[i].style.transform = "scale(1.1)";
      }
    });
    star.addEventListener("mouseleave", function () {
      const checkedInput = document.querySelector(".star-rating input:checked");
      if (!checkedInput) {
        stars.forEach((s) => {
          s.style.color = "#e9ecef";
          s.style.transform = "scale(1)";
        });
      } else {
        const selectedValue = parseInt(checkedInput.value);
        stars.forEach((s, i) => {
          if (stars.length - i <= selectedValue) {
            s.style.color = "#f39c12";
            s.style.transform = "scale(1.1)";
          } else {
            s.style.color = "#e9ecef";
            s.style.transform = "scale(1)";
          }
        });
      }
    });
  });
}

function isValidEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}
