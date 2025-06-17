let editorVi = null;
let editorEn = null;
function initializeEventListeners() {
  const tourInfoForm = document.getElementById("tourInfoForm");
  const includesForm = document.getElementById("includesForm");
  const addImageForm = document.getElementById("addImageForm");
  const descriptionForm = document.getElementById("descriptionForm");

  if (tourInfoForm) {
    tourInfoForm.addEventListener("submit", handleTourInfoSubmit);
  }
  if (includesForm) {
    includesForm.addEventListener("submit", handleIncludesSubmit);
  }
  if (addImageForm) {
    addImageForm.addEventListener("submit", handleImageSubmit);
  }
  if (descriptionForm) {
    descriptionForm.addEventListener("submit", handleDescriptionSubmit);
  }

  setTimeout(hideToast, 5000);
}

function initializeCKEditor() {
  // Khởi tạo CKEditor cho content_vi
  ClassicEditor.create(document.querySelector("#content_vi"), {
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
      editorVi = editor;
      console.log("CKEditor initialized for content_vi");
    })
    .catch((error) => {
      console.error("Lỗi khởi tạo CKEditor tiếng Việt:", error);
      showToast("Lỗi khởi tạo trình chỉnh sửa tiếng Việt", "error");
    });

  // Khởi tạo CKEditor cho content_en
  ClassicEditor.create(document.querySelector("#content_en"), {
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
      editorEn = editor;
      console.log("CKEditor initialized for content_en");
    })
    .catch((error) => {
      console.error("Lỗi khởi tạo CKEditor tiếng Anh:", error);
      showToast("Lỗi khởi tạo trình chỉnh sửa tiếng Anh", "error");
    });
}

function toggleForm(formId) {
  const form = document.getElementById(formId);
  if (form) {
    const isVisible = form.style.display !== "none";
    form.style.display = isVisible ? "none" : "block";
    if (!isVisible) {
      const formElement = form.querySelector("form");
      if (formElement) {
        formElement.reset();
      }
      const firstInput = form.querySelector('input[type="text"], textarea');
      if (firstInput) {
        setTimeout(() => firstInput.focus(), 100);
      }
    }
  }
}

