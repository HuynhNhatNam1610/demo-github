// Sidebar toggle functionality
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.querySelector(".sidebar-overlay");
  const mainContent = document.getElementById("mainContent");

  if (window.innerWidth <= 991) {
    // Mobile behavior
    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");

    // Prevent body scroll when sidebar is open
    if (sidebar.classList.contains("active")) {
      document.body.classList.add("sidebar-open");
    } else {
      document.body.classList.remove("sidebar-open");
    }
  } else {
    // Desktop behavior
    sidebar.classList.toggle("collapsed");
    if (mainContent) {
      mainContent.classList.toggle("collapsed");
    }
  }
}

function closeSidebar() {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.querySelector(".sidebar-overlay");

  sidebar.classList.remove("active");
  overlay.classList.remove("active");
  document.body.classList.remove("sidebar-open");
}

// Set active navigation item
function setActiveNavItem(pageName) {
  const navItems = document.querySelectorAll(".nav-item");
  navItems.forEach((item) => {
    item.classList.remove("active");
    if (item.getAttribute("data-page") === pageName) {
      item.classList.add("active");
    }
  });
}

// Improved auto-detect current page and set active nav item
function autoSetActiveNavItem() {
  const currentPath = window.location.pathname;
  console.log("Current path:", currentPath); // Debug log

  // Mapping URL paths to page names
  const pageMap = {
    "/libertylaocai/quan-ly-anh": "dashboard",
    "/libertylaocai/quan-ly-lich-dat": "bookings",
    "/libertylaocai/quan-ly-phong": "rooms",
    "/libertylaocai/quan-ly-thong-tin": "events",
    "/libertylaocai/quan-ly-binh-luan": "comment",
    "/libertylaocai/quan-ly-bai-viet": "tus",
    "/libertylaocai/quan-ly-menu": "menu",
    "/libertylaocai/quan-ly-dich-vu": "reports",
    "/libertylaocai/quan-ly-tour": "reports", //thêm casi này
    "/libertylaocai/tai_khoan": "settings",
  };

  // Find matching page
  let pageName = null;
  for (const [path, page] of Object.entries(pageMap)) {
    if (currentPath.includes(path)) {
      pageName = page;
      break;
    }
  }

  // Fallback to default
  if (!pageName) {
    pageName = "dashboard";
  }

  console.log("Setting active page:", pageName); // Debug log
  setActiveNavItem(pageName);
}

// Enhanced navigation click handler
function handleNavigationClick(event) {
  const clickedItem = event.currentTarget;
  const pageName = clickedItem.getAttribute("data-page");

  // Remove active class from all items and add to clicked item
  if (pageName) {
    setActiveNavItem(pageName);

    // Store the active page in localStorage for persistence
    localStorage.setItem("activeNavPage", pageName);
  }

  // Close sidebar on mobile after navigation
  if (window.innerWidth <= 991) {
    setTimeout(() => {
      closeSidebar();
    }, 150); // Small delay for better UX
  }
}

// Set active item based on URL or stored preference
function setActiveFromUrl() {
  const currentPath = window.location.pathname;
  const storedPage = localStorage.getItem("activeNavPage");

  // Priority: URL-based detection, then stored preference
  if (currentPath && currentPath !== "/") {
    autoSetActiveNavItem();
  } else if (storedPage) {
    setActiveNavItem(storedPage);
  } else {
    setActiveNavItem("dashboard"); // Default
  }
}

// Window resize handler
function handleSidebarResize() {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.querySelector(".sidebar-overlay");
  const mainContent = document.getElementById("mainContent");

  if (window.innerWidth > 991) {
    // Desktop mode
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
    document.body.classList.remove("sidebar-open");

    if (mainContent) {
      mainContent.classList.remove("mobile-full");
    }
  } else {
    // Mobile mode
    if (mainContent) {
      mainContent.classList.add("mobile-full");
      // Remove collapsed state on mobile
      if (sidebar.classList.contains("collapsed")) {
        sidebar.classList.remove("collapsed");
        mainContent.classList.remove("collapsed");
      }
    }
  }
}

// Keyboard shortcuts
function handleKeyboardShortcuts(event) {
  // Ctrl + / to toggle sidebar
  if (event.ctrlKey && event.key === "/") {
    event.preventDefault();
    toggleSidebar();
  }

  // Escape to close sidebar on mobile
  if (event.key === "Escape" && window.innerWidth <= 991) {
    closeSidebar();
  }
}

// Initialize sidebar functionality
function initializeSidebar() {
  console.log("Liberty Lào Cai Sidebar Component initialized");

  // Set active navigation item based on current page or stored preference
  setActiveFromUrl();

  // Add event listeners
  window.addEventListener("resize", handleSidebarResize);
  document.addEventListener("keydown", handleKeyboardShortcuts);

  // Add click handlers to navigation items
  const navItems = document.querySelectorAll(".nav-item[data-page]");
  navItems.forEach((item) => {
    item.addEventListener("click", handleNavigationClick);
  });

  // Handle overlay clicks
  const overlay = document.querySelector(".sidebar-overlay");
  if (overlay) {
    overlay.addEventListener("click", closeSidebar);
  }

  // Initial resize handling
  handleSidebarResize();
}

// Listen for page changes (for SPA-like behavior)
window.addEventListener("popstate", function () {
  setActiveFromUrl();
});

// Auto-initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", initializeSidebar);

// Export functions for use in other scripts
window.SidebarComponent = {
  toggle: toggleSidebar,
  close: closeSidebar,
  setActive: setActiveNavItem,
  setActiveFromUrl: setActiveFromUrl,
  init: initializeSidebar,
};
