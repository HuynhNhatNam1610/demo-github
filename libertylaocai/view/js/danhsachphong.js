// Carousel functionality - Improved version
const carousels = {};
let autoSlideInterval;

// Initialize carousel for a specific room
function initCarousel(roomId) {
  const slidesContainer = document.getElementById(`${roomId}-slides`);
  if (!slidesContainer) return;

  const slides = slidesContainer.querySelectorAll(".carousel-slide");
  const totalSlides = slides.length;

  if (totalSlides === 0) return;

  // Initialize carousel data
  carousels[roomId] = {
    currentSlide: 0,
    totalSlides: totalSlides,
  };

  createDots(roomId);
  updateCarousel(roomId);
}

// Create dots for navigation
function createDots(roomId) {
  const dotsContainer = document.getElementById(`${roomId}-dots`);
  if (!dotsContainer) return;

  const totalSlides = carousels[roomId].totalSlides;

  // Clear existing dots
  dotsContainer.innerHTML = "";

  for (let i = 0; i < totalSlides; i++) {
    const dot = document.createElement("div");
    dot.className = "carousel-dot";
    dot.onclick = () => {
      goToSlide(roomId, i);
      pauseAutoSlide();
      resumeAutoSlide();
    };
    dotsContainer.appendChild(dot);
  }
}

// Enhanced update carousel with smoother transitions
function updateCarousel(roomId) {
  if (!carousels[roomId]) return;

  const slides = document.getElementById(`${roomId}-slides`);
  const dots = document.querySelectorAll(`#${roomId}-dots .carousel-dot`);
  const counter = document.getElementById(`${roomId}-counter`);

  if (!slides) return;

  const currentSlide = carousels[roomId].currentSlide;
  const totalSlides = carousels[roomId].totalSlides;

  // Add smooth transition if not already present
  if (!slides.style.transition) {
    slides.style.transition = "transform 0.5s ease-in-out";
  }

  // Move slides
  slides.style.transform = `translateX(-${currentSlide * 100}%)`;

  // Update dots
  dots.forEach((dot, index) => {
    dot.classList.toggle("active", index === currentSlide);
  });

  // Update counter
  if (counter) {
    counter.textContent = `${currentSlide + 1} / ${totalSlides}`;
  }
}

// Enhanced change slide function
function changeSlide(roomId, direction) {
  if (!carousels[roomId]) return;

  const carousel = carousels[roomId];
  carousel.currentSlide += direction;

  if (carousel.currentSlide < 0) {
    carousel.currentSlide = carousel.totalSlides - 1;
  } else if (carousel.currentSlide >= carousel.totalSlides) {
    carousel.currentSlide = 0;
  }

  updateCarousel(roomId);
}

// Go to specific slide
function goToSlide(roomId, slideIndex) {
  if (!carousels[roomId]) return;

  carousels[roomId].currentSlide = slideIndex;
  updateCarousel(roomId);
}

// Auto-slide functionality - Improved version
function startAutoSlide() {
  // Clear any existing interval
  if (autoSlideInterval) {
    clearInterval(autoSlideInterval);
  }

  // Tăng thời gian từ 5 giây lên 8 giây để giảm lag
  autoSlideInterval = setInterval(() => {
    Object.keys(carousels).forEach((roomId, index) => {
      // Stagger the slide changes to avoid all carousels changing at once
      setTimeout(() => {
        changeSlide(roomId, 1);
      }, index * 2000); // Delay 200ms between each carousel
    });
  }, 10000); // Change slide every 8 seconds instead of 5
}

// Stop auto-slide when user interacts with carousel
function pauseAutoSlide() {
  if (autoSlideInterval) {
    clearInterval(autoSlideInterval);
  }
}

// Resume auto-slide after user interaction
function resumeAutoSlide() {
  setTimeout(() => {
    startAutoSlide();
  }, 3000); // Resume after 3 seconds of no interaction
}

// Price filter functionality
const minRange = document.getElementById("minRange");
const maxRange = document.getElementById("maxRange");
const minPrice = document.getElementById("minPrice");
const maxPrice = document.getElementById("maxPrice");
const priceDisplay = document.getElementById("priceDisplay");

function formatPrice(price) {
  return new Intl.NumberFormat("vi-VN").format(price) + " đ";
}

