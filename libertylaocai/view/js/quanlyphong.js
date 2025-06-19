let selectedRoomImages = [];
let selectedEditRoomImages = [];
let isSubmitting = false;
let editors = {};
let currentImageCount = 0;

// Hàm khởi tạo CKEditor
function initializeCKEditor(textareaId) {
  return ClassicEditor.create(document.querySelector(`#${textareaId}`), {
    ckfinder: {
      uploadUrl:
        "/libertylaocai/model/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json",
    },
    toolbar: [
      "heading",
      "|",
      "bold",
      "italic",
      "link",
      "bulletedList",
      "numberedList",
      "|",
      "imageUpload",
      "blockQuote",
    ],
  })
    .then((editor) => {
      editors[textareaId] = editor;
      console.log(`CKEditor initialized for ${textareaId}`);
      return editor;
    })
    .catch((error) => {
      console.error(`Lỗi khởi tạo CKEditor cho ${textareaId}:`, error);
      showAlert(
        "error",
        `Không thể khởi tạo trình chỉnh sửa cho ${textareaId}`
      );
      return null;
    });
}

// Hàm hủy CKEditor
function destroyCKEditor(textareaId) {
  if (editors[textareaId]) {
    editors[textareaId]
      .destroy()
      .then(() => {
        delete editors[textareaId];
      })
      .catch((error) => {
        console.error(`Lỗi hủy CKEditor cho ${textareaId}:`, error);
      });
  }
}

function formatPriceInput(input) {
  // Lấy giá trị gốc, loại bỏ các ký tự không phải số
  let value = input.value.replace(/[^0-9]/g, "");

  // Định dạng giá trị với dấu chấm phân cách
  if (value) {
    value = Number(value).toLocaleString("vi-VN");
    input.value = `${value} VNĐ`;
  } else {
    input.value = "";
  }
}

function cleanPriceInput(input) {
  // Lưu giá trị gốc (loại bỏ VNĐ và dấu chấm) vào một thuộc tính data
  let value = input.value.replace(/[^0-9]/g, "");
  input.dataset.originalValue = value;
}

// Hàm khởi tạo CKEditor cho modal
function initializeModalEditors(modalId) {
  const editorConfigs = {
    addRoomTypeModal: ["room_type_description_vi", "room_type_description_en"],
    editRoomTypeModal: [
      "edit_room_type_description_vi",
      "edit_room_type_description_en",
    ],
  };

  const textareas = editorConfigs[modalId] || [];
  const promises = textareas.map((textareaId) => {
    if (!editors[textareaId]) {
      return initializeCKEditor(textareaId).then((editor) => {
        if (!editor)
          throw new Error(`Failed to initialize CKEditor for ${textareaId}`);
      });
    }
    return Promise.resolve();
  });

  return Promise.all(promises).catch((error) => {
    console.error(`Lỗi khởi tạo CKEditor cho modal ${modalId}:`, error);
    showAlert("error", "Lỗi khởi tạo trình chỉnh sửa");
  });
}

// Hàm hủy CKEditor trong modal
function destroyModalEditors(modalId) {
  const editorConfigs = {
    addRoomTypeModal: ["room_type_description_vi", "room_type_description_en"],
    editRoomTypeModal: [
      "edit_room_type_description_vi",
      "edit_room_type_description_en",
    ],
  };

  const textareas = editorConfigs[modalId] || [];
  textareas.forEach((textareaId) => destroyCKEditor(textareaId));
}

// Hàm thêm ô input cho loại giường
function addBedTypeInput() {
  const container = document.getElementById("bed-types-container");
  const index = container.children.length;
  const div = document.createElement("div");
  div.className = "price-input-row";
  div.innerHTML = `
    <input type="text" class="form-control bed-name-input" placeholder="Tên giường" name="bed_names[]" value="">
    <input type="number" class="form-control bed-quantity-input" placeholder="Số lượng" name="bed_quantities[]" value="" min="1">
    <button type="button" class="btn btn-danger remove-row-btn" onclick="this.parentElement.remove()">×</button>
  `;
  container.appendChild(div);
}

