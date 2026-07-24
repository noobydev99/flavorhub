// ─── FlavorHub Menu Page Interaction Logic ───────────────────

document.addEventListener("DOMContentLoaded", async () => {
  // Read category search query from URL (e.g., menu.html?category=pizza)
  const urlParams = new URLSearchParams(window.location.search);
  const categoryParam = urlParams.get("category");

  let activeCategory = categoryParam || "";
  let searchKeyword = "";

  // Load and update category buttons from admin
  await loadCategoryButtons();

  // Highlight category button if pre-selected
  if (activeCategory) {
    const btn = document.querySelector(`.category-tag-btn[data-category="${activeCategory}"]`);
    if (btn) {
      document.querySelectorAll(".category-tag-btn").forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
    }
  }

  // Load and render menu catalog
  await loadMenu(activeCategory, searchKeyword);

  // Setup Category Tags Filter click listeners
  const categoryBtns = document.querySelectorAll(".category-tag-btn");
  categoryBtns.forEach(btn => {
    btn.addEventListener("click", async function () {
      categoryBtns.forEach(b => b.classList.remove("active"));
      this.classList.add("active");

      activeCategory = this.dataset.category || "";

      // Update browser URL query parameter without reloading
      const newUrl = activeCategory
        ? `${window.location.pathname}?category=${activeCategory}`
        : window.location.pathname;
      window.history.pushState({ path: newUrl }, '', newUrl);

      await loadMenu(activeCategory, searchKeyword);
    });
  });

  // Setup Search Input triggers
  const searchInput = document.getElementById("menuSearchInput");
  const searchForm = document.getElementById("menuSearchForm");

  if (searchForm && searchInput) {
    searchForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      searchKeyword = searchInput.value.trim().toLowerCase();
      await loadMenu(activeCategory, searchKeyword);
    });

    searchInput.addEventListener("input", async function () {
      searchKeyword = this.value.trim().toLowerCase();
      // Simple debounce or instant search
      await loadMenu(activeCategory, searchKeyword);
    });
  }
});

// Load category buttons from admin categories
async function loadCategoryButtons() {
  try {
    const categories = await window.FlavorHubAPI.getCategories();
    const filterContainer = document.querySelector(".menu-category-filters");

    if (!filterContainer) return;

    // Keep the "All Menu" button and add admin categories
    const allMenuBtn = filterContainer.querySelector(".category-tag-btn[data-category='']");
    let categoryHtml = allMenuBtn.outerHTML;

    // Add dynamic category buttons from admin
    categories.forEach(cat => {
      const categoryName = cat.name.toLowerCase();
      const categoryLabel = cat.name.charAt(0).toUpperCase() + cat.name.slice(1);

      categoryHtml += `
        <button class="category-tag-btn" data-category="${categoryName}">
          <i class="fas fa-tag"></i> ${categoryLabel}
        </button>
      `;
    });

    filterContainer.innerHTML = categoryHtml;

    // Re-attach event listeners
    const categoryBtns = filterContainer.querySelectorAll(".category-tag-btn");
    categoryBtns.forEach(btn => {
      btn.addEventListener("click", async function () {
        categoryBtns.forEach(b => b.classList.remove("active"));
        this.classList.add("active");

        const category = this.dataset.category || "";
        const newUrl = category
          ? `${window.location.pathname}?category=${category}`
          : window.location.pathname;
        window.history.pushState({ path: newUrl }, '', newUrl);

        await loadMenu(category, "");
      });
    });
  } catch (e) {
    console.error("Error loading categories:", e);
  }
}

// Render the grid dynamically
async function loadMenu(category, search) {
  const container = document.getElementById("menuGridContainer");
  if (!container) return;

  try {
    // Use recipes API instead of menu API
    const menuData = await window.FlavorHubAPI.getRecipes(category);

    // Client-side search keyword filtering
    const filtered = menuData.filter(food => {
      if (search) {
        const nameMatch = food.name.toLowerCase().includes(search);
        const descMatch = food.description.toLowerCase().includes(search);
        return nameMatch || descMatch;
      }
      return true;
    });

    container.innerHTML = "";

    if (filtered.length === 0) {
      container.innerHTML = `
        <div class="no-results-box">
          <i class="fas fa-search"></i>
          <h3>No Dishes Found</h3>
          <p>We couldn't find any dishes matching your filters. Try clearing your search keyword.</p>
        </div>
      `;
      return;
    }

    filtered.forEach(food => {
      const card = document.createElement("div");
      card.className = "shared-food-card";
      card.innerHTML = `
        <div class="card-img-wrap">
          <a href="details.html?id=${food.id}">
            <img src="${food.image}" alt="${food.name}">
          </a>
          <div class="card-rating-badge">
            <i class="fas fa-star"></i> <span>${food.rating}</span>
          </div>
        </div>
        <div class="card-body-details">
          <a href="details.html?id=${food.id}">
            <h3 class="card-food-name">${food.name}</h3>
          </a>
          <p class="card-food-desc">${food.description}</p>
          <div class="card-footer-action" style="display:flex; align-items:center; gap:8px;">
            <span class="card-food-price" style="flex:1;">LKR ${food.price.toFixed(2)}</span>
            <button class="btn-buy-now-icon btn-buy-now-menu" data-id="${food.id}" title="Buy Now">
              <i class="fas fa-bolt"></i>
            </button>
            <button class="btn-card-add btn-add-to-cart-menu" data-id="${food.id}" title="Add to Cart">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
      `;

      // Bind Add to Cart event
      card.querySelector(".btn-add-to-cart-menu").addEventListener("click", function (e) {
        e.preventDefault();
        const success = window.FlavorHubAPI.addToCart(food.id, 1);
        if (success) {
          window.showToast(`Added ${food.name} to Cart!`);
        } else {
          window.showToast("Failed to add to Cart", "error");
        }
      });

      // Bind Buy Now event
      card.querySelector(".btn-buy-now-menu").addEventListener("click", function (e) {
        e.preventDefault();
        window.FlavorHubAPI.buyNow(food.id, 1);
      });

      container.appendChild(card);
    });
  } catch (e) {
    console.error("Error loading menu catalog:", e);
    container.innerHTML = `
      <div class="no-results-box">
        <i class="fas fa-exclamation-triangle"></i>
        <h3>Error Loading Catalog</h3>
        <p style="font-size:0.8rem;color:#e44;word-break:break-all;">Debug: ${e.message || e}</p>
        <p>Please check browser console (F12) for details.</p>
      </div>
    `;
  }
}