function updatePriceDisplay() {
  if (!minRange || !maxRange) return;

  const min = parseInt(minRange.value);
  const max = parseInt(maxRange.value);

  if (min > max) {
    minRange.value = max;
    maxRange.value = min;
  }

  if (minPrice) minPrice.value = formatPrice(minRange.value);
  if (maxPrice) maxPrice.value = formatPrice(maxRange.value);
  if (priceDisplay)
    priceDisplay.textContent = `${formatPrice(minRange.value)} - ${formatPrice(
      maxRange.value
    )}`;

  filterRooms();
}

// Add event listeners for price range when elements exist
document.addEventListener("DOMContentLoaded", function () {
  const minRange = document.getElementById("minRange");
  const maxRange = document.getElementById("maxRange");

  if (minRange) minRange.addEventListener("input", updatePriceDisplay);
  if (maxRange) maxRange.addEventListener("input", updatePriceDisplay);
});

// Rating filter functionality
function toggleRating(rating) {
  const checkbox = document.getElementById(`rating${rating}`);
  if (checkbox) {
    checkbox.checked = !checkbox.checked;
    filterRooms();
  }
}

// Room category filter functionality
function filterByCategory() {
  const checkboxes = document.querySelectorAll(
    'input[name="room_category"]:checked'
  );
  const selectedCategories = Array.from(checkboxes).map((cb) => cb.value);

  const roomBlocks = document.querySelectorAll(".room-block");
  let visibleCount = 0;

  roomBlocks.forEach((room) => {
    const roomId = room.dataset.roomId;
    let show = true;

    // Category filter
    if (selectedCategories.length > 0 && !selectedCategories.includes(roomId)) {
      show = false;
    }

    room.style.display = show ? "flex" : "none";
    if (show) visibleCount++;
  });

  const resultsCount = document.querySelector(".results-count");
  if (resultsCount) {
    const showingText = texts[languageId].showing_results || "Showing";
    const resultsText = texts[languageId].results || "results";
    resultsCount.textContent = `${showingText} ${visibleCount} ${resultsText}`;
  }

  // Also apply price and rating filters
  filterRooms();
}

// Filter rooms based on selected criteria
function filterRooms() {
  const minRange = document.getElementById("minRange");
  const maxRange = document.getElementById("maxRange");

  if (!minRange || !maxRange) return;

  const minPriceValue = parseInt(minRange.value);
  const maxPriceValue = parseInt(maxRange.value);
  const selectedRatings = [];

  // Get selected ratings
  for (let i = 0; i <= 5; i++) {
    const checkbox = document.getElementById(`rating${i}`);
    if (checkbox && checkbox.checked) {
      selectedRatings.push(i);
    }
  }

  // Get selected room categories
  const categoryCheckboxes = document.querySelectorAll(
    'input[name="room_category"]:checked'
  );
  const selectedCategories = Array.from(categoryCheckboxes).map(
    (cb) => cb.value
  );

  const roomBlocks = document.querySelectorAll(".room-block");
  let visibleCount = 0;

  roomBlocks.forEach((room) => {
    const price = parseInt(room.dataset.price);
    const rating = parseInt(room.dataset.rating);
    const available = parseInt(room.dataset.available) || 1;
    const roomId = room.dataset.roomId;

    let show = true;

    // Price filter
    if (price < minPriceValue || price > maxPriceValue) {
      show = false;
    }

    // Rating filter (only if ratings are selected)
    if (selectedRatings.length > 0 && !selectedRatings.includes(rating)) {
      show = false;
    }

    // Category filter
    if (selectedCategories.length > 0 && !selectedCategories.includes(roomId)) {
      show = false;
    }

    // Available rooms filter
    if (available === 0) {
      show = false;
    }

    room.style.display = show ? "flex" : "none";
    if (show) visibleCount++;
  });

  // Update results count
  const resultsCount = document.querySelector(".results-count");
  if (resultsCount) {
    const showingText = texts[languageId].showing_results || "Showing";
    const resultsText = texts[languageId].results || "results";
    resultsCount.textContent = `${showingText} ${visibleCount} ${resultsText}`;
  }
}

