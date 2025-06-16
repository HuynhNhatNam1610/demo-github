let currentTopicId = null;
let currentTopicName = "";
let sukienList = [];
let currentImages = [];
let currentImageIndex = 0;
let imageScale = 1;
let isDragging = false;
let startX = 0;
let startY = 0;
let translateX = 0;
let translateY = 0;
let pagesList = [];
let currentFilterValue = "";
let selectedFiles = [];
let uploadModal = null;

const topicsGrid = document.getElementById("topics-grid");
const managementSection = document.getElementById("management-section");
const currentTopicNameEl = document.getElementById("current-topic-name");
const itemsGrid = document.getElementById("items-grid");

document.addEventListener("DOMContentLoaded", function () {
  loadTopics();
  loadSukien();
});

async function loadTopics() {
  try {
    const formData = new FormData();
    formData.append("action", "get_topics");
    const response = await fetch(
      "/libertylaocai/controller/UserController.php",
      {
        method: "POST",
        body: formData,
      }
    );
    const topics = await response.json();
    displayTopics(topics);
  } catch (error) {
    console.error("Error loading topics:", error);
    showAlert("Lỗi khi tải danh sách chủ đề!", "error");
  }
}

async function loadSukien() {
  try {
    const formData = new FormData();
    formData.append("action", "get_sukien");
    const response = await fetch(
      "/libertylaocai/controller/UserController.php",
      {
        method: "POST",
        body: formData,
      }
    );
    sukienList = await response.json();
  } catch (error) {
    console.error("Error loading sukien:", error);
  }
}

function displayTopics(topics) {
  topicsGrid.innerHTML = "";
  topics.forEach((topic) => {
    const topicCard = document.createElement("div");
    topicCard.className = "topic-card";
    topicCard.dataset.topicId = topic.id;
    topicCard.innerHTML = `
            <h3>${topic.topic}</h3>
            <p>${topic.topic_ngonngu}</p>
        `;
    topicCard.addEventListener("click", () =>
      selectTopic(topic.id, topic.topic)
    );
    topicsGrid.appendChild(topicCard);
  });
}

async function selectTopic(topicId, topicName) {
  document
    .querySelectorAll(".topic-card")
    .forEach((card) => card.classList.remove("active"));
  document
    .querySelector(`[data-topic-id="${topicId}"]`)
    .classList.add("active");
  currentTopicId = topicId;
  currentTopicName = topicName;
  currentTopicNameEl.textContent = `Quản Lý: ${topicName}`;
  managementSection.style.display = "block";
  managementSection.scrollIntoView({ behavior: "smooth" });

  const tabsContainer = document.querySelector(".tabs-container");
  if (tabsContainer) {
    tabsContainer.remove();
  }

  currentFilterValue = "";

  if (topicId === "4") {
    await loadPages();
    displayTabs(pagesList, "page");
    if (pagesList.length > 0) {
      await loadImages(topicId, pagesList[0]);
    } else {
      await loadImages(topicId);
    }
  } else if (topicId === "9") {
    await loadSukien();
    const eventDisplayNames = sukienList.map(
      (event) => event.title || event.code
    );
    displayTabs(eventDisplayNames, "event");
    if (sukienList.length > 0) {
      await loadImages(topicId, sukienList[0].code);
    } else {
      await loadImages(topicId);
    }
  } else {
    await loadImages(topicId);
  }

  addUploadButton();
}

async function loadPages() {
  try {
    const formData = new FormData();
    formData.append("action", "get_pages");
    const response = await fetch(
      "/libertylaocai/controller/UserController.php",
      {
        method: "POST",
        body: formData,
      }
    );
    pagesList = await response.json();
  } catch (error) {
    console.error("Error loading pages:", error);
    showAlert("Lỗi khi tải danh sách page!", "error");
  }
}

