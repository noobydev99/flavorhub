// ─── FlavorHub Order Tracking Logic ───────────────────────────

const TRACKING_STEPS = [
  {
    id: "received",
    title: "Order Received",
    desc: "Your order has been confirmed and sent to our kitchen.",
    icon: "fas fa-receipt",
    progressVal: 15
  },
  {
    id: "preparing",
    title: "Preparing Your Food",
    desc: "Our chefs are carefully preparing your delicious meal.",
    icon: "fas fa-fire",
    progressVal: 45
  },
  {
    id: "out_for_delivery",
    title: "Out for Delivery",
    desc: "Your order is on the way! Our rider is heading to your address.",
    icon: "fas fa-motorcycle",
    progressVal: 75
  },
  {
    id: "delivered",
    title: "Delivered!",
    desc: "Your order has been delivered. Enjoy your meal! 🎉",
    icon: "fas fa-check-circle",
    progressVal: 100
  }
];

const STATUS_TO_STEP_INDEX = {
  "Order Received": 0,
  "Preparing": 1,
  "Out for Delivery": 2,
  "Delivered": 3
};

document.addEventListener("DOMContentLoaded", async () => {
  const urlParams = new URLSearchParams(window.location.search);
  const orderId = urlParams.get("orderId");

  if (!orderId) {
    window.location.href = "dashboard.html?tab=orders";
    return;
  }

  // Initial render
  await renderTrackingPage(orderId);

  // Poll every 30 seconds for status updates (simulating real-time)
  setInterval(async () => {
    const order = await window.FlavorHubAPI.getOrderById(orderId);
    if (order) updateTrackingUI(order);
  }, 10000);
});

async function renderTrackingPage(orderId) {
  const order = await window.FlavorHubAPI.getOrderById(orderId);

  if (!order) {
    document.querySelector(".tracking-page-section").innerHTML = `
      <div style="text-align: center; padding: 5rem 2rem; color: var(--text-muted);">
        <i class="fas fa-search fa-3x mb-3" style="opacity: 0.3;"></i>
        <h3>Order Not Found</h3>
        <p style="margin: 1rem 0 2rem;">We couldn't locate order ID: <strong>${orderId}</strong></p>
        <a href="dashboard.html?tab=orders" class="btn-orange">View My Orders</a>
      </div>
    `;
    return;
  }

  // Populate order info header
  document.getElementById("trackingOrderId").textContent = order.orderId;
  document.getElementById("trackingEstTime").textContent = `Estimated: ${order.estimatedTime}`;
  document.getElementById("trackingTotalBadge").textContent = `LKR ${order.total.toFixed(2)}`;

  // Populate order items
  const itemsList = document.getElementById("trackingItemsList");
  if (itemsList) {
    itemsList.innerHTML = "";
    order.items.forEach(item => {
      const row = document.createElement("div");
      row.className = "tracking-item-row";
      row.innerHTML = `<span>${item.name} x${item.quantity}</span><span>LKR ${(item.price * item.quantity).toFixed(2)}</span>`;
      itemsList.appendChild(row);
    });
  }

  updateTrackingUI(order);
}

function updateTrackingUI(order) {
  const currentStepIndex = STATUS_TO_STEP_INDEX[order.status] ?? 0;

  // Update progress bar
  const progressFill = document.getElementById("trackingProgressFill");
  if (progressFill) {
    progressFill.style.width = `${order.progress}%`;
  }

  // Update timeline steps
  const steps = document.querySelectorAll(".timeline-step");
  steps.forEach((step, idx) => {
    step.classList.remove("step-done", "step-active", "step-pending");
    const timeEl = step.querySelector(".timeline-step-time");

    if (idx < currentStepIndex) {
      step.classList.add("step-done");
      if (timeEl) timeEl.textContent = "✓ Done";
    } else if (idx === currentStepIndex) {
      step.classList.add("step-active");
      if (timeEl) timeEl.textContent = "In Progress...";
    } else {
      step.classList.add("step-pending");
      if (timeEl) timeEl.textContent = "Pending";
    }
  });

  // Update page title badge
  const titleStatus = document.getElementById("trackingStatusTitle");
  if (titleStatus) {
    titleStatus.textContent = order.status;
  }
}

