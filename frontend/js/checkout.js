// ─── FlavorHub Checkout Page Logic ────────────────────────────

document.addEventListener("DOMContentLoaded", () => {
  // 1. Authenticated customer check
  const user = window.FlavorHubAPI.getCurrentUser();
  if (!user) {
    window.location.href = "login.html?redirect=checkout.html";
    return;
  }

  // 2. Cart empty check
  const cart = window.FlavorHubAPI.getCart();
  if (cart.length === 0) {
    window.location.href = "menu.html";
    return;
  }

  // 3. Pre-fill customer fields
  prefillCustomerFields(user);

  // 4. Render Checkout Order Summary Panel
  renderCheckoutSummary(cart);

  // 5. Setup Payment Box Click Handlers
  setupPaymentSelector();

  // 6. Setup Form Submission Validation
  setupFormValidation();
});

function prefillCustomerFields(user) {
  const nameInput = document.getElementById("checkoutName");
  const phoneInput = document.getElementById("checkoutPhone");
  const addressInput = document.getElementById("checkoutAddress");

  if (nameInput) nameInput.value = user.fullName || "";
  if (phoneInput) phoneInput.value = user.phone || "";
  if (addressInput) addressInput.value = user.address || "";
}

function renderCheckoutSummary(cart) {
  const listContainer = document.getElementById("checkoutItemsList");
  const subtotalVal = document.getElementById("checkoutSubtotalVal");
  const taxVal = document.getElementById("checkoutTaxVal");
  const totalVal = document.getElementById("checkoutTotalVal");

  if (!listContainer || !subtotalVal || !totalVal) return;

  listContainer.innerHTML = "";

  cart.forEach(item => {
    const div = document.createElement("div");
    div.className = "checkout-summary-item";
    div.innerHTML = `
      <span class="checkout-summary-item-name">${item.name}</span>
      <span class="checkout-summary-item-qty">x${item.quantity}</span>
      <span class="checkout-summary-item-price">LKR ${(item.price * item.quantity).toFixed(2)}</span>
    `;
    listContainer.appendChild(div);
  });

  const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
  const delivery = 300;
  const total = subtotal + delivery;

  subtotalVal.textContent = `LKR ${subtotal.toFixed(2)}`;
  if (taxVal) taxVal.parentElement.style.display = 'none';

  totalVal.textContent = `LKR ${total.toFixed(2)}`;
}

function setupPaymentSelector() {
  const boxes = document.querySelectorAll(".payment-method-box");
  const cardSection = document.getElementById("cardDetailsSection");
  const bankingSection = document.getElementById("bankingDetailsSection");
  const codSection = document.getElementById("codDetailsSection");

  function updatePaymentUI() {
    const radio = document.querySelector('input[name="paymentMethod"]:checked');
    if (!radio) return;

    // Hide all details
    if (cardSection) cardSection.style.display = "none";
    if (bankingSection) bankingSection.style.display = "none";
    if (codSection) codSection.style.display = "none";

    // Show correct details
    if (radio.value === "Credit/Debit Card" && cardSection) {
      cardSection.style.display = "block";
    } else if (radio.value === "Online Banking" && bankingSection) {
      bankingSection.style.display = "block";
    } else if (radio.value === "Cash on Delivery" && codSection) {
      codSection.style.display = "block";

      const cn = document.getElementById("codName");
      const cp = document.getElementById("codPhone");
      const ca = document.getElementById("codAddress");
      if (cn && !cn.value) cn.value = document.getElementById("checkoutName") ? document.getElementById("checkoutName").value || "" : "";
      if (cp && !cp.value) cp.value = document.getElementById("checkoutPhone") ? document.getElementById("checkoutPhone").value || "" : "";
      if (ca && !ca.value) ca.value = document.getElementById("checkoutAddress") ? document.getElementById("checkoutAddress").value || "" : "";
    }

    // Sync active classes
    boxes.forEach(b => {
      const bRadio = b.querySelector('input[type="radio"]');
      if (bRadio && bRadio.checked) {
        b.classList.add("selected");
      } else {
        b.classList.remove("selected");
      }
    });
  }

  boxes.forEach(box => {
    box.addEventListener("click", function () {
      const radio = this.querySelector('input[type="radio"]');
      if (radio) {
        radio.checked = true;
        updatePaymentUI();
      }
    });
  });

  // Call on load to sync any cached states
  updatePaymentUI();

  // Dynamic COD updates
  ["checkoutName", "checkoutPhone", "checkoutAddress"].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener("input", updatePaymentUI);
    }
  });
}