function displayTabs(items, type) {
  const tabsContainer = document.createElement("div");
  tabsContainer.className = "tabs-container";

  // Ánh xạ tên page sang tên hiển thị thân thiện
  const pageNameMap = {
    event: "Sự kiện",
    pagedetail: "Tổ chức sự kiện",
    "nhahang&bar": "Nhà hàng & Bar",
    sale: "Khuyến mãi",
    saledetail: "Chi tiết khuyến mãi",
    tintuc: "Tin tức",
    "tintuc-detail": "Chi tiết tin tức",
    dichvu: "Dịch vụ",
    "su-kien-da-to-chuc": "Sự kiện đã tổ chức",
    "chi-tiet-su-kien-da-to-chuc": "Chi tiết sự kiện đã tổ chức",
    "thu-vien": "Thư viện",
    "dat-phong": "Đặt phòng",
    duadonsanbay: "Đưa đón sân bay",
  };

  tabsContainer.innerHTML = `
    <div class="tabs">
      ${items
        .map(
          (item, index) => `
        <button class="tab-btn ${index === 0 ? "active" : ""}" 
                data-${type}="${
            type === "event"
              ? sukienList.find((e) => (e.title || e.code) === item).code
              : item
          }">
          ${pageNameMap[item] || item}
        </button>
      `
        )
        .join("")}
    </div>
  `;

  itemsGrid.parentNode.insertBefore(tabsContainer, itemsGrid);

  document.querySelectorAll(".tab-btn").forEach((btn) => {
    btn.addEventListener("click", async () => {
      document
        .querySelectorAll(".tab-btn")
        .forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
      const value = btn.dataset[type];
      currentFilterValue = value;
      await loadImages(currentTopicId, value);
    });
  });

  if (items.length > 0) {
    currentFilterValue =
      type === "event"
        ? sukienList.find((e) => (e.title || e.code) === items[0]).code
        : items[0];
  }
}

async function loadImages(topicId, filterValue = "") {
  try {
    itemsGrid.innerHTML = '<div class="loading"></div>';
    const formData = new FormData();
    formData.append("action", "get_images");
    formData.append("topic_id", topicId);
    if (topicId === "4" && filterValue) {
      formData.append("page", filterValue);
    } else if (topicId === "9" && filterValue) {
      const event = sukienList.find((e) => e.code === filterValue);
      if (event) {
        formData.append("id_sukien", event.id);
      }
    }
    const response = await fetch(
      "/libertylaocai/controller/UserController.php",
      {
        method: "POST",
        body: formData,
      }
    );
    const data = await response.json();
    displayImages(data.images, data.topic_id);
  } catch (error) {
    console.error("Error loading images:", error);
    itemsGrid.innerHTML = "<p>Lỗi khi tải ảnh!</p>";
  }
}

