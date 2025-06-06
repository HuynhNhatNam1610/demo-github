let currentEditingBanner = null;
let currentEditingTour = null;

document.addEventListener("DOMContentLoaded", function () {
  initializeEventListeners();
  initializeImagePreview();
  autoHideAlerts();
});

// Hàm xử lý submit form bằng AJAX
function handleAjaxFormSubmit(form, successMessage) {
  const formData = new FormData(form);
  const submitBtn = form.querySelector('button[type="submit"]');

  showLoadingState(submitBtn);

  fetch("quanlydichvu.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      submitBtn.innerHTML =
        submitBtn.dataset.originalText || submitBtn.innerHTML;
      submitBtn.disabled = false;

      if (data.success) {
        showAlert(data.message || successMessage, "success");
        if (form.closest(".modal")) {
          closeModal(form.closest(".modal").id);
        }
        // Thêm độ trễ trước khi tải lại trang
        setTimeout(() => {
          location.reload();
        }, 1000);
      } else {
        showAlert(data.message || "Đã xảy ra lỗi!", "error");
      }
    })
    .catch((error) => {
      submitBtn.innerHTML =
        submitBtn.dataset.originalText || submitBtn.innerHTML;
      submitBtn.disabled = false;
      showAlert("Lỗi kết nối: " + error.message, "error");
    });
}

// Cập nhật initializeEventListeners để xử lý AJAX
function initializeEventListeners() {
  const forms = document.querySelectorAll("form");
  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault(); // Ngăn submit form mặc định
      if (!validateForm(this)) {
        return;
      }

      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.dataset.originalText = submitBtn.innerHTML; // Lưu text gốc
      }

      // Xử lý các form cụ thể
      if (form.id === "bannerForm") {
        handleAjaxFormSubmit(form, "Thành công với banner!");
      } else if (form.id === "addGreetingForm" || form.id === "greetingForm") {
        handleAjaxFormSubmit(form, "Thành công với lời chào!");
      } else if (form.id === "featureForm") {
        handleAjaxFormSubmit(form, "Thành công với tiện ích!");
      } else if (form.id === "tourForm") {
        handleAjaxFormSubmit(form, "Thành công với tour!");
      } else if (form.id === "activeGreetingForm") {
        if (!document.getElementById("activeGreetingId").value) {
          showAlert("Vui lòng chọn một lời chào để kích hoạt!", "error");
          submitBtn.innerHTML =
            '<i class="fas fa-save"></i> Cập Nhật Lời Chào Hoạt Động';
          submitBtn.disabled = false;
          return;
        }
        handleAjaxFormSubmit(form, "Cập nhật lời chào hoạt động thành công!");
      } else if (form.id === "deleteGreetingForm") {
        if (!document.getElementById("deleteGreetingId").value) {
          showAlert("Vui lòng chọn một lời chào để xóa!", "error");
          return;
        }
        if (confirmDelete("Bạn có chắc chắn muốn xóa lời chào này?")) {
          handleAjaxFormSubmit(form, "Xóa lời chào thành công!");
          // Làm mới danh sách select
          const greetingSelect = document.getElementById("greetingSelect");
          const activeGreetingSelect = document.getElementById(
            "activeGreetingSelect"
          );
          greetingSelect.value = "";
          activeGreetingSelect.value = "";
          loadGreeting(greetingSelect);
          updateActiveGreetingInputs(activeGreetingSelect);
        }
      } else if (
        form.querySelector('input[name="action"][value="delete_banner"]')
      ) {
        if (confirmDelete("Bạn có chắc muốn xóa banner này?")) {
          handleAjaxFormSubmit(form, "Xóa banner thành công!");
        }
      } else if (
        form.querySelector('input[name="action"][value="delete_feature"]')
      ) {
        if (confirmDelete("Bạn có chắc muốn xóa tiện ích này?")) {
          handleAjaxFormSubmit(form, "Xóa tiện ích thành công!");
        }
      } else if (
        form.querySelector('input[name="action"][value="delete_tour"]')
      ) {
        if (confirmDelete("Bạn có chắc muốn xóa tour này?")) {
          handleAjaxFormSubmit(form, "Xóa tour thành công!");
        }
      } else if (
        form.querySelector('input[name="action"][value="update_service"]')
      ) {
        handleAjaxFormSubmit(form, "Cập nhật dịch vụ thành công!");
      }
    });
  });

  // Giữ nguyên các listener khác
  const fileInputs = document.querySelectorAll('input[type="file"]');
  fileInputs.forEach((input) => {
    input.addEventListener("change", handleFileInput);
  });

  const modals = document.querySelectorAll(".modal");
  modals.forEach((modal) => {
    modal.addEventListener("click", function (e) {
      if (e.target === modal) {
        closeModal(modal.id);
      }
    });
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      closeAllModals();
    }
  });
}

