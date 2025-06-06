document.addEventListener("DOMContentLoaded", () => {
  // FAQ Section: Toggle answers
  const faqItems = document.querySelectorAll(".faq-item");
  faqItems.forEach((item) => {
    const question = item.querySelector(".faq-question");
    question.addEventListener("click", () => {
      const isActive = item.classList.contains("active");
      faqItems.forEach((i) => {
        i.classList.remove("active");
        i.querySelector(".faq-answer").style.display = "none";
      });
      if (!isActive) {
        item.classList.add("active");
        item.querySelector(".faq-answer").style.display = "block";
      }
    });
  });

  // Booking Form: Basic validation
  const bookingForm = document.querySelector(".booking-form");
  if (bookingForm) {
    bookingForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const name = bookingForm.querySelector('input[name="name"]');
      const phone = bookingForm.querySelector('input[name="phone"]');
      const vehicle = bookingForm.querySelector('select[name="vehicle"]');
      const tripType = bookingForm.querySelector('select[name="trip-type"]');
      const pickupTime = bookingForm.querySelector('input[name="pickup-time"]');
      const passengers = bookingForm.querySelector('input[name="passengers"]');

      let isValid = true;
      let errorMessage = "";

      if (!name.value.trim()) {
        isValid = false;
        errorMessage += "Vui lòng nhập họ và tên.\n";
      }
      if (!phone.value.match(/^\d{10}$/)) {
        isValid = false;
        errorMessage += "Vui lòng nhập số điện thoại hợp lệ (10 chữ số).\n";
      }
      if (!vehicle.value) {
        isValid = false;
        errorMessage += "Vui lòng chọn loại xe.\n";
      }
      if (!tripType.value) {
        isValid = false;
        errorMessage += "Vui lòng chọn hình thức di chuyển.\n";
      }
      if (!pickupTime.value) {
        isValid = false;
        errorMessage += "Vui lòng chọn thời gian đón.\n";
      }
      if (!passengers.value || passengers.value < 1) {
        isValid = false;
        errorMessage += "Vui lòng nhập số hành khách hợp lệ.\n";
      }

      if (isValid) {
        bookingForm.submit(); // Gửi form để PHP xử lý
      } else {
        alert(errorMessage);
      }
    });
  }

  // Smooth Scroll for CTA Buttons
  const ctaButtons = document.querySelectorAll(".cta-btn, .book-btn");
  ctaButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const targetId = btn.getAttribute("href") || "#booking";
      if (targetId && targetId.startsWith("#")) {
        e.preventDefault();
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          window.scrollTo({
            top: targetElement.offsetTop - 50,
            behavior: "smooth",
          });
        }
      }
    });
  });

  // Hero Buttons Animation on Hover
  const heroButtons = document.querySelectorAll(".cta-btn");
  heroButtons.forEach((btn) => {
    btn.addEventListener("mouseenter", () => {
      btn.style.transform = "scale(1.05)";
    });
    btn.addEventListener("mouseleave", () => {
      btn.style.transform = "scale(1)";
    });
  });
});

/**
 * Infinite Vehicle Carousel
 * Carousel có thể cuộn vô hạn với hiệu ứng mượt mà
 *
 * @author Liberty Lào Cai
 * @version 1.0.0
 */
class InfiniteVehicleCarousel {
  constructor() {
    this.wrapper = document.getElementById("vehicleWrapper");
    this.prevBtn = document.getElementById("prevBtn");
    this.nextBtn = document.getElementById("nextBtn");
    this.indicators = document.getElementById("indicators");

    this.currentIndex = 0;
    this.slides = document.querySelectorAll(".vehicle-slide");
    this.totalSlides = this.slides.length;
    this.slidesToShow = this.getSlidesToShow();
    this.isTransitioning = false;

    this.init();
    this.setupEventListeners();
    this.setupResizeListener();
  }

  /**
   * Khởi tạo carousel
   */
  init() {
    this.createClones();
    this.createIndicators();
    this.setupInitialPosition();
    this.updateIndicators();
  }

  /**
   * Tính toán số slides hiển thị dựa trên kích thước màn hình
   */
  getSlidesToShow() {
    const width = window.innerWidth;
    if (width <= 768) return 1;
    if (width <= 1024) return 2;
    return 3;
  }

  /**
   * Tạo các slide clone để tạo hiệu ứng infinite loop
   */
  createClones() {
    // Clone slides for infinite loop
    const firstClones = [];
    const lastClones = [];

    // Create clones of first slides (for end)
    for (let i = 0; i < this.slidesToShow; i++) {
      const clone = this.slides[i].cloneNode(true);
      clone.classList.add("clone");
      firstClones.push(clone);
    }

    // Create clones of last slides (for beginning)
    for (
      let i = this.totalSlides - this.slidesToShow;
      i < this.totalSlides;
      i++
    ) {
      const clone = this.slides[i].cloneNode(true);
      clone.classList.add("clone");
      lastClones.push(clone);
    }

    // Add clones to wrapper
    lastClones.forEach((clone) => {
      this.wrapper.insertBefore(clone, this.wrapper.firstChild);
    });

    firstClones.forEach((clone) => {
      this.wrapper.appendChild(clone);
    });

    // Update slides list to include clones
    this.allSlides = document.querySelectorAll(".vehicle-slide");
    this.totalSlidesWithClones = this.allSlides.length;
  }

