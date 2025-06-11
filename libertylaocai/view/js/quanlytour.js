document.addEventListener("DOMContentLoaded", function () {
  initializeEventListeners();
});

function initializeEventListeners() {
  const tourInfoForm = document.getElementById("tourInfoForm");
  const addHighlightForm = document.getElementById("highlightForm");
  const addScheduleForm = document.getElementById("addScheduleForm");
  const includesForm = document.getElementById("includesForm");
  const addImageForm = document.getElementById("addImageForm");
  const scheduleEditForm = document.getElementById("scheduleEditForm");
  const descriptionForm = document.getElementById("descriptionForm");

  if (tourInfoForm) {
    tourInfoForm.addEventListener("submit", handleTourInfoSubmit);
  }
  if (addHighlightForm) {
    addHighlightForm.addEventListener("submit", handleHighlightFormSubmit);
  }
  if (addScheduleForm) {
    addScheduleForm.addEventListener("submit", handleScheduleSubmit);
  }
  if (includesForm) {
    includesForm.addEventListener("submit", handleIncludesSubmit);
  }
  if (addImageForm) {
    addImageForm.addEventListener("submit", handleImageSubmit);
  }
  if (scheduleEditForm) {
    scheduleEditForm.addEventListener("submit", handleScheduleEditSubmit);
  }
  if (descriptionForm) {
    descriptionForm.addEventListener("submit", handleDescriptionSubmit);
  }

  setTimeout(hideToast, 5000);
}

