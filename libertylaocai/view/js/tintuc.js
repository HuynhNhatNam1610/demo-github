document.addEventListener("DOMContentLoaded", function () {
  const cards = document.querySelectorAll(".promotion-card");
  const paginationNav = document.querySelector(".pagination-nav");
  const cardsPerPage = 9;
  let currentPage = 1;

  function displayCards() {
    const totalPages = Math.ceil(cards.length / cardsPerPage);
    const startIndex = (currentPage - 1) * cardsPerPage;
    const endIndex = startIndex + cardsPerPage;

    cards.forEach((card, index) => {
      card.classList.toggle("visible", index >= startIndex && index < endIndex);
    });

    // Tạo nút phân trang
    if (paginationNav) {
      paginationNav.innerHTML = "";
      for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement("button");
        button.textContent = i;
        button.classList.add("pagination-button");
        if (i === currentPage) button.classList.add("active");
        button.addEventListener("click", () => {
          currentPage = i;
          displayCards();
        });
        paginationNav.appendChild(button);
      }

      // Thêm nút Previous và Next
      const prevButton = document.createElement("button");
      prevButton.textContent = "Trước";
      prevButton.classList.add("pagination-button");
      if (currentPage === 1) prevButton.classList.add("disabled");
      prevButton.addEventListener("click", () => {
        if (currentPage > 1) {
          currentPage--;
          displayCards();
        }
      });
      paginationNav.insertBefore(prevButton, paginationNav.firstChild);

      const nextButton = document.createElement("button");
      nextButton.textContent = "Sau";
      nextButton.classList.add("pagination-button");
      if (currentPage === totalPages) nextButton.classList.add("disabled");
      nextButton.addEventListener("click", () => {
        if (currentPage < totalPages) {
          currentPage++;
          displayCards();
        }
      });
      paginationNav.appendChild(nextButton);
    }
  }

  if (cards.length > cardsPerPage) {
    displayCards();
  } else {
    cards.forEach((card) => card.classList.add("visible"));
    if (paginationNav) paginationNav.style.display = "none";
  }
});
