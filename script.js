// Khởi tạo thời gian
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById("initialization-time").innerText = new Date().toLocaleTimeString();
    updateElementInfo();
  });
  
  function manipulateDOM() {
    // Thêm hiệu ứng chuyển động
    document.body.classList.add("transition-active");
    
    // 1. Thay đổi các phần tử HTML
    const header = document.getElementById("header");
    const newHeader = document.createElement("h2");
    newHeader.innerHTML = "DOM đã được thay đổi! 🚀";
    newHeader.id = "header";
    newHeader.className = "modified-style";
    newHeader.setAttribute("data-info", "modified");
    header.parentNode.replaceChild(newHeader, header);
  
    const paragraph = document.querySelector("p");
    const newParagraph = document.createElement("div");
    newParagraph.innerHTML = "Văn bản đã được chuyển thành div và các thuộc tính đã được thay đổi. Hãy kiểm tra Inspector để xem chi tiết!";
    newParagraph.className = "modified-style";
    newParagraph.setAttribute("data-info", "modified");
    paragraph.parentNode.replaceChild(newParagraph, paragraph);
  
    // 2. Thay đổi tất cả các thuộc tính HTML
    const elements = document.querySelectorAll("[data-info]");
    elements.forEach((element) => {
      element.setAttribute("data-info", "modified-" + new Date().getTime());
      element.className = "modified-style";
    });
  
    // 3. Thay đổi container
    const container = document.getElementById("container");
    container.innerHTML = "<p>Nội dung đã được thay đổi!</p>";
    container.className = "modified-style";
    
    // Thêm nội dung động
    const dynamicContent = document.createElement("div");
    dynamicContent.innerHTML = `
      <p>Nội dung mới được tạo tại: ${new Date().toLocaleTimeString()}</p>
      <p>Một số thuộc tính quan trọng trong DOM:</p>
      <ul>
        <li><strong>createElement</strong>: Tạo phần tử mới</li>
        <li><strong>appendChild</strong>: Thêm phần tử con</li>
        <li><strong>removeChild</strong>: Xóa phần tử con</li>
        <li><strong>replaceChild</strong>: Thay thế phần tử</li>
        <li><strong>setAttribute</strong>: Thiết lập thuộc tính</li>
      </ul>
    `;
    container.appendChild(dynamicContent);
  
    // 4. Thêm phần tử mới
    const newDiv = document.createElement("div");
    newDiv.id = "new-container";
    newDiv.className = "new-style";
    newDiv.innerHTML = `
      <h3>Phần tử mới đã được thêm vào</h3>
      <p>Đây là một div mới với các kiểu CSS đặc biệt. Các thay đổi đã được thực hiện lúc ${new Date().toLocaleTimeString()}</p>
      <p>Thử các nút khác để xem các tính năng bổ sung.</p>
    `;
    newDiv.setAttribute("data-custom", "new-element");
    document.querySelector(".container").appendChild(newDiv);
    
    // Cập nhật thông tin phần tử
    updateElementInfo();
  }
  
  function toggleTheme() {
    document.body.classList.toggle("dark-theme");
  }
  
  function toggleAnimation() {
    const elements = document.querySelectorAll(".modified-style, .new-style, h1, h2, h3");
    
    elements.forEach(element => {
      element.classList.toggle("animated");
    });
    
    document.getElementById("animation-container").classList.toggle("show-animation");
  }
  
  function resetPage() {
    location.reload();
  }
  
  function updateElementInfo() {
    const totalElements = document.querySelectorAll("*").length;
    const elementCount = document.getElementById("element-count");
    elementCount.textContent = totalElements;
    
    const elementInfo = document.getElementById("element-info");
    const timeElement = document.querySelector("#element-info span:last-child");
    timeElement.textContent = new Date().toLocaleTimeString();
  }