// Navigation functionality
function showSection(sectionId, element) {
  // Hide all sections
  const sections = document.querySelectorAll(".content-section");
  sections.forEach((section) => {
    section.classList.remove("active");
  });

  // Show selected section
  document.getElementById(sectionId).classList.add("active");

  // Update active nav item
  const navItems = document.querySelectorAll(".nav-item");
  navItems.forEach((item) => {
    item.classList.remove("active");
  });
  element.classList.add("active");

  // Update page title
  const titles = {
    dashboard: "Dashboard",
    bookings: "Quản lý đặt phòng",
    rooms: "Quản lý phòng",
    events: "Quản lý sự kiện",
    services: "Dịch vụ",
    reports: "Báo cáo",
  };
  document.getElementById("page-title").textContent =
    titles[sectionId] || "Dashboard";

  // Close sidebar on mobile after selection
  if (window.innerWidth <= 991) {
    closeSidebar();
  }
}

// Sidebar toggle functionality
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.querySelector(".sidebar-overlay");
  const mainContent = document.getElementById("mainContent");

  if (window.innerWidth <= 991) {
    // Mobile behavior
    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
  } else {
    // Desktop behavior
    sidebar.classList.toggle("collapsed");
    mainContent.classList.toggle("collapsed");
  }
}

function closeSidebar() {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.querySelector(".sidebar-overlay");

  sidebar.classList.remove("active");
  overlay.classList.remove("active");
}

// Window resize handler
window.addEventListener("resize", function () {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.querySelector(".sidebar-overlay");
  const mainContent = document.getElementById("mainContent");

  if (window.innerWidth > 991) {
    // Desktop mode
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
    mainContent.classList.remove("mobile-full");
  } else {
    // Mobile mode
    mainContent.classList.add("mobile-full");
    if (!sidebar.classList.contains("collapsed")) {
      sidebar.classList.remove("collapsed");
      mainContent.classList.remove("collapsed");
    }
  }
});

// Table actions
function editRecord(id, type) {
  alert(`Chỉnh sửa ${type} với ID: ${id}`);
  // Implement edit functionality here
}

function deleteRecord(id, type) {
  if (confirm(`Bạn có chắc muốn xóa ${type} này?`)) {
    alert(`Đã xóa ${type} với ID: ${id}`);
    // Implement delete functionality here
  }
}

function cancelBooking(id) {
  if (confirm("Bạn có chắc muốn hủy đặt phòng này?")) {
    alert(`Đã hủy đặt phòng ${id}`);
    // Implement cancel booking functionality here
  }
}

// Add new record functions
function addNewBooking() {
  alert("Mở form thêm đặt phòng mới");
  // Implement add booking form
}

function addNewRoom() {
  alert("Mở form thêm phòng mới");
  // Implement add room form
}

function addNewEvent() {
  alert("Mở form thêm sự kiện mới");
  // Implement add event form
}

// Status update functions
function updateStatus(id, newStatus, type) {
  alert(`Cập nhật trạng thái ${type} ${id} thành: ${newStatus}`);
  // Implement status update functionality
}