// Hàm thêm ô input cho tiện ích
function addAmenityInput() {
  const container = document.getElementById("amenities-container");
  const index = container.children.length;
  const div = document.createElement("div");
  div.className = "price-input-row";
  div.innerHTML = `
    <input type="text" class="form-control amenity-name-vi-input" placeholder="Tên tiện ích (VN)" name="amenity_names_vi[]" value="">
    <input type="text" class="form-control amenity-name-en-input" placeholder="Tên tiện ích (EN)" name="amenity_names_en[]" value="">
    <button type="button" class="btn btn-danger remove-row-btn" onclick="this.parentElement.remove()">×</button>
  `;
  container.appendChild(div);
}

// // Hàm xử lý upload ảnh khi thêm loại phòng
// function attachRoomImageUploadListener() {
//   const imageUpload = document.getElementById("room_type_images");
//   const uploadArea = document.querySelector("#addRoomTypeModal .upload-area");
//   if (!imageUpload || !uploadArea) {
//     console.error("Không tìm thấy imageUpload hoặc uploadArea");
//     return;
//   }

//   uploadArea.onclick = (e) => {
//     if (
//       e.target === uploadArea ||
//       e.target.closest(".upload-icon, .upload-text, .add-more-btn")
//     ) {
//       imageUpload.click();
//     }
//   };

//   imageUpload.addEventListener("change", function (e) {
//     const files = e.target.files;
//     if (!files.length) return;

//     const maxTotalFiles = 4;
//     let validNewFiles = Array.from(files).filter((file) => {
//       const isDuplicate = selectedRoomImages.some(
//         (f) => f.name === file.name && f.size === file.size
//       );
//       if (isDuplicate) {
//         showAlert("error", `Tệp ${file.name} đã được chọn.`);
//         return false;
//       }
//       return true;
//     });

//     if (selectedRoomImages.length + validNewFiles.length > maxTotalFiles) {
//       const remainingSlots = maxTotalFiles - selectedRoomImages.length;
//       if (remainingSlots > 0) {
//         showAlert("error", `Chỉ có thể thêm ${remainingSlots} ảnh nữa.`);
//         validNewFiles = validNewFiles.slice(0, remainingSlots);
//       } else {
//         showAlert("error", `Đã đạt giới hạn tối đa ${maxTotalFiles} ảnh.`);
//         return;
//       }
//     }

//     selectedRoomImages = [...selectedRoomImages, ...validNewFiles];
//     updateRoomTypeImageInput();
//     renderRoomTypeImagePreviews();
//     e.target.value = "";
//   });
// }

function updateRoomTypeImageInput() {
  const imageUpload = document.getElementById("room_type_images");
  if (!imageUpload) return;

  const dt = new DataTransfer();
  selectedRoomImages.forEach((file) => dt.items.add(file));
  imageUpload.files = dt.files;
}