  /**
   * Thiết lập vị trí ban đầu của carousel
   */
  setupInitialPosition() {
    // Start from the first real slide (after cloned slides)
    this.currentIndex = this.slidesToShow;
    this.updateCarouselPosition(false);
  }

  /**
   * Tạo các indicators (chấm tròn) để điều hướng
   */
  createIndicators() {
    this.indicators.innerHTML = "";
    const indicatorCount = this.totalSlides;

    for (let i = 0; i < indicatorCount; i++) {
      const indicator = document.createElement("div");
      indicator.className = "indicator";
      indicator.addEventListener("click", () => this.goToSlide(i));
      this.indicators.appendChild(indicator);
    }
  }

  /**
   * Cập nhật vị trí carousel
   * @param {boolean} withTransition - Có sử dụng transition không
   */
  updateCarouselPosition(withTransition = true) {
    if (withTransition) {
      this.wrapper.style.transition =
        "transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)";
    } else {
      this.wrapper.style.transition = "none";
    }

    const slideWidth = 100 / this.slidesToShow;
    const translateX = -(this.currentIndex * slideWidth);
    this.wrapper.style.transform = `translateX(${translateX}%)`;
  }

  /**
   * Cập nhật trạng thái active của indicators
   */
  updateIndicators() {
    const realIndex = this.getRealIndex();
    document.querySelectorAll(".indicator").forEach((indicator, index) => {
      indicator.classList.toggle("active", index === realIndex);
    });
  }

  /**
   * Lấy index thực của slide hiện tại (không tính clone)
   */
  getRealIndex() {
    let realIndex = this.currentIndex - this.slidesToShow;
    if (realIndex < 0) realIndex = this.totalSlides + realIndex;
    if (realIndex >= this.totalSlides) realIndex = realIndex - this.totalSlides;
    return realIndex;
  }

  /**
   * Chuyển đến slide cụ thể
   * @param {number} targetIndex - Index của slide đích
   */
  goToSlide(targetIndex) {
    if (this.isTransitioning) return;

    this.currentIndex = targetIndex + this.slidesToShow;
    this.updateCarouselPosition(true);
    this.updateIndicators();
  }

  /**
   * Chuyển đến slide tiếp theo
   */
  nextSlide() {
    if (this.isTransitioning) return;

    this.isTransitioning = true;
    this.currentIndex++;
    this.updateCarouselPosition(true);
    this.updateIndicators();

    // Check if we need to reset position for infinite loop
    setTimeout(() => {
      if (this.currentIndex >= this.totalSlidesWithClones - this.slidesToShow) {
        this.currentIndex = this.slidesToShow;
        this.updateCarouselPosition(false);
      }
      this.isTransitioning = false;
    }, 500);
  }

  /**
   * Chuyển đến slide trước đó
   */
  prevSlide() {
    if (this.isTransitioning) return;

    this.isTransitioning = true;
    this.currentIndex--;
    this.updateCarouselPosition(true);
    this.updateIndicators();

    // Check if we need to reset position for infinite loop
    setTimeout(() => {
      if (this.currentIndex < this.slidesToShow) {
        this.currentIndex = this.totalSlidesWithClones - 2 * this.slidesToShow;
        this.updateCarouselPosition(false);
      }
      this.isTransitioning = false;
    }, 500);
  }

  /**
   * Thiết lập các event listeners
   */
  setupEventListeners() {
    // Navigation buttons
    this.nextBtn.addEventListener("click", () => this.nextSlide());
    this.prevBtn.addEventListener("click", () => this.prevSlide());

    // Keyboard navigation
    document.addEventListener("keydown", (e) => {
      if (e.key === "ArrowLeft") this.prevSlide();
      if (e.key === "ArrowRight") this.nextSlide();
    });

    // Touch/swipe support
    this.setupTouchEvents();
  }