function displayImages(items, topicId) {
  itemsGrid.innerHTML = "";
  currentImages = [];

  if (items.length === 0) {
    itemsGrid.innerHTML =
      '<p style="text-align: center; color: #7f8c8d; font-size: 1.2rem; padding: 40px;">Chưa có ảnh/video nào</p>';
    return;
  }

  // Ánh xạ tên area sang tên hiển thị thân thiện cho pagedetail
  const areaNameMap = {
    "pagedetail-banner-wedding": "Đám cưới",
    "pagedetail-banner-birthday": "Sinh nhật",
    "pagedetail-banner-conference": "Hội nghị",
    "pagedetail-banner-gala": "Gala",
  };

  items.forEach((item, index) => {
    const itemCard = document.createElement("div");
    itemCard.className = "item-card";

    let mediaHtml = "";
    let statusHtml = "";
    let actionsHtml = "";
    let infoHtml = "";

    if (item.video) {
      mediaHtml = `<video class="item-video" controls>
                    <source src="../../view/video/${item.video}" type="video/mp4">
                    Video không được hỗ trợ
                </video>`;
      infoHtml = `<div class="item-info">
                    ${item.extra_info ? item.extra_info : ""}
                </div>`;
      actionsHtml = `
                    <button class="btn btn-danger" onclick="deleteItem(${item.id}, '${item.table}', '${item.video}')">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                `;
    } else {
      // Thêm loading="lazy" vào thẻ img
      mediaHtml = `<img class="item-image" src="../../view/img/${item.image}" alt="Image" loading="lazy" onclick="openImageViewer(${currentImages.length})" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkFuaCBrb25nIHRvbiB0YWk8L3RleHQ+PC9zdmc+'">`;

      currentImages.push({
        url: `../../view/img/${item.image}`,
        name: item.image,
        info: item,
      });

      if (topicId === "4") {
        const extraInfoParts = item.extra_info.split(", ");
        let formattedInfo = "";
        if (
          extraInfoParts[0].startsWith("Page: pagedetail") &&
          extraInfoParts[1]
        ) {
          const area = extraInfoParts[1].replace("Area: ", "");
          formattedInfo = areaNameMap[area] || area;
        }
        infoHtml = `<div class="item-info">
                        ${
                          item.created_at
                            ? "Ngày tạo: " +
                              formatDate(item.created_at) +
                              "<br>"
                            : ""
                        }
                        ${formattedInfo}
                    </div>`;
        actionsHtml = `
                    <button class="btn btn-primary" onclick="openEditModal(${item.id}, '${item.image}')">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </button>
                `;
      } else if (topicId === "1" && item.chon_area) {
        // Ảnh thuộc chon_anhtongquat
        infoHtml = `<div class="item-info">
                        ${
                          item.created_at
                            ? "Ngày tạo: " +
                              formatDate(item.created_at) +
                              "<br>"
                            : ""
                        }
                        Khu vực: ${item.area_display}
                    </div>`;
        actionsHtml = `
                    <button class="btn btn-primary" onclick="openEditModal(${item.id}, '${item.image}')">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </button>
                `;
      } else {
        infoHtml = `<div class="item-info">
                        ${
                          item.created_at
                            ? "Ngày tạo: " +
                              formatDate(item.created_at) +
                              "<br>"
                            : ""
                        }
                        ${item.extra_info ? item.extra_info : ""}
                    </div>`;

        if (item.hasOwnProperty("active")) {
          statusHtml += `<span class="status-badge ${
            item.active == 1 ? "status-active" : "status-inactive"
          }">
                            ${item.active == 1 ? "Đang hiển thị" : "Đang ẩn"}
                        </span>`;
        }

        if (item.hasOwnProperty("is_primary") && item.is_primary == 1) {
          statusHtml += `<span class="status-badge status-primary">Ảnh chính</span>`;
        }

        actionsHtml = `
                    ${
                      item.hasOwnProperty("active")
                        ? `
                        <button class="btn ${
                          item.active == 1 ? "btn-secondary" : "btn-success"
                        }" 
                                onclick="toggleStatus(${item.id}, '${
                            item.table
                          }', 'active', ${item.active})">
                            <i class="fas ${
                              item.active == 1 ? "fa-pause" : "fa-play"
                            }"></i> 
                            ${item.active == 1 ? "Ẩn" : "Hiện"}
                        </button>
                    `
                        : ""
                    }
                    ${
                      item.hasOwnProperty("is_primary")
                        ? `
                        <button class="btn ${
                          item.is_primary == 1 ? "btn-secondary" : "btn-success"
                        }" 
                                onclick="toggleStatus(${item.id}, '${
                            item.table
                          }', 'is_primary', ${item.is_primary})">
                            <i class="fas ${
                              item.is_primary == 1 ? "fa-star" : "fa-star-o"
                            }"></i> 
                            ${
                              item.is_primary == 1
                                ? "Bỏ làm chính"
                                : "Làm ảnh chính"
                            }
                        </button>
                    `
                        : ""
                    }
                    <button class="btn btn-danger" onclick="deleteItem(${
                      item.id
                    }, '${item.table}', '${item.video || item.image}')">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                `;
      }
    }

    itemCard.innerHTML = `
                ${mediaHtml}
                <div class="item-content">
                    ${statusHtml}
                    ${infoHtml}
                    <div class="item-actions">
                        ${actionsHtml}
                    </div>
                </div>
            `;

    itemsGrid.appendChild(itemCard);
  });
}
async function deleteItem(id, table, fileName) {
  if (!confirm("Bạn có chắc chắn muốn xóa?")) return;

  try {
    const formData = new FormData();
    formData.append("action", "delete_item");
    formData.append("id", id);
    formData.append("table", table);
    formData.append("image_name", fileName);

    const response = await fetch(
      "/libertylaocai/controller/UserController.php",
      {
        method: "POST",
        body: formData,
      }
    );
    const result = await response.json();

    if (result.success) {
      showAlert(result.message, "success");
      await loadImages(currentTopicId);
    } else {
      showAlert(result.message, "error");
    }
  } catch (error) {
    console.error("Error deleting:", error);
    showAlert("Lỗi khi xóa!", "error");
  }
}

