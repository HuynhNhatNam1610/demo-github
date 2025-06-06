let selectedFiles = [];

function showTab(tabName) {
  const panes = document.querySelectorAll(".tab-pane");
  panes.forEach((pane) => pane.classList.remove("active"));

  const buttons = document.querySelectorAll(".tab-btn");
  buttons.forEach((btn) => btn.classList.remove("active"));

  document.getElementById(tabName).classList.add("active");
  document
    .querySelector(`[onclick="showTab('${tabName}')"]`)
    .classList.add("active");

  if (tabName === "booking") {
    clearImagePreviews();
  }
}

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
      console.log("Clicked upload-text, triggering imageUpload.click()");
      imageUpload.click();
    };
  }

  imageUpload.addEventListener("change", function (e) {
    console.log("Change event triggered on imageUpload");
    const files = e.target.files;
    if (!files.length) return;

    console.log(
      "Selected new files:",
      Array.from(files).map((f) => f.name)
    );

    const maxTotalFiles = 5;
    let validNewFiles = [];

    Array.from(files).forEach((file) => {
      const isDuplicate = selectedFiles.some(
        (existingFile) =>
          existingFile.name === file.name && existingFile.size === file.size
      );

      if (isDuplicate) {
        alert(
          languageId == 1
            ? `Tệp ${file.name} đã được chọn trước đó.`
            : `File ${file.name} has already been selected.`
        );
        return;
      }

      validNewFiles.push(file);
    });

    if (selectedFiles.length + validNewFiles.length > maxTotalFiles) {
      const remainingSlots = maxTotalFiles - selectedFiles.length;
      if (remainingSlots > 0) {
        alert(
          languageId == 1
            ? `Chỉ có thể thêm ${remainingSlots} ảnh nữa. Tối đa ${maxTotalFiles} ảnh.`
            : `Only ${remainingSlots} more images can be added. Maximum ${maxTotalFiles} images.`
        );
        validNewFiles = validNewFiles.slice(0, remainingSlots);
      } else {
        alert(
          languageId == 1
            ? `Đã đạt giới hạn tối đa ${maxTotalFiles} ảnh.`
            : `Maximum limit of ${maxTotalFiles} images reached.`
        );
        return;
      }
    }

    if (validNewFiles.length > 0) {
      selectedFiles = selectedFiles.concat(validNewFiles);
      console.log(
        "Updated selectedFiles:",
        selectedFiles.map((f) => f.name)
      );
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
            ${
              languageId == 1
                ? "Nhấp để tải lên hình ảnh tham khảo"
                : "Click to upload reference images"
            }<br>
            <small>${
              languageId == 1
                ? "Có thể tải lên nhiều hình ảnh"
                : "Multiple images can be uploaded"
            }</small>
        </div>
    `;
  uploadArea.appendChild(imageUpload);
  uploadArea.value = "";
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
  console.log(
    "Updated imageUpload.files:",
    imageUpload.files.length,
    Array.from(imageUpload.files).map((f) => f.name)
  );
}

function renderImagePreviews() {
  const uploadArea = document.querySelector(".upload-area");
  const imageUpload = document.getElementById("imageUpload");
  if (!uploadArea || !imageUpload) return;

  console.log(
    "Rendering previews for selectedFiles:",
    selectedFiles.length,
    selectedFiles.map((f) => f.name)
  );

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
      console.log(
        "Removed file, new selectedFiles:",
        selectedFiles.map((f) => f.name)
      );
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
            <span class="upload-count">${
              languageId == 1 ? "Đã chọn" : "Selected"
            } ${selectedFiles.length} ${
    languageId == 1 ? "hình ảnh" : "images"
  }</span>
            <button class="add-more-btn" type="button">${
              languageId == 1 ? "Thêm ảnh" : "Add more"
            }</button>
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

document.addEventListener("DOMContentLoaded", function () {
  const quickBookingForm = document.getElementById("quickBookingForm");
  if (quickBookingForm) {
    quickBookingForm.addEventListener("submit", function (e) {
      e.preventDefault();
      e.stopImmediatePropagation();

      // Kiểm tra các trường bắt buộc bằng HTML5 validation
      let isValid = quickBookingForm.checkValidity();

      // Kiểm tra các điều kiện khác ngoài required
      const startDate = new Date(
        document.getElementById("quickEventDate")?.value
      );
      const endDate = new Date(document.getElementById("quickEndDate")?.value);
      const today = new Date();
      today.setHours(0, 0, 0, 0);

      if (startDate < today) {
        alert(
          languageId == 1
            ? "Ngày bắt đầu không thể là ngày trong quá khứ"
            : "Start date cannot be in the past"
        );
        isValid = false;
      }

      if (endDate < startDate) {
        alert(
          languageId == 1
            ? "Ngày kết thúc không thể trước ngày bắt đầu"
            : "End date cannot be before start date"
        );
        isValid = false;
      }

      const email = document.getElementById("quickEmail")?.value;
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (email && !emailRegex.test(email)) {
        alert(
          languageId == 1
            ? "Email không đúng định dạng"
            : "Invalid email format"
        );
        isValid = false;
      }

      const phone = document.getElementById("quickPhone")?.value;
      const phoneRegex = /^[0-9+\-\s\(\)]+$/;
      if (phone && (!phoneRegex.test(phone) || phone.length < 10)) {
        alert(
          languageId == 1
            ? "Số điện thoại không hợp lệ"
            : "Invalid phone number"
        );
        isValid = false;
      }

      if (selectedFiles.length > 5) {
        alert(
          languageId == 1
            ? "Chỉ được phép tải lên tối đa 5 hình ảnh"
            : "Only a maximum of 5 images can be uploaded"
        );
        isValid = false;
      }

      if (isValid) {
        const formData = new FormData(quickBookingForm);
        formData.append("submit_booking", "true");
        selectedFiles.forEach((file, index) => {
          formData.append(`images[]`, file);
        });

        console.log("FormData entries:");
        for (let [key, value] of formData.entries()) {
          console.log(
            key,
            value instanceof File ? `File: ${value.name}` : value
          );
        }

        fetch("/libertylaocai/user/submit", {
          method: "POST",
          body: formData,
        })
          .then((response) => {
            console.log("Fetch response status:", response.status);
            if (!response.ok) {
              throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
          })
          .then((data) => {
            console.log("Server response:", data);
            if (data.status === "success") {
              alert(data.message);
              quickBookingForm.reset();
              selectedFiles = [];
              clearImagePreviews();
              showTab("description");
            } else {
              alert(data.message);
            }
          })
          .catch((error) => {
            console.error("Fetch error:", error);
            alert(
              languageId == 1
                ? "Có lỗi khi gửi yêu cầu. Vui lòng thử lại."
                : "An error occurred while sending the request. Please try again."
            );
          });
      } else {
        // Hiển thị thông báo lỗi HTML5 mặc định
        quickBookingForm.reportValidity();
      }
    });

    const submitButton = quickBookingForm.querySelector(".btn-submit");
    if (submitButton) {
      submitButton.addEventListener("click", function (e) {
        e.preventDefault();
        quickBookingForm.dispatchEvent(new Event("submit"));
      });
    }
  }

  const eventDateInput = document.getElementById("quickEventDate");
  const endDateInput = document.getElementById("quickEndDate");
  if (eventDateInput && endDateInput) {
    eventDateInput.min = new Date().toISOString().split("T")[0];
    endDateInput.min = new Date().toISOString().split("T")[0];

    eventDateInput.addEventListener("change", function () {
      endDateInput.min = this.value;
      if (endDateInput.value < this.value) {
        endDateInput.value = this.value;
      }
    });
  }

  const slider = document.querySelector(".gallery-slider");
  const items = document.querySelectorAll(".gallery-item");
  const prevBtn = document.querySelector(".prev-btn");
  const nextBtn = document.querySelector(".next-btn");
  const dots = document.querySelectorAll(".dot");
  let currentIndex = 0;

  function updateSlider() {
    slider.style.transform = `translateX(-${currentIndex * 100}%)`;
    dots.forEach((dot, index) => {
      dot.classList.toggle("active", index === currentIndex);
    });
  }

  prevBtn.addEventListener("click", () => {
    currentIndex = (currentIndex - 1 + items.length) % items.length;
    updateSlider();
  });

  nextBtn.addEventListener("click", () => {
    currentIndex = (currentIndex + 1) % items.length;
    updateSlider();
  });

  dots.forEach((dot, index) => {
    dot.addEventListener("click", () => {
      currentIndex = index;
      updateSlider();
    });
  });

  updateSlider();

  const specSliders = document.querySelectorAll(".spec-img");
  specSliders.forEach((sliderContainer) => {
    const slider = sliderContainer.querySelector(".spec-img-slider");
    const items = sliderContainer.querySelectorAll(".spec-img-item");
    const dots = sliderContainer.querySelectorAll(".spec-dot");
    const prevBtn = sliderContainer.querySelector(".spec-prev-btn");
    const nextBtn = sliderContainer.querySelector(".spec-next-btn");
    let currentIndex = 0;

    function updateSlider() {
      slider.style.transform = `translateX(-${currentIndex * 100}%)`;
      dots.forEach((dot, index) => {
        dot.classList.toggle("active", index === currentIndex);
      });
    }

    prevBtn.addEventListener("click", () => {
      currentIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
      updateSlider();
    });

    nextBtn.addEventListener("click", () => {
      currentIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
      updateSlider();
    });

    dots.forEach((dot) => {
      dot.addEventListener("click", () => {
        currentIndex = parseInt(dot.getAttribute("data-index"));
        updateSlider();
      });
    });

    updateSlider();
  });

  attachImageUploadListener();
});

document.getElementById("quickBudget").addEventListener("input", function (e) {
  let value = e.target.value.replace(/[^0-9]/g, ""); // Loại bỏ ký tự không phải số
  if (value === "") {
    e.target.value = "";
    return;
  }
  // Định dạng VNĐ với dấu phẩy phân cách hàng nghìn
  e.target.value = Number(value).toLocaleString("vi-VN") + " đ";
});