  /**
   * Thiết lập hỗ trợ touch/swipe cho mobile
   */
  setupTouchEvents() {
    let startX = 0;
    let endX = 0;
    let startY = 0;
    let endY = 0;

    this.wrapper.addEventListener(
      "touchstart",
      (e) => {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
      },
      { passive: true }
    );

    this.wrapper.addEventListener(
      "touchend",
      (e) => {
        endX = e.changedTouches[0].clientX;
        endY = e.changedTouches[0].clientY;

        const diffX = startX - endX;
        const diffY = startY - endY;

        // Chỉ xử lý swipe ngang nếu movement ngang > movement dọc
        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
          if (diffX > 0) {
            this.nextSlide();
          } else {
            this.prevSlide();
          }
        }
      },
      { passive: true }
    );
  }

  /**
   * Thiết lập listener cho resize window
   */
  setupResizeListener() {
    let resizeTimeout;

    window.addEventListener("resize", () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(() => {
        const newSlidesToShow = this.getSlidesToShow();
        if (newSlidesToShow !== this.slidesToShow) {
          this.slidesToShow = newSlidesToShow;
          this.recreateCarousel();
        }
      }, 250);
    });
  }

  /**
   * Tái tạo carousel khi thay đổi số slides hiển thị
   */
  recreateCarousel() {
    // Remove all clones
    document.querySelectorAll(".vehicle-slide.clone").forEach((clone) => {
      clone.remove();
    });

    // Reinitialize
    this.slides = document.querySelectorAll(".vehicle-slide:not(.clone)");
    this.currentIndex = 0;
    this.isTransitioning = false;
    this.init();
  }

  /**
   * Destroy carousel (cleanup)
   */
  destroy() {
    // Remove event listeners
    this.nextBtn.removeEventListener("click", this.nextSlide);
    this.prevBtn.removeEventListener("click", this.prevSlide);

    // Remove clones
    document.querySelectorAll(".vehicle-slide.clone").forEach((clone) => {
      clone.remove();
    });

    // Reset styles
    this.wrapper.style.transform = "";
    this.wrapper.style.transition = "";
  }
}

/**
 * Auto-play functionality
 */
class CarouselAutoPlay {
  constructor(carousel, interval = 4000) {
    this.carousel = carousel;
    this.interval = interval;
    this.autoPlayInterval = null;
    this.isPlaying = false;

    this.setupAutoPlay();
  }

  /**
   * Thiết lập auto-play
   */
  setupAutoPlay() {
    const carouselElement = document.querySelector(".vehicle-carousel");

    if (carouselElement) {
      carouselElement.addEventListener("mouseenter", () => this.stop());
      carouselElement.addEventListener("mouseleave", () => this.start());

      // Start auto-play
      this.start();
    }
  }

  /**
   * Bắt đầu auto-play
   */
  start() {
    if (this.isPlaying) return;

    this.autoPlayInterval = setInterval(() => {
      if (this.carousel && !this.carousel.isTransitioning) {
        this.carousel.nextSlide();
      }
    }, this.interval);

    this.isPlaying = true;
  }

  /**
   * Dừng auto-play
   */
  stop() {
    if (this.autoPlayInterval) {
      clearInterval(this.autoPlayInterval);
      this.autoPlayInterval = null;
    }
    this.isPlaying = false;
  }

  /**
   * Thay đổi interval
   */
  setInterval(newInterval) {
    this.interval = newInterval;
    if (this.isPlaying) {
      this.stop();
      this.start();
    }
  }
}

/**
 * Vehicle booking function
 */
function bookVehicle(vehicleType) {
  // Cuộn đến phần đặt xe ngay
  const bookingSection = document.querySelector("#booking");
  if (bookingSection) {
    window.scrollTo({
      top: bookingSection.offsetTop - 50,
      behavior: "smooth",
    });
    // Tùy chọn: Tự động điền loại xe vào form
    const vehicleSelect = document.querySelector('select[name="vehicle"]');
    if (vehicleSelect) {
      const vehicleOption = Array.from(vehicleSelect.options).find(
        (option) =>
          option.value.toLowerCase().replace(/\s/g, "") ===
          vehicleType.toLowerCase().replace(/\s/g, "")
      );
      if (vehicleOption) {
        vehicleSelect.value = vehicleOption.value;
      }
    }
  }
}

/**
 * Initialize carousel when DOM is loaded
 */
let vehicleCarousel;
let autoPlay;

document.addEventListener("DOMContentLoaded", () => {
  // Kiểm tra xem các elements cần thiết có tồn tại không
  const requiredElements = [
    "vehicleWrapper",
    "prevBtn",
    "nextBtn",
    "indicators",
  ];

  const allElementsExist = requiredElements.every(
    (id) => document.getElementById(id) !== null
  );

  if (allElementsExist) {
    try {
      // Khởi tạo carousel
      vehicleCarousel = new InfiniteVehicleCarousel();

      // Khởi tạo auto-play (interval: 4000ms = 4 giây)
      autoPlay = new CarouselAutoPlay(vehicleCarousel, 4000);

      console.log("✅ Vehicle Carousel initialized successfully");
    } catch (error) {
      console.error("❌ Error initializing carousel:", error);
    }
  } else {
    console.warn("⚠️ Required carousel elements not found");
  }
});

/**
 * Cleanup khi trang được unload
 */
window.addEventListener("beforeunload", () => {
  if (autoPlay) {
    autoPlay.stop();
  }

  if (vehicleCarousel) {
    vehicleCarousel.destroy();
  }
});

/**
 * Export cho sử dụng module (nếu cần)
 */
if (typeof module !== "undefined" && module.exports) {
  module.exports = {
    InfiniteVehicleCarousel,
    CarouselAutoPlay,
    bookVehicle,
  };
}