async function toggleStatus(id, table, field, currentStatus) {
  try {
    const formData = new FormData();
    formData.append("action", "toggle_status");
    formData.append("id", id);
    formData.append("table", table);
    formData.append("field", field);
    formData.append("current_status", currentStatus);

    const response = await fetch(
      "/libertylaocai/controller/UserController.php",
      {
        method: "POST",
        body: formData,
      }
    );
    const result = await response.json();

    if (result.success) {
      showAlert("Cập nhật thành công!", "success");
      await loadImages(currentTopicId, currentFilterValue);
    } else {
      showAlert(result.message, "error");
    }
  } catch (error) {
    console.error("Error toggling status:", error);
    showAlert("Lỗi khi cập nhật!", "error");
  }
}

function showAlert(message, type) {
  const existingAlerts = document.querySelectorAll(".toast-alert");
  existingAlerts.forEach((alert) => alert.remove());

  let toastContainer = document.getElementById("toast-container");
  if (!toastContainer) {
    toastContainer = document.createElement("div");
    toastContainer.id = "toast-container";
    toastContainer.className = "toast-container";
    document.body.appendChild(toastContainer);
  }

  const alert = document.createElement("div");
  alert.className = `toast-alert toast-${type}`;
  const icon =
    type === "success" ? "fas fa-check-circle" : "fas fa-exclamation-circle";

  alert.innerHTML = `
    <div class="toast-content">
      <i class="${icon}"></i>
      <span class="toast-message">${message}</span>
    </div>
    <button class="toast-close" onclick="this.parentElement.remove()">
      <i class="fas fa-times"></i>
    </button>
  `;

  toastContainer.appendChild(alert);

  setTimeout(() => {
    alert.classList.add("show");
  }, 10);

  setTimeout(() => {
    alert.classList.remove("show");
    setTimeout(() => {
      if (alert.parentElement) {
        alert.remove();
      }
    }, 300);
  }, 5000);
}

function formatDate(dateString) {
  const date = new Date(dateString);
  return (
    date.toLocaleDateString("vi-VN") + " " + date.toLocaleTimeString("vi-VN")
  );
}
function openEditModal(id, currentImage) {
  selectedFiles = [];
  updateSelectedFiles();

  uploadModal = document.getElementById("upload-modal");
  document.getElementById("upload-modal-title").textContent =
    "Chỉnh sửa ảnh Head Banner";
  document.getElementById("upload-label-text").textContent =
    "Kéo và thả ảnh vào đây hoặc nhấp để chọn (chỉ 1 ảnh)";
  document.getElementById("file-input").accept = "image/*";
  document.getElementById("upload-options").innerHTML = `
        <div class="option-group">
            <label>Ảnh hiện tại:</label>
            <span>${currentImage}</span>
        </div>
    `;

  uploadModal.style.display = "flex";
  document.body.style.overflow = "hidden";

  const uploadSection = uploadModal.querySelector(".upload-section");
  uploadSection.addEventListener("dragover", (e) => {
    e.preventDefault();
    uploadSection.classList.add("dragover");
  });
  uploadSection.addEventListener("dragleave", () => {
    uploadSection.classList.remove("dragover");
  });
  uploadSection.addEventListener("drop", (e) => {
    e.preventDefault();
    uploadSection.classList.remove("dragover");
    const files = Array.from(e.dataTransfer.files);
    handleFileSelect({ target: { files } }, true);
  });

  document
    .getElementById("file-input")
    .addEventListener("change", (e) => handleFileSelect(e, true));

  // Lưu id để sử dụng khi upload
  uploadModal.dataset.imageId = id;
}

