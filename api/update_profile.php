<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/autoload.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\UserDAO;
use FlavorHub\BusinessLogic\UserService;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true);

$id = isset($payload['id']) ? (int)$payload['id'] : 0;
$fullname = trim($payload['fullName'] ?? $payload['fullname'] ?? '');
$email = trim($payload['email'] ?? '');
$phone = trim($payload['phone'] ?? '');
$address = trim($payload['address'] ?? '');
$bio = trim($payload['bio'] ?? '');

if ($id <= 0 || $fullname === '' || $email === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'User ID, full name, and email are required.']);
    exit;
}

try {
    $db = Database::getConnection();
    $userDAO = new UserDAO($db);
    $userService = new UserService($userDAO);

    $success = $userService->updateProfile($id, $fullname, $email, $phone, $address, $bio);
    if ($success) {
        $updatedUser = $userDAO->findById($id);
        if ($updatedUser) {
            unset($updatedUser['password']);
            $updatedUser['fullName'] = $updatedUser['fullname'] ?? '';
            unset($updatedUser['fullname']);
            echo json_encode(['success' => true, 'user' => $updatedUser]);
            exit;
        }
    }
    echo json_encode(['success' => false, 'message' => 'Failed to update profile.']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
