// let currentPage = {};
// let itemsPerPage = 12;
// let allItems = {};

// document.addEventListener("DOMContentLoaded", function () {
//   // Khởi tạo CKEditor
//   ClassicEditor.create(document.querySelector("#post-content-vi"), {
//     ckfinder: {
//       uploadUrl:
//         "/libertylaocai/model/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json",
//     },
//   })
//     .then((editor) => {
//       editorVi = editor;
//     })
//     .catch((error) => {
//       console.error("Lỗi khởi tạo CKEditor tiếng Việt:", error);
//     });

//   ClassicEditor.create(document.querySelector("#post-content-en"), {
//     ckfinder: {
//       uploadUrl:
//         "/libertylaocai/model/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json",
//     },
//   })
//     .then((editor) => {
//       editorEn = editor;
//     })
//     .catch((error) => {
//       console.error("Lỗi khởi tạo CKEditor tiếng Anh:", error);
//     });

//   // Xử lý định dạng giá khi nhập
//   document
//     .getElementById("price-input")
//     .addEventListener("input", function (e) {
//       const rawValue = getRawNumber(e.target.value);
//       if (rawValue) {
//         e.target.value = formatCurrency(rawValue);
//       } else {
//         e.target.value = "";
//       }
//     });

//   // Xử lý form submit
//   document
//     .getElementById("menu-item-form")
//     .addEventListener("submit", function (e) {
//       e.preventDefault();
//       const formData = new FormData(this);
//       const type = document.getElementById("post-type").value;
//       const postId = document.getElementById("post-id").value;
//       const primaryImage = document.getElementById("primary-image");

//       console.log("Submitting form - Type:", type, "PostId:", postId);

//       formData.append("type", type);
//       formData.append("post_id", postId);

//       if (!postId && !primaryImage.files.length) {
//         alert("Vui lòng chọn ảnh đại diện cho mục menu mới!");
//         return;
//       }

//       // Lấy giá trị số nguyên từ trường price
//       const priceInput = document.getElementById("price-input").value;
//       const rawPrice = getRawNumber(priceInput);
//       if (!rawPrice || parseInt(rawPrice) <= 0) {
//         alert("Vui lòng nhập giá hợp lệ!");
//         return;
//       }
//       formData.set("price", rawPrice); // Gửi giá trị số nguyên

//       fetch("/libertylaocai/model/config/save_menu_item.php", {
//         method: "POST",
//         body: formData,
//       })
//         .then((response) => response.json())
//         .then((data) => {
//           if (data.success) {
//             alert("Lưu mục menu thành công!");
//             closeModal();
//             loadItems(type, 1);
//           } else {
//             alert("Lỗi: " + data.message);
//           }
//         })
//         .catch((error) => {
//           console.error("Lỗi khi lưu mục menu:", error);
//           alert("Đã xảy ra lỗi khi lưu mục menu.");
//         });
//     });

//   openTab("restaurant");
// });

// Hàm định dạng giá thành tiền tệ Việt Nam
function formatCurrency(value) {
  value = value.replace(/[^0-9]/g, "");
  return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " VNĐ";
}

// Hàm lấy giá trị số nguyên từ chuỗi định dạng
function getRawNumber(formattedValue) {
  return formattedValue.replace(/[^0-9]/g, "");
}

// Hàm định dạng giá (sử dụng trong displayItems)
function formatPrice(price) {
  return formatCurrency(price);
}

// // Các hàm khác
// function openTab(tabName) {
//   document
//     .querySelectorAll(".tab-content")
//     .forEach((tab) => tab.classList.remove("active"));
//   document
//     .querySelectorAll(".tab-button")
//     .forEach((btn) => btn.classList.remove("active"));
//   document.getElementById(tabName).classList.add("active");
//   document
//     .querySelector(`button[onclick="openTab('${tabName}')"]`)
//     .classList.add("active");
//   if (tabName === "bar") {
//     openSubTab("bar_food");
//   } else {
//     loadItems("restaurant", 1);
//   }
// }

// function openSubTab(subTabName) {
//   document
//     .querySelectorAll(".sub-tab-content")
//     .forEach((subTab) => subTab.classList.remove("active"));
//   document
//     .querySelectorAll(".sub-tab-btn")
//     .forEach((btn) => btn.classList.remove("active"));
//   document.getElementById(subTabName).classList.add("active");
//   document
//     .querySelector(`button[onclick="openSubTab('${subTabName}')"]`)
//     .classList.add("active");
//   loadItems(subTabName.replace("bar_", ""), 1);
// }

// function openAddForm(type) {
//   console.log("Opening add form with type:", type);
//   document.getElementById("modal-title").textContent =
//     type === "restaurant"
//       ? "Thêm món ăn"
//       : type === "bar_food"
//       ? "Thêm đồ ăn"
//       : "Thêm đồ uống";
//   document.getElementById("post-id").value = "";
//   document.getElementById("post-type").value = type;
//   document.getElementById("item-title-vi").value = "";
//   document.getElementById("item-title-en").value = "";
//   if (editorVi) editorVi.setData("");
//   if (editorEn) editorEn.setData("");
//   document.getElementById("primary-image").value = "";
//   document.getElementById("price-input").value = "";
//   document.getElementById("primary-image").setAttribute("required", "required");
//   document.getElementById("image-preview").src =
//     "/libertylaocai/view/img/uploads/new/place_holder.jpg";
//   document.getElementById("menu-modal").style.display = "block";
// }

