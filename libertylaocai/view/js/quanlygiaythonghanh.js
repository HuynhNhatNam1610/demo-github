document.addEventListener("DOMContentLoaded", function () {
  initializeEventListeners();
  autoHideAlerts();
});

// Hàm gửi yêu cầu AJAX chung

function sendAjaxRequest(form, callback) {
  const formData = new FormData(form);
  const submitBtn = form.querySelector('button[type="submit"]');

  showLoadingState(submitBtn);

  fetch("quanlygiaythonghanh.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      hideLoadingState(submitBtn);
      if (data.success) {
        showAlert(data.message, "success");
        if (callback) callback(data);

        const action = formData.get("action");
        if (action === "add_greeting") {
          updateGreetingSelectOptions(data.data || {});
          closeModal("greetingModal");
          const modalForm = document.getElementById("addGreetingForm");
          if (modalForm) modalForm.reset();
          setTimeout(() => location.reload(), 1500); // Tăng lên 1.5s
        } else if (
          action === "update_greeting" ||
          action === "update_active_greeting"
        ) {
          location.reload();
        }
      } else {
        showAlert(data.message, "error");
        if (formData.get("action") === "add_greeting") {
          closeModal("greetingModal"); // Đóng modal ngay cả khi server trả về lỗi
        }
      }
    })
    .catch((error) => {
      hideLoadingState(submitBtn);
      showAlert("Lỗi kết nối máy chủ! Vui lòng thử lại.", "error");
      console.error("Error:", error);
      if (formData.get("action") === "add_greeting") {
        closeModal("greetingModal"); // Đóng modal trong trường hợp lỗi
      }
    });
}
// Sửa initializeEventListeners để sử dụng AJAX
function initializeEventListeners() {
  const forms = document.querySelectorAll("form");
  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      if (!validateForm(this)) {
        return;
      }

      const action = form.querySelector('input[name="action"]').value;
      const submitBtn = form.querySelector('button[type="submit"]');

      if (action === "delete_greeting") {
        if (!confirmDelete("Bạn có chắc chắn muốn xóa lời chào này?")) {
          return;
        }
        const idInput = form.querySelector("#deleteGreetingId");
        if (!idInput.value) {
          showAlert("Vui lòng chọn một lời chào để xóa!", "error");
          return;
        }
        sendAjaxRequest(form, () => {
          updateGreetingSelectOptions({ id: idInput.value }, "delete");
          form.reset();
          document.getElementById("updateGreetingBtn").disabled = true;
          document.getElementById("deleteGreetingBtn").disabled = true;
          location.reload();
        });
        return;
      }

      if (action === "update_active_greeting") {
        const idInput = form.querySelector("#activeGreetingId");
        if (!idInput.value) {
          showAlert("Vui lòng chọn một lời chào để kích hoạt!", "error");
          return;
        }
      }

      if (action === "delete_description") {
        if (!confirmDelete("Bạn có chắc chắn muốn xóa mô tả này?")) {
          return;
        }
        const idInput = form.querySelector("#deleteDescriptionId");
        if (!idInput.value) {
          showAlert("Vui lòng chọn một mô tả để xóa!", "error");
          return;
        }
        sendAjaxRequest(form, () => {
          updateDescriptionSelectOptions({ id: idInput.value }, "delete");
          form.reset();
          document.getElementById("updateDescriptionBtn").disabled = true;
          document.getElementById("deleteDescriptionBtn").disabled = true;
          location.reload();
        });
        return;
      }

      if (action === "update_active_description") {
        const idInput = form.querySelector("#activeDescriptionId");
        if (!idInput.value) {
          showAlert("Vui lòng chọn một mô tả để kích hoạt!", "error");
          return;
        }
      }

      if (action === "delete_feature") {
        if (!confirmDelete("Bạn có chắc chắn muốn xóa tiện ích này?")) {
          return;
        }
        const idInput = form.querySelector('input[name="id_tienich"]');
        if (!idInput.value) {
          showAlert("Vui lòng chọn một tiện ích để xóa!", "error");
          return;
        }
        sendAjaxRequest(form, () => {
          const featureElement = document
            .querySelector(
              `.tour-item input[name="id_tienich"][value="${idInput.value}"]`
            )
            .closest(".tour-item");
          if (featureElement) {
            featureElement.remove();
          }
          showAlert("Xóa tiện ích thành công!", "success");
        });
        return;
      }

      // Gọi sendAjaxRequest chung, không xử lý riêng add_greeting
      sendAjaxRequest(form, (data) => {
        if (
          action === "add_description" ||
          action === "add_feature" ||
          action === "update_feature" ||
          action === "update_service" ||
          action === "update_description" ||
          action === "update_active_description" ||
          action === "update_greeting" ||
          action === "update_active_greeting"
        ) {
          location.reload();
        }
      });
    });
  });

  // Sự kiện cho modal
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

