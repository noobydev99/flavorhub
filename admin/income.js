// ─── FlavorHub Admin Dashboard - Income Management JS ─────────

// 1. Mock Transaction Database (used as fallback)
let INITIAL_TRANSACTIONS = [
  {
    incomeId: "INC-1015",
    orderId: "ORD-9035",
    customerName: "John Doe",
    date: "2026-07-09",
    paymentMethod: "Online Payment",
    amount: 45.50,
    orderStatus: "Completed",
    incomeStatus: "Completed",
    items: [
      { name: "Spicy Garlic Ramen", qty: 1, price: 14.99 },
      { name: "Pork Gyoza (6pcs)", qty: 1, price: 7.99 },
      { name: "Bubble Milk Tea", qty: 2, price: 5.50 }
    ],
    tax: 3.52,
    serviceCharge: 2.00
  },
  {
    incomeId: "INC-1014",
    orderId: "ORD-9034",
    customerName: "Jane Smith",
    date: "2026-07-08",
    paymentMethod: "Card",
    amount: 68.20,
    orderStatus: "Completed",
    incomeStatus: "Completed",
    items: [
      { name: "Teriyaki Chicken Bento", qty: 2, price: 18.99 },
      { name: "Miso Soup", qty: 2, price: 3.00 },
      { name: "Premium Green Tea", qty: 2, price: 2.50 }
    ],
    tax: 5.23,
    serviceCharge: 3.50
  },
  {
    incomeId: "INC-1013",
    orderId: "ORD-9033",
    customerName: "Robert Johnson",
    date: "2026-07-09",
    paymentMethod: "Cash",
    amount: 18.50,
    orderStatus: "Completed",
    incomeStatus: "Completed",
    items: [
      { name: "California Roll (8pcs)", qty: 1, price: 8.99 },
      { name: "Shrimp Tempura (4pcs)", qty: 1, price: 6.99 }
    ],
    tax: 1.52,
    serviceCharge: 1.00
  },
  {
    incomeId: "INC-1012",
    orderId: "ORD-9032",
    customerName: "Emily Davis",
    date: "2026-07-07",
    paymentMethod: "Online Payment",
    amount: 112.40,
    orderStatus: "Pending",
    incomeStatus: "Pending",
    items: [
      { name: "Wagyu Beef Burger Set", qty: 3, price: 25.00 },
      { name: "Truffle Fries", qty: 3, price: 6.00 },
      { name: "Craft Soda", qty: 3, price: 4.00 }
    ],
    tax: 8.60,
    serviceCharge: 5.00
  },
  {
    incomeId: "INC-1011",
    orderId: "ORD-9031",
    customerName: "Michael Wilson",
    date: "2026-07-06",
    paymentMethod: "Card",
    amount: 32.00,
    orderStatus: "Completed",
    incomeStatus: "Completed",
    items: [
      { name: "Pepperoni Pizza 12\"", qty: 1, price: 18.99 },
      { name: "Classic Caesar Salad", qty: 1, price: 9.99 }
    ],
    tax: 2.22,
    serviceCharge: 0.80
  },
  {
    incomeId: "INC-1010",
    orderId: "ORD-9030",
    customerName: "Sarah Brown",
    date: "2026-07-05",
    paymentMethod: "Online Payment",
    amount: 54.10,
    orderStatus: "Cancelled",
    incomeStatus: "Cancelled",
    items: [
      { name: "Seafood Paella", qty: 1, price: 28.99 },
      { name: "Sangria Pitcher", qty: 1, price: 18.00 }
    ],
    tax: 4.11,
    serviceCharge: 3.00
  },
  {
    incomeId: "INC-1009",
    orderId: "ORD-9029",
    customerName: "David Miller",
    date: "2026-07-09",
    paymentMethod: "Card",
    amount: 24.50,
    orderStatus: "Completed",
    incomeStatus: "Completed",
    items: [
      { name: "Spaghetti Carbonara", qty: 1, price: 16.99 },
      { name: "Garlic Bread (4pcs)", qty: 1, price: 4.99 }
    ],
    tax: 1.82,
    serviceCharge: 0.70
  },
  {
    incomeId: "INC-1008",
    orderId: "ORD-9028",
    customerName: "Jessica Taylor",
    date: "2026-07-04",
    paymentMethod: "Cash",
    amount: 95.00,
    orderStatus: "Completed",
    incomeStatus: "Completed",
    items: [
      { name: "Ribeye Steak 300g", qty: 2, price: 38.00 },
      { name: "Mashed Potatoes", qty: 2, price: 5.00 },
      { name: "Red Wine Glass", qty: 2, price: 8.00 }
    ],
    tax: 7.20,
    serviceCharge: 4.80
  },
  {
    incomeId: "INC-1007",
    orderId: "ORD-9027",
    customerName: "James Anderson",
    date: "2026-07-03",
    paymentMethod: "Card",
    amount: 41.80,
    orderStatus: "Completed",
    incomeStatus: "Completed",
    items: [
      { name: "Tonkotsu Ramen Special", qty: 2, price: 15.99 },
      { name: "Edamame Beans", qty: 1, price: 4.50 }
    ],
    tax: 3.32,
    serviceCharge: 2.00
  },
  {
    incomeId: "INC-1006",
    orderId: "ORD-9026",
    customerName: "Karen Thomas",
    date: "2026-07-08",
    paymentMethod: "Online Payment",
    amount: 38.90,
    orderStatus: "Completed",
    incomeStatus: "Completed",
    items: [
      { name: "Pad Thai Chicken", qty: 2, price: 13.99 },
      { name: "Thai Iced Tea", qty: 2, price: 4.00 }
    ],
    tax: 2.92,
    serviceCharge: 2.00
  },
  {
    incomeId: "INC-1005",
    orderId: "ORD-9025",
    customerName: "Nancy White",
    date: "2026-06-25",
    paymentMethod: "Card",
    amount: 85.50,
    orderStatus: "Completed",
    incomeStatus: "Completed",
    items: [
      { name: "Sushi Combo Deluxe", qty: 2, price: 34.99 },
      { name: "Sake Bottle", qty: 1, price: 12.00 }
    ],
    tax: 6.52,
    serviceCharge: 4.00
  },
  {
    incomeId: "INC-1004",
    orderId: "ORD-9024",
    customerName: "Charles Harris",
    date: "2026-06-20",
    paymentMethod: "Cash",
    amount: 55.00,
    orderStatus: "Completed",
    incomeStatus: "Completed",
    items: [
      { name: "BBQ Pork Ribs Half-Rack", qty: 2, price: 21.99 },
      { name: "Coleslaw Salad", qty: 2, price: 3.50 }
    ],
    tax: 4.20,
    serviceCharge: 3.00
  },
  {
    incomeId: "INC-1003",
    orderId: "ORD-9023",
    customerName: "Daniel Martin",
    date: "2026-06-15",
    paymentMethod: "Online Payment",
    amount: 120.00,
    orderStatus: "Completed",
    incomeStatus: "Completed",
    items: [
      { name: "Tomahawk Pork Chop", qty: 2, price: 45.00 },
      { name: "Grilled Asparagus", qty: 2, price: 6.00 },
      { name: "IPA Beer Can", qty: 4, price: 5.50 }
    ],
    tax: 9.20,
    serviceCharge: 6.00
  },
  {
    incomeId: "INC-1002",
    orderId: "ORD-9022",
    customerName: "Matthew Thompson",
    date: "2026-05-18",
    paymentMethod: "Card",
    amount: 72.40,
    orderStatus: "Completed",
    incomeStatus: "Completed",
    items: [
      { name: "Margherita Pizza 12\"", qty: 2, price: 14.99 },
      { name: "Mozzarella Sticks", qty: 2, price: 6.99 },
      { name: "Lemonade Pitcher", qty: 1, price: 10.00 }
    ],
    tax: 5.42,
    serviceCharge: 3.00
  },
  {
    incomeId: "INC-1001",
    orderId: "ORD-9021",
    customerName: "Patricia Garcia",
    date: "2026-05-10",
    paymentMethod: "Online Payment",
    amount: 98.60,
    orderStatus: "Completed",
    incomeStatus: "Completed",
    items: [
      { name: "Chicken Alfredo Pasta", qty: 3, price: 17.99 },
      { name: "Warm Chocolate Brownie", qty: 3, price: 6.50 },
      { name: "Cappuccino Coffee", qty: 3, price: 4.50 }
    ],
    tax: 7.42,
    serviceCharge: 4.50
  }
];

