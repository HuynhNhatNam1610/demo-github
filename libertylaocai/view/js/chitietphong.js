let currentSlide = 0;
const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");
let currentRoomSlide = 0;
let roomsPerSlide = 3;
let totalRoomCards = 0;
let maxRoomSlides = 0;
let currentPage = 1;
let currentLimit = 5;
let totalPages = 1;

function showSlide(index) {
  slides.forEach((slide) => slide.classList.remove("active"));
  dots.forEach((dot) => dot.classList.remove("active"));
  slides[index].classList.add("active");
  dots[index].classList.add("active");
  currentSlide = index;
}

function nextSlide() {
  currentSlide = (currentSlide + 1) % slides.length;
  showSlide(currentSlide);
}

function prevSlide() {
  currentSlide = (currentSlide - 1 + slides.length) % slides.length;
  showSlide(currentSlide);
}

function currentSlideFunc(index) {
  showSlide(index - 1);
}

let autoSlideInterval = setInterval(nextSlide, 5000);
const sliderContainer = document.querySelector(".slider-container");
sliderContainer.addEventListener("mouseenter", () => {
  clearInterval(autoSlideInterval);
});
sliderContainer.addEventListener("mouseleave", () => {
  autoSlideInterval = setInterval(nextSlide, 5000);
});

function switchTab(tabName) {
  document.querySelectorAll(".tab-btn").forEach((btn) => {
    btn.classList.remove("active");
  });
  document.querySelectorAll(".tab-content").forEach((content) => {
    content.classList.remove("active");
  });
  document.querySelector(`[data-tab="${tabName}"]`).classList.add("active");
  document.getElementById(tabName).classList.add("active");
}

document.querySelectorAll(".tab-btn").forEach((btn) => {
  btn.addEventListener("click", (e) => {
    const tabName = e.target.getAttribute("data-tab");
    switchTab(tabName);
  });
});

function setMinDate() {
  const today = new Date().toISOString().split("T")[0];
  const checkin = document.getElementById("checkin");
  if (checkin) {
    checkin.setAttribute("min", today);
    checkin.value = today;
  }
  const checkout = document.getElementById("checkout");
   if (checkout) {
    checkout.setAttribute("min", today);
  }
  // calculateNights();
}

document.getElementById("checkin").addEventListener("change", function () {
  const checkinDate = new Date(this.value);
  const minCheckout = new Date(checkinDate);
  minCheckout.setDate(checkinDate.getDate() + 1);
  const checkoutInput = document.getElementById("checkout");
  checkoutInput.setAttribute("min", minCheckout.toISOString().split("T")[0]);
  const currentCheckout = new Date(checkoutInput.value);
  if (currentCheckout <= checkinDate) {
    checkoutInput.value = minCheckout.toISOString().split("T")[0];
  }
  // calculateNights();
});

// document.getElementById("checkout").addEventListener("change", calculateNights);

document.getElementById("phone").addEventListener("input", function (e) {
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
    value = value.slice(0, 4) + " " + value.slice(4, 7) + " " + value.slice(7);
  }
  e.target.value = value.trim();
});

function toggleReviewForm() {
  const form = document.getElementById("reviewForm");
  const btn = document.querySelector(".write-review-btn");
  const languageId = document.documentElement.lang === "vi" ? 1 : 0;
  if (form.style.display === "none" || form.style.display === "") {
    form.style.display = "block";
    btn.innerHTML = `<i class="fas fa-times"></i> ${
      languageId == 1 ? "Hủy viết đánh giá" : "Cancel review"
    }`;
    btn.style.background = "linear-gradient(135deg, #dc3545, #c82333)";
  } else {
    form.style.display = "none";
    btn.innerHTML = `<i class="fas fa-pen"></i> ${
      languageId == 1 ? "Viết đánh giá của bạn" : "Write a review"
    }`;
    btn.style.background = "linear-gradient(135deg, #28a745, #20c997)";
    resetReviewForm();
  }
}

function resetReviewForm() {
  const form = document.querySelector(".review-form");
  form.reset();
  const ratingText = document.querySelector(".rating-text");
  const languageId = document.documentElement.lang === "vi" ? 1 : 0;
  ratingText.textContent = languageId == 1 ? "Chọn số sao" : "Select rating";
  ratingText.style.color = "#666";
  const stars = document.querySelectorAll(".star-rating .star");
  stars.forEach((star) => {
    star.style.color = "#dee2e6";
    star.style.transform = "scale(1)";
  });
}

