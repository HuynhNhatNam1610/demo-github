// Tab switching
const tabButtons = document.querySelectorAll(".tab-btn");
const tabContents = document.querySelectorAll(".tab-content");

// Lazy loading observer
let lazyLoadObserver;
let loadedItems = new Set();

// Initialize Intersection Observer for lazy loading
function initLazyLoading() {
  const options = {
    root: null,
    rootMargin: "50px",
    threshold: 0.01,
  };

  lazyLoadObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting && !loadedItems.has(entry.target)) {
        loadLazyItem(entry.target);
        loadedItems.add(entry.target);
        lazyLoadObserver.unobserve(entry.target);
      }
    });
  }, options);

  // Observe all lazy items
  observeLazyItems();
}

function observeLazyItems() {
  const lazyItems = document.querySelectorAll(".lazy-item:not(.loaded)");
  lazyItems.forEach((item) => {
    // Only observe items that are in active tabs or if "all" tab is active
    const tabContent = item.closest(".tab-content");
    if (tabContent && tabContent.classList.contains("active")) {
      lazyLoadObserver.observe(item);
    }
  });
}

function loadLazyItem(item) {
  const isVideo = item.classList.contains("video");
  const src = item.dataset.src;
  const placeholder = item.querySelector(".lazy-placeholder");

  if (isVideo) {
    const video = item.querySelector("video");
    video.src = src;
    video.style.display = "block";

    video.addEventListener("loadeddata", () => {
      placeholder.style.display = "none";
      item.classList.add("loaded");
    });

    video.addEventListener("error", () => {
      placeholder.innerHTML =
        '<div class="error-message"><i class="fas fa-exclamation-triangle"></i><span>Video không thể tải</span></div>';
      item.classList.add("error");
    });
  } else {
    const img = item.querySelector("img");
    img.src = src;
    img.alt = item.dataset.alt || "";

    img.addEventListener("load", () => {
      img.style.display = "block";
      placeholder.style.display = "none";
      item.classList.add("loaded");
    });

    img.addEventListener("error", () => {
      placeholder.innerHTML =
        '<div class="error-message"><i class="fas fa-exclamation-triangle"></i><span>Ảnh không thể tải</span></div>';
      item.classList.add("error");
    });
  }
}

// Preload critical images (first few items)
function preloadCriticalItems() {
  const criticalItems = document.querySelectorAll(
    ".tab-content.active .lazy-item"
  );
  const itemsToPreload = Math.min(6, criticalItems.length); // Preload first 6 items

  for (let i = 0; i < itemsToPreload; i++) {
    if (!loadedItems.has(criticalItems[i])) {
      loadLazyItem(criticalItems[i]);
      loadedItems.add(criticalItems[i]);
    }
  }
}

tabButtons.forEach((button) => {
  button.addEventListener("click", () => {
    // Remove active class from all tabs and contents
    tabButtons.forEach((btn) => btn.classList.remove("active"));
    tabContents.forEach((content) => content.classList.remove("active"));

    // Add active class to clicked tab
    button.classList.add("active");

    // Show corresponding tab content
    const tabId = button.dataset.tab;
    if (tabId === "all") {
      // Show all tab contents when "Tất cả" is clicked
      tabContents.forEach((content) => {
        content.classList.add("active");
        // Hide empty category messages in "all" view
        const emptyMessage = content.querySelector("p");
        if (
          emptyMessage &&
          (emptyMessage.textContent.includes("Chưa có hình ảnh") ||
            emptyMessage.textContent.includes("No images in this category"))
        ) {
          emptyMessage.style.display = "none";
        }
      });
    } else {
      // Show specific tab content
      const targetContent = document.getElementById(`tab-${tabId}`);
      if (targetContent) {
        targetContent.classList.add("active");
        // Show empty message in specific tab view
        const emptyMessage = targetContent.querySelector("p");
        if (emptyMessage) {
          emptyMessage.style.display = "block";
        }
      }
    }

    // Re-observe lazy items in newly active tabs
    setTimeout(() => {
      observeLazyItems();
      preloadCriticalItems();
    }, 100);
  });
});