let transactions = [...INITIAL_TRANSACTIONS];

// 2. Global Dashboard State
const state = {
  filters: {
    fromDate: "",
    toDate: "",
    search: "",
    paymentMethod: "",
    status: ""
  },
  sort: {
    column: "date",
    direction: "desc" // 'asc' or 'desc'
  },
  pagination: {
    currentPage: 1,
    pageSize: 5
  }
};

// 3. UI References
let incomeTableBody, paginationContainer, showingEntriesText;
let barChartInstance = null;
let pieChartInstance = null;

// 4. Initialize dashboard on DOM load
document.addEventListener("DOMContentLoaded", () => {
  // Bind UI References
  incomeTableBody = document.getElementById("incomeTableBody");
  paginationContainer = document.getElementById("paginationContainer");
  showingEntriesText = document.getElementById("showingEntriesText");

  // Load Initial Calculations & Views
  setupCharts();
  // Load first page from server with current filters
  loadServerData(1);

  // Set up Event Listeners
  setupEventListeners();
});

// 5. Setup Listeners
function setupEventListeners() {
  // Search & Filter buttons
  document.getElementById("btnSearch").addEventListener("click", () => {
    state.filters.fromDate = document.getElementById("filterFromDate").value;
    state.filters.toDate = document.getElementById("filterToDate").value;
    state.filters.search = document.getElementById("filterSearch").value.trim().toLowerCase();
    state.filters.paymentMethod = document.getElementById("filterPayment").value;
    state.filters.status = document.getElementById("filterStatus").value;

    state.pagination.currentPage = 1;
    loadServerData(1);
  });

  document.getElementById("btnReset").addEventListener("click", () => {
    // Reset Form inputs
    document.getElementById("filterFromDate").value = "";
    document.getElementById("filterToDate").value = "";
    document.getElementById("filterSearch").value = "";
    document.getElementById("filterPayment").value = "";
    document.getElementById("filterStatus").value = "";

    // Reset state filters
    state.filters = {
      fromDate: "",
      toDate: "",
      search: "",
      paymentMethod: "",
      status: ""
    };
    state.pagination.currentPage = 1;
    loadServerData(1);
  });

  // Table sorting headers
  const headers = document.querySelectorAll(".sortable-header");
  headers.forEach(header => {
    header.addEventListener("click", () => {
      const col = header.dataset.column;
      if (state.sort.column === col) {
        state.sort.direction = state.sort.direction === "asc" ? "desc" : "asc";
      } else {
        state.sort.column = col;
        state.sort.direction = "desc"; // Default to desc on new column click
      }

      // Update UI classes
      headers.forEach(h => h.classList.remove("asc", "desc"));
      header.classList.add(state.sort.direction);

      loadServerData(1);
    });
  });

  // Export actions
  document.getElementById("btnCSV").addEventListener("click", exportCSV);
  document.getElementById("btnPDF").addEventListener("click", exportPDF);
  document.getElementById("btnPrint").addEventListener("click", () => window.print());
  document.getElementById("btnRefresh").addEventListener("click", refreshData);
}

