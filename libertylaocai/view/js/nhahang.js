let currentSlide = 0;
const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");
let currentPage = 1;
let currentLimit = 5;
let totalPages = 1;

let currentMenuPage = 1;
let currentMenuLimit = 9;
// let totalMenuPages = 1;

// function fetchMenu(page = 1, limit = 9) {
//   fetch("/libertylaocai/api/menu.php", {
//     method: "POST",
//     headers: {
//       "Content-Type": "application/x-www-form-urlencoded",
//     },
//     body: `page=${page}&limit=${limit}`,
//   })
//     .then((response) => response.json())
//     .then((data) => {
//       if (data.status === "success") {
//         updateMenu(data.data);
//         currentMenuPage = data.data.currentPage;
//         totalMenuPages = data.data.totalPages;
//         currentMenuLimit = limit;
//         updateMenuPaginationControls();
//       }
//     })
//     .catch((error) => {
//       console.error("Error fetching menu:", error);
//     });
// }
function fetchMenu(page = 1, limit = 9) {
  fetch("/libertylaocai/api/menu.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `page=${page}&limit=${limit}`,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`Lỗi HTTP! Trạng thái: ${response.status}`);
      }
      return response.text();
    })
    .then((text) => {
      console.log("Phản hồi gốc từ menu.php:", text);
      try {
        const data = JSON.parse(text);
        if (data.status === "success") {
          updateMenu(data.data);
          currentMenuPage = data.data.currentPage;
          totalMenuPages = data.data.totalPages;
          updateMenuPaginationControls();
        } else {
          console.error("Lỗi từ server:", data.message);
          alert(
            languageId == 1 ? `Lỗi: ${data.message}` : `Error: ${data.message}`
          );
        }
      } catch (e) {
        console.error("Lỗi phân tích JSON:", e);
        alert(
          languageId == 1
            ? "Lỗi định dạng dữ liệu từ server."
            : "Invalid data format from server."
        );
      }
    })
    .catch((error) => {
      console.error("Lỗi khi lấy thực đơn:", error);
      alert(
        languageId == 1
          ? "Lỗi khi tải thực đơn. Vui lòng thử lại."
          : "Error loading menu. Please try again."
      );
    });
}

// function updateMenu(data) {
//   const menuList = document.getElementById("menu-list");
//   menuList.innerHTML = "";
//   data.menuImages.forEach((dish) => {
//     const dishDiv = document.createElement("div");
//     dishDiv.className = "service-featured-detail";
//     dishDiv.innerHTML = `
//             <div class="featured-img">
//                 <img src="/libertylaocai/view/img/${dish.image}" alt="${
//       dish.title
//     }">
//             </div>
//             <div class="featured-content">
//                 <div class="featured-title">${dish.title}</div>
//                 <div class="featured-description">${
//                   dish.description || ""
//                 }</div>
//                 <div class="price-container">
//                     <div class="price">${Number(dish.price).toLocaleString(
//                       "vi-VN"
//                     )} VNĐ</div>
//                 </div>
//             </div>
//         `;
//     menuList.appendChild(dishDiv);
//   });
// }
function updateMenu(data) {
  const menuList = document.getElementById("menu-list");
  if (!menuList) {
    console.error(
      "Không tìm thấy phần tử với id='menu-list'. Vui lòng kiểm tra HTML."
    );
    alert(
      languageId == 1
        ? "Lỗi: Không tìm thấy danh sách thực đơn."
        : "Error: Menu list not found."
    );
    return;
  }
  menuList.innerHTML = "";
  if (data.menuImages && data.menuImages.length > 0) {
    data.menuImages.forEach((dish) => {
      const dishDiv = document.createElement("div");
      dishDiv.className = "service-featured-detail";
      dishDiv.innerHTML = `
                <div class="featured-img">
                    <img src="/libertylaocai/view/img/${dish.image}" alt="${
        dish.title
      }">
                </div>
                <div class="featured-content">
                    <div class="featured-title">${dish.title}</div>
                    <div class="featured-description">${
                      dish.description || ""
                    }</div>
                    <div class="price-container">
                        <div class="price">${Number(dish.price).toLocaleString(
                          "vi-VN"
                        )} VNĐ</div>
                    </div>
                </div>
            `;
      menuList.appendChild(dishDiv);
    });
  } else {
    menuList.innerHTML = `<p>${
      languageId == 1
        ? "Không có món ăn nào để hiển thị."
        : "No dishes available to display."
    }</p>`;
  }
}

