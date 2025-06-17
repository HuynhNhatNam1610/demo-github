let currentPage = {};
let itemsPerPage = 12;
let allItems = {};
let editorVi, editorEn;
let selectedFiles = [];
let existingImages = [];
let isImageUploadListenerAttached = false;

function formatCurrency(value) {
  value = (value != null ? value.toString() : "0").replace(/[^0-9]/g, "");
  return value ? value.replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " VNĐ" : "";
}

function getRawNumber(formattedValue) {
  return formattedValue.replace(/[^0-9]/g, "");
}

function formatDuration(value) {
  value = (value != null ? value.toString() : "").replace(/[^0-9]/g, "");
  return value ? value + "h" : "";
}

function getRawDuration(formattedValue) {
  return formattedValue.replace(/[^0-9]/g, "");
}

function initializeCKEditor(elementId) {
  return ClassicEditor.create(document.querySelector(`#${elementId}`), {
    ckfinder: {
      uploadUrl:
        "/libertylaocai/model/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json",
    },
  });
}

function getNextRoomNumber() {
  return fetch("/libertylaocai/model/config/get_max_room_number.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        return parseInt(data.max_room_number || 0) + 1;
      }
      throw new Error("Lỗi khi lấy số phòng");
    })
    .catch((error) => {
      console.error("Error fetching max room number:", error);
      return 1;
    });
}

function updateFileInput() {
  const imageUpload = document.getElementById("primary-image");
  if (!imageUpload) return;

  const dt = new DataTransfer();
  selectedFiles.forEach((file) => dt.items.add(file));
  imageUpload.files = dt.files;
}

function renderImagePreviews() {
  const uploadArea = document.querySelector(".upload-area");
  const imageUpload = document.getElementById("primary-image");
  let previewContainer = document.getElementById("image-preview-container");

  if (!uploadArea || !imageUpload) return;

  if (!previewContainer) {
    previewContainer = document.createElement("div");
    previewContainer.id = "image-preview-container";
    previewContainer.className = "images-grid";
    uploadArea.appendChild(previewContainer);
  }

  previewContainer.innerHTML = "";
  if (existingImages.length === 0 && selectedFiles.length === 0) {
    uploadArea.innerHTML = `
      <div class="upload-icon">📷</div>
      <div class="upload-text">
        Nhấp để tải lên hình ảnh<br><small>Có thể tải lên nhiều hình ảnh (tối đa 5)</small>
      </div>
    `;
    uploadArea.appendChild(imageUpload);
    uploadArea.appendChild(previewContainer);
  } else {
    uploadArea.innerHTML = `
      <div class="upload-header">
        <span class="upload-count">Đã chọn ${
          existingImages.length + selectedFiles.length
        } hình ảnh</span>
        <button class="add-more-btn" type="button">Thêm ảnh</button>
      </div>
    `;
    uploadArea.appendChild(imageUpload);
    uploadArea.appendChild(previewContainer);

    const addMoreBtn = uploadArea.querySelector(".add-more-btn");
    if (addMoreBtn) {
      addMoreBtn.onclick = () => imageUpload.click();
    }
  }

  existingImages.forEach((image, index) => {
    const previewItem = document.createElement("div");
    previewItem.className = "image-preview-item";

    const img = document.createElement("img");
    img.src = `/libertylaocai/view/img/${image}`;

    const overlay = document.createElement("div");
    overlay.className = "image-overlay";

    const imageName = document.createElement("span");
    imageName.className = "image-name";
    imageName.textContent = image.split("/").pop();

    const removeBtn = document.createElement("button");
    removeBtn.className = "remove-btn";
    removeBtn.innerHTML = "×";
    removeBtn.onclick = (event) => {
      event.stopPropagation();
      existingImages.splice(index, 1);
      renderImagePreviews();
    };

    overlay.appendChild(imageName);
    overlay.appendChild(removeBtn);
    previewItem.appendChild(img);
    previewItem.appendChild(overlay);
    previewContainer.appendChild(previewItem);
  });

  selectedFiles.forEach((file, index) => {
    const previewItem = document.createElement("div");
    previewItem.className = "image-preview-item";

    const img = document.createElement("img");
    img.src = URL.createObjectURL(file);

    const overlay = document.createElement("div");
    overlay.className = "image-overlay";

    const imageName = document.createElement("span");
    imageName.className = "image-name";
    imageName.textContent = file.name;

    const removeBtn = document.createElement("button");
    removeBtn.className = "remove-btn";
    removeBtn.innerHTML = "×";
    removeBtn.onclick = (event) => {
      event.stopPropagation();
      selectedFiles.splice(index, 1);
      updateFileInput();
      renderImagePreviews();
    };

    overlay.appendChild(imageName);
    overlay.appendChild(removeBtn);
    previewItem.appendChild(img);
    previewItem.appendChild(overlay);
    previewContainer.appendChild(previewItem);
  });
}

