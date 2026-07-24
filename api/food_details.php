<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/autoload.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\FoodDAO;

$id = trim($_GET['id'] ?? '');
if ($id === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Food ID is required.']);
    exit;
}

try {
    $db = Database::getConnection();
    $foodDAO = new FoodDAO($db);
    $food = $foodDAO->findById($id);
    if (!$food) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Food item not found.']);
        exit;
    }
    echo json_encode($food);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