// function updateMenuPaginationControls() {
//   const paginationControls = document.getElementById("menu-pagination");
//   const showMoreBtn = document.querySelector(".show-more-menu");
//   const paginationButtons = document.getElementById("menu-pagination-buttons");

//   if (totalMenuPages <= 1 && currentMenuLimit >= 10) {
//     paginationControls.style.display = "none";
//     return;
//   }

//   paginationControls.style.display = "block";

//   if (currentMenuLimit < 10) {
//     showMoreBtn.style.display = "block";
//     paginationButtons.style.display = "none";
//   } else {
//     showMoreBtn.style.display = "none";
//     paginationButtons.style.display = "flex";

//     paginationButtons.innerHTML = "";
//     const maxButtons = 5;
//     let startPage = Math.max(
//       1,
//       currentMenuPage - Math.floor((maxButtons - 1) / 2)
//     );
//     let endPage = Math.min(totalMenuPages, startPage + maxButtons - 1);

//     if (endPage - startPage + 1 > maxButtons) {
//       endPage = startPage + maxButtons - 1;
//     }

//     if (startPage > 1) {
//       const firstPageBtn = document.createElement("button");
//       firstPageBtn.className = "pagination-btn";
//       firstPageBtn.textContent = "1";
//       firstPageBtn.addEventListener("click", () =>
//         fetchMenu(1, currentMenuLimit)
//       );
//       paginationButtons.appendChild(firstPageBtn);

//       if (startPage > 2) {
//         const ellipsis = document.createElement("span");
//         ellipsis.className = "pagination-ellipsis";
//         ellipsis.textContent = "...";
//         paginationButtons.appendChild(ellipsis);
//       }
//       startPage++;
//       endPage = Math.min(endPage, startPage + maxButtons - 2);
//     }

//     for (let i = startPage; i <= endPage; i++) {
//       const pageBtn = document.createElement("button");
//       pageBtn.className = `pagination-btn ${
//         i === currentMenuPage ? "active" : ""
//       }`;
//       pageBtn.textContent = i;
//       pageBtn.addEventListener("click", () => fetchMenu(i, currentMenuLimit));
//       paginationButtons.appendChild(pageBtn);
//     }

//     if (endPage < totalMenuPages) {
//       if (endPage < totalMenuPages - 1) {
//         const ellipsis = document.createElement("span");
//         ellipsis.className = "pagination-ellipsis";
//         ellipsis.textContent = "...";
//         paginationButtons.appendChild(ellipsis);
//       }
//       const lastPageBtn = document.createElement("button");
//       lastPageBtn.className = "pagination-btn";
//       lastPageBtn.textContent = totalMenuPages;
//       lastPageBtn.addEventListener("click", () =>
//         fetchMenu(totalMenuPages, currentMenuLimit)
//       );
//       paginationButtons.appendChild(lastPageBtn);
//     }

//     const buttonCount = paginationButtons.children.length;
//     if (buttonCount > maxButtons) {
//       while (paginationButtons.children.length > maxButtons) {
//         paginationButtons.removeChild(paginationButtons.lastChild);
//       }
//     }
//   }