function attachDurationInputListener(input) {
  input.addEventListener("input", function (e) {
    const rawValue = getRawDuration(e.target.value);
    if (rawValue) {
      e.target.value = formatDuration(rawValue);
    } else {
      e.target.value = "";
    }
  });
}

function attachPriceInputListener(input) {
  input.addEventListener("input", function (e) {
    const rawValue = getRawNumber(e.target.value);
    if (rawValue) {
      e.target.value = formatCurrency(rawValue);
    } else {
      e.target.value = "";
    }
  });
}

function attachImageUploadListener() {
  const imageUpload = document.getElementById("primary-image");
  const uploadArea = document.querySelector(".upload-area");
  if (!imageUpload || !uploadArea) return;

  uploadArea.addEventListener("click", function (e) {
    if (
      e.target.classList.contains("add-more-btn") ||
      e.target === imageUpload
    ) {
      return;
    }
    imageUpload.click();
  });
}

document.addEventListener("DOMContentLoaded", function () {
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("add-price-btn")) {
      const priceList = document.getElementById("price-list");
      const newPriceItem = document.createElement("div");
      newPriceItem.className = "price-item";
      newPriceItem.innerHTML = `
        <input type="text" name="how_long[]" class="duration-input" placeholder="Thời gian (VD: 4h)">
        <input type="text" name="price_value[]" class="price-value-input" placeholder="Giá (VNĐ)">
        <button type="button" class="remove-price-btn"><i class="fas fa-trash"></i></button>
      `;
      priceList.appendChild(newPriceItem);
      attachDurationInputListener(
        newPriceItem.querySelector(".duration-input")
      );
      attachPriceInputListener(
        newPriceItem.querySelector(".price-value-input")
      );
    } else if (e.target.classList.contains("remove-price-btn")) {
      e.target.closest(".price-item").remove();
    }
  });

  document
    .querySelectorAll(".duration-input")
    .forEach(attachDurationInputListener);
  document
    .querySelectorAll(".price-value-input")
    .forEach(attachPriceInputListener);

  const imageUpload = document.getElementById("primary-image");
  if (imageUpload && !isImageUploadListenerAttached) {
    imageUpload.addEventListener("change", function (e) {
      const files = e.target.files;
      if (!files.length) return;

      const maxTotalFiles = 5;
      let validNewFiles = [];

      Array.from(files).forEach((file) => {
        const isDuplicate = selectedFiles.some(
          (existing) =>
            existing.name === file.name && existing.size === file.size
        );
        if (isDuplicate) {
          alert(`Tệp ${file.name} đã được chọn trước đó.`);
          return;
        }
        validNewFiles.push(file);
      });

      if (
        existingImages.length + selectedFiles.length + validNewFiles.length >
        maxTotalFiles
      ) {
        const remainingSlots =
          maxTotalFiles - (existingImages.length + selectedFiles.length);
        if (remainingSlots > 0) {
          alert(
            `Chỉ có thể thêm ${remainingSlots} ảnh nữa. Tối đa ${maxTotalFiles} ảnh.`
          );
          validNewFiles = validNewFiles.slice(0, remainingSlots);
        } else {
          alert(`Đã đạt giới hạn tối đa ${maxTotalFiles} ảnh.`);
          return;
        }
      }

      if (validNewFiles.length > 0) {
        selectedFiles = selectedFiles.concat(validNewFiles);
        updateFileInput();
        renderImagePreviews();
      }
    });
    isImageUploadListenerAttached = true;
  }

  document.getElementById("info-form").addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const type = document.getElementById("post-type").value;

    if (editorVi) formData.set("description_vi", editorVi.getData());
    if (editorEn) formData.set("description_en", editorEn.getData());

    if (type === "conference") {
      document.querySelectorAll('input[name="how_long[]"]').forEach((input) => {
        const rawValue = getRawDuration(input.value);
        input.value = rawValue || "";
        formData.set(input.name, rawValue);
      });

      document
        .querySelectorAll('input[name="price_value[]"]')
        .forEach((input) => {
          const rawValue = getRawNumber(input.value);
          input.value = rawValue || "0";
          formData.set(input.name, rawValue);
        });
    }

    if (type !== "terms" && type !== "introduction" && type !== "description") {
      formData.append("existing_images", JSON.stringify(existingImages));
    }

    const endpoint =
      type === "conference"
        ? "/libertylaocai/model/config/save_conference_room.php"
        : type === "restaurant"
        ? "/libertylaocai/model/config/save_restaurant.php"
        : type === "bar"
        ? "/libertylaocai/model/config/save_bar.php"
        : type === "terms"
        ? "/libertylaocai/model/config/save_terms.php"
        : type === "introduction"
        ? "/libertylaocai/model/config/save_introduction.php"
        : "/libertylaocai/model/config/save_description.php";

    fetch(endpoint, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Lưu thông tin thành công!");
          closeModal();
          selectedFiles = [];
          existingImages = [];
          loadItems(type, 1);
        } else {
          alert("Lỗi: " + data.message);
        }
      })
      .catch((error) => {
        console.error("Lỗi khi lưu thông tin:", error);
        alert("Đã xảy ra lỗi khi lưu thông tin.");
      });
  });

  openTab("conference");
});