// Search and filter functionality
function initializeSearch() {
  const searchInputs = document.querySelectorAll(".search-input");
  searchInputs.forEach((input) => {
    input.addEventListener("input", function () {
      const searchTerm = this.value.toLowerCase();
      const table =
        this.closest(".content-section").querySelector(".table tbody");
      const rows = table.querySelectorAll("tr");

      rows.forEach((row) => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    });
  });
}

// Date range picker initialization
function initializeDatePickers() {
  const dateInputs = document.querySelectorAll('input[type="date"]');
  dateInputs.forEach((input) => {
    input.addEventListener("change", function () {
      console.log("Date changed:", this.value);
      // Implement date filtering logic
    });
  });
}

// Chart initialization (placeholder for Chart.js)
function initializeCharts() {
  // Occupancy Chart
  const occupancyCtx = document.getElementById("occupancyChart");
  if (occupancyCtx) {
    // This is a placeholder - you would use Chart.js here
    console.log("Initializing occupancy chart");

    // Example with Chart.js (uncomment when Chart.js is loaded):
    /*
        new Chart(occupancyCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Tỷ lệ lấp đầy (%)',
                    data: [65, 70, 75, 72, 78, 80],
                    borderColor: '#66bb6a',
                    backgroundColor: 'rgba(102, 187, 106, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
        */
  }
}

// Real-time updates (WebSocket placeholder)
function initializeRealTimeUpdates() {
  // Placeholder for WebSocket connection
  setInterval(() => {
    // Update dashboard metrics
    updateDashboardMetrics();
  }, 30000); // Update every 30 seconds
}

function updateDashboardMetrics() {
  // Simulate real-time data updates
  const metrics = document.querySelectorAll(".card-value");
  metrics.forEach((metric, index) => {
    // Add subtle animation to indicate update
    metric.style.transform = "scale(1.05)";
    setTimeout(() => {
      metric.style.transform = "scale(1)";
    }, 200);
  });
}

// Notification system
function showNotification(message, type = "info") {
  const notification = document.createElement("div");
  notification.className = `notification notification-${type}`;
  notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">&times;</button>
        </div>
    `;

  // Add notification styles
  notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        min-width: 300px;
        border-left: 4px solid ${
          type === "success"
            ? "#66bb6a"
            : type === "error"
            ? "#e53935"
            : "#2196f3"
        };
        animation: slideInRight 0.3s ease;
    `;

  document.body.appendChild(notification);

  // Auto remove after 5 seconds
  setTimeout(() => {
    if (notification.parentElement) {
      notification.remove();
    }
  }, 5000);
}

// Export functions
function exportToCSV(tableId, filename) {
  const table = document.querySelector(`#${tableId} .table`);
  if (!table) return;

  let csv = [];
  const rows = table.querySelectorAll("tr");

  rows.forEach((row) => {
    const cols = row.querySelectorAll("td, th");
    const rowData = [];
    cols.forEach((col) => {
      rowData.push('"' + col.textContent.replace(/"/g, '""') + '"');
    });
    csv.push(rowData.join(","));
  });

  const csvContent = csv.join("\n");
  const blob = new Blob([csvContent], { type: "text/csv" });
  const url = window.URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = filename + ".csv";
  a.click();
  window.URL.revokeObjectURL(url);
}

// Print functionality
function printReport(sectionId) {
  const section = document.getElementById(sectionId);
  const printWindow = window.open("", "_blank");

  printWindow.document.write(`
        <html>
            <head>
                <title>Báo cáo - Liberty Lào Cai</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f5f5f5; }
                    .no-print { display: none; }
                    @media print { .no-print { display: none !important; } }
                </style>
            </head>
            <body>
                <h1>Liberty Lào Cai - Báo cáo</h1>
                <p>Ngày in: ${new Date().toLocaleDateString("vi-VN")}</p>
                ${section.innerHTML}
            </body>
        </html>
    `);

  printWindow.document.close();
  printWindow.print();
}

// Initialize everything when page loads
document.addEventListener("DOMContentLoaded", function () {
  console.log("Liberty Lào Cai Admin Dashboard initialized");

  // Initialize components
  initializeSearch();
  initializeDatePickers();
  initializeCharts();
  initializeRealTimeUpdates();

  // Add click handlers to buttons
  document.querySelectorAll(".btn-primary").forEach((btn) => {
    if (btn.textContent.includes("Thêm đặt phòng")) {
      btn.onclick = addNewBooking;
    } else if (btn.textContent.includes("Thêm phòng")) {
      btn.onclick = addNewRoom;
    } else if (btn.textContent.includes("Thêm sự kiện")) {
      btn.onclick = addNewEvent;
    }
  });

  // Add keyboard shortcuts
  document.addEventListener("keydown", function (e) {
    // Ctrl + / to toggle sidebar
    if (e.ctrlKey && e.key === "/") {
      e.preventDefault();
      toggleSidebar();
    }

    // Escape to close sidebar on mobile
    if (e.key === "Escape" && window.innerWidth <= 991) {
      closeSidebar();
    }
  });

  // Show welcome notification
  setTimeout(() => {
    showNotification(
      "Chào mừng đến với Liberty Lào Cai Admin Dashboard!",
      "success"
    );
  }, 1000);
});

// Add CSS animations for notifications
const style = document.createElement("style");
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .notification-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .notification-close {
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        margin-left: 10px;
        opacity: 0.6;
    }
    
    .notification-close:hover {
        opacity: 1;
    }
`;
document.head.appendChild(style);
