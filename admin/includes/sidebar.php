<?php
// Active state helper
$current = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar Navigation -->
<nav id="sidebar">
  <div>
    <!-- Brand Info -->
    <div class="sidebar-header">
      <a href="dashboard.php" class="sidebar-brand">
        <i class="bi bi-egg-fried"></i> <?= htmlspecialchars($siteName) ?>
      </a>
    </div>

    <!-- Navigation Section Label -->
    <div class="sidebar-section-label">Management</div>

    <!-- Navigation links -->
    <ul class="sidebar-nav">
      <li class="<?= $current === 'dashboard.php' ? 'active' : '' ?>">
        <a href="dashboard.php">
          <i class="bi bi-speedometer2"></i> Dashboard
        </a>
      </li>
      <li class="<?= $current === 'orders.php' ? 'active' : '' ?>">
        <a href="orders.php">
          <i class="bi bi-bag-check-fill"></i> Orders
        </a>
      </li>
      <li class="<?= $current === 'income.php' ? 'active' : '' ?>">
        <a href="income.php">
          <i class="bi bi-currency-dollar"></i> Income
        </a>
      </li>
      <li class="<?= $current === 'recipes.php' ? 'active' : '' ?>">
        <a href="recipes.php">
          <i class="bi bi-journal-bookmark-fill"></i> Recipes
        </a>
      </li>
      <li class="<?= $current === 'categories.php' ? 'active' : '' ?>">
        <a href="categories.php">
          <i class="bi bi-tags-fill"></i> Categories
        </a>
      </li>
      <li class="<?= $current === 'users.php' ? 'active' : '' ?>">
        <a href="users.php">
          <i class="bi bi-people-fill"></i> User Accounts
        </a>
      </li>
      <li class="<?= $current === 'comments.php' ? 'active' : '' ?>">
        <a href="comments.php">
          <i class="bi bi-chat-right-text-fill"></i> Comments
        </a>
      </li>
      <li class="<?= $current === 'profile.php' ? 'active' : '' ?>">
        <a href="profile.php">
          <i class="bi bi-person-circle"></i> Profile Info
        </a>
      </li>
      <li class="<?= $current === 'settings.php' ? 'active' : '' ?>">
        <a href="settings.php">
          <i class="bi bi-sliders"></i> Site Settings
        </a>
      </li>
    </ul>
  </div>

  <!-- Logout item at the bottom -->
  <div class="sidebar-footer">
    <a href="logout.php" onclick="return confirm('Are you sure you want to sign out?');">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
  </div>
</nav>