// Fetch live income records from API (falls back to INITIAL_TRANSACTIONS)
async function getApiUrl() {
  // Build an API URL relative to the current project root so it works in subfolders
  const path = window.location.pathname;
  const idx = path.indexOf('/admin/');
  if (idx !== -1) {
    const root = path.substring(0, idx);
    return `${root}/api/income.php`;
  }
  return '/api/income.php';
}

async function fetchIncomeData(params = {}) {
  try {
    const api = await getApiUrl();
    const qs = new URLSearchParams(params).toString();
    const resp = await fetch(api + (qs ? ('?' + qs) : ''));
    if (!resp.ok) throw new Error('Network response was not ok');
    const json = await resp.json();

    // If API returned structured response with data + summary
    if (json && Array.isArray(json.data)) {
      // Update fallback/full dataset for client-side usage when needed
      INITIAL_TRANSACTIONS = json.data.map(d => ({
        incomeId: d.incomeId,
        orderId: d.orderId,
        customerName: d.customerName,
        date: d.date,
        paymentMethod: d.paymentMethod,
        amount: parseFloat(d.amount),
        orderStatus: d.orderStatus,
        incomeStatus: d.incomeStatus,
        items: d.items || [],
        tax: parseFloat(d.tax || 0),
        serviceCharge: parseFloat(d.serviceCharge || 0)
      }));

      transactions = [...INITIAL_TRANSACTIONS];

      // Render table and pagination using API-provided data
      renderTable(json.data);
      renderPagination(json.total, (params.page - 1) * (params.page_size || state.pagination.pageSize), Math.min((params.page || 1) * (params.page_size || state.pagination.pageSize), json.total));

      // Update stats and charts from summary if present
      if (json.summary) {
        renderSummary(json.summary);
      } else {
        updateStats();
      }
    }

    return true;
  } catch (err) {
    console.warn('Could not fetch live income data, using mock dataset.', err);
    return false;
  }
}

