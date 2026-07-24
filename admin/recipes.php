<?php
require_once __DIR__ . '/includes/header.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\RecipeDAO;
use FlavorHub\DataAccess\CategoryDAO;
use FlavorHub\BusinessLogic\RecipeService;
use FlavorHub\BusinessLogic\CategoryService;
use FlavorHub\Integration\FileUploader;

$error = '';
$success = '';

try {
    $db = Database::getConnection();
    
    $recipeDAO   = new RecipeDAO($db);
    $categoryDAO = new CategoryDAO($db);
    
    $recipeService   = new RecipeService($recipeDAO, new FileUploader());
    $categoryService = new CategoryService($categoryDAO, $recipeDAO);
} catch (Exception $e) {
    $error = "Database Connection Error: " . $e->getMessage();
}

$action = $_GET['action'] ?? 'list';

// 1. Handle Recipe Deletion
if ($action === 'delete' && isset($_GET['id'])) {
    $deleteId = (int)$_GET['id'];
    try {
        if ($recipeService->deleteRecipe($deleteId)) {
            $success = "Recipe deleted successfully.";
        } else {
            $error = "Unable to delete recipe.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
    $action = 'list';
}

// 2. Handle Form POST (Add/Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipeId     = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title        = trim($_POST['title'] ?? '');
    $description  = trim($_POST['description'] ?? '');
    $ingredients  = trim($_POST['ingredients'] ?? '');
    $instructions = trim($_POST['instructions'] ?? '');
    $categoryId   = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    $price        = isset($_POST['price']) ? (float)$_POST['price'] : 0.00;
    $imageFile    = $_FILES['image'] ?? null;

    try {
        if ($recipeId > 0) {
            // Edit
            if ($recipeService->editRecipe($recipeId, $title, $description, $ingredients, $instructions, $imageFile, $categoryId, $price)) {
                $success = "Recipe updated successfully.";
                $action = 'list';
            } else {
                $error = "No changes were made or update failed.";
            }
        } else {
            // Add
            $currentUserId = (int)$_SESSION['user_id'];
            if ($recipeService->addRecipe($title, $description, $ingredients, $instructions, $imageFile, $categoryId, $currentUserId, $price) > 0) {
                $success = "Recipe added successfully.";
                $action = 'list';
            } else {
                $error = "Failed to create recipe.";
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Fetch lists
$recipes = [];
$categories = [];
try {
    $recipes = $recipeService->getAllRecipes();
    $categories = $categoryService->getAllCategories();
} catch (Exception $e) {
    $error = "Failed to retrieve items: " . $e->getMessage();
}

// Handle edit item prefill
$editRecipe = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $editId = (int)$_GET['id'];
    try {
        $editRecipe = $recipeService->getRecipeById($editId);
        if (!$editRecipe) {
            $error = "Recipe not found.";
            $action = 'list';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        $action = 'list';
    }
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

<!-- Main Module Content -->
<div class="card border-0 rounded-4 shadow-sm p-4">
  
  <?php if ($action === 'add' || $action === 'edit'): ?>
    <!-- Form View (Add / Edit) -->
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h4 class="fw-bold text-dark mb-0">
        <?= $action === 'edit' ? '<i class="bi bi-pencil-square text-warning me-2"></i>Edit Recipe' : '<i class="bi bi-plus-circle text-primary me-2"></i>Add New Recipe' ?>
      </h4>
      <a href="recipes.php" class="btn btn-outline-secondary rounded-3">
        <i class="bi bi-arrow-left me-1"></i> Back to Directory
      </a>
    </div>

    <form action="recipes.php" method="POST" enctype="multipart/form-data">
      <?php if ($action === 'edit'): ?>
        <input type="hidden" name="id" value="<?= (int)$editRecipe['id'] ?>">
      <?php endif; ?>

      <div class="row g-4">
        
        <!-- Left Column: Title, Category, Description, Image -->
        <div class="col-12 col-lg-6">
          <div class="mb-3">
            <label for="title" class="form-label fw-semibold text-secondary">Recipe Title</label>
            <input 
              type="text" 
              name="title" 
              id="title" 
              class="form-control rounded-3" 
              placeholder="e.g. Classic Tomato Bruschetta"
              value="<?= htmlspecialchars($editRecipe ? $editRecipe['title'] : ($_POST['title'] ?? '')) ?>"
              required
            >
          </div>

          <div class="mb-3">
            <label for="category_id" class="form-label fw-semibold text-secondary">Food Category</label>
            <select name="category_id" id="category_id" class="form-select rounded-3" required>
              <option value="">-- Choose Category --</option>
              <?php foreach ($categories as $cat): ?>
                <?php 
                  $selected = '';
                  if ($editRecipe && $editRecipe['category_id'] == $cat['id']) {
                      $selected = 'selected';
                  } elseif (isset($_POST['category_id']) && $_POST['category_id'] == $cat['id']) {
                      $selected = 'selected';
                  }
                ?>
                <option value="<?= (int)$cat['id'] ?>" <?= $selected ?>><?= htmlspecialchars($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="price" class="form-label fw-semibold text-secondary">Price (LKR)</label>
            <input 
              type="number" 
              step="0.01"
              name="price" 
              id="price" 
              class="form-control rounded-3" 
              placeholder="e.g. 1500.00"
              value="<?= htmlspecialchars($editRecipe ? $editRecipe['price'] : ($_POST['price'] ?? '')) ?>"
            >
          </div>

          <div class="mb-3">
            <label for="description" class="form-label fw-semibold text-secondary">Short Description</label>
            <textarea 
              name="description" 
              id="description" 
              rows="3" 
              class="form-control rounded-3" 
              placeholder="A fresh, simple starters..."
            ><?= htmlspecialchars($editRecipe ? $editRecipe['description'] : ($_POST['description'] ?? '')) ?></textarea>
          </div>

          <div class="mb-3">
            <label for="image" class="form-label fw-semibold text-secondary">Recipe Image Cover</label>
            <input type="file" name="image" id="image" class="form-control rounded-3">
            <div class="form-text small text-secondary mt-1">Accepts JPG, PNG, GIF, WEBP up to 5MB.</div>
            
            <?php if ($editRecipe && $editRecipe['image_url']): ?>
              <div class="mt-3">
                <span class="d-block text-secondary small fw-bold mb-1">Current Cover:</span>
                <div class="rounded-3 overflow-hidden border border-secondary border-opacity-10" style="width: 150px; height: 100px;">
                  <img src="../<?= htmlspecialchars($editRecipe['image_url']) ?>" class="w-100 h-100 object-fit-cover" alt="Cover">
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Right Column: Ingredients & Instructions -->
        <div class="col-12 col-lg-6">
          <div class="mb-3">
            <label for="ingredients" class="form-label fw-semibold text-secondary">Ingredients (One per line)</label>
            <textarea 
              name="ingredients" 
              id="ingredients" 
              rows="6" 
              class="form-control rounded-3 font-monospace" 
              placeholder="4 ripe tomatoes&#10;2 cloves garlic&#10;1 tbsp olive oil..."
              required
            ><?= htmlspecialchars($editRecipe ? $editRecipe['ingredients'] : ($_POST['ingredients'] ?? '')) ?></textarea>
          </div>

          <div class="mb-3">
            <label for="instructions" class="form-label fw-semibold text-secondary">Cooking Instructions (Step-by-step)</label>
            <textarea 
              name="instructions" 
              id="instructions" 
              rows="6" 
              class="form-control rounded-3" 
              placeholder="1. Toss diced tomatoes and garlic.&#10;2. Toast baguette slices.&#10;3. Spoon mixture onto bread..."
              required
            ><?= htmlspecialchars($editRecipe ? $editRecipe['instructions'] : ($_POST['instructions'] ?? '')) ?></textarea>
          </div>
        </div>

      </div>

      <div class="mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
        <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-bold">
          <?= $action === 'edit' ? 'Save Changes' : 'Create Recipe' ?>
        </button>
        <a href="recipes.php" class="btn btn-light border px-4 py-2 rounded-3">Cancel</a>
      </div>
    </form>

  <?php else: ?>
    <!-- List View -->
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <h4 class="fw-bold text-dark mb-1">Recipes Registry</h4>
        <p class="text-secondary small mb-0">Browse and manage FlavorHub recipes.</p>
      </div>
      <a href="recipes.php?action=add" class="btn btn-primary rounded-3">
        <i class="bi bi-plus-lg me-1"></i> Add Recipe
      </a>
    </div>

    <div class="table-responsive">
      <table class="fh-table">
        <thead>
          <tr class="text-muted">
            <th>Cover</th>
            <th>Title</th>
            <th>Category</th>
            <th>Author</th>
            <th>Added Date</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recipes)): ?>
            <tr>
              <td colspan="6" class="text-center text-secondary py-5">No recipes found. Add your first recipe now!</td>
            </tr>
          <?php else: ?>
            <?php foreach ($recipes as $recipe): ?>
              <tr>
                <!-- Cover thumbnail -->
                <td>
                  <div class="rounded-3 bg-light border border-secondary border-opacity-10 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; overflow: hidden;">
                    <?php if ($recipe['image_url']): ?>
                      <img src="../<?= htmlspecialchars($recipe['image_url']) ?>" class="w-100 h-100 object-fit-cover" alt="">
                    <?php else: ?>
                      <i class="bi bi-image text-secondary fs-4"></i>
                    <?php endif; ?>
                  </div>
                </td>
                
                <!-- Title & short desc -->
                <td>
                  <span class="fw-bold text-dark d-block"><?= htmlspecialchars($recipe['title']) ?></span>
                  <span class="text-secondary small text-truncate d-inline-block" style="max-width: 200px;"><?= htmlspecialchars($recipe['description'] ?? '') ?></span>
                </td>
                
                <!-- Category badge -->
                <td>
                  <span class="badge bg-light text-secondary border border-secondary border-opacity-10 py-1.5 px-2.5">
                    <?= htmlspecialchars($recipe['category_name']) ?>
                  </span>
                </td>
                
                <!-- Author -->
                <td class="text-secondary small">
                  <?= htmlspecialchars($recipe['author_name']) ?>
                </td>
                
                <!-- Created date -->
                <td class="text-secondary small">
                  <?= date('M d, Y', strtotime($recipe['created_at'])) ?>
                </td>
                
                <!-- Actions -->
                <td class="text-end">
                  <a 
                    href="recipes.php?action=edit&id=<?= (int)$recipe['id'] ?>" 
                    class="btn btn-sm btn-outline-warning rounded-3 me-1" 
                    title="Edit Recipe"
                  >
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a 
                    href="recipes.php?action=delete&id=<?= (int)$recipe['id'] ?>" 
                    class="btn btn-sm btn-outline-danger rounded-3" 
                    title="Delete Recipe"
                    onclick="return confirm('Are you sure you want to delete this recipe? This cannot be undone.');"
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

  <?php endif; ?>

</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