// function editItem(id, type) {
//   console.log("Editing item - ID:", id, "Type:", type);
//   document.getElementById("modal-title").textContent =
//     type === "restaurant"
//       ? "Sửa món ăn"
//       : type === "food"
//       ? "Sửa đồ ăn"
//       : "Sửa đồ uống";
//   document.getElementById("post-id").value = id;
//   document.getElementById("post-type").value = type;
//   document.getElementById("primary-image").removeAttribute("required");
//   fetch(`/libertylaocai/model/config/fetch_menu_item.php?id=${id}&type=${type}`)
//     .then((response) => response.json())
//     .then((data) => {
//       if (data.success) {
//         document.getElementById("item-title-vi").value =
//           data.item.title_vi || "";
//         document.getElementById("item-title-en").value =
//           data.item.title_en || "";
//         // Định dạng giá trị price từ database
//         document.getElementById("price-input").value = data.item.price
//           ? formatCurrency(data.item.price.toString())
//           : "";
//         if (editorVi) editorVi.setData(data.item.content_vi || "");
//         if (editorEn) editorEn.setData(data.item.content_en || "");
//         document.getElementById("image-preview").src = data.item.image
//           ? `/libertylaocai/view/img/${data.item.image}`
//           : "/libertylaocai/view/img/uploads/new/place_holder.jpg";
//         document.getElementById("menu-modal").style.display = "block";
//       } else {
//         alert("Lỗi: " + data.message);
//       }
//     })
//     .catch((error) => {
//       console.error("Lỗi khi tải dữ liệu mục menu:", error);
//       alert("Đã xảy ra lỗi khi tải dữ liệu mục menu.");
//     });
// }

// function showItem(id, type) {
//   console.log("Showing item - ID:", id, "Type:", type);
//   fetch("/libertylaocai/model/config/show_menu_item.php", {
//     method: "POST",
//     headers: {
//       "Content-Type": "application/json",
//     },
//     body: JSON.stringify({ id: id, type: type }),
//   })
//     .then((response) => {
//       if (!response.ok) throw new Error("Network response was not ok");
//       return response.json();
//     })
//     .then((data) => {
//       if (data.success) {
//         alert("Mục menu đã được hiển thị lại thành công!");
//         const postCard = document.querySelector(
//           `#${type}-items .post-card[data-post-id="${id}"]`
//         );
//         if (postCard) postCard.remove();
//         loadItems(type, 0);
//       } else {
//         alert("Lỗi: " + data.message);
//       }
//     })
//     .catch((error) => {
//       console.error("Lỗi khi hiển thị lại mục menu:", error);
//       alert("Đã xảy ra lỗi khi hiển thị lại mục menu.");
//     });
// }

// function hideItem(id, type) {
//   console.log("Hiding item - ID:", id, "Type:", type);
//   fetch("/libertylaocai/model/config/hide_menu_item.php", {
//     method: "POST",
//     headers: {
//       "Content-Type": "application/json",
//     },
//     body: JSON.stringify({ id: id, type: type }),
//   })
//     .then((response) => response.json())
//     .then((data) => {
//       if (data.success) {
//         alert("Mục menu đã được ẩn thành công!");
//         const postCard = document.querySelector(
//           `#${type}-items .post-card[data-post-id="${id}"]`
//         );
//         if (postCard) postCard.remove();
//         loadItems(type, 1);
//       } else {
//         alert("Lỗi: " + data.message);
//       }
//     })
//     .catch((error) => {
//       console.error("Lỗi khi ẩn mục menu:", error);
//       alert("Đã xảy ra lỗi khi ẩn mục menu.");
//     });
// }

// function deleteItem(id, type) {
//   if (confirm("Bạn có chắc muốn xóa mục menu này?")) {
//     console.log("Deleting item - ID:", id, "Type:", type);
//     fetch("/libertylaocai/model/config/delete_menu_item.php", {
//       method: "POST",
//       headers: {
//         "Content-Type": "application/json",
//       },
//       body: JSON.stringify({ id: id, type: type }),
//     })
//       .then((response) => {
//         if (!response.ok) throw new Error("Network response was not ok");
//         return response.json();
//       })
//       .then((data) => {
//         if (data.success) {
//           alert("Mục menu đã được xóa thành công!");
//           const postCard = document.querySelector(
//             `#${type}-items .post-card[data-post-id="${id}"]`
//           );
//           if (postCard) postCard.remove();
//           const btn = document.querySelector(`#${type} .toggle-hidden-btn`);
//           const active = btn ? (btn.dataset.view === "visible" ? 1 : 0) : 1;
//           loadItems(type, active);
//         } else {
//           alert("Lỗi: " + (data.message || "Không thể xóa mục menu"));
//         }
//       })
//       .catch((error) => {
//         console.error("Lỗi khi xóa mục menu:", error);
//         alert("Đã xảy ra lỗi khi xóa mục menu.");
//       });
//   }
// }

