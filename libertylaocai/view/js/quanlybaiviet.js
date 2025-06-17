let currentPage = {};
let itemsPerPage = 12;
let allPosts = {};

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

document.addEventListener("DOMContentLoaded", function () {
  // Khởi tạo CKEditor
  ClassicEditor.create(document.querySelector("#post-content-vi"), {
    ckfinder: {
      uploadUrl:
        "/libertylaocai/model/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json",
    },
  })
    .then((editor) => {
      editorVi = editor;
    })
    .catch((error) => {
      console.error("Lỗi khởi tạo CKEditor tiếng Việt:", error);
    });

  ClassicEditor.create(document.querySelector("#post-content-en"), {
    ckfinder: {
      uploadUrl:
        "/libertylaocai/model/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json",
    },
  })
    .then((editor) => {
      editorEn = editor;
    })
    .catch((error) => {
      console.error("Lỗi khởi tạo CKEditor tiếng Anh:", error);
    });

  // Xử lý form submit bằng AJAX
  document.getElementById("post-form").addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const type = document.getElementById("post-type").value;
    const postId = document.getElementById("post-id").value;
    const primaryImage = document.getElementById("primary-image");

    if (!postId && !primaryImage.files.length) {
      alert("Vui lòng chọn ảnh đại diện cho bài viết mới!");
      return;
    }

    console.log("Submitting form - Type:", type, "PostId:", postId);
    fetch("/libertylaocai/model/config/save_post.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Lưu bài viết thành công!");
          closeModal();
          loadPosts(type, 1);
        } else {
          alert("Lỗi: " + data.message);
        }
      })
      .catch((error) => {
        console.error("Lỗi khi lưu bài viết:", error);
        alert("Đã xảy ra lỗi khi lưu bài viết.");
      });
  });

  // Xem trước ảnh
  document
    .getElementById("primary-image")
    .addEventListener("change", function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          document.getElementById("image-preview").src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    });

  openTab("news");
});

function openTab(tabName) {
  document
    .querySelectorAll(".tab-content")
    .forEach((tab) => tab.classList.remove("active"));
  document
    .querySelectorAll(".tab-button")
    .forEach((btn) => btn.classList.remove("active"));
  document.getElementById(tabName).classList.add("active");
  document
    .querySelector(`button[onclick="openTab('${tabName}')"]`)
    .classList.add("active");
  loadPosts(tabName, 1);
}

function openAddForm(type) {
  document.getElementById("modal-title").textContent = "Thêm bài viết";
  document.getElementById("post-id").value = "";
  document.getElementById("post-type").value = type;
  document.getElementById("post-title-vi").value = "";
  document.getElementById("post-title-en").value = "";
  if (editorVi) editorVi.setData("");
  if (editorEn) editorEn.setData("");
  document.getElementById("primary-image").value = "";
  document.getElementById("primary-image").setAttribute("required", "required");
  document.getElementById("image-preview").src =
    "/libertylaocai/view/img/uploads/new/place_holder.jpg";
  document.getElementById("post-modal").style.display = "block";
}

function editPost(id, type) {
  document.getElementById("modal-title").textContent = "Sửa bài viết";
  document.getElementById("post-id").value = id;
  document.getElementById("post-type").value = type;
  document.getElementById("primary-image").removeAttribute("required");
  fetch(`/libertylaocai/model/config/fetch_post.php?id=${id}&type=${type}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        document.getElementById("post-title-vi").value =
          data.post.title_vi || "";
        document.getElementById("post-title-en").value =
          data.post.title_en || "";
        if (editorVi) editorVi.setData(data.post.content_vi || "");
        if (editorEn) editorEn.setData(data.post.content_en || "");
        document.getElementById("image-preview").src = data.post.image
          ? `${data.post.image}`
          : "/libertylaocai/view/img/uploads/new/place_holder.jpg";
        document.getElementById("post-modal").style.display = "block";
      } else {
        alert("Lỗi: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Lỗi khi lấy dữ liệu bài viết:", error);
      alert("Đã xảy ra lỗi khi lấy dữ liệu bài viết.");
    });
}

function showPost(id, type) {
  fetch("/libertylaocai/model/config/show_post.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id: id, type: type }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Bài viết đã được hiển thị lại thành công!");
        const postCard = document.querySelector(
          `#${type}-posts .post-card[data-post-id="${id}"]`
        );
        if (postCard) postCard.remove();
        loadPosts(type, 0);
      } else {
        alert("Lỗi: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Lỗi khi hiển thị lại bài viết:", error);
      alert("Đã xảy ra lỗi khi hiển thị lại bài viết.");
    });
}

