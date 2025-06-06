let currentSlideIndex = 0;
const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");

document.addEventListener("DOMContentLoaded", function () {
  initializeSlider();
  initializeDatePicker();
  initializeBookingForm();
  initializeTabSwitching();
  initializeAnimations();
  initializeReviewForm();
  initializeContactForm();
  loadMoreReviews();
  initializeLazyLoading();
});

function initializeSlider() {
  const slides = document.querySelectorAll(".slide");
  const dots = document.querySelectorAll(".dot");
  if (slides.length === 0) return;

  // Tự động chuyển slide mỗi 5 giây
  let slideInterval = setInterval(() => {
    changeSlide(1);
  }, 5000);

  // Tạm dừng slider khi trang không hiển thị
  document.addEventListener("visibilitychange", () => {
    if (document.hidden) {
      clearInterval(slideInterval);
    } else {
      slideInterval = setInterval(() => {
        changeSlide(1);
      }, 5000);
    }
  });
}

function changeSlide(direction) {
  const slides = document.querySelectorAll(".slide");
  const dots = document.querySelectorAll(".dot");
  if (slides.length === 0) return;

  slides[currentSlideIndex].classList.remove("active");
  if (dots[currentSlideIndex])
    dots[currentSlideIndex].classList.remove("active");

  currentSlideIndex += direction;
  if (currentSlideIndex >= slides.length) currentSlideIndex = 0;
  else if (currentSlideIndex < 0) currentSlideIndex = slides.length - 1;

  slides[currentSlideIndex].classList.add("active");
  if (dots[currentSlideIndex]) dots[currentSlideIndex].classList.add("active");
}

function currentSlide(n) {
  const slides = document.querySelectorAll(".slide");
  const dots = document.querySelectorAll(".dot");
  if (slides.length === 0) return;

  slides[currentSlideIndex].classList.remove("active");
  if (dots[currentSlideIndex])
    dots[currentSlideIndex].classList.remove("active");

  currentSlideIndex = n - 1;
  slides[currentSlideIndex].classList.add("active");
  if (dots[currentSlideIndex]) dots[currentSlideIndex].classList.add("active");
}

function openTab(evt, tabName) {
  const tabContents = document.querySelectorAll(".tab-content");
  tabContents.forEach((content) => content.classList.remove("active"));
  const tabButtons = document.querySelectorAll(".tab-btn");
  tabButtons.forEach((btn) => btn.classList.remove("active"));
  document.getElementById(tabName).classList.add("active");
  evt.currentTarget.classList.add("active");
}

function initializeTabSwitching() {
  const tabButtons = document.querySelectorAll(".tab-btn");
  tabButtons.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      const tabName = this.getAttribute("onclick").match(/'([^']+)'/)[1];
      openTab(e, tabName);
    });
  });
}

function initializeDatePicker() {
  const dateInput = document.getElementById("departureDate");
  if (dateInput) {
    const today = new Date();
    const todayString = today.toISOString().split("T")[0];
    dateInput.min = todayString;
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowString = tomorrow.toISOString().split("T")[0];
    dateInput.value = tomorrowString;
  }
}

function initializeBookingForm() {
  const form = document.getElementById("bookingForm");
  const guestInput = document.getElementById("guestCount");
  const inputs = form.querySelectorAll("input[required], #email");

  inputs.forEach((input) => {
    input.addEventListener("input", validateInput);
    input.addEventListener("blur", validateInput);
    input.addEventListener("input", clearErrors);
  });

  if (guestInput) {
    guestInput.addEventListener("input", updateTotalPrice);
    guestInput.addEventListener("change", ensureValidGuestCount);
  }

  if (form) {
    form.addEventListener("submit", handleBookingSubmit);
  }

  updateTotalPrice();
}

function updateGuestCount(delta) {
  const guestInput = document.getElementById("guestCount");
  if (guestInput) {
    let currentCount = parseInt(guestInput.value) || 1;
    currentCount += delta;
    if (currentCount < 1) currentCount = 1;
    if (currentCount > 25) currentCount = 25;
    guestInput.value = currentCount;
    updateTotalPrice();
  }
}

function ensureValidGuestCount() {
  const guestInput = document.getElementById("guestCount");
  if (guestInput) {
    let value = parseInt(guestInput.value) || 1;
    if (value < 1) value = 1;
    if (value > 25) value = 25;
    guestInput.value = value;
    updateTotalPrice();
  }
}

