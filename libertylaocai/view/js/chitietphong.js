// Banner Slider Functions
let currentSlide = 0;
const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");

function showSlide(index) {
  // Remove active class from all slides and dots
  slides.forEach((slide) => slide.classList.remove("active"));
  dots.forEach((dot) => dot.classList.remove("active"));

  // Add active class to current slide and dot
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

// Auto-play slider
let autoSlideInterval = setInterval(nextSlide, 5000);

// Pause auto-play on hover
const sliderContainer = document.querySelector(".slider-container");
sliderContainer.addEventListener("mouseenter", () => {
  clearInterval(autoSlideInterval);
});

sliderContainer.addEventListener("mouseleave", () => {
  autoSlideInterval = setInterval(nextSlide, 5000);
});

// Tab Navigation Functions
function switchTab(tabName) {
  // Remove active class from all tabs and content
  document.querySelectorAll(".tab-btn").forEach((btn) => {
    btn.classList.remove("active");
  });
  document.querySelectorAll(".tab-content").forEach((content) => {
    content.classList.remove("active");
  });

  // Add active class to selected tab and content
  document.querySelector(`[data-tab="${tabName}"]`).classList.add("active");
  document.getElementById(tabName).classList.add("active");
}

// Tab click event listeners
document.querySelectorAll(".tab-btn").forEach((btn) => {
  btn.addEventListener("click", (e) => {
    const tabName = e.target.getAttribute("data-tab");
    switchTab(tabName);
  });
});

// Booking Form Functions
function calculateNights() {
  const checkinDate = document.getElementById("checkin").value;
  const checkoutDate = document.getElementById("checkout").value;

  if (checkinDate && checkoutDate) {
    const checkin = new Date(checkinDate);
    const checkout = new Date(checkoutDate);
    const timeDiff = checkout.getTime() - checkin.getTime();
    const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));

    if (nights > 0) {
      document.getElementById("nights-count").textContent = `${nights} đêm`;

      const roomPrice = 700000;
      const totalPrice = roomPrice * nights;
      document.getElementById("total-price").textContent =
        new Intl.NumberFormat("vi-VN").format(totalPrice) + " VNĐ";
    }
  }
}

// Set minimum date to today
function setMinDate() {
  const today = new Date().toISOString().split("T")[0];
  document.getElementById("checkin").setAttribute("min", today);
  document.getElementById("checkout").setAttribute("min", today);

  // Set default dates
  const tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  const dayAfterTomorrow = new Date();
  dayAfterTomorrow.setDate(dayAfterTomorrow.getDate() + 2);

  document.getElementById("checkin").value = tomorrow
    .toISOString()
    .split("T")[0];
  document.getElementById("checkout").value = dayAfterTomorrow
    .toISOString()
    .split("T")[0];

  calculateNights();
}

// Update checkout date when checkin changes
document.getElementById("checkin").addEventListener("change", function () {
  const checkinDate = new Date(this.value);
  const minCheckout = new Date(checkinDate);
  minCheckout.setDate(minCheckout.getDate() + 1);

  const checkoutInput = document.getElementById("checkout");
  checkoutInput.setAttribute("min", minCheckout.toISOString().split("T")[0]);

  // If checkout is before or equal to checkin, update it
  const currentCheckout = new Date(checkoutInput.value);
  if (currentCheckout <= checkinDate) {
    checkoutInput.value = minCheckout.toISOString().split("T")[0];
  }

  calculateNights();
});

document.getElementById("checkout").addEventListener("change", calculateNights);