function openImageViewer(imageIndex) {
  if (currentImages.length === 0) return;

  currentImageIndex = imageIndex;
  updateViewerImage();

  const modal = document.getElementById("image-viewer-modal");
  modal.style.display = "flex";
  document.body.style.overflow = "hidden";

  const viewerImage = document.getElementById("viewer-image");
  const imageContainer = document.querySelector(".image-container");

  viewerImage.addEventListener("mousedown", startDrag);
  document.addEventListener("mousemove", drag);
  document.addEventListener("mouseup", endDrag);

  imageContainer.addEventListener("wheel", handleWheel);

  viewerImage.addEventListener("touchstart", handleTouchStart);
  viewerImage.addEventListener("touchmove", handleTouchMove);
  viewerImage.addEventListener("touchend", handleTouchEnd);
}

function closeImageViewer() {
  const modal = document.getElementById("image-viewer-modal");
  modal.style.display = "none";
  document.body.style.overflow = "auto";

  resetZoom();
}

function updateViewerImage() {
  if (currentImages.length === 0) return;

  const image = currentImages[currentImageIndex];
  const viewerImage = document.getElementById("viewer-image");
  const imageCounter = document.getElementById("image-counter");
  const imageName = document.getElementById("image-name");

  viewerImage.src = image.url;
  viewerImage.onload = () => {
    viewerImage.classList.add("loaded");
    console.log("Image loaded successfully:", image.url);
  };
  viewerImage.onerror = () => {
    console.error("Failed to load image:", image.url);
    viewerImage.src =
      "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkFuaCBrb25nIHRvbiB0YWk8L3RleHQ+PC9zdmc+";
  };
  imageCounter.textContent = `${currentImageIndex + 1} / ${
    currentImages.length
  }`;
  imageName.textContent = image.name;

  resetZoom();

  const prevBtn = document.querySelector(".nav-prev");
  const nextBtn = document.querySelector(".nav-next");

  prevBtn.style.display = currentImages.length > 1 ? "flex" : "none";
  nextBtn.style.display = currentImages.length > 1 ? "flex" : "none";
}

function previousImage() {
  if (currentImages.length <= 1) return;

  currentImageIndex =
    (currentImageIndex - 1 + currentImages.length) % currentImages.length;
  updateViewerImage();
}

function nextImage() {
  if (currentImages.length <= 1) return;

  currentImageIndex = (currentImageIndex + 1) % currentImages.length;
  updateViewerImage();
}

function zoomIn() {
  imageScale = Math.min(imageScale * 1.2, 5);
  updateImageTransform();
}

function zoomOut() {
  imageScale = Math.max(imageScale / 1.2, 0.1);
  updateImageTransform();
}

function resetZoom() {
  imageScale = 1;
  translateX = 0;
  translateY = 0;
  updateImageTransform();
}

function updateImageTransform() {
  const viewerImage = document.getElementById("viewer-image");
  const zoomLevel = document.getElementById("zoom-level");

  viewerImage.style.transform = `translate(${translateX}px, ${translateY}px) scale(${imageScale})`;
  zoomLevel.textContent = `${Math.round(imageScale * 100)}%`;
}

function startDrag(e) {
  if (imageScale <= 1) return;

  isDragging = true;
  startX = e.clientX - translateX;
  startY = e.clientY - translateY;

  document.body.style.cursor = "grabbing";
}

function drag(e) {
  if (!isDragging || imageScale <= 1) return;

  e.preventDefault();
  translateX = e.clientX - startX;
  translateY = e.clientY - startY;

  updateImageTransform();
}

function endDrag() {
  isDragging = false;
  document.body.style.cursor = "default";
}

function handleWheel(e) {
  e.preventDefault();

  if (e.deltaY < 0) {
    zoomIn();
  } else {
    zoomOut();
  }
}

let touchStartX = 0;
let touchStartY = 0;
let initialDistance = 0;
let initialScale = 1;

