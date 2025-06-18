let currentEditingBanner = null;
let currentEditingTour = null;
let currentEditingService = null;

document.addEventListener("DOMContentLoaded", function () {
  initializeEventListeners();
  autoHideAlerts();

  // Khởi tạo Select2 cho featureIconSelect
  $("#featureIconSelect").select2({
    templateResult: formatIcon,
    templateSelection: formatIcon,
    escapeMarkup: function (m) {
      return m;
    },
    minimumResultsForSearch: -1, // Tắt thanh tìm kiếm
  });
});

// Hàm định dạng option trong Select2 để hiển thị chỉ biểu tượng
function formatIcon(option) {
  if (!option.id) {
    return '<i class="fas fa-list"></i>'; // Biểu tượng cho placeholder
  }
  const iconClass = $(option.element).data("icon");
  if (option.id === "custom") {
    return '<i class="' + iconClass + '"></i>';
  }
  return '<i class="' + iconClass + '"></i>';
}

// Hàm kiểm tra chuỗi có phải là số
function isNumeric(str) {
  return /^\d+$/.test(str);
}

// Hàm định dạng số với dấu phân cách hàng nghìn
function formatNumberWithCommas(number) {
  if (!number) return "";
  return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Hàm loại bỏ dấu phân cách để lấy số gốc
function parseNumberWithCommas(str) {
  return str.replace(/\./g, "");
}

// Hàm khởi tạo định dạng giá tiền cho các input
function initializePriceInputs() {
  const priceInputs = document.querySelectorAll('input[name="price_vi"]');
  priceInputs.forEach((input) => {
    if (input.value) {
      if (isNumeric(input.value)) {
        input.dataset.rawValue = input.value;
        input.value = formatNumberWithCommas(input.value);
      } else {
        input.dataset.rawValue = input.value;
      }
    } else {
      input.dataset.rawValue = "";
    }

    input.addEventListener("input", function (e) {
      let value = e.target.value;
      let rawValue = parseNumberWithCommas(value);

      if (isNumeric(rawValue)) {
        e.target.dataset.rawValue = rawValue;
        e.target.value = formatNumberWithCommas(rawValue);
      } else {
        e.target.dataset.rawValue = value;
        e.target.value = value;
      }
    });
  });
}

// Hàm xử lý submit form bằng AJAX
function handleAjaxFormSubmit(form, successMessage) {
  const formData = new FormData(form);
  const submitBtn = form.querySelector('button[type="submit"]');

  // Đồng bộ dữ liệu từ CKEditor
  const editorIds = [
    "featureContent_vi",
    "featureContent_en",
    "serviceTourContent_vi",
    "serviceTourContent_en",
    "editServiceContent_vi",
    "editServiceContent_en",
  ];
  editorIds.forEach((id) => {
    if (editors[id]) {
      const textarea = document.getElementById(id);
      if (textarea) {
        const data = editors[id].getData();
        textarea.value = data;
        formData.set(textarea.name, data);
        console.log(`Synced CKEditor data for ${id}:`, data);
      } else {
        console.warn(`Textarea with ID ${id} not found`);
      }
    } else {
      console.warn(`CKEditor instance for ${id} not initialized`);
    }
  });

  // Log FormData để kiểm tra
  for (let [key, value] of formData.entries()) {
    console.log(`FormData: ${key} = ${value}`);
  }

  showLoadingState(submitBtn);

  fetch("/libertylaocai/user/submit", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Server response:", data);
      submitBtn.innerHTML =
        submitBtn.dataset.originalText || submitBtn.innerHTML;
      submitBtn.disabled = false;

      if (data.success) {
        showAlert(data.message || successMessage, "success");
        if (form.closest(".modal")) {
          closeModal(form.closest(".modal").id);
        }
        setTimeout(() => {
          location.reload();
        }, 1000);
      } else {
        showAlert(data.message || "Đã xảy ra lỗi!", "error");
      }
    })
    .catch((error) => {
      console.error("Fetch error:", error);
      submitBtn.innerHTML =
        submitBtn.dataset.originalText || submitBtn.innerHTML;
      submitBtn.disabled = false;
      showAlert("Lỗi kết nối: " + error.message, "error");
    });
}

