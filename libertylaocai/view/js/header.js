$(document).ready(function () {
  // Tạo overlay element
  if (!$(".mobile-overlay").length) {
    $("body").append('<div class="mobile-overlay"></div>');
  }

  // Xử lý click vào nút menu toggle
  $(".menu-toggle").click(function (e) {
    e.preventDefault();
    $(".mobile-dropdown").addClass("active");
    $(".mobile-overlay").addClass("active");
    $("body").css("overflow", "hidden"); // Ngăn scroll khi menu mở
  });

  // Xử lý click vào category-option để gửi form
  $(document).on("click", ".category-option", function (e) {
    if ($(e.target).closest(".dropdown-content").length) {
      return;
    }
    var $form = $(this).closest("form");
    if ($form.length) {
      $form.submit();
    }
  });

  // Xử lý click vào mobile-category-option để gửi form
  $(document).on(
    "click",
    ".mobile-category-option:not(.has-dropdown)",
    function (e) {
      if (
        $(e.target).closest(".mobile-dropdown-content").length ||
        $(this).find("a").length
      ) {
        return;
      }
      var $form = $(this).closest("form");
      if ($form.length) {
        $form.submit();
      }
    }
  );

  // Xử lý click vào mobile-dropdown-item để gửi form
  $(document).on("click", ".mobile-dropdown-item", function (e) {
    e.preventDefault();
    var $form = $(this).closest("form");
    if ($form.length) {
      $form.submit();
    }
  });

  // Xử lý click vào language toggle
  $(".language-toggle").click(function (e) {
    e.preventDefault();
    var langId = $(this).data("lang");
    $.ajax({
      url: "/libertylaocai/view/php/set_language.php",
      method: "POST",
      data: { language_id: langId },
      success: function (response) {
        location.reload();
      },
      error: function (xhr, status, error) {
        console.error("Error changing language:", error);
      },
    });
  });

  // Xử lý click vào nút đóng menu
  $(document).on("click", ".close-menu", function (e) {
    e.preventDefault();
    closeMobileMenu();
  });

  // Xử lý click vào overlay để đóng menu
  $(".mobile-overlay").click(function () {
    closeMobileMenu();
  });

  // Xử lý click vào các menu item có dropdown
  $(document).on("click", ".mobile-category-option.has-dropdown", function (e) {
    e.preventDefault();

    var $this = $(this);
    var $dropdown = $this.find(".mobile-dropdown-content");

    // Toggle dropdown hiện tại
    $this.toggleClass("active");
    $dropdown.toggleClass("active");

    // Đóng các dropdown khác
    $(".mobile-category-option.has-dropdown").not($this).removeClass("active");
    $(".mobile-dropdown-content").not($dropdown).removeClass("active");
  });

  // Xử lý phím ESC để đóng menu
  $(document).keyup(function (e) {
    if (e.keyCode === 27) {
      // ESC key
      closeMobileMenu();
    }
  });

  // Hàm đóng mobile menu
  function closeMobileMenu() {
    $(".mobile-dropdown").removeClass("active");
    $(".mobile-overlay").removeClass("active");
    $("body").css("overflow", "auto"); // Cho phép scroll lại

    // Đóng tất cả dropdown con
    $(".mobile-category-option.has-dropdown").removeClass("active");
    $(".mobile-dropdown-content").removeClass("active");
  }

  // Xử lý resize window
  $(window).resize(function () {
    if ($(window).width() > 768) {
      closeMobileMenu();
    }
  });
});