function handleTouchStart(e) {
  if (e.touches.length === 1) {
    touchStartX = e.touches[0].clientX - translateX;
    touchStartY = e.touches[0].clientY - translateY;
  } else if (e.touches.length === 2) {
    const touch1 = e.touches[0];
    const touch2 = e.touches[1];
    initialDistance = Math.hypot(
      touch2.clientX - touch1.clientX,
      touch2.clientY - touch1.clientY
    );
    initialScale = imageScale;
  }
}

function handleTouchMove(e) {
  e.preventDefault();

  if (e.touches.length === 1 && imageScale > 1) {
    translateX = e.touches[0].clientX - touchStartX;
    translateY = e.touches[0].clientY - touchStartY;
    updateImageTransform();
  } else if (e.touches.length === 2) {
    const touch1 = e.touches[0];
    const touch2 = e.touches[1];
    const currentDistance = Math.hypot(
      touch2.clientX - touch1.clientX,
      touch2.clientY - touch1.clientY
    );
    const scale = (currentDistance / initialDistance) * initialScale;

    imageScale = Math.max(0.1, Math.min(5, scale));
    updateImageTransform();
  }
}

function handleTouchEnd() {}

function createUploadButton() {
  const uploadBtn = document.createElement("button");
  uploadBtn.className = "btn btn-primary upload-btn";
  const buttonText = currentTopicId === "16" ? "Thêm Video" : "Thêm Ảnh";
  uploadBtn.innerHTML = `<i class="fas fa-plus"></i> ${buttonText}`;
  uploadBtn.onclick = openUploadModal;
  return uploadBtn;
}

function addUploadButton() {
  const existingBtn = document.querySelector(".upload-btn");
  if (existingBtn) {
    existingBtn.remove();
  }

  // Không hiển thị nút thêm cho topic_id = 4
  if (currentTopicId !== "4") {
    const sectionHeader = document.querySelector(".section-header");
    const uploadBtn = createUploadButton();
    sectionHeader.appendChild(uploadBtn);
  }
}

function openUploadModal() {
  if (currentTopicId === "4") return; // Không mở modal cho head_banner
  selectedFiles = [];
  updateSelectedFiles();
  updateUploadOptions();

  uploadModal = document.getElementById("upload-modal");
  const isVideo = currentTopicId === "16";
  const modalTitle = isVideo ? "Thêm Video Mới" : "Thêm Ảnh Mới";
  const acceptType = isVideo ? "video/*" : "image/*";
  const fileTypeText = isVideo ? "video" : "ảnh";
  const maxFiles = isVideo ? 3 : 5;

  document.getElementById("upload-modal-title").textContent = modalTitle;
  document.getElementById(
    "upload-label-text"
  ).textContent = `Kéo và thả ${fileTypeText} vào đây hoặc nhấp để chọn (tối đa ${maxFiles} ${fileTypeText})`;
  document.getElementById("file-input").accept = acceptType;

  uploadModal.style.display = "flex";
  document.body.style.overflow = "hidden";

  const uploadSection = uploadModal.querySelector(".upload-section");
  uploadSection.addEventListener("dragover", (e) => {
    e.preventDefault();
    uploadSection.classList.add("dragover");
  });
  uploadSection.addEventListener("dragleave", () => {
    uploadSection.classList.remove("dragover");
  });
  uploadSection.addEventListener("drop", (e) => {
    e.preventDefault();
    uploadSection.classList.remove("dragover");
    const files = Array.from(e.dataTransfer.files);
    handleFileSelect({ target: { files } });
  });

  document
    .getElementById("file-input")
    .addEventListener("change", handleFileSelect);
}

function closeUploadModal() {
  if (uploadModal) {
    uploadModal.style.display = "none";
    document.body.style.overflow = "auto";
  }
}

