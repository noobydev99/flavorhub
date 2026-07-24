<?php
require_once __DIR__ . '/includes/header.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\CategoryDAO;
use FlavorHub\DataAccess\RecipeDAO;
use FlavorHub\BusinessLogic\CategoryService;

$error = '';
$success = '';

try {
    $db = Database::getConnection();
    $categoryDAO = new CategoryDAO($db);
    $recipeDAO   = new RecipeDAO($db);
    $categoryService = new CategoryService($categoryDAO, $recipeDAO);
} catch (Exception $e) {
    $error = "Database Error: " . $e->getMessage();
}

// 1. Handle Deletion Action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $deleteId = (int)$_GET['id'];
    try {
        if ($categoryService->deleteCategory($deleteId)) {
            $success = "Category deleted successfully.";
        } else {
            $error = "Unable to delete category.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// 2. Handle Add / Edit Submission POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $categoryId  = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    try {
        if ($categoryId > 0) {
            // Update
            if ($categoryService->editCategory($categoryId, $name, $description)) {
                $success = "Category updated successfully.";
            } else {
                $error = "No changes were made or update failed.";
            }
        } else {
            // Create
            if ($categoryService->addCategory($name, $description) > 0) {
                $success = "Category created successfully.";
            } else {
                $error = "Failed to create category.";
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Fetch all categories for list
$categories = [];
try {
    $categories = $categoryService->getAllCategories();
} catch (Exception $e) {
    $error = "Failed to load categories: " . $e->getMessage();
}

// Handle fetching item for edit mode
$editCategory = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $editId = (int)$_GET['id'];
    try {
        $editCategory = $categoryService->getCategoryById($editId);
        if (!$editCategory) {
            $error = "Category not found.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!-- Alert notices -->
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
  
  <!-- Left Side: Form (Collapsible/Dynamic) -->
  <div class="col-12 col-lg-4">
    <div class="card border-0 rounded-4 shadow-sm p-4">
      <h5 class="fw-bold text-dark mb-3">
        <?= $editCategory ? '<i class="bi bi-pencil-square text-warning me-2"></i>Edit Category' : '<i class="bi bi-plus-circle-fill text-primary me-2"></i>Add Category' ?>
      </h5>
      
      <form action="categories.php" method="POST">
        <?php if ($editCategory): ?>
          <input type="hidden" name="id" value="<?= (int)$editCategory['id'] ?>">
        <?php endif; ?>

        <div class="mb-3">
          <label for="name" class="form-label fw-semibold text-secondary">Category Name</label>
          <input 
            type="text" 
            name="name" 
            id="name" 
            class="form-control rounded-3" 
            placeholder="e.g. Appetizers"
            value="<?= htmlspecialchars($editCategory ? $editCategory['name'] : ($_POST['name'] ?? '')) ?>"
            required
          >
        </div>

        <div class="mb-4">
          <label for="description" class="form-label fw-semibold text-secondary">Description</label>
          <textarea 
            name="description" 
            id="description" 
            rows="4" 
            class="form-control rounded-3" 
            placeholder="Short details about the category..."
          ><?= htmlspecialchars($editCategory ? $editCategory['description'] : ($_POST['description'] ?? '')) ?></textarea>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-semibold">
            <?= $editCategory ? 'Save Changes' : 'Add Category' ?>
          </button>
          
          <?php if ($editCategory): ?>
            <a href="categories.php" class="btn btn-light border w-100 rounded-3 py-2 fw-semibold">Cancel</a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <!-- Right Side: List Categories -->
  <div class="col-12 col-lg-8">
    <div class="card border-0 rounded-4 shadow-sm h-100">
      <div class="card-header bg-white border-0 py-3">
        <h5 class="fw-bold text-dark mb-0">Category Directory</h5>
      </div>
      <div class="table-responsive px-3 pb-3">
        <table class="fh-table">
          <thead>
            <tr class="text-muted">
              <th>ID</th>
              <th>Name</th>
              <th>Description</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($categories)): ?>
              <tr>
                <td colspan="4" class="text-center text-secondary py-5">No categories found in the system.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($categories as $cat): ?>
                <tr>
                  <td class="text-secondary fw-semibold">#<?= (int)$cat['id'] ?></td>
                  <td class="fw-bold text-dark"><?= htmlspecialchars($cat['name']) ?></td>
                  <td class="text-secondary small text-truncate-2" style="max-width: 250px;">
                    <?= htmlspecialchars($cat['description'] ?? 'N/A') ?>
                  </td>
                  <td class="text-end">
                    <a 
                      href="categories.php?action=edit&id=<?= (int)$cat['id'] ?>" 
                      class="btn btn-sm btn-outline-warning rounded-3 me-1"
                      title="Edit"
                    >
                      <i class="bi bi-pencil"></i>
                    </a>
                    <a 
                      href="categories.php?action=delete&id=<?= (int)$cat['id'] ?>" 
                      class="btn btn-sm btn-outline-danger rounded-3" 
                      title="Delete"
                      onclick="return confirm('Are you sure you want to delete this category? All associated recipes might be deleted.');"
                    >
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
