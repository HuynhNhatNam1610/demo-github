let slideIndex = 1;
let slidesPerView = 3; // Mặc định hiển thị 3 ảnh
// Sửa hàm openTab trong quanlydatphong.js
function openTab(tabName) {
    const tabcontent = document.getElementsByClassName("tab-content");
    const tablinks = document.getElementsByClassName("tab-btn");
    
    for (let i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    for (let i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    
    document.getElementById(tabName).style.display = "block";
    event.currentTarget.className += " active";

    // Làm mới danh sách tương ứng khi mở tab
    if (tabName === 'event-management') {
        fetchEvents(1);
    } else if (tabName === 'booking-management') {
        fetchBookings(1);
    } else if (tabName === 'restaurant-management') {
        fetchRestaurants(1);
    } else if (tabName === 'bar-management') {
        fetchBars(1);
    } else if (tabName === 'contact-management') {
        fetchContacts(1);
    } else if (tabName === 'service-management') {
        fetchServices(1);
    }
}
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.querySelector(".main-content");
    const overlay = document.querySelector(".sidebar-overlay");
    const body = document.body;

    sidebar.classList.toggle("collapsed");
    sidebar.classList.toggle("active");
    mainContent.classList.toggle("collapsed");

    if (window.innerWidth <= 991) {
        if (sidebar.classList.contains("active")) {
            overlay.classList.add("active");
            body.classList.add("sidebar-open");
        } else {
            overlay.classList.remove("active");
            body.classList.remove("sidebar-open");
        }
    }
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.getElementsByName('selected_bookings[]');
    for (let checkbox of checkboxes) {
        checkbox.checked = selectAll.checked;
    }
}

function showLoading() {
    document.getElementById('loading').style.display = 'block';
}

function hideLoading() {
    document.getElementById('loading').style.display = 'none';
}

function updateBookingTable(bookings) {
    const tbody = document.getElementById('bookingTableBody');
    tbody.innerHTML = '';
    
    bookings.forEach(booking => {
        const row = document.createElement('tr');
        if (booking.is_read === 0) {
            row.classList.add('unread');
        }
        row.innerHTML = `
            <td>${booking.id}</td>
            <td>${booking.customer_name}</td>
            <td>${new Date(booking.created_at).toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
            <td>${booking.is_read === 0 ? 'Chưa đọc' : 'Đã đọc'}</td> <!-- Thêm cột trạng thái -->
            <td>
                <button class="btn btn-info btn-small" data-booking='${JSON.stringify(booking)}'><i class="fas fa-info-circle"></i></button>
                <button class="btn btn-danger btn-small" onclick="confirmDelete(${booking.id}, '${booking.id}')"><i class="fas fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    });

    // Add event listeners for buttons
    document.querySelectorAll('.btn-info').forEach(button => {
        button.addEventListener('click', () => {
            const booking = JSON.parse(button.getAttribute('data-booking'));
            openDetailModal(booking);
        });
    });
}

function updatePagination(total, per_page, current_page) {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';
    const total_pages = Math.ceil(total / per_page);
    
    if (total_pages <= 1) return;

    // Previous button
    if (current_page > 1) {
        const prev = document.createElement('a');
        prev.href = '#';
        prev.innerHTML = '&laquo;';
        prev.addEventListener('click', (e) => {
            e.preventDefault();
            const searchTerm = document.getElementById('searchBooking').value;
            const page = current_page - 1;
            if (searchTerm) {
                searchBookings(page);
            } else {
                fetchBookings(page);
            }
        });
        pagination.appendChild(prev);
    }

    // Always show first page
    const first = document.createElement('a');
    first.href = '#';
    first.textContent = '1';
    if (1 === current_page) {
        first.classList.add('active');
    }
    first.addEventListener('click', (e) => {
        e.preventDefault();
        const searchTerm = document.getElementById('searchBooking').value;
        if (searchTerm) {
            searchBookings(1);
        } else {
            fetchBookings(1);
        }
    });
    pagination.appendChild(first);

    // Show ellipsis if needed
    if (current_page > 3) {
        const ellipsis = document.createElement('span');
        ellipsis.className = 'ellipsis';
        ellipsis.textContent = '...';
        pagination.appendChild(ellipsis);
    }

    // Show pages around current page
    const start = Math.max(2, current_page - 1);
    const end = Math.min(total_pages - 1, current_page + 1);
    
    for (let i = start; i <= end; i++) {
        if (i > 1 && i < total_pages) {
            const a = document.createElement('a');
            a.href = '#';
            a.textContent = i;
            if (i === current_page) {
                a.classList.add('active');
            }
            a.addEventListener('click', (e) => {
                e.preventDefault();
                const searchTerm = document.getElementById('searchBooking').value;
                if (searchTerm) {
                    searchBookings(i);
                } else {
                    fetchBookings(i);
                }
            });
            pagination.appendChild(a);
        }
    }

    // Show ellipsis if needed
    if (current_page < total_pages - 2) {
        const ellipsis = document.createElement('span');
        ellipsis.className = 'ellipsis';
        ellipsis.textContent = '...';
        pagination.appendChild(ellipsis);
    }

    // Always show last page if different from first
    if (total_pages > 1) {
        const last = document.createElement('a');
        last.href = '#';
        last.textContent = total_pages;
        if (total_pages === current_page) {
            last.classList.add('active');
        }
        last.addEventListener('click', (e) => {
            e.preventDefault();
            const searchTerm = document.getElementById('searchBooking').value;
            if (searchTerm) {
                searchBookings(total_pages);
            } else {
                fetchBookings(total_pages);
            }
        });
        pagination.appendChild(last);
    }

    // Next button
    if (current_page < total_pages) {
        const next = document.createElement('a');
        next.href = '#';
        next.innerHTML = '&raquo;';
        next.addEventListener('click', (e) => {
            e.preventDefault();
            const searchTerm = document.getElementById('searchBooking').value;
            const page = current_page + 1;
            if (searchTerm) {
                searchBookings(page);
            } else {
                fetchBookings(page);
            }
        });
        pagination.appendChild(next);
    }
}
function fetchBookings(page = 1) {
    showLoading();
    const formData = new FormData();
    formData.append('action', 'search_bookings');
    formData.append('search_term', '');
    formData.append('page', page);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            updateBookingTable(data.data.bookings);
            updatePagination(data.data.total, data.data.per_page, data.data.current_page);
        }
    })
    .catch(error => {
        hideLoading();
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Đã xảy ra lỗi khi tải danh sách đặt phòng!'
        });
    });
}

function searchBookings(page = 1) {
    showLoading();
    const input = document.getElementById('searchBooking').value;
    const formData = new FormData();
    formData.append('action', 'search_bookings');
    formData.append('search_term', input);
    formData.append('page', page);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            updateBookingTable(data.data.bookings);
            updatePagination(data.data.total, data.data.per_page, data.data.current_page);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        hideLoading();
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Đã xảy ra lỗi khi tìm kiếm đặt phòng!'
        });
    });
}

function confirmDelete(bookingId, bookingCode) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: `Bạn có chắc muốn xóa đặt phòng mã ${bookingCode}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            document.getElementById('delete_booking_id').value = bookingId;
            const formData = new FormData(document.getElementById('deleteForm'));
            
            fetch('/libertylaocai/user/submit', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                Swal.fire({
                    icon: data.status === 'success' ? 'success' : 'error',
                    title: data.status === 'success' ? 'Thành công' : 'Lỗi',
                    text: data.message
                });
                if (data.status === 'success') {
                    updateBookingTable(data.data.bookings);
                    updatePagination(data.data.total, data.data.per_page, data.data.current_page);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                hideLoading();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Đã xảy ra lỗi khi xóa đặt phòng!'
                });
            });
        }
    });
}

