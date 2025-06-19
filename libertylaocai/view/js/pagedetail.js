// let selectedFiles = [];

// function showTab(tabName) {
//   // Kiểm tra xem tab có tồn tại không
//   const targetTab = document.getElementById(tabName);
//   if (!targetTab) {
//     console.warn(
//       `Tab ${tabName} does not exist. Falling back to 'description' tab.`
//     );
//     showTab("description"); // Chuyển về tab mặc định nếu tab không tồn tại
//     return;
//   }

//   const panes = document.querySelectorAll(".tab-pane");
//   panes.forEach((pane) => pane.classList.remove("active"));

//   const buttons = document.querySelectorAll(".tab-btn");
//   buttons.forEach((btn) => btn.classList.remove("active"));

//   targetTab.classList.add("active");
//   document
//     .querySelector(`[onclick="showTab('${tabName}')"]`)
//     ?.classList.add("active"); // Dùng optional chaining để tránh lỗi nếu nút không tồn tại

//   if (tabName === "booking") {
//     clearImagePreviews();
//   }
// }

// function attachImageUploadListener() {
//   const imageUpload = document.getElementById("imageUpload");
//   const uploadArea = document.querySelector(".upload-area");
//   if (!imageUpload || !uploadArea) {
//     console.log("Không tìm thấy imageUpload hoặc uploadArea");
//     return;
//   }

//   const uploadText = uploadArea.querySelector(".upload-text");
//   if (uploadText) {
//     uploadText.onclick = function () {
//       console.log("Clicked upload-text, triggering imageUpload.click()");
//       imageUpload.click();
//     };
//   }

//   imageUpload.addEventListener("change", function (e) {
//     console.log("Change event triggered on imageUpload");
//     const files = e.target.files;
//     if (!files.length) return;

//     console.log(
//       "Selected new files:",
//       Array.from(files).map((f) => f.name)
//     );

//     const maxTotalFiles = 5;
//     let validNewFiles = [];

//     Array.from(files).forEach((file) => {
//       const isDuplicate = selectedFiles.some(
//         (existingFile) =>
//           existingFile.name === file.name && existingFile.size === file.size
//       );

//       if (isDuplicate) {
//         alert(
//           languageId == 1
//             ? `Tệp ${file.name} đã được chọn trước đó.`
//             : `File ${file.name} has already been selected.`
//         );
//         return;
//       }

//       validNewFiles.push(file);
//     });

//     if (selectedFiles.length + validNewFiles.length > maxTotalFiles) {
//       const remainingSlots = maxTotalFiles - selectedFiles.length;
//       if (remainingSlots > 0) {
//         alert(
//           languageId == 1
//             ? `Chỉ có thể thêm ${remainingSlots} ảnh nữa. Tối đa ${maxTotalFiles} ảnh.`
//             : `Only ${remainingSlots} more images can be added. Maximum ${maxTotalFiles} images.`
//         );
//         validNewFiles = validNewFiles.slice(0, remainingSlots);
//       } else {
//         alert(
//           languageId == 1
//             ? `Đã đạt giới hạn tối đa ${maxTotalFiles} ảnh.`
//             : `Maximum limit of ${maxTotalFiles} images reached.`
//         );
//         return;
//       }
//     }

//     if (validNewFiles.length > 0) {
//       selectedFiles = selectedFiles.concat(validNewFiles);
//       console.log(
//         "Updated selectedFiles:",
//         selectedFiles.map((f) => f.name)
//       );
//       updateFileInput();
//       renderImagePreviews();
//     }

//     e.target.value = "";
//   });
// }

// function clearImagePreviews() {
//   const uploadArea = document.querySelector(".upload-area");
//   const imageUpload = document.getElementById("imageUpload");
//   if (!uploadArea || !imageUpload) return;