// Modal functionality
const modal = document.getElementById("galleryModal");
const modalImage = document.getElementById("modalImage");
const modalVideo = document.getElementById("modalVideo");
const modalInfo = document.getElementById("modalInfo");
const closeModal = document.querySelector(".close");
const prevBtn = document.querySelector(".prev");
const nextBtn = document.querySelector(".next");
const zoomInBtn = document.getElementById("zoomIn");
const zoomOutBtn = document.getElementById("zoomOut");
const zoomInfo = document.getElementById("zoomInfo");
const imageContainer = document.querySelector(".image-container");

let currentItems = [];
let currentIndex = 0;
let zoomLevel = 1;

// Function to get all visible gallery items
function getVisibleGalleryItems() {
  const activeTabContents = document.querySelectorAll(".tab-content.active");
  let visibleItems = [];

  activeTabContents.forEach((content) => {
    const items = content.querySelectorAll(
      ".gallery-item.loaded, .gallery-item.error"
    );
    visibleItems = visibleItems.concat(Array.from(items));
  });

  return visibleItems;
}

// Add click event to gallery items (using event delegation)
document.addEventListener("click", (e) => {
  const galleryItem = e.target.closest(".gallery-item.loaded");
  if (galleryItem && !galleryItem.classList.contains("error")) {
    // Get all currently visible items
    currentItems = getVisibleGalleryItems();
    currentIndex = currentItems.indexOf(galleryItem);
    openModal();
  }
});

function openModal() {
  const item = currentItems[currentIndex];
  const isVideo = item.classList.contains("video");
  modalImage.style.display = isVideo ? "none" : "block";
  modalVideo.style.display = isVideo ? "block" : "none";

  if (isVideo) {
    const videoSrc = item.querySelector("video").src;
    modalVideo.querySelector("source").src = videoSrc;
    modalVideo.src = videoSrc;
    modalVideo.load();
    modalInfo.textContent = "Video";
  } else {
    modalImage.src = item.querySelector("img").src;
    modalInfo.textContent = item.querySelector("img").alt;
  }

  // Reset zoom and position
  zoomLevel = 1;
  translateX = 0;
  translateY = 0;
  modalImage.style.transform = `scale(${zoomLevel})`;
  zoomInfo.textContent = "100%";
  modal.style.display = "block";

  // Preload adjacent items
  preloadAdjacentItems();
}

function preloadAdjacentItems() {
  const adjacentIndices = [
    (currentIndex - 1 + currentItems.length) % currentItems.length,
    (currentIndex + 1) % currentItems.length,
  ];

  adjacentIndices.forEach((index) => {
    const item = currentItems[index];
    if (item && !loadedItems.has(item)) {
      loadLazyItem(item);
      loadedItems.add(item);
    }
  });
}

closeModal.addEventListener("click", () => {
  modal.style.display = "none";
  modalVideo.pause();
});

// Close modal when clicking outside
modal.addEventListener("click", (e) => {
  if (e.target === modal) {
    modal.style.display = "none";
    modalVideo.pause();
  }
});

prevBtn.addEventListener("click", () => {
  currentIndex = (currentIndex - 1 + currentItems.length) % currentItems.length;
  openModal();
});

nextBtn.addEventListener("click", () => {
  currentIndex = (currentIndex + 1) % currentItems.length;
  openModal();
});

zoomInBtn.addEventListener("click", () => {
  if (zoomLevel < 3) {
    zoomLevel += 0.2;
    modalImage.style.transform = `scale(${zoomLevel}) translate(${translateX}px, ${translateY}px)`;
    zoomInfo.textContent = `${Math.round(zoomLevel * 100)}%`;
  }
});

zoomOutBtn.addEventListener("click", () => {
  if (zoomLevel > 0.5) {
    zoomLevel -= 0.2;
    modalImage.style.transform = `scale(${zoomLevel}) translate(${translateX}px, ${translateY}px)`;
    zoomInfo.textContent = `${Math.round(zoomLevel * 100)}%`;
  }
});

// Image panning
let isDragging = false;
let startX,
  startY,
  translateX = 0,
  translateY = 0;

imageContainer.addEventListener("mousedown", (e) => {
  if (modalImage.style.display === "none") return;
  isDragging = true;
  imageContainer.classList.add("grabbing");
  startX = e.clientX - translateX;
  startY = e.clientY - translateY;
  e.preventDefault();
});

document.addEventListener("mousemove", (e) => {
  if (!isDragging) return;
  translateX = e.clientX - startX;
  translateY = e.clientY - startY;
  modalImage.style.transform = `scale(${zoomLevel}) translate(${translateX}px, ${translateY}px)`;
});

