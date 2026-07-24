// ─── FlavorHub Home Page Interaction Logic ───────────────────

document.addEventListener("DOMContentLoaded", async () => {
  // 1. Fetch and render popular dishes (top 4 rated items)
  await loadPopularDishes();

  // 2. Newsletter Subscription handler
  setupNewsletter();
});

// Fetch and load dishes dynamically
async function loadPopularDishes() {
  const container = document.getElementById("popularDishesContainer");
  if (!container) return;

  try {
    const allMenu = await window.FlavorHubAPI.getRecipes();

    // Sort by rating desc and take top 4
    const popular = [...allMenu]
      .sort((a, b) => b.rating - a.rating)
      .slice(0, 4);

    container.innerHTML = "";

    popular.forEach(food => {
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
            <button class="btn-buy-now-icon btn-buy-now-home" data-id="${food.id}" title="Buy Now">
              <i class="fas fa-bolt"></i>
            </button>
            <button class="btn-card-add btn-add-to-cart-home" data-id="${food.id}" title="Add to Cart">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
      `;

      // Bind Add to Cart event
      card.querySelector(".btn-add-to-cart-home").addEventListener("click", function (e) {
        e.preventDefault();
        const success = window.FlavorHubAPI.addToCart(food.id, 1);
        if (success) {
          window.showToast(`Added ${food.name} to Cart!`);
        } else {
          window.showToast("Failed to add to Cart", "error");
        }
      });

      // Bind Buy Now event
      card.querySelector(".btn-buy-now-home").addEventListener("click", function (e) {
        e.preventDefault();
        window.FlavorHubAPI.buyNow(food.id, 1);
      });

      container.appendChild(card);
    });
  } catch (e) {
    console.error("Error loading popular dishes:", e);
    container.innerHTML = `
      <div style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 2rem;">
        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
        <p>Could not load popular dishes. Please refresh the page.</p>
      </div>
    `;
  }
}

// Newsletter setup
function setupNewsletter() {
  const form = document.getElementById("newsletterForm");
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const emailInput = form.querySelector("input[type='email']");
    if (!emailInput) return;

    const email = emailInput.value.trim();
    if (!email) {
      window.showToast("Please enter a valid email address.", "error");
      return;
    }

    // Simulate API request
    window.showToast("Subscription successful! Check your inbox.", "success");
    emailInput.value = "";
  });
}

