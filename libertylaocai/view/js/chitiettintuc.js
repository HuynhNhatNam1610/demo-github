let currentPromotionSlide = 0;
let promotionsPerSlide = 3;
let totalPromotionCards = 0;
let maxPromotionSlides = 0;

function updatePromotionsPerSlide() {
  const screenWidth = window.innerWidth;
  if (screenWidth <= 768) {
    promotionsPerSlide = 1;
  } else if (screenWidth <= 1024) {
    promotionsPerSlide = 2;
  } else {
    promotionsPerSlide = 3;
  }

  const promotionCards = document.querySelectorAll(".promotion-card");
  totalPromotionCards = promotionCards.length;
  maxPromotionSlides = Math.max(0, Math.ceil(totalPromotionCards / promotionsPerSlide) - 1);

  // Reset slide if current position is invalid
  if (currentPromotionSlide > maxPromotionSlides) {
    currentPromotionSlide = maxPromotionSlides;
  }

  updatePromotionSlider();
  updatePromotionNavigation();
}

function updatePromotionSlider() {
  const promotionsGrid = document.querySelector(".promotions-grid");
  const promotionCards = document.querySelectorAll(".promotion-card");

  if (!promotionsGrid || promotionCards.length === 0) return;

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
  promotionsGrid.style.setProperty("--card-width", `${cardWidth}%`);
  promotionsGrid.style.setProperty("--gap", `${gap}%`);

  const translateX = currentPromotionSlide * 100;
  promotionsGrid.style.transform = `translateX(-${translateX}%)`;
}

function nextPromotionSlide() {
  if (currentPromotionSlide < maxPromotionSlides) {
    currentPromotionSlide++;
    updatePromotionSlider();
    updatePromotionNavigation();
  }
}

function prevPromotionSlide() {
  if (currentPromotionSlide > 0) {
    currentPromotionSlide--;
    updatePromotionSlider();
    updatePromotionNavigation();
  }
}

function updatePromotionNavigation() {
  const prevBtn = document.querySelector(".promotion-nav-prev");
  const nextBtn = document.querySelector(".promotion-nav-next");

  if (prevBtn && nextBtn) {
    prevBtn.disabled = currentPromotionSlide === 0;
    nextBtn.disabled = currentPromotionSlide >= maxPromotionSlides;
  }

  // Update dots indicator
  const dots = document.querySelectorAll(".promotion-dot");
  dots.forEach((dot, index) => {
    dot.classList.toggle("active", index === currentPromotionSlide);
  });
}

// Initialize promotion slider
function initPromotionSlider() {
  updatePromotionsPerSlide();

  // Create navigation dots
  const promotionSliderContainer = document.querySelector(".related-promotions .content-wrapper");
  const existingDots = document.querySelector(".promotion-slider-dots");
  if (existingDots) {
    existingDots.remove();
  }

  const dotsContainer = document.createElement("div");
  dotsContainer.className = "promotion-slider-dots";

  for (let i = 0; i <= maxPromotionSlides; i++) {
    const dot = document.createElement("span");
    dot.className = "promotion-dot";
    if (i === 0) dot.classList.add("active");
    dot.addEventListener("click", () => {
      currentPromotionSlide = i;
      updatePromotionSlider();
      updatePromotionNavigation();
    });
    dotsContainer.appendChild(dot);
  }

  // Only show dots if there are multiple slides
  if (maxPromotionSlides > 0) {
    promotionSliderContainer.appendChild(dotsContainer);
  }

  // Add event listeners for navigation buttons
  const prevBtn = document.querySelector(".promotion-nav-prev");
  const nextBtn = document.querySelector(".promotion-nav-next");

  if (prevBtn && nextBtn) {
    prevBtn.addEventListener("click", prevPromotionSlide);
    nextBtn.addEventListener("click", nextPromotionSlide);
  }
}

// Touch/swipe support
let startX = 0;
let endX = 0;

document.querySelector(".promotions-grid-wrapper").addEventListener("touchstart", (e) => {
  startX = e.touches[0].clientX;
});

document.querySelector(".promotions-grid-wrapper").addEventListener("touchend", (e) => {
  endX = e.changedTouches[0].clientX;
  handlePromotionSwipe();
});

function handlePromotionSwipe() {
  const swipeThreshold = 50;
  const diff = startX - endX;

  if (Math.abs(diff) > swipeThreshold) {
    if (diff > 0) {
      nextPromotionSlide();
    } else {
      prevPromotionSlide();
    }
  }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", function() {
  initPromotionSlider();

  // Handle promotion item clicks
  $(document).on("click", ".promotion-card", function(e) {
    // Prevent form submission if clicking on the card but not the button
    if (!$(e.target).closest('.promotion-button').length) {
      e.preventDefault();
      const promotionId = $(this).data("promotion-id");
      
      if (promotionId) {
        $("#promotionIdInput").val(promotionId);
        $("#promotionForm").submit();
      }
    }
  });
});

// Window resize handler
window.addEventListener("resize", () => {
  updatePromotionsPerSlide();
});