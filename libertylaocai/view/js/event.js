// Định nghĩa hàm attachImageUploadListener ở phạm vi toàn cục
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

// Định nghĩa các hàm khác
function openModal() {
  const modal = document.getElementById("bookingModal");
  if (modal) {
    modal.style.display = "block";
    document.body.style.overflow = "hidden";
  }
}

function closeModal() {
  const modal = document.getElementById("bookingModal");
  if (modal) {
    modal.style.display = "none";
    document.body.style.overflow = "auto";
  }
  clearImagePreviews();
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

// Biến toàn cục
let selectedFiles = [];

document.addEventListener("DOMContentLoaded", function () {
  window.onclick = function (event) {
    const modal = document.getElementById("bookingModal");
    if (event.target === modal) {
      closeModal();
    }
  };

  const closeButton = document.querySelector(".close");
  if (closeButton) {
    closeButton.onclick = closeModal;
  }

  const bookingForm = document.getElementById("bookingForm");
  if (bookingForm) {
    bookingForm.addEventListener("submit", function (e) {
      e.preventDefault();
      e.stopImmediatePropagation();

      const requiredFields = [
        "fullName",
        "phone",
        "email",
        "eventType",
        "guestCount",
        "eventDate",
        "endDate",
        "startTime",
        "endTime",
        "venue",
        "description",
      ];
      let isValid = true;

      requiredFields.forEach((field) => {
        const input = document.getElementById(field);
        if (!input || !input.value.trim()) {
          if (input) input.style.borderColor = "#e74c3c";
          isValid = false;
        } else {
          if (input) input.style.borderColor = "#e0e0e0";
        }
      });

      const startDate = new Date(document.getElementById("eventDate")?.value);
      const endDate = new Date(document.getElementById("endDate")?.value);
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

      const email = document.getElementById("email")?.value;
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (email && !emailRegex.test(email)) {
        alert(
          languageId == 1
            ? "Email không đúng định dạng"
            : "Invalid email format"
        );
        isValid = false;
      }

      const phone = document.getElementById("phone")?.value;
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
        const formData = new FormData(bookingForm);
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
              alert(
                languageId == 1
                  ? "Gửi yêu cầu thành công! Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất."
                  : "Request sent successfully! We will contact you as soon as possible."
              );
              bookingForm.reset();
              selectedFiles = [];
              clearImagePreviews();
              closeModal();
            } else {
              alert(
                languageId == 1
                  ? "Lỗi: " + (data.message || "Vui lòng thử lại.")
                  : "Error: " + (data.message || "Please try again.")
              );
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
      }
    });

    const submitButton = document.querySelector(".btn-submit");
    if (submitButton) {
      submitButton.addEventListener("click", function (e) {
        e.preventDefault();
        bookingForm.dispatchEvent(new Event("submit"));
      });
    }
  }

  const eventDateInput = document.getElementById("eventDate");
  const endDateInput = document.getElementById("endDate");
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

  $(document).on("click", ".event-more", function (e) {
    e.preventDefault();
    var $form = $(this).closest("form");
    if ($form.length) {
      $form.submit();
    }
  });

  // Gắn sự kiện lần đầu khi tải trang
  attachImageUploadListener();
});