//   showMoreBtn.removeEventListener("click", handleShowMoreMenu);
//   showMoreBtn.addEventListener("click", handleShowMoreMenu);
// }
function updateMenuPaginationControls() {
  const paginationControls = document.getElementById("menu-pagination");
  const paginationButtons = document.getElementById("menu-pagination-buttons");

  if (totalMenuPages <= 1) {
    paginationControls.style.display = "none";
    return;
  }

  paginationControls.style.display = "block";
  paginationButtons.style.display = "flex";
  paginationButtons.innerHTML = "";

  const maxButtons = 5;
  let startPage = Math.max(
    1,
    currentMenuPage - Math.floor((maxButtons - 1) / 2)
  );
  let endPage = Math.min(totalMenuPages, startPage + maxButtons - 1);

  if (startPage > 1) {
    const firstPageBtn = document.createElement("button");
    firstPageBtn.className = "pagination-btn";
    firstPageBtn.textContent = "1";
    firstPageBtn.addEventListener("click", () =>
      fetchMenu(1, currentMenuLimit)
    );
    paginationButtons.appendChild(firstPageBtn);

    if (startPage > 2) {
      const ellipsis = document.createElement("span");
      ellipsis.className = "pagination-ellipsis";
      ellipsis.textContent = "...";
      paginationButtons.appendChild(ellipsis);
    }
  }

  for (let i = startPage; i <= endPage; i++) {
    const pageBtn = document.createElement("button");
    pageBtn.className = `pagination-btn ${
      i === currentMenuPage ? "active" : ""
    }`;
    pageBtn.textContent = i;
    pageBtn.addEventListener("click", () => fetchMenu(i, currentMenuLimit));
    paginationButtons.appendChild(pageBtn);
  }

  if (endPage < totalMenuPages) {
    if (endPage < totalMenuPages - 1) {
      const ellipsis = document.createElement("span");
      ellipsis.className = "pagination-ellipsis";
      ellipsis.textContent = "...";
      paginationButtons.appendChild(ellipsis);
    }
    const lastPageBtn = document.createElement("button");
    lastPageBtn.className = "pagination-btn";
    lastPageBtn.textContent = totalMenuPages;
    lastPageBtn.addEventListener("click", () =>
      fetchMenu(totalMenuPages, currentMenuLimit)
    );
    paginationButtons.appendChild(lastPageBtn);
  }
}

function handleShowMoreMenu() {
  if (currentMenuLimit < 10) {
    currentMenuLimit = 10;
    fetchMenu(1, 10);
  }
}

function showSlide(index) {
  slides.forEach((slide) => slide.classList.remove("active"));
  dots.forEach((dot) => dot.classList.remove("active"));
  slides[index].classList.add("active");
  dots[index].classList.add("active");
  currentSlide = index;
}

function nextSlide() {
  currentSlide = (currentSlide + 1) % slides.length;
  showSlide(currentSlide);
}

function prevSlide() {
  currentSlide = (currentSlide - 1 + slides.length) % slides.length;
  showSlide(currentSlide);
}

document.querySelector(".next-btn").addEventListener("click", nextSlide);
document.querySelector(".prev-btn").addEventListener("click", prevSlide);
dots.forEach((dot, index) => {
  dot.addEventListener("click", () => showSlide(index));
});

let autoSlideInterval = setInterval(nextSlide, 5000);
const sliderContainer = document.querySelector(".slider-container");
sliderContainer.addEventListener("mouseenter", () =>
  clearInterval(autoSlideInterval)
);
sliderContainer.addEventListener(
  "mouseleave",
  () => (autoSlideInterval = setInterval(nextSlide, 5000))
);

function switchTab(tabName) {
  document
    .querySelectorAll(".tab-btn")
    .forEach((btn) => btn.classList.remove("active"));
  document
    .querySelectorAll(".tab-content")
    .forEach((content) => content.classList.remove("active"));
  document.querySelector(`[data-tab="${tabName}"]`).classList.add("active");
  document.getElementById(tabName).classList.add("active");
  // Gọi fetchMenu khi chuyển sang tab Menu
  if (tabName === "menu") {
    fetchMenu(currentMenuPage, currentMenuLimit);
  }
}

document.querySelectorAll(".tab-btn").forEach((btn) => {
  btn.addEventListener("click", (e) => {
    const tabName = e.target.getAttribute("data-tab");
    switchTab(tabName);
  });
});

