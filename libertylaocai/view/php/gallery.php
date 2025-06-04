<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sự kiện - Gallery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/libertylaocai/view/css/gallery.css">
</head>

<body>
    <?php include "header.php" ?>
    <div class="gallery-container">
        <div class="gallery-banner">
            <img src="https://thewesternhill.com/themes/thewesternhill/images/banner/breadcrumb-2.jpg" alt="Banner Image" class="banner-image">
            <h1>GALLERY</h1>
            <div class="gallery-breadcomb">
                Trang Chủ > Gallery
            </div>
        </div>

        <div class="gallery-tabs">
            <button class="tab-btn active" data-tab="all">
                <i class="fas fa-th"></i> Tất cả
            </button>
            <button class="tab-btn" data-tab="miacation">
                <i class="fas fa-map-marker-alt"></i> Miacation
            </button>
            <button class="tab-btn" data-tab="rooms">
                <i class="fas fa-bed"></i> Phòng & Suites
            </button>
            <button class="tab-btn" data-tab="facilities">
                <i class="fas fa-swimming-pool"></i> Tiện ích
            </button>
            <button class="tab-btn" data-tab="dining">
                <i class="fas fa-utensils"></i> Ẩm thực
            </button>
            <button class="tab-btn" data-tab="art">
                <i class="fas fa-palette"></i> Bộ sưu tập nghệ thuật
            </button>
            <button class="tab-btn" data-tab="videos">
                <i class="fas fa-play"></i> Video
            </button>
        </div>

        <div class="gallery-content">
            <!-- All Images Tab -->
            <div class="tab-content active" id="all">
                <div class="gallery-grid">
                    <div class="gallery-item large" data-src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-5.jpg" data-category="miacation">
                        <img src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-5.jpg" alt="Miacation Experience">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item" data-src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-1.jpg" data-category="miacation">
                        <img src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-1.jpg" alt="Boat Experience">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item" data-src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-2.jpg" data-category="miacation">
                        <img src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-2.jpg" alt="Water Activities">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item tall" data-src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-3.jpg" data-category="facilities">
                        <img src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-3.jpg" alt="Resort Facilities">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item" data-src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-4.jpg" data-category="art">
                        <img src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-4.jpg" alt="Art Collection">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item wide" data-src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800" data-category="rooms">
                        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800" alt="Luxury Suite">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item" data-src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800" data-category="rooms">
                        <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800" alt="Hotel Room">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item" data-src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800" data-category="dining">
                        <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800" alt="Fine Dining">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item" data-src="https://images.unsplash.com/photo-1544148103-0773bf10d330?w=800" data-category="facilities">
                        <img src="https://images.unsplash.com/photo-1544148103-0773bf10d330?w=800" alt="Swimming Pool">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item large" data-src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800" data-category="facilities">
                        <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800" alt="Spa Facilities">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item" data-src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800" data-category="dining">
                        <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800" alt="Restaurant">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item" data-src="https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?w=800" data-category="art">
                        <img src="https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?w=800" alt="Art Gallery">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
            </div>

            <!-- Miacation Tab -->
            <div class="tab-content" id="miacation">
                <div class="gallery-grid">
                    <div class="gallery-item large" data-src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-5.jpg" data-category="miacation">
                        <img src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-5.jpg" alt="Miacation Experience">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item" data-src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-1.jpg" data-category="miacation">
                        <img src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-1.jpg" alt="Boat Experience">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item wide" data-src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-2.jpg" data-category="miacation">
                        <img src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-2.jpg" alt="Water Activities">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
            </div>

            <!-- Videos Tab -->
            <div class="tab-content" id="videos">
                <div class="gallery-grid">
                    <div class="gallery-item video large" data-src="https://www.w3schools.com/html/mov_bbb.mp4" data-type="video" data-category="videos">
                        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800" alt="Video Thumbnail">
                        <div class="overlay"><i class="fas fa-play"></i></div>
                    </div>
                    <div class="gallery-item video" data-src="https://www.w3schools.com/html/movie.mp4" data-type="video" data-category="videos">
                        <img src="https://images.unsplash.com/photo-1544148103-0773bf10d330?w=800" alt="Video Thumbnail">
                        <div class="overlay"><i class="fas fa-play"></i></div>
                    </div>
                </div>
            </div>

            <!-- Other tabs would follow similar pattern -->
            <div class="tab-content" id="rooms">
                <div class="gallery-grid">
                    <div class="gallery-item wide" data-src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800" data-category="rooms">
                        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800" alt="Luxury Suite">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item" data-src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800" data-category="rooms">
                        <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800" alt="Hotel Room">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="facilities">
                <div class="gallery-grid">
                    <div class="gallery-item tall" data-src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-3.jpg" data-category="facilities">
                        <img src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-3.jpg" alt="Resort Facilities">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item" data-src="https://images.unsplash.com/photo-1544148103-0773bf10d330?w=800" data-category="facilities">
                        <img src="https://images.unsplash.com/photo-1544148103-0773bf10d330?w=800" alt="Swimming Pool">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item large" data-src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800" data-category="facilities">
                        <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800" alt="Spa Facilities">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="dining">
                <div class="gallery-grid">
                    <div class="gallery-item" data-src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800" data-category="dining">
                        <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800" alt="Fine Dining">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item wide" data-src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800" data-category="dining">
                        <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800" alt="Restaurant">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="art">
                <div class="gallery-grid">
                    <div class="gallery-item" data-src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-4.jpg" data-category="art">
                        <img src="https://www.miasaigon.com/wp-content/uploads/2023/04/Mia-Saigon-Miacation-Experiences-4.jpg" alt="Art Collection">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                    <div class="gallery-item tall" data-src="https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?w=800" data-category="art">
                        <img src="https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?w=800" alt="Art Gallery">
                        <div class="overlay"><i class="fas fa-search-plus"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Modal -->
    <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <button class="nav-btn prev"><i class="fas fa-chevron-left"></i></button>
        <button class="nav-btn next"><i class="fas fa-chevron-right"></i></button>

        <div class="modal-info" id="modalInfo"></div>

        <div class="modal-content">
            <div class="loading-spinner" id="loadingSpinner">
                <i class="fas fa-spinner spinner"></i>
            </div>

            <div class="image-container" id="imageContainer">
                <img id="modalImage" src="" alt="">
                <video id="modalVideo" controls style="display: none;">
                    <source src="" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>

        <div class="zoom-controls" id="zoomControls" style="display: none;">
            <button class="zoom-btn" id="zoomOut"><i class="fas fa-search-minus"></i></button>
            <div class="zoom-info" id="zoomInfo">100%</div>
            <button class="zoom-btn" id="zoomIn"><i class="fas fa-search-plus"></i></button>
            <button class="zoom-btn" id="resetZoom"><i class="fas fa-expand-arrows-alt"></i></button>
        </div>
    </div>
    <?php include "footer.php" ?>

    <script>
        // Tab functionality
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const targetTab = btn.getAttribute('data-tab');

                // Remove active class from all tabs and contents
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));

                // Add active class to clicked tab and corresponding content
                btn.classList.add('active');
                const targetContent = document.getElementById(targetTab);
                if (targetContent) {
                    targetContent.classList.add('active');
                }
            });
        });

        // Enhanced Modal functionality with zoom
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const modalVideo = document.getElementById('modalVideo');
        const modalInfo = document.getElementById('modalInfo');
        const closeBtn = document.querySelector('.close');
        const prevBtn = document.querySelector('.prev');
        const nextBtn = document.querySelector('.next');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const imageContainer = document.getElementById('imageContainer');
        const zoomControls = document.getElementById('zoomControls');
        const zoomInBtn = document.getElementById('zoomIn');
        const zoomOutBtn = document.getElementById('zoomOut');
        const resetZoomBtn = document.getElementById('resetZoom');
        const zoomInfo = document.getElementById('zoomInfo');

        let currentImages = [];
        let currentIndex = 0;
        let zoomLevel = 1;
        let minZoom = 0.5;
        let maxZoom = 3;
        let isPanning = false;
        let startX = 0;
        let startY = 0;
        let translateX = 0;
        let translateY = 0;

        // Get all gallery items
        function updateCurrentImages() {
            const activeTab = document.querySelector('.tab-content.active');
            currentImages = Array.from(activeTab.querySelectorAll('.gallery-item'));
        }

        // Reset zoom and pan
        function resetImageTransform() {
            zoomLevel = 1;
            translateX = 0;
            translateY = 0;
            updateImageTransform();
            updateZoomInfo();
        }

        // Update image transform
        function updateImageTransform() {
            if (modalImage.style.display !== 'none') {
                modalImage.style.transform = `scale(${zoomLevel}) translate(${translateX}px, ${translateY}px)`;
            }
        }

        // Update zoom info display
        function updateZoomInfo() {
            zoomInfo.textContent = Math.round(zoomLevel * 100) + '%';
        }

        // Zoom functions
        function zoomIn() {
            if (zoomLevel < maxZoom) {
                zoomLevel = Math.min(zoomLevel * 1.2, maxZoom);
                updateImageTransform();
                updateZoomInfo();
            }
        }

        function zoomOut() {
            if (zoomLevel > minZoom) {
                zoomLevel = Math.max(zoomLevel / 1.2, minZoom);
                // Reset translation if zoomed out too much
                if (zoomLevel <= 1) {
                    translateX = 0;
                    translateY = 0;
                }
                updateImageTransform();
                updateZoomInfo();
            }
        }

        // Open modal
        document.addEventListener('click', (e) => {
            if (e.target.closest('.gallery-item')) {
                const item = e.target.closest('.gallery-item');
                updateCurrentImages();
                currentIndex = currentImages.indexOf(item);
                showModalImage(currentIndex);
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        });

        // Show image in modal
        function showModalImage(index) {
            const item = currentImages[index];
            const src = item.getAttribute('data-src');
            const isVideo = item.getAttribute('data-type') === 'video';
            const alt = item.querySelector('img').getAttribute('alt');

            // Show loading spinner
            loadingSpinner.style.display = 'block';
            resetImageTransform();

            if (isVideo) {
                modalImage.style.display = 'none';
                modalVideo.style.display = 'block';
                zoomControls.style.display = 'none';

                modalVideo.querySelector('source').src = src;
                modalVideo.load();
                modalVideo.onloadeddata = () => {
                    loadingSpinner.style.display = 'none';
                };
            } else {
                modalVideo.style.display = 'none';
                modalImage.style.display = 'block';
                zoomControls.style.display = 'flex';

                modalImage.onload = () => {
                    loadingSpinner.style.display = 'none';
                };
                modalImage.src = src;
                modalImage.alt = alt;
            }

            modalInfo.textContent = `${index + 1} / ${currentImages.length} - ${alt}`;
        }

        // Navigation
        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
            showModalImage(currentIndex);
        });

        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % currentImages.length;
            showModalImage(currentIndex);
        });

        // Zoom controls
        zoomInBtn.addEventListener('click', zoomIn);
        zoomOutBtn.addEventListener('click', zoomOut);
        resetZoomBtn.addEventListener('click', resetImageTransform);

        // Mouse wheel zoom
        imageContainer.addEventListener('wheel', (e) => {
            if (modalImage.style.display !== 'none') {
                e.preventDefault();
                if (e.deltaY < 0) {
                    zoomIn();
                } else {
                    zoomOut();
                }
            }
        });

        // Touch zoom (pinch gesture)
        let initialDistance = 0;
        let initialZoom = 1;

        imageContainer.addEventListener('touchstart', (e) => {
            if (e.touches.length === 2) {
                e.preventDefault();
                initialDistance = getDistance(e.touches[0], e.touches[1]);
                initialZoom = zoomLevel;
            } else if (e.touches.length === 1 && zoomLevel > 1) {
                isPanning = true;
                startX = e.touches[0].clientX - translateX;
                startY = e.touches[0].clientY - translateY;
                imageContainer.classList.add('grabbing');
            }
        });

        imageContainer.addEventListener('touchmove', (e) => {
            if (e.touches.length === 2) {
                e.preventDefault();
                const currentDistance = getDistance(e.touches[0], e.touches[1]);
                const scale = currentDistance / initialDistance;
                zoomLevel = Math.max(minZoom, Math.min(maxZoom, initialZoom * scale));
                updateImageTransform();
                updateZoomInfo();
            } else if (e.touches.length === 1 && isPanning && zoomLevel > 1) {
                e.preventDefault();
                translateX = e.touches[0].clientX - startX;
                translateY = e.touches[0].clientY - startY;
                updateImageTransform();
            }
        });

        imageContainer.addEventListener('touchend', (e) => {
            if (e.touches.length === 0) {
                isPanning = false;
                imageContainer.classList.remove('grabbing');
            }
        });

        // Mouse pan
        imageContainer.addEventListener('mousedown', (e) => {
            if (zoomLevel > 1 && modalImage.style.display !== 'none') {
                isPanning = true;
                startX = e.clientX - translateX;
                startY = e.clientY - translateY;
                imageContainer.classList.add('grabbing');
                e.preventDefault();
            }
        });

        document.addEventListener('mousemove', (e) => {
            if (isPanning && zoomLevel > 1) {
                translateX = e.clientX - startX;
                translateY = e.clientY - startY;
                updateImageTransform();
            }
        });

        document.addEventListener('mouseup', () => {
            if (isPanning) {
                isPanning = false;
                imageContainer.classList.remove('grabbing');
            }
        });

        // Helper function for touch distance
        function getDistance(touch1, touch2) {
            const dx = touch1.clientX - touch2.clientX;
            const dy = touch1.clientY - touch2.clientY;
            return Math.sqrt(dx * dx + dy * dy);
        }

        // Double click/tap to zoom
        let lastTap = 0;
        imageContainer.addEventListener('click', (e) => {
            const currentTime = new Date().getTime();
            const tapLength = currentTime - lastTap;
            if (tapLength < 500 && tapLength > 0) {
                e.preventDefault();
                if (zoomLevel === 1) {
                    zoomLevel = 2;
                } else {
                    resetImageTransform();
                }
                updateImageTransform();
                updateZoomInfo();
            }
            lastTap = currentTime;
        });

        // Close modal
        closeBtn.addEventListener('click', closeModal);

        function closeModal() {
            modal.style.display = 'none';
            modalVideo.pause();
            document.body.style.overflow = 'auto';
            resetImageTransform();
        }

        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Keyboard navigation and controls
        document.addEventListener('keydown', (e) => {
            if (modal.style.display === 'block') {
                switch (e.key) {
                    case 'Escape':
                        closeModal();
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
                        showModalImage(currentIndex);
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        currentIndex = (currentIndex + 1) % currentImages.length;
                        showModalImage(currentIndex);
                        break;
                    case '=':
                    case '+':
                        e.preventDefault();
                        zoomIn();
                        break;
                    case '-':
                        e.preventDefault();
                        zoomOut();
                        break;
                    case '0':
                        e.preventDefault();
                        resetImageTransform();
                        break;
                    case ' ':
                        e.preventDefault();
                        if (modalVideo.style.display !== 'none') {
                            if (modalVideo.paused) {
                                modalVideo.play();
                            } else {
                                modalVideo.pause();
                            }
                        }
                        break;
                }
            }
        });

        // Prevent context menu on image
        modalImage.addEventListener('contextmenu', (e) => {
            e.preventDefault();
        });

        // Prevent image dragging
        modalImage.addEventListener('dragstart', (e) => {
            e.preventDefault();
        });
    </script>

</body>

</html>