function renderRoomTypeImagePreviews() {
  const uploadArea = document.querySelector("#addRoomTypeModal .upload-area");
  const imageUpload = document.getElementById("room_type_images");
  if (!uploadArea || !imageUpload) return;

  const existingInput = uploadArea.querySelector("#room_type_images");

  if (!selectedRoomImages.length) {
    uploadArea.innerHTML = `
            <div class="upload-icon">📷</div>
            <div class="upload-text">
                Nhấp để tải lên ảnh loại phòng<br>
                <small>Có thể tải lên tối đa 5 ảnh</small>
            </div>
        `;
    if (existingInput) {
      uploadArea.appendChild(existingInput);
    }
    uploadArea.style.borderColor = "";
    uploadArea.style.background = "";
    return;
  }

  const previewContainer = document.createElement("div");
  previewContainer.className = "images-grid";

  selectedRoomImages.forEach((file, index) => {
    const previewItem = document.createElement("div");
    previewItem.className = "image-preview-item";

    const img = document.createElement("img");
    img.src = URL.createObjectURL(file);
    img.alt = "Ảnh xem trước";

    const overlay = document.createElement("div");
    overlay.className = "image-overlay";

    const imageName = document.createElement("span");
    imageName.className = "image-name";
    imageName.textContent =
      file.name.length > 10 ? file.name.substring(0, 10) + "..." : file.name;

    const removeBtn = document.createElement("button");
    removeBtn.className = "remove-btn";
    removeBtn.innerHTML = "×";
    removeBtn.onclick = (e) => {
      e.stopPropagation();
      selectedRoomImages.splice(index, 1);
      updateRoomTypeImageInput();
      renderRoomTypeImagePreviews();
    };

    overlay.appendChild(imageName);
    overlay.appendChild(removeBtn);
    previewItem.appendChild(img);
    previewItem.appendChild(overlay);
    previewContainer.appendChild(previewItem);
  });

  const headerDiv = document.createElement("div");
  headerDiv.className = "upload-header";
  headerDiv.innerHTML = `
        <span class="upload-count">Đã chọn ${selectedRoomImages.length} hình ảnh</span>
        <button class="add-more-btn" type="button">Thêm ảnh</button>
    `;

  uploadArea.innerHTML = "";
  uploadArea.appendChild(headerDiv);
  uploadArea.appendChild(previewContainer);

  if (existingInput) {
    uploadArea.appendChild(existingInput);
  }

  uploadArea.style.borderColor = "#004d40";
  uploadArea.style.background = "#f0f8f0";

  const addMoreBtn = uploadArea.querySelector(".add-more-btn");
  if (addMoreBtn) {
    addMoreBtn.onclick = (e) => {
      e.stopPropagation();
      imageUpload.click();
    };
  }
}

function clearRoomTypeImagePreviews() {
  selectedRoomImages = [];
  updateRoomTypeImageInput();
  renderRoomTypeImagePreviews();
}

function attachEditRoomImageUploadListener() {
  const editImageUpload = document.getElementById("edit_room_type_images");
  const editUploadArea = document.querySelector(
    "#editRoomTypeModal .upload-area"
  );
  if (!editImageUpload || !editUploadArea) return;

  editUploadArea.onclick = (e) => {
    if (
      e.target === editUploadArea ||
      e.target.closest(".upload-icon, .upload-text, .add-more-btn")
    ) {
      if (currentImageCount >= 8) {
        showAlert("error", "Đã đạt giới hạn tối đa 8 ảnh.");
        return;
      }
      editImageUpload.click();
    }
  };

  editImageUpload.addEventListener("change", function (e) {
    const files = e.target.files;
    if (!files.length) return;

    // Tính số ảnh còn lại có thể thêm
    const deletedImagesCount = document.querySelectorAll(
      'input[name="delete_images[]"]:checked'
    ).length;
    const remainingSlots = 8 - (currentImageCount - deletedImagesCount);

    if (remainingSlots <= 0) {
      showAlert("error", "Đã đạt giới hạn tối đa 8 ảnh.");
      e.target.value = "";
      return;
    }

    let validNewFiles = Array.from(files).filter((file) => {
      const isDuplicate = selectedEditRoomImages.some(
        (f) => f.name === file.name && f.size === file.size
      );
      if (isDuplicate) {
        showAlert("error", `Tệp ${file.name} đã được chọn.`);
        return false;
      }
      return true;
    });

    if (selectedEditRoomImages.length + validNewFiles.length > remainingSlots) {
      validNewFiles = validNewFiles.slice(
        0,
        remainingSlots - selectedEditRoomImages.length
      );
      showAlert("error", `Chỉ có thể thêm tối đa ${remainingSlots} ảnh nữa.`);
    }

    selectedEditRoomImages = [...selectedEditRoomImages, ...validNewFiles];
    updateEditRoomTypeImageInput();
    renderEditRoomTypeImagePreviews();
    e.target.value = "";
  });
}

function updateEditRoomTypeImageInput() {
  const editImageUpload = document.getElementById("edit_room_type_images");
  if (!editImageUpload) return;

  const dt = new DataTransfer();
  selectedEditRoomImages.forEach((file) => dt.items.add(file));
  editImageUpload.files = dt.files;
}

