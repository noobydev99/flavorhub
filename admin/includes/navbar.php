<!-- Content wrapper start (will be closed in footer) -->
<div id="content">

<!-- Top Navbar -->
<nav class="top-navbar">
  
  <!-- Sidebar Toggle Button -->
  <button class="sidebar-toggle" id="sidebarCollapse" type="button">
    <i class="bi bi-list fs-5"></i>
  </button>
  
  <!-- Dynamic Page Title/Breadcrumb -->
  <span class="page-title d-none d-sm-inline-block">
    <?php
      switch ($current_page) {
          case 'dashboard.php': echo '<i class="bi bi-speedometer2 me-2"></i>Dashboard'; break;
          case 'recipes.php': echo '<i class="bi bi-journal-bookmark-fill me-2"></i>Recipes Manager'; break;
          case 'categories.php': echo '<i class="bi bi-tags-fill me-2"></i>Categories Manager'; break;
          case 'users.php': echo '<i class="bi bi-people-fill me-2"></i>Users Registry'; break;
          case 'comments.php': echo '<i class="bi bi-chat-right-text-fill me-2"></i>Comments Moderation'; break;
          case 'profile.php': echo '<i class="bi bi-person-circle me-2"></i>Account Profile'; break;
          case 'settings.php': echo '<i class="bi bi-sliders me-2"></i>System Settings'; break;
          default: echo 'FlavorHub Portal';
      }
    ?>
  </span>

  <!-- Right Profile Section -->
  <div class="ms-auto d-flex align-items-center">
    <div class="dropdown">
      <a class="user-pill dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="user-avatar">
          <?= strtoupper(substr($_SESSION['fullname'] ?? 'A', 0, 1)) ?>
        </div>
        <span class="user-name d-none d-md-inline"><?= htmlspecialchars($_SESSION['fullname'] ?? 'Admin') ?></span>
      </a>

      <ul class="dropdown-menu dropdown-menu-end shadow-sm border-secondary border-opacity-10 rounded-3" aria-labelledby="userDropdown" style="font-size: 0.9rem;">
        <li class="dropdown-header">
          <span class="fw-bold d-block text-dark"><?= htmlspecialchars($_SESSION['fullname'] ?? 'Admin') ?></span>
          <span class="text-muted small"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></span>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>My Profile</a></li>
        <li><a class="dropdown-item" href="settings.php"><i class="bi bi-gear me-2"></i>Settings</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="logout.php" onclick="return confirm('Are you sure you want to log out?');"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
      </ul>
    </div>
  </div>

</nav>

  <div class="main-container">
