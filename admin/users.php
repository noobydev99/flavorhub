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

// Handle User Deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $targetId  = (int)$_GET['id'];
    $currentId = (int)$_SESSION['user_id'];

    try {
        if ($userService->deleteUser($targetId, $currentId)) {
            $success = "User account deleted successfully.";
        } else {
            $error = "Unable to delete user account.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Fetch all users
$users = [];
try {
    $users = $userService->getAllUsers();
} catch (Exception $e) {
    $error = "Failed to load users: " . $e->getMessage();
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

<div class="card border-0 rounded-4 shadow-sm p-4">
  
  <div class="mb-4">
    <h4 class="fw-bold text-dark mb-1">Users Registry</h4>
    <p class="text-secondary small mb-0">List of registered system administrators with backend database controls.</p>
  </div>

  <div class="table-responsive">
    <table class="fh-table">
      <thead>
        <tr class="text-muted">
          <th>Profile</th>
          <th>Full Name</th>
          <th>Email Address</th>
          <th>Bio Summary</th>
          <th>Registered On</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($users)): ?>
          <tr>
            <td colspan="6" class="text-center text-secondary py-5">No registered users found.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($users as $user): ?>
            <?php 
              $isCurrentUser = ((int)$user['id'] === (int)$_SESSION['user_id']);
            ?>
            <tr class="<?= $isCurrentUser ? 'table-warning table-opacity-10' : '' ?>">
              
              <!-- Avatar Circle -->
              <td>
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white bg-secondary bg-opacity-70" style="width: 38px; height: 38px;">
                  <?= strtoupper(substr($user['fullname'] ?? 'A', 0, 1)) ?>
                </div>
              </td>
              
              <!-- Full Name -->
              <td>
                <span class="fw-bold text-dark">
                  <?= htmlspecialchars($user['fullname']) ?>
                </span>
                <?php if ($isCurrentUser): ?>
                  <span class="badge bg-warning text-dark ms-1 small px-2">You</span>
                <?php endif; ?>
              </td>

              <!-- Email -->
              <td class="text-secondary small">
                <?= htmlspecialchars($user['email']) ?>
              </td>

              <!-- Bio -->
              <td class="text-secondary small text-truncate-2" style="max-width: 250px;">
                <?= htmlspecialchars($user['bio'] ?? 'No bio description provided.') ?>
              </td>

              <!-- Date -->
              <td class="text-secondary small">
                <?= date('M d, Y', strtotime($user['created_at'])) ?>
              </td>

              <!-- Actions -->
              <td class="text-end">
                <?php if ($isCurrentUser): ?>
                  <a href="profile.php" class="btn btn-sm btn-outline-warning rounded-3" title="Edit Profile">
                    <i class="bi bi-pencil-fill"></i> Edit Profile
                  </a>
                <?php else: ?>
                  <a 
                    href="users.php?action=delete&id=<?= (int)$user['id'] ?>" 
                    class="btn btn-sm btn-outline-danger rounded-3" 
                    title="Delete User"
                    onclick="return confirm('Are you sure you want to permanently delete this user account?');"
                  >
                    <i class="bi bi-trash"></i> Delete
                  </a>
                <?php endif; ?>
              </td>

            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