function toggleSelectAllEvents() {
    const selectAll = document.getElementById('selectAllEvents');
    const checkboxes = document.getElementsByName('selected_events[]');
    for (let checkbox of checkboxes) {
        checkbox.checked = selectAll.checked;
    }
}
function updateEventTable(events) {
    const tbody = document.getElementById('eventTableBody');
    tbody.innerHTML = '';
    
    // Ánh xạ các giá trị type_event thành chuỗi hiển thị thân thiện
    const eventTypeMap = {
        'tiec-cuoi': 'Tiệc Cưới',
        'hoi-nghi': 'Hội Nghị',
        'gala-dinner': 'Gala Dinner',
        'sinh-nhat': 'Sinh Nhật',
        'other': 'Khác'
    };

    events.forEach(event => {
        const row = document.createElement('tr');
        if (event.is_read === 0) {
            row.classList.add('unread');
        }
        row.innerHTML = `
            <td>${event.id}</td>
            <td>${event.customer_name || 'N/A'}</td>
            <td>${eventTypeMap[event.type_event] || event.type_event}</td> 
            <td>${new Date(event.start_at).toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
            <td>${event.is_read === 0 ? 'Chưa đọc' : 'Đã đọc'}</td>
            <td>
                <button class="btn btn-info btn-small" onclick='openDetailEventModal(${JSON.stringify(event)})'><i class="fas fa-info-circle"></i></button>
                <button class="btn btn-danger btn-small" onclick="confirmDeleteEvent(${event.id}, '${event.id}')"><i class="fas fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    });
}
function updateEventPagination(total, per_page, current_page) {
    const pagination = document.getElementById('eventPagination');
    pagination.innerHTML = '';
    const total_pages = Math.ceil(total / per_page);
    
    if (total_pages <= 1) return;

    for (let i = 1; i <= total_pages; i++) {
        const a = document.createElement('a');
        a.href = '#';
        a.textContent = i;
        if (i === current_page) {
            a.classList.add('active');
        }
        a.addEventListener('click', (e) => {
            e.preventDefault();
            const searchTerm = document.getElementById('searchEvent').value;
            if (searchTerm) {
                searchEvents(i);
            } else {
                fetchEvents(i);
            }
        });
        pagination.appendChild(a);
    }
}

function fetchEvents(page = 1) {
    showLoading();
    const formData = new FormData();
    formData.append('action', 'search_events');
    formData.append('search_term', '');
    formData.append('page', page);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            updateEventTable(data.data.events);
            updateEventPagination(data.data.total, data.data.per_page, data.data.current_page);
        }
    })
    .catch(error => {
        hideLoading();
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Đã xảy ra lỗi khi tải danh sách đặt hội trường!'
        });
    });
}

function searchEvents(page = 1) {
    showLoading();
    const input = document.getElementById('searchEvent').value;
    const formData = new FormData();
    formData.append('action', 'search_events');
    formData.append('search_term', input);
    formData.append('page', page);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            updateEventTable(data.data.events);
            updateEventPagination(data.data.total, data.data.per_page, data.data.current_page);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        hideLoading();
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Đã xảy ra lỗi khi tìm kiếm đặt hội trường!'
        });
    });
}

function confirmDeleteEvent(eventId, eventCode) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: `Bạn có chắc muốn xóa đặt hội trường mã ${eventCode}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            document.getElementById('delete_event_id').value = eventId;
            const formData = new FormData(document.getElementById('deleteEventForm'));
            
            fetch('/libertylaocai/user/submit', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                Swal.fire({
                    icon: data.status === 'success' ? 'success' : 'error',
                    title: data.status === 'success' ? 'Thành công' : 'Lỗi',
                    text: data.message
                });
                if (data.status === 'success') {
                    updateEventTable(data.data.events);
                    updateEventPagination(data.data.total, data.data.per_page, data.data.current_page);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                hideLoading();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Đã xảy ra lỗi khi xóa đặt hội trường!'
                });
            });
        }
    });
}