async function handleTourInfoSubmit(e) {
  e.preventDefault();
  const formData = new FormData(e.target);
  try {
    showLoading();
    const response = await fetch("/libertylaocai/user/submit", {
      method: "POST",
      body: formData,
    });
    const result = await response.json();
    if (result.success) {
      showToast(result.message, "success");
      setTimeout(() => {
        window.location.reload();
      }, 1500);
    } else {
      showToast(result.message, "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Có lỗi xảy ra khi cập nhật thông tin tour", "error");
  } finally {
    hideLoading();
  }
}

async function handleIncludesSubmit(e) {
  e.preventDefault();
  const formData = new FormData(e.target);
  try {
    showLoading();
    const response = await fetch("/libertylaocai/user/submit", {
      method: "POST",
      body: formData,
    });
    const result = await response.json();
    if (result.success) {
      showToast(result.message, "success");
    } else {
      showToast(result.message, "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Có lỗi xảy ra khi cập nhật thông tin", "error");
  } finally {
    hideLoading();
  }
}

async function deleteImage(imageId, imageName) {
  if (!confirm("Bạn có chắc chắn muốn xóa ảnh này?")) {
    return;
  }
  const formData = new FormData();
  formData.append("action", "delete_image");
  formData.append("id_image", imageId);
  formData.append("image_name", imageName);
  try {
    showLoading();
    const response = await fetch("/libertylaocai/user/submit", {
      method: "POST",
      body: formData,
    });
    const result = await response.json();
    if (result.success) {
      showToast(result.message, "success");
      setTimeout(() => {
        window.location.reload();
      }, 1500);
    } else {
      showToast(result.message, "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Có lỗi xảy ra khi xóa ảnh", "error");
  } finally {
    hideLoading();
  }
}

function showLoading() {
  const overlay = document.getElementById("loadingOverlay");
  if (overlay) {
    overlay.classList.add("show");
  }
}

function hideLoading() {
  const overlay = document.getElementById("loadingOverlay");
  if (overlay) {
    overlay.classList.remove("show");
  }
}

function showToast(message, type = "success") {
  const toast = document.getElementById("toast");
  const messageElement = toast.querySelector(".toast-message");
  const iconElement = toast.querySelector("i");
  if (toast && messageElement) {
    messageElement.textContent = message;
    toast.classList.remove("error", "success");
    if (type === "error") {
      toast.classList.add("error");
      iconElement.className = "fas fa-exclamation-circle";
    } else {
      toast.classList.add("success");
      iconElement.className = "fas fa-check-circle";
    }
    toast.classList.add("show");
    setTimeout(() => {
      hideToast();
    }, 4000);
  }
}

function hideToast() {
  const toast = document.getElementById("toast");
  if (toast) {
    toast.classList.remove("show");
  }
}

function formatPriceInput(input) {
  let value = input.value.trim();

  // Kiểm tra nếu giá trị chỉ chứa số (có thể có dấu phẩy hoặc dấu chấm)
  if (/^\d*[.,]?\d*$/.test(value)) {
    // Loại bỏ các ký tự không phải số
    value = value.replace(/[^0-9]/g, "");
    if (value) {
      value = parseInt(value).toLocaleString("vi-VN");
      input.value = value;
    }
  }
  // Nếu không phải số, giữ nguyên giá trị người dùng nhập
}

function autoResizeTextarea(textarea) {
  textarea.style.height = "auto";
  textarea.style.height = textarea.scrollHeight + "px";
}

document.addEventListener("DOMContentLoaded", function () {
  initializeEventListeners();
  initializeCKEditor(); // Khởi tạo CKEditor khi tải trang
  attachImageUploadListener();

  const tourItems = document.querySelectorAll(".tour-item");
  tourItems.forEach((item) => {
    item.style.cursor = "pointer";
    item.addEventListener("click", function () {
      const form = item.closest("form");
      if (form) {
        form.submit();
      }
    });
  });

  const textareas = document.querySelectorAll("textarea");
  textareas.forEach((textarea) => {
    textarea.addEventListener("input", function () {
      autoResizeTextarea(this);
    });
    autoResizeTextarea(textarea);
  });

  const priceInputs = document.querySelectorAll('input[name="price"]');
  priceInputs.forEach((input) => {
    input.addEventListener("input", function () {
      if (this.value !== "Liên hệ") {
        formatPriceInput(this);
      }
    });
  });
});

document.addEventListener("keydown", function (e) {
  if ((e.ctrlKey || e.metaKey) && e.key === "s") {
    e.preventDefault();
    const forms = document.querySelectorAll("form");
    forms.forEach((form) => {
      if (form.style.display !== "none") {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
          submitBtn.click();
        }
      }
    });
  }
  if (e.key === "Escape") {
    const visibleForms = ["highlightForm", "scheduleForm", "scheduleModal"];
    visibleForms.forEach((formId) => {
      const form = document.getElementById(formId);
      if (formId === "scheduleModal") {
        if (form && form.style.display === "grid") {
          closeModal(formId);
        }
      } else if (form && form.style.display !== "none") {
        toggleForm(formId);
      }
    });
  }
});

function scrollToSection(sectionId) {
  const section = document.getElementById(sectionId);
  if (section) {
    section.scrollIntoView({
      behavior: "smooth",
      block: "center",
    });
  }
}

function validateScheduleForm(formData) {
  const time = formData.get("time");
  const title = formData.get("title").trim();
  const content = formData.get("content").trim();
  if (!time) {
    showToast("Vui lòng chọn thời gian", "error");
    return false;
  }
  if (!title) {
    showToast("Vui lòng nhập tiêu đề lịch trình", "error");
    return false;
  }
  if (!content) {
    showToast("Vui lòng nhập nội dung lịch trình", "error");
    return false;
  }
  const selectedTime = new Date(time);
  const now = new Date();
  if (selectedTime < now) {
    if (!confirm("Thời gian đã chọn là trong quá khứ. Bạn có muốn tiếp tục?")) {
      return false;
    }
  }
  return true;
}

function setButtonLoading(button, loading = true) {
  if (loading) {
    button.disabled = true;
    const originalText = button.innerHTML;
    button.dataset.originalText = originalText;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
  } else {
    button.disabled = false;
    if (button.dataset.originalText) {
      button.innerHTML = button.dataset.originalText;
    }
  }
}

// window.addEventListener("error", function (e) {
//   console.error("JavaScript Error:", e.error);
//   showToast("Có lỗi xảy ra trong ứng dụng", "error");
// });

window.addEventListener("unhandledrejection", function (e) {
  console.error("Unhandled Promise Rejection:", e.reason);
  showToast("Có lỗi xảy ra khi xử lý yêu cầu", "error");
});

function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.style.display = "grid";
    document.body.style.overflow = "hidden";
    if (modalId === "highlightModal") {
      const form = document.getElementById("highlightForm");
      if (form) {
        form.reset();
        document.getElementById("highlightModalTitle").textContent =
          "Thêm Điểm Nổi Bật Mới";
        document.getElementById("highlightAction").value = "add_highlight";
        document.getElementById("highlightId").value = "";
        document.getElementById("highlightIconSelect").value = "";
        document.getElementById("highlightIconCustom").style.display = "none";
        document.getElementById("highlightIcon").value = "";
        document.getElementById("iconPreview").innerHTML = "";
      }
    }
  }
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.style.display = "none";
    document.body.style.overflow = "auto";
  }
}