function handleFileSelect(event, isEdit = false) {
  const files = Array.from(event.target.files);
  const isVideo = currentTopicId === "16";
  const maxFiles = isEdit ? 1 : isVideo ? 3 : 5;
  const fileTypeText = isVideo ? "video" : "ảnh";

  if (selectedFiles.length + files.length > maxFiles) {
    showAlert(`Chỉ được chọn tối đa ${maxFiles} ${fileTypeText}!`, "error");
    return;
  }

  let validTypes;
  if (isVideo) {
    validTypes = [
      "video/mp4",
      "video/avi",
      "video/mov",
      "video/wmv",
      "video/flv",
    ];
  } else {
    validTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
  }

  const invalidFiles = files.filter((file) => !validTypes.includes(file.type));

  if (invalidFiles.length > 0) {
    const typeText = isVideo
      ? "video (MP4, AVI, MOV, WMV, FLV)"
      : "ảnh (JPG, PNG, GIF)";
    showAlert(`Chỉ được chọn file ${typeText}!`, "error");
    return;
  }

  files.forEach((file) => {
    const fileObj = {
      file: file,
      id: Date.now() + Math.random().toString(36).substr(2, 9),
      preview: isVideo ? null : URL.createObjectURL(file),
    };
    if (isEdit) {
      selectedFiles = [fileObj];
    } else {
      selectedFiles.push(fileObj);
    }
  });

  updateSelectedFiles();
  event.target.value = "";
}

function updateSelectedFiles() {
  const container = document.getElementById("selected-files");
  const uploadBtn = document.getElementById("upload-btn");
  const isVideo = currentTopicId === "16";

  if (selectedFiles.length === 0) {
    const fileTypeText = isVideo ? "video" : "ảnh";
    container.innerHTML = `<p class="no-files">Chưa chọn ${fileTypeText} nào</p>`;
    uploadBtn.disabled = true;
  } else {
    container.innerHTML = selectedFiles
      .map((fileObj) => {
        let previewHtml;
        if (isVideo) {
          previewHtml = `<div class="video-icon"><i class="fas fa-video"></i></div>`;
        } else {
          previewHtml = `<img src="${fileObj.preview}" alt="Preview">`;
        }

        return `
          <div class="file-preview" data-file-id="${fileObj.id}">
            ${previewHtml}
            <div class="file-info">
              <span class="file-name">${fileObj.file.name}</span>
              <span class="file-size">${(
                fileObj.file.size /
                1024 /
                1024
              ).toFixed(2)} MB</span>
            </div>
            <button class="btn-remove" data-file-id="${fileObj.id}">
              <i class="fas fa-times"></i>
            </button>
          </div>
        `;
      })
      .join("");
    uploadBtn.disabled = false;

    const removeButtons = container.querySelectorAll(".btn-remove");
    removeButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const fileId = button.getAttribute("data-file-id");
        removeFile(fileId);
      });
    });
  }
}

function removeFile(fileId) {
  const fileIndex = selectedFiles.findIndex((f) => f.id === fileId);
  if (fileIndex === -1) {
    console.error(`File with ID ${fileId} not found`);
    showAlert("Không tìm thấy ảnh để xóa!", "error");
    return;
  }

  URL.revokeObjectURL(selectedFiles[fileIndex].preview);
  selectedFiles.splice(fileIndex, 1);
  updateSelectedFiles();
}

function updateUploadOptions() {
  const container = document.getElementById("upload-options");
  let optionsHtml = "";

  switch (currentTopicId) {
    case "9":
      optionsHtml = `
        <div class="option-group">
          <label>Sự kiện:</label>
          <select id="event-select" class="select2" required>
            <option value="">-- Chọn sự kiện --</option>
            ${sukienList
              .map(
                (event) =>
                  `<option value="${event.id}">${
                    event.code || event.name || "Sự kiện #" + event.id
                  }</option>`
              )
              .join("")}
          </select>
        </div>
      `;
      break;
    case "16":
      optionsHtml = `
        <div class="option-group">
          <label>Dịch vụ (tùy chọn):</label>
          <input type="text" id="service-input" placeholder="Nhập tên dịch vụ hoặc để trống">
        </div>
      `;
      break;
  }

  container.innerHTML = optionsHtml;

  // Khởi tạo Select2
  if (document.getElementById("event-select")) {
    $("#event-select").select2({
      dropdownParent: $("#upload-modal"), // Giữ dropdown trong modal
      width: "100%",
    });
  }
}

function toggleAreaSelect() {
  const pageSelect = document.getElementById("page-select");
  const areaSelectGroup = document.getElementById("area-select-group");
  if (pageSelect.value === "pagedetail") {
    areaSelectGroup.style.display = "block";
  } else {
    areaSelectGroup.style.display = "none";
  }
}

