<?php
namespace FlavorHub\DataAccess;

use PDO;

/**
 * Food Data Access Object (DataAccess Layer)
 * Implements operations for the frontend food catalog.
 */
class FoodDAO {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Get all food items, optionally filtered by category.
     */
    public function getAll(?string $category = null): array {
        if ($category) {
            $stmt = $this->db->prepare("SELECT id, name, category, description, ingredients, price, rating, reviews, image_url AS image FROM foods WHERE category = :category ORDER BY created_at DESC");
            $stmt->execute(['category' => $category]);
        } else {
            $stmt = $this->db->query("SELECT id, name, category, description, ingredients, price, rating, reviews, image_url AS image FROM foods ORDER BY created_at DESC");
        }
        return $stmt->fetchAll();
    }

    /**
     * Find a food item by its ID.
     */
    public function findById(string $id): ?array {
        $stmt = $this->db->prepare("SELECT id, name, category, description, ingredients, price, rating, reviews, image_url AS image FROM foods WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $food = $stmt->fetch();
        return $food ?: null;
    }
}
