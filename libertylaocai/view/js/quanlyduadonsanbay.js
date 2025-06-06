// Hiển thị thông báo
function showAlert(message, type = "info", duration = 5000) {
  // Xóa các thông báo hiện có
  const existingAlerts = document.querySelectorAll(".alert");
  existingAlerts.forEach((alert) => alert.remove());

  // Tạo phần tử thông báo mới
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

  // Chèn thông báo vào body
  document.body.appendChild(alert);

  // Hiệu ứng biến mất sau duration
  setTimeout(() => {
    if (alert.parentNode) {
      alert.style.opacity = "0";
      alert.style.transform = "translateY(-20px)";
      setTimeout(() => alert.remove(), 300);
    }
  }, duration);
}

// Tải lại danh sách FAQ
function reloadFaqs() {
  fetch("quanlyduadonsanbay.php?action=get_faqs")
    .then((response) => response.json())
    .then((data) => {
      const faqGrid = document.getElementById("faqGrid");
      faqGrid.innerHTML = "";
      data.forEach((faq) => {
        const faqItem = document.createElement("div");
        faqItem.className = "tour-item";
        faqItem.innerHTML = `
                    <div class="tour-header">
                        <h3>${faq.question}</h3>
                        <div class="tour-actions">
                            <button class="btn btn-small btn-secondary" onclick='editFaq(${JSON.stringify(
                              faq
                            )})'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" style="display: inline;" class="deleteFaqForm">
                                <input type="hidden" name="action" value="delete_faq">
                                <input type="hidden" name="id_cauhoithuonggap" value="${
                                  faq.id_cauhoithuonggap
                                }">
                                <input type="hidden" name="id_ngonngu" value="1">
                                <button type="submit" class="btn btn-small btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="tour-content">
                        <p>${faq.answer}</p>
                    </div>
                `;
        faqGrid.appendChild(faqItem);
      });
      bindDeleteFaqForms();
    })
    .catch((error) =>
      showAlert("Lỗi khi tải danh sách FAQ: " + error, "error")
    );
}

// Tải lại danh sách xe
function reloadVehicles() {
  fetch("quanlyduadonsanbay.php?action=get_vehicles")
    .then((response) => response.json())
    .then((data) => {
      const vehicleGrid = document.getElementById("vehicleGrid");
      vehicleGrid.innerHTML = "";
      data.forEach((vehicle) => {
        const vehicleItem = document.createElement("div");
        vehicleItem.className = "tour-item";
        vehicleItem.innerHTML = `
                    <div class="tour-header">
                        <h3>${vehicle.name}</h3>
                        <div class="tour-actions">
                            <button class="btn btn-small btn-secondary" onclick='editVehicle(${JSON.stringify(
                              vehicle
                            )})'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" style="display: inline;" class="deleteVehicleForm">
                                <input type="hidden" name="action" value="delete_vehicle">
                                <input type="hidden" name="id" value="${
                                  vehicle.id
                                }">
                                <button type="submit" class="btn btn-small btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="tour-content">
                        <p>Giá: ${vehicle.price}</p>
                        <p>Số ghế: ${vehicle.number_seat}</p>
                        ${
                          vehicle.image_car
                            ? `<img src="/libertylaocai/view/img/${vehicle.image_car}" alt="${vehicle.name}" class="tour-image-preview">`
                            : ""
                        }
                    </div>
                `;
        vehicleGrid.appendChild(vehicleItem);
      });
      bindDeleteVehicleForms();
    })
    .catch((error) => showAlert("Lỗi khi tải danh sách xe: " + error, "error"));
}

// Xử lý form thêm/cập nhật FAQ
document.getElementById("faqForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const formData = new FormData(this);
  fetch("quanlyduadonsanbay.php", {
    method: "POST",
    body: formData,
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      showAlert(data.message, data.success ? "success" : "error");
      if (data.success) {
        closeModal("faqModal");
        this.reset();
        reloadFaqs();
      }
    })
    .catch((error) => showAlert("Lỗi khi xử lý FAQ: " + error, "error"));
});