function renderSummary(summary) {
  if (!summary) return;
  animateCount('statTodayIncome', summary.todayIncome || 0, true);
  animateCount('statMonthlyIncome', summary.monthlyIncome || 0, true);
  animateCount('statTotalIncome', summary.totalIncome || 0, true);
  animateCount('statCompletedOrders', summary.completedOrders || 0, false);

  // Update bar chart (monthly sums)
  if (barChartInstance) {
    const months = Object.keys(summary.monthlySums || {});
    const vals = months.map(m => summary.monthlySums[m]);
    barChartInstance.data.labels = months.length ? months : ['May', 'Jun', 'Jul'];
    barChartInstance.data.datasets[0].data = vals.length ? vals : [0, 0, 0];
    barChartInstance.update();
  }

  // Update pie chart (payment totals)
  if (pieChartInstance) {
    const pt = summary.paymentTotals || { Cash: 0, Card: 0, 'Online Payment': 0 };
    pieChartInstance.data.datasets[0].data = [pt.Cash || 0, pt.Card || 0, pt['Online Payment'] || 0];
    pieChartInstance.update();
  }
}

// Load page `page` from server with current filters/sort
function loadServerData(page = 1) {
  state.pagination.currentPage = page;
  const params = {
    page: page,
    page_size: state.pagination.pageSize,
    sort: state.sort.column,
    dir: state.sort.direction,
    from_date: state.filters.fromDate,
    to_date: state.filters.toDate,
    payment_method: state.filters.paymentMethod,
    status: state.filters.status,
    search: state.filters.search
  };

  // Show loading overlay if present
  const overlay = document.getElementById('loadingOverlay');
  if (overlay) overlay.classList.add('active');

  fetchIncomeData(params).then(success => {
    if (!success) {
      // fallback to client-side rendering
      applyFiltersAndRender();
    }
    if (overlay) overlay.classList.remove('active');
  }).catch(() => {
    if (overlay) overlay.classList.remove('active');
    applyFiltersAndRender();
  });
}

// 6. Stats calculation (Top 4 Cards)
function updateStats() {
  const today = "2026-07-09";
  const currentMonthPrefix = "2026-07";

  // Calculate Today's Income
  const todayIncome = INITIAL_TRANSACTIONS
    .filter(t => t.date === today && t.incomeStatus === "Completed")
    .reduce((sum, t) => sum + t.amount, 0);

  // Calculate Monthly Income (July 2026)
  const monthlyIncome = INITIAL_TRANSACTIONS
    .filter(t => t.date.startsWith(currentMonthPrefix) && t.incomeStatus === "Completed")
    .reduce((sum, t) => sum + t.amount, 0);

  // Calculate Total Income (All time completed)
  const totalIncome = INITIAL_TRANSACTIONS
    .filter(t => t.incomeStatus === "Completed")
    .reduce((sum, t) => sum + t.amount, 0);

  // Calculate Completed Orders Count
  const completedOrders = INITIAL_TRANSACTIONS
    .filter(t => t.orderStatus === "Completed")
    .length;

  // Render to DOM with animation
  animateCount("statTodayIncome", todayIncome, true);
  animateCount("statMonthlyIncome", monthlyIncome, true);
  animateCount("statTotalIncome", totalIncome, true);
  animateCount("statCompletedOrders", completedOrders, false);
}

