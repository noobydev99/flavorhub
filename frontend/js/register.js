// ─── FlavorHub Register Page Logic ────────────────────────────

document.addEventListener("DOMContentLoaded", () => {
  const user = window.FlavorHubAPI.getCurrentUser();
  if (user) { window.location.href = "dashboard.html"; return; }

  setupRegisterForm();
});

function setupRegisterForm() {
  const form = document.getElementById("registerForm");
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const fullName = document.getElementById("regName").value.trim();
    const email = document.getElementById("regEmail").value.trim();
    const phone = document.getElementById("regPhone").value.trim();
    const address = document.getElementById("regAddress").value.trim();
    const password = document.getElementById("regPassword").value;
    const confirmPass = document.getElementById("regConfirmPassword").value;

    let hasError = false;

    if (!fullName || fullName.length < 2) { setAuthError("regName", "Please enter your full name."); hasError = true; } else clearAuthError("regName");
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { setAuthError("regEmail", "Please enter a valid email."); hasError = true; } else clearAuthError("regEmail");
    if (!phone) { setAuthError("regPhone", "Phone number is required."); hasError = true; } else clearAuthError("regPhone");
    if (password.length < 6) { setAuthError("regPassword", "Password must be at least 6 characters."); hasError = true; } else clearAuthError("regPassword");
    if (password !== confirmPass) { setAuthError("regConfirmPassword", "Passwords do not match."); hasError = true; } else clearAuthError("regConfirmPassword");

    if (hasError) return;

    const submitBtn = form.querySelector(".btn-auth-submit");
    submitBtn.textContent = "Creating Account...";
    submitBtn.disabled = true;

    const result = await window.FlavorHubAPI.register(fullName, email, phone, address, password);

    submitBtn.textContent = "Create Account";
    submitBtn.disabled = false;

    if (result.success) {
      window.showToast("Account created! Welcome to FlavorHub!", "success");
      setTimeout(() => { window.location.href = "dashboard.html"; }, 800);
    } else {
      window.showToast(result.message || "Registration failed.", "error");
      setAuthError("regEmail", result.message);
    }
  });
}

function setAuthError(id, msg) {
  const input = document.getElementById(id);
  if (!input) return;
  input.classList.add("error");
  let err = input.parentNode.querySelector(".auth-error-msg");
  if (!err) { err = document.createElement("span"); err.className = "auth-error-msg"; input.parentNode.appendChild(err); }
  err.textContent = msg;
}

function clearAuthError(id) {
  const input = document.getElementById(id);
  if (!input) return;
  input.classList.remove("error");
  const err = input.parentNode.querySelector(".auth-error-msg");
  if (err) err.remove();
}

