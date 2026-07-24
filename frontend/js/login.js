// ─── FlavorHub Login Page Logic ───────────────────────────────

document.addEventListener("DOMContentLoaded", () => {
  // If already logged in, redirect
  const user = window.FlavorHubAPI.getCurrentUser();
  if (user) {
    const urlParams = new URLSearchParams(window.location.search);
    const redirect = urlParams.get("redirect") || "dashboard.html";
    window.location.href = redirect;
    return;
  }

  setupLoginForm();
});

function setupLoginForm() {
  const form = document.getElementById("loginForm");
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const email = document.getElementById("loginEmail").value.trim();
    const password = document.getElementById("loginPassword").value;
    let hasError = false;

    // Validate fields
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      setAuthError("loginEmail", "Please enter a valid email address.");
      hasError = true;
    } else {
      clearAuthError("loginEmail");
    }

    if (!password || password.length < 6) {
      setAuthError("loginPassword", "Password must be at least 6 characters.");
      hasError = true;
    } else {
      clearAuthError("loginPassword");
    }

    if (hasError) return;

    // Submit button loading state
    const submitBtn = form.querySelector(".btn-auth-submit");
    submitBtn.textContent = "Signing In...";
    submitBtn.disabled = true;

    const result = await window.FlavorHubAPI.login(email, password);

    submitBtn.textContent = "Sign In";
    submitBtn.disabled = false;

    if (result.success) {
      window.showToast("Welcome back! Redirecting...", "success");
      const urlParams = new URLSearchParams(window.location.search);
      const redirect = urlParams.get("redirect") || "dashboard.html";
      setTimeout(() => { window.location.href = redirect; }, 800);
    } else {
      window.showToast(result.message || "Login failed. Try again.", "error");
      document.getElementById("loginEmail").classList.add("error");
      document.getElementById("loginPassword").classList.add("error");
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

