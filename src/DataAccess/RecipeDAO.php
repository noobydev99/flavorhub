<?php
namespace FlavorHub\DataAccess;

use PDO;

/**
 * Recipe Data Access Object (DataAccess Layer)
 * Implements SQL operations for the recipes table.
 */
class RecipeDAO {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Get all recipes with category and author details.
     */
    public function getAll(): array {
        $sql = "SELECT r.*, c.name as category_name, u.fullname as author_name 
                FROM recipes r 
                JOIN categories c ON r.category_id = c.id 
                JOIN users u ON r.user_id = u.id 
                ORDER BY r.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Find a recipe by its ID.
     */
    public function findById(int $id): ?array {
        $sql = "SELECT r.*, c.name as category_name, u.fullname as author_name 
                FROM recipes r 
                JOIN categories c ON r.category_id = c.id 
                JOIN users u ON r.user_id = u.id 
                WHERE r.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $recipe = $stmt->fetch();
        return $recipe ?: null;
    }

    /**
     * Create a new recipe.
     */
    public function create(
        string $title, 
        ?string $description, 
        string $ingredients, 
        string $instructions, 
        ?string $imageUrl, 
        int $categoryId, 
        int $userId,
        float $price
    ): int {
        $sql = "INSERT INTO recipes (title, description, ingredients, instructions, image_url, category_id, user_id, price) 
                VALUES (:title, :description, :ingredients, :instructions, :image_url, :category_id, :user_id, :price)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'title'        => $title,
            'description' => $description,
            'ingredients' => $ingredients,
            'instructions'=> $instructions,
            'image_url'   => $imageUrl,
            'category_id' => $categoryId,
            'user_id'     => $userId,
            'price'       => $price
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Update an existing recipe.
     */
    public function update(
        int $id, 
        string $title, 
        ?string $description, 
        string $ingredients, 
        string $instructions, 
        ?string $imageUrl, 
        int $categoryId,
        float $price
    ): bool {
        $sql = "UPDATE recipes 
                SET title = :title, description = :description, ingredients = :ingredients, 
                    instructions = :instructions, image_url = :image_url, category_id = :category_id, price = :price 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'title'        => $title,
            'description' => $description,
            'ingredients' => $ingredients,
            'instructions'=> $instructions,
            'image_url'   => $imageUrl,
            'category_id' => $categoryId,
            'price'       => $price,
            'id'          => $id
        ]);
    }

    /**
     * Delete a recipe by ID.
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM recipes WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Count total recipes.
     */
    public function countAll(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM recipes");
        return (int)$stmt->fetchColumn();
    }

    /**
     * Count recipes by Category ID.
     */
    public function countByCategoryId(int $categoryId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM recipes WHERE category_id = :category_id");
        $stmt->execute(['category_id' => $categoryId]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get recent recipes with limit.
     */
    public function getRecent(int $limit): array {
        $sql = "SELECT r.*, c.name as category_name 
                FROM recipes r 
                JOIN categories c ON r.category_id = c.id 
                ORDER BY r.created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