function fetchReviews(page = 1, limit = 5) {
  const roomId = document.querySelector('input[name="room_id"]').value;
  const languageId = document.documentElement.lang === "vi" ? 1 : 0;
  fetch(
    `/libertylaocai/api/room_review.php?page=${page}&limit=${limit}&room_id=${roomId}`
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
      console.error("Error fetching reviews:", error);
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
  const languageId = document.documentElement.lang === "vi" ? 1 : 0;

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
        ? "Chưa có đánh giá nào cho phòng này."
        : "No reviews available for this room."
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
  const languageId = document.documentElement.lang === "vi" ? 1 : 0;
  const formData = new FormData(event.target);
  formData.append("comment_room", "true");
  formData.append(
    "id_loaiphong",
    document.querySelector('input[name="room_id"]').value
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
  const languageId = document.documentElement.lang === "vi" ? 1 : 0;
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

function initStarRating() {
  const starInputs = document.querySelectorAll(".star-rating input");
  const ratingText = document.querySelector(".rating-text");
  const languageId = document.documentElement.lang === "vi" ? 1 : 0;
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
}

// function updateDebugInfo() {
//   document.getElementById("currentSlide").textContent = currentRoomSlide;
//   document.getElementById("maxSlides").textContent = maxRoomSlides;
//   document.getElementById("screenWidth").textContent = window.innerWidth;
//   document.getElementById("cardsPerSlide").textContent = roomsPerSlide;
// }

function updateRoomsPerSlide() {
  const screenWidth = window.innerWidth;
  if (screenWidth <= 768) {
    roomsPerSlide = 1;
  } else if (screenWidth <= 1024) {
    roomsPerSlide = 2;
  } else {
    roomsPerSlide = 3;
  }
  const roomCards = document.querySelectorAll(".room-card");
  totalRoomCards = roomCards.length;
  maxRoomSlides = Math.max(0, totalRoomCards - roomsPerSlide);
  if (currentRoomSlide > maxRoomSlides) {
    currentRoomSlide = maxRoomSlides;
  }
  updateRoomSlider();
  updateRoomNavigation();
  // updateDebugInfo();
}

function updateRoomSlider() {
  const roomsGrid = document.querySelector(".rooms-grid");
  const roomCards = document.querySelectorAll(".room-card");
  if (!roomsGrid || roomCards.length === 0) return;
  let cardWidth, gap;
  const screenWidth = window.innerWidth;
  if (screenWidth <= 768) {
    cardWidth = 100;
    gap = 0;
  } else if (screenWidth <= 1024) {
    cardWidth = 48;
    gap = 2;
  } else {
    cardWidth = 31.33;
    gap = 1;
  }
  roomsGrid.style.setProperty("--card-width", `${cardWidth}%`);
  roomsGrid.style.setProperty("--gap", `${gap}%`);
  const translateX = currentRoomSlide * (cardWidth + gap);
  roomsGrid.style.transform = `translateX(-${translateX}%)`;
}

function nextRoomSlide() {
  currentRoomSlide = (currentRoomSlide + 1) % (maxRoomSlides + 1);
  updateRoomSlider();
  updateRoomNavigation();
  // updateDebugInfo();
}

function prevRoomSlide() {
  currentRoomSlide =
    (currentRoomSlide - 1 + maxRoomSlides + 1) % (maxRoomSlides + 1);
  updateRoomSlider();
  updateRoomNavigation();
  // updateDebugInfo();
}

function updateRoomNavigation() {
  const prevBtn = document.querySelector(".room-nav-prev");
  const nextBtn = document.querySelector(".room-nav-next");
  if (prevBtn && nextBtn) {
    prevBtn.style.opacity = currentRoomSlide === 0 ? "0.5" : "1";
    prevBtn.style.cursor = currentRoomSlide === 0 ? "not-allowed" : "pointer";
    nextBtn.style.opacity = currentRoomSlide >= maxRoomSlides ? "0.5" : "1";
    nextBtn.style.cursor =
      currentRoomSlide >= maxRoomSlides ? "not-allowed" : "pointer";
  }
  const dots = document.querySelectorAll(".room-dot");
  dots.forEach((dot, index) => {
    dot.classList.toggle("active", index === currentRoomSlide);
  });
}

function initRoomSlider() {
  updateRoomsPerSlide();
  const roomSliderContainer = document.querySelector(".other-rooms");
  const existingDots = document.querySelector(".room-slider-dots");
  if (existingDots) {
    existingDots.remove();
  }
  const dotsContainer = document.createElement("div");
  dotsContainer.className = "room-slider-dots";
  for (let i = 0; i <= maxRoomSlides; i++) {
    const dot = document.createElement("span");
    dot.className = "room-dot";
    if (i === 0) dot.classList.add("active");
    dot.addEventListener("click", () => {
      currentRoomSlide = i;
      updateRoomSlider();
      updateRoomNavigation();
      // updateDebugInfo();
    });
    dotsContainer.appendChild(dot);
  }
  if (maxRoomSlides > 0) {
    roomSliderContainer.appendChild(dotsContainer);
  }
}

let startX = 0;
let endX = 0;
document
  .querySelector(".rooms-grid-wrapper")
  .addEventListener("touchstart", (e) => {
    startX = e.touches[0].clientX;
  });
document
  .querySelector(".rooms-grid-wrapper")
  .addEventListener("touchend", (e) => {
    endX = e.changedTouches[0].clientX;
    handleSwipe();
  });

function handleSwipe() {
  const swipeThreshold = 50;
  const diff = startX - endX;
  if (Math.abs(diff) > swipeThreshold) {
    if (diff > 0) {
      nextRoomSlide();
    } else {
      prevRoomSlide();
    }
  }
}

function formatVND(amount) {
  return (
    new Intl.NumberFormat("vi-VN", { minimumFractionDigits: 0 }).format(
      amount
    ) + " VNĐ"
  );
}

function animateOnScroll() {
  const elements = document.querySelectorAll(
    ".room-card, .amenity-item, .info-item"
  );
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = "1";
          entry.target.style.transform = "translateY(0)";
        }
      });
    },
    { threshold: 0.1 }
  );
  elements.forEach((element) => {
    element.style.opacity = "0";
    element.style.transform = "translateY(20px)";
    element.style.transition = "all 0.6s ease";
    observer.observe(element);
  });
}

