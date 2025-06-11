let currentSlideIndex = 0;
let currentModalIndex = 0;
let zoomLevel = 1;
const maxZoom = 2;
const minZoom = 0.5;
const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");

document.addEventListener("DOMContentLoaded", function () {
  initializeSlider();
  initializeTabSwitching();
  initializeReviewForm();
  loadMoreReviews();
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
  if (!form || !btn) return;
  if (form.style.display === "none" || form.style.display === "") {
    form.style.display = "block";
    btn.innerHTML = '<i class="fas fa-times"></i> Hủy viết đánh giá';
    btn.style.background = "linear-gradient(135deg, #e74c3c, #c0392b)";
  } else {
    form.style.display = "none";
    btn.innerHTML = '<i class="fas fa-pen"></i> Viết đánh giá của bạn';
    btn.style.background = "linear-gradient(135deg, #3498db, #2980b9)";
    resetReviewForm();
  }
}

function resetReviewForm() {
  const form = document.getElementById("reviewFormSubmit");
  if (form) {
    form.reset();
    const ratingText = document.querySelector(".rating-text");
    if (ratingText) {
      ratingText.textContent = "Chọn số sao";
      ratingText.style.color = "#7f8c8d";
    }
    const stars = document.querySelectorAll(".star-rating .star");
    stars.forEach((star) => {
      star.style.color = "#e9ecef";
      star.style.transform = "scale(1)";
    });
  }
}

function submitReview(event) {
  event.preventDefault();
  const form = event.target;
  const formData = new FormData(form);
  if (!formData.get("rating")) {
    alert("Vui lòng chọn số sao đánh giá!");
    return;
  }
  if (!formData.get("reviewer-name").trim()) {
    alert("Vui lòng nhập họ và tên!");
    return;
  }
  if (!formData.get("reviewer-phone").match(/^[0-9]{10,11}$/)) {
    alert("Vui lòng nhập số điện thoại hợp lệ (10-11 số)!");
    return;
  }
  if (!formData.get("review-content").trim()) {
    alert("Vui lòng nhập nội dung đánh giá!");
    return;
  }
  if (!formData.get("id_dichvu")) {
    alert("Dịch vụ không hợp lệ!");
    return;
  }
  const submitBtn = document.querySelector(".submit-review-btn");
  const originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
  submitBtn.disabled = true;
  fetch("/libertylaocai/view/php/chitiettour.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
      if (data.success) {
        const newReview = createReviewElement(data.review);
        const reviewsList = document.querySelector(".reviews-list");
        reviewsList.insertBefore(newReview, reviewsList.firstChild);
        updateReviewStats(data.review.rating);
        alert("Cảm ơn bạn đã chia sẻ đánh giá!");
        toggleReviewForm();
        newReview.scrollIntoView({ behavior: "smooth", block: "center" });
      } else {
        alert(data.message);
      }
    })
    .catch((error) => {
      console.error("Lỗi:", error);
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
      alert("Có lỗi xảy ra, vui lòng thử lại!");
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
  reviewDiv.innerHTML = `
    <div class="review-header">
      <div class="reviewer-info">
        <div class="reviewer-avatar">
          <i class="fas fa-user"></i>
        </div>
        <div class="reviewer-details">
          <div class="reviewer-name">${reviewData.name}</div>
          <div class="review-date">${reviewData.date}</div>
        </div>
      </div>
      <div class="review-rating">
        ${starsHTML}
      </div>
    </div>
    <div class="review-content">
      <p>${reviewData.content}</p>
    </div>
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

function updateReviewStats(newRating) {
  const ratingCount = document.querySelector(".rating-count");
  const ratingScore = document.querySelector(".rating-score");
  const ratingBars = document.querySelectorAll(".rating-bar");
  let currentCount = parseInt(ratingCount.textContent.match(/\d+/)[0]);
  currentCount++;
  ratingCount.textContent = `(${currentCount} đánh giá)`;
  let currentAvg = parseFloat(ratingScore.textContent);
  let newAvg =
    (currentAvg * (currentCount - 1) + parseInt(newRating)) / currentCount;
  ratingScore.textContent = newAvg.toFixed(1);
  const ratingStars = document.querySelector(".rating-stars");
  ratingStars.innerHTML = "";
  for (let i = 1; i <= 5; i++) {
    if (i <= Math.floor(newAvg)) {
      ratingStars.innerHTML += '<i class="fas fa-star"></i>';
    } else if (i == Math.ceil(newAvg) && newAvg - Math.floor(newAvg) >= 0.5) {
      ratingStars.innerHTML += '<i class="fas fa-star-half-alt"></i>';
    } else {
      ratingStars.innerHTML += '<i class="far fa-star"></i>';
    }
  }
  ratingBars.forEach((bar) => {
    const star = parseInt(bar.querySelector(".rating-label").textContent);
    const percentSpan = bar.querySelector(".rating-percent");
    let currentPercent = parseInt(percentSpan.textContent);
    if (star == newRating) {
      let count = (currentPercent * (currentCount - 1)) / 100 + 1;
      let newPercent = (count / currentCount) * 100;
      percentSpan.textContent = Math.round(newPercent) + "%";
      bar.querySelector(".bar-fill").style.width = Math.round(newPercent) + "%";
    } else {
      let count = (currentPercent * (currentCount - 1)) / 100;
      let newPercent = (count / currentCount) * 100;
      percentSpan.textContent = Math.round(newPercent) + "%";
      bar.querySelector(".bar-fill").style.width = Math.round(newPercent) + "%";
    }
  });
}

function loadMoreReviews() {
  const showMoreBtn = document.querySelector(".show-more-reviews");
  const hideReviewsBtn = document.querySelector(".hide-reviews");
  if (!showMoreBtn) return;
  showMoreBtn.addEventListener("click", function () {
    const id_dichvuInput = document.querySelector('input[name="id_dichvu"]');
    if (!id_dichvuInput) {
      console.error("Input id_dichvu not found.");
      return;
    }
    const id_dichvu = id_dichvuInput.value;
    const reviewsList = document.querySelector(".reviews-list");
    const currentReviews = reviewsList.querySelectorAll(".review-item").length;
    showMoreBtn.innerHTML =
      '<i class="fas fa-spinner fa-spin"></i> Đang tải...';
    showMoreBtn.disabled = true;
    fetch("/libertylaocai/view/php/chitiettour.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `action=load_more_reviews&id_dichvu=${id_dichvu}&offset=${currentReviews}`,
    })
      .then((response) => {
        console.log("Response status:", response.status);
        return response.json();
      })
      .then((data) => {
        showMoreBtn.innerHTML =
          '<i class="fas fa-chevron-down"></i> Xem thêm đánh giá';
        showMoreBtn.disabled = false;
        if (data.success && data.reviews.length > 0) {
          const marker = document.createElement("div");
          marker.className = "new-reviews-marker";
          marker.dataset.startIndex = currentReviews;
          reviewsList.appendChild(marker);
          data.reviews.forEach((review) => {
            const newReview = createReviewElement(review);
            newReview.classList.add("new-review");
            reviewsList.appendChild(newReview);
          });
          if (hideReviewsBtn) {
            hideReviewsBtn.style.display = "block";
          }
          if (data.has_more === false) {
            showMoreBtn.style.display = "none";
          }
        } else if (!data.success) {
          alert(data.message || "Không thể tải thêm đánh giá.");
        } else if (data.reviews.length === 0) {
          showMoreBtn.style.display = "none";
        }
      })
      .catch((error) => {
        console.error("Fetch error:", error);
        showMoreBtn.innerHTML =
          '<i class="fas fa-chevron-down"></i> Xem thêm đánh giá';
        showMoreBtn.disabled = false;
        alert("Có lỗi xảy ra khi tải đánh giá: " + error.message);
      });
  });
  if (hideReviewsBtn) {
    hideReviewsBtn.addEventListener("click", function () {
      const reviewsList = document.querySelector(".reviews-list");
      const markers = reviewsList.querySelectorAll(".new-reviews-marker");
      const newReviews = reviewsList.querySelectorAll(".new-review");
      newReviews.forEach((review) => review.remove());
      markers.forEach((marker) => marker.remove());
      hideReviewsBtn.style.display = "none";
      showMoreBtn.style.display = "block";
    });
  }
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
  starInputs.forEach((input) => {
    input.addEventListener("change", function () {
      const rating = this.value;
      const ratingTexts = {
        1: "Rất tệ",
        2: "Tệ",
        3: "Bình thường",
        4: "Tốt",
        5: "Xuất sắc",
      };
      ratingText.textContent = `${rating} sao - ${ratingTexts[rating]}`;
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