//   selectedFiles = [];
//   uploadArea.innerHTML = `
//         <div class="upload-icon">📷</div>
//         <div class="upload-text">
//             ${
//               languageId == 1
//                 ? "Nhấp để tải lên hình ảnh tham khảo"
//                 : "Click to upload reference images"
//             }<br>
//             <small>${
//               languageId == 1
//                 ? "Có thể tải lên nhiều hình ảnh"
//                 : "Multiple images can be uploaded"
//             }</small>
//         </div>
//     `;
//   uploadArea.appendChild(imageUpload);
//   uploadArea.value = "";
//   uploadArea.style.borderColor = "";
//   uploadArea.style.background = "";

//   attachImageUploadListener();
// }

// function updateFileInput() {
//   const imageUpload = document.getElementById("imageUpload");
//   if (!imageUpload) return;

//   const dt = new DataTransfer();
//   selectedFiles.forEach((file) => {
//     dt.items.add(file);
//   });
//   imageUpload.files = dt.files;
//   console.log(
//     "Updated imageUpload.files:",
//     imageUpload.files.length,
//     Array.from(imageUpload.files).map((f) => f.name)
//   );
// }

// function renderImagePreviews() {
//   const uploadArea = document.querySelector(".upload-area");
//   const imageUpload = document.getElementById("imageUpload");
//   if (!uploadArea || !imageUpload) return;

//   console.log(
//     "Rendering previews for selectedFiles:",
//     selectedFiles.length,
//     selectedFiles.map((f) => f.name)
//   );

//   if (selectedFiles.length === 0) {
//     clearImagePreviews();
//     return;
//   }

//   const previewContainer = document.createElement("div");
//   previewContainer.className = "images-grid";
//   previewContainer.style.marginTop = "10px";

//   selectedFiles.forEach((file, index) => {
//     const previewItem = document.createElement("div");
//     previewItem.className = "image-preview-item";

//     const img = document.createElement("img");
//     img.src = URL.createObjectURL(file);

//     const overlay = document.createElement("div");
//     overlay.className = "image-overlay";

//     const imageName = document.createElement("span");
//     imageName.className = "image-name";
//     imageName.textContent = file.name;

//     const removeBtn = document.createElement("button");
//     removeBtn.className = "remove-btn";
//     removeBtn.innerHTML = "×";
//     removeBtn.onclick = function (e) {
//       e.stopPropagation();
//       selectedFiles.splice(index, 1);
//       console.log(
//         "Removed file, new selectedFiles:",
//         selectedFiles.map((f) => f.name)
//       );
//       updateFileInput();
//       renderImagePreviews();
//     };

//     overlay.appendChild(imageName);
//     overlay.appendChild(removeBtn);
//     previewItem.appendChild(img);
//     previewItem.appendChild(overlay);
//     previewContainer.appendChild(previewItem);
//   });

//   uploadArea.innerHTML = `
//         <div class="upload-header">
//             <span class="upload-count">${
//               languageId == 1 ? "Đã chọn" : "Selected"
//             } ${selectedFiles.length} ${
//     languageId == 1 ? "hình ảnh" : "images"
//   }</span>
//             <button class="add-more-btn" type="button">${
//               languageId == 1 ? "Thêm ảnh" : "Add more"
//             }</button>
//         </div>
//     `;
//   uploadArea.appendChild(previewContainer);
//   uploadArea.appendChild(imageUpload);
//   uploadArea.style.borderColor = "#004d40";
//   uploadArea.style.background = "#f0f8f0";

//   const addMoreBtn = uploadArea.querySelector(".add-more-btn");
//   if (addMoreBtn) {
//     addMoreBtn.onclick = function () {
//       imageUpload.click();
//     };
//   }
// }

// document.addEventListener("DOMContentLoaded", function () {
//   const quickBookingForm = document.getElementById("quickBookingForm");
//   if (quickBookingForm) {
//     quickBookingForm.addEventListener("submit", function (e) {
//       e.preventDefault();
//       e.stopImmediatePropagation();