function setMinDate() {
  const today = new Date().toISOString().split("T")[0];
  const eventDateInput = document.getElementById("event-date");
  if (eventDateInput) {
    eventDateInput.setAttribute("min", today);
    eventDateInput.value = today;
    // updateBookingSummary();
  }
}

document.addEventListener("DOMContentLoaded", function () {
  showSlide(0);
  setMinDate();
  fetchReviews(1, 5);

  // // Gắn sự kiện cho các nút phân trang menu
  // const menuPaginationButtons = document.querySelectorAll(
  //   "#menu-pagination-buttons .pagination-btn"
  // );
  // menuPaginationButtons.forEach((btn) => {
  //   btn.addEventListener("click", () => {
  //     const page = parseInt(btn.getAttribute("data-page"));
  //     fetchMenu(page, currentMenuLimit);
  //   });
  // });
  // Gắn sự kiện cho các nút phân trang menu
  const menuPaginationButtons = document.querySelectorAll(
    "#menu-pagination-buttons .pagination-btn"
  );
  menuPaginationButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const page = parseInt(btn.getAttribute("data-page"));
      switchTab("menu"); // Chuyển sang tab Menu trước khi gọi fetchMenu
      fetchMenu(page, currentMenuLimit);
    });
  });

  // // Gắn sự kiện cho nút "Xem thêm món ăn"
  // const showMoreMenuBtn = document.querySelector(".show-more-menu");
  // if (showMoreMenuBtn) {
  //   showMoreMenuBtn.addEventListener("click", handleShowMoreMenu);
  // }

  // const phoneInput = document.getElementById("phone");
  // if (phoneInput) {
  //   phoneInput.addEventListener("input", function (e) {
  //     let value = e.target.value.replace(/\D/g, "");
  //     if (value.startsWith("84")) {
  //       value =
  //         "+84 " +
  //         value.slice(2, 5) +
  //         " " +
  //         value.slice(5, 8) +
  //         " " +
  //         value.slice(8);
  //     } else if (value.startsWith("0")) {
  //       value =
  //         value.slice(0, 4) + " " + value.slice(4, 7) + " " + value.slice(7);
  //     }
  //     e.target.value = value.trim();
  //   });
  // }
  const phoneInput = document.getElementById("phone");
  if (phoneInput) {
    phoneInput.addEventListener("input", function (e) {
      let value = e.target.value.replace(/\D/g, "");
      if (value.startsWith("84")) {
        value =
          "+84 " +
          value.slice(2, 5) +
          " " +
          value.slice(5, 8) +
          " " +
          value.slice(8);
      } else if (value.startsWith("0")) {
        value =
          value.slice(0, 4) + " " + value.slice(4, 7) + " " + value.slice(7);
      }
      e.target.value = value.trim();
    });
  }

  const bookingForm = document.getElementById("bookingForm");
  if (bookingForm) {
    bookingForm.addEventListener("submit", function (e) {
      e.preventDefault();

      // Xóa thông báo lỗi trước đó
      document.querySelectorAll(".error-message").forEach((span) => {
        span.style.display = "none";
        span.textContent = "";
      });
      document
        .querySelectorAll(".form-group input, .form-group select")
        .forEach((input) => {
          input.classList.remove("error");
        });

      const fieldNames = {
        customerName: languageId == 1 ? "Họ và tên" : "Full Name",
        phoneNumber: languageId == 1 ? "Số điện thoại" : "Phone Number",
        email: languageId == 1 ? "Email" : "Email",
        bookingDate: languageId == 1 ? "Ngày đặt bàn" : "Booking Date",
        startTime: languageId == 1 ? "Giờ đặt bàn" : "Booking Time",
        guestCount: languageId == 1 ? "Số lượng khách" : "Number of Guests",
        diningArea: languageId == 1 ? "Khu vực đặt bàn" : "Dining Area",
      };

      const requiredFields = [
        "customerName",
        "phoneNumber",
        "email",
        "bookingDate",
        "startTime",
        "guestCount",
        "diningArea",
      ];
      let isValid = true;

      requiredFields.forEach((field) => {
        const input = document.getElementById(field);
        const errorSpan = document.getElementById(`${field}-error`);
        if (!input || !input.value.trim()) {
          if (input) input.classList.add("error");
          if (errorSpan) {
            errorSpan.textContent =
              languageId == 1
                ? `Vui lòng nhập ${fieldNames[field]}`
                : `Please enter ${fieldNames[field]}`;
            errorSpan.style.display = "block";
            errorSpan.style.color = "red";
            errorSpan.style.fontSize = "14px";
          }
          isValid = false;
        }
      });

      const bookingDate = new Date(
        document.getElementById("bookingDate").value
      );
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      if (bookingDate < today) {
        const errorSpan = document.getElementById("bookingDate-error");
        if (errorSpan) {
          errorSpan.textContent =
            languageId == 1
              ? "Ngày đặt bàn không thể là ngày trong quá khứ"
              : "Booking date cannot be in the past";
          errorSpan.style.display = "block";
          errorSpan.style.color = "red";
          errorSpan.style.fontSize = "14px";
        }
        document.getElementById("bookingDate").classList.add("error");
        isValid = false;
      }

      const email = document.getElementById("email")?.value;
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (email && !emailRegex.test(email)) {
        const errorSpan = document.getElementById("email-error");
        if (errorSpan) {
          errorSpan.textContent =
            languageId == 1
              ? "Email không đúng định dạng"
              : "Invalid email format";
          errorSpan.style.display = "block";
          errorSpan.style.color = "red";
          errorSpan.style.fontSize = "14px";
        }
        document.getElementById("email").classList.add("error");
        isValid = false;
      }

      const phoneNumber = document.getElementById("phoneNumber")?.value;
      const phoneRegex = /^[0-9+\-\s\(\)]+$/;
      if (
        phoneNumber &&
        (!phoneRegex.test(phoneNumber) || phoneNumber.length < 10)
      ) {
        const errorSpan = document.getElementById("phoneNumber-error");
        if (errorSpan) {
          errorSpan.textContent =
            languageId == 1
              ? "Số điện thoại không hợp lệ"
              : "Invalid phone number";
          errorSpan.style.display = "block";
          errorSpan.style.color = "red";
          errorSpan.style.fontSize = "14px";
        }
        document.getElementById("phoneNumber").classList.add("error");
        isValid = false;
      }

      if (isValid) {
        const submitBtn = document.querySelector(".submit-booking-btn");
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML =
          '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        submitBtn.disabled = true;

        const formData = new FormData(bookingForm);
        formData.append("submit_booking_restaurant", "true");

        fetch("/libertylaocai/user/submit", {
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
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            if (data.status === "success") {
              alert(data.message);
              bookingForm.reset();
              switchTab("description");
            } else {
              alert(data.message);
            }
          })
          .catch((error) => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            console.error("Error:", error);
            alert(
              languageId == 1
                ? "Có lỗi khi gửi yêu cầu. Vui lòng thử lại."
                : "An error occurred while sending the request. Please try again."
            );
          });
      }
    });
  }

  const starInputs = document.querySelectorAll(".star-rating input");
  const ratingText = document.querySelector(".rating-text");
  starInputs.forEach((input) => {
    input.addEventListener("change", function () {
      const rating = this.value;
      const ratingTexts = {
        1: "Rất tệ",
        2: "Tệ",
        3: "Bình thường",
        4: "Tốt",
        5: "Xuất sắc",
      };
      ratingText.textContent = `${rating} sao - ${ratingTexts[rating]}`;
      ratingText.style.color = "#007bff";
      ratingText.style.fontWeight = "500";
    });
  });

  const stars = document.querySelectorAll(".star-rating .star");
  stars.forEach((star, index) => {
    star.addEventListener("mouseenter", function () {
      for (let i = stars.length - 1; i >= stars.length - 1 - index; i--) {
        stars[i].style.color = "#ffc107";
        stars[i].style.transform = "scale(1.1)";
      }
    });
    star.addEventListener("mouseleave", function () {
      const checkedInput = document.querySelector(".star-rating input:checked");
      if (!checkedInput) {
        stars.forEach((s) => {
          s.style.color = "#dee2e6";
          s.style.transform = "scale(1)";
        });
      } else {
        const selectedValue = parseInt(checkedInput.value);
        stars.forEach((s, i) => {
          if (stars.length - i <= selectedValue) {
            s.style.color = "#ffc107";
            s.style.transform = "scale(1.1)";
          } else {
            s.style.color = "#dee2e6";
            s.style.transform = "scale(1)";
          }
        });
      }
    });
  });

  let touchStartX = 0;
  let touchEndX = 0;
  sliderContainer.addEventListener("touchstart", function (e) {
    touchStartX = e.changedTouches[0].screenX;
  });
  sliderContainer.addEventListener("touchend", function (e) {
    touchEndX = e.changedTouches[0].screenX;
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;
    if (Math.abs(diff) > swipeThreshold) {
      if (diff > 0) {
        nextSlide();
      } else {
        prevSlide();
      }
    }
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "ArrowLeft") {
      prevSlide();
    } else if (e.key === "ArrowRight") {
      nextSlide();
    }
  });

  const elements = document.querySelectorAll(
    ".amenity-item, .info-item, .review-item"
  );
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = "1";
          entry.target.style.transform = "translateY(0)";
        }
      });
    },
    { threshold: 0.1 }
  );
  elements.forEach((element) => {
    element.style.opacity = "0";
    element.style.transform = "translateY(20px)";
    element.style.transition = "all 0.6s ease";
    observer.observe(element);
  });
});

