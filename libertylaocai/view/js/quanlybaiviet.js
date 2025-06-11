function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const mainContent = document.querySelector(".main-content");
  const overlay = document.querySelector(".sidebar-overlay");
  const body = document.body;

  sidebar.classList.toggle("collapsed");
  sidebar.classList.toggle("active"); // Thêm lớp .active cho sidebar
  mainContent.classList.toggle("collapsed");

  // Xử lý overlay và khóa cuộn trang trên mobile
  if (window.innerWidth <= 991) {
    if (sidebar.classList.contains("active")) {
      overlay.classList.add("active");
      body.classList.add("sidebar-open"); // Khóa cuộn trang
    } else {
      overlay.classList.remove("active");
      body.classList.remove("sidebar-open");
    }
  }
}