function closeAlert(alertId) {
  const alert = document.getElementById(alertId);
  if (alert) {
    alert.style.animation = "slideOut 0.3s ease-out";
    setTimeout(() => {
      alert.remove();
    }, 300);
  }
}

// function validateBookingForm() {
//   const form = document.querySelector(".booking-form");
//   const inputs = form.querySelectorAll("input[required], select[required]");
//   let isValid = true;
//   const languageId = document.documentElement.lang === "vi" ? 1 : 0;

//   const adultsInput = document.getElementById("adults");
//   const childrenInput = document.getElementById("children");
//   if (adultsInput.value < 1) {
//     isValid = false;
//     const formGroup = adultsInput.closest(".form-group");
//     formGroup.classList.add("has-error");
//     const errorMsg = document.createElement("span");
//     errorMsg.className = "validation-message";
//     errorMsg.textContent =
//       languageId == 1
//         ? "Số người lớn phải từ 1 trở lên"
//         : "Number of adults must be at least 1";
//     adultsInput.parentNode.appendChild(errorMsg);
//   }
//   if (childrenInput.value < 0) {
//     isValid = false;
//     const formGroup = childrenInput.closest(".form-group");
//     formGroup.classList.add("has-error");
//     const errorMsg = document.createElement("span");
//     errorMsg.className = "validation-message";
//     errorMsg.textContent =
//       languageId == 1
//         ? "Số trẻ em không được âm"
//         : "Number of children cannot be negative";
//     childrenInput.parentNode.appendChild(errorMsg);
//   }

//   const emailInput = document.getElementById("email");
//   if (emailInput.value && !isValidEmail(emailInput.value)) {
//     isValid = false;
//     const formGroup = emailInput.closest(".form-group");
//     formGroup.classList.add("has-error");
//     const errorMsg = document.createElement("span");
//     errorMsg.className = "validation-message";
//     errorMsg.textContent =
//       languageId == 1 ? "Email không hợp lệ" : "Invalid email format";
//     emailInput.parentNode.appendChild(errorMsg);
//   }