function toggleSelectAllRestaurants() {
    const selectAll = document.getElementById('selectAllRestaurants');
    const checkboxes = document.getElementsByName('selected_restaurants[]');
    for (let checkbox of checkboxes) {
        checkbox.checked = selectAll.checked;
    }
}
function updateRestaurantTable(restaurants) {
    const tbody = document.getElementById('restaurantTableBody');
    tbody.innerHTML = '';
    
    restaurants.forEach(restaurant => {
        const row = document.createElement('tr');
        if (restaurant.is_read === 0) {
            row.classList.add('unread');
        }
        row.innerHTML = `
            <td>${restaurant.id}</td>
            <td>${restaurant.customer_name || 'N/A'}</td>
            <td>${new Date(restaurant.start_at).toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
            <td>${restaurant.occasion || 'N/A'}</td> <!-- Thêm cột Dịp -->
            <td>${restaurant.is_read === 0 ? 'Chưa đọc' : 'Đã đọc'}</td> 
            <td>
                <button class="btn btn-info btn-small" onclick='openDetailRestaurantModal(${JSON.stringify(restaurant)})'><i class="fas fa-info-circle"></i></button>
                <button class="btn btn-danger btn-small" onclick="confirmDeleteRestaurant(${restaurant.id}, '${restaurant.id}')"><i class="fas fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    });
}
function updateRestaurantPagination(total, per_page, current_page) {
    const pagination = document.getElementById('restaurantPagination');
    pagination.innerHTML = '';
    const total_pages = Math.ceil(total / per_page);
    
    if (total_pages <= 1) return;

    for (let i = 1; i <= total_pages; i++) {
        const a = document.createElement('a');
        a.href = '#';
        a.textContent = i;
        if (i === current_page) {
            a.classList.add('active');
        }
        a.addEventListener('click', (e) => {
            e.preventDefault();
            const searchTerm = document.getElementById('searchRestaurant').value;
            if (searchTerm) {
                searchRestaurants(i);
            } else {
                fetchRestaurants(i);
            }
        });
        pagination.appendChild(a);
    }
}

function fetchRestaurants(page = 1) {
    showLoading();
    const formData = new FormData();
    formData.append('action', 'search_restaurants');
    formData.append('search_term', '');
    formData.append('page', page);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            updateRestaurantTable(data.data.restaurants);
            updateRestaurantPagination(data.data.total, data.data.per_page, data.data.current_page);
        }
    })
    .catch(error => {
        hideLoading();
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Đã xảy ra lỗi khi tải danh sách đặt nhà hàng!'
        });
    });
}

function searchRestaurants(page = 1) {
    showLoading();
    const input = document.getElementById('searchRestaurant').value;
    const formData = new FormData();
    formData.append('action', 'search_restaurants');
    formData.append('search_term', input);
    formData.append('page', page);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            updateRestaurantTable(data.data.restaurants);
            updateRestaurantPagination(data.data.total, data.data.per_page, data.data.current_page);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        hideLoading();
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Đã xảy ra lỗi khi tìm kiếm đặt nhà hàng!'
        });
    });
}

function confirmDeleteRestaurant(restaurantId, restaurantCode) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: `Bạn có chắc muốn xóa đặt nhà hàng mã ${restaurantCode}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            document.getElementById('delete_restaurant_id').value = restaurantId;
            const formData = new FormData(document.getElementById('deleteRestaurantForm'));
            
            fetch('/libertylaocai/user/submit', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                Swal.fire({
                    icon: data.status === 'success' ? 'success' : 'error',
                    title: data.status === 'success' ? 'Thành công' : 'Lỗi',
                    text: data.message
                });
                if (data.status === 'success') {
                    updateRestaurantTable(data.data.restaurants);
                    updateRestaurantPagination(data.data.total, data.data.per_page, data.data.current_page);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                hideLoading();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Đã xảy ra lỗi khi xóa đặt nhà hàng!'
                });
            });
        }
    });
}

function toggleSelectAllBars() {
    const selectAll = document.getElementById('selectAllBars');
    const checkboxes = document.getElementsByName('selected_bars[]');
    for (let checkbox of checkboxes) {
        checkbox.checked = selectAll.checked;
    }
}