function initializeEventListeners() {
  const forms = document.querySelectorAll("form");
  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      if (!validateForm(this)) return;

      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.dataset.originalText = submitBtn.innerHTML;
      }

      const priceInput = form.querySelector('input[name="price_vi"]');
      if (priceInput && priceInput.dataset.rawValue) {
        priceInput.value = priceInput.dataset.rawValue;
      }

      if (form.id === "bannerForm") {
        handleAjaxFormSubmit(form, "Thành công với banner!");
      } else if (form.id === "addGreetingForm" || form.id === "greetingForm") {
        handleAjaxFormSubmit(form, "Thành công với lời chào!");
      } else if (form.id === "featureForm") {
        handleAjaxFormSubmit(form, "Thành công với tiện ích!");
      } else if (form.id === "serviceTourForm") {
        const action = document.getElementById("serviceTourAction").value;
        handleAjaxFormSubmit(
          form,
          action === "add_tour"
            ? "Thêm tour thành công!"
            : "Thêm dịch vụ thành công!"
        );
      } else if (form.id === "editServiceForm") {
        handleAjaxFormSubmit(form, "Cập nhật dịch vụ thành công!");
      } else if (form.id === "highlightForm") {
        handleAjaxFormSubmit(form, "Thành công với highlight!");
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
        form.querySelector('input[name="action"][value="delete_service"]')
      ) {
        if (confirmDelete("Bạn có chắc muốn xóa dịch vụ này?")) {
          handleAjaxFormSubmit(form, "Xóa dịch vụ thành công!");
        }
      } else if (
        form.querySelector('input[name="action"][value="delete_highlight"]')
      ) {
        if (confirmDelete("Bạn có chắc muốn xóa highlight này?")) {
          handleAjaxFormSubmit(form, "Xóa highlight thành công!");
        }
      }
    });
  });

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

  initializePriceInputs();
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

// Biến lưu trữ các instance CKEditor
let editors = {};

// Hàm khởi tạo CKEditor cho textarea
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
        `Không thể khởi tạo trình chỉnh sửa cho ${textareaId}. Vui lòng thử lại!`,
        "error"
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

// Hàm khởi tạo CKEditor cho modal
function initializeModalEditors(modalId) {
  const editorConfigs = {
    featureModal: ["featureContent_vi", "featureContent_en"],
    serviceTourModal: ["serviceTourContent_vi", "serviceTourContent_en"],
    editServiceModal: ["editServiceContent_vi", "editServiceContent_en"],
  };

  const textareas = editorConfigs[modalId] || [];
  const promises = textareas.map((textareaId) => {
    if (!editors[textareaId]) {
      return initializeCKEditor(textareaId).then((editor) => {
        if (!editor) {
          throw new Error(`Failed to initialize CKEditor for ${textareaId}`);
        }
      });
    }
    return Promise.resolve();
  });

  return Promise.all(promises).catch((error) => {
    console.error(`Lỗi khởi tạo CKEditor cho modal ${modalId}:`, error);
    showAlert("Lỗi khởi tạo trình chỉnh sửa. Vui lòng thử lại!", "error");
  });
}

// Hàm hủy tất cả CKEditor trong modal
function destroyModalEditors(modalId) {
  const editorConfigs = {
    featureModal: ["featureContent_vi", "featureContent_en"],
    serviceTourModal: ["serviceTourContent_vi", "serviceTourContent_en"],
    editServiceModal: ["editServiceContent_vi", "editServiceContent_en"],
  };

  const textareas = editorConfigs[modalId] || [];
  textareas.forEach((textareaId) => {
    destroyCKEditor(textareaId);
  });
}