function updateTotalPrice() {
  const guestInput = document.getElementById("guestCount");
  const totalPriceElement = document.getElementById("totalPrice");
  const basePriceElement = document.querySelector(".booking-price");
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;

  if (guestInput && totalPriceElement && basePriceElement) {
    let guestCount = parseInt(guestInput.value) || 1;
    let basePriceText = basePriceElement.textContent.trim();

    // Kiểm tra xem giá có phải là số hay không
    if (/^\d+/.test(basePriceText)) {
      let basePrice = parseFloat(basePriceText.replace(/[^\d]/g, ""));
      const totalPrice = basePrice * guestCount;
      totalPriceElement.textContent = formatPrice(totalPrice) + " VNĐ";
    } else {
      totalPriceElement.textContent = "Liên hệ/Contact";
    }
  } else {
    console.error(
      "One or more elements (guestInput, totalPriceElement, basePriceElement) are missing."
    );
  }
}

function formatPrice(price) {
  return price.toLocaleString("vi-VN");
}

function validateInput(e) {
  const input = e.target;
  const value = input.value.trim();
  const inputGroup = input.closest(".form-group");
  clearErrors({ target: input });

  let isValid = true;
  let errorMessage = "";
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;

  if (input.hasAttribute("required") && !value) {
    isValid = false;
    errorMessage =
      languageId == 1 ? "Trường này là bắt buộc" : "This field is required";
  } else if (input.type === "tel" && value) {
    const phoneRegex = /^[0-9]{10,11}$/;
    if (!phoneRegex.test(value)) {
      isValid = false;
      errorMessage =
        languageId == 1
          ? "Số điện thoại không hợp lệ (10-11 số)"
          : "Invalid phone number (10-11 digits)";
    }
  } else if (input.type === "email" && value) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
      isValid = false;
      errorMessage = languageId == 1 ? "Email không hợp lệ" : "Invalid email";
    }
  } else if (input.type === "number" && value) {
    const numValue = parseInt(value);
    const min = parseInt(input.getAttribute("min")) || 1;
    const max = parseInt(input.getAttribute("max")) || 25;
    if (numValue < min || numValue > max) {
      isValid = false;
      errorMessage =
        languageId == 1
          ? `Số khách phải từ ${min} đến ${max}`
          : `Number of guests must be between ${min} and ${max}`;
    }
  }

  if (!isValid) {
    showInputError(inputGroup, errorMessage);
    input.classList.add("error");
  } else {
    input.classList.remove("error");
    input.classList.add("valid");
  }

  return isValid;
}

function showInputError(inputGroup, message) {
  const existingError = inputGroup.querySelector(".error-message");
  if (existingError) {
    existingError.textContent = message;
    return;
  }

  const errorElement = document.createElement("div");
  errorElement.className = "error-message";
  errorElement.textContent = message;
  errorElement.style.color = "#dc3545";
  errorElement.style.fontSize = "0.875rem";
  errorElement.style.marginTop = "5px";
  inputGroup.appendChild(errorElement);
}

function clearErrors(e) {
  const input = e.target;
  const inputGroup = input.closest(".form-group");
  const errorElement = inputGroup.querySelector(".error-message");

  if (errorElement) errorElement.remove();
  input.classList.remove("error");
}

function handleBookingSubmit(e) {
  e.preventDefault();

  const form = document.getElementById("bookingForm");
  if (!validateForm(form)) return;

  const formData = new FormData(form);
  const submitBtn = form.querySelector(".btn-book");
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;

  showLoadingState(submitBtn);

  fetch("/libertylaocai/view/php/chitiettour.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      hideLoadingState(submitBtn);
      if (data.success) {
        showSuccessMessage(
          languageId == 1
            ? "Đặt Tour Thành Công!"
            : "Tour Booked Successfully!",
          languageId == 1
            ? "Chúng tôi đã nhận được thông tin của bạn và sẽ liên hệ sớm nhất có thể."
            : "We have received your information and will contact you as soon as possible."
        );
        resetForm(form);
      } else {
        showErrorMessage(data.message);
      }
    })
    .catch((error) => {
      hideLoadingState(submitBtn);
      showErrorMessage(
        languageId == 1
          ? `Có lỗi xảy ra khi gửi yêu cầu: ${error.message}`
          : `An error occurred while sending the request: ${error.message}`
      );
    });
}