function openTab(tabName) {
  document
    .querySelectorAll(".tab-content")
    .forEach((tab) => tab.classList.remove("active"));
  document
    .querySelectorAll(".tab-button")
    .forEach((btn) => btn.classList.remove("active"));
  document.getElementById(tabName).classList.add("active");
  document
    .querySelector(`button[onclick="openTab('${tabName}')"]`)
    .classList.add("active");
  loadItems(tabName, 1);
}

function openAddForm(type) {
  document.getElementById("modal-title").textContent =
    type === "conference"
      ? "Thêm hội trường"
      : type === "introduction"
      ? "Thêm giới thiệu"
      : "Chỉnh sửa thông tin";
  document.getElementById("post-id").value = "";
  document.getElementById("post-type").value = type;
  document.getElementById("item-title-vi").value = "";
  document.getElementById("item-title-en").value = "";
  document.getElementById("item-content-vi").value = "";
  document.getElementById("item-content-en").value = "";
  if (editorVi) editorVi.setData("");
  if (editorEn) editorEn.setData("");
  selectedFiles = [];
  existingImages = [];
  document.getElementById("primary-image").value = "";
  document.getElementById("image-preview-container").innerHTML = "";
  document.getElementById("conference-fields").style.display =
    type === "conference" ? "block" : "none";

  document.getElementById("content-vi-section").style.display =
    type === "conference" || type === "introduction" ? "none" : "block";
  document.getElementById("content-en-section").style.display =
    type === "conference" || type === "introduction" ? "none" : "block";

  const imageUploadSection = document.getElementById("image-upload-section");
  if (imageUploadSection) {
    imageUploadSection.style.display =
      type === "terms" || type === "introduction" ? "none" : "block";
  }

  if (type === "conference") {
    getNextRoomNumber().then((roomNumber) => {
      document.getElementById("room-number").value = roomNumber;
    });
    document.getElementById("price-list").innerHTML = `
      <div class="price-item">
        <input type="text" name="how_long[]" class="duration-input" placeholder="Thời gian (VD: 4h)">
        <input type="text" name="price_value[]" class="price-value-input" placeholder="Giá (VNĐ)">
        <button type="button" class="remove-price-btn"><i class="fas fa-trash"></i></button>
      </div>
    `;
    document
      .querySelectorAll(".duration-input")
      .forEach(attachDurationInputListener);
    document
      .querySelectorAll(".price-value-input")
      .forEach(attachPriceInputListener);
  }

  if (editorVi) editorVi.destroy();
  if (editorEn) editorEn.destroy();
  initializeCKEditor("post-description-vi").then(
    (editor) => (editorVi = editor)
  );
  initializeCKEditor("post-description-en").then(
    (editor) => (editorEn = editor)
  );

  renderImagePreviews();
  attachImageUploadListener();

  document.getElementById("info-modal").style.display = "block";
}