// Xử lý form xóa FAQ
function bindDeleteFaqForms() {
  document.querySelectorAll(".deleteFaqForm").forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      if (!confirm("Bạn có chắc muốn xóa câu hỏi này?")) return;
      const formData = new FormData(this);
      fetch("quanlyduadonsanbay.php", {
        method: "POST",
        body: formData,
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((response) => response.json())
        .then((data) => {
          showAlert(data.message, data.success ? "success" : "error");
          if (data.success) {
            reloadFaqs();
          }
        })
        .catch((error) => showAlert("Lỗi khi xóa FAQ: " + error, "error"));
    });
  });
}

// Xử lý form thêm/cập nhật xe
document.getElementById("vehicleForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const formData = new FormData(this);
  fetch("quanlyduadonsanbay.php", {
    method: "POST",
    body: formData,
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      showAlert(data.message, data.success ? "success" : "error");
      if (data.success) {
        closeModal("vehicleModal");
        this.reset();
        document.getElementById("currentVehicleImage").innerHTML = "";
        reloadVehicles();
      }
    })
    .catch((error) => showAlert("Lỗi khi xử lý xe: " + error, "error"));
});

// Xử lý form xóa xe
function bindDeleteVehicleForms() {
  document.querySelectorAll(".deleteVehicleForm").forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      if (!confirm("Bạn có chắc muốn xóa xe này?")) return;
      const formData = new FormData(this);
      fetch("quanlyduadonsanbay.php", {
        method: "POST",
        body: formData,
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((response) => response.json())
        .then((data) => {
          showAlert(data.message, data.success ? "success" : "error");
          if (data.success) {
            reloadVehicles();
          }
        })
        .catch((error) => showAlert("Lỗi khi xóa xe: " + error, "error"));
    });
  });
}

// Các hàm hiện tại
function editFaq(faq) {
  const modal = document.getElementById("faqModal");
  const form = document.getElementById("faqForm");
  const titleElement = document.getElementById("faqModalTitle");
  const actionInput = document.getElementById("faqAction");
  const idInput = document.getElementById("faqId");
  const questionInputVi = document.getElementById("faqQuestion_vi");
  const answerInputVi = document.getElementById("faqAnswer_vi");
  const questionInputEn = document.getElementById("faqQuestion_en");
  const answerInputEn = document.getElementById("faqAnswer_en");

  titleElement.textContent = "Chỉnh Sửa Câu Hỏi";
  actionInput.value = "update_faq";
  idInput.value = faq.id_cauhoithuonggap || "";
  questionInputVi.value = faq.question || "";
  answerInputVi.value = faq.answer || "";
  questionInputEn.value = "";
  answerInputEn.value = "";

  fetch(
    `quanlyduadonsanbay.php?action=get_faq_en&id_cauhoithuonggap=${idInput.value}`
  )
    .then((response) => response.json())
    .then((data) => {
      questionInputEn.value = data.question || "";
      answerInputEn.value = data.answer || "";
    })
    .catch((error) =>
      showAlert("Lỗi khi tải dữ liệu FAQ tiếng Anh: " + error, "error")
    );

  openModal("faqModal");
}