// Form validation and submission
document
  .querySelector(".booking-form")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    // Get form data
    const formData = new FormData(this);
    const bookingData = {
      checkin: formData.get("checkin"),
      checkout: formData.get("checkout"),
      adults: formData.get("adults"),
      children: formData.get("children"),
      fullname: formData.get("fullname"),
      email: formData.get("email"),
      phone: formData.get("phone"),
      specialRequests: formData.get("special-requests"),
      roomType: "Deluxe Double",
      totalPrice: document.getElementById("total-price").textContent,
    };

    // Validate required fields
    if (
      !bookingData.checkin ||
      !bookingData.checkout ||
      !bookingData.fullname ||
      !bookingData.email ||
      !bookingData.phone
    ) {
      alert("Vui lòng điền đầy đủ thông tin bắt buộc!");
      return;
    }

    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(bookingData.email)) {
      alert("Vui lòng nhập địa chỉ email hợp lệ!");
      return;
    }

    // Validate phone format (Vietnamese phone number)
    const phoneRegex = /^(\+84|84|0)[1-9][0-9]{8,9}$/;
    if (!phoneRegex.test(bookingData.phone.replace(/\s/g, ""))) {
      alert("Vui lòng nhập số điện thoại hợp lệ!");
      return;
    }

    // Show loading state
    const submitBtn = document.querySelector(".submit-booking-btn");
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML =
      '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    submitBtn.disabled = true;

    // Simulate booking process
    setTimeout(() => {
      alert(
        `Đặt phòng thành công!\n\nThông tin đặt phòng:\n- Phòng: ${bookingData.roomType}\n- Từ: ${bookingData.checkin}\n- Đến: ${bookingData.checkout}\n- Tổng tiền: ${bookingData.totalPrice}\n\nChúng tôi sẽ liên hệ xác nhận trong 24h!`
      );

      // Reset form
      this.reset();
      setMinDate();

      // Reset button
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;

      // Switch back to description tab
      switchTab("description");
    }, 2000);
  });

// Smooth scrolling for room cards
document.querySelectorAll(".view-room-btn").forEach((btn) => {
  btn.addEventListener("click", function (e) {
    e.preventDefault();
    // In a real application, this would navigate to the specific room page
    alert("Chức năng xem chi tiết phòng sẽ được phát triển!");
  });
});

// Phone number formatting
document.getElementById("phone").addEventListener("input", function (e) {
  let value = e.target.value.replace(/\D/g, "");

  // Format Vietnamese phone number
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

// Initialize page
document.addEventListener("DOMContentLoaded", function () {
  setMinDate();

  // Set up keyboard navigation for slider
  document.addEventListener("keydown", function (e) {
    if (e.key === "ArrowLeft") {
      prevSlide();
    } else if (e.key === "ArrowRight") {
      nextSlide();
    }
  });

  // Touch/swipe support for mobile
  let touchStartX = 0;
  let touchEndX = 0;

  sliderContainer.addEventListener("touchstart", function (e) {
    touchStartX = e.changedTouches[0].screenX;
  });

  sliderContainer.addEventListener("touchend", function (e) {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
  });

  function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;

    if (Math.abs(diff) > swipeThreshold) {
      if (diff > 0) {
        nextSlide(); // Swipe left, go to next
      } else {
        prevSlide(); // Swipe right, go to previous
      }
    }
  }

  // Lazy loading for images (performance optimization)
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

  // Add smooth scroll behavior to page
  document.documentElement.style.scrollBehavior = "smooth";

  // Price formatting
  const priceElements = document.querySelectorAll(
    ".current-price, .room-card-price, .price-total span:last-child"
  );
  priceElements.forEach((element) => {
    const price = element.textContent.replace(/\D/g, "");
    if (price) {
      element.textContent =
        new Intl.NumberFormat("vi-VN").format(price) + " VNĐ";
    }
  });
});

// Utility function to format Vietnamese currency
function formatVND(amount) {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(amount);
}

// Add animation on scroll
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
    {
      threshold: 0.1,
    }
  );

  elements.forEach((element) => {
    element.style.opacity = "0";
    element.style.transform = "translateY(20px)";
    element.style.transition = "all 0.6s ease";
    observer.observe(element);
  });
}

// Initialize animations
document.addEventListener("DOMContentLoaded", animateOnScroll);
// Thêm vào cuối file chitietphong.js

// Review Form Functions
function toggleReviewForm() {
  const form = document.getElementById("reviewForm");
  const btn = document.querySelector(".write-review-btn");

  if (form.style.display === "none" || form.style.display === "") {
    form.style.display = "block";
    btn.innerHTML = '<i class="fas fa-times"></i> Hủy viết đánh giá';
    btn.style.background = "linear-gradient(135deg, #dc3545, #c82333)";
  } else {
    form.style.display = "none";
    btn.innerHTML = '<i class="fas fa-pen"></i> Viết đánh giá của bạn';
    btn.style.background = "linear-gradient(135deg, #28a745, #20c997)";
    resetReviewForm();
  }
}