function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (!modal) return;

  const form = modal.querySelector("form");
  if (form) {
    form.reset();
  }

  // Đặt lại trạng thái cho modal tiện ích
  if (modalId === "featureModal") {
    const titleElement = document.getElementById("featureModalTitle");
    const actionInput = document.getElementById("featureAction");
    const idInput = document.getElementById("featureId");
    const iconSelect = document.getElementById("featureIconSelect");
    const iconCustomInput = document.getElementById("featureIconCustom");
    const iconHiddenInput = document.getElementById("featureIcon");
    const iconPreview = document.getElementById("iconPreview");

    // Đặt lại tiêu đề và hành động
    titleElement.textContent = "Thêm Tiện Ích Mới";
    actionInput.value = "add_feature";
    idInput.value = "";

    // Đặt lại các trường icon
    iconSelect.value = "";
    iconCustomInput.style.display = "none";
    iconCustomInput.value = "";
    iconHiddenInput.value = "";
    iconPreview.innerHTML = "";
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
  }

  // Đặt lại các trường icon cho featureModal
  if (modalId === "featureModal") {
    const iconSelect = document.getElementById("featureIconSelect");
    const iconCustomInput = document.getElementById("featureIconCustom");
    const iconHiddenInput = document.getElementById("featureIcon");
    const iconPreview = document.getElementById("iconPreview");

    iconSelect.value = "";
    iconCustomInput.style.display = "none";
    iconCustomInput.value = "";
    iconHiddenInput.value = "";
    iconPreview.innerHTML = "";
  }
}
function closeAllModals() {
  const modals = document.querySelectorAll(".modal");
  modals.forEach((modal) => {
    modal.style.display = "none";
  });
  document.body.style.overflow = "";
}
function loadGreeting(select) {
  const updateBtn = document.getElementById("updateGreetingBtn");
  const deleteBtn = document.getElementById("deleteGreetingBtn");
  const greetingIdInput = document.getElementById("greetingId");
  const deleteGreetingIdInput = document.getElementById("deleteGreetingId");
  const contentInputVi = document.getElementById("greetingContent_vi");
  const contentInputEn = document.getElementById("greetingContent_en");

  if (select.value === "") {
    greetingIdInput.value = "";
    deleteGreetingIdInput.value = "";
    contentInputVi.value = "";
    contentInputEn.value = "";
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
      greetingIdInput.value = greeting.id_nhungcauchaohoi || "";
      deleteGreetingIdInput.value = greeting.id_nhungcauchaohoi || "";
      contentInputVi.value = greeting.content_vi || "";
      contentInputEn.value = greeting.content_en || "";
      updateBtn.disabled = false;
      deleteBtn.disabled = false;
    } catch (e) {
      showAlert("Lỗi khi tải dữ liệu lời chào!", "error");
      select.value = "";
      greetingIdInput.value = "";
      deleteGreetingIdInput.value = "";
      contentInputVi.value = "";
      contentInputEn.value = "";
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
      if (!greeting.id_nhungcauchaohoi) {
        showAlert("Dữ liệu lời chào không hợp lệ!", "error");
        select.value = "";
        return;
      }
      activeGreetingIdInput.value = greeting.id_nhungcauchaohoi || "";
      updateActiveGreetingBtn.disabled = false;
    } catch (e) {
      showAlert("Lỗi khi tải dữ liệu lời chào!", "error");
      select.value = "";
      activeGreetingIdInput.value = "";
      updateActiveGreetingBtn.disabled = true;
    }
  }
}

function updateGreetingSelectOptions(data, action = "add") {
  const select = document.getElementById("greetingSelect");
  const activeSelect = document.getElementById("activeGreetingSelect");

  if (action === "delete") {
    const options = select.querySelectorAll(`option[value*="${data.id}"]`);
    options.forEach((option) => option.remove());
    const activeOptions = activeSelect.querySelectorAll(
      `option[value*="${data.id}"]`
    );
    activeOptions.forEach((option) => option.remove());
  } else {
    const newOption = document.createElement("option");
    newOption.value = JSON.stringify(data);
    newOption.text = `Lời chào #${
      data.id_nhungcauchaohoi
    }: ${data.content_vi.substring(0, 60)}`;
    select.appendChild(newOption);
    activeSelect.appendChild(newOption.cloneNode(true));
  }
}
// Hàm cập nhật danh sách select cho mô tả
function updateDescriptionSelectOptions(data, action = "add") {
  const select = document.getElementById("descriptionSelect");
  const activeSelect = document.getElementById("activeDescriptionSelect");

  if (action === "delete") {
    const options = select.querySelectorAll(`option[value*="${data.id}"]`);
    options.forEach((option) => option.remove());
    const activeOptions = activeSelect.querySelectorAll(
      `option[value*="${data.id}"]`
    );
    activeOptions.forEach((option) => option.remove());
  } else {
    const newOption = document.createElement("option");
    newOption.value = JSON.stringify(data); // Dữ liệu đầy đủ
    newOption.text = `Mô tả #${data.id_mota}: ${
      data.title_vi || data.content_vi.substring(0, 60)
    }`;
    select.appendChild(newOption);
    activeSelect.appendChild(newOption.cloneNode(true));
  }
}

