document.addEventListener("DOMContentLoaded", function () {
  const toggleBtns = document.querySelectorAll(".toggle-btn");

  toggleBtns.forEach((btn) => {
    btn.addEventListener("click", function (event) {
      event.stopPropagation();
      let parent = this.closest(".footer-column");
      let ul = parent.querySelector("ul");

      ul.classList.toggle("active");
      if (ul.classList.contains("active")) {
        this.classList.replace("bi-plus", "bi-dash");
      } else {
        this.classList.replace("bi-dash", "bi-plus");
      }
    });
  });

  // $(document).ready(function() {
  //     $(".slide-item a").on("click", function(event) {
  //         event.preventDefault(); // Ngăn chặn điều hướng mặc định

  //         let selectedId = $(this).attr("id"); // Lấy id của thẻ <a> được nhấn

  //         // Nếu thẻ <a> không có id, không làm gì cả
  //         if (!selectedId) return;

  //         // Xóa input ẩn cũ nếu có
  //         $("#sportsForm input[name='selected_sport']").remove();

  //         // Tạo input ẩn mới để gửi id
  //         let hiddenInput = $("<input>")
  //             .attr("type", "hidden")
  //             .attr("name", "selected_sport")
  //             .val(selectedId);

  //         $("#sportsForm").append(hiddenInput);

  //         $("#sportsForm").submit();
  //     });

  //     $("#all-product1").on("click", function(event) {
  //         event.preventDefault(); // Ngăn chặn điều hướng mặc định

  //         let selectedId = $(this).attr("id"); // Lấy id của thẻ <a>
  //         console.log(selectedId); // In id ra console
  //         if (!selectedId) return;

  //         // Xóa input cũ nếu có
  //         $("#sportsForm input[name='all-product1']").remove();

  //         // Tạo input hidden mới
  //         let hiddenInput = $("<input>")
  //             .attr("type", "hidden")
  //             .attr("name", "all-product1")
  //             .val(selectedId);

  //         $("#sportsForm").append(hiddenInput);
  //         $("#sportsForm").submit();
  //     });
  // });

  // Chọn tất cả các phần tử li có class "tieumuc"
  const tieumucs = document.querySelectorAll("li.tieumuc");

  tieumucs.forEach(function (li) {
    li.addEventListener("click", function () {
      const form = li.closest("form"); // Tìm thẻ form cha gần nhất
      if (form) {
        form.submit(); // Gửi form
      }
    });
  });
});