function hidePost(id, type) {
  fetch("/libertylaocai/model/config/hide_post.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id: id, type: type }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Bài viết đã được ẩn thành công!");
        const postCard = document.querySelector(
          `#${type}-posts .post-card[data-post-id="${id}"]`
        );
        if (postCard) postCard.remove();
        loadPosts(type, 1);
      } else {
        alert("Lỗi: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Lỗi khi ẩn bài viết:", error);
      alert("Đã xảy ra lỗi khi ẩn bài viết.");
    });
}

function deletePost(id, type) {
  if (confirm("Bạn có chắc muốn xóa bài viết này?")) {
    fetch("/libertylaocai/model/config/delete_post.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id: id, type: type }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Bài viết đã được xóa thành công!");
          const postCard = document.querySelector(
            `#${type}-posts .post-card[data-post-id="${id}"]`
          );
          if (postCard) postCard.remove();
          const btn = document.querySelector(`#${type} .toggle-hidden-btn`);
          const active = btn ? (btn.dataset.view === "visible" ? 1 : 0) : 1;
          loadPosts(type, active);
        } else {
          alert("Lỗi: " + data.message);
        }
      })
      .catch((error) => {
        console.error("Lỗi khi xóa bài viết:", error);
        alert("Đã xảy ra lỗi khi xóa bài viết.");
      });
  }
}

function searchPosts(type) {
  const searchValue =
    document.getElementById(`search-${type}`)?.value.toLowerCase() || "";
  document.querySelectorAll(`#${type}-posts .post-card`).forEach((card) => {
    const title = card.querySelector("h3").textContent.toLowerCase();
    card.style.display = title.includes(searchValue) ? "block" : "none";
  });
}

function loadPosts(type, active, page = 1) {
  const btn = document.querySelector(`#${type} .toggle-hidden-btn`);
  if (!btn) {
    console.warn(`Không tìm thấy toggle-hidden-btn cho type: ${type}`);
    return;
  }

  console.log(
    `Loading posts for type: ${type}, active: ${active}, page: ${page}`
  );
  fetch(
    `/libertylaocai/model/config/fetch_posts.php?type=${type}&active=${active}&language=1`
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        allPosts[type] = data.posts;
        currentPage[type] = page;
        displayPosts(type, active);
      } else {
        const postList = document.getElementById(`${type}-posts`);
        if (postList)
          postList.innerHTML = "<p>Không tìm thấy bài viết nào.</p>";
      }
    })
    .catch((error) => {
      console.error("Lỗi khi tải danh sách bài viết:", error);
      const postList = document.getElementById(`${type}-posts`);
      if (postList)
        postList.innerHTML = "<p>Đã xảy ra lỗi khi tải danh sách bài viết.</p>";
    });
}

