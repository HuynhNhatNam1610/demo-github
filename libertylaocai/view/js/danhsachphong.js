// Carousel functionality
const carousels = {
    vip: { currentSlide: 0, totalSlides: 4 },
    suite: { currentSlide: 0, totalSlides: 5 },
    president: { currentSlide: 0, totalSlides: 3 }
};

// Initialize carousels
function initCarousels() {
    Object.keys(carousels).forEach(roomType => {
        createDots(roomType);
        updateCarousel(roomType);
    });
}

// Create dots for navigation
function createDots(roomType) {
    const dotsContainer = document.getElementById(`${roomType}-dots`);
    const totalSlides = carousels[roomType].totalSlides;
    
    for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('div');
        dot.className = 'carousel-dot';
        dot.onclick = () => goToSlide(roomType, i);
        dotsContainer.appendChild(dot);
    }
}

// Update carousel display
function updateCarousel(roomType) {
    const slides = document.getElementById(`${roomType}-slides`);
    const dots = document.querySelectorAll(`#${roomType}-dots .carousel-dot`);
    const counter = document.getElementById(`${roomType}-counter`);
    const currentSlide = carousels[roomType].currentSlide;
    const totalSlides = carousels[roomType].totalSlides;

    // Move slides
    slides.style.transform = `translateX(-${currentSlide * 100}%)`;

    // Update dots
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === currentSlide);
    });

    // Update counter
    counter.textContent = `${currentSlide + 1} / ${totalSlides}`;
}

// Change slide
function changeSlide(roomType, direction) {
    const carousel = carousels[roomType];
    carousel.currentSlide += direction;

    if (carousel.currentSlide < 0) {
        carousel.currentSlide = carousel.totalSlides - 1;
    } else if (carousel.currentSlide >= carousel.totalSlides) {
        carousel.currentSlide = 0;
    }

    updateCarousel(roomType);
}

// Go to specific slide
function goToSlide(roomType, slideIndex) {
    carousels[roomType].currentSlide = slideIndex;
    updateCarousel(roomType);
}

// Auto-slide functionality
function startAutoSlide() {
    setInterval(() => {
        Object.keys(carousels).forEach(roomType => {
            changeSlide(roomType, 1);
        });
    }, 5000); // Change slide every 5 seconds
}

// Price filter functionality
const minRange = document.getElementById('minRange');
const maxRange = document.getElementById('maxRange');
const minPrice = document.getElementById('minPrice');
const maxPrice = document.getElementById('maxPrice');
const priceDisplay = document.getElementById('priceDisplay');

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price) + ' đ';
}

function updatePriceDisplay() {
    const min = parseInt(minRange.value);
    const max = parseInt(maxRange.value);
    
    if (min > max) {
        minRange.value = max;
        maxRange.value = min;
    }
    
    minPrice.value = formatPrice(minRange.value);
    maxPrice.value = formatPrice(maxRange.value);
    priceDisplay.textContent = `${formatPrice(minRange.value)} - ${formatPrice(maxRange.value)}`;
    
    filterRooms();
}

minRange.addEventListener('input', updatePriceDisplay);
maxRange.addEventListener('input', updatePriceDisplay);

// Rating filter functionality
function toggleRating(rating) {
    const checkbox = document.getElementById(`rating${rating}`);
    checkbox.checked = !checkbox.checked;
    filterRooms();
}

// Filter rooms based on selected criteria
function filterRooms() {
    const minPriceValue = parseInt(minRange.value);
    const maxPriceValue = parseInt(maxRange.value);
    const selectedRatings = [];
    
    // Get selected ratings
    for (let i = 0; i <= 5; i++) {
        const checkbox = document.getElementById(`rating${i}`);
        if (checkbox && checkbox.checked) {
            selectedRatings.push(i);
        }
    }
    
    const roomBlocks = document.querySelectorAll('.room-block');
    let visibleCount = 0;
    
    roomBlocks.forEach(room => {
        const price = parseInt(room.dataset.price);
        const rating = parseInt(room.dataset.rating);
        
        let show = true;
        
        // Price filter
        if (price < minPriceValue || price > maxPriceValue) {
            show = false;
        }
        
        // Rating filter (only if ratings are selected)
        if (selectedRatings.length > 0 && !selectedRatings.includes(rating)) {
            show = false;
        }
        
        room.style.display = show ? 'flex' : 'none';
        if (show) visibleCount++;
    });
    
    // Update results count
    document.querySelector('.results-count').textContent = `Hiển thị ${visibleCount} kết quả`;
}

// Sort rooms functionality
function sortRooms() {
    const sortBy = document.getElementById('sortSelect').value;
    const roomsContainer = document.querySelector('.room-highlight-section');
    const rooms = Array.from(document.querySelectorAll('.room-block'));
    
    rooms.sort((a, b) => {
        switch (sortBy) {
            case 'price-low':
                return parseInt(a.dataset.price) - parseInt(b.dataset.price);
            case 'price-high':
                return parseInt(b.dataset.price) - parseInt(a.dataset.price);
            case 'name':
                const nameA = a.querySelector('h3').textContent;
                const nameB = b.querySelector('h3').textContent;
                return nameA.localeCompare(nameB);
            case 'size':
                const sizeA = parseInt(a.querySelector('.room-specs p').textContent.match(/\d+/)[0]);
                const sizeB = parseInt(b.querySelector('.room-specs p').textContent.match(/\d+/)[0]);
                return sizeB - sizeA;
            default:
                return 0;
        }
    });
    
    // Re-append sorted rooms
    rooms.forEach(room => roomsContainer.appendChild(room));
}

// Clear all filters
function clearAllFilters() {
    // Reset price range to default (min to max)
    minRange.value = 500000;
    maxRange.value = 3000000; // Đặt về giá trị max thay vì 1500000
    updatePriceDisplay();
    
    // Uncheck all rating checkboxes
    for (let i = 0; i <= 5; i++) {
        const checkbox = document.getElementById(`rating${i}`);
        if (checkbox) {
            checkbox.checked = false;
        }
    }
    
    // Reset sort
    document.getElementById('sortSelect').value = 'price-low';
    
    // Show all rooms
    filterRooms();
}

// Touch/swipe support for mobile
let touchStartX = 0;
let touchEndX = 0;

document.addEventListener('touchstart', e => {
    touchStartX = e.changedTouches[0].screenX;
});

document.addEventListener('touchend', e => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe(e.target);
});

function handleSwipe(target) {
    const carousel = target.closest('.room-image-box');
    if (!carousel) return;

    const roomType = carousel.closest('.room-block').querySelector('.room-info-box h3').textContent.toLowerCase().replace(' ', '');
    const swipeThreshold = 50;

    if (touchEndX < touchStartX - swipeThreshold) {
        // Swipe left - next slide
        changeSlide(getRoomTypeFromTitle(roomType), 1);
    }
    if (touchEndX > touchStartX + swipeThreshold) {
        // Swipe right - previous slide
        changeSlide(getRoomTypeFromTitle(roomType), -1);
    }
}

function getRoomTypeFromTitle(title) {
    if (title.includes('vip')) return 'vip';
    if (title.includes('suite') && title.includes('family')) return 'suite';
    if (title.includes('president')) return 'president';
    return 'vip';
}

// Initialize default price range to show all rooms
function initializePriceRange() {
    // Set default values to show full range
    minRange.value = 500000;
    maxRange.value = 3000000; // Giá trị max thay vì 1500000
    updatePriceDisplay();
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', () => {
    initCarousels();
    startAutoSlide();
    initializePriceRange(); // Khởi tạo với giá trị max
});