document.addEventListener("DOMContentLoaded", () => {
  const gridBtn = document.querySelector(".toggle-btn.grid");
  const listBtn = document.querySelector(".toggle-btn.list");
  const productContainer = document.getElementById("product-container");

  if (!gridBtn || !listBtn || !productContainer) return;

  gridBtn.addEventListener("click", (e) => {
      e.preventDefault();
      gridBtn.classList.add("active");
      listBtn.classList.remove("active");
      productContainer.classList.remove("products-list");
      productContainer.classList.add("products-grid");
  });

  listBtn.addEventListener("click", (e) => {
      e.preventDefault();
      listBtn.classList.add("active");
      gridBtn.classList.remove("active");
      productContainer.classList.remove("products-grid");
      productContainer.classList.add("products-list");
  });
});