function updateBarTable(bars) {
    const tbody = document.getElementById('barTableBody');
    tbody.innerHTML = '';
    
    bars.forEach(bar => {
        const row = document.createElement('tr');
        if (bar.is_read === 0) {
            row.classList.add('unread');
        }
        row.innerHTML = `
            <td>${bar.id}</td>
            <td>${bar.customer_name || 'N/A'}</td>
            <td>${new Date(bar.start_at).toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
            <td>${bar.is_read === 0 ? 'Chưa đọc' : 'Đã đọc'}</td> 
            <td>
                <button class="btn btn-info btn-small" onclick='openDetailBarModal(${JSON.stringify(bar)})'><i class="fas fa-info-circle"></i></button>
                <button class="btn btn-danger btn-small" onclick="confirmDeleteBar(${bar.id}, '${bar.id}')"><i class="fas fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    });
}
function updateBarPagination(total, per_page, current_page) {
    const pagination = document.getElementById('barPagination');
    pagination.innerHTML = '';
    const total_pages = Math.ceil(total / per_page);
    
    if (total_pages <= 1) return;

    for (let i = 1; i <= total_pages; i++) {
        const a = document.createElement('a');
        a.href = '#';
        a.textContent = i;
        if (i === current_page) {
            a.classList.add('active');
        }
        a.addEventListener('click', (e) => {
            e.preventDefault();
            const searchTerm = document.getElementById('searchBar').value;
            if (searchTerm) {
                searchBars(i);
            } else {
                fetchBars(i);
            }
        });
        pagination.appendChild(a);
    }
}

function fetchBars(page = 1) {
    showLoading();
    const formData = new FormData();
    formData.append('action', 'search_bars');
    formData.append('search_term', '');
    formData.append('page', page);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            updateBarTable(data.data.bars);
            updateBarPagination(data.data.total, data.data.per_page, data.data.current_page);
        }
    })
    .catch(error => {
        hideLoading();
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Đã xảy ra lỗi khi tải danh sách đặt bàn bar!'
        });
    });
}

function searchBars(page = 1) {
    showLoading();
    const input = document.getElementById('searchBar').value;
    const formData = new FormData();
    formData.append('action', 'search_bars');
    formData.append('search_term', input);
    formData.append('page', page);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            updateBarTable(data.data.bars);
            updateBarPagination(data.data.total, data.data.per_page, data.data.current_page);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        hideLoading();
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Đã xảy ra lỗi khi tìm kiếm đặt bàn bar!'
        });
    });
}

function confirmDeleteBar(barId, barCode) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: `Bạn có chắc muốn xóa đặt bàn bar mã ${barCode}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            document.getElementById('delete_bar_id').value = barId;
            const formData = new FormData(document.getElementById('deleteBarForm'));
            
            fetch('/libertylaocai/user/submit', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                Swal.fire({
                    icon: data.status === 'success' ? 'success' : 'error',
                    title: data.status === 'success' ? 'Thành công' : 'Lỗi',
                    text: data.message
                });
                if (data.status === 'success') {
                    updateBarTable(data.data.bars);
                    updateBarPagination(data.data.total, data.data.per_page, data.data.current_page);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                hideLoading();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Đã xảy ra lỗi khi xóa đặt bàn bar!'
                });
            });
        }
    });
}



function openDetailModal(booking) {
    // Gửi yêu cầu đánh dấu đã đọc
    showLoading();
    const formData = new FormData();
    formData.append('action', 'mark_as_read');
    formData.append('booking_id', booking.id);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            // Cập nhật lại danh sách đặt phòng sau khi đánh dấu đã đọc
            const searchTerm = document.getElementById('searchBooking').value;
            if (searchTerm) {
                searchBookings(1);
            } else {
                fetchBookings(1);
            }
            
            // Hiển thị modal chi tiết
            showBookingDetailModal(booking);
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error marking as read:', error);
        // Vẫn hiển thị modal chi tiết ngay cả khi có lỗi đánh dấu đã đọc
        showBookingDetailModal(booking);
    });
}

// Tách phần hiển thị modal thành hàm riêng để tái sử dụng
function showBookingDetailModal(booking) {
    document.getElementById('detail_id').textContent = booking.id;
    document.getElementById('detail_room_type').textContent = booking.room_type_name || 'Chưa xác định';
    document.getElementById('detail_customer_name').textContent = booking.customer_name || 'N/A';
    document.getElementById('detail_customer_phone').textContent = booking.customer_phone || 'N/A';
    document.getElementById('detail_customer_email').textContent = booking.customer_email || 'N/A';
    document.getElementById('detail_time_come').textContent = new Date(booking.time_come).toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    document.getElementById('detail_time_leave').textContent = new Date(booking.time_leave).toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    document.getElementById('detail_number_people').textContent = `${booking.number_adult + booking.number_children} (${booking.number_adult} người lớn, ${booking.number_children} trẻ em)`;
    document.getElementById('detail_note').textContent = booking.note || 'N/A';
    document.getElementById('detailModal').style.display = 'block';
}

function openDetailRestaurantModal(restaurant) {
    showLoading();
    const formData = new FormData();
    formData.append('action', 'mark_restaurant_as_read');
    formData.append('restaurant_id', restaurant.id);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            const searchTerm = document.getElementById('searchRestaurant').value;
            if (searchTerm) {
                searchRestaurants(1);
            } else {
                fetchRestaurants(1);
            }
            
            showRestaurantDetailModal(restaurant);
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error marking as read:', error);
        showRestaurantDetailModal(restaurant);
    });
}

function showRestaurantDetailModal(restaurant) {
    document.getElementById('detail_restaurant_id').textContent = restaurant.id;
    document.getElementById('detail_restaurant_location').textContent = restaurant.location || 'Chưa xác định';
    document.getElementById('detail_restaurant_customer_name').textContent = restaurant.customer_name || 'N/A';
    document.getElementById('detail_restaurant_customer_phone').textContent = restaurant.customer_phone || 'N/A';
    document.getElementById('detail_restaurant_customer_email').textContent = restaurant.customer_email || 'N/A';
    document.getElementById('detail_restaurant_start_at').textContent = new Date(restaurant.start_at).toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    document.getElementById('detail_restaurant_number_people').textContent = restaurant.number_people || 'N/A';
    document.getElementById('detail_restaurant_occasion').textContent = restaurant.occasion || 'N/A';
    document.getElementById('detail_restaurant_note').textContent = restaurant.note || 'N/A';
    document.getElementById('detailRestaurantModal').style.display = 'block';
}