function toggleReviewForm() {
  const form = document.getElementById("reviewForm");
  const btn = document.querySelector(".write-review-btn");

  if (form.style.display === "none" || form.style.display === "") {
    form.style.display = "block";
    btn.innerHTML = '<i class="fas fa-times"></i> Hủy viết đánh giá';
    btn.style.background = "linear-gradient(135deg, #dc3545, #c82333)";
  } else {
    form.style.display = "none";
    btn.innerHTML = '<i class="fas fa-pen"></i> Viết đánh giá của bạn';
    btn.style.background = "linear-gradient(135deg, #28a745, #20c997)";
    resetReviewForm();
  }
}

function resetReviewForm() {
  const form = document.querySelector(".review-form");
  form.reset();
  const ratingText = document.querySelector(".rating-text");
  ratingText.textContent = "Chọn số sao";
  ratingText.style.color = "#666";
  const stars = document.querySelectorAll(".star-rating .star");
  stars.forEach((star) => {
    star.style.color = "#dee2e6";
    star.style.transform = "scale(1)";
  });
}

function fetchReviews(page = 1, limit = 5) {
  fetch(`/libertylaocai/api/restaurant_review.php?page=${page}&limit=${limit}`)
    .then((response) => response.json())
    .then((data) => {
      updateReviews(data);
      currentPage = data.currentPage;
      totalPages = data.totalPages;
      currentLimit = limit;
      updatePaginationControls();
    })
    .catch((error) => {
      console.error("Error fetching reviews:", error);
    });
}

