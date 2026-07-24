<?php
namespace FlavorHub\BusinessLogic;

use FlavorHub\DataAccess\OrderDAO;
use Exception;

/**
 * Order Service (Business Logic Layer)
 * Handles order management operations.
 */
class OrderService {
    private OrderDAO $orderDAO;

    public function __construct(OrderDAO $orderDAO) {
        $this->orderDAO = $orderDAO;
    }

    /**
     * Get all orders
     */
    public function getAllOrders(): array {
        try {
            return $this->orderDAO->getAll();
        } catch (Exception $e) {
            throw new Exception("Failed to fetch orders: " . $e->getMessage());
        }
    }

    /**
     * Get order by ID
     */
    public function getOrderById(int $id): ?array {
        try {
            return $this->orderDAO->getOrderById($id);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch order: " . $e->getMessage());
        }
    }

    /**
     * Get orders by user ID
     */
    public function getOrdersByUserId(int $userId): array {
        try {
            return $this->orderDAO->getOrdersByUserId($userId);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch user orders: " . $e->getMessage());
        }
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(int $orderId, string $status): bool {
        try {
            return $this->orderDAO->updateStatus($orderId, $status);
        } catch (Exception $e) {
            throw new Exception("Failed to update order status: " . $e->getMessage());
        }
    }
}