//       // Kiểm tra các trường bắt buộc bằng HTML5 validation
//       let isValid = quickBookingForm.checkValidity();
//       // Làm sạch giá trị quickBudget
//       const budgetInput = document.getElementById("quickBudget");
//       let budgetValue = budgetInput.value.replace(/[^0-9]/g, ""); // Loại bỏ tất cả ký tự không phải số
//       budgetInput.value = budgetValue || "";
//       // Kiểm tra các điều kiện khác ngoài required
//       const startDate = new Date(
//         document.getElementById("quickEventDate")?.value
//       );
//       const endDate = new Date(document.getElementById("quickEndDate")?.value);
//       const today = new Date();
//       today.setHours(0, 0, 0, 0);

//       if (startDate < today) {
//         alert(
//           languageId == 1
//             ? "Ngày bắt đầu không thể là ngày trong quá khứ"
//             : "Start date cannot be in the past"
//         );
//         isValid = false;
//       }

//       if (endDate < startDate) {
//         alert(
//           languageId == 1
//             ? "Ngày kết thúc không thể trước ngày bắt đầu"
//             : "End date cannot be before start date"
//         );
//         isValid = false;
//       }

//       const email = document.getElementById("quickEmail")?.value;
//       const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
//       if (email && !emailRegex.test(email)) {
//         alert(
//           languageId == 1
//             ? "Email không đúng định dạng"
//             : "Invalid email format"
//         );
//         isValid = false;
//       }

//       const phone = document.getElementById("quickPhone")?.value;
//       const phoneRegex = /^[0-9+\-\s\(\)]+$/;
//       if (phone && (!phoneRegex.test(phone) || phone.length < 10)) {
//         alert(
//           languageId == 1
//             ? "Số điện thoại không hợp lệ"
//             : "Invalid phone number"
//         );
//         isValid = false;
//       }

//       if (selectedFiles.length > 5) {
//         alert(
//           languageId == 1
//             ? "Chỉ được phép tải lên tối đa 5 hình ảnh"
//             : "Only a maximum of 5 images can be uploaded"
//         );
//         isValid = false;
//       }

//       if (isValid) {
//         const formData = new FormData(quickBookingForm);
//         formData.append("submit_booking", "true");
//         selectedFiles.forEach((file, index) => {
//           formData.append(`images[]`, file);
//         });

//         console.log("FormData entries:");
//         for (let [key, value] of formData.entries()) {
//           console.log(
//             key,
//             value instanceof File ? `File: ${value.name}` : value
//           );
//         }

//         fetch("/libertylaocai/user/submit", {
//           method: "POST",
//           body: formData,
//         })
//           .then((response) => {
//             console.log("Fetch response status:", response.status);
//             if (!response.ok) {
//               throw new Error(`HTTP error! Status: ${response.status}`);
//             }
//             return response.json();
//           })
//           .then((data) => {
//             console.log("Server response:", data);
//             if (data.status === "success") {
//               alert(data.message);
//               quickBookingForm.reset();
//               selectedFiles = [];
//               clearImagePreviews();
//               showTab("description");
//             } else {
//               alert(data.message);
//             }
//           })
//           .catch((error) => {
//             console.error("Fetch error:", error);
//             alert(
//               languageId == 1
//                 ? "Có lỗi khi gửi yêu cầu. Vui lòng thử lại."
//                 : "An error occurred while sending the request. Please try again."
//             );
//           });
//       } else {
//         // Hiển thị thông báo lỗi HTML5 mặc định
//         quickBookingForm.reportValidity();
//       }
//     });

//     const submitButton = quickBookingForm.querySelector(".btn-submit");
//     if (submitButton) {
//       submitButton.addEventListener("click", function (e) {
//         e.preventDefault();
//         quickBookingForm.dispatchEvent(new Event("submit"));
//       });
//     }
//   }