//   const checkinInput = document.getElementById("checkin");
//   const checkoutInput = document.getElementById("checkout");
//   if (checkinInput.value && checkoutInput.value) {
//     const checkinDate = new Date(checkinInput.value);
//     const checkoutDate = new Date(checkoutInput.value);
//     const today = new Date();
//     today.setHours(0, 0, 0, 0);
//     if (checkinDate < today) {
//       isValid = false;
//       const formGroup = checkinInput.closest(".form-group");
//       formGroup.classList.add("has-error");
//       const errorMsg = document.createElement("span");
//       errorMsg.className = "validation-message";
//       errorMsg.textContent =
//         languageId == 1
//           ? "Ngày nhận phòng không thể là quá khứ"
//           : "Check-in date cannot be in the past";
//       checkinInput.parentNode.appendChild(errorMsg);
//     }
//     if (checkoutDate <= checkinDate) {
//       isValid = false;
//       const formGroup = checkoutInput.closest(".form-group");
//       formGroup.classList.add("has-error");
//       const errorMsg = document.createElement("span");
//       errorMsg.className = "validation-message";
//       errorMsg.textContent =
//         languageId == 1
//           ? "Ngày trả phòng phải sau ngày nhận phòng"
//           : "Check-out date must be after check-in date";
//       checkoutInput.parentNode.appendChild(errorMsg);
//     }
//   }
//   return isValid;
// }
function validateBookingForm() {
  const form = document.querySelector(".booking-form");
  const requiredFields = [
    "checkin",
    "checkout",
    "adults",
    "fullname",
    "email",
    "phone",
    "room_id",
  ];
  const languageId = document.documentElement.lang === "vi" ? 1 : 0;
  let isValid = true;

  requiredFields.forEach((field) => {
    const input = form.querySelector(`[name="${field}"]`);
    if (!input || !input.value.trim()) {
      isValid = false;
      const formGroup = input.closest(".form-group");
      formGroup.classList.add("has-error");
      const errorMsg = document.createElement("span");
      errorMsg.className = "validation-message";
      errorMsg.textContent =
        texts["booking_error_missing_info"] ||
        "Vui lòng nhập thông tin bắt buộc";
      input.parentNode.appendChild(errorMsg);
    }
  });

  const emailInput = form.querySelector('[name="email"]');
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
    isValid = false;
    const formGroup = emailInput.closest(".form-group");
    formGroup.classList.add("has-error");
    const errorMsg = document.createElement("span");
    errorMsg.className = "validation-message";
    errorMsg.textContent =
      texts["booking_error_invalid_email"] || "Email không hợp lệ";
    emailInput.parentNode.appendChild(errorMsg);
  }

  const phoneInput = form.querySelector('[name="phone"]');
  if (
    !/^[0-9+\-\s\(\)]+$/.test(phoneInput.value) ||
    phoneInput.value.length < 10
  ) {
    isValid = false;
    const formGroup = phoneInput.closest(".form-group");
    formGroup.classList.add("has-error");
    const errorMsg = document.createElement("span");
    errorMsg.className = "validation-message";
    errorMsg.textContent =
      texts["booking_error_invalid_phone"] || "Số điện thoại không hợp lệ";
    phoneInput.parentNode.appendChild(errorMsg);
  }

  const checkin = new Date(form.querySelector('[name="checkin"]').value);
  const checkout = new Date(form.querySelector('[name="checkout"]').value);
  const today = new Date();
  today.setHours(0, 0, 0, 0);

  if (checkin < today) {
    isValid = false;
    const formGroup = form
      .querySelector('[name="checkin"]')
      .closest(".form-group");
    formGroup.classList.add("has-error");
    const errorMsg = document.createElement("span");
    errorMsg.className = "validation-message";
    errorMsg.textContent =
      texts["booking_error_past_date"] ||
      "Ngày nhận phòng không thể trong quá khứ";
    form.querySelector('[name="checkin"]').parentNode.appendChild(errorMsg);
  }

  if (checkout <= checkin) {
    isValid = false;
    const formGroup = form
      .querySelector('[name="checkout"]')
      .closest(".form-group");
    formGroup.classList.add("has-error");
    const errorMsg = document.createElement("span");
    errorMsg.className = "validation-message";
    errorMsg.textContent =
      texts["booking_error_invalid_dates"] ||
      "Ngày trả phòng phải sau ngày nhận phòng";
    form.querySelector('[name="checkout"]').parentNode.appendChild(errorMsg);
  }

  return isValid;
}

function isValidEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

// Xử lý gửi biểu mẫu đặt phòng bằng AJAX
function submitBookingForm(event) {
  event.preventDefault(); // Ngăn chặn hành vi gửi biểu mẫu mặc định
  const languageId = document.documentElement.lang === "vi" ? 1 : 0;
  // Xác thực biểu mẫu trước khi gửi
  if (!validateBookingForm()) {
    return;
  }

  const form = document.querySelector(".booking-form");
  const submitBtn = form.querySelector(".submit-booking-btn");
  const originalText = submitBtn.innerHTML;

  // Vô hiệu hóa nút gửi để tránh gửi lặp lại
  submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${
    languageId == 1 ? "Đang xử lý..." : "Processing..."
  }`;
  submitBtn.disabled = true;

  // Thu thập dữ liệu biểu mẫu
  const formData = new FormData(form);
  formData.append("submit_booking_room", "1"); // Đảm bảo gửi tham số submit_booking
  formData.append(
    "id_loaiphong",
    document.querySelector('input[name="room_id"]').value
  );
  // Gửi yêu cầu AJAX
  fetch("/libertylaocai/user/submit", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`Lỗi HTTP! Trạng thái: ${response.status}`);
      }
      return response.json(); // Giả sử server trả về JSON
    })
    .then((data) => {
      // Khôi phục nút gửi
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
      if (data.status === "success") {
        alert(data.message);
        form.reset();
        switchTab("description");
      } else {
        alert(data.message);
      }
    })
    .catch((error) => {
      // Khôi phục nút gửi
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

document.addEventListener("DOMContentLoaded", function () {
  setMinDate();
  initStarRating();
  initRoomSlider();
  animateOnScroll();
  fetchReviews(1, 5);

  const bookingForm = document.querySelector(".booking-form");
  if (bookingForm) {
    bookingForm.addEventListener("submit", submitBookingForm);
  }

  const languageId = document.documentElement.lang === "vi" ? 1 : 0;

  document.addEventListener("keydown", function (e) {
    if (e.key === "ArrowLeft") {
      prevSlide();
    } else if (e.key === "ArrowRight") {
      nextSlide();
    }
  });

  let touchStartX = 0;
  let touchEndX = 0;
  sliderContainer.addEventListener("touchstart", function (e) {
    touchStartX = e.changedTouches[0].screenX;
  });
  sliderContainer.addEventListener("touchend", function (e) {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
  });

  if ("IntersectionObserver" in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          img.classList.remove("lazy");
          imageObserver.unobserve(img);
        }
      });
    });
    document.querySelectorAll("img[data-src]").forEach((img) => {
      imageObserver.observe(img);
    });
  }

  document.documentElement.style.scrollBehavior = "smooth";
  const priceElements = document.querySelectorAll(
    ".current-price, .room-card-price, .price-total span:last-child"
  );
  priceElements.forEach((element) => {
    const price = element.textContent.replace(/\D/g, "");
    if (price) {
      element.textContent = formatVND(price);
    }
  });

  const alerts = document.querySelectorAll(".alert");
  alerts.forEach((alert) => {
    setTimeout(() => {
      if (document.body.contains(alert)) {
        closeAlert(alert.id);
      }
    }, 3000);
  });

  const style = document.createElement("style");
  style.textContent = `
    @keyframes slideOut {
      from {
        transform: translateX(0);
        opacity: 1;
      }
      to {
        transform: translateX(100%);
        opacity: 0;
      }
    }
  `;
  document.head.appendChild(style);

  // const bookingForm = document.querySelector(".booking-form");
  // if (bookingForm) {
  //   bookingForm.addEventListener("submit", function (e) {
  //     if (!validateBookingForm()) {
  //       e.preventDefault();
  //       return false;
  //     }
  //     const submitBtn = this.querySelector(".submit-booking-btn");
  //     const originalText = submitBtn.innerHTML;
  //     submitBtn.innerHTML = `<span class="loading-spinner"></span>${
  //       languageId == 1 ? "Đang xử lý..." : "Processing..."
  //     }`;
  //     submitBtn.disabled = true;
  //     setTimeout(() => {
  //       submitBtn.innerHTML = originalText;
  //       submitBtn.disabled = false;
  //     }, 10000);
  //   });
  // }
});

window.addEventListener("resize", () => {
  updateRoomsPerSlide();
});