function updateReviews(data) {
  const reviewsList = document.querySelector(".reviews-list");
  const ratingScore = document.querySelector(".rating-score");
  const ratingCount = document.querySelector(".rating-count");
  const ratingStars = document.querySelector(".rating-stars");
  const ratingBreakdown = document.querySelector(".rating-breakdown");

  reviewsList.innerHTML = "";
  data.reviews.forEach((review) => {
    const reviewDiv = document.createElement("div");
    reviewDiv.className = "review-item";
    let starsHTML = "";
    for (let i = 1; i <= 5; i++) {
      starsHTML +=
        i <= review.rate
          ? '<i class="fas fa-star"></i>'
          : '<i class="far fa-star"></i>';
    }
    const reviewDate = new Date(review.create_at).toLocaleDateString("vi-VN");
    reviewDiv.innerHTML = `
            <div class="review-header">
                <div class="reviewer-info">
                    <div class="reviewer-avatar">${review.name
                      .charAt(0)
                      .toUpperCase()}</div>
                    <div class="reviewer-details">
                        <div class="reviewer-name">${review.name}</div>
                        <div class="review-date">${reviewDate}</div>
                    </div>
                </div>
                <div class="review-rating">${starsHTML}</div>
            </div>
            <div class="review-content"><p>${review.content}</p></div>
        `;
    reviewsList.appendChild(reviewDiv);
  });

  ratingScore.textContent = data.totalRating;
  ratingCount.textContent = `(${data.totalReviews} đánh giá)`;

  ratingStars.innerHTML = "";
  for (let i = 1; i <= 5; i++) {
    if (i <= Math.floor(data.totalRating)) {
      ratingStars.innerHTML += '<i class="fas fa-star"></i>';
    } else if (i == Math.ceil(data.totalRating) && data.totalRating % 1 !== 0) {
      ratingStars.innerHTML += '<i class="fas fa-star-half-alt"></i>';
    } else {
      ratingStars.innerHTML += '<i class="far fa-star"></i>';
    }
  }

  ratingBreakdown.innerHTML = "";
  for (let i = 5; i >= 1; i--) {
    const bar = document.createElement("div");
    bar.className = "rating-bar";
    bar.innerHTML = `
            <span class="rating-label">${i} sao</span>
            <div class="bar-container">
                <div class="bar-fill" style="width: ${data.ratingBreakdown[i].percentage}%"></div>
            </div>
            <span class="rating-percent">${data.ratingBreakdown[i].percentage}%</span>
        `;
    ratingBreakdown.appendChild(bar);
  }
}