//   const eventDateInput = document.getElementById("quickEventDate");
//   const endDateInput = document.getElementById("quickEndDate");
//   if (eventDateInput && endDateInput) {
//     eventDateInput.min = new Date().toISOString().split("T")[0];
//     endDateInput.min = new Date().toISOString().split("T")[0];

//     eventDateInput.addEventListener("change", function () {
//       endDateInput.min = this.value;
//       if (endDateInput.value < this.value) {
//         endDateInput.value = this.value;
//       }
//     });
//   }

//   const slider = document.querySelector(".gallery-slider");
//   const items = document.querySelectorAll(".gallery-item");
//   const prevBtn = document.querySelector(".prev-btn");
//   const nextBtn = document.querySelector(".next-btn");
//   const dots = document.querySelectorAll(".dot");
//   let currentIndex = 0;

//   function updateSlider() {
//     slider.style.transform = `translateX(-${currentIndex * 100}%)`;
//     dots.forEach((dot, index) => {
//       dot.classList.toggle("active", index === currentIndex);
//     });
//   }

//   prevBtn.addEventListener("click", () => {
//     currentIndex = (currentIndex - 1 + items.length) % items.length;
//     updateSlider();
//   });

//   nextBtn.addEventListener("click", () => {
//     currentIndex = (currentIndex + 1) % items.length;
//     updateSlider();
//   });

//   dots.forEach((dot, index) => {
//     dot.addEventListener("click", () => {
//       currentIndex = index;
//       updateSlider();
//     });
//   });

//   updateSlider();

//   const specSliders = document.querySelectorAll(".spec-img");
//   specSliders.forEach((sliderContainer) => {
//     const slider = sliderContainer.querySelector(".spec-img-slider");
//     const items = sliderContainer.querySelectorAll(".spec-img-item");
//     const dots = sliderContainer.querySelectorAll(".spec-dot");
//     const prevBtn = sliderContainer.querySelector(".spec-prev-btn");
//     const nextBtn = sliderContainer.querySelector(".spec-next-btn");
//     let currentIndex = 0;

//     function updateSlider() {
//       slider.style.transform = `translateX(-${currentIndex * 100}%)`;
//       dots.forEach((dot, index) => {
//         dot.classList.toggle("active", index === currentIndex);
//       });
//     }

//     prevBtn.addEventListener("click", () => {
//       currentIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
//       updateSlider();
//     });

//     nextBtn.addEventListener("click", () => {
//       currentIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
//       updateSlider();
//     });

//     dots.forEach((dot) => {
//       dot.addEventListener("click", () => {
//         currentIndex = parseInt(dot.getAttribute("data-index"));
//         updateSlider();
//       });
//     });

//     updateSlider();
//   });

//   attachImageUploadListener();

//   // Menu Slider
//   const menuSliders = document.querySelectorAll(".menu-slider-container");
//   menuSliders.forEach((sliderContainer) => {
//     const slider = sliderContainer.querySelector(".menu-slider");
//     const items = sliderContainer.querySelectorAll(".menu-slide");
//     const prevBtn = sliderContainer.querySelector(".menu-prev-btn");
//     const nextBtn = sliderContainer.querySelector(".menu-next-btn");
//     const dots = sliderContainer.querySelectorAll(".menu-dot");
//     let currentIndex = 0;

//     function updateMenuSlider() {
//       slider.style.transform = `translateX(-${currentIndex * 100}%)`;
//       dots.forEach((dot, index) => {
//         dot.classList.toggle("active", index === currentIndex);
//       });
//     }

//     if (prevBtn) {
//       prevBtn.addEventListener("click", () => {
//         currentIndex = (currentIndex - 1 + items.length) % items.length;
//         updateMenuSlider();
//       });
//     }

//     if (nextBtn) {
//       nextBtn.addEventListener("click", () => {
//         currentIndex = (currentIndex + 1) % items.length;
//         updateMenuSlider();
//       });
//     }