function editVehicle(vehicle) {
  const modal = document.getElementById("vehicleModal");
  const form = document.getElementById("vehicleForm");
  const titleElement = document.getElementById("vehicleModalTitle");
  const actionInput = document.getElementById("vehicleAction");
  const idInput = document.getElementById("vehicleId");
  const nameInputVi = document.getElementById("vehicleName_vi");
  const nameInputEn = document.getElementById("vehicleName_en");
  const priceInput = document.getElementById("vehiclePrice");
  const seatsInput = document.getElementById("vehicleSeats");
  const currentImageDiv = document.getElementById("currentVehicleImage");

  titleElement.textContent = "Chỉnh Sửa Xe";
  actionInput.value = "update_vehicle";
  idInput.value = vehicle.id || "";
  nameInputVi.value = vehicle.name || "";
  nameInputEn.value = "";
  priceInput.value = vehicle.price || "";
  seatsInput.value = vehicle.number_seat || "";

  fetch(
    `quanlyduadonsanbay.php?action=get_vehicle_en&id_xeduadon=${idInput.value}`
  )
    .then((response) => response.json())
    .then((data) => {
      nameInputEn.value = data.name || "";
    })
    .catch((error) =>
      showAlert("Lỗi khi tải dữ liệu xe tiếng Anh: " + error, "error")
    );

  if (vehicle.image_car) {
    currentImageDiv.innerHTML = `
            <div class="current-image">
                <p>Hình ảnh hiện tại:</p>
                <img src="/libertylaocai/view/img/${vehicle.image_car}" alt="Current Vehicle" class="current-image-preview">
            </div>
        `;
  } else {
    currentImageDiv.innerHTML = "";
  }

  openModal("vehicleModal");
}

function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.style.display = "block";
  }
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.style.display = "none";
    switch (modalId) {
      case "greetingModal":
        document.getElementById("addGreetingForm").reset();
        break;
      case "descriptionModal":
        document.getElementById("addDescriptionForm").reset();
        break;
      case "featureModal":
        document.getElementById("featureForm").reset();
        document.getElementById("featureAction").value = "add_feature";
        document.getElementById("customIconInput").style.display = "none";
        document.getElementById("customIconInput").value = "";
        document.getElementById("featureIcon").value = "";
        document.getElementById("iconPreview").className = "";
        break;
      case "faqModal":
        document.getElementById("faqForm").reset();
        document.getElementById("faqAction").value = "add_faq";
        document.getElementById("faqId").value = "";
        break;
      case "vehicleModal":
        document.getElementById("vehicleForm").reset();
        document.getElementById("vehicleAction").value = "add_vehicle";
        document.getElementById("vehicleId").value = "";
        document.getElementById("currentVehicleImage").innerHTML = "";
        break;
    }
  }
}

document.addEventListener("DOMContentLoaded", () => {
  reloadGreetings();
  reloadFaqs();
  reloadVehicles();
});
function reloadGreetings() {
  fetch("quanlyduadonsanbay.php?action=get_greetings")
    .then((response) => response.json())
    .then((data) => {
      const greetingSelect = document.getElementById("greetingSelect");
      const activeGreetingSelect = document.getElementById(
        "activeGreetingSelect"
      );
      greetingSelect.innerHTML =
        '<option value="">-- Chọn lời chào --</option>';
      activeGreetingSelect.innerHTML =
        '<option value="">-- Chọn lời chào --</option>';

      data.greetings.forEach((greeting) => {
        const option = document.createElement("option");
        option.value = JSON.stringify(greeting);
        const contentVi = greeting.content_vi || "Không có nội dung";
        option.textContent = `Lời chào #${
          greeting.id_nhungcauchaohoi
        }: ${contentVi.substring(0, 60)}`;
        greetingSelect.appendChild(option.cloneNode(true));
        activeGreetingSelect.appendChild(option);
      });

      const activeGreetingText = document.getElementById("activeGreetingText");
      if (data.active_greeting) {
        activeGreetingText.innerHTML = `
                    <strong>Tiếng Việt:</strong> ${
                      data.active_greeting.content_vi || "Không có nội dung"
                    }<br>
                    ${
                      data.active_greeting.content_en
                        ? `<strong>Tiếng Anh:</strong> ${
                            data.active_greeting.content_en ||
                            "Không có nội dung"
                          }`
                        : ""
                    }
                `;
      } else {
        activeGreetingText.textContent = "Chưa chọn lời chào";
      }
    })
    .catch((error) =>
      showAlert("Lỗi khi tải danh sách lời chào: " + error, "error")
    );
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
    return;
  }

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

    fetch(
      `quanlyduadonsanbay.php?action=get_greeting_en&id_nhungcauchaohoi=${greeting.id_nhungcauchaohoi}`
    )
      .then((response) => response.json())
      .then((data) => {
        contentInputEn.value = data.content || "";
      })
      .catch((error) =>
        showAlert("Lỗi khi tải dữ liệu lời chào tiếng Anh: " + error, "error")
      );
  } catch (error) {
    showAlert("Lỗi khi tải dữ liệu lời chào: " + error, "error");
    select.value = "";
    greetingIdInput.value = "";
    deleteGreetingIdInput.value = "";
    contentInputVi.value = "";
    contentInputEn.value = "";
    updateBtn.disabled = true;
    deleteBtn.disabled = true;
  }
}

