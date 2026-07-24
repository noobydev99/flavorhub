<?php
require_once __DIR__ . '/includes/header.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\UserDAO;
use FlavorHub\BusinessLogic\UserService;

$error = '';
$success = '';

try {
    $db = Database::getConnection();
    $userDAO = new UserDAO($db);
    $userService = new UserService($userDAO);
} catch (Exception $e) {
    $error = "Database Connection Error: " . $e->getMessage();
}

$userId = (int)$_SESSION['user_id'];

// Handle Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['form_type'] ?? '';

    try {
        if ($formType === 'profile') {
            $fullname = trim($_POST['fullname'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $bio      = trim($_POST['bio'] ?? '');

            if ($userService->updateProfile($userId, $fullname, $email, $bio)) {
                $success = "Profile updated successfully.";
                // Sync session values
                $_SESSION['fullname'] = $fullname;
                $_SESSION['email']    = $email;
            } else {
                $error = "Failed to update profile.";
            }
        } elseif ($formType === 'password') {
            $currPass = $_POST['current_password'] ?? '';
            $newPass  = $_POST['new_password'] ?? '';
            $confPass = $_POST['confirm_password'] ?? '';

            if ($userService->changePassword($userId, $currPass, $newPass, $confPass)) {
                $success = "Password changed successfully.";
            } else {
                $error = "Failed to update password.";
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Fetch fresh profile details
$user = null;
try {
    $user = $userService->getUserById($userId);
} catch (Exception $e) {
    $error = "Failed to load profile details: " . $e->getMessage();
}
?>

<!-- Alerts -->
<?php if ($error !== ''): ?>
  <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm py-3 mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php if ($success !== ''): ?>
  <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm py-3 mb-4" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<div class="row g-4">
  
  <!-- Left Side: Profile Info Card -->
  <div class="col-12 col-lg-7">
    <div class="card border-0 rounded-4 shadow-sm p-4 h-100">
      <h5 class="fw-bold text-dark mb-1"><i class="bi bi-person-fill text-primary me-2"></i>Edit Profile Information</h5>
      <p class="text-secondary small mb-4">Modify your display name, system contact email, and biographical description.</p>

      <form action="profile.php" method="POST">
        <input type="hidden" name="form_type" value="profile">

        <div class="mb-3">
          <label for="fullname" class="form-label fw-semibold text-secondary">Full Name</label>
          <input 
            type="text" 
            name="fullname" 
            id="fullname" 
            class="form-control rounded-3"
            value="<?= htmlspecialchars($user ? $user['fullname'] : '') ?>"
            required
          >
        </div>

        <div class="mb-3">
          <label for="email" class="form-label fw-semibold text-secondary">Email Address</label>
          <input 
            type="email" 
            name="email" 
            id="email" 
            class="form-control rounded-3"
            value="<?= htmlspecialchars($user ? $user['email'] : '') ?>"
            required
          >
        </div>

        <div class="mb-4">
          <label for="bio" class="form-label fw-semibold text-secondary">Biography</label>
          <textarea 
            name="bio" 
            id="bio" 
            rows="5" 
            class="form-control rounded-3" 
            placeholder="Tell us about yourself..."
          ><?= htmlspecialchars($user ? ($user['bio'] ?? '') : '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 fw-semibold">Save Profile Details</button>
      </form>
    </div>
  </div>

  <!-- Right Side: Change Password Card -->
  <div class="col-12 col-lg-5">
    <div class="card border-0 rounded-4 shadow-sm p-4 h-100">
      <h5 class="fw-bold text-dark mb-1"><i class="bi bi-shield-lock-fill text-warning me-2"></i>Security Settings</h5>
      <p class="text-secondary small mb-4">Change your account password regularly to keep your administrative portal safe.</p>

      <form action="profile.php" method="POST">
        <input type="hidden" name="form_type" value="password">

        <div class="mb-3">
          <label for="current_password" class="form-label fw-semibold text-secondary">Current Password</label>
          <input 
            type="password" 
            name="current_password" 
            id="current_password" 
            class="form-control rounded-3" 
            placeholder="••••••••"
            required
          >
        </div>

        <div class="mb-3">
          <label for="new_password" class="form-label fw-semibold text-secondary">New Password</label>
          <input 
            type="password" 
            name="new_password" 
            id="new_password" 
            class="form-control rounded-3" 
            placeholder="Min 6 characters"
            required
          >
        </div>

        <div class="mb-4">
          <label for="confirm_password" class="form-label fw-semibold text-secondary">Confirm New Password</label>
          <input 
            type="password" 
            name="confirm_password" 
            id="confirm_password" 
            class="form-control rounded-3" 
            placeholder="••••••••"
            required
          >
        </div>

        <button type="submit" class="btn btn-warning text-dark rounded-3 px-4 py-2 fw-bold">Change Password</button>
      </form>
    </div>
  </div>

</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