function editItem(id, type) {
  const modal = document.getElementById("info-modal");
  if (!modal) {
    console.error("Modal element not found");
    alert("Lỗi: Không tìm thấy modal");
    return;
  }
  modal.style.display = "block";

  document.getElementById("modal-title").textContent =
    type === "conference"
      ? "Sửa hội trường"
      : type === "restaurant"
      ? "Sửa thông tin Nhà hàng"
      : type === "bar"
      ? "Sửa thông tin Bar"
      : type === "terms"
      ? "Sửa thông tin Điều khoản"
      : type === "introduction"
      ? "Sửa thông tin Giới thiệu"
      : "Sửa thông tin Mô tả";

  const postId =
    type === "restaurant" ? 1 : type === "bar" ? 2 : type === "terms" ? 1 : id;
  document.getElementById("post-id").value = postId;
  document.getElementById("post-type").value = type;

  const fetchId =
    type === "restaurant" ? 1 : type === "bar" ? 2 : type === "terms" ? 1 : id;

  const endpoint =
    type === "conference"
      ? `/libertylaocai/model/config/fetch_conference_room.php?id=${id}`
      : type === "restaurant"
      ? `/libertylaocai/model/config/fetch_restaurant.php?id=${fetchId}`
      : type === "bar"
      ? `/libertylaocai/model/config/fetch_bar.php?id=${fetchId}`
      : type === "terms"
      ? `/libertylaocai/model/config/fetch_terms.php?id=${fetchId}`
      : type === "introduction"
      ? `/libertylaocai/model/config/fetch_introduction.php?id=${id}`
      : `/libertylaocai/model/config/fetch_description.php?id=${id}`;

  fetch(endpoint)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        document.getElementById("item-title-vi").value =
          data.item.title_vi || "";
        document.getElementById("item-title-en").value =
          data.item.title_en || "";

        if (type === "restaurant" || type === "bar") {
          document.getElementById("item-content-vi").value =
            data.item.content_vi || "";
          document.getElementById("item-content-en").value =
            data.item.content_en || "";
          document.getElementById("content-vi-section").style.display = "block";
          document.getElementById("content-en-section").style.display = "block";
        } else {
          document.getElementById("content-vi-section").style.display = "none";
          document.getElementById("content-en-section").style.display = "none";
        }

        if (editorVi) editorVi.destroy();
        if (editorEn) editorEn.destroy();
        initializeCKEditor("post-description-vi").then((editor) => {
          editorVi = editor;
          editorVi.setData(
            data.item.description_vi || data.item.content_vi || ""
          );
        });
        initializeCKEditor("post-description-en").then((editor) => {
          editorEn = editor;
          editorEn.setData(
            data.item.description_en || data.item.content_en || ""
          );
        });

        const imageUploadSection = document.getElementById(
          "image-upload-section"
        );
        if (imageUploadSection) {
          imageUploadSection.style.display =
            type === "terms" ||
            type === "introduction" ||
            type === "description"
              ? "none"
              : "block";
        }

        if (
          type !== "terms" &&
          type !== "introduction" &&
          type !== "description"
        ) {
          selectedFiles = [];
          existingImages = data.item.images || [];
          const previewContainer = document.getElementById(
            "image-preview-container"
          );
          if (!previewContainer) {
            console.error("Image preview container not found");
            alert("Lỗi: Không tìm thấy container hiển thị ảnh");
            return;
          }
          previewContainer.innerHTML = "";
          renderImagePreviews();
          attachImageUploadListener();
        } else {
          selectedFiles = [];
          existingImages = [];
          const previewContainer = document.getElementById(
            "image-preview-container"
          );
          if (previewContainer) {
            previewContainer.innerHTML = "";
          }
        }

        if (type === "conference") {
          document.getElementById("room-number").value =
            data.item.room_number || "";
          const priceList = document.getElementById("price-list");
          if (!priceList) {
            console.error("Price list container not found");
            return;
          }
          priceList.innerHTML = "";
          if (data.item.prices) {
            for (let [how_long, price_value] of Object.entries(
              data.item.prices
            )) {
              const priceItem = document.createElement("div");
              priceItem.className = "price-item";
              priceItem.innerHTML = `
                <input type="text" name="how_long[]" class="duration-input" value="${formatDuration(
                  how_long
                )}">
                <input type="text" name="price_value[]" class="price-value-input" value="${formatCurrency(
                  price_value
                )}">
                <button type="button" class="remove-price-btn"><i class="fas fa-trash"></i></button>
              `;
              priceList.appendChild(priceItem);
              attachDurationInputListener(
                priceItem.querySelector(".duration-input")
              );
              attachPriceInputListener(
                priceItem.querySelector(".price-value-input")
              );
            }
          }
        }
        document.getElementById("conference-fields").style.display =
          type === "conference" ? "block" : "none";
      } else {
        alert("Lỗi: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Lỗi khi tải dữ liệu:", error);
      alert("Đã xảy ra lỗi khi tải dữ liệu.");
    });
}