async function handleDescriptionSubmit(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);

  // Đồng bộ dữ liệu từ CKEditor
  if (editorVi) {
    const contentVi = editorVi.getData();
    formData.set("content_vi", contentVi);
    document.querySelector("#content_vi").value = contentVi;
  }
  if (editorEn) {
    const contentEn = editorEn.getData();
    formData.set("content_en", contentEn);
    document.querySelector("#content_en").value = contentEn;
  }

  // Log FormData để gỡ lỗi
  for (let [key, value] of formData.entries()) {
    console.log(`FormData: ${key} = ${value}`);
  }

  try {
    showLoading();
    const response = await fetch("/libertylaocai/user/submit", {
      method: "POST",
      body: formData,
    });
    const result = await response.json();
    console.log("Server response:", result);
    if (result.success) {
      showToast(result.message || "Cập nhật mô tả tour thành công!", "success");
    } else {
      showToast(result.message || "Lỗi khi cập nhật mô tả tour", "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Có lỗi xảy ra khi cập nhật mô tả tour", "error");
  } finally {
    hideLoading();
  }
}

let selectedFiles = [];

function attachImageUploadListener() {
  const imageUpload = document.getElementById("imageUpload");
  const uploadArea = document.querySelector(".upload-area");
  if (!imageUpload || !uploadArea) {
    console.log("Không tìm thấy imageUpload hoặc uploadArea");
    return;
  }

  const uploadText = uploadArea.querySelector(".upload-text");
  if (uploadText) {
    uploadText.onclick = function () {
      imageUpload.click();
    };
  }

  imageUpload.addEventListener("change", function (e) {
    const files = e.target.files;
    if (!files.length) return;

    const maxTotalFiles = 5;
    let validNewFiles = [];

    Array.from(files).forEach((file) => {
      const isDuplicate = selectedFiles.some(
        (existingFile) =>
          existingFile.name === file.name && existingFile.size === file.size
      );

      if (isDuplicate) {
        showToast(`Tệp ${file.name} đã được chọn trước đó.`, "error");
        return;
      }

      if (file.size > 5 * 1024 * 1024) {
        showToast(`Tệp ${file.name} quá lớn (tối đa 5MB).`, "error");
        return;
      }

      if (!file.type.includes("image")) {
        showToast(`Tệp ${file.name} không phải là hình ảnh.`, "error");
        return;
      }

      validNewFiles.push(file);
    });

    if (selectedFiles.length + validNewFiles.length > maxTotalFiles) {
      const remainingSlots = maxTotalFiles - selectedFiles.length;
      if (remainingSlots > 0) {
        showToast(
          `Chỉ có thể thêm ${remainingSlots} ảnh nữa. Tối đa ${maxTotalFiles} ảnh.`,
          "error"
        );
        validNewFiles = validNewFiles.slice(0, remainingSlots);
      } else {
        showToast(`Đã đạt giới hạn tối đa ${maxTotalFiles} ảnh.`, "error");
        return;
      }
    }

    if (validNewFiles.length > 0) {
      selectedFiles = selectedFiles.concat(validNewFiles);
      updateFileInput();
      renderImagePreviews();
    }

    e.target.value = "";
  });
}