function openModal(modalId, data = null) {
  const modal = document.getElementById(modalId);
  if (!modal) return;

  const form = modal.querySelector("form");
  if (form) {
    form.reset();
    clearImagePreviews(form);
    destroyModalEditors(modalId);
  }

  const titleElement = modal.querySelector(".modal-title");
  if (titleElement) {
    if (data) {
      if (modalId === "editServiceModal") {
        titleElement.textContent = "Chỉnh Sửa Dịch Vụ";
      } else if (modalId === "featureModal") {
        titleElement.textContent = "Chỉnh Sửa Tiện Ích";
      }
    } else {
      if (modalId === "serviceTourModal") {
        titleElement.textContent = "Thêm Dịch Vụ/Tour Mới";
        const actionInput = document.getElementById("serviceTourAction");
        if (actionInput) actionInput.value = "add_service";
      } else if (modalId === "featureModal") {
        titleElement.textContent = "Thêm Tiện Ích Mới";
        const actionInput = document.getElementById("featureAction");
        if (actionInput) actionInput.value = "add_feature";
      }
    }
  }

  if (data && modalId !== "greetingModal") {
    populateModalForm(modalId, data);
  } else if (modalId === "serviceTourModal") {
    const actionInput = document.getElementById("serviceTourAction");
    if (actionInput) actionInput.value = "add_service";
    const titleElement = document.getElementById("serviceTourModalTitle");
    if (titleElement) titleElement.textContent = "Thêm Dịch Vụ/Tour Mới";
  }

  modal.style.display = "grid";
  document.body.style.overflow = "hidden";

  // Khởi tạo CKEditor với thời gian chờ dài hơn
  setTimeout(() => {
    initializeModalEditors(modalId).then(() => {
      console.log(`CKEditor initialized for modal: ${modalId}`);
      if (data) {
        if (modalId === "serviceTourModal") {
          if (editors["serviceTourContent_vi"])
            editors["serviceTourContent_vi"].setData(data.content_vi || "");
          if (editors["serviceTourContent_en"])
            editors["serviceTourContent_en"].setData(data.content_en || "");
        } else if (modalId === "editServiceModal") {
          if (editors["editServiceContent_vi"])
            editors["editServiceContent_vi"].setData(data.content_vi || "");
          if (editors["editServiceContent_en"])
            editors["editServiceContent_en"].setData(data.content_en || "");
        }
      }
    });
  }, 300); // Tăng từ 100ms lên 300ms

  setTimeout(() => {
    const firstInput = modal.querySelector("input, textarea, select");
    if (firstInput) firstInput.focus();
  }, 400);
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

  destroyModalEditors(modalId);

  currentEditingBanner = null;
  currentEditingTour = null;
  currentEditingService = null;
  currentEditingHighlight = null;

  const activeGreetingSelect = document.getElementById("activeGreetingSelect");
  if (activeGreetingSelect) {
    updateActiveGreetingInputs(activeGreetingSelect);
  }
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

  if (modalId === "editServiceModal") {
    populateEditServiceForm(form, data);
  } else if (modalId === "serviceTourModal") {
    populateServiceTourForm(form, data);
  }
}

function populateEditServiceForm(form, service) {
  const titleElement = document.getElementById("editServiceModalTitle");
  const actionInput = document.getElementById("editServiceAction");
  const idInput = document.getElementById("editServiceId");
  const titleInputVi = document.getElementById("editServiceTitle_vi");
  const titleInputEn = document.getElementById("editServiceTitle_en");
  const priceInput = document.getElementById("editServicePrice_vi");
  const currentImageDiv = document.getElementById("currentEditServiceImage");

  titleElement.textContent = "Chỉnh Sửa Dịch Vụ";
  actionInput.value = "update_service";
  idInput.value = service.id_dichvu;

  titleInputVi.value = service.title_vi || "";
  titleInputEn.value = service.title_en || "";

  if (service.price) {
    if (isNumeric(service.price)) {
      priceInput.value = formatNumberWithCommas(service.price);
      priceInput.dataset.rawValue = service.price;
    } else {
      priceInput.value = service.price;
      priceInput.dataset.rawValue = service.price;
    }
  } else {
    priceInput.value = "";
    priceInput.dataset.rawValue = "";
  }

  if (service.image) {
    currentImageDiv.innerHTML = `
            <div class="current-image">
                <p>Hình ảnh hiện tại:</p>
                <img src="/libertylaocai/view/img/${service.image}" alt="Current Service" class="current-image-preview">
            </div>
        `;
  } else {
    currentImageDiv.innerHTML = "";
  }

  currentEditingService = service;
}