function showLoadingState(button) {
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;
  const originalText = button.innerHTML;
  button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${
    languageId == 1 ? "Đang xử lý..." : "Processing..."
  }`;
  button.disabled = true;
  button.style.opacity = "0.7";
  button.dataset.originalText = originalText;
}

function hideLoadingState(button) {
  button.innerHTML = button.dataset.originalText;
  button.disabled = false;
  button.style.opacity = "1";
  delete button.dataset.originalText;
}

function showSuccessMessage(title, message) {
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;
  const modal = document.createElement("div");
  modal.className = "success-modal";
  modal.innerHTML = `
    <div class="modal-overlay">
      <div class="modal-content">
        <div class="success-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <h3>${title}</h3>
        <p>${message}</p>
        <div class="modal-actions">
          <button class="close-modal-btn">${
            languageId == 1 ? "Đóng" : "Close"
          }</button>
          <button class="continue-btn" onclick="window.location.reload()">${
            languageId == 1 ? "Đặt tour khác" : "Book Another Tour"
          }</button>
        </div>
      </div>
    </div>
  `;

  document.body.appendChild(modal);

  const closeBtn = modal.querySelector(".close-modal-btn");
  const overlay = modal.querySelector(".modal-overlay");

  function closeModal() {
    modal.style.animation = "fadeOut 0.3s ease-out forwards";
    setTimeout(() => {
      if (document.body.contains(modal)) document.body.removeChild(modal);
    }, 300);
  }

  closeBtn.addEventListener("click", closeModal);
  overlay.addEventListener("click", (e) => {
    if (e.target === overlay) closeModal();
  });

  setTimeout(closeModal, 8000);
}

function showErrorMessage(message) {
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;
  const errorModal = document.createElement("div");
  errorModal.className = "error-modal";
  errorModal.innerHTML = `
    <div class="modal-overlay">
      <div class="modal-content error">
        <div class="error-icon">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>${languageId == 1 ? "Có Lỗi Xảy Ra!" : "An Error Occurred!"}</h3>
        <p>${message}</p>
        <button class="close-modal-btn">${
          languageId == 1 ? "Đóng" : "Close"
        }</button>
      </div>
    </div>
  `;

  document.body.appendChild(errorModal);

  const closeBtn = errorModal.querySelector(".close-modal-btn");
  const overlay = errorModal.querySelector(".modal-overlay");

  function closeErrorModal() {
    errorModal.style.animation = "fadeOut 0.3s ease-out forwards";
    setTimeout(() => {
      if (document.body.contains(errorModal))
        document.body.removeChild(errorModal);
    }, 300);
  }

  closeBtn.addEventListener("click", closeErrorModal);
  overlay.addEventListener("click", (e) => {
    if (e.target === overlay) closeErrorModal();
  });

  setTimeout(closeErrorModal, 5000);
}

function validateForm(form) {
  let isValid = true;
  const requiredInputs = form.querySelectorAll(
    "input[required], textarea[required]"
  );
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;

  requiredInputs.forEach((input) => {
    if (!validateInput({ target: input })) isValid = false;
  });

  const emailInput = form.querySelector("#email, #reviewer-email");
  if (
    emailInput &&
    emailInput.value.trim() &&
    !validateInput({ target: emailInput })
  ) {
    isValid = false;
  }

  if (!isValid) {
    showErrorMessage(
      languageId == 1
        ? "Vui lòng kiểm tra lại các trường thông tin."
        : "Please check the information fields."
    );
  }

  return isValid;
}

function resetForm(form) {
  form.reset();
  const inputs = form.querySelectorAll("input, textarea");
  inputs.forEach((input) => input.classList.remove("error", "valid"));

  const errorMessages = form.querySelectorAll(".error-message");
  errorMessages.forEach((error) => error.remove());

  initializeDatePicker();
  const guestInput = document.getElementById("guestCount");
  if (guestInput) guestInput.value = 1;
  updateTotalPrice();
}

function openModal(imageSrc) {
  const modal = document.getElementById("imageModal");
  const modalImage = document.getElementById("modalImage");
  if (modal && modalImage) {
    modalImage.src = imageSrc;
    modal.style.display = "block";
    document.body.style.overflow = "hidden";
  }
}

function closeModal() {
  const modal = document.getElementById("imageModal");
  if (modal) {
    modal.style.display = "none";
    document.body.style.overflow = "auto";
  }
}

document.addEventListener("click", function (e) {
  const modal = document.getElementById("imageModal");
  if (e.target === modal) closeModal();
});

document.addEventListener("keydown", function (e) {
  if (e.key === "Escape") closeModal();
});

function initializeAnimations() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1";
        entry.target.style.transform = "translateY(0)";
      }
    });
  }, observerOptions);

  const animatedElements = document.querySelectorAll(
    ".highlight-item, .day-item, .gallery-item"
  );
  animatedElements.forEach((el) => {
    el.style.opacity = "0";
    el.style.transform = "translateY(20px)";
    el.style.transition = "opacity 0.6s ease, transform 0.6s ease";
    observer.observe(el);
  });
}

function initializeContactForm() {
  const contactBtns = document.querySelectorAll(".contact-item");
  contactBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const contact = this.textContent.trim();
      if (contact.includes("0214 366 1666")) {
        window.location.href = "tel:+842143661666";
      } else if (contact.includes("@gmail.com")) {
        window.location.href = "mailto:chamsockhachhang.liberty@gmail.com";
      }
    });
  });
}

let touchStartX = 0;
let touchEndX = 0;

function handleTouchStart(e) {
  touchStartX = e.changedTouches[0].screenX;
}

function handleTouchEnd(e) {
  touchEndX = e.changedTouches[0].screenX;
  handleSwipe();
}

function handleSwipe() {
  const swipeThreshold = 50;
  const diff = touchStartX - touchEndX;
  if (Math.abs(diff) > swipeThreshold) {
    if (diff > 0) changeSlide(1);
    else changeSlide(-1);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const heroSection = document.querySelector(".hero");
  if (heroSection) {
    heroSection.addEventListener("touchstart", handleTouchStart, {
      passive: true,
    });
    heroSection.addEventListener("touchend", handleTouchEnd, { passive: true });
  }
});

function initializeLazyLoading() {
  const images = document.querySelectorAll("img[data-src]");
  const imageObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const img = entry.target;
        img.src = img.dataset.src;
        img.removeAttribute("data-src");
        imageObserver.unobserve(img);
      }
    });
  });

  images.forEach((img) => imageObserver.observe(img));
}

document.addEventListener("DOMContentLoaded", function () {
  const images = document.querySelectorAll("img");
  images.forEach((img) => {
    img.addEventListener("error", function () {
      this.src =
        "https://via.placeholder.com/400x300/cccccc/666666?text=Image+Not+Found";
    });
  });
});

function initializeReviewForm() {
  const writeReviewBtn = document.querySelector(".write-review-btn");
  const cancelBtn = document.querySelector(".review-form .cancel-btn");
  const form = document.getElementById("reviewFormSubmit");
  const stars = document.querySelectorAll(".star-rating input");
  const ratingText = document.querySelector(".rating-text");
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;

  if (writeReviewBtn) {
    writeReviewBtn.addEventListener("click", toggleReviewForm);
  }

  if (cancelBtn) {
    cancelBtn.addEventListener("click", toggleReviewForm);
  }

  if (form) {
    form.addEventListener("submit", submitReview);
  }

  stars.forEach((star) => {
    star.addEventListener("change", () => {
      const rating = parseInt(star.value);
      ratingText.textContent =
        rating === 1
          ? languageId == 1
            ? "1 sao - Kém"
            : "1 star - Poor"
          : rating === 2
          ? languageId == 1
            ? "2 sao - Trung bình"
            : "2 stars - Average"
          : rating === 3
          ? languageId == 1
            ? "3 sao - Tốt"
            : "3 stars - Good"
          : rating === 4
          ? languageId == 1
            ? "4 sao - Rất tốt"
            : "4 stars - Very Good"
          : languageId == 1
          ? "5 sao - Xuất sắc"
          : "5 stars - Excellent";
      ratingText.style.color = "#2c3e50";

      const starLabels = document.querySelectorAll(".star-rating .star");
      starLabels.forEach((label, index) => {
        if (index < rating) {
          label.style.color = "#f1c40f";
          label.style.transform = "scale(1.2)";
        } else {
          label.style.color = "#e9ecef";
          label.style.transform = "scale(1)";
        }
      });
    });
  });
}

function toggleReviewForm() {
  const form = document.getElementById("reviewForm");
  const btn = document.querySelector(".write-review-btn");
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;

  if (form.style.display === "none" || form.style.display === "") {
    form.style.display = "block";
    btn.innerHTML = `<i class="fas fa-times"></i> ${
      languageId == 1 ? "Hủy viết đánh giá" : "Cancel Writing Review"
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
  const ratingText = document.querySelector(".rating-text");
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;

  form.reset();
  ratingText.textContent = languageId == 1 ? "Chọn số sao" : "Select rating";
  ratingText.style.color = "#7f8c8d";
  const stars = document.querySelectorAll(".star-rating .star");
  stars.forEach((star) => {
    star.style.color = "#e9ecef";
    star.style.transform = "scale(1)";
  });

  const inputs = form.querySelectorAll("input, textarea");
  inputs.forEach((input) => input.classList.remove("error", "valid"));

  const errorMessages = form.querySelectorAll(".error-message");
  errorMessages.forEach((error) => error.remove());
}

function submitReview(event) {
  event.preventDefault();

  const form = document.getElementById("reviewFormSubmit");
  if (!validateForm(form)) return;

  const formData = new FormData(form);
  const submitBtn = form.querySelector(".submit-review-btn");
  const languageId = document.documentElement.lang === "vi" ? 1 : 2;

  showLoadingState(submitBtn);

  fetch("/libertylaocai/view/php/chitiettour.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      hideLoadingState(submitBtn);
      if (data.success) {
        showSuccessMessage(
          languageId == 1
            ? "Gửi Đánh Giá Thành Công!"
            : "Review Submitted Successfully!",
          languageId == 1
            ? "Cảm ơn bạn đã chia sẻ trải nghiệm của mình!"
            : "Thank you for sharing your experience!"
        );
        appendReview(data.review);
        resetReviewForm();
        toggleReviewForm();
      } else {
        showErrorMessage(data.message);
      }
    })
    .catch((error) => {
      hideLoadingState(submitBtn);
      showErrorMessage(
        languageId == 1
          ? `Có lỗi xảy ra khi gửi đánh giá: ${error.message}`
          : `An error occurred while submitting the review: ${error.message}`
      );
    });
}

