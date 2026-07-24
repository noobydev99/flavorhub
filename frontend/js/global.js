// ─── FlavorHub Global Interactions Script ───────────────────

document.addEventListener("DOMContentLoaded", () => {
  // 1. Hide Loader
  const loader = document.querySelector(".global-page-loader");
  if (loader) {
    setTimeout(() => {
      loader.classList.add("hide");
    }, 400);
  }

  // 2. Sticky Header & Back to Top Toggle
  const header = document.querySelector(".header-nav");
  const backToTop = document.querySelector(".back-to-top");

  window.addEventListener("scroll", () => {
    if (window.scrollY > 50) {
      if (header) header.style.padding = "0.6rem 1.5rem";
    } else {
      if (header) header.style.padding = "1rem 1.5rem";
    }

    if (backToTop) {
      if (window.scrollY > 300) {
        backToTop.classList.add("show");
      } else {
        backToTop.classList.remove("show");
      }
    }
  });

  if (backToTop) {
    backToTop.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }

  // 3. Mobile Hamburger Menu
  const burger = document.querySelector(".menu-burger");
  const navLinks = document.querySelector(".nav-links");

  if (burger && navLinks) {
    burger.addEventListener("click", () => {
      navLinks.classList.toggle("active");
      const icon = burger.querySelector("i");
      if (icon) {
        if (navLinks.classList.contains("active")) {
          icon.className = "bi bi-x";
        } else {
          icon.className = "bi bi-list";
        }
      }
    });
  }

  // 4. Initialize Navbar Cart Badge & Auth State Pill
  syncNavbarState();

  // Listen for cart update events
  window.addEventListener("cartUpdated", syncNavbarState);
});

// Toast notification renderer
function showToast(message, type = "success") {
  let container = document.querySelector(".toast-container-custom");
  if (!container) {
    container = document.createElement("div");
    container.className = "toast-container-custom";
    document.body.appendChild(container);
  }

  const toast = document.createElement("div");
  toast.className = `toast-item-custom ${type}`;
  
  let icon = "fa-check-circle";
  if (type === "error") icon = "fa-exclamation-circle";
  if (type === "info") icon = "fa-info-circle";

  toast.innerHTML = `
    <i class="fas ${icon}"></i>
    <span>${message}</span>
  `;

  container.appendChild(toast);

  // Auto remove toast
  setTimeout(() => {
    toast.classList.add("fade-out");
    setTimeout(() => {
      toast.remove();
    }, 300);
  }, 3500);
}

// Set badge value & adjust user accounts dropdown link
function syncNavbarState() {
  // Cart count
  const cartBadge = document.querySelector(".cart-icon-badge");
  if (cartBadge) {
    const cart = window.FlavorHubAPI ? window.FlavorHubAPI.getCart() : [];
    const count = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartBadge.textContent = count;
    cartBadge.style.display = count > 0 ? "flex" : "none";
  }

  // User auth state
  const navActions = document.querySelector(".nav-actions");
  if (navActions && window.FlavorHubAPI) {
    const user = window.FlavorHubAPI.getCurrentUser();
    
    // Check if user login node already exists inside the navbar
    let userPillContainer = navActions.querySelector(".nav-dropdown.user-auth-pill");
    let loginLink = navActions.querySelector(".nav-login-btn, .btn-login");
    let registerLink = navActions.querySelector(".nav-register-btn, .btn-register");

    if (loginLink && !loginLink.classList.contains("nav-login-btn")) {
      loginLink.classList.add("nav-login-btn");
    }
    if (registerLink && !registerLink.classList.contains("nav-register-btn")) {
      registerLink.classList.add("nav-register-btn");
    }

    if (user) {
      if (loginLink) loginLink.remove();
      if (registerLink) registerLink.remove();
      if (!userPillContainer) {
        userPillContainer = document.createElement("div");
        userPillContainer.className = "nav-dropdown user-auth-pill";
        userPillContainer.innerHTML = `
          <div class="user-dropdown-pill">
            <div class="user-avatar-initial">${user.fullName.charAt(0).toUpperCase()}</div>
            <span>${user.fullName.split(' ')[0]}</span>
          </div>
          <ul class="dropdown-menu-list">
            <a href="dashboard.html"><i class="fas fa-user-circle"></i> Dashboard</a>
            <a href="dashboard.html?tab=orders"><i class="fas fa-history"></i> My Orders</a>
            <a href="#" id="btnLogoutAction"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </ul>
        `;
        navActions.appendChild(userPillContainer);
        
        // Bind logout click
        userPillContainer.querySelector("#btnLogoutAction").addEventListener("click", (e) => {
          e.preventDefault();
          window.FlavorHubAPI.logout();
        });
      }
    } else {
      if (userPillContainer) userPillContainer.remove();

      if (!registerLink) {
        registerLink = document.createElement("a");
        registerLink.className = "btn-orange-outline nav-register-btn";
        registerLink.href = "register.html";
        registerLink.innerHTML = `<i class="fas fa-user-plus"></i> Register`;
        navActions.appendChild(registerLink);
      }

      if (!loginLink) {
        loginLink = document.createElement("a");
        loginLink.className = "btn-orange nav-login-btn";
        loginLink.href = "login.html";
        loginLink.textContent = "Login";
        navActions.appendChild(loginLink);
      }
    }
  }
}