function selectTour(tourId) {
  showLoading();
  window.location.href = `?id_dichvu=${tourId}`;
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
    const response = await fetch(window.location.href, {
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

async function handleScheduleSubmit(e) {
  e.preventDefault();
  const formData = new FormData(e.target);
  const time = formData.get("time");
  if (!time) {
    showToast("Vui lòng chọn thời gian", "error");
    return;
  }
  try {
    showLoading();
    const response = await fetch(window.location.href, {
      method: "POST",
      body: formData,
    });
    const result = await response.json();
    if (result.success) {
      showToast(result.message, "success");
      toggleForm("scheduleForm");
      setTimeout(() => {
        window.location.reload();
      }, 1500);
    } else {
      showToast(result.message, "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Có lỗi xảy ra khi thêm lịch trình", "error");
  } finally {
    hideLoading();
  }
}

async function handleScheduleEditSubmit(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  const button = form.querySelector('button[type="submit"]');
  try {
    setButtonLoading(button, true);
    showLoading();
    const response = await fetch(window.location.href, {
      method: "POST",
      body: formData,
    });
    const result = await response.json();
    if (result.success) {
      showToast(result.message, "Std::stringstream::sentryuccess");
      closeModal("scheduleModal");
      setTimeout(() => {
        window.location.reload();
      }, 1500);
    } else {
      showToast(result.message, "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Có lỗi xảy ra khi cập nhật lịch trình", "error");
  } finally {
    setButtonLoading(button, false);
    hideLoading();
  }
}

async function handleIncludesSubmit(e) {
  e.preventDefault();
  const formData = new FormData(e.target);
  try {
    showLoading();
    const response = await fetch(window.location.href, {
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

async function deleteHighlight(highlightId) {
  if (!confirm("Bạn có chắc chắn muốn xóa điểm nổi bật này?")) {
    return;
  }
  const urlParams = new URLSearchParams(window.location.search);
  const tourId = urlParams.get("id_dichvu");
  const formData = new FormData();
  formData.append("action", "delete_highlight");
  formData.append("id_tienich", highlightId);
  formData.append("id_dichvu", tourId);
  try {
    showLoading();
    const response = await fetch(window.location.href, {
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
    showToast("Có lỗi xảy ra khi xóa điểm nổi bật", "error");
  } finally {
    hideLoading();
  }
}

async function deleteSchedule(scheduleId) {
  if (!confirm("Bạn có chắc chắn muốn xóa lịch trình này?")) {
    return;
  }
  const formData = new FormData();
  formData.append("action", "delete_schedule");
  formData.append("id_lichtrinh", scheduleId);
  try {
    showLoading();
    const response = await fetch(window.location.href, {
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
    showToast("Có lỗi xảy ra khi xóa lịch trình", "error");
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
    const response = await fetch(window.location.href, {
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

window.addEventListener("error", function (e) {
  console.error("JavaScript Error:", e.error);
  showToast("Có lỗi xảy ra trong ứng dụng", "error");
});

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

function updateHighlightIcon() {
  const iconSelect = document.getElementById("highlightIconSelect");
  const iconCustomInput = document.getElementById("highlightIconCustom");
  const iconHiddenInput = document.getElementById("highlightIcon");
  const iconPreview = document.getElementById("iconPreview");
  let iconClass = "";
  if (iconSelect.value === "custom") {
    iconCustomInput.style.display = "block";
    iconClass = iconCustomInput.value.trim();
  } else {
    iconCustomInput.style.display = "none";
    iconClass = iconSelect.value;
  }
  iconHiddenInput.value = iconClass;
  iconPreview.innerHTML = iconClass ? `<i class="${iconClass}"></i>` : "";
}

function editHighlight(highlight) {
  try {
    if (!highlight || !highlight.id_tienich) {
      showToast("Dữ liệu điểm nổi bật không hợp lệ", "error");
      return;
    }
    const modal = document.getElementById("highlightModal");
    const form = document.getElementById("highlightForm");
    const titleElement = document.getElementById("highlightModalTitle");
    const actionInput = document.getElementById("highlightAction");
    const idInput = document.getElementById("highlightId");
    const titleInputVi = document.getElementById("highlight_title_vi");
    const contentInputVi = document.getElementById("highlight_content_vi");
    const titleInputEn = document.getElementById("highlight_title_en");
    const contentInputEn = document.getElementById("highlight_content_en");
    const iconSelect = document.getElementById("highlightIconSelect");
    const iconCustomInput = document.getElementById("highlightIconCustom");
    const iconHiddenInput = document.getElementById("highlightIcon");
    const iconPreview = document.getElementById("iconPreview");
    if (
      !modal ||
      !form ||
      !titleElement ||
      !actionInput ||
      !idInput ||
      !titleInputVi ||
      !contentInputVi ||
      !titleInputEn ||
      !contentInputEn ||
      !iconSelect ||
      !iconCustomInput ||
      !iconHiddenInput ||
      !iconPreview
    ) {
      showToast("Không tìm thấy các thành phần giao diện", "error");
      return;
    }
    titleElement.textContent = "Chỉnh Sửa Điểm Nổi Bật";
    actionInput.value = "update_highlight";
    idInput.value = highlight.id_tienich || "";
    titleInputVi.value = highlight.title || "";
    contentInputVi.value = highlight.content || "";

    // Lấy dữ liệu tiếng Anh từ server bằng POST
    const formData = new FormData();
    formData.append("action", "get_highlight");
    formData.append("id_tienich", highlight.id_tienich);
    formData.append("id_ngonngu", 2);
    fetch(window.location.href, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          titleInputEn.value = data.data.title || "";
          contentInputEn.value = data.data.content || "";
        } else {
          titleInputEn.value = "";
          contentInputEn.value = "";
        }
      })
      .catch((error) => {
        console.error("Error fetching English highlight:", error);
        titleInputEn.value = "";
        contentInputEn.value = "";
      });

    const iconOption = Array.from(iconSelect.options).find(
      (option) => option.value === highlight.icon
    );
    if (iconOption) {
      iconSelect.value = highlight.icon;
      iconCustomInput.style.display = "none";
    } else {
      iconSelect.value = "custom";
      iconCustomInput.style.display = "block";
      iconCustomInput.value = highlight.icon || "";
    }
    iconHiddenInput.value = highlight.icon || "";
    iconPreview.innerHTML = highlight.icon
      ? `<i class="${highlight.icon}"></i>`
      : "";
    modal.style.display = "grid";
    document.body.style.overflow = "hidden";
  } catch (error) {
    console.error("Error in editHighlight:", error);
    showToast("Có lỗi xảy ra khi mở chỉnh sửa điểm nổi bật", "error");
  }
}

function editSchedule(schedule) {
  try {
    if (!schedule || !schedule.id) {
      showToast("Dữ liệu lịch trình không hợp lệ", "error");
      return;
    }
    const modal = document.getElementById("scheduleModal");
    const form = document.getElementById("scheduleEditForm");
    const titleElement = document.getElementById("scheduleModalTitle");
    const actionInput = document.getElementById("scheduleAction");
    const idInput = document.getElementById("scheduleId");
    const timeInput = document.getElementById("scheduleTime");
    const ngayInputVi = document.getElementById("schedule_ngay_vi");
    const titleInputVi = document.getElementById("schedule_title_vi");
    const contentInputVi = document.getElementById("schedule_content_vi");
    const ngayInputEn = document.getElementById("schedule_ngay_en");
    const titleInputEn = document.getElementById("schedule_title_en");
    const contentInputEn = document.getElementById("schedule_content_en");
    if (
      !modal ||
      !form ||
      !titleElement ||
      !actionInput ||
      !idInput ||
      !timeInput ||
      !ngayInputVi ||
      !titleInputVi ||
      !contentInputVi ||
      !ngayInputEn ||
      !titleInputEn ||
      !contentInputEn
    ) {
      showToast("Không tìm thấy các thành phần giao diện", "error");
      return;
    }
    titleElement.textContent = "Chỉnh Sửa Lịch Trình";
    actionInput.value = "update_schedule";
    idInput.value = schedule.id || "";
    timeInput.value = schedule.time ? schedule.time.slice(0, 16) : "";
    ngayInputVi.value = schedule.ngay || "";
    titleInputVi.value = schedule.title || "";
    contentInputVi.value = schedule.content || "";

    // Lấy dữ liệu tiếng Anh từ server bằng POST
    const formData = new FormData();
    formData.append("action", "get_schedule");
    formData.append("id_lichtrinh", schedule.id);
    formData.append("id_ngonngu", 2);
    fetch(window.location.href, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          ngayInputEn.value = data.data.ngay || "";
          titleInputEn.value = data.data.title || "";
          contentInputEn.value = data.data.content || "";
        } else {
          ngayInputEn.value = "";
          titleInputEn.value = "";
          contentInputEn.value = "";
        }
      })
      .catch((error) => {
        console.error("Error fetching English schedule:", error);
        ngayInputEn.value = "";
        titleInputEn.value = "";
        contentInputEn.value = "";
      });

    modal.style.display = "grid";
    document.body.style.overflow = "hidden";
  } catch (error) {
    console.error("Error in editSchedule:", error);
    showToast("Có lỗi xảy ra khi mở chỉnh sửa lịch trình", "error");
  }
}

async function handleHighlightFormSubmit(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  const button = form.querySelector('button[type="submit"]');
  try {
    setButtonLoading(button, true);
    showLoading();
    const response = await fetch(window.location.href, {
      method: "POST",
      body: formData,
    });
    const result = await response.json();
    if (result.success) {
      showToast(result.message, "success");
      closeModal("highlightModal");
      setTimeout(() => {
        window.location.reload();
      }, 1500);
    } else {
      showToast(result.message, "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Có lỗi xảy ra khi xử lý điểm nổi bật", "error");
  } finally {
    setButtonLoading(button, false);
    hideLoading();
  }
}
async function handleDescriptionSubmit(e) {
  e.preventDefault();
  const formData = new FormData(e.target);
  try {
    showLoading();
    const response = await fetch(window.location.href, {
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
    showToast("Có lỗi xảy ra khi cập nhật mô tả tour", "error");
  } finally {
    hideLoading();
  }
}
async function handleImageSubmit(e) {
  e.preventDefault();
  const form = e.target;
  const fileInput = form.querySelector('input[type="file"]');
  const isPrimaryCheckbox = form.querySelector('input[name="is_primary"]');
  if (!fileInput.files.length) {
    showToast("Vui lòng chọn file ảnh", "error");
    return;
  }
  const file = fileInput.files[0];
  if (file.size > 5 * 1024 * 1024) {
    showToast("Kích thước file không được vượt quá 5MB!", "error");
    fileInput.value = "";
    return;
  }
  if (!file.type.includes("image")) {
    showToast("Vui lòng chọn file hình ảnh!", "error");
    fileInput.value = "";
    return;
  }
  const formData = new FormData(form);
  const button = form.querySelector('button[type="submit"]');
  try {
    setButtonLoading(button, true);
    showLoading();
    const response = await fetch(window.location.href, {
      method: "POST",
      body: formData,
    });
    const result = await response.json();
    if (result.success) {
      showToast(result.message, "success");
      toggleForm("imageForm");
      setTimeout(() => {
        window.location.reload();
      }, 1500);
    } else {
      showToast(result.message, "error");
      if (result.message.includes("Chỉ được phép có một ảnh chính")) {
        isPrimaryCheckbox.checked = false; // Bỏ chọn checkbox nếu có lỗi ảnh chính
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
  const iconCustomInput = document.getElementById("highlightIconCustom");
  const highlightForm = document.getElementById("highlightForm");
  if (iconCustomInput) {
    iconCustomInput.addEventListener("input", updateHighlightIcon);
  }
  if (highlightForm) {
    highlightForm.addEventListener("submit", handleHighlightFormSubmit);
  }
});
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
    const response = await fetch(window.location.href, {
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
window.editHighlight = editHighlight;
window.editSchedule = editSchedule;
window.updateHighlightIcon = updateHighlightIcon;
window.selectTour = selectTour;
window.toggleForm = toggleForm;
window.deleteHighlight = deleteHighlight;
window.deleteSchedule = deleteSchedule;
window.deleteImage = deleteImage;
window.showToast = showToast;
window.hideToast = hideToast;
