<?php
namespace FlavorHub\BusinessLogic;

use FlavorHub\DataAccess\UserDAO;
use Exception;

/**
 * User Service (Business Logic Layer)
 * Manages user CRUD actions, inputs validation, and business rule enforcement.
 */
class UserService {
    private UserDAO $userDAO;

    public function __construct(UserDAO $userDAO) {
        $this->userDAO = $userDAO;
    }

    /**
     * Register a new user with input validation.
     */
    public function register(string $fullname, string $email, string $password, ?string $phone = null, ?string $address = null): int {
        $fullname = trim($fullname);
        $email    = trim($email);

        if ($fullname === '' || $email === '' || $password === '') {
            throw new Exception("Please fill in all required fields.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Please provide a valid email address.");
        }

        if (strlen($password) < 6) {
            throw new Exception("Password must be at least 6 characters long.");
        }

        if ($this->userDAO->findByEmail($email) !== null) {
            throw new Exception("An account with that email already exists.");
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        return $this->userDAO->create($fullname, $email, $passwordHash, $phone, $address);
    }

    /**
     * Update user profile information.
     */
    public function updateProfile(int $id, string $fullname, string $email, ?string $phone = null, ?string $address = null, ?string $bio = null): bool {
        $fullname = trim($fullname);
        $email    = trim($email);

        if ($fullname === '' || $email === '') {
            throw new Exception("Full Name and Email are required.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Please provide a valid email address.");
        }

        // Check email uniqueness, excluding current user
        $existing = $this->userDAO->findByEmail($email);
        if ($existing && (int)$existing['id'] !== $id) {
            throw new Exception("Email address is already in use by another account.");
        }

        return $this->userDAO->update($id, $fullname, $email, $phone, $address, $bio);
    }

    /**
     * Change user password.
     */
    public function changePassword(int $id, string $currentPassword, string $newPassword, string $confirmPassword): bool {
        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            throw new Exception("Please fill in all password fields.");
        }

        if ($newPassword !== $confirmPassword) {
            throw new Exception("New password and confirmation password do not match.");
        }

        if (strlen($newPassword) < 6) {
            throw new Exception("New password must be at least 6 characters long.");
        }

        $user = $this->userDAO->findById($id);
        if (!$user) {
            throw new Exception("User not found.");
        }

        if (!password_verify($currentPassword, $user['password'])) {
            throw new Exception("Current password is incorrect.");
        }

        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->userDAO->updatePassword($id, $newPasswordHash);
    }

    /**
     * Delete user, preventing self-deletion.
     */
    public function deleteUser(int $targetUserId, int $currentUserId): bool {
        if ($targetUserId === $currentUserId) {
            throw new Exception("Security violation: You cannot delete your own account while logged in.");
        }

        return $this->userDAO->delete($targetUserId);
    }

    /**
     * Get all users.
     */
    public function getAllUsers(): array {
        return $this->userDAO->getAll();
    }

    /**
     * Get user count.
     */
    public function getUserCount(): int {
        return $this->userDAO->countAll();
    }

    /**
     * Get user profile by ID.
     */
    public function getUserById(int $id): ?array {
        return $this->userDAO->findById($id);
    }
}