function searchItems(type) {
  const searchId = `search-${type}`;
  const searchValue = document.getElementById(searchId)
    ? document.getElementById(searchId).value.toLowerCase()
    : "";
  document.querySelectorAll(`#${type}-items .post-card`).forEach((card) => {
    const title = card.querySelector("h3").textContent.toLowerCase();
    const content =
      type === "description"
        ? card.querySelector("p")
          ? card.querySelector("p").textContent.toLowerCase()
          : ""
        : "";
    const shouldDisplay =
      title.includes(searchValue) ||
      (type === "description" && content.includes(searchValue));
    card.style.display = shouldDisplay ? "flex" : "none";
  });
}

function loadItems(type, active, page = 1) {
  let endpoint;
  if (type === "conference") {
    endpoint = `/libertylaocai/model/config/fetch_conference_rooms.php?active=${active}&language=1`;
  } else if (type === "restaurant") {
    endpoint = `/libertylaocai/model/config/fetch_restaurant.php?id=1`;
  } else if (type === "bar") {
    endpoint = `/libertylaocai/model/config/fetch_bar.php?id=2`;
  } else if (type === "terms") {
    endpoint = `/libertylaocai/model/config/fetch_terms.php?id=1`;
  } else if (type === "introduction") {
    endpoint = `/libertylaocai/model/config/fetch_introductions.php?active=${active}&language=1`;
  } else if (type === "description") {
    endpoint = `/libertylaocai/model/config/fetch_descriptions.php`; // Sử dụng fetch_descriptions.php
  } else {
    console.error("Loại tab không hợp lệ:", type);
    return;
  }

  fetch(endpoint)
    .then((response) => {
      if (!response.ok) {
        throw new Error(
          `HTTP error! Status: ${response.status}, URL: ${endpoint}`
        );
      }
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        let items = Array.isArray(data.items) ? data.items : [data.item];
        if (type === "restaurant" || type === "bar" || type === "terms") {
          items = items.map((item) => ({
            id: item.id,
            title: item.title_vi,
            name: item.title_vi,
            description: item.description_vi || item.content_vi || "",
            content: item.content_vi || "",
            images: item.images || [],
          }));
        } else if (type === "introduction" || type === "description") {
          items = items.map((item) => ({
            id: item.id,
            title: item.title_vi,
            name: item.title_vi,
            description: item.content_vi || "",
            content: item.content_vi || "",
            images: item.images || [],
          }));
        }
        allItems[type] = items;
        currentPage[type] = page;
        displayItems(type, active);
      } else {
        const itemList = document.getElementById(`${type}-items`);
        if (itemList)
          itemList.innerHTML = "<p>Không tìm thấy thông tin nào.</p>";
      }
    })
    .catch((error) => {
      console.error("Lỗi khi tải danh sách:", error);
      const itemList = document.getElementById(`${type}-items`);
      if (itemList)
        itemList.innerHTML = "<p>Đã xảy ra lỗi khi tải danh sách.</p>";
    });
}

