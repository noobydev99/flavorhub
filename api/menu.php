<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/autoload.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\FoodDAO;

try {
    $db = Database::getConnection();
    $foodDAO = new FoodDAO($db);
    $category = trim($_GET['category'] ?? '');
    $foods = $foodDAO->getAll($category !== '' ? $category : null);
    echo json_encode($foods);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
