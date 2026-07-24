<?php
require_once __DIR__ . '/includes/header.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\CommentDAO;
use FlavorHub\BusinessLogic\CommentService;

$error = '';
$success = '';

try {
    $db = Database::getConnection();
    $commentDAO = new CommentDAO($db);
    $commentService = new CommentService($commentDAO);
} catch (Exception $e) {
    $error = "Database Connection Error: " . $e->getMessage();
}

// Handle Moderation Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $commentId = (int)$_GET['id'];
    $act       = $_GET['action'];

    try {
        if ($act === 'approve') {
            if ($commentService->approveComment($commentId)) {
                $success = "Comment approved successfully.";
            } else {
                $error = "Unable to approve comment.";
            }
        } elseif ($act === 'delete') {
            if ($commentService->deleteComment($commentId)) {
                $success = "Comment deleted successfully.";
            } else {
                $error = "Unable to delete comment.";
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Fetch comments
$comments = [];
try {
    $comments = $commentService->getAllComments();
} catch (Exception $e) {
    $error = "Failed to load comments: " . $e->getMessage();
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
    <h4 class="fw-bold text-dark mb-1">Comments Moderation</h4>
    <p class="text-secondary small mb-0">Approve user comments to display them publicly, or remove spam and negative inputs.</p>
  </div>

  <div class="table-responsive">
    <table class="fh-table">
      <thead>
        <tr class="text-muted">
          <th>Recipe</th>
          <th>Commenter</th>
          <th>Comment Content</th>
          <th>Status</th>
          <th>Submitted On</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($comments)): ?>
          <tr>
            <td colspan="6" class="text-center text-secondary py-5">No comments found.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($comments as $comment): ?>
            <tr>
              
              <!-- Recipe Title link -->
              <td class="fw-bold text-dark" style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                <?= htmlspecialchars($comment['recipe_title']) ?>
              </td>

              <!-- Commenter Info -->
              <td>
                <span class="fw-semibold text-dark d-block"><?= htmlspecialchars($comment['fullname']) ?></span>
                <span class="text-secondary small text-muted"><?= htmlspecialchars($comment['email']) ?></span>
              </td>

              <!-- Comment Text -->
              <td class="text-secondary small" style="max-width: 300px; white-space: normal; word-wrap: break-word;">
                <?= htmlspecialchars($comment['comment_text']) ?>
              </td>

              <!-- Status Badge -->
              <td>
                <?php if ($comment['status'] === 'approved'): ?>
                  <span class="badge-pill badge-approved"><span class="dot"></span>Approved</span>
                <?php else: ?>
                  <span class="badge-pill badge-pending"><span class="dot"></span>Pending</span>
                <?php endif; ?>
              </td>

              <!-- Date -->
              <td class="text-secondary small">
                <?= date('M d, Y', strtotime($comment['created_at'])) ?>
              </td>

              <!-- Action buttons -->
              <td class="text-end" style="min-width: 150px;">
                <?php if ($comment['status'] !== 'approved'): ?>
                  <a 
                    href="comments.php?action=approve&id=<?= (int)$comment['id'] ?>" 
                    class="btn btn-sm btn-success rounded-3 me-1"
                    title="Approve Comment"
                  >
                    <i class="bi bi-check-lg"></i> Approve
                  </a>
                <?php endif; ?>
                <a 
                  href="comments.php?action=delete&id=<?= (int)$comment['id'] ?>" 
                  class="btn btn-sm btn-outline-danger rounded-3"
                  title="Delete Comment"
                  onclick="return confirm('Are you sure you want to delete this comment?');"
                >
                  <i class="bi bi-trash"></i> Delete
                </a>
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
