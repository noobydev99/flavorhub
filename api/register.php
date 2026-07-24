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
$fullname = trim($payload['fullName'] ?? '');
$email = trim($payload['email'] ?? '');
$password = $payload['password'] ?? '';
$phone = trim($payload['phone'] ?? '');
$address = trim($payload['address'] ?? '');

if ($fullname === '' || $email === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Full name, email, and password are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
    exit;
}

try {
    $db = Database::getConnection();
    $userService = new UserService(new UserDAO($db));
    $userId = $userService->register($fullname, $email, $password, $phone, $address);
    $user = [
        'id' => $userId,
        'fullName' => $fullname,
        'email' => $email,
        'phone' => $phone,
        'address' => $address
    ];
    echo json_encode(['success' => true, 'user' => $user]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