function appendReview(review) {
  const reviewsList = document.querySelector(".reviews-list");
  if (!reviewsList) return;

  const reviewItem = document.createElement("div");
  reviewItem.className = "review-item";
  reviewItem.innerHTML = `
    <div class="review-header">
      <div class="reviewer-info">
        <div class="reviewer-avatar"><i class="fas fa-user"></i></div>
        <div class="reviewer-details">
          <div class="reviewer-name">${review.name}</div>
          <div class="review-date">${review.date}</div>
        </div>
      </div>
      <div class="review-rating">
        ${[...Array(5)]
          .map(
            (_, i) =>
              `<i class="${i < review.rating ? "fas" : "far"} fa-star"></i>`
          )
          .join("")}
      </div>
    </div>
    <div class="review-content">
      <p>${review.content}</p>
    </div>
  `;

  reviewsList.insertBefore(reviewItem, reviewsList.firstChild);
  reviewItem.style.opacity = "0";
  reviewItem.style.transform = "translateY(20px)";
  setTimeout(() => {
    reviewItem.style.transition = "opacity 0.6s ease, transform 0.6s ease";
    reviewItem.style.opacity = "1";
    reviewItem.style.transform = "translateY(0)";
  }, 100);
}

function loadMoreReviews() {
  const showMoreBtn = document.querySelector(".show-more-reviews");
  const hideBtn = document.querySelector(".hide-reviews");
  let offset = document.querySelectorAll(".reviews-list .review-item").length;

  if (showMoreBtn) {
    showMoreBtn.addEventListener("click", function () {
      const idDichVu = document.querySelector('input[name="id_dichvu"]').value;
      const languageId = document.documentElement.lang === "vi" ? 1 : 2;

      fetch("/libertylaocai/view/php/chitiettour.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `action=load_more_reviews&id_dichvu=${idDichVu}&offset=${offset}`,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            data.reviews.forEach((review) => appendReview(review));
            offset += data.reviews.length;

            if (!data.has_more) {
              showMoreBtn.style.display = "none";
              hideBtn.style.display = "inline-block";
            }
          } else {
            showErrorMessage(data.message);
          }
        })
        .catch((error) => {
          showErrorMessage(
            languageId == 1
              ? `Lỗi khi tải thêm đánh giá: ${error.message}`
              : `Error loading more reviews: ${error.message}`
          );
        });
    });
  }

  if (hideBtn) {
    hideBtn.addEventListener("click", function () {
      const reviewsList = document.querySelector(".reviews-list");
      const initialReviews = Array.from(reviewsList.children).slice(0, 3);
      reviewsList.innerHTML = "";
      initialReviews.forEach((review) => reviewsList.appendChild(review));
      offset = initialReviews.length;
      showMoreBtn.style.display = "inline-block";
      hideBtn.style.display = "none";
      window.scrollTo({ top: reviewsList.offsetTop - 100, behavior: "smooth" });
    });
  }
}
