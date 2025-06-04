// Global variables
let currentSlideIndex = 0;
const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");

// Initialize the page when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  initializeSlider();
  initializeDatePicker();
  initializeBookingForm();
  initializeTabSwitching();
});

// Hero Slider Functions
function initializeSlider() {
  // Auto-play slider every 5 seconds
  setInterval(() => {
    changeSlide(1);
  }, 5000);
}

function changeSlide(direction) {
  // Remove active class from current slide and dot
  slides[currentSlideIndex].classList.remove("active");
  dots[currentSlideIndex].classList.remove("active");

  // Calculate new slide index
  currentSlideIndex += direction;

  // Handle wraparound
  if (currentSlideIndex >= slides.length) {
    currentSlideIndex = 0;
  } else if (currentSlideIndex < 0) {
    currentSlideIndex = slides.length - 1;
  }

  // Add active class to new slide and dot
  slides[currentSlideIndex].classList.add("active");
  dots[currentSlideIndex].classList.add("active");
}

function currentSlide(n) {
  // Remove active class from current slide and dot
  slides[currentSlideIndex].classList.remove("active");
  dots[currentSlideIndex].classList.remove("active");

  // Set new slide index (n is 1-based, convert to 0-based)
  currentSlideIndex = n - 1;

  // Add active class to new slide and dot
  slides[currentSlideIndex].classList.add("active");
  dots[currentSlideIndex].classList.add("active");
}

// Tab Functions
function openTab(evt, tabName) {
  // Hide all tab contents
  const tabContents = document.querySelectorAll(".tab-content");
  tabContents.forEach((content) => {
    content.classList.remove("active");
  });

  // Remove active class from all tab buttons
  const tabButtons = document.querySelectorAll(".tab-btn");
  tabButtons.forEach((btn) => {
    btn.classList.remove("active");
  });

  // Show selected tab content and mark button as active
  document.getElementById(tabName).classList.add("active");
  evt.currentTarget.classList.add("active");
}

function initializeTabSwitching() {
  // Add click event listeners to all tab buttons
  const tabButtons = document.querySelectorAll(".tab-btn");
  tabButtons.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      const tabName = this.getAttribute("onclick").match(/'([^']+)'/)[1];
      openTab(e, tabName);
    });
  });
}

// Date Picker Initialization
function initializeDatePicker() {
  const dateInput = document.getElementById("departureDate");
  if (dateInput) {
    // Set minimum date to today
    const today = new Date();
    const todayString = today.toISOString().split("T")[0];
    dateInput.min = todayString;

    // Set default date to tomorrow
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowString = tomorrow.toISOString().split("T")[0];
    dateInput.value = tomorrowString;
  }
}

// Booking Form Functions
function initializeBookingForm() {
  const form = document.getElementById("bookingForm");
  const guestSelect = document.getElementById("guestCount");
  const totalPriceElement = document.getElementById("totalPrice");

  // Update total price when guest count changes
  if (guestSelect) {
    guestSelect.addEventListener("change", updateTotalPrice);
  }

  // Handle form submission
  if (form) {
    form.addEventListener("submit", handleBookingSubmit);
  }
}

function updateTotalPrice() {
  const guestSelect = document.getElementById("guestCount");
  const totalPriceElement = document.getElementById("totalPrice");
  const basePrice = 1890000;

  if (guestSelect && totalPriceElement) {
    let guestCount = parseInt(guestSelect.value) || 1;

    // Handle "5+" option
    if (guestSelect.value === "5+") {
      guestCount = 5;
    }

    const totalPrice = basePrice * guestCount;
    totalPriceElement.textContent = formatPrice(totalPrice) + " VNĐ";
  }
}

function formatPrice(price) {
  return price.toLocaleString("vi-VN");
}

function handleBookingSubmit(e) {
  e.preventDefault();

  const fullName = document.getElementById("fullName").value.trim();
  const phoneNumber = document.getElementById("phoneNumber").value.trim();
  const departureDate = document.getElementById("departureDate").value;
  const guestCount = document.getElementById("guestCount").value;
  const note = document.getElementById("note").value.trim();

  // Validate form
  if (!validateBookingForm()) {
    return;
  }

  // Show booking confirmation
  const confirmMessage = `
        Xác nhận thông tin đặt tour:
        
        Thông tin khách hàng:
        - Họ và tên: ${fullName}
        - Số điện thoại: ${phoneNumber}
        
        Thông tin tour:
        - Ngày khởi hành: ${formatDate(departureDate)}
        - Số khách: ${guestCount}
        - Tổng tiền: ${document.getElementById("totalPrice").textContent}
        ${note ? `\n        - Ghi chú: ${note}` : ""}
        
        Bạn có muốn tiếp tục không?
    `;

  if (confirm(confirmMessage)) {
    // Simulate booking process
    showBookingSuccess();
  }
}