// function searchItems(type) {
//   let searchId = `search-${type}`;
//   if (type === "food" || type === "drink") {
//     searchId = `search-bar_${type}`;
//   }
//   const searchValue = document.getElementById(searchId)
//     ? document.getElementById(searchId).value.toLowerCase()
//     : "";
//   document.querySelectorAll(`#${type}-items .post-card`).forEach((card) => {
//     const title = card.querySelector("h3").textContent.toLowerCase();
//     card.style.display = title.includes(searchValue) ? "block" : "none";
//   });
// }

// // Hàm loadItems với phân trang
// function loadItems(type, active, page = 1) {
//   let btn;
//   if (type === "restaurant") {
//     btn = document.querySelector("#restaurant .toggle-hidden-btn");
//   } else if (type === "food") {
//     btn = document.querySelector("#bar_food .toggle-hidden-btn");
//   } else if (type === "drink") {
//     btn = document.querySelector("#bar_drink .toggle-hidden-btn");
//   }

//   if (!btn) {
//     console.warn(`Không tìm thấy toggle-hidden-btn cho type: ${type}`);
//     return;
//   }

//   let menuType = type;
//   if (type === "food") menuType = "main";
//   if (type === "drink") menuType = "cocktails";

//   console.log(
//     `Loading items for type: ${type}, active: ${active}, page: ${page}`
//   );
//   fetch(
//     `/libertylaocai/model/config/fetch_menu_items.php?type=${menuType}&active=${active}&language=1&id_amthuc=${
//       type === "restaurant" ? 1 : 2
//     }`
//   )
//     .then((response) => response.json())
//     .then((data) => {
//       if (data.success) {
//         allItems[type] = data.items;
//         currentPage[type] = page;
//         displayItems(type, active);
//       } else {
//         const itemList = document.getElementById(`${type}-items`);
//         if (itemList)
//           itemList.innerHTML = "<p>Không tìm thấy mục menu nào.</p>";
//       }
//     })
//     .catch((error) => {
//       console.error("Lỗi khi tải danh sách mục menu:", error);
//       const itemList = document.getElementById(`${type}-items`);
//       if (itemList)
//         itemList.innerHTML = "<p>Đã xảy ra lỗi khi tải danh sách mục menu.</p>";
//     });
// }

// // Hiển thị items theo trang
// function displayItems(type, active) {
//   const itemList = document.getElementById(`${type}-items`);
//   const pagination = document.getElementById(`pagination-${type}`);
//   if (!itemList || !pagination) {
//     console.warn(`Phần tử không tồn tại cho type: ${type}`);
//     return;
//   }

//   itemList.innerHTML = "";

//   const start = (currentPage[type] - 1) * itemsPerPage;
//   const end = start + itemsPerPage;
//   const paginatedItems = allItems[type].slice(start, end);

//   if (paginatedItems.length > 0) {
//     paginatedItems.forEach((item) => {
//       const description = item.description || "";
//       const postCard = `
//                 <div class="post-card" data-post-id="${item.id}">
//                     <div class="post-image-container">
//                         <img class="post-image" src="/libertylaocai/view/img/${
//                           item.image || "uploads/new/place_holder.jpg"
//                         }" alt="${item.title}">
//                         <p class="post-price">${
//                           item.price ? formatPrice(item.price) : "100.000 VNĐ"
//                         }</p>
//                     </div>
//                     <h3>${item.title}</h3>
//                     <p>${description.substring(0, 100)}${
//         description.length > 100 ? "..." : ""
//       }</p>
//                     <div class="post-actions">
//                         ${
//                           active === 0
//                             ? `<button class="action-btn show-btn" onclick="showItem(${item.id}, '${type}')"><i class="fas fa-eye"></i> Hiển thị lại</button>`
//                             : `<button class="action-btn hide-btn" onclick="hideItem(${item.id}, '${type}')"><i class="fas fa-eye-slash"></i> Ẩn</button>`
//                         }
//                         <button class="action-btn edit-btn" onclick="editItem(${
//                           item.id
//                         }, '${type}')"><i class="fas fa-edit"></i> Chỉnh sửa</button>
//                         <button class="action-btn delete-btn" onclick="deleteItem(${
//                           item.id
//                         }, '${type}')"><i class="fas fa-trash"></i> Xóa</button>
//                     </div>
//                 </div>
//             `;
//       itemList.insertAdjacentHTML("beforeend", postCard);
//     });
//   } else {
//     itemList.innerHTML = "<p>Không tìm thấy mục menu nào.</p>";
//   }

//   const totalPages = Math.ceil(allItems[type].length / itemsPerPage);
//   pagination.innerHTML = "";

//   if (totalPages <= 5) {
//     for (let i = 1; i <= totalPages; i++) {
//       const button = document.createElement("button");
//       button.textContent = i;
//       button.className = `pagination-btn ${
//         i === currentPage[type] ? "active" : ""
//       }`;
//       button.onclick = () => loadItems(type, active, i);
//       pagination.appendChild(button);
//     }
//   } else {
//     const maxButtons = 5;
//     const half = Math.floor(maxButtons / 2);
//     let startPage = Math.max(1, currentPage[type] - half);
//     let endPage = Math.min(totalPages, startPage + maxButtons - 1);