function setupFormValidation() {
  const handleOrderSubmission = async (e) => {
    e.preventDefault();

    const name = document.getElementById("checkoutName").value.trim();
    const phone = document.getElementById("checkoutPhone").value.trim();
    const address = document.getElementById("checkoutAddress").value.trim();
    const instructions = document.getElementById("checkoutInstructions").value.trim();

    // Validate inputs
    let hasError = false;

    if (!name) {
      setError("checkoutName", "Full Name is required.");
      hasError = true;
    } else {
      clearError("checkoutName");
    }

    if (!phone) {
      setError("checkoutPhone", "Phone Number is required.");
      hasError = true;
    } else {
      clearError("checkoutPhone");
    }

    if (!address) {
      setError("checkoutAddress", "Delivery Address is required.");
      hasError = true;
    } else {
      clearError("checkoutAddress");
    }

    // Payment method
    const paymentRadio = document.querySelector('input[name="paymentMethod"]:checked');
    if (!paymentRadio) {
      window.showToast("Please choose a payment method.", "error");
      hasError = true;
    } else {
      if (paymentRadio.value === "Credit/Debit Card") {
        const cardNum = document.getElementById("cardNumber").value.trim();
        const cardExpMonth = document.getElementById("cardExpiryMonth").value.trim();
        const cardExpYear = document.getElementById("cardExpiryYear").value.trim();
        const cardCvc = document.getElementById("cardCvc").value.trim();

        let cardError = false;
        if (!cardNum) { setError("cardNumber", "Required"); cardError = true; } else clearError("cardNumber");
        if (!cardExpMonth) { setError("cardExpiryMonth", "Required"); cardError = true; } else clearError("cardExpiryMonth");
        if (!cardExpYear) { setError("cardExpiryYear", "Required"); cardError = true; } else clearError("cardExpiryYear");
        if (!cardCvc) { setError("cardCvc", "Required"); cardError = true; } else clearError("cardCvc");

        if (cardError) {
          window.showToast("Please fill in your complete card details.", "error");
          hasError = true;
        }
      } else if (paymentRadio.value === "Online Banking") {
        const bankSelect = document.getElementById("bankSelect");
        const bankAcc = document.getElementById("bankAccount") ? document.getElementById("bankAccount").value.trim() : "Demo";

        let bankError = false;
        if (!bankSelect.value) {
          setError("bankSelect", "Please select a bank");
          bankError = true;
        } else {
          clearError("bankSelect");
        }

        const bankAccEl = document.getElementById("bankAccount");
        if (bankAccEl) {
          if (!bankAcc) {
            setError("bankAccount", "Required");
            bankError = true;
          } else {
            clearError("bankAccount");
          }
        }

        if (bankError) {
          window.showToast("Please complete your banking details.", "error");
          hasError = true;
        }
      } else if (paymentRadio.value === "Cash on Delivery") {
        const codName = document.getElementById("codName").value.trim();
        const codPhone = document.getElementById("codPhone").value.trim();
        const codAddress = document.getElementById("codAddress").value.trim();

        let codError = false;
        if (!codName) { setError("codName", "Required"); codError = true; } else clearError("codName");
        if (!codPhone) { setError("codPhone", "Required"); codError = true; } else clearError("codPhone");
        if (!codAddress) { setError("codAddress", "Required"); codError = true; } else clearError("codAddress");

        if (codError) {
          window.showToast("Please fill in cash on delivery contact details.", "error");
          hasError = true;
        }
      }
    }

    if (hasError) return;

    // Compile delivery details
    const deliveryDetails = {
      fullName: name,
      phone,
      address,
      instructions,
      paymentMethod: paymentRadio.value
    };

    // Place Order via API
    const response = await window.FlavorHubAPI.placeOrder(deliveryDetails);

    if (response.success) {
      window.showToast("Order placed successfully! Redirecting to tracking...", "success");
      setTimeout(() => {
        window.location.href = `tracking.html?orderId=${response.orderId}`;
      }, 1500);
    } else {
      window.showToast(response.message || "Failed to place order.", "error");
    }
  };

  const cardForm = document.getElementById("cardDetailsSection");
  const bankForm = document.getElementById("bankingDetailsSection");
  const codForm = document.getElementById("codDetailsSection");

  if (cardForm) cardForm.addEventListener("submit", handleOrderSubmission);
  if (bankForm) bankForm.addEventListener("submit", handleOrderSubmission);
  if (codForm) codForm.addEventListener("submit", handleOrderSubmission);

  // --- Strict Card Input Validation & Auto-Formatting --- //
  const ccNum = document.getElementById("cardNumber");
  if (ccNum) {
    ccNum.addEventListener("input", function (e) {
      // Remove all non-digits
      let value = e.target.value.replace(/\D/g, "");
      // Limit to 16 digits
      value = value.substring(0, 16);
      // Group by 4 digits
      let formatted = value.match(/.{1,4}/g);
      e.target.value = formatted ? formatted.join(" ") : "";
    });
  }

  const enforceNumeric = (id, maxLength) => {
    const el = document.getElementById(id);
    if (!el) return;
    el.addEventListener("input", function (e) {
      // Remove non-digits
      let value = e.target.value.replace(/\D/g, "");
      e.target.value = value.substring(0, maxLength);
    });
  };

  enforceNumeric("cardExpiryMonth", 2);
  enforceNumeric("cardExpiryYear", 2);
  enforceNumeric("cardCvc", 3);
}

function setError(id, msg) {
  const input = document.getElementById(id);
  if (!input) return;

  input.classList.add("error");
  input.classList.remove("success");

  // Find error label
  let errLabel = input.parentNode.querySelector(".form-error-msg");
  if (!errLabel) {
    errLabel = document.createElement("span");
    errLabel.className = "form-error-msg";
    input.parentNode.appendChild(errLabel);
  }
  errLabel.textContent = msg;
}

function clearError(id) {
  const input = document.getElementById(id);
  if (!input) return;

  input.classList.remove("error");
  input.classList.add("success");

  const errLabel = input.parentNode.querySelector(".form-error-msg");
  if (errLabel) errLabel.remove();
}

