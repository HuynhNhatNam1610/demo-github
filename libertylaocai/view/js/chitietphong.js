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
function initStarRating() {
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
}

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

// Touch/swipe support for room slider
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

// Alert functions
function closeAlert(alertId) {
  const alert = document.getElementById(alertId);
  if (alert) {
    alert.style.animation = 'slideOut 0.3s ease-out';
    setTimeout(() => {
      alert.remove();
    }, 300);
  }
}

// Form validation
function validateBookingForm() {
  const form = document.querySelector('.booking-form');
  const inputs = form.querySelectorAll('input[required], select[required]');
  let isValid = true;
  
  inputs.forEach(input => {
    const formGroup = input.closest('.form-group');
    const existingError = formGroup.querySelector('.validation-message');
    
    if (existingError) {
      existingError.remove();
    }
    
    formGroup.classList.remove('has-error');
    
    if (!input.value.trim()) {
      isValid = false;
      formGroup.classList.add('has-error');
      
      const errorMsg = document.createElement('span');
      errorMsg.className = 'validation-message';
      errorMsg.textContent = 'Trường này không được để trống';
      input.parentNode.appendChild(errorMsg);
    }
    // Trong hàm validateBookingForm()
const adultsInput = document.getElementById('adults');
const childrenInput = document.getElementById('children');

if (adultsInput.value < 1) {
    isValid = false;
    const formGroup = adultsInput.closest('.form-group');
    formGroup.classList.add('has-error');
    
    const errorMsg = document.createElement('span');
    errorMsg.className = 'validation-message';
    errorMsg.textContent = 'Số người lớn phải từ 1 trở lên';
    adultsInput.parentNode.appendChild(errorMsg);
}

if (childrenInput.value < 0) {
    isValid = false;
    const formGroup = childrenInput.closest('.form-group');
    formGroup.classList.add('has-error');
    
    const errorMsg = document.createElement('span');
    errorMsg.className = 'validation-message';
    errorMsg.textContent = 'Số trẻ em không được âm';
    childrenInput.parentNode.appendChild(errorMsg);
}
  });
  
  // Validate email
  const emailInput = document.getElementById('email');
  if (emailInput.value && !isValidEmail(emailInput.value)) {
    isValid = false;
    const formGroup = emailInput.closest('.form-group');
    formGroup.classList.add('has-error');
    
    const errorMsg = document.createElement('span');
    errorMsg.className = 'validation-message';
    errorMsg.textContent = 'Email không hợp lệ';
    emailInput.parentNode.appendChild(errorMsg);
  }
  
  // Validate dates
  const checkinInput = document.getElementById('checkin');
  const checkoutInput = document.getElementById('checkout');
  
  if (checkinInput.value && checkoutInput.value) {
    const checkinDate = new Date(checkinInput.value);
    const checkoutDate = new Date(checkoutInput.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (checkinDate < today) {
      isValid = false;
      const formGroup = checkinInput.closest('.form-group');
      formGroup.classList.add('has-error');
      
      const errorMsg = document.createElement('span');
      errorMsg.className = 'validation-message';
      errorMsg.textContent = 'Ngày nhận phòng không thể là quá khứ';
      checkinInput.parentNode.appendChild(errorMsg);
    }
    
    if (checkoutDate <= checkinDate) {
      isValid = false;
      const formGroup = checkoutInput.closest('.form-group');
      formGroup.classList.add('has-error');
      
      const errorMsg = document.createElement('span');
      errorMsg.className = 'validation-message';
      errorMsg.textContent = 'Ngày trả phòng phải sau ngày nhận phòng';
      checkoutInput.parentNode.appendChild(errorMsg);
    }
  }
  
  return isValid;
}

function isValidEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

function calculateNights() {
  const checkin = document.getElementById('checkin').value;
  const checkout = document.getElementById('checkout').value;
  const adults = parseInt(document.getElementById('adults').value) || 0;
  const children = parseInt(document.getElementById('children').value) || 0;
  
  if (checkin && checkout) {
    const checkinDate = new Date(checkin);
    const checkoutDate = new Date(checkout);
    const diffTime = Math.abs(checkoutDate - checkinDate);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    document.getElementById('nights-count').textContent = diffDays + ' đêm';
    
    // Calculate children fee (100,000 VND per child per night)
    const childrenFee = children * 100000 * diffDays;
    document.getElementById('children-fee').textContent = 
      new Intl.NumberFormat('vi-VN').format(childrenFee) + ' VNĐ';
    
    // Calculate total
    const total = (roomPrice * diffDays) + childrenFee;
    document.getElementById('total-price').textContent = 
      new Intl.NumberFormat('vi-VN').format(total) + ' VNĐ';
  }
}

// Thêm event listener cho input người lớn và trẻ em
document.getElementById('adults').addEventListener('change', calculateNights);
document.getElementById('children').addEventListener('change', calculateNights);

// Initialize page
document.addEventListener("DOMContentLoaded", function () {
  setMinDate();
  initStarRating();
  initRoomSlider();
  animateOnScroll();

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


const alerts = document.querySelectorAll('.alert');
alerts.forEach(alert => {
  setTimeout(() => {
    if (document.body.contains(alert)) {
      closeAlert(alert.id);
    }
  }, 3000);
});

  // Add slide out animation
  const style = document.createElement('style');
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

  // Add form validation on submit
  const bookingForm = document.querySelector('.booking-form');
  if (bookingForm) {
    bookingForm.addEventListener('submit', function(e) {
      if (!validateBookingForm()) {
        e.preventDefault();
        return false;
      }
      
      // Show loading state
      const submitBtn = this.querySelector('.submit-booking-btn');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<span class="loading-spinner"></span>Đang xử lý...';
      submitBtn.disabled = true;
      
      // Re-enable if there's an error (page reload will handle success)
      setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      }, 10000);
    });
  }
});

// Window resize handler
window.addEventListener("resize", () => {
  updateRoomsPerSlide();
});