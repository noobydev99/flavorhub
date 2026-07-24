<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/autoload.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\OrderDAO;

$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

if ($userId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'User ID is required.']);
    exit;
}

try {
    $db = Database::getConnection();
    $orderDAO = new OrderDAO($db);
    $orders = $orderDAO->getOrdersByUserId($userId);
    echo json_encode($orders);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
