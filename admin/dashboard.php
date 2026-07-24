<?php
require_once __DIR__ . '/includes/header.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\RecipeDAO;
use FlavorHub\DataAccess\CategoryDAO;
use FlavorHub\DataAccess\CommentDAO;
use FlavorHub\DataAccess\UserDAO;
use FlavorHub\BusinessLogic\RecipeService;
use FlavorHub\BusinessLogic\CategoryService;
use FlavorHub\BusinessLogic\CommentService;
use FlavorHub\BusinessLogic\UserService;
use FlavorHub\Integration\FileUploader;

try {
    $db = Database::getConnection();

    $recipeDAO   = new RecipeDAO($db);
    $categoryDAO = new CategoryDAO($db);
    $commentDAO  = new CommentDAO($db);
    $userDAO     = new UserDAO($db);

    $recipeService   = new RecipeService($recipeDAO, new FileUploader());
    $categoryService = new CategoryService($categoryDAO, $recipeDAO);
    $commentService  = new CommentService($commentDAO);
    $userService     = new UserService($userDAO);

    // Read Counts
    $recipeCount        = $recipeService->getRecipeCount();
    $categoryCount      = $categoryService->getCategoryCount();
    $userCount          = $userService->getUserCount();
    $pendingComments    = $commentService->getPendingCommentCount();

    // Read Lists
    $recentRecipes  = $recipeService->getRecentRecipes(5);
    $recentComments = $commentService->getRecentComments(5);
} catch (Exception $e) {
    echo '<div class="alert alert-danger">Error loading dashboard: ' . htmlspecialchars($e->getMessage()) . '</div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
?>

<style>
    body {
        font-family: 'Outfit', sans-serif;
    }
    .heading-font {
        font-family: 'Outfit', sans-serif;
    }
</style>

<!-- Dashboard Container -->
<div class="space-y-8 p-4 lg:p-8">
    
    <!-- Header Section -->
    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="heading-font text-3xl font-extrabold text-slate-900">Dashboard Overview</h1>
            <p class="text-slate-600 text-sm mt-1">Real-time statistics and recent recipes.</p>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        
        <!-- Card: Total Recipes -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-slate-200/80 p-6 flex items-center gap-5 transition-all hover:border-slate-300 hover:shadow-sm">
            <div class="p-4 bg-yellow-50 rounded-xl text-yellow-600">
                <i class="bi bi-journal-bookmark-fill text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-600">Total Recipes</p>
                <h4 class="text-3xl font-bold text-slate-900 mt-1"><?= $recipeCount ?></h4>
            </div>
            <div class="absolute -right-4 -bottom-4 text-slate-200/40 text-7xl font-bold select-none"><i class="bi bi-journal-bookmark-fill"></i></div>
        </div>

        <!-- Card: Categories -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-slate-200/80 p-6 flex items-center gap-5 transition-all hover:border-slate-300 hover:shadow-sm">
            <div class="p-4 bg-emerald-50 rounded-xl text-emerald-600">
                <i class="bi bi-tags-fill text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-600">Categories</p>
                <h4 class="text-3xl font-bold text-slate-900 mt-1"><?= $categoryCount ?></h4>
            </div>
            <div class="absolute -right-4 -bottom-4 text-slate-200/40 text-7xl font-bold select-none"><i class="bi bi-tags-fill"></i></div>
        </div>

        <!-- Card: Admin Users -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-slate-200/80 p-6 flex items-center gap-5 transition-all hover:border-slate-300 hover:shadow-sm">
            <div class="p-4 bg-indigo-50 rounded-xl text-indigo-600">
                <i class="bi bi-people-fill text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-600">Users</p>
                <h4 class="text-3xl font-bold text-slate-900 mt-1"><?= $userCount ?></h4>
            </div>
            <div class="absolute -right-4 -bottom-4 text-slate-200/40 text-7xl font-bold select-none"><i class="bi bi-people-fill"></i></div>
        </div>

        <!-- Card: Pending Reviews -->
        <div class="relative overflow-hidden rounded-2xl bg-white border border-slate-200/80 p-6 flex items-center gap-5 transition-all hover:border-slate-300 hover:shadow-sm">
            <div class="p-4 bg-rose-50 rounded-xl text-rose-600">
                <i class="bi bi-chat-left-dots-fill text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-600">Pending Reviews</p>
                <h4 class="text-3xl font-bold text-slate-900 mt-1"><?= $pendingComments ?></h4>
            </div>
            <div class="absolute -right-4 -bottom-4 text-slate-200/40 text-7xl font-bold select-none"><i class="bi bi-chat-left-dots-fill"></i></div>
        </div>

    </div>

    <!-- Main Content Panels -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        
        <!-- Recent Recipes Table -->
        <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                <h3 class="heading-font text-lg font-bold text-slate-900">Recent Recipe Uploads</h3>
                <a href="recipes.php" class="text-indigo-600 hover:text-indigo-700 text-xs font-semibold flex items-center gap-1 transition-colors">
                    View All <i class="bi bi-arrow-right"></i>
                </a>
            </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-slate-600 border-b border-slate-200">
                    <th class="pb-3 font-semibold">Recipe</th>
                    <th class="pb-3 font-semibold">Category</th>
                        <th class="pb-3 font-semibold">Created</th>
                    </tr>
                </thead>
            <tbody class="divide-y divide-slate-200/50">
        <?php if (empty($recentRecipes)): ?>
            <tr>
                <td colspan="3" class="py-8 text-center text-slate-500">No recipes uploaded yet.</td>
            </tr>
        <?php else: ?>
             <?php foreach ($recentRecipes as $recipe): ?>
            <tr class="group text-slate-700 hover:text-slate-900 transition-colors hover:bg-slate-50">
                <td class="py-4 flex items-center gap-3">
                    <div class="h-12 w-12 rounded-lg bg-slate-200 overflow-hidden shrink-0 border border-slate-300/50">
                        <?php if (!empty($recipe['image_path'])): ?>
                        <img src="<?= htmlspecialchars($recipe['image_path']) ?>" class="h-full w-full object-cover transition-transform group-hover:scale-110" alt="Recipe" onerror="this.onerror=null; this.src='https://placehold.co/100x100/e2e8f0/64748b?text=Recipe';">
                        <?php else: ?>
                        <img src="https://placehold.co/100x100/e2e8f0/64748b?text=Recipe" alt="No Image">
                                <?php endif; ?>
            </div>
         <div class="min-w-0">
        <p class="font-semibold truncate max-w-[200px]"><?= htmlspecialchars($recipe['title'] ?? '') ?></p>
        <p class="text-xs text-slate-500 truncate max-w-[200px]">
             <?php
            $created = $recipe['created_at'] ?? '';
             if ($created) {
            $date = new DateTime($created);
            $now = new DateTime();
            $diff = $now->diff($date);
                if ($diff->days == 0) {
                echo $diff->h > 0 ? $diff->h . 'h ago' : $diff->i . 'm ago';
                    } else {
             echo $diff->days . 'd ago';
            }
        }
         ?>
            </p>
        </div>
    </td>
        <td class="py-4 text-slate-600">
            <?= htmlspecialchars($recipe['category_name'] ?? 'Uncategorized') ?>
        </td>
        <td class="py-4 text-slate-500 text-xs">
        <?php
         $created = $recipe['created_at'] ?? '';
         if ($created) {
     echo date('M d, Y', strtotime($created));
        }
        ?>
        </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
                </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Comments Sidebar -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                <h3 class="heading-font text-lg font-bold text-slate-900">Recent Comments</h3>
                <a href="comments.php" class="text-indigo-600 hover:text-indigo-700 text-xs font-semibold flex items-center gap-1 transition-colors">
                    Inbox <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="space-y-4">
                <?php if (empty($recentComments)): ?>
                    <div class="py-8 text-center text-slate-500 text-sm">No comments yet.</div>
    <?php else: ?>
        <?php foreach ($recentComments as $comment): ?>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition-all flex flex-col gap-2">
            <div class="flex items-center justify-between">
        <span class="font-semibold text-slate-900 text-sm truncate max-w-[120px]"><?= htmlspecialchars($comment['commenter_name'] ?? 'Anonymous') ?></span>
    <span class="text-slate-500 text-[10px]">
      <?php
        $created = $comment['created_at'] ?? '';
            if ($created) {
                $date = new DateTime($created);
                    $now = new DateTime();$diff = $now->diff($date);
                        if ($diff->days == 0) {
    echo $diff->h > 0 ? $diff->h . 'h' : $diff->i . 'm';
        } else {
        echo $diff->days . 'd';
            }
                }
             ?>
                </span>
                        </div>
               <p class="text-indigo-600 text-xs font-semibold truncate">Recipe Comment</p>
            <p class="text-slate-600 text-xs line-clamp-2 mt-0.5 leading-relaxed"><?= htmlspecialchars(substr($comment['comment_text'] ?? '', 0, 100)) ?></p>
        </div>
              <?php endforeach; ?>
          <?php endif; ?>
        </div>                                                                                                                                         
    </div>

   </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