//     if (endPage - startPage + 1 < maxButtons) {
//       startPage = Math.max(1, endPage - maxButtons + 1);
//     }

//     if (startPage > 2) {
//       const button1 = document.createElement("button");
//       button1.textContent = 1;
//       button1.className = `pagination-btn ${
//         1 === currentPage[type] ? "active" : ""
//       }`;
//       button1.onclick = () => loadItems(type, active, 1);
//       pagination.appendChild(button1);

//       if (startPage > 3) {
//         const ellipsis = document.createElement("span");
//         ellipsis.textContent = "...";
//         ellipsis.className = "pagination-ellipsis";
//         pagination.appendChild(ellipsis);
//       }
//     }

//     for (let i = startPage; i <= endPage; i++) {
//       const button = document.createElement("button");
//       button.textContent = i;
//       button.className = `pagination-btn ${
//         i === currentPage[type] ? "active" : ""
//       }`;
//       button.onclick = () => loadItems(type, active, i);
//       pagination.appendChild(button);
//     }

//     if (endPage < totalPages - 1) {
//       if (endPage < totalPages - 2) {
//         const ellipsis = document.createElement("span");
//         ellipsis.textContent = "...";
//         ellipsis.className = "pagination-ellipsis";
//         pagination.appendChild(ellipsis);
//       }
//       const buttonLast = document.createElement("button");
//       buttonLast.textContent = totalPages;
//       buttonLast.className = `pagination-btn ${
//         totalPages === currentPage[type] ? "active" : ""
//       }`;
//       buttonLast.onclick = () => loadItems(type, active, totalPages);
//       pagination.appendChild(buttonLast);
//     }
//   }
// }

// function toggleHiddenItems(type) {
//   const btn = document.querySelector(`#${type} .toggle-hidden-btn`);
//   if (!btn) {
//     console.warn(`Không tìm thấy toggle-hidden-btn cho type: ${type}`);
//     return;
//   }

//   const isHiddenView = btn.dataset.view === "hidden";
//   const active = isHiddenView ? 1 : 0;
//   btn.innerHTML = active
//     ? '<i class="fas fa-eye-slash"></i> Xem món đã ẩn'
//     : '<i class="fas fa-eye"></i> Xem món hiển thị';
//   btn.dataset.view = active ? "visible" : "hidden";

//   loadItems(type, active, 1);
// }

// function closeModal() {
//   document.getElementById("menu-modal").style.display = "none";
// }

// document
//   .getElementById("primary-image")
//   .addEventListener("change", function (e) {
//     const file = e.target.files[0];
//     if (file) {
//       const reader = new FileReader();
//       reader.onload = function (e) {
//         document.getElementById("image-preview").src = e.target.result;
//       };
//       reader.readAsDataURL(file);
//     }
//   });

let currentPage = {};
let itemsPerPage = 12;
let allItems = {};
let editorVi, editorEn;

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

  // Xử lý định dạng giá khi nhập
  document
    .getElementById("price-input")
    .addEventListener("input", function (e) {
      const rawValue = getRawNumber(e.target.value);
      if (rawValue) {
        e.target.value = formatCurrency(rawValue);
      } else {
        e.target.value = "";
      }
    });

  // Xử lý form submit
  document
    .getElementById("menu-item-form")
    .addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      const type = document.getElementById("post-type").value;
      const postId = document.getElementById("post-id").value;
      const primaryImage = document.getElementById("primary-image");
      const isTourMenu = [
        "tour",
        "hoinghi",
        "sinhnhat",
        "gala",
        "tieccuoi",
      ].includes(type);

      console.log("Submitting form - Type:", type, "PostId:", postId);

      formData.append("type", type);
      formData.append("post_id", postId);

      if (!postId && !primaryImage.files.length) {
        alert("Vui lòng chọn ảnh đại diện cho mục menu mới!");
        return;
      }

      // Chỉ kiểm tra giá cho Nhà hàng/Bar
      if (!isTourMenu) {
        const priceInput = document.getElementById("price-input").value;
        const rawPrice = getRawNumber(priceInput);
        if (!rawPrice || parseInt(rawPrice) <= 0) {
          alert("Vui lòng nhập giá hợp lệ!");
          return;
        }
        formData.set("price", rawPrice);
      }

      const endpoint = isTourMenu
        ? "/libertylaocai/model/config/save_tour_menu_item.php"
        : "/libertylaocai/model/config/save_menu_item.php";

      fetch(endpoint, {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert("Lưu mục menu thành công!");
            closeModal();
            loadItems(type, 1);
          } else {
            alert("Lỗi: " + data.message);
          }
        })
        .catch((error) => {
          console.error("Lỗi khi lưu mục menu:", error);
          alert("Đã xảy ra lỗi khi lưu mục menu.");
        });
    });

  openTab("restaurant");
});

function formatCurrency(value) {
  value = (value != null ? value.toString() : "0").replace(/[^0-9]/g, "");
  return value ? value.replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " VNĐ" : "";
}
function formatPrice(price) {
  return price != null ? formatCurrency(price) : "";
}

function formatPrice(price) {
  return formatCurrency(price);
}

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
  if (tabName === "bar") {
    openSubTab("bar_food");
  } else if (tabName === "tour_menu") {
    openSubTab("tour");
  } else {
    loadItems("restaurant", 1);
  }
}