function initializeImagePreview() {
  const imageInputs = document.querySelectorAll(
    'input[type="file"][accept*="image"]'
  );
  imageInputs.forEach((input) => {
    input.addEventListener("change", function (e) {
      // previewImage(e.target);
    });
  });
}
function previewImage(input) {
  // if (input.files && input.files[0]) {
  //   const reader = new FileReader();
  //   const previewContainer =
  //     input.closest(".form-group").querySelector(".image-preview") ||
  //     createPreviewContainer(input);
  //   reader.onload = function (e) {
  //     previewContainer.innerHTML = `
  //               <div class="preview-image-container">
  //                   <img src="${e.target.result}" alt="Preview" class="preview-image">
  //                   <button type="button" class="remove-preview" onclick="removeImagePreview(this)">
  //                       <i class="fas fa-times"></i>
  //                   </button>
  //               </div>
  //           `;
  //     previewContainer.style.display = "";
  //   };
  //   reader.readAsDataURL(input.files[0]);
  // }
}

function createPreviewContainer(input) {
  const container = document.createElement("div");
  container.className = "image-preview";
  container.style.marginTop = "10px";
  input.parentNode.appendChild(container);
  return container;
}

function removeImagePreview(button) {
  const previewContainer = button.closest(".image-preview");
  const fileInput =
    previewContainer.previousElementSibling.querySelector("input[type='file']");

  if (fileInput) {
    fileInput.value = "";
  }

  previewContainer.innerHTML = "";
  previewContainer.style.display = "none";
}

function handleFileInput(e) {
  const file = e.target.files[0];
  if (file) {
    if (file.size > 5 * 1024 * 1024) {
      showAlert("Kích thước file không được vượt quá 5MB!", "error");
      e.target.value = "";
      return;
    }
    if (!file.type.includes("image")) {
      showAlert("Vui lòng chọn file hình ảnh!", "error");
      e.target.value = "";
      return;
    }
    previewImage(e.target);
  }
}

function openModal(modalId, data = null) {
  const modal = document.getElementById(modalId);
  if (!modal) return;

  const form = modal.querySelector("form");
  if (form) {
    form.reset();
    clearImagePreviews(form);
  }

  // Đặt tiêu đề modal dựa trên modalId và trạng thái data
  const titleElement = modal.querySelector(".modal-header h3");
  if (titleElement) {
    if (data) {
      // Trường hợp chỉnh sửa
      if (modalId === "bannerModal") {
        titleElement.textContent = "Chỉnh Sửa Banner";
      } else if (modalId === "tourModal") {
        titleElement.textContent = "Chỉnh Sửa Tour";
      } else if (modalId === "featureModal") {
        titleElement.textContent = "Chỉnh Sửa Tiện Ích";
      }
    } else {
      // Trường hợp thêm mới
      if (modalId === "bannerModal") {
        titleElement.textContent = "Thêm Banner Mới";
        const actionInput = document.getElementById("bannerAction");
        if (actionInput) actionInput.value = "add_banner";
      } else if (modalId === "tourModal") {
        titleElement.textContent = "Thêm Tour Mới";
        const actionInput = document.getElementById("tourAction");
        if (actionInput) actionInput.value = "add_tour";
      } else if (modalId === "featureModal") {
        titleElement.textContent = "Thêm Tiện Ích Mới";
        const actionInput = document.getElementById("featureAction");
        if (actionInput) actionInput.value = "add_feature";
      } else if (modalId === "greetingModal") {
        titleElement.textContent = "Thêm Lời Chào Mới";
      }
    }
  }

  if (data && modalId !== "greetingModal") {
    populateModalForm(modalId, data);
  } else if (modalId === "greetingModal") {
    const greetingForm = document.getElementById("addGreetingForm");
    if (greetingForm) {
      greetingForm.reset();
    }
  }

  modal.style.display = "grid";
  document.body.style.overflow = "hidden";

  setTimeout(() => {
    const firstInput = modal.querySelector("input, textarea, select");
    if (firstInput) {
      firstInput.focus();
    }
  }, 100);
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (!modal) return;

  modal.style.display = "none";
  document.body.style.overflow = "";

  const form = modal.querySelector("form");
  if (form) {
    form.reset();
    clearImagePreviews(form);
  }

  currentEditingBanner = null;
  currentEditingTour = null;

  const activeGreetingSelect = document.getElementById("activeGreetingSelect");
  if (activeGreetingSelect) {
    updateActiveGreetingInputs(activeGreetingSelect);
  }
}