// Sort rooms functionality
function sortRooms() {
  const sortSelect = document.getElementById("sortSelect");
  if (!sortSelect) return;

  const sortBy = sortSelect.value;
  const roomsContainer = document.querySelector(".room-highlight-section");
  if (!roomsContainer) return;

  const rooms = Array.from(document.querySelectorAll(".room-block"));

  rooms.sort((a, b) => {
    switch (sortBy) {
      case "price-low":
        return parseInt(a.dataset.price) - parseInt(b.dataset.price);
      case "price-high":
        return parseInt(b.dataset.price) - parseInt(a.dataset.price);
      default:
        return 0;
    }
  });

  // Re-append sorted rooms
  rooms.forEach((room) => roomsContainer.appendChild(room));
}

// Clear all filters
function clearAllFilters() {
  // Reset price range to default (min to max)
  const minRange = document.getElementById("minRange");
  const maxRange = document.getElementById("maxRange");

  if (minRange && maxRange) {
    minRange.value = minRange.min;
    maxRange.value = maxRange.max;
    updatePriceDisplay();
  }

  // Uncheck all rating checkboxes
  for (let i = 0; i <= 5; i++) {
    const checkbox = document.getElementById(`rating${i}`);
    if (checkbox) {
      checkbox.checked = false;
    }
  }

  // Uncheck all room category checkboxes
  const categoryCheckboxes = document.querySelectorAll(
    'input[name="room_category"]'
  );
  categoryCheckboxes.forEach((checkbox) => {
    checkbox.checked = false;
  });

  // Reset sort
  const sortSelect = document.getElementById("sortSelect");
  if (sortSelect) {
    sortSelect.value = "price-low";
  }

  // Show all rooms
  filterRooms();
}

// Touch/swipe support for mobile
let touchStartX = 0;
let touchEndX = 0;

document.addEventListener("touchstart", (e) => {
  touchStartX = e.changedTouches[0].screenX;
});

document.addEventListener("touchend", (e) => {
  touchEndX = e.changedTouches[0].screenX;
  handleSwipe(e.target);
});

function handleSwipe(target) {
  const carousel = target.closest(".room-image-box");
  if (!carousel) return;

  const roomBlock = carousel.closest(".room-block");
  if (!roomBlock) return;

  const roomId = getRoomIdFromBlock(roomBlock);
  if (!roomId) return;

  const swipeThreshold = 50;

  if (touchEndX < touchStartX - swipeThreshold) {
    // Swipe left - next slide
    changeSlide(roomId, 1);
    pauseAutoSlide();
    resumeAutoSlide();
  }
  if (touchEndX > touchStartX + swipeThreshold) {
    // Swipe right - previous slide
    changeSlide(roomId, -1);
    pauseAutoSlide();
    resumeAutoSlide();
  }
}

function getRoomIdFromBlock(roomBlock) {
  const slidesContainer = roomBlock.querySelector('[id$="-slides"]');
  if (slidesContainer) {
    return slidesContainer.id.replace("-slides", "");
  }
  return null;
}

// Initialize default price range to show all rooms
function initializePriceRange() {
  const minRange = document.getElementById("minRange");
  const maxRange = document.getElementById("maxRange");

  if (minRange && maxRange) {
    // Set default values to show full range
    minRange.value = minRange.min;
    maxRange.value = maxRange.max;
    updatePriceDisplay();
  }
}

// Initialize all carousels on page
function initializeAllCarousels() {
  // Find all carousel containers and initialize them
  const carouselContainers = document.querySelectorAll('[id$="-slides"]');
  carouselContainers.forEach((container) => {
    const roomId = container.id.replace("-slides", "");
    initCarousel(roomId);
  });
}

// Add event listeners for carousel interaction
function addCarouselEventListeners() {
  // Pause auto-slide when user hovers over carousel
  document.querySelectorAll(".carousel-container").forEach((container) => {
    container.addEventListener("mouseenter", pauseAutoSlide);
    container.addEventListener("mouseleave", resumeAutoSlide);
  });

  // Pause auto-slide when user clicks navigation buttons
  document.querySelectorAll(".carousel-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      pauseAutoSlide();
      resumeAutoSlide();
    });
  });

  // Add event listeners for room category checkboxes
  document
    .querySelectorAll('input[name="room_category"]')
    .forEach((checkbox) => {
      checkbox.addEventListener("change", filterRooms);
    });
}

// Initialize when page loads
document.addEventListener("DOMContentLoaded", () => {
  initializeAllCarousels();
  initializePriceRange();
  addCarouselEventListeners();

  // Start auto-slide after a short delay to ensure all carousels are initialized
  setTimeout(() => {
    startAutoSlide();
  }, 1000);
});
