// ─── FlavorHub Customer Dashboard Logic ──────────────────────

document.addEventListener("DOMContentLoaded", async () => {
  const user = window.FlavorHubAPI.getCurrentUser();
  if (!user) {
    window.location.href = "login.html?redirect=dashboard.html";
    return;
  }

  // Render user profile in sidebar
  renderProfileSidebar(user);

  // Render dashboard stats
  await renderOverviewStats(user);

  // Render orders list
  await renderOrderHistory();

  // Setup Tab Navigation
  setupTabNav();

  // Activate tab from URL param
  const urlParams = new URLSearchParams(window.location.search);
  const tab = urlParams.get("tab");
  if (tab) {
    activateTab(tab);
  }

  // Pre-fill settings form
  prefillSettingsForm(user);

  // Setup settings save
  setupSettingsSave(user);
});

function renderProfileSidebar(user) {
  const avatarEl = document.getElementById("dashboardAvatar");
  const nameEl = document.getElementById("dashboardUserName");
  const emailEl = document.getElementById("dashboardUserEmail");

  if (avatarEl) avatarEl.textContent = user.fullName.charAt(0).toUpperCase();
  if (nameEl) nameEl.textContent = user.fullName;
  if (emailEl) emailEl.textContent = user.email;
}

async function renderOverviewStats(user) {
  const orders = await window.FlavorHubAPI.getOrders();
  const completedOrders = orders.filter(o => o.status === "Delivered").length;
  const totalSpent = orders.reduce((sum, o) => sum + parseFloat(o.total || 0), 0);

  const statOrders = document.getElementById("statTotalOrders");
  const statSpent = document.getElementById("statTotalSpent");
  const statMember = document.getElementById("statMemberSince");

  if (statOrders) statOrders.textContent = orders.length;
  if (statSpent) statSpent.textContent = `LKR ${totalSpent.toFixed(2)}`;
  if (statMember) statMember.textContent = "July 2026";
}

async function renderOrderHistory() {
  const container = document.getElementById("orderHistoryList");
  if (!container) return;

  const orders = await window.FlavorHubAPI.getOrders();

  if (orders.length === 0) {
    container.innerHTML = `
      <div class="empty-orders-placeholder">
        <i class="fas fa-history"></i>
        <h3>No Orders Yet</h3>
        <p>You haven't placed any orders yet. Start exploring our menu!</p>
        <a href="menu.html" class="btn-orange" style="margin-top: 1rem;">Browse Menu</a>
      </div>
    `;
    return;
  }

  container.innerHTML = "";
  orders.forEach(order => {
    const date = new Date(order.date).toLocaleDateString("en-US", { year: "numeric", month: "short", day: "numeric" });

    const statusClassMap = {
      "Order Received": "status-badge-received",
      "Preparing": "status-badge-preparing",
      "Out for Delivery": "status-badge-outfordelivery",
      "Delivered": "status-badge-delivered"
    };

    const statusClass = statusClassMap[order.status] || "status-badge-received";
    const itemsSummary = order.items.map(i => `${i.name} x${i.quantity}`).join(", ");

    const itemEl = document.createElement("div");
    itemEl.className = "order-history-item";
    itemEl.innerHTML = `
      <div>
        <p class="order-id-label"><i class="fas fa-receipt"></i> ${order.orderId}</p>
        <p class="order-date-text">${date}</p>
        <p class="order-items-summary">${itemsSummary.substring(0, 70)}${itemsSummary.length > 70 ? "..." : ""}</p>
      </div>
      <div class="order-history-right">
        <span class="order-status-badge ${statusClass}">${order.status}</span>
        <span class="order-total-val">LKR ${parseFloat(order.total).toFixed(2)}</span>
        <button class="btn-track-order" onclick="window.location.href='tracking.html?orderId=${order.orderId}'">
          <i class="fas fa-map-marker-alt"></i> Track Order
        </button>
      </div>
    `;
    container.appendChild(itemEl);
  });
}

function setupTabNav() {
  const navItems = document.querySelectorAll(".dashboard-nav-item[data-tab]");
  navItems.forEach(item => {
    item.addEventListener("click", function () {
      activateTab(this.dataset.tab);
    });
  });
}

function activateTab(tabId) {
  document.querySelectorAll(".dashboard-nav-item[data-tab]").forEach(n => n.classList.remove("active"));
  document.querySelectorAll(".dashboard-tab-panel").forEach(p => p.classList.remove("active"));

  const targetNav = document.querySelector(`.dashboard-nav-item[data-tab="${tabId}"]`);
  const targetPanel = document.getElementById(`tab-${tabId}`);

  if (targetNav) targetNav.classList.add("active");
  if (targetPanel) targetPanel.classList.add("active");
}

function prefillSettingsForm(user) {
  const nameInput = document.getElementById("settingsFullName");
  const emailInput = document.getElementById("settingsEmail");
  const phoneInput = document.getElementById("settingsPhone");
  const addressInput = document.getElementById("settingsAddress");

  if (nameInput) nameInput.value = user.fullName || "";
  if (emailInput) emailInput.value = user.email || "";
  if (phoneInput) phoneInput.value = user.phone || "";
  if (addressInput) addressInput.value = user.address || "";
}

function setupSettingsSave(user) {
  const form = document.getElementById("settingsForm");
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const updatedUser = {
      ...user,
      fullName: document.getElementById("settingsFullName").value.trim(),
      phone: document.getElementById("settingsPhone").value.trim(),
      address: document.getElementById("settingsAddress").value.trim()
    };

    window.FlavorHubAPI.updateProfile(updatedUser);
    window.showToast("Profile updated successfully!", "success");
    renderProfileSidebar(updatedUser);
  });
}