function closeAllModals() {
  const modals = document.querySelectorAll(".modal");
  modals.forEach((modal) => {
    modal.style.display = "none";
  });
  document.body.style.overflow = "";
}

function clearImagePreviews(form) {
  const previews = form.querySelectorAll(".image-preview");
  previews.forEach((preview) => {
    preview.innerHTML = "";
    preview.style.display = "none";
  });
}

function populateModalForm(modalId, data) {
  const modal = document.getElementById(modalId);
  const form = modal.querySelector("form");

  if (modalId === "bannerModal") {
    populateBannerForm(form, data);
  } else if (modalId === "tourModal") {
    populateTourForm(form, data);
  }
}

function populateBannerForm(form, banner) {
  const titleElement = document.getElementById("bannerModalTitle");
  const actionInput = document.getElementById("bannerAction");
  const idInput = document.getElementById("bannerId");

  if (titleElement) titleElement.textContent = "Chỉnh Sửa Banner";
  if (actionInput) actionInput.value = "update_banner";
  if (idInput) idInput.value = banner.id;

  const pageInput = document.getElementById("bannerPage");
  const topicInput = document.getElementById("bannerTopic");

  if (pageInput) pageInput.value = banner.page || "";
  if (topicInput) topicInput.value = banner.id_topic || "1";

  if (banner.image) {
    const currentImageDiv = document.getElementById("currentBannerImage");
    // if (currentImageDiv) {
    //   currentImageDiv.innerHTML = `
    //     <div class="current-image">
    //         <p>Hình ảnh hiện tại:</p>
    //         <img src="../../Uploads/${banner.image}" alt="Current Banner" class="current-image-preview">
    //     </div>
    // `;
    // }
  }

  currentEditingBanner = banner;
}

function populateTourForm(form, tour) {
  const titleElement = document.getElementById("tourModalTitle");
  const actionInput = document.getElementById("tourAction");
  const idInput = document.getElementById("tourId");
  const topicInput = document.getElementById("tourTopic");
  const priceInputVi = document.getElementById("tourPrice_vi");
  const priceInputEn = document.getElementById("tourPrice_en");
  const iconInput = document.getElementById("tourIcon");

  if (titleElement) titleElement.textContent = "Chỉnh Sửa Tour";
  if (actionInput) actionInput.value = "update_tour";
  if (idInput) idInput.value = tour.id_dichvu;

  const titleInputVi = document.getElementById("tourTitle_vi");
  const contentInputVi = document.getElementById("tourContent_vi");
  const titleInputEn = document.getElementById("tourTitle_en");
  const contentInputEn = document.getElementById("tourContent_en");
  const currentImageDiv = document.getElementById("currentTourImage");

  if (titleInputVi) titleInputVi.value = tour.title_vi || "";
  if (contentInputVi) contentInputVi.value = tour.content_vi || "";
  if (titleInputEn) titleInputEn.value = tour.title_en || "";
  if (contentInputEn) contentInputEn.value = tour.content_en || "";
  if (topicInput) topicInput.value = tour.id_topic || "1";
  if (priceInputVi) priceInputVi.value = tour.price || "Liên hệ";
  if (priceInputEn) priceInputEn.value = tour.price_en || "Contact us";
  if (iconInput) iconInput.value = tour.icon || "fas fa-map-marked-alt";

  if (tour.image) {
    if (currentImageDiv) {
      // currentImageDiv.innerHTML = `
      //     <div class="current-image">
      //         <p>Hình ảnh hiện tại:</p>
      //         <img src="../../view/img/${tour.image}" alt="Current Tour" class="current-image-preview">
      //     </div>
      // `;
    }
  } else {
    if (currentImageDiv) currentImageDiv.innerHTML = "";
  }

  currentEditingTour = tour;
}