function openDetailBarModal(bar) {
    showLoading();
    const formData = new FormData();
    formData.append('action', 'mark_bar_as_read');
    formData.append('bar_id', bar.id);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            const searchTerm = document.getElementById('searchBar').value;
            if (searchTerm) {
                searchBars(1);
            } else {
                fetchBars(1);
            }
            showBarDetailModal(bar);
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error marking as read:', error);
        showBarDetailModal(bar);
    });
}



// Cập nhật hàm openDetailEventModal
function openDetailEventModal(event) {
    showLoading();
    const formData = new FormData();
    formData.append('action', 'mark_event_as_read');
    formData.append('event_id', event.id);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            const searchTerm = document.getElementById('searchEvent').value;
            if (searchTerm) {
                searchEvents(1);
            } else {
                fetchEvents(1);
            }
            showEventDetailModal(event);
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error marking as read:', error);
        showEventDetailModal(event);
    });
}

// Cập nhật hàm openDetailRestaurantModal (đã có sẵn)
function showBarDetailModal(bar) {
    document.getElementById('detail_bar_id').textContent = bar.id;
    document.getElementById('detail_bar_customer_name').textContent = bar.customer_name || 'N/A';
    document.getElementById('detail_bar_customer_phone').textContent = bar.customer_phone || 'N/A';
    document.getElementById('detail_bar_customer_email').textContent = bar.customer_email || 'N/A';
    document.getElementById('detail_bar_start_at').textContent = new Date(bar.start_at).toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    document.getElementById('detail_bar_number_people').textContent = bar.number_people || 'N/A';
    document.getElementById('detail_bar_note').textContent = bar.note || 'N/A';
    document.getElementById('detailBarModal').style.display = 'block';
}
// Thêm hàm openDetailBarModal với chức năng đánh dấu đã đọc
function openDetailBarModal(bar) {
    showLoading();
    const formData = new FormData();
    formData.append('action', 'mark_bar_as_read');
    formData.append('bar_id', bar.id);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            const searchTerm = document.getElementById('searchBar').value;
            if (searchTerm) {
                searchBars(1);
            } else {
                fetchBars(1);
            }
            showBarDetailModal(bar);
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error marking as read:', error);
        showBarDetailModal(bar);
    });
}

function openDetailContactModal(contact) {
    showLoading();
    const formData = new FormData();
    formData.append('action', 'mark_contact_as_read');
    formData.append('contact_id', contact.id);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            const searchTerm = document.getElementById('searchContact').value;
            if (searchTerm) {
                searchContacts(1);
            } else {
                fetchContacts(1);
            }
            showContactDetailModal(contact);
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error marking as read:', error);
        showContactDetailModal(contact);
    });
}

function showContactDetailModal(contact) {
    document.getElementById('detail_contact_id').textContent = contact.id;
    document.getElementById('detail_contact_customer_name').textContent = contact.customer_name || 'N/A';
    document.getElementById('detail_contact_customer_phone').textContent = contact.customer_phone || 'N/A';
    document.getElementById('detail_contact_customer_email').textContent = contact.customer_email || 'N/A';
    document.getElementById('detail_contact_created_at').textContent = new Date(contact.created_at).toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    document.getElementById('detail_contact_message').textContent = contact.message || 'N/A';
    document.getElementById('detailContactModal').style.display = 'block';
}

// Thêm hàm openDetailServiceModal với chức năng đánh dấu đã đọc
function openDetailServiceModal(service) {
    showLoading();
    const formData = new FormData();
    formData.append('action', 'mark_service_as_read');
    formData.append('service_id', service.id);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            const searchTerm = document.getElementById('searchService').value;
            if (searchTerm) {
                searchServices(1);
            } else {
                fetchServices(1);
            }
            showServiceDetailModal(service);
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error marking as read:', error);
        showServiceDetailModal(service);
    });
}