function openSubTab(subTabName) {
  document
    .querySelectorAll(".sub-tab-content")
    .forEach((subTab) => subTab.classList.remove("active"));
  document
    .querySelectorAll(".sub-tab-btn")
    .forEach((btn) => btn.classList.remove("active"));
  document.getElementById(subTabName).classList.add("active");
  document
    .querySelector(`button[onclick="openSubTab('${subTabName}')"]`)
    .classList.add("active");
  loadItems(subTabName.replace("bar_", ""), 1);
}

// function openAddForm(type) {
//   console.log("Opening add form with type:", type);
//   document.getElementById("modal-title").textContent =
//     type === "restaurant"
//       ? "Thêm món ăn"
//       : type === "bar_food"
//       ? "Thêm đồ ăn"
//       : type === "bar_drink"
//       ? "Thêm đồ uống"
//       : type === "tour"
//       ? "Thêm thực đơn Tour"
//       : type === "hoinghi"
//       ? "Thêm thực đơn Hội nghị"
//       : type === "sinhnhat"
//       ? "Thêm thực đơn Sinh nhật"
//       : type === "gala"
//       ? "Thêm thực đơn Gala"
//       : "Thêm thực đơn Tiệc cưới";
//   document.getElementById("post-id").value = "";
//   document.getElementById("post-type").value = type;
//   document.getElementById("item-title-vi").value = "";
//   document.getElementById("item-title-en").value = "";
//   if (editorVi) editorVi.setData("");
//   if (editorEn) editorEn.setData("");
//   document.getElementById("primary-image").value = "";
//   document.getElementById("price-input").value = "";
//   document.getElementById("primary-image").setAttribute("required", "required");
//   document.getElementById("image-preview").src =
//     "/libertylaocai/view/img/uploads/new/place_holder.jpg";

//   // Ẩn/hiện trường giá dựa trên type
//   const priceGroup = document.getElementById("price-group");
//   const priceInput = document.getElementById("price-input");
//   const isTourMenu = [
//     "tour",
//     "hoinghi",
//     "sinhnhat",
//     "gala",
//     "tieccuoi",
//   ].includes(type);
//   priceGroup.style.display = isTourMenu ? "none" : "block";
//   priceInput.required = !isTourMenu; // Yêu cầu giá cho Nhà hàng/Bar

//   document.getElementById("menu-modal").style.display = "block";
// }

// function editItem(id, type) {
//   console.log("Editing item - ID:", id, "Type:", type);
//   document.getElementById("modal-title").textContent =
//     type === "restaurant"
//       ? "Sửa món ăn"
//       : type === "food"
//       ? "Sửa đồ ăn"
//       : type === "drink"
//       ? "Sửa đồ uống"
//       : type === "tour"
//       ? "Sửa thực đơn Tour"
//       : type === "hoinghi"
//       ? "Sửa thực đơn Hội nghị"
//       : type === "sinhnhat"
//       ? "Sửa thực đơn Sinh nhật"
//       : type === "gala"
//       ? "Sửa thực đơn Gala"
//       : "Sửa thực đơn Tiệc cưới";
//   document.getElementById("post-id").value = id;
//   document.getElementById("post-type").value = type;
//   document.getElementById("primary-image").removeAttribute("required");

//   const priceGroup = document.getElementById("price-group");
//   const priceInput = document.getElementById("price-input");
//   const isTourMenu = [
//     "tour",
//     "hoinghi",
//     "sinhnhat",
//     "gala",
//     "tieccuoi",
//   ].includes(type);
//   priceGroup.style.display = isTourMenu ? "none" : "block";
//   priceInput.required = !isTourMenu;

//   const endpoint = ["tour", "hoinghi", "sinhnhat", "gala", "tieccuoi"].includes(
//     type
//   )
//     ? `/libertylaocai/model/config/fetch_tour_menu_item.php?id=${id}&type=${type}`
//     : `/libertylaocai/model/config/fetch_menu_item.php?id=${id}&type=${type}`;

