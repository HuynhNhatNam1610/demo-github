// Slideshow for right-panel
function initSlideshow() {
  const slides = document.querySelectorAll(".slideshow-slide");
  let currentSlide = 0;
  const totalSlides = slides.length;

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle("active", i === index);
    });
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
  }

  showSlide(currentSlide);
  setInterval(nextSlide, 3000);
}

// Room carousel functionality
let carousels = {};

function initCarousels() {
  Object.keys(carousels).forEach((roomType) => {
    createDots(roomType);
    updateCarousel(roomType);
  });
}

function createDots(roomType) {
  const dotsContainer = document.getElementById(`${roomType}-dots`);
  if (!dotsContainer) return;
  dotsContainer.innerHTML = "";
  const totalSlides = carousels[roomType].totalSlides;

  for (let i = 0; i < totalSlides; i++) {
    const dot = document.createElement("div");
    dot.className = "carousel-dot";
    dot.onclick = () => goToSlide(roomType, i);
    dotsContainer.appendChild(dot);
  }
}

function updateCarousel(roomType) {
  const slides = document.getElementById(`${roomType}-slides`);
  const dots = document.querySelectorAll(`#${roomType}-dots .carousel-dot`);
  const counter = document.getElementById(`${roomType}-counter`);
  if (!slides || !counter) return;

  const currentSlide = carousels[roomType].currentSlide;
  const totalSlides = carousels[roomType].totalSlides;

  slides.style.transform = `translateX(-${currentSlide * 100}%)`;
  dots.forEach((dot, index) => {
    dot.classList.toggle("active", index === currentSlide);
  });
  counter.textContent = `${currentSlide + 1} / ${totalSlides}`;
}

function changeSlide(roomType, direction) {
  const carousel = carousels[roomType];
  if (!carousel) return;
  carousel.currentSlide += direction;

  if (carousel.currentSlide < 0) {
    carousel.currentSlide = carousel.totalSlides - 1;
  } else if (carousel.currentSlide >= carousel.totalSlides) {
    carousel.currentSlide = 0;
  }

  updateCarousel(roomType);
}

function goToSlide(roomType, slideIndex) {
  const carousel = carousels[roomType];
  if (!carousel) return;
  carousel.currentSlide = slideIndex;
  updateCarousel(roomType);
}

function startAutoSlide() {
  setInterval(() => {
    Object.keys(carousels).forEach((roomType) => {
      changeSlide(roomType, 1);
    });
  }, 5000);
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

  const roomId = carousel.querySelector(".carousel-slides").id.split("-")[1];
  const roomType = `room-${roomId}`;
  const swipeThreshold = 50;

  if (touchEndX < touchStartX - swipeThreshold) {
    changeSlide(roomType, 1);
  }
  if (touchEndX > touchStartX + swipeThreshold) {
    changeSlide(roomType, -1);
  }
}

// Service Carousel
let serviceCurrentSlide = 0;
let serviceAutoSlideInterval = null;

function updateServiceCarousel() {
  const slidesContainer = document.getElementById("services-slides");
  const totalSlides = slidesContainer.querySelectorAll(".service-slide").length;
  const isMobile = window.innerWidth <= 480;
  const visibleSlides = isMobile ? 1 : 2;
  const maxIndex = totalSlides - visibleSlides;

  if (serviceCurrentSlide < 0) {
    serviceCurrentSlide = maxIndex;
  } else if (serviceCurrentSlide > maxIndex) {
    serviceCurrentSlide = 0;
  }

  const translateX = -(serviceCurrentSlide * (100 / visibleSlides));
  slidesContainer.style.transform = `translateX(${translateX}%)`;
}

function changeServiceSlide(direction) {
  serviceCurrentSlide += direction;
  updateServiceCarousel();
  pauseServiceAutoSlide();
  setTimeout(startServiceAutoSlide, 10000);
}

function startServiceAutoSlide() {
  if (serviceAutoSlideInterval) {
    clearInterval(serviceAutoSlideInterval);
  }
  serviceAutoSlideInterval = setInterval(() => {
    changeServiceSlide(1);
  }, 5000);
}

function pauseServiceAutoSlide() {
  if (serviceAutoSlideInterval) {
    clearInterval(serviceAutoSlideInterval);
    serviceAutoSlideInterval = null;
  }
}

// Customer Reviews Carousel
let reviewCurrentSlide = 0;

function createReviewDots() {
  const dotsContainer = document.querySelector(".reviews-dots");
  if (!dotsContainer) return;
  dotsContainer.innerHTML = "";
  const totalSlides = document.querySelectorAll(".review-slide").length;
  const visibleSlides =
    window.innerWidth <= 480 ? 1 : window.innerWidth <= 768 ? 2 : 3;
  const dotCount = Math.ceil(totalSlides / visibleSlides);

  for (let i = 0; i < dotCount; i++) {
    const dot = document.createElement("div");
    dot.className = "review-dot";
    dot.onclick = () => goToReviewSlide(i);
    dotsContainer.appendChild(dot);
  }
}

function updateReviewCarousel() {
  const slidesContainer = document.getElementById("reviews-slides");
  const totalSlides = slidesContainer.querySelectorAll(".review-slide").length;
  const isMobile = window.innerWidth <= 480;
  const isTablet = window.innerWidth <= 768;
  const visibleSlides = isMobile ? 1 : isTablet ? 2 : 3;
  const maxIndex = totalSlides - visibleSlides;

  if (reviewCurrentSlide < 0) {
    reviewCurrentSlide = maxIndex;
  } else if (reviewCurrentSlide > maxIndex) {
    reviewCurrentSlide = 0;
  }

  const translateX = -(reviewCurrentSlide * (100 / visibleSlides));
  slidesContainer.style.transform = `translateX(${translateX}%)`;

  const dots = document.querySelectorAll(".review-dot");
  dots.forEach((dot, index) => {
    dot.classList.toggle("active", index === reviewCurrentSlide);
  });
}

function changeReviewSlide(direction) {
  reviewCurrentSlide += direction;
  updateReviewCarousel();
}

function goToReviewSlide(slideIndex) {
  reviewCurrentSlide = slideIndex;
  updateReviewCarousel();
}

document.addEventListener("DOMContentLoaded", () => {
  initCarousels();
  startAutoSlide();
  initSlideshow();
  updateServiceCarousel();
  window.addEventListener("resize", updateServiceCarousel);
  startServiceAutoSlide();
  createReviewDots();
  updateReviewCarousel();
  window.addEventListener("resize", () => {
    createReviewDots();
    updateReviewCarousel();
  });
});