function updateBarTable(bars) {
    const tbody = document.getElementById('barTableBody');
    tbody.innerHTML = '';
    
    bars.forEach(bar => {
        const row = document.createElement('tr');
        if (bar.is_read === 0) {
            row.classList.add('unread');
        }
        row.innerHTML = `
            <td>${bar.id}</td>
            <td>${bar.customer_name || 'N/A'}</td>
            <td>${new Date(bar.start_at).toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
            <td>${bar.is_read === 0 ? 'Chưa đọc' : 'Đã đọc'}</td> 
            <td>
                <button class="btn btn-info btn-small" onclick='openDetailBarModal(${JSON.stringify(bar)})'><i class="fas fa-info-circle"></i></button>
                <button class="btn btn-danger btn-small" onclick="confirmDeleteBar(${bar.id}, '${bar.id}')"><i class="fas fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    });
}


function updateContactTable(contacts) {
    const tbody = document.getElementById('contactTableBody');
    tbody.innerHTML = '';
    
    contacts.forEach(contact => {
        const row = document.createElement('tr');
        if (contact.is_read === 0) {
            row.classList.add('unread');
        }
        row.innerHTML = `
            <td>${contact.id}</td>
            <td>${contact.customer_name || 'N/A'}</td>
            <td>${new Date(contact.created_at).toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
            <td>${contact.is_read === 0 ? 'Chưa đọc' : 'Đã đọc'}</td> 
            <td>
                <button class="btn btn-info btn-small" onclick='openDetailContactModal(${JSON.stringify(contact)})'><i class="fas fa-info-circle"></i></button>
                <button class="btn btn-danger btn-small" onclick="confirmDeleteContact(${contact.id}, '${contact.id}')"><i class="fas fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Hàm cập nhật bảng dịch vụ
function updateServiceTable(services) {
    const tbody = document.getElementById('serviceTableBody');
    tbody.innerHTML = '';
    
    services.forEach(service => {
        const row = document.createElement('tr');
        if (service.is_read === 0) {
            row.classList.add('unread');
        }
        row.innerHTML = `
            <td>${service.id}</td>
            <td>${service.customer_name || 'N/A'}</td>
            <td>${new Date(service.created_at).toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
            <td>${service.is_read === 0 ? 'Chưa đọc' : 'Đã đọc'}</td> 
            <td>
                <button class="btn btn-info btn-small" onclick='openDetailServiceModal(${JSON.stringify(service)})'><i class="fas fa-info-circle"></i></button>
                <button class="btn btn-danger btn-small" onclick="confirmDeleteService(${service.id}, '${service.id}')"><i class="fas fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Tách phần hiển thị modal thành hàm riêng để tái sử dụng
function showEventDetailModal(event) {
    // Ánh xạ các giá trị type_event thành chuỗi hiển thị thân thiện
    const eventTypeMap = {
        'tiec-cuoi': 'Tiệc Cưới',
        'hoi-nghi': 'Hội Nghị',
        'gala-dinner': 'Gala Dinner',
        'sinh-nhat': 'Sinh Nhật',
        'other': 'Khác'
    };

    // Cập nhật thông tin chi tiết
    document.getElementById('detail_event_id').textContent = event.id;
    document.getElementById('detail_event_type').textContent = eventTypeMap[event.type_event] || event.type_event;
    document.getElementById('detail_event_customer_name').textContent = event.customer_name || 'N/A';
    document.getElementById('detail_event_customer_email').textContent = event.customer_email || 'N/A';
    document.getElementById('detail_event_customer_phone').textContent = event.customer_phone || 'N/A';
    document.getElementById('detail_event_hall_name').textContent = event.hall_name || 'N/A';
    document.getElementById('detail_event_start_at').textContent = new Date(event.start_at).toLocaleString('vi-VN');
    document.getElementById('detail_event_end_at').textContent = new Date(event.end_at).toLocaleString('vi-VN');
    document.getElementById('detail_event_number_people').textContent = event.number_people;
    document.getElementById('detail_event_note').textContent = event.note || 'N/A';
    document.getElementById('detail_event_budget').textContent = event.budget || 'N/A';

    // Xử lý ảnh trong slider
    const swiperWrapper = document.querySelector('#detail_event_images .swiper-wrapper');
    swiperWrapper.innerHTML = '';

    if (event.images && event.images !== '') {
        const images = event.images.split(',');
        images.forEach((image, index) => {
            const slide = document.createElement('div');
            slide.className = 'swiper-slide';
            const aElement = document.createElement('a');
            aElement.href = `/libertylaocai/view/img/uploads/${image}`;
            aElement.setAttribute('data-lightbox', 'event-images');
            aElement.setAttribute('data-title', `Hình ảnh sự kiện ${index + 1}`);

            const imgElement = document.createElement('img');
            imgElement.src = `/libertylaocai/view/img/uploads/${image}`;
            imgElement.alt = 'Hình ảnh sự kiện';
            imgElement.style.maxWidth = '150px';
            imgElement.style.maxHeight = '150px';
            imgElement.style.objectFit = 'cover';

            aElement.appendChild(imgElement);
            slide.appendChild(aElement);
            swiperWrapper.appendChild(slide);
        });

        // Khởi tạo Swiper
        new Swiper('#detail_event_images', {
            slidesPerView: 3,
            spaceBetween: 10,
            centeredSlides: false,
            loop: false,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    spaceBetween: 8
                },
                480: {
                    slidesPerView: 1,
                    spaceBetween: 5
                }
            }
        });

        // Thêm sự kiện cho lightbox với chức năng zoom
        document.querySelectorAll('[data-lightbox="event-images"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Tạo lightbox
                const lightbox = document.createElement('div');
                lightbox.className = 'lightbox';
                lightbox.style.position = 'fixed';
                lightbox.style.top = '0';
                lightbox.style.left = '0';
                lightbox.style.width = '100%';
                lightbox.style.height = '100%';
                lightbox.style.backgroundColor = 'rgba(0, 0, 0, 0.9)';
                lightbox.style.display = 'flex';
                lightbox.style.justifyContent = 'center';
                lightbox.style.alignItems = 'center';
                lightbox.style.zIndex = '1000';

                // Tạo nút đóng
                const closeButton = document.createElement('span');
                closeButton.className = 'lightbox-close';
                closeButton.innerHTML = '&times;';
                closeButton.style.position = 'absolute';
                closeButton.style.top = '20px';
                closeButton.style.right = '30px';
                closeButton.style.color = 'white';
                closeButton.style.fontSize = '35px';
                closeButton.style.fontWeight = 'bold';
                closeButton.style.cursor = 'pointer';
                closeButton.addEventListener('click', function() {
                    document.body.removeChild(lightbox);
                });

                // Tạo nút zoom in
                const zoomInButton = document.createElement('button');
                zoomInButton.innerHTML = '<i class="fas fa-search-plus"></i>';
                zoomInButton.style.position = 'absolute';
                zoomInButton.style.bottom = '20px';
                zoomInButton.style.right = '80px';
                zoomInButton.style.background = 'rgba(0,0,0,0.5)';
                zoomInButton.style.color = 'white';
                zoomInButton.style.border = 'none';
                zoomInButton.style.borderRadius = '50%';
                zoomInButton.style.width = '40px';
                zoomInButton.style.height = '40px';
                zoomInButton.style.fontSize = '20px';
                zoomInButton.style.cursor = 'pointer';

                // Tạo nút zoom out
                const zoomOutButton = document.createElement('button');
                zoomOutButton.innerHTML = '<i class="fas fa-search-minus"></i>';
                zoomOutButton.style.position = 'absolute';
                zoomOutButton.style.bottom = '20px';
                zoomOutButton.style.right = '30px';
                zoomOutButton.style.background = 'rgba(0,0,0,0.5)';
                zoomOutButton.style.color = 'white';
                zoomOutButton.style.border = 'none';
                zoomOutButton.style.borderRadius = '50%';
                zoomOutButton.style.width = '40px';
                zoomOutButton.style.height = '40px';
                zoomOutButton.style.fontSize = '20px';
                zoomOutButton.style.cursor = 'pointer';

                // Tạo hình ảnh
                const img = document.createElement('img');
                img.src = this.href;
                img.style.maxHeight = '80%';
                img.style.maxWidth = '80%';
                img.style.objectFit = 'contain';
                img.style.transition = 'transform 0.3s ease';
                
                let currentScale = 1;
                const scaleStep = 0.2;

                // Sự kiện zoom in
                zoomInButton.addEventListener('click', function() {
                    currentScale += scaleStep;
                    img.style.transform = `scale(${currentScale})`;
                });

                // Sự kiện zoom out
                zoomOutButton.addEventListener('click', function() {
                    if (currentScale > scaleStep) {
                        currentScale -= scaleStep;
                        img.style.transform = `scale(${currentScale})`;
                    }
                });

                // Thêm vào lightbox
                lightbox.appendChild(closeButton);
                lightbox.appendChild(zoomInButton);
                lightbox.appendChild(zoomOutButton);
                lightbox.appendChild(img);
                document.body.appendChild(lightbox);

                // Đóng khi click bên ngoài ảnh
                lightbox.addEventListener('click', function(e) {
                    if (e.target === this) {
                        document.body.removeChild(lightbox);
                    }
                });

                // Reset zoom khi ảnh được click
                img.addEventListener('click', function() {
                    currentScale = 1;
                    img.style.transform = 'scale(1)';
                });
            });
        });
    } else {
        swiperWrapper.innerHTML = '<p>Không có ảnh</p>';
    }

    document.getElementById('detailEventModal').style.display = 'block';
}
function adjustTableLayout() {
    const tables = document.querySelectorAll('.rooms-table');
    const screenWidth = window.innerWidth;
    
    tables.forEach(table => {
        if (screenWidth < 768) {
            table.classList.add('responsive-table');
            const rows = table.querySelectorAll('tr');
            rows.forEach((row, index) => {
                if (index === 0) return;
                const cells = row.querySelectorAll('td');
                if (cells.length > 4) {
                    for (let i = 3; i < cells.length - 1; i++) {
                        cells[i].style.display = 'none';
                    }
                }
            });
        } else {
            table.classList.remove('responsive-table');
            const cells = table.querySelectorAll('td');
            cells.forEach(cell => {
                cell.style.display = '';
            });
        }
    });
}
// Hàm chọn tất cả yêu cầu liên hệ
function toggleSelectAllContacts() {
    const selectAll = document.getElementById('selectAllContacts');
    const checkboxes = document.getElementsByName('selected_contacts[]');
    for (let checkbox of checkboxes) {
        checkbox.checked = selectAll.checked;
    }
}

// Hàm chọn tất cả yêu cầu dịch vụ
function toggleSelectAllServices() {
    const selectAll = document.getElementById('selectAllServices');
    const checkboxes = document.getElementsByName('selected_services[]');
    for (let checkbox of checkboxes) {
        checkbox.checked = selectAll.checked;
    }
}




// Hàm cập nhật phân trang cho liên hệ
function updateContactPagination(total, per_page, current_page) {
    const pagination = document.getElementById('contactPagination');
    pagination.innerHTML = '';
    const total_pages = Math.ceil(total / per_page);
    
    if (total_pages <= 1) return;

    for (let i = 1; i <= total_pages; i++) {
        const a = document.createElement('a');
        a.href = '#';
        a.textContent = i;
        if (i === current_page) {
            a.classList.add('active');
        }
        a.addEventListener('click', (e) => {
            e.preventDefault();
            const searchTerm = document.getElementById('searchContact').value;
            if (searchTerm) {
                searchContacts(i);
            } else {
                fetchContacts(i);
            }
        });
        pagination.appendChild(a);
    }
}

// Hàm cập nhật phân trang cho dịch vụ
function updateServicePagination(total, per_page, current_page) {
    const pagination = document.getElementById('servicePagination');
    pagination.innerHTML = '';
    const total_pages = Math.ceil(total / per_page);
    
    if (total_pages <= 1) return;

    for (let i = 1; i <= total_pages; i++) {
        const a = document.createElement('a');
        a.href = '#';
        a.textContent = i;
        if (i === current_page) {
            a.classList.add('active');
        }
        a.addEventListener('click', (e) => {
            e.preventDefault();
            const searchTerm = document.getElementById('searchService').value;
            if (searchTerm) {
                searchServices(i);
            } else {
                fetchServices(i);
            }
        });
        pagination.appendChild(a);
    }
}

// Hàm lấy danh sách liên hệ
function fetchContacts(page = 1) {
    showLoading();
    const formData = new FormData();
    formData.append('action', 'search_contacts');
    formData.append('search_term', '');
    formData.append('type', 'lienhe');
    formData.append('page', page);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            updateContactTable(data.data.contacts);
            updateContactPagination(data.data.total, data.data.per_page, data.data.current_page);
        }
    })
    .catch(error => {
        hideLoading();
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Đã xảy ra lỗi khi tải danh sách liên hệ!'
        });
    });
}

// Hàm tìm kiếm liên hệ
function searchContacts(page = 1) {
    showLoading();
    const input = document.getElementById('searchContact').value;
    const formData = new FormData();
    formData.append('action', 'search_contacts');
    formData.append('search_term', input);
    formData.append('type', 'lienhe');
    formData.append('page', page);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            updateContactTable(data.data.contacts);
            updateContactPagination(data.data.total, data.data.per_page, data.data.current_page);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        hideLoading();
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Đã xảy ra lỗi khi tìm kiếm liên hệ!'
        });
    });
}

// Hàm lấy danh sách dịch vụ
function fetchServices(page = 1) {
    showLoading();
    const formData = new FormData();
    formData.append('action', 'search_services');
    formData.append('search_term', '');
    formData.append('type', 'dichvu');
    formData.append('page', page);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            updateServiceTable(data.data.services);
            updateServicePagination(data.data.total, data.data.per_page, data.data.current_page);
        }
    })
    .catch(error => {
        hideLoading();
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Đã xảy ra lỗi khi tải danh sách dịch vụ!'
        });
    });
}

// Hàm tìm kiếm dịch vụ
function searchServices(page = 1) {
    showLoading();
    const input = document.getElementById('searchService').value;
    const formData = new FormData();
    formData.append('action', 'search_services');
    formData.append('search_term', input);
    formData.append('type', 'dichvu');
    formData.append('page', page);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            updateServiceTable(data.data.services);
            updateServicePagination(data.data.total, data.data.per_page, data.data.current_page);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        hideLoading();
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Đã xảy ra lỗi khi tìm kiếm dịch vụ!'
        });
    });
}

// Hàm xác nhận xóa liên hệ
function confirmDeleteContact(contactId, contactCode) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: `Bạn có chắc muốn xóa yêu cầu liên hệ mã ${contactCode}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            document.getElementById('delete_contact_id').value = contactId;
            const formData = new FormData(document.getElementById('deleteContactForm'));
            
            fetch('/libertylaocai/user/submit', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                Swal.fire({
                    icon: data.status === 'success' ? 'success' : 'error',
                    title: data.status === 'success' ? 'Thành công' : 'Lỗi',
                    text: data.message
                });
                if (data.status === 'success') {
                    updateContactTable(data.data.contacts);
                    updateContactPagination(data.data.total, data.data.per_page, data.data.current_page);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                hideLoading();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Đã xảy ra lỗi khi xóa yêu cầu liên hệ!'
                });
            });
        }
    });
}

// Hàm xác nhận xóa dịch vụ
function confirmDeleteService(serviceId, serviceCode) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: `Bạn có chắc muốn xóa yêu cầu dịch vụ mã ${serviceCode}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            document.getElementById('delete_service_id').value = serviceId;
            const formData = new FormData(document.getElementById('deleteServiceForm'));
            
            fetch('/libertylaocai/user/submit', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                Swal.fire({
                    icon: data.status === 'success' ? 'success' : 'error',
                    title: data.status === 'success' ? 'Thành công' : 'Lỗi',
                    text: data.message
                });
                if (data.status === 'success') {
                    updateServiceTable(data.data.services);
                    updateServicePagination(data.data.total, data.data.per_page, data.data.current_page);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                hideLoading();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Đã xảy ra lỗi khi xóa yêu cầu dịch vụ!'
                });
            });
        }
    });
}



// Hàm mở modal chi tiết dịch vụ
function openDetailServiceModal(service) {
    showLoading();
    const formData = new FormData();
    formData.append('action', 'mark_service_as_read');
    formData.append('service_id', service.id);

    fetch('/libertylaocai/user/submit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            const searchTerm = document.getElementById('searchService').value;
            if (searchTerm) {
                searchServices(1);
            } else {
                fetchServices(1);
            }
            showServiceDetailModal(service);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: data.message || 'Không thể đánh dấu đã đọc!'
            });
            showServiceDetailModal(service); // Vẫn hiển thị modal ngay cả khi lỗi
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error marking as read:', error);
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Đã xảy ra lỗi khi đánh dấu đã đọc!'
        });
        showServiceDetailModal(service); // Hiển thị modal ngay cả khi lỗi
    });
}
function showServiceDetailModal(service) {
    try {
        document.getElementById('detail_service_id').textContent = service.id || 'N/A';
        document.getElementById('detail_service_service').textContent = service.service || 'N/A';
        document.getElementById('detail_service_customer_name').textContent = service.customer_name || 'N/A';
        document.getElementById('detail_service_customer_phone').textContent = service.customer_phone || 'N/A';
        document.getElementById('detail_service_customer_email').textContent = service.customer_email || 'N/A';
        document.getElementById('detail_service_created_at').textContent = service.created_at ? 
            new Date(service.created_at).toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : 'N/A';
        document.getElementById('detail_service_message').textContent = service.message || 'N/A';
        document.getElementById('detailServiceModal').style.display = 'block';
    } catch (error) {
        console.error('Error displaying service modal:', error);
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Không thể hiển thị chi tiết dịch vụ!'
        });
    }
}
// Cập nhật hàm DOMContentLoaded để gọi fetchContacts và fetchServices khi tab tương ứng được mở
document.addEventListener('DOMContentLoaded', () => {
    adjustTableLayout();
    window.addEventListener('resize', adjustTableLayout);
    fetchBookings(1);
    if (document.getElementById('event-management').style.display === 'block') {
        fetchEvents(1);
    }
    if (document.getElementById('restaurant-management').style.display === 'block') {
        fetchRestaurants(1);
    }
    if (document.getElementById('bar-management').style.display === 'block') {
        fetchBars(1);
    }
    if (document.getElementById('contact-management').style.display === 'block') {
        fetchContacts(1);
    }
    if (document.getElementById('service-management').style.display === 'block') {
        fetchServices(1);
    }
});