//   fetch(endpoint)
//     .then((response) => response.json())
//     .then((data) => {
//       if (data.success) {
//         document.getElementById("item-title-vi").value =
//           data.item.title_vi || "";
//         document.getElementById("item-title-en").value =
//           data.item.title_en || "";
//         document.getElementById("price-input").value = data.item.price
//           ? formatCurrency(data.item.price.toString())
//           : "";
//         if (editorVi) editorVi.setData(data.item.content_vi || "");
//         if (editorEn) editorEn.setData(data.item.content_en || "");
//         document.getElementById("image-preview").src = data.item.image
//           ? `/libertylaocai/view/img/${data.item.image}`
//           : "/libertylaocai/view/img/uploads/new/place_holder.jpg";
//         document.getElementById("menu-modal").style.display = "block";
//       } else {
//         alert("Lỗi: " + data.message);
//       }
//     })
//     .catch((error) => {
//       console.error("Lỗi khi tải dữ liệu mục menu:", error);
//       alert("Đã xảy ra lỗi khi tải dữ liệu mục menu.");
//     });
// }
function openAddForm(type) {
  console.log("Opening add form with type:", type);
  document.getElementById("modal-title").textContent =
    type === "restaurant"
      ? "Thêm món ăn"
      : type === "bar_food"
      ? "Thêm đồ ăn"
      : type === "bar_drink"
      ? "Thêm đồ uống"
      : type === "tour"
      ? "Thêm thực đơn Tour"
      : type === "hoinghi"
      ? "Thêm thực đơn Hội nghị"
      : type === "sinhnhat"
      ? "Thêm thực đơn Sinh nhật"
      : type === "gala"
      ? "Thêm thực đơn Gala"
      : "Thêm thực đơn Tiệc cưới";
  document.getElementById("post-id").value = "";
  document.getElementById("post-type").value = type;
  document.getElementById("item-title-vi").value = "";
  document.getElementById("item-title-en").value = "";
  if (editorVi) editorVi.setData("");
  if (editorEn) editorEn.setData("");
  document.getElementById("primary-image").value = "";
  document.getElementById("price-input").value = "";
  document.getElementById("primary-image").setAttribute("required", "required");
  document.getElementById("image-preview").src =
    "/libertylaocai/view/img/uploads/new/place_holder.jpg";
  document.getElementById("outstanding").checked = false;

  // Ẩn/hiện trường giá và checkbox dựa trên type
  const priceGroup = document.getElementById("price-group");
  const priceInput = document.getElementById("price-input");
  const outstandingCheckbox = document.getElementById("outstanding");
  const isTourMenu = [
    "tour",
    "hoinghi",
    "sinhnhat",
    "gala",
    "tieccuoi",
  ].includes(type);
  // Kiểm tra null trước khi truy cập style
  if (priceGroup) priceGroup.style.display = isTourMenu ? "none" : "block";
  if (priceInput) priceInput.required = !isTourMenu;
  if (outstandingCheckbox)
    outstandingCheckbox.style.display = isTourMenu ? "none" : "block"; // Thêm kiểm tra null

  document.getElementById("menu-modal").style.display = "block";
}

function editItem(id, type) {
  console.log("Editing item - ID:", id, "Type:", type);
  document.getElementById("modal-title").textContent =
    type === "restaurant"
      ? "Sửa món ăn"
      : type === "food"
      ? "Sửa đồ ăn"
      : type === "drink"
      ? "Sửa đồ uống"
      : type === "tour"
      ? "Sửa thực đơn Tour"
      : type === "hoinghi"
      ? "Sửa thực đơn Hội nghị"
      : type === "sinhnhat"
      ? "Sửa thực đơn Sinh nhật"
      : type === "gala"
      ? "Sửa thực đơn Gala"
      : "Sửa thực đơn Tiệc cưới";
  document.getElementById("post-id").value = id;
  document.getElementById("post-type").value = type;
  document.getElementById("primary-image").removeAttribute("required");

  const priceGroup = document.getElementById("price-group");
  const priceInput = document.getElementById("price-input");
  const outstandingCheckbox = document.getElementById("outstanding"); // Có thể null
  const isTourMenu = [
    "tour",
    "hoinghi",
    "sinhnhat",
    "gala",
    "tieccuoi",
  ].includes(type);

  // Kiểm tra null trước khi truy cập style
  if (priceGroup) priceGroup.style.display = isTourMenu ? "none" : "block";
  if (priceInput) priceInput.required = !isTourMenu;
  if (outstandingCheckbox)
    outstandingCheckbox.style.display = isTourMenu ? "none" : "block"; // Thêm kiểm tra null

  const endpoint = ["tour", "hoinghi", "sinhnhat", "gala", "tieccuoi"].includes(
    type
  )
    ? `/libertylaocai/model/config/fetch_tour_menu_item.php?id=${id}&type=${type}`
    : `/libertylaocai/model/config/fetch_menu_item.php?id=${id}&type=${type}`;

  fetch(endpoint)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        document.getElementById("item-title-vi").value =
          data.item.title_vi || "";
        document.getElementById("item-title-en").value =
          data.item.title_en || "";
        document.getElementById("price-input").value = data.item.price
          ? formatCurrency(data.item.price.toString())
          : "";
        if (editorVi) editorVi.setData(data.item.content_vi || "");
        if (editorEn) editorEn.setData(data.item.content_en || "");
        document.getElementById("image-preview").src = data.item.image
          ? `/libertylaocai/view/img/${data.item.image}`
          : "/libertylaocai/view/img/uploads/new/place_holder.jpg";
        if (outstandingCheckbox)
          outstandingCheckbox.checked = data.item.outstanding === 1; // Thêm kiểm tra null
        document.getElementById("menu-modal").style.display = "block";
      } else {
        alert("Lỗi: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Lỗi khi tải dữ liệu mục menu:", error);
      alert("Đã xảy ra lỗi khi tải dữ liệu mục menu.");
    });
}

function showItem(id, type) {
  console.log("Showing item - ID:", id, "Type:", type);
  const endpoint = ["tour", "hoinghi", "sinhnhat", "gala", "tieccuoi"].includes(
    type
  )
    ? "/libertylaocai/model/config/show_tour_menu_item.php"
    : "/libertylaocai/model/config/show_menu_item.php";

  fetch(endpoint, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id: id, type: type }),
  })
    .then((response) => {
      if (!response.ok) throw new Error("Network response was not ok");
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        alert("Mục menu đã được hiển thị lại thành công!");
        const postCard = document.querySelector(
          `#${type}-items .post-card[data-post-id="${id}"]`
        );
        if (postCard) postCard.remove();
        loadItems(type, 0);
      } else {
        alert("Lỗi: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Lỗi khi hiển thị lại mục menu:", error);
      alert("Đã xảy ra lỗi khi hiển thị lại mục menu.");
    });
}