function clearImagePreviews() {
  const uploadArea = document.querySelector(".upload-area");
  const imageUpload = document.getElementById("imageUpload");
  if (!uploadArea || !imageUpload) return;

  selectedFiles = [];
  uploadArea.innerHTML = `
        <div class="upload-icon">📷</div>
        <div class="upload-text">
            Nhấp để tải lên hình ảnh<br>
            <small>Có thể tải lên nhiều hình ảnh (tối đa 5)</small>
        </div>
    `;
  uploadArea.appendChild(imageUpload);
  uploadArea.style.borderColor = "";
  uploadArea.style.background = "";
  attachImageUploadListener();
}

function updateFileInput() {
  const imageUpload = document.getElementById("imageUpload");
  if (!imageUpload) return;

  const dt = new DataTransfer();
  selectedFiles.forEach((file) => {
    dt.items.add(file);
  });
  imageUpload.files = dt.files;
}

function renderImagePreviews() {
  const uploadArea = document.querySelector(".upload-area");
  const imageUpload = document.getElementById("imageUpload");
  if (!uploadArea || !imageUpload) return;

  if (selectedFiles.length === 0) {
    clearImagePreviews();
    return;
  }

  const previewContainer = document.createElement("div");
  previewContainer.className = "images-grid";
  previewContainer.style.marginTop = "10px";

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
    removeBtn.onclick = function (e) {
      e.stopPropagation();
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

  uploadArea.innerHTML = `
        <div class="upload-header">
            <span class="upload-count">Đã chọn ${selectedFiles.length} hình ảnh</span>
            <button class="add-more-btn" type="button">Thêm ảnh</button>
        </div>
    `;
  uploadArea.appendChild(previewContainer);
  uploadArea.appendChild(imageUpload);
  uploadArea.style.borderColor = "#004d40";
  uploadArea.style.background = "#f0f8f0";

  const addMoreBtn = uploadArea.querySelector(".add-more-btn");
  if (addMoreBtn) {
    addMoreBtn.onclick = function () {
      imageUpload.click();
    };
  }
}

async function handleImageSubmit(e) {
  e.preventDefault();
  const form = e.target;
  const isPrimaryCheckbox = form.querySelector('input[name="is_primary"]');
  if (!selectedFiles.length) {
    showToast("Vui lòng chọn ít nhất một file ảnh", "error");
    return;
  }

  const formData = new FormData();
  formData.append("action", "add_image");
  formData.append(
    "id_dichvu",
    form.querySelector('input[name="id_dichvu"]').value
  );
  formData.append(
    "id_topic",
    form.querySelector('input[name="id_topic"]').value
  );
  formData.append("is_primary", isPrimaryCheckbox.checked ? "1" : "0");

  selectedFiles.forEach((file) => {
    formData.append("images[]", file);
  });

  const button = form.querySelector('button[type="submit"]');
  try {
    setButtonLoading(button, true);
    showLoading();
    const response = await fetch("/libertylaocai/user/submit", {
      method: "POST",
      body: formData,
    });
    const result = await response.json();
    if (result.success) {
      showToast(result.message, "success");
      toggleForm("imageForm");
      selectedFiles = [];
      setTimeout(() => {
        window.location.reload();
      }, 1500);
    } else {
      showToast(result.message, "error");
      if (result.message.includes("Chỉ được phép có một ảnh chính")) {
        isPrimaryCheckbox.checked = false;
      }
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Có lỗi xảy ra khi tải ảnh lên", "error");
  } finally {
    setButtonLoading(button, false);
    hideLoading();
  }
}

document.addEventListener("DOMContentLoaded", function () {
  attachImageUploadListener();
});
window.openModal = openModal;
window.closeModal = closeModal;
// window.selectTour = selectTour;
window.toggleForm = toggleForm;
window.deleteImage = deleteImage;
window.showToast = showToast;
window.hideToast = hideToast;