function resetReviewForm() {
  const form = document.querySelector(".review-form");
  form.reset();

  // Reset star rating display
  const ratingText = document.querySelector(".rating-text");
  ratingText.textContent = "Chọn số sao";
  ratingText.style.color = "#666";

  // Reset all stars
  const stars = document.querySelectorAll(".star-rating .star");
  stars.forEach((star) => {
    star.style.color = "#dee2e6";
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

    // Update review count and rating (simplified)
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
  reviewDiv.style.border = "2px solid #007bff";
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
  // Simplified update - in real app, this would recalculate from all reviews
  const ratingCount = document.querySelector(".rating-count");
  const currentCount = parseInt(ratingCount.textContent.match(/\d+/)[0]);
  ratingCount.textContent = `(${currentCount + 1} đánh giá)`;
}

// Initialize star rating interaction
document.addEventListener("DOMContentLoaded", function () {
  // Add click event for star rating
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
      ratingText.style.color = "#007bff";
      ratingText.style.fontWeight = "500";
    });
  });

  // Add hover effect for stars
  const stars = document.querySelectorAll(".star-rating .star");
  stars.forEach((star, index) => {
    star.addEventListener("mouseenter", function () {
      // Highlight current and previous stars
      for (let i = stars.length - 1; i >= stars.length - 1 - index; i--) {
        stars[i].style.color = "#ffc107";
        stars[i].style.transform = "scale(1.1)";
      }
    });

    star.addEventListener("mouseleave", function () {
      // Reset if not selected
      const checkedInput = document.querySelector(".star-rating input:checked");
      if (!checkedInput) {
        stars.forEach((s) => {
          s.style.color = "#dee2e6";
          s.style.transform = "scale(1)";
        });
      } else {
        // Reset to selected state
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
let currentRoomSlide = 0;
let roomsPerSlide = 3;
let totalRoomCards = 0;
let maxRoomSlides = 0;

function updateDebugInfo() {
  document.getElementById("currentSlide").textContent = currentRoomSlide;
  document.getElementById("maxSlides").textContent = maxRoomSlides;
  document.getElementById("screenWidth").textContent = window.innerWidth;
  document.getElementById("cardsPerSlide").textContent = roomsPerSlide;
}

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

  // Reset slide if current position is invalid
  if (currentRoomSlide > maxRoomSlides) {
    currentRoomSlide = maxRoomSlides;
  }

  updateRoomSlider();
  updateRoomNavigation();
  updateDebugInfo();
}

function updateRoomSlider() {
  const roomsGrid = document.querySelector(".rooms-grid");
  const roomCards = document.querySelectorAll(".room-card");

  if (!roomsGrid || roomCards.length === 0) return;

  // Calculate card width based on screen size
  let cardWidth, gap;
  const screenWidth = window.innerWidth;

  if (screenWidth <= 768) {
    cardWidth = 100; // 100% width for mobile
    gap = 0;
  } else if (screenWidth <= 1024) {
    cardWidth = 48; // ~48% width for tablet (2 cards)
    gap = 2;
  } else {
    cardWidth = 31.33; // ~31.33% width for desktop (3 cards)
    gap = 1;
  }

  // Set CSS custom properties for smooth transition
  roomsGrid.style.setProperty("--card-width", `${cardWidth}%`);
  roomsGrid.style.setProperty("--gap", `${gap}%`);

  const translateX = currentRoomSlide * (cardWidth + gap);
  roomsGrid.style.transform = `translateX(-${translateX}%)`;
}

function nextRoomSlide() {
  currentRoomSlide = (currentRoomSlide + 1) % (maxRoomSlides + 1);
  updateRoomSlider();
  updateRoomNavigation();
  updateDebugInfo();
}

function prevRoomSlide() {
  currentRoomSlide =
    (currentRoomSlide - 1 + maxRoomSlides + 1) % (maxRoomSlides + 1);
  updateRoomSlider();
  updateRoomNavigation();
  updateDebugInfo();
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

  // Update dots indicator if exists
  const dots = document.querySelectorAll(".room-dot");
  dots.forEach((dot, index) => {
    dot.classList.toggle("active", index === currentRoomSlide);
  });
}

// Initialize room slider
function initRoomSlider() {
  updateRoomsPerSlide();

  // Create navigation dots
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
      updateDebugInfo();
    });
    dotsContainer.appendChild(dot);
  }

  // Only show dots if there are multiple slides
  if (maxRoomSlides > 0) {
    roomSliderContainer.appendChild(dotsContainer);
  }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  initRoomSlider();
});

// Window resize handler
window.addEventListener("resize", () => {
  updateRoomsPerSlide();
});

// Touch/swipe support
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
      // Swipe left - next slide
      nextRoomSlide();
    } else {
      // Swipe right - previous slide
      prevRoomSlide();
    }
  }
}