//     dots.forEach((dot, index) => {
//       dot.addEventListener("click", () => {
//         currentIndex = index;
//         updateMenuSlider();
//       });
//     });

//     updateMenuSlider();
//   });
// });

// document.getElementById("quickBudget").addEventListener("input", function (e) {
//   let value = e.target.value.replace(/[^0-9]/g, ""); // Loại bỏ ký tự không phải số
//   if (value === "") {
//     e.target.value = "";
//     return;
//   }
//   // Định dạng VNĐ với dấu phẩy phân cách hàng nghìn
//   e.target.value = Number(value).toLocaleString("vi-VN") + " đ";
// });

// function openImageModal(src) {
//   const modal = document.getElementById("imageModal");
//   const modalImg = document.getElementById("modalImage");
//   modal.style.display = "block";
//   modalImg.src = src;
//   modalImg.style.transform = "scale(1) translate(0, 0)"; // Reset scale và vị trí
//   modalImg.style.transition = "transform 0.2s ease"; // Hiệu ứng mượt mà
//   modalImg.dataset.scale = 1; // Lưu tỷ lệ hiện tại
//   modalImg.dataset.translateX = 0; // Lưu vị trí X
//   modalImg.dataset.translateY = 0; // Lưu vị trí Y
// }

// function closeImageModal() {
//   const modal = document.getElementById("imageModal");
//   modal.style.display = "none";
// }

// const modalImg = document.getElementById("modalImage");
// let scale = 1;
// let isDragging = false;
// let startX,
//   startY,
//   translateX = 0,
//   translateY = 0;

// // // Xử lý nút zoom
// // document.getElementById("zoomInBtn").addEventListener("click", () => {
// //   scale += 0.1; // Tăng tỷ lệ
// //   scale = Math.min(Math.max(0.5, scale), 3); // Giới hạn zoom từ 0.5x đến 3x
// //   modalImg.dataset.scale = scale;
// //   updateTransformWithBounds();
// // });

// // document.getElementById("zoomOutBtn").addEventListener("click", () => {
// //   scale -= 0.1; // Giảm tỷ lệ
// //   scale = Math.min(Math.max(0.5, scale), 3); // Giới hạn zoom từ 0.5x đến 3x
// //   modalImg.dataset.scale = scale;
// //   updateTransformWithBounds();
// // });

// // // Kéo ảnh bằng chuột
// // modalImg.addEventListener("mousedown", (event) => {
// //   if (scale <= 1) return; // Chỉ cho phép kéo khi ảnh được phóng to
// //   event.preventDefault();
// //   isDragging = true;
// //   startX = event.clientX - translateX;
// //   startY = event.clientY - translateY;
// //   modalImg.style.cursor = "grabbing";
// // });

// // modalImg.addEventListener("mousemove", (event) => {
// //   if (!isDragging) return;
// //   event.preventDefault();
// //   translateX = event.clientX - startX;
// //   translateY = event.clientY - startY;
// //   updateTransformWithBounds();
// // });

// // modalImg.addEventListener("mouseup", () => {
// //   isDragging = false;
// //   modalImg.style.cursor = "grab";
// // });

// // modalImg.addEventListener("mouseleave", () => {
// //   isDragging = false;
// //   modalImg.style.cursor = "grab";
// // });

// // // Kéo ảnh bằng cảm ứng
// // modalImg.addEventListener("touchstart", (event) => {
// //   if (scale <= 1) return; // Chỉ cho phép kéo khi ảnh được phóng to
// //   if (event.touches.length === 1) {
// //     isDragging = true;
// //     startX = event.touches[0].clientX - translateX;
// //     startY = event.touches[0].clientY - translateY;
// //     event.preventDefault();
// //   }
// // });

// // modalImg.addEventListener("touchmove", (event) => {
// //   if (!isDragging || event.touches.length !== 1) return;
// //   event.preventDefault();
// //   translateX = event.touches[0].clientX - startX;
// //   translateY = event.touches[0].clientY - startY;
// //   updateTransformWithBounds();
// // });