function updateGreetingInputs(select) {
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
    } catch (error) {
      showAlert("Lỗi khi tải dữ liệu lời chào: " + error, "error");
      select.value = "";
      activeGreetingIdInput.value = "";
      updateActiveGreetingBtn.disabled = true;
    }
  }
}

document
  .getElementById("addGreetingForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch("quanlyduadonsanbay.php", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        showAlert(data.message, data.success ? "success" : "error");
        if (data.success) {
          closeModal("greetingModal");
          this.reset();
          reloadGreetings();
        }
      })
      .catch((error) => showAlert("Lỗi khi thêm lời chào: " + error, "error"));
  });

document
  .getElementById("activeGreetingForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch("quanlyduadonsanbay.php", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        showAlert(data.message, data.success ? "success" : "error");
        if (data.success) {
          reloadGreetings();
        }
      })
      .catch((error) =>
        showAlert("Lỗi khi cập nhật lời chào hoạt động: " + error, "error")
      );
  });

document
  .getElementById("greetingForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch("quanlyduadonsanbay.php", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        showAlert(data.message, data.success ? "success" : "error");
        if (data.success) {
          reloadGreetings();
        }
      })
      .catch((error) =>
        showAlert("Lỗi khi cập nhật lời chào: " + error, "error")
      );
  });

document
  .getElementById("deleteGreetingForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    if (!confirm("Bạn có chắc muốn xóa lời chào này?")) return;
    const formData = new FormData(this);
    fetch("quanlyduadonsanbay.php", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        showAlert(data.message, data.success ? "success" : "error");
        if (data.success) {
          reloadGreetings();
          document.getElementById("greetingSelect").value = "";
          document.getElementById("greetingId").value = "";
          document.getElementById("deleteGreetingId").value = "";
          document.getElementById("greetingContent_vi").value = "";
          document.getElementById("greetingContent_en").value = "";
          document.getElementById("updateGreetingBtn").disabled = true;
          document.getElementById("deleteGreetingBtn").disabled = true;
        }
      })
      .catch((error) => showAlert("Lỗi khi xóa lời chào: " + error, "error"));
  });
// Tải lại danh sách mô tả
function reloadDescriptions() {
  fetch("quanlyduadonsanbay.php?action=get_descriptions")
    .then((response) => response.json())
    .then((data) => {
      const descriptionSelect = document.getElementById("descriptionSelect");
      const activeDescriptionSelect = document.getElementById(
        "activeDescriptionSelect"
      );
      descriptionSelect.innerHTML =
        '<option value="">-- Chọn mô tả --</option>';
      activeDescriptionSelect.innerHTML =
        '<option value="">-- Chọn mô tả --</option>';

      data.descriptions.forEach((description) => {
        const option = document.createElement("option");
        option.value = JSON.stringify(description);
        // Kiểm tra title_vi để tránh lỗi null
        const titleVi = description.title_vi || "Không có tiêu đề";
        option.textContent = `Mô tả #${
          description.id_mota
        }: ${titleVi.substring(0, 60)}`;
        descriptionSelect.appendChild(option.cloneNode(true));
        activeDescriptionSelect.appendChild(option);
      });

      const activeDescriptionText = document.getElementById(
        "activeDescriptionText"
      );
      if (data.active_description) {
        activeDescriptionText.innerHTML = `
                  <strong>Tiếng Việt:</strong> ${
                    data.active_description.title_vi || "Không có tiêu đề"
                  }<br>${
          data.active_description.content_vi || "Không có nội dung"
        }<br>
                  ${
                    data.active_description.title_en ||
                    data.active_description.content_en
                      ? `<strong>Tiếng Anh:</strong> ${
                          data.active_description.title_en || "Không có tiêu đề"
                        }<br>${
                          data.active_description.content_en ||
                          "Không có nội dung"
                        }`
                      : ""
                  }
              `;
      } else {
        activeDescriptionText.textContent = "Chưa chọn mô tả";
      }
    })
    .catch((error) =>
      showAlert("Lỗi khi tải danh sách mô tả: " + error, "error")
    );
}

