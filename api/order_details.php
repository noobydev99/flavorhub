<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/autoload.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\OrderDAO;

$orderId = trim($_GET['orderId'] ?? '');
if ($orderId === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Order ID is required.']);
    exit;
}

try {
    $db = Database::getConnection();
    $orderDAO = new OrderDAO($db);
    $order = $orderDAO->getOrderByOrderId($orderId);
    if (!$order) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Order not found.']);
        exit;
    }
    echo json_encode($order);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