function renderEditRoomTypeImagePreviews() {
  const editUploadArea = document.querySelector(
    "#editRoomTypeModal .upload-area"
  );
  const editImageUpload = document.getElementById("edit_room_type_images");
  if (!editUploadArea || !editImageUpload) return;

  const existingInput = editUploadArea.querySelector("#edit_room_type_images");

  // Nếu đã đạt giới hạn 8 ảnh, không hiển thị preview
  if (currentImageCount >= 8) {
    updateEditUploadArea();
    return;
  }

  if (!selectedEditRoomImages.length) {
    updateEditUploadArea();
    return;
  }

  const previewContainer = document.createElement("div");
  previewContainer.className = "images-grid";

  selectedEditRoomImages.forEach((file, index) => {
    const previewItem = document.createElement("div");
    previewItem.className = "image-preview-item";

    const img = document.createElement("img");
    img.src = URL.createObjectURL(file);
    img.alt = "Ảnh xem trước";

    const overlay = document.createElement("div");
    overlay.className = "image-overlay";

    const imageName = document.createElement("span");
    imageName.className = "image-name";
    imageName.textContent =
      file.name.length > 10 ? file.name.substring(0, 10) + "..." : file.name;

    const removeBtn = document.createElement("button");
    removeBtn.className = "remove-btn";
    removeBtn.innerHTML = "×";
    removeBtn.onclick = (e) => {
      e.stopPropagation();
      selectedEditRoomImages.splice(index, 1);
      updateEditRoomTypeImageInput();
      renderEditRoomTypeImagePreviews();
    };

    overlay.appendChild(imageName);
    overlay.appendChild(removeBtn);
    previewItem.appendChild(img);
    previewItem.appendChild(overlay);
    previewContainer.appendChild(previewItem);
  });

  const headerDiv = document.createElement("div");
  headerDiv.className = "upload-header";
  headerDiv.innerHTML = `
    <span class="upload-count">Đã chọn ${selectedEditRoomImages.length} hình ảnh mới</span>
    <button class="add-more-btn" type="button">Thêm ảnh</button>
  `;

  editUploadArea.innerHTML = "";
  editUploadArea.appendChild(headerDiv);
  editUploadArea.appendChild(previewContainer);

  if (existingInput) {
    editUploadArea.appendChild(existingInput);
  }

  editUploadArea.style.borderColor = "#004d40";
  editUploadArea.style.background = "#f0f8f0";

  const addMoreBtn = editUploadArea.querySelector(".add-more-btn");
  if (addMoreBtn) {
    addMoreBtn.onclick = (e) => {
      e.stopPropagation();
      if (
        currentImageCount -
          document.querySelectorAll('input[name="delete_images[]"]:checked')
            .length >=
        8
      ) {
        showAlert("error", "Đã đạt giới hạn tối đa 8 ảnh.");
        return;
      }
      editImageUpload.click();
    };
  }
}

function clearEditRoomTypeImagePreviews() {
  selectedEditRoomImages = [];
  updateEditRoomTypeImageInput();
  renderEditRoomTypeImagePreviews();
}

function fetchRoomTypes() {
  fetch("/libertylaocai/user/submit", {
    method: "POST",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "action=fetch_room_types",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        updateRoomTypeList(data.room_types);
      } else {
        showAlert("error", data.message);
      }
    })
    .catch((error) => {
      console.error("Error fetching room types:", error);
      showAlert("error", "Không thể tải danh sách loại phòng.");
    });
}

function updateRoomTypeList(roomTypes) {
  const tbody = document.querySelector(
    "#room-type-management .rooms-table tbody"
  );
  if (!tbody) return;
  tbody.innerHTML = "";

  roomTypes.forEach((type) => {
    const row = document.createElement("tr");
    row.innerHTML = `
            <td><strong>${type.languages[1].name}</strong></td>
            <td>${
              type.languages[1].description.length > 50
                ? type.languages[1].description.substring(0, 50) + "..."
                : type.languages[1].description
            }</td>
            <td>${Number(type.price).toLocaleString("vi-VN")} VNĐ</td>
            <td>${type.area} m²</td>
            <td>${type.quantity}</td>
            <td>${type.image_count}</td>
            <td>
                <button class="btn btn-warning btn-small" onclick='openEditRoomTypeModal(${JSON.stringify(
                  type
                )})'>
                    <i class="fas fa-edit"></i>
                </button>
            </td>
        `;
    tbody.appendChild(row);
  });
}