// Animate numbers for rich UI effect
function animateCount(elementId, targetValue, isCurrency) {
  const el = document.getElementById(elementId);
  if (!el) return;

  let start = 0;
  const duration = 800; // ms
  const stepTime = 15;
  const steps = Math.ceil(duration / stepTime);
  const increment = targetValue / steps;
  let step = 0;

  const timer = setInterval(() => {
    step++;
    start += increment;
    if (step >= steps) {
      clearInterval(timer);
      start = targetValue;
    }
    el.textContent = isCurrency
      ? "LKR " + start.toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 })
      : Math.floor(start).toLocaleString("en-US");
  }, stepTime);
}

// 7. Filtering, Sorting, and Paginated Rendering
function applyFiltersAndRender() {
  // Filter
  let filtered = INITIAL_TRANSACTIONS.filter(t => {
    // Search keyword
    if (state.filters.search) {
      const query = state.filters.search;
      const matchName = t.customerName.toLowerCase().includes(query);
      const matchOrder = t.orderId.toLowerCase().includes(query);
      const matchInc = t.incomeId.toLowerCase().includes(query);
      if (!matchName && !matchOrder && !matchInc) return false;
    }
    // From date
    if (state.filters.fromDate && t.date < state.filters.fromDate) return false;
    // To date
    if (state.filters.toDate && t.date > state.filters.toDate) return false;
    // Payment Method
    if (state.filters.paymentMethod && t.paymentMethod !== state.filters.paymentMethod) return false;
    // Status
    if (state.filters.status && t.incomeStatus !== state.filters.status) return false;

    return true;
  });

  // Sort
  filtered.sort((a, b) => {
    let valA = a[state.sort.column];
    let valB = b[state.sort.column];

    if (typeof valA === "string") {
      return state.sort.direction === "asc"
        ? valA.localeCompare(valB)
        : valB.localeCompare(valA);
    } else {
      return state.sort.direction === "asc"
        ? valA - valB
        : valB - valA;
    }
  });

  // Update transaction reference for exports
  transactions = filtered;

  // Pagination Math
  const total = filtered.length;
  const startIdx = (state.pagination.currentPage - 1) * state.pagination.pageSize;
  const endIdx = Math.min(startIdx + state.pagination.pageSize, total);
  const paginated = filtered.slice(startIdx, endIdx);

  // Render Table
  renderTable(paginated);

  // Render Pagination controls
  renderPagination(total, startIdx, endIdx);

  // Update Charts dynamically with the filtered data set
  updateChartsData(filtered);
}

