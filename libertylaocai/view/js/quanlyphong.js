let selectedRoomImages = [];
let isSubmitting = false;
// Thêm biến toàn cục để lưu trữ ảnh mới khi chỉnh sửa
let selectedEditRoomImages = [];

// Hàm xử lý upload ảnh khi chỉnh sửa
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
      editImageUpload.click();
    }
  };

  editImageUpload.addEventListener("change", function (e) {
    const files = e.target.files;
    if (!files.length) return;

    const maxTotalFiles = 4;
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

    if (selectedEditRoomImages.length + validNewFiles.length > maxTotalFiles) {
      const remainingSlots = maxTotalFiles - selectedEditRoomImages.length;
      if (remainingSlots > 0) {
        showAlert("error", `Chỉ có thể thêm ${remainingSlots} ảnh nữa.`);
        validNewFiles = validNewFiles.slice(0, remainingSlots);
      } else {
        showAlert("error", `Đã đạt giới hạn tối đa ${maxTotalFiles} ảnh.`);
        return;
      }
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

  if (!selectedEditRoomImages.length) {
    editUploadArea.innerHTML = `
            <div class="upload-icon">📷</div>
            <div class="upload-text">
                Nhấp để tải lên ảnh mới<br>
                <small>Có thể tải lên tối đa 4 ảnh</small>
            </div>
        `;
    if (existingInput) {
      editUploadArea.appendChild(existingInput);
    }
    editUploadArea.style.borderColor = "";
    editUploadArea.style.background = "";
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
      editImageUpload.click();
    };
  }
}

function clearEditRoomTypeImagePreviews() {
  selectedEditRoomImages = [];
  updateEditRoomTypeImageInput();
  renderEditRoomTypeImagePreviews();
}

function attachRoomImageUploadListener() {
  const imageUpload = document.getElementById("room_type_images");
  const uploadArea = document.querySelector("#addRoomTypeModal .upload-area");
  if (!imageUpload || !uploadArea) {
    console.error("Không tìm thấy imageUpload hoặc uploadArea");
    return;
  }

  // Chỉ gắn event click một lần cho uploadArea
  uploadArea.onclick = (e) => {
    // Chỉ trigger khi click vào chính uploadArea, không phải các element con
    if (
      e.target === uploadArea ||
      e.target.closest(".upload-icon, .upload-text, .add-more-btn")
    ) {
      imageUpload.click();
    }
  };

  imageUpload.addEventListener("change", function (e) {
    const files = e.target.files;
    if (!files.length) return;

    const maxTotalFiles = 4;
    let validNewFiles = Array.from(files).filter((file) => {
      const isDuplicate = selectedRoomImages.some(
        (f) => f.name === file.name && f.size === file.size
      );
      if (isDuplicate) {
        showAlert("error", `Tệp ${file.name} đã được chọn.`);
        return false;
      }
      return true;
    });

    if (selectedRoomImages.length + validNewFiles.length > maxTotalFiles) {
      const remainingSlots = maxTotalFiles - selectedRoomImages.length;
      if (remainingSlots > 0) {
        showAlert("error", `Chỉ có thể thêm ${remainingSlots} ảnh nữa.`);
        validNewFiles = validNewFiles.slice(0, remainingSlots);
      } else {
        showAlert("error", `Đã đạt giới hạn tối đa ${maxTotalFiles} ảnh.`);
        return;
      }
    }

    selectedRoomImages = [...selectedRoomImages, ...validNewFiles];
    updateRoomTypeImageInput();
    renderRoomTypeImagePreviews();
    e.target.value = "";
  });
}

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
                <small>Có thể tải lên tối đa 4 ảnh</small>
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
      selectedRoomImages.splice(index, 1); // Xóa ảnh khỏi mảng
      updateRoomTypeImageInput(); // Cập nhật input file
      renderRoomTypeImagePreviews(); // Render lại giao diện
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
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const mainContent = document.querySelector(".main-content");
  const overlay = document.querySelector(".sidebar-overlay");
  const body = document.body;

  sidebar.classList.toggle("collapsed");
  sidebar.classList.toggle("active"); // Thêm lớp .active cho sidebar
  mainContent.classList.toggle("collapsed");

  // Xử lý overlay và khóa cuộn trang trên mobile
  if (window.innerWidth <= 991) {
    if (sidebar.classList.contains("active")) {
      overlay.classList.add("active");
      body.classList.add("sidebar-open"); // Khóa cuộn trang
    } else {
      overlay.classList.remove("active");
      body.classList.remove("sidebar-open");
    }
  }
}
function clearRoomTypeImagePreviews() {
  selectedRoomImages = [];
  updateRoomTypeImageInput();
  renderRoomTypeImagePreviews();
}

