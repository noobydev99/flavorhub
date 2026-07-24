// ─── FlavorHub Shopping Cart Page Logic ───────────────────────

document.addEventListener("DOMContentLoaded", () => {
  renderCart();

  // Listen for cart update events
  window.addEventListener("cartUpdated", renderCart);
});

// Render the cart layout
function renderCart() {
  const cart = window.FlavorHubAPI.getCart();

  const activeCartWrap = document.getElementById("activeCartWrapper");
  const emptyCartWrap = document.getElementById("emptyCartWrapper");

  if (!activeCartWrap || !emptyCartWrap) return;

  if (cart.length === 0) {
    activeCartWrap.style.display = "none";
    emptyCartWrap.style.display = "block";
    return;
  }

  activeCartWrap.style.display = "grid";
  emptyCartWrap.style.display = "none";

  renderCartItemsList(cart);
  renderCartSummary(cart);
}

function renderCartItemsList(cart) {
  const container = document.getElementById("cartItemsListContainer");
  if (!container) return;

  container.innerHTML = "";

  cart.forEach(item => {
    const row = document.createElement("div");
    row.className = "cart-item-row";
    row.innerHTML = `
      <div class="cart-item-img">
        <img src="${item.image}" alt="${item.name}">
      </div>
      <div class="cart-item-details">
        <h3 class="cart-item-name">${item.name}</h3>
        <span class="cart-item-price-unit">LKR ${item.price.toFixed(2)} each</span>
      </div>
      <div class="cart-item-action-qty">
        <div class="qty-counter-widget" style="scale: 0.85;">
          <button type="button" class="btn-cart-minus" data-id="${item.id}"><i class="fas fa-minus"></i></button>
          <span>${item.quantity}</span>
          <button type="button" class="btn-cart-plus" data-id="${item.id}"><i class="fas fa-plus"></i></button>
        </div>
        <span class="cart-item-total-price">LKR ${(item.price * item.quantity).toFixed(2)}</span>
      </div>
      <button type="button" class="btn-remove-cart-item" data-id="${item.id}" title="Remove Item">
        <i class="fas fa-trash-alt"></i>
      </button>
    `;

    // Bind item action listeners
    row.querySelector(".btn-cart-minus").addEventListener("click", (e) => {
      e.stopPropagation();
      window.FlavorHubAPI.updateCartQty(item.id, item.quantity - 1);
    });

    row.querySelector(".btn-cart-plus").addEventListener("click", (e) => {
      e.stopPropagation();
      window.FlavorHubAPI.updateCartQty(item.id, item.quantity + 1);
    });

    row.querySelector(".btn-remove-cart-item").addEventListener("click", (e) => {
      e.stopPropagation();
      window.FlavorHubAPI.removeFromCart(item.id);
      window.showToast(`Removed ${item.name} from Cart`, "info");
    });

    container.appendChild(row);
  });
}

function renderCartSummary(cart) {
  const subtotalVal = document.getElementById("summarySubtotalVal");
  const taxVal = document.getElementById("summaryTaxVal");
  const totalVal = document.getElementById("summaryTotalVal");
  const btnCheckout = document.getElementById("btnCheckoutProceed");

  if (!subtotalVal || !totalVal || !btnCheckout) return;

  const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
  const delivery = 300;
  const total = subtotal + delivery;

  subtotalVal.textContent = `LKR ${subtotal.toFixed(2)}`;
  if (taxVal) taxVal.parentElement.style.display = 'none'; // Hide tax row visually
  totalVal.textContent = `LKR ${total.toFixed(2)}`;

  // Bind checkout proceed button click
  btnCheckout.onclick = (e) => {
    e.preventDefault();
    const user = window.FlavorHubAPI.getCurrentUser();
    if (user) {
      window.location.href = "checkout.html";
    } else {
      window.showToast("Please log in to proceed to checkout.", "info");
      setTimeout(() => {
        window.location.href = "login.html?redirect=checkout.html";
      }, 1000);
    }
  };
}