function updatePaginationControls() {
  const paginationControls = document.querySelector(".pagination-controls");
  const showMoreBtn = document.querySelector(".show-more-reviews");
  const paginationButtons = document.querySelector(".pagination-buttons");

  if (totalPages <= 1 && currentLimit >= 10) {
    paginationControls.style.display = "none";
    return;
  }

  paginationControls.style.display = "block";

  if (currentLimit < 10) {
    showMoreBtn.style.display = "block";
    paginationButtons.style.display = "none";
  } else {
    showMoreBtn.style.display = "none";
    paginationButtons.style.display = "flex";

    paginationButtons.innerHTML = "";
    const maxButtons = 5;
    let startPage = Math.max(1, currentPage - Math.floor((maxButtons - 1) / 2));
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);

    if (endPage - startPage + 1 > maxButtons) {
      endPage = startPage + maxButtons - 1;
    }

    if (startPage > 1) {
      const firstPageBtn = document.createElement("button");
      firstPageBtn.className = "pagination-btn";
      firstPageBtn.textContent = "1";
      firstPageBtn.addEventListener("click", () => {
        fetchReviews(1, currentLimit);
      });
      paginationButtons.appendChild(firstPageBtn);

      if (startPage > 2) {
        const ellipsis = document.createElement("span");
        ellipsis.className = "pagination-ellipsis";
        ellipsis.textContent = "...";
        paginationButtons.appendChild(ellipsis);
      }
      startPage++;
      endPage = Math.min(endPage, startPage + maxButtons - 2);
    }

    for (let i = startPage; i <= endPage; i++) {
      const pageBtn = document.createElement("button");
      pageBtn.className = `pagination-btn ${i === currentPage ? "active" : ""}`;
      pageBtn.textContent = i;
      pageBtn.addEventListener("click", () => {
        fetchReviews(i, currentLimit);
      });
      paginationButtons.appendChild(pageBtn);
    }

    if (endPage < totalPages) {
      if (endPage < totalPages - 1) {
        const ellipsis = document.createElement("span");
        ellipsis.className = "pagination-ellipsis";
        ellipsis.textContent = "...";
        paginationButtons.appendChild(ellipsis);
      }
      const lastPageBtn = document.createElement("button");
      lastPageBtn.className = "pagination-btn";
      lastPageBtn.textContent = totalPages;
      lastPageBtn.addEventListener("click", () => {
        fetchReviews(totalPages, currentLimit);
      });
      paginationButtons.appendChild(lastPageBtn);
    }

    const buttonCount = paginationButtons.children.length;
    if (buttonCount > maxButtons) {
      while (paginationButtons.children.length > maxButtons) {
        paginationButtons.removeChild(paginationButtons.lastChild);
      }
    }
  }

  showMoreBtn.removeEventListener("click", handleShowMore);
  showMoreBtn.addEventListener("click", handleShowMore);
}