function hideItem(id, type) {
  console.log("Hiding item - ID:", id, "Type:", type);
  const endpoint = ["tour", "hoinghi", "sinhnhat", "gala", "tieccuoi"].includes(
    type
  )
    ? "/libertylaocai/model/config/hide_tour_menu_item.php"
    : "/libertylaocai/model/config/hide_menu_item.php";

  fetch(endpoint, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id: id, type: type }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Mục menu đã được ẩn thành công!");
        const postCard = document.querySelector(
          `#${type}-items .post-card[data-post-id="${id}"]`
        );
        if (postCard) postCard.remove();
        loadItems(type, 1);
      } else {
        alert("Lỗi: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Lỗi khi ẩn mục menu:", error);
      alert("Đã xảy ra lỗi khi ẩn mục menu.");
    });
}

function deleteItem(id, type) {
  if (confirm("Bạn có chắc muốn xóa mục menu này?")) {
    console.log("Deleting item - ID:", id, "Type:", type);
    const endpoint = [
      "tour",
      "hoinghi",
      "sinhnhat",
      "gala",
      "tieccuoi",
    ].includes(type)
      ? "/libertylaocai/model/config/delete_tour_menu_item.php"
      : "/libertylaocai/model/config/delete_menu_item.php";

    fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: id, type: type }),
    })
      .then((response) => {
        if (!response.ok) throw new Error("Network response was not ok");
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          alert("Mục menu đã được xóa thành công!");
          const postCard = document.querySelector(
            `#${type}-items .post-card[data-post-id="${id}"]`
          );
          if (postCard) postCard.remove();
          const btn = document.querySelector(`#${type} .toggle-hidden-btn`);
          const active = btn ? (btn.dataset.view === "visible" ? 1 : 0) : 1;
          loadItems(type, active);
        } else {
          alert("Lỗi: " + (data.message || "Không thể xóa mục menu"));
        }
      })
      .catch((error) => {
        console.error("Lỗi khi xóa mục menu:", error);
        alert("Đã xảy ra lỗi khi xóa mục menu.");
      });
  }
}

// function searchItems(type) {
//   let searchId = `search-${type}`;
//   if (
//     type === "food" ||
//     type === "drink" ||
//     ["tour", "hoinghi", "sinhnhat", "gala", "tieccuoi"].includes(type)
//   ) {
//     searchId = `search-bar_${type}`;
//   }
//   const searchValue = document.getElementById(searchId)
//     ? document.getElementById(searchId).value.toLowerCase()
//     : "";
//   document.querySelectorAll(`#${type}-items .post-card`).forEach((card) => {
//     const title = card.querySelector("h3").textContent.toLowerCase();
//     card.style.display = title.includes(searchValue) ? "block" : "none";
//   });
// }
function searchItems(type) {
  let searchId;
  if (type === "food" || type === "drink") {
    searchId = `search-bar_${type}`; // Sử dụng search-bar_${type} cho food và drink
  } else {
    searchId = `search-${type}`; // Sử dụng search-${type} cho các type khác
  }
  const searchValue = document.getElementById(searchId)
    ? document.getElementById(searchId).value.toLowerCase()
    : "";
  document.querySelectorAll(`#${type}-items .post-card`).forEach((card) => {
    const title = card.querySelector("h3").textContent.toLowerCase();
    card.style.display = title.includes(searchValue) ? "flex" : "none";
  });
}

function loadItems(type, active, page = 1) {
  let btn;
  if (type === "restaurant") {
    btn = document.querySelector("#restaurant .toggle-hidden-btn");
  } else if (type === "food") {
    btn = document.querySelector("#bar_food .toggle-hidden-btn");
  } else if (type === "drink") {
    btn = document.querySelector("#bar_drink .toggle-hidden-btn");
  } else if (
    ["tour", "hoinghi", "sinhnhat", "gala", "tieccuoi"].includes(type)
  ) {
    btn = document.querySelector(`#${type} .toggle-hidden-btn`);
  }

  if (!btn) {
    console.warn(`Không tìm thấy toggle-hidden-btn cho type: ${type}`);
    return;
  }

  let menuType = type;
  let id_amthuc = 1;
  if (type === "food") {
    menuType = "main";
    id_amthuc = 2;
  } else if (type === "drink") {
    menuType = "cocktails";
    id_amthuc = 2;
  }

  console.log(
    `Loading items for type: ${type}, active: ${active}, page: ${page}`
  );

  const endpoint = ["tour", "hoinghi", "sinhnhat", "gala", "tieccuoi"].includes(
    type
  )
    ? `/libertylaocai/model/config/fetch_tour_menu_items.php?type=${type}&active=${active}&language=1`
    : `/libertylaocai/model/config/fetch_menu_items.php?type=${menuType}&active=${active}&language=1&id_amthuc=${id_amthuc}`;

  fetch(endpoint)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        allItems[type] = data.items;
        currentPage[type] = page;
        displayItems(type, active);
      } else {
        const itemList = document.getElementById(`${type}-items`);
        if (itemList)
          itemList.innerHTML = "<p>Không tìm thấy mục menu nào.</p>";
      }
    })
    .catch((error) => {
      console.error("Lỗi khi tải danh sách mục menu:", error);
      const itemList = document.getElementById(`${type}-items`);
      if (itemList)
        itemList.innerHTML = "<p>Đã xảy ra lỗi khi tải danh sách mục menu.</p>";
    });
}

