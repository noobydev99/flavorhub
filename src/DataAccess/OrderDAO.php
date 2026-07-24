<?php
namespace FlavorHub\DataAccess;

use PDO;

/**
 * Order Data Access Object (DataAccess Layer)
 * Implements order persistence and retrieval.
 */
class OrderDAO {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function createOrder(array $orderData): int {
        $stmt = $this->db->prepare("INSERT INTO orders (
            order_id, user_id, customer_name, customer_phone, customer_address,
            payment_method, special_instructions, subtotal, tax, delivery_fee, total, status, estimated_time
        ) VALUES (
            :order_id, :user_id, :customer_name, :customer_phone, :customer_address,
            :payment_method, :special_instructions, :subtotal, :tax, :delivery_fee, :total, :status, :estimated_time
        )");

        $stmt->execute([
            'order_id' => $orderData['order_id'],
            'user_id' => $orderData['user_id'],
            'customer_name' => $orderData['customer_name'],
            'customer_phone' => $orderData['customer_phone'],
            'customer_address' => $orderData['customer_address'],
            'payment_method' => $orderData['payment_method'],
            'special_instructions' => $orderData['special_instructions'],
            'subtotal' => $orderData['subtotal'],
            'tax' => $orderData['tax'],
            'delivery_fee' => $orderData['delivery_fee'],
            'total' => $orderData['total'],
            'status' => $orderData['status'],
            'estimated_time' => $orderData['estimated_time'],
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function createOrderItems(int $orderId, array $items): bool {
        $stmt = $this->db->prepare("INSERT INTO order_items (order_id, food_id, name, unit_price, quantity, total_price)
            VALUES (:order_id, :food_id, :name, :unit_price, :quantity, :total_price)");

        foreach ($items as $item) {
            $stmt->execute([
                'order_id' => $orderId,
                'food_id' => $item['id'],
                'name' => $item['name'],
                'unit_price' => $item['price'],
                'quantity' => $item['quantity'],
                'total_price' => $item['price'] * $item['quantity'],
            ]);
        }

        return true;
    }

    public function getOrdersByUserId(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        $orders = $stmt->fetchAll();

        foreach ($orders as &$order) {
            $order['items'] = $this->getOrderItems((int)$order['id']);
        }

        return $orders;
    }

    public function getOrderByOrderId(string $orderId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE order_id = :order_id LIMIT 1");
        $stmt->execute(['order_id' => $orderId]);
        $order = $stmt->fetch();
        if (!$order) {
            return null;
        }

        $order['items'] = $this->getOrderItems((int)$order['id']);
        return $order;
    }

    /**
     * Get order by database ID
     */
    public function getOrderById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch();
        if (!$order) {
            return null;
        }

        $order['items'] = $this->getOrderItems($id);
        return $order;
    }

    /**
     * Get all orders with items.
     */
    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM orders ORDER BY created_at DESC");
        $orders = $stmt->fetchAll();

        foreach ($orders as &$order) {
            $order['items'] = $this->getOrderItems((int)$order['id']);
        }

        return $orders;
    }

    /**
     * Update order status.
     */
    public function updateStatus(int $orderId, string $status): bool {
        $stmt = $this->db->prepare("UPDATE orders SET status = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $orderId]);
    }

    private function getOrderItems(int $orderId): array {
        $stmt = $this->db->prepare("SELECT food_id AS id, name, unit_price, quantity, total_price FROM order_items WHERE order_id = :order_id");
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll();
    }
}