function submitFormAjax(formData, successCallback) {
  if (isSubmitting) return;
  isSubmitting = true;

  showLoading(true);
  fetch("/libertylaocai/user/submit", {
    method: "POST",
    headers: { "X-Requested-With": "XMLHttpRequest" },
    body: formData,
  })
    .then((response) => {
      if (!response.ok)
        throw new Error(`HTTP error! Status: ${response.status}`);
      return response.json();
    })
    .then((data) => {
      showLoading(false);
      isSubmitting = false;

      if (data.status === "success") {
        showAlert("success", data.message);
        if (data.room_types) updateRoomTypeList(data.room_types);
        if (successCallback) successCallback(data);
      } else {
        showAlert("error", data.message);
      }
    })
    .catch((error) => {
      showLoading(false);
      isSubmitting = false;
      console.error("Error:", error);
      showAlert("error", "Có lỗi xảy ra khi xử lý yêu cầu: " + error.message);
    });
}

function showAlert(type, message) {
  let container = document.querySelector(".toast-container");
  if (!container) {
    container = document.createElement("div");
    container.className = "toast-container";
    document.body.appendChild(container);
  }

  const alertId = `toast-${Date.now()}`;
  const toast = document.createElement("div");
  toast.className = `toast ${type}`;
  toast.id = alertId;
  toast.innerHTML = `
        <i class="fas ${
          type === "success" ? "fa-check-circle" : "fa-exclamation-circle"
        }"></i>
        <div>${message}</div>
        <button class="toast-close" onclick="closeAlert('${alertId}')">×</button>
    `;

  container.appendChild(toast);
  setTimeout(() => closeAlert(alertId), 3000);
}

function closeAlert(alertId) {
  const alert = document.getElementById(alertId);
  if (alert) {
    alert.style.animation = "slideOut 0.3s forwards";
    setTimeout(() => {
      alert.remove();
      const container = document.querySelector(".toast-container");
      if (container && container.children.length === 0) container.remove();
    }, 300);
  }
}

function showLoading(show) {
  let loading = document.getElementById("loading-indicator");
  if (!loading && show) {
    loading = document.createElement("div");
    loading.id = "loading-indicator";
    loading.style.cssText = `
            position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.7); color: white; padding: 15px 30px;
            border-radius: 5px; z-index: 1000;
        `;
    loading.textContent = "Đang tải...";
    document.body.appendChild(loading);
  }
  if (loading) loading.style.display = show ? "block" : "none";
}