function loadGreeting(select) {
  const updateBtn = document.getElementById("updateGreetingBtn");
  const deleteBtn = document.getElementById("deleteGreetingBtn");
  const greetingIdInput = document.getElementById("greetingId");
  const deleteGreetingIdInput = document.getElementById("deleteGreetingId");
  const greetingContentViInput = document.getElementById("greetingContent_vi");
  const greetingContentEnInput = document.getElementById("greetingContent_en");

  if (select.value === "") {
    greetingIdInput.value = "";
    deleteGreetingIdInput.value = "";
    greetingContentViInput.value = "";
    greetingContentEnInput.value = "";
    updateBtn.disabled = true;
    deleteBtn.disabled = true;
  } else {
    try {
      const greeting = JSON.parse(select.value);
      if (!greeting.id_nhungcauchaohoi) {
        showAlert("Dữ liệu lời chào không hợp lệ!", "error");
        select.value = "";
        return;
      }
      greetingIdInput.value = greeting.id_nhungcauchaohoi_ngonngu || "";
      deleteGreetingIdInput.value = greeting.id_nhungcauchaohoi || ""; // Sửa ở đây
      greetingContentViInput.value = greeting.content_vi || "";
      greetingContentEnInput.value = greeting.content_en || "";
      updateBtn.disabled = false;
      deleteBtn.disabled = false;
    } catch (e) {
      showAlert("Lỗi khi tải dữ liệu lời chào!", "error");
      select.value = "";
      greetingIdInput.value = "";
      deleteGreetingIdInput.value = "";
      greetingContentViInput.value = "";
      greetingContentEnInput.value = "";
      updateBtn.disabled = true;
      deleteBtn.disabled = true;
    }
  }
}

function updateActiveGreetingInputs(select) {
  const activeGreetingIdInput = document.getElementById("activeGreetingId");
  const updateActiveGreetingBtn = document.getElementById(
    "updateActiveGreetingBtn"
  );

  if (select.value === "") {
    activeGreetingIdInput.value = "";
    updateActiveGreetingBtn.disabled = true;
  } else {
    try {
      const greeting = JSON.parse(select.value);
      if (!greeting.id_nhungcauchaohoi_ngonngu) {
        showAlert("Dữ liệu lời chào không hợp lệ!", "error");
        select.value = "";
        return;
      }
      activeGreetingIdInput.value = greeting.id_nhungcauchaohoi_ngonngu || "";
      updateActiveGreetingBtn.disabled = false;
    } catch (e) {
      showAlert("Lỗi khi tải dữ liệu lời chào!", "error");
      select.value = "";
      activeGreetingIdInput.value = "";
      updateActiveGreetingBtn.disabled = true;
    }
  }
}

function editTour(tour) {
  openModal("tourModal", tour);
}

function editBanner(banner) {
  openModal("bannerModal", banner);
}

function showLoadingState(button) {
  const originalText = button.innerHTML;
  button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
  button.disabled = true;

  setTimeout(() => {
    button.innerHTML = originalText;
    button.disabled = false;
  }, 5000);
}

function showAlert(message, type = "info", duration = 5000) {
  const existingAlerts = document.querySelectorAll(".alert");
  existingAlerts.forEach((alert) => alert.remove());

  const alert = document.createElement("div");
  alert.className = `alert alert-${type} ajax-alert`;
  alert.innerHTML = `
    <i class="fas fa-${
      type === "success"
        ? "check-circle"
        : type === "error"
        ? "exclamation-circle"
        : "info-circle"
    }"></i>
    ${message}
  `;

  document.body.appendChild(alert); // Chèn trực tiếp vào body

  setTimeout(() => {
    if (alert.parentNode) {
      alert.style.opacity = "0";
      alert.style.transform = "translateY(-20px)";
      setTimeout(() => alert.remove(), 300);
    }
  }, duration);
}

function autoHideAlerts() {
  const alerts = document.querySelectorAll(".alert:not(.ajax-alert)");
  alerts.forEach((alert) => {
    setTimeout(() => {
      if (alert.parentNode) {
        alert.style.opacity = "0";
        alert.style.transform = "translateY(-20px)";
        setTimeout(() => alert.remove(), 300);
      }
    }, 5000);
  });
}
function validateForm(form) {
  const requiredFields = form.querySelectorAll("[required]");
  let isValid = true;

  requiredFields.forEach((field) => {
    if (!field.value.trim()) {
      field.classList.add("error");
      isValid = false;

      field.addEventListener(
        "input",
        function () {
          this.classList.remove("error");
        },
        { once: true }
      );
    }
  });

  if (!isValid) {
    showAlert("Vui lòng điền đầy đủ thông tin bắt buộc!", "error");
  }

  return isValid;
}

function confirmDelete(message = "Bạn có chắc chắn muốn xóa không?") {
  return confirm(message);
}

function formatFileSize(bytes) {
  if (bytes === 0) return "0 Bytes";
  const k = 1024;
  const sizes = ["Bytes", "KB", "MB", "GB"];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
}

function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
}

function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

