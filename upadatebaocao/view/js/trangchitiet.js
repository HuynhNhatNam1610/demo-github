// Product Image Gallery with enhanced zoom functionality
function changeImage(element) {
  const mainImage = document.getElementById("mainImage");

  // Add fade transition
  mainImage.style.opacity = 0.6;

  setTimeout(() => {
    mainImage.src = element.src;

    // Reset thumbnails and set active
    const thumbnails = document.querySelectorAll(".thumbnail");
    thumbnails.forEach((thumb) => thumb.classList.remove("active"));
    element.classList.add("active");

    // Update current index for arrow navigation
    const thumbArray = Array.from(thumbnails);
    currentIndex = thumbArray.indexOf(element);

    // Restore opacity
    mainImage.style.opacity = 1;

    // Initialize zoom after image change
    setTimeout(imageZoom, 200);
  }, 200);
}

// Size Selector
function selectSize(element, size) {
  const sizeOptions = document.querySelectorAll(".size-btn");
  sizeOptions.forEach((option) => option.classList.remove("active"));
  element.classList.add("active");
  document.getElementById("selectedSize").textContent = size;
}

// Quantity Controls
function increaseQuantity() {
  const quantityInput = document.getElementById("quantity");
  const currentQuantity = parseInt(quantityInput.value);
  // Set reasonable upper limit
  if (currentQuantity < 99) {
    quantityInput.value = currentQuantity + 1;
  }
}

function decreaseQuantity() {
  const quantityInput = document.getElementById("quantity");
  const currentQuantity = parseInt(quantityInput.value);
  if (currentQuantity > 1) {
    quantityInput.value = currentQuantity - 1;
  }
}

function imageZoom() {
  const mainImage = document.getElementById("mainImage");
  const zoomLens = document.querySelector(".zoom-lens");
  const zoomResult = document.querySelector(".zoom-result");

  if (!mainImage || !zoomLens || !zoomResult) return;

  // Cấu hình kích thước
  const lensSize = 100;
  const resultSize = 300;
  zoomLens.style.width = `${lensSize}px`;
  zoomLens.style.height = `${lensSize}px`;
  zoomResult.style.width = `${resultSize}px`;
  zoomResult.style.height = `${resultSize}px`;

  // Tính tỉ lệ zoom
  const cx = resultSize / lensSize;
  const cy = resultSize / lensSize;

  // Xóa event listeners cũ
  mainImage.removeEventListener("mousemove", moveLens);
  mainImage.removeEventListener("mouseenter", showZoom);
  mainImage.removeEventListener("mouseleave", hideZoom);
  mainImage.removeEventListener("touchmove", handleTouchMove);
  mainImage.removeEventListener("touchstart", showZoom);
  mainImage.removeEventListener("touchend", hideZoom);

  // Khởi tạo khi ảnh đã load
  function setupZoom() {
    zoomResult.style.backgroundImage = `url('${mainImage.src}')`;
    zoomResult.style.backgroundSize = `${mainImage.width * cx}px ${
      mainImage.height * cy
    }px`;

    // Thêm event listeners - đảm bảo mouseenter trigger ngay khi hover
    mainImage.addEventListener("mousemove", moveLens);
    mainImage.addEventListener("mouseenter", showZoom); // This event shows zoom on hover
    mainImage.addEventListener("mouseleave", hideZoom);

    // Thêm support cho touch devices
    mainImage.addEventListener("touchmove", handleTouchMove);
    mainImage.addEventListener("touchstart", showZoom);
    mainImage.addEventListener("touchend", hideZoom);
  }

  if (mainImage.complete) {
    setupZoom();
  } else {
    mainImage.onload = setupZoom;
  }

  function showZoom(e) {
    if (window.innerWidth <= 992) return; // Không hiển thị trên mobile
    zoomLens.style.display = "block";
    zoomResult.style.display = "block";
    moveLens(e); // Gọi ngay moveLens để zoom hiển thị tức thì khi rê chuột
  }

  function hideZoom() {
    zoomLens.style.display = "none";
    zoomResult.style.display = "none";
  }

  function moveLens(e) {
    e.preventDefault();
    if (window.innerWidth <= 992) return;

    const pos = getCursorPos(e);
    const imgRect = mainImage.getBoundingClientRect();

    // Tính vị trí của zoom lens
    let lensX = pos.x - lensSize / 2;
    let lensY = pos.y - lensSize / 2;

    lensX = Math.max(0, Math.min(lensX, imgRect.width - lensSize));
    lensY = Math.max(0, Math.min(lensY, imgRect.height - lensSize));

    zoomLens.style.left = `${lensX}px`;
    zoomLens.style.top = `${lensY}px`;

    // Tính vị trí nền của zoom result
    const bgX = lensX * cx;
    const bgY = lensY * cy;
    zoomResult.style.backgroundPosition = `-${bgX}px -${bgY}px`;

    // Tính toán vị trí động cho zoom result
    const resultX = pos.x + 20;
    const resultY = pos.y + 20;

    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;

    let adjustedX = resultX;
    let adjustedY = resultY;

    if (resultX + resultSize > viewportWidth) {
      adjustedX = resultX - resultSize - 40;
    }
    if (resultY + resultSize > viewportHeight) {
      adjustedY = viewportHeight - resultSize - 10;
    }
    if (adjustedX < 0) adjustedX = 10;
    if (adjustedY < 0) adjustedY = 10;

    zoomResult.style.left = `${adjustedX}px`;
    zoomResult.style.top = `${adjustedY}px`;
  }

  function handleTouchMove(e) {
    if (e.touches.length > 0) {
      moveLens(e.touches[0]);
    }
  }

  function getCursorPos(e) {
    const rect = mainImage.getBoundingClientRect();
    const x = e.pageX - rect.left - window.pageXOffset;
    const y = e.pageY - rect.top - window.pageYOffset;
    return { x, y };
  }
}

// Single DOMContentLoaded Event Listener
document.addEventListener("DOMContentLoaded", function () {
  // Main image navigation
  const prev = document.querySelector(".nav-arrow.prev");
  const next = document.querySelector(".nav-arrow.next");
  const thumbnails = document.querySelectorAll(".thumbnail");
  let currentIndex = 0;

  prev.addEventListener("click", function () {
    currentIndex = (currentIndex - 1 + thumbnails.length) % thumbnails.length;
    changeImage(thumbnails[currentIndex]);
  });

  next.addEventListener("click", function () {
    currentIndex = (currentIndex + 1) % thumbnails.length;
    changeImage(thumbnails[currentIndex]);
  });

  // Validate quantity input
  const quantityInput = document.getElementById("quantity");
  quantityInput.addEventListener("input", function () {
    this.value = this.value.replace(/[^0-9]/g, "");
    if (this.value === "" || parseInt(this.value) < 1) {
      this.value = "1";
    }
  });

  // Add to cart button
  const addToCartBtn = document.querySelector(".add-to-cart");
  addToCartBtn.addEventListener("click", function () {
    const selectedSize = document.getElementById("selectedSize").textContent;
    const quantity = document.getElementById("quantity").value;
    alert(`Đã thêm ${quantity} sản phẩm kích cỡ ${selectedSize} vào giỏ hàng!`);
  });

  // Initialize image zoom
  imageZoom();

  // Cập nhật zoom khi thay đổi kích thước cửa sổ
  let resizeTimer;
  window.addEventListener("resize", function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(imageZoom, 250);
  });
});