// // modalImg.addEventListener("touchend", () => {
// //   isDragging = false;
// // });

// // function updateTransformWithBounds() {
// //   const modalBody = document.querySelector(".modal-body");
// //   const imgRect = modalImg.getBoundingClientRect();
// //   const modalRect = modalBody.getBoundingClientRect();

// //   const scaledWidth = imgRect.width * scale;
// //   const scaledHeight = imgRect.height * scale;

// //   // Giới hạn kéo ảnh không vượt ra ngoài modal
// //   const maxX = (scaledWidth - modalRect.width) / (2 * scale);
// //   const maxY = (scaledHeight - modalRect.height) / (2 * scale);

// //   translateX = Math.max(-maxX, Math.min(maxX, translateX));
// //   translateY = Math.max(-maxY, Math.min(maxY, translateY));

// //   modalImg.style.transform = `scale(${scale}) translate(${translateX}px, ${translateY}px)`;
// //   modalImg.dataset.translateX = translateX;
// //   modalImg.dataset.translateY = translateY;
// // }

// // Đóng modal khi nhấp ra ngoài
// document.addEventListener("click", function (event) {
//   const modal = document.getElementById("imageModal");
//   const modalContent = document.querySelector(".modal-content");
//   if (event.target === modal) {
//     closeImageModal();
//   }
// });

// // Đóng modal bằng phím Esc
// document.addEventListener("keydown", function (event) {
//   if (event.key === "Escape") {
//     closeImageModal();
//   }
// });

let selectedFiles = [];