function populateServiceTourForm(form, tour) {
  const actionInput = document.getElementById("serviceTourAction");
  const idInput = document.getElementById("serviceTourId");
  const titleInputVi = document.getElementById("serviceTourTitle_vi");
  const titleInputEn = document.getElementById("serviceTourTitle_en");
  const priceInput = document.getElementById("serviceTourPrice_vi");
  const currentImageDiv = document.getElementById("currentServiceTourImage");

  if (actionInput) actionInput.value = "update_tour";
  if (idInput) idInput.value = tour.id_dichvu || "";
  if (titleInputVi) titleInputVi.value = tour.title_vi || "";
  if (titleInputEn) titleInputEn.value = tour.title_en || "";

  if (tour.price) {
    if (isNumeric(tour.price)) {
      if (priceInput) priceInput.value = formatNumberWithCommas(tour.price);
      if (priceInput) priceInput.dataset.rawValue = tour.price;
    } else {
      if (priceInput) priceInput.value = tour.price;
      if (priceInput) priceInput.dataset.rawValue = tour.price;
    }
  } else {
    if (priceInput) priceInput.value = "";
    if (priceInput) priceInput.dataset.rawValue = "";
  }

  if (tour.image) {
    if (currentImageDiv) {
      currentImageDiv.innerHTML = `
                <div class="current-image">
                    <p>Hình ảnh hiện tại:</p>
                    <img src="/libertylaocai/view/img/${tour.image}" alt="Current Tour" class="current-image-preview">
                </div>
            `;
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
      deleteGreetingIdInput.value = greeting.id_nhungcauchaohoi || "";
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
  openModal("serviceTourModal", tour);
  const actionInput = document.getElementById("serviceTourAction");
  if (actionInput) actionInput.value = "update_tour";
  const titleElement = document.getElementById("serviceTourModalTitle");
  if (titleElement) titleElement.textContent = "Chỉnh Sửa Tour";
}

function editBanner(banner) {
  openModal("bannerModal", banner);
}

function editHighlight(highlight) {
  const modal = document.getElementById("highlightModal");
  const form = document.getElementById("highlightForm");
  const titleElement = document.getElementById("highlightModalTitle");
  const actionInput = document.getElementById("highlightAction");
  const idInput = document.getElementById("highlightId");
  const titleInputVi = document.getElementById("highlightTitle_vi");
  const contentInputVi = document.getElementById("highlightContent_vi");
  const titleInputEn = document.getElementById("highlightTitle_en");
  const contentInputEn = document.getElementById("highlightContent_en");
  const serviceSelect = document.getElementById("highlightService");

  titleElement.textContent = "Chỉnh Sửa Highlight";
  actionInput.value = "update_highlight";
  idInput.value = highlight.id;
  titleInputVi.value = highlight.title_vi || "";
  contentInputVi.value = highlight.content_vi || "";
  titleInputEn.value = "";
  contentInputEn.value = "";
  serviceSelect.value = highlight.id_dichvu || "";

  loadHighlightEnglishData(highlight.id, (data) => {
    titleInputEn.value = data.title || "";
    contentInputEn.value = data.content || "";
  });

  modal.style.display = "grid";
  document.body.style.overflow = "hidden";
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

  document.body.appendChild(alert);

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

  // Kiểm tra CKEditor fields
  const editorFields = [
    "featureContent_vi",
    "serviceTourContent_vi",
    "editServiceContent_vi",
  ]; // Chỉ tiếng Việt là bắt buộc
  editorFields.forEach((id) => {
    if (editors[id] && !editors[id].getData().trim()) {
      showAlert(
        `Vui lòng điền nội dung cho ${id.replace("_vi", "")} (Tiếng Việt)`,
        "error"
      );
      isValid = false;
    }
  });

  if (!isValid) {
    showAlert("Vui lòng điền đầy đủ thông tin bắt buộc!", "error");
  }

  return isValid;
}

function previewImage(input) {
  const file = input.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      // Tìm thẻ <img> trong cùng modal để hiển thị ảnh xem trước
      const modal = input.closest(".modal");
      const previewId =
        input.id === "serviceTourImage"
          ? "currentServiceTourImage"
          : "currentEditServiceImage";
      const previewDiv = modal.querySelector(`#${previewId}`);
      if (previewDiv) {
        previewDiv.innerHTML = `
                    <div class="current-image">
                        <p>Ảnh xem trước:</p>
                        <img src="${e.target.result}" alt="Image Preview" class="current-image-preview">
                    </div>
                `;
      }
    };
    reader.readAsDataURL(file);
  }
}

function editService(service) {
  const modal = document.getElementById("editServiceModal");
  const form = document.getElementById("editServiceForm");
  const titleElement = document.getElementById("editServiceModalTitle");
  const actionInput = document.getElementById("editServiceAction");
  const idInput = document.getElementById("editServiceId");
  const titleInputVi = document.getElementById("editServiceTitle_vi");
  const titleInputEn = document.getElementById("editServiceTitle_en");
  const priceInput = document.getElementById("editServicePrice_vi");
  const currentImageDiv = document.getElementById("currentEditServiceImage");

  if (!modal || !form) {
    console.error("Modal hoặc form không tồn tại!");
    return;
  }

  destroyModalEditors("editServiceModal");

  titleElement.textContent = "Chỉnh Sửa Dịch Vụ";
  actionInput.value = "update_service";
  idInput.value = service.id_dichvu;

  titleInputVi.value = service.title_vi || "";
  titleInputEn.value = service.title_en || "";

  if (service.price) {
    if (isNumeric(service.price)) {
      priceInput.value = formatNumberWithCommas(service.price);
      priceInput.dataset.rawValue = service.price;
    } else {
      priceInput.value = service.price;
      priceInput.dataset.rawValue = service.price;
    }
  } else {
    priceInput.value = "";
    priceInput.dataset.rawValue = "";
  }

  if (service.image) {
    currentImageDiv.innerHTML = `
            <div class="current-image">
                <p>Hình ảnh hiện tại:</p>
                <img src="/libertylaocai/view/img/${service.image}" alt="Current Service" class="current-image-preview">
            </div>
        `;
  } else {
    currentImageDiv.innerHTML = "";
  }

  modal.style.display = "grid";
  document.body.style.overflow = "hidden";

  initializeModalEditors("editServiceModal").then(() => {
    if (editors["editServiceContent_vi"])
      editors["editServiceContent_vi"].setData(service.content_vi || "");
    if (editors["editServiceContent_en"])
      editors["editServiceContent_en"].setData(service.content_en || "");
  });

  setTimeout(() => {
    const firstInput = modal.querySelector("input, textarea, select");
    if (firstInput) firstInput.focus();
  }, 200);

  currentEditingService = service;
}