function enableAutoSave(formElement, saveCallback, interval = 30000) {
  let autoSaveInterval;

  function startAutoSave() {
    autoSaveInterval = setInterval(() => {
      const formData = new FormData(formElement);
      saveCallback(formData);
    }, interval);
  }

  function stopAutoSave() {
    if (autoSaveInterval) {
      clearInterval(autoSaveInterval);
    }
  }

  formElement.addEventListener("input", debounce(startAutoSave, 1000));
  formElement.addEventListener("submit", stopAutoSave);

  return { start: startAutoSave, stop: stopAutoSave };
}

function copyToClipboard(text) {
  navigator.clipboard
    .writeText(text)
    .then(() => {
      showAlert("Đã sao chép vào clipboard!", "success", 2000);
    })
    .catch(() => {
      const textArea = document.createElement("textarea");
      textArea.value = text;
      document.body.appendChild(textArea);
      textArea.select();
      document.execCommand("copy");
      document.body.removeChild(textArea);
      showAlert("Đã sao chép vào clipboard!", "success", 2000);
    });
}

function toggleTheme() {
  const body = document.body;
  const isDark = body.classList.contains("dark-theme");

  if (isDark) {
    body.classList.remove("dark-theme");
    localStorage.setItem("theme", "light");
  } else {
    body.classList.add("dark-theme");
    localStorage.setItem("theme", "dark");
  }
}

function loadSavedTheme() {
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme === "dark") {
    document.body.classList.add("dark-theme");
  }
}

function initializeSavedSettings() {
  loadSavedTheme();
}

document.addEventListener("DOMContentLoaded", initializeSavedSettings);
// Hàm cập nhật icon và preview
function updateIcon() {
  const iconSelect = document.getElementById("featureIconSelect");
  const iconCustomInput = document.getElementById("featureIconCustom");
  const iconHiddenInput = document.getElementById("featureIcon");
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

// Hàm xử lý chỉnh sửa tiện ích
function editFeature(feature) {
  const modal = document.getElementById("featureModal");
  const form = document.getElementById("featureForm");
  const titleElement = document.getElementById("featureModalTitle");
  const actionInput = document.getElementById("featureAction");
  const idInput = document.getElementById("featureId");
  const titleInputVi = document.getElementById("featureTitle_vi");
  const contentInputVi = document.getElementById("featureContent_vi");
  const titleInputEn = document.getElementById("featureTitle_en");
  const contentInputEn = document.getElementById("featureContent_en");
  const iconSelect = document.getElementById("featureIconSelect");
  const iconCustomInput = document.getElementById("featureIconCustom");
  const iconHiddenInput = document.getElementById("featureIcon");
  const iconPreview = document.getElementById("iconPreview");

  titleElement.textContent = "Chỉnh Sửa Tiện Ích";
  actionInput.value = "update_feature";
  idInput.value = feature.id_tienich;
  titleInputVi.value = feature.title || "";
  contentInputVi.value = feature.content || "";
  titleInputEn.value = "";
  contentInputEn.value = "";

  // Lấy dữ liệu tiếng Anh
  fetch(
    `quanlydichvu.php?action=get_feature_en&id_tienich=${feature.id_tienich}`
  )
    .then((response) => response.json())
    .then((data) => {
      titleInputEn.value = data.title || "";
      contentInputEn.value = data.content || "";
    })
    .catch((error) =>
      console.error("Error fetching English feature data:", error)
    );

  const iconOption = Array.from(iconSelect.options).find(
    (option) => option.value === feature.icon
  );
  if (iconOption) {
    iconSelect.value = feature.icon;
    iconCustomInput.style.display = "none";
  } else {
    iconSelect.value = "custom";
    iconCustomInput.style.display = "block";
    iconCustomInput.value = feature.icon || "";
  }

  iconHiddenInput.value = feature.icon || "";
  iconPreview.innerHTML = feature.icon ? `<i class="${feature.icon}"></i>` : "";

  modal.style.display = "grid";
  document.body.style.overflow = "hidden";
}
// Đảm bảo icon được cập nhật khi thay đổi input tùy chỉnh
document.addEventListener("DOMContentLoaded", function () {
  const iconCustomInput = document.getElementById("featureIconCustom");
  const featureForm = document.getElementById("featureForm");
  if (iconCustomInput) {
    iconCustomInput.addEventListener("input", updateIcon);
  }
  if (featureForm) {
    featureForm.addEventListener("submit", function () {
      updateIcon();
    });
  }
});
window.AdminDichVu = {
  openModal,
  closeModal,
  editBanner,
  editTour,
  showAlert,
  confirmDelete,
  copyToClipboard,
  toggleTheme,
  scrollToTop,
  loadGreeting,
  updateActiveGreetingInputs,
  editFeature,
};

console.log("Admin Dịch Vụ JavaScript loaded successfully!");
