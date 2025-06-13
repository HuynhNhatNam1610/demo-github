$(document).ready(function () {
  let currentTab = "dichvu";
  let currentSubTab = "tour";
  let currentPage = 1; // Thêm biến này

  // Khởi tạo trang
  loadTabData();
  $(document).on("click", ".pagination-btn", function () {
    currentPage = parseInt($(this).data("page"));
    loadTabData();
  });

  // Xử lý chuyển đổi tab chính
  $(".tab-link").click(function () {
    currentPage = 1; // Reset về trang 1 khi chuyển tab

    $(".tab-link").removeClass("active");
    $(this).addClass("active");

    $(".tab-content").removeClass("active");
    currentTab = $(this).data("tab");
    $("#" + currentTab).addClass("active");

    if (currentTab === "dichvu") {
      currentSubTab = "tour";
      $(".sub-tab-link").removeClass("active");
      $('.sub-tab-link[data-subtab="tour"]').addClass("active");
    } else if (currentTab === "phong") {
      // Lấy sub-tab đầu tiên từ danh sách sub-tabs
      const firstSubTab =
        $("#phong .sub-tab-link").first().data("subtab") || "phongdon";
      currentSubTab = firstSubTab;
      $(".sub-tab-link").removeClass("active");
      $(`.sub-tab-link[data-subtab="${firstSubTab}"]`).addClass("active");
    } else {
      currentSubTab = "";
    }

    loadTabData();
  });

  // Xử lý chuyển đổi sub-tab
  $(".sub-tab-link").click(function () {
    currentPage = 1; // Reset về trang 1 khi chuyển tab

    $(".sub-tab-link").removeClass("active");
    $(this).addClass("active");
    currentSubTab = $(this).data("subtab");
    loadTabData();
  });

  // Mở modal thêm bình luận
  $("#openAddModal").click(function () {
    $("#addModal").show();
    resetAddForm();
  });

  // Đóng modal
  $(".close, .btn-cancel").click(function () {
    $(".modal").hide();
  });

  // Đóng modal khi click ra ngoài
  $(window).click(function (event) {
    if ($(event.target).hasClass("modal")) {
      $(".modal").hide();
    }
  });

  // Xử lý thay đổi loại khách hàng
  $("#customerSelect").change(function () {
    if ($(this).val()) {
      $("#manualCustomer").hide();
      $("#manualCustomer input").removeAttr("required");
    } else {
      $("#manualCustomer").show();
      $("#manualCustomer input").attr("required", "required");
    }
  });

  // Xử lý thay đổi loại bình luận
  $("#commentType").change(function () {
    const type = $(this).val();
    $(
      '.form-group select[name="id_dichvu"], .form-group select[name="id_loaiphong"]'
    )
      .parent()
      .hide();
    $(
      '.form-group select[name="id_dichvu"], .form-group select[name="id_loaiphong"]'
    ).removeAttr("required");

    if (type === "dichvu") {
      $("#dichvuSelect").show();
      $('select[name="id_dichvu"]').attr("required", "required");
    } else if (type === "phong") {
      $("#phongSelect").show();
      $('select[name="id_loaiphong"]').attr("required", "required");
    }
  });

  // Xử lý form thêm bình luận
  // Xử lý form thêm bình luận
  $("#addCommentForm").submit(function (e) {
    e.preventDefault();

    const formData = $(this).serialize() + "&action=add";

    showLoading();
    $.post(
      "/libertylaocai/controller/UserController.php",
      formData,
      function (response) {
        hideLoading();
        const data = JSON.parse(response);

        if (data.status === "success") {
          showNotification(data.message, "success");
          $("#addModal").hide();
          loadTabData();

          // Cập nhật dropdown khách hàng
          const id_khachhang = $("#customerSelect").val();
          if (!id_khachhang) {
            // Nếu nhập thủ công
            const name = $('input[name="name"]').val();
            const email = $('input[name="email"]').val();
            if (name && email) {
              // Thêm option mới vào dropdown
              const newOption = `<option value="${data.id_khachhang}" data-name="${name}" data-email="${email}">${name} (${email})</option>`;
              $("#customerSelect").append(newOption);
            }
          }
        } else {
          showNotification(data.message || "Có lỗi xảy ra!", "error");
        }
      }
    ).fail(function () {
      hideLoading();
      showNotification("Có lỗi xảy ra khi gửi dữ liệu!", "error");
    });
  });

  // Xử lý form chỉnh sửa bình luận
  $("#editCommentForm").submit(function (e) {
    e.preventDefault();

    const formData = $(this).serialize() + "&action=edit";

    showLoading();
    $.post(
      "/libertylaocai/controller/UserController.php",
      formData,
      function (response) {
        hideLoading();
        const data = JSON.parse(response);

        if (data.status === "success") {
          showNotification(data.message, "success");
          $("#editModal").hide();
          loadTabData();
        } else {
          showNotification(data.message || "Có lỗi xảy ra!", "error");
        }
      }
    ).fail(function () {
      hideLoading();
      showNotification("Có lỗi xảy ra khi gửi dữ liệu!", "error");
    });
  });

  // Xử lý nút chỉnh sửa
  $(document).on("click", ".edit", function () {
    const id = $(this).data("id");
    const content = $(this).data("content");
    const rate = $(this).data("rate");

    $('#editCommentForm input[name="id"]').val(id);
    $('#editCommentForm textarea[name="content"]').val(content);
    $('#editCommentForm input[name="rate"][value="' + rate + '"]').prop(
      "checked",
      true
    );

    $("#editModal").show();
  });

  // Xử lý checkbox chọn tất cả
  $(document).on("click", "#selectAll", function () {
    $(".select-comment").prop("checked", $(this).prop("checked"));
  });

  // Xử lý nút hiển thị/ẩn nhiều bình luận
  // Xử lý nút hiển thị/ẩn nhiều bình luận
  $(document).on("click", "#bulkToggle", function () {
    const selectedIds = $(".select-comment:checked")
      .map(function () {
        return $(this).val();
      })
      .get();

    if (selectedIds.length === 0) {
      showNotification("Vui lòng chọn ít nhất một bình luận!", "error");
      return;
    }

    // Kiểm tra trạng thái của các bình luận đã chọn
    let activeCount = 0;
    $(".select-comment:checked").each(function () {
      const isActive = parseInt($(this).closest("tr").data("active"));
      if (isActive === 1) {
        activeCount++;
      }
    });
    const inactiveCount = selectedIds.length - activeCount;
    let message;

    // Gỡ lỗi: In trạng thái ra console
    console.log("Selected IDs:", selectedIds);
    console.log("Active Count:", activeCount);
    console.log("Inactive Count:", inactiveCount);

    // Xác định thông báo dựa trên trạng thái
    if (activeCount === selectedIds.length) {
      message = "Bạn có chắc chắn muốn ẩn các bình luận đã chọn?";
    } else if (inactiveCount === selectedIds.length) {
      message = "Bạn có chắc chắn muốn hiển thị các bình luận đã chọn?";
    } else {
      message =
        "Bạn có chắc chắn muốn thay đổi trạng thái các bình luận đã chọn (hiển thị thành ẩn và ngược lại)?";
    }

    if (confirm(message)) {
      const formData = {
        action: "bulk_toggle_active",
        ids: selectedIds,
        active: null, // Gửi null để server xử lý đảo trạng thái
      };

      showLoading();
      $.post(
        "/libertylaocai/controller/UserController.php",
        formData,
        function (response) {
          hideLoading();
          const data = JSON.parse(response);

          if (data.status === "success") {
            showNotification(data.message, "success");
            loadTabData();
            $("#selectAll, .select-comment").prop("checked", false);
          } else {
            showNotification(data.message || "Có lỗi xảy ra!", "error");
          }
        }
      ).fail(function () {
        hideLoading();
        showNotification("Có lỗi xảy ra khi gửi dữ liệu!", "error");
      });
    }
  });

  // Xử lý nút xóa nhiều bình luận
  $(document).on("click", "#bulkDelete", function () {
    const selectedIds = $(".select-comment:checked")
      .map(function () {
        return parseInt($(this).val()); // Ép kiểu thành số nguyên
      })
      .get();

    if (selectedIds.length === 0) {
      showNotification("Vui lòng chọn ít nhất một bình luận!", "error");
      return;
    }

    if (
      confirm(
        "Bạn có chắc chắn muốn xóa các bình luận đã chọn? Hành động này không thể hoàn tác!"
      )
    ) {
      const formData = {
        action: "bulk_delete",
        ids: selectedIds,
      };

      showLoading();
      $.post(
        "/libertylaocai/controller/UserController.php",
        formData,
        function (response) {
          hideLoading();
          const data = JSON.parse(response);

          if (data.status === "success") {
            showNotification(data.message, "success");
            loadTabData();
            $("#selectAll, .select-comment").prop("checked", false);
          } else {
            showNotification(data.message || "Có lỗi xảy ra!", "error");
          }
        }
      ).fail(function () {
        hideLoading();
        showNotification("Có lỗi xảy ra khi gửi dữ liệu!", "error");
      });
    }
  });

  // Xử lý tìm kiếm
  $("#searchBtn").click(function () {
    currentPage = 1;
    loadTabData();
  });

  $("#searchInput").keypress(function (e) {
    if (e.which === 13) {
      currentPage = 1;
      loadTabData();
    }
  });

  $("#sortFilter, #statusFilter, #dateFilter, #rateFilter").change(function () {
    currentPage = 1;
    loadTabData();
  });

  $("#clearFilter").click(function () {
    $("#searchInput").val("");
    $("#sortFilter").val("newest");
    $("#statusFilter").val("");
    $("#dateFilter").val("");
    $("#rateFilter").val("");
    currentPage = 1;
    loadTabData();
  });

  // Đóng thông báo
  $("#closeNotification").click(function () {
    $("#notification").removeClass("show");
  });

  // Tự động đóng thông báo sau 5 giây
  setTimeout(function () {
    $("#notification").removeClass("show");
  }, 5000);

  // Hàm tải dữ liệu cho tab
  function loadTabData() {
    const search = $("#searchInput").val();
    const sort = $("#sortFilter").val();
    const status = $("#statusFilter").val();
    const date = $("#dateFilter").val();
    const rate = $("#rateFilter").val();

    const formData = {
      action: "load_data",
      tab: currentTab,
      subtab: currentSubTab,
      search: search,
      sort: sort,
      status: status,
      date: date,
      rate: rate,
      page: currentPage,
    };

    showLoading();
    $.post(
      "/libertylaocai/controller/UserController.php",
      formData,
      function (response) {
        hideLoading();
        const data = JSON.parse(response);

        if (data.status === "success") {
          $("#" + currentTab + "-table-body").html(data.html);

          // SỬA PHẦN NÀY - Cập nhật phân trang
          // const currentTab_selector = "#" + currentTab;
          // const paginationContainer = $(
          //   currentTab_selector + " .pagination-container"
          // );
          // const infoContainer = $(currentTab_selector + " .pagination-info");

          // if (data.pagination && data.total_pages > 1) {
          //   // Hiển thị phân trang
          //   paginationContainer.html(data.pagination).show();

          //   // Hiển thị thông tin trang
          //   let infoText = `Hiển thị ${Math.min(
          //     15 * (currentPage - 1) + 1,
          //     data.total_records
          //   )} - ${Math.min(
          //     15 * currentPage,
          //     data.total_records
          //   )} trong tổng số ${data.total_records} bình luận`;
          //   infoContainer.text(infoText).show();
          // } else {
          //   // Ẩn phân trang nếu chỉ có 1 trang hoặc không có dữ liệu
          //   paginationContainer.hide();
          //   if (data.total_records > 0) {
          //     infoContainer
          //       .text(`Tổng số ${data.total_records} bình luận`)
          //       .show();
          //   } else {
          //     infoContainer.hide();
          //   }
          // }
          const currentTab_selector = "#" + currentTab;
          const paginationContainer = $(
            currentTab_selector + " .pagination-container"
          );
          const infoContainer = $(currentTab_selector + " .pagination-info");

          if (data.pagination && data.total_pages > 1) {
            // Hiển thị phân trang
            paginationContainer.html(data.pagination).show();

            // Hiển thị thông tin trang
            let recordsOnPage = Math.min(
              15,
              data.total_records - (currentPage - 1) * 15
            ); // Số bản ghi trên trang hiện tại
            let infoText = `Hiển thị ${recordsOnPage} bình luận trong tổng số ${data.total_records} bình luận`;
            infoContainer.text(infoText).show();
          } else {
            // Ẩn phân trang nếu chỉ có 1 trang hoặc không có dữ liệu
            paginationContainer.hide();
            if (data.total_records > 0) {
              let recordsOnPage = Math.min(15, data.total_records); // Số bản ghi trên trang duy nhất
              infoContainer
                .text(
                  `Hiển thị ${recordsOnPage} bình luận trong tổng số ${data.total_records} bình luận`
                )
                .show();
            } else {
              infoContainer.hide();
            }
          }
        } else {
          showNotification("Có lỗi xảy ra khi tải dữ liệu!", "error");
        }
      }
    ).fail(function () {
      hideLoading();
      showNotification("Có lỗi xảy ra khi tải dữ liệu!", "error");
    });
  }

  // Hàm reset form thêm bình luận
  function resetAddForm() {
    $("#addCommentForm")[0].reset();
    $("#manualCustomer").show();
    $(
      '.form-group select[name="id_dichvu"], .form-group select[name="id_loaiphong"]'
    )
      .parent()
      .hide();
    $(
      '.form-group select[name="id_dichvu"], .form-group select[name="id_loaiphong"]'
    ).removeAttr("required");
    $("#manualCustomer input").attr("required", "required");
    // Reset star rating
    $("#addCommentForm .star-rating input[name='rate']").prop("checked", false);
    $("#addCommentForm .star-rating label").removeClass("selected");
  }

  // Hàm hiển thị loading
  function showLoading() {
    $("#loading").show();
  }

  // Hàm ẩn loading
  function hideLoading() {
    $("#loading").hide();
  }

  // Hàm hiển thị thông báo
  function showNotification(message, type = "info") {
    $("#notificationMessage").text(message);
    $("#notification")
      .removeClass("success error info")
      .addClass(type)
      .addClass("show");

    setTimeout(function () {
      $("#notification").removeClass("show");
    }, 5000);
  }

  // Xử lý hover effect cho star rating
  $(".star-rating input").change(function () {
    const rating = $(this).val();
    const container = $(this).closest(".star-rating");

    container.find("label").removeClass("selected");
    for (let i = rating; i >= 1; i--) {
      container
        .find('input[value="' + i + '"]')
        .next("label")
        .addClass("selected");
    }
  });

  // Xử lý hiệu ứng hover cho star rating
  $(".star-rating label").hover(
    function () {
      const rating = $(this).prev("input").val();
      const container = $(this).closest(".star-rating");

      container.find("label").removeClass("hover");
      for (let i = rating; i >= 1; i--) {
        container
          .find('input[value="' + i + '"]')
          .next("label")
          .addClass("hover");
      }
    },
    function () {
      $(this).closest(".star-rating").find("label").removeClass("hover");
    }
  );

  // Xử lý responsive cho bảng
  function makeTablesResponsive() {
    $(".table-container").each(function () {
      const table = $(this).find("table");
      if (table.width() > $(this).width()) {
        $(this).addClass("scrollable");
      } else {
        $(this).removeClass("scrollable");
      }
    });
  }

  $(window).resize(function () {
    makeTablesResponsive();
  });

  makeTablesResponsive();

  // setInterval(function () {
  //   if (!$(".modal").is(":visible")) {
  //     loadTabData();
  //   }
  // }, 30000);

  $(document).keydown(function (e) {
    if (e.ctrlKey && e.which === 78) {
      e.preventDefault();
      $("#openAddModal").click();
    }

    if (e.which === 27) {
      $(".modal").hide();
      $("#notification").removeClass("show");
    }

    if (e.ctrlKey && e.which === 70) {
      e.preventDefault();
      $("#searchInput").focus();
    }
  });
});
