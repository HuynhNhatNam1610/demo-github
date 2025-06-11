

// Sidebar toggle functionality
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const mainContent = document.getElementById('mainContent');

    if (window.innerWidth <= 991) {
        // Mobile behavior
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        
        // Prevent body scroll when sidebar is open
        if (sidebar.classList.contains('active')) {
            document.body.classList.add('sidebar-open');
        } else {
            document.body.classList.remove('sidebar-open');
        }
    } else {
        // Desktop behavior
        sidebar.classList.toggle('collapsed');
        if (mainContent) {
            mainContent.classList.toggle('collapsed');
        }
    }
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');

    sidebar.classList.remove('active');
    overlay.classList.remove('active');
    document.body.classList.remove('sidebar-open');
}

// Set active navigation item
function setActiveNavItem(pageName) {
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.classList.remove('active');
        if (item.getAttribute('data-page') === pageName) {
            item.classList.add('active');
        }
    });
}

// Auto-detect current page and set active nav item
function autoSetActiveNavItem() {
    const currentPath = window.location.pathname;
    const fileName = currentPath.split('/').pop().split('.')[0]; // Get filename without extension
    
    const pageMap = {
        'admin': 'dashboard',
        'dashboard': 'dashboard',
        'bookings': 'bookings',
        'rooms': 'rooms',
        'events': 'events',
        'services': 'services',
        'reports': 'reports',
        'settings': 'settings'
    };
    
    const pageName = pageMap[fileName] || 'dashboard';
    setActiveNavItem(pageName);
}

// Window resize handler
function handleSidebarResize() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const mainContent = document.getElementById('mainContent');

    if (window.innerWidth > 991) {
        // Desktop mode
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.classList.remove('sidebar-open');
        
        if (mainContent) {
            mainContent.classList.remove('mobile-full');
        }
    } else {
        // Mobile mode
        if (mainContent) {
            mainContent.classList.add('mobile-full');
            // Remove collapsed state on mobile
            if (sidebar.classList.contains('collapsed')) {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('collapsed');
            }
        }
    }
}

// Handle navigation clicks
function handleNavigationClick(event) {
    // Close sidebar on mobile after navigation
    if (window.innerWidth <= 991) {
        closeSidebar();
    }
}

// Keyboard shortcuts
function handleKeyboardShortcuts(event) {
    // Ctrl + / to toggle sidebar
    if (event.ctrlKey && event.key === '/') {
        event.preventDefault();
        toggleSidebar();
    }

    // Escape to close sidebar on mobile
    if (event.key === 'Escape' && window.innerWidth <= 991) {
        closeSidebar();
    }
}

// Initialize sidebar functionality
function initializeSidebar() {
    console.log('Liberty Lào Cai Sidebar Component initialized');
    
    // Set active navigation item based on current page
    autoSetActiveNavItem();
    
    // Add event listeners
    window.addEventListener('resize', handleSidebarResize);
    document.addEventListener('keydown', handleKeyboardShortcuts);
    
    // Add click handlers to navigation items
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', handleNavigationClick);
    });
    
    // Handle overlay clicks
    const overlay = document.querySelector('.sidebar-overlay');
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }
    
    // Initial resize handling
    handleSidebarResize();
}

// Auto-initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initializeSidebar);

// Export functions for use in other scripts
window.SidebarComponent = {
    toggle: toggleSidebar,
    close: closeSidebar,
    setActive: setActiveNavItem,
    init: initializeSidebar
};