// Tải dữ liệu mô tả vào form chỉnh sửa
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
    titleInputEn.value = "";
    contentInputEn.value = "";
    updateBtn.disabled = true;
    deleteBtn.disabled = true;
    return;
  }

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
    titleInputEn.value = description.title_en || "";
    contentInputEn.value = description.content_en || "";

    updateBtn.disabled = false;
    deleteBtn.disabled = false;
  } catch (e) {
    showAlert("Lỗi khi tải dữ liệu mô tả!", "error");
    select.value = "";
    descriptionIdInput.value = "";
    deleteDescriptionIdInput.value = "";
    titleInputVi.value = "";
    contentInputVi.value = "";
    titleInputEn.value = "";
    contentInputEn.value = "";
    updateBtn.disabled = true;
    deleteBtn.disabled = true;
  }
}

// Cập nhật dữ liệu mô tả hoạt động
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

// Xử lý form thêm/cập nhật mô tả
document
  .getElementById("addDescriptionForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch("quanlyduadonsanbay.php", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        showAlert(data.message, data.success ? "success" : "error");
        if (data.success) {
          closeModal("descriptionModal");
          this.reset();
          reloadDescriptions();
        }
      })
      .catch((error) => showAlert("Lỗi khi thêm mô tả: " + error, "error"));
  });
document
  .getElementById("activeDescriptionForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    console.log("Submitting activeDescriptionForm");
    const formData = new FormData(this);
    fetch("quanlyduadonsanbay.php", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => {
        console.log("Response status:", response.status);
        return response.json();
      })
      .then((data) => {
        console.log("Response data:", data);
        showAlert(data.message, data.success ? "success" : "error");
        if (data.success) {
          reloadDescriptions();
        }
      })
      .catch((error) => {
        console.error("AJAX error:", error);
        showAlert("Lỗi khi cập nhật mô tả hoạt động: " + error, "error");
      });
  });
// Xử lý form cập nhật mô tả
document
  .getElementById("descriptionForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch("quanlyduadonsanbay.php", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        showAlert(data.message, data.success ? "success" : "error");
        if (data.success) {
          reloadDescriptions();
        }
      })
      .catch((error) => showAlert("Lỗi khi cập nhật mô tả: " + error, "error"));
  });

// Xử lý form xóa mô tả
document
  .getElementById("deleteDescriptionForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    if (!confirm("Bạn có chắc muốn xóa mô tả này?")) return;
    const formData = new FormData(this);
    fetch("quanlyduadonsanbay.php", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        showAlert(data.message, data.success ? "success" : "error");
        if (data.success) {
          reloadDescriptions();
          document.getElementById("descriptionSelect").value = "";
          document.getElementById("descriptionId").value = "";
          document.getElementById("deleteDescriptionId").value = "";
          document.getElementById("descriptionTitle_vi").value = "";
          document.getElementById("descriptionContent_vi").value = "";
          document.getElementById("descriptionTitle_en").value = "";
          document.getElementById("descriptionContent_en").value = "";
          document.getElementById("updateDescriptionBtn").disabled = true;
          document.getElementById("deleteDescriptionBtn").disabled = true;
        }
      })
      .catch((error) => showAlert("Lỗi khi xóa mô tả: " + error, "error"));
  });