function handleShowMore() {
  if (currentLimit < 10) {
    currentLimit = 10;
    fetchReviews(1, 10);
  }
}

function submitReview(event) {
  event.preventDefault();
  const formData = new FormData(event.target);
  formData.append("comment_restaurant", "true");

  const reviewData = {
    rating: formData.get("rating"),
    name: formData.get("reviewer-name"),
    email: formData.get("reviewer-email"),
    content: formData.get("review-content"),
  };

  if (!reviewData.rating) {
    alert("Vui lòng chọn số sao đánh giá!");
    return;
  }

  if (!reviewData.name.trim()) {
    alert("Vui lòng nhập họ và tên!");
    return;
  }

  if (!reviewData.email.trim()) {
    alert("Vui lòng nhập email!");
    return;
  }

  if (!reviewData.content.trim()) {
    alert("Vui lòng nhập nội dung đánh giá!");
    return;
  }

  const submitBtn = document.querySelector(".submit-review-btn");
  const originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
  submitBtn.disabled = true;

  fetch("/libertylaocai/user/submit", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;

      if (data.status === "success") {
        const newReview = createReviewElement(reviewData);
        const reviewsList = document.querySelector(".reviews-list");
        reviewsList.insertBefore(newReview, reviewsList.firstChild);
        fetchReviews(currentPage, currentLimit);
        alert(
          "Cảm ơn bạn đã chia sẻ đánh giá! Đánh giá của bạn đã được thêm thành công."
        );
        toggleReviewForm();
        newReview.scrollIntoView({ behavior: "smooth", block: "center" });
      } else {
        alert("Lỗi: " + data.message);
      }
    })
    .catch((error) => {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
      alert("Lỗi gửi đánh giá: " + error.message);
    });
}

function createReviewElement(reviewData) {
  const reviewDiv = document.createElement("div");
  reviewDiv.className = "review-item new-review";
  let starsHTML = "";
  for (let i = 1; i <= 5; i++) {
    starsHTML +=
      i <= reviewData.rating
        ? '<i class="fas fa-star"></i>'
        : '<i class="far fa-star"></i>';
  }
  const currentDate = new Date().toLocaleDateString("vi-VN");
  reviewDiv.innerHTML = `
        <div class="review-header">
            <div class="reviewer-info">
                <div class="reviewer-avatar">${reviewData.name
                  .charAt(0)
                  .toUpperCase()}</div>
                <div class="reviewer-details">
                    <div class="reviewer-name">${reviewData.name}</div>
                    <div class="review-date">${currentDate}</div>
                </div>
            </div>
            <div class="review-rating">${starsHTML}</div>
        </div>
        <div class="review-content"><p>${reviewData.content}</p></div>
    `;
  reviewDiv.style.background = "linear-gradient(135deg, #e3f2fd, #f8f9fa)";
  reviewDiv.style.border = "2px solid #007bff";
  reviewDiv.style.borderRadius = "10px";
  reviewDiv.style.padding = "20px";
  reviewDiv.style.marginBottom = "15px";
  setTimeout(() => {
    reviewDiv.style.background = "";
    reviewDiv.style.border = "";
    reviewDiv.style.borderRadius = "";
    reviewDiv.style.padding = "20px 0";
    reviewDiv.style.marginBottom = "";
    reviewDiv.classList.remove("new-review");
  }, 3000);
  return reviewDiv;
}
