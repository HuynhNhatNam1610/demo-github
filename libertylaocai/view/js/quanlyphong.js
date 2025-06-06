let selectedRoomImages = [];
let isSubmitting = false;

function attachRoomImageUploadListener() {
    const imageUpload = document.getElementById('room_type_images');
    const uploadArea = document.querySelector('#addRoomTypeModal .upload-area');
    if (!imageUpload || !uploadArea) {
        console.error('Không tìm thấy imageUpload hoặc uploadArea');
        return;
    }

    uploadArea.onclick = () => imageUpload.click();

    imageUpload.addEventListener('change', function(e) {
        const files = e.target.files;
        if (!files.length) return;

        const maxTotalFiles = 4;
        let validNewFiles = Array.from(files).filter(file => {
            const isDuplicate = selectedRoomImages.some(
                f => f.name === file.name && f.size === file.size
            );
            if (isDuplicate) {
                showAlert('error', `Tệp ${file.name} đã được chọn.`);
                return false;
            }
            return true;
        });

        if (selectedRoomImages.length + validNewFiles.length > maxTotalFiles) {
            const remainingSlots = maxTotalFiles - selectedRoomImages.length;
            if (remainingSlots > 0) {
                showAlert('error', `Chỉ có thể thêm ${remainingSlots} ảnh nữa.`);
                validNewFiles = validNewFiles.slice(0, remainingSlots);
            } else {
                showAlert('error', `Đã đạt giới hạn tối đa ${maxTotalFiles} ảnh.`);
                return;
            }
        }

        selectedRoomImages = [...selectedRoomImages, ...validNewFiles];
        updateRoomTypeImageInput();
        renderRoomTypeImagePreviews();
        e.target.value = '';
    });
}

function updateRoomTypeImageInput() {
    const imageUpload = document.getElementById('room_type_images');
    if (!imageUpload) return;

    const dt = new DataTransfer();
    selectedRoomImages.forEach(file => dt.items.add(file));
    imageUpload.files = dt.files;
}

function renderRoomTypeImagePreviews() {
    const uploadArea = document.querySelector('#addRoomTypeModal .upload-area');
    const imageUpload = document.getElementById('room_type_images');
    if (!uploadArea || !imageUpload) return;

    uploadArea.innerHTML = `
        <div class="upload-icon">📷</div>
        <div class="upload-text">
            Nhấp để tải lên ảnh loại phòng<br>
            <small>Có thể tải lên tối đa 4 ảnh</small>
        </div>
    `;
    uploadArea.appendChild(imageUpload);

    if (!selectedRoomImages.length) {
        uploadArea.style.borderColor = '';
        uploadArea.style.background = '';
        return;
    }

    const previewContainer = document.createElement('div');
    previewContainer.className = 'images-grid';

    selectedRoomImages.forEach((file, index) => {
        const previewItem = document.createElement('div');
        previewItem.className = 'image-preview-item';

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.alt = 'Ảnh xem trước';

        const overlay = document.createElement('div');
        overlay.className = 'image-overlay';

        const imageName = document.createElement('span');
        imageName.className = 'image-name';
        imageName.textContent = file.name.length > 10 ? file.name.substring(0, 10) + '...' : file.name;

        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-btn';
        removeBtn.innerHTML = '×';
        removeBtn.onclick = (e) => {
            e.stopPropagation();
            selectedRoomImages.splice(index, 1);
            updateRoomTypeImageInput();
            renderRoomTypeImagePreviews();
        };

        overlay.appendChild(imageName);
        overlay.appendChild(removeBtn);
        previewItem.appendChild(img);
        previewItem.appendChild(overlay);
        previewContainer.appendChild(previewItem);
    });

    uploadArea.innerHTML = `
        <div class="upload-header">
            <span class="upload-count">Đã chọn ${selectedRoomImages.length} hình ảnh</span>
            <button class="add-more-btn" type="button">Thêm ảnh</button>
        </div>
    `;
    uploadArea.appendChild(previewContainer);
    uploadArea.appendChild(imageUpload);
    uploadArea.style.borderColor = '#004d40';
    uploadArea.style.background = '#f0f8f0';

    uploadArea.querySelector('.add-more-btn').onclick = () => imageUpload.click();
}

function clearRoomTypeImagePreviews() {
    selectedRoomImages = [];
    updateRoomTypeImageInput();
    renderRoomTypeImagePreviews();
}

