<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/autoload.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\OrderDAO;
use FlavorHub\DataAccess\UserDAO;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true);

$orderId = trim($payload['orderId'] ?? '');
$userEmail = trim($payload['customerEmail'] ?? '');
$items = $payload['items'] ?? [];
$subtotal = $payload['subtotal'] ?? null;
$tax = $payload['tax'] ?? null;
$deliveryFee = $payload['deliveryFee'] ?? null;
$total = $payload['total'] ?? null;
$deliveryDetails = $payload['deliveryDetails'] ?? [];

if ($orderId === '' || $userEmail === '' || !is_array($items) || empty($items) || $subtotal === null || $tax === null || $deliveryFee === null || $total === null) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid order payload.']);
    exit;
}

try {
    $db = Database::getConnection();
    $userDAO = new UserDAO($db);
    $user = $userDAO->findByEmail($userEmail);
    $userId = $user ? (int)$user['id'] : null;

    $orderData = [
        'order_id' => $orderId,
        'user_id' => $userId,
        'customer_name' => trim($deliveryDetails['fullName'] ?? ''),
        'customer_phone' => trim($deliveryDetails['phone'] ?? ''),
        'customer_address' => trim($deliveryDetails['address'] ?? ''),
        'payment_method' => trim($deliveryDetails['paymentMethod'] ?? ''),
        'special_instructions' => trim($deliveryDetails['instructions'] ?? ''),
        'subtotal' => $subtotal,
        'tax' => $tax,
        'delivery_fee' => $deliveryFee,
        'total' => $total,
        'status' => 'Order Received',
        'estimated_time' => '25-35 mins'
    ];

    $orderDAO = new OrderDAO($db);
    $newOrderId = $orderDAO->createOrder($orderData);
    $orderDAO->createOrderItems($newOrderId, $items);

    echo json_encode(['success' => true, 'orderId' => $orderId]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Unable to save order. ' . $e->getMessage()]);
}