// Tải lại danh sách tiện ích
function reloadFeatures() {
  fetch("quanlyduadonsanbay.php?action=get_features")
    .then((response) => response.json())
    .then((data) => {
      const featureGrid = document.getElementById("featureGrid");
      featureGrid.innerHTML = "";
      data.forEach((feature) => {
        const featureItem = document.createElement("div");
        featureItem.className = "tour-item";
        featureItem.id = `feature_${feature.id_tienich}`;
        featureItem.innerHTML = `
                    <div class="tour-header">
                        <h3><i class="${feature.icon}"></i> ${feature.title}</h3>
                        <div class="tour-actions">
                            <button class="btn btn-small btn-secondary" onclick="editFeature(${feature.id_tienich})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" style="display: inline;" class="deleteFeatureForm">
                                <input type="hidden" name="action" value="delete_feature">
                                <input type="hidden" name="id_tienich" value="${feature.id_tienich}">
                                <button type="submit" class="btn btn-small btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="tour-content">
                        <p>${feature.content}</p>
                    </div>
                `;
        featureGrid.appendChild(featureItem);
      });
      bindDeleteFeatureForms();
    })
    .catch((error) =>
      showAlert("Lỗi khi tải danh sách tiện ích: " + error, "error")
    );
}

// Xử lý chỉnh sửa tiện ích
function editFeature(id_tienich) {
  fetch(`quanlyduadonsanbay.php?action=get_feature&id_tienich=${id_tienich}`)
    .then((response) => response.json())
    .then((data) => {
      const modal = document.getElementById("featureModal");
      const form = document.getElementById("featureForm");
      const titleElement = document.getElementById("featureModalTitle");
      const actionInput = document.getElementById("featureAction");
      const idInput = document.getElementById("featureId");
      const iconSelect = document.getElementById("featureIcon");
      const customIconInput = document.getElementById("customIconInput");
      const iconPreview = document.getElementById("iconPreview");
      const titleInputVi = document.getElementById("featureTitle_vi");
      const contentInputVi = document.getElementById("featureContent_vi");
      const titleInputEn = document.getElementById("featureTitle_en");
      const contentInputEn = document.getElementById("featureContent_en");

      titleElement.textContent = "Chỉnh Sửa Tiện Ích";
      actionInput.value = "update_feature";
      idInput.value = data.id_tienich || "";
      titleInputVi.value = data.title || "";
      contentInputVi.value = data.content || "";
      titleInputEn.value = "";
      contentInputEn.value = "";

      if (data.icon) {
        if (iconSelect.querySelector(`option[value="${data.icon}"]`)) {
          iconSelect.value = data.icon;
          customIconInput.style.display = "none";
        } else {
          iconSelect.value = "custom";
          customIconInput.value = data.icon;
          customIconInput.style.display = "block";
        }
        iconPreview.className = data.icon;
      } else {
        iconSelect.value = "";
        customIconInput.style.display = "none";
        iconPreview.className = "";
      }

      fetch(
        `quanlyduadonsanbay.php?action=get_feature_en&id_tienich=${id_tienich}`
      )
        .then((response) => response.json())
        .then((data) => {
          titleInputEn.value = data.title || "";
          contentInputEn.value = data.content || "";
        })
        .catch((error) =>
          showAlert("Lỗi khi tải dữ liệu tiện ích tiếng Anh: " + error, "error")
        );

      openModal("featureModal");
    })
    .catch((error) =>
      showAlert("Lỗi khi tải dữ liệu tiện ích: " + error, "error")
    );
}