function updateRoomList(rooms) {
    const tbody = document.querySelector('#room-management .rooms-table tbody');
    if (!tbody) return;
    tbody.innerHTML = '';

    rooms.forEach(room => {
        const statusText = {
            'available': 'Trống',
            'reserved': 'Đã đặt',
            'maintenance': 'Bảo trì'
        }[room.status] || room.status;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="checkbox" name="selected_rooms[]" value="${room.id}"></td>
            <td><strong>${room.room_number}</strong></td>
            <td data-room-type-id="${room.id_loaiphong}">${room.room_type_name || 'Chưa xác định'}</td>
            <td>${Number(room.price).toLocaleString('vi-VN')} VNĐ</td>
            <td>${room.area} m²</td>
            <td><span class="status-badge status-${room.status}">${statusText}</span></td>
            <td>
                <button class="btn btn-warning btn-small" onclick='openEditModal(${JSON.stringify(room)})'>
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-danger btn-small" onclick="confirmDelete(${room.id}, '${room.room_number}')">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function updateStats(stats) {
    if (!stats) return;
    const selectors = {
        total: '.stat-card.total .stat-number',
        available: '.stat-card.available .stat-number',
        reserved: '.stat-card.reserved .stat-number',
        maintenance: '.stat-card.maintenance .stat-number'
    };
    Object.entries(selectors).forEach(([key, selector]) => {
        const element = document.querySelector(selector);
        if (element) element.textContent = stats[`${key}_rooms`];
    });
}

function updateRoomTypeStats(roomTypeStats) {
    const tbody = document.querySelector('.room-type-stats .rooms-table tbody');
    if (!tbody) return;
    tbody.innerHTML = '';

    roomTypeStats.forEach(stat => {
        const usageRate = stat.total_quantity > 0
            ? Math.round(((stat.actual_rooms - stat.available_count) / stat.total_quantity) * 100 * 10) / 10
            : 0;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td><strong>${stat.name || 'Chưa xác định'}</strong></td>
            <td>${stat.total_quantity}</td>
            <td>${stat.actual_rooms}</td>
            <td>${stat.available_count}</td>
            <td>${usageRate}%</td>
        `;
        tbody.appendChild(row);
    });
}

function updateRoomTypeList(roomTypes) {
    const tbody = document.querySelector('#room-type-management .rooms-table tbody');
    if (!tbody) return;
    tbody.innerHTML = '';

    roomTypes.forEach(type => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><strong>${type.name}</strong></td>
            <td>${type.description.length > 50 ? type.description.substring(0, 50) + '...' : type.description}</td>
            <td>${Number(type.price).toLocaleString('vi-VN')} VNĐ</td>
            <td>${type.area} m²</td>
            <td>${type.quantity}</td>
            <td>${type.image_count}</td>
            <td>
                <button class="btn btn-warning btn-small" onclick='openEditRoomTypeModal(${JSON.stringify(type)})'>
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-danger btn-small" onclick="confirmDeleteRoomType(${type.id}, '${type.name}')">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });

    updateRoomTypeDropdowns(roomTypes);
}

function updateRoomTypeDropdowns(roomTypes) {
    const dropdowns = [
        document.querySelector('#addModal select[name="id_loaiphong"]'),
        document.querySelector('#editModal select[name="id_loaiphong"]'),
        document.querySelector('select[onchange="filterRoomsByType(this.value)"]')
    ];

    dropdowns.forEach(dropdown => {
        if (dropdown) {
            const currentValue = dropdown.value;
            dropdown.innerHTML = '<option value="">Chọn loại phòng...</option>';
            roomTypes.forEach(type => {
                const option = document.createElement('option');
                option.value = type.id;
                option.textContent = `${type.name} - ${Number(type.price).toLocaleString('vi-VN')} VNĐ (Số lượng: ${type.quantity})`;
                dropdown.appendChild(option);
            });
            dropdown.value = currentValue || '';
        }
    });
}

function fetchRoomTypes() {
    fetch('', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'action=fetch_room_types'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            updateRoomTypeList(data.room_types);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error fetching room types:', error);
        showAlert('error', 'Không thể tải danh sách loại phòng.');
    });
}

function submitFormAjax(formData, successCallback) {
    if (isSubmitting) return;
    isSubmitting = true;

    showLoading(true);
    fetch('', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        showLoading(false);
        isSubmitting = false;

        if (data.status === 'success') {
            showAlert('success', data.message);
            if (data.rooms) updateRoomList(data.rooms);
            if (data.stats) updateStats(data.stats);
            if (data.room_type_stats) updateRoomTypeStats(data.room_type_stats);
            if (data.room_types) updateRoomTypeList(data.room_types);
            if (successCallback) successCallback(data);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showLoading(false);
        isSubmitting = false;
        console.error('Error:', error);
        showAlert('error', 'Có lỗi xảy ra khi xử lý yêu cầu.');
    });
}

function showAlert(type, message) {
    const alertId = `${type}-alert-${Date.now()}`;
    const alertContainer = document.createElement('div');
    alertContainer.innerHTML = `
        <div class="alert alert-${type}" id="${alertId}">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> 
            ${message}
            <button class="close-alert" onclick="closeAlert('${alertId}')">×</button>
        </div>
    `;
    
    document.querySelector('.container').prepend(alertContainer);
    setTimeout(() => closeAlert(alertId), 3000);
}

function closeAlert(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) alert.remove();
}

function showLoading(show) {
    let loading = document.getElementById('loading-indicator');
    if (!loading && show) {
        loading = document.createElement('div');
        loading.id = 'loading-indicator';
        loading.style.cssText = `
            position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.7); color: white; padding: 15px 30px;
            border-radius: 5px; z-index: 1000;
        `;
        loading.textContent = 'Đang tải...';
        document.body.appendChild(loading);
    }
    if (loading) loading.style.display = show ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    attachRoomImageUploadListener();

    const addRoomForm = document.querySelector('#addRoomForm');
    if (addRoomForm) {
        addRoomForm.addEventListener('submit', e => {
            e.preventDefault();
            if (isSubmitting) return;
            submitFormAjax(new FormData(addRoomForm), () => closeModal('addModal'));
        });
    }

    const editRoomForm = document.querySelector('#editRoomForm');
    if (editRoomForm) {
        editRoomForm.addEventListener('submit', e => {
            e.preventDefault();
            if (isSubmitting) return;
            submitFormAjax(new FormData(editRoomForm), () => closeModal('editModal'));
        });
    }

    const addRoomTypeForm = document.querySelector('#addRoomTypeForm');
    if (addRoomTypeForm) {
        addRoomTypeForm.addEventListener('submit', e => {
            e.preventDefault();
            if (isSubmitting) return;

            if (!selectedRoomImages.length) {
                showAlert('error', 'Vui lòng chọn ít nhất một ảnh loại phòng.');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'add_room_type');
            formData.append('name_vi', document.getElementById('room_type_name_vi').value);
            formData.append('name_en', document.getElementById('room_type_name_en').value);
            formData.append('description_vi', document.getElementById('room_type_description_vi').value);
            formData.append('description_en', document.getElementById('room_type_description_en').value);
            formData.append('quantity', document.getElementById('room_type_quantity').value);
            formData.append('area', document.getElementById('room_type_area').value);
            formData.append('price', document.getElementById('room_type_price').value);

            selectedRoomImages.forEach(file => formData.append('images[]', file));

            submitFormAjax(formData, () => {
                closeModal('addRoomTypeModal');
                clearRoomTypeImagePreviews();
            });
        });
    }

    const editRoomTypeForm = document.querySelector('#editRoomTypeForm');
    if (editRoomTypeForm) {
        editRoomTypeForm.addEventListener('submit', e => {
            e.preventDefault();
            if (isSubmitting) return;
            submitFormAjax(new FormData(editRoomTypeForm), () => closeModal('editRoomTypeModal'));
        });
    }
});

function openAddModal() {
    document.getElementById('addModal').style.display = 'block';
}

function openEditModal(room) {
    document.getElementById('edit_room_id').value = room.id;
    document.getElementById('edit_room_number').value = room.room_number;
    document.getElementById('edit_id_loaiphong').value = room.id_loaiphong;
    document.getElementById('edit_status').value = room.status;
    document.getElementById('editModal').style.display = 'block';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.style.display = 'none';
    if (modalId === 'addRoomTypeModal') clearRoomTypeImagePreviews();
}

function confirmDelete(roomId, roomNumber) {
    if (confirm(`Bạn có chắc chắn muốn xóa phòng ${roomNumber}?`)) {
        const formData = new FormData();
        formData.append('action', 'delete_room');
        formData.append('room_id', roomId);
        submitFormAjax(formData);
    }
}

function openTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById(tabId).style.display = 'block';
    event.currentTarget.classList.add('active');
    if (tabId === 'room-type-management') fetchRoomTypes();
}

function openAddRoomTypeModal() {
    clearRoomTypeImagePreviews();
    document.getElementById('addRoomTypeModal').style.display = 'block';
}

function openEditRoomTypeModal(roomType) {
    document.getElementById('edit_room_type_id').value = roomType.id;
    document.getElementById('edit_room_type_name_vi').value = roomType.name || '';
    document.getElementById('edit_room_type_description_vi').value = roomType.description || '';
    document.getElementById('edit_room_type_name_en').value = roomType.name || '';
    document.getElementById('edit_room_type_description_en').value = roomType.description || '';
    document.getElementById('edit_room_type_price').value = roomType.price || '';
    document.getElementById('edit_room_type_quantity').value = roomType.quantity || '';
    document.getElementById('edit_room_type_area').value = roomType.area || '';

    const container = document.getElementById('current-images-container');
    container.innerHTML = roomType.images?.length
        ? roomType.images.map(img => `
            <div class="image-item">
                <img src="/libertylaocai/view/img/${img.image}" alt="Ảnh phòng">
                <div class="image-actions">
                    <input type="checkbox" name="delete_images[]" value="${img.id}" id="delete_img_${img.id}">
                    <label for="delete_img_${img.id}">Xóa</label>
                </div>
            </div>
        `).join('')
        : '<p>Không có ảnh nào</p>';

    ['1', '2', '3', '4'].forEach(i => {
        const input = document.getElementById(`edit_room_type_image${i}`);
        const preview = document.getElementById(`edit-new-image-preview-${i}`);
        if (input) input.value = '';
        if (preview) preview.innerHTML = '';
    });

    document.getElementById('editRoomTypeModal').style.display = 'block';
}

function confirmDeleteRoomType(roomTypeId, roomTypeName) {
    if (confirm(`Bạn có chắc chắn muốn xóa loại phòng "${roomTypeName}"?`)) {
        const formData = new FormData();
        formData.append('action', 'delete_room_type');
        formData.append('room_type_id', roomTypeId);
        submitFormAjax(formData);
    }
}

function filterRooms(status) {
    document.querySelectorAll('#room-management .rooms-table tbody tr').forEach(row => {
        const statusBadge = row.querySelector('.status-badge');
        row.style.display = !status || statusBadge.classList.contains(`status-${status}`) ? '' : 'none';
    });
}

function filterRoomsByType(typeId) {
    document.querySelectorAll('#room-management .rooms-table tbody tr').forEach(row => {
        const roomTypeId = row.querySelector('td:nth-child(3)').dataset.roomTypeId;
        row.style.display = !typeId || roomTypeId === typeId ? '' : 'none';
    });
}

function bulkUpdateStatus() {
    const status = document.getElementById('bulkStatus')?.value;
    if (!status) {
        showAlert('error', 'Vui lòng chọn trạng thái!');
        return;
    }

    const checkedBoxes = document.querySelectorAll('input[name="selected_rooms[]"]:checked');
    if (!checkedBoxes.length) {
        showAlert('error', 'Vui lòng chọn ít nhất một phòng!');
        return;
    }

    if (confirm(`Bạn có chắc chắn muốn thay đổi trạng thái của ${checkedBoxes.length} phòng?`)) {
        const formData = new FormData();
        formData.append('action', 'bulk_update_status');
        formData.append('status', status);
        checkedBoxes.forEach(cb => formData.append('room_ids[]', cb.value));
        submitFormAjax(formData);
    }
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    document.querySelectorAll('input[name="selected_rooms[]"]').forEach(cb => {
        cb.checked = selectAll.checked;
    });
}

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (!preview) return;
    preview.innerHTML = '';
    if (input.files?.[0]) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(input.files[0]);
        img.alt = 'Ảnh xem trước';
        img.style.maxWidth = '100px';
        img.style.maxHeight = '100px';
        preview.appendChild(img);
    }
}

window.onclick = event => {
    document.querySelectorAll('.modal').forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
            if (modal.id === 'addRoomTypeModal') clearRoomTypeImagePreviews();
        }
    });
};