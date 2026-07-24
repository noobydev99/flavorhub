<?php
namespace FlavorHub\DataAccess;

use PDO;

/**
 * User Data Access Object (DataAccess Layer)
 * Implements SQL operations for the users table.
 */
class UserDAO {
    private PDO $db;

    public function __construct(PDO $db) {
        $db->exec("SET NAMES 'utf8mb4'");
        $this->db = $db;
    }

    /**
     * Find a user by their email address.
     */
    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Find a user by their ID.
     */
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Create a new user account.
     */
    public function create(string $fullname, string $email, string $passwordHash, ?string $phone = null, ?string $address = null): int {
        $stmt = $this->db->prepare(
            "INSERT INTO users (fullname, email, password, phone, address) VALUES (:fullname, :email, :password, :phone, :address)"
        );
        $stmt->execute([
            'fullname' => $fullname,
            'email'    => $email,
            'password' => $passwordHash,
            'phone'    => $phone,
            'address'  => $address
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Update user profile information.
     */
    public function update(int $id, string $fullname, string $email, ?string $phone = null, ?string $address = null, ?string $bio = null): bool {
        $stmt = $this->db->prepare(
            "UPDATE users SET fullname = :fullname, email = :email, phone = :phone, address = :address, bio = :bio WHERE id = :id"
        );
        return $stmt->execute([
            'fullname' => $fullname,
            'email'    => $email,
            'phone'    => $phone,
            'address'  => $address,
            'bio'      => $bio,
            'id'       => $id
        ]);
    }

    /**
     * Update user password.
     */
    public function updatePassword(int $id, string $passwordHash): bool {
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
        return $stmt->execute([
            'password' => $passwordHash,
            'id'       => $id
        ]);
    }

    /**
     * Delete a user by ID.
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Retrieve all registered users.
     */
    public function getAll(): array {
        $stmt = $this->db->query("SELECT id, fullname, email, bio, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    /**
     * Count total users in the system.
     */
    public function countAll(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM users");
        return (int)$stmt->fetchColumn();
    }
}