// Xử lý form thêm/cập nhật tiện ích
document.getElementById("featureForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const formData = new FormData(this);
  const customIcon = document.getElementById("customIconInput").value;
  if (document.getElementById("featureIcon").value === "custom" && customIcon) {
    formData.set("icon", customIcon);
  }
  fetch("quanlyduadonsanbay.php", {
    method: "POST",
    body: formData,
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      showAlert(data.message, data.success ? "success" : "error");
      if (data.success) {
        closeModal("featureModal");
        this.reset();
        document.getElementById("customIconInput").value = "";
        document.getElementById("customIconInput").style.display = "none";
        document.getElementById("featureIcon").value = "";
        document.getElementById("iconPreview").className = "";
        reloadFeatures();
      }
    })
    .catch((error) => showAlert("Lỗi khi xử lý tiện ích: " + error, "error"));
});

// Xử lý form xóa tiện ích
function bindDeleteFeatureForms() {
  document.querySelectorAll(".deleteFeatureForm").forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      if (!confirm("Bạn có chắc muốn xóa tiện ích này?")) return;
      const formData = new FormData(this);
      fetch("quanlyduadonsanbay.php", {
        method: "POST",
        body: formData,
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((response) => response.json())
        .then((data) => {
          showAlert(data.message, data.success ? "success" : "error");
          if (data.success) {
            reloadFeatures();
          }
        })
        .catch((error) => showAlert("Lỗi khi xóa tiện ích: " + error, "error"));
    });
  });
}

// Cập nhật biểu tượng
function updateIcon(select) {
  const customIconInput = document.getElementById("customIconInput");
  const iconPreview = document.getElementById("iconPreview");
  if (select.value === "custom") {
    customIconInput.style.display = "block";
    iconPreview.className = customIconInput.value || "";
  } else {
    customIconInput.style.display = "none";
    customIconInput.value = "";
    iconPreview.className = select.value;
  }
}

// Cập nhật sự kiện khi thay đổi icon tùy chỉnh
document
  .getElementById("customIconInput")
  .addEventListener("input", function () {
    const iconPreview = document.getElementById("iconPreview");
    if (document.getElementById("featureIcon").value === "custom") {
      iconPreview.className = this.value;
    }
  });

// Cập nhật DOMContentLoaded để tải thêm dữ liệu
document.addEventListener("DOMContentLoaded", () => {
  reloadGreetings();
  reloadDescriptions();
  reloadFaqs();
  reloadVehicles();
  reloadFeatures();
});
function openAddModal(modalId) {
  const modal = document.getElementById(modalId);
  if (!modal) return;

  switch (modalId) {
    case "greetingModal":
      document.getElementById("addGreetingForm").reset();
      document
        .getElementById("greetingModal")
        .querySelector(".modal-header h3").textContent = "Thêm Lời Chào Mới";
      break;
    case "descriptionModal":
      document.getElementById("addDescriptionForm").reset();
      document
        .getElementById("descriptionModal")
        .querySelector(".modal-header h3").textContent = "Thêm Mô Tả Mới";
      break;
    case "featureModal":
      document.getElementById("featureForm").reset();
      document.getElementById("featureModalTitle").textContent =
        "Thêm Tiện Ích Mới";
      document.getElementById("featureAction").value = "add_feature";
      document.getElementById("customIconInput").style.display = "none";
      document.getElementById("customIconInput").value = "";
      document.getElementById("featureIcon").value = "";
      document.getElementById("iconPreview").className = "";
      break;
    case "faqModal":
      document.getElementById("faqForm").reset();
      document.getElementById("faqModalTitle").textContent = "Thêm Câu Hỏi Mới";
      document.getElementById("faqAction").value = "add_faq";
      document.getElementById("faqId").value = "";
      break;
    case "vehicleModal":
      document.getElementById("vehicleForm").reset();
      document.getElementById("vehicleModalTitle").textContent = "Thêm Xe Mới";
      document.getElementById("vehicleAction").value = "add_vehicle";
      document.getElementById("vehicleId").value = "";
      document.getElementById("currentVehicleImage").innerHTML = "";
      break;
  }

  modal.style.display = "block";
}