function renderTable(data) {
  incomeTableBody.innerHTML = "";

  if (data.length === 0) {
    incomeTableBody.innerHTML = `
      <tr>
        <td colspan="9" class="text-center py-5 text-secondary">
          <i class="fas fa-receipt fa-2x mb-3 text-muted"></i>
          <p class="mb-0 fw-medium">No matching transactions found.</p>
        </td>
      </tr>
    `;
    return;
  }

  data.forEach(t => {
    // Status Badge classes
    let statusClass = "status-completed";
    if (t.incomeStatus === "Pending") statusClass = "status-pending";
    if (t.incomeStatus === "Cancelled") statusClass = "status-cancelled";

    let orderStatusClass = "status-completed";
    if (t.orderStatus === "Pending") orderStatusClass = "status-pending";
    if (t.orderStatus === "Cancelled") orderStatusClass = "status-cancelled";

    // Payment Badge classes
    let payClass = "pay-cash";
    let payIcon = "fa-money-bill-wave";
    if (t.paymentMethod === "Card") {
      payClass = "pay-card";
      payIcon = "fa-credit-card";
    } else if (t.paymentMethod === "Online Payment") {
      payClass = "pay-online";
      payIcon = "fa-globe";
    }

    const tr = document.createElement("tr");
    tr.className = "align-middle";
    tr.innerHTML = `
      <td class="fw-bold text-slate-800">${t.incomeId}</td>
      <td class="text-secondary small">${t.orderId}</td>
      <td>
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center text-slate-700 fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
            ${t.customerName.charAt(0)}
          </div>
          <span class="fw-medium text-slate-900">${t.customerName}</span>
        </div>
      </td>
      <td class="text-secondary small">${formatDate(t.date)}</td>
      <td>
        <span class="badge-pay ${payClass}">
          <i class="fas ${payIcon} me-1"></i> ${t.paymentMethod}
        </span>
      </td>
      <td class="fw-bold text-slate-800">LKR ${t.amount.toFixed(2)}</td>
      <td>
        <span class="badge-status ${orderStatusClass}">
          <span class="status-dot"></span>${t.orderStatus}
        </span>
      </td>
      <td>
        <span class="badge-status ${statusClass}">
          <span class="status-dot"></span>${t.incomeStatus}
        </span>
      </td>
      <td>
        <button class="btn btn-sm btn-brand-outline-orange py-1 px-2.5 rounded-3 btn-view-details" data-id="${t.incomeId}">
          <i class="fas fa-eye"></i> View
        </button>
      </td>
    `;

    // Bind modal details view trigger
    tr.querySelector(".btn-view-details").addEventListener("click", () => {
      showTransactionDetails(t.incomeId);
    });

    incomeTableBody.appendChild(tr);
  });
}

function renderPagination(total, startIdx, endIdx) {
  // Update footer text
  if (total === 0) {
    showingEntriesText.textContent = "Showing 0 to 0 of 0 entries";
  } else {
    showingEntriesText.textContent = `Showing ${startIdx + 1} to ${endIdx} of ${total} entries`;
  }

  paginationContainer.innerHTML = "";
  const totalPages = Math.ceil(total / state.pagination.pageSize);
  if (totalPages <= 1) return;

  // Previous Button
  const prevLi = document.createElement("li");
  prevLi.className = `page-item ${state.pagination.currentPage === 1 ? 'disabled' : ''}`;
  prevLi.innerHTML = `<a class="page-link" href="#" aria-label="Previous"><i class="fas fa-chevron-left"></i></a>`;
  prevLi.addEventListener("click", (e) => {
    e.preventDefault();
    if (state.pagination.currentPage > 1) {
      loadServerData(state.pagination.currentPage - 1);
    }
  });
  paginationContainer.appendChild(prevLi);

  // Pages
  for (let i = 1; i <= totalPages; i++) {
    const pageLi = document.createElement("li");
    pageLi.className = `page-item ${state.pagination.currentPage === i ? 'active' : ''}`;
    pageLi.innerHTML = `<a class="page-link" href="#">${i}</a>`;
    pageLi.addEventListener("click", (e) => {
      e.preventDefault();
      loadServerData(i);
    });
    paginationContainer.appendChild(pageLi);
  }

  // Next Button
  const nextLi = document.createElement("li");
  nextLi.className = `page-item ${state.pagination.currentPage === totalPages ? 'disabled' : ''}`;
  nextLi.innerHTML = `<a class="page-link" href="#" aria-label="Next"><i class="fas fa-chevron-right"></i></a>`;
  nextLi.addEventListener("click", (e) => {
    e.preventDefault();
    if (state.pagination.currentPage < totalPages) {
      loadServerData(state.pagination.currentPage + 1);
    }
  });
  paginationContainer.appendChild(nextLi);
}

// Format Date to Month Day, Year
function formatDate(dateStr) {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  const d = new Date(dateStr);
  return d.toLocaleDateString('en-US', options);
}

