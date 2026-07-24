<?php
namespace FlavorHub\DataAccess;

use PDO;

/**
 * Comment Data Access Object (DataAccess Layer)
 * Implements SQL operations for the comments table.
 */
class CommentDAO {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Get all comments with recipe title.
     */
    public function getAll(): array {
        $sql = "SELECT c.*, r.title as recipe_title 
                FROM comments c 
                JOIN recipes r ON c.recipe_id = r.id 
                ORDER BY c.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Find a comment by ID.
     */
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM comments WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $comment = $stmt->fetch();
        return $comment ?: null;
    }

    /**
     * Create a new comment.
     */
    public function create(int $recipeId, ?int $userId, string $fullname, string $email, string $commentText): int {
        $sql = "INSERT INTO comments (recipe_id, user_id, fullname, email, comment_text, status) 
                VALUES (:recipe_id, :user_id, :fullname, :email, :comment_text, 'pending')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'recipe_id'    => $recipeId,
            'user_id'      => $userId,
            'fullname'     => $fullname,
            'email'        => $email,
            'comment_text' => $commentText
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Update comment approval status.
     */
    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE comments SET status = :status WHERE id = :id");
        return $stmt->execute([
            'status' => $status,
            'id'     => $id
        ]);
    }

    /**
     * Delete a comment by ID.
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Count all comments in the system.
     */
    public function countAll(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM comments");
        return (int)$stmt->fetchColumn();
    }

    /**
     * Count pending comments.
     */
    public function countPending(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM comments WHERE status = 'pending'");
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get recent comments for the dashboard.
     */
    public function getRecent(int $limit): array {
        $sql = "SELECT c.*, r.title as recipe_title 
                FROM comments c 
                JOIN recipes r ON c.recipe_id = r.id 
                ORDER BY c.created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