async function uploadImages() {
  if (selectedFiles.length === 0) {
    showAlert("Vui lòng chọn ít nhất 1 ảnh!", "error");
    return;
  }

  const uploadBtn = document.getElementById("upload-btn");
  uploadBtn.disabled = true;
  uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tải...';

  try {
    const formData = new FormData();
    if (
      currentTopicId === "4" ||
      (currentTopicId === "1" && uploadModal.dataset.imageId)
    ) {
      // Chỉ gọi edit_image nếu đang chỉnh sửa ảnh (có imageId)
      formData.append("action", "edit_image");
      formData.append("id", uploadModal.dataset.imageId);
      formData.append("image", selectedFiles[0].file);
    } else {
      // Gọi upload_images cho thêm mới
      formData.append("action", "upload_images");
      let additionalData = {};

      if (currentTopicId === "9") {
        const eventId = document.getElementById("event-select").value;
        if (!eventId) {
          showAlert("Vui lòng chọn sự kiện!", "error");
          uploadBtn.disabled = false;
          uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Tải Lên';
          return;
        }
        additionalData.event_id = eventId;
      } else if (currentTopicId === "16") {
        const service = document.getElementById("service-input").value.trim();
        additionalData.service = service;
      }

      Object.keys(additionalData).forEach((key) => {
        formData.append(key, additionalData[key]);
      });

      selectedFiles.forEach((fileObj, index) => {
        formData.append("images[]", fileObj.file);
      });
    }

    formData.append("topic_id", currentTopicId);

    const response = await fetch(
      "/libertylaocai/controller/UserController.php",
      {
        method: "POST",
        body: formData,
      }
    );

    const result = await response.json();

    if (result.success) {
      showAlert(
        result.message ||
          `Đã tải lên thành công ${result.uploaded_count || 1} ảnh!`,
        "success"
      );
      closeUploadModal();
      await loadImages(currentTopicId, currentFilterValue);
    } else {
      showAlert(result.message || "Có lỗi xảy ra khi tải ảnh!", "error");
    }
  } catch (error) {
    console.error("Upload error:", error);
    showAlert("Lỗi khi tải ảnh!", "error");
  } finally {
    uploadBtn.disabled = false;
    uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Tải Lên';
  }
}

document.addEventListener("keydown", function (e) {
  const modal = document.getElementById("image-viewer-modal");
  if (!modal || modal.style.display === "none") return;

  switch (e.key) {
    case "Escape":
      closeImageViewer();
      break;
    case "ArrowLeft":
      previousImage();
      break;
    case "ArrowRight":
      nextImage();
      break;
    case "+":
    case "=":
      zoomIn();
      break;
    case "-":
      zoomOut();
      break;
    case "0":
      resetZoom();
      break;
  }
});

const arrowBtn = document.getElementById("arrow-btn");
const topicsMenu = document.getElementById("topics-menu");

if (arrowBtn && topicsMenu) {
  arrowBtn.addEventListener("click", () => {
    topicsMenu.classList.toggle("collapsed");
    topicsMenu.classList.toggle("expanded");
    arrowBtn.classList.toggle("active");

    if (topicsMenu.classList.contains("expanded")) {
      topicsMenu.style.display = "block";
    } else {
      setTimeout(() => {
        topicsMenu.style.display = "none";
      }, 300);
    }
  });

  if (window.innerWidth <= 768) {
    topicsMenu.classList.add("collapsed");
    topicsMenu.style.display = "none";
  } else {
    topicsMenu.classList.add("expanded");
    topicsMenu.style.display = "block";
  }

  window.addEventListener("resize", () => {
    if (window.innerWidth <= 768) {
      if (!topicsMenu.classList.contains("expanded")) {
        topicsMenu.classList.add("collapsed");
        topicsMenu.style.display = "none";
      }
    } else {
      topicsMenu.classList.remove("collapsed");
      topicsMenu.classList.add("expanded");
      topicsMenu.style.display = "block";
    }
  });
}
