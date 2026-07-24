<?php
namespace FlavorHub\BusinessLogic;

use FlavorHub\DataAccess\CategoryDAO;
use FlavorHub\DataAccess\RecipeDAO;
use Exception;

/**
 * Category Service (Business Logic Layer)
 * Manages category CRUD and checks category rules.
 */
class CategoryService {
    private CategoryDAO $categoryDAO;
    private RecipeDAO $recipeDAO;

    public function __construct(CategoryDAO $categoryDAO, RecipeDAO $recipeDAO) {
        $this->categoryDAO = $categoryDAO;
        $this->recipeDAO = $recipeDAO;
    }

    /**
     * Create a new category.
     */
    public function addCategory(string $name, ?string $description = null): int {
        $name = trim($name);
        if ($name === '') {
            throw new Exception("Category name is required.");
        }

        if ($this->categoryDAO->findByName($name) !== null) {
            throw new Exception("A category with this name already exists.");
        }

        return $this->categoryDAO->create($name, $description);
    }

    /**
     * Update an existing category.
     */
    public function editCategory(int $id, string $name, ?string $description = null): bool {
        $name = trim($name);
        if ($name === '') {
            throw new Exception("Category name is required.");
        }

        $existing = $this->categoryDAO->findByName($name);
        if ($existing && (int)$existing['id'] !== $id) {
            throw new Exception("Another category with this name already exists.");
        }

        return $this->categoryDAO->update($id, $name, $description);
    }

    /**
     * Delete a category, validating that no recipes belong to it.
     */
    public function deleteCategory(int $id): bool {
        // Enforce business rule: Prevent category deletion if it contains active recipes
        $recipeCount = $this->recipeDAO->countByCategoryId($id);
        if ($recipeCount > 0) {
            throw new Exception("Cannot delete category. It currently contains {$recipeCount} active recipe(s).");
        }

        return $this->categoryDAO->delete($id);
    }

    /**
     * Retrieve all categories.
     */
    public function getAllCategories(): array {
        return $this->categoryDAO->getAll();
    }

    /**
     * Get category by ID.
     */
    public function getCategoryById(int $id): ?array {
        return $this->categoryDAO->findById($id);
    }

    /**
     * Get categories count.
     */
    public function getCategoryCount(): int {
        return $this->categoryDAO->countAll();
    }
}