function openEditRoomTypeModal(roomType) {
  document.getElementById("edit_room_type_id").value = roomType.id;
  document.getElementById("edit_room_type_name_vi").value =
    roomType.languages[1]?.name || "";
  document.getElementById("edit_room_type_name_en").value =
    roomType.languages[2]?.name || "";
  const priceInput = document.getElementById("edit_room_type_price");
  priceInput.dataset.originalValue = roomType.price || "";
  priceInput.value = roomType.price
    ? `${Number(roomType.price).toLocaleString("vi-VN")} VNĐ`
    : "";
  document.getElementById("edit_room_type_quantity").value =
    roomType.quantity || "";
  document.getElementById("edit_room_type_area").value = roomType.area || "";

  // Lưu số lượng ảnh hiện có
  currentImageCount = roomType.images?.length || 0;

  // Xóa các ô input cũ và thêm lại từ dữ liệu loại giường
  const bedTypesContainer = document.getElementById("bed-types-container");
  bedTypesContainer.innerHTML = "";
  roomType.bed_types?.forEach((bed) => {
    const div = document.createElement("div");
    div.className = "price-input-row";
    div.innerHTML = `
      <input type="text" class="form-control bed-name-input" placeholder="Tên giường" name="bed_names[]" value="${
        bed.name || ""
      }">
      <input type="number" class="form-control bed-quantity-input" placeholder="Số lượng" name="bed_quantities[]" value="${
        bed.quantity || ""
      }" min="1">
      <button type="button" class="btn btn-danger remove-row-btn" onclick="this.parentElement.remove()">×</button>
    `;
    bedTypesContainer.appendChild(div);
  });
  if (!bedTypesContainer.children.length) addBedTypeInput();

  // Xóa các ô input cũ và thêm lại từ dữ liệu tiện ích
  const amenitiesContainer = document.getElementById("amenities-container");
  amenitiesContainer.innerHTML = "";
  roomType.amenities?.forEach((amenity) => {
    const div = document.createElement("div");
    div.className = "price-input-row";
    div.innerHTML = `
      <input type="text" class="form-control amenity-name-vi-input" placeholder="Tên tiện ích (VN)" name="amenity_names_vi[]" value="${
        amenity.name_vi || ""
      }">
      <input type="text" class="form-control amenity-name-en-input" placeholder="Tên tiện ích (EN)" name="amenity_names_en[]" value="${
        amenity.name_en || ""
      }">
      <button type="button" class="btn btn-danger remove-row-btn" onclick="this.parentElement.remove()">×</button>
    `;
    amenitiesContainer.appendChild(div);
  });
  if (!amenitiesContainer.children.length) addAmenityInput();

  // Điền dữ liệu CKEditor sau khi khởi tạo
  setTimeout(() => {
    initializeModalEditors("editRoomTypeModal").then(() => {
      if (editors["edit_room_type_description_vi"]) {
        editors["edit_room_type_description_vi"].setData(
          roomType.languages[1]?.description || ""
        );
      }
      if (editors["edit_room_type_description_en"]) {
        editors["edit_room_type_description_en"].setData(
          roomType.languages[2]?.description || ""
        );
      }
      console.log("CKEditor initialized for editRoomTypeModal");
    });
  }, 300);

  const imagesContainer = document.getElementById("current-images-container");
  imagesContainer.innerHTML = roomType.images?.length
    ? roomType.images
        .map(
          (img) => `
            <div class="image-item">
                <img src="/libertylaocai/view/img/${img.image}" alt="Ảnh phòng">
                <div class="image-actions">
                    <input type="checkbox" name="delete_images[]" value="${img.id}" id="delete_img_${img.id}">
                    <label for="delete_img_${img.id}">Xóa</label>
                </div>
            </div>
          `
        )
        .join("")
    : "<p>Không có ảnh nào</p>";

  clearEditRoomTypeImagePreviews();
  updateEditUploadArea();
  document.getElementById("editRoomTypeModal").style.display = "block";
}

// Hàm cập nhật khu vực tải ảnh dựa trên số lượng ảnh hiện có
function updateEditUploadArea() {
  const editUploadArea = document.querySelector(
    "#editRoomTypeModal .upload-area"
  );
  const editImageUpload = document.getElementById("edit_room_type_images");
  if (!editUploadArea || !editImageUpload) return;

  if (currentImageCount >= 8) {
    editUploadArea.innerHTML = `
      <div class="upload-text">
        <p style="color: red;">Đã đạt giới hạn tối đa 8 ảnh.</p>
      </div>
    `;
    editUploadArea.style.pointerEvents = "none"; // Vô hiệu hóa tương tác
    editUploadArea.style.opacity = "0.6"; // Làm mờ khu vực
  } else {
    editUploadArea.innerHTML = `
      <div class="upload-icon">📷</div>
      <div class="upload-text">
        Nhấp để tải lên ảnh mới<br>
        <small>Có thể tải lên tối đa ${
          8 - currentImageCount
        } ảnh (JPG, PNG)</small>
      </div>
    `;
    editUploadArea.appendChild(editImageUpload);
    editUploadArea.style.pointerEvents = "auto";
    editUploadArea.style.opacity = "1";
  }
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.style.display = "none";
    destroyModalEditors(modalId);
    if (modalId === "addRoomTypeModal") clearRoomTypeImagePreviews();
    if (modalId === "editRoomTypeModal") clearEditRoomTypeImagePreviews();
  }
}