function displayPosts(type, active) {
  const postList = document.getElementById(`${type}-posts`);
  const pagination = document.getElementById(`pagination-${type}`);
  if (!postList || !pagination) {
    console.warn(`Phần tử không tồn tại cho type: ${type}`);
    return;
  }

  postList.innerHTML = "";

  const start = (currentPage[type] - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  const paginatedPosts = allPosts[type].slice(start, end);

  if (paginatedPosts.length > 0) {
    paginatedPosts.forEach((post) => {
      const content = post.content || "";
      const postCard = `
                <div class="post-card" data-post-id="${post.id}">
                    <div class="post-image-container">
                        <img class="post-image" src="${
                          post.image || "uploads/new/place_holder.jpg"
                        }" alt="${post.title}">
                        <p class="post-date">${post.date || "10/06/2025"}</p>
                    </div>
                    <h3>${post.title}</h3>
                    <p>${content.substring(0, 100)}${
        content.length > 100 ? "..." : ""
      }</p>
                    <div class="post-actions">
                        ${
                          active === 0
                            ? `<button class="action-btn show-btn" onclick="showPost(${post.id}, '${type}')"><i class="fas fa-eye"></i> Hiển thị lại</button>`
                            : `<button class="action-btn hide-btn" onclick="hidePost(${post.id}, '${type}')"><i class="fas fa-eye-slash"></i> Ẩn</button>`
                        }
                        <button class="action-btn edit-btn" onclick="editPost(${
                          post.id
                        }, '${type}')"><i class="fas fa-edit"></i> Chỉnh sửa</button>
                        <button class="action-btn delete-btn" onclick="deletePost(${
                          post.id
                        }, '${type}')"><i class="fas fa-trash"></i> Xóa</button>
                    </div>
                </div>
            `;
      postList.insertAdjacentHTML("beforeend", postCard);
    });
  } else {
    postList.innerHTML = "<p>Không tìm thấy bài viết nào.</p>";
  }

  const totalPages = Math.ceil(allPosts[type].length / itemsPerPage);
  pagination.innerHTML = "";

  if (totalPages <= 5) {
    for (let i = 1; i <= totalPages; i++) {
      const button = document.createElement("button");
      button.textContent = i;
      button.className = `pagination-btn ${
        i === currentPage[type] ? "active" : ""
      }`;
      button.onclick = () => loadPosts(type, active, i);
      pagination.appendChild(button);
    }
  } else {
    const maxButtons = 5;
    const half = Math.floor(maxButtons / 2);
    let startPage = Math.max(1, currentPage[type] - half);
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);

    if (endPage - startPage + 1 < maxButtons) {
      startPage = Math.max(1, endPage - maxButtons + 1);
    }

    if (startPage > 2) {
      const button1 = document.createElement("button");
      button1.textContent = 1;
      button1.className = `pagination-btn ${
        1 === currentPage[type] ? "active" : ""
      }`;
      button1.onclick = () => loadPosts(type, active, 1);
      pagination.appendChild(button1);

      if (startPage > 3) {
        const ellipsis = document.createElement("span");
        ellipsis.textContent = "...";
        ellipsis.className = "pagination-ellipsis";
        pagination.appendChild(ellipsis);
      }
    }

    for (let i = startPage; i <= endPage; i++) {
      const button = document.createElement("button");
      button.textContent = i;
      button.className = `pagination-btn ${
        i === currentPage[type] ? "active" : ""
      }`;
      button.onclick = () => loadPosts(type, active, i);
      pagination.appendChild(button);
    }

    if (endPage < totalPages - 1) {
      if (endPage < totalPages - 2) {
        const ellipsis = document.createElement("span");
        ellipsis.textContent = "...";
        ellipsis.className = "pagination-ellipsis";
        pagination.appendChild(ellipsis);
      }
      const buttonLast = document.createElement("button");
      buttonLast.textContent = totalPages;
      buttonLast.className = `pagination-btn ${
        totalPages === currentPage[type] ? "active" : ""
      }`;
      buttonLast.onclick = () => loadPosts(type, active, totalPages);
      pagination.appendChild(buttonLast);
    }
  }
}

function toggleHiddenPosts(type) {
  const btn = document.querySelector(`#${type} .toggle-hidden-btn`);
  if (!btn) {
    console.warn(`Không tìm thấy toggle-hidden-btn cho type: ${type}`);
    return;
  }

  const isHiddenView = btn.dataset.view === "hidden";
  const active = isHiddenView ? 1 : 0;
  btn.innerHTML = active
    ? '<i class="fas fa-eye-slash"></i> Xem bài viết đã ẩn'
    : '<i class="fas fa-eye"></i> Xem bài viết hiển thị';
  btn.dataset.view = active ? "visible" : "hidden";
  loadPosts(type, active, 1);
}

function closeModal() {
  document.getElementById("post-modal").style.display = "none";
}
