<?php
namespace FlavorHub\BusinessLogic;

use FlavorHub\DataAccess\RecipeDAO;
use FlavorHub\Integration\FileUploader;
use Exception;

/**
 * Recipe Service (Business Logic Layer)
 * Validates recipe operations and coordinates with FileUploader.
 */
class RecipeService {
    private RecipeDAO $recipeDAO;
    private FileUploader $uploader;

    public function __construct(RecipeDAO $recipeDAO, FileUploader $uploader) {
        $this->recipeDAO = $recipeDAO;
        $this->uploader  = $uploader;
    }

    /**
     * Create a new recipe.
     */
    public function addRecipe(
        string $title, 
        ?string $description, 
        string $ingredients, 
        string $instructions, 
        ?array $fileArray, 
        int $categoryId, 
        int $userId,
        float $price
    ): int {
        $title        = trim($title);
        $ingredients  = trim($ingredients);
        $instructions = trim($instructions);

        if ($title === '' || $ingredients === '' || $instructions === '') {
            throw new Exception("Title, Ingredients, and Instructions are required fields.");
        }

        if ($categoryId <= 0) {
            throw new Exception("Please select a valid category.");
        }

        $imageUrl = null;
        if ($fileArray && isset($fileArray['tmp_name']) && $fileArray['tmp_name'] !== '') {
            $imageUrl = $this->uploader->uploadImage($fileArray);
        }

        return $this->recipeDAO->create(
            $title, 
            $description, 
            $ingredients, 
            $instructions, 
            $imageUrl, 
            $categoryId, 
            $userId,
            $price
        );
    }

    /**
     * Update an existing recipe.
     */
    public function editRecipe(
        int $id, 
        string $title, 
        ?string $description, 
        string $ingredients, 
        string $instructions, 
        ?array $fileArray, 
        int $categoryId,
        float $price
    ): bool {
        $title        = trim($title);
        $ingredients  = trim($ingredients);
        $instructions = trim($instructions);

        if ($title === '' || $ingredients === '' || $instructions === '') {
            throw new Exception("Title, Ingredients, and Instructions are required fields.");
        }

        $recipe = $this->recipeDAO->findById($id);
        if (!$recipe) {
            throw new Exception("Recipe not found.");
        }

        $imageUrl = $recipe['image_url'];
        if ($fileArray && isset($fileArray['tmp_name']) && $fileArray['tmp_name'] !== '') {
            // Upload new image
            $newImageUrl = $this->uploader->uploadImage($fileArray);
            
            // Delete old image file if it exists
            if ($imageUrl) {
                $oldPath = __DIR__ . '/../../' . $imageUrl;
                if (file_exists($oldPath) && is_file($oldPath)) {
                    unlink($oldPath);
                }
            }
            $imageUrl = $newImageUrl;
        }

        return $this->recipeDAO->update($id, $title, $description, $ingredients, $instructions, $imageUrl, $categoryId, $price);
    }

    /**
     * Delete recipe and its uploaded image.
     */
    public function deleteRecipe(int $id): bool {
        $recipe = $this->recipeDAO->findById($id);
        if (!$recipe) {
            return false;
        }

        $deleted = $this->recipeDAO->delete($id);
        if ($deleted && $recipe['image_url']) {
            $path = __DIR__ . '/../../' . $recipe['image_url'];
            if (file_exists($path) && is_file($path)) {
                unlink($path);
            }
        }
        return $deleted;
    }

    /**
     * Fetch all recipes.
     */
    public function getAllRecipes(): array {
        return $this->recipeDAO->getAll();
    }

    /**
     * Get recipe by ID.
     */
    public function getRecipeById(int $id): ?array {
        return $this->recipeDAO->findById($id);
    }

    /**
     * Get total recipes count.
     */
    public function getRecipeCount(): int {
        return $this->recipeDAO->countAll();
    }

    /**
     * Get recent recipes.
     */
    public function getRecentRecipes(int $limit): array {
        return $this->recipeDAO->getRecent($limit);
    }
}