// 8. Transaction Details Modal
function showTransactionDetails(incomeId) {
  const t = INITIAL_TRANSACTIONS.find(item => item.incomeId === incomeId);
  if (!t) return;

  // Populate Modal Fields
  document.getElementById("modalIncomeId").textContent = t.incomeId;
  document.getElementById("modalOrderId").textContent = t.orderId;
  document.getElementById("modalCustomer").textContent = t.customerName;
  document.getElementById("modalDate").textContent = formatDate(t.date);

  // Badges in Modal
  let payClass = "pay-cash";
  if (t.paymentMethod === "Card") payClass = "pay-card";
  if (t.paymentMethod === "Online Payment") payClass = "pay-online";
  document.getElementById("modalPayment").innerHTML = `
    <span class="badge-pay ${payClass}">${t.paymentMethod}</span>
  `;

  let statusClass = "status-completed";
  if (t.incomeStatus === "Pending") statusClass = "status-pending";
  if (t.incomeStatus === "Cancelled") statusClass = "status-cancelled";
  document.getElementById("modalStatus").innerHTML = `
    <span class="badge-status ${statusClass}"><span class="status-dot"></span>${t.incomeStatus}</span>
  `;

  // Render Itemized breakdown
  const itemsContainer = document.getElementById("modalReceiptItems");
  itemsContainer.innerHTML = "";

  let subtotal = 0;
  t.items.forEach(item => {
    const itemTotal = item.qty * item.price;
    subtotal += itemTotal;

    const div = document.createElement("div");
    div.className = "receipt-item";
    div.innerHTML = `
      <span>${item.qty}x ${item.name}</span>
      <span class="fw-semibold">LKR ${itemTotal.toFixed(2)}</span>
    `;
    itemsContainer.appendChild(div);
  });

  const tax = t.tax || (subtotal * 0.08);
  const serviceCharge = t.serviceCharge || (subtotal * 0.05);
  const total = subtotal + tax + serviceCharge;

  document.getElementById("modalSubtotal").textContent = "LKR " + subtotal.toFixed(2);
  document.getElementById("modalTax").textContent = "LKR " + tax.toFixed(2);
  document.getElementById("modalServiceCharge").textContent = "LKR " + serviceCharge.toFixed(2);
  document.getElementById("modalTotal").textContent = "LKR " + total.toFixed(2);

  // Show Bootstrap Modal
  const modalEl = document.getElementById("detailsModal");
  const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
  modal.show();
}

// 9. Chart.js Implementations
function setupCharts() {
  // Chart 1: Monthly Income Bar Chart
  const barCtx = document.getElementById("monthlyIncomeChart").getContext("2d");

  barChartInstance = new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: ['May', 'Jun', 'Jul'],
      datasets: [{
        label: 'Income (LKR)',
        data: [0, 0, 0], // Populated dynamically
        backgroundColor: '#FF6B35', // Brand color
        borderRadius: 8,
        hoverBackgroundColor: '#e0531f',
        maxBarThickness: 32
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#0f172a',
          titleFont: { family: 'Outfit', size: 13 },
          bodyFont: { family: 'Outfit', size: 12 },
          callbacks: {
            label: function (context) {
              return 'Income: LKR ' + context.raw.toFixed(2);
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: { color: '#f1f5f9' },
          ticks: {
            font: { family: 'Outfit' },
            callback: function (value) { return 'LKR ' + value; }
          }
        },
        x: {
          grid: { display: false },
          ticks: { font: { family: 'Outfit' } }
        }
      }
    }
  });

  // Chart 2: Payment Methods Pie Chart
  const pieCtx = document.getElementById("paymentMethodChart").getContext("2d");
  pieChartInstance = new Chart(pieCtx, {
    type: 'doughnut',
    data: {
      labels: ['Cash', 'Card', 'Online Payment'],
      datasets: [{
        data: [0, 0, 0], // Populated dynamically
        backgroundColor: [
          '#64748b', // Slate for Cash
          '#3b82f6', // Blue for Card
          '#8b5cf6'  // Purple for Online
        ],
        borderWidth: 2,
        borderColor: '#ffffff',
        hoverOffset: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            font: { family: 'Outfit', size: 12, weight: 600 },
            padding: 15,
            usePointStyle: true
          }
        },
        tooltip: {
          backgroundColor: '#0f172a',
          callbacks: {
            label: function (context) {
              const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
              const percentage = total > 0 ? Math.round((context.raw / total) * 100) : 0;
              return ` ${context.label}: LKR ${context.raw.toFixed(2)} (${percentage}%)`;
            }
          }
        }
      },
      cutout: '65%'
    }
  });
}

