// ─── FlavorHub Food Details Page Logic ─────────────────────────

document.addEventListener("DOMContentLoaded", async () => {
  // 1. Read food ID parameter from URL (e.g. details.html?id=1)
  const urlParams = new URLSearchParams(window.location.search);
  const foodId = urlParams.get("id");

  if (!foodId) {
    window.location.href = "menu.html";
    return;
  }

  // 2. Fetch food information from recipes API
  const food = await window.FlavorHubAPI.getRecipeById(foodId);
  if (!food) {
    showErrorPage();
    return;
  }

  // 3. Render Food Details
  renderFoodDetails(food);

  // 4. Setup Quantity Widget Controls
  setupQuantityWidget(food);

  // 5. Load Related Recommendations
  await loadRelatedRecommendations(food);
});

// Render detailed values
function renderFoodDetails(food) {
  // Set Category Tag
  const categoryTag = document.getElementById("detailsCategoryTag");
  if (categoryTag) {
    categoryTag.textContent = food.category;
  }

  // Set Title
  const title = document.getElementById("detailsTitleName");
  if (title) {
    title.textContent = food.name;
    document.title = `FlavorHub — ${food.name}`;
  }

  // Set Rating Stars
  const ratingStars = document.getElementById("detailsRatingStars");
  if (ratingStars) {
    ratingStars.innerHTML = "";
    const fullStars = Math.floor(food.rating);
    const hasHalf = food.rating % 1 !== 0;

    for (let i = 1; i <= 5; i++) {
      if (i <= fullStars) {
        ratingStars.innerHTML += `<i class="fas fa-star"></i>`;
      } else if (i === fullStars + 1 && hasHalf) {
        ratingStars.innerHTML += `<i class="fas fa-star-half-alt"></i>`;
      } else {
        ratingStars.innerHTML += `<i class="far fa-star"></i>`;
      }
    }
  }

  // Set Review count
  const reviewsCount = document.getElementById("detailsReviewsCount");
  if (reviewsCount) {
    reviewsCount.textContent = `(${food.reviews || 45} customer reviews)`;
  }

  // Set Price
  const priceTag = document.getElementById("detailsPriceTag");
  if (priceTag) {
    priceTag.textContent = `LKR ${food.price.toFixed(2)}`;
  }

  // Set Description
  const description = document.getElementById("detailsDescription");
  if (description) {
    description.textContent = food.description;
  }

  // Set Image Gallery
  const mainImage = document.getElementById("mainFoodImage");
  if (mainImage) {
    mainImage.src = food.image;
    mainImage.alt = food.name;
  }

  // Set thumbnails
  const thumbsWrap = document.getElementById("thumbImageList");
  if (thumbsWrap) {
    thumbsWrap.innerHTML = `
      <div class="thumb-img-item active"><img src="${food.image}" alt="Thumbnail"></div>
    `;

    // Swap main image on thumbnail click
    const thumbs = thumbsWrap.querySelectorAll(".thumb-img-item");
    thumbs.forEach(t => {
      t.addEventListener("click", function () {
        thumbs.forEach(b => b.classList.remove("active"));
        this.classList.add("active");
        mainImage.src = this.querySelector("img").src;
      });
    });
  }

  // Set Ingredients List
  const ingredientsContainer = document.getElementById("detailsIngredientsList");
  if (ingredientsContainer && food.ingredients) {
    ingredientsContainer.innerHTML = "";
    const items = food.ingredients.split(",");
    items.forEach(item => {
      const pill = document.createElement("span");
      pill.className = "ingredient-pill";
      pill.textContent = item.trim();
      ingredientsContainer.appendChild(pill);
    });
  }
}

// Quantity adjust buttons & cart action
function setupQuantityWidget(food) {
  const btnMinus = document.getElementById("btnQtyMinus");
  const btnPlus = document.getElementById("btnQtyPlus");
  const textVal = document.getElementById("qtyTextVal");
  const btnAddToCart = document.getElementById("btnAddToCartDetails");
  const btnBuyNow = document.getElementById("btnBuyNowDetails");

  if (!btnMinus || !btnPlus || !textVal || !btnAddToCart) return;

  let qty = 1;

  btnMinus.addEventListener("click", () => {
    if (qty > 1) {
      qty--;
      textVal.textContent = qty;
    }
  });

  btnPlus.addEventListener("click", () => {
    qty++;
    textVal.textContent = qty;
  });

  btnAddToCart.addEventListener("click", () => {
    const success = window.FlavorHubAPI.addToCart(food.id, qty);
    if (success) {
      window.showToast(`Added ${qty}x ${food.name} to Cart!`);
      // Reset count to 1
      qty = 1;
      textVal.textContent = qty;
    } else {
      window.showToast("Failed to add to Cart", "error");
    }
  });

  if (btnBuyNow) {
    btnBuyNow.addEventListener("click", () => {
      window.FlavorHubAPI.buyNow(food.id, qty);
    });
  }
}

// Load Recommendations inside category
async function loadRelatedRecommendations(currentFood) {
  const container = document.getElementById("relatedDishesContainer");
  if (!container) return;

  try {
    // Fetch related items from the same category using recipes API
    const allMenu = await window.FlavorHubAPI.getRecipes(currentFood.category);

    // Filter out the current item
    const related = allMenu
      .filter(item => item.id !== currentFood.id)
      .slice(0, 4);

    container.innerHTML = "";

    if (related.length === 0) {
      // Fallback: load general catalog
      const fallbackMenu = await window.FlavorHubAPI.getRecipes();
      const fallbackList = fallbackMenu.filter(item => item.id !== currentFood.id).slice(0, 4);
      renderRelatedDishes(fallbackList, container);
      return;
    }

    renderRelatedDishes(related, container);
  } catch (e) {
    console.error("Error loading related recommendations:", e);
    container.parentNode.style.display = "none"; // Hide section
  }
}

function renderRelatedDishes(list, container) {
  list.forEach(food => {
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
          <button class="btn-buy-now-icon btn-buy-now-related" data-id="${food.id}" title="Buy Now">
            <i class="fas fa-bolt"></i>
          </button>
          <button class="btn-card-add btn-add-to-cart-related" data-id="${food.id}" title="Add to Cart">
            <i class="fas fa-plus"></i>
          </button>
        </div>
      </div>
    `;

    // Bind Add to Cart event
    card.querySelector(".btn-add-to-cart-related").addEventListener("click", function (e) {
      e.preventDefault();
      const success = window.FlavorHubAPI.addToCart(food.id, 1);
      if (success) {
        window.showToast(`Added ${food.name} to Cart!`);
      } else {
        window.showToast("Failed to add to Cart", "error");
      }
    });

    // Bind Buy Now event
    card.querySelector(".btn-buy-now-related").addEventListener("click", function (e) {
      e.preventDefault();
      window.FlavorHubAPI.buyNow(food.id, 1);
    });

    container.appendChild(card);
  });
}

function showErrorPage() {
  const mainSec = document.querySelector(".details-page-section");
  if (mainSec) {
    mainSec.innerHTML = `
      <div style="text-align: center; padding: 5rem 2rem; color: var(--text-muted);">
        <i class="fas fa-exclamation-triangle fa-3x mb-3 text-brand"></i>
        <h3>Dish Not Found</h3>
        <p class="mb-4">The page you are looking for does not exist or has been removed.</p>
        <a href="menu.html" class="btn-orange">Back to Menu</a>
      </div>
    `;
  }
}