// Hàm tải mô tả
function loadDescription(select) {
  const updateBtn = document.getElementById("updateDescriptionBtn");
  const deleteBtn = document.getElementById("deleteDescriptionBtn");
  const descriptionIdInput = document.getElementById("descriptionId");
  const deleteDescriptionIdInput = document.getElementById(
    "deleteDescriptionId"
  );
  const titleInputVi = document.getElementById("descriptionTitle_vi");
  const contentInputVi = document.getElementById("descriptionContent_vi");
  const titleInputEn = document.getElementById("descriptionTitle_en");
  const contentInputEn = document.getElementById("descriptionContent_en");

  if (select.value === "") {
    descriptionIdInput.value = "";
    deleteDescriptionIdInput.value = "";
    titleInputVi.value = "";
    contentInputVi.value = "";
    if (titleInputEn) titleInputEn.value = "";
    if (contentInputEn) contentInputEn.value = "";
    updateBtn.disabled = true;
    deleteBtn.disabled = true;
  } else {
    try {
      const description = JSON.parse(select.value);
      if (!description.id_mota) {
        showAlert("Dữ liệu mô tả không hợp lệ!", "error");
        select.value = "";
        return;
      }
      descriptionIdInput.value = description.id_mota || "";
      deleteDescriptionIdInput.value = description.id_mota || "";
      titleInputVi.value = description.title_vi || "";
      contentInputVi.value = description.content_vi || "";
      if (titleInputEn) {
        titleInputEn.value = description.title_en || "";
      }
      if (contentInputEn) {
        contentInputEn.value = description.content_en || "";
      }
      updateBtn.disabled = false;
      deleteBtn.disabled = false;
    } catch (e) {
      showAlert("Lỗi khi tải dữ liệu mô tả!", "error");
      select.value = "";
      descriptionIdInput.value = "";
      deleteDescriptionIdInput.value = "";
      titleInputVi.value = "";
      contentInputVi.value = "";
      if (titleInputEn) titleInputEn.value = "";
      if (contentInputEn) contentInputEn.value = "";
      updateBtn.disabled = true;
      deleteBtn.disabled = true;
    }
  }
}

// Hàm cập nhật đầu vào cho mô tả hoạt động
function updateActiveDescriptionInputs(select) {
  const activeDescriptionIdInput = document.getElementById(
    "activeDescriptionId"
  );
  const updateActiveDescriptionBtn = document.getElementById(
    "updateActiveDescriptionBtn"
  );

  if (select.value === "") {
    activeDescriptionIdInput.value = "";
    updateActiveDescriptionBtn.disabled = true;
  } else {
    try {
      const description = JSON.parse(select.value);
      if (!description.id_mota) {
        showAlert("Dữ liệu mô tả không hợp lệ!", "error");
        select.value = "";
        return;
      }
      activeDescriptionIdInput.value = description.id_mota || "";
      updateActiveDescriptionBtn.disabled = false;
    } catch (e) {
      showAlert("Lỗi khi tải dữ liệu mô tả!", "error");
      select.value = "";
      activeDescriptionIdInput.value = "";
      updateActiveDescriptionBtn.disabled = true;
    }
  }
}
function showLoadingState(button) {
  const originalText = button.innerHTML;
  button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
  button.disabled = true;
  button.style.opacity = "0.7";
  button.dataset.originalText = originalText;
}

function hideLoadingState(button) {
  button.innerHTML = button.dataset.originalText;
  button.disabled = false;
  button.style.opacity = "1";
  delete button.dataset.originalText;
}

function showAlert(message, type = "info", duration = 7000) {
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
      alert.style.transform = "translateY(-30px)";
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

function confirmDelete(message) {
  return confirm(message);
}

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
  const titleInput = document.getElementById("featureTitle");
  const contentInput = document.getElementById("featureContent");
  const titleInputEn = document.getElementById("featureTitleEn");
  const contentInputEn = document.getElementById("featureContentEn");
  const iconSelect = document.getElementById("featureIconSelect");
  const iconCustomInput = document.getElementById("featureIconCustom");
  const iconHiddenInput = document.getElementById("featureIcon");
  const iconPreview = document.getElementById("iconPreview");

  titleElement.textContent = "Chỉnh Sửa Tiện Ích";
  actionInput.value = "update_feature";
  idInput.value = feature.id_tienich;
  titleInput.value = feature.title || "";
  contentInput.value = feature.content || "";

  // Gọi AJAX để lấy dữ liệu tiếng Anh
  fetch(
    `quanlygiaythonghanh.php?action=get_english_feature&id_tienich=${feature.id_tienich}`
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        titleInputEn.value = data.title || "";
        contentInputEn.value = data.content || "";
      } else {
        titleInputEn.value = "";
        contentInputEn.value = "";
      }
    })
    .catch((error) => {
      console.error("Lỗi khi lấy dữ liệu tiếng Anh:", error);
      titleInputEn.value = "";
      contentInputEn.value = "";
    });

  // Cập nhật icon
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

window.GiayThongHanhAdmin = {
  openModal,
  closeModal,
  loadDescription,
  updateActiveDescriptionInputs,
  loadGreeting,
  updateActiveGreetingInputs,
  showAlert,
  confirmDelete,
  editFeature,
  updateIcon,
};