function updateChartsData(filteredData) {
  if (!barChartInstance || !pieChartInstance) return;

  // 1. Recalculate Monthly sums
  let maySum = 0;
  let junSum = 0;
  let julSum = 0;

  filteredData.forEach(t => {
    if (t.incomeStatus === "Completed") {
      const month = new Date(t.date).getMonth(); // 0-indexed
      if (month === 4) maySum += t.amount; // May
      if (month === 5) junSum += t.amount; // June
      if (month === 6) julSum += t.amount; // July
    }
  });

  barChartInstance.data.datasets[0].data = [maySum, junSum, julSum];
  barChartInstance.update();

  // 2. Recalculate Payment method distribution
  let cashTotal = 0;
  let cardTotal = 0;
  let onlineTotal = 0;

  filteredData.forEach(t => {
    if (t.incomeStatus === "Completed") {
      if (t.paymentMethod === "Cash") cashTotal += t.amount;
      if (t.paymentMethod === "Card") cardTotal += t.amount;
      if (t.paymentMethod === "Online Payment") onlineTotal += t.amount;
    }
  });

  pieChartInstance.data.datasets[0].data = [cashTotal, cardTotal, onlineTotal];
  pieChartInstance.update();
}

// 10. Data refreshing mock logic (loading animations)
function refreshData() {
  const overlay = document.getElementById("loadingOverlay");
  overlay.classList.add("active");

  setTimeout(() => {
    // Add a random mock transaction during refresh to show data updates
    const randomCustomer = ["Liam Neeson", "Olivia Wilde", "Emma Watson", "Bruce Wayne"][Math.floor(Math.random() * 4)];
    const randomPayMethod = ["Cash", "Card", "Online Payment"][Math.floor(Math.random() * 3)];
    const randomAmount = parseFloat((Math.random() * 60 + 20).toFixed(2));
    const randomOrderNum = Math.floor(Math.random() * 100 + 9000);
    const nextIncNum = INITIAL_TRANSACTIONS.length + 1001;

    const newTx = {
      incomeId: "INC-" + nextIncNum,
      orderId: "ORD-" + randomOrderNum,
      customerName: randomCustomer,
      date: "2026-07-09",
      paymentMethod: randomPayMethod,
      amount: randomAmount,
      orderStatus: "Completed",
      incomeStatus: "Completed",
      items: [
        { name: "Special House Fried Rice", qty: 1, price: 15.99 },
        { name: "Crispy Spring Rolls", qty: 1, price: 6.99 }
      ],
      tax: randomAmount * 0.08,
      serviceCharge: randomAmount * 0.05
    };

    // Prepend to transaction mock DB
    INITIAL_TRANSACTIONS.unshift(newTx);

    // Refresh calculations
    updateStats();
    applyFiltersAndRender();

    overlay.classList.remove("active");
  }, 750);
}

// 11. Reports Exporting logic
function exportCSV() {
  if (transactions.length === 0) {
    alert("No data available to export.");
    return;
  }

  let csvContent = "data:text/csv;charset=utf-8,";
  csvContent += "Income ID,Order ID,Customer Name,Date,Payment Method,Amount,Income Status\n";

  transactions.forEach(t => {
    const row = [
      t.incomeId,
      t.orderId,
      `"${t.customerName}"`,
      t.date,
      t.paymentMethod,
      t.amount.toFixed(2),
      t.incomeStatus
    ].join(",");
    csvContent += row + "\n";
  });

  const encodedUri = encodeURI(csvContent);
  const link = document.createElement("a");
  link.setAttribute("href", encodedUri);
  link.setAttribute("download", `FlavorHub_Income_Report_${new Date().toISOString().split('T')[0]}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

function exportPDF() {
  // Simple elegant report PDF download using window.print() or specialized layout
  // We customize print styles so it looks extremely neat and targets the page's core contents
  alert("Preparing page for PDF/Print report. Click OK to open print wizard. Note: Choose 'Save as PDF' in the destination settings.");
  window.print();
}