function displayItems(type, active) {
  const itemList = document.getElementById(`${type}-items`);
  const pagination = document.getElementById(`pagination-${type}`);
  if (!itemList || !pagination) {
    console.warn(`Phần tử không tồn tại cho type: ${type}`);
    return;
  }

  itemList.innerHTML = "";
  const start = (currentPage[type] - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  const paginatedItems = allItems[type].slice(start, end);

  if (paginatedItems.length > 0) {
    paginatedItems.forEach((item) => {
      console.log("Item in displayItems:", item);
      if (!item.id || isNaN(item.id) || item.id <= 0) {
        console.warn("Invalid item.id:", item.id);
        return;
      }

      const description = item.description || item.content || "";
      const tempDiv = document.createElement("div");
      tempDiv.innerHTML = description;
      const shortDesc = tempDiv.textContent || tempDiv.innerText || "";
      const postCard = `
        <div class="post-card" data-post-id="${item.id}">
          <div class="post-image-container">
            <img class="post-image" src="/libertylaocai/view/img/${
              item.images && item.images[0]
                ? item.images[0]
                : "uploads/new/place_holder.jpg"
            }" alt="${item.title || item.name}">
          </div>
          <h3>${item.title || item.name || "Chưa có tiêu đề"}</h3>
          <p>${shortDesc.substring(0, 100)}${
        shortDesc.length > 100 ? "..." : ""
      }</p>
          <div class="post-actions">
            ${
              (type === "conference" || type === "introduction") && active === 0
                ? `<button class="action-btn show-btn" onclick="showItem(${item.id}, '${type}')"><i class="fas fa-eye"></i> Hiển thị lại</button>`
                : type === "conference" || type === "introduction"
                ? `<button class="action-btn hide-btn" onclick="hideItem(${item.id}, '${type}')"><i class="fas fa-eye-slash"></i> Ẩn</button>`
                : ""
            }
            <button class="action-btn edit-btn" onclick="editItem(${
              item.id
            }, '${type}')"><i class="fas fa-edit"></i> Chỉnh sửa</button>
            ${
              type === "conference" || type === "introduction"
                ? `<button class="action-btn delete-btn" onclick="deleteItem(${item.id}, '${type}')"><i class="fas fa-trash"></i> Xóa</button>`
                : ""
            }
          </div>
        </div>
      `;
      itemList.insertAdjacentHTML("beforeend", postCard);
    });
  } else {
    itemList.innerHTML = "<p>Không tìm thấy thông tin nào.</p>";
  }

  const totalPages = Math.ceil(allItems[type].length / itemsPerPage);
  pagination.innerHTML = "";
  if (totalPages <= 5) {
    for (let i = 1; i <= totalPages; i++) {
      const button = document.createElement("button");
      button.textContent = i;
      button.className = `pagination-btn ${
        i === currentPage[type] ? "active" : ""
      }`;
      button.onclick = () => loadItems(type, active, i);
      pagination.appendChild(button);
    }
  } else {
    const maxButtons = 5;
    const half = Math.floor(maxButtons / 2);
    let startPage = Math.max(1, currentPage[type] - half);
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);

    if (endPage - startPage + 1 < maxButtons) {
      startPage = Math.max(1, endPage - maxButtons + 1);
    }

    if (startPage > 2) {
      const button1 = document.createElement("button");
      button1.textContent = 1;
      button1.className = `pagination-btn ${
        1 === currentPage[type] ? "active" : ""
      }`;
      button1.onclick = () => loadItems(type, active, 1);
      pagination.appendChild(button1);

      if (startPage > 3) {
        const ellipsis = document.createElement("span");
        ellipsis.textContent = "...";
        ellipsis.className = "pagination-ellipsis";
        pagination.appendChild(ellipsis);
      }
    }

    for (let i = startPage; i <= endPage; i++) {
      const button = document.createElement("button");
      button.textContent = i;
      button.className = `pagination-btn ${
        i === currentPage[type] ? "active" : ""
      }`;
      button.onclick = () => loadItems(type, active, i);
      pagination.appendChild(button);
    }

    if (endPage < totalPages - 1) {
      if (endPage < totalPages - 2) {
        const ellipsis = document.createElement("span");
        ellipsis.textContent = "...";
        ellipsis.className = "pagination-ellipsis";
        pagination.appendChild(ellipsis);
      }
      const buttonLast = document.createElement("button");
      buttonLast.textContent = totalPages;
      buttonLast.className = `pagination-btn ${
        totalPages === currentPage[type] ? "active" : ""
      }`;
      buttonLast.onclick = () => loadItems(type, active, totalPages);
      pagination.appendChild(buttonLast);
    }
  }
}

