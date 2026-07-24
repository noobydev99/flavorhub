<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/autoload.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\CategoryDAO;

try {
    $db = Database::getConnection();
    $categoryDAO = new CategoryDAO($db);
    
    $categories = $categoryDAO->getAll();
    
    echo json_encode($categories);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