// function confirmDeleteRoomType(roomTypeId, roomTypeName) {
//   if (confirm(`Bạn có chắc chắn muốn xóa loại phòng "${roomTypeName}"?`)) {
//     const formData = new FormData();
//     formData.append("action", "delete_room_type");
//     formData.append("room_type_id", roomTypeId);
//     submitFormAjax(formData);
//   }
// }

document.addEventListener("DOMContentLoaded", () => {
  // attachRoomImageUploadListener();
  attachEditRoomImageUploadListener();
  fetchRoomTypes();

  // Xử lý định dạng giá
  const priceInputs = [
    document.getElementById("room_type_price"),
    document.getElementById("edit_room_type_price"),
  ];

  priceInputs.forEach((input) => {
    if (input) {
      // Định dạng khi người dùng nhập
      input.addEventListener("input", () => {
        formatPriceInput(input);
      });

      // Lưu giá trị gốc khi mất focus
      input.addEventListener("blur", () => {
        cleanPriceInput(input);
      });

      // Định dạng lại khi focus để dễ chỉnh sửa
      input.addEventListener("focus", () => {
        if (input.dataset.originalValue) {
          input.value = input.dataset.originalValue;
        }
      });
    }
  });

  const editRoomTypeForm = document.querySelector("#editRoomTypeForm");
  if (editRoomTypeForm) {
    editRoomTypeForm.addEventListener("submit", (e) => {
      e.preventDefault();
      if (isSubmitting) return;

      const formData = new FormData();
      formData.append("action", "update_room_type");
      formData.append(
        "room_type_id",
        document.getElementById("edit_room_type_id").value
      );
      formData.append(
        "name_vi",
        document.getElementById("edit_room_type_name_vi").value
      );
      formData.append(
        "name_en",
        document.getElementById("edit_room_type_name_en").value
      );
      formData.append(
        "description_vi",
        editors["edit_room_type_description_vi"]
          ? editors["edit_room_type_description_vi"].getData()
          : ""
      );
      formData.append(
        "description_en",
        editors["edit_room_type_description_en"]
          ? editors["edit_room_type_description_en"].getData()
          : ""
      );
      formData.append(
        "quantity",
        document.getElementById("edit_room_type_quantity").value
      );
      formData.append(
        "area",
        document.getElementById("edit_room_type_area").value
      );
      // Sử dụng giá trị gốc
      const priceInput = document.getElementById("edit_room_type_price");
      formData.append(
        "price",
        priceInput.dataset.originalValue ||
          priceInput.value.replace(/[^0-9]/g, "")
      );

      // Thêm dữ liệu loại giường
      document.querySelectorAll(".bed-name-input").forEach((input) => {
        formData.append("bed_names[]", input.value);
      });
      document.querySelectorAll(".bed-quantity-input").forEach((input) => {
        formData.append("bed_quantities[]", input.value);
      });

      // Thêm dữ liệu tiện ích
      document.querySelectorAll(".amenity-name-vi-input").forEach((input) => {
        formData.append("amenity_names_vi[]", input.value);
      });
      document.querySelectorAll(".amenity-name-en-input").forEach((input) => {
        formData.append("amenity_names_en[]", input.value);
      });

      document
        .querySelectorAll('input[name="delete_images[]"]:checked')
        .forEach((cb) => formData.append("delete_images[]", cb.value));

      selectedEditRoomImages.forEach((file) =>
        formData.append("new_images[]", file)
      );

      submitFormAjax(formData, () => {
        closeModal("editRoomTypeModal");
      });
    });
  }

  window.onclick = (event) => {
    document.querySelectorAll(".modal").forEach((modal) => {
      if (event.target === modal) {
        closeModal(modal.id);
      }
    });
  };
});
