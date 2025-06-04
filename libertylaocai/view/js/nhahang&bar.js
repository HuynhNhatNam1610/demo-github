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
    // Reset error messages
    document.querySelectorAll(".error-message").forEach((span) => {
      span.style.display = "none";
      span.textContent = "";
    });
    document
      .querySelectorAll(".form-group input, .form-group select")
      .forEach((input) => {
        input.classList.remove("error");
      });
  }
}

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

      // Clear previous error messages
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
            if (data.status === "success") {
              alert(data.message);
              bookingForm.reset();
              closeModal();
            } else {
              alert(data.message);
            }
          })
          .catch((error) => {
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

  const bookingDateInput = document.getElementById("bookingDate");
  if (bookingDateInput) {
    bookingDateInput.min = new Date().toISOString().split("T")[0];
  }
});

$(document).on("click", ".service-more", function (e) {
  e.preventDefault();

  var formId = $(this).data("form-id"); // Lấy giá trị từ data-form-id
  var $form = $("#" + formId); // Chọn form theo ID

  if ($form.length) {
    $form.submit(); // Gửi form
  }
});