function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString("vi-VN", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  });
}

function showBookingSuccess() {
  alert(
    "Đặt tour thành công! Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất."
  );

  // Reset form
  document.getElementById("bookingForm").reset();
  initializeDatePicker();
  updateTotalPrice();
}

// Gallery Functions
function openModal(imageSrc) {
  const modal = document.getElementById("imageModal");
  const modalImage = document.getElementById("modalImage");

  if (modal && modalImage) {
    modalImage.src = imageSrc;
    modal.style.display = "block";
    document.body.style.overflow = "hidden"; // Prevent scrolling
  }
}

function closeModal() {
  const modal = document.getElementById("imageModal");
  if (modal) {
    modal.style.display = "none";
    document.body.style.overflow = "auto"; // Restore scrolling
  }
}

// Close modal when clicking outside the image
document.addEventListener("click", function (e) {
  const modal = document.getElementById("imageModal");
  if (e.target === modal) {
    closeModal();
  }
});

// Close modal with Escape key
document.addEventListener("keydown", function (e) {
  if (e.key === "Escape") {
    closeModal();
  }
});

// Smooth Scrolling for Internal Links
document.addEventListener("click", function (e) {
  if (e.target.matches('a[href^="#"]')) {
    e.preventDefault();
    const targetId = e.target.getAttribute("href").substring(1);
    const targetElement = document.getElementById(targetId);

    if (targetElement) {
      targetElement.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }
  }
});

// Intersection Observer for Animations
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

  // Observe elements for animation
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

// Initialize animations when page loads
document.addEventListener("DOMContentLoaded", initializeAnimations);

// Booking Form Validation
function validateBookingForm() {
  const departureDate = document.getElementById("departureDate");
  const guestCount = document.getElementById("guestCount");

  let isValid = true;

  // Clear previous error styles
  [departureDate, guestCount].forEach((field) => {
    if (field) {
      field.style.borderColor = "#e9ecef";
    }
  });

  // Validate departure date
  if (!departureDate.value) {
    departureDate.style.borderColor = "#e74c3c";
    isValid = false;
  }

  // Validate guest count
  if (!guestCount.value) {
    guestCount.style.borderColor = "#e74c3c";
    isValid = false;
  }

  return isValid;
}

// Contact Form Functions (if needed for future expansion)
function initializeContactForm() {
  const contactBtns = document.querySelectorAll(".contact-item");

  contactBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const phone = this.textContent.trim();
      if (phone.includes("0214 366 1666")) {
        window.location.href = "tel:+842143661666";
      } else if (phone.includes("@gmail.com")) {
        window.location.href = "mailto:chamsockhachhang.liberty@gmail.com";
      }
    });
  });
}

// Add touch/swipe support for mobile slider
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
    if (diff > 0) {
      // Swipe left - next slide
      changeSlide(1);
    } else {
      // Swipe right - previous slide
      changeSlide(-1);
    }
  }
}

// Add touch event listeners to hero section
document.addEventListener("DOMContentLoaded", function () {
  const heroSection = document.querySelector(".hero");
  if (heroSection) {
    heroSection.addEventListener("touchstart", handleTouchStart, {
      passive: true,
    });
    heroSection.addEventListener("touchend", handleTouchEnd, { passive: true });
  }

  // Initialize contact form
  initializeContactForm();
});

// Lazy loading for images (performance optimization)
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

// Error handling for images
document.addEventListener("DOMContentLoaded", function () {
  const images = document.querySelectorAll("img");

  images.forEach((img) => {
    img.addEventListener("error", function () {
      this.src =
        "https://via.placeholder.com/400x300/cccccc/666666?text=Image+Not+Found";
    });
  });
});

// Print functionality (bonus feature)
function printTour() {
  window.print();
}

