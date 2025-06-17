class InfinitePromotionCarousel {
  constructor() {
    this.wrapper = document.querySelector(".promotions-grid");
    this.prevBtn = document.querySelector(".promotion-nav-prev");
    this.nextBtn = document.querySelector(".promotion-nav-next");
    this.slides = document.querySelectorAll(".promotion-card:not(.clone)");
    this.totalSlides = this.slides.length;
    this.slidesToShow = this.getSlidesToShow();
    this.currentIndex = 0;
    this.isTransitioning = false;
    this.isInfinite = this.totalSlides > this.slidesToShow; // Kiểm tra có cần vòng tròn không

    this.init();
    this.setupEventListeners();
    this.setupResizeListener();
  }

  getSlidesToShow() {
    const width = window.innerWidth;
    if (width <= 768) return 1;
    if (width <= 1024) return 2;
    return 3;
  }

  createClones() {
    if (!this.isInfinite) return; // Không tạo clones nếu số thẻ không đủ

    const firstClones = [];
    const lastClones = [];

    for (let i = 0; i < this.slidesToShow && i < this.totalSlides; i++) {
      const clone = this.slides[i].cloneNode(true);
      clone.classList.add("clone");
      firstClones.push(clone);
    }

    for (
      let i = this.totalSlides - this.slidesToShow;
      i < this.totalSlides;
      i++
    ) {
      if (i >= 0) {
        const clone = this.slides[i].cloneNode(true);
        clone.classList.add("clone");
        lastClones.push(clone);
      }
    }

    lastClones.forEach((clone) => {
      this.wrapper.insertBefore(clone, this.wrapper.firstChild);
    });

    firstClones.forEach((clone) => {
      this.wrapper.appendChild(clone);
    });

    this.allSlides = document.querySelectorAll(".promotion-card");
    this.totalSlidesWithClones = this.allSlides.length;
  }

  setupInitialPosition() {
    if (this.isInfinite) {
      this.currentIndex = this.slidesToShow;
    } else {
      this.currentIndex = 0; // Không cần offset nếu không có clones
    }
    this.updateCarouselPosition(false);
  }

  updateCarouselPosition(withTransition = true) {
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

    this.wrapper.style.setProperty("--card-width", `${cardWidth}%`);
    this.wrapper.style.setProperty("--gap", `${gap}%`);

    if (withTransition && this.isInfinite) {
      this.wrapper.style.transition =
        "transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)";
    } else {
      this.wrapper.style.transition = "none";
    }

    const slideWidth = cardWidth + gap;
    const translateX = -(this.currentIndex * slideWidth);
    this.wrapper.style.transform = `translateX(${translateX}%)`;
  }

  updateNavigation() {
    if (this.prevBtn && this.nextBtn) {
      const shouldShow = this.totalSlides >= 4;
      this.prevBtn.style.display = shouldShow ? "flex" : "none";
      this.nextBtn.style.display = shouldShow ? "flex" : "none";
    }
  }

  nextSlide() {
    if (this.isTransitioning || !this.isInfinite) return;

    this.isTransitioning = true;
    this.currentIndex++;
    this.updateCarouselPosition(true);

    setTimeout(() => {
      if (this.currentIndex >= this.totalSlidesWithClones - this.slidesToShow) {
        this.currentIndex = this.slidesToShow;
        this.updateCarouselPosition(false);
      }
      this.isTransitioning = false;
      this.updateNavigation();
    }, 500);
  }

  prevSlide() {
    if (this.isTransitioning || !this.isInfinite) return;

    this.isTransitioning = true;
    this.currentIndex--;
    this.updateCarouselPosition(true);

    setTimeout(() => {
      if (this.currentIndex < this.slidesToShow) {
        this.currentIndex = this.totalSlidesWithClones - 2 * this.slidesToShow;
        this.updateCarouselPosition(false);
      }
      this.isTransitioning = false;
      this.updateNavigation();
    }, 500);
  }

  setupEventListeners() {
    if (this.nextBtn) {
      this.nextBtn.addEventListener("click", () => this.nextSlide());
    }
    if (this.prevBtn) {
      this.prevBtn.addEventListener("click", () => this.prevSlide());
    }

    this.setupTouchEvents();

    this.wrapper.addEventListener("transitionend", () => {
      this.isTransitioning = false;
    });
  }

  setupTouchEvents() {
    let startX = 0;
    let endX = 0;

    this.wrapper.addEventListener(
      "touchstart",
      (e) => {
        startX = e.touches[0].clientX;
      },
      { passive: true }
    );

    this.wrapper.addEventListener(
      "touchend",
      (e) => {
        endX = e.changedTouches[0].clientX;
        const diffX = startX - endX;
        if (Math.abs(diffX) > 50 && this.isInfinite) {
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

  recreateCarousel() {
    document.querySelectorAll(".promotion-card.clone").forEach((clone) => {
      clone.remove();
    });

    this.slides = document.querySelectorAll(".promotion-card:not(.clone)");
    this.totalSlides = this.slides.length;
    this.currentIndex = 0;
    this.isTransitioning = false;
    this.isInfinite = this.totalSlides > this.slidesToShow;
    this.init();
  }

  init() {
    if (!this.wrapper || this.totalSlides === 0) return;

    this.createClones();
    this.setupInitialPosition();
    this.updateNavigation();
  }

  destroy() {
    if (this.nextBtn) {
      this.nextBtn.removeEventListener("click", this.nextSlide);
    }
    if (this.prevBtn) {
      this.prevBtn.removeEventListener("click", this.prevSlide);
    }

    document.querySelectorAll(".promotion-card.clone").forEach((clone) => {
      clone.remove();
    });

    this.wrapper.style.transform = "";
    this.wrapper.style.transition = "";
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const promotionCarousel = new InfinitePromotionCarousel();

  $(document).on("click", ".promotion-card", function (e) {
    if (!$(e.target).closest(".promotion-button").length) {
      e.preventDefault();
      const promotionId = $(this).data("promotion-id");
      if (promotionId) {
        $("#promotionIdInput").val(promotionId);
        $("#promotionForm").submit();
      }
    }
  });

  window.addEventListener("beforeunload", () => {
    promotionCarousel.destroy();
  });
});