document.addEventListener("mouseup", () => {
  isDragging = false;
  imageContainer.classList.remove("grabbing");
});

// Touch support for mobile
imageContainer.addEventListener("touchstart", (e) => {
  if (modalImage.style.display === "none") return;
  isDragging = true;
  const touch = e.touches[0];
  startX = touch.clientX - translateX;
  startY = touch.clientY - translateY;
  e.preventDefault();
});

imageContainer.addEventListener("touchmove", (e) => {
  if (!isDragging) return;
  const touch = e.touches[0];
  translateX = touch.clientX - startX;
  translateY = touch.clientY - startY;
  modalImage.style.transform = `scale(${zoomLevel}) translate(${translateX}px, ${translateY}px)`;
  e.preventDefault();
});

imageContainer.addEventListener("touchend", () => {
  isDragging = false;
});

// Keyboard navigation
document.addEventListener("keydown", (e) => {
  if (modal.style.display === "block") {
    switch (e.key) {
      case "Escape":
        modal.style.display = "none";
        modalVideo.pause();
        break;
      case "ArrowLeft":
        prevBtn.click();
        break;
      case "ArrowRight":
        nextBtn.click();
        break;
      case "+":
      case "=":
        zoomInBtn.click();
        break;
      case "-":
        zoomOutBtn.click();
        break;
    }
  }
});

// Performance optimization: Throttle scroll events
function throttle(func, limit) {
  let inThrottle;
  return function () {
    const args = arguments;
    const context = this;
    if (!inThrottle) {
      func.apply(context, args);
      inThrottle = true;
      setTimeout(() => (inThrottle = false), limit);
    }
  };
}

// Monitor scroll performance
let scrollTimeout;
window.addEventListener(
  "scroll",
  throttle(() => {
    // Clear any existing timeout
    clearTimeout(scrollTimeout);

    // Set a small delay to batch scroll operations
    scrollTimeout = setTimeout(() => {
      // Any additional scroll-based operations can go here
    }, 50);
  }, 16)
); // ~60fps

// Initialize: Show all content by default
document.addEventListener("DOMContentLoaded", () => {
  // Remove active class from all tabs first
  tabButtons.forEach((btn) => btn.classList.remove("active"));
  tabContents.forEach((content) => content.classList.remove("active"));

  // Set only "Tất cả" tab as active
  const allButton = document.querySelector('.tab-btn[data-tab="all"]');
  if (allButton) {
    allButton.classList.add("active");
    // Show all content and hide empty messages
    tabContents.forEach((content) => {
      content.classList.add("active");
      // Hide empty category messages in "all" view on page load
      const emptyMessage = content.querySelector("p");
      if (
        emptyMessage &&
        (emptyMessage.textContent.includes("Chưa có hình ảnh") ||
          emptyMessage.textContent.includes("No images in this category") ||
          emptyMessage.textContent.includes("Chưa có video"))
      ) {
        emptyMessage.style.display = "none";
      }
    });
  }

  // Initialize lazy loading after DOM is ready
  if ("IntersectionObserver" in window) {
    initLazyLoading();
    // Preload critical items after a short delay
    setTimeout(preloadCriticalItems, 100);
  } else {
    // Fallback for browsers without IntersectionObserver support
    loadAllItems();
  }
});

// Fallback function for older browsers
function loadAllItems() {
  const allLazyItems = document.querySelectorAll(".lazy-item");
  allLazyItems.forEach((item) => {
    loadLazyItem(item);
    loadedItems.add(item);
  });
}

// Handle tab visibility change to pause/resume loading
document.addEventListener("visibilitychange", () => {
  if (document.hidden) {
    // Pause aggressive loading when tab is hidden
    if (lazyLoadObserver) {
      document.querySelectorAll(".lazy-item:not(.loaded)").forEach((item) => {
        lazyLoadObserver.unobserve(item);
      });
    }
  } else {
    // Resume loading when tab becomes visible
    setTimeout(() => {
      observeLazyItems();
    }, 100);
  }
});

// Error retry mechanism
function retryFailedItems() {
  const errorItems = document.querySelectorAll(".gallery-item.error");
  errorItems.forEach((item) => {
    item.classList.remove("error");
    loadedItems.delete(item);
    if (lazyLoadObserver) {
      lazyLoadObserver.observe(item);
    }
  });
}

// Auto-retry failed items after 30 seconds
setTimeout(retryFailedItems, 30000);