// Share functionality (bonus feature)
function shareTour() {
  if (navigator.share) {
    navigator.share({
      title: "Tour Sapa - Chinh Phục Nóc Nhà Đông Dương",
      text: "Khám phá vẻ đẹp hùng vĩ của Sapa cùng Liberty Lào Cai",
      url: window.location.href,
    });
  } else {
    // Fallback - copy URL to clipboard
    navigator.clipboard.writeText(window.location.href).then(() => {
      alert("Đã sao chép đường link!");
    });
  }
}
// Review Form Functions
function toggleReviewForm() {
  const form = document.getElementById("reviewForm");
  const btn = document.querySelector(".write-review-btn");

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
  const form = document.querySelector(".review-form");
  form.reset();

  // Reset star rating display
  const ratingText = document.querySelector(".rating-text");
  ratingText.textContent = "Chọn số sao";
  ratingText.style.color = "#7f8c8d";

  // Reset all stars
  const stars = document.querySelectorAll(".star-rating .star");
  stars.forEach((star) => {
    star.style.color = "#e9ecef";
    star.style.transform = "scale(1)";
  });
}

function submitReview(event) {
  event.preventDefault();

  const formData = new FormData(event.target);
  const reviewData = {
    rating: formData.get("rating"),
    name: formData.get("reviewer-name"),
    content: formData.get("review-content"),
  };

  // Validate rating
  if (!reviewData.rating) {
    alert("Vui lòng chọn số sao đánh giá!");
    return;
  }

  // Validate name
  if (!reviewData.name.trim()) {
    alert("Vui lòng nhập họ và tên!");
    return;
  }

  // Validate content
  if (!reviewData.content.trim()) {
    alert("Vui lòng nhập nội dung đánh giá!");
    return;
  }

  // Show loading state
  const submitBtn = document.querySelector(".submit-review-btn");
  const originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
  submitBtn.disabled = true;

  // Simulate API call
  setTimeout(() => {
    // Create new review element
    const newReview = createReviewElement(reviewData);

    // Add to reviews list
    const reviewsList = document.querySelector(".reviews-list");
    reviewsList.insertBefore(newReview, reviewsList.firstChild);

    // Update review count and rating
    updateReviewStats();

    // Show success message
    alert(
      "Cảm ơn bạn đã chia sẻ đánh giá! Đánh giá của bạn đã được thêm thành công."
    );

    // Reset and hide form
    toggleReviewForm();

    // Reset button
    submitBtn.innerHTML = originalText;
    submitBtn.disabled = false;

    // Scroll to new review
    newReview.scrollIntoView({ behavior: "smooth", block: "center" });
  }, 1500);
}

function createReviewElement(reviewData) {
  const reviewDiv = document.createElement("div");
  reviewDiv.className = "review-item new-review";

  // Create stars HTML
  let starsHTML = "";
  for (let i = 1; i <= 5; i++) {
    if (i <= reviewData.rating) {
      starsHTML += '<i class="fas fa-star"></i>';
    } else {
      starsHTML += '<i class="far fa-star"></i>';
    }
  }

  // Get current date
  const currentDate = new Date().toLocaleDateString("vi-VN");

  reviewDiv.innerHTML = `
        <div class="review-header">
            <div class="reviewer-info">
                <div class="reviewer-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="reviewer-details">
                    <div class="reviewer-name">${reviewData.name}</div>
                    <div class="review-date">${currentDate}</div>
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

  // Add highlight animation for new review
  reviewDiv.style.background = "linear-gradient(135deg, #e3f2fd, #f8f9fa)";
  reviewDiv.style.border = "2px solid #3498db";
  reviewDiv.style.borderRadius = "10px";
  reviewDiv.style.padding = "20px";
  reviewDiv.style.marginBottom = "15px";

  // Remove highlight after animation
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

function updateReviewStats() {
  const ratingCount = document.querySelector(".rating-count");
  const currentCount = parseInt(ratingCount.textContent.match(/\d+/)[0]);
  ratingCount.textContent = `(${currentCount + 1} đánh giá)`;
}

// Initialize star rating interaction
function initializeReviewForm() {
  const writeReviewBtn = document.querySelector(".write-review-btn");
  if (writeReviewBtn) {
    writeReviewBtn.addEventListener("click", toggleReviewForm);
  }

  const reviewForm = document.querySelector(".review-form");
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

  // Add hover effect for stars
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

// Initialize review form when page loads
document.addEventListener("DOMContentLoaded", initializeReviewForm);