function showTab(tabName) {
  // Kiểm tra xem tab có tồn tại không
  const targetTab = document.getElementById(tabName);
  if (!targetTab) {
    console.warn(
      `Tab ${tabName} does not exist. Falling back to 'description' tab.`
    );
    showTab("description"); // Chuyển về tab mặc định nếu tab không tồn tại
    return;
  }

  const panes = document.querySelectorAll(".tab-pane");
  panes.forEach((pane) => pane.classList.remove("active"));

  const buttons = document.querySelectorAll(".tab-btn");
  buttons.forEach((btn) => btn.classList.remove("active"));

  targetTab.classList.add("active");
  document
    .querySelector(`[onclick="showTab('${tabName}')"]`)
    ?.classList.add("active"); // Dùng optional chaining để tránh lỗi nếu nút không tồn tại

  if (tabName === "booking") {
    clearImagePreviews();
    // Cuộn đến đầu tab booking
    targetTab.scrollIntoView({ behavior: "smooth", block: "start" });
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

      // Hiển thị overlay loading toàn màn hình
      const fullScreenLoader = document.getElementById("fullScreenLoader");
      fullScreenLoader.style.display = "flex";

      // Kiểm tra các trường bắt buộc bằng HTML5 validation
      let isValid = quickBookingForm.checkValidity();
      // Làm sạch giá trị quickBudget
      const budgetInput = document.getElementById("quickBudget");
      let budgetValue = budgetInput.value.replace(/[^0-9]/g, ""); // Loại bỏ tất cả ký tự không phải số
      budgetInput.value = budgetValue || "";
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
            // Ẩn overlay loading
            fullScreenLoader.style.display = "none";
            console.log("Server response:", data);
            if (data.status === "success") {
              // Tạo thông báo động
              const notification = document.createElement("div");
              notification.className = "success-notification";
              notification.innerHTML = `
                <div class="notification-content">
                  <i class="fas fa-check-circle"></i>
                  <div>
                    <h3>${languageId == 1 ? "Thành công!" : "Success!"}</h3>
                    <p>${
                      languageId == 1
                        ? "Yêu cầu đặt lịch sự kiện đã được gửi thành công."
                        : "Your event booking request has been sent successfully."
                    }</p>
                  </div>
                </div>
              `;
              document.body.appendChild(notification);

              // Tự động ẩn thông báo sau 3 giây
              setTimeout(() => {
                notification.style.animation = "slideOutRight 0.3s ease-in";
                setTimeout(() => notification.remove(), 300);
              }, 3000);

              quickBookingForm.reset();
              selectedFiles = [];
              clearImagePreviews();
              showTab("description");
            } else {
              alert(
                languageId == 1
                  ? "Lỗi: " + (data.message || "Vui lòng thử lại.")
                  : "Error: " + (data.message || "Please try again.")
              );
            }
          })
          .catch((error) => {
            // Ẩn overlay loading
            fullScreenLoader.style.display = "none";
            console.error("Fetch error:", error);
            alert(
              languageId == 1
                ? "Có lỗi khi gửi yêu cầu. Vui lòng thử lại."
                : "An error occurred while sending the request. Please try again."
            );
          });
      } else {
        // Ẩn overlay loading nếu form không hợp lệ
        fullScreenLoader.style.display = "none";
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

  // Menu Slider
  const menuSliders = document.querySelectorAll(".menu-slider-container");
  menuSliders.forEach((sliderContainer) => {
    const slider = sliderContainer.querySelector(".menu-slider");
    const items = sliderContainer.querySelectorAll(".menu-slide");
    const prevBtn = sliderContainer.querySelector(".menu-prev-btn");
    const nextBtn = sliderContainer.querySelector(".menu-next-btn");
    const dots = sliderContainer.querySelectorAll(".menu-dot");
    let currentIndex = 0;

    function updateMenuSlider() {
      slider.style.transform = `translateX(-${currentIndex * 100}%)`;
      dots.forEach((dot, index) => {
        dot.classList.toggle("active", index === currentIndex);
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener("click", () => {
        currentIndex = (currentIndex - 1 + items.length) % items.length;
        updateMenuSlider();
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener("click", () => {
        currentIndex = (currentIndex + 1) % items.length;
        updateMenuSlider();
      });
    }

    dots.forEach((dot, index) => {
      dot.addEventListener("click", () => {
        currentIndex = index;
        updateMenuSlider();
      });
    });

    updateMenuSlider();
  });

  // Thêm sự kiện cho nút "Đặt Lịch Nhanh"
  const quickBookingBtn = document.querySelector(".quick-booking-btn");
  if (quickBookingBtn) {
    quickBookingBtn.addEventListener("click", function () {
      showTab("booking");
      // Cuộn đến đầu tab booking
      const bookingTab = document.getElementById("booking");
      if (bookingTab) {
        bookingTab.scrollIntoView({ behavior: "smooth", block: "start" });
      }
    });
  }

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

function openImageModal(src) {
  const modal = document.getElementById("imageModal");
  const modalImg = document.getElementById("modalImage");
  modal.style.display = "block";
  modalImg.src = src;
  modalImg.style.transform = "scale(1) translate(0, 0)"; // Reset scale và vị trí
  modalImg.style.transition = "transform 0.2s ease"; // Hiệu ứng mượt mà
  modalImg.dataset.scale = 1; // Lưu tỷ lệ hiện tại
  modalImg.dataset.translateX = 0; // Lưu vị trí X
  modalImg.dataset.translateY = 0; // Lưu vị trí Y
}

function closeImageModal() {
  const modal = document.getElementById("imageModal");
  modal.style.display = "none";
}

const modalImg = document.getElementById("modalImage");
let scale = 1;
let isDragging = false;
let startX,
  startY,
  translateX = 0,
  translateY = 0;

// Đóng modal khi nhấp ra ngoài
document.addEventListener("click", function (event) {
  const modal = document.getElementById("imageModal");
  const modalContent = document.querySelector(".modal-content");
  if (event.target === modal) {
    closeImageModal();
  }
});

// Đóng modal bằng phím Esc
document.addEventListener("keydown", function (event) {
  if (event.key === "Escape") {
    closeImageModal();
  }
});