function confirmDelete(message = "Bạn có chắc chắn muốn xóa không?") {
  return confirm(message);
}

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

function openServiceTourModal(type) {
  openModal("serviceTourModal");
  const modal = document.getElementById("serviceTourModal");
  const titleElement = document.getElementById("serviceTourModalTitle");
  const actionInput = document.getElementById("serviceTourAction");
  const form = document.getElementById("serviceTourForm");
  const currentImageDiv = document.getElementById("currentServiceTourImage");
  const titleInputVi = document.getElementById("serviceTourTitle_vi");

  if (!modal || !form) {
    console.error("Modal hoặc form không tồn tại!");
    return;
  }

  form.reset();
  clearImagePreviews(form);
  if (currentImageDiv) {
    currentImageDiv.innerHTML = "";
  }

  if (type === "tour") {
    titleElement.textContent = "Thêm Tour Mới";
    actionInput.value = "add_tour";
  } else {
    titleElement.textContent = "Thêm Dịch Vụ Mới";
    actionInput.value = "add_service";
  }

  titleInputVi.value = "";
}

function editFeature(feature) {
  const modal = document.getElementById("featureModal");
  const form = document.getElementById("featureForm");
  const titleElement = document.getElementById("featureModalTitle");
  const actionInput = document.getElementById("featureAction");
  const idInput = document.getElementById("featureId");
  const titleInputVi = document.getElementById("featureTitle_vi");
  const titleInputEn = document.getElementById("featureTitle_en");
  const iconSelect = document.getElementById("featureIconSelect");
  const iconCustomInput = document.getElementById("featureIconCustom");
  const iconHiddenInput = document.getElementById("featureIcon");
  const iconPreview = document.getElementById("iconPreview");

  destroyModalEditors("featureModal");

  titleElement.textContent = "Chỉnh Sửa Tiện Ích";
  actionInput.value = "update_feature";
  idInput.value = feature.id_tienich;
  titleInputVi.value = feature.title || "";
  titleInputEn.value = "";

  fetch("/libertylaocai/user/submit", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `action=get_feature_en&id_tienich=${encodeURIComponent(
      feature.id_tienich
    )}`,
  })
    .then((response) => response.json())
    .then((data) => {
      titleInputEn.value = data.title || "";
      initializeModalEditors("featureModal").then(() => {
        if (editors["featureContent_vi"])
          editors["featureContent_vi"].setData(feature.content || "");
        if (editors["featureContent_en"])
          editors["featureContent_en"].setData(data.content || "");
      });
    })
    .catch((error) =>
      console.error("Lỗi khi lấy dữ liệu tiện ích tiếng Anh:", error)
    );

  const iconOption = Array.from(iconSelect.options).find(
    (option) => option.value === feature.icon
  );
  if (iconOption) {
    iconSelect.value = feature.icon;
    $("#featureIconSelect").val(feature.icon).trigger("change"); // Cập nhật Select2
    iconCustomInput.style.display = "none";
  } else {
    iconSelect.value = "custom";
    $("#featureIconSelect").val("custom").trigger("change"); // Cập nhật Select2
    iconCustomInput.style.display = "block";
    iconCustomInput.value = feature.icon || "";
  }

  iconHiddenInput.value = feature.icon || "";
  iconPreview.innerHTML = feature.icon ? `<i class="${feature.icon}"></i>` : "";

  modal.style.display = "grid";
  document.body.style.overflow = "hidden";
}

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
  editService,
  editHighlight,
  showAlert,
  confirmDelete,
  loadGreeting,
  updateActiveGreetingInputs,
  editFeature,
};
console.log("Admin Dịch Vụ JavaScript loaded successfully!");
