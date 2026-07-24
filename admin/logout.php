<?php
require_once __DIR__ . '/../config/autoload.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\UserDAO;
use FlavorHub\BusinessLogic\AuthService;

try {
    $db = Database::getConnection();
    $userDAO = new UserDAO($db);
    $authService = new AuthService($userDAO);
    $authService->logout();
} catch (Exception $e) {
    // If database connection fails, fall back to simple session destroy
    session_start();
    $_SESSION = [];
    session_destroy();
}

header('Location: ../index.php?success=Logged+out+successfully.');
exit;