function displayItems(type, active) {
  const itemList = document.getElementById(`${type}-items`);
  const pagination = document.getElementById(`pagination-${type}`);
  if (!itemList || !pagination) {
    console.warn(`Phần tử không tồn tại cho type: ${type}`);
    return;
  }

  itemList.innerHTML = "";

  const start = (currentPage[type] - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  const paginatedItems = allItems[type].slice(start, end);
  const isTourMenu = [
    "tour",
    "hoinghi",
    "sinhnhat",
    "gala",
    "tieccuoi",
  ].includes(type);

  if (paginatedItems.length > 0) {
    paginatedItems.forEach((item) => {
      const rawDesc = item.description || item.content || "";
      const tempDiv = document.createElement("div");
      tempDiv.innerHTML = rawDesc;
      const description = tempDiv.textContent || tempDiv.innerText || "";
      const postCard = `
        <div class="post-card" data-post-id="${item.id}">
          <div class="post-image-container">
            <img class="post-image" src="/libertylaocai/view/img/${
              item.image || "uploads/new/place_holder.jpg"
            }" alt="${item.title}">
            ${
              !isTourMenu
                ? `<p class="post-price">${formatPrice(item.price)}</p>`
                : ""
            }
          </div>
          <h3>${item.title}</h3>
          <p>${description.substring(0, 100)}${
        description.length > 100 ? "..." : ""
      }</p>
          <div class="post-actions">
            ${
              active === 0
                ? `<button class="action-btn show-btn" onclick="showItem(${item.id}, '${type}')"><i class="fas fa-eye"></i> Hiển thị lại</button>`
                : `<button class="action-btn hide-btn" onclick="hideItem(${item.id}, '${type}')"><i class="fas fa-eye-slash"></i> Ẩn</button>`
            }
            <button class="action-btn edit-btn" onclick="editItem(${
              item.id
            }, '${type}')"><i class="fas fa-edit"></i> Chỉnh sửa</button>
            <button class="action-btn delete-btn" onclick="deleteItem(${
              item.id
            }, '${type}')"><i class="fas fa-trash"></i> Xóa</button>
          </div>
        </div>
      `;
      itemList.insertAdjacentHTML("beforeend", postCard);
    });
  } else {
    itemList.innerHTML = "<p>Không tìm thấy mục menu nào.</p>";
  }

  const totalPages = Math.ceil(allItems[type].length / itemsPerPage);
  pagination.innerHTML = "";

  if (totalPages <= 5) {
    for (let i = 1; i <= totalPages; i++) {
      const button = document.createElement("button");
      button.textContent = i;
      button.className = `pagination-btn ${
        i === currentPage[type] ? "active" : ""
      }`;
      button.onclick = () => loadItems(type, active, i);
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
      button1.onclick = () => loadItems(type, active, 1);
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
      button.onclick = () => loadItems(type, active, i);
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
      buttonLast.onclick = () => loadItems(type, active, totalPages);
      pagination.appendChild(buttonLast);
    }
  }
}

// function toggleHiddenItems(type) {
//   const btn = document.querySelector(`#${type} .toggle-hidden-btn`);
//   if (!btn) {
//     console.warn(`Không tìm thấy toggle-hidden-btn cho type: ${type}`);
//     return;
//   }

//   const isHiddenView = btn.dataset.view === "hidden";
//   const active = isHiddenView ? 1 : 0;
//   btn.innerHTML = active
//     ? '<i class="fas fa-eye-slash"></i> Xem món đã ẩn'
//     : '<i class="fas fa-eye"></i> Xem món hiển thị';
//   btn.dataset.view = active ? "visible" : "hidden";

//   loadItems(type, active, 1);
// }
function toggleHiddenItems(type) {
  let containerId = type;
  if (type === "food") {
    containerId = "bar_food";
  } else if (type === "drink") {
    containerId = "bar_drink";
  }

  const btn = document.querySelector(`#${containerId} .toggle-hidden-btn`);
  if (!btn) {
    console.warn(
      `Không tìm thấy toggle-hidden-btn cho containerId: ${containerId}`
    );
    return;
  }

  const isHiddenView = btn.dataset.view === "hidden";
  const active = isHiddenView ? 1 : 0;
  btn.innerHTML = active
    ? '<i class="fas fa-eye-slash"></i> Xem món đã ẩn'
    : '<i class="fas fa-eye"></i> Xem món hiển thị';
  btn.dataset.view = active ? "visible" : "hidden";

  loadItems(type, active, 1);
}

function closeModal() {
  document.getElementById("menu-modal").style.display = "none";
}

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