function updateRoomList(rooms) {
  const tbody = document.querySelector("#room-management .rooms-table tbody");
  if (!tbody) return;
  tbody.innerHTML = "";

  rooms.forEach((room) => {
    const statusText =
      {
        available: "Trống",
        pending: "Đang chờ",
        reserved: "Đã đặt",
        maintenance: "Bảo trì",
      }[room.status] || room.status;

    const row = document.createElement("tr");
    row.innerHTML = `
            <td><input type="checkbox" name="selected_rooms[]" value="${
              room.id
            }"></td>
            <td><strong>${room.room_number}</strong></td>
            <td data-room-type-id="${room.id_loaiphong}">${
      room.room_type_name || "Chưa xác định"
    }</td>
            <td>${Number(room.price).toLocaleString("vi-VN")} VNĐ</td>
            <td>${room.area} m²</td>
            <td>${room.phone || "-"}</td>
            <td><span class="status-badge status-${
              room.status
            }">${statusText}</span></td>
            <td>
                <button class="btn btn-warning btn-small" onclick='openEditModal(${JSON.stringify(
                  room
                )})'>
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-danger btn-small" onclick="confirmDelete(${
                  room.id
                }, '${room.room_number}')">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
    tbody.appendChild(row);
  });
}

function updateStats(stats) {
  if (!stats) return;
  const selectors = {
    total: ".stat-card.total .stat-number",
    available: ".stat-card.available .stat-number",
    reserved: ".stat-card.reserved .stat-number",
    maintenance: ".stat-card.maintenance .stat-number",
    pending: ".stat-card.pending .stat-number",
  };
  Object.entries(selectors).forEach(([key, selector]) => {
    const element = document.querySelector(selector);
    if (element) element.textContent = stats[`${key}_rooms`];
  });
}

function updateRoomTypeStats(roomTypeStats) {
  const tbody = document.querySelector(".room-type-stats .rooms-table tbody");
  if (!tbody) return;
  tbody.innerHTML = "";

  roomTypeStats.forEach((stat) => {
    const usageRate =
      stat.total_quantity > 0
        ? Math.round(
            ((stat.actual_rooms - stat.available_count) / stat.total_quantity) *
              100 *
              10
          ) / 10
        : 0;

    const row = document.createElement("tr");
    row.innerHTML = `
            <td><strong>${stat.name || "Chưa xác định"}</strong></td>
            <td>${stat.total_quantity}</td>
            <td>${stat.actual_rooms}</td>
            <td>${stat.available_count}</td>
            <td>${usageRate}%</td>
        `;
    tbody.appendChild(row);
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
                <button class="btn btn-danger btn-small" onclick="confirmDeleteRoomType(${
                  type.id
                }, '${type.languages[1].name}')">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
    tbody.appendChild(row);
  });

  updateRoomTypeDropdowns(roomTypes);
}
function updateRoomTypeDropdowns(roomTypes) {
  const dropdowns = [
    document.querySelector('#addModal select[name="id_loaiphong"]'),
    document.querySelector('#editModal select[name="id_loaiphong"]'),
    document.querySelector('select[onchange="filterRoomsByType(this.value)"]'),
  ];

  dropdowns.forEach((dropdown) => {
    if (dropdown) {
      const currentValue = dropdown.value;
      dropdown.innerHTML = '<option value="">Chọn loại phòng...</option>';
      roomTypes.forEach((type) => {
        const option = document.createElement("option");
        option.value = type.id;
        option.textContent = `${type.languages[1].name} - ${Number(
          type.price
        ).toLocaleString("vi-VN")} VNĐ (Số lượng: ${type.quantity})`;
        dropdown.appendChild(option);
      });
      dropdown.value = currentValue || "";
    }
  });
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
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.text(); // Lấy text trước để debug
    })
    .then((text) => {
      console.log("Raw response:", text); // Debug phản hồi thô
      try {
        return JSON.parse(text); // Thử parse JSON
      } catch (e) {
        throw new Error("Invalid JSON: " + text);
      }
    })
    .then((data) => {
      showLoading(false);
      isSubmitting = false;

      if (data.status === "success") {
        showAlert("success", data.message);
        if (data.rooms) updateRoomList(data.rooms);
        if (data.stats) updateStats(data.stats);
        if (data.room_type_stats) updateRoomTypeStats(data.room_type_stats);
        if (data.room_types) updateRoomTypeList(data.room_types);

        const action = formData.get("action");
        if (action === "add_room" || action === "add_room_type") {
          resetCurrentForm(action);
        }

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

// Hàm reset form dựa trên action
function resetCurrentForm(action) {
  switch (action) {
    case "add_room":
      document.getElementById("addRoomForm").reset();
      break;
    case "add_room_type":
      document.getElementById("addRoomTypeForm").reset();
      clearRoomTypeImagePreviews();
      break;
    // Thêm các case khác nếu cần
  }
}
function showAlert(type, message) {
  // Tạo container nếu chưa có
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

  // Tự động đóng sau 3 giây
  setTimeout(() => {
    closeAlert(alertId);
  }, 3000);
}

function closeAlert(alertId) {
  const alert = document.getElementById(alertId);
  if (alert) {
    alert.style.animation = "slideOut 0.3s forwards";
    setTimeout(() => {
      alert.remove();
      // Xóa container nếu không còn toast nào
      const container = document.querySelector(".toast-container");
      if (container && container.children.length === 0) {
        container.remove();
      }
    }, 300);
  }
}
// Biến lưu trữ các bộ lọc hiện tại
let currentFilters = {
  status: "",
  typeId: "",
  searchText: "",
};

// Hàm lọc phòng kết hợp tất cả điều kiện
function applyAllFilters() {
  const searchValue = currentFilters.searchText.toLowerCase();
  const statusFilter = currentFilters.status;
  const typeFilter = currentFilters.typeId;

  document
    .querySelectorAll("#room-management .rooms-table tbody tr")
    .forEach((row) => {
      const roomNumber = row
        .querySelector("td:nth-child(2)")
        .textContent.toLowerCase();
      const phone = row
        .querySelector("td:nth-child(6)")
        .textContent.toLowerCase();
      const statusBadge = row.querySelector(".status-badge");
      const roomTypeId =
        row.querySelector("td:nth-child(3)").dataset.roomTypeId;

      // Kiểm tra tất cả điều kiện
      const matchesSearch =
        !searchValue ||
        roomNumber.includes(searchValue) ||
        phone.includes(searchValue);
      const matchesStatus =
        !statusFilter ||
        statusBadge.classList.contains(`status-${statusFilter}`);
      const matchesType = !typeFilter || roomTypeId === typeFilter;

      row.style.display =
        matchesSearch && matchesStatus && matchesType ? "" : "none";
    });
}

// Hàm lọc theo trạng thái
function filterRooms(status) {
  currentFilters.status = status;
  applyAllFilters();
}

// Hàm lọc theo loại phòng
function filterRoomsByType(typeId) {
  currentFilters.typeId = typeId;
  applyAllFilters();
}

// Hàm tìm kiếm phòng
function searchRooms() {
  currentFilters.searchText = document.getElementById("searchRoom").value;
  applyAllFilters();
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

document.addEventListener("DOMContentLoaded", () => {
  attachRoomImageUploadListener();
  attachEditRoomImageUploadListener();
  const addRoomForm = document.querySelector("#addRoomForm");
  if (addRoomForm) {
    addRoomForm.addEventListener("submit", (e) => {
      e.preventDefault();
      if (isSubmitting) return;
      submitFormAjax(new FormData(addRoomForm), () => {
        closeModal("addModal");
        // Không reset form ở đây nữa vì đã xử lý trong submitFormAjax
      });
    });
  }

  const editRoomForm = document.querySelector("#editRoomForm");
  if (editRoomForm) {
    editRoomForm.addEventListener("submit", (e) => {
      e.preventDefault();
      if (isSubmitting) return;

      const status = document.getElementById("edit_status").value;
      const phone = document.getElementById("edit_phone").value;

      // Kiểm tra nếu là trạng thái reserved và không có số điện thoại
      if (status === "reserved" && !phone) {
        showAlert(
          "error",
          'Vui lòng nhập số điện thoại khi chuyển sang trạng thái "Đã đặt"'
        );
        return;
      }

      submitFormAjax(new FormData(editRoomForm), () => closeModal("editModal"));
    });
  }

  const addRoomTypeForm = document.querySelector("#addRoomTypeForm");
  if (addRoomTypeForm) {
    addRoomTypeForm.addEventListener("submit", (e) => {
      e.preventDefault();
      if (isSubmitting) return;

      if (!selectedRoomImages.length) {
        showAlert("error", "Vui lòng chọn ít nhất một ảnh loại phòng.");
        return;
      }

      const formData = new FormData();
      formData.append("action", "add_room_type");
      formData.append(
        "name_vi",
        document.getElementById("room_type_name_vi").value
      );
      formData.append(
        "name_en",
        document.getElementById("room_type_name_en").value
      );
      formData.append(
        "description_vi",
        document.getElementById("room_type_description_vi").value
      );
      formData.append(
        "description_en",
        document.getElementById("room_type_description_en").value
      );
      formData.append(
        "quantity",
        document.getElementById("room_type_quantity").value
      );
      formData.append("area", document.getElementById("room_type_area").value);
      formData.append(
        "price",
        document.getElementById("room_type_price").value
      );

      selectedRoomImages.forEach((file) => formData.append("images[]", file));

      submitFormAjax(formData, () => {
        closeModal("addRoomTypeModal");
        clearRoomTypeImagePreviews();
      });
    });
  }

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
        document.getElementById("edit_room_type_description_vi").value
      );
      formData.append(
        "description_en",
        document.getElementById("edit_room_type_description_en").value
      );
      formData.append(
        "quantity",
        document.getElementById("edit_room_type_quantity").value
      );
      formData.append(
        "area",
        document.getElementById("edit_room_type_area").value
      );
      formData.append(
        "price",
        document.getElementById("edit_room_type_price").value
      );

      // Thêm các ảnh cần xóa
      document
        .querySelectorAll('input[name="delete_images[]"]:checked')
        .forEach((cb) => {
          formData.append("delete_images[]", cb.value);
        });

      // Thêm các ảnh mới
      selectedEditRoomImages.forEach((file) => {
        formData.append("new_images[]", file);
      });

      submitFormAjax(formData, () => {
        closeModal("editRoomTypeModal");
        clearEditRoomTypeImagePreviews();
      });
    });
  }
});

function openAddModal() {
  document.getElementById("addModal").style.display = "block";
}
function openEditModal(room) {
  document.getElementById("edit_room_id").value = room.id;
  document.getElementById("edit_room_number").value = room.room_number;
  document.getElementById("edit_id_loaiphong").value = room.id_loaiphong;
  document.getElementById("edit_status").value = room.status;
  document.getElementById("edit_phone").value = room.phone || "";

  // Thêm sự kiện change cho trạng thái
  const statusSelect = document.getElementById("edit_status");
  const phoneInput = document.getElementById("edit_phone");

  statusSelect.onchange = function () {
    if (this.value === "reserved") {
      phoneInput.required = true;
    } else {
      phoneInput.required = false;
      phoneInput.value = ""; // Xóa số điện thoại khi không phải trạng thái reserved
    }
  };

  // Kích hoạt kiểm tra ban đầu
  if (statusSelect.value === "reserved") {
    phoneInput.required = true;
  } else {
    phoneInput.required = false;
  }

  document.getElementById("editModal").style.display = "block";
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.style.display = "none";
  if (modalId === "addRoomTypeModal") clearRoomTypeImagePreviews();
}

function confirmDelete(roomId, roomNumber) {
  if (confirm(`Bạn có chắc chắn muốn xóa phòng ${roomNumber}?`)) {
    const formData = new FormData();
    formData.append("action", "delete_room");
    formData.append("room_id", roomId);
    submitFormAjax(formData);
  }
}

function openTab(tabId) {
  document
    .querySelectorAll(".tab-content")
    .forEach((content) => (content.style.display = "none"));
  document
    .querySelectorAll(".tab-btn")
    .forEach((btn) => btn.classList.remove("active"));
  document.getElementById(tabId).style.display = "block";
  event.currentTarget.classList.add("active");
  if (tabId === "room-type-management") fetchRoomTypes();
}

function openAddRoomTypeModal() {
  clearRoomTypeImagePreviews();
  document.getElementById("addRoomTypeModal").style.display = "block";
}

function openEditRoomTypeModal(roomType) {
  document.getElementById("edit_room_type_id").value = roomType.id;
  document.getElementById("edit_room_type_name_vi").value =
    roomType.languages[1]?.name || "";
  document.getElementById("edit_room_type_description_vi").value =
    roomType.languages[1]?.description || "";
  document.getElementById("edit_room_type_name_en").value =
    roomType.languages[2]?.name || "";
  document.getElementById("edit_room_type_description_en").value =
    roomType.languages[2]?.description || "";
  document.getElementById("edit_room_type_price").value = roomType.price || "";
  document.getElementById("edit_room_type_quantity").value =
    roomType.quantity || "";
  document.getElementById("edit_room_type_area").value = roomType.area || "";

  // Xử lý hiển thị ảnh hiện tại
  const container = document.getElementById("current-images-container");
  container.innerHTML = roomType.images?.length
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

  // Reset ảnh mới
  clearEditRoomTypeImagePreviews();

  document.getElementById("editRoomTypeModal").style.display = "block";
}
function confirmDeleteRoomType(roomTypeId, roomTypeName) {
  if (confirm(`Bạn có chắc chắn muốn xóa loại phòng "${roomTypeName}"?`)) {
    const formData = new FormData();
    formData.append("action", "delete_room_type");
    formData.append("room_type_id", roomTypeId);
    submitFormAjax(formData);
  }
}

function filterRoomsByType(typeId) {
  document
    .querySelectorAll("#room-management .rooms-table tbody tr")
    .forEach((row) => {
      const roomTypeId =
        row.querySelector("td:nth-child(3)").dataset.roomTypeId;
      row.style.display = !typeId || roomTypeId === typeId ? "" : "none";
    });
}

function bulkUpdateStatus() {
  const status = document.getElementById("bulkStatus")?.value;
  if (!status) {
    showAlert("error", "Vui lòng chọn trạng thái!");
    return;
  }

  const checkedBoxes = document.querySelectorAll(
    'input[name="selected_rooms[]"]:checked'
  );
  if (!checkedBoxes.length) {
    showAlert("error", "Vui lòng chọn ít nhất một phòng!");
    return;
  }

  if (
    confirm(
      `Bạn có chắc chắn muốn thay đổi trạng thái của ${checkedBoxes.length} phòng?`
    )
  ) {
    const formData = new FormData();
    formData.append("action", "bulk_update_status");
    formData.append("status", status);
    checkedBoxes.forEach((cb) => formData.append("room_ids[]", cb.value));
    submitFormAjax(formData);
  }
}

function toggleSelectAll() {
  const selectAll = document.getElementById("selectAll");
  document.querySelectorAll('input[name="selected_rooms[]"]').forEach((cb) => {
    cb.checked = selectAll.checked;
  });
}

function previewImage(input, previewId) {
  const preview = document.getElementById(previewId);
  if (!preview) return;
  preview.innerHTML = "";
  if (input.files?.[0]) {
    const img = document.createElement("img");
    img.src = URL.createObjectURL(input.files[0]);
    img.alt = "Ảnh xem trước";
    img.style.maxWidth = "100px";
    img.style.maxHeight = "100px";
    preview.appendChild(img);
  }
}
function searchRooms() {
  const searchValue = document.getElementById("searchRoom").value.toLowerCase();
  document
    .querySelectorAll("#room-management .rooms-table tbody tr")
    .forEach((row) => {
      const roomNumber = row
        .querySelector("td:nth-child(2)")
        .textContent.toLowerCase();
      const phone = row
        .querySelector("td:nth-child(6)")
        .textContent.toLowerCase();
      row.style.display =
        !searchValue ||
        roomNumber.includes(searchValue) ||
        phone.includes(searchValue)
          ? ""
          : "none";
    });
}
window.onclick = (event) => {
  document.querySelectorAll(".modal").forEach((modal) => {
    if (event.target === modal) {
      modal.style.display = "none";
      if (modal.id === "addRoomTypeModal") clearRoomTypeImagePreviews();
    }
  });
};
