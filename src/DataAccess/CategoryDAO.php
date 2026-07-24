<?php
namespace FlavorHub\DataAccess;

use PDO;

/**
 * Category Data Access Object (DataAccess Layer)
 * Implements SQL operations for the categories table.
 */
class CategoryDAO {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Get all categories.
     */
    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    /**
     * Find a category by ID.
     */
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $category = $stmt->fetch();
        return $category ?: null;
    }

    /**
     * Find a category by Name.
     */
    public function findByName(string $name): ?array {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE name = :name LIMIT 1");
        $stmt->execute(['name' => $name]);
        $category = $stmt->fetch();
        return $category ?: null;
    }

    /**
     * Create a new recipe category.
     */
    public function create(string $name, ?string $description = null): int {
        $stmt = $this->db->prepare("INSERT INTO categories (name, description) VALUES (:name, :description)");
        $stmt->execute([
            'name'        => $name,
            'description' => $description
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Update an existing category.
     */
    public function update(int $id, string $name, ?string $description = null): bool {
        $stmt = $this->db->prepare("UPDATE categories SET name = :name, description = :description WHERE id = :id");
        return $stmt->execute([
            'name'        => $name,
            'description' => $description,
            'id'          => $id
        ]);
    }

    /**
     * Delete a category.
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Count total categories.
     */
    public function countAll(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM categories");
        return (int)$stmt->fetchColumn();
    }
}