function showItem(id, type) {
  if (type !== "conference" && type !== "introduction") {
    return;
  }
  if (!id || isNaN(id) || id <= 0) {
    alert("ID không hợp lệ!");
    return;
  }

  const endpoint =
    type === "introduction"
      ? "/libertylaocai/model/config/show_introduction.php"
      : "/libertylaocai/model/config/show_conference_room.php";

  fetch(endpoint, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id: parseInt(id) }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert(
          type === "introduction"
            ? "Giới thiệu đã được hiển thị lại thành công!"
            : "Hội trường đã được hiển thị lại thành công!"
        );
        const postCard = document.querySelector(
          `#${type}-items .post-card[data-post-id="${id}"]`
        );
        if (postCard) postCard.remove();
        loadItems(type, 0);
      } else {
        alert("Lỗi: " + data.message);
      }
    })
    .catch((error) => {
      console.error(`Lỗi khi hiển thị lại ${type}:`, error);
      alert(`Đã xảy ra lỗi khi hiển thị lại ${type}.`);
    });
}

function hideItem(id, type) {
  if (type !== "conference" && type !== "introduction") {
    return;
  }
  if (!id || isNaN(id) || id <= 0) {
    alert("ID không hợp lệ!");
    return;
  }

  const endpoint =
    type === "introduction"
      ? "/libertylaocai/model/config/hide_introduction.php"
      : "/libertylaocai/model/config/hide_conference_room.php";

  fetch(endpoint, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id: parseInt(id) }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert(
          type === "introduction"
            ? "Giới thiệu đã được ẩn thành công!"
            : "Hội trường đã được ẩn thành công!"
        );
        const postCard = document.querySelector(
          `#${type}-items .post-card[data-post-id="${id}"]`
        );
        if (postCard) postCard.remove();
        loadItems(type, 1);
      } else {
        alert("Lỗi: " + data.message);
      }
    })
    .catch((error) => {
      console.error(`Lỗi khi ẩn ${type}:`, error);
      alert(`Đã xảy ra lỗi khi ẩn ${type}.`);
    });
}

function deleteItem(id, type) {
  if (type !== "conference" && type !== "introduction") {
    return;
  }
  if (!id || isNaN(id) || id <= 0) {
    alert("ID không hợp lệ!");
    return;
  }

  if (
    confirm(
      `Bạn có chắc muốn xóa ${
        type === "introduction" ? "giới thiệu" : "hội trường"
      } này?`
    )
  ) {
    const endpoint =
      type === "introduction"
        ? "/libertylaocai/model/config/delete_introduction.php"
        : "/libertylaocai/model/config/delete_conference_room.php";

    fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: parseInt(id) }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert(
            type === "introduction"
              ? "Giới thiệu đã được xóa thành công!"
              : "Hội trường đã được xóa thành công!"
          );
          const postCard = document.querySelector(
            `#${type}-items .post-card[data-post-id="${id}"]`
          );
          if (postCard) postCard.remove();
          const btn = document.querySelector(`#${type} .toggle-hidden-btn`);
          const active = btn ? (btn.dataset.view === "visible" ? 1 : 0) : 1;
          loadItems(type, active);
        } else {
          alert("Lỗi: " + data.message);
        }
      })
      .catch((error) => {
        console.error(`Lỗi khi xóa ${type}:`, error);
        alert(`Đã xảy ra lỗi khi xóa ${type}.`);
      });
  }
}

function toggleHiddenItems(type) {
  const containerId = type;
  const btn = document.querySelector(`#${containerId} .toggle-hidden-btn`);
  if (!btn) {
    console.warn(
      `Không tìm thấy toggle-hidden-btn cho containerId: ${containerId}`
    );
    return;
  }

  const isHiddenView = btn.dataset.view === "hidden";
  const active = isHiddenView ? 1 : 0;
  btn.innerHTML = active
    ? `<i class="fas fa-eye-slash"></i> Xem ${
        type === "introduction" ? "giới thiệu" : "hội trường"
      } đã ẩn`
    : `<i class="fas fa-eye"></i> Xem ${
        type === "introduction" ? "giới thiệu" : "hội trường"
      } hiển thị`;
  btn.dataset.view = active ? "visible" : "hidden";

  loadItems(type, active, 1);
}

function closeModal() {
  document.getElementById("info-modal").style.display = "none";
  selectedFiles = [];
  existingImages = [];
  const imageUpload = document.getElementById("primary-image");
  if (imageUpload) {
    imageUpload.value = "";
  }
  const previewContainer = document.getElementById("image-preview-container");
  if (previewContainer) {
    previewContainer.innerHTML = "";
  }
}
